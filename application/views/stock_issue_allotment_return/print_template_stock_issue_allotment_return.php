<?php 
//print '<pre>'; print_r($all_detail['sales_list']);die;

$template_data->template = str_replace("{item_discount}",$all_detail['sales_list'][0]->item_discount,$template_data->template);
$payment_mode=$payment_mode[0]->payment_mode;

$template_data->template = str_replace("{payment_due_date}",'',$template_data->template);

$template_data->template = str_replace("Pay. Due Date:",'',$template_data->template);

$template_data->template = str_replace("{customer_code}",'',$template_data->template);

$template_data->template = str_replace("Customer Code:",'',$template_data->template);

if($template_data->printer_id==2)
{
	
	$template_data->template = str_replace("{member_name}",$all_detail['sales_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['sales_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['sales_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['sales_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['sales_list']);
	foreach($all_detail['sales_list']['item_list'] as $sales_list)
	{ 
	  //  print_r($sales_list);
	  
	    $serial_no=array();
        $purchase_item_serial =get_issue_return_serial_no_item($sales_list->issue_return_id,$sales_list->item_id);
        foreach ($purchase_item_serial as  $serial) 
        {
            array_push($serial_no, $serial->serial_no);
        } 
        if(!empty($serial_no))
        {
           $serial_data=implode(",", $serial_no); 
           $mnd = " (".$serial_data.")";
        }
        else
        {
            $mnd ='';
        }
          $tr = $row_loop;
          $tr = str_replace("{s_no}",$i,$tr);
          $tr = str_replace("{quantity}",$sales_list->quantity,$tr);
          $tr = str_replace("{mrp}",$sales_list->per_pic_price,$tr);
          $tr = str_replace("{purchase_amount}",$sales_list->mrp,$tr);
          $tr = str_replace("{item_name}",$sales_list->item_name.$mnd,$tr);
          $tr = str_replace("{cgst}",$sales_list->cgst,$tr);
          $tr = str_replace("{sgst}",$sales_list->sgst,$tr);
          $tr = str_replace("{batch_no}",$sales_list->batch_no,$tr);
          $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($sales_list->expiry_date)),$tr);
          $tr = str_replace("{igst}",$sales_list->igst,$tr);
          $tr = str_replace("{discount}",$sales_list->discount,$tr);
          $tr = str_replace("{total_amount}",$sales_list->total_amount,$tr);
          $tr_html .= $tr;
          $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['sales_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$template_data->template);
}

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
$this->session->unset_userdata('allotment_id');
echo $template_data->template;
}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
	
	$template_data->template = str_replace("{member_name}",$all_detail['sales_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['sales_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['sales_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['sales_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['sales_list']);
	foreach($all_detail['sales_list']['item_list'] as $sales_list)
	{ 
	  //  print_r($sales_list);
	   $serial_no=array();
        $purchase_item_serial =get_issue_return_serial_no_item($sales_list->issue_return_id,$sales_list->item_id);
        foreach ($purchase_item_serial as  $serial) 
        {
            array_push($serial_no, $serial->serial_no);
        } 
        if(!empty($serial_no))
        {
           $serial_data=implode(",", $serial_no); 
           $mnd = " (".$serial_data.")";
        }
        else
        {
            $mnd ='';
        }
      $tr = $row_loop;
      $tr = str_replace("{s_no}",$i,$tr);
      $tr = str_replace("{quantity}",$sales_list->quantity,$tr);
      $tr = str_replace("{mrp}",$sales_list->per_pic_price,$tr);
      $tr = str_replace("{purchase_amount}",$sales_list->mrp,$tr);
      $tr = str_replace("{item_name}",$sales_list->item_name.$mnd,$tr);
      $tr = str_replace("{cgst}",$sales_list->cgst,$tr);
      $tr = str_replace("{sgst}",$sales_list->sgst,$tr);
      $tr = str_replace("{batch_no}",$sales_list->batch_no,$tr);
      $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($sales_list->expiry_date)),$tr);
      $tr = str_replace("{igst}",$sales_list->igst,$tr);
      $tr = str_replace("{discount}",$sales_list->discount,$tr);
      $tr = str_replace("{total_amount}",$sales_list->total_amount,$tr);
      $tr_html .= $tr;
      $i++;
	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['sales_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$template_data->template);
}

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
$this->session->unset_userdata('allotment_id');
echo $template_data->template;
}
/* end dot printing */

/* start leaser printing */

if($template_data->printer_id==1)
{
	
	$template_data->template = str_replace("{member_name}",$all_detail['sales_list'][0]->member_name,$template_data->template);
	$template_data->template = str_replace("{issue_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->issue_date)),$template_data->template);
	$template_data->template = str_replace("{issue_no}",$all_detail['sales_list'][0]->return_no,$template_data->template);

	$template_data->template = str_replace("{email}",$all_detail['sales_list'][0]->email,$template_data->template);
	$template_data->template = str_replace("{mobile}",$all_detail['sales_list'][0]->mobile,$template_data->template);
	$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);
	
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
	//print '<pre>'; print_r($all_detail['purchase_list']['sales_list']);
	foreach($all_detail['sales_list']['item_list'] as $sales_list)
	{ 
	  //  print_r($sales_list);
	  $serial_no=array();
        $purchase_item_serial =get_issue_return_serial_no_item($sales_list->issue_return_id,$sales_list->item_id);
        foreach ($purchase_item_serial as  $serial) 
        {
            array_push($serial_no, $serial->serial_no);
        } 
        if(!empty($serial_no))
        {
           $serial_data=implode(",", $serial_no); 
           $mnd = " (".$serial_data.")";
        }
        else
        {
            $mnd ='';
        }
          $tr = $row_loop;
          $tr = str_replace("{s_no}",$i,$tr);
          $tr = str_replace("{quantity}",$sales_list->quantity,$tr);
          $tr = str_replace("{mrp}",$sales_list->per_pic_price,$tr);
          $tr = str_replace("{purchase_amount}",$sales_list->mrp,$tr);
          $tr = str_replace("{item_name}",$sales_list->item_name.$mnd,$tr);
          $tr = str_replace("{cgst}",$sales_list->cgst,$tr);
          $tr = str_replace("{sgst}",$sales_list->sgst,$tr);
          $tr = str_replace("{batch_no}",$sales_list->batch_no,$tr);
          $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($sales_list->expiry_date)),$tr);
          $tr = str_replace("{igst}",$sales_list->igst,$tr);
          $tr = str_replace("{discount}",$sales_list->discount,$tr);
          $tr = str_replace("{total_amount}",$sales_list->total_amount,$tr);
          $tr_html .= $tr;
          $i++;
        	

	}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$data_patient_reg = get_setting_value('PATIENT_REG_NO');
$template_data->template = str_replace("Bill To:",$data_patient_reg,$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$template_data->template);

//$template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{item_discount}",$all_detail['sales_list'][0]->item_discount,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

if($user_detail['users_role']==4)
{
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
} 
else
{
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$template_data->template);
}

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
$this->session->unset_userdata('allotment_id');
echo $template_data->template;
}

/* end leaser printing*/
?>






