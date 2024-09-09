<!DCOTYPE html>
<html lang="en">
<head>
  <title>Test Report</title>
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
<page size="A4">
    <div class="logo" style="float:left;width:200px;min-height:100px;">
      <img src="<?php echo ROOT_IMAGES_PATH; ?>logo.png" style="width: auto;background-size:cover;">
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
    </div>

    <div class="row" style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
      <div style="float:left;width:32%;min-height:100px;margin-right:1.5%;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient  Reg. No. :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['patient_code']; ?></div>
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
                  ?> </div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Referrerd By :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
        </div>
      </div>

      <div style="float:left;width:32%;min-height:100px;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attended_doctor']); ?></div>
        </div>
      </div>

      <div style="float:right;width:32%;min-height:100px;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Lab Ref. No. :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['booking_code']; ?></div>
        </div>
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No. : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['mobile_no']; ?> </div>
        </div>
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Collected Date: </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['appointment_date']; ?> </div>
        </div>
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">OPD Reg. Date : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['appointment_date']; ?> </div>
        </div>
      </div>
    </div>

    <div style="float:left;width:100%;text-align:center;text-decoration:underline;font-size:medium;font-weight:bold;margin-bottom:0.5em;">TEST</div>

    <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">S.No.</th>
         <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Test Name</th>
        </tr>
      </thead>
      <tbody>
       
          <?php 
                    if(!empty($test_data)) { 
                    $i=1;  
                    foreach ($test_data as $testdata) {  
                         ?> <tr>
                           <td><?php echo $i; ?></td>
                           <td><?php echo $testdata->test_name; ?></td>
                          </tr>
                         <?php 
                     $i++;
                      }  
                      ?>
                      
                    <?php 
                    }
                    ?>
       
      </tbody>
    </table>
     <div style="float:left;width:100%;text-align:center;text-decoration:underline;font-size:medium;font-weight:bold;margin-bottom:0.5em;">Prescription</div>
    <table width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <thead>
        <tr>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Name</th>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Type</th>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Dose</th>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Frequency</th>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Duration</th>
          <th align="left" style="font-size: small;padding:5px;text-decoration: underline;">Medicine Advice</th>
    </tr>
      </thead>
      <tbody>
       
         <?php 
            if(!empty($prescription_data)) { 
            foreach ($prescription_data as $prescriptiondata) {  
                 ?>
                  <tr>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_name; ?></td>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_type; ?></td>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_dose; ?></td>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_frequency; ?></td>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_duration; ?></td>
                      <td style="font-size: small;padding:5px;"><?php echo $prescriptiondata->medicine_advice; ?></td>
                  </tr>

                 <?php 
              }  
              ?>
              
            <?php 
            }
            ?>

      </tbody>
    </table>

    <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Diagnosis :</b>
       <?php echo $form_data['diagnosis']; ?>
      </div>
    </div>

    <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Suggestion :</b>
       <?php echo $form_data['suggestion']; ?>
      </div>
    </div>

    <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Examination :</b>
       <?php echo $form_data['examination']; ?>
      </div>
    </div>

    <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Prrvious History :</b>
       <?php echo $form_data['prv_history']; ?>
      </div>
    </div>

     <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Chief Complaints :</b>
       <?php echo $form_data['chief_complaints']; ?>
      </div>
    </div>

     <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Remark :</b>
       <?php echo $form_data['remark']; ?>
      </div>
    </div>

  <?php if(!empty($form_data['appointment_date']) && $form_data['appointment_date']!=='0000-00-00'){ ?>
      <div style="float:left;width:100%;margin:1em 0;">
      <div style="font-size:small;line-height:18px;">
        <b>Next Appointment Date :</b>
       <?php echo $form_data['appointment_date']; ?>
      </div>
    </div>
<?php } ?>

</body>
</html>