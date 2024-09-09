<?php
$users_data = $this->session->userdata('auth_users');
?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
<form  id="test_search_form" class="form-inline">     
  <div class="modal-content"> 
      
      
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4><?php echo $page_title;  ?></h4> 
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="start_datepicker" id="adv_start_date" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Patient Reg. No.</label>
                          <input type="text" name="patient_code"  value="<?php echo $form_data['patient_code']; ?>">
                        </div>
                      <?php
                      if($users_data['users_role']==1 || $users_data['users_role']==2)
                      {
                      ?>
                        <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
                           <div class="grp">
                           <label>Referred By</label>
                           <div class="rslt">
                                <div id="referred_by_1">
                                       <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
                                        <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
                                        
                                </div>
                            </div>
                        </div> <!-- innerrow -->

                        <div class="grp" id="doctor_div_1" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
                            
                                <label>Referred By</label>

                              <div class="rslt">
                            
                                <select  name="refered_id" id="refered_id_1">
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors_list as $doctors) {?>
                                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                                    <?php }?>
                                </select>
                                   

                           </div>
                        </div> 

                        <div class="grp" id="hospital_div_1" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
                            
                            <label>Referred By</label>
                            <div class="rslt">
                                <select name="referral_hospital" id="referral_hospital_1" class="" >
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
                        <?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
                        {  ?>
                          <div class="grp">
                            
                                <label>Referred By</label>
                               <div class="rslt">
                                <select  name="refered_id" id="refered_id_1">
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors_list as $doctors) {?>
                                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                                    <?php }?>
                                </select>
                            </div>
                                   

                           
                        </div> <!-- row -->
                        <input type="hidden" name="referred_by" value="0">
                        <input type="hidden" name="referral_hospital" value="0">
                        <?php 
                        } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
                        {  
                          ?>

                          <div class="grp">
                            
                                <label>Referred By</label>

                                 <div class="rslt">
                            
                                <select name="referral_hospital" id="referral_hospital_1"  >
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
                        <input type="hidden" name="referred_by" value="1">
                        <input type="hidden" name="refered_id" value="0">
                          <?php 

                          } ?>
                        
                      <?php
                      }
                      ?>  
                        <div class="grp">
                          <label>Patient Name </label>
                          <div class="rslt">
                              <select name="simulation_id" id="simulation_id" class="mr m6">
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
                                </select> 
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                          </div>
                        </div>
                        

                        

                        <div class="grp">
                           <label>Age </label>
                          <div class="rslt">
                           <input type="text" name="age_from" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_from']; ?>"> To
                            <input type="text" name="age_to" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_to']; ?>"> 
                          </div>
                        </div>
                         
                        <div class="grp">
                          <label>Country</label>
                          <select name="country_id" id="country_id" onchange="return get_state(this.value);">
                            <option value="">Select Country</option>
                            <?php
                            if(!empty($country_list))
                            {
                              foreach($country_list as $country)
                              {
                                $selected_country = "";
                                if($country->id==$form_data['country_id'])
                                {
                                  $selected_country = 'selected="selected"';
                                }
                                echo '<option value="'.$country->id.'" '.$selected_country.'>'.$country->country.'</option>';
                              }
                            }
                            ?> 
                          </select>
                        </div>
                        
                        
                        
                        <div class="grp">
                          <label>City</label>
                          <select name="city_id" id="city_id">
                              <option value="">Select City</option>
                              <?php
                               if(!empty($form_data['state_id']))
                               {
                                  $city_list = city_list($form_data['state_id']);
                                  if(!empty($city_list))
                                  {
                                     foreach($city_list as $city)
                                     {
                                      ?>   
                                        <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>>
                                        <?php echo $city->city; ?> 
                                        </option>
                                      <?php
                                     }
                                  }
                               }
                              ?>
                            </select>
                        </div>

