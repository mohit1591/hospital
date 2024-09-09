<html><head><title>IPD Booking</title>
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
td
{
  padding-left:3px;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr> 
    <th>S.No.</th>
	<th>UHID No.</th>
    <th>Patient Name</th>
    <th>Mobile No.</th>
    <th>Address</th>
    <th>Death Date</th>
    <th>Death time</th>
	<th>Date/Time of Admission</th>
	<th>Cause of Death</th>
	<th>Treating Doctor</th>
	<th>Caste and Religion</th>
	<th>Mothers name</th>
	<th>Occupation of the patient</th>
    
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
          
   	   ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?></td>
			   <td align="center"><?php echo $reports->patient_code; ?></td>
      		 	<td align="center"><?php echo $reports->patient_name; ?></td>
      		 	<td align="center"><?php echo $reports->mobile_no; ?></td>
      		 	<td align="center"><?php echo $reports->address; ?></td>
      		 	<td align="center"><?php echo date('d-m-Y',strtotime($reports->death_date)); ?></td>
      		 	<td align="center"><?php echo date('h:i A',strtotime($reports->death_time)); ?></td>
      		 	
				   <td align="center"><?php echo date('d/m/Y',strtotime($reports->admission_date)).'<br> ('.date('H:i A',strtotime($reports->admission_time)).')'; ?></td>
				   <td align="center"><?php echo $reports->cause_of_death; ?></td>
				   <td align="center"><?php echo 'Dr. '.get_doctor_name($reports->attend_doctor_id); ?></td>
				   <td align="center"><?php echo get_religion_name($reports->religion_id); ?></td>
				   <td align="center"><?php echo $reports->mother; ?></td>
				   <td align="center"><?php echo $reports->occupation; ?></td>
      		 	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>