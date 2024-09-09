<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);die;
?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="add_all_medicine_data" class="form-inline">
      <input type="hidden" name="data_id" id="type_id" value="<?php //echo $form_data['data_id'];?>" /> 
    <input type="hidden" name="age_id" id="age_id" value="<?php //echo $form_data['age_id'];?>" /> 
    

    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id'];?>" /> 
    <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id'];?>"/>

    <input type="hidden" name="data_id" id="type_id" value="<?php echo $edit_id; ?>" /> 
      
      <div class="modal-header">
      <button type="button" class="close close_popup" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      <h4><?php echo $page_title; ?></h4> 
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
            <div class="col-md-6">
            <div class="row">
            <div class="col-md-4">
              <label>Vaccination Date Time</label>
            </div>

            <div class="col-xs-8">
              <input type="text" name="vaccination_date_time" class="datepicker date" data-date-format="dd-mm-yyyy HH:ii" value="<?php if(strtotime($form_data['vaccination_date_time'])>86400 && $form_data['vaccination_date_time']!='' && $form_data['vaccination_date_time']!='0000-00-00:00:00:00' && $form_data['vaccination_date_time']!='1970-01-01:05:30:00'){ echo $form_data['vaccination_date_time']; } ?>" /> 

              <?php if(!empty($form_error)){ echo form_error('vaccination_date_time'); } ?>
            </div>
              
            </div>
            </div>

            <div class="col-md-6">


            <div class="row">
            <div class="col-md-4">
              <label>Attended Doctor</label>
              </div>
              <div class="col-md-8">
              <select name="attended_doctor">
              <?php foreach($attended_doctor as $attended)
              {?>
              <option value="<?php echo $attended->id; ?>" <?php if(isset($form_data['attended_doctor']) && $form_data['attended_doctor']==$attended->id){echo 'selected';} ?>><?php echo $attended->doctor_name; ?></option>
              <?php } ?></select>

              </div>
            </div> <!-- innerrow -->


            </div>
            
            </div> <!-- innerrow -->
          </div> <!-- 12 -->
        </div> <!-- row -->  


      



      <div class="row">
      <div class="col-md-12">
      <div class="sale_medicine_tbl_box" id="medicine_table">
      <?php echo $medicine_list; ?><!-- left -->
      </div> <!-- sale_medicine_tbl_box -->

      <div class="sale_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <b>Mode of Payment</b>
                    <select  name="payment_mode" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>"><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?> 
                         
                    </select>
                </div>
                 <div id="updated_payment_detail">
               <!--   <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {

                      $tot_values= explode('_',$field_names);

                    ?>

                   <div class="purchase_medicine_mod_of_payment"><label><?php echo $tot_values[1];?><span class="star">*</span></label>
                   <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" onkeypress="payment_calc_all();">
                   <input type="hidden" name="field_id[]" value="<?php echo $tot_values[2];?>" onkeypress="payment_calc_all();">
                  <div class="f_right">
                     <?php 
                        if(empty($tot_values[0]))
                        {
                            if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                        }
                      ?>
                     </div>
                   </div>
                  
                   <?php } }?> -->
                     
                   </div>
                  
                <div id="payment_detail">
                    

                </div>
          
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" name="total_amount" id="total_amount" onkeyup="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
                </div>

            <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
            if(isset($discount_vals) && $discount_vals==1){?>

            <div class="sale_medicine_mod_of_payment">
                <label>Discount</label>
                <div class="grp m7">
                <input class="input-tiny m8 price_float" name="discount_percent" type="text" value="<?php echo $form_data['discount_percent'];?>" id="discount_all" onKeyUp="payment_calc_all();" placeholder="%">
                <input class="m9" type="text" name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" readonly>
                </div>
            </div>
            <?php } else{?>
           <div class="sale_medicine_mod_of_payment">
                <label></label>
                <div class="grp m7">
                <input class="input-tiny m8 price_float" name="discount_percent" type="hidden" value="0" id="discount_all" onKeyUp="payment_calc_all();" placeholder="%">
                <input class="m9" type="hidden" name="discount_amount"  id="discount_amount" value="0" readonly>
                </div>
            </div> 
                <?php }?>
                
            
            <div class="sale_medicine_mod_of_payment">
                     <label>CGST(Rs.)</label>
                    
                        <input class="m9" type="text" name="cgst_amount"  id="cgst_amount"  value="<?php echo $form_data['cgst_amount'];?>" >
                   
                </div>
                  <div class="sale_medicine_mod_of_payment">
                     <label>SGST(Rs.)</label>
                   
                        <input class="m9" type="text" name="sgst_amount"  id="sgst_amount"  value="<?php echo $form_data['sgst_amount'];?>" >
                   
                </div>
                  <div class="sale_medicine_mod_of_payment">
                     <label>IGST(Rs.)</label>
                     <input class="m9" type="text" name="igst_amount"  id="igst_amount"  value="<?php echo $form_data['igst_amount'];?>" >
                   
                </div>

               <!-- <div class="sale_medicine_mod_of_payment">
                    <label>Net Payable</label>
                    <input type="text" name="" value="0">
                </div>-->

                <div class="sale_medicine_mod_of_payment">
                     <label>Net Amount</label>
                    <input type="text" name="net_amount"  id="net_amount" onKeyUp="payment_calc_all();" readonly value="<?php echo $form_data['net_amount'];?>">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Pay Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount" <?php //if(isset($form_data['ipd_id']) && $form_data['ipd_id']!='' && !empty($form_data['ipd_id'])) { echo 'readonly'; }?>  id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_vals(1);">
                     <div class="f_right"><?php //if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                    <?php  
                        $balance=$form_data['net_amount']-$form_data['pay_amount'];
                    ?>
                     <input type="text" name="balance_due"  id="balance_due" value="<?php if(!empty($balance) && $balance>0){ echo $balance; }else{ echo '0.00' ;} ?>" class="price_float" readonly >
                   
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <!-- <div class="right">
            <button class="btn-save" type="submit" name="remove_levels" onClick=" validateForm();" ><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
            <a href="<?php echo base_url('sales_vaccination');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
        </div> --> 
    </div> <!-- sale_medicine_bottom -->

                </div> <!-- 8 -->
              </div> <!-- Row -->
               
           
              </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" id="allot_to_branch" name="submit">Save</button>
               <button type="button" class="btn-cancel close_popup" data-dismiss="modal" data-number="2" >Close</button>
            </div>
    </form>     

