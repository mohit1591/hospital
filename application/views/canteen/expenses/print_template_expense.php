<?php 

$payment_mode=$all_detail['expense_list'][0]->payment_mode;
/*if($all_detail['expense_list'][0]->payment_mode==1){
	$payment_mode='Cash';
}
else if($all_detail['expense_list'][0]->payment_mode==2){
	$payment_mode='Card';
}
elseif($all_detail['expense_list'][0]->payment_mode==3){
	$payment_mode='Cheque';
}
else if($all_detail['expense_list'][0]->payment_mode==4){
	$payment_mode='NEFT';
}else{
 $payment_mode=strtoupper($all_detail['expense_list'][0]->payment_mode);   
}
*/
if($all_detail['expense_list'][0]->type==1)
{
    $paid_amount = $all_detail['expense_list'][0]->employee_pay_now;
}
else
{
    $paid_amount = $all_detail['expense_list'][0]->paid_amount;
}
$vouchor_no='';
if(!empty($emp_name))
{
	$vouchor_no=$all_detail['expense_list'][0]->vouchar_no.'&emsp; <b>Employee Name :</b> '.$emp_name;
}
else{
	$vouchor_no=$all_detail['expense_list'][0]->vouchar_no;
}
$template_data->setting_value = str_replace("{receipt_no}",$vouchor_no,$template_data->setting_value);
$template_data->setting_value = str_replace("{start_loop}",'',$template_data->setting_value);
$template_data->setting_value = str_replace("{end_loop}",'',$template_data->setting_value);
$template_data->setting_value = str_replace("{payment_mode}",$payment_mode,$template_data->setting_value);
$template_data->setting_value = str_replace("{remarks}",$all_detail['expense_list'][0]->remarks,$template_data->setting_value);
$template_data->setting_value = str_replace("{signature}",ucfirst($all_detail['expense_list'][0]->user_name),$template_data->setting_value);
$template_data->setting_value = str_replace("{particulars}",$all_detail['expense_list'][0]->expenses_type,$template_data->setting_value);
$template_data->setting_value = str_replace("{receipt_date}",date('d-m-Y',strtotime($all_detail['expense_list'][0]->expenses_date)),$template_data->setting_value);
$template_data->setting_value = str_replace("{amount}",number_format($paid_amount,2,'.',''),$template_data->setting_value);
$template_data->setting_value = str_replace("{total_amount}",number_format($paid_amount,2,'.',''),$template_data->setting_value);

echo $template_data->setting_value;


/* end leaser printing*/
?>

