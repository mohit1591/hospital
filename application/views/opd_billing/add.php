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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 




<script type="text/javascript">
var save_method; 
var table; 

 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function delete_particulars_vals_dental() 
  {           
       var allVals = [];
       $('.booked_checkbox_dental').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       remove_particulars_vals_dental(allVals);
  } 

  function remove_particulars_vals_dental(allVals)
  {    
   if(allVals!="")
   {
      var particulars_charges = $('#particulars_charges').val();
      var discount = $('#discount').val();
      var paid_amount = $('#paid_amount').val();
      var kit_amount = $('#kit_amount').val();
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('opd_billing/remove_opd_particular_dental');?>",
              
              data: {particular_id: allVals,particulars_charges:particulars_charges,discount:discount,paid_amount:paid_amount,kit_amount:kit_amount},
              
              dataType: "json",
              success: function(result) 
              { 
                $('#particular_list_dental').html(result.html_data);
                $('#particulars_charges').val(result.particulars_charges);
                $('#total_amount').val(result.total_amount); 
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.total_amount); 
                $('#balance').val(0);   
              }
          });
   }
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
      var particulars_charges = $('#particulars_charges').val();
      var discount = '0.00';//$('#discount').val();
      var paid_amount = $('#paid_amount').val();
      var kit_amount = $('#kit_amount').val();
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('opd_billing/remove_opd_particular');?>",
              
              data: {particular_id: allVals,particulars_charges:particulars_charges,discount:discount,paid_amount:paid_amount,kit_amount:kit_amount},
              
              dataType: "json",
              success: function(result) 
              { 
                $('#particular_list').html(result.html_data);
                $('#particulars_charges').val(result.particulars_charges);
                $('#total_amount').val(result.total_amount); 
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.total_amount); 
                $('#balance').val(0);   
              }
          });
   }
  }

 
function generate_token(vals)
   {
   // alert(vals);

          var doctor_id = vals;
          var branch_id = $('#branch_id').val();
          var booking_date = $('#booking_date').val();
<?php if(empty($form_data['data_id'])){ ?>
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/generate_token/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&booking_date='+booking_date,
              success: function(result)
              {    
                $('#token_no').val(result.token_no); 
              }
          });

      <?php } ?>
   }
  
   function generate_token_by_date(vals)
   {
     //alert(vals);
     
      var booking_date = vals;
      var booking_date = $('#booking_date').val();
      var branch_id = $('#branch_id').val();
      var doctor_id = $('#attended_doctor').val();
<?php if(empty($form_data['data_id'])){ ?>
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd_billing/generate_token/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&booking_date='+booking_date,
              success: function(result)
              {    
                $('#token_no').val(result.token_no);
              
              }
          });
      <?php } ?>
   }
   
   
   function generate_token_date()
   {
     var booking_date = $('#booking_date').val(); 
     var branch_id = $('#branch_id').val();
      var doctor_id = $('#attended_doctor').val();
<?php if(empty($form_data['data_id'])){ ?>
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd_billing/generate_token_date/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&booking_date='+booking_date,
              success: function(result)
              {    
                $('#token_no').val(result.token_no);
              
              }
          });
      <?php } ?>
   }

   function generate_token(vals)
   {
   // alert(vals);

          var doctor_id = vals;
          var branch_id = $('#branch_id').val();
          var booking_date = $('#booking_date').val();
<?php if(empty($form_data['data_id'])){ ?>
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd_billing/generate_token/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&booking_date='+booking_date,
              success: function(result)
              {    
                $('#token_no').val(result.token_no); 
              }
          });

      <?php } ?>
   }
  
</script>

</head>

<body onLoad="set_tpa(<?php echo $form_data['pannel_type']; ?>); generate_token_date();">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
   //print_r($dental_billed_list);
                  //print_r($dental_booking_list);
                 // die;
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="path-booking">
<form action="<?php echo current_url(); ?>" method="post" id="opd_billing_form">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" />
<input type="hidden" name="type" value="<?php echo $form_data['type']; ?>" />
<input type="hidden" name="pay_now" value="1" />
<input type="hidden" name="token_type" id="token_type" value="<?php echo $form_data['token_type']; ?>" />
<input type="hidden" name="branch_id" value="<?php echo $users_data['parent_id']; ?>" id="branch_id">

