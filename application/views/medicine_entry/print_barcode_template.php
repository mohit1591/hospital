<html>
<head>
<title>Medicine</title>
<style>
	page {
	  background: white;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 2in;
	  height: 4in; 
	  padding: 1em;
	  font-size:13px;
	}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
		
		<?php if($barcode_type=='horizontal'){ ?>
		<div style="float:left; width:100%; min-height: 100px;">
		<?php //for($i=1;$i<=$total_receipt;$i++){
			?>
			

			<div style="float:left;width: 100%;height:80px;background:yellow;margin-bottom:10px;clear:both;">
				<img src="<?php echo MEDICINE_BARCODE_FS_PATH.$barcode_image; ?>" style="width:100%; height: 100%; background: 100% 100%;">
			</div>
		
			<?php 

			//} ?>
		
		</div> 


		<?php }elseif($barcode_type=='vertical'){ ?>
		<!-- Vertical -->
		<div style="float:left; width:100%; min-height: 100px;">
		<?php //for($i=1;$i<=$total_receipt;$i++){
			?>
			<div style="float:left;width: 30%;min-height:80px;background:red;margin-bottom:2px;margin-right:6px;">
				<img src="<?php echo MEDICINE_BARCODE_FS_PATH.$barcode_image; ?>" style="width:100%; height: 100%; background: 100% 100%; background-repeat:no-repeat;">
			</div>
			<?php //} ?>
		</div>
		<?php } ?>
	</page>
</body>
</html>