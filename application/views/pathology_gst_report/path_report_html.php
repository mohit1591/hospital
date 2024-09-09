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
    <tr><td colspan="7" align="center"><h3>Pathology GST Report <?php 
$search_data = $this->session->userdata('search_data'); 

if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th> S. No. </th> 
                    <th> Booking Date </th>
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?>  </th>
                    <th> Lab Ref. No. </th>
                    <th> Patient Name </th>
                    <th> Total Amount</th>
                    <th> GST </th>
 </tr>
 <?php
   if(!empty($data_list))
   {
    //added on 31-jan-2018
      $g_total_amount=0;          // total amount
      
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      //added on 31-jan-2018
        $g_total_amount=$g_total_amount+$data->gst_amount;// total amount
       

   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?></td>
   	        <td align=""><?php echo date('d M Y',strtotime($data->booking_date)); ?></td>
   	        <td><?php echo $data->patient_code; ?></td>
   	        <td><?php echo $data->lab_reg_no; ?></td>
      		<td><?php echo $data->patient_name; ?></td>
            <td><?php echo $data->total_amount; ?></td>
      		
      		<td align=""><?php echo $data->gst_amount; ?></td>
      		 	
		 </tr>
   	   <?php
   	   $i++;	
   	 }
   	 
     ?>
    
   <?php   
   }
 ?> 
</table>
</body></html>