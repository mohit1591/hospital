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
            "url": "<?php echo base_url('canteen/Item_category/ajax_list')?>",
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
var $modal = $('#load_add_Cat_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/Item_category/add/' ?>',
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
  $modal.load('<?php echo base_url().'canteen/Item_category/edit/' ?>'+id,
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
  $modal.load('<?php echo base_url().'canteen/Item_category/view/' ?>'+id,
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
                      url: "<?php echo base_url('canteen/Item_category/deleteall');?>",
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
  <div class="userlist-box canteen_box">

   <div class="row">
     <div class="col-lg-4">
        <div class="well">
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Type</label>
         </div>
         <div class="col-sm-8">
           <select name="" id="" class="form-control">
             <option value="">Select</option>
             <option value="">Combo</option>
           </select>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Name</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Product Name">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Code</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Product Code">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Weight (Kg)</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Weight (Kg)">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Category</label>
         </div>
         <div class="col-sm-8">
           <select name="" id="" class="form-control">
             <option value="">Select</option>
             <option value="">Combo</option>
           </select>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Sub Category</label>
         </div>
         <div class="col-sm-8">
           <select name="" id="" class="form-control">
             <option value="">Select</option>
             <option value="">Combo</option>
           </select>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Unit</label>
         </div>
         <div class="col-sm-8">
            <div class="input-group">
             <select name="" id="" class="form-control">
               <option value="">Select</option>
               <option value="">Kg (Kilogram)</option>
               <option value="">Gm (Grams)</option>
               <option value="">Pcs (Pieces)</option>
             </select>
             <span class="input-group-addon">
               <a href="#unit_modal" data-toggle="modal"><i class="fa fa-plus"></i> New</a>
             </span>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Cost</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Product Cost">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Price</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Product Price">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Alert Quantity</label>
         </div>
         <div class="col-sm-8">
           <input type="text" class="form-control" placeholder="Alert Quantity">
         </div>
       </div>
       <div class="row">
         <div class="col-sm-4">
           <label for="">Product Detail</label>
         </div>
         <div class="col-sm-8">
           <textarea name="" id="" rows="5" class="form-control"></textarea>
         </div>
       </div>
       </div>

     </div>
     <div class="col-lg-8">
      <div class="well">
           <div class="row">
             <div class="col-sm-2">
               <label for="">Add Product</label>
             </div>
             <div class="col-sm-5">
               <input type="text" class="form-control" placeholder="Add Product">
             </div>
           </div>
           <div class="row">
             <div class="col-sm-2">
               <label for="">Combo Product</label>
             </div>
             <div class="col-sm-10">
               <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Product (Name-Code)</th>
                      <th>Quantity</th>
                      <th>Unit Price</th>
                      <th>Total Price</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Apple <span>(apple-2500)</span></td>
                      <td><input type="text" class="form-control" value="50"></td>
                      <td><input type="text" class="form-control" value="10"></td>
                      <td>500</td>
                      <td>
                        <a href="#"><i class="fa fa-times"></i></a>
                      </td>
                    </tr>
                    <tr>
                      <td>Banana <span>(banana-1875)</span></td>
                      <td><input type="text" class="form-control" value="50"></td>
                      <td><input type="text" class="form-control" value="10"></td>
                      <td>500</td>
                      <td>
                        <a href="#"><i class="fa fa-times"></i></a>
                      </td>
                    </tr>
                  </tbody>
               </table>
             </div>
           </div>
       </div>
       
     </div>
   </div>











  </div> <!-- close -->
  <div class="userlist-right">
    <div class="btns">
      <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
        <i class="fa fa-trash"></i> Delete
     </button>
     <button class="btn-update" onclick="reload_table()">
        <i class="fa fa-refresh"></i> Reload
     </button>
     <button class="btn-update" onclick="window.location.href='<?php echo base_url('canteen/item_category/archive'); ?>'">
        <i class="fa fa-archive"></i> Archive
     </button>
     <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
        <i class="fa fa-sign-out"></i> Exit
     </button>
  </div>
</div>
</section>
<?php
$this->load->view('include/footer');
?>

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
                 url: "<?php echo base_url('canteen/Item_category/delete/'); ?>"+rate_id, 
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
  <div id="unit_modal" class="modal fade dlt-modal" data-backdrop="dynamic"  data-keyboard="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Add Unit</h4></div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Save</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->


<!-- Confirmation Box end -->
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>