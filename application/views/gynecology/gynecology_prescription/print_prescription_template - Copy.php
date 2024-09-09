<link rel="stylesheet" type="text/css" href="https://www.hospitalms.in/assets/css/font-awesome.min.css">
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
td:last-child{padding-left: 60px;}
td ol {padding-left:35px;}
body {font:13px arial;}
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
if($users_data['parent_id']==146)
{
    $patient_vital .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;" width="100%">

    <tbody>';

        if(!empty($vitals_list))
        {
            
            $patient_vital .=' <tr>';
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
          $patient_vital .=' </tr>';
          $patient_vital .=' <tr>';
          foreach ($vitals_list as $vitals) 
          {
              $vital_val = get_vitals_value($vitals->id,$prescription_id,3);
              $patient_vital .='<td align="center" valign="top" style="padding-left:0px;height:20px;">'.$vital_val.'</td>';
                   
          }
          
          $patient_vital .=' </tr>';
         
          /*$patient_vital .=' <tr>';
          
         $patient_vital .= '<th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">Weight</br>(Kg)</th>
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">Height</br>(cm)</th> 
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">BMI</br>(Kg/m2)</th>
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">MAP</th>
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" valign="top">BP</th> ';            
          
          $patient_vital .=' </tr>';
          $patient_vital .=' <tr>';
          
          $patient_vital .='<td style="text-align:center;height:20px;">'.$all_detail['prescription_list'][0]->weight.'</td>
          <td style="text-align:center;height:20px;">'.$all_detail['prescription_list'][0]->height.'</td>
          <td style="text-align:center;height:20px;">'.$all_detail['prescription_list'][0]->bmi.'</td>
          <td style="text-align:center;height:20px;">'.$all_detail['prescription_list'][0]->map.'</td>
          <td style="text-align:center;height:20px;">'.$all_detail['prescription_list'][0]->bp.'</td>';

          

          $patient_vital .=' </tr>';*/
        }

            
       $patient_vital .= '
    </tbody>
</table> <br>';
}
else
{
    

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

}
 /*$extra .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;" width="100%">
        <tbody>
        <tr style="font-size:13px;">
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" >Weight</br>(Kg)</th>
          <th align="center" style="border-bottom:1px solid black;ppadding:0 8px;">Height</br>(cm)</th> 
          <th  align="center" style="border-bottom:1px solid black;padding:0 8px;">BMI</br>(Kg/m2)</th>
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" >LMPS</th>
          <th align="center" style="border-bottom:1px solid black;ppadding:0 8px;">Days</th> 
          <th  align="center" style="border-bottom:1px solid black;padding:0 8px;">EDD</th>
          <th align="center" style="border-bottom:1px solid black;padding:0 8px;" >POG</th>
          <th align="center" style="border-bottom:1px solid black;ppadding:0 8px;">BP</th> 
          <th  align="center" style="border-bottom:1px solid black;padding:0 8px;">MAP</th></tr>
          <tr>
         <td style="text-align:center;">'.$all_detail['prescription_list'][0]->weight.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->height.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->bmi.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->lmps.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->edd.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->pog.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->bp.'</td>
          <td style="text-align:center;">'.$all_detail['prescription_list'][0]->map.'</td>
          </tr>
    </tbody>
</table>';*/

      if(!empty($gpla_list) && count($gpla_list)>0)
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






