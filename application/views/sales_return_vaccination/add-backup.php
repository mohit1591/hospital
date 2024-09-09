<?php

$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
$users_data = $this->session->userdata('auth_users');
 //print_r($this->session->userdata('vaccine_id')); ?>

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

<body onLoad="list_added_medicine();">


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
<section class="userlist">
 
   <form id="sales_return_form" action="<?php echo current_url().$query_string; ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
	<input type="hidden"   name="patient_id" value="<?php if(isset($form_data['patient_id'])){echo $form_data['patient_id'];}?>"/>
<div class="sale_medicine">
    <div class="row">
        <div class="col-md-12">
            <div class="grp">
                <?php 
                $checked_reg=''; 
                $checked_ipd='';
                $checked_nor='checked';
                if(isset($_GET['reg']) && $_GET['reg']!='') {
                $checked_reg="checked";
                $checked_nor='';
                }?>
                <?php if(isset($_GET['ipd']) && $_GET['ipd']!='') {

                $checked_ipd="checked";
                $checked_nor='';
                }  ?>

                <span class="new_vendor"><input type="radio" name="" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('sales_return_vaccination/');?>add/';"> <label>New Patient</label></span>
                <span class="new_vendor"><input type="radio" name="" <?php echo $checked_reg; ?> onClick="window.location='<?php echo base_url('patient');?>';"> <label>Registered Patient</label></span>
                
            </div>
        </div>
    </div>    <!-- endRow -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
            
                <label>Patient Reg. No.</label>
                <input type="hidden" value="<?php echo $form_data['patient_reg_code'];?>" name="patient_reg_code" />
            </div>
            <div class="b2">
                <b><?php echo $form_data['patient_reg_code'];?></b>
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
         <!-- grp-full -->
        
<?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="col-md-7" id="referred_by">
                   <label><input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
                    <label><input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
                    <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
            </div>
        </div> <!-- innerrow -->

        <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-md-5">
                <label>Referred By Doctor<span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <select class="w-150px" name="refered_id" id="refered_id">
                    <option value="">Select Doctor</option>
                    <?php foreach($doctors_list as $doctors) {?>
                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                    <?php }?>
                </select>
                   

                <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal">New</a>
                <?php } ?>
                    <?php if(!empty($form_error)){ echo form_error('refered_id'); } ?>
            </div>
        </div> <!-- innerrow -->

        <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
            <div class="col-md-5">
                <label>Referred By Hospital <span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <select name="referral_hospital" id="referral_hospital" class="w-150px m_input_default" >
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) { ?>
                <a  class="btn-new" id="hospital_add_modal">New</a>
                <?php } ?>
            <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
            </div>
        </div> <!-- innerrow -->
<?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){ ?>

<div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By Doctor<span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <select class="w-150px" name="refered_id" id="refered_id">
                    <option value="">Select Doctor</option>
                    <?php foreach($doctors_list as $doctors) {?>
                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                    <?php }?>
                </select>
                   

                <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal">New</a>
                <?php } ?>
                    <?php if(!empty($form_error)){ echo form_error('refered_id'); } ?>
            </div>
        </div> <!-- innerrow -->
        <input type="hidden" name="referred_by" value="0">
        <input type="hidden" name="referral_hospital" value="0">

<?php  } else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){ ?>
<div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By<span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <select name="referral_hospital" id="referral_hospital" class="w-150px m_input_default" >
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) { ?>
                <a  class="btn-new" id="hospital_add_modal">New</a>
                <?php } ?>
            <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
            </div>
        </div> <!-- innerrow -->
        <input type="hidden" name="referred_by" value="1">
        <input type="hidden" name="refered_id" value="0">

