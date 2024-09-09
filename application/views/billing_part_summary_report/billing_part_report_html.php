<?php
$users_data = $this->session->userdata('auth_users');
?>
<html><head>
<title>Billing Collection Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font-family:arial, sans-serif;font-size:13px;">
<tr><td colspan="6" align="center"><h3>IPD Particular Summary Report <?php 
$search_data = $this->session->userdata('part_collection_report');


if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th>S No.</th>
    <th> Particular</th>
    
   
    <th>Price</th>
    <th> QTY </th>
    <th>Net Amount</th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
      
      $ttl_paid=0;
      
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      
        $ttl_paid= $ttl_paid + $data->PersonalAmount;
        ?>
   	  <tr>
        <td><?php echo $i; ?></td>
        <td><?php echo $data->particulars; ?></td>
        <td><?php echo $data->amount; ?></td>
        <td><?php echo $data->PersonalCount+0; ?></td>
        <td><?php echo $data->PersonalAmount; ?></td>
        
     </tr>
   	   <?php
   	   $i++;	
   	 }
   	 
   	
     ?>
      <tr>
         <td colspan="4" align="right"><b>Total</b></td> 
         
         <td align="left" ><b><?php echo number_format($ttl_paid,2); ?></b></td> 
      
      </tr>
   <?php  
   }
 ?> 
</table>
</body></html>