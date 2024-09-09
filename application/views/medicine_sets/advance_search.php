 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_opd" class="form-inline"> 
      
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
                          <label>Booking From Date</label>
                          <input type="text" class="datepicker start_date_booking" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>

                        <div class="grp">
                          <label>Appointment From Date</label>
                          <input type="text" class="datepicker start_date_appointment" name="appointment_from_date" value="<?php echo $form_data['appointment_from_date']; ?>">
                        </div>

                        

                        <div class="grp">
                          <label>Time From</label>
                          <input type="text" class="datepicker3" name="start_time" value="<?php echo $form_data['start_time']; ?>">
                        </div>
                        

                          
                        <div class="grp">
                          <label> Branch </label>
                          <?php     $users_data = $this->session->userdata('auth_users');
                                    $sub_branch_details = $this->session->userdata('sub_branches_data');
                                    $parent_branch_details = $this->session->userdata('parent_branches_data');
                                    
                                ?>
                        <select name="branch_id" id="branch_id">
                           <option value="">Select Branch</option>
                           
                           <option  selected="selected" value="<?php echo $users_data['parent_id'];?>">Self</option>';
                             <?php 
                             if(!empty($sub_branch_details)){
                                 $i=0;
                                foreach($sub_branch_details as $key=>$value){
                                     ?>
                                     <option value="<?php echo $sub_branch_details[$i]['id'];?>"><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                                     <?php 
                                     $i = $i+1;
                                 }
                               
                             }
                            ?> 
                            </select>
                        </div>



                        <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code" class="inputFocus" value="<?php echo $form_data['patient_code']; ?>">
                        </div>

                        <!-- <div class="grp">
                          <label>Booking Amount From</label>
                          <input type="text" name="amount_from"  value="< ?php echo $form_data['amount_from']; ?>">
                        </div> 

                        <div class="grp">
                          <label>Paid Amount From </label>
                          <input type="text" name="paid_amount_from"  value="< ?php echo $form_data['paid_amount_from']; ?>">
                        </div>  -->
                        
                        <div class="grp">
                          <label>Patient Name</label>
                          <div class="rslt">
                               <select name="simulation_id" id="simulation_id" class="mr" onchange="find_gender(this.value)">
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
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                       <div class="grp">
                           <label>Gender</label>
                          <div class="rslt" id="gender">
                            <input type="radio" name="gender" value="1" <?php if(isset($form_data['gender']) && $form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                            <input type="radio" name="gender" value="0" <?php if(isset($form_data['gender']) && $form_data['gender']!="" && $form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                             <input type="radio" name="gender" value="2" <?php if(isset($form_data['gender']) && $form_data['gender']!="" && $form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
                          </div>
                        </div>


                        <div class="grp">
                           <label>Age </label>
                          <div class="rslt">
                           <input type="text" name="age_y" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_y']; ?>"> To
                            <input type="text" name="age_m" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> 
                          </div>
                        </div>
                        
                        <div class="grp">
                          <label>Address</label>
                          <textarea name="address" id="address" maxlength="255"><?php echo $form_data['address']; ?></textarea>
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
                          <label>Pin Code</label>
                          <input type="text" name="pincode" id="pincode" value="<?php echo $form_data['pincode']; ?>" maxlength="6" onkeypress="return isNumberKey(event);">
                        </div>

                                               
                        
                        
                        
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label> Booking To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_booking" value="<?php echo $form_data['end_date']; ?>">
                        </div>

                        <div class="grp">
                          <label>Appointment To Date</label>
                          <input type="text" class="datepicker end_date_appointment" name="appointment_to_date" value="<?php echo $form_data['appointment_to_date']; ?>">
                        </div>

                        
                        
                        <div class="grp">
                          <label>Time To</label>
                          <input type="text" class="datepicker3" name="end_time" value="<?php echo $form_data['end_time']; ?>">
                        </div>  
                        
                         
                        <div class="grp">
                          <label>OPD NO.</label>
                          <input type="text" name="booking_code" id="booking_code" value="<?php echo $form_data['booking_code']; ?>" />
                        </div>

                        <!-- <div class="grp">
                          <label>Booking Amount To</label>
                          <input type="text" name="amount_to"  value="< ?php echo $form_data['amount_to']; ?>">
                        </div>

                        <div class="grp">
                          <label>Paid Amount To </label>
                          <input type="text" name="paid_amount_to"  value="< ?php echo $form_data['paid_amount_to']; ?>">
                        </div>  -->

                        <div class="grp">
                          <label>Patient Email</label>
                          <input type="text" name="patient_email" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
                        </div>

                       <div class="grp">
                          <label>Disease</label>
                          <input type="text" name="disease" id="disease" value="<?php echo $form_data['disease']; ?>" />
                        </div>

                         <div class="grp">
                          <label>ICD-10</label>
                          <input type="text" name="disease_code" id="disease_code" value="<?php echo $form_data['disease_code']; ?>" />
                        </div>

                        <div class="grp">
                          <label>Referred By</label>
                          <select name="referral_doctor" id="referral_doctor" >
                              <option value="">Select Referred By</option>
                              <?php
                              if(!empty($referal_doctor_list))
                              {
                                foreach($referal_doctor_list as $referal_doctor)
                                {
                                  ?>
                                    <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select> 
                        </div>
                          
                        

                        <div class="grp">
                          <label>Specialization</label>
                          <select name="specialization_id" id="specialization_id" onChange="return get_doctor_specilization(this.value);">
                              <option value="">Select Specialization</option>
                              <?php
                              if(!empty($specialization_list))
                              {
                                foreach($specialization_list as $specializationlist)
                                {
                                  ?>
                                    <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select>  
                        </div> 
                      <div class="grp">
                          <label>Consultant</label>
                        <select name="attended_doctor" id="attended_doctor">
                            <option value="">Select Attended By</option>
                            <?php
                           if(!empty($form_data['specialization_id']))
                           {
                              $doctor_list = doctor_specilization_list($form_data['specialization_id']); 
                              
                              if(!empty($doctor_list))
                              {
                                 foreach($doctor_list as $doctor)
                                 {  
                                  ?>   
                                    <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                                  <?php
                                 }
                              }
                           }
                          ?>
                          </select>
                        </div>

                        <div class="grp">
                          <label>Source From</label>
                          <select name="source_from" id="source_from" >
                              <option value="">Select Source</option>
                              <?php
                              if(!empty($source_list))
                              {
                                foreach($source_list as $sources)
                                {
                                  ?>
                                    <option <?php if($form_data['source_from']==$sources->id){ echo 'selected="selected"'; } ?> value="<?php echo $sources->id; ?>"><?php echo $sources->source; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select> 
                        </div>
                        <div class="grp">
                          <label>Booking Status</label>
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="booking_status" value="1" <?php if(isset($form_data['booking_status']) && $form_data['booking_status']==1){ echo 'checked="checked"'; } ?>>   Confirm
                            </div>
                            <div class="gen"> 
                              <input type="radio" name="booking_status" value="0" <?php if(isset($form_data['booking_status']) && $form_data['booking_status']!="" && $form_data['booking_status']==0){ echo 'checked="checked"'; } ?>> Pending 
                            </div>
                            
                          </div>
                        </div>

                        <div class="grp">
                          <label>&nbsp;</label>
                          <div class="rslt">
                           <div class="gen"> 
                              <input type="radio" name="booking_status" value="2" <?php if(isset($form_data['booking_status']) && $form_data['booking_status']!="" && $form_data['booking_status']==2){ echo 'checked="checked"'; } ?>> Attended
                            </div>
                          </div>
                        </div>

                        <div class="grp">
                          <label>Status</label>
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="status" value="1" <?php if(isset($form_data['status']) && $form_data['status']==1){ echo 'checked="checked"'; } ?>> Active
                            </div>
                            <div class="gen"> 
                              <input type="radio" name="status" value="0" <?php if(isset($form_data['status']) && $form_data['status']!="" && $form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
                            </div>
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
$(document).ready(function(){
   var simulation_id = $("#simulation_id :selected").val();
    find_gender(simulation_id);
});
//function to find gender according to selected simulation
 function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }
 //ends
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
  
  $.ajax({url: "<?php echo base_url(); ?>opd/reset_search/", 
    success: function(result)
    {
      $("#search_form_opd").reset();
      reload_table();
    } 
  }); 
}

function get_doctor_specilization(specilization_id)
  {   

    $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
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
 
$("#search_form_opd").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('opd/advance_search/'); ?>",
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
 
 /*/ $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    //endDate : new Date(),
    autoclose: true
  })
  */

$('.start_date_booking').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_date_booking').val();
      $('.end_date_booking').datepicker('setStartDate', start_data); 
  });

  $('.end_date_booking').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });


  $('.start_date_appointment').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_date_appointment').val();
      $('.end_date_appointment').datepicker('setStartDate', start_data); 
  });

  $('.end_date_appointment').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });



 $('.datepicker3').datetimepicker({
      format: 'LT'
  });
</script> 

<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->