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
                     <label class="col-md-4">Enquiry No. <span class="text-danger">*</span></label>
                     <div class="col-md-8">
                      <input type="text" readonly="" id="enquiry_no" class="m_input_default" name="enquiry_no" value="<?php echo $form_data['enquiry_no']; ?>"/> 
                    </div>
                  </div>
                  <div class="row mb-2">
                   <label class="col-md-4">Patient Name  <span class="text-danger">*</span></label>
                   <div class="col-md-8">
                    <select class="mr m_mr alpha_space"  name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                      <?php
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
                <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
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
        </label>
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
         <!-- <a href="#" class="btn-new">New</a> -->
       </div>
     </div>
     <div class="row mb-2">
     
       <label class="col-md-4">Mobile No.  <?php  if(!empty($field_list)){
        if($field_list[0]['mandatory_field_id']==50 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
         <span class="star">*</span>
         <?php 
       }
     } 
     ?></label>
     <div class="col-md-8">
       <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91"> 

       <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number m_number" placeholder="eg.9897221234" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return numericvalue(event);">
       <?php if(!empty($field_list)){
         if($field_list[0]['mandatory_field_id']==50 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
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
         <label class="col-md-4">Guardian Name</label>
         <div class="col-md-8">
          <input type="text" name="guardian_name"  value="<?php if(!empty($form_data['guardian_name'])){ echo $form_data['guardian_name'];} ?>">
        </div>
      </div>
      <div class="row mb-2">
       <label class="col-md-4">Guardian Phone</label>
       <div class="col-md-8">
        <input type="text" name="guardian_phone" maxlength="10"  value="<?php if(!empty($form_data['guardian_phone'])){ echo $form_data['guardian_phone'];} ?>">
      </div>
    </div>

     <div class="row mb-2">
         <div class="col-md-8" style="text-align: right;">
            <a href="javascript:void(0);" class="show_hide_more" data-toggle="collapse"   onclick="more_patient_info()">More Info</a>
         </div>
        </div>
        
        

                         <!--   <div class="btn-custom" data-toggle="collapse" data-target="#more"><b>More</b> </div>-->
                         
                        
             <div class="more_content" id="patient_info" style="display: none;">                
                
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
                  </div>
                <div class="row m-b-4">
                    <div class="col-md-12">
                       <div class="row">
                         <div class="col-md-4"><b>Address 1</b><?php if(!empty($field_list)){
                                          if($field_list[1]['mandatory_field_id']=='48' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                                                ?>
                                                <span class="text-danger">*</span>
                                                <?php 
                                       }
                                 } 
                                                ?></div>
                         <div class="col-md-8">
                           <input type="text" name="address" id="address" class="m_input_default address" maxlength="250" value="<?php echo $form_data['address']; ?>">
                           <?php if(!empty($field_list)){
                                          if($field_list[1]['mandatory_field_id']=='48' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                                                if(!empty($form_error)){ echo form_error('address'); }
                                       }
                                 } 
                                                ?>
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
                            
                                     <div class="row mb-2">
                                       <label class="col-md-4">Country</label>
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
                                    <div class="row mb-2">
                                     <label class="col-md-4">State</label>
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
                                <div class="row mb-2">
                                 <label class="col-md-4">City</label>
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

            </div>
            <div class="col-md-4">

        <!--- Referred by Hospital --->

        <div class="row mb-2">
         <label class="col-md-4">Booking Date</label>
         <div class="col-md-8">
          <input type="text" name="booking_date" id="bookingdate" class="datepicker m_input_default" value="<?php echo $form_data['booking_date']; ?>" />
         <!--  <input type="text" name="booking_time" id="bookingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['booking_time']; ?>" /> -->
        </div>
      </div> 
       <div class="row mb-2">
         <label class="col-md-4">Booking time</label>
         <div class="col-md-8">
        <!--   <input type="text" name="booking_date" id="bookingdate" class="datepicker m_input_default" value="<?php echo $form_data['booking_date']; ?>" /> -->
          <input type="text" name="booking_time" id="bookingtime" class="datepicker3 m_input_default" value="<?php echo $form_data['booking_time']; ?>" />
        </div>
      </div> 
      <!-- 21-04-2020 -->
      <div class="row mb-2">
       <label class="col-md-4">Distance Type <?php  if(!empty($field_list)){
        if($field_list[3]['mandatory_field_id']==74 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){?>         
         <span class="star">*</span>
         <?php 
       }
     } 
     ?></label>
       <div class="col-md-8" id="distance_type">
        <input type="radio" name="distance_type" value="0" <?php if($form_data['distance_type']==0){ echo 'checked="checked"'; } ?>> Local &nbsp;
        <input type="radio" name="distance_type" value="1" <?php if($form_data['distance_type']==1){ echo 'checked="checked"'; } ?>> Outstation
          <?php if(!empty($field_list)){
         if($field_list[3]['mandatory_field_id']==74 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
         if(!empty($form_error)){ echo form_error('distance_type'); }}} ?>
      </div>
    </div>
    <!-- 21-04-2020 -->
     <div class="row mb-2">
                           <label class="col-md-4">Location <?php if($field_list[0]['mandatory_field_id']==71 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?> <span class="text-danger">*</span><?php }?></label>
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
                               <?php if($field_list[0]['mandatory_field_id']==71 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){ if(!empty($form_error)){ echo form_error('location'); }} ?>
                           </div>
                        </div>
      <div class="row mb-2">
       <label class="col-md-4">Pick from <?php if($field_list[1]['mandatory_field_id']==72 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?><span class="text-danger">*</span><?php }?></label>
       <div class="col-md-8">
          <input type="text" name="source_from" value="<?php if(!empty($form_data['source_from'])){ echo $form_data['source_from'];} ?>">
        <?php if($field_list[1]['mandatory_field_id']==72 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){ if(!empty($form_error)){ echo form_error('source_from'); } }?>
      </div>
    </div>
    <div class="row mb-2">
     <label class="col-md-4">Drop to <?php if($field_list[2]['mandatory_field_id']==73 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?><span class="text-danger">*</span><?php } ?></label>
     <div class="col-md-8">
      <input type="text" name="destination" value="<?php if(!empty($form_data['destination'])){ echo $form_data['destination'];} ?>">
      <?php if($field_list[2]['mandatory_field_id']==73 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){if(!empty($form_error)){ echo form_error('destination'); }} ?>
    </div>
  </div>
  
 <!--  <div class="row mb-2">
       <label class="col-md-4">Distance(in km)<?php if(!empty($field_list)){
        if($field_list[4]['mandatory_field_id']==75 && $field_list[4]['mandatory_branch_id']==$users_data['parent_id']){?>  <span class="text-danger">*</span><?php } }?></label>
       <div class="col-md-8">
          <input type="text" name="distance" id="distance" value="<?php if(!empty($form_data['distance'])){ echo $form_data['distance'];} ?>"  onkeypress="return numericvalue(event);">
        <?php if(!empty($field_list)){
        if($field_list[4]['mandatory_field_id']==75 && $field_list[4]['mandatory_branch_id']==$users_data['parent_id']){ if(!empty($form_error)){ echo form_error('distance'); }}} ?>
      </div>
  </div>-->
  <input type="hidden" name="distance" id="distance">
  <div class="row mb-2">
   <label class="col-md-4">Remarks</label>
   <div class="col-md-8">
    <textarea name="remark" id="remark"><?php echo $form_data['remarks'];?></textarea>
  </div>
</div>
<div class="row mb-2">
 <label class="col-md-4"></label>
 <div class="col-md-8">
  <button class="btn-save" id="btnsubmit"><i class="fa fa-floppy-o"></i> Save</button>
  <a href="<?php echo base_url('ambulance/enquiry');?>"><button class="btn-update" type="button"><i class="fa fa-sign-out"></i> Exit </button></a>
</div>
</div>

</div>
<div class="col-sm-4">   


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
    
    function more_patient_info()
 {
     
     var txt = $(".more_content").is(':visible') ? 'More Info' : 'Less Info';
        $(".show_hide_more").text(txt);
     
   $("#patient_info").slideToggle();
 }
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
          var total_amount = consultants_charge;
          var discount = $('#discount').val();
          var balance = total_amount-discount;

        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>ambulance/booking/get_charge/", 
          dataType: "json",
          data: 'vehicle_id='+vehicle_id+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance,
          success: function(result)
          {
            $("#consultants_charge").val(result.consultants_charge);
            
            $("#net_amount").val(result.net_amount);
            $("#paid_amount").val(result.net_amount);
               // $("#total_amount").val(result.total_amount); 

               
               
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
      /* 21-04-2020 */
     /* function get_distance_charge(val)
      {
        if(val!='')
       {
        
          var distance = $('#distance').val();
          var distance_type = $('#distance_type').val();
          var vehicle_id = $('#vehicle_no').val();
          var consultants_charge = $('#consultants_charge').val();
          var total_amount = consultants_charge;
          var discount = $('#discount').val();
          var balance = total_amount-discount;
          
        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>ambulance/booking/get_distance_charge/", 
          dataType: "json",
          data: 'vehicle_id='+vehicle_id+'&distance_type='+distance_type+'&distance='+distance+'&discount='+discount+'&balance='+balance,
          success: function(result)
          {
            $("#consultants_charge").val(result.consultants_charge);
            
            $("#net_amount").val(result.net_amount);
            $("#paid_amount").val(result.net_amount);
               // $("#total_amount").val(result.total_amount); 
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
    }*/
    /* 21-04-2020 */
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
          $(document).ready(function() {
         var data_id=$('#data_id').val();
         if(data_id!="")
         {
            $( "#more" ).removeClass( "collapse" );
         }
         else{
          
         }
      });

    
      $(document).ready(function(){
$('.datepicker_dob').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    //startView: 2
  })


        $('.datepicker').datepicker({
          format: "dd-mm-yyyy",
          autoclose: true
        }); 

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

        var kit_amount = $('#kit_amount').val();
        var consultants_charge = val;
        var total_amount = consultants_charge+kit_amount;
        var discount = $('#discount').val();
        var balance = total_amount-discount;


        $.ajax({
          type: "POST",
          url: "<?php echo base_url(); ?>ambulance/booking/update_amount/", 
          dataType: "json",
          data: 'kit_amount='+kit_amount+'&consultants_charge='+consultants_charge+'&discount='+discount+'&balance='+balance,
          success: function(result)
          {
            $("#consultants_charge").val(result.consultants_charge);
            $("#kit_amount").val(result.kit_amount);
            $("#net_amount").val(result.net_amount);
            $("#paid_amount").val(result.net_amount);
               // $("#total_amount").val(result.total_amount); 

               
               
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

       // var kit_amount = $('#kit_amount').val();
        var consultants_charge =$('#consultants_charge').val();
        var discount = $('#discount').val();
        var paid_amount = val;
       var total_amount=consultants_charge-discount;
        var net_amount= $('#net_amount').val();
       // alert(paid_amount);
        var balance = net_amount-paid_amount;

       $('#balance').val(balance);

        
      }
      else
      {
        
        $("#balance").val('0.00');
     }
   }


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
      alert('Discount amount can not be greater than net amount');
      $('#discount').val('0.00');
      return false;
    }
    if(parseInt(paid_amount)>parseInt(net_amount))
    {
      alert('Paid amount can not be greater than net amount');
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
    var kit_amount = $('#kit_amount').val();
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();

    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>ambulance/booking/calculate_payment/", 
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
$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });

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

 function get_city(state_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#citys_id').html(result); 
      } 
    }); 
  }
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
         <!--<a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>-->
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
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ambulance/booking/print_booking_report"); ?>');">Print</a>
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
</div>
</body>
</html>