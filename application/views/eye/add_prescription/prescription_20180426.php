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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <form id="prescription_form" name="prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
    <!--  // prescription button modal -->


                <div class="row">
                    <div class="col-xs-2">
                        <!-- <button class="btn-commission2" type="button"  data-toggle="modal" data-target="#prescription_select_patient"> Select Patient</button> -->
                        <a class="btn-custom m-l-0 m-b-5" href="<?php echo base_url('opd'); ?>"><i class="fa fa-user"></i> <b>Registered Patient</b></a>
                        
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>OPD No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_code" value="<?php echo $form_data['booking_code']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                                <input type="text"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Patient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>">
                            </div>
                        </div>


                         <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="aadhaar_no" value="<?php echo $form_data['aadhaar_no']; ?>">
                                 <?php if(!empty($form_error)){ echo form_error('aadhaar_no'); } ?>
                            </div>
                        </div>
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                               <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Age</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
                              <input type="text" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
                              <input type="text" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                            </div>
                        </div>
                    </div> <!-- 5 -->
                </div> <!-- row -->

                <div class="row m-t-10">
                    <div class="col-xs-12">
                        <label>
                            <b>Template</b> 
                   <select name="template_list"  id="template_list" >
                        <option value="">Select Template</option>
                     <?php
                        if(isset($template_list) && !empty($template_list))
                        {
                          foreach($template_list as $templatelist)
                          {
                             echo '<option class="grp" value="'.$templatelist->id.'">'.$templatelist->template_name.'</option>';
                          }
                        }
                     ?>
                  </select>
                        </label> &nbsp;
                    </div>
                </div>



                <?php 

                $enable_setting = get_setting_value('ENABLE_VITALS'); 
                if($enable_setting==1)
                {
                ?>
                <div class="row m-t-10">
                    <div class="col-xs-12">
                        
                        <?php 
                    //print_r($vitals_list); exit;
                    if(!empty($vitals_list))
                    {
                      $i=0;
                      foreach ($vitals_list as $vitals) 
                      {
                        $vital_val = get_vitals_value($vitals->id,$form_data['booking_id'],1);
                        ?>
                          <label>
                            <b><?php echo $vitals->vitals_name; ?></b> 
                            <input type="text" name="data[<?php echo $vitals->id; ?>][name]"  class="w-50px input-tiny" value="<?php echo $vital_val; ?>"> 
                            <span><?php echo $vitals->vitals_unit; ?></span>
                        </label> &nbsp;

                        <?php

                        $i++;
                        if($i==6)
                        {
                           $i=0;
                           ?>
                           
                           <?php 
                        }
                      }
                    }
                    ?>  

                                         </div>
                </div> <!-- row -->
                <?php } ?>

                <div class="row m-t-10">
                    <div class="col-xs-11">
                        <ul class="nav nav-tabs" >
                        <?php 
                            $i=1; 
                            foreach ($prescription_tab_setting as $value) { 
                            ?>
                                <li <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
                                <?php 
                            $i++;
                            }
                         ?>
                          
                        </ul>

                       <?php 
                            $j=1; 
                            foreach ($prescription_tab_setting as $value) { 

                                 //echo "<pre>"; print_r($value->setting_name); exit;
                            ?>
                              <div class="tab-content">

                            <?php 
                            if(strcmp(strtolower($value->setting_name),'previous_history')=='0'){ ?>

                            <div id="tab_previous_history" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                             <textarea name="prv_history" id="prv_history" class="media_100"><?php echo $form_data['prv_history']; ?></textarea> 
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                           
                                            <select size="9" class="dropdown-box media_dropdown_box" name="prv_history_data"  id="prv_history_data" multiple="multiple" >
                                             <?php
                                                if(isset($prv_history) && !empty($prv_history))
                                                {
                                                  foreach($prv_history as $prvhistory)
                                                  {
                                                     echo '<option class="grp" value="'.$prvhistory->id.'">'.$prvhistory->prv_history.'</option>';
                                                  }
                                                }
                                             ?>
                                          </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php 

                             if(strcmp(strtolower($value->setting_name),'personal_history')=='0'){ ?>
                            <div id="tab_personal_history" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                            <textarea name="personal_history" id="personal_history" class="media_100"><?php echo $form_data['personal_history']; ?></textarea>  
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                        
                                            <select size="9" class="dropdown-box" name="personal_history_data"  id="personal_history_data" multiple="multiple" >
                                             <?php
                                                if(isset($personal_history) && !empty($personal_history))
                                                {
                                                  foreach($personal_history as $personalhistory)
                                                  {
                                                     echo '<option class="grp" value="'.$personalhistory->id.'">'.$personalhistory->personal_history.'</option>';
                                                  }
                                                }
                                             ?>
                                          </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <!-- new code for cheif complaint -->

                  <?php 
                  if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0'){ ?>
                  <div id="tab_chief_complaint" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                    <div class="row m-t-10">
                      <div class="col-xs-8">
                        <div class="well" id="">
                          <div class="grid-frame3" style="<?php if(!empty($cheif_template_data)){echo '';}else{echo 'display:none;';}?>" id="complain_grid">
                              <table width="100%" border="" id="chief_complaints" cellspacing="0" cellpadding="0" class="grid_tbl">
                                <tr>
                                <td align="left" height="30"><b>Complaint Description</b></td>
                                <td align="center" height="30"><b>L</b></td>
                                <td align="center" height="30"><b>R</b></td>
                                <td align="center" height="30" colspan="2"><b>Duration</b></td>
                                </tr>


                                <tr>
                                <td align="center" height="30"></td>
                                <td align="center" height="30"></td>
                                <td align="center" height="30"></td>
                                <td align="center" height="30"><b>Days</b></td>
                                <td align="center" height="30"><b>Time</b></td>
                                </tr>


                              <?php if(!empty($cheif_template_data)) {$i=1;
                              foreach($cheif_template_data as $cheif_data)
                              {


                              ?>
                                <tr>
                                <td align="left" style="text-align:left;" height="30"><input type="text" name="cheif_complains[<?php echo $i; ?>][cheif_complain_name]" value="<?php echo $cheif_data->cheif_complains;?>"/></td>


                                <td align="center" height="30">
                                <input type="checkbox" class="w-40px" value="1" <?php if(isset($cheif_data->left_eye)&& $cheif_data->left_eye==1){echo 'checked';}?> name="cheif_complains[<?php echo $i; ?>][cheif_c_left]">
                                </td>


                                <td align="center" height="30">
                                <input type="checkbox" class="w-40px" value="2" <?php if(isset($cheif_data->right_eye)&& $cheif_data->right_eye==2){echo 'checked';}?> name="cheif_complains[<?php echo $i; ?>][cheif_c_right]">

                                </td>


                                <td align="center" height="30">
                                <select class="w-60px" id="" name="cheif_complains[<?php echo $i; ?>][cheif_c_days]">
                                <option value="1" <?php if(isset($cheif_data->days) && $cheif_data->days==1){echo 'selected';}?>>1</option>
                                <option value="2" <?php if(isset($cheif_data->days)&& $cheif_data->days==2){echo 'selected';}?>>2</option>
                                <option value="3" <?php if(isset($cheif_data->days)&& $cheif_data->days==3){echo 'selected';}?>>3</option>
                                <option value="4" <?php if(isset($cheif_data->days)&& $cheif_data->days==4){echo 'selected';}?>>4</option>
                                <option value="5" <?php if(isset($cheif_data->days)&& $cheif_data->days==5){echo 'selected';}?>>5</option>
                                <option value="6" <?php if(isset($cheif_data->days)&& $cheif_data->days==6){echo 'selected';}?>>6</option>
                                <option value="7" <?php if(isset($cheif_data->days)&& $cheif_data->days==7){echo 'selected';}?>>7</option>
                                <option value="8" <?php if(isset($cheif_data->days)&& $cheif_data->days==8){echo 'selected';}?>>8</option>
                                <option value="9" <?php if(isset($cheif_data->days)&& $cheif_data->days==9){echo 'selected';}?>>9</option>
                                </select>
                                </td>
                                <td align="center" height="30">
                                <select class="w-60px" name="cheif_complains[<?php echo $i; ?>][cheif_c_time]">
                                <option value="1" <?php if(isset($cheif_data->time)&& $cheif_data->time==1){echo 'selected';}?>>Days</option>
                                <option value="2" <?php if(isset($cheif_data->time)&& $cheif_data->time==2){echo 'selected';}?>>Week</option>
                                <option value="3" <?php if(isset($cheif_data->time)&& $cheif_data->time==3){echo 'selected';}?>>Month</option>
                                <option value="4" <?php if(isset($cheif_data->time)&& $cheif_data->time==4){echo 'selected';}?>>Year</option>
                                </select>

                                </td>

                                <td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('');"><i class="fa fa-trash"></i> Delete</a></td>
                                </tr>
                              <?php $i++;} } ?>
                              </table>
                          </div>
                        <!-- <h4><?php //if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea name="chief_complaints" id="chief_complaints" class="media_100"><?php //echo $form_data['chief_complaints']; ?></textarea>  -->      
                        </div>
                      </div>
                    <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                    <!-- mamta code here -->



                    <!--mamta code here -->
                    <!-- <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div>
                    <div class="grp">DSHPT1</div> -->

                    <select size="9" class="dropdown-box chief_complaints_data" name="chief_complaints_data"  id="chief_complaints_data" multiple="multiple" >
                    <?php
                    if(isset($chief_complaints) && !empty($chief_complaints))
                    {
                    foreach($chief_complaints as $chiefcomplaints)
                    {
                    echo '<option class="grp" value="'.$chiefcomplaints->id.'">'.$chiefcomplaints->chief_complaints.'</option>';
                    }
                    }
                    ?>
                    </select>

                    </div>
                    </div>
                    </div>
                  </div>
                  <?php } ?>

                            <!-- new code for cheif complaint -->

                            <!--new ocde for systemic illness -->
                   <?php  if(strcmp(strtolower($value->setting_name),'systemic_illness')=='0'){ 
                    ?>
                  <div id="tab_systemic_illness" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                      <div class="row m-t-10">
                          <div class="col-xs-8">
                              <div class="well">
                                   <div class="grid-frame3" style="<?php if(!empty($systemic_illness_template_data)){echo '';}else{echo 'display:none;';}?>" id="systemic_illness_grid">
                                  <table width="100%" border="" id="systemic_illness" cellspacing="0" cellpadding="0" class="grid_tbl">
                                  <tr>
                                    <td align="left" height="30"><b>Diagnosis</b></td>

                                    
                                    <td align="center" height="30" colspan="2"><b>Duration</b></td>
                                  </tr>
                                  <tr>
                                    <td align="center" height="30"></td>
                                    
                                    <td align="center" height="30"><b>Days</b></td>
                                    <td align="center" height="30"><b>Time</b></td>
                                  </tr>
                                  <?php if(!empty($systemic_illness_template_data)) {
                                  foreach($systemic_illness_template_data as $systemic_illness_data)
                                   {


                                    ?>
                                    <tr><td align="left" style="text-align:left;" height="30"><input type="text" name="systemic_illness[]" value="<?php echo $systemic_illness_data->systemic_illness;?>"/></td>
                                
                                  
                                      <td align="center" height="30">
                                          <select class="w-60px" id="" name="systemic_illness_days[]">
                                              <option value="1" <?php if(isset($systemic_illness_data->days) && $systemic_illness_data->days==1){echo 'selected';}?>>1</option>
                                              <option value="2" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==2){echo 'selected';}?>>2</option>
                                              <option value="3" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==3){echo 'selected';}?>>3</option>
                                              <option value="4" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==4){echo 'selected';}?>>4</option>
                                              <option value="5" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==5){echo 'selected';}?>>5</option>
                                              <option value="6" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==6){echo 'selected';}?>>6</option>
                                              <option value="7" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==7){echo 'selected';}?>>7</option>
                                              <option value="8" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==8){echo 'selected';}?>>8</option>
                                              <option value="9" <?php if(isset($systemic_illness_data->days)&& $systemic_illness_data->days==9){echo 'selected';}?>>9</option>
                                          </select>
                                      </td>
                                      <td align="center" height="30">
                                          <select class="w-60px" name="systemic_illness_time[]">
                                              <option value="1" <?php if(isset($systemic_illness_data->time)&& $systemic_illness_data->time==1){echo 'selected';}?>>Days</option>
                                              <option value="2" <?php if(isset($systemic_illness_data->time)&& $systemic_illness_data->time==2){echo 'selected';}?>>Week</option>
                                              <option value="3" <?php if(isset($systemic_illness_data->time)&& $systemic_illness_data->time==3){echo 'selected';}?>>Month</option>
                                              <option value="4" <?php if(isset($systemic_illness_data->time)&& $systemic_illness_data->time==4){echo 'selected';}?>>Year</option>
                                          </select>
                                          <a class="btn-custom btnDelete" onclick="remove_row('');"><i class="fa fa-trash"></i> Delete</a>
                                      </td>
                                  </tr>
                                  <?php } } ?>
                                </table>
                              </div>

                           <!--    <h4><?php //if(!empty($value->setting_value)) { //echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                              <textarea name="diagnosis" id="diagnosis" class="media_100"><?php //echo $form_data['diagnosis']; ?></textarea>  -->
                              </div>
                          </div>
                          <div class="col-xs-4">
                              <div class="well tab-right-scroll">
                                  <!-- <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div>
                                  <div class="grp">DSHPT1</div> -->
                                  <select size="9" class="dropdown-box systemic_illness_data" name="systemic_illness_data"  id="systemic_illness_data" multiple="multiple" >
                                   <?php
                                      if(isset($systemic_illness_list) && !empty($systemic_illness_list))
                                      {
                                        foreach($systemic_illness_list as $systemic_illnes)
                                        {
                                           echo '<option class="grp" value="'.$systemic_illnes->id.'">'.$systemic_illnes->systemic_illness.'</option>';
                                        }
                                      }
                                   ?>
                                </select>

                              </div>
                          </div>
                      </div>
                  </div>
                  <?php } ?>

                            <!-- new code for systemic illness -->

                            <!-- new code for diagnosisi -->

                      <?php  if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){  ?>
                      <div id="tab_diagnosis" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                          <div class="row m-t-10">
                              <div class="col-xs-10">
                                  <div class="well">
                                       <div class="grid-frame3" style="<?php if(!empty($diagnosis_template_data)){echo '';}else{echo 'display:none;';}?>" id="diagnosis_grid">
                                      <table width="100%" border="" id="diagnosis" cellspacing="0" cellpadding="0" class="grid_tbl">
                                      <tr>
                                        <td align="left" height="30"><b>Diagnosis</b></td>
                                        <td align="center" height="30"><b>L</b></td>
                                        <td align="center" height="30"><b>R</b></td>
                                        <td align="center" height="30" colspan="2"><b>Duration</b></td>
                                      </tr>
                                      <tr>
                                        <td align="center" height="30"></td>
                                        <td align="center" height="30"></td>
                                        <td align="center" height="30"></td>
                                        <td align="center" height="30"><b>Days</b></td>
                                        <td align="center" height="30"><b>Time</b></td>
                                      </tr>
                                      <?php if(!empty($diagnosis_template_data)) { $i=1;
                                      foreach($diagnosis_template_data as $diagnosis_data)
                                       {


                                        ?>
                                        <tr><td align="left" style="text-align:left;" height="30"><input type="text" name="diagnosis[<?php echo $i; ?>][diagnosis_name]" value="<?php echo $diagnosis_data->diagnosis;?>"/></td>
                                          <td align="center" height="30">
                                           <input type="checkbox" class="w-40px" value="1" <?php if(isset($diagnosis_data->left_eye)&& $diagnosis_data->left_eye==1){echo 'checked';}?> name="diagnosis[<?php echo $i; ?>][diagnosis_left]"> 
                                          </td>
                                          <td align="center" height="30">
                                         

                                          <input type="checkbox" class="w-40px" value="2" <?php if(isset($diagnosis_data->right_eye)&& $diagnosis_data->right_eye==2){echo 'checked';}?> name="diagnosis[<?php echo $i; ?>][diagnosis_right]">

                                          </td>
                                          <td align="center" height="30">
                                              <select class="w-60px" id="" name="diagnosis[<?php echo $i; ?>][diagnosis_days]">
                                                  <option value="1" <?php if(isset($diagnosis_data->days) && $diagnosis_data->days==1){echo 'selected';}?>>1</option>
                                                  <option value="2" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==2){echo 'selected';}?>>2</option>
                                                  <option value="3" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==3){echo 'selected';}?>>3</option>
                                                  <option value="4" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==4){echo 'selected';}?>>4</option>
                                                  <option value="5" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==5){echo 'selected';}?>>5</option>
                                                  <option value="6" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==6){echo 'selected';}?>>6</option>
                                                  <option value="7" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==7){echo 'selected';}?>>7</option>
                                                  <option value="8" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==8){echo 'selected';}?>>8</option>
                                                  <option value="9" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==9){echo 'selected';}?>>9</option>
                                              </select>
                                          </td>
                                          <td align="center" height="30">
                                              <select class="w-60px" name="diagnosis[<?php echo $i; ?>][diagnosis_time]">
                                                  <option value="1" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==1){echo 'selected';}?>>Days</option>
                                                  <option value="2" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==2){echo 'selected';}?>>Week</option>
                                                  <option value="3" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==3){echo 'selected';}?>>Month</option>
                                                  <option value="4" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==4){echo 'selected';}?>>Year</option>
                                              </select>
                                              
                                          </td>
                                          <td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('');"><i class="fa fa-trash"></i> Delete</a></td>
                                      </tr>
                                      <?php $i++;} } ?>
                                    </table>
                                  </div>

                               <!--    <h4><?php //if(!empty($value->setting_value)) { //echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                  <textarea name="diagnosis" id="diagnosis" class="media_100"><?php //echo $form_data['diagnosis']; ?></textarea>  -->
                                  </div>
                              </div>
                              <div class="col-xs-4">
                                  <div class="well tab-right-scroll">
                                      <!-- <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div>
                                      <div class="grp">DSHPT1</div> -->
                                      <select size="9" class="dropdown-box diagnosis_data" name="diagnosis_data"  id="diagnosis_data" multiple="multiple" >
                                       <?php
                                          if(isset($diagnosis_list) && !empty($diagnosis_list))
                                          {
                                            foreach($diagnosis_list as $diagnosislist)
                                            {
                                               echo '<option class="grp" value="'.$diagnosislist->id.'">'.$diagnosislist->diagnosis.'</option>';
                                            }
                                          }
                                       ?>
                                    </select>

                                  </div>
                              </div>
                          </div>
                      </div>
                      <?php } ?>

                            <!-- new code for diagnosis-->




                        
                             <?php if(strcmp(strtolower($value->setting_name),'examination')=='0'){  ?>
                            <div id="tab_examination" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-10">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                          <textarea name="examination" id="examination" class="media_100"><?php echo $form_data['examination']; ?></textarea>   
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <select size="9" class="dropdown-box" name="examination_data"  id="examination_data" multiple="multiple" >
                                             <?php
                                                if(isset($examination_list) && !empty($examination_list))
                                                {
                                                  foreach($examination_list as $examinationlist)
                                                  {
                                                     echo '<option class="grp" value="'.$examinationlist->id.'">'.$examinationlist->examination.'</option>';
                                                  }
                                                }
                                             ?>
                                          </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!-- pictorial test -->

                            <?php if(strcmp(strtolower($value->setting_name),'pictorial_test')=='0'){  ?>
                            <div id="tab_pictorial_test" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well">

                                            <div class="row">
                                            <div class="col-lg-5">
                                            <div class="row">
                                            <div class="col-sm-6">
                                              <table border="0" cellpadding="0" cellspacing="0" class="eye_tbl">
                                              <tr>
                                                <td align="center">Right Eye</td>
                                                </tr>
                                              <tr>
                                                <td align="center">
                                                <div class="eyeBox">

                                                  <?php
                                                  $img_path = base_url('assets/images/photo.png');
                                                  if(!empty($form_data['data_id']) && !empty($form_data['old_img_right'])){
                                                  $img_path = ROOT_UPLOADS_PATH.'eye/right_eye_image/'.$form_data['old_img_right'];
                                                  }  
                                                  ?>
                                                  <img id="pimg2" src="<?php echo $img_path; ?>" class="img-responsive">



                                                  <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
                                                   
                                                </div>

                                                <?php
                                                if(isset($photo_error_right) && !empty($photo_error_right)){
                                                   echo '<div class="text-danger">'.$photo_error_right.'</div>';
                                                }
                                                ?>
                                                </td>
                                                </tr>
                                              <tr>
                                                <td align="center">
                                              
                                                 <input type="hidden" name="old_img_right"  value="<?php echo $form_data['old_img_right']; ?>" />
                                                <input type="file" id="img-input2" accept="image/*" name="right_eye_file" id="eye_custom_select">
                                                </td>
                                              </tr>
                                              </table>
                                            </div>
                                              <div class="col-sm-6">
                                                <table border="0" cellpadding="0" cellspacing="0" class="eye_tbl">
                                                  <tr>
                                                    <td align="center">Left Eye</td>
                                                    </tr>
                                                    <tr>
                                                    <td align="center">
                                                    <div class="eyeBox">



                                                      <?php
                                                    $img_path = base_url('assets/images/photo.png');
                                                      if(!empty($form_data['data_id']) && !empty($form_data['old_img_left'])){
                                                      $img_path = ROOT_UPLOADS_PATH.'eye/left_eye_image/'.$form_data['old_img_left'];
                                                      }  
                                                      ?>
                                                      <img id="pimg" src="<?php echo $img_path; ?>" class="img-responsive">




                                                    <input type="hidden" id="capture_img_left_image" name="capture_img_left_image" value="" />
                                                  
                                                      

                                                    </div>
                                                    <?php
                                                    if(isset($photo_error_left) && !empty($photo_error_left)){
                                                    echo '<div class="text-danger">'.$photo_error_left.'</div>';
                                                    }
                                                    ?>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td align="center">
                                                    <!--<label for="eye_custom_select" class="eye_custom_select"><i class="fa fa-eye"></i> Browse</label>-->
                                                    <input type="file" id="img-input" accept="image/*" name="left_eye_file" id="eye_custom_select">
                                                     <input type="hidden" name="old_img_left"  value="<?php echo $form_data['old_img_left']; ?>" />
                                                    </td>
                                                  </tr>
                                                </table>
                                              </div>
                                            </div>
                                            </div>
                                            <div class="col-lg-7">

                                            <div class="row">
                                            <div class="col-sm-12">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="eye_tbl">
                                            <tr>
                                            <td align="left">Right Eye</td>
                                            </tr>
                                            <tr>
                                            <td align="left">
                                            <textarea type="text" class="form-control" name="right_eye_dicussion" ><?php echo $form_data['right_eye_dicussion']; ?></textarea>
                                            </td>
                                            </tr> 
                                            </table>
                                            </div>
                                            </div>

                                            <div class="row">
                                            <div class="col-sm-12">
                                            <table width="100%" border="0" cellpadding="0" cellspacing="0" class="eye_tbl">
                                            <tr>
                                            <td align="left">Left Eye</td>
                                            </tr>
                                            <tr>
                                            <td align="left">
                                            <textarea type="text" class="form-control" name="left_eye_dicussion"><?php echo $form_data['left_eye_dicussion']; ?></textarea>
                                            </td>
                                            </tr> 
                                            </table>
                                            </div>
                                            </div>

                                            </div>
                                            </div>
                                        </div>
                                    </div>
                               
                                </div>
                            </div>
                            <?php } ?>
                            <!--pictorial test -->

                             <?php if(strcmp(strtolower($value->setting_name),'biometric_detail')=='0'){  ?>
                            <div id="tab_biometric_detail" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                  <div class="col-xs-12">
                                <div class="well" style="float:left;width:100%;">
                                  <form method="post" id="biometric_form" >
                                    <input type="hidden" name="patient_id" value="<?php echo $booking_data['patient_id']; ?>" >
                                    <input type="hidden" name="branch_id" value="<?php echo $booking_data['branch_id']; ?>" >
                                    <input type="hidden" name="opd_booking_id" value="<?php echo $booking_data['id']; ?>" >
                                      <div class="grid-frame2">
                                        <div class="grid-box" style="margin-left:21%;width: 57%;">
                                        <h5>DVA</h5>
                                        <div class="tbl_responsive">
                                        <table class="tbl_grid" cellpadding="0" cellspacing="0" border="1">
                                        <tr>
                                        <td rowspan="3" width="50" align="center" valign="bottom">R</td> 
                                        <td colspan="2" align="center" height="30">UCVA</td> 
                                        <td colspan="7" align="center" height="30">BCVA</td> 
                                        </tr>
                                        <tr>
                                        <td align="center" height="30">NVA</td>
                                        <td align="center" height="30">DVA</td>
                                        <td align="center" height="30">SPH</td>
                                        <td align="center" height="30">CYL</td>
                                        <td align="center" height="30">AXIS</td>
                                        <td align="center" height="30">ADD</td>
                                        <td align="center" height="30">DVA</td>
                                        <td align="center" height="30">NVA</td>
                                        </tr>
                                        <tr>  
                                        <td height="30">
                                        <input type="text" class="input-responsive ucva w-50px" name="ucva_nva_right" id="ucva_nva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_nva_right; } ?>" ></td>
                                        <td><input type="text" class="input-responsive ucva w-50px" name="ucva_dva_right" id="ucva_dva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_dva_right; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_sph_right" id="bcva_sph_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_sph_right; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_cyl_right" id="bcva_cyl_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_cyl_right; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_axis_right" id="bcva_axis_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_axis_right; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_add_right" id="bcva_add_right"  value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_add_right; } ?>"></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_dva_right" id="bcva_dva_right"  value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_dva_right; } ?>"></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_nva_right" id="bcva_nva_right" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_nva_right; } ?>" ></td> 
                                        </tr>
                                        <tr> 
                                        <td width="50" height="30" align="center">L</td>
                                        <td><input type="text" class="input-responsive ucva w-50px" name="ucva_nva_left" id="ucva_nva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_nva_left; } ?>" ></td>
                                        <td><input type="text" class="input-responsive ucva w-50px" name="ucva_dva_left" id="ucva_dva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->ucva_dva_left; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_sph_left" id="bcva_sph_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_sph_left; } ?>"  ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_cyl_left" id="bcva_cyl_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_cyl_left; } ?>"  ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_axis_left" id="bcva_axis_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_axis_left; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_add_left" id="bcva_add_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_add_left; } ?>" ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_dva_left" id="bcva_dva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_dva_left; } ?>"  ></td>
                                        <td><input type="text" class="input-responsive bcva w-50px" name="bcva_nva_left" id="bcva_nva_left" value="<?php if($ucva_bcva_data!='empty') { echo $ucva_bcva_data[0]->bcva_nva_left; } ?>" ></td> 
                                        </tr>
                                        </table>
                                        </div>
                                      </div>
                                    </div>
                                  <!-- /// Bottom grids -->


                                  <div class="grid-frame2">



                                  <div class="grid-box" style="width:40%;height:260px;border:1px solid #aaa;padding:0px;margin-left:120px;overflow-y:auto;">
                                  <div class="grid-head">Keratometer Readings</div>
                                  <div class="grid-body">
                                  <?php if($keratometer_data!="empty") { ?>
                                  <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                  <tr>
                                  <td width="15%" height="30"  align="center">RE</td>
                                  <td></td>
                                  <td width="15%" height="30"  align="center">LE</td>
                                  </tr>

                                  <?php 
                                  $i=1;

                                  foreach($keratometer_data as $data) 
                                  { 
                                  $right_val="";
                                  $left_val="";
                                  if($keratometer_details!="empty")
                                  {
                                  foreach($keratometer_details as $dt)
                                  {
                                  if($dt->kera_id==$data->id)
                                  {
                                  $right_val=$dt->right_eye;
                                  $left_val=$dt->left_eye;
                                  }
                                  } 
                                  }
                                  ?>
                                  <tr style='margin-top:15px;'>
                                  <td width="15%"  align="center"><input type="text" name="kera_re[<?php echo $data->id; ?>]" class="w-60px" 
                                  value="<?php  echo $right_val;   ?>" ></td>
                                  <td align="center"><?php echo $data->keratometer; ?></td>
                                  <td width="15%"  align="center"><input type="text" name="kera_le[<?php echo $data->id; ?>]" class="w-60px" value="<?php  echo $left_val;   ?>" ></td>
                                  </tr>
                                  <?php $i++; } ?>
                                  </table>
                                  <?php } ?> 
                                  </div>
                                  </div>





                                  <div class="grid-box" style="width:40%;height:260px;border:1px solid #aaa;padding:0px;margin-left:20px;overflow-y:auto;">
                                  <div class="grid-head">IOL Section</div>
                                  <div class="grid-body">
                                  <?php if($iol_data!="empty") { ?>
                                  <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                  <tr>
                                  <td width="15%" height="30" align="center">RE</td>
                                  <td></td>
                                  <td width="15%" height="30"  align="center">LE</td>
                                  </tr>
                                  <?php foreach($iol_data as $iol) 
                                  { 
                                  $right_val="";
                                  $left_val="";
                                  if($iol_details!="empty")
                                  {
                                  foreach($iol_details as $dt)
                                  {
                                  if($dt->iol_id==$iol->id)
                                  {
                                  $right_val=$dt->right_eye;
                                  $left_val=$dt->left_eye;
                                  }
                                  } 
                                  }
                                  ?>
                                  <tr>
                                  <td width="15%" align="center"><input type="text" name="iol_re[<?php echo $iol->id; ?>]" class="w-60px" value="<?php echo $right_val; ?>" ></td>
                                  <td align="center"><?php echo $iol->iol_section; ?></td>
                                  <td width="15%" align="center"><input type="text" name="iol_le[<?php echo $iol->id; ?>]" class="w-60px" value="<?php echo $left_val; ?>" ></td>
                                  </tr>
                                  <?php } ?>
                                  </table>
                                  <?php } ?>
                                  </div>
                                  </div>
                                  </form>
                                </div>
                                  </div>
                                </div>
                            </div>
                            <?php } ?>
                         
                             <?php if(strcmp(strtolower($value->setting_name),'test_result')=='0'){  ?>
                            <div id="tab_test_result" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table">
                                                <tbody>
                                                    <tr>
                                                        <td><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>
                                                        </td>
                                                    </tr>
                                                    <?php $i=1;if(!empty($prescription_test_list))
                                                        {
                                                      foreach($prescription_test_list as $test_list) {?>
                                                        <tr>
                                                        <td><input type="text" name="test_name[]" class="w-100 test_val<?php echo $i; ?>" value="<?php echo $test_list->test_name;?>"></td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php $i++;} } ?>
                                                


                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php if(strcmp(strtolower($value->setting_name),'prescription')=='0'){  ?>
                            <div id="tab_prescription" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="prescription_name_table">
                                                <tbody>
                                                    <tr>
                                                      <td>Eye Drop</td>
                    <?php 
                    $m=0;
                      //print '<pre>'; print_r($prescription_medicine_tab_setting);die;
                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                            <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                                <?php 
                           $m++; 
                            }
                            ?>
                            <td width="80">
                                <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                            </td>
                            </tr>

    <?php if(!empty($prescription_presc_list)) { 
    $l=1; //print_r($prescription_presc_list);
    foreach ($prescription_presc_list as $prescriptiontemplate) {
    //print '<pre>'; print_r($prescriptiontemplate);
    ?>

    <tr>
    <td ><input type="checkbox" name="is_eye_drop" id="is_eye_drop<?php echo $l;?>" value="1" onclick="check_eye_drop(<?php echo $l ?>);"/></td>

    <?php
   //print '<pre>'; print_r($prescription_medicine_tab_setting);
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
      <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="w-100px" id="medicine_name<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_name; ?>"></td>
    <?php }?>

    <?php if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" class="w-100px" id="salt<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_salt; ?>"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_company]" class="w-100px" id="medicine_company<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_brand; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
    { ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" class="input-small w-100px" id="type<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_type; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    { ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class="input-small w-100px dosage_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_dose; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    {  ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class="medicine-name duration_val<?php echo $l; ?> w-100px" value="<?php echo $prescriptiontemplate->medicine_duration; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="medicine-name frequency_val<?php echo $l; ?> w-100px" value="<?php echo $prescriptiontemplate->medicine_frequency; ?>"></td>
    <?php 
    } 


    if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
    {
    ?>
    <td class="right_eye_appned<?php echo $l; ?>"><input type="checkbox" name="prescription[<?php echo $l; ?>][medicine_right_eye]" value="2" class="medicine-name  right_eye_val<?php echo $l; ?>" <?php if($prescriptiontemplate->right_eye==2){echo 'checked';}?>></td>
    <?php 
    }


     if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
    {
    ?>
    <td class="left_eye_appned<?php echo $l; ?>"><input type="checkbox" name="prescription[<?php echo $l; ?>][medicine_left_eye]" value="1" class="medicine-name  left_eye_val<?php echo $l; ?>" <?php if($prescriptiontemplate->left_eye==1){echo 'checked';}?>></td>
    <?php 
    }

    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="medicine-name advice_val<?php echo $l; ?> w-100px" value="<?php echo $prescriptiontemplate->medicine_advice; ?>"></td>
    <?php } 


    if(strcmp(strtolower($tab_value->setting_name),'date')=='0')
    { 
      
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_date]" class="datepicker_m medicine-name date_val<?php echo $l; ?> w-100px" value="<?php if(isset($prescriptiontemplate->date) && ($prescriptiontemplate->date =='1970-01-01' || $prescriptiontemplate->date=='0000-00-00')){echo '';} else {echo date('d-m-Y',strtotime($prescriptiontemplate->date));}?>"></td>
  <?php } 
  }
  ?>        

        <script type="text/javascript">
                          
                    /* script start */
                      $(function () 
                      {
                            var getData = function (request, response) { 
                              row = <?php echo $l; ?> ;
                              $.ajax({
                              url : "<?php echo base_url('eye/prescription_template/get_eye_medicine_auto_vals/'); ?>" + request.term,
                              dataType: "json",
                              method: 'post',
                            data: {
                               name_startsWith: request.term,
                               type: 'country_table',
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

                                var names = ui.item.data.split("|");

                                $('.medicine_val'+<?php echo $l; ?>).val(names[0]);
                                $('#type'+<?php echo $l; ?>).val(names[1]);
                                $('#medicine_company'+<?php echo $l; ?>).val(names[3]);
                                $('#salt'+<?php echo $l; ?>).val(names[2]);
                              //$(".medicine_val").val(ui.item.value);
                              return false;
                          }

                          $(".medicine_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });


                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".dosage_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".dosage_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".duration_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".duration_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });
                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".frequency_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".frequency_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".advice_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".advice_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });
                              /* script end*/

                        </script>

                <td width="80">
                    <a href="javascript:void(0)" onclick="delete_prescription_medicine('<?php echo $prescriptiontemplate->id; ?>,<?php echo $form_data['data_id']; ?>');" class="btn-w-60">Delete</a>
                </td>
                </tr>

                <?php     
                $l++; }
            }?>
                 
              </tbody>
              </table>
          </div>
      </div>
  </div>
