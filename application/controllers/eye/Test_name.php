<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_name extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/test_name/test_name_model','test_name');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('228','1286');
        $data['page_title'] = 'Test Name List'; 
        $this->load->view('eye/test_name/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('228','1286');
          $users_data = $this->session->userdata('auth_users');
        $list = $this->test_name->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_name) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($test_name->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_name->id.'">'.$check_script; 
            $row[] = $test_name->test_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_name->created_date)); 
          
          $btnedit='';
          $btndelete='';
          if(in_array('1288',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_test_name('.$test_name->id.');" class="btn-custom" href="javascript:void(0)" style="'.$test_name->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('1289',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_test_name('.$test_name->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_name->count_all(),
                        "recordsFiltered" => $this->test_name->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('228','1287');
        $data['page_title'] = "Add Test Name";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'test_name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_name->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/test_name/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('228','1288');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->test_name->get_by_id($id);  
        $data['page_title'] = "Update Test Name";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'test_name'=>$result['test_name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_name->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('eye/test_name/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('test_name', 'test name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'test_name'=>$post['test_name'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('228','1289');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_name->delete($id);
           $response = "Test Name successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('228','1289');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_name->deleteall($post['row_id']);
            $response = "Test Name successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->test_name->get_by_id($id);  
        $data['page_title'] = $data['form_data']['test_name']." detail";
        $this->load->view('eye/test_name/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('228','1290');
        $data['page_title'] = 'Test Name Archive List';
        $this->load->helper('url');
        $this->load->view('eye/test_name/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('228','1290');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('eye/test_name/test_name_archive_model','test_name_archive'); 

        $list = $this->test_name_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_name) { 
            $no++;
            $row = array();
            if($test_name->status==1)
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
           /* $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_name->id.'">'.$check_script; 
            $row[] = $test_name->test_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_name->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1292',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_test_name('.$test_name->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1291',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$test_name->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_name_archive->count_all(),
                        "recordsFiltered" => $this->test_name_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('228','1292');
        $this->load->model('eye/test_name/test_name_archive_model','test_name_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_name_archive->restore($id);
           $response = "Test Name successfully restore in Test Name list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('228','1292');
        $this->load->model('eye/test_name/test_name_archive_model','test_name_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_name_archive->restoreall($post['row_id']);
            $response = "Test Name successfully restore in Test Name list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('228','1291');
        $this->load->model('eye/test_name/test_name_archive_model','test_name_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_name_archive->trash($id);
           $response = "Test Name successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('228','1291');
        $this->load->model('eye/test_name/test_name_archive_model','test_name_archive');
        $post = $this->input->post();  
        //echo "<pre>";print_r($post); exit;
        if(!empty($post))
        {
            $result = $this->test_name_archive->trashall($post['row_id']);
            $response = "Test Name successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function test_name_dropdown()
  {

      $test_name_list = $this->test_name->test_name_list();
      $dropdown = '<option value="">Select Relation</option>'; 
      if(!empty($test_name_list))
      {
        foreach($test_name_list as $test_name)
        {
           $dropdown .= '<option value="'.$test_name->id.'">'.$test_name->test_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>