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
      height: 29.7cm; 
      padding: 2em;
    }
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
        
        <?php 

         $booking_time ='';
    if(!empty($all_detail['prescription_list'][0]->booking_time) && $all_detail['prescription_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['prescription_list'][0]->booking_time)>0)
    {
        $booking_time = date('h:i A', strtotime($all_detail['prescription_list'][0]->booking_time));    
    }
    
    //$template_data = str_replace("{booking_time}",$booking_time,$template_data);

        ?>


        <!--<div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD Reg. Time : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $booking_time; ?> </div>
        </div>
-->


     
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
        </div>
     
<?php 
        
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);

        
        
 ?>
    <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"><?php echo $form_data['relation']; ?> :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $rel_simulation.' '.$form_data['relation_name']; ?></div>
        </div>

      </div>
    </div>


      </div>
    </div>


  <!--   <table  width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <tr>
        
        <?php 
        if(!empty($vitals_list))
        {
          $i=0;
          foreach ($vitals_list as $vitals) 
          {
            $vital_val = get_vitals_value($vitals->id,$id,2);
            ?>

            <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b><?php echo $vitals->vitals_name; ?></b></th></tr>
            <tr><td valign="top" align="left"><div style="float:center;min-height:20px;text-align: center;padding-left: 4px;"><?php echo $vital_val;//$form_data['patient_bp']; ?></div></td></tr>
          </table>
        </td>

            <?php

            $i++;
            
          }
        }
        ?> 
      </tr>
    </table>
 -->


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


                if(strcmp(strtolower($value->setting_name),'brand')=='0')
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

                        if(strcmp(strtolower($value->setting_name),'brand')=='0')
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

                      <?php } } ?>
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
  
  

  if(strcmp(strtolower($tab_value->setting_name),'diagnosis')=='0')
    { 
  if(!empty($form_data['diagnosis'])){ ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $diagnosis =  $tab_value->setting_value; } else { echo  $diagnosis =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['diagnosis']); ?>
      </div>
    </div>
 <?php } } 
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
if(strcmp(strtolower($tab_value->setting_name),'chief_complaint')=='0')
    { 
if(!empty($form_data['chief_complaints'])){  ?>
     <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $chief_complaints =  $tab_value->setting_value; } else { echo  $chief_complaints =  $tab_value->var_title; } ?> :</b>
       <?php echo nl2br($form_data['chief_complaints']); ?>
      </div>
    </div>
<?php } }
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
       <?php echo  date('d-m-Y',strtotime($form_data['next_appointment_date'])); ?>
      </div>
    </div>
<?php }   } } ?>
</page>
</body>
</html>