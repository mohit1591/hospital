<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_heads extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_heads/test_heads_model','test_heads');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('139','827');
        $data['page_title'] = 'Test Head List'; 
        $this->load->view('test_heads/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('139','827');
        $users_data = $this->session->userdata('auth_users');
         
       
            $list = $this->test_heads->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_heads) {
         // print_r($test_heads);die;
            $no++;
            $row = array();
            if($test_heads->status==1)
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
            if($users_data['parent_id']==$test_heads->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_heads->id.'">'.$check_script; 
            }else{
               $row[]='';
            }
            $row[] = $test_heads->department; 
            $row[] = $test_heads->test_heads;  
            $row[] = '<input type="text" width="30" class="input-tiny" value='.$test_heads->sort_order.' name="sortorder" onkeyup="sort_test_master('.$test_heads->id.',this.value)"/>';
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_heads->created_date)); 
 

          
          $btnedit='';
          $btndelete='';
           
          if($users_data['parent_id']==$test_heads->branch_id){
               if(in_array('829',$users_data['permission']['action'])){
                    $btnedit =' <a onClick="return edit_test_heads('.$test_heads->id.');" class="btn-custom" href="javascript:void(0)" style="'.$test_heads->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
                if(in_array('830',$users_data['permission']['action'])){
                    $btndelete=' <a class="btn-custom" onClick="return delete_test_heads('.$test_heads->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
         
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_heads->count_all(),
                        "recordsFiltered" => $this->test_heads->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('139','828');
        $data['page_title'] = "Add Test Head";  
        $this->load->model('general/general_model'); 
        //$data['dept_list'] = $this->general_model->department_list(5); 
        $data['dept_list'] = $this->general_model->active_department_list(5); 
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'dept_id'=>"",
                                  'test_heads'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_heads->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_heads/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('139','829');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $this->load->model('general/general_model'); 
        //$data['dept_list'] = $this->general_model->department_list(5);
        $data['dept_list'] = $this->general_model->active_department_list(5);  
        $result = $this->test_heads->get_by_id($id);  
        $data['page_title'] = "Update Test Head";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'dept_id'=>$result['dept_id'],
                                  'test_heads'=>$result['test_heads'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_heads->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_heads/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('dept_id', 'Department', 'trim|required'); 
        $this->form_validation->set_rules('test_heads', 'Hest Heads', 'trim|required|callback_check_duplicate'); 
        
        if ($this->form_validation->run() == FALSE) 
        {   
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'dept_id'=>$post['dept_id'],
                                        'test_heads'=>$post['test_heads'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('139','830');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_heads->delete($id);
           $response = "Test Head successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('139','830');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_heads->deleteall($post['row_id']);
            $response = "Test Head successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->test_heads->get_by_id($id);  
        $data['page_title'] = $data['form_data']['test_heads']." detail";
        $this->load->view('test_heads/view',$data);     
      }
    }  
 

  public function test_heads_dropdown($dept_id="", $branch_id="")
  {
      $this->session->set_userdata('test_dept_id',$dept_id);
      $users_data = $this->session->userdata('auth_users');
      if(empty($branch_id))
      {
         $branch_id = $users_data['parent_id'];  
      }
      $test_heads_list = $this->test_heads->test_heads_list($dept_id,$branch_id);
      $dropdown = '<option value="">Select Test Head</option>'; 
      if(!empty($test_heads_list))
      {
        foreach($test_heads_list as $test_heads)
        {
           $dropdown .= '<option value="'.$test_heads->id.'">'.$test_heads->test_heads.'</option>';
        }
      } 
      echo $dropdown; 
  }
  public function archive()
    {
        unauthorise_permission('139','831');
        $data['page_title'] = 'Test Head Archive List';
        $this->load->helper('url');
        $this->load->view('test_heads/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('139','831');
       $this->load->model('test_heads/Test_head_archive_model','test_head_archive');

         $list = $this->test_head_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_head) {
         // print_r($test_method);die;
            $no++;
            $row = array();
            if($test_head->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_head->id.'">'.$check_script; 
           $row[] = $test_head->department; 
            $row[] = $test_head->test_heads;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_head->created_date)); 
 
           $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('833',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_test_heads('.$test_head->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
          if(in_array('832',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$test_head->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_head_archive->count_all(),
                        "recordsFiltered" => $this->test_head_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('139','833');
        $this->load->model('test_heads/test_head_archive_model','test_head_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_head_archive->restore($id);
           $response = "Test Head successfully restore in Test Head list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('139','833');
       $this->load->model('test_heads/test_head_archive_model','test_head_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_head_archive->restoreall($post['row_id']);
            $response = "Test Head successfully restore in Test Head list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('139','832');
        $this->load->model('test_heads/test_head_archive_model','test_head_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_head_archive->trash($id);
           $response = "Test Head successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('139','832');
        $this->load->model('test_heads/test_head_archive_model','test_head_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_head_archive->trashall($post['row_id']);
            $response = "Test Head successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////
    public function save_sort_order_data(){
          $post = $this->input->post();
          $id = $post['test_id'];
          $sort_order_value = $post['sort_order_value'];
          if(!empty($id) && !empty($sort_order_value)){
               $result = $this->test_heads->save_sort_order_data($id,$sort_order_value);
               echo $result;
               die;
          }

     }
     
    public function check_duplicate()
    {
      $test_head = $this->input->post('test_heads');
      $data = $this->test_heads->check_duplicate($test_head);
      $data_id = $this->input->post('data_id');
      if(empty($data) || $data_id>0)
      {
        return true;
      }
      else
      {
        $this->form_validation->set_message('check_duplicate', 'Test head already exists.');
        return false;
      }
    }
 
}
?>