<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_allotment_report extends CI_Controller {
 
	function __construct() 
	{
  	parent::__construct();	
  	 auth_users();  
  	$this->load->model('stock_allotment_report/stock_allotment_model','stock_purchase');
  	$this->load->library('form_validation');
  }

    
  	public function index()
      {
        unauthorise_permission(165,952);
        $data['page_title'] = 'Branch Allotment Report'; 
        $this->session->unset_userdata('stock_purchase_item_list');  
        $this->session->unset_userdata('stock_item_payment_payment_array');
        $this->session->unset_userdata('stock_purchase_search'); 
        // Default Search  Setting
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
        $this->load->view('stock_allotment_report/collection_list',$data);
      }

    public function ajax_list()
    {  
        unauthorise_permission(165,952);
        $list = $this->stock_purchase->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
	        $total_num = count($list);
        //$row='';
        foreach ($list as $item_stock) { 
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
           
            
            $row[] = $item_stock->branch_name;
            $row[] = $item_stock->item;
            $row[] = $item_stock->item_code;
            $row[] = $item_stock->quantity;
            $row[] = date('d-M-Y',strtotime($item_stock->created_date));
			$data[] = $row;
		
		   
            $i++;
          }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_purchase->count_all(),
                        "recordsFiltered" => $this->stock_purchase->count_filtered(),
                        "data" => $data,
                );
         //output to json format
        echo json_encode($output);
    }

    public function excel()
    {
        // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
          $objWorksheet = $objPHPExcel->getActiveSheet();
         $num_rows = $objPHPExcel->getActiveSheet()->getHighestRow();
          $objWorksheet->insertNewRowBefore($num_rows + 1, 1);
          $name = isset($var) ? $var : '';
          // Field names in the first row
     
		  $fields = array('Branch Name','Item Name','Item Code','Quantity','Date');
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
                
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $col++;
                $row_heading++;
          }
         
         $list = $this->stock_purchase->search_report_data();
          //print '<pre>'; print_r($list);die;
          $rowData = array();
          $data= array();
          $total_num = count($list);

          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $item_stock)
               {
                   array_push($rowData,$item_stock->branch_name,$item_stock->item,$item_stock->item_code,$item_stock->quantity,date('d-M-Y',strtotime($item_stock->created_date)));
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
                    $row_val=1;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $boking_data[$field]);

                         $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                          $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                         $col++;
                         $row_val++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=alloot_list_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         } 
        
    }
	
	

    public function pdf_stock()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->stock_purchase->search_report_data();
        $this->load->view('stock_allotment_report/stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("allot_report_".time().".pdf");
    }
    public function print_stock()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->stock_purchase->search_report_data();
      $this->load->view('stock_allotment_report/stock_report_html',$data); 
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
        $this->session->unset_userdata('stock_purchase_search');
    }
     
  function get_vandor_data($vendor_id="")
  {
    $data['vendor_list'] = $this->stock_purchase->vendor_list($vendor_id);
   
    $html='<div class="row m-b-5"> <div class="col-md-4"><label>Code</label></div><div class="col-md-8">
                  '.$data['vendor_list'][0]->vendor_id.'<input type="hidden" value="'.$data['vendor_list'][0]->vendor_id.'" name="vendor_code"/> </div></div> <div class="row m-b-5"> <div class="col-md-4">  <label>Name</label>
                </div> <div class="col-md-8">  '.$data['vendor_list'][0]->name.'<input type="hidden" value="'.$data['vendor_list'][0]->name.'" name="vendor_name"/></div></div><div class="row m-b-5"><div class="col-md-4">
                  <label>Address</label></div><div class="col-md-8"> '.$data['vendor_list'][0]->address.'<input type="hidden" value="'.$data['vendor_list'][0]->address.', '.$data['vendor_list'][0]->address2.', '.$data['vendor_list'][0]->address3.'" name="address"/> </div></div>';
    echo $html;exit;
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
     $html.='<div class="row m-b-5"> <div class="col-md-5"><label>'.$payment_detail->field_name.'<span class="star">*</span></label></div><div class="col-md-7"><input type="text" name="field_name[]" value="" onkeypress="payment_calc_all();"><input type="hidden" value="'.$payment_detail->id.'" name="field_id[]" /><div class="">'.$var_form_error.'</div></div></div>';
  }
  echo $html;exit;
  
}

  
//add bottom 4 feb 20

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
          $data['item_new_list'] = $this->stock_purchase->get_item_values($item_ids);
           foreach($data['item_new_list'] as $item_list){
                           $ids[]=$item_list->id;
           }
        }
        $this->load->model('item_manage/item_manage_model','item_manage');
       
      
        $keywords= $this->input->post('search_keyword');
        $name= $this->input->post('name'); 
        if(!empty($post['item']) || !empty($post['item_code']) || !empty($post['company_name']) || !empty($post['conversion']) || !empty($post['mfc_date']) || !empty($post['unit1']) ||  !empty($post['unit2']) || !empty($post['mrp']) || $post['mrp']==0 || !empty($post['p_rate']) || !empty($post['discount']) || $post['discount']==0 || !empty($post['cgst']) || $post['cgst']==0 || !empty($post['igst']) || $post['igst']==0 || !empty($post['sgst']) || $post['sgst']==0 ||!empty($post['packing']))
        { 

        
          $result_item = $this->stock_purchase->item_list_search();  

      //print_r($result_item); die;


        } 

        if(count($result_item)>0 && isset($result_item) || !empty($ids))
        {
          foreach($result_item as $item)
          {
              if(!in_array($item->id,$ids))
              {
                  $table.='<tr class="append_row">';
                  $table.='<td><input type="checkbox" name="test_id[]" class="child_checkbox" value="'.$item->id.'" onclick="add_check();"></td>';

                  $table.='<td>'.$item->item.'</a></td>';

                  $table.='<td>'.$item->packing.'</td>';
                  $table.='<td>'.$item->conversion.'</td>';
                  $table.='<td>'.$item->item_code.'</td>';
                  
                  $table.='<td>'.$item->company_name.'</td>';
                 
                  // $table.='<td>'.date('d-m-Y',strtotime($item->created_date)).'</td>';
                  if($item->type==3){
                  $table.='<td>'.$item->unit1.'</td>';} 
                  else {
                  $table.='<td>'.$item->unit_id.'</td>';}

                  if($item->type==3){
                  $table.='<td>'.$item->unit2.'</td>';} 
                  else {
                  $table.='<td>'.$item->second_unit.'</td>';}

                  $table.='<td>'.$item->mrp.'</td>';
                  $table.='<td>'.$item->price.'</td>';
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


public function ajax_added_item(){
        //$this->load->model('item_manage/item_manage_model','item_manage');
         $item_sess = $this->session->userdata('item_id');
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
          $result_item = $this->stock_purchase->item_list($item_ids);
           foreach($result_item as $item_list){
//echo '<pre>';print_r($item_sess);die;

             $ids[]=$item_list->id;
           }
        }
     // $setting_data
                      $table='<div class=" box_scroll">';
                      $table.='<table class="table table-bordered table-striped m_pur_tbl1">';
                      $table.='<thead class="bg-theme">';
                      $table.='<tr>';
                      $table.='<th class="40" align="center">';
                       $table.='<input type="checkbox" name="" onClick="toggle_new(this);">';
                       $table.='</th>';
                        $table.='<th>Item Name</th>';
                        $table.='<th>Item Code</th>';
                       
                        $table.='<th>Packing</th>';
                    
                        $table.='<th>Conv.</th>';
                        $table.='<th>Mfg. Date</th>';
                        $table.='<th>Exp. Date</th>';
                       
                        $table.='<th>Unit1</th>';
                        $table.='<th>Unit2</th>';
                         $table.='<th>Free Unit1</th>';
                        $table.='<th>Free Unit2</th>';
                
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

//echo '<pre>';print_r($item);die;


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
                        $table.='<tr><input type="hidden" id="item_id_'.$item->id.'" name="item_id[]" value="'.$item->id.'"/>
                            <input type="hidden" value="'.$item->conversion.'"  name="conversion[]" id="conversion_'.$item->id.'" />';
                        $table.='<td><input type="checkbox" name="item_id[]" class="booked_checkbox" value="'.$item->id.'"></td>';
                        $table.='<td><input type="hidden" value="'.$item->item.'" name="item[]" id="item_'.$item->id.'" />'.$item->item.'</td>';
                        $table.='<td>'.$item->item_code.' </td>';

                        $table.='<td>'.$item->packing.'</td>';

                        $table.='<td>'.$item->conversion.'</td>';

                        $table.='<td><input type="text" value="'.$date_newma.'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script1.'</td>';

                        $table.='<td><input type="text" value="'.$date_new.'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'.$item->id.'" onchange="payment_cal_perrow('.$item->id.');"/>'.$check_script.'</td>';
              
                        $table.='<td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'.$item->id.'" value="'.$item_sess[$item->id]["unit1"].'" onkeyup="payment_cal_perrow('.$item->id.');"/>
                        <span id="unit1_max_'.$item->id.'" class="text-danger"></span></td>';
                        $table.='<td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'.$item->id.'" value="'.$item_sess[$item->id]["unit2"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'.$item->id.'" value="'.$item_sess[$item->id]["freeunit1"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                        $table.='<td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'.$item->id.'" value="'.$item_sess[$item->id]["freeunit2"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                       // $table.='<td></td>';
                        $table.='<td><input type="text" id="mrp_'.$item->id.'" class="w-60px" name="mrp[]" value="'.$item_sess[$item->id]["mrp"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" id="purchase_rate_'.$item->id.'" class="w-60px" name="purchase_rate[]" value="'.$item_sess[$item->id]["purchase_amount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        $table.='<td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'.$item->id.'" value="'.$item_sess[$item->id]["discount"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';
                       /* $table.='<td><input type="text" class="price_float" name="vat[]" placeholder="Vat" style="width:59px;" value="'.$item_sess[$item->id]["vat"].'" id="vat_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';*/
                        $table.='<td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'.$item_sess[$item->id]["cgst"].'" id="cgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                         $table.='<td><input type="text" class="price_float" name="SGST[]" placeholder="SGST" style="width:59px;" value="'.$item_sess[$item->id]["sgst"].'" id="sgst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                          $table.='<td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'.$item_sess[$item->id]["igst"].'" id="igst_'.$item->id.'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';

                        /*$table.='<td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'.$item->id.'" value="'.$item_sess[$item->id]["qty"].'" onkeyup="payment_cal_perrow('.$item->id.');"/></td>';*/
                      
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





 public function set_item()
    {
       $this->load->model('item_manage/item_manage_model','item_manage');
       
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
            foreach($post['item_id'] as $ids)
            {
               $item_data = $this->stock_purchase->item_list($ids);

 //print_r($item_data); die;

                $ratewithunit1= $item_data[0]->purchase_rate*0;
                $qty=0;
                //$ratewithunit1= $item_data[0]->purchase_rate*$post['unit1'];
                $perpic_rate=$item_data[0]->purchase_rate/$item_data[0]->conversion;
                $ratewithunit2=$perpic_rate*0;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;

               /* $cgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($item_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                 $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;

                $post_iid_arr[$ids] = array('item_id'=>$ids,'unit1'=>0,'unit2'=>0,'item'=>0,'conversion'=>0,'perpic_rate'=>$perpic_rate,'manuf_date'=>'00-00-0000','freeunit1'=>0,'freeunit2'=>0,'qty'=>'1','freeqty'=>'1', 'exp_date'=>'00-00-0000','discount'=>$item_data[0]->discount,'mrp'=>$item_data[0]->mrp,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'qty'=>$qty,'purchase_amount'=>$item_data[0]->purchase_rate, 'total_amount'=>$total_amount); 
                $iid_arr[] = $ids;
                $i++;
            } 
            
            $item_id = $purchase+$post_iid_arr;
            
         } 
         else
         {
            $total_amount=0;
            $i=0;

            foreach($post['item_id'] as $ids)
            {

              $item_data = $this->stock_purchase->item_list($ids);


              $ratewithunit1= $item_data[0]->purchase_rate*0;
              //$ratewithunit1= $item_data[0]->purchase_rate*$post['unit1'];
              $qty=0;
              $freeqty=0;
              $perpic_rate=$item_data[0]->purchase_rate/$item_data[0]->conversion;
              $ratewithunit2=$perpic_rate*0;
              $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                
                /*$cgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_qty_with_rate / 100) * $item_data[0]->sgst;
                $totalPrice = $tot_qty_with_rate + $cgstToPay +$igstToPay + $sgstToPay;
                $total_discount = ($item_data[0]->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;*/

                $total_discount = ($item_data[0]->discount/100)*$tot_qty_with_rate;
                $tot_price=$tot_qty_with_rate-$total_discount;

                $cgstToPay = ($tot_price / 100) * $item_data[0]->cgst;
                $igstToPay = ($tot_price / 100) * $item_data[0]->igst;
                $sgstToPay = ($tot_price / 100) * $item_data[0]->sgst;
                $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;



                $item_data = $this->stock_purchase->item_list($ids);

//echo '<pre>';print_r($item_data);die;
   

                $post_iid_arr[$ids] = array('item_id'=>$ids,'unit1'=>0,'unit2'=>0,'item'=>0,'conversion'=>0,'batch_no'=>0,'manuf_date'=>'00-00-0000','perpic_rate'=>$perpic_rate,'freeunit1'=>0,'freeunit2'=>0,'qty'=>'1', 'exp_date'=>'00-00-0000', 'purchase_amount'=>$item_data[0]->purchase_rate,'mrp'=>$item_data[0]->mrp,'qty'=>$qty,'freeqty'=>$freeqty,'discount'=>$item_data[0]->discount,'cgst'=>$item_data[0]->cgst,'igst'=>$item_data[0]->igst,'sgst'=>$item_data[0]->sgst,'total_amount'=>$total_amount); 
                $iid_arr[] = $ids;
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
        $this->load->model('item_manage/item_manage_model','item_manage');
      
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
           
           $item_list = [];
           $ids_list = $this->session->userdata('item_id');  
         
           $this->ajax_added_item();
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
          $signal_unit1_price = $item['purchase_rate']*$item['unit1'];
          $signal_unit2_price = ($item['purchase_rate']/$item['conversion'])*$item['unit2'];
          }
          else{
            $signal_unit1_price = $item['purchase_amount']*$item['unit1'];
            $signal_unit2_price = ($item['purchase_amount']/$item['conversion'])*$item['unit2'];
            }
            $total_amount += $signal_unit1_price+$signal_unit2_price;
            $total_row_amount = ($signal_unit1_price+$signal_unit2_price)-(($signal_unit1_price+$signal_unit2_price)/100*$item['discount']);
            $total_cgst += ($total_row_amount/100)*$item['cgst']; 
            $total_sgst += ($total_row_amount/100)*$item['sgst'];
            $total_igst += ($total_row_amount/100)*$item['igst']; 
            $tot_discount_amount+= ($signal_unit1_price+$signal_unit2_price)/100*$item['discount'];
            $i++;
        } 


            if($post['discount']!='' && $post['discount']!=0)
            {
            $total_discount_perc = ($post['discount']/100)* $total_amount;
            $total_discount = round($total_discount_perc);
            }
         $total_medicine_discount=$tot_discount_amount; //total item discount

        $net_amount = ($total_amount-$total_discount)+$total_cgst+$total_igst+$total_sgst-$tot_discount_amount;
         if($post['pay']==1 || $post['data_id']!=''){
           $payamount=$post['pay_amount'];
        }else{
          $payamount=$net_amount;
        }
         
      
        $blance_due=$net_amount - $payamount;
        $total_igst = number_format($total_igst,2,'.','');
        $total_igst = number_format($total_igst,2,'.','');
        $total_sgst = number_format($total_sgst,2,'.','');
       

        $pay_arr = array('total_amount'=>number_format($total_amount,2,'.',''), 'net_amount'=>number_format($net_amount,2,'.',''),'pay_amount'=>number_format($payamount,2,'.',''),'discount'=>$post['discount'],'igst'=>$post['igst'],'sgst'=>$post['sgst'],'cgst'=>$post['cgst'],'sgst_amount'=>$total_sgst,'igst_amount'=>$total_igst,'cgst_amount'=> $total_cgst,'balance_due'=>number_format($blance_due,2,'.',''),'discount_amount'=>number_format($total_discount,2,'.',''),'item_discount'=>number_format($total_medicine_discount,2,'.',''));
        $json = json_encode($pay_arr,true);
        echo $json;die;
        
       }
        
    } 


 public function payment_cal_perrow()
    {
       $this->load->model('item_manage/item_manage_model','item_manage');

 //$post = $this->input->post();
 //echo "<pre>"; print_r($post); exit;

       $post = $this->input->post();
       $total_amount='';
       if(isset($post) && !empty($post))
       {
         $total_amount = 0;
         $item_list = $this->session->userdata('item_id');
     //print_r($item_list);die;
        
         if(!empty($item_list))
         { 
            $item_data = $this->stock_purchase->item_list($post['item_id']);



            $ratewithunit1= $post['purchase_rate']*$post['unit1'];
           // print_r($ratewithunit1);die;
            $perpic_rate=  $post['purchase_rate']/$post['conversion'];
            //print_r($perpic_rate);die;
//echo $perpic_rate;
            $ratewithunit2=$perpic_rate*$post['unit2'];
            $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
            //echo $tot_qty_with_rate;
            //$tot_qty_with_rate= $item_data[0]->purchase_rate*$post['unit1'];
            $qty=($post['conversion']*$post['unit1'])+$post['unit2'];
            $freeqty=($post['conversion']*$post['freeunit1'])+$post['freeunit2'];
            //echo $qty;
            $total_discount = ($post['discount']/100)*$tot_qty_with_rate;
            $tot_price=$tot_qty_with_rate-$total_discount;

            $cgstToPay = ($tot_price / 100) * $post['cgst'];
            $igstToPay = ($tot_price / 100) * $post['igst'];
            $sgstToPay = ($tot_price / 100) * $post['sgst'];
            $total_amount = $total_amount+$tot_price+ $cgstToPay+$igstToPay+$sgstToPay;



             $item_list[$post['item_id']] =  array('item_id'=>$post['item_id'],'item'=>$post['item'],'freeunit1'=>$post['freeunit1'],'freeunit2'=>$post['freeunit2'],'unit1'=>$post['unit1'],'unit2'=>$post['unit2'],'manuf_date'=>$post['manuf_date'],'conversion'=>$post['conversion'],'perpic_rate'=>$perpic_rate,'exp_date'=>$post['expiry_date'],'qty'=>$qty,'freeqty'=>$freeqty,'mrp'=>$post['mrp'],'sgst'=>$post['sgst'],'igst'=>$post['igst'],'cgst'=>$post['cgst'],'discount'=>$post['discount'],'purchase_amount'=>$post['purchase_rate'], 'total_amount'=>$total_amount,'total_price'=>$total_amount); 
            $this->session->set_userdata('item_id', $item_list);
            $pay_arr = array('total_amount'=>number_format($total_amount,2));
            $json = json_encode($pay_arr,true);
            echo $json;
         }
       }
    }


  function check_unique_value($invoice='', $id='') 
  {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->stock_purchase->check_unique_value($users_data['parent_id'], $invoice, $id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'This Invoice Number already Registered.');
            $response = false;
        }
        return $response;
    }

   /********** print**************/  
  public function print_stock_issue_purchase($ids=""){

      $user_detail= $this->session->userdata('auth_users');
      $data['page_title'] = "Stock issue purchase";
	  $this->load->model('stock_purchase/stock_purchase_model','stock_purchase');
      $this->load->model('general/general_model');
      if(!empty($ids)){
      $purchase= $ids;
      }else{
      $purchase= $this->session->userdata('purchase');
      }
      $get_detail_by_id= $this->stock_purchase->get_by_id($purchase);
      $get_by_id_data=$this->stock_purchase->get_all_detail_print($purchase,$get_detail_by_id['branch_id']);
	  
  //print '<pre>';print_r($get_by_id_data);die;
       $get_payment_detail= $this->general_model->payment_mode_by_id('',$get_detail_by_id['payment_mode']);
      $template_format= $this->stock_purchase->template_format(array('section_id'=>9,'types'=>1,'sub_section'=>1,'branch_id'=>$get_detail_by_id['branch_id']));
      $data['template_data']=$template_format;
      $data['payment_mode']= $get_payment_detail;
      $data['user_detail']=$user_detail;
      $data['all_detail']= $get_by_id_data;
      $this->load->view('stock_purchase/print_template_stock_purchase',$data);
  }


}


?>