<!DOCTYPE html>
<html>
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
      padding: 4em;
    }
  </style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

<page size="A4">
    <div style="float:left;width:100%;min-height:10px;border:1px solid #ccc;margin-bottom:2%">
      
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
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><b>Doctor Name :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php echo $doctor_name; ?></td>
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
              <td valign="top" align="left" width="50%" style="text-align:left;padding-left: 4px;"><?php echo  date('d-m-Y',strtotime($appointment_date)); ?></td>
            </tr>
            
          </table>
        </td>
      </tr>
    </table>

    

    




  </page>

</body>
</html>