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

<!-- // opd booking modal -->
<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#opd_booking">OPD Booking</button>
<div id="opd_booking" class="modal modal fade in modal-45" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><?php echo $modal_title; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Patient Reg. No.</label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">2017/0021</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>OPD No.</label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">0015</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Patient Name <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<select class="mr">
								<option>Mr.</option>
								<option>Mrs.</option>
							</select>
							<input type="text" name="" class="mr-filed">
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Mobile No. <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<input type="text" name="">
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Gender <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<label><input type="radio" value=""> Male</label>
							<label><input type="radio" value=""> Female</label>
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Age <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<label><input type="text" value="" class="input-tiny"> Y</label>
							<label><input type="text" value="" class="input-tiny"> M</label>
							<label><input type="text" value="" class="input-tiny"> D</label>
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Specialization  <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<select>
								<option>-Select-</option>
								<option>Physician</option>
							</select>
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label>Consultant   <span class="star">*</span></label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<select>
								<option>-Select-</option>
								<option></option>
							</select>
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-5"><label> Appointment date </label></div>	
					<div class="col-xs-7">
						<div class="opd-booking-right-box">
							<input type="date" name="">
						</div>	
					</div>	
				</div>
			</div> <!-- modal body -->
			<div class="modal-footer">
				<button class="btn-save" type="button" name=""> Save</button>
				<button class="btn-cancel" type="button" name="" data-dismiss="modal"> Close</button>
			</div>
		</div>
	</div>
</div>





<!--  // more button modal -->
<button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#opd_booking-more">More</button>
<div id="opd_booking-more" class="modal modal fade in modal-40" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4><?php echo $modal_title_more; ?></h4>
			</div>
			<div class="modal-body">
				<div class="row opd-booking">
					<div class="col-xs-4"><label>Email</label></div>	
					<div class="col-xs-8">
						<div class="opd-booking-right-box">
							<input type="text" name="">
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-4"><label>Address</label></div>	
					<div class="col-xs-8">
						<div class="opd-booking-right-box">
							<textarea></textarea>
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-4"><label>Panel</label></div>	
					<div class="col-xs-8">
						<div class="opd-booking-right-box">
							<input type="text" name="">
						</div>
					</div>	
				</div>
				<div class="row opd-booking">
					<div class="col-xs-4"><label>OPD Date <span class="star">*</span></label></div>	
					<div class="col-xs-8">
						<div class="opd-booking-right-box">
							<input type="text" name="">
						</div>
					</div>	
				</div>
			</div> <!-- modal body -->
			<div class="modal-footer">
				<button class="btn-save" type="button" name=""> Save</button>
				<button class="btn-cancel" type="button" name="" data-dismiss="modal"> Close</button>
			</div>
		</div>
	</div>
</div>













</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>