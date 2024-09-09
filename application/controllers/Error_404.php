<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Error_404 extends CI_Controller {

	public function __construct()
	{
		parent::__construct(); 
	}

	public function error_404()
	{
		$data['page_title'] = 'Page not found'; 
		$this->load->view('error/404',$data);
	}

	public function error_401()
	{
		$data['page_title'] = 'Unauthorise access'; 
		$this->load->view('error/unauthorised',$data);
	} 


}
?>