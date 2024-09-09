<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_blank_nursingnotes_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        //auth_users();
        //unauthorise_permission('6','29');
        $this->load->model('ipd_nursingnotes_prescription_setting/blank_prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(121,733);
        $data['page_title'] = 'Nursing notes Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Nursing notes Settings successfully updated.';;
            return false;
        }

       
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(1);
        $this->load->view('ipd_nursingnotes_prescription_setting/add',$data);
    }

}
?>