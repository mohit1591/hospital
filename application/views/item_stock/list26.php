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
<?php
if(in_array('984',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('item_stock/ajax_list')?>",
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
var $modal = $('#load_add_item_manage_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'item_stock/add/' ?>',
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
    $modal.load('<?php echo base_url().'item_stock/inventory_item_import_excel' ?>',
    { 
    },
    function(){
    $modal.modal('show');
    });

    });

});

function edit_item_manage(id)
{

  var $modal = $('#load_add_item_manage_modal_popup');
  $modal.load('<?php echo base_url().'item_stock/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}

function view_item_manage(id)
{
  var $modal = $('#load_add_item_manage_modal_popup');
  $modal.load('<?php echo base_url().'item_stock/view/' ?>'+id,
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
                      url: "<?php echo base_url('item_stock/deleteall');?>",
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


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    <!-- // -->
    <form id="stock_item_search_form">
<!--  <tr>
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date_ot_l" class="datepicker"  onkeyup="return form_submit();" value="<?php //echo $form_data['start_date'];?>"></td>
        <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date_ot_l" value="<?php //echo $form_data['end_date'];?>" class="datepicker"  onkeyup="return form_submit();"></td>
        <td><a href="javascript:void(0)" class="btn-a-search" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> <a class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a></td>
      </tr>  -->
     <div class="row m-b-5">
       <div class="col-md-4">
            <!-- //////////// [ start top left side ]//////////////// -->
            <div class="row">
              <div class="col-md-5">
                <label>Searching Criteria</label>
              </div>
              <div class="col-md-7">
                <select name="criteria" onchange="select_item_data(this.value);">
                  <option value="">Select</option>
                  <option value="1">Item Code</option>
                  <option value="2">Item Name</option>
                  <option value="3">Category</option>
                  <option value="4">Rack No</option>
                </select>
              </div>
            </div>
            <!-- //////////// [ ends top left side ]//////////////// -->
       </div>
       <div class="col-md-4">
         <!-- //////////// [ ends top middle section ]//////////////// -->
            <div class="row">
              <div class="col-md-12">
                <div id="room_type"></div>
              </div>
            </div>
         <!-- //////////// [ ends top middle section ]//////////////// -->
       </div>
       <div class="col-md-4">
         <!-- //////////// [ ends top right section ]//////////////// -->
         <!-- //////////// [ ends top right section ]//////////////// -->
       </div>
     </div>
 
    </form>
    
    <form>
       <?php if(in_array('984',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered list_stock_item_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Item Code </th> 
                    <th> Item Name </th> 
                    <th> MRP </th>
                    <th> Price </th>
                    <th> Category </th>
                    <th> Quantity </th>
                    <th> Min. Alert </th>
                    <th> Rack No. </th>
                    <th> Status </th> 
                    <th> Created Date </th> 
                    <th width="100"> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

   <div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;">
        <table width="200px" align="right" cellpadding="0" cellspacing="0" >
           <tr>
               <td><div class="m_alert_red_mark"></div></td>
               <td>Item minimum alert</td>
           </tr>
         
        </table>
       </div> 

    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative">
      <div class="fixed">
    		<div class="btns">
        
           <?php if(in_array('984',$users_data['permission']['action'])) {
           ?>
            <a href="<?php echo base_url('item_stock/item_stock_list_excel'); ?>" class="btn-anchor m-b-2">
            <i class="fa fa-file-excel-o"></i> Excel
            </a>

            <a href="<?php echo base_url('item_stock/item_stock_list_csv'); ?>" class="btn-anchor m-b-2">
            <i class="fa fa-file-word-o"></i> CSV
            </a>

            <a href="<?php echo base_url('item_stock/pdf_item_stock_list'); ?>" class="btn-anchor m-b-2">
            <i class="fa fa-file-pdf-o"></i> PDF
            </a>
                     
             <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("item_stock/print_item_manage_list"); ?>');"> <i class="fa fa-print"></i> Print</a>
             <?php }?>
          
            
            <?php if(in_array('984',$users_data['permission']['action'])) {
            ?>
                 <button class="btn-update" onclick="reload_table()">
                      <i class="fa fa-refresh"></i> Reload
                 </button>
            <?php } ?>
   
       
          <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
            <i class="fa fa-sign-out"></i> Exit
          </button>
    		</div>
      </div>
  	</div> 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

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

    if(item_type==4){
     $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="rack_no"  placeholder="Item Code" onkeyup="return form_submit();"/></div>');  }

  }


  function form_submit()
  {

    $('#stock_item_search_form').delay(200).submit();
  }


  $("#stock_item_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();

    $.ajax({
          url: "<?php echo base_url(); ?>item_stock/advance_search/",
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
 function delete_item_manage(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('item_stock/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 function view_inventory_entry(id)
{
  var $modal = $('#load_add_item_manage_modal_popup');
  $modal.load('<?php echo base_url().'item_stock/view_item/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
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

<!-- Confirmation Box end -->
<div id="load_add_item_manage_modal_popup" class="modal fade 11modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_item_inventory_import_modal_popup" class="modal fade 11modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div><!-- container-fluid -->
</body>
</html>