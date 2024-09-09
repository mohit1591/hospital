<html><head>
<title>IPD Discharge Summary Report</title>
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
    <th>IPD No.</th>
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th>Patient Name</th>
    <th>Mobile No.</th>
   <th>Created Date</th>

 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $opds)
   	 {
       $create_date = date('d-M-Y H:i A',strtotime($opds->created_date));
   	   ?>
   	    <tr>
   	        <td align="center"><?php echo $i; ?></td>
      		<td align="center"><?php echo $opds->ipd_no; ?></td>
      		<td align="center"><?php echo $opds->patient_code; ?></td>
            <td align="center"><?php echo $opds->patient_name; ?></td>
      		<td align="center"><?php echo $opds->mobile_no; ?></td>
            <td align="center"><?php echo $create_date; ?></td>
      	</tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>