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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">
<link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-multiselect.css" type="text/css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-multiselect.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

<script type="text/javascript">
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
      $.ajax({
         url: "<?php echo base_url('pathology_gst_report/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
  }

$(document).ready(function(){
var $modal = $('#load_advance_search_modal_popup');
$('#advance_search').on('click', function(){
$modal.load('<?php echo base_url().'pathology_gst_report/advance_search/' ?>',
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
var $modal = $('#load_advance_search_modal_popup');
$('#advance_report').on('click', function(){
$modal.load('<?php echo base_url().'pathology_gst_report/advance_report/' ?>',
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
    <div class="userlist-box "> 
    <form> 
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
    <div class="grp_box m-b-5">
      
<input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

      <div class="grp">

            <label><b>From Date:</b></label>
            <input type="text" id="start_date" name="start_date" class="datepicker m_input_default w-80px" value="<?php echo $form_data['start_date']; ?>">
      </div>

      <div class="grp">
            <label><b>To Date:</b></label>
            <input type="text" name="end_date" id="end_date" class="datepicker_to m_input_default w-80px" value="<?php echo $form_data['end_date']; ?>">
      </div>
      
      <div class="grp">
            <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
      </div>
    </div> <!-- //row -->
     <?php
          if(in_array('890',$users_data['permission']['action'])){?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered pathology_report_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> S. No. </th> 
                    <th> Booking Date </th>
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?>  </th>
                    <th> Lab Ref. No. </th>
                    <th> Patient Name </th>
                    <th> Total Amount</th>
                    <th> GST </th>
                  </tr>
            </thead>  
        </table> 
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative" id="example_wrapper">
      <div class="fixed">
  		<div class="btns media_btns"> 
          <?php if(in_array('891',$users_data['permission']['action'])){?>
               <a href="<?php echo base_url('pathology_gst_report/path_report_excel'); ?>" class="btn-anchor">
               <i class="fa fa-file-excel-o"></i> Excel
               </a>
          <?php } ?>
          <?php if(in_array('893',$users_data['permission']['action'])){?>
               <a href="<?php echo base_url('pathology_gst_report/pdf_path_report'); ?>" class="btn-anchor">
                    <i class="fa fa-file-pdf-o"></i> PDF
               </a>
          <?php } ?>
          <?php if(in_array('894',$users_data['permission']['action'])){?>
               <a href="javascript:void(0)" class="btn-anchor" id="deleteAll" onClick="return print_window_page('<?php echo base_url("pathology_gst_report/print_path_report"); ?>');">
               <i class="fa fa-print"></i> Print
               </a>
          <?php } ?>
          
        <button class="btn-update" onClick="window.location.href='<?php echo base_url(); ?>'">
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
<script type="text/javascript">

function send_reminder(booking_id,patient_id)
{ 
  var $modal = $('#load_send_reminder_modal_popup');
  $modal.load('<?php echo base_url().'pathology_gst_report/send_reminder/' ?>',
  {
    'patient_id': patient_id,
    'booking_id':booking_id
  },
  function(){
  $modal.modal('show');
  });
} 



function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>pathology_gst_report/reset_date_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        reload_table();
      } 
  }); 

}


function get_selected_value(value)
{ 
      var branch_id ='<?php echo $users_data['parent_id']; ?>';
      if(value==branch_id)
      {
        document.getElementById("search_box_user").style.display="block";
      }
      else
      {
        document.getElementById("search_box_user").style.display="none";
      }
      
} 

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
  var branch_id = $('#branch_id').val();
  var employee = $('#employee').val();
  var disease = $('#disease').val();
  $.ajax({
         url: "<?php echo base_url('pathology_gst_report/search_data/'); ?>", 
         type: 'POST',
         data: { branch_id: branch_id, start_date: start_date, end_date : end_date, disease : disease, employee : employee} ,
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
var save_method; 
var table; 
<?php
if(in_array('890',$users_data['permission']['action']))
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,  
        "pageLength": '20', 
        "ajax": {
            "url": "<?php echo base_url('pathology_gst_report/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
});
<?php } ?> 


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

function email_report()
{
  
  $('#confirm_email').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#email_now', function(e)
        { 
            
            $.ajax({
                 url: "<?php echo base_url('pathology_gst_report/email_report/'); ?>", 
                 success: function(result)
                 {
                    var msg = 'Today report sent on your email address successfully.';
                    flash_session_msg(msg);
                    reload_table(); 
                 }
              });

            
        });

  
}
</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_send_reminder_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>

  <div id="confirm_email" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="email_now">Confirm</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
            </div>
        </div>
      </div>  
  </div> 
</body>
</html>