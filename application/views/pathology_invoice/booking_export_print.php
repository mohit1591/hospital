<html><head>
<title>Test Booking Report</title>
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
<table width="100%" cellpadding="0" cellspacing="0" border="1px">
 <tr>
    <th>S.No.</th>
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th> Proforma No. </th> 
    <th> Patient Name </th>
    <th> Mobile No. </th>
    <th> Gender </th>
    <th> Age </th>
    <th> Referred By </th>  
    <th> Booking Date </th> 
    <th> Remark </th>
    <th> Total Amount</th>
    <th> Discount </th>
    <th> Net Amount </th> 
    <th> Status </th>  
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $test)
   	 { 
          $create_date = date('d-M-Y H:i A',strtotime($test->created_date));
          $booking_date = date('d-M-Y',strtotime($test->booking_date)); 
          
          $complate_status = array('0'=>'Pending','1'=>'Confirmed');
          
                $age_y = $test->age_y;
                $age_m = $test->age_m;
                $age_d = $test->age_d;
                $age_h = $test->age_h;
                $age = "";
                if($age_y>0)
                {
                $year = 'Years';
                if($age_y==1)
                {
                  $year = 'Year';
                }
                $age .= $age_y." ".$year;
                }
                if($age_m>0)
                {
                $month = 'Months';
                if($age_m==1)
                {
                  $month = 'Month';
                }
                $age .= ", ".$age_m." ".$month;
                }
                if($age_d>0)
                {
                $day = 'Days';
                if($age_d==1)
                {
                  $day = 'Day';
                }
                $age .= ", ".$age_d." ".$day;
                }
                if($age_h>0)
                {
                $hours = 'Hours';
                
                $age .= " ".$age_h." ".$hours;
                } 
          
             
   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?></td>
   	        <td><?php echo $test->patient_code; ?></td>
      		<td><?php echo $test->lab_invoice_no; ?></td>
      		<td><?php echo $test->patient_name; ?></td>
      		<td><?php echo $test->mobile_no; ?></td>
      		<td><?php echo $test->patient_gender; ?></td>
      		<td><?php echo $age; ?></td>
      		<td><?php echo $test->doctor_hospital_name; ?></td>
      		<td><?php echo date('d M Y',strtotime($test->booking_date)); ?></td>
            <td><?php echo $test->remarks; ?></td> 
            <td><?php echo $test->total_amount; ?></td>
            <td><?php echo $test->discount; ?></td>
            <td><?php echo $test->net_amount; ?></td>
            <td><?php echo $complate_status[$test->complation_status]; ?></td>
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>