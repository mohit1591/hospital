<?php 
    $user_detail = $this->session->userdata('auth_users');
    $users_data = $this->session->userdata('auth_users');
    //address print
    if(empty($address_setting_list))
    {
        $address = $all_detail['ipd_list'][0]->address;
        $pincode = $all_detail['ipd_list'][0]->pincode;    
        $country = $all_detail['ipd_list'][0]->country_name;    
        $state = $all_detail['ipd_list'][0]->state_name;    
        $city = $all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.' '.$country.','.$state.' '.$city.' - '.$pincode;
        $template_data = str_replace("{patient_address}",$patient_address,$template_data);
    }
    else
    {
        $address='';
        if($address_setting_list[0]->address1)
        {
           $address .= $all_detail['ipd_list'][0]->address1.' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address .= $all_detail['ipd_list'][0]->address2.' '; 
        }
       
        if($address_setting_list[0]->address3)
        {
           $address .=  $all_detail['ipd_list'][0]->address3.' '; 
        }
      
        if($address_setting_list[0]->city)
        {
           $address .=  $all_detail['ipd_list'][0]->city_name.' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address .= $all_detail['ipd_list'][0]->state_name.' '; 
        }
        if($address_setting_list[0]->country)
        {
           $address .=  $all_detail['ipd_list'][0]->country_name.' '; 
        }
        if($address_setting_list[0]->pincode)
        {
           $address .= $all_detail['ipd_list'][0]->pincode; 
        }
        $template_data = str_replace("{patient_address}",$address,$template_data);
    }
    
    $admission_date=date('d-m-Y' , strtotime($all_detail['ipd_list'][0]->admission_date));
    $admission_time=date('H:i A' , strtotime($all_detail['ipd_list'][0]->admission_time));

    if(!empty($time_setting[0]->ipd) && !empty($time_setting[0]->ipd))
    {
        // admission date time
        $template_data = str_replace("{admission_date_time}", $admission_date." ".$admission_time, $template_data);  
        // admission date time
    }
    else
    {
        // admission date time
        $template_data = str_replace("{admission_date_time}", $admission_date, $template_data);  
       // admission date time
    }
    
    // admission date time
        /*$admission_date=date('d-m-Y' , strtotime($all_detail['ipd_list'][0]->admission_date));
        $admission_time='';
        if(date('h:i:s' , strtotime($all_detail['ipd_list'][0]->admission_time))!='12:00:00' && $all_detail['ipd_list'][0]->admission_time!='00:00:00')
        {
            $admission_time=date('h:i A' , strtotime($all_detail['ipd_list'][0]->admission_time));
        }
        
        $template_data = str_replace("{admission_date_time}", $admission_date." ".$admission_time, $template_data);  */

