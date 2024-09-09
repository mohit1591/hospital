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
<h2 align="center">Commission Payment Details</h2>
 <span align="right"><input type="button" name="button_print" value="Print"  id="print" onClick="return my_function();"/></span>
<table id="table" class="table table-striped table-bordered branch_detail_list" width="" cellspacing="0">   
    <thead class="bg-theme">
		 <tr>  
			<th width="20%"> Rec. Date </th> 
			
			<th> Payment mode </th>  
			<th> Amount </th>  
		</tr>
	</thead>
	<tbody>
	<?php
	 if(!empty($list))
	 {
	 // $pay_mode = array('0'=>'','1'=>'Cash', '3'=>'Cheque', '2'=>'Card', '4'=>'NEFT');	
	 	$total_amount =0;
	  foreach($list as $data)
	  {
	  ?>
	     <tr>
		   <td style="text-align:left;"><?php echo date('d-m-Y', strtotime($data->created_date)); ?></td>
		   <td style="text-align:left;"><?php echo $data->payment_mode; ?></td> 
		   <td style="text-align:left;"><?php echo $data->debit; ?></td> 
		   
		</tr>
	  <?php 
	  $total_amount = $total_amount+$data->debit;
	   } ?>
	   <tr>
	   	<td colspan="2"><strong>Total Amount</strong></td>
	   	<td><strong><?php if(!empty($total_amount)) { echo  number_format($total_amount,2); } ?></strong></td>
	   </tr>
	   <?php 
	 }
	 else
	 {
	   ?>
	     <tr><td colspan="3"><span class="text-danger">Record not found</span></td></tr>
	   <?php
	 }
	?> 
	</tbody>
</table>
</body>

</html>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
 <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script>
function my_function()
{
 $("#print").hide();
  window.print();
}
</script>
<style type="text/css" media="print">
    @page 
    {
        size:  auto;   /* auto is the initial value */
        margin: 0mm;  /* this affects the margin in the printer settings */
    }

    html
    {
        background-color: #FFFFFF; 
        margin: 0px;  /* this affects the margin on the html before sending to printer */
    }

    body
    {
        border: solid 0px black ;
        margin: 10mm 15mm 10mm 15mm; /* margin you want for the content */
    }
    </style>