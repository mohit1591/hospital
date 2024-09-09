<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_sample_type extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_sample_type/Test_sample_type_model','test_sample_type');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('12','64');
        $data['page_title'] = 'Sample Type List'; 
        $this->load->view('test_sample_type/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('12','64');
         $users_data = $this->session->userdata('auth_users');
     
      
            $list = $this->test_sample_type->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_sample_type) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($test_sample_type->status==1)
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
            if($users_data['parent_id']==$test_sample_type->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_sample_type->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $test_sample_type->sample_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_sample_type->created_date)); 
            $btnedit='';
            $btndelete='';
            
            if($users_data['parent_id']==$test_sample_type->branch_id)
            {
              if(in_array('66',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_test_sample_type('.$test_sample_type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$test_sample_type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('67',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_test_sample_type('.$test_sample_type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
            
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_sample_type->count_all(),
                        "recordsFiltered" => $this->test_sample_type->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        // unauthorise_permission('12','65');
        $data['page_title'] = "Add Sample Type";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'sample_type'=>"",
                                  'status'=>"1",
                                
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_sample_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_sample_type/add',$data);       
    }
     // -> function to find gender according to selected simulation
    // -> change made by: mahesh:date:23-05-17
    // -> starts:
    public function find_gender(){
         $this->load->model('general/general_model'); 
         // $simulation_id = $this->input->post('simulation_id');
         $data='';
          if(!empty($simulation_id)){
               $result = $this->general_model->find_gender($simulation_id);
               if(!empty($result))
               {
                  $male = "";
                  $female = "";  
                  if($result['gender']==1)
                  {
                     $male = 'checked="checked"';
                  } 
                  else
                  {
                     $female = 'checked="checked"';
                  } 
                  $data.='<input type="radio" name="gender" '.$male.' value="1" /> Male &nbsp;
                          <input type="radio" name="gender" '.$female.' value="0" /> Female ';
               }
 
          }
          echo $data;
    }
    // -> end:
    public function edit($id="")
    {
     // unauthorise_permission('12','66');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->test_sample_type->get_by_id($id);  
        $data['page_title'] = "Update Sample Type";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'sample_type'=>$result['sample_type'], 
                                  'status'=>$result['status'],
                                 
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->test_sample_type->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('test_sample_type/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('sample_type', 'sample type', 'trim|required|callback_check_sample'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'sample_type'=>$post['sample_type'], 
                                        'status'=>$post['status'],
                                       
                                       ); 
            return $data['form_data'];
        }   
    }
    
    public function check_sample($str)
    {
        $post = $this->input->post();
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
                return true;
        }
        else
        {
              $userdata = $this->test_sample_type->check_sample($str); 
              if(empty($userdata))
              {
                 return true;
              }
              else
              { 
                $this->form_validation->set_message('check_sample', 'Sample already exists.');
                return false;
              }
        }
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->test_sample_type->delete($id);
           $response = "Sample Type successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        // unauthorise_permission('12','67');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_sample_type->deleteall($post['row_id']);
            $response = "Sample Type successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->test_sample_type->get_by_id($id);  
        $data['page_title'] = $data['form_data']['test_sample_type']." detail";
        $this->load->view('test_sample_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        // unauthorise_permission('12','68');
        $data['page_title'] = 'Sample Type Archive List';
        $this->load->helper('url');
        $this->load->view('test_sample_type/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('12','68');
        $this->load->model('test_sample_type/Test_sample_type_archive_model','test_sample_type_archive'); 

      
               $list = $this->test_sample_type_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test_sample_type) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($test_sample_type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$test_sample_type->id.'">'.$check_script; 
            $row[] = $test_sample_type->sample_type;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($test_sample_type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('70',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_test_sample_type('.$test_sample_type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('69',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$test_sample_type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_sample_type_archive->count_all(),
                        "recordsFiltered" => $this->test_sample_type_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission('12','70');
        $this->load->model('test_sample_type/Test_sample_type_archive_model','test_sample_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_sample_type_archive->restore($id);
           $response = "Sample Type successfully restore in Sample Type list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission('12','70');
        $this->load->model('test_sample_type/Test_sample_type_archive_model','test_sample_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_sample_type_archive->restoreall($post['row_id']);
            $response = "Sample Type successfully restore in Sample Type list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('12','69');
        $this->load->model('test_sample_type/Test_sample_type_archive_model','test_sample_type_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_sample_type_archive->trash($id);
           $response = "Sample Type successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('12','69');
        $this->load->model('test_sample_type/Test_sample_type_archive_model','test_sample_type_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_sample_type_archive->trashall($post['row_id']);
            $response = "Sample Type successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function test_sample_type_dropdown()
  {
      $test_sample_type_list = $this->test_sample_type->test_sample_type_list();
      $dropdown = '<option value="">Select Sample Type</option>'; 
      if(!empty($test_sample_type_list))
      {
        foreach($test_sample_type_list as $test_sample_type)
        {
           $dropdown .= '<option value="'.$test_sample_type->id.'">'.$test_sample_type->sample_type.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
}
?>