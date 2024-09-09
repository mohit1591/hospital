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

    <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->
<script type="text/javascript">
var save_method; 
var table;

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
    <div class="userlist-box media_tbl_full">
  <form name="search_form"  id="search_form"> 
  <div class="row">
  <div class="col-md-12">

    <div class="row m-b-2">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> <input type="radio" name="running" id="running" class=""  onkeyup="return form_submit();"> Running</label>
            </div>
            <div class="col-md-7">
              <label> <input type="radio" name="running" id="discharge"  onkeyup="return form_submit();"> Discharge</label>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
               <label>  Patient Type </label>
            </div>
            <div class="col-md-7">
              <select name="patient_type" id="patient_type" onchange="return form_submit();">
              <option value="-1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']=='-1'){ echo 'selected="selected"'; } ?> >All</option>
              <option  value="1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==1){ echo 'selected="selected"'; } ?> >Normal</option>
              <option value="2" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==2){ echo 'selected="selected"'; } ?> >Panel</option></select>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <a href="javascript:void(0)" class="btn-a-search" id="adv_search_ipd_booking"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a>
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->


    <div class="row m-b-5">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> From Date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="start_date" id="start_date_p"  value="<?php echo $form_data['start_date']?>" class="datepicker"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <label>To date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date']?>" class="datepicker"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <a href="javascript:void(0)" class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a>
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->

   

  </div> <!-- 12 -->
</div> <!-- row -->
    


</form>

    <form>
       <?php if(in_array('777',$users_data['permission']['action'])) {
       ?>

       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered purchase_list" cellspacing="0" width="100%">

            <thead class="bg-theme">

                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                        <th>OPD No.</th> 
                        <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th> 
                        <th>Patient Name </th> 
                        <th>Mobile No.</th> 
                        <th>Admission Date</th>
                        <th>Doctor Name</th> 
                        <th>Room No.</th> 
                        <th>Bed No.</th> 
                        <th>Address</th> 
                        <th>Remarks</th> 
                        <th>Created Date </th>
                        <th>Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

    </form>


   </div> <!-- close -->


<div class="userlist-right media_btns">
  		<div class="btns">

               <?php if(in_array('777',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd_charge_entry/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>

  <?php /* ?>                    
              
              <a href="<?php echo base_url('opd/opd_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('opd/opd_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('opd/opd_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
  
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("opd/opd_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
        
               <?php if(in_array('525',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                         <i class="fa fa-trash"></i> Delete
                    </button>
               <?php } ?> 
               <?php if(in_array('529',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('526',$users_data['permission']['action'])) {
               ?>
             <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd/archive'); ?>'">
                         <i class="fa fa-archive"></i> Archive
                    </button>
               <?php } ?>

 <?php */ ?>
        
               
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('day_care'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 
  	<!-- right -->
 


  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script> 
$(document).ready(function(){
   form_submit();
 }); 
 function delete_ipd_booking(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_booking/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
</script> 

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking_recipt"); ?>')
    }) ;
   
       
  <?php }?>
 });

$('documnet').ready(function(){
 <?php if(isset($_GET['print_receipt']) && $_GET['status']=='print_receipt'){?>
  $('#confirm_admission_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('ipd_booking');?>'; 
        print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_card"); ?>')
    }) ;
   
       
  <?php }?>
 });

</script>

<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_ipd_booking').on('click', function(){
$modal.load('<?php echo base_url().'ipd_booking/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});



</script>

<script>
function form_submit()
{
  $('#search_form').delay(200).submit();
}


function reset_search()
{ 
  $('#start_date_p').val('');
  $('#end_date_p').val('');
  $('#ipd_no').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
    } 
  }); 
}

$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/ipd_booking/advance_search/",
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
    $('#start_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_p").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_p").datepicker("option", "maxDate", selected);
      }
    })


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
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking_recipt"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
    <div id="confirm_admission_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking_recipt"); ?>');" >Print</a>
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
</div>
</body>
</html>
