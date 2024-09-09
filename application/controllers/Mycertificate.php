<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mycertificate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('mycertificate/Mycertificate_model','doctor_certificate');
        $this->load->model('patient/patient_model','patient');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        //echo "xyz";die;
        unauthorise_permission('171','990');
        $data['page_title'] = 'Patient Certificates List'; 
        $this->load->view('mycertificate/list',$data);
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
            $row[] = $ipd_room_type->patient_name;
            $row[] = $ipd_room_type->certi_name;
            
        
           // $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($ipd_room_type->created_date)); 
            $btnview='';
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_room_type->branch_id)
            {
              if(in_array('996',$users_data['permission']['action']))
              {
                $print_url = "'".base_url('certificate/prints/'.$ipd_room_type->id)."'";
                $btnview = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print"  data-url="512"><i class="fa fa-print"></i> Print</a>';
              }
              
             
                    $btndelete = ' <a class="btn-custom" onClick="return delete_certificate_type('.$ipd_room_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
            
            }
          
             $row[] = $btnview.$btndelete;
             
        
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
        $dropdown .= '</select></div>';
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
		//echo "<pre>";print_r($data['form_data']); exit;
		$this->load->view('certificate/print_certificate_data',$data);
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

  public function prints($id)
  {
      $get_by_id_data = $this->doctor_certificate->get_print_data($id);
      $data['all_detail']= $get_by_id_data;
      $data['template'] = $data['all_detail'][0]->template;
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
   
   public function view($id="")
    {  

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->doctor_certificate->get_by_id($id);  
     //   $data['page_title'] = $data['form_data']." detail";
        $this->load->view('mycertificate/view',$data);     
      }
    }
    
    public function delete($id="")
    {
        //unauthorise_permission(85,525);
       if(!empty($id) && $id>0)
       {
           $result = $this->doctor_certificate->delete($id);
           $response = "Certificate successfully deleted.";
           echo $response;
       }
    }


}
?>