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
    <th>ID</th>
    <th>Code</th>
    <th>Particular Name</th>
    <th>Charges</th>
    <th>Panel Charge</th> 
    
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
        <td><?php echo $reports->particular_code; ?></td>
        <td><?php echo $reports->particular; ?></td>
        <td><?php echo $reports->charge; ?></td>
        <td><?php echo $reports->panel_charge; ?></td>
        </tr>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>