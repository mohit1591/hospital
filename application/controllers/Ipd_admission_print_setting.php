<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_admission_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ipd_admission_print_setting/ipd_admission_print_setting_model','ipd_admission_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(125,761);
        $data['page_title'] = 'IPD Admission Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->ipd_admission_print->save();
            echo 'Your IPD Admission Print Settings Settings successfully updated.';
            return false;
        }

        
        $data['ipd_admission_setting_list'] = $this->ipd_admission_print->get_master_unique(0);
        //print_r($data['ipd_admission_setting_list']);
        $this->load->view('ipd_admission_print_setting/add',$data);
    }


    public function preview($id="")
    {
        //$this->load->model('prescription/prescription_model','prescription');
		$template_format = $this->ipd_admission_print->template_format(array('setting_name'=>'IPD_PRINT_SETTING','unique_id'=>1,'type'=>0));
		//print_r($template_format);
		$data['template_data']=$template_format->setting_value;
        $this->load->view('ipd_admission_print_setting/preview',$data);
    } 
      
   
}
?>