<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_return_indent extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('sales_return_indent/sales_return_indent_model','sales_return_indent');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
       unauthorise_permission(61,407);
        $this->session->unset_userdata('medicine_id'); 
        $this->session->unset_userdata('sale_search');
        $this->session->unset_userdata('net_values_all'); 
     
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
         $data['form_data'] = array('sale_no'=>'','referred_by'=>'','refered_id'=>'','referral_hospital'=>'','paid_amount_to'=>'','paid_amount_from'=>'','balance_to'=>'','balance_from'=>'','start_date'=>$start_date, 'end_date'=>$end_date);
        $data['page_title'] = 'Indent Sale Return'; 
        $this->load->view('sales_return_indent/list',$data);
    }

    public function ajax_list()
    {  
        unauthorise_permission(61,407);
        $list = $this->sales_return_indent->get_datatables();  
        $assoc_array = json_decode(json_encode($list),TRUE);
        $session_data= $this->session->userdata('auth_users');
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
            
            //$row[] = $sales_return->sale_no;
            $row[] = $sales_return->return_no;
            $row[] = $sales_return->indent;
            $row[] = date('d-M-Y',strtotime($sales_return->sale_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if($session_data['parent_id']==$sales_return->branch_id){
           if(in_array('409',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_sales_return_indent('.$sales_return->id.');" class="btn-custom" href="javascript:void(0)" style="'.$sales_return->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
         
            if(in_array('410',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_sales_return_indent('.$sales_return->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }   
          }
           
             $print_url = "'".base_url('sales_return_indent/print_sales_report/'.$sales_return->id)."'";
             $btnprint = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$print_url.')" title="Print" ><i class="fa fa-print"></i> Print</a>'; 

            $lettherhead_print_url = "'".base_url('sales_return_indent/letterhead_print_sales/'.$sales_medicine->id)."'";
            $btnlettherhead_print = '<a class="btn-custom" href="javascript:void(0)" onClick="return print_window_page('.$lettherhead_print_url.')" title="Print" ><i class="fa fa-print"></i>Letterhead print</a>'; 

            $row[] = $btnedit.$btnview.$btndelete. $btnprint.$btnlettherhead_print;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->sales_return_indent->count_all(),
                        "recordsFiltered" => $this->sales_return_indent->count_filtered(),
                        "data" => $data,
                );
  
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


    public function ajax_list_medicine(){
        $medicine_list = $this->session->userdata('medicine_id');
         // print_r($medicine_list);die;
       $ids=array();
       $post = $this->input->post(); 
        if(!empty($medicine_list))
        { 
          $ids_arr= [];
          foreach($medicine_list as $key_m_arr=>$m_arr)
          {
             //$ids_arr[] = $key_m_arr;
             //$batch_arr[] = $m_arr['batch_no'];
             $ids_arr[] = $m_arr['mid'];
             $batch_arr[] = $m_arr['batch_no'];
          }
          $medicine_ids = implode(',', $ids_arr); 
          $batch_nos = implode(',', $batch_arr); 
          //$medicine_ids = implode(',', $ids_arr);
          $data['medicne_new_list'] = $this->sales_return_indent->medicine_list($medicine_ids,$batch_nos);
          //print_r($data['medicne_new_list']);
           foreach($data['medicne_new_list'] as $medicine_list){
                           $ids[]=$medicine_list->id;
           }
        }
        $table='';
        $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
        $keywords= $this->input->post('search_keyword');

        $name= $this->input->post('name');
          if(!empty($post['medicine_name']) ||!empty($post['medicine_company']) ||!empty($post['batch_number']) ||!empty($post['bar_code']) || !empty($post['medicine_code']) || !empty($post['qty']) || !empty($post['stock']) || !empty($post['rate']) || !empty($post['packing']) ||!empty($post['discount'])||!empty($post['hsn_no']) ||!empty($post['cgst'])||!empty($post['igst'])||!empty($post['sgst']))
          {   
            
             $result_medicine = $this->sales_return_indent->medicine_list_search();  
          } 

         //print_r($result_medicine);die;
         if((count($result_medicine)>0 && isset($result_medicine))){
            foreach($result_medicine as $medicine)
            {  
                   //echo $medicine->medicine_name;
                $table .='<tr class="append_row">';
                //if($medicine->stock_quantity>0)
                 // {
                      $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$medicine->id.'.'.$medicine->batch_no.'" onclick="add_check();"></td>';
                      $table.='<td>'.$medicine->medicine_name.'</td>';
                      $table.='<td>'.$medicine->packing.'</td>';
                      $table.='<td>'.$medicine->medicine_code.'</td>';
                      $table.='<td>'.$medicine->hsn_no.'</td>';
                      $table.='<td>'.$medicine->company_name.'</td>';
                      $table.='<td>'.$medicine->batch_no.'</td>';
                      $table.='<td>'.$medicine->bar_code.'</td>';
                      $table.='<td>'.$medicine->min_alrt.'</td>';
                      if($medicine->qty<1)
                      {
                        $medicine->qty = 0;
                      }
                      $table.='<td>'.$medicine->qty.'</td>';
                      $table.='<td>'.$medicine->mrp.'</td>';
                      $table.='<td>'.$medicine->discount.'</td>';
                      $table.='<td>'.$medicine->cgst.'</td>';
                      $table.='<td>'.$medicine->sgst.'</td>';
                      $table.='<td>'.$medicine->igst.'</td>';
                      $table.='</tr>';
                   //} 
            }
          }
        else
        {
             $table='<tr class="append_row"><td colspan="15" class="text-danger"><div class="text-center">No record found</div></td></tr>';
        }
               $output=array('data'=>$table);
               echo json_encode($output);
        }

    public function medicine_sales_excel()
    {

          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Sale No.','Return No.','Indent Name','Return Date');
          $col = 0;
          $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $objPHPExcel->getActiveSheet()->getStyle('E')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
          foreach ($fields as $field)
          {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
               $col++;
          }
          $list =$this->sales_return_indent->search_report_data();
          
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->return_no,$reports->indent, date('d-M-Y',strtotime($reports->sale_date)));
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
          header("Content-Disposition: attachment; filename=indent_sales_return_report_".time().".xls");
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
           $fields = array('Sale No.','Return No.','Indent Name','Return Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->sales_return_indent->search_report_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $reports)
               {
                    
                    array_push($rowData,$reports->sale_no,$reports->return_no,$reports->indent,date('d-M-Y',strtotime($reports->sale_date)));
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
         header("Content-Disposition: attachment; filename=indent_sales_return_report_".time().".csv");  
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
        $data['data_list'] = $this->sales_return_indent->search_report_data();
       
        $this->load->view('sales_return_indent/medicine_sales_return_report_html',$data);
        $html = $this->output->get_output();
        //print_r($html);
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("indent_sales_return_report_".time().".pdf");
    }
    public function print_medicine_sales()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->sales_return_indent->search_report_data();
      $this->load->view('sales_return_indent/medicine_sales_return_report_html',$data); 
    }

    public function ajax_added_medicine(){

         $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
         $medicine_sess = $this->session->userdata('medicine_id');
         $check_script="";
         $result_medicine = [];
        if(!empty($medicine_sess))
        { 
          $ids_arr= [];

          foreach($medicine_sess as $key_m_arr=>$m_arr)
          {
              $ids_arr[] = "'".$m_arr['mid']."'";
             $batch_arr[] = "'".$m_arr['batch_no']."'";
          }
          $medicine_ids = implode(',', $ids_arr); 
          $batch_nos = implode(',', $batch_arr); 
         
          //echo $batch_nos;
          $result_medicine = $this->sales_return_indent->medicine_list($medicine_ids,$batch_nos,1);
           //print_r($medicine_sess);die;
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
                        if(count($result_medicine)>0 && isset($result_medicine) || !empty($ids)){

                        foreach($result_medicine as $medicine){
                          //print_r($medicine);
                            if($medicine_sess[$medicine->id.'.'.$medicine->batch_no]["exp_date"]=="00-00-0000"){

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
                          startDate: '".$date_new."',
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
                     <input type="hidden" id="purchase_rate_mrp'.$medicine->id.$medicine->batch_no.'" name="purchase_rate_mrp[]" value="'.$medicine->mrp.'"/><input type="hidden" id="batch_no_'.$medicine->id.'" name="batch_no[]" value="'.$medicine->batch_no.'"/><input type="hidden" id="conversion_'.$medicine->id.$medicine->batch_no.'" name="conversion[]" value="'.$medicine->conversion.'"/>';

                        $table.='<td><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='.$value.'></td>';
                        $table.='<td>'.$medicine->medicine_name.'</td>';
                        $table.='<td>'.$medicine->medicine_code.'</td>';

                        $table.='<td><input type="text" id="hsn_no_'.$medicine->id.$medicine->batch_no.'" name="hsn_no[]" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["hsn_no"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';

                        $table.='<td>'.$medicine->packing.'</td>';
                         $table.='<td>'.$medicine->batch_no.'</td>';
                       // $table.='<td>'.$medicine->conversion.'</td>';
                       $table.='<td><input type="text" value="'.$date_newmanuf.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';

                        $table.='<td><input type="text" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["bar_code"].'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'.$medicine->id.$medicine->batch_no.'" onchange="payment_cal_perrow('.$varids.');"/>'.$check_script.'</td>';


                            $table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$medicine->id.$medicine->batch_no.'" value="'.$medicine_sess[$medicine->id.'.'.$medicine->batch_no]["qty"].'" onkeyup="payment_cal_perrow('.$varids.');"/></td>';
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
                  
                //print_r($medicine_data);die;
                $m_id_arr = explode('.',$m_ids);
                $vat='';
                $medicine_data = $this->sales_return_indent->medicine_list($m_id_arr[0],$m_id_arr[1]);
                $per_pic_amount= $medicine_data[0]->mrp/$medicine_data[0]->conversion;
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
                
                /*$cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;

                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                 $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;

                $total_amount= $tot_qty_with_rate-$total_discount;
                $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
                $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
                $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;

                //$m_new_array_id[$m_ids]=array('mid'=> $m_id_arr[0],'batch_no'=>$m_id_arr[1]);
               $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0],'hsn_no'=>$medicine_data[0]->hsn,'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'bar_code'=>$medicine_data[0]->bar_code,'discount'=>$medicine_data[0]->discount,'conversion'=>$medicine_data[0]->conversion,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst, 'per_pic_amount'=>$per_pic_amount,'sale_amount'=>$medicine_data[0]->mrp,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
                //$mid_arr[] = $m_new_array_id[$m_id_arr[0]];
                

            } 
            
            $medicine_id = $purchase+$post_mid_arr;
          //print_r($post_mid_arr);die;

         } 
         else
         { 
          $total_price_medicine_amount=0;
            foreach($post['medicine_id'] as $m_ids)
            {
              // print_r($m_ids);
                $m_id_arr = explode('.',$m_ids);
                $medicine_data = $this->sales_return_indent->medicine_list($m_id_arr[0],$m_id_arr[1]);
                
               
                $per_pic_amount= $medicine_data[0]->per_price;
                
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
               
                $tot_qty_with_rate= $per_pic_amount*1;

               /* $cgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $medicine_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;

                $total_discount = ($medicine_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                $total_discount = ($medicine_data[0]->discount/100)*$tot_qty_with_rate;

                $total_amount= $tot_qty_with_rate-$total_discount;
                $cgstToPay = ($total_amount / 100) * $medicine_data[0]->cgst;
                $igstToPay = ($total_amount / 100) * $medicine_data[0]->igst;
                $sgstToPay = ($total_amount / 100) * $medicine_data[0]->sgst;
                $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
  $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
  
                $post_mid_arr[$m_id_arr[0].'.'.$m_id_arr[1]] = array('mid'=>$m_id_arr[0],'hsn_no'=>$medicine_data[0]->hsn,'batch_no'=>$m_id_arr[1], 'qty'=>'1', 'exp_date'=>$exp_date,'manuf_date'=>$manuf_date,'bar_code'=>$medicine_data[0]->bar_code,'per_pic_amount'=>$per_pic_amount,'conversion'=>$medicine_data[0]->conversion,'sale_amount'=>$medicine_data[0]->mrp,'per_pic_amount'=>$per_pic_amount,'discount'=>$medicine_data[0]->discount,'cgst'=>$medicine_data[0]->cgst,'sgst'=>$medicine_data[0]->sgst,'igst'=>$medicine_data[0]->igst,'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount);
                //print_r($post_mid_arr);die;  
            }

            $medicine_id = $post_mid_arr;
            //print_r($medicine_id);die;
         }  
         $this->session->set_userdata('medicine_id',$medicine_id); 
        // print_r($this->session->userdata('medicine_id'));
         $this->ajax_added_medicine();
       }
    }

     public function remove_medicine_list()
    {
        
    	$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post =  $this->input->post();
       if(isset($post['medicine_id']) && !empty($post['medicine_id']))
       {
           $ids_list = $this->session->userdata('medicine_id');
           //print_r($ids_list);die;
           
             foreach($post['medicine_id'] as $post_id)
             {
                  if(array_key_exists($post_id,$ids_list))
                  {

                     unset($ids_list[$post_id]);
                  }
             } 
             $this->session->set_userdata('medicine_id',$ids_list);
           
           $medicne_list = [];
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
            $medicine_listdata = $this->sales_return_indent->medicine_list();
           }
           $this->ajax_added_medicine();
       }
    } 

	public function add()
	{
     //print_r($_POST);die;
         unauthorise_permission(61,408);
         $users_data = $this->session->userdata('auth_users');
    $pid='';
    if(isset($_GET['reg'])){
      $pid= $_GET['reg'];
    }
     if(isset($_GET['ipd'])){
      $pid= $_GET['ipd'];
    }
    $sale_no = generate_unique_id(52);
		$this->load->model('general/general_model'); 
		$data['page_title'] = "Add Indent Sale Return";
		$data['form_error'] = [];
    $data['button_value'] = "Save";
		$post = $this->input->post();


          $medicine_list = $this->session->userdata('medicine_id');
          $data['medicine_id'] = $medicine_list;
        
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
          $data['medicne_new_list'] = $this->sales_return_indent->medicine_list($medicine_id_arr,$medicine_bno_arr,1);
        }
        $this->load->model('sales_indent/sales_indent_model','sales_indent');
        $data['indent_list']= $this->sales_indent->testing($users_data['parent_id']);

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
                
                $salesid=  $this->sales_return_indent->save();
                
                if(!empty($salesid))
                {
                   $get_by_id_data = $this->sales_return_indent->get_by_id($salesid);
                
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
                redirect(base_url('sales_return_indent/add/?status=print'));
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
      $this->load->view('sales_return_indent/add',$data);
	}

	public function edit($id="")
    {
       unauthorise_permission(61,409);
       $this->load->model('general/general_model'); 

        $this->load->model('sales_indent/sales_indent_model','sales_indent');
        $data['indent_list']= $this->sales_indent->testing($users_data['parent_id']);
       
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
         $post = $this->input->post();
         $result = $this->sales_return_indent->get_by_id($id); 

         $medicine_id_arr=[];
        
         if(empty($post))
         { 
            $result_medince_list = $this->sales_return_indent->get_medicine_by_sales_id($id,'');
            $this->session->set_userdata('medicine_id',$result_medince_list);
         }
         $medicine_list = $this->session->userdata('medicine_id');
      
         $data['medicine_id'] = $medicine_list;
         $data['id'] = $id;
      
        if(!empty($medicine_list))
        { 
          $medicine_id_arr = [];
          foreach($medicine_list  as $key=>$val)
          { 
             $medicine_id_arr[] = $key;
          } 
          $medicine_ids = implode(',', $medicine_id_arr);
          $data['medicne_new_list'] = $this->sales_return_indent->medicine_list($medicine_ids);
          
        }
      
     
     
        $data['page_title'] = "Update Indent Sale Return";  
        $data['button_value'] = "Update";
        $data['form_error'] = ''; 
        $data['form_data'] = array(

                                    "indent_id"=> $result['indent_id'],
                                    'sales_no'=>$result['sale_no'],                                  
                                    'return_no'=>$result['return_no'],                                    
                                    "data_id"=>$result['id'],
                                    "branch_id"=>$result['branch_id'],
                                    "sales_date"=>date('d-m-Y',strtotime($result['sale_date'])),
                                    "remarks"=>$result['remarks'],
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                 $salesid=  $this->sales_return_indent->save();
                 $this->session->set_userdata('sales_id',$salesid);
                 $this->session->set_flashdata('success','Sales medicine has been successfully updated.');
                 redirect(base_url('sales_return_indent/?status=print'));
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            } 
        }
        $this->load->view('sales_return_indent/add',$data);  

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
          $this->load->view('sales_return_indent/advance_search',$data);
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
          
            //$total_discount = ($post['discount']/100)*$total_new_amount;
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
         
            $blance_due=$net_amount - $payamount;
          
            $newamountwithsgst = number_format($newamountwithsgst,2,'.','');
            $newamountwithcgst = number_format($newamountwithcgst,2,'.','');
            $newamountwithigst = number_format($newamountwithigst,2,'.','');
        
       

        $pay_arr = array('total_amount'=>number_format($total_new_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>$payamount,'balance_due'=>number_format($blance_due,2,'.',''),'discount'=>$post['discount'],'sgst_amount'=>$newamountwithsgst,'igst_amount'=>$newamountwithigst,'cgst_amount'=> $newamountwithcgst,'discount_amount'=>number_format($total_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 

public function payment_cal_perrow()
    {
      $this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
       $post = $this->input->post();
       $total_price_medicine_amount=0;
       //echo $post['manuf_date'];
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $medicine_list = $this->session->userdata('medicine_id');
         // print_r($medicine_list);die;
         if(!empty($medicine_list))
         {

           $medicine_id_new= explode('.',$post['medicine_id']);

            $medicine_data = $this->sales_return_indent->medicine_list($medicine_id_new[0],$medicine_id_new[1]);
            $per_pic_amount= $post['mrp'];
            $tot_qty_with_rate= $per_pic_amount*$post['qty'];
       

            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
             
             $total_amount= $tot_qty_with_rate-$total_discount;
             $cgstToPay = ($total_amount / 100) * $post['cgst'];
             $igstToPay = ($total_amount / 100) * $post['igst'];
             $sgstToPay = ($total_amount / 100) * $post['sgst'];
             $total_tax= $cgstToPay+$igstToPay+$sgstToPay;
           
             $total_price_medicine_amount =  $total_price_medicine_amount+$total_amount-$total_tax;
             

             //
           
             $medicine_list[$post['mbid']] =  array('mid'=>$medicine_id_new[0], 'qty'=>$post['qty'],'batch_no'=>$medicine_id_new[1],'manuf_date'=>$post['manuf_date'],'exp_date'=>$post['expiry_date'],'sgst'=>$post['sgst'],'bar_code'=>$post['bar_code'],'igst'=>$post['igst'],'hsn_no'=>$post['hsn_no'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'sale_amount'=>$post['mrp'],'per_pic_amount'=>$per_pic_amount,'conversion'=>$post['conversion'],'total_amount'=>$total_amount,'total_pricewith_medicine'=>$total_price_medicine_amount); 
            $this->session->set_userdata('medicine_id', $medicine_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }


    private function _validate()
    {

        $post = $this->input->post();  
         
        $total_values=array();
        $this->form_validation->set_rules('indent_id', 'indent name', 'trim|required');
    
        if ($this->form_validation->run() == FALSE) 
        {  
            $sale_no = generate_unique_id(52); 
          
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'],
									                  "indent_id"=>$_POST['indent_id'], 
                                    'patient_code'=>$patient_code,
                                    "sales_no"=>$_POST['sales_no'],
                                    "return_no"=>$sale_no,
                                    "remarks"=>$post['remarks']
                                   );  
            return $data['form_data'];
        }


    }
 
    public function delete($id="")
    {
        unauthorise_permission(61,410);
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_indent->delete($id);
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
            $result = $this->sales_return_indent->deleteall($post['row_id']);
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
        $this->load->view('sales_return_indent/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission(61,411);
        $data['page_title'] = 'Sales return medicine archive list';
        $this->load->helper('url');
        $this->load->view('sales_return_indent/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(61,411);
        $this->load->model('sales_return_indent/sales_return_indent_archive_model','sales_return_indent_archive'); 

        $list = $this->sales_return_indent_archive->get_datatables();
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
            $row[] = $sales_return->indent;
            $row[] = date('d-M-Y',strtotime($sales_return->sale_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('413',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_sales_return_indent('.$sales_return->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
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
                        "recordsTotal" => $this->sales_return_indent_archive->count_all(),
                        "recordsFiltered" => $this->sales_return_indent_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       unauthorise_permission(61,413);
        $this->load->model('sales_return_indent/sales_return_indent_archive_model','sales_return_indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_indent_archive->restore($id);
           $response = "Sales return medicine  successfully restore in  sales medicine list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(61,413);
        $this->load->model('sales_return_indent/sales_return_indent_archive_model','sales_return_indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_return_indent_archive->restoreall($post['row_id']);
            $response = "Sales return medicine successfully restore in sales medicine list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(61,412);
        $this->load->model('sales_return_indent/sales_return_indent_archive_model','sales_return_indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->sales_return_indent_archive->trash($id);
           $response = "Sales return medicine  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission(61,412);
        $this->load->model('sales_return_indent/sales_return_indent_archive_model','sales_return_indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->sales_return_indent_archive->trashall($post['row_id']);
            $response = "Sales return medicine successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

   public function sales_return_indent_dropdown()
  {
      $doctor_list = $this->sales_return_indent->doctor_list();
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
      if(!empty($ids)){
      $sales_id= $ids;
      }else{
      $sales_id= $this->session->userdata('sales_id');
      }
      $get_detail_by_id= $this->sales_return_indent->get_by_id($sales_id);
      $get_by_id_data=$this->sales_return_indent->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
      $template_format= $this->sales_return_indent->template_format(array('section_id'=>2,'types'=>1,'sub_section'=>3,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
 
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
     // print_r($data['all_detail']);die;
   
      $this->load->view('sales_return_indent/print_template_medicine',$data);
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
            $result = $this->sales_return_indent->search_sales($vals);  
            if(!empty($result))
            {
              echo json_encode($result,true);
            } 
        } 
    }
    public function get_sales_indent()
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
            $this->load->model('sales_indent/sales_indent_model','sales_indent');
            $total_amount=0;

            $result = $this->sales_indent->get_by_id($post['sales_id']); 
            
            $result_medince_list = $this->sales_indent->get_medicine_by_sales_id($post['sales_id']);
            //echo "<pre>"; print_r($result_medince_list); exit;  
            foreach($result_medince_list as $medicines)
            {
               $post_mid_arr[$medicines['mid'].'.'.$medicines['batch_no']] = array('mid'=>$medicines['mid'], 'batch_no'=>$medicines['batch_no'], 'qty'=>$medicines['qty'], 'exp_date'=>date('d-m-Y',strtotime($medicines['exp_date'])),'manuf_date'=>date('d-m-Y',strtotime($medicines['manuf_date'])),'discount'=>$medicines['discount'],'bar_code'=>$medicines['bar_code'],'conversion'=>$medicines['conversion'],'hsn_no'=>$medicines['hsn_no'],'cgst'=>$medicines['cgst'],'sgst'=>$medicines['sgst'],'igst'=>$medicines['igst'], 'per_pic_amount'=>$medicines['per_pic_amount'],'sale_amount'=>$medicines['sale_amount'],'total_amount'=>$medicines['total_amount'],'total_pricewith_medicine'=>$medicines['total_pricewith_medicine']); 



                
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

      /* Letter Head print */
  public function letterhead_print_sales($ids="")
  {
      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Add sales medicine";
      $this->load->model('general/general_model');
      $this->load->library('m_pdf');
      if(!empty($ids)){
      $sales_id= $ids;
      }else{
      $sales_id= $this->session->userdata('sales_id');
      }
      $get_detail_by_id= $this->sales_return_indent->get_by_id($sales_id);
      $get_by_id_data=$this->sales_return_indent->get_all_detail_print($sales_id,$get_detail_by_id['branch_id']);
      $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->sales_return_indent->letterhead_template_format();
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
