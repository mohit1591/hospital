<!DOCTYPE html>
<html>
<head>
  <title></title>
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
      padding: 4em;
    }
  </style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

<page size="A4">
    <div style="float:left;width:100%;min-height:10px;border:0px solid #ccc;margin-bottom:2%">
      
    </div>

    <table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top:1%;">
      <tr>
        <td valign="top" align="left" width="33.33%" cellpadding="4px" cellspacing="0" width="100%">
          <table width="100%" cellpadding="0px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr>
              <td valign="top" align="left" width="55%" style="text-align:left;padding-left: 4px;"><b><?php echo $data= get_setting_value('PATIENT_REG_NO');?> :</b></td>
              <td valign="top" align="left" width="45%" style="text-align:left;padding-left: 4px;"><?php echo $patient_code;?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="55%" style="text-align:left;padding-left: 4px;"><b>Patient Name :</b></td>
              <td valign="top" align="left" width="45%" style="text-align:left;padding-left: 4px;"><?php echo $patient_name;?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="55%" style="text-align:left;padding-left: 4px;"><b>Gender/Age :</b></td>
              <td valign="top" align="left" width="45%" style="text-align:left;padding-left: 4px;"><?php echo $gender; ?>/<?php echo $patient_age; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="55%" style="text-align:left;padding-left: 4px;"><b>Referrerd By :</b></td>
              <td valign="top" align="left" width="45%" style="text-align:left;padding-left: 4px;"><?php echo $referral_doctor; ?></td>
            </tr>
          </table>
        </td>
        <td valign="top" align="left" width="33.33%">
          <table width="100%" cellpadding="0px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Doctor Name:</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 2px;"><?php echo $doctor_name; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Specialization :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php echo $specialization_id; ?></td>
            </tr>
          </table>
        </td>

       

        <td valign="top" align="left" width="33.33%">
          <table width="100%" cellpadding="0px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Appointment. No. :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php echo $booking_code; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Mobile No. :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php echo $mobile_no; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Appointment Date:</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php  if(!empty($appointment_date) && $appointment_date!='0000-00-00')echo  date('d-m-Y',strtotime($appointment_date)); ?></td>
            </tr>
            
          </table>
        </td>
      </tr>
    </table>

    <table border="1" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;">
      <tr>
      <?php  if(!empty($patient_bp)){ ?>
         <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>BP</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_bp; ?>mm/Hg</div></td></tr>
          </table>
        </td>
        <?php } if(!empty($patient_temp)){ ?>
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Temp</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_temp; ?> ℉</div></td></tr>
          </table>
        </td>
        <?php } if(!empty($patient_weight)){ ?>
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Weight</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_weight; ?> kg</div></td></tr>
          </table>
        </td>

       <?php } if(!empty($patient_height)){ ?>
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>PR</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_height; ?>  cm</div></td></tr>
          </table>
        </td>
        <?php } if(!empty($patient_spo2)){ ?>
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>Spo2%</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_spo2; ?>  % </div></td></tr>
          </table>
        </td>
        <?php } if(!empty($patient_rbs)){ ?>
        <td valign="top" align="left" width="20%">
          <table width="100%" cellpadding="1px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr><th valign="top" align="center" width="50%" style="border-bottom:1px solid black;padding-left: 4px;"><b>RBS/FBS</b></th></tr>
            <tr><td valign="top" align="left"><div style="float:left;text-align: left;padding-left: 4px;"><?php echo $patient_rbs; ?> mg/dl </div></td></tr>
          </table>
        </td>
        <?php } ?>
      </tr>
    </table>
    <?php echo $test_content; ?>
    <?php echo $presc_prescriptions; ?>

</page>

</body>
</html>