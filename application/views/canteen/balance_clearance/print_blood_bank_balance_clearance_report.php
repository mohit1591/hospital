<?php //echo $template_data->printer_id;

  $user_detail = $this->session->userdata('auth_users');
  $users_data = $this->session->userdata('auth_users');
  $pay_mode='';
  $pay_mode=$all_detail['sales_list'][0]->payment_mode;
  /* start thermal printing */

  if($template_data->printer_id==2){

  if(in_array('218',$users_data['permission']['section']))
  {
  $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
  }
  else
  {
  $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
  }

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
$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
  $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);

  $template_data->template = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

  $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

  $genders = array('0'=>'F','1'=>'M');
  if(isset($all_detail['sales_list'][0]->gender)){
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
  }else{
  $gender_age='';
  }

  $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
  $template_data->template = str_replace("{start_loop}",'',$template_data->template);
  $template_data->template = str_replace("{end_loop}",'',$template_data->template);


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


  $simulation = get_simulation_name($all_detail['sales_list'][0]->simulation_id);

  $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['sales_list'][0]->name,$template_data->template);
  //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
  $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);


  $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);



  $template_data->template = str_replace("{total_discount}",'',$template_data->template);

  $template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->total_amount,$template_data->template);


  $template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

  $template_data->template = str_replace("{amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);


  $template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);

  $template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);
  $template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);
 $template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);

 $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);
  $template_data->template = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
  $template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

  $template_data->template = str_replace('Component Name',"Particulars",$template_data->template);
  $template_data->template = str_replace('{component_name}',"Balance Clearance",$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date,$template_data->template);
  $template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);
  $template_data->template = str_replace('QTY',"",$template_data->template);
  $template_data->template = str_replace('Basic Price',"",$template_data->template);
  $template_data->template = str_replace('Discount:',"",$template_data->template);
  $template_data->template = str_replace('VAT:',"",$template_data->template);
  $template_data->template = str_replace('{s_no}',"1",$template_data->template);
  $template_data->template = str_replace('{quantity}',"",$template_data->template);
  $template_data->template = str_replace('{mrp}',"",$template_data->template);
  //$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
  $template_data->template = str_replace('Discount(%): ',"",$template_data->template);
  $template_data->template = str_replace('{tot_discount}',"",$template_data->template);
$template_data->template = str_replace("{blood_price}",'',$template_data->template);
$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{dis_heading}",'',$template_data->template); 
  $template_data->template = str_replace("Shipping Charge:",'',$template_data->template); 
  $template_data->template = str_replace("{shipping_charge}",'',$template_data->template);
  $template_data->template = str_replace("{remark}",' ',$template_data->template);
   $template_data->template = str_replace("{donor_id}",' ',$template_data->template);
  $template_data->template = str_replace("Remarks :",' ',$template_data->template);
$template_data->template = str_replace("{donor_id}",' ',$template_data->template);
$template_data->template = str_replace("Donor Id",' ',$template_data->template);

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
    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);
  
     $receipt_code = '<div><b>OPD No.:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);

         $booking_date = '<b>Booking Date:</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
        $template_data->template = str_replace("{reciept_date}",$booking_date,$template_data->template);
    

  if(in_array('218',$users_data['permission']['section']))
    {
     $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
    }
    else
    {
    $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
    }

  
    


    $template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);


	$genders = array('0'=>'F','1'=>'M');
    if(isset($all_detail['sales_list'][0]->gender)){
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
    }else{
       $gender_age='';
    }
        
     

   $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);
    
$template_data->template = str_replace("{Consultant}",'',$template_data->template);
$template_data->template = str_replace("{specialization}",'',$template_data->template);
$template_data->template = str_replace("{next_app_date}",'',$template_data->template);


$template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
$simulation = get_simulation_name($all_detail['sales_list'][0]->simulation_id); 
$template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['sales_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No.:","Receipt No :",$template_data->template);

$template_data->template = str_replace("INVOICE","Receipt No :",$template_data->template);

//$template_data->template = str_replace("Salesman","Patient Registration No :",$template_data->template);

    if($type==3){
      $template_data->template = str_replace("OPD","Medicine",$template_data->template);
  
    }
   $template_data->template = str_replace("{page_type}","Blance Clearance",$template_data->template);



$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);



$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

$template_data->template = str_replace("{total_medicine}",0,$template_data->template);
$template_data->template = str_replace("{total_quantity}",'',$template_data->template);
$template_data->template = str_replace("{total_discount_amt}",'',$template_data->template);
$template_data->template = str_replace("{total_vat_amt}",'',$template_data->template);
$template_data->template = str_replace("{total_amt_per}",'',$template_data->template);
$template_data->template = str_replace("{total_per_price_amt}",'',$template_data->template);
//echo $i;
$template_data->template = str_replace("{total_vat}",'',$template_data->template);

$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

$template_data->template = str_replace("{total_discount}",'0.00',$template_data->template);

$template_data->template = str_replace("{total_net}",number_format($all_detail['sales_list'][0]->total_amount,2,'.',''),$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);

$template_data->template = str_replace("{total_gross}",number_format($all_detail['sales_list'][0]->total_amount,2,'.',''),$template_data->template); 
$template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);

