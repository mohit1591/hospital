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
<style type="text/css">
  table th{text-transform: none !important;}
  #roomno {
    text-decoration-line:underline;
}
</style>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('670',$users_data['permission']['action'])) 
//{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('dialysis_room_list/ajax_list')?>",
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
<?php //} ?>


$(document).ready(function(){
var $modal = $('#load_add_dialysis_room_list_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'dialysis_room_list/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_dialysis_room_list(id)
{
  var $modal = $('#load_add_dialysis_room_list_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_room_list/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


function view_dialysis_room_list(id)
{
  var $modal = $('#load_add_dialysis_room_list_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_room_list/view/' ?>'+id,
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
                      url: "<?php echo base_url('dialysis_room_list/deleteall');?>",
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
   <form id="room_list_data">    
    <div class="row">
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Select Criteria</label></div>
          <div class="col-xs-7">
            <select name="criteria" onchange="select_room_type(this.value);">
              <option value="">Select</option>
              <option value="1">All</option>
              <option value="2">Room Type</option>
            </select>
          </div>
        </div>
      </div>
       <div class="col-sm-4" id="room_type">
       </div>
      
    </div>
    </form>

    <form>
       <?php //if(in_array('670',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered dialysis_room_list_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Room No. </th> 
                    <th> Room Type </th> 
                    <?php 
                    $users_data= $this->session->userdata('auth_users');

                    $get_charges= get_dialysis_room_charge_according_to_branch($users_data['parent_id']); 
                    if(!empty( $get_charges)){
                        foreach($get_charges as $charges_type){

                     ?>
                     <th> <?php echo ucfirst($charges_type->charge_type);?> </th>
                    <?php }
                    }
                  ?>
                    <th> No. of Beds </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
     <?php //} ?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right">
        <div class="btns">
               <?php //if(in_array('671',$users_data['permission']['action'])) {
               ?>
                 <button class="btn-update" id="modal_add">
                    <i class="fa fa-plus"></i> New
                 </button>
               <?php //} ?>

              <?php //if(in_array('670',$users_data['permission']['action'])) {
               ?>
             <a href="<?php echo base_url('dialysis_room_list/room_list_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('dialysis_room_list/room_list_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('dialysis_room_list/pdf_room_list'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

             <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('< ?php echo base_url("medicine_entry/print_medicine_entry"); ?>', 'windowTitle', 'width=820,height=600');">
              <i class="fa fa-print"></i> Print
              </a> -->

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("dialysis_room_list/print_room_list"); ?>');"> <i class="fa fa-print"></i> Print</a>

               <?php //} ?>
               <?php //if(in_array('673',$users_data['permission']['action'])) {
               ?>
                 <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                    <i class="fa fa-trash"></i> Delete
                 </button>
               <?php //} ?>
              
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
              
               <?php if(in_array('674',$users_data['permission']['action'])) {
               ?>
              <button class="btn-update" onclick="window.location.href='<?php echo base_url('dialysis_room_list/archive'); ?>'">
                    <i class="fa fa-archive"></i> Archive
                 </button> 
               <?php } ?>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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


 function delete_dialysis_room_list(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('dialysis_room_list/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 function reset_search()
  { 
    $('#start_date_ot').val('');
    $('#end_date_p').val('');
    $.ajax({url: "<?php echo base_url(); ?>medicine_entry?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }




  function select_room_type(room_type){
   
  if(room_type==2){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="room_type"  onkeyup="return form_submit();"/></div>');
  }

  }

  function form_submit()
  {

    $('#room_list_data').delay(200).submit();
  }

  $("#room_list_data").on("submit", function(event) { 
   event.preventDefault(); 
   $('#overlay-loader').show();

    $.ajax({
        url: "<?php echo base_url(); ?>dialysis_room_list/advance_search/",
        type: "post",
        data: $(this).serialize(),
        success: function(result) 
        {
            $('#load_add_dialysis_room_list_modal_popup').modal('hide'); 
            reload_table();       
            $('#overlay-loader').hide();
        }
    });

  });

$(document).ready(function(){
  $('#load_add_dialysis_room_list_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

</script> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

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
<!-- Confirmation Box end -->
<div id="load_add_dialysis_room_list_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</div><!-- container-fluid -->
</body>
</html>