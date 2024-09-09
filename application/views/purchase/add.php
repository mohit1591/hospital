<?php
$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
$users_data = $this->session->userdata('auth_users'); 
$field_list = mandatory_section_field_list(10);
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>

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
                <th width="100"  class="text-center">Medicine Name</th>
                <th class="text-center">Vendor Name</th>
                <th class="text-center">Parchase Date</th>
                <th class="text-center">Purchase Rate</th>
            </tr>
            
        </table>
   
    </div> 
</div>
</div>
</div>
<section class="userlist">
 
   <form id="purchase_form" action="<?php echo current_url(); ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
    
        <input type="hidden" name="discount_setting" id="discount_setting" value="<?php echo $discount_setting;?>" />
    
    
<div class="purchase_medicine">
    <div class="row">
        <div class="col-md-12">
            <div class="grp">
            <?php if(!empty($form_data['vendor_id'])){?>
                 <span class="new_vendor"><input type="radio" name="new_vendor" onClick="window.location='<?php echo base_url('purchase/');?>add/';"> <label>New Vendor</label></span>
                <span class="new_vendor"><input type="radio" name="new_vendor" checked onClick="window.location='<?php echo base_url('vendor');?>';"> <label>Registered Vendor</label></span>
               <?php  }else{?>
                 <span class="new_vendor"><input type="radio" name="new_vendor" checked onClick="window.location='<?php echo base_url('purchase/');?>add/';"> <label>New Vendor</label></span>
                <span class="new_vendor"><input type="radio" name="new_vendor" onClick="window.location='<?php echo base_url('vendor');?>';"> <label>Registered Vendor</label></span>
                    <?php }?>

              <!--<input type="text" name="estimate_no" id="estimate_no" placeholder="Search Purchase Estimate no" class="estimate_no"> <label></label>-->
          
               
            </div>
        </div>
    </div>    <!-- endRow -->



