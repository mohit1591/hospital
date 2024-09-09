<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Daywise_collection extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    { 
        $data['page_title'] = 'Day Wise Collection';
        // $this->load->view('canteen/daywise_collection/list',$data);

         $get= $this->input->get();
         $data['from_c_date'] = date('d-m-Y');
         $data['to_c_date'] =   date('d-m-Y'); 
         $data['vendor_list'] = '';// $this->vendor->vendor_list(); 
         $this->load->view('canteen/daywise_collection/popup_item', $data);
    }
    public function daywise_report_data(){
        $data['page_title'] = 'Day Wise Report Data';
        $this->load->view('canteen/daywise_report/daywise_report_data', $data);
    }
      
    
    
}
?>