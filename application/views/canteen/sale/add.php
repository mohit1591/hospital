<?php
$this->load->model('canteen/stock_item/stock_item_model','stock_item');
 //print_r($this->session->userdata('item_id')); ?>

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

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!--new css-->
  <!--<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
  <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>-->

</head>

<body onload="list_added_item();">
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<?php //$setting_data= get_setting_value('MEDICINE_VAT');?>

<!-- M Name, V Name, P Date, P Rate using From & To Date -->


<div class="modal fade" tabindex="-1" role="dialog" id="medicine_namess">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-60">
    <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4>Vendor Rate List</h4>
    </div>
    <div class="modal-body">
        <table class="table table-bordered text-center">
            <tr id="vender_list">
                <th width="100"  class="text-center">Item Name</th>
                <th class="text-center">Vendor Name</th>
                <th class="text-center">Sale Date</th>
                <th class="text-center">Sale Rate</th>
            </tr>
            
        </table>
   
    </div> 
</div>
</div>
</div>



<section class="userlist">
 
   <form id="sales_form" action="<?php echo current_url(); ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
	
	<input type="hidden"  name="customer_id" value="<?php if(isset($form_data['customer_id']) && !empty($form_data['customer_id'])){ echo $form_data['customer_id']; }else{ echo ''; } ?>"/>
	<input type="hidden" id="patient_id"  name="patient_id" value="<?php if(isset($form_data['patient_id'])){echo $form_data['patient_id'];}else{ echo $patient_id;}?>"/>

<div class="purchase_item">
    <div class="row">
        <div class="col-md-6">
                <?php 
				$checked_reg=''; 
                $checked_customer=''; 
                $checked_nor='checked';
				
				if(isset($form_data['customer_id']) && $form_data['customer_id']!='' && !empty($form_data['customer_id'])) 
                {
                    $checked_customer="checked";
                    $checked_nor='';
                    
                } 
				
                if((isset($_GET['reg']) && $_GET['reg']!='') || (!empty($form_data['patient_id']) && isset($form_data['patient_id']))) 
                {
                  $checked_reg="checked";
                  $checked_nor='';
                } 
				 
                ?>
                    
                <span class="new_vendor"><input type="radio" name="user_type" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('canteen/sale/');?>add/';" value="1"> <label>New Customer</label></span> &nbsp;
                <span class="new_vendor"><input type="radio" name="user_type" <?php echo $checked_customer; ?> onClick="window.location='<?php echo base_url('canteen/customer');?>';" value="1"> <label>Registered Customer</label></span> &nbsp;
                <span class="new_vendor"><input type="radio" name="user_type" <?php echo $checked_reg; ?> onClick="window.location='<?php echo base_url('patient');?>';" value="2"> <label>Patient</label></span>
		
            <?php /* if(!empty($form_data['customer_id'])){?>
                 <span class="new_customer"><input type="radio" name="new_customer" onClick="window.location='<?php echo base_url('canteen/sale/');?>add/';"> <label>New Customer</label></span>
                <span class="new_customer"><input type="radio" name="new_customer" checked onClick="window.location='<?php echo base_url('canteen/customer');?>';"> <label>Registered Customer</label></span>
               <?php } */  ?>
		</div>

    </div>    <!-- endRow -->


