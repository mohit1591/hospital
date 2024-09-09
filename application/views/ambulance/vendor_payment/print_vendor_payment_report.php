<?php 
//print '<pre>';print_r($all_detail['sales_list'][0]);die;
$pay_mode=$all_detail['sales_list'][0]->payment_mode;
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
if($template_data->printer_id==2){
    
$template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);


$template_data->template = str_replace("{vendor_name}",$all_detail['sales_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No.:","Receipt No.:",$template_data->template);

$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);

$template_data->template = str_replace("{sn}","1",$template_data->template);
//echo $i;

$template_data->template = str_replace("{total_vat}",'',$template_data->template);

$template_data->template = str_replace("{total_discount}",'0.00',$template_data->template);

$template_data->template = str_replace("{total_net}",$balance,$template_data->template);
$template_data->template = str_replace("{total_amount}",$balance,$template_data->template);
$template_data->template = str_replace("{medicine_per_net_amount}",$balance,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{balance}",$balance-$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);

if($type==1){
$template_data->template = str_replace("Name:","Patient Name",$template_data->template);
}
if($type==2){
$template_data->template = str_replace("Name:","Vendor Name",$template_data->template);
}

if($type==2){
$template_data->template = str_replace("Patient Reg No :","Vendor Code",$template_data->template);
}

$template_data->template = str_replace('Medicine',"Particulars",$template_data->template);
$template_data->template = str_replace('QTY',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount:',"",$template_data->template);
$template_data->template = str_replace('VAT:',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{medicine_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('{cgst}',"",$template_data->template);
$template_data->template = str_replace('{igst}',"",$template_data->template);
$template_data->template = str_replace('{sgst}',"",$template_data->template);
$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
//$all_detail['sales_list'][0]->cgst
$template_data->template = str_replace("{tot_cgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->sgst
$template_data->template = str_replace("{tot_sgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->igst
$template_data->template = str_replace("{tot_igst}",'0.00',$template_data->template);
$template_data->template = str_replace('{tot_discount}',"0.00",$template_data->template);

$template_data->template = str_replace("{purchase_no}",$all_detail['sales_list'][0]->purchase_id,$template_data->template);
$template_data->template = str_replace("{email_id}",$all_detail['sales_list'][0]->email,$template_data->template);
$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);
$template_data->template = str_replace("{signature}",$all_detail['sales_list'][0]->user_name,$template_data->template);

$template_data->template = str_replace('CGST(%)',"",$template_data->template);
$template_data->template = str_replace('IGST(%)',"",$template_data->template);
$template_data->template = str_replace('SGST(%)',"",$template_data->template);

 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
    $template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);


	$genders = array('0'=>'F','1'=>'M','2'=>'O');
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
    
	$template_data->template = str_replace("{vendor_name}",$all_detail['sales_list'][0]->name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("Invoice No.:","Receipt No. :",$template_data->template);

$template_data->template = str_replace("INVOICE","Receipt No.:",$template_data->template);
if($type==1){
$template_data->template = str_replace("Salesman","Patient Registration No.:",$template_data->template);
}
if($type==2){
   $template_data->template = str_replace("Salesman","Vendor Code :",$template_data->template); 
}

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
$template_data->template = str_replace("{salesman}",$all_detail['sales_list'][0]->code,$template_data->template);

$template_data->template = str_replace("{total_discount}",'',$template_data->template);

$template_data->template = str_replace("{total_net}",$balance,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);

$template_data->template = str_replace("{total_gross}",$balance,$template_data->template);

$template_data->template = str_replace("{balance}",$balance-$all_detail['sales_list'][0]->debit,$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);


if($type==2){
$template_data->template = str_replace("Patient Name :","Vendor Name",$template_data->template);
}

$template_data->template = str_replace("{purchase_no}",$all_detail['sales_list'][0]->purchase_id,$template_data->template);
$template_data->template = str_replace("{email_id}",$all_detail['sales_list'][0]->email,$template_data->template);
$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);
$template_data->template = str_replace("{signature}",$all_detail['sales_list'][0]->user_name,$template_data->template);
$template_data->template = str_replace('Medicine Name',"Particulars",$template_data->template);
$template_data->template = str_replace('Qty',"",$template_data->template);
$template_data->template = str_replace('Cash Disc.',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount(%)',"",$template_data->template);
$template_data->template = str_replace('Disc.',"",$template_data->template);
$template_data->template = str_replace('Vat%',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{medicine_name}',"B. Clearance",$template_data->template);
$template_data->template = str_replace('{medicine_qty}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_price}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_discount}',"",$template_data->template);
$template_data->template = str_replace('{medicine_per_net_amount}',$balance,$template_data->template);
$template_data->template = str_replace('{medicine_per_vat}',"",$template_data->template);

$template_data->template = str_replace('{cgst}',"",$template_data->template);
$template_data->template = str_replace('{igst}',"",$template_data->template);
$template_data->template = str_replace('{sgst}',"",$template_data->template);

$template_data->template = str_replace('{tot_cgst}',"0.00",$template_data->template);
$template_data->template = str_replace('{tot_igst}',"0.00",$template_data->template);
$template_data->template = str_replace('{tot_sgst}',"0.00",$template_data->template);

$template_data->template = str_replace('{total_cgst}',"0.00",$template_data->template);
$template_data->template = str_replace('{total_igst}',"0.00",$template_data->template);
$template_data->template = str_replace('{total_sgst}',"0.00",$template_data->template);

$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{tot_discount}',"0.00",$template_data->template);

$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amt.',"Total Amt.",$template_data->template);
//$all_detail['sales_list'][0]->cgst
$template_data->template = str_replace("{tot_cgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->sgst
$template_data->template = str_replace("{tot_sgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->igst
$template_data->template = str_replace("{tot_igst}",'0.00',$template_data->template);
$template_data->template = str_replace('{tot_discount}',"0.00",$template_data->template);


$template_data->template = str_replace('Cash Disc(%)',"",$template_data->template);
$template_data->template = str_replace('Purchase Rate',"",$template_data->template);
$template_data->template = str_replace('CGST(%)',"",$template_data->template);
$template_data->template = str_replace('IGST(%)',"",$template_data->template);
$template_data->template = str_replace('SGST(%)',"",$template_data->template);

if(!empty($all_detail['sales_list'][0]->remk))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remk,$template_data->template);
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
   
 if($type==2){
   $template_data->template = str_replace("Refered by :",'',$template_data->template);
 }
    $template_data->template = str_replace("{start_loop}","",$template_data->template);
$template_data->template = str_replace("{end_loop}","",$template_data->template);

	$genders = array('0'=>'F','1'=>'M','2'=>'O');
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

$template_data->template = str_replace("{invoice_no}",$all_detail['sales_list'][0]->recepit_no,$template_data->template);

   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

$template_data->template = str_replace('Medicine Name',"Particulars",$template_data->template);
$template_data->template = str_replace("INVOICE:","Receipt No. :",$template_data->template);


	$template_data->template = str_replace("{vendor_name}",$all_detail['sales_list'][0]->name,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['sales_list'][0]->mobile_no,$template_data->template);
    if($type==1){
     $template_data->template = str_replace("{refered_by}",$all_detail['sales_list'][0]->doctor_name,$template_data->template);   
 }
 if($type==2){
    $template_data->template = str_replace("{refered_by}",'',$template_data->template);  
 }
    

 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);


$template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['sales_list'][0]->date)),$template_data->template);


//echo $i;
$template_data->template = str_replace("{vat}",'',$template_data->template);
$template_data->template = str_replace("Bill To:","Patient Reg No. :",$template_data->template);
$template_data->template = str_replace("{sales_name}",$all_detail['sales_list'][0]->code,$template_data->template);

$template_data->template = str_replace("{discount}",'',$template_data->template);
$template_data->template = str_replace("{total_amount}",$balance,$template_data->template);
$template_data->template = str_replace("{net_amount}",$balance,$template_data->template);
$template_data->template = str_replace("{vat_percent}",'',$template_data->template);
$template_data->template = str_replace("{discount_percent}",'',$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['sales_list'][0]->debit,$template_data->template);
$template_data->template = str_replace("{purchase_no}",$all_detail['sales_list'][0]->purchase_id,$template_data->template);

$template_data->template = str_replace("{email_id}",$all_detail['sales_list'][0]->email,$template_data->template);
$template_data->template = str_replace("{signature}",$all_detail['sales_list'][0]->user_name,$template_data->template);
$template_data->template = str_replace("{address}",$all_detail['sales_list'][0]->address,$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",$balance,$template_data->template);

$template_data->template = str_replace("{balance}",$balance-$all_detail['sales_list'][0]->debit,$template_data->template);
	$template_data->template = str_replace("{payment_mode}",$pay_mode,$template_data->template);
if($type==1){
$template_data->template = str_replace("Name:","Patient Name",$template_data->template);
}
if($type==2){
$template_data->template = str_replace("Name:","Vendor Name",$template_data->template);
}

if($type==2){
$template_data->template = str_replace("Patient Reg No :","Vendor Code",$template_data->template);
}
$template_data->template = str_replace('{cgst}',"",$template_data->template);
$template_data->template = str_replace('{igst}',"",$template_data->template);
$template_data->template = str_replace('{sgst}',"",$template_data->template);

$template_data->template = str_replace('CGST(%)',"",$template_data->template);
$template_data->template = str_replace('IGST(%)',"",$template_data->template);
$template_data->template = str_replace('SGST(%)',"",$template_data->template);

//$template_data->template = str_replace('Medicine Name',"Particulars",$template_data->template);
$template_data->template = str_replace('QTY',"",$template_data->template);
$template_data->template = str_replace('Basic Price',"",$template_data->template);
$template_data->template = str_replace('Discount(%)',"",$template_data->template);
$template_data->template = str_replace('Vat',"",$template_data->template);
$template_data->template = str_replace('{s_no}',"1",$template_data->template);
$template_data->template = str_replace('{medicine_name}',"Balance Clearance",$template_data->template);
$template_data->template = str_replace('{quantity}',"",$template_data->template);
$template_data->template = str_replace('{mrp}',"",$template_data->template);
$template_data->template = str_replace('Net Amount',"Total Amount",$template_data->template);
//$all_detail['sales_list'][0]->cgst
$template_data->template = str_replace("{tot_cgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->sgst
$template_data->template = str_replace("{tot_sgst}",'0.00',$template_data->template);
//$all_detail['sales_list'][0]->igst
$template_data->template = str_replace("{tot_igst}",'0.00',$template_data->template);
$template_data->template = str_replace('{tot_discount}',"0.00",$template_data->template);

if(!empty($all_detail['sales_list'][0]->remk))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['sales_list'][0]->remk,$template_data->template);
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

