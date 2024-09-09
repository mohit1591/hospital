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
		    height: 25cm; 
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
			$doctor_name= get_doctor_name($attend_doctor_id);
	        for($i=1;$i<=$total_no;$i++){ ?>
			
				<div style="display:inline-block;width:10.2cm;height:4.8cm;margin:1px;padding:6px;border:1px solid #ccc	">
				<div style="display:block;padding:1px 4px;"><b>UHID:</b> <?php echo $patient_code; ?> / IPD:<?php echo $barcode_text; ?></div>
				
				<div style="display:block;padding:1px 4px;"><b>Name:</b> <?php echo $patient_name; ?> /<?php echo $age; ?>/<?php echo $gender; ?></div>
				<div style="display:block;padding:1px 4px;"><b>Mob.:</b> <?php echo $mobile_no; ?> </div>
				<div style="display:block;padding:1px 4px;"><b>Doctor:</b> <?php echo $doctor_name; ?> </div>
				
				<div style="display:block;padding:1px 4px;"><b>Admission Dt:</b> <?php echo $admission_date; ?></div>
				<div style="display:block;padding:2px 4px;"><?php 	echo $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=false"/>'; ?></div>
				
				
			</div>
		
			<?php } ?>
		</div>
	</page>
</body>
</html>
