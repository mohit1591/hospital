<div class="cp-modal">
<div class="modal-dialog modal-80">

  <div class="modal-content modal-top"> 
    <form  id="default_search_form" class="form-inline"> 
  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">    
           
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Patient Record</label>
                </div>
                <div class="col-xs-7">
                    <input type="radio" name="records[1]" value="0" <?php if(!isset($form_data[1]) || $form_data[1]!=1){ echo 'checked="checked"'; }  ?>> Current Date Wise 
                    <input type="radio" name="records[1]" value="1" <?php if(isset($form_data[1]) && $form_data[1]==1){ echo 'checked="checked"'; } ?>> All Records 
                </div>
              </div> <!-- row -->
              
                

            </div> <!-- modal-body -->
          
             
             
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Save</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Cancel</button> &nbsp;
        </div>
</form>     

<script>  
 
$("#default_search_form").on("submit", function(event) { 
  event.preventDefault();  
  $.ajax({
    url: "<?php echo base_url('default_search_setting/index'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_change_password_popup').modal('hide');
        flash_session_msg('Default setting successfully updated.'); 
      } 
      else
      {
        $("#load_change_password_popup").html(result);
      }       
    }
  });
}); 
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->