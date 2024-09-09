<?php
$users_data = $this->session->userdata('auth_users');
$branch_id = $users_data['parent_id'];
//echo "<pre>"; print_r($prescription_list); die;
//echo count($prescription_list);
if(!empty($all_prescription_detail))
{
    $total_count = count($all_prescription_detail);
    $total = count($all_prescription_detail);
    $m=1;
    $final_template_data ='';
    
    foreach($all_prescription_detail as $allDetail)
    {
        //echo "<pre>"; print_r($pres_detail); die;
        $pres_detail = $allDetail[0];
        //echo "<pre>"; print_r($pres_detail); die;
        $page_count = 'Page '. $m.' of '.$total_count;
        if($pres_detail->flag=='0')
        {
            
        
        $template_format = $this->prescription->template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
        $template_format_left = $this->prescription->template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = $this->prescription->template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = $this->prescription->template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = $this->prescription->template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
        $template_data_left=$template_format_left->setting_value;
        $template_format_right=$template_format_right->setting_value;
        $template_format_top=$template_format_top->setting_value;
        $template_format_bottom=$template_format_bottom->setting_value;
        $vital_show=$template_format_bottom->vital_show;
        $template_data=$template_format->setting_value;
        
        $header_content=$template_format->header_content;
        $prescription_tab_setting = get_ipd_prescription_print_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $prescription_medicine_tab_setting = get_ipd_prescription_medicine_print_tab_setting('',$branch_id);
        
        
        //$template_data = str_replace("{page_count}",$page_count,$template_data);
        
        if($m != 1) {
             $template_data = str_replace('<page size="A4" style="margin-top: 0.5in;margin-right: 0.25in;margin-bottom: 0.50in;margin-left: 0.25in;float: left;">',"",$template_data);
             $template_data = str_replace('style="float:left;width:50%;min-height:100px;"',"hidden",$template_data);
             $template_data = str_replace('style="float:right;width:50%;min-height:100px;"',"hidden",$template_data);
             $template_data = str_replace('style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;"',"hidden",$template_data);
             
        }
        $template_data = str_replace('style="margin-top: 0.5in;margin-right: 0.25in;margin-bottom: 0.50in;margin-left: 0.25in;float: left;"','style="padding-bottom: 1in !important;"',$template_data);
        if($pres_detail->prescription_header=='1')
        {
            if($pres_detail->seprate_header=='1')
            {
                //docotor headercontent
                $doctor_header = $pres_detail->header_content;
                $template_data = str_replace("{header_content}",$doctor_header,$template_data);
            }
            else
            {   //tempate header content
                $doctor_header = $header_content;
                $template_data = str_replace("{header_content}",$doctor_header,$template_data);
            }
        }
        else
        {
           $template_data = str_replace("{header_content}",'',$template_data);  
        }
        
            $booking_time1 = date('h:i A', strtotime($pres_detail->booking_time)); 
            $template_data = str_replace("{date_time}",date('d-m-Y',strtotime($pres_detail->booking_date)).' '.$booking_time1,$template_data);
            $simulation = get_simulation_name($pres_detail->simulation_id);
            $template_data = str_replace("{patient_name}",$simulation.' '.$pres_detail->patient_name,$template_data);
            $address = $pres_detail->address;
            $pincode = $pres_detail->pincode;
            $booking_date_time='';
            if(!empty($pres_detail->admission_date) && $pres_detail->admission_date!='0000-00-00')
            {
                $booking_date_time = date('d-m-Y',strtotime($pres_detail->admission_date)); 
            }
            $booking_time ='';
            if(!empty($pres_detail->admission_time) && $pres_detail->admission_time!='00:00:00' && strtotime($pres_detail->admission_time)>0)
            {
                $booking_time = date('h:i A', strtotime($pres_detail->admission_time));    
            }
            
            $template_data = str_replace("{booking_time}",$booking_time,$template_data);
            $presc_time ='';
            if(!empty($all_detail['prescription_list'][0]->created_date) && $all_detail['prescription_list'][0]->created_date!='00:00:00' && strtotime($all_detail['prescription_list'][0]->created_date)>0)
            {
                $presc_time = date('d-m-Y h:i A', strtotime($all_detail['prescription_list'][0]->created_date));    
            }
            $template_data = str_replace("{prescription_time}",$presc_time,$template_data);
               //.' '.$pres_detail->booking_time   
            
            
             $validity_date='';
            if(!empty($pres_detail->validity_date) && $pres_detail->validity_date!='0000-00-00')
        {
            $validity_date = date('d-m-Y',strtotime($pres_detail->validity_date)); 
        }
           
            $template_data = str_replace("{validity_date}",$validity_date,$template_data);
             
            
            
            $patient_address = $address.' - '.$pincode;
        
            $template_data = str_replace("{patient_address}",$patient_address,$template_data);
        
            $template_data = str_replace("{patient_reg_no}",$pres_detail->patient_code,$template_data);
        
            $template_data = str_replace("{mobile_no}",$pres_detail->mobile_no,$template_data);
            
            //$template_data = str_replace("{booking_code}",$pres_detail->booking_code,$template_data);
            $template_data = str_replace("{ipd_no}",$pres_detail->ipd_no,$template_data);
            $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
            if(!empty($pres_detail->attended_doctor)) {
                $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($pres_detail->attended_doctor),$template_data);
            } else {
                $template_data = str_replace("{doctor_name}",'',$template_data);
            }
            
        
            $template_data = str_replace("{ref_doctor_name}",get_doctor_name($pres_detail->referral_doctor),$template_data);
            
            $spec_name='';
                $specialization = get_specilization_name($pres_detail->specialization_id);
                if(!empty($specialization))
                {
                    $spec_name= str_replace('(Default)','',$specialization);
                }
                
            $template_data = str_replace("{specialization}",$spec_name,$template_data);
        
               if(!empty($pres_detail->relation))
                {
                $rel_simulation = get_simulation_name($pres_detail->relation_simulation_id);
                $template_data = str_replace("{parent_relation_type}",$pres_detail->relation,$template_data);
                }
                else
                {
                 $template_data = str_replace("{parent_relation_type}",'',$template_data);
                }
        
            if(!empty($pres_detail->relation_name))
                {
                $rel_simulation = get_simulation_name($pres_detail->relation_simulation_id);
                $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$pres_detail->relation_name,$template_data);
                }
                else
                {
                 $template_data = str_replace("{parent_relation_name}",'',$template_data);
                }               
            
        
        
            if(!empty($vitals_list))
            {
                foreach ($vitals_list as $vitals) 
                {
                    $vitals_val = get_vitals_value($vitals->id,$pres_detail->id,$type);
                    $template_data = str_replace("{".$vitals->short_code."}",$vitals_val,$template_data);
                }
            }    
            //echo $template_data;
            $print_date_data = !empty($allDetail[0]->date_time_new) ? date('d-m-Y H:i A',strtotime($allDetail[0]->date_time_new)) : date('d-m-Y',strtotime($allDetail[0]->created_date));
            $patient_vital = '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;margin-bottom:1%;text-decoration:underline;float:left;"><b>'.$print_date_data.'</b></div>';
            if($vital_show==0)
            {
            $patient_vital .= '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
            <tbody>
                <tr>';
            }
            else{
            $patient_vital .= '<table cellpadding="0" cellspacing="0" style="border:none; margin-top : 20px;" width="100%">
            <tbody>
                <tr>';
            }
        
                if(!empty($vitals_list))
                {
                  $i=0;
                  foreach ($vitals_list as $vitals) 
                  {
                    $vital_val = get_vitals_value($vitals->id,$pres_detail->id,$type);
                    if(!empty($vital_val) || $vital_sets=='0'){
                    if($vital_show==0)
                    {
                    $patient_vital .= '<td align="left" valign="top">
                   
                    </td>';
                    }
                    else  if($vital_show==1)
                    {
                    $patient_vital .= '
                                <td style="width:15%;text-align:left;font-size:small;line-height:18px;" valign="top"> <b>'.$vitals->vitals_name.'</b> :</td>
                                <td style="width:7%;text-align:left;font-size:small;line-height:18px;" valign="top"> '.ucfirst($vital_val).'</td>
                                <td style="width:3%;text-align:left;font-size:small;line-height:18px;" valign="top">
                                '.ucfirst($vitals->vitals_unit).'
                                </td><td style="width:25%;font-size:small;line-height:18px;"></td>';
                    }
                    
                    }
                 
                    $i++;
                    if($i%2==0 && $vital_show==1)
                    {
                        $patient_vital .='</tr><tr>';
                    }
                  }
                }
        
                    
               $patient_vital .= '</tr>
            </tbody>
        </table>';
        // $template_data .= $patient_vital2;
         $template_data = str_replace("{date}",$patient_vital,$template_data);
            //echo $template_data; die;
        
            	$genders = array('0'=>'F','1'=>'M');
                $gender = $genders[$pres_detail->gender];
                $age_y = $pres_detail->age_y; 
                $age_m = $pres_detail->age_m;
                $age_d = $pres_detail->age_d;
        
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
            $patient_test_name="";
            $patient_pres = '';
            $prv_history ='';
            $personal_history ='';
            $chief_complaints ='';
            $examination ='';
            $diagnosis ='';
            $suggestion ='';
            $remark ='';
            $next_app = "";
            
            

            $prescription_test_list = get_prescription_test($pres_detail->id);
            foreach ($prescription_tab_setting as $value) {   
        
            if(strcmp(strtolower($value->setting_name),'test_result')=='0')
            {  
                if(!empty($prescription_test_list))
                {
                    if(!empty($value->setting_value)) { $Test_names =  $value->setting_value; } else { $Test_names =  $value->var_title; }
                    $patient_test_name .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;">'.$Test_names.':</div>';
                        
                      $patient_test_name .= '<div style="float:left;width:100%;margin:1% 0 0;border:1px solid #808080;font:12px Arial;">
                <div style="float:left;width:10%;height:25px;line-height:25px;border-right:1px solid #808080;padding:0 0 0 5px;"><b>Sr.No.</b></div>
                <div style="float:left;width:90%;height:25px;line-height:25px;padding:0 0 0 5px;"><b>Test Name</b></div>
            </div>';
        
                    $j=1;
                    
                    foreach($prescription_test_list as $prescription_testname)
                    { 
                    	 
                    $patient_test_name .= '<div style="float:left;width:100%;border:1px solid #808080;border-top:none;font:12px Arial;">
                <div style="float:left;width:10%;height:25px;line-height:25px;border-right:1px solid #808080;padding:0 0 0 15px;">'.$j.'</div>
                <div style="float:left;width:90%;height:25px;line-height:25px;padding:0 0 0 5px;">'.$prescription_testname->test_name.'</div>
            </div>';
                     $j++; 	 	 	 	
                    }
                    }
            
            }
        
            $prescription_med_list = get_prescription_medicine($pres_detail->id);
            
            if(strcmp(strtolower($value->setting_name),'prescription')=='0')
            {
        
                    if(!empty($prescription_med_list))
                    {
                      
                      if(!empty($value->setting_value)) { $pre_names =  $value->setting_value; } else { $pre_names =  $value->var_title; }
                    $patient_pres .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;float:left;">'.$pre_names.':</div>';  
        
                    $i=1;
                    
                    $patient_pres .= '<table border="1" width="99%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;">';
                    $patient_pres .= '<thead>
                        <th>Sr. No.</th>';
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Medicine_name =  $tab_value->setting_value; } else { $Medicine_name =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Medicine_name.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Medicine_salt =  $tab_value->setting_value; } else { $Medicine_salt =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Medicine_salt.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Medicine_brand =  $tab_value->setting_value; } else { $Medicine_brand =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Medicine_brand.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Types.'</th>';
        
                        }
        
                        
        
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Dose =  $tab_value->setting_value; } else { $Dose =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Dose.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Duration =  $tab_value->setting_value; } else { $Duration =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Duration.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Frequency =  $tab_value->setting_value; } else { $Frequency =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Frequency.'</th>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        if(!empty($tab_value->setting_value)) { $Advice =  $tab_value->setting_value; } else { $Advice =  $tab_value->var_title; }   
                        $patient_pres .= '<th>'.$Advice.'</th>';
        
                        }
        
                    }    
                     $patient_pres .= '</thead><tbody>';
                      
                     
                        
                    foreach($prescription_med_list as $prescription_presc)
                    { 
                                  
                          $patient_pres .= '<tr>
                        <td>'.$i.'</td>';
        
        
        
        
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        { 
                           
                        $patient_pres .= '<td>'.$prescription_presc->medicine_name.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        { 
                           
                        $patient_pres .= '<td>'.$prescription_presc->medicine_salt.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        { 
                           
                        $patient_pres .= '<td>'.$prescription_presc->medicine_brand.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { 
                          
                        $patient_pres .= '<td>'.$prescription_presc->medicine_type.'</td>';
        
                        }
        
                        
        
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { 
                           
                        $patient_pres .= '<td>'.$prescription_presc->medicine_dose.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        { 
                         
                        $patient_pres .= '<td>'.$prescription_presc->medicine_duration.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        { 
                         
                        $patient_pres .= '<td>'.$prescription_presc->medicine_frequency.'</td>';
        
                        }
        
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
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
            
        
        
            if(strcmp(strtolower($value->setting_name),'previous_history')=='0')
            {
        
                if(!empty($value->setting_value)) { $Prv_history =  $value->setting_value; } else { $Prv_history =  $value->var_title; }
        
                    if(!empty($pres_detail->prv_history)){ 
                    $prv_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Prv_history.' :</b>
                       '.nl2br($pres_detail->prv_history).'
                      </div>
                    </div>';
                    }
            }
        
            
        
        
            if(strcmp(strtolower($value->setting_name),'personal_history')=='0')
            {
                if(!empty($value->setting_value)) { $Personal_history =  $value->setting_value; } else { $Personal_history =  $value->var_title; }
                if(!empty($pres_detail->personal_history)){ 
                $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Personal_history.' :</b>
                   '.nl2br($pres_detail->personal_history).'
                  </div>
                </div>';
                }
            }
        
        
        
            if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0')
            {   
                if(!empty($value->setting_value)) { $Chief_complaints =  $value->setting_value; } else { $Chief_complaints =  $value->var_title; }
                if(!empty($pres_detail->chief_complaints)){ 
                $chief_complaints = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$Chief_complaints.':</b>
                   '.nl2br($pres_detail->chief_complaints).'
                  </div>
                </div>';
                }
            }
        
            
        
            if(strcmp(strtolower($value->setting_name),'examination')=='0')
            {
                if(!empty($value->setting_value)) { $Examination =  $value->setting_value; } else { $Examination =  $value->var_title; }
                if(!empty($pres_detail->examination)){ 
                $examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Examination.' :</b>
                   '.nl2br($pres_detail->examination).'
                  </div>
                </div>';
                }
            }
                
            
        
        
            if(strcmp(strtolower($value->setting_name),'diagnosis')=='0')
            { 
                if(!empty($value->setting_value)) { $Diagnosis =  $value->setting_value; } else { $Diagnosis =  $value->var_title; }
                if(!empty($pres_detail->diagnosis)){ 
                $diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Diagnosis.' :</b>
                   '.nl2br($pres_detail->diagnosis).'
                  </div>
                </div>';
                }
            }
                
        
        
        
            if(strcmp(strtolower($value->setting_name),'suggestions')=='0')
            {
                if(!empty($value->setting_value)) { $Suggestion =  $value->setting_value; } else { $Suggestion =  $value->var_title; }
                if(!empty($pres_detail->suggestion)){ 
                $suggestion = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b> '.$Suggestion.' :</b>'.nl2br($pres_detail->suggestion).'
                  
                  </div>
                </div>';
                }
            }    
            
        
        
            if(strcmp(strtolower($value->setting_name),'remarks')=='0')
            { 
                if(!empty($value->setting_value)) { $Remark =  $value->setting_value; } else { $Remark =  $value->var_title; }
                if(!empty($pres_detail->remark)){ 
                $remark = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Remark.' :</b>
                   '.nl2br($pres_detail->remark).'
                  </div>
                </div>';
                }
            }
            
         
        
        if(strcmp(strtolower($value->setting_name),'appointment')=='0' && !empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970' )
        {
        
        //if(!empty($form_data['next_appointment_date'])){  ?>
          <?php if(!empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970'){ ?>
            
              <div style="float:left;width:100%;margin:1em 0;">
              <div style="font-size:small;line-height:18px;">
                <b><?php  if(!empty($appointment_date->setting_value)) { echo $appointment_date =  $value->setting_value; } else { echo  $appointment =  $value->var_title; } ?> :</b>
               <?php echo  date('d-m-Y',strtotime($form_data['next_appointment_date'])); ?>
              </div>
            </div>
        <?php }   }
        
            
            }
        
            
        
            $template_data = str_replace("{patient_test_name}",$patient_test_name,$template_data);
            $template_data = str_replace("{patient_pres}",$patient_pres,$template_data);
            $template_data = str_replace("{prv_history}",$prv_history,$template_data);
            $template_data = str_replace("{personal_history}",$personal_history,$template_data);
            $template_data = str_replace("{chief_complaints}",$chief_complaints,$template_data);
            $template_data = str_replace("{examination}",$examination,$template_data);
            $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
            $template_data = str_replace("{suggestion}",$suggestion,$template_data);
            $template_data = str_replace("{remark}",$remark,$template_data);
            $template_data = str_replace("{appointment_date}",$next_app,$template_data);
            $template_data = str_replace("{signature}",$signature,$template_data);
            $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
            $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
            $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
            $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
            
            if($total !=$m) {
                $template_data = str_replace('</page>',"",$template_data);
            }
        
            $final_template_data .= $template_data;   
            
        }
        $m++;  
        echo $template_data;
        }
        
      //die;
}
    

?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
            // Select all <li> elements within <ul> elements
            $('ul').each(function() {
                // Find and remove all <br> elements within the current <li> element
                $(this).find('br').remove();
            });

            $('body').each(function() {
                $(this).find('br').each(function() {
                    var nextBr = $(this).next('br');
                    if (nextBr.length) {
                        nextBr.remove();
                    }
                });
            });
            $('p + br, br + p').filter('br').remove();
        });
</script>