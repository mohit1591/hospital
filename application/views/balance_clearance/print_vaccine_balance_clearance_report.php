<?php //echo $template_data->printer_id;
//print '<pre>';print_r($all_detail['sales_list'][0]);die;
$pay_mode='';
$pay_mode=$all_detail['sales_list'][0]->payment_mode;
$users_data = $this->session->userdata('auth_users');
/*if($all_detail['sales_list'][0]->pay_mode==1){
	$pay_mode='Cash';
}
if($all_detail['sales_list'][0]->pay_mode==2){
	$pay_mode='Card';
}
if($all_detail['sales_list'][0]->pay_mode==3){
	$pay_mode='Cheque';
}
if($all_detail['sales_list'][0]->pay_mode==4){
	$pay_mode='NEFT';
}*/
/* start thermal printing */

$template_data->template = str_replace('CGST:',"",$template_data->template);
$template_data->template = str_replace('SGST:',"",$template_data->template);
$template_data->template = str_replace('IGST:',"",$template_data->template);
$template_data->template = str_replace('Discount(%):',"",$template_data->template);

$template_data->template = str_replace('Discount:',"",$template_data->template);
$template_data->template = str_replace('Discount :',"",$template_data->template);


$template_data->template = str_replace("{transaction_no}",$transaction_id,$template_data->template);

if($template_data->printer_id==2){
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);
    
 if($type==2){
 $template_data->template = str_replace("{booking_level}",'Booking Code:',$template_data->template);

 $template_data->template = str_replace("{booking_date_level}",'Booking Date:',$template_data->template);
 $template_data->template = str_replace("{booking_code}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

 }

if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

 if($type==3){
   $template_data->template = str_replace("{booking_level}",'Sale No. :',$template_data->template);
  $template_data->template = str_replace("{booking_code}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

   $template_data->template = str_replace("{booking_date_level}",'Sale Date:',$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);
 
 }

$template_data->template = str_replace('{hsn_no}',"0",$template_data->template);
$template_data->template = str_replace('{exp_date}',"0",$template_data->template);
$template_data->template = str_replace('{batch_no}',"0",$template_data->template);




$template_data->template = str_replace("{booking_code}",'',$template_data->template);
$template_data->template = str_replace("{Consultant}",'',$template_data->template);
$template_data->template = str_replace("{specialization}",'',$template_data->template);
$template_data->template = str_replace("{Consultant_level}",'',$template_data->template);
$template_data->template = str_replace("{specialization_level}",'',$template_data->template);
$template_data->template = str_replace("{next_app_date}",'',$template_data->template);

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

    
$template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);


$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.ucfirst($all_detail['sales_list'][0]->name),$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No.:","Receipt No.:",$template_data->template);

$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->c_date)),$template_data->template);

$template_data->template = str_replace("{sn}","1",$template_data->template);
//echo $i;

$template_data->template = str_replace("{total_vat}",'',$template_data->template);

$template_data->template = str_replace("{total_discount}",'',$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$template_data->template = str_replace("{net_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{medicine_per_net_amount}",$new_balance['balance'][0]->balance,$template_data->template);

$template_data->template = str_replace('{medicine_per_vat}',"",$template_data->template);
$template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);


$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{amount}",$new_balance['balance'][0]->balance,$template_data->template);


$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);
$template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);


$template_data->template = str_replace("Name:","Patient Name",$template_data->template);
$template_data->template = str_replace("{salesman}",$all_detail['sales_list'][0]->username,$template_data->template);

