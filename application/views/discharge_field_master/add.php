<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="discharge_field_master" class="form-inline">
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
                    <label>Label<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="discharge_lable"  class="" value="<?php echo $form_data['discharge_lable']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('discharge_lable'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Short Code<span class="star">*</span></label><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Please insert short code like {abc} OR {xyz}</span></a></sup>
                </div>

                <div class="col-md-7">
                    <input type="text" name="discharge_short_code"  class="" value="<?php echo $form_data['discharge_short_code']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('discharge_short_code'); } ?>
                </div>
              </div> <!-- innerrow -->
              
            </div> <!-- 12 -->
          </div> <!-- row --> 

          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <label>Type</label><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>The TEXT BOX is a single line box. The TEXTAREA is a multiple line box.</span></a></sup>
                </div>
                <div class="col-md-7">
                     <input type="radio"  class="" name="type" <?php if($form_data['type']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Textbox  
                     <input type="radio"  class="" name="type" <?php if($form_data['type']==2){ echo 'checked="checked"'; } ?> id="login_status" value="2" /> Textarea   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Sort Order<span class="star">*</span></label><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A standard order is often called ascending (corresponding to the fact that the standard order of numbers is ascending, i.e. 1 to 9)</span></a></sup>
                </div>
                <div class="col-md-7">
                    <input type="text" name="sort_order"  class="" value="<?php echo $form_data['sort_order']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('sort_order'); } ?>
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
 
$("#discharge_field_master").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Discharge field successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Discharge field successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('discharge_field_master/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_discharge_field_master_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_discharge_field_master();
        reload_table();
      } 
      else
      {
        $("#load_add_discharge_field_master_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_discharge_field_master_modal_popup').modal('hide');
});

function get_discharge_field_master()
{
   $.ajax({url: "<?php echo base_url(); ?>discharge_field_master/discharge_field_master_dropdown/", 
    success: function(result)
    {
      $('#discharge_field_master_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->