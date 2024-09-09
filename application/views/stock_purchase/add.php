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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<!--new css-->
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
  <!--<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>-->

    <!--new css-->
<script type="text/javascript">

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'stock_purchase/view/' ?>'+id,
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
                      url: "<?php echo base_url('stock_purchase/deleteall');?>",
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

<body onload="list_added_item();">


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
 <form id="purchase_form" action="<?php echo current_url().$query_string; ?>" method="post">
 <input type="hidden" name="data_id" value="<?php echo $form_data['data_id'] ?>"/>
 <section class="userlist">
  <!-- Left side Contents  -->
    <div class="userlist-box">
    
      <!-- upper all fields -->
      <div class="row">
        <div class="col-md-4">
          <!-- upper left side label and their fields -->
          <div class="row m-b-5">
          <input type="hidden" class="m_input_default" value="<?php echo $form_data['data_id'] ?>" name="data_id" />
            <div class="col-md-4">
              <label>Purchase No.</label>
            </div>
            <div class="col-md-8">
            <input type="hidden" class="m_input_default" name="purchase_code" value="<?php echo $form_data['purchase_code'];?>"/>
              <strong><?php echo $form_data['purchase_code'];?></strong>
            </div>
          </div>
          
         
          
          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Purchase Date</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="purchase_date" class="purchasedatepicker m_input_default"value="<?php echo $form_data['purchase_date'] ?>">
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Select Vendor<span class="star">*</span></label>
            </div>
            <div class="col-md-8">
              <select class="w-150px m_select_btn" name="vendor_id" id="vendor_id" onchange="select_vendor_data(this.value)">
                  <option value="">Select Vendor</option>
                  <?php foreach($vendor_list as $vendorl){?>
                   <option value="<?php echo $vendorl->id;?>" <?php if(isset($form_data['vendor_id']) && $form_data['vendor_id']==$vendorl->id){ echo 'selected';}?>><?php echo $vendorl->name;?></option>
                  <?php }?>
              </select>
              <a href="javascript:void(0)" class="btn-new" onclick=" return add_vendor(3);"><i class="fa fa-plus"></i> New</a>
               <div class="">
            <?php if(!empty($form_error)){ echo form_error('vendor_id'); } ?>
            </div>
            </div>
           
          </div>

         <!--  <div class="row m-b-5">
            <div class="col-md-4">
              <label>Item Name</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="item_name" placeholder="Enter Item Name" class="item_name m_input_default txt_firstCap" value="">

              <input type="hidden" value="" id="item_id"/>
              
            </div>
          </div>

          <div class="row m-b-5">
            <div class="col-md-4">
              <label>Item Code</label>
            </div>
            <div class="col-md-8">
              <input type="text" class="m_input_default" name="item_code" placeholder="Item Code" id="item_code" value="" readonly>
            </div>
          </div> -->


       <input type="hidden" value="" id="category_id"/>

        </div> <!-- 4 -->
       
        <div class="col-md-4">
            <?php $well='';if(isset($form_data['vendor_code']) || isset($form_data['vendor_name']) || isset($form_data['address']))
            {
              $well='class="well"';
            }
            ?>
            <!-- upper Middle section label and their fields -->
        <div id="vendor_data_append">
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
          
            <div class="col-md-4">
              <label>Customer Code</label>
            </div>
            <div class="col-md-8">
            <input type="text" class="m_input_default" name="customer_code" value="<?php echo $form_data['customer_code'];?>"/>
            </div>
          </div>
            <div class="row m-b-5">
            <div class="col-md-4">
              <label>Payment Due Date</label>
            </div>
            <div class="col-md-8">
              <input type="text" name="payment_due_date" class="purchasedatepicker m_input_default"value="<?php echo $form_data['payment_due_date'] ?>">
            </div>
          </div>
    <?php /* ?>
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

            <?php } } ?>

        </div>

        <div id="payment_detail">


        </div>

<?php */ ?>

       </div>
      </div> <!-- Row -->
      <!-- Upper row ends here -->



    <div class="purchase_medicine_tbl_box" id="medicine_table">
       <div class="box_scroll" > <!-- class="left" on 07-12-2017-->
            <table class="table table-bordered table-striped m_pur_tbl1">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" onClick="toggle(this); add_check();"></th>
                            <th>Item Name</th>
                            <th>Packing</th>
                            <th>Conv.</th>
                            <th>Item Code</th>
                         
                            <th>Mfg.Company</th>
                           
                            <th>Unit1</th>
                            <th>Unit2</th>
                            
                            <th>MRP</th>
                            <th>Purchase Rate</th>
                            <th>Discount</th>
                            <th>CGST (%)</th>
                            <th>SGST (%)</th>
                            <th>IGST (%)</th>
                        
                    </tr>
                </thead>
                <tbody>
                   <tr id="previours_row">
                        <td colspan=""></td>
                        <td colspan=""><input type="text" name="item" class="w-150px" id="item" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="packing" id="packing" class="w-60px"  onkeyup="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="conversion" class="w-60px" id="conversion" onkeyup="search_func(this.value);"/></td>

                        <td colspan=""><input type="text" name="item_code" class="w-60px" id="item_code" onkeyup="search_func(this.value);" /></td>
                       
                        <td colspan=""><input type="text" name="manuf_company" class="w-150px"  id="manuf_company" onkeyup="search_func(this.value);" /></td>
                       
                        
                       
                        
                        <!--<td colspan=""><input type="text" name="exp_date" id="exp_date" onkeyup="search_func(this.value);"/></td>-->
                       <td colspan="">
                        <select name="unit1" value="unit1" class="w-150px" onchange="search_func();">
                        <option value="">
                          Select Unit1  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->unit;?></option>
                        <?php }?>
                        </select></td>
                        <td colspan=""><select name="unit2" value="unit2" onchange="search_func();" class="w-150px" > 
                        <option value="">
                          Select Unit2  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->unit;?></option>
                        <?php }?>
                        </select></td>
                        <td colspan=""><input type="text" name="mrp" id="mrp" class="w-60px"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="p_rate" id="p_rate" class="w-60px"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="discount" id="discount" class="w-80px"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="cgst" id="cgst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                         <td colspan=""><input type="text" name="sgst" id="sgst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                          <td colspan=""><input type="text" name="igst" id="igst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                        <!--<td colspan=""><input type="text" name="total" id="total" onkeyup="search_func(this.value);"/></td>-->
                    </tr>

                   <tr class="append_row">
                        <td colspan="16" class="text-danger" align="center">No record found</td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
    </div> <!-- purchase_item_tbl_box -->
