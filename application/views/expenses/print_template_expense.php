<?php 
$payment_mode=$all_detail['expense_list'][0]->payment_mode;

if($all_detail['expense_list'][0]->type==1)
{
    $paid_amount = $all_detail['expense_list'][0]->employee_pay_now;
}
else
{
    $paid_amount = $all_detail['expense_list'][0]->paid_amount;
}

$template_data->setting_value = str_replace("{expenstypes}",$all_detail['expense_list'][0]->exptypes,$template_data->setting_value);

$template_data->setting_value = str_replace("{receipt_no}",$all_detail['expense_list'][0]->vouchar_no,$template_data->setting_value);
$template_data->setting_value = str_replace("{start_loop}",'',$template_data->setting_value);
$template_data->setting_value = str_replace("{end_loop}",'',$template_data->setting_value);
$template_data->setting_value = str_replace("{payment_mode}",$payment_mode,$template_data->setting_value);
$template_data->setting_value = str_replace("{remarks}",$all_detail['expense_list'][0]->remarks,$template_data->setting_value);
$template_data->setting_value = str_replace("{signature}",ucfirst($all_detail['expense_list'][0]->user_name),$template_data->setting_value);

$template_data->setting_value = str_replace("{exp_category}",ucfirst($all_detail['expense_list'][0]->exptypes),$template_data->setting_value);

//Paid To
$template_data->setting_value = str_replace("{paid_to}",ucfirst($all_detail['expense_list'][0]->vendor_names),$template_data->setting_value);




$template_data->setting_value = str_replace("{payment_from}",ucfirst($all_detail['expense_list'][0]->payment_from),$template_data->setting_value);





            $exp_type = $all_detail['expense_list'][0]->exp_category;
            if($all_detail['expense_list'][0]->type>0)
            {
              $exp_type = $all_detail['expense_list'][0]->expenses_type;
            }
            if($all_detail['expense_list'][0]->type=='13' && empty($all_detail['expense_list'][0]->parent_id))
            {
                $exp_type='Doctor Commission';
            }
            
            if($all_detail['expense_list'][0]->type=='13')
            {
                $paid_to_name =$all_detail['expense_list'][0]->doctor_name;
            }
            else
            {
                $paid_to_name =$all_detail['expense_list'][0]->patient_name.' '.$all_detail['expense_list'][0]->book_code;
            }



$template_data->setting_value = str_replace("{particulars}",$exp_type.' ('.$paid_to_name.')',$template_data->setting_value);
$template_data->setting_value = str_replace("{receipt_date}",date('d-m-Y',strtotime($all_detail['expense_list'][0]->expenses_date)),$template_data->setting_value);

$template_data->setting_value = str_replace("{paid_to_name}",$all_detail['expense_list'][0]->paid_to_name,$template_data->setting_value);



$template_data->setting_value = str_replace("{vendor_name}",$all_detail['expense_list'][0]->vendor_names,$template_data->setting_value);


if($type==1)
{
   $code_d =$persone_reg; 
   $template_data->setting_value = str_replace("Patient Reg. No. ",'Reg. No.',$template_data->setting_value);
}
else if($type==2)
{
    $code_d =$persone_reg;
    $template_data->setting_value = str_replace("Patient Reg. No. ",'Reg. No.',$template_data->setting_value);
}
else
{
    $code_d =$all_detail['expense_list'][0]->patient_code;
}
$template_data->setting_value = str_replace("{patient_reg_no}",$code_d,$template_data->setting_value);



if($type==1)
{
   $code_name =$persone_name; 
   $template_data->setting_value = str_replace("Patient Name ",'Employee Name',$template_data->setting_value);
}
else if($type==2)
{
    $code_name =$persone_name;
    $template_data->setting_value = str_replace("Patient Name ",'Vendor Name',$template_data->setting_value);
}
else
{
    $code_name =$all_detail['expense_list'][0]->patient_name;
}
$template_data->setting_value = str_replace("{patient_name}",$code_name,$template_data->setting_value);

if($type==1)
{
   $code_mobile =$persone_mobile; 
   
}
else if($type==2)
{
    $code_mobile =$persone_mobile;
    
    
        
}
else
{
    $code_mobile =$all_detail['expense_list'][0]->mobile_no;
}

$template_data->setting_value = str_replace("{mobile_no}",$code_mobile,$template_data->setting_value);
$gender = array('0'=>'Female', '1'=>'Male','2'=>'Others');

				$gender = $gender[$all_detail['expense_list'][0]->gender];

				$age_y = $all_detail['expense_list'][0]->age_y;
                $age_m = $all_detail['expense_list'][0]->age_m;
                $age_d = $all_detail['expense_list'][0]->age_d;
               
                $age = "";
                if($age_y>0)
                {
                $year = 'Years';
                if($age_y==1)
                {
                  $year = 'Year';
                }
                $age .= $age_y." ".$year;
                }
                if($age_m>0)
                {
                $month = 'Months';
                if($age_m==1)
                {
                  $month = 'Month';
                }
                $age .= ", ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= ", ".$age_d." ".$day;
                }
                
            
            $gender_age = $gender.' ' .$age; 
$template_data->setting_value = str_replace("{gender_age}",$gender_age,$template_data->setting_value);

$template_data->setting_value = str_replace("{booking_code}",$all_detail['expense_list'][0]->book_code,$template_data->setting_value);
if(!empty($all_detail['expense_list'][0]->book_date) && $all_detail['expense_list'][0]->book_date!='1970-01-01')
{
	$date_booking = date('d-m-Y',strtotime($all_detail['expense_list'][0]->book_date));
}
else
{
	$date_booking = '';	
	$template_data->setting_value = str_replace("Booking Date :",' ',$template_data->setting_value);
}
$template_data->setting_value = str_replace("{booking_date}",$date_booking,$template_data->setting_value);




$template_data->setting_value = str_replace("{amount}",number_format($paid_amount,2,'.',''),$template_data->setting_value);
$template_data->setting_value = str_replace("{total_amount}",number_format($paid_amount,2,'.',''),$template_data->setting_value);

echo $template_data->setting_value;


/* end leaser printing*/
?>