<?php //print_r($form_data);die; ?>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="reschedule_booking" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /><input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 

  <input type="hidden" name="attended_doctor" id="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>" />
   <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 
  <input type="hidden" name="consultant" id="consultant" value="<?php echo $form_data['consultant']; ?>" /> 
  
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close" ><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 
<input type="hidden" name="schedule_type" value="<?php echo $schedule_type; ?>">
        <?php //if($schedule_type=='0'){ ?>
             <div class="row m-b-5">
    
                 <div class="col-md-4"><b>Appointment Date <span class="star">*</span></b></div>
                 <div class="col-md-8">
                   <input type="text" id="dialysis_date" name="dialysis_date" class="datepicker_app" value="<?php echo $form_data['dialysis_date']; ?>" onchange="return get_available_days('',this.value);" />
                    <?php if(!empty($form_error)){ echo form_error('dialysis_date'); } ?>
                 </div>
               </div>
               
            <div class="row m-b-5">
              
                   <div class="col-xs-4"><b>Schedule </b><span class="star">*</span></div>
                 <div class="col-xs-8">
                   <select name="schedule_id" class="m_select_btn" id="schedule_id" onchange="return get_available_days('',this.value);">
                      <option value="">Select Schedule</option>
                      <?php
                         $schedule_list = dialysis_schedule_list($users_data['parent_id']); 
                        
                        if(!empty($schedule_list))
                        {
                           foreach($schedule_list as $doctor)
                           {  
                                
                            ?>   
                              <option value="<?php echo $doctor->id; ?>" < <?php if(!empty($form_data['schedule_id']) && $form_data['schedule_id'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->schedule_name; ?></option>
                            <?php
                             
                           }
                        }
                     
                    ?>
                    </select>
                    <?php if(!empty($form_error)){ echo form_error('schedule_id'); } ?> 
                 </div>
               </div>
        
          <!-- row -->
          
        
         <div class="row m-b-5" id="available_time" <?php if(empty($form_data['available_time'])){ ?> style="display: none;" <?php } ?> >
           
                 <div class="col-md-4"><b>Available Time</b><span class="star">*</span></div>
                 <div class="col-md-8">
                    <select name="available_time" class="m_select_btn" id="doctor_time" onchange="return select_a_slot(this.value);">
                      <option value="">Select time</option>
                      <?php
                        if(!empty($schedule_available_time))
                        {
                            foreach($schedule_available_time as $doctor_av_time)
                            { //date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time))
                                ?> 
                                <option <?php if($form_data['available_time']==$doctor_av_time->id){ echo 'selected="selected"';} ?> value="<?php echo $doctor_av_time->id; ?>"> <?php echo date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time)); ?> </option>
                                
                           <?php  }
                        } 
        
                      ?>
                    </select>
        <?php if(!empty($form_error)){ echo form_error('available_time'); } ?>
          </div>
          </div>
             <!-- row -->
        
          <div class="row m-b-5" id="available_slot"  <?php if(empty($form_data['doctor_slot'])){ ?> style="display: none;" <?php } ?> >
              
                 <div class="col-md-4"><b>Available Slot</b><span class="star">*</span></div>
                 <div class="col-md-8">
                    <select name="doctor_slot" class="m_select_btn" id="doctor_slot">
                      <!-- <option value="">Select time</option> -->
                      <?php echo $schedule_available_slot; ?>
                    </select>
        <?php if(!empty($form_error)){ echo form_error('doctor_slot'); } ?>
          </div>
          </div>
             <!-- row -->
        
          
          <div class="row m-b-5" id="doctor_avalaible" style="display: none;">
             
                 <div class="col-md-4"><b>Available Time</b></div>
                 <div class="col-md-8">
                 <span id="doctor_not_avalaible"></span>
        
          </div>
          </div>
            
            <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Dialysis name <span class="star">*</span></label>
             </div>
            
             <div class="col-xs-8">
              <select name="dialysis_name"  id="dialysis_name_id">
                <option value="">Select Dialysis</option>
                <?php foreach($dialysisn_list as $dy_list)
                {?>
                <option value="<?php echo $dy_list->id;?>" <?php if(isset($form_data['dialysis_name']) && $form_data['dialysis_name']== $dy_list->id){echo 'selected';}?>><?php echo $dy_list->name;?></option>
                <?php }?>
            
              </select>
            <?php if(!empty($form_error)){ echo form_error('dialysis_name'); } ?>
            </div>
            
            </div>
            
            
         
      
      </div> <!-- modal-body --> 

      <div class="modal-footer">
          <?php  if($schedule_type=='1'){ ?> 
      <input type="hidden" name="booking_date" class="datepicker" value="< ?php echo $form_data['booking_date']; ?>" /> <?php } ?>
         <button type="submit"  class="btn-update" name="submit">Save</button>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script> 