<?php echo form_error('mrp[]'); ?>
     <div class="purchase_medicine_tbl_box" id="item_select">
       
    </div> <!-- purchase_item_tbl_box -->


        <div class="purchase_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="purchase_medicine_mod_of_payment">
                   <b>Mode of Payment</b>
                    <select  name="payment_mode" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
                </div>
                 <div id="updated_payment_detail">
                 <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {

                      $tot_values= explode('_',$field_names);

                    ?>

                   <div class="purchase_medicine_mod_of_payment"><label><?php echo $tot_values[1];?><span class="star">*</span></label>
                   <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" onkeypress="payment_calc_all();">
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
                   
                   <?php } }?>
                 
                   </div>
                  
                <div id="payment_detail">
                    

                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" name="total_amount" id="total_amount" onChange="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
                </div>

                    <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
                        if(isset($discount_vals) && $discount_vals==1){?>

                    <!--<div class="purchase_medicine_mod_of_payment">
                        <label>Discount</label>
                        <div class="grp">

                        <input class="input-tiny price_float" type="text" value="< ?php echo $form_data['discount_percent'];?>" id="discount_all" onkeyup="payment_calc_all();" placeholder="%" name="discount_percent">

                        <input type="text" name="discount_amount"  id="discount_amount" value="< ?php echo $form_data['discount_amount'];?>" readonly>
                        </div>
                    </div>-->
                    
                    <div class="sale_medicine_mod_of_payment">
            <label>Discount</label>
            <div class="grp m7">

              <script>
               function payment_calc_all() {
                 document.getElementById("discount_all").value = document.getElementById("discount_type").value;

               }
             </script>

             <select id="discount_type" style="width: 40px;height: 24px;"onchange="payment_calc_all()">

               <option value="0">%</option>

               <option value="1"<?php if(($form_data['discount_percent'] == $form_data['discount_amount']) && ($form_data['discount_percent']) >0){ echo 'selected';}?>>₹</option>            
             </select>

             <input class="input-tiny m8 price_float" name="discount_percent" type="text" value="<?php echo $form_data['discount_percent'];?>" id="discount_all" onKeyUp="payment_calc_all();" placeholder="">

             <input style="width: 156px;" class="m9" type="text" name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" readonly>
           </div>
         </div>

                        <?php }else{?>

                    <div class="purchase_medicine_mod_of_payment">
                        <label></label>
                        <div class="grp">
                        <input class="input-tiny price_float" type="hidden" value="0" id="discount_all" onkeyup="payment_calc_all();" placeholder="%" name="discount_percent">
                        <input type="hidden" name="discount_amount"  id="discount_amount" value="0" readonly>
                        </div>
                    </div>

                       <?php  }

                    ?>
                <div class="purchase_medicine_mod_of_payment">
                   <label>Item Discount</label>
                   <input class="m9" type="text" name="item_discount"  id="item_discount"  value="<?php echo @$form_data['item_discount'];?>" >
               </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>CGST(Rs.) </label>
                        <input type="text" name="cgst_amount"  id="cgst_amount"  value="<?php echo $form_data['cgst_amount'];?>" readonly>
                   
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>SGST(Rs.) </label>
                        <input type="text" name="sgst_amount"  id="sgst_amount"  value="<?php echo $form_data['sgst_amount'];?>" readonly>
                   
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>IGST(Rs.) </label>
                        <input type="text" name="igst_amount"  id="igst_amount"  value="<?php echo $form_data['igst_amount'];?>" readonly>
                  
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Net Amount</label>
                    <input type="text" name="net_amount"  id="net_amount" onkeyup="payment_calc_all();" readonly value="<?php echo $form_data['net_amount'];?>">
                </div>
                <div class="purchase_medicine_mod_of_payment">
                    <label>Pay Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount"  id="pay_amount" value="<?php echo $form_data['pay_amount']; ?>" class=""  onblur="payemt_vals(1);">
                    <div class="f_right"><?php if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                     <input type="text" name="balance_due"  id="balance_due" value="<?php echo @$balance= $form_data['net_amount']-@$form_data['pay_amount']; ?>" class="price_float" readonly >
                   
                </div>


            </div> <!-- right_box -->

        </div> <!-- left -->
        <div class="right">
        </div> <!-- dont delete this div -->
    </div> <!-- purchase_item_bottom -->


      
  </div> <!-- close -->
   <!-- Ends Left all content section -->





    <!-- Right side buttons  -->
    <div class="userlist-right relative">
      <div class="fixed">
        <div class="btns">
          <button class="btn-save" id="purchase_submit" type="submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
         <!--  <button class="btn-save" type="submit" name="">
            <i class="fa fa-pencil"></i> Edit </button>
          <button class="btn-save" type="submit" name="">
            <i class="fa fa-trash"></i> Delete </button> -->
          <a href="<?php echo base_url('stock_purchase'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
        </div>
      </div>
    </div> 
    <!-- Ends Right all Button section -->


  
