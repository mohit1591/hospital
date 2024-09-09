<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Path_token_setting extends CI_Controller {
 
  function __construct() 
  {
      parent::__construct();  
      auth_users();  
      $this->load->model('path_token_setting/token_setting_model','path_token_setting');
      $this->load->library('form_validation');
  }

    
   public function index()
   {
    //echo "hi";die;
       // unauthorise_permission(103,645);
        $data['page_title'] = 'Path Token Setting'; 
        $post = $this->input->post();
        //print_r($_POST);die;

        if(!empty($post))
        { 
            $this->path_token_setting->save();
            echo 'Your Token settings successfully updated.';
            return false;
        }
        $data['path_token_setting']= $this->path_token_setting->get_master_unique();
        $this->load->view('path_token_setting/add',$data);
    } 


 } 
 ?>