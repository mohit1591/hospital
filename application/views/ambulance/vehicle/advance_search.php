 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_vehicle" class="form-inline"> 
      
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
                     <!--   <div class="grp">
                          <label>Booking From Date</label>
                          <input type="text" class="datepicker start_date_booking"  id="start_date" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                          
                      
                         <div class="grp">
                          <label> Booking To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_booking" id="end_date" value="<?php echo $form_data['end_date']; ?>">
                        </div>-->
                       <!--  <div class="grp">
                          <label>Patient Name</label>
                         
                              <input type="text" name="patient_name" id="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                          
                        </div> -->
                        <div class="grp">
                          <label>Vehicle No.</label>
                          <input type="text" name="vehicle_no" id="vehicle_no" id="vehicle_no" value="<?php echo $form_data['vehicle_no']; ?>" />
                        </div>
                         <div class="grp">
                          <label>Chassis No.</label>
                          <input type="text" name="chassis_no" id="chassis_no" id="chassis_no" value="<?php echo $form_data['chassis_no']; ?>" />
                        </div>

                           <div class="grp">
                          <label>Engine No.</label>
                          <input type="text" name="engine_no" id="engine_no" id="engine_no" value="<?php echo $form_data['engine_no']; ?>" />
                        </div>
                        
                         <div class="grp">
                          <label>Location</label>
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
                </div>

                     <div class="inner"> 
                      
                         <div class="grp">
                          <label>Reg Date</label>
                          <input type="text" class="datepicker start_date_booking"  id="start_date" name="reg_date" value="<?php echo $form_data['reg_date']; ?>">
                        </div>
                          
                      
                         <div class="grp">
                          <label>Reg. Exp.</label>
                          <input type="text" name="reg_exp" class="datepicker end_date_booking" id="reg_exp" value="<?php echo $form_data['reg_exp']; ?>">
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
                            
                         <div class="grp" >
                          <label>Leased Owner Name.</label>
                           <select name="owner_name" id="location">
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

                       </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               
<input value="Reset" onclick="reset_search()" type="button" class="btn-reset">
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
 
$("#search_form_vehicle").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ambulance/vehicle/advance_search/'); ?>",
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
  });

  $('.end_date_booking').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });
  /*
   $('.reg_date').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });*/

    function reset_search()
{ 
  $('#start_date').val('');
  $('#end_date').val('');
  $('#vehicle_no').val('');
/*  $('#driver_id').val(''); 
  $('#patient_name').val('');*/
  $('#location').val('');

  $.ajax({url: "<?php echo base_url(); ?>ambulance/vehicle/reset_search/", 
    success: function(result)
    { 
           $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
      //document.getElementById("search_form").reset(); 

     
    } 
  }); 
}

 $(document).ready(function(){
  $('#load_add_type_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

function type_modal()
  {
      var $modal = $('#load_add_type_modal_popup');
      $modal.load('<?php echo base_url().'ambulance/vehicle/add_type/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 


</script> 
<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->