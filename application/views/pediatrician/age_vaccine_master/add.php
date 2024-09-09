<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="age_vaccine" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-2">
                    <label>Title<span class="star">*</span></label>
                </div>
                <div class="col-md-5">
                <input type="text" name="title" class="inputFocus" placeholder="Title" value="<?php echo $form_data['title']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('title'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-2">
                    <label>Start Age<span class="star">*</span></label>
                </div>
                <div class="col-md-5">
                <input type="text" name="start_age" placeholder="Start Age" class="Number inputFocus formcontrol" value="<?php echo $form_data['start_age']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('start_age'); } ?>
                </div>
                <div class="col-md-5">
                 <select name="start_age_type" id="start_age_type">
               <option value="">Select Duration</option>
                  <option value="4" <?php if ($form_data['start_age_type'] == '4') { echo 'selected'; } ?>>Year</option>
                <option value="3" <?php if ($form_data['start_age_type'] == '3') { echo 'selected'; } ?>>Month</option>
                <option value="1" <?php if ($form_data['start_age_type'] == '1') { echo 'selected'; } ?>>Days</option>
                <option value="2" <?php if ($form_data['start_age_type'] == '2') { echo 'selected'; } ?>>Week</option>
                  
               
       </select>
                <?php if(!empty($form_error)){ echo form_error('start_age_type'); } ?>
                </div>


              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-2">
                    <label>End Age</label>
                </div>
                <div class="col-md-5">
                <input type="text" name="end_age" placeholder="End Age" class="Number inputFocus formcontrol" value="<?php echo $form_data['end_age']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('end_age'); } ?>
                </div>
                  <div class="col-md-5">
                  <select name="end_age_type" id="end_age_type">
                      <option value="">Select Duration</option>
                      <option value="4" <?php if ($form_data['end_age_type'] == '4') { echo 'selected'; } ?>>Year</option>
                      <option value="3" <?php if ($form_data['end_age_type'] == '3') { echo 'selected'; } ?>>Month</option>
                      <option value="1" <?php if ($form_data['end_age_type'] == '1') { echo 'selected'; } ?>>Days</option>
                      <option value="2" <?php if ($form_data['end_age_type'] == '2') { echo 'selected'; } ?>>Week</option>
                  </select>
                <?php if(!empty($form_error)){ echo form_error('end_age_type'); } ?>
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

$('.Number').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
    }
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#age_vaccine").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Age Vaccine Master successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Age Vaccine Master successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('pediatrician/age_vaccine_master/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_age_vaccine_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_add_age_vaccine_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_age_vaccine_modal_popup').modal('hide');
});
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->