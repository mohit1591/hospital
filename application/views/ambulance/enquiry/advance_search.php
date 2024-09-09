 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_enquiry" class="form-inline"> 
      
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
                          <label>UHID No.</label>
                         
                              <input type="text" name="uhid_no" id="uhid_no" value="<?php echo $form_data['uhid_no']; ?>" class="p-name">
                          
                        </div>
                        
                        <!--<div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code" class="inputFocus" value="<?php echo $form_data['patient_code']; ?>">
                        </div>-->

                        
                        <div class="grp">
                          <label>Patient Name</label>
                         
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                          
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
                          <label>Patient Email</label>
                          <input type="text" name="patient_email" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
                        </div>

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label> Booking To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_booking" value="<?php echo $form_data['end_date']; ?>">
                        </div>

                      
                         
                        <div class="grp">
                          <label>Vehicle No.</label>
                          <input type="text" name="vehicle_no" id="vehicle_no" value="<?php echo $form_data['vehicle_no']; ?>" />
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
                          <label>Drop To </label>
                          <input type="text" name="destination"  value="<?php echo $form_data['destination']; ?>">
                        </div> 
                         <div class="grp">
                          <label>Distance type</label>
                           <div class="rslt" id="gender">
                         <!-- <input type="text" name="distance_type"  value="<?php echo $form_data['distance_type']; ?>">-->
                            <input type="radio" name="distance_type" value="0" <?php if(isset($form_data['distance_type']) && $form_data['distance_type']==0){ echo 'checked="checked"'; } ?>> Local &nbsp;
                            <input type="radio" name="distance_type" value="1" <?php if(isset($form_data['distance_type']) && $form_data['distance_type']!="" && $form_data['gender']==1){ echo 'checked="checked"'; } ?>> Outstation
                        </div> 
                        </div>
                         <div class="grp">
                          <label>Enquiry Status</label>
                            <select name="enquiry_status" id="enquiry_status">
                                <option value="">Select</option>
                                <option value="1" <?php if($form_data['enquiry_status']==1){echo "selected";} ?>>Confirmed</option>
                                <option value="2" <?php if($form_data['enquiry_status']==2){echo "selected";} ?>>Pending</option>
                                <option value="3" <?php if($form_data['enquiry_status']==3){echo "selected";} ?>>Cancelled</option>
                               
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
               
<input value="Reset" onclick="reset_search();" type="button" class="btn-reset">
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
  
  $.ajax({url: "<?php echo base_url(); ?>ambulance/enquiry/reset_search/", 
    success: function(result)
    {
     $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
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
 
$("#search_form_enquiry").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ambulance/enquiry/advance_search/'); ?>",
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