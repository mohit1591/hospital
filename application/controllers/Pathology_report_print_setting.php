<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_report_print_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('pathology_report_print_setting/pathology_report_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $get = $this->input->get();
        if(!empty($get['temp_id']))
        {
            $temp_id=$get['temp_id'];
        }
        $data['page_title'] = 'Test Report Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Test report print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique($temp_id);
        if(!empty($temp_id))
        {
            $data['temp_id'] = $temp_id;
        }
        else
        {
            $data['temp_id'] =0;
        }
        //print_r($data['report_print_setting']);die;
        $this->load->view('pathology_report_print_setting/add',$data);
    }
    
    
      
   
}
?>
