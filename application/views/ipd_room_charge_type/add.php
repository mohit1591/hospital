<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="ipd_room_charge_type" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Room Charge Type<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="charge_type"  value="<?php echo $form_data['charge_type']; ?>" class="inputFocus">
                    <!-- alpha_space -->
                    <?php if(!empty($form_error)){ echo form_error('charge_type'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-5">
						<label>Status</label>
					</div>
					<div class="col-md-7">
						 <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
						 <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
					</div>
				</div>
				<!-- <div class="row">
					<div class="col-md-4">
						<label>Gender</label>
					</div>
					<div class="col-md-8">
						<input type="radio"  class="" name="gender" <?php //if($form_data['gender']==1){ echo 'checked="checked"'; } ?> id="gender" value="1" /> Male  
						<input type="radio"  class="" name="gender" <?php //if($form_data['gender']==0){ echo 'checked="checked"'; } ?> id="gender" value="0" /> Female  
                              <input type="radio"  class="" name="gender" <?php //if($form_data['gender']==2){ echo 'checked="checked"'; } ?> id="gender" value="2" /> Others    
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
 
$("#ipd_room_charge_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'IPD Room charge type successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'IPD Room charge type successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_room_charge_type/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ipd_room_charge_type_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_ipd_room_charge_type();
        reload_table();
      } 
      else
      {
        $("#load_add_ipd_room_charge_type_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ipd_room_charge_type_modal_popup').modal('hide');
});

function get_ipd_room_charge_type()
{
   $.ajax({url: "<?php echo base_url(); ?>ipd_room_charge_type/ipd_room_charge_type_dropdown/", 
    success: function(result)
    {
      $('#ipd_room_charge_type_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->