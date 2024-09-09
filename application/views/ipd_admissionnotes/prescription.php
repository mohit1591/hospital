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
<script type="text/javascript" src="<?=ROOT_JS_PATH?>moment-with-locales.js"></script>

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
 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
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
    <!--  // prescription button modal -->
    <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>">

                <div class="row">
                    <div class="col-xs-2">
                        <!-- <button class="btn-commission2" type="button"  data-toggle="modal" data-target="#prescription_select_patient"> Select Patient</button> -->
                        <a class="btn-custom m-l-0 m-b-5" href="<?php echo base_url('ipd_booking'); ?>"><i class="fa fa-user"></i> <b>Registered Patient</b></a>
                        
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>IPD No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="ipd_no" value="<?php echo $form_data['ipd_no']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                                <input type="text"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Patient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?=get_simulation_name($form_data['simulation_id'])?> <?php echo $form_data['patient_name']; ?>" readonly="">
                            </div>
                        </div>
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                               <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>  readonly=""> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>  readonly=""> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>"  readonly=""> Y &nbsp;
                              <input type="text" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"  readonly=""> M &nbsp;
                              <input type="text" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>"  readonly=""> D
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
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
          <option value="">Select</option>
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
                    </div> <!-- 5 -->
                </div> <!-- row -->

                <div class="row m-t-10">
                    <div class="col-xs-9">
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
                        <a href="<?=base_url('admission_notes_template/add')?>" target="_blank" class="btn btn-success"><i class="fa fa-plus"></i> Add new</a>
                    </div>

                    <div class="col-xs-3">
                        <label for="">
                            <b>Date Time</b>
                            <input type="datetime-local" name="date_time_new" value="<?=$form_data['date_time_new']?>">
                        </label>
                        <script>
                            $('#date_time_new').datetimepicker({
                          format: 'DD-MM-YYYY HH:mm A',
                          useCurrent: false,  // Important! See issue #1075
                          showClose: true,
                          showClear: true,
                          showTodayButton: true,
                          stepping: 15, // Time step in minutes
                          minDate: moment().startOf('day'), // Minimum date is today
                          maxDate: moment().add(1, 'year'), // Maximum date is one year from today
                          icons: {
                              time: 'fa fa-clock-o',
                              date: 'fa fa-calendar',
                              up: 'fa fa-chevron-up',
                              down: 'fa fa-chevron-down',
                              previous: 'fa fa-chevron-left',
                              next: 'fa fa-chevron-right',
                              today: 'fa fa-crosshairs',
                              clear: 'fa fa-trash',
                              close: 'fa fa-times'
                          }
                                  
                                });
                        </script>
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
                        $vital_val = get_vitals_value($vitals->id,$form_data['data_id'],6);
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
                 <?php } else{  ?>  
          
          <input type="hidden" name="patient_bp" value="" class="">
          <input type="hidden" name="patient_temp" value="" class="">
          <input type="hidden" name="patient_weight" value="" class="">
          <input type="hidden" name="patient_height" value="" class="">
          <input type="hidden" name="patient_spo2" value="" class="">
          <input type="hidden" name="patient_rbs" value="" class="">
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
                                <!-- <li <?php if($j==1){  ?> class="active" <?php }  ?> ><a data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li> -->
                                


                                <div class="tab-content">

                            <?php 
                            if(strcmp(strtolower($value->setting_name),'previous_history')=='0'){ ?>

                            <div id="tab_previous_history" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                             <textarea name="prv_history" id="prv_history" class="media_100 ckeditor"><?php echo $form_data['prv_history']; ?></textarea> 
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                        <input type="text" name="chief_complaints_search" data-appendId="prv_history_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Previous History">
                                        <a href="<?=base_url('admissionnotes/previous_history')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                            <textarea name="personal_history" id="personal_history" class="media_100 ckeditor"><?php echo $form_data['personal_history']; ?></textarea>  
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                          <!--   <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div> -->
                                            <input type="text" name="chief_complaints_search" data-appendId="personal_history_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Personal History">
                                            <a href="<?=base_url('admissionnotes/personal_history')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                             <?php 
                            if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0'){ ?>
                             <div id="tab_chief_complaint" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                         <textarea name="chief_complaints" id="chief_complaints" class="media_100 ckeditor"><?php echo $form_data['chief_complaints']; ?></textarea>       
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
                                            <input type="text" name="chief_complaints_search" data-appendId="chief_complaints_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Chief Complaints">
                                            <a href="<?=base_url('admissionnotes/chief_complaints')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                                            <select size="9" class="dropdown-box" name="chief_complaints_data"  id="chief_complaints_data" multiple="multiple" >
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
                            <!--Added By Nitin Sharma-->
                             <?php if(strcmp(strtolower($value->setting_name),'examination')=='0'){  ?>
                            <div id="tab_examination" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div style="margin-top:15px;margin-left:15px;">
                                    <label class="radio-inline">
                                      <input type="radio" name="optradio" value="general_examination" <?php echo ($form_data['examination_type'] == "general_examination" || $form_data['examination_type'] == "") ? "checked" : "" ?>>General
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="optradio" value="systemic_examination" <?php echo $form_data['examination_type'] == "systemic_examination"  ? "checked" : "" ?>>Systemic
                                    </label>
                                    <label class="radio-inline">
                                      <input type="radio" name="optradio" value="local_examination" <?php echo $form_data['examination_type'] == "local_examination"  ? "checked" : "" ?>>Local
                                    </label>
                                </div>
                                <div class="row m-t-10" id="general_examination">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                          <textarea name="examination" id="examination" class="media_100"><?php echo $form_data['examination']; ?></textarea>   
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
                                            <input type="text" name="chief_complaints_search" data-appendId="examination_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Examination">
                                            <a href="<?=base_url('admissionnotes/examination')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                <div class="row m-t-10" id="systemic_examination">
                                    <div class="col-xs-12">
                                        <div style="margin-left:15px;">
                                            <div class="form-group row">
                                                <label for="staticEmail" class="col-sm-2 col-form-label">CVS</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control-plaintext" id="cvs" name="cvs" value="<?php echo $form_data['cvs'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword" class="col-sm-2 col-form-label">CNS</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control-plaintext" id="cns" name="cns" value="<?php echo $form_data['cns'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword" class="col-sm-2 col-form-label">Respiratory System</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control-plaintext" id="respiratory_system" name="respiratory_system" value="<?php echo $form_data['respiratory_system'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword" class="col-sm-2 col-form-label">Per abdomen</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control-plaintext" id="per_abdomen" name="per_abdomen" value="<?php echo $form_data['per_abdomen'] ?>">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="inputPassword" class="col-sm-2 col-form-label">Per Vaginal</label>
                                                <div class="col-sm-10">
                                                  <input type="text" class="form-control-plaintext" id="per_vaginal" name="per_vaginal" value="<?php echo $form_data['per_vaginal'] ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row m-t-10" id="local_examination">
                                    <div class="col-xs-12">
                                        <div class="well"><h4>Local Examination</h4>
                                          <textarea name="local_examination_text" id="local_examination_text" class="media_100" name="local_examination_text"><?php echo $form_data['local_examination'] ?></textarea>   
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            <!--Ended By Nitin Sharma-->
                             <?php  if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){  ?>
                            <div id="tab_diagnosis" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                        <textarea name="diagnosis" id="diagnosis" class="media_100 ckeditor"><?php echo $form_data['diagnosis']; ?></textarea> 
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
                                            <input type="text" name="chief_complaints_search" data-appendId="diagnosis_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for diagnosis data">
                                            <a href="<?=base_url('diagnosis')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                                            <select size="9" class="dropdown-box" name="diagnosis_data"  id="diagnosis_data" multiple="multiple" >
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
     <?php if(strcmp(strtolower($value->setting_name),'test_result')=='0'){  ?>
    <div id="tab_test_result" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row m-t-10">
            <div class="col-xs-12">
                <div class="well tab-right-scroll">
                    <table class="table table-bordered table-striped" id="test_name_table">
                        <thead>
                            <tr>
                                <td><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                <td width="80">
                                    <a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(!empty($prescription_test_list))
                            { 
                                $y=1;
                                foreach ($prescription_test_list as $prescription_test) 
                                {
                                    
                                ?>
                            <tr id="del<?php echo $y; ?>">
                                <td><input type="text" name="test_name[]" class="w-100 test_val<?php echo $y; ?>" value="<?php echo $prescription_test->test_name; ?>" >
                                <input type="hidden" name="test_id[]" id="test_id<?php echo $y; ?>" value="<?php echo $prescription_test->test_id; ?>" ></td>
                                <td width="80">
                                    <a onclick="delete_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                </td>
                            </tr>
                             
                            <script type="text/javascript">
                            function delete_row(r)
                            { 
                                var i = r.parentNode.parentNode.rowIndex;
                                document.getElementById("test_name_table").deleteRow(i);
                            }
                              $(function () {
                                      var getData = function (request, response) { 
                                        row = <?php echo $y; ?>;
                                        $.ajax({
                                        url : "<?php echo base_url('ipd_admissionnotes/get_test_vals/'); ?>" + request.term,
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

                                var test_names = ui.item.data.split("|");

                                      $('.test_val'+<?php echo $y; ?>).val(test_names[0]);
                                      $('#test_id'+<?php echo $y; ?>).val(test_names[1]);
                                      return false;
                                  }

                                $(".test_val"+<?php echo $y; ?>).autocomplete({
                                      source: getData,
                                      select: selectItem,
                                      minLength: 1,
                                      change: function() {
                                          //$("#test_val").val("").css("display", 2);
                                      }
                                  });

    });

                            </script>

                            <?php $y++; 

                            } }else{  ?>


                            <tr>
                                <td><input type="text" name="test_name[]" class="w-100 test_val" ><input type="hidden" name="test_id[]" id="test_id" value="" ></td>
                                <td width="80">
                                    <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>

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
                      
                      <?php 
                    $m=0;

                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                            <td <?php echo ($med_value->setting_value == "Advice")? 'width="40%"':''; ?> <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                                <?php 
                           $m++; 
                            }
                            ?>    
                                                    
                        <td width="80">
                            <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                        </td>
                    </tr>

                      <?php 
                      //echo "<pre>"; print_r($prescription_presc_list); exit;
                      if(!empty($prescription_presc_list))
                      { 
                           $l=1;
                          foreach ($prescription_presc_list as $prescription_presc) 
                          {
                            
                          ?>
                     <tr id="prescription_tr_<?php echo $l; ?>">
                       <?php
                        
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td>

                        <input type="text" name="medicine_name[]" value="<?php echo $prescription_presc->medicine_name; ?>" onkeyup="get_medicine_autocomplete(<?php echo $l; ?>);" id="medicine_name<?php echo $l; ?>">
                        <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_id; ?>">
                        </td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_brand[]" value="<?php echo $prescription_presc->medicine_brand; ?>" id="brand<?php echo $l; ?>"  class="" onkeyup="get_brand_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_salt[]" id="salt<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>" ></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="medicine_type[]" id="type<?php echo $l; ?>" class="input-small" value="<?php echo $prescription_presc->medicine_type; ?>" onkeyup="get_medicine_type_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="medicine_dose[]"  value="<?php echo $prescription_presc->medicine_dose; ?>" class="input-small" id="medicine_dose<?php echo $l; ?>"onkeyup="get_medicine_dose_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="medicine_duration[]" class="medicine-name" value="<?php echo $prescription_presc->medicine_duration; ?>" id="medicine_duration<?php echo $l; ?>" onkeyup="get_medicine_duration_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_frequency[]" class="medicine-name" value="<?php echo $prescription_presc->medicine_frequency; ?>" id="medicine_frequency<?php echo $l; ?>" onkeyup="get_medicine_frequency_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="medicine_advice[]" id="medicine_advice<?php echo $l; ?>" class="medicine-name" value="<?php echo $prescription_presc->medicine_advice; ?>" onkeyup="get_medicine_advice_autocomplete(<?php echo $l; ?>);"></td>
                        <?php } }
                        ?>
                        <td width="80">
                            <a onclick="remove_prescription(<?php echo $l; ?>);return false;"  href="javascript:void(0)" class="btn-w-60">Delete</a>
                        </td>
                    </tr>
                    <?php $l++; } }else{ ?>

                    <tr>
                        <?php
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_name[]" class="medicine_val" onkeyup="get_medicine_autocomplete(0);" id="medicine_name0">
                        <input type="hidden" name="medicine_id[]" id="medicine_id0">
                        </td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_brand[]" id="brand0" class="" onkeyup="get_brand_autocomplete(0);"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_salt[]" id="medicine_salt0" class="" ></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="medicine_type[]" id="type0" class="input-small" onkeyup="get_medicine_type_autocomplete(0);"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="medicine_dose[]" class="input-small" onkeyup="get_medicine_dose_autocomplete(0);" id="medicine_dose0"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="medicine_duration[]" class="medicine-name" onkeyup="get_medicine_duration_autocomplete(0);" id="medicine_duration0"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_frequency[]" id="medicine_frequency0" class="medicine-name" onkeyup="get_medicine_frequency_autocomplete(0);"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="medicine_advice[]" class="medicine-name" id="medicine_advice0" onkeyup="get_medicine_advice_autocomplete(0);"></td>
                        <?php } 
                      }
                   ?>
                    <td width="80">
                        <a href="javascript:void(0)" class="btn-w-60" onclick="remove_prescription(0);">Delete</a>
                    </td>
                </tr>
                <?php } ?>
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
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                       <textarea name="suggestion" id="suggestion" class="media_100 ckeditor"><?php echo $form_data['suggestion']; ?></textarea>  
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
                    <input type="text" name="chief_complaints_search" data-appendId="suggestion_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Procedure">
                    <a href="<?=base_url('admissionnotes/suggestion')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
    
    <!--Added by Nitin Sharma 28th Jan 2024-->
     <?php if(strcmp(strtolower($value->setting_name),'remarks')=='0'){   ?>

    <div id="tab_remarks" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
        <div class="row m-t-10">
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                    <textarea class="form-control ckeditor" rows="8" id="remark" name="remark"><?php echo $form_data['remark']; ?></textarea>
                </div>
            </div>
            <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                        <input type="text" name="chief_complaints_search" data-appendId="remark_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Advice data">
                        <a href="<?=base_url('advice_master')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark_data"  id="remark_data" multiple="multiple" >
                            <?php
                                if(isset($advice_master) && !empty($advice_master))
                                {
                                foreach($advice_master as $am)
                                {
                                    echo '<option class="grp" value="'.$am->id.'">'.$am->name.'</option>';
                                }
                                }
                            ?>
                        </select>

                    </div>
                </div>
        </div>
        </div>
    </div>
     <?php } ?>
    <?php  if(strcmp(strtolower($value->setting_name),'history_presenting_illness')=='0'){  ?>
    <div id="tab_history_presenting_illness" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row m-t-10">
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                    <textarea name="history_presenting_illness" id="history_presenting_illness" class="media_100 ckeditor"><?php echo $form_data['history_presenting_illness']; ?></textarea>  
                </div>
            </div>
            <div class="col-xs-4">
                <div class="well tab-right-scroll">
                <input type="text" name="chief_complaints_search" data-appendId="history_presenting_illness_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for History of Presenting Illness">
                <a href="<?=base_url('admissionnotes/history_presenting_illness')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                    <select size="9" class="dropdown-box" name="history_presenting_illness_data"  id="history_presenting_illness_data" multiple="multiple" >
                     <?php
                        if(isset($history_presenting_illness_list) && !empty($history_presenting_illness_list))
                        {
                          foreach($history_presenting_illness_list as $history_presenting_illness)
                          {
                             echo '<option class="grp" value="'.$history_presenting_illness->id.'">'.$history_presenting_illness->illness_name.'</option>';
                          }
                        }
                     ?>
                  </select>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php  if(strcmp(strtolower($value->setting_name),'obstetrics_menstrual_history')=='0'){  ?>
    <div id="tab_obstetrics_menstrual_history" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row m-t-10">
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                    <textarea name="obstetrics_menstrual_history" id="obstetrics_menstrual_history" class="media_100 ckeditor"><?php echo $form_data['obstetrics_menstrual_history']; ?></textarea>  
                </div>
            </div>
            <div class="col-xs-4">
                <div class="well tab-right-scroll">
                <input type="text" name="chief_complaints_search" data-appendId="obstetrics_menstrual_history_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Obstetrics Menstrual History">
                <a href="<?=base_url('admissionnotes/obstetrics_menstrual_history')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                    <select size="9" class="dropdown-box" name="obstetrics_menstrual_history_data"  id="obstetrics_menstrual_history_data" multiple="multiple" >
                     <?php
                        if(isset($obstetrics_menstrual_history_list) && !empty($obstetrics_menstrual_history_list))
                        {
                          foreach($obstetrics_menstrual_history_list as $obstetrics_menstrual_history)
                          {
                             echo '<option class="grp" value="'.$obstetrics_menstrual_history->id.'">'.$obstetrics_menstrual_history->obstetrics_menstrual_history.'</option>';
                          }
                        }
                     ?>
                  </select>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <?php  if(strcmp(strtolower($value->setting_name),'family_history_disease')=='0'){  ?>
    <div id="tab_family_history_disease" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row m-t-10">
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                    <textarea name="family_history_disease" id="family_history_disease" class="media_100 ckeditor"><?php echo $form_data['family_history_disease']; ?></textarea>  
                </div>
            </div>
            <div class="col-xs-4">
                <div class="well tab-right-scroll">
                <input type="text" name="chief_complaints_search" data-appendId="family_history_disease_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for family history disease">
                <a href="<?=base_url('admissionnotes/family_history_disease')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                    <select size="9" class="dropdown-box" name="family_history_disease_data"  id="family_history_disease_data" multiple="multiple" >
                     <?php
                        if(isset($family_history_disease_list) && !empty($family_history_disease_list))
                        {
                          foreach($family_history_disease_list as $family_history_disease)
                          {
                             echo '<option class="grp" value="'.$family_history_disease->id.'">'.$family_history_disease->family_history_disease.'</option>';
                          }
                        }
                     ?>
                  </select>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    
     <?php if(strcmp(strtolower($value->setting_name),'remark1')=='0'){   ?>

    <div id="tab_remark1" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark1" name="remark1"><?php echo $form_data['remark1']; ?></textarea>
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
                        <input type="text" name="chief_complaints_search" data-appendId="remark1_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for diagnosis data">
                        <a href="<?=base_url('diagnosis')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark1_data"  id="remark1_data" multiple="multiple" >
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
    </div>
     <?php } ?>
     
      <?php if(strcmp(strtolower($value->setting_name),'remark2')=='0'){   ?>

    <div id="tab_remark2" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark2" name="remark2"><?php echo $form_data['remark2']; ?></textarea>
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
                        <input type="text" name="chief_complaints_search" data-appendId="remark2_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Nutritional Screening data">
                        <a href="<?=base_url('nutritional_screening')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark2_data"  id="remark2_data" multiple="multiple" >
                            <?php
                                if(isset($nutritional_screening) && !empty($nutritional_screening))
                                {
                                foreach($nutritional_screening as $ns)
                                {
                                    echo '<option class="grp" value="'.$ns->id.'">'.$ns->name.'</option>';
                                }
                                }
                            ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
     
      <?php if(strcmp(strtolower($value->setting_name),'remark3')=='0'){   ?>

    <div id="tab_remark3" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark3" name="remark3"><?php echo $form_data['remark3']; ?></textarea>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                        <input type="text" name="chief_complaints_search" data-appendId="remark3_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Plan of Care data">
                        <a href="<?=base_url('plan_of_care')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark3_data"  id="remark3_data" multiple="multiple" >
                            <?php
                                if(isset($plan_of_care) && !empty($plan_of_care))
                                {
                                foreach($plan_of_care as $pc)
                                {
                                    echo '<option class="grp" value="'.$pc->id.'">'.$pc->name.'</option>';
                                }
                                }
                            ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
     
     <?php if(strcmp(strtolower($value->setting_name),'remark4')=='0'){   ?>

    <div id="tab_remark4" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark4" name="remark4"><?php echo $form_data['remark4']; ?></textarea>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                        <input type="text" name="chief_complaints_search" data-appendId="remark4_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Pain Score data">
                        <a href="<?=base_url('pain_score')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark4_data"  id="remark4_data" multiple="multiple" >
                            <?php
                                if(isset($pain_score) && !empty($pain_score))
                                {
                                foreach($pain_score as $ps)
                                {
                                    echo '<option class="grp" value="'.$ps->id.'">'.$ps->name.'</option>';
                                }
                                }
                            ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
     
     <?php if(strcmp(strtolower($value->setting_name),'remark5')=='0'){   ?>

    <div id="tab_remark5" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark5" name="remark5"><?php echo $form_data['remark5']; ?></textarea>
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
                        <input type="text" name="chief_complaints_search" data-appendId="remark5_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Pain Score data">
                        <a href="<?=base_url('initial_assessment_performed_by_doctor')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                        <select size="9" class="dropdown-box" name="remark5_data"  id="remark5_data" multiple="multiple" >
                            <?php
                                if(isset($initial_assessment_performed_by_doctor) && !empty($initial_assessment_performed_by_doctor))
                                {
                                foreach($initial_assessment_performed_by_doctor as $ps)
                                {
                                    echo '<option class="grp" value="'.$ps->id.'">'.$ps->name.'</option>';
                                }
                                }
                            ?>
                        </select>

                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
    <!--Ended by Nitin Sharma 28th Jan 2024-->
</div>



        <?php 
    $j++;
    }
 ?>



</div> <!-- 11 -->
                




                    
      <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">
     
      <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
      <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
      <div class="col-xs-1">
      <div class="prescription_btns">
      <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
      <button class="btn-save" type="button"  onclick="window.location.href='<?php echo base_url('ipdprescriptionhistory/lists/'.$form_data['patient_id']); ?>'" name=""><i class="fa fa-history"></i> History</button>
      <!-- <button class="btn-save" type="button" name=""><i class="fa fa-info-circle"></i> View</button>
      <button class="btn-save" type="button" name=""><i class="fa fa-upload"></i> Upload</button> -->
      <a href="<?php echo base_url('ipd_admissionnotes'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>


      </div> <!-- row -->
 
</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 <script src="<?=base_url('assets/ckeditor/ckeditor.js')?>"></script>
</div><!-- container-fluid -->
<script type="text/javascript">
  function tab_links(vals)
  {
    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('class','in');
    $('#'+vals).addClass('class','active');
  }

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>opd/get_admission_template_data/"+template_id, 
        success: function(result)
        {
           load_values(result);
           load_test_values(template_id);
           load_prescription_values(template_id);
        } 
      }); 
  });


  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       console.log(obj);
       /*$('#patient_bp').val(obj.patient_bp);
       $('#patient_temp').val(obj.patient_temp);
       $('#patient_weight').val(obj.patient_weight);
       $('#patient_height').val(obj.patient_height);
       $('#patient_spo2').val(obj.patient_spo2);*/
       $('#prescription_medicine').val(obj.prescription_medicine);
        CKEDITOR.instances['prv_history'].setData(obj.prv_history);
        CKEDITOR.instances['personal_history'].setData(obj.personal_history);
        CKEDITOR.instances['chief_complaints'].setData(obj.chief_complaints);
        $('#examination').val(obj.examination);
        CKEDITOR.instances['diagnosis'].setData(obj.diagnosis);
        CKEDITOR.instances['suggestion'].setData(obj.suggestion);
        CKEDITOR.instances['remark'].setData(obj.remark);
        CKEDITOR.instances['remark1'].setData(obj.remark1);
        CKEDITOR.instances['remark2'].setData(obj.remark2);
        CKEDITOR.instances['remark3'].setData(obj.remark3);
        CKEDITOR.instances['remark4'].setData(obj.remark4);
        $('#remark5').val(obj.remark5);
        CKEDITOR.instances['history_presenting_illness'].setData(obj.history_presenting_illness);
        CKEDITOR.instances['obstetrics_menstrual_history'].setData(obj.obstetrics_menstrual_history);
        CKEDITOR.instances['family_history_disease'].setData(obj.family_history_disease);
        $('#local_examination_text').val(obj.local_examination);
        $('#remark5').val(obj.remark5);
        $('#general_examination').val(obj.examination);
        $('#cvs').val(obj.cvs);
        $('#cns').val(obj.cns);
        $('#respiratory_system').val(obj.respiratory_system);
        $('#per_abdomen').val(obj.per_abdomen);
        $('#per_vaginal').val(obj.per_vaginal);
       // Added By Nitin Sharma 27/02/2024
    };

    function load_test_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>opd/get_admission_template_test_data/"+template_id, 
        success: function(result)
        {
           get_test_values(result);
        } 
      });
    }
/*<a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>*/
    function get_test_values(result)
    {
      var obj = JSON.parse(result);
      var arr = '';
    //   arr += '<tbody><tr><td>Test Name</td><td width="80"></td></tr>';
    // arr += '<tbody>';
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) { 
        arr += `<tr>
                <td>
                    <input type="text" name="test_name[]" class="w-100 test_val${i}" value="${obj[index].test_name}" placeholder="Click to add Test">
                    <input type="hidden" id="test_id${obj[index].test_name}" name="test_id[]" class="w-100" value="${obj[index].test_id}">
                </td>
                <td width="80">
                    <a href="javascript:void(0)" class="btn-w-60 remove_row" title="Remove Test">Delete</a>
                </td>
            </tr>`;
        //  arr += '<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'"><input type="hidden" name="test_id[]" id="test_id'+i+'" value="'+obj[index].test_id+'"></td></tr>';
        
        $(function () {
          

          var getData = function (request, response) { 
              row = i ;
              $.ajax({
              url : "<?php echo base_url('ipd_admissionnotes/get_test_vals/'); ?>" + request.term,
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

                  var test_names = ui.item.data.split("|");

                  $('.test_val'+i).val(test_names[0]);
                  $('#test_id'+i).val(test_names[1]);
                  return false;
            }

            $(".test_val"+i).autocomplete({
                source: getData,
                select: selectItem,
                minLength: 1,
                change: function() {
                   
                }
            });

        
          });

    }); 

    arr += '</tbody>'; 
      
      $("#test_name_table tbody").replaceWith(arr);
     
    }

    function load_prescription_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>opd/get_admission_template_prescription_data/"+template_id, 
        success: function(result)
        {
           get_prescription_values(result);
        } 
      });
    }
       
    function get_prescription_values(result)
    {
      /*<a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>*/
      var obj = JSON.parse(result);
      var pres = '';
      pres += '<tbody><tr><?php 
                    $l=0;
                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>  <td <?php  if($l=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>                                <?php 
                           $l++; 
                            }
                            ?><td width="80"><a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a></td></tr>';
      i = 1;
      $.each(obj, function (index, value) {       
         pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" value="'+obj[index].medicine_name+'"><input type="hidden" name="medicine_id[]" class="" value="'+obj[index].medicine_id+'"></td> <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?> <td><input type="text" name="medicine_brand[]" class="" value="'+obj[index].medicine_brand+'"></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_salt[]" class="" value="'+obj[index].medicine_salt+'" ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>                        <td><input type="text" name="medicine_type[]" class="input-small" value="'+obj[index].medicine_type+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>                        <td><input type="text" name="medicine_dose[]" class="input-small" value="'+obj[index].medicine_dose+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>                        <td><input type="text" name="medicine_duration[]" class="medicine-name" value="'+obj[index].medicine_duration+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_frequency[]" class="medicine-name" value="'+obj[index].medicine_frequency+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?><td><input type="text" name="medicine_advice[]" class="medicine-name" value="'+obj[index].medicine_advice+'"></td>                        <?php } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';

    }); 
      pres += '</tbody>'; 
      $("#prescription_name_table tbody").replaceWith(pres);
    

    }    
    
       


     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
    //   var chief_complaints_val = $("#chief_complaints").val();
      var chief_complaints_val = CKEDITOR.instances.chief_complaints.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_complaints_name/"+complaints_id, 
        success: function(result)
        {
           if(chief_complaints_val!='')
           {
            var chief_complaints_value = chief_complaints_val+' '+result; 
           }
           else
           {
            var chief_complaints_value = result;
           }
           CKEDITOR.instances['chief_complaints'].setData(chief_complaints_value);
        } 
      }); 
  });


    $('#examination_data').change(function(){  
      var examination_id = $(this).val();
      var examination_val = $("#examination").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_examination_name/"+examination_id, 
        success: function(result)
        {
           if(examination_val!='')
           {
            var examination_value = examination_val+' '+result; 
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
      var diagnosis_id = $(this).val();
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.diagnosis.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
        success: function(result)
        {
           if(diagnosis_val!='')
           {
            var diagnosiss_value = diagnosis_val+' '+result; 
           }
           else
           {
            var diagnosiss_value = result;
           }
           CKEDITOR.instances['diagnosis'].setData(diagnosiss_value);
        } 
      }); 
  });
  $('#remark1_data').change(function(){  
      var diagnosis_id = $(this).val();
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.remark1.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
        success: function(result)
        {
           if(diagnosis_val!='')
           {
            var diagnosiss_value = diagnosis_val+' '+result; 
           }
           else
           {
            var diagnosiss_value = result;
           }
           CKEDITOR.instances['remark1'].setData(diagnosiss_value);
        } 
      }); 
  });

  $('#remark4_data').change(function(){  
      var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.remark4.getData();
    //   $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
    //     success: function(result)
    //     {
           if(text!='')
           {
            var diagnosiss_value = diagnosis_val+' '+text; 
           }
           else
           {
            var diagnosiss_value = text;
           }
           CKEDITOR.instances['remark4'].setData(diagnosiss_value);
    //     } 
    //   }); 
  });

  $('#remark5_data').change(function(){  
      var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.remark5.getData();
    //   $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
    //     success: function(result)
    //     {
           if(text!='')
           {
            var diagnosiss_value = diagnosis_val+' '+text; 
           }
           else
           {
            var diagnosiss_value = text;
           }
           CKEDITOR.instances['remark5'].setData(diagnosiss_value);
    //     } 
    //   }); 
  });

  $('#remark2_data').change(function(){  
        var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
        var diagnosis_val = CKEDITOR.instances.remark2.getData();
        if(text!='')
        {
        var diagnosiss_value = diagnosis_val+' '+text; 
        }
        else
        {
        var diagnosiss_value = text;
        }
        CKEDITOR.instances['remark2'].setData(diagnosiss_value);
  });

  $('#remark3_data').change(function(){  
        var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
        var diagnosis_val = CKEDITOR.instances.remark3.getData();
        if(text!='')
        {
        var diagnosiss_value = diagnosis_val+' '+text; 
        }
        else
        {
        var diagnosiss_value = text;
        }
        CKEDITOR.instances['remark3'].setData(diagnosiss_value);
  });

  $('#remark_data').change(function(){  
        var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
        var diagnosis_val = CKEDITOR.instances.remark.getData();
        if(text!='')
        {
        var diagnosiss_value = diagnosis_val+' '+text; 
        }
        else
        {
        var diagnosiss_value = text;
        }
        CKEDITOR.instances['remark'].setData(diagnosiss_value);
  });



     $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
    //   var suggestion_val = $("#suggestion").val();
      var suggestion_val = CKEDITOR.instances.suggestion.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_suggetion_name/"+suggetion_id, 
        success: function(result)
        {
           if(suggestion_val!='')
           {
            var suggestion_value = suggestion_val+' '+result; 
           }
           else
           {
            var suggestion_value = result;
           }
           //$('#suggestion').html(result); 
           CKEDITOR.instances['suggestion'].setData(suggestion_value);
        } 
      }); 
  }); 

     $('#personal_history_data').change(function(){  
      var personal_history_id = $(this).val();
    //   var personal_history_val = $("#personal_history").val();
      var personal_history_val = CKEDITOR.instances.personal_history.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_personal_history_name/"+personal_history_id, 
        success: function(result)
        {
           
           if(personal_history_val!='')
           {
            var personal_history_value = personal_history_val+' '+result; 
           }
           else
           {
            var personal_history_value = result;
           }
           CKEDITOR.instances['personal_history'].setData(personal_history_value);
        } 
      }); 
  }); 

     $('#prv_history_data').change(function(){  
      var prv_history_id = $(this).val();
    //   var prv_history_val = $("#prv_history").val();
      var prv_history_val = CKEDITOR.instances.prv_history.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_prv_history_name/"+prv_history_id, 
        success: function(result)
        {
           if(prv_history_val!='')
           {
            var prv_history_value = prv_history_val+' '+result; 
           }
           else
           {
            var prv_history_value = result;
           }
           
           CKEDITOR.instances['prv_history'].setData(prv_history_value);
        } 
      }); 
  }); 
    // Added by Nitin Sharma 28th Jan 2024
   $('#history_presenting_illness_data').change(function(){
        var illness_id = $(this).val();
        // var illness_val = $("#history_presenting_illness").val();
        var illness_val = CKEDITOR.instances.history_presenting_illness.getData();
        $.ajax({url: "<?php echo base_url(); ?>opd/admission_illness_name/"+illness_id, 
            success: function(result)
            {
                if(illness_val != ""){
                    var illness_value = `${illness_val},${result}`;
                }else{
                    var illness_value = result;
                }   
            //   $("#history_presenting_illness").val(illness_value.trim());
              CKEDITOR.instances['history_presenting_illness'].setData(illness_value);
            } 
        });
    });
    
    $('#obstetrics_menstrual_history_data').change(function(){
        var obs_mens_his_id = $(this).val();
        // var obs_mens_his_val = $("#obstetrics_menstrual_history").val();
        var obs_mens_his_val = CKEDITOR.instances.obstetrics_menstrual_history.getData();
        $.ajax({url: "<?php echo base_url(); ?>opd/admission_obstetrics_menstrual_history_name/"+obs_mens_his_id, 
            success: function(result)
            {
                if(obs_mens_his_val != ""){
                    var obs_mens_his_value = `${obs_mens_his_val},${result}`;
                }else{
                    var obs_mens_his_value = result;
                }   
              CKEDITOR.instances['obstetrics_menstrual_history'].setData(obs_mens_his_value);
            } 
        });
    });
    
    $('#family_history_disease_data').change(function(){
        var obs_mens_his_id = $(this).val();
        // var family_history_disease_val = $("#family_history_disease").val();
        var family_history_disease_val = CKEDITOR.instances.family_history_disease.getData();
        $.ajax({url: "<?php echo base_url(); ?>opd/admission_family_history_disease_name/"+obs_mens_his_id, 
            success: function(result)
            {
                if(family_history_disease_val != ""){
                    var family_history_disease_value = `${family_history_disease_val},${result}`;
                }else{
                    var family_history_disease_value = result;
                }   
                CKEDITOR.instances['family_history_disease'].setData(family_history_disease_value);
            } 
        });
    });
    
    $('input[name="optradio"]').on('change', function(e) {
        var radio_val = e.target.value;
        if(radio_val == 'general_examination'){
            $("#general_examination").css("display" ,"block");
            $("#systemic_examination").css("display" ,"none");
            $("#local_examination").css("display","none");
        }else if(radio_val == 'systemic_examination'){
            $("#general_examination").css("display" ,"none");
            $("#systemic_examination").css("display" ,"block");
            $("#local_examination").css("display","none");
        }else{
            $("#general_examination").css("display" ,"none");
            $("#systemic_examination").css("display" ,"none");
            $("#local_examination").css("display","block");
        }
    
    });
    function examinationShowHide(radio_val = 'general_examination'){
        if(radio_val == ""){
            radio_val = "general_examination";
        }
        if(radio_val == 'general_examination'){
            $("#general_examination").css("display" ,"block");
            $("#systemic_examination").css("display" ,"none");
            $("#local_examination").css("display","none");
        }else if(radio_val == 'systemic_examination'){
            $("#general_examination").css("display" ,"none");
            $("#systemic_examination").css("display" ,"block");
            $("#local_examination").css("display","none");
        }else{
            $("#general_examination").css("display" ,"none");
            $("#systemic_examination").css("display" ,"none");
            $("#local_examination").css("display","block");
        }
    }
    
    // Ended by Nitin Sharma 28th Jan 2024
 $(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });
 $('.datepickerfdf').datetimepicker({
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

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });*/