</section> <!-- cbranch -->
</form>
<?php
$this->load->view('include/footer');
?>
</div><!-- container-fluid -->
<script>

 

 function payemt_vals()
  {
      var timerA = setInterval(function(){  
      get_total_amount();
      clearInterval(timerA); 
      }, 80);
  }
  function get_total_amount(){

  var amount= $('#price').val();
  var quantity= $('#quantity').val();
  var total_amount= amount *quantity;

  $('#amount').val(total_amount);

}


 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('stock_purchase/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
     
   

   
  }

 
function select_vendor_data(vendor_id)
{
    $.ajax({
          url: "<?php echo base_url('stock_purchase/get_vandor_data/'); ?>"+vendor_id, 
          success: function(result)
          {
            //$('#vendor_data_append').addClass('well');
            $('#vendor_data_append').html(result);
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
    $.ajax({url: "<?php echo base_url(); ?>stock_purchase/reset_search/", 
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
                 url: "<?php echo base_url('stock_purchase/delete/'); ?>"+id, 
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

</script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
           

            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("stock_purchase/print_purchase_recipt"); ?>');">Print</a>

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

</div>
<script>

</script>

<script>

$('.purchasedatepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  });


$(document).ready(function(){
 payment_new_calc_all()
});

function add_vendor(vendor_id)
{

  var $modal = $('#load_add_ven_modal_popup');
  $modal.load('<?php echo base_url().'vendor/add/' ?>'+vendor_id,
  {
    //'id1': '1',
    //'id2': '2'
  },
  function(){
   $modal.modal('show');
  });
}

  // function get_vendor()
  // {
  //     $.ajax({url: "<?php echo base_url(); ?>vendor/vendor_dropdown/", 
  //     success: function(result)
  //     {
  //     $('#vendor_id').html(result); 
  //     } 
  //     });
  // }

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
        url: "<?php echo base_url(); ?>stock_purchase/payment_calc_all/", 
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

 //  function payment_cal_perrow(ids){
   
 //    var item_code = $('#item_code_'+ids).val();
 //    var item_id = $('#item_id_'+ids).val();
 //    var total_price = $('#total_price_'+ids).val();
 //    var category_id = $('#category_id_'+ids).val();
 //    var item_name = $('#item_name_'+ids).val();
 //    var quantity= $('#quantity_'+ids).val();
 //    var unit= $('#unit_'+ids).val();
 //    var item_price= $('#item_price_'+ids).val();
 //    var unit2= $('#unit2_'+ids).val();
    
    
 //    $.ajax({
 //            type: "POST",
 //            url: "<?php echo base_url(); ?>stock_purchase/payment_cal_perrow/", 
 //            dataType: "json",
 //            data: 'item_code='+item_code+'&item_name='+item_name+'&quantity='+quantity+'&unit='+unit+'&item_price='+item_price+'&item_id='+item_id+'&category_id='+category_id+'&total_price='+total_price+'&unit2='+unit2,
 //            success: function(result)
 //            {
 //               //$('#total_amount_'+ids).val(result.total_amount); 
 //              $('#total_price_'+ids).val(result.total_new_price);
 //               payment_new_calc_all();
 //            } 
 //          });
 // }


function remove_item(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_purchase/remove_stock_purchase_item_list');?>",
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
  <!--new css-->

<!--     <script type="text/javascript">
      var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php // echo base_url('stock_purchase/get_item_values/'); ?>" + request.term,
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
          $('#unit1').val(names[2]);
          $('#price').val(names[3]);
          $('#item_id').val(names[4]);
          $('#category_id').val(names[5]);
          $('#unit2').val(names[6]);
        
     
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
    </script> -->

<!-- add bottom 4 feb 20  -->

<script>

$('#purchase_submit').click(function(){  
    var z=true;
    /*if(item_mrp_error()==false)
    {
        z=false;
    }*/
    
    if(z==true)
    {
       $(':input[id=purchase_submit]').prop('disabled', true);
        $('#purchase_form').submit();  
    }
    else
    {
        return false;
    }
   
});

function item_mrp_error()
{
    var mrp=true;
    var purchase_rate=true;
    
    var data_ids = get_ids();
    
    $.each(data_ids,function(index,value){
       
       if($('#mrp_'+value).val()=="" || $('#mrp_'+value).val()=="0.00")
       {
           $('#mrp_error_'+value).show().html('MRP is required!');
           mrp=false;
       }
       
       if($('#purchase_rate_'+value).val()=="" || $('#purchase_rate_'+value).val()=="0.00")
       {
           $('#purchase_rate_error_'+value).show().html('Purchase is required!');
           purchase_rate=false;
       }
        
    });
    
    
    if(mrp==false || purchase_rate==false)
    {
        return false;
    }
    else
    {
        return true;
    }
    
    
}

function get_ids()
{
    var allVals=[];
    $('.booked_checkbox').each(function() 
    {
        allVals.push($(this).val());
          
    });
    return allVals;
    
}

$('#igst_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('IGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#cgst_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('CGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#sgst_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('SGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});


 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('stock_purchase/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
     
   
 $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
   });  
   
  }

 // function payment_function(value,error_field){
 //    $('#updated_payment_detail').html('');
 //     $.ajax({
 //        type: "POST",
 //        url: "<?php echo base_url('stock_purchase/get_payment_mode_data')?>",
 //        data: {'payment_mode_id' : value,'error_field':error_field},
 //       success: function(msg){
 //         $('#payment_detail').html(msg);
 //        }
 //    });
     

$(document).ready(function(){
   
  payment_calc_all();
   
    $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
});

function add_check()
{
    var timerA = setInterval(function(){  
      child_item_vals();
      clearInterval(timerA); 
    }, 1000);
}
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}



function search_func()
{  
    var item = $('#item').val();
    var item_code = $('#item_code').val();
    var packing = $('#packing').val();  
    var conversion = $('#conversion').val();
    //var mfc_date = $('#mfc_date').val();
    var manuf_company = $('#manuf_company').val();
    var unit1 = $("#unit1" ).val();
    var unit2 = $('#unit2').val();
    var mrp = $('#mrp').val();
    var p_rate = $('#p_rate').val();
    var discount = $('#discount').val();
   // var vat = $('#vat').val();
    var cgst = $('#cgst').val();
    var sgst = $('#sgst').val();
    var igst = $('#igst').val();

    $.ajax({
       type: "POST",
       url: "<?php echo base_url('stock_purchase/ajax_list_item')?>",
       data: {'item' : item,'item_code':item_code,'conversion':conversion,'manuf_company':manuf_company,'unit1':unit1,'unit2':unit2,'mrp':mrp,'p_rate':p_rate,'discount':discount,'cgst':cgst,'sgst':sgst,'igst':igst,'packing':packing},
       dataType: "json",
       success: function(msg){
       // alert(msg.data);
        $(".append_row").remove();
        $("#previours_row").after(msg.data);
                   //Receiving the result of search here
       }
    }); 
}

function child_item_vals() 
  {      
   
    var allVals = [];
   
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
    
         } 
       });

       if(allVals!="")
        {
       send_item(allVals);
       search_func();
       }
  } 
 function toggle(source) 
  {  
   
     checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function toggle_new(source) 
  {  
     checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

 function validation_item_code(id){
  
    $('#unit1_error_'+id).html('');
    var iid =$('#item_id_'+id).val();
    var item_code =$('#item_code_'+id).val();
  }
 function send_item(allVals)
  {   
      
       var item_code=[];
       if(allVals!="")
       {
        //alert(allVals);
            $.each(allVals,function(i){
            // alert(allVals[i]);
            if($('#item_code_'+allVals[i]).val()!='')
            {
              item_code.push($('#item_code_'+allVals[i]).val());
              setTimeout(function() {validation_item_code(allVals[i]); }, 80);   
            }
            else
            {
             item_code.push($('#item_code_'+allVals[i]).val());   
            }

            });

          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('stock_purchase/set_item');?>",
                  data: {item_id: allVals, item_code:item_code},
                  dataType: "json",
                  success: function(result) 
                  { 

                    $('#item_select').html(result.data); 
                    search_func(); 
                    list_added_item();
                    payment_calc_all();  
                  }
              });
       }      
  }

  function list_added_item()
  {
    $.ajax({
            url: "<?php echo base_url(); ?>stock_purchase/ajax_added_item", 
            dataType: "json",
           success: function(result)
            {

              $('#item_select').html(result.data); 
              //payment_calc_all();
           } 
         });   
  }

  function item_list_vals() 
  {      

       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
        if(allVals!="")
        {
        remove_medicine(allVals);
        search_func();
        }
  
  } 
  function remove_medicine(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_purchase/remove_item_list');?>",
               dataType: "json",
              data: {item_id: allVals},
              success: function(result) 
              {  
                $('#item_select').html(result.data); 
                search_func();
                 
                list_added_item();  
                payment_calc_all();
               
                $('#discount_amount').val('');
                $('#total_amount').val('');
                $('#net_amount').val('');
                $('#igst_amount').val('');
                $('#cgst_amount').val('');
                $('#sgst_amount').val('');
                $('#discount_all').val('');
                $('#igst_percent').val('');
                $('#cgst_percent').val('');
                $('#sgst_percent').val('');
                $('#balance_due').val('');
                $('#pay_amount').val('');

              }
          });
   }
  }


 function payment_cal_perrow(ids){


   
 
    var purchase_rate = $('#purchase_rate_'+ids).val();
    var conversion = $('#conversion_'+ids).val();
    var item_id= $('#item_id_'+ids).val();
    var item= $('#item_'+ids).val();
    var item_code= $('#item_code_'+ids).val();
    var unit1= +($('#unit1_'+ids).val());
    var freeunit1= $('#freeunit1_'+ids).val();
    
    var freeunit2= $('#freeunit2_'+ids).val();
    

   // alert('#unit1'+ids);
    //alert(unit1);
    var unit2= $('#unit2_'+ids).val();
    var mrp= $('#mrp_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    //var vat= $('#vat_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    $('#unit1_max_'+ids).text('');
   // if(unit1 <= max_qty)
    //{
    
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>stock_purchase/payment_cal_perrow/", 
                dataType: "json",
                data: 'purchase_rate='+purchase_rate+'&mrp='+mrp+'&item='+item+'&conversion='+conversion+'&manuf_date='+manuf_date+'&unit1='+unit1+'&unit2='+unit2+'&item_id='+item_id+'&expiry_date='+expiry_date+'&cgst='+cgst+'&sgst='+sgst+'&igst='+igst+'&discount='+discount+'&freeunit1='+freeunit1+'&freeunit2='+freeunit2+'&item_code='+item_code,
                success: function(result)
                {

                   $('#total_amount_'+ids).val(result.total_amount); 
                  
                   payment_calc_all();
                } 
              });

    // }
   // else{
   //    $('#unit1_max_'+ids).text('Item qty should be less than '+max_qty);
   // }

 }

   function payment_calc_all(pay)
    {
        
        var discount = $('#discount_all').val();
        var discount_amount= $('#discount_amount').val();
        var data_id= '<?php echo $form_data['data_id'];?>';
        var net_amount = $('#net_amount').val();
        var pay_amount = $('#pay_amount').val();
         var cgst = $('#cgst_percent').val();
         var sgst = $('#sgst_percent').val(); 
         var igst = $('#igst_percent').val(); 
         var discount_type = $('#discount_type').val(); 
    
            $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>stock_purchase/payment_calc_all/", 
            dataType: "json",
             data: 'discount='+discount+'&sgst='+sgst+'&igst='+igst+'&cgst='+cgst+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&discount_amount='+discount_amount+'&data_id='+data_id+'&discount_type='+discount_type,
            success: function(result)
            {                
                  $('#discount_amount').val(result.discount_amount);
                  $('#total_amount').val(result.total_amount);
                  $('#net_amount').val(result.net_amount);
                  $('#pay_amount').val(result.pay_amount);
                  $('#cgst_amount').val(result.cgst_amount.toFixed(2));
                  $('#igst_amount').val(result.igst_amount);
                  $('#sgst_amount').val(result.sgst_amount);
                  $('#discount_all').val(result.discount);
                  $('#cgst_percent').val(result.cgst);
                  $('#sgst_percent').val(result.sgst);
                  $('#igst_percent').val(result.igst);
                  $('#balance_due').val(result.balance_due);
                  $('#item_discount').val(result.item_discount);
                 
            } 
          });
        }
  //  }

    function payemt_vals(pay)
  {
   
     var timerA = setInterval(function(){  
          payment_calc_all(pay);
          clearInterval(timerA); 
        }, 80);
  }
