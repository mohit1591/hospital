<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('dental/prescription_setting/prescription_setting_model','prescription_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(294,1740);
        $data['page_title'] = 'Dental Prescription Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescription_setting->save();
            echo 'Your Dental Prescription Settings successfully updated.';;
            return false;
        }

        
        $data['prescription_setting_list'] = $this->prescription_setting->get_master_unique(0);
        $this->load->view('dental/prescription_setting/add',$data);
    }  
}
?>