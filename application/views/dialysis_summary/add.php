<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="dialysis_summary" class="form-inline">
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
                    <label>Template Name<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="name"  class="alpha_numeric_space inputFocus" value="<?php echo $form_data['name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                </div>
              </div> <!-- innerrow -->
              
        <?php      
        if(empty($form_data['data_id']))
        {
          if(!empty($discharge_field_master_list))
          {
            foreach ($discharge_field_master_list as $discharge_field) 
            {
                ?>
                      <div class="row m-b-5">
                      <div class="col-xs-4">
                      <strong><?php echo ucfirst($discharge_field->discharge_lable); ?></strong>
                      </div>
                      

                      <?php if($discharge_field->type==1)
                      {
                       
                        ?>
                        <div class="col-xs-8">
                        <input type="text" name="field_name[]" value="" />
                        <input type="hidden" value="<?php echo $discharge_field->id;?>" name="field_id[]" />
                        </div>
                        <?php 
                      }
                      else
                      {
                        ?>
                        <div class="col-xs-8">
                        <textarea name="field_name[]"></textarea>
                        <input type="hidden" value="<?php echo $discharge_field->id;?>" name="field_id[]" />

                         </div>
                        <?php
                      } ?>
                      </div>
                <?php 
                 
            }
          }
        }
        else
        {
           
                  if(!empty($field_name))
                   { 
                    foreach ($field_name as $field_names) 
                    {
                        $tot_values= explode('__',$field_names);
                        
                  ?>

              <div class="row m-b-5">
                      <div class="col-xs-4">
                      <b><?php echo ucfirst($tot_values[1]);?></b>
                      </div> 
                  
                  <?php if($tot_values[3]==1){ ?>
                  <div class="col-xs-8">
                  <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" />
                  <input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                  <?php 
                      if(empty($tot_values[0]))
                      {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                      }
                      ?>
                  </div>
                    <?php 
                  }
                  else
                  {
                      ?>  
                        <div class="col-xs-8">
                        <textarea id="field_id_<?php echo $tot_values[1];?>" name="field_name[]"><?php echo $tot_values[0];?></textarea>
                        <input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" /> 
                        </div>
                      <?php 
                  }
                  if(empty($tot_values[0]))
                  {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                  }
                  ?>
               </div>
               <?php 
               } 
               } 
             } ?>
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              <!--<div class="row m-b-5">
                <div class="col-md-5">
                    <label>Diagnosis</label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="diagnosis" id="diagnosis" class="alpha_numeric_space" value="< ?php echo $form_data['diagnosis']; ?>">
                    
                    < ?php if(!empty($form_error)){ echo form_error('diagnosis'); } ?>
                </div>
              </div>--> <!-- innerrow -->

            <!--<div class="row m-b-5">
            
                  <div class="col-md-5">
                        <strong>Dialysis<span class="star">*</span></strong>
                      </div>
                      <div class="col-md-7">
                        <select name="dialysis_name_id" id="dialysis_name_id" class="m10 w-145px">
                          <option value=""> Select Dialysis</option>
                          < ?php
                          if(!empty($dialysis_management_list))
                          {
                            foreach($dialysis_management_list as $di_type)
                            {

                             ?>
                               <option value="< ?php echo $di_type->id; ?>" < ?php if($di_type->id==$form_data['dialysis_name_id']){ echo 'selected="selected"'; } ?>>< ?php echo $di_type->name; ?></option>
                             < ?php  
                            }
                          }
                          ?>
                        </select>
                        
                      
                     
                      < ?php if(!empty($form_error)){ echo form_error('dialysis_name_id'); } ?>
                      </div>
      



              </div>--> <!-- innerrow -->

              <!--<div class="row m-b-5">
                <div class="col-md-5">
                    <label>Dialysis. Findings</label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="dialysis_findings" class="alpha_numeric_space" value="< ?php echo $form_data['dialysis_findings']; ?>">
                    
                    < ?php if(!empty($form_error)){ echo form_error('dialysis_findings'); } ?>
                </div>
              </div>--> <!-- innerrow -->

              <!--<div class="row m-b-5">
                <div class="col-md-5">
                    <label>Procedures</label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="procedures" class="alpha_numeric_space" value="< ?php echo $form_data['procedures']; ?>">
                    
                    < ?php if(!empty($form_error)){ echo form_error('procedures'); } ?>
                </div>
              </div>--> <!-- innerrow -->

              <!--<div class="row m-b-5">
                <div class="col-md-5">
                    <label>Post Dialysis. Order</label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="pos_dialysis_orders" class="alpha_numeric_space" value="< ?php echo $form_data['pos_dialysis_orders']; ?>">
                    
                    < ?php if(!empty($form_error)){ echo form_error('pos_dialysis_orders'); } ?>
                </div>
              </div>--> <!-- innerrow -->



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
      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     


<script>  
$(document).ready(function() {
  $('#load_add_dialysis_management_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dialysis_summary/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 

        $("#ot_summary_types").val(ui.item.value);
        return false;
    }

    $("#ot_summary_types").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 2,
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
 
$("#dialysis_summary").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'dialysis summary successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'dialysis summary successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dialysis_summary/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_dialysis_summary_modal_popup').modal('hide');
        flash_session_msg(msg);
      //  get_dialysis_summary();
        reload_table();
      } 
      else
      {
        $("#load_add_dialysis_summary_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_dialysis_summary_modal_popup').modal('hide');
});
function add_dialysis_management()
{
  //alert("hi");
  var $modal = $('#load_add_dialysis_management_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_management/add/' ?>',
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

function get_dialysis_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>dialysis_summary/dialysis_summary_dropdown/", 
    success: function(result)
    {
      $('#ot_summary_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_dialysis_management_modal_popup" class="modal fade modal-top45 modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>