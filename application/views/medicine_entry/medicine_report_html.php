<html><head>
<title>Medicine Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>Sr.No.</th>
     <th>Medicine Code</th>
    <th>Medicine Name</th>
    <th>Medicine Company</th>
    <th>Packing</th>
    <th>Rack No.</th>
    <th>Mrp</th>
    <th>Purchase Rate</th>
    
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
            <td><?php echo $data->medicine_code; ?></td>
      		 	<td><?php echo $data->medicine_name; ?></td>
            <td ><?php echo $data->company_name; ?></td>
      		 	<td><?php echo $data->packing; ?></td>
            <td><?php echo $data->rack_no; ?></td>
      		 	<td><?php echo $data->mrp; ?></td>
      		 	<td><?php echo $data->purchase_rate; ?></td>
      		 
      </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>