<div class="row">
    
    <div class="col-md-4">
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label><input type="hidden" name="vendor_id" value="<?php if(isset($form_data['vendor_id'])){echo $form_data['vendor_id'];}else{ echo '';}?>"/> Vendor Code</label>
            </div>
            <div class="col-sm-8">
                <input type="hidden" value="<?php echo $form_data['vendor_code'];?>" name="vendor_code" />
                <div class="vendor_code"><b><?php echo $form_data['vendor_code'];?></b></div>
            </div>
        </div>
        
       
        <div class="row m-b-5">
             <div class="col-sm-4"><label>Invoice no <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==51 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-8">
                 
                <input type="text" class="m_input_default" name="invoice_id" value="<?php echo $form_data['invoice_id'];?>" autofocus="">
                    <div class="">
                    <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='51' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('invoice_id'); }
                         }
                    }
          ?>
                    </div>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label>purchase no.</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="m_input_default" name="purchase_no" value="<?php echo $form_data['purchase_no'];?>">
            </div>
        </div>
        
      

       <div class="row m-b-5">
             <div class="col-sm-4 pr-0"><label>Vendor name <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==52 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-8">
                 
               <input type="text" name="name" class="alpha_numeric_space m_input_default txt_firstCap" id="ven_name" value="<?php echo $form_data['name'];?>">
                    <div class="">
                    <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='52' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('name'); }
                         }
                    }
          ?>
                    </div>
            </div>
        </div>
        
        
        
        <div class="row m-b-5">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"></div>
        </div>

    </div> <!-- //4 -->




    
    <div class="col-md-4">
        
        <div class="row m-b-5">
            <div class="col-sm-4"><label>Mobile No. <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==53 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-8">
                 <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91" style="width:59px;"> 
                <input type="text" name="mobile" class="number m_number" id="mobile_no" maxlength="10" value="<?php echo $form_data['mobile'];?>" onKeyPress="return isNumberKey(event);">
                    <div class="">
                    <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='53' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile'); }
                         }
                    }
          ?>
                    </div>
            </div>
        </div>

        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label>email id</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="m_input_default" name="email" value="<?php echo $form_data['email'];?>">
                <?php if(!empty($form_error)){ echo form_error('email'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-4">
                <label style="width:39%;">purchase date/time</label>
            </div>
            <div class="col-sm-8">
                <input type="text" id="purchase_date" name="purchase_date" style="width: 110px;" class="datepicker m_input_default" value="<?php if($form_data['purchase_date']=='00-00-0000' || empty($form_data['purchase_date'])){echo '';}else{echo $form_data['purchase_date'];}?>">
                <input type="text" name="purchase_time" class="w-80px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['purchase_time']; ?>">
            </div>
        </div>
        
         <div class="row m-b-5">
            <div class="col-sm-4">
                <label>Vendor GST No.</label>
            </div>
            <div class="col-sm-8">
                <input type="text" name="vendor_gst" id="vendor_gst_no" value="<?php echo $form_data['vendor_gst']; ?>" style="text-transform:uppercase">
            </div>
        </div>

    </div> <!-- //4 -->


    
   <div class="col-md-4">
        
       <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 1</label>
            </div>
            <div class="col-xs-4">
               <input type="text" name="address" id="" class="address" value="<?php echo $form_data['address'];?>"/>
               <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
            </div>
          </div>

           <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 2</label>
            </div>
            <div class="col-xs-4">
               <input type="text"  name="address2" class="address" value="<?php echo $form_data['address2'];?>"/>
              
            </div>
          </div>
           <div class="row m-b-3">
            <div class="col-xs-3">
               <label>Address 3</label>
            </div>
            <div class="col-xs-4">
               <input type="text" name="address3" class="address" value="<?php echo $form_data['address3'];?>"/>
               
            </div>
          </div>

           
            <div class="row m-b-3">
            <div class="col-xs-3">
                <label class="address">Remarks</label>
                </div>
            <div class="col-xs-4">
                <textarea type="text" id="remarks" class="m_input_default" name="remarks"><?php echo $form_data['remarks'];?></textarea>
            </div>
          </div>

    </div> <!-- //4 -->

</div> <!-- mainRow -->

    <div class="purchase_medicine_tbl_box" id="medicine_table">
       <div class="box_scroll" > <!-- class="left" on 07-12-2017-->
            <table class="table table-bordered table-striped m_pur_tbl1" >
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" onClick="toggle(this); add_check();"></th>
                            <th>Medicine Name</th>
                            <th>Packing</th>
                            <th>Conv.</th>
                            <th>Medicine Code</th>
                            <th>HSN No.</th>
                            <th>Mfg.Company</th>
                            <th>Barcode</th>
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
                        <td colspan=""><input type="text" name="medicine_name" class="w-150px" id="medicine_name" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="packing" id="packing" class="w-60px"  onkeyup="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="conv" class="w-60px" id="conv" onkeyup="search_func(this.value);"/></td>

                        <td colspan=""><input type="text" name="medicine_code" class="w-60px" id="medicine_code" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="hsn_no" id="hsn_no" class="w-60px"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="medicine_company" class="w-150px"  id="medicine_company" onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="bar_code" id="bar_code" class="w-60px" onkeyup="search_func(this.value);" /></td>
                        
                       <td colspan="">
                        <select name="unit1" value="unit1" class="w-150px" onchange="search_func();">
                        <option value="">
                          Select Unit1  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->medicine_unit;?></option>
                        <?php }?>
                        </select></td>
                        <td colspan=""><select name="unit2" value="unit2" onchange="search_func();" class="w-150px" > 
                        <option value="">
                          Select Unit2  
                        </option>
                        <?php foreach($unit_list as $unit1){?>
                        <option value="<?php echo $unit1->id;?>"><?php echo $unit1->medicine_unit;?></option>
                        <?php }?>
                        </select></td>
                        
                        <td colspan=""><input type="text" name="mrp" id="mrp" class="w-60px"  onkeyup="search_func(this.value);" /></td>
                        <td colspan=""><input type="text" name="p_rate" id="p_rate" class="w-60px"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="discount" id="discount" class="w-80px"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="cgst" id="cgst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                         <td colspan=""><input type="text" name="sgst" id="sgst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                          <td colspan=""><input type="text" name="igst" id="igst" class="w-60px" onkeyup="search_func(this.value);" /></td>
                        
                    </tr>

                   <tr class="append_row">
                        <td colspan="16" class="text-danger" align="center">No record found</td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        
        <div class="right relative">
            <div class="fixed">
                <button class="btn-save" id="purchase_submit" type="button"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
                <!--<button class="btn-save"><i class="fa fa-refresh"></i> Update</button>-->
                <a href="<?php echo base_url('purchase');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div -->

        <!-- right -->
    </div> 


    <!-- purchase_medicine_tbl_box -->
   <div class="purchase_medicine_tbl_box" id="medicine_select">
        
       <div class=" box_scroll">
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
                        <th>Conv.</th>
                        <th>Mfg. Date</th>
                        <th>Exp. Date</th>
                        <th>Barcode</th>
                        <th>Unit1</th>
                        <th>Unit2</th>
                         <th>Free Unit1</th>
                        <th>Free Unit2</th>
                        <th>MRP</th>
                        <th>Purchase Rate</th>
                        <th>Discount<?php if($discount_setting==1){ ?>(₹) <?php  }else{ ?>(%) <?php } ?></th>
                        <th>CGST (%)</th>
                        <th>SGST (%)</th>
                        <th>IGST (%)</th>
                        <th>Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(empty($form_data['data_id'])){ ?>
                         <tr id="append_data_row" <?php if(!empty($form_data['display_not_found'])){ ?> style="display:none;" <?php } ?>>
                        <td colspan="20"  align="center" class="text-danger">No record found</td>
                       </tr>
                       
                       <?php
                        }
                       if(!empty($medicine_list))
                       {
                            $i=1;
                            foreach($medicine_list as $medicine_data)
                            {
                               // echo $medicine_data['manuf_date'];
                                
                                if(($medicine_data['manuf_date']!='00-00-000') && ($medicine_data['manuf_date']!='01-01-1970'))
                                {
                                    $manuf_date = $medicine_data['manuf_date'];
                                }
                                else
                                {
                                    $manuf_date ='';
                                }
                                if($medicine_data['exp_date']!='00-00-000' || $medicine_data['exp_date']!='01-01-1970')
                                {
                                    $exp_date = $medicine_data['exp_date'];
                                }
                                else
                                {
                                    $exp_date ='';
                                }
                                
                            $qty = ($medicine_data['conversion']*$medicine_data['unit1'])+$medicine_data['unit2'];
                            $free_qty = ($medicine_data['conversion']*$medicine_data['freeunit1'])+$medicine_data['freeunit2'];
                            $qtys = $qty+$free_qty; 
                                //echo "<pre>"; print_r($medicine_data); exit;
                            ?>
                            
                            <tr id="prescription_tr_<?php echo $i;?>"><td><input class="my_medicine_purchase" type="hidden" id="medicine_id_<?php echo $i;?>" name="m_id[]" value="<?php echo $i;?>"/>
                            <!-- previous value for stock comparision -->
                            <input type="hidden" id="row_previous_total_<?php echo $i;?>"  value="<?php echo $qtys; ?>"/>
                            <input type="hidden" id="row_previous_unit1_<?php echo $i;?>"  value="<?php echo $medicine_data['unit1']; ?>"/>
                            
                            <input type="hidden" id="row_previous_unit2_<?php echo $i;?>"  value="<?php echo $medicine_data['unit2']; ?>"/>
                            
                            <input type="hidden" id="row_previous_freeunit1_<?php echo $i;?>"  value="<?php echo $medicine_data['freeunit1']; ?>"/>
                            
                            <input type="hidden" id="row_previous_freeunit2_<?php echo $i;?>"  value="<?php echo $medicine_data['freeunit2']; ?>"/>
                            
                            <!-- previous value for stock comparision -->
                            <input type="hidden" id="medicine_sel_id_<?php echo $i;?>" name="medicine_sel_id[]" value="<?php echo $medicine_data['mid']; ?>"/><input type="hidden" value="<?php echo $medicine_data['conversion']; ?>"  name="conversion[]" id="conversion_<?php echo $i;?>" /><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value="<?php echo $i;?>"></p></td><td><?php echo $medicine_data['medicine_name']; ?><input type="hidden" value="<?php echo $medicine_data['medicine_name']; ?>"  name="medicine_name_data[]" id="medicine_name_data_<?php echo $i;?>" /></td><td><?php echo $medicine_data['medicine_code']; ?><input type="hidden" value="<?php echo $medicine_data['medicine_code']; ?>"  name="medicine_code_data[]" id="medicine_code_data_<?php echo $i;?>" /></td>   <td><input type="text" name="hsn_no[]" placeholder="HSN No." style="width:59px;" id="hsn_no_<?php echo $i;?>" value="<?php echo $medicine_data['hsn_no']; ?>" /></td>  <td><?php echo $medicine_data['packing']; ?></td>   <td><input type="text" value="<?php echo $medicine_data['batch_no']; ?>" name="batch_no[]"  placeholder="Batch Number" style="width:84px;" id="batch_no_<?php echo $i;?>" /></td>  <td><?php echo $medicine_data['conversion']; ?></td>     <td><input type="text" value="<?php echo $manuf_date; ?>" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_<?php echo $i;?>" /></td>   <td><input type="text" value="<?php echo $exp_date; ?>" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_<?php echo $i;?>"/><div  id="expiry_date_error_<?php echo $i;?>"  style="color:red;"></div></td>    <td><input type="text" value="<?php echo $medicine_data['bar_code']; ?>" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_<?php echo $i;?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');validation_bar_code('<?php echo $i;?>');"/></td>   <td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_<?php echo $i;?>" value="<?php echo $medicine_data['unit1']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>'),validation_check('<?php echo $i; ?>');"/><div  id="unit1_error_<?php echo $i; ?>"  style="color:red;"></div></td><td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_<?php echo $i;?>" value="<?php echo $medicine_data['unit2']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>'),validation_check('<?php echo $i; ?>');"/></td><td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_<?php echo $i;?>" value="<?php echo $medicine_data['freeunit1']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>'),validation_check('<?php echo $i; ?>');"/></td><td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_<?php echo $i;?>" value="<?php echo $medicine_data['freeunit2']; ?>" onkeyup="payment_cal_perrow(<?php echo $i;?>'),validation_check('<?php echo $i; ?>');"/></td><td><input type="text" id="mrp_<?php echo $i;?>" class="w-60px" name="mrp[]" value="<?php echo $medicine_data['mrp']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/><span id="mrp_error_<?php echo $i;?>" class="text-danger" style="display:none;"></span></td><td><input type="text" id="purchase_rate_<?php echo $i;?>" class="w-60px" name="purchase_rate[]" value="<?php echo $medicine_data['purchase_amount']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/><span id="purchase_rate_error_<?php echo $i;?>" class="text-danger" style="display:none;"></span></td><td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_<?php echo $i;?>" value="<?php echo $medicine_data['discount']; ?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/></td><td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;"  value="<?php echo $medicine_data['cgst']; ?>" id="cgst_<?php echo $i;?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/></td><td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="<?php echo $medicine_data['sgst']; ?>" id="sgst_<?php echo $i;?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/></td><td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="<?php echo $medicine_data['igst']; ?>" id="igst_<?php echo $i;?>" onkeyup="payment_cal_perrow('<?php echo $i;?>');"/></td><td><input type="text" value="<?php echo $medicine_data['total_amount']; ?>" name="row_total_amount[]" placeholder="Total" style="width:59px;" class="row_total_amount" readonly id="total_amount_<?php echo $i;?>" /></td></tr>
                            
                            <?php
                                $i++;
                            }
                       }
                       
                       
                       
                       ?>
                        </tbody>
                        </table>
                        </div>
                        <div class="right">
                        <a class="btn-new" onclick="medicine_list_vals();">Delete</a>
                        </div> 
                    
        
        
        
        
        
       
    </div> 
    <!-- purchase_medicine_tbl_box -->
    <!-- payment box -->
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
                    <label>Total Amount </label>
                    <input type="text" name="total_amount" id="total_amount" onChange="payment_calc_overall();" value="<?php echo $form_data['total_amount'];?>" readonly>
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
                    <label>Net Amount </label>
                    <input type="text" name="net_amount"  id="net_amount" onkeyup="payment_calc_overall();" readonly value="<?php echo $form_data['net_amount'];?>">
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
                    
                    <!--<div class="sale_medicine_mod_of_payment">-->
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

                        <?php }else{?>

                    <div class="purchase_medicine_mod_of_payment">
                        <label></label>
                        <div class="grp">
                        <input class="input-tiny price_float" type="hidden" value="0" id="discount_all" onkeyup="payment_calc_overall();" placeholder="%" name="discount_percent">
                        <input type="hidden" name="discount_amount"  id="discount_amount" value="0" readonly>
                        </div>
                    </div>

                       <?php  }

                    ?>
                <div class="purchase_medicine_mod_of_payment">
                    <label>Paid Amount<span class="star">*</span></label>
                    <input type="text" name="pay_amount"  id="pay_amount" value="<?php echo $form_data['pay_amount']; ?>" class=""  onblur="payemt_vals(1);">
                    <div class="f_right"><?php if(!empty($form_error)){ echo form_error('pay_amount'); } ?>
                    </div>
                </div>

                 <div class="purchase_medicine_mod_of_payment">
                    <label>Balance</label>
                     <input type="text" name="balance_due"  id="balance_due" value="<?php echo $balance=($form_data['net_amount']-$form_data['pay_amount'])-$form_data['discount_amount']; ?>" class="price_float" readonly >
                   
                </div>


            </div> <!-- right_box -->

            
        </div> <!-- left -->
        <div class="right">
           
        </div> <!-- dont delete this div -->
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

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("purchase/print_purchase_recipt"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<script>
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
$('.datepicker3').datetimepicker({
     format: 'LT'
  });
$('#purchase_submit').click(function(){  
    
    var ven_name = $('#ven_name').val();
    if(ven_name=='')
    {
        alert('Please enter the Vendor name!');
        return false;
    }
    
    var z=true;
    
    if(item_purchase_row_error()==false)
    {
        z=false;
    }
    
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

function item_purchase_row_error()
{
    var qty_error = true; 
    var mrp=true;
    var purchase_rate=true;
    var expiry_error = true;
    var expirt_date_error = true;
    var purc_date = $('#purchase_date').val();
    var data_ids = get_ids();
    $.each(data_ids,function(index,value){
       
       if(($('#unit1_'+value).val()=="" || $('#unit1_'+value).val()=="0") && ($('#unit2_'+value).val()=="" || $('#unit2_'+value).val()=="0") )
       {
           $('#unit1_error_'+value).show().html('Unit1 is required!');
           $('#unit2_error_'+value).show().html('Unit1 is required!');
           qty_error=false;
           
       }
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
       
       if($('#expiry_date_'+value).val()=="" || $('#expiry_date_'+value).val()=="01-01-1970")
       {
           $('#expiry_date_error_'+value).show().html('Expiry is required!');
           expiry_error=false;
       }
       
       var row_date = $('#expiry_date_'+value).val().split("-");
        var purch_date = purc_date.split("-");
        var txtDate = new Date(row_date[2], row_date[1] - 1, row_date[0]);
        var purcDate = new Date(purch_date[2], purch_date[1] - 1, purch_date[0]);
        
        if (txtDate - purcDate < 0) 
        {
          $('#expiry_date_error_'+value).show().html('Invalid Expiry!');
          expirt_date_error=false;
        }

       
        
    });
    
    
    if(mrp==false || purchase_rate==false || qty_error==false || expiry_error==false || expirt_date_error==false)
    {
        return false;
    }
    else
    {
        return true;
    }
    
    
}

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
   
  //payment_calc_all();
   
    $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
});

/*function add_check()
{
    var timerA = setInterval(function(){  
      child_medicine_vals();
      clearInterval(timerA); 
    }, 1000);
}*/

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
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
             allVals.push($(this).val());
         } 
       });

        if(allVals!="")
        {
            set_medicine_list(allVals);
            search_func();
        }
}

