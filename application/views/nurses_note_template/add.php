<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="diagnosis" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Name<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="name" id="name"  class="alpha_space inputFocus" value="<?php echo $form_data['name']; ?>" />
                    
                    <?php if(!empty($form_error)){ echo form_error('diagnosis'); } ?>
                </div>
              </div> <!-- innerrow -->
              <div class="row">
                <div class="col-md-4">
                    <label>Content</label>
                </div>
                <div class="col-md-8">
                    <textarea type="text" name="content" id="content"  class="alpha_space inputFocus ckeditor" value=""><?php echo $form_data['content']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('diagnosis'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                </div>
                <div class="col-md-8">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     
<script src="<?=base_url('assets/ckeditor/ckeditor.js')?>"></script>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#diagnosis").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Nurses Note Template successfully updated.';
  }
  else
  {
    var path = 'add';
    var msg = 'Nurses Note Template successfully created.';
  } 
  for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
  }

  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('nurses_note_template/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_diagnosis_modal_popup').modal('hide');
        var pathname = window.location.pathname;
        var parts = pathname.split('/');
        var desiredPart = parts[2] + '/' + parts[3];
        if(desiredPart == 'ipd_booking/nurses_notes') {
          location.reload();
        }
        if(urlWithoutDomain == '')
        flash_session_msg(msg);
        get_diagnosis();
        reload_table();
        
      } 
      else
      {
        $("#load_add_diagnosis_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_diagnosis_modal_popup').modal('hide');
});

function get_diagnosis()
{
   $.ajax({url: "<?php echo base_url(); ?>diagnosis/diagnosis_dropdown/", 
    success: function(result)
    {
      $('#diagnosis_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->

      <script>
        var basicToolbar = [
            { name: 'basicstyles', items: ['Bold', 'Italic'] },
            { name: 'editing', items: ['Scayt'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'insert', items: ['Table'] },
            // { name: 'tools', items: ['Maximize'] }
        ];
        var elements = document.querySelectorAll('.ckeditor');
        elements.forEach(function(element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        });

        // var element = document.querySelector('cause_of_death');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }

        // var element = document.querySelector('field_name[]');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }
        
      </script>