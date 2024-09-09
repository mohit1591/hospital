<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_token_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('billing_token_setting/billing_token_setting_model','token_setting');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
    //echo "hi";die;
        unauthorise_permission(103,645);
        $data['page_title'] = 'OPD Billing Token Setting'; 
        $post = $this->input->post();
        //print_r($_POST);die;

        if(!empty($post))
        { 
            $this->token_setting->save();
            echo 'Your Token settings successfully updated.';
            return false;
        }
        $data['token_setting']= $this->token_setting->get_master_unique();
        $this->load->view('billing_token_setting/add',$data);
    } 


 } 
 ?>