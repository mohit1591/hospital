 <style>

.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>" id="start_date">
                        </div>
                        
                        
                        
                        <div class="grp">
                          <label>Patient Name</label>
                        
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                         
                        </div>
                        
                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                        <div class="grp">
                          <label>Sale No. </label>
                          <input type="text" name="sale_no" value="<?php echo $form_data['sale_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                       <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?> </label>
                          <input type="text" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" onkeypress="return isNumberKey(event);">
                        </div>
                        
                      <!--  <div class="grp">
                          <label>Medicine Name</label>
                          <input type="text" name="medicine_name" id="medicine_name"  value="<?php //echo $form_data['medicine_name']; ?>" />
                        </div>
                        
                        
                        <div class="grp">
                          <label>Medicine company</label>
                          <input type="text" name="medicine_company" id="automplete-1" value="<?php //echo $form_data['medicine_company']; ?>" >
                        </div>-->
                        
                         <div class="grp">
                          <label>Medicine Name</label>
                              <input type="text" name="medicine_name" id="medicine_name"  value="<?php echo $form_data['medicine_name']; ?>" onkeypress="search_func(this.value);" autocomplete="off" />
                                <div class="append_box_medicine medicin"></div>
                              
                        </div>
                        
                        
                        <div class="grp">
                          <label>Medicine company</label>
                              <input type="text" name="medicine_company" id="medicine_company" value="<?php echo $form_data['medicine_company']; ?>" onkeypress="search_func_com(this.value);"  autocomplete="off">
                               <div class="append_box_medicine comp"></div>
                            
                        </div>

                      <div class="grp">
                          <label>Medicine code</label>
                          <input type="text" name="medicine_code" id="medicine_code" value="<?php echo $form_data['medicine_code']; ?>" >
                        </div>
                         <div class="grp">
                          <label>CGST (Rs.)</label>
                          <input type="text" name="cgst" id="cgst" value="<?php echo $form_data['cgst']; ?>" />
                        </div>

                        <div class="grp">
                          <label>SGST (Rs.)</label>
                          <input type="text" name="sgst" id="sgst" value="<?php echo $form_data['sgst']; ?>" />
                        </div>

                         <div class="grp">
                          <label>IGST (Rs.)</label>
                          <input type="text" name="igst" id="igst" value="<?php echo $form_data['igst']; ?>" />
                        </div>
                     
                        
                       <!-- <div class="grp">
                          <label>Sale Rate</label>
                          <input type="text" name="purchase_rate" id="purchase_rate" value="<?php //echo $form_data['purchase_rate']; ?>" />
                        </div>-->
                        
                        <div class="grp">
                          <label>Discount(%)</label>
                          <input type="text" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" />
                        </div>

                          <!-- <div class="grp">
                          <label>Referred By</label>
                          <select name="refered_by" id="refered_by">
                                  <option value="">Select</option>
                                  <?php
                                  if(!empty($doctors_list))
                                  {
                                    foreach($doctors_list as $doctor)
                                    {
                                      $selected_doctor = '';
                                      if($doctor->id==$form_data['refered_by'])
                                      {
                                         $selected_doctor = 'selected="selected"';
                                      }
                                      echo '<option value="'.$doctor->id.'" '.$selected_doctor.'>'.$doctor->doctor_name.'</option>';
                                    }
                                  }
                                  ?> 
                                </select>
                        </div> -->

                        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="col-md-7" id="referred_by_1">
                   <label><input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
                    <label><input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
                    
            </div>
        </div> <!-- innerrow -->

        <div class="row m-b-5" id="doctor_div_1" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="col-md-7">
                <select class="w-150px" name="refered_id" id="refered_id_1">
                    <option value="">Select Doctor</option>
                    <?php foreach($doctors_list as $doctors) {?>
                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                    <?php }?>
                </select>
                   

            </div>
        </div> <!-- innerrow -->

        <div class="row m-b-5" id="hospital_div_1" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="col-md-7">
                <select name="referral_hospital" id="referral_hospital_1" class="w-150px m_input_default" >
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            
            </div>
        </div> 

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date">
                        </div>
                          
                       
                          
                        <div class="grp">
                          <label>Batch No.</label>
                          <input type="text" name="batch_no" id="batch_no" value="<?php echo $form_data['batch_no']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Quantity</label>
                          <input type="text" name="quantity" onkeypress="return isNumberKey(event);" id="quantity" value="<?php echo $form_data['quantity']; ?>" maxlength="10">
                        </div>
                          
                         
                          
                        <div class="grp">
                          <label>Packing</label>
                          <input type="text" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Conversion</label>
                          <input type="text" name="conversion" onkeypress="return isNumberKey(event);" maxlength="10" id="conversion" value="<?php echo $form_data['conversion']; ?>" >
                        </div>

                         <div class="grp">
                           <label>Paid Amount</label>
                          <div class="rslt">
                           <input type="text" name="paid_amount_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['paid_amount_to']; ?>"> To
                            <input type="text" name="paid_amount_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['paid_amount_from']; ?>"> 
                          </div>
                        </div>

                        <!--<div class="grp">
                           <label>MRP</label>
                          <div class="rslt">
                           <input type="text" name="mrp_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php //echo $form_data['mrp_to']; ?>"> To
                            <input type="text" name="mrp_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php //echo $form_data['mrp_from']; ?>"> 
                          </div>
                        </div>-->

                       <!-- <div class="grp">
                          <label>Paid Amount</label>


                          <input type="text" name="paid_amount" onkeypress="return isNumberKey(event);" maxlength="10" id="paid_amount" value="<?php //echo $form_data['paid_amount']; ?>" >
                        </div>-->

                        <div class="grp">
                           <label>Balance</label>
                          <div class="rslt">
                           <input type="text" name="balance_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['balance_to']; ?>"> To
                            <input type="text" name="balance_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['balance_from']; ?>"> 
                          </div>
                        </div>

                        <!-- <div class="grp">
                          <label>Balance</label>
                          <input type="text" name="balance" onkeypress="return isNumberKey(event);" maxlength="10" id="balance" value="<?php //echo $form_data['balance']; ?>" >
                        </div>-->

                         <div class="grp">
                           <label>Total Amount</label>
                          <div class="rslt">
                           <input type="text" name="total_amount_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['total_amount_to']; ?>"> To
                            <input type="text" name="total_amount_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['total_amount_from']; ?>"> 
                          </div>
                        </div>

                       <!-- <div class="grp">
                          <label>Total Amount</label>
                          <input type="text" name="total_amount" onkeypress="return isNumberKey(event);" maxlength="10" id="total_amount" value="<?php //echo $form_data['total_amount']; ?>" >
                        </div>-->
                          
                      <div class="grp">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" id="bank_name" value="<?php echo $form_data['bank_name']; ?>" >
                      </div>
                          
                   
                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 
