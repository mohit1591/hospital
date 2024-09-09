<html><head>
<title>Department Collection Report</title>
<?php
$users_data = $this->session->userdata('auth_users'); 
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
    <tr><td colspan="<?php echo count($dept_list)+8;?>" align="center"><h3>Pathology Collection Report <?php 
$search_data = $this->session->userdata('search_data'); 
if(!empty($search_data['start_date'])){ echo $search_data['start_date']; } ?><?php if(!empty($search_data['end_date'])){ echo ' To  '.$search_data['end_date']; } ?></h3></td></tr>
 <tr>
   
    <th>Booking Date</th>
    <th>Patient Name</th>
    <th>Referred By</th>
    <th>Total Amount</th>
    <?php 
    if(!empty($dept_list))
    {
        foreach($dept_list as $dept)
        {
            ?>
            
            <th><?php echo $dept->department; ?></th>
            <?php 
        }
    }    
    ?>
    
    <th>Discount</th>
    <th>Net Amount</th>
    <th>Paid Amount</th>
    <th>Balance</th> 
 </tr>
 <?php
   if(!empty($data_list))
   {
       
        $tot_amout=array();
         foreach ($data_list as $reports_one) 
         {
            if(!empty($dept_list))
            {
                foreach($dept_list as $dept)
                {
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$reports_one->id);
                      if(!empty($department_charge[0]['total_amount']))
                      {
                         $tot_amout[$dept->id] += $department_charge[0]['total_amount'];
                         
                      }
                     
                     
                    
                }
            }
         }
         
    //added on 31-jan-2018
      $total_amount=0;          // total amount
      $total_discount=0;        // total discount
      $total_net_amount=0;      // total_net_amount 
      $total_paid_amount=0;     // total_paid_amount 
      $total_balance=0;         // total_balance        
    //added on 31-jan-2018
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
      //added on 31-jan-2018
        $total_amount=$total_amount + $data->total_amount;           // total amount
        $total_discount=$total_discount + $data->discount;           // total discount
        $total_net_amount=$total_net_amount + $data->net_amount;     // total_net_amount 
        $total_paid_amount=$total_paid_amount + $data->paid_amount;  // total_paid_amount
        $total_balance=$total_balance + $data->balance;              // total_balance
      //added on 31-jan-2018  

   	   ?>
   	    <tr>
   	        <td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
      		 	<td><?php echo $data->patient_name; ?></td>
      		 	<td><?php echo $data->doctor_hospital_name; ?></td>
      		 	<td align="right"><?php echo $data->total_amount; ?></td>
      		 	<?php
      		 	if(!empty($dept_list))
      		 	{
      		 	foreach($dept_list as $dept)
                {
                    $department_charge = get_department_wise_charge($users_data['parent_id'],$dept->id,$data->id);
                     if(!empty($department_charge[0]['total_amount']))
                      {
                          
                        ?> <td><?php echo $department_charge[0]['total_amount']; ?></td>
      		 	
                         <?php 
                      }
                      else
                      {
                           ?> <td><?php echo '0.00'; ?></td>
      		 	
                         <?php
                      }
                     
                     
                    
                }
      		 	}
      		 	?>
      		 	
      		 	<td align="right"><?php echo $data->discount; ?></td>
      		 	<td align="right"><?php echo $data->net_amount; ?></td>
      		 	<td align="right"><?php echo $data->paid_amount; ?></td>
      		 	<td align="right"><?php echo $data->balance; ?></td> 
		 </tr>
   	   <?php
   	   $i++;	
   	 }
     ?>
     <!-- added on 31-jan-2018 -->
     <tr>
          <td align="center" colspan="3" ><b>Total</b></td>
          <td align="right" ><b><?php echo number_format($total_amount,2); ?></b></td>
          <?php 
           if(!empty($dept_list))
                {
                    foreach($dept_list as $dept)
                    {
          ?>
          <td align="right" ><b><?php echo number_format($tot_amout[$dept->id],2); ?></b></td>
          
          <?php   
                    }
                } ?>
          
          
          <td align="right" ><b><?php echo number_format($total_discount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_net_amount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_paid_amount,2); ?></b></td>
          <td align="right" ><b><?php echo number_format($total_balance,2); ?></b></td>
     </tr>
     <!-- added on 31-jan-2018 -->
   <?php   
   }
 ?> 
</table>
</body></html>