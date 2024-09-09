
<?php 
/* start thermal printing */
    
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

    $template_data = str_replace("{specialization}",get_specilization_name($all_detail['prescription_list'][0]->specialization_id),$template_data);

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
    /*$template_data = str_replace("{patient_bp}",$all_detail['prescription_list'][0]->patientbp,$template_data);

    $template_data = str_replace("{patient_temp}",$all_detail['prescription_list'][0]->patienttemp,$template_data);

    $template_data = str_replace("{patient_weight}",$all_detail['prescription_list'][0]->patientweight,$template_data);

    $template_data = str_replace("{patient_pr}",$all_detail['prescription_list'][0]->patientpr,$template_data);

    $template_data = str_replace("{patient_spo}",$all_detail['prescription_list'][0]->patientspo,$template_data);
    $template_data = str_replace("{patient_rbs}",$all_detail['prescription_list'][0]->patientrbs,$template_data);*/

/*
    if(!empty($vitals_list))
    {
        foreach ($vitals_list as $vitals) 
        {
            $vitals_val = get_vitals_value($vitals->id,$prescription_id,$type);
            $template_data = str_replace("{".$vitals->short_code."}",$vitals_val,$template_data);
        }
    }    
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
*/
$patient_vital='';
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
    
   
   /*if(strcmp(strtolower($value->setting_name),'appointment')=='0')
   {
       if(!empty($value->setting_value)) 
       { 
            $Appointment_date =  $value->setting_value; } else { $Appointment_date =  $value->var_title; 
        }
       if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00')
       {
        $next_app .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Appointment_date.' :</b>
            '.date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)).'
          </div>
        </div>';
       }
    }*/

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
    

    echo $template_data; 

$this->session->unset_userdata('opd_prescription_id');
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