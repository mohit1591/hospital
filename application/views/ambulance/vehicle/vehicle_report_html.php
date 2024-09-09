<html><head>
<title>Driver list</title>
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
   
    <th>Vehicle No.</th>
    <th>Chassis No.</th>
    <th>Engine No.</th>
    <th>Vendor type</th>
    <th>Reg. Date</th>
    <th>Reg. Exp.</th>
    <th>Created Date</th>
    <th>Charge</th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
      if($reports->vehicle_type==1)
      {
        $vehicle_type='Self-Owned';
      }
      else{
        $vehicle_type='Leased';
      }
   	   ?>
   	    <tr>
   	     
        <td><?php echo $reports->vehicle_no; ?></td>
        <td><?php echo $reports->chassis_no; ?></td>
        <td><?php echo $reports->engine_no; ?></td>
        <td><?php echo $vehicle_type; ?></td>
        
        <td><?php echo date('d-M-Y',strtotime($reports->registration_date)); ?></td>
        
        <td><?php echo date('d-M-Y',strtotime($reports->registration_exp_date)); ?></td>
        
        <td><?php echo  date('d-M-Y',strtotime($reports->created_date)); ?></td>
        
         <td><?php echo $reports->charge; ?></td>
        
        
        
        </tr>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>