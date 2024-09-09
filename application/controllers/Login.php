<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
      $this->load->model('branch/branch_model','branch');
    }

    
	public function index()
	{
        $data['status'] = 0;
        $data['flash_msg'] = '';
        $data['error_msg'] = '';
		$data['page_title'] = 'Login :: Hospital Management System';
		$post = $this->input->post();
		$data['form_error'] = [];
		$data['form_data'] = array('username'=>'','password'=>'','email'=>'');
		if(isset($post) && !empty($post))
		{
			$this->load->library('form_validation');
			$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
			$this->form_validation->set_rules('username', 'Username', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required');
			if ($this->form_validation->run() == TRUE)
            {
            	$this->load->model('login/login_model');
            	$result = $this->login_model->auth_users();
            	if(!empty($result))
            	{
            	
            	  
            	  
                    if($result['users_role']!='1')
                    {
                    //check branch status 
                    if($result['branch_current_status']==1)
                    {
                    if($result['status']=='1') //user login status
                    { 
                        //fetch branch details when logged in by branch
                        //echo "<pre>";print_r($result); die;
                      if($result['users_role']==2)
                      {
                            $branch_details = $this->branch->get_branch_details($result['parent_id']);
                            if(!empty($branch_details))
                            {
                                $today_date = date('Y-m-d');
                                if($branch_details[0]->branch_type==0)
                                { 
                                    if($branch_details[0]->start_date<=$today_date && $branch_details[0]->end_date>=$today_date)
                                    {
                                        $this->session->set_userdata('auth_users',$result); 
                                        $this->get_sub_branches();
                                        $this->get_parent_branches();

                                    /* login activity */
                                     $update_loin_activity=$this->login_model->update_login_activity($result['id'],$result['parent_id']);
                                    /* login activity */
                                     

                                    }else{
                                        $response = '<strong>Please Contact Sara Technologies.<br>Contact No.:8506080374<br> ** Thank you **</strong>';
                                        echo $response;die;
                                    }
                                }
                                else if($branch_details[0]->branch_type==1)
                                { 
                                    if($branch_details[0]->start_date<=$today_date && $branch_details[0]->end_date>=$today_date)
                                    {
                                        $this->session->set_userdata('auth_users',$result);
                                        $this->get_sub_branches();
                                        $this->get_parent_branches();
                                        /* login activity */
                                     $update_loin_activity=$this->login_model->update_login_activity($result['id'],$result['parent_id']);
                                    /* login activity */
                                    }
                                    else
                                    {
                                        $response = '<strong>Please Contact Sara Technologies.<br>Contact No.:8506080374<br> ** Thank you **</strong>';
                                        echo $response;die; 
                                    }
                                }
                                else
                                {
                                   
                                }
                            }else{
                                $data['error_msg'] = 'Invalid username & password';
                                $data['form_data'] = array('username'=>$post['username'],'password'=>$post['password']);
                                $data['form_error'] = validation_errors(); 
                                $this->load->view('login/login_form',$data); 
                            }
                        }else{
                            $this->session->set_userdata('auth_users',$result); 
                            /* login activity */
                                     $update_loin_activity=$this->login_model->update_login_activity($result['id'],$result['parent_id']);
                                    /* login activity */
                        }
                    	 echo 1;
                        
                        return false;
                    }
                    else
                    {
                    	$response = array('status'=>2,'msg'=>'<strong>Please Contact Sara Technologies.<br>Contact No.:8506080374<br> ** Thank you **</strong>');
                        echo json_encode($response);die;
                    }
            	
                }
                else
                {
                    //////messaage
                    $response = '<strong>Please Contact Sara Technologies.<br>Contact No.:8506080374<br> ** Thank you **</strong>';
                    echo $response;die; 
                }
                ////admin login
                
            }
            else
            {
                $this->session->set_userdata('auth_users',$result); 
                echo 1;
                return false;
            }

                
            	/*
                    if($result['status']=='1')
                    { 
                        
                      if($result['users_role']==2){
                            $branch_details = $this->branch->get_branch_details($result['parent_id']);
                          
                            if(!empty($branch_details)){
                                $today_date = date('Y-m-d');
                                if($branch_details[0]->branch_type==0){ 
                                    if($branch_details[0]->start_date<=$today_date && $branch_details[0]->end_date>=$today_date){
                                        $this->session->set_userdata('auth_users',$result); 
                                        $this->get_sub_branches();
                                        $this->get_parent_branches();
                                     

                                    }else{
                                        $response = 'Unable To Login: Training Time Expired.';
                                        echo $response;die;
                                    }
                                }
                                else if($branch_details[0]->branch_type==1){ 
                                    $this->session->set_userdata('auth_users',$result);
                                     $this->get_sub_branches();
                                      $this->get_parent_branches();
                                    
                                }else{
                                   
                                }
                            }else{
                                $data['error_msg'] = 'Invalid username & password';
                                $data['form_data'] = array('username'=>$post['username'],'password'=>$post['password']);
                                $data['form_error'] = validation_errors(); 
                                $this->load->view('login/login_form',$data); 
                            }
                        }else{
                            $this->session->set_userdata('auth_users',$result); 
                        }
                    	 echo 1;
                        
                        return false;
                    }
                    else
                    {
                    	$response = array('status'=>2,'msg'=>'Your account inactive so please contact to support.');
                        echo json_encode($response);die;
                    }
            	*/}
            	else
            	{
                    $data['error_msg'] = 'Invalid username & password';
                    $data['form_data'] = array('username'=>$post['username'],'password'=>$post['password']);
                    $data['form_error'] = validation_errors(); 
                    $this->load->view('login/login_form',$data); 
            	}
                
            }
            else
            {
                $data['form_data'] = array('username'=>$post['username'],'password'=>$post['password']);
                $data['form_error'] = validation_errors(); 
                $this->load->view('login/login_form',$data); 
            }
		}
		else
		{
			$this->load->view('login/login',$data);
		}
		
	}

	public function logout()
	{
		$this->session->unset_userdata('auth_users');
        session_destroy();
		redirect(base_url().'login');
	}

	public function forgot_password()
	{
        $data['status'] = 0;
        $data['flash_msg'] = '';
        $data['error_msg'] = ''; 
        $post = $this->input->post();
        $data['form_data'] = array('email'=>$post['email']);
         if(isset($post) && !empty($post) && !empty($post['email']))
         {
			$this->load->model('login/login_model');
			$result = $this->login_model->get_users($post['email']);

			if(!empty($result))
			{
               if($result[0]->status==1)
               {
                    $this->load->library('general');
                    $token = md5($result[0]->email.time());
                    $token_time = date('Y-m-d H:i', strtotime('+1 hour'));
                    $this->login_model->set_password_token($result[0]->id,$token,$token_time);
                    $to = $result[0]->email;
                    $this->load->model('email_template/Email_template_model','email_template');
                    $email_type='1';
                    $email_template = $this->email_template->get_email_template($email_type);
                    $message = ''; 
                    if(!empty($email_template))
                    {
                        $subject = $email_template[0]->subject;
                        $reset_link = base_url()."reset-password/$token";
                        $search = array("{name}","{url}");
                        $relaced = array($result[0]->username,$reset_link);
                        $message=str_replace($search,$relaced,$email_template[0]->message);

                        $this->general->email($to,$subject,$message);  
                        $data['flash_msg'] = 'Reset password link sent on your email address.';
                        $data['status'] = 1;
                    }
               } 
               else
               {
                    $data['error_msg'] = 'Your account inactive so please contact to support.'; 
               }
			}
			else
			{
                $data['error_msg'] = 'Invalid email address.';  
			}
         }
         else
            {
                $data['error_msg'] = 'Please enter your email address.'; 
            }
         $this->load->view('login/forgot_password',$data);    
	}

    public function reset_password($token="")
    {
       if(!empty($token))
       {
         $this->load->model('login/login_model');
         $result = $this->login_model->get_users("",$token); 
         $data['page_title'] = "Reset Password :: Pathology";
         $data['form_error'] = []; 
         if(!empty($result))
         {
            $data['form_data'] = array('password'=>'','cpassword'=>'');
            $current_time = date('Y-m-d H:i');
            if($result[0]->token_expire>=$current_time)
            { 
                $data['action'] = 1;
                $post = $this->input->post();
                ///////// Validation ///////////////
                if(isset($post) && !empty($post))
                {
                    $this->load->library('form_validation');
                    $this->form_validation->set_error_delimiters('<div class="error_msg">', '</div>');
                    $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[15]');
                    $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required|matches[password]');
                    if ($this->form_validation->run() == TRUE)
                    {
                        $this->load->model('login/login_model');
                        $result = $this->login_model->reset_password($token);
                        echo '<div class="flash_msg">Your password successfully udpated.</div>';die;
                    } 
                    else
                    {
                         $data['form_data'] = array('password'=>$post['password'],'cpassword'=>$post['cpassword']);
                         $data['form_error'] = validation_errors(); 
                    }
                    $this->load->view('login/reset_password_form',$data);
                    return false;
                } 
               /////////////////////////////////// 
               
               $this->load->view('login/reset_password',$data);
            }
            else
            { 
                $data['action'] = 2; 
                $data['message'] = 'Sorry, this link expired.';
                $this->load->view('login/reset_password',$data);

            }
         }
        else
        {
            $data['action'] = 2; 
            $data['message'] = 'Invalid token.';
            $this->load->view('login/reset_password',$data);
        }

         
       }
    }

    public function change_password()
    {

        $data['page_title'] = "Change password";
        $post = $this->input->post();
        $data['form_error'] = [];
        $data['form_data'] = array(
                                    'old_password'=>'',
                                    'password'=>'',
                                    'cpassword'=>''
                                  );
        if(isset($post) && !empty($post))
        {
            $this->load->library('form_validation');
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            $this->form_validation->set_rules('old_password', 'old password', 'required|callback_check_old_password');
            $this->form_validation->set_rules('password', 'new password', 'trim|required|min_length[6]|max_length[20]');
            $this->form_validation->set_rules('cpassword', 'confirm password', 'trim|required|min_length[6]|max_length[20]|matches[password]');
            if ($this->form_validation->run() == TRUE)
            {
                $this->load->model('login/login_model');
                $users_data = $this->session->userdata('auth_users');
                $user_detail= $this->login_model->get_by_user_id($users_data['id']);
               
        
                 $this->login_model->change_password();
                  echo '1';
                 if(in_array('640',$users_data['permission']['action']))
                  {
                    
                    if(!empty($user_detail[0]->contact_no))
                    {
                        
                      send_sms('reset_password',13,$user_detail[0]->branch_name,$user_detail[0]->contact_no,array('{Name}'=>$user_detail[0]->branch_name)); 
                    }

                   }

                if(in_array('641',$users_data['permission']['action']))
                  {
                    if(!empty($users_data['email']))
                    {
                      
                      $this->load->library('general_functions');
                      $this->general_functions->email($users_data['email'],'','','','','1','reset_password','13',array('{name}'=>$user_detail[0]->branch_name));
                       
                    }
                  } 

               
                return false;
            }
            else
            {
                $data['form_data'] = array(
                                            'old_password'=>$post['old_password'],
                                            'password'=>$post['cpassword'],
                                            'cpassword'=>$post['cpassword']
                                          );
                $data['form_error'] = validation_errors(); 
            }
        }
       
        $this->load->view('login/change_password',$data);
    }

    public function check_old_password($str)
    { 
        if(empty($str))
        {
            $this->form_validation->set_message('check_old_password', 'The old password field is required.');
            return false;
        }
        $this->load->model('login/login_model');
        $result = $this->login_model->check_old_password($str); //print_r($result);
        if(!empty($result))
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('check_old_password', 'Invalid old password.');
            return false;
        }
    }
        public function get_sub_branches($parent_id=''){
            $users = $this->session->userdata('auth_users');
           
            $this->load->model('general/general_model'); 
            $nested = nested_branch($users['parent_id']);
            
         
            $sub_branch_details = $this->general_model->get_sub_branch_details($nested);
          
            $branches_data  =array();
            $i=0;
            if(!empty($sub_branch_details)){
                foreach ($sub_branch_details as $key => $value) {
                    $branches_data[$i]['id'] = $sub_branch_details[$i]->id;
                    $branches_data[$i]['branch_name'] = $sub_branch_details[$i]->branch_name;
                    $i = $i+1;
                }
            }

           $this->session->set_userdata('sub_branches_data',$branches_data);

        }
         public function get_parent_branches($parent_id=''){
            $users = $this->session->userdata('auth_users');
           
            $this->load->model('general/general_model'); 
            $nested = nested_parent_branch($users['parent_id']);
            
            if (($key = array_search('0',$nested)) !== false) {
                unset($nested[$key]);
            }
            $nested  = array_unique($nested);
           
            
           $this->session->set_userdata('parent_branches_data',$nested);

        }
    
    

}
