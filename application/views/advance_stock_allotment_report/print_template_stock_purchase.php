<?php 
//print '<pre>'; print_r($all_detail['purchases_list']);die;

$template_data->template = str_replace("{item_discount}",$all_detail['purchases_list'][0]->item_discount,$template_data->template);
$payment_mode=$payment_mode[0]->payment_mode;
if($template_data->printer_id==2)
{
	
	$template_data->template = str_replace("{vendor_name}",$all_detail['purchases_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{purchase_date}",date('d-m-Y',strtotime($all_detail['purchases_list'][0]->purchase_date)),$template_data->template);
	$template_data->template = str_replace("{purchase_no}",$all_detail['purchases_list'][0]->purchase_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->sales_name,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['purchases_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['purchases_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['purchases_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['purchases_list']);
	foreach($all_detail['purchases_list']['item_list'] as $purchases_list)
	{ 
	  //  print_r($purchases_list);
  $tr = $row_loop;
  $tr = str_replace("{s_no}",$i,$tr);
  $tr = str_replace("{quantity}",$purchases_list->quantity,$tr);
  $tr = str_replace("{mrp}",$purchases_list->per_pic_price,$tr);
  $tr = str_replace("{item_name}",$purchases_list->item_name,$tr);
  $tr = str_replace("{cgst}",$purchases_list->cgst,$tr);
  $tr = str_replace("{sgst}",$purchases_list->sgst,$tr);
  $tr = str_replace("{batch_no}",$purchases_list->batch_no,$tr);
  $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($purchases_list->expiry_date)),$tr);
  $tr = str_replace("{igst}",$purchases_list->igst,$tr);
  $tr = str_replace("{discount}",$purchases_list->discount,$tr);
  $tr = str_replace("{total_amount}",$purchases_list->total_amount,$tr);
  $tr_html .= $tr;
  $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['purchases_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['purchases_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['purchases_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchases_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['purchases_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['purchases_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['purchases_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchases_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchases_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['purchases_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['purchases_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['purchases_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['purchases_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
	
	$template_data->template = str_replace("{vendor_name}",$all_detail['purchases_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{purchase_date}",date('d-m-Y',strtotime($all_detail['purchases_list'][0]->purchase_date)),$template_data->template);
	$template_data->template = str_replace("{purchase_no}",$all_detail['purchases_list'][0]->purchase_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->sales_name,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['purchases_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['purchases_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['purchases_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['purchases_list']);
	foreach($all_detail['purchases_list']['item_list'] as $purchases_list)
	{ 
	  //  print_r($purchases_list);
  $tr = $row_loop;
  $tr = str_replace("{s_no}",$i,$tr);
  $tr = str_replace("{quantity}",$purchases_list->quantity,$tr);
  $tr = str_replace("{mrp}",$purchases_list->per_pic_price,$tr);
  $tr = str_replace("{item_name}",$purchases_list->item_name,$tr);
  $tr = str_replace("{cgst}",$purchases_list->cgst,$tr);
  $tr = str_replace("{sgst}",$purchases_list->sgst,$tr);
  $tr = str_replace("{batch_no}",$purchases_list->batch_no,$tr);
  $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($purchases_list->expiry_date)),$tr);
  $tr = str_replace("{igst}",$purchases_list->igst,$tr);
  $tr = str_replace("{discount}",$purchases_list->discount,$tr);
  $tr = str_replace("{total_amount}",$purchases_list->total_amount,$tr);
  $tr_html .= $tr;
  $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['purchases_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['purchases_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['purchases_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchases_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['purchases_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['purchases_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['purchases_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchases_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchases_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['purchases_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['purchases_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['purchases_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['purchases_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
}
/* end dot printing */

/* start leaser printing */

if($template_data->printer_id==1)
{
	
	$template_data->template = str_replace("{vendor_name}",$all_detail['purchases_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{purchase_date}",date('d-m-Y',strtotime($all_detail['purchases_list'][0]->purchase_date)),$template_data->template);
	$template_data->template = str_replace("{purchase_no}",$all_detail['purchases_list'][0]->purchase_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->sales_name,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['purchases_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['purchases_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['purchases_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['purchases_list']);
	foreach($all_detail['purchases_list']['item_list'] as $purchases_list)
	{ 
	  //  print_r($purchases_list);
  $tr = $row_loop;
  $tr = str_replace("{s_no}",$i,$tr);
  $tr = str_replace("{quantity}",$purchases_list->quantity,$tr);
  $tr = str_replace("{mrp}",$purchases_list->per_pic_price,$tr);
  $tr = str_replace("{item_name}",$purchases_list->item_name,$tr);
  $tr = str_replace("{cgst}",$purchases_list->cgst,$tr);
  $tr = str_replace("{sgst}",$purchases_list->sgst,$tr);
  $tr = str_replace("{batch_no}",$purchases_list->batch_no,$tr);
  $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($purchases_list->expiry_date)),$tr);
  $tr = str_replace("{igst}",$purchases_list->igst,$tr);
  $tr = str_replace("{discount}",$purchases_list->discount,$tr);
  $tr = str_replace("{total_amount}",$purchases_list->total_amount,$tr);
  $tr_html .= $tr;
  $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['purchases_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['purchases_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['purchases_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['purchases_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['purchases_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['purchases_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['purchases_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['purchases_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['purchases_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['purchases_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['purchases_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['purchases_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['purchases_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['purchases_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['purchases_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
}

/* end leaser printing*/
?>






