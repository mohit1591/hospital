<html><head>
<title>Pathology Summary Report</title>
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
    <tr><td colspan="6" align="center"><h3>Pathology Summary Report <?php 
$search_data = $this->session->userdata('search_data'); 

if(!empty($search_data["department"]))
{
  $depa_name = get_department_name($search_data["department"]);
  echo $depa_name.' ';
}
if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th>S.No.</th>
    <th>Receipt No.</th>
    <th>Patient Name</th>
    <th>Reg. No.</th>
    <th>Total Amount</th>
    <th>Payment Mode</th>
   <!-- <th>Doctor Name</th> -->
 </tr>
 <?php
 $all_payment_mode=array();
 $all_payment_mode_name=array();
foreach($payment_mode as $payment_mode) 
{
$all_payment_mode[]=$payment_mode->id;
$all_payment_mode_name[]=$payment_mode->payment_mode;
}
$total_mode_val = array();
foreach($all_payment_mode_name as $payu)
{
    
    foreach($data_list as $collection)
    {
        if($collection->mode==$payu)
        {
          $total_mode_val[$collection->mode] += $collection->net_amount; 
        }
    }
}

   if(!empty($data_list))
   {
    //added on 31-jan-2018
      $g_total_amount=0;          // total amount
      
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      //added on 31-jan-2018
        $g_total_amount=$g_total_amount+$data->net_amount;// total amount
       

   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?></td>
      		 	
      		 <!--	<td>< ?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>-->
            <td><?php echo $data->booking_code; ?></td>
            <td><?php echo $data->patient_name; ?></td>
            <td><?php echo $data->patient_code; ?></td>
      		 	
      		 	<td align=""><?php echo $data->net_amount; ?></td>
      		 	
      		 	<td align=""><?php echo $data->mode; ?></td>
      		 	
		 </tr>
   	   <?php
   	   $i++;	
   	 }
   	 
   	 //echo "<pre>"; print_r($total_mode_val); exit;
   	 if(!empty($total_mode_val)){
   	 foreach($total_mode_val as $key=>$payment_mode_path)
     {
         ?>
         <tr>
          <td align="" colspan="4" ><b><?php echo $key ?></b></td>
          <td align="" colspan="2"><b><?php echo number_format($payment_mode_path,2); ?></b></td>
          
     </tr>
         <?php 
     }
   	 }
     ?>
     <!-- added on 31-jan-2018 -->
     <tr>
          <td align="" colspan="4" ><b>Total</b></td>
          <td align="" colspan="2"><b><?php echo number_format($g_total_amount,2); ?></b></td>
          
          
     </tr>
     <!-- added on 31-jan-2018 -->
   <?php   
   }
 ?> 
</table>
</body></html>