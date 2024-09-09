<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/employee_profile/Employee_profile_model','employee_profile');
        $this->load->library('form_validation');
    }

    public function index()
    { 

       unauthorise_permission('270','1580');
        $data['page_title'] = 'Employee Profile List'; 
        $this->load->view('blood_bank/employee_profile/list',$data);
    }

    public function ajax_list()
    { 

        unauthorise_permission('270','1580');
        $list = $this->employee_profile->get_datatables(); 
        //print_r($list); 
        //;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $employee_profile) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($employee_profile->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$employee_profile->id.'">'.$check_script; 
             
             $row[] = $employee_profile->name; 
             $row[] = $employee_profile->employee_type_name; 
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($employee_profile->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1582',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_profile_type('.$employee_profile->id.');" class="btn-custom" href="javascript:void(0)" style="'.$employee_profile->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1583',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_profile_type('.$employee_profile->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee_profile->count_all(),
                        "recordsFiltered" => $this->employee_profile->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('270','1581');
        $data['page_title'] = "Add Employee Profile Type"; 
        $this->load->model('employee/Employee_model','employee');
        $data['emp_list'] = $this->employee->employee_list();
       // print '<pre>'; print_r($data['emp_list']);die;
        $data['employee_type_list']=$this->employee_profile->get_blood_employee_list(); 
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'employee_type'=>"",
                                  'profile_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->employee_profile->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/employee_profile/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('270','1582');
     if(isset($id) && !empty($id) && is_numeric($id))
      { 
       $this->load->model('employee/Employee_model','employee');
        $data['emp_list'] = $this->employee->employee_list();     
        $result = $this->employee_profile->get_by_id($id); 
         $data['employee_type_list']=$this->employee_profile->get_blood_employee_list(); 
        $data['page_title'] = "Update Employee Profile Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'employee_type'=>$result['employee_type'], 
                                  'profile_name'=>$result['profile_name'],                                   
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->employee_profile->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/employee_profile/add',$data);       
      }
    }
     
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('profile_name', 'profile name', 'trim|required'); 
           $this->form_validation->set_rules('employee_type', 'employee type', 'trim|required'); 
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'profile_name'=>$post['profile_name'],
                                        'employee_type'=>$post['employee_type'],  
                                        'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }

    /* check validation laready exit */
     public function employee_profile($str)
      {
        $post = $this->input->post();
         if(!empty($post['profile_name']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->employee_profile->get_by_id($post['data_id']);
                   // print_r($data_cat);
                    //die;
                      if($data_cat['profile_name']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $profile_name = $this->general->check_profile_name($str);

                        if(empty($profile_name))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('profile_name', 'The profile name already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $deferral_reason = $this->general->check_profile_name($post['profile_name'], $post['data_id']);
                    if(empty($deferral_reason))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('profile_name', 'The profile name already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('profile_name', 'The profile name field is required.');
               return false; 
          } 
      }


    
 
    public function delete($id="")
    {
      unauthorise_permission('270','1583');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_profile->delete($id);
           $response = "Employee Profile successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('270','1583');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_profile->deleteall($post['row_id']);
            $response = "Employee Profile successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->employee_profile->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Bag Type']." detail";
        $this->load->view('blood_bank/bag_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('270','1584');
        $data['page_title'] = 'Employee Profile archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/employee_profile/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('270','1584');
        $this->load->model('blood_bank/employee_profile/Employee_profile_archive_model','employee_profile_archive'); 

        $list = $this->employee_profile_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $list_archive) { 
            $no++;
            $row = array();
            if($list_archive->status==1)
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
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$list_archive->id.'">'.$check_script; 
          
             $row[] = $list_archive->name;   
             $row[] = $list_archive->employee_type_name; 
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($list_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1585',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_profile('.$list_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1586',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$list_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->employee_profile_archive->count_all(),
                        "recordsFiltered" => $this->employee_profile_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('270','1585');
       $this->load->model('blood_bank/employee_profile/Employee_profile_archive_model','employee_profile_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_profile_archive->restore($id);
           $response = "Employee Profile successfully restore in Employee Profile list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('270','1585');
        $this->load->model('blood_bank/employee_profile/Employee_profile_archive_model','employee_profile_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_profile_archive->restoreall($post['row_id']);
            $response = "Employee Profile successfully restore in Employee Profile list";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('270','1586');
        $this->load->model('blood_bank/employee_profile/Employee_profile_archive_model','employee_profile_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->employee_profile_archive->trash($id);
           $response = "Employee Profile successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
     unauthorise_permission('270','1586');
         $this->load->model('blood_bank/employee_profile/Employee_profile_archive_model','employee_profile_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->employee_profile_archive->trashall($post['row_id']);
            $response = "Employee Profile successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function bag_type_archive_dropdown()
  {

      $deferral_reason_archive_list = $this->employee_profile_archive->bag_type_archive_list();
      $dropdown = '<option value="">Select Bag Type</option>'; 
      if(!empty($deferral_reason_archive_list))
      {
        foreach($deferral_reason_archive_list as $deferral_reason_archive)
        {
           $dropdown .= '<option value="'.$deferral_reason_archive->id.'">'.$deferral_reason_archive->deferral_reason_archive.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>