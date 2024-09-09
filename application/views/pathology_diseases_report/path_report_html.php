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
    <tr><td colspan="10" align="center"><h3>Pathology Summary Report <?php 
$search_data = $this->session->userdata('search_data'); 

if(!empty($search_data["department"]))
{
  $depa_name = get_department_name($search_data["department"]);
  echo $depa_name.' ';
}
if(!empty($search_data['start_date'])){ echo ' From '.$search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
    <th> S. No. </th> 
    <th> Patient Name </th>
    <th> Reg. No. </th>
    <th> Diseases </th>
    <th> Booking date </th>
    <th> Reminder Date </th>
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
        $g_total_amount=$g_total_amount+$data->total_amount;// total amount
       

   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?></td>
      		<td><?php echo $data->patient_name; ?></td>
            <td><?php echo $data->patient_code; ?></td>
      		 	
      		 	<td align=""><?php echo $data->disease; ?></td>
      		 	
      		 	<td align=""><?php echo date('d M Y',strtotime($data->booking_date)); ?></td>
      		 		<td align=""><?php echo date('d M Y',strtotime($data->reminder_date)); ?></td>
      		 	
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