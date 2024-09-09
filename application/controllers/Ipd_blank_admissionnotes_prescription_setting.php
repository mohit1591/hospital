<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_blank_admissionnotes_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('ipd_admissionnotes_prescription_setting/blank_prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(121,733);
        $data['page_title'] = 'Admission notes Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Admission Note Settings successfully updated.';;
            return false;
        }

       
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(1);
        $this->load->view('ipd_blank_admissionnotes_prescription_setting/add',$data);
    }

}
?>