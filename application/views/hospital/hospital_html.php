<html><head>
<title>Hospital Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:12px Arial;">
 <tr>
    
    <th>Hospital Code</th>
    <th>Hospital Name</th>
    <th>Mobile No.</th>
    <th>Email</th> 
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $hospital)
   	 {
        
   	   ?>
   	    <tr>
   	        
      		 	<td style="text-align: left;"><?php echo $hospital->hospital_code; ?></td>
      		 	<td style="text-align: left;"><?php echo $hospital->hospital_name; ?></td>
            <td style="text-align: left;"><?php echo $hospital->mobile_no; ?></td>
      		 	<td style="text-align: left;"><?php echo $hospital->email; ?></td> 
      		 
		    </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>