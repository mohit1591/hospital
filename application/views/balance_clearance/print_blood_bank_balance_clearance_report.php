<?php 
$users_data = $this->session->userdata('auth_users');
$del = ','; 
$address_n='';
$address_re=array();

$template_data->template = str_replace('Discount:',"",$template_data->template);
$template_data->template = str_replace('Discount :',"",$template_data->template);

$template_data->template = str_replace('Net Amount :',"",$template_data->template);
$template_data->template = str_replace('Net Amount:',"",$template_data->template);


$date_req = date('d-m-Y',strtotime($all_detail['sales_list'][0]->requirement_date));

$template_data->template = str_replace("{dis_heading}",' ',$template_data->template);
$template_data->template = str_replace("{total_discount}",'',$template_data->template);


$template_data->template = str_replace("{technician_name}",$patient_detail['technician_name'],$template_data->template);

$template_data->template = str_replace("{billing_for}",$patient_detail['billing_for'],$template_data->template);

$template_data->template = str_replace("{patient_relation}",$patient_detail['patient_relation'],$template_data->template);

$template_data->template = str_replace("{verified_by}",$patient_detail['verified_by'],$template_data->template);

$template_data->template = str_replace("{doctor_name}",$patient_detail['verified_by'],$template_data->template);


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

  $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
  $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

  $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
  if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
    }
     if(!empty($address_n))
     {
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
   $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

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


      if(in_array('218',$users_data['permission']['section']))
      {
      if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)) )
      {
          $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
      }
      }
      else
      {
          $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }

$template_data->template = str_replace("{address}",$patient_address,$template_data->template);
 $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->issue_code,$template_data->template);
 $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
 
 $template_data->template = str_replace("{gender}",$gender,$template_data->template);
 
 $template_data->template = str_replace("{age}",$patient1_age,$template_data->template);
 

$template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date_req,$template_data->template);
  

  //$template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  
    $template_data->template = str_replace("{blood_groups}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  
  

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
//////////////////////// 
$isid = $all_detail['sales_list'][0]->issue_code;

$client_name =$all_detail['sales_list'][0]->relation_name;
        if(!empty($all_detail['sales_list'][0]->cat_spec))
        {
          $commom_s =$all_detail['sales_list'][0]->cat_spec;
        }
        else
        {
          $commom_s =$all_detail['sales_list'][0]->dog_spec;
        }

//echo "<pre>"; print_r($component_detail); exit;
//////////////////////// 
$i=1;
$tr_html = "";
//foreach($component_detail as $component_list)
//{ 

    //print_r($medicine_list);
    $tr = $row_loop;

    $tr = str_replace("{date}",'',$tr);
    $tr = str_replace("{patient_id}",'',$tr);
     $tr = str_replace("{patient_code}","Balance Clearance",$tr);
    $tr = str_replace("{bar_code}","",$tr);

    $tr = str_replace("{client}",$client_name,$tr);

    $tr = str_replace("{species}",$commom_s,$tr);

    $tr = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$tr);
    
    $tr = str_replace("{component}",'',$tr);
    $tr = str_replace("{qty}",1,$tr);
    $tr = str_replace("{mrp}",$all_detail['sales_list'][0]->debit,$tr);
    $tr = str_replace("{total_amount}",$all_detail['sales_list'][0]->debit,$tr);
    //$tr = str_replace("{donor_id}",$component_list->donor_code,$tr);
    $tr_html .= $tr;
   // $i++;

//}

$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{net_paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);
 $div_head='';
      $div_val='';
      $div_head='';
      $div_val='{total_discount}';

      //echo $div_val;
      //echo $div_head;
if($payment_mode['discount_amount']=='0.00')
{
 $template_data->template = str_replace("{dis_heading}",' ',$template_data->template); 
 $template_data->template = str_replace("{total_discount}",' ',$template_data->template); 
}
else
{
 $template_data->template = str_replace("{dis_heading}",$div_head,$template_data->template); 
 $template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);  

}

//$template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);
if($all_detail['sales_list'][0]->shipment_amount > 0)
{
    $template_data->template = str_replace("{shipping_charge}",$all_detail['sales_list'][0]->shipment_amount,$template_data->template);

}
else
{
     $template_data->template = str_replace("Shipping Charge:",' ',$template_data->template);
    $template_data->template = str_replace("{shipping_charge}",' ',$template_data->template);
}


