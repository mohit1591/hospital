<!DOCTYPE html>
<html>
<head>
 <title><?php echo $page_title.PAGE_TITLE; ?></title>
 <?php  $users_data = $this->session->userdata('auth_users'); ?>
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

 <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
 <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
 <!-- datatable js --> 
 <script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
 <script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

 <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
 <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">

$('document').ready(function(){
 <?php
 $ambulance_booking_id = $this->session->userdata('ambulance_booking_id');
 if(isset($_GET['status']) && isset($ambulance_booking_id) && $_GET['status']=='print'){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('opd/booking');?>'; 
    }); 
       
  <?php } ?>
   // $('#vendor_commission').show();
 });

</script>
</head>

<body style="padding-bottom: 70px;">
 <div class="container-fluid">
  <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  ?>



       <!-- ============================= Main content start here ===================================== -->
       <section class="top_article">
        <form action="<?php echo current_url(); ?>" method="post" id="ambulance_form">
          <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
          
          <input type="hidden" name="refund_amount_data" value="<?php echo $form_data['refund_amount_data']; ?>" />
          
          
<input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>" id="patient_id"/>
<input type="hidden" name="type" value="2" />
          <div class="container-fluid">
           <div class="row">
            <div class="col-md-12">

             <div class="row mb-2">
               
                   <div class="grp">
                    <span class="btn btn-sm"><input type="radio" name="new_patient" onClick="window.location='<?php echo base_url('ambulance/booking/add');?>';" <?php if(empty($form_data['patient_id'])) { ?> checked <?php } ?> > <label>New Patient</label></span>
                    <span class="btn btn-sm"><input type="radio" name="new_patient" onClick="window.location='<?php echo base_url('patient');?>';" <?php if(!empty($form_data['patient_id'])) { ?> checked <?php } ?>> <label>Registered Patient</label></span>
                  </div>
                </div>

                <article class="">
                  <div class="row m-t-5">
                   <div class="col-md-4">
                    <div class="row mb-2">
                     <label class="col-md-4">UHID No. <span class="text-danger">*</span></label>
                     <div class="col-md-8">
                       <input type="text" class="m_input_default" readonly="" id="patient_code" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
                     </div>
                   </div>
                   <div class="row mb-2">
                     <label class="col-md-4">Hospital ID <span class="text-danger">*</span></label>
                     <div class="col-md-8">
                       <input type="text" class="m_input_default"  id="hospital_id" name="hospital_id" value="<?php echo $form_data['hospital_id']; ?>" /> 
                       <?php if(!empty($form_error)){ echo form_error('hospital_id'); } ?>
                     </div>
                      
                   </div>
                   <div class="row mb-2">
                     <label class="col-md-4">Booking No. <span class="text-danger">*</span></label>
                     <div class="col-md-8">
                      <input type="text" readonly="" id="booking_code" class="m_input_default" name="booking_code" value="<?php echo $form_data['booking_code']; ?>"/> 
                    </div>
                  </div>
                  <div class="row mb-2">
                   <label class="col-md-4">Patient Name  <span class="text-danger">*</span></label>
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
                <input type="text" name="patient_name" id="patient_name" style="width:128px!important;" value="<?php echo $form_data['patient_name']; ?>" class="mr-name m_name txt_firstCap"  onkeydown="return alphaOnly(event);" autofocus/>
                <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"> New</a>
                <div class="clear">
                  <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                  <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
        <!--   <?php if(in_array('65',$users_data['permission']['action'])) {
           ?>
              
         
           <?php } ?> -->
         </div>
       </div>
     </div>
     <div class="row mb-2">
       <label class="col-md-4">
         <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
          <?php foreach($gardian_relation_list as $gardian_list) 
          {?>
            <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
          <?php }?>
        </select>
        <?php if(!empty($field_list)){
         if($field_list[5]['mandatory_field_id']==87 && $field_list[5]['mandatory_branch_id']==$users_data['parent_id']){
          ?>
      <span class="text-danger">*</span><?php   }
      } ?></label>
        <div class="col-md-8">
          <select name="relation_simulation_id" id="relation_simulation_id" class="mr m_mr alpha_space">

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
          <?php if(!empty($field_list)){
         if($field_list[5]['mandatory_field_id']==87 && $field_list[5]['mandatory_branch_id']==$users_data['parent_id']){
          if(!empty($form_error)){ echo form_error('relation_name'); } 
        }
      }
      ?>
         
         <!-- <a href="#" class="btn-new">New</a> -->
       </div>
     </div>
     <div class="row mb-2">
     
       <label class="col-md-4">Mobile No.  <?php  if(!empty($field_list)){
          //echo "<pre>"; print_r($field_list);die;
        if($field_list[3]['mandatory_field_id']==50 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){?>         
         <span class="star">*</span>
         <?php 
       }
     } 
     ?></label>
     <div class="col-md-8">
       <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91"> 

       <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number m_number" placeholder="eg.9897221234" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return numericvalue(event);">
       <?php if(!empty($field_list)){
         if($field_list[3]['mandatory_field_id']==50 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
          if(!empty($form_error)){ echo form_error('mobile_no'); } 
        }
      }
      ?>
    </div>
  </div>
  <div class="row mb-2">
   <label class="col-md-4">Gender <span class="text-danger">*</span></label>
   <div class="col-md-8" id="gender">
     <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
     <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
     <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
     <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
   </div>
 </div>
 <div class="row mb-2">
  
   <label class="col-md-4"><b>Age<?php if(!empty($field_list)){
    if($field_list[1]['mandatory_field_id']==49 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
     <span class="star">*</span>
     <?php 
   }
 } 
 ?></b></label>
 <div class="col-md-8">
  <input type="text" id="age_y" name="age_y" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
  <input type="text" id="age_m" name="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
  <input type="text" id="age_d" name="age_d"  class="input-tiny m_tiny numeric"  maxlength="2" value="<?php echo $form_data['age_d']; ?>"> 
  
  <?php if(!empty($field_list)){

   if($field_list[1]['mandatory_field_id']==49 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
    if(!empty($form_error)){ echo form_error('age_y'); }
  }
} 
?>
</div>
</div>
<div class="row mb-2">
 <div class="col-md-4"><label>Address <?php if(!empty($field_list)){
                          if($field_list[1]['mandatory_field_id']=='48' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                                ?>
                                <span class="text-danger">*</span>
                                <?php 
                       }
                 } 
                                ?>
                </label>
           </div>                     
 <div class="col-md-8">
  <textarea name="address" id="address"><?php if(!empty($form_data['address'])){ echo $form_data['address'];} ?></textarea>
                               <?php if(!empty($field_list)){
                          if($field_list[1]['mandatory_field_id']=='48' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                                if(!empty($form_error)){ echo form_error('address'); }
                       }
                 } 
                                ?>
                              </div>
                            </div>

                           
                            <section class="collapse_sds">
                            <input type="hidden" name="country_id" id="countrys_id" value="<?php echo $form_data['country_id'];?>">
                            <input type="hidden" name="state_id" id="states_id" value="<?php echo $form_data['state_id'];?>">
                            <input type="hidden" name="city_id" value="<?php echo $form_data['city_id'];?>">
                                   
                            <h5><b>Guardian's Detail</b> </h5>
                            <div class="row mb-2">
                             <label class="col-md-4">Name</label>
                             <div class="col-md-8">
                              <input type="text" name="guardian_name"  value="<?php if(!empty($form_data['guardian_name'])){ echo $form_data['guardian_name'];} ?>">
                            </div>
                          </div>
                          <div class="row mb-2">
                           <label class="col-md-4">Phone</label>
                           <div class="col-md-8">
                            <input type="text" name="guardian_phone" maxlength="10"  value="<?php if(!empty($form_data['guardian_phone'])){ echo $form_data['guardian_phone'];} ?>">
                          </div>
                        </div>
                    
                                            
              </section>

            </div>
            <div class="col-md-4">

              <div class="row mb-2">
               <label class="col-md-4">Vehicle Type. <span class="text-danger">*</span></label>
               <div class="col-md-8">
                <select name="vehicle_type" id="vehicle_type" onchange="get_charge(this.value);">

                  <option value="">Select Vehicle Type</option>
                  <?php
                  if(!empty($vehicle_types))
                  {
                    foreach($vehicle_types as $list)
                    {
                      ?>
                      <option <?php if($form_data['vehicle_type']==$list->id){ echo 'selected="selected"'; } ?> value="<?php echo $list->id; ?>"><?php echo $list->type; ?></option>

                      <?php
                    }
                  }
                  ?>
                </select>
                <?php if(!empty($form_error)){ echo form_error('vehicle_type'); } ?>
              </div>
            </div>
            
             <div class="row mb-2">
                  <label class="col-md-4"></label>
               <div class="col-md-8">
                 <label class="btn btn-sm">
                    <input type="radio"  name="ven_tp" <?php if($form_data['owner_type']==1){ echo 'checked';}?> onClick="change_vehicle(this.value);" value="1" >
                    <span>Self Owned </span>
                </label>
                <label class="btn btn-sm">
                    <input type="radio"  name="ven_tp" <?php if($form_data['owner_type']==2){ echo 'checked';}?> onClick=" change_vehicle(this.value);" value="2">
                    <span>Leased</span>
                </label>
              </div>
            </div>

             <div class="row mb-2" id="vendor_show" style="display:<?php if($form_data['vendor_id']>0){ echo 'block';}else{ echo 'none';}?>">
                 <label class="col-md-4">Vendor</label>
               <div class="col-md-8">
                <select name="owner_name" onchange="change_vehicle_by_vendor(this.value);" id="vendor_id" onchange="return form_submit();">
                     <option value="">Select</option>
                         <?php
                              if(!empty($owner_list))
                              {
                                foreach($owner_list as $vendor)
                                {
                                  $selected_vendor = "";
                                  if($vendor->id==$form_data['vendor_id'])
                                  {
                                    $selected_vendor = "selected='selected'";
                                  }
                                  echo '<option value="'.$vendor->id.'" '.$selected_vendor.'>'.$vendor->name.'</option>';
                                }
                              }
                     ?> 
                </select>
              </div>
            </div>

              <div class="row mb-2">
               <label class="col-md-4">Vehicle No. <span class="text-danger">*</span></label>
               <div class="col-md-8">
                <select name="vehicle_no" onchange="get_vendor_charge(this.value);" id="vehicle_no">

                  <option value="">Select Vehicle No</option>
                  <?php
                  if(!empty($vehicle_list))
                  {
                    foreach($vehicle_list as $vehicle)
                    {
                      ?>
                      <option <?php if($form_data['vehicle_no']==$vehicle->id){ echo 'selected="selected"'; } ?> value="<?php echo $vehicle->id; ?>"><?php echo $vehicle->vehicle_no; ?></option>

                      <?php
                    }
                  }
                  ?>
                </select>
                <?php if(!empty($form_error)){ echo form_error('vehicle_no'); } ?>
              </div>
            </div>
            <div class="row mb-2">
             <label class="col-md-4">Driver Name <span class="text-danger">*</span></label>
             <div class="col-md-8">
              <select name="driver_name" id="driver_name">
                <option value="">Select Driver</option>
                <?php
                if(!empty($driver_list))
                {
                  foreach($driver_list as $driver)
                  {
                    ?>
                    <option <?php if($form_data['driver']==$driver->id){ echo 'selected="selected"'; } ?> value="<?php echo $driver->id; ?>"><?php echo $driver->driver_name; ?></option>
                    
                    <?php
                  }
                }
                ?>
              </select>
              <?php if(!empty($form_error)){ echo form_error('driver_name'); } ?>
            </div>
          </div>
          <div class="row mb-2">
           <label class="col-md-4">Hospital Staff</label>
           <div class="col-md-8">
            <select name="hospital_staff" id="hospital_staff">
             <option value="">Select</option>
             <?php
             if(!empty($employee_list))
             {
              foreach($employee_list as $employee)
              {
                ?>
                <option <?php if($form_data['hospital_staff']==$employee->id){ echo 'selected="selected"'; } ?> value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>

                <?php
              }
            }
            ?>

          </select>
        </div>
      </div>
      <div class="row mb-2">
       <label class="col-md-4">Referred By</label>
       <div class="col-md-8" id="referred_by">
        <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
        <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
        <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
      </div>
    </div>
    <div class="row mb-2" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?>>
     <label class="col-md-4">Referred By Doctor</label>
     <div class="col-md-8">
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
            <!-- <?php if(in_array('122',$users_data['permission']['action'])) {
              ?> -->
              <a  class="btn-new" id="doctor_add_modal">New</a>
              <!-- <?php } ?> -->


            </div>
          </div>

          <!--- referred by Hospital --->
          <div class="row mb-2" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
            <div class="col-md-12">
             <div class="row">
               <label class="col-md-4">Referred By Hospital</label>
               <div class="col-md-8">
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
        <!--- Referred by Hospital --->

        <div class="row mb-2">
         <label class="col-md-4">Booking Date</label>
         <div class="col-md-8">
          <input type="text" name="booking_date" id="bookingdate" class="datepicker booking_datepicker m_input_default" value="<?php echo $form_data['booking_date']; ?>" />
        </div>
         <?php if(!empty($form_error)){ echo form_error('booking_date'); } ?>
      </div> 
       <div class="row mb-2">
         <label class="col-md-4">Booking time</label>
         <div class="col-md-8">
          <input type="text" name="booking_time" id="bookingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['booking_time']; ?>" />
        </div>
         <?php if(!empty($form_error)){ echo form_error('booking_time'); } ?>
      </div> 
      
           <div class="row mb-2">
         <label class="col-md-4">Going Date</label>
         <div class="col-md-8">
          <input type="text" name="going_date" id="goingdate" class="datepicker going_datepicker m_input_default" value="<?php echo $form_data['going_date']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('going_date'); } ?>
        </div>
       
      </div> 
       <div class="row mb-2">
         <label class="col-md-4">Going time</label>
         <div class="col-md-8">
          <input type="text" name="going_time" id="goingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['going_time']; ?>" />
        </div>
         <?php if(!empty($form_error)){ echo form_error('going_time'); } ?>
      </div>
      
      <script>
          function TimePickerCtrl($) {
  var startTime = $('#bookingtime').datetimepicker({
    format: 'HH:mm'
  });
  
  var endTime = $('#goingtime').datetimepicker({
    format: 'HH:mm',
    minDate: startTime.data("DateTimePicker").date()
  });
  
  function setMinDate() {
    return endTime
      .data("DateTimePicker").minDate(
        startTime.data("DateTimePicker").date()
      )
    ;
  }
  
  var bound = false;
  function bindMinEndTimeToStartTime() {
  
    return bound || startTime.on('dp.change', setMinDate);
  }
  
  endTime.on('dp.change', () => {
    bindMinEndTimeToStartTime();
    bound = true;
    setMinDate();
  });
}

