<?php 
class General { 
	
	protected $CI;
	
	public function __construct()
    {
            // Assign the CodeIgniter super-object
            $this->CI =& get_instance();
    }
	
	public function email($to,$subject,$msg,$cc="",$attachment="",$type='',$module='',$template='',$parameter='')
	{ 
		
		echo "ddd";die;
		$network_email_address = SMTP_EMAIL;
		$email_password = SMTP_PASSWORD;
		$port = SMTP_PORT;
		$smtp_address =SMTP_HOST;
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
		$this->CI->email->from($smtp_address, 'Pathology');
		$this->CI->email->to($to);
		$this->CI->email->subject($subject); 
		if(!empty($attachment))
        {
        	//$this->CI->email->attach($attachment);
        } 
        //echo $msg;die;
		$this->CI->email->message($msg);
		$this->CI->email->send();  
		//echo $this->CI->email->print_debugger();die;
	} 
	
}