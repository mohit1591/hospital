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
                          <label>Medicine Name</label>
                          <input type="text" name="medicine_name" id="medicine_name"  value="<?php echo $form_data['medicine_name']; ?>" autofocus=""/>
                        </div>
                        
                         <div class="grp">
                          <label>Rack No. </label>
                          <input type="text" name="rack_no" value="<?php echo $form_data['rack_no']; ?>">
                        </div>

                       
                        
                        <div class="grp">
                          <label>Medicine company</label>
                          <input type="text" name="medicine_company" id = "automplete-12" value="<?php echo $form_data['medicine_company']; ?>" >
                        </div>

                      <div class="grp">
                          <label>Medicine code</label>
                          <input type="text" name="medicine_code" id="medicine_code" value="<?php echo $form_data['medicine_code']; ?>" >
                        </div>

                         <div class="grp">
                          <label>HSN No.</label>
                          <input type="text" name="hsn_no" id="hsn_no" value="<?php echo $form_data['hsn_no']; ?>" >
                        </div>
                        
                     
                        
                       <!-- <div class="grp">
                          <label>Sale Rate</label>
                          <input type="text" name="purchase_rate" id="purchase_rate" value="<?php //echo $form_data['purchase_rate']; ?>" />
                        </div>-->
                        
                        <div class="grp">
                          <label>Discount(%)</label>
                          <input type="text" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" />
                        </div>

                         <div class="grp">
                          <label>Unit1</label>
                          <select name="unit1" id="unit1">
                                  <option value="">Select Unit1</option>
                                  <?php
                                  if(!empty($unit_list))
                                  {
                                    foreach($unit_list as $unit1)
                                    {
                                      $selected_unit1 = '';
                                      if($unit1->id==$form_data['unit1'])
                                      {
                                         $selected_unit1 = 'selected="selected"';
                                      }
                                      echo '<option value="'.$unit1->id.'" '.$selected_unit1.'>'.$unit1->medicine_unit.'</option>';
                                    }
                                  }
                                  ?> 
                                </select>
                        </div>

                         <div class="grp">
                          <label>Unit2</label>
                          <select name="unit2" id="unit2">
                                  <option value="">Select Unit2</option>
                                  <?php
                                  if(!empty($unit_list))
                                  {
                                    foreach($unit_list as $unit2)
                                    {
                                      $selected_unit2 = '';
                                      if($unit1->id==$form_data['unit2'])
                                      {
                                         $selected_unit2 = 'selected="selected"';
                                      }
                                      echo '<option value="'.$unit2->id.'" '.$selected_unit2.'>'.$unit2->medicine_unit.'</option>';
                                    }
                                  }
                                  ?> 
                                </select>
                        </div>

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date">
                        </div>
                          
                        <div class="grp">
                          <label>CGST(%)</label>
                          <input type="text" name="cgst" id="cgst" value="<?php echo $form_data['cgst']; ?>" />
                        </div>
                           <div class="grp">
                          <label>SGST(%)</label>
                          <input type="text" name="sgst" id="sgst" value="<?php echo $form_data['sgst']; ?>" />
                        </div>
                           <div class="grp">
                          <label>IGST(%)</label>
                          <input type="text" name="igst" id="igst" value="<?php echo $form_data['igst']; ?>" />
                        </div>
                         
                         
                         <div class="grp">
                          <label>Packing</label>
                          <input type="text" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" />
                        </div>
                        
                        <div class="grp">
                          <label>Min Alert</label>
                          <input type="text" name="min_alert" id="min_alert" value="<?php echo $form_data['min_alert']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Conversion</label>
                          <input type="text" name="conversion" onkeypress="return isNumberKey(event);" maxlength="10" id="conversion" value="<?php echo $form_data['conversion']; ?>" >
                        </div>

                        

                        <div class="grp">
                           <label>MRP</label>
                          <div class="rslt">
                           <input type="text" name="mrp_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['mrp_to']; ?>"> To
                            <input type="text" name="mrp_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['mrp_from']; ?>"> 
                          </div>
                        </div>


                         <div class="grp">
                           <label>Purchase Rate</label>
                          <div class="rslt">
                           <input type="text" name="purchase_to" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['purchase_to']; ?>"> To
                            <input type="text" name="purchase_from" class="w-80px" onkeypress="return isNumberKey(event);"  value="<?php echo $form_data['purchase_from']; ?>"> 
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

<?php //print_r(all_medicine_list()); ?>
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
  $.ajax({url: "<?php echo base_url(); ?>medicine_entry/reset_search/", 
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
    url: "<?php echo base_url('medicine_entry/advance_search/'); ?>",
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
 
 <script> 
         $(function() {
             var all_manufacturingcompany  =  
             [
              <?php
              $company_list= manuf_company_list();
              if(!empty($company_list))
              { 
                 foreach($company_list as $company)
                  { 
                    echo '"'.$company->company_name.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#automplete-12" ).autocomplete({
               source: all_manufacturingcompany
            });


              var all_medicinelist  =  
             [
              <?php
              $medicine_list= all_medicine_list();
              if(!empty($company_list))
              { 
                 foreach($medicine_list as $medicine_name)
                  { 
                    echo '"'.$medicine_name->medicine_name.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#medicine_name" ).autocomplete({
               source: all_medicinelist
            });
         });


      </script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->
