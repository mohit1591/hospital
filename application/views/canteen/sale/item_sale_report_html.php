<html><head>
<title>Item Sale Report</title>
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
    <th>Customer No.</th>
    <th>Customer Name</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Created Date</th>
    
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
      		 	<td><?php if(!empty($data->customer_code)){ echo $data->customer_code; } else { echo $data->patient_code;} ?></td>
      		 	<td><?php if(!empty($data->customer_name)){ echo $data->customer_name; } else { echo $data->patient_name.' (Patient)';} ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount; ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount; ?></td>
      		 	<td style="text-align:right;padding-right:5px;"><?php echo $data->balance; ?></td>
      		 	<td><?php echo date('d-M-Y',strtotime($data->sale_date)); ?> <?php echo date('H:i A',strtotime($data->sale_time)); ?></td>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>