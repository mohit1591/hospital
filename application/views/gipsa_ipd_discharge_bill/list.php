<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php if(in_array('113',$users_data['permission']['action'])): ?>
     $(document).ready(function() { 
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true, 
             "order": [], 
             "pageLength": '20',
             "ajax": {
                 "url": "<?php echo base_url('ipd_running_bill/ajax_list')?>",
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
<?php endif;?>


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'ipd_running_bill/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'ipd_running_bill/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

 form_submit();
});

function edit_ipd_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_running_bill/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_ipd_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_running_bill/view/' ?>'+id,
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
                      url: "<?php echo base_url('ipd_running_bill/deleteall');?>",
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


function reset_search()
{ 
    $('#start_date_patient').val('');
    $('#end_date_patient').val('');
    $('#mobile_no').val('');
    $('#address').val('');
    $('#patient_name').val('');
    $('#patient_code').val('');
    $.ajax({url: "<?php echo base_url(); ?>ipd_running_bill/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
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
    <form id="ipd_running_bill_search_form">

      <table class="ptl_tbl m-b-5">
        <tr> <td> <label>Searching Criteria</label>
        <select name="search_criteria" onchange="select_search_criteria(this.value);">
        <option value="">Select</option>
        <option value="1">All</option>
        <option value="2">Pannel</option>
        <option value="3">Normal</option>
        <option value="4">Patient Name</option>
        <option value="5">Mobile No</option>
        </select>
        </td>
        <td  id="room_type">
        </td>
           <td  id="room_type">
            </td>
        </tr>

      </table>
    </form>

    <form>
       <?php if(in_array('113',$users_data['permission']['action'])): ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ipd_patient_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input onclick="selectall();" type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th>Patient Reg.No.</th>
                    <th> IPD No. </th> 
                    <th> Patient Name </th> 
                    <th> Admission Date </th> 
                    <th> Admission Time </th> 
                    <th> Doctor Name </th> 
                    <th> Room No. </th> 
                    <th> Bed No. </th>  
                    <th> Specilization </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php endif;?>

    </form>


   </div> <!-- close -->





  	
 
  <!-- cbranch-rslt close -->

  


  
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
$(document).ready(function(){
  reload_table();
   $('#selectAll').on('click', function () { 
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});


function form_submit()
{
  $('#ipd_running_bill_search_form').delay(200).submit();
}

$("#ipd_running_bill_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ipd_running_bill/advance_search/'); ?>",
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

  function select_search_criteria(room_type){
  if(room_type==1){
    $('#patient_name').val('');
    $('#mobile_no').val('');
  }
  if(room_type==4){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="patient_name"  onkeyup="return form_submit();" id="patient_name"/></div>');
  }
   if(room_type==5){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="mobile_no"  onkeyup="return form_submit();" id="mobile_no"/></div>');
  }else{
    form_submit();
  }
  
 

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
            <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>