</div>

                            <?php } ?>
                             <?php  if(strcmp(strtolower($value->setting_name),'suggestions')=='0'){  ?>
                            <div id="tab_suggestions" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-10">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                               <textarea name="suggestion" id="suggestion" class="media_100"><?php echo $form_data['suggestion']; ?></textarea>  
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                            <!-- <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div> -->
                                            <select size="9" class="dropdown-box" name="suggestion_data"  id="suggestion_data" multiple="multiple" >
                                             <?php
                                                if(isset($suggetion_list) && !empty($suggetion_list))
                                                {
                                                  foreach($suggetion_list as $suggestionlist)
                                                  {
                                                     echo '<option class="grp" value="'.$suggestionlist->id.'">'.$suggestionlist->medicine_suggetion.'</option>';
                                                  }
                                                }
                                             ?>
                                          </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php if(strcmp(strtolower($value->setting_name),'remarks')=='0'){   ?>

                            <div id="tab_remarks" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                                <div class="row m-t-10">
                                    <div class="col-xs-1">
                                        <label><b><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></b></label>
                                    </div>
                                    <div class="col-xs-11">
                                        <textarea class="form-control" rows="8" id="remark" name="remark"><?php echo $form_data['remark']; ?></textarea>
                                    </div>
                                </div>
                                </div>
                            </div>
                             <?php } ?>
                             <?php if(strcmp(strtolower($value->setting_name),'appointment')=='0'){    ?>
                            <div id="tab_appointment" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                                    <div class="row m-t-10">
                                        <div class="col-xs-2">
                                            <label><b><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></b></label>
                                        </div>
                                        <div class="col-xs-10">
                                            <input type="checkbox" name="">
                                            <input type="text" name="next_appointment_date"  class="datepicker date "  data-date-format="dd-mm-yyyy HH:ii"  value="<?php echo $form_data['next_appointment_date']; ?>" /> 
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php } ?>
                     
                      </div>



                                <?php 
                            $j++;
                            }
                         ?>


                        
                    </div> <!-- 11 -->
                    </div> <!-- 11 -->
                




                    
            <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">
             <input type="hidden" name="appointment_date" value="<?php echo $form_data['appointment_date']; ?>">
              <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
           <div class="col-xs-1">
            <div class="prescription_btns">
                <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
               <!--  <button class="btn-save" type="button"  onclick="window.location.href='<?php //echo base_url('opdprescriptionhistory/lists/'.$form_data['patient_id']); ?>'" name=""><i class="fa fa-history"></i> History</button> -->
                <a href="<?php echo base_url('prescription'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
            </div>


            </div> <!-- row -->
        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">
