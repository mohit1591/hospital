<?php 
//print '<pre>'; print_r($all_detail['sales_list']);die;
$users_data = $this->session->userdata('auth_users');
$payment_mode=$payment_mode[0]->payment_mode;

/* start thermal printing */
$del = ','; 
$address_n='';
$address_re='';
if($template_data->printer_id==2){
    
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

        if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)) )
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
            }
  
    $template_data->template = str_replace("Discount(%):","Discount ({discount_percent}%):",$template_data->template);
    $template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);
	$template_data->template = str_replace("Name","Customer Name",$template_data->template);

if(!empty($all_detail['sales_list'][0]->customer_name))
{ 
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_name,$template_data->template);
} 

if(!empty($all_detail['sales_list'][0]->patient_name))
{
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);	
} 


if(!empty($all_detail['sales_list'][0]->customer_code))
{ 
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_code,$template_data->template);
} 

if(!empty($all_detail['sales_list'][0]->patient_code))
{
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_code,$template_data->template);	
} 

if(!empty($all_detail['sales_list'][0]->customer_mobile)){ 
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->customer_mobile,$template_data->template);
} 
if(!empty($all_detail['sales_list'][0]->patient_mobile)){
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->patient_mobile,$template_data->template);
}

/* 06-02-2018 */
$template_data->template = str_replace("{aadhaar_no}",$all_detail['sales_list'][0]->adhar_no,$template_data->template);
if($all_detail['sales_list'][0]->anniversary!='1970-01-01' && $all_detail['sales_list'][0]->anniversary!='0000-00-00') 
{
    $template_data->template = str_replace("{anniversary}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->anniversary)),$template_data->template);

}
else
{
    $template_data->template = str_replace("{anniversary}",'',$template_data->template);

}
if($all_detail['sales_list'][0]->marital_status==1)
{
    $marital_status = 'Married';
}
else
{
    $marital_status = 'Un-married';
}
$template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
$template_data->template = str_replace("{religion}",$all_detail['sales_list'][0]->religion,$template_data->template);


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
$template_data->template = str_replace("{address}",$patient_address,$template_data->template);

$template_data->template = str_replace("{pincode}",$all_detail['sales_list'][0]->pincode,$template_data->template);

$template_data->template = str_replace("{country}",$all_detail['sales_list'][0]->country,$template_data->template);

$template_data->template = str_replace("{state}",$all_detail['sales_list'][0]->state,$template_data->template);

$template_data->template = str_replace("{city}",$all_detail['sales_list'][0]->city,$template_data->template);

$template_data->template = str_replace("{father_husband}",$all_detail['sales_list'][0]->father_husband,$template_data->template);

$template_data->template = str_replace("{mother}",$all_detail['sales_list'][0]->mother,$template_data->template);

$template_data->template = str_replace("{guardian_name}",$all_detail['sales_list'][0]->guardian_name,$template_data->template);

$template_data->template = str_replace("{guardian_email}",$all_detail['sales_list'][0]->guardian_email,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{patient_email}",$all_detail['sales_list'][0]->patient_email,$template_data->template);


$template_data->template = str_replace("{monthly_income}",$all_detail['sales_list'][0]->monthly_income,$template_data->template);

$template_data->template = str_replace("{occupation}",$all_detail['sales_list'][0]->occupation,$template_data->template);

$template_data->template = str_replace("{insurance_company}",$all_detail['sales_list'][0]->insurance_company,$template_data->template);

$template_data->template = str_replace("{policy_no}",$all_detail['sales_list'][0]->polocy_no,$template_data->template);

$template_data->template = str_replace("{monthly_income}",$all_detail['sales_list'][0]->monthly_income,$template_data->template);

$template_data->template = str_replace("{occupation}",$all_detail['sales_list'][0]->occupation,$template_data->template);

$template_data->template = str_replace("{insurance_company}",$all_detail['sales_list'][0]->insurance_company,$template_data->template);

$template_data->template = str_replace("{policy_no}",$all_detail['sales_list'][0]->polocy_no,$template_data->template);

/*06-02-2018*/


$template_data->template = str_replace("{sale_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

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
foreach($all_detail['sales_list']['item_list'] as $item_list)
{ 
	 $tr = $row_loop;
	 $tr = str_replace("{sn}",$i,$tr);
	 $tr = str_replace("{item_name}",$item_list->item,$tr);
	 $tr = str_replace("{item_per_net_amount}",$item_list->mrp,$tr);
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
$this->session->unset_userdata('sales_id');
 echo $template_data->template; 

}
/* end thermal printing */

/* start dot printing */
if($template_data->printer_id==3){
     

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

        if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)))
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
            }
   
   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);


