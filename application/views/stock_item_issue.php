<?php
  // print_r($this->session->userdata('net_values_all'));
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

    <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('385',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('purchase/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],
        "drawCallback": function() 
        {
            $.ajax({
                      dataType: "json",
                      url: "<?php echo base_url('purchase/total_calc_return');?>",
                      success: function(result) 
                      {
                        $('#total_net_amount').val(result.net_amount);
                        $('#total_discount').val(result.discount);
                        $('#total_balance').val(result.balance);
                        $('#total_vat').val(result.vat);
                        $('#total_paid_amount').val(result.paid_amount);
                      }
                  });
        },

    });
    form_submit();

}); 
<?php } ?>



function edit_purchase(id)
{
  
  window.location.href='<?php echo base_url().'purchase/edit/';?>'+id
  
  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'purchase/view/' ?>'+id,
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
                      url: "<?php echo base_url('purchase/deleteall');?>",
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
  <!-- Left side Contents  -->
    <div class="userlist-box media_tbl_full">
     <form >
      <!-- upper all fields -->
      <div class="row">
        <div class="col-md-4">
          <!-- upper left side label and their fields -->
          <div class="row m-b-5">
            <div class="col-md-12">
              <label><input type="radio"> Employee</label> &nbsp;
              <label><input type="radio"> Patient</label>
            </div>
          </div>



        </div> <!-- 4 -->
        <div class="col-md-4">
          <!-- upper Middle section label and their fields -->
        
          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Employee Type</label>
            </div>
            <div class="col-md-8">
              <select name="" id="">
                <option value="">Select</option>
              </select>
            </div>
          </div>

        </div> <!-- 4 -->
        <div class="col-md-4">
          <!-- upper Right side label and their fields -->

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Employee Name</label>
            </div>
            <div class="col-md-8">
              <select class="w-189px" name="" id="">
                <option value="">Select</option>
              </select>
              <a href="" class="btn-new">New</a>
            </div>
          </div>
          
        </div> <!-- 4 -->
      </div> <!-- Row -->
      <!-- Upper row ends here -->


      <!-- upper 2nd all fields  -->
      <div class="row">
        <div class="col-md-12">
          <div class="well">
              <!-- upper left side label and their fields -->
              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Emp. Reg. No.</label>
                </div>
                <div class="col-md-10">
                  EMP0001
                </div>
              </div>

              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Employee Name</label>
                </div>
                <div class="col-md-10">
                  Reception
                </div>
              </div>

              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Emp. Qualification</label>
                </div>
                <div class="col-md-10">
                  asdfsadf
                </div>
              </div>

        </div> <!-- well -->
          
        </div> <!-- 12 -->
      </div> <!-- Row -->
      <!-- Upper row ends here -->




    <div class="row">
      <div class="col-md-12" style="padding-right:24px">
        <table class="table table-bordered stock_item_issue_upper" style="margin-bottom:0;background: transparent;">
          <tbody>
            <tr>
              <td>Issue Code</td>
              <td colspan="8">2017/10/0001</td>
            </tr>
            <tr>
              <td>Item Name</td>
              <td><input type="text">  </td>
              <td>Issue Date  </td>
              <td colspan="8">2017/10/0001</td>
            </tr>
            <tr>
              <td>Item Code</td>
              <td><input type="text" class="" value="0"></td>
              <td>Qty.</td>
              <td><input type="text" class="w-100px" value="0"></td>
              <td><input type="text" class="w-100px" value="0"></td>
              <td>Price</td>
              <td><input type="text" class="w-100px" value="0"></td>
              <td>Amount</td>
              <td><input type="text" class="w-100px" value="0"></td>
            </tr>
          </tbody>
        </table>


        <table class="table table-bordered table-striped purchase_stock_item_list">
          <thead class="bg-theme">
            <tr>
              <th><input type="checkbox" name=""></th>
              <th>S.No.</th>
              <th>Item Code</th>
              <th>Item Name</th>
              <th>Qty</th>
              <th>Unit </th>
              <th>Price</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><input type="checkbox" name=""></td>
              <td>1</td>
              <td>STK005</td>
              <td>Spirit</td>
              <td>2</td>
              <td>Pcs</td>
              <td>80</td>
              <td>800</td>
            </tr>
          </tbody>
        </table>


        <table class="table stock_item_issue_lower">
          <tbody class="">
            <tr class="">
              <td><label>Total Amount:</label></td>
              <td><input type="text" class="" value="800"></td>
            </tr>
          </tbody>
        </table>


      </div>
    </div>
      
  
    </form>
   </div> <!-- close -->
   <!-- Ends Left all content section -->





    <!-- Right side buttons  -->
  	<div class="userlist-right relative">
      <div class="fixed">
    		<div class="btns">
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-plus"></i> New </button>
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-floppy-o"></i> Save </button>
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-pencil"></i> Edit </button>
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-trash"></i> Delete </button>
          <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
            <i class="fa fa-sign-out"></i> Exit </button>
    		</div>
      </div>
  	</div> 
  	<!-- Ends Right all Button section -->


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script> 
function reset_search()
  { 
    $('#start_date_p').val('');
    $('#end_date_p').val('');
    $('#paid_amount_from').val('');
    $('#paid_amount_to').val('');
    $('#balance_to').val('');
    $('#balance_from').val('');
    $('#purchase_no').val('');
    $('#invoice_id').val('');
    $('#branch_id').val('');
    $.ajax({url: "<?php echo base_url(); ?>purchase?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }

 function delete_purchase(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('purchase/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
</script> 

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('purchase');?>'; 
    }) ;
   
       
  <?php }?>
 });

 /*function openPrintnewWindow(url, name, specs) {
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

function openPrintWindow(url, name, specs,id) {

    if(url==123){
          url12='< ?php echo base_url("purchase/print_purchase_recipt"); ?>/'+name;
          //alert(url12);
          name_win='windowTitle';
          specs='width=820,height=600';
          var printWindow = window.open(url12,name_win,specs);

          var printAndClose = function() {
          if (printWindow.document.readyState =='complete') {
          clearInterval(sched);
          printWindow.print();
          printWindow.close();
          window.location.href='< ?php echo base_url('purchase');?>';
          // alert('< ?php echo $this->session->userdata('sales_id');?>');
          }
          }
          var sched = setInterval(printAndClose, 200);
    }

   
};*/
$(document).ready(function(){
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $(this).find('.inputFocus').focus();
  });
});

</script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintnewWindow('< ?php echo base_url("purchase/print_purchase_recipt"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("purchase/print_purchase_recipt"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

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
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_purchase').on('click', function(){
$modal.load('<?php echo base_url().'purchase/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});



</script>

<script>
function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/purchase/advance_search/",
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

  var today =new Date();
    $('#start_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_p").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      //alert(selected);
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_p").datepicker("option", "maxDate", selected);
      }
    })


/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/

  </script>

<!-- container-fluid -->
</body>
</html>
