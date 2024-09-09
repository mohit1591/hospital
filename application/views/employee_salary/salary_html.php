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
    
    <th>Employee Name</th>
    <th>Expense Date</th>
    <th>Paid Amount</th>
    <th>Payment Mode</th>
    <th>Created Date</th>
    

 </tr>
 <?php
   if(!empty($data_list))
   {
   	 
   	 $i=1;
   	 foreach($data_list as $employee_salary)
   	 {
          ?>
   	    <tr>
   	     
      		 	<td><?php echo $employee_salary->emp_names; ?></td>
            <td><?php echo date('d-m-Y',strtotime($employee_salary->expenses_date)); ?></td>
      		 	<td><?php echo $employee_salary->employee_pay_now; ?></td>
      		 	<td><?php echo $employee_salary->payment_mode; ?></td>
            <td><?php echo date('d-M-Y H:i A',strtotime($employee_salary->created_date)); ?></td>
           
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body></html>