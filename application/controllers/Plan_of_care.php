<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plan_of_care extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('plan_of_care/Plan_of_care_model','plan_of_care');
        $this->load->library('form_validation');
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
        $data['page_title'] = 'Plan of Care List'; 
        $this->load->view('plan_of_care/list',$data);
    }

    public function ajax_list()
    { 
      ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
        $list = $this->plan_of_care->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $plan_of_care) {
         
            $no++;
            $row = array();
            if($plan_of_care->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$plan_of_care->id.'">'.$check_script; 
            $row[] = $plan_of_care->name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($plan_of_care->created_date)); 
            
          $btnedit='';
          $btndelete='';
          //if(in_array('448',$users_data['permission']['action'])){
          if(in_array('448',$permission_action) || in_array('121',$permission_section)){
               $btnedit = ' <a onClick="return edit_diagnosis('.$plan_of_care->id.');" class="btn-custom" href="javascript:void(0)" style="'.$plan_of_care->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           //if(in_array('449',$users_data['permission']['action'])){
          if(in_array('449',$permission_action) || in_array('121',$permission_section)){
               $btndelete = ' <a class="btn-custom" onClick="return delete_diagnosis('.$plan_of_care->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->plan_of_care->count_all(),
                        "recordsFiltered" => $this->plan_of_care->count_filtered(),
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
        $data['page_title'] = "Add Plan of Care";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'name'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $this->plan_of_care->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('plan_of_care/add',$data);       
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
        $result = $this->plan_of_care->get_by_id($id);  
        $data['page_title'] = "Update Plan of Care";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'name'=>$result['name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->plan_of_care->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('plan_of_care/add',$data);       
      }
    }
     
    private function _validate($id='')
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_check_unique_value['.$id.']'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'name'=>$post['name'], 
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
           $result = $this->plan_of_care->delete($id);
           $response = "Plan of Care successfully deleted.";
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
            $result = $this->plan_of_care->deleteall($post['row_id']);
            $response = "Plan of Care successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->plan_of_care->get_by_id($id);  
        $data['page_title'] = $data['form_data']['name']." detail";
        $this->load->view('plan_of_care/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
  

  
    ///// employee Archive end  ///////////////

  public function dropdown()
  {

      $plan_of_care_list = $this->plan_of_care->list();
      $dropdown = '<option value="">Select Plan of Care</option>'; 
      if(!empty($plan_of_care_list))
      {
        foreach($plan_of_care_list as $plan_of_care)
        {
           $dropdown .= '<option value="'.$plan_of_care->id.'">'.$plan_of_care->name.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  function check_unique_value($plan_of_care, $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->plan_of_care->check_unique_value($users_data['parent_id'], $plan_of_care, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Plan of Care already exist.');
            $response = false;
        }
        return $response;
    }

}
?>