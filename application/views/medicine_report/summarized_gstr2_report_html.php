<html><head>
<title>Summarized GSTR2 Report</title>
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
  font:13px Arial;
}

</style>
</head><body>
<table cellpadding="0" cellspacing="0" border="1px" width="100%" style="font:13px Arial !important;">
    <tr><td colspan="7" align="center"><h3> Summarized GSTR2 Report </h3></td></tr>
 <tr>
    
    <th>S.No.</th>
    <th>Date</th>
    <th>Total Amount</th>
    <th>SGST Amount</th>
    <th>CGST Amount</th>
    <th>IGST Amount</th>
    <td>&nbsp;</td>
    <th>Total GST Total</th> 
 </tr>
 <?php
   if(!empty($report_list))
   {


//echo "<pre>";print_r($report_list); die;

      $i = 1;
        $total_num = count($report_list);
        $gst_total_amount=0;
        $total_cgst = 0;
        $total_igst = 0;
        $total_sgst = 0;
        $tot_discount=0;
        $tot_discount_amount=0;
        $taxable_amount_total = 0;
        $amount_total = 0;


        $n=1;
        $k=1;
        foreach ($report_list as $data_list) 
          {
             $total_amt = $data_list->egst+$data_list->tgst+$data_list->fgst+$data_list->zgst;
            $amount_total +=$total_amt;
            $total_egst +=$data_list->egst;
            $total_tgst +=$data_list->tgst;
            $total_fgst +=$data_list->fgst;
            $total_zgst +=$data_list->zgst;

            $total_egst_amt=$data_list->egst-($data_list->egst/1.18);
            $total_tgst_amt=$data_list->tgst-($data_list->tgst/1.12);
            $total_fgst_amt=$data_list->fgst-($data_list->fgst/1.05);
            $total_zgst_amt=$total_egst_amt+$total_tgst_amt+$total_fgst_amt;
            $gst_total_amount += $total_zgst_amt;

          
              ?>
              <tr>
                  <td><?php echo $n; ?></td>
                  <td><?php echo date('d-m-Y', strtotime($data_list->sale_date)); ?></td>
                  <td><?php echo $total_amt; ?></td>
                  <td><?php echo $data_list->egst; ?></td>
                  <td><?php echo $data_list->tgst; ?></td>
                  <td><?php echo $data_list->fgst; ?></td>
                  <td><?php echo $data_list->zgst; ?></td>
                  <td><?php echo number_format($total_zgst_amt,'2','.',''); ?></td>
              </tr>

              <?php
        
            
           if($n==$total_num)
           {
               ?>
                <tr>
                 <td>&nbsp;</td>
                 <td>Total</td>
                 <td><?php echo number_format($amount_total,2,'.','');?></td>
                 <td><?php echo number_format($total_egst,2,'.','');?></td>
                 <td><?php echo number_format($total_tgst,2,'.','');?></td>
                 <td><?php echo number_format($total_fgst,2,'.','');?></td>
                 <td><?php echo number_format($total_zgst,2,'.','');?></td>
                 <td><?php echo number_format($gst_total_amount,2,'.','');?></td>
               </tr>
               <?php
     
           }
            $n++; 
            
        }
       ?>
        
       <?php
       $i++;  
     }  
   
 ?> 
</table>
</body></html>