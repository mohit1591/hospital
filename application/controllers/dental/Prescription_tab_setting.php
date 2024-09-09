<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('284','1681');
        $this->load->model('dental/prescription_tab_setting/prescription_tab_setting_model','prescriptiontabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission('284','1681');
        $data['page_title'] = 'Dental Prescription Tab Settings'; 
        $post = $this->input->post();
       // print_r($post);die;

        if(!empty($post))
        { 
            $this->prescriptiontabsetting->save();
            echo 'Your Dental Prescription Tab Settings successfully updated.';;
            return false;
        }
        $data['prescription_tab_setting_list'] = $this->prescriptiontabsetting->get_master_unique();
       // print_r($data);die;
        $this->load->view('dental/prescription_tab_setting/add',$data);
    } 
      
   
}
?>