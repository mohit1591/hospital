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
    <!-- <th>S.No.</th> -->
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th>Enquiry No.</th>
    <th>Patient Name</th>
    
    <th>Booking Date</th>
    <th>Mobile No.</th>
    <th>Pick From</th>
    <th>Drop To</th>
    <th>Enquiry status</th>
    
   <!--  <th>Created Date</th> -->

 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $ambulance)
   	 {
          if($ambulance->enquiry_status==2)
            {
                $enquiry_status = '<font color="blue">Pending</font>';
            }   
            elseif($ambulance->enquiry_status==1){
                $enquiry_status = '<font color="green">Confirm</font>';
            }                 
            elseif($ambulance->enquiry_status==3){
                $enquiry_status = '<font color="red">Cancel</font>';
            } 
           

           
            $booking_code = $ambulance->booking_no;
            
            $patient_name = $ambulance->patient_name;
            
            $booking_date = date('d-m-Y',strtotime($ambulance->created_date));
                $genders = array('0'=>'Female','1'=>'Male');
               
   	   ?>
   	    <tr>
   	       <!--  <td align="center">< ?php echo $i; ?>.</td> -->
      		 	<td><?php echo $ambulance->patient_code; ?></td>
      		 	<td><?php echo $ambulance->enquiry_no; ?></td>
            <td><?php echo $ambulance->patient_name; ?></td>
      		 	
            <td><?php echo $booking_date; ?></td>
            <td><?php echo $ambulance->mobile_no; ?></td>
            <td><?php echo $ambulance->source; ?></td>
            <td><?php echo $ambulance->destination; ?></td>
            <td><?php echo $enquiry_status; ?></td>
            
            
      		 	<!-- <td>< ?php echo $create_date; ?></td> -->
      		 
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>