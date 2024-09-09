<html><head>
<title>OPD Collection Report</title>
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
<tr><td colspan="9" align="center"><h3>OPD Summary Report <?php 
$search_data = $this->session->userdata('opd_summary_search');
if(!empty($search_data['attended_doctor'])){
  echo $doctor_name = get_doctor_name($search_data['attended_doctor']); 
}
if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th> S. No.</th>
    
    <th> Receipt No.</th>
    <th> Token No.</th>
    <th> Patient Name</th>
    <th> Reg. No.</th>
    
    <th> Amount</th>
    <th> Payment Mode</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {
      
      $ttl_paid=0;
      
     $i=1;
     foreach($data_list as $data)
     {
      
        $ttl_paid= $ttl_paid + $data->debit;
        
      ?>
      <tr>
            <td><?php echo $i; ?></td>
            
            <td><?php echo $data->booking_code; ?></td>
            <td><?php echo $data->token_no; ?></td>
            <td><?php echo $data->patient_name; ?></td>
            <td><?php echo $data->patient_code; ?></td>
            <td><?php echo $data->debit; ?></td>
           
            <td><?php echo $data->mode; ?></td>
         
      </tr>
       <?php
       $i++;  
     }
     if(!empty($self_opd_coll_payment_mode)){
     foreach($self_opd_coll_payment_mode as $payment_mode_opd)
     {
        ?>
            
             <tr>
         <td colspan="5" align="right"><b><?php echo $payment_mode_opd->mode; ?></b></td> 
         
         <td align="left" colspan="2"><b><?php echo number_format($payment_mode_opd->tot_debit,2); ?></b></td> 
      
      </tr>
        <?php 
     }
     
     }
     ?>
      <tr>
         <td colspan="5" align="right"><b>Total</b></td> 
         
         <td align="left"colspan="2" ><b><?php echo number_format($ttl_paid,2); ?></b></td> 
      
      </tr>
   <?php  
   }
 ?> 
</table>
</body></html>