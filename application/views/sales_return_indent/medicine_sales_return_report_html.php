<html><head>
<title>Medicine Sales Return Report</title>
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
} 
td
{
  padding-left:3px;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;border-collapse:collapse;">
 <tr>
    <th>Sr.No.</th>
    <th>Sale No.</th>
    <th>Return No.</th>
    <th>Indent Name</th>
    <th>Return Date</th>
    
 </tr>
 <?php
   if(!empty($data_list))
   {
     $i=1;
     foreach($data_list as $data)
     {
       ?>
        <tr>
            <td><?php echo $i; ?>.</td>
            <td><?php echo $data->sale_no; ?></td>
            <td><?php echo $data->return_no; ?></td>
            <td><?php echo $data->indent; ?></td>
            <td><?php echo date('d-M-Y',strtotime($data->sale_date)); ?></td>
        
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body></html>