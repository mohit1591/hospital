<div class="modal-dialog  modal-50">
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
                    <label>Features<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    
                    <textarea name='features' class="inputFocus"><?php echo $form_data['features']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('features'); } ?>
                </div>
              </div> <!-- innerrow -->
               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Start Date<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                <input  name="start_date" class="datepicker start_datepicker" type="text" value="<?php echo $form_data['start_date']?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('start_date'); } ?>
                </div>
              </div> <!-- innerrow -->
               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>End Date<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                <input  name="end_date" class="datepicker datepicker_to end_datepicker" type="text" value="<?php echo $form_data['end_date']?>">
                 
                    
                    <?php if(!empty($form_error)){ echo form_error('end_date'); } ?>
                </div>
              </div> <!-- innerrow -->
               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Section<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
               <select name="section" id="section_id" class="pat-select1">

                  <option value="">Select</option>
                  <option <?php if($form_data['section'] == '1') { echo 'selected="selected"';} ?> value="1">OPD</option>
                   <option <?php if($form_data['section'] == '2') { echo 'selected="selected"';} ?> value="2">IPD</option>
                  <option <?php if($form_data['section'] == '3') { echo 'selected="selected"';} ?> value="3">Pathology</option>
                  <option <?php if($form_data['section'] == '4') { echo 'selected="selected"';} ?> value="4">Medicine</option>
                  <option <?php if($form_data['section'] == '5') { echo 'selected="selected"';} ?> value="5">Vaccination</option>
                  <option <?php if($form_data['section'] == '6') { echo 'selected="selected"';} ?> value="6">Dialysis</option>
                  <option <?php if($form_data['section'] == '7') { echo 'selected="selected"';} ?> value="7">OT</option>
                  <option <?php if($form_data['section'] == '8') { echo 'selected="selected"';} ?> value="8">Inventory</option>
                  <option <?php if($form_data['section'] == '9') { echo 'selected="selected"';} ?> value="9">Common</option>
                  
                 
                 
                </select> 
                    <?php if(!empty($form_error)){ echo form_error('section'); } ?>
                </div>
              </div> <!-- innerrow -->
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
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Add features successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Add features successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('admin_add_features/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_feature_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_add_feature_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_feature_modal_popup').modal('hide');
});

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->