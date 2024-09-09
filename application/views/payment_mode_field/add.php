<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="payment_mode_field" class="form-inline">
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
                    <label>Payment Mode<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="alpha_numeric_space w-145px" name="payment_mode" id="payment_mode" value="<?php echo $form_data['payment_mode'];?>"/>
                     <a href="javascript:void(0)" onclick=" return add_more_field();"  class="btn-new" >
                     <i class="fa fa-plus"></i> Add Field
                   </a>
                    
                     <div class="text-danger" id="payment_mode_error"></div>

                </div>
              </div> <!-- innerrow -->
                
             

              <div class="add_more_filed">
                 <?php
                  if(!empty($form_data['field_list']))
                  {
                    $i = 0;
                    foreach($form_data['field_list'] as $field)
                    {
                    ?>
                    <div class="row m-b-5 m-t-5" id="filed_name_new_<?php echo $i; ?>">
                      <div class="col-md-4">
                      <label>Field Name<span class="star">*</span></label>
                      </div>
                      <div class="col-md-8"> 
                         <input type="text" name="field[]" class="w-145px" id="filed_name_<?php echo $i; ?>" value="<?php echo $field->field_name; ?>">
                         <input type="hidden" value="<?php echo $field->id;?>" name="field_id[]" />
                         <a href="javascript:void(0)" onclick=" return remove_field(<?php echo $i; ?>);"  class="btn-new">
                          <i class="fa fa-trash"></i> Remove
                        </a>
                         <div class="text-danger" id="field_name_error_<?php echo $i; ?>"></div>
                      </div>
                        
                    </div>
                  <?php 
                    $i++;
                   }
                  }
                 ?>
              </div>



            </div> <!-- 12 --> 
         

          </div> <!-- row -->  
           <div class="row">
                <div class="col-md-4">
                    <label>Sort Order<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="numeric w-145px" name="sort_order" id="sort_order" value="<?php echo $form_data['sort_order'];?>"/>
                   
                     <div class="text-danger" id="sort_order_error"></div>

                </div>
              </div> <!-- innerrow -->
              <div class="row">
              <div class="col-md-12">
              <div class="row">
              <div class="col-md-4">
              <label>Status</label>
              </div>
              <div class="col-md-8">
              <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
              <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
              </div>
              </div>

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
 
$("#payment_mode_field").on("submit", function(event) { 


   var str_pass='0';
    for(var i=1;i<=$(".add_more_filed > div").size();i++)
    {
      
      if($("#filed_name_new_"+i).html()!='')
      {
        if(str_pass=='')
        {
          str_pass=i;
        }
        else
        {
          if($('#filed_name_'+i).val()=='')
          {
            $('#field_name_error_'+i).html('field name is required');
             var error=1;
         }
         else
         {
           $('#field_name_error_'+i).html('');
         }

          str_pass=str_pass+","+i;
        }
      }
      
    }

  if($('#payment_mode').val()=='')
  {
    $('#payment_mode_error').html('payment mode is required');
    return false;
  }
  else if($('#sort_order').val()=='')
  {
     $('#sort_order_error').html('sort order is required');
     return false;
  }
  else if(error==1)
  {
    return false;
  }

  $('#total_ids').val(str_pass);
   event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Payment Mode Field successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Payment Mode Field successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('payment_mode_field/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_payment_mode_field_modal_popup').modal('hide');
        flash_session_msg(msg);
        
        reload_table();
      }
      else if(result==2)
      {
        $('#load_add_payment_mode_field_modal_popup').modal('hide');
        error_flash_session_msg("This payment gateway already used in system therefore you are not able to change.");
        
        reload_table();
      } 
      else
      {
        $("#load_add_payment_mode_field_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_payment_mode_field_modal_popup').modal('hide');
});

function get_payment_mode()
{
   $.ajax({url: "<?php echo base_url(); ?>payment_mode_field/payment_mode_field_dropdown/", 
    success: function(result)
    {
      $('#payment_mode_id').html(result); 
    } 
  });
}
<?php if(isset($form_data['field_name']))
{?>

  add_more_field();
<?php }?>

<?php
if(!empty($form_data['field_list']))
{
  $total_num = count($form_data['field_list']);
  echo 'var divSize = '.$total_num.';';
}
else
{
  echo 'var divSize = $(".add_more_filed > div").size()+1;';
}
?>
function add_more_field()
{

      var my_div = '';  
      my_div= '<div class="row m-b-5 m-t-5" id="filed_name_new_'+divSize+'"> <div class="col-md-4"> <label>Field Name<span class="star">*</span></label></div> <div class="col-md-8"><input name="field[]" value="" id="filed_name_'+divSize+'"" class="m-r-2" type="text" style="width:145px;"><a href="javascript:void(0)" onclick=" return remove_field('+divSize+');"  class="btn-new"> <i class="fa fa-plus"></i>Remove</a> <div class="text-danger" id="field_name_error_'+divSize+'"></div> </div></div>';
      var fields =[];
      
      if(divSize=='0'){
      $('.add_more_filed').html(my_div);
      }
      else{
      $('.add_more_filed').append(my_div);
      }
      divSize++;
     
}

function remove_field(n)
{
   $('#filed_name_new_'+n).html('');
   $('#filed_name_new_'+n+' input').remove();
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->