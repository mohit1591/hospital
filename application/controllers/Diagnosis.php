<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosis extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('diagnosis/Diagnosis_model','diagnosis');
        $this->load->library('form_validation');
    }

    public function upload() {
      $file_path = FCPATH."assets/icd10cm_codes_2018.csv";
      $this->load->database();
      if ($file_path) {
          // Read the file content
          // Check if file exists
          // dd($file_path);
          if (!file_exists($file_path)) {
              echo "File does not exist.";
              return;
          }

          // Read the file content
          $file_data = @file_get_contents($file_path);
          
          // Check if file content was successfully read
          if ($file_data === FALSE) {
              echo "Failed to read the file.";
              return;
          }
         
          // Split the content into lines
          $lines = explode(PHP_EOL, $file_data);
          $data = [];
          foreach ($lines as $line) {
            $name = str_replace('"','',$line);
            $data[] = array( 
              'branch_id'=> 210,
              'diagnosis'=> $name,
              'status'=> 1,
              'ip_address'=> $_SERVER['REMOTE_ADDR'],
              'created_by' =>715251,
              'created_date'=> date('Y-m-d H:i:s')
            );
          }
            $this->db->insert_batch('hms_opd_diagnosis',$data);
          echo "Data inserted successfully!";
      } else {
          echo "File path is required!";
      }
  }

    public function index()
    { 
        //unauthorise_permission('71','446');
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
        if(in_array('446',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Diagnosis List'; 
        $this->load->view('diagnosis/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('71','446');
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
        if(in_array('446',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $users_data = $this->session->userdata('auth_users');
        $list = $this->diagnosis->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $diagnosis) {
         
            $no++;
            $row = array();
            if($diagnosis->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis->id.'">'.$check_script; 
            $row[] = $diagnosis->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($diagnosis->created_date)); 
            
          $btnedit='';
          $btndelete='';
          //if(in_array('448',$users_data['permission']['action'])){
          if(in_array('448',$permission_action) || in_array('121',$permission_section)){
               $btnedit = ' <a onClick="return edit_diagnosis('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)" style="'.$diagnosis->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('449',$users_data['permission']['action'])){
          if(in_array('449',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_diagnosis('.$diagnosis->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis->count_all(),
                        "recordsFiltered" => $this->diagnosis->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('71','447');
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
        if(in_array('447',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = "Add Diagnosis";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'diagnosis'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('diagnosis/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('71','448');
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
        if(in_array('448',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->diagnosis->get_by_id($id);  
        $data['page_title'] = "Update Diagnosis";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'diagnosis'=>$result['diagnosis'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->diagnosis->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('diagnosis/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('diagnosis', 'diagnosis', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'diagnosis'=>$post['diagnosis'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('71','449');
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
        if(in_array('449',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis->delete($id);
           $response = "Diagnosis successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('71','449');
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
        if(in_array('449',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis->deleteall($post['row_id']);
            $response = "Diagnosis successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->diagnosis->get_by_id($id);  
        $data['page_title'] = $data['form_data']['diagnosis']." detail";
        $this->load->view('diagnosis/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('71','450');
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
        if(in_array('450',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Diagnosis archive list';
        $this->load->helper('url');
        $this->load->view('diagnosis/archive',$data);
    }

    public function archive_ajax_list()
    {
       //unauthorise_permission('71','450');
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
        if(in_array('450',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $this->load->model('diagnosis/diagnosis_archive_model','diagnosis_archive'); 

        $list = $this->diagnosis_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $diagnosis) { 
            $no++;
            $row = array();
            if($diagnosis->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$diagnosis->id.'">'.$check_script; 
            $row[] = $diagnosis->diagnosis;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($diagnosis->created_date)); 
           
          $btnrestore='';
          $btndelete='';
          //if(in_array('452',$users_data['permission']['action'])){
          if(in_array('452',$permission_action) || in_array('121',$permission_section)){
               $btnrestore = ' <a onClick="return restore_diagnosis('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('451',$users_data['permission']['action'])){
          if(in_array('451',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$diagnosis->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->diagnosis_archive->count_all(),
                        "recordsFiltered" => $this->diagnosis_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('71','452');
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
        if(in_array('452',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->restore($id);
           $response = "Diagnosis successfully restore in Diagnosis list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('71','452');
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
        if(in_array('452',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->restoreall($post['row_id']);
            $response = "Diagnosis successfully restore in Diagnosis list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('71','451');
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
        if(in_array('451',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('diagnosis/diagnosis_archive_model','diagnosis_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->diagnosis_archive->trash($id);
           $response = "Diagnosis successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('71','451');
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
        if(in_array('451',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('diagnosis/diagnosis_archive_model','diagnosis_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->diagnosis_archive->trashall($post['row_id']);
            $response = "Diagnosis successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function diagnosis_dropdown()
  {

      $diagnosis_list = $this->diagnosis->diagnosis_list();
      $dropdown = '<option value="">Select Diagnosis</option>'; 
      if(!empty($diagnosis_list))
      {
        foreach($diagnosis_list as $diagnosis)
        {
           $dropdown .= '<option value="'.$diagnosis->id.'">'.$diagnosis->diagnosis.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  function check_unique_value($diagnosis, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->diagnosis->check_unique_value($users_data['parent_id'], $diagnosis, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Diagnosis already exist.');
            $response = false;
        }
        return $response;
    }

}
?>