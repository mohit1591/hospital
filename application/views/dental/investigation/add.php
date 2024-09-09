<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="investigation" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 
      
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Investigation Category</label>
                </div>
                <div class="col-md-7">
                    <select name="investigation_cat" id="investigation_cat" class="m_input_default">
            <option value="">Select Investigation Category</option>
            <?php
            
              if(!empty($investigation_cat_list))
              {   
                  foreach($investigation_cat_list as $investigation)
                  {  
                    //print_r($investigation);
                   //die;
                    if($form_data!="empty")
                    {
                      if($form_data['investigation_cat']==$investigation->id)
                          $selected="selected=selected";
                        else
                          $selected="";
                      echo '<option value='.$investigation->id.' '.$selected.' >'.$investigation->investigation_sub.'</option>';
                    }
                    else
                    {  
                     echo '<option value='.$investigation->id.'>'.$investigation->investigation_sub.'</option>';
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
                    <label>Investigation Subcategory<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="investigation_sub" class="inputFocus first_txt_cap" value="<?php echo $form_data['investigation_sub']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('investigation_sub'); } ?>
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
         <button type="button" class="btn-cancel" data-number="1">Close</button>
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
 
$("#investigation").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Investigation successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Investigation successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dental/investigation/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_dental_investigation_modal_popup').modal('hide');
        flash_session_msg(msg);
        //get_chief_complaints();
        reload_table();
      } 
      else
      {
        $("#load_add_dental_investigation_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_dental_investigation_modal_popup').modal('hide');
});

$(".first_txt_cap").on('keyup', function(){

   var str = $('.first_txt_cap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
        if(i==0)
        {
            var j = part_val[i].charAt(0).toUpperCase();
            part_val[i] = j + part_val[i].substr(1);     
        }
       
        
      
    }
      
   $('.first_txt_cap').val(part_val.join(" "));
  
  });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->