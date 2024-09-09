<!DOCTYPE html>
<html>
<head>
<title class="print"><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css"> 
 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css"> 
</script>

</head>

<body>
<h2 align="center">Doctor Commission List</h2>
<table width="100%" class="m-b-5">
<?php
$doctor = get_doctor_name($_GET['doctor_id']);
?>
  <tr>
  	 <td width="25%" align="left" class="text-left"><b>Doctor Name :</b> <?php echo $doctor; ?></td>
  	 <?php $users_data = $this->session->userdata('auth_users'); 
      if($users_data['users_role']!=3)
      { ?>
  	 <td width="25%" align="left" class="text-left"><b>Start Date :</b> <?php echo $_GET['start_date']; ?></td>
  	 <td width="25%" align="left" class="text-left"><b>End Date :</b> <?php echo $_GET['end_date']; ?></td>
  	 <?php } ?>

  	  <td width="25%" align="left" class="text-left print"><input type="button" name="button_print" value="Print"  id="print" onClick="return my_function();"/></td>
  </tr>
</table>

<table id="table" class="table table-striped table-bordered branch_doctor_list" width="100%" cellspacing="0">   
    <thead class="bg-theme">
		 <tr>  
			<th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
			<th> Patient Name </th> 
			<th> Booking / Sale Date </th> 
			<th> Department </th> 
			<th> Total Commission </th>   
		</tr>
	</thead>
	<tbody>
	<?php
	 if(!empty($list))
	 { 
	  //echo '<pre>';print_r($list);die;
	  $total_amount = '0';
	  foreach($list as $data)
	  {
	  ?>
	     <tr>
		   <td><?php echo $data->patient_code; ?></td>
		   <td><?php echo $data->patient_name; ?></td>
		   <td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
		   <td><?php echo $data->commission_type; ?></td>
		   <td><?php echo number_format($data->total_credit,2); ?></td>
		</tr>
	  <?php
	  $total_amount = $total_amount+ $data->total_credit;
	   }

	   ?>
	   <tr><td colspan="5"></td></tr>	
	   <tr>
	   <td colspan="3"></td>
	   	<td style="text-align: left;"><strong>Total Amount</strong></td>
	   	<td><strong><?php echo $total_amount; ?></strong></td>
	   </tr>

	   <?php  
	 }
	 else
	 {
	   ?>
	     <tr><td colspan="4">Record not found</td></tr>
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