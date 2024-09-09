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
    <th>Vendor Name</th>
    <th>Vendor Code</th>
    <th>GST</th>
    <th>Mobile No.</th>
    <th width="10%">Email</th>
    <th width="10%">Adress</th>
    <!--
    <th width="10%">Adress2</th>
    <th width="10%">Adress3</th>-->
    
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
            <td><?php echo $i; ?></td>
            <td><?php echo $data->name; ?></td>
            <td><?php echo $data->vendor_id; ?></td>
            <td><?php echo $data->vendor_gst; ?></td>
            <td><?php echo $data->mobile_no; ?></td>
            <td><?php echo $data->email; ?></td>
            <td width="10%"><?php echo nl2br($data->address); ?><br><?php echo nl2br($data->address2); ?><br><?php echo nl2br($data->address3); ?></td>
        
      		 	
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>