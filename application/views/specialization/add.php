<div class="modal-dialog add-specialization">
<div class="overlay-loader" id="overlay-loader-child">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="specialization_form" class="form-inline">
  <input type="hidden" name="data_id" id="specialization_id" value="<?php echo $form_data['data_id']; ?>" />
  
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>

      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Specialization<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="specialization" class="inputFocus" value="<?php echo $form_data['specialization']; ?>">                    
                    <?php if(!empty($form_error)){ echo form_error('specialization'); } ?>
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
           <button type="button" class="btn-cancel" data-number="1">Close</button>
        </div>
</form>     

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#specialization_form").on("submit", function(event) { 
  event.preventDefault();   
  $('#overlay-loader-child').show();
  var ids = $('#specialization_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Specialization successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Specialization successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('specialization/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_specialization_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_specilization();
        reload_table();
      } 
      else
      {
        $("#load_add_specialization_modal_popup").html(result);
      }       
      $('#overlay-loader-child').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result);
      $('.specilization_id').html(result); 
    } 
  });
}

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->