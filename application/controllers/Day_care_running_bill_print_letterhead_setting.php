<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Day_care_running_bill_print_letterhead_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('day_care_running_bill_print_letterhead_setting/day_care_running_bill_print_letterhead_setting_model','day_care_running_bill_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(125,766);
        $data['page_title'] = 'Day Care Running Bill Letterhead Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->day_care_running_bill_print->save();
            echo 'Your Day Care Running Bill Letterhead Settings successfully updated.';
            return false;
        }

        
        $data['report_print_setting'] = $this->day_care_running_bill_print->get_master_unique();
       
        $this->load->view('day_care_running_bill_print_letterhead_setting/add',$data);
    }

   
}
?>