<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Previous_history extends CI_Controller {




    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('admissionnotes/previous_history/Previous_history_model','previoushis');
        $this->load->library('form_validation');
      
        
    }

    public function index()
    { 
      
        //unauthorise_permission('68','425');
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
        if(in_array('425',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Previous History List'; 
        $this->load->view('admissionnotes/previous_history/list',$data);
    }

    public function ajax_list()
    { 

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
        if(in_array('425',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        //unauthorise_permission('68','425');
        $users_data = $this->session->userdata('auth_users');
        $list = $this->previoushis->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $previoushis) {
         // print_r($previoushis);die;
            $no++;
            $row = array();
            if($previoushis->status==1)
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
                              })</script>
                              
                              ";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$previoushis->id.'">'.$check_script; 
            $row[] = $previoushis->prv_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($previoushis->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          //if(in_array('427',$users_data['permission']['action'])){
          if(in_array('427',$permission_action) || in_array('121',$permission_section)){
               $btnedit = ' <a onClick="return edit_previous_history('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$previoushis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('428',$users_data['permission']['action'])){
          if(in_array('428',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_previous_history('.$previoushis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->previoushis->count_all(),
                        "recordsFiltered" => $this->previoushis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('68','426');

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
        if(in_array('426',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }

        $data['page_title'] = "Add Previous History";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'prv_history'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->previoushis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/previous_history/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('68','427');

        $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('427',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->previoushis->get_by_id($id);  
        $data['page_title'] = "Update Previous History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'prv_history'=>$result['prv_history'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->previoushis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/previous_history/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('prv_history', 'previous history', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'prv_history'=>$post['prv_history'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('68','60');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('428',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis->delete($id);
           $response = "Previous History successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('68','428');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('428',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis->deleteall($post['row_id']);
            $response = "Previous History successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->previoushis->get_by_id($id);  
        $data['page_title'] = $data['form_data']['previoushis']." detail";
        $this->load->view('admissionnotes/previous_history/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('68','429');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('429',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Previous History Archive List';
        $this->load->helper('url');
        $this->load->view('admissionnotes/previous_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('68','429');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('429',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('admissionnotes/previous_history/previous_history_archive_model','previoushis_archive'); 

        $list = $this->previoushis_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $previoushis) { 
            $no++;
            $row = array();
            if($previoushis->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$previoushis->id.'">'.$check_script; 
            $row[] = $previoushis->prv_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($previoushis->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('431',$users_data['permission']['action'])){
          if(in_array('431',$permission_action) || in_array('121',$permission_section)){
               $btnrestore = ' <a onClick="return restore_previous_history('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('430',$users_data['permission']['action'])){
          if(in_array('430',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$previoushis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->previoushis_archive->count_all(),
                        "recordsFiltered" => $this->previoushis_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('68','431');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('431',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/previous_history/previous_history_archive_model','previoushis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis_archive->restore($id);
           $response = "Previous History successfully restore in Previous History List.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('68','431');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('431',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/previous_history/previous_history_archive_model','previoushis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis_archive->restoreall($post['row_id']);
            $response = "Previous History successfully restore in Previous History List.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('68','430');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('430',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/previous_history/previous_history_archive_model','previoushis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->previoushis_archive->trash($id);
           $response = "Previous History successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('68','430');
      $users_data = $this->session->userdata('auth_users');
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        
        if(in_array('430',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/previous_history/previous_history_archive_model','previoushis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->previoushis_archive->trashall($post['row_id']);
            $response = "Previous History successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function previoushis_dropdown()
  {

      $previoushis_list = $this->previoushis->previoushis_list();
      $dropdown = '<option value="">Select previoushis</option>'; 
      if(!empty($previoushis_list))
      {
        foreach($previoushis_list as $previoushis)
        {
           $dropdown .= '<option value="'.$previoushis->id.'">'.$previoushis->previoushis.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  function check_unique_value($prev_history,$id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->previoushis->check_unique_value($users_data['parent_id'], $prev_history,$id);
        if($result == 0)
        {
            $response = true;
        }
        else {
            $this->form_validation->set_message('check_unique_value', 'Previous history already exist.');
            $response = false;
        }
        return $response;
    }

}
?>