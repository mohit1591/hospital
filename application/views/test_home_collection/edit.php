<?php

//print_r($record_data);die;
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="home_collection_form" class="form-inline">
           <input type="hidden" name="rec_id" id="home_collection_id" value="<?php echo $record_data['id']; ?>" />
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4><?php echo $page_title; ?></h4> 
                </div>
                <div class="modal-body">  
                <!-- ============================================================================================ -->
                     <div class="row m-b-5">
                          <div class="col-md-12">
                               <div class="row m-b-5">
                                    <div class="col-md-12">
                                         <div class="row">
                                                  <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                          <strong>Status  <span class="star">*</span></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                              <input onclick="show_charge(1);" type="radio" name="status" <?php if($record_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Enable 
                                                              <input onclick="show_charge(0);" type="radio" name="status" <?php if($record_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Disable  
                                                        </div>
                                                      </div>
                                                  </div>     


                                              <?php if($record_data['status']==0) {   ?>
                                                  <div id="charge_div" class="col-md-6" style="display: none;">
                                              <?php } else { ?>
                                                  <div id="charge_div" class="col-md-6">
                                              <?php } ?>
                                                   <div class="row">
                                                        <div class="col-md-4">
                                                             <strong>Charge <span class="star">*</span></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                              <input type="text" name="charge" id="charge" class="w-133px inputFocus m_name" value="<?php echo $record_data['charge']; ?>" />
                                                              <span id="charge_error"></span>

                                                        </div>
                                                   </div>
                                              </div>
                                              


                                         </div> <!-- row -->
                                    </div>
                               </div> <!-- // main row -->
                              
         

            </div> <!-- 12 -->
          </div> <!-- row -->


          


      </div>    <!--  modal-body --> 
             
             
        <div class="modal-footer"> 
           <input type="submit"  class="btn-update" name="submit" value="Save" />
           <button type="button" class="btn-cancel" data-number="1">Close</button>
        </div>
</form>     



<script> 

$("button[data-number=1]").click(function(){
    $('#load_add_record_modal_popup').modal('hide'); 
});

 
//function to find gender according to selected simulation

 //ends
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


 
$("#home_collection_form").on("submit", function(event) { 
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var id = $('#home_collection_id').val();
  $.ajax({
    url: "<?php echo base_url(); ?>test_home_collection/save/",
    type: "post",
    data: $(this).serialize(),
    dataType:'Json',
    success: function(result) 
    {
      if(result.st==1)
      {
        $('#load_add_record_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(result.message);
      } 
      else if(result.st==0)
      {
        $("#charge_error").html(result.charge_error);
      }
      else if(result.st==2)
      {
        $("#charge_error").html(result.message);
      }   
      $('.overlay-loader').hide();     
    }
  });
}); 



function show_charge(val)
{
  if(val==1)
  {
    $("#charge_div").css('display','block');
  }
  else
  {
    $("#charge_div").css('display','none');
  }
}


</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
     
</div><!-- /.modal-dialog -->    
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_emp_type_modal_popup" class="modal fade  top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>

