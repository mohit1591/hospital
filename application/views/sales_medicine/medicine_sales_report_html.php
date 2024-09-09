<html><head>
<title>Medicine Sales Report</title>
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
*{margin:0;padding:0;box-sizing:border-box;}
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
    <th>Sr.No.</th>
	<th>Sale No.</th>
    <th>Patient Name</th>
	<th>Address</th>
    <th>Referred By</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Created Date</th>
	<th>Diseases</th>
	<th>Category</th>
    
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
      		 	<td><?php echo $data->sale_no; ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
				<td><?php echo $data->address; ?></td>
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount; ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount; ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->balance; ?></td>
      		 	<td><?php echo date('d-M-Y H:i A',strtotime($data->created_date)); ?></td>
				<td><?php echo $data->disease; ?></td>
				<td><?php echo $data->patient_category; ?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>