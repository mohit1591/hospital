<div class="cp-modal">
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-top"> 
    <form  id="document_files_form" class="form-inline" enctype="multipart/form-data"> 
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
    <input type="hidden" name="old_document_files" value="<?php echo $form_data['old_document_files']; ?>">
    <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
        <div class="modal-body">    
            

            <div class="row m-b1">
                <div class="col-xs-5">
                  <label>Document Name</label>
                </div>
                <div class="col-xs-5">
                    <input type="text" name="document_name" value="">
                 </div> 
              </div>

           <div class="row m-b1">
                <div class="col-xs-5">
                  <label>Document</label>
                </div>
                <div class="col-xs-5">
                    <input type="file" name="document_files">
                    
                    <?php if(!empty($form_error)){ echo form_error('document_files'); } ?>
                    
                    <?php
                    if(!empty($document_files_error))
                    {
                      echo '<div class="text-danger">'.$document_files_error.'</div>';
                    }

                    if(!empty($form_data['old_document_files']) && file_exists(DIR_UPLOAD_PATH.'patients/document/'.$form_data['old_document_files']))
                    {
                        $prescription_files = ROOT_UPLOADS_PATH.'patients/document/'.$form_data['old_document_files'];
                        echo '<img src="'.$prescription_files.'" width="100px" />';
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
 
$("#document_files_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var data_id  = $('#data_id').val();
  var patient_id  = $('#patient_id').val();
  if(data_id!="" && data_id>0)
  {
     var path = 'edit_document_file/'+data_id+'/'+patient_id;
     var msg = "Document successfully updated.";
  }
  else
  {
     var path = 'upload_document'+'/'+patient_id;
     var msg = "Document file successfully added.";
  }

  $.ajax({
    url: "<?php echo base_url('patient/'); ?>"+path,
    type: "post", 
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false, 
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg); 
      } 
      else
            {
        $("#load_add_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
</script>   
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->