$(document).ready(TimePickerCtrl);
      </script>
       <!-- 21-04-2020 -->
      <div class="row mb-2">
       <label class="col-md-4">Distance Type</label>
       <div class="col-md-8" id="distance_type">
        <input type="radio" onchange="distance_type_charge(this.value);" name="distance_type" value="0" <?php if($form_data['distance_type']==0){ echo 'checked="checked"'; } ?>> Local &nbsp;
        <input type="radio" onchange="distance_type_charge(this.value);" name="distance_type" value="1" <?php if($form_data['distance_type']==1){ echo 'checked="checked"'; } ?>> Outstation
        <?php if(!empty($form_error)){ echo form_error('distance_type'); } ?>
      </div>
    </div>
     <!-- 21-04-2020 -->
     <div class="row mb-2">
                           <label class="col-md-4">Location  <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <select name="location" id="location">
                                <option value="">Select</option>
                                <?php
                               
                              if(!empty($location_list))
                              {
                                foreach($location_list as $location)
                                {
                                  $selected_location = "";
                                  if($location->id==$form_data['location'])
                                  {
                                    $selected_location = "selected='selected'";
                                  }
                                  echo '<option value="'.$location->id.'" '.$selected_location.'>'.$location->location_name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                           <!--   <a class="btn-new" href="javascript:void(0)" onClick="location_modal()"><i class="fa fa-plus"></i> New</a> -->
                               <?php if(!empty($form_error)){ echo form_error('location'); } ?>
                           </div>
                        </div>
    <!-- 21-04-2020 -->
      <div class="row mb-2">
       <label class="col-md-4">Pick from <span class="text-danger">*</span></label>
       <div class="col-md-8">
          <input type="text" name="source_from" value="<?php if(!empty($form_data['source_from'])){ echo $form_data['source_from'];} ?>">
     <!--    <select name="source_from" id="patient_source_id" class="w-150px m_select_btn">
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
        </select> --> 

       
        <?php if(!empty($form_error)){ echo form_error('source_from'); } ?>
      </div>
    </div>
    <div class="row mb-2">
     <label class="col-md-4">Drop to <span class="text-danger">*</span></label>
     <div class="col-md-8">
      <input type="text" name="destination" value="<?php if(!empty($form_data['destination'])){ echo $form_data['destination'];} ?>">
      <?php if(!empty($form_error)){ echo form_error('destination'); } ?>
    </div>
  </div>
   <div class="row mb-2">
       <label class="col-md-4">Distance(in km)<span class="text-danger">*</span></label>
       <div class="col-md-8">
          <input type="text" name="distance" id="distance" value="<?php if(!empty($form_data['distance'])){ echo $form_data['distance'];} ?>" onkeyup="get_distance_charge(this.value);" onkeypress="return isNumberKey(event);">
        <?php if(!empty($form_error)){ echo form_error('distance'); } ?>
      </div>
  </div>
  
  	
<!-- Particular add  -->	
<div class="row m-b-5">	
  <div class="col-xs-4">	
    <strong>Miscellaneous  </strong><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is an objects used in hospital like injections,xray machine.</span></a></sup>	
  </div>	
  <div class="col-xs-8">	
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
     <a href="javascript:void(0)" onclick="return add_particulars();"  class="btn-new"> New</a>	
   <?php } ?>  	
   <?php  echo form_error('particular_id');  ?>	
 </div>	
</div> <!-- row -->	
<div class="row m-b-5">	
  <div class="col-xs-4">	
    <strong>Charges</strong>	
  </div>	
  <div class="col-xs-8">	
    <input type="text"  name="charges" class="price_float m_input_default" id="charges" value="">	
  </div>	
</div> <!-- row -->	
<div class="row m-b-5" id="quantity_id">	
  <div class="col-xs-4">	
    <strong>Quantity</strong>	
  </div>	
  <div class="col-xs-8">	
    <input type="text"  name="quantity" id="quantity"  class="numeric m_input_default" onkeyup="get_particular_amount();" value="">	
  </div>	
</div> <!-- row -->	
    <div class="row m-b-5" id="amount_id">	
      <div class="col-xs-4">	
        <strong>Amount</strong>	
      </div>	
      <div class="col-xs-8">	
        <input type="text"  class="price_float m_input_default" name="amount" id="amount" value="">	
        <a class="btn-new" onclick="particular_payment_calculation(0);"> Add </a>	
      </div>	
    </div> <!-- row -->	
    <!-- Particular op -->
    



</div>
<div class="col-sm-4">   

  <section>
    <!-- <h5><b>Mode of Payment</b> </h5> -->
   <?php 	
$perticuller_list = $this->session->userdata('amb_particular_billing');  
//echo "<pre>"; print_r($perticuller_list); 
?>	
    </div> <!-- Main 4 -->	
   	
  <div class="col-xs-4">	
    <div id="particular_list_id">	
    <table class="table table-bordered table-striped opd_billing_table" id="particular_list">	
      <thead class="bg-theme">	
          <tr>	
              <th align="center" width="">	
                <input name="selectall" class="" id="selectall" value="" onclick="chech_all();" type="checkbox"  onclick="toggle(this);">	
              </th>	
              <th scope="col">S.No.</th>	
              <th>Miscellaneous</th>	
              <th>Charges</th>	
              <th>Quantity</th>	
          </tr>	
           </thead>	
          <?php  	
          $i = 0;	
          $parti_charge=0;	
          if(!empty($perticuller_list))	
          {	
             $i = 1;	
             foreach($perticuller_list as $perticuller)	
             {	
              $parti_charge +=$perticuller['amount'];	
              ?>	
                <tr>	
                  <td>	
                    <input type="checkbox"  class="part_checkbox booked_checkbox" name="particular_id[]" value="<?php echo $perticuller['particular']; ?>" >	
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
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_ambulance_particulars_vals();">	
             <i class="fa fa-trash"></i> Delete	
          </a>	
      <?php  if(!empty($form_error)){ ?>    	
     <tr>	
            <td colspan="5"><?php  echo form_error('particular_id');  ?></td>	
        </tr>	
        <?php } ?>	
  </table>	
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
    	
  <?php $opd_particular_payment =  $this->session->userdata('amb_particular_billing'); 
  //echo "<pre>";print_r($opd_particular_payment); exit;
  ?>  
  <!--19-05-2020-->
   <div class="row m-b-5"  id="vendor_commission" style="display:<?php if($form_data['vendor_id']>0){ echo 'block';}else{ echo 'none';}?>">	
      <div class="col-xs-4">	
        <strong>Vendor Commission</strong>	
      </div>	
      <div class="col-xs-8">	
        <input type="text"   class="price_float m_input_default" id="vendor_charge" name="vendor_charge"  value="<?php echo number_format($form_data['vendor_charge'],2,'.','');?>">	
      </div>	
    </div>
  <!--19-05-2020-->
    <div class="row m-b-5">	
      <div class="col-xs-4">	
        <strong>Miscellaneous Charge</strong>	
      </div>	
      <div class="col-xs-8">	
        <input type="text" readonly=""  class="price_float m_input_default" name="particulars_charges" id="particulars_charges" value="<?php echo number_format($parti_charge,2,'.','');?>">	
      </div>	
    </div> <!-- row -->	
   
   
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
                    

                              <!-- <div class="row mb-2">
                                 <label class="col-md-4">Charge</label>
                                 <div class="col-md-8">
                                    <input type="text">
                                 </div>
                               </div> -->  
                              
                               <div class="row mb-2">

                                 <div class="col-md-4"><b>Ambulance Charge</b></div>
                                 <div class="col-md-8">
                                  <input type="text"  name="consultants_charge" id="consultants_charge" class="price_float m_input_default" onchange="update_amount(this.value);" value="<?php echo number_format($form_data['consultants_charge'],2,'.', ''); ?>">

                                </div>
                              </div>
                              
                              <div class="row m-b-5">	
                                  <div class="col-xs-4">	
                                    <strong>Total Amount</strong>	
                                  </div>	
                                  <div class="col-xs-8">	
                                    <input type="text" readonly="" class="price_float m_input_default" name="total_amount" id="total_amount" value="<?php echo number_format($form_data['total_amount'],2,'.',''); ?>">	
                                  </div>	
                                </div>
                                
                               <?php 

                               $discount_val_setting = get_setting_value('ENABLE_DISCOUNT'); 
                               if($discount_val_setting==1)
                               {
                                ?>
                                <div class="row mb-2">


                                 <div class="col-md-4"><b>Discount (Rs.)</b></div>
                                 <div class="col-md-8">
                                  <input type="text" name="discount" onchange="check_paid_amount();discount_vals();" class="price_float m_input_default" id="discount" value="<?php  echo number_format($form_data['discount'],2,'.', '');  ?>">
                                </div>

                              </div> <!-- row -->
                            <?php } else{ 

                              ?>
                              <input type="hidden" name="discount" class="price_float" id="discount" value="">

                              <?php 

                            } ?>
                            <div class="row mb-2">

                             <div class="col-md-4"><b>Net Amount</b></div>
                             <div class="col-md-8">
                              <input type="text" readonly="" name="net_amount" class="price_float m_input_default" id="net_amount" value="<?php echo number_format($form_data['net_amount'],2,'.', ''); ?>">

                            </div>
                          </div> <!-- row -->
                          <div class="row mb-2">

                           <div class="col-md-4"><b>Paid Amount</b></div>
                           <div class="col-md-8">
                            <input type="text" name="paid_amount" class="price_float m_input_default" id="paid_amount" value="<?php echo number_format($form_data['paid_amount'],2,'.', ''); ?>" 
                            onchange="check_paid_amount();total_balance(this.value);">
<input type="hidden" name="initial_paid_amount" id="initial_paid_amount" value="<?php echo number_format($form_data['paid_amount'],2,'.', ''); ?>">
                          </div>
                        </div> <!-- row -->
                        <?php  if(!empty($form_data['data_id'])){?>
                         <div class="row mb-2">

                           <div class="col-md-4"><b> Total Paid Amount</b></div>
                           <div class="col-md-8">
                            <input type="text" name="total_paid_amount" class="price_float m_input_default total_paid_amount" id="paid_amount" value="<?php if(!empty($form_data['total_paid_amount'])){echo number_format($form_data['total_paid_amount'],2,'.', '');}?>" 
                             readonly>

                          </div>
                        </div> <!-- row -->
                        <?php } ?>
                        
                        <div class="row mb-2" >

                         <div class="col-md-4"><b>Balance</b></div>
                         <div class="col-md-8">
                            
                          <input type="text" name="balance" id="balance" class="price_float" value="<?php echo number_format($form_data['balance'],2,'.', ''); ?>" readonly="">
                        
                        <input type="hidden" name="vendor_id" id="vendor_ids" value="<?php echo $form_data['vendor_id'];?>">
                        <!--<input type="hidden" name="refund_amount" id="refund_amount" value="<?php //echo $form_data['refund_amount'];?>">-->
                        <input type="hidden" name="ven_charge_type" id="ven_charge_type" value="<?php echo $form_data['ven_charge_type'];?>">
                        <input type="hidden" name="branch_id" value="<?php echo $users_data['parent_id'];?>">
                        </div>
                      </div> <!-- row -->
                        <?php if(isset($form_data['refund_amount']) && !empty($form_data['refund_amount'])){?>
                        
                          <div class="row mb-2" >
                         <div class="col-md-4"><b>Refund Amount</b></div>
                         <div class="col-md-8">
                          
                          <input type="text" name="refund_amount" id="refund_amount" class="price_float" value="<?php echo number_format($form_data['refund_amount'],2,'.', ''); ?>" readonly="">
                        
                        </div>
                      </div> 
                      <?php } else{?>
                       <input type="hidden" name="refund_amount" id="refund_amount" value="<?php echo $form_data['refund_amount'];?>">
                      <?php } ?>
                        
                      
                      <div class="row mb-2">
                       <label class="col-md-4">Remarks <?php  if(!empty($field_list)){
        if($field_list[4]['mandatory_field_id']==86 && $field_list[4]['mandatory_branch_id']==$users_data['parent_id']){?>         
         <span class="star">*</span>
         <?php 
       }
     } 
     ?></label>
                       <div class="col-md-8">
                        <textarea name="remark" id="remark"><?php if(!empty($form_data['remark'])){ echo $form_data['remark'];} ?></textarea>
                          <?php if(!empty($field_list)){
         if($field_list[4]['mandatory_field_id']==86 && $field_list[4]['mandatory_branch_id']==$users_data['parent_id']){
          if(!empty($form_error)){ echo form_error('remark'); } 
        }
      }
      ?>
                      </div>
                    </div>
                    
                    <div class="row mb-2">
                     <label class="col-md-4"></label>
                     <div class="col-md-8">
                      <button class="btn-save" id="btnsubmit"><i class="fa fa-floppy-o"></i> Save</button>
                      <a href="<?php echo base_url('ambulance/booking');?>"><button class="btn-update" type="button"><i class="fa fa-sign-out"></i> Exit </button></a>
                    </div>
                    </div>
                    </section>
                  </div>
                </div>


              </article>

            </div>
          </div>
        </div>
      </form>
    </section>
    </div>
    <script>
      /****** Only Alphabet **********/
         function alphaOnly(event) {
          var key = event.keyCode;
        //   alert(key);
          return ((key >= 65 && key <= 90 ) || key == 8 || key== 32);
        };
        
  /****** Only Alphabet **********/
  /****** Only Numeric **********/
           
    function numericvalue(event){
        var e = event.keyCode;
         if (e != 8 && e != 0 && (e < 48 || e > 57))
         {
             return false;
            }
        else{
         
        }
    }
         
   /****** Only Numeric **********/ 
    </script>
    <script>

      function get_charge(val)
      {
          
        if(val!='')
       {	
        var vehicle_id = val;	
        var consultants_charge = $('#consultants_charge').val();
        var particulars = $('#particulars_charges').val();
        var total_amount = consultants_charge;	
        var discount = $('#discount').val();	
        var balance = total_amount-discount;	
        var total = $('#particulars_charges').val();
        var id=$('#data_id').val();
        $.ajax({	
          type: "POST",	
          url: "<?php echo base_url(); ?>ambulance/booking/get_charge/", 	
          dataType: "json",	
          data: 'vehicle_id='+vehicle_id+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance+'&total_amount='+total+'&id='+id,	
          success: function(result)	
          {	
            //  console.log(result);
            $("#consultants_charge").val(result.consultants_charge);	
            	
            $("#net_amount").val(result.net_amount);
            <?php if(empty($form_data['data_id'])){ ?> $("#paid_amount").val(result.net_amount); <?php } ?>
            	
            $("#total_amount").val(result.total_amount);
            $("#vendor_ids").val(result.vendor_id);
            $("#vendor_charge").val(result.vendor_charge);
            $("#ven_charge_type").val(result.ven_charge_type);
            $('#refund_amount').val(result.refund_amount);
            $('#balance').val(result.balance);
           } 	
         });
      }
      else
      {
        $('consultants_charge').val('0.00');
        $("#net_amount").val('0.00');
        $("#paid_amount").val('0.00');
       // $("#total_amount").val('0.00');
     }
     var distance = +($('#distance').val());
     if(distance !='')
     {
        get_distance_charge(distance);
     }
}



  function get_vendor_charge(val)
      {
       
        if(val!='')
       {	
        var vehicle_id = val;	
        $.ajax({	
          type: "POST",	
          url: "<?php echo base_url(); ?>ambulance/booking/get_vendor_charge/", 	
          dataType: "json",	
          data: 'vehicle_id='+vehicle_id,	
          success: function(result)	
          {	
            $("#vendor_ids").val(result.vendor_id);
            $("#vendor_charge").val(result.vendor_charge);
            $("#ven_charge_type").val(result.ven_charge_type);
           } 	
         });
      }
     var distance = +($('#distance').val());
     if(distance !='')
     {
        get_distance_charge(distance);
     }
}
      
       /* 21-04-2020 */
      function get_distance_charge(val)
      {
        if(val!='')
       {
      
          var distance = $('#distance').val();
         // var distance_type = $('#distance_type').val();
         var distance_type=  $('input[name=distance_type]:checked', '#ambulance_form').val();
          var vehicle_id = $('#vehicle_no').val();
          var vehicle_type = $('#vehicle_type').val();
          var consultants_charge = $('#consultants_charge').val();
          var particulars_charges = $('#particulars_charges').val();
          var total_amount = $('#total_amount').val();
          var discount = $('#discount').val();
          var vendor_charge = $("#vendor_charge").val();
          var ven_charge_type =  $("#ven_charge_type").val();
          var balance = total_amount-discount;
          var id=$('#data_id').val();
          
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>ambulance/booking/get_distance_charge/", 
          dataType: "json",
          data: 'vehicle_id='+vehicle_id+'&vehicle_type='+vehicle_type+'&distance_type='+distance_type+'&distance='+distance+'&discount='+discount+'&balance='+balance+'&particulars_charges='+particulars_charges
          +'&vendor_charge='+vendor_charge+'&ven_charge_type='+ven_charge_type+'&id='+id,
          success: function(result)
          {
            $("#consultants_charge").val(result.consultants_charge);
            $("#net_amount").val(result.net_amount);
            <?php if(empty($form_data['data_id'])){ ?> $("#paid_amount").val(result.net_amount); <?php } ?>
            //$("#paid_amount").val(result.net_amount);
            $("#total_amount").val(result.total_amount); 
            $("#vendor_charge").val(result.vendor_charge); 
            $("#ven_charge_type").val(ven_charge_type); 
            $("#refund_amount").val(result.refund_amount);
            $("#balance").val(result.balance);
             } 
           });

      }
        else
        {
          $('consultants_charge').val('0.00');
          $("#net_amount").val('0.00');
          $("#paid_amount").val('0.00');
         // $("#total_amount").val('0.00');
       }
    }
      $(document).ready(function() {
        $("input[name='referred_by']").click(function() 
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
    /*      $(document).ready(function() {
         var data_id=$('#data_id').val();
         if(data_id!="")
         {
            $( "#more" ).removeClass( "collapse" );
         }
         else{
          
         }
      });*/

    
      $(document).ready(function(){


       $('.booking_datepicker').datepicker({
            format: 'dd-mm-yyyy', 
            autoclose: true, 
            //startDate : new Date(), 
          }).on("change", function(selectedDate) 
          { 
              var start_data = $('.booking_datepicker').val();
              $('.going_datepicker').datepicker('setStartDate', start_data); 
              
          });
        
          $('.going_datepicker').datepicker({
            format: 'dd-mm-yyyy',     
            autoclose: true,  
          }).on("change", function(selectedDate) 
          {   
             
          });
       /* $('.datepicker').datepicker({
          format: "dd-mm-yyyy",
         autoclose: true,
         startDate:'+0d',
        }); */

        $('.datepicker3').datetimepicker({
          format: 'LT'
        });
      });

      $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#ambulance_form').submit();
  })
    </script>
    <script>
      function update_amount(val)
      {
       if(val!='')	
       {	
        var total_amount = $('#total_amount').val();	
        var particulars_charges = $('#particulars_charges').val();	
        var consultants_charge = val;	
        var total_amount = consultants_charge+total_amount;	
        var discount = $('#discount').val();	
        var balance = total_amount-discount;	
        $.ajax({	
          type: "POST",	
          url: "<?php echo base_url(); ?>ambulance/booking/update_amount/", 	
          dataType: "json",	
          data: 'particulars_charges='+particulars_charges+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance,	
          success: function(result)	
          {	
            $("#consultants_charge").val(result.consultants_charge);	
            $("#particulars_charges").val(result.particulars_charges);	
            $('#total_amount').val(result.total_amount);	
            $("#net_amount").val(result.net_amount);	
            <?php if(empty($form_data['data_id'])){ ?> $("#paid_amount").val(result.net_amount); <?php } ?>
            //$("#paid_amount").val(result.net_amount);	
            } 	
         });
      }
      else
      {
        $("#net_amount").val('0.00');
        $("#paid_amount").val('0.00');
       // $("#total_amount").val('0.00');
     }
   }

      function total_balance(val)
      {
       if(val!='')
       {

      
            var consultants_charge =$('#consultants_charge').val();
            var discount = $('#discount').val();
            var paid_amount = val;
            var total_amount=consultants_charge-discount;
            var net_amount= $('#net_amount').val();
            var initial_paid_amount = $('#initial_paid_amount').val();
            
            var refund_amount = $('#refund_amount').val();
            
             var total_paid_amount = $('.total_paid_amount').val();
            var tot_paid_amt = parseFloat(paid_amount)+parseFloat(total_paid_amount)-parseFloat(initial_paid_amount);
            // alert(paid_amount);
            if(parseInt(net_amount)>parseInt(paid_amount))
            {
                var balance = net_amount-paid_amount; //15 july 2020
                
                 $('#refund_amount').val('0.00');
                 $('#balance').val(balance);
                 //$('.total_paid_amount').val(tot_paid_amtss);
                 
            }
            
            if(parseInt(net_amount)<parseInt(tot_paid_amt))
            {
                /*var refund_amount = parseInt(tot_paid_amt)-parseInt(net_amount)+parseInt(refund_amount);
                 var tot_paid_amtss = Number(tot_paid_amt).toFixed(2);
                $('.total_paid_amount').val(tot_paid_amtss);*/
                
                var balance = net_amount-paid_amount; //15 july 2020
                
                
                $('#refund_amount').val('0.00');
                $('#balance').val(balance);
            }
            
            
           

        
      }
      else
      {
        
        $("#balance").val('0.00');
     }
   }


   function check_paid_amount()
{
    var consultants_charge = $('#consultants_charge').val();
    
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    
    if(parseInt(discount)>parseInt(total_amount))
    {
      alert('Discount amount can not be greater than net amount');
      $('#discount').val('0.00');
      if(parseInt(net_amount)>parseInt(paid_amount))
            {
                var balance = net_amount-paid_amount;
                $('#refund_amount').val('0.00');
                 $('#balance').val(balance);
            }
            
            if(parseInt(net_amount)<parseInt(paid_amount))
            {
                var refund_amount = paid_amount-net_amount;
                $('#refund_amount').val(refund_amount);
                $('#balance').val('0.00');
            }
      return false;
    }
    if(parseInt(paid_amount)>parseInt(net_amount))
    {
      alert('Paid amount can not be greater than net amount');
      
            if(parseInt(net_amount)>parseInt(paid_amount))
            {
                var balance = net_amount-paid_amount;
                $('#refund_amount').val('0.00');
                 $('#balance').val(balance);
            }
            
            if(parseInt(net_amount)<parseInt(paid_amount))
            {
                var refund_amount = paid_amount-net_amount;
                $('#refund_amount').val(refund_amount);
                $('#balance').val('0.00');
            }
      
      $('#paid_amount').val(net_amount);
      return false;
    }
}


  function discount_vals()
  {
     var timerA = setInterval(function(){  
          payment_calc();
          clearInterval(timerA); 
        }, 100);
  }

  function payment_calc()
  {
    var consultants_charge = $('#consultants_charge').val();
    
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var total_paid_amount = $('.total_paid_amount').val();
    var balance = $('#balance').val();
    var refund_amount = $('#refund_amount').val();
    
    var initial_paid_amount = $('#initial_paid_amount').val();

    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>ambulance/booking/calculate_payment/", 
            dataType: "json",
            data: 'consultants_charge='+consultants_charge+'&total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance+'&total_paid_amount='+total_paid_amount+'&refund_amount='+refund_amount+'&initial_paid_amount='+initial_paid_amount,
            success: function(result)
            {
               
               $('#consultants_charge').val(result.consultants_charge); 
               $('#total_amount').val(result.total_amount); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.paid_amount); 
               $('#balance').val(result.balance);
               //$('#refund_amount').val(result.refund_amount);
               
            } 
          });
  }

   function payment_function(value,error_field)
   {
    $('#updated_payment_detail').html('');
    $.ajax({
      type: "POST",
      url: "<?php echo base_url('ambulance/booking/get_payment_mode_data')?>",
      data: {'payment_mode_id' : value,'error_field':error_field},
      success: function(msg){
       $('#payment_detail').html(msg);
     }
   });
  }
  
  function father_husband_son()
  {
   $("#relation_name").css("display","block");
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
 function simulation_modal()
 {
  var $modal = $('#load_add_simulation_modal_popup');
  $modal.load('<?php echo base_url().'simulation/add/' ?>',
  {
   
      },
      function(){
        $modal.modal('show');
      });
}




$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });

  var $modal = $('#load_add_modal_popup');

  $('#doctor_add_modal').on('click', function(){
    $modal.load('<?php echo base_url().'doctors/add/' ?>',
    {
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
    {},
function(){
  $modal.modal('show');
});

  });

});
$(document).ready(function(){
  var $modal = $('#load_add_emp_type_modal_popup');

  $('#patient_source_add_modal').on('click', function(){
    $modal.load('<?php echo base_url().'patient_source/add/' ?>',
    {},
function(){
  $modal.modal('show');
});

  });


});
</script>