$('.datepicker_m').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });
</script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script>
function remove_row(row_val)
{

    $("#chief_complaints").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".chief_complaints_data option[value='"+row_val+"']").show();
    
}
  function tab_links(vals)
  {
    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('in');
    $('#'+vals).addClass('active');
  }

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_template_data/"+template_id, 
        success: function(result)
        {
          //alert();
           load_values(result);
           load_test_values(template_id);
           load_systemic_illness(template_id);
           load_diagnosis(template_id);
           load_cheif_complain(template_id);
           load_prescription_values(template_id);
        } 
      }); 
  });


  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       /*$('#patient_bp').val(obj.patient_bp);
       $('#patient_temp').val(obj.patient_temp);
       $('#patient_weight').val(obj.patient_weight);
       $('#patient_height').val(obj.patient_height);
       $('#patient_spo2').val(obj.patient_spo2);*/
       $('#prescription_medicine').val(obj.prescription_medicine);
       $('#prv_history').val(obj.prv_history);
       $('#personal_history').val(obj.personal_history);
       //$('#chief_complaints').val(obj.chief_complaints);
       $('#examination').val(obj.examination);
       //$('#diagnosis').val(obj.diagnosis);
       $('#suggestion').val(obj.suggestion);
       $('#remark').val(obj.remark);
       $('#appointment_date').val(obj.appointment_date);
       

       
    };


   function load_cheif_complain(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_cheif_complain_data/"+template_id, 
        success: function(result)
        {
           
           get_cheif_complain_values(result);
        } 
      });
    }
    function load_systemic_illness(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_systemic_illness_data/"+template_id, 
        success: function(result)
        {
          
           get_systemic_illness_values(result);
        } 
      });
    }
    function load_diagnosis(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_diagnosis_data/"+template_id, 
        success: function(result)
        {
          
           get_diagnosis_data_values(result);
        } 
      });
    }

    function load_test_values(template_id)
    {

        $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_template_test_data/"+template_id, 
        success: function(result)
        {
           get_test_values(result);
        } 
      });
    }

    
