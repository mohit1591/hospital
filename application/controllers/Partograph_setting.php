<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Partograph_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('partograph_setting/partograph_setting_model','partographsetting');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(92,583);
        $data['page_title'] = 'Partograph Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->partographsetting->save();
            echo 'Your Partograph Settings successfully updated.';;
            return false;
        }

        
        $data['partograph_setting_list'] = $this->partographsetting->get_master_unique(0);
        $this->load->view('partograph/add',$data);
    }


    public function preview($id="")
    {
        $this->load->model('partograph/partograph_model','partograph');

        $template_format = $this->partograph->template_format(array('setting_name'=>'PARTOGRAPH_PRINT_SETTING','unique_id'=>5,'type'=>0));

        $template_format_left = $this->partograph->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0));
        $template_format_right = $this->partograph->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0));
        $template_format_top = $this->partograph->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0));
        $template_format_bottom = $this->partograph->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>4,'type'=>0));
        $data['format_left'] = $template_format_left->setting_value;
        $data['format_right'] = $template_format_right->setting_value;
        $data['format_top'] = $template_format_top->setting_value;
        $data['format_bottom'] = $template_format_top->setting_value;
        $data['template_data']=$template_format->setting_value;
        $this->load->view('partograph/preview',$data);
    } 
      
   
}
?>