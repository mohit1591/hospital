<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_bill_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ipd_discharge_bill_print_setting/ipd_discharge_bill_print_setting_model','ipd_discharge_bill_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(125,766);
        $data['page_title'] = 'IPD Discharge Bill Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->ipd_discharge_bill_print->save();
            echo 'Your IPD Discharge Bill Print Settings successfully updated.';
            return false;
        }

        
        $data['ipd_discharge_bill_print_setting_list'] = $this->ipd_discharge_bill_print->get_master_unique();
       
        $this->load->view('ipd_discharge_bill_print_setting/add',$data);
    }


    /*public function preview($id="")
    {
        //$this->load->model('prescription/prescription_model','prescription');
		$template_format = $this->ipd_running_bill_print->template_format(array('setting_name'=>'IPD_RUNNING_BILL_SETTING','unique_id'=>1,'type'=>0));
		//print_r($template_format);
		$data['template_data']=$template_format->setting_value;
        $this->load->view('ipd_admission_print_setting/preview',$data);
    } */
      
   
}
?>