<html>
<head>
<style>
	
	body {
			margin-top: 0in;
			margin-left: 0in;
		}
	page {
	  background: white;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	}

	page {  
	  width: auto;
	  height: 25.0mm;
	  padding: 1em;
	  font-size:13px;
	  
	  display: inline-block;
	}
	
	div.b128{
    border-left: 1px black solid;
	height: 50px;border-left: 1px black solid;
	
}

</style>
</head>
<body style="background: rgb(204,204,204);font-family:times new roman;font-size:16pt;color:#333;">

	<page size="A4">
		
	<!--	<div style="float:left; width:100%; min-height: 100px;">-->
		
			<div style="border:0px; padding:5px;margin:5px auto;width:100%;">
		<?php 	echo generate_ipd_label($barcode_text,$patient_name,$admission_date,$gender_age,$patient_code); ?> </div>

		
	<!--	</div> -->
	</page>
</body>
</html>
<?php /* <html>
<head>
	
<title></title>

<style>

	page {
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	}
	<?php 
if($type_barcode=='horizontal'){ 
?>
	page[size="A4"] {  
	  width: auto;
	  height: 24.85mm;
	  padding: 1em;
	  font-size:13px;
	  display: inline-block;
	}

<?php }else{

	?> 

page[size="A4"] {  
	  width: auto;
	  
	  height: auto; 
	  padding: 0.5rem;
	  margin-top: 10px;
	  font-size:13px;
	  display: inline-block;
	   transform: rotate(90deg);
	}
	<?php
} ?>

}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
		<div style="float:left; width:100%; min-height: 100px;">
		<?php for($i=1;$i<=$no_of_print;$i++){ ?>
		<div style="background-color: white; padding:18px;"> <?php 	echo generate_new_barcode($barcode_text,$patient_name,$patient_date); ?> </div>

			<?php 
			echo "<br>";
		}
			?>
		</div> 
	</page>
</body>
</html> */ ?>