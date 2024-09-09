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

   
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('385',$users_data['permission']['action'])) 
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
  $query_string = "";
  if(!empty($_SERVER['QUERY_STRING']))
  {
    $query_string = '?'.$_SERVER['QUERY_STRING'];
  }
  
 ?>
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<form action="<?php echo current_url().$query_string; ?>" method="post">
 <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
<section class="userlist">
  <!-- Left side Contents  -->
    <div class="userlist-box ">
   
 <!-- //////////////// Main Top row 1st /////////////// -->
      <!-- upper all fields -->
      <div class="row">

        <!-- ///////////////////////////// Top Left Side  ///////////////////////////// -->
        <div class="col-md-4">
          <!-- upper left side label and their fields -->
          <div class="row m-b-5">
            <div class="col-md-12">
              <label><input type="radio" <?php if($form_data['user_type']==1){ echo 'checked';} ?> value="1" name="user_type" onclick="select_user_type(1);"> Employee</label> &nbsp;
              <label><input type="radio" <?php if($form_data['user_type']==2){ echo 'checked';} ?> value="2" name="user_type" onclick="select_user_type(2);"> Patient</label>
              <label><input type="radio" <?php if($form_data['user_type']==3){ echo 'checked';} ?> value="3" name="user_type" onclick="select_user_type(3);"> Doctor</label>
            </div>
          </div>



        </div> <!-- 4 -->


        <div id="user_type_list"><!--new div start by mamta from -->
        <!-- ///////////////////////// Top Middle Side  /////////////////////////// -->
        <?php if($form_data['user_type']==2) {?>

          <div class="col-md-4">
                  <!-- upper Middle section label and their fields -->
                
                  <div class="row m-b-5">
                    <div class="col-md-4">
                      <label>Patient Name<span class="star">*</span><span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
                      <select name="user_type_id" class="m_input_default" id="user_type_id"  onchange="appned_user_detail(2,this.value);">
                        <option value="">Select</option>
                          <?php foreach($user_list as $use_list)
                          {?>
                           <option value="<?php echo $use_list->ids; ?>" <?php if(isset($form_data['user_type_id']) && $form_data['user_type_id']==$use_list->ids){ echo 'selected';} ?>><?php echo $use_list->patient_name; ?></option>
                         <?php  } ?>
                      </select>
                    </div>
                  </div>

                </div> <!-- 4 -->
          <?php }?>

           <?php if($form_data['user_type']==3) {?>

          <div class="col-md-4">
                  <!-- upper Middle section label and their fields -->
                
                  <div class="row m-b-5">
                    <div class="col-md-4">
                      <label>Doctor Name<span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
                      <select name="user_type_id" class="m_input_default" id="user_type_id" onchange="appned_user_detail(3,this.value);">
                        <option value="">Select</option>
                         <?php foreach($user_list as $use_list)
                          {?>
                           <option value="<?php echo $use_list->ids; ?>" <?php if(isset($form_data['user_type_id']) && $form_data['user_type_id']==$use_list->ids){ echo 'selected';} ?>><?php echo $use_list->doctor_name; ?></option>
                         <?php  } ?>
                      </select>
                      <?php if(!empty($form_error)){ echo form_error('user_type_id'); } ?>
                    </div>
                  </div>

                </div> <!-- 4 -->
          <?php }?>

        <?php if($form_data['user_type']==1) {?>

          <div class="col-md-4">
                  <!-- upper Middle section label and their fields -->
                
                  <div class="row m-b-5">
                    <div class="col-md-4">
                      <label>Employee Type<span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
                      <select name="employee_type" class="m_input_default" id="employee_type" onchange="employee_list_new(this.value);">
                        <option value="">Select</option>
                        <?php foreach($type_list as $type){ ?>
                           <option value="<?php echo $type->id; ?>" <?php if(isset($form_data['employee_type']) && $form_data['employee_type']==$type->id){ echo 'selected';} ?>><?php echo $type->emp_type; ?></option>
                         <?php  } ?>
                      </select>
                      <?php if(!empty($form_error)){ echo form_error('employee_type'); } ?>
                    </div>
                  </div>

                </div> <!-- 4 -->
                 <!-- //////////////////////// Top Right Side  //////////////////////// -->
          <div class="col-md-4">
            <!-- upper Right side label and their fields -->

            <div class="row m-b-5">
              <div class="col-md-5">
                <label>Employee Name<span class="star">*</span></label>
              </div>
              <div class="col-md-7">
                  <select class="w-150px m_select_btn" name="user_type_id" id="employee_list_n" onchange="appned_user_detail(1,this.value);">
                  <option value="">Select</option>
                  <?php //print_r($employee_list); 
                  foreach($employee_list as $emp_list)
                  {?>
                       <option value="<?php echo $emp_list->id;?>"><?php echo $emp_list->name;?></option>
                  <?php } ?>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('user_type_id'); } ?>
               <!--<a href="javascript:void(0)" class="btn-new" onclick=" return add_employee();">New</a>-->
              </div>
            </div> <!-- innerRow -->

            
          </div> <!-- 4 -->
          <?php }?>



       

        </div><!--new div start by mamta from -->
      </div> <!-- Row -->
      <!-- Upper row ends here -->


   




      <!-- //////////////// Main Top Well row 2nd /////////////// -->
      <!-- upper 2nd all fields  -->
      <div class="row">
        <div class="col-md-12">
        <?php $well='';if((isset($form_data['user_name']) && !empty($form_data['user_name'])) || (isset($form_data['address']) && !empty($form_data['address'])))
            {
              $well="well";
            }
            ?>
          <div id="vendor_data_append" class="<?php echo $well; ?>">
              <!-- upper left side label and their fields -->
            <?php if(isset($form_data['user_name']) && !empty($form_data['user_name']))
            {?>
              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">
                  <?php echo $form_data['user_name'];?>
                  <input type="hidden" class="m_input_default" value=" <?php echo $form_data['user_name'];?>" name="user_name"/>
                </div>
              </div>
            <?php }?>
              <?php if(isset($form_data['address']) && !empty($form_data['address']))
              {?>
              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Address</label>
                </div>
                <div class="col-md-10">
                  <?php echo $form_data['address'];?>
                  <input type="hidden" class="m_input_default" value=" <?php echo $form_data['address'];?>" name="address"/>
                </div>
              </div>
              <?php }?>


        </div> <!-- well -->
          
        </div> <!-- 12 -->
      </div> <!-- Row -->
      <!-- Upper row ends here -->





    <!-- /////////// table 1st /////////// -->
    <div class="row">
      <div class="col-md-11 pr-0">
        <!-- ////////// -->
        <table class="table table-bordered stock_item_issue_upper" style="margin-bottom:0;background: transparent;">
          <tbody>
            <tr>
              <td>Return Code</td>
             <td colspan="8"><?php echo $form_data['return_code'];?>
                <input type="hidden" value="<?php echo $form_data['return_code'] ?>" name="return_code"/>
                </td>
            </tr>
            <tr>
              <td>Item Name</td>
              <td><input type="text" name="item_name" class="item_name m_input_default txt_firstCap" value="" id="">
              <input type="hidden" value="" id="item_id"/>
              <input type="hidden" value="" id="category_id"/>
              <td>Issue Date  </td>
              <td colspan="8"><input type="text" name="issue_date" class="issue_date purchasedatepicker m_input_default" value="<?php echo $form_data['issue_date'] ?>"/></td>
            </tr>
            <tr>
              <td>Item Code</td>
              <td><input type="text" class="m_input_default" name="item_code" id="item_code" value="" readonly></td>
              <td>Qty.</td>
              <td><input type="text" class="w-100px " value="0" name="quantity" onblur="payemt_vals();" id="quantity"></td>
              <td><b>Unit</b></td>
              <td><input type="text" class="w-100px" value="0" id="unit" name="unit"></td>
              <td><b>Price</b></td>
              <td><input type="text" class="w-100px" value="0" id="price" name="price" onblur="payemt_vals();"></td>
              <td><b>Amount</b></td>
              <td><input type="text" class="w-100px" value="0" name="amount" id="amount"></td>
            </tr>
          </tbody>
        </table>
      <span class="text-danger" id="error_message"></span>
        <!-- /////////// table 2nd /////////// -->
        <table class="table table-bordered table-striped purchase_stock_item_list" id="item_list">
          <thead class="bg-theme">
            <tr>
              <th><input type="checkbox" name=""></th>
                <th>S.No.</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Unit </th>
                <th>Price</th>
                <th>Total Price</th>

            </tr>
          </thead>
           <tbody>
                  <?php //print '<pre>';print_r($stock_issue_allotment_return_item_list) ;
                  if(isset($stock_issue_allotment_return_item_list) && !empty($stock_issue_allotment_return_item_list))
                  {
                    $i=1;foreach($stock_issue_allotment_return_item_list as $stock_p_list)
                  {
                    ?>
                    <tr>
                      <td><input type="checkbox" name="item_id[]" value="<?php echo $stock_p_list['item_id']; ?>"  class="booked_checkbox"></td>
                      <td><?php echo $i;?><input type="hidden" value="<?php echo $stock_p_list['item_id'];?>" id="item_id_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><?php echo $stock_p_list['item_code'];?><input type="hidden" value="<?php echo $stock_p_list['item_code'];?>" id="item_code_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><?php echo $stock_p_list['item_name'];?><input type="hidden" value="<?php echo $stock_p_list['item_name'];?>" id="item_name_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><input type="text" value="<?php echo $stock_p_list['quantity'];?>" name="quantity" id="quantity_<?php echo $stock_p_list['item_id'];?>" onkeyup="payment_cal_perrow('<?php echo $stock_p_list['item_id'];?>');"/></td>
                     <td><?php echo $stock_p_list['unit'];?><input type="hidden" value="<?php echo $stock_p_list['unit'];?>" id="unit_<?php echo $stock_p_list['item_id'];?>"/></td>
                      <td><?php echo $stock_p_list['item_price'];?><input type="hidden" value="<?php echo $stock_p_list['item_price'];?>" id="item_price_<?php echo $stock_p_list['item_id'];?>"/><input type="hidden" value="<?php echo $stock_p_list['category_id'];?>" id="category_id_<?php echo $stock_p_list['item_id'];?>"/></td>

                      <td><input type="text" value="<?php echo $stock_p_list['total_price'];?>" id="total_price_<?php echo $stock_p_list['item_id'];?>"/></td>
                    </tr>
                    <?php $i++ ;
                  } 
                } 
                else
                {?>
                <tr>
                <td colspan="8">
                No Records Found
                </td>
                </tr>
                <?php }
                    ?>

              <?php  if(!empty($form_error)){ ?>    
              <tr>
              <td colspan="8"><?php  echo form_error('item_id');  ?></td>
              </tr>
              <?php } ?>
                  </tbody>
        </table>

      <!-- /////////// table 3rd /////////// -->
        <table class="table stock_item_issue_lower">
          <tbody class="no-bg">
              <tr class="no-bg">
                 <td><label>Total Amount:</label></td>
                <td><input type="text" class="w-100px"   class="price_float" name="total_amount" id="total_amount" value="<?php if(!empty($stock_item_payment_payment_array['total_amount'])) { echo number_format($stock_item_payment_payment_array['total_amount'],2,'.',''); } else{ echo $form_data['total_amount']; } ?>"></td>
               
                <!-- <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
                if(isset($discount_vals) && $discount_vals==1){?>

                <td><label>Discount.</label></td>
                <td><input class="input-tiny price_float" type="text" value="<?php echo $form_data['discount_percent'];?>" id="discount_all" onkeyup="payemt_get_balance();" placeholder="%" name="discount_percent">
                <input type="text" class="w-100px"   name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" onkeyup=payemt_get_balance();></td>

                <?php } else{?>
                <td><label>Discount.</label></td>
                <td> <input class="input-tiny price_float" type="hidden" value="0" id="discount_all" onkeyup="payemt_get_balance();" placeholder="%" name="discount_percent">
                <input type="text" class="w-100px"   name="discount_amount"  id="discount_amount" value="0" onkeyup=payemt_get_balance();></td>
                <?php }?>
                  
                 <td><label>Net Amount</label></td>
                <td><input type="text" name="net_amount"  id="net_amount" onKeyUp="payemt_get_balance();" readonly value="<?php echo $form_data['net_amount'];?>"></td>

                <td><label>Paid Amount</label></td>
                <td> <input type="text" name="pay_amount" <?php if(isset($form_data['ipd_id']) && $form_data['ipd_id']!='') { echo 'readonly'; }?>  id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_get_balance(1);"></td>
                  <?php  
                  $balance=$form_data['net_amount']-$form_data['pay_amount'];
                  ?>
              
                <td><label>Balance</label></td>
                <td><input type="text" class="w-100px" name="balance_due"  id="balance_due" value="<?php if(!empty($balance) && $balance>0){ echo $balance; }else{ echo '0.00' ;} ?>" class="price_float" readonly></td> -->
             
              </tr>
            </tbody>
        </table>


      </div> <!-- 11 -->
      <div class="col-md-1 pl-0">
        <!-- /////////////// -->
          <div class="addDelete mt-1em">
             <a class="btn-custom" onclick="item_payment_calculation();">Add</a>
              <a class="btn-custom" onclick="child_medicine_vals();">Delete</a>
          </div>
      </div> <!-- 1 -->
    </div> <!-- Row -->
      
  
    
   </div> <!-- close -->
   <!-- Ends Left all content section -->





    <!-- Right side buttons  -->
    <div class="userlist-right relative">
      <div class="fixed">
        <div class="btns">
         <button class="btn-save" id="purchase_submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
         <a href="<?php echo base_url('stock_issue_allotment_return'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
        </div>
      </div>
    </div> 
    <!-- Ends Right all Button section -->


  