/*<a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>*/

/* script for cheif compalin */
function get_cheif_complain_values(result)
    {
     
      var obj = JSON.parse(result);
   
      var arr = '';
      var rowCount = $('#chief_complaints tr').length;
      var newrowcount=rowCount-1;
      var i=newrowcount;
      var slectedoption9='';
      var slectedoption8='';
      var slectedoption7='';
      var slectedoption6='';
      var slectedoption5='';
      var slectedoption4='';
      var slectedoption3='';
      var slectedoption2='';
   
      $.each(obj, function (index, value) {
      //alert(i);
       // $('.cheif_option_complains'+i+' option[value='+obj[index].days+']').attr('selected','selected');
       if(obj[index].days==1)
       {
        var slectedoption1='selected="selected"';
       }
       else
       {
         var slectedoption1='';
       }
       if(obj[index].days==2)
       {
        var slectedoption2='selected="selected"';
       }
       else
       {
         var slectedoption2='';
       }

       if(obj[index].days==3)
       {
        var slectedoption3='selected="selected"';
       }
       else
       {
        var slectedoption3='';
       }
       if(obj[index].days==4)
       {
        var slectedoption4='selected="selected"';
       }
       else
       {
        var slectedoption4='';
       }
       if(obj[index].days==5)
       {
        var slectedoption5='selected="selected"';
       }
       else
       {
        var slectedoption5='';
       }
       if(obj[index].days==6)
       {
        var slectedoption6='selected="selected"';
       }
       else
       {
        var slectedoption6='';
       }
       if(obj[index].days==7)
       {
        var slectedoption7='selected="selected"';
       }
       else
       {
        var slectedoption7='';
       }

       if(obj[index].days==8)
       {
        var slectedoption8='selected="selected"';
       }
       else
       {
        var slectedoption8='';
       }
       if(obj[index].days==9)
       {
        var slectedoption9='selected="selected"';
       }
       else
       {
        var slectedoption9='';
       }
      if(obj[index].time==1)
      {
      var slectedoptiontime1='selected="selected"';
      }
      else
      {
      var slectedoptiontime1='';
      }
      if(obj[index].time==2)
      {
      var slectedoptiontime2='selected="selected"';
      }
      else
      {
      var slectedoptiontime2='';
      }
      if(obj[index].time==3)
      {
      var slectedoptiontime3='selected="selected"';
      }
      else
      {
      var slectedoptiontime3='';
      }
      if(obj[index].time==4)
      {
      var slectedoptiontime4='selected="selected"';
      }
      else
      {
      var slectedoptiontime4='';
      }
      if(obj[index].right_eye==2)
      {
      var checkedrighteye='checked="checked"';
      }
      if(obj[index].left_eye==1)
      {
      var checkedlefteye='checked="checked"';
      }


      arr += '<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="cheif_complains['+i+'][cheif_complain_name]" value="'+obj[index].cheif_complains+'"/></td>'; 


      arr += '<td align="center" height="30"><input type="checkbox" class="w-40px" value="1" name="cheif_complains['+i+'][cheif_c_left]" '+checkedlefteye+'> </td>';


      arr += '<td align="center" height="30"><input type="checkbox" class="w-40px" value="2" name="cheif_complains['+i+'][cheif_c_right]" '+checkedrighteye+'></td>';


      arr += '<td align="center" height="30">';
            arr += '<select class="w-60px cheif_option_complains'+i+'" id="cheif_option_complains'+i+'" name="cheif_complains['+i+'][cheif_c_days]">'; 
                arr += '<option value="1"  '+slectedoption1+'>1</option>';
                arr += '<option value="2" '+slectedoption2+'>2</option>';
                arr += '<option value="3" '+slectedoption3+'>3</option>';
                arr += '<option value="4" '+slectedoption4+'>4</option>';
                arr += '<option value="5" '+slectedoption5+'>5</option>';
                arr += '<option value="6" '+slectedoption6+'>6</option>';
                arr += '<option value="7" '+slectedoption7+'>7</option>';
                arr += '<option value="8" '+slectedoption8+'>8</option>';
                arr += '<option value="9" '+slectedoption9+'>9</option>';
            arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30">';
          arr += '<select class="w-60px" name="cheif_complains['+i+'][cheif_c_time]">';
              arr += '<option value="1" '+slectedoptiontime1+'>Days</option>';
              arr += '<option value="2" '+slectedoptiontime2+'>Week</option>';
              arr += '<option value="3" '+slectedoptiontime3+'>Month</option>';
              arr += '<option value="4" '+slectedoptiontime4+'>Year</option>';
          arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('+i+');"><i class="fa fa-trash"></i> Delete</a></td>';
      arr += '</tr>';
      i++;
    }); 
    $('#complain_grid').css('display','block');
    $("#chief_complaints").append(arr);
     
    }


