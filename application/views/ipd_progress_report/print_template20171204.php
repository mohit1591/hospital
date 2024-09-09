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
    

    <div class="row" style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
      <div style="float:left;width:32%;min-height:100px;margin-right:1.5%;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient Reg. No : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"> <?php echo $form_data['patient_code']; ?></div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['patient_name']; ?></div>
        </div>

        

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gender/Age :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php 
                    $gender = array('0'=>'F','1'=>'M');
                    echo $gender[$form_data['gender']]; 
                   ?>
                   / 
                   <?php 
                    $age = "";
                    if($form_data['age_y']>0)
                    {
                      $year = 'Y';
                      if($form_data['age_y']==1)
                      {
                        $year = 'Y';
                      }
                      $age .= $form_data['age_y']." ".$year;
                    }
                    if($form_data['age_m']>0)
                    {
                      $month = 'M';
                      if($form_data['age_m']==1)
                      {
                        $month = 'M';
                      }
                      $age .= ", ".$form_data['age_m']." ".$month;
                    }
                    if($form_data['age_d']>0)
                    {
                      $day = 'D';
                      if($form_data['age_d']==1)
                      {
                        $day = 'D';
                      }
                      $age .= ", ".$form_data['age_d']." ".$day;
                    }
                     echo $age; 
                    $report_date='-';

                    if(!empty($form_data['report_date']) && $form_data['report_date']!='0000-00-00')
                    {
                      $report_date = date('d-m-Y',strtotime($form_data['report_date']));
                    }

                  ?> </div>
        </div>

        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Referrerd By :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['referral_doctor']); ?></div>
        </div>
      </div>

     

      <div style="float:right;width:32%;min-height:100px;">
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">IPD No. :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['ipd_no']; ?></div>
        </div>
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No. : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $form_data['mobile_no']; ?> </div>
        </div>
        
        
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">IPD Reg. Date : </div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $report_date; ?> </div>
        </div>



     
        <div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo get_doctor_name($form_data['attend_doctor_id']); ?></div>
        </div>
     
<div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Admission Date :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo  date('d-m-Y h:i:s',strtotime($form_data['admission_date'])); ?></div>
        </div>

<div style="float:left;width:100%;margin-bottom:2px;">
          <div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Discharge Date :</div>
          <div style="float:left;width:50%;font-size:small;line-height:17px;"><?php if($form_data['discharge_date']!='0000-00-00 00:00:00'){ echo  date('d-m-Y h:i:s',strtotime($form_data['discharge_date'])); } ?></div>
        </div>


      </div>
    </div>


    <table  width="100%" cellpadding="0" cellspacing="0" border="" style="border-collapse:collapse;">
      <tr>
        
        <td valign="top" align="left" width="20%">
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
        </td>
      </tr>
    </table>



    <?php 
    
    foreach ($progress_report_tab_setting as $tab_value) 
    {
    
   if(strcmp(strtolower($tab_value->setting_name),'prescription')=='0')
   { 
  if(!empty($form_data['prescription'])){ ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $prescription_name =  $tab_value->setting_value; } else { echo  $prescription_name =  $tab_value->var_title; } ?> :</b>
       <?php echo $form_data['prescription']; ?>
      </div>
    </div>
 <?php } } 
  if(strcmp(strtolower($tab_value->setting_name),'suggestions')=='0')
    {
 if(!empty($form_data['suggestions'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $suggestions =  $tab_value->setting_value; } else { echo  $suggestions =  $tab_value->var_title; } ?> :</b>
       <?php echo $form_data['suggestions']; ?>
      </div>
    </div>
<?php } } 
if(strcmp(strtolower($tab_value->setting_name),'dressing')=='0')
{
 if(!empty($form_data['dressing'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $dressing =  $tab_value->setting_value; } else { echo  $dressing =  $tab_value->var_title; } ?> :</b>
       <?php echo $form_data['dressing']; ?>
      </div>
    </div>
<?php } }
if(strcmp(strtolower($tab_value->setting_name),'remarks')=='0')
{  
if(!empty($form_data['remarks'])){  ?>
    <div style="float:left;width:100%;margin:1em 0 0;">
      <div style="font-size:small;line-height:18px;">
        <b><?php  if(!empty($tab_value->setting_value)) { echo $remarks =  $tab_value->setting_value; } else { echo  $remarks =  $tab_value->var_title; } ?> :</b>
       <?php echo $form_data['remarks']; ?>
      </div>
    </div>
<?php } }
} ?>
</page>
</body>
</html>