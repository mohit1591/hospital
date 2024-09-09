<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Amb_document_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('ambulance/document_reports_model','document_report');
    }


   public function reports(){
          unauthorise_permission('405','2458');
          $data['page_title'] = 'Ambulance Document Report';
          $data['vehicle_list'] = $this->document_report->vehicle_list();
          $data['document_list'] = $this->document_report->document_list();
          $this->load->view('ambulance/document_report/document_report',$data);
    }

    public function get_document_data($get="")
    {

      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $data['documents_list'] = $this->document_report->document_report_list($get);
      $data['get'] = $get;
      $this->load->view('ambulance/document_report/document_report_data',$data); 
    }    
}
?>