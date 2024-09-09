<?php
$users_data = $this->session->userdata('auth_users');
$company_data = $this->session->userdata('company_data');
?>
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
<h2 align="center">Commission List</h2>
<?php
if($users_data['users_role']==4)
{
  ?>
<table width="100%">
   <tr>
   	  <td><b>Name :</b> <?php echo $doctor_name; ?></td>
   	  <td><b>Start Date :</b> <?php echo $_GET['start_date']; ?></td>
   	  <td><b>End Date :</b> <?php echo $_GET['end_date']; ?></td>
   </tr>
</table>
<?php } ?>
<table id="table" class="table table-striped table-bordered branch_doctor_list" width="100%" cellspacing="0">   
    <thead class="bg-theme">
		 <tr>  
			<th width="20%"> Patient Name </th> 
			<th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
			<th> Total Amount </th>  
			<th> Rate </th> 
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
		   <td width="20%"><?php echo $data->patient_name; ?></td>
		   <td><?php echo $data->lab_reg_no; ?></td>
		   <td class="text-right"><?php echo $data->total_amount; ?></td>
		   <td class="text-right"><?php echo $data->rate; ?></td>
		   <td class="text-right"><?php if(!empty($data->p_mode)){ echo $data->p_mode; } ?></td>
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