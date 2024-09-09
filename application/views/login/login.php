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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>  


 

</head>

<body>
 
<div style="background:linear-gradient(360deg,#209560,#31a36f,#3baa77,#56bc8d,#65c79b,#7bd4ac,#95e1bf,#adeccf,#c7f7e1,#e3fcf1); background-size:100% 100%; background-repeat:no-repeat; height:100%;width:100%;position: absolute;overflow:hidden;">



<!-- ============================= Main content start here ===================================== -->
<section class="login">
  <!--<img src="<?php //echo ROOT_IMAGES_PATH; ?>logo.png" class="login-logo">-->

  <div class="login-box" id="login_box">
	  <div class="login-header">
	  	<img src="<?php echo ROOT_IMAGES_PATH; ?>login_icon.png"> <span>Login</span>
	  </div>
	  <div class="login-form-box">
	  <div class="error_msg" id="l_error_msg"><?php echo $error_msg; ?></div>
	  <div class="flash_msg" id="l_flash_msg"></div>
	  <form id="login_form" autocomplete="false">
	  <div id="login_form_box">
	  	<?php $this->load->view('login/login_form'); ?>
	  </div>
	  </form>	
	  </div> 
  </div>

  <div class="login-box" id="forgot_password_box" style="display: none;">
	  <div class="login-header">
	  	<img src="<?php echo ROOT_IMAGES_PATH; ?>login_icon.png"> <span>Forgot Password</span>
	  </div>
	  <div class="login-form-box">
	  <div class="error_msg" id="error_fp_msg"></div>
	  <form id="forgot_password_form" autocomplete="false">
	  <div id="forgot_form_box">
	  	<?php $this->load->view('login/forgot_password'); ?>
	  </div>
	  </form>	
	  </div> 
  </div>
 

</section> <!-- dr -->
</div>
<!-- ============================= Main content close here ========================================== -->

<script>
$("#login_form").on("submit", function(event) {
	event.preventDefault();
	$('.overlay-loader').show();
	$.ajax({
		url: "<?php echo base_url('login'); ?>",
		type: "post", 
		data: $(this).serialize(),
		success: function(result)
		{  
		   if(result==1)
		   {
		   	   window.location.href="<?php echo base_url('dashboard'); ?>";
		   } 
		   else
		   { 
               $('#login_form_box').html(result);
		   } 
           $('.overlay-loader').hide();
		   
		}
		});
});	

$("#forgot_password_form").on("submit", function(event) { 
	event.preventDefault();
	$('.overlay-loader').show();
	$.ajax({
		url: "<?php echo base_url('forgot-password'); ?>",
		type: "post", 
		data: $(this).serialize(),
		success: function(result)
		{  
		   $('#forgot_form_box').html(result);		
		   $('.overlay-loader').hide();   
		}
		});
});	
$(document).ready(function(){
	$("#login_form_box").on("click","#forgot-password",function(){
		
		    $("#login_box").slideUp();
		    $("#forgot_password_box").slideDown();
		});
	$("#forgot_form_box").on("click","#back_login",function(){
		
		    $("#forgot_password_box").slideUp();
		    $("#login_box").slideDown(); 
		});
});
</script>

</body>
</html>