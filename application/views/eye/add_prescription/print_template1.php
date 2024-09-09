<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
<html lang="en">
<head>
  <title><?php echo $page_title; ?></title>
  <style>
    page {
      background: white;
      display: block;
      margin: 0 auto;
      margin-bottom: 0.5cm;
      box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
    }
    page[size="A4"] {  
      width: 21cm;
      *height: 29.7cm; 
      padding: 2em;
    }


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
</head>
<body style="font-family: sans-serif, Arial;background:#cdcdcd;">
<page size="A4" style="margin-top: 0.5in;margin-right: 0.25in;margin-bottom: 0.50in;margin-left: 0.25in;">
    <!-- <div class="logo" style="float:left;width:200px;min-height:100px;">
      <img src="< ?php echo ROOT_IMAGES_PATH; ?>logo.png" style="width: auto;background-size:cover;">
    </div>
    <div class="adderss" style="float:right;width:250px;min-height:100px;padding:0 5px;">
      <div style="float:left;width:250px;">
        <div style="width:;line-height:17px;word-wrap:break-word;text-align: left;font-size:medium;">
          <address style="">
          <b>Address :</b><br>
          Noida Sec - 63,<br>
          E-156<br>
          1st floor
          </address>
        </div>
      </div>
    </div> -->

    <div class="row" style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
      <div style="float:left;width:32%;min-height:100px;margin-right:1.5%;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"><?php echo $data= get_setting_value('PATIENT_REG_NO');?> : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"> <?php echo $form_data['patient_code']; ?></div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['patient_name']; ?></div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gender/Age :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php 
                    $gender = array('0'=>'Female','1'=>'Male');
                    echo $gender[$form_data['gender']]; 
                   ?>
                   / 
                   <?php 
                    $age = "";
                    if($form_data['age_y']>0)
                    {
                      $year = 'Years';
                      if($form_data['age_y']==1)
                      {
                        $year = 'Year';
                      }
                      $age .= $form_data['age_y']." ".$year;
                    }
                    if($form_data['age_m']>0)
                    {
                      $month = 'Months';
                      if($form_data['age_m']==1)
                      {
                        $month = 'Month';
                      }
                      $age .= ", ".$form_data['age_m']." ".$month;
                    }
                    if($form_data['age_d']>0)
                    {
                      $day = 'Days';
                      if($form_data['age_d']==1)
                      {
                        $day = 'Day';
                      }
                      $age .= ", ".$form_data['age_d']." ".$day;
                    }
                    echo $age; 
                    $appointment_date='-';
$booking_date='';

                    if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!='0000-00-00')
                    {
                      $appointment_date = date('d-m-Y',strtotime($form_data['appointment_date']));
                    }

                     if(!empty($form_data['booking_date']) && $form_data['booking_date']!='0000-00-00')
                    {
                      $booking_date = date('d-m-Y',strtotime($form_data['booking_date']));
                    }

                  ?> </div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Referrerd By :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
        </div>
      </div>

     

      <div style="float:right;width:32%;min-height:100px;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD NO. :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['booking_code']; ?></div>
        </div>
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No. : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['mobile_no']; ?> </div>
        </div>
        
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD Reg. Date : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $booking_date; ?> </div>
        </div>



     
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
        </div>
     



      </div>
    </div>
<?php 
 //$chief_complaints='';
 ?>
<!--     <table  width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <tr>
        
        <?php 
        //print_r($vitals_list); exit;
        //if(!empty($vitals_list))
        {
          //$i=0;
          //foreach ($vitals_list as $vitals) 
          {
            //$vital_val = get_vitals_value($vitals->id,$id,2);
            ?>

            <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b><?php //echo $vitals->vitals_name; ?></b></th></tr>
            <tr><td valign="top" align="left"><div style="float:center;min-height:20px;text-align: center;padding-left: 4px;"><?php //echo $vital_val;//$form_data['patient_bp']; ?></div></td></tr>
          </table>
        </td>

            <?php

           // $i++;
            
          }
        }
        ?> -->



        <!-- <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>BP</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;min-height:20px;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_bp']; ?></div></td></tr>
          </table>
        </td>

        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Temp</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_temp']; ?></div></td></tr>
          </table>
        </td>

        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Weight</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_weight']; ?></div></td></tr>
          </table>
        </td>

         

         <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>PR</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_height']; ?></div></td></tr>
          </table>
        </td>

       
         
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Spo2%</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_spo2']; ?></div></td></tr>
          </table>
        </td>
        
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>RBS/FBS</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $form_data['patient_rbs']; ?></div></td></tr>
          </table>
        </td> -->
      
