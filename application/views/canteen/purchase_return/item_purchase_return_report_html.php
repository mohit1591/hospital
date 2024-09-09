<html><head>
<title>Item Purchase Return Report</title>
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
    <th>Purchase No.</th>
    <th>Return No.</th>
    <th>Invoice No.</th>
    <th>Vendor Name</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th>
    <th>Discount;</th>
    <th>Created Date</th>
    
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
            <td><?php echo $data->purchase_id; ?></td>
            <td><?php echo $data->return_no; ?></td>
            <td><?php echo $data->invoice_id; ?></td>
            <td><?php echo $data->vendor_name; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->net_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->paid_amount; ?></td>
            <td style="text-align:right;padding-right:5px;"><?php echo $data->balance; ?></td> 
             <td style="text-align:right;padding-right:5px;"><?php echo $data->discount; ?></td> 
            <td><?php echo date('d-M-Y H:i A',strtotime($data->created_date)); ?></td>
        
     </tr>
       <?php
       $i++;  
     }  
   }
 ?> 
      <!-- added on 02-FEB-2018 -->
          <tr>
           <?php
            //unauthorise_permission(59,392);
            $list = $this->purchase_return->get_datatables(); 
            // echo "<pre>"; print_r($list); exit;
            $assoc_array = json_decode(json_encode($list),TRUE);
            $session_data= $this->session->userdata('auth_users');
            $total_net_amount = array_sum(array_column($assoc_array,'net_amount'));
            $total_discount = array_sum(array_column($assoc_array,'discount'));
            $total_balance= array_sum(array_column($assoc_array,'balance'));
            $total_vat= array_sum(array_column($assoc_array,'vat'));
            $total_paid_amount= array_sum(array_column($assoc_array,'paid_amount'));
            ?>

            <td align="center" colspan="5"><b>Total</b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($total_net_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($total_paid_amount,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($total_balance,2); ?></b></td>
            <td style="text-align:right;padding-right:5px;"><b><?php echo number_format($total_discount,2); ?></b></td>
            <td></td>
          </tr>
      <!-- added on 02-FEB-2018 -->   
</table>
</body></html>