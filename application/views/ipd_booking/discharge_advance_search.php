<style>
  
  .ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_ipd" class="form-inline"> 
      
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
                          <label>IPD No.</label>
                          <input type="text" name="ipd_no" class="inputFocus" value="<?php echo $form_data['ipd_no']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>
                        <div class="grp">
                          <label>Aadhaar No. </label>
                          <input type="text"  name="adhar_no" value="<?php echo $form_data['adhar_no']; ?>" class="numeric">
                        </div>

                        <div class="grp">
                        <div class="row m-b-2">
                        <div class="col-sm-5"><label>Patient Type</label></div>
                          <div class="col-sm-7">
                          <label><input type="radio" name="patient_type" value="1" <?php if($form_data['patient_type']==1){ echo 'checked';}?>  onclick="patient_change(this.value);"> Normal</label> &nbsp;
                          <label><input type="radio" name="patient_type" value="2" <?php if($form_data['patient_type']==2){ echo 'checked';}?> onclick="patient_change(this.value);"> Panel</label>
                          </div>
                        </div>
                        </div>

                      
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date">
                        </div>

                         <div class="grp">
                          <label>Patient Name </label>
                          <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>">
                        </div>
                        <div class="grp">
                          <label>Room No. </label>
                          <input type="text" name="room_no" id="room_no" value="<?php echo $form_data['room_no']; ?>">
                        </div>

                        <div class="grp">
                         <label>Attended Doctor <span class="star">*</span></label>
                          <select name="attended_doctor" class="m_input_default">
                            <option value="">-Select-</option>
                            <?php foreach($attended_doctor as $attened_docotr_list){ ?>
                            <option value="<?php echo $attened_docotr_list->id;?>" <?php if($form_data['attended_doctor']==$attened_docotr_list->id){echo 'selected';}?>><?php echo ucfirst($attened_docotr_list->doctor_name); ?></option>
                            <?php }?>
                         </select>
                       
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
  $('#start_date_p').val('');
  $('#end_date_p').val('');
  $('#start_date').val('');
  $('#end_date').val('');
  $('#ipd_no').val('');
  $('#room_no').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
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
 
$("#search_form_ipd").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ipd_discharge_booking/advance_search/'); ?>",
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
            $( "#automplete-1" ).autocomplete({
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

    var today =new Date();
    $('#start_date').datepicker({
    dateFormat: 'dd-mm-yy',
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