<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('users/users_model','users');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(5,22);
        $data['page_title'] = 'Users List'; 
        $this->load->view('users/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(5,22);
        $users_data = $this->session->userdata('auth_users');
        
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
        
            $list = $this->users->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $users) { 
            $no++;
            $row = array();
            if($users->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($users->state))
            {
                $state = " ( ".ucfirst(strtolower($users->state))." )";
            }
            //////////////////////// 
            
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
             
            if($users_data['parent_id']==$users->parent_id){
               $row[] = '<input type="checkbox" name="users[]" class="checklist" value="'.$users->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $users->reg_no;
            $row[] = $users->emp_type;
            $row[] = '<a href="javascript:void(0)" onClick="users_details('.$users->emp_id.')">'.$users->name.'</a>';
            $row[] = $users->username; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($users->created_date)); 
            
            //Action button /////
            $btn_edit = "";
            $btn_permission = "";
            $btn_delete = "";
            if($users_data['parent_id']==$users->parent_id){
                 if(in_array('24',$users_data['permission']['action'])) 
                 {
                    $btn_edit = ' <a onClick="return edit_users('.$users->id.');" class="btn-custom" href="javascript:void(0)" style="'.$users->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                 }
                 if(in_array('25',$users_data['permission']['action'])) 
                 {
                    $btn_delete = ' <a class="btn-custom" onClick="return delete_users('.$users->id.')" href="javascript:void(0)" title="Delete"><i class="fa fa-trash"></i> Delete</a>';
                 }
                 if(in_array('130',$users_data['permission']['action'])) 
                 {
                     $btn_permission = ' <a class="btn-custom" href="'.base_url("users/permission/").$users->id.'" title="Permission"><i class="fa fa-expeditedssl"></i> Permission</a>';
                 } 
            }
            // End Action Button //

            $row[] = $btn_edit.$btn_delete.$btn_permission;                  
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->users->count_all(),
                        "recordsFiltered" => $this->users->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {  
        unauthorise_permission(5,23);
        $data['page_title'] = "Add User"; 
        $data['total_users'] = get_total_user(5,23);
        $post = $this->input->post();
        $this->load->model('employee/employee_model','employee');
        $data['type_list'] = $this->employee->employee_type_list();
        $data['role_list'] = $this->users->user_role_list();
        $data['doctor_list'] = $this->users->referal_doctor_list();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"",
                                  'emp_type_id'=>"",
                                  'emp_id'=>"",
                                  'users_role'=>"2",
                                  'username'=>"",
                                  'password'=>"",
                                  'cpassword'=>"",
                                  'email'=>"",
                                  'status'=>"1",
                                  'record_access'=>'',
                                   'collection_type'=>''
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            $data['emp_list'] = $this->users->type_to_employee($post['emp_type_id']);
            if($this->form_validation->run() == TRUE)
            {
                $this->users->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('users/add',$data);       
    }
    
    public function edit($id="")
    {  
      unauthorise_permission(5,24);
       $data['total_users'] = '1';
     if(isset($id) && !empty($id) && is_numeric($id))
      {     
        $result = $this->users->get_by_id($id); 
        $this->load->model('general/general_model'); 
        $data['type_list'] = $this->users->employee_type_list();  
        $data['page_title'] = "Update Users";  
        $post = $this->input->post();
        $data['emp_list'] = $this->users->type_to_employee($result['emp_type_id'],$id);
        $data['role_list'] = $this->users->user_role_list();
        $data['doctor_list'] = $this->users->referal_doctor_list();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'emp_type_id'=>$result['emp_type_id'],
                                  'emp_id'=>$result['emp_id'],
                                  'users_role'=>$result['users_role'],
                                  'username'=>$result['username'],
                                  'password'=>'',
                                  'cpassword'=>"",
                                  'email'=>$result['email'],
                                  'status'=>$result['status'],
                                  'record_access'=>$result['record_access'],
                                   'collection_type'=>$result['collection_type'],
                                  ); 
        
        if(isset($post) && !empty($post))
        {   
            $data['emp_list'] = $this->users->type_to_employee($post['emp_type_id'],$id);
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->users->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        
        $assigned_doctor_data = $this->users->users_to_doctors($id);
        //echo "<pre>"; print_r($assigned_doctor_data); exit;
        
        if(!empty($assigned_doctor_data))
        {
          $assigned_doctor=array();
          foreach($assigned_doctor_data as $doc_data)
          {
            array_push($assigned_doctor, $doc_data->doctor_id);
          }
          
        }
        else
        {
          $assigned_doctor=array();
        }
        //echo "<pre>"; print_r($assigned_doctor); exit;
        $data['assigned_doctor'] =  $assigned_doctor;
        $this->load->view('users/add',$data);       
      }  
    }
      
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('emp_type_id', 'employee type', 'trim|required');
        $this->form_validation->set_rules('emp_id', 'employee', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email');
        $this->form_validation->set_rules('username', 'username', 'trim|alpha_numeric|required|min_length[4]|max_length[15]|callback_check_username'); 
        if((!empty($post['password']) || !empty($post['cpassword'])) && $post['data_id']>0)
        {
          $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[15]');
          $this->form_validation->set_rules('cpassword', 'confirm password', 'trim|required|min_length[6]|max_length[15]|matches[password]'); 
        } 
        else if($post['data_id']==0 || empty($post['data_id']))
        {
            $this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[15]');
          $this->form_validation->set_rules('cpassword', 'confirm password', 'trim|required|min_length[6]|max_length[15]|matches[password]');  
        }
        
        if ($this->form_validation->run() == FALSE) 
        {   
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'emp_type_id'=>$post['emp_type_id'],
                                        'users_role'=>$post['users_role'],
                                        'emp_id'=>$post['emp_id'],
                                        'username'=>$post['username'],
                                        'password'=>$post['password'],
                                        'cpassword'=>$post['cpassword'],
                                        'email'=>$post['email'],
                                        'status'=>$post['status'],
                                        'record_access'=>$post['record_access'],
                                        'collection_type'=>$post['collection_type'],
                                       ); 
            return $data['form_data'];
        }   
    }
    
    public function check_username($str)
    {

      $this->load->model('general/general_model','general');
      $post = $this->input->post();
      if(empty($str))
      {
         $this->form_validation->set_message('check_username', 'The username field is required.');
         return false;
      }

        $userdata = $this->general->check_username($str,$post['data_id']);
        if(empty($userdata))
        {
           return true;
        }
        else
        {
          $this->form_validation->set_message('check_username', 'Username already exists.');
          return false;
        }
    }

    public function check_email($str)
    {
      $this->load->model('general/general_model','general');
      $post = $this->input->post();
      if(empty($str))
      {
         $this->form_validation->set_message('check_email', 'The email field is required.');
         return false;
      }

        $userdata = $this->general->check_email($str,$post['data_id']); 
        if(empty($userdata))
        {
           return true;
        }
        else
        { 
        $this->form_validation->set_message('check_email', 'Email already exists.');
        return false;
        }
    }

    public function delete($id="")
    {
      unauthorise_permission(5,25);
       if(!empty($id) && $id>0)
       {
           $result = $this->users->delete($id);
           $response = "User successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(5,25);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->users->deleteall($post['row_id']);
            $response = "User successfully deleted.";
            echo $response;
        }
    }


    public function view($id="")
    {  
      unauthorise_permission(5,28);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->users->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('users/view',$data);     
      }
    }  


    ///// users Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(5,26);
        $data['page_title'] = 'Users archive list';
        $this->load->helper('url');
        $this->load->view('users/archive',$data);
    }

    public function archive_ajax_list()
    { 
        unauthorise_permission(5,26);
        $this->load->model('users/users_archive_model','users_archive');
        $users_data = $this->session->userdata('auth_users'); 
      

               $list = $this->users_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $users) { 
            $no++;
            $row = array();
            if($users->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($users->state))
            {
                $state = " ( ".ucfirst(strtolower($users->state))." )";
            }
            //////////////////////// 
            
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
             

           $row[] = '<input type="checkbox" name="users[]" class="checklist" value="'.$users->id.'">'.$check_script;
            $row[] = $users->reg_no;
            $row[] = $users->emp_type;
            $row[] = '<a href="javascript:void(0)" onClick="users_details('.$users->emp_id.')">'.$users->name.'</a>';
            $row[] = $users->username; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($users->created_date));  
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('41',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_users('.$users->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore</a>';
            } 
            if(in_array('27',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash_users('.$users->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //

            $row[] = $btn_restore.$btn_delete;            
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->users_archive->count_all(),
                        "recordsFiltered" => $this->users_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(5,41);
       $this->load->model('users/users_archive_model','users_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->users_archive->restore($id);
           $response = "Users successfully restore in Users list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(5,41);
        $this->load->model('users/users_archive_model','users_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->users_archive->restoreall($post['row_id']);
            $response = "Users successfully restore in Users list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       unauthorise_permission(5,27);
       $this->load->model('users/users_archive_model','users_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->users_archive->trash($id);
           $response = "Users successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(5,27);
        $this->load->model('users/users_archive_model','users_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->users_archive->trashall($post['row_id']);
            $response = "Users plans successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// users Archive end  ///////////////
    
    public function type_to_employee($type_id="",$data_id="")
   {

      if(!empty($type_id) && $type_id > 0)
      {
        
           $this->session->set_userdata('emp_type_id',$type_id);
        $dropdown = '<option value="">Select employee</option>';
        $result = $this->users->type_to_employee($type_id,$data_id);
        if(!empty($result))
        {
          foreach($result as $employee)
          {
            $dropdown .= '<option value="'.$employee->id.'">'.$employee->name.'</option>';
          }
        }
        echo $dropdown;
      }
   }


   public function permission($users_id="")
   {
      unauthorise_permission(5,130);
      if(!empty($users_id) && $users_id>0)
      {
         auth_branch_users($users_id);
         $post = $this->input->post();
         if(!empty($post))
         {
           $this->users->save_users_permission($users_id);
           echo 'User permission successfully assigned.';
           return false;
           //echo "<pre>"; print_r($post);die;
         }
         $data['users_id'] = $users_id;
         $data['page_title'] = "User Permission";
         $data['section_list'] = $this->users->permission_section_list($users_id);
         $this->load->view('users/permission',$data);
      }
   }


   


}
?>