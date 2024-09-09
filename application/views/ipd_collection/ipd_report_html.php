<html><head>
<title>IPD Collection Report</title>
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
    
    <tr><td colspan="5" align="center"><h3>IPD Collection Report <?php 
$search_data = $this->session->userdata('ipd_collection_resport_search_data');
if(!empty($search_data['from_date'])){ echo $search_data['from_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
   <!--  <th>S.No.</th> -->
    <th>IPD No.</th>
    <th>Admission Date</th>
    <th>Patient Name</th>
    <th>Referred By</th>
    <th>Paid Amount</th>
 </tr>
 <?php

    $ttl_paid=0;
   if(!empty($data_list))
   {
   	  $i=1;
   	 foreach($data_list as $data)
   	 {
      $ttl_paid = $ttl_paid + $data->paid_amount;
   	   ?>
   	    <tr>
      		 	<td><?php echo $data->ipd_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->admission_date));  ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
      		 	<td align="right"><?php echo $data->paid_amount; ?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }
    ?>
      <tr>
          <td colspan="4" align="right"><b>Total</b></td>
          <td align="right"><b><?php echo number_format($ttl_paid,2); ?></b></td>
      </tr>

    <?php     	
   }
 ?> 
</table>
</body></html>