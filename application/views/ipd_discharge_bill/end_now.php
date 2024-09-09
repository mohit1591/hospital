<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="end_now_new" class="form-inline">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">


              <!-- // first row -->
            <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>End Date</label>
                              <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="end_date"  class="datepicker" id="start_date_s" value="<?php echo $form_data['end_date']; ?>">

                              
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

                  
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   
$(document).ready(function(){
  var today =new Date();
    $('#start_date_s').datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : '<?php echo $form_data['start_date'];?>',
    onSelect: function (selected) {
 
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
        }
    })
})


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#end_now_new").on("submit", function(event) { 
 event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'end_now/'+ids;
    var msg = 'End date successfully updated.';
  }
  else
  {
    var path = 'end_now/';
    var msg = 'End date successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_running_bill/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_end_now_modal_popup').modal('hide');
        flash_session_msg(msg); 
      } 
      else
      {
        $("#load_end_now_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}





</script>  


</div><!-- /.modal-dialog -->