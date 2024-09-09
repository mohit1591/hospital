<div class="cp-modal">
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-top"> 
    <form  id="prescription_video_form" class="form-inline" enctype="multipart/form-data"> 
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
    <input type="hidden" name="prescription_id" id="prescription_id" value="<?php echo $form_data['prescription_id']; ?>">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id']; ?>">
    <input type="hidden" name="doctor_mobile_no" id="booking_id" value="<?php echo $form_data['doctor_mobile_no']; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
            <div class="modal-body">    
                 <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Patient Name<span class="star">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="patient_name" id="patient_name" class="inputFocus" value="<?php echo $form_data['patient_name']; ?>">
                             <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                 <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Patient Code<span class="star">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="patient_code" id="patient_code" class="inputFocus" value="<?php echo $form_data['patient_code']; ?>">
                             <?php if(!empty($form_error)){ echo form_error('patient_code'); } ?>
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                 
                  <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Patient Phone<span class="star">*</span></label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="patient_phone" class="" value="<?php echo $form_data['patient_phone']; ?>">
                             <?php if(!empty($form_error)){ echo form_error('patient_phone'); } ?>
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Patient Email</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="patient_email" class="" value="<?php echo $form_data['patient_email']; ?>">
                             
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Doctor Name</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="doctor_name" class="" value="<?php echo $form_data['doctor_name']; ?>">
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                  
                  
                  
                  <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Start Time</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="appointment_utc_time" class="" value="">
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
                  <div class="row">
                    <div class="col-md-12 m-b1">
                      <div class="row">
                        <div class="col-md-4">
                            <label>Expiry Time</label>
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="appointment_expireby_time" class="" value="">
                         </div>
                      </div> <!-- innerrow -->
                    </div> <!-- 12 -->
                  </div>
            </div> <!-- modal-body -->
            
           
          
            






 
             
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Send</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
        </div>
</form>     

<script>  
 
$("#prescription_video_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var data_id  = $('#data_id').val();
  var prescription_id  = $('#prescription_id').val();
  
  var path = 'share_video_link'+'/'+prescription_id;
  var msg = "Video link sent successfully.";
  
  $.ajax({
    url: "<?php echo base_url('dialysis_prescription/'); ?>"+path,
    type: "post", 
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false, 
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg); 
      } 
      else
            {
        $("#load_add_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
</script>   
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->