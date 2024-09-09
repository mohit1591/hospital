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
     <tr><td colspan="5" align="center"><h3> Fast And Slow Moving Medicine Report </h3></td></tr>
       <tr>
            <th width="40" align="center">S.No.</th> 
            <th> Medicine code</th> 
            <th> Medicine Name </th> 
            <th> Purchase Quatity </th> 
            <th> Sale Quantity </th> 
       </tr>
 <?php
   if(!empty($report_list))
   {
 //echo "<pre>";print_r($report_list); die;
     $i=1;
     $tot_data=count($report_list);
     foreach($report_list as $data)
     {

       ?>
      <tr>
        <td align="center"><?php echo $i; ?></td>
        <td><?php echo $data->medicine_code ?></td>
        <td><?php echo $data->medicine_name; ?></td>
        <td><?php echo $data->purchase_qty; ?></td>
        <td><?php echo $data->sale_qty; ?></td>  
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