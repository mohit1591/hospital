<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_medicine_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        unauthorise_permission('246','1400');
        $this->load->model('eye/prescription_medicine_tab_setting/prescription_medicine_tab_setting_model','prescriptiontabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(246,1400);
        $data['page_title'] = 'Eye Prescription Medicine Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->prescriptiontabsetting->save();
            echo 'Your Eye Prescription Medicine Settings successfully updated.';;
            return false;
        }
        $data['prescription_tab_setting_list'] = $this->prescriptiontabsetting->get_master_unique();
        $this->load->view('eye/prescription_medicine_tab_setting/add',$data);
    } 
      
   
}
?>