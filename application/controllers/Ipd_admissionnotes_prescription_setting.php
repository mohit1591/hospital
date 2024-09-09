<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_admissionnotes_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ipd_admissionnotes_prescription_setting/prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(121,733);
        $data['page_title'] = 'Admission Note Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Admission Note Settings successfully updated.';;
            return false;
        }

        
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(0);
        $this->load->view('ipd_admissionnotes_prescription_setting/add',$data);
    }

   
}
?>