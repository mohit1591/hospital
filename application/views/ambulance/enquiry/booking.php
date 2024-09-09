<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(3);
//print_r($doctor_specialization_list);die;
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

$('document').ready(function(){
 <?php
 $opd_booking_id = $this->session->userdata('opd_booking_id');
 if(isset($_GET['status']) && isset($opd_booking_id) && $_GET['status']=='print'){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('opd/booking');?>'; 
    }); 
       
  <?php } ?>
 });

</script>

</head>

<body onLoad="set_tpa(<?php echo $form_data['pannel_type']; ?>),set_default_doct(<?php echo $form_data['specialization_id']; ?>, <?php echo $form_data['attended_doctor']; ?>)">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<?php //print_r($form_data);?>
<section class="path-booking">
<form action="<?php echo current_url(); ?>" method="post" id="opd_form">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>" id="patient_id"/>
<input type="hidden" name="type" value="2" />
<input type="hidden" name="token_type" id="token_type" value="<?php echo $form_data['token_type']; ?>" />


<div class="row">
<div class="col-xs-4">



<div class="row">
        <div class="col-md-12">
            <div class="grp">
                <span class="new_patient"><input type="radio" name="new_patient" <?php if(empty($form_data['patient_id'])) { ?> checked <?php } ?> > <label>New Patient</label></span>
                <span class="new_patient"><input type="radio" name="new_patient" onClick="window.location='<?php echo base_url('patient');?>';" <?php if(!empty($form_data['patient_id'])) { ?> checked <?php } ?>> <label>Registered Patient</label></span>
             </div>
        </div>
    </div>    <!-- endRow -->


  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>OPD Type</b></div>
         <div class="col-md-8" id="opd_type">
           <input type="radio" name="opd_type" value="0" onclick="update_doctor_charges('0');" <?php if($form_data['opd_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
            <input type="radio"  name="opd_type"  onclick="update_doctor_charges('1');" value="1" <?php if($form_data['opd_type']==1){ echo 'checked="checked"'; } ?>> <?php echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');  ?>
            <?php if(!empty($form_error)){ echo form_error('opd_type'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>MLC</b></div>
         <div class="col-md-8">
           <label>
            <input type="radio" name="mlc_status" value="1" <?php if($form_data['mlc_status']=="1" && !empty($form_data['mlc_status'])){echo 'checked';} ?> onchange="check_status(1);">Yes</label> &nbsp;
                <label><input type="radio" name="mlc_status" value="0" <?php if($form_data['mlc_status']=="0" && !empty($form_data['mlc_status'])){echo 'checked';} ?> onchange="check_status(0);">No</label>

                 <input type="text" name="mlc" value="<?php echo $form_data['mlc'];?>" id="mlc_status" <?php if($form_data['mlc_status']=='1' && !empty($form_data['mlc_status'])){echo "style='display:block;' readonly";} else{echo "style='display:none;'";}?>/>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  <!-- row -->
  

  
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Branches</b></div>
         <div class="col-md-8">
           <?php    $users_data = $this->session->userdata('auth_users');
                    $sub_branch_details = $this->session->userdata('sub_branches_data');
                    $parent_branch_details = $this->session->userdata('parent_branches_data');
                    //$branch_name = get_branch_name($parent_branch_details[0]);
                ?>
        <select name="branch_id" id="branch_id">
           <option value="">Select Branch</option>
           
           <option   <?php if($users_data['parent_id']==$form_data['branch_id']){ ?> selected="selected" <?php } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
             <?php 
             if(!empty($sub_branch_details)){
                 $i=0;
                foreach($sub_branch_details as $key=>$value){
                     ?>
                     <option <?php if($sub_branch_details[$i]['id']==$form_data['branch_id']){ ?> selected="selected" <?php } ?> value="<?php echo $sub_branch_details[$i]['id'];?>"><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                     <?php 
                     $i = $i+1;
                 }
               
             }
            ?> 
            </select>
         </div>
       </div>
    </div>
  </div> <!-- row -->


  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b> <?php echo $data= get_setting_value('PATIENT_REG_NO');?></b></div>
         <div class="col-md-8">
            <input type="text" class="m_input_default" readonly="" id="patient_code" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
         </div>
       </div>
    </div>
  </div> <!-- row -->
 
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>OPD No.</b></div>
         <div class="col-md-8">
           <input type="text" readonly="" id="booking_code" class="m_input_default" name="booking_code" value="<?php echo $form_data['booking_code']; ?>"/> 
         </div>
       </div>
    </div>
  </div> <!-- row -->
  
 
 <div class="row m-b-5">
   <div class="col-md-4"><b>Patient Name <span class="star">*</span></b></div>
   <div class="col-md-8">
     <select class="mr m_mr alpha_space"  name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
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
                if(in_array($simulation->simulation,$simulations_arr))
                {

                   $selected_simulation = 'selected="selected"';

                }
                else
                {
                if($simulation->id==$form_data['simulation_id'])
                {

                    $selected_simulation = 'selected="selected"';
                }

                }
               
               
                   
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              }
            }
            ?> 
        </select> 
        <input type="text" name="patient_name" id="patient_name" style="width:128px!important;" value="<?php echo $form_data['patient_name']; ?>" class="mr-name m_name txt_firstCap"  autofocus/>
        <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new move1"> New</a>
          <div class="clear">
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
          <?php if(in_array('65',$users_data['permission']['action'])) {
           ?>
              
         
    <?php } ?>
         </div>
   </div>
 </div>
     
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
         
          <?php
             $selected_simulation = '';
              foreach($simulation_list as $simulation)
              {
                
                    $selected_simulation='';
                  if(isset($form_data['relation_simulation_id']) && $simulation->id==$form_data['relation_simulation_id'])
                  {
                       $selected_simulation = 'selected="selected"';
                  }
                       
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              
            }
            ?> 
        </select> 
        <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
      </div>
    </div> <!-- row -->

<!-- new code by mamta -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Mobile No.
           <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==25 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
         </b></div>
         <div class="col-md-8">
           <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91"> 
            
           <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number m_number" placeholder="eg.9897221234" value="<?php echo $form_data['mobile_no']; ?>">
              <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='25' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile_no'); } 
                        }
                  }
            ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Gender <span class="star">*</span></b></div>
         <div class="col-md-8" id="gender">
           <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
              <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Age 
            <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==26 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
         </b></div>

         <div class="col-md-8">
              <input type="text" id="age_y" name="age_y" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
              <input type="text" id="age_m" name="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
              <input type="text" id="age_d" name="age_d"  class="input-tiny m_tiny numeric"  maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
              <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='26' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); }
                        }
                  } 
               ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"></div>
         <div class="col-md-8" style="text-align: center;">
            <a href="javascript:void(0);" onclick="more_patient_info()">More Info</a>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div id="patient_info" style="display: none;"> 
  
 <div class="row m-b-5" id="pedic_spec" >
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>DOB</b></div>
         <div class="col-md-8">
           <input type="text" class="datepicker_dob" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>"  /> <!-- onchange="showAge(this.value);"-->
         </div>
       </div>
    </div>
  </div>
  

  
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Email Id </b></div>
         <div class="col-md-8">
           <input type="text" name="patient_email" class="email_address m_input_default" value="<?php echo $form_data['patient_email']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-4">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address 1</b></div>
         <div class="col-md-8">
           <input type="text" name="address" id="address" class="m_input_default address" maxlength="250" value="<?php echo $form_data['address']; ?>">
         </div>
       </div>
    </div>
  </div> <!-- row -->

   <div class="row m-b-4">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address 2</b></div>
         <div class="col-md-8">
             <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php echo $form_data['address_second']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->

   <div class="row m-b-4">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address 3</b></div>
         <div class="col-md-8">
          <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php echo $form_data['address_third']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-4">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Aadhaar No.</b></div>
         <div class="col-md-8">
           <input type="text" name="adhar_no" id="adhar_no" class="m_input_default" value="<?php echo $form_data['adhar_no']; ?>" class="numeric"/>
           <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
         </div>
       </div>
    </div>
  </div>


  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Country</b></div>
         <div class="col-md-8">
           <select name="country_id" id="countrys_id" class="m_input_default" onchange="return get_state(this.value);">
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
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>State</b></div>
         <div class="col-md-8">
           <select name="state_id" id="states_id" class="m_input_default" onchange="return get_city(this.value)">
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
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>City</b></div>
         <div class="col-md-8">
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
       </div>
    </div>
  </div> <!-- row -->

  </div>  
  
 
 </div>
