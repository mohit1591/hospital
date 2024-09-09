<div class="cp-modal">
<div class="modal-dialog">

  <div class="modal-content modal-top"> 
    <form  id="change_password_form" class="form-inline"> 
  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">    
           
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Old password<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                      <input type="password" name="old_password" value="<?php echo $form_data['old_password']; ?>">
                      <?php if(!empty($form_error)){ echo form_error('old_password'); } ?>
                </div>
              </div> <!-- row -->
              
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>New password<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <input type="password" name="password" value="<?php echo $form_data['password']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('password'); } ?>
                </div> 
              </div> <!-- row -->
              
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Confirm password<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <input type="password" name="cpassword" value="<?php echo $form_data['cpassword']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('cpassword'); } ?>
                </div>
              </div> <!-- row -->

            </div> <!-- modal-body -->
          
             
             
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Save</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Cancel</button> &nbsp;
        </div>
</form>     

<script>  
 
$("#change_password_form").on("submit", function(event) { 
  event.preventDefault();  
  $.ajax({
    url: "<?php echo base_url('change-password'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_change_password_popup').modal('hide');
        flash_session_msg('Password successfully changes.'); 
      } 
      else
            {
        $("#load_change_password_popup").html(result);
      }       
    }
  });
}); 
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->