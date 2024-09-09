
	<div class="error_msg" id="l_error_msg"><?php echo $error_msg; ?></div>
	<label>Username</label>
	<input type="text" name="username" id="username" value="<?php echo $form_data['username']; ?>" class="login-input">
	<?php if(isset($form_error) && !empty($form_error)){ echo form_error('username'); } ?>
	<label>Password</label>
	<input type="password" name="password" id="password" value="<?php echo $form_data['password']; ?>" class="login-input">
	<?php if(isset($form_error) && !empty($form_error)){ echo form_error('password'); } ?>
	<input type="submit" name="" value="LOGIN" class="login-btn"> 
	<a href="javascript:void(0);" id="forgot-password" class="login-text">Forgot Password</a>