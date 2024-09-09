<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(12);

?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="item_form" class="form-inline">
               <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <!-- ============================================================================================ -->
       <div class="modal-body"> 

               <div class="row">
                <div class="col-md-6">
        
                 <div class="row m-b-5">
                      <div class="col-md-4">
                           <strong>Item Code<span class="star">*</span></strong>
                      </div>
                      <div class="col-md-8">
                            <div class="dcode"><?php echo $form_data['item_code']; ?></div>
                           <input type="hidden" name="item_code" id="item_code" readonly="readonly" value="<?php echo $form_data['item_code']; ?>" />
                           
                      </div>
                 </div> <!-- row -->


               <div class="row m-b-5">
                  <div class="col-md-4">
                     <strong>Item Name <span class="star">*</span></strong>
                  </div>
                  <div class="col-md-8">
                     <input type="text" name="item" value="<?php echo $form_data['item'];?>" class="Cap_item_name"/>
                     <?php if(!empty($form_error)){ echo form_error('item'); } ?>
                  </div>
               </div> <!-- row -->

    
               <div class="row m-b-5">
                  <div class="col-md-4">
                  <label>Mfg.Company</label>
                 </div>
                  <div class="col-md-8">
                  <select name="manuf_company" class="m_select_btn" id="manuf_company">
                    <option value=""> Select Mfg.Company</option>
                    <?php
                      if(!empty($manuf_company_list))
                      {
                        foreach($manuf_company_list as $manuf_company)
                        {
                          ?>
                          <option value="<?php echo $manuf_company->id; ?>" <?php if($manuf_company->id==$form_data['manuf_company']){ echo 'selected="selected"'; } ?>><?php echo $manuf_company->company_name; ?></option>
                          <?php
                        }
                      }
                     ?> 
                  </select>
                   
                    
                </div>
               </div> <!-- row -->



            <div class="row m-b-5">
              <div class="col-md-4">
                <strong>Item Category <span class="star">*</span></strong>
              </div>
              <div class="col-md-8">
                <select name="category_id" id="category_id" class="pat-select1 media_100px w-145px" >
                  <option value="">Select Category</option>
                  <?php
                     if(!empty($category_list)){
                      foreach($category_list as $category){
                        $selected_category = '';
                        if($category->id==$form_data['category_id']){
                           $selected_category = 'selected="selected"';
                        }
                       echo '<option value="'.$category->id.'" '.$selected_category.'>'.$category->category.'</option>';
                      }
                    }
                  ?> 
                </select> 
                <?php if(in_array('176',$users_data['permission']['action'])) {
                ?>
                <a href="javascript:void(0)" onclick="return add_category();"  class="btn-new">
                   <i class="fa fa-plus"></i> Add
                </a>
                <?php } ?>
                <?php if(!empty($form_error)){ echo form_error('category_id'); } ?>

              </div>
            </div> <!-- row -->


              <div class="row m-b-5">
                <div class="col-md-4">
                <strong>Item Price             
                <?php if(!empty($field_list)){
                  if($field_list[0]['mandatory_field_id']=='57' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                       <span class="star">*</span>
                    <?php 
                    }
                  } ?> 
                </strong>
                </div>
                <div class="col-md-8">
                <input type="text" name="item_price" id="item_price" value="<?php echo $form_data['item_price']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter Price"/>

                <?php if(!empty($field_list)){
                  
                if($field_list[0]['mandatory_field_id']=='57' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                    if(!empty($form_error)){ echo form_error('item_price'); }
                    }
                  else
                  { 
                  echo form_error('item_price');
                  }
                    } ?>  
                
                </div>
              </div>

    
             <!-- add jan 20 -->
              <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Item MRP</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                         <input type="text" name="mrp" id="mrp" value="<?php echo $form_data['mrp']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter MRP"/>
                        <div class="text-danger" id="mrp_error"></div>
                      </div>

                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->
 

              </div><!-- left col-md-6 --> 


              <div class="col-md-6">
