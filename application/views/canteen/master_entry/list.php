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
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('946',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/master_entry/ajax_list')?>",
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
var $modal = $('#load_add_Cat_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/master_entry/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_Category(id)
{

  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/master_entry/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}

function view_Category(id)
{
  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/master_entry/view/' ?>'+id,
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
                      url: "<?php echo base_url('canteen/master_entry/deleteall');?>",
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
           alert('Select at least one checkbox');
       }
 }
</script>

</head>

<body>


<div class="header_top">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
</div>
<!-- ============================= Main content start here ===================================== -->
<main class="main_page">
    <div class="main_wrapper">
      <div class="main_content">


        <section>
          <div class="row">
            <div class="col-lg-4">

              <div class="row">
                <div class="col-md-5">
                    <label for="">From Date</label>  
                </div>
                <div class="col-md-7">
                  <input id="start_date_master" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
                </div>
              </div>

            </div>
            <div class="col-lg-4">

              <div class="row">
                <div class="col-md-5">
                  <label for="">To Date</label>
                </div>
                <div class="col-md-7">
                 <input id="end_date_master" name="end_date" class="datepicker end_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
                </div>
              </div>

            </div>
            <div class="col-lg-4">
             <!-- <a href="javascript:void(0)" class="btn-custom" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a>-->
              <a class="btn-custom" id="reset_date" role="button" onclick="reset_search();">Reset</a>
            </div>
          </div>
        </section>

        <section>
            <form>
                <table id="table" class="table table-striped table-bordered category_list" cellspacing="0" width="100%">
                    <thead class="bg-theme">
                        <tr>
                            <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                            <th> Product Code </th> 
                             <th> Product Name </th> 
                            <th> Product Type </th> 
                            <th> Created Date </th> 
                            <th> Status </th> 
                            <th width="200"> Action </th>
                        </tr>
                    </thead> 
                </table>
            </form>
          </section>
     </div> 
    	<div class="main_btns">
    		<div class="fixed-top">
          <a href="<?php echo base_url('canteen/master_entry/add');?>">
            <button class="btn-hmas" type="button"> <i class="fa fa-plus"></i> New </button>
          </a>
          <button class="btn-hmas"  id="deleteAll" onclick="return checkboxValues();"> <i class="fa fa-trash"></i> Delete </button>
          <button class="btn-hmas" type="button" onclick="return reset();"> <i class="fa fa-refresh"></i> Reload </button>
         <!-- <button class="btn-hmas" type="button"> <i class="fa fa-download"></i> Import </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-upload"></i> Export </button>-->

        <!--  <button class="btn-hmas" type="button"> <i class="fa fa-file-excel-o"></i> Excel </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-file-word-o"></i> CSV </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-file-pdf-o"></i> PDF </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-print"></i> Print </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-file-excel-o"></i> Sample(.xls) </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-file-excel-o"></i> Import(.xls) </button>-->
          <a href="<?php echo base_url('dashboard');?>">
            <button class="btn-hmas" type="button"> <i class="fa fa-sign-out"></i> Exit </button>
          </a>
        </div>
      </div> 
    </div>
    <footer>
      <?php $this->load->view('include/footer'); ?>
    </footer>
</main>

<script>  

 function delete_master(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/master_entry/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
 function reset()
 {
     location.reload();
 }
 
 function form_submit(vals)
{ 
  var start_date = $('#start_date_master').val();
  var end_date = $('#end_date_master').val();
 
  $.ajax({
         url: "<?php echo base_url('canteen/master_entry/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date} ,
         success: function(result)
         { 
            if(vals!="1")
            {
               reload_table(); 
            }
         }
      });      
 }
 
 function reload_table()
 {
     table.ajax.reload(null,false);
 }
 function reset_search(ele)
{ 
    $('#start_date_master').val('');
    $('#end_date_master').val('');
   
    $.ajax({url: "<?php echo base_url(); ?>canteen/master_entry/reset_search/", 
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
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>