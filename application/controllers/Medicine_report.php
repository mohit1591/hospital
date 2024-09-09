<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_report extends CI_Controller {

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

	 public function purchase_gst_report()
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
       $data['page_title'] = 'GSTR2 Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       //$data['all_detail'] = $this->medicine_report->get_all_medicine_report();
       $this->load->view('medicine_report/purchase_medicine_report',$data);
    }

    public function gstr1_list()
    {
		$users_data = $this->session->userdata('auth_users');
        $list = $this->medicine_report->get_datatables();
        //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
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
        foreach ($list as $data_list) 
        {
        	$total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
			$no++;
            $row = array();
            if($n==1)
            {
            	$row[] = $k;
            	$row[] = $data_list->vendor_name;
	            $row[] = $data_list->vendor_gst;
	            $row[] = date('d-m-Y', strtotime($data_list->purchase_date));
	            $row[] = $data_list->invoice_id;
	            $row[] = $data_list->net_amount;
	            $row[] = 'Local';
            	$k++;

            }
            else
            {
            	$row[] = '';
            	$row[] = '';
	            $row[] = '';
	            $row[] = '';
	            $row[] = '';
	            $row[] = '';
	            $row[] = '';	
				
				if($total_medicine_count<=$n)
				{
					$n=0; 
				}
				          	

            }
            $n++; 
            $row[] = $data_list->hsn_no;
            $row[] = $data_list->purchase_rate;
            $row[] = $data_list->purchase_rate;

            $taxable_amount_total=$taxable_amount_total+$data_list->purchase_rate;
            $amount_total=$amount_total+$data_list->purchase_rate;
            
            $signal_unit1_price = $data_list->purchase_rate*$data_list->unit1;
            if($data_list->conversion>0)
            {
            	$signal_unit2_price = ($data_list->purchase_rate/$data_list->conversion)*$data_list->unit2;
            }
            else
            {
            	$signal_unit2_price = ($data_list->purchase_rate);
            }
            
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
            
			$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
            
            /*
            $tot_discount_amount+= (($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
			*/
			$row[] = number_format($data_list->sgst, 2, '.', ',');
            $row[] = number_format($total_sgst_amt, 2, '.', ',');
            $row[] = number_format($data_list->cgst, 2, '.', ',');
            $row[] = number_format($total_cgst_amt, 2, '.', ',');
            $row[] = number_format($data_list->igst, 2, '.', ',');
            $row[] = number_format($total_igst_amt, 2, '.', ',');
            
            $row[] = '';
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            
            $row[] = number_format($gst_total, 2, '.', ',');
            $gst_total_amount = $gst_total_amount+$gst_total;
           	
            //Action button /////
            // End Action Button //
         	$data[] = $row;

         	


         	$tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = ''; 
              $tot_row[] = ''; 
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
               
              
              $tot_row[] = ''; 
			  $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($gst_total_amount,2,'.','').' readonly >'; 
              
              $data[] = $tot_row; 
           }
            $i++;


            
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_report->count_all(),
                        "recordsFiltered" => $this->medicine_report->count_filtered(),
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
		 $fields = array('S.No.','Name of Party','GSTIN','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Amount','Taxable Amount','SGST %age','SGST %age Amount','CGST %age','CGST %age Amount','IGST %age','IGST %age Amount','Cess','Total GST');
		
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
	          $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }
        
        $list = $this->medicine_report->search_medicine_data();
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

            	$total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
				$taxable_amount_total=$taxable_amount_total+$data_list->purchase_rate;
	            $amount_total=$amount_total+$data_list->purchase_rate;
	            $signal_unit1_price = $data_list->purchase_rate*$data_list->unit1;
	            $signal_unit2_price = ($data_list->purchase_rate/$data_list->conversion)*$data_list->unit2;
	            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
	            
				$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
	            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
	            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
	            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
	            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
	            $total_igst += ($total_row_amount/100)*$data_list->igst; 
	            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
	            $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
            if($n==1)
            {
            	array_push($rowData,$k,$data_list->vendor_name,$data_list->vendor_gst,date('d-m-Y', strtotime($data_list->purchase_date)),$data_list->invoice_id,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->purchase_rate,$data_list->purchase_rate,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,'0.0',$gst_total);
            	$k++;
            }
            else
            {
            	array_push($rowData,'','','','','','','',$data_list->hsn_no,$data_list->purchase_rate,$data_list->purchase_rate,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,'0.0',$gst_total);
				
				if($total_medicine_count<=$n)
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
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$row,number_format($gst_total_amount,2,'.',''));

			$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':R'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr1_report_".time().".xls");
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
        $data['report_list'] = $this->medicine_report->search_medicine_data();
        $this->load->view('medicine_report/gstr1_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("gstr1_list_".time().".pdf");
    }
    public function gstr1_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->search_medicine_data();
      $this->load->view('medicine_report/gstr1_html',$data); 
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
		 $fields = array('S.No.','Name of Party','GSTIN','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Amount','Taxable Amount','SGST %age','SGST %age Amount','CGST %age','CGST %age Amount','IGST %age','IGST %age Amount','Cess','Total GST');
		
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
	          $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	          $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	           $row_heading++;
	           $col++;
	      }
        
        $list = $this->medicine_report->search_medicine_data();
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

            	$total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
				$taxable_amount_total=$taxable_amount_total+$data_list->purchase_rate;
	            $amount_total=$amount_total+$data_list->purchase_rate;
	            $signal_unit1_price = $data_list->purchase_rate*$data_list->unit1;
	            $signal_unit2_price = ($data_list->purchase_rate/$data_list->conversion)*$data_list->unit2;
	            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
	            
				$total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
	            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
	            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
	            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
	            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
	            $total_igst += ($total_row_amount/100)*$data_list->igst; 
	            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
	            $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
            if($n==1)
            {
            	array_push($rowData,$k,$data_list->vendor_name,$data_list->vendor_gst,date('d-m-Y', strtotime($data_list->purchase_date)),$data_list->invoice_id,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->purchase_rate,$data_list->purchase_rate,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,'0.0',$gst_total);
            	$k++;
            }
            else
            {
            	array_push($rowData,'','','','','','','',$data_list->hsn_no,$data_list->purchase_rate,$data_list->purchase_rate,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,'0.0',$gst_total);
				
				if($total_medicine_count<=$n)
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
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$row,number_format($gst_total_amount,2,'.',''));

			$objPHPExcel->getActiveSheet()->getStyle('H'.$row.':R'.$row.'')->getFont()->setBold( true );  
			$objPHPExcel->setActiveSheetIndex(0);
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'CSV');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr1_report_".time().".csv");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
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
    
    public function purchase_gst_qty_report()
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
       $data['page_title'] = 'GSTR2 Quantity Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       //$data['all_detail'] = $this->medicine_report->get_all_medicine_report();
       $this->load->view('medicine_report/purchase_medicine_report_qty',$data);
    }
    
    
    
  public function gstr1_qty_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $list = $this->medicine_report->get_datatables();
   //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
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
        foreach ($list as $data_list) 
        {
            $total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
            $no++;
            $row = array();
            
                $row[] = $k;
                $row[] = $data_list->vendor_name;
                $row[] = $data_list->vendor_gst;
                $row[] = date('d-m-Y', strtotime($data_list->purchase_date));
                $row[] = $data_list->p_id;
                $row[] = $data_list->net_amount;
                $row[] = 'Local';
                $k++;

            

            $n++; 
            $row[] = $data_list->hsn_no;
            $row[] = $data_list->qty;
            $row[] = $data_list->total_amount;
            $row[] = $data_list->total_amount;

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
            $signal_unit1_price = $data_list->purchase_rate*$data_list->unit1;
            if($data_list->conversion>0)
            {
                $signal_unit2_price = ($data_list->purchase_rate/$data_list->conversion)*$data_list->unit2;
            }
            else
            {
                $signal_unit2_price = ($data_list->purchase_rate);
            }
            
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
            
            $total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
            
            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
            
            /*
            $tot_discount_amount+= (($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
            */
            $row[] = number_format($data_list->sgst,2,'.','');
            $row[] = number_format($total_sgst_amt,2,'.','');
            $row[] = number_format($data_list->cgst,2,'.','');
            $row[] = number_format($total_cgst_amt,2,'.','');
            $row[] = number_format($data_list->igst,2,'.','');
            $row[] = number_format($total_igst_amt,2,'.','');
            
            $row[] = '0';
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            
            $row[] = number_format($gst_total,2,'.','');
            $gst_total_amount = $gst_total_amount+$gst_total;
            
            //Action button /////
            // End Action Button //
            $data[] = $row;         


            $tot_row = array();
           if($i==$total_num)
           {
              
              $tot_row[] = ''; 
              $tot_row[] = ''; 
              $tot_row[] = ''; 
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
               
              
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value="0.00" readonly >'; 
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($gst_total_amount,2,'.','').' readonly >'; 
              
              $data[] = $tot_row; 
           }
            $i++;


            
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_report->count_all(),
                        "recordsFiltered" => $this->medicine_report->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    
    }

        public function advance_search_qty()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"",);
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('gstr1_search', $merge);
        }
    }


 public function gstr1_qty_excel()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('S.No.','Name of Party','GSTIN','Invoice Date','Invoice No.','Invoice Value','Type','HSN Code','Quantity','Amount','Taxable Amount','SGST %age','SGST Amount','CGST %age','CGST Amount','IGST %age','IGST Amount','Cess','Total GST');
        
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->medicine_report->search_medicine_data();
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

                $total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
                $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
                $amount_total=$amount_total+$data_list->total_amount;
                $signal_unit1_price = $data_list->purchase_rate*$data_list->unit1;
                $signal_unit2_price = ($data_list->purchase_rate/$data_list->conversion)*$data_list->unit2;
                $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$data_list->discount);
                
                $total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
                $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
                $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 
                $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
                $total_sgst += ($total_row_amount/100)*$data_list->sgst;
                $total_igst += ($total_row_amount/100)*$data_list->igst; 
                $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
                $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
            // if($n==1)
            // {
                array_push($rowData,$k,$data_list->vendor_name,$data_list->vendor_gst,date('d-m-Y', strtotime($data_list->purchase_date)),$data_list->invoice_id,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->qty,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,$total_sgst_amt,$data_list->cgst,$total_cgst_amt,$data_list->igst,$total_igst_amt,'0.0',$gst_total);
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

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$row,'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$row,number_format($amount_total,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$row,number_format($taxable_amount_total,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$row,number_format($total_sgst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$row,number_format($total_cgst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$row,number_format($total_igst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$row,number_format($gst_total_amount,2,'.',''));

            $objPHPExcel->getActiveSheet()->getStyle('H'.$row.':S'.$row.'')->getFont()->setBold( true );  
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=gstr1_qty_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }


 public function gstr1_qty_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->medicine_report->search_medicine_data();
        $this->load->view('medicine_report/gstr1_qty_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("gstr1_qty_list_".time().".pdf");
    }
    public function gstr1_qty_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->search_medicine_data();
      $this->load->view('medicine_report/gstr1_qty_html',$data); 
    }

    /*End GSTR1 With Quantity Report*/
    public function summarized_purchase_gstr_report()
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
       $data['page_title'] = 'GSTR2 Summarized Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       $this->load->view('medicine_report/summarized_purchase_gstr_report',$data);
    }
    
    
    public function summarized_gstr1_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $list = $this->medicine_report->summ_get_datatables();
        //echo "<pre>"; print_r($list); exit;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $gst_total_amount=0;
        $total_egst = 0;
        $total_tgst = 0;
        $total_fgst = 0;
        $total_zgst = 0;

        $total_egst_amt = 0;
        $total_tgst_amt = 0;
        $total_fgst_amt = 0;
        $total_zgst_amt = 0;
        $amount_total = 0;

        $n=1;
        $k=1;
        foreach ($list as $data_list) 
        {
            $no++;
            $row = array();
          
            $row[] = $k;
            $row[] = date('d-m-Y', strtotime($data_list->purchase_date));
            $total_amt = $data_list->egst+$data_list->tgst+$data_list->fgst+$data_list->zgst;
            $amount_total +=$total_amt;
            $total_egst +=$data_list->egst;
            $total_tgst +=$data_list->tgst;
            $total_fgst +=$data_list->fgst;
            $total_zgst +=$data_list->zgst;

            $total_egst_amt=$data_list->egst-($data_list->egst/1.18);
            $total_tgst_amt=$data_list->tgst-($data_list->tgst/1.12);
            $total_fgst_amt=$data_list->fgst-($data_list->fgst/1.05);
            $total_zgst_amt=$total_egst_amt+$total_tgst_amt+$total_fgst_amt;
            $gst_total_amount += $total_zgst_amt;

            $row[] = number_format($total_amt,2,'.','');
            $row[] = number_format($data_list->egst,2,'.','');
            $row[] = number_format($data_list->tgst,2,'.','');
            $row[] = number_format($data_list->fgst,2,'.','');
            $row[] = number_format($data_list->zgst,2,'.','');
            $row[] = number_format($total_zgst_amt,2,'.','');
            $k++;
            //Action button /////
            // End Action Button //
            $data[] = $row;         


            $tot_row = array();
           if($i==$total_num)
           {
              
         
              $tot_row[] = '';  
              $tot_row[] = '';   
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($amount_total,2,'.','').' readonly >'; 
    
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_egst,2,'.','').' readonly >';
         
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_tgst,2,'.','').' readonly >';
          
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_fgst,2,'.','').' readonly >';    
              $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($total_zgst,2,'.','').' readonly >'; 
               $tot_row[] = '<input type="text" class="w-150px" style="text-align:left;" value='.number_format($gst_total_amount,2,'.','').' readonly >'; 
              $data[] = $tot_row; 
           }
            $i++;           
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_report->count_all(),
                        "recordsFiltered" => $this->medicine_report->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    
    }

        public function advance_search_summarized()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"",);
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('gstr1_search', $merge);
        }
    }


    public function summarized_gstr1_excel()
    {
       
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         $fields = array('S.No.','Date','Total Amount','18%(CGST+SGST)','12%(CGST+SGST)','5%(CGST+SGST)','0%(CGST+SGST)','Total GST Amount');
        
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
    

              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->medicine_report->summ_search_medicine_data();
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
            $total_amt = $data_list->egst+$data_list->tgst+$data_list->fgst+$data_list->zgst;
            $amount_total +=$total_amt;
            $total_egst +=$data_list->egst;
            $total_tgst +=$data_list->tgst;
            $total_fgst +=$data_list->fgst;
            $total_zgst +=$data_list->zgst;

            $total_egst_amt=$data_list->egst-($data_list->egst/1.18);
            $total_tgst_amt=$data_list->tgst-($data_list->tgst/1.12);
            $total_fgst_amt=$data_list->fgst-($data_list->fgst/1.05);
            $total_zgst_amt=$total_egst_amt+$total_tgst_amt+$total_fgst_amt;
            $gst_total_amount += $total_zgst_amt;
            $row = array();
         
            array_push($rowData,$k,date('d-m-Y', strtotime($data_list->purchase_date)),$total_amt,$data_list->egst,$data_list->tgst,$data_list->fgst,$data_list->zgst, number_format($total_zgst_amt,'2','.',''));
                $k++;
           

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
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$row,'Total');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$row,number_format($amount_total,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$row,number_format($total_egst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$row,number_format($total_tgst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$row,number_format($total_fgst,2,'.',''));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$row,number_format($total_zgst,2,'.',''));
             $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$row,number_format($gst_total_amount,2,'.',''));

            $objPHPExcel->getActiveSheet()->getStyle('B'.$row.':G'.$row.'')->getFont()->setBold( true );  
            $objPHPExcel->setActiveSheetIndex(0);
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
        }
          
        // Sending headers to force the user to download the file
        header('Content-Type: application/octet-stream charset=UTF-8');
        header("Content-Disposition: attachment; filename=summarized_gstr1_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }

    }


    public function summarized_gstr1_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->medicine_report->summ_search_medicine_data();
        $this->load->view('medicine_report/summarized_gstr1_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("summarized_gstr1_report_".time().".pdf");
    }
    public function summarized_gstr1_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->summ_search_medicine_data();
      $this->load->view('medicine_report/summarized_gstr1_report_html',$data); 
    }
    
    function schedule_medicine_report($type='') {
      $users_data = $this->session->userdata('auth_users');
      $search = $this->session->userdata('schmed_qty_search');
      $data['page_title'] = 'Schedule medicines Report';

      $post = $this->input->post(); 
      $data['type'] =  $this->input->post('type');

      
     $this->load->view('medicine_report/schedule_medicine_report',$data);

    }
}
?>