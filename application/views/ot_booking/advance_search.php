<style>

.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_ot" class="form-inline"> 
      
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
                          <input type="text" class="datepicker" name="start_date" id="start_date_ot" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        
                        
                        <div class="grp">
                          <label>Patient Name</label>
                        
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
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name searchFocus">
                         
                        </div>
                        
                     

                        <div class="grp">
                          <label>IPD No. </label>
                          <input type="text" name="ipd_no" value="<?php echo $form_data['ipd_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                        <div class="grp">
                          <label>Aadhaar No. </label>
                          <input type="text" name="adhar_no" value="<?php echo $form_data['adhar_no']; ?>" class="numeric">
                        </div>

                       <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" onkeypress="return isNumberKey(event);">
                        </div>
                        
                        <div class="grp" id="op_name">
                          <label>Operation Name</label>
                          <select name="operation_name" id="operation_name" class="m_input_default" id="ot_name_id" onchange="get_operation_prices(this.value);">
                            <option value="">Select Operation</option>
                            <?php foreach($operation_list as $op_list)
                            {?>
                            <option value="<?php echo $op_list->id;?>" <?php if(isset($form_data['operation_name']) && $form_data['operation_name']== $op_list->id){echo 'selected';}?>  > <?php echo $op_list->name; ?>   </option>
                            <?php }?>

                          </select>
                        </div>
                      
                        <div class="grp">
                              <label>Package</label>
                              <select class="m_input_default" name="pacakage_name" id="ot_pacakge_id" onchange="get_package_prices(this.value);">
                                <option value="" >Select OT package</option>
                                <?php foreach($ot_pacakage_list as $package_list) {?>
                                <option value="<?php echo $package_list->id; ?>" <?php  if($package_list->id==$form_data['pacakage_name']){ echo 'selected';}?>><?php echo $package_list->name; ?></option>
                                <?php }?>
                              </select>
                        </div>
                        
                        <!--<div class="grp">
                          <label>Operation Time</label>
                          <input type="text" name="operation_time" id="operation_time" value="<?php echo $form_data['operation_time']; ?>" >
                        </div>

                      <div class="grp">
                          <label>Operation Date</label>
                          <input type="text" name="operation_date" id="operation_date" value="<?php echo $form_data['operation_date']; ?>" >
                        </div>-->
                       
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date_ot">
                        </div>
                        
                        <div class="grp">
                          <label>Insurance Type</label> 
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="insurance_type" value="0" <?php if($form_data['insurance_type']=='0'){ echo 'checked="checked"'; } ?> onclick="return set_tpa(0)">Normal &nbsp;
                            </div>
                            <div class="gen">
                              <input type="radio" name="insurance_type" value="1" <?php if($form_data['insurance_type']=='1'){ echo 'checked="checked"'; } ?> onclick="return set_tpa(1)">TPA 
                           </div>   
                          </div> 
                        </div>


                            <div class="grp">
                              <label>Type</label> 
                                  <select name="insurance_type_id" id="insurance_type_id">
                                    <option value="">Select Insurance Type</option>
                                    <?php
                                    if(!empty($insurance_type_list))
                                    {
                                      foreach($insurance_type_list as $insurance_type)
                                      {
                                        $selected_ins_type = "";
                                        if($insurance_type->id==$form_data['insurance_type_id'])
                                        {
                                          $selected_ins_type = 'selected="selected"';
                                        }
                                        echo '<option value="'.$insurance_type->id.'" '.$selected_ins_type.'>'.$insurance_type->insurance_type.'</option>';
                                      }
                                    }
                                    ?> 
                                  </select> 
                            </div>


                            <div class="grp">
                              <label>Company</label> 
                                  <select name="ins_company_id" id="ins_company_id">
                                    <option value="">Select Insurance Company</option>
                                    <?php
                                    if(!empty($insurance_company_list))
                                    {
                                      foreach($insurance_company_list as $insurance_company)
                                      {
                                        $selected_company = '';
                                        if($insurance_company->id == $form_data['ins_company_id'])
                                        {
                                          $selected_company = 'selected="selected"';
                                        }
                                        echo '<option value="'.$insurance_company->id.'" '.$selected_company.'>'.$insurance_company->insurance_company.'</option>';
                                      }
                                    }
                                    ?> 
                                  </select> 
                            </div>
                        
                            <div class="grp">
                             <label>Specialization</label>
                             <select name="specialization_id" id="specialization_id" onchange="hide_show_ipd_option(this.value);">
                                    <option>Select Specialization</option>
                                    <?php foreach($specialization_list as $list) {  ?>
                                          <option  <?php if($form_data['specialization_id']==$list->id){ echo 'selected="selected"'; } ?>  value="<?php echo $list->id; ?>"><?php echo $list->specialization; ?></option>
                                    <?php } ?>
                              </select>
                            </div>

                            <div class="grp">
                             <label>Doctor List</label>
                             <input type="text" class=" m-b-5 doctor_name_ot inputFocus" name="doctor_name" id="doctor_name_ot" >
                             <input type="hidden" class=" m-b-5 doctor_id_ot inputFocus" name="doctor_id" id="doctor_id_ot" >
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
  setTimeout(() => {
    $("#operation_name").select2();
    $('.select2-container').css({
    'float': 'right'
    });
  }, 500);
  get_operation_prices('<?php echo $lead_ot_id; ?>');
});

function get_operation_prices(val)
 {
   $.ajax({
            type: "POST",
            dataType: "json",
            url: "<?php echo base_url('ot_booking/get_amount_details_operation_management');?>",
            data: {op_mgmt_id:val  },
            success: function(result) 
            {
              <?php     if($set_payment_hide==0) { ?> 
              $("#total_amount").val(result.amount);
              $("#net_amount").val(result.amount);
              $("#paid_amount").val(result.amount);
              $("#discount").val('0.00');
              $("#balance").val('0.00');
              $("#discount_percent").val('0.00');
              <?php } ?>
            }
          });
 }

 function get_ot_name()
{
   $.ajax({url: "<?php echo base_url(); ?>ot_booking/ot_name_dropdown/", 
    success: function(result)
    {
     
      $('#ot_name_id').html(result); 
      
    } 
  });
}

function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>ot_booking/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}
 
$("#search_form_ot").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ot_booking/advance_search/'); ?>",
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
  $('#start_date_ot').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date_ot").datepicker("option", "minDate", selected);
        }
  });
 $('#end_date_ot').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#end_date_ot").datepicker("option", "maxDate", selected);
        }
  });

  $(function () {

var i=1;
var getData = function (request, response) { 
    row = i ;
    specialization_id=$("#specialization_id").val();
    $.ajax({
    url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term +"/"+specialization_id,
    dataType: "json",
    method: 'post',
   data: {
     name_startsWith: request.term,
     
     row_num : row
  },
   success: function( data ) {
     response( $.map( data, function( item ) {
      var code = item.split("|");
      return {
        label: code[0],
        value: code[0],
        data : item
      }
    }));
  }
  });

   
};

var selectItem = function (event, ui) {
    //$(".medicine_val").val(ui.item.value);

    var names = ui.item.data.split("|");

      $('.doctor_name_ot').val(names[0]);
      $('.doctor_id_ot').val(names[1]);
      

    return false;
}

$(".doctor_name_ot").autocomplete({
    source: getData,
    select: selectItem,
    minLength: 1,
    change: function() {  
        //$("#default_vals").val("").css("display", 2);
    }
});
});

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
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
    }
 }

  set_tpa(<?php echo $form_data['insurance_type']; ?>);
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->