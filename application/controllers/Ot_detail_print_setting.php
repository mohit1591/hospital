<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_detail_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('ot_detail_print_setting/ot_detail_print_setting_model','ot_detail_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(136,818);
        $data['page_title'] = 'OT Detail Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->ot_detail_print->save();
            echo 'Your OT Detail Print Settings successfully updated.';
            return false;
        }

        
        $data['ot_detail_print_setting_list'] = $this->ot_detail_print->get_master_unique();
       
        $this->load->view('ot_detail_print_setting/add',$data);
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