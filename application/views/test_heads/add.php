<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="test_heads" class="form-inline">
  <input type="hidden" name="data_id" id="test_heads_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Department</label>
                </div>
                <div class="col-md-8">
                    <select name="dept_id">
                        <option value="">Select Department</option>
                              <?php
                               if(!empty($dept_list))
                               {
                                  foreach($dept_list as $dept)
                                  {
                                      $dept_select = "";
                                      if($dept->id==$form_data['dept_id'])
                                      {
                                          $dept_select = "selected='selected'";
                                      }
                                      echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                  }
                               }
                              ?>
                      </select>
                    
                    <?php if(!empty($form_error)){ echo form_error('dept_id'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Test head</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="test_heads"  value="<?php echo $form_data['test_heads']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('test_heads'); } ?>
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
 
$("#test_heads").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#test_heads_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Test Head successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Test Head successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('test_heads/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        get_test_heads();
        $('#load_add_test_heads_modal_popup').modal('hide');
        flash_session_msg(msg);
        
        reload_table();
      } 
      else
      {
        $("#load_add_test_heads_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_test_heads_modal_popup').modal('hide');
});

function get_test_heads()
    {
       var dept_id = '<?php echo $this->session->userdata('test_dept_id'); ?>';  
       if(dept_id!="" && dept_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>test_heads/test_heads_dropdown/"+dept_id, 
              success: function(result)
              {
                $('#test_heads_id').html(result); 
              } 
            });
       } 
    }
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->