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

 <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->

<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('415',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
  form_submit();
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        "searching": false,
        "bLengthChange": false , 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('vaccination_stock/medicine_allot_history_ajax')?>",
            "type": "POST",
            "data": function(d){
                d.vaccine_id = getUrlData('id');
                d.batch_no = getUrlData('batch_no');
                d.type = getUrlData('type');
               
                return d;

            },
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
     allbranch_delete(allVals);
} 
function getUrlData(name) { 
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
          return "";
   else
      return results[1];
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
                      url: "<?php echo base_url('vaccination_stock/deleteall');?>",
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

 function reset_search()
  { 
    $('#start_date').val('');
    $('#end_date').val('');
    
    $('#batch_no').val('');
    $.ajax({url: "<?php echo base_url(); ?>vaccination_stock?>/reset_search/", 
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
   
    <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
         <input type="radio" name="types" class="types" value="1" onclick="return type_set(1)" <?php if($type==1){ echo 'checked=""'; } ?> /> Purchase 
         <input type="radio" name="types" <?php if($type==2){ echo 'checked=""'; } ?> class="types" value="1" onclick="return type_set(2)" /> Purchase Return
         <input type="radio" name="types" <?php if($type==3){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(3)" /> Billing 
         <input type="radio" name="types" <?php if($type==4){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(4)" /> Billing Return

         <input type="radio" name="types" <?php if($type==5){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(5)" /> Branch Allotment 

         <!-- <input type="radio" name="types" < ?php if($type==2){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(2)" /> Sale  -->

         <!--  <input type="radio" name="types" < ?php if($type==3){ echo 'checked=""'; } ?> class="types" value="3" onclick="return type_set(3)" /> Manage Kit Quantity  Branch Allotment-->
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>
    	 
    <form>
       <?php if(in_array('415',$users_data['permission']['action'])) {
       $type = $this->input->get('type');
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_stock_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                 <?php if($type==1 || $type==2){ ?>
                 <th> S.No.</th>
                    
                    <th> Vaccination Code </th>
                    <th> Vender Name </th>
                    <th> Vaccination Name </th>
                    <th> Vaccination Company </th>
                     <th> Batch No. </th> 
                     <th> Barcode </th>  
                    <th> Quantity </th>
                    <th> Date</th> 
                <?php }elseif($type==3 || $type==4){ ?>    
                  
                    <th> S.No.</th>
                      
                      <th> Vaccination Code </th>
                      <th> Patient Name </th>
                      <th> Vaccination Name </th>
                      <th> Vaccination Company </th>
                      <th> Batch No. </th>  
                       <th> Barcode </th>  
                      <th> Quantity </th>
                      <th> Date</th>
                    <?php }else{ 
                      ?>
                      <th> S.No.</th>
                      <th> Branch Name</th>
                      <th> Vaccination Code </th>
                      
                      <th> Vaccination Name </th>
                      <th> Vaccination Company </th>
                      
                      <th> Quantity </th>
                      <th> Date</th>

                      <?php 

                      } ?>
                  </tr>
            </thead>  
        </table>
        <?php } ?>
      
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
  		<div class="btns">
              <!--  <?php if(in_array('582',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update h-auto" onclick="load_allot_model()">
                         <i class="fa fa-refresh"></i> Allotment To Branch
                    </button>
               <?php } ?>
               <?php if(in_array('578',$users_data['permission']['action'])) {
               ?>
                <a href="<?php echo base_url('vaccination_stock/medicine_stock_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>
                <?php } ?>
               <?php if(in_array('579',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('vaccination_stock/medicine_stock_csv'); ?>" class="btn-anchor m-b-2">
                         <i class="fa fa-file-word-o"></i> CSV
                    </a>
               <?php } ?>
               <?php if(in_array('580',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('vaccination_stock/pdf_medicine_stock'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
                    </a>
               <?php } ?>
               <?php if(in_array('581',$users_data['permission']['action'])) {
               ?>        -->
                    <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('< ?php echo base_url("vaccination_stock/print_medicine_stock"); ?>', 'windowTitle', 'width=820,height=600');">
                    <i class="fa fa-print"></i> Print
                    </a> -->

                   <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("vaccination_stock/print_medicine_stock"); ?>');"> <i class="fa fa-print"></i> Print</a>
               <?php } ?> -->
               <?php if(in_array('415',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
             
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('vaccination_stock');?>'">
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
//medicine_allot_history?id=1571&batch_no=2334455
function type_set(vals)
{ 
   window.location.href='<?php echo base_url('vaccination_stock/medicine_allot_history').'/?id='.$id.'&batch_no='.$batch_no; ?>&type='+vals;
} 

function load_allot_model()
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
     var $modal = $('#load_allot_to_branch_modal_popup');
     $modal.load('<?php echo base_url('vaccination_stock/allotement_to_branch/'); ?>',{'medicine_ids':allVals},function(){
          $modal.modal('show');
     });

   
}
 function delete_medicine_stock(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('vaccination_stock/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
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
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
/*var $modal = $('#load_add_modal_popup');
  $('#adv_search_stock').on('click', function(){
  //  alert();
$modal.load('< ?php echo base_url().'vaccination_stock/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});*/


 /*function openPrintWindow(url, name, specs) {
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
*/

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>vaccination_stock/advance_search/",
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

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/

  var today =new Date();
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
    })
</script>
<!-- container-fluid -->
</body>
</html>