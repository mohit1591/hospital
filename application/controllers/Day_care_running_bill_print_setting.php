<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Day_care_running_bill_print_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('day_care_running_bill_print_setting/day_care_running_bill_print_setting_model','day_care_running_bill_print');
        $this->load->library('form_validation');
    }

    public function index()
    {
        
        unauthorise_permission(125,765);
        $data['page_title'] = 'Day Care Running Bill Settings'; 
        $post = $this->input->post();
        if(!empty($post))
        { 
            $this->day_care_running_bill_print->save();
            echo 'Your Day Care Running Bill Print Settings successfully updated.';
            return false;
        }
        
        $data['day_care_running_bill_print_setting_list'] = $this->day_care_running_bill_print->get_master_unique();
     //print_r($data['day_care_running_bill_print_setting_list']);
        $this->load->view('day_care_running_bill_print_setting/add',$data);
    }

     
   
}
?>