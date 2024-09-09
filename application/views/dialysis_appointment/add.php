<?php
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
  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

  <!-- datatable js -->
  <script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
  <script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>



  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
  <script type="text/javascript">
    var save_method; 
    var table;
    <?php
    if(in_array('529',$users_data['permission']['action'])) 
    {
      ?>
      $(document).ready(function() { 
        table = $('#table').DataTable({  
          "processing": true, 
          "serverSide": true, 
          "aaSorting": [],
          "pageLength": '20',
          "ajax": {
            "url": "<?php echo base_url('ot_schedule_list/ajax_list')?>",
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
      <?php } ?>


      function reload_table()
      {
    table.ajax.reload(null,false); //reload datatable ajax 
  }
</script>
</head>
<body onLoad="set_tpa(<?php echo $form_data['pannel_type']; ?>)">
  <div class="container-fluid">
   <?php
   $this->load->view('include/header');
   $this->load->view('include/inner_header'); 
   ?>
   <!-- ============================= Main content start here ===================================== -->
   <section class="userlist">
    <div class="userlist-box media_tbl_full">

      <form action="<?php echo current_url(); ?>" method="post" id="dialysis_form">
        <input type="hidden" value="<?php echo $form_data['data_id'] ?>" name="data_id"/>
        <div class="row">
          <div class="col-md-12">

            <div class="row">
             <div class="row m-b-5">
              <div class="col-md-12">
                <?php 
                $checked_reg=''; 
                $checked_ipd='';
                $checked_nor='checked';
                if(isset($_GET['reg']) && $_GET['reg']!='') {
                  $checked_reg="checked";
                  $checked_nor='';
                }?>
              
               <?php if(isset($form_data['reg_patient']) && $form_data['reg_patient']!='' && $form_data['reg_patient']!=0)
               {
                $checked_reg="checked";
                $checked_nor='';
              }  
              ?>
              <input type="hidden" value="<?php echo $form_data['reg_patient'];?>" id="" name="reg_patient"/>
              <span class="new_vendor"><input type="radio" name="" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('ot_booking/');?>add/';"> <label>New Patient</label></span> &nbsp;
              <span class="new_vendor"><input type="radio" name="" <?php echo $checked_reg; ?> onClick="window.location='<?php echo base_url('patient');?>';"> <label>Registered Patient</label></span> &nbsp;
             
            </div>
          </div> <!-- innerrow -->
          <div class="col-sm-4">
            <div class="row m-b-5">
              <div class="col-xs-5">
                <label>Appointment No. <sapn class="star">*</sapn></label>
              </div>
              <div class="col-xs-7">
               <input type="hidden" value="<?php echo $form_data['dialysis_appointment_code'];?>" name="dialysis_appointment_code" />
               <?php if(!empty($form_data['dialysis_appointment_code']))
               {
                ?>
                <div class="fright"><b><?php echo $form_data['dialysis_appointment_code'];?></b></div>
                <?php }
                else
                {
                  ?>
                  <div class="fright"><b>textRegisterID</b></div>
                  <?php } ?>


                </div>
              </div>

              <div class="row m-b-5">
                <div class="col-xs-5">
                  <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?><sapn class="star">*</sapn></label>
                </div>
                <div class="col-xs-7">
                 <input type="hidden" value="<?php echo $form_data['patient_code'];?>" name="patient_reg_code" />
                 <?php if(!empty($form_data['patient_code']))
                 {
                  ?>
                  <div class="fright"><b><?php echo $form_data['patient_code'];?></b></div>
                  <?php }
                  else
                  {
                    ?>
                    <div class="fright"><b>textRegisterID</b></div>
                    <?php } ?>


                  </div>
                </div>

               
                    <input type="hidden" name="ipd_no" value="0"/>
                    <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
                   
                    <div class="row m-b-5">
                      <div class="col-xs-5">
                        <label>patient name <sapn class="star">*</sapn></label>
                      </div>
                      <div class="col-xs-7">
                        <input type="hidden" value="<?php echo $form_data['simulation_id']; ?>" name="simulation_id" />
                        <select class="mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                          <?php foreach($simulation_list as $simulation){?>
                          <option value="<?php echo $simulation->id; ?>" <?php if($form_data['simulation_id']==$simulation->id){ echo 'selected';}?>><?php echo $simulation->simulation;?></option>
                          <?php }
                          ?>

                        </select>
                        <input type="text" name="name" id="name"  class="mr-name alpha_numeric_space txt_firstCap" value="<?php echo $form_data['name'];?>" autofocus="">
                        <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                        <?php if(!empty($form_error)){ echo form_error('name'); } ?>



                      </div>
                    </div>

                    <!-- new code by mamta -->
                    <div class="row m-b-5">
                      <div class="col-xs-5">
                        <strong> 
                          <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
                            <?php foreach($gardian_relation_list as $gardian_list) 
                            {?>
                            <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
                            <?php }?>
                          </select>

                        </strong>
                      </div>
                      <div class="col-xs-7">
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
                    <div class="col-xs-5">
                      <label>mobile no.</label>
                    </div>
                    <div class="col-xs-7">
                      <input type="text" name="" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91" style="width:59px;"> 
                      <input type="text" name="mobile_no" class="number" id="mobile_no" maxlength="10" value="<?php echo $form_data['mobile_no'];?>" onKeyPress="return isNumberKey(event);">
                      <div class="f_right">
                        <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                      </div>
                    </div>
                  </div>

                  <div class="row m-b-5">
                    <div class="col-xs-5">
                     <label>Gender <span class="star">*</span></label>
                   </div>
                   <div class="col-xs-7">
                    <input type="hidden" value="<?php echo $form_data['gender']; ?>" name="gender"/>
                    <label><input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> > Male</label> &nbsp;
                    <label><input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female</label>
                    <label><input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?> > Others</label>
                    <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                  </div>
                </div>

                <div class="row m-b-5">
                  <div class="col-xs-5">
                   <label>Age <sapn class="star">*</sapn></label>
                 </div>
                 <div class="col-xs-7">
                   <div class="fright">
                    <input type="text" name="age_y" class="input-tiny" value="<?php echo $form_data['age_y']; ?>"> Y
                    <input type="text" name="age_m" class="input-tiny" value="<?php echo $form_data['age_m']; ?>"> M
                    <input type="text" name="age_d" class="input-tiny" value="<?php echo $form_data['age_d']; ?>"> D
                  </div>
                  <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                </div>
              </div>



 <!--  <div class="row m-b-5">
                  <div class="col-xs-5"></div>
      
         
                 <div class="col-xs-12" style="text-align: center;">
                   <div class="fright">
            <a href="javascript:void(0);" onclick="more_patient_info()">More Info</a>
        </div>
      
    </div>
  </div> <!-- row -->

<div id="patient_info">  
  <div class="row m-b-5">
        <div class="col-xs-5">
          <label><b>Email Id </b></div>
                    <div class="col-xs-7">
           <input type="text" name="patient_email" id="patient_email" class="email_address m_input_default" value="<?php echo $form_data['patient_email']; ?>" >
              
         <div class="f_right">
            <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
          </div>
        </div>
      </div>

   <div class="row m-b-5">
        <div class="col-xs-5">
          <label><b>Address1</b></div>
                    <div class="col-xs-7">
          <input type="text" name="address" id="address" class="address" maxlength="255" value="<?php echo $form_data['address']; ?>"/>
         </div>
     
    </div>
  
    <div class="row m-b-5">
            <div class="col-xs-5">
              <label><b>Address2</b></div>
                    <div class="col-xs-7">
           <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php echo $form_data['address_second']; ?>"/>
         
        </div>
      </div>
    <div class="row m-b-5">
                    <div class="col-xs-5">
                      <label><b>Address3</b></div>
                    <div class="col-xs-7">
           <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php echo $form_data['address_third']; ?>"/>
       
        </div>
      </div>

<div class="row m-b-5">
                    <div class="col-xs-5">
                      <label><b>Aadhaar No.</b></div>
                    <div class="col-xs-7">
           <input type="text" name="adhar_no" class="m_input_default numeric" id="adhar_no" value="<?php if(!empty($form_data['adhar_no'])){ echo $form_data['adhar_no']; } ?>"/>
            
         <div class="f_right">
            <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
          </div>
        </div>
      </div>

  <div class="row m-b-5">
                    <div class="col-xs-5">
                      <label><b>Country</b></div>
                    <div class="col-xs-7">
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
      </div>
  

  <div class="row m-b-5">
                    <div class="col-xs-5">
                      <label><b>State</b></div>
                    <div class="col-xs-7">
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
      </div>
  

  <div class="row m-b-5">
                    <div class="col-xs-5">
                      <label><b>City</b></div>
                    <div class="col-xs-7">
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
              
           
          






      


</div> <!-- 4 -->



<div class="col-sm-4">

 <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Specialization
         <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==41 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
            ?>
         </b></div>
         <div class="col-md-7" id="specilizationid">
        
           <select name="specialization" class="m_select_btn" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
              <option value="">Select Specialization</option>
              <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specializationlist)
                {
                  ?>
                    <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization;  ?></option>
                  <?php
                }
              }
              ?>
            </select> 
             
            <?php if(!empty($form_error)){ echo form_error('specialization'); } 
                       
            ?> 
         </div>

       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Consultant  </b></div>
         <div class="col-md-7"> <!-- attended_doctor change for new doctor add -->
           <select name="attended_doctor" class="m_select_btn" id="attended_doctor">
              <option value="">Select Attended By</option>
              <?php
             if(!empty($form_data['specialization_id']))
             {
               
                $doctor_list = doctor_specilization_list($form_data['specialization_id'],$form_data['branch_id']); 
                
                if(!empty($doctor_list))
                {
                   foreach($doctor_list as $doctor)
                   {  
                        
                    ?>   
                      <option value="<?php echo $doctor->id; ?>" < <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                     
                   }
                }
             }
            ?>
            </select>
<?php if(!empty($form_error)){ echo form_error('attended_doctor'); } ?> 
         </div>
       </div>
    </div>
  </div> <!-- row -->    
    

<div class="row m-b-5" id="dialysis_name">
  <div class="col-xs-4">
   <label>Dialysis Name </label>
 </div>

 <div class="col-xs-8">
  <select name="dialysis_name" class="w-145px" id="dialysis_name_id">
    <option value="">Select Dialysis</option>
    <?php foreach($dialysisn_list as $dy_list)
    {?>
    <option value="<?php echo $dy_list->id;?>" <?php if(isset($form_data['dialysis_name']) && $form_data['dialysis_name']== $dy_list->id){echo 'selected';}?>><?php echo $dy_list->name;?></option>
    <?php }?>

  </select>

  <a title="Add Dialysis" class="btn-new" onclick="add_dialysis_name();"><i class="fa fa-plus"></i> New</a>
  <?php if(!empty($form_error)){ echo form_error('dialysis_name'); } ?>
</div>

</div>


    <div class="row m-b-5">
    
         <div class="col-md-4"><b>Appointment Date <span class="star">*</span></b></div>
         <div class="col-md-8">
           <input type="text" id="dialysis_date" name="dialysis_date" class="datepicker_app" value="<?php echo $form_data['dialysis_date']; ?>" onchange="return get_available_days('',this.value);" />
            <?php if(!empty($form_error)){ echo form_error('dialysis_date'); } ?>
         </div>
       </div>
       
    <div class="row m-b-5">
      
           <div class="col-xs-4"><b>Schedule </b><span class="star">*</span></div>
         <div class="col-xs-8">
           <select name="schedule_id" class="m_select_btn" id="schedule_id" onchange="return get_available_days('',this.value);">
              <option value="">Select Schedule</option>
              <?php
                 $schedule_list = dialysis_schedule_list($users_data['parent_id']); 
                
                if(!empty($schedule_list))
                {
                   foreach($schedule_list as $doctor)
                   {  
                        
                    ?>   
                      <option value="<?php echo $doctor->id; ?>" < <?php if(!empty($form_data['schedule_id']) && $form_data['schedule_id'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->schedule_name; ?></option>
                    <?php
                     
                   }
                }
             
            ?>
            </select>
            <?php if(!empty($form_error)){ echo form_error('schedule_id'); } ?> 
         </div>
       </div>

  <!-- row -->
  

 <div class="row m-b-5" id="available_time" <?php if(empty($form_data['available_time'])){ ?> style="display: none;" <?php } ?> >
   
         <div class="col-md-4"><b>Available Time</b><span class="star">*</span></div>
         <div class="col-md-8">
            <select name="available_time" class="m_select_btn" id="doctor_time" onchange="return select_a_slot(this.value);">
              <option value="">Select time</option>
              <?php
                if(!empty($schedule_available_time))
                {
                    foreach($schedule_available_time as $doctor_av_time)
                    { //date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time))
                        ?> 
                        <option <?php if($form_data['available_time']==$doctor_av_time->id){ echo 'selected="selected"';} ?> value="<?php echo $doctor_av_time->id; ?>"> <?php echo date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time)); ?> </option>
                        
                   <?php  }
                } 

              ?>
            </select>
<?php if(!empty($form_error)){ echo form_error('available_time'); } ?>
  </div>
  </div>
     <!-- row -->

  <div class="row m-b-5" id="available_slot"  <?php if(empty($form_data['doctor_slot'])){ ?> style="display: none;" <?php } ?> >
      
         <div class="col-md-4"><b>Available Slot</b><span class="star">*</span></div>
         <div class="col-md-8">
            <select name="doctor_slot" class="m_select_btn" id="doctor_slot">
              <!-- <option value="">Select time</option> -->
              <?php echo $schedule_available_slot; ?>
            </select>
<?php if(!empty($form_error)){ echo form_error('doctor_slot'); } ?>
  </div>
  </div>
     <!-- row -->

  
  <div class="row m-b-5" id="doctor_avalaible" style="display: none;">
     
         <div class="col-md-4"><b>Available Time</b></div>
         <div class="col-md-8">
         <span id="doctor_not_avalaible"></span>

  </div>
  </div>
    
    <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>

            <div class="row m-b-5">
              <div class="col-md-12">
               <div class="row">
                 <div class="col-md-4"><b>Referred By</b></div>
                 <div class="col-md-8" id="referred_by">
                   <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
                   <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
                   <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
                 </div>
               </div>
             </div>
           </div> <!-- row -->

           <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-sm-4"><label>Referred by Doctor</label></div>
            <div class="col-sm-8">
              <select name="referral_doctor" class="m_input_default" id="refered_id" onChange="return get_others(this.value)">
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

                  ?>

                  <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
                  <?php
                }
                ?>
              </select> 

              <?php if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>
            </div>
          </div>

          <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>

           <div class="col-md-4"><b> Other </b></div>
           <div class="col-xs-8">
            <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
            <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
          </div>
        </div>
        <!-- row -->






        <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>

         <div class="col-md-4"><b>Referred By Hospital</b></div>
         <div class="col-sm-8">
           <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
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
          <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
        </div>

      </div> <!-- row -->
      <?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){ 

        ?>
        <div class="row m-b-5">
          <div class="col-sm-4"><label>Referred By Doctor<span class="star">*</span></label></div>
          <div class="col-sm-8">
            <select name="referral_doctor" class="m_input_default" id="refered_id" onChange="return get_others(this.value)">
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

                ?>

                <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
                <?php
              }
              ?>
            </select> 

            <?php if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>
          </div>
        </div>

        <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>

         <div class="col-md-4"><b> Other </b></div>
         <div class="col-xs-8">
          <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
          <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
        </div>
      </div>
      <!-- row -->
      <input type="hidden" name="referred_by" value="0">
      <input type="hidden" name="referral_hospital" value="0">
      <?php
    }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

      ?>
      <div class="row m-b-5">

       <div class="col-md-4"><b>Referred by Hospital</b></div>
       <div class="col-sm-8">
         <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
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
        <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
      </div>
      
    </div> <!-- row -->

    <input type="hidden" name="referred_by" value="1">
    <input type="hidden" name="referral_doctor" value="0">  <?php 

  }

  ?>

    <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Remarks <sapn class="star">*</sapn></label>
             </div>
             <div class="col-xs-8">
               <textarea  name="remarks" id="remarks_id"><?php echo $form_data['remarks'];?></textarea>
               
               <?php if(!empty($form_error)){ echo form_error('remarks'); } ?>
             </div>
           </div>
           
           
           
           
           
           <!-- issurance -->
           
           <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Panel Type</label>
             </div>
             <div class="col-xs-8" id="pannel_type">
           <input type="radio" name="pannel_type" value="0" onclick="set_tpa(0);" <?php if($form_data['pannel_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
            <input type="radio" name="pannel_type" value="1" onclick="set_tpa(1);" <?php if($form_data['pannel_type']==1){ echo 'checked="checked"'; } ?>> Panel
            <?php if(!empty($form_error)){ echo form_error('pannel_type'); } ?>
         </div>
           </div>
<div id="panel_box">
        <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Type</label>
             </div>
             <div class="col-xs-8" id="paneltypeid">
        
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
            <a  class="btn-new" onclick="insurance_type_modal()"  id="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>

             

            <?php } ?>
           </div>
           </div>
          
              


     <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Name</label>
             </div>
             <div class="col-xs-8" id="panelcompanyid">
       
                <select name="ins_company_id" id="ins_company_id" class="w-150px m_select_btn">
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
            <a  class="btn-new" id="insurance_company_modal" onclick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
            </div>
           </div>
          
      
   <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Policy No.</label>
             </div>
             <div class="col-xs-8">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
        </div>
           </div>
      
   <div class="row m-b-5">
              <div class="col-xs-4">
               <label>TPA ID</label>
             </div>
             <div class="col-xs-8">
            <input type="text" name="tpa_id" class="alpha_numeric" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
        </div>
           </div>
      
   <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Insurance Amount</label>
             </div>
             <div class="col-xs-8">
            <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onKeyPress="return isNumberKey(event);" />
        </div>
           </div>
      
   <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Authorization No.</label>
             </div>
             <div class="col-xs-8">
            <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
        </div>
           </div>
</div>  
           
           <!-- end insurance -->
           
           
           
           
           
           
           
           
           
           

           <div class="row m-b-5">
            <div class="col-xs-4">
             <!-- <label>Remarks <span class="star">*</span></label> -->
           </div>
           <div class="col-xs-8">
             <button class="btn-update" type="submit" id="btnsubmit"> <i class="fa fa-floppy-o"></i> Submit</button>
             <a class="btn-anchor" href="<?php echo base_url('dialysis_appointment');?>"> <i class="fa fa-sign-out"></i> Exit</a>
           </div>
         </div>
         
       </div> <!-- 4 -->



       <div class="col-sm-4"></div> <!-- 4 -->

     </div> <!-- inner row -->

   </div>
 </div>

</form>





</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<!--new css-->

<!--new css-->

<script>
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
 function more_patient_info()
 {
   $("#patient_info").slideToggle();
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
   
 $('.datepicker_app').datepicker({ 
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });
function get_available_days(schedule_id,date)
  {
      if(date!='')
      {
        var schedule_id = $('#schedule_id').val();
      }
      if(schedule_id!='')
      {
        var booking_date = $('#dialysis_date').val();
      }
      
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dialysis_appointment/get_schedule_available_days/", 
            dataType: "json",
            data: 'schedule_id='+schedule_id+'&booking_date='+booking_date,
            success: function(result)
            {
               
               if(result==1)
               {
                    
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>general/get_schedule_available_time/",
                      data: 'schedule_id='+schedule_id+'&booking_date='+booking_date, 
                      success: function(result)
                      {
                        $("#appointmenttime").val('');
                        $("#available_time").css("display", "block");
                        $("#doctor_avalaible").css("display", "none");
                        $("#dialysis_time").css("display","none");
                        $('#doctor_time').html(result); 
                        
                      } 
                    });
                   
                    
               }
               else if(result==0)
               {
                    $("#appointmenttime").val('');
                    $("#dialysis_time").css("display","none");
                    $("#available_time").css("display", "none");
                    $("#available_slot").css("display", "none");
                    $("#doctor_avalaible").css("display", "block");
                    
                    


                    $('#doctor_not_avalaible').html('<p style="color:red;">Schedule not available.</p>'); 
                }
                else if(result==2)
                {
                  $("#doctor_avalaible").css("display", "none");
                  $("#available_time").css("display", "none");
                  $("#available_slot").css("display", "none");
                  $("#dialysis_time").css("display","block");
                  //available_time
                  
                } 

            }

          });
  }


  function select_a_slot(vals)
  {      
        var time_id = vals;
        var schedule_id = $('#schedule_id').val();
        var booking_date = $('#dialysis_date').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>general/schedule_slot/", 
            data: 'schedule_id='+schedule_id+'&time_id='+time_id+'&booking_date='+booking_date,
            success: function(result)
            {
                
                $("#available_slot").css("display", "block");
                $('#doctor_slot').html(result); 
                //$('#doctor_slot').html(result); 
            }



          });
  }

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
   $("#relation_name").css("display","block");
 }

 $(function() {
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
});

 $(document).ready(function() {
  $("input[name$='dialysis_type']").click(function() 
  {

    var test = $(this).val();

    if(test==1)
    {
      $("#package_name").hide();
        //$("#package_name").html('');
        $("#dialysis_name").show();
        
        
      }
      else if(test==2)
      {
        // $("#op_name").hide();
        $("#dialysis_name").css("display","none"); 
          //$("#op_name").html('');
          $("#package_name").show();

          //$("#refered_id :selected").val('');
        }
        
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


 function add_doctor_list(){
  var rowCount = $('#doctor_list tr').length;

  var doc= $('#doctor_name').val();
  var doctor_id= $('#doctor_id').val();

  $.ajax({
    url : "<?php echo base_url('dialysis_appointment/append_doctor_list/'); ?>",
    method: 'post',
    data: {name : doc ,rowCount:rowCount,doctor_id:doctor_id},
    success: function( data ) {

     $('#append_doctor_list').append(data);
   }
 });

}
$(document).ready(function(){

 form_submit();
});

$(function () {

  var i=1;
  var getData = function (request, response) { 
    row = i ;
    $.ajax({
      url : "<?php echo base_url('dialysis_appointment/get_doctor_name/'); ?>" + request.term,
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


  var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

        $('.doctor_name').val(names[0]);
        $('.doctor_id').val(names[1]);


        return false;
      }


      $(".doctor_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
          }
        });
    });



$('.start_datepicker').datepicker({
  format: 'dd-mm-yyyy', 
  autoclose: true, 
  endDate : new Date(), 
}).on("change", function(selectedDate) 
{ 
  var start_data = $('.start_datepicker').val();
  $('.end_datepicker').datepicker('setStartDate', start_data); 
  form_submit();
});

$('.end_datepicker').datepicker({
  format: 'dd-mm-yyyy',     
  autoclose: true,  
}).on("change", function(selectedDate) 
{   
 form_submit();
});

function form_submit()
{
  $('#search_form').delay(200).submit();
}

$('.datepicker').datepicker({
  format: 'dd-mm-yyyy', 
  autoclose: true 
});

$('.datepicker3').datetimepicker({
 format: 'LT'
});

<?php
$flash_success = $this->session->flashdata('success');
if(isset($flash_success) && !empty($flash_success))
{
 echo 'flash_session_msg("'.$flash_success.'");';
}
?>

function add_package()
{
  var $modal = $('#load_add_dialysis_pacakge_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_pacakge/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
  },
  function(){
    $modal.modal('show');
  });
}

function add_dialysis_name()
{
  var $modal = $('#load_add_dialysis_management_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_management/add/' ?>',
  {
      //'id1': '1',
      //'id2': '2'
    },
    function(){
      $modal.modal('show');
    });
}

function get_dialysis_pacakge()
{
 $.ajax({url: "<?php echo base_url(); ?>dialysis_appointment/dialysis_pacakage_dropdown/", 
  success: function(result)
  {

    $('#package_name_p').html(result); 

  } 
});
}

function get_dialysis_name()
{
 $.ajax({url: "<?php echo base_url(); ?>dialysis_appointment/dialysis_name_dropdown/", 
  success: function(result)
  {

    $('#dialysis_name_id').html(result); 

  } 
});
}
function add_remarks(){
  var $modal = $('#load_add_dialysis_remarks_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_remarks/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
  },
  function(){
    $modal.modal('show');
  });
}

function add_dialysis_room_no(){
  var $modal = $('#load_add_dialysis_room_master_modal_popup');
  $modal.load('<?php echo base_url().'dialysis_room/add' ?>',
  {
    //'id1': '1',
    //'id2': '2'
  },
  function(){
    $modal.modal('show');
  });
}



function toggle(source) 
{  
 checkboxes = document.getElementsByClassName('child_checkbox');
 for(var i=0, n=checkboxes.length;i<n;i++) {
  checkboxes[i].checked = source.checked;
}
}

function new_remark_field(remarks_value){
  $('#remarks_id').val(remarks_value);
}



function remove_row()
{
  jQuery('input:checkbox:checked').parents("tr").remove();
}
$(document).ready(function(){
  $('#load_add_ot_pacakge_modal_popup').on('shown.bs.modal', function(e){
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


</script>
<script type="text/javascript">
 $('documnet').ready(function(){
   <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
    $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
    })

    .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('dialysis_appointment/add');?>'; 
      }) ;


    <?php }?>
  });

 <?php
 $flash_error = $this->session->flashdata('error');
 if(isset($flash_error) && !empty($flash_error))
 {
   echo '<script> error_flash_session_msg("'.$flash_error.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
  </script> 
  <?php
}
?>
</script>
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>

      <div class="modal-footer">


        <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("dialysis_appointment/print_dialysis_appointment_report"); ?>');">Print</a>

        <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
      </div>
    </div>
  </div>  
</div> 

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>

<div id="load_add_dialysis_pacakge_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dialysis_management_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_dialysis_room_master_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
   $('#btnsubmit').on("click",function(){
    $(':input[id=btnsubmit]').prop('disabled', true);
       $('#dialysis_form').submit();

  })
</script>
