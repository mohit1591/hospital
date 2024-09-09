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

  <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

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
            "url": "<?php echo base_url('advance_payment/ajax_list')?>",
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






$(document).ready(function(){
var $modal = $('#load_add_ot_booking_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'advance_payment/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});


function edit_advance_payment(id)
{
  
  window.location.href='<?php echo base_url().'advance_payment/edit/';?>'+id;
  
}

function view_advance_payment(id)
{
  var $modal = $('#load_add_ot_booking_modal_popup');
  $modal.load('<?php echo base_url().'advance_payment/view/' ?>'+id,
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
                      url: "<?php echo base_url('advance_payment/deleteall');?>",
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
          <?php  
              $users_data = $this->session->userdata('auth_users');  ?>
          <input type="hidden" id="branch_id" name="branch_id" value="<?php echo $users_data['parent_id']; ?>">
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date_ot_l" class="datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'];?>"></td>
        <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date_ot_l" value="<?php echo $form_data['end_date'];?>" class="datepicker"  onkeyup="return form_submit();"></td>
        <td><!--<a href="javascript:void(0)" class="btn-a-search" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> --><a class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a></td>
      </tr>
     
      
      <tr >
          <td style="text-align:left !important;"> 
          <label>Search</label><select name="criteria" onchange="select_room_type(this.value);">
                <option value="">Select</option>
                <option value="1">All</option>
                <option value="2">Patient Name</option>
                <option value="3">Mobile No.</option>
                <option value="4">IPD No.</option>
              </select>
            </td>
            <td  id="room_type">
            </td>
            <td>&nbsp;</td>
      </tr>

    </table>


    </form>
    	 
    <form>
       <?php if(in_array('773',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ot_summary_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
                    <th> IPD No. </th> 
                    <th> Patient Name </th> 
                    <th> Admission Date </th> 
                    <th> Amount </th> 
                    <th> Deposit Date </th> 
                   <!-- <th> Deposit Time </th>  -->
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
  		    
  		     <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
  			     
  		    <a href="<?php echo base_url('advance_payment/advance_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              
              <a href="<?php echo base_url('advance_payment/advance_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
  		    <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("advance_payment/advance_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
  		    
            <button class="btn-update" onclick="reload_table()">
              <i class="fa fa-refresh"></i> Reload
            </button>
         <button class="btn-exit" onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
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
function select_room_type(room_type){
   
  if(room_type==2){
      $('#room_type').html('<label>&nbsp;</label><div class="row m-b-5"><div class=""><input type="text" name="patient_name" placeholder="Patient Name"  onkeyup="return form_submit();"/></div>');
  }
   if(room_type==3){
      $('#room_type').html('<label>&nbsp;</label><div class="row m-b-5"><div class=""><input type="text" name="mobile_no" placeholder="Mobile No." onkeyup="return form_submit();"/></div>');
  }
   if(room_type==4){
      $('#room_type').html('<label>&nbsp;</label><div class="row m-b-5"><div class=""><input type="text" name="ipd_no" placeholder="IPD NO."  onkeyup="return form_submit();"/></div>');
  }
   if(room_type==1){
      $('#room_type').html('<label>&nbsp;</label><div class="row m-b-5"><div class=""><input type="text" name="all"  onkeyup="return form_submit();"/></div>');
  }

  }
function reset_search()
{
  //alert();
  $('#start_date_ot_l').val('');
  $('#end_date_ot_l').val('');
  $.ajax({url: "<?php echo base_url(); ?>advance_payment/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}

var $modal = $('#load_add_modal_popup');
  $('#adv_search_sale').on('click', function(){

$modal.load('<?php echo base_url().'advance_payment/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
 function delete_advance_payment(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('advance_payment/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
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
function form_submit()
  {

    $('#advance_payment_search_form').delay(200).submit();
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
  });

  
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
    })
</script>