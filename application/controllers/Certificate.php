<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Certificate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('certificate/Certificate_type_model','doctor_certificate');
          $this->load->model('patient/patient_model','patient');
       // $this->load->model('ipd_patient/ipd_patient_model','ipd_patient');
        $this->load->library('form_validation');
    }

    public function index()
    {  
     //echo "xyz";die;
        unauthorise_permission('171','990');
        $data['page_title'] = 'Certificate'; 
        $this->load->view('certificate/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('171','990');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->doctor_certificate->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_type) {
         // print_r($ipd_room_type);die;
            $no++;
            $row = array();
           
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$ipd_room_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_room_type->title;
        
           // $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_room_type->created_date)); 
            $btnview='';
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_room_type->branch_id)
            {
              if(in_array('996',$users_data['permission']['action'])){
               $btnview =' <a onClick="return view_certificate_type('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_room_type->id.'" title="View"><i class="fa fa-pencil" aria-hidden="true"></i> View</a>';
              }
              if(in_array('992',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_certificate_type('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_room_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('993',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_certificate_type('.$ipd_room_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnview.$btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->doctor_certificate->count_all(),
                        "recordsFiltered" => $this->doctor_certificate->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('171','991');
        $data['page_title'] = "Add Certificate";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'title'=>"",
                                  'template'=>"",
                                    'template_header' => "",
                                  
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->doctor_certificate->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('certificate/add',$data);       
    }
    
    public function find_gender()
    {
         $this->load->model('general/general_model'); 
         $ipd_room_type_id = $this->input->post('ipd_room_type_id');
         $data='';
          if(!empty($ipd_room_type_id)){
               $result = $this->general_model->find_gender($ipd_room_type_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = ""; 
                  $others=""; 
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else if($result['gender']==0)
                  {
                     $female = 'checked="checked"';
                  } 
                  else if($result['gender']==2){
                    $others = 'checked="checked"';
                  }
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female &nbsp;
                          <input type="radio" name="gender" '.$others.' value="2" /> Others ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     unauthorise_permission('171','992');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->doctor_certificate->get_by_id($id);  
       // print_r($result);
        $data['page_title'] = "Update Certificate";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'title'=>$result['title'], 
                                  'template'=>$result['template'],
                                  'template_header' => $result['template_header'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->doctor_certificate->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('certificate/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('title', 'title', 'trim|required'); 
      
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'title'=>$post['title'], 
                                        'template'=>$post['template'],
                                       
                                     ); 
            return $data['form_data'];
        }   
    }
    public function check_room_type($str){
 
          $post = $this->input->post();
          if(!empty($post['room_type']))
          {
               $this->load->model('general/general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    return true;
               }
               else
               {
                    $room_data = $this->general->check_room_type($post['room_type']);
                    if(empty($room_data))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('check_room_type', 'The room category already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('check_room_type', 'The  room category field is required.');
               return false; 
          } 
     }
    public function delete($id="")
    {
       unauthorise_permission('171','993');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->doctor_certificate->delete($id);
           $response = "Certificate is successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('171','995');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctor_certificate->deleteall($post['row_id']);
            $response = "Certificate is successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->doctor_certificate->get_by_id($id);  
     //   $data['page_title'] = $data['form_data']." detail";
        $this->load->view('certificate/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('171','994');
        $data['page_title'] = 'Certificate Archive List';
        $this->load->helper('url');
        $this->load->view('certificate/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('171','994');
        $this->load->model('certificate/Certificate_type_archive_model','doctor_certificate_type_archive'); 

      
               $list = $this->doctor_certificate_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_room_type) {
         // print_r($ipd_room_type);die;
            $no++;
            $row = array();
           
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_room_type->id.'">'.$check_script; 
            $row[] = $ipd_room_type->title; 
            //$row[] = $ipd_room_type->template; 
         //   $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_room_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('997',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_certificate_type('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('995',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_room_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->doctor_certificate_type_archive->count_all(),
                        "recordsFiltered" => $this->doctor_certificate_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('171','997');
        $this->load->model('certificate/Certificate_type_archive_model','doctor_certificate_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->doctor_certificate_type_archive->restore($id);
           $response = "Certificate successfully restore in Certificate list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('171','997');
        $this->load->model('certificate/Certificate_type_archive_model','doctor_certificate_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctor_certificate_type_archive->restoreall($post['row_id']);
            $response = "Certificate successfully restore in Certificate list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('171','995');
        $this->load->model('certificate/Certificate_type_archive_model','doctor_certificate_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->doctor_certificate_type_archive->trash($id);
           $response = "Certificate successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('171','995');
        $this->load->model('certificate/Certificate_type_archive_model','doctor_certificate_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->doctor_certificate_type_archive->trashall($post['row_id']);
            $response = "Certificate successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function ipd_room_type_dropdown()
  {
     $ipd_room_type_list = $this->ipd_room_type->ipd_room_type_list();
     $dropdown = '<option value="">Select ipd_room_type</option>'; 
     $ipd_room_types_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
     
     if(!empty($ipd_room_type_list))
     {
          foreach($ipd_room_type_list as $ipd_room_type)
          {
               if(in_array($ipd_room_type->ipd_room_type,$ipd_room_types_array)){
                    $selected_ipd_room_type = 'selected="selected"';
               }
               else
               {
                  $selected_ipd_room_type = '';  
               }
               $dropdown .= '<option value="'.$ipd_room_type->id.'" '.$selected_ipd_room_type.' >'.$ipd_room_type->ipd_room_type.'</option>';
          }
     } 
     echo $dropdown; 
  }

  public function generate()
  {
		unauthorise_permission('171','1013');
		$data['page_title'] = 'Generate Certificate';
		$data['ipd_patient_data'] = $this->doctor_certificate->get_ipd_patient();
		$data['template_data'] = $this->doctor_certificate->get_template_data();
		// print_r($data['ipd_patient_data']);die;
		$this->load->view('certificate/generate_certificate_master',$data);
  }

   public function get_ipd_patient_list()
   {
      $result = $this->doctor_certificate->get_ipd_patient();
   //  print_r($result);die;
     // $result = $this->test_price->get_transactional_doctors_list();
      $dropdown = '<div class="col-xs-3"><label>Patient</label> </div><div class="col-xs-3"> <select  id="patientid" name="patient_id"><option value="">Select Patient</option>';
       if(!empty($result))
       { //echo count($result);die;
          foreach($result as $val){
                $dropdown .= '<option value="'.$val['id'].'">'.$val['patient_name'].'</option>';
                   
          } 
       } 
        $dropdown .= '</select></div>';
        echo $dropdown;
   }
   public function get_opd_patient_list()
   {
      $result = $this->doctor_certificate->get_opd_patient();
   //  print_r($result);die;
     // $result = $this->test_price->get_transactional_doctors_list();
      $dropdown = '<div class="col-xs-3"><label>Patient</label></div><div class="col-xs-3"> <select  id="patientid" name="patient_id"><option value="">Select Patient</option>';
       if(!empty($result))
       { //echo count($result);die;
          foreach($result as $val){
                $dropdown .= '<option value="'.$val['id'].'">'.$val['patient_name'].'</option>';
                   
          } 
       } 
        $dropdown .= '</select></div><script>$("#patientid").select2();</script>';
        echo $dropdown;
   }


   public function get_ot_patient_list()
   {
      $result = $this->doctor_certificate->get_ot_patient();
   	  //print_r($result); exit;
   	  $dropdown = '<div class="col-xs-3"><label>Patient</label> </div><div class="col-xs-3"><select  id="patientid" name="patient_id"><option value="">Select Patient</option>';
       if(!empty($result))
       { //echo count($result);die;
          foreach($result as $val){
                $dropdown .= '<option value="'.$val['id'].'">'.$val['patient_name'].'</option>';
                   
          } 
       } 
        $dropdown .= '</select></div>';
        echo $dropdown;
   }

   public function get_pathology_patient_list()
   {
      $result = $this->doctor_certificate->get_pathology_patient();
   	  $dropdown = '<div class="col-xs-3"><label>Patient</label></div><div class="col-xs-3"> <select  id="patientid" name="patient_id"><option value="">Select Patient</option>';
       if(!empty($result))
       { //echo count($result);die;
          foreach($result as $val){
                $dropdown .= '<option value="'.$val['id'].'">'.$val['patient_name'].'</option>';
                   
          } 
       } 
        $dropdown .= '</select></div>';
        echo $dropdown;
   }
   public function list_patient_certificate_old($patient_id='',$certificate_id='')
   { 
       	$data['template_data'] = $this->doctor_certificate->get_template_data_by_patient_id($certificate_id);
        $data['form_data'] = $this->doctor_certificate->get_patient_data($patient_id);
        
         $this->load->view('certificate/print_certificate_data',$data);
        //$this->load->view('certificate/print_certificate',$data);  
   }

   public function list_patient_certificate()
   { 
		$get = $this->input->get();
		$users_data = $this->session->userdata('auth_users');
		$branch_list= $this->session->userdata('sub_branches_data');
		$parent_id= $users_data['parent_id'];
        
		$branch_ids = array_column($branch_list, 'id'); 
		$data['branch_collection_list'] = [];
		$data['branch_detail']=$users_data;
		$data['company_data']=$this->session->userdata('company_data');

		$data['template_data'] = $this->doctor_certificate->get_template_data_by_patient_id($get);
		$data['patient_id'] = $get['patient_id'];
		$data['type'] = $get['type'];
		$data['certificate_id'] = $get['certificate_id'];
		$data['form_data'] = $this->doctor_certificate->get_patient_data($get);
        $data['form_data']['prescription'] = $this->getPrescriptionDiagnosis($get['patient_id']);
        $data['form_data']['doctor_name'] = $this->getPatientDoctor($get['patient_id']);
        $data['date_of_death'] = "";
        $data['time_of_death'] = "";
        $data['cause_of_death'] = "";
        $data['birth_time'] = "";
        $data['birth_date'] = "";
        $data['birth_weight'] = "";
        if($get['type'] == 0) {
            $data['booking'] = $this->db->where('patient_id',$get['patient_id'])->get('hms_opd_booking')->row_array();
            
            $data['date_of_admission'] = $data['booking']['booking_date'];
            $data['diagnosis'] = $this->db->where('booking_id',$data['booking']['id'])->get('hms_opd_prescription')->row_array()['diagnosis'];
            // $data['his_her'] = get_simulation_name($data['form_data']['simulation_id']);

        } else if($get['type'] == 1) {
            $data['booking'] = $this->db->where('patient_id',$get['patient_id'])->get('hms_ipd_booking')->row_array();
            $data['diagnosis'] = $data['booking']['diagnosis'];
            $data['date_of_admission'] = $data['booking']['admission_date'];
            $data['time_of_admission'] = $data['booking']['admission_time'];
            $discharge = $this->db->where('patient_id',$get['patient_id'])->where('ipd_id',$data['booking']['id'])->get('hms_ipd_patient_discharge_summery')->row_array();
            $data['date_of_death'] = $discharge['death_date'];
            $data['time_of_death'] = $discharge['death_time'];
            $data['cause_of_death'] = $discharge['cause_of_death'];
            // $data['his_her'] = get_simulation_name($data['form_data']['simulation_id']);
            $born = $this->db->where('patient_id',$get['patient_id'])->where('ipd_id',$data['booking']['id'])->get('hms_born_summery')->row_array();
            $data['birth_time'] = $born['born_time'];
            $data['birth_date'] = $born['birth_date'];
            $data['birth_weight'] = $born['weight'];

        } else if($get['type'] == 2) {
            $data['booking_data'] = $this->db->where('patient_id',$get['patient_id'])->get('hms_operation_booking')->row_array();
            $data['diagnosis'] = $data['booking_data']['diagnosis'];
        } else if($get['type'] == 3) {
            $data['booking_data'] = $this->db->where('patient_id',$get['patient_id'])->get('path_test_booking')->row_array();
            $data['diagnosis'] = $data['booking_data']['diagnosis'];
        }
		// echo "<pre>";print_r($data['form_data']); exit; 
		$this->load->view('certificate/print_certificate_data',$data);
   }

   public function getPatientDoctor($patient_id)
   {
        $data = [];
        $data = $this->db->where('patient_id',$patient_id)
        ->select("hms_doctors.*")
        ->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor')
        ->get('hms_opd_booking')
        ->result_array();
        if(empty($data)) {
            $data = $this->db->where('patient_id',$patient_id)
            ->select("hms_doctors.*")
            ->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id')
            ->get('hms_ipd_booking')
            ->result_array();
        }
        return empty($data) ? "" : $data[0]['doctor_name'];
   }

   public function getPrescriptionDiagnosis($patient_id)
   {
    $prescription = [];
    $prescription = $this->db->where('patient_id',$patient_id)->get('hms_opd_prescription')->result_array();
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_dental_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_eye_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_gynecology_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_pediatrician_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_ipd_patient_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_ipd_patient_nursing_prescription')->result_array();
    }
    if(empty($prescription))
    {
        $prescription = $this->db->where('patient_id',$patient_id)->get('hms_ipd_patient_admission_prescription')->result_array();
    }
    return $prescription[0];
   }

  public function get_patient_list()
  {
      $value= $this->input->post('value');
      $data_array= array('id'=>$value);
      $opd_list = $this->patient->template_list($data_array);
      $data['template']= $opd_list['template'];
     // $data['short_code']= $opd_list['short_code'];
      echo json_encode($data);
  }

  public function print_final_certificate()
  {
  	$post = $this->input->post();
	$certificateid = $this->doctor_certificate->save_certificate();
	//$this->session->set_userdata('certificateid',$certificateid);
	echo  $certificateid; 
	//redirect(base_url('certificate/print?status=print'));
  	//$data['template'] = $post['template'];
  	 //$htnk = $this->load->view('certificate/print_certificate',$data,true);
  	 //echo $htnk; exit;
  }

  public function prints($id, $flag="")
  {
      $get_by_id_data = $this->doctor_certificate->get_print_data($id);
      $data['all_detail']= $get_by_id_data;
      $data['template'] = $data['all_detail'][0]->template;
      $data['template_header'] = $data['all_detail'][0]->template_header;
      $data['flag'] = $flag;
      $this->load->view('certificate/print_certificate',$data);
  }
  
  
    public function get_module_patient_list()
   {
       $post= $this->input->post();
       
      $result = $this->doctor_certificate->get_module_patient_list($post);
    //print_r($result);die;
     // $result = $this->test_price->get_transactional_doctors_list();
      $dropdown = '<div class="col-xs-3"><label>Patient</label></div><div class="col-xs-3"> <select  id="patientid" name="patient_id"><option value="">Select Patient</option>';
       if(!empty($result))
       { //echo count($result);die;
          foreach($result as $val){
                $dropdown .= '<option value="'.$val['id'].'">'.$val['patient_name'].' ('.$val['patient_code'].')</option>';
                   
          } 
       } 
        $dropdown .= '</select></div>';
        echo $dropdown;
   }

}
?>