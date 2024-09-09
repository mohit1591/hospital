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
        "aaSorting": [],
        "ajax": {
            "url": "<?php echo base_url('billing_summary_report/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
    form_submit();
});

 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
      $('#employee').val('');
      $.ajax({
         url: "<?php echo base_url('billing_summary_report/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
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
    <form> 

    <div class="grp_box m-b-5">
      
      <input type="hidden" name="branch_id" class="m_input_default" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">

      <div class="grp">

            <label><b>From Date:</b></label>
            <input type="text" id="start_date" name="from_date" class="datepicker start_datepicker m_input_default  w-80px" value="<?php echo $form_data['start_date']; ?>">
      </div>

      <div class="grp">
            <label><b>To Date:</b></label>
            <input type="text" name="to_date" id="end_date" class="datepicker datepicker_to end_datepicker m_input_default  w-80px" value="<?php echo $form_data['end_date']; ?>">
      </div>
      <div class="grp">
              <label>Particulars Name: </label>
              <!-- <input type="text" name="particulars" id="particulars" value="<?php echo $form_data['particulars']; ?>" onkeyup ="return form_submit();"/> -->
              <select name="particulars" id="particulars" class="m_select_btn" onchange="return form_submit(this.value);">
                  <option value="">Select Particulars</option>
                  <?php
                  if(!empty($particulars_list))
                  {
                    foreach($particulars_list as $particularslist)
                    {
                      echo '<option value="'.$particularslist->id.'">'.$particularslist->particular.'</option>';
                    }
                  }
                  ?> 
                </select>
            </div>
            
             <div class="grp">
              <label>By Particulars: </label>
              <input type="text" name="particulars_name" id="particulars_name" value="<?php echo $form_data['particulars_name']; ?>" onkeyup ="return form_submit();"/> 
              
            </div>
                        
      <div class="grp">
            
<input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
      </div>

      
    </div> <!-- //row -->

<!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered billing_collection_report_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> S. No.</th> 
                    <th> Receipt No.</th>
                    <th> Token No.</th> 
                    <th> Patient Name </th>
                    <th> Reg. No. </th>
                    <?php  if($users_data['parent_id']!='157'){?>
                    <th> Particular </th>
                  <?php } ?>
                    <th> Amount </th>
                    
                    <th> Payment Mode </th> 
                </tr>
            </thead>  
        </table> 
       
    </form>
   </div> <!-- close -->
    <div class="userlist-right relative" id="example_wrapper">
      <div class="fixed">
      <div class="btns"> 
               <a href="<?php echo base_url('billing_summary_report/billing_summary_report_excel'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-excel-o"></i> Excel
               </a>
               <a href="<?php echo base_url('billing_summary_report/pdf_billing_summary_report'); ?>" class="btn-anchor m-b-2">
               <i class="fa fa-file-pdf-o"></i> PDF
               </a>
            <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("billing_summary_report/print_billing_collection_report"); ?>');">
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
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?> 
<script type="text/javascript">
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>billing_summary_report/reset_date_search/",
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

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>billing_summary_report/reset_date_search/",
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


function form_submit(){
    var start_date = $('#start_date').val();
    var branch_id = $('#branch_id').val();
    var end_date = $('#end_date').val();
    var particulars = $('#particulars').val();
    var particulars_name = $('#particulars_name').val();
    $.ajax({
           url: "<?php echo base_url('billing_summary_report/search_data/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date,branch_id:branch_id,particulars:particulars,particulars_name:particulars_name} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
   
    //$("#end_date").datepicker({ minDate: selectedDate });
    $('.end_datepicker').datepicker('setStartDate', start_date);
}
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