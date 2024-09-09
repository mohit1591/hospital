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
$(document).ready(function() { 

    table = $('#table').DataTable({ /*  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "< ?php echo base_url('blood_bank/stock/view_start')?>/"+'< ?php echo $stock_id; ?>'+'/'+'< ?php echo $blood_grp_id; ?>'+'/'+'< ?php echo $component_id; ?>',
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    */ 
        
          
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
             "url": "<?php echo base_url('blood_bank/stock/view_start')?>/"+'<?php echo $stock_id; ?>'+'/'+'<?php echo $blood_grp_id; ?>'+'/'+'<?php echo $component_id; ?>',
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
                    <div class="col-xs-2">
                     
                        
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Blood Group</strong></div>
                            <div class="col-xs-8">
                                <?php echo $stock_list['blood'];?>
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Component Name</strong></div>
                            <div class="col-xs-8">
                                <?php echo $stock_list['component_name'];?>
                            </div>
                        </div>
                        
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Component Price</strong></div>
                            <div class="col-xs-8">
                                <?php echo $stock_list['component_price'];?>
                            </div>
                        </div>
                       
                        
                    </div> <!-- 5 -->
                </div>
    </div>
  </div>
    	 
    <form>
       
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_stock_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                 <th> S.No.</th>
                    
                    <th> Donor Id </th>
                    <th> Bar Code </th>
                    <th> Blood Group </th>
                    <th> Component </th>
                     <th> Quantity </th> 
                     <th> Expiry Date </th>  
                    <th> Status </th>
                    <th> Created Date</th> 
              
                  </tr>
            </thead>  
        </table>
        
      
    </form>


   </div> <!-- close -->
    <div class="userlist-right">
    <div class="btns">


    <button class="btn-update" onclick="reload_table()">
    <i class="fa fa-refresh"></i> Reload
    </button>


    <button class="btn-exit" onclick="window.location.href='<?php echo base_url('blood_bank/stock');?>'">
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
     $modal.load('<?php echo base_url('medicine_stock/allotement_to_branch/'); ?>',{'medicine_ids':allVals},function(){
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
                 url: "<?php echo base_url('medicine_stock/delete/'); ?>"+id, 
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
$modal.load('< ?php echo base_url().'medicine_stock/advance_search/' ?>',
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
    url: "<?php echo base_url(); ?>medicine_stock/advance_search/",
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