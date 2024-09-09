<html><head>
<title>Patient Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
    
    <tr><td colspan="9" align="center"><h3>IPD Payment Summary  <?php 
$search_data = $this->session->userdata('ipd_summary_report');
if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th> S.No. </th> 
    
    <th> Receipt No. </th> 
    <th> IPD No. </th> 
    <th> Patient Name </th>
    <th> Reg. No. </th>
    
    <th> Amount </th> 
    <th> Payment Mode </th> 
   
 </tr>
 <?php
   if(!empty($data_list))
   {
     
     $i=1;
     $tot=0;
     foreach($data_list as $datas)
     {
      $tot = $tot+$datas->net_price;
       ?>
        <tr>
           <td><?php echo $i; ?></td>
            
            <td><?php echo $datas->reciept_prefix.$datas->reciept_suffix; ?></td>
            <td><?php echo $datas->ipd_no; ?></td>
            <td><?php echo $datas->patient_name; ?></td>
            
            <td><?php echo $datas->patient_code; ?></td>
            
            <td><?php echo $datas->net_price; ?></td>
            <td><?php echo $datas->payment_mode; ?></td>
           
        
     </tr>
       <?php
       $i++;  
     } 
     
      if(!empty($self_advance_payment_mode))
	 {
			foreach($self_advance_payment_mode as $billing_payment_mode)
			{
					?>
					<tr>
						 <td colspan="5" align="right"><b><?php echo $billing_payment_mode->payment_mode; ?></b></td> 
						 
						 <td align="left" ><b><?php echo number_format($billing_payment_mode->net_price,2); ?></b></td> 
					  
					  </tr>
					<?php 
			}
	 }

     ?>
     <tr>
       <td colspan="5" align="right">Total</td>
       <td align="left"><?php echo $tot; ?></td>
       <td></td>
     </tr>
     <?php 
   }
 ?> 

</body></table></html>