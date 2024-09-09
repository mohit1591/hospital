<?php
$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
 

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
<section class="userlist">
 
   <form id="sales_return_form" action="<?php echo current_url().$query_string; ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
	<input type="hidden"   name="patient_id" id="patient_id" value="<?php if(isset($form_data['patient_id'])){echo $form_data['patient_id'];}?>"/>
	
	<input type="hidden" name="discount_setting" id="discount_setting" value="<?php echo $discount_setting;?>" />  
	
<div class="row m-b-5">
            <div class="col-md-4">
                <!-- ///////////////////////// -->
                <div class="row">
                    <div class="col-md-4">
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

                        <span class="new_vendor"><input type="radio" name="" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('sales_return_medicine/');?>add/';"> <label>New Patient</label></span>
                        
                    </div>
                    <div class="col-md-8">
                        <span class="new_vendor"><input type="radio" name="" <?php echo $checked_reg; ?> onClick="window.location='<?php echo base_url('patient');?>';"> <label>Registered Patient</label></span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                         <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                         <input type="hidden" id="patient_code" value="<?php echo $form_data['patient_reg_code'];?>" name="patient_reg_code" />
                    </div>
                    <div class="col-md-8">
                        <b><?php echo $form_data['patient_reg_code'];?></b>
                    </div>
                </div>
                
               
                
                <div class="row">
                    <div class="col-md-4">
                        <label>Referred By</label>
                    </div>
                    <div class="col-md-8" id="referred_by">
                       <label><input type="radio" name="referred_by" id='radio_1' value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
                        <label><input type="radio" name="referred_by" id='radio_2' value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
                        <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
                    </div>
                </div>
                
                <div class="row"  id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?>>
                    <div class="col-md-4">
                        <label>Referred By Doctor<span class="star">*</span></label>
                    </div>
                    <div class="col-md-8" >
                        <select class="w-150px" name="refered_id" id="refered_id" onChange="return get_others(this.value)">
                            <option value="">Select Doctor</option>
                            <?php foreach($doctors_list as $doctors) {?>
                            <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                            <?php }?>
                            <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['refered_id']=='0'){ echo "selected"; }} ?>> Others </option>
                        </select>
                       

                        <?php if(in_array('122',$users_data['permission']['action'])) {
                        ?>
                            <a  class="btn-new" id="doctor_add_modal"><i class="fa fa-plus"></i> New</a>
                        <?php } ?>
                        <?php if(!empty($form_error)){ echo form_error('refered_id'); } ?>
                    </div>
                </div>

                <div class="row" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['refered_id']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
                   <div class="col-md-4"><b> Other </b></div>
                   <div class="col-xs-8">
                    <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
                      <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
                 </div>
               </div>
                
                <div class="row" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
                    <div class="col-md-4">
                        <label>Referred By Hospital <span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
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
                            <a  class="btn-new" id="hospital_add_modal"><i class="fa fa-plus"></i> New</a>
                            <?php } ?>
                        <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
                    </div>
                </div>
                
           
                
            </div>   <!-- 4 -->
            
            
            <div class="col-md-4">
                <!-- ///////////////////////// -->
                
                <div class="row m-b-5">
                    <div class="col-md-4">
                        <label>Sale No.</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" placeholder="Type Sale No. for Return" name="sales_no"  id="sales_no" class="sales_no" value="<?php echo $form_data['sales_no'];?>">
                    </div>
                </div>
                
                <div class="row m-b-5">
                    <div class="col-md-4">
                         <label>Sale Date</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" id="sales_date" name="sales_date" class="datepicker" value="<?php if($form_data['sales_date']=='00-00-0000' || empty($form_data['sales_date'])){echo '';}else{echo $form_data['sales_date'];}?>">
                    </div>
                </div>
                
                <div class="row m-b-5">
                    <div class="col-md-4">
                         <label>Return No.</label>
                    </div>
                    <div class="col-md-8">
                         <input type="text" name="return_no"  value="<?php echo $form_data['return_no'];?>" readonly>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <label>Gender<span class="star">*</span></label>
                    </div>
                    <div class="col-md-8" id="gender">
                         <input type="radio" name="gender" id="male_g" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                         <input type="radio" name="gender" id="female_g" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                         <input type="radio" name="gender" id="other_g" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
                         <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                    </div>
                </div>
            
            </div>   <!-- 4 -->
            
            
            <div class="col-md-4">
                <!-- ///////////////////////// -->
                
                <div class="row m-b-5">
                    <div class="col-md-4">
                        <label>Patient Name <span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
                        <select class="mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                   
                            <?php foreach($simulation_list as $simulation){?>
                              <option value="<?php echo $simulation->id; ?>" <?php if($form_data['simulation_id']==$simulation->id){ echo 'selected';}?>><?php echo $simulation->simulation;?></option>
                            <?php }
                            ?>
                        </select>

                        <input type="text" name="name" id="patinet_name" class="mr_name alpha_numeric_space w-134px txt_firstCap" value="<?php echo $form_data['name'];?>" autofocus="">
                        <div class="f_right"><?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                        </div>
                        <div class="f_right"><?php if(!empty($form_error)){ echo form_error('name'); } ?>
                        </div>
                    </div>
                </div>

                <!-- new code by mamta -->
                <div class="row m-b-5">
                    <div class="col-md-4">
                    <strong> 
                        <select name="relation_type"  id="relation_type" class="w-90px" onchange="father_husband_son(this.value);">
                        <?php foreach($gardian_relation_list as $gardian_list) 
                        {?>
                        <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
                        <?php }?>
                        </select>

                        </strong>
                    </div>
                    <div class="col-md-8">
                        <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id">
                        <option value="">Select</option>
                            <?php
                            if(!empty($simulation_list))
                            {
                                foreach($simulation_list as $simulation)
                                {
                                    $selected_simulation = '';
                                    if(in_array($simulation->simulation,$simulations_array)){

                                    $selected_simulation = 'selected="selected"';

                                    }
                                    else{
                                    if($simulation->id==$form_data['relation_simulation_id'])
                                    {
                                    $selected_simulation = 'selected="selected"';
                                    }
                                    }
                                    echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                                }
                            }
                            ?> 
                        </select> 
                        <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
                    </div>
                </div> <!-- row -->

                <!-- new code by mamta -->
                
                <div class="row m-b-5">
                    <div class="col-md-4">
                        <label>Mobile No.<!--<span class="star">*</span>--></label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91" style="width:59px;"> 
                        <input type="text" name="mobile" id="mobile" class="number numeric" maxlength="10" value="<?php echo $form_data['mobile'];?>" onKeyPress="return isNumberKey(event);">
                        <div class="f_right">
                            <?php if(!empty($form_error)){ echo form_error('mobile'); } ?>
                        </div>
                    </div>
                </div>
                
                
                
                <div class="row">
                    <div class="col-md-4">
                        <label>Remarks</label>
                    </div>
                    <div class="col-md-8">
                        <textarea type="text" id="remarks" name="remarks" class=""><?php echo $form_data['remarks'];?></textarea>
                    </div>
                </div>
            
            </div>   <!-- 4 -->
        </div> <!-- row -->
            
    
    
    
    
    <div class="userlist-right relative">
        <div class="fixed">
            <div class="btns"></div>
        </div>
    </div>







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
                <a href="<?php echo base_url('sales_return_medicine');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div -->

        <!-- right -->
    </div> <!-- sale_medicine_tbl_box -->



    <div class="sale_medicine_tbl_box" id="medicine_select">
        
        <div class="left">
            <table class="table table-bordered table-striped m_pur_tbl1" id="medicine_per_row">
               <thead class="bg-theme">
                    <tr>
                      <th class="40" align="center">
                       <input type="checkbox" name="" onClick="toggle_new(this);">
                       </th>
                        <th>Medicine Name</th>
                        <th>Medicine Code</th>
                        <th>HSN No.</th>
                        <th>Packing</th>
                        <th>Batch No.</th>
                        <th>Mfg. Date</th>
                        <th>Exp. Date</th>
                        <th>Barcode</th>
                        <th>Quantity</th>
                        <th>MRP</th>
                        <th>Discount</th>
                        <th>CGST(%)</th>
                        <th>SGST(%)</th>
                        <th>IGST(%)</th>
                        <th>Total</th>
                        </tr>
                </thead>
                <tbody>
                        <tr id="append_data_row" <?php if(!empty($form_data['data_id']) || !empty($sale_medicne_list)){?> style="display:none;"  <?php  } ?>>
                            <td colspan="20"  align="center" class="text-danger">No record found</td>
                       </tr>
                       <?php 
                       //echo "<pre>"; print_r($medicne_new_list); exit;
                       if(!empty($medicne_list))
                       {
                            $i=1;
                            foreach($medicne_list as $medicine_data)
                            {
                                //echo "<pre>"; print_r($medicine_data); exit;
                               $batch_med_id = $medicine_data['mid'].$medicine_data['batch_no'];
                                
                                $varids="'".$medicine_data['mid'].$medicine_data['batch_no']."'";
                                
                                $value="'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
                                
                                $row_varids=$medicine_data['mid'].'.'.$medicine_data['batch_no'];
                               $batch_no =  $medicine_data['batch_no'];
                               
                               $row_batch_val=$medicine_data['mid'].$medicine_data['batch_no'];
                               
                                
                                $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine_data['mid'].$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine_data['mid'].$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           $('#discount_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });
                          $('#cgst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
        
                                
                                ?>
                                
                                <tr id="prescription_tr_<?php echo $row_batch_val; ?>"><td><input type="hidden" id="per_pic_amount<?php echo $batch_med_id; ?>" name="per_pic_amount[]" value="<?php echo $medicine_data['per_pic_amount']; ?>"><input type="hidden" id="sale_amount<?php echo $batch_med_id; ?>" name="sale_amount[]" value="<?php echo $medicine_data['sale_amount']; ?>"/><input class="my_medicine_purchase" type="hidden" id="medicine_id_<?php echo $batch_med_id; ?>" name="m_id[]" value="<?php echo $batch_med_id; ?>"/><input type="hidden" id="medicine_sel_id_<?php echo $batch_med_id; ?>" name="medicine_sel_id[]" value="<?php echo $medicine_data['mid']; ?>"/><input type="hidden" value="<?php echo $medicine_data['conversion']; ?>"  name="conversion[]" id="conversion_<?php echo $batch_med_id; ?>" /><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value="<?php echo $row_batch_val; ?>"><input type="hidden" id="mbid_<?php echo $batch_med_id; ?>" name="mbid[]" value="<?php echo $row_varids; ?>"/><input type="hidden" id="purchase_rate_mrp<?php echo $batch_med_id; ?>" name="purchase_rate_mrp[]" value="<?php echo $medicine_data['mrp']; ?>"/></td><td><?php echo $medicine_data['medicine_name']; ?><input type="hidden" id="medicine_name<?php echo $batch_med_id; ?>" name="medicine_name[]" value="<?php echo $medicine_data['medicine_name']; ?>"/></td><td><?php echo $medicine_data['medicine_code']; ?><input type="hidden" id="medicine_code<?php echo $batch_med_id; ?>" name="medicine_code[]" value="<?php echo $medicine_data['medicine_code']; ?>"/></td><td><input type="text" id="hsn_no_<?php echo $batch_med_id; ?>" name="hsn_no[]" value="<?php echo $medicine_data['hsn_no']; ?>" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><?php echo $medicine_data['packing']; ?></td><td><?php echo $medicine_data['batch_no']; ?><input type="hidden" name="batch_no[]" value="<?php echo $medicine_data['batch_no']; ?>" id="batch_no_<?php echo $batch_med_id; ?>"></td><td><input type="text" value="<?php echo date('d-m-Y',strtotime($medicine_data['manuf_date'])); ?>" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_<?php echo $batch_med_id; ?>" /><?php echo $check_script1; ?></td><td><input type="text" value="<?php echo date('d-m-Y',strtotime($medicine_data['expiry_date'])); ?>" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_<?php echo $batch_med_id; ?>" /><?php echo $check_script; ?><div id="expiry_date_error_<?php echo $batch_med_id; ?>"  style="color:red;"></div></td><td><input type="text"  value="<?php echo $medicine_data['bar_code']; ?>" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');validation_bar_code('<?php echo $batch_med_id; ?>');"/><div  id="barcode_error_<?php echo $batch_med_id; ?>"  style="color:red;"></td><td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_<?php echo $batch_med_id; ?>" value="<?php echo $medicine_data['qty']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/><div  id="unit1_error_<?php echo $batch_med_id; ?>"  style="color:red;"></div></td><td><input type="text" id="mrp_<?php echo $batch_med_id; ?>" name="mrp[]" value="<?php echo $medicine_data['per_pic_amount']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_<?php echo $batch_med_id; ?>" value="<?php echo $medicine_data['discount']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="<?php echo $medicine_data['cgst']; ?>" id="cgst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="<?php echo $medicine_data['sgst']; ?>" id="sgst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="<?php echo $medicine_data['igst']; ?>" id="igst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" value="<?php echo $medicine_data['total_amount']; ?>" name="row_total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_<?php echo $batch_med_id; ?>" /></td></tr>
                                
                                <?php
                            $i++;    
                            }
                       }
                       ?>
                       
                       <!-- sales medicine for return --->
                       
                       <?php 
                       //echo "<pre>"; print_r($medicne_list); exit;
                       if(!empty($sale_medicne_list))
                       {
                            $i=1;
                            foreach($sale_medicne_list as $medicine_data)
                            {
                                //echo $medicine_data["manuf_date"];
                                //echo "<pre>"; print_r($medicine_data); exit;
                               $batch_med_id = $medicine_data['mid'].$medicine_data['batch_no'];
                                
                                $varids="'".$medicine_data['mid'].$medicine_data['batch_no']."'";
                                
                                $value="'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
                                
                                $row_varids=$medicine_data['mid'].'.'.$medicine_data['batch_no'];
                               $batch_no =  $medicine_data['batch_no'];
                               
                               if($medicine_data["exp_date"]=="00-00-0000" || $medicine_data["exp_date"]=='01-01-1970')
                             {

                                $date_new='';
                            }else{
                                $date_new=$medicine_data["exp_date"];
                            }
                            
                            if($medicine_data["manuf_date"]=="00-00-0000" || $medicine_data["manuf_date"]=='01-01-1970')
                             {

                                $manufdate='';
                            }else{
                                $manufdate=$medicine_data["manuf_date"];
                            }
                           
                            /*$('#discount_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                                 alert('Discount should be less then 100');
                            }
                          });*/
                                
                                $check_script= "<script>
                          var today = new Date();
                          $('#expiry_date_".$medicine_data['mid'].$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                          startDate: '".$date_new."'
                          });</script>";
                          $check_script1= "<script>
                          var today = new Date();
                          $('#manuf_date_".$medicine_data['mid'].$batch_no."').datepicker({
                          format: 'dd-mm-yyyy',
                          autoclose: true,
                           endDate: '".$date_newmanuf."',
                          });
                         
                           
                           
                           
                          $('#cgst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('CGST should be less then 100' );
                                 
                            }
                          });
                           $('#igst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('IGST should be less then 100' );
                                 
                            }
                          });
                           $('#sgst_".$medicine_data['mid'].$batch_no."').keyup(function(e){
                            if ($(this).val() > 100){
                              alert('SGST should be less then 100' );
                                 
                            }
                          });
                          </script>";
                                
                                
                            
                            
                            
                                
                                ?>
                                
                                <tr id="prescription_tr_<?php echo $batch_med_id; ?>"><td><input type="hidden" id="per_pic_amount<?php echo $batch_med_id; ?>" name="per_pic_amount[]" value="<?php echo $medicine_data['per_pic_amount']; ?>"><input type="hidden" id="sale_amount<?php echo $batch_med_id; ?>" name="sale_amount[]" value="<?php echo $medicine_data['sale_amount']; ?>"/><input class="my_medicine_purchase" type="hidden" id="medicine_id_<?php echo $batch_med_id; ?>" name="m_id[]" value="<?php echo $batch_med_id; ?>"/><input type="hidden" id="medicine_sel_id_<?php echo $batch_med_id; ?>" name="medicine_sel_id[]" value="<?php echo $medicine_data['mid']; ?>"/><input type="hidden" value="<?php echo $medicine_data['conversion']; ?>"  name="conversion[]" id="conversion_<?php echo $batch_med_id; ?>" /><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value="<?php echo $batch_med_id; ?>" data-id="<?php echo $row_varids; ?>"><input type="hidden" id="mbid_<?php echo $batch_med_id; ?>" name="mbid[]" value="<?php echo $row_varids; ?>"/><input type="hidden" id="purchase_rate_mrp<?php echo $batch_med_id; ?>" name="purchase_rate_mrp[]" value="<?php echo $medicine_data['mrp']; ?>"/></td><td><?php echo $medicine_data['medicine_name']; ?><input type="hidden" id="medicine_name<?php echo $batch_med_id; ?>" name="medicine_name[]" value="<?php echo $medicine_data['medicine_name']; ?>"/></td><td><?php echo $medicine_data['medicine_code']; ?><input type="hidden" id="medicine_code<?php echo $batch_med_id; ?>" name="medicine_code[]" value="<?php echo $medicine_data['medicine_code']; ?>"/></td><td><input type="text" id="hsn_no_<?php echo $batch_med_id; ?>" name="hsn_no[]" value="<?php echo $medicine_data['hsn_no']; ?>" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><?php echo $medicine_data['packing']; ?></td><td><?php echo $medicine_data['batch_no']; ?><input type="hidden" name="batch_no[]" value="<?php echo $medicine_data['batch_no']; ?>" id="batch_no_<?php echo $batch_med_id; ?>"></td><td><input type="text" value="<?php echo $manufdate; ?>" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_<?php echo $batch_med_id; ?>" onchange="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/><?php echo $check_script1; ?></td><td><input type="text" value="<?php echo $date_new; ?>" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_<?php echo $batch_med_id; ?>" onchange="payment_cal_perrow(<?php echo $batch_med_id; ?>);"/><?php echo $check_script; ?><div id="expiry_date_error_<?php echo $batch_med_id; ?>"  style="color:red;"></div></td><td><input type="text"  value="<?php echo $medicine_data['bar_code']; ?>" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');validation_bar_code('<?php echo $batch_med_id; ?>');"/><div  id="barcode_error_<?php echo $batch_med_id; ?>"  style="color:red;"></td><td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_<?php echo $batch_med_id; ?>" value="<?php echo $medicine_data['qty']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>'),validation_check(qty,'<?php echo $batch_med_id; ?>');"/><div  id="unit1_error_<?php echo $batch_med_id; ?>"  style="color:red;"></div></td><td><input type="text" id="mrp_<?php echo $batch_med_id; ?>" name="mrp[]" value="<?php echo $medicine_data['per_pic_amount']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_<?php echo $batch_med_id; ?>" value="<?php echo $medicine_data['discount']; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="<?php echo $medicine_data['cgst']; ?>" id="cgst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="<?php echo $medicine_data['sgst']; ?>" id="sgst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="<?php echo $medicine_data['igst']; ?>" id="igst_<?php echo $batch_med_id; ?>" onkeyup="payment_cal_perrow('<?php echo $batch_med_id; ?>');"/></td><td><input type="text" value="<?php echo $medicine_data['total_amount']; ?>" name="row_total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_<?php echo $batch_med_id; ?>" /></td></tr>
                                
                                <?php
                            $i++;    
                            }
                       }
                       ?>
                       <!-- sales medicine for return end -->
                            
                </tbody>
            </table>
        </div>
           <div class="right"><a class="btn-new" onclick="medicine_list_vals();">Delete</a></div>
      
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

                
                <div class="purchase_medicine_mod_of_payment">
            <label>Discount</label>
            <div class="grp m7">

              <select id="discount_type" style="width: 40px;height: 24px;"onchange="payment_calc_overall()">

               <option value="0">%</option>

               <option value="1"<?php if(($form_data['discount_percent'] == $form_data['discount_amount']) && ($form_data['discount_percent']) >0){ echo 'selected';}?>>₹</option>            
             </select>

             <input class="input-tiny m8 price_float" name="discount_percent" type="text" value="<?php echo $form_data['discount_percent'];?>" id="discount_all" onKeyUp="payment_calc_overall();" placeholder="">

             <input style="width: 156px;" class="m9" type="text" name="discount_amount"  id="discount_amount" value="<?php echo $form_data['discount_amount'];?>" readonly>
           </div>
         </div>
                


        <div class="sale_medicine_mod_of_payment">
             <label>Net Amount</label>
            <input type="text" name="net_amount"  id="net_amount" onKeyUp="payment_calc_all();" readonly value="<?php echo $form_data['net_amount'];?>">
        </div>
                      

                <div class="sale_medicine_mod_of_payment">
                    <label>Refund Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount"  id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_vals(1);">
                     <div class="f_right"><?php if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                     <input type="text" name="balance_due"  id="balance_due"  value="<?php echo $balance= ($form_data['net_amount']-$form_data['pay_amount']);//-$form_data['discount_amount']; ?>" class="price_float" readonly >
                   
                </div>

            </div> <!-- right_box -->

            
        </div> <!-- left -->
        
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
/*function set_template(ids)
 {
    if(ids!='')
    {
      window.location.href='< ?php echo current_url(); ?>?tid='+ids;
    }
 }*/

