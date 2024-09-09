<html><head>
<title>Stock Purchase Return Item Inventory Report</title>
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
*{margin:0;padding:0;box-sizing:border-box;}
body
{
  font-size: 10px;
  font-family: Arial;
} 
td,th
{
  padding-left:3px;
  font-size:13px;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
 <tr>
    <th>Sr.No.</th>
    <th>Purchase No.</th>
    <th>Items</th>
    <th>Return No.</th>
    <th>Vendor Name</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Created Date</th>
    
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
            <td><?php echo $data->purchase_no; ?></td>
            <td><?php echo $data->items; ?></td>
            <td><?php echo $data->return_no; ?></td>
           <td><?php echo $data->name; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->balance; ?></td>
            <td><?php echo date('d-M-Y H:i A',strtotime($data->created_date)); ?></td>
        
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body></html>