<div class="row">
    
    <div class="col-md-4">
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label><input type="hidden" name="customer_id" value="<?php if(isset($form_data['customer_id'])){echo $form_data['customer_id'];}else{ echo '';}?>"/> 
		<?php
		if(!empty($form_data['customer_id'])) {echo "Customer Code";} else {echo "";}
		if(!empty($form_data['patient_id'])) {echo "Patient Code";  } else {echo "";}
		if(empty($form_data['customer_id']) && empty($form_data['patient_id'])) {echo "Customer Code"; }
		?>
				
				</label>
            </div>
            <div class="col-sm-8">
                <input type="hidden" value="<?php echo $form_data['customer_code'];?>" name="customer_code" />
				<input type="hidden" value="<?php echo $form_data['patient_code'];?>" name="patient_code" />
				
                <div class="customer_code"><b>
				<?php if(!empty($form_data['customer_id'])){ echo $form_data['customer_code']; };?>
				<?php if(!empty($form_data['patient_id'])){ echo $form_data['patient_code']; } ;?>
				<?php if(empty($form_data['customer_id']) && empty($form_data['patient_id'])) { echo $form_data['customer_code'];} ?>
				</b></div>
            </div>
        </div>
     
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label>Sale no.</label>
            </div>
            <div class="col-sm-8">
                <b><input type="text" class="m_input_default" name="sale_no" value="<?php echo $form_data['sale_no'];?>" readonly></b>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-4 pr-0">
        <label>
		<?php
		if(!empty($form_data['customer_id'])){echo "Customer Name";} else {echo "";}
		if(!empty($form_data['patient_id'])) {echo "Patient Name";  } else {echo "";}
		if(empty($form_data['customer_id']) && empty($form_data['patient_id'])) {echo "Customer Name"; }
		?>
		<span class="star">*</span></label>
            </div>
            <div class="col-sm-8">
                    <input type="text" name="name" placeholder="Customer Name" class="alpha_numeric_space m_input_default txt_firstCap" id="ven_name" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['pname']; } else { echo $form_data['cname']; } ?>">
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
            </div>
        </div>
        
        
        
        <div class="row m-b-5">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"></div>
        </div>

    </div> <!-- //4 -->




    
    <div class="col-md-4">
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label>Mobile No.<span class="star">*</span></label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="" readonly="readonly"  value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91"> 
                <input type="text" name="mobile" placeholder="Mobile No." class="number numeric m_number" maxlength="10" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['pmobile']; } else { echo $form_data['cmobile']; } ?>" onkeypress="return isNumberKey(event);">
                <?php if(!empty($form_error)){ echo form_error('mobile'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label>Email id</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="m_input_default" placeholder="Email id" name="email" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['pemail']; } else { echo $form_data['cemail']; } ?>">
                <?php if(!empty($form_error)){ echo form_error('email'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label style="width:39%;">Sale date/time</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="sale_date" style="width: 110px;" class="datepickersale m_input_default" value="<?php if($form_data['sale_date']=='00-00-0000' || empty($form_data['sale_date'])){echo '';}else{echo $form_data['sale_date'];}?>">
                <input type="text" name="sale_time" class="w-80px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['sale_time']; ?>">
            </div>
        </div>     
    </div> <!-- //4 -->


    
   <div class="col-md-4">
        
       <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 1</label>
            </div>
            <div class="col-xs-4">
               <input type="text" name="address" id="" placeholder="Address 1" class="address" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['paddress']; } else { echo $form_data['caddress']; } ?>"/>
               <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
            </div>
          </div>

           <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 2</label>
            </div>
            <div class="col-xs-4">
               <input type="text"  name="address2" placeholder="Address 2" class="address" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['paddress2']; } else { echo $form_data['caddress2']; } ?>"/>
              
            </div>
          </div>
           <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 3</label>
            </div>
            <div class="col-xs-4">
               <input type="text" name="address3" placeholder="Address 3" class="address" value="<?php if(!empty($form_data['patient_id'])){ echo $form_data['paddress3']; } else { echo $form_data['caddress3']; } ?>"/>
               
            </div>
          </div>

           
            <div class="row m-b-3">
            <div class="col-xs-3">
                <label class="address">Remarks</label>
                </div>
            <div class="col-xs-4">
                <textarea type="text" id="remarks" placeholder="Remarks" class="m_input_default" name="remarks"><?php if(!empty($form_data['patient_id'])){ echo $form_data['premarks']; } else { echo $form_data['cremarks']; } ?></textarea>
            </div>
          </div>

    </div> <!-- //4 -->

</div> <!-- mainRow -->

    <div class="purchase_medicine_tbl_box" id="medicine_table">
       <div class="box_scroll" > <!-- class="left" on 07-12-2017-->
            <table class="table table-bordered table-striped m_pur_tbl1">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" onClick="toggle(this); add_check();"></th>
                            <th>Item Name</th>
                            <th>Packing</th>
                            <th>Item Code</th>
                            <th>HSN No.</th>
                            <th>Mfg.Company</th>
                            <th>QTY</th>
                            <th>MRP</th>
                            <th>Discount(%)</th>
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
                        <td colspan=""><input type="text" name="item_code" class="w-60px" id="item_code" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="hsn_no" id="hsn_no" class="w-60px"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="manuf_company" class="w-150px"  id="manuf_company" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="qty" id="qty" class="w-60px" onkeyup="search_func(this.value);" /></td>
    
                        <td colspan=""><input type="text" name="mrp" id="mrp" class="w-60px"  onkeyup="search_func(this.value);" /></td>
                  
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
		
        <!--<div class="right">
             <a class="btn-new" onclick="child_item_vals();">Add</a>
        </div> -->
        <div class="right relative">
            <div class="fixed">
                <button class="btn-save" id="sales_submit" type="button"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
                <!--<button class="btn-save"><i class="fa fa-refresh"></i> Update</button>-->
                <a href="<?php echo base_url('canteen/sale');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div -->

        <!-- right -->
    </div> <!-- purchase_medicine_tbl_box -->


<?php //if(empty($this->uri->segment(4))){ ?>
    <div class="purchase_medicine_tbl_box" id="item_select">
       
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
                    <input type="text" name="total_amount" id="total_amount" onChange="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
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

                       <?php  }

                    ?>
                

           
               <!--<div class="purchase_medicine_mod_of_payment">
                    <label><?php //echo get_setting_value('MEDICINE_VAT_NAME');?></label>
                    <div class="grp">
                        <input class="input-tiny price_float" type="text"  id="vat_percent" onkeyup="payment_calc_all();" placeholder="%" value="<?php //echo $form_data['vat_percent'];?>" name="vat_percent">
                        
                        <input type="text" name="vat_amount"  id="vat_amount"  value="<?php //echo $form_data['vat_amount'];?>" readonly>
                    </div>
                </div>-->
                <div class="purchase_medicine_mod_of_payment">
                    <label>CGST(Rs.) </label>
                   
                        <!--<input class="input-tiny price_float" type="text"  id="cgst_percent" onkeyup="payment_calc_all();" placeholder="%" value="<?php //echo $form_data['cgst_percent'];?>" name="cgst_percent">-->
                        
                        <input type="text" name="cgst_amount"  id="cgst_amount"  value="<?php echo $form_data['cgst_amount'];?>" readonly>
                   
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>SGST(Rs.) </label>
                   
                       <!--<input class="input-tiny price_float" type="text"  id="sgst_percent" onkeyup="payment_calc_all();" placeholder="%" value="<?php //echo $form_data['sgst_percent'];?>" name="sgst_percent">-->
                        
                        <input type="text" name="sgst_amount"  id="sgst_amount"  value="<?php echo $form_data['sgst_amount'];?>" readonly>
                   
                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>IGST(Rs.) </label>
                   
                        <!--<input class="input-tiny price_float" type="text"  id="igst_percent" onkeyup="payment_calc_all();" placeholder="%" value="<?php echo $form_data['igst_percent'];?>" name="igst_percent">
                        -->
                        
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
                     <input type="text" name="balance_due"  id="balance_due" value="<?php echo $balance= $form_data['net_amount']-$form_data['pay_amount']; ?>" class="price_float" readonly >
                   
                </div>


            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <div class="right">
            <!-- <button class="btn-save" type="submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
            <button class="btn-save"><i class="fa fa-refresh"></i> Update</button>
            <a href="<?php echo base_url('purchase');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a> -->
        </div> <!-- dont delete this div -->
    </div> <!-- purchase_medicine_bottom -->





