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
   <!--- 24-04-2020 ---->
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!--- 24-04-2020--->
   <script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2460',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ambulance/enquiry/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });
      $('.tog-col').on( 'click', function (e) 
      {
        var column = table.column( $(this).attr('data-column') );
        column.visible( ! column.visible() );
      });

}); 
<?php } ?>


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient/add/' ?>',
{
  },
function(){
$modal.modal('show');
});

});
 

$('#opd_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'ambulance/enquiry/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});

function edit_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/view/' ?>'+id,
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
                      url: "<?php echo base_url('ambulance/enquiry/deleteall');?>",
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

<body style="padding-bottom: 70px;">
   <div class="container-fluid">
      <?php
      $this->load->view('include/header');
      $this->load->view('include/inner_header');
      ?>
   
 
   <!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
      
               <form name="search_form"  id="search_form">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="row mb-2">
                           <label class="col-md-3 p-r-0">From Date</label>
                           <div class="col-md-4">
                              <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
                           </div>
                        </div>
                          <div class="row mb-2">
                           <label class="col-md-3 p-r-0">Location</label>
                           <div class="col-md-4">
                               <select name="location" id="location" onchange="return form_submit();">
                                <option value="">Select</option>
                                <?php
                              if(!empty($location_list))
                              {
                                foreach($location_list as $location)
                                {
                                  $selected_location = "";
                                  if($location->id==$form_data['location'])
                                  {
                                    $selected_location = "selected='selected'";
                                  }
                                  echo '<option value="'.$location->id.'" '.$selected_location.'>'.$location->location_name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                           </div>
                        </div>
                       
                        <!-- <div class="row mb-2">
                           <label class="col-md-3 p-r-0">Patient Mobile No.</label>
                           <div class="col-md-7">
                              <input  id="mobile_no" name="mobile_no" value="<?php echo $form_data['mobile_no']?>" id="mobile_no" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text">
                           </div>
                           </div> -->
                      <!--   <div class="row mb-2">
                           <div class="col-md-9 col-md-push-3">
                              <label class="btn btn-sm">
                              <input type="radio"  name="vehicle_type"  onClick="return form_submit();" value="1" <?php if($form_data['vehicle_type']==1){?>checked="checked" <?php }?> >
                              <span>Self Owned </span>
                              </label>
                              
                              <label class="btn btn-sm">
                              <input type="radio"  name="vehicle_type" onClick="return form_submit();" value="2" <?php if($form_data['vehicle_type']==2){ ?>checked="checked" <?php } ?> >
                              <span>Leased</span>
                              </label>
                           </div>
                        </div> -->
                     </div>
                     <div class="col-md-4">
                        <div class="row mb-2">
                           <label class="col-md-4">To Date</label>
                           <div class="col-md-8">
                              <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
                           </div>
                        </div>
                         <div class="row mb-2">
                            <label class="col-md-4">Patient Name</label>
                           <div class="col-md-8">
                              <input id="patient_name" name="patient_name" value="<?php echo $form_data['patient_name']?>" id="patient_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
                           </div>
                        </div>
                      
                        <div class="row mb-2">
                           <label class="col-md-4">Enquiry Status</label>
                           <div class="col-md-8">
                               <select name="enquiry_status" id="enquiry_status" onchange="return form_submit();">
                                <option value="">Select</option>
                                <option value="1" <?php if($form_data['enquiry_status']==1){echo "selected";} ?>>Confirmed</option>
                                <option value="2" <?php if($form_data['enquiry_status']==2){echo "selected";} ?>>Pending</option>
                                <option value="3" <?php if($form_data['enquiry_status']==3){echo "selected";} ?>>Cancelled</option>
                               
                              </select>
                           </div>
                        </div>
                       <!--  <div class="row mb-2">
                           <label class="col-md-4">Driver Name</label>
                           <div class="col-md-8">
                              <input name="driver_name" value="<?php echo $form_data['driver_name']?>" id="driver_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
                           </div>
                        </div> -->
                     </div>
                     <div class="col-sm-4">
                         <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
                         <a href="javascript:void(0)" class="btn-a-search" id="opd_adv_search">
                           <i class="fa fa-cubes" aria-hidden="true"></i> 
                           Advance Search
                           </a> 
               
                     </div>
                  </div>
               </form>
            
            <div class="mb-3">
               <div class="hr-scroll">
                  <table id="table" class="table table-striped table-bordered ambulance_booking_list " cellspacing="0" width="100%">
                     <thead>
                        <tr>
                           <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th>
                           <?php $data= get_setting_value('PATIENT_REG_NO'); 
                              if(!empty($data) && isset($data)) 
                                { ?>
                           <th><?php echo $data; ?></th>
                           <?php } else { ?>
                           <th>Patient Reg No.</th>
                           <?php  } ?>
                           <th>Enquiry No.</th>
                           <!-- <th>Booking No.</th> -->
                           <th>Patient Name</th>
                           <!--<th>Vehicle No.</th>
                           <th>Driver Name</th>-->
                           <th>Booking Date</th>
                           <th>Pick From</th>
                           <th>Drop To</th>
                          <!-- <th>Total Amount</th>-->
                           <th>Enquiry status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
    </div>
    <div class="userlist-right">
      <div class="fixed">
               <div class="btns opd_booking_list_right_btns">
                  <?php if(in_array('2461',$users_data['permission']['action'])) {
                     ?>
                  <button class="btn-update" onclick="window.location.href='<?php echo base_url('ambulance/enquiry/add'); ?>'">
                  <i class="fa fa-plus"></i> New
                  </button>
                  <?php } ?>
                  <a href="<?php echo base_url('ambulance/enquiry/enquiry_excel'); ?>" class="btn-anchor m-b-2">
                  <i class="fa fa-file-excel-o"></i> Excel
                  </a>
                  <a href="<?php echo base_url('ambulance/enquiry/enquiry_pdf'); ?>" class="btn-anchor m-b-2">
                  <i class="fa fa-file-pdf-o"></i> PDF
                  </a>
                  <?php if(in_array('2374',$users_data['permission']['action'])) {
                     ?>
<!--                  <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                  <i class="fa fa-trash"></i> Delete
                  </button>-->
                  <?php } ?> 
                  <?php if(in_array('2460',$users_data['permission']['action'])) {
                     ?>
                  <button class="btn-update" onclick="reload_table()">
                  <i class="fa fa-refresh"></i> Reload
                  </button>
                  <?php } ?>
                  <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
                  <i class="fa fa-sign-out"></i> Exit
                  </button>
               </div>
            </div>
    </div>
  
</section>
</div>

   <!-- container-fluid -->
   <div class="container-fluid  navbar-fixed-bottom">
      <?php $this->load->view('include/footer'); ?>
   </div>
   <script>

  $(document).ready(function()
  {
      var $modal = $('#load_opd_import_modal_popup');
        $('#open_model').on('click', function(){
        $modal.load('<?php echo base_url().'opd/import_opd_excel' ?>',
        { 

        },
      function(){
        $modal.modal('show');
        });

      });
  })
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>ambulance/enquiry/reset_search/", 
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

function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#vehicle_no').val('');
  $('#driver_id').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');

  $.ajax({url: "<?php echo base_url(); ?>ambulance/enquiry/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
    } 
  }); 
}


