<?php
$users_data = $this->session->userdata('auth_users');

?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="maintenance_page" class="form-inline">
 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
          <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id" />
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                  <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Message:</b></label>
                         </div>
                         <div class="col-md-7">
                              <textarea type="text" name="message" id="message" class="" ><?php echo $form_data['message'];?></textarea>
                         </div>
                    </div>
              </div> <!-- 12 -->
          </div> <!-- row -->  


          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <label>Status</label>
                </div>
                <div class="col-md-7">
                     <input class="" name="status" <?php if($form_data['status']==1){echo 'checked="checked"';}?> id="status" value="1" type="radio"> Active  
                     <input class="" name="status" <?php if($form_data['status']==2){echo 'checked="checked"';}?> value="2" type="radio"> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div>
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:80%;display:inline-flex;">
                <!-- <button type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();">Reset</button> -->
                <button type="submit" class="btn-update" name="submit" id="print">Save</button>
             
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">

$("#maintenance_page").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#data_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'open_popup_page/'+ids;
    var msg = 'Maintenance page successfully updated.';
  }
  else
  {
    var path = 'open_popup_page/';
    var msg = 'Maintenance page successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('under_maintenance_page/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_popup_maintenance_page').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_popup_maintenance_page").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_popup_maintenance_page').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
