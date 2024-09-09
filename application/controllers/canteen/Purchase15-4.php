<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('canteen/purchase/purchase_model','purchase');
    $this->load->library('form_validation');
    }

    
  public function index()
    {
        //unauthorise_permission(58,385);
        $this->session->unset_userdata('item_id'); 
        $this->session->unset_userdata('net_values_all');
        $this->session->unset_userdata('purchase_search_item');
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
        $data['page_title'] = 'Purchase Item List'; 
        $this->load->view('canteen/purchase/list',$data);
    }

    public function ajax_list()
    {  
       //unauthorise_permission(58,385);
        $list = $this->purchase->get_datatables();    
		
	//echo"<pre>"; print_r($list); die;	
        $session_data= $this->session->userdata('auth_users');
       
        $assoc_array = json_decode(json_encode($list),TRUE);
        $total_net_amount = array_sum(array_column($assoc_array,'net_amount'));
        $total_discount = array_sum(array_column($assoc_array,'discount_percent'));
        $total_balance= array_sum(array_column($assoc_array,'balance'));
        //$total_vat= array_sum(array_column($assoc_array,'vat'));
        $total_paid_amount= array_sum(array_column($assoc_array,'paid_amount'));
        $session_new_datas=array('net_amount'=>$total_net_amount,'discount'=>$total_discount,'balance'=>$total_balance,'paid_amount'=>$total_paid_amount);
        $this->session->set_userdata('net_values_all',$session_new_datas); 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $purchase) { 
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
           
            $row[] = '<input type="checkbox" name="purchase[]" class="checklist" value="'.$purchase->id.'">'.$check_script;  
            $row[] = $purchase->purchase_id;
            $row[] = $purchase->invoice_id;
            $row[] = $purchase->vendor_name;
            //$row[] = $purchase->total_amount;
            $row[] = $purchase->net_amount;
            $row[] = $purchase->paid_amount;
            $row[] = $purchase->balance;
            $row[] = date('d-M-Y',strtotime($purchase->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
            if($session_data['parent_id']==$purchase->branch_id){

           //if(in_array('387',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_purchase('.$purchase->id.');" class="btn-custom" href="javascript:void(0)" style="'.$purchase->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
           // }
		   
            //if(in_array('125',$users_data['permission']['action'])){
                /*$btnview=' <a class="btn-custom" onclick="return view_purchase('.$purchase->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';*/
           // }
		   
            //if(in_array('388',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_purchase('.$purchase->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
           // }
          }

          $new_path=base_url().'canteen/purchase/print_purchase_recipt';
          //$btnprint = '<a class="btn-custom" onclick="openPrintWindow(123,'.$purchase->id.');" target="_blank"><i class="fa fa-print"></i> Print</a>';
          //;$print_url = "'".base_url('canteen/purchase/print_purchase_recipt/'.$purchase->id)."'";
          //$btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 

            $row[] = $btnedit.$btnview.$btndelete;
            //$row[] = $btnedit.$btnview.$btndelete.$btnprint;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->purchase->count_all(),
                        "recordsFiltered" => $this->purchase->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

    public function item_purchase_excel()
    {
         $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Purchase No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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

               $col++;
          }
          $list = $this->purchase->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
          header("Content-Disposition: attachment; filename=item_purchase_report_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }





    public function item_purchase_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Purchase No.','Invoice No.','Vendor Name','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->purchase->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->purchase_id,$reports->invoice_id,$reports->vendor_name,$reports->net_amount,$reports->paid_amount,$reports->balance,date('d-M-Y H:i A',strtotime($reports->created_date)));
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
         header("Content-Disposition: attachment; filename=item_purchase_report_".time().".csv");  
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
               ob_end_clean();
               $objWriter->save('php://output');
         }

    }

    public function pdf_item_purchase()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->purchase->search_report_data();
        $this->load->view('canteen/purchase/item_purchase_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("item_purchase_report_".time().".pdf");
    }
    public function print_item_purchase()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->purchase->search_report_data();
      $this->load->view('canteen/purchase/item_purchase_report_html',$data); 
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
    public function ajax_list_item(){
       $item_list = $this->session->userdata('item_id');

       $post = $this->input->post();  
       $ids=array();
       $table = '';
        if(!empty($item_list))
        { 
          $ids_arr= [];
          foreach($item_list as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
          }
          $item_ids = implode(',', $ids_arr);
          $data['item_new_list'] = $this->purchase->item_list($item_ids);
           foreach($data['item_new_list'] as $item_list){
                           $ids[]=$item_list->id;
           }
        }

        $this->load->model('canteen/stock_item/stock_item_model','stock_item');
        $keywords= $this->input->post('search_keyword');
        $name= $this->input->post('name'); 
        if(!empty($post['item']) || !empty($post['item_code']) || !empty($post['bar_code1']) || !empty($post['manuf_company']) || !empty($post['conv']) || !empty($post['mfc_date']) || !empty($post['unit1']) ||  !empty($post['unit2']) || !empty($post['mrp']) || $post['mrp']==0 || !empty($post['p_rate']) || !empty($post['discount']) || $post['discount']==0 || !empty($post['cgst']) || $post['cgst']==0 || !empty($post['hsn_no']) || !empty($post['igst']) || !empty($post['hsn_no']) || $post['igst']==0 || !empty($post['sgst']) || $post['sgst']==0 ||!empty($post['packing']))
        { 
        
          $result_item = $this->purchase->item_list_search();  
		  
		  
        } 

        if(count($result_item)>0 && isset($result_item) || !empty($ids))
        {
          foreach($result_item as $item)
          {
              if(!in_array($item->id,$ids))
              {
                  $table.='<tr class="append_row">';
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$item->id.'" onclick="add_check();"></td>';
                  $table.='<td><a onClick="get_canteen_vendor('.$item->id.');" href="#" data-toggle="modal" data-target="#item_namess">'.$item->item.'</a></td>';
                  $table.='<td>'.$item->packing.'</td>';
                  $table.='<td>'.$item->item_code.'</td>';
                  $table.='<td>'.$item->hsn_no.'</td>';
                  $table.='<td>'.$item->company_name.'</td>';
                  $table.='<td>'.$item->qty.'</td>';
                  $table.='<td>'.$item->mrp.'</td>';
                  $table.='<td>'.$item->purchase_rate.'</td>';
                  $table.='<td>'.$item->discount.'</td>';
                  $table.='<td>'.$item->cgst.'</td>';
                  $table.='<td>'.$item->sgst.'</td>';
                  $table.='<td>'.$item->igst.'</td>';
                  $table.='</tr>';
              }
          }
        }
        else
        {
            $table.='<tr class="append_row"><td colspan="20" align="center" class="text-danger">No record found</td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
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
                                      "item"=>"",
                                      "manuf_company"=>"",
                                      "item_code"=>"",
                                      "purchase_rate"=>"",
                                      "discount"=>"",
                                      "end_date"=>"",
                                      "igst"=>"",
                                      "cgst"=>"",
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
              $this->session->set_userdata('purchase_search_item', $marge_post);
          }
          $purchase_search_item = $this->session->userdata('purchase_search_item');
          if(isset($purchase_search_item) && !empty($purchase_search_item))
          {
              $data['form_data'] = $purchase_search_item;
          }
          $this->load->view('canteen/purchase/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('purchase_search_item');
    }

    public function ajax_added_item(){

         
		 $this->load->model('canteen/stock_item/stock_item_model','stock_item');
         $item_sess = $this->session->userdata('item_id');
        //echo '<pre>';print_r($item_sess);die;
         $check_script="";
         $result_item = [];
        if(!empty($item_sess))
        { 
          $ids_arr= [];
          foreach($item_sess as $key_m_arr=>$m_arr)
          {
             $ids_arr[] = $key_m_arr;
          }
          $item_ids = implode(',', $ids_arr);
          $result_item = $this->purchase->item_list($item_ids);
           foreach($result_item as $item_list){
                           $ids[]=$item_list->id;
                  }
                 }
                    // $setting_data= get_setting_value('MEDICINE_VAT');
                        $table='<div class=" box_scroll">';
                        $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
                        $table.='<thead class="bg-theme">';
                        $table.='<tr>';
                        $table.='<th class="40" align="center">';
                        $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                        $table.='</th>';
                        $table.='<th>item Name</th>';
                        $table.='<th>item Code</th>';
                        $table.='<th>HSN No.</th>';
                        $table.='<th>Packing</th>';
                        $table.='<th>Batch No.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>QTY</th>';
                        $table.='<th>MRP</th>';
                        $table.='<th>Purchase Rate</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST (%)</th>';
                        $table.='<th>SGST (%)</th>';
                        $table.='<th>IGST (%)</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        if(count($result_item)>0 && isset($result_item) || !empty($ids)){
                        foreach($result_item as $item){
                            if($item_sess[$item->id]["exp_date"]=="00-00-0000"){

                                $date_new=date('d-m-Y');;
                            }else{
                                $date_new=$item_sess[$item->id]["exp_date"];
                            }
                            if($item_sess[$item->id]["manuf_date"]=="00-00-0000"){

                                $date_newma=date('d-m-Y');
                            }else{
                                $date_newma=$item_sess[$item->id]["manuf_date"];
                            }
                        $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$item->id."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate:  '".$date_new."',
                        });
                       
                        </script>";

                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$item->id."').datepicker({
                              format: 'dd-mm-yyyy',
                              autoclose: true,
                              endDate: '".$date_newma."',
                            
                        });
                        
                          $('#discount_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#igst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100');
                                 
                            }

                          });
                           $('#sgst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100');
                                 
                            }
                            
                          });
                             $('#cgst_".$item->id."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100');
                                 
                            }
                            
                          });
                         
                        </script>";
                          //if(!in_array($item->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="item_id_'.$item->id.'" name="i_id[]" value="'.$item->id.'"/>';
                        $table.='<td><input type="checkbox" name="item_id[]" class="booked_checkbox" value="'.$item->id.'"> </td>';
                        $table.='<td>'.$item->item.'</td>';
                        $table.='<td>'.$item->item_code.'</td>';
                         $table.='<td><input type="text" name="hsn_no[]" placeholder="HSN No." style="width:59px;" id="hsn_no_'.$item->id.'" value="'.$item_sess[$item->id]["hsn_no"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td>'.$item->packing.'</td>';

                        $table.='<td><input type="text" value="'.$item_sess[$item->id]["batch_no"].'" name="batch_no[]" class="datepicker" placeholder="Batch Number" style="width:84px;" id="batch_no_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script.'</td>';

                        
                        $table.='<td><input type="text" name="qty[]" placeholder="Quantity" style="width:59px;" id="qty_'.$item->id.'" value="'.$item_sess[$item->id]["qty"].'" onkeyup="payment_cal_perrow('.$item->id.');"/>
                        <span id="qty_max_'.$item->id.'" class="text-danger"></span></td>';

                        $table.='<td><input type="text" id="mrp_'.$item->id.'" class="w-60px" name="mrp[]" value="'.$item_sess[$item->id]["mrp"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                        $table.='<td><input type="text" id="purchase_rate_'.$item->id.'" class="w-60px" name="purchase_rate[]" value="'.$item_sess[$item->id]["purchase_amount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$item->id.'" value="'.$item_sess[$item->id]["discount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                       
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$item_sess[$item->id]["cgst"].'" id="cgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                         $table.='<td><input type="text" class="price_float" name="SGST[]" placeholder="SGST" style="width:59px;" value="'.$item_sess[$item->id]["sgst"].'" id="sgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                          $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$item_sess[$item->id]["igst"].'" id="igst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.=' <td><input type="text" value="'.$item_sess[$item->id]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$item->id.'" /></td>';
                       
                        $table.='</tr>';
                          // }
                       }
                       }else{
                        $table.='<td colspan="20"  align="center" class="text-danger">No record found</td>';
                       }
                        $table.='</tbody>';
                        $table.='</table>';
                        $table.='</div>';
                        $table.='<div class="right">';
                        $table.='<a class="btn-new" onclick="item_list_vals();">Delete</a>';
                        $table.='</div>'; 
                     $output=array('data'=>$table);
                     echo json_encode($output);
        }
    function check_bar_code()
    {
      $bar_code= $this->input->post('bar_code');
      if(!empty($bar_code))
{
 $mbid= $this->input->post('mbid');
      $return= $this->purchase->check_bar_code($bar_code,$mbid);
      if($return==2)
      {
        echo '1';exit;
      }
      else
      {
        echo '0';exit;
      }
}
     
    }

    public function set_item()
    {
       $this->load->model('canteen/stock_item/stock_item_model','stock_item');
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
         $purchase = $this->session->userdata('item_id');
         $item_id = [];
         $iid_arr = [];
         if(isset($purchase) && !empty($purchase))
         { 
           $total_amount=0;
            $post_iid_arr = [];
            $i=0;
            foreach($post['item_id'] as $i_ids)
            {
               $item_data = $this->purchase->item_list($i_ids);
//print_r($item_data);
                $qty=0;
                $perpic_rate=$item_data[0]->purchase_rate;
                $tot_qty_with_rate=$item_data[0]->purchase_rate*$item_data[0]->qty;
                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                //$total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;
                $total_amount = $total_amount;
                $post_iid_arr[$i_ids] = array('iid'=>$i_ids,'perpic_rate'=>$perpic_rate,'manuf_date'=>'00-00-0000','batch_no'=>0,'hsn_no'=>$item_data[0]->hsn_no,'qty'=>'1','freeqty'=>'1', 'exp_date'=>'00-00-0000','discount'=>$item_data[0]->discount,'mrp'=>$item_data[0]->mrp,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'qty'=>$qty,'purchase_amount'=>$item_data[0]->purchase_rate, 'total_amount'=>$total_amount,'bar_code2'=>$post['barcode'][$i]); 
                $iid_arr[] = $i_ids;
                $i++;
            } 
            
            $item_id = $purchase+$post_iid_arr;
            
         } 
         else
         {
            $total_amount=0;
            $i=0;

            foreach($post['item_id'] as $i_ids)
            {

              $item_data = $this->purchase->item_list($i_ids);
              $qty=0;
              $perpic_rate=$item_data[0]->purchase_rate;
              $tot_qty_with_rate=$item_data[0]->purchase_rate*$item_data[0]->qty;
                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                //$total_amount = $total_amount+$tot_price;
                $total_amount = $total_amount;
                $item_data = $this->purchase->item_list($i_ids);
                $post_iid_arr[$i_ids] = array('iid'=>$i_ids,'unit1'=>0,'unit2'=>0,'conversion'=>0,'batch_no'=>0,'manuf_date'=>'00-00-0000','perpic_rate'=>$perpic_rate,'freeunit1'=>0,'freeunit2'=>0,'hsn_no'=>$item_data[0]->hsn_no,'qty'=>'1', 'exp_date'=>'00-00-0000', 'purchase_amount'=>$item_data[0]->purchase_rate,'mrp'=>$item_data[0]->mrp,'qty'=>$qty,'discount'=>$item_data[0]->discount,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'total_amount'=>$total_amount,'bar_code2'=>$post['barcode'][$i]); 
                $iid_arr[] = $i_ids;
                $i++;
            }
            $item_id = $post_iid_arr;
            
         } 
         $item_ids = implode(',',$iid_arr);
         $this->session->set_userdata('item_id',$item_id);
         //print_r($this->session->userdata('item_id'));
         $this->ajax_added_item();
       }
    }

     public function remove_item_list()
    {

      $this->load->model('canteen/stock_item/stock_item_model','stock_item');
       $post =  $this->input->post();
       if(isset($post['item_id']) && !empty($post['item_id']))
       {
           $ids_list = $this->session->userdata('item_id');
           
             foreach($post['item_id'] as $post_id)
             {
                  if(array_key_exists($post_id,$ids_list))
                  {
                     unset($ids_list[$post_id]);
                  }
             } 
             $this->session->set_userdata('item_id',$ids_list);
           
           $medicne_list = [];
           $ids_list = $this->session->userdata('item_id');  
         /*  $id_arr = []; 
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
            $medicine_listdata = $this->purchase->item_list($imp_ids);
           }*/
           $this->ajax_added_item();
       }
    } 

  public function add($pid="",$estimate_id="")
  {

		// unauthorise_permission(58,386);
		$users_data = $this->session->userdata('auth_users');
		$data['max_pur_qty']=get_setting_value('Set_Purchase_Limit_Per_Medicine');

		$this->load->model('general/general_model'); 
		$data['page_title'] = "Purchase Item";
		$data['button_value'] = "Save";
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
        $address2 = "";
        $address3 = "";
        $vendor_gst='';

       if($pid>0)
        {
           $vendor = $this->purchase->get_vendor_by_id($pid);
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
        }else{
          $vendor_code = generate_unique_id(63);
          $item_list = $this->session->userdata('item_id');
          $data['item_id'] = $item_list;
        }
		
          if(!isset($post) || empty($post))
          {
          $this->session->unset_userdata('item_id');  
          }

          $item_new_list = $this->session->userdata('item_id');
          $data['item_id'] = $item_new_list;
 
        if(!empty($item_new_list))
        { 
          $item_id_arr = [];
          foreach($item_new_list as $key=>$item_sess)
          {
             $item_id_arr[] = $key;
          } 
          $item_ids = implode(',', $item_id_arr);
          $data['item_new_list'] = $this->purchase->item_list($item_ids);
        }		
 //echo "<pre>";print_r($data['item_new_list']);die; 
 
        $invoice_no = generate_unique_id(12);
        $data['payment_mode']=$this->general_model->payment_mode();
       // echo $invoice_no;
        $purchase_no = generate_unique_id(64);
        if(!empty($estimate_id)){
        $data['item_new_list']=$item_new_list;  
        $data['form_data'] = array(
                                    "data_id"=>"",
                                    'vendor_id'=> $pid,
                                    "vendor_code"=> $vendor_code,
                                    "name"=>$name,
                                    "vendor_gst"=>$vendor_gst,
                                    'invoice_id'=>"",
                                    'purchase_no'=>$purchase_no,
                                    "address"=>$address,
                                    "address2"=>$address2,
                                    "address3"=>$address3,
                                    "mobile"=>$mobile_no,
                                    "email"=>$email,
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "remarks"=>"",
                                    "purchase_date"=>date('d-m-Y'),
                                    'purchase_time'=>date('H:i:s'),
                                    'total_amount'=>$estimate_data['total_amount'],
                                    'discount_amount'=>$estimate_data['discount'],
                                    'payment_mode'=>"",
                                    "field_name"=>'',
                                    //'bank_name'=>"",
                                    //'card_no'=>"",
                                    //'cheque_no'=>"",
                                    //'payment_date'=>date('d-m-Y'),
                                    'net_amount'=>$estimate_data['net_amount'],
                                    //'vat_amount'=>"",
                                    'igst_amount'=>$estimate_data['igst'],
                                    'sgst_amount'=>$estimate_data['sgst'],
                                    'cgst_amount'=>$estimate_data['cgst'],
                                   //'vat_percent'=>get_setting_value('MEDICINE_VAT_VALUE'),
                                    'discount_percent'=>$estimate_data['discount_percent'],
                                    'pay_amount'=>$estimate_data['paid_amount'],
                                    //'transaction_no'=>"",
                                    "country_code"=>"+91"
                                );

        //print_r($data['form_data']);die;
      }
      else{
        $data['form_data'] = array(
                                    "data_id"=>"",
                                    'vendor_id'=> $pid,
                                    "vendor_code"=> $vendor_code,
                                    "name"=>$name,
                                    "vendor_gst"=>$vendor_gst,
                                    'invoice_id'=>"",
                                    'purchase_no'=>$purchase_no,
                                    "address"=>$address,
                                    "address2"=>$address2,
                                    "address3"=>$address3,
                                    "mobile"=>$mobile_no,
                                    "email"=>$email,
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "remarks"=>"",
                                    "purchase_date"=>date('d-m-Y'),
                                    'purchase_time'=>date('H:i:s'),
                                    'total_amount'=>"0.00",
                                    'discount_amount'=>"0.00",
                                    'payment_mode'=>"",
                                    "field_name"=>'',
                                    //'bank_name'=>"",
                                    //'card_no'=>"",
                                    //'cheque_no'=>"",
                                    //'payment_date'=>date('d-m-Y'),
                                    'net_amount'=>"",
                                    //'vat_amount'=>"",
                                    'igst_amount'=>'',
                                    'sgst_amount'=>'',
                                    'cgst_amount'=>'',
                                   //'vat_percent'=>get_setting_value('MEDICINE_VAT_VALUE'),
                                    'discount_percent'=>'',
                                    'pay_amount'=>"",
                                    //'transaction_no'=>"",
                                    "country_code"=>"+91"
                                );
      }
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $return_id= $this->purchase->save();
              if(!empty($return_id))
              {
                
                $get_by_id_data = $this->purchase->get_by_id($return_id);
                $get_vendor_by_id = $this->purchase->get_vendor_by_id($get_by_id_data['vendor_id']);
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
                    send_sms('purchase_item',6,$vendor_name,$mobile_no,array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount)); 
                  }
              }

              if(in_array('641',$users_data['permission']['action']))
              {
                if(!empty($email))
                {
                  
                  $this->load->library('general_functions');
                  $this->general_functions->email($email,'','','','','1','purchase_item','6',array('{Name}'=>$vendor_name,'{Amt}'=>$net_amount,'{BillNo}'=>$purchase_id,'{PaidAmt}'=>$paid_amount));
                   
                }
              }
            }
                $this->session->set_userdata('purchase_id',$return_id);
              //   echo $return_id;die; 
                $this->session->set_flashdata('success','Purchase has been successfully added.');
				redirect(base_url('canteen/purchase'));
               // redirect(base_url('canteen/purchase/add/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
       // print_r($data['form_data']);
      $this->load->view('canteen/purchase/add',$data);
  }

  public function edit($id="")
    {
     //unauthorise_permission(58,387);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
          $result = $this->purchase->get_by_id($id);
          $data['max_pur_qty']=get_setting_value('Set_Purchase_Limit_Per_Medicine');

         $item_id_arr=[];
         $result_vendor = $this->purchase->get_vendor_by_id($result['vendor_id']);
         if(empty($post))
         { 
            $result_item_list = $this->purchase->get_item_by_purchase_id($id);
    // echo "<pre>"; print_r($result_item_list); 
            $this->session->set_userdata('item_id',$result_item_list);
          }
         $item_new_list = $this->session->userdata('item_id');
     // print_r($item_new_list);die;
         $data['item_id'] = $item_new_list;
         $data['id'] = $id;
        //print '<pre>';print_r($item_new_list);
        if(!empty($item_new_list))
        { 
          $item_id_arr = [];
          foreach($item_new_list  as $key=>$val)
          { 
             $item_id_arr[] = $key;
          } 
          $item_ids = implode(',', $item_id_arr);
          $data['item_new_list'] = $this->purchase->item_list($item_ids);
          
        }
	   
        $reg_no = generate_unique_id(10);
        $this->load->model('general/general_model');
        
        $data['payment_mode']=$this->general_model->payment_mode();
        //echo $result['mode_payment'];die;
        $get_payment_detail= $this->purchase->payment_mode_detail_according_to_field($result['mode_payment'],$id);

        $total_values='';
        for($i=0;$i<count($get_payment_detail);$i++) {
        $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        
         if($result['purchase_time']=='00:00:00')
        {
          $purchase_time='';
        }
        else
        {
          $purchase_time=$result['purchase_time'];
        }
        //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
        $data['page_title'] = "Purchase Item";  
        $data['button_value'] = "Update";
	    $data['item_new_list']=$item_new_list;  
        $data['form_error'] = ''; 
        $data['form_data'] = array(

                                    "vendor_id"=> $result['vendor_id'],
                                    "vendor_code"=>$result_vendor['vendor_id'],
                                    "name"=>$result_vendor['name'],
                                    "vendor_gst"=>$result_vendor['vendor_gst'],
                                    'invoice_id'=>$result['invoice_id'],
                                    'purchase_no'=>$result['purchase_id'],
                                    "address"=>$result_vendor['address'],
                                     "address2"=>$result_vendor['address2'],
                                      "address3"=>$result_vendor['address3'],
                                    "mobile"=>$result_vendor['mobile'],
                                    "email"=>$result_vendor['email'],
                                    "data_id"=>$result['id'],
                                    "field_name"=>$total_values,
                                    "branch_id"=>$result['branch_id'],
                                    "purchase_date"=>date('d-m-Y',strtotime($result['purchase_date'])),
                                    'purchase_time'=>$purchase_time,
                                    'total_amount'=>$result['total_amount'],
                                    'discount_amount'=>$result['discount'],
                                    'payment_mode'=>$result['mode_payment'],
                                    'bank_name'=>$result['bank_name'],
                                    'card_no'=>$result['card_no'],
                                    'cheque_no'=>$result['cheque_no'],
                                    'net_amount'=>$result['net_amount'],
                                     "remarks"=>$result['remarks'],
                                    "field_name"=>$total_values,
                                    //'vat_amount'=>$result['vat'],
                                   // 'vat_percent'=>$result['vat_percent'],
                                    'igst_amount'=>$result['igst'],
                                    'sgst_amount'=>$result['sgst'],
                                    'cgst_amount'=>$result['cgst'],
                                    'discount_percent'=>$result['discount_percent'],
                                    'pay_amount'=>$result['paid_amount'],
                                    'payment_date'=>date('d-m-Y',strtotime($result['payment_date'])),
                                    "country_code"=>"+91"
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                
                 $return_id= $this->purchase->save();

                 $this->session->set_userdata('purchase_id',$return_id);
                 $this->session->set_flashdata('success','Purchase has been successfully updated.');
				 redirect(base_url('canteen/purchase'));
                // redirect(base_url('canteen/purchase/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            }     
        }
       //print '<pre>'; print_r($data);die;
        $this->load->view('canteen/purchase/add',$data);  

      }
    }
    
   public function payment_calc_all()
    { 
      // $post = $this->input->post();
      // echo "<pre>"; print_r($post); exit;

       $item_list = $this->session->userdata('item_id');
      // print_r($item_list);die;
      
       if(!empty($item_list))
       {
        $post = $this->input->post();
        $discount_type=$post['discount_type'];
       // echo "<pre>"; print_r($post); exit;
        $total_amount = 0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0;
        $tot_discount_amount=0;
       // $totigst_amount=0;
       // $totcgst_amount=0;
       // $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0; 
        $payamount=0; 
        $purchase_amount=0;
        $total_amountwithigst=0;
       //$newamountwithigst=0;
        $total_amountwithigst=0;
       //$total_amountwithigst=0;
        $newamountwithcgst=0;
        //$newamountwithsgst=0;
        $total_new_amount=0;

        //print '<pre>'; print_r($item_list);die;
        $i=0;

        foreach($item_list as $item)
        {    
          //print_r($item['purchase_id']);die;
          if($item['purchase_id']!="")
          {
          $tot_qty_with_rate = $item['purchase_rate']*$item['qty'];
          //print_r($tot_qty_with_rate);die;
           
          }
          else{
            $tot_qty_with_rate = $item['purchase_amount']*$item['qty'];
           
        }
         
            $total_amount += $tot_qty_with_rate;
            $total_row_amount = ($tot_qty_with_rate)-(($tot_qty_with_rate)/100*$item['discount']);
            $total_cgst += ($total_row_amount/100)*$item['cgst']; 
            $total_sgst += ($total_row_amount/100)*$item['sgst'];
            $total_igst += ($total_row_amount/100)*$item['igst']; 
            $tot_discount_amount+= ((+$tot_qty_with_rate)/100*$item['discount']);
            $i++;
        } 

            if($post['discount']!='' && $post['discount']!=0)
            {
            $total_discount_perc = ($post['discount']/100)* $total_amount;
            $total_discount = round($total_discount_perc);
            }
            else
            {  
            $total_discount=$tot_discount_amount;
            }

       // $total_discount = ($post['discount']/100)* $total_amount;
        $net_amount = ($total_amount-$total_discount);
         if($post['pay']==1 || $post['data_id']!=''){
           $payamount=$post['pay_amount'];
        }else{
          $payamount=$net_amount;
        }
         
      
        $blance_due=$net_amount - $payamount;
        $total_igst = number_format($total_igst,2,'.','');
        $total_igst = number_format($total_igst,2,'.','');
        $total_sgst = number_format($total_sgst,2,'.','');
       

        $pay_arr = array('total_amount'=>round($total_amount), 'net_amount'=>round($net_amount),'pay_amount'=>round($payamount),'discount'=>$post['discount'],'igst'=>$post['igst'],'sgst'=>$post['sgst'],'cgst'=>$post['cgst'],'sgst_amount'=>$total_sgst,'igst_amount'=>$total_igst,'cgst_amount'=> $total_cgst,'balance_due'=>round($blance_due),'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

public function payment_cal_perrow()
    {
       $this->load->model('canteen/stock_item/stock_item_model','stock_item');
       $post = $this->input->post();
	   
// print_r($post); exit;   
       $total_amount='';
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $item_list = $this->session->userdata('item_id');

        
         if(!empty($item_list))
         { 
            $item_data = $this->purchase->item_list($post['item_id']);
			
            $ratewithqty= $post['purchase_rate']*$post['qty'];
            $perpic_rate=  $post['purchase_rate'];
            //print_r($perpic_rate);die;
            $tot_qty_with_rate=$ratewithqty;
            $qty=$post['qty'];
            //echo $qty;
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $tot_price=$tot_qty_with_rate-$total_discount;

            $cgstToPay = ($tot_price / 100) * $post['cgst'];
            $igstToPay = ($tot_price / 100) * $post['igst'];
            $sgstToPay = ($tot_price / 100) * $post['sgst'];

            $total_amount = $total_amount+$tot_price;

             $item_list[$post['item_id']] = array('iid'=>$post['item_id'],'item_id'=>$post['item_id'],'manuf_date'=>$post['manuf_date'],'perpic_rate'=>$perpic_rate,'batch_no'=>$post['batch_no'],'hsn_no'=>$post['hsn_no'],'exp_date'=>$post['expiry_date'],'qty'=>$qty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'], 'total_amount'=>$total_amount,'total_price'=>$total_amount); 
            $this->session->set_userdata('item_id', $item_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }
    private function _validate($id='')
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
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'vendor name', 'trim|required');
         //$this->form_validation->set_rules('invoice_id', 'invoice id', 'trim|required|callback_check_unique_value['.$id.']'); 
          $this->form_validation->set_rules('pay_amount', 'Pay amount', 'trim|required');
          $this->form_validation->set_rules('email', 'Email', 'valid_email');
          if(isset($post['field_name']))
          {
             $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
          }
         

        /*if(isset($_POST['payment_mode']) && $_POST['payment_mode']==2) {
        $this->form_validation->set_rules('card_no', 'Transaction no', 'trim|required');
         }
         if(isset($_POST['payment_mode']) && $_POST['payment_mode']==3) {
          $this->form_validation->set_rules('bank_name', 'Bank name', 'trim|required');
          $this->form_validation->set_rules('cheque_no', 'cheque no', 'trim|required');
          $this->form_validation->set_rules('payment_date', 'cheque date', 'trim|required');
        
         }
       if(isset($_POST['payment_mode']) && $_POST['payment_mode']==4) {
         $this->form_validation->set_rules('transaction_no', 'Transaction no', 'trim|required');
        }*/
           
        $this->form_validation->set_rules('mobile', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]'); 
       
        if ($this->form_validation->run() == FALSE) 
        {  
            $invoiceid = generate_unique_id(12);
            $purchase_no = generate_unique_id(64); 
            $vendor_code = generate_unique_id(63); 

              /*
              if(isset($post['bank_name'])){

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
              } */
              
              
              if(isset($post['discount_percent'])){

              $discount_percent=$post['discount_percent'];
              }else{
              $discount_percent='';
              }

              if(isset($post['cgst_amount'])){

              $cgst_amount=$post['cgst_amount'];
              }else{
              $cgst_amount='';
              }

              if(isset($post['sgst_amount'])){

              $sgst_amount=$post['sgst_amount'];
              }else{
              $sgst_amount='';
              }

              if(isset($post['igst_amount'])){

              $igst_amount=$post['igst_amount'];
              }else{
              $igst_amount='';
              }
              if(isset($post['net_amount'])){

              $net_amount=$post['net_amount'];
              }else{
              $net_amount='';
              }
              if(isset($post['payment_mode'])){

              $payment_mode=$post['payment_mode'];
              }else{
              $payment_mode='';
              }

              if(isset($post['total_amount'])){

              $total_amount=$post['total_amount'];
              }else{
              $total_amount='';
              }
               if(isset($post['discount_amount'])){

              $discount_amount=$post['discount_amount'];
              }else{
              $discount_amount='';
              }

              if(isset($post['pay_amount'])){

              $pay_amount=$post['pay_amount'];
              }else{
              $pay_amount='';
              }


            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vendor_id"=>$_POST['vendor_id'],
                                    'vendor_code'=>$vendor_code,
                                    "invoice_id"=>$_POST['invoice_id'],
                                    "purchase_no"=>$purchase_no,
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
                                    'discount_amount'=>$discount_amount,
                                    'payment_mode'=>$payment_mode,
                                    //'bank_name'=>$bank_name,
                                    //'card_no'=>$card_no,
                                    //'cheque_no'=>$cheque_no,
                                    //'payment_date'=>$payment_date,
                                    'net_amount'=>$net_amount,
                                    "remarks"=>$post['remarks'],
                                    "field_name"=>$total_values,
                                    'vendor_gst'=>$post['vendor_gst'],
                                    'igst_amount'=>$igst_amount,
                                    'sgst_amount'=>$sgst_amount,
                                    'cgst_amount'=>$cgst_amount,
                                    'pay_amount'=>$pay_amount,
                                   // 'vat_percent'=>$post['vat_percent'],
                                    'discount_percent'=>$discount_percent,
                                    //'transaction_no'=>$transaction_no,
                                    "country_code"=>"+91"
                                   );  
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission(58,388);
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase->delete($id);
           $response = "Purchase successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       // unauthorise_permission(58,388);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase->deleteall($post['row_id']);
            $response = "Purchase successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        // unauthorise_permission(58,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['item']." detail";
        $this->load->view('canteen/purchase/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
      // unauthorise_permission(58,389);
        $data['page_title'] = 'Purchase archive list';
        $this->load->helper('url');
        $this->load->view('canteen/purchase/archive',$data);
    }

    public function archive_ajax_list()
    {
       // unauthorise_permission(58,389);
        $this->load->model('canteen/purchase/purchase_archive_model','purchase_archive'); 

        $list = $this->purchase_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $purchase) { 
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

            $row[] = '<input type="checkbox" name="purchase[]" class="checklist" value="'.$purchase->id.'">'.$check_script;  
            $row[] = $purchase->purchase_id;
            $row[] = $purchase->invoice_id;
            $row[] = $purchase->vendor_name;
            //$row[] = $purchase->total_amount;
            $row[] = $purchase->net_amount;
            $row[] = $purchase->paid_amount;
            $row[] = $purchase->balance;
            $row[] = date('d-M-Y',strtotime($purchase->purchase_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('391',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_purchase('.$purchase->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('390',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$purchase->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->purchase_archive->count_all(),
                        "recordsFiltered" => $this->purchase_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       //unauthorise_permission(58,391);
        $this->load->model('canteen/purchase/purchase_archive_model','purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_archive->restore($id);
           $response = "Purchase  successfully restore in item purchase list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       //unauthorise_permission(58,391);
        $this->load->model('canteen/purchase/purchase_archive_model','purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_archive->restoreall($post['row_id']);
            $response = "Purchase successfully restore in purchase list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       // unauthorise_permission(58,390);
        $this->load->model('canteen/purchase/purchase_archive_model','purchase_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->purchase_archive->trash($id);
           $response = "Purchase  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
     // unauthorise_permission(58,390);
        $this->load->model('canteen/purchase/purchase_archive_model','purchase_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->purchase_archive->trashall($post['row_id']);
            $response = "Purchase successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  /*public function medicine_entry_dropdown()
  {
      $medicine_entry_list = $this->medicine_entry->employee_type_list();
      $dropdown = '<option value="">Select item Entry</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $medicine_entry)
        {
           $dropdown .= '<option value="'.$medicine_entry->id.'">'.$medicine_entry->item.'</option>';
        }
      } 
      echo $dropdown; 
  }*/

  public function print_purchase_recipt($ids="")
  {
      $user_detail= $this->session->userdata('auth_users');
      $this->load->model('general/general_model');
      if(!empty($ids))
      {
        $purchase_id= $ids;
      }
      else
      {
        $purchase_id= $this->session->userdata('purchase_id');
      }
      $data['page_title'] = "Add purchase";
      $get_detail_by_id= $this->purchase->get_by_id($purchase_id);

      $get_by_id_data=$this->purchase->get_all_detail_print($purchase_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['mode_payment']);
      $template_format= $this->purchase->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $data['payment_mode']= $get_payment_detail;
      $this->load->view('canteen/purchase/print_template_medicine',$data);

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

  /********** estimate_item**************/

   function estimate_item($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->purchase->estimate_item($vals);  
           // print_r($result);die;
            if(!empty($result))
            {
              //$this->session->set_userdata('item_id',$result); 
          
           echo json_encode($result,true);
            } 
        } 
    }

    public function set_item_stockitem()
   {
       
       $post =  $this->input->post(); 
     // print_r($post['purchase_id']);die('fgf');
       if(isset($post['purchase_id']) && !empty($post['purchase_id']))
       {
        $result = $this->purchase->get_estimate_medicine_by_id($post['purchase_id']);  
         //  echo "<pre>" print_r($result);die;
            if(!empty($result))
            {
              $this->session->set_userdata('item_id',$result); 
            $this->ajax_added_estimate_item();
           //   echo json_encode($result,true);
            }
        }
    }


    public function ajax_added_estimate_item()
    {

        $this->load->model('canteen/stock_item/stock_item_model','stock_item');
         $item_sess = $this->session->userdata('item_id');
   //      echo "<pre>";print_r($item_sess);die;
         $check_script="";
         $result_item = [];
        if(!empty($item_sess))
        { 
          $ids_arr= [];
                
          foreach($item_sess as $key_m_arr=>$m_arr)
          {
             $imp_data = explode(".", $key_m_arr);
             $ids_arr[] = $imp_data[0];
             $batch_arr[] = $imp_data[1];
          }
          //print_r($batch_arr);die;
          //$item_ids = implode(',', $ids_arr); 
         // $batch_nos = implode(',', $batch_arr); 
          //echo $batch_nos;
        //  $result_item = $this->purchase->get_medicine_by_id($key_m_arr->item_id);
          // print_r($result_item);die;
        }
    // die;
       $check_script='';
                        $table='<div class="box_scroll">';
                        $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
                        $table.='<thead class="bg-theme">';
                        $table.='<tr>';
                        $table.='<th class="40" align="center">';
                        $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                        $table.='</th>';
                        $table.='<th>item Name</th>';
                        $table.='<th>item Code</th>';
                        $table.='<th>HSN No.</th>';
                        $table.='<th>Packing</th>';
                        $table.='<th>Batch No.</th>';
                       // $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>Barcode</th>';
                        $table.='<th>Quantity</th>';
                        $table.='<th>Unit1</th>';
                        $table.='<th>Unit2</th>';
                        $table.='<th>Free Unit1</th>';
                        $table.='<th>Free Unit2</th>';
                        $table.='<th>MRP</th>';
                        $table.='<th>P.Rate</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST(%)</th>';
                        $table.='<th>SGST(%)</th>';
                        $table.='<th>IGST(%)</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        //print_r($result_item);die;
                        if(count($item_sess)>0 && isset($item_sess) || !empty($ids)){

                        foreach($item_sess as $item){
                        //  print_r($item['purchase_id']);die;
                             if($item['expiry_date']=="00-00-0000")
                             {

                                $date_new='';
                            }else{
                                $date_new=$item['expiry_date'];
                            }
                       if($item['manuf_date']=="00-00-0000"){

                                                      $date_newmanuf='';
                                                  }else{
                                                      $date_newmanuf=$item['manuf_date'];
                                                  }

                        $varids=$item['id'];

                        $value="'".$item['id']."'";


                          $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$item['id'].$item['batch_no']."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$item['id'].$item['batch_no']."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           $('#discount_".$item['id'].$item['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$item['id'].$item['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$item['id'].$item['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$item['id'].$item['batch_no']."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                          //if(!in_array($item->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="item_id_'.$item['id'].$item['batch_no'].'" name="i_id[]" value="'.$item['id'].'.'.$item['batch_no'].'"/>
                         <input type="hidden" id="mbid_'.$item['id'].$item['batch_no'].'" name="mbid[]" value='.$value.'/>
                     <input type="hidden" id="purchase_rate_mrp'.$item['id'].$item['batch_no'].'" name="purchase_rate_mrp[]" value="'.$item['mrp'].'"/><input type="hidden" id="batch_no_'.$item['id'].$item['batch_no'].'" name="batch_no[]" value="'.$item['batch_no'].'"/><input type="hidden" id="conversion_'.$item['id'].$item['batch_no'].'" name="conversion[]" value="'.$item['conversion'].'"/>';

                        $table.='<td><input type="checkbox" name="item_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$item['item'].'</td>';
                        $table.='<td>'.$item['item_code'].'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$item['id'].$item['batch_no'].'" name="hsn_no[]" value="'.$item['hsn_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$item['packing'].'</td>';
                         $table.='<td>'.$item['batch_no'].'</td>';
                       // $table.='<td>'.$item->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$item['id'].$item['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$item['id'].$item['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text"  value="'.$item['bar_code'].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$item['id'].$item['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$item['id'].$item['batch_no'].'"  style="color:red;"></td>';

                          $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$item['id'].'" value="'.$item["unit1"].'" onkeyup="payment_cal_perrow('.$item['id'].');"/></td>';
                        $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$item['id'].'" value="'.$item["unit2"].'" onkeyup="payment_cal_perrow('.$item['id'].');"/></td>';

                        $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$item['id'].'" value="'.$item["freeunit1"].'" onkeyup="payment_cal_perrow('.$item['id'].');"/></td>';
                        $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$item->id.'" value="'.$item["freeunit2"].'" onkeyup="payment_cal_perrow('.$item['id'].');"/></td>';

                            // $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$item['id'].$item['batch_no'].'" value="'.$item['qty'].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(qty,'.$varids.');"/><div  id="unit1_error_'.$item['id'].$item['batch_no'].'"  style="color:red;"></div></td>';
                        //$table.='<td>'.$item->medicine_unit_2.'</td>';
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$item['id'].$item['batch_no'].'" name="mrp[]" value="'.number_format($item['mrp'],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" id="purchase_rate_'.$item['id'].'" class="w-60px" name="purchase_rate[]" value="'.$item["purchase_amount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$item['id'].$item['batch_no'].'" value="'.$item['discount'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$item['cgst'].'" id="cgst_'.$item['id'].$item['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$item['sgst'].'" id="sgst_'.$item['id'].$item['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$item['igst'].'" id="igst_'.$item['id'].$item['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.=' <td><input type="text" value="'.$item['total_amount'].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$item['id'].$item['batch_no'].'" /></td>';
                       
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
                        $table.='<a class="btn-new" onclick="item_list_vals();">Delete</a>';
                        $table.='</div>'; 
                     $output=array('data'=>$table);
                     echo json_encode($output);
        
    }


    public function get_canteen_vendors()
    {
      $item_id=$_POST['item_id'];
      $all_data=$this->purchase->get_canteen_vendors($item_id);
      if(!empty($all_data))
      {
        $tr='';
         foreach ($all_data as $values) 
         {
          $tr.='<tr onClick="get_purchase_rates('.$values['purchase_rate'].','.$item_id.');">
                  <td>'.$values['item'].'</td>
                  <td>'.$values['name'].'</td>
                  <td>'.date('d-m-Y', strtotime($values['purchase_date'])).'</td>
                  <td>'.$values['purchase_rate'].'</td>
                </tr>';
         }
      }
      echo json_encode($tr);
    }


  function check_unique_value($invoice='', $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->purchase->check_unique_value($users_data['parent_id'], $invoice, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Invoice Number already Registered.');
            $response = false;
        }
        return $response;
    }

  /********** Estimate_Item**************/  


}
