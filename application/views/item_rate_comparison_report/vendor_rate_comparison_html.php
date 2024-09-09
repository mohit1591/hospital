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
     <tr><td colspan="6" align="center"><h3> Inventory Vendor Rate Comparison Report </h3></td></tr>
         <tr>
            <th width="40" align="center">S.No.</th> 
            <th> Vendor Name </th> 
            <th> Item Name </th> 
            <th> Purchase Rate </th>
            <th> MRP </th>
            <th> Purchase Date </th> 
             
        </tr>
 <?php
   if(!empty($report_list))
   {
 //echo "<pre>";print_r($report_list); die;
     $i=1;
     $tot_data=count($report_list);
     foreach($report_list as $data)
     {
         if($data->purchase_rate!='0.00')
            {
                $purchase_rate = $data->purchase_rate;
            }
            else
            {
                $purchase_rate = $data->per_pic_price;
            }

       ?>
      <tr>
        <td align="center"><?php echo $i; ?></td>
        <td><?php echo $data->vendor_name ?></td>
        <td><?php echo $data->item_name; ?></td>
        <td><?php echo $purchase_rate; ?></td>
        <td><?php echo $data->mrp; ?></td>
        <td><?php echo date('d-m-Y', strtotime($data->purchase_date)); ?></td>
          
     </tr>
       <?php
       if($tot_data==$i){?>
        
     <?php }
       $i++;  
     }
     ?>  

   <?php       
   }
 ?> 
</table></body></html>