$template_data->template = str_replace("{amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
 $template_data->template = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

 $template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
if($type==2) {
    $booking_date = '<b>Booking Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
     $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
     $receipt_code = '<div><b>OPD No.:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
   }
   
   if($type==3) {
    $booking_date = '<b>Sale Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
     $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
     $receipt_code = '<div><b>Sale No.</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
  
   }
   $template_data->template = str_replace("{dis_heading}",'',$template_data->template); 
  $template_data->template = str_replace("Shipping Charge:",'',$template_data->template); 
  $template_data->template = str_replace("{shipping_charge}",'',$template_data->template);
  $template_data->template = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

  $template_data->template = str_replace('Component Name',"Particulars",$template_data->template);
  $template_data->template = str_replace('{component_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('Price',"",$template_data->template);
$template_data->template = str_replace('Qty',"",$template_data->template);
$template_data->template = str_replace('Cash Disc.',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount',"",$template_data->template);
$template_data->template = str_replace('Disc.',"",$template_data->template);
$template_data->template = str_replace('Vat%',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date,$template_data->template);

$template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);
 $template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
  $template_data->template = str_replace('Bar Code',"",$template_data->template);
           $template_data->template = str_replace('{bar_code}',"",$template_data->template);
$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amt.',"Total Amount",$template_data->template);
  $template_data->template = str_replace("{remarks}",' ',$template_data->template);
  $template_data->template = str_replace("{blood_price}",'',$template_data->template);
$template_data->template = str_replace("{donor_id}",' ',$template_data->template);
$template_data->template = str_replace("Donor Id",' ',$template_data->template);
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

      $template_data->template = str_replace("{start_loop}","",$template_data->template);
      $template_data->template = str_replace("{end_loop}","",$template_data->template);
      if(in_array('218',$users_data['permission']['section']))
      {
      $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
      }
      if(isset($all_detail['sales_list'][0]->token_no) && !empty($all_detail['sales_list'][0]->token_no))
      {
      $template_data->template = str_replace("{token_no}",$all_detail['sales_list'][0]->token_no,$template_data->template);    
      }
      else
      {
      $template_data->template = str_replace("{token_no}",'',$template_data->template);
      $template_data->template = str_replace("Token No.:",'',$template_data->template);
      $template_data->template = str_replace("Token No.",'',$template_data->template);
      }

      $genders = array('0'=>'F','1'=>'M');
      if(isset($all_detail['sales_list'][0]->gender)){
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
      $age .= $age_y."".$year;
      }
      if($age_m>0)
      {
      $month = 'M';
      if($age_m==1)
      {
      $month = 'M';
      }
      $age .= ",".$age_m." ".$month;
      }
      if($age_d>0)
      {
      $day = 'D';
      if($age_d==1)
      {
      $day = 'D';
      }
      $age .= ",".$age_d."".$day;
      }
      $patient_age =  $age;
      if($patient_age!=''){
      $patient1_age = '/'.$patient_age;
      }
      if($patient_age==''){
      $patient1_age=$patient_age;
      }
      $gender_age = $gender.$patient1_age ;
      }else{
      $gender_age='';

      }

      $template_data->template = str_replace("{requirement_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

      $template_data->template = str_replace("{pateint_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);



      $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);



      $template_data->template = str_replace("{issue_code}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

$template_data->template = str_replace("{hospital_name}",$all_detail['sales_list'][0]->doctor_hospital_name,$template_data->template);




      $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);


      $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

      $simulation = get_simulation_name($all_detail['sales_list'][0]->simulation_id);

      $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['sales_list'][0]->name,$template_data->template);

      $template_data->template = str_replace("{pateint_address}",$all_detail['sales_list'][0]->address,$template_data->template);



      $template_data->template = str_replace("{booking_code}",'',$template_data->template);

      $template_data->template = str_replace("{amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
      $template_data->template = str_replace("{discount}",'0.00',$template_data->template);


      $template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

      $template_data->template = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);


      $template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);
$template_data->template = str_replace("{total_amount_full}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

      $template_data->template = str_replace("{balance}",number_format($all_detail['sales_list'][0]->balance,2,'.',''),$template_data->template);
      $template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);


      $template_data->template = str_replace('Component Name',"Particulars",$template_data->template);
      $template_data->template = str_replace('Bar Code',"",$template_data->template);
      $template_data->template = str_replace('{bar_code}',"",$template_data->template);


      $template_data->template = str_replace('Discount :',"",$template_data->template);
      $template_data->template = str_replace('{total_discount}',"",$template_data->template);

      $template_data->template = str_replace('{s_no}',"1",$template_data->template);
      $template_data->template = str_replace('{component_name}',"Balance Clearance",$template_data->template);
 $template_data->template = str_replace("{blood_group}",$all_detail['sales_list'][0]->blood_group,$template_data->template);
      //$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
      $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

      $template_data->template = str_replace('Price',"",$template_data->template);
$date='';
$date=date('d-m-Y');
  $template_data->template = str_replace("{created_date}",$date,$template_data->template);
//$template_data->template = str_replace('Price',"",$template_data->template);

    $div_head='';
    $div_val='';
    $div_head='Discount:';
    $div_val='{total_discount}';

    
  $template_data->template = str_replace("{dis_heading}",'',$template_data->template); 
  $template_data->template = str_replace("Shipping Charge:",'',$template_data->template); 
  $template_data->template = str_replace("{shipping_charge}",'',$template_data->template);

    


      $template_data->template = str_replace("{remarks}",' ',$template_data->template);
      $template_data->template = str_replace("Remarks :",' ',$template_data->template);
      $template_data->template = str_replace("{room_no}",' ',$template_data->template);
      $template_data->template = str_replace("{mrp}",'',$template_data->template);
       $template_data->template = str_replace("{blood_price}",'',$template_data->template);
      $template_data->template = str_replace("Discount (%):",'',$template_data->template);
$template_data->template = str_replace("{donor_id}",' ',$template_data->template);
         $template_data->template = str_replace("Donor Id",' ',$template_data->template);     

            
            
            echo $template_data->template; die;
}

/* end leaser printing*/
?>