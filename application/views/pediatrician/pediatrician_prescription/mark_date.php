<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<!-- <div class="modal-dialog" role="document" style="width:30%"> -->
  <div class="modal-dialog emp-add-add">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4><?php echo $title;?> </h4> 
      </div>

      <form id="discharge_date_form" method="post" action="">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12">
              <div class="row m-b-5">
                <div class="col-md-4">
                  <label>Mark date:</label></div>
                <div class="col-md-8">
                  <input type="hidden" name="age" value="<?php echo $age;?>">
                  <input type="hidden" name="age_id" value="<?php echo $age_id;?>">
                   <input type="hidden" name="vaccine_id" value="<?php echo $vaccine_id;?>">
                    <input type="hidden" name="booking_id" value="<?php echo $booking_id;?>">
                    <input type="hidden" name="patient_id" value="<?php echo $patient_id;?>">
                    <input type="hidden" name="attended_doctor" value="<?php echo $attended_doctor;?>">
                 
                  <!--<input type="text" name="booking_date" class="datepickercal" id="booking_date" value="< ?php echo $booking_date; ?>" style="width:60%">
                  <input type="text" name="booking_date_time" class="w-65px datepickercal3 m_input_default"  value="< ?php echo $booking_date_time; ?>">-->
                  
                  <input type="text" name="booking_date" class="datepicker date" data-date-format="dd-mm-yyyy HH:ii" value="<?php if(strtotime($booking_date)>86400 && $booking_date!='' && $booking_date!='0000-00-00:00:00:00' && $booking_date!='1970-01-01:05:30:00'){ echo $booking_date; } ?>" /> 

                 
                </div>
              </div>
            </div>

            
          </div> <!-- 12 -->
        </div> <!-- row -->  

        <div class="modal-footer">
              <div style="float:right;width:60%;display:inline-flex;">
                <button type="button" data-dismiss="modal" class="btn-update" id="discharge_now">Mark</button>
                <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>

              </div>
            </div>
      </form>

    </div> <!-- 12 -->
  </div> <!-- row --> 

  <script>

    $('#discharge_now').on('click',function(e){  
var msg = 'Marked successfully.';
       e.preventDefault();
       $('#overlay-loader').show();
       $.ajax({
        'type':'POST',
        'url':'<?php echo base_url('pediatrician/pediatrician_prescription/mark_vaccination/');?>',
        'data':$( "#discharge_date_form" ).serialize(),
        'dataType':'json',
        success:function(result)
        {
            if(result.success==1)
            {  
              flash_session_msg(msg);
              return window.location.reload(true);
              
            }

            $('#overlay-loader').hide();
            
          }
        });
     });



   </script>
   <script>
     $(".datepicker").datetimepicker({
      format: "dd-mm-yyyy HH:ii P",
      showMeridian: true,
      autoclose: true,
      todayBtn: true
  }).datetimepicker("setDate", new Date());
/* $('.datepickercal').datepicker({
     dateFormat: 'dd-mm-yy',
  //format: 'dd-mm-yyyy', 
  autoclose: true, 
});
 

 $('.datepickercal3').datetimepicker({
   format: 'LT'
 });
*/
</script>