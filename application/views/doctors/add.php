<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(1);
?>
<?php //print_r($form_data['specialization_row']);die; ?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="emp_type" class="form-inline" enctype="multipart/form-data">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="old_sign_img" value="<?php echo $form_data['old_sign_img']; ?>">
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4>
                </div>
            <div class="modal-body" style="max-height:calc(100vh - 165px);overflow-y:auto;">

              <!-- // ====== From Here  -->
              <div class="row">
                <div class="col-xs-6">
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                         <strong>Doctor Reg. No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <div class="dcode"><b><?php echo $form_data['doctor_code']; ?></b></div>
                        <input type="hidden" name="doctor_code"  value="<?php echo $form_data['doctor_code']; ?>"/>
                      </div>
                    </div> <!-- row -->

                    

                   <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Doctor Name <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" name="doctor_name"   value="<?php echo $form_data['doctor_name'] ?>" class="txt_firstCapital alpha_space_dot_space inputFocus" autofocus> 
                        <?php if(!empty($form_error)){ echo form_error('doctor_name'); } ?>
                      </div>
                    </div> <!-- row -->

                    

                      <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Specialization <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="specilization_id" id="specilization_id" class="m10 specilization_id">
                          <option value=""> Select Specialization </option>
                          <?php
                          if(!empty($specialization_list))
                          {
                            foreach($specialization_list as $specialization)
                            {
                             ?>
                               <option value="<?php echo $specialization->id; ?>" <?php if($specialization->id==$form_data['specilization_id']){ echo 'selected="selected"'; } ?>><?php echo $specialization->specialization; ?></option>
                             <?php  
                            }
                          }
                          ?>
                        </select>
                        
                      
                      <?php if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                           <a title="Add Specialization" href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">
                                <i class="fa fa-plus"></i> New
                           </a>
                      <?php } ?>
                      <?php if(!empty($form_error)){ echo form_error('specilization_id'); } ?>
                      </div>
                    </div> <!-- row -->

                  

                     <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Mobile No.
                         <?php 
                         if(!empty($field_list)){
                if($field_list[0]['mandatory_field_id']=='3' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>
                                        <span class="star">*</span>
                                   <?php 
                                   } 
                              }
                         ?>
                        </strong>
                      </div>
                      <div class="col-xs-8">
                       <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                        <input type="text" maxlength="10" data-toggle="tooltip"  title="Allow only numeric." class="numeric number" name="mobile_no"   value="<?php echo $form_data['mobile_no'] ?>">
                      
                      <?php if(!empty($field_list)){
                                if($field_list[0]['mandatory_field_id']=='3' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                                    if(!empty($form_error)){ echo form_error('mobile_no'); }
                                } 
                            }
                        ?>
                      </div>
                    </div> <!-- row -->

                 


                   <!--  <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Marketing Person </strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="marketing_person_id" id="marketing_person_id">
                          <option value=""> Select Person </option>
                          <?php
                            if(!empty($person_list))
                            {
                              foreach($person_list as $person)
                              {
                                ?>
                                <option value="<?php echo $person->id; ?>" <?php if($person->id==$form_data['marketing_person_id']){ echo 'selected="selected"'; } ?>><?php echo $person->name; ?></option>
                                <?php
                              }
                            }
                           ?> 
                        </select>
                      </div>
                    </div>  --><!-- row -->
                    <?php 
                     if(in_array('224',$users_data['permission']['action'])) 
                     {
                    ?>
                         <div class="row m-b-5">
                              <div class="col-xs-4">
                                   <strong>Sharing Pattern <span class="star">*</span></strong>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Sharing pattern are of two type <br> Commision: Commission is a sum of money that is paid to an doctor upon completion of a task<br> Transaction:Basically this is used in Pathalogy it is type of doctor which paid before completion of any task. </span></a></sup>
                              </div>
                              <div class="col-xs-8">
                      
                                   <select name="doctor_pay_type" id="doctor_pay_type" onchange="get_comission(this.value);">
                                        <option value="">Select Sharing Pattern</option>
                        
                                        <option value="1" <?php if(1==$form_data['doctor_pay_type']){ echo 'selected="selected"'; } ?>>Commission</option>
                                        <?php if(in_array('145',$users_data['permission']['section'])){ ?>
                                        <option value="2" <?php if(2==$form_data['doctor_pay_type']){ echo 'selected="selected"'; } ?>>Transaction</option>
                                        <?php } ?>
                                   </select>
                                   <?php if(!empty($form_error)){ echo form_error('doctor_pay_type'); } ?>
                              </div>
                         </div> <!-- row -->
                         
                         


                         
                     <?php
                        if($form_data['doctor_pay_type']==2 && in_array('145',$users_data['permission']['action'])){ ?>
                         <div class="row m-b-5">
                     <div class="col-xs-4">
                        <strong id="share_lable">Rate list <span class="star">*</span></strong>
                     </div>
                     <div class="col-xs-8 " id="share_input">
                        <select name="rate_plan_id" id="rate_id" class="m1">
                           <option value="">Select Rate Plan</option>
                              <?php if(!empty($rate_list)){
                                 foreach($rate_list as $rate){
                                    $rate_select = "";
                                       if($rate->id == $form_data['rate_plan_id'])
                                 {
                                       $rate_select = 'selected="selected"';
                                    }
                                       echo '<option value="'.$rate->id.'" '.$rate_select.'>'.$rate->title.'</option>';
                                    }
                                 }
                              ?>
                        </select>  
                        
                        <a title="Add Rate" href="javascript:void(0)" onclick="rate_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                        
                         <?php if(!empty($form_error)){ echo form_error('rate_plan_id'); } ?>
                     </div>
                    </div> <!-- row -->
                     
                     <?php }else{ ?>
                 



                  <div class="row m-b-5">
                     <div class="col-xs-4" id="share_lable">
                        <strong>Share Details</strong>
                     </div>
                     <div class="col-xs-8" id="share_input">
                        <input type="hidden" name="doctor_commission_data" value="" />
                        <a href="javascript:void(0)" class="btn-commission" onclick="comission();"><i class="fa fa-cog"></i> Commission</a> 
                        <?php if(!empty($form_error)){ echo form_error('doctor_commission_data'); } ?>
                    
                      </div>

                  </div> <!-- row -->
                     <?php } ?> 
                     
                    

                    <?php }
                    else
                    {
                    ?>
                      <input name="doctor_pay_type" type="hidden" id="doctor_pay_type" value="0"/>
                    <?php  
                    } 
                    ?> 
                   
                      <div class="row m-b-5 m-t-5">
                      <div class="col-xs-4">
                         <strong>Doctor Type </strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="doctor_type">
                            <option value="0" <?php if($form_data['doctor_type']==0){ echo 'selected="selected"'; } ?>>Referral</option>
                            <option value="1" <?php if($form_data['doctor_type']==1){ echo 'selected="selected"'; } ?>>Attended</option>
                            <option value="2" <?php if($form_data['doctor_type']==2){ echo 'selected="selected"'; } ?>>Both</option>
                        </select>
                        <?php if(!empty($form_error)){ echo form_error('doctor_type'); } ?>
                      </div>
                    </div> <!-- row -->

                    
                    <div class="row m-b-5">
                     <div class="col-xs-4">
                        <strong>Marketing Person </strong>
                     </div>
                     <div class="col-xs-8">
                        <select name="marketing_person_id" id="marketing_person_id">
                           <option value=""> Select Person </option>
                           <?php
                              if(!empty($person_list))
                              {
                              foreach($person_list as $person)
                              {
                                ?>
                                <option value="<?php echo $person->id; ?>" <?php if($person->id==$form_data['marketing_person_id']){ echo 'selected="selected"'; } ?>><?php echo $person->name; ?></option>
                                <?php
                              }
                            }
                           ?> 
                        </select>
                     </div>
                  </div> <!-- row -->


                  <?php 
                 if(!empty($form_data['data_id']) && $form_data['data_id']>0)
                 {
                ?>
                <div class="row m-b-5">
                     <div class="col-xs-4">
                        <strong>Username</strong>
                     </div>
                     <div class="col-xs-8">
                        <input type="text" name="username" id="username" value="<?php echo $form_data['username'] ?>" readonly /> 
                     </div>
                  </div> <!-- row -->
                  <div class="row m-b-5">
                     <div class="col-xs-4">
                        <strong>Password</strong>
                     </div>
                     <div class="col-xs-8">
                        <div class='pwdwidgetdiv' id='thepwddiv'></div>
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
                  </div> <!-- row -->
                <?php   
                 }
                ?> 
              

                    
