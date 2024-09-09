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
<tr><td colspan="9" align="center"><h3>Billing Summary Report <?php 
$search_data = $this->session->userdata('billing_summary_search_data');

if(isset($search_data['particulars']) && !empty($search_data['particulars']))
{
  $particular_name =  get_opd_particular_name($search_data['particulars']);
  //print_r();
  echo $particular_name->particular.' ';
}

if(isset($search_data['particulars_name']) && !empty($search_data['particulars_name']))
{
 
  echo ucfirst($search_data['particulars_name']);
}



if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th>S No.</th>
    <th> Receipt No.</th>
    <th> Token No.</th>
    <th>Patient Name</th>
    <th> Reg. No. </th>
    <?php  if($users_data['parent_id']!='157'){?>
        <th> Particular </th>
      <?php } ?>
    <th>Amount</th>
    <th>Payment Mode</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {
      
      $ttl_paid=0;
      
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      
        $ttl_paid= $ttl_paid + $data->debit;
        
      ?>
   	  <tr>
        <td><?php echo $i; ?></td>
        <!--<td>< ?php  echo date('d-m-Y',strtotime($data->booking_date));  ?></td>-->
        <td><?php echo $data->booking_code; ?></td>
        <td><?php echo $data->token_no; ?></td>
        <td><?php echo $data->patient_name; ?></td>
        <td><?php echo $data->patient_code; ?></td>
        <?php  if($users_data['parent_id']!='157'){?>
        <td><?php echo $data->particular_name; ?></td>
      <?php } ?>
        <td><?php echo $data->debit; ?></td>
        <td><?php echo $data->mode; ?></td>
     </tr>
   	   <?php
   	   $i++;	
   	 }
   	 
   	 if(!empty($self_bill_coll_payment_mode))
	 {
			foreach($self_bill_coll_payment_mode as $billing_payment_mode)
			{
					?>
					<tr>
						 <td <?php  if($users_data['parent_id']!='157'){?> colspan="6" <?php } else {?> colspan="5" <?php } ?> align="right"><b><?php echo $billing_payment_mode->mode; ?></b></td> 
						 
						 <td align="right" ><b><?php echo number_format($billing_payment_mode->tot_debit,2); ?></b></td> 
					  
					  </tr>
					<?php 
			}
	 }
     ?>
      <tr>
         <td <?php  if($users_data['parent_id']!='157'){?> colspan="6" <?php } else {?> colspan="5" <?php } ?> align="right"><b>Total</b></td> 
         
         <td align="right" ><b><?php echo number_format($ttl_paid,2); ?></b></td> 
      
      </tr>
   <?php  
   }
 ?> 
</table>
</body></html>