<?php 
//print '<pre>'; print_r($all_detail['sales_list']);die;
$users_data = $this->session->userdata('auth_users');
$payment_mode=$payment_mode[0]->payment_mode;
$template_data->template = str_replace("{transaction_no}",$transaction_id,$template_data->template);
/* start thermal printing */
if($template_data->printer_id==2){
     

    // if(!empty($all_detail['sales_list'][0]->relation_name))
    // {
    //  $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
    //  $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
    // }
    // else
    // {
    // $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
    // }


    if(!empty($all_detail['sales_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

        if(!empty($all_detail['sales_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }



    $template_data->template = str_replace("Discount(%):","Discount ({discount_percent}%):",$template_data->template);
    $template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);
	$template_data->template = str_replace("Name","Patient Name",$template_data->template);

$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No:","Receipt No.:",$template_data->template);
$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

if(in_array('218',$users_data['permission']['section']))
{
  if($all_detail['sales_list'][0]->paid_amount>0)
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
        }  
}
else
{
    $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
}



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
	 $tr = str_replace("{vaccine_name}",$medicine_list->vaccine_name,$tr);
	 $tr = str_replace("{vaccine_per_net_amount}",$medicine_list->mrp,$tr);
	 $tr_html .= $tr;
	 $i++;

}
//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
$template_data->template = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$template_data->template);
if($user_detail['users_role']==4)
{
$template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);
}
else
{
 $template_data->template = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$template_data->template);   
}


$template_data->template = str_replace("{refered_by}",''.$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);

 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
     

    // if(!empty($all_detail['sales_list'][0]->relation_name))
    // {
    //  $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
    // $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
    // }
    // else
    // {
    //     $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
    // }

    if(!empty($all_detail['sales_list'][0]->relation))
    {
            $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation,$template_data->template);
    }
    else
    {
            $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
    }

    if(!empty($all_detail['sales_list'][0]->relation_name))
    {
        $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
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

    $template_data->template = str_replace("Disc:","Discount ({discount_percent}%):",$template_data->template);
    $template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

	$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No","Receipt No. :",$template_data->template);

//$template_data->template = str_replace("INVOICE","Receipt No :",$template_data->template);
$template_data->template = str_replace("Salesman","Patient Registration No. :",$template_data->template);

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
	$tr = $row_loop;
	$tot_medicine=$tot_medicine+$i;
	$tr = str_replace("{s_no}",$i,$tr);
	$total_quantity_amt=$total_quantity_amt+$medicine_list->qty;
	$total_discount_amt=$total_discount_amt+$medicine_list->discount;
	$total_vat_amt=$total_vat_amt+$medicine_list->vat;
    $total_vat_amt=$total_vat_amt+$medicine_list->vat;
    $total_cgst=$total_cgst+$medicine_list->m_cgst;
    $total_sgst=$total_sgst+$medicine_list->m_sgst;
    $total_igst=$total_igst+$medicine_list->m_igst;
	$total_mrp=$total_mrp+$medicine_list->mrp;
	$tr = str_replace("{vaccine_qty}",$medicine_list->qty,$tr);
	$tr = str_replace("{vaccine_per_discount}",$medicine_list->m_discount,$tr);
	$tr = str_replace("{vaccine_per_vat}",$medicine_list->vat,$tr);
	$tr = str_replace("{vaccine_per_price}",$medicine_list->per_pic_price,$tr);
     $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
       $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($medicine_list->m_expiry_date)),$tr);
    $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
      $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
      $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
	$tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
	$tr = str_replace("{vaccine_per_net_amount}",$medicine_list->mrp,$tr);
	$tr_html .= $tr;
	$i++;
	$j++;

}
//echo $i;
if(in_array('218',$users_data['permission']['section']))
{
if($all_detail['sales_list'][0]->paid_amount>0)
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
        }
}
else
{
    $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
}

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
$template_data->template = str_replace("{total_vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("{salesman}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{total_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->net_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{total_gross}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
    $template_data->template = str_replace("{tot_cgst}",$all_detail['sales_list'][0]->cgst,$template_data->template);
$template_data->template = str_replace("{tot_sgst}",$all_detail['sales_list'][0]->sgst,$template_data->template);
$template_data->template = str_replace("{tot_igst}",$all_detail['sales_list'][0]->igst,$template_data->template);
if($user_detail['users_role']==4)
{
 $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
}
else
{
  $template_data->template = str_replace("{signature}",ucfirst($all_detail['sales_list'][0]->user_name),$template_data->template);    
}


$template_data->template = str_replace("{refered_by}",''.$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);
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

	echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['sales_list']);die;
if($template_data->printer_id==1){
    

    // if(!empty($all_detail['sales_list'][0]->relation_name))
    // {
    // $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
    // $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
    // }
    // else
    // {
    // $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
    // }


      if(!empty($all_detail['sales_list'][0]->relation))
    {
            $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['sales_list'][0]->relation,$template_data->template);
    }
    else
    {
            $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
    }

    if(!empty($all_detail['sales_list'][0]->relation_name))
    {
        $rel_simulation = get_simulation_name($all_detail['sales_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['sales_list'][0]->relation_name,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
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

$template_data->template = str_replace("Discount(%):","Discount ({discount_percent}%):",$template_data->template);
    $template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
	$template_data->template = str_replace("Name:","Patient Name :",$template_data->template);

   $template_data->template = str_replace("INVOICE:","Receipt No.:",$template_data->template);


	$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{refered_by}",''.$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);

 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);

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

	//print_r($medicine_list);
	 $tr = $row_loop;
	 $tr = str_replace("{s_no}",$i,$tr);
	 $tr = str_replace("{quantity}",$medicine_list->qty,$tr);
	  $tr = str_replace("{mrp}",$medicine_list->per_pic_price,$tr);
	 $tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
      $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
        $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($medicine_list->m_expiry_date)),$tr);
    $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
      $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
      $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
       $tr = str_replace("{discount}",$medicine_list->m_discount,$tr);
	 $tr = str_replace("{total_amount}",$medicine_list->total_amount,$tr);
	 $tr_html .= $tr;
	 $i++;

}
if(in_array('218',$users_data['permission']['section']))
{
    if($all_detail['sales_list'][0]->paid_amount >0  && isset($all_detail['sales_list'][0]->reciept_prefix) && $all_detail['sales_list'][0]->reciept_suffix!="" )
    {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);       
    }
}
else
{
   $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
}
//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

