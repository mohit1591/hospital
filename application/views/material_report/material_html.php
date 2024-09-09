<html><head><title>IPD Booking</title>
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
 <tr> 
    <th>S. No.</th>
                    <th>OPD No.</th>
                    <th>Patient Name</th>
                    <th>Treatment Name</th> 
                    <th>Teeth Type </th>
                    <th>Tooth No. </th> 
                    <th>Material </th> 
                    <th>Remarks</th>
                    <th>Date</th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
       ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?></td>
   	            <td align="center"><?php echo $reports->booking_code; ?></td>
      		 	<td align="center"><?php echo $reports->patient_name; ?></td>
      		 	<td align="center"><?php echo $reports->treatment_name; ?></td>
      		 	<td align="center"><?php echo $reports->teeth_name; ?></td>
      		 	<td align="center"><?php echo $reports->tooth_number; ?></td>
      		 	<td align="center"><?php echo $reports->treatment_type_id; ?></td>
      		 	<td align="center"><?php echo $reports->treatment_remarks; ?></td>
      		 	
      		 	<td align="center"><?php echo date('d-m-Y',strtotime($reports->booking_date)); ?></td>
      	 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>