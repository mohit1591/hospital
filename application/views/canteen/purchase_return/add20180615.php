<?php

$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
 //print_r($this->session->userdata('medicine_id')); ?>

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
 

</head>

<body onload="list_added_medicine();">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
 
   <form id="purchase_return_form" action="<?php echo current_url(); ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
<div class="purchase_medicine">
    <div class="row">
        <div class="col-md-12">
            <?php if(!empty($form_data['vendor_id'])){?>
                 <span class="new_vendor"><input type="radio" name="new_vendor" onClick="window.location='<?php echo base_url('purchase_return/');?>add/';"> <label>New Vendor</label></span>
                <span class="new_vendor"><input type="radio" name="new_vendor" checked onClick="window.location='<?php echo base_url('vendor');?>';"> <label>Registered Vendor</label></span>
               <?php  }else{?>
                 <span class="new_vendor"><input type="radio" name="new_vendor" checked onClick="window.location='<?php echo base_url('purchase_return/');?>add/';"> <label>New Vendor</label></span>
                <span class="new_vendor"><input type="radio" name="new_vendor" onClick="window.location='<?php echo base_url('vendor');?>';"> <label>Registered Vendor</label></span>
                    <?php }?>
               
        </div>
    </div>    <!-- endRow -->

    <div class="purchase_fields">
        <div class="purchase_fields_left">
            <div class="purchase_medicine_left">
            <input type="hidden" class="m_input_default"  name="vendor_id" value="<?php if(isset($form_data['vendor_id'])){echo $form_data['vendor_id'];}else{ echo '';}?>"/>
                <div class="grp">
                    <label>vendor code</label>
                   <input type="hidden" class="m_input_default" value="<?php echo $form_data['vendor_code'];?>" name="vendor_code" />
                    <div class="vendor_code"><b><?php echo $form_data['vendor_code'];?></b></div>
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp"></div>
            </div>
            <div class="purchase_medicine_left clear">
                <div class="grp">
                    <label>invoice no.</label>
                    <input type="text" class="m_input_default" name="invoice_id" value="<?php echo $form_data['invoice_id'];?>" autofocus="">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp">
                    <label>mobile No.<span class="star">*</span></label>
                    <div class="pur_box">
                      <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                    <input type="text" maxlength="10" name="mobile" class="number" value="<?php echo $form_data['mobile'];?>" onkeypress="return isNumberKey(event);">
                    </div>
                    <div class="f_right">
                     <?php if(!empty($form_error)){ echo form_error('mobile'); } ?>
                     </div>
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>purchase no.</label>
                    <input type="text" name="purchase_no" class="m_input_default" value="<?php echo $form_data['purchase_no'];?>">
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>email id</label>
                    <input type="text" name="email" class="m_input_default" value="<?php echo $form_data['email'];?>">
                    <div class="f_right"><?php if(!empty($form_error)){ echo form_error('email'); } ?>
                    </div>
                </div>
            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>Return No.</label>
                    <input type="text" name="return_no" class="m_input_default" value="<?php echo $form_data['return_no'];?>">
                </div>

            </div>
            <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>purchase date</label>
                    <input type="text" name="purchase_date" class="datepicker m_input_default" value="<?php if($form_data['purchase_date']=='00-00-0000' || $form_data['purchase_date']==''){ echo '' ;}else{echo $form_data['purchase_date'];}?>">
                </div>
            </div>
             <div class="purchase_medicine_left">
                <div class="grp m-t-5">
                    <label>vendor name <span class="star">*</span></label>
                    <input type="text" class="alpha_numeric_space m_input_default txt_firstCap" name="name" value="<?php echo $form_data['name'];?>">
                    <div class="f_right"><?php if(!empty($form_error)){ echo form_error('name'); } ?>
                    </div>
                </div>

            </div>

            <div class="purchase_medicine_left">
               <div class="grp m-t-5">
                    <label style="float:left;vertical-align: top;">Remarks</label>
                    <textarea type="text" name="remarks" class="f_right m_input_default"><?php echo $form_data['remarks'];?></textarea>
                </div>
            </div>
        </div> <!-- purchase_fields_left -->




        <div class="purchase_fields_right">
            <div class="grp">
                <label class="Remark-Date">Remark-Date (MM/YYYY)</label>
            </div>
            <div class="grp">
                <label class="address">Address</label>
                <textarea type="text" class="m_input_default" name="address"><?php echo $form_data['address'];?></textarea>
            </div>
        </div> <!-- purchase_fields_right -->
    </div> <!-- purchase_fields -->





   <!-- <div class="purchase_medicine_box">
        <div class="grp">
            <label>by code</label>
            <input type="text" name="search_code" id="search_code" onkeyup="search_func(this.value,'search_code');">
        </div>
        <div class="grp2">
            <label>by name</label>
            <input type="text" name="search_name" id="search_name" onkeyup="search_func(this.value,'search_name');">
            <a class="btn-new" onclick="add_new_medicine();">New</a>
        </div>
        <div class="grp3">
            <label>by company</label>
            <input type="text" name="search_company" id="search_company" onkeyup="search_func(this.value,'search_company');" /> 
        </div>
    </div> -->

    <!-- purchase_medicine_box -->



    <div class="purchase_medicine_tbl_box" id="medicine_table">
       <div class="box_scroll">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" onClick="toggle(this);add_check();"></th>
                        <th>Medicine Name</th>
                        <th>Medicine Code</th>
                         <th>HSN No.</th>
                        <th>Medicine Company</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                         <th>Barcode</th>
                        <th>Conv.</th>
                        <!--<th>Mfd. Date</th>-->
                        <!--<th>Exp. Date</th>-->
                        <th>Unit1</th>
                        <th>Unit2</th>
                       <!-- <th>Free</th>-->
                        <th>MRP</th>
                        <th>Purchase Rate</th>
                        <th>Discount(%)</th>
                         <th>CGST(%)</th>
                         <th>SGST(%)</th>
                         <th>IGST(%)</th>
                         <!--<th>Total</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr id="previours_row">
                       <td colspan=""></td>
                        <td colspan=""><input type="text" name="medicine_name"  class="w-150px" id="medicine_name" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="medicine_code" class="w-60px" id="medicine_code" onkeyup="search_func(this.value);" /></td>
                         <td colspan=""><input type="text" name="hsn_no" class="w-60px" id="hsn_no" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="medicine_company" class="w-150px" id="medicine_company" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="packing" class="w-60px" id="packing"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="batch_number"  class="w-60px" id="batch_number"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="bar_code"  class="w-60px"id="bar_code"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="conv" class="w-60px" id="conv" onkeyup="search_func(this.value);" /></td>
                        <!--<td colspan=""><input type="text" name="mfc_date" id="mfc_date" class="datepicker" onchange="search_func(this.value);"/></td>-->
                        <!--<td colspan=""><input type="text" name="exp_date" id="exp_date" onkeyup="search_func(this.value);"/></td>-->
                        <td colspan="">
                        <select name="unit1" id="unit1" onchange="search_func();" class="w-60px">
                        <option value="">
                          Select Unit1  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->medicine_unit;?></option>
                        <?php }?>
                        </select></td>
                        <td colspan=""><select name="unit2" id="unit2"  onchange="search_func();" class="w-60px">
                        <option value="">
                          Select Unit2  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->medicine_unit;?></option>
                        <?php }?>
                        </select></td>
                        
                        <td colspan=""><input type="text" name="mrp" class="w-60px" id="mrp" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="p_rate"  class="w-60px" id="p_rate"  onkeyup="search_func(this.value);" /></td>
                        <!--<td colspan=""><input type="text" name="purchase_quantity" id="purchase_quantity"  onkeyup="search_func(this.value);"/></td>-->
                        <!--<td colspan=""><input type="text" name="stock_quantity" id="stock_quantity" onkeyup="search_func(this.value);"/></td>-->
                        <td colspan=""><input type="text" name="discount"  class="w-60px" id="discount_search"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="cgst" class="w-60px" id="cgst_search" onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="sgst" class="w-60px" id="sgst_search" onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="igst" class="w-60px" id="igst_search" onkeyup="search_func(this.value);"/></td>
                        
                     </tr>
                    <tr class="append_row">
                        <td colspan="16" align="center" class="text-danger">No record found</td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <!--<div class="right">
             <a class="btn-new" onclick="child_medicine_vals();">Add</a>
        </div>-->
        <div class="right relative">
            <div class="fixed">
                <button class="btn-save" type="button" id="purchase_return_submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
                <!--<button class="btn-save"><i class="fa fa-refresh"></i> Update</button>-->
                <a href="<?php echo base_url('purchase_return');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div --> 

        <!-- right -->
    </div> <!-- purchase_medicine_tbl_box -->



    <div class="purchase_medicine_tbl_box" id="medicine_select">
        
        <div class="right">
             <div class="right">
             <a class="btn-new" onclick="medicine_list_vals();">Delete</a>
        </div> <!-- right -->
        </div> <!-- right -->
    </div> <!-- purchase_medicine_tbl_box -->





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
                    <input type="text" name="total_amount" value="0" id="total_amount" onkeyup="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
                </div>

                <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
                if(isset($discount_vals) && $discount_vals==1){?>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Discount</label>
                    <div class="grp">
                    <input class="input-tiny price_float" type="text" value="<?php echo $form_data['discount_percent'];?>" id="discount_all" onkeyup="payment_calc_all();" placeholder="%" name="discount_percent">
                    <input type="text" name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" readonly>
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
                   <?php  }?>

               <!--<div class="purchase_medicine_mod_of_payment">
                    <label><?php //echo get_setting_value('MEDICINE_VAT_NAME'); ?></label>
                    <div class="grp">
                        <input class="input-tiny price_float" type="text"  id="vat_percent" onkeyup="payment_calc_all();" placeholder="%" value="<?php //echo $form_data['vat_percent'];?>" name="vat_percent">
                        <input type="text" name="vat_amount"  id="vat_amount"  value="<?php //echo $form_data['vat_amount'];?>" readonly>
                    </div>
                </div>-->
                <div class="purchase_medicine_mod_of_payment">
                    <label>CGST(Rs.)</label>
                   
                        <input type="text" name="cgst_amount"  id="cgst_amount"  value="<?php echo $form_data['cgst_amount'];?>" readonly>
                   
                </div>
                <div class="purchase_medicine_mod_of_payment">
                    <label>SGST(Rs.)</label>
                   
                        <input type="text" name="sgst_amount"  id="sgst_amount"  value="<?php echo $form_data['sgst_amount'];?>" readonly>
                    
                </div>
                <div class="purchase_medicine_mod_of_payment">
                    <label>IGST(Rs.)</label>
                   
                        <input type="text" name="igst_amount"  id="igst_amount"  value="<?php echo $form_data['igst_amount'];?>" readonly>
                   
                </div>
              

                <div class="purchase_medicine_mod_of_payment">
                    <label>Net Amount</label>
                    <input type="text" name="net_amount"  id="net_amount" onkeyup="payment_calc_all();" readonly value="<?php echo $form_data['net_amount'];?>">
                </div>
                <div class="purchase_medicine_mod_of_payment">
                    <label>Pay Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount"  id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_vals(1);">
                    <div class="f_right"><?php if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>
                <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                     <input type="text" name="balance_due"  id="balance_due" value="<?php echo $balance= $form_data['net_amount']-$form_data['pay_amount']; ?>" class="price_float" readonly >
                   
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <!-- <div class="right">
            <button class="btn-save" type="submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
            <a href="<?php echo base_url('purchase');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
        </div> --> <!-- dont delete this div -->
    </div> <!-- purchase_medicine_bottom -->