<?php } ?>
    </div> <!-- sale_fields -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
                <label>Billing No.</label>
            </div>
            <div class="b2">
                <input type="text" name="sales_no" value="<?php echo $form_data['sales_no'];?>">
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
        <div class="grp-full">
            <div class="b1">
                <label>Billing Date</label>
            </div>
            <div class="b2">
               <input type="text" name="sales_date" class="datepicker" value="<?php if($form_data['sales_date']=='00-00-0000' || empty($form_data['sales_date'])){echo '';}else{echo $form_data['sales_date'];}?>">
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->

        <div class="grp-full">
            <div class="b1">
                <label>Return No.</label>
            </div>
            <div class="b2">
               <input type="text" name="return_no"  value="<?php echo $form_data['return_no'];?>" readonly>
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
    </div> <!-- sale_fields -->

    <div class="sale_fields">
        
        <div class="grp-full">
            <div class="b1">
                <label>Patient Name <span class="star">*</span></label>
            </div>
                <div class="b2 m11">
                <select class="mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                   
                    <?php foreach($simulation_list as $simulation){?>
                      <option value="<?php echo $simulation->id; ?>" <?php if($form_data['simulation_id']==$simulation->id){ echo 'selected';}?>><?php echo $simulation->simulation;?></option>
                    <?php }
                    ?>
                </select>

                <input type="text" name="name" class="mr_name alpha_numeric_space" value="<?php echo $form_data['name'];?>" autofocus="">
                <div class="f_right"><?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                    </div>
                <div class="f_right"><?php if(!empty($form_error)){ echo form_error('name'); } ?>
                    </div>
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->
        <div class="grp-full">
            <div class="b1">
                <label>Gender<span class="star">*</span></label>
            </div>
            <div class="b2" id="gender">
                    <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                    <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                    <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
                    <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
            </div>
           
            <div class="b3"></div>
        </div> <!-- grp-full -->
        
        <div class="grp-full">
            <div class="b1">
                <label>Mobile No.<span class="star">*</span></label>
            </div>
            <div class="b2" >
                 <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91" style="width:59px;"> 
                <input type="text" name="mobile" class="number numeric" maxlength="10" value="<?php echo $form_data['mobile'];?>" onKeyPress="return isNumberKey(event);">
                 <div class="f_right">
                     <?php if(!empty($form_error)){ echo form_error('mobile'); } ?>
                     </div>
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->

         <div class="grp-full">
            <div class="b1">
                <label>Aadhaar No.</label>
            </div>
            <div class="b2">
               <input type="text" name="adhar_no"  value="<?php echo $form_data['adhar_no'];?>">
                <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
            </div>
            <div class="b3"></div>
        </div> <!-- grp-full -->

           <div class="grp-full">
            <div class="b1">
                <label>Remarks</label>
            </div>
            <div class="b2">
                <textarea type="text" name="remarks" class=""><?php echo $form_data['remarks'];?></textarea>
            </div>
           
            <div class="b3"></div>
        </div>

        
    </div> <!-- sale_fields -->







 <div class="sale_medicine_tbl_box" id="medicine_table">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" class=""  onClick="toggle(this);add_check();" value=""></th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Medicine Code</th>
                        <th>HSN No.</th>
                        <th>Medicine Company</th>
                        <th>Batch No.</th>
                        <th>Barcode</th>
                        <!--<th>Mfd. Date</th>
                        <th>Exp. Date</th>-->
                        <th>Min Alert</th>
                        <th>Quantity</th>
                        <th>MRP</th>
                        <th>Discount(%)</th>
                        <th>CGST(%)</th>
                        <th>SGST(%)</th>
                        <th>IGST(%)</th>
                         <!--<th>Total</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                     <tr id="previours_row">
                       <td colspan=""></td>
                        <td colspan=""><input type="text" name="medicine_name" id="medicine_name" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="packing" id="packing"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="medicine_code" id="medicine_code" onKeyUp="search_func(this.value);"/></td>
                          <td colspan=""><input type="text" name="hsn_no" id="hsn_no" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="medicine_company" id="medicine_company" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="batch_number" id="batch_number" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="bar_code" id="bar_code" onKeyUp="search_func(this.value);"/></td>
                       
                        <td colspan=""><input type="text" name="stock" id="stock" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="qty" id="qty"  onkeyup="search_func(this.value);"/></td>
                       <td colspan=""><input type="text" name="rate" id="rate"  onkeyup="search_func(this.value);"/></td>
                        <!--<td colspan=""><input type="text" name="purchase_quantity" id="purchase_quantity"  onkeyup="search_func(this.value);"/></td>-->
                        <!--<td colspan=""><input type="text" name="stock_quantity" id="stock_quantity" onkeyup="search_func(this.value);"/></td>-->
                        <td colspan=""><input type="text" name="discount" id="discount_search"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="cgst" id="cgst_search" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="sgst" id="sgst_search" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="igst" id="igst_search" onKeyUp="search_func(this.value);"/></td>
                        
                     </tr>
                         <td class="append_row text-danger" colspan="15"><div class="text-center">No record found</div></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <!--<div class="right">
             <a class="btn-new" onClick="child_medicine_vals();">Add</a>
        </div> -->
        <div class="right relative">
            <div class="fixed">
                <button class="btn-save" type="button" id="sales_return_submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
                <!--<button class="btn-save"><i class="fa fa-refresh"></i> Update</button>-->
                <a href="<?php echo base_url('sales_return_vaccination');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div -->

        <!-- right -->
    </div> <!-- sale_medicine_tbl_box -->



    <div class="sale_medicine_tbl_box" id="medicine_select">
      
    </div> <!-- sale_medicine_tbl_box -->





    <div class="sale_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
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
                   

                <div id="payment_detail" class="">
                    

                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" name="total_amount" id="total_amount" onkeyup="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
                </div>

  <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
            if(isset($discount_vals) && $discount_vals==1){?>
                <div class="sale_medicine_mod_of_payment">
                     <label>Discount</label>
                    <div class="grp m7">
                    <input class="input-tiny m8 price_float" id="discount_percent" name="discount_percent" type="text" value="<?php echo $form_data['discount_percent'];?>" onKeyUp="payment_calc_all();" placeholder="%">
                    <input class="m9" type="text" name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" readonly>
                    </div>
                </div>

                <?php } else{?>

            <div class="sale_medicine_mod_of_payment">
                             <label></label>
                            <div class="grp m7">
                            <input class="input-tiny m8 price_float" id="discount_percent" name="discount_percent" type="hidden" value="0" onKeyUp="payment_calc_all();" placeholder="%">
                            <input class="m9" type="hidden" name="discount_amount"  id="discount_amount" value="0" readonly>
                            </div>
                        </div>


                   <?php  }?>

              
             <div class="sale_medicine_mod_of_payment">
                     <label>CGST</label>
                   
                        <input class="m9" type="text" name="cgst_amount"  id="cgst_amount"  value="<?php echo $form_data['cgst_amount'];?>" readonly/>
                
                </div>
                 <div class="sale_medicine_mod_of_payment">
                     <label>SGST</label>
                   
                        <input class="m9" type="text" name="sgst_amount"  id="sgst_amount"  value="<?php echo $form_data['sgst_amount'];?>" readonly/>
                
                </div>
                 <div class="sale_medicine_mod_of_payment">
                     <label>IGST</label>
                   
                        <input class="m9" type="text" name="igst_amount"  id="igst_amount"  value="<?php echo $form_data['igst_amount'];?>" readonly/>
                
                </div>

                <!--<div class="sale_medicine_mod_of_payment">
                    <label>Net Payable</label>
                    <input type="text" name="" value="0">
                </div>-->

                <div class="sale_medicine_mod_of_payment">
                     <label>Net Amount</label>
                    <input type="text" name="net_amount"  id="net_amount" onKeyUp="payment_calc_all();" readonly value="<?php echo $form_data['net_amount'];?>">
                </div>

                <div class="sale_medicine_mod_of_payment">
                    <label>Pay Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount"  id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_vals(1);">
                     <div class="f_right"><?php if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                     <input type="text" name="balance_due"  id="balance_due"  value="<?php echo $balance= $form_data['net_amount']-$form_data['pay_amount']; ?>" class="price_float" readonly >
                   
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <!-- <div class="right">
            <button class="btn-save" type="submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
            <button class="btn-save"><i class="fa fa-sign-out"></i> Exit</button>
        </div>  -->
    </div> <!-- sale_medicine_bottom -->