<div class="grp">
                          <label>Barcode:</label>
                          <input type="text" name="bar_code" id="bar_code" class="" value="<?php echo $form_data['bar_code']; ?>" onkeyup="return form_submit();">
                        <!-- 3 -->
                        </div>

                        <div class="grp">
                          <label><b>Tube No.</b></label>
                          <input type="text" name="tube_no" id="tube_no" class="" value="<?php echo $form_data['tube_no']; ?>" onkeyup="return form_submit();">
                        </div> <!-- 3 -->
                        
                        <div class="grp">
                          <label><b>Collection Center</b></label>
                <select name="collection_center" id="collection_center" class="m_input_default">
                <option value=""> Select Collection Center </option>
                <?php
                if(!empty($collection_center_list))
                {
                  foreach($collection_center_list as $collection_center)
                  {
                    ?>
                     <option <?php if($form_data['collection_center']==$collection_center->id){ echo 'selected="selected"'; } ?> value="<?php echo $collection_center->id; ?>"><?php echo $collection_center->source; ?></option>
                    <?php
                  }
                }
                ?> 
              </select>
                        </div> <!-- 3 -->
                        
                        
                        <div class="grp">
                          <label><b>Home Collection</b></label>
                <input type="checkbox" name="is_home_collection" id="is_home_collection" class="" value="1">
                        </div>
                        
                        <div class="grp">
                           <label>Completion Status </label>
                          <div class="rslt">
                            <input type="radio" name="complation_status" value="1" <?php if(isset($form_data['complation_status']) && $form_data['complation_status']==1){ echo 'checked="checked"'; } ?>> Complete &nbsp;
                            <input type="radio" name="complation_status" value="2" <?php if(isset($form_data['complation_status']) && $form_data['complation_status']!="" && $form_data['complation_status']==2){ echo 'checked="checked"'; } ?>> Pending
                          </div>
                        </div>   
                        

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" id="adv_end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>">
                        </div>

                        <div class="grp">
                          <label>Lab Ref. No.</label>
                          <input type="text" name="lab_reg_no" id="lab_reg_no" value="<?php echo $form_data['lab_reg_no']; ?>" >
                        </div>
                      <?php
                      if($users_data['users_role']==1 || $users_data['users_role']==2)
                      {
                      ?>
                        <div class="grp">
                          <label>Attended Doctor</label>
                          <select name="attended_doctor" id="attended_doctor">
                            <option value="">Select Doctor</option>
                            <?php
                            if(!empty($attended_doctor_list))
                            {
                              foreach($attended_doctor_list as $attended_doctor)
                              {
                                $selected_attended = "";
                                if($attended_doctor->id==$form_data['attended_doctor'])
                                {
                                  $selected_attended = 'selected="selected"';
                                }
                                echo '<option value="'.$attended_doctor->id.'" '.$selected_attended.'>'.$attended_doctor->doctor_name.'</option>';
                              }
                            }
                            ?> 
                          </select>
                        </div>
                    <?php
                      }
                    ?>      
                        <div class="grp">
                          <label>Mobile No. </label>
						   <div style="float:right">
						  <input type="text" name="country_code" value="+91" readonly="" class="country_code" placeholder="+91"> 
                          <input type="text" maxlength="10" name="mobile_no" class="number numeric" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
						  </div>
                        </div>
                          
                        <div class="grp">
                           <label>Gender </label>
                          <div class="rslt">
                            <input type="radio" name="gender" value="1" <?php if(isset($form_data['gender']) && $form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                            <input type="radio" name="gender" value="0" <?php if(isset($form_data['gender']) && $form_data['gender']!="" && $form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                          </div>
                        </div> 
                        
                        <div class="grp">
                          <label>State</label>
                          <select name="state_id" id="state_id" onchange="return get_city(this.value)">
                            <option value="">Select State</option>
                            <?php
                           if(!empty($form_data['country_id']))
                           {
                              $state_list = state_list($form_data['country_id']); 
                              if(!empty($state_list))
                              {
                                 foreach($state_list as $state)
                                 {  
                                  ?>   
                                    <option value="<?php echo $state->id; ?>" <?php if(!empty($form_data['state_id']) && $form_data['state_id'] == $state->id){ echo 'selected="selected"'; } ?>><?php echo $state->state; ?></option>
                                  <?php
                                 }
                              }
                           }
                          ?>
                          </select>
                        </div>

                        <div class="grp">
                          <label>Pin Code</label>
                          <input type="text" name="pincode" id="pincode" value="<?php echo $form_data['pincode']; ?>" maxlength="6" onkeypress="return isNumberKey(event);">
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
                          <label>Profile</label>
                          <select name="profile_id" id="profile_id" onchange="get_profile_test();">
                            <option value="">Select Profile</option>
                                  <?php
                                   if(!empty($profile_list))
                                   {
                                      foreach($profile_list as $profile)
                                      { 
									      $selected = "";
									      if($form_data['profile_id']==$profile->id)
										  {
										     $selected =  'selected="selected"'; 
										  }
                                          echo '<option value="'.$profile->id.'" '.$selected.' >'.$profile->profile_name.'</option>';
                                      }
                                   }
                                  ?>
                          </select>
                        </div>
                        
                        <div class="grp">
                          <label>Test</label>
                           <input type="text"  name="test_name" id="test_name" value="" class="test_ids alpha_numeric_space inputFocus" placeholder="Type Test Name">
                           <input type="hidden" name="test_ids" id="test_id">
                        </div>




                          

                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
<!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> -->
<input value="Reset" onclick="clear_form_elements(this.form)" type="button" class="btn-reset">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
   </div><!-- /.modal-content -->           
    </form>     

<script>  
  
$(document).ready(function() 
  {
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
/*
function reset_search()
{
  $.ajax({url: "<?php echo base_url(); ?>test/reset_date_search/", 
    success: function(result)
    { 
      $('#load_adv_search_modal_popup').modal('hide'); 
      reload_table();
    } 
  }); 
}*/

function reset_search()
  {
    $('#adv_start_date').val('');
     $('#adv_end_date').val('');
    // $('#patientcode').val('');
    // $('#lab_reg_no').val('');
    // $('#mobile_no').val('');
    // $('#country_id').val('');
    // $('#city_id').val(''); 
    // $('#patient_name').val('');
    // $('#referral_doctor').val('');
    // $('#attended_doctor').val('');
    // $('#state_id').val('');
    // $('#pincode').val('');
    // $('#age_from').val('');
    // $('#age_to').val('');
    document.getElementById("test_search_form").reset();
     
    $.ajax({
           url: "<?php echo base_url('test/reset_date_search/'); ?>",  
           success: function(result)
           { 
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
 
$("#test_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
    
  $.ajax({
    url: "<?php echo base_url('test/search_date/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      var from_date = $('#adv_start_date').val();
      if(from_date=="")
      {
         $('#start_date').val(from_date);
      }

      var end_date = $('#adv_end_date').val();
      if(end_date=="")
      {
         $('#end_date').val(end_date);
      }
      $('#load_adv_search_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 
  $('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
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
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
  <script>
  $(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('test/get_test_name/'); ?>" + request.term,
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

          $('.test_ids').val(names[0]);
          $('#test_id').val(names[1]);
          

        return false;
    }

    $(".test_ids").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});
    
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
     
</div><!-- /.modal-dialog -->