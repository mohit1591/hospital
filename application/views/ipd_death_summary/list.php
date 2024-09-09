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


function reset_ipd_search(ele) { 
   $.ajax({url: "<?php echo base_url(); ?>ipd_death_summary/reset_search/", 
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


function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('ipd_death_summary/advance_search/'); ?>",
      type: "post",
      data: $('#search_form').serialize(),
      success: function(result) 
      {
        if(vals!='1')
        {
          $('#load_add_modal_popup').modal('hide'); 
          reload_table();       
          $('#overlay-loader').hide();
        } 
      }
    }); 
}
form_submit('1');


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
            "url": "<?php echo base_url('ipd_death_summary/ajax_list')?>",
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
var $modal = $('#load_add_ipd_death_summary_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'ipd_death_summary/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_ipd_death_summary(id)
{
  var $modal = $('#load_add_ipd_death_summary_modal_popup');
  $modal.load('<?php echo base_url().'ipd_death_summary/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_ipd_death_summary(id)
{
  var $modal = $('#load_add_ipd_death_summary_modal_popup');
  $modal.load('<?php echo base_url().'ipd_death_summary/view/' ?>'+id,
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
                      url: "<?php echo base_url('ipd_death_summary/deleteall');?>",
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
  <div class="col-md-12">



    <div class="row m-b-2">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> Patient Name</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="patient_name" id="patient_name"  value="<?php echo $form_data['patient_name'];?>" class="m_input_default"  onkeyup="return form_submit();">
            </div>
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
              <input type="text" name="start_date" id="start_date_p"  value="<?php echo $form_data['start_date'];?>" class="datepicker m_input_default start_datepicker"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <label>To date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date']?>" class="datepicker m_input_default end_datepicker"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <input value="Reset" class="btn-custom" onclick="reset_ipd_search(this.form)" type="button">
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->

  </div> <!-- 12 -->
</div> <!-- row -->
    


</form>

    <form>
      
       <!-- bootstrap data table -->
       <div class="table-repsponsive"  style="overflow:auto;padding-right:15px;">
         <table id="table" class="table table-striped table-bordered patient_list_tbl" cellspacing="0" width="100%">
             <thead class="bg-theme">
                 <tr>
                     <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                     <th>S. No.</th>
                     <th>UHID No.</th>
                     <th>Patient Name</th>
                     <th>Mobile No.</th> 
                     <th>Address</th>
                     <th>Death Date </th> 
                     <th>Death Time </th> 
 
                     <th>Date/Time of Admission</th>
                     <th>Cause of Death</th>
                     <th>Treating Doctor</th>
                     <th>Caste and Religion</th>
                     <th>Mothers name</th>
                     <th>Occupation of the patient</th>
                     
                     <th>Action</th>
                     
                 </tr>
             </thead>  
         </table>
       </div>
     
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative">
    <div class="fixed">
      <div class="btns">
                <a href="<?php echo base_url('ipd_death_summary/ipd_death_excel'); ?>" class="btn-anchor m-b-2">
                  <i class="fa fa-file-excel-o"></i> Excel
                  </a>


                  <a href="<?php echo base_url('ipd_death_summary/pdf_ipd_death'); ?>" class="btn-anchor m-b-2">
                  <i class="fa fa-file-pdf-o"></i> PDF
                  </a>

                  
                  <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ipd_death_summary/print_ipd_death"); ?>');">
                <i class="fa fa-print"></i> Print
                </a> 
              
              <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_death_summary'); ?>'">
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

  function delete_born(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_death_summary/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
  $('#load_add_ipd_death_summary_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
 $('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_death_summary');?>'; 
    }); 
       
  <?php }?>
 });

 $('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print_medicine') { ?>
  $('#confirm_medicine_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_death_summary');?>'; 
    }); 
       
  <?php }?>
 });

 function print_summary(id)
{
  var $modal = $('#load_add_ipd_discharge_summary_print_modal_popup');
  $modal.load('<?php echo base_url().'ipd_death_summary/print_template/' ?>'+id,
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
 

  print_window_page('<?php echo base_url('ipd_death_summary/print_discharge_summary_letter_head/') ?>'+id+'/'+branch_id+'/'+print_option);
} 
</script> 
<!-- Confirmation Box -->
  <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_death_summary/print_discharge_summary"); ?>');">Print</a>

           
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
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_death_summary/print_discharge_summary_medicine"); ?>');">Print</a>

           
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
<div id="load_add_ipd_death_summary_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ipd_discharge_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>