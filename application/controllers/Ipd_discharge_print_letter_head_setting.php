<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_print_letter_head_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('ipd_discharge_print_letter_head_setting/ipd_discharge_summary_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Discharge Report Print Setting'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Discharge report print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        $this->load->view('ipd_discharge_summary_letter_head_print_setting/add',$data);
    } 
      
   
}
?>
