<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(5);
//print_r($form_data);
?>
<div class="modal-dialog">
     <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="emp_type" class="form-inline">
               <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="stock_id" id="stock_id" value="<?php echo $form_data['stock_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">
                    <div class="row">
                         <div class="col-md-6">
                         <!-- / --> 
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <!--  <label>Medicine code</label> -->
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                       <!--  <div class="dcode"><b><?php //echo $form_data['medicine_code']; ?></b></div> -->
                                       <input type="hidden" name="medicine_code" id="medicine_code" value="<?php echo $form_data['medicine_code']; ?>"/>
                                   </div> <!-- 8 -->
                             </div> <!-- innerRow --> 
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Medicine Name<span class="star">*</span></label>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                   <?php if(!empty($form_data['data_id'])){ ?>
                                        <input type="text" readonly="readonly" name="medicine_name" id="medicine_name" value="<?php echo $form_data['medicine_name']; ?>" class="medicine_val alpha_numeric_space inputFocus">
                                   <?php }else{ ?>
                                          <input type="text"  name="medicine_name" id="medicine_name" value="<?php echo $form_data['medicine_name']; ?>" class="medicine_val alpha_numeric_space inputFocus">
                                   <?php } ?>
                                        <?php if(!empty($form_error)){ echo form_error('medicine_name'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- innerRow --> 
                              <input type="hidden" id="manuf_company" name="manuf_company" value="<?php echo $form_data['manuf_company']; ?>">
                               <!--<div class="row m-b-5">
                                   <div class="col-md-4">
                                      <label>Mfg. comp.</label>
                                   </div> 
                                   <div class="col-md-8">
                                        <select name="manuf_company" class="m_input_default" id="manuf_company">
                                            <option value=""> Select Manufacturing Company </option>
                                            < ?php
                                                  if(!empty($manuf_company_list))
                                                  {
                                                       foreach($manuf_company_list as $manuf_company)
                                                       {
                                                        ?>
                                                        <option value="< ?php echo $manuf_company->id;?>" < ?php if(isset($form_data['manuf_company'])&& $form_data['manuf_company']==$manuf_company->id){echo 'selected';}else{echo '';}?>>< ?php echo $manuf_company->company_name; ?></option>

                                                       < ?php }
                                                  }
                                             ?> 
                                        </select>
                                        <a href="javascript:void(0)" onclick=" return add_manuf_company();"  class="btn-new">
                                            <i class="fa fa-plus"></i> New
                                        </a>
                                        < ?php if(!empty($form_error)){ echo form_error('manuf_company'); } ?>
                                   </div>
                              </div>-->  
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Batch No.</label>
                                   </div> <!-- 4 -->
                                  <div class="col-md-8">
                                       <input type="text" maxlength="10" name="batch_no" id="batch_no" class="alpha_numeric_hyphen" value="<?php echo $form_data['batch_no']; ?>">
                                       <?php if(!empty($form_error)){ echo form_error('batch_no'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- innerRow -->

                                   <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Barcode</label>
                                   </div> <!-- 4 -->
                                  <div class="col-md-8">
                                       <input type="text" maxlength="10" name="bar_code" id="bar_code" class="alpha_numeric_space" value="<?php echo $form_data['bar_code']; ?>" onkeyup="validation_bar_code();">
                                       <div class="text-danger" id="bar_code_error"></div>
                                   </div> <!-- 8 -->

                              </div> <!-- innerRow -->
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>MRP<span class="star">*</span></label>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" maxlength="10" class="price_float" name="mrp" id="mrp" onkeypress="return isNumberKey(event);" value="<?php echo $form_data['mrp']; ?>">
                                       <?php if(!empty($form_error)){ echo form_error('mrp'); } ?>
                                       <div class="text-danger" id="mrp_error"></div>
                                   </div> <!-- 8 -->
                             </div> <!-- innerRow -->
                                                 
                             
                         </div> <!-- 6 -->
                         <div class="col-xs-6">
                             <!-- <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Unit1 Qty</label>
                                   </div> 
                                   <div class="col-md-8">
                                        <input type="text"  name="unit1_quantity" id="unit1_quantity" onkeypress="return isNumberKey(event);" value="< ?php echo $form_data['unit1_quantity']; ?>">
                                        < ?php if(!empty($form_error)){ echo form_error('unit1_quantity'); } ?>
                                   </div> 
                              </div> --><!-- innerRow -->
                              <input type="hidden"  name="unit1_quantity" id="unit1_quantity" onkeypress="return isNumberKey(event);" value="<?php echo $form_data['unit1_quantity']; ?>">
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Unit2 Qty<span class="star">*</span></label>
                                   </div> <!-- 4 -->
                                   <!--Changes by Nitin sharma 1st Feb 2024-->
                                   <div class="col-md-8">
                                       <?php $total_qty = $form_data['unit2_quantity'] ? $form_data['unit2_quantity'] : $form_data['total_quantity']?>
                                        <input type="text"  name="unit2_quantity" id="unit2_quantity" onkeypress="return isNumberKey(event);" value="<?php echo $total_qty; ?>">
                                        <?php if(!empty($form_error)){ echo form_error('unit2_quantity'); } ?>
                                   </div> <!-- 8 -->
                                   <!--Changes by Nitin sharma 1st Feb 2024-->
                              </div> <!-- innerRow -->
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Conversion<span class="star">*</span></label>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" name="conversion" id="conversion" value="<?php echo $form_data['conversion'] ?>" onchange="return check_medicine_conversion();" style="width: 200px;" class="number numeric tooltip-text numeric" <?php if(!empty($form_data['conversion'])){ echo "readonly";} ?>> 
                                        <?php if(!empty($form_error)){ echo form_error('conversion'); } ?>
                                        <div id="error" class="text-danger"></div>
                                        <div class="text-danger" id="conversion_error"></div>
                                  </div> <!-- 8 -->
                             </div> <!-- innerRow -->
                             <div class="row m-b-5">
                                   <?php //if(empty($form_data['data_id'])){?>
                                       <div class="col-md-4">
                                             <label>Expiry date <span class="star">*</span></label>
                                       </div> <!-- 4 -->
                                       <div class="col-md-8">
                                       <?php $expiry_date=date('d-m-Y',strtotime($form_data['expiry_date'])) ?>
                                            <input type="text" readonly="readonly"   class="datepicker" name="expiry_date" id="expiry_date" value="<?php if(isset($form_data['expiry_date']) && $expiry_date!='01-01-1970'){echo  date('d-m-Y',strtotime($form_data['expiry_date']));}?>" />
                                            <?php if(!empty($form_error)){ echo form_error('expiry_date'); } ?>
                                       </div> <!-- 8 --> <!-- 8 -->
                                  <?php //} ?>
                                  <input type="hidden" id="medicine_id" value=""/>
                                  <input type="hidden" name="medicine_idss" id="medicine_idss" value=""/>
                              </div> <!-- innerRow -->
                               <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <label>Purchase Rate</label>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" maxlength="10" class="price_float" name="purchase_rate" id="purchase_rate" onkeypress="return isNumberKey(event);" value="<?php echo $form_data['purchase_rate']; ?>">
                                       <?php if(!empty($form_error)){ echo form_error('purchase_rate'); } ?>
                                       <div class="text-danger" id="purchase_rate_error"></div>
                                   </div> <!-- 8 -->
                             </div> <!-- innerRow -->        
                         </div> <!-- 6 -->
                   </div> <!-- row -->
               </div> <!-- modal-body -->  
               <div class="modal-footer"> 
                    <button type="submit"  class="btn-update" name="submit">Save</button>
                    <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
               </div>
         </form>
  <script type="text/javascript">

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
  });
  </script>    
  <script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">     

<script>  

 $("#mrp").blur(function() {
  var purchas_value=$('#purchase_rate').val();
  var mrp=$('#mrp').val();
  if(purchas_value>0){
  if(mrp<purchas_value){
    $('#mrp_error').html('MRP value is greater and equal to purchase rate');
  }else{
    $('#mrp_error').html('');
  }
}

});
  $("#purchase_rate").blur(function() {
  var purchas_value=$('#purchase_rate').val();
  var mrp=$('#mrp').val();
  if(mrp>0){
  if(mrp<purchas_value){
    $('#purchase_error').html('Purchase rate must be less and equal to MRP');
  }else{
    $('#purchase_error').html('');
  }
}

}); 

function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
}

function get_city(state_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
    success: function(result)
    {
      $('#city_id').html(result); 
    } 
  }); 
}
  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
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