/* script for cheif complain */

/* script for systemic illness */

function get_systemic_illness_values(result)
    {
     
      var obj = JSON.parse(result);
   
      var arr = '';
     var rowCount = $('#systemic_illness tr').length;
      var newrowcount=rowCount-1;
      var i=newrowcount;
      var slectedoption9='';
      var slectedoption8='';
      var slectedoption7='';
      var slectedoption6='';
      var slectedoption5='';
      var slectedoption4='';
      var slectedoption3='';
      var slectedoption2='';
   
      $.each(obj, function (index, value) {
     
       // $('.cheif_option_complains'+i+' option[value='+obj[index].days+']').attr('selected','selected');
       if(obj[index].days==1)
       {
        var slectedoption1='selected="selected"';
       }
       else
       {
         var slectedoption1='';
       }
       if(obj[index].days==2)
       {
        var slectedoption2='selected="selected"';
       }
       else
       {
         var slectedoption2='';
       }

       if(obj[index].days==3)
       {
        var slectedoption3='selected="selected"';
       }
       else
       {
        var slectedoption3='';
       }
       if(obj[index].days==4)
       {
        var slectedoption4='selected="selected"';
       }
       else
       {
        var slectedoption4='';
       }
       if(obj[index].days==5)
       {
        var slectedoption5='selected="selected"';
       }
       else
       {
        var slectedoption5='';
       }
       if(obj[index].days==6)
       {
        var slectedoption6='selected="selected"';
       }
       else
       {
        var slectedoption6='';
       }
       if(obj[index].days==7)
       {
        var slectedoption7='selected="selected"';
       }
       else
       {
        var slectedoption7='';
       }

       if(obj[index].days==8)
       {
        var slectedoption8='selected="selected"';
       }
       else
       {
        var slectedoption8='';
       }
       if(obj[index].days==9)
       {
        var slectedoption9='selected="selected"';
       }
       else
       {
        var slectedoption9='';
       }
      if(obj[index].time==1)
      {
      var slectedoptiontime1='selected="selected"';
      }
      else
      {
      var slectedoptiontime1='';
      }
      if(obj[index].time==2)
      {
      var slectedoptiontime2='selected="selected"';
      }
      else
      {
      var slectedoptiontime2='';
      }
      if(obj[index].time==3)
      {
      var slectedoptiontime3='selected="selected"';
      }
      else
      {
      var slectedoptiontime3='';
      }
      if(obj[index].time==4)
      {
      var slectedoptiontime4='selected="selected"';
      }
      else
      {
      var slectedoptiontime4='';
      }
      if(obj[index].right_eye==2)
      {
      var checkedrighteye='checked="checked"';
      }
      if(obj[index].left_eye==1)
      {
      var checkedlefteye='checked="checked"';
      }


      arr += '<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="systemic_illness[]" value="'+obj[index].systemic_illness+'"/></td>'; 


      


      arr += '<td align="center" height="30">';
            arr += '<select class="w-60px cheif_option_complains'+i+'" id="cheif_option_complains'+i+'" name="systemic_illness_days[]">'; 
                arr += '<option value="1"  '+slectedoption1+'>1</option>';
                arr += '<option value="2" '+slectedoption2+'>2</option>';
                arr += '<option value="3" '+slectedoption3+'>3</option>';
                arr += '<option value="4" '+slectedoption4+'>4</option>';
                arr += '<option value="5" '+slectedoption5+'>5</option>';
                arr += '<option value="6" '+slectedoption6+'>6</option>';
                arr += '<option value="7" '+slectedoption7+'>7</option>';
                arr += '<option value="8" '+slectedoption8+'>8</option>';
                arr += '<option value="9" '+slectedoption9+'>9</option>';
            arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30">';
          arr += '<select class="w-60px" name="systemic_illness_time[]">';
              arr += '<option value="1" '+slectedoptiontime1+'>Days</option>';
              arr += '<option value="2" '+slectedoptiontime2+'>Week</option>';
              arr += '<option value="3" '+slectedoptiontime3+'>Month</option>';
              arr += '<option value="4" '+slectedoptiontime4+'>Year</option>';
          arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('+i+');"><i class="fa fa-trash"></i> Delete</a></td>';
      arr += '</tr>';
      i++;
    }); 

     $('#systemic_illness_grid').css('display','block');
      $('#systemic_illness').append(arr);  
    }

      $('#systemic_illness_data').change(function(){  
      var systemic_illness_id = $(this).val();
      var systemic_illness_val = $("#systemic_illness").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_systemic_illness/"+systemic_illness_id, 
        success: function(result)
        {
           $(".systemic_illness_data option[value='"+systemic_illness_id+"']").hide();
           // $(".chief_complaints_data option[value="+complaints_id+"").remove();
          //alert(result);
           //$('#chief_complaints').html(result);
           // if(chief_complaints_val!='')
           // {
           //  var chief_complaints_value = chief_complaints_val+','+result; 
           // }
           // else
           // {
           //  var chief_complaints_value = result;
           // }
           $('#systemic_illness_grid').css('display','block');
           $('#systemic_illness').append(result);  
        } 
      }); 
  });