</div> <!-- purchase_item -->

</section> <!-- section close -->
</form>




<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("canteen/sale/print_purchase_recipt"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<script>

 $('.datepickersale').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                });  

$('.datepicker3').datetimepicker({
     format: 'LT'
  });
$('#sales_submit').click(function(){  
    $(':input[id=sales_submit]').prop('disabled', true);
   $('#sales_form').submit();
});

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
        url: "<?php echo base_url('canteen/sale/get_payment_mode_data')?>",
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
    var item = $('#item').val();
    var item_code = $('#item_code').val();
    var packing = $('#packing').val();
    var hsn_no = $('#hsn_no').val();
    var qty = $('#qty').val();
    var manuf_company = $('#manuf_company').val();
    var mrp = $('#mrp').val();

    var discount = $('#discount').val();
   // var vat = $('#vat').val();
    var cgst = $('#cgst').val();
    var sgst = $('#sgst').val();
    var igst = $('#igst').val();

    $.ajax({
       type: "POST",
       url: "<?php echo base_url('canteen/sale/ajax_list_item')?>",
       data: {'item' : item,'item_code':item_code,'hsn_no':hsn_no,'manuf_company':manuf_company,'mrp':mrp,'discount':discount,'cgst':cgst,'sgst':sgst,'igst':igst,'packing':packing,'qty':qty},
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
              
              //barcode.push($('#qty_'+this).val());
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
   function send_item(allVals)
  {   
      
       var barncode=[];
       if(allVals!="")
       {
        //alert(allVals);
            $.each(allVals,function(i){
            // alert(allVals[i]);
            if($('#qty_'+allVals[i]).val()!='')
            {
              barncode.push($('#qty_'+allVals[i]).val());
              setTimeout(function() {validation_bar_code(allVals[i]); }, 80);   
            }
            else
            {
             barncode.push($('#qty_'+allVals[i]).val());   
            }
           
             
            });
            
        //  var qty = $('#qty_'+allVals).val();
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('canteen/sale/set_item');?>",
                  data: {item_id: allVals, barcode:barncode},
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
                    url: "<?php echo base_url(); ?>canteen/sale/ajax_added_item", 
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
        remove_item(allVals);
        search_func();
        }
         else{
        $('#confirm-select').modal({
            backdrop: 'static',
            keyboard: false
          });
     } 
  
  } 
  function remove_item(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('canteen/sale/remove_item_list');?>",
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


    var max_qty=+('<?php echo $max_pur_qty;?>');
 
    var sale_rate = $('#sale_rate_'+ids).val();
    var item_id= $('#item_id_'+ids).val();
    var batch_no= $('#batch_no_'+ids).val();
    var hsn_no= $('#hsn_no_'+ids).val();
    var qty= $('#qty_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var mrp= $('#mrp_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    $('#qty_max_'+ids).text('');
   // if(qty <= max_qty)
   // {
    
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>canteen/sale/payment_cal_perrow/", 
                dataType: "json",
                data: 'sale_rate='+sale_rate+'&batch_no='+batch_no+'&mrp='+mrp+'&manuf_date='+manuf_date+'&item_id='+item_id+'&expiry_date='+expiry_date+'&cgst='+cgst+'&sgst='+sgst+'&igst='+igst+'&discount='+discount+'&hsn_no='+hsn_no+'&qty='+qty,
                success: function(result)
                {

                   $('#total_amount_'+ids).val(result.total_amount); 
                  
                   payment_calc_all();
                } 
              });
    //}
   // else{
   //     $('#qty_max_'+ids).text('Purchase qty should be less than '+max_qty);
   // }
 }

   function payment_calc_all(pay)
    {
        
        var discount = $('#discount_all').val();
        var discount_amount= $('#discount_amount').val();
        var data_id= '<?php echo $form_data['data_id'];?>';
       // var vat = $('#vat_percent').val(); 
        var net_amount = $('#net_amount').val();
        var pay_amount = $('#pay_amount').val();
         var cgst = $('#cgst_percent').val();
         var sgst = $('#sgst_percent').val(); 
         var igst = $('#igst_percent').val(); 
         var discount_type = $('#discount_type').val(); 
      //var balance_due = $('#balance_due').val();
      /*if(pay !=undefined)
      {*/
            $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>canteen/sale/payment_calc_all/", 
            dataType: "json",
             data: 'discount='+discount+'&sgst='+sgst+'&igst='+igst+'&cgst='+cgst+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&discount_amount='+discount_amount+'&data_id='+data_id+'&discount_type='+discount_type,
            success: function(result)
            {                
                  $('#discount_amount').val(result.discount_amount);
                  $('#total_amount').val(result.total_amount);
                  $('#net_amount').val(result.net_amount);
                  $('#pay_amount').val(result.pay_amount);
                 // $('#vat_amount').val(result.vat_amount);
                  $('#cgst_amount').val(result.cgst_amount.toFixed(2));
                  $('#igst_amount').val(result.igst_amount);
                  $('#sgst_amount').val(result.sgst_amount);
                  $('#discount_all').val(result.discount);
                  $('#cgst_percent').val(result.cgst);
                  $('#sgst_percent').val(result.sgst);
                  $('#igst_percent').val(result.igst);
                  $('#balance_due').val(result.balance_due);
                 
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
$('#discount_all').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
 function validation_bar_code(id){
  
    $('#unit1_error_'+id).html('');
    //var val=  $('#batch_no_'+id).val();
   // var unit2= $('#qty_'+id).val();
    var mbid =$('#item_id_'+id).val();
    var qty =$('#qty_'+id).val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>canteen/sale/check_bar_code/", 
      dataType: "json",
       data: 'mbid='+mbid+'&qty='+qty,
      success: function(result)
      {
        //alert(result);
         if(result==1){
          $('#barcode_error_'+id).html('This Barcode already in used');
         }else{
           $('#barcode_error_'+id).html('');
         }

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
        window.location.href='<?php echo base_url('canteen/sale/add');?>'; 
    }) ;
   
       
  <?php }?>
 });
