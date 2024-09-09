<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_indent extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('sales_indent/sales_indent_model','sales_indent');
		$this->load->library('form_validation');
    }

    
    public function index()
    {
      unauthorise_permission(60,399);
      $this->session->unset_userdata('medicine_id'); 
      $this->session->unset_userdata('sale_search'); 
      $this->session->unset_userdata('net_values_all');
      $this->load->model('general/general_model'); 
      $data['doctors_list']= $this->general_model->doctors_list();
      $data['referal_hospital_list'] = $this->general_model->referal_hospital_list();
     
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
      $data['page_title'] = 'Indent Sale'; 
      $this->load->view('sales_indent/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(60,399);
        $users_data = $this->session->userdata('auth_users');
        $list = $this->sales_indent->get_datatables(); 
        $session_data= $this->session->userdata('auth_users');
        $assoc_array = json_decode(json_encode($list),TRUE);

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $sales_indent) { 
            $no++;
            $row = array();
        
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
            $row[] = '<input type="checkbox" name="sales_indent[]" class="checklist" value="'.$sales_indent->id.'">'.$check_script;  
            $row[] = $sales_indent->sale_no;
            $row[] = $sales_indent->indent;
            $row[] = date('d-M-Y',strtotime($sales_indent->sale_date));  
            
            $btnedit='';
            $btndelete='';
            $btnview = '';
             if($session_data['parent_id']==$sales_indent->branch_id){
           if(in_array('401',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_sales_indent('.$sales_indent->id.');" class="btn-custom" href="javascript:void(0)" style="'.$sales_indent->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
       
            if(in_array('402',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_sales_indent('.$sales_indent->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }   
          }
           
            
            $print_url = "'".base_url('sales_indent/print_sales_report/'.$sales_indent->id)."'";
            $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 

            $lettherhead_print_url = "'".base_url('sales_indent/letterhead_print_sales/'.$sales_indent->id)."'";
            $btnlettherhead_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$lettherhead_print_url.')" title="Print" ><i class="fa fa-print"></i>Letterhead print</a>'; 

            $row[] = $btnedit.$btnview.$btndelete.$btnprint;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_indent->count_all(),
                        "recordsFiltered" => $this->sales_indent->count_filtered(),
                        "data" => $data,
                );

        echo json_encode($output);
    }

    function opd_patient($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->sales_indent->opd_patient($vals);  
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
          $fields = array('Issue No.','Indent Name','Issue Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
         
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
               $col++;
          }
          $list = $this->sales_indent->search_report_data();

          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->indent, date('d-M-Y',strtotime($reports->sale_date)));
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
          header("Content-Disposition: attachment; filename=indent_sales_report_".time().".xls");
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
          $fields = array('Issue No.','Indent Name','Issue Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->sales_indent->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->indent, date('d-M-Y',strtotime($reports->sale_date)));
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
          header("Content-Disposition: attachment; filename=indent_sales_report_".time().".csv");    
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
        $data['data_list'] = $this->sales_indent->search_report_data();
        $this->load->view('sales_indent/medicine_sales_report_html',$data);
        $html = $this->output->get_output();
        $this->load->library('pdf');
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("indent_sales_report_".time().".pdf");
    }
    public function print_medicine_sales()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->sales_indent->search_report_data();
      $this->load->view('sales_indent/medicine_sales_report_html',$data); 
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
          
          $data['medicne_new_list'] = $this->sales_indent->medicine_list($ids_arr,$batch_arr);
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
             $result_medicine = $this->sales_indent->medicine_list_search();  
          }  
       // print_r($ids);
         if((isset($result_medicine) && !empty($result_medicine)) || !empty($ids)){
          //echo 'fssd';
            foreach($result_medicine as $medicine)
            { 
              //print_r($medicine);
                //if(!in_array($medicine->id,$ids))
               // {
               $qty_data = $this->sales_indent->get_batch_med_qty($medicine->id,$medicine->batch_no);
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
          $result_medicine = $this->sales_indent->medicine_list($ids_arr,$batch_arr);
        }
 
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
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){

                        foreach($result_medicine as $medicine){
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

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

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
        $return= $this->sales_indent->check_bar_code($bar_code,$new_ids[0]);
       
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
            $total_price_medicine_amount=0;
            foreach($post['medicine_id'] as $m_ids)
            {
                $m_id_arr = explode('.',$m_ids);
                $vat='';
                $medicine_data = $this->sales_indent->medicine_list($m_id_arr[0],$m_id_arr[1]);
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

         } 
         else
         { 
          $total_price_medicine_amount=0;

            foreach($post['medicine_id'] as $m_ids)
            {

                $m_id_arr = explode('.',$m_ids);

                $medicine_data = $this->sales_indent->medicine_list($m_id_arr[0],$m_id_arr[1]);
                //print_r($medicine_data);
               
                $per_pic_amount= $medicine_data[0]->m_rp/$medicine_data[0]->conv;
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
				$cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
				$igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
				$sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
				$total_tax= $cgstToPay+$igstToPay+$sgstToPay;

				$total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax; 

				$post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0],'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'bar_code'=>$medicine_data[0]->bar_code,'manuf_date'=>$manuf_date,'per_pic_amount'=>$per_pic_amount,'conversion'=>$medicine_data[0]->conversion,'hsn_no'=>$medicine_data[0]->hsn,'sale_amount'=>$medicine_data[0]->mrp,'per_pic_amount'=>$per_pic_amount,'discount'=>$medicine_data[0]->discount,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount);
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
      $return= $this->sales_indent->check_stock_avability($explode_ids[0],$batch_no);
      $tot_val= $unit2;
      if($return >= $tot_val){
      echo '0'; 
      }else{
      echo '1';

      }
    
}

 public function advance_search()
      {
         
          $data['page_title'] = "Advance Search";
         
          $post = $this->input->post();
      
         
          $data['form_data'] = array(
                                      "start_date"=>"",
                                      "end_date"=>"",
                                      "indent_name"=>'',
                                      "sale_no"=>"",
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
                                      "status"=>"", 
                                      "bank_name"=>""
                                    );
          if(isset($post) && !empty($post))
          {
             $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('sale_search', $marge_post);
             
          }
          $purchase_search = $this->session->userdata('sale_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('sales_indent/advance_search',$data);
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
        unauthorise_permission(60,400);
        $users_data = $this->session->userdata('auth_users');
        $pid='';
        $sale_no = generate_unique_id(51);

        $data['page_title'] = "Add Indent Sale";
        $data['form_error'] = [];
        $data['button_value'] = "Save";
        $post = $this->input->post();
       
        $medicine_list = $this->session->userdata('medicine_id');
        $data['medicine_id'] = $medicine_list;
        if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          foreach($medicine_list as $key=>$medicine_sess)
          {
              $imp_key = explode('.', $key);
              $medicine_id_arr[] = $imp_key[0];
              $medicine_batch_arr[] = $imp_key[1];
          } 
            $medicine_ids = implode(',', $medicine_id_arr);
            $medicine_batchs = implode(',', $medicine_batch_arr);
            $data['medicne_new_list'] = $this->sales_indent->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }

        $data['indent_list']= $this->sales_indent->testing($users_data['parent_id']);

        $this->load->model('opd/opd_model','opd');
    		$data['form_data'] = array(
                                  "data_id"=>"",
                                  'indent_id'=> $pid,                           
                                  'sales_no'=>$sale_no,                                 
                                  "branch_id"=>"",
                                  "sales_date"=>date('d-m-Y'),
                                  'remarks'=>'',
  			                      );
        if(isset($post) && !empty($post))
        {   
            
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $salesid=  $this->sales_indent->save();
                if(!empty($salesid))
                {
                  $get_by_id_data = $this->sales_indent->get_by_id($salesid);
            
                  $sale_no = $get_by_id_data['sale_no'];

                }
                $this->session->set_userdata('sales_id',$salesid);
                $this->session->set_flashdata('success','Sales indent has been successfully added.');
                redirect(base_url('sales_indent/add/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
      $this->load->view('sales_indent/add',$data);
	}

	public function edit($id="")
  {
      unauthorise_permission(60,401);
      $users_data = $this->session->userdata('auth_users');
      $this->load->model('general/general_model'); 
         
      if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->sales_indent->get_by_id($id); 
         $medicine_id_arr=[];
                 
         if(empty($post))
         { 
            $result_medince_list = $this->sales_indent->get_medicine_by_sales_id($id);
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
          $medicine_ids = implode(',', $medicine_id_arr);
          $medicine_batchs = implode(',', $medicine_batch_arr);
          $data['medicne_new_list'] = $this->sales_indent->medicine_list($medicine_id_arr,$medicine_batch_arr);
        }

        }

        $data['indent_list']= $this->sales_indent->testing($users_data['parent_id']);
        $data['page_title'] = "Edit Indent Sale";  
        $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        $data['form_data'] = array(                        
                          "indent_id"=>$result['indent_id'],
                          'sales_no'=>$result['sale_no'],                        
                          "data_id"=>$result['id'],
                          "remarks"=>$result['remarks'],
                          "branch_id"=>$result['branch_id'],
                          "sales_date"=>date('d-m-Y',strtotime($result['sale_date'])),
                          );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $salesid=  $this->sales_indent->save();
                $this->session->set_userdata('sales_id',$salesid);
                $this->session->set_flashdata('success','Sales medicine has been successfully updated.');
                redirect(base_url('sales_indent/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
        $this->load->view('sales_indent/add',$data);  
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
            $total_new_amount= $total_new_amount+$medicine['total_pricewith_medicine'];
            
            $totigst_amount = $medicine['igst'];
            $total_amountwithigst= ($total_new_other_amount/100)*$totigst_amount;

            $newamountwithigst=$newamountwithigst+ $total_amountwithigst;

           // echo $newamountwithigst;
          /* amount with cgst */

           
            $totcgst_amount = $medicine['cgst'];
            $total_amountwithcgst= ($totcgst_amount/100)*$total_new_other_amount;
          //echo $total_amountwithcgst;
            //echo $total_amountwithcgst;
            $newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
          // echo $newamountwithcgst;
            /* amount with cgst */


            $totsgst_amount = $medicine['sgst']; 
            $total_amountwithsgst= ($total_new_other_amount/100)*$totsgst_amount; 
            $newamountwithsgst=$newamountwithsgst+ $total_amountwithsgst; 
            //echo $tot_qty_with_rate;
             $tot_discount_amount+= ($tot_qty_with_rate)/100*$medicine['discount']; 
              $i++;
        } 
     
            if($post['discount']!='' && $post['discount']!=0){
            $total_discount = ($post['discount']/100)*$total_new_amount;}
            else{
               $total_discount=$tot_discount_amount;
            }
            $net_amount = ($total_new_amount-$total_discount)+$newamountwithsgst+$newamountwithcgst+$newamountwithigst;


           
            if($post['pay']==1 || $post['data_id']!=''){
            $payamount=$post['pay_amount'];
            }else{
            $payamount=$net_amount;
            }

            //echo $net_amount;
            //echo $payamount;
            
             $blance_dues=$net_amount -$payamount;
             
             $blance_due = number_format($blance_dues,2,'.','');
            //$blance_due=intval($net_amount -$payamount);
           // $blance_due = number_format($blance_due);
            $newamountwithsgst = number_format($newamountwithsgst,2,'.','');
            $newamountwithcgst = number_format($newamountwithcgst,2,'.','');
            $newamountwithigst = number_format($newamountwithigst,2,'.','');
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
            $medicine_data = $this->sales_indent->medicine_list($medicine_id_new[0],$medicine_id_new[1]);
           // print_r($medicine_data);die;
            $per_pic_amount= $post['mrp'];
            $tot_qty_with_rate= $per_pic_amount*$post['qty'];
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $total_amount= $tot_qty_with_rate-$total_discount;
            $cgstToPay = ($total_amount / 100) * $post['cgst'];
            $igstToPay = ($total_amount / 100) * $post['igst'];
            $sgstToPay = ($total_amount / 100) * $post['sgst'];
            $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
            $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
             $medicine_list[$post['mbid']] =  array('mid'=>$medicine_id_new[0], 'qty'=>$post['qty'],'batch_no'=>$medicine_id_new[1],'bar_code'=>$post['bar_code'],'manuf_date'=>$post['manuf_date'],'exp_date'=>$post['expiry_date'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'hsn_no'=>$post['hsn_no'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'sale_amount'=>$post['mrp'],'per_pic_amount'=>$per_pic_amount,'conversion'=>$post['conversion'],'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
            $this->session->set_userdata('medicine_id', $medicine_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
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
        $this->form_validation->set_rules('sales_no', 'Issue no', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $sale_no = generate_unique_id(51); 
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
        unauthorise_permission(60,402);
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_indent->delete($id);
           $response = "Sales medicine successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(60,402);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_indent->deleteall($post['row_id']);
            $response = "Sales medicine successfully deleted.";
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
        $this->load->view('sales_indent/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(60,403);
        $data['page_title'] = 'Sales indent archive list';
        $this->load->helper('url');
        $this->load->view('sales_indent/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(60,403);
        $this->load->model('sales_indent/sales_indent_archive_model','sales_indent_archive'); 

        $list = $this->sales_indent_archive->get_datatables();
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $sales_indent) { 
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

           $row[] = '<input type="checkbox" name="sales_indent[]" class="checklist" value="'.$sales_indent->id.'">'.$check_script;  
            $row[] = $sales_indent->sale_no;
            $row[] = $sales_indent->indent;
            $row[] = date('d-M-Y',strtotime($sales_indent->sale_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('405',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_sales_indent('.$sales_indent->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('404',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$sales_indent->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_indent_archive->count_all(),
                        "recordsFiltered" => $this->sales_indent_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(60,405);
        $this->load->model('sales_indent/sales_indent_archive_model','sales_indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_indent_archive->restore($id);
           $response = "Sales medicine  successfully restore in  sales medicine list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(60,405);
        $this->load->model('sales_indent/sales_indent_archive_model','sales_indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_indent_archive->restoreall($post['row_id']);
            $response = "Sales medicine successfully restore in sales medicine list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(60,404);
        $this->load->model('sales_indent/sales_indent_archive_model','sales_indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_indent_archive->trash($id);
           $response = "Sales medicine  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(60,404);
        $this->load->model('sales_indent/sales_indent_archive_model','sales_indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_indent_archive->trashall($post['row_id']);
            $response = "Sales medicine successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function sales_indent_dropdown()
  {
      $doctor_list = $this->sales_indent->doctor_list();
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
      $data['page_title'] = "Add sales indent";
      if(!empty($ids)){
        $sales_id= $ids;
     }else{
       $sales_id= $this->session->userdata('sales_id');
     }
      $get_detail_by_id= $this->sales_indent->get_by_id($sales_id);

      $get_by_id_data=$this->sales_indent->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
      $template_format= $this->sales_indent->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>3,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $this->load->view('sales_indent/print_template_medicine',$data);
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
                $medicine_data = $this->sales_indent->medicine_list($m_id_arr[0],$m_id_arr[1]);
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
                $medicine_data = $this->sales_indent->prescription_medicine_list($prescription_medicine->medicine_id);
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
          $result_medicine = $this->sales_indent->medicine_list($ids_arr,$batch_arr);
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
          $result_medicine = $this->sales_indent->prescription_medicine_array_list($priscription_medicine_arr);
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
                        $table.='<tr><input type="hidden" id="medicine_id_'.$medicine['id'].$medicine['batch_no'].'" name="m_id[]" value="'.$medicine['id'].'.'.$medicine['batch_no'].'"/>
                         <input type="hidden" id="mbid_'.$medicine['id'].$medicine['batch_no'].'" name="mbid[]" value='.$value.'/>
                     <input type="hidden" id="purchase_rate_mrp'.$medicine['id'].$medicine['batch_no'].'" name="purchase_rate_mrp[]" value="'.$medicine['mrp'].'"/><input type="hidden" id="batch_no_'.$medicine['id'].$medicine['batch_no'].'" name="batch_no[]" value="'.$medicine['batch_no'].'"/><input type="hidden" id="conversion_'.$medicine['id'].$medicine['batch_no'].'" name="conversion[]" value="'.$medicine['conversion'].'"/>';

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$medicine['medicine_name'].'</td>';
                        $table.='<td>'.$medicine['medicine_code'].'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$medicine['id'].$medicine['batch_no'].'" name="hsn_no[]" value="'.$medicine['hsn_no'].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine['packing'].'</td>';
                         $table.='<td>'.$medicine['batch_no'].'</td>';
                       // $table.='<td>'.$medicine->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine['id'].$medicine['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine['id'].$medicine['batch_no'].'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text"  value="'.$medicine['bar_code'].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine['id'].$medicine['batch_no'].'" onkeyup="payment_cal_perrow('.$varids.');validation_bar_code('.$varids.');"/><div  id="barcode_error_'.$medicine['id'].$medicine['batch_no'].'"  style="color:red;"></td>';

                        //   $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$medicine['id'].'" value="'.$medicine["unit1"].'" onkeyup="payment_cal_perrow('.$medicine['id'].');"/></td>';
                        // $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$medicine['id'].'" value="'.$medicine["unit2"].'" onkeyup="payment_cal_perrow('.$medicine['id'].');"/></td>';

                        // $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$medicine['id'].'" value="'.$medicine["freeunit1"].'" onkeyup="payment_cal_perrow('.$medicine['id'].');"/></td>';
                        // $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$medicine->id.'" value="'.$medicine["freeunit2"].'" onkeyup="payment_cal_perrow('.$medicine['id'].');"/></td>';

                             $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine['id'].$medicine['batch_no'].'" value="'.$medicine['qty'].'" onkeyup="payment_cal_perrow('.$varids.'),validation_check(qty,'.$varids.');"/><div  id="unit1_error_'.$medicine['id'].$medicine['batch_no'].'"  style="color:red;"></div></td>';
                        //$table.='<td>'.$medicine->medicine_unit_2.'</td>';
                       /* $table.='<td></td>';*/
                        $table.='<td><input type="text" id="mrp_'.$medicine['id'].$medicine['batch_no'].'" name="mrp[]" value="'.number_format($medicine['per_pic_rate'],2,'.','').'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

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


        /********** Estimate_medicine**************/

   function estimate_medicine($vals="")
    {
        if(!empty($vals))
        {
            $result = $this->sales_indent->estimate_medicine($vals);  
           // print_r($result);die;
            if(!empty($result))
            {
              //$this->session->set_userdata('medicine_id',$result); 
          
           echo json_encode($result,true);
            } 
        } 
    }

    public function set_estimate_medicine()
   {
       
       $post =  $this->input->post(); 
     // print_r($post['purchase_id']);die('fgf');
       if(isset($post['sales_id']) && !empty($post['sales_id']))
       {
        $result = $this->sales_indent->get_estimate_medicine_by_id($post['sales_id']);  
          // echo "<pre>" ;print_r($result);die;
            if(!empty($result))
            {
              $this->session->set_userdata('medicine_id',$result); 
            $this->ajax_added_estimate_medicine();
           //   echo json_encode($result,true);
            }
        }
    }


    public function ajax_added_estimate_medicine()
    {

         $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
         $medicine_sess = $this->session->userdata('medicine_id');
      //  echo "<pre>";print_r($medicine_sess);die;
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

                        $varids=$medicine['id'];

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

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
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

  /********** Estimate_medicine**************/
   /* Letter Head print */
  public function letterhead_print_sales($ids="")
  {

      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Add sales indent";
       $this->load->model('general/general_model');
      $this->load->library('m_pdf');
      if(!empty($ids)){
        $sales_id= $ids;
     }else{
       $sales_id= $this->session->userdata('sales_id');
     }
      $get_detail_by_id= $this->sales_indent->get_by_id($sales_id);

      $get_by_id_data=$this->sales_indent->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->sales_indent->letterhead_template_format();
      $template_data=$template_format;
        $user_detail=$user_detail;
        $all_detail= $get_by_id_data;
        $header_replace_part=$template_data->page_details;

 

    if(!empty($all_detail['sales_list'][0]->relation))
    {
        $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
        $header_replace_part = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation,$header_replace_part);
    }
    else
    {
       $header_replace_part = str_replace("{parent_relation_type}",'',$header_replace_part);
    }

    if(!empty($all_detail['sales_list'][0]->relation_name))
    {
        $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
        $header_replace_part = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$header_replace_part);
    }
    else
    {
       $header_replace_part = str_replace("{parent_relation_name}",'',$header_replace_part);
    }

      $genders = array('0'=>'F','1'=>'M','2'=>'O');
        $gender = $genders[$all_detail['sales_list'][0]->gender];
        $age_y = $all_detail['sales_list'][0]->age_y; 
        $age_m = $all_detail['sales_list'][0]->age_m;
        $age_d = $all_detail['sales_list'][0]->age_d;

        $age = "";
        if($age_y>0)
        {
        $year = 'Y';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
        }
        $age .= ", ".$age_d." ".$day;
        }
        $patient_age =  $age;
        if($patient_age!=''){
            $patient1_age = '/'.$patient_age;
        }
        if($patient_age==''){
            $patient1_age=$patient_age;
        }
        $gender_age = $gender.$patient1_age ;

    $header_replace_part = str_replace("Disc:","Discount ({discount_percent}%):",$header_replace_part);
   
   $header_replace_part = str_replace("{gender_age}",$gender_age,$header_replace_part);


$header_replace_part = str_replace("Discount(%):","Discount ({discount_percent}%):",$header_replace_part);
$header_replace_part = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$header_replace_part);
$header_replace_part = str_replace("Name","Patient Name",$header_replace_part);

$header_replace_part = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$header_replace_part);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$header_replace_part = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$header_replace_part);
$header_replace_part = str_replace("Invoice No:","Receipt No.:",$header_replace_part);
$header_replace_part = str_replace("{invoice_no}",$all_detail['sales_list'][0]->sale_no,$header_replace_part);

/* 06-02-2018 */
$header_replace_part = str_replace("{aadhaar_no}",$all_detail['sales_list'][0]->adhar_no,$header_replace_part);
if($all_detail['sales_list'][0]->anniversary!='1970-01-01' && $all_detail['sales_list'][0]->anniversary!='0000-00-00') 
{
    $header_replace_part = str_replace("{anniversary}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->anniversary)),$header_replace_part);

}
else
{
    $header_replace_part = str_replace("{anniversary}",'',$header_replace_part);

}
if($all_detail['sales_list'][0]->marital_status==1)
{
    $marital_status = 'Married';
}
else
{
    $marital_status = 'Un-married';
}
$header_replace_part = str_replace("{marital_status}",$marital_status,$header_replace_part);
$header_replace_part = str_replace("{religion}",$all_detail['sales_list'][0]->religion,$header_replace_part);


if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
{
    $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
}
if(!empty($address_n))
{
   $address_re=array();
   foreach($address_n as $add_re)
   {
    if(!empty($add_re))
    {
        $address_re[]=$add_re;  
    }

}
$patient_address = implode(',',$address_re).' - '.$all_detail['sales_list'][0]->pincode;
}
else
{
    $patient_address='';
}
$header_replace_part = str_replace("{sales_name}",$all_detail['sales_list'][0]->patient_code,$header_replace_part);
$header_replace_part = str_replace("{address}",$patient_address,$header_replace_part);

$header_replace_part = str_replace("{pincode}",$all_detail['sales_list'][0]->pincode,$header_replace_part);

$header_replace_part = str_replace("{country}",$all_detail['sales_list'][0]->country,$header_replace_part);

$header_replace_part = str_replace("{state}",$all_detail['sales_list'][0]->state,$header_replace_part);

$header_replace_part = str_replace("{city}",$all_detail['sales_list'][0]->city,$header_replace_part);

$header_replace_part = str_replace("{father_husband}",$all_detail['sales_list'][0]->father_husband,$header_replace_part);

$header_replace_part = str_replace("{mother}",$all_detail['sales_list'][0]->mother,$header_replace_part);

$header_replace_part = str_replace("{guardian_name}",$all_detail['sales_list'][0]->guardian_name,$header_replace_part);

$header_replace_part = str_replace("{guardian_email}",$all_detail['sales_list'][0]->guardian_email,$header_replace_part);

$header_replace_part = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$header_replace_part);

$header_replace_part = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$header_replace_part);

$header_replace_part = str_replace("{patient_email}",$all_detail['sales_list'][0]->patient_email,$header_replace_part);


$header_replace_part = str_replace("{monthly_income}",$all_detail['sales_list'][0]->monthly_income,$header_replace_part);

$header_replace_part = str_replace("{occupation}",$all_detail['sales_list'][0]->occupation,$header_replace_part);

$header_replace_part = str_replace("{insurance_company}",$all_detail['sales_list'][0]->insurance_company,$header_replace_part);

$header_replace_part = str_replace("{policy_no}",$all_detail['sales_list'][0]->polocy_no,$header_replace_part);

$header_replace_part = str_replace("{refered_by}",''.$all_detail['sales_list'][0]->doctor_hospital_name,$header_replace_part);
/*06-02-2018*/




$header_replace_part = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->sale_date)),$header_replace_part);

$pos_start = strpos($header_replace_part, '{start_loop}');
$pos_end = strpos($header_replace_part, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($header_replace_part,$pos_start+12,$row_last_length-12);
// Replace looping row//
$rplc_row = trim(substr($header_replace_part,$pos_start,$row_last_length+10));
/*$header_replace_part = str_replace($rplc_row,"{row_data}",$header_replace_part);*/

$middle_replace_part=$template_data->page_middle;

//////////////////////// 
$i=1;

$table_data='<table>';
/*print '<pre>'; print_r($all_detail['sales_list']);*/
foreach($all_detail['sales_list']['medicine_list'] as $medicine_list)
{ 

   $table_data.='<tr>
                 <td width="5%">'.$i.'</td>
                  <td width="23%">'.$medicine_list->medicine_name.'</td>
                    <td width="8%">'.$medicine_list->qty.'</td>
                      <td width="8%">'.$medicine_list->mrp.'</td>
                          <td width="8%">'.$medicine_list->hsn_no.'</td>
                            <td width="8%">'.$medicine_list->batch_no.'</td>
                            <td width="8%">'.$medicine_list->discount.'</td>
                              <td width="8%">'.$medicine_list->m_cgst.'</td>
                                <td width="8%">'.$medicine_list->m_sgst.'</td>
                                  <td width="8%">'.$medicine_list->m_igst.'</td>
                                   <td width="8%">'.$medicine_list->total_amount.'</td>

                </tr>'; 
   $i++;

}
$table_data.='</table>';
//echo $i;
$middle_replace_part = str_replace("{table_data}",$table_data,$middle_replace_part);
$middle_replace_part = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$middle_replace_part);
$middle_replace_part = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$middle_replace_part);
$middle_replace_part  = str_replace("{medicine_discount}",$all_detail['sales_list'][0]->medicine_discount,$middle_replace_part );

$middle_replace_part = str_replace("{total_vat}",$all_detail['sales_list'][0]->vat_percent,$middle_replace_part);

$middle_replace_part = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$middle_replace_part);

$middle_replace_part = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$middle_replace_part);

$middle_replace_part = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$middle_replace_part);

$middle_replace_part = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$middle_replace_part);

$middle_replace_part = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$middle_replace_part);

$middle_replace_part = str_replace("{payment_mode}",$payment_mode,$middle_replace_part);
$middle_replace_part = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$middle_replace_part);
$middle_replace_part = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$middle_replace_part);
$middle_replace_part = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$middle_replace_part);
if($user_detail['users_role']==4)
{
    $middle_replace_part = str_replace("{signature}",ucfirst($user_detail['username']),$middle_replace_part);
}
else
{
   $middle_replace_part = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$middle_replace_part);   
}


$middle_replace_part = str_replace("{refered_by}",''.$all_detail['sales_list'][0]->doctor_hospital_name,$middle_replace_part);
if(!empty($all_detail['sales_list'][0]->remarks))
{
 $middle_replace_part = str_replace("{remarks}",$all_detail['sales_list'][0]->remarks,$middle_replace_part);
}
else
{
 $middle_replace_part = str_replace("{remarks}",' ',$middle_replace_part);
 $middle_replace_part = str_replace("Remarks :",' ',$middle_replace_part);
 $middle_replace_part = str_replace("Remarks",' ',$middle_replace_part);
}



      $footer_data_part = $template_data->page_footer;
      $stylesheet = file_get_contents(ROOT_CSS_PATH.'report_pdf.css'); 
/*print_r($middle_replace_part);die;
*/      $this->m_pdf->pdf->WriteHTML($stylesheet,1); 

    
   
     if($type=='Download')
    {
        if($template_data->header_pdf==1)
        {
           $page_header = $template_data->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$template_data->page_header.'</div>';
        }

        if($template_data->details_pdf==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_data->middle_pdf==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_data->footer_pdf==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
        }

       $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part);
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf'; 
        $pdfFilePath = DIR_UPLOAD_PATH.'temp/'.$pdfFilePath; 
        // $this->load->library('m_pdf');
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "F");
        
    }
    else 
    { 

    // echo $middle_replace_part;die;
        
        if($template_data->header_print==1)
        {
           $page_header = $template_data->page_header;
        }
        else
        {
           $page_header = '<div style="visibility:hidden">'.$template_data->page_header.'</div><br></br>';
        }

        if($template_data->details_print==1)
        {
           $header_replace_part = $header_replace_part;
        }
        else
        {
           $header_replace_part = '';
        }

        if($template_data->middle_print==1)
        {
           $middle_replace_part = $middle_replace_part;
        }
        else
        {
           $middle_replace_part = '';
        }

        if($template_data->footer_print==1)
        {
           $footer_data_part = $footer_data_part;
        }
        else
        {
           $footer_data_part = '';
           $footer_data_part = '<p style="height:'.$template_data->pixel_value.'px;"></p>'; 
            $this->m_pdf->pdf->defaultfooterline = 0;
        }
  /* print_r($header_replace_part);die;*/
        $this->m_pdf->pdf->setAutoTopMargin = 'stretch'; 
        $this->m_pdf->pdf->SetHeader($page_header.$header_replace_part);

        $this->m_pdf->pdf->setAutoBottomMargin = 'stretch';
        $this->m_pdf->pdf->SetFooter($footer_data_part); 
        $patient_name = str_replace(' ', '-', $patient_name);
        $pdfFilePath = $patient_name.'_report.pdf';  
        $this->m_pdf->pdf->WriteHTML($middle_replace_part,2);
        //download it.
        $this->m_pdf->pdf->Output($pdfFilePath, "I"); // I open pdf
    }  
  
  }
  
  
  /* Letter head print */
    
    


}
