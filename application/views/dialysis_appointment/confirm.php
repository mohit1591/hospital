<?php //print_r($form_data);die; ?>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="confirm_booking" class="form-inline">
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
              <div class="col-md-4">
                  <label><b>Dialysis Date</b></label>
                   </div>
                    <div class="col-md-8">
                     <input type="text" name="dialysis_date" class="datepicker" value="<?php echo $form_data['dialysis_date']; ?>" />
                   </div>
              <?php if(!empty($form_error)){ echo form_error('dialysis_date'); } ?>
            </div>
            <div class="row m-b-5">
              <div class="col-md-4">
                  <label><b>Booking Time</b></label>
                  </div>
                    <div class="col-md-8"> 
                    <input type="text" name="dialysis_time" class="datepicker3" value="<?php echo $form_data['dialysis_time']; ?>" />
                 </div>
              <?php if(!empty($form_error)){ echo form_error('dialysis_time'); } ?>
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
            
            <div class="row m-b-5">
            <div class="col-xs-4">
             <label> Room Type <sapn class="star">*</sapn></label></div>
        
           <div class="col-xs-8">
                        <select name="room_id" class="m_input_default" value="room_id" onchange="room_no_select(this.value);" id="room_id">
                            <option value="">-Select-</option>
                            <?php foreach($room_type_list as $room_type){?>
                            <option value="<?php echo $room_type->id; ?>" <?php if($form_data['room_id']==$room_type->id){ echo 'selected';}?>><?php echo $room_type->room_category; ?></option>
                            <?php }?>
                        </select>
                         <?php if(!empty($form_error)){ echo form_error('room_id'); } ?>
             </div>
        
        </div>
                
        <div class="row m-b-5">
            <div class="col-xs-4"><label>Room No. <sapn class="star">*</sapn> </label></div>
        
           <div class="col-xs-8">
                    <select name="room_no_id" class="m_input_default" id="room_no_id" onchange="select_no_bed(this.value);">
                        <option value="">-Select-</option>
                    </select>
                     <?php if(!empty($form_error)){ echo form_error('room_no_id'); } ?>
                </div>
            </div>
                
        <div class="row m-b-5">
            <div class="col-xs-4"><label>Bed No. <sapn class="star">*</sapn></label></div>
        
           <div class="col-xs-8">
                <select name="bed_no_id" class="m_input_default" id="bed_no_id">
                    <option value="">-Select-</option>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('bed_no_id'); } ?>
            </div>
        </div>

            <div class="row m-b-5">
          <div class="col-md-4">
              <label>Advance Amount</label>
          </div>
          <div class="col-md-8">
              <input type="text" name="advance_amount" id="advance_amount"  value="<?php echo $form_data['advance_amount']; ?>"><?php if(!empty($form_error)){ echo form_error('advance_amount'); } ?>
          </div>
        </div> <!-- row -->  
            
            <div class="row m-b-5">
              <div class="col-md-4">
                  <label>Payment Mode</label>
              </div>
              <div class="col-md-8">
                <select  name="payment_mode" onChange="payment_function(this.value,'');">
                <?php foreach($payment_mode as $payment_mode) 
                {?>
                <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                <?php }?>

                </select>

              </div>
              <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
            </div> <!-- row-->


            <div id="updated_payment_detail">
            <?php if(!empty($form_data['field_name']))
            { foreach ($form_data['field_name'] as $field_names) {
            $tot_values= explode('_',$field_names);

            ?>

            <div class="row m-b-5" id="branch"> 
                <div class="col-md-4">
                <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                </div>
                <div class="col-md-8"> 
                <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                <?php 
                if(empty($tot_values[0]))
                {
                if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                }
                ?>
                </div>
            </div>
            <?php } }?>

            </div>

            <div id="payment_detail">


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


$("#confirm_booking").on("submit", function(event) {  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
      
        $.ajax({
                url: "<?php echo base_url('dialysis_appointment/confirm_booking/'); ?>"+ids,
                type: "post",
                data: $(this).serialize(),
                success: function(result) {
                  if(result==1)
                  {
                    var msg = 'Appointment confirm successfully.';
                    $('#load_add_modal_popup').modal('hide');
                    flash_session_msg(msg); 
                    window.location ="<?php echo base_url('dialysis_booking/?status=print'); ?>";
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