<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_discount_setting extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
      $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
    }

    public function index()
    {

        $data['page_title'] = "Discount Setting";
        $post = $this->input->post();
        $data['form_error'] = [];
        $data['form_data'] = array('module[]'=>array());
        $data['form_data'] = $this->inventory_discount_setting_model->get_default_setting();
        if(isset($post) && !empty($post))
        {  
			$user_detail= $this->inventory_discount_setting_model->save_setting();
			echo 1;die;
        }
       
        $this->load->view('inventory_discount_setting/inventory_discount_setting',$data);
    }

  }  
?>