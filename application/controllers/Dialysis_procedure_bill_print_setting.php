<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_procedure_bill_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('dialysis_procedure_bill_print_setting/dialysis_procedure_bill_print_setting_model','ipd_discharge_bill_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        $data['page_title'] = 'Dialysis Procedure Bill Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->ipd_discharge_bill_print->save();
            echo 'Your Dialysis procedure Bill Print Settings successfully updated.';
            return false;
        }

        
        $data['ipd_discharge_bill_print_setting_list'] = $this->ipd_discharge_bill_print->get_master_unique();
       
        $this->load->view('dialysis_procedure_bill_print_setting/add',$data);
    }


   
}
?>