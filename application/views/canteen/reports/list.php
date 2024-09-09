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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

<script type="text/javascript">
var save_method; 
var table; 
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,  
        "pageLength": '20', 
        "ajax": {
            "url": "<?php echo base_url('reports/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
      $.ajax({
         url: "<?php echo base_url('reports/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
  }

$(document).ready(function(){
var $modal = $('#load_advance_search_modal_popup');
$('#advance_search').on('click', function(){
$modal.load('<?php echo base_url().'reports/advance_search/' ?>',
{
//'id1': '1',
//'id2': '2'
},
function(){
$modal.modal('show');
});

});

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
    <div class="userlist-box media_100"> 
    <form> 

    <div class="grp_box m-b-5 media_m_b_5 media_d_default">
      <div class="grp media_float_left">
        <label><b>Branch:</b></label>  
            <select name="branch_id">
                <option value="">Self</option>
                <option value="0">All</option>
                <?php
                foreach($sub_branch as $branch)
                {
                 ?>
                  <option value="<?php echo $branch['id']; ?>"><?php echo $branch['branch_name']; ?></option>
                 <?php  
                }
                ?>
            </select>
      </div>

      <div class="grp media_float_left">

            <label><b>From Date:</b></label>
            <input type="text" id="start_date" name="from_date" class="datepicker media_w_100" value="<?php echo $form_data['start_date']; ?>">
      </div>

      <div class="grp media_float_left">
            <label><b>To Date:</b></label>
            <input type="text" name="to_date" id="end_date" class="datepicker media_w_100" value="<?php echo $form_data['end_date']; ?>">
      </div>
      <div class="grp media_float_left">
            <a class="btn-custom media_btn1" id="reset_date" onClick="reset_date_search();">
              <i class="fa fa-refresh"></i> Reset
            </a>
      </div>

      <div class="grp media_float_left">
            <a class="btn-custom media_w_auto" id="advance_search">
              <i class="fa fa-cubes"></i> Advance Search 
            </a>

      </div> 
    </div> <!-- //row -->






       
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered pathology_report_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> Lab Reg. No. </th> 
                    <th> Booking Date </th>  
                    <th> Patient Name </th>
                    <th> Doctor Name </th>
                    <th> Department </th>
                    <th> Total Amount </th>
                    <th> Discount </th>
                    <th> Net Amount </th>
                    <th> Paid Amount </th>
                    <th> Balance </th>
                    <th> Status </th> 
                </tr>
            </thead>  
        </table> 
    </form>
   </div> <!-- close -->
  	<div class="userlist-right media_100" id="example_wrapper">
  		<div class="btns media_btns"> 
        <a href="<?php echo base_url('reports/path_report_excel'); ?>" class="btn-anchor">
          <i class="fa fa-file-excel-o"></i> Excel
        </a>
         
        <a href="<?php echo base_url('reports/path_report_csv'); ?>" class="btn-anchor">
          <i class="fa fa-file-word-o"></i> CSV
        </a>

        <a href="<?php echo base_url('reports/pdf_path_report'); ?>" class="btn-anchor">
          <i class="fa fa-file-pdf-o"></i> PDF
        </a>

        <a href="javascript:void(0)" class="btn-anchor" id="deleteAll" onClick="return openPrintWindow('<?php echo base_url("reports/print_path_report"); ?>', 'windowTitle', 'width=820,height=600');">
          <i class="fa fa-print"></i> Print
        </a>
         
       <button class="btn-update" onClick="reload_table()">
          <i class="fa fa-refresh"></i> Reload
        </button> 
         
        <button class="btn-update" onClick="window.location.href='<?php echo base_url(); ?>'">
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
<script type="text/javascript">
  
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    $.ajax({
           url: "<?php echo base_url('reports/search_data/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
    $("#end_date").datepicker({ minDate: selectedDate });
}); 

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
</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>