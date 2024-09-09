
	<label>Password</label>
	<input type="password" name="password" id="password" value="<?php echo $form_data['password']; ?>" class="login-input">
	<?php if(isset($form_error) && !empty($form_error)){ echo form_error('password'); } ?>
	<label>Confirm password</label>
	<input type="password" name="cpassword" id="cpassword" value="<?php echo $form_data['cpassword']; ?>" class="login-input">
	<?php if(isset($form_error) && !empty($form_error)){ echo form_error('cpassword'); } ?>
	<input type="submit" name="" value="Reset" class="login-btn-reset">  