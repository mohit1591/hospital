<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_opd_summary_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('doctor_opd_summary_report/doctor_opd_summary_report_model','reports');
        $this->load->library('form_validation');
    }

   /* public function index()
    {   
        $this->session->unset_userdata('search_data');
        $data['sub_branch'] = $this->session->userdata('sub_branches_data'); 
        $search_date = $this->session->userdata('search_data');
        $data['form_data'] = array('start_date'=>'', 'end_date'=>'');
        $data['page_title'] = 'Report list'; 
        $this->load->view('reports/list',$data);
    }*/
    public function reports()
    {
        $data['page_title'] = 'OPD Doctor Summary Reports';
        $this->load->view('doctor_opd_summary_report/list_report',$data);
    }
    
    public function print_reports()
    { 
     $get = $this->input->get();
     $data['expense_list'] = [];
     if(!empty($get['branch_id']))
     {
        $data['doctor_list'] = $this->reports->get_details($get);
     }
     $data['get'] = $get;
     $data['page_title'] ="OPD Doctor Summary Reports"; 
     $this->load->view('doctor_opd_summary_report/list_expenses_reports',$data);  

    }
    
    
}
?>