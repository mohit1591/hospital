<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class procedure_history extends CI_Controller {

    private $gender = ["Female", "Male", "Other"];
    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('procedure_history/procedure_history_model','procedure_history');
        $this->load->library('form_validation');
        $this->load->model('operation_summary/operation_summary_model','operation_summary');
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
    }

    public function index()
    {  
        unauthorise_permission(10,50);
        $data['page_title'] = 'Procedure History'; 
        $this->load->view('procedure_history/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(10,50);
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
    
       
            $list = $this->procedure_history->get_datatables();
     
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $religion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            // if($religion->status==1)
            // {
            //     $status = '<font color="green">Active</font>';
            // }   
            // else{
            //     $status = '<font color="red">Inactive</font>';
            // } 
            
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
            if($users_data['parent_id']==$religion->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$religion->id.'">'.$check_script; 
             }else{
               $row[]='';
             }
            $row[] = $religion->patient_name; 
            $row[] = $religion->booking_code; 
            $row[] = $religion->patient_code; 
            $row[] = $religion->mobile_no;  
            $row[] = $this->gender[$religion->gender];  
            $row[] = $religion->name;
            $row[] = date('d-M-Y H:i A',strtotime($religion->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
            $btn_print_ot_summary = "";
         
            if($users_data['parent_id']==$religion->branch_id){
              
                 if(in_array('52',$users_data['permission']['action'])) 
                 {
                    $print_procedure_url =  "'".base_url('operation_summary/print_procedure_note_summary/'.$religion->ot_booking_id).'/'.$religion->id."'";  
                
                $btn_print_ot_summary .='<a onClick="return print_window_page('.$print_procedure_url.')"  href="javascript:void(0)" style="'.$ot->id.'" class="btn-custom" title="Print Procedure History"><i class="fa fa-print" aria-hidden="true"></i> Print</a>';
                    
                    $btnedit = ' <a href="'.base_url('procedure_history/edit/').$religion->id.'" class="btn-custom" style="'.$religion->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                 }
                 if(in_array('53',$users_data['permission']['action'])) 
                 {
                    $btndelete = ' <a class="btn-custom" onClick="return delete_religion('.$religion->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                 } 
            }

            // End Action Button //
            
    
             $row[] = $btn_print_ot_summary.$btnedit.$btndelete;
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->procedure_history->count_all(),
                        "recordsFiltered" => $this->procedure_history->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add($ot_id="")
    {
        unauthorise_permission(10,51); 
        $data['page_title'] = "Add Procedure History";  
        $post = $this->input->post();
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $result = $this->otbooking->get_by_id($ot_id); 
        $users_data = $this->session->userdata('auth_users');
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'religion'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   

            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->procedure_history->save();
                echo json_encode(array('st'=>1, 'message'=>"Record Inserted Successfully" ));
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $this->load->model('procedure_note_summary/procedure_note_summary_model','prosummary');
        $data['ot_summary_template'] = $this->prosummary->ot_summary_list();
        $this->load->model('general/general_model');
        $this->load->model('procedure_data/procedure_data_model','pd_list');
        $data['procedure_data'] = $this->pd_list->procedure_data_list();
        $this->load->model('procedure_note_tab_setting/procedure_note_tab_setting_model','procedure_note');
        $data['procedure_note_tab_setting_list'] = $this->procedure_note->get_setting();
        $data['ot_booking_list'] = $this->db->select("booking_code")->where('is_deleted','0')->get('hms_operation_booking')->result_array();
        $data['simulation_list'] = $this->general_model->simulation_list(); 
        $data['medicine_data'] = [];
        $data['data']['booking_type'] = 0;
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $summary_data=$this->operation_summary->get_data_by_id($ot_id,$result['patient_id'],$users_data['parent_id']);
        // $data['summary_data']=$summary_data;
			$data['rec_id']="";
			$data['diagnosis']=$summary_data['diagnosis'];
			$data['op_findings']=$summary_data['op_findings'];
			$data['procedures']=$summary_data['procedures'];
			$data['pos_op_orders']=$summary_data['pos_op_orders'];
			
			$data['indication_of_surgery']=$summary_data['indication_of_surgery'];
			$data['type_of_anaesthesia']=$summary_data['type_of_anaesthesia'];
			$data['name_of_anaesthetist']=$summary_data['name_of_anaesthetist'];
			$data['operation_start_time']=$summary_data['operation_start_time'];
			$data['operation_finish_time']=$summary_data['operation_finish_time'];
			$data['post_operative_period'] = $summary_data['post_operative_period'];
            $this->load->model('ot_booking/ot_booking_model','otbooking');  
            $data['doctor_list'] =$this->otbooking->doctor_list_by_otids($ot_id);
        if(!empty($ot_id)){
            $data['data'] = $this->get_ot_details($ot_id);
        } else {
            $data['data'][''] = [];
        }
        $data['data']=$result;
       $this->load->view('procedure_history/add',$data);        
    }
    
    public function edit($id="")
    {
        //         ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);

      unauthorise_permission(10,52); 
      $users_data = $this->session->userdata('auth_users');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->procedure_history->get_by_id($id);  
        $data['summary_data'] = $result;
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        $data['data'] = $this->otbooking->get_by_id($result['ot_booking_id']);
        $data['page_title'] = "Update Procedure History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'religion'=>$result['religion'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            
                $this->procedure_history->update($id);
                echo json_encode(array('st'=>1, 'message'=>"Record Inserted Successfully" ));
                return true;
                
        }
        $this->load->model('procedure_note_summary/procedure_note_summary_model','prosummary');
        $data['ot_summary_template'] = $this->prosummary->ot_summary_list();
        $this->load->model('general/general_model');
        $this->load->model('procedure_data/procedure_data_model','pd_list');
        $data['procedure_data'] = $this->pd_list->procedure_data_list();
        $this->load->model('procedure_note_tab_setting/procedure_note_tab_setting_model','procedure_note');
        $data['procedure_note_tab_setting_list'] = $this->procedure_note->get_setting();
        $data['ot_booking_list'] = $this->db->select("booking_code")->where('is_deleted','0')->get('hms_operation_booking')->result_array();
        $data['simulation_list'] = $this->general_model->simulation_list(); 
        $data['medicine_data'] = [];
        // $data['data']['booking_type'] = 0;
        $data['ot_procedures'] = get_ot_procedure_list();
        $data['post_observations'] = get_post_operative_observations();
        $data['doctor_list'] = $this->procedure_history->procedure_doctor_list_by_otids($id);
        $data['medicine_data']=$this->procedure_history->get_procedure_summary_medicine_data($id,$users_data['parent_id']);
        $data['rec_id'] = $id;

        $data['diagnosis']=$result['diagnosis'];
        $data['op_findings']=$result['op_findings'];
        $data['procedures']=$result['procedures'];
        $data['pos_op_orders']=$result['pos_op_orders'];
        
        $data['indication_of_surgery']=$result['indication_of_surgery'];
        $data['type_of_anaesthesia']=$result['type_of_anaesthesia'];
        $data['name_of_anaesthetist']=$result['name_of_anaesthetist'];
        $data['operation_start_time']=$result['operation_start_time'];
        $data['operation_finish_time']=$result['operation_finish_time'];
        $data['post_operative_period'] = $result['post_operative_period'];
        $this->load->view('procedure_history/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('booking_code', 'booking_code', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'religion'=>$post['religion'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(10,53); 
       if(!empty($id) && $id>0)
       {
           $result = $this->procedure_history->delete($id);
           $response = "Procedure History successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(10,53); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->procedure_history->deleteall($post['row_id']);
            $response = "Religions successfully deleted.";
            echo $response;
        }
    }
 


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(10,54); 
        $data['page_title'] = 'Religion Archive List';
        $this->load->helper('url');
        $this->load->view('procedure_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(10,54);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('procedure_history/religion_archive_model','religion_archive'); 

        

               $list = $this->religion_archive->get_datatables();
              
            
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $religion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            if($religion->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$religion->id.'">'.$check_script; 
            $row[] = $religion->religion;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($religion->created_date)); 
 
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";

            if(in_array('56',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_religion('.$religion->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            if(in_array('55',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$religion->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_delete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->religion_archive->count_all(),
                        "recordsFiltered" => $this->religion_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        $this->load->model('procedure_history/religion_archive_model','religion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->religion_archive->restore($id);
           $response = "Religion successfully restore in Religion list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        $this->load->model('procedure_history/religion_archive_model','religion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->religion_archive->restoreall($post['row_id']);
            $response = "Religions successfully restore in Religion list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        $this->load->model('procedure_history/religion_archive_model','religion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->religion_archive->trash($id);
           $response = "Religion successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        $this->load->model('procedure_history/religion_archive_model','religion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->religion_archive->trashall($post['row_id']);
            $response = "Religion successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function religion_dropdown()
  {
      $religion_list = $this->procedure_history->religion_list();
      $dropdown = '<option value="">Select Religion</option>'; 
      if(!empty($religion_list))
      {
        foreach($religion_list as $religion)
        {
           $dropdown .= '<option value="'.$religion->id.'">'.$religion->religion.'</option>';
        }
      } 
      echo $dropdown; 
  }
 
  function check_unique_value($religion, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->procedure_history->check_unique_value($users_data['parent_id'], $religion,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Religion already exist.');
            $response = false;
        }
        return $response;
      }
   
    public function get_ot_details($ot_id="") {
        $post = $this->input->post();
        $this->load->model('ot_booking/ot_booking_model','otbooking');      
        $this->load->model('general/general_model');
        if(!empty($ot_id)) {
            $result = $this->otbooking->get_by_id($ot_id,"");  
            return $result;
        } else {
            $result = $this->otbooking->get_by_id("",$post['booking_code']); 
            echo json_encode($result); 
        }
    }

}
?>