<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ipd_discharge_summary_print_setting/ipd_discharge_summary_print_setting_model','discharge_print_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        unauthorise_permission('124','749');
        $data['page_title'] = 'Discharge summary print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            //echo "<pre>";print_r($post); exit;
            $this->discharge_print_setting->save();
            echo 'Your Discharge summary Settings successfully updated.';;
            return false;
        }
        $data['discharge_setting_list'] = $this->discharge_print_setting->get_master_unique(0);
        $this->load->view('ipd_discharge_summary_print_setting/add',$data);
    }


    public function preview($id="")
    {
        $template_format = $this->discharge_print_setting->template_format(array('setting_name'=>'DISCHARGE_SUMMARY_PRINT_SETTING'));
        $data['template_data']=$template_format->setting_value;
        $this->load->view('ipd_discharge_summary_print_setting/preview',$data);
    }

    public function get_according_to_section()
    {
        $type=$this->input->post('value');
        $this->load->model('medicine_print_setting/medicine_print_setting_model','medicine_print_setting');
        $get_data= $this->discharge_print_setting->second_template_format($type);
     
         if(count($get_data)>0)
         {
             $data_id=$get_data['id'];
             $setting_name=$get_data['setting_name'];
             $type = $get_data['type'];
             $template=$get_data['setting_value'];
             
         }
         else
         {
             $data_id='';
             $setting_name='';
             $template='';
             $type='';
         }
      
            $data['form_data'] = array(
                                        "data_id"=>$data_id,
                                        "setting_name"=>$setting_name,
                                        "message"=>$template,
                                        "type"=>$type,
                                    );
    
     $this->load->view('ipd_discharge_summary_print_setting/print_template',$data);
     
  } 
      
   
}
?>