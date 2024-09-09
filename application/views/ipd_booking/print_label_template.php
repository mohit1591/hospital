<?php
	if($total_no > 1){
?>

<html>
<head>
	<title></title>
	<style type="text/css">
		*{padding: 0;margin: 0;box-sizing: border-box;}
		page {
			background: white;
			display: block;
			padding-left:{margin_left}px !important;
			padding-right:{margin_right}px !important;
			padding-top:{margin_top}px !important;
			padding-bottom:{margin_bottom}px !important;
			margin: 0 auto;
			margin-bottom: 0.5cm;
			position:relative;

		}
		page[size="A4"] {  
			width: 21cm;
			min-height: 25cm; 
			padding: 0px;
		}
		@page {
			size: auto;   
			margin: 0;  
		}
	</style>
</head>
<body style="font-family: sans-serif, Arial;">
	<page size="A4" style="display:block;margin:0px auto;border:1px solid #ccc;font-size:12px;">
		<div style="display: flex; justify-content: center; flex-wrap: wrap;">
		<?php 
		
		$users_data = $this->session->userdata('auth_users'); 
       
		if($users_data['parent_id']=='158')
		{
		    for($i=1;$i<=$total_no;$i++){ ?>
		<div style="display:inline-block;width:10.2cm;height:4.8cm;border:1px solid #ccc;margin:1px;padding:6px;border-radius:2px;">
			<div style="padding: 1px 4px;"><b>UHID:</b> <?php echo $patient_code; ?> IPD:<?php echo $barcode_text; ?></div>
			
			<div style="padding: 1px 4px;"><b>Name:</b> <?php echo $patient_name; ?> /<?php echo $gender; ?></div>
			<div style="padding: 1px 4px;"><b>Mob.:</b> <?php echo $mobile_no; ?> Age:<?php echo $age; ?></div>
			
			<!--<div style="padding: 1px 4px;">Admission Dt: < ?php echo $admission_date; ?></div>-->
			<div style="padding: 1px 4px;"><?php 	echo $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=false"/>'; ?><?php //	echo generate_ipd_barcode_label($barcode_text); ?></div>
			
			
		</div>
		<?php }
		}
		else
		{
		  for($i=1;$i<=$total_no;$i++){ ?>
		<div style="display:inline-block;width:10.2cm;height:4.8cm;border:1px solid #ccc;margin:1px;padding:6px;border-radius:2px;">
			<div style="padding: 1px 4px;"><b>Name:</b> <?php echo $patient_name; ?></div>
			<div style="padding: 1px 4px;"><b>UHID No.:</b> <?php echo $patient_code; ?></div>
			<!-- <div style="padding: 1px 4px;">IPD No.: <?php echo $barcode_text; ?></div> -->
			<div style="padding: 1px 4px;"><b>Gender/Age:</b> <?php echo $gender_age; ?></div>

			<div style="padding: 1px 4px;"><b>Prescribed By:</b> </div>
			<div style="padding: 1px 4px;"><b>Drug Name:</b> </div>
			<div style="padding: 1px 4px;"><b>Dose:</b> </div>
			<div style="padding: 1px 4px;"><b>Volume and Type of Solution:</b> </div>
			<div style="padding: 1px 4px;"><b>Staff sign:</b> </div>
			<div style="padding: 1px 4px;"><b>RMP ID:</b> </div>
			
			<!-- <div style="padding: 1px 4px;">Phone No.: <?php echo $mobile_no; ?></div> -->
			
			<div style="padding: 1px 4px;"><b>Admission Dt:</b> <?php echo $admission_date; ?></div>
			<!-- <div style="padding: 1px 4px;"><?php 	echo generate_ipd_barcode_label($barcode_text); ?> -->
		</div>
		<?php }  
		}
		 ?>
		</div>
	</page>
</body>
</html>
<?php
 } else {
?>
<html>
<head>
	<title></title>
	<style type="text/css">
		* {
			padding: 0;
			margin: 0;
			box-sizing: border-box;
		}
		.page {
			background: white;
			display: block;
			padding: 0cm 0.2cm;
			margin: 0 auto;
			position: relative;
			border: 1px solid #ccc; /* Add border for label boundary */
			width: 7.5cm; /* Set width for thermal printer */
			height: 5cm; /* Set height for thermal printer */
			background: white;
			box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
		}
		
		body {
			font-family: sans-serif, Arial;
			font-size: 13px;
		}
	</style>
</head>
<body style="background: rgb(204,204,204);color:#333;">
	<div class="page">
		<?php 
		// Fetch necessary data
		$users_data = $this->session->userdata('auth_users'); 
       
		if($users_data['parent_id'] == '158') {
		?>
		<div style="padding: 3px 4px;">UHID: <?php echo $patient_code; ?> IPD:<?php echo $barcode_text; ?></div>
		<div style="padding: 3px 4px;">Name: <?php echo $patient_name; ?> /<?php echo $gender; ?></div>
		<div style="padding: 3px 4px;">Mob.: <?php echo $mobile_no; ?> Age:<?php echo $age; ?></div>
		<div style="padding: 3px 4px;"><?php echo $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=false"/>'; ?></div>
		<?php 
		} else {
		?>
			<div style="padding: 1px 4px;">Name: <?php echo $patient_name; ?></div>
			<div style="padding: 1px 4px;">UHID No.: <?php echo $patient_code; ?></div>
			<!-- <div style="padding: 1px 4px;">IPD No.: <?php echo $barcode_text; ?></div> -->
			<div style="padding: 1px 4px;">Gender/Age: <?php echo $gender_age; ?></div>

			<div style="padding: 1px 4px;">Prescribed By: </div>
			<div style="padding: 1px 4px;">Drug Name: </div>
			<div style="padding: 1px 4px;">Dose: </div>
			<div style="padding: 1px 4px;">Volume and Type of Solution: </div>
			<div style="padding: 1px 4px;">Staff sign: </div>
			<div style="padding: 1px 4px;">RMP ID: </div>
			
			<!-- <div style="padding: 1px 4px;">Phone No.: <?php echo $mobile_no; ?></div> -->
			
			<div style="padding: 1px 4px;">Admission Dt: <?php echo $admission_date; ?></div>
			<!-- <div style="padding: 1px 4px;"><?php 	echo generate_ipd_barcode_label($barcode_text); ?> -->
		<?php 
		}
		?>
	</div>
</body>
</html>

<?php } ?>