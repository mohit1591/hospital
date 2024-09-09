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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 

<script type="text/javascript">
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#medicine_entry_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'stock_issue_allotment_return/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_stock_purchase(id)
{
   window.location.href= '<?php echo base_url().'stock_issue_allotment_return/edit/' ?>'+id;

  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'stock_issue_allotment_return/view/' ?>'+id,
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


var allVals = [];
function checkboxValues() 
{         
    $('#table').dataTable();
     
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       }
       else { 
	       if ((index = allVals.indexOf($(this).val())) !== -1) { allVals.splice(index, 1);}
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
            e.stopPropagation(); e.preventDefault();
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('stock_issue_allotment_return/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   } 
else
	{
	 $('#nochecked').modal({ backdrop: 'static', keyboard: false })
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
  <form id="new_search_form">
  <table class="ptl_tbl">
     
      <tr>
        <td><label>From Date</label> <input type="text" name="start_date" id="start_date_p" class="datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'] ?>" placeholder="From Date"></td>
        <td><label>To Date</label> <input type="text" name="end_date" id="end_date_p" class="datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['end_date'] ?>" placeholder="To Date"></td>
        <td><!-- <a href="javascript:void(0)" class="btn-a-search" id="adv_search_purchase"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> --> <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a></td>
      </tr>
      </table>

   



 </form>
    	 
    <form>
       <?php if(in_array('970',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        
		 <table id="table" class="table table-striped table-bordered opd_collection_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll_allot_re" value=""> </th> 
                     <th> Return No. </th> 
                     <th> Items </th> 
                    <th> Issue No. </th> 
                    <th> Patient / Employee / Doctor Name </th>
					<th> Total Amount </th>
                    <th> Discount </th>					
                    <th> Net Amount </th> 
                    <th> Paid Amount </th> 
                    <th> Balance </th> 
                    <th> Return Date </th> 
                    <th width="150"> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>
          
    </form>
  </div> <!-- close -->

  	<div class="userlist-right">
  		<div class="btns">
               <?php if(in_array('971',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('stock_issue_allotment_return/add');?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
             <?php }?>

               <?php if(in_array('970',$users_data['permission']['action'])) {
               ?>
              <a href="<?php echo base_url('stock_issue_allotment_return/stock_allotment_return_issue_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('stock_issue_allotment_return/stock_allotment_return_issue_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('stock_issue_allotment_return/pdf_stock_allotment_return_issue'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("stock_issue_allotment_return/print_stock_allotment_return_issue"); ?>');"> <i class="fa fa-print"></i> Print</a>

               <?php } ?>
               <?php if(in_array('974',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
               <?php if(in_array('970',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('975',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-exit" onclick="window.location.href='<?php echo base_url('stock_issue_allotment_return/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
$(document).ready(function(){
 
   $('#selectAll_allot_re').on('click', function () { 
   
   if ($("#selectAll_allot_re").hasClass('allChecked')) {
          
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
 function delete_stock_purchase(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('stock_issue_allotment_return/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
$(document).ready(function() {
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
</script> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->

<!-- Confirmation no select -->
<div id="nochecked" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header bg-theme"><h4>Please select at least one record! </h4></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation no select -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_medicine').on('click', function(){
  // alert();
$modal.load('<?php echo base_url().'stock_issue_allotment_return/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});


 $('#start_date_p').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#start_date_p').val();
      $('#end_date_p').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('#end_date_p').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });
  
function form_submit(vals)
{ 
  var start_date = $('#start_date_p').val();
  var end_date = $('#end_date_p').val();
  
 $.ajax({
         url: "<?php echo base_url('stock_issue_allotment_return/advance_search/'); ?>", 
         type: 'POST',
         data: {start_date: start_date, end_date : end_date} ,
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
if(in_array('970',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
   table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('stock_issue_allotment_return/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });

     //form_submit();
}); 
<?php } ?>

function reset_search()
  { 
    $('#start_date_p').val('');
    $('#end_date_p').val('');
    $.ajax({url: "<?php echo base_url(); ?>stock_issue_allotment_return/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }

/*function form_submit()
{
  $('#new_search_form').delay(200).submit();
}*/
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
</script>
<style>.opd_collection_list td:nth-child(4), .opd_collection_list td:nth-child(5) {text-align: right !important; padding-right: 5px;}</style>
<!-- container-fluid -->
</body>
</html>