$(document).on('focus', 'input[type=text]', function(){
    var $input = $(this);
    if ($input.val() == "0")
    {
        $input.val("");
    }
    if ($input.val() == "0.00")
    {
        $input.val("");
    }
});
function get_others(val)
{
  
  if(val=='0')
  {
    $("#ref_by_other").css("display","block");
  }
  else
  {
    $("#ref_by_other").css("display","none");
  }
}
function father_husband_son()
{
   //$("#relation_name").css("display","block");
}
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
           $('#ref_other').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
});

$('#sales_return_submit').click(function(){  
    
    var patinet_name = $('#patinet_name').val();
    if(patinet_name=='')
    {
        alert('Please enter the patient name!');
        return false;
    }
    
    var z=true;
    
    if(item_purchase_row_error()==false)
    {
        z=false;
    }
    
    if(z==true)
    {
        $(':input[id=sales_return_submit]').prop('disabled', true);
        $('#sales_return_form').submit();
    }
    else
    {
        return false;
    }
});


function item_purchase_row_error()
{
    var expiry_error = true;
    var qty_error=true;
    var expiry_expir_error = true;
    var purc_date = $('#sales_date').val();
    var data_ids = get_ids();
    $.each(data_ids,function(index,value){
       
       if(($('#qty_'+value).val()=="" || $('#qty_'+value).val()=="0"))
       {
           $('#unit1_error_'+value).show().html('Qty is required!');
           qty_error=false;
           
       }
       
       if($('#expiry_date_'+value).val()=="" || $('#expiry_date_'+value).val()=="01-01-1970")
       {
           $('#expiry_date_error_'+value).show().html('Expiry is required!');
           expiry_error=false;
       }
       
        var purch_date = purc_date.split("-");;
        var row_date = $('#expiry_date_'+value).val().split("-");
        //var purch_date = purc_date.split("-");
        var txtDate = new Date(row_date[2], row_date[1] - 1, row_date[0]);
        var purcDate = new Date(purch_date[2], purch_date[1] - 1, purch_date[0]);
        //alert(txtDate - purcDate);
        if (txtDate - purcDate < 0) 
        {
          $('#expiry_date_error_'+value).show().html('Invalid Expiry!');
          expiry_expir_error=false;
        }
       
        
    });
    
    
    if(expiry_error==false || qty_error==false || expiry_expir_error==false)
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
/*$('#sales_return_submit').click(function(){ 
    
    var patient_name = $('#patinet_name').val();
    if(patient_name=='')
    {
        alert('Please enter the Patient name!');
        return flase;
    }
    $(':input[id=sales_return_submit]').prop('disabled', true);
   $('#sales_return_form').submit();
});*/

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
         async:true,
        type: "POST",
        url: "<?php echo base_url('sales_return_medicine/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
 }
     


