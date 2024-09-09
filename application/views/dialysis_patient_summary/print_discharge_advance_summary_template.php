<?php 
$users_data = $this->session->userdata('auth_users');
$address_re='';
$del = ','; 
$address_n='';
$simulation = get_simulation_name($all_detail['discharge_list'][0]->simulation_id);
$template_data = str_replace("{patient_name}",$simulation.' '.$all_detail['discharge_list'][0]->patient_name,$template_data);

$address = $all_detail['discharge_list'][0]->address;

$pincode = $all_detail['discharge_list'][0]->pincode;
    

    $admission_date_time='';
    if(!empty($all_detail['discharge_list'][0]->admission_date) && $all_detail['discharge_list'][0]->admission_date!='0000-00-00')
    {
           // if($date_time_status==1)
           // {
                $time = '';
                if(date('h:i:s', strtotime($all_detail['discharge_list'][0]->admission_time))!='12:00:00')
                {
                    $time = date('h:i A', strtotime($all_detail['discharge_list'][0]->admission_time));
                }
                //$admission_time = date('h:i A', strtotime($all_detail['discharge_list'][0]->admission_time));

                $admission_date_time = date('d-m-Y',strtotime($all_detail['discharge_list'][0]->admission_date)).' '.$time;
           
             
    }


$general_examination_vital = '
    <table width="80%" style="font:13px arial;">
    <tr>
        <td width="25%"></td>


        <td width="25%">BP- </td>
        <td width="25%">'.nl2br($all_detail['discharge_list'][0]->vitals_bp).'</td>
        <td width="25%">Pallor- </td>
        <td width="25%">'.nl2br($all_detail['discharge_list'][0]->vitals_pallor).'</td>
    </tr>
    <tr>
       <td></td>


        <td>Pulse- </td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_pulse).'</td>
        <td>Icterus</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_icterus).'</td>
    </tr>
    <tr>
        <td></td>


        <td>R/R- </td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_r_r).'</td>
        <td>Cyanosis</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_cyanosis).'</td>
    </tr>
    <tr>
        <td></td>


        <td>Saturation</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_saturation).'</td>
        <td>Clubbing</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_clubbing).'</td>
    </tr>
    <tr>
         <td></td>


        <td>Temperature</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_temp).'</td>
        <td>Edema</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_edema).'</td>
    </tr>
    <tr>
         <td></td>


        <td>Pupils</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_pupils).'</td>
        <td>Lymphadenopathy</td>
        <td>'.nl2br($all_detail['discharge_list'][0]->vitals_lymphadenopathy).'</td>
    </tr>
</table>';

$systemic_examination_vital='<table width="80%" style="font:13px arial;">
    <tr>
        <td width="25%">Chest</td>
        <td width="25%">B/L CREPTS++</td>
    </tr>
    <tr>
        <td>CVS</td>
        <td>NAD</td>
    </tr>
    <tr>
        <td>CVS</td>
        <td>NAD</td>
    </tr>
    <tr>
        <td>P/A</td>
        <td>EPIGASTRIC TENDERNESS++</td>
    </tr>
</table>';
    // $time = $all_detail['discharge_list'][0]->admission_time;
    $booking_date_time = $admission_date_time;
    
    //$patient_address = $address.' - '.$pincode;
    if($all_detail['discharge_list'][0]->address!='' || $all_detail['discharge_list'][0]->address2!='' || $all_detail['discharge_list'][0]->address3!='')
    {
     $address_n = array_merge(explode ( $del , $all_detail['discharge_list'][0]->address),explode ( $del , $all_detail['discharge_list'][0]->address2),explode ( $del , $all_detail['discharge_list'][0]->address3));
    }
     if(!empty($address_n))
     {
        $address_re = array();
        foreach($address_n as $add_re)
        {
            if(!empty($add_re))
            {
            $address_re[]=$add_re;  
            }

        }
        $patient_address = implode(',',$address_re).' - '.$pincode;
     }
     else
     {
        $patient_address='';
     }
   

    


    $template_data = str_replace("{patient_address}",$patient_address,$template_data);

    $template_data = str_replace("{patient_reg_no}",$all_detail['discharge_list'][0]->patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$all_detail['discharge_list'][0]->mobile_no,$template_data);
    
    $template_data = str_replace("{ipd_no}",$all_detail['discharge_list'][0]->ipd_no,$template_data);

    
    $template_data = str_replace("{admission_date}",$booking_date_time,$template_data);


    $template_data = str_replace("{doctor_name}",$all_detail['discharge_list'][0]->doctor_name,$template_data);

     $template_data = str_replace("{specialization}",$all_detail['discharge_list'][0]->specialization,$template_data);

    $template_data = str_replace("{mlc}",$all_detail['discharge_list'][0]->mlc,$template_data);

    $discharge_date='';
    
    if(!empty($all_detail['discharge_list'][0]->discharge_date) && $all_detail['discharge_list'][0]->discharge_date!='0000-00-00' && $all_detail['discharge_list'][0]->discharge_date!='0000-00-00 00:00:00' )
    {
        //if($date_time_status==1)
        //{
            $discharge_date = date('d-m-Y h:i A',strtotime($all_detail['discharge_list'][0]->discharge_date)); 
        /* }
        else
        {
            $discharge_date = date('d-m-Y',strtotime($all_detail['discharge_list'][0]->discharge_date));  
        } */
    }
    
    /************Medicine Administered*************/
