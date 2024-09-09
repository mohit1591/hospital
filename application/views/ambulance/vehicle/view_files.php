<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
<?php $users_data = $this->session->userdata('auth_users'); ?>

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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2458',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ambulance/vehicle/ajax_file_list/'.$vehicle_id)?>",
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
                      url: "<?php echo base_url('ambulance/vehicle/deleteall_document_file');?>",
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


/* function upload_prescription(id)
 {
    var $modal = $('#load_add_modal_neww_popup');
    $modal.load('<?php echo base_url().'ambulance/vehicle/upload_document/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }*/
  
  $(document).ready(function(){
    var $modal = $('#load_add_modal_neww_popup');
    $('#modal_add').on('click', function(){
        
    $modal.load('<?php echo base_url().'ambulance/vehicle/upload_document/' ?>'+<?php echo $vehicle_id; ?>,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
    
    });
    
});
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
        
         <div class="row">
             <div class="col-sm-4">
                 </div>
      <div class="col-sm-4">
        <div class="row m-b-5" >
          <div class="col-xs-5"><label>Vehicle No.: </label></div>
          <div class="col-xs-7">
            <?php echo $vehicle_no; ?>
          </div>
        </div>
        
        </div>
        </div>
  
    <form>
    <?php if(in_array('2458',$users_data['permission']['action'])) {
       ?>
        <table id="table" class="table table-striped table-bordered prescription_list_file" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Document File </th> 
                    <th> Document Name </th> 
                    <th> Renewal Date </th> 
                    <th> Expiry Date </th>
                    <th> Status </th>  
                    <th> Remark </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
    <?php } ?>

    </form>


   </div> <!-- close -->





    <div class="userlist-right">
      <div class="btns">
        
       <?php if(in_array('2457',$users_data['permission']['action'])) {
       ?>
         <button class="btn-update" id="modal_add">
          <i class="fa fa-plus"></i> New
        </button>
        <!--<button class="btn-update" id="new" onclick="return upload_prescription('<?php echo $vehicle_id; ?>')">
          <i class="fa fa-plus"></i> New
        </button>-->
    <?php }  if(in_array('2459',$users_data['permission']['action'])) {
       ?>
        <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
          <i class="fa fa-trash"></i> Delete
        </button>
    <?php } ?>
        <button class="btn-update" onclick="reload_table()">
          <i class="fa fa-refresh"></i> Reload
        </button>
       
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('ambulance/vehicle'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
    </div> 
  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>  
 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
 function delete_document_file(vehicle_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ambulance/vehicle/delete_document_file/'); ?>"+vehicle_id, 
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

<!-- Confirmation Box end -->
<div id="load_add_modal_neww_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!----container-fluid--->
</body>
</html>