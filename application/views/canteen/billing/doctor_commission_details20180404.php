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
<h2 align="center">Doctor Commission List</h2>
<table width="100%" class="m-b-5">
<?php
$doctor = get_doctor_name($_GET['doctor_id']);
?>
  <tr>
  	 <td width="33%" align="left" class="text-left"><b>Doctor Name :</b> <?php echo $doctor; ?></td>
  	 <?php $users_data = $this->session->userdata('auth_users'); 
      if($users_data['users_role']!=3)
      { ?>
  	 <td width="33%" align="left" class="text-left"><b>Start Date :</b> <?php echo $_GET['start_date']; ?></td>
  	 <td width="33%" align="left" class="text-left"><b>End Date :</b> <?php echo $_GET['end_date']; ?></td>
  	 <?php } ?>
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
	   } 
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