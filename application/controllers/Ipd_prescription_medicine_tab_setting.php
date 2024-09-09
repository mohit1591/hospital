<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_prescription_medicine_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('ipd_prescription_medicine_tab_setting/prescription_medicine_tab_setting_model','prescriptiontabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(121,733);
        $data['page_title'] = 'IPD Prescription Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->prescriptiontabsetting->save();
            echo 'Your Prescription IPD Settings successfully updated.';;
            return false;
        }
        $data['prescription_tab_setting_list'] = $this->prescriptiontabsetting->get_master_unique();
        $this->load->view('ipd_prescription_medicine_tab_setting/add',$data);
    } 
      
   
}
?>