
<div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-50"> 
    <form  id="result_time_form" class="form-inline"> 
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id']; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
        <div class="modal-body">    
           <div class="row">
                
                <div class="col-sm-6">
                    <input type="text" class="form-control datepicker" name="prefix" value="<?php echo date('d-m-Y',strtotime($form_data['prefix'])); ?>">
                  </div>
                <div class="col-sm-6">
                    <input type="text" class="form-control datepicker_day" name="result_time" value="<?php echo $form_data['result_time']; ?>">
                </div> 
              </div> <!-- row -->
            </div> <!-- modal-body -->
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Save</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
        </div>
</form>     

<script>  
 
$("#result_time_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var booking_id  = $('#booking_id').val();
     var path = 'expect_result_time'+'/'+booking_id;
     var msg = "Report Time Successfully added.";
  $.ajax({
    url: "<?php echo base_url('test/'); ?>"+path,
    type: "post", 
    data: $('#result_time_form').serialize(),  
    success: function(result) {
      if(result==1)
      {
        $('#load_sample_modal_popup').modal('hide');
        flash_session_msg(msg); 
        window.location.href='<?php echo base_url("test") ?>';
      } 
      else
      {
        $("#load_sample_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
</script>   
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
  $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true
    });

  $('.datepicker_day').datetimepicker({
      format: 'LT'
  });
   
</script>