<!-- add jaj 20 -->
               
  <div class="row m-b-5">
                  <div class="col-md-4">
                   <label>Unit 1 QTY<span class="star"></span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a bunddle of inventory item</span></a>
                     </sup></label>
                  </div>
                   <div class="col-md-8">

                     <input type="text" name="stock_item_unit" id="stock_item_unit" value="<?php echo $form_data['stock_item_unit'] ?>" 
                     data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter Unit 1 QTY"/>
                    <?php if(!empty($form_error)){ echo form_error('stock_item_unit'); } ?>
                  </div>
                </div>

                <div class="row m-b-5">
                  <div class="col-md-4">
                  <label>Unit 2 QTY<span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a Inventory item from open bunddle</span>
                              </a>
                              </sup></label>
                  </div>
                   <div class="col-md-8">

                     <input type="text" name="second_unit" id="second_unit" value="<?php echo $form_data['second_unit'] ?>" 
                     data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter Unit 2 QTY"/>
                      <?php if(!empty($form_error)){ echo form_error('second_unit'); } ?>

                  </div>
                </div>

                <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Conversion<span class="star">*</span></label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                        <input type="text" class="m_input_default" name="conversion" id="conversion" value="<?php echo $form_data['conversion'] ?>" onkeypress="return isNumberKey(event);" placeholder="Enter Conversion"> 
                        <?php if(!empty($form_error)){ echo form_error('conversion'); } ?>
                        <div id="error" class="text-danger"></div>
                      </div> <!-- 8 -->
                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->

               <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Packing<span class="star"></span></label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                        <input type="text" class="m_input_default" maxlength="10" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" placeholder="Enter Packing">
                        <?php // if(!empty($form_error)){ echo form_error('packing'); } ?>
                        <div id="error" class="text-danger"></div>
                      </div> <!-- 8 -->
                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->
<!-- add jaj 20 -->
              <?php /* ?>
                <div class="row m-b-5">
                  <div class="col-md-4">
                  <strong>Min. Alert</strong>
                  </div>
                   <div class="col-md-8">
                     <input type="text" name="min_alert" id="min_alert" value="<?php echo $form_data['min_alert']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter min alert"/>
                  </div>
                </div> 
              <?php */ ?>
                
<!-- add jaj 20 -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Rack No.</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="rack_no" class="m_select_btn" id="rack_no">
                              <option value=""> Select Rack No. </option>
                              <?php
                                if(!empty($rack_list))
                                {
                                  foreach($rack_list as $rack)
                                  {
                                    ?>
                                    <option value="<?php echo $rack->id; ?>" <?php if($rack->id==$form_data['rack_no']){ echo 'selected="selected"'; } ?>><?php echo $rack->rack_no; ?></option>
                                    <?php
                                  }
                                }
                               ?> 
                            </select>
                            <?php //if(!empty($form_error)){ echo form_error('rack_no'); } ?>
                            <a href="javascript:void(0)" onclick=" return add_rack_no();"  class="btn-new">
                              <i class="fa fa-plus"></i> Add
                            </a>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row --> 


<!-- add jaj 20 -->

              
                 <div class="row m-b-5">
                  <div class="col-md-4">
                     <label><strong>Status</strong></label>
                  </div>
                  <div class="col-md-8">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
                  </div>
               </div>

             </div><!-- right col-md-6 --> 
            </div><!--  row --> 
         </div> <!--  modal-body --> 

         <div class="modal-footer"> 
              <input type="submit"  class="btn-update" name="submit" value="Save" />
              <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
              <!-- <button class="btn-cancel" type="button" data-number="2">Close</button> -->
         </div>
    </form>     

<script> 
$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});



function add_second_unit(unit_id)
{
   $('#unit_second_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('item_opening_stock/get_second_unit')?>",
        data: {'unit_id' : unit_id},
       success: function(msg){
         $('#unit_second_detail').html(msg);
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


 
$("#item_form").on("submit", function(event) { 
 // alert();
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#data_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Item manage successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Item manage successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>item_opening_stock/"+path,
    type: "post",
    data: $(this).serialize(),
    //dataType:'json',
    success: function(result) {
    
      if(result==1)
      {
        $('#load_item_inventory_import_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg);
        reload_table();
      } 

      else{
        $("#load_item_inventory_import_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
}); 



function add_item_manage_vendor()
{ 
  var $modal = $('#load_add_item_manage_modal_popup');
  $modal.load('<?php echo base_url().'item_opening_stock/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
    function(){
  $modal.modal('show');
  }); 
}

function add_category()
{ 
  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'item_category/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_stock_item_unit()
{ 
  var $modal = $('#load_add_stock_unit_modal_popup');
  $modal.load('<?php echo base_url().'stock_item_unit/add/' ?>',
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
  $modal.load('<?php echo base_url().'inventory_rack/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


$("button[data-number=1]").click(function(){
    $('#load_add_item_manage_modal_popup').modal('hide'); 
});
$("button[data-number=2]").click(function(){
    $('#load_item_inventory_import_modal_popup').modal('hide'); 
});



$(".Cap_item_name").on('keyup', function(){

   var str = $('.Cap_item_name').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.Cap_item_name').val(part_val.join(" "));
  
  });


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

$('#cgst').keyup(function(){
  if ($(this).val() > 100){
      alert('CGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#sgst').keyup(function(){
  if ($(this).val() > 100){
      alert('SGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#igst').keyup(function(){
  if ($(this).val() > 100){
      alert('IGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
      

</div><!-- /.modal-dialog -->
<div id="load_add_stock_unit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_item_manage_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