<!-- // ================ Second column -->



<div class="col-xs-4 media_margin_left">

<?php if(in_array('916',$users_data['permission']['action'])) { ?>
<div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Source From</b></div>
        <div class="col-md-7" id="patient_sources_id">
           <select name="source_from" id="patient_source_id" class="w-150px m_select_btn">
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
            <a  class="btn-new" id="patient_source_add_modal"> New</a>
            <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
 <?php }else{ ?> <input type="hidden" name="source_from" value="0"> <?php } ?> 
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Diseases</b></div>
        <div class="col-md-7" id="diseaseid">
           <select name="diseases" id="disease_id" class="w-150px m_select_btn">
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
    </div>
  </div> <!-- row -->
<?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By</b></div>
         <div class="col-md-7" id="referred_by">
           <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
            <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
            <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->


  <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By Doctor</b></div>
         <div class="col-md-7">
           <select name="referral_doctor" id="refered_id" class="w-150px m_select_btn"  onChange="return get_other(this.value)">
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
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal">New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  


    <div class="row m-b-5" id="ref_by_other" <?php if($form_data['referral_doctor']=='0' && !empty($form_data['ref_by_other'])){ }else{ ?> style="display: none;" <?php } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b> Other </b></div>
         <div class="col-md-7">
           <input type="text" name="ref_by_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->


