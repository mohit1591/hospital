<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('employee/employee_model','employee');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(2,8);
        $data['page_title'] = 'Employee List'; 
        $this->load->view('employee/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission(2,8);
        $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->employee->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $employee) { 
            $no++;
            $row = array();
            if($employee->status==1)
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
             
            if($users_data['parent_id']==$employee->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$employee->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $row[] = $employee->reg_no;
            $row[] = $employee->name;
            $row[] = $employee->contact_no;
            $row[] = $employee->email; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($employee->created_date)); 
            //Action button /////
            $btn_edit = "";
            $btn_view = "";
            $btn_delete = "";
            if($users_data['parent_id']==$employee->branch_id){
                 if(in_array('10',$users_data['permission']['action'])) 
                 {
                    $btn_edit = ' <a onClick="return edit_employee('.$employee->id.');" class="btn-custom" href="javascript:void(0)" style="'.$employee->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                 }
                 if(in_array('14',$users_data['permission']['action'])) 
                 {
                    $btn_view = ' <a class="btn-custom" onClick="return view_employee('.$employee->id.')" href="javascript:void(0)" title="View" data-url="512"><i class="fa fa-info-circle"></i> View </a>';
                 }
                 if(in_array('11',$users_data['permission']['action'])) 
                 {
                     $btn_delete = ' <a class="btn-custom" onClick="return delete_emp('.$employee->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
                 } 
            }
            // End Action Button //

            $row[] = $btn_edit.$btn_view.$btn_delete;                  
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee->count_all(),
                        "recordsFiltered" => $this->employee->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {  
        unauthorise_permission(2,9);
        $data['page_title'] = "Add Employee"; 
        $this->load->model('general/general_model');
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['country_list'] = $this->general_model->country_list();
        $data['type_list'] = $this->employee->employee_type_list();
        $post = $this->input->post();
        $data['form_error'] = [];
        $reg_no = generate_unique_id(2); 
        $data['form_data'] = array(
                                  'data_id'=>"",
                                  'reg_no'=>$reg_no,
                                  'emp_type_id'=>"",
                                  'name'=>"",
                                  'contact_no'=>"",
                                  'dob'=>"",
                                  'age'=>"",
                                  'gender'=>"1",
                                  'merital_status'=>"1",
                                  'qualification'=>"",
                                  'email'=>"",
                                  'address'=>"",
                                  'city_id'=>"",
                                  'state_id'=>"",
                                  'country_id'=>"99",
                                  'postal_code'=>"",
                                  'salary'=>"",
                                  'status'=>"1",
                                   "country_code"=>"+91",
                                    "simulation_id"=>"",
                                    'anniversary'=>'',
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->employee->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('employee/add',$data);       
    }
    
    public function edit($id="")
    {  
     unauthorise_permission(2,10);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->employee->get_by_id($id); 
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['simulation_list'] = $this->general_model->simulation_list();
        $data['type_list'] = $this->employee->employee_type_list();  
        $data['page_title'] = "Update Employee";  
        $post = $this->input->post();
        $data['form_error'] = ''; 

        $dob ='';
        if($result['dob']!='0000-00-00')
        {
            $dob = date('d-m-Y',strtotime($result['dob']));
        } 

        $anniversary='';
        if($result['anniversary']!='0000-00-00')
        {
            $anniversary = date('d-m-Y',strtotime($result['anniversary']));
        }

        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'reg_no'=>$result['reg_no'],
                                  'emp_type_id'=>$result['emp_type_id'],
                                  'name'=>$result['name'],
                                  'contact_no'=>$result['contact_no'],
                                  'dob'=>$dob,
                                  'anniversary'=>$anniversary,
                                  'age'=>$result['age'],
                                  'gender'=>$result['sex'],
                                  'salary'=>$result['salary'],
                                  'merital_status'=>$result['merital_status'],
                                  'qualification'=>$result['qualification'],
                                  'email'=>$result['email'],
                                  'address'=>$result['address'],
                                  'city_id'=>$result['city_id'],
                                  'state_id'=>$result['state_id'],
                                  'country_id'=>$result['country_id'],
                                  'postal_code'=>$result['postal_code'], 
                                  'status'=>$result['status'],
                                   "country_code"=>"+91",
                                   "simulation_id"=>$result['simulation_id'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->employee->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       $this->load->view('employee/add',$data);       
      }  
    }
    
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('emp_type_id', 'employee type', 'trim|required|numeric');
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required'); 
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('salary', 'salary', 'trim|required|numeric');
        if(!empty($post['email']))
        {
          $this->form_validation->set_rules('email', 'Email', 'trim|valid_email|callback_check_email');
          
        }
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'reg_no'=>$reg_no,
                                        'emp_type_id'=>$post['emp_type_id'],
                                        'name'=>$post['name'],
                                        'contact_no'=>$post['contact_no'],
                                        'dob'=>$post['dob'],
                                        'anniversary'=>$post['anniversary'],
                                        'age'=>$post['age'],
                                        'gender'=>$post['gender'],
                                        'salary'=>$post['salary'],
                                        'merital_status'=>$post['merital_status'],
                                        'qualification'=>$post['qualification'],
                                        'email'=>$post['email'],
                                        'address'=>$post['address'],
                                        'city_id'=>$post['city_id'],
                                        'state_id'=>$post['state_id'],
                                        'country_id'=>$post['country_id'],
                                        'postal_code'=>$post['postal_code'], 
                                        'status'=>$post['status'],
                                         "country_code"=>"+91",
                                          "simulation_id"=>$post['simulation_id'],
                                       ); 
            return $data['form_data'];
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

        $userdata = $this->general->check_employee_email($str,$post['data_id']); 
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
       unauthorise_permission(2,11);
       if(!empty($id) && $id>0)
       {
           $result = $this->employee->delete($id);
           $response = "Employee successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(2,11);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee->deleteall($post['row_id']);
            $response = "Employees successfully deleted.";
            echo $response;
        }
    }


    public function view($id="")
    {  
     unauthorise_permission(2,14);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->employee->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('employee/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(2,12);
        $data['page_title'] = 'Employee Archive List';
        $this->load->helper('url');
        $this->load->view('employee/archive',$data);
    }

    public function archive_ajax_list()
    { 
        unauthorise_permission(2,12);
        
        $this->load->model('employee/employee_archive_model','employee_archive');
         
     
               $list = $this->employee_archive->get_datatables();
              
              
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $employee) { 
            $no++;
            $row = array();
            if($employee->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($employee->state))
            {
                $state = " ( ".ucfirst(strtolower($employee->state))." )";
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
             

            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$employee->id.'">'.$check_script;
            $row[] = $employee->reg_no;
            $row[] = $employee->name;
            $row[] = $employee->contact_no;
            $row[] = $employee->email; 
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($employee->created_date)); 
            
            $users_data = $this->session->userdata('auth_users');
            //Action button /////
            $btn_restore = "";
            $btn_view = "";
            $btn_delete = "";
            if(in_array('39',$users_data['permission']['action'])) 
            {
               $btn_restore = ' <a onClick="return restore_employee('.$employee->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('14',$users_data['permission']['action'])) 
            {
                $btn_view = ' <a onClick="return view_employee('.$employee->id.');" class="btn-custom" href="javascript:void(0)" title="View"><i class="fa fa-info-circle" aria-hidden="true"></i> View </a>';
            }
            if(in_array('13',$users_data['permission']['action'])) 
            {
                $btn_delete = ' <a onClick="return trash_employee('.$employee->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //

            $row[] = $btn_restore.$btn_view.$btn_delete;                
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee_archive->count_all(),
                        "recordsFiltered" => $this->employee_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(2,39);
        $this->load->model('employee/employee_archive_model','employee_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_archive->restore($id);
           $response = "Employee successfully restore in Employee list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(2,39);
        $this->load->model('employee/employee_archive_model','employee_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_archive->restoreall($post['row_id']);
            $response = "Employees successfully restore in Employee list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(2,13);
        $this->load->model('employee/employee_archive_model','employee_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_archive->trash($id);
           $response = "Employee successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(2,13);
        $this->load->model('employee/employee_archive_model','employee_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_archive->trashall($post['row_id']);
            $response = "Employee successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////
   
   public function type_to_employee($type_id="")
   {
      if(!empty($type_id) && $type_id > 0)
      {
        $dropdown = '<option value="">Select employee</option>';
        $result = $this->employee->type_to_employee($type_id);
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
 
}
?>