<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_management extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('plan_management/Plan_management_model','plan_mgmt');
        $this->load->library('form_validation');
      
        
    }

    public function index()
    { 
      
        unauthorise_permission('395','2406');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        $data['page_title'] = 'Plane Management List'; 
        $this->load->view('plan_management/list',$data);
    }

    public function ajax_list()
    { 
         unauthorise_permission('395','2406');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
       
        $users_data = $this->session->userdata('auth_users');
        $list = $this->plan_mgmt->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $plan_mgmt) {
         // print_r($plan_mgmt);die;
            $no++;
            $row = array();
            if($plan_mgmt->status==1)
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
                              })</script>
                              
                              ";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$plan_mgmt->id.'">'.$check_script; 
            $row[] = $plan_mgmt->plan_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($plan_mgmt->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          //if(in_array('427',$users_data['permission']['action'])){
          if(in_array('2402',$permission_action) || in_array('395',$permission_section)){
               $btnedit = ' <a onClick="return edit_previous_history('.$plan_mgmt->id.');" class="btn-custom" href="javascript:void(0)" style="'.$plan_mgmt->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('428',$users_data['permission']['action'])){
          if(in_array('2404',$permission_action) || in_array('395',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_previous_history('.$plan_mgmt->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->plan_mgmt->count_all(),
                        "recordsFiltered" => $this->plan_mgmt->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('395','2402');

        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }

        $data['page_title'] = "Add Plane Management";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'plan_name'=>"",
                                  'plan_text'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->plan_mgmt->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('plan_management/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('395','2402');

        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->plan_mgmt->get_by_id($id);  
        $data['page_title'] = "Update Plane Management";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'plan_name'=>$result['plan_name'], 
                                  'plan_text'=>$result['plan_text'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->plan_mgmt->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('plan_management/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('plan_name', 'Plane Management', 'trim|required|callback_check_unique_value['.$id.']'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'plan_name'=>$post['plan_name'], 
                                        'plan_text'=>$post['plan_text'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->plan_mgmt->delete($id);
           $response = "Plane Management successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->plan_mgmt->deleteall($post['row_id']);
            $response = "Plane Management successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->plan_mgmt->get_by_id($id);  
        $data['page_title'] = $data['form_data']['plan_mgmt']." detail";
        $this->load->view('plan_management/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $data['page_title'] = 'Plane Management Archive List';
        $this->load->helper('url');
        $this->load->view('plan_management/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('plan_management/plan_management_archive_model','plan_mgmt_archive'); 

        $list = $this->plan_mgmt_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $plan_mgmt) { 
            $no++;
            $row = array();
            if($plan_mgmt->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$plan_mgmt->id.'">'.$check_script; 
            $row[] = $plan_mgmt->plan_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($plan_mgmt->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('431',$users_data['permission']['action'])){
          if(in_array('2404',$permission_action) || in_array('395',$permission_section)){
               $btnrestore = ' <a onClick="return restore_previous_history('.$plan_mgmt->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('430',$users_data['permission']['action'])){
          if(in_array('2404',$permission_action) || in_array('395',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$plan_mgmt->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->plan_mgmt_archive->count_all(),
                        "recordsFiltered" => $this->plan_mgmt_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $this->load->model('plan_management/plan_management_archive_model','plan_mgmt_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->plan_mgmt_archive->restore($id);
           $response = "Plane Management successfully restore in Plane Management List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $this->load->model('plan_management/plan_management_archive_model','plan_mgmt_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->plan_mgmt_archive->restoreall($post['row_id']);
            $response = "Plane Management successfully restore in Plane Management List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $this->load->model('plan_management/plan_management_archive_model','plan_mgmt_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->plan_mgmt_archive->trash($id);
           $response = "Plane Management successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('395','2404');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        $this->load->model('plan_management/plan_management_archive_model','plan_mgmt_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->plan_mgmt_archive->trashall($post['row_id']);
            $response = "Plane Management successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function plan_mgmt_dropdown()
  {

      $plan_mgmt_list = $this->plan_mgmt->plan_mgmt_list();
      $dropdown = '<option value="">Select plan_mgmt</option>'; 
      if(!empty($plan_mgmt_list))
      {
        foreach($plan_mgmt_list as $plan_mgmt)
        {
           $dropdown .= '<option value="'.$plan_mgmt->id.'">'.$plan_mgmt->plan_mgmt.'</option>';
        }
      } 
      echo $dropdown; 
  }

// op 19/08/19
  function check_unique_value($prev_history,$id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->plan_mgmt->check_unique_value($users_data['parent_id'], $prev_history,$id);
        if($result == 0)
        {
            $response = true;
        }
        else {
            $this->form_validation->set_message('check_unique_value', 'Plane Management already exist.');
            $response = false;
        }
        return $response;
    }

}
?>