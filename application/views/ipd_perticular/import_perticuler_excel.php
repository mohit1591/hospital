<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="particular_opening" class="form-inline" enctype="multipart/form-data">
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
            <div class="modal-body">


              <!-- // first row -->
              <div class="row">
                 <div class="col-md-5">
                      <strong>Excel File: <span class="star">*</span></strong>
                 </div> <!-- 4 -->
                 <div class="col-md-7">
                     <input type="file" accept=".xls"  name="particular_list" id="particular_list"/>
                     <p id="msg"></p> 
                     <input type="hidden" name="name" id="name" value="0"/>
                      <?php if(!empty($form_error)){ echo form_error('particular_list'); } ?>
                 </div> <!-- 8 -->
              </div> <!-- row -->

              <!-- // first row -->
              <div class="row m-b-5">
                <div class="col-md-12">
                 
                </div> <!-- 12 -->
              </div> <!-- row -->
                

              <div class="row">
                <div class="col-md-12">
                   
                </div> <!-- 8 -->
              </div> <!-- Row -->
               
           
              </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" id="patient_lists_save" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-number="2" >Close</button>
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

$("button[data-number=2]").click(function(){
  <?php //$this->session->unset_userdata('alloted_medicine_ids'); ?>
    $('#load_particular_import_modal_popup').modal('hide'); 
}); 

$("#particular_opening").on("submit", function(event) { 
     event.preventDefault(); 
     $('.overlay-loader').show();
  
     var path = 'import_particular_excel/';
     var msg = 'Particular successfully added.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>ipd_particular/"+path,
          type: "post",
          data: new FormData(this),
          processData: false,
          contentType: false,
          
          success: function(result) {
               if(result==1)
               {
                    flash_session_msg(msg);
                    $('#load_particular_import_modal_popup').modal('hide');
                    reload_table();
               } 
               else
               {
                    $("#load_particular_import_modal_popup").html(result);
               }
               $('.overlay-loader').hide();       
          }
     });
}); 

 function check()
 {
     
     if($('#getmedicineselectAll').is(':checked')) 
     {    
        
          $('.medicinechecklist').prop('checked', false);
     }
     else
     {

          $('.medicinechecklist').prop('checked', false);
     }

 }




</script>  


</div><!-- /.modal-dialog -->