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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('529',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "aaSorting": [],
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('opd_billing/ajax_list')?>",
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


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#opd_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'opd_billing/advance_search/' ?>',
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
                      url: "<?php echo base_url('opd_billing/deleteall');?>",
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
  <div class="userlist-box media_tbl_full">
   <!--  <div class="row m-b-5">
      <div class="col-md-12">
        <div class="row">
          <div class="col-md-6">
            <div id="child_branch" class="patient_sub_branch"></div>
          </div>
          <div class="col-md-6 text-right">
            <a href="javascript:void(0)" class="btn-a-search" id="opd_adv_search">
              <i class="fa fa-cubes" aria-hidden="true"></i> 
              Advance Search
            </a>
          </div> 
        </div> 
      </div>
       
    </div>   --> 
    
<form>
  
<div class="row">
  <div class="col-md-12">
    
    <div class="row">
      <div class="col-sm-4">
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <a class="btn-custom" href="javascript:void(0)"> <i class="fa fa-user"></i> Select Patient</a>
            </div>
            <div class="col-xs-7"></div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>patient reg no. <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
              <div class="fright"><b>textRegisterID</b></div>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>IPD no. <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
              <div class="fright"><b>textIPDid</b></div>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>patient name <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
              <select class="mr">
                <option>Mr.</option>
                <option>Mrs.</option>
              </select>
              <input type="text" name="patient_name" class="mr-name">
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
              <label>mobile no.</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="mobile_no">
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>Age <sapn class="star">*</sapn></label>
            </div>
            <div class="col-xs-7">
               <div class="fright">
                  <input type="text" class="input-tiny"> Y
                  <input type="text" class="input-tiny"> M
                  <input type="text" class="input-tiny"> D
               </div>
            </div>
          </div>
        
          <div class="row m-b-3">
            <div class="col-xs-5">
               <label>patient address</label>
            </div>
            <div class="col-xs-7">
               <textarea name=""></textarea>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>room no. <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <select>
                  <option>select room</option>
               </select>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>patient Type <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <label><input type="radio" name=""> normal</label> &nbsp;
               <label><input type="radio" name=""> panel</label>
            </div>
          </div>

      </div> <!-- 4 -->


      
      <div class="col-sm-4">
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>operation name <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="">
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>package <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <select class="w-150px">
                  <option>select</option>
               </select>
               <a class="btn-new">New</a>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>Date/Time <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="" class="w-80px" placeholder="dd/mm/yyyy">
               <input type="text" name="" class="w-60px" placeholder="00:00">
               <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
               </select>
              
            </div>
          </div>
        
          <div class="row">
            <div class="col-xs-5">
               <label>doctor list</label>
            </div>
            <div class="col-xs-7">
               <input type="text" class=" m-b-5">
               <div class="p-t-2px">
                  <a class="btn-new">Add</a>
                  <a class="btn-new">Delete</a>
               </div>
            </div>
          </div>

        
          <div class="row m-t-5 m-b-5">
            <div class="col-xs-12">
               <div class="row">
                  <div class="col-sm-5"></div>
                 <!--  <div class="col-sm-7 ot_booking_delete">
                     <a class="btn-new">Delete</a>
                  </div> -->
               </div>
               <div class="ot_border">
                  <table class="table table-bordered table-striped ot_table">
                     <thead class="bg-theme">
                        <tr>
                           <th><input type="checkbox" name=""></th>
                           <th>S.No.</th>
                           <th>Doctor Name</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td colspan="3" class="text-danger"><div class="text-center">No data found !</div></td>
                        </tr>
                     </tbody>
                  </table>
               </div>         
            </div>
          </div>

        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <label>Remarks <span class="star">*</span></label>
            </div>
            <div class="col-xs-7">
               <input type="text" class="w-140px">
               <a class="btn-new">Select</a>
            </div>
          </div>
        
          <div class="row m-b-5">
            <div class="col-xs-5">
               <!-- <label>Remarks <span class="star">*</span></label> -->
            </div>
            <div class="col-xs-7">
               <button class="btn-update" type="submit"> <i class="fa fa-floppy-o"></i> Submit</button>
               <a class="btn-anchor" href=""> <i class="fa fa-sign-out"></i> Exit</a>
            </div>
          </div>
         
      </div> <!-- 4 -->


      
      <div class="col-sm-4"></div> <!-- 4 -->

    </div> <!-- inner row -->

  </div>
</div>

</form>
    
  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>

$(document).ready(function(){
   form_submit();
 });
function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#reciept_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>opd_billing/reset_search/", 
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

function form_submit()
{
  $('#search_form').delay(200).submit();
}
$("#search_form").on("submit", function(event) { 
  event.preventDefault();  
   
  $.ajax({
    url: "<?php echo base_url('opd_billing/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });

});


function blank_print(id){ 
  $.ajax({
      url: "<?php echo base_url();?>prescription/print_blank_prescription_pdf/"+id, 
      type: 'post',
      dataType: 'json',
      async: false,
      success: function(response){
        if(response.success)
        { 
          printdiv(response.pdf_template);
         }
         else
         {
          alert(response.msg);   
         }
      },
      }); 
           }

function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = printpage
var oldstr = document.body.innerHTML;
//document.getElementById('header').style.display = 'none';
//document.getElementById('footer').style.display = 'none';

document.body.innerHTML = headstr+newstr+footstr;
window.print();
//window.location.reload();
return false;
}

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('opd_billing');?>'; 
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
        window.location.href='<?php echo base_url('opd_billing');?>'; 
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

  function confirm_booking(id)
  {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'opd_billing/confirm_booking/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

 function delete_opd_booking(booking_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('opd_billing/delete_booking/'); ?>"+booking_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 /*function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
    return printWindow;
}*/

/* function print_pdf(id)
 {

    var printWindow = openPrintWindow('< ?php echo base_url(); ?>opd_billing/print_billing_report/'+id, 'windowTitle', 'width=820,height=600');
     var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
 }*/



 

 
 
</script> 
<!-- Confirmation Box -->
    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd_billing/print_billing_report"); ?>');">Print</a>


            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm_billing_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("opd_billing/print_billing_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->
             <a href="javascript:void(0)" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd_billing/print_billing_report"); ?>');">
              <i class="fa fa-print"></i> Print</a>

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
</div><!-- container-fluid -->
</body>
</html>