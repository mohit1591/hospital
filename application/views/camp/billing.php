<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(4);
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>select2.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 


<script type="text/javascript">
var save_method; 
var table; 
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('test/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
 
 
function view_test(id)
{
  var $modal = $('#load_add_test_modal_popup');
  $modal.load('<?php echo base_url().'test/view/' ?>'+id,
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

function delete_particulars_vals() 
  {           
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       remove_particulars_vals(allVals);
  } 

  function remove_particulars_vals(allVals)
  {    
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('opd/remove_opd_particular');?>",
              data: {particular_id: allVals},
              dataType: "json",
              success: function(result) 
              { 
                $('#particular_list').html(result.html_data);
                $('#total_amount').val(result.total_amount); 
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.total_amount); 
                $('#balance').val(0);   
              }
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

<section class="path-booking">
<form action="<?php echo base_url('opd/billing'); ?>" method="post">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>" />
<input type="hidden" name="type" value="<?php echo $form_data['type']; ?>" />
<input type="hidden" name="pay_now" value="1" />

<div class="row">
  <div class="col-xs-4 media_50">
    
    <div class="row m-b-5">
      <div class="col-xs-6">
        <a class="btn-custom m2" href="<?php echo base_url('patient'); ?>"><i class="fa fa-user"></i> Registered Patient</a>
      </div>
      <div class="col-xs-6">
        
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Patient Reg. No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Receipt No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" name="booking_code" value="<?php echo $form_data['booking_code']; ?>"/>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Patient Name <span class="star">*</span></strong>
      </div>
      <div class="col-xs-8">
        <select class="mr" name="simulation_id" id="simulation_id"  onchange="find_gender(this.value)">
          <option value="">Select</option>
          <?php
            if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
                if($simulation->id==$form_data['simulation_id'])
                {
                   $selected_simulation = 'selected="selected"';
                }
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              }
            }
            ?> 
        </select> 
        <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="mr-name txt_firstCap"/>
        <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new d-flex"> New</a>
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
      </div>
      <?php if(in_array('65',$users_data['permission']['action'])) {
          ?>
              
                    
               
          <?php } ?>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Mobile No.
            <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==27 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
        </strong>
      </div>
      <div class="col-xs-8">
          <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
            
           <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number" placeholder="eg.9897221234" value="<?php echo $form_data['mobile_no']; ?>">
        
        <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='27' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile_no'); }
                         }
                    }
          ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Gender <span class="star">*</span></strong>
      </div>
      <div class="col-xs-8" id="gender">
        <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Age 
           <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==28 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
        </strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="age_y"  class="input-tiny numeric" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m"  class="input-tiny numeric" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d"  class="input-tiny numeric" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
              <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='28' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); }
                         }
                    }
                ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Email Id</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="patient_email" class="email_address" value="<?php echo $form_data['patient_email']; ?>" /> 
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-4">
      <div class="col-xs-4">
        <strong>Address</strong>
      </div>
      <div class="col-xs-8">
        <textarea name="address" id="address" maxlength="250"><?php echo $form_data['address']; ?></textarea>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Country</strong>
      </div>
      <div class="col-xs-8">
        <select name="country_id" id="countrys_id" onchange="return get_state(this.value);">
              <option value="">Select Country</option>
              <?php
              if(!empty($country_list))
              {
                foreach($country_list as $country)
                {
                  $selected_country = "";
                  if($country->id==$form_data['country_id'])
                  {
                    $selected_country = 'selected="selected"';
                  }
                  echo '<option value="'.$country->id.'" '.$selected_country.'>'.$country->country.'</option>';
                }
              }
              ?> 
            </select>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>State</strong>
      </div>
      <div class="col-xs-8">
        <select name="state_id" id="states_id" onchange="return get_city(this.value)">
            <option value="">Select State</option>
            <?php
           if(!empty($form_data['country_id']))
           {
              $state_list = state_list($form_data['country_id']); 
              if(!empty($state_list))
              {
                 foreach($state_list as $state)
                 {  
                  ?>   
                    <option value="<?php echo $state->id; ?>" <?php if(!empty($form_data['state_id']) && $form_data['state_id'] == $state->id){ echo 'selected="selected"'; } ?>><?php echo $state->state; ?></option>
                  <?php
                 }
              }
           }
          ?>
          </select>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>City</strong>
      </div>
      <div class="col-xs-8">
        <select name="city_id" id="citys_id">
              <option>Select City</option>
              <?php
               if(!empty($form_data['state_id']))
               {
                  $city_list = city_list($form_data['state_id']);
                  if(!empty($city_list))
                  {
                     foreach($city_list as $city)
                     {
                      ?>   
                        <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>>
                        <?php echo $city->city; ?> 
                        </option>
                      <?php
                     }
                  }
               }
              ?>
            </select> 
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Billing Date</strong>
      </div>
      <div class="col-xs-8">
         <input type="text" name="booking_date" class="datepicker" value="<?php echo $form_data['booking_date']; ?>" /> 
      </div>
    </div> <!-- row -->

  </div> <!-- Main 4 -->




  <div class="col-xs-4 media_50">
    
    
    <div class="row m-b-5">
     <div class="col-md-4"><b>Diseases</b></div>
        <div class="col-xs-8">
           <select name="diseases" id="diseases" class="">
              <option value="">Select Diseases</option>
              <?php
              if(!empty($diseases_list))
              {
                foreach($diseases_list as $diseases)
                {
                  ?>
                    <option <?php if($form_data['diseases']==$diseases->id){ echo 'selected="selected"'; } ?> value="<?php echo $diseases->id; ?>"><?php echo $diseases->disease; ?></option>
                    
                  <?php
                }
              }
              ?>
    </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    
                    <a  class="btn-new" id="diseases_add_modal"> New</a>
                <?php } ?>
         </div>
       </div>
   

   

    <!-- <div class="row m-b-5">
         <div class="col-md-4"><b>Consultant</b></div>
        <div class="col-xs-8">
           <select name="attended_doctor" class="" id="attended_doctor">
              <option value="">Select Consultant</option>
              <?php
             
                $doctor_list = doctor_specilization_list(); 
                
                if(!empty($doctor_list))
                {
                   foreach($doctor_list as $doctor)
                   {  
                    ?>   
                      <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                   }
                }
             
            ?>
            </select>
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal_2"> New</a>
                <?php } ?>
         </div>
       </div> -->


       <div class="row m-b-5">
    
         <div class="col-md-4"><b>Referred By</b></div>
         <div class="col-xs-8">
           <select name="referral_doctor" id="refered_id"   onChange="return get_other(this.value)">
              <option value="">Select Referred By</option>
              <?php
              if(!empty($referal_doctor_list))
              {
                foreach($referal_doctor_list as $referal_doctor)
                {
                  ?>
                    <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                    
                  <?php
                }
              }
              ?>

              <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal">New</a>
                <?php } ?>
         </div>
       </div>
     <!-- row -->
  
    <div class="row m-b-5" id="ref_by_other" <?php if($form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
         <div class="col-md-4"><b> Other </b></div>
         <div class="col-xs-8">
           <input type="text" name="ref_by_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
   <!-- row -->


   <!-- row -->
  

  <div class="row m-b-5">
         <div class="col-md-4"><b>Consultant</b></div>
        <div class="col-xs-8">
           <select name="attended_doctor" class="" id="attended_doctor">
              <option value="">Select Consultant</option>
              <?php
             
                $doctor_list = doctor_specilization_list(); 
                
                if(!empty($doctor_list))
                {
                   foreach($doctor_list as $doctor)
                   {  
                    ?>   
                      <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                   }
                }
             
            ?>
            </select>
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal_2"> New</a>
                <?php } ?>
         </div>
       </div>




   

    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Particulars</strong>
      </div>
      <div class="col-xs-8">
        <select name="particulars" id="particulars" class="" onchange="return get_particulars_data(this.value);">
                  <option value="">Select Particulars</option>
                  <?php
                  if(!empty($particulars_list))
                  {
                    foreach($particulars_list as $particularslist)
                    {
                      $selected_particulars = "";
                      if($particularslist->id==$form_data['particulars'])
                      {
                        $selected_particulars = 'selected="selected"';
                      }
                      echo '<option value="'.$particularslist->id.'" '.$selected_particulars.'>'.$particularslist->particular.'</option>';
                    }
                  }
                  ?> 
                </select>    
               <?php if(in_array('546',$users_data['permission']['action'])) {
               ?>
                    <a href="javascript:void(0)" onclick=" return add_particulars();"  class="btn-new"> New</a>
               <?php } ?>  
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Charges</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  name="charges" class="price_float" id="charges" value="">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Quantity</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  name="quantity" id="quantity"  class="numeric" onkeyup="get_particular_amount();" value="">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text"  class="price_float" name="amount" id="amount" value="">
        <a class="btn-new" onclick="particular_payment_calculation();"> Add </a>
      </div>
    </div> <!-- row -->




