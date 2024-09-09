<?php 
 $user_detail = $this->session->userdata('auth_users');
$users_data = $this->session->userdata('auth_users');
/* start thermal printing */
//print '<pre>'; print_r($all_detail['ipd_list'][0]);die;
$payment_mode=$all_detail['ipd_list'][0]->payment_mode;
$template_data->template = str_replace("{advance_amount}",'',$template_data->template);
$template_data->template = str_replace("Advance:",'',$template_data->template);

$template_data->template = str_replace("{discount}",'',$template_data->template);
$template_data->template = str_replace("Discount:",'',$template_data->template);

$admission_time=date('H:i A' , strtotime($all_detail['ipd_list'][0]->admission_time));
$template_data->template = str_replace("{booking_time}", $admission_time, $template_data->template);
$template_data->template = str_replace("{reciept_no}",'',$template_data->template);
$template_data->template = str_replace("Receipt No. :",'',$template_data->template); 
$template_data->template = str_replace("{transaction_no}",$all_detail['ipd_list'][0]->transaction_no,$template_data->template);

$template_data->template = str_replace("{diagnosis}",$simulation.''.$all_detail['ipd_list'][0]->diagnosis,$template_data->template);
    $admission_date=date('d-m-Y' , strtotime($all_detail['ipd_list'][0]->admission_date));
    $admission_time=date('H:i A' , strtotime($all_detail['ipd_list'][0]->admission_time));

    if(!empty($time_setting[0]->ipd) && !empty($time_setting[0]->ipd))
    {
        // admission date time
        $template_data->template = str_replace("{admission_date_time}", $admission_date." ".$admission_time, $template_data->template);  
        // admission date time
            
            
        
    }
    else
    {
        
           // admission date time
        $template_data->template = str_replace("{admission_date_time}", $admission_date, $template_data->template);  
        // admission date time
        
           
    }


        

  
    
   
