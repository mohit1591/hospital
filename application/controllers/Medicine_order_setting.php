<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_order_setting extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
      $this->load->model('medicine_order_setting/medicine_order_setting_model'); 
    }

    public function index()
    {

        $data['page_title'] = "Medicine Order By Setting";
        $post = $this->input->post();
        $data['form_error'] = [];
        $data['form_data'] = array('module[]'=>array());
        $data['form_data'] = $this->medicine_order_setting_model->get_default_setting();
        if(isset($post) && !empty($post))
        {  
			$user_detail= $this->medicine_order_setting_model->save_setting();
			echo 1;die;
        }
       
        $this->load->view('medicine_order_setting/medicine_order_setting',$data);
    }

  }  
?>