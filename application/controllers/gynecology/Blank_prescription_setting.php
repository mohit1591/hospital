<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Blank_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //auth_users();
        //unauthorise_permission('317','1898');
        $this->load->model('gynecology/prescription_setting/blank_prescription_setting_model','prescription_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $users_data = $this->session->userdata('auth_users');
          //unauthorise_permission('317','1898');
        $data['page_title'] = 'Gynecology Blank Prescription Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescription_setting->save();
            echo 'Your Gynecology Blank Prescription Settings successfully updated.';;
            return false;
        }

       
        $data['prescription_setting_list'] = $this->prescription_setting->get_master_unique(1);
        $this->load->view('gynecology/blank_prescription_setting/add',$data);
    }


   
      
   
}
?>