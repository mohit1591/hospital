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
   $query_string = "";
  if(!empty($_SERVER['QUERY_STRING']))
  {
    $query_string = '?'.$_SERVER['QUERY_STRING'];
  }
  
 ?>
<!-- ============================= Main content start here ===================================== -->
 <form action="<?php echo current_url().$query_string; ?>" method="post">
 <input type="hidden" name="data_id" value="<?php echo $form_data['data_id'] ?>"/>
 <section class="userlist">
  <!-- Left side Contents  -->
    <div class="userlist-box media_tbl_full">
    
      <!-- upper all fields -->
      <div class="row">
        <div class="col-md-4">
          <!-- upper left side label and their fields -->
          <div class="row m-b-5">
          <input type="hidden" class="m_input_default" value="<?php echo $form_data['data_id'] ?>" name="data_id" />
            <div class="col-md-4">
              <label>Return Code</label>
            </div>
            <div class="col-md-8">
            <input type="hidden" class="m_input_default" name="return_code" value="<?php echo $form_data['return_code'];?>"/>
              <strong><?php echo $form_data['return_code'];?></strong>
            </div>
          </div>
          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Purchase Code</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="purchase_code" placeholder="Purchase Code" class="m_input_default" value="<?php echo $form_data['purchase_code'] ?>">
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Purchase Date</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="purchase_date" class="purchasedatepicker m_input_default" value="<?php echo $form_data['purchase_date'] ?>">
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Select Vendor<span class="star">*</span></label>
            </div>
            <div class="col-md-8">
              <select class="w-150px m_select_btn" name="vendor_id" id="vendor_id" onchange="select_vendor_data(this.value)">
                  <option value="">Select</option>
                  <?php foreach($vendor_list as $vendorl){?>
                   <option value="<?php echo $vendorl->id;?>" <?php if(isset($form_data['vendor_id']) && $form_data['vendor_id']==$vendorl->id){ echo 'selected';}?>><?php echo $vendorl->name;?></option>
                  <?php }?>
              </select>
              <a href="javascript:void(0)" class="btn-new" onclick=" return add_vendor(3);">New</a>
               <div class="">
            <?php if(!empty($form_error)){ echo form_error('vendor_id'); } ?>
            </div>
            </div>
           
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Item Name</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="item_name" placeholder="Enter Item Name" class="item_name m_input_default txt_firstCap" value="">

              <input type="hidden" class="m_input_default" value="" id="item_id"/>
               <input type="hidden" class="m_input_default" value="" id="category_id"/>
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Item Code</label>
            </div>
            <div class="col-md-8">
              <input type="text" class="m_input_default" placeholder="Item Code" name="item_code" id="item_code" value="" readonly>
            </div>
          </div>



        </div> <!-- 4 -->
       
        <div class="col-md-4">
            <?php $well='';if(isset($form_data['vendor_code']) || isset($form_data['vendor_name']) || isset($form_data['address']))
            {
              $well='class="well"';
            }
            ?>
            <!-- upper Middle section label and their fields -->
        <div <?php echo $well; ?> id="vendor_data_append">
        <?php if(isset($form_data['vendor_code']))
        {?>
          <div class="row m-b-5"> 
          <div class="col-md-4"><label>Code</label>
          </div>
          <div class="col-md-8">
           <?php echo $form_data['vendor_code'];?>
          </div>
          </div> 
        <?php }?>
        <?php if(isset($form_data['vendor_name']))
        {?>
          <div class="row m-b-5"> 
          <div class="col-md-4">  
          <label>Name</label>
          </div> 
          <div class="col-md-8"> <?php echo $form_data['vendor_name'];?>
          </div>
          </div>
        <?php }?>
         <?php if(isset($form_data['address']))
        {?>
            <div class="row m-b-5">
            <div class="col-md-4">
            <label>Address</label>
            </div>
            <div class="col-md-8"> <?php echo $form_data['address']; ?> </div>
            </div>

          <?php }?>
        </div> <!-- well -->

        </div> <!-- 4 -->
     


        <div class="col-md-4">
          <!-- upper Right side label and their fields -->

          <div class="row m-b-5">
            <div class="col-md-5">
              <label>Mode of Payment</label>
            </div>
            <div class="col-md-7">
             <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
            </div>
          </div>

            <div id="updated_payment_detail">
            <?php if(!empty($form_data['field_name']))
            { foreach ($form_data['field_name'] as $field_names) {

            $tot_values= explode('_',$field_names);

            ?>

            <div class="row m-b-5"><div class="col-md-5"><label><?php echo $tot_values[1];?><span class="star">*</span></label></div>
            <div class="col-md-7"><input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" onkeypress="payment_calc_all();">
            <input type="hidden" name="field_id[]" value="<?php echo $tot_values[2];?>" onkeypress="payment_calc_all();">
            <div class="f_right">
            <?php 
            if(empty($tot_values[0]))
            {
            if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
            }
            ?>
            </div>
            </div>
            </div>

            <?php } }?>

        </div>

        <div id="payment_detail">


        </div>

          
      </div> <!-- Row -->
      <!-- Upper row ends here -->




    <div class="row">
      <div class="col-md-12" style="padding-right:24px">
      
        <table class="table table-bordered purchase_stock_item_list_upper" style="margin-bottom:0;background: transparent;">
          <tbody class="no-bg">
            <tr class="no-bg">
              <td><b>Qty.</b></td>
              <td><input type="text" class="w-100px" value="0" name="quantity" onblur="payemt_vals();" id="quantity"></td>
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
        <div class="row">
          <div class="col-md-11 pr-0">
              <!-- ///////// -->
              <table class="table table-bordered table-striped purchase_stock_item_list" id="item_list">
                <thead class="bg-theme">
                  <tr>
                    <th><input type="checkbox" name="" onClick="toggle(this);"></th>
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
                <?php if(isset($canteen_item_list) && !empty($canteen_item_list))
                {
                  $i=1;foreach($canteen_item_list as $stock_p_list)
                {
                  ?>
                  <tr>
                    <td><input type="checkbox" name="item_id[]" value="<?php echo $stock_p_list['item_id']; ?>"  class="booked_checkbox"></td>
                    <td><?php echo $i;?><input type="hidden" value="<?php echo $stock_p_list['item_id'];?>" id="item_id_<?php echo $stock_p_list['item_id'];?>"/></td>
                   <td><?php echo $stock_p_list['item_code'];?><input type="hidden" value="<?php echo $stock_p_list['item_code'];?>" id="item_code_<?php echo $stock_p_list['item_id'];?>"/></td>
                   <td><?php echo $stock_p_list['item_name'];?><input type="hidden" value="<?php echo $stock_p_list['item_name'];?>" id="item_name_<?php echo $stock_p_list['item_id'];?>"/></td>
                   <td><input type="text" value="<?php echo $stock_p_list['quantity'];?>" name="quantity" id="quantity_<?php echo $stock_p_list['item_id'];?>" onkeyup="payment_cal_perrow('<?php echo $stock_p_list['item_id'];?>');"/></td>
                   <td><?php echo $stock_p_list['unit'];?><input type="hidden" value="<?php echo $stock_p_list['unit'];?>" id="unit_<?php echo $stock_p_list['item_id'];?>"/><input type="hidden" value="<?php echo $stock_p_list['category_id'];?>" id="category_id_<?php echo $stock_p_list['item_id'];?>"/></td>
                    <td><?php echo $stock_p_list['item_price'];?><input type="hidden" value="<?php echo $stock_p_list['item_price'];?>" id="item_price_<?php echo $stock_p_list['item_id'];?>"/></td>
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
			  
			  
			  
			  <?php 


   $stock_item_payment_payment_array =  $this->session->userdata('stock_item_payment_payment_array');
  ?>

        <table class="table  purchase_stock_item_list_lower">
          <tbody class="no-bg">
            <tr class="no-bg">
               <td><label>Total Amount:</label></td>
              <td><input type="text" class="w-100px"   class="price_float" name="total_amount" id="total_amount" value="<?php if(!empty($stock_item_payment_payment_array['total_amount'])) { echo number_format($stock_item_payment_payment_array['total_amount'],2,'.',''); } else{ echo $form_data['total_amount']; } ?>"></td>
             
              <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
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
              <td><input type="text" class="w-100px" name="balance_due"  id="balance_due" value="<?php if(!empty($balance) && $balance>0){ echo $balance; }else{ echo '0.00' ;} ?>" class="price_float" readonly></td>
           
            </tr>
          </tbody>
        </table>
			  
			  
			  
          </div> <!-- 11 -->
          <div class="col-md-1 text-right pl-0">
          <!-- ///////// -->
              <div class="stock_add_btns">
                <a class="btn-custom" onclick="item_payment_calculation();">Add</a>
                <a class="btn-custom" onclick="child_medicine_vals();">Delete</a>
              </div>
          </div> <!-- 1 -->
        </div>

        
   


      </div>
    </div>
      
  </div> <!-- close -->
   <!-- Ends Left all content section -->
</div>




    <!-- Right side buttons  -->
    <div class="userlist-right relative">
      <div class="fixed">
        <div class="btns">
          <button class="btn-save" id="purchase_submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
         <!--  <button class="btn-save" type="submit" name="">
            <i class="fa fa-pencil"></i> Edit </button>
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-trash"></i> Delete </button> -->
          <a href="<?php echo base_url('stock_purchase_return'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
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
      get_total_amount();
      clearInterval(timerA); 
      }, 80);
  }
  function get_total_amount(){
   var quantity= $('#quantity').val();
  var amount= $('#price').val();
  var total_amount= amount *quantity;
  $('#amount').val(total_amount);

}
 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('canteen/stock_purchase_return/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
     
   

   
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


function select_vendor_data(vendor_id)
{
    $.ajax({
          url: "<?php echo base_url('canteen/stock_purchase_return/get_vandor_data/'); ?>"+vendor_id, 
          success: function(result)
          {
            $('#vendor_data_append').addClass('well');
            $('#vendor_data_append').html(result);
          }
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



//$(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('canteen/stock_purchase_return/get_item_values/'); ?>" + request.term,
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
    //});
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

</script>

<script>
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
            url: "<?php echo base_url(); ?>canteen/stock_purchase_return/item_payment_calculation/", 
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

function add_vendor(type_p)
{

  var $modal = $('#load_add_ven_modal_popup');
  $modal.load('<?php echo base_url().'vendor/add/' ?>'+type_p,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

  function get_vendor()
  {
      $.ajax({url: "<?php echo base_url(); ?>vendor/vendor_dropdown/", 
      success: function(result)
      {
      $('#vendor_id').html(result); 
      } 
      });
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
        url: "<?php echo base_url(); ?>canteen/stock_purchase_return/payment_calc_all/", 
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
            url: "<?php echo base_url(); ?>canteen/stock_purchase_return/payment_cal_perrow/", 
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
              url: "<?php echo base_url('canteen/stock_purchase_return/remove_stock_purchase_item_list');?>",
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
$('.purchasedatepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  });

function get_vendor()
          {
               $.ajax({url: "<?php echo base_url(); ?>vendor/vendor_dropdown/"+'3', 
               success: function(result)
               {
               $('#vendor_id').html(result); 
               } 
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

<!-- container-fluid -->
</body>
</html>
<div id="load_add_ven_modal_popup" class="modal fade modal-top modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<script>
  $('input[id=purchase_submit]').click(function(){
    $(this).attr('disabled', 'disabled');
});
</script>
