<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Design extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	 
    }

    
	public function test_footer()
	{
		 $data['page_title'] = "Test Footer";
         $this->load->view('design/test_footer',$data);
    } 

    
	public function profile_master()
	{
		 $data['page_title'] = "Profile Master";
         $this->load->view('design/profile_master',$data);
    } 

    
	public function test_master()
	{
		 $data['page_title'] = "Test Master";
         $this->load->view('design/test_master',$data);
    } 

    
	public function test_booking()
	{
		 $data['page_title'] = "Test Booking";
         $this->load->view('design/test_booking',$data);
    }  

    
	public function prescription()
	{
		 $data['page_title'] = "Prescription";
		 $data['prescription_select_patient'] = "Select Patient";
         $this->load->view('design/prescription',$data);
    }   

    
	public function opd_booking()
	{
		 $data['page_title'] = "OPD Booking";
		 $data['modal_title'] = "OPD Booking";
		 $data['modal_title_more'] = "OPD Booking More";
         $this->load->view('design/opd_booking',$data);
    }

    public function ipd_booking()
    {
    	$data['page_title'] = "IPD Booking";
    	$data['page_title_more'] = "IPD Booking More";
    	$this->load->view('design/ipd_booking', $data);
    }

    public function master_discharge_summary()
    {
    	$data['page_title'] = "Master Discharge Summary";
    	$this->load->view('design/master_discharge_summary', $data);
    }

    public function purchase_medicine()
    {
    	$data['page_title'] = "Purchase Medicine";
    	$this->load->view('design/purchase_medicine', $data);
    }

    public function purchase_medicine_return()
    {
    	$data['page_title'] = "Purchase Medicine Return";
    	$this->load->view('design/purchase_medicine_return', $data);
    }

    public function sale_medicine()
    {
        $data['page_title'] = "Sale Medicine";
        $this->load->view('design/sale_medicine', $data);
    }

    public function sale_medicine_return()
    {
        $data['page_title'] = "Sale Medicine Return";
        $this->load->view('design/sale_medicine_return', $data);
    }

    public function medicine_stock()
    {
        $data['page_title'] = "Medicine Stock";
        $this->load->view('design/medicine_stock', $data);
    }

    public function medicine_entry()
    {
        $data['page_title'] = "Medicine Entry";
        $this->load->view('design/medicine_entry', $data);
    }

    public function page_new()
    {
        $data['page_title'] = "New Page";
        $this->load->view('design/page_new', $data);
    }

    public function ot_booking()
    {
        $data['page_title'] = "OT Booking";
        $this->load->view('design/ot_booking', $data);
    }

    public function purchase_stock_item()
    {
        $data['page_title'] = "Purchase Stock Item";
        $this->load->view('design/purchase_stock_item', $data);
    }

    public function stock_item_issue()
    {
        $data['page_title'] = "Stock Item Issue";
        $this->load->view('design/stock_item_issue', $data);
    }
}
