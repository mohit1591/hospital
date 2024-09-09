<html>
<head>
<title>Inventory Purchase Report</title>
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
      <?php 
      if($get['section_type']==1) 
       {?>
      <th>Purchase No.</th>
       <?php }
        ?>

    <?php 
      if($get['section_type']==2) 
       {?>
      <th>Return No.</th>
       <?php }
        ?>
      <?php 
      if($get['section_type']==1) 
       {?>
      <th>Purchase Date</th>
       <?php }
        ?>

         <?php 
      if($get['section_type']==2) 
       {?>
      <th>Return Date</th>
       <?php }
        ?>
   

    <th>Vendor Name</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
   
    
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
            <?php 
            if($get['section_type']==1) 
            {?>
            <?php echo $data->purchase_no; ?>
            <?php }?>

            <?php 
            if($get['section_type']==2) 
            {?>
            <?php echo $data->return_no; ?>
            <?php }?>

            </td>

            <td>
            <?php echo date('d-m-Y',strtotime($data->purchase_date));?>
           </td>
            <td><?php echo $data->name; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount;  //$pay_mode[$branchs->pay_mode]; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount;  //$pay_mode[$branchs->pay_mode]; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->balance;  //$pay_mode[$branchs->pay_mode]; ?></td>
           
        
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body>
</html>