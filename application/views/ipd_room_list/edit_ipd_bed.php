<div class="modal-dialog ">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="edit_bed" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id'];?>" /> 
  <input type="hidden" name="room_no" id="room_no" value="<?php echo $form_data['room_no'];?>" /> 
  <input type="hidden" name="room_id" id="room_id" value="<?php echo $form_data['room_id'];?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12 m-b1">
                    <div class="row">
                         <div class="col-md-5">
                              <label>Bed No.<span class="star">*</span></label>
                         </div>
                         <div class="col-md-7">
                            <input type ="text" value="<?php echo $form_data['bad_no'];?>" name="bad_no" readonly/>
                         </div>
                    </div> <!-- innerrow -->
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
        
        <div class="row m-b-3">
          <div class="col-md-5">
          <label>Bed Name</label>
          </div>
          <div class="col-md-7">
          <input type="text"  name="bad_name" class="" id="bad_name" value="<?php echo $form_data['bad_name'];?>" />
          </div>
        </div>
	
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="2" data-dismiss="modal">Close</button>
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
 
$("#edit_bed").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit_bed/'+ids;
    var msg = 'Bed successfully updated.';
  }
  
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_room_list/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {

        $('#load_edit_ipd_bed_modal_popup').modal('hide');
        flash_session_msg(msg);
        location.reload();
      } 
      else
      {
        $("#load_edit_ipd_bed_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
  $('#load_edit_ipd_bed_modal_popup').modal('hide');
});


function reload_table()
{
  //alert();
    table.ajax.reload(null,false); //reload datatable ajax 
}


</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
<div id="load_edit_ipd_bed_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->