<div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By Hospital</b></div>
         <div class="col-md-7">
           <select name="referral_hospital" id="referral_hospital" class="w-150px m_select_btn">
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $referal_hospital)
                {
                  ?>
                    <option <?php if($form_data['referral_hospital']==$referal_hospital['id']){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital['id']; ?>"><?php echo $referal_hospital['hospital_name']; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="hospital_add_modal">New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
<?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){ ?>

<div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred by</b></div>
         <div class="col-md-7">
           <select name="referral_doctor" id="refered_id" class="w-150px m_select_btn"  onChange="return get_other(this.value)">
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
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal">New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  


    <div class="row m-b-5" id="ref_by_other" <?php if($form_data['referral_doctor']=='0' && !empty($form_data['ref_by_other'])){ }else{ ?> style="display: none;" <?php } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b> Other </b></div>
         <div class="col-md-7">
           <input type="text" name="ref_by_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<input type="hidden" name="referred_by" value="0">
  <input type="hidden" name="ref_by_other" value="0"> 
  <input type="hidden" name="referral_hospital" value="0">
<?php }  else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){ ?>
  <div class="row m-b-5" >
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred by</b></div>
         <div class="col-md-7">
           <select name="referral_hospital" id="referral_hospital" class="w-150px m_select_btn">
              <option value="">Select Hospital</option>
              <?php
              if(!empty($referal_hospital_list))
              {
                foreach($referal_hospital_list as $ref_hospital)
                {

                  ?>
                    <option <?php if($form_data['referral_hospital']==$ref_hospital['id']){ echo 'selected="selected"'; } ?> value="<?php echo $ref_hospital['id']; ?>"><?php echo $ref_hospital['hospital_name']; ?></option>
                    
                  <?php
                }
              }
              ?>

              
            </select> 
            <?php if(in_array('998',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="hospital_add_modal">New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div>

  <input type="hidden" name="referred_by" value="1">
  <input type="hidden" name="ref_by_other" value="0"> 
  <input type="hidden" name="referral_doctor" value="0">

<?php } ?>
 

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Booking Date</b></div>
         <div class="col-md-7">
           <input type="text" name="booking_date"  id="booking_date" class="datepicker m_input_default" value="<?php echo $form_data['booking_date']; ?>" onchange="generate_token_by_date(this.value); get_available_days('',this.value); <?php if($form_data['data_id']=='') { ?> consultant_charge('',this.value); get_validity_date_in_between('',this.value); change_validity_date('',this.value);<?php }?> "/>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">

         <div class="col-md-5"><b>Specialization 
          <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']=='41' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id'])
                    { ?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
            ?>
             </b>
          </div>
         <div class="col-md-7" id="specilizationid">
           <select name="specialization" class="w-150px m_select_btn" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
              <option value="">Select Specialization</option>
              <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specializationlist)
                {
                  ?>
                    <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                  <?php
                }
              }
              ?>
            </select> 
             <?php if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                    <a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>
               <?php } ?>
                 <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='41' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('specialization'); } 
                        }
                  }
            ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  




  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Consultant  
          <?php if(!empty($field_list)){
                    if($field_list[3]['mandatory_field_id']=='42' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id'])
                    { ?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
            ?></b>
           <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a doctor type which have two forms one is attended other is referral it may be both. </span></a></sup></div>
         <div class="col-md-7">
           <select name="attended_doctor" class="w-150px m_select_btn" id="attended_doctor" onchange="consultant_charge(this.value);  generate_token(this.value);  <?php if(!empty($form_data['patient_id']) && $form_data['data_id']=='') { ?> get_validity_date_in_between(this.value); change_validity_date(this.value);<?php }?>">
              <option value="">Select Consultant</option>
              <?php
              //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
             if(!empty($form_data['specialization_id']))
             {
                $doctor_list = doctor_specilization_list($form_data['specialization_id'],$form_data['branch_id']); 

                
                if(!empty($doctor_list))
                {
                   foreach($doctor_list as $doctor)
                   {  //if($doctor->id!==$referral_doctor_id){
                    ?>   
                      <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                     //}
                   }
                }
             }
            ?>
            </select>

            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal_2">New</a>
                <?php } ?>
                <?php if(!empty($field_list)){
                         if($field_list[3]['mandatory_field_id']=='42' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('attended_doctor'); } 
                        }
                  }
            ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

   <!-- row --> 
  <div class="row m-b-5" id="available_time" <?php if(empty($form_data['available_time'])){ ?> style="display: none;" <?php } ?> >
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Time</b></div>
         <div class="col-md-7">
            <select name="available_time" class="m_select_btn" id="doctor_time" onchange="return select_a_slot(this.value);">
              <option value="">Select time</option>
              <?php
                if(!empty($doctor_available_time))
                {
                    foreach($doctor_available_time as $doctor_av_time)
                    {
                        ?> 
                        <option <?php if($form_data['time_value']==date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time))){ echo 'selected="selected"';} ?> value="<?php echo $doctor_av_time->id; ?>"> <?php echo date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time)); ?> </option>
                        
                   <?php  }
                } 

              ?>
            </select>

  </div>
  </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5" id="available_slot"  <?php if(empty($form_data['doctor_slot'])){ ?> style="display: none;" <?php } ?> >
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Slot</b></div>
         <div class="col-md-7">
            <select name="doctor_slot" class="m_select_btn" id="doctor_slot">
              <!-- <option value="">Select time</option> -->
              <?php echo $doctor_available_slot; ?>
            </select>

  </div>
  </div>
    </div>
  </div> <!-- row -->

  
  <div class="row m-b-5" id="doctor_avalaible" style="display: none;">
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Time</b></div>
         <div class="col-md-7">
         <span id="doctor_not_avalaible"></span>

  </div>
  </div>
    </div>
  </div>

  
    <div class="row m-b-5" id="booking_time" <?php /* if(empty($form_data['booking_time']) || $form_data['booking_time']=='00:00:00'){ ?> style="display: none;" <?php  }*/ ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Booking Time </b></div>
         <div class="col-md-7">
           <input type="text" name="booking_time" id="bookingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['booking_time']; ?>" />
         </div>
       </div>
    </div>
  </div> <!-- row -->


  <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Token No.</b></div>
         <div class="col-md-7">
           <input type="text" id="token_no" readonly class="m_input_default" name="token_no" value="<?php echo $form_data['token_no']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Validity Date</b></div>
         <div class="col-md-7">
           <input type="text" name="validity_date" class="validity_date m_input_default" value="<?php echo $form_data['validity_date']; ?>" id="validity_date" readonly="true"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->
 <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
        <div class="col-md-5"><b>Remarks</b></div>
         <div class="col-md-7">
           <textarea name="remarks" class="m_input_default" id="remarks" maxlength="250"><?php echo $form_data['remarks']; ?></textarea>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<?php //echo "<pre>"; print_r($form_data);die(); ?>

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Panel Type</b> <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A doctor within a given area available for consultation by patients insured under the National Health Insurance Scheme It has two type <br> Normal: Having no policy. <br>Panel:Having policy.</span></a></sup></div> 
         <div class="col-md-7" id="pannel_type">
           <input type="radio" name="pannel_type" value="0" onclick="update_doctor_panel_charges(); set_tpa(0);" <?php if($form_data['insurance_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
            <input type="radio" name="pannel_type" value="1" onclick="update_doctor_panel_charges();set_tpa(1);" <?php if($form_data['insurance_type']==1){ echo 'checked="checked"'; } ?>> Panel
            <?php if(!empty($form_error)){ echo form_error('pannel_type'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
<div id="panel_box">
   <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Type</b></div>
        <div class="col-md-7">
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
            <a  class="btn-new" onclick="insurance_type_modal()"  id="insurance_type_modal()"> New</a>

             

            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
              


     <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Name</b></div>
        <div class="col-md-7">
                <select name="ins_company_id" id="ins_company_id" class="w-150px m_select_btn" onchange="update_doctor_panel_charges();">
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
            <a  class="btn-new" id="insurance_company_modal" onclick="insurance_company_modal()"> New</a>
            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Policy No.</b></div>
        <div class="col-md-7">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>TPA ID</b></div>
        <div class="col-md-7">
            <input type="text" name="tpa_id" class="alpha_numeric" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Insurance Amount</b></div>
        <div class="col-md-7">
            <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onKeyPress="return isNumberKey(event);" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Authorization No.</b></div>
        <div class="col-md-7">
            <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
  
</div> 
 <!--  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Pay Now</b></div>
         <div class="col-md-7">
           <input type="checkbox" name="pay_now" id="pay_now" value="1" onclick="ShowHideDiv(this)" < ?php if($form_data['pay_now']==1){ echo "checked";} ?>> 
         </div>
       </div>
    </div>
  </div> --> <!-- row -->
  <input type="hidden" name="pay_now" id="pay_now" value="1">




  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b></b></div>
         <div class="col-md-7">
              <button class="btn-save" id="btnsubmit"><i class="fa fa-floppy-o"></i> Submit</button>
              <a href="<?php echo base_url('opd');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
         </div>
       </div>
    </div>
  </div> <!-- row -->

    
  
  
  </div> <!-- //====== col 4 -->
  
  
     <!-- //next 4th column -->
     <div class="col-xs-4 media_margin_left" id="payment_box" <?php /*if(empty($form_data['pay_now']) || $form_data['pay_now']==0) {  ?> style="display: none" <?php } */ ?>>
          <!-- <div class="row m-b-5">
               <?php //if(in_array('91',$users_data['permission']['section'])): ?> 
               <div class="col-md-12" >
                   <div class="row">
                         <div class="col-md-5"><b>Medicine Kit</b>
                         <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A Medicine kit is a collection of supplies and equipment that is used to give medical treatment it has a large number of medicine quantity.</span></a></sup>
                         </div>
                         <div class="col-md-7" id="packageid">
                              <select name="package_id" class="m_input_default"  id="package_id" onchange="return kit_charge(this.value);">
                                   <option value="">Select Medicine Kit</option>
                                   <?php
                                       /* if(!empty($package_list))
                                        {
                                             foreach($package_list as $package)
                                             {  
                                              if($package->total_kit_qty>0)
                                              {
                                             ?>   
                                                  <option value="<?php echo $package->id; ?>" <?php if(!empty($form_data['package_id']) && $form_data['package_id'] == $package->id){ echo 'selected="selected"'; } ?>><?php echo $package->title; ?></option>
                                             <?php
                                              }
                                             }
                                        }
                  */
                                   ?>
                              </select> 
                              <?php //if(!empty($form_error)){ echo form_error('package_id'); } ?>
                         </div>
                    </div>
               </div>
          </div> -->
          <?php //else: ?>
               <input type="hidden" class="m_input_default" name="package_id" id="package_id" value="" />
          <?php //endif; ?>

           <div class="row m-b-5">
               
               <div class="col-md-12" >
                   <div class="row">
                         <div class="col-md-5"><b>Next Appointment</b></div>
                         <div class="col-md-7">
                              <input type="text" name="next_app_date" class="datepicker m_input_default" value="<?php echo $form_data['next_app_date']; ?>" /> 
                              
                         </div>
                    </div>
               </div>
          </div>
          <div class="col-md-12">
               <div class="row m-b-5 opd_m_left">
                    <div class="col-md-5"><b>Mode of Payment</b></div>
                    <div class="col-md-7 opd_p_left">
                         <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
                    </div>
               </div>
          </div>
         
         <div id="updated_payment_detail">
                 <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {
                     $tot_values= explode('_',$field_names);

                    ?>

                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                       <div class="col-md-5"><b><?php echo $tot_values[1];?><span class="star">*</span></b></div> 
                      <div class="col-md-7"> 
                      <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                        <?php 
                      if(empty($tot_values[0]))
                      {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                      }
                      ?>
                      </div>
                      
                      </div>
                    </div>
                  </div>

                  
                   <?php } }?>
                     
                   </div>
                  
                <div id="payment_detail">
                    

                </div>

         

          
          <?php //if(in_array('91',$users_data['permission']['section'])): ?>
          <!--<div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Kit Amount</b></div>
                         <div class="col-md-7">
                              <input type="text"  name="kit_amount" id="kit_amount" class="price_float m_input_default" onblur="update_kit_amount(this.value);" value="<?php echo number_format($form_data['kit_amount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div>--> <!-- row -->
          <?php //else: ?>
               <input type="hidden" name="kit_amount" id="kit_amount" value="" />
  <?php //endif; ?>
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Consultant Charge</b></div>
                         <div class="col-md-7">
                              <input type="text"  name="consultants_charge" id="consultants_charge" class="price_float m_input_default" onchange="update_amount(this.value);" value="<?php echo number_format($form_data['consultants_charge'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Total Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly=""  name="total_amount" id="total_amount" class="price_float m_input_default" onchange="update_amount(this.value);" value="<?php echo number_format($form_data['total_amount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <?php 

$discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
if($discount_val_setting==1)
{
?>
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Discount (Rs.)</b></div>
                         <div class="col-md-7">
                              <input type="text" name="discount" onchange="check_paid_amount();discount_vals();" class="price_float m_input_default" id="discount" value="<?php echo number_format($form_data['discount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <?php } else{ 

?>
<input type="hidden" name="discount" class="price_float" id="discount" value="">

<?php 

  } ?>
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Net Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" readonly="" name="net_amount" class="price_float m_input_default" id="net_amount" value="<?php echo number_format($form_data['net_amount'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <div class="row m-b-5">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Paid Amount</b></div>
                         <div class="col-md-7">
                              <input type="text" name="paid_amount" class="price_float m_input_default" id="paid_amount" value="<?php if(!empty($form_data['paid_amount'])){echo number_format($form_data['paid_amount'],2,'.', '');}?>" onchange="check_paid_amount();">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
          <div class="row m-b-5" style="display: none;">
               <div class="col-md-12">
                    <div class="row">
                         <div class="col-md-5"><b>Balance</b></div>
                         <div class="col-md-7">
                              <input type="text" name="balance" id="balance" class="price_float" value="<?php echo number_format($form_data['balance'],2,'.', ''); ?>">
                         </div>
                    </div>
               </div>
          </div> <!-- row -->
<?php 

$enable_setting = get_setting_value('ENABLE_VITALS'); 
if($enable_setting==1)
{
?>
          <div class="row m-t-5">
               <div class="col-md-12">
                    <div class="grp_box">
                    <?php 
                    //print_r($vitals_list); exit;
                    if(!empty($vitals_list))
                    {
                      $i=0;
                      foreach ($vitals_list as $vitals) 
                      {
                        $vital_val = get_vitals_value($vitals->id,$form_data['data_id'],1);
                        ?>
                        <div class="grp">
                              <label><?php echo $vitals->vitals_name; ?> <br> 
                              <input   name="data[<?php echo $vitals->id; ?>][name]" value="<?php echo $vital_val; ?>"  type="text" class=" w-50px m_tiny">
                               <br> <?php echo $vitals->vitals_unit; ?> </label>
                         </div>
                        <?php

                        $i++;
                        if($i==6)
                        {
                           $i=0;
                           ?>
                           </div>
                           <div class="grp_box">
                           <?php 
                        }
                      }
                    }
                    ?>
                         <!-- <div class="grp">
                              <label>BP <br> <input type="text" name="patient_bp" value="<?php echo $form_data['patient_bp']; ?>" class="numeric_slash w-70px m_tiny"> <br> mm/Hg </label>
                         </div>
                         <div class="grp">
                              <label>Temp <br> <input type="text" name="patient_temp" value="<?php echo $form_data['patient_temp']; ?>" class="price_float input-tiny m_tiny"> <br> &deg;F</label>
                         </div>
                         <div class="grp">
                              <label>Weight <br> <input type="text" name="patient_weight" value="<?php echo $form_data['patient_weight']; ?>" class="price_float input-tiny m_tiny"> <br> kg</label>
                         </div>
                         <div class="grp">
                              <label>PR <br> <input type="text" name="patient_height"  value="<?php echo $form_data['patient_height']; ?>" class="price_float input-tiny m_tiny"> <br>cm </label>
                         </div>
                         <div class="grp">
                              <label>Spo2 <br> <input type="text" name="patient_sop" value="<?php echo $form_data['patient_sop']; ?>" class="price_float input-tiny m_tiny"> <br> %</label>
                         </div>
                         <div class="grp">
                              <label>RBS/FBS <br> <input type="text" name="patient_rbs" value="<?php echo $form_data['patient_rbs']; ?>" class="numeric_slash input-tiny m_tiny"> <br> mg/dl</label>
                         </div> -->
                    </div>
               </div>
          </div> <!-- MainRow -->

          <?php } /*else{  ?>  
          
          <input type="hidden" name="patient_bp" value="" class="">
          <input type="hidden" name="patient_temp" value="" class="">
          <input type="hidden" name="patient_weight" value="" class="">
          <input type="hidden" name="patient_height" value="" class="">
          <input type="hidden" name="patient_sop" value="" class="">
          <input type="hidden" name="patient_rbs" value="" class="">
          <?php }*/ ?>
     </div> <!-- main4 -->
</div> <!-- mainRow -->
<!-- =============================================== // new row  // ============================ -->



</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>

<script type="text/javascript">
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
function check_paid_amount()
{
    var consultants_charge = $('#consultants_charge').val();
    var kit_amount = $('#kit_amount').val();
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

function showAge(dob_birth) 
{
  
  var now = new Date(); //Todays Date   
  var birthday = dob_birth
  birthday=birthday.split("-");   

  var dobMonth= birthday[1]; 
  var dobDay= birthday[0];
  var dobYear= birthday[2];

  var nowDay= now.getDate();
  var nowMonth = now.getMonth() + 1;  //jan=0 so month+1
  var nowYear= now.getFullYear();

  var ageyear = nowYear- dobYear;
  var agemonth = nowMonth - dobMonth;
  var ageday = nowDay- dobDay;
  if (agemonth < 0) {
       ageyear--;
       agemonth = (12 + agemonth);
        }
  if (nowDay< dobDay) {
      agemonth--;
      ageday = 30 + ageday;
      }
  var val = ageyear + "-" + agemonth + "-" + ageday;
    $('#age_y').val(ageyear);
    $('#age_m').val(agemonth);
    $('#age_d').val(ageday);
}

$(document).ready(function(){
$('.datepicker_dob').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
  })

  $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 

  $('.datepicker3').datetimepicker({
      format: 'LT'
  });
   

});

function change_validity_date(vals)
{
    if(vals!='')
    {
      var doctor_id = vals;
    }
    else
    {
      var doctor_id = $('#attended_doctor').val();
    }
    
    var booking_date = $('#booking_date').val();
    var patient_id = $('#patient_id').val();
    $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>opd/get_validate_date/", 
        dataType: "json",
        data: 'doctor_id='+doctor_id+'&booking_date='+booking_date+'&patient_id='+patient_id,
        dataType: "json",
        success: function(result)
        {
            var date_new = result.date.split('-');
            var myDate = new Date(date_new[2],date_new[1]-1,date_new[0]);
             $('#validity_date').datepicker({ dateFormat: 'dd-mm-yy' });
            // $('#validity_date').val(result.date);
            $('#validity_date').datepicker("setDate",myDate);
               
        }
    });
}

$('.validity_date').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
            startDate : new Date(), 
            });

</script>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{ 
if((empty($specialization_list)) || (empty($attended_doctor_list)) || (empty($simulation_list)))
{
  
?>  

 
  $('#specilization_consultant_row_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 
}
}
?>

});
$("button[data-number=4]").click(function(){
    $('#specilization_consultant_row_count').modal('hide');
   /* $(this).hide();*/
});
</script>
<!--new css-->

<!--new css-->
<script type="text/javascript">
function father_husband_son()
{
   $("#relation_name").css("display","block");
}
/*  $(function() {
    //alert();
          
              var get_address  =  
             [
              <?php
              $address_list= get_patient_address();
              if(!empty($address_list))
              { 
                 foreach($address_list as $addres_li)
                  { 
                    echo '"'.$addres_li.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( ".address" ).autocomplete({
               source: get_address
            });
         });*/

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
$(document).ready(function()
{
       var simulation_id = $("#simulation_id :selected").val();
        find_gender(simulation_id);
 })
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

 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('opd/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
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
    

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }

function get_doctor_specilization(specilization_id,branch_id)
{   

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
   //ask mamta 
   /*if(specilization_id==640)
   {
      $('.datepicker_dob').val('');
      $('#pedic_spec').removeClass("hide");
      $('#pedic_spec').css("display","block");
     
   }
   else
   {
     
    $('#pedic_spec').addClass("hide");
    $('#pedic_spec').removeClass("show");
    $('#pedic_spec').css("display","none");
    $('.datepicker_dob').val('');
   }*/
}
/*function showAge(dob_birth) 
{
  
  var now = new Date(); //Todays Date   
  var birthday = dob_birth
  birthday=birthday.split("-");   

  var dobMonth= birthday[1]; 
  var dobDay= birthday[0];
  var dobYear= birthday[2];

  var nowDay= now.getDate();
  var nowMonth = now.getMonth() + 1;  //jan=0 so month+1
  var nowYear= now.getFullYear();

  var ageyear = nowYear- dobYear;
  var agemonth = nowMonth - dobMonth;
  var ageday = nowDay- dobDay;
  if (agemonth < 0) {
       ageyear--;
       agemonth = (12 + agemonth);
        }
  if (nowDay< dobDay) {
      agemonth--;
      ageday = 30 + ageday;
      }
  var val = ageyear + "-" + agemonth + "-" + ageday;
    $('#age_y').val(ageyear);
    $('#age_m').val(agemonth);
    $('#age_d').val(ageday);
}*/
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
   

  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }

  
 

  function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

  function final_toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
   function generate_token(vals)
   {
   // alert(vals);

          var doctor_id = vals;
          var branch_id = $('#branch_id').val();
          var booking_date = $('#booking_date').val();
          var specilization_id=$('#specilization_id').val();
<?php if(empty($form_data['data_id'])){ ?>
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/generate_token/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&booking_date='+booking_date+'&specilization_id'+specilization_id,
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
      var branch_id = $('#branch_id').val();
      var doctor_id = $('#attended_doctor').val();
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
  
  function discount_vals()
  {
     var timerA = setInterval(function(){  
          payment_calc();
          clearInterval(timerA); 
        }, 100);
  }

 

  function consultant_charge(vals)
  { 
          if(vals!='')
          {
            var doctor_id = vals;
          }
          else
          {
            var doctor_id =  $('#attended_doctor').val();  
          }
          
          var branch_id = $('#branch_id').val();
          var discount = $('#discount').val();
          var package_id = package_id; 
          var total_amount = $('#total_amount').val(); 
          var discount = '0.00';//$('#discount').val();
          var paid_amount = $('#paid_amount').val();
          var consultants_charge = $('#consultants_charge').val();
          var kit_amount = $('#kit_amount').val();
          var kit_amount = $('#kit_amount').val();
          var opd_type =  $('input[name=opd_type]:checked').val();
          //for panel
          var panel_type =  $('input[name=pannel_type]:checked').val();
          var ins_company_id = $('#ins_company_id :selected').val(); 
         
          
          get_available_days(doctor_id);
         

          if(doctor_id!='' && opd_type=='0')
          {
            
          $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                //$('#discount').val(result.discount); 
                $('#discount').val('0.00'); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
        }
        else if(doctor_id!='' && opd_type=='1')
        {
            
          $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_emergency_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
        }
        else
        {
                //$('#kit_amount').val(result.kit_amount);
               
               update_amount('0.00');
                
        }
        
  }

  function get_validity_date_in_between(vals)
  {
      if(vals!='')
      {
        var doctor_id = vals;
      }
      else
      {
        var doctor_id = $('#attended_doctor').val();
      }
      
      var booking_date = $('#booking_date').val();
      var patient_id = $('#patient_id').val();
      
     $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/get_validity_date_in_between/", 
              dataType: "json",
              data: 'doctor_id='+doctor_id+'&booking_date='+booking_date+'&patient_id='+patient_id,
                  success: function(result)
                  {
                    if(result==1)
                    {
                      $('#paid_amount').val('0.00');
                      $('#net_amount').val('0.00');
                      $('#discount').val('0.00');
                      $('#total_amount').val('0.00');
                      $('#consultants_charge').val('0.00');
                      
                      
                    }
                  }
              });
  }
  function get_available_days(doctor_id,date)
  {
      if(date!='')
      {
        var doctor_id = $('#attended_doctor').val();
      }
      if(doctor_id!='')
      {
        var booking_date = $('#booking_date').val();
      }
      
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>appointment/get_doctor_available_days/", 
            dataType: "json",
            data: 'doctor_id='+doctor_id+'&booking_date='+booking_date,
            success: function(result)
            {
               
               if(result==1)
               {
                    
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>general/get_doctor_available_time/",
                      data: 'doctor_id='+doctor_id+'&booking_date='+booking_date, 
                      success: function(result)
                      {
                        $('#bookingtime').val('');
                        $("#available_time").css("display", "block");
                        $("#doctor_avalaible").css("display", "none");
                        $("#booking_time").css("display","none");
                        $('#doctor_time').html(result); 
                        
                      } 
                    });
                   
                    
               }
               else if(result==0)
               {
                    $("#bookingtime").val('');
                    $("#booking_time").css("display","none");
                    $("#available_time").css("display", "none");
                    $("#available_slot").css("display", "none");
                    $("#doctor_avalaible").css("display", "block");
                    
                    


                    $('#doctor_not_avalaible').html('<p style="color:red;">Doctor not available.</p>'); 
                }
                else if(result==2)
                {
                  $("#doctor_avalaible").css("display", "none");
                  $("#available_time").css("display", "none");
                  $("#available_slot").css("display", "none");
                  $("#booking_time").css("display","block");
                  //available_time
                  
                } 

            }

          });
  }


  function select_a_slot(vals)
  {      
        var time_id = vals;
        var doctor_id = $('#attended_doctor').val();
        var booking_date = $('#booking_date').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>general/doctor_slot/", 
            data: 'doctor_id='+doctor_id+'&time_id='+time_id+'&booking_date='+booking_date,
            success: function(result)
            {
                
                $("#available_slot").css("display", "block");
                $('#doctor_slot').html(result); 
                //$('#doctor_slot').html(result); 
            }



          });
  }


  function update_doctor_charges(val) 
  {
    if(val=='1')
    {
        var doctor_id = $('#attended_doctor :selected').val();
        var branch_id = $('#branch_id').val();
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var consultants_charge = $('#consultants_charge').val();
        var kit_amount = $('#kit_amount').val();
        var opd_type =  $('input[name=opd_type]:checked').val();  
        //for panel
        var panel_type =  $('input[name=pannel_type]:checked').val();

        var ins_company_id = $('#ins_company_id :selected').val();
        $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_emergency_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
    } 
    else
    {
        var doctor_id = $('#attended_doctor :selected').val();
        var branch_id = $('#branch_id').val();
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var consultants_charge = $('#consultants_charge').val();
        var kit_amount = $('#kit_amount').val();
        var kit_amount = $('#kit_amount').val();
        var opd_type =  $('input[name=opd_type]:checked').val();  
        var panel_type =  $('input[name=pannel_type]:checked').val();
        var ins_company_id = $('#ins_company_id :selected').val();

      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
        //alert(doctor_id);
      //alert('inn');
    }
  }


  function update_doctor_panel_charges()
  {
   var panel_type =  $('input[name=pannel_type]:checked').val();
   //alert(panel_type);
   if(panel_type=='1')
    {
        
        var doctor_id = $('#attended_doctor :selected').val();
        var branch_id = $('#branch_id').val();
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var consultants_charge = $('#consultants_charge').val();
        var kit_amount = $('#kit_amount').val();
        
        // for normal and emergency charges
        var opd_type =  $('input[name=opd_type]:checked').val();
        var panel_type =  $('input[name=pannel_type]:checked').val(); 

        // for panel charges 
        var ins_company_id = $('#ins_company_id :selected').val(); 
        
        $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_panel_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type+'&panel_type='+panel_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
    } 
    else
    {
        var doctor_id = $('#attended_doctor :selected').val();
        var branch_id = $('#branch_id').val();
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var consultants_charge = $('#consultants_charge').val();
        var kit_amount = $('#kit_amount').val();
        var kit_amount = $('#kit_amount').val();
        var opd_type =  $('input[name=opd_type]:checked').val();  
        var panel_type =  $('input[name=pannel_type]:checked').val();
        var ins_company_id = $('#ins_company_id :selected').val();
      $.ajax({
              type: "POST",
              url: "<?php echo base_url(); ?>opd/doctor_rate/", 
              dataType: "json",
              data: 'branch_id='+branch_id+'&doctor_id='+doctor_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&kit_amount='+kit_amount+'&ins_company_id='+ins_company_id+'&opd_type='+opd_type+'&panel_type='+panel_type,
              success: function(result)
              {
                $('#kit_amount').val(result.kit_amount);
                $('#consultants_charge').val(result.consultants_charge);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount); 
                $('#discount').val(result.discount); 
                $('#paid_amount').val(result.net_amount);
                $('#balance').val(result.balance);  
                 
              }
          });
        //alert(doctor_id);
      //alert('inn');
    }
  }


  function kit_charge(vals)
  {
       var package_id = vals;
       
        var discount = $('#discount').val();
        var package_id = package_id; 
        var total_amount = $('#total_amount').val(); 
        var discount = $('#discount').val();
        var paid_amount = $('#paid_amount').val();
        var consultants_charge = $('#consultants_charge').val();
        var kit_amount = $('#kit_amount').val();
      if(package_id!='')
      {
      $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>opd/package_rate/", 
          dataType: "json",
          data: 'package_id='+package_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&consultants_charge='+consultants_charge,
          success: function(result)
          {
             
             $('#kit_amount').val(result.kit_amount);
             $('#consultants_charge').val(result.consultants_charge);
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
                //$('#kit_amount').val(result.kit_amount);
               
               update_kit_amount('0.00');
                
        }

      
  }
   


 
 function update_kit_amount(val)
 {
     if(val!='')
     {
        var kit_amount = val;
        var consultants_charge = $('#consultants_charge').val();
        var discount = $('#discount').val();
        
         $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/update_amount/", 
            dataType: "json",
            data: 'kit_amount='+kit_amount+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance,
            success: function(result)
            {
               
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
        $("#paid_amount").val('0.00');
        $("#total_amount").val('0.00');
     }
 }
 function update_amount(val)
 {
     if(val!='')
     {
        
        var kit_amount = $('#kit_amount').val();
        var consultants_charge = val;
        var total_amount = consultants_charge+kit_amount;
        var discount = $('#discount').val();
        var balance = total_amount-discount;
       

        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/update_amount/", 
            dataType: "json",
            data: 'kit_amount='+kit_amount+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance,
            success: function(result)
            {
                $("#consultants_charge").val(result.consultants_charge);
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
        $("#paid_amount").val('0.00');
        $("#total_amount").val('0.00');
     }
 }
  function payment_calc()
  {
    var consultants_charge = $('#consultants_charge').val();
    var kit_amount = $('#kit_amount').val();
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>opd/calculate_payment/", 
            dataType: "json",
            data: 'kit_amount='+kit_amount+'&consultants_charge='+consultants_charge+'&total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance,
            success: function(result)
            {
               $('#kit_amount').val(result.kit_amount); 
               $('#consultants_charge').val(result.consultants_charge); 
               $('#total_amount').val(result.total_amount); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.paid_amount); 
               $('#balance').val(result.balance); 
               
            } 
          });
  }

   function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }


