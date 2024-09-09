<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="disease" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
  <input type="hidden" name="lead_id" id="lead_id" value="<?php echo $form_data['lead_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Call Date<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" readonly=""  class=" datepicker" name="call_date" value="<?php echo $form_data['call_date']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('call_date'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->

            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Call Time<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" readonly=""  class="datepicker3 m_input_default" name="call_time" value="<?php echo $form_data['call_time']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('call_time'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->


            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Follow-Up Date<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" readonly=""  class=" datepicker" name="followup_date" value="<?php echo $form_data['followup_date']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('followup_date'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->


            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Follow-Up Time<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" readonly=""  class="datepicker3 m_input_default" name="followup_time" value="<?php echo $form_data['followup_time']; ?>">
                    <?php if(!empty($form_error)){ echo form_error('followup_time'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->


            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Status<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <select class="" name="call_status">
                        <option value="">Select Status</option>
                        <?php
                        if(!empty($call_status))
                        {
                            foreach($call_status as $status)
                            {
                                $status_select = '';
                                if($form_data['call_status']==$status['id'])
                                {
                                    $status_select = 'selected="selected"';
                                }
                                echo '<option '.$status_select.' value="'.$status['id'].'">'.$status['call_status'].'</option>';
                            }
                        }
                        ?>
                    </select>
                    <?php if(!empty($form_error)){ echo form_error('call_status'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->

            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Remarks<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <textarea class="" name="remark" ><?php echo $form_data['remark']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('remark'); } ?>
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
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
    });   

/*$('.datepicker3').datetimepicker({
     format: 'LT'
  });

$('.datepicker3').datetimepicker();*/

$('.datepicker3').timepicker({

    });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#disease").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#lead_id').val(); 
    var msg = 'Follow-Up successfully created.';
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('crm/leads/followup/'); ?>"+ids,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_disease_modal_popup').modal('hide');
        flash_session_msg(msg); 
        reload_table();
      } 
      else
      {
        $("#load_add_disease_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_disease_modal_popup').modal('hide');
});

function get_disease()
{
   $.ajax({url: "<?php echo base_url(); ?>call_status/disease_dropdown/", 
    success: function(result)
    {
      $('#disease_id').html(result); 
    } 
  });
}


</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->