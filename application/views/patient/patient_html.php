<html><head>
<title>Patient Report</title>
<?php
if($print_status==1)
{
?>
<script type="text/javascript">
window.print();
window.onfocus=function(){ window.close();}
</script>
<?php	
}
?>
<style>
body
{
	font-size: 10px;
}	
td,th
{
	padding-left:3px;
  font:13px Arial;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr> 
    <th style="text-align: left;"><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th style="text-align: left;">Patient Name</th>
    <th style="text-align: left;">Mobile No.</th>
    <th style="text-align: left;">Gender</th>
    <th style="text-align: left;">Age</th>
    <th style="text-align: left;">Address</th>
    <th style="text-align: left;">Created Date</th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $patients)
   	 {
                $genders = array('0'=>'Female','1'=>'Male','2'=>'Other');
                $gender = $genders[$patients->gender];
                $age_y = $patients->age_y;
                $age_m = $patients->age_m;
                $age_d = $patients->age_d;
                $age = "";
                if($age_y>0)
                {
                $year = 'Years';
                if($age_y==1)
                {
                  $year = 'Year';
                }
                $age .= $age_y." ".$year;
                }
                if($age_m>0)
                {
                $month = 'Months';
                if($age_m==1)
                {
                  $month = 'Month';
                }
                $age .= ", ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= ", ".$age_d." ".$day;
                }
                $patient_age =  $age;
   	   ?>
   	    <tr> 
      		 	<td><?php echo $patients->patient_code; ?></td>
      		 	<td><?php echo $patients->patient_name; ?></td>
            <td><?php echo $patients->mobile_no; ?></td>
              <td><?php echo $gender; ?></td> 
      		 	<td><?php echo $patient_age; ?></td>
      		 
      		 	<td><?php echo $patients->address; ?></td>
            <td><?php echo date('d-m-Y h:i A', strtotime($patients->created_date)); ?></td>
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>