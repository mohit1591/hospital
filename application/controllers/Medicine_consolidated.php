<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_consolidated extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('medicine_report/medicine_report_model','medicine_report');
		$this->load->library('form_validation');
	}

	public function index()
	{
		
	}

	 public function consolidated_report()
	 {
       //$data['medicine_gst_list'] = $this->medicine_gst->gst_list();
       $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
       $data['page_title'] = 'Medicine Consolidated Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       $this->load->view('medicine_report/consolidated_medicine_report',$data);
    }

    public function consolidated_list()
    {
		$users_data = $this->session->userdata('auth_users');
        $data = array();
        $no = $_POST['start'];
        //$total_num = count($list);
        $purchase_grand_total=0;
        $purchase_grand_return_total = 0;
        $sales_grand_total = 0;
        $sales_grand_return_total = 0;
        $vendor_grand_total=0;
        
         $search = $this->session->userdata('consolidated_search');
        $date1 = new DateTime(date('Y-m-d',strtotime($search['start_date'])));
        $date2 = new DateTime(date('Y-m-d',strtotime($search['end_date'])));
        $date2->modify("+1 days");
        $interval = $date1->diff($date2);
        $days= $interval->days;
        $total_num = $days;          
        $k=1;           
        $i=1;

        $start_date_time = new DateTime($search['start_date']);
        $end_date_time = new DateTime($search['end_date']);
 
           
           $p=0;
        while($k <= $days) 
        { 
            $date1_v='';
            if($start_date_time == $end_date_time)
            {
                $date1_v = $search['start_date'];
            }
            else
            {
                $date1_v= date('d-m-Y', strtotime($search['start_date']. $p.'  days'));
            }
                $purchase_total = get_purchase_amount($date1_v);
                $purchase_grand_total = $purchase_grand_total+$purchase_total;
                $purchase_return_total = get_purchase_return_amount($date1_v);
                $purchase_grand_return_total = $purchase_grand_return_total+$purchase_return_total;
                $sale_total = get_sales_amount($date1_v);
                $sales_grand_total = $sales_grand_total+$sale_total;
                $sale_return_total = get_sales_return_amount($date1_v);

                $sales_grand_return_total = $sales_grand_return_total+$sale_return_total;
                $vendor_total =get_vendor_payment_amount($date1_v);
                $vendor_grand_total = $vendor_grand_total+$vendor_total;
                $row = array();
                $row[] = $k;
                $row[] = $purchase_total;
                $row[] = $purchase_return_total;
                $row[] = $sale_total;
                $row[] = $sale_return_total;
                
                $row[] = $vendor_total;
                $row[] = $date1_v;
                //Action button /////
                // End Action Button //
                $data[] = $row;
                $tot_row = array();
               if($i==$days)
               {
                  $tot_row[] = 'Total';  
                  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($purchase_grand_total,2,'.','').' readonly >'; 
                  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($purchase_grand_return_total,2,'.','').' readonly >'; 
                  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($sales_grand_total,2,'.','').' readonly >';
                  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($sales_grand_return_total,2,'.','').' readonly >';
                  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($vendor_grand_total,2,'.','').' readonly >';
                  $tot_row[] = '';
              $data[] = $tot_row; 
              
              
            }
            $i++;
            $k++;
          $p++;
        }
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $days,
                        "recordsFiltered" => $days,

                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    
    }

    public function advance_search()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"",);
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('consolidated_search', $merge);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('consolidated_search');
    }

    public function consolidated_excel()
    {
    	  // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
		 $fields = array('S.No.','Purchase','Purchase Return','Sales','Sales Return','Pay to Vendor','Date');
		
		$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$col = 0;
        $row_heading =1;
	      foreach ($fields as $field)
	      {
	          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
	          
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }




        /////////////////////////////////////////////////start

        $rowData = array();
        $data= array();
        
        $purchase_grand_total=0;
        $purchase_grand_return_total = 0;
        $sales_grand_total = 0;
        $sales_grand_return_total = 0;
        $vendor_grand_total=0;
        
        $search = $this->session->userdata('consolidated_search');
        $date1 = new DateTime(date('Y-m-d',strtotime($search['start_date'])));
        $date2 = new DateTime(date('Y-m-d',strtotime($search['end_date'])));
        $date2->modify("+1 days");
        $interval = $date1->diff($date2);
        $days= $interval->days;
        $total_num = $days;          
        $k=1;           
        $i=1;

        $start_date_time = new DateTime($search['start_date']);
        $end_date_time = new DateTime($search['end_date']);
 
           
           $p=0;
        while($k <= $days) 
        { 
                $date1_v='';
                if($start_date_time == $end_date_time)
                {
                    $date1_v = $search['start_date'];
                }
                else
                {
                    $date1_v= date('d-m-Y', strtotime($search['start_date']. $p.'  days'));
                }
                $purchase_total = get_purchase_amount($date1_v);
                $purchase_grand_total = $purchase_grand_total+$purchase_total;
                $purchase_return_total = get_purchase_return_amount($date1_v);
                $purchase_grand_return_total = $purchase_grand_return_total+$purchase_return_total;
                $sale_total = get_sales_amount($date1_v);
                $sales_grand_total = $sales_grand_total+$sale_total;
                $sale_return_total = get_sales_return_amount($date1_v);

                $sales_grand_return_total = $sales_grand_return_total+$sale_return_total;
                $vendor_total =get_vendor_payment_amount($date1_v);
                $vendor_grand_total = $vendor_grand_total+$vendor_total;
                
                               
                array_push($rowData,$k,$purchase_total,$purchase_return_total,$sale_total,$sale_return_total,$vendor_total,$date1_v);
                
                $n++;
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                
            $i++;
            $k++;
          $p++;
        }

        ////////////////////////////////////////////////end
        
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



			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$row,'Total');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,number_format($purchase_grand_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,number_format($purchase_grand_return_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,number_format($sales_grand_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,number_format($sales_grand_return_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,number_format($vendor_grand_total,2,'.',''));
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':F'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=consolidated_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function consolidated_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->medicine_report->search_medicine_data();
        $this->load->view('medicine_report/consolidated_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("consolidated_list_".time().".pdf");
    }
    public function consolidated_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->search_medicine_data();
      $this->load->view('medicine_report/consolidated_html',$data); 
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