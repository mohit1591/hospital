<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('crm/leads/leads_model','leads');
        $this->load->library('form_validation');
    }
 

    public function index()
    {
        $data['page_title'] = 'Report Search'; 
        $data['lead_type_list'] = $this->leads->lead_type_list();
        $data['call_status_list'] = $this->leads->call_status();
        $data['users_list'] = $this->leads->users_list();
        $data['lead_source_list'] = $this->leads->lead_source_list(); 
        //$data['department_list'] = $this->leads->department_list(); 
        $this->load->model('general/general_model');
       $data['department_list'] = $this->general_model->department_list();
        $data['dept_list'] = $this->leads->lab_department_list(); 
        $data['profile_list'] = $this->leads->profile_list();
        $data['operation_list']= $this->leads->operation_list();
        $data['form_data'] = $this->session->userdata('advance_search');
        $this->load->view('crm/leads/report',$data);
    }

    public function set_report()
    {
       $post = $this->input->post(); 
       if(!empty($post))
       { 
         $this->session->set_userdata('report_search',$post);
       }
    }


    public function print_report()
    { 
       $data['data_list'] = $this->leads->report_data();
       $this->load->view('crm/leads/print_report',$data);
    }
 
     
 
}
?>