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
			min-height: 24.7cm; 
			padding: 2em;
		}
		@page {
			size: auto;   
			margin: 0;  
		}
	</style>
</head>
<body style="font-family: sans-serif, Arial;">
	<page size="A4" style="display:block;margin:0.5rem auto;border:1px solid #ccc;font-size:12px;">
		
		<?php 
		
		$users_data = $this->session->userdata('auth_users'); 
       
		 
		    for($i=1;$i<=$total_no;$i++){ ?>
		<div style="display:inline-block;width:20.5%;height:40px;border:1px solid #ccc;margin:5px 5px 15px;padding:6px;border-radius:2px;"> 
			<div style="display:block;padding:3px 4px;"><?php 	echo $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=false"/>'; ?><?php //	echo generate_ipd_barcode_label($barcode_text); ?></div>
			
			
		</div>
		<?php }  
		 ?>
		
	</page>
</body>
</html>
