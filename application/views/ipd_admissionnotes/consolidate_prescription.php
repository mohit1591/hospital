<?php
$users_data = $this->session->userdata('auth_users');
$branch_id = $users_data['parent_id'];
//echo "<pre>"; print_r($prescription_list); die;
//echo count($prescription_list);
if(!empty($prescription_list))
{
    $total_count = count($prescription_list);
    $m=1;
    $final_template_data ='';
    foreach($prescription_list as $pres_detail)
    {
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
        $prescription_tab_setting = get_prescription_print_tab_setting('',$branch_id);
        //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
        $prescription_medicine_tab_setting = get_prescription_medicine_print_tab_setting('',$branch_id);
        
        
        $template_data = str_replace("{page_count}",$page_count,$template_data);
        
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
            if(!empty($pres_detail->booking_date) && $pres_detail->booking_date!='0000-00-00')
        {
            $booking_date_time = date('d-m-Y',strtotime($pres_detail->booking_date)); 
        }
        
            $booking_time ='';
            if(!empty($pres_detail->booking_time) && $pres_detail->booking_time!='00:00:00' && strtotime($pres_detail->booking_time)>0)
            {
                $booking_time = date('h:i A', strtotime($pres_detail->booking_time));    
            }
            
            $template_data = str_replace("{booking_time}",$booking_time,$template_data);
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
            
            $template_data = str_replace("{booking_code}",$pres_detail->booking_code,$template_data);
            $template_data = str_replace("{ipd_no}",$pres_detail->ipd_no,$template_data);
            
            $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
            $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($pres_detail->attended_doctor),$template_data);
        
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
            $patient_vital = '';
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
                    <table cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;" width="100%">
                        <tbody>
                            <tr>
                                <th align="center" style="border-bottom:1px solid black;padding-left: 4px;font-size:15px;" valign="top" width="50%"><b>'.$vitals->vitals_name.'</b></th>
                            </tr>
                            <tr>
                                <td align="left" valign="top">
                                <div style="float:left;min-height:20px;text-align: right;padding-left: 50px;">'.$vital_val.$vitals->vitals_unit.'</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
        
         $template_data = str_replace("{vitals}",$patient_vital,$template_data);
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
            
         $final_template_data .= $template_data;   
            
      
     
        }///normal prescription end
        else if($pres_detail->flag=='2') //Dental
        {
            /////////////////////////////////////////dental/////////////////////
            
            $type = 2;
            $prescription_id = $pres_detail->flag_id;
            $page_title = "Print Prescription";
            $opd_prescription_info = get_dental_detail_by_prescription_id($prescription_id);
            //print_r($opd_prescription_info);
            //die;
            $template_format = dental_template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
            
            $template_format_left = dental_template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
            $template_format_right = dental_template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
            $template_format_top = dental_template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
            $template_format_bottom = dental_template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
    
            $template_data_left=$template_format_left->setting_value;
            $template_format_right=$template_format_right->setting_value;
            $template_format_top=$template_format_top->setting_value;
            $template_format_bottom=$template_format_bottom->setting_value;
            
            $template_data=$template_format->setting_value;
          
            $all_detail= $opd_prescription_info;
            
            $opd_booking_id=$all_detail['prescription_list'][0]->booking_id;
            $prescription_tab_setting = get_dental_prescription_tab_setting('',$branch_id);
    
    
            $prescription_medicine_tab_setting = get_dental_prescription_medicine_tab_setting('',$branch_id);
            
           $booking_data=get_dental_opd_by_id($opd_booking_id);
           $patient_id=$booking_data['patient_id'];
           
           
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
                <tr>
                    <td align="left" height="30">Complaint Name</td>
                    <td align="center" height="30">Teeth Name</td>
                    <td align="center" height="30">Tooth No.</td>
                    <td align="center" height="30">Reason</td>
                    <td align="center" height="30" colspan="2">Duration</td>
                </tr>';
              
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
                    <td align="center" height="30">Teeth Name</td>
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
                    $patient_pres .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;float:left;">'.$pre_names.' :</div>';  
        
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
                    <td align="center" height="30">Teeth Name</td>
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
        
          $next_appointmentdates='';
 if(strcmp(strtolower($tab_value->setting_name),'next_appointment')=='0')
{

if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970'){ 
    
     $next_appointmentdates .= '<div style="float:left;width:100%;margin:1em 0;"><div style="font-size:small;line-height:18px;"><b>';
        
         if(!empty($appointment_date->setting_value)) { $next_appointmentdates .= $appointment_date =  $tab_value->setting_value; } else { $next_appointmentdates .=$appointment =  $tab_value->var_title; } $next_appointmentdates .=':</b>';
        
        $next_appointmentdates.= date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)); 
      $next_appointmentdates .='</div></div>';
  }   
    
}
        
            
            }
        
          // $next_appointment_date='';
           $signature='';
           //$investigation='';
         
            $template_data = str_replace("{chief_complaints}",$chief_complaint,$template_data);
            $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
            $template_data = str_replace("{prv_history}",$previous_history,$template_data);
            $template_data = str_replace("{allergy}",$allergy,$template_data);
        
            $template_data = str_replace("{oral_habbit}",$oral_habits,$template_data);
            $template_data = str_replace("{treatement}",$treatment,$template_data);
            $template_data = str_replace("{advice}",$advice_booking,$template_data);
            $template_data = str_replace("{appointment_date}",$next_appointmentdates,$template_data);
            $template_data = str_replace("{signature}",$signature,$template_data);
           $template_data = str_replace("{investigation}",$investigation_data,$template_data);
             $template_data = str_replace("{medicine}",$patient_pres,$template_data);
            $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
            $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
            $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
            $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
            
        $final_template_data .= $template_data;       
        } //end of dental
        else if($pres_detail->flag=='3')  //gynic
        {
            
          //////////////////////////////////////////////// Gynic/////////////////////////  
        $type = 3;
        $prescription_id = $pres_detail->flag_id;
        $branch_id = $pres_detail->branch_id;
        
        $opd_prescription_info = get_detail_by_prescription_gynic_consolidate($prescription_id);
        //echo"<pre>";print_r($opd_prescription_info);

        $prescription_patient_medicine_tab_setting = get_gynecology_prescription_medicine_tab_setting();
        $prescription_medicine_tab_setting = get_gynecology_prescription_medicine_tab_setting();

        $template_format = gynic_template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);

        $template_format_left = gynic_template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
        $template_format_right = gynic_template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
        $template_format_top = gynic_template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
        $template_format_bottom = gynic_template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);

        $template_data_left=$template_format_left->setting_value;
        $template_format_right=$template_format_right->setting_value;
        $template_format_top=$template_format_top->setting_value;
        $template_format_bottom=$template_format_bottom->setting_value;

        $template_data=$template_format->setting_value;
        
        $all_detail= $opd_prescription_info;

        $opd_booking_id=$all_detail['prescription_list'][0]->booking_id;
        $prescription_tab_setting = get_gynecology_patient_tab_setting('',$branch_id);

        $booking_data=get_gynic_opd_by_id($opd_booking_id);
        $patient_id=$booking_data['patient_id'];

        $vitals_list=gynic_vitals_list();

        $gpla_list= gpla_list($prescription_id);
        $right_ovary_dataa = get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,1);
        
        $right_ovary_db_data = get_gynecology_right_ovary_list($prescription_id, $opd_booking_id,0); 
        // left_ovary_dataa
        $left_ovary_dataa = get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,1);
        
        $left_ovary_db_data = get_gynecology_left_ovary_list($prescription_id, $opd_booking_id,0);
        $patient_icsilab_db_data =get_patient_icsilab_list($prescription_id, $opd_booking_id);
        
        $patient_antenatal_care_db_data = get_patient_antenatal_care_list($prescription_id, $opd_booking_id);
        
        
        $patient_fertility_data = get_fertility_list2($prescription_id, $opd_booking_id);
        $list_fertility_data = $patient_fertility_data;

      $fertility_co = $patient_fertility_data[0]->fertility_co;
      $fertility_uterine_factor = $patient_fertility_data[0]->fertility_uterine_factor;
      $fertility_tubal_factor = $patient_fertility_data[0]->fertility_tubal_factor;
      $fertility_uploadhsg = $patient_fertility_data[0]->fertility_uploadhsg;
      $fertility_laparoscopy = $patient_fertility_data[0]->fertility_laparoscopy;
      $fertility_risk = $patient_fertility_data[0]->fertility_risk;
      $fertility_decision = $patient_fertility_data[0]->fertility_decision;
      $fertility_ovarian_factor = $patient_fertility_data[0]->fertility_ovarian_factor;
      $fertility_ultrasound_images = $patient_fertility_data[0]->fertility_ultrasound_images;
      $fertility_male_factor = $patient_fertility_data[0]->fertility_male_factor;
      $fertility_sperm_date = $patient_fertility_data[0]->fertility_sperm_date;
      $fertility_sperm_count = $patient_fertility_data[0]->fertility_sperm_count;
      $fertility_sperm_motality = $patient_fertility_data[0]->fertility_sperm_motality;
      $fertility_sperm_g3 = $patient_fertility_data[0]->fertility_sperm_g3;
      $fertility_sperm_abnform = $patient_fertility_data[0]->fertility_sperm_abnform;
      $fertility_sperm_remarks = $patient_fertility_data[0]->fertility_sperm_remarks; 
            
            
            ///controller
            $print_patient_history_flag = $all_detail['prescription_list'][0]->print_patient_history_flag;
            $print_disease_flag = $all_detail['prescription_list'][0]->print_disease_flag;
            $print_complaints_flag = $all_detail['prescription_list'][0]->print_complaints_flag;
            
            $print_allergy_flag = $all_detail['prescription_list'][0]->print_allergy_flag;
            $print_general_examination_flag = $all_detail['prescription_list'][0]->print_general_examination_flag;
            $print_clinical_examination_flag = $all_detail['prescription_list'][0]->print_clinical_examination_flag;
            
            $print_investigations_flag = $all_detail['prescription_list'][0]->print_investigations_flag;
            $print_medicine_flag= $all_detail['prescription_list'][0]->print_medicine_flag;
            $print_advice_flag = $all_detail['prescription_list'][0]->print_advice_flag;
            
            $print_next_app_flag = $all_detail['prescription_list'][0]->print_next_app_flag;
            $print_gpla_flag = $all_detail['prescription_list'][0]->print_gpla_flag;
            $print_follicular_flag = $all_detail['prescription_list'][0]->print_follicular_flag;
            
            $print_icsilab_flag = $all_detail['prescription_list'][0]->print_icsilab_flag;
            $print_fertility_flag = $all_detail['prescription_list'][0]->print_fertility_flag;
            $print_antenatal_flag = $all_detail['prescription_list'][0]->print_antenatal_flag;
            
            
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
            
            $barcode="";
                    if(!empty($all_detail['prescription_list'][0]->barcode_image))
                    {
                        $barcode_image = $all_detail['prescription_list'][0]->barcode_image;
                        if($all_detail['prescription_list'][0]->barcode_type=='vertical')
                        {
                            $barcode = '<img width="90px" src="'.OPD_BARCODE_FS_PATH.$barcode_image.'" style="max-height:100px;">';
                        }
                        else
                        {
                            $barcode = '<img width="90px" src="'.OPD_BARCODE_FS_PATH.$barcode_image.'">';
                        }
                        
                    }
                    $template_data = str_replace("{bar_code}",$barcode,$template_data);
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
              
               //echo "<pre>"; print_r($all_detail['prescription_list']); exit;
                $patient_vital = $extra='';
                if(isset($all_detail['prescription_list'][0]->weight))
                  $all_detail['prescription_list'][0]->weight=$all_detail['prescription_list'][0]->weight;
                else
                  $all_detail['prescription_list'][0]->weight="";
            
                if(isset($all_detail['prescription_list'][0]->height))
                  $all_detail['prescription_list'][0]->height=$all_detail['prescription_list'][0]->height;
                else
                  $all_detail['prescription_list'][0]->height="";
            
                if(isset($all_detail['prescription_list'][0]->bmi))
                  $all_detail['prescription_list'][0]->bmi=$all_detail['prescription_list'][0]->bmi;
                else
                  $all_detail['prescription_list'][0]->bmi="";
            
                 if(isset($all_detail['prescription_list'][0]->lmps) && $all_detail['prescription_list'][0]->lmps !='0000-00-00' && $all_detail['prescription_list'][0]->lmps !='1970-01-01')
                  $all_detail['prescription_list'][0]->lmps=' LMP : '.date('d-m-Y',strtotime($all_detail['prescription_list'][0]->lmps));
                else
                  $all_detail['prescription_list'][0]->lmps="";
                
                 if(isset($all_detail['prescription_list'][0]->pog) && !empty($all_detail['prescription_list'][0]->pog))
                  $all_detail['prescription_list'][0]->pog=', POG : '.$all_detail['prescription_list'][0]->pog;
                else
                  $all_detail['prescription_list'][0]->pog="";
                /* if(isset($all_detail['prescription_list'][0]->map))
                  $all_detail['prescription_list'][0]->map=', MAP : '.$all_detail['prescription_list'][0]->map;
                else
                  $all_detail['prescription_list'][0]->map="";*/
            
                 if(isset($all_detail['prescription_list'][0]->edd) && $all_detail['prescription_list'][0]->edd !='0000-00-00' && $all_detail['prescription_list'][0]->edd !='1970-01-01')
                  $all_detail['prescription_list'][0]->edd=', EDD : '.date('d-m-Y',strtotime($all_detail['prescription_list'][0]->edd));
                else
                  $all_detail['prescription_list'][0]->edd="";
                  if(isset($all_detail['prescription_list'][0]->bp))
                  $all_detail['prescription_list'][0]->bp=$all_detail['prescription_list'][0]->bp;
                else
                  $all_detail['prescription_list'][0]->bp="";
            
            
            $extra= $all_detail['prescription_list'][0]->lmps.$all_detail['prescription_list'][0]->edd.$all_detail['prescription_list'][0]->pog;
             
             $users_data = $this->session->userdata('auth_users');
            
            
                $patient_vital .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;" width="100%">
            
                <tbody>
                    <tr style="font-size:13px;">';
            
                    if(!empty($vitals_list))
                    {
                     $patient_vital .= '<th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">Weight</br>(Kg)</th>
                      <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">Height</br>(cm)</th> 
                      <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">BMI</br>(Kg/m2)</th>
                      <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">MAP</th>
                      <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">BP</th> ';            
                      foreach ($vitals_list as $vitals) 
                      {
                        $vital_val = get_vitals_value();
            
                        
                          $patient_vital .= '<th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">'.$vitals->vitals_name;
                          if(!empty($vitals->vitals_unit))
                          {
                              $patient_vital .='<br>('.$vitals->vitals_unit.')';
                          }
                          
                          $patient_vital .='</th>';
                        
                      }
                      $patient_vital .='</tr><tr><td style="text-align:center;">'.$all_detail['prescription_list'][0]->weight.'</td>
                      <td style="text-align:center;">'.$all_detail['prescription_list'][0]->height.'</td>
                      <td style="text-align:center;">'.$all_detail['prescription_list'][0]->bmi.'</td>
                      <td style="text-align:center;">'.$all_detail['prescription_list'][0]->map.'</td>
                      <td style="text-align:center;">'.$all_detail['prescription_list'][0]->bp.'</td>';
            
                      foreach ($vitals_list as $vitals) 
                      {
                          $vital_val = get_vitals_value($vitals->id,$prescription_id,3);
                          $patient_vital .='<td align="center" valign="top" style="padding-left:0px;">'.$vital_val.'</td>';
                               
                      }
            
                      $patient_vital .=' </tr>';
                    }
            
                        
                   $patient_vital .= '</tr>
                </tbody>
            </table> <br>';
            
            
            
                  if(!empty($gpla_list) && count($gpla_list)>0 && $print_gpla_flag=='1')
                  {
                    $patient_gpla .= '<h4>Obs History</h4>
                    <b>G___________P_____________L_____________A____________</b><table border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;margin-top:5px;" width="50%">
                 
                    <tbody>
                    <tr style="font-size:13px;">
                      <th align="center" style="border:1px solid black;padding:0 8px;" >S. No.</th>
                      <th align="center" style="border:1px solid black;padding:0 8px;" >DOG</th>
                      <th align="center" style="border:1px solid black;padding:0 8px;" >Mode</th>
                      <th align="center" style="border:1px solid black;padding:0 8px;" >Month Year</th></tr><tr>';
                      $ij=0;
                      foreach ($gpla_list as $gpla) 
                      {
                        $ij++;
                          $patient_gpla .='<td style="border:1px solid black;text-align:center;">'.$ij.'</td>
                          <td style="border:1px solid black;text-align:center;">'.$gpla['dog_value'].'</td>
                          <td style="border:1px solid black;text-align:center;">'.$gpla['mode_value'].'</td>
                          <td style="border:1px solid black;text-align:center;padding-left:0px;">'.$gpla['monthyear_value'].'</td>';
                               
                      }
            
                      $patient_gpla .=' </tr></tbody></table>';
                  }
            
            
             $template_data = str_replace("{vitals}",$patient_vital,$template_data);
            
             $template_data = str_replace("{extra}",$extra,$template_data);
             $template_data = str_replace("{patient_gpla}",$patient_gpla,$template_data);
             
             
             $common_rsk='';
             if(!empty($all_detail['prescription_list'][0]->common_fertility_risk))
             {
                 $common_rsk = '<div style="">
                        <div style="line-height:17px;font-weight:600;">Risk :</div>
            
                        <div style="">'.$all_detail['prescription_list'][0]->common_fertility_risk.'</div>
                        </div>';  
             }
            
                        
                        
             $template_data = str_replace("{common_fertility_risk}",$common_rsk,$template_data);
             
             
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
                $patient_history="";
                $family_history = '';
                $personal_history ='';
                $menstrual_history ='';
                $medical_history ='';
                $obestetric_history ='';
                $current_medicine ='';
                $medicine ='';
                $disease_data ='';
                $complaints_data = "";
                $allergy_data='';
                $general_examination_data ='';
                $clinical_examination_data ='';
                $investigation_data = "";
                $medicine_data = "";
                $advice_data = "";
                $next_appointment ='';
                $next_app = "";
            
            
                $next_appointment_date= '';
                $right_ovary_data ='';
                $left_ovary_data='';
                $icsilab_data='';
                $fertility_data='';
                $antenatal_data ='';
            
            
            
            
            //new updates
            foreach ($prescription_tab_setting as $tab_value) 
            {
            
            
            
                if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0 && $print_patient_history_flag==1)
                { 
                    $patient_history='';
                    
                   /* if(!empty($tab_value->setting_value)) { $patient_history_name =  $tab_value->setting_value; } else { $patient_history_name =  'Patient History';//$tab_value->var_title; 
                          
                      }*/
                      
                    $patient_history .='<h3 style="margin-bottom:0px;">Patient History:</h3>';
                    if(!empty($all_detail['prescription_list']['patient_history']))
                    {
                      
            
                      $patient_history.='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%"><thead class="bg-theme"><tr><th>Married</th><th>Married Life Duration</th><th>Marriage No.</th><th>Married Details</th><th>Previous Delivery</th><th>Delivery Type</th><th>Delivery Details </th></tr></thead>';
                      foreach($all_detail['prescription_list']['patient_history'] as $patient_history_li)
                      {
                        
                       $patient_history.='<tr>';
                       $patient_history.='<td> '.$patient_history_li->marriage_status.'</td>';
                       
                       if(!empty($patient_history_li->married_life_unit))
                       {
                          $patient_history.='<td> '.$patient_history_li->married_life_unit.' '.$patient_history_li->married_life_type.'</td>';
                       }
                       if(!empty($patient_history_li->marriage_details))
                       {
                          $patient_history.=' <td> '.$patient_history_li->marriage_details.'</td>';
                       }
                       if(!empty($patient_history_li->previous_delivery))
                       {
                          $patient_history.=' <td>'.$patient_history_li->previous_delivery.'</td>';
                       }
                       if(!empty($patient_history_li->delivery_type))
                       {
                          $patient_history.=' <td>'.$patient_history_li->delivery_type.'</td>';
                       }
            
                       if(!empty($patient_history_li->delivery_details))
                       {
                          $patient_history.=' <td>'.$patient_history_li->delivery_details.'</td>';
                       }
            
                       $patient_history.='</tr>';
                      }
                       $patient_history.='</table>';
                    }
                  
                }
            
            
                    $family_history='';
                    if(!empty($all_detail['prescription_list']['family_history']) && $print_patient_history_flag==1)
                    {
            
                     // if(!empty($tab_value->setting_value)) { $family_history_name =  $tab_value->setting_value; } else { $family_history_name =  'Family History';//$tab_value->var_title; 
                          
                      //}
            
                        $family_history .='<h3 style="margin-bottom:0px;">Family History:</h3>';
                        $family_history .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                       <thead>
                                                          <tr>                                        <th>Relation</th>
                                                             <th>Disease</th>
                                                             <th>Description</th>
                                                             <th>Disease Duration</th>
                                                          </tr>
                                                       </thead>';
                        
                    
                      foreach($all_detail['prescription_list']['family_history'] as $family_history_li)
                      {
                        $family_history.='<tr><td>'.$family_history_li->relation.' </td><td> '.$family_history_li->disease.'</td><td>  '.$family_history_li->family_description.'</td><td>  '.$family_history_li->family_duration_unit.'</td><td> '.$family_history_li->family_duration_type.' </td></tr>';
            
                      }
                      $family_history.='</table>';
                    
            
                    }
            //echo $family_history; die;
              
                    $personal_history='';
                    if(!empty($all_detail['prescription_list']['personal_history']) && $print_patient_history_flag==1)
                    {
                      //if(!empty($tab_value->setting_value)) { $personal_history_name =  $tab_value->setting_value; } else { $personal_history_name =  'Patient History'; //$tab_value->var_title; 
                          
                      //}
            
                      $personal_history ='<h3 style="margin-bottom:0px;">Personal History:</h3>';
                      $personal_history .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                       <thead>
                                                          <tr>
                                                            
                                                             <th>Breast Discharge</th>
                                                             <th>Side</th>
                                                             <th>Hirsutism</th>
                                                             <th>White Discharge</th>
                                                             <th>Type</th>
                                                             <th>Frequency</th>
                                                             <th>Dyspareunia</th>
                                                             <th>Details</th>
                                                          </tr>
                                                       </thead>';
                      foreach($all_detail['prescription_list']['personal_history'] as $personal_history_li)
                      {
            
                        $personal_history.='<tr><td>'.$personal_history_li->br_discharge.'</td><td> '.$personal_history_li->side.' </td><td> '.$personal_history_li->hirsutism.'  '.$personal_history_li->white_discharge.' </td><td> '.$personal_history_li->type.'</td><td> '.$personal_history_li->frequency_personal.'</td><td> '.$personal_history_li->dyspareunia.' </td><td>'.$personal_history_li->personal_details.'</td></tr>';
                     }
                     $personal_history.='</table>';
                  
            
                    }
            
               
                    $menstrual_history='';
                    if(!empty($all_detail['prescription_list']['menstrual_history']) && $print_patient_history_flag==1)
                    {
                      //if(!empty($tab_value->setting_value)) { $menstrual_history_name =  $tab_value->setting_value; } else { $menstrual_history_name = 'Menstrual History'; //$tab_value->var_title; 
                      //}
                      $menstrual_history ='<h3 style="margin-bottom:0px;">Menstrual History:</h3>';
                      $menstrual_history .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                     <thead>
                                                        <tr>
                                                           <th>Previous Cycle</th>
                                                           <th>Cycle Type</th>
                                                           <th>Present Cycle</th>
                                                           <th>Cycle Type</th>
                                                           <th>Details</th>
                                                           <th style="120px;">LMP Date</th>
                                                           <th>Dysmenorrhea</th>
                                                           <th>Dysmenorrhea Type</th>
                                                        </tr>
                                                     </thead>';
                      foreach($all_detail['prescription_list']['menstrual_history'] as $menstrual_history_li)
                      {
            
                        $lmp_date='';
                        if(($menstrual_history_li->lmp_date=="1970-01-01")||($menstrual_history_li->lmp_date=="") ||($menstrual_history_li->lmp_date=="0000-00-00"))
                        {
                          $lmp_date = "";
                        }
                        else
                        {
                          $lmp_date = date("d-m-Y",strtotime($menstrual_history_li->lmp_date));
                        }
            
            
                        $menstrual_history.='<tr><td>'.$menstrual_history_li->previous_cycle.' </td><td>  '.$menstrual_history_li->prev_cycle_type.'</td><td> '.$menstrual_history_li->present_cycle.' </td><td> '.$menstrual_history_li->present_cycle_type.' </td><td> '.$menstrual_history_li->cycle_details.' </td><td>'.$lmp_date.' '.$menstrual_history_li->dysmenorrhea.'</td><td> '.$menstrual_history_li->dysmenorrhea_type.'</td></tr>';
            
            
                      }
                       $menstrual_history.='</table>';
                    
                   
            
                    }
                
                    $medical_history_p='';
                    if(!empty($all_detail['prescription_list']['medical_history']) && $print_patient_history_flag==1)
                    {
                       //$medical_history_name =  'Medical History';//$tab_value->var_title; 
                     // if(!empty($tab_value->setting_value)) { $medical_history_name =  $tab_value->setting_value; } else {    
                      //}
                      $medical_history_p .='<h3 style="margin-bottom:0px;">Medical History:</h3>';
                      $medical_history_p .='<table  border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                       <thead>
                                                          <tr>
                                                            
                                                             <th>T.B</th>
                                                             <th>Rx</th>
                                                             <th>D.M</th>
                                                             <th>Years</th>
                                                             <th>Rx</th>
                                                             <th>H.T</th>
                                                             <th>Details</th>
                                                             <th>Others</th>
                                                          </tr>
                                                       </thead>';
                      foreach($all_detail['prescription_list']['medical_history'] as $medical_history_li)
                      {
            
            
                        $medical_history_p.='<tr><td>'.$medical_history_li->tb.'</td><td>  '.$medical_history_li->tb_rx.' </td><td> '.$menstrual_history_li->present_cycle.' </td><td> '.$medical_history_li->dm.' </td><td> '.$medical_history_li->dm_years.' </td><td> '.$medical_history_li->dm_rx.' </td><td> '.$medical_history_li->ht.' </td><td> '.$medical_history_li->medical_details.' </td><td> '.$medical_history_li->medical_others.'</td></tr>';
                        
                      
                      }
                       $medical_history_p.='</table>';
                    
                
            
                    }
                    
                    $obestetric_history='';
                    if(!empty($all_detail['prescription_list']['obestetric_history']) && $print_patient_history_flag==1)
                    {
            
                      //if(!empty($tab_value->setting_value)) { $obestetric_history_name =  $tab_value->setting_value; } else { $obestetric_history_name = 'Obesteric History'; //$tab_value->var_title; 
                          
                      //}
            
                       $obestetric_history ='<h3 style="margin-bottom:0px;">Obesteric History:</h3>';
                      $obestetric_history .='<table  border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%"><thead>
                                                          <tr>
                                                            
                                                             <th>G</th>
                                                             <th>P</th>
                                                             <th>A</th>
                                                             <th>E</th>
                                                             
                                                             <th>LMP</th>
                                                             <th>Remarks</th>
                                                          </tr>
                                                       </thead>';
                      
                      foreach($all_detail['prescription_list']['obestetric_history'] as $obestetric_history_li)
                      {
                         
            
                         $obestetric_history.='<tr><td>'.$obestetric_history_li->obestetric_g.' </td><td>  '.$obestetric_history_li->obestetric_p.'</td><td> '.$obestetric_history_li->obestetric_l.' </td><td> '.$obestetric_history_li->obestetric_e.' </td><td> '.$obestetric_history_li->obestetric_mtp.' </td><td> '.$obestetric_history_li->obestetric_remarks.' </td></tr>';
            
            
            
                        
                      }
                      $obestetric_history.='</table>';
                     
                      
            
                    }
            
            
                if(strcmp(strtolower($tab_value->setting_name),'disease')=='0' && $tab_value->print_status!=0 && $print_disease_flag=='1')
                {   
                  
                  $disease_data='';
                  if(!empty($all_detail['prescription_list']['disease_history']))
                  {
                    
            
                    if(!empty($tab_value->setting_value)) { $disease_history_name =  $tab_value->setting_value; } else { $disease_history_name =  $tab_value->var_title; }
            
                       $disease_data ='<h3 style="margin-bottom:0px;">'.$disease_history_name.':</h3>';
                      $disease_data .='<table  border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                <thead>
                                                   <tr>
                                                      
                                                      <th>Disease Name</th>
                                                      <th>Duration</th>
                                                      <th>Description</th>
                                                   </tr>
                                                </thead>';
            
                    foreach($all_detail['prescription_list']['disease_history'] as $disease_data_li)
                    {
                      $disease_data.='<tr><td>'.$disease_data_li->patient_disease_name.' </td><td> '.$obestetric_history_li->obestetric_p.' </td><td>  '.$disease_data_li->disease_description.'</td></tr>';
                    }
                    $disease_data.='</table>';
            
                  }
                
                 
                }
                if(strcmp(strtolower($tab_value->setting_name),'complaints')=='0' && $print_complaints_flag=='1')
                {   //echo "asas";die;
                  $complaints_data='';
                  if(!empty($all_detail['prescription_list']['complaint']))
                  {
                    if(!empty($tab_value->setting_value)) { $complaint_name =  $tab_value->setting_value; } else { $complaint_name =  $tab_value->var_title; }
                    $complaints_data .='<h3 style="margin-bottom:0px;">'.$complaint_name.':</h3>';
                    $complaints_data .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                <thead>
                                                   <tr>
                                                      
                                                      <th>Complaint Name</th>
                                                      <th>Duration</th>
                                                      <th>Description</th>
                                                   </tr>
                                                </thead>';
                    foreach($all_detail['prescription_list']['complaint'] as $complaint_data_li)
                    {
                      
                      $complaints_data.='<tr><td>'.$complaint_data_li->patient_complaint_name.'</td>  ';
                       if(!empty($complaint_data_li->patient_complaint_unit))
                       {
                          $complaints_data.='<td>'.$complaint_data_li->patient_complaint_unit.'</td> ';
                       }
            
                       if(!empty($complaint_data_li->patient_complaint_type))
                       {
                          $complaints_data.='<td>'.$complaint_data_li->patient_complaint_type.' </td>';
                       }
            
                       if(!empty($complaint_data_li->complaint_description))
                       {
                          $complaints_data.='<td>'.$complaint_data_li->complaint_description.'</td>';
                       }
            
                     $complaints_data.='</tr>';
                    }
                    $complaints_data.='</table>';
            
                  }
                
                 
                }
             //echo $complaint_data;die;
                if(strcmp(strtolower($tab_value->setting_name),'allergy')=='0' && $tab_value->print_status!=0 && $print_allergy_flag=='1')
                {   
                  $allergy_data='';
                  if(!empty($all_detail['prescription_list']['allergy']))
                  {
                    if(!empty($tab_value->setting_value)) { $allergy_name =  $tab_value->setting_value; } else { $allergy_name =  $tab_value->var_title; }
                    $allergy_data .='<h3 style="margin-bottom:0px;">'.$allergy_name.':</h3>';
                    $allergy_data .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                <thead>
                                                   <tr>
                                                     
                                                      <th>Allergy Name</th>
                                                      <th>Duration</th>
                                                      <th>Description</th>
                                                   </tr>
                                                </thead>';
                    foreach($all_detail['prescription_list']['allergy'] as $allergy_data_li)
                    {
            
                      $allergy_data.='<tr><td>'.$allergy_data_li->patient_allergy_name.' </td><td> '.$allergy_data_li->patient_allergy_unit.' </td><td>'.$allergy_data_li->patient_allergy_type.' </td><td> '.$allergy_data_li->allergy_description.'</td></tr>';
            
                      
                    }
                    $allergy_data.='</table>';
            
                  }
             
                 
                }
              
            
                if(strcmp(strtolower($tab_value->setting_name),'general_examination')=='0' && $tab_value->print_status!=0 && $print_general_examination_flag=='1')
                {   
                  $general_examination_data='';
                  if(!empty($all_detail['prescription_list']['general_examination']))
                  {
                    if(!empty($tab_value->setting_value)) { $general_examination_name =  $tab_value->setting_value; } else { $general_examination_name =  $tab_value->var_title; }
                    $general_examination_data .='<h3 style="margin-bottom:0px;">'.$general_examination_name.':</h3>';
                    $general_examination_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
            
                    foreach($all_detail['prescription_list']['general_examination'] as $general_examination_data_li)
                    {
            
                      $general_examination_data.='<li style="margin-bottom:15px;">'.$general_examination_data_li->patient_general_examination_name.'  '.$general_examination_data_li->general_examination_description.'</li>';
                    }
                    $general_examination_data.='</ol>';
            
                  }
             
                 
                }
                
                if(strcmp(strtolower($tab_value->setting_name),'clinical_examination')=='0' && $tab_value->print_status!=0 && $print_clinical_examination_flag=='1')
                {   
                  $clinical_examination_data='';
                  if(!empty($all_detail['prescription_list']['clinical_examination']))
                  {
                    if(!empty($tab_value->setting_value)) { $clinical_examination_name =  $tab_value->setting_value; } else { $clinical_examination_name =  $tab_value->var_title; }
                    $clinical_examination_data .='<h3 style="margin-bottom:0px;">'.$clinical_examination_name.':</h3>';
                    $clinical_examination_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
            
                    foreach($all_detail['prescription_list']['clinical_examination'] as $clinical_examination_data_li)
                    {
            
                        $clinical_examination_data.='<li style="margin-bottom:15px;">'.$clinical_examination_data_li->patient_clinical_examination_name.' '.$clinical_examination_data_li->clinical_examination_description.'</li>';
                    }
                    $clinical_examination_data.='</ol>';
            
                  }
            
                }
            
            
                if(strcmp(strtolower($tab_value->setting_name),'investigation')=='0' && $tab_value->print_status!=0 && $print_investigations_flag=='1')
                {   
                  $investigation_data='';
                  if(!empty($all_detail['prescription_list']['investigation_prescription']))
                  {
                    if(!empty($tab_value->setting_value)) { $investigation_names =  $tab_value->setting_value; } else { $investigation_names =  $tab_value->var_title; }
            
                    $investigation_data .='<h3 style="margin-bottom:0px;">'.$investigation_names.':</h3>';
                    $investigation_data .='<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
                                                <thead>
                                                   <tr>
                                                    
                                                      <th>Investigation Name</th>
                                                      <th>Std. Value</th>
                                                      <th>Observed Value</th>
                                                      <th>Date</th>
                                                   </tr>
                                                </thead>';
            
                     foreach($all_detail['prescription_list']['investigation_prescription'] as $investigation_data_li)
                    {
                      //echo $investigation_data_li->investigation_date;die;
                      if(($investigation_data_li->investigation_date=="1970-01-01")||($investigation_data_li->investigation_date=="") ||($investigation_data_li->investigation_date=="0000-00-00"))
                      {
                        $investigation_date = "";
                      }
                      else
                      {
                        $investigation_date = date("d-m-Y",strtotime($investigation_data_li->investigation_date));
                      }
            
                      $investigation_data.='<tr><td>'.$investigation_data_li->patient_investigation_name.'</td> ';
                        if(!empty($investigation_data_li->std_value) && $investigation_data_li->std_value!=0)
                        {
                            $investigation_data.='<td>'.$investigation_data_li->std_value.' </td>';
                        }
                        else
                        {
                           $investigation_data.='<td> &nbsp; </td>'; 
                        }
                        if(!empty($investigation_data_li->observed_value))
                        {
                            $investigation_data.='<td>'.$investigation_data_li->observed_value.'</td> ';
                        }
                        else
                        {
                            $investigation_data.='<td>&nbsp;</td> ';
                        }
                        
                        if(!empty($investigation_date))
                        {
                            $investigation_data.='<td>'.$investigation_date.'</td> ';
                        }
                        else
                        {
                           $investigation_data.='<td>&nbsp; </td> '; 
                        }
                        
                      $investigation_data.='</tr>';
                    }
                    $investigation_data.='</table>';
            
                  }
                  
                 
                }
            
                if(strcmp(strtolower($tab_value->setting_name),'advice')=='0' && $tab_value->print_status!=0 && $print_advice_flag=='1')
                {   
                  $advice_data='';
                  if(!empty($all_detail['prescription_list']['advice_prescription']))
                  {
                    if(!empty($tab_value->setting_value)) { $advice_name =  $tab_value->setting_value; } else { $advice_name =  $tab_value->var_title; }
                    
                    $advice_data .='<h3 style="margin-bottom:0px;">'.$advice_name.':</h3>';
                    $advice_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
            
                    foreach($all_detail['prescription_list']['advice_prescription'] as $advice_data_li)
                    {
                      $advice_data.='<li style="margin-bottom:15px;">'.nl2br($advice_data_li->patient_advice_name).'</li>';
                    }
                    $advice_data.='</ol>';
            
                  }
                
                 
                }
            
            
            
              if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0)
              {
                if(!empty($all_detail['prescription_list']['prescription_history_data']))
                {
            
                   if(!empty($tab_value->setting_value)) { $prescription_history__name =  $tab_value->setting_value; } else { $prescription_history__name = 'Current Medication'; //$tab_value->var_title; 
                       
                   }
                    //echo $prescription_history__name; exit;
                    $current_medicine .='<h3 style="margin-bottom:0px;">Current Medication:</h3>';
                    $current_medicine .='<table  border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%"><thead>
                                                          <tr>
                                                            
                                                             <th>Medicine</th>
                                                             <th>Type</th>
                                                             <th>Salt</th>
                                                             <th>Brand</th>
                                                             
                                                             <th>Dose</th>
                                                             <th>Duration (Days)</th>
                                                             <th>Frequency</th>
                                                             <th>Advice</th>
                                                          </tr>
                                                       </thead>';
                    $prescription_history_data = $all_detail['prescription_list']['prescription_history_data'];
                         
                    foreach ($prescription_history_data as $prescriptiondata)
                    {  
                        $current_medicine.='<tr>';
                        foreach ($prescription_medicine_tab_setting as $value) 
                        {
                                if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                  $current_medicine.='<td>'.$prescriptiondata->medicine_name.'</td>';
                                }
            
                                if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                   $current_medicine.='<td>'.$prescriptiondata->medicine_brand.'</td>';
                                }
                                if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                   $current_medicine.='<td>'.$prescriptiondata->medicine_salt.'</td>';
                               
                                }
                                 if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                                  { 
                                    $current_medicine.='<td>'.$prescriptiondata->medicine_type.'</td> ';
                              }
                              if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                              {
                                   $current_medicine.='<td>'.$prescriptiondata->medicine_dose.' </td>';
                              } 
                              if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                              {
                                $current_medicine.='<td>'.$prescriptiondata->medicine_duration.' </td>';
                              } 
                              if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                              { 
                                  $current_medicine.='<td>'.$prescriptiondata->medicine_frequency.'</td> ';
                             }
                               if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                               {
                                 $current_medicine.='<td>'.$prescriptiondata->medicine_advice.'</td>';
                               } 
                                
                          } 
                          $current_medicine.='</tr>';
                         
                     }  
                      $current_medicine.='</table>';   
                      
                  }
              }
            
            
            
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0' && $tab_value->print_status!=0 && $print_medicine_flag=='1')
            {
                  $medicine='';
                  if(!empty($all_detail['prescription_list']['prescription_data']) )
                  {
            
                      $prescription_data = $all_detail['prescription_list']['prescription_data'];
                   
                      if(!empty($tab_value->setting_value)) { $med_name =  $tab_value->setting_value; } else { $med_name =  $tab_value->var_title; }
                    
                    $medicine .='<h3 style="margin-bottom:0px;">'.$med_name.':</h3>';
                    $medicine .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
                         
                        foreach ($prescription_data as $prescriptiondata)
                        {  
                          
                          $medicine.='<li style="margin-bottom:15px;">';
                              
                                foreach ($prescription_medicine_tab_setting as $value) 
                                {
            
                                
                                if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                  $medicine.=$prescriptiondata->medicine_name.' ';
                                }
            
                                if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                   $medicine.=$prescriptiondata->medicine_brand.' ';
                                }
                                if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                                { 
                                   $medicine.=$prescriptiondata->medicine_salt.' ';
                               
                                }
                                 if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                                  { 
                                    $medicine.=$prescriptiondata->medicine_type.' ';
                              }
                              if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                              {
                                   $medicine.=$prescriptiondata->medicine_dose.' ';
                              } 
                              if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                              {
                                $medicine.=$prescriptiondata->medicine_duration.' ';
                              } 
                              if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                              { 
                                  $medicine.=$prescriptiondata->medicine_frequency.' ';
                             }
                               if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                               {
                                 $medicine.=$prescriptiondata->medicine_advice.' ';
                               } 
                               
                              } 
                          $medicine.='</li>';
                         }  
                         
                        $medicine.='</ol>';
                  } 
             }
            
            
            
              if(strcmp(strtolower($tab_value->setting_name),'next_appointment' )=='0' && $tab_value->print_status!=0 && $print_next_app_flag=='1' && !empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970' )
            {
            
            if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970')
            {
                if(!empty($tab_value->setting_value)) { $next_apt_name =  $tab_value->setting_value; } else { $next_apt_name =  $tab_value->var_title; }
                    
                    $next_appointment_date .='<h3 style="margin-bottom:0px;">'.$next_apt_name.':</h3>';
                    $next_appointment_date .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
                    $next_appointment_date .=date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date));
             }   
            }
            
            
            if(strcmp(strtolower($tab_value->setting_name),'follicularscaning')=='0' && $tab_value->print_status!=0 && $print_follicular_flag=='1')
            {
              
              if(!empty($tab_value->setting_value)) { $follicularscaning_name =  $tab_value->setting_value; } else { $follicularscaning_name =  $tab_value->var_title; }
                    
                    $right_ovary_data_size='';
                    if(!empty($right_ovary_db_data))
                    {
                        $r_count = count($right_ovary_db_data);
                        $q=1;
                      foreach($right_ovary_db_data as $key=>$rightovarydataa)
                      { 
                          if(!empty($rightovarydataa['right_follic_size']))
                          {
                              $r_comm='';
                              if($q!==$r_count)
                              {
                                 $r_comm =', '; 
                              }
                            $right_ovary_data_size.= $rightovarydataa['right_follic_size'].$r_comm;
                            $q++;   
                          }   
                      }  
                    }
                    
                     $left_ovary_data_size='';
                    if(!empty($right_ovary_db_data))
                    {
                        $r_count = count($right_ovary_db_data);
                        $q=1;
                      foreach($right_ovary_db_data as $key=>$rightovarydataa)
                      { 
                          if(!empty($rightovarydataa['left_follic_size']))
                          {
                              $r_comm='';
                              if($q!==$r_count)
                              {
                                 $r_comm =', '; 
                              }
                            $left_ovary_data_size.= $rightovarydataa['left_follic_size'].$r_comm;
                            $q++;     
                          }
                      }  
                    }
                      
               
               $right_ovary_data .='<h3 style="margin-bottom:0px;">Follicular Scanning:</h3>';
                if(!empty($right_ovary_dataa))
                {  
                   $right_ovary_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
            
                   <thead class="bg-theme"><tr>        
                                  <th>Date</th>
                                  <th>Day</th>
                                  <th>Protocol</th>
                                  <th>PFSH</th>
                                  <th>REC FSH</th>
                                  <th>HMG</th>
                                  <th>HP HMG </th>
                                  <th>Agonist </th>
                                  <th>Antagonist </th>
                                  <th>Trigger</th> 
                                  <th>Endometriumothers </th>
                                  <th>E2 </th>
                                  <th>P4 </th>
                                  <th>Risk </th>
                                  <th>Others </th>
                                  
                                  
                                  <th>Right Size </th>
                                  <th>Left Size </th>
                              </tr></thead>';
                      $i = 1;
                      foreach($right_ovary_dataa as $rightovarydata)
                      { 
                          if($i=='1')
                          {
                              $r_size_data = $right_ovary_data_size;
                              $l_size_data = $left_ovary_data_size;
                          }
                          else
                          {
                              $r_size_data='';
                              $l_size_data='';
                          }
                        $right_ovary_data .= '<tr> 
                                    <td>'.date('d-m-Y',strtotime($rightovarydata['right_folli_date'])).'</td>
                                    <td>'.$rightovarydata['right_folli_day'].'</td>
                                    <td>'.$rightovarydata['right_folli_protocol'].'</td>
                                    <td>'.$rightovarydata['right_folli_pfsh'].'</td>
                                    <td>'.$rightovarydata['right_folli_recfsh'].'  </td>
                                    <td>'.$rightovarydata['right_folli_hmg'].'  </td>
                                    <td>'.$rightovarydata['right_folli_hp_hmg'].'  </td>
                                    <td>'.$rightovarydata['right_folli_agonist'].'  </td>
                                    <td>'.$rightovarydata['right_folli_antiagonist'].'  </td>
                                    <td>'.$rightovarydata['right_folli_trigger'].'  </td> 
                                    <td>'.$rightovarydata['endometriumothers'].'  </td>
                                    <td>'.$rightovarydata['e2'].'  </td>
                                    <td>'.$rightovarydata['p4'].'  </td>
                                    <td>'.$rightovarydata['risk'].'  </td>
                                    <td>'.$rightovarydata['others'].'  </td>
                                    
                                    <td>'.$r_size_data.'  </td>
                                    <td>'.$l_size_data.'  </td>
                                    
                                </tr>';
                         $i++;                
                      }
                      $right_ovary_data .= '</table>';
              }
              
              
            }
            
            
            
            if(strcmp(strtolower($tab_value->setting_name),'icsilab')=='0' && $tab_value->print_status!=0 && $print_icsilab_flag=='1')
            {
            
              if(!empty($tab_value->setting_value)) { $icsilab_name =  $tab_value->setting_value; } else { $icsilab_name =  $tab_value->var_title; }
               if(!empty($patient_icsilab_db_data))
               {
                    
                     $icsilab_data .='<h3 style="margin-bottom:0px;">'.$icsilab_name.':</h3>';
                    $icsilab_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
            
                       if(isset($patient_icsilab_db_data) && !empty($patient_icsilab_db_data))
                       {
                          $i = 1;
                          foreach($patient_icsilab_db_data as $key=>$patient_icsilab_val)
                          {
                               //echo "<pre>";  print_r($patient_icsilab_val); die;
                            
                              $icsilab_data .= '<li>
                                         <div><b>Date </b>'.$patient_icsilab_val['icsilab_date'].'</div>
                                         <div><b>Oocytes </b>'.$patient_icsilab_val['oocytes'].'</div>
                                         <div><b>M2 </b>'.$patient_icsilab_val['m2'].'</div>
                                         <div><b>Injected </b>'.$patient_icsilab_val['injected'].'</div>
                                         <div><b>Cleavge </b>'.$patient_icsilab_val['cleavge'].'</div>
                                         <div><b>Embryos Day3 </b>'.$patient_icsilab_val['embryos_day3'].'</div>
                                         <div><b>Day 5 </b>'.$patient_icsilab_val['day5'].'</div>
                                         <div><b>Day of ET </b>'.$patient_icsilab_val['day_of_et'].'</div>
                                         <div><b>Et </b>'.$patient_icsilab_val['et'].'</div>
                                         <div><b>VIT </b>'.$patient_icsilab_val['vit'].'</div>
                                         <div><b>LAH </b>'.$patient_icsilab_val['lah'].'</div>
                                         <div><b>Semen </b>'.$patient_icsilab_val['semen'].'</div>
                                         <div><b>Count </b>'.$patient_icsilab_val['count'].'</div>
                                         <div><b>Motility </b>'.$patient_icsilab_val['motility'].'</div>
                                         <div><b>G3 </b>'.$patient_icsilab_val['g3'].'</div>
                                         <div><b>Abn. </b>'.$patient_icsilab_val['abn_form'].'</div>
                                         <div><b>IMSI </b>'.$patient_icsilab_val['imsi'].'</div>
                                         <div><b>Pregnancy </b>'.$patient_icsilab_val['pregnancy'].'</div>
                                         <div><b>Remarks </b>'.$patient_icsilab_val['remarks'].'</div>
                                      </li>';
                                      
                                  $i++;               
                          } 
                       }
            
                       $icsilab_data .= '</ol>';
                       //$icsilab_data .= '</table>';
                       
                      // echo $icsilab_data; die;
                     
               }
            }
            //patient_antenatal_care_db_data
            if(strcmp(strtolower($tab_value->setting_name),'antenatal_care')=='0' && $tab_value->print_status!=0 && $print_antenatal_flag=='1')
            {
            
            if(!empty($patient_antenatal_care_db_data))
                 {
              if(!empty($tab_value->setting_value)) { $antenatal_care_name =  $tab_value->setting_value; } else { $antenatal_care_name =  $tab_value->var_title; }
                 //echo $antenatal_care_name.'poooo'; die;  
                 
              $antenatal_data .='<h3 style="margin-bottom:0px;">'.$antenatal_care_name.':</h3>';
            $antenatal_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
             
                       if(isset($patient_antenatal_care_db_data) && !empty($patient_antenatal_care_db_data))
                       {
                          $p = 1;
                          foreach($patient_antenatal_care_db_data as $key=>$patient_antenatal_care_val)
                          {
                              
                               $antenatal_data .= '<li>
                                         <div><b>Last Menstrual Period(LMP) </b>'.$patient_antenatal_care_val['antenatal_care_period'].'</div>
                                         <div><b>Expected Date of Delivery(EDD) </b>'.$patient_antenatal_care_val['antenatal_expected_date'].'</div>
                                         
                                         <div><b>Gestational Age(POG) </b>'.$patient_antenatal_care_val['antenatal_first_date'].'</div>
                                         
                                         <div><b>Remarks </b>'.$patient_antenatal_care_val['antenatal_remarks'].'</div></li>';
                                         
                           
                          } 
                       }
            
                       $antenatal_data .= '</ol>';
                       
                      // echo $icsilab_data; die;
                 }    
                     
            }
            
            if(strcmp(strtolower($tab_value->setting_name),'fertility')=='0' && $tab_value->print_status!=0&& !empty($list_fertility_data) && $print_fertility_flag=='1')
            {
            
              if(!empty($tab_value->setting_value)) { $fertility_name =  $tab_value->setting_value; } else { $fertility_name =  $tab_value->var_title; }
            
                      $fertility_data ='<h3 style="margin-bottom:0px;">'.$fertility_name.':</h3>';
                      
                      $fertility_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
                     
                        if(!empty($list_fertility_data))
                        {
                            $i=1;
                            //$fertility_data .= '<tbody>';
                            foreach($list_fertility_data as $fertility)
                            {
                                $fertility_sperm_date='';
                                if($fertility['fertility_sperm_date']!='0000-00-00' && $fertility['fertility_sperm_date'] !='1970-01-01')
                                {
                                    $fertility_sperm_date= $fertility['fertility_sperm_date'];
                                }
                                
                        $fertility_data .= '<li>
                            <div><b>CO </b>'.$fertility['fertility_co'].'</div>
                            <div><b>Risk </b>'.$fertility['fertility_risk'].'</div>
                            <div><b>Uterine Factor </b>'.$fertility['fertility_uterine_factor'].'</div>
                            <div><b>Tubal Factor </b>'.$fertility['fertility_tubal_factor'].'</div>
                            <div><b>Decision </b>'.$fertility['fertility_decision'].'</div>
                            <div><b>Ovarian Factor </b>'.$fertility['fertility_ovarian_factor'].'</div>
                            <div><b>Sperm </b>'.$fertility['fertility_male_factor'].'</div>
                            <div><b>Date </b>'.$fertility_sperm_date.'</div>
                            <div><b>Count </b>'.$fertility['fertility_sperm_count'].'</div>
                            <div><b>Motility </b>'.$fertility['fertility_sperm_motality'].'</div>
                            <div><b>G3 </b>'.$fertility['fertility_sperm_g3'].'</div>
                            <div><b>Abnormal form </b>'.$fertility['fertility_sperm_abnform'].'</div>
                            <div><b>Remarks </b>'.$fertility['fertility_sperm_remarks'].'</div>
                            
                            </li>';
                                         
                              
                            }
                            
                        }
                        $fertility_data .= '</ol>';
                      
            }
            
            
            }
            //old setting data 
            
            
            
            
               //$next_appointment_date='';
                //echo $medicine;die;
               $signature='Dr. '.get_doctor_name($all_detail['prescription_list'][0]->attended_doctor);
               $remark='';
               $suggestion='';
                $template_data = str_replace("{patient_history}",$patient_history,$template_data);
                $template_data = str_replace("{family_history}",$family_history,$template_data);
                $template_data = str_replace("{personal_history}",$personal_history,$template_data);
                $template_data = str_replace("{menstrual_history}",$menstrual_history,$template_data);
                $template_data = str_replace("{medical_history}",$medical_history_p,$template_data);
                $template_data = str_replace("{obestetric_history}",$obestetric_history,$template_data);
                $template_data = str_replace("{current_medicine}",$current_medicine,$template_data);
                $template_data = str_replace("{appointment_date}",$next_appointment_date,$template_data);
                $template_data = str_replace("{signature}",$signature,$template_data);
                $template_data = str_replace("{disease}",$disease_data,$template_data);
                $template_data = str_replace("{complaints}",$complaints_data,$template_data);
                $template_data = str_replace("{allergy}",$allergy_data,$template_data);
                $template_data = str_replace("{general_examination}",$general_examination_data,$template_data);
                $template_data = str_replace("{clinical_examination}",$clinical_examination_data,$template_data);
                $template_data = str_replace("{investigation}",$investigation_data,$template_data);
                //$template_data = str_replace("{medicine}",$medicine_data,$template_data);
                $template_data = str_replace("{advice}",$advice_data,$template_data);
            
                $template_data = str_replace("{medicine_data}",$medicine,$template_data);
                $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
                $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
                $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
                $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
                $template_data = str_replace("{suggestion}",$suggestion,$template_data);
                $template_data = str_replace("{remark}",$remark,$template_data);
                $template_data = str_replace("{icsilab_data}",$icsilab_data,$template_data);
               
                $template_data = str_replace("{antenatal_data}",$antenatal_data,$template_data);
                
                //echo $icsilab_data; die;
                
                $template_data = str_replace("{follicular_scaning_right}",$right_ovary_data,$template_data);
                $template_data = str_replace("{follicular_scaning_left}",$left_ovary_data,$template_data);
                
                $template_data = str_replace("{fertility_data}",$fertility_data,$template_data);

            
          $final_template_data .= $template_data;  
        } ///end of gynic
        elseif($pres_detail->flag=='4') ///start pedic
        {
            ////////////////////////////////pedic /////////////////////////
            
                $type = 2;
                $prescription_id = $pres_detail->flag_id;
                $branch_id = $pres_detail->branch_id;
                $page_title = "Print Prescription";
                $opd_prescription_info = get_pediatrician_detail_by_prescription_id($prescription_id);
                //echo '<pre>'; print_r($opd_prescription_info);die;
                
                $template_format = pediatrician_template_format(array('setting_name'=>'PRESCRIPTION_PRINT_SETTING','unique_id'=>5,'type'=>0),$branch_id);
               
                $template_format_left = pediatrician_template_format(array('setting_name'=>'LEFTMARGIN','unique_id'=>1,'type'=>0),$branch_id);
                
                $template_format_right = pediatrician_template_format(array('setting_name'=>'RIGHTMARGINS','unique_id'=>2,'type'=>0),$branch_id);
                
                $template_format_top = pediatrician_template_format(array('setting_name'=>'TOPMARGIN','unique_id'=>3,'type'=>0),$branch_id);
                
                $template_format_bottom = pediatrician_template_format(array('setting_name'=>'BOTTOMMARGINS','unique_id'=>4,'type'=>0),$branch_id);
                
                $template_data_left=$template_format_left->setting_value;
                $template_format_right=$template_format_right->setting_value;
                $template_format_top=$template_format_top->setting_value;
                $template_format_bottom=$template_format_bottom->setting_value;
                
                $vitals_list=gynic_vitals_list();
                $template_data=$template_format->setting_value;
                // echo "<pre>"; print_r($opd_prescription_info); exit;
                $all_detail= $opd_prescription_info;
        
                $prescription_tab_setting = get_pediatrician_prescription_tab_setting('',$branch_id);
                //echo "<pre>";print_r($data['prescription_tab_setting']); exit;
                $prescription_medicine_tab_setting = get_pediatrician_prescription_medicine_tab_setting('',$branch_id);
                //echo "<pre>";print_r($data['all_detail']['prescription_list'][0]); exit;
                $signature_image = get_doctor_signature($all_detail['prescription_list'][0]->attended_doctor);
                
           $signature_reprt_data ='';
           if(!empty($signature_image))
           {
           
             $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
              <tr>
              <td width="70%"></td>
                <td valign="top" align="" style="text-align:center;"><b>Signature</b></td>
              </tr>';
              
              if(!empty($signature_image->sign_img) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_image->sign_img))
              {
              
              $signature_reprt_data .='<tr>
              <td width="70%"></td>
                <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_image->sign_img.'"></div></td>
              </tr>';
              
               }
               
              $signature_reprt_data .='<tr>
              <td width="70%"></td>
                <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">'.nl2br($signature_image->signature).'</b></td>
              </tr>
              
            </table>';
        
           }
           $signature = $signature_reprt_data;
            
           ///controller
           
           $simulation = get_simulation_name($all_detail['prescription_list'][0]->simulation_id);
            $template_data = str_replace("{patient_name}",$simulation.' '.$all_detail['prescription_list'][0]->patient_name,$template_data);
        
            $address = $all_detail['prescription_list'][0]->address;
            $pincode = $all_detail['prescription_list'][0]->pincode;
            $booking_date_time='';
            if(!empty($all_detail['prescription_list'][0]->booking_date) && $all_detail['prescription_list'][0]->booking_date!='0000-00-00')
        {
            $booking_date_time = date('d-m-Y',strtotime($all_detail['prescription_list'][0]->booking_date)); 
        }
        
            $booking_time ='';
            if(!empty($all_detail['prescription_list'][0]->booking_time) && $all_detail['prescription_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['prescription_list'][0]->booking_time)>0)
            {
                $booking_time = date('h:i A', strtotime($all_detail['prescription_list'][0]->booking_time));    
            }
            
            $template_data = str_replace("{booking_time}",$booking_time,$template_data);
               //.' '.$all_detail['prescription_list'][0]->booking_time   
            
            $patient_address = $address.' - '.$pincode;
        
            $template_data = str_replace("{patient_address}",$patient_address,$template_data);
        
            $template_data = str_replace("{patient_reg_no}",$all_detail['prescription_list'][0]->patient_code,$template_data);
        
            $template_data = str_replace("{mobile_no}",$all_detail['prescription_list'][0]->mobile_no,$template_data);
            
            $template_data = str_replace("{booking_code}",$all_detail['prescription_list'][0]->booking_code,$template_data);
            $template_data = str_replace("{booking_date}",$booking_date_time,$template_data);
            $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($all_detail['prescription_list'][0]->attended_doctor),$template_data);
        
            $template_data = str_replace("{ref_doctor_name}",get_doctor_name($all_detail['prescription_list'][0]->referral_doctor),$template_data);
             $template_data = str_replace("{doctor_qualification}",get_doctor_qualifications($all_detail['prescription_list'][0]->attended_doctor),$template_data);
            $spec_name='';
            $specialization = get_specilization_name($all_detail['prescription_list'][0]->specialization_id);
            if(!empty($specialization))
            {
                $spec_name= str_replace('(Default)','',$specialization);
            }
        
            $template_data = str_replace("{specialization}",$spec_name,$template_data);
        
               if(!empty($all_detail['prescription_list'][0]->relation))
                {
                $rel_simulation = get_simulation_name($all_detail['prescription_list'][0]->relation_simulation_id);
                $template_data = str_replace("{parent_relation_type}",$all_detail['prescription_list'][0]->relation,$template_data);
                }
                else
                {
                 $template_data = str_replace("{parent_relation_type}",'',$template_data);
                }
        
            if(!empty($all_detail['prescription_list'][0]->relation_name))
                {
                $rel_simulation = get_simulation_name($all_detail['prescription_list'][0]->relation_simulation_id);
                $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['prescription_list'][0]->relation_name,$template_data);
                }
                else
                {
                 $template_data = str_replace("{parent_relation_name}",'',$template_data);
                } 
                
                $template_data = str_replace("{aadhaar_no}",$all_detail['prescription_list'][0]->adhar_no,$template_data);
                
                if($all_detail['prescription_list'][0]->dob!='0000-00-00')
                    {
                        $dob=date('d-m-Y' ,strtotime($all_detail['prescription_list'][0]->dob));
                        $template_data = str_replace("{dob}", $dob, $template_data);
                    }
                    else
                    {
                        $template_data = str_replace("{dob}",'-', $template_data);   
                    }
                    
                
            /*$template_data = str_replace("{patient_bp}",$all_detail['prescription_list'][0]->patientbp,$template_data);
        
            $template_data = str_replace("{patient_temp}",$all_detail['prescription_list'][0]->patienttemp,$template_data);
        
            $template_data = str_replace("{patient_weight}",$all_detail['prescription_list'][0]->patientweight,$template_data);
        
            $template_data = str_replace("{patient_pr}",$all_detail['prescription_list'][0]->patientpr,$template_data);
        
            $template_data = str_replace("{patient_spo}",$all_detail['prescription_list'][0]->patientspo,$template_data);
            $template_data = str_replace("{patient_rbs}",$all_detail['prescription_list'][0]->patientrbs,$template_data);*/
        
         $patient_vital = '';
            if(!empty($vitals_list))
            {
                foreach ($vitals_list as $vitals) 
                {
                   // echo $vitals->id.'-'.$prescription_id.'-'.$type.'<br>';
                    $vitals_val = get_vitals_value($vitals->id,$prescription_id,$type);
                    $template_data = str_replace("{".$vitals->short_code."}",$vitals_val,$template_data);
                }
            } 
           // die();   
            $patient_vital = '<table border="" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
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
                                <div style="float:left;min-height:20px;text-align: center;padding-left: 4px;">'.$vital_val.'</div>
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
        
        //$patient_vital='';
         $template_data = str_replace("{vitals}",$patient_vital,$template_data);
           
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
            foreach ($prescription_tab_setting as $value) {   
        
            if(strcmp(strtolower($value->setting_name),'test_result')=='0')
            {  
                if(!empty($all_detail['prescription_list']['prescription_test_list']))
                {
                    if(!empty($value->setting_value)) { $Test_names =  $value->setting_value; } else { $Test_names =  $value->var_title; }
                    $patient_test_name .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;">'.$Test_names.':</div>';
                        
                      $patient_test_name .= '<div style="float:left;width:100%;margin:1% 0 0;border:1px solid #808080;font:12px Arial;">
                <div style="float:left;width:10%;height:25px;line-height:25px;border-right:1px solid #808080;padding:0 0 0 5px;"><b>Sr.No.</b></div>
                <div style="float:left;width:90%;height:25px;line-height:25px;padding:0 0 0 5px;"><b>Test Name</b></div>
            </div>';
        
                    $j=1;
                    
                    foreach($all_detail['prescription_list']['prescription_test_list'] as $prescription_testname)
                    { 
                    	 
                    $patient_test_name .= '<div style="float:left;width:100%;border:1px solid #808080;border-top:none;font:12px Arial;">
                <div style="float:left;width:10%;height:25px;line-height:25px;border-right:1px solid #808080;padding:0 0 0 15px;">'.$j.'</div>
                <div style="float:left;width:90%;height:25px;line-height:25px;padding:0 0 0 5px;">'.$prescription_testname->test_name.'</div>
            </div>';
                     $j++; 	 	 	 	
                    }
                    }
            
            }
        
        
            if(strcmp(strtolower($value->setting_name),'prescription')=='0')
            {
        
                    if(!empty($all_detail['prescription_list']['prescription_presc_list']))
                    {
                      
                      if(!empty($value->setting_value)) { $pre_names =  $value->setting_value; } else { $pre_names =  $value->var_title; }
                    $patient_pres .= '<div style="width:100%;font-size:small;font-weight:bold;text-align: left;margin-top:1%;text-decoration:underline;float:left;">'.$pre_names.':</div>';  
        
                    $i=1;
                    
                    $patient_pres .= '<table border="1" width="100%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;float:left;">';
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
                      
                     
                        
                    foreach($all_detail['prescription_list']['prescription_presc_list'] as $prescription_presc)
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
        
                    if(!empty($all_detail['prescription_list'][0]->prv_history)){ 
                    $prv_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Prv_history.' :</b>
                       '.nl2br($all_detail['prescription_list'][0]->prv_history).'
                      </div>
                    </div>';
                    }
            }
        
            
        
        
            if(strcmp(strtolower($value->setting_name),'personal_history')=='0')
            {
                if(!empty($value->setting_value)) { $Personal_history =  $value->setting_value; } else { $Personal_history =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->personal_history)){ 
                $personal_history = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Personal_history.' :</b>
                   '.nl2br($all_detail['prescription_list'][0]->personal_history).'
                  </div>
                </div>';
                }
            }
        
        
        
            if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0')
            {   
                if(!empty($value->setting_value)) { $Chief_complaints =  $value->setting_value; } else { $Chief_complaints =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->chief_complaints)){ 
                $chief_complaints = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;"><b>'.$Chief_complaints.':</b>
                   '.nl2br($all_detail['prescription_list'][0]->chief_complaints).'
                  </div>
                </div>';
                }
            }
        
            
        
            if(strcmp(strtolower($value->setting_name),'examination')=='0')
            {
                if(!empty($value->setting_value)) { $Examination =  $value->setting_value; } else { $Examination =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->examination)){ 
                $examination = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Examination.' :</b>
                   '.nl2br($all_detail['prescription_list'][0]->examination).'
                  </div>
                </div>';
                }
            }
                
            
        
        
            if(strcmp(strtolower($value->setting_name),'diagnosis')=='0')
            { 
                if(!empty($value->setting_value)) { $Diagnosis =  $value->setting_value; } else { $Diagnosis =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->diagnosis)){ 
                $diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Diagnosis.' :</b>
                   '.nl2br($all_detail['prescription_list'][0]->diagnosis).'
                  </div>
                </div>';
                }
            }
                
        
        
        
            if(strcmp(strtolower($value->setting_name),'suggestions')=='0')
            {
                if(!empty($value->setting_value)) { $Suggestion =  $value->setting_value; } else { $Suggestion =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->suggestion)){ 
                $suggestion = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b> '.$Suggestion.' :</b>'.nl2br($all_detail['prescription_list'][0]->suggestion).'
                  
                  </div>
                </div>';
                }
            }    
            
        
        
            if(strcmp(strtolower($value->setting_name),'remarks')=='0')
            { 
                if(!empty($value->setting_value)) { $Remark =  $value->setting_value; } else { $Remark =  $value->var_title; }
                if(!empty($all_detail['prescription_list'][0]->remark)){ 
                $remark = '<div style="float:left;width:100%;margin:1em 0 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Remark.' :</b>
                   '.nl2br($all_detail['prescription_list'][0]->remark).'
                  </div>
                </div>';
                }
            }
            
           
           if(strcmp(strtolower($value->setting_name),'appointment')=='0')
           {
               if(!empty($value->setting_value)) 
               { 
                    $Appointment_date =  $value->setting_value; } else { $Appointment_date =  $value->var_title; 
                }
               if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970')
               {
                if(!empty($all_detail['prescription_list'][0]->next_appointment_date))
                {
                  $naDate = date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date));
                  $next_app .= '<div style="float:left;width:100%;margin:1em 0;">
                  <div style="font-size:small;line-height:18px;">
                    <b>'.$Appointment_date.' :</b>
                    '.$naDate.'
                  </div>
                </div>';
                } else {
                  $next_app = "";
                }
                
               }
            }
        
         // echo  date('d-m-Y',strtotime($form_data['next_appointment_date']));
        
          
        
            
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
            $template_data = str_replace("{validity_date}",'',$template_data);
            
            
        
        $final_template_data .= $template_data;      
        }//end of padic
        
        
        
        $m++;  
        }
     
     echo $final_template_data; //die;
}
    

?>