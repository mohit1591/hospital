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
    

    <?php 
    $simulation = get_simulation_name($all_detail[0]->simulation_id);

    $address = $all_detail[0]->address;
    $pincode = $all_detail[0]->pincode;
    $booking_date_time='';
    //echo $all_detail[0]->admission_date;
    if(!empty($all_detail[0]->admission_date) && $all_detail[0]->admission_date!='0000-00-00')
    {
        $booking_date_time = date('d-m-Y',strtotime($all_detail[0]->admission_date)); 
    }
       //.' '.$all_detail['prescription_list'][0]->booking_time   
    
    $patient_address = $address.' - '.$pincode;

   

   

   
    


      $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail[0]->gender];
        $age_y = $all_detail[0]->age_y; 
        $age_m = $all_detail[0]->age_m;
        $age_d = $all_detail[0]->age_d;

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
    ?>

<div class="row" style="float:left;width:100%;min-height:100px;*border:1px solid #eee;clear:both;margin-top:1em;">
<div style="float:left;width:50%;min-height:100px;">
<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;"><?php echo $data= get_setting_value('PATIENT_REG_NO');?> :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $all_detail[0]->patient_code; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Patient Name :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $simulation.' '.$all_detail[0]->patient_name; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Gender/Age :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $gender_age; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Address :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $patient_address; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Mobile No. :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $all_detail[0]->mobile_no; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Progress Report Date :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo date('d-m-Y',strtotime($all_detail[0]->report_date)); ?></div>
</div>


</div>

<div style="float:right;width:50%;min-height:100px;">
<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">IPD No. :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php  echo $all_detail[0]->ipd_no;?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">IPD Reg. Date :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $booking_date_time; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Doctor Name :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo 'Dr. '.get_doctor_name($all_detail[0]->attend_doctor_id); ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Room Type :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $all_detail[0]->room_category; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Room No. :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $all_detail[0]->room_no; ?></div>
</div>

<div style="float:left;width:100%;margin-bottom:2px;">
<div style="float:left;width:50%;font-weight:bold;font-size:small;line-height:17px;">Bed No. :</div>

<div style="float:left;width:50%;font-size:small;line-height:17px;"><?php echo $all_detail[0]->bad_no; ?></div>
</div>
</div>
</div>


  



    <?php 


     $patient_pres = '';
            $i=1;
            $patient_pres .= '<table border="1" width="100%" style="border-collapse:collapse;font:13px Arial;margin-top:1%;float:left;">';
            $patient_pres .= '<thead>
                <th>BP</th><th>Temp </th><th>Weight  </th><th>PR  </th><th>Spo2  </th><th> RBS/FBS </th>';
                  
             $patient_pres .= '</thead><tbody>';
         
           //if(!empty($all_detail['progress_report_list']))
           //{
                
           //foreach($all_detail['progress_report_list'] as $prescription_presc)
            //{ 
                          
                  $patient_pres .= '<tr>
                <td>'.$all_detail[0]->patient_bp.'</td><td>'.$all_detail[0]->patient_temp.'</td><td>'.$all_detail[0]->patient_weight.'</td><td>'.$all_detail[0]->patient_height.'</td><td>'.$all_detail[0]->patient_spo2.'</td><td>'.$all_detail[0]->patient_rbs.'</td>';





                $patient_pres .= '</tr>';

                 
                 
                 //$i++;
                            
            //}  

         // }    
             
           $patient_pres .= '</tbody></table>';
        foreach ($progress_report_tab_setting as $tab_value) 
        { 
            if(strcmp(strtolower($tab_value->setting_name),'prescription')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->prescription)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->prescription.'
                      </div>
                    </div>';
                    }
            }

           if(strcmp(strtolower($tab_value->setting_name),'dressing')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->dressing)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->dressing.'
                      </div>
                    </div>';
                    }
            }
           if(strcmp(strtolower($tab_value->setting_name),'suggestions')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->suggestion)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->suggestion.'
                      </div>
                    </div>';
                    }
            }
           if(strcmp(strtolower($tab_value->setting_name),'remarks')=='0')
            {

                if(!empty($tab_value->setting_value)) { $Types =  $tab_value->setting_value; } else { $Types =  $tab_value->var_title; }

                    if(!empty($all_detail[0]->remarks)){  
                    $patient_pres .= '<div style="float:left;width:100%;margin:1em 0 0;">
                      <div style="font-size:small;line-height:18px;"><b>'.$Types.' :</b>
                       '.$all_detail[0]->remarks.'
                      </div>
                    </div>';
                    }
            }
        }
    /*
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
}*/  echo $patient_pres; ?>
</page>
</body>
</html>