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

<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript">
$(document).ready(function(){
var $modal = $('#load_add_ipd_patient_discharge_summary_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'ipd_patient_discharge_summary/add/' ?>',
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
  $modal.load('<?php echo base_url().'ipd_patient_discharge_summary/edit/' ?>'+id,
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
  $modal.load('<?php echo base_url().'ipd_patient_discharge_summary/view/' ?>'+id,
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
                      url: "<?php echo base_url('ipd_patient_discharge_summary/deleteall');?>",
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
 
 // Added By Nitin Sharma 05/02/2024
    function sendWhatsApp(id,no,branch_id){
        $.ajax({
            url: '<?php echo base_url(); ?>ipd_patient_discharge_summary/print_discharge_summary_letter_head/'+id+'/'+branch_id+'/send',
            type: 'POST',
            data : {
                mobile : no
            },
            success: function (data)
                {
                    console.log(data);
                    let res = JSON.parse(data);
                    if(res.msg == "SUCCESSFULLY SEND"){
                        flash_session_msg("Whatsapp Send Successfull");
                    }else{
                        flash_session_msg("Whatsapp Send Fail");
                    }
    
                }
    
            });
    }
    // Added By Nitin Sharma 05/02/2024
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
    	 
  <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
         
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>
  
  <form name="search_form"  id="search_form"> 

    <div class="row">
        <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date'];?>">
          </div>
        </div>
        </div>
          <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date'];?>" type="text">
          </div>
        </div>
        </div>
        
        <div class="col-sm-4">
          <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
        
      </div> <!-- 4 -->
      
        </div>
        
        <div class="row">
        
           
          <div class="col-sm-2"><label>Summary Type</label></div>
          <div class="col-sm-6">
            
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']=='-1') { ?> checked="checked" <?php } ?> onchange="return form_submit();" value="-1"> ALL</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']=='0') { ?> checked="checked" <?php } ?> onchange="return form_submit();" value="0"> LAMA</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']=='1') { ?> checked="checked" <?php } ?> onchange="return form_submit();" value="1"> REFERRAL</label> &nbsp;
                <label class="font-default"><input type="radio" <?php if($form_data['summery_type']=='2') { ?> checked="checked" <?php } ?> name="summery_type" id="summery_type" onchange="return form_submit();" value="2"> DISCHARGE</label> &nbsp;
                <label class="font-default"><input type="radio" <?php if($form_data['summery_type']=='3') { ?> checked="checked" <?php } ?> name="summery_type" id="summery_type" onchange="return form_submit();" value="3"> D.O.P.R</label> &nbsp;
                <label class="font-default"><input type="radio" <?php if($form_data['summery_type']=='4') { ?> checked="checked" <?php } ?> name="summery_type" id="summery_type" onchange="return form_submit();" value="4"> NORMAL</label>&nbsp;
                <label class="font-default"><input type="radio" <?php if($form_data['summery_type']=='5') { ?> checked="checked" <?php } ?> name="summery_type" id="summery_type" onchange="return form_submit();" value="5"> Expired</label>
           
            </div>
        </div>
        
    </form>    

    <form>
       <?php if(in_array('749',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ipd_patient_discharge_summary_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th>IPD No.</th>
                    <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
                    <th>Patient Name</th> 
                    <th>Mobile No.</th>
                    <th>Status </th> 
                    <th>Created Date </th> 
                    <th>Action </th>
                </tr>
            </thead>  
        </table>
     <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
               <?php if(in_array('750',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update"  onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               
               <a href="<?php echo base_url('ipd_patient_discharge_summary/summary_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

             <a target="_blank" href="<?php echo base_url('ipd_patient_discharge_summary/summary_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ipd_patient_discharge_summary/summary_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
               
               <?php if(in_array('752',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
              
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
              
               <?php if(in_array('753',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_patient_discharge_summary/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'">
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


 function delete_ipd_discharge_summary(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_patient_discharge_summary/delete/'); ?>"+rate_id, 
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
        window.location.href='<?php echo base_url('ipd_patient_discharge_summary');?>'; 
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
        window.location.href='<?php echo base_url('ipd_patient_discharge_summary');?>'; 
    }); 
       
  <?php }?>
 });

 function print_summary(id)
{
  var $modal = $('#load_add_ipd_discharge_summary_print_modal_popup');
  $modal.load('<?php echo base_url().'ipd_patient_discharge_summary/print_template/' ?>'+id,
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
 

  print_window_page('<?php echo base_url('ipd_patient_discharge_summary/print_discharge_summary_letter_head/') ?>'+id+'/'+branch_id+'/'+print_option);
}

function print_letter_head_adv_summary(id,branch_id)
{  
 
  var print_option = 1;
  var id=id;
  var branch_id=branch_id;
 

  print_window_page('<?php echo base_url('Ipd_patient_advance_discharge_summary/print_advance_discharge_summary_letter_head/') ?>'+id+'/'+branch_id+'/'+print_option);
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
  
 function form_submit(vals)
{ 
    var start_date = $('#start_date_patient').val();
    var end_date = $('#end_date_patient').val();
    var summery_type = $("input[name='summery_type']:checked").val();
 
  $.ajax({
         url: "<?php echo base_url('ipd_patient_discharge_summary/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date,summery_type:summery_type} ,
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
if(in_array('749',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ipd_patient_discharge_summary/ajax_list')?>",
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

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/reset_search/", 
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
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
</script> 
<!-- Confirmation Box -->
  <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_patient_discharge_summary/print_discharge_summary"); ?>');">Print</a>

           
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
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_patient_discharge_summary/print_discharge_summary_medicine"); ?>');">Print</a>

           
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