<div class="modal-dialog emp-add-add modal-80">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="qc_type" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 
      <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Parent  QC Field</label>
                </div>
                <div class="col-md-7">
                    <select name="parent_qc_field" id="parent_qc_field" class="m_input_default">
            <option value="">Select Parent  QC Field</option>
            <?php
            
              if(!empty($qc_cat_list))
              {   
                  foreach($qc_cat_list as $qc_cat_list_data)
                  {  
                    //print_r($investigation);
                   //die;
                    if($form_data!="empty")
                    {
                      if($form_data['parent_qc_field']==$qc_cat_list_data->id)
                          $selected="selected=selected";
                        else
                          $selected="";
                      echo '<option value='.$qc_cat_list_data->id.' '.$selected.' >'.$qc_cat_list_data->qc_field.'</option>';
                    }
                    else
                    {  
                     echo '<option value='.$qc_cat_list_data->id.'>'.$qc_cat_list_data->qc_field.'</option>';
                    }
                  }
              }
            ?>
          </select>
                    
                    
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>QC Field<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                <input type="text" name="qc_field" class="inputFocus" value="<?php echo $form_data['qc_field']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('qc_field'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
           
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <label>Status</label>
                </div>
                <div class="col-md-7">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     

<script>  

$('.Number').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
    }
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#qc_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Bag QC Field successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Bag QC Field successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('blood_bank/bag_qc/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_component_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_deferral_reason();
        reload_table();
      } 
      else
      {
        $("#load_add_component_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_component_modal_popup').modal('hide');
});

function get_deferral_reason()
{
   $.ajax({url: "<?php echo base_url(); ?>blood_bank/bag_type/deferral_reason_dropdown/", 
    success: function(result)
    {
      $('#deferral_reason_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->