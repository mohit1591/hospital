<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Canteen_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function itemwise_report()
    { 
        $data['page_title'] = 'Item Wise Report List';
        // $this->load->view('canteen/itemwise_report/list', $data);
         $get= $this->input->get();
         $data['from_c_date'] = date('d-m-Y');
         $data['to_c_date'] =   date('d-m-Y'); 
         $data['vendor_list'] = '';// $this->vendor->vendor_list(); 
         $this->load->view('canteen/itemwise_report/popup_item',$data);
    }

    public function itemwise_report_data(){
        $data['page_title'] = 'Item Wise Report List';
        $this->load->view('canteen/itemwise_report/itemwise_report_data',$data);
    }


    public function purchase_report()
    { 
        $data['page_title'] = 'Purchase Report List';
        $this->load->view('canteen/purchase_report/list',$data);
    }
    public function purchase_return_report()
    { 
        $data['page_title'] = 'Purchase Return Report List';
        $this->load->view('canteen/purchase_return_report/list',$data);
    }
    public function sale_report()
    { 
        $data['page_title'] = 'Sale Report List';
        $this->load->view('canteen/sale_report/list',$data);
    }
    public function profit_loss()
    { 
        $data['page_title'] = 'Profit & Loss List';
        $get= $this->input->get();
        $data['from_c_date'] = date('d-m-Y');
        $data['to_c_date'] =   date('d-m-Y'); 
        $data['vendor_list'] = '';// $this->vendor->vendor_list(); 
        $this->load->view('canteen/profit_loss/popup_item',$data);
        // $this->load->view('canteen/profit_loss/list',$data);
    }
    public function profit_loss_data(){
        $data['page_title'] = 'Profile & Loss';
        $this->load->view('canteen/profit_loss/popup_item',$data);
    }






    public function gstr1_report()
    { 
        $data['page_title'] = 'GSTR1 Report List';
        $this->load->view('canteen/gstr1_report/list',$data);
    }
    public function gstr1_with_qty_report()
    { 
        $data['page_title'] = 'GSTR1 with Quantity Report List';
        $this->load->view('canteen/gstr1_with_qty_report/list',$data);
    }
    public function gstr2_report()
    { 
        $data['page_title'] = 'GSTR1 Report List';
        $this->load->view('canteen/gstr2_report/list',$data);
    }
    public function gstr2_with_qty_report()
    { 
        $data['page_title'] = 'GSTR2 with Quantity Report List';
        $this->load->view('canteen/gstr2_with_qty_report/list',$data);
    }
      
    
    
}
?>