//$template_data->template = str_replace('Medicine',"Particulars",$template_data->template);
$template_data->template = str_replace('QTY',"",$template_data->template);
$template_data->template = str_replace('QTY ',"",$template_data->template);
$template_data->template = str_replace(' QTY',"",$template_data->template);
$template_data->template = str_replace(' QTY ',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount:',"",$template_data->template);
$template_data->template = str_replace('VAT:',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{vaccine_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

$template_data->template = str_replace('{cgst}'," ",$template_data->template);
$template_data->template = str_replace('{igst}'," ",$template_data->template);
$template_data->template = str_replace('{sgst}'," ",$template_data->template);
$template_data->template = str_replace('{discount}'," ",$template_data->template);
$template_data->template = str_replace('{tot_cgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_igst}',"",$template_data->template);
$template_data->template = str_replace('{tot_sgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_discount}',"",$template_data->template);

/*if(!empty($all_detail['sales_list'][0]->remk))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remk,$template_data->template);
        }
        else
        {
           */$template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
       // }
 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);
  if($type==2){
     $receipt_code = '<div><b>Booking Code:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);

         $booking_date = '<b>Booking Date:</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
      
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
         $template_data->template = str_replace("Consultant.",'',$template_data->template);
         $template_data->template = str_replace("Specialization",'',$template_data->template);

  }

if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

$template_data->template = str_replace('{hsn_no}',"0",$template_data->template);
$template_data->template = str_replace('{exp_date}',"0",$template_data->template);
$template_data->template = str_replace('{batch_no}',"0",$template_data->template);

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
   if($type==3){
     $receipt_code = '<div><b>Sale No.:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);

         $booking_date = '<b>Sale Date:</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);

        $template_data->template = str_replace("Consultant.",'Reffered By',$template_data->template);
         $template_data->template = str_replace("Specialization",'',$template_data->template);

        $template_data->template = str_replace("{specialization}",'',$template_data->template);
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
        
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

$template_data->template = str_replace("{booking_code}",'',$template_data->template);
$template_data->template = str_replace("{Consultant}",'',$template_data->template);
$template_data->template = str_replace("{specialization}",'',$template_data->template);
$template_data->template = str_replace("{next_app_date}",'',$template_data->template);


   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    
	$template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.ucfirst($all_detail['sales_list'][0]->name),$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No.:","Receipt No. :",$template_data->template);

$template_data->template = str_replace("INVOICE","Receipt No. :",$template_data->template);

//$template_data->template = str_replace("Salesman","Patient Registration No :",$template_data->template);

    if($type==3){
      $template_data->template = str_replace("OPD","Medicine",$template_data->template);
  
    }
   $template_data->template = str_replace("{page_type}","Blance Clearance",$template_data->template);



$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);



$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->c_date)),$template_data->template);

$template_data->template = str_replace("{total_medicine}",0,$template_data->template);
$template_data->template = str_replace("{total_quantity}",'',$template_data->template);
$template_data->template = str_replace("{total_discount_amt}",'',$template_data->template);
$template_data->template = str_replace("{total_vat_amt}",'',$template_data->template);
$template_data->template = str_replace("{total_amt_per}",'',$template_data->template);
$template_data->template = str_replace("{total_per_price_amt}",'',$template_data->template);
//echo $i;
$template_data->template = str_replace("{total_vat}",'',$template_data->template);
$template_data->template = str_replace("{doctor_name}",$all_detail['sales_list'][0]->doctor_name,$template_data->template);
$template_data->template = str_replace("{salesman}",$all_detail['sales_list'][0]->username,$template_data->template);

$template_data->template = str_replace("{total_discount}",'',$template_data->template);

$template_data->template = str_replace("{total_net}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{total_gross}",$all_detail['sales_list'][0]->total_amount,$template_data->template);
$total_balance = $new_balance['balance'][0]->balance-$all_detail['sales_list'][0]->debit;
$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);

$template_data->template = str_replace("{amount}",$new_balance['balance'][0]->balance,$template_data->template);

if($type==2) {
    $booking_date = '<b>Booking Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
     $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
     $receipt_code = '<div><b>Booking Code:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
   }
   
   if($type==3) {
    $booking_date = '<b>Sale Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
     $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
     $receipt_code = '<div><b>Sale No.:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
  
   }

