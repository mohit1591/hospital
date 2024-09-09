<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<div class="modal-dialog emp-add-add modal-80">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="profile_type" class="form-inline">
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
                    <label>Profile Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                <select name="profile_name" id="profile_name">
               <option value="">Select profile name</option>
                  <?php
                  
                  ?>
              <?php
              if(!empty($emp_list))
              {
                
                foreach($emp_list as $emp_list_name)
                {
                    if($emp_list_name!='empty')
                    { 
                        if($form_data['profile_name']==$emp_list_name->id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$emp_list_name->id.'" '.$select.'>'.$emp_list_name->name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$emp_list_name->id.'" >'.$emp_list_name->name.'</option>';
                    }
                }
              }
              ?> 
       </select>
               
                   <?php if(!empty($form_error)){ echo form_error('profile_name'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
          
        <div class="col-md-5"><b>Employee Type<span class="star">*</span></b></div>
         <div class="col-md-7">
             <select name="employee_type" id="employee_type">
               <option value="">Select employee type</option>
                  <?php
                  
                  ?>
              <?php
              if(!empty($employee_type_list))
              {
                
                foreach($employee_type_list as $employee_type)
                {
                    if($employee_type!='empty')
                    { 
                        if($form_data['employee_type']==$employee_type->id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$employee_type->id.'" '.$select.'>'.$employee_type->employee_type_name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$employee_type->id.'" >'.$employee_type->employee_type_name.'</option>';
                    }
                }
              }
              ?> 
       </select>
                <?php if(!empty($form_error)){ echo form_error('employee_type'); } ?>
        
      </div> </div></div></div>
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
$(".txt_firstCap").on('keyup', function(){

   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCap').val(part_val.join(" "));
  
  });  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#profile_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Employee profile successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Employee profile successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('blood_bank/employee_profile/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_profile_type_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_deferral_reason();
        reload_table();
      } 
      else
      {
        $("#load_add_profile_type_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_profile_type_modal_popup').modal('hide');
});

function get_deferral_reason()
{
   $.ajax({url: "<?php echo base_url(); ?>blood_bank/employee_profile/deferral_reason_dropdown/", 
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