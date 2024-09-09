<html><head>
<title>Pathology Inventory Report</title>
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
    <tr><td colspan="10" align="center"><h3>Pathology Inventory Report <?php 
    $search_data = $this->session->userdata('search_data'); 
    if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <!-- <th>S.No.</th> -->
    <th>Lab Ref. No.</th>
    <th>Booking Date</th>
    <th>UHID No.</th>
    <th>Patient Name</th>
    <th>Referred By</th>
    <th>Item Name</th>
    <th>Qty</th>
    </tr>
 <?php
   if(!empty($data_list))
   {
      //added on 31-jan-2018
      $total_amount=0;          // total amount
      $i=1;
   	 foreach($data_list as $data)
   	 {
       ?>
   	    <tr>
   	       <td><?php echo $data->lab_reg_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
            <td><?php echo $data->patient_code; ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
      		 	<td><?php echo $data->item; ?></td>
      		 	<td align="right"><?php echo $data->qty; ?></td>
     
		 </tr>
   	   <?php
   	   $i++;	
   	 }
     ?>
     <!-- added on 31-jan-2018 -->
    <!--  <tr>
          <td align="center" colspan="5" ><b>Total</b></td>
          <td align="right" ><b><?php //echo number_format($total_amount,2); ?></b></td>
 
     </tr> -->
     <!-- added on 31-jan-2018 -->
   <?php   
   }
 ?> 
</table>
</body></html>