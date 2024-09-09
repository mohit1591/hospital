<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        //$data['page_title'] = 'OPD Booking List';
        $dlr_url = 'http://www.yourdomainname.domain/yourdlrpage&custom=XX'; 
        
    }

}
?>    