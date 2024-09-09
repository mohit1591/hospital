<?php 
class General_library { 
	
	protected $CI;
	
	public function __construct()
        {
                // Assign the CodeIgniter super-object
                $this->CI =& get_instance();
        }
	
	public function email($to,$subject,$msg,$cc="",$attachment="",$type='',$module='',$template='',$parameter='',$branch_id="")
	{  
		    
                    $get_data = "";
		    if(!empty($module))
		    {
                       $get_data = get_sms_setting($module,2); //email 2
		    } 
			if($get_data==1 || empty($module))
			{ 
				if(!empty($subject))
				{
                                    $msg = $msg;
				    $subject = $subject;
				}
				else
				{
					$email_template = get_email_template($template,$branch_id);
				    $subject = $email_template->subject;

				    if(!empty($email_template))
					{
					 $msg =  str_replace(array_keys($parameter),$parameter,$email_template->template);
					}
				}

				$email_settings = email_setting($branch_id);
//print_r($email_settings);die;
				if(!empty($email_settings))
				{
					$cc_email = $email_settings->cc_email;
					$network_email_address = $email_settings->network_email_address;
					$email_password = $email_settings->email_password;
					$port = $email_settings->port;
					$smtp_ssl = $email_settings->smtp_ssl;
					$smtp_address = $email_settings->smtp_address;

                    //print_r($email_settings);die;
					//$this->CI->load->library('email');
					$this->CI->load->library('form_validation');
					
					$config = Array(
                            'protocol' => 'smtp',
                            'smtp_host' => $smtp_address,
                            'smtp_port' => $port,
                            'smtp_user' => $network_email_address,
                            'smtp_pass' => $email_password,
                            //'mailtype'  => 'html', 
                            //'charset'   => 'utf-8',//'iso-8859-1',
                            'validation'=>TRUE,
                        );
                        $config['crlf'] = "\r\n";
                        $config['newline'] = "\r\n";
                        $config['charset']    = 'utf-8';
					    $config['newline']    = "\r\n";
					    $config['mailtype'] = 'html'; 
                        
                        $this->CI->load->library('email', $config);
                        $this->CI->email->set_newline("\r\n");
                        
                    $this->CI->email->from($network_email_address, '');
					$this->CI->email->to($to);
					$this->CI->email->subject($subject); 
					if(!empty($attachment))
					{  
					  $this->CI->email->attach($attachment);
					} 
					
					$this->CI->email->message($msg);
                   $this->CI->email->send();
                   /*
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
					*/
/* if ( ! $this->CI->email->send())
{
       return false;
}
else
{
 return true;
} */
  
			//echo $this->CI->email->print_debugger();die;
				}
								
			 }  
	} 
	
}