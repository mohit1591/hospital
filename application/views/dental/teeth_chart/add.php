<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="teeth_chart" class="form-inline" enctype="multipart/form-data" method="post">
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
                    <label>Teeth Type<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <select name="teeth_name" id="teeth_name" class="m_input_default">
            <option value="">Select teeth type</option>
            <option value="1"<?php if ($form_data['teeth_name'] == '1') echo ' selected="selected"'; ?>>Permanent Teeth</option>
             <option value="2"<?php if ($form_data['teeth_name'] == '2') echo ' selected="selected"'; ?>>Deciduous Teeth</option>
          </select>
             <?php if(!empty($form_data)){ echo form_error('teeth_name'); } ?>
                    
                    
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div>
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Teeth Number<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <select name="teeth_number" id="teeth_number" class="m_input_default">
            <option value="">Select Number</option>
            <?php
            //print_r($teeth_number_list);
              if(!empty($teeth_number_list))
              {   
                  foreach($teeth_number_list as $number_list)
                  {  
                    //print_r($investigation);
                   //die;
                    if($form_data!="empty")
                    {
                      if($form_data['teeth_number']==$number_list->number)
                          $selected="selected=selected";
                        else
                          $selected="";
                      echo '<option value='.$number_list->number.' '.$selected.' >'.$number_list->number.'</option>';
                    }
                    else
                    {  
                     echo '<option value='.$number_list->number.'>'.$number_list->number.'</option>';
                    }
                  }
              }
            ?>
          </select>
                 <?php if(!empty($form_data)){ echo form_error('teeth_number'); } ?>   
                    
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Teeth Category<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <select name="teeth_type" id="teeth_type" class="m_input_default">
            <option value="">Select Teeth Category</option>
            <option value="1"<?php if ($form_data['teeth_type'] == '1') echo ' selected="selected"'; ?>>Upper Left</option>
             <option value="2"<?php if ($form_data['teeth_type'] == '2') echo ' selected="selected"'; ?>>Upper Right</option>
              <option value="3"<?php if ($form_data['teeth_type'] == '3') echo ' selected="selected"'; ?>>Lower Left</option>
               <option value="4"<?php if ($form_data['teeth_type'] == '4') echo ' selected="selected"'; ?>>Lower Right</option>
          </select>
                        <?php if(!empty($form_data)){ echo form_error('teeth_type'); } ?>    
                    
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
            <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Sort Order<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="sort_order" class="numeric inputFocus" value="<?php echo $form_data['sort_order']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('sort_order'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          <div class="row mb-5">
      <div class="col-md-4"> <strong>Teeth Image</strong></div>
        <div class="col-md-8">
        <div class="pat-col">
           <div class="photo_update_company_setting">
                      <?php
                           $img_path  = $file_img = base_url('assets/images/photo.png');
                           if(!empty($form_data['teeth_image'])){
                                $img_path = ROOT_UPLOADS_PATH.'dental/teeth_chart/'.$form_data['teeth_image'];
                           }  
                      ?>
                  <img id="pbimg" src="<?php echo $img_path; ?>" class="img-responsive">

             </div>
        </div>
        </div>
        <div class="row m-b-5">
                                   
               <div class="col-md-7">
                  <div class="col-xs-7">
                         <input type="hidden" name="teeth_image"  value="<?php  if(!empty($form_data['teeth_image'])){ echo $form_data['teeth_image']; } ?>" />
                         <input type="file" id="bimg-input1" accept="image/*" name="photo">
                          <?php
                              if(isset($photo_banner_error) && !empty($photo_banner_error)){
                                   echo '<div class="text-danger">'.$photo_banner_error.'</div>';
                              }
                         ?>
                        
                    </div>
               </div>
          </div>
        </div> 
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
function readURLs(input) 
  {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pbimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

  $("#img-input").change(function(){
        readURL(this);
    });

  $("#bimg-input1").change(function(){
        readURLs(this);
    });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#teeth_chart").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Teeth chart successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Teeth chart successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dental/teeth_chart/'); ?>"+path,
    type: "post",
   cache: false,
     contentType: false,
     processData: false, 
    data: new FormData(this),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_dental_teeth_chart_modal_popup').modal('hide');
        flash_session_msg(msg);
        //get_chief_complaints();
        reload_table();
      } 
      else
      {
        $("#load_add_dental_teeth_chart_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_dental_teeth_chart_modal_popup').modal('hide');
});

// function get_chief_complaints()
// {
//    $.ajax({url: "<?php echo base_url(); ?>eye/teeth_number/teeth_number_dropdown/", 
//     success: function(result)
//     {
//       $('#teeth_number_id').html(result); 
//     } 
//   });
// }
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->