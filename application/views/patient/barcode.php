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
			
			position:relative;

		}
		page[size="A4"] {  
		    float:left;
			width: 13cm;
			min-height: 2.5cm; 
			padding: 0 2em;
		}
		@page {
			size: auto;   
			margin: 0;  
		}
		
		.barcode-new{
		    display:inline-block;
		    width:200px;
		    height:68px;
		    border:1px solid #ccc;
		    padding:6px;
		    border-radius:2px;
		    margin:60px 0px 5px 0px;
		}
		.barcode-new:nth-child(even){
		    margin-left:36px;
		}
	</style>
</head>
<body style="font-family: sans-serif, Arial;">
	<page size="A4" style="display:block;margin:-3rem auto; font-size:12px;">
	    <?php 
	    if(!empty($total_no))
	    {
		  for($i=1;$i<=$total_no;$i++){ ?>
		<div class="barcode-new" style="">
		<div style="display:block;padding:0px 4px;"><?php
echo '<img class="barcode" alt="'.$barcode_id.'" src="'.base_url('barcode.php').'?text='.$barcode_id.'&codetype=code128&orientation=horizontal&size=27&print=true"/>'; ?>
</div>
</div>
		<?php } 
	    }
	    else
	    {
	     ?>
	     <div class="barcode-new" style="">
		<div style="display:block;padding:0px 4px;"><?php
echo '<img class="barcode" alt="'.$barcode_id.'" src="'.base_url('barcode.php').'?text='.$barcode_id.'&codetype=code128&orientation=horizontal&size=27&print=true"/>'; ?>
</div>
</div>
	     <?php   
	    }
		
		 ?>
	</page>
</body>
</html>