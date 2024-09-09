<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="website_setting" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <input type="hidden" name="var_title" <?php if(!empty($form_data['data_id'])){ ?> readonly <?php } ?> value="<?php echo $form_data['var_title']; ?>">
               <input type="hidden" name="var_name"  value="<?php echo $form_data['var_name']; ?>">

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Setting value</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="setting_value"  class="" value="<?php echo nl2br($form_data['setting_value']); ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('setting_value'); } ?>
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
 
$("#website_setting").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Website setting successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Website setting successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('website_setting/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_website_setting_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_website_setting();
        reload_table();
      } 
      else
      {
        $("#load_add_website_setting_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_website_setting_modal_popup').modal('hide');
});

function get_website_setting()
{
   $.ajax({url: "<?php echo base_url(); ?>website_setting/website_setting_dropdown/", 
    success: function(result)
    {
      $('#website_setting_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->