$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
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


function form_submit(vals)
{ 

  var start_date = $('#start_date_patient').val();
  var end_date = $('#end_date_patient').val();
  var patient_name=$('#patient_name').val();
  var location=$('#location').val();
  var mobile_no=$('#mobile_no').val();
  var vehicle_type= $('input[name=vehicle_type]:checked').val();
  var enquiry_status= $('#enquiry_status').val();

 
  $.ajax({
         url: "<?php echo base_url('ambulance/enquiry/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date,patient_name : patient_name,location : location,mobile_no : mobile_no,vehicle_type:vehicle_type,enquiry_status:enquiry_status} ,
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

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('opd');?>'; 
    }); 
       
  <?php }?>
 });

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && isset($_GET['type']) && $_GET['type']==1){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('opd');?>'; 
    }); 
       
  <?php }?>
 });

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

  function confirm_enquiry(id)
  {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'ambulance/enquiry/confirm_booking/' ?>'+id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }


 function delete_ambulance_booking(booking_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ambulance/enquiry/delete_booking/'); ?>"+booking_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
 function cancel_enquiry(enquiry_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ambulance/enquiry/cancel_enquiry/'); ?>"+enquiry_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }



$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script> 
<!-- Confirmation Box -->
    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
              <?php 
              if($users_data['parent_id']=='60')
                {
                    $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>
                    <?php 
                }
              
              ?>
              <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm_billing_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-footer">
             <?php 
              if($users_data['parent_id']=='60')
                {
                    $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>
                    <?php 
                }
              ?>
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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_opd_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->

     <?php
if(!empty($unchecked_column))
{
    $implode_checked_column = implode(',', $unchecked_column);

?>
<script type="text/javascript">
    $( document ).ready(function(e) {  
            table.columns([<?php echo $implode_checked_column; ?>]).visible(false);
         } );
</script>
<?php    
}
?>


<!-- function to reload columns -->
<script type="text/javascript">

    function check_status()
    {
        var val = $('input[name=vehicle_type]:checked').val();
        form_submit(val);
    }


   $('#selectAll').on('click', function () { 
          if ($(this).hasClass('allChecked')) {
              $('.checklist').prop('checked', false);
          } else {
              $('.checklist').prop('checked', true);
          }
          $(this).toggleClass('allChecked');
      });

 function doctor_checked_status(opd_id,doctor_status)
 {
     $.ajax({
      url: '<?php echo base_url(); ?>opd/update_doctor_status/'+opd_id+'/'+doctor_status, 
          success: function(result)
          { 
            if(result==0)
              {
                  $('#doctor_status_'+opd_id).html('<button type="button" class="btn btn-success" onclick="doctor_checked_status('+opd_id+',1)">Checked</button>');
              }
              else
              {
                $('#doctor_status_'+opd_id).html('<button type="button" class="btn btn-danger" onclick="doctor_checked_status('+opd_id+',0)">Pending</button>');
              }
        }
    }); 
}

</script>
</body>
</html>



<div class="modal fade" id="advance_search_ambulance_booking">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Advance Search</h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6"> 
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>UHID No.</label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="form-control">
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Booking No.</label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="form-control">
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Booking Time</label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="mr-name" value="24-10-2019">
                        <input type="text" class="w-60px" value="11:20PM">
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Patient Name</label>
                     </div>
                     <div class="col-md-8">
                        <select name="" id="" class="mr m_mr alpha_space">
                           <option value="">Mr.</option>
                        </select>
                        <input type="text" class="mr-name">
                     </div>
                  </div>
               </div>
               <div class="col-md-6">  
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Driver Name</label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="form-control">
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Staff</label>
                     </div>
                     <div class="col-md-8">
                        <select name="" id="" class="">
                           <option value="">Select--</option>
                        </select>
                     </div>
                  </div>
                  <div class="row mb-2">
                     <div class="col-md-4">
                        <label>Vehicle No.</label>
                     </div>
                     <div class="col-md-8">
                        <input type="text" class="form-control">
                     </div>
                  </div>

                  <div class="row mb-2">
                     <label class="col-md-4">Referred By Doctor</label>
                     <div class="col-md-8">
                        <select name="" id="" class="">
                           <option value="">Select--</option>
                        </select>
                     </div>
                  </div>
             
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn-save">Save</button>
            <button type="button" class="btn-save" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>