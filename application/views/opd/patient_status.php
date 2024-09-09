<div class="modal-dialog emp-add-add p-t-40">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="patient_source" class="form-inline">
  <input type="hidden" name="data_id" id="opd_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">
              <div class="row m-b-5">
               <?php // echo "<pre>"; print_r($pat_data);?>
                <label class="col-md-12 text-center">Patient Details</label>
                <label class="col-md-5">Patient Name : </label>
                <div class="col-md-7">
                    <span><?php echo $pat_data['patient_name'];?></span>
                </div>
              </div>

              <div class="row m-b-5">
                <label class="col-md-5">Gender : </label>
                <div class="col-md-7">
                   <span><?php if($pat_data['gender']=='1'){ echo "Male";}else{ echo "Female"; }?></span>
                </div>
              </div>

              <div class="row m-b-5">
                <label class="col-md-5">Patient ID : </label>
                <div class="col-md-7">
                   <span><?php echo $pat_data['patient_code'];?></span>
                </div>
              </div>
              <div class="row m-b-5">
                <label class="col-md-5">Patient Contact : </label>
                <div class="col-md-7">
                   <span><?php echo $pat_data['mobile_no'];?></span>
                </div>
              </div>

              <div class="row m-b-5">
                <label class="col-md-5">Arrival Status : </label>
                <div class="col-md-7 btn-group">
                     <label class="btn-custom <?php if($pat_data['arrive']==1){echo 'bg-theme';}?> "><input type="checkbox" onchange="change_arrive(this.value);" <?php if($pat_data['arrive']==1){echo 'checked';}?> name="arrive" id="arrive" value="1"> Mark As Arrived</label> 
                </div>
              </div>

              <div class="row m-b-5">
                <label class="col-md-5"> Send To : </label>
                <div class="col-md-7 btn-group">
                 <label class="btn-custom <?php if($pat_data['reception']==1){ echo 'bg-theme';}?>"><input  onchange="change_reception(this.value);" type="checkbox" <?php if($pat_data['reception']==1){echo 'checked';}?> name="reception" id="reception" value="1"> Reception</label> 
                 <label class="btn-custom <?php if($pat_data['optimetrist']==1){ echo 'bg-theme';}?>"><input  onchange="change_optimetrist(this.value);" type="checkbox" <?php if($pat_data['optimetrist']==1){echo 'checked';}?> name="optimetrist" id="optimetrist" value="1"> Optometrist</label> 
                 <label class="btn-custom <?php if($pat_data['doctor']==1){ echo 'bg-theme';}?>"><input  onchange="change_doctor(this.value);" type="checkbox" <?php if($pat_data['doctor']==1){echo 'checked';}?> name="doctor" id="doctor" value="1"> Doctor</label> 
                 <label class="btn-custom <?php if($pat_data['completed']==1){ echo 'bg-theme';}?>"><input  onchange="change_completed(this.value);" type="checkbox" <?php if($pat_data['completed']==1){echo 'checked';}?> name="completed" id="completed" value="1"> Completed</label>
               </div> 
             </div>

               <input type="hidden" name="data_id" id="data_id" value="<?php echo $pat_data['id'];?>">
               <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $pat_data['patient_ids'];?>">
               <input type="hidden" name="book_id" id="book_id" value="<?php echo $pat_data['booking_id'];?>">
               <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $pat_data['branch_ids'];?>">

               <div class="app_app">
                <?php if($pat_data['arrive']==1){?>
                 <a href="javascript:void(0);" class="bg-danger">Arrived</a>
                <?php } ?>
                 <?php if($pat_data['reception']==1){?>
                 <a href="javascript:void(0);" class="bg-grey">Reception</a>
                <?php } ?>
                 <?php if($pat_data['optimetrist']==1){?>
                 <a href="javascript:void(0);" class="bg-grey">Opt.Optom</a>
                <?php } ?>
                 <?php if($pat_data['doctor']==1){?>
                 <a href="javascript:void(0);" class="bg-info">Doctor</a>
                <?php } ?>
                 <?php if($pat_data['completed']==1){?>
                 <a href="javascript:void(0);" class="bg-success">Completed</a>
                <?php } ?>
              </div>
      
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
</form>     

<script>  

 
$("#patient_source").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
    var msg = 'Status successfully updated.';
 
  $.ajax({
    url: "<?php echo base_url(''); ?>opd/update_patient_status",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_eye_app_type').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_emp_type_modal_popup').modal('hide');
});

function change_arrive(val)
{
  if($('#arrive').is(':checked'))
  {
    $('#arrive').parent().addClass('bg-theme');
  }else{
      $('#arrive').parent().removeClass('bg-theme');
  }
}
function change_reception(val)
{
  if($('#reception').is(':checked'))
  {
    $('#reception').parent().addClass('bg-theme');
  }else{
      $('#reception').parent().removeClass('bg-theme');
  }
}
function change_optimetrist(val)
{
  if($('#optimetrist').is(':checked'))
  {
    $('#optimetrist').parent().addClass('bg-theme');
  }else{
      $('#optimetrist').parent().removeClass('bg-theme');
  }
}
function change_doctor(val)
{
  if($('#doctor').is(':checked'))
  {
    $('#doctor').parent().addClass('bg-theme');
  }else{
      $('#doctor').parent().removeClass('bg-theme');
  }
}
function change_completed(val)
{
 if($('#completed').is(':checked'))
  {
    $('#completed').parent().addClass('bg-theme');
  }else{
      $('#completed').parent().removeClass('bg-theme');
  }
}

</script>  
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->