<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
     <div class="modal-content"> 
          <form  id="email_form" class="form-inline">
               <input type="hidden" name="email_set_id" id="email_set_id" value="<?php echo $form_data['email_set_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close p-t-0" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">  
               <!-- ============================================================================================ -->
                    <div class="row m-b-5">
                      <div class="col-xs-12">

                          <div class="row m-b-5">
                              <div class="col-md-6">
                                  <strong> SMTP Address <span class="star">*</span> 
               <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span> Eg. For gmail ssl://smtp.gmail.com</span>
               </a>
               </sup></strong>
                              </div>
                              <div class="col-md-6">
                                   <input type="text"  name="smtp_address" value="<?php echo $form_data['smtp_address']; ?>">
                                  <?php if(!empty($form_error)){ echo form_error('smtp_address'); } ?>
                              </div>
                          </div>

                            
                            <div class="row m-b-5">
                               <div class="col-md-6">
                                    <strong>Network Email Id <span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Put your Gmail username</span>
               </a>
               </sup></strong>
                               </div>
                               <div class="col-md-6">
                                    <input type="text" name="network_email_id" id="network_email_id" value="<?php echo $form_data['network_email_id']; ?>" />
                                    <?php if(!empty($form_error)){ echo form_error('network_email_id'); } ?>
                                     
                               </div>
                            </div>

                            <div class="row m-b-5 ">
                                <div class="col-md-6">
                                    <strong>Network Email Password <span class="star">*</span></strong><span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Put your Gmail password. If you change your gmail password. Kindly update the same here.</span>
               </a>
               </sup>
                               </div>
                               <div class="col-md-6">
                                    <input type="text" name="network_email_pass" id="network_email_pass" value="<?php echo $form_data['network_email_pass']; ?>" />
                                    
                                    
									<?php if(!empty($form_error)){ echo form_error('network_email_pass'); } ?>
                               </div>
                            </div>

                            <div class="row">
                              <div class="col-md-6">
                                  <strong>Port No<span class="star">*</span></strong>
                              </div>
                              <div class="col-md-6">
                                  <input type="text"  maxlength="4" onkeypress="return isNumberKey(event);" name="port" id="port" value="<?php echo $form_data['port']; ?>" />
                                  <?php if(!empty($form_error)){ echo form_error('port'); } ?>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <strong>Using SSL  <span class="star">*</span></strong>
                              </div>
                              <div class="col-md-6">
                                <input type="radio" name="ssl_status" <?php if($form_data['ssl_status']==1){ echo 'checked="checked"'; } ?> id="ssl_status" value="1" /> Yes 
                                <input type="radio" name="ssl_status" <?php if($form_data['ssl_status']==0){ echo 'checked="checked"'; } ?> id="ssl_status" value="0" /> No  
                              </div>
                            </div>
                            <div class="row m-b-5">
                              <div class="col-md-6">
                                <strong>Email Id<span class="star">*</span></strong>
                              </div>
                              <div class="col-md-6">
                                <input type="text" name="cc_email" id="cc_email" value="<?php echo $form_data['cc_email']; ?>" />
                                <?php if(!empty($form_error)){ echo form_error('cc_email'); } ?>
                                 
                              </div>
                            </div>
                          
                      </div> <!-- 6 -->
                    

     <span class="star">*</span> For gmail kindly enable less secure apps gmail from the below link. <a href="https://myaccount.google.com/lesssecureapps?rapt=AEjHL4Mrqbt01FREMpHrJRsNETaWkQcbaq1O3IpS98KA8aRhujas6WBm0cGzTImzJq7zHKTV8til14nSuzJogJzePjftDRfnHQ">Link</a>

                    
                      
                          
                      
                    </div> <!-- row -->


                    
                        
                         

               </div>    <!--  modal-body --> 
               <div class="modal-footer"> 
                    <input type="submit"  class="btn-update" name="submit" value="Save" />
                    <button type="button" class="btn-cancel" data-number="2">Close</button>
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


 
$("#email_form").on("submit", function(event) { 
    
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#email_set_id').val();
  
  if(ids!="" && !isNaN(ids))
  { 
     
    var path = 'edit/'+ids;
    var msg = 'Email Settings successfully updated.';
  }
  else
  {
     
    var path = 'add/';
    var msg = 'Email Settings successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>email_settings/"+path,
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
   $.ajax({url: "<?php echo base_url(); ?>email_settings/Element_Setting_dropdown/", 
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
