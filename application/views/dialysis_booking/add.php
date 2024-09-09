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
<body>
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
        
        <input type="hidden" value="<?php echo $form_data['appointment_id'] ?>" name="appointment_id"/>
        
        
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
                <?php if(isset($form_data['ipd_id']) && $form_data['ipd_id']!='' && $form_data['ipd_id']!=0)
                {
                 $checked_ipd="checked";
                 $checked_nor='';
               }  
               ?>

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
                <label>Booking No. <sapn class="star">*</sapn></label>
              </div>
              <div class="col-xs-7">
               <input type="hidden" value="<?php echo $form_data['dialysis_booking_code'];?>" name="dialysis_booking_code" />
               <?php if(!empty($form_data['dialysis_booking_code']))
               {
                ?>
                <div class="fright"><b><?php echo $form_data['dialysis_booking_code'];?></b></div>
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

                
                    <input type="hidden" name="ipd_no" value="<?php echo $form_data['ipd_no'];?>"/>
                    <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
                    <input type="hidden" name="ipd_id" value="<?php echo $form_data['ipd_id'];?>"/>
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
<!--- Show hide start -->             
              
               <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"></div>
         <div class="col-md-7">
            <a href="javascript:void(0);" class="show_hide_more" data-content="toggle-text" onclick="more_patient_info()">More Info</a>
            
            
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div class="more_content" id="patient_info" style="display: none;"> 
  
  <div class="row m-b-3">
                <div class="col-xs-5">
                 <label>DOB</label>
               </div>
               <div class="col-xs-7">
           <input type="text" class="datepicker_dob" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>"  /> <!-- onchange="showAge(this.value);"-->
     </div>
   </div>
   <div class="row m-b-3">
        <div class="col-xs-5">
         <label>Email Id </label>
       </div>
       <div class="col-xs-7">
   <input type="text" name="patient_email" class="email_address m_input_default" value="<?php echo $form_data['patient_email']; ?>" >
      <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
      </div>
    </div>
    

  <div class="row m-b-3">
                <div class="col-xs-5">
                 <label>Address 1</label>
               </div>
               <div class="col-xs-7">
                 <input type="text" name="address" id="" class="address" value="<?php echo $form_data['address'];?>"/>
                 <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
               </div>
             </div>

             <div class="row m-b-3">
              <div class="col-xs-5">
               <label>Address 2</label>
             </div>
             <div class="col-xs-7">
               <input type="text"  name="address_second" class="address" value="<?php echo $form_data['address_second'];?>"/>

             </div>
           </div>
           <div class="row m-b-3">
            <div class="col-xs-5">
             <label>Address 3</label>
           </div>
           <div class="col-xs-7">
             <input type="text" name="address_third" class="address" value="<?php echo $form_data['address_third'];?>"/>

           </div>
         </div>

 <div class="row m-b-3">
          <div class="col-xs-5">
           <label>Aadhaar No.</label>
         </div>
         <div class="col-xs-7">
           <input type="text" name="adhar_no"  class="numeric" value="<?php echo $form_data['adhar_no'];?>"/>
           <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
         </div>
       </div>


 <div class="row m-b-3">
              <div class="col-xs-5">
               <label>Country</label>
           </div>
           <div class="col-xs-7">
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

  

  <div class="row m-b-3">
              <div class="col-xs-5">
               <label>State</label>
           </div>
           <div class="col-xs-7">
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

  

  <div class="row m-b-3">
              <div class="col-xs-5">
               <label>City</label>
           </div>
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
              
              
              
              



<!--- Show Hide ends --->


</div> <!-- 4 -->



<div class="col-sm-4">
    <input type="hidden" name="dialysis_room" value="0">
<div class="row m-b-5">
    <div class="col-xs-4">
     <label> Room Type <sapn class="star">*</sapn></label></div>

   <div class="col-xs-8">
                <select name="room_id" class="m_input_default" value="room_id" onchange="room_no_select(this.value);" id="room_id">
                    <option value="">-Select-</option>
                    <?php foreach($room_type_list as $room_type){?>
                    <option value="<?php echo $room_type->id; ?>" <?php if($form_data['room_id']==$room_type->id){ echo 'selected';}?>><?php echo $room_type->room_category; ?></option>
                    <?php }?>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('room_id'); } ?>
     </div>

