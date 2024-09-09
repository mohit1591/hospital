<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="simulation" class="form-inline"> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Department<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                     <select multiple="" name="dept_id[]" style="height: 100px;">
                      <?php
                       if(!empty($dept_list))
                       {
                        foreach($dept_list as $dept)
                        {
                          $selected = '';
                          if(!empty($selected_dept) && in_array($dept->id,$selected_dept))
                          {
                            $selected = 'selected="selected"';
                          }
                          echo '<option '.$selected.' value="'.$dept->id.'">'.$dept->department.'</option>';
                        }
                       }
                      ?> 
                     </select>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Report Print<span class="star">*</span></label>
                </div>  
                <div class="col-md-8">
                     <input type="radio" name="report_print" value="1" <?php if(!empty($report_setting['report_print']) && $report_setting['report_print']=='1'){ echo 'checked="checked"'; } ?> /> After Complete Payment<br/>
                     <input type="radio" name="report_print" value="0" <?php if(empty($report_setting['report_print'])){ echo 'checked="checked"'; } ?> /> Normal
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Report Fill Lock<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                     <input type="radio" name="report_lock" value="1" <?php if(!empty($report_setting['report_lock']) && $report_setting['report_lock']=='1'){ echo 'checked="checked"'; } ?> /> After Verify/Complete<br/>
                     <input type="radio" name="report_lock" value="0" <?php if(empty($report_setting['report_lock'])){ echo 'checked="checked"'; } ?> /> Normal
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
 
$("#simulation").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
    
  var msg = 'Test report setting successfully updated.';  
  
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('test_report_verify/index/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_collection_modal_popup').modal('hide');
        flash_session_msg(msg);  
      } 
      else
      {
        $("#load_add_collection_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_collection_modal_popup').modal('hide');
});
 
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->