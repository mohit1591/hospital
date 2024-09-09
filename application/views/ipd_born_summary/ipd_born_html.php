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
    <th>S.No.</th>
    <th>Baby Of</th>
    <th>Weight</th>
    <th>Gender</th>
    <th>Mobile No.</th>
    <th>Address</th>
    <th>Birth Date</th>
    <th>Birth time</th>
    <th>Type of Delivery</th>
    <th>Caste</th>
    <th>Religion</th>
    <th>Para</th>
    <th>Remarks</th>
    <th>Age of Mother</th>
    
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
        if($reports->gender==1)
        {
        $gender = 'Male';
        }
        else if($reports->gender==0)
        {
        $gender = 'Female';
        }
        elseif ($reports->gender==2) 
        {
        $gender = 'Others';
        }
        $age = "{$reports->age_y} Years, {$reports->age_m} Months, {$reports->age_d} Days";
                  
   	   ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?></td>
      		 	<td align="center"><?php echo $reports->baby_of; ?></td>
      		 	<td align="center"><?php echo $reports->weight; ?></td>
      		 	<td align="center"><?php echo $gender; ?></td>
      		 	
      		 	<td align="center"><?php echo $reports->mobile_no; ?></td>
      		 	<td align="center"><?php echo $reports->address; ?></td>
      		 	
      		 	
      		 	<td align="center"><?php echo date('d-m-Y',strtotime($reports->born_date)); ?></td>
      		 	<td align="center"><?php echo date('h:i A',strtotime($reports->born_time)); ?></td>

             <td align="center"><?php echo $reports->type_of_delivery; ?></td>
             <td align="center"><?php echo $reports->caste; ?></td>
             <td align="center"><?php echo $reports->religion; ?></td>
             <td align="center"><?php echo $reports->para; ?></td>
             <td align="center"><?php echo $reports->remarks; ?></td>
             <td align="center"><?php echo $age; ?></td>
      		 
      		 	
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>