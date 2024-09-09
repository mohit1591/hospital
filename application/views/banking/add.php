<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<div class="modal-dialog emp-add-add p-t-40">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="banking_type" class="form-inline">
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
                <label>Account<span class="star">*</span></label>
                <?php  $get_bank_name= get_bank_namewith_account();
               // print_r( $get_bank_name);
                ?>
                </div>
                <div class="col-md-7">
                <select name="account_id"><option value="">Select Account</option>
               <?php foreach($get_bank_name as $bank_name) {?>
                <option value="<?php echo $bank_name->id; ?>" <?php if($bank_name->id==$form_data['account_id']){ echo 'selected';}?>><?php echo $bank_name->bank_name; ?>_<?php echo $bank_name->account_no; ?></option>
              <?php }?>
                </select>

                <?php if(!empty($form_error)){ echo form_error('account_id'); } ?>
                </div>
                </div> <!-- innerrow -->
                </div> <!-- 12 -->
          </div> <!-- row -->  
           <div class="row">
                <div class="col-md-12 m-b1">
                <div class="row">
                <div class="col-md-5">
                <label>Deposite Date<span class="star">*</span></label>
           
                </div>
                <div class="col-md-7">
                <input type="text" name="deposite_date"  class="datepicker" value="<?php echo $form_data['deposite_date']; ?>">

                <?php if(!empty($form_error)){ echo form_error('deposite_date'); } ?>

                
                </div>
                </div> <!-- innerrow -->
                </div> <!-- 12 -->
          </div> <!-- row --> 

            <div class="row">
                <div class="col-md-12 m-b1">
                <div class="row">
                <div class="col-md-5">
                <label>Amount<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                <input type="text" name="amount"  class="price_float inputFocus" value="<?php echo $form_data['amount']; ?>">

                <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
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
 
$("#banking_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Banking successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Banking successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('banking/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_banking_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_emp_type();
        reload_table();
      } 
      else
      {
        $("#load_add_banking_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_banking_modal_popup').modal('hide');
});

function get_emp_type()
{
   $.ajax({url: "<?php echo base_url(); ?>banking/banking_dropdown/", 
    success: function(result)
    {
      $('#emp_type_id').html(result); 
    } 
  });
}

 $(document).ready(function(){
  $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 

 });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->