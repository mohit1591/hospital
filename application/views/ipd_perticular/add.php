<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="ipd_perticular" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">  
    
            
             
            <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Particular<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text"  name="perticular"  value="<?php echo $form_data['perticular']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('perticular'); } ?>
                </div>
              </div> 
              
              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Code</label>
                </div>
                <div class="col-md-8">
                    <input type="text"  name="particular_code"  value="<?php echo $form_data['particular_code']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('particular_code'); } ?>
                </div>
              </div> 
              
              
              

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Charge<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="price_float" name="charge"  value="<?php echo $form_data['charge']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('charge'); } ?>
                </div>
              </div> <!-- innerrow -->

             
				<div class="row m-b-5">
					<div class="col-md-4">
						<label>Status</label>
					</div>
					<div class="col-md-8">
						 <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
						 <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
					</div>

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
 
$("#ipd_perticular").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Particular successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Particular successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_perticular/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ipd_perticular_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_ipd_perticular();
        reload_table();
       
      } 
      else
      {
        $("#load_add_ipd_perticular_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ipd_perticular_modal_popup').modal('hide');
});

function get_ipd_perticular()
{
   $.ajax({url: "<?php echo base_url(); ?>ipd_perticular/ipd_perticular_dropdown/", 
    success: function(result)
    {
      $('#particular_id').html(result); 
    } 
  });
}

$(document).ready(function(){
  var id=$('#grp_code1').val();
  if(id=='')
  {
    $('#grp_code1').hide();
  }
  else{
    $('#grp_code1').show();
  }


})

 
function show_code(val)
{
  if(val==0){
  $('#grp_code1').hide();
}
else{
   $('#grp_code1').show();

}
  if(val==1)
  {
    var grp_code=10
  }
   if(val==2)
  {
    var grp_code=20
  }
   if(val==3)
  {
    var grp_code=30
  }
   if(val==4)
  {
    var grp_code=40
  }
   if(val==5)
  {
    var grp_code=50
  }
   if(val==6)
  {
    var grp_code=60
  }
   if(val==7)
  {
    var grp_code=70
  }
   if(val==8)
  {
    var grp_code=80
  }
   if(val==9)
  {
    var grp_code=90
  }
//   alert(grp_code);
//var grp_value =$( "#grpselect option:selected" ).val();
$('#grp_code1').val(grp_code);
$('#hide_grp_id').val(grp_code);
 
}

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->