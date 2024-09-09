<script>
 $('.datepicker').datepicker({
     dateFormat: 'dd-mm-yy',
  //format: 'dd-mm-yyyy', 
  autoclose: true, 
});
 

 $('.datepicker3').datetimepicker({
   format: 'LT'
 });

</script>
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
                  <?php if($type=='1'){ ?>
                  <label>Discharge date:</label>
                  <?php }else { ?>

                    <?php  } ?>
                </div>
                <div class="col-md-8">
                  <input type="hidden" name="ipd_id" value="<?php echo $ipd_id;?>">
                  <input type="hidden" name="patient_id" value="<?php echo $patient_id;?>">
                  <?php if($type=='1'){ ?>
                  <input type="text" name="discharge_date" class="datepicker" id="discharge_date" value="<?php echo $discharge_date; ?>" style="width:60%">
                  <input type="text" name="discharge_date_time" class="w-65px datepicker3 m_input_default"  value="<?php echo $discharge_date_time; ?>">
                  <?php }else { ?> 

                    <input type="hidden" name="discharge_date" class="datepicker" id="discharge_date" value="<?php echo $discharge_date; ?>" style="width:60%">
                  <input type="hidden" name="discharge_date_time" class="w-65px datepicker3 m_input_default"  value="<?php echo $discharge_date_time; ?>">
                    <?php  } ?>
                    <input type="hidden" name="type" value="<?php echo $type; ?>">
                </div>
              </div>
            </div>

            
          </div> <!-- 12 -->
        </div> <!-- row -->  

        <div class="modal-footer">
              <div style="float:right;width:60%;display:inline-flex;">
                <?php if($type=='1'){ ?>

                  <button type="button" data-dismiss="modal" class="btn-update" id="discharge_now">Discharge</button>

                 <?php }else { ?> 

                  <button type="button" data-dismiss="modal" class="btn-update" id="discharge_now">Modify</button>
                 <?php  } ?>
                
                <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>

              </div>
            </div>
      </form>

    </div> <!-- 12 -->
  </div> <!-- row --> 

  <script>

    $('#discharge_now').on('click',function(e){  

       e.preventDefault();
       $('#overlay-loader').show();
       $.ajax({
        'type':'POST',
        'url':'<?php echo base_url('ipd_booking/update_discharge_data');?>',
        'data':$( "#discharge_date_form" ).serialize(),
        'dataType':'json',
        success:function(result)
        {
            //$(".loader_modal").hide();
            if(result.success==1)
            {  
              window.location.href='<?php echo base_url('ipd_discharge_bill/discharge_bill_info') ?>/'+result.ipd_id+'/'+result.patient_id+'/'+result.discharge_date;
              //flash_session_msg(result.msg);
              $('#load_discharge_modal_popup').modal('hide');
              //$('#overlay-loader').hide();
              
            }

            $('#overlay-loader').hide();
            
          }
        });
     });



   </script>