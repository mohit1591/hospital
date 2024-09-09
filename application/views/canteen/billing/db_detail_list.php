<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css"> 
 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css"> 
</script>

</head>

<body>
<h2 align="center">Payment Details</h2>
<table id="table" class="table table-striped table-bordered branch_detail_list" width="" cellspacing="0">   
    <thead class="bg-theme">
		 <tr>  
			<th width="20%"> Rec. Date </th> 
			<th> Amount </th>  
			<th> Payment mode </th>  
		</tr>
	</thead>
	<tbody>
	<?php
	 if(!empty($list))
	 {
	  //$pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');		
	  foreach($list as $data)
	  {
	  ?>
	     <tr>
		   <td style="text-align:left;"><?php echo date('d-m-Y H:i A', strtotime($data->created_date)); ?></td>
		   <td style="text-align:left;"><?php echo $data->debit; ?></td> 
		   <td style="text-align:left;"><?php echo $data->p_mode; ?></td> 
		</tr>
	  <?php 
	   } 
	 }
	 else
	 {
	   ?>
	     <tr><td colspan="3">Record not found</td></tr>
	   <?php
	 }
	?> 
	</tbody>
</table>
</body>

</html>