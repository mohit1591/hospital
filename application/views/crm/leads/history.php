<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title>CRM | Lead History (<?php echo $lead_data['crm_code']; ?>)</title>
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
if(in_array('2439',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('crm/leads/ajax_list_history/'.$lead_id)?>",
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
                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Lead ID: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['crm_code']; ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Lead Type: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['lead_type']; ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Department: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['department']; ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Name: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['name']; ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Phone: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['phone']; ?>
                            </div>
                        </div>

                        <div class="form-group col-sm-4">
                            <label class="col-sm-4 fleft bold">Email: </label>
                            <div class="col-sm-8 fleft">
                                <?php echo $lead_data['email']; ?>
                            </div>
                        </div>
                    </div>     
  <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
         
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>

    <form>
       <?php if(in_array('2434',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered disease_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr> 
                    <th class="table-align">S.No.</th>
                    <th class="table-align">Call Date</th>
                    <th class="table-align">Call Time</th>
                    <th class="table-align">Follow-Up Date</th>
                    <th class="table-align">Follow-Up Time</th> 
                    <th class="table-align">Status</th>  
                    <th class="table-align">Remarks</th>  
                    <th class="table-align">Created Date</th>  
                </tr>
            </thead>  
        </table>
     <?php } ?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right">
        <div class="btns"> 
                 
               <?php if(in_array('2434',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?> 
                <button class="btn-update" onclick="window.location.href='<?php echo base_url('crm/leads'); ?>'">
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


 function delete_disease(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('disease/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
   $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});  
</script> 
<!-- Confirmation Box -->

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
<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
</body>
</html>