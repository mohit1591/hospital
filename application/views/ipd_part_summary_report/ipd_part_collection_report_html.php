<?php
$users_data = $this->session->userdata('auth_users');
?>
<html><head>
<title>IPD Particular Summary Report</title>
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
<tr><td colspan="9" align="center"><h3>IPD Particular Summary Report <?php 
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
    <th> IPD No.</th>
    <th>Patient Name</th>
    <th> Reg. No. </th>
    <th> Particular </th>
    <th> Date </th>
    <th> Qty </th>
    <th>Amount</th>
    
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
        <td><?php echo $data->booking_code; ?></td>
        <td><?php echo $data->patient_name; ?></td>
        <td><?php echo $data->patient_code; ?></td>
        <td><?php echo $data->particular_name; ?></td>
        <td><?php  echo date('d-m-Y',strtotime($data->start_date));  ?></td>
        <td><?php echo $data->quantity; ?></td>
        <td><?php echo $data->debit; ?></td>
        
     </tr>
   	   <?php
   	   $i++;	
   	 }
   	 ?>
      <tr>
         <td  colspan="7"  align="right"><b>Total</b></td> 
         
         <td align="right" ><b><?php echo number_format($ttl_paid,2); ?></b></td> 
      
      </tr>
   <?php  
   }
 ?> 
</table>
</body></html>