if(!empty($all_detail['sales_list'][0]->customer_name)){ 
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_name,$template_data->template);
} 
if(!empty($all_detail['sales_list'][0]->patient_name)){
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);	
} 

if(!empty($all_detail['sales_list'][0]->customer_code))
{ 
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_code,$template_data->template);
} 

if(!empty($all_detail['sales_list'][0]->patient_code))
{
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_code,$template_data->template);	
} 

/* 06-02-2018 */
$template_data->template = str_replace("{aadhaar_no}",$all_detail['sales_list'][0]->adhar_no,$template_data->template);
if($all_detail['sales_list'][0]->anniversary!='1970-01-01' && $all_detail['sales_list'][0]->anniversary!='0000-00-00') 
{
    $template_data->template = str_replace("{anniversary}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->anniversary)),$template_data->template);

}
else
{
    $template_data->template = str_replace("{anniversary}",'',$template_data->template);

}
if($all_detail['sales_list'][0]->marital_status==1)
{
    $marital_status = 'Married';
}
else
{
    $marital_status = 'Un-married';
}
$template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
$template_data->template = str_replace("{religion}",$all_detail['sales_list'][0]->religion,$template_data->template);


if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
    }
     if(!empty($address_n))
     { $address_re=array();
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
$template_data->template = str_replace("{address}",$patient_address,$template_data->template);

$template_data->template = str_replace("{pincode}",$all_detail['sales_list'][0]->pincode,$template_data->template);

$template_data->template = str_replace("{country}",$all_detail['sales_list'][0]->country,$template_data->template);

$template_data->template = str_replace("{state}",$all_detail['sales_list'][0]->state,$template_data->template);

$template_data->template = str_replace("{city}",$all_detail['sales_list'][0]->city,$template_data->template);

$template_data->template = str_replace("{father_husband}",$all_detail['sales_list'][0]->father_husband,$template_data->template);

$template_data->template = str_replace("{mother}",$all_detail['sales_list'][0]->mother,$template_data->template);

$template_data->template = str_replace("{guardian_name}",$all_detail['sales_list'][0]->guardian_name,$template_data->template);

$template_data->template = str_replace("{guardian_email}",$all_detail['sales_list'][0]->guardian_email,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{patient_email}",$all_detail['sales_list'][0]->patient_email,$template_data->template);


$template_data->template = str_replace("{monthly_income}",$all_detail['sales_list'][0]->monthly_income,$template_data->template);

$template_data->template = str_replace("{occupation}",$all_detail['sales_list'][0]->occupation,$template_data->template);

$template_data->template = str_replace("{insurance_company}",$all_detail['sales_list'][0]->insurance_company,$template_data->template);

$template_data->template = str_replace("{policy_no}",$all_detail['sales_list'][0]->polocy_no,$template_data->template);

/*06-02-2018*/

if(!empty($all_detail['sales_list'][0]->customer_mobile)){ 
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->customer_mobile,$template_data->template);
} 
if(!empty($all_detail['sales_list'][0]->patient_mobile)){
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->patient_mobile,$template_data->template);
}