$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->debit,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

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

  $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
  $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date_req,$template_data->template);
  

  //$template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  
   $template_data->template = str_replace("{blood_groups}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  
  
  if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
    }
     if(!empty($address_n))
     {
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
$template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

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

$template_data->template = str_replace("{address}",$patient_address,$template_data->template);
 $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->issue_code,$template_data->template);
 $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
 
  $template_data->template = str_replace("{gender}",$gender,$template_data->template);
 
 $template_data->template = str_replace("{age}",$patient1_age,$template_data->template);

  
$template_data->template = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->requirement_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));

$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
//////////////////////// 
$isid = $all_detail['sales_list'][0]->issue_code;

$client_name =$all_detail['sales_list'][0]->relation_name;
        if(!empty($all_detail['sales_list'][0]->cat_spec))
        {
          $commom_s =$all_detail['sales_list'][0]->cat_spec;
        }
        else
        {
          $commom_s =$all_detail['sales_list'][0]->dog_spec;
        }
$date_req = date('d-m-Y',strtotime($all_detail['sales_list'][0]->requirement_date));
//echo "<pre>"; print_r($component_detail); exit;
//////////////////////// 
$i=1;
$tr_html = "";
//echo "<pre>"; print_r($component_detail); exit;
//////////////////////// 
$i=1;
$tr_html = "";
//foreach($component_detail as $component_list)
//{ 

    //print_r($medicine_list);
    $tr = $row_loop;

    $tr = str_replace("{date}",'',$tr);
    $tr = str_replace("{patient_id}",'',$tr);
     $tr = str_replace("{patient_code}","Balance Clearance",$tr);
    $tr = str_replace("{bar_code}","",$tr);

    $tr = str_replace("{client}",$client_name,$tr);

    $tr = str_replace("{species}",$commom_s,$tr);

    $tr = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$tr);
    
    $tr = str_replace("{component}",'',$tr);
    $tr = str_replace("{qty}",1,$tr);
    $tr = str_replace("{mrp}",$all_detail['sales_list'][0]->debit,$tr);
    $tr = str_replace("{total_amount}",$all_detail['sales_list'][0]->debit,$tr);
    //$tr = str_replace("{donor_id}",$component_list->donor_code,$tr);
    $tr_html .= $tr;
   // $i++;

//}

$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{net_paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);
     $div_head='';
      $div_val='';
      $div_head='';
      $div_val='{total_discount}';

      //echo $div_val;
      //echo $div_head;
if($payment_mode['discount_amount']=='0.00')
{
 $template_data->template = str_replace("{dis_heading}",' ',$template_data->template); 
 $template_data->template = str_replace("{total_discount}",' ',$template_data->template); 
}
else
{
 $template_data->template = str_replace("{dis_heading}",$div_head,$template_data->template); 
 $template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);  

}

//$template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);
if($all_detail['sales_list'][0]->shipment_amount > 0)
{
    $template_data->template = str_replace("{shipping_charge}",$all_detail['sales_list'][0]->shipment_amount,$template_data->template);

}
else
{
     $template_data->template = str_replace("Shipping Charge:",' ',$template_data->template);
    $template_data->template = str_replace("{shipping_charge}",' ',$template_data->template);
}

 if(in_array('218',$users_data['permission']['section']))
      {
      if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)) )
      {
          $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
      }
      }
      else
      {
          $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }

$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->debit,$template_data->template);
$template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
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
        //$template_data->template = str_replace("Discount (%):",' ',$template_data->template);
         //$template_data->template = str_replace("discount_percent:",' ',$template_data->template);
        
  echo $template_data->template;
  //exit;
}


