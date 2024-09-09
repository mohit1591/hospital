<?php 
$user_detail = $this->session->userdata('auth_users');
$users_data = $this->session->userdata('auth_users');
/* start thermal printing */
//echo "<pre>";print_r($growth_prescription_data);die();
$template_data->template = str_replace("{opd_code}",$growth_prescription_ipd_data['id'],$template_data->template);

if($template_data->printer_id==2)
{
  
$template_data->template = str_replace("{booking_code}",$growth_prescription_ipd_data['booking_code'],$template_data->template);

    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
    $simulation = get_simulation_name($all_detail['opd_list'][0]->simulation_id);
    
    $template_data->template = str_replace("{patient_name}",$growth_prescription_ipd_data['patient_name'],$template_data->template);

  
            $adhar_no=$all_detail['opd_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            if($all_detail['opd_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['opd_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


    if(!empty($all_detail['opd_list'][0]->opd_type) && $all_detail['opd_list'][0]->opd_type=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
        
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);
    
    if(!empty($all_detail['opd_list'][0]->pannel_type) && $all_detail['opd_list'][0]->pannel_type=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }

    if(in_array('218',$users_data['permission']['section']))
    {
        if($all_detail['opd_list'][0]->paid_amount>0)
        {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['opd_list'][0]->reciept_prefix.$all_detail['opd_list'][0]->reciept_suffix,$template_data->template);
        }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }

    $template_data->template = str_replace("{referral_by}",$all_detail['opd_list'][0]->referral_doctor_name,$template_data->template);
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    if(!empty($all_detail['opd_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['opd_list'][0]->token_no,$template_data->template);    
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
        
     $template_data->template = str_replace("{patient_weight}",$growth_prescription_data['weight'],$template_data->template);
    
    if(!empty($growth_prescription_data['oedema']==2))
    {
        
        $template_data->template = str_replace("{patient_height_level}",'Length/Height(cm) :',$template_data->template);
        $template_data->template = str_replace("{patient_height}",$growth_prescription_data['height'],$template_data->template);
    }
    else{
        $template_data->template = str_replace("{patient_height_level}",'',$template_data->template);
        $template_data->template = str_replace("{patient_height}",'',$template_data->template);
    }
    if($growth_prescription_data['measured']==2)
    {
        $template_data->template = str_replace("{measured}",'Standing',$template_data->template);
    }
    else{
        $template_data->template = str_replace("{measured}",'Recumbent',$template_data->template);
    }

  
        $template_data->template = str_replace("{head_circum}",$growth_prescription_data['head_circumference'],$template_data->template);

        $template_data->template = str_replace("{triceps}",$growth_prescription_data['triceps_skinfold'],$template_data->template);

   
        $template_data->template = str_replace("{subscapular}",$growth_prescription_data['subscapular_skinfold'],$template_data->template);
   

        $template_data->template = str_replace("{muac}",$growth_prescription_data['muac'],$template_data->template);
  


}
/* end thermal printing */


/* start dot printing */
if($template_data->printer_id==3)
{
   

    $simulation = get_simulation_name($all_detail['opd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$growth_prescription_ipd_data['patient_name'],$template_data->template);

   
        
            // adhar no
            $adhar_no=$all_detail['opd_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            
    if(!empty($all_detail['opd_list'][0]->opd_type) && $all_detail['opd_list'][0]->opd_type=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
        
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);
    
    if(!empty($all_detail['opd_list'][0]->pannel_type) && $all_detail['opd_list'][0]->pannel_type=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    $template_data->template = str_replace("{referral_by}",$all_detail['opd_list'][0]->referral_doctor_name,$template_data->template);

    if(!empty($all_detail['opd_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['opd_list'][0]->token_no,$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }

      if(in_array('218',$users_data['permission']['section']))
      {

            if($all_detail['opd_list'][0]->paid_amount>0)
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['opd_list'][0]->reciept_prefix.$all_detail['opd_list'][0]->reciept_suffix,$template_data->template);
            }
      }
      else
      {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);

   
    $template_data->template = str_replace("{mobile_no}",$growth_prescription_ipd_data['mobile_no'],$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$growth_prescription_ipd_data['patient_code'],$template_data->template);

    $email_address = $all_detail['opd_list'][0]->patient_email;
    $template_data->template = str_replace("{email_address}",$email_address,$template_data->template);

  


    if(!empty($growth_prescription_ipd_data['booking_code']))
    {
        $receipt_code = '<div><br><b>OPD No.</b>'.$growth_prescription_ipd_data['booking_code'].'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
    }
  
    if(!empty($growth_prescription_ipd_data['booking_date']))
    {
        $booking_date = '<br><b>Booking Date</b>'.date('d-m-Y',strtotime($growth_prescription_ipd_data['booking_date']));
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $template_data->template = str_replace("{patient_weight}",$growth_prescription_data['weight'],$template_data->template);
    
    if(!empty($growth_prescription_data['oedema']==2))
    {
        
        $template_data->template = str_replace("{patient_height_level}",'Length/Height(cm) :',$template_data->template);
        $template_data->template = str_replace("{patient_height}",$growth_prescription_data['height'],$template_data->template);
    }
    else{
        $template_data->template = str_replace("{patient_height_level}",'',$template_data->template);
        $template_data->template = str_replace("{patient_height}",'',$template_data->template);
    }
    if($growth_prescription_data['measured']==2)
    {
        $template_data->template = str_replace("{measured}",'Standing',$template_data->template);
    }
    else{
        $template_data->template = str_replace("{measured}",'Recumbent',$template_data->template);
    }

  
        $template_data->template = str_replace("{head_circum}",$growth_prescription_data['head_circumference'],$template_data->template);

        $template_data->template = str_replace("{triceps}",$growth_prescription_data['triceps_skinfold'],$template_data->template);

   
        $template_data->template = str_replace("{subscapular}",$growth_prescription_data['subscapular_skinfold'],$template_data->template);
   

        $template_data->template = str_replace("{muac}",$growth_prescription_data['muac'],$template_data->template);
  


}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['opd_list']);die;
if($template_data->printer_id==1)
{
    
       
    $simulation = get_simulation_name($all_detail['opd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$growth_prescription_ipd_data['patient_name'],$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$growth_prescription_ipd_data['patient_code'],$template_data->template);
    
            $adhar_no=$all_detail['opd_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            if($all_detail['opd_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['opd_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


       
    if(!empty($all_detail['opd_list'][0]->opd_type) && $all_detail['opd_list'][0]->opd_type=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);

    if(!empty($all_detail['opd_list'][0]->pannel_type) && $all_detail['opd_list'][0]->pannel_type=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }

      if(in_array('218',$users_data['permission']['section']))
      {

            if($all_detail['opd_list'][0]->paid_amount>0)
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['opd_list'][0]->reciept_prefix.$all_detail['opd_list'][0]->reciept_suffix,$template_data->template);
            }
      }
      else
      {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    if(!empty($all_detail['opd_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['opd_list'][0]->token_no,$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }     

  
    
    $template_data->template = str_replace("{mobile_no}",$growth_prescription_ipd_data['mobile_no'],$template_data->template);

 

    if(!empty($growth_prescription_ipd_data['booking_code']))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">OPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$growth_prescription_ipd_data['booking_code'].'</div>
            </div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
    }

     

    if(!empty($growth_prescription_ipd_data['booking_date']))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Booking Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($growth_prescription_ipd_data['booking_date'])).'</div>
            </div>';
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }

    //$template_data->template = str_replace("{doctor_name}",'Dr.'. get_doctor_name($all_detail['opd_list'][0]->attended_doctor),$template_data->template);
    

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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
     $template_data->template = str_replace("{patient_weight}",$growth_prescription_data['weight'],$template_data->template);
    
    if(!empty($growth_prescription_data['oedema']==2))
    {
        
        $template_data->template = str_replace("{patient_height_level}",'Length/Height(cm) :',$template_data->template);
        $template_data->template = str_replace("{patient_height}",$growth_prescription_data['height'],$template_data->template);
    }
    else{
        $template_data->template = str_replace("{patient_height_level}",'',$template_data->template);
        $template_data->template = str_replace("{patient_height}",'',$template_data->template);
    }
    if($growth_prescription_data['measured']==2)
    {
        $template_data->template = str_replace("{measured}",'Standing',$template_data->template);
    }
    else{
        $template_data->template = str_replace("{measured}",'Recumbent',$template_data->template);
    }

  
        $template_data->template = str_replace("{head_circum}",$growth_prescription_data['head_circumference'],$template_data->template);

        $template_data->template = str_replace("{triceps}",$growth_prescription_data['triceps_skinfold'],$template_data->template);

   
        $template_data->template = str_replace("{subscapular}",$growth_prescription_data['subscapular_skinfold'],$template_data->template);
   

        $template_data->template = str_replace("{muac}",$growth_prescription_data['muac'],$template_data->template);
  
    $this->session->unset_userdata('opd_booking_id');
    echo $template_data->template; 
}
 
/* end leaser printing*/
?>

