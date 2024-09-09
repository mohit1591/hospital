<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="update_quantity" class="form-inline">
   <input type="hidden" name="id" id="id" value="<?php echo $form_data['id']; ?>" />
    <input type="hidden" name="batch_no" id="batch_no" value="<?php echo $form_data['batch_no']; ?>" /> 
   
  
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 

      <div class="row m-b-5">
          <div class="col-md-4">
              <label> Quanity</label>
          </div>
          <div class="col-md-8">
              <input type="text" name="quantity" id="quantity"  value="">
              <?php if(!empty($form_error)){ echo form_error('quantity'); } ?>
              <br>Sample:Increase by 10 enter 10/decrease by 10 enter -10
          </div>
        </div> <!-- row -->  


   
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 

         <button type="submit"  class="btn-update" name="submit">Update</button>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script>  

$("#update_quantity").on("submit", function(event) {  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#id').val();
  if(ids!="" && !isNaN(ids))
  { 
      
        $.ajax({
                url: "<?php echo base_url('medicine_stock/change_quantity/'); ?>",
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                  if(result==1)
                  {
                    var msg = 'quantity successfully updated.';
                    $('#load_add_modal_popup').modal('hide');
                    flash_session_msg(msg); 
                    reload_table();   
                  } 
                  else
                  {
                    $("#load_add_modal_popup").html(result);
                  }       
                  //$('#overlay-loader-comission').hide();
                }
              });


  }
  

 


}); 

$("button[data-number=1]").click(function(){
    $('#load_add_modal_popup').modal('hide');
});
</script>  
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->