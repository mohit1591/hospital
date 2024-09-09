<html>
<head>
<title>Inventory Garbage Report</title>
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
    <th>Garbage Code</th>
    <th>Remarks</th>
    <th>Total Amount</th>
    <th>Garbage Date</th>
    
   
    
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
            <td><?php echo $i; ?>.</td>
            <td>
            
            <?php echo $data->garbage_no; ?>
           </td>
           <td>
            <?php echo $data->remarks; ?>
            
            </td>
              <td>
            <?php echo $data->total_amount; ?>
            
            </td>

            <td>
            <?php echo date('d-m-Y',strtotime($data->garbage_date));?>
           </td>
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body>
</html>