$('input#conversion').keypress(function(e){ 
  if (this.value.length == 0 && e.which == 48 ){
    $('#error').html('Zero not allowed');
   //return false;
   }else{
     $('#error').html('');
   }
});

 
$("#emp_type").on("submit", function(event) { 
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
    url: "<?php echo base_url('medicine_opening_stock/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_opening_stock_modal_popup').modal('hide');
        flash_session_msg(msg); 
        reload_table();
      } 
      else if(result==2)
      {
          var errormsg = 'Medicine already added in opening stock kindly modify and upgrade the quantity.';
         $('#load_add_opening_stock_modal_popup').modal('hide');
         alert(errormsg);
         return false;
        error_flash_session_msg(errormsg); 
        reload_table(); 
      }
      else
      {
        $("#load_add_opening_stock_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
  $('#load_add_unit_modal_popup').modal('hide');
});
 

function add_unit()
{

  var $modal = $('#load_add_medicine_unit_modal_popup');
  $modal.load('<?php echo base_url().'medicine_unit/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_rack_no()
{
  var $modal = $('#load_add_medicine_rack_modal_popup');
  $modal.load('<?php echo base_url().'medicine_rack/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_manuf_company()
{
  var $modal = $('#load_add_manuf_company_modal_popup');
  $modal.load('<?php echo base_url().'manuf_company/add/' ?>',
  {
    //alert();
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function get_unit()
{
   $.ajax({url: "<?php echo base_url(); ?>medicine_unit/medicine_unit_dropdown/", 
    success: function(result)
    {
     
      $('#unit_id').html(result); 
      $('#unit_second_id').html(result);
    } 
  });
}
function get_rack()
{
   $.ajax({url: "<?php echo base_url(); ?>medicine_rack/medicine_rack_dropdown/", 
    success: function(result)
    {
      $('#rack_no').html(result); 
    } 
  });
}
function get_company()
{
   $.ajax({url: "<?php echo base_url(); ?>manuf_company/manuf_company_dropdown/", 
    success: function(result)
    {
      $('#manuf_company').html(result); 
    } 
  });
}

function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result); 
    } 
  });
}
      
$('#vat').keyup(function(){
  if ($(this).val() > 100){
       alert('<?php echo get_setting_value('MEDICINE_VAT_NAME');?> should be less then 100' );
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

$('#discount').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});


$(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('medicine_opening_stock/get_medicine_name/'); ?>" + request.term,
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
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

          $('.medicine_val').val(names[0]);
          $('#conversion').val(names[1]);
          
           var conversion= $('#conversion').val(names[1]);

    if(conversion.length > 0)
    {
      $('#conversion').attr('readonly', true);
    }
          $('#mrp').val(names[2]);
          $('#manuf_company').val(names[3]);
          $('#bar_code').attr('onkeypress','validation_bar_code('+names[4]+')');
          //$('#medicine_id').val(names[4]);
          $('#medicine_idss').val(names[4]);
          $('#purchase_rate').val(names[5]);

        return false;
    }

    $(".medicine_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });

 function validation_bar_code(id){
 var bar_code =$('#bar_code').val();
   if(bar_code!='')
   {
     $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>medicine_opening_stock/check_bar_code/", 
          dataType: "json",
           data: 'mbid='+id+'&bar_code='+bar_code,
            success: function(result)
            {
             if(result==1)
                {
                  $('#bar_code_error').html('This Barcode already in used');
                }
              else
                {
                  $('#bar_code_error').html('');
                }

            } 
      });
   }
   
}

function check_medicine_conversion()
{
    var medicine_id = $('#medicine_idss').val();
    var medicine_name = $('#medicine_name').val();
    //console.log(medicine_name);
    var conversion = $('#conversion').val();
    var batch_no = $('#batch_no').val();
    //alert(medicine_id); return;
    $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>medicine_opening_stock/check_medicine_conversion/", 
          dataType: "json",
           data: 'medicine_id='+medicine_id+'&conversion='+conversion+'&batch_no='+batch_no+'&medicine_name='+medicine_name,
            success: function(result)
            {
              
                if(result.sucess==1)
                { 
                  $('#conversion').val(result.conversion);
                  $('#conversion_error').html('medicine conversion change not allowed!');
                }
                else
                { 
                  
                  $('#conversion_error').html('');
                  
                }

            } 
      });
}
</script>  

</div><!-- /.modal-dialog -->
 </div><!-- /.modal-content --> 
<div id="load_add_manuf_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
    
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
