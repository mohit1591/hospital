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
                          <input type="text" class="datepicker" id="start_date_stock" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Batch No.</label>
                          <input type="text" class="" name="batch_no" value="<?php echo $form_data['batch_no']; ?>">
                        </div>
                        
                        
                        
                       <!--  <div class="grp">
                          <label>Unit1 </label>
                        
                              <select name="unit1" id="unit1" >
                                  <option value="">Select Unit1</option>
                                  <?php
                                  /*if(!empty($unit_list))
                                  {
                                    foreach($unit_list as $unit1)
                                    {
                                      $selected_unit1 = '';
                                      if($unit1->id==$form_data['unit1'])
                                      {
                                         $selected_unit1 = 'selected="selected"';
                                      }
                                      echo '<option value="'.$unit1->id.'" '.$selected_unit1.'>'.$unit1->vaccination_unit.'</option>';
                                    }
                                  }*/
                                  ?> 
                                </select> 
                              
                         
                        </div> -->
                        <!-- <div class="grp">
                          <label>Unit2 </label>
                        
                              <select name="unit2" id="unit2">
                                  <option value="">Select Unit2</option>
                                  <?php
                                 /* if(!empty($unit_list))
                                  {
                                    foreach($unit_list as $unit1)
                                    {
                                      $selected_unit1 = '';
                                      if($unit1->id==$form_data['unit1'])
                                      {
                                         $selected_unit1 = 'selected="selected"';
                                      }
                                      echo '<option value="'.$unit1->id.'" '.$selected_unit1.'>'.$unit1->vaccination_unit.'</option>';
                                    }
                                  }*/
                                  ?> 
                                </select> 
                              
                         
                        </div> -->
                        
                        
                        <div class="grp">
                          <label>Vaccination code. </label>
                          <input type="text" maxlength="10" name="vaccination_code" value="<?php echo $form_data['vaccination_code']; ?>">
                        </div>

                        <div class="grp">
                          <label>Vaccination Name. </label>
                          <input type="text" name="vaccination_name" value="<?php echo $form_data['vaccination_name']; ?>" id="vaccination_name">
                        </div>

                       <div class="grp">
                          <label>Min Alert. </label>
                          <input type="text" name="min_alert" value="<?php echo $form_data['min_alert']; ?>">
                        </div>
                        
                       
                        
                        
                        <div class="grp">
                          <label>Vaccination company</label>
                          <input type="text" name="vaccination_company" id="automplete-1" value="<?php echo $form_data['vaccination_company']; ?>" >
                        </div>

                      </div> <!-- inner -->

                      <div class="inner">
                         <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" id="end_date_stock" class="datepicker" value="<?php echo $form_data['end_date']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Rack No.</label>
                          <input type="text" name="rack_no" class="" value="<?php echo $form_data['rack_no']; ?>">
                        </div>
                          
                          <div class="grp">
                          <label>Packing. </label>
                          <input type="text" name="packing" value="<?php echo $form_data['packing']; ?>">
                        </div>
                        
                         <div class="grp">
                           <label>Price MRP</label>
                          <div class="rslt">
                           <input type="text" name="price_to_mrp" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['price_to_mrp']; ?>"> To
                            <input type="text" name="price_from_mrp" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['price_from_mrp']; ?>"> 
                          </div>
                        </div>

                        <div class="grp">
                           <label>Price Purchase</label>
                          <div class="rslt">
                           <input type="text" name="price_to_purchase" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['price_to_purchase']; ?>"> To
                            <input type="text" name="price_from_purchase" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['price_from_purchase']; ?>"> 
                          </div>
                        </div>

                      

                        <div class="grp">
                           <label>Quantity</label>
                          <div class="rslt">
                           <input type="text" name="qty_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['qty_to']; ?>"> To
                            <input type="text" name="qty_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['qty_from']; ?>"> 
                          </div>
                        </div>

                         <div class="grp">
                           <label>Expiry Date</label>
                          <div class="rslt">
                           <input type="text" name="expiry_to" id="start_date_expiry" class="w-80px datepicker"  value="<?php echo $form_data['expiry_to']; ?>"> To
                            <input type="text" name="expiry_from" id="end_date_expiry" class="w-80px datepicker" value="<?php echo $form_data['expiry_from']; ?>"> 
                          </div>
                           
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

function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
}

function get_city(state_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
    success: function(result)
    {
      $('#city_id').html(result); 
    } 
  }); 
}

function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>vaccination_stock/reset_search/", 
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
    url: "<?php echo base_url('vaccination_stock/advance_search/'); ?>",
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
  })

  */
   var today =new Date();
  $('#start_date_stock').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date_stock").datepicker("option", "minDate", selected);
        }
  })
 $('#end_date_stock').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date_stock").datepicker("option", "maxDate", selected);
        }
  })

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

                var all_vaccination_list  =  
             [
              <?php
              $medicine_list= all_vaccination_list();
              if(!empty($medicine_list))
              { 
                 foreach($medicine_list as $medicine_name)
                  { 
                    echo '"'.$medicine_name->vaccination_name.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#vaccination_name" ).autocomplete({
               source: all_vaccination_list
            });
         });


   $('#start_date_expiry').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date_expiry").datepicker("option", "minDate", selected);
        }
  })
  $('#end_date_expiry').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date_expiry").datepicker("option", "maxDate", selected);
        }
  })

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->