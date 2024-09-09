<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    
function index()
	{
		$this->load->library('restclient');
		$rest = new Restclient();
		$username	='Nupeye';
		$password	='Byf@454nu';
		$headers = array(
			'Authorization: Basic '. base64_encode($username.':'.$password),
		);

		$rest->setUrlServer('https://qa.valuetopup.com/api/v1/catalog/skus');
		$response = $rest->get($headers);
		print_r($response);
	}
	
}