<?php 
 $users_data = $this->session->userdata('auth_users');
 if(in_array('588',$users_data['permission']['action'])) 
 { ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Consultation Charge</strong>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a price money of a doctor or consultant when doctor came with any reference like consultant this charge gave to a doctor or consultant by which doctor come. </span></a></sup>
                      </div>
                      <div class="col-xs-8">
                        <input type="text"  name="consultant_charge" class="price_float" id="consultant_charge" value="<?php echo $form_data['consultant_charge']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('consultant_charge'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong><?php echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');  ?></strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text"  name="emergency_charge" class="price_float" id="emergency_charge" value="<?php echo $form_data['emergency_charge']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('emergency_charge'); } ?>
                      </div>
                    </div> <!-- row -->

      <?php } else { ?>
      <input type="hidden"  name="consultant_charge" value="0.00">
      <input type="hidden"  name="emergency_charge" value="0.00">

      <?php } ?>
                    
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>DOB</strong>
                      </div>
                      <div class="col-xs-5">
                              <input type="text" class="datepicker" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>" /> 
                            </div>
                    </div> <!-- row -->
                    
                     <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Qualification</strong>
                      </div>
                      <div class="col-xs-5">
                        <!--<input type="text" class="" name="qualification" id="qualification" value="< ?php echo $form_data['qualification']; ?>" /> -->
                        
                         <textarea id="qualification" name="qualification" ><?php echo $form_data['qualification']; ?></textarea>
                        
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Marriage Anniversary</strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" class="datepicker" readonly="" name="anniversary" id="anniversary" value="<?php echo $form_data['anniversary']; ?>" /> 
                      </div>
                    </div> <!-- row -->
                    <div class="row m-b-5">
                         <div class="col-xs-4">
                              <strong>Status</strong>
                         </div>
                         <div class="col-xs-8">
                              <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>   value="1" /> Active 
                              <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>  value="0" /> Inactive 
                         </div>
                    </div>

                  <?php 
                  $users_data = $this->session->userdata('auth_users');
                  if(in_array('588',$users_data['permission']['action'])) 
                  { ?> 
                    <div class="row m-b-5">
                     <div class="col-xs-4"><label>Doctor Panel Type</label></div>
                     <div class="col-xs-8">
                        <input type="radio" name="doctor_panel_type" value="1" <?php if($form_data['doctor_panel_type']==1){ echo 'checked';} ?> > Normal &nbsp;
                          <input type="radio" name="doctor_panel_type" value="2" <?php if($form_data['doctor_panel_type']==2){ echo 'checked';}?>> Panel
                      </div>
                  </div>

                  <div class="row m-b-5">
                     <div class="col-xs-4"><label>Doctor Schedule</label></div>
                     <div class="col-xs-8">
                        <input type="radio" name="schedule_type" value="1" <?php if($form_data['schedule_type']==1){ echo 'checked';} ?> > Yes &nbsp;
                          <input type="radio" name="schedule_type" value="2" <?php if($form_data['schedule_type']==2){ echo 'checked';}?>> No
                      </div>
                  </div>
                  <?php } else { ?>
                   <input type="hidden" name="schedule_type" value="2">
                   <input type="hidden" name="doctor_panel_type" value="2">
                  <?php } ?>


                  


 <?php if(in_array('872',$users_data['permission']['action'])){ ?>
                 <div class="row m-b-5">
                  <div class="col-xs-4"><label>Sign Image</label></div>
                  <div class="col-xs-8">
                   <input type="file" name="sign_img" accept="image/*">
                    <?php if(!empty($form_error))
                    { 
                      echo form_error('sign_img'); 
                     } ?>
                     <?php
                    if(!empty($sign_error))
                    {
                      echo '<div class="text-danger">'.$sign_error.'</div>';
                    }

                    if(!empty($form_data['old_sign_img']) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$form_data['old_sign_img']))
                    {
                        $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$form_data['old_sign_img'];
                        echo '<img src="'.$sign_img.'" width="100px" />';
                    }
                    ?>

                    </div>
                  </div>
