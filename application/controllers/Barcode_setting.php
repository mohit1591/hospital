<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('barcode_setting/barcode_setting_model','barcode');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
        unauthorise_permission(103,645);
        $data['page_title'] = 'Bar Code Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->barcode->save();
            echo 'Your Bar code settings successfully updated.';
            return false;
        }
        $sms_setting_list = $this->barcode->get_master_unique();
        $data['total_receipt'] = $sms_setting_list->total_receipt;
        $data['type'] = $sms_setting_list->type;
        $data['size'] = $sms_setting_list->size;
        $data['id'] = $sms_setting_list->id;
        $this->load->view('barcode_setting/add',$data);
    } 


 } 
 ?>