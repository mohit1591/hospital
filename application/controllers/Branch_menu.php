<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_menu extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('branch_menu/branch_menu_model','branch_menu');
		$this->load->library('form_validation');
    }

    
	function create_menu($id='')
	{
		$this->branch_menu->create_menu($id);
	}
}    	