/* script for systemic illness */

/* get diagnosis values */
function get_diagnosis_data_values(result)
    {
    
      var obj = JSON.parse(result);
   
      var arr = '';
      var rowCount = $('#diagnosis tr').length;
      var newrowcount=rowCount-1;
      var i=newrowcount;
      var slectedoption9='';
      var slectedoption8='';
      var slectedoption7='';
      var slectedoption6='';
      var slectedoption5='';
      var slectedoption4='';
      var slectedoption3='';
      var slectedoption2='';
   
      $.each(obj, function (index, value) {
      //alert(i);
       // $('.cheif_option_complains'+i+' option[value='+obj[index].days+']').attr('selected','selected');
       if(obj[index].days==1)
       {
        var slectedoption1='selected="selected"';
       }
       else
       {
         var slectedoption1='';
       }
       if(obj[index].days==2)
       {
        var slectedoption2='selected="selected"';
       }
       else
       {
         var slectedoption2='';
       }

       if(obj[index].days==3)
       {
        var slectedoption3='selected="selected"';
       }
       else
       {
        var slectedoption3='';
       }
       if(obj[index].days==4)
       {
        var slectedoption4='selected="selected"';
       }
       else
       {
        var slectedoption4='';
       }
       if(obj[index].days==5)
       {
        var slectedoption5='selected="selected"';
       }
       else
       {
        var slectedoption5='';
       }
       if(obj[index].days==6)
       {
        var slectedoption6='selected="selected"';
       }
       else
       {
        var slectedoption6='';
       }
       if(obj[index].days==7)
       {
        var slectedoption7='selected="selected"';
       }
       else
       {
        var slectedoption7='';
       }

       if(obj[index].days==8)
       {
        var slectedoption8='selected="selected"';
       }
       else
       {
        var slectedoption8='';
       }
       if(obj[index].days==9)
       {
        var slectedoption9='selected="selected"';
       }
       else
       {
        var slectedoption9='';
       }
      if(obj[index].time==1)
      {
      var slectedoptiontime1='selected="selected"';
      }
      else
      {
      var slectedoptiontime1='';
      }
      if(obj[index].time==2)
      {
      var slectedoptiontime2='selected="selected"';
      }
      else
      {
      var slectedoptiontime2='';
      }
      if(obj[index].time==3)
      {
      var slectedoptiontime3='selected="selected"';
      }
      else
      {
      var slectedoptiontime3='';
      }
      if(obj[index].time==4)
      {
      var slectedoptiontime4='selected="selected"';
      }
      else
      {
      var slectedoptiontime4='';
      }
      if(obj[index].right_eye==2)
      {
      var checkedrighteye='checked="checked"';
      }
      if(obj[index].left_eye==1)
      {
      var checkedlefteye='checked="checked"';
      }


      arr += '<tr><td align="left" style="text-align:left;" height="30"><input type="text" name="diagnosis['+i+'][diagnosis_name]" value="'+obj[index].diagnosis+'"/></td>'; 


      arr += '<td align="center" height="30"><input type="checkbox" class="w-40px" value="1" name="diagnosis['+i+'][diagnosis_left]" '+checkedlefteye+'> </td>';


      arr += '<td align="center" height="30"><input type="checkbox" class="w-40px" value="2" name="diagnosis['+i+'][diagnosis_right]" '+checkedrighteye+'></td>';


      arr += '<td align="center" height="30">';
            arr += '<select class="w-60px cheif_option_complains'+i+'" id="cheif_option_complains'+i+'" name="diagnosis['+i+'][diagnosis_days]">'; 
                arr += '<option value="1" '+slectedoption1+'>1</option>';
                arr += '<option value="2" '+slectedoption2+'>2</option>';
                arr += '<option value="3" '+slectedoption3+'>3</option>';
                arr += '<option value="4" '+slectedoption4+'>4</option>';
                arr += '<option value="5" '+slectedoption5+'>5</option>';
                arr += '<option value="6" '+slectedoption6+'>6</option>';
                arr += '<option value="7" '+slectedoption7+'>7</option>';
                arr += '<option value="8" '+slectedoption8+'>8</option>';
                arr += '<option value="9" '+slectedoption9+'>9</option>';
            arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30">';
          arr += '<select class="w-60px" name="diagnosis['+i+'][diagnosis_time]">';
              arr += '<option value="1" '+slectedoptiontime1+'>Days</option>';
              arr += '<option value="2" '+slectedoptiontime2+'>Week</option>';
              arr += '<option value="3" '+slectedoptiontime3+'>Month</option>';
              arr += '<option value="4" '+slectedoptiontime4+'>Year</option>';
          arr += '</select>';
      arr += '</td>';


      arr += '<td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('+i+');"><i class="fa fa-trash"></i> Delete</a></td>';
      arr += '</tr>';
      i++;
    }); 
     $('#diagnosis_grid').css('display','block');
     $('#diagnosis').append(arr);  
     
    }

