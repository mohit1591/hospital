 <?php  $users_data = $this->session->userdata('auth_users'); ?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="advance_search_form" class="form-inline"> 
      <input type="hidden" name="status" value="send">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>Send Type</label>
                          <select name="send_type">
                            <option value="1">SMS/Email</option>
                            <option value="2">SMS</option>
                            <option value="3">Email</option>
                          </select>
                        </div>         
                        
                        <div class="grp">
                          <label>SMS </label>
                          <textarea name="sms_msg" id="sms_msg"><?php echo $sms_template['template']; ?></textarea>
                        </div>        

                      </div> <!-- inner -->

                      <div class="row col-xs-12" style="">
                        
                        <div class="col-sm-2">
                          <label>Email </label> 
                        </div>
                        <div class="col-sm-10">  
                          <textarea name="email_msg" class="email_msg w-full" id="email_msg"><?php echo $email_template['template'];?></textarea>
                        </div>        

                      </div> <!-- inner -->
                      

                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> --> 
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  
  
 
 
$("#advance_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('crm/leads/send_sms'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_disease_modal_popup').modal('hide'); 
      reload_table();     
      flash_session_msg('SMS/Email  successfully sent.');   
      $('#overlay-loader').hide();
    }
  });
}); 
 

$(document).ready(function() {
  $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});


$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'email_msg', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );


});
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->