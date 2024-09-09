<html><head>
<title>Stock Allotment Item Return Inventory Report</title>
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
    <th>Return Code</th>
    <th>Employee Name/Patient Name/Doctor Name</th>
    <th>Total Amount</th>
    <th>Issue Date</th>
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
            <td><?php echo $data->return_no; ?></td>
           <td><?php echo $data->member_name; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->total_amount; ?></td>
            <td><?php echo date('d-M-Y H:i A',strtotime($data->issue_date)); ?></td>
            <td><?php echo date('d-M-Y H:i A',strtotime($data->created_date)); ?></td>
        
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
</table>
</body></html>