$(document).ready(function(){
    
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
        async:true,
       type: "POST",
       url: "<?php echo base_url('sales_return_medicine/ajax_list_medicine')?>",
       data: {'medicine_name' : medicine_name,'medicine_code':medicine_code,'medicine_company':medicine_company,'stock':stock,'qty':qty,'rate':rate,'discount':discount,'cgst':cgst,'sgst':sgst,'hsn_no':hsn_no,'igst':igst,'packing':packing,'batch_number':batch_number,'bar_code':bar_code},
       dataType: "json",
       success: function(msg){
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

    var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              $(this).closest("tr").hide();
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
       if(allVals!="")
       {
            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('sales_return_medicine/set_new_medicine');?>",
                  data: {medicine_id: allVals},
                  dataType: "json",
                  success: function(result) 
                  { 
                     

                    var data_res = result.data;
                    if($('#prescription_tr_'+data_res.row_varids).length)
                    {
                        alert("This medicine already added.");
                        return false;
                    } 
                    $("#append_data_row").remove();
                    var i=$('#medicine_per_row tr').length;
                    $('#medicine_per_row').append('<tr id="prescription_tr_'+data_res.row_varids+'"><td><input type="hidden" id="per_pic_amount'+data_res.row_varids+'" name="per_pic_amount[]" value="'+data_res.per_pic_amount+'"><input type="hidden" id="sale_amount'+data_res.row_varids+'" name="sale_amount[]" value="'+data_res.sale_amount+'"/><input class="my_medicine_purchase" type="hidden" id="medicine_id_'+data_res.row_varids+'" name="m_id[]" value="'+data_res.row_varids+'"/><input type="hidden" id="medicine_sel_id_'+data_res.row_varids+'" name="medicine_sel_id[]" value="'+data_res.mid+'"/><input type="hidden" value="'+data_res.conversion+'"  name="conversion[]" id="conversion_'+data_res.row_varids+'" /><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value='+data_res.row_varids+'><input type="hidden" id="mbid_'+data_res.row_varids+'" name="mbid[]" value='+data_res.vals+'/><input type="hidden" id="purchase_rate_mrp'+data_res.row_varids+'" name="purchase_rate_mrp[]" value="'+data_res.mrp+'"/></td><td>'+data_res.medicine_name+'<input type="hidden" id="medicine_name'+data_res.row_varids+'" name="medicine_name[]" value="'+data_res.medicine_name+'"/></td><td>'+data_res.medicine_code+'<input type="hidden" id="medicine_code'+data_res.row_varids+'" name="medicine_code[]" value="'+data_res.medicine_code+'"/></td><td><input type="text" id="hsn_no_'+data_res.row_varids+'" name="hsn_no[]" value="'+data_res.hsn_no+'" /></td><td>'+data_res.packing+'</td><td>'+data_res.batch_no+'<input type="hidden" name="batch_no[]" value="'+data_res.batch_no+'" id="batch_no_'+data_res.row_varids+'"></td><td><input type="text" value="" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'+data_res.row_varids+'"/>'+data_res.manuf_script+'</td><td><input type="text" value="" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'+data_res.row_varids+'" />'+data_res.check_scrip+'<div id="expiry_date_error_'+data_res.row_varids+'"  style="color:red;"></div></td><td><input type="text"  value="'+data_res.bar_code+'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'+data_res.row_varids+'" onkeyup="payment_cal_perrow('+data_res.varids+');validation_bar_code('+data_res.varids+');"/><div  id="barcode_error_'+data_res.row_varids+'"  style="color:red;"></td><td><input type="text" name="quantity[]" placeholder="Qty" style="width:59px;" id="qty_'+data_res.row_varids+'" value="'+data_res.qty+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/><div  id="unit1_error_'+data_res.row_varids+'"  style="color:red;"></div></td><td><input type="text" id="mrp_'+data_res.row_varids+'" name="mrp[]" value="'+data_res.per_pic_amount+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'+data_res.row_varids+'" value="'+data_res.discount+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'+data_res.cgst+'" id="cgst_'+data_res.row_varids+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'+data_res.sgst+'" id="sgst_'+data_res.row_varids+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'+data_res.igst+'" id="igst_'+data_res.row_varids+'" onkeyup="payment_cal_perrow('+data_res.varids+');"/></td><td><input type="text" value="'+data_res.total_amount+'" name="row_total_amount[]" placeholder="Total" style="width:59px;" readonly id="total_amount_'+data_res.row_varids+'" /></td></tr>');
                      
                  }
              });
       }      
  }

  function list_added_medicine()
  {
    $.ajax({
        async:true,
                    url: "<?php echo base_url(); ?>sales_return_medicine/ajax_added_medicine", 
                    dataType: "json",
                   success: function(result)
                    {
                      $('#medicine_select').html(result.data); 
                      payment_calc_all();
                   } 
                 });   
  }

  function medicine_list_vals_old() 
  {      
  
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
              //$(this).closest("tr").hide();
         } 
       });
       if(allVals!=""){
       remove_medicine(allVals);
       //search_func();
        }
  
  } 
  
  function medicine_list_vals() 
  {      
      $('.booked_checkbox').each(function() 
       {
           
         if($(this).prop('checked')==true)
         {
             
            var id = $(this).val();
            
            /*if (1 == $("#medicine_per_row > tbody > tr").length) 
            {
                alert("There only one row you can't delete.");
            }
        	else 
        	{*/
        	    
        	    $("#prescription_tr_"+id).remove();
            //}
              
         } 
         payment_calc_overall();
       });
       
        
  
  }
   function payemt_vals(pay)
  {
     var timerA = setInterval(function(){  
          payment_calc_overall();
          clearInterval(timerA); 
        }, 80);
  }
 
 function payment_calc_overall()
 {
     
            var discount_type=0;//$post['discount_type']; //total_gstcsg
            var total_amount_cal = 0;
            var total_cgst_cal = 0;
            var total_igst_cal = 0;
            var total_sgst_cal = 0;
            var tot_discount_cal=0;
            var tot_discount_amount_cal=0;
            var total_discount_cal =0;
            var totsgst_amount_cal=0;
            var net_amount_cal =0; 
            var payamount_cal=0; 
            var purchase_amount_cal=0;
            var total_amountwithigst_cal=0;
            var total_amountwithigst_cal=0;
            var newamountwithcgst_cal=0;
            var total_new_amount_cal=0;
            var total_price_medicine_amount=0;
            var discount_setting = $('#discount_setting').val();
            
                  $('.my_medicine_purchase').each(function() 
                  {
                     var i = $(this).val();
                     var mrp = $('#mrp_'+i).val();
                     var qty = $('#qty_'+i).val();
                     var cgst = $('#cgst_'+i).val();
                     var sgst = $('#sgst_'+i).val();
                     var igst = $('#igst_'+i).val();
                     var discount = $('#discount_'+i).val();
                     var tot_qty_with_rate= mrp*qty;
                       if(discount_setting==1)
                        {
                          var total_row_discount = discount;
                        }
                        else
                        {
                           var total_row_discount = (discount/100)*tot_qty_with_rate;
                        }
                     
                    var total_amount = tot_qty_with_rate-total_row_discount;
                    var cgst_initial = $('#cgst_'+i).val();
                    if(cgst_initial != "") 
                    {
                        var cgst_initial=parseInt(cgst_initial);  
                    }
                    else
                    {
                        var cgst_initial=0;  
                    }
                    var sgst_initial = $('#sgst_'+i).val();
                    if(sgst_initial != "") 
                    {
                        var sgst_initial=parseInt(sgst_initial);  
                    }
                    else
                    {
                    var sgst_initial=0;  
                    }
                    var igst_initial = $('#igst_'+i).val();
                    if(igst_initial != "") 
                    {
                        var igst_initial=parseInt(igst_initial);  
                    }
                    else
                    {
                    var igst_initial=0;  
                    }
                    
                    var gst_per_total = parseInt(cgst_initial)+parseInt(sgst_initial)+parseInt(igst_initial);
                    var gst_cal =total_amount-(total_amount/((100+gst_per_total)/100));
                    
                    if(cgst_initial>0)
                    {
                        var cgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var cgst_cal_val = 0; 
                    }
                    
                    if(sgst_initial>0)
                    {
                        var sgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var sgst_cal_val = 0; 
                    }
                    
                    if(igst_initial>0)
                    {
                        var igst_cal_val = gst_cal;
                    }
                    else
                    {
                       var igst_cal_val = 0; 
                    }
                    
                    total_cgst_cal += cgst_cal_val; 
                    total_sgst_cal += sgst_cal_val;
                    total_igst_cal += igst_cal_val;
                        
                     //end gst
                    
                    var total_tax =  total_cgst_cal+total_igst_cal+total_sgst_cal;
                     total_price_medicine_amount +=total_amount; //-total_tax;
                     tot_discount_amount_cal+= total_row_discount;//(((mrp)/100)*$('#discount_'+i).val());
                     var row_total_amount = $('#total_amount_'+i).val();
                     total_amount_cal +=parseFloat(row_total_amount);
                    
                  });
                  
                  
                    //var g_total = (total_price_medicine_amount+(total_cgst_cal+total_sgst_cal+total_igst_cal))-tot_discount_amount_cal;
                    var total_gstcsg = total_cgst_cal+total_sgst_cal+total_igst_cal;
                    
                    var g_total =(total_amount_cal); //+total_gstcsg
                    var pay_amount = $('#pay_amount').val();
                    var discount_all = $('#discount_all').val();
                    var discount_type = $('#discount_type').val(); 
                    //var net_amount = g_total;
                    if(discount_type==1)
                    {
                        if(Number(g_total)>0)
                        {
                            if(discount_all > Number(g_total)){
                              alert('Discount should be less then net amount!');
                              $('#discount_all').val('0.00');
                              return false;
                           } 
                        }
                        var total_discount = discount_all;
                    }
                    else
                    {
                         if(Number(discount_all) > 100)
                         {
                              alert('Discount should be less then 100');
                              $('#discount_all').val('0.00');
                              return false;
                         }
                       var total_discount = (discount_all/100)*g_total;
                    }
                    var net_amount  = g_total-total_discount;
                    var g_total =(g_total-total_discount);
                    if(pay_amount!='')
                    {
                        var balance_final = g_total-pay_amount;
                        var payamt = pay_amount;
                    }
                    else
                    {
                        var balance_final = g_total-pay_amount;
                        var payamt = pay_amount;
                    }
                    
                    
                    $('#discount_amount').val(Number(total_discount).toFixed(2,2));
                    
                    $('#total_amount').val(total_price_medicine_amount.toFixed(2, 2));
                    $('#net_amount').val(net_amount.toFixed(2, 2));
                    
                    //$('#pay_amount').val(g_total.toFixed(2, 2));
                    $('#cgst_amount').val(total_cgst_cal.toFixed(2, 2));
                    $('#igst_amount').val(total_igst_cal.toFixed(2, 2));
                    $('#sgst_amount').val(total_sgst_cal.toFixed(2, 2));
                    
                    
                    $('#discount_all').val(discount_all);
                	//$('#pay_amount').val(Number(paytotal).toFixed(2, 2));
                	$('#pay_amount').val(Number(payamt).toFixed(2));
                	$('#balance_due').val(Number(balance_final).toFixed(2, 2));
                    
                    
                    //$('#discount_all').val('0');
                    //$('#balance_due').val('0.00');
                    $('#medicine_discount').val(Number(tot_discount_amount_cal).toFixed(2,2));
     
 }
  
  
  
  function remove_medicine(allVals)
  { 
   if(allVals!="")
   {
    //$(this).closest("tr").remove();  
   	//alert($(this).html());
      $.ajax({
          async:true,
              type: "POST",
              url: "<?php echo base_url('sales_return_medicine/remove_medicine_list');?>",
               //dataType: "json",payment_cal_perrow
              data: {medicine_id: allVals},
              success: function(result) 
              {  
                 $('#medicine_select').html(result.data); 
                    search_func();
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


function payment_cal_perrow(ids)
 {
     
    var initial_pay_amount = $("#pay_amount").val();
    var purchase_rate = $("#purchase_rate_mrp"+ids).val();
    var mrp = $("#mrp_"+ids).val();
    var qty = $("#qty_"+ids).val();
    var hsn_no = $("#hsn_no_"+ids).val();
    var medicine_id= $("#medicine_id_"+ids).val();
    var mbid= $("#mbid_"+ids).val();
    var expiry_date= $("#expiry_date_"+ids).val();
    var bar_code= $("#bar_code_"+ids).val();
    var manuf_date= $("#manuf_date_"+ids).val();
    var batch_no= $("#batch_no_"+ids).val();
    var vat= $("#vat_"+ids).val();
    var igst= $("#igst_"+ids).val();
    var cgst= $("#cgst_"+ids).val();
    var sgst= $("#sgst_"+ids).val();
    var discount= $("#discount_"+ids).val();
    var conversion= $("#conversion_"+ids).val();
    
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>sales_return_medicine/payment_cal_perrow/", 
                dataType: "json",
                data: 'mbid='+mbid+'&purchase_rate='+purchase_rate+'&qty='+qty+'&medicine_id='+medicine_id+'&expiry_date='+expiry_date+'&igst='+igst+'&cgst='+cgst+'&sgst='+sgst+'&discount='+discount+'&manuf_date='+manuf_date+'&batch_no='+batch_no+'&conversion='+conversion+'&mrp='+mrp+'&hsn_no='+hsn_no+'&bar_code='+bar_code,
                success: function(result)
                {
                   
                var row_tot = result.total_pricewith_medicine;
                $('#total_amount_'+ids).val(row_tot.toFixed(2,2));
                var discount_type=0;
                var total_amount_cal = 0;
                var total_cgst_cal = 0;
                var total_igst_cal = 0;
                var total_sgst_cal = 0;
                var tot_discount_cal=0;
                var tot_discount_amount_cal=0;
                var total_discount_cal =0;
                var totsgst_amount_cal=0;
                var net_amount_cal =0; 
                var payamount_cal=0; 
                var purchase_amount_cal=0;
                var total_amountwithigst_cal=0;
                var total_amountwithigst_cal=0;
                var newamountwithcgst_cal=0;
                var total_new_amount_cal=0;
                var total_price_medicine_amount=0;
                var discount_setting = $('#discount_setting').val();
                  
                  $('.my_medicine_purchase').each(function() 
                  {
                     var i = $(this).val();
                     var mrp = $('#mrp_'+i).val();
                     var qty = $('#qty_'+i).val();
                     var cgst = $('#cgst_'+i).val();
                     var sgst = $('#sgst_'+i).val();
                     var igst = $('#igst_'+i).val();
                     var tot_qty_with_rate= mrp*qty;
                     var discount = $('#discount_'+i).val();
                     if(discount_setting==1)
                        {
                          var total_discount = discount;
                        }
                        else
                        {
                           var total_discount = (discount/100)*tot_qty_with_rate;
                        }
                        
                    if(total_discount!='')
                    {
                       total_discount = Number(total_discount);  
                    }
                    else
                    {
                        total_discount = 0;
                    }
                    // alert(total_discount);
                     var total_amount = tot_qty_with_rate-total_discount;
                     
                    //gst
                    var cgst_initial = $('#cgst_'+i).val();
                    if(cgst_initial != "") 
                    {
                        var cgst_initial=parseInt(cgst_initial);  
                    }
                    else
                    {
                        var cgst_initial=0;  
                    }
                    var sgst_initial = $('#sgst_'+i).val();
                    if(sgst_initial != "") 
                    {
                        var sgst_initial=parseInt(sgst_initial);  
                    }
                    else
                    {
                    var sgst_initial=0;  
                    }
                    var igst_initial = $('#igst_'+i).val();
                    if(igst_initial != "") 
                    {
                        var igst_initial=parseInt(igst_initial);  
                    }
                    else
                    {
                    var igst_initial=0;  
                    }
                    var gst_per_total = parseInt(cgst_initial)+parseInt(sgst_initial)+parseInt(igst_initial);
                    //var gst_cal = (total_amount -(total_amount*(100/(100+(gst_per_total)/100))))*100;
                     
                    var gst_cal =total_amount-(total_amount/((100+gst_per_total)/100));
                    
                    if(cgst_initial>0)
                    {
                        var cgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var cgst_cal_val = 0; 
                    }
                    
                    if(sgst_initial>0)
                    {
                        var sgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var sgst_cal_val = 0; 
                    }
                    
                    if(igst_initial>0)
                    {
                        var igst_cal_val = gst_cal;
                    }
                    else
                    {
                       var igst_cal_val = 0; 
                    }
                    
                    total_cgst_cal += cgst_cal_val; 
                    total_sgst_cal += sgst_cal_val;
                    total_igst_cal += igst_cal_val;
                        
                    
                     
                     //end gst
                     var total_tax =  total_cgst_cal+total_igst_cal+total_sgst_cal;
                     total_price_medicine_amount +=total_amount; //-total_tax;
                     //tot_discount_amount_cal += (((mrp)/100)*$('#discount_'+i).val());
                     
                     tot_discount_amount_cal += total_discount;
                    
                  });
                  
                     var g_total =total_price_medicine_amount;//-tot_discount_amount_cal;
                    //alert(tot_discount_amount_cal);
                    if(initial_pay_amount>g_total)
                    {
                        var new_bal = '0.00';
                        var pay_initial = g_total;
                    }
                    else
                    {
                        var new_bal = g_total-initial_pay_amount;
                        var pay_initial = initial_pay_amount;
                    }
                    
                    $('#discount_amount').val('0');
                    $('#total_amount').val(Number(total_price_medicine_amount).toFixed(2, 2));
                    $('#net_amount').val(g_total.toFixed(2, 2));
                    $('#pay_amount').val(Number(pay_initial).toFixed(2, 2));
                    $('#cgst_amount').val(total_cgst_cal.toFixed(2, 2));
                    $('#igst_amount').val(total_igst_cal.toFixed(2, 2));
                    $('#sgst_amount').val(total_sgst_cal.toFixed(2, 2));
                    $('#discount_all').val('0');
                    $('#balance_due').val(Number(new_bal).toFixed(2, 2));
                    $('#medicine_discount').val(Number(tot_discount_amount_cal).toFixed(2,2));
                  
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
          async:true,
      type: "POST",
      url: "<?php echo base_url(); ?>sales_return_medicine/payment_calc_all/", 
      dataType: "json",
       data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
          if(result.discount!='' || typeof(result.discount)!=undefined)
          {
              var discounta = result.discount;
          }
          else
          {
              var discounta = 0;
          }
          $('#discount_amount').val(result.discount_amount);
          $('#total_amount').val(result.total_amount);
          $('#net_amount').val(result.net_amount);
          $('#pay_amount').val(result.pay_amount);
          $('#cgst_amount').val(result.cgst_amount);
          $('#sgst_amount').val(result.sgst_amount);
          $('#igst_amount').val(result.igst_amount);
          $('#discount_all').val(discounta);
          //$('#vat_percent').val(result.vat);
          $('#balance_due').val(result.balance_due);
      } 
    });
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
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($doctors_list)) || (empty($simulation_list)) || (empty($referal_hospital_list)))
{
  
?>  

 
  $('#sales_medicine_return_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 

}
}
?>

});
</script>
<script>
$("button[data-number=4]").click(function(){
    $('#sales_medicine_return_count').modal('hide');
   /* $(this).hide();*/
});
</script>

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="sales_medicine_return_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($doctors_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Doctor is required.</span></p><?php } ?>
           <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Doctor is required.</span></p><?php } ?>
         <?php if(empty($referal_hospital_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Hospital is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    
</div>
<style>

.ui-autocomplete { z-index:2147483647; }
</style>


<script type="text/javascript">
    $(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('sales_return_medicine/search_sales/'); ?>" + request.term,
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

    var selectItem = function (event, ui) 
    {
           
            var names = ui.item.data.split("|");
            $('#sales_no').val(names[0]);
            $('#sales_id').val(names[1]);
            
            window.location.href='<?php echo current_url(); ?>?sid='+names[1];
            
            /*$('#referred_by').val(names[2]);
            $('#referral_hospital').val(names[3]);
            $('#ref_by_other_doctor').val(names[4]);
            $('#ref_by_other_hospital').val(names[5]);
            $('#refered_id').val(names[6]);
            $('#ref_by_other').val(names[7]);
            $('#total_amount').val(names[8]);
            $('#net_amount').val(names[9]);
            $('#pay_amount').val(names[10]);
            $('#balance_due').val(names[11]);
            $('#remarks').val(names[12]);
            $('#discount_amount').val(names[13]);
            $('#sgst_amount').val(names[14]);
            $('#cgst_amount').val(names[15]);
            $('#igst_amount').val(names[16]);
            $('#discount_percent').val(names[17]);
            $('#vat_percent').val(names[18]);
            $('#payment_mode').val(names[19]);
            $('#simulation_id').val(names[20]);
            $('#patinet_name').val(names[21]);
            $('#patient_id').val(names[22]);
            $('#mobile').val(names[23]);
            $('#email').val(names[24]);
            $('#relation_type').val(names[25]);
            $('#relation_simulation_id').val(names[26]);
            $('#relation_name').val(names[27]);
            $('#gender').val(names[28]);
            if(names[28]=='1')
            {
                $("#male_gender").attr('checked', 'checked');
            }
            else if(names[28]=='0')
            {
                $("#female_gender").attr('checked', 'checked');
            }
            else if(names[28]=='2')
            {
                $("#other_gender").attr('checked', 'checked');
            }
            set_sales_medicine(names[1]);*/
            return false;


    }

    $("#sales_no").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            
        }
    });
    });


  function set_sales_medicine(allVals)
  { 
  //alert(allVals );  
     if(allVals!="")
     {
        
        $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_medicine/get_sales_medicine');?>",
              data: {sales_id: allVals},
              dataType: "json",
              success: function(result) 
              {
                $('#medicine_select').html(result.data); 
                //list_purchase_medicine();
                payment_calc_all();  
              }
        });
     }      
  }

  
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 $sales_id = $this->session->userdata('sales_id');
 ?>
</script> 

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && isset($sales_id) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('sales_return_medicine/add/');?>'; 
    }) ;
   
       
  <?php }?>
 });
</script>

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
         
          <div class="modal-footer">
           <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("sales_return_medicine/print_sales_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>