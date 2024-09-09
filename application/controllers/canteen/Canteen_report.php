<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Canteen_report extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->model('canteen/canteen_report/canteen_report_model','canteen_report');
        $this->load->library('form_validation');
    }

    public function itemwise_report()
    { 
        $data['page_title'] = 'Item Wise Report';
        // $this->load->view('canteen/itemwise_report/list', $data);
         $get= $this->input->get();
         $data['from_c_date'] = date('d-m-Y');
         $data['to_c_date'] =   date('d-m-Y'); 
         $data['vendor_list'] = '';// $this->vendor->vendor_list(); 
         $this->load->view('canteen/itemwise_report/popup_item',$data);
    }

    public function itemwise_report_data(){
        $data['page_title'] = 'Item Wise Report';
        $this->load->view('canteen/itemwise_report/itemwise_report_data',$data);
    }


    public function purchase_report()
    { 
        $data['page_title'] = 'Purchase Report';
        $this->load->view('canteen/purchase_report/list',$data);
    }
    public function purchase_return_report()
    { 
        $data['page_title'] = 'Purchase Return Report';
        $this->load->view('canteen/purchase_return_report/list',$data);
    }
    public function sale_report()
    { 
        $data['page_title'] = 'Sale Report';
        $this->load->view('canteen/sale_report/list',$data);
    }
    public function profit_loss()
    { 
        $data['page_title'] = 'Profit & Loss';
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

	
 // Start purchase gstr1_report
 
    public function gstr1_report()
    { 
        $data['page_title'] = 'GSTR1 Purchase Report';
        $this->load->view('canteen/canteen_report/gstr1_report',$data);
    }
	
 public function gstr1_list()
    {
		$user_data = $this->session->userdata('auth_users');
        $list = $this->canteen_report->get_datatables_gstr1();
    //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $total_num = count($list);
        $gst_total_amount=0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0;
        $tot_discount_amount=0;
        $taxable_amount_total = 0;
        $amount_total = 0;

        $i=1;
        $k=1;
        foreach ($list as $data_list) 
        {
        	$total_purchase_count = $this->canteen_report->get_purchase_count($data_list->p_id);
			$no++;
            $row = array();
			$row[] = $k;
			$row[] = $data_list->vendor_name;
			$row[] = $data_list->vendor_gst;
			$row[] = date('d-m-Y', strtotime($data_list->purchase_date));
			$row[] = $data_list->invoice_id;
			$row[] = $data_list->net_amount;
			$row[] = 'Local';
            $row[] = $data_list->hsn_no;
            $row[] = $data_list->total_amount;
            $row[] = $data_list->total_amount;

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
			$tot_qty_with_rate=$data_list->purchase_rate*$data_list->qty;
            $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			$total_row_amount=$tot_qty_with_rate-$data_list->discount;
            
			$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt = ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt = ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
           
		    $row[] = number_format($data_list->cgst,2,'.',''); 
            $row[] = number_format($total_cgst_amt,2,'.',''); 
			
			$row[] = number_format($data_list->sgst,2,'.','');
            $row[] = number_format($total_sgst_amt,2,'.',''); 

            $row[] = number_format($data_list->igst,2,'.',''); 
            $row[] = number_format($total_igst_amt,2,'.',''); 
 
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
  
            $row[] = number_format($gst_total,2,'.',''); 
            $gst_total_amount = $gst_total_amount+$gst_total;
           	
            //Action button /////
            // End Action Button //
         	$data[] = $row;        	
            $k++;

         	$tot_row = array();
        if($i==$total_num)
          {
              
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-100px" style="border:0px">'; 
		  $tot_row[] = ''; 
		  $tot_row[] = ''; 
		  $tot_row[] = ''; 
		  $tot_row[] = '';  
		  $tot_row[] = '';
		  $tot_row[] = '';  
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($amount_total,2,'.','').' readonly >'; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($taxable_amount_total,2,'.','').' readonly >'; 
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_sgst,2,'.','').' readonly >';
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_cgst,2,'.','').' readonly >';
		  $tot_row[] = '';
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_igst,2,'.','').' readonly >';
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($gst_total_amount,2,'.','').' readonly >'; 
              
              $data[] = $tot_row; 
          }
         $i++;

        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->canteen_report->count_all_gstr1(),
                        "recordsFiltered" => $this->canteen_report->count_filtered_gstr1(),
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
            $this->session->set_userdata('gstr1_search', $merge);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('gstr1_search');
    }

    public function gstr1_excel()
    {
    	  // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
		 $fields = array('S.No.','Name of Party','GSTIN','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Amount','Taxable Amount','SGST %age','SGST Amount','CGST %age','CGST Amount','IGST %age','IGST Amount','Total GST');
		
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
	          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	  
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }
        
        $list = $this->canteen_report->search_data_gstr1();
    	$rowData = array();
        $data= array();
        if(!empty($list))
        {
            $gst_total_amount=0;
	        $total_cgst = 0;
	        $total_igst = 0;
	        $total_sgst = 0;
	        $tot_discount=0;
	        $tot_discount_amount=0;
	        $taxable_amount_total = 0;
	        $amount_total = 0;

	        $n=1;
	        $k=1;
	        $i=1;
            foreach($list as $data_list)
            {

            	$total_purchase_count = $this->canteen_report->get_purchase_count($data_list->p_id);
				$taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
	            $amount_total=$amount_total+$data_list->total_amount;
				
	            $tot_qty_with_rate=$data_list->purchase_rate*$data_list->qty;
                $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			    $total_row_amount=$tot_qty_with_rate-$data_list->discount;
	            
				$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
	            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
	            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
	            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
	            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
	            $total_igst += ($total_row_amount/100)*$data_list->igst; 
	            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
	            $gst_total_amount = $gst_total_amount+$gst_total;

                $row = array();
            	array_push($rowData,$k,$data_list->vendor_name,$data_list->vendor_gst,date('d-m-Y', strtotime($data_list->purchase_date)),$data_list->invoice_id,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,$gst_total);
         	    $k++;
                $n++;

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

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,'Total');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,number_format($amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,number_format($taxable_amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row,number_format($total_sgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$row,number_format($total_cgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$row,number_format($total_igst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row,number_format($gst_total_amount,2,'.',''));

			$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':R'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr1_canteen_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function gstr1_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->canteen_report->search_data_gstr1();
        $this->load->view('canteen/canteen_report/gstr1_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("gstr1_canteen_list_".time().".pdf");
    }
    public function gstr1_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->canteen_report->search_data_gstr1();
      $this->load->view('canteen/canteen_report/gstr1_html',$data); 
    }

    public function gstr1_csv()
    {
    	  // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
		 $fields = array('S.No.','Name of Party','GSTIN','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Amount','Taxable Amount','SGST %age','SGST Amount','CGST %age','CGST Amount','IGST %age','IGST Amount','Total GST');
		
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
	          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }
        
      $list = $this->canteen_report->search_data_gstr1();
    	$rowData = array();
        $data= array();
        if(!empty($list))
        {
          $gst_total_amount=0;
	        $total_cgst = 0;
	        $total_igst = 0;
	        $total_sgst = 0;
	        $tot_discount=0;
	        $tot_discount_amount=0;
	        $taxable_amount_total = 0;
	        $amount_total = 0;

	        $n=1;
	        $k=1;
	        $i=1;
            foreach($list as $data_list)
            {

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
			$tot_qty_with_rate=$data_list->purchase_rate*$data_list->qty;
            $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			$total_row_amount=$tot_qty_with_rate-$data_list->discount;
            
			$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt = ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt = ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
			
			$gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
	        $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
            if($n==1)
            {
            	array_push($rowData,$k,$data_list->vendor_name,$data_list->vendor_gst,date('d-m-Y', strtotime($data_list->purchase_date)),$data_list->invoice_id,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,$gst_total);
            	$k++;
            }
            else
            {
            	array_push($rowData,'','','','','','','',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,$gst_total);
				
				if($total_purchase_count<=$n)
				{
					$n=0; 
				}

            }
            $n++;

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

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,'Total');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,number_format($amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,number_format($taxable_amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$row,number_format($total_sgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$row,number_format($total_cgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$row,number_format($total_igst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row,number_format($gst_total_amount,2,'.',''));

			$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':R'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr1_canteen_report_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }	
	
	
// End purchase gstr1_repor

// Start sale gstr2_report	

public function gstr2_report()
	 {
    
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
       $data['page_title'] = 'GSTR2 Sale Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
	   $this->load->view('canteen/canteen_report/gstr2_report',$data);
    }

  public function gstr2_lists()
    {
		$user_data = $this->session->userdata('auth_users');
        $list = $this->canteen_report->get_datatables_gstr2();
  //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $total_num = count($list);
        $gst_total_amount=0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0;
        $tot_discount_amount=0;
        $taxable_amount_total = 0;
        $amount_total = 0;

        $i=1;
        $k=1;
        foreach ($list as $data_list) 
        {
        	$total_sale_count = $this->canteen_report->get_sale_count($data_list->s_id);
			$no++;
            $row = array();
			$row[] = $k;
			if(!empty($data_list->customer_name)){ $row[] = $data_list->customer_name; } else { $row[] = $data_list->patient_name;}
			$row[] = date('d-m-Y', strtotime($data_list->sale_date));
			$row[] = $data_list->sale_no;
			$row[] = $data_list->net_amount;
			$row[] = 'Local';
            $row[] = $data_list->hsn_no;
            $row[] = $data_list->total_amount;
            $row[] = $data_list->total_amount;

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
			$tot_qty_with_rate=$data_list->mrp*$data_list->qty;
            $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			$total_row_amount=$tot_qty_with_rate-$data_list->discount;
            
			$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt = ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt = ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
           
		    $row[] = number_format($data_list->cgst,2,'.',''); 
            $row[] = number_format($total_cgst_amt,2,'.',''); 
			
			$row[] = number_format($data_list->sgst,2,'.','');
            $row[] = number_format($total_sgst_amt,2,'.',''); 

            $row[] = number_format($data_list->igst,2,'.',''); 
            $row[] = number_format($total_igst_amt,2,'.',''); 
 
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
  
            $row[] = number_format($gst_total,2,'.',''); 
            $gst_total_amount = $gst_total_amount+$gst_total;
           	
            //Action button /////
            // End Action Button //
         	$data[] = $row;        	
            $k++;

         	$tot_row = array();
        if($i==$total_num)
          {
              
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-100px" style="border:0px">'; 
		  $tot_row[] = ''; 
		  $tot_row[] = ''; 
		  $tot_row[] = ''; 
		  $tot_row[] = '';  
		  $tot_row[] = '';
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($amount_total,2,'.','').' readonly >'; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($taxable_amount_total,2,'.','').' readonly >'; 
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_sgst,2,'.','').' readonly >';
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_cgst,2,'.','').' readonly >';
		  $tot_row[] = '';
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_igst,2,'.','').' readonly >';
		  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($gst_total_amount,2,'.','').' readonly >'; 
              
              $data[] = $tot_row; 
          }
         $i++;

        }

        $output = array(
                        "draw" => $_POST['draw'],
                       "recordsTotal" => $this->canteen_report->count_all_gstr2(),
                        "recordsFiltered" => $this->canteen_report->count_filtered_gstr2(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    
    }

  public function advance_search_gstr2()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"",);
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('gstr2_search', $merge);
        }
    }

  public function reset_search_gstr2()
    {
        $this->session->unset_userdata('gstr2_search');
    }

  public function gstr2_excel()
    {
    	  // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
		 $fields = array('S.No.','Name of Party','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Amount','Taxable Amount','SGST %age','SGST Amount','CGST %age','CGST Amount','IGST %age','IGST Amount','Total GST');
		
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
	          $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(25);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(25);

	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }
        
        $list = $this->canteen_report->search_data_gstr2();
    	$rowData = array();
        $data= array();
        if(!empty($list))
        {
            $gst_total_amount=0;
	        $total_cgst = 0;
	        $total_igst = 0;
	        $total_sgst = 0;
	        $tot_discount=0;
	        $tot_discount_amount=0;
	        $taxable_amount_total = 0;
	        $amount_total = 0;

	        $n=1;
	        $k=1;
	        $i=1;
            foreach($list as $data_list)
            {

            $total_sale_count = $this->canteen_report->get_sale_count($data_list->s_id);

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
			$tot_qty_with_rate=$data_list->mrp*$data_list->qty;
            $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			$total_row_amount=$tot_qty_with_rate-$data_list->discount;
            
			$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt = ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt = ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst;
			
			$gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
	        $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
                if(!empty($data_list->customer_name)){ $customer_name = $data_list->customer_name; } else { $customer_name = $data_list->patient_name;} 
				
            	array_push($rowData,$k,$customer_name,date('d-m-Y', strtotime($data_list->sale_date)),$data_list->sale_no,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,$gst_total);
            	$k++;
                $n++;

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

			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,'Total');
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,number_format($amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,number_format($taxable_amount_total,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row,number_format($total_sgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row,number_format($total_cgst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$row,number_format($total_igst,2,'.',''));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$row,number_format($gst_total_amount,2,'.',''));

			$objPHPExcel->getActiveSheet()->getStyle('G'.$row.':P'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr2_sale_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

 public function gstr2_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->canteen_report->search_data_gstr2();
        $this->load->view('canteen/canteen_report/gstr2_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("gstr2_list_".time().".pdf");
    }
 public function gstr2_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->canteen_report->search_data_gstr2();
      $this->load->view('canteen/canteen_report/gstr2_html',$data); 
    }
	
   // End sale gstr2_report
    
}
?>