
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="dialysis_management" class="form-inline">
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
                    <label>Dialysis Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="name"  class="alpha_numeric_space inputFocus" value="<?php echo $form_data['name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                </div>
              </div> <!-- innerrow -->
              <div class="row m-b-5">
                <div class="col-md-5">
                      <strong>Dialysis Type<span class="star">*</span></strong>
                </div>
                <!--  <div class="col-md-7">
                    <input type="text" name="type" id="ot_pacakge_types" class="alpha_numeric_space type_name" value="<?php echo $form_data['type']; ?>">
                    <input type="hidden" value="" id="dialysis_type_id" name="dialysis_type_id"/>
                    
                </div> -->
                     
                      <div class="col-xs-7">
                        <select name="dialysis_pacakge_type_id" id="dialysis_pacakge_type_id" class="m10 w-145px">
                          <option value=""> Select Dialysis Type</option>
                          <?php
                          if(!empty($dialysis_type_list))
                          {
                            foreach($dialysis_type_list as $dia_type)
                            {

                             ?>
                               <option value="<?php echo $dia_type->id; ?>" <?php if($dia_type->id==$form_data['dialysis_pacakge_type_id']){ echo 'selected="selected"'; } ?>><?php echo $dia_type->dialysis_type; ?></option>
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

               <!--<div class="row m-b-5">
                <div class="col-md-5">
                    <label>Amount <span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="amount" class="price_float" value="< php echo $form_data['amount']; ?>">
                    
                    < ?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                </div>
              </div>--> <!-- innerrow -->

              <input type="hidden" name="amount" class="price_float" value="0.00">

              <div class="row m-b-5">
                <div class="col-md-5">
                <label>Dialysis Hours<span class="star">*</span></label>
                </div>
                <div class="col-md-7">

                <input type="text" name="hours" class="price_float" value="<?php echo $form_data['hours']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('hours'); } ?>
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
            "<?php echo base_url('dialysis_management/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 

        $("#dialysis_management_types").val(ui.item.value);
        return false;
    }

    $("#dialysis_management_types").autocomplete({ 
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

    var i=1;
    var getData = function (request, response) { 
      
    row = i ;
        $.ajax({
        url : "<?php echo base_url('dialysis_management/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
        data: {
        name_startsWith: request.term,

        row_num : row
        },
        success: function( data ) {
            response( $.map( data, function( item ) {
             var code = item.split("|");
            return {
            label: code[0],
            value: code[0],
            data : item
            }
            }));

        }
        });


    };

    var selectItem = function (event, ui) {
        var names = ui.item.data.split("|"); 
         $('.type_name').val(names[0]);
         $('#dialysis_type_id').val(names[0]);
           return false;
    }

  $(".type_name").autocomplete({
      source: getData,
      select: selectItem,
      minLength: 1,
      change: function() {  
      }
  });

 
$("#dialysis_management").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Dialysis management successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Dialysis management successfully created.';
  } 
  //alert('ddd');return false;
    $.ajax({
      url: "<?php echo base_url('dialysis_management/'); ?>"+path,
      type: "post",
      data: $(this).serialize(),
      success: function(result) {
        if(result==1)
        {
          $('#load_add_dialysis_management_modal_popup').modal('hide');
          flash_session_msg(msg);
          get_dialysis_name();
          reload_table();
        } 
        else
        {
          $("#load_add_dialysis_management_modal_popup").html(result);
        }       
        $('#overlay-loader').hide();
      }
    });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_dialysis_management_modal_popup').modal('hide');
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
}

function get_dialysis_management()
{
   $.ajax({url: "<?php echo base_url(); ?>dialysis_management/dialysis_summary_dropdown/", 
    success: function(result)
    {
      $('#dialysis_management_id').html(result); 
    } 
  });
}

function get_dialysis_name()
{
   $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/dialysis_name_dropdown/", 
    success: function(result)
    {
     
      $('#dialysis_name_id').html(result); 
      
    } 
  });
}

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
function get_dialysis_pacakge()
{
   $.ajax({url: "<?php echo base_url(); ?>dialysis_type/dialysis_type_dropdown/", 
    success: function(result)
    {
      $('#dialysis_pacakge_type_id').html(result); 
    } 
  });
}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 

<div id="load_add_dialysis_type_modal_popup" class="modal fade modal-top15" role="dialog" data-backdrop="static" data-keyboard="false"></div>
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->