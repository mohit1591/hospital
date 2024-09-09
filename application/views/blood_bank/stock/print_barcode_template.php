<html>
<head>
<title></title>

<style>

	page {
	  background: white;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	}
}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
		
		<div style="float:left; width:100%; min-height: 100px;">
		<?php 	echo $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=true"/>'; ?>
		</div> 

</page>

</body>
</html>