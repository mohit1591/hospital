<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ipd_discharge_bill_print_letterhead_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('ipd_discharge_bill_print_letterhead_setting/ipd_discharge_bill_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Discharge Bill Letterhead Print Setting'; 
        $post = $this->input->post();
       
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Discharge Bill print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        $this->load->view('ipd_discharge_bill_print_letterhead_setting/add',$data);
    } 
      
   
}
?>