</div>
        
<div class="row m-b-5">
    <div class="col-xs-4"><label>Room No. <sapn class="star">*</sapn> </label></div>

   <div class="col-xs-8">
            <select name="room_no_id" class="m_input_default" id="room_no_id" onchange="select_no_bed(this.value);">
                <option value="">-Select-</option>
            </select>
             <?php if(!empty($form_error)){ echo form_error('room_no_id'); } ?>
        </div>
    </div>
        
<div class="row m-b-5">
    <div class="col-xs-4"><label>Bed No. <sapn class="star">*</sapn></label></div>

   <div class="col-xs-8">
        <select name="bed_no_id" class="m_input_default" id="bed_no_id">
            <option value="">-Select-</option>
        </select>
         <?php if(!empty($form_error)){ echo form_error('bed_no_id'); } ?>
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



  <div class="row">
    <div class="col-xs-4">
     <label>doctor list</label>
   </div>
   <div class="col-xs-8">
     <input type="text" class=" m-b-5 doctor_name inputFocus" name="doctor_name" id="doctor_name" >
     <input type="hidden" class=" m-b-5 doctor_id inputFocus" name="doctor_id" id="doctor_id" >
     <div class="p-t-2px">
      <a title="Add Doctor" class="btn-new" onclick="add_doctor_list();">Add</a>
      <a title="Remove Doctor" class="btn-new" onclick="remove_row();">Delete</a>
    </div>
  </div>
</div>


<div class="row m-t-5 m-b-5">
  <div class="col-xs-12">
   <div class="row">
    <div class="col-sm-4"></div>
                 <!--  <div class="col-sm-7 ot_booking_delete">
                     <a class="btn-new">Delete</a>
                   </div> -->
                 </div>
                 <div class="ot_border">
                   <from id="deleteform">
                    <table class="table table-bordered table-striped ot_table" id="doctor_list">
                     <thead class="bg-theme">
                      <tr>
                       <th><input type="checkbox" name="" onClick="toggle(this);add_check();"></th>
                       <th>S.No.</th>
                       <th>Doctor Name</th>
                     </tr>
                   </thead>
                   <tbody id="append_doctor_list">
                     <?php $i=1;if(!empty($doctor_list)){

                      foreach($doctor_list as $key=>$value){
                        ?>

                        <tr><td><input type="checkbox" name="doctor_names[<?php echo $key?>][]" checked value="<?php echo $value[0]; ?>" class="child_checkbox"/><td><?php echo $i; ?></td><td><?php echo $value[0];?></td></tr>

                        <?php $i++;} }?>
                      </tbody>
                    </table>
                  </form>
                </div>         
              </div>
            </div>


            <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Remarks <span class="star">*</span></label> <br>
               <select  name="remarks_list" class="" style="width:125px" onchange="new_remark_field(this.value);">
                 <option value="">Select remarks</option>
                 <?php foreach($remarks_list as $remarks){?>
                 <option value="<?php echo $remarks->remarks; ?>"><?php echo $remarks->remarks; ?></option>
                 <?php }?>
               </select>
             </div>
             <div class="col-xs-8">
                 <textarea name="remarks"  class="m_input_default" id="remarks_id"  style="height: 72.5px;"><?php echo $form_data['remarks'];?></textarea>
               <!--<input type="text" class="w-140px" name="remarks" id="remarks_id" value="< ?php echo $form_data['remarks'];?>">-->
               
               <?php if(!empty($form_error)){ echo form_error('remarks'); } ?>
             </div>
           </div>

           
         
       </div> <!-- 4 -->



       <div class="col-sm-4">
           
          <!-- row 3 -->
          
          
          
          
