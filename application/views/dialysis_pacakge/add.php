<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="dialysis_pacakge" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
     
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Dialysis Package Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="name"  class="alpha_numeric_space inputFocus" value="<?php echo $form_data['name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                </div>
              </div> <!-- innerrow -->
              <div class="row m-b-5">
            
                  <div class="col-xs-5">
                        <strong>Dialysis Type<span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-7">
                        <select name="dialysis_pacakge_type_id" id="dialysis_pacakge_type_id" class="m10 w-145px">
                          <option value=""> Select Dialysis Type</option>
                          <?php
                          if(!empty($dialysis_type_list))
                          {
                            foreach($dialysis_type_list as $di_type)
                            {

                             ?>
                               <option value="<?php echo $di_type->id; ?>" <?php if($di_type->id==$form_data['dialysis_pacakge_type_id']){ echo 'selected="selected"'; } ?>><?php echo $di_type->dialysis_type; ?></option>
                             <?php  
                            }
                          }
                          ?>
                        </select>
                        
                      
                      <?php //if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                           <a href="javascript:void(0)" onclick=" return add_dialysis_type();"  class="btn-new">
                                <i class="fa fa-plus"></i> Add
                           </a>
                      <?php //} ?>
                      <?php if(!empty($form_error)){ echo form_error('dialysis_pacakge_type_id'); } ?>
                      </div>
      



              </div> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Dialysis Days<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="days"   class="price_float" value="<?php echo $form_data['days']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('days'); } ?>
                </div>
              </div> <!-- innerrow -->

                            <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Dialysis Hours</label>
                </div>
                <div class="col-md-7">

                    <input type="text" name="hours" class="price_float" value="<?php echo $form_data['hours']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('hours'); } ?>
                </div>
              </div> <!-- innerrow -->


              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Amount<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="amount" class="price_float" value="<?php echo $form_data['amount']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                </div>
              </div> <!-- innerrow -->

             

              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Remarks</label>
                </div>
                <div class="col-md-7">
                    <textarea type="text" name="remarks" class="alpha_numeric_space" ><?php echo $form_data['remarks']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('remarks'); } ?>
                </div>
              </div> <!-- innerrow -->



            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row m-b-5">
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
<div id="load_add_dialysis_type_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     


<script>  
$(document).ready(function() {
  $('#load_add_dialysis_type_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dialysis_pacakge/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 
      var names = ui.item.value.split("|");
//alert(names[1]);
       $('#ot_pacakge_types').val(names[0]);
          $('#ot_type_id').val(names[0]);

       // $("#ot_pacakge_types").val(ui.item.value);
        return false;
    }

    $("#ot_pacakge_types").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#dialysis_pacakge").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Dialysis package successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Dialysis package successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dialysis_pacakge/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_dialysis_pacakge_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_dialysis_pacakge();
        reload_table();
      } 
      else
      {
        $("#load_add_dialysis_pacakge_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_dialysis_pacakge_modal_popup').modal('hide');
});

function add_dialysis_type()
{
  //alert("hi");
  var $modal = $('#load_add_dialysis_type_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_type/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
  /*var $modal = $('#load_add_specialization_modal_popup');
  $modal.load('<?php echo base_url().'specialization/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });*/
}
// function get_dialysis_pacakge()
// {
//    $.ajax({url: "<?php echo base_url(); ?>dialysis_type/dialysis_type_dropdown/", 
//     success: function(result)
//     {
//       $('#dialysis_pacakge_type_id').html(result); 
//     } 
//   });
// }
function get_dialysis_pacakge()
{
   $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/dialysis_pacakage_dropdown/", 
    success: function(result)
    {
     
      $('#package_name_p').html(result); 
      
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->