function get_available_days(schedule_id,date)
  {
      if(date!='')
      {
        var schedule_id = $('#schedule_id').val();
      }
      if(schedule_id!='')
      {
        var booking_date = $('#dialysis_date').val();
      }
      
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dialysis_appointment/get_schedule_available_days/", 
            dataType: "json",
            data: 'schedule_id='+schedule_id+'&booking_date='+booking_date,
            success: function(result)
            {
               
               if(result==1)
               {
                    
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>general/get_schedule_available_time/",
                      data: 'schedule_id='+schedule_id+'&booking_date='+booking_date, 
                      success: function(result)
                      {
                        $("#appointmenttime").val('');
                        $("#available_time").css("display", "block");
                        $("#doctor_avalaible").css("display", "none");
                        $("#dialysis_time").css("display","none");
                        $('#doctor_time').html(result); 
                        
                      } 
                    });
                   
                    
               }
               else if(result==0)
               {
                    $("#appointmenttime").val('');
                    $("#dialysis_time").css("display","none");
                    $("#available_time").css("display", "none");
                    $("#available_slot").css("display", "none");
                    $("#doctor_avalaible").css("display", "block");
                    
                    


                    $('#doctor_not_avalaible').html('<p style="color:red;">Schedule not available.</p>'); 
                }
                else if(result==2)
                {
                  $("#doctor_avalaible").css("display", "none");
                  $("#available_time").css("display", "none");
                  $("#available_slot").css("display", "none");
                  $("#dialysis_time").css("display","block");
                  //available_time
                  
                } 

            }

          });
  }


  function select_a_slot(vals)
  {      
        var time_id = vals;
        var schedule_id = $('#schedule_id').val();
        var booking_date = $('#dialysis_date').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>general/schedule_slot/", 
            data: 'schedule_id='+schedule_id+'&time_id='+time_id+'&booking_date='+booking_date,
            success: function(result)
            {
                
                $("#available_slot").css("display", "block");
                $('#doctor_slot').html(result); 
                //$('#doctor_slot').html(result); 
            }



          });
  }


    function room_no_select(value_room,room_no_id){
            $.ajax({
                url: "<?php echo base_url('dialysis_booking/select_room_number/'); ?>",
                type: "post",
                data: {room_id:value_room,room_no_id:room_no_id},
                success: function(result) 
                {
                  $('#room_no_id').html(result);
                }
            });
     }

     function select_no_bed(value_bed,bed_id){

        var room_id= $("#room_id option:selected").val();
        var ipd_id = $("#type_id").val();
        
        $.ajax({
                url: "<?php echo base_url('dialysis_booking/select_bed_no_number/'); ?>",
                type: "post",
                data: {room_id:room_id,room_no_id:value_bed,bed_id:bed_id,ipd_id:ipd_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                }
            });

     }


       function payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('dialysis_appointment/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#payment_detail').html(msg);
                     }
                });


           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           });  

      }
      $(document).ready(function(){
           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           }); 
      }); 


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


$("#reschedule_booking").on("submit", function(event) {  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
      
        $.ajax({
                url: "<?php echo base_url('dialysis_appointment/reschedule_appointment/'); ?>"+ids,
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                  if(result==1)
                  {
                    var msg = 'Appointment reschedule successfully.';
                    $('#load_add_modal_popup').modal('hide');
                    flash_session_msg(msg); 
                    
                  } 
                  else
                  {
                    $("#load_add_modal_popup").html(result);
                  }       
                  
                }
              });


  }
 
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_modal_popup').modal('hide');
});

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });

   $('#cheque_date').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });

  $('.datepicker3').datetimepicker({
      format: 'LT'
  });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->