/* get diagnosis values */


    function get_test_values(result)
    {
    
      var obj = JSON.parse(result);
      var arr = '';
     
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) {
     
         arr += '<tr><td><input type="text"   name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'"></td> <td width="80"><a href="javascript:void(0)" class="btn-w-60 remove_row">Delete</a></td></tr>';

         $(function () {
          var getData = function (request, response) {  
              $.getJSON(
                  "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                  function (data) {
                      response(data);
                  });
          };

          var selectItem = function (event, ui) {
              $(".test_val"+i).val(ui.item.value);
              return false;
          }

          $(".test_val"+i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 1,
              change: function() {
                  //$("#test_val").val("").css("display", 2);
              }
          });
          });
        

    }); 

   
      
      $("#test_name_table").append(arr);
     
    }

    function load_prescription_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>eye/add_prescription/get_template_prescription_data/"+template_id, 
        success: function(result)
        {
           get_prescription_values(result);
        } 
      });
    }
       
    function get_prescription_values(result)
    {
      
      //$("table tr:nth-child(2+n)").remove();
      /*<a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>*/
      var obj = JSON.parse(result);
      var pres = '';

      var i=$('#prescription_name_table tr').length;
      $.each(obj, function (index, value) {       
         pres += '<tr><td><input type="checkbox" name="is_eye_drop" value="'+i+'" onclick="check_eye_drop('+i+');" id="is_eye_drop'+i+'"/></td><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px" value="'+obj[index].medicine_name+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_salt]" class="w-100px" value="'+obj[index].medicine_salt+'"></td>       <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
                        {
                        ?>  <td><input type="text" name="prescription['+i+'][brand]" class="w-100px" value="'+obj[index].medicine_brand+'"></td>             <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
                        { ?>                        <td><input type="text" name="prescription['+i+'][medicine_type]" class="input-small w-100px" value="'+obj[index].medicine_type+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>                        <td><input type="text" name="prescription['+i+'][medicine_dose]" class="input-small w-100px" value="'+obj[index].medicine_dose+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
                        {
                        ?>                        <td class="right_eye_append'+i+'"><input type="checkbox"  value="2" name="prescription['+i+'][medicine_right_eye]" class="w-100px medicine-name hide right_eye_val'+i+'"></td>                        <?php 
                        } 
                         if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
                        {
                        ?>                        <td class="left_eye_append'+i+'"><input type="checkbox" value="1" name="prescription['+i+'][medicine_left_eye]" class="w-100px medicine-name hide left_eye_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>                        <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-100px medicine-name" value="'+obj[index].medicine_duration+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-100px medicine-name" value="'+obj[index].medicine_frequency+'"></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'date')=='0')
                        {
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_date]" class="datepicker_m w-100px medicine-name" value="'+obj[index].medicine_frequency+'"></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name" value="'+obj[index].medicine_advice+'"></td>                        <?php } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';
                      i++;

    }); 
      
      $("#prescription_name_table").append(pres);
    

    }    
    
       


     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
      var rowCount = $('#chief_complaints tr').length;
     var newrowcount=rowCount-1;

      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_complaints_name/"+complaints_id+'/'+newrowcount, 
        success: function(result)
        {
           //$('#chief_complaints').html(result);
            $(".chief_complaints_data option[value='"+complaints_id+"']").hide();
            $('#complain_grid').css('display','block');
            $('#chief_complaints').append(result);  
        } 
      }); 
  });


    $('#examination_data').change(function(){  
      var examination_id = $(this).val();
      var examination_val = $("#examination").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_examination_name/"+examination_id, 
        success: function(result)
        {
           //$('#examination').html(result);
           if(examination_val!='')
           {
            var examination_value = examination_val+','+result; 
           }
           else
           {
            var examination_value = result;
           }
           $('#examination').val(examination_value); 
        } 
      }); 
  }); 

    $('#diagnosis_data').change(function(){  
      var rowCount = $('#diagnosis tr').length;
      var newrowcount=rowCount-1;
      var diagnosis_id = $(this).val();
      var diagnosis_val = $("#diagnosis").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_diagnosis_name/"+diagnosis_id+'/'+newrowcount, 
        success: function(result)
        {
            $(".diagnosis_data option[value='"+diagnosis_id+"']").hide();
            $('#diagnosis_grid').css('display','block');
            $('#diagnosis').append(result);  

        } 
      }); 
  });



     $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
      var suggestion_val = $("#suggestion").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_suggetion_name/"+suggetion_id, 
        success: function(result)
        {
           if(suggestion_val!='')
           {
            var suggestion_value = suggestion_val+','+result; 
           }
           else
           {
            var suggestion_value = result;
           }
           //$('#suggestion').html(result); 
           $('#suggestion').val(suggestion_value);
        } 
      }); 
  }); 

     $('#personal_history_data').change(function(){  
      var personal_history_id = $(this).val();
      var personal_history_val = $("#personal_history").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_personal_history_name/"+personal_history_id, 
        success: function(result)
        {
           //$('#personal_history').html(result);

           if(personal_history_val!='')
           {
            var personal_history_value = personal_history_val+','+result; 
           }
           else
           {
            var personal_history_value = result;
           }
           
           $('#personal_history').val(personal_history_value);

        } 
      }); 
  }); 

     $('#prv_history_data').change(function(){  
      var prv_history_id = $(this).val();
      var prv_history_val = $("#prv_history").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_prv_history_name/"+prv_history_id, 
        success: function(result)
        {
           //$('#prv_history').html(result); 

           if(prv_history_val!='')
           {
            var prv_history_value = prv_history_val+','+result; 
           }
           else
           {
            var prv_history_value = result;
           }
           
           $('#prv_history').val(prv_history_value);
        } 
      }); 
  }); 