$('#branch_id').change(function(){  
      var branch_id = $(this).val();

      $.ajax({url: "<?php echo base_url(); ?>opd/get_branch_data/"+branch_id, 
        success: function(result)
        {
           load_values(result);
           load_simulation_values(branch_id);
           load_referral_doctor_values(branch_id);
           load_specialization_values(branch_id);
           load_attended_doctor_values(branch_id);
           load_source_from_values(branch_id);
           load_diseases_values(branch_id);
           load_package_values(branch_id);
        } 
      }); 
  });
function more_patient_info()
 {
   $("#patient_info").slideToggle();
 }
  function load_values(jdata)
  {
     var obj = JSON.parse(jdata);
     $('#patient_code').val(obj.patient_code);
     $('#booking_code').val(obj.booking_code);
  };

  function load_simulation_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_simulation_data/"+branch_id, 
    success: function(result)
    {
      $('#simulation_id').html(result); 
    } 
  });
  }

  function load_referral_doctor_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_referral_doctor_data/"+branch_id, 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
  });
  }

  function load_specialization_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_specialization_data/"+branch_id, 
    success: function(result)
    {
      $('#specilizationid').html(result); 
    } 
  });
  }

  function load_attended_doctor_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_attended_doctor_data/"+branch_id, 
    success: function(result)
    {
      $('#attended_doctor').html(result); 
    } 
  });
  }

  function load_source_from_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_source_from_data/"+branch_id, 
    success: function(result)
    {
      $('#patient_sources_id').html(result); 
    } 
  });
  }

  function load_diseases_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>opd/get_diseases_data/"+branch_id, 
    success: function(result)
    {
      $('#diseaseid').html(result); 
    } 
  });
  }

  function insurance_type_modal()
  {
      var $modal = $('#load_add_insurance_type_modal_popup');
      $modal.load('<?php echo base_url().'insurance_type/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function insurance_company_modal()
  {
      var $modal = $('#load_add_insurance_company_modal_popup');
      $modal.load('<?php echo base_url().'insurance_company/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }


  function load_package_values(branch_id)
  {
      $.ajax({url: "<?php echo base_url(); ?>opd/get_package_data/"+branch_id, 
      success: function(result)
      {
        $('#packageid').html(result); 
      } 
    });
  }

function set_tpa(val)
 { 
    var editval='';
    editval='<?php echo $form_data['insurance_type'];?>';
    if(val==0 && editval !=1)
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

$(document).ready(function() {
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_specialization_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

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

<!-- Confirmation Box end -->

<div id="load_add_specialization_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="specilization_consultant_row_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          <?php if(empty($specialization_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Specialization is required.</span></p><?php } ?>
          <?php if(empty($attended_doctor_list)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Consultant is required.</span></p>
          <?php } ?>
          </div>
        </div>
      </div>  
    </div> 


<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_emp_type_modal_popup" class="modal fade top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_insurance_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
</body>
</html>
<script type="text/javascript">
  $(document).ready(function(){

  })
</script>
<div id="confirm_billing_print" class="modal fade dlt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
      <!-- <div class="modal-body"></div> -->
      <div class="modal-footer">
         <!--<a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>-->
         <?php 
              if($users_data['parent_id']=='60')
                {
                    $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>
                    <?php 
                }
              
              ?>
       <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
      </div>
    </div>
  </div>  
</div>
<script>

  $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#opd_form').submit();
  })
  

/// 30/07/2019 mlc no
function check_status(check_status)
{
  if(check_status==1)
  {
    <?php $arr=check_hospital_mlc_no(); $mlc_no=$arr['prefix'].$arr['suffix'];?>
    $('#mlc_status').show();
    if($('#mlc_status').val()=='')
    $('#mlc_status').val('<?php echo $mlc_no;?>').attr('readonly', true);
  }
  else
  {
    $('#mlc_status').val('');
    $('#mlc_status').hide();
  }
  
}

function set_default_doct(sp_id, doc_id)
{
  consultant_charge(doc_id);
  generate_token(doc_id);  
  get_validity_date_in_between(doc_id);
  change_validity_date(doc_id);
}

</script>