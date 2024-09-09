<?php
$users_data = $this->session->userdata('auth_users');
?>

<style>
.row_hide{display:none;}
</style>
<div class="modal-dialog">
     <!--<div class="overlay-loader">
          <img src="<?php //echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>-->
    <div class="modal-content"> 
          <form  id="pay_form" class="form-inline">
               <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['pid']; ?>" />
               <input type="hidden" name="section_id" id="section_id" value="<?php echo $form_data['section_id']; ?>" />
               <input type="hidden" name="parent_id" id="parent_id" value="<?php echo $form_data['parent_id']; ?>" />
               <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $form_data['branch_id']; ?>" />
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
                                     
                                        <input type="text" readonly="readonly" name="paid_date"  id="paid_date" value="<?php echo $form_data['paid_date']; ?>" class="datepicker"/>
                                        <?php if(!empty($form_error)){ echo form_error('paid_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Balance<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                     
                                        <input type="text"  class="price_float" name="balance" value="<?php echo $form_data['balance']; ?>"/>
                                        <?php if(!empty($form_error)){ echo form_error('balance'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row --> 

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Payment Mode <span class="star">*</span></strong>
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
                                  <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php if(isset($tot_values[2])) {  echo $tot_values[2]; }?>" name="field_id[]" />
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
                  url: "<?php echo base_url('balance_clearance/get_payment_mode_data')?>",
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
               var patient_id= $('#patient_id').val();
               var paid_date= $('#paid_date').val();
                var now = new Date(),
                now = now.getHours()+':'+now.getMinutes()+':'+now.getSeconds();
              // alert(paid_date);
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
                              get_balance_clearance_list(<?php echo $this->uri->segment('4'); ?>);
                              //print_bill(result,'<?php echo $this->uri->segment('5'); ?>');
                              
                              print_window_page('<?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/'+result+'/'+patient_id+'/'+paid_date+'/'+<?php echo $this->uri->segment('5'); ?>);
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

          $('#cheque_date').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
          });

           function print_bill(id,type)
           {
            alert();
              print_window_page('<?php echo base_url(); ?>balance_clearance/print_patient_balance_receipt/'+id+'/'+type);
            
           }

           
          </script>  
          <!-- Delete confirmation box -->  
          <!-- Delete confirmation end --> 
     </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
     <script type="text/javascript">
$(document).ready(function(){
<?php
if((empty($payment_mode)))
{
  
?>  

 
  $('#balance_clearance_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 

}

?>

});
</script>
<script>
$("button[data-number=4]").click(function(){
    $('#balance_clearance_count').modal('hide');
   /* $(this).hide();*/
});
</script>
     <div id="balance_clearance_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($payment_mode)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Payment Mode is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    </div>
    


