<div class="modal-dialog">
<div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="rate_form" class="form-inline">
  <input type="hidden" name="data_id" id="rate_id" value="<?php echo $form_data['data_id']; ?>" />
  
            <div class="modal-header">
                <button type="button" class="close p-t-0" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Rate Plan<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="title"  class="rp-s inputFocus" value="<?php echo $form_data['title']; ?>">
                    <select class="rp-s" style="opacity:0;"></select>
                    <?php if(!empty($form_error)){ echo form_error('title'); } ?>
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                <label> Patient Rate<span class="star">*</span> </label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="master_rate"  class="rp-s rate_plan" value="<?php echo $form_data['master_rate']; ?>">
                    <select name="master_type" class="rp-s"> 
                      <option value="0" <?php if($form_data['master_type']==0){ echo 'selected="selected"'; } ?>> Rs </option>
                      <option value="1" <?php if($form_data['master_type']==1){ echo 'selected="selected"'; } ?>> % </option>
                    </select>
                     <?php if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label> Branch Rate<span class="star">*</span> </label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="base_rate"  class="rp-s rate_plan"  value="<?php echo $form_data['base_rate']; ?>">
                     
                    <select name="base_type" class="rp-s"> 
                      <option value="0" <?php if($form_data['base_type']==0){ echo 'selected="selected"'; } ?>>Rs</option>
                      <option value="1" <?php if($form_data['base_type']==1){ echo 'selected="selected"'; } ?>>%</option>
                    </select>
                    <?php if(!empty($form_error)){ echo form_error('base_rate'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status<span class="star">*</span></label>
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
           <button type="button" data-number="1" class="btn-cancel">Close</button>
        </div>
</form>     

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 45 && charCode != 43 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 $("button[data-number=1]").click(function(){

    $('#load_add_rate_modal_popup').modal('hide'); 

});


$("#rate_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  var ids = $('#rate_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
      var msg = 'Rate successfully updated.';
  }
  else
  {
    var path = 'add/';
      var msg = 'Rate successfully created.';
  } 

  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url(); ?>rate/"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_rate_modal_popup').modal('hide');
         flash_session_msg(msg);
        get_rate();
        reload_table();
      } 
      else
      {
        $("#load_add_rate_modal_popup").html(result);
      }       
      $('.overlay-loader').hide();
    }
  });
}); 
function get_rate()
{
   $.ajax({url: "<?php echo base_url(); ?>rate/rate_dropdown/", 
    success: function(result)
    {
      $('#rate_plan_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->