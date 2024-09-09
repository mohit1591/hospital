<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

    if(strcmp(strtolower($tab_value->setting_name),'patient_history')=='0' && $tab_value->print_status!=0 && $print_patient_history_flag==1)
    { 
        $patient_history='';
        if(!empty($all_detail['prescription_list']['patient_history']))
        {
          if(!empty($tab_value->setting_value)) { $patient_history_name =  $tab_value->setting_value; } else { $patient_history_name =  'Patient History';//$tab_value->var_title; 
              
          }

          $patient_history ='<h3 style="margin-bottom:0px;">Patient History:</h3>';
          $patient_history.='<ol style="float:left;width:100%;margin-bottom:20px;font-weight:normal;padding-left:15px;">';
          foreach($all_detail['prescription_list']['patient_history'] as $patient_history_li)
          {
            
           $patient_history.='<li style="float:left;width:100%;margin-bottom:15px;">'.$patient_history_li->marriage_status;
           if(!empty($patient_history_li->married_life_unit))
           {
              $patient_history.=''.$patient_history_li->married_life_unit.' '.$patient_history_li->married_life_type;
           }
           if(!empty($patient_history_li->marriage_details))
           {
              $patient_history.=' '.$patient_history_li->marriage_details;
           }
           if(!empty($patient_history_li->previous_delivery))
           {
              $patient_history.=' '.$patient_history_li->previous_delivery;
           }
           if(!empty($patient_history_li->delivery_type))
           {
              $patient_history.=' '.$patient_history_li->delivery_type;
           }

           if(!empty($patient_history_li->delivery_details))
           {
              $patient_history.=' '.$patient_history_li->delivery_details;
           }

           $patient_history.='</li>';
          }
           $patient_history.='</ol>';
        }
      
    }


        $family_history='';
        if(!empty($all_detail['prescription_list']['family_history']))
        {

         // if(!empty($tab_value->setting_value)) { $family_history_name =  $tab_value->setting_value; } else { $family_history_name =  'Family History';//$tab_value->var_title; 
              
          //}

            $family_history ='<h3 style="margin-bottom:0px;">Family History:</h3>';
            $family_history .='<ol style="float:left;width:100%;margin-bottom:20px;padding-left:15px;">';
            
        
          foreach($all_detail['prescription_list']['family_history'] as $family_history_li)
          {
            $family_history.='<li style="float:left;width:100%;margin-bottom:15px;">'.$family_history_li->relation.'  '.$family_history_li->disease.'  '.$family_history_li->family_description.'  '.$family_history_li->family_duration_unit.' '.$family_history_li->family_duration_type.' </li>';

          }
          $family_history.='</ol>';
        

        }

  
        $personal_history='';
        if(!empty($all_detail['prescription_list']['personal_history']))
        {
          //if(!empty($tab_value->setting_value)) { $personal_history_name =  $tab_value->setting_value; } else { $personal_history_name =  'Patient History'; //$tab_value->var_title; 
              
          //}

          $personal_history ='<h3 style="margin-bottom:0px;">Patient History:</h3>';
          $personal_history .='<ol style="float:left;width:100%;margin-bottom:20px;padding-left:15px;">';
          foreach($all_detail['prescription_list']['personal_history'] as $personal_history_li)
          {

            $personal_history.='<li style="float:left;width:100%;margin-bottom:15px;">'.$personal_history_li->br_discharge.' '.$personal_history_li->side.'  '.$personal_history_li->hirsutism.'  '.$personal_history_li->white_discharge.'  '.$personal_history_li->type.' '.$personal_history_li->frequency_personal.' '.$personal_history_li->dyspareunia.' '.$personal_history_li->personal_details.'</li>';
         }
         $personal_history.='</ol>';
      

        }

   
        $menstrual_history='';
        if(!empty($all_detail['prescription_list']['menstrual_history']))
        {
          //if(!empty($tab_value->setting_value)) { $menstrual_history_name =  $tab_value->setting_value; } else { $menstrual_history_name = 'Menstrual History'; //$tab_value->var_title; 
          //}
          $menstrual_history ='<h3 style="margin-bottom:0px;">Menstrual History:</h3>';
          $menstrual_history .='<ol style="margin-bottom:20px;padding-left:15px;float:left;width:100%;">';
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


            $menstrual_history.='<li style="margin-bottom:15px;">'.$menstrual_history_li->previous_cycle.'  '.$menstrual_history_li->prev_cycle_type.' '.$menstrual_history_li->present_cycle.'  '.$menstrual_history_li->present_cycle_type.'  '.$menstrual_history_li->cycle_details.' '.$lmp_date.' '.$menstrual_history_li->dysmenorrhea.' '.$menstrual_history_li->dysmenorrhea_type.'</li>';


          }
           $menstrual_history.='</ol>';
        
       

        }
    
        $medical_history_p='';
        if(!empty($all_detail['prescription_list']['medical_history']))
        {
           //$medical_history_name =  'Medical History';//$tab_value->var_title; 
         // if(!empty($tab_value->setting_value)) { $medical_history_name =  $tab_value->setting_value; } else {    
          //}
          $medical_history_p .='<h3 style="margin-bottom:0px;">Medical History:</h3>';
          $medical_history_p .='<ol style="margin-bottom:20px;float:left;padding-left:15px;width:100%;">';
          foreach($all_detail['prescription_list']['medical_history'] as $medical_history_li)
          {


            $medical_history_p.='<li style="margin-bottom:15px;">'.$medical_history_li->tb.'  '.$medical_history_li->tb_rx.'  '.$menstrual_history_li->present_cycle.'  '.$medical_history_li->dm.'  '.$medical_history_li->dm_years.' '.$medical_history_li->dm_rx.' '.$medical_history_li->ht.' '.$medical_history_li->medical_details.'  '.$medical_history_li->medical_others.'</li>';
            
          
          }
           $medical_history_p.='</ol>';
        
    

        }
        
        $obestetric_history='';
        if(!empty($all_detail['prescription_list']['obestetric_history']))
        {

          //if(!empty($tab_value->setting_value)) { $obestetric_history_name =  $tab_value->setting_value; } else { $obestetric_history_name = 'Obesteric History'; //$tab_value->var_title; 
              
          //}

           $obestetric_history ='<h3 style="margin-bottom:0px;">Obesteric History:</h3>';
          $obestetric_history .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
          
          foreach($all_detail['prescription_list']['obestetric_history'] as $obestetric_history_li)
          {
            

             $obestetric_history.='<li style="margin-bottom:15px;">'.$obestetric_history_li->obestetric_g.'  '.$obestetric_history_li->obestetric_p.' '.$obestetric_history_li->obestetric_l.'  '.$obestetric_history_li->obestetric_mtp.'  '.$medical_history_li->dm_years.' '.$medical_history_li->dm_rx.'  '.$medical_history_li->ht.' '.$medical_history_li->medical_details.'  '.$medical_history_li->medical_others.'</li>';



            
          }
          $obestetric_history.='</ol>';
         
          

        }


    if(strcmp(strtolower($tab_value->setting_name),'disease')=='0' && $tab_value->print_status!=0 && $print_disease_flag=='1')
    {   
      
      $disease_data='';
      if(!empty($all_detail['prescription_list']['disease_history']))
      {
        

        if(!empty($tab_value->setting_value)) { $disease_history_name =  $tab_value->setting_value; } else { $disease_history_name =  $tab_value->var_title; }

           $disease_data ='<h3 style="margin-bottom:0px;">'.$disease_history_name.':</h3>';
          $disease_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';

        foreach($all_detail['prescription_list']['disease_history'] as $disease_data_li)
        {
          $disease_data.='<li style="margin-bottom:15px;">'.$disease_data_li->patient_disease_name.'  '.$obestetric_history_li->obestetric_p.'  '.$disease_data_li->disease_description.'</li>';
        }
        $disease_data.='</ol>';

      }
    
     
    }
    if(strcmp(strtolower($tab_value->setting_name),'complaints')=='0' && $print_complaints_flag=='1')
    {   //echo "asas";die;
      $complaints_data='';
      if(!empty($all_detail['prescription_list']['complaint']))
      {
        if(!empty($tab_value->setting_value)) { $complaint_name =  $tab_value->setting_value; } else { $complaint_name =  $tab_value->var_title; }
        $complaints_data ='<h3 style="margin-bottom:0px;">'.$complaint_name.':</h3>';
        $complaints_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
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
    if(strcmp(strtolower($tab_value->setting_name),'allergy')=='0' && $tab_value->print_status!=0 && $print_allergy_flag=='1')
    {   
      $allergy_data='';
      if(!empty($all_detail['prescription_list']['allergy']))
      {
        if(!empty($tab_value->setting_value)) { $allergy_name =  $tab_value->setting_value; } else { $allergy_name =  $tab_value->var_title; }
        $allergy_data .='<h3 style="margin-bottom:0px;">'.$allergy_name.':</h3>';
        $allergy_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
        foreach($all_detail['prescription_list']['allergy'] as $allergy_data_li)
        {

          $allergy_data.='<li style="margin-bottom:15px;">'.$allergy_data_li->patient_allergy_name.'  '.$allergy_data_li->patient_allergy_unit.' '.$allergy_data_li->patient_allergy_type.'  '.$allergy_data_li->allergy_description.'</li>';

          
        }
        $allergy_data.='</ol>';

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
        if(!empty($tab_value->setting_value)) { $investigation_name =  $tab_value->setting_value; } else { $investigation_name =  $tab_value->var_title; }

        $investigation_data .='<h3 style="margin-bottom:0px;">'.$investigation_name.':</h3>';
        $investigation_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';

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
        $current_medicine .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
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
          $current_medicine.='</ol>';   
          
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
   
   $right_ovary_data .='<h3 style="margin-bottom:0px;">Right Ovary</h3>';
    if(!empty($right_ovary_dataa))
    {  
       $right_ovary_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">

       <thead class="bg-theme"><tr>        
                      <th>Date</th>
                      <th>Day</th>
                      <th>protocol</th>
                      <th>PFSH</th>
                      <th>REC FSH</th>
                      <th>HMG</th>
                      <th>HP HMG </th>
                      <th>Agonist </th>
                      <th>Antagonist </th>
                      <th>Trigger</th> 
                  </tr></thead>';
          $i = 1;
          foreach($right_ovary_dataa as $rightovarydata)
          { 
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
                        
                    </tr>';
             $i++;                
          }
          $right_ovary_data .= '</table>';
  }
  
   if(!empty($right_ovary_db_data))
    {  
       $right_ovary_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">

       <thead class="bg-theme"><tr>       
                      <th scope="col">S.No.</th> 
                      <th>Size </th>
                  </tr></thead>';
          $i = 1;
          foreach($right_ovary_db_data as $key=>$rightovarydataa)
          { 
            $right_ovary_data .= '<tr>
                        <td>'.$i.'</td> 
                        <td>'.$rightovarydataa['right_follic_size'].'  </td>
                        
                    </tr>';
             $i++;                
          }
          $right_ovary_data .= '</table>';
  }
  
  
  $left_ovary_data .='<h3 style="margin-bottom:0px;">Left Ovary</h3>';
  if(isset($left_ovary_dataa) && !empty($left_ovary_dataa))
  {
   
  $left_ovary_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
  
  <thead ><tr>        
                      <th>Date</th>
                      <th>Day</th>
                      <th>protocol</th>
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
                  </tr></thead>';  
           
              $i = 1;
              foreach($left_ovary_dataa as $key=>$leftovarydata)
              { 
                $left_ovary_data .= '<tr>
                             
                            <td>'.date('d-m-Y',strtotime($leftovarydata['left_folli_date'])).'</td>
                            <td>'.$leftovarydata['left_folli_day'].'</td>
                            <td>'.$leftovarydata['left_folli_protocol'].'</td>
                            <td>'.$leftovarydata['left_folli_pfsh'].'</td>
                            <td>'.$leftovarydata['left_folli_recfsh'].'  </td>
                            <td>'.$leftovarydata['left_folli_hmg'].'  </td>
                            <td>'.$leftovarydata['left_folli_hp_hmg'].'  </td>
                            <td>'.$leftovarydata['left_folli_agonist'].'  </td>
                            <td>'.$leftovarydata['left_folli_antiagonist'].'  </td>
                            <td>'.$leftovarydata['left_folli_trigger'].'  </td> 
                            <td>'.$leftovarydata['endometriumothers'].'  </td>
                            <td>'.$leftovarydata['e2'].'  </td>
                            <td>'.$leftovarydata['p4'].'  </td>
                            <td>'.$leftovarydata['risk'].'  </td>
                            <td>'.$leftovarydata['others'].'  </td>
                            
                        </tr>';
                 $i++;                
              } 
               $left_ovary_data .= '</table>';
           }
           
          
           
if(isset($left_ovary_db_data) && !empty($left_ovary_db_data))
  { 
  $left_ovary_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial; margin-top:10px;" width="100%">
  
  <thead ><tr>       
                    
                      <th scope="col">S.No.</th> 
                      <th>Size </th> 
                  </tr></thead>';  
           
              $i = 1;
              foreach($left_ovary_db_data as $key=>$leftovarydataa)
              { 
                $left_ovary_data .= '<tr>
                            
                            <td>'.$i.'</td> 
                            <td>'.$leftovarydataa['left_follic_size'].'  </td> 
                        </tr>';
                 $i++;                
              }
               $left_ovary_data .= '</table>';
           }


}



if(strcmp(strtolower($tab_value->setting_name),'icsilab')=='0' && $tab_value->print_status!=0 && $print_icsilab_flag=='1')
{

  if(!empty($tab_value->setting_value)) { $icsilab_name =  $tab_value->setting_value; } else { $icsilab_name =  $tab_value->var_title; }
   if(!empty($patient_icsilab_db_data))
   {
        
         $icsilab_data .='<h3 style="margin-bottom:0px;">'.$icsilab_name.':</h3>';
        $icsilab_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';

/*  $icsilab_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;" width="100%"><thead class="bg-theme"><tr>       
                    <th>S.No.</th>
                    <th>Date</th>
                    <th>Oocytes</th>
                    <th>M2</th>
                    <th>Injected</th>
                    <th>Cleavge</th>
                    <th>Embryos Day3</th>
                    <th>Day 5</th>
                    <th>Day of ET</th>
                    <th>Et</th>
                    <th>VIT</th>
                    <th>LAH</th>
                    <th>Semen</th>
                    <th>Count</th>
                    <th>Motility</th>
                    <th>G3</th>
                    <th>Abn. Form</th>
                    <th>IMSI</th>
                    <th>Pregnancy</th>
                    <th>Remarks</th>
                  </tr></thead>'; */ 
                  
              
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
                          
                  /*$icsilab_data .= '<tr><td>'.$i.'</td>
                             <td>'.$patient_icsilab_val['icsilab_date'].'</td>
                             <td>'.$patient_icsilab_val['oocytes'].'</td>
                             <td>'.$patient_icsilab_val['m2'].'</td>
                             <td>'.$patient_icsilab_val['injected'].'</td>
                             <td>'.$patient_icsilab_val['cleavge'].'</td>
                             <td>'.$patient_icsilab_val['embryos_day3'].'</td>
                             <td>'.$patient_icsilab_val['day5'].'</td>
                             <td>'.$patient_icsilab_val['day_of_et'].'</td>
                             <td>'.$patient_icsilab_val['et'].'</td>
                             <td>'.$patient_icsilab_val['vit'].'</td>
                             <td>'.$patient_icsilab_val['lah'].'</td>
                             <td>'.$patient_icsilab_val['semen'].'</td>
                             <td>'.$patient_icsilab_val['count'].'</td>
                             <td>'.$patient_icsilab_val['motility'].'</td>
                             <td>'.$patient_icsilab_val['g3'].'</td>
                             <td>'.$patient_icsilab_val['abn_form'].'</td>
                             <td>'.$patient_icsilab_val['imsi'].'</td>
                             <td>'.$patient_icsilab_val['pregnancy'].'</td>
                             <td>'.$patient_icsilab_val['remarks'].'</td>
                          </tr>';*/
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

  if(!empty($tab_value->setting_value)) { $antenatal_care_name =  $tab_value->setting_value; } else { $antenatal_care_name =  $tab_value->var_title; }
     //echo $antenatal_care_name.'poooo'; die;  
     if(!empty($patient_antenatal_care_db_data))
     {
  $antenatal_data .='<h3 style="margin-bottom:0px;">'.$antenatal_care_name.':</h3>';
$antenatal_data .='<ol style="margin-bottom:20px;padding-left:15px;width:100%;float:left;">';
  /*$antenatal_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;" width="100%"><thead class="bg-theme"><tr>       
                    <th>S.No.</th>
                    <th>Last Menstrual Period</th> 
                    <th>Expected Date of Delivery</th>
                    <th>Gestational Age</th> 
                    <th>Remarks</th>
                  </tr></thead>';  */
                  
              
           if(isset($patient_antenatal_care_db_data) && !empty($patient_antenatal_care_db_data))
           {
              $p = 1;
              foreach($patient_antenatal_care_db_data as $key=>$patient_antenatal_care_val)
              {
                  
                   $antenatal_data .= '<li>
                             <div><b>Last Menstrual Period </b>'.$patient_antenatal_care_val['antenatal_care_period'].'</div>
                             <div><b>Expected Date of Delivery </b>'.$patient_antenatal_care_val['antenatal_expected_date'].'</div>
                             
                             <div><b>Gestational Age </b>'.$patient_antenatal_care_val['antenatal_first_date'].'</div>
                             
                             <div><b>Remarks </b>'.$patient_antenatal_care_val['antenatal_remarks'].'</div></li>';
                             
                /* $antenatal_data .= '<tr><td>'.$p.'</td><td>'.$patient_antenatal_care_val['antenatal_care_period'].'</td><td>'.$patient_antenatal_care_val['antenatal_expected_date'].'</td><td>'.$patient_antenatal_care_val['antenatal_first_date'].'</td><td>'.$patient_antenatal_care_val['antenatal_remarks'].'</td></tr>';
                 $p++;               */
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
          
          /*$fertility_data .= '<table border="1px 4px" cellpadding="0" cellspacing="0" style="border-collapse:collapse;font:13px arial;" width="100%"><thead class="bg-theme"><tr>       
                      <th scope="col">S.No.</th>
                      <th>CO</th>
                      <th>Risk</th>
                      <th> Uterine Factor </th>
                      <th>Tubal Factor</th>
                      <th>Decision</th>
                      <th> Ovarian Factor </th> 
                      <th>Sperm</th>
                      <th>Date</th>
                      <th>Count</th> 
                      <th>Motality</th>
                      <th>G3</th>
                      <th> Abnormal form </th> 
                      <th>Remarks</th>
                  </tr>
                  </thead>'; */
            if(!empty($list_fertility_data))
            {
                $i=1;
                //$fertility_data .= '<tbody>';
                foreach($list_fertility_data as $fertility)
                {
                    
            $fertility_data .= '<li>
                <div><b>CO </b>'.$fertility['fertility_co'].'</div>
                <div><b>Risk </b>'.$fertility['fertility_risk'].'</div>
                <div><b>Uterine Factor </b>'.$fertility['fertility_uterine_factor'].'</div>
                <div><b>Tubal Factor </b>'.$fertility['fertility_tubal_factor'].'</div>
                <div><b>Decision </b>'.$fertility['fertility_decision'].'</div>
                <div><b>Ovarian Factor </b>'.$fertility['fertility_ovarian_factor'].'</div>
                <div><b>Sperm </b>'.$fertility['fertility_male_factor'].'</div>
                <div><b>Date </b>'.$fertility['fertility_sperm_date'].'</div>
                <div><b>Count </b>'.$fertility['fertility_sperm_count'].'</div>
                <div><b>Motality </b>'.$fertility['fertility_sperm_motality'].'</div>
                <div><b>G3 </b>'.$fertility['fertility_sperm_g3'].'</div>
                <div><b>Abnormal form </b>'.$fertility['fertility_sperm_abnform'].'</div>
                <div><b>Remarks </b>'.$fertility['fertility_sperm_remarks'].'</div>
                
                </li>';
                             
                             
                             
                    /*$fertility_data .= '<tr>';
                    $fertility_data .= '<td>'.$i.'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_co'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_risk'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_uterine_factor'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_tubal_factor'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_decision'].'</td>'; 
                    $fertility_data .= '<td>'.$fertility['fertility_ovarian_factor'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_male_factor'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_date'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_count'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_motality'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_g3'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_abnform'].'</td>';
                    $fertility_data .= '<td>'.$fertility['fertility_sperm_remarks'].'</td>';
                    $fertility_data .= '</tr>';*/
                }
                //$fertility_data .= '</tbody>';
            }
            $fertility_data .= '</ol>';
          /*$fertility_data .='<ol style="margin-bottom:20px;list-style:none;">';
          $fertility_data.='<li style="margin-bottom:15px;"> <strong>CO : </strong>'.$fertility_co.' <br> <strong>Uterine Factor : </strong>'.$fertility_uterine_factor.' <br> <strong>Tubal Factor : </strong>'.$fertility_tubal_factor.' <br> <strong>Risk : </strong>'.$fertility_risk.' <br> <strong>Decision : </strong>'.$fertility_decision.' <br> <strong>Ovarian Factor : </strong>'.$fertility_ovarian_factor.' <br> <strong>Sperm : </strong>'.$fertility_male_factor.' <br> <strong>Date : </strong>'.date(strtotime('d-m-Y',$fertility_sperm_date)).' <br> <strong>Count : </strong>'.$fertility_sperm_count.' <br> <strong> Motility : </strong>'.$fertility_sperm_motality.' <br> <strong> G3 : </strong>'.$fertility_sperm_g3.' <br><strong> Abnormal form : </strong>'.$fertility_sperm_abnform.' <br><strong> Remarks : </strong>'.$fertility_sperm_remarks.'</li>';*/
         
         //$fertility_data.='</ol>';


  


/*fertility_uploadhsg
fertility_laparoscopy
fertility_ultrasound_images*/





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

    //$template_data = str_replace("{remark}",$remark,$template_data);
    
    
    echo $template_data; die;
  
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