<script>   

  $(".datepicker").datetimepicker({
      format: "dd-mm-yyyy HH:ii P",
      showMeridian: true,
      autoclose: true,
      todayBtn: true
  }).datetimepicker("setDate", new Date());

$(document).ready(function()
{
 payment_calc_all();
});
$("#add_all_medicine_data").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
   if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit_vaccine_pres/'+ids;
    var msg = 'Vaccination prescription successfully updated.';
  }
  else
  {
   
    var path = 'add_vaccine_pres/';
    var msg = 'Vaccination prescription successfully created.';
  }
   $.ajax({
    url: "<?php echo base_url('pediatrician/pediatrician_prescription/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {

      if(result==1)
      {
       
        $('#load_add_pediatrician_modal_popup').modal('hide');
        flash_session_msg(msg);
         print_data();
         
      } 
      else
      {
        $("#load_add_pediatrician_modal_popup").html(result);
      }        
      $('#overlay-loader').hide();
    }
  });
}); 
 function payment_cal_perrow(ids){
    var purchase_rate = $('#purchase_rate_mrp'+ids).val();
    var mrp = $('#mrp_'+ids).val();
    var qty = $('#qty_'+ids).val();
    var age_id = $('#age_id_'+ids).val();
    var hsn_no = $('#hsn_no_'+ids).val();
    var vaccine_id= $('#vaccine_id_'+ids).val();
    var mbid= $('#mbid_'+ids).val();

    var expiry_date= $('#expiry_date_'+ids).val();
    var bar_code= $('#bar_code_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var batch_no= $('#batch_no_'+ids).val();
    var vat= $('#vat_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    var conversion= $('#conversion_'+ids).val();
     //alert(qty);
   
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/payment_cal_perrow/", 
            dataType: "json",
            data: 'mbid='+mbid+'&purchase_rate='+purchase_rate+'&qty='+qty+'&vaccine_id='+vaccine_id+'&expiry_date='+expiry_date+'&igst='+igst+'&cgst='+cgst+'&sgst='+sgst+'&discount='+discount+'&manuf_date='+manuf_date+'&batch_no='+batch_no+'&conversion='+conversion+'&mrp='+mrp+'&hsn_no='+hsn_no+'&bar_code='+bar_code+'&age_id='+age_id,
            success: function(result)
            {
               $('#total_amount_'+ids).val(result.total_amount); 
               payment_calc_all();
            } 
          });
 }

   function payment_calc_all(pay)
    {
        var data_id= '';
        var discount = $('#discount_all').val();
        var vat = $('#vat_percent').val(); 
        var net_amount = $('#net_amount').val();
        var pay_amount= $('#pay_amount').val();
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/payment_calc_all/", 
      dataType: "json",
       data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
            $('#discount_amount').val(result.discount_amount);
            $('#total_amount').val(result.total_amount);
            $('#net_amount').val(result.net_amount);
            $('#pay_amount').val(result.pay_amount);
            $('#cgst_amount').val(result.cgst_amount);
            $('#igst_amount').val(result.igst_amount);
            $('#sgst_amount').val(result.sgst_amount);
            // $('#vat_amount').val(result.vat_amount);
            $('#discount_all').val(result.discount);
            //$('#vat_percent').val(result.vat);
            $('#balance_due').val(result.balance_due);
      } 
    });
    }
  
 function payemt_vals(pay)
  {
     var timerA = setInterval(function(){  
          payment_calc_all(pay);
          clearInterval(timerA); 
        }, 80);
  }

   function validation_bar_code(id){

    $('#unit1_error_'+id).html('');
    //var val=  $('#batch_no_'+id).val();
   // var unit2= $('#qty_'+id).val();
    var mbid =$('#medicine_id_'+id).val();
    var bar_code =$('#bar_code_'+id).val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>sales_vaccination/check_bar_code/", 
      dataType: "json",
       data: 'mbid='+mbid+'&bar_code='+bar_code,
      success: function(result)
      {
        //alert(result);
         if(result==1){
            $('#barcode_error_'+id).html('This Barcode already in used');
         }else{
            $('#barcode_error_'+id).html('');
         }

      } 
    });
  }

 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('pediatrician/pediatrician_prescription/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
   }

 function validation_check(unit,id)
 {
    $('#unit1_error_'+id).html('');
    var val=  $('#batch_no_'+id).val();
    var unit2= $('#qty_'+id).val();
    var mbid =$('#mbid_'+id).val();
    if(mbid='undefined')
    {
        var mbid =$('#vaccine_s_id_'+id).val(); 
        
    }
    var batchss =$('#batch_s_id_'+id).val();
    if(val='undefined')
    {
        var val = batchss;
    }
    
    //var qty= $('#qty_'+id+'.'+0).val();
    
    
    
     $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/check_stock_avability/", 
      dataType: "json",
       data: 'mbid='+mbid+'&batch_no='+val+'&unit2='+unit2,
      success: function(result)
      {
        //alert(result);
         if(result==1){
            $('#unit1_error_'+id).html('No Available Quantity');
         }else{
            $('#unit1_error_'+id).html('');
         }

      } 
    });
  }

