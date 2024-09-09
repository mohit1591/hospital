<?php
$users_data = $this->session->userdata('auth_users'); 
$child_branch_list = $this->session->userdata('sub_branches_data'); 
$post_data = $this->input->post();
//echo "<pre>"; print_r($form_data);die;

?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">

<!-- boot210strap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<style>
.row_hide{display:none;}
</style>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>  
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_PLUGIN_PATH; ?>bootstrap-timepicker/css/bootstrap-timepicker.css">
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<style>
fieldset{float:left;width:100%;}
.mb-10{margin-bottom:10px;}
.flex-input{display:flex;align-items:center;}
.form-control{font-size:13px;}
.flex-input select,.flex-input .form-control{height:19pt;line-height:19pt;padding:0px;max-width:200px;color: #000!important;}
.flex-input .btn-new{margin-left:5px;}
.__heading{float:left;width:100%;background:#eee;padding:11px 15px;font-size:14px;font-weight:400;}
.flex_age{display:flex;align-items:center;}
.flex_age input{width:48px!important;margin:0 5px}
.w-200 {
    width: 200px;
    height: 19pt;
    line-height: 19pt;
    padding: 0;
    color: #000!important;
}
.float-right{float:right;}
.__prf{display:flex;align-items:center;}
.__prf label{margin-right:20px;}
.__prf > div{margin-right:10px;}
.__flex_table{display:flex;}
.__ftbl{flex:1;height:250px;border:1px solid #ccc;overflow-y:scroll;}
.__fbtn{padding:0 0 0 10px;}
</style>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="path-booking">
<form id="booking_form" action="<?php echo current_url(); //base_url('test/booking'); ?>" method="post">
  <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>">   
  <!-- Lead Data -->

<link rel="stylesheet" type="text/css" href="https://www.hospitalms.in/assets/css/crm/withoutresponsive.css">
  <div class="panel panel-default">
  	<div class="panel-heading"><strong>ADD LEAD</strong></div>
  	<div class="panel-body">
  		<fieldset>
  			<div class="row">
  				<div class="col-md-4">
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Lead ID<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<input type="text" class="form-control" name="lead_id" value="<?php echo $form_data['lead_id']; ?>" readonly>
                  <?php if(!empty($form_error)){ echo form_error('lead_id'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Lead Type<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<div class="flex-input">
  									<select name="lead_type_id" id="lead_type_id" class="form-control" onchange="return set_lead_type(this.value)">
                      <option value="">Select Lead Type</option>
                      <?php
                       if(!empty($lead_type_list))
                       {
                          foreach($lead_type_list as $lead_type_id)
                          {
                              $lead_type_select = '';
                              if($form_data['lead_type_id']==$lead_type_id->id)
                              {
                                  $lead_type_select = 'selected="selected"';
                              }    
                                  echo '<option '.$lead_type_select.' value="'.$lead_type_id->id.'">'.$lead_type_id->lead_type.'</option>';
                              
                          }
                       }
                      ?>
                  </select> 
                  <?php
                  if(in_array('2437',$users_data['permission']['action'])) 
                  {
                  ?>
  									<div><a href="javascript:void(0);" id="add_lead_type" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                  <?php
                  }
                  ?>  
  								</div>
                  <?php if(!empty($form_error)){ echo form_error('lead_type_id'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Source<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<div class="flex-input">
  									<select name="lead_source_id" id="lead_source_id" class="form-control">
                        <option value="">Select Source</option>
                        <?php
                         if(!empty($lead_source_list))
                         {
                            foreach($lead_source_list as $lead_source)
                            {
                                $lead_source_select = '';
                                if($form_data['lead_source_id']==$lead_source->id)
                                {
                                    $lead_source_select = 'selected="selected"';
                                }    
                                    echo '<option '.$lead_source_select.' value="'.$lead_source->id.'">'.$lead_source->source.'</option>';
                                
                            }
                         }
                        ?>
                    </select> 
  									<?php
                    if(in_array('2445',$users_data['permission']['action'])) 
                    {
                    ?>
                      <div><a href="javascript:void(0);" id="add_lead_source" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                    <?php
                    }
                    ?> 
  								</div>
                  <?php if(!empty($form_error)){ echo form_error('lead_source_id'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  			</div>
  		</fieldset>

  		<h4 class="__heading">Patient Details</h4>
  		<fieldset>
  			<div class="row">
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Name<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
                    <input type="text" class="form-control" name="name" value="<?php echo $form_data['name']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Email</label>
  							</div>
  							<div class="col-sm-9">
  								<input type="text" class="form-control" name="email" value="<?php echo $form_data['email']; ?>" >
                  <?php if(!empty($form_error)){ echo form_error('email'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Phone<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<input type="text" maxlength="10" class="form-control" name="phone" value="<?php echo $form_data['phone']; ?>" >
                  <?php if(!empty($form_error)){ echo form_error('phone'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
          <div class="col-md-4">
            <fieldset class="mb-10">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Age</label>
                </div>
                <div class="col-sm-9">
                  <div class="flex_age">
                    <span>D</span><input type="text" class="form-control" value="<?php echo $form_data['age_d']; ?>" name="age_d">
                    <span>M</span><input type="text" class="form-control" value="<?php echo $form_data['age_m']; ?>" name="age_m">
                    <span>Y</span><input type="text" class="form-control" value="<?php echo $form_data['age_y']; ?>" name="age_y"> 
                    <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-md-4">
            <fieldset class="mb-10">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Gender<span class="star">*</span></label>
                </div>
                <div class="col-sm-9">
                  <div id="gender">
                    <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                    <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                    <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
                    <?php 
                    if(!empty($form_error)){ echo form_error('gender'); } 

                    ?>
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-md-4">
            <fieldset class="mb-10">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Address 1</label>
                </div>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="address" value="<?php echo $form_data['address']; ?>">
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-md-4">
            <fieldset class="mb-10">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Address 2</label>
                </div>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="address2" value="<?php echo $form_data['address2']; ?>">
                </div>
              </div>
            </fieldset>
          </div>
          <div class="col-md-4">
            <fieldset class="mb-10">
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Address 3</label>
                </div>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="address3" value="<?php echo $form_data['address3']; ?>">
                </div>
              </div>
            </fieldset>
          </div>
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Country</label>
  							</div>
  							<div class="col-sm-9"> 
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
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">State</label>
  							</div>
  							<div class="col-sm-9">
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
  					</fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">City</label>
  							</div>
  							<div class="col-sm-9">
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
  					</fieldset>
  				</div>
  			</div>
  		</fieldset>

  		<h4 class="__heading">
  			<div class="row">
  				<div class="col-sm-4">
  					<span>Service Department<span class="star">*</span></span>
  					<select name="department_id" id="department_id" class="" onchange="return department_data(this.value);">
                  <option value="">Select Department</option>
                  <?php
                  
               $users_data = $this->session->userdata('auth_users'); 
               if (array_key_exists("permission",$users_data)){
                     $permission_section = $users_data['permission']['section'];
                     $permission_action = $users_data['permission']['action'];
                }
                else{
                     $permission_section = array();
                     $permission_action = array();
                     $permission_section_new=array();
                   }
                //echo "<pre>"; print_r($dept_list);    die;
                if(!empty($department_list))
                {
                  foreach($department_list as $dept)
                   {
                       $lead_source_select = '';
                          if($form_data['department_id']==$dept->id)
                          {
                              $lead_source_select = 'selected="selected"';
                          }  
                          if($dept->department =='OPD' && in_array('85',$permission_section))
                          {
                              
                                 echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                          }
                           elseif($dept->department =='OPD Billing' && in_array('151',$permission_section))
                          {
                              echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                          }
                           elseif($dept->department =='IPD' && in_array('121',$permission_section))
                            {
                               echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>'; 
                            }
                            elseif($dept->department =='OT' && in_array('134',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                            elseif($dept->department =='BLOOD BANK' && in_array('262',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                             else if($dept->department =='Ambulance' && in_array('349',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';    
                            }
                             elseif($dept->department =='LAB' && in_array('145',$permission_section))
                            { 
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                            
                            
                       
                   }
                   
                        if(in_array('179',$permission_section))
                            { 
                                echo '<option '.$lead_source_select.' value="-1">Vaccination</option>';  
                            }
                            
                             echo '<option '.$lead_source_select.' value="-2">Other</option>';  
                   
                }
                  
                  
                  
                  
                  ?>
              </select>
              <?php  if(!empty($form_error)){ echo form_error('department_id'); } ?>
 
  				</div>
  			</div>
  		</h4>
  		<!-- Consultation -->
  		<div class="opd_booking_box" <?php if($form_data['department_id']!=2){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-3">
  									<label for="">Specialization<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-9">
                  <div class="flex-input">  
  									<select name="specialization_id" class="w-200 form-control" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
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
                  <?php
                  if(in_array('44',$users_data['permission']['action'])) 
                  {
                  ?>
                    <div><a href="javascript:void(0);" id="add_specilization_id" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                  <?php
                  }
                  ?>
                </div>

                  <?php if(!empty($form_error)){ echo form_error('specialization_id'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>

            <div class="col-md-4">
              <fieldset class="mb-10">
                <div class="row">
                  <div class="col-sm-3">
                    <label for="">Consultant<span class="star">*</span></label>
                  </div>
                  <div class="col-sm-9">
                   <div class="flex-input"> 
                    <select name="attended_doctor" class="form-control w-200" id="attended_doctor">
                                      <option value="">Select Consultant</option>
                                      <?php                                         
                                        if(!empty($doctor_list))
                                        {
                                           foreach($doctor_list as $doctor)
                                           {    
                                          ?>   
                                            <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                                          <?php
                                            //}
                                         }
                                      }
                                   
                                  ?>
                                  </select>
                    <?php
                    if(in_array('122',$users_data['permission']['action'])) 
                    {
                    ?>
                      <div><a href="javascript:void(0);" id="add_attended_doctor" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                    <?php
                    }
                    ?>              
                    </div>              
                   <?php if(!empty($form_error)){ echo form_error('attended_doctor'); } ?>
                  </div>
                </div>
                </fieldset>
            </div>


 
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-3">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-9">
  									<input type="text" class="form-control" name="opd_service" value="<?php echo $form_data['opd_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('opd_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>

  		<!-- Pathology -->
      <?php
      //$path_arr = array(2,3);
      ?>
  		<div class="lab_booking_box" <?php if($form_data['department_id']!=8){ ?> style="display: none;" <?php } ?>>
	  		<fieldset class="mb-10">
          <div class="row">
            <div class="col-md-3">
              <fieldset class="mb-10">
                <div class="row">
                  <div class="col-sm-6">
                    <label for="">Home Collection (<span id="home_collection_amount"><?php echo $form_data['home_collection_amount']; ?></span>)</label>
                  </div>
                  <div class="col-sm-6">
                      <input type="radio" name="home_collection" value="1" onclick="return set_home_collection(1); " <?php if($form_data['home_collection']==1){ echo 'checked="checked"'; } ?>> Yes &nbsp;
                      <input type="radio" onclick="return set_home_collection(0); " name="home_collection" value="0" <?php if($form_data['home_collection']==0){ echo 'checked="checked"'; } ?>> No
                  </div>
                </div>
              </fieldset> 
            </div>     
          </div>

	  			<div class="row">
	  				<div class="col-md-3">
	  					<fieldset class="mb-10">
	  						<div class="row">
	  							<div class="col-sm-3">
	  								<label for="">Department</label>
	  							</div>
	  							<div class="col-sm-9">
	  								<select name="dept_id" id="dept_id" class="m_input_default">
                      <option value="">Select Department</option>
                            <?php
                             if(!empty($dept_list))
                             {
                                foreach($dept_list as $dept)
                                {
                                    $dept_select = "";
                                    if($dept->id==$form_data['dept_id'])
                                    {
                                        $dept_select = "selected='selected'";
                                    }
                                    echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                }
                             }
                            ?>
                    </select>
	  							</div>
                  <?php if(!empty($form_error)){ echo form_error('dept_id'); } ?>
	  						</div>
	  					</fieldset>
	  				</div>
	  				<div class="col-md-3">
	  					<fieldset class="mb-10">
	  						<div class="row">
	  							<div class="col-sm-3">
	  								<label for="">Search</label>
	  							</div>
	  							<div class="col-sm-9"> 
                    <input type="text" class="form-control w-200" name="test_search" id="test_search" value=""> 
	  							</div>
	  						</div>
	  					</fieldset>
	  				</div>
	  				<div class="col-md-6">  					
	  					<fieldset class="mb-10">
	  						<div class="__prf">
	  							<label for="">Profile</label>
	  							<div> 
                    <select name="profile_id" class="form-control w-200" id="profile_id" onChange="get_profile_test();" style="width: 160px; margin-left: 3px;">
                    <option value="">Select Profile</option>
                          <?php 
                            if(!empty($profile_list))
                           {
                              foreach($profile_list as $profile)
                              { 
                                  $profile_select = "";
                                  if($profile->id==$form_data['profile_id'])
                                  {
                                      $profile_select = "selected='selected'";
                                  }
    $profile_status = $profile_print_status->profile_status;
                                  $print_status = $profile_print_status->print_status;
                                  if($profile_status==1 && $print_status==1)
                                  {
                                    if(!empty($profile->print_name))
                                    {
                                      $profile_name = $profile->profile_name.' ('.$profile->print_name.')';
                                    }
                                    else
                                    {
                                      $profile_name = $profile->profile_name;
                                    }
                                  }
                                  elseif($profile_status==1 && $print_status==0)
                                  {
                                    $profile_name = $profile->profile_name;
                                  }
                                  elseif($profile_status==0 && $print_status==1)
                                  {
                                    if(!empty($profile->print_name))
                                    {
                                      $profile_name = $profile->print_name;
                                    }
                                    else
                                    {
                                      $profile_name = $profile->profile_name;
                                    }
                                    
                                  }
                                  elseif($profile_status==0 && $print_status==0)
                                  {
                                    $profile_name = $profile->profile_name;
                                  }
                                  //echo "<pre>"; print_r($profile_print_status); exit;
                                  
                                  echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile_name.'</option>';
                                  //echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile->profile_name.'</option>';
                              }
                           } 
                          ?>
                  </select> 
	  							</div>
	  							<div>
	  								<input type="text"  name="rate" value="<?php echo $form_data['profile_price']; ?>"  id="profile_price" class="numeric form-control"> 
	  							</div>
	  							<div>
	  								<a href="javascript:void(0);" onClick="add_profile();" class="btn-new"><i class="fa fa-plus"></i> Add</a>
	  							</div>
	  						</div>
	  					</fieldset>
	  				</div>
	  			</div>
	  			<div class="row">
	  				<div class="col-md-3"> 
              <select size="9" name="dept_parent_test" class="form-control"  id="dept_parent_test" tabindex="14" style="height:250px;padding:0px;">
                 <?php
                    if(isset($head_list) && !empty($head_list))
                    {
                      foreach($head_list as $head)
                      {
                         echo '<option value="'.$head->id.'">'.$head->test_heads.'</option>';
                      }
                    }
                 ?>
              </select>
	  				</div>
	  				<div class="col-md-9">
	  					<div class="__flex_table">
	  						<div class="__ftbl">
                  <table class="table table-bordered" id="test_list">
                    <thead class="bg-primary">
                    <tr>
                      <th width="60" align="center">Select</th>
                      <th>Test ID</th>
                      <th>Test Name</th> 
                      <th>Patient Rate</th>
                    </tr>
                    </thead>
                    <tr>  
                      <td colspan="5">
                        <div class="text-danger p-l-half" style="text-align: center;">Test not available</div>
                      </td>
                    </tr>            
                </table>
	  							
	  						</div>
	  						<div class="__fbtn">
	  							<a href="javascript:void(0);" onclick="return send_test(event);" class="btn-new"><i class="fa fa-plus"></i> Add</a>
	  						</div>
	  					</div>
	  				</div>
	  			</div>
	  		</fieldset>
	  		<fieldset class="mb-10">
	  			<h4 class="__heading">Booking Test Detail</h4>
	  			<div class="row">
	  				<div class="col-md-12">
	  					<div class="__flex_table">
	  						<div class="__ftbl">
                  <table class="table table-bordered" id="test_select">
                      <thead class="bg-primary">
                      <tr>
                        <th width="40" align="center" style="text-align: center;"> <input type="checkbox" class="" name="select_all" id="select_all" onClick="final_toggle(this);"/> </th>
                        <th>Test ID</th>
                        <th>Test / Profile Name</th> 
                        <th width="95px">Patient Rate</th>
                      </tr>
                      </thead>
                      <?php
                      $profile_data = $this->session->userdata('set_profile');
                      //echo "<pre>";print_r($profile_data);die;
                      $booked_arr = $this->session->userdata('book_test'); 
                      $total_test = count($booked_arr);
                      $profile_row = "";
                      $p_order = 1;
                      $profile_order = 1;
                      if(isset($profile_data) && !empty($profile_data))
                      {
                        $pi = 1;
                        foreach($profile_data as $profile_dat)
                        {
                           $profile_row .= '<tr>
                                            <td width="40" align="center">
                                               <input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$profile_dat['id'].'" >
                                            </td>
                                            <td>'.$profile_dat['id'].'</td>
                                            <td>'.$profile_dat['name'].'</td> 
                                            <td>'.$profile_dat['price'].'</td>
                                        </tr>';
                        
                         if($total_test>1)
                          {
                            $profile_order = $total_test-$profile_dat['order'];
                            if($profile_order==0)
                            {
                              $profile_order = '1';
                            }
                          }
                          $pi++;
                        }                 
                      }
                      if(isset($booked_test_list) && !empty($booked_test_list))
                      {
                        $i = 1;          
                        foreach($booked_test_list as $booked_test)
                        {  
                          ?>
                            <tr>
                                <td width="40" align="center">
                                   <input type="checkbox" name="test_id[]" class="booked_checkbox" value="<?php echo $booked_test->id; ?>" >
                                </td>
                                <td><?php echo $booked_test->test_code; ?></td>
                                <td><?php echo $booked_test->test_name; ?></td> 
                                <td><?php echo $booked_test->price; ?></td>
                            </tr>       
                          <?php
                          if(isset($profile_data) && !empty($profile_data))
                          { 
                             if($i==1)
                             {
                               echo $profile_row;
                             }
                          }
                        $i++;  
                        }
                      }
                      else
                      {
                        if(isset($profile_data) && !empty($profile_data))
                        {
                          echo $profile_row;
                        }
                        else
                        {
                           ?> 
                          <tr>
                            <td colspan="4" style="text-align: center;">
                               <div class="text-danger p-l-half">Test not added.</div>
                            </td> 
                          </tr>
                          <?php
                        } 
                      }
                      ?>
                      <tr>
                          <td colspan="4"><?php if(!empty($form_error)){ echo form_error('test'); } ?></td>
                      </tr>
                    </table>
	  							
	  						</div>
	  						<div class="__fbtn">
	  							<a href="javascript:void(0);" onClick="test_list_vals();" class="btn-new"><i class="fa fa-trash-o"></i> Delete</a>
	  						</div>
	  					</div>
	  				</div>
	  			</div>
	  		</fieldset>

        <div class="row">
          <div class="col-md-12">
            <div class="col-md-4" style="float: right;">
             <div class="col-md-3"><label for="">Total : </label></div> 
             <div class="col-md-6">
               <input type="text" id="total_amount" readonly="" class="form-control" name="total_amount" value="<?php echo $form_data['total_amount']; ?>" >
               <br/>
             </div> 
             <div class="col-md-2"></div>
            </div> 
          </div>
        </div>
  		</div>

  		<!-- Radiology -->
  		

  		<!-- Dressing -->
  		<div class="billing_booking_box" <?php if($form_data['department_id']!=3){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
  									<input type="text" class="form-control" name="billing_service" value="<?php echo $form_data['billing_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('billing_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>

  		<!-- IPD Admission -->
  		<div class="ipd_booking_box" <?php if($form_data['department_id']!=5){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
  									<input type="text" class="form-control" name="ipd_service" value="<?php echo $form_data['ipd_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('ipd_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>
  		
  		
  		
  		
  		
  		<!-- Surgery -->
  		<div class="ot_booking_box" <?php if($form_data['department_id']!=6){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Surgery<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
                    <div class="flex-input">
                      <select name="ot_id" class="w-145px form-control" id="ot_name_id">
                        <option value="">Select Surgery</option>
                        <?php foreach($operation_list as $op_list)
                        {?>
                        <option value="<?php echo $op_list->id;?>" <?php if(isset($form_data['ot_id']) && $form_data['ot_id']== $op_list->id){echo 'selected';}?>  > <?php echo $op_list->name; ?>   </option>
                        <?php }?>

                        </select>
                      <?php if(!empty($form_error)){ echo form_error('ot_id'); } ?>
                      <div>
                        <?php
                        if(in_array('1132',$users_data['permission']['action'])) 
                        {
                        ?>
                          <div><a href="javascript:void(0);" id="add_ot" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                        <?php
                        }
                        ?>
                    </div>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
  									<input type="text" class="form-control" name="ot_service" value="<?php echo $form_data['ot_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('ot_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>
  		

  		<div class="vacci_booking_box" <?php if($form_data['department_id']!=-1){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
  									<input type="text" class="form-control" name="vaccination_service" value="<?php echo $form_data['vaccination_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('vaccination_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>
  		
  		<div class="other_booking_box" <?php if($form_data['department_id']!=-2){ ?> style="display: none;" <?php } ?>>
  			<fieldset class="mb-10">
  				<div class="row">
  					<div class="col-md-4">
  						<fieldset class="mb-10">
  							<div class="row">
  								<div class="col-sm-4">
  									<label for="">Services<span class="star">*</span></label>
  								</div>
  								<div class="col-sm-8">
  									<input type="text" class="form-control" name="other_service" value="<?php echo $form_data['other_service']; ?>" >
                    <?php if(!empty($form_error)){ echo form_error('other_service'); } ?>
  								</div>
  							</div>
  					    </fieldset>
  					</div>
  				</div>
  			</fieldset>
  		</div>
  		

  		<fieldset class="mb-10">
  			<div class="row">
  				<div class="col-md-4">
  					<fieldset class="mb-10">
  						<div class="row">
  							<div class="col-sm-4">
  								<label for="">Appointment Date/Time<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-8">
  								<input type="text" class="w-130px datepicker" name="appointment_date" value="<?php echo $form_data['appointment_date']; ?>" readonly="">
  								<input type="text" class="w-65px timepicker" name="appointment_time" value="<?php echo $form_data['appointment_time']; ?>" readonly="">
                  <?php if(!empty($form_error)){ echo form_error('appointment_date'); } ?>
                  <?php if(!empty($form_error)){ echo form_error('appointment_time'); } ?>
  							</div>
  						</div>
  					</fieldset class="mb-10">
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-4">
  								<label for="">Call Date/Time<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-8">
  								<input type="text" class="w-130px datepicker" name="call_date" value="<?php echo $form_data['call_date']; ?>" readonly="">
  								<input type="text" class="w-65px timepicker" name="call_time" value="<?php echo $form_data['call_time']; ?>" readonly="">
                  <?php if(!empty($form_error)){ echo form_error('call_date'); } ?>
                  <?php if(!empty($form_error)){ echo form_error('call_time'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  				<div class="col-md-4"> 
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Follow-up Date/Time<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<input type="text" class="w-130px datepicker" name="followup_date" value="<?php echo $form_data['followup_date']; ?>" readonly="">
  								<input type="text" class="w-65px timepicker" name="followup_time" value="<?php echo $form_data['followup_time']; ?>" readonly="">
                  <?php if(!empty($form_error)){ echo form_error('followup_date'); } ?>
                  <?php if(!empty($form_error)){ echo form_error('followup_time'); } ?>
  							</div>
  						</div>
  					</fieldset>
            <fieldset>
              <div class="row">
                <div class="col-sm-3">
                  <label for="">Call Status<span class="star">*</span></label>
                </div>
                <div class="col-sm-9">
                  <div class="flex-input">
                  <select class="" id="call_status" name="call_status">
                        <option value="">Select Status</option>
                        <?php
                        if(!empty($call_status))
                        {
                            foreach($call_status as $status)
                            {
                                $status_select = '';
                                if($form_data['call_status']==$status['id'])
                                {
                                    $status_select = 'selected="selected"';
                                }
                                echo '<option '.$status_select.' value="'.$status['id'].'">'.$status['call_status'].'</option>';
                            }
                        }
                        ?>
                    </select>
                  <?php
                  if(in_array('2440',$users_data['permission']['action'])) 
                  {
                  ?>
                    <div><a href="javascript:void(0);" id="add_call_status" class="btn-new"><i class="fa fa-plus"></i> Add</a></div>
                  <?php
                  }
                  ?>  
                  </div>  
                    <?php if(!empty($form_error)){ echo form_error('call_status'); } ?>
                </div>
              </div>
            </fieldset>
  				</div>
  				<div class="col-md-4">
  					<fieldset>
  						<div class="row">
  							<div class="col-sm-3">
  								<label for="">Remarks<span class="star">*</span></label>
  							</div>
  							<div class="col-sm-9">
  								<textarea name="call_remark" id="" cols="30" rows="10"><?php echo $form_data['call_remark']; ?></textarea>
                  <?php if(!empty($form_error)){ echo form_error('call_remark'); } ?>
  							</div>
  						</div>
  					</fieldset>
  				</div>
  			</div>
  		</fieldset>
  	</div>
  	<div class="panel-footer text-right">
		<input type="submit" class="btn-update" name="submit" value="Save">
		<a href="<?php echo base_url('crm/leads'); ?>">
			<button type="button" class="btn-cancel">Cancel</button>
		</a>
  	</div>
  </div> 
</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
function set_lead_type(vals)
{

     $.ajax({
            url: "<?php echo base_url(); ?>crm/leads/specialization_list/", 
            success: function(result)
            {
              $('#specialization_id').html(result); 
            } 
          });

}

function get_doctor_specilization(specilization_id,branch_id)
{   

    if(typeof branch_id === "undefined" || branch_id === null)
    {
        $.ajax({url: "<?php echo base_url(); ?>crm/leads/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
    }
    else
    {

      $.ajax({url: "<?php echo base_url(); ?>crm/leads/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
      success: function(result)
      {
        $('#billing__attended_doctor').html(result); 
      } 
    });
   } 
}


    </script>  
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<!-- Confirmation Box -->
<div id="load_leads_modal_popup" class="modal fade" role="dialog" data-backdrop="dynamic" data-keyboard="true"></div>  
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>  
<script type="text/javascript">
function department_data(vals)
{ 
   
   if(vals==2)  //for OPD
   {
    $('.opd_booking_box').slideDown();
   }
   else
   {
    $('.opd_booking_box').slideUp();
   }

   if(vals==8) //LAB
   {
    $('.lab_booking_box').slideDown();
   }
   else
   {
    $('.lab_booking_box').slideUp();
   }

   if(vals==3) //opd Billing
   {
    $('.billing_booking_box').slideDown();
   }
   else
   {
    $('.billing_booking_box').slideUp();
   }

   if(vals==5)
   {
    $('.ipd_booking_box').slideDown();
   }
   else
   {
    $('.ipd_booking_box').slideUp();
   }

   if(vals==6)
   {
    $('.ot_booking_box').slideDown();
   }
    else
   {
    $('.ot_booking_box').slideUp();
   }
   if(vals==-1)
   {
    $('.vacci_booking_box').slideDown();
   }
    else
   {
    $('.vacci_booking_box').slideUp();
   }
   if(vals==-2)
   {
    $('.other_booking_box').slideDown();
   }
   else
   {
    $('.other_booking_box').slideUp();
   }
}

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});   

$('.timepicker').timepicker({

});



$('#dept_id').change(function(){
      var dept_id = $(this).val(); 
      $.ajax({url: "<?php echo base_url(); ?>crm/leads/dept_test_heads/"+dept_id, 
        success: function(result)
        { 
           $('#dept_parent_test').html(result);   
        } 
      }); 
});

$('#dept_parent_test').change(function(){  
      var head_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>crm/leads/test_list/"+head_id, 
        success: function(result)
        {
           $('#test_list').html(result); 
        } 
      }); 
  });

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

$('#test_search').keyup(delay(function (e) {
  console.log('Time elapsed!', this.value);
}, 500)); 

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      //callback.apply(context, args);
      test_search_list();
    }, ms || 0);
  };
}


  
 function test_search_list()
 {
      var search_text = $("#test_search").val();  
      var dept_id = $("#dept_id").val();  
      var test_head_id = $("#dept_parent_test").val();
      if(test_head_id=='' || test_head_id==null)
      {
        test_head_id = 0;
      }  
      if(dept_id=='')
      {
        dept_id = 0;
      } 
      if(search_text=='')
      {
        search_text = 0;
      }   
          
      var url ="<?php echo base_url(); ?>crm/leads/test_list";
      $.post(url,
        { test_head_id: test_head_id, profile_id: 0, search_text: search_text, dept_id: dept_id},
        function (msg) {
          $("#test_list").html(msg);

        });  
}   


function add_profile()
  {  
     var profile_id = $('#profile_id').val(); 
     
     var profile_price_updated= $('#profile_price').val();
     
     $.ajax({
         type:'POST',
      url: "<?php echo base_url(); ?>crm/leads/set_profile/"+profile_id, 
       data:{profile_price_updated:profile_price_updated},
      success: function(result)
      {
         if(result!=1)
         {
           $('#profile_error').html(result);
         } 
         else
         {
          set_total_test();
          $.ajax({url: "<?php echo base_url(); ?>crm/leads/list_booked_test/",
                success: function(result)
                { 
                   $('#test_select').html(result);
                   //payment_calc();
                } 
              });
         }
      } 
    }); 
  }  

  function get_profile_test()
  { 
     var profile_id = $("#profile_id").val();  
     var panel_id ='';
     ///// Profile Price
      $.ajax({url: "<?php echo base_url(); ?>crm/leads/profile_price/"+profile_id+'/'+panel_id,  
        success: function(result)
        {
           //$('#profile_price').html(result);  
            $('#profile_price').val(result); 
        } 
      }); 
  }

  function test_list_vals() 
  {          
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
       remove_test(allVals);
  } 

  function remove_test(allVals)
  {     
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('crm/leads/remove_booked_test');?>",
              data: {test_id: allVals},
              success: function(result) 
              {
                set_total_test();
                $('#test_select').html(result); 
                var head_id = $('#dept_parent_test').val();
                $.ajax({url: "<?php echo base_url(); ?>crm/leads/test_list/"+head_id, 
                  success: function(result)
                  {
                     $('#test_list').html(result); 
                     //payment_calc();
                     return false;
                  } 
                });  
              }
          });
   }
  }


  function child_test_vals() 
  {          
       var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       send_test(allVals);
  } 
 
  function send_test(event)
  {     
    event.preventDefault(); 
    $.ajax({
      url: "<?php echo base_url('crm/leads/set_booking_test'); ?>",
      type: "post",
      data: $('#booking_form').serialize(),
      success: function(result) 
      {  
        $('#test_select').html(result); 
        var head_id = $('#dept_parent_test').val();
        $.ajax({url: "<?php echo base_url(); ?>crm/leads/test_list/"+head_id, 
          success: function(result)
          { 
             set_total_test();
             $('#test_list').html(result); 
             //payment_calc();
          } 
        }); 
      }
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


  $(document).ready(function(){
  var $modal = $('#load_add_disease_modal_popup');
  $('#add_lead_type').on('click', function(){
  $modal.load('<?php echo base_url().'crm/lead_type/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });

  });

var $modals = $('#load_add_specialization_modal_popup');
$('#add_specilization_id').on('click', function(){
  $modals.load('<?php echo base_url().'specialization/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modals.modal('show');
  });

  });

   
  $('#add_call_status').on('click', function(){
  $modal.load('<?php echo base_url().'crm/call_status/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });

  });


  $('#add_lead_source').on('click', function(){
  $modal.load('<?php echo base_url().'crm/source/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });

  });

  var $omodals = $('#load_add_ot_management_modal_popup');
  $('#add_ot').on('click', function(){
  $omodals.load('<?php echo base_url().'ot_management/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $omodals.modal('show');
  });

  });
  
  var $amodals = $('#load_add_modal_popup');
  $('#add_attended_doctor').on('click', function(){
  $amodals.load('<?php echo base_url().'doctors/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $amodals.modal('show');
  });

  });




  });

   function set_total_test()
   { 
     $.ajax({
               url: "<?php echo base_url('crm/leads/set_total_payment'); ?>",  
               success: function(result)
               { 
                 $('#total_amount').val(result) 
               }
            }); 
   } 

   function set_home_collection(vals)
   {
     $.ajax({
               url: "<?php echo base_url('crm/leads/set_home_collection/'); ?>"+vals,  
               success: function(result)
               { 
                 if(vals==1)
                 {
                   $('#home_collection_amount').text(result); 

                 }
                 else
                 {
                    $('#home_collection_amount').text('0.00'); 
                 }
                 set_total_test();
                 
               }
            }); 
   }

   set_total_test();
</script>
</div><!-- container-fluid -->
<div id="load_add_ot_management_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_specialization_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>  
</script> 
<div class="overlay-loader"><img src="<?php echo base_url().'assets/images/loader.gif';?>"></div>