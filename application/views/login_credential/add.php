<!-- <script type="text/javascript" src="<?php //echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script> -->
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="send_login_credentials" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Password<span class="star">*</span></label>
                    <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It will reset all patient login password and they will recieve a new password that you will enter here! </span></a></sup>
                </div>
                <div class="col-md-8">
                    <input type="text" name="password" value="" class="inputFocus">
                    <?php if(!empty($form_error)){ echo form_error('password'); } ?>
                </div>

              </div> <!-- innerrow -->
               <!-- <div class="row m-b-5">
                 <div class="col-md-4">
                     <label>Template<span class="star">*</span></label>
                 </div>
                 <div class="col-md-8">
                 <textarea name='template' class="template" id="template"><?php echo $form_data['template']; ?></textarea>
                     
                     <?php //if(!empty($form_error)){ echo form_error('template'); } ?>
                 </div>
                              </div> --> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>SMS Template<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                <textarea name='sms_template'  style="width: 100%;"><?php echo $form_data['sms_template']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('sms_template'); } ?>
                </div>
              </div> <!-- innerrow -->
               
               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>&nbsp;</label>
                </div>
                <div class="col-md-8">
                <textarea>{patient_name},{username},{password},{url}</textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('template'); } ?>
                </div>
              </div> 
               
            </div> <!-- 12 -->
          </div> <!-- row -->  
          
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Send" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     

<script>  

$("#send_login_credentials").on("submit", function(event) { 
  
 /* for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }*/
  event.preventDefault(); 
  $('#overlay-loader').show();
  
    var path = 'add/';
    var msg = 'Login credentilas sent successfully!';
 
  $.ajax({
    url: "<?php echo base_url('send_login/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_send_login_credential_modal_popup').modal('hide');
        flash_session_msg(msg);
        //reload_table();
      } 
      else
      {
        $("#load_send_login_credential_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_send_login_credential_modal_popup').modal('hide');
});
/*$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'template', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})*/
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->