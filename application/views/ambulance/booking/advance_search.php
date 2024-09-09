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
                          <label> Booking To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_booking" value="<?php echo $form_data['end_date']; ?>">
                        </div>



                     <!--     
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
                        </div>-->



                        <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code" class="inputFocus" value="<?php echo $form_data['patient_code']; ?>">
                        </div>

                     
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
                          <label>Patient Email</label>
                          <input type="text" name="patient_email" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
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
                          <label>Booking No.</label>
                          <input type="text" name="booking_no" id="booking_no" value="<?php echo $form_data['booking_no']; ?>" />
                        </div>
                        
                         <div class="grp">
                          <!-- <div class="col-md-9 col-md-push-3">-->
                          
                              <label class="btn btn-sm">
                              <input type="radio"  name="vehicle_type"   value="1" <?php if($form_data['vehicle_type']==1){?>checked="checked" <?php }?> >
                              <span>Self Owned </span>
                              </label>
                             
                              <label class="btn btn-sm">
                              <input type="radio"  name="vehicle_type"  value="2" <?php if($form_data['vehicle_type']==2){ ?>checked="checked" <?php } ?> >
                              <span>Leased</span>
                              </label>
                          <!-- </div>-->
                        
                        </div>
                            

                        
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>Referred By Doctor</label>
                          <select name="referral_doctor" id="referal_doctor" >
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
                          <label>Referred By Hospital</label>
                          <select name="referral_hospital" id="referal_hospital" >
                              <option value="">Select Referred By</option>
                              <?php
                              if(!empty($referal_hospital_list))
                              {
                                foreach($referal_hospital_list as $referal_hospital)
                                {
                                  ?>
                                    <option <?php if($form_data['referral_hospital']==$referal_hospital['id']){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital['id']; ?>"><?php echo $referal_hospital['hospital_name']; ?></option>
                                  <?php
                                }
                              }
                              ?>
                            </select> 
                        </div>
                          <div class="grp">
                          <label>Driver Name</label>
                          <input type="text" name="driver_name" id="driver_name" value="<?php echo $form_data['driver_name']; ?>" />
                        </div>

                     <div class="grp">
                          <label>Location</label>
                         <!-- <input type="text" name="location" id="location" value="<?php echo $form_data['location']; ?>" />-->
                          <select name="location" id="location">
                                <option value="">Select</option>
                                <?php
                              if(!empty($location_list))
                              {
                                foreach($location_list as $location)
                                {
                                  $selected_location = "";
                                  if($location->id==$form_data['location'])
                                  {
                                    $selected_location = "selected='selected'";
                                  }
                                  echo '<option value="'.$location->id.'" '.$selected_location.'>'.$location->location_name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                        </div>

                        <div class="grp">
                          <label>Pick From</label>
                          <input type="text" name="source_from"  value="<?php echo $form_data['source_from']; ?>">
                        </div>
                          <div class="grp">
                          <label>Drop To</label>
                          <input type="text" name="destination"  value="<?php echo $form_data['destination']; ?>">
                        </div>
                          <div class="grp">
                          <label>Distance type</label>
                           <div class="rslt" id="gender">
                      
                            <input type="radio" name="distance_type" value="0" <?php if(isset($form_data['distance_type']) && $form_data['distance_type']==0){ echo 'checked="checked"'; } ?>> Local &nbsp;
                            <input type="radio" name="distance_type" value="1" <?php if(isset($form_data['distance_type']) && $form_data['distance_type']!="" && $form_data['gender']==1){ echo 'checked="checked"'; } ?>> Outstation
                        </div> 
                        </div>
                        
                        

                         <div class="grp">
                          <label>Mode of Payment</label>
                        
                          <select name="payment_mode" id="payment_mode">
                                <option value="">Select</option>
                                <?php
                              if(!empty($payment_mode))
                              {
                                foreach($payment_mode as $payment_mode)
                                {
                                  $selected_location = "";
                                  if($payment_mode->id==$form_data['payment_mode'])
                                  {
                                    $selected_mode = "selected='selected'";
                                  }
                                  echo '<option value="'.$payment_mode->id.'" '.$selected_mode.'>'.$payment_mode->payment_mode.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                        </div>

                        <!--<div class="grp">
                          <label>&nbsp;</label>
                          <div class="rslt">
                           <div class="gen"> 
                              <input type="radio" name="booking_status" value="2" <?php if(isset($form_data['booking_status']) && $form_data['booking_status']!="" && $form_data['booking_status']==2){ echo 'checked="checked"'; } ?>> Attended
                            </div>
                          </div>
                        </div>-->

                        <!--<div class="grp">
                          <label>Status</label>
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="status" value="1" <?php if(isset($form_data['status']) && $form_data['status']==1){ echo 'checked="checked"'; } ?>> Active
                            </div>
                            <div class="gen"> 
                              <input type="radio" name="status" value="0" <?php if(isset($form_data['status']) && $form_data['status']!="" && $form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
                            </div>
                          </div>
                        </div>-->
                        
                              
                         <div class="grp" >
                          <label>Leased Owner Name.</label>
                           <select name="owner_name" onchange="change_vehicle_by_vendor(this.value);">
                                <option value="">Select</option>
                                <?php
                              if(!empty($owner_list))
                              {
                                foreach($owner_list as $owner)
                                {
                                  $selected_owner = "";
                                  if($owner->id==$form_data['owner_name'])
                                  {
                                    $selected_owner = "selected='selected'";
                                  }
                                  echo '<option value="'.$owner->id.'" '.$selected_owner.'>'.$owner->name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                        </div>
                        
                         <div class="grp">
                          <label>Vehicle No.</label>
                          <select name="vehicle_no" value="<?php echo $form_data['vehicle_no']?>" id="vehicle_nos">
                                <option value="">Select Vehicle No</option>
                              <?php
                              if(!empty($vehicle_list))
                              {
                                foreach($vehicle_list as $vehicle)
                                {
                                  ?>
                                  <option <?php if($form_data['vehicle_no']==$vehicle->id){ echo 'selected="selected"'; } ?> value="<?php echo $vehicle->id; ?>"><?php echo $vehicle->vehicle_no; ?></option>
            
                                  <?php
                                }
                              }
                              ?>
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
    url: "<?php echo base_url('ambulance/booking/advance_search/'); ?>",
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

 function change_vehicle_by_vendor(id)
 {
     $('#vehicle_nos').html('');
     $.ajax({
        url: "<?php echo base_url(); ?>ambulance/booking/vendor_vehicle_list/"+id,
        success: function(result) 
        {
            $('#vehicle_nos').html(result);
        }
      });
 }


</script> 

<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->