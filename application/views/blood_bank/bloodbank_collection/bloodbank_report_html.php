<html><head>
<title>Blood Bank Collection Report</title>
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
  font:13px Arial;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial !important;">
 <tr>
    <th>Sr.No.</th>
     <th>Issue Code</th>
      <th>Requirement Date</th>
    <th>Patient Name</th>
    <th> Component </th> 
    <th> Component Unit </th> 
    <th>Net Amount</th>
    <th>Discount</th>
    <th>Paid Amount</th>
    <th>Balance</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {
    $grand_total_amount =0;
    $grand_total_discount=0;
    $grand_paid_amount=0;
    $grand_balance_amount=0;
   	//echo "<pre>";print_r($data_list); die;
   	 $i=1;
     $tot_data=count($data_list);
   	 foreach($data_list as $data)
   	 {
         $grand_total_amount = $grand_total_amount+$data->total_amount;
        $grand_total_discount = $grand_total_discount + $data->discount;
        $grand_paid_amount = $grand_paid_amount + $data->paid_amount;
        $grand_balance_amount = $grand_balance_amount + $data->balance;


   	   ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?>.</td>
            <td><?php echo $data->issue_code; ?></td>
            <td><?php if(!empty($data->requirement_date)){ echo date('d-m-Y',strtotime($data->requirement_date)); }else{ echo date('d-m-Y',strtotime($data->requirement_date)); }  ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	
      		 	<td><?php echo $data->component; ?></td>
      		 	<td><?php echo $data->comp_unite; ?></td>
      		 	
      		 	<td><?php echo number_format($data->total_amount,2); ?></td>
      		 	<td><?php echo number_format($data->discount,2); ?></td>
      		 	<td><?php echo number_format($data->paid_amount,2); ?></td>
      		 	<td><?php echo number_format($data->balance,2); ?></td> 
		 </tr>
   	   <?php
       if($tot_data==$i){?>
        
     <?php }
   	   $i++;	
   	 }
     ?>

      <!-- added on 02-FEB-2018 -->
          <tr>
            <td align="center" colspan="6"><b>Total</b></td>
            <td><b><?php echo number_format($grand_total_amount,2); ?></b></td>
            <td><b><?php echo number_format($grand_total_discount,2); ?></b></td>
            <td><b><?php echo number_format($grand_paid_amount,2); ?></b></td>
            <td><b><?php echo number_format($grand_balance_amount,2); ?></b></td>
          </tr>
      <!-- added on 02-FEB-2018 -->    

   <?php       
   }
 ?> 
</table>
</body></html>