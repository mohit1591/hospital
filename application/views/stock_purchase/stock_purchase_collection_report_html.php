<html><head>
<title>Stock Purchase Item Inventory Collection Report</title>
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
  font-family: Arial;
} 
td,th
{
  padding-left:3px;
  font-size:13px;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" colspan="10" border="1px" style="font:13px Arial;">
 <tr>
    <th>Sr.No.</th>
    <th>Purchase Code</th>
    <th>Vendor Name</th>
	<th>Total Amount </th>
    <th>Discount </th>	
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Purchase Date</th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
     //echo "<pre>";print_r($data_list); die;
    $grand_total_amount =0;
    $grand_total_discount=0;
    $grand_paid_amount=0;
    $grand_balance_amount=0;
    $grand_net_amount=0;
	$i=1;
	$tot_data=count($data_list);
     foreach($data_list as $data)
     {
		$grand_total_amount = $grand_total_amount+$data->total_amount;
        $grand_total_discount = $grand_total_discount + $data->discount;
        $grand_paid_amount = $grand_paid_amount + $data->paid_amount;
        $grand_balance_amount = $grand_balance_amount + $data->balance;
        $grand_net_amount = $grand_net_amount + $data->net_amount; 	  
       ?>
        <tr>
            <td><?php echo $i; ?>.</td>
            <td><?php echo $data->purchase_no; ?></td>
            <td><?php echo $data->name; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->total_amount; ?></td>
			<td style="text-align:right;padding-right:5px;"><?php echo $data->discount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->balance; ?></td>
            <td><?php echo date('d-m-Y',strtotime($data->purchase_date)); ?></td>
        
     </tr>
    <?php
       if($tot_data==$i){ 
	   ?>       
     <?php }
   	   $i++;	
   	 }
     ?>

      <!-- added on 02-FEB-2018 -->
          <tr>
            <td align="center" colspan="3"><b>Total</b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($grand_total_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($grand_total_discount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($grand_net_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($grand_paid_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($grand_balance_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;">&nbsp;</td>  
          </tr>
      <!-- added on 02-FEB-2018 -->    

   <?php       
   }
 ?>
</table>
</body></html>