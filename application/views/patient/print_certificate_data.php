<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
	*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto 0;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 21cm;
	  height: 27.7cm;  
	  padding: 3em;
	  font-size:13px;
	}
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
	
	<?php //print_r($form_data);  $file_name = UPLOAD_FS_FOOTER_IMAGES.$signature_image;?>
	<?php //print_r($doctor_data);?>
	<?php $file_name = ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data['sign_img'];//print_r($signature_data); ?>
	<?php $signature_data['sign_img'] = '<img src="'.$file_name.'" width="100px" />'; ?>
	<?php $template_data['template'] = str_replace("{patient_name}",$form_data['patient_name'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{mother}",$form_data['mother'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{father_husband}",$form_data['father_husband'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{country}",$form_data['country'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{city}",$form_data['city'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{state}",$form_data['state'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{address}",$form_data['address'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{dob}",date("d-m-Y", strtotime($form_data['dob'])),$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{doctor_name}",$doctor_data['doctor_name'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{signature}",$signature_data['signature'],$template_data['template']);?>
	<?php $template_data['template'] = str_replace("{sign_img}",$signature_data['sign_img'],$template_data['template']);?>
	<?php echo $template_data['template'];?>


	</page>

</body>
</html>