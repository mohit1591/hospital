<link rel="stylesheet" type="text/css" href="http://192.168.1.240/hmas/assets/css/font-awesome.min.css">
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
    $address_re='';
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

    $template_data = str_replace("{specialization}",get_specilization_name($all_detail['prescription_list'][0]->specialization_id),$template_data);

                      
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
    $systemic_illness="";
    $pictorial_test='';
    $biometric_test="";
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

                if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
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

                 if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
                { 
                if(!empty($tab_value->setting_value)) { $left_eye =  $tab_value->setting_value; } else { $left_eye =  $tab_value->var_title; }   
                $patient_pres .= '<th>'.$left_eye.'</th>';

                }
                if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
                { 
                if(!empty($tab_value->setting_value)) { $right_eye =  $tab_value->setting_value; } else { $right_eye =  $tab_value->var_title; }   
                $patient_pres .= '<th>'.$right_eye.'</th>';

                }

                if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                { 
                if(!empty($tab_value->setting_value)) { $Advice =  $tab_value->setting_value; } else { $Advice =  $tab_value->var_title; }   
                $patient_pres .= '<th>'.$Advice.'</th>';

                }
                if(strcmp(strtolower($tab_value->setting_name),'date')=='0')
                { 
                if(!empty($tab_value->setting_value)) { $date_val =  $tab_value->setting_value; } else { $date_val =  $tab_value->var_title; }   
                $patient_pres .= '<th>'.$date_val.'</th>';

                }

            }    
             $patient_pres .= '</thead><tbody>';
              
             
                $lef_eye='';
                $right_eye='';
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

                if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
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
                if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
                { 
                   if($prescription_presc->left_eye==1)
                   {
                    $lef_eye='<i class="fa fa-eye"></i>';
                   }
                   else
                   {
                    $lef_eye='';
                   }
                  $patient_pres .= '<td>'.$lef_eye.'</td>';

                }
                if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
                { 
                   if($prescription_presc->right_eye==2)
                   {
                    $right_eye='<i class="fa fa-eye"></i>';
                   }
                   else
                   {
                    $right_eye='';
                   }
                $patient_pres .= '<td>'.$right_eye.'</td>';

                }
                if(strcmp(strtolower($tab_value->setting_name),'date')=='0')
                { 
                   if($prescription_presc->date=='1970-01-01')
                   {
                    $date_new_val='';
                   }
                   else
                   {
                    $date_new_val=date('d-m-Y',strtotime($prescription_presc->date));
                   }
                $patient_pres .= '<td>'.$date_new_val.'</td>';

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
       // print '<pre>'; print_r($all_detail['prescription_list']['cheif_compalin']);die;

        if(!empty($all_detail['prescription_list']['cheif_compalin']))
        {
            if(!empty($value->setting_value)) { $Chief_complaints =  $value->setting_value; } else { $Chief_complaints =  $value->var_title; }
         $chief_complaints='<div style="font-size:small;line-height:18px;"><b>'.$Chief_complaints.':</b>
       
        </div>';
        $chief_complaints.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Complaint Description</td>
            <td align="center" height="30">L</td>
            <td align="center" height="30">R</td>
            <td align="center" height="30" colspan="2">Duration</td>
        </tr>
        <tr>
            <td align="center" height="30"></td>
            <td align="center" height="30"></td>
            <td align="center" height="30"></td>
            <td align="center" height="30">Days</td>
            <td align="center" height="30">Time</td>
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['cheif_compalin'] as $cheif_c_data)
       {
        $chief_complaints.='<tr>
            <td align="left" height="30">'.$cheif_c_data->cheif_complains.'</td>';
               if($cheif_c_data->left_eye==1)
            {
                $eye_su_l='<i class="fa fa-eye"></i>';
            }
            else
            {
                $eye_su_l='';
            }
            $chief_complaints.='<td align="center" height="30">'.$eye_su_l;
         

             $chief_complaints.='</td>';
              if($cheif_c_data->right_eye==2)
            {
                $eye_su= '<i class="fa fa-eye"></i>';
            }
            else
            {
               $eye_su='';
            }
            $chief_complaints.='<td align="center" height="30">'.$eye_su;
           
            $chief_complaints.='</td>';

            $chief_complaints.='<td align="center" height="30">'.$cheif_c_data->days;
               
            $chief_complaints.='</td>';
             if($cheif_c_data->time==1)
            {
                $time_var= 'Days';
            }
            
            if($cheif_c_data->time==2)
            {
                 $time_var='Week';
            }
            
            if($cheif_c_data->time==3)
            {
                 $time_var='Month';
            }
           
             if($cheif_c_data->time==4)
            {
                 $time_var='Year';
            }
            
             $chief_complaints.='<td align="center" height="30">'.$time_var;
           
            $chief_complaints.='</td>';
        $chief_complaints.='</tr>';
       }
        
       
     $chief_complaints.='</table>
</div>';

    }
}




