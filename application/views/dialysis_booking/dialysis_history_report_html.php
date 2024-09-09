<html><head>
<title>Dialysis Report</title>
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
} 
td
{
  padding-left:3px;
}
</style></head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
     <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th>Booking No.</th>
    
    <th>Patient Name</th>
    <th>Dialysis Name</th>
    <th>Dialysis Date</th>
    <th>Dialysis Time</th>
    <th>Room Type</th>
    <th>Room No.</th>
    <th>Bed No.</th>
   
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	   ?>
   	    <tr>
   	        
        <td><?php echo $data->patient_code; ?></td>
        <td><?php echo $data->booking_code; ?></td>
        <td><?php echo $data->patient_name; ?></td>
        <td><?php echo $data->dialysiss_name; ?></td>
        <td><?php echo date('d-m-Y',strtotime($data->dialysis_date)); ?></td>
        <td><?php echo date('h:i A',strtotime($data->dialysis_time)); ?></td>
        <td><?php echo $data->room_type; ?></td>
        <td><?php echo $data->room_no; ?></td>
        <td><?php echo $data->bad_no; ?></td>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>