$template_data->template = str_replace("{sale_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

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
$tot_item=0;
$total_cgst=0;
$total_sgst=0;
$total_igst=0;
foreach($all_detail['sales_list']['item_list'] as $item_list)
{ 
	$tr = $row_loop;
	$tot_item=$tot_item+$i;
	$tr = str_replace("{s_no}",$i,$tr);
	$total_quantity_amt=$total_quantity_amt+$item_list->qty;
	$total_discount_amt=$total_discount_amt+$item_list->discount;
	$total_vat_amt=$total_vat_amt+$item_list->vat;
    $total_vat_amt=$total_vat_amt+$item_list->vat;
    $total_cgst=$total_cgst+$item_list->m_cgst;
    $total_sgst=$total_sgst+$item_list->m_sgst;
    $total_igst=$total_igst+$item_list->m_igst;
	$total_mrp=$total_mrp+$item_list->mrp;
	$tr = str_replace("{item_qty}",$item_list->qty,$tr);
	$tr = str_replace("{item_per_discount}",$item_list->discount,$tr);
	$tr = str_replace("{item_per_vat}",$item_list->vat,$tr);
	$tr = str_replace("{item_per_price}",$item_list->per_pic_rate,$tr);
	$tr = str_replace("{batch_no}",$item_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($item_list->expiry_date)),$tr);
    $tr = str_replace("{hsn_no}",$item_list->m_hsn_no,$tr);
     $tr = str_replace("{cgst}",$item_list->m_cgst,$tr);
      $tr = str_replace("{sgst}",$item_list->m_sgst,$tr);
      $tr = str_replace("{igst}",$item_list->m_igst,$tr);
	$tr = str_replace("{item_name}",$item_list->item,$tr);
	$tr = str_replace("{item_per_net_amount}",$item_list->mrp,$tr);
	$tr_html .= $tr;
	$i++;
	$j++;

}
//echo $i;

$template_data->template = str_replace("{total_cgst}",$total_cgst,$template_data->template);
$template_data->template = str_replace("{total_sgst}",$total_sgst,$template_data->template);
$template_data->template = str_replace("{total_igst}",$total_igst,$template_data->template);
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_item}",$j,$template_data->template);
$template_data->template = str_replace("{total_quantity}",$total_quantity_amt,$template_data->template);
$template_data->template = str_replace("{total_discount_amt}",$total_discount_amt,$template_data->template);
$template_data->template = str_replace("{total_vat_amt}",$total_vat_amt,$template_data->template);
$template_data->template = str_replace("{total_amt_per}",$total_mrp,$template_data->template);
$template_data->template = str_replace("{total_per_price_amt}",$total_mrp,$template_data->template);
//echo $i;
$template_data->template = str_replace("{total_vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);
$template_data->template = str_replace("{sale_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

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
$this->session->unset_userdata('sales_id');
	echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['sales_list']);die;
if($template_data->printer_id==1){
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
    /* 06-02-2018 */
$template_data->template = str_replace("{aadhaar_no}",$all_detail['sales_list'][0]->adhar_no,$template_data->template);
if($all_detail['sales_list'][0]->anniversary!='1970-01-01' && $all_detail['sales_list'][0]->anniversary!='0000-00-00') 
{
    $template_data->template = str_replace("{anniversary}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->anniversary)),$template_data->template);

}
else
{
    $template_data->template = str_replace("{anniversary}",'',$template_data->template);

}
if($all_detail['sales_list'][0]->marital_status==1)
{
    $marital_status = 'Married';
}
else
{
    $marital_status = 'Un-married';
}
$template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
$template_data->template = str_replace("{religion}",$all_detail['sales_list'][0]->religion,$template_data->template);


if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
    }
     if(!empty($address_n))
     { $address_re=array();
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
$template_data->template = str_replace("{address}",$patient_address,$template_data->template);

$template_data->template = str_replace("{pincode}",$all_detail['sales_list'][0]->pincode,$template_data->template);

$template_data->template = str_replace("{country}",$all_detail['sales_list'][0]->country,$template_data->template);

$template_data->template = str_replace("{state}",$all_detail['sales_list'][0]->state,$template_data->template);

$template_data->template = str_replace("{city}",$all_detail['sales_list'][0]->city,$template_data->template);

$template_data->template = str_replace("{father_husband}",$all_detail['sales_list'][0]->father_husband,$template_data->template);

$template_data->template = str_replace("{mother}",$all_detail['sales_list'][0]->mother,$template_data->template);

$template_data->template = str_replace("{guardian_name}",$all_detail['sales_list'][0]->guardian_name,$template_data->template);

$template_data->template = str_replace("{guardian_email}",$all_detail['sales_list'][0]->guardian_email,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{relation}",$all_detail['sales_list'][0]->relation,$template_data->template);

$template_data->template = str_replace("{patient_email}",$all_detail['sales_list'][0]->patient_email,$template_data->template);


$template_data->template = str_replace("{monthly_income}",$all_detail['sales_list'][0]->monthly_income,$template_data->template);

$template_data->template = str_replace("{occupation}",$all_detail['sales_list'][0]->occupation,$template_data->template);

$template_data->template = str_replace("{insurance_company}",$all_detail['sales_list'][0]->insurance_company,$template_data->template);

$template_data->template = str_replace("{policy_no}",$all_detail['sales_list'][0]->polocy_no,$template_data->template);

/*06-02-2018*/

    
        if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)) )
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
            }
    
