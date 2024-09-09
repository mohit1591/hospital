<html><head>
<title>IPD Booking</title>
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
     
    <th>S.No.</th>
    
    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th> IPD No. </th> 
    <th> Patient Name </th> 
    <th> Admission Date </th> 
    <th> Amount </th> 
    <th> Deposit Date </th> 
    
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $reports)
   	 {
        if($reports->admission_date =='1970-01-01'){
        $adm_date='';
        }else{
            $adm_date=date('d-m-Y',strtotime($reports->admission_date));
        }
        
        
        if($reports->payment_date =='1970-01-01'){
        $pay_date = '';
        }else{
            $pay_date=date('d-m-Y',strtotime($reports->payment_date));
        }
            
          
   	   ?>
   	    <tr>
   	        <td><?php echo $i; ?>.</td>
      		 	<td><?php echo $reports->patient_code; ?></td>
      		 	<td><?php echo $reports->ipd_no; ?></td>
      		 	
      		 	<td><?php echo $reports->patient_name; ?></td>
      		 	<td><?php echo $adm_date; ?></td>
      		 	<td><?php echo $reports->net_price; ?></td>
      		 	<td><?php echo $pay_date; ?></td>
      		 	
      	
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>