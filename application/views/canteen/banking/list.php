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
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('30',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/banking/ajax_list')?>",
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
<?php  
}
?>




$(document).ready(function(){
var $modal = $('#load_add_banking_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/banking/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_banking(id)
{
  var $modal = $('#load_add_banking_modal_popup');
  $modal.load('<?php echo base_url().'canteen/banking/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

 function delete_banking(rate_id)
 {    
 
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/banking/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

function view_emp_type(id)
{
  var $modal = $('#load_add_banking_modal_popup');
  $modal.load('<?php echo base_url().'canteen/banking/view/' ?>'+id,
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
                      url: "<?php echo base_url('canteen/banking/deleteall');?>",
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

 function form_submit()
{
  $('#bank_search_form').delay(200).submit();
}

$("#bank_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>canteen/banking/advance_search/",
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

$(document).ready(function(){
/* var today =new Date();
    $('.datepicker_from').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
       //alert(selected);
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $(".datepicker_to").datepicker("option", "minDate", selected);
              form_submit();
      }
    })

    $(".datepicker_to").datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          //$('.datepicker').datepicker("option", "maxDate", selected);
      }
    })*/


});
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
    	   <form name="search_form_list"  id="search_form_list"> 

    <div class="row">
     <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="datepicker_from" name="start_date" class="datepicker start_datepicker m_input_default datepicker_from" type="text" value="">
          </div>
        </div>


       <!-- <div class="row m-b-5">
          <div class="col-xs-5"><label>Amount</label></div>
          <div class="col-xs-7">
            <input name="amount" value="" id="amount" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text" autofocus="">
          </div>
        </div-->
      
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
          <input name="end_date" id="datepicker_to" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
       
      </div> <!-- 4 -->
  </div>
         
    </form>

    <form>
      
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered employee_type_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                  
                   <th> Bank Name.</th> 
                   <th> A/c Name</th> 
                    <th> A/c No.</th> 
                     <th> Amount</th> 
                     <th> Deposit Date</th> 
                    <th> Status </th> 
                    <th> Created Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
     
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
      <?php
      if(in_array('613',$users_data['permission']['action'])) 
      {
      ?>
  			<button class="btn-update" id="modal_add">
  				<i class="fa fa-plus"></i> New
  			</button>
      <?php
      }?>

       <?php if(in_array('619',$users_data['permission']['action'])) { ?>
        <a href="<?php echo base_url('canteen/banking/banking_excel'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-excel-o"></i> Excel
        </a>
        <?php }  if(in_array('620',$users_data['permission']['action'])) { ?>
        <a href="<?php echo base_url('canteen/banking/banking_csv'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-word-o"></i> CSV
        </a>
        <?php }  if(in_array('621',$users_data['permission']['action'])) { ?>
        <a href="<?php echo base_url('canteen/banking/pdf_banking'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-pdf-o"></i> PDF
        </a>
        <?php }  if(in_array('622',$users_data['permission']['action'])) { ?>
        <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('<?php echo base_url("banking/print_banking"); ?>', 'windowTitle', 'width=820,height=600');">
        <i class="fa fa-print"></i> Print
        </a>

    <?php }  if(in_array('615',$users_data['permission']['action'])) 
      {
      ?>
        <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
          <i class="fa fa-trash"></i> Delete
        </button>
      <?php
      }

      //if(in_array('30',$users_data['permission']['action'])) 
      //{
      ?>
        <button class="btn-update" onclick="reload_table()">
          <i class="fa fa-refresh"></i> Reload
        </button>
      <?php
      //}

      if(in_array('616',$users_data['permission']['action'])) 
      {
      ?>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url('canteen/banking/archive'); ?>'">
          <i class="fa fa-archive"></i> Archive
        </button>
      <?php
      }
      ?>  
  			 
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

 function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};



function form_submit()
{
  $('#search_form_list').delay(200).submit();
}

$("#search_form_list").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('canteen/banking/advance_search/'); ?>",
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

// $('.start_datepicker').datepicker({
//     format: 'dd-mm-yyyy', 
//     autoclose: true, 
//     endDate : new Date(), 
//   }).on("change", function(selectedDate) 
//   { 
//       var start_data = $('.start_datepicker').val();
//       $('.end_datepicker').datepicker('setStartDate', start_data); 
//       form_submit();
//   });

//   $('.end_datepicker').datepicker({
//     format: 'dd-mm-yyyy',     
//     autoclose: true,  
//   }).on("change", function(selectedDate) 
//   {   
//      form_submit();
//   });

 $('#load_add_banking_modal_popup').on('shown.bs.modal', function(e){
   $(this).find(".inputFocus").focus();
    });

$(document).ready(function(){
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
 });

 
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
<div id="load_add_banking_modal_popup" class="modal fade top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>