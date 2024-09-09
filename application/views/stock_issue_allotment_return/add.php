<?php
$this->load->model('stock_issue_allotment/stock_issue_allotment_model','stock_issue_allotment');
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


<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>select2.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-select.min.css">

<!--new css-->
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<!--new datepicker-->
<script src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.min.js"></script>

<script type="text/javascript">
var save_method; 
var table;

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


<body onload="list_added_item();">

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
<form id="purchase_form" action="<?php echo current_url().$query_string; ?>" method="post">
 <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
<section class="userlist">
  <!-- Left side Contents  -->
    <div class="userlist-box media_tbl_full">
   
 <!-- //////////////// Main Top row 1st /////////////// -->
      <!-- upper all fields -->
      <div class="row">

        <!-- ///////////////////////////// Top Left Side  ///////////////////////////// -->
        <div class="col-md-4">
          <!-- upper left side label and their fields -->
          <div class="row m-b-5">
            <div class="col-md-12" id="user_type">
              <label><input type="radio" <?php if($form_data['user_type']==1){ echo 'checked';} ?> value="1" name="user_type" onclick="select_user_type(1);"> Employee</label> 
              &nbsp;
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
                      <label>Patient Name <span class="star">*</span></label>
                    </div>
                    <div class="col-md-8">
                      <select name="user_type_id" id="user_type_id"  onchange="appned_user_detail(2,this.value);">
                        <option value="">Select</option>
                          <?php foreach($user_list as $use_list)
                          {?>
                           <option value="<?php echo $use_list->ids; ?>" <?php if(isset($form_data['user_type_id']) && $form_data['user_type_id']==$use_list->ids){ echo 'selected';} ?>><?php echo $use_list->patient_name; ?></option>
                         <?php  } ?>
                      </select>
                      <?php if(!empty($form_error)){ echo form_error('user_type_id'); } ?>
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
                      <select name="user_type_id" id="user_type_id" onchange="appned_user_detail(3,this.value);">
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
                      <select name="employee_type" id="employee_type" onchange="employee_list_new(this.value);">
                        <option value="">Select</option>
                        <?php foreach($type_list as $type){ //print_r($type); ?>


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

        <?php if(!empty($form_data['user_type_id'])) { ?>   

                <select name="user_type_id" id="employee_list_n" onchange="user_detail(this.value);" class="selectpicker"  data-live-search="true">
                      <option value="">Select</option>
                      <?php foreach($employee_list as $emp_list){ ?>
                      <option value="<?php echo $emp_list->id; ?>" <?php if(isset($form_data['user_type_id']) && $form_data['user_type_id']==$emp_list->id){ echo 'selected';} ?>><?php echo $emp_list->name; ?></option>   
                       <?php  } ?>
                </select>

        <?php } else { ?>

                <select class="w-150px selectpicker" name="user_type_id" id="employee_list_n" onchange="appned_user_detail(1,this.value);" data-live-search="true">
                  <option value="">Select</option>
                  <?php //print_r($employee_list); 
                  foreach($employee_list as $emp_list)
                  {?>
                       <option value="<?php echo $emp_list->id;?>"><?php echo $emp_list->name.' ('.$emp_list->reg_no.')';?></option>
                  <?php } ?>
                </select>

      <?php }  ?>    

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

            <?php if(isset($form_data['member_name']) && !empty($form_data['member_name']))
            {?>
              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">
                  <?php echo $form_data['member_name'];?>
                  <input type="hidden" value=" <?php echo $form_data['member_name'];?>" id="member_name" name="user_name"/>
                </div>
              </div>
            <?php }?>




        <?php $well='';if((isset($form_data['user_name']) && !empty($form_data['user_name'])) || (isset($form_data['address']) && !empty($form_data['address'])))
            {
              $well="well";
            }
            ?>
          <div id="vendor_data_append">
              <!-- upper left side label and their fields -->
            <?php if(isset($form_data['user_name']) && !empty($form_data['user_name']))
            {?>
              <div class="row m-b-5">
                <div class="col-md-2">
                  <label>Name</label>
                </div>
                <div class="col-md-10">
                  <?php echo $form_data['user_name'];?>
                  <input type="hidden" value=" <?php echo $form_data['user_name'];?>" name="user_name"/>
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
                  <input type="hidden" value=" <?php echo $form_data['address'];?>" name="address" id="address"/>
                </div>
              </div>
              <?php }?>


        </div> <!-- well -->
          
        </div> <!-- 12 -->
      </div> <!-- Row -->
      <!-- Upper row ends here -->
    <!-- /////////// table 1st /////////// -->
    <div class="row">
      <div class="col-md-12 pr-0">
<!-- ////////// -->
       <table width="92%" class="table table-bordered stock_item_issue_upper" style="margin-bottom:0;background: transparent;">
          <tbody>
            <tr>
              <td><label>Return No.</label></td>
             <td colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $form_data['return_code'];?>
                <input type="hidden" value="<?php echo $form_data['return_code'] ?>" name="return_code"/>
                <input type="hidden" value="" id="item_id"/>
                  <input type="hidden" value="" id="category_id"/>
                </td>
            </tr>

            <tr>
             <td><label>Issue No.</label></td>
              <td> 
               
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="issue_no" id="issue_no" class="issue_no" value="<?php echo $form_data['issue_no'] ?>"/>
                 <div id="no_ret" class="text-danger">Return date exceeded.</div>    
              </td>

              <td><label>Return Date</label> </td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="issue_date" id="issue_date" class="issue_date purchasedatepicker m_input_default" value="<?php echo $form_data['issue_date'] ?>"/>
              </td>

              <td></td>
              <td>
               <!--  <input type="text" name="item_name" class="item_name m_input_default txt_firstCap" value="" id=""> -->
              </td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>

          
          </tbody>
        </table>

<!---- stock_issue_allotment_tbl_box  ----->

    <div class="purchase_medicine_tbl_box" id="medicine_table">
       <div class="box_scroll table-bordered"> <!-- class="left" on 07-12-2017-->
            <table class="table table-bordered table-striped m_pur_tbl1">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" onClick="toggle(this); add_check();"></th>
                            <th>Item Name</th>
                            <th>Packing</th>
                            <th>Conv.</th>
                            <th>Item Code</th>
                            <th>Mfg.Company</th>
                            <th>Qty</th>
                            <th>MRP</th>
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

                       <td colspan=""><input type="text" name="qty" id="qty" class="w-60px" onkeyup="search_func(this.value);"/></td>
                  
                        <td colspan=""><input type="text" name="mrp" id="mrp" class="w-60px"  onkeyup="search_func(this.value);" /></td>

                        <td colspan=""><input type="text" name="discount" id="discount_search" class="w-80px"  onkeyup="search_func(this.value);"/></td>
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
    </div> <!-- stock_issue_allotment_tbl_box -->

     <div class="purchase_medicine_tbl_box" id="item_select">
       
    </div> <!-- stock_issue_allotment_tbl_box -->


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
                   
                   <?php } } ?>
                 
                   </div>
                  
                <div id="payment_detail">
                    

                </div>

                <div class="purchase_medicine_mod_of_payment">
                    <label>Total Amount</label>
                    <input type="text" name="total_amount" id="total_amount" onChange="payment_calc_all();" value="<?php echo $form_data['total_amount'];?>" readonly>
                </div>

                    <?php $discount_vals = get_setting_value('ENABLE_DISCOUNT'); 
                        if(isset($discount_vals) && $discount_vals==1){?>

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
                   <input class="m9" type="text" name="item_discount"  id="item_discount"  value="<?php echo $form_data['item_discount'];?>" >
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

                    <input type="text" name="pay_amount" id="pay_amount" value="<?php echo $form_data['pay_amount'];?>" class="price_float" onblur="payemt_vals(1);">

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
        </div> <!-- dont delete this div -->
       </div> <!-- stock_issue_allotment_return bottom-->
      </div> <!-- 12 -->
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
<style>

.ui-autocomplete { z-index:2147483647; }

</style>
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

<script>




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
               $('.selectpicker').selectpicker('refresh');
           
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
                 $('.selectpicker').selectpicker('refresh');
             
              }
          });
}
function appned_user_detail(user_type,emp_id)
{
  $.ajax({
          url: "<?php echo base_url('stock_issue_allotment_return/get_emp_data/'); ?>"+emp_id+'/'+user_type, 
          success: function(result)
          {
            //$('#vendor_data_append').addClass('well');
            $('#vendor_data_append').html(result);
            
          }
    });
}




  

    $(function () {
    $('#no_ret').hide();
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('stock_issue_allotment_return/search_sales/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
        data: {
         name_startsWith: request.term,
         row_num : row
      },
       success: function( data ) {
        if(data.status!=0)
        {          
                $('#no_ret').hide();
                  response( $.map( data, function( item ) {
                  var code = item.split("|");
                  return {
                    label: code[0],
                    value: code[0],
                    data : item
                  }
                }));
          }
        else{
            $('#no_ret').show();
        }
      }
      });

       
    };

   
    var selectItem = function (event, ui) 
    {  
      var names = ui.item.data.split("|");
      $('#issue_no').val(names[0]);
      $('#item_id').val(names[1]);
      $('#total_amount').val(names[2]);
      $('#discount_all').val(names[3]);
      $('#discount_amount').val(names[4]);
      $('#item_discount').val(names[5]);
      $('#sgst_amount').val(names[6]);
      $('#cgst_amount').val(names[7]);
      $('#igst_amount').val(names[8]);
      $('#net_amount').val(names[9]);
      $('#pay_amount').val(names[10]);
      $('#balance_due').val(names[11]);
      $('#payment_mode').val(names[12]);
      $('#issue_date').val(names[13]);
      $('#employee_type').val(names[14]);
      $('#employee_list_n').val(names[15]);
      $('#user_type').val(names[16]);
      $('#member_name').val(names[17]);
      $('#address').val(names[18]);
      

      set_sales_item(names[1]);
      return false;
    }

    $("#issue_no").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            
        }
    });
    });


  function set_sales_item(allVals)
  { 
 //alert(allVals );  
     if(allVals!="")
     {
        
        $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_issue_allotment_return/get_sales_item');?>",
              data: {item_id: allVals},
              dataType: "json",
              success: function(result) 
              {
                $('#item_select').html(result.data); 
               
                //payment_calc_all();  
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
<div id="load_add_emp_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
  $('input[id=purchase_submit]').click(function(){
    $(this).attr('disabled', 'disabled');
});
</script>


<!-- start added script 3-3-20 -->

<script>
  function get_total_amount(){

  // var amount= $('#price').val();
  var quantity= $('#quantity').val();
  var total_amount= amount *quantity;

  $('#amount').val(total_amount);

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
    $.ajax({url: "<?php echo base_url(); ?>stock_issue_allotment_return/reset_search/", 
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
                 url: "<?php echo base_url('stock_issue_allotment_return/delete/'); ?>"+id, 
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

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
           

            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("stock_issue_allotment_return/print_purchase_recipt"); ?>');">Print</a>

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


    <!--new css-->

<script>

$('#purchase_submit').click(function(){  
    $(':input[id=purchase_submit]').prop('disabled', true);
   $('#purchase_form').submit();
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
        url: "<?php echo base_url('stock_issue_allotment_return/get_payment_mode_data')?>",
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



function search_func()
{  
    var item = $('#item').val();
    var item_code = $('#item_code').val();
    var packing = $('#packing').val();  
    var conversion = $('#conversion').val();
    //var mfc_date = $('#mfc_date').val();
    var manuf_company = $('#manuf_company').val();
    var qty = $("#qty" ).val();
    var mrp = $('#mrp').val();
    var category_id = $('#category_id').val();
    var discount = $('#discount_search').val();
   // var vat = $('#vat').val();
    var cgst = $('#cgst').val();
    var sgst = $('#sgst').val();
    var igst = $('#igst').val();

    $.ajax({
       type: "POST",
       url: "<?php echo base_url('stock_issue_allotment_return/ajax_list_item')?>",
       data: {'item' : item,'item_code':item_code,'conversion':conversion,'manuf_company':manuf_company,'qty':qty,'mrp':mrp,'discount':discount,'cgst':cgst,'sgst':sgst,'igst':igst,'packing':packing,'category_id':category_id},
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
     // alert(allVals);
     if(allVals!="")
     {
      $.ajax({
        type: "POST",
        url: "<?php echo base_url('stock_issue_allotment_return/set_item');?>",
        data: {item_id: allVals},
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
            url: "<?php echo base_url(); ?>stock_issue_allotment_return/ajax_added_item", 
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
  
  } 
  function remove_item(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('stock_issue_allotment_return/remove_item_list');?>",
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

 
    var conversion = $('#conversion_'+ids).val();
    var item_id= $('#item_id_'+ids).val();
    var item_name= $('#item_name_'+ids).val();
    var qty= +($('#qty_'+ids).val());
    var mrp= $('#mrp_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    var category_id= $('#category_id_'+ids).val();
    var item_code= $('#item_code_'+ids).val();

    $('#unit1_max_'+ids).text('');
   // if(unit1 <= max_qty)
    //{
    
        $.ajax({
                type: "POST",
                url: "<?php echo base_url(); ?>stock_issue_allotment_return/payment_cal_perrow/", 
                dataType: "json",
                data: 'mrp='+mrp+'&item_name='+item_name+'&conversion='+conversion+'&manuf_date='+manuf_date+'&qty='+qty+'&item_id='+item_id+'&expiry_date='+expiry_date+'&cgst='+cgst+'&sgst='+sgst+'&igst='+igst+'&discount='+discount+'&category_id='+category_id+'&item_code='+item_code,

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
  var discount = $('#discount_all').val();
  var vat = $('#vat_percent').val(); 
  var net_amount = $('#net_amount').val();
  var pay_amount= $('#pay_amount').val();
  var discount_type = $('#discount_type').val();
  $.ajax({
    type: "POST",
    url: "<?php echo base_url(); ?>stock_issue_allotment_return/payment_calc_all/", 
    dataType: "json",
    data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id+'&discount_type='+discount_type,
    success: function(result)
    {
      $('#discount_amount').val(result.discount_amount);
      $('#total_amount').val(result.total_amount);
      $('#net_amount').val(result.net_amount);
      $('#pay_amount').val(result.pay_amount);
      $('#cgst_amount').val(result.cgst_amount);
      $('#igst_amount').val(result.igst_amount);
      $('#sgst_amount').val(result.sgst_amount);
      $('#discount_all').val(result.discount);
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
/*$('#discount_all').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});*/



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
 $allotment_id = $this->session->userdata('allotment_id');
 ?>
</script>
<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && isset($allotment_id) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('stock_issue_allotment_return/add');?>'; 
    }) ;
   
       
  <?php }?>
 });

$(document).ready(function() {
  $(".select2").select2();
});


    function add_serial(value)
    {   
            var ser_ar = '';
            var str_serial= $('#serial_no_array_'+value).val();
           // alert(str_serial);
            if(str_serial!='')
            {
                var str_serial=JSON.parse(str_serial); 
                var ser_ar=str_serial.split(',');
                
            }
          
            var quantity=$('#qty_'+value).val();

                  if(value != '1_'+quantity)
                  {

                     $('#add_serial_no').val('1_'+quantity);

                     $('#serial_no_data').html('');

                   
                     if(quantity > 0)
                     {
                      pr="<tr><td><input type='hidden' id='serial_row_no' name='serial_row_no' value='"+value+"'></td></tr>";
                       $('#serial_no_data').append(pr);
                      
                       for(i=1;i <= quantity;i++)
                       {
                         
                         var valss = ser_ar[i-1];
        
                           if(typeof valss === 'undefined')
                           {
                             var valssw =''; 
                             
                           }
                           else
                           {
                              var valssw = ser_ar[i-1];
                           }
                          
                        tr="<tr>";
                        tr+="<td>"+i+"</td><td><input type='text' value='"+valssw+"' onkeyup='get_serial_autocomplete("+i+","+value+");' id='serial_"+i+"' class='serial_"+i+"'><input type='hidden' value=''  id='issued_id_"+i+"' class='issued_id_"+i+"'></td>";
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

         

          function save_serial_no_records(value)
          {
             var serial_row_no = $('#serial_row_no').val();
               rows=[];
               rows_ids=[];
               for(i=1;i <= value; i++)
               {
                 val=$('#serial_'+i).val();
                 rows.push(val);
                 valids=$('#issued_id_'+i).val();
                 if(valids!='')
                 {
                   rows_ids.push(valids);  
                 }
                 
               }
               
               
               $("#serial_no_array_"+serial_row_no).val('"'+rows+'"');
               $("#issued_ser_id_no_array_"+serial_row_no).val(rows_ids);

          }
          
          
function get_serial_autocomplete(row_id,item_id)
{ 
    var issue_allot_id = '<?php echo $form_data['data_id'];?>';
    
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('stock_issue_allotment_return/search_serial_no/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
          issue_allot_id: issue_allot_id,
          item_id:item_id,
         type: 'country_table',
         row_num : row_id
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

          var names = ui.item.data.split("|");
          $('.serial_'+row_id).val(names[0]);
          $('#issued_id_'+row_id).val(names[1]);
          
          return false;
    }

    $(".serial_"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }
          
//Autocomplete function 
</script>

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
                <table class="table table-striped table-bordered doctor_list dataTable no-footer" role="grid" aria-describedby="table_info" style="width: 100%;" width="100%" cellspacing="0" id="show_serial_no_data"></table>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
         </div>
      </div>
   </div>
