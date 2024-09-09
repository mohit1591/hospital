<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
 <div class="modal-content"> 
    <form id="branch_form" class="form-inline">
      <input type="hidden" name="data_id" id="branch_id" value="<?php echo $form_data['id']; ?>" />
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
        <h4><?php echo $page_title; ?></h4> 
      </div>
  <div class="modal-body">    
                     
                         <div class="row">
                              <div class="col-sm-12">

                                   <div class="row m-b-5">
                                        <div class="col-md-5">
                                             <strong>Branch ID<span class="star">*</span></strong>
                                        </div> <!-- 4 -->
                                        <div class="col-md-7">
                                             <span> <?php echo $form_data['branch_code']; ?> </span>
                                        </div> <!-- 8 -->
                                   </div> <!-- row -->

                                 <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Branch Name<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="branch_name" id="branch_name" value="<?php echo $form_data['branch_name']; ?>" readonly class="inputFocus"/>
                                        <?php //if(!empty($form_error)){ echo form_error('branch_name'); } ?>
                                   </div> <!-- 8 -->
                                 </div> <!-- row -->
                                 <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Subject</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="subject" id="branch_name" value="<?php echo $template_data->subject?>" readonly class="inputFocus"/>
                                        <?php //if(!empty($form_error)){ echo form_error('branch_name'); } ?>
                                   </div> <!-- 8 -->
                                 </div> <!-- row -->


                                <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Email</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                         <input type="text" name="email"   data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text email_address" id="email" value="<?php echo $form_data['email']; ?>" readonly/>
                                       
                                       
                                         <?php //if(!empty($form_error)){ echo form_error('email'); } ?>
                                   </div> <!-- 8 -->
                                 </div> <!-- row -->



                           <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Message<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                   <?php
                                   //_totprint_r($date_days_total);
                                   $date_days_total_one=$date_days_total;
                                   $name=$form_data['branch_name'];
                                   $get_data=$template_data->template;
                                   $template_data_tot = str_replace("{branch_name}",$name,$get_data);
                                    $template_data_tot = str_replace("{date}",$date_days_total_one,$template_data_tot);
                                   ?>
                                     <textarea name="message" class="message w-full" id="message"><?php echo $template_data_tot; ?></textarea>
                                      
                                   </div> <!-- 8 -->
                                 </div> <!-- row -->
                             


                        </div> <!-- 6 -->
                         
                    </div> <!-- MainRow -->
              

      </div>    <!--  modal-body --> 
             
  <div class="modal-footer">
           
           <input type="submit"  class="btn-save" name="submit" value="Save" />
           
           <button type="button" class="btn-cancel"  data-number="2">Close</button>
        </div>
  </form>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script> 

<script>
$("button[data-number=1]").click(function(){
    $('#load_add_branch_modal_popup_mail').modal('hide'); 
});

$("button[data-number=2]").click(function(){
    $('#load_add_branch_modal_popup_mail').modal('hide'); 
});

$(document).on("click", function(e){
    if( !$(".password_id").is(e.target) ){ 
    //if your box isn't the target of click, hide it
        $(".brn_1").hide();
    }
});

$("#branch_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  var ids = $('#branch_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'renew_mail/'+ids;
    var msg = 'Mail has been successfully sent.';
   
  }
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url(); ?>branch/"+path,
    type: "post",
    data:  $('#branch_form').serialize(),
    success: function(result) {
      if(result==1)
      {
        flash_session_msg(msg);
        $('#load_add_branch_modal_popup_mail').modal('hide');
        reload_table();
      } 
      else
      {
        $("#load_add_branch_modal_popup_mail").html(result);
      }
      $('.overlay-loader').hide();       
    }
  });
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