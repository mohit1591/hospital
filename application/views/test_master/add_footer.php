<div class="cp-modal">
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-top"> 
    <form  id="signature_form" class="form-inline" enctype="multipart/form-data"> 
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
    <input type="hidden" name="old_sign_img" value="<?php echo $form_data['old_sign_img']; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">    
           
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Department<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                     <select name="dept_id">
                        <option value="">Select Department</option>
                              <?php
                               if(!empty($dept_list))
                               {
                                  foreach($dept_list as $dept)
                                  {
                                      $dept_select = "";
                                      if($dept->id==$form_data['dept_id'])
                                      {
                                          $dept_select = "selected='selected'";
                                      }
                                      echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                  }
                               }
                              ?>
                      </select>
                      <?php if(!empty($form_error)){ echo form_error('dept_id'); } ?>
                </div>
              </div> <!-- row -->
              
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Technician<span class="star">*</span></label>
                </div>
                <div class="col-xs-7">
                    <select name="employee_id">
                        <option value="">Select Technician</option>
                        <?php
                         if(!empty($employee_list))
                         {
                            foreach($employee_list as $employee)
                            {
                                $employee_select = "";
                                if($employee->id==$form_data['employee_id'])
                                {
                                    $employee_select = "selected='selected'";
                                }
                                echo '<option value="'.$employee->id.'" '.$employee_select.'>'.$employee->name.'</option>';
                            }
                         }
                        ?>
                    </select>
                    <?php if(!empty($form_error)){ echo form_error('employee_id'); } ?>
                </div> 
              </div> <!-- row --> <!-- row -->
              
              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Signature</label>
                </div>
                <div class="col-xs-7">
                    <textarea name="signature"><?php echo $form_data['signature']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('signature'); } ?>
                </div>
              </div> <!-- row -->

              <div class="row m-b1">
                <div class="col-xs-5">
                    <label>Sign Image</label>
                </div>
                <div class="col-xs-7">
                    <input type="file" name="sign_img" accept="image/*">
                    <?php if(!empty($form_error)){ echo form_error('sign_img'); } ?>
                    
                    <?php
                    if(!empty($sign_error))
                    {
                      echo '<div class="text-danger">'.$sign_error.'</div>';
                    }

                    if(!empty($form_data['old_sign_img']) && file_exists(DIR_UPLOAD_PATH.'technician_signature/'.$form_data['old_sign_img']))
                    {
                        $sign_img = ROOT_UPLOADS_PATH.'technician_signature/'.$form_data['old_sign_img'];
                        echo '<img src="'.$sign_img.'" width="100px" />';
                    }
                    ?>

                </div> 
              </div> <!-- row -->

            </div> <!-- modal-body -->
          
             
             
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Save</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
        </div>
</form>     

<script>  
 
$("#signature_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var data_id  = $('#data_id').val();
  if(data_id!="" && data_id>0)
  {
     var path = 'edit_footer/'+data_id;
     var msg = "Doctor Signature successfully updated.";
  }
  else
  {
     var path = 'add_footer';
     var msg = "Doctor Signature successfully added.";
  }

  $.ajax({
    url: "<?php echo base_url('test_footer/'); ?>"+path,
    type: "post", 
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false, 
    success: function(result) {
      if(result==1)
      {
        $('#load_add_footer_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg); 
      } 
      else
            {
        $("#load_add_footer_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
</script>   
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->