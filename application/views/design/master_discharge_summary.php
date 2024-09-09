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

<!-- // master_discharge_summary -->
<div class="master_discharge_summary_left">
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Name <span class="star">*</span></label>
				</div>
				<div class="col-xs-8">
					<input type="text" name="">
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-5">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label> <span class="star"></span></label>
				</div>
				<div class="col-xs-8">
					<span class="m-r-3"><input type="radio" name=""> <span>LAMA</span></span>
					<span class="m-r-3"><input type="radio" name=""> <span>REFERRAL</span></span>
					<span class="m-r-3"><input type="radio" name=""> <span>Discharge</span></span>
					<span class="m-r-3"><input type="radio" name=""> <span>D.O.P.R</span></span>
					<span class="m-r-3"><input type="radio" name=""> <span>Normal</span></span>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Chief Complaints</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>H/O Presenting Illness</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>On Examination</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Vitals</label>
				</div>
				<div class="col-xs-8">
					<div class="row m-b-2">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-6"><strong>Pulse: /Min</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="row">
								<div class="col-xs-6"><strong>Chest</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
					</div> <!-- // -->
					<div class="row m-b-2">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-6"><strong>BP: mm/Hg</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="row">
								<div class="col-xs-6"><strong>CVS</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
					</div> <!-- // -->
					<div class="row m-b-2">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-6"><strong>emp: F</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="row">
								<div class="col-xs-6"><strong>CNS</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
					</div> <!-- // -->
					<div class="row">
						<div class="col-xs-6">
							<div class="row">
								<div class="col-xs-6"><strong></strong></div>
								<div class="col-xs-6"></div>
							</div>
						</div>
						<div class="col-xs-5">
							<div class="row">
								<div class="col-xs-6"><strong>P/A</strong></div>
								<div class="col-xs-6"><input type="text" name="" class="input-small"></div>
							</div>
						</div>
					</div> <!-- // -->
				</div> <!-- 8 -->
			</div> <!-- innerRow -->
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Provisional Diagnosis</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Final Diagnosis</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Course in Hospital</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Investigations</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Condition at Discharge Time</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Advise on Discharge</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->
	
	<div class="row m-b-2">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<label>Review time and Date</label>
				</div>
				<div class="col-xs-8">
					<textarea class="form-control textarea-h-40"></textarea>
				</div>
			</div>
		</div>
	</div> <!-- row -->


</div> <!-- master_discharge_summary_left -->

<div class="master_discharge_summary_right">
	<div class="well">
		
		<table class="table table-bordered table-striped">
			<thead class="bg-theme">
				<tr>
					<th width="40"><input type="checkbox" name=""></th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td align="center"><input type="checkbox" name=""></td>
					<td>Ram Lal</td>
				</tr>
				<tr>
					<td align="center"><input type="checkbox" name=""></td>
					<td>Ram Lal</td>
				</tr>
			</tbody>
		</table>

	</div> <!-- well -->

	<div class="btns">
			<button type="button" class="btn-save"><i class="fa fa-floppy-o"></i> Save</button>
			<button type="button" class="btn-save"><i class="fa fa-refresh"></i> Update</button>
			<button type="button" class="btn-save"><i class="fa fa-trash"></i> Delete</button>
			<button type="button" class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
	</div>

</div> <!-- master_discharge_summary_right -->

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>