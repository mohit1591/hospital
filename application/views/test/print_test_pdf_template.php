<?php 
/* start thermal printing */
    //echo "<pre>";print_r($all_detail['prescription_list']); exit;
    $simulation = get_simulation_name($simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.' '.$patient_name,$template_data);

    $address = $address;
    $pincode = $pincode;
    $booking_date_time = date('d-m-Y',strtotime($booking_date));       
    
    $patient_address = $address.' - '.$pincode;

    $template_data = str_replace("{address}",$patient_address,$template_data);

    $template_data = str_replace("{patient_reg_no}",$patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$mobile_no,$template_data);
    
    $template_data = str_replace("{lab_reg_no}",$lab_reg_no,$template_data);
    $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
    $template_data = str_replace("{ref_doctor_name}",'Dr. '.$referred_by,$template_data);
    
    if(!empty($doctor_name))
    {
        $doctorname = 'Dr. '.$doctor_name;
    }
    else
    {
        $doctorname = '';
    }
    $template_data = str_replace("{doctor_name}",$doctorname,$template_data);

    
    $gender_age = $gender.'/'.$patient_age;

    $template_data = str_replace("{patient_age}",$gender_age,$template_data);
    $pos_start = strpos($template_data, '{start_loop}');
    $pos_end = strpos($template_data, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data,$pos_start+12,$row_last_length-12);
    $template_data = str_replace("{test_report_data}",$test_report_data,$template_data);
    $template_data = str_replace("{signature_reprt_data}",$signature_reprt_data,$template_data);
    
    echo $template_data; 


/* end thermal printing */
?>