//new updates
foreach ($prescription_tab_setting as $tab_value) 
{


      /*<table width="100%" cellpadding="0" border=0 style="font:13px Arial;margin-top:20px;">
   <tr>
      <td width="50%" valign="top">
         <h3 style="margin-bottom:0px;">Complaints:</h3>
         <ol style="margin-bottom:20px;list-style:none;">
            <li style="margin-bottom:15px;">ANC-Nausia <br> 0 <br> descriptions </li>
            <li style="margin-bottom:15px;">ANC-Nausia <br> 1 <br> descriptions </li>
            <li style="margin-bottom:15px;">ANC-Nausia <br> 2 <br> descriptions </li>
            <li style="margin-bottom:15px;">ANC-Nausia <br> 3 <br> descriptions </li>
         </ol>
         <h3 style="margin-bottom:0px;">Clinical Examination:</h3>
         <ol style="margin-bottom:20px;list-style:none;">
            <li style="margin-bottom:10px;">P/A soft, non tender <br>  descriptions </li>
            <li style="margin-bottom:10px;">P/A soft, non tender <br>  descriptions </li>
            <li style="margin-bottom:10px;">P/A soft, non tender <br>  descriptions </li>
            <li style="margin-bottom:10px;">P/A soft, non tender <br>  descriptions </li>
         </ol>
         <h3 style="margin-bottom:0px;">Investigation:</h3>
         <ol style="margin-bottom:20px;list-style:none;">
            <li style="margin-bottom:10px;">TSH <br>  12 <br> 13 <br> 12-05-2019 </li>
            <li style="margin-bottom:10px;">TSH <br>  12 <br> 13 <br> 12-05-2019 </li>
            <li style="margin-bottom:10px;">TSH <br>  12 <br> 13 <br> 12-05-2019 </li>
            <li style="margin-bottom:10px;">TSH <br>  12 <br> 13 <br> 12-05-2019 </li>
         </ol>
      </td>
      <td valign="top" width="50%">
         <h3 style="margin-bottom:0px;">Medicines:</h3>
         <ol>
            <li style="margin-bottom:15px;">
               <strong style="font-size:12px;">FOL 123 MF/ TAB FOLPLUS</strong> <br>
               <span>cap </span>
               <span>dose </span>
               <span>30 </span>
               <span> 0-0-1 </span>
               <span>after food</span>
            </li>
            <li style="margin-bottom:15px;">
               <strong style="font-size:12px;">FOL 123 MF/ TAB FOLPLUS</strong> <br>
               <span>cap </span>
               <span>dose </span>
               <span>30 </span>
               <span> 0-0-1 </span>
               <span>after food</span>
            </li>
            <li style="margin-bottom:15px;">
               <strong style="font-size:12px;">FOL 123 MF/ TAB FOLPLUS</strong> <br>
               <span>cap </span>
               <span>dose </span>
               <span>30 </span>
               <span> 0-0-1 </span>
               <span>after food</span>
            </li>
         </ol>*/

    if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0)
    { 
        $patient_history='';
        if(!empty($all_detail['prescription_list']['patient_history']))
        {
          if(!empty($tab_value->setting_value)) { $patient_history_name =  $tab_value->setting_value; } else { $patient_history_name =  $tab_value->var_title; }

          $patient_history ='<h3 style="margin-bottom:0px;>'.$patient_history_name.':</h3>';
          $patient_history.='<ol style="margin-bottom:20px;list-style:none;">';
          foreach($all_detail['prescription_list']['patient_history'] as $patient_history_li)
          {
            
           $patient_history.='<li style="margin-bottom:15px;">'.$patient_history_li->marriage_status.' <br> '.$patient_history_li->married_life_unit.' '.$patient_history_li->married_life_type.' <br> '.$patient_history_li->marriage_details.' <br>'.$patient_history_li->previous_delivery.'<br>'.$patient_history_li->delivery_type.'<br>'.$patient_history_li->delivery_details.'</li>';
          }
           $patient_history.='</ol>';
        }
      
    }


        $family_history='';
        if(!empty($all_detail['prescription_list']['family_history']))
        {

          if(!empty($tab_value->setting_value)) { $family_history_name =  $tab_value->setting_value; } else { $family_history_name =  $tab_value->var_title; }

            $family_history ='<h3 style="margin-bottom:0px;">'.$family_history_name.':</h3>';
            $family_history .='<ol style="margin-bottom:20px;list-style:none;">';
            
        
          foreach($all_detail['prescription_list']['family_history'] as $family_history_li)
          {
            $family_history.='<li style="margin-bottom:15px;">'.$family_history_li->relation.' <br> '.$family_history_li->disease.' <br> '.$family_history_li->family_description.' <br> '.$family_history_li->family_duration_unit.' '.$family_history_li->family_duration_type.' </li>';

          }
          $family_history.='</ol>';
        

        }

  
        $personal_history='';
        if(!empty($all_detail['prescription_list']['personal_history']))
        {
          if(!empty($tab_value->setting_value)) { $personal_history_name =  $tab_value->setting_value; } else { $personal_history_name =  $tab_value->var_title; }

          $personal_history ='<h3 style="margin-bottom:0px;">'.$personal_history_name.':</h3>';
          $personal_history .='<ol style="margin-bottom:20px;list-style:none;">';
          foreach($all_detail['prescription_list']['personal_history'] as $personal_history_li)
          {

            $personal_history.='<li style="margin-bottom:15px;">'.$personal_history_li->br_discharge.' <br> '.$personal_history_li->side.' <br> '.$personal_history_li->hirsutism.' <br> '.$personal_history_li->white_discharge.' <br> '.$personal_history_li->type.' <br>'.$personal_history_li->frequency_personal.' <br>'.$personal_history_li->dyspareunia.' <br>'.$personal_history_li->personal_details.'</li>';
         }
         $personal_history.='</ol>';
      

        }

   
        $menstrual_history='';
        if(!empty($all_detail['prescription_list']['menstrual_history']))
        {
          if(!empty($tab_value->setting_value)) { $menstrual_history_name =  $tab_value->setting_value; } else { $menstrual_history_name =  $tab_value->var_title; }
          $menstrual_history ='<h3 style="margin-bottom:0px;">'.$menstrual_history_name.':</h3>';
          $menstrual_history .='<ol style="margin-bottom:20px;list-style:none;">';
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


            $menstrual_history.='<li style="margin-bottom:15px;">'.$menstrual_history_li->previous_cycle.' <br> '.$menstrual_history_li->prev_cycle_type.' <br> '.$menstrual_history_li->present_cycle.' <br> '.$menstrual_history_li->present_cycle_type.' <br> '.$menstrual_history_li->cycle_details.' <br>'.$lmp_date.' <br>'.$menstrual_history_li->dysmenorrhea.' <br>'.$menstrual_history_li->dysmenorrhea_type.'</li>';


          }
           $menstrual_history.='</ol>';
        
       

        }
    
        $medical_history='';
        if(!empty($all_detail['prescription_list']['medical_history']))
        {
          if(!empty($tab_value->setting_value)) { $medical_history_name =  $tab_value->setting_value; } else { $medical_history_name =  $tab_value->var_title; }
          $medical_history ='<h3 style="margin-bottom:0px;">'.$medical_history_name.':</h3>';
          $medical_history .='<ol style="margin-bottom:20px;list-style:none;">';
          foreach($all_detail['prescription_list']['medical_history'] as $medical_history_li)
          {


            $menstrual_history.='<li style="margin-bottom:15px;">'.$medical_history_li->tb.' <br> '.$medical_history_li->tb_rx.' <br> '.$menstrual_history_li->present_cycle.' <br> '.$medical_history_li->dm.' <br> '.$medical_history_li->dm_years.'<br>'.$medical_history_li->dm_rx.' <br>'.$medical_history_li->ht.' <br>'.$medical_history_li->medical_details.' <br> '.$medical_history_li->medical_others.'</li>';
            
          
          }
           $menstrual_history.='</ol>';
        
    

        }
        
        $obestetric_history='';
        if(!empty($all_detail['prescription_list']['obestetric_history']))
        {

          if(!empty($tab_value->setting_value)) { $obestetric_history_name =  $tab_value->setting_value; } else { $obestetric_history_name =  $tab_value->var_title; }

           $obestetric_history ='<h3 style="margin-bottom:0px;">'.$obestetric_history_name.':</h3>';
          $obestetric_history .='<ol style="margin-bottom:20px;list-style:none;">';
          
          foreach($all_detail['prescription_list']['obestetric_history'] as $obestetric_history_li)
          {
            

             $obestetric_history.='<li style="margin-bottom:15px;">'.$obestetric_history_li->obestetric_g.' <br> '.$obestetric_history_li->obestetric_p.' <br> '.$obestetric_history_li->obestetric_l.' <br> '.$obestetric_history_li->obestetric_mtp.' <br> '.$medical_history_li->dm_years.'<br>'.$medical_history_li->dm_rx.' <br>'.$medical_history_li->ht.' <br>'.$medical_history_li->medical_details.' <br> '.$medical_history_li->medical_others.'</li>';



            
          }
          $obestetric_history.='</ol>';
         
          

        }


        if(strcmp(strtolower($tab_value->setting_name),'disease')=='0' && $tab_value->print_status!=0)
    {   
      
      $disease_data='';
      if(!empty($all_detail['prescription_list']['disease_history']))
      {
        

        if(!empty($tab_value->setting_value)) { $disease_history_name =  $tab_value->setting_value; } else { $disease_history_name =  $tab_value->var_title; }

           $disease_data ='<h3 style="margin-bottom:0px;">'.$disease_history_name.':</h3>';
          $disease_data .='<ol style="margin-bottom:20px;list-style:none;">';

        foreach($all_detail['prescription_list']['disease_history'] as $disease_data_li)
        {
          $disease_data.='<li style="margin-bottom:15px;">'.$disease_data_li->patient_disease_name.' <br> '.$obestetric_history_li->obestetric_p.' <br> '.$disease_data_li->disease_description.'</li>';
        }
        $disease_data.='</ol>';

      }
    
     
    }
    if(strcmp(strtolower($tab_value->setting_name),'complaints')=='0' )
    {   //echo "asas";die;
      $complaints_data='';
      if(!empty($all_detail['prescription_list']['complaint']))
      {
        if(!empty($tab_value->setting_value)) { $complaint_name =  $tab_value->setting_value; } else { $complaint_name =  $tab_value->var_title; }
        $complaints_data ='<h3 style="margin-bottom:0px;">'.$complaint_name.':</h3>';
        $complaints_data .='<ol style="margin-bottom:20px;list-style:none;">';
        foreach($all_detail['prescription_list']['complaint'] as $complaint_data_li)
        {
          
           /*$complaints_data.='<li style="margin-bottom:15px;">'.$complaint_data_li->patient_complaint_name.' <br> '.$complaint_data_li->patient_complaint_unit.' '.$complaint_data_li->patient_complaint_type.' <br> '.$complaint_data_li->complaint_description.'</li>';*/

           $complaints_data.='<li style="margin-bottom:15px;">'.$complaint_data_li->patient_complaint_name.'  ';
           if(!empty($complaint_data_li->patient_complaint_unit))
           {
              $complaints_data.=$complaint_data_li->patient_complaint_unit.' ';
           }

           if(!empty($complaint_data_li->patient_complaint_type))
           {
              $complaints_data.=$complaint_data_li->patient_complaint_type.' ';
           }

           if(!empty($complaint_data_li->complaint_description))
           {
              $complaints_data.=$complaint_data_li->complaint_description;
           }

         $complaints_data.='</li>';
        }
        $complaints_data.='</ol>';

      }
    
     
    }
 //echo $complaint_data;die;
    if(strcmp(strtolower($tab_value->setting_name),'allergy')=='0' && $tab_value->print_status!=0)
    {   
      $allergy_data='';
      if(!empty($all_detail['prescription_list']['allergy']))
      {
        if(!empty($tab_value->setting_value)) { $allergy_name =  $tab_value->setting_value; } else { $allergy_name =  $tab_value->var_title; }
        $allergy_data .='<h3 style="margin-bottom:0px;">'.$allergy_name.':</h3>';
        $allergy_data .='<ol style="margin-bottom:20px;list-style:none;">';
        foreach($all_detail['prescription_list']['allergy'] as $allergy_data_li)
        {

          $allergy_data.='<li style="margin-bottom:15px;">'.$allergy_data_li->patient_allergy_name.' <br> '.$allergy_data_li->patient_allergy_unit.' '.$allergy_data_li->patient_allergy_type.' <br> '.$allergy_data_li->allergy_description.'</li>';

          
        }
        $allergy_data.='</ol>';

      }
 
     
    }
  

    if(strcmp(strtolower($tab_value->setting_name),'general_examination')=='0' && $tab_value->print_status!=0)
    {   
      $general_examination_data='';
      if(!empty($all_detail['prescription_list']['general_examination']))
      {
        if(!empty($tab_value->setting_value)) { $general_examination_name =  $tab_value->setting_value; } else { $general_examination_name =  $tab_value->var_title; }
        $general_examination_data .='<h3 style="margin-bottom:0px;">'.$general_examination_name.':</h3>';
        $general_examination_data .='<ol style="margin-bottom:20px;list-style:none;">';

        foreach($all_detail['prescription_list']['general_examination'] as $general_examination_data_li)
        {

          $general_examination_data.='<li style="margin-bottom:15px;">'.$general_examination_data_li->patient_general_examination_name.'<br> '.$general_examination_data_li->general_examination_description.'</li>';
        }
        $general_examination_data.='</ol>';

      }
 
     
    }
    
    if(strcmp(strtolower($tab_value->setting_name),'clinical_examination')=='0' && $tab_value->print_status!=0)
    {   
      $clinical_examination_data='';
      if(!empty($all_detail['prescription_list']['clinical_examination']))
      {
        if(!empty($tab_value->setting_value)) { $clinical_examination_name =  $tab_value->setting_value; } else { $clinical_examination_name =  $tab_value->var_title; }
        $clinical_examination_data .='<h3 style="margin-bottom:0px;">'.$clinical_examination_name.':</h3>';
        $clinical_examination_data .='<ol style="margin-bottom:20px;list-style:none;">';

        foreach($all_detail['prescription_list']['clinical_examination'] as $clinical_examination_data_li)
        {

            $clinical_examination_data.='<li style="margin-bottom:15px;">'.$clinical_examination_data_li->patient_clinical_examination_name.'<br> '.$clinical_examination_data_li->clinical_examination_description.'</li>';
        }
        $clinical_examination_data.='</ol>';

      }

    }


    if(strcmp(strtolower($tab_value->setting_name),'investigation')=='0' && $tab_value->print_status!=0)
    {   
      $investigation_data='';
      if(!empty($all_detail['prescription_list']['investigation_prescription']))
      {
        if(!empty($tab_value->setting_value)) { $investigation_name =  $tab_value->setting_value; } else { $investigation_name =  $tab_value->var_title; }

        $investigation_data .='<h3 style="margin-bottom:0px;">'.$investigation_name.':</h3>';
        $investigation_data .='<ol style="margin-bottom:20px;">';

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

          $investigation_data.='<li style="margin-bottom:15px;">'.$investigation_data_li->patient_investigation_name.' ';
            if(!empty($investigation_data_li->std_value) && $investigation_data_li->std_value!=0)
            {
                $investigation_data.=$investigation_data_li->std_value.' ';
            }
            if(!empty($investigation_data_li->observed_value))
            {
                $investigation_data.=$investigation_data_li->observed_value.' ';
            }
            
          $investigation_data.='</li>';
        }
        $investigation_data.='</ol>';

      }
      
     
    }

    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0' && $tab_value->print_status!=0)
    {   
      $advice_data='';
      if(!empty($all_detail['prescription_list']['advice_prescription']))
      {
        if(!empty($tab_value->setting_value)) { $advice_name =  $tab_value->setting_value; } else { $advice_name =  $tab_value->var_title; }
        
        $advice_data .='<h3 style="margin-bottom:0px;">'.$advice_name.':</h3>';
        $advice_data .='<ol style="margin-bottom:20px;list-style:none;">';

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

       if(!empty($tab_value->setting_value)) { $prescription_history__name =  $tab_value->setting_value; } else { $prescription_history__name =  $tab_value->var_title; }
        
        $current_medicine .='<h3 style="margin-bottom:0px;">'.$prescription_history__name.':</h3>';
        $current_medicine .='<ol style="margin-bottom:20px;list-style:none;">';
        $prescription_history_data = $all_detail['prescription_list']['prescription_history_data'];
             
        foreach ($prescription_history_data as $prescriptiondata)
        {  
            $current_medicine.='<li style="margin-bottom:15px;">';
            foreach ($prescription_medicine_tab_setting as $value) 
            {
                    if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                      $current_medicine.=$prescriptiondata->medicine_name;
                    }

                    if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $current_medicine.=$prescriptiondata->medicine_brand;
                    }
                    if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $current_medicine.=$prescriptiondata->medicine_salt;
                   
                    }
                     if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                        $current_medicine.=$prescriptiondata->medicine_type.' ';
                  }
                  if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                  {
                       $current_medicine.=$prescriptiondata->medicine_dose.' ';
                  } 
                  if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                  {
                    $current_medicine.=$prescriptiondata->medicine_duration.' ';
                  } 
                  if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                  { 
                      $current_medicine.=$prescriptiondata->medicine_frequency.' ';
                 }
                   if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                   {
                     $current_medicine.=$prescriptiondata->medicine_advice;
                   } 
                    
              } 
              $current_medicine.='</li>';
             
         }  
             
          
      }
  }



