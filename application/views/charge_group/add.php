<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="group_charge" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
             <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Group Name<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="group_name"  class="inputFocus" value="<?php echo $form_data['group_name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('group_name'); } ?>
                </div>
              </div> <!-- innerrow -->


              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Group Code<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="group_code"  class="alpha_numeric inputFocus" value="<?php echo $form_data['group_code']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('group_code'); } ?>
                </div>
              </div> <!-- innerrow -->

               <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Sort Order<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="sort_order"  class="alpha_numeric inputFocus" value="<?php echo $form_data['sort_order']; ?>">
                    
                    <?php  //if(!empty($form_error)){ echo form_error('group_code'); } ?>
                </div>
              </div> <!-- innerrow -->

              

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Module Name</label>
                </div>
                <div class="col-md-8">
                 <?php  $selected='';
                 $selected1='';
                 $selected2='';
                 $selected3='';
                 $selected4='';
                 $selected5='';
                 $selected6='';
                 $selected7='';
                 $selected8='';
                 if($form_data['module']=='1'){

                  $selected="selected='selected'";

                  } 
                  if($form_data['module']=='2'){

                  $selected1="selected='selected'";

                  } if($form_data['module']=='3'){

                  $selected2="selected='selected'";

                  } if($form_data['module']=='4'){

                  $selected3="selected='selected'";

                  } if($form_data['module']=='5'){

                  $selected4="selected='selected'";

                  } if($form_data['module']=='6'){

                  $selected5="selected='selected'";

                  }if($form_data['module']=='7'){

                  $selected6="selected='selected'";

                  }if($form_data['module']=='8'){

                  $selected7="selected='selected'";

                  }if($form_data['module']=='9'){

                  $selected8="selected='selected'";

                  }?>
                <select name="module" onchange="show_list_ipd_particular(this.value);">
                    <option value="1" <?php echo $selected; ?>>Pathalogy</option>
                    <option value="2" <?php echo $selected1; ?>>Medicine</option>
                    <option value="3" <?php echo $selected2; ?>>OT</option>
                    <option value="4" <?php echo $selected3; ?>>Particulars</option>
                     <option value="5" <?php echo $selected4; ?>>Registration Charge</option>
                     <option value="6" <?php echo $selected5; ?>>Room Charge</option>
                      <option value="7" <?php echo $selected6; ?>>Pacakage Charge</option>
                      <option value="8" <?php echo $selected7; ?>>Advance Payment</option>
                        <option value="9" <?php echo $selected8; ?>>OT Package</option>
                  </select>

                   <?php if(!empty($form_error)){ echo form_error('module'); } ?>
                   
                </div>
              </div> <!-- innerrow -->
                 <?php $selected_p=''; ?>
               <div class="row m-b-5  <?php if($form_data['module']=='4'){echo '' ;} else {echo "hide";}?> " id="particular_id">
                <div class="col-md-4">
                    <label>Partculars List</label>
                </div>
                <div class="col-md-8"> 
                <select name="particular_id[]" class="multi_type" multiple="multiple">
                
                <?php 
                    if($form_data['particular_id']==0)
                      {
                         $selected_p="selected='selected'";
                      } 
                      
                if(!empty($particulars_list))
                {
                   if($form_data['particular_id']!="")
                   {
                     $selected_particular = explode(',', $form_data['particular_id']);
                   }
                   else
                   {
                     $selected_particular = array();
                   }
                   ?>
                  <option value="0" <?php echo $selected_p; ?>>All</option>
                   <?php
                   foreach($particulars_list as $particular_list) 
                   {
                      $selected_r='';
                     if(in_array($particular_list->id,$selected_particular))
                     {
                       $selected_r="selected='selected'";
                     }
                ?>
                <option value="<?php echo $particular_list->id;?>" <?php echo $selected_r; ?>><?php echo $particular_list->particular;?></option>
               <?php
                  }
                }
                ?>
                </select>

                   
                </div>
              </div> <!-- innerrow -->
                <input type="hidden" name="particular_id_blank" value="" class="hide particular_id_new"/>

            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row m-b-5">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                </div>
                <div class="col-md-8">
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
<?php 
if($form_data['module']==4)
{?>
$('.particular_id_new').val("particular");
<?php }?>
function show_list_ipd_particular(module_id)
{

    if(module_id==4)
    {
       $('#particular_id').removeClass( "hide" );
       $('.particular_id_new').val("particular");
    }
    else
    {
      $('#particular_id').addClass("hide");
      $('.particular_id_new').val("");


    }
}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#group_charge").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Group Charge successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Group Charge successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('charge_group/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_charge_group_modal_popup').modal('hide');
        flash_session_msg(msg);
        //get_particular();
        reload_table();
      } 
      else
      {
        $("#load_add_charge_group_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_charge_group_modal_popup').modal('hide');
});

function get_charge_group()
{
   $.ajax({url: "<?php echo base_url(); ?>charge_group/charge_group_dropdown/", 
    success: function(result)
    {
      $('#charge_group').html(result); 
    } 
  });
}

 $(document).ready(function() {
        $('.multi_type').multiselect({
          //'includeSelectAllOption':true,
          'maxHeight':200,
          'enableFiltering':true
        });
    });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->