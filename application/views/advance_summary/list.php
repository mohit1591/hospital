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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

 

<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('773',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('advance_payment_summary/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
form_submit();

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
    <form id="advance_payment_search_form">
    
    <table class="ptl_tbl m-b-5">
      <tr>
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date" class="datepicker start_datepicker m_input_default  w-80px"  value="<?php echo $form_data['start_date'];?>"></td>
        <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date" value="<?php echo $form_data['end_date'];?>" class="datepicker datepicker_to end_datepicker m_input_default  w-80px"></td>
        <td>
          <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a></td>
      </tr>
     
      <tr>
      
      <input type="hidden" name="branch_id" id="branch_id" value="<?php $users_data['parent_id']; ?>">
        
    </table>


    </form>
       
    <form>
       <?php if(in_array('773',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ot_summary_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    
                    <th> S.No. </th> 
                     <th> Receipt No. </th> 
                    <th> IPD No. </th> 
                   
                    <th> Patient Name </th> 
                    <th> Reg. No. </th> 
                    <th> Amount </th> 
                    <th> Payment Mode </th> 
                   
                </tr>
            </thead>  
        </table>
        <?php } ?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right relative">
      <div class="fixed">
      <div class="btns">
               <a href="<?php echo base_url('advance_payment_summary/report_excel'); ?>" class="btn-anchor m-b-2">
               <i class="fa fa-file-excel-o"></i> Excel
               </a>
               <a href="<?php echo base_url('advance_payment_summary/pdf_report'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
               </a>
          
            <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("advance_payment_summary/print_report"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 

            <button class="btn-update" onclick="reload_table()">
              <i class="fa fa-refresh"></i> Reload
            </button>
        
        </button>
      </div>
      </div>
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<?php
$this->load->view('include/footer');
?>

<script> 

function reset_search()
{
  //alert();
  $('#start_date_ot_l').val('');
  $('#end_date_ot_l').val('');
  $.ajax({url: "<?php echo base_url(); ?>advance_payment_summary/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}

$(document).ready(function(){
  $('#load_add_ot_booking_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
 


</script> 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>
<script>
  
  $('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('advance_payment');?>'; 
    }) ;
   
       
  <?php }?>
 });

</script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_medicine/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("advance_payment/print_advance_payment_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

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
<div id="load_add_ot_booking_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>

<script>



$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });


function form_submit(){
    var start_date = $('#start_date').val();
    var branch_id = $('#branch_id').val();
    var end_date = $('#end_date').val();
   
    $.ajax({
           url: "<?php echo base_url('advance_payment_summary/search_data/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date,branch_id:branch_id} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
   
    //$("#end_date").datepicker({ minDate: selectedDate });
    $('.end_datepicker').datepicker('setStartDate', start_date);
}

/*
function form_submit()
  {

    $('#advance_payment_search_form').submit();
  }


    $("#advance_payment_search_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('#overlay-loader').show();

  $.ajax({
      url: "<?php echo base_url(); ?>advance_payment/advance_search/",
      type: "post",
      data: $(this).serialize(),
      success: function(result) 
      {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
      }
      });
  });*/

  /*
  var today =new Date();
    $('#start_date_ot_l').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_ot_l").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_ot_l').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_ot_l").datepicker("option", "maxDate", selected);
      }
    })*/
</script>