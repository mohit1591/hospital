<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="advice" class="form-inline">
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
                    <label>Days<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="days" value="<?php echo $form_data['days'];?>" class="inputFocus">
                    
                    
                    <?php if(!empty($form_error)){ echo form_error('days'); } ?>
                </div>
              </div> <!-- innerrow -->
               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Template<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                <textarea name='template' class="template" id="template"><?php echo $form_data['template']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('template'); } ?>
                </div>
              </div> <!-- innerrow -->

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
                <textarea>{contact_person},{hospital_name},{expiry_date}</textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('template'); } ?>
                </div>
              </div> 
               
            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                </div>
                <div class="col-md-8">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     

<script>  


  $('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
    
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     
  });



function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#advice").on("submit", function(event) { 
  
  for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Renewal mail template successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Renewal mail template successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('renewal_mail/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_renewal_mail_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_renewal_mail_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_renewal_mail_modal_popup').modal('hide');
});
$(document).ready(function(){
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
})
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->