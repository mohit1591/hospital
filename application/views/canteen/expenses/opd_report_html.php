<html>
<head>
<title>Pathology Collection Report</title>
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
</head>
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
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>S.No.</th>
    <th>Lab Reg. No.</th>
    <th>Booking Date</th>
    <th>Patient Name</th>
    <th>Doctor Name</th>
    <th>Department</th>
    <th>Total Amount</th>
    <th>Discount</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?>.</td>
      		 	<td><?php echo $data->lab_reg_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_name; ?></td>
      		 	<td><?php echo $data->department; ?></td>
      		 	<td align="right"><?php echo $data->total_amount; ?></td>
      		 	<td align="right"><?php echo $data->discount; ?></td>
      		 	<td align="right"><?php echo $data->net_amount; ?></td>
      		 	<td align="right"><?php echo $data->paid_amount; ?></td>
      		 	<td align="right"><?php echo $data->balance; ?></td> 
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>