if($template_data->printer_id==2)
{
     if(!empty($all_detail['ipd_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ipd_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

    if(!empty($all_detail['ipd_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ipd_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }

    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.' '.$all_detail['ipd_list'][0]->patient_name,$template_data->template);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    //patient_address = $address.' - '.$pincode;
     // added on 08-Feb-2018
        $country = $all_detail['ipd_list'][0]->country_name;    
        $state = $all_detail['ipd_list'][0]->state_name;    
        $city = $all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
        //$patient_address = $address.' - '.$pincode;

        // adhar no 
        $adhar_no=$all_detail['ipd_list'][0]->adhar_no;
        $template_data->template = str_replace("{adhar_no}",$adhar_no,$template_data->template);  
        // adhar no

        // marital status
        $marital_status=$all_detail['ipd_list'][0]->marital_status;
        $template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
        // marital status

        // Anniversary
        $anniversary=date('Y-m-d', strtotime($all_detail['ipd_list'][0]->anniversary));
        $template_data->template = str_replace("{anniversary}",$anniversary,$template_data->template);
        // Anniversary

        // Religion Name
        $religion_name= ucwords($all_detail['ipd_list'][0]->religion_name);
        $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);    
        // Religion Name

        // DOB starts here
        if($all_detail['ipd_list'][0]->dob!='0000-00-00')
        {
            $dob=date('d-m-Y' ,strtotime($all_detail['ipd_list'][0]->dob));
            $template_data->template = str_replace("{dob}", $dob, $template_data->template);
        }
        else
        {
            $template_data->template = str_replace("{dob}",'-', $template_data->template);   
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
        $template_data->template = str_replace("{relation_name}", $type." ".$relation_name, $template_data->template);    
        // Relation Name

        // Mother Name
        $mother= ucwords($all_detail['ipd_list'][0]->mother);
        $template_data->template = str_replace("{mother}", $mother, $template_data->template);     
        // Mother Name

        // Guardian Name
        $guardian_name= ucwords($all_detail['ipd_list'][0]->guardian_name);
        $template_data->template = str_replace("{guardian_name}", $guardian_name, $template_data->template);     
        // Guardian Name

       // Guardian Email
        $guardian_email= ucwords($all_detail['ipd_list'][0]->guardian_email);
        $template_data->template = str_replace("{guardian_email}", $guardian_email, $template_data->template);  
      // guardian Email 

        // Guardian Phone
        $guardian_phone= ucwords($all_detail['ipd_list'][0]->guardian_phone);
        $template_data->template = str_replace("{guardian_phone}",$guardian_phone, $template_data->template);  
        // guardian Phone 


        // Guardian Relation
        $guardian_relation= ucwords($all_detail['ipd_list'][0]->relation);
        $template_data->template = str_replace("{guardian_relation}",$guardian_relation, $template_data->template);  
        // guardian Relation 


        // Patient email
        $patient_email= ucwords($all_detail['ipd_list'][0]->patient_email);
        $template_data->template = str_replace("{patient_email}", $patient_email, $template_data->template);  
        // patient Email 

        // Monthly Income
        $monthly_income= number_format(ucwords($all_detail['ipd_list'][0]->monthly_income,2));
        $template_data->template = str_replace("{monthly_income}", $monthly_income, $template_data->template);  
        // Monthly Income

        // occupation
        $occupation= ucwords($all_detail['ipd_list'][0]->occupation);
        $template_data->template = str_replace("{occupation}", $occupation, $template_data->template);      
        // occupation

        //insurance_type
        $insurance_type= ucwords($all_detail['ipd_list'][0]->insurance_type);
        $template_data->template = str_replace("{insurance_type}", $insurance_type, $template_data->template);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($all_detail['ipd_list'][0]->insurance_type_name);
        $template_data->template = str_replace("{insurance_type_name}", $insurance_type_name, $template_data->template);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($all_detail['ipd_list'][0]->insurance_company_name);
        $template_data->template = str_replace("{insurance_company_name}", $insurance_company_name, $template_data->template);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($all_detail['ipd_list'][0]->insurance_policy_no);
        $template_data->template = str_replace("{insurance_policy_no}", $insurance_policy_no, $template_data->template);  
        //insurance_policy_no

          //insurance_tpa_id
        $insurance_tpa_id= ucwords($all_detail['ipd_list'][0]->tpa_id);
        $template_data->template = str_replace("{insurance_tpa_id}", $insurance_tpa_id, $template_data->template);  
        //insurance_tpa_id


        //insurance_amount
        $insurance_amount= ucwords($all_detail['ipd_list'][0]->insurance_amount);
        $template_data->template = str_replace("{insurance_amount}", $insurance_amount, $template_data->template);  
        //insurance_amount

        //auth_no
        $auth_no= ucwords($all_detail['ipd_list'][0]->auth_no);
        $template_data->template = str_replace("{auth_no}", $auth_no, $template_data->template);  
        //auth_no


        // Ipd booking fields starts here
        
        // Pacakage Name
        $package_name= ucwords($all_detail['ipd_list'][0]->package_name);
        $template_data->template = str_replace("{package_name}", $package_name, $template_data->template);  

        // Referred By
        $referrer= ucwords($all_detail['ipd_list'][0]->referrered_by);
        $template_data->template = str_replace("{referred_by}", $referrer, $template_data->template);  
        // Referred By

        
        // Referrer Name
        $referrer_name= ucwords($all_detail['ipd_list'][0]->referrer_name);
        $template_data->template = str_replace("{referrer_name}", $referrer_name, $template_data->template);  
        // Referrer Name
    
        // Attented Doctors
        $attented_doctor= ucwords($all_detail['ipd_list'][0]->attented_doctor);
        $template_data->template = str_replace("{attented_doctors}", $attented_doctor, $template_data->template );  
        // Attented Doctors        
    
        // assigned doctors name
        $ipd_assigned_doctors=get_ipd_assigned_doctors_name($all_detail['ipd_list'][0]->id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
        $template_data->template = str_replace("{assigned_doctor}", "Dr. ".$ipd_assigned_doctors , $template_data->template);  
        // assigned doctors name

        // Advance Payment
        $advance_payment= number_format($all_detail['ipd_list'][0]->advance_payment,0);
        $template_data->template = str_replace("{attented_doctors}", $advance_payment, $template_data->template);  
        // Advance Payment

        // Registration Charge
        $reg_charge= number_format($all_detail['ipd_list'][0]->reg_charge,2);
        $template_data->template = str_replace("{reg_charge}", $reg_charge, $template_data->template);  
        // Registration Charge


        
    // Ipd booking fields Ends here
    // added on 08-Feb-2018
    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);
    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $template_data->template = str_replace("{booking_level}",'IPD No. :',$template_data->template);
        $template_data->template = str_replace("{ipd_no}",$all_detail['ipd_list'][0]->ipd_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_level}",'',$template_data->template);
         $template_data->template = str_replace("{ipd_no}",'',$template_data->template);
    }

if(in_array('218',$users_data['permission']['section'])  )
    {
     if($all_detail['ipd_list'][0]->advance_payment>0 && (!empty($all_detail['ipd_list'][0]->reciept_suffix) || !empty($all_detail['ipd_list'][0]->reciept_prefix)))
      {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
      }
       else
      {
         $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        
        $template_data->template = str_replace("{booking_date_level}",'IPD Reg. Date:',$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date)),$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_date_level}",'',$template_data->template);
         $template_data->template = str_replace("{booking_date}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        
        $template_data->template = str_replace("{Consultant_level}",'Assigned Doctor :',$template_data->template);
        $template_data->template = str_replace("{Consultant}",'Dr.'.$all_detail['ipd_list'][0]->doctor_name,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant_level}",'',$template_data->template);
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->mlc_status) && isset($all_detail['ipd_list'][0]->mlc_status))
    {
        
        $template_data->template = str_replace("{mlc_level}",'MLC :',$template_data->template);
        $template_data->template = str_replace("{mlc}",$all_detail['ipd_list'][0]->mlc,$template_data->template);
        $template_data->template = str_replace("{mlc_status}",'Yes',$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{mlc_level}",'',$template_data->template);
         $template_data->template = str_replace("{mlc}",'',$template_data->template);
         $template_data->template = str_replace("MLC:",' ',$template_data->template);
         $template_data->template = str_replace("MLC :",' ',$template_data->template);
         $template_data->template = str_replace("MLC",' ',$template_data->template);
    }

   
    
    /*if(!empty($all_detail['ipd_list'][0]->specialization_id))
    {
        
        $template_data->template = str_replace("{specialization_level}",'Spec. :',$template_data->template);
        $template_data->template = str_replace("{specialization}",get_specilization_name($all_detail['ipd_list'][0]->specialization_id),$template_data->template);
    }
    else
    {*/
         $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    //}
    if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        $room_type = '<br><b>Room Type:</b>'.$all_detail['ipd_list'][0]->room_category;
        $template_data->template = str_replace("{room_type}",$room_type,$template_data->template);
             
        
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
         $template_data->template = str_replace("Room Type:",' ',$template_data->template);
         $template_data->template = str_replace("Room Type :",' ',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        $template_data->template = str_replace("{room_no}",$all_detail['ipd_list'][0]->room_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("Room NO.:",'',$template_data->template);
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
        $template_data->template = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_name,$template_data->template);
    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $template_data->template = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_no,$template_data->template);
    }
    else
    {
          $template_data->template = str_replace("Bed NO.:",'',$template_data->template);
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
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

    //$template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);
    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));

    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

    //////////////////////// 
    $tr_html = "";
    $tr = $row_loop;
    $tr = str_replace("{s_no}",1,$tr);
    $tr = str_replace("{particular}",'Advance Payment',$tr);
    $tr = str_replace("{quantity}","",$tr);
    $tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
    $tr_html .= $tr;
        
    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{total_net}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
   
  $template_data->template = str_replace("{balance}",'0.00',$template_data->template);

     $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

     
    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
    //echo "<pre>";print_r($template_data); exit;

    echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
   if(!empty($all_detail['ipd_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ipd_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

    if(!empty($all_detail['ipd_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ipd_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.' '.$all_detail['ipd_list'][0]->patient_name,$template_data->template);

    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    
    //$patient_address = $address.' - '.$pincode;
     // added on 08-Feb-2018
        $country = $all_detail['ipd_list'][0]->country_name;    
        $state = $all_detail['ipd_list'][0]->state_name;    
        $city = $all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
        //$patient_address = $address.' - '.$pincode;

        // adhar no 
        $adhar_no=$all_detail['ipd_list'][0]->adhar_no;
        $template_data->template = str_replace("{adhar_no}",$adhar_no,$template_data->template);  
        // adhar no

        // marital status
        $marital_status=$all_detail['ipd_list'][0]->marital_status;
        $template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
        // marital status

        // Anniversary
        $anniversary=date('Y-m-d', strtotime($all_detail['ipd_list'][0]->anniversary));
        $template_data->template = str_replace("{anniversary}",$anniversary,$template_data->template);
        // Anniversary

        // Religion Name
        $religion_name= ucwords($all_detail['ipd_list'][0]->religion_name);
        $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);    
        // Religion Name

        // DOB starts here
        if($all_detail['ipd_list'][0]->dob!='0000-00-00')
        {
            $dob=date('d-m-Y' ,strtotime($all_detail['ipd_list'][0]->dob));
            $template_data->template = str_replace("{dob}", $dob, $template_data->template);
        }
        else
        {
            $template_data->template = str_replace("{dob}",'-', $template_data->template);   
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
        $template_data->template = str_replace("{relation_name}", $type." ".$relation_name, $template_data->template);    
        // Relation Name

        // Mother Name
        $mother= ucwords($all_detail['ipd_list'][0]->mother);
        $template_data->template = str_replace("{mother}", $mother, $template_data->template);     
        // Mother Name

        // Guardian Name
        $guardian_name= ucwords($all_detail['ipd_list'][0]->guardian_name);
        $template_data->template = str_replace("{guardian_name}", $guardian_name, $template_data->template);     
        // Guardian Name

       // Guardian Email
        $guardian_email= ucwords($all_detail['ipd_list'][0]->guardian_email);
        $template_data->template = str_replace("{guardian_email}", $guardian_email, $template_data->template);  
      // guardian Email 

        // Guardian Phone
        $guardian_phone= ucwords($all_detail['ipd_list'][0]->guardian_phone);
        $template_data->template = str_replace("{guardian_phone}",$guardian_phone, $template_data->template);  
        // guardian Phone 


        // Guardian Relation
        $guardian_relation= ucwords($all_detail['ipd_list'][0]->relation);
        $template_data->template = str_replace("{guardian_relation}",$guardian_relation, $template_data->template);  
        // guardian Relation 


        // Patient email
        $patient_email= ucwords($all_detail['ipd_list'][0]->patient_email);
        $template_data->template = str_replace("{patient_email}", $patient_email, $template_data->template);  
        // patient Email 

        // Monthly Income
        $monthly_income= number_format(ucwords($all_detail['ipd_list'][0]->monthly_income,2));
        $template_data->template = str_replace("{monthly_income}", $monthly_income, $template_data->template);  
        // Monthly Income

        // occupation
        $occupation= ucwords($all_detail['ipd_list'][0]->occupation);
        $template_data->template = str_replace("{occupation}", $occupation, $template_data->template);      
        // occupation

        //insurance_type
        $insurance_type= ucwords($all_detail['ipd_list'][0]->insurance_type);
        $template_data->template = str_replace("{insurance_type}", $insurance_type, $template_data->template);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($all_detail['ipd_list'][0]->insurance_type_name);
        $template_data->template = str_replace("{insurance_type_name}", $insurance_type_name, $template_data->template);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($all_detail['ipd_list'][0]->insurance_company_name);
        $template_data->template = str_replace("{insurance_company_name}", $insurance_company_name, $template_data->template);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($all_detail['ipd_list'][0]->insurance_policy_no);
        $template_data->template = str_replace("{insurance_policy_no}", $insurance_policy_no, $template_data->template);  
        //insurance_policy_no

          //insurance_tpa_id
        $insurance_tpa_id= ucwords($all_detail['ipd_list'][0]->tpa_id);
        $template_data->template = str_replace("{insurance_tpa_id}", $insurance_tpa_id, $template_data->template);  
        //insurance_tpa_id


        //insurance_amount
        $insurance_amount= ucwords($all_detail['ipd_list'][0]->insurance_amount);
        $template_data->template = str_replace("{insurance_amount}", $insurance_amount, $template_data->template);  
        //insurance_amount

        //auth_no
        $auth_no= ucwords($all_detail['ipd_list'][0]->auth_no);
        $template_data->template = str_replace("{auth_no}", $auth_no, $template_data->template);  
        //auth_no


        // Ipd booking fields starts here
        
        // Pacakage Name
        $package_name= ucwords($all_detail['ipd_list'][0]->package_name);
        $template_data->template = str_replace("{package_name}", $package_name, $template_data->template);  

        // Referred By
        $referrer= ucwords($all_detail['ipd_list'][0]->referrered_by);
        $template_data->template = str_replace("{referred_by}", $referrer, $template_data->template);  
        // Referred By

        
        // Referrer Name
        $referrer_name= ucwords($all_detail['ipd_list'][0]->referrer_name);
        $template_data->template = str_replace("{referrer_name}", $referrer_name, $template_data->template);  
        // Referrer Name
    
        // Attented Doctors
        $attented_doctor= ucwords($all_detail['ipd_list'][0]->attented_doctor);
        $template_data->template = str_replace("{attented_doctors}", $attented_doctor, $template_data->template );  
        // Attented Doctors        
    
        // assigned doctors name
        $ipd_assigned_doctors=get_ipd_assigned_doctors_name($all_detail['ipd_list'][0]->id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
        $template_data->template = str_replace("{assigned_doctor}", "Dr. ".$ipd_assigned_doctors , $template_data->template);  
        // assigned doctors name

        // Advance Payment
        $advance_payment= number_format($all_detail['ipd_list'][0]->advance_payment,0);
        $template_data->template = str_replace("{attented_doctors}", $advance_payment, $template_data->template);  
        // Advance Payment

        // Registration Charge
        $reg_charge= number_format($all_detail['ipd_list'][0]->reg_charge,2);
        $template_data->template = str_replace("{reg_charge}", $reg_charge, $template_data->template);  
        // Registration Charge


        
    // Ipd booking fields Ends here
    // added on 08-Feb-2018

    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data->template);
    
    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $receipt_code = '<br><b>IPD No.:</b>'.$all_detail['ipd_list'][0]->ipd_no.'</b>';
        $template_data->template = str_replace("{ipd_no}",$receipt_code,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        $booking_date = '<br><b>IPD Reg. Date :</b>'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date));
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }
if(in_array('218',$users_data['permission']['section']))
    {
     if($all_detail['ipd_list'][0]->advance_payment>0)
      {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
      }
       else
      {
         $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->created_date))
    {
        $created_date = '<br><b>Receipt Date :</b>'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->created_date));
        $template_data->template = str_replace("{receipt_date}",$created_date,$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        $consultant_new = '<br><b>Assigned Doctor :</b>'.'Dr.'. $all_detail['ipd_list'][0]->doctor_name;
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }


    if(!empty($all_detail['ipd_list'][0]->mlc_status) && isset($all_detail['ipd_list'][0]->mlc_status))
    {
        //$mlc = '<br><b>MLC :</b>'.$all_detail['ipd_list'][0]->doctor_name;
         $template_data->template = str_replace("{mlc}",$mlc,$template_data->template);
         $template_data->template = str_replace("{mlc_status}",'Yes',$mlc,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{mlc}",'',$template_data->template);
        $template_data->template = str_replace("MLC:",' ',$template_data->template);
        $template_data->template = str_replace("MLC :",' ',$template_data->template);
        $template_data->template = str_replace("MLC",' ',$template_data->template);
    }
   /* if(!empty($all_detail['ipd_list'][0]->specialization_id))
    {
        $specialization_new = '<br><b>Spec.</b>'.get_specilization_name($all_detail['ipd_list'][0]->specialization_id);
        $template_data->template = str_replace("{specialization}",$specialization_new,$template_data->template);
    }
    else
    {*/ $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    //}

     if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        
        $room_type = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->room_category;
        $template_data->template = str_replace("{room_type}",$room_type,$template_data->template);
             
        
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        
        $room_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->room_no;
        $template_data->template = str_replace("{room_no}",$room_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }
    
    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
       $bed_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->bad_name;
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);
    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        
        $bed_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->bad_no;
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);

    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);


    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
      		
    	$tr_html = "";
    	$tr = $row_loop;
    	$tr = str_replace("{s_no}",1,$tr);
    	$tr = str_replace("{particular}",'Advance Payment',$tr);
    	$tr = str_replace("{quantity}",'',$tr);
    	$tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
        $tr_html .= $tr;

   

 $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    
    $template_data->template = str_replace("{salesman}",ucfirst($user_detail['user_name']),$template_data->template);

    $template_data->template = str_replace("{total_net}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);

    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
    $template_data->template = str_replace("{balance}",'0.00',$template_data->template);
    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
    echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['ipd_list']);die;
if($template_data->printer_id==1)
{
    if(!empty($all_detail['ipd_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ipd_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

        if(!empty($all_detail['ipd_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ipd_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.' '.$all_detail['ipd_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data->template);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
     

    // added on 08-Feb-2018
        $country = $all_detail['ipd_list'][0]->country_name;    
        $state = $all_detail['ipd_list'][0]->state_name;    
        $city = $all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
        //$patient_address = $address.' - '.$pincode;

        // adhar no 
        $adhar_no=$all_detail['ipd_list'][0]->adhar_no;
        $template_data->template = str_replace("{adhar_no}",$adhar_no,$template_data->template);  
        // adhar no

        // marital status
        $marital_status=$all_detail['ipd_list'][0]->marital_status;
        $template_data->template = str_replace("{marital_status}",$marital_status,$template_data->template);
        // marital status

        // Anniversary
        $anniversary=date('Y-m-d', strtotime($all_detail['ipd_list'][0]->anniversary));
        $template_data->template = str_replace("{anniversary}",$anniversary,$template_data->template);
        // Anniversary

        // Religion Name
        $religion_name= ucwords($all_detail['ipd_list'][0]->religion_name);
        $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);    
        // Religion Name

        // DOB starts here
        if($all_detail['ipd_list'][0]->dob!='0000-00-00')
        {
            $dob=date('d-m-Y' ,strtotime($all_detail['ipd_list'][0]->dob));
            $template_data->template = str_replace("{dob}", $dob, $template_data->template);
        }
        else
        {
            $template_data->template = str_replace("{dob}",'-', $template_data->template);   
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
        $template_data->template = str_replace("{relation_name}", $type." ".$relation_name, $template_data->template);    
        // Relation Name

        // Mother Name
        $mother= ucwords($all_detail['ipd_list'][0]->mother);
        $template_data->template = str_replace("{mother}", $mother, $template_data->template);     
        // Mother Name

        // Guardian Name
        $guardian_name= ucwords($all_detail['ipd_list'][0]->guardian_name);
        $template_data->template = str_replace("{guardian_name}", $guardian_name, $template_data->template);     
        // Guardian Name

       // Guardian Email
        $guardian_email= ucwords($all_detail['ipd_list'][0]->guardian_email);
        $template_data->template = str_replace("{guardian_email}", $guardian_email, $template_data->template);  
      // guardian Email 

        // Guardian Phone
        $guardian_phone= ucwords($all_detail['ipd_list'][0]->guardian_phone);
        $template_data->template = str_replace("{guardian_phone}",$guardian_phone, $template_data->template);  
        // guardian Phone 


        // Guardian Relation
        $guardian_relation= ucwords($all_detail['ipd_list'][0]->relation);
        $template_data->template = str_replace("{guardian_relation}",$guardian_relation, $template_data->template);  
        // guardian Relation 


        // Patient email
        $patient_email= ucwords($all_detail['ipd_list'][0]->patient_email);
        $template_data->template = str_replace("{patient_email}", $patient_email, $template_data->template);  
        // patient Email 

        // Monthly Income
        $monthly_income= number_format(ucwords($all_detail['ipd_list'][0]->monthly_income,2));
        $template_data->template = str_replace("{monthly_income}", $monthly_income, $template_data->template);  
        // Monthly Income

        // occupation
        $occupation= ucwords($all_detail['ipd_list'][0]->occupation);
        $template_data->template = str_replace("{occupation}", $occupation, $template_data->template);      
        // occupation

        //insurance_type
        $insurance_type= ucwords($all_detail['ipd_list'][0]->insurance_type);
        $template_data->template = str_replace("{insurance_type}", $insurance_type, $template_data->template);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($all_detail['ipd_list'][0]->insurance_type_name);
        $template_data->template = str_replace("{insurance_type_name}", $insurance_type_name, $template_data->template);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($all_detail['ipd_list'][0]->insurance_company_name);
        $template_data->template = str_replace("{insurance_company_name}", $insurance_company_name, $template_data->template);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($all_detail['ipd_list'][0]->insurance_policy_no);
        $template_data->template = str_replace("{insurance_policy_no}", $insurance_policy_no, $template_data->template);  
        //insurance_policy_no

          //insurance_tpa_id
        $insurance_tpa_id= ucwords($all_detail['ipd_list'][0]->tpa_id);
        $template_data->template = str_replace("{insurance_tpa_id}", $insurance_tpa_id, $template_data->template);  
        //insurance_tpa_id


        //insurance_amount
        $insurance_amount= ucwords($all_detail['ipd_list'][0]->insurance_amount);
        $template_data->template = str_replace("{insurance_amount}", $insurance_amount, $template_data->template);  
        //insurance_amount

        //auth_no
        $auth_no= ucwords($all_detail['ipd_list'][0]->auth_no);
        $template_data->template = str_replace("{auth_no}", $auth_no, $template_data->template);  
        //auth_no


        // Ipd booking fields starts here
        
        // Pacakage Name
        $package_name= ucwords($all_detail['ipd_list'][0]->package_name);
        $template_data->template = str_replace("{package_name}", $package_name, $template_data->template);  

        // Referred By
        $referrer= ucwords($all_detail['ipd_list'][0]->referrered_by);
        $template_data->template = str_replace("{referred_by}", $referrer, $template_data->template);  
        // Referred By

        
        // Referrer Name
        $referrer_name= ucwords($all_detail['ipd_list'][0]->referrer_name);
        $template_data->template = str_replace("{referrer_name}", $referrer_name, $template_data->template);  
        // Referrer Name
    
        // Attented Doctors
        $attented_doctor= ucwords($all_detail['ipd_list'][0]->attented_doctor);
        $template_data->template = str_replace("{attented_doctors}", $attented_doctor, $template_data->template );  
        // Attented Doctors        
    
        // assigned doctors name
        $ipd_assigned_doctors=get_ipd_assigned_doctors_name($all_detail['ipd_list'][0]->id);
        $ipd_assigned_doctors=$ipd_assigned_doctors[0]->assigned_doctor;
        $ipd_assigned_doctors=explode(',', $ipd_assigned_doctors);
        $ipd_assigned_doctors=implode(', Dr. ', $ipd_assigned_doctors);
        $template_data->template = str_replace("{assigned_doctor}", "Dr. ".$ipd_assigned_doctors , $template_data->template);  
        // assigned doctors name

        // Advance Payment
        $advance_payment= number_format($all_detail['ipd_list'][0]->advance_payment,0);
        $template_data->template = str_replace("{attented_doctors}", $advance_payment, $template_data->template);  
        // Advance Payment

        // Registration Charge
        $reg_charge= number_format($all_detail['ipd_list'][0]->reg_charge,2);
        $template_data->template = str_replace("{reg_charge}", $reg_charge, $template_data->template);  
        // Registration Charge


        
    // Ipd booking fields Ends here
    // added on 08-Feb-2018
    
    if(in_array('218',$users_data['permission']['section']))
    {
     if($all_detail['ipd_list'][0]->advance_payment>0)
      {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
      }
       else
      {
         $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }


    

    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
    $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    if(in_array('218',$users_data['permission']['section']))
    {
     if($all_detail['ipd_list'][0]->advance_payment>0)
      {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
      }
      
       else
      {
         $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        $consultant_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Assigned Doctor :</div>

            <div style="width:60%;line-height:17px;">'.'Dr. '. $all_detail['ipd_list'][0]->doctor_name.'</div>
            </div>';
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);


    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->ipd_no.'</div>
            </div>';
        $template_data->template = str_replace("{ipd_no}",$receipt_code,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD Reg. Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date)).'</div>
            </div>';
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->created_date))
    {
        $receipt_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Receipt Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->created_date)).'</div>
            </div>';
        $template_data->template = str_replace("{receipt_date}",$receipt_date,$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        $room_category = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room Type:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->room_category.'</div>
            </div>';
        $template_data->template = str_replace("{room_type}",$room_category,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        $room_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->room_no.'</div>
            </div>';
        $template_data->template = str_replace("{room_no}",$room_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
      $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->bad_name.'</div>
            </div>';
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);
    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->bad_no.'</div>
            </div>';
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
    }
   if(!empty($all_detail['ipd_list'][0]->mlc_status) && isset($all_detail['ipd_list'][0]->mlc_status))
    {
            $mlc=$all_detail['ipd_list'][0]->mlc;
            $template_data->template = str_replace("{mlc}",$mlc,$template_data->template);
            $template_data->template = str_replace("{mlc_status}",'Yes',$template_data->template);
    }
    else
    {
            $template_data->template = str_replace("{mlc}",'',$template_data->template);
            $template_data->template = str_replace("{mlc_status}",'',$template_data->template);
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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);

    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
    
   	 
    $tr_html = "";
    $tr = $row_loop;
    $tr = str_replace("{s_no}",1,$tr);
    $tr = str_replace("{particular}",'Advance Payment',$tr);
    $tr = str_replace("{quantity}",'',$tr);
    $tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
    $tr_html .= $tr;
        
    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{sales_name}",ucfirst($all_detail['ipd_list'][0]->user_name),$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{balance}",'0.00',$template_data->template);
    $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{remarks}",' ',$template_data->template);
        $template_data->template = str_replace("Remarks:",' ',$template_data->template);
        $template_data->template = str_replace("Remarks :",' ',$template_data->template);
        $template_data->template = str_replace("Remarks",' ',$template_data->template);
    }
    echo $template_data->template; 
}

/* end leaser printing*/
?>

