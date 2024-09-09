<html>
<head>
<title>Inventory Allotment Report</title>
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
      if($get['section_type']==3) 
       {?>
      <th>Issue Code</th>
       <?php }
        ?>

    <?php 
      if($get['section_type']==4) 
       {?>
      <th>Return Code</th>
       <?php }
        ?>
     <th>Patient Name/Employee Name/Doctor Name</th>
    
    <th>Total Amount</th>
    <?php 
    if($get['section_type']==3) 
     {?>
    <th>Issue date</th>
     <?php }
      ?>
        <?php 
    if($get['section_type']==4) 
     {?>
    <th>Return date</th>
     <?php }
      ?>
   
    
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
            if($get['section_type']==3) 
            {?>
            <?php echo $data->issue_no; ?>
            <?php }?>

            <?php 
            if($get['section_type']==4) 
            {?>
            <?php echo $data->return_no; ?>
            <?php }?>

            </td>
             <td><?php echo $data->member_name; ?></td>
             <td><?php echo $data->total_amount;?></td>
            <td>
            <?php echo date('d-m-Y',strtotime($data->issue_date));?>
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