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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
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
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<style>
*{margin:0;padding:0;box-sizing:border-box;}

.grid-frame3{display:block;}
.grid-frame3 .grid_tbl{border-collpase:collpase;border:1px solid #aaa;font:13px arial;}
.grid-frame3 .grid_tbl td{border:1px solid #aaa;border-top:none;border-left:none;padding:0 4px;}
.grid-frame3 .grid_tbl td input.w-40px{width:40px;padding:2px;}
.grid-frame3 .grid_tbl td select.w-60px{width:60px;padding:2px;}
</style>
 

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

<input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
                <div class="row">
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>Template Name <span class="star">*</span></label></div>
                            <div class="col-xs-8">
                                <input type="text" name="template_name" value="<?php echo $form_data['template_name']; ?>" autofocus>
                                <?php if(!empty($form_error)){ echo form_error('template_name'); } ?>
                            </div>
                        </div>
                        
                    </div> <!-- 5 -->
                    
                </div> <!-- row -->



                <div class="row m-t-10">
                    <div class="col-xs-11">
                        <ul class="nav nav-tabs">

                        <?php 
                          //print '<pre>'; print_r($prescription_tab_setting);
                            $i=1; 
                            foreach ($prescription_tab_setting as $value) 
                            { 

                              if($value->setting_name!='biometric_detail' &&  $value->setting_name!='pictorial_test')
                              {

                              
                               ?>
                                <li style="margin-top:2px;" <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
                                <?php 
                                $i++;
                              }

                             }
                         ?>
                            
                        </ul>

                        <div class="tab-content">


            <?php 
            $j=1; 
            foreach ($prescription_tab_setting as $value) 
            { 
            ?>
       <div class="tab-content"  style="overflow-x:auto;">

        <?php  //echo $value->setting_name;
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
                    <input type="text" name="chief_complaints_search" data-appendId="prv_history_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Previous History">
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
                        <textarea name="personal_history" id="personal_history" class="media_100 ckeditor"><?php echo $form_data['personal_history']; ?> </textarea>  
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
                        <input type="text" name="chief_complaints_search" data-appendId="personal_history_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Personal History">
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
                          <tr><td align="left" style="text-align:left;" height="30"><input type="text" name="cheif_complains[<?php echo $i; ?>][cheif_complain_name]" value="<?php echo $cheif_data->cheif_complains;?>"/></td>
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
                        <input type="text" name="chief_complaints_search" data-appendId="chief_complaints_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for chief complaints">
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
         <?php if(strcmp(strtolower($value->setting_name),'examination')=='0'){  ?>
        <div id="tab_examination" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                      <textarea name="examination" id="examination" class="media_100 ckeditor"><?php echo $form_data['examination']; ?></textarea>   
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
                        <input type="text" name="chief_complaints_search" data-appendId="examination_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for examination">
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
                        <input type="text" name="chief_complaints_search" data-appendId="diagnosis_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for diagnosis">
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
                        <input type="text" name="chief_complaints_search" data-appendId="systemic_illness_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for systemic illness">
                        <select size="9" class="dropdown-box systemic_illness_data" name="systemic_illness_data"  id="systemic_illness_data" multiple="multiple" >
                         <?php
                            if(isset($systemic_illness) && !empty($systemic_illness))
                            {
                              foreach($systemic_illness as $systemic_illnes)
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
                                 <?php if(!empty($test_template_data)) { 
                                 $y=1; 
                                    foreach ($test_template_data as $testtemplate) { 
                                    ?>
                                     <tr>
                                    <td><input type="text" name="test_name[]" class="w-100 test_val<?php echo $y; ?>" value="<?php echo $testtemplate->test_name; ?>"></td>
                                    <td><a href="javascript:void(0)" class="btn-w-60 remove_row">Delete</a>
                                    </td>
                                    </tr>

                                   <script type="text/javascript">
                              $(function () {
                                  var getData = function (request, response) { 
                                      $.getJSON(
                                          "<?php echo base_url('eye/prescription_template/get_test_vals/'); ?>" + request.term,
                                          function (data) {
                                              response(data);
                                          });
                                  };

                                  var selectItem = function (event, ui) {
                                      $(".test_val"+<?php echo $y; ?>).val(ui.item.value);
                                      return false;
                                  }

                                  $(".test_val"+<?php echo $y; ?>).autocomplete({
                                      source: getData,
                                      select: selectItem,
                                      minLength: 2,
                                      change: function() {
                                          //$("#test_val").val("").css("display", 2);
                                      }
                                  });
                                  });

                            </script>

                            <?php $y++;   
                                    }
                                }else{
                                  ?>
                                    <tr>
                                    <td><input type="text" name="test_name[]" class="w-100 test_val"></td>
                                    <td width="80">
                                        <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                    </td>
                                </tr>

                                  <?php   
                                }
                                ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php if(strcmp(strtolower($value->setting_name),'prescription')=='0'){  ?>
  <div id="tab_prescription" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
  <div class="row m-t-10" style="padding:10px;">
  <div class="col-xs-12">
  <div class="well tab-right-scroll" style="border:none;">
    <table class="table table-bordered table-striped" id="prescription_name_table">
        <tbody>
            <tr>
            <td>Eye Drop</td>
                <?php 
                    $m=0;
                    
                    foreach ($prescription_medicine_tab_setting as $med_value) 
                    { 
                       
                       if($med_value->setting_name!='date')
                       {?>
                        <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                       <?php }
                      ?>
                            
                                <?php 
                           $m++; 
                    }            
                            ?>
                <td width="80">
                    <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                </td>
            </tr>
            <?php  if(!empty($prescription_template_data)) { 

                 $l=1; 
                foreach ($prescription_template_data as $prescriptiontemplate) {
                   //print '<pre>'; print_r($prescriptiontemplate);
                ?>
                
                 <tr>
                  <td ><input type="checkbox" name="is_eye_drop" id="is_eye_drop<?php echo $l;?>" value="1" onclick="check_eye_drop(<?php echo $l ?>);"/></td>

                <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
      <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="w-100px medicine_val<?php echo $l; ?>" id="medicine_name<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_name; ?>"></td>
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
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" class="w-100px input-small" id="type<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_type; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    { ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class="w-100px  input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_dose; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    {  ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class="w-100px medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_duration; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="w-100px medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_frequency; ?>"></td>
    <?php 
    } 


    if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
    {

    ?>
    <td class="right_eye_appned<?php echo $l; ?>"><input type="checkbox" name="prescription[<?php echo $l; ?>][medicine_right_eye]" value="2" class="medicine-name hide right_eye_val<?php echo $l; ?>" <?php if($prescriptiontemplate->right_eye==2){echo 'checked';}?>></td>
    <?php 
    }


     if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
    {
    ?>
    <td class="left_eye_appned<?php echo $l; ?>"><input type="checkbox" name="prescription[<?php echo $l; ?>][medicine_left_eye]" value="1" class="medicine-name hide left_eye_val<?php echo $l; ?>" <?php if($prescriptiontemplate->left_eye==1){echo 'checked';}?>></td>
    <?php 
    }

    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="w-100px medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_advice; ?>"></td>
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
                                $('#medicine_company'+<?php echo $l; ?>).val(names[2]);
                                $('#salt'+<?php echo $l; ?>).val(names[3]);
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
            }
            else
            {
                ?>
                 <tr>
                  <td><input type ="checkbox" id="is_eye_drop1" value="1" onclick="check_eye_drop(1);"/></td>
               <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_name]" class="medicine_val w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_salt]" id="salt" class="w-100px"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_company]" id="medicine_company" class="w-100px" ></td>
    <?php 
    }  
    if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
    { ?>
    <td><input type="text" name="prescription[1][medicine_type]"  id="type" class="input-small w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    { ?>
    <td><input type="text" name="prescription[1][medicine_dose]" class="input-small dosage_val"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    {  ?>
    <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-name duration_val w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-name frequency_val w-100px"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-name advice_val w-100px"></td>
    <?php } 



  if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
  { 
  ?>
    <td class="right_eye_appned1"><input type="checkbox" name="prescription[1][medicine_right_eye]" value="2" class="medicine-name right_eye_val1 hide"></td>
    <?php }
       if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
    { 
    ?>
    <td class="left_eye_appned1"><input type="checkbox" value="1"  name="prescription[1][medicine_left_eye]" class="medicine-name left_eye_val1 hide"></td>
    <?php }  
  }
  ?>
              <!--  <td width="80">
                   <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                </td> -->


            </tr>
                <?php 
             }
             ?>
           

           
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
                        <input type="text" name="chief_complaints_search" data-appendId="suggestion_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for suggestion">
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
                    <textarea class="form-control ckeditor" rows="8" id="remark" name="remark"><?php echo $form_data['remark']; ?></textarea>
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
                        <input type="text" name="next_appointment_date" class="datepicker" value="<?php echo $form_data['next_appointment_date']; ?>" /> 
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
        

    </div>
  </div> <!-- 11 -->
  <div class="col-xs-1">
  <div class="prescription_btns">
  <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>

  <button class="btn-save" type="button" name="" data-dismiss="modal" onclick="window.location.href='<?php echo base_url('eye/prescription_template'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
  </div>


  </div> <!-- row -->
        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">
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
function delete_prescription_medicine(val,temp_id)
{
  
  var prescription_medicine_id = val;
  var templ_id = temp_id
  $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/delete_pres_medicine/"+prescription_medicine_id+'/'+templ_id, 
    success: function(result)
    {
       //alert(result); return;
       $("#tab_prescription").append(result);
    } 
  }); 
  
}
function tab_links(vals)
  {

    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('in');
    $('#'+vals).addClass('active');
  }

     $('#chief_complaints_data').change(function(){  
      var rowCount = $('#chief_complaints tr').length;
     var newrowcount=rowCount-1;

     //alert(newrowcount);

      var complaints_id = $(this).val();
      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_complaints_name/"+complaints_id+'/'+newrowcount, 
        success: function(result)
        {
           $(".chief_complaints_data option[value='"+complaints_id+"']").hide();
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
           $('#complain_grid').css('display','block');
           $('#chief_complaints').append(result);  
        } 
      }); 
  });


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
function remove_row(row_val)
{

    $("#chief_complaints").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".chief_complaints_data option[value='"+row_val+"']").show();
    
}

