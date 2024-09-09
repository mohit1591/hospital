<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<div class="modal-dialog emp-add-add modal-top" style="width:100%;">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="stock_item_form" class="form-inline" method="post">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo  $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body canteen_box">
              <div class="row">              
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="">Product Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control item_name"  name="item_name" value="<?php echo $form_data['item_name']; ?>">
                    <input type="hidden" class="form-control"  name="product_code" id="product_code">
                    <input type="hidden" class="form-control"  name="product_id" id="product_id" value="<?php echo $form_data['product_id']; ?>">
                    <input type="hidden" class="form-control"  name="price" id="price">
                    <input type="hidden" class="form-control"  name="quantity" id="quantity">
                <?php 
                     if(!empty($form_error)){ echo form_error('item_name'); }
                ?>

                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="">Qty.<span class="text-danger">*</span></label>
                    <input type="text" class="form-control"  name="item_qty" id="item_qty" onkeyup="increase_price(this.value);" value="<?php echo $form_data['item_qty']; ?>">
                    <input type="hidden" name="hide_qty" id="hide_qty" value="<?php echo $form_data['item_qty']; ?>">
                      <?php 
                     if(!empty($form_error)){ echo form_error('item_qty'); }
                ?>

                  </div>
                </div>
               <!-- <div class="col-lg-4">
                  <div class="form-group">
                    <label for="">Date</label>
                    <input type="date" class="form-control" value="20-01-2020">
                  </div>
                </div>-->
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label for="">Select Item</label>
                    <select name="select_item" class="form-control" id="add_item_name" >
                      <option value="">Select Item</option>
                       <?php
                      if(!empty($item_lists))
                      {
                        foreach($item_lists as $item_list)
                        {
                         /* $selected = "";
                          if($item_list->id==$form_data['select_item'])
                          {
                            $selected = 'selected="selected"';
                          }*/
                          echo '<option value="'.$item_list->id.'" >'.$item_list->product_name.'</option>';
                      
                        }
                      }
                      ?> 
                     
                    </select>
                                   
                  </div>
                       <span class="text-danger" id="error_name"></span>
                </div>
            
                <div class="col-lg-3">
                  <div class="form-group">
                    <label for="">Qty.</label>
                    <input type="text" class="form-control" placeholder="Qty." name="select_item_qty" id="add_qty">
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="reset_vgap"></div>
                  <a href="javascript:void(0);" class="btn-custom itemTableListBtn" onclick="add_item();" title="Add item">Add</a>
                </div>
              </div>

              <strong class="text-theme">Item List:</strong>
              <div class="table-responsive">
                <table class="table table-bordered" id="mytable">
                  <thead>
                    <tr>
                      <th>Sr.No.</th>
                      <th>Item Name</th>
                      <th>Qty.</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody id="item_list">
                      <?php if(!empty($lists)){ $i=1;foreach($lists as $list){
                          echo
                      '<tr id="tr_'.$list['process_id'].'"><td>'.$i.'</td><td>'.$list['item_name'].'-'.$list['item_code'].'<input type="hidden" name="comb_prod_code[]" value="'.$list['item_code'].'" id="comb_prod_code_'.$list['process_id'].'"/><input type="hidden" name="comb_prod_name[]" value="'.$list['item_name'].'" id="comb_prod_name_'.$list['process_id'].'"/></td><td>'.$list['item_qty'].'<input type="hidden" value="'.$list['item_qty'].'" name="comb_prod_qty[]" id="quantity_'.$list['process_id'].'" /></td> <td class="text-center"> <a href="javascript:void(0);" id="del_'.$list['process_id'].'" onClick="return delete_combo_item('.$list['process_id'].');" class="btn-custom" title="remove item">Delete</a></td></tr>';
                  
                 $i++; } ?>
                    <?php }else{ ?>
                     <tr class="itemTableList">
                      <td class="text-center text-danger" colspan="4">
                        Item not found!!
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
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
          <div class="col-md-4"><label for="">Add Unit</label></div>
          <div class="col-md-8">
              <input type="text" name="stock_item_unit" class="form-control" placeholder="Unit name">     
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
          <div class="col-md-4"><label for="">Add Category</label></div>
          <div class="col-md-8">
              <input type="text" name="category" class="form-control" placeholder="Category name">     
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
        $('#unit_modal').modal('hide');
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
        $('#load_add_stock_item_modal_popup').modal('hide');
        flash_session_msg(msg);
        $('#category_id').load(window.location.href  + ' #category_id');
     }
    else{
        $('#load_add_stock_item_modal_popup').modal('hide');
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
    var msg = 'Product process successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Product process successfully created.'; 
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('canteen/process/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
    
      if(result==1)
      {
        $('#load_item_inventory_import_modal_popup').modal('hide');
        
       /* $('#load_add_stock_item_modal_popup').modal('hide');*/
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
        url : "<?php echo base_url('canteen/process/get_product_values/'); ?>" + request.term,
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
          $('#item_qty').val(names[6]);
           $('#hide_qty').val(names[6]);
          $('#price').val(names[2]);
          $('#product_id').val(names[4]);
          $('#min_qty_alert').val(names[5]);
          var price=names[2];
          var quantity=1;
          var total_price=price;
          $('#total_price').val(total_price);
         
         
         
        return false;
    }

    $(".item_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() { 
              
        var prod_id=$('#product_id').val();
               prod_list(prod_id);
            //$("#default_vals").val("").css("display", 2);
        }
    });
    
    
    function prod_list(prod_id)
    {
        var qty=$('#item_qty').val();
          var hide_qty=$('#hide_qty').val();
        
          $.ajax({
        url : "<?php echo base_url('canteen/process/product_combo_list/'); ?>" + prod_id+'/'+qty+'/'+hide_qty,
        dataType: "json",
        method: 'post',
       
       success: function( data ) {
           
            $('#item_list').html(data.html_data);
            if(data!="")
            {
                 $('.itemTableList').css('display:none;');
            }
           
            
       }
           });
        
    }
    
   
    </script>
    <script>
     function increase_price(qty) 
    {
        
        var product_id=$('#product_id').val();
        var hide_qty=$('#hide_qty').val();
        
         $.ajax({
        url : "<?php echo base_url('canteen/process/product_combo_list/'); ?>" + product_id+'/'+qty+'/'+hide_qty,
        dataType: "json",
        method: 'post',
     
       success: function( data ) {
           
            $('#item_list').html(data.html_data);
            
       }
           });
    }
     function add_item() 
    {
        var product_id=$('#add_item_name').val();
        var qty=$('#add_qty').val();
        if(product_id!=''){
         $.ajax({
        url : "<?php echo base_url('canteen/process/get_code/'); ?>" + product_id+'/'+qty,
       // dataType: "json",
        method: 'post',
     
       success: function( data ) {
                var val=$.parseJSON(data);
                var i=1;
               var count= $('#mytable tr').length;
             $('#item_list').append('<tr id="tr_'+val.product_id+'"><td>'+count+'</td><td>'+val.product_name+'-'+val.product_code+'<input type="hidden" name="comb_prod_code[]" value="'+val.product_code+'" id="comb_prod_code_'+val.product_id+'"/><input type="hidden" name="comb_prod_name[]" value="'+val.product_name+'" id="comb_prod_name_'+val.product_id+'"/></td><td>'+val.qty+'<input type="hidden" value="'+val.qty+'" name="comb_prod_qty[]" id="quantity_'+val.product_id+'" /></td> <td class="text-center"> <a href="javascript:void(0);" id="del_'+val.product_id+'" onClick="return delete_combo_item('+val.product_id+');" class="btn-custom">Delete</a></td></tr>');
            
       }
           });
        }
        else{
            
            $('#error_name').text('Select Item');
              setTimeout(fade_out, 5000);
        }
    }
    function fade_out() {
          $("#error_name").fadeOut().empty();
        }
    
    function delete_combo_item(id)
    {
        $('#tr_'+id).remove();
        
    }
    
</script>
