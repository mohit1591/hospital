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
  
    <th>Driver name</th>
     <th>DL No.</th>
    <th>Mobile no</th>
   
    <th>Address</th>
    
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
   	     
        <td><?php echo $reports->driver_name; ?></td>
        <td><?php echo $reports->licence_no; ?></td>
        <td><?php echo $reports->mobile_no; ?></td>
        
        <td><?php echo $reports->address; ?></td>
        
        </tr>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>