/* pictorial test */
if(strcmp(strtolower($value->setting_name),'pictorial_test')=='0')
    {  
 
            if(!empty($value->setting_value)) { $pictor =  $value->setting_value; } else { $pictor =  $value->var_title; }
         $pictorial_test='<div style="font-size:small;line-height:18px;"><b>'.$pictor.':</b>
       
        </div>';

 $pictorial_test.='<div class="printPage">
    <div class="box">
      <table width="65%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
          <td align="left" valign="middle">
            
            <div>Right Eye</div>';
              //echo $all_detail->right_eye_image;
                $img_path = base_url('assets/images/photo.png');
                if(!empty($all_detail['prescription_list'][0]->right_eye_image) && !empty($all_detail['prescription_list'][0]->right_eye_image)){
                $img_path = ROOT_UPLOADS_PATH.'eye/right_eye_image/'.$all_detail['prescription_list'][0]->right_eye_image;
                }  

                   $img_path_left = base_url('assets/images/photo.png');
                if(!empty($all_detail['prescription_list'][0]->left_eye_image) && !empty($all_detail['prescription_list'][0]->left_eye_image)){
                $img_path_left = ROOT_UPLOADS_PATH.'eye/left_eye_image/'.$all_detail['prescription_list'][0]->left_eye_image;
                }  
                                                  

            $pictorial_test.='<div class="frame"> <img id="pimg2" src="'.$img_path.'" class="img-responsive frame" width="100px;"/></div>
           
          </td>
          <td align="left" valign="middle">
             
            <div>Left Eye</div>
            <div class="frame"><img id="pimg2" src="'.$img_path_left.'" class="img-responsive frame" width="100px;"/></div>
             
          </td>
        </tr>
      </table>
      
  </div>
    <div class="box">
      <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
          <td>
            <div>Right Eye Discussion</div>
            <div class="ri8Frame">'.$all_detail['prescription_list'][0]->right_eye_discussion.'</div>
          </td>
        </tr>
        <tr>
          <td>
            <div>Left Eye Discussion</div>
            <div class="ri8Frame">'.$all_detail['prescription_list'][0]->left_eye_discussion.'</div>
          </td>
        </tr>
      </table>
  </div>
</div>
';

}
/* pictorial test */

