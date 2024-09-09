<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_admission_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('dialysis_admission_print_setting/dialysis_admission_print_setting_model','dialysis_admission_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        //unauthorise_permission(125,761);
        $data['page_title'] = 'Dialysis Admission Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->dialysis_admission_print->save();
            echo 'Your Dialysis Admission Print Settings Settings successfully updated.';
            return false;
        }

        
        $data['dialysis_admission_setting_list'] = $this->dialysis_admission_print->get_master_unique(0);
        //print_r($data['ipd_admission_setting_list']);
        $this->load->view('dialysis_admission_print_setting/add',$data);
    }

}
?>