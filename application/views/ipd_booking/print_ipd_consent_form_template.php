<?php 
  //echo "<pre>";print_r($all_detail);die;
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.''.$all_detail['ipd_list'][0]->patient_name,$template_data);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    //$patient_address = $address.' - '.$pincode;

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
        $template_data = str_replace("{relation_name}", $relation_name, $template_data);    
        // Relation Name

    
        // Advance Payment
        $advance_payment= number_format($all_detail['ipd_list'][0]->advance_payment,0);
        $template_data = str_replace("{advance_amt}", $advance_payment, $template_data);  
        // Advance Payment

        
    $template_data = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data);
   
    
    if(!empty($all_detail['ipd_list'][0]->attented_doctor))
    {
        $template_data = str_replace("{doctor_name}",'Dr.'.$all_detail['ipd_list'][0]->attented_doctor,$template_data);
    }
    else
    {
        $template_data = str_replace("{doctor_name}",'',$template_data);
    }
    $template_data = str_replace("{ipd_no}",$all_detail['ipd_list'][0]->ipd_no,$template_data);

    if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $template_data = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_no,$template_data);
    }
    else
    {
          $template_data = str_replace("Bed NO.:",'',$template_data);
         $template_data = str_replace("{bed_no}",'',$template_data);
    }
    if(!empty($all_detail['ipd_list'][0]->mlc))
    {
        $template_data = str_replace("{mlc}",$all_detail['ipd_list'][0]->mlc,$template_data);
    }
    else{
         $template_data = str_replace("{mlc}",'',$template_data);
    }

        $country = $all_detail['ipd_list'][0]->country_name;    
        $state = $all_detail['ipd_list'][0]->state_name;    
        $city = $all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;



    $template_data = str_replace("{patient_address}",$patient_address,$template_data);
    $template_data= str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data);

     $admission_date=date('d-m-Y' , strtotime($all_detail['ipd_list'][0]->admission_date));
    $admission_time='';
    if(date('h:i:s' , strtotime($all_detail['ipd_list'][0]->admission_time))!='12:00:00' && $all_detail['ipd_list'][0]->admission_time!='00:00:00')
    {
        $admission_time=date('h:i A' , strtotime($all_detail['ipd_list'][0]->admission_time));
    }
        

    $template_data = str_replace("{admission_date}", $admission_date, $template_data); 
     $template_data = str_replace("{admission_time}", $admission_time, $template_data);


    if($all_detail['ipd_list'][0]->discharge_date !='0000-00-00 00:00:00')
    {
       $discharge_date=date('d-m-Y' , strtotime($all_detail['ipd_list'][0]->discharge_date));
    }
    else{
        $discharge_date='';
    }
    

    $discharge_time='';
    if(date('h:i:s' , strtotime($all_detail['ipd_list'][0]->discharge_date))!='12:00:00' && $all_detail['ipd_list'][0]->discharge_date!='00:00:00')
    {
        $discharge_time=date('h:i A' , strtotime($all_detail['ipd_list'][0]->discharge_date));
    }

     $template_data = str_replace("{discharge_date}", $discharge_date, $template_data); 
     $template_data = str_replace("{discharge_time}", $discharge_time, $template_data); 


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

    $template_data = str_replace("{age}",$age,$template_data);
  
    $template_data = str_replace("{gender}",$gender,$template_data);

    $template_data = str_replace("{country}",$country,$template_data);

    echo $template_data; 

//$this->session->unset_userdata('ipd_booking_id'); 
/* end thermal printing */
?>

