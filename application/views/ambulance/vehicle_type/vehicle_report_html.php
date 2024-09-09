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
    <th>ID</th>
    <th>Vehicle Type</th>
    <th>Local Min Charge</th>
    <th>Out Station Min Charge</th>
    <th>Ceated Date</th>
    
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
   	     <td><?php echo $i; ?></td>
        <td><?php echo $reports->type; ?></td>
        <td><?php echo $reports->local_min_amount; ?></td>
        <td><?php echo $reports->outstation_min_amount; ?></td>
        <td><?php echo date('d-M-Y',strtotime($reports->created_date)); ?></td>
        
        </tr>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>