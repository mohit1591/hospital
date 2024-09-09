<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="medicine" class="form-inline">
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
                    <label>Medicine<span class="star">*</span></label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="medicine"  class="alpha_numeric_hyphen inputFocus" value="<?php echo $form_data['medicine']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('medicine'); } ?>
                </div>
              </div> <!-- innerrow -->
              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Unit 1</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="type" id="medicine_types" class="alpha_numeric_space" value="<?php echo $form_data['type']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('type'); } ?>
                </div>
              </div> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Salt</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="salt"   class="alpha_numeric_space" value="<?php echo $form_data['salt']; ?>" id="medicine_salt">
                    
                    <?php if(!empty($form_error)){ echo form_error('salt'); } ?>
                </div>
              </div> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-4">
                    <label>Mfg.Company</label>
                </div>
                <div class="col-md-8">
                    <input type="text" name="brand" id="brand_val" class="alpha_numeric_space" value="<?php echo $form_data['brand']; ?>">

                      <input type="hidden" name="company_id" id="company_id" class="alpha_numeric_space" value="<?php echo $form_data['company_id']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('brand'); } ?>
                </div>
              </div> <!-- innerrow -->



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

 // $(function () {
 //    var getData = function (request, response) { 
 //      $.getJSON(
 //      "<?php echo base_url('eye/medicine/get_salt_vals/'); ?>" + request.term,
 //      function (data) {
 //      response(data);
 //      });
 //    };

 //    var selectItem = function (event, ui) { 

 //        $("#medicine_salt").val(ui.item.value);
 //        //$("#medicine_salt_id").val(ui.item.id);
 //        return false;
 //    }

 //    $("#medicine_salt").autocomplete({ 
 //        source: getData,
 //        select: selectItem,
 //        minLength: 1,
 //        change: function() {  
 //        //$("#medicine_types").val("").css("display", 2);
 //        }
 //    });
 //  });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/medicine/get_unit_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 

        $("#medicine_types").val(ui.item.value);
        return false;
    }

    $("#medicine_types").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#medicine_types").val("").css("display", 2);
        }
    });
    });

$(function () {
   var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('eye/medicine/get_company_vals/'); ?>" + request.term,
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
      
       //$('.item_name').val(names[0]);
        $("#brand_val").val(names[0]);
         $("#company_id").val(names[1]);
        return false;
    }

    $("#brand_val").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#medicine_types").val("").css("display", 2);
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
 
$("#medicine").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Medicine successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Medicine successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('eye/medicine/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_medicine_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_medicine();
        reload_table();
      } 
      else
      {
        $("#load_add_medicine_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_medicine_modal_popup').modal('hide');
});

function get_medicine()
{
   $.ajax({url: "<?php echo base_url(); ?>eye/medicine/medicine_dropdown/", 
    success: function(result)
    {
      $('#medicine_id').html(result); 
    } 
  });
}

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".frequency_val").val(ui.item.value);
        return false;
    }

    $(".frequency_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".advice_val").val(ui.item.value);
        return false;
    }

    $(".advice_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->