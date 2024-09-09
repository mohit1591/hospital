<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_status extends CI_Controller {
 
    function __construct() 
    {
        parent::__construct();  
        auth_users();  
        $this->load->model('token_status/token_model','token');
        $this->load->model('token_status/path_token_model','path_token');
        $this->load->library('form_validation');
    }

  public function opd_token_status()
  {
     $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->token->get_branch_token_type($users_data['parent_id']);       
        $data['page_title'] = 'Token Status list';
        $data['specialization_list']=$this->general_model->specialization_list();       
        $this->load->view('token_status/opd_token_status',$data);
  }

    public function ajax_list()
    {   
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $branch_type=$this->token->get_branch_token_type($users_data['parent_id']);

        $list = $this->token->get_datatables();  

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array();
            $wait=$prog=$done=$emer=$can='';
            if($test->status==0)
            {
              $wait='selected';
            }
            elseif($test->status==1)
            {
              $prog='selected';
            }
            elseif($test->status==2)
            {
              $done='selected';
            }
             elseif($test->status==3)
            {
               $emer='selected';
            }
            elseif($test->status==4)
            {
              $can='selected';
            }
            $booking_status = '<select name="status_update" id="status_update" class="m_input_default" onchange="update_status('.$test->id.',this.value)">
             <option value="0" '.$wait.'>Waiting</option>
             <option value="1" '.$prog.'>In Progress</option>
             <option value="2" '.$done.'>Done</option>
             <option value="3" '.$emer.'>Emergency</option>
             <option value="4" '.$can.'>Cancel</option>
            </select>';            
          
          
            $row[] = $test->token_no;
            $row[] = $test->patient_name;
            if($branch_type==1){
                $row[] = ucfirst(get_doctor_name($test->doctor_id));
            }
            else{
               $row[] = ucfirst(get_specilization_name($test->specialization_id));
            }
            $row[] =  $booking_status;
            $data[] = $row;
            $i++;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->token->count_all(),
                        "recordsFiltered" => $this->token->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }


    public function advance_search()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('token_search', $post);
        }
        $opd_search = $this->session->userdata('token_search');
        if(isset($opd_search) && !empty($opd_search))
        {
            $data['form_data'] = $opd_search;
        }
        return 'ok';
    }

   public function update_token_status()
   {
    $post = $this->input->post();
    $result = $this->token->update_token_status($post['token_id'], $post['token_status']); 
    if($result)
    {
       $response = "Status successfully updated.";
        echo $response;
    } 
   }

