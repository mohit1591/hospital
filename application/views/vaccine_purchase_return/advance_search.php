 <style>
.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_advance" class="form-inline"> 
      
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
                          <label>Vendor Code</label>
                          <input type="text" name="vendor_code" class="inputFocus" value="<?php echo $form_data['vendor_code']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Vendor Name</label>
                        
                              <!-- <select name="simulation_id" id="simulation_id" class="mr">
                                  <option value="">Select</option>
                                  <?php
                                  if(!empty($simulation_list))
                                  {
                                    foreach($simulation_list as $simulation)
                                    {
                                      $selected_simulation = '';
                                      if($simulation->id==$form_data['simulation_id'])
                                      {
                                         $selected_simulation = 'selected="selected"';
                                      }
                                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                                    }
                                  }
                                  ?> 
                                </select> -->
                              <input type="text" name="vendor_name" id="vendor_name" value="<?php echo $form_data['vendor_name']; ?>" class="p-name">
                         
                        </div>
                        
                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                        <div class="grp">
                          <label>Invoice No. </label>
                          <input type="text" name="invoice_id" value="<?php echo $form_data['invoice_id']; ?>">
                        </div>

                       <div class="grp">
                          <label>Purchase No. </label>
                          <input type="text" name="purchase_no" value="<?php echo $form_data['purchase_no']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Vaccination Name</label>
                          <input type="text" name="vaccination_name" id="vaccination_name"  value="<?php echo $form_data['vaccination_name']; ?>" />
                        </div>
                        
                        
                        <div class="grp">
                          <label>Vaccination company</label>
                          <input type="text" name="vaccine_company" id="automplete-1" value="<?php echo $form_data['vaccine_company']; ?>" >
                        </div>

                      <div class="grp">
                          <label>Vaccination code</label>
                          <input type="text" name="vaccine_code" id="vaccine_code" value="<?php echo $form_data['vaccine_code']; ?>" >
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
                        
                        <!--<div class="grp">
                          <label>Purchase Rate</label>
                          <input type="text" name="purchase_rate" id="purchase_rate" value="<?php //echo $form_data['purchase_rate']; ?>" />
                        </div>-->
                        
                        <div class="grp">
                          <label>Discount(%)</label>
                          <input type="text" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" />
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
                          <label>Unit1</label>
                          <input type="text" name="unit1" onkeypress="return isNumberKey(event);" id="unit1" value="<?php echo $form_data['unit1']; ?>" maxlength="10">
                        </div>
                          
                         <div class="grp">
                          <label>Unit2</label>
                          <input type="text" name="unit2" onkeypress="return isNumberKey(event);" id="unit2" value="<?php echo $form_data['unit2']; ?>" maxlength="10">
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
                           <input type="text" name="mrp_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['mrp_to']; ?>"> To
                            <input type="text" name="mrp_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['mrp_from']; ?>"> 
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
                <input value="Reset" onclick="clear_form_elements(this.form)" type="button" class="btn-reset">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  



function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>vaccine_purchase_return/reset_search/", 
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
 
$("#search_form_advance").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('vaccine_purchase_return/advance_search/'); ?>",
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
 

$(function() {
             var all_manufacturingcompany  =  
             [
              <?php
              $company_list= vaccination_manuf_company_list();
              if(!empty($company_list))
              { 
                 foreach($company_list as $company)
                  { 
                    echo '"'.$company->company_name.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#automplete-1" ).autocomplete({
               source: all_manufacturingcompany
            });

                var all_medicinelist  =  
             [
              <?php
              $medicine_list= all_vaccination_list();
              if(!empty($medicine_list))
              { 
                 foreach($medicine_list as $vaccine_name)
                  { 
                    echo '"'.$vaccine_name->vaccination_name.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#vaccination_name" ).autocomplete({
               source: all_medicinelist
            });
         });

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