<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_medicine extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('sales_return_medicine/sales_return_medicine_model','sales_return_medicine');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(61,407);
        $this->session->unset_userdata('medicine_id'); 
        $this->session->unset_userdata('sale_search');
        $this->session->unset_userdata('net_values_all'); 
        $this->load->model('general/general_model'); 
        $data['doctors_list']= $this->general_model->doctors_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
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
         $data['form_data'] = array('sale_no'=>'','return_no'=>'','referred_by'=>'','refered_id'=>'','referral_hospital'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Sales Return List'; 
        $this->load->view('sales_return_medicine/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(61,407);
        $list = $this->sales_return_medicine->get_datatables();  
        $assoc_array = json_decode(json_encode($list),TRUE);
        $session_data= $this->session->userdata('auth_users');

        $total_net_amount = array_sum(array_column($assoc_array,'net_amount'));
        $total_discount = array_sum(array_column($assoc_array,'discount_percent'));
        $total_balance= array_sum(array_column($assoc_array,'balance'));
        $total_vat= array_sum(array_column($assoc_array,'vat'));
        $total_paid_amount= array_sum(array_column($assoc_array,'paid_amount'));
        $session_new_datas=array('net_amount'=>$total_net_amount,'discount'=>$total_discount,'balance'=>$total_balance,'paid_amount'=>$total_paid_amount);
        $this->session->set_userdata('net_values_all',$session_new_datas);
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $sales_return) { 
            $no++;
            $row = array();
           
           
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }          
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="sales_return[]" class="checklist" value="'.$sales_return->id.'">'.$check_script;  
            
            $row[] = $sales_return->sale_no;
            $row[] = $sales_return->return_no;
            $row[] = $sales_return->patient_name;
            $row[] = $sales_return->doctor_hospital_name;
           // $row[] = $sales_return->total_amount;
            $row[] = $sales_return->net_amount;
            $row[] = $sales_return->paid_amount;
            $row[] = $sales_return->balance;
            $row[] = date('d-M-Y',strtotime($sales_return->sale_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if($session_data['parent_id']==$sales_return->branch_id){
           if(in_array('409',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_sales_return_medicine('.$sales_return->id.');" class="btn-custom" href="javascript:void(0)" style="'.$sales_return->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            //if(in_array('125',$users_data['permission']['action'])){
                /*$btnview=' <a class="btn-custom" onclick="return view_purchase('.$purchase->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';*/
           // }
            if(in_array('410',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_sales_return_medicine('.$sales_return->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }   
          }
            //$btnprint = '<a class="btn-custom" onclick="openPrintWindow(123,'.$sales_return->id.');" target="_blank"><i class="fa fa-print"></i> Print</a>';

             $print_url = "'".base_url('sales_return_medicine/print_sales_report/'.$sales_return->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            
            
            $print_barcode_url = "'".base_url('sales_return_medicine/print_barcode/'.$sales_return->id)."'";
            $btn_barcode = ' <a class="btn-custom"  href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a>';

            $row[] = $btnedit.$btnview.$btndelete. $btnprint.$btn_barcode;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_return_medicine->count_all(),
                        "recordsFiltered" => $this->sales_return_medicine->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
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


    public function ajax_list_medicine()
    { //$this->session->unset_userdata('medicine_id');
       //$medicine_list = $this->session->userdata('medicine_id');
       //print_r($medicine_list);die;
       $ids=array();
       $post = $this->input->post(); 
        /*if(!empty($medicine_list))
        { 
          $ids_arr= [];
          foreach($medicine_list as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $m_arr['mid'];
             $batch_arr[] = $m_arr['batch_no'];
          }
          $medicine_ids = implode(',', $ids_arr); 
          $batch_nos = implode(',', $batch_arr);  
          $data['medicne_new_list'] = $this->sales_return_medicine->medicine_list($ids_arr,$batch_arr);
           foreach($data['medicne_new_list'] as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }*/
        
        $table ='';
        $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
        $keywords= $this->input->post('search_keyword');
         $result_medicine = [];
         $name= $this->input->post('name');
          if(!empty($post['medicine_name']) ||!empty($post['medicine_company']) ||!empty($post['batch_number']) ||!empty($post['bar_code']) || !empty($post['medicine_code']) || !empty($post['qty']) || !empty($post['stock']) || !empty($post['rate']) || !empty($post['packing']) ||!empty($post['discount'])||!empty($post['hsn_no']) ||!empty($post['igst'])||!empty($post['cgst'])||!empty($post['sgst']))
          {  
             $result_medicine = $this->sales_return_medicine->medicine_list_search();  
          }  
       // print_r($ids);
         if((isset($result_medicine) && !empty($result_medicine)) || !empty($ids)){
         
            foreach($result_medicine as $medicine)
            { 
              
                
                $table.='<tr class="append_row" id="'.$medicine->id.'.'.$medicine->batch_no.'">';
                //if($medicine->qty>0) //remove as return will not need this
                  //{
                      $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$medicine->id.'.'.$medicine->batch_no.'" onclick="add_check();"></td>';
                      $table.='<td>'.$medicine->medicine_name.'</td>';
                      $table.='<td>'.$medicine->packing.'</td>';
                      $table.='<td>'.$medicine->medicine_code.'</td>';
                       $table.='<td>'.$medicine->hsn_no.'</td>';
                      $table.='<td>'.$medicine->company_name.'</td>';
                      $table.='<td>'.$medicine->batch_no.'</td>';
                      $table.='<td>'.$medicine->bar_code.'</td>';
                      $table.='<td>'.$medicine->min_alrt.'</td>';//$qty_data['total_qty']. //$medicine->qty
                      $table.='<td>'.$medicine->qty.'</td>';
                      $table.='<td>'.$medicine->mrp.'</td>';
                      $table.='<td>'.$medicine->discount.'</td>';
                      $table.='<td>'.$medicine->cgst.'</td>';
                      $table.='<td>'.$medicine->sgst.'</td>';
                      $table.='<td>'.$medicine->igst.'</td>';

                      $table.='</tr>';
                   //}
                //}
            }
          }
        else
        {
             $table='<tr class="append_row"><td class="text-danger" colspan="15"><div class="text-center">No record found</div></td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
        }

    public function medicine_sales_excel()
    {
        /*$list = $this->sales_return_medicine->search_report_data();
        // print_r( $list);die;
        $columnHeader = '';  
        $columnHeader = "Sale No." . "\t" . "Patient Name" . "\t" . "Refered  By" . "\t" . "Net Amount" . "\t" . "Paid Amount" . "\t" . "Balance". "\t" . "Created Date";
        $setData = '';
        if(!empty($list))
        {
//print '<pre>';print_r($list);die;
           
            $rowData = "";
            foreach($list as $reports)
            {
               
                $rowData = $reports->sale_no . "\t" . $reports->patient_name. "\t" . $reports->doctor_name . "\t" . $reports->net_amount . "\t" . $reports->paid_amount . "\t". $reports->balance. "\t". date('d-M-Y H:i A',strtotime($reports->created_date)); 
                $setData .= trim($rowData) . "\n";    
            }
        }
        //echo $setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=medicine_sales_return_report_".time().".xls");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; 

        */

          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Sale No.','Return No.','Patient Name','Referred By','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
               $col++;
          }
          $list =$this->sales_return_medicine->search_report_data();
          //print_r($list);die;
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->return_no,$reports->patient_name,$reports->doctor_hospital_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
               foreach($data as $boking_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=medicine_sales_return_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function medicine_sales_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
           $fields = array('Sale No.','Return No.','Patient Name','Referred By','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->sales_return_medicine->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->return_no,$reports->patient_name,$reports->doctor_hospital_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
         header("Content-Disposition: attachment; filename=medicine_sales_return_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
           ob_end_clean();
               $objWriter->save('php://output');
         }


    }

    public function pdf_medicine_sales()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->sales_return_medicine->search_report_data();
       
        $this->load->view('sales_return_medicine/medicine_sales_return_report_html',$data);
        $html = $this->output->get_output();
        //print_r($html);
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_sales_return_report_".time().".pdf");
    }
    public function print_medicine_sales()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->sales_return_medicine->search_report_data();
      $this->load->view('sales_return_medicine/medicine_sales_return_report_html',$data); 
    }

    public function ajax_added_medicine(){
         //$this->session->unset_userdata('medicine_id');die;
         $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
         $medicine_sess = $this->session->userdata('medicine_id');
         //echo "<pre>";print_r($medicine_sess);die;
         $check_script="";
         $result_medicine = [];
        if(!empty($medicine_sess))
        { 
          $ids_arr= [];
                
          foreach($medicine_sess as $key_m_arr=>$m_arr)
          {
             $imp_data = explode(".", $key_m_arr);
             $ids_arr[] = $imp_data[0];
             $batch_arr[] = $imp_data[1];
          }
          //print_r($batch_arr);die;
          //$medicine_ids = implode(',', $ids_arr); 
         // $batch_nos = implode(',', $batch_arr); 
          //echo $batch_nos;
        //  $result_medicine = $this->purchase->get_medicine_by_id($key_m_arr->medicine_id);
          // print_r($result_medicine);die;
        }
    // die;
       $check_script='';
        $table='<div class="left">';
             $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
               $table.='<thead class="bg-theme">';
                    $table.='<tr>';
                      $table.='<th class="40" align="center">';
                       $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                       $table.='</th>';
                        $table.='<th>Medicine Name</th>';
                        $table.='<th>Medicine Code</th>';
                        $table.='<th>HSN No.</th>';
                        $table.='<th>Packing</th>';
                        $table.='<th>Batch No.</th>';
                       // $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>Barcode</th>';
                         $table.='<th>Quantity</th>';
                       
                        $table.='<th>MRP</th>';
                       /* $table.='<th>P.Rate</th>';*/
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST(%)</th>';
                        $table.='<th>SGST(%)</th>';
                        $table.='<th>IGST(%)</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        //print_r($result_medicine);die;
                        if(count($medicine_sess)>0 && isset($medicine_sess) || !empty($ids)){

                        foreach($medicine_sess as $medicine){
                            
                            
                        //  print_r($medicine['purchase_id']);die;
                             if($medicine['expiry_date']=="00-00-0000")
                             {

                                $date_new='';
                            }else{
                                $date_new=$medicine['expiry_date'];
                            }
                       if($medicine['manuf_date']=="00-00-0000"){

                                                      $date_newmanuf='';
                                                  }else{
                                                      $date_newmanuf=$medicine['manuf_date'];
                                                  }

                        $varids=$medicine['id'].$medicine['batch_no'];

                        $value="'".$medicine['id']."'";


                          $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine['id'].$medicine['batch_no']."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine['id'].$medicine['batch_no']."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           $('#discount_".$medicine['id'].$medicine['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$medicine['id'].$medicine['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine['id'].$medicine['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine['id'].$medicine['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                          //if(!in_array($medicine->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine['id'].$medicine['batch_no'].'" name="m_id[]" value="'.$medicine['id'].'.'.$medicine['batch_no'].'"/>
                         <input type="hidden" id="mbid_'.$medicine['id'].$medicine['batch_no'].'" name="mbid[]" value='.$value.'/>
                     <input type="hidden" id="purchase_rate_mrp'.$medicine['id'].$medicine['batch_no'].'" name="purchase_rate_mrp[]" value="'.$medicine['mrp'].'"/><input type="hidden" id="batch_no_'.$medicine['id'].$medicine['batch_no'].'" name="batch_no[]" value="'.$medicine['batch_no'].'"/><input type="hidden" id="conversion_'.$medicine['id'].$medicine['batch_no'].'" name="conversion[]" value="'.$medicine['conversion'].'"/>';

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$medicine['mid'].'.'.$medicine['batch_no'].'></td>';
                        $table.='<td>'.$medicine['medicine_name'].'</td>';
                        $table.='<td>'.$medicine['medicine_code'].'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$medicine['id'].$medicine['batch_no'].'" name="hsn_no[]" value="'.$medicine['hsn_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine['packing'].'</td>';
                         $table.='<td>'.$medicine['batch_no'].'</td>';
                       // $table.='<td>'.$medicine->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine['id'].$medicine['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine['id'].$medicine['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text"  value="'.$medicine['bar_code'].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine['id'].$medicine['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$medicine['id'].$medicine['batch_no'].'"  style="color:red;"></td>';

          

                             $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine['id'].$medicine['batch_no'].'" value="'.$medicine['qty'].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(qty,'.$varids.');"/><div  id="unit1_error_'.$medicine['id'].$medicine['batch_no'].'"  style="color:red;"></div></td>';
                        //$table.='<td>'.$medicine->medicine_unit_2.'</td>';
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$medicine['id'].$medicine['batch_no'].'" name="mrp[]" value="'.number_format($medicine['mrp'],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        // $table.='<td><input type="text" id="purchase_rate_'.$medicine['id'].'" class="w-60px" name="purchase_rate[]" value="'.$medicine["purchase_rate"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$medicine['id'].$medicine['batch_no'].'" value="'.$medicine['discount'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine['cgst'].'" id="cgst_'.$medicine['id'].$medicine['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$medicine['sgst'].'" id="sgst_'.$medicine['id'].$medicine['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine['igst'].'" id="igst_'.$medicine['id'].$medicine['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.=' <td><input type="text" value="'.$medicine['total_amount'].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$medicine['id'].$medicine['batch_no'].'" /></td>';
                       
                        $table.='</tr>';
                          // }
                       }
                       }else{
                        $table.='<td colspan="15" class="text-danger"><div class="text-center">No record found</div></td>';
                       }
                        $table.='</tbody>';
                        $table.='</table>';
                        $table.='</div>';
                        $table.='<div class="right">';
                        $table.='<a class="btn-new" onclick="medicine_list_vals();">Delete</a>';
                        $table.='</div>'; 
                     $output=array('data'=>$table);
                     echo json_encode($output);
        
    }


    public function set_medicine()
    {
       
       $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post =  $this->input->post(); 
       $post_mid_arr = [];
       $m_new_array_id=[];
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
         $purchase = $this->session->userdata('medicine_id');
         $medicine_id = [];
         $mid_arr = [];

         if(isset($purchase) && !empty($purchase))
         {

         } 
         else
         {
           $this->session->set_userdata('medicine_id',[]);
           $purchase = $this->session->userdata('medicine_id');
         }  

         $total_price_medicine_amount=0;
         $m_ids = $post['medicine_id'][0];
         $m_id_arr = explode('.',$post['medicine_id'][0]);
         $vat='';
        $medicine_data = $this->sales_return_medicine->medicine_list($m_id_arr[0],$m_id_arr[1]);
         // print_r($medicine_data);
        $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
        $tot_qty_with_rate= $per_pic_amount*1;
        //echo $per_pic_amount;die;
        $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
        $total_amount= $tot_qty_with_rate-$total_discount;
        $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
        $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
        $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
        $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
        $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
        if(strtotime($medicine_data[0]->manuf_date)>316000)
        {
                   $manuf_date=date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
        }
        else
        {
                   $manuf_date='';
        } 

        if(strtotime($medicine_data[0]->expiry_date)>315000)
        {
                   $exp_date=date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
        }
        else
        {
                   $exp_date='';
        }  

 
        $purchase[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0], 'id'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1], 'medicine_code'=>$medicine_data[0]->medicine_code, 'medicine_name'=>$medicine_data[0]->medicine_name, 'packing'=>$medicine_data[0]->packing, 'mrp'=>$per_pic_amount, 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicine_data[0]->discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conv,'hsn_no'=>$medicine_data[0]->hsn,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 

         
         $this->session->set_userdata('medicine_id',$purchase); 
         //print_r($this->session->userdata('medicine_id'));die;
         //$this->ajax_added_medicine();
       }
    
    }

     public function remove_medicine_list()
    {
        
    	$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post =  $this->input->post();
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
           $ids_list = $this->session->userdata('medicine_id');
           //echo "<pre>"; print_r($ids_list);die;
           
             foreach($post['medicine_id'] as $post_id)
             {
                  if(array_key_exists($post_id,$ids_list))
                  {

                     unset($ids_list[$post_id]);
                  }
             } 
             $this->session->set_userdata('medicine_id',$ids_list);
           
          /* $medicne_list = [];
           $ids_list = $this->session->userdata('medicine_id');  
           $id_arr = []; 
           if(!empty($ids_list))
           {
             foreach($ids_list as $id_key=>$id_arr)
             {
               $id_arr[] = $id_key;
             }
           }
           $imp_ids = implode(',', $id_arr); 
           $medicine_listdata = [];
           if(!empty($imp_ids))
           {
            $medicine_listdata = $this->sales_return_medicine->medicine_list();
           }*/
           //$this->ajax_added_medicine();
       }
    } 

	public function add()
	{ 
    
        unauthorise_permission(61,408);
        $users_data = $this->session->userdata('auth_users');
        $pid='';
        
        if(isset($_GET['reg'])){
          $pid= $_GET['reg'];
        }
         if(isset($_GET['ipd'])){
          $pid= $_GET['ipd'];
        }
        $sale_id='';
        if(isset($_GET['sid'])){
          $sale_id= $_GET['sid'];
        }
        $sale_no = generate_unique_id(17);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add Sale Medicine Return";
		$data['form_error'] = [];
        $data['button_value'] = "Save";
		$post = $this->input->post();
        
        $vendor_id='';
        $purchase_no = "";
        $vendor_code = "";
        $name = "";
        $patient_name = "";
        $mobile_no = "";
        $email = "";
        $address = "";
        $simulation_id="";
        $gender='';
        $relation_type="";
        $relation_name="";
        $relation_simulation_id="";
        $total_amount="0.00";
        $discount_amount="";
        $medicine_discount="";
        $net_amount="0.00";
        $igst_amount="";
        $sgst_amount="";
        $cgst_amount="";
        $pay_amount="";
        $discount_percent="";
       if($pid>0)
        {
           $patient = $this->sales_return_medicine->get_patient_by_id($pid);
           //print_r($purchase);
           if(!empty($patient))
           {
             

              $patient_id = $patient['id'];
              $simulation_id = $patient['simulation_id'];
              $patient_reg_code = $patient['patient_code'];
              $name = $patient['patient_name'];
              $mobile_no = $patient['mobile_no'];
              $email = $patient['patient_email'];
              $gender=$patient['gender'];
              $relation_type=$patient['relation_type'];
              $relation_name=$patient['relation_name'];
              $relation_simulation_id=$patient['relation_simulation_id'];
              }
        }
        elseif(!empty($sale_id))
        {
            //for sale return
            $this->load->model('sales_medicine/sales_medicine_model','salesmedicine');
            $result = $this->salesmedicine->get_by_id($sale_id); 
            $result_patient = $this->salesmedicine->get_patient_by_id($result['patient_id']);
            //echo "<pre>"; print_r($result); exit;
            $result_medince_list = $this->salesmedicine->get_medicine_by_sales_id($sale_id,$result['total_amount']);
            //$this->session->set_userdata('medicine_id',$result_medince_list);
            $data['sale_medicne_list'] = $result_medince_list;
            //echo "<pre>";print_r($data['sale_medicne_list']); exit;
              $patient_id = $result_patient['id'];
              $simulation_id = $result_patient['simulation_id'];
              $patient_reg_code = $result_patient['patient_code'];
              $name = $result_patient['patient_name'];
              $mobile_no = $result_patient['mobile_no'];
              $email = $result_patient['patient_email'];
              $gender=$result_patient['gender'];
              $relation_type=$result_patient['relation_type'];
              $relation_name=$result_patient['relation_name'];
              $relation_simulation_id=$result_patient['relation_simulation_id'];
              
              //
              $total_amount=$result['total_amount'];
              $discount_amount=$result['discount'];
              $medicine_discount=$result['medicine_discount'];
              $net_amount=$result['net_amount'];
              $igst_amount=$result['igst'];
              $sgst_amount=$result['sgst'];
              $cgst_amount=$result['cgst'];
              $pay_amount=$result['paid_amount'];
              $discount_percent=$result['discount_percent'];
              
            
        }
        else
        {
          $patient_reg_code=generate_unique_id(4);

         // $medicine_list = $this->session->userdata('medicine_id');
          $data['medicine_id'] = $medicine_list;
        } 
        if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          $medicine_bno_arr = [];  
          foreach($medicine_list as $key=>$medicine_sess)
          {
             $medicine_id_arr[] = "'".$medicine_sess['mid']."'";
             $medicine_bno_arr[] = "'".$medicine_sess['batch_no']."'";
          }  
          $medicine_id_arr = implode(',', $medicine_id_arr);
          $medicine_bno_arr = implode(',', $medicine_bno_arr);  
          $data['medicne_new_list'] = $this->sales_return_medicine->medicine_list($medicine_id_arr,$medicine_bno_arr,1);
        }


         $data['simulation_list']= $this->general_model->simulation_list();
          $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
          $this->load->model('opd/opd_model','opd');
         $data['doctors_list'] = $this->opd->referal_doctor_list();
         $data['payment_mode']=$this->general_model->payment_mode();
         $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
    		$data['form_data'] = array(
                                    "data_id"=>"",
                                    'patient_id'=> $pid,
                                    "patient_reg_code"=>$patient_reg_code,
                                    "name"=>$name,
                                    'sales_no'=>'',
                                    'return_no'=>$sale_no,
                                     "gender"=>$gender,
                                    'refered_id'=>'',
                                    'simulation_id'=>$simulation_id,
                                    "mobile"=>$mobile_no,
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "sales_date"=>date('d-m-Y'),
                                   
                                    
                                    'payment_mode'=>"",
                                    //'bank_name'=>"",
                                    'remarks'=>'',
                                    //'card_no'=>"",
                                    "remarks"=>"",
                                    //'cheque_no'=>"",
                                    'sale_date'=>"",
                                    "field_name"=>'',
                                    
                                    "payment_date"=>date('d-m-Y'),
                                    'discount_percent'=>"",
                                    //'vat_percent'=>get_setting_value('MEDICINE_VAT_VALUE'),
                                   // 'transaction_no'=>"",
                                    "country_code"=>"+91",
                                    'referred_by'=>'',
                                    "relation_type"=>$relation_type,
                                    "relation_name"=>$relation_name,
                                    "relation_simulation_id"=>$relation_simulation_id,
                                    'referral_hospital'=>'',
                                     'total_amount'=>$total_amount,
                                    'discount_amount'=>$discount_amount,
                                    'medicine_discount'=>$medicine_discount,
                                    'net_amount'=>$net_amount,
                                    "igst_amount"=>$igst_amount,
                                    "sgst_amount"=>$sgst_amount,
                                    "cgst_amount"=>$cgst_amount,
                                    'pay_amount'=>$pay_amount,
                                    'discount_percent'=>$discount_percent,
    			                      );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            
            if($this->form_validation->run() == TRUE)
            {
                
                $salesid=  $this->sales_return_medicine->save();
                
                if(!empty($salesid))
                {
                   $get_by_id_data = $this->sales_return_medicine->get_by_id($salesid);
                    $get_patient_by_id = $this->sales_return_medicine->get_patient_by_id($get_by_id_data['patient_id']);
                    //print_r($get_by_id_data); exit;
                    $patient_name = $get_patient_by_id['patient_name'];
                    $mobile_no = $get_patient_by_id['mobile_no'];
                    $patient_email = $get_patient_by_id['patient_email'];
                    //print_r($get_by_id_data); exit;
                    $return_no = $get_by_id_data['return_no'];
                    $paid_amount = $get_by_id_data['paid_amount'];
                    $net_amount = $get_by_id_data['net_amount'];
                  //check permission
                  if(in_array('640',$users_data['permission']['action']))
                  {
                   
                    if(!empty($mobile_no))
                    {
                      send_sms('sale_medicine_return',5,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{Amt}'=>$net_amount,'{BillNo}'=>$return_no,'{PaidAmt}'=>$paid_amount)); 
                    }

                   }

                  if(in_array('641',$users_data['permission']['action']))
                  {
                    if(!empty($patient_email))
                    {
                      
                      $this->load->library('general_functions');
                      $this->general_functions->email($patient_email,'','','','','1','sale_medicine_return','5',array('{Name}'=>$patient_name,'{Amt}'=>$net_amount,'{BillNo}'=>$return_no,'{PaidAmt}'=>$paid_amount));
                       
                    }
                  } 
                }  

                $this->session->set_userdata('sales_id',$salesid);
                $this->session->set_flashdata('success','Sales return medicine has been successfully added.');
                redirect(base_url('sales_return_medicine/add/?status=print'));
            }
            else
            { 
                 //echo "<pre>"; print_r(validation_errors());die;  
                $data['form_error'] = validation_errors();  
                //echo "<pre>"; print_r($data['form_error']);die;  
               
            }       
        }
        $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        
        $data['discount_setting'] = $discount_setting_data[1];
      $this->load->view('sales_return_medicine/add',$data);
	}

	public function edit($id="")
    {
       unauthorise_permission(61,409);
       $this->load->model('general/general_model'); 
       
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->sales_return_medicine->get_by_id($id); 
         //echo "<pre>"; print_r($result); exit;
         $medicine_id_arr=[];
         $result_patient = $this->sales_return_medicine->get_patient_by_id($result['patient_id']);
        // print_r($result_patient);
         if(empty($post))
         { 
            $result_medince_list = $this->sales_return_medicine->get_medicine_by_sales_id($id,$result['total_amount']);
         }
         $data['medicne_list'] = $result_medince_list;
         $data['id'] = $id;
        //print '<pre>';print_r($medicine_list);
       
        $data['simulation_list']= $this->general_model->simulation_list();
         $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
        $this->load->model('opd/opd_model','opd');
        $data['doctors_list'] = $this->opd->referal_doctor_list();
        $data['payment_mode']=$this->general_model->payment_mode();
        $get_payment_detail= $this->sales_return_medicine->payment_mode_detail_according_to_field($result['payment_mode'],$id);
        $total_values='';
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        //print '<pre>';print_r($data['medicne_new_list']);
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
         $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
        $data['page_title'] = "Edit Sale Medicine Return";  
        $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        $data['form_data'] = array(

                                    "patient_id"=> $result['patient_id'],
                                    "patient_reg_code"=>$result_patient['patient_code'],
                                    "name"=>$result_patient['patient_name'],
                                    'sales_no'=>$result['sale_no'],
                                    "gender"=>$result_patient['gender'],
                                    'return_no'=>$result['return_no'],
                                    "mobile"=>$result_patient['mobile_no'],
                                    'refered_id'=>$result['refered_id'],
                                    'simulation_id'=>$result['simulation_id'],
                                    "data_id"=>$result['id'],
                                    "branch_id"=>$result['branch_id'],
                                    "sales_date"=>date('d-m-Y',strtotime($result['sale_date'])),
                                    'total_amount'=>$result['total_amount'],
                                    'discount_amount'=>$result['discount'],
                                    'payment_mode'=>$result['payment_mode'],
                                    'bank_name'=>$result['bank_name'],
                                    'card_no'=>$result['card_no'],
                                    'cheque_no'=>$result['cheque_no'],
                                    "remarks"=>$result['remarks'],
                                    'net_amount'=>$result['net_amount'],
                                     "field_name"=>$total_values,
                                    //'vat_amount'=>$result['vat'],
                                    'igst_amount'=>$result['igst'],
                                    'cgst_amount'=>$result['cgst'],
                                    'sgst_amount'=>$result['sgst'],
                                    'pay_amount'=>$result['paid_amount'],
                                    'discount_percent'=>$result['discount_percent'],
                                   // 'vat_percent'=>$result['vat_percent'],
                                    'payment_date'=>date('d-m-Y',strtotime($result['payment_date'])),
                                    "country_code"=>"+91",
                                    'referred_by'=>$result['referred_by'],
                                    "relation_type"=>$result_patient['relation_type'],
                                    "relation_name"=>$result_patient['relation_name'],
                                    "relation_simulation_id"=>$result_patient['relation_simulation_id'],
                                    'referral_hospital'=>$result['referral_hospital'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                 $salesid=  $this->sales_return_medicine->save();
                 $this->session->set_userdata('sales_id',$salesid);
                 $this->session->set_flashdata('success','Sales medicine has been successfully updated.');
                 redirect(base_url('sales_return_medicine/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            } 

             
        }
       //print '<pre>'; print_r($data);die;
       
       $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        
        $data['discount_setting'] = $discount_setting_data[1];
        
        $this->load->view('sales_return_medicine/add',$data);  

      }
    }

     public function advance_search()
      {

          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['doctors_list']= $this->general_model->doctors_list();
          $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "patient_name"=>"",
                                      "simulation_id"=>"",
                                      "mobile_no"=>"",
                                      "sale_no"=>"",
                                      "patient_code"=>"",
                                      "medicine_name"=>"",
                                      "medicine_company"=>"",
                                      "medicine_code"=>"",
                                      "purchase_rate"=>"",
                                      "discount"=>"",
                                      "end_date"=>"",
                                      //"vat"=>"",
                                      "igst"=>'',
                                      "sgst"=>'',
                                      "cgst"=>'',
                                      "batch_no"=>"",
                                      "quantity"=>"",
                                      "packing"=>"",
                                      "conversion"=>"",
                                      "paid_amount_to"=>"",
                                      "paid_amount_from"=>"",
                                      "balance_to"=>"",
                                      "mrp_to"=>"",
                                      "mrp_from"=>"",
                                      "balance_from"=>"",
                                      "total_amount_to"=>"",
                                      "total_amount_from"=>"", 
                                      'referred_by'=>'',
                                      'refered_id'=>'',
                                      'referral_hospital'=>'',
                                      "status"=>"", 
                                      "bank_name"=>"",
                                      "return_no"=>"",
                                    );
          if(isset($post) && !empty($post))
          {
            //print_r($post);die;
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('sale_search', $marge_post);
          }
          $purchase_search = $this->session->userdata('sale_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('sales_return_medicine/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('sale_search');
    }
    
   public function payment_calc_all()
    { 
       $medicine_list = $this->session->userdata('medicine_id');
     //print '<pre>'; print_r($medicine_list);
       
       if(!empty($medicine_list))
       {
        $post = $this->input->post();
        $discount_type=$post['discount_type'];
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        //$totigst_amount=0;
        //$totcgst_amount=0;
       // $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0;  
        $purchase_amount=0;
        $newamountwithigst=0;
        $newamountwithcgst=0;
        $newamountwithsgst=0;
        $total_new_amount=0;
        $total_new_other_amount=0;
        $payamount=0;
         $tot_discount_amount=0;
         $i=0;
        foreach($medicine_list as $medicine)
        {    
             $per_pic_amount= $medicine['per_pic_amount'];
            $tot_qty_with_rate= $per_pic_amount*$medicine['qty'];
            
           

             $total_new_other_amount= $tot_qty_with_rate-($tot_qty_with_rate/100)*$medicine['discount'];
         
            $total_new_amount= $total_new_amount+$medicine['total_pricewith_medicine'];
            $totigst_amount = $medicine['igst'];

            $total_amountwithigst= ($total_new_other_amount/100)*$totigst_amount;
          
            $newamountwithigst=$newamountwithigst+ $total_amountwithigst;
           // echo $newamountwithigst;
          /* amount with cgst */

           
            $totcgst_amount = $medicine['cgst'];
            //echo $totcgst_amount;
            //echo $total_new_amount;
            $total_amountwithcgst= ($totcgst_amount/100)*$total_new_other_amount;
            
            //echo $total_amountwithcgst;
            $newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
            /* amount with cgst */


            $totsgst_amount = $medicine['sgst']; 
            $total_amountwithsgst= ($total_new_other_amount/100)*$totsgst_amount; 
            $newamountwithsgst=$newamountwithsgst+ $total_amountwithsgst; 
            $tot_discount_amount+= ($tot_qty_with_rate)/100*$medicine['discount']; 
            $i++;

        } 
          
            if($discount_type==1)
            {
             $total_discount_inr = $post['discount'] ;
             $total_discount = round($total_discount_inr);
            } 
            else
            {
            if($post['discount']!='' && $post['discount']!=0)
            {
             $total_discount_perc = ($post['discount']/100)* $total_new_amount;
             $total_discount = round($total_discount_perc);
            }
             else
             {  
             $total_discount=$tot_discount_amount;
             }
            }
            // if($post['discount']!='' && $post['discount']!=0){
            // $total_discount_perc = ($post['discount']/100)* $total_new_amount;
            // $total_discount = round($total_discount_perc);
            // }
            // else{
            // $total_discount=$tot_discount_amount;

            // }


            $net_amount = ($total_new_amount-$total_discount)+$newamountwithsgst+$newamountwithcgst+$newamountwithigst;

             if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=$net_amount;
            }
         
            $blance_due=$net_amount - $payamount;
             $blance_due = number_format($total_cgst,2,'.','');
          
            $newamountwithsgst = number_format($newamountwithsgst,2,'.','');
            $newamountwithcgst = number_format($newamountwithcgst,2,'.','');
            $newamountwithigst = number_format($newamountwithigst,2,'.','');
        
       

        $pay_arr = array('total_amount'=>number_format($total_new_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'balance_due'=>number_format($blance_due,2,'.',''),'discount'=>$post['discount'],'sgst_amount'=>$newamountwithsgst,'igst_amount'=>$newamountwithigst,'cgst_amount'=> $newamountwithcgst,'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

    public function payment_cal_perrow()
    {
      $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
      
      $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        
        $discount_setting = $discount_setting_data[1];
        
       $post = $this->input->post();
       $total_price_medicine_amount=0;
       //echo "<pre>"; print_r($post); exit;
       //echo $post['manuf_date'];
       if(isset($post) && !empty($post))
       {
            $total_amount = 0;
            $medicine_id_new= explode('.',$post['mbid']);
            $medicine_data = $this->sales_return_medicine->medicine_list($medicine_id_new[0],$medicine_id_new[1]);
            // print_r($medicine_data);die;
            $per_pic_amount= $post['mrp'];
            $tot_qty_with_rate= $per_pic_amount*$post['qty'];
            if($discount_setting==1)
            {
               $total_discount = $post['discount']; 
            }
            else
            {
                $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            }
            
             
             $total_amount= $tot_qty_with_rate-$total_discount;
             
             //GST
            
            $total_gst_tax = $post['cgst']+$post['igst']+$post['sgst'];
            //$totcgstToPay_init = ($total_amount*(100/(100+($total_gst_tax)/100)));
            //$totcgstToPay = ($total_amount -$totcgstToPay_init)*100;
            
             $totcgstToPay = $total_amount-($total_amount/((100+$total_gst_tax)/100));
            
            if($post['cgst']>0)
            {
                $cgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $cgst_cal_val = 0; 
            }
            
            if($post['sgst']>0)
            {
                $sgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $sgst_cal_val = 0; 
            }
            
            if($post['igst']>0)
            {
                $igst_cal_val = $totcgstToPay;
            }
            else
            {
               $igst_cal_val = 0; 
            }
            
           $total_price_medicine_amount = $total_price_medicine_amount+ ($total_amount);
            
            // $cgst_cal_val+$sgst_cal_val+$igst_cal_val
            //GST END
            
            
             $medicine_list =  array('mid'=>$medicine_id_new[0], 'id'=>$medicine_id_new[0],'medicine_name'=>$medicine_data[0]->medicine_name, 'medicine_code'=>$medicine_data[0]->medicine_code, 'packing'=>$medicine_data[0]->packing, 'mrp'=>$post['mrp'], 'qty'=>$post['qty'],'batch_no'=>$medicine_id_new[1],'manuf_date'=>$post['manuf_date'],'exp_date'=>$post['expiry_date'],'sgst'=>$post['sgst'],'bar_code'=>$post['bar_code'],'igst'=>$post['igst'],'hsn_no'=>$post['hsn_no'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'sale_amount'=>$post['mrp'],'per_pic_amount'=>$per_pic_amount,'conversion'=>$post['conversion'],'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount);
            //echo '<pre>'; print_r($medicine_list);die; 
            //$this->session->set_userdata('medicine_id', $medicine_list);
            //$pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($medicine_list,true);
            echo $json;
         
       }
    }


    private function _validate()
    {

        $post = $this->input->post();  
         
        $total_values=array();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        $this->form_validation->set_rules('name', 'Patient Name.', 'trim|required');  
        
        /*if(isset($post['field_name']))
        {
          $count_field_names= count($post['field_name']);
          $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        }  
        }*/
        
         /* 19-03-2020*/

        /*$field_list = mandatory_section_field_list(14);   
        $users_data = $this->session->userdata('auth_users'); 
        
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  

         if(!empty($field_list)){ 
            if($field_list[2]['mandatory_field_id']=='61' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }

            if($field_list[1]['mandatory_field_id']=='62' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('name', 'Patient Name.', 'trim|required'); 
            }

             if($field_list[0]['mandatory_field_id']=='63' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){    
               
               if($post['referred_by']=='0')
                {
                  $this->form_validation->set_rules('refered_id', 'referred by', 'trim|required');
                }
                else
                {
                  $this->form_validation->set_rules('referral_hospital', 'referred by hospital', 'trim|required');  
                }
            }
          }*/
         
        if ($this->form_validation->run() == FALSE) 
        {  
            //echo "<pre>"; print_r(validation_errors()); die;
            $sale_no = generate_unique_id(17); 
            $patient_code = generate_unique_id(4); 
           
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'],
									"patient_id"=>$_POST['patient_id'], 
                                    'patient_code'=>$patient_code,
                                    "sales_no"=>$_POST['sales_no'],
                                    "return_no"=>$sale_no,
                                    "name"=>$_POST['name'],
                                    "gender"=>$post['gender'],
                                    "patient_reg_code"=>$post['patient_reg_code'],
                                    'refered_id'=>$_POST['refered_id'],
                                    'simulation_id'=>$_POST['simulation_id'],
                                    "mobile"=>$_POST['mobile'],
                                    'total_amount'=>$post['total_amount'],
                                    "sales_date"=>$post['sales_date'],
                                    'discount_amount'=>$post['discount_amount'],
                                    'payment_mode'=>$post['payment_mode'],
                                    //'bank_name'=>$bank_name,
                                    "remarks"=>$post['remarks'],
                                   // 'card_no'=>$card_no,
                                    //'cheque_no'=>$cheque_no,
                                   // 'payment_date'=>$payment_date,
                                    'net_amount'=>$post['net_amount'],
                                    'cgst_amount'=>$post['cgst_amount'],
                                    'sgst_amount'=>$post['sgst_amount'],
                                    'igst_amount'=>$post['igst_amount'],
                                     "field_name"=>$total_values,
                                    //'vat_amount'=>$post['vat_amount'],
                                    'pay_amount'=>$post['pay_amount'],
                                    'discount_percent'=>$_POST['discount_percent'],
                                   // 'vat_percent'=>$_POST['vat_percent'],
                                   // 'transaction_no'=>$transaction_no,
                                    "country_code"=>"+91",
                                    "relation_type"=>$_POST['relation_type'],
                                    "relation_name"=>$_POST['relation_name'],
                                    "relation_simulation_id"=>$_POST['relation_simulation_id'],
                                    'referred_by'=>$post['referred_by'],
                                    'referral_hospital'=>$post['referral_hospital'],
                                   );  
            return $data['form_data'];
        }


    }
 
    public function delete($id="")
    {
        unauthorise_permission(61,410);
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_medicine->delete($id);
           $response = "Sales return medicine successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(61,410);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_return_medicine->deleteall($post['row_id']);
            $response = "Sales return medicine successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        //unauthorise_permission(61,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
        $this->load->view('sales_return_medicine/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(61,411);
        $data['page_title'] = 'Sales return medicine archive list';
        $this->load->helper('url');
        $this->load->view('sales_return_medicine/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(61,411);
        $this->load->model('sales_return_medicine/sales_return_medicine_archive_model','sales_return_medicine_archive'); 

        $list = $this->sales_return_medicine_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $sales_return) { 
            $no++;
            $row = array();
          
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                  

           $row[] = '<input type="checkbox" name="sales_return[]" class="checklist" value="'.$sales_return->id.'">'.$check_script;  
            $row[] = $sales_return->sale_no;
             $row[] = $sales_return->return_no;
            $row[] = $sales_return->patient_name;
            $row[] = $sales_return->doctor_hospital_name;
           // $row[] = $sales_return->total_amount;
            $row[] = $sales_return->net_amount;
            $row[] = $sales_return->paid_amount;
            $row[] = $sales_return->balance;
            $row[] = date('d-M-Y',strtotime($sales_return->sale_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('413',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_sales_return_medicine('.$sales_return->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('412',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$sales_return->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_return_medicine_archive->count_all(),
                        "recordsFiltered" => $this->sales_return_medicine_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(61,413);
        $this->load->model('sales_return_medicine/sales_return_medicine_archive_model','sales_return_medicine_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_medicine_archive->restore($id);
           $response = "Sales return medicine  successfully restore in  sales medicine list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(61,413);
        $this->load->model('sales_return_medicine/sales_return_medicine_archive_model','sales_return_medicine_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_return_medicine_archive->restoreall($post['row_id']);
            $response = "Sales return medicine successfully restore in sales medicine list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(61,412);
        $this->load->model('sales_return_medicine/sales_return_medicine_archive_model','sales_return_medicine_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_medicine_archive->trash($id);
           $response = "Sales return medicine  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(61,412);
        $this->load->model('sales_return_medicine/sales_return_medicine_archive_model','sales_return_medicine_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_return_medicine_archive->trashall($post['row_id']);
            $response = "Sales return medicine successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

   public function sales_return_medicine_dropdown()
  {
      $doctor_list = $this->sales_return_medicine->doctor_list();
      $dropdown = '<option value="">Select refered by</option>'; 
      if(!empty($doctor_list))
      {
        foreach($doctor_list as $doctors)
        {
           $dropdown .= '<option value="'.$doctors->id.'">'.$doctors->doctor_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

 public function print_sales_report($ids=""){
      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Add sales medicine";
      $this->load->model('general/general_model');
      if(!empty($ids)){
      $sales_id= $ids;
      }else{
      $sales_id= $this->session->userdata('sales_id');
      }
      $get_detail_by_id= $this->sales_return_medicine->get_by_id($sales_id);
      $get_by_id_data=$this->sales_return_medicine->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
       $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->sales_return_medicine->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>2,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['payment_mode']= $get_payment_detail;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $this->load->view('sales_return_medicine/print_template_medicine',$data);
  }

  public function get_payment_mode_data()
  {
    $this->load->model('general/general_model'); 
    $payment_mode_id= $this->input->post('payment_mode_id');
    $error_field= $this->input->post('error_field');
    
    $get_payment_detail= $this->general_model->payment_mode_detail($payment_mode_id); 
    $html='';
    $var_form_error='';
    foreach($get_payment_detail as $payment_detail)
    {

      if(!empty($error_field))
        {
         
         $var_form_error= $error_field; 
        }
       $html.='<div class="purchase_medicine_mod_of_payment"><label>'.$payment_detail->field_name.'<span class="star">*</span></label><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="f_right">'.$var_form_error.'</div></div>';
    }
    echo $html;exit;
    
  }
  
   public function search_sales($vals="")
   {
        if(!empty($vals))
        {
            $result = $this->sales_return_medicine->search_sales($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    public function get_sales_medicine()
    {
       
       $post =  $this->input->post();
       $post_mid_arr = [];
       if(isset($post['sales_id']) && !empty($post['sales_id']))
       {
         $purchase = $this->session->userdata('medicine_id');
         $medicine_id = [];
         $mid_arr = [];

          
         if(!empty($post['sales_id']))
         {
            $this->load->model('sales_medicine/sales_medicine_model','sales_medicine');
            $total_amount=0;

            $result = $this->sales_medicine->get_by_id($post['sales_id']); 
            
            $result_medince_list = $this->sales_medicine->get_medicine_by_sales_id($post['sales_id']); 
            //echo "<pre>"; print_r($result_medince_list); exit; 
            $total_price_medicine_amount=0;
            foreach($result_medince_list as $medicines)
            {
              $per_pic_amount= $medicines['sale_amount'];
              $tot_qty_with_rate= $per_pic_amount*1;
              
                $total_discount = ($medicines['discount']/100)*$tot_qty_with_rate;
                $total_amount= $tot_qty_with_rate-$total_discount;
                $cgstToPay = ($total_amount / 100) * $medicines['cgst'];
                $igstToPay = ($total_amount / 100) * $medicines['igst'];
                $sgstToPay = ($total_amount / 100) * $medicines['sgst'];
                //echo $total_tax;die;
                $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
                 
                if(strtotime($medicines['manuf_date'])>316000)
                {
                           $manuf_date=date('d-m-Y',strtotime($medicines['manuf_date']));
                }
                else
                {
                           $manuf_date='';
                } 
        
                if(strtotime($medicines['exp_date'])>315000)
                {
                           $exp_date=date('d-m-Y',strtotime($medicines['exp_date']));
                }
                else
                {
                           $exp_date='';
                }
                
                $id_batch = $medicines['mid'].'.'.$medicines['batch_no'];
               $post_mid_arr[$medicines['mid'].'.'.$medicines['batch_no']] = array('id'=>$medicines['mid'], 'mid'=>$medicines['mid'],'mbid'=>$medicines['mid'], 'm_id'=>$id_batch, 'medicine_name'=>$medicines['medicine_name'], 'medicine_code'=>$medicines['medicine_code'], 'mrp'=>$medicines['per_pic_amount'], 'packing'=>$medicines['packing'], 'batch_no'=>$medicines['batch_no'], 'qty'=>$medicines['qty'], 'expiry_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicines['discount'],'bar_code'=>$medicines['bar_code'],'conversion'=>$medicines['conversion'],'hsn_no'=>$medicines['hsn_no'],'cgst'=>$medicines['cgst'],'sgst'=>$medicines['sgst'],'igst'=>$medicines['igst'], 'per_pic_amount'=>$medicines['per_pic_amount'],'sale_amount'=>$medicines['sale_amount'],'total_amount'=>$medicines['total_amount'],'total_pricewith_medicine'=>$total_price_medicine_amount); 



                
            }
            
            //echo "<pre>"; print_r($post_mid_arr); exit;
              if(isset($purchase) && !empty($purchase))
              { 
                $medicine_id = $purchase+$post_mid_arr;
              } 
              else
              {
                $medicine_id = $post_mid_arr;
              }
         }
          //echo "<pre>"; print_r($medicine_id); exit;
          $this->session->set_userdata('medicine_id',$medicine_id);
          $this->ajax_added_medicine();
       }
    }
    
    
    public function print_barcode($id)
    {
        $patient_data = $this->sales_return_medicine->get_by_id($id); 
        $data['barcode_id'] = $patient_data['return_no'];
        if(!empty($data['barcode_id']))
        {
            $this->load->view('patient/barcode',$data);
        }
    }
    
    public function set_new_medicine()
    {
        
        
        $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        
        $discount_setting = $discount_setting_data[1];
        
       $post =  $this->input->post(); 
       $post_mid_arr = [];
       $m_new_array_id=[];
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
         $medicine_id = [];
         $mid_arr = [];
         $total_price_medicine_amount=0;
         $m_ids = $post['medicine_id'][0];
         $m_id_arr = explode('.',$post['medicine_id'][0]);
         $vat='';
        $medicine_data = $this->sales_return_medicine->medicine_list($m_id_arr[0],$m_id_arr[1]);
         // print_r($medicine_data);
        $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
        $tot_qty_with_rate= $per_pic_amount*1;
        
        if($discount_setting==1)
        {
           $total_discount = $medicine_data[0]->discount; 
        }
        else
        {
            $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
        }
        
        
        $total_amount= $tot_qty_with_rate-$total_discount;
        //gst
        $total_tax = $medicine_data[0]->cgst+$medicine_data[0]->igst+$medicine_data[0]->sgst;
            //$totcgstToPay_init = ($total_amount*(100/(100+($total_tax)/100)));
            //$totcgstToPay = ($total_amount -$totcgstToPay_init)*100;
            $totcgstToPay = $total_amount-($total_amount/((100+$total_tax)/100));
            
            if($common_cgst>0)
            {
                $cgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $cgst_cal_val = 0; 
            }
            
            if($common_sgst>0)
            {
                $sgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $sgst_cal_val = 0; 
            }
            
            if($common_igst>0)
            {
                $igst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $igst_cal_val = 0; 
            }
            
           $total_price_medicine_amount = $total_price_medicine_amount+$total_amount;
           $total_tax= $cgst_cal_val+$sgst_cal_val+$igst_cal_val;
        
        
        //end of new gst
        
        
        
        //$cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
        //$igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
        //$sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
        //echo $total_tax;die;
        //$total_tax= $cgstToPay+$igstToPay+$sgstToPay;
        $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount; //$total_tax
        if($medicine_data[0]->expiry_date=="00-00-0000")
        {
        $exp_date='';
        }else{
        $exp_date=$medicine_data[0]->expiry_date;
        }
        if($medicine_data[0]->manuf_date=="00-00-0000"){
        
        $date_newmanuf='';
        }else{
        $date_newmanuf=$medicine_data[0]->manuf_date;
        }
        
        $varids="'".$medicine_data[0]->mid.$medicine_data[0]->batch_no."'";

        $value="'".$medicine_data[0]->mid.".".$medicine_data[0]->batch_no."'";
        
        $row_varids=$medicine_data[0]->mid.$medicine_data[0]->batch_no;
        
        $row_batch_med=$medicine_data[0]->mid.'.'.$medicine_data[0]->batch_no;

        //$value="'".$medicine_data[0]->mid.".".$medicine_data[0]->batch_no."'";
        $batch_no = $m_id_arr[1];
        $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine_data[0]->mid.$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine_data[0]->mid.$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           $('#discount_".$medicine_data[0]->mid.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$medicine_data[0]->mid.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine_data[0]->mid.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine_data[0]->mid.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                          
          if($medicine_data[0]->discount>0)
          {
              $med_discount = $medicine_data[0]->discount;
          }
          else
          {
              $med_discount = '0';
          }
                        
        $purchase = array('mid'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1], 'medicine_code'=>$medicine_data[0]->medicine_code, 'medicine_name'=>$medicine_data[0]->medicine_name, 'packing'=>$medicine_data[0]->packing, 'mrp'=>$medicine_data[0]->mrp, 'qty'=>'0', 'exp_date'=>$exp_date,'manuf_date'=>$date_newmanuf,'discount'=>$med_discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conv,'hsn_no'=>$medicine_data[0]->hsn,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount,'varids'=>$varids,'vals'=>$value,'row_varids'=>$row_varids,'check_scrip'=>$check_script,'manuf_script'=>$check_script1,'row_batch_med'=>$row_batch_med); 

       }
       $output=array('data'=>$purchase);
       echo json_encode($output);
       //return $purchase;
    
    }


}
