<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_estimate extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('sales_estimate/sales_estimate_model','sales_medicine');
		$this->load->library('form_validation');
    }

    
    public function index()
    {
      unauthorise_permission(342,2069);
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
      $data['form_data'] = array('sale_no'=>'','refered_by'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>$start_date, 'end_date'=>$end_date,'referred_by'=>'','refered_id'=>'','referral_hospital'=>'');
      //print_r($data['doctors_list']);die;
      $data['page_title'] = 'Sales Estimate Medicines'; 
      $this->load->view('sales_estimate/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(342,2069);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->sales_medicine->get_datatables(); 
        $session_data= $this->session->userdata('auth_users');
        $assoc_array = json_decode(json_encode($list),TRUE);
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
        foreach ($list as $sales_medicine) { 
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
            $row[] = '<input type="checkbox" name="sales_medicine[]" class="checklist" value="'.$sales_medicine->id.'">'.$check_script;  
            $row[] = $sales_medicine->sale_no;
            $row[] = $sales_medicine->patient_name;
            
            $row[] = $sales_medicine->doctor_hospital_name;
           // $row[] = $sales_medicine->total_amount;
            $row[] = $sales_medicine->net_amount;
            $row[] = $sales_medicine->paid_amount;
            $row[] = $sales_medicine->balance;
            $row[] = date('d-M-Y',strtotime($sales_medicine->sale_date));  
            
            $btnedit='';
            $btndelete='';
            $btnview = '';
             if($session_data['parent_id']==$sales_medicine->branch_id){
           if(in_array('2066',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_sales_medicine('.$sales_medicine->id.');" class="btn-custom" href="javascript:void(0)" style="'.$sales_medicine->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
           
            if(in_array('2067',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_sales_estimate('.$sales_medicine->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }   
          }
            // $btnprint = '<a class="btn-custom" onclick="openPrintWindow(123,'.$sales_medicine->id.');" target="_blank"><i class="fa fa-print"></i> Print</a>';
            
            $print_url = "'".base_url('sales_estimate/print_sales_report/'.$sales_medicine->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 
            
            
            
           $btnsales = ' <div class="btn-ipd"><a class="btn-custom"  href="'.base_url('sales_medicine/add/0/0/'.$sales_medicine->id).'" style="'.$sales_medicine->id.'" title="Sale Medicine"><i class="fa fa-plus"></i>Sale Medicine</a></div>';

          
            $row[] = $btnedit.$btnview.$btndelete.$btnprint.$btnsales;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_medicine->count_all(),
                        "recordsFiltered" => $this->sales_medicine->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }

    function opd_patient($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->sales_medicine->opd_patient($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }

    public function medicine_sales_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Sale No.','Patient Name','Referred By','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           //$objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
               
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
               //$objPHPExcel->getActiveSheet()->getStyle($col)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
               $col++;
          }
          $list = $this->sales_medicine->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_hospital_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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

                        //$objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                       // $objPHPExcel->getActiveSheet()->getStyle($row)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
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
          header("Content-Disposition: attachment; filename=medicine_sales_report_".time().".xls");
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
          $fields = array('Sale No.','Patient Name','Referred By','Net Amount','Paid Amount','Balance','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->sales_medicine->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->patient_name,$reports->doctor_hospital_name,$reports->net_amount,$reports->paid_amount,$reports->balance, date('d-M-Y H:i A',strtotime($reports->created_date)));
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
          header("Content-Disposition: attachment; filename=medicine_sales_report_".time().".csv");    
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
        $data['data_list'] = $this->sales_medicine->search_report_data();
       $this->load->view('sales_estimate/medicine_sales_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_sales_report_".time().".pdf");
    }
    public function print_medicine_sales()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->sales_medicine->search_report_data();
     $this->load->view('sales_estimate/medicine_sales_report_html',$data); 
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
    {
       $medicine_list = $this->session->userdata('medicine_id');
       //print_r($medicine_list);
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
          
          $data['medicne_new_list'] = $this->sales_medicine->medicine_list($ids_arr,$batch_arr);
           foreach($data['medicne_new_list'] as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }
        $table ='';
        $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
        $keywords= $this->input->post('search_keyword');
         $result_medicine = [];
         $name= $this->input->post('name');
          if(!empty($post['medicine_name']) ||!empty($post['medicine_company']) ||!empty($post['batch_number']) ||!empty($post['bar_code']) || !empty($post['medicine_code']) || !empty($post['qty']) || !empty($post['stock']) || !empty($post['rate']) || !empty($post['packing']) ||!empty($post['discount'])||!empty($post['hsn_no']) ||!empty($post['igst'])||!empty($post['cgst'])||!empty($post['sgst']))
          {  
             $result_medicine = $this->sales_medicine->medicine_list_search();  
          }  
       // print_r($ids);
         if((isset($result_medicine) && !empty($result_medicine)) || !empty($ids)){
          //echo 'fssd';
            foreach($result_medicine as $medicine)
            { 
              //print_r($medicine);
                //if(!in_array($medicine->id,$ids))
               // {
               $qty_data = $this->sales_medicine->get_batch_med_qty($medicine->id,$medicine->batch_no);
                  //echo $medicine->medicine_name;
                $table.='<tr class="append_row">';
                if($qty_data['total_qty']>0)
                  {
                      $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$medicine->id.'.'.$medicine->batch_no.'" onclick="add_check();"></td>';
                      $table.='<td>'.$medicine->medicine_name.'</td>';
                      $table.='<td>'.$medicine->packing.'</td>';
                      $table.='<td>'.$medicine->medicine_code.'</td>';
                       $table.='<td>'.$medicine->hsn_no.'</td>';
                      $table.='<td>'.$medicine->company_name.'</td>';
                      $table.='<td>'.$medicine->batch_no.'</td>';
                      $table.='<td>'.$medicine->bar_code.'</td>';
                      $table.='<td>'.$medicine->min_alrt.'</td>';//$qty_data['total_qty'].
                      $table.='<td>'.$medicine->qty.'</td>';
                      $table.='<td>'.$medicine->mrp.'</td>';
                      $table.='<td>'.$medicine->discount.'</td>';
                      $table.='<td>'.$medicine->cgst.'</td>';
                      $table.='<td>'.$medicine->sgst.'</td>';
                      $table.='<td>'.$medicine->igst.'</td>';

                      $table.='</tr>';
                   }
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

    public function ajax_added_medicine(){
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
          $result_medicine = $this->sales_medicine->medicine_list($ids_arr,$batch_arr);
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
                       // $table.='<th>Unit1</th>';
                        //$table.='<th>Unit2</th>';
                       /* $table.='<th>Free</th>';*/
                        $table.='<th>MRP</th>';
                        //$table.='<th>P.Rate</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST(%)</th>';
                        $table.='<th>SGST(%)</th>';
                        $table.='<th>IGST(%)</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        //print_r($result_medicine);die;
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){

                        foreach($result_medicine as $medicine){
                          //print_r($medicine);
                             if($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["exp_date"]=="00-00-0000")
                             {

                                $date_new='';
                            }else{
                                $date_new=$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["exp_date"];
                            }
                       if($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"]=="00-00-0000"){

                                                      $date_newmanuf='';
                                                  }else{
                                                      $date_newmanuf=$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["manuf_date"];
                                                  }

                        $varids="'".$medicine->id.$medicine->batch_no."'";

                        $value="'".$medicine->id.".".$medicine->batch_no."'";


                          $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine->id.$medicine->batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine->id.$medicine->batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
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
                           $('#igst_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine->id.$medicine->batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                        	//if(!in_array($medicine->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine->id.$medicine->batch_no.'" name="m_id[]" value="'.$medicine->id.'.'.$medicine->batch_no.'"/>
                         <input type="hidden" id="mbid_'.$medicine->id.$medicine->batch_no.'" name="mbid[]" value='.$value.'/>
                     <input type="hidden" id="purchase_rate_mrp'.$medicine->id.$medicine->batch_no.'" name="purchase_rate_mrp[]" value="'.$medicine->mrp.'"/><input type="hidden" id="batch_no_'.$medicine->id.$medicine->batch_no.'" name="batch_no[]" value="'.$medicine->batch_no.'"/><input type="hidden" id="conversion_'.$medicine->id.$medicine->batch_no.'" name="conversion[]" value="'.$medicine->conversion.'"/>';

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$medicine->medicine_name.'</td>';
                        $table.='<td>'.$medicine->medicine_code.'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$medicine->id.$medicine->batch_no.'" name="hsn_no[]" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["hsn_no"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine->packing.'</td>';
                         $table.='<td>'.$medicine->batch_no.'</td>';
                       // $table.='<td>'.$medicine->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'<div id="expiry_error_'.$medicine->id.$medicine->batch_no.'" style="color:red;"></div></td>';

                        $table.='<td><input type="text"  value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$medicine->id.$medicine->batch_no.'"  style="color:red;"></td>';

                            $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["qty"].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(qty,'.$varids.');"/><div  id="unit1_error_'.$medicine->id.$medicine->batch_no.'"  style="color:red;"></div></td>';
                        //$table.='<td>'.$medicine->medicine_unit_2.'</td>';
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$medicine->id.$medicine->batch_no.'" name="mrp[]" value="'.number_format($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["per_pic_amount"],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                       // $table.='<td>'.$medicine->purchase_rate.'</td>';
                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["discount"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["cgst"].'" id="cgst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["sgst"].'" id="sgst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["igst"].'" id="igst_'.$medicine->id.$medicine->batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.=' <td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$medicine->id.$medicine->batch_no.'" /></td>';
                       
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
    function check_bar_code()
      {
        $mbid= $this->input->post('mbid');
        $new_ids= explode('.',$mbid);
        $bar_code= $this->input->post('bar_code');
        $return= $this->sales_medicine->check_bar_code($bar_code,$new_ids[0]);
        /*if(count($return)>0)
        {
          if($return==2)
          {
          echo '1';exit;
          }
          else
          {
          echo '0';exit;
          } 
        }*/
       
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
         //echo "<pre>"; print_r($purchase); die;
         $medicine_id = [];
         $mid_arr = [];

         if(isset($purchase) && !empty($purchase))
         { 
            $total_price_medicine_amount=0;
            foreach($post['medicine_id'] as $m_ids)
            {
                $m_id_arr = explode('.',$m_ids);
                $vat='';
                $medicine_data = $this->sales_medicine->medicine_list($m_id_arr[0],$m_id_arr[1]);
                 // print_r($medicine_data);
                $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
                $tot_qty_with_rate= $per_pic_amount*1;
                
              	$total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
				$total_amount= $tot_qty_with_rate-$total_discount;
				
				//GST
            
            $total_gst_tax = $medicine_data[0]->cgst+$$medicine_data[0]->igst+$medicine_data[0]->sgst;
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
                $igst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $igst_cal_val = 0; 
            }
            
           $total_price_medicine_amount = $total_price_medicine_amount+ ($total_amount);
            
				
				
				/*$cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
				$igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
				$sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;*/
				
				$total_tax= $cgst_cal_val+$igst_cal_val+$sgst_cal_val;
				$total_price_medicine_amount =  $total_price_medicine_amount+$total_amount; //-$total_tax;
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

                //$m_new_array_id[$m_ids]=array('mid'=> $m_id_arr[0],'batch_no'=>$m_id_arr[1]); 
                $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicine_data[0]->discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
                //$mid_arr[] = $m_new_array_id[$m_id_arr[0]];
                

            } 
            
            $medicine_id = $purchase+$post_mid_arr;

         } 
         else
         { 
          $total_price_medicine_amount=0;

            foreach($post['medicine_id'] as $m_ids)
            {

                $m_id_arr = explode('.',$m_ids);

                $medicine_data = $this->sales_medicine->medicine_list($m_id_arr[0],$m_id_arr[1]);
                //print_r($medicine_data);
               
                $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
                //$manuf_date=date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
                //$exp_date=date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
                $tot_qty_with_rate= $per_pic_amount*1;
                if(strtotime($medicine_data[0]->manuf_date)>31536000)
				{
                   $manuf_date=date('d-m-Y',strtotime($medicine_data[0]->manuf_date));
				}
				else
				{
                   $manuf_date='';
				} 

				if(strtotime($medicine_data[0]->expiry_date)>31536000)
				{
                   $exp_date=date('d-m-Y',strtotime($medicine_data[0]->expiry_date));
				}
				else
				{
                   $exp_date='';
				} 
                $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate; 
				$total_amount= $tot_qty_with_rate-$total_discount;
				
				
            $total_gst_tax = $medicine_data[0]->cgst+$$medicine_data[0]->igst+$medicine_data[0]->sgst;
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
                $igst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $igst_cal_val = 0; 
            }
            
           $total_price_medicine_amount = $total_price_medicine_amount+ ($total_amount);
				
				/*$cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
				$igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
				$sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
				$total_tax= $cgstToPay+$igstToPay+$sgstToPay;*/
				
				$total_tax= $cgst_cal_val+$igst_cal_val+$sgst_cal_val;

				$total_price_medicine_amount =  $total_price_medicine_amount+$total_amount; //total_tax 

				$post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0],'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'bar_code'=>$medicine_data[0]->bar_code,'manuf_date'=>$manuf_date,'per_pic_amount'=>$per_pic_amount,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'sale_amount'=>$medicine_data[0]->mrp,'per_pic_amount'=>$per_pic_amount,'discount'=>$medicine_data[0]->discount,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount);
                //print_r($post_mid_arr);die;  
            }
            $medicine_id = $post_mid_arr;
         }  
         $this->session->set_userdata('medicine_id',$medicine_id); 
          //print_r($this->session->userdata('medicine_id'));die;
         $this->ajax_added_medicine();
       }
    }

   public function check_stock_avability()
   {
      $id= $this->input->post('mbid');
      $explode_ids= explode('.',$id);
      $batch_no= $this->input->post('batch_no');
      $conversion= $this->input->post('conversion');
      $unit2= $this->input->post('unit2');
      //if(!empty($batch_no)){
      $return= $this->sales_medicine->check_stock_avability($explode_ids[0],$batch_no);
      $tot_val= $unit2;
      if($return >= $tot_val){
      echo '0'; 
      }else{
      echo '1';

      }
    
}

 public function advance_search()
      {

          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          $data['simulation_list'] = $this->general_model->simulation_list();
          $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
          $data['doctors_list']= $this->general_model->doctors_list();
         
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
                                      "cgst"=>"",
                                      "igst"=>"",
                                      "sgst"=>"",
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
                                      "refered_by"=>"",
                                      "refered_id"=>"",
                                      "refered_hospital"=>"",
                                      "total_amount_from"=>"", 
                                      "status"=>"", 
                                      "bank_name"=>""
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
         $this->load->view('sales_estimate/advance_search',$data);
   }

   public function reset_search()
    {
        $this->session->unset_userdata('sale_search');
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
         $this->ajax_added_medicine();
     }
  } 

	public function add($patient_id='',$ipd_id='')
	{
        //print_r($_POST);die;
        unauthorise_permission(342,2065);
        $users_data = $this->session->userdata('auth_users');
         $pid='';
        if(isset($_GET['reg']))
        {
          $pid= $_GET['reg'];
        }
        if(isset($patient_id) && !empty($patient_id))
        {
          $pid = $patient_id;
        }
        
        if(isset($ipd_id))
        {
           $ipd_id= $ipd_id;
        }
        else
        {
          $ipd_id='';
        }
       
        $sale_no = generate_unique_id(48);
        $this->load->model('general/general_model'); 
        $data['page_title'] = "Add Sales Estimate";
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
        $gender='';
        $simulation_id="";
        $refered_id='';
        $relation_type="";
        $relation_name="";
        $relation_simulation_id="";
        
       if($pid>0)
       {
           if(isset($pid) && !empty($pid))
           {
              $patient = $this->sales_medicine->get_patient_by_id($pid);
           }
           
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
        else
        {
            $patient_id='';
            $patient_reg_code=generate_unique_id(4);
            $medicine_list = $this->session->userdata('medicine_id');
            $data['medicine_id'] = $medicine_list;
        }
        if(!empty($medicine_list))
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
            $data['medicne_new_list'] = $this->sales_medicine->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }

        //print_r( $data['medicne_new_list']);die;
        $data['payment_mode']=$this->general_model->payment_mode();
        $data['simulation_list']= $this->general_model->simulation_list();
        $data['doctors_list']= $this->general_model->doctors_list();
        $this->load->model('opd/opd_model','opd');
        $data['doctors_list'] = $this->opd->referal_doctor_list();
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list(); 
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
    		$data['form_data'] = array(
                                  "patient_id"=>$patient_id,
                                  "data_id"=>"",
                                  'vendor_id'=> $pid,
                                  "patient_reg_code"=>$patient_reg_code,
                                  "name"=>$name,
                                  "gender"=>$gender,
                                  'sales_no'=>$sale_no,
                                  'refered_id'=>$refered_id,
                                  'simulation_id'=>$simulation_id,
                                  "relation_type"=>$relation_type,
                                  "relation_name"=>$relation_name,
                                  "relation_simulation_id"=>$relation_simulation_id,
                                  "mobile"=>$mobile_no,
                                  "data_id"=>"",
                                  "branch_id"=>"",
                                  "sales_date"=>date('d-m-Y'),
                                  'total_amount'=>"0.00",
                                  'discount_amount'=>"0.00",
                                  'payment_mode'=>"",
                                  'sale_date'=>"",
                                  'remarks'=>'',
                                  "field_name"=>'',
                                  'net_amount'=>"0.00",
                                  'igst_amount'=>"0.00",
                                  'cgst_amount'=>"0.00",
                                  'sgst_amount'=>"0.00",
                                  'discount_percent'=>"0",
                                  'payment_date'=>'',
                                  'pay_amount'=>"0.00",
                                  "country_code"=>"+91",
                                  "ipd_id"=>$ipd_id,
                                  'referred_by'=>'',
                                  'referral_hospital'=>'',
                                  'ref_by_other'=>'',
  			                      );
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $salesid=  $this->sales_medicine->save();
                if(!empty($salesid))
                {
                  //check permission
                  $get_by_id_data = $this->sales_medicine->get_by_id($salesid);
                  $get_patient_by_id = $this->sales_medicine->get_patient_by_id($get_by_id_data['patient_id']);
                  //print_r($get_by_id_data); exit;
                  $patient_name = $get_patient_by_id['patient_name'];
                  $mobile_no = $get_patient_by_id['mobile_no'];
                  $patient_email = $get_patient_by_id['patient_email'];
                  //print_r($get_by_id_data); exit;
                  $sale_no = $get_by_id_data['sale_no'];
                  $paid_amount = $get_by_id_data['paid_amount'];
                  $net_amount = $get_by_id_data['net_amount'];
                  if(in_array('640',$users_data['permission']['action']))
                  {
                    
                    if(!empty($mobile_no))
                    {
                      send_sms('sale_medicine',4,$patient_name,$mobile_no,array('{Name}'=>$patient_name,'{Amt}'=>$net_amount,'{BillNo}'=>$sale_no,'{PaidAmt}'=>$paid_amount)); 
                    }

                   }

                  if(in_array('641',$users_data['permission']['action']))
                  {
                    if(!empty($patient_email))
                    {
                      
                      $this->load->library('general_functions');
                      $this->general_functions->email($patient_email,'','','','','1','sale_medicine','4',array('{Name}'=>$patient_name,'{Amt}'=>$net_amount,'{BillNo}'=>$sale_no,'{PaidAmt}'=>$paid_amount));
                       
                    }
                  } 

                }
                 //print_r($salesid); exit;
                $this->session->set_userdata('sales_id',$salesid);
                $this->session->set_flashdata('success','Sales Estimate Medicines has been successfully added.');
                redirect(base_url('sales_estimate/add/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
     $this->load->view('sales_estimate/add',$data);
	}

  public function edit($id="")
  {
      unauthorise_permission(342,2066);
      $this->load->model('general/general_model'); 
         
      if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->sales_medicine->get_by_id($id); 
         $medicine_id_arr=[];
         $result_patient = $this->sales_medicine->get_patient_by_id($result['patient_id']);
         
         if(empty($post))
         { 
            $result_medince_list = $this->sales_medicine->get_medicine_by_sales_id($id,$result['total_amount']);
            $this->session->set_userdata('medicine_id',$result_medince_list);
         }
         $medicine_list = $this->session->userdata('medicine_id');
         $data['medicine_id'] = $medicine_list;
         $data['id'] = $id;
        
        if(!empty($medicine_list))
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
          $data['medicne_new_list'] = $this->sales_medicine->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }
          $data['simulation_list']= $this->general_model->simulation_list();
          $this->load->model('opd/opd_model','opd');
          $data['doctors_list'] = $this->opd->referal_doctor_list();
          //print '<pre>';print_r($data['medicne_new_list']);
          $reg_no = generate_unique_id(10);
          $this->load->model('general/general_model');
          $data['payment_mode']=$this->general_model->payment_mode();
          $get_payment_detail= $this->sales_medicine->payment_mode_detail_according_to_field($result['payment_mode'],$id);
          $total_values='';
          for($i=0;$i<count($get_payment_detail);$i++) {
          $total_values[]= $get_payment_detail[$i]->field_value.'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->field_id;

        }
        $data['referal_hospital_list'] = $this->general_model->referal_hospital_list(); 
        $data['gardian_relation_list'] = $this->general_model->gardian_relation_list();
        //$data['manuf_company_list'] = $this->purchase->manuf_company_list();
        $data['page_title'] = "Edit Sales Estimate";  
        $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                          "patient_id"=>$result['patient_id'],
                          "ipd_id"=>$result['ipd_id'],
                          "patient_reg_code"=>$result_patient['patient_code'],
                          "name"=>$result_patient['patient_name'],
                          "gender"=>$result_patient['gender'],
                          'sales_no'=>$result['sale_no'],
                          "mobile"=>$result_patient['mobile_no'],
                          'refered_id'=>$result['refered_id'],
                          'simulation_id'=>$result['simulation_id'],
                          "relation_type"=>$result_patient['relation_type'],
                          "relation_name"=>$result_patient['relation_name'],
                          "relation_simulation_id"=>$result_patient['relation_simulation_id'],
                          "data_id"=>$result['id'],
                          "remarks"=>$result['remarks'],
                          "branch_id"=>$result['branch_id'],
                          "sales_date"=>date('d-m-Y',strtotime($result['sale_date'])),
                          'total_amount'=>$result['total_amount'],
                          'discount_amount'=>$result['discount'],
                          'payment_mode'=>$result['payment_mode'],
                          "field_name"=>$total_values,
                          'net_amount'=>$result['net_amount'],
                          'igst_amount'=>$result['igst'],
                          'sgst_amount'=>$result['sgst'],
                          'cgst_amount'=>$result['cgst'],
                          'pay_amount'=>$result['paid_amount'],
                          'discount_percent'=>$result['discount_percent'],
                          "country_code"=>"+91",
                          'referred_by'=>$result['referred_by'],
                          'referral_hospital'=>$result['referral_hospital'],
                          'ref_by_other'=>$result['ref_by_other'],
                          );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $salesid=  $this->sales_medicine->save();
                $this->session->set_userdata('sales_id',$salesid);
                $this->session->set_flashdata('success','Sales Estimate Medicines has been successfully updated.');
                redirect(base_url('sales_estimate/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
     //print '<pre>'; print_r($data);die;
       $this->load->view('sales_estimate/add',$data);  

      }
    }
    
   public function payment_calc_all()
    { 
       $medicine_list = $this->session->userdata('medicine_id');
       //echo "<pre>"; print_r($medicine_list); die;
       if(!empty($medicine_list))
       {
        $post = $this->input->post();
        $total_amount = 0;
        $total_vat = 0;
        $total_discount =0;
        $net_amount =0;  
        //$total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        //$totigst_amount=0;
       // $totcgst_amount=0;
       // $totsgst_amount=0;
        $total_discount =0;
        $totsgst_amount=0;
        $net_amount =0;  
        $purchase_amount=0;
        $newamountwithigst=0;
        $newamountwithcgst=0;
        $newamountwithsgst=0;
        $total_new_amount=0;
        $tot_discount_amount=0;
         $payamount=0;
         $i=0;
        //$total_new_other_amount=0;
        foreach($medicine_list as $medicine)
        {    

            //print_r($medicine_list);die;
            $per_pic_amount= $medicine['per_pic_amount'];
            $tot_qty_with_rate= $per_pic_amount*$medicine['qty']; 
            $total_new_other_amount= $tot_qty_with_rate-($tot_qty_with_rate/100)*$medicine['discount'];
            
            
            $row_disc = ($tot_qty_with_rate)/100*$medicine['discount'];
            $total_new_amount= $total_new_amount+$total_new_other_amount;//$medicine['total_pricewith_medicine'];
           
            
            //GST
            $total_gst_tax = $medicine['cgst']+$medicine['igst']+$medicine['sgst']; 
            //$totcgstToPay_init = ($total_new_amount*(100/(100+($total_gst_tax)/100)));
            //$totcgstToPay = ($total_new_amount -$totcgstToPay_init)*100;
            
            $totcgstToPay = $total_new_other_amount-($total_new_other_amount/((100+$total_gst_tax)/100));
            
            //echo $totcgstToPay; die;
            if($medicine['cgst']>0)
            {
                $cgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $cgst_cal_val = 0; 
            }
            
            if($medicine['sgst']>0)
            {
                $sgst_cal_val = $totcgstToPay/2;
            }
            else
            {
               $sgst_cal_val = 0; 
            }
            
            if($medicine['igst']>0)
            {
                $igst_cal_val = $totcgstToPay;
            }
            else
            {
               $igst_cal_val = 0; 
            }
            
            
           $total_price_medicine_amount = $total_price_medicine_amount+ ($total_amount);
            //GST END
            
           /* $newamountwithsgst=$newamountwithsgst+ $sgst_cal_val; 
            $newamountwithcgst = $newamountwithcgst+$cgst_cal_val;
            $newamountwithigst = $newamountwithigst+$igst_cal_val;*/
            
            $sgst_tot +=$sgst_cal_val;
            $cgst_tot +=$cgst_cal_val;
            $igst_tot +=$igst_cal_val;
            //echo $tot_qty_with_rate;
             $tot_discount_amount+= ($tot_qty_with_rate)/100*$medicine['discount']; 
              $i++;
        } 
        
        
    
            if($post['discount']!='' && $post['discount']!=0)
            {
            $total_discount = ($post['discount']/100)*$total_new_amount;}
            else{
               $total_discount=0;
            }
            $net_amount = ($total_new_amount-$total_discount); //+$newamountwithsgst+$newamountwithcgst+$newamountwithigst;


           
            if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=0;
            }

            //echo $total_new_amount; die;
            
             $blance_dues=$net_amount -$payamount;
             
             $blance_due = number_format($blance_dues,2,'.','');
            //$blance_due=intval($net_amount -$payamount);
           // $blance_due = number_format($blance_due);
            $newamountwithsgst = number_format($sgst_tot,2,'.','');
            $newamountwithcgst = number_format($cgst_tot,2,'.','');
            $newamountwithigst = number_format($igst_tot,2,'.','');
            $payamount = number_format($payamount,2,'.','');


         $pay_arr = array('total_amount'=>number_format($total_new_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>$blance_due,'discount'=>$post['discount'],'sgst_amount'=>$newamountwithsgst,'igst_amount'=>$newamountwithigst,'cgst_amount'=> $newamountwithcgst,'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

    public function payment_cal_perrow()
    {
        $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
        $post = $this->input->post();
        $total_price_medicine_amount=0;
        $total_amount=0;
        $cgstToPay=0;
        $total_tax=0;
        //echo '<pre>'; print_r($post);
       if(isset($post) && !empty($post))
       {
          $total_amount = 0;
          $medicine_list = $this->session->userdata('medicine_id');
          //print_r($medicine_list);die;
         if(!empty($medicine_list))
         {
            $medicine_id_new= explode('.',$post['medicine_id']);
            $medicine_data = $this->sales_medicine->medicine_list($medicine_id_new[0],$medicine_id_new[1]);
           // print_r($medicine_data);die;
            $per_pic_amount= $post['mrp'];
            $tot_qty_with_rate= $per_pic_amount*$post['qty'];
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $total_amount= $tot_qty_with_rate-$total_discount;
            
            $row_total_amount= $tot_qty_with_rate-$total_discount;
            
            //GST 
            
            $total_gst_tax = $post['cgst']+$post['igst']+$post['sgst'];
            //$totcgstToPay_init = ($total_amount*(100/(100+($total_gst_tax)/100)));
            //$totcgstToPay = ($total_amount -$totcgstToPay_init)*100;
            
            $totcgstToPay = $row_total_amount-($row_total_amount/((100+$total_gst_tax)/100));
            //echo $totcgstToPay; die;
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
            
            //ENd GST
            
           
             $medicine_list[$post['mbid']] =  array('mid'=>$medicine_id_new[0], 'qty'=>$post['qty'],'batch_no'=>$medicine_id_new[1],'bar_code'=>$post['bar_code'],'manuf_date'=>$post['manuf_date'],'exp_date'=>$post['expiry_date'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'hsn_no'=>$post['hsn_no'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'sale_amount'=>$post['mrp'],'per_pic_amount'=>$per_pic_amount,'conversion'=>$post['conversion'],'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
            $this->session->set_userdata('medicine_id', $medicine_list);
            $pay_arr = array('total_amount'=>number_format($total_price_medicine_amount,2));
            $json = json_encode($pay_arr,true);
            //echo $this->session->userdata('sale_gross_amount');
            echo $json;
         }
       }
    }
    private function _validate()
    {

        $post = $this->input->post();
        //print '<pre>'; print_r($post['field_name']);
        $total_values=array();
        if(isset($post['field_name']))
        {
            $count_field_names= count($post['field_name']);  
            $get_payment_detail= $this->general_model->payment_mode_detail($post['payment_mode']);
            for($i=0;$i<$count_field_names;$i++) 
            {
              $total_values[]= $post['field_name'][$i].'_'.$get_payment_detail[$i]->field_name.'_'.$get_payment_detail[$i]->id;

            }
        }
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('name', 'patient name', 'trim|required');
        $this->form_validation->set_rules('simulation_id', 'simulation', 'trim|required');
        if($post['referred_by']=='0')
        {
          $this->form_validation->set_rules('refered_id', 'referred by', 'trim|required');
        }
        else
        {
          $this->form_validation->set_rules('referral_hospital', 'referred by hospital', 'trim|required');  
        }
        
        $this->form_validation->set_rules('pay_amount', 'pay amount', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        if(isset($post['field_name']))
        {
          $this->form_validation->set_rules('field_name[]', 'field', 'trim|required'); 
        }
        $this->form_validation->set_rules('mobile', 'mobile no.', 'trim|numeric|min_length[10]|max_length[10]');
       
        if ($this->form_validation->run() == FALSE) 
        {  
            $sale_no = generate_unique_id(48); 
            $patient_code = generate_unique_id(4); 
          
          $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
									                  "patient_id"=>$_POST['patient_id'], 
                                    'patient_code'=>$patient_code,
                                    "sales_no"=>$sale_no,
                                    "name"=>$_POST['name'],
                                    "patient_reg_code"=>$post['patient_reg_code'],
                                    'refered_id'=>$_POST['refered_id'],
                                    "gender"=>$post['gender'],
                                    'simulation_id'=>$_POST['simulation_id'],
                                    "mobile"=>$_POST['mobile'],
                                    'total_amount'=>$_POST['total_amount'],
                                    "sales_date"=>$_POST['sales_date'],
                                    'discount_amount'=>$_POST['discount_amount'],
                                    'payment_mode'=>$_POST['payment_mode'],
                                    "relation_type"=>$_POST['relation_type'],
                                    "relation_name"=>$_POST['relation_name'],
                                    "relation_simulation_id"=>$_POST['relation_simulation_id'],
                                    "field_name"=>$total_values,
                                    'remarks'=>$_POST['remarks'],
                                    'net_amount'=>$_POST['net_amount'],
                                    'igst_amount'=>$_POST['igst_amount'],
                                    'sgst_amount'=>$_POST['sgst_amount'],
                                    'cgst_amount'=>$_POST['cgst_amount'],
                                    'pay_amount'=>$_POST['pay_amount'],
                                    'discount_percent'=>$_POST['discount_percent'],
                                    "country_code"=>"+91",
                                    'ipd_id'=>$post['ipd_id'],
                                    'referred_by'=>$post['referred_by'],
                                    'referral_hospital'=>$post['referral_hospital'],
                                    'ref_by_other'=>$post['ref_by_other'],
                                   );  
            return $data['form_data'];
        }


    }
 
    public function delete($id="")
    {
        unauthorise_permission(342,2067);
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_medicine->delete($id);
           $response = "Sales Estimate Medicines successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(342,2067);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_medicine->deleteall($post['row_id']);
            $response = "Sales Estimate Medicines successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        //unauthorise_permission(60,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_name']." detail";
       $this->load->view('sales_estimate/view',$data);     
      }
    }  


   

  

  


    public function trash($id="")
    {
        unauthorise_permission(60,404);
        $this->load->model('sales_medicine/sales_medicine_archive_model','sales_medicine_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_medicine_archive->trash($id);
           $response = "Sales Estimate Medicines  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(60,404);
        $this->load->model('sales_medicine/sales_medicine_archive_model','sales_medicine_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_medicine_archive->trashall($post['row_id']);
            $response = "Sales Estimate Medicines successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function sales_medicine_dropdown()
  {
      $doctor_list = $this->sales_medicine->doctor_list();
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

  public function print_sales_report($ids="")
  {
     $user_detail= $this->session->userdata('auth_users');
     //print '<pre>';print_r($user_detail);die;
      $this->load->model('general/general_model');
      $data['page_title'] = "Add Sales Estimate Medicines";
      if(!empty($ids)){
        $sales_id= $ids;
     }else{
       $sales_id= $this->session->userdata('sales_id');
     }
      $get_detail_by_id= $this->sales_medicine->get_by_id($sales_id);

      $get_by_id_data=$this->sales_medicine->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->sales_medicine->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>2,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['payment_mode']= $get_payment_detail;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
     $this->load->view('sales_estimate/print_template_medicine',$data);
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



  public function set_prescription_medicine()
  {
       $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post =  $this->input->post(); 
       $post_mid_arr = [];
       $m_new_array_id=[];
       if(isset($post['prescription_id']) && !empty($post['prescription_id']))
       {
         $purchase = $this->session->userdata('medicine_id');
         $medicine_id = [];
         $mid_arr = [];

         if(isset($purchase) && !empty($purchase))
         { /* 
            $total_price_medicine_amount=0;
            foreach($post['medicine_id'] as $m_ids)
            {
                $m_id_arr = explode('.',$m_ids);
                $vat='';
                $medicine_data = $this->sales_medicine->medicine_list($m_id_arr[0],$m_id_arr[1]);
                 // print_r($medicine_data);
                $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
                $tot_qty_with_rate= $per_pic_amount*1;
                
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

                //$m_new_array_id[$m_ids]=array('mid'=> $m_id_arr[0],'batch_no'=>$m_id_arr[1]); 
                $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0], 'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicine_data[0]->discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
                //$mid_arr[] = $m_new_array_id[$m_id_arr[0]];
                

            } 
            
            $medicine_id = $purchase+$post_mid_arr;

         */ 
          }
         else
         {

            $this->load->model('prescription/prescription_model','prescription');
            $get_by_id_data = $this->prescription->get_by_prescription_id($post['prescription_id']);
            $prescription_presc_list = $get_by_id_data['prescription_list']['prescription_presc_list'];


            $medicine_id = [];
         $mid_arr = [];

        if(isset($prescription_presc_list) && !empty($prescription_presc_list))
         { 
            $total_price_medicine_amount=0;
            foreach($prescription_presc_list as $prescription_medicine)
            {  ///done
                $vat='';
                $medicine_data = $this->sales_medicine->prescription_medicine_list($prescription_medicine->medicine_id);
                //echo "<pre>";print_r($medicine_data); exit;
                if($medicine_data[0]->mrp>0)
                {
                    $per_pic_amount= $medicine_data[0]->mrp/$medicine_data[0]->conversion;
                    $tot_qty_with_rate= $per_pic_amount*1;

                    $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;
                    $total_amount= $tot_qty_with_rate-$total_discount;
                    $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
                    $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
                    $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
                    $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                    $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
                }
                else
                {
                     $per_pic_amount= '';
                    $total_discount ='';
                    $total_amount= '';
                    $cgstToPay = '';
                    $igstToPay = '';
                    $sgstToPay = '';
                    $total_tax='';
                    $total_price_medicine_amount='';
                }
                if(!empty($medicine_data[0]->batch_no))
                {
                  $batch_no = $medicine_data[0]->batch_no;
                }
                else
                {
                  $batch_no = 0;
                }
                $manuf_date='';
                $exp_date='';
               $post_mid_arr[$prescription_medicine->medicine_id.'.'.$batch_no] = array('mid'=>$prescription_medicine->medicine_id, 'batch_no'=>$batch_no, 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'discount'=>$medicine_data[0]->discount,'bar_code'=>$medicine_data[0]->bar_code,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn_no,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
                //$mid_arr[] = $m_new_array_id[$m_id_arr[0]];
                

            } 
            //print_r($post_mid_arr); exit;
            if(!empty($pres_purchase))
            {
              $medicine_id = $pres_purchase+$post_mid_arr;
            }
            else
            {
              $medicine_id = $post_mid_arr;  
            }
            

         }
         
                 
         }
         //print_r($medicine_id); exit;
         $this->session->set_userdata('medicine_id',$medicine_id); 
         $this->ajax_added_prescription_medicine();
       }
    }


    public function ajax_added_prescription_medicine()
    {
        $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
        $medicine_sess = $this->session->userdata('medicine_id');
        //echo "<pre>"; print_r($medicine_sess);die;
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
          //print_r($ids_arr);die; 
          $result_medicine = $this->sales_medicine->medicine_list($ids_arr,$batch_arr);
        }
        //print_r($medicine_sess);die;
        //echo "<pre>"; print_r($result_medicine);die; 
       
        if(!empty($prescription_medicine_id))
        { 
          $priscription_medicine_arr= [];
          foreach($prescription_medicine_id as $key_m_arr=>$m_arr)
          {
             $priscription_medicine_arr[] = $key_m_arr;
          }
          $result_medicine = $this->sales_medicine->prescription_medicine_array_list($priscription_medicine_arr);
        }
        
        //print_r($result_medicine);die;
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
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                        $table.='<th>Barcode</th>';
                        $table.='<th>Quantity</th>';
                        $table.='<th>MRP</th>';
                        $table.='<th>Discount(%)</th>';
                        $table.='<th>CGST(%)</th>';
                        $table.='<th>SGST(%)</th>';
                        $table.='<th>IGST(%)</th>';
                        $table.='<th>Total</th>';
                        $table.='</tr>';
                        $table.='</thead>';
                        $table.='<tbody>';
                        //print_r($result_medicine);die;
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){

                        foreach($result_medicine as $medicine)
                        {
                          $batch_no = '0';
                          if(!empty($medicine->batch_no))
                          {
                            $batch_no = $medicine->batch_no;
                          }
                             if($medicine_sess[$medicine->id.'.'.$batch_no]["exp_date"]=="00-00-0000")
                             {

                                $date_new='';
                            }else{
                                $date_new=$medicine_sess[$medicine->id.'.'.$batch_no]["exp_date"];
                            }
                       if($medicine_sess[$medicine->id.'.'.$batch_no]["manuf_date"]=="00-00-0000"){

                                                      $date_newmanuf='';
                                                  }else{
                                                      $date_newmanuf=$medicine_sess[$medicine->id.'.'.$batch_no]["manuf_date"];
                                                  }

                        $varids="'".$medicine->id.$batch_no."'";

                        $value="'".$medicine->id.".".$batch_no."'";


                          $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine->id.$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine->id.$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           $('#discount_".$medicine->id.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$medicine->id.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine->id.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine->id.$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                          //if(!in_array($medicine->id,$ids)){ 
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine->id.$batch_no.'" name="m_id[]" value="'.$medicine->id.'.'.$batch_no.'"/>
                         <input type="hidden" id="mbid_'.$medicine->id.$batch_no.'" name="mbid[]" value='.$value.'/>
                     <input type="hidden" id="purchase_rate_mrp'.$medicine->id.$batch_no.'" name="purchase_rate_mrp[]" value="'.$medicine->mrp.'"/><input type="hidden" id="batch_no_'.$medicine->id.$batch_no.'" name="batch_no[]" value="'.$batch_no.'"/><input type="hidden" id="conversion_'.$medicine->id.$batch_no.'" name="conversion[]" value="'.$medicine->conversion.'"/>';

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$medicine->medicine_name.'</td>';
                        $table.='<td>'.$medicine->medicine_code.'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$medicine->id.$batch_no.'" name="hsn_no[]" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["hsn_no"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine->packing.'</td>';
                         $table.='<td>'.$batch_no.'</td>';
                       // $table.='<td>'.$medicine->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine->id.$batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.$batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text"  value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$medicine->id.$batch_no.'"  style="color:red;"></td>';

                            $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine->id.$batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["qty"].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(qty,'.$varids.');"/><div  id="unit1_error_'.$medicine->id.$batch_no.'"  style="color:red;"></div></td>';
                        //$table.='<td>'.$medicine->medicine_unit_2.'</td>';
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$medicine->id.$batch_no.'" name="mrp[]" value="'.number_format($medicine_sess[$medicine->id.'.'.$batch_no]["per_pic_amount"],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                       // $table.='<td>'.$medicine->purchase_rate.'</td>';
                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$medicine->id.$batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["discount"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["cgst"].'" id="cgst_'.$medicine->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["sgst"].'" id="sgst_'.$medicine->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
                        $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["igst"].'" id="igst_'.$medicine->id.$batch_no.'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.=' <td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$batch_no]["total_amount"].'" name="total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'.$medicine->id.$batch_no.'" /></td>';
                       
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

}
