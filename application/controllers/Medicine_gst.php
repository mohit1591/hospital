<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_gst extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('medicine_gst/medicine_gst_model','medicine_gst');
		$this->load->library('form_validation');
	}

	public function index()
	{
		
	}

	 public function gst(){
       //$data['medicine_gst_list'] = $this->medicine_gst->gst_list();
       $data['page_title'] = 'GST Reports';
       $this->load->view('medicine_gst/medicine_gst_report',$data);
    }

    public function print_medicine_gst_reports()
    { 
      $get = $this->input->get();
      $users_data = $this->session->userdata('auth_users');
     
      $branch_list= $this->session->userdata('sub_branches_data');
      $parent_id= $users_data['parent_id'];
      $branch_ids = array_column($branch_list, 'id'); 

      $data['branch_gst_list'] = [];
      $data['branch_gst_list'] = $this->medicine_gst->branch_gst_list($get);

 	  $data['get'] = $get;
 	 if($get['section_type']==3 || $get['section_type']==4){
      $this->load->view('medicine_gst/list_sale_gst_report_data',$data);  

    }

     if($get['section_type']==1 || $get['section_type']==2){
      $this->load->view('medicine_gst/list_gst_report_data',$data);  

    }
  if($get['section_type']=='all')
    
     $this->load->view('medicine_gst/list_all_gst_report_data',$data);  
  }

       public function gst_report_excel()
    {
    	$get= $this->input->get();
		
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
		if($get['section_type']==1 || $get['section_type']==2){
		 $fields = array('Purchase No.','Invoice No.','Vendor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
		}
		if($get['section_type']==3 || $get['section_type']==4){
		 $fields = array('Sale No.','Patient Name.','Doctor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
		}
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
		$users_data = $this->session->userdata('auth_users');

		$branch_list= $this->session->userdata('sub_branches_data');
		$parent_id= $users_data['parent_id'];
		$branch_ids = array_column($branch_list, 'id'); 
		// print_r($branch_ids );die;
		$data['branch_gst_list'] = [];
		 $list = $this->medicine_gst->branch_gst_list($get);
    
      
      
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $reports)
            {

            	if($get['section_type']==1 || $get['section_type']==2){
            		 array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
            	}
              
               if($get['section_type']==3 || $get['section_type']==4){
               	array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
               }


                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }
        // Fetching the table data
        $row = 2;
        if(!empty($data))
        {
            foreach($data as $reports_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gst_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }


    public function gst_report_csv()
    {
    	 $get= $this->input->get();
         // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
        if($get['section_type']==1 || $get['section_type']==2){
		 $fields = array('Purchase No.','Invoice No.','Vendor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
		}
		if($get['section_type']==3 || $get['section_type']==4){
		  $fields = array('Sale No.','Patient Name.','Doctor Name','Total Amount','Discount %','Cgst','Sgst','Igst','Net Amount');
		}
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
		$users_data = $this->session->userdata('auth_users');
		
		$branch_list= $this->session->userdata('sub_branches_data');
		$parent_id= $users_data['parent_id'];
		$branch_ids = array_column($branch_list, 'id'); 
		// print_r($branch_ids );die;
		$data['branch_gst_list'] = [];
		  $list = $this->medicine_gst->branch_gst_list($get);
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            
            $i=0;
            foreach($list as $reports)
            {
              
               if($get['section_type']==1 || $get['section_type']==2){
            		 array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);

            	}
              
               if($get['section_type']==3 || $get['section_type']==4){
               	array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_name,$reports->total_amount,$reports->discount_percent,$reports->cgst,$reports->sgst,$reports->igst,$reports->net_amount);
               }
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }
        // Fetching the table data
        $row = 2;
        if(!empty($data))
        {
            foreach($data as $reports_data)
            {
                $col = 0;
                foreach ($fields as $field)
                { 
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                    $col++;
                }
                $row++;
            }
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=opd_collection_report_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
      
    }
}
?>