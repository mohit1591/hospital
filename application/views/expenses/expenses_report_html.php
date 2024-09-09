<html><head>
<title>EXpenses Report</title>
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
  <th>Voucher No.</th>
  <th>Expense Date</th>
  <th>Expense Type</th>
  <th>Department</th>
  <th>Paid Amount</th>
  <th>Paid To</th>
  <th>Remarks</th>
  <th>Payment From</th>
  <th>Payment Mode</th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	     if($data->type==13)
		{
		   $paid_to_name = $data->doctor_name; 
		}
		else
		{
		   $paid_to_name = $data->paid_to_name; 
		}
   	   ?>
   	    <tr>
   	        
            <td><?php echo $data->vouchar_no; ?></td>
             <td><?php echo date('d-m-Y',strtotime($data->expenses_date)); ?></td>
            <td><?php $expenses_type = $data->exp_category;
            if($data->type>0)
            {
            $expenses_type = $data->expenses_type;
            } echo $expenses_type;?></td>
            
            <td><?php echo $dept_list[$data->department_type];?></td>
            <td><?php echo $data->paid_amount;?></td>
            
            <td><?php echo $paid_to_name;?></td>
            <td><?php echo $data->remarks;?></td>
            <td><?php echo $data->payment_from;?></td>
            
            <td><?php echo $data->p_mode;?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>