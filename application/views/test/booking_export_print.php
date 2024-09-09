<html><head>
<title>Test Booking Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>S.No.</th>
    <th>Lab Reg. No.</th>
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    
    <th>Patient Name</th>
<th>Referred By</th>
    <th>Booking Date</th>
    <th>Net Amount</th> 
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Status</th>  
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $test)
   	 { 
          $create_date = date('d-M-Y H:i A',strtotime($test->created_date));
          $booking_date = date('d-M-Y',strtotime($test->booking_date)); 
          $doctor_name = get_doctor($test->referral_doctor);
          $complate_status = array('0'=>'Pending','1'=>'Complete');
   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?>.</td>
      		 	<td><?php echo $test->lab_reg_no; ?></td>
      		 	<td><?php echo $test->patient_code; ?></td>
            <td><?php echo $test->patient_name; ?></td>
            <td><?php echo $test->doctor_hospital_name; ?></td>
            <td><?php echo $booking_date; ?></td> 
            <td><?php echo $test->net_amount; ?></td>
            <td><?php echo $test->paid_amount1; ?></td>
      		 	<td><?php echo $test->balance1; ?></td> 
      		 	<td><?php echo $complate_status[$test->complation_status]; ?></td>
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>