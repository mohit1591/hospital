<html><head>
<title>IPD Collection Report</title>
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
    
    <tr><td colspan="6" align="center"><h3>Doctor visiting Report <?php 
$search_data = $this->session->userdata('ipd_visiting_search_data');
if(!empty($search_data['from_date'])){ echo $search_data['from_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
                    <th> IPD No. </th> 
                    <th> Admission Date </th>  
                    <th> Patient Name </th>
                    <th> Doctor Name </th>
                    <th> Amount </th>
                    <th> Date </th>  
 </tr>
 <?php

    $ttl_paid=0;
   if(!empty($data_list))
   {
   	  $i=1;
   	 foreach($data_list as $data)
   	 {
        $ttl_paid = $ttl_paid + $data->net_price;
   	   ?>
   	    <tr>
      		 	<td><?php echo $data->ipd_no; ?></td>
      		 	<td><?php echo date('d-m-Y',strtotime($data->admission_date));  ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_name; ?></td>
      		 	<td align="right"><?php echo $data->net_price; ?></td>
      		 	<td align="right"><?php echo date('d-m-Y',strtotime($data->start_date)); ?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }
    ?>
      <tr>
          <td colspan="4" align="right"><b>Total</b></td>
          <td align="right"><b><?php echo number_format($ttl_paid,2); ?></b></td>
          <td>&nbsp;</td>
      </tr>

    <?php     	
   }
 ?> 
</table>
</body></html>