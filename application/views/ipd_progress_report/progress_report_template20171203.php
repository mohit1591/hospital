<?php 
/* start thermal printing */
   //echo "<pre>"; print_r($all_detail[0]); exit;
    $simulation = get_simulation_name($all_detail[0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.' '.$all_detail[0]->patient_name,$template_data);

    $address = $all_detail[0]->address;
    $pincode = $all_detail[0]->pincode;
    $booking_date_time='';
    //echo $all_detail[0]->admission_date;
    if(!empty($all_detail[0]->admission_date) && $all_detail[0]->admission_date!='0000-00-00')
    {
        $booking_date_time = date('d-m-Y',strtotime($all_detail[0]->admission_date)); 
    }
       //.' '.$all_detail['prescription_list'][0]->booking_time   
    
    $patient_address = $address.' - '.$pincode;

    $template_data = str_replace("{patient_address}",$patient_address,$template_data);

    $template_data = str_replace("{patient_reg_no}",$all_detail[0]->patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$all_detail[0]->mobile_no,$template_data);
    
    $template_data = str_replace("{ipd_no}",$all_detail[0]->ipd_no,$template_data);
    $template_data = str_replace("{admission_date}",$booking_date_time,$template_data);
    $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($all_detail[0]->attend_doctor_id),$template_data);

    $template_data = str_replace("{room_no}",$all_detail[0]->room_no,$template_data);

    $template_data = str_replace("{bed_no}",$all_detail[0]->bad_no,$template_data);

    $template_data = str_replace("{room_type}",$all_detail[0]->room_category,$template_data);
    


    	$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail[0]->gender];
        $age_y = $all_detail[0]->age_y; 
        $age_m = $all_detail[0]->age_m;
        $age_d = $all_detail[0]->age_d;

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
    
            $patient_pres = '';
            $i=1;
            $patient_pres .= '<table border="1" width="100%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;float:left;">';
            $patient_pres .= '<thead>
                <th>BP</th><th>Temp </th><th>Weight  </th><th>PR  </th><th>Spo2  </th><th> RBS/FBS </th>';
                  
             $patient_pres .= '</thead><tbody>';
         
           //if(!empty($all_detail['progress_report_list']))
           //{
                
           //foreach($all_detail['progress_report_list'] as $prescription_presc)
            //{ 
                          
                  $patient_pres .= '<tr>
                <td>'.$all_detail[0]->patient_bp.'</td><td>'.$all_detail[0]->patient_temp.'</td><td>'.$all_detail[0]->patient_weight.'</td><td>'.$all_detail[0]->patient_height.'</td><td>'.$all_detail[0]->patient_spo2.'</td><td>'.$all_detail[0]->patient_rbs.'</td>';





                $patient_pres .= '</tr>';

                 
                 
                 //$i++;
                            
            //}  

         // }    
             
           $patient_pres .= '</tbody></table>';
        foreach ($progress_report_tab_setting as $tab_value) 
        { 
            if(strcmp(strtolower($tab_value->setting_name),'prescription')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->prescription)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->prescription.'
                      </div>
                    </div>';
                    }
            }

           if(strcmp(strtolower($tab_value->setting_name),'dressing')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->dressing)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->dressing.'
                      </div>
                    </div>';
                    }
            }
           if(strcmp(strtolower($tab_value->setting_name),'suggestions')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->suggestion)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->suggestion.'
                      </div>
                    </div>';
                    }
            }
           if(strcmp(strtolower($tab_value->setting_name),'remarks')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->remarks)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->remarks.'
                      </div>
                    </div>';
                    }
            }
        } 
    
    $template_data = str_replace("{progress_note}",$patient_pres,$template_data);
    $template_data = str_replace("{signature}",$signature,$template_data);
    $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
    $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
    $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
    $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
    

    echo $template_data; 

$this->session->unset_userdata('progress_report_id');
/* end thermal printing */
?>

