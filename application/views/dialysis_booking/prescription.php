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

<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>

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


                <div class="row">
                   
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Dialysis No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_code" value="<?php echo $form_data['booking_code']; ?>" readonly="">
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
                                <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>" readonly="">
                            </div>
                        </div>
                        
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Date</strong></div>
                            <div class="col-xs-8">
                                
                                <input type="text" name="prescription_date"  id="prescription_date" class="datepicker m_input_default" value="<?php echo $form_data['prescription_date']; ?>"/>
                                
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
                               <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> readonly=""> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?> readonly=""> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>" readonly=""> Y &nbsp;
                              <input type="text" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>" readonly=""> M &nbsp;
                              <input type="text" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>" readonly=""> D
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                            </div>
                        </div>
                        
                        <!-- new code by mamta -->
  <div class="row m-b-5">
    <div class="col-xs-4">
      <strong> 
      <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);" disabled>
      <?php foreach($gardian_relation_list as $gardian_list) 
      {?>
      <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
      <?php }?>
      </select>

      </strong>
    </div>
      <div class="col-xs-8">
        <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id" disabled>
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
        <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name" readonly=""/>
      </div>
    </div> <!-- row -->
    
    
    <div class="row m-b-5">
            <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
            <div class="col-xs-8">
                <input type="text" name="aadhaar_no" value="<?php echo $form_data['aadhaar_no']; ?>" readonly="">
                 <?php if(!empty($form_error)){ echo form_error('aadhaar_no'); } ?>
            </div>
        </div>