function remove_systemic_ill_row(row_val)
{
  
    $("#systemic_illness").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".systemic_illness_data option[value='"+row_val+"']").show();
    
}

function remove_diagnosis_row(row_val)
{
  
    $("#diagnosis").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".diagnosis_data option[value='"+row_val+"']").show();
    
}

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
           CKEDITOR.instances['examination'].setData(examination_value);
        } 
      }); 
  }); 

    $('#diagnosis_data').change(function(){  
      var diagnosis_id = $(this).val();
      var rowCount = $('#diagnosis tr').length;
      var newrowcount=rowCount-1;
      var diagnosis_val = $("#diagnosis").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_diagnosis_name/"+diagnosis_id+'/'+newrowcount, 
        success: function(result)
        {
           //$('#diagnosis').html(result);

         $(".diagnosis_data option[value='"+diagnosis_id+"']").hide();
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
           $('#diagnosis_grid').css('display','block');
           $('#diagnosis').append(result);  
           //$('#diagnosis').val(diagnosiss_value);

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
           CKEDITOR.instances['suggestion'].setData(suggestion_value);
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
           
           CKEDITOR.instances['personal_history'].setData(personal_history_value);
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
           
           CKEDITOR.instances['prv_history'].setData(prv_history_value);
        } 
      }); 
  }); 

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });

