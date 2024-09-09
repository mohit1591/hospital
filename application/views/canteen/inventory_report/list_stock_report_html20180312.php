<?php $this->load->model('item_manage/item_manage_model','item_manage'); ?>
<html>
<head>
<title>Medicine Sales Report</title>
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
    <th> Item Code</th> 
    <th> Item Name</th> 
    <th> MRP</th>
    <th>Category</th>
    <th>Quantity</th>
    <th>Unit</th>
    
   
    
 </tr>
 <?php
   if(!empty($branch_inventory_list))
   {
     //echo "<pre>";print_r($data_list); die;
     $i=1;
     foreach($branch_inventory_list as $item_manage)
     {
       ?>
        <tr>
            <td><?php echo $i; ?>.</td>
            <td>
            
            <?php echo $item_manage->item_code; ?>
           </td>
           <td>
            <?php echo $item_manage->item; ?>
            
            </td>
              <td>
            <?php echo $item_manage->price; ?>
            
            </td>

            <td>
            <?php echo $item_manage->category;?>
           </td>
            <td>
            <?php 

            $qty_data = get_item_quantity($item_manage->id,$item_manage->category_id);
            $medicine_total_qty = $qty_data['total_qty'];
            echo $medicine_total_qty;
            ?>
           </td>
           <td><?php echo $item_manage->unit; ?></td>

           
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body>
</html>