$('#discount_all').keyup(function()
{
    var discount_type = $('#discount_type').val();
    if(discount_type===0)
    {
        if ($(this).val() > 100){
          alert('Discount should be less then 100');
        //$('#error_msg_vat').html('Gst should be less then 100');
      } 
    }
    else if(discount_type===1)
    {
        var total_amount = $('#total_amount').val();
        if ($(this).val() > total_amount){
          alert('Discount should be less then total amount');
        //$('#error_msg_vat').html('Gst should be less then 100');
        }
        
    }
  
});



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
  
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 $purchase_id = $this->session->userdata('purchase_id');
 ?>
</script>
<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && isset($purchase_id) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('stock_purchase/add');?>'; 
    }) ;
   
       
  <?php }?>
 });
</script>
<!--- Search Estimate ----------->
<script type="text/javascript">
function get_purchase_rates(val,id)
      {
        payment_cal_perrow(id);
        $('#purchase_rate_'+id).val(+(val));
      }
      
      
          function add_serial(value)
          {
            var ser_ar='';
            var str_serial= $('#serial_no_array_'+value).val();
            if(str_serial!='')
            {
                var str_serial=JSON.parse(str_serial); 
                var ser_ar=str_serial.split(',');
                //var ser_length=ser_ar.length;
            }
            
            var conversion=$('#conversion_'+value).val();
            var unit_one=$('#unit1_'+value).val();
            var unit_two=$('#unit2_'+value).val();
            
            var free_unit_one=$('#freeunit1_'+value).val();
            var free_unit_two=$('#freeunit2_'+value).val();
            
            var free_quantity=parseFloat(conversion)*parseFloat(free_unit_one)+parseFloat(free_unit_two);
            
            var unitquantity=parseFloat(conversion)*parseFloat(unit_one)+parseFloat(unit_two);
            var quantity=parseFloat(free_quantity)+parseFloat(unitquantity);
            
                  if(value != '1_'+quantity)
                  {

                     $('#add_serial_no').val('1_'+quantity);

                     $('#serial_no_data').html('');

                    
                     if(quantity > 0)
                     {
                      pr="<tr><td><input type='hidden' id='serial_row_no' name='serial_row_no' value='"+value+"'><div><input type='checkbox' name='checkall' id='check_all' onclick='clone_rows();'> Copy </div></td></tr>";
                       $('#serial_no_data').append(pr);
                      //tr+="<tr><td>S.no</td><td>Serial No</td></tr>";
                       for(i=1;i<=quantity;i++)
                       {
                           var valss = ser_ar[i-1];
                           //if(typeof valss==="undefined")
                           if(typeof valss === 'undefined')
                           {
                             var valssw =''; 
                             
                           }
                           else
                           {
                              var valssw = ser_ar[i-1];
                           }
                           
                           
                        tr="<tr>";
                        tr+="<td>"+i+"</td><td><input type='text' value='"+valssw+"' id='serial_"+i+"'></td>";
                        tr+="</tr>";
                        $('#serial_no_data').append(tr);
                        $('#save_serial_no_records').val(i);
                       
                       } 
                     }
                     else{
                        $('#serial_no_data').html("<div class='text-danger'>Insert Quantity First</div>");
                     } 
                  }
      
                 $('#serial_no').modal({
                     backdrop: 'static',
                     keyboard: false
                   })
          }

          function show_serial(serials, k, status)
          {
                
              var str_serial=JSON.parse(serials);
              
              var ser_ar=str_serial.split(',');
              var ser_length=ser_ar.length;

              var serial_status=$("#serial_show_status_"+k).val();
          
                  $("#show_serial_no_data").html('');
                  $("#serial_show_status_"+k).val('1');
                  
                  tr="<tr><td>S.no</td><td>Serial No.</td></tr>";
                  for(i=0; i < ser_length; i++ )
                  {
                     if(i==0)
                     { 
                      tr+="<tr>";
                     } else { tr ="<tr>"; };

                     tr+="<td>"+(i+1)+"</td><td>"+ser_ar[i]+"</td>";
                     tr+="</tr>";
                     $("#show_serial_no_data").append(tr);
                     //console.log(tr);
                  }

     
                    $('#show_serial_no').modal({
                             backdrop: 'static',
                             keyboard: false
                           })
          }

          function save_serial_no_records(value)
          {
            var serial_row_no = $('#serial_row_no').val();
           rows=[];
           for(i=1;i <= value; i++)
           {
             val=$('#serial_'+i).val();
             rows.push(val);
           }
           $("#serial_no_array_"+serial_row_no).val(rows);
           $("#serial_post_no_array_"+serial_row_no).val(rows);

          }
          
          
function clone_rows()
{ 
    var table_val = $('#serial_1').val(); 
    var i=2;
    //$('#tblOne > tbody  > tr').each(function() {
   $("#serial_no_data tbody tr").each(function (){ 
      
      $('#serial_'+i).val(table_val);
      i++;
  });   
             
 } 
  </script>


<!-- container-fluid -->
</body>
</html>
<div id="load_add_ven_modal_popup" class="modal fade modal-top modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-dismiss="modal" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
              <table id="serial_no_data"></table>
              </div>
             </div> 

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
         </div>
      </div>
   </div>

  <div id="show_serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-dismiss="modal" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
                 <table class="table-bordered table-responsive" id="show_serial_no_data"></table>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
         </div>
      </div>
   </div>