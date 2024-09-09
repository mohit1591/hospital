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
if(in_array('1081',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('vaccine_purchase_return/ajax_list')?>",
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
                      url: "<?php echo base_url('vaccine_purchase_return/total_calc_return');?>",
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



function edit_purchase_return(id)
{
  
  window.location.href='<?php echo base_url().'vaccine_purchase_return/edit/';?>'+id
  
  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'vaccine_purchase_return/view/' ?>'+id,
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
                      url: "<?php echo base_url('vaccine_purchase_return/deleteall');?>",
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
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
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
    <div class="userlist-box">
          <?php if(in_array('1081',$users_data['permission']['action'])) {
       ?>
 <form id="new_search_form">
    
  

      <table class="ptl_tbl">
        <tr>
          <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date_p" class="datepicker "  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'];?>"></td>
          <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date_p" class="datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['end_date'];?>"></td>
          <td>
             <input value="Reset" class="btn-custom" onclick="reset_search(this.form)" type="button">
          <a href="javascript:void(0)" class="btn-custom" id="adv_search_purchase"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> 

      

          </td>
        </tr>
        <tr>
          <td><label>Purchase No.</label>
            <input type="text" name="purchase_no" id="purchase_no" value="<?php echo $form_data['purchase_no'];?>" class=""  onkeyup="return form_submit();" autofocus=""></td>
          <td><label>Invoice No.</label>
            <input type="text" name="invoice_id" id="invoice_id" value="<?php echo $form_data['invoice_id'];?>" class=""  onkeyup="return form_submit();"></td>
          <td></td>
        </tr>
        <tr>
          <td><label>Paid Amount</label>
            <input type="text" name="paid_amount_to" id="paid_amount_to" value="<?php echo $form_data['paid_amount_to'];?>" class="w-90px"  onkeyup="return form_submit();"> To
            <input type="text" name="paid_amount_from" id="paid_amount_from" value="<?php echo $form_data['paid_amount_from'];?>" class="w-90px"  onkeyup="return form_submit();"></td>
            <td>
            <?php  
              $users_data = $this->session->userdata('auth_users'); 

              if (array_key_exists("permission",$users_data)){
              $permission_section = $users_data['permission']['section'];
              $permission_action = $users_data['permission']['action'];
              }
              else{
              $permission_section = array();
              $permission_action = array();
              }
              //print_r($permission_action);

              $new_branch_data=array();
              $users_data = $this->session->userdata('auth_users');
              $sub_branch_details = $this->session->userdata('sub_branches_data');
              $parent_branch_details = $this->session->userdata('parent_branches_data');


              if(!empty($users_data['parent_id'])){
              $new_branch_data['id']=$users_data['parent_id'];

              $users_new_data[]=$new_branch_data;
              $merg_branch= array_merge($users_new_data,$sub_branch_details);

              $ids = array_column($merg_branch, 'id'); 
              $branch_id = implode(',', $ids); 
              $option= '<option value="'.$branch_id.'">All</option>';
              }

              ?>
              <?php if(in_array('1',$permission_section)): ?> 
              <label>Select Branch</label>
              <select name="branch_id" id="branch_id" onchange="return form_submit();">
              <?php echo $option ;?>
              <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
              <?php 
              if(!empty($sub_branch_details)){
              $i=0;
              foreach($sub_branch_details as $key=>$value){
              ?>
              <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
              <?php 
              $i = $i+1;
              }

              }
              ?> 
            </select>
            <?php endif;?></td>
          <td></td>
        </tr>
      </table>


      
   
    </form>
    <?php }?>
    <form>
       <?php if(in_array('1081',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered purchase_return_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Purchase No. </th> 
                    <th> Return No. </th> 
                    <th> Invoice No. </th> 
                    <th> Vendor Name </th> 
                   <!-- <th> Total Amount</th> -->
                    <th> Net Amount </th> 
                    <th>Paid Amount</th> 
                    <th>Balance</th> 
                    <th> Return Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

    </form>


   </div> <!-- close -->





  	<div class="userlist-right ">
  		<div class="btns">
               <?php if(in_array('1082',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('vaccine_purchase_return/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               <?php if(in_array('1084',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
             <?php }?>

              <?php if(in_array('1081',$users_data['permission']['action'])) {
               ?>

              <a href="<?php echo base_url('vaccine_purchase_return/vaccine_purchase_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>

                <a href="<?php echo base_url('vaccine_purchase_return/vaccine_purchase_csv'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-word-o"></i> CSV
                </a>

                <a href="<?php echo base_url('vaccine_purchase_return/pdf_vaccine_purchase'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-pdf-o"></i> PDF
                </a>

                <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintnewWindow('< ?php echo base_url("vaccine_purchase_return/print_medicine_purchase"); ?>', 'windowTitle', 'width=820,height=600');">
                <i class="fa fa-print"></i> Print
                </a> -->

                <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("vaccine_purchase_return/print_medicine_purchase"); ?>');"> <i class="fa fa-print"></i> Print</a>

               <?php } ?>
               <?php if(in_array('1081',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('1087',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-exit" onclick="window.location.href='<?php echo base_url('vaccine_purchase_return/archive'); ?>'">
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
  <!-- cbranch-rslt close -->
   <div class="sale_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Net Amount</label>
                    <input type="text" id="total_net_amount" value="0.00"  readonly="" />
                </div>
                <div class="sale_medicine_mod_of_payment">
                 <label>Total Paid Amount</label>
                  <input type="text" id="total_paid_amount" value="0.00"  readonly="" />
                </div>
               <div class="sale_medicine_mod_of_payment">
                    <label>Total Balance</label>
                    <input type="text" id="total_balance" value="0.00"  readonly="" />
                </div>
               

            </div> <!-- right_box -->

            
        </div> <!-- left -->
      
    </div> <!-- sale_medicine_bottom -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script> 
$(document).ready(function(){
  reload_table();
  $('#selectAll').on('click', function () {
 // alert(); 
    if ($("#selectAll").hasClass('allChecked')) {

    $('.checklist').prop('checked', false);
    } else {
    $('.checklist').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
  });
});

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>vaccine_purchase_return/reset_search/", 
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
    $.ajax({url: "<?php echo base_url(); ?>vaccine_purchase_return?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  } 
 function delete_purchase_return(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('vaccine_purchase_return/delete/'); ?>"+id, 
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
<!-- Confirmation Box -->

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('vaccine_purchase_return');?>'; 
    }) ;
   
       
  <?php }?>
 });

/*function openPrintWindow(url, name, specs) {
 if(url==123){
    url='< ?php echo base_url("vaccine_purchase_return/print_purchase_return_recipt"); ?>/'+name;
    name='windowTitle';
    specs='width=820,height=600';
     var printWindow = window.open(url, name, specs);
  }else{
     var printWindow = window.open(url, name, specs);
  }
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
            window.location.href='< ?php echo base_url('vaccine_purchase_return');?>';
           // alert('< ?php echo $this->session->userdata('sales_id');?>');
        }
    }
    var sched = setInterval(printAndClose, 200);
};



 function openPrintnewWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};*/

$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
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
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("vaccine_purchase_return/print_purchase_return_recipt"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("vaccine_purchase_return/print_purchase_return_recipt"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->
    
    <div id="confirm-select" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Please select at-least one record.</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal --> 


<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_purchase').on('click', function(){
$modal.load('<?php echo base_url().'vaccine_purchase_return/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/vaccine_purchase_return/advance_search/",
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
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_p").datepicker("option", "maxDate", selected);
      }
    })


</script>
</div><!----container-fluid--->
</body>
</html>