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
    <th>Appointment No.</th>
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th>Patient Name</th>
    <th>Dialysis Date</th>
    <th>Dialysis Time</th>
    <th>Doctor Name</th>
    <th>Dialysis Name</th>
    
   
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	     
   	     if($data->dialysis_time=="00:00:00")
        {
            $dialysis_time='';
            if(!empty($data->time_value))
            {
              $dialysis_time=$data->time_value;  
            }
            
        }
        else
        {
            $dialysis_time=date('h:i A',strtotime($data->dialysis_time));
        }
   	   ?>
   	    <tr>
   	        <td><?php echo $data->booking_code; ?>.</td>
      		 	<td><?php echo $data->p_code; ?></td>
      		 	<td><?php echo $data->p_name; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->dialysis_date)); ?></td>
      		 	<td><?php echo $dialysis_time; ?></td>
      		 	<td>

          <?php $doctor_name=array(); 
          $doctor_list= $this->dialysisbooking->doctor_list_by_otids($data->id);
          foreach($doctor_list as $doctor_list=>$value){
          $doctor_name[]=$value[0];
          }
          $name= implode(',',$doctor_name);?>
            <?php echo $name; ?></td>
      		 	<td><?php echo $data->ot_pack_name; ?></td>
           
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>