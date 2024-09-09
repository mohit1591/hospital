<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="item_list_opening" class="form-inline" enctype="multipart/form-data">
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
                     <input type="file" accept=".xls"  name="item_list" id="item_list"/>
                     <p id="msg"></p> 
                     <input type="hidden" name="name" id="name" value="0"/>
                      <?php if(!empty($form_error)){ echo form_error('item_list'); } ?>
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
               <button type="submit"  class="btn-update" id="item_list_save" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   

$("button[data-number=2]").click(function(){
    $('#load_item_inventory_import_modal_popup').modal('hide'); 
}); 

$("#item_list_opening").on("submit", function(event) { 
     event.preventDefault(); 
     $('.overlay-loader').show();
  
     var path = 'inventory_item_import_excel/';
     var msg = 'Items successfully added.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>item_manage/"+path,
          type: "post",
          data: new FormData(this),
          processData: false,
          contentType: false,
          
          success: function(result) {
               if(result==1)
               {
                    flash_session_msg(msg);
                    $('#load_item_inventory_import_modal_popup').modal('hide');
                    reload_table();
               } 
               else
               {
                    $("#load_item_inventory_import_modal_popup").html(result);
               }
               $('.overlay-loader').hide();       
          }
     });
}); 
</script>  
</div><!-- /.modal-dialog -->