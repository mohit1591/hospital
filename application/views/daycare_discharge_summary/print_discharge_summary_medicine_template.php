<?php 


if($all_detail['discharge_list'][0]->summery_type)
        {
        
        if($all_detail['discharge_list'][0]->summery_type=='1')
        { 
            $sumtype = 'Referral';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='2')
        { 
            $sumtype = 'Discharge';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='3')
        { 
            $sumtype = 'D.O.P.R';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='4')
        { 
            $sumtype = 'Normal';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='5')
        { 
            $sumtype = 'Death';
        }
        }
        else
        {
          
            $sumtype = 'Lama';
        
        }
        
        
$template_data = str_replace("{summary_type}",$sumtype,$template_data);
    $simulation = get_simulation_name($all_detail['discharge_list'][0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.' '.$all_detail['discharge_list'][0]->patient_name,$template_data);

    $address = $all_detail['discharge_list'][0]->address;
    $pincode = $all_detail['discharge_list'][0]->pincode;
    $admission_date_time='';
    if(!empty($all_detail['discharge_list'][0]->booking_date) && $all_detail['discharge_list'][0]->booking_date!='0000-00-00')
{
    $admission_date_time = date('d-m-Y',strtotime($all_detail['discharge_list'][0]->booking_date)); 
}
     $time = date('h:i A', strtotime($all_detail['discharge_list'][0]->booking_time));
    $booking_date_time = $admission_date_time.' '.$time;
    $patient_address = $address.' - '.$pincode;

    $template_data = str_replace("{patient_address}",$patient_address,$template_data);
    
    if(!empty($all_detail['discharge_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['discharge_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_type}",$all_detail['discharge_list'][0]->relation,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_type}",'',$template_data);
        }

    if(!empty($all_detail['discharge_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['discharge_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['discharge_list'][0]->relation_name,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_name}",'',$template_data);
        }

    $template_data = str_replace("{patient_reg_no}",$all_detail['discharge_list'][0]->patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$all_detail['discharge_list'][0]->mobile_no,$template_data);
    
    $template_data = str_replace("{daycare_no}",$all_detail['discharge_list'][0]->booking_code,$template_data);
    
    $template_data = str_replace("{booking_code}",$all_detail['discharge_list'][0]->booking_code,$template_data);
    $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
    $template_data = str_replace("{doctor_incharge}",'Dr. '.$all_detail['discharge_list'][0]->doctor_name,$template_data);
    $discharge_date='';
    if(!empty($all_detail['discharge_list'][0]->discharge_date) && $all_detail['discharge_list'][0]->discharge_date!='0000-00-00' && $all_detail['discharge_list'][0]->discharge_date!='0000-00-00 00:00:00')
    {
        $discharge_date = date('d-m-Y  h:i A',strtotime($all_detail['discharge_list'][0]->discharge_date)); 
    }
    $template_data = str_replace("{date_discharge}",$discharge_date,$template_data);
        
        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['discharge_list'][0]->gender];
        $age_y = $all_detail['discharge_list'][0]->age_y; 
        $age_m = $all_detail['discharge_list'][0]->age_m;
        $age_d = $all_detail['discharge_list'][0]->age_d;

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
        $gender_age = $gender.'/'.$patient_age;

    $template_data = str_replace("{gender_age}",$gender_age,$template_data);
    $pos_start = strpos($template_data, '{start_loop}');
    $pos_end = strpos($template_data, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data,$pos_start+12,$row_last_length-12);



    // Replace looping row//
   
    $template_data = str_replace("{discharge_medicine_list}",$discharge_medicine_list_row,$template_data);
    $template_data = str_replace("{signature}",$signature,$template_data);
    
    

    echo $template_data; 

$this->session->unset_userdata('discharge_summary_id');

/* end thermal printing */
?>

