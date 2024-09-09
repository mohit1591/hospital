<?php 
//print '<pre>'; print_r($all_detail['operation_list']);die;


/* start thermal printing */
if($template_data->printer_id==2){
    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);
	
$template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("{s_no}",1,$template_data->template);
$template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);

$genders = array('0'=>'F','1'=>'M','2'=>'O');
        $gender = $genders[$all_detail['operation_list'][0]->gender];
        $age_y = $all_detail['operation_list'][0]->age_y; 
        $age_m = $all_detail['operation_list'][0]->age_m;
        $age_d = $all_detail['operation_list'][0]->age_d;

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


$template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

if($user_detail['users_role']==4)
{
 $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
}
else
{
  $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);    
}


 echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
        $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);
    
$template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
 //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("{s_no}",1,$template_data->template);
$template_data->template = str_replace("{package_type}",$all_detail['operation_list'][0]->pacakge_type,$template_data->template);
$template_data->template = str_replace("{package_days}",$all_detail['operation_list'][0]->days,$template_data->template);
$template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);
$template_data->template = str_replace("{package_amount}",$all_detail['operation_list'][0]->package_amount,$template_data->template);
$template_data->template = str_replace("{package_remarks}",$all_detail['operation_list'][0]->pacakge_remarks,$template_data->template);
$template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)),$template_data->template);
$template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("{operation_date_time}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).''.date('H:i:s',strtotime($all_detail['operation_list'][0]->operation_time)),$template_data->template);


$genders = array('0'=>'F','1'=>'M','2'=>'O');
        $gender = $genders[$all_detail['operation_list'][0]->gender];
        $age_y = $all_detail['operation_list'][0]->age_y; 
        $age_m = $all_detail['operation_list'][0]->age_m;
        $age_d = $all_detail['operation_list'][0]->age_d;

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
        $doctor_name=array();
        foreach($all_detail['operation_list']['doctor_list']as $doctor_list){
         $doctor_name[]=$doctor_list->doctor_name;

        }
        $doctor_name= implode(',',$doctor_name);

$template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

if($user_detail['users_role']==4)
{
 $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
}
else
{
  $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);    
}
$template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);


 echo $template_data->template; 

}
/* end dot printing */


/* start leaser printing */
if($template_data->printer_id==1){
    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{ipd_no}",$all_detail['operation_list'][0]->ipd_no,$template_data->template);
$template_data->template = str_replace("{room_no}",$all_detail['operation_list'][0]->room_no,$template_data->template);
$template_data->template = str_replace("{bed_no}",$all_detail['operation_list'][0]->bed_no,$template_data->template);
$template_data->template = str_replace("{note}",$all_detail['operation_list'][0]->remarks,$template_data->template);

$template_data->template = str_replace("{operation}",$all_detail['operation_list'][0]->operation_name,$template_data->template);

$template_data->template = str_replace("{pateint_address}",$all_detail['operation_list'][0]->address,$template_data->template);

    $template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
    //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{s_no}",1,$template_data->template);
    $template_data->template = str_replace("{package_type}",$all_detail['operation_list'][0]->pacakge_type,$template_data->template);
    $template_data->template = str_replace("{package_days}",$all_detail['operation_list'][0]->days,$template_data->template);
    $template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);
    $template_data->template = str_replace("{package_amount}",$all_detail['operation_list'][0]->package_amount,$template_data->template);
    $template_data->template = str_replace("{package_remarks}",$all_detail['operation_list'][0]->pacakge_remarks,$template_data->template);
    $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)),$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{operation_date_time}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.date('H:i:s',strtotime($all_detail['operation_list'][0]->operation_time)),$template_data->template);


    $genders = array('0'=>'F','1'=>'M','2'=>'O');
    $gender = $genders[$all_detail['operation_list'][0]->gender];
    $age_y = $all_detail['operation_list'][0]->age_y; 
    $age_m = $all_detail['operation_list'][0]->age_m;
    $age_d = $all_detail['operation_list'][0]->age_d;

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
    $doctor_name=array();
    foreach($all_detail['operation_list']['doctor_list']as $doctor_list){
    $doctor_name[]=$doctor_list->doctor_name;

    }
    $doctor_name= implode(',',$doctor_name);

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

    if($user_detail['users_role']==4)
    {
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
    }
    else
    {
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);    
    }
    $template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);

	echo $template_data->template;
}

/* end leaser printing*/
?>