if($template_data->printer_id==1)
{
  //print '<pre>';print_r($all_detail);
  //print '<pre>';print_r($patient_detail);

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

  $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.$all_detail['sales_list'][0]->patient_name,$template_data->template);
  $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

  $template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date_req,$template_data->template);
  

  //$template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);

 $template_data->template = str_replace("{blood_groups}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  
  if($all_detail['sales_list'][0]->address!='' || $all_detail['sales_list'][0]->address2!='' || $all_detail['sales_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['sales_list'][0]->address),explode ( $del , $all_detail['sales_list'][0]->address2),explode ( $del , $all_detail['sales_list'][0]->address3));
    }
     if(!empty($address_n))
     {
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
$template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

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

$template_data->template = str_replace("{address}",$patient_address,$template_data->template);
 $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->issue_code,$template_data->template);
 $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
 
  $template_data->template = str_replace("{gender}",$gender,$template_data->template);
 
 $template_data->template = str_replace("{age}",$patient1_age,$template_data->template);

  
$template_data->template = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->requirement_date)),$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));

$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

$isid = $all_detail['sales_list'][0]->issue_code;

$client_name =$all_detail['sales_list'][0]->relation_name;
        if(!empty($all_detail['sales_list'][0]->cat_spec))
        {
          $commom_s =$all_detail['sales_list'][0]->cat_spec;
        }
        else
        {
          $commom_s =$all_detail['sales_list'][0]->dog_spec;
        }
$date_req = date('d-m-Y',strtotime($all_detail['sales_list'][0]->requirement_date));
//echo "<pre>"; print_r($component_detail); exit;
//////////////////////// 
$i=1;
$tr_html = "";
//echo "<pre>"; print_r($component_detail); exit;
//////////////////////// 
$i=1;
$tr_html = "";
//foreach($component_detail as $component_list)
//{ 

    //print_r($medicine_list);
    $tr = $row_loop;

    $tr = str_replace("{date}",'',$tr);
    $tr = str_replace("{patient_id}",'',$tr);
     $tr = str_replace("{patient_code}","Balance Clearance",$tr);
    $tr = str_replace("{bar_code}","",$tr);

    $tr = str_replace("{client}",$client_name,$tr);

    $tr = str_replace("{species}",$commom_s,$tr);

    $tr = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$tr);
    
    $tr = str_replace("{component}",'',$tr);
    $tr = str_replace("{qty}",1,$tr);
    $tr = str_replace("{mrp}",$all_detail['sales_list'][0]->debit,$tr);
    $tr = str_replace("{total_amount}",$all_detail['sales_list'][0]->debit,$tr);
    //$tr = str_replace("{donor_id}",$component_list->donor_code,$tr);
    $tr_html .= $tr;
   // $i++;

//}

$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
$template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{net_paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);
$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->debit,$template_data->template);
if($all_detail['sales_list'][0]->shipment_amount > 0)
{
    $template_data->template = str_replace("{shipping_charge}",$all_detail['sales_list'][0]->shipment_amount,$template_data->template);

}
else
{
     $template_data->template = str_replace("Shipping Charge:",' ',$template_data->template);
    $template_data->template = str_replace("{shipping_charge}",' ',$template_data->template);
}

 if(in_array('218',$users_data['permission']['section']))
      {
      if($all_detail['sales_list'][0]->paid_amount>0 && (!empty($all_detail['sales_list'][0]->reciept_prefix) || !empty($all_detail['sales_list'][0]->reciept_suffix)) )
      {
          $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
      }
      }
      else
      {
          $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
      $div_head='';
      $div_val='';
      $div_head='<div style="float:left;font-weight:bold;">Discount</div>';
      $div_val='<div style="float:right;font-weight:bold;">{total_discount}</div>';

      //echo $div_val;
      //echo $div_head;
if($payment_mode['discount_amount']=='0.00')
{
 $template_data->template = str_replace("{dis_heading}",' ',$template_data->template); 
 $template_data->template = str_replace("{total_discount}",' ',$template_data->template); 
}
else
{
 $template_data->template = str_replace("{dis_heading}",$div_head,$template_data->template); 
 $template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);  

}


//$template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);
$template_data->template = str_replace("{net_amount}",$payment_mode['net_amount'],$template_data->template);


$get_amount = AmountInWords($payment_mode['net_amount']);

$template_data->template = str_replace("{net_amount_word}",$get_amount,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
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
        //$template_data->template = str_replace("Discount (%):",' ',$template_data->template);
         //$template_data->template = str_replace("discount_percent:",' ',$template_data->template);
        
  echo $template_data->template;
}

/* end leaser printing*/
?>