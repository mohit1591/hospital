<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obstetrics_menstrual_history extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('admissionnotes/obstetrics_menstrual_history/Obstetrics_menstrual_history_model','obstetrics_menstrual');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('69','432');
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
        if(in_array('432',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }

        $data['page_title'] = 'Obstetrics Menstrual History List'; 
        $this->load->view('admissionnotes/obstetrics_menstrual_history/list',$data);
    }

    public function ajax_list()
    { 
       
        //unauthorise_permission('69','432');
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
        if(in_array('432',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        
        $list = $this->obstetrics_menstrual->get_datatables();  
        // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $obstetrics_menstrual) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($obstetrics_menstrual->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$obstetrics_menstrual->id.'">'.$check_script; 
            $row[] = $obstetrics_menstrual->obstetrics_menstrual_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('434',$users_data['permission']['action'])){obstetrics_menstrual
          if(in_array('434',$permission_action) || in_array('121',$permission_section)){
              $btnedit = ' <a onClick="return edit_chief_complaints('.$obstetrics_menstrual->id.');" class="btn-custom" href="javascript:void(0)" style="'.$obstetrics_menstrual->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('435',$users_data['permission']['action'])){
          if(in_array('435',$permission_action) || in_array('121',$permission_section)){
              $btndelete = ' <a class="btn-custom" onClick="return delete_chief_complaints('.$obstetrics_menstrual->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->obstetrics_menstrual->count_all(),
            "recordsFiltered" => $this->obstetrics_menstrual->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('69','433');
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
        if(in_array('433',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = "Add Obstetrics Menstrual History";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
          'data_id'=>"", 
          'obstetrics_menstrual_history'=>"",
          'status'=>"1"
          );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->obstetrics_menstrual->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/obstetrics_menstrual_history/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('69','434');
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
        if(in_array('434',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      { 
        $result = $this->obstetrics_menstrual->get_by_id($id);  
        $data['page_title'] = "Update Obstetrics Menstrual History";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
          'data_id'=>$result['id'],
          'obstetrics_menstrual_history'=>$result['obstetrics_menstrual_history'], 
          'status'=>$result['status']
        );  
        
        
        if(isset($post) && !empty($post))
        {
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->obstetrics_menstrual->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/obstetrics_menstrual_history/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('obstetrics_menstrual_history', 'Obstetrics Menstrual History', 'trim|required|callback_check_unique_value['.$id.']'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                'data_id'=>$post['id'],
                'obstetrics_menstrual_history'=>$post['obstetrics_menstrual_history'], 
                'status'=>$post['status']
            ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('69','435');
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
        if(in_array('435',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->obstetrics_menstrual->delete($id);
           $response = "Obstetrics Menstrual History Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('69','435');
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
        if(in_array('435',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->obstetrics_menstrual->deleteall($post['row_id']);
            $response = "Obstetrics Menstrual History successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->obstetrics_menstrual->get_by_id($id);  
        $data['page_title'] = $data['form_data']['obstetrics_menstrual_history']." detail";
        $this->load->view('admissionnotes/obstetrics_menstrual_history/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('69','436');
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
        if(in_array('436',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Obstetrics Menstrual History Archive List';
        $this->load->helper('url');
        $this->load->view('admissionnotes/obstetrics_menstrual_history/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('69','436');
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
        if(in_array('436',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('admissionnotes/obstetrics_menstrual_history/obstetrics_menstrual_history_archive_model','obstetrics_menstrual_archive');

        $list = $this->obstetrics_menstrual_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $chief_complaints) { 
            $no++;
            $row = array();
            if($chief_complaints->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$chief_complaints->id.'">'.$check_script; 
            $row[] = $chief_complaints->obstetrics_menstrual_history;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($chief_complaints->created_date)); 
            
          $btnrestore='';
          $btndelete='';
         // if(in_array('438',$users_data['permission']['action'])){
          if(in_array('438',$permission_action) || in_array('121',$permission_section)){
               $btnrestore = ' <a onClick="return restore_chief_complaints('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('437',$users_data['permission']['action'])){
          if(in_array('437',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$chief_complaints->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->obstetrics_menstrual_archive->count_all(),
                        "recordsFiltered" => $this->obstetrics_menstrual_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('69','438');
        
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
        if(in_array('438',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/obstetrics_menstrual_history/obstetrics_menstrual_history_archive_model','obstetrics_menstrual_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->obstetrics_menstrual_archive->restore($id);
           $response = "Obstetrics Menstrual History Achive successfully restore in History Presenting list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('69','438');
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
        if(in_array('438',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/obstetrics_menstrual_history/obstetrics_menstrual_history_archive_model','obstetrics_menstrual_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->obstetrics_menstrual_archive->restoreall($post['row_id']);
            $response = "Obstetrics Menstrual History Achive successfully restore in Obstetrics Menstrual History list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('69','437');
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
        if(in_array('437',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/obstetrics_menstrual_history/obstetrics_menstrual_history_archive_model','obstetrics_menstrual_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->obstetrics_menstrual_archive->trash($id);
           $response = "Obstetrics Menstrual History successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('69','437');
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
        if(in_array('437',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/obstetrics_menstrual_history/obstetrics_menstrual_history_archive_model','obstetrics_menstrual_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->obstetrics_menstrual_archive->trashall($post['row_id']);
            $response = "Obstetrics Menstrual History successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function chief_complaints_dropdown()
  {

      $chief_complaints_list = $this->chief_complaints->chief_complaints_list();
      $dropdown = '<option value="">Select chief complaints</option>'; 
      if(!empty($chief_complaints_list))
      {
        foreach($chief_complaints_list as $chief_complaints)
        {
           $dropdown .= '<option value="'.$chief_complaints->id.'">'.$chief_complaints->chief_complaints.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  function check_unique_value($illness, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->obstetrics_menstrual->check_unique_value($users_data['parent_id'], $illness, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Obstetrics Menstrual History already exist.');
            $response = false;
        }
        
        return $response;
    }


}
?>