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
if(in_array('864',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() 
{ 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "searching": false,
        "bPaginate": false,
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pathology_print_test_name_setting/ajax_list')?>",
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
       <?php if(in_array('864',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive department_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th style="padding:4px 7px;"> Module </th> 
                    <th> Profile </th> 
                    <th> Print </th> 
                </tr>
            </thead>  
        </table>
        <?php } ?>
    </form>
   </div> <!-- close -->
    
    <!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>
function update_profile_status(branch_id,module,status)
{
    if(branch_id!=''){
        $.post('<?php echo base_url('pathology_print_test_name_setting/update_profile_status/'); ?>',{'branch_id':branch_id,'module':module,'status':status},function(result){
            if(result!=''){
                reload_table();
                var msg = 'pathology profile setting updated successfully.';
                flash_session_msg(msg);
            }

        })
    }
} 

function update_print_status(branch_id,module,status)
{
   
    if(branch_id!=''){
        $.post('<?php echo base_url('pathology_print_test_name_setting/update_print_status/'); ?>',{'branch_id':branch_id,'module':module,'status':status},function(result){
            if(result!=''){
                reload_table();
                var msg = 'pathology profile setting updated successfully.';
                flash_session_msg(msg);
            }

        })
    }
} 

$(document).ready(function() {
   $('#load_add_department_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});

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
<div id="load_add_department_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>