/*if($medicine_administered_list){
    $template_data = str_replace("{medicine_administered_detail}",$medicine_administered_list,$template_data);
}
else{
     $template_data = str_replace("{medicine_administered_detail}",'',$template_data);
}*/
    /************Medicine Administered*************/


/************Medicine *************/
if($medicine_list){
    $template_data = str_replace("{medicine_detail}",$medicine_list,$template_data);
}
else{
     $template_data = str_replace("{medicine_detail}",'',$template_data);
}
    /************Medicine *************/

    /************Investigation Detail *************/
if($test_list!=""){
    $template_data = str_replace("{investigation_list}",$test_list,$template_data);
}
else{
     $template_data = str_replace("{investigation_list}",'',$template_data);
}
    /************Investigation Detail *************/
    
    
    $template_data = str_replace("{discharge_date}",$discharge_date,$template_data);
        
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

    $template_data = str_replace("{patient_age}",$gender_age,$template_data);
    $pos_start = strpos($template_data, '{start_loop}');
    $pos_end = strpos($template_data, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data,$pos_start+12,$row_last_length-12);



    // Replace looping row//
    $h_o_presenting_illness="";
    $on_examination = '';
    $chief_complaints ='';
    $diagnosis ='';
    $vitals='';
    $provisional_diagnosis ='';
    $final_diagnosis ='';
    $course_in_hospital ='';
    $investigation ='';
    $condition_at_discharge_time ='';
    $advise_on_aischarge ='';
    $review_time_and_date = "";
    $vitals_lymphadenopathy='';
    $vitals_edema='';
    $vitals_clubbing='';
    $vitals_cyanosis='';
    $vitals_icterus='';
    $vitals_pallor='';
    $vitals_pupils='';
    $vitals_saturation='';
    $vitals_r_r='';
    $h_o_past='';
    $consultants_name='';
    $personal_history='';
    $vitals_pulse=''; $vitals_chest=''; $vitals_bp='';$vitals_cvs='';$vitals_temp = '';$vitals_cns='';$vitals_p_a = '';
    //echo "<pre>";print_r($all_detail['discharge_list']); exit;
    //[summery_type]
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
    elseif($all_detail['discharge_list'][0]->summery_type=='0')
        { 
            $sumtype = 'Lama';
        }
        
         $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>Summary Type :</b>
               '.$sumtype.'
              </div>
            </div>';
    // provisional_diagnosisfinal_diagnosiscourse_in_hospitalinvestigationcondition_at_discharge_timeadvise_on_dischargereview_time_and_date
    //echo "<pre>"; print_r($all_detail['discharge_list'][0]); exit;
    //echo "<pre>"; print_r($discharge_labels_setting_list); exit;
    //echo "<pre>";print_r($discharge_labels_setting_list); exit;
    foreach ($discharge_labels_setting_list as $value) 
    {   
    //echo "<pre>".$value->setting_name; 

        
    
    if(strcmp(strtolower($value['setting_name']),'diagnosis')=='0')
    {

        if(!empty($value['setting_value'])) { $diagnosis_name =  $value['setting_value']; } else { $diagnosis_name =  $value['var_title']; }

            if(!empty($all_detail['discharge_list'][0]->diagnosis)){ 
            $diagnosis.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$diagnosis_name.' :</b>
               '.nl2br($all_detail['discharge_list'][0]->diagnosis).'
              </div>
            </div>';
            }
    }

    if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
    {

        if(!empty($value['setting_value'])) { $cheif_complaints_name =  $value['setting_value']; } else { $cheif_complaints_name =  $value['var_title']; }

            if(!empty($all_detail['discharge_list'][0]->chief_complaints)){ 
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$cheif_complaints_name.' :</b>
               '.nl2br($all_detail['discharge_list'][0]->chief_complaints).'
              </div>
            </div>';
            }
    }

   



    if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
    {
       
         if(!empty($value['setting_value'])) { $h_o_presenting_name =  $value['setting_value']; } else { $h_o_presenting_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->h_o_presenting)){ 
        $h_o_presenting_illness = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$h_o_presenting_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->h_o_presenting).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'on_examination')=='0')
    {   
        if(!empty($value['setting_value'])) { $on_examination_name =  $value['setting_value']; } else { $on_examination_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->on_examination)){ 
        $on_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;"><b>'.$on_examination_name.':</b>
           '.nl2br($all_detail['discharge_list'][0]->on_examination).'
          </div>
        </div>';
        }
    }
            if(strcmp(strtolower($value['setting_name']),'past_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $past_history_name =  $value['setting_value']; } else { $past_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->past_history)){ 
                $past_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$past_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->past_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'personal_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $personal_history_name =  $value['setting_value']; } else { $personal_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->personal_history)){ 
                $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$personal_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->personal_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'family_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $family_history_name =  $value['setting_value']; } else { $family_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->family_history)){ 
                $family_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$family_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->family_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'birth_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $birth_history_name =  $value['setting_value']; } else { $birth_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->birth_history)){ 
                $birth_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$birth_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->birth_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'menstrual_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $menstrual_history_name =  $value['setting_value']; } else { $menstrual_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->menstrual_history)){ 
                $menstrual_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$menstrual_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->menstrual_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'obstetric_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $obstetric_history_name =  $value['setting_value']; } else { $obstetric_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->obstetric_history)){ 
                $obstetric_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$obstetric_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->obstetric_history).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'general_history')=='0')
            {   
                if(!empty($value['setting_value'])) { $general_history_name =  $value['setting_value']; } else { $general_history_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->general_history)){ 
                $general_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$general_history_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->general_history).'
                  </div>
                </div>'.$general_examination_vital;
                }
            }


             if(strcmp(strtolower($value['setting_name']),'systemic_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $systemic_examination_name =  $value['setting_value']; } else { $systemic_examination_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->systemic_examination)){ 
                $systemic_examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$systemic_examination_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->systemic_examination).'
                  </div>
                </div>'.$systemic_examination_vital;
                }
            }

            if(strcmp(strtolower($value['setting_name']),'local_examination')=='0')
            {   
                if(!empty($value['setting_value'])) { $local_examination_name =  $value['setting_value']; } else { $local_examination_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->local_examination)){ 
                $local_examination = '<div style="float:left;width:100%;margin:1em 0 4em;">
                  <div style="font-size:small;line-height:18px;"><b>'.$local_examination_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->local_examination).'
                  </div>
                </div>';
                }
            }
            

            if(strcmp(strtolower($value['setting_name']),'surgery_preferred')=='0')
            {   
                if(!empty($value['setting_value'])) { $surgery_preferred_name =  $value['setting_value']; } else { $surgery_preferred_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->surgery_preferred)){ 
                $surgery_preferred = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$surgery_preferred_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->surgery_preferred).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'operation_notes')=='0')
            {   
                if(!empty($value['setting_value'])) { $operation_notes_name =  $value['setting_value']; } else { $operation_notes_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->operation_notes)){ 
                $operation_notes = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$operation_notes_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->operation_notes).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'specific_findings')=='0')
            {   
                if(!empty($value['setting_value'])) { $specific_findings_name =  $value['setting_value']; } else { $specific_findings_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->specific_findings)){ 
                $specific_findings = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$specific_findings_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->specific_findings).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
            {   
                if(!empty($value['setting_value'])) { $course_in_hospital_name =  $value['setting_value']; } else { $course_in_hospital_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->course_in_hospital)){ 
                $course_in_hospital = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$course_in_hospital_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->course_in_hospital).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'treatment_given')=='0')
            {   
                if(!empty($value['setting_value'])) { $treatment_given_name =  $value['setting_value']; } else { $treatment_given_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->treatment_given)){ 
                $treatment_given = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$treatment_given_name.':</b>
                   '.nl2br($all_detail['discharge_list'][0]->treatment_given).'
                  </div>
                </div>';
                }
            }

              if(strcmp(strtolower($value['setting_name']),'mlc_fir_no')=='0')
              {
                if(!empty($value['setting_value'])) { $mlc_fir_no_name =  $value['setting_value']; } else { $mlc_fir_no_name =  $value['var_title']; }
               if(!empty($all_detail['discharge_list'][0]->mlc_fir_no)){ 
                $mlc_fir_no = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$mlc_fir_no_name.':</b><br>
                '.nl2br($all_detail['discharge_list'][0]->mlc_fir_no).'
                </div>
                </div>';
                 } 

              }
                  if(strcmp(strtolower($value['setting_name']),'lama_dama_reasons')=='0')
              {
              if(!empty($value['setting_value'])) { $lama_dama_reason =  $value['setting_value']; } else { $lama_dama_reason =  $value['var_title']; }
               if(!empty($all_detail['discharge_list'][0]->lama_dama_reasons)){ 
                $lama_dama_reasons = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$lama_dama_reason.':</b><br>
                '.nl2br($all_detail['discharge_list'][0]->lama_dama_reasons).'
                </div>
                </div>';
                 }

              }
              if(strcmp(strtolower($value['setting_name']),'refered_data')=='0')
              {
                if(!empty($value['setting_value'])) { $refered_data_name =  $value['setting_value']; } else { $refered_data_name =  $value['var_title']; }
               if(!empty($all_detail['discharge_list'][0]->refered_data)){ 
                $refered_data = '<div style="float:left;width:100%;margin:1em 0 0;">
                <div style="font-size:small;line-height:18px;"><b>'.$refered_data_name.':</b><br>
                '.nl2br($all_detail['discharge_list'][0]->refered_data).'
                </div>
                </div>';
                 }

              }

    
    $users_data = $this->session->userdata('auth_users');
    if($users_data['parent_id']=='113')
    {
        //for astha hospital new delhi 
        if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
    {
        if(!empty($value['setting_value'])) { $vitals_name =  $value['setting_value']; } else { $vitals_name =  $value['var_title']; }
       
        $vitals = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$vitals_name.' :</b>';
        $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font-size:small;line-height:18px;" width="100%">
            <tbody>
                <tr>';
            foreach($discharge_vital_setting_list as $discharge_vital_labels)
            {
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pulse')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pulse_name = $discharge_vital_labels['setting_value']; } else { $pulse_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_pulse))
                    {
                    $vitals .='<td align="left" valign="top" width="14%">
                                '.$pulse_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_pulse).'
                                </td>'; 
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'chest')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_chest_name = $discharge_vital_labels['setting_value']; } else { $vitals_chest_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_chest))
                    {
                    $vitals .='<td align="left" valign="top" width="14%">
                                '.$vitals_chest_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_chest).'
                                </td>'; 
                    }
                }


                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'bp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $bp_name = $discharge_vital_labels['setting_value']; } else { $bp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_bp))
                    {
                    
                    $vitals .='
                            <td align="left" valign="top" width="14%">
                           '.$bp_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_bp).'
                            </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cvs')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_cvs_name = $discharge_vital_labels['setting_value']; } else { $vitals_cvs_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_cvs))
                    {
                    
                    $vitals .='<td align="left" valign="top" width="14%">
                                '.$vitals_cvs_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_cvs).'
                                </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'temp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_temp_name = $discharge_vital_labels['setting_value']; } else { $vitals_temp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_temp))
                    {
                    
                    $vitals .='

                    <td align="left" valign="top" width="14%">
                    '.$vitals_temp_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_temp).'
                    </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cns')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cns_name = $discharge_vital_labels['setting_value']; } else { $cns_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_cns))
                    {
                    
                    $vitals .='
                    <td align="left" valign="top" width="14%">
                        '.$cns_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_cns).'
                        </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'p_a')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $p_a_name = $discharge_vital_labels['setting_value']; } else { $p_a_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_p_a))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="14%">
                            '.$p_a_name.': '.nl2br($all_detail['discharge_list'][0]->vitals_p_a).'
                            </td>';
                    
                    }
                }     
            
           

        }


         $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
      
    }

        //end of astha hospital work
    } 
    else
    {
        //for all other branches
         
    if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
    {
        if(!empty($value['setting_value'])) { $vitals_name =  $value['setting_value']; } else { $vitals_name =  $value['var_title']; }
       
        $vitals = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$vitals_name.' :</b>';
        $vitals .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
            <tbody>
                <tr>';
            foreach($discharge_vital_setting_list as $discharge_vital_labels)
            {
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'pulse')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $pulse_name = $discharge_vital_labels['setting_value']; } else { $pulse_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_pulse))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$pulse_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_pulse).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }
                
                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'chest')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_chest_name = $discharge_vital_labels['setting_value']; } else { $vitals_chest_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_chest))
                    {
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_chest_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_chest).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>'; 
                    }
                }


                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'bp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $bp_name = $discharge_vital_labels['setting_value']; } else { $bp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_bp))
                    {
                    
                    $vitals .='
                            <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$bp_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_bp).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cvs')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_cvs_name = $discharge_vital_labels['setting_value']; } else { $vitals_cvs_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_cvs))
                    {
                    
                    $vitals .='<td align="left" valign="top" width="20%">
                                <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                    <tbody>
                                        <tr>
                                            <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_cvs_name.'</b></th>
                                        </tr>
                                        <tr>
                                            <td align="left" valign="top">
                                            <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_cvs).'</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'temp')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $vitals_temp_name = $discharge_vital_labels['setting_value']; } else { $vitals_temp_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_temp))
                    {
                    
                    $vitals .='

                    <td align="left" valign="top" width="20%">
                    <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                        <tbody>
                            <tr>
                                <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals_temp_name.'</b></th>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_temp).'</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </td>';
                    
                    }
                }

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'cns')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $cns_name = $discharge_vital_labels['setting_value']; } else { $cns_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_cns))
                    {
                    
                    $vitals .='
                    <td align="left" valign="top" width="20%">
                        <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                            <tbody>
                                <tr>
                                    <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$cns_name.'</b></th>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">
                                    <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_cns).'</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </td>';
                    
                    }
                } 

                if(strcmp(strtolower($discharge_vital_labels['setting_name']),'p_a')=='0' && $discharge_vital_labels['type'] =='vitals')
                {
                    if(!empty($discharge_vital_labels['setting_value'])) { $p_a_name = $discharge_vital_labels['setting_value']; } else { $p_a_name = $discharge_vital_labels['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_p_a))
                    {
                    
                    $vitals .='
                        <td align="left" valign="top" width="20%">
                            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                                <tbody>
                                    <tr>
                                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$p_a_name.'</b></th>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.nl2br($all_detail['discharge_list'][0]->vitals_p_a).'</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>';
                    
                    }
                }     
            
           

        }


         $vitals .='</tr>
                    </tbody>
                </table></div>
                        </div>';
      
    }
     //end of other branch   
    }
        
    






    if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
    { 
        if(!empty($value['setting_value'])) { $Diagnosis_name =  $value['setting_value']; } else { $Diagnosis_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->provisional_diagnosis)){ 
        $provisional_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Diagnosis_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->provisional_diagnosis).'
          </div>
        </div>';
        }
    }
        



    if(strcmp(strtolower($value['setting_name']),'final_diagnosis')=='0')
    {
        if(!empty($value['setting_value'])) { $final_diagnosis_name =  $value['setting_value']; } else { $final_diagnosis_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->final_diagnosis)){ 
        $final_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b> '.$final_diagnosis_name.' :</b>'.nl2br($all_detail['discharge_list'][0]->final_diagnosis).'
          
          </div>
        </div>';
        }
    }    
    


    if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
    { 
        if(!empty($value['setting_value'])) { $course_in_hospital_name =  $value['setting_value']; } else { $course_in_hospital_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->course_in_hospital)){ 
        $course_in_hospital = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$course_in_hospital_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->course_in_hospital).'
          </div>
        </div>';
        }
    }


    if(strcmp(strtolower($value['setting_name']),'investigation')=='0')
    { 
        if(!empty($value['setting_value'])) { $investigations_name =  $value['setting_value']; } else { $investigations_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->investigations)){ 
        $investigation = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$investigations_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->investigations).'
          </div>
        </div>';
        }
    }



    if(strcmp(strtolower($value['setting_name']),'condition_at_discharge_time')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->discharge_time_condition)){ 
        $condition_at_discharge_time = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->discharge_time_condition).'
          </div>
        </div>';
        }
    }

    if(strcmp(strtolower($value['setting_name']),'advise_on_discharge')=='0')
    { 
        if(!empty($value['setting_value'])) { $discharge_time_condition_name =  $value['setting_value']; } else { $discharge_time_condition_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->discharge_advice)){ 
        $advise_on_aischarge = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$discharge_time_condition_name.' :</b>
           '.nl2br($all_detail['discharge_list'][0]->discharge_advice).'
          </div>
        </div>';
        }
    }
    
   
           if(strcmp(strtolower($value['setting_name']),'review_time_and_date')=='0')
           {
               if(!empty($value['setting_value'])) 
               { 
                    $review_time_date_name =  $value['setting_value']; } else { $review_time_date_name =  $value['var_title']; 
               }
               $time='';
               if(!empty($all_detail['discharge_list'][0]->review_time_date) && $all_detail['discharge_list'][0]->review_time_date!='00:00:00' && $all_detail['discharge_list'][0]->review_time_date!='00:00:00 00:00:00')
               {
                    $time = $all_detail['discharge_list'][0]->review_time;
               }
               if(!empty($all_detail['discharge_list'][0]->review_time) && $all_detail['discharge_list'][0]->review_time!='0000-00-00 00:00:00' && $all_detail['discharge_list'][0]->review_time_date!='1970:01:01 00:00:00' && $all_detail['discharge_list'][0]->review_time_date!='1970-01-01')
               {
                $review_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$review_time_date_name.' :</b>
                    '.date('d-m-Y',strtotime($all_detail['discharge_list'][0]->review_time_date)).'
                  </div>
                </div>';
               }
            }

            //seprated vitals
               
               //echo "<pre>"; print_r($value); 
                if(strcmp(strtolower($value['setting_name']),'pulse')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_pulse)){ 
                    $vitals_pulse.= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$pulse_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_pulse).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'chest')=='0' && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_chest_name =  $value['setting_value']; } else { $vitals_chest_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_chest)){ 
                    $vitals_chest .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_chest_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_chest).'
                      </div>
                    </div>';
                    }
                }
               
                if(strcmp(strtolower($value['setting_name']),'bp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $bp_name =  $value['setting_value']; } else { $bp_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_bp)){ 
                    $vitals_bp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$bp_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_bp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cvs')=='0'  && $value['type'] =='vitals')
                { //echo $all_detail['discharge_list'][0]->vitals_cvs.''. strtolower($value['setting_name']);

                    if(!empty($value['setting_value'])) { $vitals_cvs_name =  $value['setting_value']; } else { $vitals_cvs_name =  $value['var_title']; }

                    if(!empty($all_detail['discharge_list'][0]->vitals_cvs)){  


                    $vitals_cvs .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_cvs_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_cvs).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'temp')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $vitals_temp_name =  $value['setting_value']; } else { $vitals_temp_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_temp)){ 
                    $vitals_temp .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$vitals_temp_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_temp).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'cns')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $cns_name =  $value['setting_value']; } else { $cns_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_cns)){ 
                    $vitals_cns .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$cns_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_cns).'
                      </div>
                    </div>';
                    }
                }
                
                if(strcmp(strtolower($value['setting_name']),'p_a')=='0'  && $value['type'] =='vitals')
                { 
                    if(!empty($value['setting_value'])) { $p_a_name =  $value['setting_value']; } else { $p_a_name =  $value['var_title']; }
                    if(!empty($all_detail['discharge_list'][0]->vitals_p_a)){ 
                    $vitals_p_a .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;">
                        <b>'.$p_a_name.' :</b>
                       '.nl2br($all_detail['discharge_list'][0]->vitals_p_a).'
                      </div>
                    </div>';
                    }
                }

            

            if(strcmp(strtolower($value['setting_name']),'vitals_r_r')=='0'  && $value['type'] =='vitals_r_r')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_r_r)){ 
                $vitals_r_r.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_r_r).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'vitals_saturation')=='0'  && $value['type'] =='vitals_saturation')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_saturation)){ 
                $vitals_saturation.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_saturation).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'vitals_pupils')=='0'  && $value['type'] =='vitals_pupils')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_pupils)){ 
                $vitals_pupils.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_pupils).'
                  </div>
                </div>';
                }
            }

             if(strcmp(strtolower($value['setting_name']),'vitals_pallor')=='0'  && $value['type'] =='vitals_pallor')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_pallor)){ 
                $vitals_pallor.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_pallor).'
                  </div>
                </div>';
                }
            }


             if(strcmp(strtolower($value['setting_name']),'vitals_icterus')=='0'  && $value['type'] =='vitals_icterus')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_icterus)){ 
                $vitals_icterus.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_icterus).'
                  </div>
                </div>';
                }
            }


            if(strcmp(strtolower($value['setting_name']),'vitals_cyanosis')=='0'  && $value['type'] =='vitals_cyanosis')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_cyanosis)){ 
                $vitals_cyanosis.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_cyanosis).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'vitals_clubbing')=='0'  && $value['type'] =='vitals_clubbing')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_clubbing)){ 
                $vitals_clubbing.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_clubbing).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'vitals_edema')=='0'  && $value['type'] =='vitals_edema')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_edema)){ 
                $vitals_edema.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_edema).'
                  </div>
                </div>';
                }
            }

            if(strcmp(strtolower($value['setting_name']),'vitals_lymphadenopathy')=='0'  && $value['type'] =='vitals_lymphadenopathy')
            { 
                if(!empty($value['setting_value'])) { $pulse_name =  $value['setting_value']; } else { $pulse_name =  $value['var_title']; }
                if(!empty($all_detail['discharge_list'][0]->vitals_lymphadenopathy)){ 
                $vitals_lymphadenopathy.= '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$pulse_name.' :</b>
                   '.nl2br($all_detail['discharge_list'][0]->vitals_lymphadenopathy).'
                  </div>
                </div>';
                }
            }

                    
                  



    
    }

        $remarks='';
        if(!empty($all_detail['discharge_list'][0]->remarks)){ 
        $remarks = '<div style="float:left;width:100%;margin:1em 0 4em;">
          <div style="font-size:small;line-height:18px;">
           '.nl2br($all_detail['discharge_list'][0]->remarks).'
          </div>
        </div>';
        }
        
        
       
              $death_time_and_date='';
               $deathtime='';
               if(!empty($all_detail['discharge_list'][0]->death_date) && $all_detail['discharge_list'][0]->death_date!='00:00:00' && $all_detail['discharge_list'][0]->death_date!='00:00:00 00:00:00')
               {
                    $deathtime = $all_detail['discharge_list'][0]->death_time;
               }
               if(!empty($all_detail['discharge_list'][0]->death_time) && $all_detail['discharge_list'][0]->death_time!='0000-00-00 00:00:00' && $all_detail['discharge_list'][0]->death_date!='1970:01:01 00:00:00' && $all_detail['discharge_list'][0]->death_date!='1970-01-01')
               {
                $death_time_and_date .= '<div style="float:left;width:100%;margin:1em 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>Death Date Time :</b>
                    '.date('d-m-Y',strtotime($all_detail['discharge_list'][0]->death_date)).' '.$time.'
                  </div>
                </div>';
               }
            
    
    $template_data = str_replace("{death_date_time}",$death_time_and_date,$template_data);
    
    $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
    $template_data = str_replace("{summary_type}",$sumtype,$template_data);
    $template_data = str_replace("{chief_complaints}",$chief_complaints,$template_data);
    $template_data = str_replace("{h_o_presenting_illness}",$h_o_presenting_illness,$template_data);
    $template_data = str_replace("{past_history}",$past_history,$template_data);

    $template_data = str_replace("{personal_history}",$personal_history,$template_data);

    $template_data = str_replace("{family_history}",$family_history,$template_data);
    $template_data = str_replace("{birth_history}",$birth_history,$template_data);

    $template_data = str_replace("{menstrual_history}",$menstrual_history,$template_data);


    $template_data = str_replace("{obstetric_history}",$obstetric_history,$template_data);
    $template_data = str_replace("{general_examination}",$general_examination,$template_data);
    $template_data = str_replace("{systemic_examination}",$systemic_examination,$template_data);
    $template_data = str_replace("{local_examination}",$local_examination,$template_data);
    $template_data = str_replace("{investigation}",$investigation,$template_data);

    $template_data = str_replace("{surgery_preferred}",$surgery_preferred,$template_data);

    $template_data = str_replace("{operation_notes}",$operation_notes,$template_data);

    $template_data = str_replace("{specific_findings}",$specific_findings,$template_data);

    $template_data = str_replace("{course_in_hospital}",$course_in_hospital,$template_data);

    $template_data = str_replace("{treatment_given}",$treatment_given,$template_data);

    $template_data = str_replace("{condition_at_discharge_time}",$condition_at_discharge_time,$template_data);

    $template_data = str_replace("{advise_on_discharge}",$advise_on_aischarge,$template_data);

    $template_data = str_replace("{vitals_r_r}",$vitals_r_r,$template_data);
    $template_data = str_replace("{vitals_saturation}",$vitals_saturation,$template_data);
    $template_data = str_replace("{vitals_pupils}",$vitals_pupils,$template_data);
    $template_data = str_replace("{vitals_pallor}",$vitals_pallor,$template_data);
    $template_data = str_replace("{vitals_icterus}",$vitals_icterus,$template_data);
    $template_data = str_replace("{vitals_cyanosis}",$vitals_cyanosis,$template_data);
    $template_data = str_replace("{vitals_clubbing}",$vitals_clubbing,$template_data);
    $template_data = str_replace("{vitals_edema}",$vitals_edema,$template_data);
    $template_data = str_replace("{vitals_lymphadenopathy}",$vitals_lymphadenopathy,$template_data);

    $template_data = str_replace("{remarks}",$remarks,$template_data);

    //astha hosp

    


    $template_data = str_replace("{on_examination}",$on_examination,$template_data);
    $template_data = str_replace("{vitals}",$vitals,$template_data);
    $template_data = str_replace("{provisional_diagnosis}",$provisional_diagnosis,$template_data);
    $template_data = str_replace("{final_diagnosis}",$final_diagnosis,$template_data);
    
    
    
    
    $template_data = str_replace("{review_time_and_date}",$review_time_and_date,$template_data);
    
    
    
    $template_data = str_replace("{h_o_past}",$h_o_past,$template_data);
   
     
  
    $template_data = str_replace("{consultants_name}",$consultants_name,$template_data);
    
    $template_data = str_replace("{vitals_pulse}",$vitals_pulse,$template_data);
    $template_data = str_replace("{vitals_chest}",$vitals_chest,$template_data);
    $template_data = str_replace("{vitals_bp}",$vitals_bp,$template_data);
    $template_data = str_replace("{vitals_cvs}",$vitals_cvs,$template_data);
    $template_data = str_replace("{vitals_temp}",$vitals_temp,$template_data);
    $template_data = str_replace("{vitals_cns}",$vitals_cns,$template_data);
    $template_data = str_replace("{vitals_p_a}",$vitals_p_a,$template_data);

    $data_discharge='';
    if(in_array('222',$users_data['permission']['section']))
    {
        if(!empty($field_name))
        { 
            
            foreach ($field_name as $field_names) 
            {
               //echo $tot_values[4];
                $tot_values= explode('__',$field_names);
                if(!empty($tot_values[0]))
                {
                     $data_discharge.= '<div style="float:left;width:100%;margin:1em 0 0;">
                          <div style="font-size:small;line-height:18px;">
                            <b>'.ucfirst($tot_values[1]).' :</b>
                           '.nl2br($tot_values[0]).'
                          </div>
                        </div>';
                    //$template_data = str_replace($tot_values[4],$data_discharge,$template_data);
                    
                   // echo $template_data; exit;
                }
                else
                {
                  //$template_data = str_replace($tot_values[4],'',$template_data);  
                }
                        

            } 
        }
    } 
    //print_r($template_data);  exit;
   
    
    $template_data = str_replace("{medical_history}",'',$template_data);
    $template_data = str_replace("{surgical_history}",'',$template_data);
    $template_data = str_replace("{family_history}",'',$template_data);
    $template_data = str_replace("{complain_duration}",'',$template_data);
    $template_data = str_replace("{sum_report}",'',$template_data);
   
    
    
    $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 20px;"> 
      <tr>
        <td align="left"><div>&nbsp;</div>
        </td><td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;">'.$all_detail['discharge_list'][0]->doctor_name.'</b></td>
      </tr>';
      
      
      
     
      $signature_reprt_data .='</table>';
    
    //echo $signature_reprt_data; die;
    $template_data = str_replace("{signature}",$data_discharge. $signature_reprt_data,$template_data);
    
    
   // $template_data = str_replace("{signature}",$data_discharge.$signature,$template_data);
    echo $template_data;  //exit;
    $this->session->unset_userdata('discharge_summary_id');

/* end thermal printing */
?>

<?php
if(!empty($download_type) && $download_type==2)
{
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>html2canvas.js"></script>
<script>
 //$(function(){
  //$("#gimg").click(function(){

    //$('#gimg').hide();
$(document).ready(function() { 
     html2canvas($("page"), {
        onrendered: function(canvas) {
           var imgsrc = canvas.toDataURL("image/png");
            $.ajax({
                url:'<?php echo base_url('ipd_patient_discharge_summary/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['discharge_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['discharge_list'][0]->patient_code; ?>"
                    },
                success: function(result)
                {
                    //alert(result); return 1;
                     location.href =result;
                //var dt = canvas.toDataURL();
               // this.href = dt; //this may not work in the future..
 

                    //var opened = view.open(object_url, "_blank");
                    //view.location.href = object_url;
                    //var dataURL = $canvas[0].toDataURL('image/png');
                    //w.document.write("<img src='" + dataURL + "' alt='from canvas'/>");
                }

                
            });
           
        }
     });
  });   
  //});  
  //});
</script>
<?php } ?>

