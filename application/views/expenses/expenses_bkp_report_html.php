<?php print_r();die; ?>
<html>
<head>
<title>Medicine Report</title>
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
</head>

<body>
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
   	        <td><?php echo $i; ?>.</td>
            <td><?php echo $data->vouchar_no; ?></td>
      		 	<td><?php 
              $expenses_type = $reports->exp_category;
              if($reports->type>0)
              {
              $expenses_type = $reports->expenses_type;
              }
            echo $expenses_type; ?></td>
            <td><?php echo $data->exp_category; ?></td>
      		 	<td><?php echo $data->paid_amount; ?></td>
            <td><?php echo $data->payment_mode; ?></td>
      		 
      </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>