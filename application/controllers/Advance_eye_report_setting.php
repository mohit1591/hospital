<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advance_eye_report_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('advance_eye_report_setting/advance_eye_report_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Eye Report Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Eye report print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        $this->load->view('advance_eye_report_setting/add',$data);
    } 
      
   
}
?>