// for display

  public function opd_token()
  {
      $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->token->get_branch_token_type($users_data['parent_id']);
        $data['page_title'] = 'Token Display list';
        $data['specialization_list']=$this->general_model->specialization_list();  
       
        $this->load->view('token_status/opd_token_display',$data);
  }

    public function ajax_list_display()
    {   
         // unauthorise_permission(85,529);
         $users_data = $this->session->userdata('auth_users');
         $branch_type=$this->token->get_branch_token_type($users_data['parent_id']);
        $list = $this->token->get_datatables();  
        //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array();
            $wait=$prog=$done='';
            if($test->status==0)
            {
              $wait='selected';
            }
            elseif($test->status==1)
            {
              $prog='selected';
            }
            elseif($test->status==2)
            {
              $done='selected';
            }
           
            if($test->status==0)
            {
                $booking_status = '<font color="red">Waiting</font>';
            }   
            elseif($test->status==1){
                $booking_status = '<font color="green">In Progress</font>';
            }                 
            elseif($test->status==2){
                $booking_status = '<font color="blue">Done</font>';
            }
            elseif($test->status==3){
                $booking_status = '<font color="warning">Emergency</font>';
            }                 
            elseif($test->status==4){
                $booking_status = '<font color="danger">Cancel</font>';
            }  
            
            $row[] = $test->token_no;
            $row[] = $test->patient_name;
            if($branch_type==2){
                
                 $row[] = ucfirst(get_specilization_name($test->specialization_id));
            }
            else{
                
                $doc_name = get_doctor_name($test->doctor_id);
                $row[] = ucfirst($doc_name);
              
            }
            $row[] =  $booking_status;
            $data[] = $row;
            $i++;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->token->count_all(),
                        "recordsFiltered" => $this->token->count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

 public function token_patient_display()
  {
      $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->token->get_branch_token_type($users_data['parent_id']);
        $data['page_title'] = 'Token Display list';
        $data['specialization_list']=$this->general_model->specialization_list();  
       
        $this->load->view('token_status/opd_token_patient_display',$data);
  }



  // pathology display setting


  public function path_token_status()
  {
     $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->path_token->path_get_branch_token_type($users_data['parent_id']);       
        $data['page_title'] = 'Token Status list';
        $data['dept_list'] = $this->general_model->active_department_list(5,$users_data['parent_id']);       
        $this->load->view('token_status/path_token_status',$data);
  }

    public function path_ajax_list()
    {   
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $branch_type=$this->path_token->path_get_branch_token_type($users_data['parent_id']);

        $list = $this->path_token->path_get_datatables();  
        //print_r($list);die();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array();
            $wait=$prog=$done=$emer=$can='';
            if($test->status==0)
            {
              $wait='selected';
            }
            elseif($test->status==1)
            {
              $prog='selected';
            }
            elseif($test->status==2)
            {
              $done='selected';
            }
             elseif($test->status==3)
            {
               $emer='selected';
            }
            elseif($test->status==4)
            {
              $can='selected';
            }
            $booking_status = '<select name="status_update" id="status_update" class="m_input_default" onchange="update_status('.$test->id.',this.value)">
             <option value="0" '.$wait.'>Waiting</option>
             <option value="1" '.$prog.'>In Progress</option>
             <option value="2" '.$done.'>Done</option>
             <option value="3" '.$emer.'>Emergency</option>
             <option value="4" '.$can.'>Cancel</option>
            </select>';            
          
          
            $row[] = $test->token_no;
            $row[] = $test->lab_reg_no;
            if($branch_type==1){
               $dep_name = get_pathology_department($test->department_id,$users_data['parent_id']);
               $row[]=$dep_name[0]->department;
            }
            $row[] = $test->patient_name;
            $row[] =  $booking_status;
            $data[] = $row;
            $i++;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->path_token->path_count_all(),
                        "recordsFiltered" => $this->path_token->path_count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

    
    public function path_advance_search()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('path_token_search', $post);
        }
        $opd_search = $this->session->userdata('path_token_search');
        if(isset($opd_search) && !empty($opd_search))
        {
            $data['form_data'] = $opd_search;
        }
        return 'ok';
    }


   public function path_update_token_status()
   {
    $post = $this->input->post();
    $result = $this->path_token->path_update_token_status($post['token_id'], $post['token_status']); 
    if($result)
    {
       $response = "Status successfully updated.";
        echo $response;
    } 
   }

// for display

  public function path_token_display()
  {
      $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->path_token->path_get_branch_token_type($users_data['parent_id']);
        $data['page_title'] = 'Token Status Display list';
        $data['dept_list'] = $this->general_model->active_department_list(5,$users_data['parent_id']);     
       
        $this->load->view('token_status/path_token_display',$data);
  }

    public function path_ajax_list_display()
    {   
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
         $branch_type=$this->path_token->path_get_branch_token_type($users_data['parent_id']);
        $list = $this->path_token->path_get_datatables();  

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $test) 
        {
            $no++;
            $row = array();
            $wait=$prog=$done='';
            if($test->status==0)
            {
              $wait='selected';
            }
            elseif($test->status==1)
            {
              $prog='selected';
            }
            elseif($test->status==2)
            {
              $done='selected';
            }
           
            if($test->status==0)
            {
                $booking_status = '<font color="red">Waiting</font>';
            }   
            elseif($test->status==1){
                $booking_status = '<font color="green">In Progress</font>';
            }                 
            elseif($test->status==2){
                $booking_status = '<font color="blue">Done</font>';
            }
            elseif($test->status==3){
                $booking_status = '<font color="warning">Emergency</font>';
            }                 
            elseif($test->status==4){
                $booking_status = '<font color="danger">Cancel</font>';
            }  
          
            $row[] = $test->token_no;
            $row[] = $test->lab_reg_no;
            if($branch_type==1){
               $dep_name = get_pathology_department($test->department_id,$users_data['parent_id']);
               $row[]=$dep_name[0]->department;
            }
            $row[] = $test->patient_name;
            $row[] =  $booking_status;
            $data[] = $row;
            $i++;

        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->path_token->path_count_all(),
                        "recordsFiltered" => $this->path_token->path_count_filtered(),
                        "data" => $data,
                );
        echo json_encode($output);
    }

 public function path_token_patient_display()
  {
      $this->load->model('general/general_model'); 
       // unauthorise_permission(85,529);
        $users_data = $this->session->userdata('auth_users');
        $data['branch_type']=$this->path_token->path_get_branch_token_type($users_data['parent_id']);
        $data['page_title'] = 'Patient list';
        $data['specialization_list']=$this->general_model->specialization_list();  
       
        $this->load->view('token_status/path_token_patient_display',$data);
  }
// please write code above    
}
?>