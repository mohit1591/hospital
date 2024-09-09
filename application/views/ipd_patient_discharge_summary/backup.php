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
            /*}
            else
            {
                $admission_date_time = date('d-m-Y',strtotime($all_detail['discharge_list'][0]->admission_date)); 
            }*/
             
    }
    // $time = $all_detail['discharge_list'][0]->admission_time;
    $booking_date_time = $admission_date_time;
    
    //$patient_address = $address.' - '.$pincode;
    if($all_detail['discharge_list'][0]->address!='' || $all_detail['discharge_list'][0]->address2!='' || $all_detail['discharge_list'][0]->address3!='')
    {
     $address_n = array_merge(explode ( $del , $all_detail['discharge_list'][0]->address),explode ( $del , $all_detail['discharge_list'][0]->address2),explode ( $del , $all_detail['discharge_list'][0]->address3));
    }
     if(!empty($address_n))
     {
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
    $vitals='';
    $provisional_diagnosis ='';
    $final_diagnosis ='';
    $course_in_hospital ='';
    $investigation ='';
    $condition_at_discharge_time ='';
    $advise_on_aischarge ='';
    $review_time_and_date = "";
    $vitals_pulse=''; $vitals_chest=''; $vitals_bp='';$vitals_cvs='';$vitals_temp = '';$vitals_cns='';$vitals_p_a = '';
    //echo "<pre>";print_r($all_detail['discharge_list']); exit;
    //[summery_type]
    if($all_detail['discharge_list'][0]->summery_type)
    {
        if($all_detail['discharge_list'][0]->summery_type=='0')
        { 
            $sumtype = 'LAMA';
        }
        elseif($all_detail['discharge_list'][0]->summery_type=='1')
        { 
            $sumtype = 'REFERRAL';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='2')
        { 
            $sumtype = 'DISCHARGE';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='3')
        { 
            $sumtype = 'D.O.P.R';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='4')
        { 
            $sumtype = 'NORMAL';
        }
         elseif($all_detail['discharge_list'][0]->summery_type=='5')
        { 
            $sumtype = 'Expire';
        }   
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>Summary Type :</b>
               '.$sumtype.'
              </div>
            </div>';
            
    }
    // provisional_diagnosisfinal_diagnosiscourse_in_hospitalinvestigationcondition_at_discharge_timeadvise_on_dischargereview_time_and_date
    //echo "<pre>"; print_r($all_detail['discharge_list'][0]); exit;
    //echo "<pre>"; print_r($discharge_labels_setting_list); exit;
    //echo "<pre>";print_r($discharge_labels_setting_list); exit;
    foreach ($discharge_labels_setting_list as $value) 
    {   
    //echo "<pre>".$value->setting_name;     
    if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
    {

        if(!empty($value['setting_value'])) { $cheif_complaints_name =  $value['setting_value']; } else { $cheif_complaints_name =  $value['var_title']; }

            if(!empty($all_detail['discharge_list'][0]->chief_complaints)){ 
            $chief_complaints.= '<div style="float:left;width:100%;margin:1em 0 0;">
              <div style="font-size:small;line-height:18px;"><b>'.$cheif_complaints_name.' :</b>
               '.$all_detail['discharge_list'][0]->chief_complaints.'
              </div>
            </div>';
            }
    }

   



    if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
    {
       
         if(!empty($value['setting_value'])) { $Personal_history =  $value['setting_value']; } else { $Personal_history =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->h_o_presenting)){ 
        $h_o_presenting_illness = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Personal_history.' :</b>
           '.$all_detail['discharge_list'][0]->h_o_presenting.'
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
           '.$all_detail['discharge_list'][0]->on_examination.'
          </div>
        </div>';
        }
    }

    

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
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_pulse.'</div>
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
                                            <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_chest.'</div>
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
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_bp.'</div>
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
                                            <div style="float:left;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_cvs.'</div>
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
                                <div style="float:left;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_temp.'</div>
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
                                    <div style="float:left;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_cns.'</div>
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
                                        <div style="float:left;text-align: left;padding-left: 4px;">'.$all_detail['discharge_list'][0]->vitals_p_a.'</div>
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
        
    






    if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
    { 
        if(!empty($value['setting_value'])) { $Diagnosis_name =  $value['setting_value']; } else { $Diagnosis_name =  $value['var_title']; }
        if(!empty($all_detail['discharge_list'][0]->provisional_diagnosis)){ 
        $provisional_diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Diagnosis_name.' :</b>
           '.$all_detail['discharge_list'][0]->provisional_diagnosis.'
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
            <b> '.$final_diagnosis_name.' :</b>'.$all_detail['discharge_list'][0]->final_diagnosis.'
          
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
           '.$all_detail['discharge_list'][0]->course_in_hospital.'
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
           '.$all_detail['discharge_list'][0]->investigations.'
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
           '.$all_detail['discharge_list'][0]->discharge_time_condition.'
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
           '.$all_detail['discharge_list'][0]->discharge_advice.'
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
            '.date('d-m-Y',strtotime($all_detail['discharge_list'][0]->review_time_date)).' '.$time.'
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
                       '.$all_detail['discharge_list'][0]->vitals_pulse.'
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
                       '.$all_detail['discharge_list'][0]->vitals_chest.'
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
                       '.$all_detail['discharge_list'][0]->vitals_bp.'
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
                       '.$all_detail['discharge_list'][0]->vitals_cvs.'
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
                       '.$all_detail['discharge_list'][0]->vitals_temp.'
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
                       '.$all_detail['discharge_list'][0]->vitals_cns.'
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
                       '.$all_detail['discharge_list'][0]->vitals_p_a.'
                      </div>
                    </div>';
                    }
                }

               
           
/* 
 [vitals_pulse] => 10yy
    [vitals_chest] => 20yy
    [vitals_bp] => 30yy
    [vitals_cvs] => 40yy
    [vitals_temp] => 50yy
    [vitals_cns] => 60yy
    [vitals_f] => 
    [vitals_p_a] => 70yy
*/
        
//seprated vitals





    
    }
    
    
    $template_data = str_replace("{chief_complaints}",$chief_complaints,$template_data);
    $template_data = str_replace("{h_o_presenting_illness}",$h_o_presenting_illness,$template_data);
    $template_data = str_replace("{on_examination}",$on_examination,$template_data);
    $template_data = str_replace("{vitals}",$vitals,$template_data);
    $template_data = str_replace("{provisional_diagnosis}",$provisional_diagnosis,$template_data);
    $template_data = str_replace("{final_diagnosis}",$final_diagnosis,$template_data);
    $template_data = str_replace("{course_in_hospital}",$course_in_hospital,$template_data);
    $template_data = str_replace("{investigation}",$investigation,$template_data);
    $template_data = str_replace("{condition_at_discharge_time}",$condition_at_discharge_time,$template_data);
    $template_data = str_replace("{advise_on_aischarge}",$advise_on_aischarge,$template_data);
    $template_data = str_replace("{review_time_and_date}",$review_time_and_date,$template_data);


    $template_data = str_replace("{vitals_pulse}",$vitals_pulse,$template_data);
    $template_data = str_replace("{vitals_chest}",$vitals_chest,$template_data);
    $template_data = str_replace("{vitals_bp}",$vitals_bp,$template_data);
    $template_data = str_replace("{vitals_cvs}",$vitals_cvs,$template_data);
    $template_data = str_replace("{vitals_temp}",$vitals_temp,$template_data);
    $template_data = str_replace("{vitals_cns}",$vitals_cns,$template_data);
    $template_data = str_replace("{vitals_p_a}",$vitals_p_a,$template_data);

    if(in_array('222',$users_data['permission']['section']))
    {
        if(!empty($field_name))
        { 
            $data_discharge='';
            foreach ($field_name as $field_names) 
            {
               // echo $tot_values[4];
                $tot_values= explode('__',$field_names);
                if(!empty($tot_values[0]))
                {
                    $data_discharge = '<div style="float:left;width:100%;margin:1em 0 0;">
                          <div style="font-size:small;line-height:18px;">
                            <b>'.ucfirst($tot_values[1]).' :</b>
                           '.$tot_values[0].'
                          </div>
                        </div>';
                    $template_data = str_replace($tot_values[4],$data_discharge,$template_data);
                }
                else
                {
                  $template_data = str_replace($tot_values[4],'',$template_data);  
                }
                        

            } 
        }
    } 
    //print_r($template_data);  exit;
    
    $template_data = str_replace("{signature}",$signature,$template_data);
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

