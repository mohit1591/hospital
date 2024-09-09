<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
     <div class="modal-content"> 
          <form  id="email_form" class="form-inline">
               <input type="hidden" name="elem_id" id="elem_id" value="<?php echo $form_data['elem_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">  
               <!-- ============================================================================================ -->
                    <div class="row m-b-5">
                      <div class="col-xs-12">

                          <div class="row m-b-5">
                              <div class="col-md-5">
                                  <strong> Sms Template Name <span class="star">*</span></strong>
                              </div>
                              <div class="col-md-7">
                                   <input type="text"  name="form_name" value="<?php echo $form_data['form_name']; ?>">
                                  <?php if(!empty($form_error)){ echo form_error('form_name'); } ?>
                              </div>
                          </div>

                            
                            <div class="row m-b-5">
                               <div class="col-md-5">
                                    <strong>Sms Template <span class="star">*</span></strong>
                               </div>
                               <div class="col-md-7">
                                    <textarea type="text" name="sms_template" id="sms_template"><?php echo $form_data['sms_template']; ?></textarea>
                                    <?php if(!empty($form_error)){ echo form_error('sms_template'); } ?>
                               </div>
                            </div>

                            <div class="row">
                               <div class="col-md-5">
                                    <strong>Status  <span class="star">*</span></strong>
                               </div>
                               <div class="col-md-7">
                                     <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active 
                                    <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive  
                               </div>
                            </div>

                           
                              
                      </div> <!-- 12 -->
                      
                    </div> <!-- row -->


                    
                        
                         

               </div>    <!--  modal-body --> 
               <div class="modal-footer"> 
                    <input type="submit"  class="btn-update" name="submit" value="Save" />
                    <button type="button" class="btn-cancel" data-number="2">Close</button>
               </div>
          </form>  
 

<script> 


$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});


 

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


 
$("#email_form").on("submit", function(event) { 
    
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#elem_id').val();

 

  if(ids!="" && !isNaN(ids))
  { 
     
    var path = 'edit/'+ids;
    var msg = 'Sms Template successfully updated.';
  }
  else
  {
     
    var path = 'add/';
    var msg = 'Sms Template successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>sms_template/"+path,
    type: "post",
    data: $(this).serialize(),
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
   $.ajax({url: "<?php echo base_url(); ?>sms_template/Element_Template_dropdown/", 
    success: function(result)
    {
      
      $('#element_template_id').html(result); 
    } 
  });
}
}); 

  // $('.datepicker').datepicker({
  //   format: 'dd-mm-yyyy',
  //   endDate : new Date(),
  //   autoclose: true,
  //   startView: 2
  // })



$("button[data-number=2]").click(function(){
  
    $('#load_add_elem_temp_modal_popup').modal('hide');
 
   
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
      


   
</div><!-- /.modal-dialog -->
