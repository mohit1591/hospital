<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_vendor_rate_compa_report extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    auth_users();
    $this->load->model('inventory_vendor_rate_compa_report/vendor_rate_comparison_report_model','vendor_rate_comparison');  
    $this->load->library('form_validation');
  }

   public function index()
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
       $data['page_title'] = 'Vendor Item Purchase Report';
       $data['form_data'] = array('start_date'=>$start_date, 'end_date'=>$end_date);
       $this->load->model('stock_purchase/stock_purchase_model','stock_purchase');
       $data['vendor_list'] = $this->stock_purchase->vendor_list();
       $this->load->view('inventory_vendor_rate_compa_report/vendor_rate_comparison_report',$data);
    }

    public function ajax_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $data = array();
        $no = $_POST['start'];
        $search = $this->session->userdata('inventory_rate_data_search');

        $list = $this->vendor_rate_comparison->get_datatables();

        //echo"<pre>";print_r($list);die;

        $total_num = count($list);
        $i = 1;
        
        $grand_total_mrp =0;
	    $grand_total_purchase=0;
	    $grand_paid_discount=0;
	    $grand_total_amount=0;
	    $total_qty=0;
	    
        foreach($list as $reports) 
        {
    
               $check_script = "";
               if($i==$total_num){
                    $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
               } 
                            
               ////////// Check list end ///////////// 
            $no++;
            //$discounts = ($reports->discount/100)*($reports->per_pic_price*$reports->qty);
            $row = array(); 
            $row[] = $i;
            $row[] = $reports->item_code; 
            $row[] = $reports->item_name; 
            $row[] = $reports->serial_numbers;  
            $row[] = $reports->vendor_name; 
            $row[] =$reports->qty;
            $row[] =$reports->mrp;
            if($reports->purchase_amount!='0.00')
            {
               $row[] = $reports->purchase_amount;
            }
            else
            {
               $row[] = $reports->per_pic_price;
            }
            //$row[] =$reports->purchase_amount;
            $row[] = number_format($reports->discount,2,'.','');
            $row[] =$reports->total_amount;
            $row[] = date('d-m-Y',strtotime($reports->purchase_date));
            $data[] = $row;
			
			  //$grand_total_mrp = $grand_total_mrp+$reports->mrp;
			  //$grand_total_purchase = $grand_total_purchase + $reports->purchase_amount;
			  //$grand_paid_discount = $grand_paid_discount +$discounts;
			 $grand_total_amount = $grand_total_amount + $reports->total_amount;
			 $total_qty = $total_qty+$reports->qty;
			  
			  $tot_row = array();
               if($i==$total_num)
               {
                  $tot_row[] = '';  
                  $tot_row[] = '';  
    			  $tot_row[] = '';  
    			  $tot_row[] = '';  
                  $tot_row[] = '';
                  $tot_row[] = '<input type="text" class="w-90px" style="text-align:right;" value='.number_format($total_qty,2).' readonly >';
                  $tot_row[] = '';
                  $tot_row[] = '';
                  $tot_row[] = '';
                  $tot_row[] = '<input type="text" class="w-90px" style="text-align:right;" value='.number_format($grand_total_amount,2).' readonly >'; 
                 
                  $tot_row[] = '';
                  $tot_row[] = '';
                  $data[] = $tot_row; 
               }
            $i++;
        }

       $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vendor_rate_comparison->count_all(),
                        "recordsFiltered" => $this->vendor_rate_comparison->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    } 

    
    

    public function advance_search()
    {
        $post = $this->input->post();
        $data['form_data'] = array("start_date"=>"","end_date"=>"","vendor_id"=>"");
        if(isset($post) && !empty($post))
        {
            $merge= array_merge($data['form_data'],$post); 
            $this->session->set_userdata('inventory_rate_data_search', $merge);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('inventory_rate_data_search');
    }

    public function vendor_rate_comparison_excel()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
         // Field names in the first row
         
         $fields = array('S.No.','Item Code','Item Name','Item Serial Number','Vendor Name','Quantity','MRP','Purchase Rate','Discount','Net Amount','Date');
         
         
        
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
              $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
          
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $row_heading++;
               $col++;
          }
        
        $list = $this->vendor_rate_comparison->search_medicine_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
            $i=1;
            foreach($list as $data_list)
            {
                // $discounts = ($data_list->discount/100)*($data_list->per_pic_price*$data_list->qty);
    
    
            if($data_list->purchase_amount!='0.00')
            {
               $purchase_amount = $data_list->purchase_amount;
            }
            else
            {
              $purchase_amount = $data_list->per_pic_price;
            }
            
                    array_push($rowData,$i,$data_list->item_code,$data_list->item_name,$data_list->serial_numbers,$data_list->vendor_name,$data_list->qty,$data_list->mrp,$purchase_amount,$data_list->discount,$data_list->total_amount,date('d-m-Y',strtotime($data_list->purchase_date)));
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
        header("Content-Disposition: attachment; filename=inventory_vendor_report_".time().".xls");
        header("Pragma: no-cache"); 
        header("Expires: 0");
        if(!empty($data))
        {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }



    public function vendor_rate_comparison_pdf()
    {    
        $data['print_status']="";
        $data['report_list'] = $this->vendor_rate_comparison->search_medicine_data();
        $this->load->view('inventory_vendor_rate_compa_report/vendor_rate_comparison_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("inventory_vendor_report_".time().".pdf");
    }
    public function vendor_rate_comparison_print()
    {    
      $data['print_status']="1";
      $data['report_list'] = $this->vendor_rate_comparison->search_medicine_data();
      $this->load->view('inventory_vendor_rate_compa_report/vendor_rate_comparison_html',$data); 
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
 
     $this->load->view('medicine_gst/vendor_rate_comparison_data',$data);  
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