<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
 <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('946',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/products/ajax_list')?>",
            "type": "POST",
         
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php } ?>


$(document).ready(function(){
     var data_id=$('#data_id').val();
   if(data_id!="")
    {
        $('#combo_product').show(); 
    }
    else{
          $('#combo_product').hide();
    }
  
var $modal = $('#load_add_Cat_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/products/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_Category(id)
{

  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/products/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}

function view_Category(id)
{
  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/products/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
}

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        {
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('canteen/products/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 }
</script>

</head>

<body>


<div class="header_top">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
</div>
<!-- ============================= Main content start here ===================================== -->
<main class="main_page">
    <form method="post" action="<?php echo current_url();?>">
        <input type="hidden" name="data_id" id="data_id" value="<?php  echo $form_data['data_id'];?>">
  <div class="main_wrapper">
      
    <div class="main_content">
         <div class="row">
           <div class="col-lg-4">
              <div class="well">
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Type<span class="text-danger">*</span></label>
               </div>
               <div class="col-sm-8">
                 <select name="product_type" id="" class="form-control" onchange="return typeChange(this.value);">
                   <option value="">Select</option>
                   <option value="1" <?php if($form_data['product_type']==1){ echo "selected";} ?>>Single</option>
                   <option value="2" <?php if($form_data['product_type']==2){ echo "selected";} ?>>Combo</option>
                 </select>
                   <?php if(!empty($form_error)){ echo form_error('product_type'); } ?>
               
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Name<span class="text-danger">*</span></label>
               </div>
               <div class="col-sm-8">
                 <input type="text" class="form-control" name="product_name" placeholder="Product Name" value="<?php echo $form_data['product_name']; ?>">
                  <?php if(!empty($form_error)){ echo form_error('product_name'); } ?>
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Code<span class="text-danger">*</span></label>
               </div>
               <div class="col-sm-8">
                 <input type="text" class="form-control" name="product_code" placeholder="Product Code" value="<?php echo $form_data['product_code']; ?>" readonly>
                  <?php if(!empty($form_error)){ echo form_error('product_code'); } ?>
               </div>
             </div>
            <!-- <div class="row">
               <div class="col-sm-4">
                 <label for="">Weight (Kg)</label>
               </div>
               <div class="col-sm-8">
                 <input type="text" class="form-control" name="product_name" placeholder="Weight (Kg)"  value="<?php echo $form_data['weight']; ?>">
               </div>
             </div>-->
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Category</label>
               </div>
               <div class="col-sm-8">
                 <select name="product_category" id="category_id" class="form-control">
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
                 <?php if(!empty($form_error)){echo form_error('product_category');} ?>
               </div>
             </div>
         
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Unit</label>
               </div>
               <div class="col-sm-8">
                  <div class="input-group" id="unit_id">
                   <select name="unit_id" id="unit" class="form-control">
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
                   <span class="input-group-addon">
                     <a href="#unit_modal" data-toggle="modal" title="Add unit"><i class="fa fa-plus"></i> New</a>
                   </span>
                  
                 </div>
               </div>
             </div>
              <div class="row">
               <div class="col-sm-4">
                 <label for="">Quantity</label>
               </div>
               <div class="col-sm-8">
                 <input type="text" name="quantity" class="form-control" placeholder="Quantity" value="<?php echo $form_data['quantity']; ?>">
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Cost</label>
               </div>
               <div class="col-sm-8">
                 <input type="text" name="product_cost" class="form-control" placeholder="Product Cost" value="<?php echo $form_data['product_cost']; ?>">
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Price</label>
               </div>
               <div class="col-sm-8">
                 <input type="text" name="product_price" class="form-control" placeholder="Product Price" value="<?php echo $form_data['product_price']; ?>">
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Alert Quantity</label>
               </div>
               <div class="col-sm-8">
                 <input type="text" name="min_qty_alert" class="form-control" placeholder="Alert Quantity" value="<?php echo $form_data['alert_qty']; ?>">
               </div>
             </div>
             <div class="row">
               <div class="col-sm-4">
                 <label for="">Product Detail</label>
               </div>
               <div class="col-sm-8">
                 <textarea name="product_detail" id="" rows="5" class="form-control"><?php echo $form_data['product_detail']; ?></textarea>
               </div>
             </div>
             </div>

           </div>
           <div class="col-lg-8" id="combo_product">
            <div class="well">
                 <div class="row">
                   <div class="col-sm-2">
                     <label for="">Add Item</label>
                   </div>
                   <div class="col-sm-5">
                     <input type="text" class="product_name txt_firstCap"  placeholder="Add Item">
                     <span class="text-danger" id="error_name"></span>
                     <input type="hidden"  name="product_id"  id="product_id">
                     <input type="hidden"  name="comb_prod_name"  id="comb_prod_name">
                     <input type="hidden"   name="product_code1"  id="product_code">
                     <input type="hidden"    id="quantity">
                     <input type="hidden"  name="price"  id="price">
                     <input type="hidden"  name="total_price"  id="total_price">
                    <!-- <input type="hidden"  name=""  placeholder="Add Product">-->
                   </div>
                    <a class="btn-custom" onclick="item_payment_calculation();" title="Add Item">Add</a>
                 </div>
                
                 <div class="row">
                   <div class="col-sm-2">
                     <label for="">Combo Product</label> 
                   </div>
                   <div class="col-sm-10" >
                   <table class="table table-bordered" id="item_list">
                        <thead>
                          <tr>
                            <th>Product (Name-Code)</th>
                            <th class="text-center" style="width:50px;">Quantity</th>
                            <th class="text-center" style="width:80px;">Total Price</th>
                            <th class="text-center" style="width:80px;">Action</th>
                          </tr>
                        </thead>
                        <tbody id="ite_list">
                            <?php $combo_lists=$this->products->combo_prod_list($form_data['data_id']);
                            $this->session->set_userdata('product_combo_list',$combo_lists);
                            foreach($combo_lists as $combo_list){
                            ?>
                          <tr>
                            <td><?php echo $combo_list['product_name'];?>-<?php echo $combo_list['comb_prod_code'];?><input type="hidden" name="comb_prod_code[]" value="<?php echo $combo_list['comb_prod_code'];?>" id="product_code_'<?php echo $combo_list['combo_id'];?>'"/><input type="hidden" name="comb_prod_name[]" value="<?php echo $combo_list['comb_prod_name'];?>" id="product_name_'<?php echo $combo_list['combo_id'];?>'"/></td>
                            <td><input type="text"  name="comb_prod_qty[]" id="quantity_'<?php echo $combo_list['combo_id'];?>" class="form-control" value="<?php echo $combo_list['comb_prod_qty'];?>"></td>
                            <td><input type="text" name="comb_prod_price[]" id="quantity_'<?php echo $combo_list['combo_id'];?>" class="form-control" value="<?php echo $combo_list['comb_prod_price'];?>"></td>
                            <!--<td class="text-center"><?php echo $combo_list['product_name'];?></td>-->
                            <td class="text-center">
                              <a href="javascript:void(0);" id="del_'<?php echo $combo_list['combo_id']; ?>" onClick="return delete_combo_product(<?php echo $combo_list['combo_id']; ?>);" class="btn-custom" title="Delete">Delete</a>
                            </td>
                          </tr>
                          <?php } ?>
                       
                        </tbody>
                     </table>
                   </div>
                 </div>
             </div>
             
           </div>
         </div>
   </div>
   <div class="main_btns">
    <div class="fixed-top">
          <button class="btn-hmas" type="submit"> <i class="fa fa-floppy-o"></i> Save </button>
        <!--  <button class="btn-hmas" type="button"> <i class="fa fa-trash"></i> Delete </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-refresh"></i> Reload </button>-->
          <a href="<?php echo base_url('canteen/products');?>">
            <button class="btn-hmas" type="button"> <i class="fa fa-sign-out"></i> Exit </button>
          </a>
    </div>
   </div>
   </form>
   <footer>
     <?php $this->load->view('include/footer'); ?>
   </footer>
</main>






<script>  

 function delete_Category(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/products/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

</script> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->


 <!-- Unit Modal -->
<div id="unit_modal" class="modal fade dlt-modal canteen_box" data-backdrop="dynamic"  data-keyboard="true">
   <div class="modal-dialog">
    <div class="modal-content">
         <form id="unit_modal_form">
     <div class="modal-header bg-theme"><h4>Add Unit</h4></div>
    
     <div class="modal-body canteen_box">
      <div class="form-group">
        <div class="row">
          <div class="col-md-4"><label for="">Add Unit</label><span class="text-danger">*</span></div>
          <div class="col-md-8">
              <input type="text" name="stock_item_unit" class="form-control" placeholder="Unit name">  
              <?php if(!empty($form_error)){echo form_error('stock_item_unit');} ?>
          </div>
         
        <!--<div class="row">
          <div class="col-md-4"><label for="">Status</label><span class="text-danger">*</span></div>
          <div class="col-md-8">
              <input type="radio" name="status" value="1" class="form-control" >Active  
              <input type="radio" name="status" value="0"class="form-control" >In Active  
              <?php if(!empty($form_error)){echo form_error('status');} ?>
          </div>
        </div>-->
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
    </div> <!-- modal -->


<!-- Confirmation Box end -->
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
<script>

function typeChange(val)
{
    if(val==2)
    {
        $('#combo_product').show();
    }
   
    else{
        $('#combo_product').hide();
    }
}

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
        $('#unit_id').load(window.location.href  + ' #unit_id');
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
$(document).ready(function(){


})
    var i=1;
     var getData = function (request, response) { 

        row = i ;
        $.ajax({
        url : "<?php echo base_url('canteen/products/get_product_values/'); ?>" + request.term,
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
       
          $('.product_name').val(names[0]);
          $('#product_code').val(names[1]);
          $('#quantity').val(1);
          $('#price').val(names[2]);
           $('#product_id').val(names[4]);
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

    $(".product_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    
      function item_payment_calculation()
  {
    var product_name = $('.product_name').val();
    var price = $('#price').val();
    var product_code = $('#product_code').val();
    var product_id = $('#product_id').val();
    var quantity = $('#quantity').val();
     var total_price = $('#total_price').val();
   /* var item_code = $('#item_code').val();
    var item_id = $('#item_id').val();
    var category_id = $('#category_id').val();
    var remaining_quantity = $('#remaining_quantity').val();
    var unit = $('#unit').val();
    var item_price= $('#price').val();
    var total_amount = $('#total_amount').val();
    var item_code = $('#item_code').val();
    var total_price = $('#total_price').val();*/

    if(product_name!='')
    {
     $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>canteen/products/item_payment_calculation/", 
            dataType: "json",
            data: 'price='+price+'&quantity='+quantity+'&product_name='+product_name+'&total_price='+total_price+'&product_id='+product_id+'&product_code='+product_code,
            success: function(result)
            {
               if(result.error==1)
               {
                   $('#error_message').html(result.message);
               }
               else
               {
                    $('#ite_list').html(result.html_data);
                    $('.product_name').val('');
                    $('#price').val('');
                    $('#total_price').val('');
                    $('#quantity').val('1');  
               }
             
            } 
          });
      }
      else{
          $('#error_name').text('Enter Item name');
          setTimeout(fade_out, 5000);


      }
   
  }
  function fade_out() {
  $("#error_name").fadeOut().empty();
}
  function delete_combo_item(id)
  {
   
   if(id!="")
   {
      $.ajax({
              type: "POST", 
              url: "<?php echo base_url('canteen/products/remove_stock_purchase_item_list');?>",
               dataType: "json",
              data: {product_id: id},
              success: function(result) 
              {  
              $('#ite_list').html(result.html_data); 
              /*  payment_new_calc_all();*/
                $('.product_name').val('');
               

              }
          });
   }
  
  }
  
   function delete_combo_product(id)
  {
   
   if(id!="")
   {
      $.ajax({
              type: "POST", 
              url: "<?php echo base_url('canteen/products/remove_combo_item_list');?>",
               dataType: "json",
              data: {id: id},
              success: function(result) 
              {  
             // $('#item_list').html(result.html_data); 
              $('#item_list').load(location.href + " #item_list");
              /*  payment_new_calc_all();*/
                $('.product_name').val('');
               

              }
          });
   }
  
  }
  
  function cal_price(id)
  {
      var qty=$('#quantity_'+id).val();
      var price=$('#total_price_'+id).val();
       var pri = parseInt(price);
      var quantity = parseInt(qty);
      var total_price=price*qty;
     
      $('#total_price_'+id).val(total_price);
       $('#total_price_'+id).val($('#total_price_'+id).val() * $('#quantity_'+id).val());
  }
</script>
</html>