<div class="row m-b-5">
  <div class="col-xs-4">
   <label>Dialysis name <span class="star">*</span></label>
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
  <div class="col-xs-4">
   <label>Dialysis Booking Date <span class="star">*</span></label>
 </div>
 <div class="col-xs-8">
   <input type="text" name="dialysis_booking_date" class="datepicker" placeholder="dd/mm/yyyy" value="<?php echo $form_data['dialysis_booking_date']; ?>">

              <!-- <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
                </select>-->
                <?php if(!empty($form_error)){ echo form_error('dialysis_booking_date'); } ?>
              </div>
            </div>


            <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Dialysis Date/Time <span class="star">*</span></label>
             </div>
             <div class="col-xs-8">
               <input type="text" name="dialysis_date" class="w-100px datepicker" placeholder="dd/mm/yyyy" value="<?php echo $form_data['dialysis_date']; ?>">
               <input type="text" name="dialysis_time" class="w-95px datepicker3" placeholder="" value="<?php echo $form_data['dialysis_time']; ?>">
              <!-- <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
                </select>-->
                <?php if(!empty($form_error)){ echo form_error('dialysis_date'); } ?>
              </div>
            </div>
            
            <div class="row m-b-5">
              <div class="col-xs-4">
               <label>No. of Visit Reqiuired <span class="star">*</span></label>
             </div>
             <div class="col-xs-8">
               <input type="text" name="no_of_visit" class="m_input_default" placeholder="No.of visit" value="<?php echo $form_data['no_of_visit']; ?>">
              <!-- <select class="m_input_default w-50px" name="no_pf_visit_duration">
                      <option value="">Please Select</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='1'){ echo 'selected="selected"';} ?> value="1">1</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='2'){ echo 'selected="selected"';} ?> value="2">2</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='3'){ echo 'selected="selected"';} ?> value="3">3</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='4'){ echo 'selected="selected"';} ?> value="4">4</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='5'){ echo 'selected="selected"';} ?> value="5">5</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='6'){ echo 'selected="selected"';} ?> value="6">6</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='7'){ echo 'selected="selected"';} ?> value="7">7</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='8'){ echo 'selected="selected"';} ?> value="8">8</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='9'){ echo 'selected="selected"';} ?> value="9">9</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='10'){ echo 'selected="selected"';} ?> value="10">10</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='11'){ echo 'selected="selected"';} ?> value="11">11</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='12'){ echo 'selected="selected"';} ?> value="12">12</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='13'){ echo 'selected="selected"';} ?> value="13">13</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='14'){ echo 'selected="selected"';} ?> value="14">14</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='15'){ echo 'selected="selected"';} ?> value="15">15</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='16'){ echo 'selected="selected"';} ?> value="16">16</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='17'){ echo 'selected="selected"';} ?> value="17">17</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='18'){ echo 'selected="selected"';} ?> value="18">18</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='19'){ echo 'selected="selected"';} ?> value="19">19</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='20'){ echo 'selected="selected"';} ?> value="20">20</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='21'){ echo 'selected="selected"';} ?> value="21">21</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='22'){ echo 'selected="selected"';} ?> value="22">22</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='23'){ echo 'selected="selected"';} ?> value="23">23</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='24'){ echo 'selected="selected"';} ?> value="24">24</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='25'){ echo 'selected="selected"';} ?> value="25">25</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='26'){ echo 'selected="selected"';} ?> value="26">26</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='27'){ echo 'selected="selected"';} ?> value="27">27</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='28'){ echo 'selected="selected"';} ?> value="28">28</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='29'){ echo 'selected="selected"';} ?> value="29">29</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='30'){ echo 'selected="selected"';} ?> value="30">30</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='31'){ echo 'selected="selected"';} ?> value="31">31</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='32'){ echo 'selected="selected"';} ?> value="32">32</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='33'){ echo 'selected="selected"';} ?> value="33">33</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='34'){ echo 'selected="selected"';} ?> value="34">34</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='35'){ echo 'selected="selected"';} ?> value="35">35</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='36'){ echo 'selected="selected"';} ?> value="36">36</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='37'){ echo 'selected="selected"';} ?> value="37">37</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='38'){ echo 'selected="selected"';} ?> value="38">38</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='39'){ echo 'selected="selected"';} ?> value="39">39</option>
                                             <option <?php if($form_data['no_pf_visit_duration']=='40'){ echo 'selected="selected"';} ?> value="40">40</option>
                                           
                   </select>
              <select class="m_input_default w-50px" name="no_pf_visit_unit">
                     <option  value="">Please Select</option>
                     <option <?php if($form_data['no_pf_visit_unit']=='Days'){ echo 'selected="selected"';} ?> value="Days">Days</option>
                     <option <?php if($form_data['no_pf_visit_unit']=='Weeks'){ echo 'selected="selected"';} ?> value="Weeks">Weeks</option>
                     <option <?php if($form_data['no_pf_visit_unit']=='Months'){ echo 'selected="selected"';} ?> value="Months">Months</option>
                     <option <?php if($form_data['no_pf_visit_unit']=='Years'){ echo 'selected="selected"';} ?> value="Years">Years</option>
                   </select>-->
                <?php if(!empty($form_error)){ echo form_error('no_pf_visit'); } ?>
              </div>
            </div>
            
            
            

            
          
          <div class="row m-b-5">
              <div class="col-md-12" id="dialysis_type">
               <span class="new_vendor"><input type="radio" name="dialysis_type" value="1" <?php if($form_data['dialysis_type']==1){echo 'checked';} ?> > <label>Normal</label></span> &nbsp;
               <span class="new_vendor"><input type="radio" name="dialysis_type" value="2" <?php if($form_data['dialysis_type']==2){echo 'checked';} ?> > <label>Package</label>
                <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>According to the central government health scheme (CGHS), package is defined as a lump sum cost of inpatient treatment for which a patient has been referred by a competent authority or CGHS to the hospital or diagnostic center..</span></a></sup>
              </span> &nbsp;
            
            </div>
            </div>

          <div id="package_list_name">
          <div class="row">
            <div class="col-xs-4">
             <label>Package List</label>
           </div>
           <div class="col-xs-8">
             <input type="text" class=" m-b-5 package_name inputFocus" name="package_name" id="package_name" >
             <input type="hidden" class=" m-b-5 package_id inputFocus" name="package_id" id="package_id" >
              <input type="hidden" class=" m-b-5 p_amount inputFocus" name="p_amount" id="p_amount" >
             
             
             <div class="p-t-2px">
              <a title="Add Package" class="btn-new" onclick="add_package_list();">Add</a>
              <a title="Remove Package" class="btn-new" onclick="remove_package_row();">Delete</a>
            </div>
          </div>
        </div>
        
        
        <div class="row m-t-5 m-b-5">
          <div class="col-xs-12">
           <div class="row">
            <div class="col-sm-4"></div>
                 
                 </div>
                 <div class="ot_border">
                   <from id="deleteform">
                    <table class="table table-bordered table-striped ot_table" id="package_list">
                     <thead class="bg-theme">
                      <tr>
                       <th><input type="checkbox" name="" onClick="togglepackage(this);add_package_check();"></th>
                       <th>S.No.</th>
                       <th>Package Name</th>
                       <th>Amount</th>
                     </tr>
                   </thead>
                   <tbody id="append_package_list">
                     <?php $i=1;if(!empty($package_list)){
                      foreach($package_list as $key=>$value)
                      {
                          $amount_result = get_dialysis_package_price($key);
                          $p_charge =  $amount_result[0]->amount;
                        ?>

                        <tr><td><input type="checkbox" name="package_names[<?php echo $key?>][]" checked value="<?php echo $value[0]; ?>" class="child_checkbox"/><td><?php echo $i; ?></td><td><?php echo $value[0];?></td><td><?php echo $p_charge;?></td></tr>

                        <?php $i++;} }?>
                      </tbody>
                    </table>
                  </form>
                </div>         
              </div>
            </div>
            
            </div>
            
            
            <div class="row m-b-5">
              <div class="col-xs-4">
               <label>Advance Deposit</label>
             </div>
             <div class="col-xs-8">
               <input type="text" name="advance_deposite" class="price_float m_input_default" placeholder="Adavcnce Deposit" value="<?php echo $form_data['advance_deposite']; ?>">
              </div>
            </div>
           <div class="row m-b-5">
              <div class="col-xs-4"><label>Mode of Payment </label>
             </div>
             <div class="col-xs-8">
               <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');"><option value="">Select Payment Mode</option>
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                       
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
            </div>
        </div>
               
               
            
            <div class="row m-b-5">
            <div class="col-xs-4">
             <!-- <label>Remarks <span class="star">*</span></label> -->
           </div>
           <div class="col-xs-8">
             <button class="btn-update" type="submit" id="btnsubmit"> <i class="fa fa-floppy-o"></i> Submit</button>
             <a class="btn-anchor" href="<?php echo base_url('dialysis_booking');?>"> <i class="fa fa-sign-out"></i> Exit</a>
           </div>
         </div>
            
          <!---row 3 -->
           
       </div> <!-- 4 -->

     </div> <!-- inner row -->

   </div>
 </div>

