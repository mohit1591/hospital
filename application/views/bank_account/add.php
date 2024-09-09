<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<div class="modal-dialog emp-add-add p-t-40">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="bank_account_type" class="form-inline">
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
                <label>Bank Name<span class="star">*</span></label>
                <?php  $get_bank_name= get_bank_name();?>
                </div>
                <div class="col-md-7">
                <select name="bank_name"><option value="">Select Bank Name</option>
                <?php foreach($get_bank_name as $bank_name){?>

                <option value="<?php echo $bank_name->id; ?>"  <?php if($bank_name->id==$form_data['bank_name']&& isset($form_data['bank_name'])){ echo 'selected';}?>><?php echo $bank_name->bank_name; ?></option>
               
                  <?php } ?>
                </select>

                <?php if(!empty($form_error)){ echo form_error('bank_name'); } ?>
                </div>
                </div> <!-- innerrow -->
                </div> <!-- 12 -->
          </div> <!-- row --> 
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label> A/C Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" class="alpha_numeric_space inputFocus" name="account_holder"  value="<?php echo $form_data['account_holder']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('account_holder'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
         

            <div class="row">
                <div class="col-md-12 m-b1">
                <div class="row">
                <div class="col-md-5">
                <label>A/C Number<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                <input type="text" name="account_no"  class="numeric" value="<?php echo $form_data['account_no']; ?>">

                <?php if(!empty($form_error)){ echo form_error('account_no'); } ?>
                </div>
                </div> <!-- innerrow -->
                </div> <!-- 12 -->
          </div> <!-- row --> 


        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-5">
              <label>A/c Type<span class="star">*</span></label>
              </div>
              <div class="col-md-7">
              <select name="type"><option value="">Select Account Type</option>
              <option value="1">Saving</option>
              <option value="2">Current</option>
              </select>

              <?php if(!empty($form_error)){ echo form_error('type'); } ?>
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row --> 

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-5">
              <label>IFSC Code<span class="star">*</span></label>
              </div>
              <div class="col-md-7">
              <input type="text" name="ifsc_code"  class="" value="<?php echo $form_data['ifsc_code']; ?>">

                <?php if(!empty($form_error)){ echo form_error('ifsc_code'); } ?>
              </div>
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-5">
              <label>MICR Code<span class="star">*</span></label>
              </div>
              <div class="col-md-7">
              <input type="text" name="micr_code"  class="" value="<?php echo $form_data['micr_code']; ?>">

                <?php if(!empty($form_error)){ echo form_error('micr_code'); } ?>
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
 
$("#bank_account_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Bank Account successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Bank Account successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('bank_account/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_bank_account_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_emp_type();
        reload_table();
      } 
      else
      {
        $("#load_add_bank_account_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_bank_account_modal_popup').modal('hide');
});

function get_emp_type()
{
   $.ajax({url: "<?php echo base_url(); ?>bank_account/bank_account_dropdown/", 
    success: function(result)
    {
      $('#emp_type_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->