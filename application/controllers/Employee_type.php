<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('employee_type/employee_type_model','employee_type','Employee_archive_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(7,30);  
        $data['page_title'] = 'Employee Type List'; 
        $this->load->view('employee_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(7,30);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->employee_type->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $employee_type) {
         // print_r($employee_type);die;
            $no++;
            $row = array();
            if($employee_type->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($employee_type->state))
            {
                $state = " ( ".ucfirst(strtolower($employee_type->state))." )";
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
            if($users_data['parent_id']==$employee_type->branch_id){ 
                $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$employee_type->id.'">'.$check_script; 
            }else{
                $row[]='';
            }
            $row[] = $employee_type->emp_type;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($employee_type->created_date)); 
 
            //Action button /////
            $btnedit = ""; 
            $btndelete = "";
         
             if($users_data['parent_id']==$employee_type->branch_id){
                if(in_array('32',$users_data['permission']['action'])) 
                {
                   $btnedit = ' <a onClick="return edit_employee_type('.$employee_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$employee_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
                }
                if(in_array('33',$users_data['permission']['action'])) 
                {
                   $btndelete = ' <a class="btn-custom" onClick="return delete_emp_type('.$employee_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                } 
            }
            // End Action Button //

             $row[] = $btnedit.$btndelete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee_type->count_all(),
                        "recordsFiltered" => $this->employee_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission(7,31);
        $data['page_title'] = "Add Employee Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'emp_type'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->employee_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('employee_type/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission(7,32);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->employee_type->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->employee_type->employee_type_list();  
        $data['page_title'] = "Update Employee";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'emp_type'=>$result['emp_type'],
 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->employee_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('employee_type/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('emp_type', 'employee type', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'emp_type'=>$post['emp_type'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission(7,33);
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_type->delete($id);
           $response = "Employee Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(7,33);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_type->deleteall($post['row_id']);
            $response = "Employee Types successfully deleted.";
            echo $response;
        }
    }

     


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(7,34);
        $data['page_title'] = 'Employee Type Archive List';
        $this->load->helper('url');
        $this->load->view('employee_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(7,34);
        $this->load->model('employee_type/employee_type_archive_model','employee_type_archive'); 
        $users_data = $this->session->userdata('auth_users');
        

               $list = $this->employee_type_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $employee_type) {
         // print_r($employee_type);die;
            $no++;
            $row = array();
            if($employee_type->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($employee_type->state))
            {
                $state = " ( ".ucfirst(strtolower($employee_type->state))." )";
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$employee_type->id.'">'.$check_script; 
            $row[] = $employee_type->emp_type;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($employee_type->created_date)); 
            
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";
            if(in_array('36',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_employee_type('.$employee_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            if(in_array('35',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash('.$employee_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //


      $row[] = $btn_restore.$btn_delete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee_type_archive->count_all(),
                        "recordsFiltered" => $this->employee_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(7,36);
        $this->load->model('employee_type/employee_type_archive_model','employee_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_archive->restore($id);
           $response = "Employee Type successfully restore in Employee Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(7,36);
        $this->load->model('employee_type/employee_type_archive_model','employee_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_archive->restoreall($post['row_id']);
            $response = "Employee Types successfully restore in Employee Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(7,35);
        $this->load->model('employee_type/employee_type_archive_model','employee_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_archive->trash($id);
           $response = "Employee type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(7,35);
        $this->load->model('employee_type/employee_type_archive_model','employee_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_archive->trashall($post['row_id']);
            $response = "Employee Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function employee_type_dropdown()
  {
      $employee_type_list = $this->employee_type->employee_type_list();
      $dropdown = '<option value="">Select employee type</option>'; 
      if(!empty($employee_type_list))
      {
        foreach($employee_type_list as $employee_type)
        {
           $dropdown .= '<option value="'.$employee_type->id.'">'.$employee_type->emp_type.'</option>';
        }
      } 
      echo $dropdown; 
  }

   function check_unique_value($emp_type, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->employee_type->check_unique_value($users_data['parent_id'], $emp_type,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Employee type already exist.');
            $response = false;
        }
        return $response;
      }

}
?>