if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0' && $tab_value->print_status!=0)
{
      $medicine='';
      if(!empty($all_detail['prescription_list']['prescription_data']) )
      {

          $prescription_data = $all_detail['prescription_list']['prescription_data'];
       
          if(!empty($tab_value->setting_value)) { $med_name =  $tab_value->setting_value; } else { $med_name =  $tab_value->var_title; }
        
        $medicine .='<h3 style="margin-bottom:0px;">'.$med_name.':</h3>';
        $medicine .='<ol style="margin-bottom:20px;">';
             
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


 $next_appointment_date= '';
  if(strcmp(strtolower($tab_value->setting_name),'next_appointment' )=='0' && $tab_value->print_status!=0 && !empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970' )
{

if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970')
{
    if(!empty($tab_value->setting_value)) { $next_apt_name =  $tab_value->setting_value; } else { $next_apt_name =  $tab_value->var_title; }
        
        $next_appointment_date .='<h3 style="margin-bottom:0px;">'.$next_apt_name.':</h3>';
        $next_appointment_date .='<ol style="margin-bottom:20px;list-style:none;">';
        $next_appointment_date .=date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date));
 }   
}





   


}




      







//old setting data 


foreach ($prescription_tab_setting as $tab_value) {/*   

 if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0)
    { 
    $patient_history='';
        if(!empty($all_detail['prescription_list']['patient_history']))
        {
          if(!empty($tab_value->setting_value)) { $patient_history_name =  $tab_value->setting_value; } else { $patient_history_name =  $tab_value->var_title; }

          $patient_history ='<div style="font-size:15px;line-height:18px;"><b>'.$patient_history_name.':</b>

          </div>';
          $patient_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Married</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Married Life</b></td>
         
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Married Details</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Previous Delivery</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Delivery Type</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Delivery Details</b></td>
          </tr>';

          foreach($all_detail['prescription_list']['patient_history'] as $patient_history_li)
          {
            $patient_history.='<tr>
            <td align="left" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->marriage_status.'</td>';

             $patient_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->married_life_unit.' '.$patient_history_li->married_life_type.'</td>';
          
            $patient_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->marriage_details.'</td>';
            $patient_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->previous_delivery.'</td>';
            $patient_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->delivery_type.'</td>';
            $patient_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$patient_history_li->delivery_details.'</td>';
            $patient_history.='</tr>';
          }
          $patient_history.='</table>
          </div>';
        }
         


         $family_history='';
        if(!empty($all_detail['prescription_list']['family_history']))
        {
          $family_history ='<div style="font-size:15px;line-height:18px;"><b>Family History:</b>

          </div>';
          $family_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Relation</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Disease</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Duration</b></td>
          </tr>';

          foreach($all_detail['prescription_list']['family_history'] as $family_history_li)
          {
            $family_history.='<tr>
            <td align="left" height="30" style="font-size:13px;padding:5px;">'.$family_history_li->relation.'</td>';
            $family_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$family_history_li->disease.'</td>';
            $family_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$family_history_li->family_description.'</td>';
            $family_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$family_history_li->family_duration_unit.' '.$family_history_li->family_duration_type.'</td>';
            
            $family_history.='</tr>';
          }
          $family_history.='</table>
          </div>';
        

        }

  
         $personal_history='';
        if(!empty($all_detail['prescription_list']['personal_history']))
        {
          $personal_history ='<div style="font-size:15px;line-height:18px;"><b>Personal History:</b>

          </div>';
          $personal_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Breast Discharge</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Side</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Hirsutism</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>White Discharge</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Type</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Frequency</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Dyspareunia</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Details</b></td>
          </tr>';

          foreach($all_detail['prescription_list']['personal_history'] as $personal_history_li)
          {
            
            $personal_history.='<td align="left" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->br_discharge.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->side.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->hirsutism.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->white_discharge.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->type.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->frequency_personal.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->dyspareunia.'</td>';
            $personal_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$personal_history_li->personal_details.'</td>';
            
            
            $personal_history.='</tr>';
          }
          $personal_history.='</table>
          </div>';
      

        }

    "<br>";
         $menstrual_history='';
        if(!empty($all_detail['prescription_list']['menstrual_history']))
        {
          $menstrual_history ='<div style="font-size:15px;line-height:18px;"><b>Menstrual History:</b>

          </div>';
          $menstrual_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Previous Cycle</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Cycle Type</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Present Cycle</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Cycle Type</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Details</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>LMP Date</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Dysmenorrhea</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Dysmenorrhea Type</b></td>
          </tr>';

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
            $menstrual_history.='<td align="left" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->previous_cycle.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->prev_cycle_type.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->present_cycle.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->present_cycle_type.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->cycle_details.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$lmp_date.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->dysmenorrhea.'</td>';
            $menstrual_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$menstrual_history_li->dysmenorrhea_type.'</td>';
            
            
            $menstrual_history.='</tr>';
          }
          $menstrual_history.='</table>
          </div>';
        
       

        }
   "<br>";
        $medical_history='';
        if(!empty($all_detail['prescription_list']['medical_history']))
        {
          $medical_history ='<div style="font-size:15px;line-height:18px;"><b>Medical History:</b>

          </div>';
          $medical_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>T.B</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Rx</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>D.M</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Years</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Rx</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>H.T</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Details</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Others</b></td>
          </tr>';

          foreach($all_detail['prescription_list']['medical_history'] as $medical_history_li)
          {
            
            $medical_history.='<td align="left" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->tb.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->tb_rx.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->dm.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->dm_years.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->dm_rx.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->ht.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->medical_details.'</td>';
            $medical_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$medical_history_li->medical_others.'</td>';
            
            
            $medical_history.='</tr>';
          }
          $medical_history.='</table>
          </div>';
        
    

        }
        
        $obestetric_history='';
        if(!empty($all_detail['prescription_list']['obestetric_history']))
        {
          $obestetric_history ='<div style="font-size:15px;line-height:18px;"><b>Obestetric History:</b>

          </div>';
          $obestetric_history.='<div class="grid-frame3">
          <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
          <tr>
          <td align="left" height="30" style="font-size:14px;padding:5px;"><b>G</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>P</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>L</b></td>
          <td align="center" height="30" style="font-size:14px;padding:5px;"><b>MTP</b></td>
          </tr>';

          foreach($all_detail['prescription_list']['obestetric_history'] as $obestetric_history_li)
          {
            
            $obestetric_history.='<td align="left" height="30" style="font-size:13px;padding:5px;">'.$obestetric_history_li->obestetric_g.'</td>';
            $obestetric_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$obestetric_history_li->obestetric_p.'</td>';
            $obestetric_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$obestetric_history_li->obestetric_l.'</td>';
            $obestetric_history.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$obestetric_history_li->obestetric_mtp.'</td>';
            $obestetric_history.='</tr>';
          }
          $obestetric_history.='</table>
          </div>';
         
          

        }

}
if(strcmp(strtolower($tab_value->setting_name),'disease')=='0' && $tab_value->print_status!=0)
    {   
      
      $disease_data='';
      if(!empty($all_detail['prescription_list']['disease_history']))
      {
        //print_r($all_detail['prescription_list']['disease_history']);
        if(!empty($tab_value->setting_value)) { $disease_name =  $tab_value->setting_value; } else { $disease_name =  $tab_value->var_title; }

        $disease_data ='<div style="font-size:15px;line-height:18px;"><b>'.$disease_name.':</b>

        </div>';
        $disease_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Disease Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Duration</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['disease_history'] as $disease_data_li)
        {
          $disease_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$disease_data_li->patient_disease_name.'</td>';
          $disease_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$disease_data_li->patient_disease_unit.' '.$disease_data_li->patient_disease_type.'</td>';
          $disease_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$disease_data_li->disease_description.'</td>';
          $disease_data.='</tr>';
        }
        $disease_data.='</table>
        </div>';

      }
    
     
    }
    if(strcmp(strtolower($tab_value->setting_name),'complaints')=='0' )
    {   //echo "asas";die;
      $complaints_data='';
      if(!empty($all_detail['prescription_list']['complaint']))
      {
        if(!empty($tab_value->setting_value)) { $complaint_name =  $tab_value->setting_value; } else { $complaint_name =  $tab_value->var_title; }

        $complaints_data ='<div style="font-size:15px;line-height:18px;"><b>'.$complaint_name.':</b>

        </div>';
        $complaints_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Complaint Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Duration</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['complaint'] as $complaint_data_li)
        {
          
          $complaints_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$complaint_data_li->patient_complaint_name.'</td>';
          $complaints_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$complaint_data_li->patient_complaint_unit.' '.$complaint_data_li->patient_complaint_type.'</td>';
          $complaints_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$complaint_data_li->complaint_description.'</td>';
          $complaints_data.='</tr>';
        }
        $complaints_data.='</table>
        </div>';

      }
    
     
    }
 //echo $complaint_data;die;
    if(strcmp(strtolower($tab_value->setting_name),'allergy')=='0' && $tab_value->print_status!=0)
    {   
      $allergy_data='';
      if(!empty($all_detail['prescription_list']['allergy']))
      {
        if(!empty($tab_value->setting_value)) { $allergy_name =  $tab_value->setting_value; } else { $allergy_name =  $tab_value->var_title; }

        $allergy_data ='<div style="font-size:15px;line-height:18px;"><b>'.$allergy_name.':</b>

        </div>';
        $allergy_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Allergy Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Duration</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['allergy'] as $allergy_data_li)
        {
          $allergy_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$allergy_data_li->patient_allergy_name.'</td>';
          $allergy_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$allergy_data_li->patient_allergy_unit.' '.$allergy_data_li->patient_allergy_type.'</td>';
          $allergy_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$allergy_data_li->allergy_description.'</td>';
          $allergy_data.='</tr>';
        }
        $allergy_data.='</table>
        </div>';

      }
 
     
    }
   "</br>";

    if(strcmp(strtolower($tab_value->setting_name),'general_examination')=='0' && $tab_value->print_status!=0)
    {   
      $general_examination_data='';
      if(!empty($all_detail['prescription_list']['general_examination']))
      {
        if(!empty($tab_value->setting_value)) { $general_examination_name =  $tab_value->setting_value; } else { $general_examination_name =  $tab_value->var_title; }

        $general_examination_data ='<div style="font-size:15px;line-height:18px;"><b>'.$general_examination_name.':</b>

        </div>';
        $general_examination_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Exam Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['general_examination'] as $general_examination_data_li)
        {
          $general_examination_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$general_examination_data_li->patient_general_examination_name.'</td>';
          
          $general_examination_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$general_examination_data_li->general_examination_description.'</td>';
          $general_examination_data.='</tr>';
        }
        $general_examination_data.='</table>
        </div>';

      }
 
     
    }
    
    if(strcmp(strtolower($tab_value->setting_name),'clinical_examination')=='0' && $tab_value->print_status!=0)
    {   
      $clinical_examination_data='';
      if(!empty($all_detail['prescription_list']['clinical_examination']))
      {
        if(!empty($tab_value->setting_value)) { $clinical_examination_name =  $tab_value->setting_value; } else { $clinical_examination_name =  $tab_value->var_title; }

        $clinical_examination_data ='<div style="font-size:15px;line-height:18px;"><b>'.$clinical_examination_name.':</b>

        </div>';
        $clinical_examination_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Exam Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Description</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['clinical_examination'] as $clinical_examination_data_li)
        {
          $clinical_examination_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$clinical_examination_data_li->patient_clinical_examination_name.'</td>';
          $clinical_examination_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$clinical_examination_data_li->clinical_examination_description.'</td>';
          $clinical_examination_data.='</tr>';
        }
        $clinical_examination_data.='</table>
        </div>';

      }

    }

      
    if(strcmp(strtolower($tab_value->setting_name),'investigation')=='0' && $tab_value->print_status!=0)
    {   
      $investigation_data='';
      if(!empty($all_detail['prescription_list']['investigation_prescription']))
      {
        if(!empty($tab_value->setting_value)) { $investigation_name =  $tab_value->setting_value; } else { $investigation_name =  $tab_value->var_title; }

        $investigation_data ='<div style="font-size:15px;line-height:18px;"><b>'.$investigation_name.':</b>

        </div>';
        $investigation_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Investigation Name</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Std. Value</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Observed Value</b></td>
        <td align="center" height="30" style="font-size:14px;padding:5px;"><b>Date</b></td>
        </tr>';

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
          $investigation_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$investigation_data_li->patient_investigation_name.'</td>';
          $investigation_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$investigation_data_li->std_value.'</td>';
          $investigation_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$investigation_data_li->observed_value.'</td>';
          $investigation_data.='<td align="center" height="30" style="font-size:13px;padding:5px;">'.$investigation_date.'</td>';
          $investigation_data.='</tr>';
        }
        $investigation_data.='</table>
        </div>';

      }
      
     
    }


 if(strcmp(strtolower($tab_value->setting_name),'advice')=='0' && $tab_value->print_status!=0)
    {   
      $advice_data='';
      if(!empty($all_detail['prescription_list']['advice_prescription']))
      {
        if(!empty($tab_value->setting_value)) { $advice_name =  $tab_value->setting_value; } else { $advice_name =  $tab_value->var_title; }

        $advice_data ='<div style="font-size:15px;line-height:18px;"><b>'.$advice_name.':</b>

        </div>';
        $advice_data.='<div class="grid-frame3">
        <table width="100%" border="" cellspacing="0" cellpadding="0" class="grid_tbl">
        <tr>
        <td align="left" height="30" style="font-size:14px;padding:5px;"><b>Advice Name</b></td>
        </tr>';

        foreach($all_detail['prescription_list']['advice_prescription'] as $advice_data_li)
        {
          $advice_data.='<tr>
          <td align="left" height="30" style="font-size:13px;padding:5px;">'.$advice_data_li->patient_advice_name.'</td>';
          $advice_data.='</tr>';
        }
        $advice_data.='</table>
        </div>';

      }
    
     
    }
    ///
  if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0)
  {
    if(!empty($all_detail['prescription_list']['prescription_history_data']))
        {
          $prescription_history_data = $all_detail['prescription_list']['prescription_history_data'];
       
          $current_medicine='<div style="float:left;width:100%;text-align:left;font-size:15px;font-weight:bold;margin-bottom:0.5em;margin-top:2%;">Current Medication:</div>
          <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
            <thead>
              <tr>';
             
                foreach ($prescription_patient_medicine_tab_setting as $value) 
                      { 
                      if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_name =  $value->setting_value; } else { $Medicine_name =  $value->var_title; } 
                       $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_name.'</th>';  
                    
                      }

                      if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_salt =  $value->setting_value; } else { $Medicine_salt =  $value->var_title; }   
                        $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_salt.'</th>';
                      
                      }


                      if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_brand =  $value->setting_value; } else { $Medicine_brand =  $value->var_title; } 
                        $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_brand.'</th>';
                      
                      }


                      if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Types =  $value->setting_value; } else { $Types =  $value->var_title; }  
                      $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Types.'</th>'; 
                    

                      }
                      if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Dose =  $value->setting_value; } else { $Dose =  $value->var_title; } 

                        $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Dose.'</th>';   
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Duration =  $value->setting_value; } else { $Duration =  $value->var_title; } 
                      $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Duration.'</th>';   
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Frequency =  $value->setting_value; } else { $Frequency =  $value->var_title; }  
                      $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Frequency.'</th>';  
                     
                      }

                      if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Advice =  $value->setting_value; } else { $Advice =  $value->var_title; } 
                          $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Advice.'</th>';  
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'left_eye')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $left_eye =  $value->setting_value; } else { $left_eye =  $value->var_title; } 
                      $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$left_eye.'</th>';   
                     
                      }

                      if(strcmp(strtolower($value->setting_name),'right_eye')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $right_eye =  $value->setting_value; } else { $right_eye =  $value->var_title; }   
                         $current_medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$right_eye.'</th>';
                       
                      }

                  }
                 
                 
              $current_medicine.='</tr>
            </thead>
            <tbody>';
             
            foreach ($prescription_history_data as $prescriptiondata)
            {  
              
              $current_medicine.='<tr>';
                  
                    foreach ($prescription_medicine_tab_setting as $value) 
                    {

                    
                    if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                      $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_name.'</td>';
                       
                    
                    }

                    if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_brand.'</td>';
                       
                   
                    }
                    if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_salt.'</td>';
                   
                    }
                     if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                        $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_type.'</td>';
                  }
                  if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                  {
                       $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_dose.'</td>';
                  } 
                  if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                  {
                    $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_duration.'</td>';
                  } 
                  if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                  { 
                      $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_frequency.'</td>';
                 }
                   if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                   {
                     $current_medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_advice.'</td>';
                   } 
                    
                  } 
              $current_medicine.='</tr>';
             
              }  
             
            $current_medicine.='</tbody>
          </table>';
      }
  }
    ///
      //echo "asas".$tab_value->print_status;

if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0' && $tab_value->print_status!=0)
{
$medicine='';
      if(!empty($all_detail['prescription_list']['prescription_data']) )
        {

          $prescription_data = $all_detail['prescription_list']['prescription_data'];
       
          $medicine='<div style="float:left;width:100%;text-align:left;font-size:15px;font-weight:bold;margin-bottom:0.5em;margin-top:2%;">Medicine</div>
          <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
            <thead>
              <tr>';
            
                foreach ($prescription_patient_medicine_tab_setting as $value) 
                      { 
                      if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_name =  $value->setting_value; } else { $Medicine_name =  $value->var_title; } 
                       $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_name.'</th>';  
                    
                      }

                      if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_salt =  $value->setting_value; } else { $Medicine_salt =  $value->var_title; }   
                        $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_salt.'</th>';
                      
                      }


                      if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Medicine_brand =  $value->setting_value; } else { $Medicine_brand =  $value->var_title; } 
                        $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Medicine_brand.'</th>';
                      
                      }


                      if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Types =  $value->setting_value; } else { $Types =  $value->var_title; }  
                      $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Types.'</th>'; 
                    

                      }
                      if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Dose =  $value->setting_value; } else { $Dose =  $value->var_title; } 

                        $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Dose.'</th>';   
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Duration =  $value->setting_value; } else { $Duration =  $value->var_title; } 
                      $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Duration.'</th>';   
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Frequency =  $value->setting_value; } else { $Frequency =  $value->var_title; }  
                      $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Frequency.'</th>';  
                     
                      }

                      if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $Advice =  $value->setting_value; } else { $Advice =  $value->var_title; } 
                          $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$Advice.'</th>';  
                      
                      }

                      if(strcmp(strtolower($value->setting_name),'left_eye')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $left_eye =  $value->setting_value; } else { $left_eye =  $value->var_title; } 
                      $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$left_eye.'</th>';   
                     
                      }

                      if(strcmp(strtolower($value->setting_name),'right_eye')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                      if(!empty($value->setting_value)) { $right_eye =  $value->setting_value; } else { $right_eye =  $value->var_title; }   
                         $medicine.='<th align="left" style="font-size: 14px;padding:5px;">'.$right_eye.'</th>';
                       
                      }

                  }
                 
                 
              $medicine.='</tr>
            </thead>
            <tbody>';
             
            foreach ($prescription_data as $prescriptiondata)
            {  
              
              $medicine.='<tr>';
                  
                    foreach ($prescription_medicine_tab_setting as $value) 
                    {

                    
                    if(strcmp(strtolower($value->setting_name),'medicine')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                      $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_name.'</td>';
                       
                    
                    }

                    if(strcmp(strtolower($value->setting_name),'medicine_company')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_brand.'</td>';
                       
                   
                    }
                    if(strcmp(strtolower($value->setting_name),'salt')=='0' && $value->print_status==1 && $value->status==1)
                    { 
                       $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_salt.'</td>';
                   
                    }
                     if(strcmp(strtolower($value->setting_name),'type')=='0' && $value->print_status==1 && $value->status==1)
                      { 
                        $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_type.'</td>';
                  }
                  if(strcmp(strtolower($value->setting_name),'dose')=='0' && $value->print_status==1 && $value->status==1)
                  {
                       $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_dose.'</td>';
                  } 
                  if(strcmp(strtolower($value->setting_name),'duration')=='0' && $value->print_status==1 && $value->status==1)
                  {
                    $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_duration.'</td>';
                  } 
                  if(strcmp(strtolower($value->setting_name),'frequency')=='0' && $value->print_status==1 && $value->status==1)
                  { 
                      $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_frequency.'</td>';
                 }
                   if(strcmp(strtolower($value->setting_name),'advice')=='0' && $value->print_status==1 && $value->status==1)
                   {
                     $medicine.='<td style="font-size: 13px;padding:5px;">'.$prescriptiondata->medicine_advice.'</td>';
                   } 
                   
                  } 
              $medicine.='</tr>';
             
              }  
             
            $medicine.='</tbody>
          </table>';
      } 
 }
 $next_appointment_date= '';
//echo $all_detail['prescription_list'][0]->next_appointment_date;
  if(strcmp(strtolower($tab_value->setting_name),'next_appointment' )=='0' && $tab_value->print_status!=0 && !empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970' )
{

//if(!empty($all_detail['prescription_list'][0]->next_appointment_date)){  ?>
  <?php if(!empty($all_detail['prescription_list'][0]->next_appointment_date) && $all_detail['prescription_list'][0]->next_appointment_date!='0000-00-00 00:00:00' && $all_detail['prescription_list'][0]->next_appointment_date!='1970-01-01' && date('d-m-Y',strtotime($all_detail['prescription_list'][0]->next_appointment_date))!='01-01-1970'){ 

    $next_appointment_date = '<div style="font-size:15px;line-height:18px;"><b>Next Appointment Date:</b>  '.
          date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)).'</div>';
 }   }

    
    */} 

   //$next_appointment_date='';
    //echo $medicine;die;
   $signature='Dr. '.get_doctor_name($all_detail['prescription_list'][0]->attended_doctor);
   $remark='';
   $suggestion='';
    $template_data = str_replace("{patient_history}",$patient_history,$template_data);
    $template_data = str_replace("{family_history}",$family_history,$template_data);
    $template_data = str_replace("{personal_history}",$personal_history,$template_data);
    $template_data = str_replace("{menstrual_history}",$menstrual_history,$template_data);
    $template_data = str_replace("{medical_history}",$medical_history,$template_data);
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
    echo $template_data; 
  
    //print_r($current_medicine);

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