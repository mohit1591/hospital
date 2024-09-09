<style type="text/css">

*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
    page {
      background: white;
      display: block;
      margin: 1em auto 0;
      margin-bottom: 0.5cm;
    }
    page[size="A4"] {  
                    
            padding: 3em;
            font-size: 13px;
            float: left;
    }
      @page {
    size: auto;   
    margin: 0;  
}
.printPage {float:left;width:100%;padding:5px;}
.printPage > .box {float:left;width:50%;min-height:252px;vertical-align:middle;text-align:left;font:12px arial !important;}
.printPage > .box td {font:13px arial;}
.printPage > .box .frame {float:left;width:100px;height:100px;border:1px solid #999;font:13px arial !important;}
.printPage > .box .ri8Frame {float:left;width:100%;min-height:100px;border:1px solid #999;margin-bottom:5px;font:14px arial;}
</style>
<?php 
/* start thermal printing */


    $del = ','; 
    $address_n='';
    $address_re=array();
    $simulation = get_simulation_name($all_detail['prescription_list'][0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.' '.$all_detail['prescription_list'][0]->patient_name,$template_data);

    $address = $all_detail['prescription_list'][0]->address;
    $pincode = $all_detail['prescription_list'][0]->pincode;
    $booking_date_time='';
    if(!empty($all_detail['prescription_list'][0]->booking_date) && $all_detail['prescription_list'][0]->booking_date!='0000-00-00')
{
    $booking_date_time = date('d-m-Y',strtotime($all_detail['prescription_list'][0]->booking_date)); 
}
       //.' '.$all_detail['prescription_list'][0]->booking_time  

    if($all_detail['prescription_list'][0]->address!='' || $all_detail['prescription_list'][0]->address2!='' || $all_detail['prescription_list'][0]->address3!='')
    {
    $address_n = array_merge(explode ( $del , $all_detail['prescription_list'][0]->address),explode ( $del , $all_detail['prescription_list'][0]->address2),explode ( $del , $all_detail['prescription_list'][0]->address3));
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
        $patient_address = implode(',',$address_re).' - '.$all_detail['prescription_list'][0]->pincode;
     }
     else
     {
        $patient_address='';
     }


    
    //$patient_address = $address.' - '.$pincode;

    $template_data = str_replace("{patient_address}",$patient_address,$template_data);

    $template_data = str_replace("{patient_reg_no}",$all_detail['prescription_list'][0]->patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$all_detail['prescription_list'][0]->mobile_no,$template_data);
    
    $template_data = str_replace("{booking_code}",$all_detail['prescription_list'][0]->booking_code,$template_data);
    $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
    $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($all_detail['prescription_list'][0]->attended_doctor),$template_data);

    $template_data = str_replace("{ref_doctor_name}",get_doctor_name($all_detail['prescription_list'][0]->referral_doctor),$template_data);

$spec_name='';
        $specialization = get_specilization_name($all_detail['prescription_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }


    $template_data = str_replace("{specialization}",$spec_name,$template_data);

                      
    /*$template_data = str_replace("{patient_bp}",$all_detail['prescription_list'][0]->patientbp,$template_data);

    $template_data = str_replace("{patient_temp}",$all_detail['prescription_list'][0]->patienttemp,$template_data);

    $template_data = str_replace("{patient_weight}",$all_detail['prescription_list'][0]->patientweight,$template_data);

    $template_data = str_replace("{patient_pr}",$all_detail['prescription_list'][0]->patientpr,$template_data);

    $template_data = str_replace("{patient_spo}",$all_detail['prescription_list'][0]->patientspo,$template_data);
    $template_data = str_replace("{patient_rbs}",$all_detail['prescription_list'][0]->patientrbs,$template_data);*/


    if(!empty($vitals_list))
    {
        foreach ($vitals_list as $vitals) 
        {
            $vitals_val = get_vitals_value($vitals->id,$prescription_id,$type);
            $template_data = str_replace("{".$vitals->short_code."}",$vitals_val,$template_data);
        }
    }    
    //echo $template_data;
    $patient_vital = '';

    $patient_vital .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
    <tbody>
        <tr>';

        if(!empty($vitals_list))
        {
          $i=0;
          foreach ($vitals_list as $vitals) 
          {
            $vital_val = get_vitals_value($vitals->id,$prescription_id,$type);

            $patient_vital .= '<td align="left" valign="top" width="20%">
            <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                <tbody>
                    <tr>
                        <th align="center" style="border-bottom:1px solid black;padding-left: 4px;" valign="top" width="50%"><b>'.$vitals->vitals_name.'</b></th>
                    </tr>
                    <tr>
                        <td align="left" valign="top">
                        <div style="float:left;min-height:20px;text-align: left;padding-left: 4px;">'.$vital_val.'</div>
                        </td>
                    </tr>
                </tbody>
            </table>
            </td>';
            $i++;
            
          }
        }

            
       $patient_vital .= '</tr>
    </tbody>
</table>';

 $template_data = str_replace("{vitals}",'',$template_data);
    //echo $template_data; die;

    	$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['prescription_list'][0]->gender];
        $age_y = $all_detail['prescription_list'][0]->age_y; 
        $age_m = $all_detail['prescription_list'][0]->age_m;
        $age_d = $all_detail['prescription_list'][0]->age_d;

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
    $chief_complaint="";
    $diagnosis = '';
    $previous_history ='';
    $allergy ='';
    $oral_habits ='';
    $treatment ='';
    $advice_booking ='';
    $next_appointment ='';
    $next_app = "";
    $patient_pres='';
    foreach ($prescription_tab_setting as $tab_value) 
    {   


  //print_r($value);
/* biometric test  */
////////////
 if(strcmp(strtolower($tab_value->setting_name),'chief_complaint')=='0')
    {   
      $chief_complaint='';
      
        if(!empty($all_detail['prescription_list']['chief_complaint']))
        {
            if(!empty($tab_value->setting_value)) { $chief_complaint_name =  $tab_value->setting_value; } else { $chief_complaint_name =  $tab_value->var_title; }
         $chief_complaint ='<div style="font-size:small;line-height:18px;"><b>'.$chief_complaint_name.':</b>
       
        </div>';
         $chief_complaint.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <thead><tr>
            <th align="left" height="30">Complaint Name</th>
            <th align="center" height="30">Teeth Type</th>
            <th align="center" height="30">Tooth No.</th>
            <th align="center" height="30">Reason</th>
            <th align="center" height="30" colspan="2">Duration</th>
        </tr></thead>';
      
       foreach($all_detail['prescription_list']['chief_complaint'] as $chief_complaint_li)
       {
        $chief_complaint.='<tr>
            <td align="left" height="30">'.$chief_complaint_li->chief_complaints.'</td>';
           
            $chief_complaint.='<td align="center" height="30">'.$chief_complaint_li->teeth_name.'</td>';
          if($chief_complaint_li->duration_time==1)
            {
                $time_var= 'Days';
            }
            
            if($chief_complaint_li->duration_time==2)
            {
                 $time_var='Week';
            }
            
            if($chief_complaint_li->duration_time==3)
            {
                 $time_var='Month';
            }
           
             if($chief_complaint_li->duration_time==4)
            {
                 $time_var='Year';
            }
              $time_var='';
            $chief_complaint.='<td align="center" height="30">'.$chief_complaint_li->tooth_number_id.'</td>';
             $chief_complaint.='<td align="center" height="30">'.$chief_complaint_li->reason.'</td>';
             $chief_complaint.='<td align="center" height="30">'.$chief_complaint_li->duration_number.' '.$chief_complaint_li->duration_time;
               
            $chief_complaint.='</td>';
            
        $chief_complaint.='</tr>';
       }
        
       
     $chief_complaint.='</table>
</div>';

    }
  
}
"<br>";
 if(strcmp(strtolower($tab_value->setting_name),'previous_history')=='0')
    {   
      $previous_history='';
      
        if(!empty($all_detail['prescription_list']['previous_history']))
        {
            if(!empty($tab_value->setting_value)) { $previous_history_name =  $tab_value->setting_value; } else { $previous_history_name =  $tab_value->var_title; }
         $previous_history ='<div style="font-size:small;line-height:18px;"><b>'.$previous_history_name.':</b>
       
        </div>';
         $previous_history.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Disease Name</td>
            <td align="center" height="30">Disease Details</td>
            <td align="center" height="30">Operation</td> 
              <td align="center" height="30">Operation Date</td>       
        </tr>';
        
        
       foreach($all_detail['prescription_list']['previous_history'] as $previous_history_li)
       {
           $date_var='';
        if(!empty($previous_history_li->operation_date) && $previous_history_li->operation_date!='0000-00-00 00:00:00' && $previous_history_li->operation_date!='1970-01-01' && date('d-m-Y',strtotime($previous_history_li->operation_date))!='01-01-1970')
        {
          $date_var=date('d-m-Y',strtotime($previous_history_li->operation_date));
        }
        $previous_history.='<tr>
            <td align="left" height="30">'.$previous_history_li->disease_name.'</td>';
           
            $previous_history.='<td align="center" height="30">'.$previous_history_li->disease_details.'</td>';
          

            $previous_history.='<td align="center" height="30">'.$previous_history_li->operation.'</td>';
            $previous_history.='<td align="center" height="30">'.$date_var.'</td>';

             
        $previous_history.='</tr>';
       }
        
       
     $previous_history.='</table>
</div>';

    }
   
}

if(strcmp(strtolower($tab_value->setting_name),'allergy')=='0')
    {   
      $allergy='';
     
        //print_r($all_detail['prescription_list']['allergy']);
        if(!empty($all_detail['prescription_list']['allergy']))
        {
            if(!empty($tab_value->setting_value)) { $allergy_name =  $tab_value->setting_value; } else { $allergy_name =  $tab_value->var_title; }
         $allergy ='<div style="font-size:small;line-height:18px;"><b>'.$allergy_name.':</b>
       
        </div>';
         $allergy.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
       <tr>
            <td align="left" height="30">Allergy Name</td>
            <td align="center" height="30">Reason</td>
            <td align="center" height="30" colspan="2">Duration</td>
        </tr>';
        $num_var='';
       
        
       foreach($all_detail['prescription_list']['allergy'] as $allergy_li)
       {
        $allergy.='<tr>
            <td align="left" height="30">'.$allergy_li->allergy_name.'</td>';
           
            $allergy.='<td align="center" height="30">'.$allergy_li->reason.'</td>';
        
             $allergy.='<td align="center" height="30">'.$allergy_li->number.' '.$allergy_li->time.'</td>';
                          

             
        $allergy.='</tr>';
       }
        
       
     $allergy.='</table>
</div>';

    }
    
}

if(strcmp(strtolower($tab_value->setting_name),'oral_habits')=='0')
    {  
      $oral_habits='';
     
        
        if(!empty($all_detail['prescription_list']['oral_habit_booking']))
        {
            if(!empty($tab_value->setting_value)) { $oral_habit_name =  $tab_value->setting_value; } else { $oral_habit_name =  $tab_value->var_title; }
         $oral_habits ='<div style="font-size:small;line-height:18px;"><b>'.$oral_habit_name.':</b>
       
        </div>';
         $oral_habits.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
       <tr>
            <td align="left" height="30">Oral Habit Name</td>
            <td align="center" height="30" colspan="2">Duration</td>
                </tr>';
        $num_var='';
       
        
       foreach($all_detail['prescription_list']['oral_habit_booking'] as $oral_habit_li)
       {
        $oral_habits.='<tr>
            <td align="left" height="30">'.$oral_habit_li->oral_habit_name.'</td>';
           
        
             $oral_habits.='<td align="center" height="30">'.$oral_habit_li->number.' '.$oral_habit_li->time.'</td>';
                         

             
        $oral_habits.='</tr>';
       }
        
       
     $oral_habits.='</table>
</div>';

    }
    
}

//incestigation start


if(strcmp(strtolower($tab_value->setting_name),'investigation')=='0')
    {   
     // echo "<pre>"; print_r($all_detail['prescription_list']['investigation_presc_list']); exit;

      $investigation_data='';
      
        if(!empty($all_detail['prescription_list']['investigation_presc_list']))
        {
            if(!empty($tab_value->setting_value)) { $investigation_name =  $tab_value->setting_value; } else { $investigation_name =  $tab_value->var_title; }
         $investigation_data ='<div style="font-size:small;line-height:18px;"><b>'.$investigation_name.':</b>
       
        </div>';
         $investigation_data.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Investigations</td>
             <td align="left" height="30">Tooth Name</td>
            
            <td align="center" height="30">Tooth Number</td>
            <td align="center" height="30">Remarks</td>       
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['investigation_presc_list'] as $dinvestigation_li)
       {
            $investigation_data.='<tr>
            <td align="left" height="30">'.$dinvestigation_li->sub_cat_name.'</td>';
            $investigation_data.='
            <td align="left" height="30">'.$dinvestigation_li->teeth_name.'</td>';
           
            $investigation_data.='<td align="center" height="30">'.$dinvestigation_li->teeth_no.'</td>';
          

            $investigation_data.='<td align="center" height="30">'.$dinvestigation_li->remarks.'</td>';
             
        $investigation_data.='</tr>';
       }
        
       
     $investigation_data.='</table>
</div>';

    }
   
}
//incestigation end
  if(strcmp(strtolower($tab_value->setting_name),'diagnosis')=='0')
    {   

      $diagnosis='';
      
        if(!empty($all_detail['prescription_list']['diagnosis_list']))
        {
            if(!empty($tab_value->setting_value)) { $Diagnosis =  $tab_value->setting_value; } else { $Diagnosis =  $tab_value->var_title; }
         $diagnosis ='<div style="font-size:small;line-height:18px;"><b>'.$Diagnosis.':</b>
       
        </div>';
         $diagnosis.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Diagnosis Name</td>
            <td align="center" height="30">Teeth Type</td>
            <td align="center" height="30">Tooth No.</td>       
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['diagnosis_list'] as $diagnos_li)
       {
        $diagnosis.='<tr>
            <td align="left" height="30">'.$diagnos_li->diagnosis_name.'</td>';
           
            $diagnosis.='<td align="center" height="30">'.$diagnos_li->teeth_name.'</td>';
          

            $diagnosis.='<td align="center" height="30">'.$diagnos_li->tooth_number.'</td>';
             
        $diagnosis.='</tr>';
       }
        
       
     $diagnosis.='</table>
</div>';

    }
   
}

 /* medicine code */


 //echo $tab_value->setting_name;
  if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    { 
        if(!empty($all_detail['prescription_list']['prescription_presc_list']))
          {
              
              if(!empty($tab_value->setting_value)) { $pre_names =  $tab_value->setting_value; } else { $pre_names =  $tab_value->var_title; }
            $patient_pres .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;float:left;">'.$pre_names.':</div>';  

            $i=1;
            
            $patient_pres .= '<table border="1" width="100%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;float:left;">';
            $patient_pres .= '<thead>
                <th>Sr. No.</th>';
                foreach ($prescription_medicine_tab_setting as $tab_value_all) 
                { 

                if(strcmp(strtolower($tab_value_all->setting_name),'medicine')=='0')
                { 


                if(!empty($tab_value_all->setting_value)) { $Medicine_name =  $tab_value_all->setting_value; } else { $Medicine_name =  $tab_value_all->var_title; }   


                $patient_pres .= '<th>'.$Medicine_name.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'salt')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Medicine_salt =  $tab_value_all->setting_value; } else { $Medicine_salt =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Medicine_salt.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'brand')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Medicine_brand =  $tab_value_all->setting_value; } else { $Medicine_brand =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Medicine_brand.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'type')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Types =  $tab_value_all->setting_value; } else { $Types =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Types.'</th>';

                }

                

                if(strcmp(strtolower($tab_value_all->setting_name),'dose')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Dose =  $tab_value_all->setting_value; } else { $Dose =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Dose.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'duration')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Duration =  $tab_value_all->setting_value; } else { $Duration =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Duration.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'frequency')=='0')
                { 
                if(!empty($tab_value->setting_value)) { $Frequency =  $tab_value_all->setting_value; } else { $Frequency =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Frequency.'</th>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'advice')=='0')
                { 
                if(!empty($tab_value_all->setting_value)) { $Advice =  $tab_value_all->setting_value; } else { $Advice =  $tab_value_all->var_title; }   
                $patient_pres .= '<th>'.$Advice.'</th>';

                }

            }    
             $patient_pres .= '</thead><tbody>';
              
             
                
            foreach($all_detail['prescription_list']['prescription_presc_list'] as $prescription_presc)
            { 
                          
                  $patient_pres .= '<tr>
                <td>'.$i.'</td>';

                foreach ($prescription_medicine_tab_setting as $tab_value_all) 
                { 
                if(strcmp(strtolower($tab_value_all->setting_name),'medicine')=='0')
                { 
                   
                  $patient_pres .= '<td>'.$prescription_presc->medicine_name.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'salt')=='0')
                { 
                   
                  $patient_pres .= '<td>'.$prescription_presc->medicine_salt.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'brand')=='0')
                { 
                   
                $patient_pres .= '<td>'.$prescription_presc->medicine_brand.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'type')=='0')
                { 
                  
                $patient_pres .= '<td>'.$prescription_presc->medicine_type.'</td>';

                }

                

                if(strcmp(strtolower($tab_value_all->setting_name),'dose')=='0')
                { 
                   
                $patient_pres .= '<td>'.$prescription_presc->medicine_dose.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'duration')=='0')
                { 
                 
                $patient_pres .= '<td>'.$prescription_presc->medicine_duration.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'frequency')=='0')
                { 
                 
                $patient_pres .= '<td>'.$prescription_presc->medicine_frequency.'</td>';

                }

                if(strcmp(strtolower($tab_value_all->setting_name),'advice')=='0')
                { 
                   
                $patient_pres .= '<td>'.$prescription_presc->medicine_advice.'</td>';

                }

            }

                $patient_pres .= '                
              </tr>';
                 
                 $i++;
                            
            }
            $patient_pres .= '</tbody></table>';

            }
    
    } 

    /* medicine code */

