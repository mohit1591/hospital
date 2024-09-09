<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<div class="modal-dialog emp-add-add modal-top">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="stock_item_form" class="form-inline" method="post">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo  $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" data-dismiss="modal" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
           <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Item Code <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
                <input type="text" name="item_code" class="form-control"  readonly="" value="<?php echo $form_data['item_code'] ?>" >  
                
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Item Name <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
                <input type="text" name="item_name" class="form-control item_name" value="<?php echo $form_data['item']; ?>" placeholder="Enter Item name">
                <input type="hidden" name="product_id" id="product_id" class="form-control" value="<?php echo $form_data['producd_id']; ?>" >
                <?php if(!empty($form_error)){echo form_error('item_name');} ?>
              
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Mfg.Company</label>
              </div>
              <div class="col-md-8">
                <select name="manuf_company" id="" class="" style="width:180px;">
                  <option value="">Select</option>
                   <?php
                      if(!empty($manuf_company_lists))
                      {
                        foreach($manuf_company_lists as $manuf_company)
                        {
                          $selected = "";
                          if($manuf_company->id==$form_data['manuf_company'])
                          {
                            $selected = 'selected="selected"';
                          }
                          echo '<option value="'.$manuf_company->id.'" '.$selected.'>'.$manuf_company->company_name.'</option>';
                        }
                      }
                      ?> 
                </select>
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Item Category <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
               <select name="category_id" id="category_id" class="" style="width:115px;">
                  <option value="">Select</option>
                   <?php
                      if(!empty($category_lists))
                      {
                        foreach($category_lists as $category_list)
                        {
                          $selected = "";
                          if($category_list->id==$form_data['category_id'])
                          {
                            $selected = 'selected="selected"';
                          }
                          echo '<option value="'.$category_list->id.'" '.$selected.'>'.$category_list->category.'</option>';
                        }
                      }
                      ?> 
                </select>
                <a href="#category_modal" data-toggle="modal" class="btn-custom"><i class="fa fa-plus"></i> Add</a>
                   <?php if(!empty($form_error)){ echo form_error('category_id'); } ?>
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Item Price <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" name="price" id="price" value="<?php echo $form_data['price']; ?>" onkeypress="return isNumberKey(event);" placeholder="Enter Item price">
                 <?php if(!empty($form_error)){ echo form_error('price'); } ?>
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Quantity  <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-8">
                <input type="text" class="w-60px" name="quantity" id="quantity" value="<?php echo $form_data['qty']; ?>" onkeypress="return isNumberKey(event);" placeholder="Enter Qty">
                 <?php if(!empty($form_error)){ echo form_error('quantity'); } ?>
             
              
                  <select name="unit_id" id="unit1_id" class="w-50px">
                    <option value="">Select</option>
                     <?php
                      if(!empty($unit_lists))
                      {
                        foreach($unit_lists as $unit_list)
                        {
                          $selected = "";
                          if($unit_list->id==$form_data['unit_id'])
                          {
                            $selected = 'selected="selected"';
                          }
                          echo '<option value="'.$unit_list->id.'" '.$selected.'>'.$unit_list->unit.'</option>';
                        }
                      }
                      ?> 
                  </select>
                    <a href="#unit_modal" data-toggle="modal" class="btn-custom"><i class="fa fa-plus"></i> Add</a>
                </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Min. Alert</label>
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" name="min_alert" id="min_qty_alert" value="<?php echo $form_data['min_alert']; ?>" onkeypress="return isNumberKey(event);" placeholder="Enter min alert qty">
              </div>
            </div>
            
            <div class="row mb-5">
              <div class="col-md-4">
                <label for="">Status</label>
              </div>
              <div class="col-md-8">
                <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo "checked";} ?>> Active 
                <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo "checked";} ?>> Inactive 
              </div>
            </div>
            
          </div>

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" /> 
         <button type="button" data-dismiss="modal" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>   

