<?php 
class General_functions { 
	
	protected $CI;
	
	public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
    }
	
	public function email($to,$subject,$msg,$cc="",$attachment="",$type='',$module='',$template='',$parameter='')
	{  
		//$type defines 1 for  and 2 is for defalt email for forgot password and reset password
		if($type==1)
		{
		$get_data = get_sms_setting($module,2); //email 2
			if($get_data==1)
			{
				$email_template = get_email_template($template);
				$subject='';
				if(isset($email_template->subject))
				{
					$subject = $email_template->subject;	
				}
				
				if(!empty($email_template))
				{
				 $msg =  str_replace(array_keys($parameter),$parameter,$email_template->template);
				}

				$email_settings = email_setting();
				if(!empty($email_settings))
				{
					$cc_email = $email_settings->cc_email;
					$network_email_address = $email_settings->network_email_address;
					$email_password = $email_settings->email_password;
					$port = $email_settings->port;
					$smtp_ssl = $email_settings->smtp_ssl;
					$smtp_address = $email_settings->smtp_address;

                    //print_r($email_settings);die;
					$this->CI->load->library('email');
					$this->CI->load->library('form_validation');
					$config['protocol']    = 'smtp';
					$config['smtp_host'] = $smtp_address;
					$config['smtp_port'] = $port;
					$config['smtp_user'] = $network_email_address;
					$config['smtp_pass'] = $email_password;
					$config['charset']    = 'utf-8';
					$config['newline']    = "\r\n";
					$config['mailtype'] = 'html';  
					$config['validation'] = TRUE; 
					$this->CI->email->initialize($config);  
					$this->CI->email->from($network_email_address, 'HMAS');
					$this->CI->email->to($to);
					$this->CI->email->subject($subject); 
					if(!empty($attachment))
					{
					  $this->CI->email->attach($attachment);
					} 
					//echo $msg;die;
					$this->CI->email->message($msg);
					$this->CI->email->send();  
					//echo $this->CI->email->print_debugger();die;
				}
								
			 }
		}
		

	}


	public function send_birh_anni_email($to,$subject,$msg='',$cc="",$attachment="",$type='',$person_id='')
	{  
		$email_settings = email_setting();
		if(!empty($email_settings))
		{
			$cc_email = $email_settings->cc_email;
			$network_email_address = $email_settings->network_email_address;
			$email_password = $email_settings->email_password;
			$port = $email_settings->port;
			$smtp_ssl = $email_settings->smtp_ssl;
			$smtp_address = $email_settings->smtp_address;

            //print_r($email_settings);die;
			$this->CI->load->library('email');
			$this->CI->load->library('form_validation');
			$config['protocol']    = 'smtp';
			$config['smtp_host'] = $smtp_address;
			$config['smtp_port'] = $port;
			$config['smtp_user'] = $network_email_address;
			$config['smtp_pass'] = $email_password;
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['mailtype'] = 'html';  
			$config['validation'] = TRUE; 
			$this->CI->email->initialize($config);  
			$this->CI->email->from($network_email_address, 'HMAS');
			$this->CI->email->to($to);
			$this->CI->email->subject($subject); 
			if(!empty($attachment))
			{
			  $this->CI->email->attach($attachment);
			} 
			//echo $msg;die;
			$this->CI->email->message($msg);
			$this->CI->email->send(); 

			//$this->CI->load->model('general/general_model','generals');
			//$this->CI->generals->update_email($type,$person_id); 
			//echo $this->CI->email->print_debugger();die;
		}
								
			 
		
		

	} 
	
	public function send_renewal_mail($to_email='',$message='')
	{	
		
		//$email_settings = email_setting();
		//print_r($email_settings);
	/*	if(!empty($email_settings))
		{*/
			//$cc_email = $email_settings->cc_email;
			$network_email_address = 'noreply@hospitalms.in';
			$email_password = 'noreply@123';
			$port = '465';
			$smtp_ssl = '';
			$smtp_address = 'ssl://mail.hospitalms.in';
			$subject = 'Hospital Renewal mail '. date('d-m-Y');
			//print_r($email_settings);die;
			//$this->CI->load->library('email');
			//$this->CI->load->library('form_validation');
			/*$this->CI->email->from($network_email_address,'HMAS');
	        $this->CI->email->to($to_email);
	        $this->CI->email->set_mailtype("html");
	        $this->CI->email->subject($subject);
	        $this->CI->email->message($message);
	        
	        if($this->CI->email->send())
	        {
	        	echo $to_email;	
	        }
	        else
	        {
	        	echo "fdsfdfd";
	        }*/


	     
         
        
         
         /*$header = "From:".$network_email_address." \r\n";
         
         $header .= "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html\r\n";
         
         $retval = mail ($to_email,$subject,'HIIII',$header);
         
         if( $retval == true ) {
         	//echo $to_email.','.$subject.','.$message.','.$header;
            echo "dddMessage sent successfully...";
         }else {
            echo "Message could not be sent...";
         }
		*/
		
		//echo $to_email; exit;
	        //$this->CI->email->clear();
	        //echo $this->CI->email->print_debugger(); exit;
			$this->CI->load->library('form_validation');
			$config['protocol']    = 'smtp';
			$config['smtp_host'] = $smtp_address;
			$config['smtp_port'] = $port;
			$config['smtp_user'] = $network_email_address;
			$config['smtp_pass'] = $email_password;
			$config['charset']    = 'utf-8';
			$config['newline']    = "\r\n";
			$config['mailtype'] = 'html';  
			$config['validation'] = TRUE; 
			$this->CI->email->initialize($config);  
			$this->CI->email->from($network_email_address, 'HMAS');
			$this->CI->email->to($to_email); //$to_email
			$this->CI->email->subject($subject); 
			
			$this->CI->email->message($message);
			$this->CI->email->send(); 
			//echo $this->CI->email->print_debugger();die;
	//	}

	}
	
}