<?php $opd_particular_payment =  $this->session->userdata('opd_particular_payment'); ?>
    
    
    
    





  </div> <!-- Main 4 -->





  <div class="col-xs-4 media_100">
    
    <table class="table table-bordered table-striped opd_billing_table" id="particular_list">
      <thead class="bg-theme">
          <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);"><?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Particular</th>
              <th>Charges</th>
              <th>Quantity</th>
          </tr>
           </thead>
          <?php 
          $perticuller_list = $this->session->userdata('opd_particular_billing'); 

          $i = 0;
          if(!empty($perticuller_list))
          {
             $i = 1;
             foreach($perticuller_list as $perticuller)
             {

              ?>
                <tr>
                  <td>
                    <input type="checkbox" class="part_checkbox booked_checkbox" name="particular_id[]" value="<?php echo $perticuller['particular']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $perticuller['particulars']; ?></td>
                  <td><?php echo $perticuller['amount']; ?></td>
                  <td><?php echo $perticuller['quantity']; ?></td>
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_particulars_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
          
     

  </table>
    

  <div class="row m-b-5">
      <div class="col-xs-5" id="payment_box">
        <strong>Mode of Payment</strong>
      </div>
      <div class="col-xs-7">
        <select name="payment_mode" id="payment_mode"  onchange="get_payment_mode(this.value)">
        <?php 
                $selected_cash = '';
                $selected_cheque='';
                $selected_card ='';
                $selected_neft ='';
             
               if($form_data['payment_mode']=='cash'){
                    $selected_cash = 'selected="selected"';
                }elseif($form_data['payment_mode']=='cheque'){
                     $selected_cheque = 'selected="selected"';
                }elseif($form_data['payment_mode']=='card'){
                       $selected_card = 'selected="selected"';
                }elseif($form_data['payment_mode']=='neft'){
                       $selected_neft = 'selected="selected"';
                }
             ?>
           
           <option value="cash" <?php echo $selected_cash; ?>>Cash</option>
           <option value="cheque" <?php echo $selected_cheque; ?>>Cheque</option>
           <option value="card" <?php echo $selected_card; ?>>Card</option>
           <option value="neft" <?php echo $selected_neft; ?>> Neft</option>

      </select> 
      <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" id="card" style="display:none;">
      <div class="col-xs-5">
        <strong>Transaction No</strong>
      </div>
      <div class="col-xs-7">
        <input type="text"  name="transaction_no" class="alpha_numeric" id="transaction_no" value="<?php echo $form_data['transaction_no']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('transaction_no'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" id="cheque" style="display:none;">
      <div class="col-xs-5">
        <strong>Cheque No</strong>
      </div>
      <div class="col-xs-7">
        <input type="text"  class="numeric" name="cheque_no" id="cheque_no" value="<?php echo $form_data['cheque_no']; ?>" />
         
            <?php if(!empty($form_error)){ echo form_error('cheque_no'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" id="branch" style="display:none;">
      <div class="col-xs-5">
        <strong>Bank Name</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" name="branch_name" id="branchname" value="<?php echo $form_data['branch_name']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('branch_name'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong>Total Amount</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" readonly="" class="price_float" name="total_amount" id="total_amount" value="<?php if(!empty($opd_particular_payment['total_amount'])) { echo $opd_particular_payment['total_amount']; } else{ echo $form_data['total_amount']; } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong>Discount</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" name="discount" class="price_float" onkeyup="discount_vals();" id="discount" value="<?php if(!empty($opd_particular_payment['discount'])) { echo $opd_particular_payment['discount']; } else{ echo $form_data['discount']; } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong>Net Amount</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" readonly="" class="price_float" name="net_amount" id="net_amount" value="<?php if(!empty($opd_particular_payment['net_amount'])) { echo $opd_particular_payment['net_amount']; } else{ echo $form_data['net_amount']; } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong>Paid Amount</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" name="paid_amount" class="price_float" id="paid_amount" value="<?php if(!empty($opd_particular_payment['paid_amount'])) { echo $opd_particular_payment['paid_amount']; } else{ echo $form_data['paid_amount']; } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" style="display: none;">
      <div class="col-xs-5">
        <strong>Balance</strong>
      </div>
      <div class="col-xs-7">
        <input type="text" name="balance" id="balance" class="price_float" value="<?php echo $form_data['balance']; ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-5">
        <strong></strong>
      </div>
      <div class="col-xs-7">
        <button class="btn-save"><i class="fa fa-floppy-o"></i> Submit</button>
      </div>
    </div> <!-- row -->


  </div> <!-- Main 4 -->

</div> <!-- MainRow -->



















  <!-- box -->


</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>
<script>
function get_other(val)
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


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
});

$(document).ready(function() {
  $(".select2").select2();
}); 
</script>
<script>  
function get_particulars_data(particulars_id)
{
    var charges = $('#charges').val();
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    $.ajax({url: "<?php echo base_url(); ?>general/particulars_list/"+particulars_id, 
      success: function(result)
      {
        var result = JSON.parse(result);
        $('#charges').val(result.charges);
        $('#amount').val(result.amount); 
        $('#quantity').val(result.quantity);  
      } 
    });
    //get_particular_amount(); 
  }


  function get_particular_amount()
  {
    var charges = $('#charges').val();
    var quantity = $('#quantity').val();
    var amount = $('#amount').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/particular_calculation/", 
            dataType: "json",
            data: 'charges='+charges+'&quantity='+quantity,
            success: function(result)
            { 
               $('#amount').val(result.amount); 
            } 
          });
  }

  
    function simulation_modal()
  {
      var $modal = $('#load_add_simulation_modal_popup');
      $modal.load('<?php echo base_url().'simulation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
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

  <?php
  if($i>0)
  {
    $total_i = $i;
    echo 'var row_sn = '.$total_i.';';
  }
  else
  {
    echo 'var row_sn = 1;';
  }
  ?>
  /*
   
  function get_particular_vals()
  { 
       var charges =''; 
       var quantity='';
       var amount ='';
       var particular='';
       var particulars='';
       charges = $('#charges').val();
       quantity = $('#quantity').val();
       amount = $('#amount').val();
       particular = $('#particulars').val();
       particulars =  $('#particulars option:selected').text();
      
      $("#particular_list").append('<tr style="background-color:White;white-space:nowrap;" align="left"><td><input type="checkbox" class="part_checkbox booked_checkbox" name="particular_id[]" value="'+particular+'" ></td><td>'+row_sn+'</td><td>'+particulars+'</td><td>'+amount+'</td><td>'+quantity+'</td></tr>');
    
      particular_payment_calculation();
      $('#charges').val('');
      $('#amount').val('');
      $('#quantity').val('1');  
      $('#particular').val('');
      //$("#particulars").select2("val", "");
      $("#particulars option[value='']").attr('selected', true);
      row_sn = row_sn+1;


  }
  */

  $(document).ready(function(){

    $("#particular_list").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    });


  function particular_payment_calculation()
  {
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var particular = $('#particulars').val();
    var particulars = $('#particulars option:selected').text();
    var discount = $('#discount').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/particular_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&particular='+particular+'&particulars='+particulars+'&balance='+balance+'&discount='+discount,
            success: function(result)
            {
              $('#particular_list').html(result.html_data);
              $('#total_amount').val(result.total_amount); 
              $('#net_amount').val(result.net_amount); 
              $('#discount').val(result.discount); 
              $('#paid_amount').val(result.net_amount); 
              $('#balance').val(0);

              $('#charges').val('');
              $('#amount').val('');
              $('#quantity').val('1');  
              $('#particular').val('');
              $("#particulars option[value='']").attr('selected', true); 
               
            } 
          });
  }


    
  function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

  
  function discount_vals()
  {
     var timerA = setInterval(function(){ 
                                          payment_calc();
                                          clearInterval(timerA); 
                                        }, 1600);
  }

  function payment_calc()
  {
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/particular_payment_disc/", 
            dataType: "json",
            data: 'total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance,
            success: function(result)
            {
               $('#total_amount').val(result.total_amount); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.paid_amount); 
               $('#balance').val(result.balance); 
               
            } 
          });
  }

