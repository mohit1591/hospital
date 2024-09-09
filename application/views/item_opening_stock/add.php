<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(12);

?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="item_form" class="form-inline" method="POST">
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
                            <div class="dcode"><span id="item_code_new"><?php echo $form_data['item_code']; ?></span></div>
                           <input type="hidden" name="item_code" id="item_code" readonly="readonly" value="<?php echo $form_data['item_code']; ?>" />
                           
                      </div>
                 </div> <!-- row -->


               <div class="row m-b-5">
                  <div class="col-md-4">
                     <strong>Item Name <span class="star">*</span></strong>
                  </div>
                  <div class="col-md-8">
                     <input type="text" name="item" id="item" value="<?php echo $form_data['item'];?>" class="item_val inputFocus Cap_item_name" placeholder="Enter Item"/>
                     <?php if(!empty($form_error)){ echo form_error('item'); } ?>
                     
                     <input type="hidden" id="item_id" value=""/>
                                  <input type="hidden" id="item_idss" value=""/>
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
                <strong>Item Price <span class="star">*</span></strong>
                </div>
                <div class="col-md-8">
                <input type="text" name="item_price" id="item_price" value="<?php echo $form_data['item_price']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter Price"/>

                <?php if(!empty($form_error)){ echo form_error('item_price'); } ?>  
                
                </div>
              </div>

    
             <!-- add jan 20 -->
              <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Item MRP <span class="star">*</span></label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                         <input type="text" name="mrp" id="mrp" value="<?php echo $form_data['mrp']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float" placeholder="Enter MRP"/>
                          <?php if(!empty($form_error)){ echo form_error('mrp'); } ?>  
                        <div class="text-danger" id="mrp_error"></div>
                      </div>

                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->
 

              </div><!-- left col-md-6 --> 


              <div class="col-md-6">
<!-- add jaj 20 -->

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
                        <label>Serial No.</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                        <button id="add_serial_no" value="" class='btn-custom' type="button" onclick="return add_serial(0,this.value,1)"> Add </button> </td><input type='hidden' name='serial_no_array[]' id="serial_no_array" value='<?php echo $nre;?>'><input type='hidden' name='issued_ser_id_no_array[]' id="issued_ser_id_no_array" value='<?php echo $nre_ids;?>'>
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
              <input type="submit"  class="btn-update" name="submit" value="<?php echo $button_value; ?>" />
              <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
              <!-- <button class="btn-cancel" type="button" data-number="2">Close</button> -->
         </div>
    </form>     
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">   
<script> 


$(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('item_opening_stock/get_item_name/'); ?>" + request.term,
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
            $('.item_val').val(names[0]);
            $('#conversion').val(names[1]);
            var conversion= $('#conversion').val(names[1]);
            if(conversion.length > 0)
            {
              $('#conversion').attr('readonly', true);
            }
            $('#mrp').val(names[2]);
            $('#manuf_company').val(names[3]);
            $('#item_idss').val(names[4]);
            $('#item_code_new').text(names[5]);
            $('#item_price').val(names[6]);
            
            
            
            return false;
    }

    $(".item_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });
    
    
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
    var msg = 'Item opening stock successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Item opening stock successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>item_opening_stock/"+path,
    type: "post",
    data: $(this).serialize(),
    //dataType:'json',
    success: function(result) {
    
      if(result==1)
      {
        $('#load_add_item_manage_modal_popup').modal('hide');
        flash_session_msg(msg);
		add_category();
		add_stock_item_unit();
        reload_table();
      } 

      else{
        $("#load_add_item_manage_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
}); 


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


    function add_serial(value)
    {   
        
            var ser_ar = '';
            var ser_ids_ar='';
            var str_serial= $('#serial_no_array').val();
            if(str_serial!='')
            {
               var str_serial=JSON.parse(str_serial); 
               var ser_ar=str_serial.split(',');
                
            }
            
            //get issued serial ids
            var str_issue_id_serial= $('#issued_ser_id_no_array').val();
             if(str_issue_id_serial!='')
            {
                var str_issue_id_serial=JSON.parse(str_issue_id_serial);
                if(str_issue_id_serial!='')
                {
                   var ser_ids_ar=str_issue_id_serial.split(','); 
                }
                
                
            }
            
            var conversion=$('#conversion').val();
            var unit_one=$('#stock_item_unit').val();
            var unit_two=$('#second_unit').val();
            var quantity=parseFloat(conversion)*parseFloat(unit_one)+parseFloat(unit_two);
          
           

                  if(value != '1_'+quantity)
                  {

                     $('#add_serial_no').val('1_'+quantity);

                     $('#serial_no_data').html('');

                   
                     if(quantity > 0)
                     {
                      pr="<tr><td><input type='hidden' id='serial_row_no' name='serial_row_no' value='"+value+"'></td></tr>";
                       $('#serial_no_data').append(pr);
                      
                       for(i=1;i <= quantity;i++)
                       {
                         
                         var valss = ser_ar[i-1];
        
                           if(typeof valss === 'undefined')
                           {
                             var valssw =''; 
                             
                           }
                           else
                           {
                              var valssw = ser_ar[i-1];
                           }
                           
                           var id_valss = ser_ids_ar[i-1];
                           if(typeof id_valss === 'undefined')
                           {
                             var id_valssw =''; 
                             
                           }
                           else
                           {
                              var id_valssw = ser_ids_ar[i-1];
                           }
                          
                        tr="<tr>";
                        tr+="<td>"+i+"</td><td><input type='text' value='"+valssw+"'  id='serial_"+i+"' class='serial_"+i+"'><input type='hidden' value='"+id_valssw+"'  id='issued_id_"+i+"' class='issued_id_"+i+"'></td>";
                        tr+="</tr>";
                        $('#serial_no_data').append(tr);
                        $('#save_serial_no_records').val(i);
                       
                       } 
                     }
                     else{
                        $('#serial_no_data').html("<div class='text-danger'>Insert Quantity First</div>");
                     } 
                  }
      
                 $('#serial_no').modal({
                     backdrop: 'static',
                     keyboard: false
                   })
          }

         

          function save_serial_no_records(value)
          {
             //var serial_row_no = $('#serial_row_no').val();
               rows=[];
               rows_ids=[];
               for(i=1;i <= value; i++)
               {
                 val=$('#serial_'+i).val();
                 rows.push(val);
                 valids=$('#issued_id_'+i).val();
                 if(valids!='')
                 {
                   rows_ids.push(valids);  
                 }
                 
               }
               
               
               $("#serial_no_array").val('"'+rows+'"');
               $("#issued_ser_id_no_array").val('"'+rows_ids+'"');
                $('#serial_no').modal('hide');
          }
          
          $("button[data-number=1]").click(function(){
                $('#serial_no').modal('hide');
            });
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
      

</div><!-- /.modal-dialog -->
<div id="load_add_stock_unit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-number="1" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
              <table id="serial_no_data"></table>
              </div>
             </div> 

            <div class="modal-footer">
               <!--<button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>-->
               
        
              
              <button type="button"  class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>

                <button type="button" class="btn-cancel" data-number="1">Close</button>
               
            </div>
         </div>
      </div>
   </div>

  <div id="show_serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-dismiss="modal" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
                 <table class="table table-striped table-bordered doctor_list dataTable no-footer" role="grid" aria-describedby="table_info" style="width: 100%;" width="100%" cellspacing="0" id="show_serial_no_data"></table>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
         </div>
      </div>
   </div>
