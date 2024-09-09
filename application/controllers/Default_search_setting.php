<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class default_search_setting extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
      $this->load->model('default_search_setting/default_search_setting_model'); 
    }

public function index()
    {

        $data['page_title'] = "Default Search Setting";
        $post = $this->input->post();
        $data['form_error'] = [];
        $data['form_data'] = array(
                                    'module[]'=>array()
                                  );
        $data['form_data'] = $this->default_search_setting_model->get_default_setting();
        if(isset($post) && !empty($post))
        {  
			$user_detail= $this->default_search_setting_model->save_setting();
			echo 1;die;
        }
       
        $this->load->view('default_search_setting/default_search_setting',$data);
    }

  }  
?>