</form>





</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>
 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('dialysis_booking/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
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
$(document).ready(function ()
{
    

$('.datepicker_dob').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    //startView: 2
  })
  
  
  <?php if(!empty($form_data['room_id'])) { ?>
    room_no_select('<?php echo $form_data['room_id'];?>','<?php echo $form_data['room_no_id'];?>');
  <?php } ?>
  <?php  if(!empty($form_data['room_no_id'])) { ?>
  select_no_bed('<?php echo $form_data['room_no_id'];?>','<?php echo $form_data['bed_no_id'];?>')

  <?php } ?>
});

function more_patient_info()
 {
     
     var txt = $(".more_content").is(':visible') ? 'More Info' : 'Less Info';
        $(".show_hide_more").text(txt);
     
   $("#patient_info").slideToggle();
 }
 
function room_no_select(value_room,room_no_id){
            $.ajax({
                url: "<?php echo base_url('dialysis_booking/select_room_number/'); ?>",
                type: "post",
                data: {room_id:value_room,room_no_id:room_no_id},
                success: function(result) 
                {
                  $('#room_no_id').html(result);
                }
            });
     }

     function select_no_bed(value_bed,bed_id){

        var room_id= $("#room_id option:selected").val();
        var ipd_id = $("#type_id").val();
        
        $.ajax({
                url: "<?php echo base_url('dialysis_booking/select_bed_no_number/'); ?>",
                type: "post",
                data: {room_id:room_id,room_no_id:value_bed,bed_id:bed_id,ipd_id:ipd_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
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


 $(document).ready(function() {
  $("input[name$='dialysis_type']").click(function() 
  {

    var test = $(this).val();

    if(test==1)
    {
      $("#package_list_name").hide();
    }
    else if(test==2)
    {
        $("#package_list_name").show();
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
    url : "<?php echo base_url('dialysis_booking/append_doctor_list/'); ?>",
    method: 'post',
    data: {name : doc ,rowCount:rowCount,doctor_id:doctor_id},
    success: function( data ) {

     $('#append_doctor_list').append(data);
   }
 });

}

 function add_package_list()
 {
  var rowCount = $('#package_list tr').length;
  var doc= $('#package_name').val();
  var package_id= $('#package_id').val();
  var amount= $('#p_amount').val();

  $.ajax({
    url : "<?php echo base_url('dialysis_booking/append_package_list/'); ?>",
    method: 'post',
    data: {name : doc ,rowCount:rowCount,package_id:package_id,amount:amount},
    success: function( data ) {

     $('#append_package_list').append(data);
   }
 });

}


$(function () {

  var i=1;
  var getData = function (request, response) { 
    row = i ;
    $.ajax({
      url : "<?php echo base_url('dialysis_booking/get_package_name/'); ?>" + request.term,
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

        $('.package_name').val(names[0]);
        $('.package_id').val(names[1]);
        $('.p_amount').val(names[2]);


        return false;
      }


      $(".package_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
          }
        });
    });



$(document).ready(function(){

 form_submit();
});

$(function () {

  var i=1;
  var getData = function (request, response) { 
    row = i ;
    $.ajax({
      url : "<?php echo base_url('dialysis_booking/get_doctor_name/'); ?>" + request.term,
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
 $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/dialysis_pacakage_dropdown/", 
  success: function(result)
  {

    $('#package_name_p').html(result); 

  } 
});
}

function get_dialysis_name()
{
 $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/dialysis_name_dropdown/", 
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

function togglepackage(source) 
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

function remove_package_row()
{
  jQuery('input:checkbox:checked').parents("tr").remove();
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
<script type="text/javascript">
 $('documnet').ready(function(){
   <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
    $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
    })

    .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('dialysis_booking/add');?>'; 
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


        <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("dialysis_booking/print_dialysis_booking_report"); ?>');">Print</a>

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
<script>
   $('#btnsubmit').on("click",function(){
    $(':input[id=btnsubmit]').prop('disabled', true);
       $('#dialysis_form').submit();

  })
</script>