$(document).ready(function(){
    $(".addrow").click(function(){

      var i=$('#test_name_table tr').length;
        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');


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
              minLength: 2,
              change: function() {
                  //$("#test_val").val("").css("display", 2);
              }
          });
          });

    });
    $("#test_name_table").on('click','.remove_row',function(){
        $(this).parent().parent().remove();
    });

var m =2;
$(".addprescriptionrow").click(function(){

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><td><input type="checkbox" name="is_eye_drop" value="'+i+'" onclick="check_eye_drop('+i+');" id="is_eye_drop'+i+'"/></td><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]"class="w-100px medicine_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>                        <td><input type="text" id="salt'+i+'" name="prescription['+i+'][medicine_salt]" class="w-100px"></td>                        <?php 
                        }

                         if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
                        {
                        ?>                        <td><input type="text" id="medicine_company'+i+'" name="prescription['+i+'][medicine_company]" class="w-100px" ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
                        { ?>                        <td><input type="text" id="type'+i+'" name="prescription['+i+'][medicine_type]" class="w-100px input-small"></td>                        <?php 
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
                        ?>                        <td class="left_eye_append'+i+'"><input type="checkbox" value="1" name="prescription['+i+'][medicine_left_eye]" class="w-100px medicine-name hide left_eye_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>                        <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name advice_val'+i+'"></td>                        <?php } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');

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
          $('#medicine_company'+i).val(names[3]);
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

    });
    $("#prescription_name_table").on('click','.remove_prescription_row',function(){
        $(this).parent().parent().remove();
    });
});

$('#form_submit').on("click",function(){
       $('#prescription_form').submit();
  })


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
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
        minLength: 2,
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
          $('#medicine_company').val(names[3]);
          $('#salt').val(names[2]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".medicine_val").autocomplete({
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
        $(".dosage_val").val(ui.item.value);
        return false;
    }

    $(".dosage_val").autocomplete({
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
        $(".advice_val").val(ui.item.value);
        return false;
    }

    $(".advice_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
</script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>

<script>
$(".select-box-search-event").on('keyup', function() {
   var level = $(this).val();
   var appendClass = $(this).attr("data-appendId");
   $.ajax({
      url:"<?php echo base_url(); ?>eye/add_prescription/search_box_data",
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
<script src="<?=base_url('assets/ckeditor/ckeditor.js')?>"></script>
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