</div> <!-- purchase_medicine -->

</section> <!-- section close -->
</form>
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>
<script>

$('#purchase_return_submit').click(function(){  
   $('#purchase_return_form').submit();
});
$('#vat_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('GST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});


 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('purchase/get_payment_mode_data')?>",
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
$(document).ready(function(){
   
  payment_calc_all();
   
    $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
});
 
function add_new_medicine(){
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'medicine_entry/add/' ?>',
    {
    //alert();
    //'id1': '1',
    //'id2': '2'
    },
    function(){
    $modal.modal('show');
    });
}
function search_func()
{  
   // var val1 = $('#unit1 option:selected').val();
  //  alert(val1);
    var medicine_name = $('#medicine_name').val();
    var medicine_code = $('#medicine_code').val();
    var hsn_no = $('#hsn_no').val();
    var medicine_company = $('#medicine_company').val();
     var batch_number = $('#batch_number').val();
     var bar_code = $('#bar_code').val();
    
    var packing = $('#packing').val();
    var conv = $('#conv').val();
    //var mfc_date = $('#mfc_date').val();
    //var exp_date = $('#exp_date').val();
    var unit1 = $('#unit1').val();
    var unit2 = $('#unit2').val();
    var mrp = $('#mrp').val();
    var p_rate = $('#p_rate').val();
    //var purchase_quantity = $('#purchase_quantity').val();
   // var stock_quantity = $('#stock_quantity').val();
    var discount = $('#discount_search').val();
   // var vat = $('#vat_search').val();
    var cgst = $('#cgst_search').val();
    var igst = $('#igst_search').val();
    var sgst = $('#sgst_search').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('purchase_return/ajax_list_medicine')?>",
       data: {'medicine_name' : medicine_name,'batch_number':batch_number,'medicine_code':medicine_code,'medicine_company':medicine_company,'conv':conv,'unit1':unit1,'unit2':unit2,'mrp':mrp,'hsn_no':hsn_no,'p_rate':p_rate,'discount':discount,'cgst':cgst,'igst':igst,'sgst':sgst,'packing':packing,'bar_code':bar_code},
       dataType: "json",
       success: function(msg){
       // alert(msg.data);
        $(".append_row").remove();
        $("#previours_row").after(msg.data);
                   //Receiving the result of search here
       }
    }); 
}