<!-- container-fluid -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_emp_type_modal_popup" class="modal fade top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="confirm_billing_print" class="modal fade dlt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
      <!-- <div class="modal-body"></div> -->
      <div class="modal-footer">
         <?php 
              if($users_data['parent_id']=='60')
                {
                    $ambulance_booking_id = $this->session->userdata('ambulance_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$ambulance_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $ambulance_booking_id = $this->session->userdata('ambulance_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$ambulance_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                { $id= $this->session->userdata('ambulance_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ambulance/booking/print_booking_report/").$id.'/'.$users_data['parent_id']; ?>');">Print</a>
                    <?php 
                }
              
              ?>
       <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
      </div>
    </div>
  </div>  
</div>
<div class="container-fluid  navbar-fixed-bottom">
  <?php $this->load->view('include/footer'); ?>
<script type="text/javascript">
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
 </script>
 	
<!-- particular -->	
<script>	
function get_particulars_data(particulars_id)	
{	
    var charges = $('#charges').val();	
    var amount = $('#amount').val();	
    var quantity = $('#quantity').val();	
    var ins_company_id_val='';	
    var url="<?php echo base_url(); ?>general/amb_particulars_list/"+particulars_id;	
     $.ajax({url: url, 	
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
            url: "<?php echo base_url(); ?>ambulance/booking/particular_calculation/", 	
            dataType: "json",	
            data: 'charges='+charges+'&quantity='+quantity,	
            success: function(result)	
            { 	
               $('#amount').val(result.amount); 	
            } 	
          });	
  }	
  function particular_payment_calculation(test_val)	
  {	
    	
    var amount = $('#amount').val();	
    var consultants_charge = $('#consultants_charge').val();	
    var quantity = $('#quantity').val();	
    var particular = $('#particulars').val();	
    var particulars = $('#particulars option:selected').text();	
    var discount = $('#discount').val();	
    var particulars_charges = $('#particulars_charges').val();	
    var balance = $('#balance').val();	
    var total_amount = $('#total_amount').val();	
    var total_paid_amount = $('.total_paid_amount').val();
    var paid_amount = $('#paid_amount').val();
     var refund_amount = $('#refund_amount').val();
    $.ajax({	
            type: "POST",	
            url: "<?php echo base_url(); ?>ambulance/booking/particular_payment_calculation/", 	
            dataType: "json",	
            data: 'amount='+amount+'&total_amount='+total_amount+'&consultants_charge='+consultants_charge+'&quantity='+quantity+'&particular='+particular+'&particulars='+particulars+'&balance='+balance+'&discount='+discount+'&total_paid_amount='+total_paid_amount+'&paid_amount='+paid_amount+'&particulars_charges='+particulars_charges+'&test_val='+test_val+'&refund_amount='+refund_amount,	
            success: function(result)	
            {	
              $('#particular_list').html(result.html_data);	
              $('#total_amount').val(result.total_amount); 	
              $('#consultants_charge').val(result.consultants_charge); 	
              $('#net_amount').val(result.net_amount);	
              $('#particulars_charges').val(result.particulars_charges);  	
              $('#discount').val(result.discount); 
              <?php if(empty($form_data['data_id'])){ ?> $("#paid_amount").val(result.net_amount); <?php } ?>
             // $('#paid_amount').val(result.net_amount); 	
              $('#balance').val(result.balance);
              $('#refund_amount').val(result.refund_amount);
              $('#charges').val('');	
              $('#amount').val('');	
              $('#quantity').val('1');  	
              $("#particulars").prop("selectedIndex", 0);	
            } 	
          });	
  }	
function delete_ambulance_particulars_vals() 	
  {           	
       var allVals = [];	
       $('.booked_checkbox').each(function() 	
       {	
         if($(this).prop('checked')==true && !isNaN($(this).val()))	
         {	
              allVals.push($(this).val());	
         } 	
       });	
       remove_ambulance_particulars_vals(allVals);	
  } 	
  function remove_ambulance_particulars_vals(allVals)	
  {    	
   if(allVals!="")	
   {	
      var particulars_charges = $('#particulars_charges').val();	
     // var discount = $('#discount').val();	
      var paid_amount = $('#paid_amount').val();	
      	
      var total_amount = $('#total_amount').val();	
      var discount = $('#discount').val();
      var total_paid_amount = $('.total_paid_amount').val();
      var refund_amount = $('#refund_amount').val();
      $.ajax({	
              type: "POST",	
              url: "<?php echo base_url('ambulance/booking/remove_opd_particular');?>",	
              	
              data: {particular_id: allVals,particulars_charges:particulars_charges,discount:discount,paid_amount:paid_amount, total_amount:total_amount,discount:discount,total_paid_amount:total_paid_amount,refund_amount:refund_amount},	
              	
              dataType: "json",	
              success: function(result) 	
              { 	
                $('#particular_list').html(result.html_data);	
                $('#particulars_charges').val(result.particulars_charges);	
                $('#total_amount').val(result.total_amount); 	
                $('#net_amount').val(result.net_amount); 	
                $('#discount').val(result.discount); 
                <?php if(empty($form_data['data_id'])){ ?> $("#paid_amount").val(result.net_amount); <?php } ?>
                //$('#paid_amount').val(result.net_amount); 	 //total_amount
                $('#balance').val(result.balance);  
                $('#refund_amount').val(result.refund_amount);  
              }	
          });	
   }	
  }	
function chech_all(){	
if ($('#selectall').prop('checked') == true) {	
      $('.booked_checkbox').prop('checked', true);	
    } 	
    else {	
      $('.booked_checkbox').prop('checked', false);	
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

function distance_type_charge(type)
{
  get_distance_charge(1);
     
}

function change_vehicle(type)
{
    if(type==2){
     $.ajax({
        url: "<?php echo base_url(); ?>ambulance/booking/vendor_list/"+type,
        success: function(result) 
        {
            $('#vendor_id').html(result);
        }
      });
     $('#vendor_show').show();
     $('#vendor_commission').show();
    }
    else{
         $.ajax({
        url: "<?php echo base_url(); ?>ambulance/booking/lease_vehicle_list/",
        success: function(result) 
        {
            $('#vehicle_no').html(result);
        }
      });
         $('#vendor_show').hide();
         $('#vendor_commission').hide();
    }
}
 function change_vehicle_by_vendor(id)
 {
      $.ajax({
        url: "<?php echo base_url(); ?>ambulance/booking/vendor_vehicle_list/"+id,
        success: function(result) 
        {
           
            $('#vehicle_no').html(result);
             get_vendor_charge(id);
        }
      });
 }
 
 /* function get_vendor_charge(id)
 {
      $.ajax({
        url: "<?php echo base_url(); ?>ambulance/booking/get_vendor_commission/"+id,
        success: function(result) 
        {
           
            $('#vendor_commission').val(result);
        }
      });
 }*/
 
</script>	

</div>
</body>
</html>