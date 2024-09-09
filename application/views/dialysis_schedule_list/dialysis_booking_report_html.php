<html>
<head>
<title>Dailysis Schedule Report</title>
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
</head>
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
</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
    <th> <?php echo $data_patient_reg = get_setting_value('PATIENT_REG_NO'); ?></th> 
    <th> Patient Name </th> 
    <th> Dialysis Booking No </th> 
    <th> Dialysis Room </th> 
    <th> Assign Doctors</th> 
    <th>Dialysis Booking Date</th> 
   
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
   	       	<td><?php echo $data->p_code; ?></td>
      		 	<td><?php echo $data->p_name; ?></td>
            <td><?php echo $data->booking_code; ?></td>
            <td><?php echo $data->dia_room; ?></td>
      		 	
      		 	
      		 	<td>

          <?php $doctor_name=array(); 
          $doctor_list= $this->dialysis_schedule_list->doctor_list_by_otids($data->id);
          foreach($doctor_list as $doctor_list=>$value){
          $doctor_name[]=$value[0];
          }
          $name= implode(',',$doctor_name);?>
            <?php echo $name; ?></td>
      		<td>

                <?php if($data->dialysis_booking_date=='0000-00-00')
                     {
                      $date_time='';
                     }
                     else
                     {
                      $date_time= date('d-m-Y',strtotime($data->dialysis_booking_date));
                     }

          echo $date_time; ?></td>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>