<?php 
//print '<pre>'; print_r($all_detail['garbage_list']);
$template_data->template = str_replace("{item_discount}",$all_detail['garbage_list'][0]->item_discount,$template_data->template);
$payment_mode=$payment_mode[0]->payment_mode;
if($template_data->printer_id==2)
{
	
	$template_data->template = str_replace("{vendor_name}",$all_detail['garbage_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{garbage_date}",date('d-m-Y',strtotime($all_detail['garbage_list'][0]->garbage_date)),$template_data->template);
	$template_data->template = str_replace("{garbage_code}",$all_detail['garbage_list'][0]->garbage_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->sales_name,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['garbage_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['garbage_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['garbage_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['garbage_list']);
	foreach($all_detail['item_list'] as $garbage_list)
	{ 
	    //  print_r($garbage_list);
        $tr = $row_loop;
        $tr = str_replace("{s_no}",$i,$tr);
        $tr = str_replace("{item_quantity}",$garbage_list->quantity,$tr);
        $tr = str_replace("{item_price}",$garbage_list->item_price,$tr);
        $tr = str_replace("{item_name}",$garbage_list->item_name,$tr);
        $tr = str_replace("{item_category}",$garbage_list->category,$tr);
        $tr = str_replace("{item_code}",$garbage_list->item_code,$tr);
        $tr = str_replace("{item_unit}",$garbage_list->unit,$tr);
        $tr = str_replace("{item_amount}",$garbage_list->total_amount,$tr);
        $tr_html .= $tr;
        $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['garbage_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->username,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['garbage_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['garbage_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['garbage_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['garbage_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
$this->session->unset_userdata('garbagess_id');
}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
	
	$template_data->template = str_replace("{vendor_name}",$all_detail['garbage_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{garbage_date}",date('d-m-Y',strtotime($all_detail['garbage_list'][0]->garbage_date)),$template_data->template);
	$template_data->template = str_replace("{garbage_code}",$all_detail['garbage_list'][0]->garbage_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->username,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['garbage_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['garbage_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['garbage_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['garbage_list']);
	foreach($all_detail['item_list'] as $garbage_list)
	{ 
	    //  print_r($garbage_list);
         $tr = $row_loop;
        $tr = str_replace("{s_no}",$i,$tr);
        $tr = str_replace("{item_quantity}",$garbage_list->quantity,$tr);
        $tr = str_replace("{item_price}",$garbage_list->item_price,$tr);
        $tr = str_replace("{item_name}",$garbage_list->item_name,$tr);
        $tr = str_replace("{item_category}",$garbage_list->category,$tr);
        $tr = str_replace("{item_code}",$garbage_list->item_code,$tr);
        $tr = str_replace("{item_unit}",$garbage_list->unit,$tr);
        $tr = str_replace("{item_amount}",$garbage_list->total_amount,$tr);
        $tr_html .= $tr;
        $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['garbage_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->username,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['garbage_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['garbage_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['garbage_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['garbage_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
$this->session->unset_userdata('garbagess_id');
}
/* end dot printing */

/* start leaser printing */

if($template_data->printer_id==1)
{



	$template_data->template = str_replace("{vendor_name}",$all_detail['garbage_list'][0]->vendor_name,$template_data->template);
	$template_data->template = str_replace("{garbage_date}",date('d-m-Y',strtotime($all_detail['garbage_list'][0]->garbage_date)),$template_data->template);
	$template_data->template = str_replace("{garbage_code}",$all_detail['garbage_list'][0]->garbage_no,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->username,$template_data->template);
	$template_data->template = str_replace("{vendor_email}",$all_detail['garbage_list'][0]->vendor_email,$template_data->template);
	$template_data->template = str_replace("{vendor_mobile}",$all_detail['garbage_list'][0]->vendor_mobile,$template_data->template);
	$template_data->template = str_replace("{vendor_address}",$all_detail['garbage_list'][0]->vendor_address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['item_list']); die;
	foreach($all_detail['item_list'] as $garbage_list)
	{ 
        //  print_r($garbage_list);
         $tr = $row_loop;
        $tr = str_replace("{s_no}",$i,$tr);
        $tr = str_replace("{item_quantity}",$garbage_list->quantity,$tr);
        $tr = str_replace("{item_price}",$garbage_list->item_price,$tr);
        $tr = str_replace("{item_name}",$garbage_list->item_name,$tr);
        $tr = str_replace("{item_category}",$garbage_list->category,$tr);
        $tr = str_replace("{item_code}",$garbage_list->item_code,$tr);
        $tr = str_replace("{item_unit}",$garbage_list->unit,$tr);
        $tr = str_replace("{item_amount}",$garbage_list->total_amount,$tr);
        $tr_html .= $tr;
        $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['garbage_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['garbage_list'][0]->username,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['garbage_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['garbage_list'][0]->user_name),$template_data->template);
}

if(!empty($all_detail['garbage_list'][0]->remarks))
{
 $template_data->template = str_replace("{remarks}",$all_detail['garbage_list'][0]->remarks,$template_data->template);
}
else
{
 $template_data->template = str_replace("{remarks}",' ',$template_data->template);
 $template_data->template = str_replace("Remarks :",' ',$template_data->template);
 $template_data->template = str_replace("Remarks",' ',$template_data->template);
}
$this->session->unset_userdata('allotment');
echo $template_data->template;
$this->session->unset_userdata('garbagess_id');
}

/* end leaser printing*/
?>






