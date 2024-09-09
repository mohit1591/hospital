<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="confirm_booking" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
   <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 
  
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 

            <div class="row m-b-5">
              <div class="col-md-4">
                  <label><b>Day Care Date</b></label>
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

            <div class="row m-b-5">
              <div class="col-md-4">
                  <label>Payment Mode</label>
              </div>
              <div class="col-md-8">
                <select name="payment_mode" id="payment_mode"  onchange="get_payment_mode(this.value)">
                <?php 
                        $selected_cash = '';
                        $selected_cheque='';
                        $selected_card ='';
                        $selected_neft ='';
                     
                       if($form_data['payment_mode']=='cash'){
                            $selected_cash = 'selected="selected"';
                        }elseif($form_data['payment_mode']=='cheque'){
                             $selected_cheque = 'selected="selected"';
                        }elseif($form_data['payment_mode']=='card'){
                               $selected_card = 'selected="selected"';
                        }elseif($form_data['payment_mode']=='neft'){
                               $selected_neft = 'selected="selected"';
                        }
                     ?>
                  
                   <option value="cash" <?php echo $selected_cash; ?>>Cash</option>
                   <option value="cheque" <?php echo $selected_cheque; ?>>Cheque</option>
                   <option value="card" <?php echo $selected_card; ?>>Card</option>
                   <option value="neft" <?php echo $selected_neft; ?>> Neft</option>

              </select> 
              </div>
              <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
            </div> <!-- row-->



        <div class="row m-b-5" id="card" style="display:none;">
          <div class="col-md-4">
              <label>Transaction No</label>
          </div>

          <div class="col-md-8">
            <input type="text"  onkeypress="return isNumberKey(event);" name="transaction_no" id="transactionno" value="<?php echo $form_data['transaction_no']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('transaction_no'); } ?>
          </div> <!-- 8 -->
        </div> <!-- row -->


        <div class="row m-b-5" id="cheque" style="display:none;">
          <div class="col-md-4">
              <label>Cheque Date</label>
          </div>
          <div class="col-md-8">
                 <input type="text" readonly="readonly" contenteditable="false" class="datepicker" name="cheque_date" id="chequedate" value="<?php echo $form_data['cheque_date']; ?>" />
              <span href="javascript:void(0)"  data-toggle="tooltip" >
              <?php if(!empty($form_error)){ echo form_error('cheque_date'); } ?>
          </div> <!-- 8 -->
        </div> <!-- row -->

      
        <div class="row m-b-5" id="branch" style="display:none;" >
          <div class="col-md-4">
            <label>Branch Name</label>
          </div><!-- 4 -->
          <div class="col-md-8">
            <input type="text" name="branch_name" id="branchname" value="<?php echo $form_data['branch_name']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('branch_name'); } ?>
          </div> <!-- 8 -->
        </div> <!-- row -->
           
          
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
            <input type="text" name="discount" onkeyup="discount_vals();" id="discount" value="<?php echo $form_data['discount']; ?>">
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
            <input type="text" name="paid_amount" id="paid_amount" value="<?php echo $form_data['paid_amount']; ?>"> <?php if(!empty($form_error)){ echo form_error('paid_amount'); } ?>
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

         <button type="submit"  class="btn-update" name="submit">Save</button>
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

function discount_vals()
{
   var timerA = setInterval(function(){  
                                        payment_calc();
                                        clearInterval(timerA); 
                                      }, 1600);
}

function payment_calc()
{
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>day_care/calculate_payment/", 
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
  }


 
$("#confirm_booking").on("submit", function(event) {  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
      
        $.ajax({
                url: "<?php echo base_url('day_care/confirm_booking/'); ?>"+ids,
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                  if(result==1)
                  {
                    var msg = 'Paymeny successfully updated.';
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


$(document).ready(function()
{
        var id = $("#payment_mode").val();
        if(id){
           get_payment_mode(id); 
        }
 })

    function get_payment_mode(id)
    {
              
          if(id=='cheque'){
                document.getElementById("cheque").style.display="block";
                document.getElementById("card").style.display="block";
                document.getElementById("branch").style.display="block";
                
             
          }else if(id=='card' || id=='neft'){
             
              document.getElementById("cheque").style.display="none";
              document.getElementById("card").style.display="block";
             document.getElementById("branch").style.display="none";
              $("#branchname").val('');
              $("#chequedate").val('');
             
          
          }else{
              document.getElementById("cheque").style.display="none";
              document.getElementById("card").style.display="none";
              document.getElementById("branch").style.display="none";
              $("#branchname").val('');
              $("#chequedate").val('');
              $("#transactionno").val('');
             
          }
    }

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }

    $('.datepicker').datepicker({
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