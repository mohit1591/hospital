<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Suggestion extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('admissionnotes/suggestion/suggestion_model','suggestion');
        $this->load->library('form_validation');
    }

    public function index()
    {  
        //unauthorise_permission(80,509);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('509',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

          }
          else
          {
            redirect('401');
          }
        $data['page_title'] = 'Suggestion List'; 
        $this->load->view('admissionnotes/suggestion/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission(80,509);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('509',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

          }
          else
          {
            redirect('401');
          }
        $users_data = $this->session->userdata('auth_users');
        $list = $this->suggestion->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $suggestion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            if($suggestion->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$suggestion->id.'">'.$check_script; 
            $row[] = $suggestion->medicine_suggetion;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($suggestion->created_date)); 
 
            //Action button /////
            $btn_edit = ""; 
            $btn_delete = "";

            //if(in_array('511',$users_data['permission']['action'])){
            if(in_array('511',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){
               $btn_edit = ' <a onClick="return edit_suggestion('.$suggestion->id.');" class="btn-custom" href="javascript:void(0)" style="'.$suggestion->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            }
            //if(in_array('512',$users_data['permission']['action'])){
            if(in_array('512',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){
               $btn_delete = ' <a class="btn-custom" onClick="return delete_suggestion('.$suggestion->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';
            } 
            // End Action Button //
            
      $row[] = $btn_edit.$btn_delete;   
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->suggestion->count_all(),
                        "recordsFiltered" => $this->suggestion->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission(80,510); 
       $users_data = $this->session->userdata('auth_users');
      if(in_array('510',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = "Add Suggestion";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_suggetion'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->suggestion->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/suggestion/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission(80,511); 
      $users_data = $this->session->userdata('auth_users');
      if(in_array('511',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->suggestion->get_by_id($id);  
        $data['page_title'] = "Update Suggestion";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_suggetion'=>$result['medicine_suggetion'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->suggestion->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/suggestion/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_suggetion', 'medicine suggestion', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_suggetion'=>$post['medicine_suggetion'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission(80,512);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('512',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
       if(!empty($id) && $id>0)
       {
           $result = $this->suggestion->delete($id);
           $response = "Suggestion successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        //unauthorise_permission(80,512); 
       $users_data = $this->session->userdata('auth_users');
      if(in_array('512',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->suggestion->deleteall($post['row_id']);
            $response = "Suggestion successfully deleted.";
            echo $response;
        }
    }
 


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission(80,513); 
       $users_data = $this->session->userdata('auth_users');
      if(in_array('513',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $data['page_title'] = 'Suggestion Archive List';
        $this->load->helper('url');
        $this->load->view('admissionnotes/suggestion/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission(80,513);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('513',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('admissionnotes/suggestion/suggestion_archive_model','suggestion_archive'); 

        $list = $this->suggestion_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $suggestion) {
         // print_r($religion);die;
            $no++;
            $row = array();
            if($suggestion->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$suggestion->id.'">'.$check_script; 
            $row[] = $suggestion->medicine_suggetion;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($suggestion->created_date)); 
 
            //Action button /////
            $btn_restore = ""; 
            $btn_delete = "";

            //if(in_array('515',$users_data['permission']['action'])){
            if(in_array('515',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){
               $btn_restore = ' <a onClick="return restore_suggestion('.$suggestion->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
            } 
            
            //if(in_array('514',$users_data['permission']['action'])){
            if(in_array('514',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){
                $btn_delete = ' <a onClick="return trash('.$suggestion->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            } 
            // End Action Button //
 
           $row[] = $btn_restore.$btn_delete;  
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->suggestion_archive->count_all(),
                        "recordsFiltered" => $this->suggestion_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission(80,515);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('515',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $this->load->model('admissionnotes/suggestion/suggestion_archive_model','suggestion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->suggestion_archive->restore($id);
           $response = "Suggestion successfully restore in Suggestion list.";
           echo $response;
       }
    }

    function restoreall()
    { 

        //unauthorise_permission(80,515);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('515',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $this->load->model('admissionnotes/suggestion/suggestion_archive_model','suggestion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->suggestion_archive->restoreall($post['row_id']);
            $response = "Suggestion successfully restore in Suggestion list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission(80,514);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('514',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $this->load->model('admissionnotes/suggestion/suggestion_archive_model','suggestion_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->suggestion_archive->trash($id);
           $response = "Suggestion successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission(80,514);
       $users_data = $this->session->userdata('auth_users');
      if(in_array('514',$users_data['permission']['action']) || in_array('121',$users_data['permission']['section'])){

        }
        else
        {
          redirect('401');
        } 
        $this->load->model('admissionnotes/suggestion/suggestion_archive_model','suggestion_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->suggestion_archive->trashall($post['row_id']);
            $response = "Suggestion successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function suggestion_dropdown()
  {
      $suggestion_list = $this->suggestion->suggestion_list();
      $dropdown = '<option value="">Select Suggestion</option>'; 
      if(!empty($suggestion_list))
      {
        foreach($suggestion_list as $suggestion)
        {
           $dropdown .= '<option value="'.$suggestion->id.'">'.$suggestion->medicine_suggetion.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  function check_unique_value($suggestion) 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->suggestion->check_unique_value($users_data['parent_id'], $suggestion);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Medicine suggestion already exist.');
            $response = false;
        }
        return $response;
    }

}
?>