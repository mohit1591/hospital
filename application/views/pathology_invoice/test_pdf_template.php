<!DOCTYPE html>
<html>
<head>
  
  <style>
  body {
    background-color: #fff;
  }
  *{margin:0;padding:0;box-sizing:border-box;}
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
<body style="font-family:sans-serif, Arial;color:#333;font-size:13px;">

<page size="A4">
    <div style="float:left;width:100%;min-height:1px;border:1px solid #ccc;margin-bottom:2%">
      
    </div>

    <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:13px;margin-top:1%;">
      <tr>
        <td valign="top" align="left" width="50%" cellpadding="4px" cellspacing="0" width="100%">
          <table width="100%" cellpadding="0px 4px" cellspacing="0" style="border-collapse: collapse;font-size:12px;">
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Patient Reg. No. :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $patient_code;?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Patient Name :</b></td>
              <td valign="top" align="left" style="text-align:left;font-size:13px;"><?php echo $patient_name;?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Gender/Age :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $gender; ?> / <?php echo $patient_age; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Mobile No. :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $mobile_no; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Address :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $address; ?></td>
            </tr>
            

          </table>
        </td>
        <td valign="top" align="left" width="50%">
          <table width="100%" border="0" cellpadding="0px 4px" cellspacing="0" style="border-collapse: collapse;font-size:13px;">
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Lab Ref. No. :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $lab_reg_no; ?></td>
            </tr>
            
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Booking Date:</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo date('d-m-Y',strtotime($booking_date)); ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Referrerd By :</b></td>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><?php echo $doctor_anme; ?></td>
            </tr>
            <tr>
              <td valign="top" align="left" width="50%" style="text-align:left;font-size:13px;"><b>Doctor Name :</b></td>
              <td valign="top" align="left"  width="50%"style="text-align:left;font-size:13px;"><?php echo $doctor_anme; ?></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    
  <?php echo $presc_prescriptions; ?>

  

    
   <?php
   if(!empty($signature_data))
   {
   ?>
     <table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
      <tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;">Signature</b></td>
      </tr>
      <?php
      if(file_exists(DIRECTORY_UPLOAD_PATH.'doctor_signature/'.$signature_data[0]->sign_img))
      {
      ?>
      <tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="<?php echo ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data[0]->sign_img; ?>"></div></td>
      </tr>
      <?php
       }
      ?> 
      <tr>
      <td width="80%"></td>
        <td valign="top" align="" style="padding-right:2em;"><b style="float:right;text-align: left;"><?php echo $signature_data[0]->signature; ?></b></td>
      </tr>
      
    </table>
   <?php 
   }
   ?> 

   

   
  </page>

</body>
</html>