$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test2 = $(this).val();
      if(test2==0)
      {
        $("#hospital_div_1").hide();
        $("#doctor_div_1").show();
        $('#referral_hospital_1').val('');
        
      }
      else if(test2==1)
      {
          
          $("#doctor_div_1").hide();
          $("#hospital_div_1").show();
          $('#refered_id_1').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
}); 
<?php
if(isset($form_data['insurance_type']) && $form_data['insurance_type']!="" && isset($form_data['insurance_type_id']) && $form_data['insurance_type_id']!="")
{
  echo 'set_tpa('.$form_data['insurance_type_id'].');';
}
?>
 
function set_tpa(val)
 {
    if(val==0)
    {
      $('#insurance_type_id').attr("disabled", true);
      $('#insurance_type_id').val('');
      $('#ins_company_id').attr("disabled", true);
      $('#ins_company_id').val('');
      $('#polocy_no').attr("readonly", "readonly");
      $('#polocy_no').val('');
      $('#tpa_id').attr("readonly", "readonly");
      $('#tpa_id').val('');
      $('#ins_amount').attr("readonly", "readonly");
      $('#ins_amount').val('');
      $('#ins_authorization_no').attr("readonly", "readonly");
      $('#ins_authorization_no').val('');
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
      $('#ins_authorization_no').removeAttr("readonly", "readonly");
    }
 }


function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>sales_return_medicine/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('sales_return_medicine/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 
 /* $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true
  })*/

    var today =new Date();
  $('#start_date').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date").datepicker("option", "minDate", selected);
        }
  })
 $('#end_date').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date").datepicker("option", "maxDate", selected);
        }
  })


</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->

<script> 


  function search_func(val)
  {  
    var len = val.length;
     if(len>=2)
     { 
     $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_entry/ajax_list_medicine')?>",
         data: {'medicine_name' : val},
         dataType: "json",
         success: function(msg){
          $(".append_row_opt").remove();
          $(".medicin").show().html(msg.data);
          $('.append_row_opt').click(function(){
        $('#medicine_name').val($(this).text());
        $(".append_box_medicine").hide()
      });
         }
      }); 
    }
  }

  function search_func_com(val)
  {  
     var len = val.length;
     if(len>=2)
     {
     $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_entry/ajax_list_medicine_com')?>",
         data: {'company_name' : val},
         dataType: "json",
         success: function(msg){
          $(".append_row_opt_com").remove();
          $(".comp").show().html(msg.data);
          $('.append_row_opt_com').click(function(){
        $('#medicine_company').val($(this).text());
        $(".append_box_medicine").hide()
      });
         }
      }); 
    }
  }
      </script>