<div class="row">
  <div class="col-xs-4 media_50">
    
    <div class="row m-b-5">
      <div class="grp">
          <span class="new_patient"><input type="radio" name="new_patient" <?php if(empty($form_data['patient_id'])) { ?> checked <?php } ?> > <label>New Patient</label></span>
          <span class="new_patient"><input type="radio" name="new_patient" onClick="window.location='<?php echo base_url('patient');?>';" <?php if(!empty($form_data['patient_id'])) { ?> checked <?php } ?>> <label>Registered Patient</label></span>
       </div>
     <div class="col-xs-6">
        
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" class="m_input_default" name="patient_code" id="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Receipt No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" name="reciept_code" class="m_input_default" value="<?php echo $form_data['reciept_code']; ?>"/>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4 pr-0">
        <strong>Patient Name <span class="star">*</span></strong>
      </div>
      <div class="col-xs-8">
        <select class="mr m_mr" name="simulation_id" id="simulation_id"  onchange="find_gender(this.value)">
          <option value="">Select</option>
          <?php
            //$simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
          $simulations_arr=[];
          if(!empty($simulation_array))
          {
              foreach($simulation_array as $simulation_array)
              {
                $simulations_arr[]=$simulation_array->id;
              }    
          }
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
        <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="mr-name m_name txt_firstCap" style="width:85px!important;" autofocus/>
        <a title="Add Simulation" href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
      </div>
      
    </div> <!-- row -->

    <!-- new code by mamta -->
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong> 
          <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
          <?php foreach($gardian_relation_list as $gardian_list) 
          {?>
          <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
          <?php }?>
          </select>

             </strong>
      </div>
      <div class="col-xs-8">
        <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id">
          <option value="">Select</option>
          <?php
            if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
               
              if($simulation->id==$form_data['relation_simulation_id'])
              {
                   $selected_simulation = 'selected="selected"';
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
        <input type="text" id="mobile_no" name="mobile_no" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text numeric m_input_default" value="<?php echo $form_data['mobile_no']; ?>" maxlength="10"  onkeyup="get_patient_detail_by_mobile();">
        
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
        <input type="text" name="age_y" id="age_y"  class="input-tiny m_tiny numeric" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m" id="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
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
        <input type="text" name="patient_email" id="patient_email" class="email_address m_input_default" value="<?php echo $form_data['patient_email']; ?>" /> 
      </div>
    </div> <!-- row -->

    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Aadhaar No.</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="adhar_no" class="numeric m_input_default" value="<?php echo $form_data['adhar_no']; ?>" /> 
        <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>DOB</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" class="datepicker_dob" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>"  />
        <?php if(!empty($form_error)){ echo form_error('dob'); } ?>
      </div>
    </div> <!-- row -->
    
      <div class="row m-b-4">
      <div class="col-xs-4">
        <strong>Address 1</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="address" class="m_input_default address" id="address" maxlength="250" value="<?php echo $form_data['address']; ?>"/>
      </div>
    </div> <!-- row -->
     <div class="row m-b-4">
      <div class="col-xs-4">
        <strong>Address 2</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="address_second" class="m_input_default address" id="address" maxlength="250" value="<?php echo $form_data['address_second']; ?>"/>
      </div>
    </div> <!-- row -->
     <div class="row m-b-4">
      <div class="col-xs-4">
        <strong>Address 3</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="address_third" class="m_input_default address" id="address" maxlength="250" value="<?php echo $form_data['address_third']; ?>"/>
      </div>
    </div> <!-- row -->

    
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Country</strong>
      </div>
      <div class="col-xs-8">
        <select name="country_id" class="m_input_default" id="countrys_id" onchange="return get_state(this.value);">
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
        <select name="state_id" class="m_input_default" id="states_id" onchange="return get_city(this.value)">
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
        <select name="city_id" class="m_input_default" id="citys_id">
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
    <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Panel Type</b> <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A doctor within a given area available for consultation by patients insured under the National Health Insurance Scheme It has two type <br> Normal: Having no policy. <br>Panel:Having policy.</span></a></sup></div> 
         <div class="col-xs-8" id="pannel_type11">
           <input type="radio" name="pannel_type" id='' value="0" onclick="set_tpa(0); particular_payment_calculation(); refresh_page();" <?php if($form_data['pannel_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
            <input type="radio" name="pannel_type" value="1" id='' onclick="set_tpa(1);" <?php if($form_data['pannel_type']==1){ echo 'checked="checked"'; } ?>> Panel
            <?php if(!empty($form_error)){ echo form_error('pannel_type'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div id="panel_box">
   <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Type</b></div>
        <div class="col-xs-8">
                <select name="insurance_type_id" id="insurance_type_id" class="w-150px m_select_btn">
                  <option value="">Select Insurance Type</option>
                  <?php
                  if(!empty($insurance_type_list))
                  {
                    foreach($insurance_type_list as $insurance_type)
                    {
                      $selected_ins_type = "";
                      if($insurance_type->id==$form_data['insurance_type_id'])
                      {
                        $selected_ins_type = 'selected="selected"';
                      }
                      echo '<option value="'.$insurance_type->id.'" '.$selected_ins_type.'>'.$insurance_type->insurance_type.'</option>';
                    }
                  }
                  ?> 
                </select>

                <?php if(in_array('72',$users_data['permission']['action'])) { ?>
            <a title="Add Insurance Type"  class="btn-new" onclick="insurance_type_modal()"  id="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>

             

            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
              


     <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Name</b></div>
        <div class="col-xs-8">
                <select name="ins_company_id" id="ins_company_id" class="w-150px m_select_btn" onchange="particular_payment_calculation(1);">
                  <option value="">Select Insurance Company</option>
                  <?php
                  if(!empty($insurance_company_list))
                  {
                    foreach($insurance_company_list as $insurance_company)
                    {
                      $selected_company = '';
                      if($insurance_company->id == $form_data['ins_company_id'])
                      {
                        $selected_company = 'selected="selected"';
                      }
                      echo '<option value="'.$insurance_company->id.'" '.$selected_company.'>'.$insurance_company->insurance_company.'</option>';
                    }
                  }
                  ?> 
                </select>

                <?php if(in_array('79',$users_data['permission']['action'])) { ?>
            <a title="Add Insurance Company" class="btn-new" id="insurance_company_modal" onclick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Policy No.</b></div>
        <div class="col-xs-8">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>TPA ID</b></div>
        <div class="col-xs-8">
            <input type="text" name="tpa_id" class="alpha_numeric" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Insurance Amount</b></div>
        <div class="col-xs-8">
            <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onKeyPress="return isNumberKey(event);" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-xs-4"><b>Authorization No.</b></div>
        <div class="col-xs-8">
            <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->

</div>  
    
    

  </div> <!-- Main 4 -->




  <div class="col-xs-4 media_50">
    
<?php if(in_array('916',$users_data['permission']['action'])) { ?>
    <div class="row m-b-5">
     <div class="col-md-3"><b>Source From</b></div>
        <div class="col-xs-9">
           <select name="source_from" id="patient_source_id" class="m_select_btn">
              <option value="">Select Source</option>
              <?php
              if(!empty($source_list))
              {
                foreach($source_list as $source)
                {
                  ?>
                    <option <?php if($form_data['source_from']==$source->id){ echo 'selected="selected"'; } ?> value="<?php echo $source->id; ?>"><?php echo $source->source; ?></option>
                    
                  <?php
                }
              }
              ?>
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) { ?>
            <a title="Add Source" class="btn-new" id="patient_source_add_modal"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
         </div>
       </div>
  <?php }else{ ?> <input type="hidden" name="source_from" value="0"> <?php } ?>  
    <div class="row m-b-5">
     <div class="col-md-3"><b>Diseases</b></div>
        <div class="col-xs-9">
           <select name="diseases" id="disease_id" class="m_select_btn">
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
                    
                    <a title="Add Diseases" class="btn-new" id="diseases_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>
       
       <?php if(in_array('411',$users_data['permission']['section'])) { ?>
       <div class="row m-b-5">
     <div class="col-md-3"><b>Patient Category</b></div>
        <div class="col-xs-9">
                              <select name="patient_category" id="patient_category" class="m_select_btn">
                                  <option value="">Select Category</option>
                                  <?php
                                  if(!empty($patient_category_list))
                                  {
                                    foreach($patient_category_list as $patientcategory)
                                    {
                                      ?>
                                        <option <?php if($form_data['patient_category']==$patientcategory->id){ echo 'selected="selected"'; } ?> value="<?php echo $patientcategory->id; ?>"><?php echo $patientcategory->patient_category; ?></option>
                                        
                                      <?php
                                    }
                                  }
                                  ?>
                            </select> 
                            <?php if(in_array('2486',$users_data['permission']['action'])) {
                ?><a title="Add Patient Category" class="btn-new" id="patient_category_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
                              
                </div>
       </div>
        <?php }else{ ?>
        <input type="patient_category" id="patient_category" value="0">
        
        <?php } if(in_array('411',$users_data['permission']['section'])) { ?>  
           <div class="row m-b-5">
     <div class="col-md-3"><b>Authorize Person</b></div>
        <div class="col-xs-9">
                              <select name="authorize_person" id="authorize_person" class="m_select_btn">
                                  <option value="">Select Authorize Person</option>
                                  <?php
                                  if(!empty($authrize_person_list))
                                  {
                                    foreach($authrize_person_list as $authrizelist)
                                    {
                                      ?>
                                        <option <?php if($form_data['authorize_person']==$authrizelist->id){ echo 'selected="selected"'; } ?> value="<?php echo $authrizelist->id; ?>"><?php echo $authrizelist->authorize_person; ?></option>
                                        
                                      <?php
                                    }
                                  }
                                  ?>
                            </select> 
                            <?php if(in_array('2493',$users_data['permission']['action'])) {
                ?><a title="Add authorize person" class="btn-new" id="authorize_person_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
                              
              </div>
       </div>
       <?php }else { ?> <input type="authorize_person" id="authorize_person" value="0"> <?php } ?>
       
      <div class="row m-b-5">
          <div class="col-md-3"><b>Referred By</b></div>
              <div class="col-xs-9" id="referred_by">
               
                 <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
                  <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
                  <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
               </div>
      </div>
          <!-- row -->
      <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?>>
    
         <div class="col-md-3"><b>Referred By Doctor</b></div>
         <div class="col-xs-9">
           <select name="referral_doctor" id="refered_id" class="m_select_btn"  onChange="return get_others(this.value)">
              <option value="">Select Doctor</option>
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
            <?php if(in_array('122',$users_data['permission']['action'])) { ?>
                    <a title="Add Referral Doctor" class="btn-new" id="doctor_add_modal"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
         </div>
       </div>
     <!-- row -->
  
    <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
       <div class="col-md-3"><b> Other </b></div>
       <div class="col-xs-9">
        <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
          <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
     </div>
   </div>
   <!-- row -->



   <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
    
         <div class="col-md-3"><b>Referred By Hospital</b></div>
         <div class="col-xs-9">
           <select name="referral_hospital" id="referral_hospital">
              <option value="">Select hospital</option>
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
                <a title="Add Hospital"  class="btn-new" id="hospital_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>

    
  
<div class="row m-b-5">
    <div class="col-md-3"><b>Specialization</b></div>
    <div class="col-xs-9" id="specilizationid">
     <select name="specialization" class="m_select_btn" id="specilization_id" onChange="return get_doctor_specilization_dental(this.value);">
              <option value="">Select Specialization</option>
              <?php

              if(!empty($specialization_list))
              {
                $select='';
                foreach($specialization_list as $specializationlist)
                {
                  if($form_data['specialization_id']==$specializationlist->id)
                  {
                   $select='selected';
                  }
                  else
                  {
                    $select='';

                  }

                  ?>
                    <option <?php echo $select; ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                  <?php
                }
              }
              ?>
            </select> 
     <?php if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                    <a title="Add Specialization" href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new"><i class="fa fa-plus"></i> New</a>
               <?php } ?>
                 <?php if(!empty($form_error)){ echo form_error('specialization'); } ?>
   </div>
  </div>



  <div class="row m-b-5">
    <div class="col-md-3"><b>Consultant</b> <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a doctor type which have two forms one is attended other is referral it may be both. </span></a></sup></div>
    <div class="col-xs-9">
     <select name="attended_doctor" class="m_select_btn" id="attended_doctor" onchange="generate_token(this.value);">
        <option value="">Select Consultant</option>
        <?php
           //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
          $doctor_list = doctor_specilization_list(); 
          
          if(!empty($doctor_list))
          {
             foreach($doctor_list as $doctor)
             {  
               //if($doctor->id!==$referral_doctor_id){
                 
            ?>   
              <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
            <?php
              //}
           }
        }
     
    ?>
    </select>
    <?php if(in_array('122',$users_data['permission']['action'])) {
        ?>
            <a title="Add Consultant" class="btn-new" id="doctor_add_modal_2"><i class="fa fa-plus"></i> New</a>
        <?php } ?>
   </div>
  </div>

  <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Billing Date</strong>
      </div>
      <div class="col-xs-9">
         <input type="text" name="booking_date" id="booking_date" class="datepicker m_input_default" value="<?php echo $form_data['booking_date']; ?>"   onchange="generate_token_by_date(this.value);"/> 
      </div>
    </div> <!-- row particulars -->

    <div class="row m-b-5">
      <div class="col-xs-3"><b>Billing Time </b></div>
      <div class="col-xs-9">
           <input type="text" name="booking_time" id="bookingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['booking_time']; ?>" />
         </div>
    </div>

    <div class="row m-b-5">
    <div class="col-xs-3"><b>Token No.</b></div>
         <div class="col-xs-9">
           <input type="text" id="token_no" readonly class="m_input_default" name="token_no" value="<?php echo $form_data['token_no']; ?>"/>
         </div>
  </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Particulars <span class="star">*</span> </strong><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is an objects used in hospital like injections,xray machine.</span></a></sup>
      </div>
      <div class="col-xs-9">
        <select name="particulars" id="particulars" class="m_select_btn" onchange="return get_particulars_data(this.value);">
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
                    <a title="Add Particulars" href="javascript:void(0)" onclick="return add_particulars();"  class="btn-new"><i class="fa fa-plus"></i> New</a>
               <?php } ?>  
               
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Charges</strong>
      </div>
      <div class="col-xs-9">
        <input type="text"  name="charges" class="price_float m_input_default" id="charges" value="<?php echo $form_data['charges'] ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" id="quantity_id">
      <div class="col-xs-3">
        <strong>Quantity</strong>
      </div>
      <div class="col-xs-9">
        <input type="text"  name="quantity" id="quantity"  class="numeric m_input_default" onkeyup="get_particular_amount();" value="<?php echo $form_data['quantity'] ?>">
      </div>
    </div> <!-- row -->
    <?php
$specialization_val_tooth='';
  if(!empty($form_data['specialization_id']))
  {
    if($form_data['specialization_id']==DENTAL_SPECIALIZATION_ID)
    {
$specialization_val_tooth='display:block';
    }
    else
    {
      $specialization_val_tooth='display:none';
    }
  }
  else
  {
     $specialization_val_tooth='display:none';
  }

  ?>
    <div class="row m-b-5" id='tooth' style="<?php echo $specialization_val_tooth;?>">
    <input type="hidden"  name="tooth_num_val" id="tooth_num_val"  class="numeric m_input_default" value="">
    <div class="col-md-3"><b>Tooth No.</b></div>
    <div class="col-xs-9" id="tooth_num_id">
     <select name="tooth_num" class="m_select_btn" id="tooth_num">
              <option value="">Select Tooth No.</option>
              <?php
              if(!empty($teeth_number_list))
              {
                $i=1;
                foreach($teeth_number_list as $teeth_list)
                {
                  $selected_teeth_list = "";
                      if($teeth_list->id==$form_data['tooth_num'])
                      {
                        $selected_teeth_list = 'selected="selected"';
                      }
                      else if($i==1)
                      {
                        $selected_teeth_list = 'selected="selected"';
                        
                      }
                      echo '<option value="'.$teeth_list->teeth_number.'" '.$selected_teeth_list.'>'.$teeth_list->teeth_number.'</option>';
                  ?>

                    
                  <?php
                $i++;
                }
              }
              ?>
            </select> 
            
     <a title="Add Particular" class="btn-new" onclick="dental_particular_payment_calculation();"> Add </a>
   </div>
  </div>
        <?php
$specialization_val_amount='';
  if(!empty($form_data['specialization_id']))
  {
    if($form_data['specialization_id']==DENTAL_SPECIALIZATION_ID)
    {
$specialization_val_amount='display:none';
    }
    else
    {
      $specialization_val_amount='display:block';
    }
  }
  else
  {
     $specialization_val_amount='display:block';
  }

  ?>
    
    <div class="row m-b-5" id="amount_id" style='<?php echo $specialization_val_amount;?>'>
      <div class="col-xs-3">
        <strong>Amount</strong>
      </div>
      <div class="col-xs-9">
        <input type="text"  class="price_float m_input_default" name="amount" id="amount" value="<?php echo $form_data['amount']; ?>"> <a title="Add Charge" class="btn-new" onclick="particular_payment_calculation(0);"> Add </a>
         <?php  //echo form_error('particularid');  ?>
         <?php  echo form_error('particularidnew');  ?>
      </div>
    </div> <!-- row -->

    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Remarks</strong>
      </div>
      <div class="col-xs-9">
        <textarea name="remarks" id="remarks" class="m_input_default" maxlength="250"><?php echo $form_data['remarks']; ?></textarea>
      </div>
    </div> <!-- row -->





<?php 
$opd_particular_payment =  $this->session->userdata('opd_particular_payment'); ?>
    </div> <!-- Main 4 -->
    <?php
$specialization_val='';
  if(!empty($form_data['specialization_id']))
  {
    if($form_data['specialization_id']==DENTAL_SPECIALIZATION_ID)
    {
$specialization_val='display:none';
    }
    else
    {
      $specialization_val='display:block';
    }
  }
  else
  {
     $specialization_val='display:block';
  }

  ?>
  <div class="col-xs-4">
    <div id="particular_list_id" style="<?php echo $specialization_val; ?>">
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
      <?php  if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php  echo form_error('particular_id');  ?></td>
        </tr>
        <?php } ?>
  </table>
  </div>
  <?php
$specialization_val='';
  if(!empty($form_data['specialization_id']))
  {
    if($form_data['specialization_id']==DENTAL_SPECIALIZATION_ID)
    {
$specialization_val='display:block';
    }
    else
    {
      $specialization_val='display:none';
    }
  }
  else
  {
     $specialization_val='display:none';
  }

  ?>

<div id="particular_list_dental_id" style="<?php echo $specialization_val ;?>">

  <table class="table table-bordered table-striped opd_billing_table" id="particular_list_dental">
      <thead class="bg-theme">
          <tr>
              <th align="center" width="">
                <input name="selectall_dental" class="" id="selectall_dental" value="" type="checkbox"  onclick="toggle_dental(this);"><?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
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
              <th>Tooth No.</th>
          </tr>
           </thead>
          <?php 
          $perticuller_list_dental = $this->session->userdata('dental_opd_particular_billing');
         
          //$perticuller_list = $this->session->userdata('opd_particular_billing'); 
      //print_r($perticuller_list_dental);
          $i = 0;
          if(!empty($perticuller_list_dental) && (isset($perticuller_list_dental)))
          {
             $i = 1;
             foreach($perticuller_list_dental as $perticuller_dental)
             {
              //print_r($perticuller_dental);

              ?>
                <tr>
                  <td>
                    <input type="checkbox" class="part_checkbox_dental booked_checkbox_dental" name="particular_id[]" value="<?php echo $perticuller_dental['particular']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $perticuller_dental['particulars']; ?></td>
                  <td><?php echo $perticuller_dental['amount']; ?></td>
                  <td><?php echo $perticuller_dental['tooth_num_val']; ?></td>
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_particulars_vals_dental();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php  echo form_error('particular_id');  ?></td>
        </tr>
        <?php } ?>
  </table>
  </div>
    

<?php //if(in_array('91',$users_data['permission']['section'])): ?>   
<!-- <div class="row m-b-5">
             
   <div class="col-xs-4" id="payment_box"><b>Medicine Kit</b>

</div>
     <div class="col-xs-8">
          <select name="package_id" class="m_input_default"  id="package_id" onchange="return kits_charge(this.value);">
             <option value="">Select Medicine Kit</option>
             < ?php
                  if(!empty($package_list))
                  {
                       foreach($package_list as $package)
                       {  
                         if($package->total_kit_qty>0)
                          {
                       ?>   
                            <option value="< ?php echo $package->id; ?>" < ?php if(!empty($form_data['package_id']) && $form_data['package_id'] == $package->id){ echo 'selected="selected"'; } ?>>< ?php echo $package->title; ?></option>
                       < ?php
                         }
                       }
                  }

              ?>
            </select> 
            < ?php if(!empty($form_error)){ echo form_error('package_id'); } ?>
         </div>
      </div>
 -->  <?php //else: ?>
               <input type="hidden" name="package_id" id="package_id" value="" />
          <?php //endif; ?>       
   <div class="row m-b-5">
               
      <div class="col-xs-4" id="payment_box"><b>Next Appointment</b></div>
      <div class="col-xs-8">
      <input type="text" name="next_app_date" class="datepicker m_input_default" value="<?php echo $form_data['next_app_date']; ?>" /> 
                            
       </div>
    </div>
               

  <div class="row m-b-5">
      <div class="col-xs-4" id="payment_box">
        <strong>Mode of Payment</strong>
      </div>
      <div class="col-xs-8">
        <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
     
      </div>
    </div> <!-- row -->
    
   <div id="updated_payment_detail">
                 <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {
                     $tot_values= explode('_',$field_names);

                    ?>

                  <div class="row m-b-5" id="branch"> 
                  <div class="col-xs-4">
                  <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                  </div>
                  <div class="col-xs-8"> 
                  <input type="text" name="field_name[]" class="m_input_default" value="<?php echo $tot_values[0];?>" /><input type="hidden" class="m_input_default" value="<?php echo $tot_values[2];?>" name="field_id[]" />
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
    
    
    
<?php //if(in_array('91',$users_data['permission']['section'])): ?>
    <!-- <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Kit Amount</strong>
      </div>
      <div class="col-xs-8">
          <input type="text"  name="kit_amount" id="kit_amount" class="price_float m_input_default" onblur="update_kit_amount(this.value);" value="<?php echo number_format($form_data['kit_amount'],2,'.', ''); ?>">
     </div>
    </div> --> <!-- row -->
<?php //else: ?>
    <input type="hidden" name="kit_amount" class="m_input_default" id="kit_amount" value="" />
  <?php //endif; ?>       
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Particular Charge</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly=""  class="price_float m_input_default" name="particulars_charges" id="particulars_charges" value="<?php if(!empty($opd_particular_payment['particulars_charges'])) { echo number_format($opd_particular_payment['particulars_charges'],2,'.',''); } else{ echo number_format($form_data['particulars_charges'],2,'.',''); } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Total Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" class="price_float m_input_default" name="total_amount" id="total_amount" value="<?php if(!empty($opd_particular_payment['total_amount'])) { echo number_format($opd_particular_payment['total_amount'],2,'.',''); } else{ echo number_format($form_data['total_amount'],2,'.',''); } ?>">
      </div>
    </div> <!-- row -->
<?php 

$discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
if($discount_val_setting==1)
{
?>
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Discount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="discount" class="price_float m_input_default" onkeyup="check_paid_amount();discount_vals();" id="discount" value="<?php if(!empty($opd_particular_payment['discount'])) { echo $opd_particular_payment['discount']; } else{ echo $form_data['discount']; } ?>">
      </div>
    </div> <!-- row -->
<?php } else{ 

?>
<input type="hidden" name="discount" class="price_float m_input_default" id="discount" value="">

<?php 

  } ?> 
  


    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Net Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" readonly="" class="price_float m_input_default" name="net_amount" id="net_amount" value="<?php if(!empty($opd_particular_payment['net_amount'])) { echo number_format($opd_particular_payment['net_amount'],2,'.',''); } else{ echo number_format($form_data['net_amount'],2,'.',''); } ?>">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong>Paid Amount</strong>
      </div>
      <div class="col-xs-8">
        <input type="text" name="paid_amount" class="price_float m_input_default" id="paid_amount" value="<?php if(!empty($opd_particular_payment['paid_amount'])) { echo number_format($opd_particular_payment['paid_amount'],2,'.',''); } else{ echo number_format($form_data['paid_amount'],2,'.',''); } ?>" onkeyup="check_paid_amount();">
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5" style="display: none;">
      <div class="col-xs-4">
        <strong>Balance</strong>
      </div>
      <?php
        $balance = '0.00';
       if(!empty($form_data['balance'])){
          $balance = number_format($form_data['balance'],2,'.','');
        } ?>
      <div class="col-xs-8">
        <input type="text" name="balance" id="balance" class="price_float m_input_default" value="<?php echo $balance; ?>">
      </div>
    </div>


    <div class="row m-b-5">
      <div class="col-xs-4">
        <strong></strong>
      </div>
      <div class="col-xs-8">
        <button class="btn-save" id="btnsubmit"><i class="fa fa-floppy-o"></i> Submit</button>
        <button class="btn-update" type="button" onclick="window.location.href='<?php echo base_url('opd_billing'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $("#particulars").select2();
</script>
<script>
 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
$(document).ready(function(){
var $modal = $('#load_add_authorize_person_modal_popup');

$('#authorize_person_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'authorize_person/add/' ?>',
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
   
   var $modal = $('#load_add_patient_category_modal_popup');

$('#patient_category_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient_category/add/' ?>',
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
$('#load_add_patient_category_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
 
});

$(document).ready(function(){

  $('#load_add_authorize_person_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
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
        
          $('#ref_other').val("");
      }
        
    });
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
  function get_doctor_specilization(specilization_id)
{   
//var special_val= $( "#specilization_id option:selected" ).text();
//alert(special_val);
  $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {

        $('#attended_doctor').html(result); 
      } 
    });

}

function get_doctor_specilization_dental(specilization_id,branch_id)
{   
//alert();
var special_val= $( "#specilization_id option:selected" ).text();
//alert(special_val);
  if((special_val=='Dental (Default)') || (special_val=='Dental (Default)')) 
   {
  
//alert("1");
    document.getElementById('tooth').style.display = "block";
    document.getElementById('particular_list_dental_id').style.display = "block";
    document.getElementById('amount_id').style.display = "none";
      document.getElementById('quantity_id').style.display = "none";
    document.getElementById('particular_list_id').style.display = "none";
    
    }
    else
    {
    
      
       document.getElementById('amount_id').style.display = "block";
      document.getElementById('particular_list_id').style.display = "block";
        document.getElementById('particular_list_dental_id').style.display = "none";
            document.getElementById('quantity_id').style.display = "block";
        document.getElementById('tooth').style.display = "none";
        //$("#particular_list_dental").html("");
       
    }
    if(typeof branch_id === "undefined" || branch_id === null)
    {
        $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
    }
    else
    {

      $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
   }
 }

 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('opd_billing/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
   }  


$(document).ready(function(){
   

    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });
     var simulation_id = $("#simulation_id :selected").val();
    find_gender(simulation_id);   
});


</script>
<script>  
function father_husband_son()
{
   $("#relation_name").css("display","block");
}
function get_particulars_data(particulars_id)
{

    var charges = $('#charges').val();
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var ins_company_id = $('#ins_company_id :selected').val();
    //alert(particulars_id);
    //alert(ins_company_id);
    if(ins_company_id!='')
    {
      var ins_company_id_val=ins_company_id;
      var url="<?php echo base_url(); ?>opd_billing/particulars_list_pannel/"+particulars_id +'/'+ins_company_id_val;  
      //alert(url);  
    } 
    else
    {
      var ins_company_id_val='';
      var url="<?php echo base_url(); ?>general/particulars_list/"+particulars_id;

    }
     var pannel_type= document.querySelector('input[name="pannel_type"]:checked').value;
           $.ajax({url: url, 
      success: function(result)
      {
        var result = JSON.parse(result);
        console.log(result);
        if(pannel_type==1)
        {
          $('#charges').val(result.charges);
        $('#amount').val(result.amount); 
        $('#quantity').val(result.quantity);


       
        }
        else
        {

        $('#charges').val(result.charges);
        $('#amount').val(result.amount); 
        $('#quantity').val(result.quantity);
        }  
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
  

  $(document).ready(function(){

    $("#particular_list").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    });

  function refresh_page()
  {
    var panel_type =  $('input[name=pannel_type]:checked').val();
    if(panel_type==1)
    {
    
    }
    else
    {

     window.location.href="<?php echo base_url('opd_billing/add'); ?>"; 

    }
  }

 
  function particular_payment_calculation(test_val)
  {

    
    var specilization_id = $('#specilization_id :selected').val(); 
     if(specilization_id=='675')
     {
      dental_particular_payment_calculation();
     }
     else
     {

     
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var particular = $('#particulars').val();
    var particulars = $('#particulars option:selected').text();
    var discount = $('#discount').val();
    var kit_amount = $('#kit_amount').val();
    var particulars_charges = $('#particulars_charges').val();
    var panel_type =  $('input[name=pannel_type]:checked').val();
  
    var ins_company_id = $('#ins_company_id :selected').val(); 
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd_billing/particular_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&particular='+particular+'&particulars='+particulars+'&balance='+balance+'&discount='+discount+'&kit_amount='+kit_amount+'&particulars_charges='+particulars_charges+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&test_val='+test_val,
            success: function(result)
            {
             
              //$('#particular_list').html(result.html_data);
              $('#particular_list').html(result.html_data);
              $('#total_amount').val(result.total_amount); 
              $('#net_amount').val(result.net_amount);
              $('#kit_amount').val(result.kit_amount);
              $('#particulars_charges').val(result.particulars_charges);  
              $('#discount').val(result.discount); 
              $('#paid_amount').val(result.net_amount); 
              $('#balance').val(0);

              $('#charges').val('');
              $('#amount').val('');
              $('#quantity').val('1');  
              //$('#particulars :selected').val(''); 
              //$('#particular').val('');
               $("#particulars").prop("selectedIndex", 0);
              //$("#particulars option[value='']").attr('selected', true); 
               
            } 
          });
  }
  }


 function dental_particular_payment_calculation()
  {
    
    var amount = $('#amount').val();
    var tooth_num = $('#tooth_num').val();

    var tooth_num_val = $('#tooth_num option:selected').text();
    $('#tooth_num_val').val(tooth_num_val);
    var quantity = $('#quantity').val();
    var particular = $('#particulars').val();
    var particulars = $('#particulars option:selected').text();
    var discount = '0.00';
    //var discount = $('#discount').val();
    var kit_amount = $('#kit_amount').val();
    var particulars_charges = $('#particulars_charges').val();
     var panel_type =  $('input[name=pannel_type]:checked').val(); 
    //   if(panel_type==1)
    // {
    
    // }
    // else
    // {

    //  window.location.href="<?php echo base_url('opd_billing/add'); ?>";
       
    // }
    var ins_company_id = $('#ins_company_id :selected').val(); 
   // alert(particulars_charges);
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd_billing/dental_particular_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&particular='+particular+'&particulars='+particulars+'&balance='+balance+'&discount='+discount+'&kit_amount='+kit_amount+'&particulars_charges='+particulars_charges+'&tooth_num_val='+tooth_num_val+'&tooth_num='+tooth_num+'&ins_company_id='+ins_company_id+'&panel_type='+panel_type,
            success: function(result)
            {
            

              $('#particular_list_dental').html(result.html_data);
              $('#total_amount').val(result.total_amount); 
              $('#net_amount').val(result.net_amount);
              $('#kit_amount').val(result.kit_amount);
              $('#particulars_charges').val(result.particulars_charges);  
              $('#discount').val(result.discount); 
              $('#paid_amount').val(result.net_amount); 
              $('#balance').val(0);

              $('#charges').val('');
              $("#particulars").prop("selectedIndex", 0);
              $('#amount').val('');
              $('#quantity').val('1');  
               
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
   function toggle_dental(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_dental');
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

  function check_paid_amount()
  {
    
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    
    if(parseInt(discount)>parseInt(total_amount))
    {
      alert('Discount amount can not be greater than total amount');
      $('#discount').val('0.00');
      return false;
    }
    if(parseInt(paid_amount)>parseInt(net_amount))
    {
      alert('Paid amount can not be greater than total amount');
      $('#paid_amount').val(net_amount);
      return false;
    }
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
            url: "<?php echo base_url(); ?>opd_billing/particular_payment_disc/", 
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

  function add_teeth_number()
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


$('.cheque_date').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
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

function kits_charge(vals)
  {
       var package_id = vals;
       
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var particulars_charges = $('#particulars_charges').val();
        var kit_amount = $('#kit_amount').val();
      if(package_id!='')
      {
      $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>opd_billing/package_rate/", 
          dataType: "json",
          data: 'package_id='+package_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&particulars_charges='+particulars_charges,
          success: function(result)
          {
             
             $('#kit_amount').val(result.kit_amount);
             $('#particulars_charges').val(result.particulars_charges);
             $('#total_amount').val(result.total_amount);
             $('#net_amount').val(result.net_amount); 
             $('#discount').val(result.discount); 
             $('#paid_amount').val(result.paid_amount);
             $('#balance').val(result.balance);
          }
      });

      }
        else
        {
          update_kit_amount('0.00');
        }

      
  }


 function update_kit_amount(val)
 {
     if(val!='')
     {
        var kit_amount = val;
        var particulars_charges = $('#particulars_charges').val();
        var discount = $('#discount').val();
        
         $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd_billing/update_amount/", 
            dataType: "json",
            data: 'kit_amount='+kit_amount+'&particulars_charges='+particulars_charges+'&discount='+discount,
            success: function(result)
            {
               
                $("#particulars_charges").val(result.particulars_charges);
                $("#kit_amount").val(result.kit_amount);
                $("#net_amount").val(result.net_amount);
                $("#paid_amount").val(result.net_amount);
                $("#total_amount").val(result.total_amount); 

               
               
            } 
          });
        
        
     }
     else
     {
        $("#net_amount").val('0.00');
        $("#particulars_charges").val('0.00');
        $("#paid_amount").val('0.00');
        $("#total_amount").val('0.00');
     }
 }

$('.datepicker_dob').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    //startView: 2
  })
  
 $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });
$('.datepicker3').datetimepicker({
      format: 'LT'
  });
 $(document).ready(function(){
var $modal = $('#load_add_emp_type_modal_popup');

$('#patient_source_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient_source/add/' ?>',
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

$(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});

$(document).ready(function(){
  $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_particular_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_specialization_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
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
</script> 

<!--new css-->
  <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
  <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
  <!--new css-->
  <script>


$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('opd_billing/add');?>'; 
    }); 
       
  <?php }?>
 });

function set_tpa(val)
 { 
    if(val==0)
    {
      $('#panel_box').slideUp();
      $('#insurance_type_id').attr("disabled", true);
      $('#insurance_type_id').val('');
      $('#ins_company_id').attr("disabled", true);
      $('#ins_company_id').val('');
      $('#polocy_no').attr("readonly", "readonly");
      $('#polocy_no').val('');
      $('#tpa_id').attr("readonly", "readonly");
      $('#tpa_id').val('');
      $('#ins_amount').attr("readonly", "readonly");
      $('#ins_amount').val('');
      $('#ins_authorization_no').attr("readonly", "readonly");
      $('#ins_authorization_no').val('');
      
    }
    else
    {
      $('#panel_box').slideDown();
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
      $('#ins_authorization_no').removeAttr("readonly", "readonly");
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
<div id="load_add_emp_type_modal_popup" class="modal fade top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>
  
<div id="confirm_billing_print" class="modal fade dlt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
      <!-- <div class="modal-body"></div> -->
      <div class="modal-footer">
         <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd_billing/print_billing_report"); ?>');">Print</a>
       
        <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
      </div>
    </div>
  </div>  
</div>

<script>

  $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#opd_billing_form').submit();
  })
  
</script>

     
<script>
//neha 14-2-2019
 function get_patient_detail_by_mobile() {
  var val = $('#mobile_no').val();
   if(val.length==10)
   {
    
    $.ajax({
      url: "<?php echo site_url('opd_billing/get_patient_detail_no_mobile'); ?>/"+val,
      type: 'POST',
      async : false,
      success: function(datas) {
       var data = $.parseJSON(datas);
        if(data.st==1)
        { 
          // Add response in Modal body
         $('.modal-body').html(data.patient_list);

      // Display Modal
        $('#patient_proceed').modal('show'); 
        }
       }
    });
    return false;
  }
};
 //neha 14-2-2019
  //neha 25-2-2019
    $(document).ready(function(){
       
        $("#proceed").click(function(){

            if($('input[name="patient_id"]:checked').length == 0){

                 alert("Please select a patient");
                  return false;

            }
            else
            {
              var action_url = '<?php echo site_url('opd_billing/add/'); ?>'
              var radioValue = $("input[name='patient_id']:checked").val();
               $.ajax({
                    url: "<?php echo site_url('opd_billing/get_patient_detail_byid'); ?>/"+radioValue,
                    type: 'POST',
                    async : false,
                   success: function(datas) {
                   var data = $.parseJSON(datas);

                   if(data.st==1)
                  { 

                    $('#patient_id').val(data.patient_detail.id);
                    $("#registered").attr('checked', true);
                    $('#new').attr('checked', false);
                    $('#patient_code').val(data.patient_detail.patient_code);
                    //$("#simulation_id option:selected").val(data.patient_detail.simulation_id);
                    $('#patient_name').val(data.patient_detail.patient_name);
                    //$("#relation_simulation_id option:selected").val(data.patient_detail.relation_simulation_id);
                    $('#relation_name').val(data.patient_detail.relation_name);
                    $('#mobile_no').val(data.patient_detail.mobile_no);
                    //$('#gender_'+data.patient_detail.gender).attr('checked', true);
                    $('#gender_'+data.patient_detail.gender).attr('checked', 'checked');
                    $('#age_y').val(data.patient_detail.age_y);
                    $('#age_m').val(data.patient_detail.age_m);
                    $('#age_d').val(data.patient_detail.age_d);
                    $('#age_h').val(data.patient_detail.age_h);
                    $('#opd_billing_form').attr('action', action_url+data.patient_detail.id); 
                    $("#simulation_id option[value="+data.patient_detail.simulation_id+"]").attr('selected', 'selected');
                    $("#relation_simulation_id option[value="+data.patient_detail.relation_simulation_id+"]").attr('selected', 'selected');
                    $("#relation_type_id option[value="+data.patient_detail.relation_type+"]").attr('selected', 'selected');
                    find_gender(data.patient_detail.simulation_id);
                    
                  //find_gender(data.patient_detail.simulation_id);
                  }
                }

             });

          }

        });

    });

     //neha 25-2-2019
</script>

 <div id="patient_proceed" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Patient Already Registered!. Do You Want to procced?</h4></div>
           <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="proceed">Continue</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 
<div id="load_add_patient_category_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_authorize_person_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 