function get_state(country_id)
{
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#states_id').html(result); 
      } 
    });
    get_city(); 
  }

function get_city(state_id)
{
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#citys_id').html(result); 
      } 
    }); 
  }

  $(document).ready(function()
{
        var id = $("#payment_mode").val();
        if(id){
           get_payment_mode(id); 
        }
 })

    function get_payment_mode(id='')
    {
              
          if(id=='cheque'){
                document.getElementById("cheque").style.display="block";
                document.getElementById("card").style.display="none";
                document.getElementById("branch").style.display="block";
             
          }else if(id=='card' || id=='neft'){
             
              document.getElementById("cheque").style.display="none";
              document.getElementById("card").style.display="block";
              document.getElementById("branch").style.display="none";
              $("#branchname").val('');
              $("#chequedate").val('');
             
          
          }else{
              document.getElementById("cheque").style.display="none";
              document.getElementById("card").style.display="none";
               document.getElementById("branch").style.display="none";
              $("#branchname").val('');
              $("#chequedate").val('');
              $("#transactionno").val('');
             
          }
    }

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }


  function add_particulars()
  {
    var $modal = $('#load_add_particular_modal_popup');
    $modal.load('<?php echo base_url().'particular/add/' ?>',
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

  function add_spacialization()
{
  var $modal = $('#load_add_specialization_modal_popup');
  $modal.load('<?php echo base_url().'specialization/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
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
$('#doctor_add_modal_2').on('click', function(){
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
$(document).ready(function(){
var $modal = $('#load_add_disease_modal_popup');

$('#diseases_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'disease/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});


});

function find_gender(id=''){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }

</script> 
<!-- Confirmation Box -->

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
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_particular_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- Confirmation Box end -->
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_specialization_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
</div>

<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div><!-- container-fluid -->
</body>
</html>