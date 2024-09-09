<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_method extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_method/test_method_model','test_method');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('140','834');
        $data['page_title'] = 'Test Method List'; 
        $this->load->view('test_method/list',$data);
    }

    public function ajax_list()
    { 
        
        unauthorise_permission('140','834');
        $users_data = $this->session->userdata('auth_users');
       
       
            $list = $this->test_method->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_method) {
         // print_r($test_method);die;
            $no++;
            $row = array();
            if($test_method->status==1)
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
            if($users_data['parent_id']==$test_method->branch_id){
                 $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_method->id.'">'.$check_script; 
                 }
            else{
               $row[]='';
            }
            $row[] = $test_method->test_method;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_method->created_date)); 
          
          $btnedit='';
          $btndelete='';
          
          if($users_data['parent_id']==$test_method->branch_id){
               if(in_array('836',$users_data['permission']['action'])){
                    $btnedit= ' <a onClick="return edit_test_method('.$test_method->id.');" class="btn-custom" href="javascript:void(0)" style="'.$test_method->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('837',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_test_method('.$test_method->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
             
       
             $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_method->count_all(),
                        "recordsFiltered" => $this->test_method->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('140','835');
        $data['page_title'] = "Add Test Method";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'test_method'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_method->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_method/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('140','836');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->test_method->get_by_id($id);  
        $data['page_title'] = "Update Test Method";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'test_method'=>$result['test_method'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_method->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_method/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('test_method', 'test method', 'trim|required|callback_check_method'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'test_method'=>$post['test_method'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
    
    public function check_method($str)
  {
      $post = $this->input->post();
      if(!empty($post['data_id']) && $post['data_id']>0)
      {
              return true;
      }
      else
      {
            $userdata = $this->test_method->check_method($str); 
            if(empty($userdata))
            {
               return true;
            }
            else
            { 
              $this->form_validation->set_message('check_method', 'Method already exists.');
              return false;
            }
      }
  }
 
    public function delete($id="")
    {
       unauthorise_permission('140','837');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_method->delete($id);
           $response = "Test Method successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('140','837');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_method->deleteall($post['row_id']);
            $response = "Test Methods successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->test_method->get_by_id($id);  
        $data['page_title'] = $data['form_data']['test_method']." detail";
        $this->load->view('test_method/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('140','838');
        $data['page_title'] = 'Test Method Archive List';
        $this->load->helper('url');
        $this->load->view('test_method/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('140','838');
        $this->load->model('test_method/test_method_archive_model','test_method_archive'); 
         $list = $this->test_method_archive->get_datatables();
                         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_method) {
         // print_r($test_method);die;
            $no++;
            $row = array();
            if($test_method->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_method->id.'">'.$check_script; 
            $row[] = $test_method->test_method;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_method->created_date)); 
 
           $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('840',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_test_method('.$test_method->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('839',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$test_method->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_method_archive->count_all(),
                        "recordsFiltered" => $this->test_method_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('140','840');
        $this->load->model('test_method/test_method_archive_model','test_method_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_method_archive->restore($id);
           $response = "Test Method successfully restore in Test Method list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('140','840');
        $this->load->model('test_method/test_method_archive_model','test_method_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_method_archive->restoreall($post['row_id']);
            $response = "Test Methods successfully restore in test Method list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('140','839');
        $this->load->model('test_method/test_method_archive_model','test_method_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_method_archive->trash($id);
           $response = "Test Method successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('140','839');
        $this->load->model('test_method/test_method_archive_model','test_method_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_method_archive->trashall($post['row_id']);
            $response = "Test Method successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function test_method_dropdown()
  {
      $test_method_list = $this->test_method->test_method_list();
      $dropdown = '<option value="">Select Test Method</option>'; 
      if(!empty($test_method_list))
      {
        foreach($test_method_list as $test_method)
        {
           $dropdown .= '<option value="'.$test_method->id.'">'.$test_method->test_method.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  
  



}
?>