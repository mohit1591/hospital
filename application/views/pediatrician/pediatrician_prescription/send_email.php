<div class="cp-modal">
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-top"> 
        <form  id="send_email_form" class="form-inline" > 
        <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id']; ?>">

        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $form_data['type']; ?>">

        <input type="hidden" name="date_sec" id="date_sec" value="<?php echo $form_data['date_sec']; ?>">
   
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">    
           
                            
             
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Email<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <input type="text" name="email" value="<?php echo $form_data['email']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                </div>
              </div> <!-- row -->
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Subject<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <input type="text" name="subject" value="<?php echo $form_data['subject']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('subject'); } ?>
                </div>
              </div> <!-- row -->

              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Message<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <textarea name="message"><?php echo $form_data['message']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('message'); } ?>
                </div>
              </div> <!-- row -->

                <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Attachment</label>
                </div>
                <div class="col-xs-7">
                    
                </div>
              </div> <!-- row -->

            

            </div> <!-- modal-body -->
          
             
             
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Send">Send</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
        </div>
</form>     

<script>

$("#send_email_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var booking_id  = $('#booking_id').val();
  var patient_id  = $('#patient_id').val();
  var date_sec  = $('#date_sec').val();
  var type = $('#type').val();
  var path = 'send_email/'+booking_id+'/'+date_sec+'/'+patient_id+'/'+type;
  var msg = "Email has been send successfully.";

  $.ajax({
    url: "<?php echo base_url('pediatrician/pediatrician_prescription/'); ?>"+path,
    type: "post", 
    data: $(this).serialize(), 
    success: function(result) {
      if(result==1)
      {
        $('#load_add_vac_pres_modal_popup').modal('hide');
       
        flash_session_msg(msg); 
      } 
      else
      {
        $("#load_add_vac_pres_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

 

</script>   
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->