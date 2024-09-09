<html>
<head>
<title>Inventory Dialysis Report</title>
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
*{margin:0;padding:0;box-sizing:border-box;}
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
    <th>Sr.No.</th>
    <th>Booking Code</th>
    <th>Patient Name</th>
    <th>Item Name</th> 
    <th>Qty</th>
   
    <th>date</th>
</tr>
 <?php
   if(!empty($branch_inventory_list))
   {
     //echo "<pre>";print_r($data_list); die;
     $i=1;
     foreach($branch_inventory_list as $data)
     {
       ?>
        <tr>
          <td><?php echo $i; ?></td>
          <td><?php echo $data->booking_code; ?></td>
          <td><?php echo $data->patient_name; ?></td>
          <td><?php echo $data->item_name; ?></td>
          <td><?php echo $data->qty; ?></td>
          <td><?php echo date('d-m-Y',strtotime($data->created_date));?></td>
    </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body>
</html>