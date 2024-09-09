<html><head>
<title>Patient Report</title>
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
    <th> Patient Code </th> 
                    
                    <th> Client Name </th> 
                    <th> Requirement Date </th>
                    <th> Mobile No. </th> 
                    
                    <th> Blood Group </th>
                   
                    <th> Created Date </th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $recipient)
   	 {
         ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?>.</td>
      		 	<td><?php echo $recipient->patient_code; ?></td>
            <td><?php echo $recipient->patient_name; ?></td>
            <td><?php echo $recipient->mobile_no; ?></td>
      		 	<td><?php echo date('d-M-Y', strtotime($recipient->requirement_date)); ?></td>
      		 	
            <td><?php echo $recipient->blood_group; ?></td>
            <td><?php echo date('d-M-Y', strtotime($recipient->requirement_date)); ?></td>
           
          
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>