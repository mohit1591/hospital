<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_report_print_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('test_report_print_setting/test_report_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Test Report Print Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Test report print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        //print_r($data['report_print_setting']);die;
        $this->load->view('test_report_print_setting/add',$data);
    } 
      
   
}
?>
