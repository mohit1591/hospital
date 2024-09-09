<html>
<head>
<title>Medicine Kit History Report</title>



</head>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body
{
	font-size: 10px;
}	
td
{
	padding-left:3px;
}
</style>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="1" style="font:13px Arial;border-collapse: collapse;">
 <tr>

    
    <th>Medicine Kit</th>
    <th>Amount</th>
    <th>Quantity</th>
    <th>Date</th>

    
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
   	     
          <td><?php echo $data->package_name; ?></td>
          <td><?php echo $data->amount; ?></td>
          <td><?php echo $data->debit; ?></td>
          <td><?php echo date('d-M-Y',strtotime($data->created_date)); ?></td>
      		 	
          </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
</table>
</body>
</html>