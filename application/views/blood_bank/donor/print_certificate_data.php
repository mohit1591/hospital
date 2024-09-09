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
	<?php //$file_name = ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data['sign_img'];//print_r($signature_data); ?>
	<?php //$signature_data['sign_img'] = '<img src="'.$file_name.'" width="100px" />'; ?>
	<?php $template_data['template'] = str_replace("{patient_name}",$donor_data['donor_name'],$template_data['template']);?>
    <?php 
        if(isset($blood_details['collection_date']) && !empty($blood_details['collection_date']))
      {
        $collection_date= date('d-m-Y',strtotime($blood_details['collection_date'])); 
       } 
      else{
        $collection_date= '';
      }
      
    ?>
    <?php $template_data['template'] = str_replace("{date}",$collection_date,$template_data['template']);?>
	<?php 
	$user_detail = $this->session->userdata('auth_users');
	$template_data['template'] = str_replace("{sign_img}",ucfirst($user_detail['user_name']),$template_data['template']);?>
	<?php //$template_data['template'] = str_replace("{sign_img}",$signature_data['sign_img'],$template_data['template']);?>
	<?php echo $template_data['template'];?>


	</page>

</body>
</html>