<html><head>
<title>IPD Booking</title>
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
<th>IPD No.</th>
<th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
<th>Patient Name</th>
<th>Operation Date</th>
<th>Operation Time</th>
<th>Doctor Name</th>
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
   	   <tr><td><?php echo $data->ipd_no; ?></td>
<td><?php echo $data->p_code; ?></td>
<td><?php echo $data->p_name; ?></td>
<td><?php echo date('d-m-Y',strtotime($data->operation_date)); ?></td>
<td><?php echo date('h:i A',strtotime($data->operation_time)); ?></td>
<?php $doctor_name=array(); 
$doctor_list= $this->otbooking->doctor_list_by_otids($data->id);
foreach($doctor_list as $doctor_list=>$value){
$doctor_name[]=$value[0];
}
$name= implode(',',$doctor_name);?>
<td><?php echo $name; ?></td>
<td><?php echo $data->room_type; ?></td>
<td><?php echo $data->room_no; ?></td>
<td><?php echo $data->bad_no; ?></td></tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>