function check_eye_drop(row_val)
{

 var eye_drop_val= $('#is_eye_drop').val();
 if ($('input#is_eye_drop'+row_val).is(':checked')) 
  {
    $('.right_eye_val'+row_val).removeClass('hide');
    $('.right_eye_append'+row_val).html('<input type="checkbox" name="prescription['+row_val+'][medicine_right_eye]" value="2" class="medicine-name right_eye_val'+row_val+'">');
     
    $('.left_eye_val'+row_val).removeClass('hide');
    $('.left_eye_append'+row_val).html('<input type="checkbox" name="prescription['+row_val+'][medicine_left_eye]" value="1" class="medicine-name left_eye_val'+row_val+'">');
    
  }
  else
  {
    
    $('.right_eye_val'+row_val).addClass('hide');
    $('.right_eye_append'+row_val).html('');
    $('.left_eye_val'+row_val).addClass('hide');
    $('.left_eye_append'+row_val).html('');
  }

}


      $(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });

    $('.datepickerewe').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.datepicker1').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.datepicker2').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });

/* $('.datepicker').datetimepicker({
    format: 'dd-mm-yyyy hh:ii'
}); */

$(document).ready(function(){
    $(".addrow").click(function(){ 
     
      var i=$('#test_name_table tr').length;

        $("#test_name_table").append('<tr><td><input type="text"  name="test_name[]" class="w-100 test_val'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');

        $(function () {
          var getData = function (request, response) { 
              $.getJSON(
                  "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
                  function (data) {
                      response(data);
                  });
          };

          var selectItem = function (event, ui) {
              $(".test_val"+i).val(ui.item.value);
              return false;
          }

          $(".test_val"+i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 1,
              change: function() {
                  //$("#test_val").val("").css("display", 2);
              }
          });
          });


    });
    $("#test_name_table").on('click','.remove_row',function(){
        $(this).parent().parent().remove();
    });


$(".addprescriptionrow").click(function(){ 

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><td><input type="checkbox" name="is_eye_drop" value="'+i+'" onclick="check_eye_drop('+i+');" id="is_eye_drop'+i+'"/></td><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="medicine_val'+i+' w-100px"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?><td><input type="text" id="salt'+i+'" name="prescription['+i+'][salt]" class="w-100px"></td><?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
                        {
                        ?><td><input type="text" id="brand'+i+'" name="prescription['+i+'][brand]" class="w-100px"></td><?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
                        { ?>                        <td><input type="text" name="prescription['+i+'][medicine_type]" id="type'+i+'" class="w-100px input-small medicine_type_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>                        <td><input type="text" name="prescription['+i+'][medicine_dose]" class="w-100px input-small dosage_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>                        <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-100px medicine-name duration_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val'+i+'"></td>                        <?php 
                        } 
                          if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
                        {
                        ?>                        <td class="right_eye_append'+i+'"><input type="checkbox"  value="2" name="prescription['+i+'][medicine_right_eye]" class="w-100px medicine-name hide right_eye_val'+i+'"></td>                        <?php 
                        }

                       
                         if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
                        {
                        ?>  <td class="left_eye_append'+i+'"><input type="checkbox" value="1" name="prescription['+i+'][medicine_left_eye]" class="w-100px medicine-name hide left_eye_val'+i+'"></td>                        <?php 
                        } 

                         if(strcmp(strtolower($tab_value->setting_name),'date')=='0')
                        {
                        ?>                        <td class="medicine_date_append'+i+'"><input type="text" value="" name="prescription['+i+'][medicine_date]" class="datepicker_m w-100px medicine-name medicine_date_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name advice_val'+i+'"></td>                        <?php } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
$('.datepicker_m').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });

/* script start */
$(function () 
{
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('eye/prescription_template/get_eye_medicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
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

       
          var names = ui.item.data.split("|");

          $('.medicine_val'+i).val(names[0]);
          $('#type'+i).val(names[1]);
          $('#brand'+i).val(names[3]);
          $('#salt'+i).val(names[2]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".medicine_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".dosage_val"+i).val(ui.item.value);
        return false;
    }

    $(".dosage_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_type_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".medicine_type_val"+i).val(ui.item.value);
        return false;
    }

    $(".medicine_type_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
});



$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".duration_val"+i).val(ui.item.value);
        return false;
    }

    $(".duration_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".frequency_val"+i).val(ui.item.value);
        return false;
    }

    $(".frequency_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".advice_val"+i).val(ui.item.value);
        return false;
    }

    $(".advice_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
        /* script end*/

    });
    $("#prescription_name_table").on('click','.remove_prescription_row',function(){
        $(this).parent().parent().remove();
    });
});

$('#form_submit').on("click",function(){
       $('#prescription_form').submit();
  })
</script>

<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab li:eq(1) a").tab('show');
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".test_val").val(ui.item.value);
        return false;
    }

    $(".test_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('eye/prescription_template/get_eye_edicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
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

        /*$.getJSON(
            "< ?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });*/
    };

    var selectItem = function (event, ui) {

          var names = ui.item.data.split("|");
          $('.medicine_val').val(names[0]);
          $('#type').val(names[1]);
          $('#brand').val(names[3]);
          $('#salt').val(names[2]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".medicine_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".dosage_val").val(ui.item.value);
        return false;
    }

    $(".dosage_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_type_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".medicine_type_val").val(ui.item.value);
        return false;
    }

    $(".medicine_type_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".duration_val").val(ui.item.value);
        return false;
    }

    $(".duration_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".frequency_val").val(ui.item.value);
        return false;
    }

    $(".frequency_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".advice_val").val(ui.item.value);
        return false;
    }

    $(".advice_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
 function upload_eye_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'eye/add_prescription/upload_eye_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
$(document).on('click', '.ucva', function () {
var $modal = $('#load_add_type_modal_popup');
$modal.load('<?php echo base_url().'eye/ucva/add/' ?>',
{
},
function(){
$modal.modal('show');
});
  });


$(document).on('click', '.bcva', function () {
var $modal = $('#load_add_type_modal_popup');
$modal.load('<?php echo base_url().'eye/bcva/add/' ?>',
{
},
function(){
$modal.modal('show');
});
  });

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

    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg2').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });
     $("#img-input2").change(function(){
        readURL2(this);
    });

function remove_diagnosis_row(row_val)
{
  
    $("#diagnosis").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".diagnosis_data option[value='"+row_val+"']").show();
    
}
function remove_systemic_ill_row(row_val)
{
  
    $("#systemic_illness").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".systemic_illness_data option[value='"+row_val+"']").show();
    
}
</script>


</body>
</html>
<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>