/* biometric test */
if(strcmp(strtolower($value->setting_name),'biometric_detail')=='0')
    {  
    if(!empty($ucva_bcva_data) || !empty($keratometer_data) || !empty($iol_data))
    {
        
         if(!empty($value->setting_value)) { $biometeric =  $value->setting_value; } else { $biometeric =  $value->var_title; }
         $biometric_test='<div style="font-size:small;line-height:18px;"><b>'.$biometeric.':</b>
       
        </div>';
    
 $biometric_test.=' <div class="well" style="float:left;width:100%;"><form method="post" id="biometric_form" ><div class="grid-frame2"><div class="grid-box" style="margin-left:21%;width: 57%;"> <h5>DVA</h5><div class="tbl_responsive"><table class="tbl_grid" cellpadding="0" cellspacing="0" border="1">';

  $biometric_test.='<tr><td rowspan="3" width="50" align="center" valign="bottom">R</td><td colspan="2" align="center" height="30">UCVA</td> <td colspan="7" align="center" height="30">BCVA</td> </tr>';

 $biometric_test.='<tr><td align="center" height="30">NVA</td><td align="center" height="30">DVA</td><td align="center" height="30">SPH</td><td align="center" height="30">CYL</td> <td align="center" height="30">AXIS</td> <td align="center" height="30">ADD</td><td align="center" height="30">DVA</td> <td align="center" height="30">NVA</td></tr>';

 $biometric_test.=' <tr>'; 
  if($ucva_bcva_data!='empty') { $ucva_nva_right= $ucva_bcva_data[0]->ucva_nva_right; } else{$ucva_nva_right='';}


 $biometric_test.='<td height="30">'.$ucva_nva_right.'</td>';
   if($ucva_bcva_data!='empty') { $ucva_dva_right= $ucva_bcva_data[0]->ucva_dva_right; } else{$ucva_dva_right='';}


 $biometric_test.=' <td>'.$ucva_dva_right.'</td>';
 if($ucva_bcva_data!='empty') { $bcva_sph_right= $ucva_bcva_data[0]->bcva_sph_right; }else{$bcva_sph_right='';}


  $biometric_test.=' <td>'.$bcva_sph_right.'</td>';

  if($ucva_bcva_data!='empty') { $bcva_cyl_right= $ucva_bcva_data[0]->bcva_cyl_right;}else{$bcva_cyl_right='';}


 $biometric_test.='<td>'.$bcva_cyl_right.'</td>';
 if($ucva_bcva_data!='empty') { $bcva_axis_right= $ucva_bcva_data[0]->bcva_axis_right; }else{$bcva_axis_right='';}
 $biometric_test.='<td>'.$bcva_axis_right.'</td>';
if($ucva_bcva_data!='empty') { $bcva_add_right= $ucva_bcva_data[0]->bcva_add_right;}else{$bcva_add_right='';}

 $biometric_test.='<td>'.$bcva_add_right.'</td>';
 if($ucva_bcva_data!='empty') { $bcva_dva_right=$ucva_bcva_data[0]->bcva_dva_right;}else{$bcva_dva_right='';}

 $biometric_test.='<td>'.$bcva_dva_right.'</td>';

if($ucva_bcva_data!='empty') { $bcva_nva_right= $ucva_bcva_data[0]->bcva_nva_right;}else{$bcva_nva_right='';}

$biometric_test.='<td>'.$bcva_nva_right.'</td> ';
 $biometric_test.='</tr>';
$biometric_test.='<tr> ';
$biometric_test.=' <td width="50" height="30" align="center">L</td>';
if($ucva_bcva_data!='empty') { $ucva_nva_left= $ucva_bcva_data[0]->ucva_nva_left;}else{$ucva_nva_left='';}
$biometric_test.='<td>'.$ucva_nva_left.'</td>';

if($ucva_bcva_data!='empty') { $ucva_dva_left= $ucva_bcva_data[0]->ucva_dva_left;}else{$ucva_dva_left='';}

$biometric_test.='<td>'.$ucva_dva_left.'</td>';

if($ucva_bcva_data!='empty') { $bcva_sph_left= $ucva_bcva_data[0]->bcva_sph_left;}else{$bcva_sph_left='';}

$biometric_test.='<td>'.$bcva_sph_left.'</td>';
if($ucva_bcva_data!='empty') { $bcva_cyl_left= $ucva_bcva_data[0]->bcva_cyl_left;}else{$bcva_cyl_left='';}

$biometric_test.='<td>'.$bcva_cyl_left.'</td>';
if($ucva_bcva_data!='empty') { $bcva_axis_left=$ucva_bcva_data[0]->bcva_axis_left; }else{$bcva_axis_left='';}

$biometric_test.='<td>'.$bcva_axis_left.'</td>';

 if($ucva_bcva_data!='empty') { $bcva_add_left= $ucva_bcva_data[0]->bcva_add_left; } else{$bcva_add_left='';}

$biometric_test.='<td>'.$bcva_add_left.'</td>';

if($ucva_bcva_data!='empty') { $bcva_dva_left=$ucva_bcva_data[0]->bcva_dva_left;} else{$bcva_dva_left='';}

$biometric_test.='<td>'.$bcva_dva_left.'</td>';

if($ucva_bcva_data!='empty') { $bcva_nva_left= $ucva_bcva_data[0]->bcva_nva_left; } else{$bcva_nva_left='';}
$biometric_test.=' <td>'.$bcva_nva_left.'</td>';
$biometric_test.='</tr> </table> </div></div></div>';
                                 
$biometric_test.=' <div class="grid-frame2" style="display:flex;justify-content:space-between;margin-top:1em;"><div class="grid-box" style="width:47%;height:auto;border:1px solid #aaa;padding:0px;overflow-y:auto;"><div class="grid-head">Keratometer Readings</div>';
$biometric_test.='<div class="grid-body">';
                            if($keratometer_data!="empty") {
$biometric_test.='<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
$biometric_test.='<tr>';
$biometric_test.='<td width="15%" height="30"  align="center">RE</td>
                                  <td></td>
                                  <td width="15%" height="30"  align="center">LE</td>';
 $biometric_test.='</tr>'; 
        $i=1;

        foreach($keratometer_data as $data) 
        { 
                $right_val="";
                $left_val="";
                if($keratometer_details!="empty")
                {
                foreach($keratometer_details as $dt)
                {
                if($dt->kera_id==$data->id)
                {
                $right_val=$dt->right_eye;
                $left_val=$dt->left_eye;
                }
                } 
                }
                                
$biometric_test.='<tr style="margin-top:15px;">';
$biometric_test.='<td width="15%"  align="center">'.$right_val.'</td>';
$biometric_test.='<td align="center">'.$data->keratometer.'</td>';
$biometric_test.='<td width="15%"  align="center">'.$left_val.'</td> </tr>';
                                  $i++; } 
$biometric_test.='</table>';
}
$biometric_test.='</div></div>';
$biometric_test.='<div class="grid-box" style="width:47%;height:auto;border:1px solid #aaa;padding:0px;overflow-y:auto;">
                                  <div class="grid-head">IOL Section</div>
                                  <div class="grid-body">';
                               if($iol_data!="empty") {
$biometric_test.='<table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
$biometric_test.='<tr><td width="15%" height="30" align="center">RE</td>
                                  <td></td>
                                  <td width="15%" height="30"  align="center">LE</td>
                                  </tr>';
                                 foreach($iol_data as $iol) 
                                  { 
                                  $right_val="";
                                  $left_val="";
                                  if($iol_details!="empty")
                                  {
                                  foreach($iol_details as $dt)
                                  {
                                  if($dt->iol_id==$iol->id)
                                  {
                                  $right_val=$dt->right_eye;
                                  $left_val=$dt->left_eye;
                                  }
                                  } 
                                  }
                               
$biometric_test.='<tr><td width="15%" align="center">'.$right_val.'</td>
                                  <td align="center">'.$iol->iol_section.'</td>
                                  <td width="15%" align="center">'.$left_val.'</td>';
$biometric_test.='</tr>';} 
$biometric_test.='</table>';
} 
$biometric_test.='</div> </div></form> </div>
';
}
}
/* biometric test  */


  if(strcmp(strtolower($value->setting_name),'diagnosis')=='0')
    {   
        //print_r($all_detail['prescription_list']['cheif_compalin']);die;

        if(!empty($all_detail['prescription_list']['diagnosis_list']))
        {
            if(!empty($value->setting_value)) { $Diagnosis =  $value->setting_value; } else { $Diagnosis =  $value->var_title; }
         $diagnosis ='<div style="font-size:small;line-height:18px;"><b>'.$Diagnosis.':</b>
       
        </div>';
         $diagnosis.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Diagnosis</td>
            <td align="center" height="30">L</td>
            <td align="center" height="30">R</td>
            <td align="center" height="30" colspan="2">Duration</td>
        </tr>
        <tr>
            <td align="center" height="30"></td>
            <td align="center" height="30"></td>
            <td align="center" height="30"></td>
            <td align="center" height="30">Days</td>
            <td align="center" height="30">Time</td>
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['diagnosis_list'] as $diagnos_li)
       {
        $diagnosis.='<tr>
            <td align="left" height="30">'.$diagnos_li->diagnosis.'</td>';
              if($diagnos_li->left_eye==1)
            {
                $eye_su_l='<i class="fa fa-eye"></i>';
            }
            else
            {
                 $eye_su_l='';
            }
            $diagnosis.='<td align="center" height="30">'.$eye_su_l;
          

             $diagnosis.='</td>';
              if($diagnos_li->right_eye==2)
            {
                $eye_su= '<i class="fa fa-eye"></i>';
            }
            else
            {
               $eye_su='';
            }
            $diagnosis.='<td align="center" height="30">'.$eye_su;
           
            $diagnosis.='</td>';

            $diagnosis.='<td align="center" height="30">'.$diagnos_li->days;
               
            $diagnosis.='</td>';
             if($diagnos_li->time==1)
            {
                $time_var= 'Days';
            }
            
            if($diagnos_li->time==2)
            {
                 $time_var='Week';
            }
            
            if($diagnos_li->time==3)
            {
                 $time_var='Month';
            }
           
             if($diagnos_li->time==4)
            {
                 $time_var='Year';
            }
            
             $diagnosis.='<td align="center" height="30">'.$time_var;
           
            $diagnosis.='</td>';
        $diagnosis.='</tr>';
       }
        
       
     $diagnosis.='</table>
</div>';

    }
}

  if(strcmp(strtolower($value->setting_name),'systemic_illness')=='0')
    {   
        //print_r($all_detail['prescription_list']['systemic_illness']);die;

        if(!empty($all_detail['prescription_list']['systemic_illness']))
        {
            if(!empty($value->setting_value)) { $systemic_ill =  $value->setting_value; } else { $systemic_ill =  $value->var_title; }
         $systemic_illness ='<div style="font-size:small;line-height:18px;"><b>'.$systemic_ill.':</b>
       
        </div>';
         $systemic_illness.='<div class="grid-frame3">
    <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
            <td align="left" height="30">Systemic Illness</td>
            
            <td align="center" height="30" colspan="2">Duration</td>
        </tr>
        <tr>
            <td align="center" height="30"></td>
           
            <td align="center" height="30">Days</td>
            <td align="center" height="30">Time</td>
        </tr>';
        $time_var='';
       foreach($all_detail['prescription_list']['systemic_illness'] as $systemic_illness_li)
       {
        $systemic_illness.='<tr>
            <td align="left" height="30">'.$systemic_illness_li->systemic_illness.'</td>';
            
          

            $systemic_illness.='<td align="center" height="30">'.$systemic_illness_li->days;
               
            $systemic_illness.='</td>';
             if($systemic_illness_li->time==1)
            {
                $time_var= 'Days';
            }
            
            if($systemic_illness_li->time==2)
            {
                 $time_var='Week';
            }
            
            if($systemic_illness_li->time==3)
            {
                 $time_var='Month';
            }
           
             if($systemic_illness_li->time==4)
            {
                 $time_var='Year';
            }
            
             $systemic_illness.='<td align="center" height="30">'.$time_var;
           
            $systemic_illness.='</td>';
        $systemic_illness.='</tr>';
       }
        
       
     $systemic_illness.='</table>
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
        
    


    // if(strcmp(strtolower($value->setting_name),'diagnosis')=='0')
    // { 
    //     if(!empty($value->setting_value)) { $Diagnosis =  $value->setting_value; } else { $Diagnosis =  $value->var_title; }
    //     if(!empty($all_detail['prescription_list'][0]->diagnosis)){ 
    //     $diagnosis = '<div style="float:left;width:100%;margin:1em 0 0;">
    //       <div style="font-size:small;line-height:18px;">
    //         <b>'.$Diagnosis.' :</b>
    //        '.nl2br($all_detail['prescription_list'][0]->diagnosis).'
    //       </div>
    //     </div>';
    //     }
    // }
        



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
   // print_r($all_detail['prescription_list'][0]);die;
   //echo date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date)); 
   if(strcmp(strtolower($value->setting_name),'appointment')=='0')
   {
       if(!empty($value->setting_value)) 
       { 
            $Appointment_date =  $value->setting_value; } else { $Appointment_date =  $value->var_title; 
        }
       if(date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970')
       {
        $next_app .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Appointment_date.' :</b>
            '.date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)).'
          </div>
        </div>';
       }
       else
       {
        $next_app .= '<div style="float:left;width:100%;margin:1em 0;">
          <div style="font-size:small;line-height:18px;">
            <b>'.$Appointment_date.' :</b>
            
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
    $template_data = str_replace("{systemic_illness}",$systemic_illness,$template_data);

    $template_data = str_replace("{examination}",$examination,$template_data);
    $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
    $template_data = str_replace("{suggestion}",$suggestion,$template_data);
    $template_data = str_replace("{remark}",$remark,$template_data);
    $template_data = str_replace("{appointment_date}",$next_app,$template_data);
    $template_data = str_replace("{signature}",$signature,$template_data);
    $template_data = str_replace("{pictorial}",$pictorial_test,$template_data);
    $template_data = str_replace("{biometric_detail}",$biometric_test,$template_data);

    
    $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
    $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
    $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
    $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
    

    echo $template_data; 

$this->session->unset_userdata('eye_prescription_id');
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