$(document).ready(function(){
  //get_unit();
  var countRow = $('#vaccine tr#nodata').length;
  
  if(countRow>0){
     $("#allot_to_branch").attr("disabled","disabled");
      $("#vaccine").html('<tr id="validate"><td class="text-danger text-center" colspan="5">Please Select atleast one</td></tr>');
  }else{
     $("#allot_to_branch").removeAttr("disabled");
      // $("#vaccine").html('<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>');
    
  }
$('.close_popup').click(function()
{
  <?php //echo $this->session->unset_userdata('billing_vaccine_ids'); ?>
});
})
 $(document).ready(function(){
          $("#msg").html('');
          $.post('<?php echo base_url('vaccination_stock/get_allsub_branch_list/'); ?>',{},function(result){
               
               $("#child_branch").html(result);
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
// function check_max_qty(obj)
// {
  
   
//    var qty = $(obj).data('qty');
//    var id = $(obj).data('id');
//    var updateQty = obj.value;
//    var msgId ="#msg_"+id; 
//     $(msgId.trim()).html('');
    
//    if(updateQty>qty)
//    {
  
//         $(msgId.trim()).html("Quantity exceed to available limit");
//         $("#"+obj.id).val(qty);
//         $("#allot_to_branch").attr("disabled","disabled");
//    }
//    else if(updateQty=='')
//    {
   
//         $(msgId.trim()).html('qty is required');
//         $("#allot_to_branch").attr("disabled","disabled");
//    }
//    else
//    {
     
//      $(msgId.trim()).html('');
//      $("#allot_to_branch").removeAttr("disabled");
//    }
// }
$("button[data-number=2]").click(function(){
  <?php //$this->session->unset_userdata('alloted_medicine_ids'); ?>
    $('#load_allot_to_branch_modal_popup').modal('hide'); 
}); 

$("#allot_branch").on("submit", function(event) { 
     event.preventDefault(); 
   
     $('.overlay-loader').show();
  
     var path = 'allot_medicine_to_branch/';
     var msg = 'Medicine successfully alloted.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>vaccination_stock/"+path,
          type: "post",
          data: $(this).serialize(),
          
          success: function(result) {
               if(result==1)
               {
                    flash_session_msg(msg);
                    $('#load_allot_to_branch_modal_popup').modal('hide');
                    reload_table();
               } 
               else
               {
                    $("#load_allot_to_branch_modal_popup").html(result);
               }
               $('.overlay-loader').hide();       
          }
     });
}); 

 function check()
 {
     
     if($('#getmedicineselectAll').is(':checked')) 
     {    
        
          $('.medicinechecklist').prop('checked', false);
     }
     else
     {

          $('.medicinechecklist').prop('checked', false);
     }

 }
function print_data()
{
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .on('click', '#cancel_pop', function(e)
    { 
        $('#confirm_print').modal('hide');
        return window.location.reload(true);
    }) ;
 }

function print_window_page_ne(url)
{
  var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
  printWindow.addEventListener('load', function(){
  printWindow.print();
  return window.location.reload(true);
  }, true);

  
} 

</script>  


</div><!-- /.modal-dialog -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
          

             <?php 

             $url=  base_url("pediatrician/pediatrician_prescription/print_pediatrician_prescription_recipt"); 
             //echo $url;
             //die;
             ?>
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page_ne('<?php echo $url; ?>');">Print</a>

            <button type="button"  class="btn-cancel" id="cancel_pop" data-number="1">Close</button>
          </div>
        </div>
      </div>  
    </div>