<html><head>
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
    <tr><td colspan="10" align="center"><h3>Pathology Collection Report <?php 
$search_data = $this->session->userdata('search_data'); 
if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <!-- <th>S.No.</th> -->
    <th>Lab Ref. No.</th>
    <th>Booking Date</th>
    <th>Patient Name</th>
    <th>Referred By</th>
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
    //added on 31-jan-2018
      $total_amount=0;          // total amount
      $total_discount=0;        // total discount
      $total_net_amount=0;      // total_net_amount 
      $total_paid_amount=0;     // total_paid_amount 
      $total_balance=0;         // total_balance        
    //added on 31-jan-2018
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      //added on 31-jan-2018
        $total_amount=$total_amount + $data->total_amount;           // total amount
        $total_discount=$total_discount + $data->discount;           // total discount
        $total_net_amount=$total_net_amount + $data->net_amount;     // total_net_amount 
        $total_paid_amount=$total_paid_amount + $data->paid_amount;  // total_paid_amount
        $total_balance=$total_balance + $data->balance;              // total_balance
      //added on 31-jan-2018  

   	   ?>
   	    <tr>
   	        <!-- <td><?php //echo $i; ?>.</td> -->
      		 	<td><?php echo $data->lab_reg_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
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
     ?>
     <!-- added on 31-jan-2018 -->
     <tr>
          <td align="center" colspan="5" ><b>Total</b></td>
          <td align="right" ><b><?php echo number_format($total_amount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_discount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_net_amount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_paid_amount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_net_amount-$total_paid_amount,2); //echo number_format($total_balance,2); ?></b></td>
     </tr>
     <!-- added on 31-jan-2018 -->
   <?php   
   }
 ?> 
</table>
</body></html>