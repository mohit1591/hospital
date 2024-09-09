<html>
<head>
<title>Pathology Collection Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    
    <th>S.No.</th>
    <th>Name of Party</th>
    <th>GSTIN</th>
    <th>Invoice Date</th>
    <th>Invoice No.</th>
    <th>Invoice Value</th>
    <th>Type</th>
    <th>HSN Code</th>
    <th>Amount</th>
    <th>Taxable Amount</th>
    <th>SGST %age</th>
    <th>SGST %age Amount</th>
    <th>CGST %age</th>
    <th>CGST %age Amount</th>
    <th>IGST %age</th>
    <th>IGST %age Amount</th>
    <th>Cess</th>
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
            $total_medicine_count = $this->medicine_report->get_medicine_count($data_list->p_id);
            $taxable_amount_total=$taxable_amount_total+$data_list->total_amount;
            $amount_total=$amount_total+$data_list->total_amount;
            
            $per_pic_amount= $data_list->per_pic_price;
            $total_with_rate= $per_pic_amount*$data_list->qty;
            $total_new_amount= $total_with_rate-($total_with_rate/100)*$data_list->discount;

            $grand_amount= $total_new_amount+$data_list->total_amount;
            $totigst_amount = $data_list->igst;
            $total_igst_amt= ($total_new_amount/100)*$totigst_amount;

            //$newamountwithigst=$newamountwithigst+ $total_amountwithigst;
            $totcgst_amount = $data_list->cgst;
            $total_cgst_amt= ($totcgst_amount/100)*$total_new_amount;
            
            //$newamountwithcgst=$newamountwithcgst+$total_amountwithcgst;
            $totsgst_amount = $data_list->sgst; 
            $total_sgst_amt= ($total_new_amount/100)*$totsgst_amount; 
            $tot_discount_amount+= ($total_with_rate)/100*$data_list->discount;
            $total_cgst = $total_cgst+$total_cgst_amt; 
            $total_sgst = $total_sgst+$total_sgst_amt; 
            $total_igst = $total_igst+$total_igst_amt; 

            $gst_total =($total_sgst_amt+$total_cgst_amt+$total_igst_amt);
            $gst_total_amount = $gst_total_amount+$gst_total;


            if($n==1)
            {

              ?>
              <tr>
                  <td><?php echo $k; ?></td>
                  <td><?php echo $data_list->patient_name; ?></td>
                  <td>&nbsp;</td>
                  <td><?php echo date('d-m-Y', strtotime($data_list->sale_date)); ?></td>
                  <td><?php echo $data_list->sale_no; ?></td>
                  <td><?php echo $data_list->net_amount; ?></td>
                  <td><?php echo 'Local' ?></td>
                  <td><?php echo $data_list->hsn_no; ?></td>
                  <td><?php echo $data_list->total_amount; ?></td>
                  <td><?php echo $data_list->total_amount; ?></td>
                  <td><?php echo $data_list->sgst; ?></td> 
                  <td><?php echo number_format($total_sgst_amt,2,'.',''); ?></td>
                  <td><?php echo $data_list->cgst; ?></td>
                  <td><?php echo number_format($total_cgst_amt,2,'.',''); ?></td>
                  <td><?php echo $data_list->igst; ?></td>
                  <td><?php echo number_format($total_igst_amt,2,'.',''); ?></td>
                  <td>0.00</td>
                  <td><?php echo number_format($gst_total,2,'.',''); ?></td>
              </tr>
              <?php 
              
              $k++;

            }
            else
            {
              ?>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
               
                <td><?php echo $data_list->hsn_no; ?></td>
                <td><?php echo $data_list->total_amount; ?></td>
                <td><?php echo $data_list->total_amount; ?></td>
                <td><?php echo $data_list->sgst; ?></td>
                <td><?php echo $total_sgst_amt; ?></td>
                <td><?php echo $data_list->cgst; ?></td>
                <td><?php echo $total_cgst_amt; ?></td>
                <td><?php echo $data_list->igst; ?></td>
                <td><?php echo $total_igst_amt; ?></td>
                <td>0.00</td>
                
                <td><?php echo $gst_total; ?></td>
              </tr>
              <?php
              
        
              if($total_medicine_count<=$n)
              {
                $n=0; 
              }
                    

            }
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
                 <td>&nbsp;</td>
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
</body>
</html>