$template_data->template = str_replace('Medicine Name',"Particulars",$template_data->template);
$template_data->template = str_replace('QTY',"",$template_data->template);
$template_data->template = str_replace('Qty',"",$template_data->template);
$template_data->template = str_replace('Cash Disc.',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount',"",$template_data->template);
$template_data->template = str_replace('Disc.',"",$template_data->template);
$template_data->template = str_replace('Vat%',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{vaccine_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('{medicine_qty}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_price}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_net_amount}',$new_balance['balance'][0]->balance,$template_data->template);
$template_data->template = str_replace('{medicine_per_vat}',"",$template_data->template);
$template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);


$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amt.',"Total Amt.",$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

$template_data->template = str_replace('{cgst}',"",$template_data->template);
$template_data->template = str_replace('{igst}',"",$template_data->template);
$template_data->template = str_replace('{sgst}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_discount}',"",$template_data->template);
$template_data->template = str_replace('{tot_cgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_igst}',"",$template_data->template);
$template_data->template = str_replace('{tot_sgst}',"",$template_data->template);
$template_data->template = str_replace('{total_cgst}',"",$template_data->template);
$template_data->template = str_replace('{total_igst}',"",$template_data->template);
$template_data->template = str_replace('{total_sgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_discount}',"",$template_data->template);
/*if(!empty($all_detail['sales_list'][0]->remk))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remk,$template_data->template);
        }
        else
        {
           */$template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
       // }

	echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['sales_list']);die;
if($template_data->printer_id==1){
   
$template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);

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

if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['sales_list'][0]->reciept_prefix.$all_detail['sales_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

$template_data->template = str_replace('{hsn_no}',"0",$template_data->template);
$template_data->template = str_replace('{exp_date}',"0",$template_data->template);
$template_data->template = str_replace('{batch_no}',"0",$template_data->template);

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
  
$template_data->template = str_replace("{patient_reg_no}",$all_detail['sales_list'][0]->patient_code,$template_data->template);
    $template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);


    $template_data->template = str_replace("INVOICE:","Receipt No. :",$template_data->template);


    $template_data->template = str_replace("{patient_name}",$all_detail['sales_list'][0]->simulation.' '.ucfirst($all_detail['sales_list'][0]->name),$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
     
    $template_data->template = str_replace("{refered_by}",'Dr.'.$all_detail['sales_list'][0]->doctor_name,$template_data->template);   
    
    $template_data->template = str_replace("{patient_address}",$all_detail['sales_list'][0]->address,$template_data->template);
    $template_data->template = str_replace("{patient_address}",$all_detail['sales_list'][0]->address,$template_data->template);
      $c_date = date('d-m-Y',strtotime($all_detail['sales_list'][0]->c_date));
      $template_data->template = str_replace("{date}",$c_date,$template_data->template);
     

   if($type==2) {
    $booking_date = '<b>Booking Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));

     $template_data->template = str_replace("{specialization}",$booking_date,$template_data->template);
     $receipt_code = '<div><b>Booking Code:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
   }
   
   if($type==3) {
    $booking_date = '<b>Sale Date :</b>'.date('d-m-Y',strtotime($all_detail['sales_list'][0]->date));
     $template_data->template = str_replace("{specialization}",$booking_date,$template_data->template);
     
     $receipt_code = '<div><b>Sale No.:</b>'.$all_detail['sales_list'][0]->recepit_no.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
  
   }


//$template_data->template = str_replace("{booking_date}","Date:",$template_data->template);

$template_data->template = str_replace("{booking_code}",'',$template_data->template);
$template_data->template = str_replace("{Consultant}",'',$template_data->template);
//$template_data->template = str_replace("{specialization}",'',$template_data->template);
$template_data->template = str_replace("{next_app_date}",'',$template_data->template);

//echo $i;
$template_data->template = str_replace("{vat}",'',$template_data->template);
$template_data->template = str_replace("Bill To:","Reg. No. :",$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->code,$template_data->template);

$template_data->template = str_replace("{amount}",$new_balance['balance'][0]->balance,$template_data->template);
$template_data->template = str_replace("{discount}",'',$template_data->template);
$template_data->template = str_replace("{total_amount}",number_format($new_balance['balance'][0]->balance,2,'.', ''),$template_data->template);
$template_data->template = str_replace("{net_amount}",$new_balance['balance'][0]->balance,$template_data->template);
$template_data->template = str_replace("{vat_percent}",'',$template_data->template);
$template_data->template = str_replace("{discount_percent}",'',$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$all_detail['sales_list'][0]->total_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['sales_list'][0]->balance,$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);

$template_data->template = str_replace("Name:","Patient Name :",$template_data->template);


$template_data->template = str_replace('Medicine Name',"Particulars",$template_data->template);
$template_data->template = str_replace('QTY',"",$template_data->template);
$template_data->template = str_replace('Qty',"",$template_data->template);
$template_data->template = str_replace('{Quantity_level}',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount :',"",$template_data->template);
$template_data->template = str_replace('{total_discount}',"",$template_data->template);

$template_data->template = str_replace('Vat',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{vaccine_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{cgst}',"",$template_data->template);
$template_data->template = str_replace('{igst}',"",$template_data->template);
$template_data->template = str_replace('{sgst}',"",$template_data->template);
$template_data->template = str_replace('{discount}',"",$template_data->template);
$template_data->template = str_replace('{tot_cgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_igst}',"",$template_data->template);
$template_data->template = str_replace('{tot_sgst}',"",$template_data->template);
$template_data->template = str_replace('{tot_discount}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
$template_data->template = str_replace("{salesman}",$all_detail['sales_list'][0]->username,$template_data->template);
$template_data->template = str_replace('{medicine_per_vat}',"",$template_data->template);
$template_data->template = str_replace('{particular}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);
/*if(!empty($all_detail['sales_list'][0]->remk))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remk,$template_data->template);
        }
        else
        {*/
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks :",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
       // }
	echo $template_data->template; die;
}

/* end leaser printing*/
?>