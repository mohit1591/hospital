<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
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


<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 


<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
var save_method; 
var table;

$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/stock_item/ajax_list')?>",
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

$(document).ready(function(){
var $modal = $('#load_add_stock_item_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/stock_item/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

var $modal = $('#load_item_inventory_import_modal_popup');
      $('#open_model').on('click', function(){
      //  alert();
    $modal.load('<?php echo base_url().'canteen/stock_item/inventory_item_import_excel' ?>',
    { 
    },
    function(){
    $modal.modal('show');
    });

    });

});

function edit_stock_item(id)
{

  var $modal = $('#load_add_stock_item_modal_popup');
  $modal.load('<?php echo base_url().'canteen/stock_item/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}

function view_stock_item(id)
{
  var $modal = $('#load_add_stock_item_modal_popup');
  $modal.load('<?php echo base_url().'canteen/stock_item/view/' ?>'+id,
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
                      url: "<?php echo base_url('canteen/stock_item/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }  
    else{
           alert('Select at least one checkbox');
       }
 }
</script>

</head>

<body>

<!-- Header -->
<div class="header_top">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
</div>
<!-- Content -->
<main class="main_page">
  <div class="main_wrapper">

    <div class="main_content">
      <section>
        <table class="table table-bordered" id="table">
          <thead>
            <tr>
              <th width="40" valign="middle"><input type="checkbox"></th>
              <th>Item Code</th>
              <th>Item Name</th>
              <th>MRP</th>
              <th>Category</th>
              <th>Quantity</th>
              <th>Min. Alert</th>
              <th>Unit</th>
              <th>Status</th>
              <th>Created Date</th>
              <th width="225" class="text-center">Action</th>
            </tr>
          </thead>
         <!-- <tbody>
            <tr>
              <td width="40" valign="middle"><input type="checkbox"></td>
              <td>sara0065</td>
              <td>Testing222</td>
              <td>1299.00</td>
              <td>Cate2</td>
              <td>51</td>
              <td>5</td>
              <td>Kg</td>
              <td><span class="badge-success">Active</span></td>
              <td>17-12-2020</td>
              <td class="text-right">
                    <a href="#" class="btn-custom"><i class="fa fa-eye"></i> View</a>
                    <a href="#" class="btn-custom"><i class="fa fa-pencil"></i> Edit</a>
                    <a href="#" class="btn-custom"><i class="fa fa-trash"></i> Delete</a>
              </td>
            </tr>
          </tbody>-->
        </table>
      </section>
    </div>
    <div class="main_btns">
      <div class="fixed-top">
        <button class="btn-hmas" type="button"  id="modal_add" > <i class="fa fa-plus"></i> New</button>
          <button class="btn-hmas"  id="deleteAll" onclick="return checkboxValues();"> <i class="fa fa-trash"></i> Delete </button>
          <button class="btn-hmas" type="button" onclick="return reset();"> <i class="fa fa-refresh"></i> Reload </button>
        <a href="<?php echo base_url();?>">
          <button class="btn-hmas" type="button"><i class="fa fa-sign-out"></i> Exit</button>
        </a>
      </div>
    </div>

  </div>
  <!-- Footer -->
  <footer>
     <?php $this->load->view('include/footer'); ?> 
  </footer>
</main>

 
<script>  
function select_item_data(item_type){
   
  if(item_type==2){
     $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="item_name" placeholder="Item Name" onkeyup="return form_submit();"/></div>');
  }
   if(item_type==3){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="category"  placeholder="Category" onkeyup="return form_submit();"/></div>');  
  }
  
   if(item_type==1){
     $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="item_code"  placeholder="Item Code" onkeyup="return form_submit();"/></div>');  }

  }
  function form_submit()
  {

    $('#stock_item_search_form').delay(200).submit();
  }


  $("#stock_item_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();

    $.ajax({
          url: "<?php echo base_url(); ?>canteen/stock_item/advance_search/",
          type: "post",
          data: $(this).serialize(),
          success: function(result) 
          {
          $('#load_add_modal_popup').modal('hide'); 
          reload_table();       
          $('#overlay-loader').hide();
          }
    });
  });
 function delete_stock_item(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/stock_item/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
 function reset()
 {
     location.reload();
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

<!-- Confirmation Box end -->
<div id="load_add_stock_item_modal_popup" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_item_inventory_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->

</body>
</html>

