<?php //print_r($form_data);die; ?>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="confirm_booking" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /><input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 

  <input type="hidden" name="attended_doctor" id="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>" />
  
  <input type="hidden" name="specialization_id" id="specialization_id" value="<?php echo $form_data['specialization_id']; ?>" />
  
   <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 
  <input type="hidden" name="consultant" id="consultant" value="<?php echo $form_data['consultant']; ?>" /> 
  
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close" ><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 
<input type="hidden" name="schedule_type" value="<?php echo $schedule_type; ?>">
        <?php //if($schedule_type=='0'){ ?>
             <div class="row m-b-5">
              <div class="col-md-4">
                  <label><b>OPD Date</b></label>
                   </div>
                    <div class="col-md-8">
                     <input type="text" name="booking_date" class="datepicker" value="<?php echo $form_data['booking_date']; ?>" />
                   </div>
              <?php if(!empty($form_error)){ echo form_error('booking_date'); } ?>
            </div>
            <div class="row m-b-5">
              <div class="col-md-4">
                  <label><b>Booking Time</b></label>
                  </div>
                    <div class="col-md-8"> 
                    <input type="text" name="booking_time" class="datepicker3" value="<?php echo $form_data['booking_time']; ?>" />
                 </div>
              <?php if(!empty($form_error)){ echo form_error('booking_time'); } ?>
            </div>
            <?php //} ?>
            <div class="row m-b-5">
              <div class="col-md-4">
                  <label>Payment Mode</label>
              </div>
              <div class="col-md-8">
                <select  name="payment_mode" onChange="payment_function(this.value,'');">
                <?php foreach($payment_mode as $payment_mode) 
                {?>
                <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                <?php }?>

                </select>

              </div>
              <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
            </div> <!-- row-->


            <div id="updated_payment_detail">
            <?php if(!empty($form_data['field_name']))
            { foreach ($form_data['field_name'] as $field_names) {
            $tot_values= explode('_',$field_names);

            ?>

            <div class="row m-b-5" id="branch"> 
                <div class="col-md-4">
                <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                </div>
                <div class="col-md-8"> 
                <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                <?php 
                if(empty($tot_values[0]))
                {
                if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                }
                ?>
                </div>
            </div>
            <?php } }?>

            </div>

            <div id="payment_detail">


            </div>
           
          
        <div class="row m-b-5">
          <div class="col-md-4">
              <label>Total Amount</label>
          </div>
          <div class="col-md-8">
              <input type="text" name="total_amount" id="total_amount" onblur="payment_calc(this)" value="<?php echo $form_data['total_amount']; ?>"><?php if(!empty($form_error)){ echo form_error('total_amount'); } ?>
          </div>
        </div> <!-- row -->  


     <?php 

$discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
if($discount_val_setting==1)
{
?>   
        <div class="row m-b-5">
            <div class="col-md-4">
          <label>Discount</label>
          </div>
          <div class="col-md-8">
            <input type="text" name="discount" onkeyup="check_paid_amount();discount_vals();" id="discount" value="<?php echo $form_data['discount']; ?>">
          </div>
        </div> <!-- row -->  
<?php  } else{ ?>
<input type="hidden" name="discount" value="">
<?php } ?>

        <div class="row m-b-5">
          <div class="col-md-4">
            <label>Net Amount</label>
          </div>
          <div class="col-md-8">
            <input type="text" readonly="" name="net_amount" id="net_amount" value="<?php echo $form_data['net_amount']; ?>"><?php if(!empty($form_error)){ echo form_error('net_amount'); } ?>
          </div>
        </div> <!-- row -->  


        
        <div class="row m-b-5">
          <div class="col-md-4">
            <label>Paid Amount</label>
          </div>
          <div class="col-md-8">
            <input type="text" name="paid_amount" id="paid_amount" value="<?php echo $form_data['paid_amount']; ?>" onkeyup="check_paid_amount();"> <?php if(!empty($form_error)){ echo form_error('paid_amount'); } ?>
          </div>
        </div> <!-- row -->  


         
        <div class="row" style="display: none;">
          <div class="col-md-4">
            <label>Balance</label>
          </div>
          <div class="col-md-8">
            <input type="text" name="balance" id="balance" value="<?php echo $form_data['balance']; ?>">
            <?php if(!empty($form_error)){ echo form_error('balance'); } ?>
          </div>
        </div> <!-- row -->  


          
      </div> <!-- modal-body --> 

      <div class="modal-footer">
          <?php  if($schedule_type=='1'){ ?> 
      <input type="hidden" name="booking_date" class="datepicker" value="< ?php echo $form_data['booking_date']; ?>" /> <?php } ?>
         <button type="submit"  class="btn-update" name="submit">Save</button>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script>  
function check_paid_amount()
  {
    var consultants_charge = $('#consultants_charge').val();
    var kit_amount = $('#kit_amount').val();
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    
    if(parseInt(discount)>parseInt(total_amount))
    {
      alert('Discount amount can not be greater than total amount');
      $('#discount').val('0.00');
      return false;
    }
    if(parseInt(paid_amount)>parseInt(net_amount))
    {
      alert('Paid amount can not be greater than total amount');
      $('#paid_amount').val(net_amount);
      return false;
    }
  }

       function payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('appointment/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#payment_detail').html(msg);
                     }
                });


           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           });  

      }
      $(document).ready(function(){
           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           }); 
      }); 


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

function discount_vals()
{
   var timerA = setInterval(function(){  
                                        payment_calc();
                                        clearInterval(timerA); 
                                      }, 1600);
}

function payment_calc(obj)
{
    
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    var timerB = setInterval(function(){
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>appointment/calculate_payment/", 
            dataType: "json",
            data: 'total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance,
            success: function(result)
            {
               $('#total_amount').val(result.total_amount); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.paid_amount); 
               $('#balance').val(result.balance); 
               
            } 
          });
    clearInterval(timerB); 
                                      }, 600);
  }


 
$("#confirm_booking").on("submit", function(event) {  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
      
        $.ajax({
                url: "<?php echo base_url('appointment/confirm_booking/'); ?>"+ids,
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                  if(result==1)
                  {
                    var msg = 'Paymeny successfully updated.';
                    $('#load_add_modal_popup').modal('hide');
                    flash_session_msg(msg); 
                    window.location ="<?php echo base_url('appointment/?status=print'); ?>";
                  } 
                  else
                  {
                    $("#load_add_modal_popup").html(result);
                  }       
                  
                }
              });


  }
 
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_modal_popup').modal('hide');
});

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });

   $('#cheque_date').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });

  $('.datepicker3').datetimepicker({
      format: 'LT'
  });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->