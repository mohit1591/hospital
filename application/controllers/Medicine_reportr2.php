<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_reportr2 extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('medicine_report/medicine_reportr2_model','medicine_report');
		$this->load->library('form_validation');
	}

	public function index()
	{
		
	}

	 public function sale_gst_report()
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
       $data['page_title'] = 'GSTR1 Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       //$data['all_detail'] = $this->medicine_report->get_all_medicine_report();
       $this->load->view('medicine_report/sale_medicine_report',$data);
    }

    public function gstr2_list()
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
            	$row[] = $data_list->patient_name;
	            $row[] = '';
	            $row[] = date('d-m-Y', strtotime($data_list->sale_date));
	            $row[] = $data_list->sale_no;
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
            $row[] = $data_list->total_amount;
            $row[] = $data_list->total_amount;

            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
            $per_pic_amount= $data_list->per_pic_price;
            $total_with_rate= $per_pic_amount*$data_list->qty;
            $total_new_amount= $total_with_rate-($total_with_rate/100)*$data_list->discount;

            $grand_amount= $total_new_amount+$data_list->total_amount;
            $totigst_amount = $data_list->igst;
            $total_igst_amt= ($total_new_amount/100)*$totigst_amount;

            //$newamountwithigst=$newamountwithigst+ $total_amountwithigst;
            $totcgst_amount = $data_list->cgst;
            $total_cgst_amt= ($totcgst_amount/100)*$total_new_amount;
            
            //$newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
            
            $totsgst_amount = $data_list->sgst; 
            $total_sgst_amt= ($total_new_amount/100)*$totsgst_amount; 
            //$newamountwithsgst=$newamountwithsgst+ $total_amountwithsgst; 

            $tot_discount_amount+= ($total_with_rate)/100*$data_list->discount; 

            $row[] = $data_list->sgst;
            $row[] = number_format($total_sgst_amt,2,'.','');
            $row[] = $data_list->cgst;
            $row[] = number_format($total_cgst_amt,2,'.','');
            $row[] = $data_list->igst;
            $row[] = number_format($total_igst_amt,2,'.','');
            
            $row[] = '';
            
            $total_cgst = $total_cgst+$total_cgst_amt; 
            $total_sgst = $total_sgst+$total_sgst_amt; 
            $total_igst = $total_igst+$total_igst_amt; 
            
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            if($gst_total>0)
            {
                $gst_total = number_format($gst_total,2,'.','');
            }
            else
            {
                $gst_total=0;
            }
            $row[] = $gst_total;
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
            $this->session->set_userdata('gstr2_search', $merge);
        }
    }

    public function reset_search()
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
				
            
            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
            $per_pic_amount= $data_list->per_pic_price;
            $total_with_rate= $per_pic_amount*$data_list->qty;
            $total_new_amount= $total_with_rate-($total_with_rate/100)*$data_list->discount;

            $grand_amount= $total_new_amount+$data_list->total_amount;
            $totigst_amount = $data_list->igst;
            $total_igst_amt= ($total_new_amount/100)*$totigst_amount;

            //$newamountwithigst=$newamountwithigst+ $total_amountwithigst;
            $totcgst_amount = $data_list->cgst;
            $total_cgst_amt= ($totcgst_amount/100)*$total_new_amount;
            
            //$newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
            $totsgst_amount = $data_list->sgst; 
            $total_sgst_amt= ($total_new_amount/100)*$totsgst_amount; 
            $tot_discount_amount+= ($total_with_rate)/100*$data_list->discount;
            $total_cgst = $total_cgst+$total_cgst_amt; 
            $total_sgst = $total_sgst+$total_sgst_amt; 
            $total_igst = $total_igst+$total_igst_amt; 

            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            $gst_total_amount = $gst_total_amount+$gst_total;

            $row = array();
            if($n==1)
            {
                //number_format($total_igst_amt,2,'.','');
            	array_push($rowData,$k,$data_list->patient_name,'',date('d-m-Y', strtotime($data_list->sale_date)),$data_list->sale_no,$data_list->net_amount,'Local',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,number_format($total_sgst_amt,2,'.',''),$data_list->cgst,number_format($total_cgst_amt,2,'.',''),$data_list->igst,number_format($total_igst_amt,2,'.',''),'0.0',number_format($gst_total,2,'.',''));
            	$k++;
            }
            else
            {
            	array_push($rowData,'','','','','','','',$data_list->hsn_no,$data_list->total_amount,$data_list->total_amount,$data_list->sgst,number_format($total_sgst_amt,2,'.',''),$data_list->cgst,number_format($total_cgst_amt,2,'.',''),$data_list->igst,number_format($total_igst_amt,2,'.',''),'0.0',number_format($gst_total,2,'.',''));
				
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
        header("Content-Disposition: attachment; filename=gstr2_report_".time().".xls");
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
        $data['report_list'] = $this->medicine_report->search_medicine_data();
        $this->load->view('medicine_report/gstr2_html',$data);
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
      $data['report_list'] = $this->medicine_report->search_medicine_data();
      $this->load->view('medicine_report/gstr2_html',$data); 
    }


      
}
?>