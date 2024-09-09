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
  <th>Paid Amount</th>
  <th>Payment Mode</th>
 </tr>
 <?php
   if(!empty($data_list))
   {
   	 //echo "<pre>";print_r($data_list); die;
   	 $i=1;
   	 foreach($data_list as $data)
   	 {
   	   ?>
   	    <tr>
   	        
            <td><?php echo $data->vouchar_no; ?></td>
             <td><?php echo date('d-m-Y',strtotime($data->expenses_date)); ?></td>
            <td><?php $expenses_type = $data->exp_category;
            if($data->type>0)
            {
            $expenses_type = $data->expenses_type;
            } echo $expenses_type;?></td>
            <td><?php echo $data->paid_amount;?></td>
            <td><?php echo $data->p_mode;?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>