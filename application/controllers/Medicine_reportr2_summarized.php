<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_reportr2_summarized extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('medicine_report/medicine_reportr2_summarized_model','medicine_report');
		$this->load->library('form_validation');
	}

	public function index()
	{
		
	}

	 public function summarized_sale_gstr_report()
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
       $data['page_title'] = 'GSTR1 Summarized Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       //$data['all_detail'] = $this->medicine_report->get_all_medicine_report();
       $this->load->view('medicine_report/summarized_sale_gstr_report',$data);
    }

    public function summarized_sale_gstr2_list()
    {
		$users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr2_search');
        $list = $this->medicine_report->get_datatables();
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
            $total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
            $no++;
            $row = array();
          
            $row[] = $k;
            $row[] = date('d-m-Y', strtotime($data_list->sale_date));
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

    public function summarized_gstr2_excel()
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
        
        $list = $this->medicine_report->search_medicine_data();
    	$rowData = array();
        $data= array();
        if(!empty($list))
        {
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
           
            array_push($rowData,$k,date('d-m-Y', strtotime($data_list->sale_date)),$total_amt,$data_list->egst,$data_list->tgst,$data_list->fgst,$data_list->zgst, number_format($total_zgst_amt,'2','.',''));
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
        header("Content-Disposition: attachment; filename=summarized_gstr2_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function summarized_gstr2_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->medicine_report->search_medicine_data();
        $this->load->view('medicine_report/summarized_gstr2_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("summarized_gstr2_report_".time().".pdf");
    }
    public function summarized_gstr2_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->medicine_report->search_medicine_data();
      $this->load->view('medicine_report/summarized_gstr2_report_html',$data); 
    }


      
}
?>