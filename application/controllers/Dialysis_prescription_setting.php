<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('dialysis_prescription_setting/dialysis_prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        //unauthorise_permission(92,583);
        $data['page_title'] = 'Dialysis Prescription Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Dialysis Prescription Settings successfully updated.';;
            return false;
        }

        
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(0);
        $this->load->view('dialysis_prescription_setting/add',$data);
    }


 
      
   
}
?>