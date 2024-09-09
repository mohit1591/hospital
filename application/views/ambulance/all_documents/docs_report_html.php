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
    <th>S.No.</th>
    <th>Document name</th>
    <th>Vehicle No.</th>
    <th>Renewal Date</th>
    <th>Expiry Date</th>
    <th>Status</th>
    <th>Created Date</th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
   	     if($reports->status==1)
            {
                $status = 'Active';
            }   
            else{
                $status = 'Inactive';
            }
   	   ?>
   	    <tr>
   	     <td><?php echo $i; ?></td>
        <td><?php echo $reports->document; ?></td>
        <td><?php echo $reports->vehicle_no; ?></td>
        <td><?php echo date('d-m-Y',strtotime($reports->renewal_date)); ?></td>
        <td><?php echo date('d-m-Y',strtotime($reports->expiry_date)); ?></td>
        <td><?php echo $status; ?></td>
        <td><?php echo date('d-m-Y h:i A',strtotime($reports->created_date)); ?></td>
        </tr>
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>