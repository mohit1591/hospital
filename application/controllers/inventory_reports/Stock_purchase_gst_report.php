<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_purchase_gst_report extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	 auth_users();  
  	$this->load->model('inventory_report/stock_purchase_report_model','stock_purchase_gst_report');
  	$this->load->library('form_validation');
  }

    
  	public function index()
    {
        //unauthorise_permission(165,952);
        $data['page_title'] = 'Stock Purchase GSTR1 Report'; 
        // Default Search Setting
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
        // End Defaul Search
        $data['form_data'] = array('purchase_no'=>'','invoice_id'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $this->load->view('inventory_report/stock_purchase_gst1_report',$data);
      }

    public function ajax_list_gst()
    {  
        //unauthorise_permission(165,952);
        $list = $this->stock_purchase_gst_report->get_datatables();  
//echo "<pre>"; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
		$k=1;
		$amount_total = 0;
		$gst_total_amount=0;
		$taxable_amount_total = 0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;


        $total_num = count($list);
        //$row='';
        foreach ($list as $stock_purchase_gst_report) { 
            $no++;
            $row = array();
            if($stock_purchase_gst_report->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($stock_purchase_gst_report->state))
            {
                $state = " ( ".ucfirst(strtolower($stock_purchase_gst_report->state))." )";
            }

            ////////// Check  List /////////////////
   
            $row[] = $k;
			$row[] = $stock_purchase_gst_report->name;
            $row[] = $stock_purchase_gst_report->purchase_no;
            $row[] = date('d-m-Y',strtotime($stock_purchase_gst_report->purchase_date));
            $row[] = $stock_purchase_gst_report->net_amount;
			$row[] = $stock_purchase_gst_report->total_amount;
			$row[] = $stock_purchase_gst_report->total_amount;

            $amount_total=$amount_total+$stock_purchase_gst_report->total_amount;
			$taxable_amount_total = $taxable_amount_total + $stock_purchase_gst_report->total_amount;
		
            $total_row_amount = $stock_purchase_gst_report->total_amount;
			$total_cgst_amt = ($total_row_amount/100)*$stock_purchase_gst_report->cgst;
            $total_sgst_amt= ($total_row_amount/100)*$stock_purchase_gst_report->sgst;
            $total_igst_amt= ($total_row_amount/100)*$stock_purchase_gst_report->igst; 
            
            $total_cgst += ($total_row_amount/100)*$stock_purchase_gst_report->cgst; 
            $total_sgst += ($total_row_amount/100)*$stock_purchase_gst_report->sgst;
            $total_igst += ($total_row_amount/100)*$stock_purchase_gst_report->igst; 
		
		    $row[] = number_format($stock_purchase_gst_report->cgst,2,'.',''); 
            $row[] = number_format($total_cgst_amt,2,'.','');
			$row[] = number_format($stock_purchase_gst_report->sgst,2,'.','');
            $row[] = number_format($total_sgst_amt,2,'.',''); 
            $row[] = number_format($stock_purchase_gst_report->igst,2,'.',''); 
            $row[] = number_format($total_igst_amt,2,'.',''); 

            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            $row[] = number_format($gst_total,2,'.',''); 
            $gst_total_amount = $gst_total_amount+$gst_total;
			
			$k++;
            $data[] = $row;

	     $tot_row = array();
         if($i==$total_num)
          {
		  $tot_row[] = '';  
		  $tot_row[] = '';  
		  $tot_row[] = '';  
		  $tot_row[] = '';  
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($amount_total,2).' readonly >'; 
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($amount_total,2).' readonly >';  
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($taxable_amount_total,2).' readonly >';  
		  $tot_row[] = ''; 			  
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($total_sgst,2).' readonly >';  
		  $tot_row[] = ''; 			  
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($total_cgst,2).' readonly >'; 
		  $tot_row[] = ''; 
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($total_igst,2).' readonly >'; 
		  $tot_row[] = '<input type="text" class="w-90px" style="text-align:left;" value='.number_format($gst_total_amount,2).' readonly >'; 
		  $data[] = $tot_row; 
          }
		   
           $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_purchase_gst_report->count_all(),
                        "recordsFiltered" => $this->stock_purchase_gst_report->count_filtered(),
                        "data" => $data,
                );
         //output to json format
        echo json_encode($output);
    }

 
public function stock_purchase_excel()
{
        // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $gst_total_amount =0;
          $grand_total_discount=0;
          $grand_paid_amount=0;
          $grand_balance_amount=0;
		  $taxable_amount_total =0;
		 	  
          $objWorksheet = $objPHPExcel->getActiveSheet();
         
          $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
     
		  $fields = array('Purchase Code','Purchase Date','Vendor Name','Total Amount','Discount','Net Amount','Paid Amount','Balance');
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
         $this->load->model('general/general_model');
 
         $list = $this->stock_purchase_gst_report->search_report_data();
          //print '<pre>'; print_r($list);die;
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $reports)
               {
				$gst_total_amount = $gst_total_amount + $reports->total_amount;
				$grand_total_discount = $grand_total_discount + $reports->discount;
				$taxable_amount_total = $taxable_amount_total + $reports->net_amount;
				$grand_paid_amount = $grand_paid_amount + $reports->paid_amount;
				$grand_balance_amount = $grand_balance_amount + $reports->balance;
 
                array_push($rowData,$reports->purchase_no,date('d-m-Y',strtotime($reports->purchase_date)),$reports->name,number_format($reports->total_amount,2),number_format($reports->discount,2),$reports->net_amount,$reports->paid_amount,number_format($reports->balance,2));
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
                     $row_val=1;

                    foreach ($fields as $field)
                    { 
                          $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $reports_data[$field]);
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(2,$row+1,'Total');
                          // added on 31-jan-2018
                          $objWorksheet->setCellValueByColumnAndRow(3,$row+1,number_format($gst_total_amount,2));
						  $objWorksheet->setCellValueByColumnAndRow(4,$row+1,number_format($grand_total_discount,2));
                          $objWorksheet->setCellValueByColumnAndRow(5,$row+1,number_format($taxable_amount_total,2));
                          $objWorksheet->setCellValueByColumnAndRow(6,$row+1,number_format($grand_paid_amount,2));
                          $objWorksheet->setCellValueByColumnAndRow(7,$row+1,number_format($grand_balance_amount,2));
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                    }
                    $row++;
               }
                // added on 24-mar-20
                $objPHPExcel->getActiveSheet()->getStyle('C'.$row.':H'.$row.'')->getFont()->setBold( true );
                 // // added on 24-mar-20
                $objPHPExcel->setActiveSheetIndex(0);
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=stock_purchase_gst_report_report_".time().".xls");  
          header("Pragma: no-cache"); 
          header("Expires: 0");
          if(!empty($data))
          {
                ob_end_clean();
               $objWriter->save('php://output');
          } 
        
    }
	
    public function pdf_stock_purchase()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->stock_purchase_gst_report->search_report_data();
        $this->load->view('inventory_report/stock_purchase_gst1_report_html.php',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("stock_purchase_gst_report_report_".time().".pdf");
    }
    public function print_stock_purchase()
    {  
      $data['print_status']="1";
      $data['data_list'] = $this->stock_purchase_gst_report->search_report_data();
      $this->load->view('inventory_report/stock_purchase_gst1_report_html.php',$data); 
    }
    public function total_calc_return()
    {
      $response = $this->session->userdata('net_values_all');
      $data = array('net_amount'=>'0','discount'=>'0','balance'=>'0','paid_amount'=>'0');
      if(isset($response))
      {
      $data = $response;
      }
      echo json_encode($data,true);
    }
    public function reset_search()
    {
        $this->session->unset_userdata('stock_purchase_report_search_gstr1');
    }
     public function advance_search()
      {

          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          //echo "<pre>"; print_r($post);
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                     
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_purchase_report_search_gstr1', $marge_post);
          }
          $stock_purchase_report_search = $this->session->userdata('stock_purchase_report_search_gstr1');
          //echo "<pre>"; print_r($stock_purchase_report_search); die;
          if(isset($stock_purchase_report_search) && !empty($stock_purchase_report_search))
          {
              $data['form_data'] = $stock_purchase_report_search;
          }
          //$this->load->view('purchase/advance_search',$data);
        }


}


?>