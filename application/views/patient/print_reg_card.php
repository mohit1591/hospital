<?php   if(!empty($patient_data['relation']))
        {
            $rel_simulation = get_simulation_name($patient_data['relation_simulation_id']);
            $template_data = str_replace("{parent_relation_type}",$patient_data['relation'],$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_type}",'',$template_data);
        }
$template_data = str_replace("{patient_category_name}",$patient_data['patient_category_name'],$template_data);
 
        if(!empty($patient_data['relation_name']))
        {
        $rel_simulation = get_simulation_name($patient_data['relation_simulation_id']);
        $template_data = str_replace("{relation_name}",$rel_simulation.' '.$patient_data['relation_name'],$template_data);
        }
        else
        {
        $template_data = str_replace("{relation_name}",'',$template_data);
        }


   
    $simulation = get_simulation_name($patient_data['simulation_id']);
    $template_data = str_replace("{patient_name}",$simulation.'  '.$patient_data['patient_name'],$template_data);
    $template_data = str_replace("{patient_reg_no}",$patient_data['patient_code'],$template_data);
    $address = $patient_data['address'];
    $pincode = $patient_data['pincode'];  


        // OPD Fields Starts Here
            $country = $patient_data['country_name'];    
            $state = $patient_data['state_name'];    
            $city = $patient_data['city_name'];
            
            $patient_address = $address.' - '.$pincode;
            $email_address = $patient_data['patient_email'];
            $template_data = str_replace("{email_address}",$email_address,$template_data);

            $template_data = str_replace("{city}",$city,$template_data);
            $template_data = str_replace("{state}",$state,$template_data);
            $template_data = str_replace("{country}",$country,$template_data);
            // adhar no
            $adhar_no=$patient_data['adhar_no'];
            $template_data = str_replace("{adhar_no}", $adhar_no, $template_data);   

            // marital status
            $marital_status=$patient_data['marital_status'];
            $template_data = str_replace("{marital_status}", $marital_status, $template_data);   

            // Religion Name
            $religion_name= ucwords($patient_data['religion_name']);
            $template_data = str_replace("{religion}", $religion_name, $template_data);
            if($patient_data['dob']!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($patient_data['dob']));
                $template_data = str_replace("{dob}", $dob, $template_data);
            }
            else
            {
                $template_data = str_replace("{dob}",'-', $template_data);   
            }


            // father Wife husband name
                $father_husband=ucwords($patient_data['father_husband']);
                $template_data = str_replace( "{father_husband}", $patient_data['f_simulation']." ".$father_husband, $template_data );     
            // father Wife husband name


             // Mother name
                $mother=ucwords($patient_data['mother']);
                $template_data = str_replace( "{mother}", $mother, $template_data );     
            // Mother name

            // Guardian name
                $guardian_name=ucwords($patient_data['guardian_name']);
                $template_data = str_replace( "{guardian_name}", $guardian_name, $template_data );     
            // Guardian name   

            // Guardian Email
                $guardian_email=ucwords($patient_data['guardian_email']);
                $template_data = str_replace( "{guardian_email}", $guardian_email, $template_data );     
            // Guardian Email   

            // Guardian Mobile
                $guardian_phone=ucwords($patient_data['guardian_phone']);
                $template_data = str_replace( "{guardian_phone}", $guardian_phone, $template_data );     
            // Guardian Mobile 

            // Relation
                $relation=ucwords($patient_data['relation']);
                $template_data = str_replace( "{relation}", $relation, $template_data );     
            // Relation

            // Monthly Income
                $monthly_income=number_format($patient_data['monthly_income'],2);
                $template_data = str_replace( "{monthly_income}", $monthly_income, $template_data);     
            // Monthly Income


            // Occupation
                $occupation=$patient_data['occupation'];
                $template_data = str_replace( "{occupation}", $occupation, $template_data);     
            // Occupation

          
            // insurance_company name
            $insurance_company=$patient_data['insurance_company'];
            $template_data = str_replace( "{insurance_company}", $insurance_company, $template_data);     
            // insurance_company name

            // policy number
            $polocy_no=$patient_data['polocy_no'];
            $template_data = str_replace( "{policy_no}", $polocy_no, $template_data);     
            // policy number

            // TPA ID
            $tpa_id=$patient_data['tpa_id'];
            $template_data = str_replace( "{tpa_id}", $tpa_id, $template_data);// TPA ID

            // insurance Amoumnt
            $ins_amount=number_format($patient_data['ins_amount'],2);
            $template_data = str_replace( "{ins_amount}", $ins_amount, $template_data);
            // Insurance Amount


            // insurance Authorization no
            $ins_authorization_no=$patient_data['ins_authorization_no'];
            $template_data = str_replace( "{ins_authorization_no}", $ins_authorization_no, $template_data);
     $template_data = str_replace("{patient_address}",$patient_address,$template_data);
    $template_data = str_replace("{mobile_no}",$patient_data['mobile_no'],$template_data);


    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$patient_data['gender']];
    $age_y = $patient_data['age_y']; 
    $age_m = $patient_data['age_m'];
    $age_d = $patient_data['age_d'];

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
    $gender_age = $patient_age;
    
    $dat = date('d-m-Y',strtotime($patient_data['created_date']));
    $template_data = str_replace("{reg_date}",$dat,$template_data);
    $template_data = str_replace("{pin_code}",$patient_data['pincode'],$template_data);
    
    
    $template_data = str_replace("{state}",$state,$template_data);
    $template_data = str_replace("{age}",$gender_age,$template_data);
    $template_data = str_replace("{gender}",$gender,$template_data);
    echo $template_data; 

 
/* end leaser printing*/
?>

