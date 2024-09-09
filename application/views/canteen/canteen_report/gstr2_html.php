<html><head>
<title>GSTR2 Canteen Sale Report</title>
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
<table cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial !important;">
 <tr>
    <th>S.No.</th>
    <th>Name of Party</th>
    <th>Invoice Date</th>
    <th>Invoice No.</th>
    <th>Invoice Value</th>
    <th>Type</th>
    <th>HSN Code</th>
    <th>Amount</th>
    <th>Taxable Amount</th>
    <th>SGST %age</th>
    <th>SGST Amount</th>
    <th>CGST %age</th>
    <th>CGST Amount</th>
    <th>IGST %age</th>
    <th>IGST Amount</th>
    <th>Total GST</th> 
 </tr>
 <?php
   if(!empty($report_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	


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
        
			$total_purchase_count = $this->canteen_report->get_purchase_count($data_list->s_id);
            
            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;

            $tot_qty_with_rate=$data_list->mrp*$data_list->qty;
            $total_discount = ($data_list->discount/100)*$tot_qty_with_rate;	
			$total_row_amount=$tot_qty_with_rate-$data_list->discount;

            $total_cgst_amt = ($total_row_amount/100)*$data_list->cgst;
            $total_sgst_amt= ($total_row_amount/100)*$data_list->sgst;
            $total_igst_amt= ($total_row_amount/100)*$data_list->igst; 

            $total_cgst += ($total_row_amount/100)*$data_list->cgst; 
            $total_sgst += ($total_row_amount/100)*$data_list->sgst;
            $total_igst += ($total_row_amount/100)*$data_list->igst; 
			
            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            $gst_total_amount = $gst_total_amount+$gst_total;

              ?>
              <tr>
                  <td><?php echo $k; ?></td>
                  <td><?php if(!empty($data_list->customer_name)){ echo $data_list->customer_name; } else { echo $data_list->patient_name.' (Patient)';} ?></td>
                  <td><?php echo date('d-m-Y', strtotime($data_list->sale_date)); ?></td>
                  <td><?php echo $data_list->sale_no; ?></td>
                  <td><?php echo $data_list->net_amount; ?></td>
                  <td><?php echo 'Local' ?></td>
                  <td><?php echo $data_list->hsn_no; ?></td>
                  <td><?php echo $data_list->total_amount; ?></td>
                  <td><?php echo $data_list->total_amount; ?></td>
                  <td><?php echo $data_list->sgst; ?></td> 
                  <td><?php echo $total_sgst_amt; ?></td>
                  <td><?php echo $data_list->cgst; ?></td>
                  <td><?php echo $total_cgst_amt; ?></td>
                  <td><?php echo $data_list->igst; ?></td>
                  <td><?php echo $total_igst_amt; ?></td>
                  <td><?php echo $gst_total; ?></td>
              </tr>
              <?php 
              
              $k++;

            $n++; 
           if($i==$total_num)
           {
               ?>
               <tr>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>&nbsp;</td>
                 <td>Total</td>
                 <td><?php echo number_format($amount_total,2,'.','');?></td>
                 <td><?php echo number_format($taxable_amount_total,2,'.','');?></td>
                 <td>&nbsp;</td>
                 <td><?php echo number_format($total_sgst,2,'.','');?></td>
                 <td>&nbsp;</td>
                 <td><?php echo number_format($total_cgst,2,'.','');?></td>
                 <td>&nbsp;</td>
                 <td><?php echo number_format($total_igst,2,'.','');?></td>
                 <td><?php echo number_format($gst_total_amount,2,'.','');?></td>

               </tr>
               <?php
     
           }
            $i++; 
        }
   	   ?>
   	   <?php
   	   $i++;	
   	 }	
 ?> 
</table>
</body></html>