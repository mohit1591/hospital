<?php
$users_data = $this->session->userdata('auth_users');
$user_role= $users_data['users_role'];
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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
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
            "url": "<?php echo base_url('crm/crm_patient/ajax_list')?>",
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
       <!-- bootstrap data table -->
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th class="table-align">Lead ID</th>
                    <th class="table-align">Department</th>
                    <th class="table-align">Lead Type</th>
                    <th class="table-align">Source</th> 
                    <th class="table-align">Name</th>  
                    <th class="table-align">Phone</th>   
                    <th class="table-align">Follow-Up Date/Time</th> 
                    <th class="table-align">Appointment Date/Time</th> 
                    <th class="table-align">Created By</th> 
                    <th class="table-align">Created Date</th> 
                    <th class="table-align">Action</th> 
                </tr>
            </thead>  
        </table>
        </div> 


    </form>


   </div> <!-- close -->





    <div class="userlist-right relative">
        <div class="fixed">
      <div class="btns opd_booking_list_right_btns"> 
      
      <a href="<?php echo base_url('crm/crm_patient/crm_patient_excel'); ?>" class="btn-anchor m-b-2" >
              <i class="fa fa-file-excel-o"></i> Excel
              </a>
              
              <a href="<?php echo base_url('crm/crm_patient/crm_patient_pdf'); ?>" class="btn-anchor m-b-2" >
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("crm/crm_patient/crm_patient_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
              
              
        <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
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
 

</body>
</html>