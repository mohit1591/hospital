<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

<div class="row">
	<div class="col-sm-4">
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label><input type="radio" name=""> New Patient</label></div>
			<div class="col-sm-7">
				<label><input type="radio" name=""> <span>Registered</span></label>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label></div>
			<div class="col-sm-7">
				<div class="ipdbox">2017/0021</div>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>IPD No.</label></div>
			<div class="col-sm-7">
				<div class="ipdbox"> 2017/0021 </div>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Patient Name <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select class="mr">
					<option>-Select-</option>
					<option>Mr.</option>
					<option>Mrs.</option>
				</select>
				<input type="text" name="" class="mr-name">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Mobile No. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Gender <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<span class="text-normal"><input type="radio" name=""> <span>Male</span></span>
				<span class="text-normal"><input type="radio" name=""> <span>Female</span></span>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Age <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="" class="input-tiny"> Y
				<input type="text" name="" class="input-tiny"> M
				<input type="text" name="" class="input-tiny"> D
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Patient Address <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<textarea></textarea>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label></label></div>
			<div class="col-sm-7">
				<button class="btn-update" type="submit" name=""><i class="fa fa-floppy-o"></i> Submit</button>
				<button class="btn-update" type="submit" name="" data-target="#ipd_booking_more" data-toggle="modal"><i class="fa fa-sign-out"></i> Exit</button>
			</div>
		</div>

	</div> <!-- 4 -->


	


	<div class="col-sm-4">
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Room Type <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Room No. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Bed No. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Attended Doctor <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Assigned Doctor <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<div class="ipd_right_scroll_box">
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
					<div class="list">
						<input type="checkbox" name="">  <span>Doc1</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Advance Deposit <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label></label></div>
			<div class="col-sm-7">
				
			</div>
		</div>
		
	</div> <!-- 4 -->

	<div class="col-sm-4">
		
		<div class="row m-b-2">
			<div class="col-sm-5"><label>Patient Type <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<label><input type="radio" name=""> Normal</label> &nbsp;
				<label><input type="radio" name=""> Panel</label>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Type <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Name <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<select>
					<option>-Select-</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Policy No. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>ID No. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label>Authorization Amt. <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="">
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5 p-r-0"><label>Admission Date & Time <span class="star">*</span></label></div>
			<div class="col-sm-7">
				<input type="text" name="" class="input-small" placeholder="Date">
				<input type="text" name="" class="input-tiny" placeholder="Time">
				<select name="" class="input-tiny">
					<option>AM</option>
					<option>PM</option>
				</select>
			</div>
		</div>
		
		<div class="row m-b-5">
			<div class="col-sm-5"><label></label></div>
			<div class="col-sm-7">
				
			</div>
		</div>
		
	</div> <!-- 4 -->
</div> <!-- main row -->












</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>