function set_medicine_list(allVals)
 {   
      
      
       if(allVals!="")
       {
            $("#append_data_row").remove();
            var row_number =$('#medicine_per_row tr').length;
         
          $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('purchase/set_new_medicine');?>",
                  data: {medicine_id: allVals,row_number:row_number},
                  dataType: "json",
                  success: function(result) 
                  { 
                      
                    var data_res = result.data;
                    
                    var i=$('#medicine_per_row tr').length;
                    $('#medicine_per_row').append('<tr id="prescription_tr_'+i+'"><td><input class="my_medicine_purchase" type="hidden" id="medicine_id_'+i+'" name="m_id[]" value="'+i+'"/><input type="hidden" id="medicine_sel_id_'+i+'" name="medicine_sel_id[]" value="'+data_res.mid+'"/><input type="hidden" value="'+data_res.conversion+'"  name="conversion[]" id="conversion_'+i+'" /><input type="checkbox" name="medicine_id[]" class="booked_checkbox" value="'+i+'"></p></td><td>'+data_res.medicine_name+'<input type="hidden" value="'+data_res.medicine_name+'"  name="medicine_name_data[]" id="medicine_name_data_'+i+'" /></td><td>'+data_res.medicine_code+'</td>   <td><input type="text" name="hsn_no[]" placeholder="HSN No." style="width:59px;" id="hsn_no_'+i+'" value="'+data_res.hsn_no+'" /></td>  <td>'+data_res.packing+'</td>   <td><input type="text" value="'+data_res.batch_no+'" name="batch_no[]"  placeholder="Batch Number" style="width:84px;" id="batch_no_'+i+'" /></td>  <td>'+data_res.conversion+'</td>     <td><input type="text" value="'+data_res.manuf_date+'" name="manuf_date[]" class="datepicker" placeholder="Manufacture date" style="width:84px;" id="manuf_date_'+i+'" />'+data_res.manuf_script+'</td>   <td><input type="text" value="'+data_res.expiry_date+'" name="expiry_date[]" class="datepicker" placeholder="Expiry date" style="width:84px;" id="expiry_date_'+i+'" />'+data_res.check_scrip+'<div  id="expiry_date_error_'+i+'"  style="color:red;"></div></td>    <td><input type="text" value="'+data_res.bar_code+'" name="bar_code[]" class="" placeholder="Bar Code" style="width:84px;" id="bar_code_'+i+'" onkeyup="payment_cal_perrow('+i+');validation_bar_code('+i+');"/></td>   <td><input type="text" name="unit1[]" placeholder="Unit1" style="width:59px;" id="unit1_'+i+'" value="'+data_res.unit1+'" onkeyup="payment_cal_perrow('+i+'),validation_check('+i+');"/><div  id="unit1_error_'+i+'"  style="color:red;"></div></td><td><input type="text" name="unit2[]" placeholder="Unit2" style="width:59px;" id="unit2_'+i+'" value="'+data_res.unit2+'" onkeyup="payment_cal_perrow('+i+'),validation_check('+i+');"/></td><td><input type="text" name="freeunit1[]" placeholder="Free Unit1" style="width:59px;" id="freeunit1_'+i+'" value="'+data_res.freeunit1+'" onkeyup="payment_cal_perrow('+i+'),validation_check('+i+');"/></td><td><input type="text" name="freeunit2[]" placeholder="Free Unit2" style="width:59px;" id="freeunit2_'+i+'" value="'+data_res.freeunit2+'" onkeyup="payment_cal_perrow('+i+'),validation_check('+i+');"/></td><td><input type="text" id="mrp_'+i+'" class="w-60px" name="mrp[]" value="'+data_res.mrp+'" onkeyup="payment_cal_perrow('+i+');"/><span id="mrp_error_'+i+'" class="text-danger" style="display:none;"></span></td><td><input type="text" id="purchase_rate_'+i+'" class="w-60px" name="purchase_rate[]" value="'+data_res.purchase_amount+'" onkeyup="payment_cal_perrow('+i+');"/><span id="purchase_rate_error_'+i+'" class="text-danger" style="display:none;"></span></td><td><input type="text" class="price_float" name="discount[]" placeholder="Discount" style="width:59px;" id="discount_'+i+'" value="'+data_res.discount+'" onkeyup="payment_cal_perrow('+i+');"/></td><td><input type="text" class="price_float" name="cgst[]" placeholder="CGST" style="width:59px;" value="'+data_res.cgst+'" id="cgst_'+i+'" onkeyup="payment_cal_perrow('+i+');"/></td><td><input type="text" class="price_float" name="sgst[]" placeholder="SGST" style="width:59px;" value="'+data_res.sgst+'" id="sgst_'+i+'" onkeyup="payment_cal_perrow('+i+');"/></td><td><input type="text" class="price_float" name="igst[]" placeholder="IGST" style="width:59px;" value="'+data_res.igst+'" id="igst_'+i+'" onkeyup="payment_cal_perrow('+i+');"/></td><td><input type="text" value="'+data_res.total_amount+'" name="row_total_amount[]" placeholder="Total" style="width:59px;" class="row_total_amount" readonly id="total_amount_'+i+'" /></td></tr>');
                      
                    
                     
                  }
              });
       }      
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
    var medicine_name = $('#medicine_name').val();
    var medicine_code = $('#medicine_code').val();
    var packing = $('#packing').val();
    var hsn_no = $('#hsn_no').val();
    var conv = $('#conv').val();
    var bar_code = $('#bar_code').val();
    //var mfc_date = $('#mfc_date').val();
    var medicine_company = $('#medicine_company').val();
    var unit1 = $("#unit1" ).val();
    //alert(unit1);
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
       url: "<?php echo base_url('purchase/ajax_list_medicine')?>",
       data: {'medicine_name' : medicine_name,'medicine_code':medicine_code,'hsn_no':hsn_no,'conv':conv,'medicine_company':medicine_company,'unit1':unit1,'unit2':unit2,'mrp':mrp,'p_rate':p_rate,'discount':discount,'cgst':cgst,'sgst':sgst,'igst':igst,'packing':packing,'bar_code':bar_code},
       dataType: "json",
       success: function(msg){
       // alert(msg.data);
        $(".append_row").remove();
        $("#previours_row").after(msg.data);
                   //Receiving the result of search here
       }
    }); 
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
  

  function list_added_medicine()
  {
    $.ajax({
                    url: "<?php echo base_url(); ?>purchase/ajax_added_medicine", 
                    dataType: "json",
                   success: function(result)
                    {

                      $('#medicine_select').html(result.data); 
                     
                   } 
                 });   
  }

  function medicine_list_vals() 
  {  
      var data_id= '<?php echo $form_data['data_id'];?>';
 
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
            var id = $(this).val();
            
            if(data_id!='') //check available stock before deleting the row.
            {
               var med_id = $('#medicine_sel_id_'+id).val(); 
               var batch = $('#batch_no_'+id).val(); 
               var mbid = med_id+'.'+batch;
               var row_conversion = $('#conversion_'+id).val();
               var row_unit1 = $('#unit1_'+id).val();
               var row_unit2 = $('#unit2_'+id).val();
               var row_free_unit1 = $('#freeunit1_'+id).val();
               var row_free_unit2 = $('#freeunit2_'+id).val();
               var row_free_qty = (parseInt(row_free_unit1)*parseInt(row_conversion))+parseInt(row_free_unit2);
               var row_unit_qty = (parseInt(row_unit1)*parseInt(row_conversion))+parseInt(row_unit2);
               
               var row_qty =parseInt(row_unit_qty)+parseInt(row_free_qty);
              
            $.ajax({
                   type: "POST",
                    url: "<?php echo base_url('sales_medicine/ajax_check_stock_avability')?>",
                    data: {'mbid' : mbid},
                   success: function(msg)
                   {
                      const myObj = JSON.parse(msg);
                       var avalable_stock = myObj.avaibale_qty;
                       //var remaining_stock = avalable_stock-row_qty;
                       
                     if(parseInt(avalable_stock)<parseInt(row_qty))
                     {
                         alert('This medicine can not be deleted as the stock quantity is less the total medicine quantity.');
                          return false;
                     }
                     else
                     {
                            /*if (1 == $("#medicine_per_row > tbody > tr").length) alert("There only one row you can't delete.");
                        	else 
                        	{*/
                        	    $("#prescription_tr_"+id).remove();
                            //} 
                            payment_calc_overall();
                     }
                     
                       
                   } 
                });
            }
            else
            {
                    //for add purchase medicine case
                    /*if (1 == $("#medicine_per_row > tbody > tr").length) alert("There only one row you can't delete.");
                	else 
                	{*/
                	    $("#prescription_tr_"+id).remove();
                    //} 
                    payment_calc_overall();
            }
            
            
            
              
         } 
         
       });
       
        
  
  } 
  function remove_medicine(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('purchase/remove_medicine_list');?>",
               dataType: "json",
              data: {medicine_id: allVals},
              success: function(result) 
              {  
                //$('#medicine_select').html(result.data); 
                //search_func();
                 
                //list_added_medicine();  
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


 function payment_cal_perrow(ids)
 {
      var timerA = setInterval(function(){  
         
          
     var data_id= '<?php echo $form_data['data_id'];?>';
     
    var purchase_rate = $('#purchase_rate_'+ids).val();
    var conversion = $('#conversion_'+ids).val();
    var medicine_id= $('#medicine_id_'+ids).val();
    var batch_no= $('#batch_no_'+ids).val();
    var hsn_no= $('#hsn_no_'+ids).val();
    var unit1= +($('#unit1_'+ids).val());
    var freeunit1= $('#freeunit1_'+ids).val();
    var freeunit2= $('#freeunit2_'+ids).val();
    var bar_code= $('#bar_code_'+ids).val();
    var unit2= $('#unit2_'+ids).val();
    var mrp= $('#mrp_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    $('#unit1_max_'+ids).text('');
    
    
            if(data_id!='') //check available stock before deleting the row.
            {
                var med_id = $('#medicine_sel_id_'+ids).val(); 
                var batch = $('#batch_no_'+ids).val(); 
                var mbid = med_id+'.'+batch;
                var row_conversion = $('#conversion_'+ids).val();
                var row_unit1 = $('#unit1_'+ids).val();
                var row_unit2 = $('#unit2_'+ids).val();
                var row_free_unit1 = $('#freeunit1_'+ids).val();
                var row_free_unit2 = $('#freeunit2_'+ids).val();
                var row_free_qty = (parseInt(row_free_unit1)*parseInt(row_conversion))+parseInt(row_free_unit2);
                var row_unit_qty = (parseInt(row_unit1)*parseInt(row_conversion))+parseInt(row_unit2);
                var row_qty =parseInt(row_unit_qty)+parseInt(row_free_qty);
                var row_prev_qty = $('#row_previous_total_'+ids).val();
                var row_previous_unit1 = $('#row_previous_unit1_'+ids).val();
                var row_previous_unit2 = $('#row_previous_unit2_'+ids).val();
                var row_previous_freeunit1 = $('#row_previous_freeunit1_'+ids).val();
                var row_previous_freeunit2 = $('#row_previous_freeunit2_'+ids).val();
               
                //the below is the common function to check the stock quantity of a medicine with batch no.
                $.ajax({
                   type: "POST",
                    url: "<?php echo base_url('sales_medicine/ajax_check_stock_avability')?>",
                    data: {'mbid' : mbid},
                   success: function(msg)
                   {
                      const myObj = JSON.parse(msg);
                       var avalable_stock = myObj.avaibale_qty;
                       
                     if(parseInt(row_prev_qty)>parseInt(row_qty))
                     {
                         
                         var difference_in_qty = parseInt(row_prev_qty)-parseInt(row_qty);
                        if(parseInt(avalable_stock)<parseInt(difference_in_qty))
                        {
                           $('#unit1_'+ids).val(row_previous_unit1);
                            $('#unit2_'+ids).val(row_previous_unit2);
                            $('#freeunit1_'+ids).val(row_previous_freeunit1);
                            $('#freeunit2_'+ids).val(row_previous_freeunit2);
                           alert('Stock quantity not avalable!');
                           return false; 
                        }
                     }
                     
                       
                   } 
                });
            }    
    
    
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>purchase/payment_cal_perrow/", 
                dataType: "json",
                data: 'purchase_rate='+purchase_rate+'&batch_no='+batch_no+'&mrp='+mrp+'&conversion='+conversion+'&manuf_date='+manuf_date+'&unit1='+unit1+'&unit2='+unit2+'&medicine_id='+medicine_id+'&expiry_date='+expiry_date+'&cgst='+cgst+'&sgst='+sgst+'&igst='+igst+'&discount='+discount+'&hsn_no='+hsn_no+'&freeunit1='+freeunit1+'&freeunit2='+freeunit2+'&bar_code='+bar_code,
                success: function(result)
                {
                  var total_amounts = parseFloat(result.total_amount);
                  $('#total_amount_'+result.key_id).val(total_amounts.toFixed(2, 2));
                  
                    var discount_type=0;//$post['discount_type'];
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
                    var discount_setting = $('#discount_setting').val();
                   var row_wise_total_w_dis=0;
                   $('.my_medicine_purchase').each(function() 
                   {
                        
                        var i = $(this).val();
                        var signal_unit1_price = $('#purchase_rate_'+i).val()* $('#unit1_'+i).val();
                        var signal_unit2_price = ($('#purchase_rate_'+i).val()/$('#conversion_'+i).val())*$('#unit2_'+i).val();
                        total_amount_cal += signal_unit1_price+signal_unit2_price;
                        var rowdoscval = $('#discount_'+i).val();
                    if(rowdoscval != "") 
                    {
                      var lrowdoscval=parseFloat(rowdoscval);  
                    }
                    else
                    {
                      var lrowdoscval=0;  
                    }
                    if(discount_setting==1)
                    {
                        var total_row_discount = lrowdoscval;
                    }
                    else
                    {
                        var total_row_discount = ((signal_unit1_price+signal_unit2_price)/100)*lrowdoscval;
                    }
                    
                    if(total_row_discount != "" || total_row_discount != "0.00") 
                    {
                      var totalrowdiscount=parseFloat(total_row_discount);  
                    }
                    else
                    {
                      var totalrowdiscount=0;  
                    }
                    
                    var total_row_amount = (signal_unit1_price+signal_unit2_price)-totalrowdiscount;
                    
                    //New code payment_cal_perrow
                    var cgstvls = $('#cgst_'+i).val();
                    if(cgstvls != "") 
                    {
                      var lcgstvls=cgstvls;  
                    }
                    else
                    {
                      var lcgstvls=0;  
                    }
                    var sgstvls = $('#sgst_'+i).val();
                    if(sgstvls != "") 
                    {
                      var lsgstvls=sgstvls;  
                    }
                    else
                    {
                      var lsgstvls=0;  
                    }
                    var igstvls = $('#igst_'+i).val();
                    if(igstvls != "") 
                    {
                      var ligstvls=igstvls;  
                    }
                    else
                    {
                      var ligstvls=0;  
                    }
                    var cgst_initial = parseFloat(lcgstvls);
                    var sgst_initial = parseFloat(lsgstvls);
                    var igst_initial = parseFloat(ligstvls);
                    
                    //if(cgst_initial != "") 
                    if(cgst_initial != "")
                    {
                      var cgstinitial=cgst_initial;  
                    }
                    else
                    {
                      var cgstinitial=0;  
                    }
                    if(sgst_initial != "") 
                    {
                      var sgstinitial=sgst_initial;  
                    }
                    else
                    {
                      var sgstinitial=0;  
                    }
                    if(igst_initial != "") 
                    {
                      var igstinitial=igst_initial;  
                    }
                    else
                    {
                      var igstinitial=0;  
                    }
                    
                    
                    var gst_per_total = parseFloat(cgstinitial)+parseFloat(sgstinitial)+parseFloat(igstinitial);
                    
                   // var gst_cal = (total_row_amount -(total_row_amount*(100/(100+(gst_per_total)/100))))*100;
                   //var gst_cal = (parseInt(total_row_amount)*parseInt(gst_per_total))/100;
                    
                    if(cgst_initial>0)
                    {
                        var cgst_cal_val = (parseFloat(total_row_amount)*parseFloat(cgst_initial))/100;
                        //var cgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var cgst_cal_val = 0; 
                    }
                    
                    if(sgst_initial>0)
                    {
                        var sgst_cal_val = (parseFloat(total_row_amount)*parseFloat(sgst_initial))/100;
                        //var sgst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var sgst_cal_val = 0; 
                    }
                    
                    if(igst_initial>0)
                    {
                        var igst_cal_val = (parseFloat(total_row_amount)*parseFloat(igst_initial))/100;
                        //var igst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var igst_cal_val = 0; 
                    }
                    total_cgst_cal += cgst_cal_val; 
                    total_sgst_cal += sgst_cal_val;
                    total_igst_cal += igst_cal_val;
                    row_wise_total_w_dis += total_row_amount; 
                    tot_discount_amount_cal += total_row_discount;
                    
                  });
                  
                  
                    var g_total = (total_amount_cal+(total_cgst_cal+total_sgst_cal+total_igst_cal))-tot_discount_amount_cal;
                    var discount_all = $('#discount_all').val();
                    var discount_type = $("#discount_type :selected").val();
                    var net_amount = g_total;
                    if(discount_type==1)
                    {
                        if(discount_all > Number(net_amount)){
                          alert('Discount should be less then net amount!');
                          $('#discount_all').val('0.00');
                          return false;
                       } 
                        var total_discount = discount_all;
                    }
                    else
                    {
                        if(Number(discount_all) > 100){
                          alert('Discount should be less then 100');
                          $('#discount_all').val('0.00');
                          return false;
                       }
                        var total_discount = (discount_all/100)*g_total;
                    }
                    //alert(total_discount);
                    var g_total =(g_total-total_discount);
                    
                    
                    
                    if(Number(total_discount)>0)
                    {
                        $('#discount_amount').val(Number(total_discount).toFixed(2,2));    
                    }
                    else
                    {
                        $('#discount_amount').val('0');    
                        //$('#discount_amount').val(Number(tot_discount_amount_cal).toFixed(2,2));
                    }
                	//alert(row_wise_total_w_dis);
                    $('#cgst_amount').val(total_cgst_cal.toFixed(2, 2));
                    $('#igst_amount').val(total_igst_cal.toFixed(2, 2));
                    $('#sgst_amount').val(total_sgst_cal.toFixed(2, 2));
                    $("#total_amount").val(row_wise_total_w_dis.toFixed(2, 2)); 
                	$('#pay_amount').val('0.00');
                	$("#net_amount").val(net_amount.toFixed(2, 2));
                	$('#balance_due').val(g_total.toFixed(2, 2));
                  //payment_calc_all();
                } 
              });
    
    clearInterval(timerA); 
        }, 500);
 }
 
 
 function payment_calc_overall()
 {
     
                    var discount_type=0;//$post['discount_type'];
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
                    var discount_setting = $('#discount_setting').val();
                    var row_wise_total_w_dis=0;
                    
                   
                  $('.my_medicine_purchase').each(function() 
                   {
                     
                        var i = $(this).val();
                        var signal_unit1_price = $('#purchase_rate_'+i).val()* $('#unit1_'+i).val();
                        var signal_unit2_price = ($('#purchase_rate_'+i).val()/$('#conversion_'+i).val())*$('#unit2_'+i).val();
                        total_amount_cal += signal_unit1_price+signal_unit2_price;
                        //alert(total_amount_cal);
                        
                        if(discount_setting==1)
                        {
                            
                            var rowdoscval = $('#discount_'+i).val();
                            if(rowdoscval != "") 
                            {
                              var total_row_discount=parseFloat(rowdoscval);  
                            }
                            else
                            {
                              var total_row_discount=0;  
                            }
                        }
                        else
                        {
                            var rowdoscval = $('#discount_'+i).val();
                            if(rowdoscval != "") 
                            {
                              var row_dosc_val=parseFloat(rowdoscval);  
                            }
                            else
                            {
                              var row_dosc_val=0;  
                            }
                           // var row_dosc_val = parseInt($('#discount_'+i).val());
                           var total_row_discount = ((signal_unit1_price+signal_unit2_price)/100)*row_dosc_val;
                        }
                        
                        var total_row_amount = (signal_unit1_price+signal_unit2_price)-parseFloat(total_row_discount);
                        
                        //new gst 
                        
                        
                        var cgst_initial = $('#cgst_'+i).val();
                        if(cgst_initial != "") 
                        {
                            var cgst_initial=parseFloat(cgst_initial);  
                        }
                        else
                        {
                            var cgst_initial=0;  
                        }
                        
                        var sgst_initial = $('#sgst_'+i).val();
                        if(sgst_initial != "") 
                        {
                            var sgst_initial=parseFloat(sgst_initial);  
                        }
                        else
                        {
                            var sgst_initial=0;  
                        }
                        var igst_initial = $('#igst_'+i).val();
                        if(igst_initial != "") 
                        {
                            var igst_initial=parseFloat(igst_initial);  
                        }
                        else
                        {
                            var igst_initial=0;  
                        }
                        
                    var gst_per_total = parseFloat(cgst_initial)+parseFloat(sgst_initial)+parseFloat(igst_initial);
                    
                    //var gst_cal = total_row_amount -(total_row_amount*(100/(100+gst_per_total)));
                    
                     
                    if(cgst_initial>0)
                    {
                        //var cgst_cal_val = gst_cal/2;
                        var cgst_cal_val = (total_row_amount*cgst_initial)/100;
                    }
                    else
                    {
                       var cgst_cal_val = 0; 
                    }
                    
                    if(sgst_initial>0)
                    {
                        //var sgst_cal_val = gst_cal/2;
                        var sgst_cal_val = (total_row_amount*sgst_initial)/100;
                    }
                    else
                    {
                       var sgst_cal_val = 0; 
                    }
                    
                    if(igst_initial>0)
                    {
                        //var igst_cal_val = gst_cal/2;
                        var igst_cal_val = (total_row_amount*igst_initial)/100;
                    }
                    else
                    {
                       var igst_cal_val = 0; 
                    }
                    
                    
                    
                    
                    total_cgst_cal += cgst_cal_val; 
                    total_sgst_cal += sgst_cal_val;
                    total_igst_cal += igst_cal_val;
                    row_wise_total_w_dis += total_row_amount;   
                   //new gst
                    tot_discount_amount_cal+= total_row_discount;
                  });
                  
                  //alert(tot_discount_amount_cal);
                  
                  var total_gstcsg = total_cgst_cal+total_sgst_cal+total_igst_cal;
                  var g_total =(total_amount_cal+total_gstcsg)-tot_discount_amount_cal;
                 
                    var pay_amount = $('#pay_amount').val();
                    var discount_all = $('#discount_all').val();
                    var discount_type = $("#discount_type :selected").val();
                    var net_amount = g_total;
                    if(discount_type==1)
                    {
                        if(discount_all > Number(net_amount)){
                          alert('Discount should be less then net amount!');
                          $('#discount_all').val('0.00');
                          return false;
                       } 
                        var total_discount = discount_all;
                    }
                    else
                    {
                        if(Number(discount_all) > 100){
                          alert('Discount should be less then 100');
                          $('#discount_all').val('0.00');
                          return false;
                       }
                        var total_discount = (discount_all/100)*g_total;
                    }
                    //alert(total_discount);
                    var g_total =(g_total-total_discount);
                    if(pay_amount!='')
                    {
                        var balance_final = g_total-pay_amount;
                        if(balance_final<0)
                        {
                           var balance_final ='0.00';
                        }
                        var payamt = pay_amount;
                        //alert(balance_final);
                    }
                    else
                    {
                        var balance_final = g_total-pay_amount;
                        var payamt = pay_amount;
                     
                        //alert(payamt);
                    }
                    
                   
                    $('#discount_amount').val(Number(total_discount).toFixed(2,2)); 
                    //$('#discount_amount').val(Number(tot_discount_amount_cal).toFixed(2,2));
                    $('#cgst_amount').val(total_cgst_cal.toFixed(2, 2));
                    $('#igst_amount').val(total_igst_cal.toFixed(2, 2));
                    $('#sgst_amount').val(total_sgst_cal.toFixed(2, 2));
                    //$("#total_amount").val(total_amount_cal.toFixed(2, 2)); 
                	$("#total_amount").val(row_wise_total_w_dis.toFixed(2, 2)); 
                	$("#net_amount").val(net_amount.toFixed(2, 2));
                	$('#discount_all').val(discount_all);
                	$('#pay_amount').val(Number(payamt).toFixed(2, 2));
                	$('#balance_due').val(Number(balance_final).toFixed(2, 2));
     
 }
 
 
 function payment_calc_all(pay)
   {
        
        var discount_all = $('#discount_all').val();
        var discount_amount= $('#discount_amount').val();
        var data_id= '<?php echo $form_data['data_id'];?>';
        // var vat = $('#vat_percent').val(); 
        var net_amount = $('#net_amount').val();
        var pay_amount = $('#pay_amount').val();
        var cgst = $('#cgst_percent').val();
        var sgst = $('#sgst_percent').val(); 
        var igst = $('#igst_percent').val(); 
         
         var discount_type=0;//$post['discount_type'];
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
                    var total_new_amount=0;
                    var total_discount=0;
                    var discount_setting = $('#discount_setting').val();
                 
                  $('.my_medicine_purchase').each(function() 
                   {
                     
                        var i = $(this).val();
                        var signal_unit1_price = $('#purchase_rate_'+i).val()* $('#unit1_'+i).val();
                        var signal_unit2_price = ($('#purchase_rate_'+i).val()/$('#conversion_'+i).val())*$('#unit2_'+i).val();
                        total_amount_cal += signal_unit1_price+signal_unit2_price;
                        //alert(total_amount_cal);
                       
                        
                        if(discount_setting==1)
                        {
                            var row_dosc_val = parseFloat($('#discount_'+i).val());
                            var disc_val_row = row_dosc_val;
                            tot_discount_amount_cal += row_dosc_val;
                        }
                        else
                        {
                            var row_dosc_val = parseFloat($('#discount_'+i).val());
                            
                            var disc_val_row = ((signal_unit1_price+signal_unit2_price)/100)*row_dosc_val;
                            
                            tot_discount_amount_cal += disc_val_row;
                        }
                        
                     var total_row_amount = (signal_unit1_price+signal_unit2_price)-disc_val_row;
                     
                        //new gst 
                        
                       var cgst_initial = $('#cgst_'+i).val();
                        if(cgst_initial != "") 
                        {
                            var cgst_initial=parseFloat(cgst_initial);  
                        }
                        else
                        {
                            var cgst_initial=0;  
                        }
                        
                        var sgst_initial = $('#sgst_'+i).val();
                        if(sgst_initial != "") 
                        {
                            var sgst_initial=parseFloat(sgst_initial);  
                        }
                        else
                        {
                            var sgst_initial=0;  
                        }
                        var igst_initial = $('#igst_'+i).val();
                        if(igst_initial != "") 
                        {
                            var igst_initial=parseFloat(igst_initial);  
                        }
                        else
                        {
                            var igst_initial=0;  
                        }
                       /* var cgst_initial = parseInt($('#cgst_'+i).val());
                    var sgst_initial = parseInt($('#sgst_'+i).val());
                    var igst_initial = parseInt($('#igst_'+i).val());*/
                    
                    var gst_per_total = parseFloat(cgst_initial)+parseFloat(sgst_initial)+parseFloat(igst_initial);
                    
                    
                    
                     //var gst_cal = (total_row_amount -(total_row_amount*(100/(100+(gst_per_total)/100))))*100;
                     var gst_cal = (total_row_amount*gst_per_total)/100;
                    
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
                        var igst_cal_val = gst_cal/2;
                    }
                    else
                    {
                       var igst_cal_val = 0; 
                    }
                    
                    
                    
                    
                    total_cgst_cal += cgst_cal_val; 
                    total_sgst_cal += sgst_cal_val;
                    total_igst_cal += igst_cal_val;
                        
                        //new gst
                        
                        //i++;
                        var row_total_amount = $('#total_amount_'+i).val();
                        total_new_amount= (parseFloat(total_new_amount)+parseFloat(row_total_amount));
                  });
                  
                
            var g_total = (total_amount_cal+(total_cgst_cal+total_sgst_cal+total_igst_cal))-tot_discount_amount_cal;
            var discount_all = $('#discount_all').val();
            var discount_type = $("#discount_type :selected").val();
            var net_amount = g_total;
            if(discount_type==1)
            {
                if(discount_all > Number(net_amount)){
                  alert('Discount should be less then net amount!');
                  $('#discount_all').val('0.00');
                  return false;
               } 
                var total_discount = discount_all;
            }
            else if(discount_type==0)
            {
                if(Number(discount_all) > 100){
                  alert('Discount should be less then 100');
                  $('#discount_all').val('0.00');
                  return false;
               }
                var total_discount = (discount_all/100)*g_total;
            }
            
            net_paid_amount = g_total-total_discount;
            
            if(pay_amount!='')
            {
                if(net_paid_amount>pay_amount)
                {
                  var balance =net_paid_amount-pay_amount;  
                }
                else
                {
                  var balance ='0.00';  
                }
                var pay_amt = pay_amount;
            }
            else
            {
                var pay_amt = net_paid_amount;
                var balance ='0.00';  
                 
            }
            //alert(balance);
            $('#discount_amount').val(Number(total_discount).toFixed(2,2));
            $('#cgst_percent').val(total_cgst_cal.toFixed(2, 2));
            $('#igst_percent').val(total_igst_cal.toFixed(2, 2));
            $('#sgst_percent').val(total_sgst_cal.toFixed(2, 2));
            $("#total_amount").val(total_amount_cal.toFixed(2, 2)); 
        	 //alert(net_amount);
        	$("#net_amount").val(net_amount.toFixed(2, 2));
        	$('#pay_amount').val(Number(pay_amt).toFixed(2,2)); //pay_amt
        	$('#balance_due').val(Number(balance).toFixed(2, 2));
            
    }
  

  function payemt_vals(pay)
  {
   
     var timerA = setInterval(function(){  
          payment_calc_overall();
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
    var mbid =$('#medicine_id_'+id).val();
    var bar_code =$('#bar_code_'+id).val();
    $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>purchase/check_bar_code/", 
      dataType: "json",
       data: 'mbid='+mbid+'&bar_code='+bar_code,
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
        window.location.href='<?php echo base_url('purchase/add');?>'; 
    }) ;
   
       
  <?php }?>
 });
