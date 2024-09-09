<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class commission_print_setting extends CI_Controller {

    public function __construct()
    {
        
        parent::__construct();
        auth_users();
        
        $this->load->model('commission_print_setting/commision_print_setting_model','test_report');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Doctor commission Letterhead Print Setting'; 
        $post = $this->input->post();
       
        if(!empty($post))
        { 
            $this->test_report->save();
            echo 'Your Doctor commission print Settings successfully updated.';;
            return false;
        }
        
        $data['report_print_setting'] = $this->test_report->get_master_unique();
        $this->load->view('commssion_print_setting/add',$data);
    } 
      
   
}
?>
