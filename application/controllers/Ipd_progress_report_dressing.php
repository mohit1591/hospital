<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_progress_report_dressing extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_model','ipd_progress_report_dressing');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('114','699');
        $data['page_title'] = 'Dressing List'; 
        $this->load->view('ipd_progress_report_dressing/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('114','699');
        $users_data = $this->session->userdata('auth_users');

        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');

        $list = $this->ipd_progress_report_dressing->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_dressing) {
         // print_r($ipd_progress_report_dressing);die;
            $no++;
            $row = array();
            if($ipd_progress_report_dressing->status==1)
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
            if($users_data['parent_id']==$ipd_progress_report_dressing->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_dressing->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $ipd_progress_report_dressing->dressing;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_dressing->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$ipd_progress_report_dressing->branch_id)
            {
              if(in_array('701',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_ipd_progress_report_dressing('.$ipd_progress_report_dressing->id.');" class="btn-custom" href="javascript:void(0)" style="'.$ipd_progress_report_dressing->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('702',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_ipd_progress_report_dressing('.$ipd_progress_report_dressing->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_dressing->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_dressing->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('114','700');
        $data['page_title'] = "Add Dressing";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dressing'=>"",
                                  'status'=>"1",
                                 
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_dressing->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_dressing/add',$data);       
    }
     // -> function to find gender according to selected ipd_progress_report_dressing
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         $ipd_progress_report_dressing_id = $this->input->post('ipd_progress_report_dressing_id');
         $data='';
          if(!empty($ipd_progress_report_dressing_id)){
               $result = $this->general_model->find_gender($ipd_progress_report_dressing_id);
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
     unauthorise_permission('114','701');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->ipd_progress_report_dressing->get_by_id($id);  
        $data['page_title'] = "Update Dressing";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'dressing'=>$result['dressing'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->ipd_progress_report_dressing->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('ipd_progress_report_dressing/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('dressing', 'dressing', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'dressing'=>$post['dressing'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('114','702');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->ipd_progress_report_dressing->delete($id);
           $response = "Dressing successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('114','702');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_dressing->deleteall($post['row_id']);
            $response = "Dressings successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->ipd_progress_report_dressing->get_by_id($id);  
        $data['page_title'] = $data['form_data']['ipd_progress_report_dressing']." detail";
        $this->load->view('ipd_progress_report_dressing/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('114','703');
        $data['page_title'] = 'Dressing Archive List';
        $this->load->helper('url');
        $this->load->view('ipd_progress_report_dressing/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('114','703');
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_archive_model','ipd_progress_report_dressing_archive'); 

      
               $list = $this->ipd_progress_report_dressing_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $ipd_progress_report_dressing) {
         // print_r($ipd_progress_report_dressing);die;
            $no++;
            $row = array();
            if($ipd_progress_report_dressing->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$ipd_progress_report_dressing->id.'">'.$check_script; 
            $row[] = $ipd_progress_report_dressing->dressing;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($ipd_progress_report_dressing->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('705',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_ipd_progress_report_dressing('.$ipd_progress_report_dressing->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('704',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$ipd_progress_report_dressing->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->ipd_progress_report_dressing_archive->count_all(),
                        "recordsFiltered" => $this->ipd_progress_report_dressing_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('114','705');
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_archive_model','ipd_progress_report_dressing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_dressing_archive->restore($id);
           $response = "Dressing successfully restore in Dressing list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('114','705');
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_archive_model','ipd_progress_report_dressing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_dressing_archive->restoreall($post['row_id']);
            $response = "Dressings successfully restore in Dressings list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('114','704');
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_archive_model','ipd_progress_report_dressing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->ipd_progress_report_dressing_archive->trash($id);
           $response = "Dressing successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('114','704');
        $this->load->model('ipd_progress_report_dressing/ipd_progress_report_dressing_archive_model','ipd_progress_report_dressing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->ipd_progress_report_dressing_archive->trashall($post['row_id']);
            $response = "Dressings successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  

}
?>