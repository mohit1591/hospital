<?php
$users_data = $this->session->userdata('auth_users');
?>
<script>
          function get_payment_mode(id)
                        {
                        
                    if(id=='2')
                    { 
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').removeClass('row_hide');
                         $("#transaction_no").val('');
                         $("#bank_name").val('');
                         $("#cheque_no").val('');   
                    }
                    else if(id=='4')
                    {
                         $('#tr_transaction_no').removeClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').addClass('row_hide');
                         $("#bank_name").val('');
                         $("#cheque_no").val(''); 
                         $("#card_no").val(''); 
                    }
                    else if(id=='3')
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').removeClass('row_hide');
                         $('#tr_cheque_no').removeClass('row_hide');
                         $('#tr_cheque_date').removeClass('row_hide');
                         $('#tr_card_no').addClass('row_hide'); 
                         $("#transaction_no").val(''); 
                         $("#card_no").val('');  
                    }
                    else
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
                         $('#tr_cheque_date').addClass('row_hide');
                         $('#tr_card_no').addClass('row_hide');
                         $("#transaction_no").val('');
                         $("#bank_name").val('');
                         $("#cheque_no").val(''); 
                         $("#card_no").val(''); 
                    }

              }
     get_payment_mode(<?php echo $form_data['payment_mode']; ?>);
</script>
<style>
.row_hide{display:none;}
</style>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="pay_form" class="form-inline">
               <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close p-t-0" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title;   ?></h4> 
               </div>
               <div class="modal-body">    
            
                              
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Paid Date<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text" readonly="readonly" name="paid_date" value="<?php echo $form_data['paid_date']; ?>" class="datepicker"/>
                                        <?php if(!empty($form_error)){ echo form_error('paid_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Balance<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text" id="balance_amt"  class="price_float" name="balance" value="<?php echo $form_data['balance']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('balance'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Payment mode <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7"> 
                                       <select  name="payment_mode" onChange="payment_function(this.value,'');">
                                          <?php foreach($payment_mode as $payment_mode) 
                                          {?>
                                          <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                                          <?php }?>

                                          </select>
                                       <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 


                               <div id="updated_payment_detail">
                                  <?php if(!empty($form_data['field_name']))
                                  { foreach ($form_data['field_name'] as $field_names) {
                                  $tot_values= explode('_',$field_names);

                                  ?>

                                  <div class="row m-b-5" id="branch"> 
                                  <div class="col-md-5">
                                  <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                                  </div>
                                  <div class="col-md-7"> 
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

                             
                       <input type="hidden" name="rem_balance" id="rem_balance"/>
                        <input type="hidden" name="expense_id" id="expense_id" value="<?php echo $form_data['expense_id'];?>" />
                        <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $form_data['payment_id'];?>" />
                         <input type="hidden" name="section_id" id="section_id" value="<?php echo $form_data['section_id'];?>" />
                         <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $form_data['parent_id'];?>" />
                         
                       

                   
                 </div> 
                  <!--  modal-body --> 
               <div class="modal-footer">
                    <input type="submit"  class="btn-save" name="submit" value="Pay" />
                    <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
               </div>
          </form>      
          <script>
               $('.tooltip-text').tooltip({
                    placement: 'right', 
                    container: 'body' 
               });
          </script>
          <script>


       function payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('vendor_payment/get_payment_mode_data')?>",
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
        
          function checkAlphaNumeric(e) {
               if ((e.keyCode >= 48 && e.keyCode <= 57) ||
                  (e.keyCode >= 65 && e.keyCode <= 90) ||
                  (e.keyCode >= 97 && e.keyCode <= 122)){
                    return true;
               }
               else{
                    return false;
               }
          }
        
          function isNumberKey(evt) {
               var charCode = (evt.which) ? evt.which : event.keyCode;
               if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
               } else {
                    return true;
               }      
          }
     
          $("#pay_form").on("submit", function(event) { 
               event.preventDefault(); 
               $('.overlay-loader').show();
               var ids = $('#data_id').val();
               var sub_branch_id = $('#sub_branch_id').val(); 
               var msg = 'payment successfully done.';
               var remain=parseFloat('<?php echo $form_data['balance']; ?>');
               var payamt=parseFloat($('#balance_amt').val());  
                $('#rem_balance').val((remain-payamt).toFixed(2));
               $.ajax({
                    url: "<?php echo current_url(); ?>",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(result) 
                    {
                         if(!isNaN(result))
                         {
                              flash_session_msg(msg);
                              $('#load_add_pay_now_modal_popup').modal('hide');
                               location.reload();
                              get_balance_clearance_list(<?php echo $this->uri->segment('4'); ?>);
                               print_window_page('<?php echo base_url(); ?>vendor_payment/print_patient_balance_receipt/'+result+'/'+<?php echo $this->uri->segment('4'); ?>);
                             
                         } 
                         else
                         {
                              $("#load_add_pay_now_modal_popup").html(result);
                         }
                         $('.overlay-loader').hide();   
                           
                    }
               });
          }); 
          
          $('.datepicker').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
          });
          $('.cheque_date').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
          });

           function print_bill(id,type)
           {
              print_window_page('<?php echo base_url(); ?>vendor_payment/print_patient_balance_receipt/'+id+'/'+type);
           }
          </script>  
     </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
    


