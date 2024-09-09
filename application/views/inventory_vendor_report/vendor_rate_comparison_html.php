<html><head>
<title>Fast And Slow Moving Medicine Report</title>
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
  padding-left:5px !important;;
  font:14px Arial;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:14px Arial !important;">
     <tr><td colspan="5" align="center"><h3> Branch Allotment Item Summary Report </h3></td></tr>
         <tr>
                    <th width="40" align="center">S.No.</th> 
                    <th>Branch</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Item Serial Number</th>
                    <th>Quantity</th>
                    
                    <th>Vendor Name </th> 
                    <th>Date</th>
                    
        </tr>
 <?php
   if(!empty($report_list))
   {
     //echo "<pre>";print_r($report_list); die;
     $i=1;
     $tot_data=count($report_list);
     foreach($report_list as $reports)
     {
            $discounts = ($reports->discount/100)*($reports->per_pic_price*$reports->qty);
       ?>
      <tr>
        <td align="center"><?php echo $i; ?></td>
        <td><?php echo $reports->branch_name; ?></td>
        <td><?php echo $reports->item_code; ?></td>
        <td><?php echo $reports->item_name; ?></td>
        <td><?php echo $reports->serial_numbers; ?></td>
        <td><?php echo $reports->qty; ?></td>  
        <td><?php echo $reports->vendor_name; ?></td>  
          
        <td><?php echo date('d-m-Y',strtotime($reports->purchase_date)); ?></td>  
         
     </tr>
       <?php
       $i++;  
     }
     ?>  

   <?php       
   }
 ?> 
</table></body></html>