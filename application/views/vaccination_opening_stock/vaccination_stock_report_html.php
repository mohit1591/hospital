<html>
<head>
<title>Medicine Stock Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>S.No.</th>
    <th>Vaccination Name.</th>
    <th>Packing</th>
    <th>Mrp</th>
    <th>Purchase Rate</th>
    <th>Discount</th>
    <th>Vaccination Company</th>
    <th>Rack No</th>
    
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
   	        <td><?php echo $i; ?>.</td>
      		 	<td><?php echo $data->vaccination_name; ?></td>
      		 	<td><?php echo $data->packing; ?></td>
      		 	<td><?php echo $data->mrp; ?></td>
      		 	<td><?php echo $data->purchase_rate; ?></td>
      		 	<td><?php echo $data->discount; ?></td>
      		 	<td align="right"><?php echo $data->company_name; ?></td>
      		 	<td align="right"><?php echo $data->rack_no; ?></td>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>