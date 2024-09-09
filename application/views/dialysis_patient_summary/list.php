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
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('749',$users_data['permission']['action'])) 
//{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('dialysis_patient_summary/ajax_list')?>",
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
<?php //} ?>


$(document).ready(function(){
var $modal = $('#load_add_ipd_patient_discharge_summary_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'dialysis_patient_summary/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_ipd_patient_discharge_summary(id)
{
  var $modal = $('#load_add_ipd_patient_discharge_summary_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_patient_summary/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_ipd_patient_discharge_summary(id)
{
  var $modal = $('#load_add_ipd_patient_discharge_summary_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_patient_summary/view/' ?>'+id,
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
                      url: "<?php echo base_url('dialysis_patient_summary/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   } 
    else{
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
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
    <?php if(!empty($form_data['dialysis_id']) && !empty($form_data['patient_id']))	 
    { ?>
    <script type="text/javascript">
    
    $(document).ready(function() { 
        $('#search_form').delay(200).submit();
  });
</script>

    <?php
        
    }
    
    ?>
 <form name="search_form"  id="search_form"> 
    <input type="hidden" name="dialysis_id" value="<?php echo $form_data['dialysis_id'];?>">
    <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id'];?>">
    <div class="row">
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label></div>
          <div class="col-xs-7">
            <input name="patient_code" class="m_input_default" id="patient_code" onkeyup="return form_submit();"  value="<?php echo $form_data['patient_code']?>" type="text" autofocus>
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Mobile No.</label></div>
          <div class="col-xs-7">
            <input name="mobile_no" value="<?php echo $form_data['mobile_no']?>" id="mobile_no" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text">
          </div>
        </div>
        
       



      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Patient Name</label></div>
          <div class="col-xs-7">
            <input name="patient_name" value="<?php echo $form_data['patient_name']?>" id="patient_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
          </div>
        </div>
        
         <div class="row m-b-5">
          <div class="col-xs-5"><label>Dialysis No.</label></div>
          <div class="col-xs-7">
            <input name="dialysis_no" value="<?php echo $form_data['dialysis_no']?>" id="dialysis_no" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
          </div>
        </div>

        <?php $users_data = $this->session->userdata('auth_users'); ?>
            <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
              
      </div> <!-- 4 -->

      <div class="col-sm-4">
        
          <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a>
      </div> <!-- 4 -->

    
    </div> <!-- row -->
  
         
    </form>

    <form>
       <?php //if(in_array('749',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ipd_patient_discharge_summary_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th>Dialysis No.</th>
                    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
                    <th>Patient Name</th> 
                    <th>Mobile No.</th>
                    <th>Date </th> 
                    <th>Action </th>
                </tr>
            </thead>  
        </table>
     <?php //} ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
               <?php //if(in_array('750',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update"  onclick="window.location.href='<?php echo base_url('dialysis_booking'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php //} ?>
               <?php //if(in_array('752',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php //} ?>
              
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
              
               <?php if(in_array('753',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('dialysis_patient_summary/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url('dialysis_booking'); ?>'">
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

function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#patient_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');

  $.ajax({url: "<?php echo base_url(); ?>dialysis_patient_summary/reset_search/", 
    success: function(result)
    { 
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
    url: "<?php echo base_url('dialysis_patient_summary/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });

});

 function delete_ipd_discharge_summary(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('dialysis_patient_summary/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
  $('#load_add_ipd_patient_discharge_summary_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
 $('document').ready(function(){
 <?php 
 
 if($users_data['parent_id']!='113')
 {
 if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('dialysis_patient_summary');?>'; 
    }); 
       
  <?php } } ?>
 });

 $('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print_medicine') { ?>
  $('#confirm_medicine_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('dialysis_patient_summary');?>'; 
    }); 
       
  <?php }?>
 });

 function print_summary(id)
{
  var $modal = $('#load_add_ipd_discharge_summary_print_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_patient_summary/print_template/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

 function print_advance_summary(id)
{
  var $modal = $('#load_add_ipd_discharge_summary_print_modal_popup');
  $modal.load('<?php echo base_url().'ipd_patient_advance_discharge_summary/print_template/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


function print_letter_head_summary(id,branch_id)
{  
 
  var print_option = 1;
  var id=id;
  var branch_id=branch_id;
 

  print_window_page('<?php echo base_url('dialysis_patient_summary/print_discharge_summary_letter_head/') ?>'+id+'/'+branch_id+'/'+print_option);
}

function print_letter_head_adv_summary(id,branch_id)
{  
 
  var print_option = 1;
  var id=id;
  var branch_id=branch_id;
 

  print_window_page('<?php echo base_url('Ipd_patient_advance_discharge_summary/print_advance_discharge_summary_letter_head/') ?>'+id+'/'+branch_id+'/'+print_option);
}
</script> 
<!-- Confirmation Box -->
  <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("dialysis_patient_summary/print_discharge_summary"); ?>');">Print</a>

           
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm_medicine_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("dialysis_patient_summary/print_discharge_summary_medicine"); ?>');">Print</a>

           
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->
    
    
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

<!-- Confirmation Box end -->
<div id="load_add_ipd_patient_discharge_summary_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ipd_discharge_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>