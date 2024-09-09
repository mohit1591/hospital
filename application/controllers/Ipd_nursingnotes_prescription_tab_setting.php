<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_nursingnotes_prescription_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        
        $this->load->model('ipd_nursingnotes_prescription_tab_setting/prescription_tab_setting_model','prescriptiontabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
       unauthorise_permission(121,733);
        $data['page_title'] = 'Nursing Note Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->prescriptiontabsetting->save();
            echo 'Your Nursing note Tab Settings successfully updated.';;
            return false;
        }
        $data['prescription_tab_setting_list'] = $this->prescriptiontabsetting->get_master_unique();
        $this->load->view('ipd_nursingnotes_prescription_tab_setting/add',$data);
    } 
      
   
}
?>