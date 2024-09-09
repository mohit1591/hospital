<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('nursingnotes/personal_history/personal_history_model','personal_history');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('74','467');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('467',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Personal History List'; 
        $this->load->view('nursingnotes/personal_history/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('74','467');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('467',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $list = $this->personal_history->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $personal_history) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($personal_history->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$personal_history->id.'">'.$check_script; 
            $row[] = $personal_history->personal_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($personal_history->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('469',$users_data['permission']['action'])){
          if(in_array('469',$permission_action) || in_array('121',$permission_section)){
               $btnedit = ' <a onClick="return edit_personal_history('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)" style="'.$personal_history->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('470',$users_data['permission']['action'])){
          if(in_array('470',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_personal_history('.$personal_history->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->personal_history->count_all(),
                        "recordsFiltered" => $this->personal_history->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('74','468');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('468',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = "Add Personal History";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'personal_history'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->personal_history->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('nursingnotes/personal_history/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('74','469');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('469',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->personal_history->get_by_id($id);  
        $data['page_title'] = "Update Personal History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'personal_history'=>$result['personal_history'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->personal_history->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('nursingnotes/personal_history/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('personal_history', 'personal history', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'personal_history'=>$post['personal_history'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('74','470');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('470',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history->delete($id);
           $response = "Personal History successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('74','470');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('470',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history->deleteall($post['row_id']);
            $response = "Personal History successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->personal_history->get_by_id($id);  
        $data['page_title'] = $data['form_data']['personal_history']." detail";
        $this->load->view('nursingnotes/personal_history/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('74','471');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('471',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Personal History Archive List';
        $this->load->helper('url');
        $this->load->view('nursingnotes/personal_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('74','471');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('471',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('nursingnotes/personal_history/personal_history_archive_model','personal_history_archive'); 

        $list = $this->personal_history_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $personal_history) { 
            $no++;
            $row = array();
            if($personal_history->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$personal_history->id.'">'.$check_script; 
            $row[] = $personal_history->personal_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($personal_history->created_date)); 
           
          $btnrestore='';
          $btndelete='';
          //if(in_array('473',$users_data['permission']['action'])){
          if(in_array('473',$permission_action) || in_array('121',$permission_section)){
               $btnrestore = ' <a onClick="return restore_personal_history('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('472',$users_data['permission']['action'])){
          if(in_array('472',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$personal_history->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->personal_history_archive->count_all(),
                        "recordsFiltered" => $this->personal_history_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('74','473');
        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('473',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('nursingnotes/personal_history/personal_history_archive_model','personal_history_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history_archive->restore($id);
           $response = "Personal History successfully restore in Personal History list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('74','473');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('473',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('nursingnotes/personal_history/personal_history_archive_model','personal_history_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history_archive->restoreall($post['row_id']);
            $response = "Personal History successfully restore in Personal History list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('74','472');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('472',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('nursingnotes/personal_history/personal_history_archive_model','personal_history_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->personal_history_archive->trash($id);
           $response = "Personal History successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('74','472');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
        }
        if(in_array('472',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('nursingnotes/personal_history/personal_history_archive_model','personal_history_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->personal_history_archive->trashall($post['row_id']);
            $response = "Personal History successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function personal_history_dropdown()
  {

      $personal_history_list = $this->personal_history->personal_history_list();
      $dropdown = '<option value="">Select Personal history</option>'; 
      if(!empty($personal_history_list))
      {
        foreach($personal_history_list as $personal_history)
        {
           $dropdown .= '<option value="'.$personal_history->id.'">'.$personal_history->personal_history.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function check_unique_value($pers, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->personal_history->check_unique_value($users_data['parent_id'], $pers, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Personal history already exist.');
            $response = false;
        }
        return $response;
    }

}
?>