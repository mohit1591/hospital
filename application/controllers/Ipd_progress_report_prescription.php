<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_progress_report_prescription extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_model','ipd_progress_report_prescription');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('113','692');
        $data['page_title'] = 'Progress Report List'; 
        $this->load->view('ipd_progress_report_prescription/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('113','692');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ipd_progress_report_prescription->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_prescription) {
         // print_r($ipd_progress_report_prescription);die;
            $no++;
            $row = array();
            if($ipd_progress_report_prescription->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$ipd_progress_report_prescription->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_prescription->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_progress_report_prescription->prescription;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_prescription->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_progress_report_prescription->branch_id)
            {
              if(in_array('694',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_progress_report_prescription('.$ipd_progress_report_prescription->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_progress_report_prescription->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('695',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_progress_report_prescription('.$ipd_progress_report_prescription->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_prescription->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_prescription->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('113','693');
        $data['page_title'] = "Add Progress Report";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'prescription'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_prescription->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_prescription/add',$data);       
    }
     // -> function to find gender according to selected ipd_progress_report_prescription
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_progress_report_prescription_id = $this->input->post('ipd_progress_report_prescription_id');
         $data='';
          if(!empty($ipd_progress_report_prescription_id)){
               $result = $this->general_model->find_gender($ipd_progress_report_prescription_id);
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
     unauthorise_permission('113','694');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_progress_report_prescription->get_by_id($id);  
        $data['page_title'] = "Update Progress Report Prescription";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'prescription'=>$result['prescription'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_prescription->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_prescription/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('prescription', 'prescription', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prescription'=>$post['prescription'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('113','695');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_progress_report_prescription->delete($id);
           $response = "Prescription successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('113','695');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_prescription->deleteall($post['row_id']);
            $response = "Prescriptions successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_progress_report_prescription->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_progress_report_prescription']." detail";
        $this->load->view('ipd_progress_report_prescription/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('113','696');
        $data['page_title'] = 'Prescription Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_progress_report_prescription/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('113','696');
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_archive_model','ipd_progress_report_prescription_archive'); 

      
               $list = $this->ipd_progress_report_prescription_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_prescription) {
         // print_r($ipd_progress_report_prescription);die;
            $no++;
            $row = array();
            if($ipd_progress_report_prescription->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_prescription->id.'">'.$check_script; 
            $row[] = $ipd_progress_report_prescription->prescription;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_prescription->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('698',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_progress_report_prescription('.$ipd_progress_report_prescription->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('697',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_progress_report_prescription->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_prescription_archive->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_prescription_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('113','698');
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_archive_model','ipd_progress_report_prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_prescription_archive->restore($id);
           $response = "Prescription successfully restore in Prescription list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('113','698');
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_archive_model','ipd_progress_report_prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_prescription_archive->restoreall($post['row_id']);
            $response = "Prescriptions successfully restore in Prescription list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('113','697');
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_archive_model','ipd_progress_report_prescription_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_prescription_archive->trash($id);
           $response = "Prescription successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('113','697');
        $this->load->model('ipd_progress_report_prescription/ipd_progress_report_prescription_archive_model','ipd_progress_report_prescription_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_prescription_archive->trashall($post['row_id']);
            $response = "Prescription successfully deleted parmanently.";
            echo $response;
        }
    }
   

}
?>