
  <div class="modal-dialog" style="width:auto;max-width:500px;">
    <div class="modal-content">
      <form id="edit_icd" method="post" class="form-inline">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4><?php  echo $page_title;?></h4>
      </div>
      <div class="modal-body">
        <div id="attach_data">
        <p class="text-center" >This Diagnosis is attached with <i><?php echo $attached_diagnosis; ?> (<?php echo $icd_code; ?>)</i></p>
      </div>
        <input type="hidden" class="form-control" name="type" value="<?php echo $type; ?>" id="type_id">
        <div class="row">
          <div class="col-md-2"></div>
          <div class="col-md-8">
            <div class="row">
              <div class="col-sm-4">Icd name :</div>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="icd_name" name="icd_name" value="<?php echo $form_data['icd_name']; ?>" onkeyup="validate(this.value);">
                <span id="icd_error" class="text-danger"></span>

                <input type="hidden" class="form-control" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
              </div>
            </div>
      
             <div class="row m-t-5">
              <div class="col-sm-4"> Eye Type :</div>
              <div class="col-sm-8">
                <select name="eye_type" id="eye_type">
                     <option value=""> Select Eye</option>
                    <option <?php if($form_data['eye_type']=='L'){ echo 'selected';}?> value="L">Left Eye</option>
                    <option <?php if($form_data['eye_type']=='R'){ echo 'selected';}?> value="R">Right Eye</option>
                    <option <?php if($form_data['eye_type']=='BE'){ echo 'selected';}?> value="BE">Both Eye</option>
                </select> 
                <input type="hidden" class="form-control" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
              </div>
            </div>

          </div>
          <div class="col-md-2"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn-save">Save</button>
        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
  </div>
  <script>
    $(document).ready(function(){
     var type= $('#type_id').val();

     if(type==1)
     {
       $('#attach_data').hide();
     }
     else if(type==2)
     {
      $('#attach_data').show();
     }
     

    });
      $('#edit_icd').submit(function(event){
         event.preventDefault(); 
            $('#overlay-loader').show();
          if($('#icd_name').val().length==0)
            {

                var msg='Please fill the ICD name';
                $('#icd_error').text(msg);
                return false;
            }
          var msg='Custom ICD updated successfully';
          var form_data= $(this).serialize();
          var id=$('#data_id').val();
          var type= $('#type_id').val();
           $.ajax({
            url:"<?php echo base_url(); ?>eye/diagnosis_set/edit/"+id+'/'+type,
            type:"POST",
            data:form_data,
            success: function(result) {
               $('#modal-id').modal('hide'); 
                flash_session_msg(msg);
                reload_table();       
            }
        });
       });
      function validate(val)
    {
        if($('#icd_name').val().length > 0)
        {
             $('#icd_error').fadeOut();
        }
        else if($('#icd_name').val().length==0){
             $('#icd_error').fadeIn();
        }
       
    }
  </script>
