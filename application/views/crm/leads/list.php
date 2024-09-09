<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- boot210strap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<style>
.row_hide{display:none;}
</style>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_PLUGIN_PATH; ?>bootstrap-timepicker/css/bootstrap-timepicker.css">
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script> 
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2430',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('crm/leads/ajax_list')?>",
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
    <!-- <form name="search_form_list"  id="search_form_list">  -->

    <div class="row">
      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-4"><label>From Date</label></div>
          <div class="col-xs-8">
            <input id="start_date" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div> 
      </div> <!-- 4 -->

      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-4"><label>To Date</label></div>
          <div class="col-xs-8">
            <input name="end_date" id="end_date" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>   
      </div> <!-- 4 -->

      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-4"><label>Maturity</label></div>
          <div class="col-xs-8">
            <select class="m_input_default" name="lead_maturity" id="lead_maturity" onchange="return form_submit();">
                <option value="">All</option>
                <option value="1">Booked</option>
                <option value="2">Closed</option> 
                <option value="3">Booked/Closed</option>
            </select> 
          </div>
        </div> 
      </div>


      <div class="col-sm-3 text-right">
            <a class="btn-custom" id="reset_date" onclick="reset_date_search(this.form);">Reset</a>
          <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a>
          
      </div> <!-- 4 -->
    </div> <!-- row -->
  
         
  <!--   </form> -->


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
       <?php if(in_array('2430',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered disease_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th class="table-align">Lead ID</th>
                    <th class="table-align">Department</th>
                    <th class="table-align">Lead Type</th>
                    <th class="table-align">Source</th> 
                    <th class="table-align">Name</th>  
                    <th class="table-align">Phone</th>   
                    <th class="table-align">Follow-Up Date/Time</th> 
                    <th class="table-align">Appointment Date/Time</th> 
                    <th class="table-align">Last Remarks</th> 
                    <th class="table-align">Created By</th> 
                    <th class="table-align">Status</th> 
                    <th class="table-align">Action</th> 
                </tr>
            </thead>  
        </table>
     <?php } ?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right">
        <div class="btns">
               <?php if(in_array('2431',$users_data['permission']['action'])) {
               ?> 
                 <button class="btn-update" style="width: 100px; data-toggle="tooltip" title="Add new patient" onclick="window.location.href='<?php echo base_url('crm/leads/add'); ?>'">
                <i class="fa fa-plus"></i> New
            </button>
               <?php } ?> 
                 
               <?php if(in_array('2430',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" style="width: 100px;" onclick="return send_sms();" >
                         <i class="fa fa-paper-plane" aria-hidden="true"></i> SMS/Email  
                    </button>

                    <button class="btn-update" style="width: 100px; onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?> 
               
               <a href="<?php echo base_url('crm/leads/lead_excel'); ?>" class="btn-anchor m-b-2" style="width:100px !important;">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>
              
              <a href="<?php echo base_url('crm/leads/lead_pdf'); ?>" class="btn-anchor m-b-2" style="width:100px !important;">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("crm/leads/lead_print"); ?>');" style="width:100px !important;">
              <i class="fa fa-print"></i> Print
              </a> 
              
                <button class="btn-update" style="width: 100px; onclick="window.location.href='<?php echo base_url(); ?>'">
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
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<script>  
 $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
      }).on("change", function(selectedDate) 
      { 
          var start_data = $('.datepicker').val();
          $('.datepicker_to').datepicker('setStartDate', start_data);
          form_submit();
      });

      $('.datepicker_to').datepicker({
        format: 'dd-mm-yyyy',     
        autoclose: true,  
      }).on("change", function(selectedDate) 
      {  
          form_submit();
      });  

     

 function form_submit(vals)
{   
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var lead_maturity = $('#lead_maturity').val();
  $.ajax({
         url: "<?php echo base_url('crm/leads/set_advance_search'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date, lead_maturity : lead_maturity} ,
         success: function(result)
         { 
            if(vals!="1")
            {
               reload_table(); 
            }
         }
      });      
}

form_submit(1);
    
 function reset_date_search()
      {
        $('#start_date').val('');
        $('#end_date').val('');
        $.ajax({
               url: "<?php echo base_url('crm/leads/reset_advance_search/'); ?>",  
               success: function(result)
               { 
                reload_table(); 
               }
            });  
      }



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


$(document).ready(function(){
  var $modal = $('#load_add_disease_modal_popup');
  $('#adv_search').on('click', function()
  {  
    $modal.load('<?php echo base_url(); ?>crm/leads/advance_search/',
    { 
    },
    function()
    {
       $modal.modal('show');
    });
  });
});


function lead_followup(id)
{
  var $modal = $('#load_leads_modal_popup');
  $modal.load('<?php echo base_url().'crm/leads/followup/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
} 


function lead_followup(id)
{
  var $modal = $('#load_add_disease_modal_popup');
  $modal.load('<?php echo base_url().'crm/leads/followup/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

/*$('.datepicker3').datetimepicker({
     format: 'LT'
  });

$('.datepicker3').datetimepicker();*/

$(document).ready(function(){
   
   $('#selectAll').on('click', function () { 
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});




function send_sms() 
{         
    $('#table').dataTable();
     var allVals = [];
     $('.checklist').each(function() 
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

     var $modal = $('#load_add_disease_modal_popup');
    $modal.load('<?php echo base_url().'crm/leads/send_sms/' ?>',
    {
      'row_id': allVals
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
     
   }); 
   }
   else
   {
    alert('Please select atleast one lead.');
   }   
 }
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