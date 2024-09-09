<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(2);
//echo "<pre>";print_r($users_data);die;
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>pwdwidget.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>   
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>pwdwidget.js"></script>  
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>webcam.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>webcam.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<body onLoad="set_tpa(<?php echo $form_data['insurance_type']; ?>); set_married(<?php echo $form_data['marital_status']; ?>);"> 
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
                              <!-- Patient Details page -->
<section class="content">
<form id="patient_form" name="ptaient_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="data_id" id="patient_id" value="<?php echo $form_data['data_id']; ?>">
<div class="content-inner">
  
    <div class="pat-col">

       <?php $data= get_setting_value('PATIENT_REG_NO'); 
       if(!empty($data) && isset($data)) 
      {
      ?>
      <div class="grp">
        <label><?php echo $data; ?></label>
        <div class="box-right"><?php echo $form_data['patient_code']; ?></div>
        <input type="hidden" name="patient_code" id="patient_code" value="<?php echo $form_data['patient_code']; ?>" />
      </div>
      <?php } ?>

      <div class="grp-full">
        <div class="grp">
              <label>Patient Name <span class="star">*</span> </label>
            <div class="box-right">
                <select name="simulation_id" id="simulation_id" class="pat-select1" onChange="find_gender(this.value)">
                  <!-- <option value="">Select</option> -->
                  <?php
                  if(!empty($simulation_list))
                  {
                    $s = 1;
                    $sim_id = '';
                    foreach($simulation_list as $simulation)
                    {
                      $selected_simulation = '';
                      
                      if($simulation->id==$form_data['simulation_id'])
                      {
                         $selected_simulation = 'selected="selected"';
                      }
                      if($s==1)
                      {
                        $sim_id = $simulation->id;
                      }
                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                    $s++;
                    }

                  }
                  ?> 
                </select> 
                <input type="text" name="patient_name"  class="alpha_space_name txt_firstCap" id="patient_name" value="<?php echo $form_data['patient_name']; ?>"  autofocus/>
               
                <?php 
                             if(!empty($form_error)){ echo form_error('patient_name'); }
                             if(!empty($form_error)){ echo form_error('simulation_id'); } 

                        
                ?>
            </div>
          </div>
          <?php if(in_array('65',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a title="Add Simulation" href="javascript:void(0)" onClick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>

      </div>

    <div class="grp-full">
        <div class="grp">
              <label> 
              <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
             <?php foreach($gardian_relation_list as $gardian_list) 
             {?>
              <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
             <?php }?>
             </select></label>
            <div class="box-right">
                <select name="relation_simulation_id" id="relation_simulation_id" class="pat-select1">
                  <!-- <option value="">Select</option> -->
                  <?php
                  if(!empty($simulation_list))
                  {
                    $s = 1;
                    $sim_id = '';
                    foreach($simulation_list as $simulation)
                    {
                      $selected_simulation = '';
                      
                      if($simulation->id==$form_data['relation_simulation_id'])
                      {
                         $selected_simulation = 'selected="selected"';
                      }
                      if($s==1)
                      {
                        $sim_id = $simulation->id;
                      }
                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                    $s++;
                    }

                  }
                  ?> 
                </select> 
              <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" class="alpha_space_name" name="relation_name" id="relation_name"/>
            </div>
          </div>
       </div>

      <div class="grp">
        <label>Mobile No. 
        <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==5 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
        </label>
        <div class="box-right">
           <!-- <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>"    data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric">  -->
       <input type="text" name="country_code" value="+91" readonly="" class="country_code" placeholder="+91"> 
       <input type="text" name="mobile_no" id="mobile_no" maxlength="10" class="number numeric" placeholder="eg.9897221234" value="<?php echo $form_data['mobile_no']; ?>"  onkeyup="get_patient_detail_by_mobile();"/> 
           <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='5' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile_no'); 
                              } 
                         }
                    }
          ?>

        </div>
      </div>


      <div class="grp">
        <label>Gender 
         
                         <span class="star">*</span>
                
        
        </label>
        <div class="box-right" id="gender">
            <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php 
                              if(!empty($form_error)){ echo form_error('gender'); } 
                    
             ?>
        </div>
      </div>


      <div class="grp">
        <label>Age 
         <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==7 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
        </label>
        <div class="box-right">
            <input type="text" name="age_y" id="age_y" class="numeric input-tiny2 media_input_tiny" maxlength="3" value="<?php echo $form_data['age_y']; ?>" onchange="getAsDate();"> Y &nbsp;
            <input type="text" name="age_m" id="age_m" class="numeric input-tiny2 media_input_tiny" maxlength="2" value="<?php echo $form_data['age_m']; ?>" onchange="getAsDate();"> M &nbsp;
            <input type="text" name="age_d" id="age_d" class="input-tiny2 media_input_tiny numeric" maxlength="2" value="<?php echo $form_data['age_d']; ?>" onchange="getAsDate();"> D
            <input type="text" name="age_h" class="input-tiny2 media_input_tiny numeric" maxlength="2" value="<?php echo $form_data['age_h']; ?>"> H
            <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='7' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); 
                              } 
                         }
                    }
               ?>
        </div>
      </div>

      

      <div class="grp">
        <label>Address 1</label>
        <div class="box-right">
            <input type="text" name="address" id="address" class="address" maxlength="255" value="<?php echo $form_data['address']; ?>"/>
        </div>
      </div>
      <div class="grp">
        <label>Address 2</label>
        <div class="box-right">
            <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php echo $form_data['address_second']; ?>"/>
        </div>
      </div>
       <div class="grp">
        <label>Address 3</label>
        <div class="box-right">
            <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php echo $form_data['address_third']; ?>"/>
        </div>
      </div>

      <div class="grp">
        <label>Aadhaar No.</label>
        <div class="box-right">
            <input type="text" name="adhar_no" id="adhar_no" class="adhar_no numeric" value="<?php echo $form_data['adhar_no']; ?>" maxlength="16" />
            <?php 
              if(!empty($form_error)){ echo form_error('adhar_no'); } 
                    
             ?>
        </div>
      </div>
 
      
      <div class="grp">
        <label>Country</label>
        <div class="box-right">
            <select name="country_id" id="country_id" class="pat-select1" onChange="return get_state(this.value);">
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

      <div class="grp">
        <label>State</label>
        <div class="box-right">
            <select name="state_id" id="state_id" onChange="return get_city(this.value)">
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

      <div class="grp">
        <label>City</label>
        <div class="box-right">
            <select name="city_id" id="city_id">
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


      <div class="grp">
        <label>PIN Code</label>
        <div class="box-right">
            <input type="text" name="pincode" id="pincode" maxlength="6" data-toggle="tooltip"  title="Pincode should be six numeric digit." class="numeric tooltip-text tool_tip" value="<?php echo $form_data['pincode']; ?>">
            <?php if(!empty($form_error)){ echo form_error('pincode'); } ?>
        </div>
      </div>


      <div class="grp">
        <label>Marital Status</label>
        <div class="box-right">
            <input type="radio" name="marital_status" value="1" <?php if($form_data['marital_status']==1){ echo 'checked="checked"'; } ?> onclick="set_married(1);" > Married
            <input type="radio" name="marital_status" value="0" <?php if($form_data['marital_status']==0){ echo 'checked="checked"'; } ?> onclick="set_married(0);"> Unmarried
        </div>
      </div>

      <div class="grp">
        <label>Marriage Anniversary</label>
        <div class="box-right">
              <input type="text" class="datepicker" readonly="" name="anniversary" id="anniversary" value="<?php echo $form_data['anniversary']; ?>" /> 
            </div>
      </div>

      <div class="grp-full">
          <div class="grp">
            <label>Religion</label>
            <div class="box-right">
                <select name="religion_id" id="religion_id">
                  <option value="">Select Religion</option>
                  <?php
                  if(!empty($religion_list))
                  {
                    foreach($religion_list as $religion)
                    {
                      $selected_religion = "";
                      if($religion->id==$form_data['religion_id'])
                      {
                        $selected_religion = 'selected="selected"';
                      }
                      echo '<option value="'.$religion->id.'" '.$selected_religion.'>'.$religion->religion.'</option>';
                    }
                  }
                  ?> 
                </select>
            </div>
          </div>
          <?php if(in_array('51',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a title="Add Religion" class="btn-new" href="javascript:void(0)" onClick="religion_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>


      <div class="grp">
        <label>DOB</label>
        <div class="box-right">
              <input type="text" class="datepicker" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>"  onchange="showAge(this.value);"/> 
        </div>
      </div>

 
      
      <div class="grp">
        <label>Mother Name</label>
        <div class="box-right">
            <input type="text" name="mother" class="alpha_space_name" id="mother" value="<?php echo $form_data['mother']; ?>" />
        </div>
      </div>

    </div> <!-- // -->

    <div class="pat-col">
      
      <div class="grp">
        <label>Guardian Name</label>
        <div class="box-right">
            <input type="text" name="guardian_name" class="alpha_space_name input_focus" id="guardian_name" value="<?php echo $form_data['guardian_name']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Guardian Email</label>
        <div class="box-right">
            <input type="text" name="guardian_email" data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text tool_tip email_address" id="guardian_email" value="<?php echo $form_data['guardian_email']; ?>" />
          
            <?php if(!empty($form_error)){ echo form_error('guardian_email'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Guardian Mobile</label>
        <div class="box-right">
            <input type="text" name="country_code" value="+91" readonly="" class="country_code" placeholder="+91">
            <input type="text" name="guardian_phone" class="number numeric" id="guardian_phone" value="<?php echo $form_data['guardian_phone']; ?>" maxlength="10">
            <?php if(!empty($form_error)){ echo form_error('guardian_phone'); } ?>
        </div>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Relation</label>
            <div class="box-right">
                <select name="relation_id" id="relation_id">
                  <option value="">Select Relation</option>
                  <?php
                  if(!empty($relation_list))
                  {
                    foreach($relation_list as $relation)
                    {
                      $selected_relation = "";
                      if($relation->id==$form_data['relation_id'])
                      {
                        $selected_relation = "selected='selected'";
                      }
                      echo '<option value="'.$relation->id.'" '.$selected_relation.'>'.$relation->relation.'</option>';
                    }
                  }
                  ?> 
                </select>
            </div>
          </div>
          <?php if(in_array('58',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a title="Add Relation" class="btn-new" href="javascript:void(0)" onClick="relation_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>
      
      <div class="grp">
        <label>Patient Email</label>
        <div class="box-right">
            <input type="text" name="patient_email" data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text tool_tip email_address" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
          
            <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Monthly Income</label>
        <div class="box-right">
            <input type="text" name="monthly_income" class="price_float"  maxlength="10" id="monthly_income" value="<?php echo $form_data['monthly_income']; ?>" placeholder="eg.10000">
            <?php if(!empty($form_error)){ echo form_error('monthly_income'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Occupation</label>
        <div class="box-right">
            <input type="text" name="occupation" class="alpha_space" id="occupation" value="<?php echo $form_data['occupation']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Insurance Type</label>
        <div class="box-right">
            <input type="radio" name="insurance_type" value="0" <?php if($form_data['insurance_type']==0){ echo 'checked="checked"'; } ?> onClick="return set_tpa(0)"> Normal &nbsp;
            <input type="radio" name="insurance_type" value="1" <?php if($form_data['insurance_type']==1){ echo 'checked="checked"'; } ?> onClick="return set_tpa(1)"> Panel
        </div>
      <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a policy which have two type <br> Normal:It is simple type policy <br> Panel:Third Party Adminstrater (TPA) is an organization that processes insurance claims or certain aspects of employee benefit plans for a separate entity.</span></a></sup>
      </div>



      <div class="grp-full">
          <div class="grp">
            <label>Type</label>
            <div class="box-right">
                <select name="insurance_type_id" id="insurance_type_id">
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
            </div>
          </div>
          <?php if(in_array('72',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a title="Add Insurance Type" class="btn-new" href="javascript:void(0)" onClick="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Name</label>
            <div class="box-right">
                <select name="ins_company_id" id="ins_company_id">
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
            </div>
          </div>
          <?php if(in_array('79',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a title="Add Insurance Company" class="btn-new" href="javascript:void(0)" onClick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>
      
      <div class="grp">
        <label>Policy No.</label>
        <div class="box-right">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
        </div>
      </div>
      
      <div class="grp">
        <label>TPA ID</label>
        <div class="box-right">
            <input type="text" name="tpa_id" class="alpha_numeric"  id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>"  data-toggle="tooltip" title="Third Party Adminstrator!"/>
        </div>
      </div>
      
      <div class="grp">
        <label>Insurance Amount</label>
        <div class="box-right">
            <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onKeyPress="return isNumberKey(event);" />
        </div>
      </div>
      
      <div class="grp">
        <label>Authorization No.</label>
        <div class="box-right">
            <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
        </div>
      </div>

      
      <div class="grp">
        <label>Created Date</label>
        <div class="box-right" style="position: relative">
            <input type="text" name="created_date" class="datepicker3 m_input_default" id="created_date" value="<?php echo $form_data['created_date']; ?>" />
        </div>
      </div>       
      
      <div class="grp">
        <label>Status</label>
        <div class="box-right">
            <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>> Active &nbsp;
            <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
        </div>
      </div>

      <div class="grp">
        <label></label>
        <div class="box-right">
            <button class="btn-update" id="form_submit">
            <i class="fa fa-save"></i> Save</button>
            <a href="<?php echo base_url('patient'); ?>" class="btn-update" style="text-decoration:none!important;color:#FFF;padding:8px 2em;"><i class="fa fa-sign-out"></i> Exit</a>
        </div>
      </div>

    </div> <!-- // --> 

    <div class="pat-col">
        <div class="pat-col-right-box">
            <!--Editing by Nitin Sharma 04/02/2024-->
               <strong><center>Patient Photo/Finger Print</center></strong> 
               <!--Editing by Nitin Sharma 04/02/2024-->
               <div class="photo">
                    <?php
                         $img_path = base_url('assets/images/photo.png');
                         if(!empty($form_data['data_id']) && !empty($form_data['old_img'])){
                              $img_path = ROOT_UPLOADS_PATH.'patients/'.$form_data['old_img'];
                         }  
                    ?>
                    <img id="pimg" src="<?php echo $img_path; ?>" class="img-responsive">
               </div>
               <!--Added By Nitin Sharma 04/02/2024-->
               <div class="photo fingerprint"  onclick="captureFP()">
                  <?php
                  $img_path = base_url('assets/images/finger_sacan.png');
                  if(!empty($form_data['data_id']) && !empty($form_data['fingerprint_photo'])){
                    $img_path = $form_data['fingerprint_photo'];
                  } 
                  ?>
                  <img id="FPImage1" src="<?php echo $img_path; ?>" class="img-responsive" >
                  <input type="hidden" id="capture_finger" name="capture_finger" value="" />
                  <input type="hidden" id="fingerprint_photo" name="fingerprint_photo" value="" />
                </div>
                <!--Added By Nitin Sharma 04/02/2024-->
        </div>
        <div class="pat-col-right-box2">
               <strong>Select Image</strong>
                    <input type="hidden" name="old_img"  value="<?php echo $form_data['old_img']; ?>" />
                    <input type="hidden" id="capture_img" name="capture_img" value="" />
                    <div style="float: left;width: 100%; font-weight: bold;text-align: center;">
                    <a href="javascript:void(0);" onclick="return start_cam()"><img src="<?php echo ROOT_IMAGES_PATH.'camera.png'; ?>" ></a>
                    </div>
                    <div style="float: left;width: 100%; font-weight: bold;text-align: center;">
                    OR
                    </div>
                    <input type="file" id="img-input" accept="image/*" name="photo">
                    <?php
                         if(isset($photo_error) && !empty($photo_error)){
                              echo '<div class="text-danger">'.$photo_error.'</div>';
                         }
                    ?>
          </div>
        <?php 
        if(!empty($form_data['data_id']) && $form_data['data_id']>0)
        {
        ?>
          <div class="pat-col-right-box2">
            <strong>Username</strong>
            <input type="text" id="username" class="alpha_numeric_space" readonly="" name="username" value="<?php echo $form_data['username']; ?>" />
          </div>
          <div class="pat-col-right-box2">
            <strong>Password</strong>
            <div class='pwdwidgetdiv' id='thepwddiv' style="float:left;"></div>
            <script  type="text/javascript" >
            $(document).ready(function(){
                 var pwdwidget = new PasswordWidget('thepwddiv','password');
                 pwdwidget.MakePWDWidget();
            });
            </script>
             <div class="brn_cover"> 
                   <div class="brn_1" id="brn_1">
                        <div class="brn_arrow"></div>
                        <div class="brn_txt">Password strength:</div>
                        <div id="mark_bar" class="brn_mark"></div>
                        <div class="brn_validation">
                             Password length should be 6-20 character only.
                        </div>
                   </div>
              </div>  
            <?php if(!empty($form_error)){ echo form_error('password'); } ?>
          </div> 
        <?php
        }
        ?>  
        <div class="pat-col-right-box2">
          <strong>Remarks</strong>
          <textarea id="remark" class="alpha_numeric_space" name="remark" maxlength="255"><?php echo $form_data['remark']; ?></textarea>
        </div>
    </div> <!-- // -->

</div> <!-- content-inner -->
</form>


</section> <!-- content -->





<!-- =================== footer ============================== -->
<?php
$this->load->view('include/footer');
?>
<script>
 function get_patient_detail_by_mobile() {
  var val = $('#mobile_no').val();
   if(val.length==10)
   {
    
    $.ajax({
      url: "<?php echo site_url('opd/get_patient_detail_no_mobile'); ?>/"+val,
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

 $(document).ready(function(){
       
        $("#proceed").click(function(){

            if($('input[name="patient_id"]:checked').length == 0){

                 alert("Please select a patient");
                  return false;

            }
            else
            {
              var action_url = '<?php echo site_url('patient/add/'); ?>'
              var radioValue = $("input[name='patient_id']:checked").val();
               $.ajax({
                    url: "<?php echo site_url('opd/get_patient_detail_byid'); ?>/"+radioValue,
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
                    $('#patient_name').val(data.patient_detail.patient_name);
                    $('#relation_name').val(data.patient_detail.relation_name);
                    $('#mobile_no').val(data.patient_detail.mobile_no);
                    $('#gender_'+data.patient_detail.gender).attr('checked', 'checked');
                    $('#age_y').val(data.patient_detail.age_y);
                    $('#age_m').val(data.patient_detail.age_m);
                    $('#age_d').val(data.patient_detail.age_d);
                    $('#age_h').val(data.patient_detail.age_h);
                    $('#opd_form').attr('action', action_url+data.patient_detail.id); 
                    
                    $("#simulation_id option[value="+data.patient_detail.simulation_id+"]").attr('selected', 'selected');
                    $("#relation_simulation_id option[value="+data.patient_detail.relation_simulation_id+"]").attr('selected', 'selected');
                    $("#relation_type_id option[value="+data.patient_detail.relation_type+"]").attr('selected', 'selected');
                    find_gender(data.patient_detail.simulation_id);
                  }
                }

             });

          }

        });

    });

function getAsDate()
{
    var day = parseInt($("#age_d").val());
    if(isNaN(day)) {
        var day = 0;
    }
    var month = parseInt($("#age_m").val() - 1);
    if(isNaN(month)) {
        var month = 0;
    }
    var year = parseInt($("#age_y").val());
    if(isNaN(year)) {
        var year = 0;
    }
    $.ajax({url: "<?php echo base_url(); ?>patient/getAge/", 
    
    type: 'POST',
    data: { day: day, month : month,year:year} ,
      success: function(result)
      { 
        $('#dob').val(result); 
      } 
    }); 
  }
  
  
function set_married(val)
 { 
    if(val==0)
    {
      $('#anniversary').attr("disabled", true);
      $('#anniversary').val('');
    }
    else
    {
      $('#anniversary').attr("disabled", false);
      
    }
 }
  




$(document).on("click", function(e){
    if( !$(".password_id").is(e.target) ){ 
    //if your box isn't the target of click, hide it
        $(".brn_1").hide();
    }
});

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger: 'focus'
    
    });   
}); 
</script>
<script type="text/javascript">
  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });

 //function to find gender according to selected simulation
 function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }
 //ends
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

  function start_cam()
  {
      var $modal = $('#load_start_cam_modal_popup');
      $modal.load('<?php echo base_url().'patient/start_cam/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }

  function relation_modal()
  {
      var $modal = $('#load_add_relation_modal_popup');
      $modal.load('<?php echo base_url().'relation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function religion_modal()
  {
      var $modal = $('#load_add_religion_modal_popup');
      $modal.load('<?php echo base_url().'religion/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
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

  function get_state(country_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#state_id').html(result); 
      } 
    });
    get_city(); 
  }

  function get_city(state_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#city_id').html(result); 
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
  
$('#form_submit').on("click",function(){
    $(':input[id=form_submit]').prop('disabled', true);
       $('#patient_form').submit();

  })
 
 function set_tpa(val)
 {
    if(val==0)
    {
      $('#insurance_type_id').attr("disabled", true);
      $('#insurance_type_id').val('');
      $('#ins_company_id').attr("disabled", true);
      $('#ins_company_id').val('');
      $('#polocy_no').attr("readonly", "readonly");
      $('#polocy_no').val('');
      
      $('#ins_authorization_no').attr("readonly", "readonly");
      $('#ins_authorization_no').val('');
      
      
      
      $('#tpa_id').attr("readonly", "readonly");
      $('#tpa_id').val('');
      $('#ins_amount').attr("readonly", "readonly");
      $('#ins_amount').val('');
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#ins_authorization_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
    }
 }

$(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(document).ready(function(){
  $('#load_add_religion_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(document).ready(function(){
  $('#load_add_relation_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
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
  
    $(document).ready(function(){
          $('.datepicker').datepicker({
            //format: 'dd-mm-yyyy',
            dateFormat: 'dd-mm-yy',
            endDate : new Date(),
            autoclose: true,
            startView: 2
          })
   

});
</script> 
<?php
if(!empty($sim_id) && $form_data['data_id']=='')
{
  echo '<script>find_gender('.$sim_id.');</script>';
}
?>
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_religion_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_relation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_start_cam_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
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
</body>
</html>
<script type="text/javascript"> 
function father_husband_son()
{
   $("#relation_name").css("display","block");
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

$('.datepicker3').datetimepicker();
</script>
<!--Added By Nitin Sharma 04/02/2024-->
<script>
  function captureFP() {
    CallSGIFPGetData(SuccessFunc, ErrorFunc);
  }
  function SuccessFunc(result) {
    if (result.ErrorCode == 0) {
      if (result != null && result.BMPBase64.length > 0) {
        document.getElementById("FPImage1").src = "data:image/bmp;base64," + result.BMPBase64;
      }
      document.getElementById("capture_finger").value = result.TemplateBase64;
      document.getElementById("fingerprint_photo").value = "data:image/bmp;base64," + result.BMPBase64;
    }else {
      alert("Fingerprint Not Captured Properly. Error Code:  " + result.ErrorCode);
    }
  }
  function ErrorFunc(status) {
    alert("Check If Fingerprint Device Is Connected?");
  }
  function CallSGIFPGetData(successCall, failCall) {
    var uri = "https://localhost:8443/SGIFPCapture";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
      if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        fpobject = JSON.parse(xmlhttp.responseText);
        successCall(fpobject);
      } else if (xmlhttp.status == 404) {
        failCall(xmlhttp.status);
      }
    };
    var params = "Timeout=" + "10000";
    params += "&Quality=" + "50";
    params += "&licstr=" + "";
    params += "&templateFormat=" + "ISO";
    params += "&imageWSQRate=" + "0.75";
    console.log;
    xmlhttp.open("POST", uri, true);
    xmlhttp.send(params);

    xmlhttp.onerror = function () {
      failCall(xmlhttp.statusText);
    };
  }

</script>
<!--Added By Nitin Sharma 04/02/2024-->
<!--new css-->
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

     <!--new css-->