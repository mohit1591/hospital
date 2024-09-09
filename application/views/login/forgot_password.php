    <div class="overlay-loader" style="display:none;left:0;top:0;background:transparent;">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
	<div class="error_msg" id="l_error_msg"><?php echo $error_msg; ?></div>
	<div class="flash_msg" id="l_error_msg"><?php echo $flash_msg; ?></div>
	<?php
    if($status==0)
    {
      ?>
		<label>Email Address</label>
		<input type="text" name="email" id="email" value="<?php echo $form_data['email']; ?>" class="login-input">
		<?php if(isset($form_error) && !empty($form_error)){ echo form_error('email'); } ?> 
		<input type="submit" name="send" value="Send" class="login-btn-back"> 
		<a href="javascript:void(0);" id="back_login" class="login-text">Back</a>
      <?php
    }
	?> 