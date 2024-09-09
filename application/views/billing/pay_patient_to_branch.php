<?php
$users_data = $this->session->userdata('auth_users');
?>
<script>
          function get_payment_mode(id)
                        {
                        
                    if(id=='3')
                    { 
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
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
                         $('#tr_card_no').addClass('row_hide');
                         $("#bank_name").val('');
                         $("#cheque_no").val(''); 
                         $("#card_no").val(''); 
                    }
                    else if(id=='2')
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').removeClass('row_hide');
                         $('#tr_cheque_no').removeClass('row_hide');
                         $('#tr_card_no').addClass('row_hide'); 
                         $("#transaction_no").val(''); 
                         $("#card_no").val('');  
                    }
                    else
                    {
                         $('#tr_transaction_no').addClass('row_hide');
                         $('#tr_bank_name').addClass('row_hide');
                         $('#tr_cheque_no').addClass('row_hide');
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
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">    
            
                              
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Balance<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text"  name="balance" value="<?php echo $form_data['balance']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('balance'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Payment mode <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7"> 
                                        <select name="payment_mode" id="payment_mode"  onchange="get_payment_mode(this.value)">
                                           <option value="1" <?php if($form_data['payment_mode']==1){ echo 'selected="selected"'; } ?>>Cash</option>
                                           <option value="3" <?php if($form_data['payment_mode']==3){ echo 'selected="selected"'; } ?> >Card</option> 
                                           <option value="4" <?php if($form_data['payment_mode']==4){ echo 'selected="selected"'; } ?>>Neft</option>
                                           <option value="2" <?php if($form_data['payment_mode']==2){ echo 'selected="selected"'; } ?>>Cheque</option>
                                       </select>
                                       <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div id="tr_bank_name" class="row m-b-5  <?php if($form_data['payment_mode']!=2){ echo 'row_hide '; } ?>" >
                                   <div class="col-md-5">
                                        <strong>Bank Name <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7"> 
                                        <input type="text" name="bank_name" id="bank_name" value="<?php echo $form_data['bank_name']; ?>">
                                        <?php if(!empty($form_error)){ echo form_error('bank_name'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5 <?php if($form_data['payment_mode']!=4){ echo 'row_hide'; } ?>" id="tr_transaction_no">
                                   <div class="col-md-5 ">
                                        <strong>Transaction No. <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7"> 
                                        <input type="text"  name="transection_no" value="<?php echo $form_data['transection_no']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('transection_no'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5 <?php if($form_data['payment_mode']!=3){ echo 'row_hide'; } ?>" id="tr_card_no">
                                   <div class="col-md-5 ">
                                        <strong>Card No. <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text"  name="card_no" value="<?php echo $form_data['card_no']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('card_no'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5 <?php if($form_data['payment_mode']!=2){ echo 'row_hide'; } ?>" id="tr_cheque_no">
                                   <div class="col-md-5 ">
                                        <strong>Cheque No. <span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text"  name="cheque_no" value="<?php echo $form_data['cheque_no']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('cheque_no'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                   
                  
                  <!--  modal-body --> 
               <div class="modal-footer">
                    <input type="submit"  class="btn-save pay" name="submit" value="Pay" />
                    <button type="button" class="btn-cancel" data-dismiss="modal">Cancel</button>
               </div>
          </form>      
          <script>
               $('.tooltip-text').tooltip({
                    placement: 'right', 
                    container: 'body' 
               });
          </script>
          <script> 
        
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
               var path = 'pay_now/'+ids+'/'+sub_branch_id;
               var msg = 'payment successfully done.';
          
               //alert('ddd');return false;
               $.ajax({
                    url: "<?php echo base_url(); ?>billing/"+path,
                    type: "post",
                    data: $(this).serialize(),
                    success: function(result) 
                    {
                         if(!isNaN(result))
                         {
                              flash_session_msg(msg);
                              $('#load_add_pay_now_modal_popup').modal('hide');
                              get_balance_clearance_list(sub_branch_id);
                              print_bill(result);
                         } 
                         else
                         {
                              $("#load_add_pay_now_modal_popup").html(result);
                         }
                         $('.overlay-loader').hide();       
                    }
               });
          }); 

           function print_bill(id)
           {

              var printWindow = openPrintWindow('<?php echo base_url(); ?>billing/print_patient_balance_receipt/'+id, 'windowTitle', 'width=820,height=600');
               var printAndClose = function() {
                  if (printWindow.document.readyState == 'complete') {
                      clearInterval(sched);
                      printWindow.print();
                      printWindow.close();
                  }
              }
              var sched = setInterval(printAndClose, 200);
           }

            function openPrintWindow(url, name, specs) 
            {
                 var printWindow =  window.open(url, name, specs);
                   var printAndClose = function() {
                       if (printWindow.document.readyState == 'complete') {
                           clearInterval(sched);
                           printWindow.print();
                           printWindow.close();
                       }
                   }
                   var sched = setInterval(printAndClose, 200);
             }
          </script>  
          <!-- Delete confirmation box -->  
          <!-- Delete confirmation end --> 
     </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
    


