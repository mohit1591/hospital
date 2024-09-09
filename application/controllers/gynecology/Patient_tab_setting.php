<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('311','1859');
        $this->load->model('gynecology/patient_tab_setting/patient_tab_setting_model','patienttabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
         unauthorise_permission('311','1859');
        $data['page_title'] = 'Patient Tab Settings'; 
        $post = $this->input->post();
        // print_r($post);die;

        if(!empty($post))
        { 
            $this->patienttabsetting->save();
            echo 'Your Gynecology Patient Tab Settings successfully updated.';
            return false;
        }
        $data['patient_tab_setting_list'] = $this->patienttabsetting->get_master_unique();
       // print_r($data);die;
        $this->load->view('gynecology/patient_tab_setting/add',$data);
    } 
      
   
}
?>