//end of address

   //print_r($all_detail);die;
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.''.$all_detail['ipd_list'][0]->patient_name,$template_data);

    $template_data = str_replace("{diagnosis}",$all_detail['ipd_list'][0]->diagnosis,$template_data);
    

        if(!empty($all_detail['ipd_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_type}",$all_detail['ipd_list'][0]->relation,$template_data);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data);
        }

    if(!empty($all_detail['ipd_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ipd_list'][0]->relation_name,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_name}",'',$template_data);
        }
        // adhar no 
        $adhar_no=$all_detail['ipd_list'][0]->adhar_no;
        $template_data = str_replace("{adhar_no}",$adhar_no,$template_data);  
        // adhar no

        // marital status
        $marital_status=$all_detail['ipd_list'][0]->marital_status;
        $template_data = str_replace("{marital_status}",$marital_status,$template_data);
        // marital status

        // Anniversary
        $anniversary=date('Y-m-d', strtotime($all_detail['ipd_list'][0]->anniversary));
        $template_data = str_replace("{anniversary}",$anniversary,$template_data);
        // Anniversary

        // Religion Name
        $religion_name= ucwords($all_detail['ipd_list'][0]->religion_name);
        $template_data = str_replace("{religion}", $religion_name, $template_data);    
        // Religion Name

        // DOB starts here
        if($all_detail['ipd_list'][0]->dob!='0000-00-00')
        {
            $dob=date('d-m-Y' ,strtotime($all_detail['ipd_list'][0]->dob));
            $template_data = str_replace("{dob}", $dob, $template_data);
        }
        else
        {
            $template_data = str_replace("{dob}",'-', $template_data);   
        }
        // DOB Ends Here

         // Relation Name
            if($all_detail['ipd_list'][0]->relation_type==1)
                $type="father/o";
            else if($all_detail['ipd_list'][0]->relation_type==2)
                $type="husband/o";
            else if($all_detail['ipd_list'][0]->relation_type==3)
                $type="baby/o";
            else if($all_detail['ipd_list'][0]->relation_type==4)
                $type="son/o";
            else if($all_detail['ipd_list'][0]->relation_type==5)
                $type="daughter/o";
            else
                $type="";
        $relation_name= ucwords($all_detail['ipd_list'][0]->relation_name);
        $template_data = str_replace("{relation_name}", $type." ".$relation_name, $template_data);    
        // Relation Name

        // Mother Name
        $mother= ucwords($all_detail['ipd_list'][0]->mother);
        $template_data = str_replace("{mother}", $mother, $template_data);     
        // Mother Name

        // Guardian Name
        $guardian_name= ucwords($all_detail['ipd_list'][0]->guardian_name);
        $template_data = str_replace("{guardian_name}", $guardian_name, $template_data);     
        // Guardian Name

       // Guardian Email
        $guardian_email= ucwords($all_detail['ipd_list'][0]->guardian_email);
        $template_data = str_replace("{guardian_email}", $guardian_email, $template_data);  
      // guardian Email 

        // Guardian Phone
        $guardian_phone= ucwords($all_detail['ipd_list'][0]->guardian_phone);
        $template_data = str_replace("{guardian_phone}",$guardian_phone, $template_data);  
        // guardian Phone 


        // Guardian Relation
        $guardian_relation= ucwords($all_detail['ipd_list'][0]->relation);
        $template_data = str_replace("{guardian_relation}",$guardian_relation, $template_data);  
        // guardian Relation 


        // Patient email
        $patient_email= ucwords($all_detail['ipd_list'][0]->patient_email);
        $template_data = str_replace("{patient_email}", $patient_email, $template_data);  
        // patient Email 

        // Monthly Income
        $monthly_income= number_format(ucwords($all_detail['ipd_list'][0]->monthly_income,2));
        $template_data = str_replace("{monthly_income}", $monthly_income, $template_data);  
        // Monthly Income

        // occupation
        $occupation= ucwords($all_detail['ipd_list'][0]->occupation);
        $template_data = str_replace("{occupation}", $occupation, $template_data);      
        // occupation

        //insurance_type
        $insurance_type= ucwords($all_detail['ipd_list'][0]->insurance_type);
        $template_data = str_replace("{insurance_type}", $insurance_type, $template_data);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($all_detail['ipd_list'][0]->insurance_type_name);
        $template_data = str_replace("{insurance_type_name}", $insurance_type_name, $template_data);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($all_detail['ipd_list'][0]->insurance_company_name);
        $template_data = str_replace("{insurance_company_name}", $insurance_company_name, $template_data);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($all_detail['ipd_list'][0]->insurance_policy_no);
        $template_data = str_replace("{insurance_policy_no}", $insurance_policy_no, $template_data);  
        //insurance_policy_no

          //insurance_tpa_id
        $insurance_tpa_id= ucwords($all_detail['ipd_list'][0]->tpa_id);
        $template_data = str_replace("{insurance_tpa_id}", $insurance_tpa_id, $template_data);  
        //insurance_tpa_id


        //insurance_amount
        $insurance_amount= ucwords($all_detail['ipd_list'][0]->insurance_amount);
        $template_data = str_replace("{insurance_amount}", $insurance_amount, $template_data);  
        //insurance_amount

        //auth_no
        $auth_no= ucwords($all_detail['ipd_list'][0]->auth_no);
        $template_data = str_replace("{auth_no}", $auth_no, $template_data);  
        //auth_no


        // Ipd booking fields starts here
        
        // Pacakage Name
        $package_name= ucwords($all_detail['ipd_list'][0]->package_name);
        $template_data = str_replace("{package_name}", $package_name, $template_data);  

        // Referred By
        $referrer= ucwords($all_detail['ipd_list'][0]->referrered_by);
        $template_data = str_replace("{referred_by}", $referrer, $template_data);  
        // Referred By

        
        // Referrer Name
        $referrer_name= ucwords($all_detail['ipd_list'][0]->referrer_name);
        $template_data = str_replace("{referrer_name}", $referrer_name, $template_data);  
        // Referrer Name
    
        // Attented Doctors
        $attented_doctor= ucwords($all_detail['ipd_list'][0]->attented_doctor);
        $template_data = str_replace("{attented_doctors}", $attented_doctor, $template_data ); 
        $attented_doctor= ucwords($all_detail['ipd_list'][0]->attented_doctor);
        $template_data = str_replace("{attended_doctor}", $attented_doctor, $template_data ); 
        
        
        // Attented Doctors        
    
        // assigned doctors name
        $ipd_assigned_doctors=get_ipd_assigned_doctors_name($all_detail['ipd_list'][0]->id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
        $template_data = str_replace("{assigned_doctor}", "Dr. ".$ipd_assigned_doctors , $template_data);  
        // assigned doctors name

        // Advance Payment
        $advance_payment= number_format($all_detail['ipd_list'][0]->advance_payment,0);
        $template_data = str_replace("{attented_doctors}", $advance_payment, $template_data);  
        // Advance Payment

        // Registration Charge
        $reg_charge= number_format($all_detail['ipd_list'][0]->reg_charge,2);
        $template_data = str_replace("{reg_charge}", $reg_charge, $template_data);  
        // Registration Charge


        
        // admission date time
    // Ipd booking fields Ends here
    // added on 08-Feb-2018
  $template_data= str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data);
    $template_data = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data);
    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $template_data = str_replace("{booking_level}",'IPD No. :',$template_data);
        $template_data = str_replace("{ipd_no}",$all_detail['ipd_list'][0]->ipd_no,$template_data);
    }
    else
    {
         $template_data = str_replace("{booking_level}",'',$template_data);
         $template_data = str_replace("{ipd_no}",'',$template_data);
    }

    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        
        $template_data = str_replace("{booking_date_level}",'IPD Reg. Date:',$template_data);
        $template_data = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date)),$template_data);
    }
    else
    {
         $template_data = str_replace("{booking_date_level}",'',$template_data);
         $template_data = str_replace("{booking_date}",'',$template_data);
    }

    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        $template_data = str_replace("{doctor_name}",'Dr.'.$all_detail['ipd_list'][0]->doctor_name,$template_data);
    }
    else
    {
        $template_data = str_replace("{doctor_name}",'',$template_data);
    }
    
     if(!empty($all_detail['ipd_list'][0]->doctor_spec))
    {
        $spec_name='';
        $specialization = get_specilization_name($all_detail['ipd_list'][0]->doctor_spec);
        if(!empty($specialization))
        {
           $spec_name= str_replace('(Default)','',$specialization);
        }
        
        
        $template_data = str_replace("{specialization}",$spec_name,$template_data);
    }
    else
    {
         
        $template_data = str_replace("{specialization}",'',$template_data);
    }

    if(!empty($all_detail['ipd_list'][0]->mlc))
    {
        
        $template_data = str_replace("{mlc_level}",$all_detail['ipd_list'][0]->mlc,$template_data);
        $template_data = str_replace("{mlc}",$all_detail['ipd_list'][0]->mlc,$template_data);
    }
    else
    {
         $template_data = str_replace("{mlc_level}",'',$template_data);
         $template_data = str_replace("{mlc}",'',$template_data);
         $template_data = str_replace("MLC:",' ',$template_data);
         $template_data = str_replace("MLC :",' ',$template_data);
         $template_data = str_replace("MLC",' ',$template_data);
    }

   
    
    

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        $template_data = str_replace("{room_no}",$all_detail['ipd_list'][0]->room_no,$template_data);

    }
    else
    {
         $template_data = str_replace("Room NO.:",'',$template_data);
         $template_data = str_replace("{room_no}",'',$template_data);
    }
    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
        $template_data = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_name,$template_data);
    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $template_data = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_no,$template_data);
    }
    else
    {
          $template_data = str_replace("Bed NO.:",'',$template_data);
         $template_data = str_replace("{bed_no}",'',$template_data);
    }
    if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        $template_data = str_replace("{room_type}",$all_detail['ipd_list'][0]->room_category,$template_data);
    }
    else
    {
        $template_data = str_replace("{room_type}",'',$template_data);
    }

        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['ipd_list'][0]->gender];
        $age_y = $all_detail['ipd_list'][0]->age_y; 
        $age_m = $all_detail['ipd_list'][0]->age_m;
        $age_d = $all_detail['ipd_list'][0]->age_d;

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

    $template_data = str_replace("{patient_age}",$gender_age,$template_data);
    $pos_start = strpos($template_data, '{start_loop}');
    $pos_end = strpos($template_data, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data,$pos_start+12,$row_last_length-12);
    

    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data);
    }
    else
    {
       $template_data = str_replace("{remarks}",' ',$template_data);
    }
    $signature_reprt_data ='';

   if(!empty($all_detail['signature_data']))
   {
   
     $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 20px;"> 
      <tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;">Signature</b></td>
      </tr>';
      
      if(file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$all_detail['signature_data'][0]->sign_img))
      {
      
      $signature_reprt_data .='<tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$all_detail['signature_data'][0]->sign_img.'"></div></td>
      </tr>';
      
       }
       
      $signature_reprt_data .='<tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.$all_detail['signature_data'][0]->signature.'</b></td>
      </tr>
      
    </table>';

   }

    $template_data = str_replace("{signature}",$signature_reprt_data,$template_data);
$template_data = str_replace("{salesman}",ucfirst($user_detail['user_name']),$template_data);
    echo $template_data; 

//$this->session->unset_userdata('ipd_booking_id'); 
/* end thermal printing */
?>

