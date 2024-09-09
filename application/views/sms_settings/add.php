<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
     <div class="modal-content"> 
          <form  id="email_form" class="form-inline">
               <input type="hidden" name="sms_set_id" id="sms_set_id" value="<?php echo $form_data['sms_set_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close p-t-0" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">  
               <!-- ============================================================================================ -->
                    <div class="row m-b-5">
                      <div class="col-xs-12">

                          <div class="row m-b-5">
                              <div class="col-md-4">
                                  <strong> URL of SMS <span class="star">*</span></strong>
                              </div>
                              <div class="col-md-8">
                                   <input type="text"  name="url_of_sms" value="<?php echo $form_data['url_of_sms']; ?>">
                                  <?php if(!empty($form_error)){ echo form_error('url_of_sms'); } ?>
                              </div>
                          </div>

                            
                            <div class="row m-b-5">
                               <div class="col-md-4">
                                    <strong>UserName<span class="star">*</span></strong>
                               </div>
                               <div class="col-md-8">
                                    <input type="text" name="username" id="username" value="<?php echo $form_data['username']; ?>" />
                                    <?php if(!empty($form_error)){ echo form_error('username'); } ?>
                                     <span href="javascript:void(0)"  data-toggle="tooltip"  title="Allow only alphanumeric." class="tooltip-text">
                                        <i class="fa fa-info-circle fainfo"></i>
                                        </span>
                               </div>
                            </div>

                            <div class="row m-b-5 ">
                                <div class="col-md-4">
                                    <strong>Password <span class="star">*</span></strong>
                               </div>
                               <div class="col-md-8">
                                    <input type="password" name="password" id="password" value="<?php echo $form_data['password']; ?>" />
                                    <?php if(!empty($form_error)){ echo form_error('password'); } ?>
                                     <span href="javascript:void(0)"  data-toggle="tooltip"  title="Password length should be 6-20 character only." class="tooltip-text">
                                        <i class="fa fa-info-circle fainfo"></i>
                                        </span>
                               </div>
                            </div>

                           
                            <div class="row">
                                 <div class="col-md-4">
                                    <strong> Sender Id<span class="star">*</span></strong>
                               </div>
                               <div class="col-md-8">
                                    <input type="text"   name="sender_id" id="sender_id" value="<?php echo $form_data['sender_id']; ?>" />
                                    <?php if(!empty($form_error)){ echo form_error('sender_id'); } ?>
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
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
    
    });   
}); 
</script>
<script> 


 

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
  var ids = $('#sms_set_id').val();
  
  if(ids!="" && !isNaN(ids))
  { 
     
    var path = 'edit/'+ids;
    var msg = 'SMS Settings successfully updated.';
  }
  else
  {
     
    var path = 'add/';
    var msg = 'SMS Settings successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>sms_settings/"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {

      if(result==1)
      {
        

        $('#load_add_email_sett_modal_popup').modal('hide');
        reload_table();
        get_Element_Template();
        flash_session_msg(msg);
      } 
      else
            {

        $("#load_add_email_sett_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
  function get_Element_Template()
{
   $.ajax({url: "<?php echo base_url(); ?>sms_settings/Element_Setting_dropdown/", 
    success: function(result)
    {
      
      $('#email_setting_id').html(result); 
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
  
    $('#load_add_email_sett_modal_popup').modal('hide');
 
   
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
      


   
</div><!-- /.modal-dialog -->
