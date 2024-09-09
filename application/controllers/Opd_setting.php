<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_setting extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
      $this->load->model('opd_setting/opd_print_setting_model'); 
    }

    public function index()
    {

        $data['page_title'] = "OPD Print Setting";
        $post = $this->input->post();
        $data['form_error'] = [];
        $data['form_data'] = array('module[]'=>array());
        $data['form_data'] = $this->opd_print_setting_model->get_print_setting();
        if(isset($post) && !empty($post))
        {  
			$user_detail= $this->opd_print_setting_model->save_setting();
			echo 1;die;
        }
       
        $this->load->view('opd_setting/opd_print_setting',$data);
    }

  }  
?>