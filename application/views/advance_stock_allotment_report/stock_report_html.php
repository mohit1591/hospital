<html><head>
<title>Stock Purchase Item Inventory Collection Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" colspan="10" border="1px" style="font:13px Arial;">
     <tr>
        <th> Branch Name </th>
        <th> Item Name </th> 
        <!--<th> Item Code </th> 
        <th> Quantity </th>-->
        <th> Date </th> 
        
     </tr>
 <?php
   if(!empty($data_list))
   {
    
	$i=1;
	$tot_data=count($data_list);
     foreach($data_list as $data)
     {
          $get_items = get_items_allot($data->parent_id,$data->created_date);
		?>
        <tr>
            <td style="text-align:center;padding-right:5px;"><?php echo $data->branch_name; ?></td>
            
            <td style="text-align:center;padding-right:5px;"><?php echo $get_items; ?></td>
			<!--<td style="text-align:center;padding-right:5px;"><?php echo $data->item_code; ?></td>
            <td style="text-align:center;padding-right:5px;"><?php echo $data->quantity; ?></td>-->
            <td style="text-align:center;padding-right:5px;"><?php echo date('d-M-Y',strtotime($data->created_date)); ?></td>
            
        
     </tr>
    <?php
  	   $i++;	
   	 }
     }
 ?>
</table>
</body></html>