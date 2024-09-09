<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="room_type_update" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-3">
                    <label>Title</label>
                </div>
                <div class="col-md-9">
                    <input type="text" name="title"  id="title" value="<?php echo $form_data['title']; ?>" class="alpha_space inputFocus">
                    
                    <?php if(!empty($form_error)){ echo form_error('title'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 

       

    <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
            <label>Template Header</label>
          </div>
          <div class="col-md-9">
       <textarea name="template_header" class="template" id="template_header" cols="45"><?php echo $form_data['template_header'];?></textarea>
     <?php if(!empty($form_error)){ echo form_error('template_header'); } ?>
          </div>
        </div>
				
            </div> <!-- 12 -->
          </div> <!-- row -->  

          <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
            <label>Template</label>
          </div>
          <div class="col-md-9">
       <textarea name="template" class="template" id="template_id" cols="45"><?php echo $form_data['template'];?></textarea>
     <?php if(!empty($form_error)){ echo form_error('template'); } ?>
          </div>
        </div>
				
            </div> <!-- 12 -->
          </div> <!-- row -->  

<!-- <div class="row">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-3">
            <label>Short Code</label>
          </div>
          <div class="col-md-9">
       <textarea name="short_code" class="short_code" id="short_code" cols="45" placeholder="{patient_name}&nbsp;{doctor_name}&nbsp;{mother}&nbsp;{father_husband}&nbsp;{country}&nbsp;{city}&nbsp;{state}&nbsp;{address}&nbsp;{dob}&nbsp;{signature}&nbsp;{sign_img}"></textarea>
    
          </div>
        </div>
        
            </div> 
          </div>  -->

      </div> <!-- modal-body --> 
 
      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>   

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 //CKEDITOR.replace( 'template' );
$("#room_type_update").on("submit", function(event) { 
  for (instance in CKEDITOR.instances)
  CKEDITOR.instances[instance].updateElement();
 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Certificate successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Certificate successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('certificate/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ipd_room_type_modal_popup').modal('hide');
       
        reload_table();
        flash_session_msg(msg);
         get_room_type();
      } 
      else
      {
        $("#load_add_ipd_room_type_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ipd_room_type_modal_popup').modal('hide');
});

/*function get_Room_Type()
{
   $.ajax({url: "<?php echo base_url(); ?>Room Type/Room Type_dropdown/", 
    success: function(result)
    {
      $('#room_category_id').html(result); 
    } 
  });
}*/
</script>  
<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'template_id', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

CKEDITOR.replace( 'template_header', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})

</script>  

<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->