function add_check()
{
    var timerA = setInterval(function(){  
      child_medicine_vals();
      clearInterval(timerA); 
    }, 1000);
}
function child_medicine_vals() 
  {      
//alert();
    var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
            //alert($(this).val());
              allVals.push($(this).val());
         } 
       });
        if(allVals!="")
           {
               send_medicine(allVals);
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
   function send_medicine(allVals)
  {   
      //alert(allVals);
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('purchase_return/set_medicine');?>",
              data: {medicine_id: allVals},
              dataType: "json",
              success: function(result) 
              {

                  $('#medicine_select').html(result.data); 
                        search_func(); 
                        list_added_medicine();
                        payment_calc_all();  
                
              }
          });
   }      
  }

  function list_added_medicine()
  {
    $.ajax({
                    url: "<?php echo base_url(); ?>purchase_return/ajax_added_medicine", 
                    dataType: "json",
                   success: function(result)
                    {
                      $('#medicine_select').html(result.data); 
                      //payment_calc_all();
                   } 
                 });   
  }

  function medicine_list_vals() 
  {      

       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
            
               allVals.push($(this).val());
         } 
       });
        //alert(allVals);
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
              url: "<?php echo base_url('purchase_return/remove_medicine_list');?>",
               dataType: "json",
              data: {medicine_id: allVals},
              success: function(result) 
              {  
                $('#medicine_select').html(result.data); 
                        search_func();
                        payment_calc_all(); 
                        list_added_medicine();
                        $('#discount_amount').val('');
                        $('#total_amount').val('');
                        $('#net_amount').val('');
                        $('#vat_amount').val('');
                        $('#discount_all').val('');
                        $('#cgst_amount').val('');
                        $('#igst_amount').val('');
                        $('#pay_amount').val('');
                        $('#sgst_amount').val('');
                        $('#balance_due').val('');
                        $('#pay_amount').val('');

                
              }
          });
   }
  }
  function payemt_vals(pay)
  {
     var timerA = setInterval(function(){  
          payment_calc_all(pay);
          clearInterval(timerA); 
        }, 80);
  }

 function payment_cal_perrow(ids){
      
    var purchase_rate = $('#purchase_rate_'+ids).val();
     //alert(purchase_rate);
    var conversion = $('#conversion_'+ids).val();
    var medicine_id= $('#medicine_id_'+ids).val();
    var mbid= $('#mbid_'+ids).val();
    //alert(mbid);
    var batch_no= $('#batch_no_'+ids).val();
    var unit1= $('#unit1_'+ids).val();

    var unit2= $('#unit2_'+ids).val();
    var bar_code= $('#bar_code_'+ids).val();
    var freeunit1= $('#freeunit1_'+ids).val();
    var freeunit2= $('#freeunit2_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var hsn_no= $('#hsn_no_'+ids).val();
    var mrp= $('#mrp_'+ids).val();
    var discount= $('#discount_'+ids).val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>purchase_return/payment_cal_perrow/", 
            dataType: "json",
            data: 'mbid='+mbid+'&purchase_rate='+purchase_rate+'&batch_no='+batch_no+'&mrp='+mrp+'&conversion='+conversion+'&manuf_date='+manuf_date+'&unit1='+unit1+'&unit2='+unit2+'&freeunit1='+freeunit1+'&freeunit2='+freeunit2+'&medicine_id='+medicine_id+'&expiry_date='+expiry_date+'&cgst='+cgst+'&igst='+igst+'&sgst='+sgst+'&discount='+discount+'&hsn_no='+hsn_no+'&bar_code='+bar_code,
            success: function(result)
            {
               $('#total_amount_'+ids).val(result.total_amount); 
              
               payment_calc_all();
            } 
          });
 }

   function payment_calc_all(pay)
    {

    var discount = $('#discount_all').val();
    var data_id= '<?php echo $form_data['data_id'];?>';
    var net_amount = $('#net_amount').val();
    var pay_amount = $('#pay_amount').val();

    var cgst = $('#cgst_percent').val();
    var sgst = $('#sgst_percent').val(); 
    var igst = $('#igst_percent').val();
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>purchase_return/payment_calc_all/", 
      dataType: "json",
       data: 'discount='+discount+'&sgst='+sgst+'&igst='+igst+'&cgst='+cgst+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
            $('#discount_amount').val(result.discount_amount);
            $('#total_amount').val(result.total_amount);
            $('#net_amount').val(result.net_amount);
            $('#pay_amount').val(result.pay_amount);
            $('#igst_amount').val(result.igst_amount);
            $('#cgst_amount').val(result.cgst_amount);
            $('#sgst_amount').val(result.sgst_amount);
            $('#discount_all').val(result.discount);
            $('#vat_percent').val(result.vat);
            $('#balance_due').val(result.balance_due);
      } 
    });
    }
  function validation_check(unit,id,m_id){
    //alert();
    $('#unit1_error_'+id).html('');
    var val=  $('#batch_no_'+id).val();
    var conversion= $('#conversion_'+id).val();
    var unit2= $('#unit2_'+id).val();
     var unit1= $('#unit1_'+id).val();
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>purchase_return/check_stock_avability/", 
      dataType: "json",
       data: 'id='+m_id+'&batch_no='+val+'&conversion='+conversion+'&unit2='+unit2+'&unit1='+unit1,
      success: function(result)
      {
        //alert(result);
         if(result==1){
            $('#unit1_error_'+id).html('No Available Quantity');
         }else{
            $('#unit1_error_'+id).html('');
         }

      } 
    });
  }

  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

$('#discount_all').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
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
</script>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<style>

.ui-autocomplete { z-index:2147483647; }
</style>
