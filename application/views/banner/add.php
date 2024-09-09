  <div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader11">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="logo" class="form-inline" enctype="multipart/form-data">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Title<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                     <input type="text"  id="documents" placeholder="Enter Title" name="title" value="<?php if(isset($form_data['title']) && !empty($form_data['title'])){echo $form_data['title'];}?>">
               
                    <?php if(!empty($form_error)){ echo form_error('title'); } ?>
              </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  

     <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Banner<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="file"  placeholder="Enter Image" id="banner_image" name="banner_image" value="<?php if(isset($form_data['banner_image']) && !empty($form_data['banner_image'])){echo $form_data['banner_image'];}?>">
                    <?php if(!empty($form_data['banner_image'])){?>
                    <img src="https://www.hospitalms.in/assets/uploads/banner/<?php echo  $form_data['banner_image'];?>" width="80" height="80">
                  <?php }?>
                     <input type="hidden" name="hide_banner_image" value="<?php echo $form_data['banner_image'] ;?>">
              <!--  <input type="file" name="imagee" accept="image/*">-->
                   
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
      </div> <!-- row --> 
       <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>URl</label>
                </div>
                <div class="col-md-8">
                     <input type="text"  id="documents" placeholder="Enter detail URL" name="url" value="<?php if(isset($form_data['url']) && !empty($form_data['url'])){echo $form_data['url'];}?>">
               
                    <?php if(!empty($form_error)){ echo form_error('url'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          
           <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" <?php if($form_data['status']=='1'){ echo 'checked="checked"';} ?> name="status" value="1"> Active 
                    <input type="radio" <?php if($form_data['status']=='0'){ echo 'checked="checked"';} ?> name="status" value="0"> In-Active
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 





          


    
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-update" data-number="1">Close</button>
      </div>
</form>     
<script>
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script>

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#logo").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader11').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Banner successfully updated.';
  }
  else
  {
    var path = 'add';
    var msg = 'Banner successfully created.';
  } 
  //alert('ddd');return false;
  
  $.ajax({
    url: "<?php echo base_url('banner/'); ?>"+path,
    type: "post",
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false, 
    //data: $(this).serialize(),
    success: function(result) {
      
      if(result==1)
      {
        $('#load_add_speciality_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_add_speciality_modal_popup").html(result);
      }       
      $('#overlay-loader11').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_speciality_modal_popup').modal('hide');
});



</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->