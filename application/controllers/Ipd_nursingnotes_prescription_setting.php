<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_nursingnotes_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ipd_nursingnotes_prescription_setting/prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(121,733);
        $data['page_title'] = 'Nursing Note Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Prescription Settings successfully updated.';;
            return false;
        }

        
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(0);
        $this->load->view('ipd_nursingnotes_prescription_setting/add',$data);
    }

 
   
}
?>