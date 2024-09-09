<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daycare_summary_medicine_tab_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        
        $this->load->model('daycare_summary_medicine_tab_setting/daycare_summary_tab_setting_model','prescriptiontabsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission(415,2511);
        $data['page_title'] = 'Day Care Summary Medicine Tab Settings'; 
        $post = $this->input->post();

        if(!empty($post))
        { 
            $this->prescriptiontabsetting->save();
            echo 'Your Day Care Summary Settings successfully updated.';;
            return false;
        }
        $data['prescription_tab_setting_list'] = $this->prescriptiontabsetting->get_master_unique();
        $this->load->view('daycare_summary_medicine_tab_setting/add',$data);
    } 
      
   
}
?>