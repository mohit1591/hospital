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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>


<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('415',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('medicine_opening_stock/ajax_list')?>",
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
var $modal = $('#load_add_modal_popup');
$('#medicine_opening_stock_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'medicine_opening_stock/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});
$(document).ready(function(){
var $modal = $('#load_add_opening_stock_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'medicine_opening_stock/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});
function edit_opening_stock(id,stock_id)
{
   var batch_no= $('#batch_no_'+stock_id).val();
   var $modal = $('#load_add_opening_stock_modal_popup');
  $modal.load('<?php echo base_url().'medicine_opening_stock/edit/' ?>'+id+'/'+batch_no,
  {
    //'id1': '1',
    //'id2': '2'
   },
  function(){
  $modal.modal('show');
  });
}

function view_medicine_opening_stock(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'medicine_opening_stock/view/' ?>'+id,
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
                      url: "<?php echo base_url('medicine_opening_stock/deleteall');?>",
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
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
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
    
    	 
    <form>
       <?php if(in_array('415',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_opening_stock_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                      <th> Medicine Name </th> 
                      <th> Batch No. </th> 
                      <th> Barcode</th> 
                      <th> Quantity</th> 
                      <th> Expiry Date</th>
                      <th> Min. Alrt</th> 
                      <th> MRP</th> 
                      <th> Purchase Rate</th> 
                      <th>Action</th>
               </tr>
            </thead>  
        </table>
        <?php } ?>
        <div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;">
          <table width="200px" align="right" cellpadding="0" cellspacing="0" >
            
            
             <tr>
                 <td><div class="m_alert_green_mark"></div></td>
                 <td>Medicine minumum alert</td>
             </tr>
             <tr>
                 <td><div class="m_alert_orange_mark"></div></td>
                 <td>Medicine Near to Expire</td>
             </tr>
             <tr>
                 <td><div class="m_alert_red_mark"></div></td>
                 <td>Medicine near to expire</td>
             </tr>
          </table>
       </div> 
    </form>


   </div> <!-- close -->





  	<div class="userlist-right">
  		<div class="btns">
             <?php if(in_array('625',$users_data['permission']['action'])) {
               ?>
                <button class="btn-update" id="modal_add">
            <i class="fa fa-plus"></i> New
          </button>
          <?php } if(in_array('627',$users_data['permission']['action'])) { ?>
                <a href="<?php echo base_url('medicine_opening_stock/medicine_opening_stock_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample(.xls)
                </a>
          <?php } if(in_array('628',$users_data['permission']['action'])) { ?>
                 <a id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import(.xls)
                </a>
                <?php } ?>
                <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
               <button class="btn-exit">
                    <i class="fa fa-sign-out"></i> Exit
               </button>
  		</div>
  	</div> 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>  

 function delete_opening_stock(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('medicine_opening_stock/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 $(document).ready(function() {
   $('#load_add_opening_stock_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
</script> 

<div id="confirm-select" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Please select at-least one record.</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal --> 

<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_opening_stock_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_medicine_opening_stock_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_stock').on('click', function(){
  //  alert();
$modal.load('<?php echo base_url().'medicine_opening_stock/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
  var $modal = $('#load_medicine_opening_stock_modal_popup');
  $('#open_model').on('click', function(){
  //  alert();
$modal.load('<?php echo base_url().'medicine_opening_stock/medicine_opening_stock_import_excel' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

 


 function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};


</script>
<!-- container-fluid -->
</body>
</html>