<html><head>
<title>Medicine Stock Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1" style="font:13px Arial;border-collapse: collapse;">
 <tr>
    <th>Sr.No.</th>
    <th>Bank Name.</th>
    <th>A/c Name</th>
    <th>A/c No.</th>
    <th>Amount</th>
    <th>Deposit Date</th>
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
      		 	<td><?php echo $data->bank_name; ?></td>
            <td><?php echo $data->account_holder; ?></td>
      		 	<td><?php echo $data->account_no; ?></td>
            <td><?php echo $data->amount; ?></td>
            <td><?php echo date('d-m-Y',strtotime($data->deposite_date)); ?></td>
   	 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>