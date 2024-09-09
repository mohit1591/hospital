<?php 
/* start thermal printing */
if($template_data->printer_id==2){
 
$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->indent,$template_data->template);


$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->sale_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

//////////////////////// 
$i=1;
$tr_html = "";
foreach($all_detail['sales_list']['medicine_list'] as $medicine_list)
{ 
   $tr = $row_loop;
   $tr = str_replace("{sn}",$i,$tr);
   $tr = str_replace("{medicine_name}",$medicine_list->medicine_name,$tr);
   $tr = str_replace("{medicine_per_net_amount}",$medicine_list->mrp,$tr);
   $tr_html .= $tr;
   $i++;

}
//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);


$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);
$this->session->unset_userdata('sales_id');
 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
  
    
  $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->indent,$template_data->template);


$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->sale_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
//////////////////////// 
$i=1;
$j=0;
$tr_html = "";
$total_quantity_amt=0;
$total_discount_amt=0;
$total_vat_amt=0;
$total_mrp=0;
$tot_medicine=0;
$total_cgst=0;
$total_sgst=0;
$total_igst=0;
foreach($all_detail['sales_list']['medicine_list'] as $medicine_list)
{ 
    
    $exc ='';
	if(date('d-m-Y',strtotime($medicine_list->m_expiry_date))!='01-01-1970')
	{
	   $expiryDate = date('d-m-Y',strtotime($medicine_list->m_expiry_date));
	   $expiryDateYear = date('y',strtotime($medicine_list->m_expiry_date));
	   $expiryDatemonth = date('m',strtotime($medicine_list->m_expiry_date));
	   $exc = $expiryDatemonth.'/'.$expiryDateYear;
	}
	
  $tr = $row_loop;
  $tot_medicine=$tot_medicine+$i;
  $tr = str_replace("{s_no}",$i,$tr);
  $total_quantity_amt=$total_quantity_amt+$medicine_list->qty;
  $total_discount_amt=$total_discount_amt+$medicine_list->discount;
  $total_vat_amt=$total_vat_amt+$medicine_list->vat;
  $total_mrp=$total_mrp+$medicine_list->mrp;
        $total_cgst=$total_cgst+$medicine_list->m_cgst;
    $total_sgst=$total_sgst+$medicine_list->m_sgst;
    $total_igst=$total_igst+$medicine_list->m_igst;
  $tr = str_replace("{medicine_qty}",$medicine_list->qty,$tr);
   $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",$exc,$tr); //date('d-m-Y',strtotime($medicine_list->m_expiry_date))
    $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
  $tr = str_replace("{medicine_per_discount}",$medicine_list->discount,$tr);
  $tr = str_replace("{medicine_per_vat}",$medicine_list->vat,$tr);
  $tr = str_replace("{medicine_per_price}",$medicine_list->per_pic_price,$tr);
    $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);

      $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
      $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
  $tr = str_replace("{medicine_name}",$medicine_list->medicine_name,$tr);
  $tr = str_replace("{medicine_per_net_amount}",$medicine_list->mrp,$tr);
  $tr_html .= $tr;
  $i++;
  $j++;

}
//echo $i;

$template_data->template = str_replace("{total_cgst}",$total_cgst,$template_data->template);
$template_data->template = str_replace("{total_sgst}",$total_sgst,$template_data->template);
$template_data->template = str_replace("{total_igst}",$total_igst,$template_data->template);
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_medicine}",$j,$template_data->template);
$template_data->template = str_replace("{total_quantity}",$total_quantity_amt,$template_data->template);
$template_data->template = str_replace("{total_discount_amt}",$total_discount_amt,$template_data->template);
$template_data->template = str_replace("{total_vat_amt}",$total_vat_amt,$template_data->template);
$template_data->template = str_replace("{total_amt_per}",$total_mrp,$template_data->template);
$template_data->template = str_replace("{total_per_price_amt}",$total_mrp,$template_data->template);
//echo $i;

$template_data->template = str_replace("{signature}",ucfirst($$user_detail['user_name']),$template_data->template);
if(!empty($all_detail['sales_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remarks,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
        }
        $this->session->unset_userdata('sales_id');
  echo $template_data->template;
}

if($template_data->printer_id==1){
   

  
  $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->indent,$template_data->template);
 
   

$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);
$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->sale_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));

$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
//////////////////////// 
$i=1;
$tr_html = "";
foreach($all_detail['sales_list']['medicine_list'] as $medicine_list)
{ 
    $exc ='';
	if(date('d-m-Y',strtotime($medicine_list->m_expiry_date))!='01-01-1970')
	{
	   $expiryDate = date('d-m-Y',strtotime($medicine_list->m_expiry_date));
	   $expiryDateYear = date('y',strtotime($medicine_list->m_expiry_date));
	   $expiryDatemonth = date('m',strtotime($medicine_list->m_expiry_date));
	   $exc = $expiryDatemonth.'/'.$expiryDateYear;
	}

  //print_r($medicine_list);
   $tr = $row_loop;
   $tr = str_replace("{s_no}",$i,$tr);
   $tr = str_replace("{quantity}",$medicine_list->qty,$tr);
    $tr = str_replace("{mrp}",$medicine_list->per_pic_price,$tr);
   $tr = str_replace("{medicine_name}",$medicine_list->medicine_name,$tr);
   $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
      $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
       $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",$exc,$tr); //date('d-m-Y',strtotime($medicine_list->m_expiry_date))
    $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
      $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
       $tr = str_replace("{discount}",$medicine_list->discount,$tr);
   $tr = str_replace("{total_amount}",$medicine_list->total_amount,$tr);
   $tr_html .= $tr;
   $i++;

}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);


$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);
if(!empty($all_detail['sales_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remarks,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks :",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
        }
  $this->session->unset_userdata('sales_id');
  echo $template_data->template;
}

/* end leaser printing*/
?>

