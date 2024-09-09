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

<!-- // IPD booking -->
<div class="ipd_booking_left">
	<div class="grp">
		<label>
			<input type="radio" name=""> New Patient
		</label>
		<div class="ipdbox">
			<input type="radio" name=""> <span>Registered</span>
		</div>
	</div>

	
	<div class="grp">
		<label>Patient Reg. No.</label>
		<div class="ipdbox">
			2017/0021
		</div>
	</div>

	
	<div class="grp">
		<label>IPD No.</label>
		<div class="ipdbox">
			2017/0021
		</div>
	</div>

	
	<div class="grp">
		<label class="arrow">Patient Name <span class="star">*</span></label>
		<div class="ipdbox">
			<select>
				<option>-Select-</option>
				<option>Mr.</option>
				<option>Mrs.</option>
			</select>
			<input type="text" name="">
		</div>
	</div>

	
	<div class="grp">
		<label>Mobile No. <span class="star">*</span></label>
		<input type="text" name="">
	</div>

	
	<div class="grp">
		<label>Gender <span class="star">*</span></label>
		<div class="ipdbox">
			<span class="text-normal"><input type="radio" name=""> <span>Male</span></span>
			<span class="text-normal"><input type="radio" name=""> <span>Female</span></span>
		</div>
	</div>

	
	<div class="grp">
		<label>Age <span class="star">*</span></label>
		<div class="ipdbox">
			<span class="text-normal m-r-5"><input type="text" name="" class="input-tiny"> <span>Y</span></span>
			<span class="text-normal m-r-5"><input type="text" name="" class="input-tiny"> <span>M</span></span>
			<span class="text-normal"><input type="text" name="" class="input-tiny"> <span>D</span></span>
		</div>
	</div>

	
	<div class="grp">
		<label>Patient Address <span class="star">*</span></label>
		<textarea></textarea>
	</div>

	
	<div class="grp">
		<label></label>
		<div class="ipdbox">
			<button class="btn-save" type="submit" name=""><i class="fa fa-floppy-o"></i> Submit</button>
			<button class="btn-save" type="submit" name="" data-target="#ipd_booking_more" data-toggle="modal"><i class="fa fa-info-circle"></i> More</button>
		</div>
	</div>
</div> <!-- ipd_booking_left -->




<!-- // ipd_booking_more modal -->
<div id="ipd_booking_more" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4><?php echo $page_title_more; ?></h4>
	      </div>
	      <div class="modal-body">
	        
	      		<div class="row">
					<div class="col-xs-6">
						<label>Patient Type <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<input type="radio" name=""> <span>Normal</span> &nbsp;
						<input type="radio" name=""> <span>Panel</span>
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>Type <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<select>
							<option>-Select-</option>
						</select>
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>Name <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<select>
							<option>-Select-</option>
						</select>
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>Policy No. <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<input type="text" name="">
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>ID No. <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<input type="text" name="">
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>Authorization Amt. <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<input type="text" name="">
					</div>
				</div>
	        
	      		<div class="row m-b-5">
					<div class="col-xs-6">
						<label>Admission Date and Time <span class="star">*</span></label>
					</div>
					<div class="col-xs-6">
						<input type="text" name="" class="input-small" placeholder="Date">
						<input type="text" name="" class="input-tiny" placeholder="Time">
						<select name="" class="input-tiny">
							<option>AM</option>
							<option>PM</option>
						</select>
					</div>
				</div>





	      </div> <!-- modal body -->
	      <div class="modal-footer">
	      	<button class="btn-save" type="submit" name="">Save</button>
	        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
	      </div>
	    </div>

	  </div>
	
</div> <!-- modal close -->



<div class="ipd_booking_right">
	
	<div class="grp">
		<label>Room Type <span class="star">*</span></label>
		<select>
			<option>-Select-</option>
		</select>
	</div>

	
	<div class="grp">
		<label>Room No. <span class="star">*</span></label>
		<select>
			<option>-Select-</option>
		</select>
	</div>

	
	<div class="grp">
		<label>Bed No. <span class="star">*</span></label>
		<select>
			<option>-Select-</option>
		</select>
	</div>

	
	<div class="grp">
		<label>Attended Doctor <span class="star">*</span></label>
		<select>
			<option>-Select-</option>
		</select>
	</div>	

	
	<div class="grp">
		<label>Assigned Doctor <span class="star">*</span></label>
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

	
	<div class="grp">
		<label>Advance Deposit <span class="star">*</span></label>
		<input type="text" name="">
	</div>
</div> <!-- ipd_booking_right -->












</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>