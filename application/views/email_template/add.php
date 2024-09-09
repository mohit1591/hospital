<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
 <div class="modal-content"> 
		<form id="email_form" class="form-inline">
			<input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
				<h4><?php echo $page_title; ?></h4> 
			</div>
	<div class="modal-body">  
		<div class="row">
			<div class="col-md-12"></div>
		</div>
    
		<div class="row m-b-5">
			<div class="col-md-3">
			  <label>Form Name<span class="star">*</span></label>
			</div>
			<input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
			<div class="col-md-9">
        
				<select class="inputFocus" name="form_name" id="form_name" onchange="get_template_according(this.value);" <?php if(!empty($form_data['data_id'])){ echo 'disabled="true"'; } ?>>
				<option value="">Select Form</option>
				<?php foreach($form_name as $type) { ?>   
				<option value="<?php echo $type->id; ?>" <?php if($form_data['form_name']==$type->id) {echo 'selected';}?> ><?php echo $type->var_title; ?></option>
				<?php }?>
             
				</select>
				<?php if(!empty($form_error)){ echo form_error('form_name'); } ?>
				<?php if(!empty($form_data['data_id']) && isset($form_data['data_id'])){ ?> <input type="hidden" name="form_name" value="<?php echo $form_data['form_name']; ?>"> <?php } ?>
			</div>
		</div> <!-- rowClose -->
      
      <div class="row m-b-5">
        <div class="col-md-3">
          <label>Email Subject</label>
        </div>
        <div class="col-md-9">
          <textarea name="subject" class="subject w-full" id="subject" cols="" rows=""><?php echo $form_data['subject'];?></textarea>
        </div>
      </div> <!-- rowClose -->


      <div class="row m-b-5">
        <div class="col-md-3">
          <label>Email Template</label>
        </div>
        <div class="col-md-9">
          <textarea name="message" class="message w-full" id="message"><?php echo $form_data['message'];?></textarea>
        </div>
      </div> <!-- rowClose -->

      <div class="row m-b-5">
		<div class="col-md-3">
			<label>Short Code</label>
		</div>
          <div class="col-md-9">
            <textarea name="short_code" readonly='' id="comment_data" class="w-full" ><?php echo $form_data['short_code'];?></textarea>
          </div>
      </div> <!-- rowClose -->
  
	</div>    <!--  modal-body --> 
	<div class="modal-footer"> 
		<input type="submit"  class="btn-update" name="submit" value="Save" />
		<button type="button" class="btn-cancel" data-number="2">Close</button>
	</div>
  </form>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script> 

<script>
$("#email_form").on("submit", function(event) { 
  event.preventDefault();
   
  $('.overlay-loader').show(); 
  var ids = $('#data_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Email template successfully updated.';
  }
  else
  {
     
    var path = 'add/';
    var msg = 'Email template successfully created.';
  } 

  for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    }
  

  $.ajax({
    url: "<?php echo base_url(); ?>email_template/"+path,
    type: "post",
    data: $('#email_form').serialize(),
    success: function(result) {
      if(result==1)
      {

        $('#load_add_elem_temp_modal_popup').modal('hide');
        reload_table();
        get_Element_Template();
        flash_session_msg(msg);
      } 
      else
            {
        $("#load_add_elem_temp_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
  function get_Element_Template()
  {
     $.ajax({url: "<?php echo base_url(); ?>email_template/email_template_dropdown/", 
      success: function(result)
      {
        
        $('#element_template_id').html(result); 
      } 
    });
  }
}); 

  function get_template_according(value)
  {
    
    $("#paper_dropdown").css("display","none"); 
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>email_template/email_template_dropdown/", 
      data: {value: value},
      datatype:'JSON',
        success: function(result)
        {
           var newdata = $.parseJSON(result);
           $('#subject').val(newdata.subject);
           CKEDITOR.instances['message'].setData(newdata.template);  
           //$('#message').val(newdata.template);
           $('#comment_data').val(newdata.short_code); 
        } 
      });

    
  }

$("button[data-number=2]").click(function(){
  $('#load_add_elem_temp_modal_popup').modal('hide');
});
</script>
 <script>

$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );


})

</script>
</div> 
   
</div><!-- /.modal-dialog -->