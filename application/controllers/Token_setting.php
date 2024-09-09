<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Token_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('token_setting/token_setting_model','token_setting');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
    //echo "hi";die;
        unauthorise_permission(103,645);
        $data['page_title'] = 'Token Setting'; 
        $post = $this->input->post();
        //print_r($_POST);die;

        if(!empty($post))
        { 
            $this->token_setting->save();
            echo 'Your Token settings successfully updated.';
            return false;
        }
        $this->load->model('combine_token_setting/combine_token_setting_model');
        $data['form_data'] = $this->combine_token_setting_model->get_default_setting();
        $data['token_setting']= $this->token_setting->get_master_unique();
        $this->load->view('token_setting/add',$data);
    } 


 } 
 ?>