<!DOCTYPE html>
<html> <?php
error_reporting(0); // Turn off all error reporting
$this->session->unset_userdata('schmed_qty_search');
?>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>

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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
/*function reset_search(ele)
{ 
    $('#start_date').val('');
    $('#end_date').val('');
    $('#type').val('');

    $.ajax({url: "<?php echo base_url(); ?>schedule_medicine_report/reset_search/", 
      success: function(result)
      { 
        //reload_table();
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
  }*/
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

    <div class="row m-b-5">
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-4"><label>From Date</label></div>
          <div class="col-xs-8">
            <input id="start_date" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>
                  
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-4"><label>To Date</label></div>
          <div class="col-xs-8">
            <input name="end_date" id="end_date" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php $form_data=''; echo $form_data['end_date']?>" type="text">
          </div>
        </div>       

      </div> <!-- 4 -->

      <div class="col-sm-4">
      <!--   <a class="btn-custom" id="reset_date" onclick="reset_search(this.form);"><i class="fa fa-refresh"></i> Reset</a> -->
      </div> <!-- 4 -->
    </div> <!-- row -->


   <!-- <div class="row">
      <div class="col-sm-4">

          <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
          
          <div class="row m-b-5" id="doctor_div" >
           
              <label class="col-xs-4"> Search Criteria </label>
            
            <div class="col-xs-8">
              <select class="" name="type" id="type" onchange="return form_submit(this.value);">
                <option value="1" <?php if($form_data['type']=='1'){ ?> checked="checked" <?php } ?> > Medicine </option> 
                <option value="2" <?php if($form_data['type']=='2'){ ?> checked="checked" <?php } ?> > Referred By Doctor</option>
                <option value="3" <?php if($form_data['type']=='3'){ ?> checked="checked" <?php } ?> > Referred By Hospital</option>
              </select>
            </div>
         
        </div>

       <?php } ?>    
    
      </div>   
    </div> -->

    
   
    <div class="table-responsive">
      
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered dataTable patient_list_tbl" cellspacing="0" width="100%">
            
             <thead>
               <tr class="bymedicine">            
                    <th width="40" align="center">S.No.</th> 
                    <th> Bill No. </th>  
                    <th> Doctor/Hospital Name </th>  
                    <th> Patient Name </th>  
                    <th> Medicine Name </th>
                    <th> Quantity </th>  
                    <th> Manuf. Company </th>  
                    <th> Batch No. </th>  
                    <th> Exp. Date </th>
                    <th> Bill Date </th>
                 </tr>   
            </thead>  
        </table>
       
        </div>
    </form>


   </div> <!-- close -->


    <div class="userlist-right relative">
      <div class="fixed">
      <div class="btns">
        <a href="javascript:void(0)" id="exportExcel" class="btn-anchor m-b-2"><i class="fa fa-file-excel-o"></i> Excel</a>

        <a id="exportPdf" href="javascript:void(0)" class="btn-anchor m-b-2"><i class="fa fa-file-pdf-o"></i> PDF
               </a>

        <a id="exportPrint" href="javascript:void(0)" class="btn-anchor m-b-2"><i class="fa fa-print"></i> Print</a> 

        <button class="btn-update" onclick="reload_table()"><i class="fa fa-refresh"></i> Reload</button>
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
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

function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('schedule_medicine_report/advance_search/'); ?>",
      type: "post",
      data: $('#search_form_list').serialize(), 
      success: function(result) 
      {   
          reload_table();       
          $('#overlay-loader').hide();       
      }
    }); 
}



//form_submit('1');
var save_method; 
var table;
     $(document).ready(function() { 
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true,
             "bLengthChange": true, 
             "bFilter":true,
             "order": [], 
             "pageLength": '100',
             "ajax": {
                 "url": "<?php echo base_url('schedule_medicine_report/schedule_medicine_qty_list')?>",
                 "type": "POST",
                 
             }, 
             "columnDefs": [
             { 
                 "targets": [ 0 , -1 ], //last column
                 "orderable": false, //set not orderable

             },
             ],
         });
      /*  $('.tog-col').on( 'click', function (e) 
        {
          var column = table.column( $(this).attr('data-column') );
          column.visible( ! column.visible() );
        });*/

     }); 

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

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
</script> 

<script>
  $('#exportExcel').on('click', function() {
    var search_query = $('#table_filter input').val();
    window.location.href = "<?php echo base_url('schedule_medicine_report/schedule_medicine_report_excel'); ?>?search_query=" + search_query;
});
</script>

<script>
  $('#exportPdf').on('click', function() {
    var search_query = $('#table_filter input').val();
    window.location.href = "<?php echo base_url('schedule_medicine_report/schedule_medicine_report_pdf'); ?>?search_query=" + search_query;
});
</script>
<script>
  $('#exportPrint').on('click', function() {
    var search_query = $('#table_filter input').val();
    let url = '<?php echo base_url("schedule_medicine_report/schedule_medicine_report_print")?>?search_query='+search_query;
    print_window_page(url);
});

</script>
<!-- Confirmation Box -->
</body>
</html>