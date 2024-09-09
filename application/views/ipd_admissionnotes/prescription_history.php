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

$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ipdprescriptionhistory/ajax_list/'.$patient_id)?>",
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

 
function edit_prescription(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_prescription/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_prescription(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_prescription/view/' ?>'+id,
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
                      url: "<?php echo base_url('ipd_prescription/deleteall');?>",
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


 function upload_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'ipd_prescription/upload_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

 function view_files(prescription_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'ipd_prescription/view_files/' ?>'+prescription_id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
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
    <!-- <div class="advance_search-btn-box">
       <a href="javascript:void(0)" class="btn-commission2" id="adv_search">
          <i class="fa fa-search" aria-hidden="true"></i> 
          Advance Search
        </a>
    </div>	 --> 
    <form>
       
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered prescription_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <!-- <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th>  -->
                    <th> Prescription code </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th>  
                    <th> Name </th> 
                    <th> Mobile </th> 
                    
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
  			<button class="btn-exit" onclick="window.location.href='<?php echo base_url('ipd_prescription'); ?>'">
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
 function delete_prescription(prescription_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_prescription/delete/'); ?>"+prescription_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

//$('#print-btn').click(function(){
 function print_prescription(id){ 
  $.ajax({
      url: "<?php echo base_url();?>ipd_prescription/print_prescription_pdf/"+id, 
      type: 'post',
      dataType: 'json',
      
      success: function(response){
        if(response.success)
        { 
          printdiv(response.pdf_template);
         }
         else
         {
          alert(response.msg);   
         }
      },
      }); 
           }

function printdiv(printpage)
{
var headstr = "<html><head><title></title></head><body>";
var footstr = "</body>";
var newstr = printpage
var oldstr = document.body.innerHTML;
//document.getElementById('header').style.display = 'none';
//document.getElementById('footer').style.display = 'none';

document.body.innerHTML = headstr+newstr+footstr;
window.print();
//window.location.reload();
return false;
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
</div><!-- container-fluid -->
</body>
</html>