if(strcmp(strtolower($tab_value->setting_name),'treatment')=='0')
    {   
      $treatment='';
    
        
        if(!empty($all_detail['prescription_list']['treatment_booking']))
        {
            if(!empty($tab_value->setting_value)) { $treatment_booking =  $tab_value->setting_value; } else { $treatment_booking =  $tab_value->var_title; }
         $treatment ='<div style="font-size:small;line-height:18px;"><b>'.$treatment_booking.':</b>
       
        </div>';
         $treatment.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Treatment Name</td>
            <td align="center" height="30">Teeth Type</td>
            <td align="center" height="30">Tooth No.</td>       
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['treatment_booking'] as $treatment_booking_li)
       {
        $treatment.='<tr>
            <td align="left" height="30">'.$treatment_booking_li->treatment_name.'</td>';
           
            $treatment.='<td align="center" height="30">'.$treatment_booking_li->teeth_name.'</td>';
          

            $treatment.='<td align="center" height="30">'.$treatment_booking_li->tooth_number.'</td>';
             
        $treatment.='</tr>';
       }
        
       
     $treatment.='</table>
</div>';

    }
  
}

if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    {   

      $advice_booking='';
        if(!empty($all_detail['prescription_list']['advice_booking']))
        {
            if(!empty($tab_value->setting_value)) { $advice_booking_name =  $tab_value->setting_value; } else { $advice_booking_name =  $tab_value->var_title; }
         $advice_booking ='<div style="font-size:small;line-height:18px;"><b>'.$advice_booking_name.':</b>
       
        </div>';
         $advice_booking.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Advice Name</td>
                 
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['advice_booking'] as $advice_booking_li)
       {
        $advice_booking.='<tr>
            <td align="left" height="30">'.$advice_booking_li->advice_name.'</td>';
           
           
             
        $advice_booking.='</tr>';
       }
        
       
     $advice_booking.='</table>
</div>';

    }
    
}

  if(strcmp(strtolower($tab_value->setting_name),'next_appointment')=='0' && !empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970' )
{

//if(!empty($form_data['next_appointment_date'])){  ?>
  <?php if(!empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970'){ ?>
    
      <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($appointment_date->setting_value)) { echo $appointment_date =  $tab_value->setting_value; } else { echo  $appointment =  $tab_value->var_title; } ?> :</b>
       <?php $next_appointment_date= date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)); ?>
      </div>
    </div>
