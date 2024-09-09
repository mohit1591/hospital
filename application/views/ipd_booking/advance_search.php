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
                      <div class="row">
                        <div class="grp ">
                          
                          <div class="rslt">
                            <div class="col-md-2"><label>Insurance Type</label> </div>
                            <div class="col-md-2">
                              <input type="radio" value="0" name="running" id="running" class=""     <?php /* if($form_data['running']=='0'){ ?> checked="checked" <?php  }*/ ?> > Running  &nbsp;
                            </div>

                            <div class="col-md-2">
                              <input type="radio" value="1"  name="running" id="running"   <?php if($form_data['running']=='1'){ ?> checked="checked" <?php } ?>> Discharge
                           </div>  

                           <div class="col-md-2">
                              <input type="radio" value="2"  name="running" id="running"   <?php if($form_data['running']=='2'){ ?> checked="checked" <?php } ?>> All
                           </div> 

                          </div> 
                        </div>
                      </div>
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
                          <label>Insurance Type</label> 
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="insurance_type" value="1" <?php if($form_data['insurance_type']==1){ echo 'checked="checked"'; } ?> onclick="return set_tpa(1)">Normal &nbsp;
                            </div>
                            <div class="gen">
                              <input type="radio" name="insurance_type" value="2" <?php if($form_data['insurance_type']==2){ echo 'checked="checked"'; } ?> onclick="return set_tpa(2)">TPA 
                           </div>   
                          </div> 
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
                        <label>MLC </label>
                        <select name="mlc" id="mlc">
                          <option value="0" <?php echo $form_data['mlc']=="0"?"selected":""?>>No</option>
                          <option value="1" <?php echo $form_data['mlc']=="1"?"selected":""?>>Yes</option>
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
               <input type="reset" onclick="return reset_advance_search(this.form);" class="btn-reset" name="reset" value="Reset" />
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
    if(val==1)
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

/*function reset_search()
{
  $('#start_date_p').val('');
  $('#end_date_p').val('');
  $('#start_date').val('');
  $('#end_date').val('');
  $('#ipd_no').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
$('#room_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}*/
function reset_advance_search(ele) { 
   $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
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
    url: "<?php echo base_url('ipd_booking/advance_search/'); ?>",
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