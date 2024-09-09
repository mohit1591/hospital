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
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2076',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ambulance/all_ambulance_docs/ajax_list')?>",
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
     //alert(allVals);
    if(allVals!="")
    {
      allbranch_delete(allVals);
    }
    else{
      alert('Select atleast one checkbox');
    }

     
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
                      url: "<?php echo base_url('ambulance/vehicle/deleteall_document_file');?>",
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
       
<form name="search_form"  id="search_form"> 
  <div class="row">
    <div class="col-sm-5">
      <div class="row m-b-5">
        <div class="col-xs-4"> <label><b>Created Date:</b></label></div>
        <div class="col-xs-8">
          <input type="text" id="create_from"  name="create_from" class="w-100px"> &nbsp; <input type="text" name="create_to" id="create_to" class="w-100px">
        </div>
      </div>
      <div class="row m-b-5">
        <div class="col-xs-4"> <label><b>Renewal Date:</b></label></div>
        <div class="col-xs-8">
            <input type="text" id="renewal_from" name="renewal_from"  class="w-100px"> &nbsp; <input type="text" name="renewal_to" id="renewal_to" class="w-100px">
        </div>
      </div>
      <div class="row m-b-5">
        <div class="col-xs-4"><label><b>Expiry Date:</b></label></div>
        <div class="col-xs-8">
          <input type="text" id="exp_from"  name="exp_from" class="w-100px">  &nbsp; <input type="text" name="exp_to" id="exp_to" class="w-100px">
        </div>
      </div>
    </div> <!-- 4 -->

    <div class="col-sm-4">
      <div class="row m-b-5">
        <div class="col-xs-4"><label><b>Document :</b></label></div>
        <div class="col-xs-8">
         <select name="document_id" id="document_id" onchange="return form_submit();" class="m_select_btn">
            <option value="">All</option>
            <?php
            if(!empty($document_list))
            {
              foreach($document_list as $list)
              {
                ?>
                  <option value="<?php echo $list->id; ?>"><?php echo $list->document; ?></option>                                      
                <?php
              }
            }
            ?>
      </select> 
        </div>
      </div>
      <div class="row m-b-5">
        <div class="col-xs-4"><label><b>Vehicle :</b></label></div>
        <div class="col-xs-8">
            <select name="vehicle_id" id="vehicle_id"  onchange="return form_submit();" class="m_select_btn">
               <option value="">All</option>
                <?php
                if(!empty($vehicle_list))
                {
                  foreach($vehicle_list as $list)
                  {
                    ?>
                      <option value="<?php echo $list->id; ?>"><?php echo $list->vehicle_no; ?></option>
                    <?php
                  }
                }
                ?>
          </select> 
        </div>
      </div>
      
      <div class="row m-b-5">
        <div class="col-xs-4"><label><b>Remark :</b></label></div>
        <div class="col-xs-8">
            <input type="text" name="remark" onkeyup="return form_submit();" id="remark">
        </div>
      </div>
    </div> <!-- 4 -->
    <div class="col-sm-2">
     <input value="Reset" class="btn-custom" onclick="clear_form_elements()" type="reset">
   </div> <!-- 4 -->
 </div> <!-- row -->
</form>

    <form>
       <?php if(in_array('2076',$users_data['permission']['action'])) {
        ?> 
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered driver_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Document File </th> 
                    <th> Document Name </th>
                    <th> Vehicle No. </th>
                    <th> Renewal Date </th> 
                    <th> Expiry Date </th>
                    <th> Status </th>  
                    <th> Created Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
    <?php } ?> 
    </form>
   </div> <!-- close -->
    <div class="userlist-right">

      <div class="btns">
              <a href="<?php echo base_url('ambulance/all_ambulance_docs/document_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>
              <a href="<?php echo base_url('ambulance/all_ambulance_docs/pdf_documents'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              <?php if(in_array('2370',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                      <i class="fa fa-trash"></i> Delete
                    </button>
                   <?php } ?>
                    
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
                     <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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
 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
 function delete_document_file(vehicle_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ambulance/vehicle/delete_document_file/'); ?>"+vehicle_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

// create
$('#create_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#create_from').val();
      $('#create_to').datepicker('setStartDate', start_data);
      form_submit();
  });
  $('#create_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
       form_submit();
  });    

  // renewal
  $('#renewal_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#renewal_from').val();
      $('#renewal_to').datepicker('setStartDate', start_data);
        form_submit();
  });
  $('#renewal_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
       form_submit();
  });  
  // expiry
  $('#exp_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#exp_from').val();
      $('#exp_to').datepicker('setStartDate', start_data);
       form_submit();
  });
  $('#exp_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
       form_submit();
  });  

function form_submit(vals)
{ 

 $.ajax({
   url: "<?php echo base_url('ambulance/all_ambulance_docs/advance_search/'); ?>", 
   type: 'POST',
   data: $('#search_form').serialize(),
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
function clear_form_elements()
{
    $("#search_form").trigger('reset');
     $.ajax({
       url: "<?php echo base_url('ambulance/all_ambulance_docs/reset_form/'); ?>", 
       type: 'POST',
       data: {},
       success: function(result)
       {
           reload_table(); 
       }
     });  
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
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!----container-fluid--->
</body>
</html>