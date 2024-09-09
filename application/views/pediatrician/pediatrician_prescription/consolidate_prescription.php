<?php 
//echo "<pre>"; print_r($template_data->template); exit;
$user_detail = $this->session->userdata('auth_users');
$users_data = $this->session->userdata('auth_users');
/* start thermal printing */
//echo "<pre>";print_r($growth_prescriptiondata);die();
if(!empty($growth_prescriptiondata))
{
    $final_template_data ='';
    foreach($growth_prescriptiondata as $growth_prescription_ipd_data)
    {
        //echo "<pre>"; print_r($growth_prescription_data['booking_id']); exit;
        //echo "<pre>"; print_r($growth_prescription_data); exit;
        $growth_prescription_opd_data = get_pedic_opd_by_id($growth_prescription_ipd_data['booking_id']);
       // echo "<pre>"; print_r($growth_prescription_ipd_data); //exit;
        
      //$template_data->template = str_replace("{opd_code}",$growth_prescription_ipd_data['id'],$template_data->template);  
  
$template_data->template = str_replace("{booking_code}",$growth_prescription_ipd_data['booking_code'],$template_data->template);

$template_data->template = str_replace("{patient_reg_no}",$growth_prescription_opd_data['patient_code'],$template_data->template);

$template_data->template = str_replace("{mobile_no}",$growth_prescription_ipd_data['mobile_no'],$template_data->template);

 

    if(!empty($growth_prescription_opd_data['booking_code']))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">OPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$growth_prescription_opd_data['booking_code'].'</div>
            </div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
    }

     

    if(!empty($growth_prescription_opd_data['booking_date']))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Booking Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($growth_prescription_opd_data['booking_date'])).'</div>
            </div>';
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }

$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$growth_prescription_opd_data['gender']];
        $age_y = $growth_prescription_opd_data['age_y']; 
        $age_m = $growth_prescription_opd_data['age_m'];
        $age_d = $growth_prescription_opd_data['age_d'];

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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $template_data->template = str_replace("{patient_weight}",$growth_prescription_ipd_data['weight'],$template_data->template);
    
    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
    $simulation = get_simulation_name($growth_prescription_opd_data['simulation_id']);
    
    $template_data->template = str_replace("{patient_name}",$growth_prescription_opd_data['patient_name'],$template_data->template);

  
            $adhar_no=$growth_prescription_opd_data['adhar_no'];
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            if($growth_prescription_opd_data['dob']!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($growth_prescription_opd_data['dob']));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


    if(!empty($growth_prescription_opd_data['opd_type']) && $growth_prescription_opd_data['opd_type']=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
        
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);
    
    if(!empty($growth_prescription_opd_data['pannel_type']) && $growth_prescription_opd_data['pannel_type']=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }

   
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    

    $template_data->template = str_replace("{referral_by}",$growth_prescription_opd_data['referral_doctor_name'],$template_data->template);
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    if(!empty($growth_prescription_opd_data['token_no']))
    {
        $template_data->template = str_replace("{token_no}",$growth_prescription_opd_data['token_no'],$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);


    $template_data->template = str_replace("{mobile_no}",$growth_prescription_ipd_data['mobile_no'],$template_data->template);

    if(!empty($growth_prescription_ipd_data['booking_code']))
    {
        $template_data->template = str_replace("{booking_level}",'OPD No. :',$template_data->template);
        $template_data->template = str_replace("{booking_code}",$growth_prescription_ipd_data['booking_code'],$template_data->template);
    }

    else
    {
         $template_data->template = str_replace("{booking_level}",'',$template_data->template);
         $template_data->template = str_replace("{booking_code}",'',$template_data->template);
    }

    if(!empty($growth_prescription_ipd_data['booking_date']))
    {
        
        $template_data->template = str_replace("{booking_date_level}",'Booking Date:',$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($growth_prescription_ipd_data['booking_date'])),$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_date_level}",'',$template_data->template);
         $template_data->template = str_replace("{booking_date}",'',$template_data->template);
    }


        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$growth_prescription_ipd_data['gender']];
        $age_y = $growth_prescription_ipd_data['age_y']; 
        $age_m = $growth_prescription_ipd_data['age_m'];
        $age_d = $growth_prescription_ipd_data['age_d'];

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
        
     $template_data->template = str_replace("{patient_weight}",$growth_prescription_ipd_data['weight'],$template_data->template);
    
    if(!empty($growth_prescription_ipd_data['oedema']==2))
    {
        
        $template_data->template = str_replace("{patient_height_level}",'Length/Height(cm) :',$template_data->template);
        $template_data->template = str_replace("{patient_height}",$growth_prescription_ipd_data['height'],$template_data->template);
    }
    else{
        $template_data->template = str_replace("{patient_height_level}",'',$template_data->template);
        $template_data->template = str_replace("{patient_height}",'',$template_data->template);
    }
    if($growth_prescription_ipd_data['measured']==2)
    {
        $template_data->template = str_replace("{measured}",'Standing',$template_data->template);
    }
    else{
        $template_data->template = str_replace("{measured}",'Recumbent',$template_data->template);
    }

  
        $template_data->template = str_replace("{head_circum}",$growth_prescription_ipd_data['head_circumference'],$template_data->template);

        $template_data->template = str_replace("{triceps}",$growth_prescription_ipd_data['triceps_skinfold'],$template_data->template);

   
        $template_data->template = str_replace("{subscapular}",$growth_prescription_ipd_data['subscapular_skinfold'],$template_data->template);
   

        $template_data->template = str_replace("{muac}",$growth_prescription_ipd_data['muac'],$template_data->template);
  


$final_template_data .= $template_data->template; 
        
    }
    
    echo $final_template_data; die;
}




/* end leaser printing*/
?>

