<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_return extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('purchase_return/purchase_return_model','purchase_return');
    $this->load->library('form_validation');
    }

    
  public function index()
  {
      unauthorise_permission(59,392);
      $this->session->unset_userdata('purchase_search');
      $this->session->unset_userdata('medicine_id');  
      $this->session->unset_userdata('net_values_all');
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
      $data['page_title'] = 'Purchase Return List'; 
      $this->load->view('purchase_return/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(59,392);
        $list = $this->purchase_return->get_datatables(); 
        $assoc_array = json_decode(json_encode($list),TRUE);
        $session_data= $this->session->userdata('auth_users');
        $total_net_amount = array_sum(array_column($assoc_array,'net_amount'));
        $total_discount = array_sum(array_column($assoc_array,'discount_percent'));
        $total_balance= array_sum(array_column($assoc_array,'balance'));
        $total_vat= array_sum(array_column($assoc_array,'vat'));
        $total_paid_amount= array_sum(array_column($assoc_array,'paid_amount'));
        $session_new_datas=array('net_amount'=>$total_net_amount,'discount'=>$total_discount,'balance'=>$total_balance,'paid_amount'=>$total_paid_amount);
        $this->session->set_userdata('net_values_all',$session_new_datas);

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $purchase_return) { 
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
            $row[] = '<input type="checkbox" name="purchase_return[]" class="checklist" value="'.$purchase_return->id.'">'.$check_script;  
            $row[] = strlen($purchase_return->purchase_id) > 12 ? substr($purchase_return->purchase_id, 0, 12) . '...' : $purchase_return->purchase_id;
            $row[] = $purchase_return->return_no;
            //$row[] = $purchase_return->invoice_id;
            $row[] = strlen($purchase_return->invoice_id) > 12 ? substr($purchase_return->invoice_id, 0, 12) . '...' : $purchase_return->invoice_id;
             $row[] = $purchase_return->vendor_name;
           
           // $row[] = $purchase_return->total_amount;
            $row[] = $purchase_return->net_amount;
            $row[] = $purchase_return->paid_amount;
            $row[] = $purchase_return->balance;
            $row[] = date('d-M-Y',strtotime($purchase_return->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
             if($session_data['parent_id']==$purchase_return->branch_id){
           if(in_array('394',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_purchase_return('.$purchase_return->id.');" class="btn-custom" href="javascript:void(0)" style="'.$purchase_return->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            //if(in_array('125',$users_data['permission']['action'])){
                /*$btnview=' <a class="btn-custom" onclick="return view_purchase('.$purchase->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';*/
           // }
            if(in_array('395',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_purchase_return('.$purchase_return->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            } 
          }
            //$btnprint = '<a class="btn-custom" onclick="openPrintWindow(123,'.$purchase_return->id.');" target="_blank"><i class="fa fa-print"></i> Print</a>'; 

            $print_url = "'".base_url('purchase_return/print_purchase_return_recipt/'.$purchase_return->id)."'";
          $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
          
          $print_barcode_url = "'".base_url('purchase_return/print_barcode/'.$purchase_return->id)."'";
            $btn_barcode = ' <a class="btn-custom"  href="javascript:void(0)" onClick="return print_window_page('.$print_barcode_url.')"  title="Print Barcode" ><i class="fa fa-barcode"></i> Print Barcode </a>';

            $row[] = $btnedit.$btnview.$btndelete.$btnprint.$btn_barcode;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->purchase_return->count_all(),
                        "recordsFiltered" => $this->purchase_return->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

public function medicine_purchase_excel()
    {
       /* $list = $this->purchase_return->search_report_data();
        // print_r( $list);die;
        $columnHeader = '';  
        $columnHeader = "Purchase No." . "\t" . "Invoice No" . "\t" . "Vendor Name" . "\t" . "Net Amount" . "\t" . "Paid Amount" . "\t" . "Balance". "\t" . "Created Date";
        $setData = '';
        if(!empty($list))
        {
          //print '<pre>';print_r($list);die;
           
            $rowData = "";
            foreach($list as $reports)
            {
                //print '<pre>';print_r($reports);
               
                $rowData = $reports->purchase_id . "\t" . $reports->invoice_id. "\t" . $reports->vendor_name . "\t" . $reports->net_amount . "\t" . $reports->paid_amount . "\t". $reports->balance. "\t". date('d-M-Y H:i A',strtotime($reports->created_date)); 
                $setData .= trim($rowData) . "\n";    
            }
        }
        //echo $setData;die;
        header("Content-type: application/octet-stream");  
        header("Content-Disposition: attachment; filename=medicine_purchase_return_report_".time().".xls");  
        header("Pragma: no-cache");  
        header("Expires: 0");  

        echo ucwords($columnHeader) . "\n" . $setData . "\n"; */

          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Purchase No.','Return No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('H')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $col = 0;
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
               $col++;
          }
          $list = $this->purchase_return->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->return_no,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
          header("Content-Disposition: attachment; filename=medicine_purchase_return_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }

    public function medicine_purchase_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Purchase No.','Return No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->purchase_return->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->return_no,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance,date('d-M-Y H:i A',strtotime($reports->created_date)));
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
         header("Content-Disposition: attachment; filename=medicine_purchase_return_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
           ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_medicine_purchase()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->purchase_return->search_report_data();
        $this->load->view('purchase_return/medicine_purchase_return_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_purchase_return_report_".time().".pdf");
    }
    public function print_medicine_purchase()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->purchase_return->search_report_data();
      $this->load->view('purchase_return/medicine_purchase_return_report_html',$data); 
    }
    public function ajax_list_medicine(){
       $medicine_list = $this->session->userdata('medicine_id');

       $ids=array();
        $post = $this->input->post(); 
        if(!empty($medicine_list))
        { 
          $ids_arr= [];
          foreach($medicine_list as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $m_arr['mid'];
             $batch_arr[] = $m_arr['batch_no'];
          }
          $medicine_ids = implode(',', $ids_arr); 
          $batch_nos = implode(',', $batch_arr); 
          $data['medicne_new_list'] = $this->purchase_return->medicine_list($ids_arr,$batch_arr);
           foreach($data['medicne_new_list'] as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }

      $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
      $keywords= $this->input->post('search_keyword');
      $name= $this->input->post('name');
      $table='';
       if(!empty($post['medicine_name']) ||!empty($post['batch_number']) ||!empty($post['bar_code'])||!empty($post['medicine_company']) || !empty($post['medicine_code']) || !empty($post['conv']) || !empty($post['hsn_no']) || !empty($post['unit1']) ||  !empty($post['unit2']) || !empty($post['mrp']) || !empty($post['p_rate']) || !empty($post['purchase_quantity']) || !empty($post['stock_quantity']) || !empty($post['packing']) ||!empty($post['discount']) ||!empty($post['hsn_no']) ||!empty($post['igst'])||!empty($post['cgst'])||!empty($post['sgst']))
      {  
        $result_medicine = $this->purchase_return->medicine_list_search();  
      } 

         
          if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){
            foreach($result_medicine as $medicine){
              $qty_data = $this->purchase_return->get_batch_med_qty($medicine->id,$medicine->batch_no);
                //if(!in_array($medicine->id,$ids)){
                $table.='<tr class="append_row">';
                if($qty_data['total_qty']>0)
                  {
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$medicine->id.'.'.$medicine->batch_no.'" onclick="add_check();"></td>';
                 

                $table.='<td>'.$medicine->medicine_name.'</td>';
                $table.='<td>'.$medicine->medicine_code.'</td>';
                $table.='<td>'.$medicine->hsn_no.'</td>';
                $table.='<td>'.$medicine->company_name.'</td>';
                $table.='<td>'.$medicine->packing.'</td>';
                $table.='<td>'.$medicine->batch_no.'</td>';
                $table.='<td>'.$medicine->bar_code.'</td>';
                $table.='<td>'.$medicine->conversion.'</td>';
                $table.='<td>'.$medicine->medicine_unit.'</td>';
                $table.='<td>'.$medicine->medicine_unit_2.'</td>';
                $table.='<td>'.$medicine->mrp.'</td>';
                $table.='<td>'.$medicine->purchase_rate.'</td>';
                $table.='<td>'.$medicine->discount.'</td>';
                $table.='<td>'.$medicine->cgst.'</td>';
                $table.='<td>'.$medicine->sgst.'</td>';
                $table.='<td>'.$medicine->igst.'</td>';
                //$table.='<td>'.$medicine->stock_quantity.'</td>';
                $table.='</tr>';
                   }
               // }
            }
          }
        else
        {
             $table='<tr class="append_row"><td colspan="15" align="center" class="text-danger">No record found</td></tr>';
        }
               $output=array('data'=>$table);
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

    public function ajax_added_medicine(){

         $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
         $medicine_sess = $this->session->userdata('medicine_id');
         //print_r($medicine_sess);die;
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
          //$medicine_ids = implode(',', $ids_arr); 
         // $batch_nos = implode(',', $batch_arr); 
          //print_r($medicine_sess);die;
            
           // print_r();
          $result_medicine = $this->purchase_return->medicine_list($ids_arr,$batch_arr);
        // print_r($medicine_ids);
         //print_r($batch_nos);

         //die;
          //print '<pre>'; print_r($result_medicine);die;
           foreach($result_medicine as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }
        //$setting_data= get_setting_value('MEDICINE_VAT');
     
        $table='<div class="box_scroll">';
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
                        $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>Barcode</th>';
                        $table.='<th>Unit1</th>';
                        $table.='<th>Unit2</th>';
                         $table.='<th>Free Unit1</th>';
                        $table.='<th>Free Unit2</th>';
                       /* $table.='<th>Free</th>';*/
                        $table.='<th>MRP</th>';
                        $table.='<th>Purchase Rate</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST</th>';
                        $table.='<th>SGST</th>';
                        $table.='<th>IGST</th>';
                       // $table.='<th>Quantity</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){
                        foreach($result_medicine as $medicine){
                            if($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["exp_date"]=="00-00-0000"){

                                $date_new='';
                            }else{
                                $date_new=$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["exp_date"];
                            }
                            if(strtotime($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"]) < 31536000){

                                $date_newma='';
                            }else{
                                $date_newma=$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"];
                            }
                            
                            $varids="'".$medicine->id.$medicine->batch_no."'";
                            
                            $value="'".$medicine->id.".".$medicine->batch_no."'";

                        $check_script = "<script>
                        var today = new Date();
                        $('#expiry_date_".$medicine->id.$medicine->batch_no."').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                         startDate: '".$date_new."',

                        });</script>";
                        $check_script1= "<script>
                          var today = new Date();
                        $('#manuf_date_".$medicine->id.$medicine->batch_no."').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                        endDate: '".$date_newma."',
                        });
                        
                         $('#discount_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });

                           $('#sgst_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                        </script>";
                          //if(!in_array($medicine->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine->id.$medicine->batch_no.'" name="m_id[]" value="'.$medicine->id.'.'.$medicine->batch_no.'"/>
                        <input type="hidden" id="mbid_'.$medicine->id.$medicine->batch_no.'" name="mbid[]" value='.$value.'/>
                    <input type="hidden" value="'.$medicine->conversion.'"  name="conversion[]" id="conversion_'.$medicine->id.$medicine->batch_no.'" />';
                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$medicine->medicine_name.'</td>';
                        $table.='<td>'.$medicine->medicine_code.'</td>';
                        $table.='<td><input type="text"  name="hsn_no[]"  value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["hsn_no"].'"  placeholder="HSN No." style="width:84px;" id="hsn_no_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine->packing.'</td>';
                         $table.='<td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["batch_no"].'" name="batch_no[]" class="" placeholder="Batch Number" style="width:84px;" id="batch_no_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');" readonly/></td>';
                        $table.='<td>'.$medicine->conversion.'</td>';
                        
                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';
                         
                         $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                         $table.='<td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/></td>';
                        /*$table.='<td>'.$medi
                        cine->created_date.'</td>';
                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.'" onchange="payment_cal_perrow('.$medicine->id.');"/>'.$check_script.'</td>';*/

                        $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["unit1"].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(unit1,'.$varids.','.$medicine->id.');"/><div class="error" style="color:red;" id="unit1_error_'.$medicine->id.$medicine->batch_no.'"></div></td>';
                        $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["unit2"].'" onkeyup="payment_cal_perrow('.$varids.');"/><p id="unit1_error_'.$medicine->id.$medicine->batch_no.'"></p></td>';
                          
                          $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["freeunit1"].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(freeunit1,'.$varids.','.$medicine->id.');"/><div class="error" style="color:red;" id="freeunit1_error_'.$medicine->id.$medicine->batch_no.'"></div></td>';

                        $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["freeunit2"].'" onkeyup="payment_cal_perrow('.$varids.');"/><p id="freeunit2_error_'.$medicine->id.$medicine->batch_no.'"></p></td>';
                       /* $table.='<td></td>';*/
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$medicine->id.$medicine->batch_no.'"  class="w-60px" name="mrp[]" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["mrp"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" id="purchase_rate_'.$medicine->id.$medicine->batch_no.'" name="purchase_rate[]" class="w-80px" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["purchase_amount"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" name="discount[]" class="price_float w-80px" placeholder="Discount" style="width:59px;" id="discount_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["discount"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["cgst"].'" id="cgst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["sgst"].'" id="sgst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["igst"].'" id="igst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                       // $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine->id.'" value="'.$medicine_sess[$medicine->id]["qty"].'" onkeyup="payment_cal_perrow('.$medicine->id.');"/></td>';
                      
                        $table.=' <td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$medicine->id.$medicine->batch_no.'" /></td>';
                       
                        $table.='</tr>';
                          // }
                       }
                       }else{
                        $table.='<td colspan="20" align="center" class="text-danger">No record found</td>';
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
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
        //print_r($post['medicine_id']);
         $purchase = $this->session->userdata('medicine_id');
         $medicine_id = [];
         $mid_arr = [];

         if(isset($purchase) && !empty($purchase))
         { 
           $total_amount=0;
            $post_mid_arr = [];
            foreach($post['medicine_id'] as $m_ids)
            {
                $m_id_arr = explode('.',$m_ids);
                //print_r($m_id_arr[0]);
               // $vat='';
                $medicine_data = $this->purchase_return->medicine_list($m_id_arr[0],$m_id_arr[1]);
                //print_r($medicine_data);die;
             
                /*$medicine_data = $this->purchase_return->medicine_list($m_ids);
                $tot_qty_with_rate= $medicine_data[0]->purchase_rate*1;
                $vatToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->vat;
                $totalPrice = $tot_qty_with_rate + $vatToPay;
                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;

                $post_mid_arr[$m_ids] = array('mid'=>$m_ids, 'qty'=>'1', 'exp_date'=>'00-00-0000','discount'=>$medicine_data[0]->discount,'vat'=>$medicine_data[0]->vat, 'purchase_amount'=>$medicine_data[0]->purchase_rate, 'total_amount'=>$total_amount); */


               // $medicine_data = $this->purchase_return->medicine_list($m_ids);
                $ratewithunit1= $medicine_data[0]->purchase_rate*$medicine_data[0]->unit1;
                //$ratewithunit1= $medicine_data[0]->purchase_rate*$post['unit1'];
                $perpic_rate= $medicine_data[0]->purchase_rate/$medicine_data[0]->conversion;
                $ratewithunit2=$perpic_rate*$medicine_data[0]->unit2;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                $qty= ($medicine_data[0]->unit1*$medicine_data[0]->conversion)+$medicine_data[0]->unit2;
                $freeqty= ($medicine_data[0]->freeunit1*$medicine_data[0]->conversion)+$medicine_data[0]->freeunit2;

               /* $cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;

                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/
                if(!empty($medicine_data[0]->discount))
                {
                   $discount = $medicine_data[0]->discount;
                }
                else
                {
                     $discount ='0.00';
                }
                 $total_discount = ($discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $medicine_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;


                 

                $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0],'batch_no'=>$m_id_arr[1],'freeunit1'=>$medicine_data[0]->freeunit1,'bar_code'=>$medicine_data[0]->bar_code,'freeunit2'=>$medicine_data[0]->freeunit2,'unit1'=>$medicine_data[0]->unit1,'unit2'=>$medicine_data[0]->unit2,'conversion'=>$medicine_data[0]->conversion,'perpic_rate'=>$perpic_rate,'mrp'=>$medicine_data[0]->mrp,'hsn_no'=>$medicine_data[0]->hsn,'manuf_date'=>date('d-m-Y',strtotime($medicine_data[0]->manuf_date)),'qty'=>$qty,'freeqty'=>$freeqty,'exp_date'=>date('d-m-Y',strtotime($medicine_data[0]->expiry_date)),'discount'=>$discount,'cgst'=>$medicine_data[0]->cgst,'igst'=>$medicine_data[0]->igst,'sgst'=>$medicine_data[0]->sgst, 'purchase_amount'=>$medicine_data[0]->p_rate, 'total_amount'=>$total_amount);
                //print_r($post_mid_arr);die;

             } 
            //print_r($post_mid_arr);die;
            $medicine_id = $purchase+$post_mid_arr;
            
         } 
         else
         {
           $total_amount=0;
            foreach($post['medicine_id'] as $m_ids)
            {


               
               /* $medicine_data = $this->purchase_return->medicine_list($m_ids);
                $tot_qty_with_rate= $medicine_data[0]->purchase_rate*1;
                $vatToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->vat;
                $totalPrice = $tot_qty_with_rate + $vatToPay;
                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;

                $medicine_data = $this->purchase_return->medicine_list($m_ids);
                $post_mid_arr[$m_ids] = array('mid'=>$m_ids, 'qty'=>'1', 'exp_date'=>'00-00-0000', 'purchase_amount'=>$medicine_data[0]->purchase_rate,'discount'=>$medicine_data[0]->discount,'vat'=>$medicine_data[0]->vat,'total_amount'=>$total_amount); */

                 $m_id_arr = explode('.',$m_ids);
                 
                 $medicine_data = $this->purchase_return->medicine_list($m_id_arr[0],$m_id_arr[1]);
 //print_r($medicine_data);die;
                $ratewithunit1= $medicine_data[0]->purchase_rate*$medicine_data[0]->unit1;
                //$ratewithunit1= $medicine_data[0]->purchase_rate*$post['unit1'];
                $perpic_rate= $medicine_data[0]->purchase_rate/$medicine_data[0]->conversion;
                $ratewithunit2=$perpic_rate*$medicine_data[0]->unit2;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                $qty= ($medicine_data[0]->unit1*$medicine_data[0]->conversion)+$medicine_data[0]->unit2;

                 $freeqty= ($medicine_data[0]->freeunit1*$medicine_data[0]->conversion)+$medicine_data[0]->freeunit2;
                  $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                  

                  /*$cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                  $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                  $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                  $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                  $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                  $total_amount= $totalPrice-$total_discount;*/
                    if(!empty($medicine_data[0]->discount))
                    {
                       $discount = $medicine_data[0]->discount;
                    }
                    else
                    {
                         $discount ='0.00';
                    }
                    if($discount>0)
                    {
                       $total_discount = ($discount/100)*$tot_qty_with_rate; 
                    }
                    else
                    {
                        $total_discount = '0.00';
                    }
                  
                  $tot_price=$tot_qty_with_rate-$total_discount;

                  $cgstToPay = ($tot_price / 100) * $medicine_data[0]->cgst;
                  $igstToPay = ($tot_price / 100) * $medicine_data[0]->igst;
                  $sgstToPay = ($tot_price / 100) * $medicine_data[0]->sgst;
                  $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;
                     
                  //$medicine_data = $this->purchase->medicine_list($m_ids);
                  $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1],'unit1'=>$medicine_data[0]->unit1,'bar_code'=>$medicine_data[0]->bar_code,'unit2'=>$medicine_data[0]->unit2,'freeunit1'=>$medicine_data[0]->freeunit1,'freeunit2'=>$medicine_data[0]->freeunit2,'hsn_no'=>$medicine_data[0]->hsn,'conversion'=>$medicine_data[0]->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine_data[0]->manuf_date)),'mrp'=>$medicine_data[0]->mrp,'perpic_rate'=>$perpic_rate, 'qty'=>$qty,'freeqty'=>$freeqty,'exp_date'=>date('d-m-Y',strtotime($medicine_data[0]->expiry_date)), 'purchase_amount'=>$medicine_data[0]->purchase_rate,'discount'=>$discount,'cgst'=>$medicine_data[0]->cgst,'igst'=>$medicine_data[0]->igst,'purchase_amount'=>$medicine_data[0]->p_rate,'sgst'=>$medicine_data[0]->sgst,'total_amount'=>$total_amount); 



               // $mid_arr[] = $m_ids;
            }
            $medicine_id = $post_mid_arr;
         } 
         //$medicine_ids = implode(',',$mid_arr);
         $this->session->set_userdata('medicine_id',$medicine_id);
         //print_r($this->session->userdata('medicine_id'));
        // print_r();
         $this->ajax_added_medicine();
       }
    }


public function check_stock_avability(){
    $id= $this->input->post('id');
    $batch_no= $this->input->post('batch_no');
    $conversion= $this->input->post('conversion');
    $unit2= $this->input->post('unit2');
    $unit1= $this->input->post('unit1');
    if(!empty($batch_no)){
    $return= $this->purchase_return->check_stock_avability($id,$batch_no);
     //echo $return;
    $tot_val= $unit1*$conversion+ $unit2;
    if($return >= $tot_val){
           echo '0'; 
      }else{
        echo '1';
        
      }
    }
}
public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "vendor_code"=>"", 
                                      "vendor_name"=>"",
                                      "simulation_id"=>"",
                                      "mobile_no"=>"",
                                      "invoice_id"=>"",
                                      "purchase_no"=>"",
                                      "medicine_name"=>"",
                                      "medicine_company"=>"",
                                      "medicine_code"=>"",
                                      "purchase_rate"=>"",
                                      "discount"=>"",
                                      "end_date"=>"",
                                      "cgst"=>"",
                                      "igst"=>"",
                                      "sgst"=>"",
                                      "batch_no"=>"",
                                      "unit1"=>"",
                                      "unit2"=>"",
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
                                      "status"=>"", 
                                      "bank_name"=>"",
                                      "branch_id"=>""
                                    );
          if(isset($post) && !empty($post))
          {
               $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('purchase_search', $marge_post);
          }
          $purchase_search = $this->session->userdata('purchase_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('purchase_return/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('purchase_search');
    }
     public function remove_medicine_list()
    {

      $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post =  $this->input->post();
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
           $ids_list = $this->session->userdata('medicine_id');
           
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
            $medicine_listdata = $this->purchase_return->medicine_list();
           }*/
           $this->ajax_added_medicine();
       }
    } 

  public function add($pid="")
  {
        //print_r($_POST);die;
        unauthorise_permission(59,393);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Add Purchase Medicines Return";
        $data['form_error'] = [];
        $post = $this->input->post();
        $vendor_id='';
        $purchase_no = "";
        $vendor_code = "";
        $name = "";
        $patient_name = "";
        $mobile_no = "";
        $email = "";
        $address = "";
        
        $address2 ='';
        $address3 = '';
        $vendor_gst = "";
        $vendor_gst = "";
        $igst_amount="";
        $cgst_amount="";
        $sgst_amount="";
        $total_amount="0.00";
        $discount_amount="0.00";
        $pay_amount="" ;       
        if(!empty($_GET['puid']))
        {
            $puid = $_GET['puid'];
        }
        
       if($pid>0)
        {
           $vendor = $this->purchase_return->get_vendor_by_id($pid);
           //print_r($purchase);
           if(!empty($vendor))
           {
              $vendor_id = $vendor['id'];
              $vendor_code = $vendor['vendor_id'];
              $name = $vendor['name'];
              $mobile_no = $vendor['mobile'];
              $address = $vendor['address'];
              $address2 = $vendor['address2'];
              $address3 = $vendor['address3'];
              $email = $vendor['email'];
              $vendor_gst = $vendor['vendor_gst'];
              }
        }
        else if(!empty($puid))
        {
            $this->load->model('purchase/purchase_model','purchasem'); 
            $result = $this->purchasem->get_by_id($puid);
            $result_vendor = $this->purchasem->get_vendor_by_id($result['vendor_id']);
            $result_medince_list = $this->purchasem->get_medicine_by_purchase_id($puid);
            $data['purchase_medicine_list'] = $result_medince_list; 
            
              $vendor_id = $result['id'];
              $vendor_code = $result['vendor_id'];
              $name = $result['name'];
              $mobile_no = $result['mobile'];
              $address = $result['address'];
              $address2 = $result['address2'];
              $address3 = $result['address3'];
              $email = $result['email'];
              $vendor_gst = $result['vendor_gst'];
              $net_amount=$result['net_amount'];
              $igst_amount=$result['igst'];
              $sgst_amount=$result['sgst'];
              $cgst_amount=$result['cgst'];
              $discount_percent=$result['discount_percent'];
              $pay_amount=$result['paid_amount'];
              $total_amount=$result['total_amount'];
              $discount_amount=$result['discount'];
            
            
        }
        else
        {
          $vendor_code=generate_unique_id(11);
          $medicine_list = $this->session->userdata('medicine_id');
          $data['medicine_id'] = $medicine_list;
        }
      //echo "<pre>";print_r($medicine_list);die;
        $medicine_id_arr = [];
        $medicine_batch_arr=[];
        if(!empty($medicine_list))
        {  
           
          foreach($medicine_list as $key=>$medicine_sess)
          { 
             $imp_key = explode('.', $key);
             $medicine_id_arr[] = $imp_key[0];
             $medicine_batch_arr[] = $imp_key[1];
          } 
          //echo "<pre>";print_r($medicine_list);die;
          $medicine_ids = implode(',', $medicine_id_arr);
          $medicine_batchs = implode(',', $medicine_batch_arr);
          $data['medicne_new_list'] = $this->purchase_return->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }
        $data['unit_list'] = $this->purchase_return->unit_list();
        $purchase_no = generate_unique_id(14);
        $invoice_no = generate_unique_id(15);
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['button_value'] = "Save";
        $data['form_data'] = array(
                                    "data_id"=>"",
                                    'vendor_id'=> $pid,
                                    "vendor_code"=> $vendor_code,
                                    "name"=>$name,
                                    'vendor_gst'=>$vendor_gst,
                                    "return_no"=>$purchase_no,
                                    'invoice_id'=>"",
                                    'purchase_no'=>"",
                                    "address"=>$address,
                                    'address2'=>$address2,
                                    'address3'=>$address3,
                                    "mobile"=>$mobile_no,
                                    "email"=>$email,
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "remarks"=>"",
                                    "purchase_date"=>date('d-m-Y'),
                                    'purchase_time'=>date('H:i:s'),
                                    'payment_mode'=>"",
                                    "field_name"=>'',
                                    'discount_percent'=>'',
                                    'total_amount'=>$total_amount,
                                    'discount_amount'=>$discount_amount,
                                    
                                    "country_code"=>"+91",
                                    'net_amount'=>$net_amount,
                                    'igst_amount'=>$igst_amount,
                                    'sgst_amount'=>$sgst_amount,
                                    'cgst_amount'=>$cgst_amount,
                                    'discount_percent'=>$discount_percent,
                                    'pay_amount'=>$paid_amount,
                                    
                                    


                                );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $return_id= $this->purchase_return->save();

              if(!empty($return_id))
              {
                  
                $get_by_id_data = $this->purchase_return->get_by_id($return_id);
                $get_vendor_by_id = $this->purchase_return->get_vendor_by_id($get_by_id_data['vendor_id']);
                //print_r($get_by_id_data); exit;
                $vendor_name = $get_vendor_by_id['name'];
                $vendor_gst = $get_vendor_by_id['vendor_gst'];
                $mobile_no = $get_vendor_by_id['mobile'];
                $email = $get_vendor_by_id['email'];
                //print_r($get_by_id_data); exit;
                $purchase_id = $get_by_id_data['purchase_id'];
                $paid_amount = $get_by_id_data['paid_amount'];
                $net_amount = $get_by_id_data['net_amount'];

                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                  
                  if(!empty($mobile_no))
                  {
                    send_sms('purchase_medicine_return',7,$vendor_name,$mobile_no,array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount)); 
                  }
              }

              if(in_array('641',$users_data['permission']['action']))
              {
                if(!empty($email))
                {
                  
                  $this->load->library('general_functions');
                  $this->general_functions->email($email,'','','','','1','purchase_medicine_return','7',array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount));
                   
                }
              }
            }

                $this->session->set_userdata('purchase_id',$return_id);
                $this->session->set_flashdata('success','Purchase return has been successfully added.');
                redirect(base_url('purchase_return/add/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        
        $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
        $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
        
        $data['discount_setting'] = $discount_setting_data[1];
       $this->load->view('purchase_return/add',$data);
  }

  public function edit($id="")
    {

         unauthorise_permission(59,394);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->purchase_return->get_by_id($id); 
        //print '<pre>';print_r($result);
         $medicine_id_arr=[];
         $result_vendor = $this->purchase_return->get_vendor_by_id($result['vendor_id']);
         if(empty($post))
         { 
            $result_medince_list = $this->purchase_return->get_medicine_by_purchase_id($id);
            
        }
         //$medicine_list = $this->session->userdata('medicine_id');
         //print_r($medicine_list);die;
         //$data['medicine_id'] = $medicine_list;
         $data['id'] = $id;
       // echo "<pre>";print '<pre>';print_r($result_medince_list);
       /* if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          foreach($medicine_list as $key=>$medicine_sess)
          {
             $imp_key = explode('.', $key);
             $medicine_id_arr[] = $imp_key[0];
             $medicine_batch_arr[] = $imp_key[1];
          } 
          //echo "<pre>";print_r($medicine_list);die;
          $medicine_ids = implode(',', $medicine_id_arr);
          $medicine_batchs = implode(',', $medicine_batch_arr);
          $data['medicne_new_list'] = $this->purchase_return->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }*/
        $data['medicine_new_list'] =$result_medince_list;
       //print '<pre>';print_r($data['medicne_new_list']);
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
         $data['payment_mode']=$this->general_model->payment_mode();
        //echo $result['mode_payment'];die;
        $get_payment_detail= $this->purchase_return->payment_mode_detail_according_to_field($result['mode_payment'],$id);

        $total_values='';
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
        $data['page_title'] = "Edit Purchase Medicines Return";  
         $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        
        if($result['purchase_time']=='00:00:00')
        {
          $purchase_time='';
        }
        else
        {
          $purchase_time=$result['purchase_time'];
        }
        
        $data['form_data'] = array(

                                    "vendor_id"=> $result['vendor_id'],
                                    "vendor_code"=>$result_vendor['vendor_id'],
                                    "name"=>$result_vendor['name'],
                                    "vendor_gst"=>$result_vendor['vendor_gst'],
                                    'invoice_id'=>$result['invoice_id'],
                                    "return_no"=>$result['return_no'],
                                    'purchase_no'=>$result['purchase_id'],
                                    "address"=>$result_vendor['address'],
                                    "address2"=>$result_vendor['address2'],
                                    "address3"=>$result_vendor['address3'],
                                    "mobile"=>$result_vendor['mobile'],
                                    "email"=>$result_vendor['email'],
                                    "data_id"=>$result['id'],
                                    "remarks"=>$result['remarks'],
                                    "branch_id"=>$result['branch_id'],
                                    "field_name"=>$total_values,
                                    "purchase_date"=>date('d-m-Y',strtotime($result['purchase_date'])),
                                    'purchase_time'=>$purchase_time,
                                    'total_amount'=>$result['total_amount'],
                                    'discount_amount'=>$result['discount'],
                                    'payment_mode'=>$result['mode_payment'],
                                    //'bank_name'=>$result['bank_name'],
                                   // 'card_no'=>$result['card_no'],
                                   // 'cheque_no'=>$result['cheque_no'],
                                    "field_name"=>$total_values,
                                    'net_amount'=>$result['net_amount'],
                                    'cgst_amount'=>$result['cgst'],
                                    'igst_amount'=>$result['igst'],
                                    'sgst_amount'=>$result['sgst'],
                                    'net_amount'=>$result['net_amount'],
                                    //'vat_amount'=>$result['vat'],
                                    //'vat_percent'=>$result['vat_percent'],
                                    'discount_percent'=>$result['discount_percent'],
                                    'pay_amount'=>$result['paid_amount'],
                                    //'payment_date'=>date('d-m-Y',strtotime($result['payment_date'])),
                                    "country_code"=>"+91"
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                 $return_id= $this->purchase_return->save();
                 //echo $return_id; die;
                $this->session->set_userdata('purchase_id',$return_id);
                 $this->session->set_flashdata('success','Purchase return has been successfully updated.');
                 redirect(base_url('purchase_return/?status=print'));
                
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
        $this->load->view('purchase_return/add',$data);  

      }
    }
    
   public function payment_calc_all()
    { 
       $medicine_list = $this->session->userdata('medicine_id');
       if(!empty($medicine_list))
       {
        $post = $this->input->post();
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $tot_discount_amount=0;
        $net_amount =0;  
         $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $totigst_amount=0;
        $totcgst_amount=0;
        $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0;  
        $purchase_amount=0;
        $newamountwithigst=0;
        $newamountwithcgst=0;
        $newamountwithsgst=0;
        $total_new_amount=0;
        $payamount=0;
        $i=0;
        //print_r($medicine_list);die;
        foreach($medicine_list as $medicine)
        {    
            $signal_unit1_price = $medicine['purchase_amount']*$medicine['unit1'];
            $signal_unit2_price = ($medicine['purchase_amount']/$medicine['conversion'])*$medicine['unit2'];
            //$total_amount += ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
             $total_amount += $signal_unit1_price+$signal_unit2_price;
             $tot_discount_amount+= (($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$medicine['discount']);
            $total_cgst += ($total_row_amount/100)*$medicine['cgst']; 
            $total_sgst += ($total_row_amount/100)*$medicine['sgst'];
            $total_igst += ($total_row_amount/100)*$medicine['igst']; 
            $i++;
        } 

        if($post['discount']!='' && $post['discount']!=0){

        $total_discount = ($post['discount']/100)* $total_amount;

        }else{
        $total_discount=$tot_discount_amount;
        }
        $net_amount = ($total_amount-$total_discount)+$total_cgst+$total_igst+$total_sgst;
            if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=$net_amount;
            }
         
            $blance_due=$net_amount - $payamount;
      
        
        $total_cgst = number_format($total_cgst,2,'.','');
        $total_igst = number_format($total_igst,2,'.','');
        $total_sgst = number_format($total_sgst,2,'.','');
      // echo $net_amount;
        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'discount'=>$post['discount'],'igst'=>$post['igst'],'sgst'=>$post['sgst'],'cgst'=>$post['cgst'],'sgst_amount'=>$total_sgst,'igst_amount'=>$total_igst,'cgst_amount'=> $total_cgst,'balance_due'=>number_format($blance_due,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

public function payment_cal_perrow()
{
    
    
    $this->load->model('inventory_discount_setting/inventory_discount_setting_model'); 
    $discount_setting_data = $this->inventory_discount_setting_model->get_default_setting();
    $discount_setting = $discount_setting_data[1];
       
       $post = $this->input->post();
       $total_amount='';
       $medicine_list=array();
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
        
            //echo $post['medicine_id'];die; 
            $m_id = explode("_",$post['medicine_id']);
            
            $medicine_data = $this->purchase_return->medicine_list($m_id[0]);
            //echo "<pre>";print_r($medicine_data);die;
            $ratewithunit1= $post['purchase_rate']*$post['unit1'];
           // print_r($ratewithunit1);die;
            $perpic_rate=  $post['purchase_rate']/$post['conversion'];
            //print_r($perpic_rate);die;
            //echo $perpic_rate;
            $ratewithunit2=$perpic_rate*$post['unit2'];
            $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
            //echo $tot_qty_with_rate;
            //$tot_qty_with_rate= $medicine_data[0]->purchase_rate*$post['unit1'];
            $qty=($post['conversion']*$post['unit1'])+$post['unit2'];
            $freeqty=($post['conversion']*$post['freeunit1'])+$post['freeunit2'];
           
           
           if($discount_setting==1)
            {
               $total_discount = $post['discount']; 
            }
            else
            {
               $total_discount = ($post['discount']/100)*$tot_qty_with_rate; 
            }
            $tot_price=$tot_qty_with_rate-$total_discount;
            $total_tax = $post['cgst']+$post['igst']+$post['sgst'];
            //$totcgstToPay = ($tot_price*$total_tax)/100;
            if($post['cgst']>0)
            {
                $cgst_cal_val = ($tot_price*$post['cgst'])/100;
            }
            else
            {
               $cgst_cal_val = 0; 
            }
            
            if($post['sgst']>0)
            {
                $sgst_cal_val = ($tot_price*$post['sgst'])/100;
            }
            else
            {
               $sgst_cal_val = 0; 
            }
            
            if($post['igst']>0)
            {
                //$igst_cal_val = $totcgstToPay/2;
                $igst_cal_val = ($tot_price*$post['igst'])/100;
            }
            else
            {
               $igst_cal_val = 0; 
            }
           
           /*$total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $tot_price=$tot_qty_with_rate-$total_discount;
            
            $total_tax = $post['cgst']+$post['igst']+$post['sgst'];
            
            $totcgstToPay = ($tot_price*$total_tax)/100;
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
                $igst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $igst_cal_val = 0; 
            }*/
            
           $total_amount = ($total_amount+$tot_price+ $cgst_cal_val+$sgst_cal_val+$igst_cal_val);
            $total_amount = $total_amount;
           
            
            //End GST
            
            $medicine_list =  array('mid'=>$m_id[0],'freeunit1'=>$post['freeunit1'],'freeunit2'=>$post['freeunit2'],'unit1'=>$post['unit1'],'unit2'=>$post['unit2'],'manuf_date'=>$post['manuf_date'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'batch_no'=>$post['batch_no'],'hsn_no'=>$post['hsn_no'],'exp_date'=>$post['expiry_date'],'qty'=>$qty,'freeqty'=>$freeqty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'], 'total_amount'=>$total_amount,'total_price'=>$total_amount,'bar_code'=>$post['bar_code'],'key_id'=>$post['medicine_id'],'row_wiout_gst'=>$tot_price); 
            //echo "<pre>"; print_r($medicine_list);die;
            //$this->session->set_userdata('medicine_id', $medicine_list);
            //$pay_arr = array('total_amount'=>number_format($total_amount,2));
            
            $json = json_encode($medicine_list,true);
            echo $json;
         
       }
    } 
 /* public function payment_cal_perrow()
      {
         $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
         $post = $this->input->post();
         if(isset($post) && !empty($post))
         {
           $total_amount = 0;
           $medicine_list = $this->session->userdata('medicine_id');
             //print_r($medicine_list);die;
           if(!empty($medicine_list))
           {

             $medicine_id_new= explode('.',$post['medicine_id']);

              $medicine_data = $this->purchase_return->medicine_list($medicine_id_new[0],$medicine_id_new[1]);
              //$medicine_data = $this->purchase_return->medicine_list();
              $ratewithunit1= $post['purchase_rate']*$post['unit1'];
              $perpic_rate=  $post['purchase_rate']/$post['conversion'];
              $ratewithunit2=$perpic_rate*$post['unit2'];
              $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
              
              $qty=$post['conversion']*$post['unit1']+$post['unit2'];
              $freeqty=$post['conversion']*$post['freeunit1']+$post['freeunit2'];
              //echo $qty;
              $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
              $tot_price=$tot_qty_with_rate-$total_discount;

              $cgstToPay = ($tot_price / 100) * $post['cgst'];
              $igstToPay = ($tot_price / 100) * $post['igst'];
              $sgstToPay = ($tot_price / 100) * $post['sgst'];
              $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;
              
              $medicine_list[$post['mbid']] =  array('mid'=>$medicine_id_new[0],'freeunit1'=>$post['freeunit1'],'freeunit2'=>$post['freeunit2'], 'unit1'=>$post['unit1'],'unit2'=>$post['unit2'],'manuf_date'=>$post['manuf_date'],'hsn_no'=>$post['hsn_no'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'batch_no'=>$post['batch_no'],'bar_code'=>$post['bar_code'],'exp_date'=>$post['expiry_date'],'qty'=>$qty,'freeqty'=>$freeqty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'], 'total_amount'=>$total_amount); 
              $this->session->set_userdata('medicine_id', $medicine_list);
              $pay_arr = array('total_amount'=>number_format($total_amount,2));
              $json = json_encode($pay_arr,true);
              echo $json;
           }
         }
      }*/
    private function _validate()
    {

        $post = $this->input->post();
        $total_values=array();  
        if(isset($post['field_name']))
          {
        $count_field_names= count($post['field_name']);  
        
        $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);

        for($i=0;$i<$count_field_names;$i++) {
        $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

        }  
        }  
        //$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        
        $field_list = mandatory_section_field_list(11);  
        $users_data = $this->session->userdata('auth_users'); 
        
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  

         if(!empty($field_list)){ 
            if($field_list[0]['mandatory_field_id']=='54' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('mobile', 'Mobile No.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
            }

            if($field_list[1]['mandatory_field_id']=='55' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('name', 'Vendor Name.', 'trim|required'); 
            }

             if($field_list[2]['mandatory_field_id']=='56' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){  
                $this->form_validation->set_rules('vendor_gst', 'Vendor GST No', 'trim|required'); 
            }
          }
       // $this->form_validation->set_rules('name', 'vendor name', 'trim|required');
        $this->form_validation->set_rules('pay_amount', 'Pay amount', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        //$this->form_validation->set_rules('mobile', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
        if(isset($post['field_name']))
        {
        $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
         
          /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
            
            $this->form_validation->set_rules('card_no', 'Transaction no', 'trim|required');
          }
          if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
            $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
            $this->form_validation->set_rules('cheque_no', 'Bank name', 'trim|required');
            $this->form_validation->set_rules('payment_date', 'Cheque Date', 'trim|required');
          }
          if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
           
            $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
          }
       */
        if ($this->form_validation->run() == FALSE) 
        {  
            $invoiceid = generate_unique_id(15);
            $purchase_no = generate_unique_id(14); 
            $vendor_code = generate_unique_id(11); 
     
    /* if(isset($post['bank_name'])){

      $bank_name=$post['bank_name'];
      }else{
      $bank_name='';
      }
      if(isset($post['card_no'])){

      $card_no=$post['card_no'];
      }else{
      $card_no='';
      }
      if(isset($post['cheque_no'])){

      $cheque_no=$post['cheque_no'];
      }else{
      $cheque_no='';
      }
      if(isset($post['payment_date']) && !empty($post['payment_date'])){

      $payment_date=date('d-m-Y',strtotime($post['payment_date']));

      }else{
      $payment_date='';
      }
      if(isset($post['transaction_no'])){

      $transaction_no=$post['transaction_no'];
      }else{
      $transaction_no='';
      }*/
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vendor_id"=>$_POST['vendor_id'],
                                    'vendor_code'=>$vendor_code,
                                    "invoice_id"=>$_POST['invoice_id'],
                                    "purchase_no"=>$_POST['purchase_no'],
                                    "return_no"=>$purchase_no,
                                    "name"=>$_POST['name'],
                                    'vendor_gst'=>$post['vendor_gst'],
                                    "email"=>$_POST['email'],
                                    "address"=>$_POST['address'],
                                    "address2"=>$_POST['address2'],
                                    "address3"=>$_POST['address3'],
                                    "mobile"=>$_POST['mobile'],
                                    'total_amount'=>$post['total_amount'],
                                    "purchase_date"=>$post['purchase_date'],
                                    "purchase_time"=>$post['purchase_time'],
                                    'discount_amount'=>$post['discount_amount'],
                                    'payment_mode'=>$post['payment_mode'],
                                    //'bank_name'=>$bank_name,
                                    //'card_no'=>$card_no,
                                    "remarks"=>$post['remarks'],
                                    //'cheque_no'=>$cheque_no,
                                    //'payment_date'=>$payment_date,
                                    'net_amount'=>$post['net_amount'],
                                    'igst_amount'=>$post['igst_amount'],
                                    'sgst_amount'=>$post['sgst_amount'],
                                    'cgst_amount'=>$post['cgst_amount'],
                                    "field_name"=>$total_values,
                                   // 'vat_amount'=>$post['vat_amount'],
                                    'pay_amount'=>$post['pay_amount'],
                                    //'vat_percent'=>$post['vat_percent'],
                                    'discount_percent'=>$post['discount_percent'],
                                    //'transaction_no'=>$transaction_no,
                                    "country_code"=>"+91"
                                   );  
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission(59,395);
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_return->delete($id);
           $response = "Purchase return successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(59,395);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_return->deleteall($post['row_id']);
            $response = "Purchase return successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(59,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
        $this->load->view('purchase_return/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(59,126);
        $data['page_title'] = 'Purchase return archive list';
        $this->load->helper('url');
        $this->load->view('purchase_return/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(59,396);
        $this->load->model('purchase_return/purchase_return_archive_model','purchase_return_archive'); 

        $list = $this->purchase_return_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $purchase_return) { 
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

           $row[] = '<input type="checkbox" name="purchase_return[]" class="checklist" value="'.$purchase_return->id.'">'.$check_script;  
              $row[] = strlen($purchase_return->purchase_id) > 12 ? substr($purchase_return->purchase_id, 0, 12) . '...' : $purchase_return->purchase_id;
               $row[] = $purchase_return->return_no;
              //$row[] = $purchase_return->invoice_id;
              $row[] = strlen($purchase_return->invoice_id) > 12 ? substr($purchase_return->invoice_id, 0, 12) . '...' : $purchase_return->invoice_id;
              $row[] = $purchase_return->vendor_name;

              // $row[] = $purchase_return->total_amount;
              $row[] = $purchase_return->net_amount;
              $row[] = $purchase_return->paid_amount;
              $row[] = $purchase_return->balance;
            $row[] = date('d-M-Y',strtotime($purchase_return->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('398',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_purchase_return('.$purchase_return->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('397',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$purchase_return->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->purchase_return_archive->count_all(),
                        "recordsFiltered" => $this->purchase_return_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(59,398);
        $this->load->model('purchase_return/purchase_return_archive_model','purchase_return_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_return_archive->restore($id);
           $response = "Purchase return  successfully restore in medicine purchase return list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(59,398);
        $this->load->model('purchase_return/purchase_return_archive_model','purchase_return_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_return_archive->restoreall($post['row_id']);
            $response = "Purchase return successfully restore in purchase return list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(59,397);
        $this->load->model('purchase_return/purchase_return_archive_model','purchase_return_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_return_archive->trash($id);
           $response = "Purchase return  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(59,397);
        $this->load->model('purchase_return/purchase_return_archive_model','purchase_return_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_return_archive->trashall($post['row_id']);
            $response = "Purchase return successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function medicine_entry_dropdown()
  {
      $medicine_entry_list = $this->medicine_entry->employee_type_list();
      $dropdown = '<option value="">Select Medicine Entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $medicine_entry)
        {
           $dropdown .= '<option value="'.$medicine_entry->id.'">'.$medicine_entry->medicine_name.'</option>';
        }
      } 
      echo $dropdown; 
  }*/

 public function print_purchase_return_recipt($ids=""){
    $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Add purchase return medicine";
      $this->load->model('general/general_model');
     if(!empty($ids)){
       $purchase_id= $ids;
     }else{
       $purchase_id= $this->session->userdata('purchase_id');
     }
      $get_detail_by_id= $this->purchase_return->get_by_id($purchase_id);
      $get_by_id_data=$this->purchase_return->get_all_detail_print($purchase_id,$get_detail_by_id['branch_id']);
      $template_format= $this->purchase_return->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
       $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['mode_payment']);
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
       $data['payment_mode']= $get_payment_detail;
      $this->load->view('purchase_return/print_template_medicine',$data);

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
  
  public function search_purchase($vals="")
   {
        if(!empty($vals))
        {
            $result = $this->purchase_return->search_purchase($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    public function get_purchase_medicine()
    {
       
       $post =  $this->input->post();
       $post_mid_arr = [];
       if(isset($post['purchase_id']) && !empty($post['purchase_id']))
       {
         $purchase = $this->session->userdata('medicine_id');
         $medicine_id = [];
         $mid_arr = [];

          
         if(!empty($post['purchase_id']))
         {
            $this->load->model('purchase/purchase_model','purchase');
            $total_amount=0;
            $result = $this->purchase->get_by_id($post['purchase_id']); 
            $medicine_id_arr=[];
            $result_vendor = $this->purchase->get_vendor_by_id($post['purchase_id']);
            $result_medince_list = $this->purchase->get_medicine_by_purchase_id($post['purchase_id']);
            //echo "<pre>"; print_r($result_medince_list); exit; 
            foreach($result_medince_list as $medicines)
            {
                $perpic_rate= $medicines['purchase_amount']/$medicines['conversion'];

                $post_mid_arr[$medicines['mid'].'.'.$medicines['batch_no']] = array('mid'=>$medicines['mid'], 'batch_no'=>$medicines['batch_no'],'unit1'=>$medicines['unit1'],'bar_code'=>$medicines['bar_code'],'unit2'=>$medicines['unit2'],'freeunit1'=>$medicines['freeunit1'],'freeunit2'=>$medicines['freeunit2'],'hsn_no'=>$medicines['hsn_no'],'conversion'=>$medicines['conversion'],'manuf_date'=>date('d-m-Y',strtotime($medicines['manuf_date'])),'mrp'=>$medicines['mrp'],'perpic_rate'=>$perpic_rate, 'qty'=>$medicines['qty'],'freeqty'=>$medicines['freeqty'],'exp_date'=>date('d-m-Y',strtotime($medicines['exp_date'])), 'purchase_amount'=>$medicines['purchase_amount'],'discount'=>$medicines['discount'],'cgst'=>$medicines['cgst'],'igst'=>$medicines['igst'],'purchase_amount'=>$medicines['purchase_amount'],'sgst'=>$medicines['sgst'],'total_amount'=>$medicines['total_amount']); 
            }
              if(isset($purchase) && !empty($purchase))
              { 
                $medicine_id = $purchase+$post_mid_arr;
              } 
              else
              {
                $medicine_id = $post_mid_arr;
              }
         }

          $this->session->set_userdata('medicine_id',$medicine_id);
          $this->ajax_added_medicine();
       }
    }
    
    
    public function print_barcode($id)
    {
        $patient_data = $this->purchase_return->get_by_id($id); 
        $data['barcode_id'] = $patient_data['return_no'];
        if(!empty($data['barcode_id']))
        {
            $this->load->view('patient/barcode',$data);
        }
    }
    
    public function set_new_medicine()
    {
      
       $post =  $this->input->post();
       $row_id = $post['row_id'];
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
            $medicine_id = [];
            $mid_arr = [];
            $total_amount=0;
            $i=0;

            foreach($post['medicine_id'] as $m_ids)
            {

              $medicine_data = $this->purchase_return->medicine_list($m_ids);
              //echo "<pre>"; print_r($medicine_data); die;
              
              if(!empty($medicine_data[0]->purchase_rate))
              {
                 $purchase_rate = $medicine_data[0]->purchase_rate;
              }
              else
              {
                  $purchase_rate = $medicine_data[0]->initial_p_rate;
              }
              
              
              
              $ratewithunit1= $purchase_rate*0;
              $qty=0;
              $freeqty=0;
              $perpic_rate=$purchase_rate/$medicine_data[0]->conversion;
              $ratewithunit2=$perpic_rate*0;
              $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                
                $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;
                $cgstToPay = ($tot_price / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $medicine_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;
                //$medicine_data = $this->purchase_return->medicine_list($m_ids);
                if(!empty($post['barcode'][$i]))
                {
                    $bar_code = $post['barcode'][$i];
                }
                else
                {
                    $bar_code='';
                }
                
                $conversion = $medicine_data[0]->conversion;
                if(!empty($medicine_data[0]->batch_no))
                {
                    $batch_no = $medicine_data[0]->batch_no;
                }
                else
                {
                    $batch_no =0;
                }
                
                if($medicine_data[0]->expiry_date=="00-00-0000" && '01-01-1970'){

                $date_new='';
                }else{
                //$date_new=$medicine_data[0]->expiry_date;
                $date_new = date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
                }
                if(strtotime($medicine_data[0]->manuf_date) < 31536000){
                
                $date_newma='';
                }else{
                //$date_newma=$medicine_data[0]->manuf_date;
                $date_newma = date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
                }
                
                
                $check_script = "<script>
                        var today = new Date();
                        $('#expiry_date_".$row_id."').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                         startDate: '".$date_new."',

                        });</script>";
                        $check_script1= "<script>
                          var today = new Date();
                        $('#manuf_date_".$row_id."').datepicker({
                        format: 'dd-mm-yyyy',
                        autoclose: true,
                        endDate: '".$date_newma."',
                        });
                        
                         $('#discount_".$row_id."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$row_id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });

                           $('#sgst_".$row_id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$row_id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                        </script>";
               // $batch_no = $m_id_arr[1];
                if(!empty($medicine_data[0]->batch_no))
                {
                    $batch_no = $medicine_data[0]->batch_no;
                }
                else
                {
                    $batch_no =0;
                }
                $value="'".$medicine_data[0]->id.".".$batch_no."'";
                
                if(!empty($medicine_data[0]->discount))
                {
                    $discountval = $medicine_data[0]->discount;
                }
                else
                {
                    $discountval='0';
                }
                
                $post_mid_arr = array('mid'=>$medicine_data[0]->id,'medicine_name'=>$medicine_data[0]->medicine_name,'medicine_code'=>$medicine_data[0]->medicine_code,'packing'=>$medicine_data[0]->packing,'unit1'=>0,'unit2'=>0,'conversion'=>$conversion,'batch_no'=>$batch_no,'manuf_date'=>$date_newma,'perpic_rate'=>$perpic_rate,'freeunit1'=>0,'freeunit2'=>0,'hsn_no'=>$medicine_data[0]->hsn_no,'qty'=>'1', 'exp_date'=>$date_new, 'purchase_amount'=>$purchase_rate,'mrp'=>$medicine_data[0]->mrp,'qty'=>$qty,'freeqty'=>$freeqty,'discount'=>$discountval,'cgst'=>$medicine_data[0]->cgst,'igst'=>$medicine_data[0]->igst,'sgst'=>$medicine_data[0]->sgst,'total_amount'=>$total_amount,'bar_code'=>$bar_code,'check_scrip'=>$check_script,'manuf_script'=>$check_script1,'vals'=>$value,); 
                $mid_arr[] = $m_ids;
                $i++;
            }
            $medicine_id = $post_mid_arr;
            
             $output=array('data'=>$post_mid_arr);
                     echo json_encode($output);
            //$this->ajax_added_medicine_list($medicine_id);
       }
    }
    
    
   public function ajax_check_stock_avability()
   {
       

      $medi_id= $this->input->post('medi_id');
      $med_batch= $this->input->post('med_batch');
      $return= $this->purchase_return->check_stock_avability($medi_id,$med_batch);
     // echo "<pre>";print_r($return); die;
     //instock    
     $output=array('avaibale_qty'=>$return);
     //print_r($output); die;
     echo json_encode($output);
     
    }
    
    public function ajax_check_row_stock_avability()
   {
       

      $medi_id= $this->input->post('medi_id');
      $med_batch= $this->input->post('med_batch');
      $row_qty = $this->input->post('row_qty');
      $return= $this->purchase_return->check_row_stock_avability($medi_id,$med_batch,$row_qty);
     // echo "<pre>";print_r($return); die;
     //instock  
     if($row_qty>$return)
     {
         $status =0;
     }
     else
     {
         $status =1;
     }
     //$output=array('avaibale_qty'=>$return);
     //print_r($output); die;
     //echo json_encode($output);
     
     $output=array('qty_status'=>$status);
     echo json_encode($output);
     
    }
    


}