<!-- new code by mamta -->
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

                       <!--  <label>
                            <b>BP</b> 
                            <input type="text"  maxlength="6" name="patient_bp" id="patient_bp" class="w-90px numeric_slash" value="<?php echo $form_data['patient_bp']; ?>"> 
                            <span>mm/Hg</span>
                        </label> &nbsp;
                        <label>
                            <b>PR</b> 
                            <input type="text"  maxlength="6"  name="patient_height" id="patient_height" class="input-tiny price_float" value="<?php echo $form_data['patient_height'];?>"> 
                            <span>Per/Min.</span>
                        </label> &nbsp;
                        <label>
                            <b>Temp</b> 
                            <input type="text"    maxlength="6" name="patient_temp" id="patient_temp" class="input-tiny price_float" value="<?php echo $form_data['patient_temp']; ?>"> 
                            <span>&#x2109;</span>
                        </label> &nbsp;
                        

                        <label>
                            <b>Weight</b> 
                            <input type="text"  maxlength="6" name="patient_weight" id="patient_weight" class="input-tiny price_float" value="<?php echo $form_data['patient_weight']; ?>"> 
                            <span>kg</span>
                        </label> &nbsp;
                        
                        <label>
                            <b>Spo2</b> 
                            <input type="text"  maxlength="6"  name="patient_spo2" id="patient_spo2" class="input-tiny price_float" value="<?php echo $form_data['patient_spo2']; ?>"> 
                            <span>%</span>
                        </label> &nbsp;
                         <label>
                            <b>RBS/FBS</b> 
                            <input type="text" maxlength="6" name="patient_rbs" id="patient_rbs" class="input-tiny numeric_slash" value="<?php echo $form_data['patient_rbs']; ?>"> 
                            <span>mg/dl</span>
                        </label> &nbsp; -->
                    </div>
                </div> <!-- row -->
                <?php } /*else{  ?>  
          
          <input type="hidden" name="patient_bp" value="" class="">
          <input type="hidden" name="patient_temp" value="" class="">
          <input type="hidden" name="patient_weight" value="" class="">
          <input type="hidden" name="patient_height" value="" class="">
          <input type="hidden" name="patient_sop" value="" class="">
          <input type="hidden" name="patient_rbs" value="" class="">
          <?php }*/ ?>

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
                                          <!--   <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div>
                                            <div class="grp">DSHPT1</div> -->
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
                                         <textarea name="chief_complaints" id="chief_complaints" class="media_100"><?php echo $form_data['chief_complaints']; ?></textarea>       
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
                             <?php if(strcmp(strtolower($value->setting_name),'examination')=='0'){  ?>
                            <div id="tab_examination" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
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
                             <?php  if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){  ?>
                            <div id="tab_diagnosis" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                        <textarea name="diagnosis" id="diagnosis" class="media_100"><?php echo $form_data['diagnosis']; ?></textarea> 
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
                                                <tbody>
                                                    <tr>
                                                        <td><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><input type="text" name="test_name[]" class="w-100 test_val" placeholder="Click to add Test">
                                                        <input type="hidden" id="test_id" name="test_id[]" class="w-100" >
                                                        </td>
                                                        <td width="80">
                                                            <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>


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
                            <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                                <?php 
                           $m++; 
                            }
                            ?>
                            <td width="80"><a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a></td></tr>
                        <tr>

                        <?php
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_name[]"  class="medicine_val" placeholder="Click to add Medicine">
                        <input type="hidden" name="medicine_id[]" id="medicine_id" value=""><p style="color:green" id="medicine_total"></p></td>
                        <?php 
                        }


                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="salt[]" id="salt" class=""></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="brand[]" id="brand" class=""></td>
                        <?php 
                        }


                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="medicine_type[]" id="type" class="input-small medicine_type_val"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="medicine_dose[]" class="input-small dosage_val"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="medicine_duration[]" class="medicine-name duration_val"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="medicine_advice[]" class="medicine-name advice_val"></td>
                        <?php } 
                      }
                   ?>
                <td width="80">
                  <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                </td>


                  </tr>
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
                                           <!-- <input type="text" name="next_appointment_date"  class="datepicker date "  data-date-format="dd-mm-yyyy HH:ii"  value="< ?php echo $form_data['next_appointment_date']; ?>" /> -->
                                            
                                            
                                    
                                    <input type="text" name="next_appointment_date" class="w-130px datepicker m_input_default" placeholder="Date" value="<?php echo  $form_data['next_appointment_date']; ?>" >
                <input type="text" name="next_appointment_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['next_appointment_time']; ?>">
                
                
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
                




                    
            <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">
             <input type="hidden" name="appointment_date" value="<?php echo $form_data['appointment_date']; ?>">
              <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
           <div class="col-xs-1">
            <div class="prescription_btns">
                <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
                <!--<button class="btn-save" type="button"  onclick="window.location.href='< ?php echo base_url('dialysis_prescriptionhistory/lists/'.$form_data['patient_id']); ?>'" name=""><i class="fa fa-history"></i> History</button>-->
                <!-- <button class="btn-save" type="button" name=""><i class="fa fa-info-circle"></i> View</button>
                <button class="btn-save" type="button" name=""><i class="fa fa-upload"></i> Upload</button> -->
                <a href="<?php echo base_url('dialysis_prescription'); ?>"  class="btn-anchor" >
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
  function tab_links(vals)
  {
    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('class','in');
    $('#'+vals).addClass('class','active');
  }

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/get_template_data/"+template_id, 
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
       /*$('#patient_bp').val(obj.patient_bp);
       $('#patient_temp').val(obj.patient_temp);
       $('#patient_weight').val(obj.patient_weight);
       $('#patient_height').val(obj.patient_height);
       $('#patient_spo2').val(obj.patient_spo2);*/
       $('#prescription_medicine').val(obj.prescription_medicine);
       $('#prv_history').val(obj.prv_history);
       $('#personal_history').val(obj.personal_history);
       $('#chief_complaints').val(obj.chief_complaints);
       $('#examination').val(obj.examination);
       $('#diagnosis').val(obj.diagnosis);
       $('#suggestion').val(obj.suggestion);
       $('#remark').val(obj.remark);
       $('#appointment_date').val(obj.appointment_date);
       

       
    };

    function load_test_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/get_template_test_data/"+template_id, 
        success: function(result)
        {
           get_test_values(result);
        } 
      });
    }

    function get_test_values(result)
    {
      
      var obj = JSON.parse(result);
      var arr = '';
      arr += '<tbody><tr><td>Test Name</td><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_test_bytemp()">Add</a></td></tr>';
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) {
     
         arr += '<tr><td><input type="text"   name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'" placeholder="Click to add Test"><input type="hidden" id="test_id'+i+'" name="test_id[]" class="w-100" value="'+obj[index].test_id+'"></td><td><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>';

         $(function () {



    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dialysis_prescription/get_test_vals/'); ?>" + request.term,
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
        $("#test_val").val(ui.item.value);
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
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    });
        
    i++;
    }); 

    arr += '</tbody>'; 
      
      $("#test_name_table tbody").replaceWith(arr);
     
    }

    function load_prescription_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/get_template_prescription_data/"+template_id, 
        success: function(result)
        {
           get_prescription_values(result);
        } 
      });
    }
       
    function get_prescription_values(result)
    {
      var obj = JSON.parse(result);
      var pres = '';
      pres += '<tbody><tr><?php 
                    $l=0;
                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>                            <td <?php  if($l=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>                                <?php 
                           $l++; 
                            }
                            ?><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_medicine_bytemp()">Add</a></td></tr>';
      i = 1;
      $.each(obj, function (index, value) {       
         pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" value="'+obj[index].medicine_name+'" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]"  value="'+obj[index].medicine_id+'" ><p style="color:green" id="medicine_total"></p></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?><td><input type="text" name="medicine_salt[]" class="" value="'+obj[index].medicine_salt+'"></td>       <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>  <td><input type="text" name="medicine_brand[]" class="" value="'+obj[index].medicine_brand+'"></td>             <?php 
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
                        ?>                        <td><input type="text" name="medicine_advice[]" class="medicine-name" value="'+obj[index].medicine_advice+'"></td>                        <?php } 
                      } ?><td><a href="javascript:void(0)" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';

    i++;
          
      }); 
      pres += '</tbody>'; 
      $("#prescription_name_table tbody").replaceWith(pres);
    

    }    
    
       


     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/complaints_name/"+complaints_id, 
        success: function(result)
        {
           //$('#chief_complaints').html(result);
           if(chief_complaints_val!='')
           {
            var chief_complaints_value = chief_complaints_val+','+result; 
           }
           else
           {
            var chief_complaints_value = result;
           }
           $('#chief_complaints').val(chief_complaints_value);  
        } 
      }); 
  });


    $('#examination_data').change(function(){  
      var examination_id = $(this).val();
      var examination_val = $("#examination").val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/examination_name/"+examination_id, 
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
      var diagnosis_id = $(this).val();
      var diagnosis_val = $("#diagnosis").val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/diagnosis_name/"+diagnosis_id, 
        success: function(result)
        {
           //$('#diagnosis').html(result);

           if(diagnosis_val!='')
           {
            var diagnosiss_value = diagnosis_val+','+result; 
           }
           else
           {
            var diagnosiss_value = result;
           }
           $('#diagnosis').val(diagnosiss_value);

        } 
      }); 
  });



     $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
      var suggestion_val = $("#suggestion").val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/suggetion_name/"+suggetion_id, 
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
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/personal_history_name/"+personal_history_id, 
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
      $.ajax({url: "<?php echo base_url(); ?>dialysis_booking/prv_history_name/"+prv_history_id, 
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


    
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });

/* $('.datepicker').datetimepicker({
    format: 'dd-mm-yyyy hh:ii'
}); */

$(document).ready(function(){
    $(".addrow").click(function(){ 
      var i=$('#test_name_table tr').length;

        $("#test_name_table").append('<tr><td><input type="text"  name="test_name[]" class="w-100 test_val'+i+'" placeholder="Click to add Test"><input type="hidden" id="test_id'+i+'" name="test_id[]" class="w-100" value=""></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');

       $(function () {



    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dialysis_prescription/get_test_vals/'); ?>" + request.term,
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

          $(".test_val"+i).val(test_names[0]);
          $('#test_id'+i).val(test_names[1]);
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
        $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="medicine_val'+i+'" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"><p  style="color:green" id="medicine_total'+i+'"></p></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?><td><input type="text" id="salt'+i+'" name="salt[]" class=""></td><?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?><td><input type="text" id="brand'+i+'" name="brand[]" class=""></td><?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>                        <td><input type="text" name="medicine_type[]" id="type'+i+'" class="input-small medicine_type_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>                        <td><input type="text" name="medicine_dose[]" class="input-small dosage_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>                        <td><input type="text" name="medicine_duration[]" class="medicine-name duration_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>                        <td><input type="text" name="medicine_advice[]" class="medicine-name advice_val'+i+'"></td>                        <?php } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');

/* script start */
$(function () 
{
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dialysis_booking/get_medicine_auto_vals/'); ?>" + request.term,
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
          $('#brand'+i).val(names[2]);
          $('#salt'+i).val(names[3]);
          var total_qty = 'Available quantity in stock '+names[5];
          $('#medicine_total'+i).text(total_qty);
          $('#medicine_id'+i).val(names[4]);
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
            "<?php echo base_url('dialysis_booking/get_dosage_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_type_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_duration_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_frequency_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_advice_vals/'); ?>" + request.term,
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
    $(':input[id=form_submit]').prop('disabled', true);
       $('#prescription_form').submit();
  })
</script>

<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab li:eq(1) a").tab('show');
});

$(function () {
    var getData = function (request, response) { 
        /*$.getJSON(
            "<?php echo base_url('dialysis_booking/get_test_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            })*/;
    

            row = 1;
        $.ajax({
        url : "<?php echo base_url('dialysis_booking/get_test_vals/'); ?>" + request.term,
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
          //alert(test_names[0]);
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


$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dialysis_booking/get_medicine_auto_vals/'); ?>" + request.term,
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

          
          $('.medicine_val').val(names[0]);
          $('#type').val(names[1]);
          $('#brand').val(names[2]);
          $('#salt').val(names[3]);
          var total_qty = 'Available quantity in stock '+names[5];
          $('#medicine_total').text(total_qty);
          $('#medicine_id').val(names[4]);
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
            "<?php echo base_url('dialysis_booking/get_dosage_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_type_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_duration_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_frequency_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dialysis_booking/get_advice_vals/'); ?>" + request.term,
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
    
    function delete_row(r)
    { 
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("test_name_table").deleteRow(i);
    }
    
    function get_medicine_bytemp()
         {
           //alert();
         
           var m=1;
           var i=$('#prescription_name_table tr').length;
                 $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
            ?>   <td><input type="text" name="prescription['+i+'][medicine_brand]" id="brand'+i+'"  class="" ></td>                        <?php 
            } 
            
            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
            ?>  <td><input type="text" id="salt'+i+'"  name="prescription['+i+'][medicine_salt]" class=""  ></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="input-small medicine_type_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class="input-small dosage_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {  ?>  <td><input type="text" name="prescription['+i+'][medicine_duration]" class="medicine-name duration_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="medicine-name frequency_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            { 
            ?>  <td><input type="text" name="prescription['+i+'][medicine_advice]" class="medicine-name advice_val'+i+'"></td>                        <?php 
            } 
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('dialysis_booking/get_medicine_auto_vals/'); ?>",
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
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             
             
              var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('dialysis_booking/get_type_vals/'); ?>",
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
                 $(".medicine_type_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".medicine_type_val"+i).autocomplete({
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
                                             
             $.ajax({
             url : "<?php echo base_url('dialysis_booking/get_dosage_vals/'); ?>",
             dataType: "json",
             method: 'post',
           data: {
              name_startsWith: request.term,
              type: 'country_table',
             
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
                 $(".dosage_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".dosage_val"+i).autocomplete({
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
                                             
                 $.ajax({
                 url : "<?php echo base_url('dialysis_booking/get_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                                             
                 $.ajax({
                 url : "<?php echo base_url('dialysis_booking/get_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".frequency_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".frequency_val"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                 }
             });
             });
         
         $(function () {
            
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('dialysis_booking/get_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".advice_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".advice_val"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
                 /* script end*/
         m++;
         }
         
         function get_test_bytemp()
         {
                 

              var i=$('#test_name_table tr').length;
        
                $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'" placeholder="Click to add Test"><input type="hidden" name="test_id[]" id="test_id'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');
        
                $(function () {
                 
        
                  var getData = function (request, response) { 
                      row = i ;
                      $.ajax({
                      url : "<?php echo base_url('dialysis_prescription/get_test_vals/'); ?>" + request.term,
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
            
         }
</script>

</body>
</html>