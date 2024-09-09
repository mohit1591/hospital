<html><head>
<title>IPD Summary Report</title>
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
  padding-left:3px;
}
</style>
</head><body>
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font-family:arial, sans-serif;font-size:13px;">
<tr><td colspan="9" align="center"><h3>IPD Discharge Summary Report <?php 
$search_data = $this->session->userdata('ipd_summary_report');
if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th> S. No.</th>
    
    <th> Receipt No.</th>
    <th> IPD No.</th>
    <th> Patient Name</th>
    <th> Reg. No.</th>
    <th> DOA </th>
    <th> DOD</th>
    <th> Amount</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {

      $ttl_paid=0;
      
     $i=1;
     foreach($data_list as $reports)
     {
      
        
        $IPD_refund_amount='0';
                    if($reports->ipd_refund_amount>0)
                    {
                      $IPD_refund_amount = $reports->ipd_refund_amount;
                    }
                    $paid_amountse = $reports->ipd_paid_amount-$IPD_refund_amount;
                    if(!empty($paid_amountse))
                    {
                      $paid_amounts = number_format($paid_amountse,2);
                    }
                    //$ttl_paid = $ttl_paid+ $paid_amountse;
                     $ttl_paid = $ttl_paid+$reports->net_amount_dis_bill;   
            
      ?>
      <tr>
            <td><?php echo $i; ?></td>
            
            <td><?php echo $reports->discharge_bill_no; ?></td>
            <td><?php echo $reports->ipd_no; ?></td>
            <td><?php echo $reports->patient_name; ?></td>
            <td><?php echo $reports->patient_code; ?></td>
            <td><?php echo date('d-m-Y',strtotime($reports->admission_date)).' '.date('h:i A',strtotime($reports->admission_time)); ?></td>
            <td><?php echo date('d-m-Y h:i A',strtotime($reports->discharge_date)); ?></td>
           
            <td align="right"><?php echo $reports->net_amount_dis_bill; ?></td>
         
      </tr>
       <?php
       $i++;  
     }
     ?>
      <tr>
         <td colspan="7" align="right"><b>Total</b></td> 
         
         <td align="right" ><b><?php echo number_format($ttl_paid,2); ?></b></td> 
      
      </tr>
   <?php  
   }
 ?> 
</table>
</body></html>