</section> <!-- cbranch -->
</form>
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


$('.purchasedatepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  });
</script>
<!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->
<script>

$(document).ready(function(){
<?php if(!empty($form_data['employee_type']))
{
  ?>
employee_list_new('<?php echo $form_data['employee_type'];?>');
<?php } ?>

})
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

function select_user_type(user_type)
{
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('stock_issue_allotment_return/usertype_list');?>",
            dataType: "text",
            data: {user_type: user_type},
            success: function(result) 
            {  
               $('#user_type_list').html(result); 
           
            }
        });
}
function employee_list_new(employe_id)
{
  $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_issue_allotment_return/employee_list');?>",
              dataType: "text",
              data: {employee_type: employe_id,user_type_id:'<?php echo $form_data['user_type_id'] ?>'},
              success: function(result) 
              {  
                 $('#employee_list_n').html(result); 
             
              }
          });
}
function appned_user_detail(user_type,emp_id)
{
  $.ajax({
          url: "<?php echo base_url('stock_issue_allotment_return/get_emp_data/'); ?>"+emp_id+'/'+user_type, 
          success: function(result)
          {
            $('#vendor_data_append').addClass('well');
            $('#vendor_data_append').html(result);
          }
    });
}
function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('stock_issue_allotment_return/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
  
  }
  function toggle(source) 
  {  
   
     checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function payemt_vals()
  {
      var timerA = setInterval(function(){  
      get_total_amount(quantity);
      clearInterval(timerA); 
      }, 80);
  }
  function get_total_amount(){

  var amount= $('#price').val();
  var quantity= $('#quantity').val();
  var total_amount= amount *quantity;
  $('#amount').val(total_amount);

}
  <?php
  $flash_success = $this->session->flashdata('success');
  if(isset($flash_success) && !empty($flash_success))
  {
   echo 'flash_session_msg("'.$flash_success.'");';
  }
  ?>

     var i=1;
     var getData = function (request, response) { 

        row = i ;
        $.ajax({
        url : "<?php echo base_url('stock_issue_allotment_return/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

          $('.item_name').val(names[0]);
          $('#item_code').val(names[1]);
          $('#unit').val(names[2]);
          $('#price').val(names[3]);
          $('#item_id').val(names[4]);
          $('#category_id').val(names[5]);
         
          //$('#medicine_id').val(names[4]);

        return false;
    }

    $(".item_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
  
    


  $(document).ready(function(){
   payment_new_calc_all()
  });
  function item_payment_calculation()
  {
    var item_name = $('.item_name').val();
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var item_code = $('#item_code').val();
    var item_id = $('#item_id').val();
    var category_id = $('#category_id').val();
    var unit = $('#unit').val();
    var item_price= $('#price').val();
    var total_amount = $('#total_amount').val();
    var item_code = $('#item_code').val();

    if(item_name!='')
    {
     $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>stock_issue_allotment_return/item_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&item_id='+item_id+'&item_name='+item_name+'&total_amount='+total_amount+'&item_price='+item_price+'&item_code='+item_code+'&unit='+unit+'&category_id='+category_id+'&total_price='+amount,
            success: function(result)
            {
               if(result.error==1)
               {
                   $('#error_message').html(result.message);
               }
               else
               {
                $('#item_list').html(result.html_data);
                 $('#total_amount').val(result.total_amount);
                  $('#pay_amount').val(result.paid_amount);
                  $('#net_amount').val(result.net_amount);
                   $('#total_amount').val(result.total_amount);   
                $('#price').val('');
                $('#amount').val('');
                $('#quantity').val('1');  
               }
             
            } 
          });
      }
   
  }
  function payemt_get_balance(pay)
  {
    var timerA = setInterval(function(){  
    payment_new_calc_all(pay);
    clearInterval(timerA); 
    }, 80);
  }

  function payment_new_calc_all(update_row)
  {
        var discount = $('#discount_all').val();
        var discount_a= $('#discount_amount').val();
        var data_id= '<?php echo $form_data['data_id'];?>';
        var net_amount = $('#net_amount').val();
        var total_amount = $('#total_amount').val();
        var pay_amount = $('#pay_amount').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>stock_issue_allotment_return/payment_calc_all/", 
        dataType: "json",
        data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+update_row+'&discount_amount='+discount_a+'&data_id='+data_id+'&total_amount='+total_amount,
            success: function(result)
            {
                $('#discount_all').val(result.discount);
                $('#discount_amount').val(result.discount_amount);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount);
                $('#pay_amount').val(result.pay_amount);
                $('#balance_due').val(result.balance_due);
            } 
        });
  }

  function payment_cal_perrow(ids){
   
    var item_code = $('#item_code_'+ids).val();
    var item_id = $('#item_id_'+ids).val();
    var category_id = $('#category_id_'+ids).val();
    var item_name = $('#item_name_'+ids).val();
    var quantity= $('#quantity_'+ids).val();
    var unit= $('#unit_'+ids).val();
    var item_price= $('#item_price_'+ids).val();
     var total_price= $('#total_price_'+ids).val();
    
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>stock_issue_allotment_return/payment_cal_perrow/", 
            dataType: "json",
            data: 'item_code='+item_code+'&item_name='+item_name+'&quantity='+quantity+'&unit='+unit+'&item_price='+item_price+'&item_id='+item_id+'&category_id='+category_id+'&total_price='+total_price,
            success: function(result)
            {
              $('#total_price_'+ids).val(result.total_new_price);
               payment_new_calc_all();
            } 
          });
 }