</script>
<!--- Search Estimate ----------->
<!--
  -->

<script type="text/javascript">

  function get_medicine_vender(m_id)
  {
    payment_cal_perrow(m_id);
   $.ajax({
          type: "POST",
          url: "<?php echo base_url('purchase/get_medicine_venders');?>",
          data: {medicine_id: m_id},
          dataType: "json",
          success: function(result) 
          {
            
            $('#vender_list').after(result); 
            
         }
      });
  }

  function get_purchase_rates(val,id)
  {
    payment_cal_perrow(id);
    $('#purchase_rate_'+id).val(+(val));
  }

/*$(document).ready(function(){
   
  var estimate_code = '< ?php echo $form_data['estimate_code']; ?>';
  //alert(estimate_code);
  set_estimate_medicine(estimate_code);                
});*/



//check row wise stock quantity

function validation_check(row_id)
{
    return true;
    /* var data_id = '< ?php echo $form_data['data_id']; ?>';
    if(Number(data_id)!='')
    {
        //alert();
        $('#unit1_error_'+row_id).html('');
        var batch_no=  $('#batch_no_'+row_id).val();
        var med_id=  $('#medicine_sel_id_'+row_id).val();
        var row_conversion= $('#conversion_'+row_id).val();
        var row_unit1= $('#unit1_'+row_id).val();
        var row_unit2= $('#unit2_'+row_id).val();
         var row_free_unit1= $('#freeunit1_'+row_id).val();
         var row_free_unit2= $('#freeunit2_'+row_id).val();
         
         var row_free_qty = (parseInt(row_free_unit1)*parseInt(row_conversion))+parseInt(row_free_unit2);
         var row_unit_qty = (parseInt(row_unit1)*parseInt(row_conversion))+parseInt(row_unit2);
         var row_qty =parseInt(row_unit_qty)+parseInt(row_free_qty);
               
         
          $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>purchase_return/ajax_check_stock_avability/", 
          data: {'medi_id' : med_id,med_batch:batch_no},
          success: function(result)
          {
             const myObj = JSON.parse(result);
             var avalable = myObj.avaibale_qty;
             if(parseInt(avalable)<parseInt(row_qty))
             {
                // alert(value);
                //$('#unit1_error_'+row_id).show().html('No Available Quantity!');
                 $('#unit1_error_'+row_id).show().html('Number of available quantity only '+avalable);
                 return false;
             }
    
          } 
        });
    }*/
  }
//end of check stock quantity
    </script>
    

    <!--- Search Estimate ----------->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<style>

.ui-autocomplete { z-index:2147483647; }
</style>