</script>
<!--- Search Estimate ----------->


<script type="text/javascript">

	$(function () {
 
    var i=1;
    var getData = function (request, response) {  
    	
        row = i ;
        $.ajax({
        url : "<?php echo base_url('canteen/sale/estimate_item/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: { 
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
      // 	alert(data);
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

    var selectItem = function (event, ui) 
    {
            //$(".medicine_val").val(ui.item.value);
            var names = ui.item.data.split("|");
           
            $('.estimate_no').val(names[0]);
            $('#ven_name').val(names[2]);
            $('#vendor_gst_no').val(names[3]); 
            $('.m_number').val(names[5]);
            $('.address').val(names[6]);
            set_item_stockitem(names[0]);
            return false;
    }

    $(".estimate_no").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });



  function set_item_stockitem(allVals)
  {   
     //alert(allVals);
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('canteen/sale/set_item_stockitem');?>",
              data: {purchase_id: allVals},
              dataType: "json",
              success: function(result) 
              {
              
                $('#item_select').html(result.data); 
                //list_added_item();
                payment_calc_all();  
             }
          });
   }      
  } 
      function get_canteen_vendor(i_id)
      {
        payment_cal_perrow(i_id);
       $.ajax({
              type: "POST",
              url: "<?php echo base_url('canteen/sale/get_canteen_vendors');?>",
              data: {item_id: i_id},
              dataType: "json",
              success: function(result) 
              {
                
                $('#vender_list').after(result); 
                
             }
          });
      }

      function get_sale_rates(val,id)
      {
        payment_cal_perrow(id);
        $('#sale_rate_'+id).val(+(val));
      }
	</script>
	<!--- Search Estimate ----------->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<style>

.ui-autocomplete { z-index:2147483647; }
</style>

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
