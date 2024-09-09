<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="disease" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">


          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Patient UH ID</label>
                </div>
                <div class="col-md-8">
                    <?php echo $balance_data['patient_code']; ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Patient Name</label>
                </div>
                <div class="col-md-8">
                    <?php echo $balance_data['patient_name']; ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Payment Mode<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <select name="pay_mode" id="pay_mode">
                      <option value="">Select Payment Mode</option>
                      <?php
                      if(!empty($payment_mode_list))
                      {
                        foreach($payment_mode_list as $payment_mode)
                        {
                          $selected = '';
                          if($form_data['pay_mode']==$payment_mode['id'])
                          {
                            $selected = 'selected="selected"';
                          }
                        ?>
                        <option <?php echo $selected; ?> value="<?php echo $payment_mode['id']; ?>"><?php echo $payment_mode['payment_mode']; ?></option>
                        <?php  
                        }
                      }
                      ?> 
                    </select>
                    <?php if(!empty($form_error)){ echo form_error('disease'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  


          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Amount<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="amount"  class="numeric inputFocus" value="<?php echo $form_data['amount']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  

  
				
     </div> <!-- 12 --> 

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
 
$("#disease").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  var path = 'edit/'+ids;
  var msg = 'Balance Clearance  successfully updated.';
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('balance_clearance_history/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_disease_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
        print_window_page('<?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/<?php echo $balance_data['id']; ?>/<?php echo $balance_data['patient_id']; ?>/<?php echo $balance_data['created_date']; ?>/<?php echo $balance_data['section_id']; ?>');
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
 
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->