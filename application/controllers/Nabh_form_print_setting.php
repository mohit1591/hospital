<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nabh_form_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('nabh_form_print_setting/nabh_form_print_setting_model','nabh_form_print_setting');
        $this->load->library('form_validation');
    }

    public function index()
    {
      //  unauthorise_permission('124','749');
        $data['page_title'] = 'NABH form print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            //echo "<pre>";print_r($post); exit;
            $this->nabh_form_print_setting->save();
            echo 'Your NABH form Settings successfully updated.';;
            return false;
        }
        $data['form_setting_list'] = $this->nabh_form_print_setting->get_master_unique(0);
        $this->load->view('nabh_form_print_setting/add',$data);
    }


    public function preview($id="")
    {
        $template_format = $this->nabh_form_print_setting->template_format(array('setting_name'=>'NABH_FORM_PRINT_SETTING'));
        $data['template_data']=$template_format->setting_value;
        $this->load->view('nabh_form_print_setting/preview',$data);
    }

    public function get_according_to_section()
    {
        $temp_type=$this->input->post('temp_name');

        $get_data= $this->nabh_form_print_setting->second_template_format($temp_type);
     
         if(count($get_data)>0)
         {
             $data_id=$get_data['id'];
             $setting_name=$get_data['setting_name'];
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
                                        "message"=>$template
                                    );
    
     $this->load->view('nabh_form_print_setting/print_template',$data);
     
  } 
}
?>