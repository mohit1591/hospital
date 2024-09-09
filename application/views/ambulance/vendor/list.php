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

  var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url()?>"+uri_seg+'/ajax_list',
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
var $modal = $('#load_add_ven_modal_popup');
$('#vendor_add_modal').on('click', function(){
var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
$modal.load('<?php echo base_url() ?>'+uri_seg+'/add/',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_vendor(id)
{
  var $modal = $('#load_add_ven_modal_popup');
  var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
  //alert(uri_seg);
  //console.log(uri_seg);
  $modal.load('<?php echo base_url();?>'+uri_seg+'/edit/'+id,
  {
    //'id1': '1',
    //'id2': '2'
  },
  function(){
  $modal.modal('show');
  });
}

function view_vendor(id)
{
  var $modal = $('#load_add_ven_modal_popup');
   var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
  $modal.load('<?php echo base_url()?>'+uri_seg+'/view/'+id,
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
  var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
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
                      url: "<?php echo base_url();?>"+uri_seg+'/deleteall',
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }

  else
	{
	 $('#nochecked').modal({ backdrop: 'static', keyboard: false })
    }    
 }
</script>




</head>

<body>

<?php // echo $this->uri->segment(2);?>
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
       
    <form>
     
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive vendor_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Vendor Code </th> 
                    <th> Vendor Name </th> 
                    <th> Vendor Type</th>  
                    <th> Mobile No.</th>  
                    <th> Email</th>
                    <th> Status </th> 
                    <th width="120"> Action </th>
                </tr>
            </thead>  
        </table>
       

    </form>


   </div> <!-- close -->





    <div class="userlist-right">
      <div class="btns">
              
             <button class="btn-update" id="vendor_add_modal">
              <i class="fa fa-plus"></i> New
             </button>
              
               
             <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
              <i class="fa fa-trash"></i> Delete
             </button>
               
               

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               
               
            <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>ambulance/<?php echo $this->uri->segment(2)?>/archive'">
              <i class="fa fa-archive"></i> Archive
             </button>
             
             <a href="<?php echo base_url('ambulance/vendor/vendor_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('ambulance/vendor/vendor_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ambulance/vendor/vendor_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
           
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
 function delete_vendor(id)
 {  
 var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';  
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url(); ?>"+uri_seg+'/delete/'+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
   $('#load_add_ven_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});  
</script> 

<!-- Confirmation no select -->
<div id="nochecked" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header bg-theme"><h4>Please select at least one record! </h4></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation no select -->

<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_ven_modal_popup" class="modal fade modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>