$template_data->template = str_replace("Discount(%):","Discount ({discount_percent}%):",$template_data->template);
    $template_data->template = str_replace("{discount_percent}",$all_detail['sales_list'][0]->discount_percent,$template_data->template);

   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
	$template_data->template = str_replace("Name:","Customer Name :",$template_data->template);

   $template_data->template = str_replace("INVOICE:","Receipt No.:",$template_data->template);

if(!empty($all_detail['sales_list'][0]->customer_name))
{ 
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_name,$template_data->template);
} 

if(!empty($all_detail['sales_list'][0]->patient_name))
{
$template_data->template = str_replace("{customer_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);	
} 

if(!empty($all_detail['sales_list'][0]->customer_code))
{ 
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->customer_code,$template_data->template);
} 

if(!empty($all_detail['sales_list'][0]->patient_code))
{
$template_data->template = str_replace("{customer_code}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_code,$template_data->template);	
} 
	
if(!empty($all_detail['sales_list'][0]->customer_mobile)){ 
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->customer_mobile,$template_data->template);
} 
if(!empty($all_detail['sales_list'][0]->patient_mobile)){
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->patient_mobile,$template_data->template);
}
  
$template_data->template = str_replace("{sale_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

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
foreach($all_detail['sales_list']['item_list'] as $item_list)
{ 

//print_r($item_list);
	 $tr = $row_loop;
	 $tr = str_replace("{s_no}",$i,$tr);
	 $tr = str_replace("{quantity}",$item_list->qty,$tr);
	  $tr = str_replace("{mrp}",$item_list->per_pic_rate,$tr);
	 $tr = str_replace("{item_name}",$item_list->item,$tr);
      $tr = str_replace("{cgst}",$item_list->m_cgst,$tr);
      $tr = str_replace("{sgst}",$item_list->m_sgst,$tr);
      $tr = str_replace("{batch_no}",$item_list->m_batch_no,$tr);
   $tr = str_replace("{exp_date}",date('d-m-Y',strtotime($item_list->expiry_date)),$tr);
    $tr = str_replace("{hsn_no}",$item_list->m_hsn_no,$tr);
      $tr = str_replace("{igst}",$item_list->m_igst,$tr);
       $tr = str_replace("{discount}",$item_list->discount,$tr);
	 $tr = str_replace("{total_amount}",$item_list->total_amount,$tr);
	 $tr_html .= $tr;
	 $i++;

}

//echo $i;
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

//echo $i;
$template_data->template = str_replace("{vat}",$all_detail['sales_list'][0]->vat_percent,$template_data->template);

$template_data->template = str_replace("{sale_no}",$all_detail['sales_list'][0]->sale_no,$template_data->template);

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
        $this->session->unset_userdata('sales_id');
	echo $template_data->template;
}

/* end leaser printing*/
?>

