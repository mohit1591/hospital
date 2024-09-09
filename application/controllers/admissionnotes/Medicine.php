<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('admissionnotes/medicine/medicine_model','medicine');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('73','460');
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
        if(in_array('460',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Medicine List'; 
        $this->load->view('admissionnotes/medicine/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('73','460');
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
        if(in_array('460',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
         $users_data = $this->session->userdata('auth_users');
        $list = $this->medicine->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($medicine->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine->id.'">'.$check_script; 
            $row[] = $medicine->medicine_name;
            $row[] = $medicine->medicine_unit;  
            $row[] = $medicine->salt;
            $row[] = $medicine->company_name;      
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
           
          $btnedit='';
          $btndelete='';
          //if(in_array('462',$users_data['permission']['action'])){
          if(in_array('462',$permission_action) || in_array('121',$permission_section)){
               $btnedit = ' <a onClick="return edit_medicine('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" style="'.$medicine->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          // if(in_array('463',$users_data['permission']['action'])){
          if(in_array('463',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_medicine('.$medicine->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine->count_all(),
                        "recordsFiltered" => $this->medicine->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    

    
    public function add()
    {
        //unauthorise_permission('73','461');
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
        if(in_array('461',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = "Add Medicine";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine'=>"",
                                  'type'=>"",
                                  'brand'=>"",
                                  'salt'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/medicine/add',$data);       
    }
    
    public function edit($id="")
    {
     // unauthorise_permission('73','462');
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
        if(in_array('462',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->medicine->get_by_id($id);  
        $data['page_title'] = "Update Medicine";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine'=>$result['medicine_name'], 
                                  'type'=>$result['medicine_unit'],
                                  'salt'=>$result['salt'],
                                  'brand'=>$result['company_name'],
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admissionnotes/medicine/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine', 'medicine', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine'=>$post['medicine'],
                                        'type'=>$post['type'],
                                        'salt'=>$post['salt'],
                                        'brand'=>$post['brand'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('73','463');
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
        if(in_array('463',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine->delete($id);
           $response = "Medicine successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('73','463');
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
        if(in_array('463',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine->deleteall($post['row_id']);
            $response = "Medicine successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine']." detail";
        $this->load->view('admissionnotes/medicine/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('73','464');
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
        if(in_array('464',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $data['page_title'] = 'Medicine Archive List';
        $this->load->helper('url');
        $this->load->view('admissionnotes/medicine/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('73','464');
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
        if(in_array('464',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/medicine/medicine_archive_model','medicine_archive'); 

        $list = $this->medicine_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $medicine) { 
            $no++;
            $row = array();
            if($medicine->status==1)
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
            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$medicine->id.'">'.$check_script; 
            $row[] = $medicine->medicine_name;
            $row[] = $medicine->medicine_unit;  
            $row[] = $medicine->salt;
            $row[] = $medicine->company_name;   
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($medicine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('466',$users_data['permission']['action'])){
          if(in_array('466',$permission_action) || in_array('121',$permission_section)){
               $btnrestore = ' <a onClick="return restore_medicine('.$medicine->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          //if(in_array('465',$users_data['permission']['action'])){
          if(in_array('465',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a onClick="return trash('.$medicine->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_archive->count_all(),
                        "recordsFiltered" => $this->medicine_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('73','466');
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
        if(in_array('466',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/medicine/medicine_archive_model','medicine_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_archive->restore($id);
           $response = "Medicine successfully restore in Medicine list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('73','466');
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
        if(in_array('466',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/medicine/medicine_archive_model','medicine_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_archive->restoreall($post['row_id']);
            $response = "Medicine successfully restore in Medicine list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('73','465');
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
        if(in_array('465',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/medicine/medicine_archive_model','medicine_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_archive->trash($id);
           $response = "Medicine successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('73','465');
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
        if(in_array('465',$permission_action) || in_array('121',$permission_section))
        {

        }
        else
        {
          redirect('401');
        }
        $this->load->model('admissionnotes/medicine/medicine_archive_model','medicine_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_archive->trashall($post['row_id']);
            $response = "Medicine successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_dropdown()
  {

      $medicine_list = $this->medicine->medicine_list();
      $dropdown = '<option value="">Select Medicine</option>'; 
      if(!empty($medicine_list))
      {
        foreach($medicine_list as $medicine)
        {
           $dropdown .= '<option value="'.$medicine->id.'">'.$medicine->medicine.'</option>';
        }
      } 
      echo $dropdown; 
  }

  function get_vals($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->medicine->get_vals($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    
  function check_unique_value($medicine, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->medicine->check_unique_value($users_data['parent_id'], $medicine, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Medicine name already exist.');
            $response = false;
        }
        return $response;
    }

}
?>