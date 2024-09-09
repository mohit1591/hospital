<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Whatsapp_sms_config extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('whatsapp_sms_config/whatsapp_sms_config_model','sms_configs');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
        unauthorise_permission(103,645);
        $data['page_title'] = 'Whatsapp SMS  Config Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->sms_configs->save();
            echo 'Your Whatsapp SMS config settings successfully updated.';
            return false;
        }
        $sms_setting_list= $this->sms_configs->get_master_unique();
        $data['url']  = $sms_setting_list->url;
        $data['id']  = $sms_setting_list->id;
        $data['variables'] = '{mobile_no},{message}';
        $this->load->view('whatsapp_sms_config/add',$data);
    } 


 } 
 ?>