<?php }   }

    
    }

   $next_appointment_date='';
   $signature='';
   //$investigation='';
 
    $template_data = str_replace("{chief_complaints}",$chief_complaint,$template_data);
    $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
    $template_data = str_replace("{prv_history}",$previous_history,$template_data);
    $template_data = str_replace("{allergy}",$allergy,$template_data);

    $template_data = str_replace("{oral_habbit}",$oral_habits,$template_data);
    $template_data = str_replace("{treatement}",$treatment,$template_data);
    $template_data = str_replace("{advice}",$advice_booking,$template_data);
    $template_data = str_replace("{appointment_date}",$next_appointment_date,$template_data);
    $template_data = str_replace("{signature}",$signature,$template_data);
   $template_data = str_replace("{investigation}",$investigation_data,$template_data);
     $template_data = str_replace("{medicine}",$patient_pres,$template_data);
    $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
    $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
    $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
    $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
    

    echo $template_data; 

$this->session->unset_userdata('dental_prescription_id');
/* end thermal printing */
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
           //console.log(imgsrc);
           //$("#newimg").attr('src',imgsrc);
           //$("#img").show();
           $.ajax({
                url:'<?php echo base_url('prescription/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['prescription_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['prescription_list'][0]->patient_code; ?>"
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

<style>
*{margin:0;padding:0;box-sizing:border-box;}

.grid-frame3{display:block;}
.grid-frame3 .grid_tbl{border-collpase:collpase;border:1px solid #aaa;font:13px arial;}
.grid-frame3 .grid_tbl td{border:1px solid #aaa;border-top:none;border-left:none;padding:0 4px;}
.grid-frame3 .grid_tbl td input.w-40px{width:40px;padding:2px;}
.grid-frame3 .grid_tbl td select.w-60px{width:60px;padding:2px;}
</style>