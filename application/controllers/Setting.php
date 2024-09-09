<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('setting/setting_model','setting');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$userdata = $this->session->userdata('auth_users'); 
		$data['page_title'] = "General Setting";
		$this->load->view('setting/setting',$data);
	}

	public function medicine_print_setting(){
		
	}

}
?>	