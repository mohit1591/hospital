<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opd_validity_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();	
      auth_users();  
      $this->load->model('opd_validity_setting/opd_validity_setting_model','opd_validity_setting');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
   // echo "hi";die;
        unauthorise_permission(103,645);
        $data['page_title'] = 'Opd Validity Setting'; 
        $post = $this->input->post();
        //print_r($_POST);die;

        if(!empty($post))
        { 
            $this->opd_validity_setting->save();
            echo 'Your Opd Validity settings successfully updated.';
            return false;
        }
        $data['opd_validity_setting']= $this->opd_validity_setting->get_master_unique();
        //print_r($data['opd_validity_setting']);die;
        $this->load->view('opd_validity_setting/add',$data);
    } 


 } 
 ?>