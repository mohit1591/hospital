<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>  
<meta name="viewport" content="width=1024">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet"> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>  


 

</head>

<body style="background:linear-gradient(360deg,#b64a30,#d6755e,#ea927d,#f1ad9d,#fbc8bc,#fbdfd9,#fcefec); background-size:100% 100%; background-repeat:no-repeat; height:auto;">




<!-- ============================= Main content start here ===================================== -->
<section class="login">
  <img src="<?php echo ROOT_IMAGES_PATH; ?>logo.png" class="login-logo">

  <div class="login-box" id="login_box">
	  <div class="login-header">
	  	<img src="<?php echo ROOT_IMAGES_PATH; ?>login_icon.png"> <span>Reset Password</span>
	  </div>
	  <div class="login-form-box">
	  <?php
       if($action==1)
       {
       	?>
          <div class="error_msg" id="rp_error_msg"></div> 
			  <form id="reset_password_form">
				  <div id="login_form_box">
				  	<?php $this->load->view('login/reset_password_form'); ?>
				  </div>
			  </form>	
		  </div> 
       	<?php
       }
       else
       {
       	?>
        <div class="error_msg" id="rp_error_msg"><?php echo $message; ?></div> 
       	<?php
       }
	  ?>
	  
  </div> 
 

</section> <!-- dr -->
<!-- ============================= Main content close here ========================================== -->

<script>
$("#reset_password_form").on("submit", function(event) {  
	event.preventDefault();
	$.ajax({
			url: "<?php echo current_url(); ?>",
			type: "post", 
			data: $(this).serialize(),
			success: function(result)
			{ 
			   $('#login_form_box').html(result);		   
			}
		});
});	
 
</script>

</body>
</html>