function remove_item(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_issue_allotment_return/remove_stock_purchase_item_list');?>",
               dataType: "json",
              data: {item_id: allVals},
              success: function(result) 
              {  
              $('#item_list').html(result.html_data); 
                payment_new_calc_all();
                $('#discount_amount').val('');
                $('#total_amount').val('');
                $('#net_amount').val('');
                $('#discount_all').val('');
               $('#balance_due').val('');
                $('#pay_amount').val('');

              }
          });
   }
  }

  function child_medicine_vals() 
  {  
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
              
              //barcode.push($('#bar_code_'+this).val());
         } 
       });

       if(allVals!="")
        {
       remove_item(allVals);
       }
  }  
  function get_employees()
    {
       var emp_type_id = '<?php echo $this->session->userdata('emp_type_id');?>';
      
       if(emp_type_id!="" && emp_type_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>users/type_to_employee/"+emp_type_id, 
              success: function(result)
              {
                $('#employee_list_n').html(result); 
              } 
            });
       } 
    }
  function add_employee()
  {

      var $modalemp = $('#load_add_emp_modal_popup');
      $modalemp.load('<?php echo base_url().'employee/add/' ?>',
      {
      //'id1': '1',
      //'id2': '2'
      },
      function(){
      $modalemp.modal('show');
      });
  }
$(".txt_firstCap").on('keyup', function(){

   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCap').val(part_val.join(" "));
  
  });
  </script>
<div id="load_add_emp_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- container-fluid -->
</body>
</html>

<script>
  $('input[id=purchase_submit]').click(function(){
    $(this).attr('disabled', 'disabled');
});
</script>

