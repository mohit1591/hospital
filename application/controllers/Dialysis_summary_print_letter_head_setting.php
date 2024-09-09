<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_summary_print_letter_head_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('dialysis_summary_print_letter_head_setting/dialysis_summary_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Dialysis Summary Print Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Dialysis Summary print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        $this->load->view('dialysis_summary_print_letter_head_setting/add',$data);
    } 
      
   
}
?>