$(document).ready(function(){
    var examination_type = "<?php echo $form_data['examination_type'] ?>"; // Added by Nitin Sharma
    examinationShowHide(examination_type); // Added By Nitin Sharma
    $(".addrow").click(function(){ 

      var i=$('#test_name_table tr').length;

        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'"><input type="hidden" name="test_id[]" id="test_id'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');

        $(function () {
         /* var getData = function (request, response) { 
              $.getJSON(
                  "<?php echo base_url('ipd_admissionnotes/get_test_vals/'); ?>" + request.term,
                  function (data) {
                      response(data);
                  });
          };*/

          var getData = function (request, response) { 
              row = i ;
              $.ajax({
              url : "<?php echo base_url('ipd_admissionnotes/get_test_vals/'); ?>" + request.term,
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

          /*var selectItem = function (event, ui) {
              $(".test_val"+i).val(ui.item.value);
              return false;
          }*/

           var selectItem = function (event, ui) {

          var test_names = ui.item.data.split("|");

          $('.test_val'+i).val(test_names[0]);
          $('#test_id'+i).val(test_names[1]);
          return false;
        }

          $(".test_val"+i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 1,
              change: function() {
                 
              }
          });
          });
    });
    $("#test_name_table").on('click','.remove_row',function(){
        $(this).parent().parent().remove();
    });


$("#prescription_name_table").on("click",'.addprescriptionrow',function(){ 

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr id="prescription_tr_'+i+'"><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" onkeyup="get_medicine_autocomplete('+i+');" id="medicine_name'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'" ></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>   <td><input type="text"  name="medicine_brand[]" id="brand'+i+'"  class="" onkeyup="get_brand_autocomplete('+i+');" ></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>  <td><input type="text" id="salt'+i+'"  name="medicine_salt[]" class=""  ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>  <td><input type="text" id="type'+i+'"  name="medicine_type[]" class="input-small" onkeyup="get_medicine_type_autocomplete('+i+');"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?> <td><input type="text" name="medicine_dose[]" class="input-small" id="medicine_dose'+i+'"onkeyup="get_medicine_dose_autocomplete('+i+');"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>  <td><input type="text" name="medicine_duration[]" class="medicine-name"  id="medicine_duration'+i+'" onkeyup="get_medicine_duration_autocomplete('+i+');"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?> <td><input type="text" name="medicine_frequency[]" class="medicine-name" id="medicine_frequency'+i+'" onkeyup="get_medicine_frequency_autocomplete('+i+');"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>  <td><input type="text" name="medicine_advice[]" class="medicine-name" onkeyup="get_medicine_advice_autocomplete('+i+');" id="medicine_advice'+i+'"></td>                        <?php 
                        } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60" onclick="remove_prescription('+i+');">Delete</a></td></tr>');
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
        row = 1 ;
        $.ajax({
        url : "<?php echo base_url('ipd_admissionnotes/get_test_vals/'); ?>" + request.term,
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

          var test_names = ui.item.data.split("|");

          $('.test_val').val(test_names[0]);
          $('#test_id').val(test_names[1]);
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



function remove_prescription(id)
{
  $("#prescription_tr_"+id).remove();
}

function get_medicine_autocomplete(row_id)
{
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('opd/get_admission_medicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
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
          //alert(names);
          $('#medicine_name'+row_id).val(names[0]);
          $('#type'+row_id).val(names[1]);
          $('#brand'+row_id).val(names[3]);
          $('#salt'+row_id).val(names[2]);
          $('#medicine_id'+row_id).val(names[4]);
          //$(".medicine_val").val(ui.item.value);
          
          return false;
    }

    $("#medicine_name"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }
 
 function get_medicine_dose_autocomplete(row_id)
 {
    
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_admission_dosage_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#medicine_dose"+row_id).val(ui.item.value);
        
        return false;
    }

    $("#medicine_dose"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
 }

 function get_medicine_type_autocomplete(row_id)
 {
    
    
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_admission_type_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#type"+row_id).val(ui.item.value);
        return false;
    }

    $("#type"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }

 function get_medicine_duration_autocomplete(row_id)
 {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_admission_duration_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#medicine_duration"+row_id).val(ui.item.value);
        
        return false;
    }

    $("#medicine_duration"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }

 function get_medicine_frequency_autocomplete(row_id)
 {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_admission_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#medicine_frequency"+row_id).val(ui.item.value);
        return false;
    }

    $("#medicine_frequency"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }

function get_medicine_advice_autocomplete(row_id)
 {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_admission_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#medicine_advice"+row_id).val(ui.item.value);
        return false;
    }

    $("#medicine_advice"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }
</script>
<script>
$(".select-box-search-event").on('keyup', function() {
   var level = $(this).val();
   var appendClass = $(this).attr("data-appendId");
   $.ajax({
      url:"<?php echo base_url(); ?>ipd_admissionnotes/search_box_data",
      method:"POST",
      data:{type:level,class:appendClass},
      dataType:"json",
      success:function(data)
      {
        var html = '';
        if(data) {
            for(var count = 0; count < data.length; count++)
            {
            html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
            }
            $(`#${appendClass}`).html(html);
        }
      }
    })
 
 });
</script>
</body>
</html>

      <script>
        var basicToolbar = [
            { name: 'basicstyles', items: ['Bold', 'Italic'] },
            { name: 'editing', items: ['Scayt'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'insert', items: ['Table'] },
            // { name: 'tools', items: ['Maximize'] }
        ];
        var elements = document.querySelectorAll('.ckeditor');
        elements.forEach(function(element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        });

        // var element = document.querySelector('cause_of_death');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }

        // var element = document.querySelector('field_name[]');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }
        
      </script>