<!-- Unit Modal -->
<div id="unit_modal" class="modal fade dlt-modal canteen_box" data-backdrop="dynamic"  data-keyboard="true">
   <div class="modal-dialog">
    <div class="modal-content">
         <form id="unit_modal_form">
     <div class="modal-header bg-theme"><h4>Add Unit</h4></div>
    
     <div class="modal-body canteen_box">
      <div class="form-group">
        <div class="row">
          <div class="col-md-4"><label for="">Add Unit<span class="text-danger">*</span></label></div>
          <div class="col-md-8">
              <input type="text" name="stock_item_unit" class="form-control" placeholder="Unit name">  
              <?php if(!empty($form_error)){ echo form_error('stock_item_unit'); } ?>
          </div>
        </div>
    </div>
 </div>
 <div class="modal-footer">
   <button type="submit" name="submit" class="btn-update">Save</button>
   <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
</div>
</form>
</div>
</div>  
</div> <!-- modal -->  

<!-- Unit Modal -->
<div id="category_modal" class="modal fade dlt-modal canteen_box" data-backdrop="dynamic"  data-keyboard="true">
   <div class="modal-dialog">
    <div class="modal-content">
         <form id="category_modal_form">
     <div class="modal-header bg-theme"><h4>Add Category</h4></div>
    
     <div class="modal-body canteen_box">
      <div class="form-group">
        <div class="row">
          <div class="col-md-4"><label for="">Add Category<span class="text-danger">*</span></label></div>
          <div class="col-md-8">
              <input type="text" name="category" class="form-control" placeholder="Category name">  
               <?php if(!empty($form_error)){ echo form_error('category'); } ?>
              
          </div>
        </div>
    </div>
 </div>
 <div class="modal-footer">
   <button type="submit" name="submit" class="btn-update">Save</button>
   <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
</div>
</form>
</div>
</div>  
</div> <!-- modal -->  

<script>
  $("#unit_modal_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  
  var ids = $('#type_id').val();
    var path = 'add/';
    var msg = 'Unit successfully created.';
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('canteen/stock_item_unit/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
    
     if(result==1)
     {
        $('#unit_modal').modal('hide');
        flash_session_msg(msg);
        $('#unit1_id').load(window.location.href  + ' #unit1_id');
     }
    else{
        $('#unit_modal').html(result);
    }
      $('#overlay-loader').hide();
  
    }
  });
}); 
  </script>
  <script>
  $("#category_modal_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  
  var ids = $('#type_id').val();
    var path = 'add/';
    var msg = 'Category successfully created.';
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('canteen/item_category/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
    
     if(result==1)
     {
        $('#category_modal').modal('hide');
        flash_session_msg(msg);
        $('#category_id').load(window.location.href  + ' #category_id');
     }
    else{
        $('#category_modal').html(result);
    }
      $('#overlay-loader').hide();
  
    }
  });
}); 
  </script>

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 $(document).ready(function(){
$("#stock_item_form").on("submit", function(event) { 
 event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = ' Stock Item successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Stock Item successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('canteen/stock_item/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
    
      if(result==1)
      {
        $('#load_add_stock_item_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
       
      } 
      else
      {
         
        $("#load_add_stock_item_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
})

/*$("button[data-number=1]").click(function(){
    $('#load_add_stock_item_modal_popup').modal('hide');
});*/



</script>  <script>
$(document).ready(function(){


})
    var i=1;
     var getData = function (request, response) { 

        row = i ;
        $.ajax({
        url : "<?php echo base_url('canteen/stock_item/get_product_values/'); ?>" + request.term,
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
       
          $('.item_name').val(names[0]);
          $('#product_code').val(names[1]);
          $('#quantity').val(1);
          $('#price').val(names[2]);
          $('#product_id').val(names[4]);
          $('#min_qty_alert').val(names[5]);
          $('#unit1_id').val(names[6]);
          $('#category_id').val(names[7]);
          
          var price=names[2];
          var quantity=1;
          var total_price=price;
          $('#total_price').val(total_price);
         /* $('#unit').val(names[2]); 
          $('#price').val(names[3]);
          $('#item_id').val(names[4]);
          $('#category_id').val(names[5]);
           $('#remaining_quantity').val(names[6]);*/
         
          //$('#medicine_id').val(names[4]);

        return false;
    }

    $(".item_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    </script>



        </div> 
    
</div> 