<?php } ?>

                </div> <!-- 6 // Left -->





                <!-- Right portion from here -->
                <div class="col-xs-6">

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Address 1</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" class="address"  name="address" id="address" value="<?php echo $form_data['address']; ?>" /> 
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Address 2</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" class="address"  name="address2" id="address2" value="<?php echo $form_data['address2']; ?>" /> 
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Address 3</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" class="address"  name="address3" id="address3" value="<?php echo $form_data['address3']; ?>" /> 
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Country</strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="country_id" id="country_id" onchange="return get_state(this.value);">
                          <option value="">Select Country</option>
                          <?php
                          if(!empty($country_list))
                          {
                            foreach($country_list as $country)
                            {
                               $selected = "";
                               if($form_data['country_id']==$country->id)
                               {
                                 $seleted = 'selected="selected"';
                               }
                               echo '<option value="'.$country->id.'" '.$seleted.'>'.$country->country.'</option>';
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
                        <select name="state_id" id="state_id" onchange="return get_city(this.value);">
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
                        <select name="city_id" id="city_id">
                          <option value="">Select City</option> 
                            <?php
                             if(!empty($form_data['state_id']))
                             {
                                $city_list = city_list($form_data['state_id']);
                                if(!empty($city_list))
                                {
                                   foreach($city_list as $city)
                                   {
                                    ?>   
                                      <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>><?php echo $city->city; ?></option>
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
                        <strong>PIN Code</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="pincode" data-toggle="tooltip"  title="Pin code should be max digit." class="numeric"  maxlength="6" id="pincode"  value="<?php echo $form_data['pincode']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('pincode'); } ?>
                       
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Email</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="email"  data-toggle="tooltip"  title="Email should be like abc@example.com." class="email_address" id="email" value="<?php echo $form_data['email']; ?>">
                      
                        <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Alternate Mobile</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="alt_mobile_no" maxlength="10"  data-toggle="tooltip"  title="Allow only numeric." class="numeric" id="alt_mobile_no" value="<?php echo $form_data['alt_mobile_no']; ?>">
                      
                          <?php if(!empty($form_error)){ echo form_error('alt_mobile_no'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Landline No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="landline_no" data-toggle="tooltip"  title="Landline should be 13 digits." class="landline" maxlength="13"  id="landline_no" value="<?php echo $form_data['landline_no']; ?>">
                        
                        <?php if(!empty($form_error)){ echo form_error('landline_no'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>PAN No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" maxlength="10" class="alpha_numeric" name="pan_no" id="pan_no" value="<?php echo $form_data['pan_no']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('pan_no'); } ?>
                      </div>
                    </div> <!-- row -->

                      <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Registration No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="doc_reg_no" class="" id="doc_reg_no" value="<?php echo $form_data['doc_reg_no']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('doc_reg_no'); } ?>
                      </div>
                    </div> <!-- row -->

                    <!-- <div class="row m-b-5">
                          <div class="col-xs-4">
                              <strong>Schedule Type</strong>
                         </div>
                         <div class="col-xs-8">
                              <select id="schedule_type" name="schedule_type">
                                  <?php 
                                   $selected_daily = '';
                                   $selected_period='';
                                   $selected_on_calls ='';
                                 
                                   if($form_data['schedule_type']=='daily'){
                                        $selected_daily = 'selected="selected"';
                                   }elseif($form_data['schedule_type']=='period'){
                                        $selected_period = 'selected="selected"';
                                   }elseif($form_data['schedule_type']=='on_calls'){
                                        $selected_on_calls = 'selected="selected"';
                                   }
                                  
                                  ?> 
                                   <option value="" >Select Schedule Type</option>
                                   <option value="1" <?php echo $selected_daily; ?> >Daily</option>
                                   <option value="2" <?php echo $selected_period; ?>>Period</option>
                                   <option value="3" <?php echo $selected_on_calls; ?>>On Calls</option>
                              </select>
                         </div>
                    </div>

                    <div class="row m-b-5">
                          <div class="col-xs-4">
                              <strong>Days</strong>
                         </div>
                         <div class="col-xs-8">
                               <input type="text"  name="days" class="price_float" id="days" value="<?php echo $form_data['days']; ?>">
                         </div>
                    </div>


                    <div class="row m-b-5">

                          <div class="col-xs-4">
                              <strong>Timings</strong>
                         </div>
                         <div class="col-xs-8">
                              <textarea id="timings" name="timings" ><?php echo $form_data['timings']; ?></textarea>
                         </div>
                    </div>  -->
 <?php 
 $users_data = $this->session->userdata('auth_users');
 if(in_array('588',$users_data['permission']['action'])) 
 { ?> 
<div class="row m-b-5">
            <div class="col-xs-4">
            <strong> Available Days</strong>
            </div>
              <div class="col-xs-8">
              <div id="day_check">
              <?php //print_r($available_day);
              foreach ($days_list as $value) 
              { 

                  ?>
                  <input type="checkbox" class="day_check" name="check_day[]" id="check-<?php echo $value->id; ?>" value="<?php echo $value->id; ?>" onClick="checkdays('<?php echo $value->id; ?>','<?php echo $value->day_name; ?>');" <?php if(isset($available_day[$value->id])){ echo 'checked'; } ?> > <?php echo $value->day_name; ?>
                    <?php

              }
              ?>
              </div>
               <div>
               <input type="checkbox" name="checkall" id="check_all" onclick="clone_rows();" value="1"> All day Time like First Day Selection </div>
                   
            </div>
            </div>
          <?php
          if(!empty($available_day))
          {
            foreach ($available_day as $key=>$value) 
            {
                  ?>
                        <div><div class="row m-b-5">
                          <div class="col-xs-4"><strong><?php  echo $value; ?> </strong></div>
                          <div class="col-xs-8">
                          <table class="schedule_timing" id="doctor_time_table_day_<?php echo $key; ?>">
                          <thead>
                                  <tr>
                                      <td>From </td>
                                      <td>To </td>
                                      <td><a href="javascript:void(0)" class="btn-new addrow_day" onClick="return add_time_row('<?php echo $key; ?>')"><i class="fa fa-plus"></i>  Add</a></td>
                                  </tr>
                          </thead>
                          <tbody>
                          <?php 
                         $doctor_availablity_time = get_doctor_schedule_time($key,$form_data['data_id']);
                          if(!empty($doctor_availablity_time))
                          {
                             $k=0;
                             foreach ($doctor_availablity_time as $doctor_time) 
                             { //print_r($value);
                          ?>
                              <tr id="row-<?php echo $doctor_time->available_day.$k; ?>">
                                    <td><input  id="from-row-<?php echo $doctor_time->available_day.$k; ?>"  type="text" name="time[<?php echo $doctor_time->available_day; ?>][from][]" value="<?php echo $doctor_time->from_time; ?>" class="datepicker_day w-60px" ></td>
                                    <td><input type="text" name="time[<?php echo $doctor_time->available_day; ?>][to][]" id="to-row-<?php echo $doctor_time->available_day.$k; ?>" class="datepicker_day  w-60px" value="<?php echo $doctor_time->to_time; ?>" ></td>
                                    <td><a class="btn-new" href="javascript:void(0)" onclick="delete_time_row(<?php echo $doctor_time->available_day.$k; ?>)"> <i class="fa fa-trash"></i> Delete </a></td>
                              </tr>
                         <?php 
                          $k++;
                          } 
                        }
                          ?>
                          </tbody>
                          </table>
                          </div>
                          </div>
                          </div>
                      <?php 
            }
          }

          

          ?>


          <div id="schedule_time">
            
          </div>
          
          
                   

          <div class="row m-b-5">

                <div class="col-xs-4">
                    <strong>Per Patient Time</strong>
               </div>
               <div class="col-xs-8">
                    
                    <input type="text"  name="per_patient_timing" class="" id="per_patient_timing" value="<?php echo $form_data['per_patient_timing']; ?>"> Min.

               </div>
          </div>  <!-- row -->

                    <!-- row -->
<?php }else{ ?>  
<input type="hidden"  name="per_patient_timing" value="0"> 
<?php } ?>

                  
                </div> <!-- 6 // Right -->



                




              </div> <!-- ROW -->
              <div class="row">
                <div class="col-xs-12">
                <div class="row m-b-5">
                     <div class="col-xs-2"><label>Separate Header For </label></div>
                     <div class="col-xs-8">
                        <input type="checkbox"  name="opd_header" value="1" id="opd_header" <?php if(!empty($form_data['opd_header'])){ echo 'checked'; } ?> > OPD &nbsp; 

                        <input type="checkbox"  name="billing_header" value="1" id="billing_header"  <?php if(!empty($form_data['billing_header'])){ echo 'checked'; } ?> > Billing &nbsp; 

                        <input type="checkbox"  name="prescription_header" value="1" id="prescription_header" <?php if(!empty($form_data['prescription_header'])){ echo 'checked'; } ?> > Prescription &nbsp; 

                      </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-xs-12">
                <div class="row m-b-5">
                     <div class="col-xs-2"><label>Separate Header</label></div>
                     <div class="col-xs-8">
                        <input type="radio" name="seprate_header" value="1" onClick="return set_header(1)" <?php if($form_data['seprate_header']==1){ echo 'checked';} ?> > Yes &nbsp;
                          <input type="radio" name="seprate_header" value="2" onClick="return set_header(2)" <?php if($form_data['seprate_header']==2){ echo 'checked';}?>> No
                      </div>
                  </div>
                </div>
              </div>
               <div class="row" id="headers_content" <?php if($form_data['seprate_header']!=1){ ?> style="display: none;" <?php } ?>>
                <div class="col-xs-12">

                  <div class="row m-b-5">
                     <div class="col-xs-2"><label>Content</label></div>
                     <div class="col-xs-10">
                        <textarea id="header_content" name="header_content" ><?php echo $form_data['header_content']; ?></textarea>

                      </div>
                  </div>
                  
<script type="text/javascript">
  CKEDITOR.replace( 'header_content' );
</script>
</div>
              </div>


              <!-- // Till Here -->




            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Save" id="save-doctor" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     
<script>

function set_header(val)
 {
    if(val==1)
    {
      $('#headers_content').show();
      
    }
    else
    {
      $('#headers_content').hide();
      
    }
 }

function checkdays(ids, vals)
{ 
   $('#'+ids+'_day').remove(); 
   if ($('#check-'+ids).is(":checked"))
   {  
      $("#schedule_time").append('<div id="'+ids+'_day" ><div class="row m-b-5"><div class="col-xs-4"><strong>'+vals+' </strong></div><div class="col-xs-8"><table class="schedule_timing" id="doctor_time_table_day_'+ids+'"><thead><tr><td>From </td><td>To </td><td><a href="javascript:void(0)" class="btn-new addrow_day_'+ids+'" onClick="return add_time_row('+ids+')"><i class="fa fa-plus"></i>  Add</a></td></tr></thead><tbody><tr id="row-'+ids+'0"><td><input id="from-row-'+ids+'0" type="text" name="time['+ids+'][from][]" value="" class="datepicker_day datepicker_day_'+ids+'  w-60px" ></td><td><input type="text" value="" name="time['+ids+'][to][]" id="to-row-'+ids+'0" class="datepicker_day datepicker_day_'+ids+'  w-60px" ></td><td>&nbsp;</td></tr></tbody></table></div></div></div>');
       $('.datepicker_day').datetimepicker({
                format: 'LT'
        });
   }
   else
   {
    $('#'+ids+'_day').remove(); 
   }
}

var i = 1;
function add_time_row(ids)
{
  $("#doctor_time_table_day_"+ids+" tbody").append('<tr id="row-'+ids+i+'"><td><input name="time['+ids+'][from][]" class="datepicker_day datepicker_day_1  w-60px" id="from-row-'+ids+i+'"  value="" type="text"></td><td><input name="time['+ids+'][to][]" id="to-row-'+ids+i+'" value="" class="datepicker_day datepicker_day_1  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+ids+i+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>');
  $('.datepicker_day').datetimepicker({
                format: 'LT'
        });
  i++;
}


function delete_time_row(row_id)
{
  $('#row-'+row_id).remove();
}

function clone_rows()
{ 
  //$( "#schedule_time table" ).first().id();
  var c = 1;
  var first_row_id = '0';
  $("#day_check input:checked").each(function () 
  { 
        if($('#check_all').is(":checked"))
        {
            var id = $(this).attr("id");
            if(c==1)
            {
              var id = id.replace("check-", "");
              first_row_id = id;
            }
            else
            {  
              
              var ti= 0;
              var id = $(this).attr("id");  
              var id = id.replace("check-", "");
              $('#doctor_time_table_day_'+id+' tbody').html(' ');
              $("#doctor_time_table_day_"+first_row_id+" tbody tr").each(function (){ 
                    var row_id = $(this).attr('id');
                    
                    var textbox_from = 'from-'+row_id;
                    var textbox_to = 'to-'+row_id;
                    var from_val = $('#'+row_id+' input:text[id='+textbox_from+']').val(); 
                    var to_val = $('#'+row_id+' input:text[id='+textbox_to+']').val();  
                    var row_html = '<tr id="row-'+id+ti+'"><td><input name="time['+id+'][from][]" value="'+from_val+'" class="datepicker_day datepicker_day_'+id+'  w-60px" style="" type="text"></td><td><input value="'+to_val+'" name="time['+id+'][to][]" class="datepicker_day datepicker_day_'+id+'  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+id+ti+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>';

                    $("#doctor_time_table_day_"+id+"").append(row_html);
                    ti++;
              });   
              /*if(first_row_id>0)
              {
                  var id = id.replace("check-", "");
                  $('#doctor_time_table_day_'+id+' tbody').html(' ');
                  $("#doctor_time_table_day_"+id+"").append($('#doctor_time_table_day_'+first_row_id+' tbody tr').clone());
                               var id = id.replace("check-", "");
                  $('#doctor_time_table_day_'+id+' tbody').html(' ');
                  var first_rows = $('#doctor_time_table_day_'+first_row_id+' tbody').html();
                  var first_rows = first_rows.replace("_"+first_row_id, "_"+id);
                  var first_rows = first_rows.replace("["+first_row_id+"]", "["+id+"]");
                  $('#doctor_time_table_day_'+id+' tbody').html(first_rows);
                  //$('#doctor_time_table_day_'+id+' tbody').html(' ');
                  
              } */
            }

        } 
        else
        {
            
            var id = $(this).attr("id");
            if(c==1)
            {
              
            }
            else
            { 

              var id = $(this).attr("id");  
              var id = id.replace("check-", "");
              
              $('#doctor_time_table_day_'+id+' tbody').html(' ');  
              $("#doctor_time_table_day_"+id+" tbody").append('<tr id="row-'+id+c+'"><td><input name="time['+id+'][from][]" class="datepicker_day datepicker_day_1  w-60px" id="from-row-'+id+c+'"  value="" type="text"></td><td><input name="time['+id+'][to][]" id="to-row-'+id+c+'" value="" class="datepicker_day datepicker_day_1  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+id+c+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>');
                $('.datepicker_day').datetimepicker({
                              format: 'LT'
                });
            }
              //$('#doctor_time_table_day_'+id+' tbody').html(' ');
        }    
        c++;
    });


} 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
});
$(".txt_firstCapital").on('keyup', function(){

   var str = $('.txt_firstCapital').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCapital').val(part_val.join(" "));
  
  }); 
</script>
<script>   

function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
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
function rate_modal()
  {
      var $modal = $('#load_add_rate_modal_popup');
      $modal.load('<?php echo base_url().'rate/add/' ?>',
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
 
$("#emp_type").on("submit", function(event) { 

  for (instance in CKEDITOR.instances)
CKEDITOR.instances[instance].updateElement();

  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Doctor successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Doctor successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('doctors/'); ?>"+path,
    type: "post",
    //data: $(this).serialize(),
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false,
    beforeSend: function() {
           $('#save-doctor').attr('disabled');
           },
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        get_doctors();
        reload_table();
        get_doctors_to_specialization();
        get_attended_doctor_to_specialization(); 
        get_sales_docotors();
        flash_session_msg(msg); 
        reload_table();

      } 
      else
      {
        $("#load_add_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});


 

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

function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result); 
    } 
  });
}
function get_doctors()
{
   $.ajax({url: "<?php echo base_url(); ?>doctors/doctors_dropdown/", 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
  });
}

function get_comission(id)
{
  $.ajax({
    dataType: "json",
    url: "<?php echo base_url(); ?>doctors/get_comission/"+id, 
    success: function(result)
    {

      $('#share_lable').html('<strong>'+result.lable+'</strong>'); 
      $('#share_input').html(result.inputs); 
    } 
  });
  
    if(id==2)
  {
      var $modal = $('#load_transaction_modal_popup');
      $modal.load('<?php echo base_url().'doctors/load_transaction_modal/' ?>',
      {
      //'id1': '1',
      //'id': '<?php echo $form_data['data_id']; ?>'
      },
      function(){
      $modal.modal('show');
      });
  }
}

function comission(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'doctors/add_comission/' ?>',
  {
    //'id1': '1',
    'id': '<?php echo $form_data['data_id']; ?>'
    },
  function(){
  $modal.modal('show');
  });
} 
function get_doctors_to_specialization()
    {
       var specilization_id = '<?php echo $this->session->userdata('specilization_id');?>';
      
       if(specilization_id!="" && specilization_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>doctors/type_to_doctors/"+specilization_id, 
              success: function(result)
              {
                $('#refered_id').html(result); 
              } 
            });
       } 
    }

    function get_attended_doctor_to_specialization()
    {
       var specilization_id = '<?php echo $this->session->userdata('specilization_id');?>';
      
       if(specilization_id!="" && specilization_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>doctors/type_attended_doctors/"+specilization_id, 
              success: function(result)
              {
                $('#attended_doctor').html(result); 
              } 
            });
       } 
    }

$(document).ready(function() {
  $('#load_add_comission_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

$(document).ready(function() {
  $('#load_add_specialization_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(document).ready(function() {
  $('#load_add_rate_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    startView: 2
  })
  
  function transaction_modal()
 {
    $('#load_transaction_modal_popup').modal('hide');
 }
</script> 
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->   
</div><!-- /.modal-dialog -->  


 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
  $('.datepicker_day').datetimepicker({
      format: 'LT'
  });
</script>
