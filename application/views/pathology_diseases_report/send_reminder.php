 <div class="modal-dialog" style="">
    <div class="overlay-loader" id="overlay-loader-comission">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="sms_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body"> 

               <div class="row">
                 
                        <div class="col-md-12 m-b1">
                        
                          <div class="row">
                            <div class="col-md-4">
                            <label> Name </label>
                            </div>
                            <div class="col-md-8">
                                <?php echo $name; ?>
                            </div>
                          </div> <!-- innerrow -->

                          <div class="row">
                            <div class="col-md-4">
                            <label> Mobile </label>
                            </div>
                            <div class="col-md-8">
                                <?php if(!empty($mobile)){ echo $mobile;}else{ echo "<span style='color:red;'>Please add the mobile No. first to send the Notifications!</span>";}  ?>
                                <input type="hidden" name="mobile" value="<?php echo $mobile; ?>">
                                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                                <input type="hidden" name="person_id" value="<?php echo $patient_id; ?>">
                            </div>
                          </div> <!-- innerrow -->

                          <div class="row">
                            <div class="col-md-4">
                            <label> Message </label>
                            </div>
                            <div class="col-md-8">
                                <textarea type="text" name="message" class="" id="message" cols="105"><?php //echo $setting_list->setting_value; ?></textarea>
                            </div>
                          </div> <!-- innerrow -->
                        </div> <!-- 12 -->
                
                </div> <!-- row -->

            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Save" />
               <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
    </form>     

<script>    
$("#sms_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader-comission').show();  
  $.ajax({
    url: "<?php echo base_url('pathology_diseases_report/send_reminder'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        var msg = "Sms Reminder.";
        $('#load_send_reminder_modal_popup').modal('hide');
        flash_session_msg(msg);  
      } 
      else
      {
        $("#load_send_reminder_modal_popup").html(result);
      }       
      $('#overlay-loader-comission').hide();
    }
  });
}); 

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

$("button[data-number=1]").click(function(){
    $('#load_send_reminder_modal_popup').modal('hide');
});

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div>  
</div><!-- /.modal-dialog -->