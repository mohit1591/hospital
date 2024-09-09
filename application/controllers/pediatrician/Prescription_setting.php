<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prescription_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('pediatrician/prescription_setting/prescription_setting_model','prescriptionsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(326,2027);
        $data['page_title'] = 'Prescription Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->prescriptionsetting->save();
            echo 'Your Prescription Settings successfully updated.';;
            return false;
        }

        
        $data['prescription_setting_list'] = $this->prescriptionsetting->get_master_unique(0);
        $this->load->view('pediatrician/prescription_setting/add',$data);
    }


    public function preview($id="")
    {
        
        $template_format = $this->prescriptionsetting->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0));

        $template_format_left = $this->prescriptionsetting->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0));
        $template_format_right = $this->prescriptionsetting->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0));
        $template_format_top = $this->prescriptionsetting->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0));
        $template_format_bottom = $this->prescriptionsetting->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>4,'type'=>0));
        $data['format_left'] = $template_format_left->setting_value;
        $data['format_right'] = $template_format_right->setting_value;
        $data['format_top'] = $template_format_top->setting_value;
        $data['format_bottom'] = $template_format_top->setting_value;
        $data['template_data']=$template_format->setting_value;
        $this->load->view('pediatrician/prescription_setting/preview',$data);
    } 
      
   
}
?>