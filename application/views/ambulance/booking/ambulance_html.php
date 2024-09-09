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
 <tr>
        <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th> 
    <th>Booking No.</th>
    <th>Patient Name</th>
    
    <th>Vehicle No</th>
    <th>Driver Name</th>
    <th>Booking Date</th>
    <th>Booking Time</th>
    <th>Net Amount</th>
    <th>Total Paid Amount</th>
    <th>Balance</th>
    <th>Refund Amount</th>
    


 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $ambulance)
   	 {
          
           
            $booking_code = $ambulance->booking_no;
            
            $patient_name = $ambulance->patient_name;
            
            $booking_date = date('d-m-Y',strtotime($ambulance->created_date));
             if($ambulance->appointment_date!='0000-00-00')
            {
              $appointment_date = date('d-m-Y',strtotime($ambulance->booking_date)); 
            }
            else
            {
                $appointment_date = ""; 
            }
                $genders = array('0'=>'Female','1'=>'Male');
                
                /*if($ambulance->balance1>0)
                    {*/
                        //$row[] = $ambulance->balance1;
                    /*}else
                    {
                          
                          $bal = $ambulance->balance1+$ambulance->refund_amount;
                          if($bal>0)
                          {
                              $balk= $bal;
                          }
                          else
                          {
                              $balk='0.00';
                          }
                          
                    }*/
                    $balk = $ambulance->balance1+$ambulance->refund_amount;
               
   	   ?>
   	    <tr>
   	       <td align="center"><?php echo $ambulance->patient_code; ?>.</td>
      		 	<td><?php echo $ambulance->booking_no; ?></td>
            <td><?php echo $ambulance->patient_name; ?></td>
      		 
      		 	<td><?php echo $ambulance->vehicle_no; ?></td>
            <td><?php echo $ambulance->driver_name; ?></td>
            <td><?php echo date('d-m-Y',strtotime($ambulance->booking_date)); ?></td>
            <td><?php echo date('h:i A',strtotime($ambulance->booking_time)); ?></td>
            <td><?php echo $ambulance->net_amount; ?></td>
            <td><?php echo $ambulance->paid_amount1; ?></td>
            <td><?php echo $balk; ?></td>
            <td><?php echo $ambulance->refund_amount; ?></td>
            
            
      		 	<!-- <td>< ?php echo $create_date; ?></td> -->
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>