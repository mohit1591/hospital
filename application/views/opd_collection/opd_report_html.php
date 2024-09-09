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
<table width="100%" cellpadding="0" cellspacing="0" border="1px" style="font:13px Arial;">
    
     <tr><td colspan="12" align="center"><h3>OPD Collection Report <?php 
$search_data = $this->session->userdata('opd_collection_resport_search_data');
if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
     <?php 
     $checkbox_list = get_checkbox_coloumns('12');
     foreach ($checkbox_list as $checkbox_list_data ) 
      {
          if($checkbox_list_data->selected_status!='' && $checkbox_list_data->coloum_name!='Actions')
          {
            ?>
            <th><?php echo $checkbox_list_data->coloum_name; ?></th>
            <?php 
          }
      }
     
     ?>
    
    
 </tr>
 <?php
   if(!empty($data_list))
   {

      
        $ttl_amt=0;
        $ttl_disc=0;
        $ttl_paid=0;
        $ttl_bal=0;
        $ttl_comm=0;
        $ttl_coll=0;
      
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
        $ttl_amt  = $ttl_amt  + $data->total_amount;
        $ttl_disc = $ttl_disc + $data->discount;
        $ttl_paid = $ttl_paid + ($data->total_amount-$data->discount);
        
        $ttl_comm=$ttl_comm+$data->total_comission;
        $ttl_coll = $ttl_coll+(($data->total_amount-$data->discount)-$data->total_comission);
                    
        $ttl_bal  = $ttl_bal  + $data->balance; 
            if(!empty($data->doctor_hospital_name))
            {
                $doctor_hospital_name=$data->doctor_hospital_name;
            }
            else
            {
                $doctor_hospital_name = $data->ref_by_other;
            }
   	   ?>
   	    <tr>
   	        
   	        <td><?php echo $data->token_no; ?></td>
   	        <td><?php echo $data->booking_code; ?></td>
  		 	<td><?php echo date('d-m-Y',strtotime($data->booking_date));  ?></td>
  		 	<td><?php echo $data->patient_name; ?></td>
  		 	<td><?php echo 'Dr. '.$data->consultant; ?></td>
  		 	<td><?php echo $doctor_hospital_name; ?></td>
  		 	
  		 	<td align="right"><?php echo $data->total_amount; ?></td>
  		 	<td align="right"><?php echo $data->discount; ?></td>
  		 	<td align="right"><?php echo $data->total_amount-$data->discount; ?></td>
  		 	<td align="right"><?php echo $data->total_comission; ?></td>
  		 	<td align="right"><?php echo $data->paid_amount-$data->total_comission; ?></td>
  		 	<td align="right"><?php echo $data->balance; ?></td> 
		 </tr>
   	   <?php
   	   $i++;	
   	 }
     ?> 
      <tr>
          <td colspan="6" align="center" ><b>Total</b></td>
          <td align="right" ><b><?php echo number_format($ttl_amt,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_disc,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_paid,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($ttl_comm,2); ?></b></td>
          
          <td align="right" ><b><?php echo number_format($ttl_coll,2); ?></b></td>
          
          <td align="right" ><b><?php echo number_format($ttl_bal,2); ?></b></td>
      </tr>


   <?php  	
   }
 ?> 
</table>
</body></html>