<!-- 
      </tr>
    </table> -->



    <?php 
    foreach ($prescription_tab_setting as $tab_value) 
    {
    if(strcmp(strtolower($tab_value->setting_name),'test_result')=='0')
    {
    if(!empty($test_data)) {  ?>
    <div style="float:left;width:100%;text-align:left;text-decoration:underline;font-size:small;font-weight:bold;margin-bottom:0.5em;"><?php  if(!empty($tab_value->setting_value)) { echo $TEST =  $tab_value->setting_value; } else { echo  $TEST =  $tab_value->var_title; } ?>:</div>

    <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">S.No.</th>
         <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Test Name</th>
        </tr>
      </thead>
      <tbody>
       
          <?php 
                    
                    $i=1;  
                    foreach ($test_data as $testdata) {  
                         ?> <tr>
                           <td style="font-size:13px;padding-left:4px"><?php echo $i; ?></td>
                           <td style="font-size:13px;padding-left:4px"><?php echo $testdata->test_name; ?></td>
                          </tr>
                         <?php 
                     $i++;
                      }  
                      ?>
                      
                    <?php 
                   
                    ?>
       
      </tbody>
    </table>

    <?php } } 
    if(strcmp(strtolower($tab_value->setting_name),'prescription')=='0')
    {
    if(!empty($prescription_data)) {   ?>

     <div style="float:left;width:100%;text-align:left;text-decoration:underline;font-size:small;font-weight:bold;margin-bottom:0.5em;margin-top:2%;"><?php  if(!empty($tab_value->setting_value)) { echo $Prescription =  $tab_value->setting_value; } else { echo  $Prescription =  $tab_value->var_title; } ?>:</div>
    <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <thead>
        <tr>
        <?php 
          foreach ($prescription_medicine_tab_setting as $value) 
                { 
                if(strcmp(strtolower($value->setting_name),'medicine')=='0')
                { 
                if(!empty($value->setting_value)) { $Medicine_name =  $value->setting_value; } else { $Medicine_name =  $value->var_title; }   
                ?>
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Medicine_name; ?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'salt')=='0')
                { 
                if(!empty($value->setting_value)) { $Medicine_salt =  $value->setting_value; } else { $Medicine_salt =  $value->var_title; }   
                ?>
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Medicine_salt; ?></th>
                <?php 
                }


                if(strcmp(strtolower($value->setting_name),'medicine_company')=='0')
                { 
                if(!empty($value->setting_value)) { $Medicine_brand =  $value->setting_value; } else { $Medicine_brand =  $value->var_title; }   
                ?>
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Medicine_brand; ?></th>
                <?php 
                }


                if(strcmp(strtolower($value->setting_name),'type')=='0')
                { 
                if(!empty($value->setting_value)) { $Types =  $value->setting_value; } else { $Types =  $value->var_title; }   
               ?> 
               <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Types;?></th><?php 

                }
                if(strcmp(strtolower($value->setting_name),'dose')=='0')
                { 
                if(!empty($value->setting_value)) { $Dose =  $value->setting_value; } else { $Dose =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Dose;?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'duration')=='0')
                { 
                if(!empty($value->setting_value)) { $Duration =  $value->setting_value; } else { $Duration =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Duration; ?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'frequency')=='0')
                { 
                if(!empty($value->setting_value)) { $Frequency =  $value->setting_value; } else { $Frequency =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Frequency;?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'advice')=='0')
                { 
                if(!empty($value->setting_value)) { $Advice =  $value->setting_value; } else { $Advice =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $Advice;?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'left_eye')=='0')
                { 
                if(!empty($value->setting_value)) { $left_eye =  $value->setting_value; } else { $left_eye =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $left_eye;?></th>
                <?php 
                }

                if(strcmp(strtolower($value->setting_name),'right_eye')=='0')
                { 
                if(!empty($value->setting_value)) { $right_eye =  $value->setting_value; } else { $right_eye =  $value->var_title; }   
                ?> 
                <th align="left" style="font-size: small;padding:5px;text-decoration: underline;"><?php echo $right_eye;?></th>
                <?php 
                }

            }
            ?>
           
    </tr>
      </thead>
      <tbody>
       
         <?php 
           
            foreach ($prescription_data as $prescriptiondata) {  
                 ?>
                  <tr>
                      <?php 
                        foreach ($prescription_medicine_tab_setting as $value) 
                        {
                        
                        if(strcmp(strtolower($value->setting_name),'medicine')=='0')
                        { 
                           
                        ?>
                        <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_name; ?></td>

                       <?php 
                        }

                        if(strcmp(strtolower($value->setting_name),'medicine_company')=='0')
                        { 
                           
                        ?>
                        <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_brand; ?></td>

                       <?php 
                        }

                        if(strcmp(strtolower($value->setting_name),'salt')=='0')
                        { 
                           
                        ?>
                        <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_salt; ?></td>

                       <?php 
                        }



                         if(strcmp(strtolower($value->setting_name),'type')=='0')
                          { 
                      ?>
                      
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_type; ?></td>
                      <?php }
                      if(strcmp(strtolower($value->setting_name),'dose')=='0')
                      {
                       ?>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_dose; ?></td>
                      <?php } 
                      if(strcmp(strtolower($value->setting_name),'duration')=='0')
                      {
                      ?>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_duration; ?></td>
                      <?php } 
                      if(strcmp(strtolower($value->setting_name),'frequency')=='0')
                      { 
                      ?>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_frequency; ?></td>
                      

                      <?php }
                       if(strcmp(strtolower($value->setting_name),'advice')=='0')
                       {
                       ?>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_advice; ?></td>

                      <?php } 
                          if(strcmp(strtolower($value->setting_name),'left_eye')=='0')
                       {
                       ?>
                      <td style="font-size: small;padding:5px;"><?php if($prescriptiondata->left_eye==1){echo 'Left Eye';}else {echo '';}?></td>

                      <?php } 
                         if(strcmp(strtolower($value->setting_name),'right_eye')=='0')
                       {
                       ?>
                      <td style="font-size: small;padding:5px;"><?php if($prescriptiondata->right_eye==2){echo 'Right Eye';}else{echo '';} ?></td>

                      <?php } 

                      } ?>
                  </tr>

                 <?php 
              }  
              ?>
              
            <?php 
            
            ?>

      </tbody>
    </table>
<?php } } ?>
<?php 
  
  

  //if(strcmp(strtolower($tab_value->setting_name),'diagnosis')=='0')
    //{ 
  //if(!empty($form_data['diagnosis'])){ ?>
   <!--  <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  //if(!empty($tab_value->setting_value)) { echo $diagnosis =  $tab_value->setting_value; } else { echo  $diagnosis =  $tab_value->var_title; } ?> :</b>
       <?php //echo nl2br($form_data['diagnosis']); ?>
      </div>
    </div> -->
 <?php //} } 


 if(strcmp(strtolower($tab_value->setting_name),'diagnosis')=='0')
    {   
      $diagnosis='';
        //print_r($all_detail['prescription_list']['cheif_compalin']);die;

        if(!empty($all_detail['prescription_list']['diagnosis_list']))
        {
            if(!empty($tab_value->setting_value)) { $Diagnosis =  $tab_value->setting_value; } else { $Diagnosis =  $tab_value->var_title; }
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
                $eye_su_l='Left Eye';
            }
            else
            {
                 $eye_su_l='';
            }
            $diagnosis.='<td align="center" height="30">'.$eye_su_l;
          

             $diagnosis.='</td>';
              if($diagnos_li->right_eye==2)
            {
                $eye_su= 'Right Eye';
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
    echo $diagnosis;
}



 if(strcmp(strtolower($tab_value->setting_name),'systemic_illness')=='0')
    {  
    $systemic_illness=''; 
        //print_r($all_detail['prescription_list']['systemic_illness']);die;

        if(!empty($all_detail['prescription_list']['systemic_illness']))
        {
            if(!empty($tab_value->setting_value)) { $systemic_ill =  $tab_value->setting_value; } else { $systemic_ill =  $tab_value->var_title; }
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
    echo $systemic_illness;
}

/* pictorial test */

if(strcmp(strtolower($tab_value->setting_name),'pictorial_test')=='0')
    {  
      $pictorial_test='';
        if(!empty($tab_value->setting_value)) { $pictor =  $tab_value->setting_value; } else { $pictor =  $tab_value->var_title; }
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
              $right_eye_val='';    
              $left_eye_val='';                                
  if(!empty($all_detail['prescription_list'][0]->right_eye_discussion)){ $right_eye_val=$all_detail['prescription_list'][0]->right_eye_discussion;}
    if(!empty($all_detail['prescription_list'][0]->left_eye_discussion)){ $left_eye_val=$all_detail['prescription_list'][0]->left_eye_discussion;}
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
            <div class="ri8Frame">'.$right_eye_val.'</div>
          </td>
        </tr>
        <tr>
          <td>
            <div>Left Eye Discussion</div>
            <div class="ri8Frame">'.$left_eye_val.'</div>
          </td>
        </tr>
      </table>
  </div>
</div>
';
echo  $pictorial_test;
}

/* pictorial test */

/* bimetric test */

if(strcmp(strtolower($tab_value->setting_name),'biometric_detail')=='0')
    {  
      $biometric_test='';
      if(!empty($ucva_bcva_data) || !empty($keratometer_data) || !empty($iol_data))
    {
     if(!empty($tab_value->setting_name)) { $biometeric =  $tab_value->setting_name; } else { $biometeric =  $tab_value->var_title; }
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
echo $biometric_test;
}

/* bimetric test */
    

  if(strcmp(strtolower($tab_value->setting_name),'suggestions')=='0')
    {
 if(!empty($form_data['suggestion'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $suggestion =  $tab_value->setting_value; } else { echo  $suggestion =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['suggestion']); ?>
      </div>
    </div>
<?php } } 
if(strcmp(strtolower($tab_value->setting_name),'examination')=='0')
{
 if(!empty($form_data['examination'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $examination =  $tab_value->setting_value; } else { echo  $examination =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['examination']); ?>
      </div>
    </div>
<?php } }
if(strcmp(strtolower($tab_value->setting_name),'previous_history')=='0')
{  
if(!empty($form_data['prv_history'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $prv_history =  $tab_value->setting_value; } else { echo  $prv_history =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['prv_history']); ?>
      </div>
    </div>
<?php } }
if(strcmp(strtolower($tab_value->setting_name),'personal_history')=='0')
{  
if(!empty($form_data['personal_history'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $pers_history =  $tab_value->setting_value; } else { echo  $pers_history =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['personal_history']); ?>
      </div>
    </div>
<?php } }


//if(strcmp(strtolower($tab_value->setting_name),'chief_complaint')=='0')
   //{ 
//if(!empty($form_data['chief_complaints'])){  ?>
    <!--  <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  //if(!empty($tab_value->setting_value)) { echo $chief_complaints =  $tab_value->setting_value; } else { echo  $chief_complaints =  $tab_value->var_title; } ?> :</b>
       <?php //echo nl2br($form_data['chief_complaints']); ?>
      </div>
    </div> -->
<?php //} }


  if(strcmp(strtolower($tab_value->setting_name),'chief_complaint')=='0')
    {   
      $chief_complaints='';
        //print_r($all_detail['prescription_list']['cheif_compalin']);die;

        if(!empty($all_detail['prescription_list']['cheif_compalin']))
        {
            if(!empty($tab_value->setting_value)) { $Chief_complaints =  $tab_value->setting_value; } else { $Chief_complaints =  $tab_value->var_title; }
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
    echo $chief_complaints;
}





if(strcmp(strtolower($tab_value->setting_name),'remarks')=='0')
{
if(!empty($form_data['remark'])){  ?>
     <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $remark =  $tab_value->setting_value; } else { echo  $remark =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['remark']); ?>
      </div>
    </div>
<?php } }

//echo date('d-m-Y',strtotime($form_data['next_appointment_date'])); exit;
if(strcmp(strtolower($tab_value->setting_name),'appointment')=='0' && !empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970' )
{

//if(!empty($form_data['next_appointment_date'])){  ?>
  <?php if(!empty($form_data['next_appointment_date']) && $form_data['next_appointment_date']!='0000-00-00 00:00:00' && $form_data['next_appointment_date']!='1970-01-01' && date('d-m-Y',strtotime($form_data['next_appointment_date']))!='01-01-1970'){ ?>
    
      <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($appointment_date->setting_value)) { echo $appointment_date =  $tab_value->setting_value; } else { echo  $appointment =  $tab_value->var_title; } ?> :</b>
       <?php echo  date('d-m-Y H:i A',strtotime($all_detail['prescription_list'][0]->next_appointment_date)); ?>
      </div>
    </div>
<?php }   } } ?>
</page>
</body>
</html>