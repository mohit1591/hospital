<html><head>
<title>Ambulance Collection Report</title>
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
    
     <tr><td colspan="9" align="center"><h3>OPD Collection Report <?php 
$search_data = $this->session->userdata('opd_collection_resport_search_data');
if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
   <!--  <th>S.No.</th> -->
    <th>Booking No.</th>
    <th>Booking Date</th>
    <th>Patient Name</th>
  
    <th>Referred By</th>
    <!-- <th>Department</th> -->
    <th>Total Amount</th>
    <th>Discount</th>

    <th>Paid Amount</th>
    <th>Balance</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {

      // added on 31-jan-2018
        $ttl_amt=0;
        $ttl_disc=0;
        $ttl_paid=0;
        $ttl_bal=0;
      // added on 31-jan-2018

   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
        $ttl_amt  = $ttl_amt  + $data->total_amount;
        $ttl_disc = $ttl_disc + $data->discount;
        $ttl_paid = $ttl_paid + $data->paid_amount;
        $ttl_bal  = $ttl_bal  + $data->balance; 

   	   ?>
   	    <tr>
   	        <td><?php echo $data->booking_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->booking_date));  ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      	
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
      		 	<!-- <td><?php echo $data->department; ?></td> -->
      		 	<td align="right"><?php echo $data->total_amount; ?></td>
      		 	<td align="right"><?php echo $data->discount; ?></td>
      		 	<td align="right"><?php echo $data->paid_amount; ?></td>
      		 	<td align="right"><?php echo $data->balance; ?></td> 
		 </tr>
   	   <?php
   	   $i++;	
   	 }
     ?> 
      <tr>
          <td colspan="4" align="center" ><b>Total</b></td>
          <td align="right" ><b><?php echo number_format($ttl_amt,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_disc,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_paid,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_bal,2); ?></b></td>
      </tr>


   <?php  	
   }
 ?> 
</table>
</body></html>