//echo $i;
$template_data->template = str_replace("{vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("Bill To:","Patient Reg No. :",$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

//$template_data->template = str_replace("{discount}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{tot_discount}",$all_detail['sales_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{vat_percent}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
//$template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
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
	echo $template_data->template;
}

/* end leaser printing*/
?>
 <?php

if(!empty($download_type) && $download_type==2)
{
  if($medicine_type_url==1)
  {
    $url= base_url('sales_vaccination/save_image');
  }
  else
  {
    $url= base_url('sales_return_vaccination/save_image');
  }
  
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>html2canvas.js"></script>
<script>
 //$(function(){
  //$("#gimg").click(function(){

    //$('#gimg').hide();
$(document).ready(function() { 

     html2canvas($("page"), {
        onrendered: function(canvas) {
           var imgsrc = canvas.toDataURL("image/png");
            $.ajax({
                url:'<?php echo $url ?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['sales_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['sales_list'][0]->patient_code; ?>"
                    },
                success: function(result)
                {
                    //alert(result); return 1;
                     location.href =result;
                //var dt = canvas.toDataURL();
               // this.href = dt; //this may not work in the future..
 

                    //var opened = view.open(object_url, "_blank");
                    //view.location.href = object_url;
                    //var dataURL = $canvas[0].toDataURL('image/png');
                    //w.document.write("<img src='" + dataURL + "' alt='from canvas'/>");
                }

                
            });
           
        }
     });
  });   
  //});  
  //});
</script>
<?php } ?>

