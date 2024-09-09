<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {  
 
    function __construct() 
    {
        parent::__construct(); 
	}
	
	public function index()
	{ 
		$this->load->model('cron/cron_model');
		$birthday_list=$this->cron_model->birthday_list();
		//echo "<pre>";print_r($birthday_list);die;
		if(!empty($birthday_list))
		{
			foreach ($birthday_list as $user_list) 
			{
				$branch_id = $user_list['branch_id'];
				$name = $user_list['name'];
				$email = $user_list['email'];
				//echo $branch_id;
				$usersid = get_user_id($branch_id);
				$users_id = $usersid->users_id;
				$permission = get_branch_permission_status($users_id,101);
				//echo "<pre>"; print_r($permission); exit;
				if($permission[2]->permission_status==1)
				{
				   if(!empty($email))
				   {
				   	$email_format = get_birthday_sms_template_format($branch_id);
				   	
				   	if(!empty($email_format))
					{
							$parameter = array('{Name}'=>$name);
							$message_template =  str_replace(array_keys($parameter),$parameter,$email_format->email_birthday_template);
					
							$email_settings = email_setting($branch_id);
							//echo "<pre>";print_r($email_settings); exit;
							if(!empty($email_settings))
							{
								$cc_email = $email_settings->cc_email;
								$network_email_address = $email_settings->network_email_address;
								$email_password = $email_settings->email_password;
								$port = $email_settings->port;
								$smtp_ssl = $email_settings->smtp_ssl;
								$smtp_address = $email_settings->smtp_address;
								$subject = 'Happy Birthday';
					            //print_r($email_settings);die;
								$this->load->library('email');
								$this->load->library('form_validation');
								$config['protocol']    = 'smtp';
								$config['smtp_host'] = $smtp_address;
								$config['smtp_port'] = $port;
								$config['smtp_user'] = $network_email_address;
								$config['smtp_pass'] = $email_password;
								$config['charset']    = 'utf-8';
								$config['newline']    = "\r\n";
								$config['mailtype'] = 'html';  
								$config['validation'] = TRUE; 
								$this->email->initialize($config);  
								$this->email->from($network_email_address, 'HMAS');
								$this->email->to($email);
								$this->email->subject($subject); 
								if(!empty($attachment))
								{
								  $this->email->attach($attachment);
								} 
								//echo $msg;die;
								$this->email->message($message_template);
								$this->email->send(); 

								 
								
							}	
						
						}
					} 
				}

			}
		}

	}

	public function birthday_sms_cron()
	{ 
		$this->load->model('cron/cron_model');
		$birthday_list=$this->cron_model->birthday_list();
		//echo "<pre>";print_r($birthday_list);die;
		if(!empty($birthday_list))
		{
			foreach ($birthday_list as $user_list) 
			{
				$branch_id = $user_list['branch_id'];
				$mobile = $user_list['mobile'];
				$name = $user_list['name'];
				//$permission = branch_permission_action_list('101',$branch_id,2);
				$usersid = get_user_id($branch_id);
				$users_id = $usersid->users_id;
				$permission = get_branch_permission_status($users_id,101);
				//echo "<pre>"; print_r($permission[1]->status); exit;
				if($permission[1]->permission_status==1)
				{
						if(!empty($mobile))
						{
								$sms_format = get_birthday_sms_template_format($branch_id);
								//echo "<pre>";print_r($sms_format->birthday_sms_template); exit;
								if(!empty($sms_format))
								{
									$parameter = array('{Name}'=>$name);
									$message_template_formate =  str_replace(array_keys($parameter),$parameter,$sms_format->birthday_sms_template);
									//echo $message_template_formate; exit;
									$sms_url = sms_url($branch_id);
									//echo $sms_url; exit;
									if(!empty($sms_url))
									{
										$url = $sms_url;
										$url = str_replace("{mobile_no}",$mobile,$url);
										$url = str_replace("{message}",rawurlencode($message_template_formate),$url);
										//echo $url; exit;
										if(!empty($mobile) && isset($mobile) && !empty($url))
										{
											$ch = curl_init($url);
											curl_setopt($ch, CURLOPT_HEADER, 0);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
											curl_setopt($ch, CURLOPT_VERBOSE, true);
											$output = curl_exec($ch);      
											curl_close($ch); 
											// Display MSGID of the successful sms push
											//echo $output; exit;
										}
									}
									

								}
							
							
						}
					
					 
				}

			}
		}

	}


	public function anniversary_email_cron()
	{ 
		$this->load->model('cron/cron_model');
		$anniversary_list=$this->cron_model->anniversary_list();
		//echo "<pre>";print_r($birthday_list);die;
		if(!empty($anniversary_list))
		{
			foreach ($anniversary_list as $user_list) 
			{
				$branch_id = $user_list['branch_id'];
				$name = $user_list['name'];
				$email = $user_list['email'];
				//echo $branch_id;
				$usersid = get_user_id($branch_id);
				$users_id = $usersid->users_id;
				$permission = get_branch_permission_status($users_id,101);
				//echo "<pre>"; print_r($permission); exit;
				if($permission[2]->permission_status==1)
				{
				   if(!empty($email))
				   {
				   	$email_format = get_birthday_sms_template_format($branch_id);
				   	
				   	if(!empty($email_format))
					{
							$parameter = array('{Name}'=>$name);
							$message_template =  str_replace(array_keys($parameter),$parameter,$email_format->anniversary_email_template);
					
							$email_settings = email_setting($branch_id);
							//echo "<pre>";print_r($email_settings); exit;
							if(!empty($email_settings))
							{
								$cc_email = $email_settings->cc_email;
								$network_email_address = $email_settings->network_email_address;
								$email_password = $email_settings->email_password;
								$port = $email_settings->port;
								$smtp_ssl = $email_settings->smtp_ssl;
								$smtp_address = $email_settings->smtp_address;
								$subject = 'Happy Birthday';
					            //print_r($email_settings);die;
								$this->load->library('email');
								$this->load->library('form_validation');
								$config['protocol']    = 'smtp';
								$config['smtp_host'] = $smtp_address;
								$config['smtp_port'] = $port;
								$config['smtp_user'] = $network_email_address;
								$config['smtp_pass'] = $email_password;
								$config['charset']    = 'utf-8';
								$config['newline']    = "\r\n";
								$config['mailtype'] = 'html';  
								$config['validation'] = TRUE; 
								$this->email->initialize($config);  
								$this->email->from($network_email_address, 'HMAS');
								$this->email->to($email);
								$this->email->subject($subject); 
								if(!empty($attachment))
								{
								  $this->email->attach($attachment);
								} 
								//echo $msg;die;
								$this->email->message($message_template);
								$this->email->send(); 

								 
								
							}	
						
						}
					} 
				}

			}
		}

	}

	public function anniversary_sms_cron()
	{ 
		$this->load->model('cron/cron_model');
		$anniversary_list=$this->cron_model->anniversary_list();
		//echo "<pre>";print_r($birthday_list);die;
		if(!empty($anniversary_list))
		{
			foreach ($anniversary_list as $user_list) 
			{
				$branch_id = $user_list['branch_id'];
				$mobile = $user_list['mobile'];
				$name = $user_list['name'];
				$usersid = get_user_id($branch_id);
				$users_id = $usersid->users_id;
				//$permission = branch_permission_action_list('101',$branch_id,2);
				$permission = get_branch_permission_status($users_id,101);
				//echo "<pre>"; print_r($permission[1]->status); exit;
				if($permission[1]->permission_status==1)
				{
						if(!empty($mobile))
						{
								$sms_format = get_birthday_sms_template_format($branch_id);
								//echo "<pre>";print_r($sms_format->birthday_sms_template); exit;
								if(!empty($sms_format))
								{
									$parameter = array('{Name}'=>$name);
									$message_template_formate =  str_replace(array_keys($parameter),$parameter,$sms_format->anniversary_sms_template);
									//echo $message_template_formate; exit;
									$sms_url = sms_url($branch_id);
									//echo $sms_url; exit;
									if(!empty($sms_url))
									{
										$url = $sms_url;
										$url = str_replace("{mobile_no}",$mobile,$url);
										$url = str_replace("{message}",rawurlencode($message_template_formate),$url);
										//echo $url; exit;
										if(!empty($mobile) && isset($mobile) && !empty($url))
										{
											$ch = curl_init($url);
											curl_setopt($ch, CURLOPT_HEADER, 0);
											curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
											curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
											curl_setopt($ch, CURLOPT_VERBOSE, true);
											$output = curl_exec($ch);      
											curl_close($ch); 
											// Display MSGID of the successful sms push
											//echo $output; exit;
										}
									}
									

								}
							
							
						}
					
					 
				}

			}
		}

	}
	
	public function next_appointment_reminder()
	{ 
	    $this->load->model('cron/cron_model');
	    $this->load->model('general/general_model','general');
		$next_appointment_list = $this->cron_model->next_appointment_list();
		if(!empty($next_appointment_list))
		{
		    foreach($next_appointment_list as $next_appointment)
		    { 
	            $result_template = $this->general->sms_template_format($next_appointment['booking_type'],$next_appointment['branch_id']);
	            //print_r($result_template);die;
	            if(!empty($result_template))
	            {
	                $message_template_formate = $result_template->template;
	                $message_template_formate = str_replace("{mobile_no}",$next_appointment['mobile_no'],$message_template_formate);
	                $message_template_formate = str_replace("{patient_name}",$next_appointment['patient_name'],$message_template_formate);
	                $message_template_formate = str_replace("{booking_code}",$next_appointment['booking_code'],$message_template_formate);
	                 
	                 
	                $result_url = $this->general->sms_url($next_appointment['branch_id']);
	                //print_r($result_url);die;
	                $url = $result_url->url; 
	                if(!empty($url))
	                {
                        $url = str_replace("{mobile_no}",$next_appointment['mobile_no'],$url);
                        $url = str_replace("{message}",rawurlencode($message_template_formate),$url);
                        
                        if(!empty($next_appointment['mobile_no']) && !empty($url))
    					{  
    						$ch = curl_init($url);
    						curl_setopt($ch, CURLOPT_HEADER, 0);
    						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    						curl_setopt($ch, CURLOPT_VERBOSE, true);
    						$output = curl_exec($ch);      
    						curl_close($ch);  
    						// Display MSGID of the successful sms push
    						//echo $output; exit;
    					}
	                }
	            } 
		    }
		} 
	}
	
}
?>