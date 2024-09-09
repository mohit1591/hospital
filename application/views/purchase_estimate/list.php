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
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>

<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
function edit_purchase(id)
{
  window.location.href='<?php echo base_url().'purchase_estimate/edit/';?>'+id
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'purchase_estimate/view/' ?>'+id,
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
                      url: "<?php echo base_url('purchase_estimate/deleteall');?>",
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
     <form id="new_search_form">
    
    <table class="ptl_tbl">
      <tr>
        <td><label>From Date</label> <input type="text" name="start_date" id="start_date_p" class="datepicker start_datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'] ?>"></td>
        <td><label>To Date</label> <input type="text" name="end_date" id="end_date_p" class="datepicker end_datepicker"  onkeyup="return form_submit();" value="<?php echo $form_data['end_date'] ?>" ></td>
        <td><a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a> <a href="javascript:void(0)" class="btn-a-search" id="adv_search_purchase"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> </td>
      </tr>
      <tr>
        <td><label>Estimate No.</label><input type="text" id="purchase_no" value="<?php echo $form_data['purchase_no'] ?>" name="purchase_no" class=" "  onkeyup="return form_submit();" autofocus=""></td>
        <td><label>Invoice No.</label><input type="text" id="invoice_id" value="<?php echo $form_data['invoice_id'] ?>" name="invoice_id" class=" "  onkeyup="return form_submit();"></td>
      </tr>
      <tr>
        <td><label>Paid Amount</label>
            <input type="text" name="paid_amount_to" id="paid_amount_to" value="<?php echo $form_data['paid_amount_to'] ?>" class="w-90px"  onkeyup="return form_submit();"> To
            <input type="text" name="paid_amount_from" id="paid_amount_from" value="<?php echo $form_data['paid_amount_from'] ?>" class="w-90px"  onkeyup="return form_submit();"></td>
            <td><label>Balance</label>
            <input type="text" name="balance_to" id="balance_to" class="w-90px"  value="<?php echo $form_data['balance_to'] ?>" onkeyup="return form_submit();"> To
            <input type="text" name="balance_from" id="balance_from" class="w-90px" value="<?php echo $form_data['balance_from'] ?>" onkeyup="return form_submit();"></td>
      </tr>
      <tr><td>  <?php  
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
          <?php endif;?></td></tr>
    </table>


    </form>

    <form>
       <?php if(in_array('385',$users_data['permission']['action'])) { ?>

       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered purchase_list" cellspacing="0" width="100%">

            <thead class="bg-theme">

                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Estimate No. </th> 
                    <th> Invoice No. </th> 
                    <th> Vendor Name </th> 
                    <th> Net Amount </th> 
                    <th> Paid Amount</th> 
                    <th> Balance</th> 
                    <th> Estimate Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

    </form>


   </div> <!-- close -->

  	<div class="userlist-right">
  		<div class="btns">

               <?php if(in_array('386',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('purchase_estimate/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               <?php if(in_array('388',$users_data['permission']['action'])) {
               ?>

                 <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("purchase_estimate/print_medicine_purchase"); ?>');"> <i class="fa fa-print"></i> Print</a>

  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
               <?php if(in_array('385',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
              
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 
  	<!-- right -->
 
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
    $.ajax({url: "<?php echo base_url(); ?>purchase_estimate/reset_search/", 
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
                 url: "<?php echo base_url('purchase_estimate/delete/'); ?>"+id, 
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
        window.location.href='<?php echo base_url('purchase_estimate');?>'; 
    }) ;
   
       
  <?php }?>
 });

 
$(document).ready(function(){
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $(this).find('.inputFocus').focus();
  });
});

</script>

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

<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintnewWindow('< ?php echo base_url("purchase_estimate/print_purchase_recipt"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("purchase_estimate/print_purchase_recipt"); ?>');">Print</a>

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

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_purchase').on('click', function(){
$modal.load('<?php echo base_url().'purchase_estimate/advance_search/' ?>',
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
    url: "<?php echo base_url(); ?>/purchase_estimate/advance_search/",
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
  
  function form_submit(vals)
{
    var start_date = $('#start_date_p').val();
    var end_date = $('#end_date_p').val();
    var branch_id = $('#branch_id').val();
    var purchase_no = $('#purchase_no').val();
    
    var paid_amount_from = $('#paid_amount_from').val();
    var paid_amount_to = $('#paid_amount_to').val();
    var balance_to = $('#balance_to').val();
    var balance_from = $('#balance_from').val();
    var invoice_id = $('#invoice_id').val();
    
  $.ajax({
       url: "<?php echo base_url(); ?>purchase_estimate/advance_search/",
       type: 'POST',
       data: { start_date: start_date, end_date : end_date,branch_id:branch_id,purchase_no:purchase_no,paid_amount_from:paid_amount_from,paid_amount_to:paid_amount_to,balance_to:balance_to,balance_from:balance_from,invoice_id:invoice_id} ,
         
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
            "url": "<?php echo base_url('purchase_estimate/ajax_list')?>",
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
                      url: "<?php echo base_url('purchase_estimate/total_calc_return');?>",
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
    //form_submit();

}); 
<?php } ?>
  </script>

<!-- container-fluid -->
</body>
</html>