</form>
</section> <!-- section close -->

<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>
<script>

$(document).ready(function(){
var $modal = $('#load_add_modal_popup');

$('#hospital_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'hospital/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});
$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();
      if(test==0)
      {
        $("#hospital_div").hide();
        $("#doctor_div").show();
        $('#referral_hospital').val('');
        
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
          $("#ref_by_other").css("display","none"); 
          $("#hospital_div").show();
          $('#refered_id').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
});

$('#sales_return_submit').click(function(){  
   $('#sales_return_form').submit();
});

$('#vat_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('GST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

$(document).ready(function(){

    var simulation_id = $("#simulation_id :selected").val();
    //alert(simulation_id);
    find_gender(simulation_id);
});
 function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }

 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('sales_return_vaccination/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
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
    var medicine_name = $('#medicine_name').val();
    var hsn_no = $('#hsn_no').val();
    var medicine_code = $('#medicine_code').val();
    var medicine_company = $('#medicine_company').val();
    var batch_number= $('#batch_number').val();
    var packing = $('#packing').val();
    var stock = $('#stock').val();
    var qty = $('#qty').val();
    var mrp = $('#mrp').val();
    var rate = $('#rate').val();
    var discount = $('#discount_search').val();
    var cgst = $('#cgst_search').val();
    var sgst = $('#sgst_search').val();
    var igst = $('#igst_search').val();
    var bar_code = $('#bar_code').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('sales_return_vaccination/ajax_list_medicine')?>",
       data: {'medicine_name' : medicine_name,'medicine_code':medicine_code,'medicine_company':medicine_company,'stock':stock,'qty':qty,'rate':rate,'discount':discount,'cgst':cgst,'sgst':sgst,'hsn_no':hsn_no,'igst':igst,'packing':packing,'batch_number':batch_number,'bar_code':bar_code},
       dataType: "json",
       success: function(msg){
          $(".append_row").remove();
           $("#previours_row").after(msg.data);
         payment_calc_all();
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

    var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
        if(allVals!=""){
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
     // alert(allVals);
   if(allVals!="")
   {  
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_vaccination/set_medicine');?>",
              data: {vaccine_id: allVals},
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
                    url: "<?php echo base_url(); ?>sales_return_vaccination/ajax_added_medicine", 
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
       if(allVals!=""){
       remove_medicine(allVals);
       search_func();
        }
  
  } 
  function remove_medicine(allVals)
  { 
   if(allVals!="")
   {
   	//alert(allVals);
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_vaccination/remove_medicine_list');?>",
               dataType: "json",
              data: {vaccine_id: allVals},
              success: function(result) 
              {  
                 $('#medicine_select').html(result.data); 
                    search_func();
                    payment_calc_all(); 
                    list_added_medicine();
                    $('#discount_amount').val('');
                    $('#total_amount').val('');
                    $('#net_amount').val('');
                    $('#cgst_amount').val('');
                    $('#igst_amount').val('');
                    $('#sgst_amount').val('');
                    $('#discount_all').val('');
                    $('#balance_due').val('');
                    $('#pay_amount').val('');
                    //$('#vat_percent').val('');
              }
          });
   }
  }


 function payment_cal_perrow(ids){
   
    var purchase_rate = $('#purchase_rate_mrp'+ids).val();
    var mrp = $('#mrp_'+ids).val();
    var qty = $('#qty_'+ids).val();
    var hsn_no = $('#hsn_no_'+ids).val();
     var mbid= $('#mbid_'+ids).val();
    var vaccine_id= $('#vaccine_id_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var bar_code= $('#bar_code_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var batch_no= $('#batch_no_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    var conversion= $('#conversion_'+ids).val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>sales_return_vaccination/payment_cal_perrow/", 
            dataType: "json",
            data: 'mbid='+mbid+'&purchase_rate='+purchase_rate+'&qty='+qty+'&vaccine_id='+vaccine_id+'&expiry_date='+expiry_date+'&igst='+igst+'&cgst='+cgst+'&sgst='+sgst+'&discount='+discount+'&manuf_date='+manuf_date+'&batch_no='+batch_no+'&conversion='+conversion+'&mrp='+mrp+'&hsn_no='+hsn_no+'&bar_code='+bar_code,
            success: function(result)
            {
               $('#total_amount_'+ids).val(result.total_amount); 
              
               payment_calc_all();
            } 
          });
 }

   function payment_calc_all(pay)
    {
    var data_id= '<?php echo $form_data['data_id'];?>';
    var discount = $('#discount_percent').val();
    var net_amount = $('#net_amount').val();
    var pay_amount = $('#pay_amount').val();  
        
      //alert(net_amount);
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>sales_return_vaccination/payment_calc_all/", 
      dataType: "json",
       data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
          $('#discount_amount').val(result.discount_amount);
          $('#total_amount').val(result.total_amount);
          $('#net_amount').val(result.net_amount);
          $('#pay_amount').val(result.pay_amount);
          $('#cgst_amount').val(result.cgst_amount);
          $('#sgst_amount').val(result.sgst_amount);
          $('#igst_amount').val(result.igst_amount);
          $('#discount_all').val(result.discount);
          //$('#vat_percent').val(result.vat);
          $('#balance_due').val(result.balance_due);
      } 
    });
    }

     function payemt_vals(pay)
  {
     var timerA = setInterval(function(){  
          payment_calc_all(pay);
          clearInterval(timerA); 
        }, 80);
  }

  

  $(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'doctors/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

  function get_sales_docotors()
{
    $.ajax({url: "<?php echo base_url(); ?>sales_medicine/sales_medicine_dropdown/", 
    success: function(result)
    {
      $('#refered_id').html(result); 
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
$('#discount_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
</script>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<style>

.ui-autocomplete { z-index:2147483647; }
</style>

<script>

   
</script>
