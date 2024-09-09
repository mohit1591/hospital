<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('department/department_model','departments');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('3','1230');
        $data['page_title'] = 'Department List'; 
        $this->load->view('department/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('3','1230');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->departments->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $departments) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
          
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($departments->dept_status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }               
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$departments->id.'">'; 
            $row[] = $departments->department;  
            
            //if($departments->branch_id==$users_data['parent_id'])
            //{
              
              $active_result_heading_checked='';
              $inactive_result_heading_checked='';
              
              
              if($departments->result_heading==1)
              {
                $active_result_heading_checked = 'checked="checked"';
              }
              else
              {
                $inactive_result_heading_checked = 'checked="checked"';
              }

              $row[] = '<input type="radio" value="1" '.$active_result_heading_checked.' name="result_heading_'.$i.'" onClick="update_result_heading_status('.$departments->id.',1)"/> Enable
            <input type="radio" value="0" name="result_heading_'.$i.'" '.$inactive_result_heading_checked.' onClick="update_result_heading_status('.$departments->id.',0)"/> Disable';
            
            $active_checked='';
            $inactive_checked=''; 
            if($departments->dept_status==1)
              {
                $active_checked = 'checked="checked"';
              }
              else
              {
                $inactive_checked = 'checked="checked"';
              }
            
            $row[] = '<input type="radio" value="1" '.$active_checked.' name="status_'.$i.'" onClick="update_status('.$departments->id.',1)"/> Active
            <input type="radio" value="0" name="status_'.$i.'" '.$inactive_checked.' onClick="update_status('.$departments->id.',0)"/> Inactive';
            /*}
            else
            {
              $row[] = $status;
            }*/
            
           
          $btnedit='';
          $btndelete='';
          if($departments->branch_id==$users_data['parent_id'])
          {
            if(in_array('1232',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_department('.$departments->id.');" class="btn-custom" href="javascript:void(0)" style="'.$departments->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            }
             if(in_array('1233',$users_data['permission']['action'])){
                 $btndelete = ' <a class="btn-custom" onClick="return delete_department('.$departments->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
            }
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->departments->count_all(),
                        "recordsFiltered" => $this->departments->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('3','1231');
        $data['page_title'] = "Add Department";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'department'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->departments->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('department/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('3','1232');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->departments->get_by_id($id);  
        $data['page_title'] = "Update department";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'department'=>$result['department'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->departments->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('department/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('department', 'department', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'department'=>$post['department'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('3','1233');
       if(!empty($id) && $id>0)
       {
           $result = $this->departments->delete($id);
           $response = "department Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('3','1233');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->departments->deleteall($post['row_id']);
            $response = "department successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->departments->get_by_id($id);  
        $data['page_title'] = $data['form_data']['department']." detail";
        $this->load->view('department/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('3','1234');
        $data['page_title'] = 'department Archive List';
        $this->load->helper('url');
        $this->load->view('department/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('3','1234');
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('department/department_archive_model','department_archive'); 

        $list = $this->department_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $departments) { 
            $no++;
            $row = array();
            if($departments->dept_status==1)
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
            
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$departments->id.'">'.$check_script; 
            $row[] = $departments->department;  
           // if($departments->branch_id==$users_data['parent_id'])
           // {
              $active_checked='';
              $inactive_checked='';
              if($departments->dept_status==1)
              {
                $active_checked = 'checked="checked"';
              }
              else
              {
                $inactive_checked = 'checked="checked"';
              }

              $row[] = '<input type="radio" value="1" '.$active_checked.' name="status_'.$i.'" onClick="update_status('.$departments->id.',1)"/> Active
            <input type="radio" value="0" name="status_'.$i.'" '.$inactive_checked.' onClick="update_status('.$departments->id.',0)"/> Inactive';
            /*}
            else
            {
              $row[] = $status;
            }*/
            
            
          $btnrestore='';
          $btndelete='';
          if($departments->branch_id ==$users_data['parent_id'])
          {
            if(in_array('1237',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_department('.$departments->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            }
            if(in_array('1235',$users_data['permission']['action'])){
                 $btndelete = ' <a onClick="return trash('.$departments->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
            } 
          }
          
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->department_archive->count_all(),
                        "recordsFiltered" => $this->department_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('3','1237');
        $this->load->model('department/department_archive_model','department_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->department_archive->restore($id);
           $response = "department successfully restore in department list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('3','1237');
        $this->load->model('department/department_archive_model','department_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->department_archive->restoreall($post['row_id']);
            $response = "department successfully restore in department list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('3','1235');
        $this->load->model('department/department_archive_model','department_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->department_archive->trash($id);
           $response = "department successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('3','1235');
        $this->load->model('department/department_archive_model','department_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->department_archive->trashall($post['row_id']);
            $response = "department successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function department_dropdown()
  {

      $department_list = $this->department->department_list();
      
      $dropdown = '<option value="">Select department</option>'; 
      if(!empty($department_list))
      {
        foreach($department_list as $department)
        {
           $dropdown .= '<option value="'.$department->id.'">'.$department->department.'</option>';
        }
      } 
      echo $dropdown; 
  }

 

  public function save_department_status()
  {
     $post = $this->input->post();
     $department_id = $post['department_id'];
     
     $status = $post['status'];
     if(!empty($department_id))
     {
          $result = $this->departments->save_department_status($department_id,$status);
          echo $result;
          die;

     }

    }
    
  public function save_department_result_heading_status()
  {
     $post = $this->input->post();
     $department_id = $post['department_id'];
     
     $status = $post['status'];
     if(!empty($department_id))
     {
          $result = $this->departments->save_department_result_heading_status($department_id,$status);
          echo $result;
          die;

     }

    }

}
?>