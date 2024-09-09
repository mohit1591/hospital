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



<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

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
                   
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>OPD No.</strong></div>
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
                              <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> readonly=""> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?> readonly=""> Female
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
                        <a href="<?=base_url('prescription_template/add')?>" target="_blank" class="btn btn-success"><i class="fa fa-plus"></i> Add new</a>
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
                        $vital_val = get_vitals_value($vitals->id,$form_data['data_id'],2);
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
                                        <div class="well"><h4>Previous History</h4>
                                             <textarea name="prv_history" id="prv_history" class="media_100 ckeditor"><?php echo $form_data['prv_history']; ?></textarea> 
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                        <input type="text" name="chief_complaints_search" data-appendId="prv_history_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for Previous History">
                                        <a href="<?=base_url('previous_history')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                        <div class="well"><h4>Personal History</h4>
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
                                            <a href="<?=base_url('personal_history')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                        <div class="well"><h4>Chief Complaint</h4>
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
                                            <input type="text" name="chief_complaints_search" data-appendId="chief_complaints_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for chief complaints">
                                            <a href="<?=base_url('chief_complaints')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                        <div class="well"><h4>Examination</h4>
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
                                            <input type="text" name="chief_complaints_search" data-appendId="examination_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for examination">
                                            <a href="<?=base_url('examination')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                                        <div class="well"><h4>Diagnosis</h4>
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
                                            <input type="text" name="chief_complaints_search" data-appendId="diagnosis_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for diagnosis">
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
                        <tbody>
                            <tr>
                                <td>Test Name</td>
                                <td width="80">
                                    <a title="Click to add Test" href="javascript:void(0)" class="btn-w-60 addrow">Add</a>
                                </td>
                            </tr>
                            <?php 
                            if(!empty($prescription_test_list))
                            { 
                                $y=1;
                                foreach ($prescription_test_list as $prescription_test) 
                                {
                                    
                                ?>
                            <tr id="del<?php echo $y; ?>">
                                <td><input type="text" name="test_name[]" class="w-100 test_val<?php echo $prescription_test->id; ?>" value="<?php echo $prescription_test->test_name; ?>" placeholder="Click to add Test">
                                <input type="hidden" name="test_id[]" id="test_id<?php echo $prescription_test->id; ?>" value="<?php echo $prescription_test->test_id; ?>" ></td>
                                <td width="80">
                                    <a title="Click to Delete Test" onclick="delete_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
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
                                        url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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

                                      $('.test_val'+<?php echo $prescription_test->id; ?>).val(test_names[0]);
                                      $('#test_id'+<?php echo $prescription_test->id; ?>).val(test_names[1]);
                                      return false;
                                  }

                                $(".test_val"+<?php echo $prescription_test->id; ?>).autocomplete({
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
                                <td><input type="text" name="test_name[]" class="w-100 test_val" plcaceholder="Click to add Test"><input type="hidden" name="test_id[]" id="test_id" value="" ></td>
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
                            <a title="Click to add Medicine" href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
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

                        <input type="text" name="medicine_name[]" value="<?php echo $prescription_presc->medicine_name; ?>" onkeyup="get_medicine_autocomplete(<?php echo $l; ?>);" id="medicine_name<?php echo $l; ?>" placeholder="Click to add Medicine" id="medicine_name<?php echo $l; ?>">
                        <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_id; ?>"><p style="color:green" id="medicine_total<?php echo $l; ?>"></p>
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
                        <td><input type="text" name="medicine_name[]" class="medicine_val" onkeyup="get_medicine_autocomplete(0);" id="medicine_name0" placeholder="Click to add Medicine">
                        <input type="hidden" name="medicine_id[]" id="medicine_id0"><p style="color:green" id="medicine_total0"></p>
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
                        <a title="Delete Medicine" href="javascript:void(0)" class="btn-w-60" onclick="remove_prescription(0);">Delete</a>
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
                <div class="well"><h4>Suggestion</h4>
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
                    <input type="text" name="chief_complaints_search" data-appendId="suggestion_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for suggestion">
                    <a href="<?=base_url('suggestion')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
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
                <label><b>Remarks</b></label>
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
              <label><b>Next Appointment</b></label>
          </div>
          <div class="col-xs-10">
             <!-- <input type="text" name="next_appointment_date" class="datepicker date" data-date-format="dd-mm-yyyy HH:ii" value="< ?php echo $form_data['next_appointment_date']; ?>" /> -->
             
             <input type="text" name="next_appointment_date" class="w-130px datepicker m_input_default" placeholder="Date" value="<?php echo  $form_data['next_appointment_date']; ?>" >
                <input type="text" name="next_appointment_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['next_appointment_time']; ?>">
          </div>
      </div>
      <div class=" m-t-10">
                                             <div class="col-xs-8">
                                                <div class="well"><h4>Reason</h4>
                                                      <textarea class="form-control ckeditor" rows="8" id="next_reason" name="next_reason"><?php echo $form_data['next_reason']; ?></textarea>
                                                </div>
                                             </div>
                                             <div class="col-xs-4">
                                                <div class="well tab-right-scroll">
                                                      <!-- <input type="text" name="chief_complaints_search" data-appendId="next_reason_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for next appointment data"> -->
                                                      <a href="<?=base_url('next_appointment')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                                                      <select size="9" class="dropdown-box" name="next_reason_data"  id="next_reason_data" multiple="multiple" >
                                                         <?php
                                                            if(isset($next_appointment_list) && !empty($next_appointment_list))
                                                            {
                                                            foreach($next_appointment_list as $ps)
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

<!--Added By Nitin Sharma 06/02/2024-->
    <?php if(strcmp(strtolower($value->setting_name),'refer')=='0'){    ?>
            <div id="tab_refer" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                    <div class="row m-t-10">
                        <div class="col-xs-12">
                            <div class="row" style="margin-left:5px;">
                                 <h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-2"><b>Specialization</b>
                                </div>
                                 <div class="col-md-10" id="specilizationid">
                                   <select name="specialization" class="w-150px m_select_btn" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
                                      <option value="">Select Specialization</option>
                                      <?php
                                      if(!empty($specialization_list))
                                      {
                                        foreach($specialization_list as $specializationlist)
                                        {
                                          ?>
                                            <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                                          <?php
                                        }
                                      }
                                      ?>
                                    </select> 
                                     <?php if(in_array('44',$users_data['permission']['action'])) {
                                              ?>
                                            <a title="Add Specialization" href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new"><i class="fa fa-plus"></i> New</a>
                                       <?php } ?> 
                                 </div>
                               </div>
                            <div class="row">
                                 <div class="col-md-2"><b>Consultant </b>
                                   <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a doctor type which have two forms one is attended other is referral it may be both. </span></a></sup></div>
                                 <div class="col-md-10">
                                   <select name="refer_attended_doctor" class="w-150px m_select_btn" id="refer_attended_doctor">
                                      <option value="">Select Consultant</option>
                                      <?php
                                      //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
                                     if(!empty($form_data['specialization_id']))
                                     {
                                        $doctor_list = doctor_specilization_list($form_data['specialization_id'],$form_data['branch_id']); 
                        
                                        
                                        if(!empty($doctor_list))
                                        {
                                           foreach($doctor_list as $doctor)
                                           {  //if($doctor->id!==$referral_doctor_id){
                                            ?>   
                                              <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                                            <?php
                                             //}
                                           }
                                        }
                                     }
                                    ?>
                                    </select>
                        
                                    <?php if(in_array('122',$users_data['permission']['action'])) {
                                        ?>
                                            <a title="Add Consultant" class="btn-new" id="doctor_add_modal_2"><i class="fa fa-plus"></i> New</a>
                                        <?php } ?>
                                 </div>
                               </div>
                        </div>
                    </div>
                </div>
    </div>
<?php } ?>
<!--Ended By Nitin Sharma 06/02/2024-->
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
      <button class="btn-save" type="button"  onclick="window.location.href='<?php echo base_url('opdprescriptionhistory/lists/'.$form_data['patient_id']); ?>'" name=""><i class="fa fa-history"></i> History</button>
      <!-- <button class="btn-save" type="button" name=""><i class="fa fa-info-circle"></i> View</button>
      <button class="btn-save" type="button" name=""><i class="fa fa-upload"></i> Upload</button> -->
      <a href="<?php echo base_url('prescription'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>


      </div> <!-- row -->
 
</form>
<!--Added By Nitin Sharma 06/02/2024-->
    <div id="load_add_specialization_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!--Ended By Nitin Sharma 06/02/2024-->

<!-- Added By Nitin Sharma 06/02/2024 for add consultent -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- Added By Nitin Sharma 06/02/2024 for add consultent -->

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
      $.ajax({url: "<?php echo base_url(); ?>opd/get_template_data/"+template_id, 
        success: function(result)
        {
           
           load_test_values(template_id);
           load_prescription_values(template_id);
           load_values(result);
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
        CKEDITOR.instances['prv_history'].setData(obj.prv_history);
        CKEDITOR.instances['personal_history'].setData(obj.personal_history);
        CKEDITOR.instances['chief_complaints'].setData(obj.chief_complaints);
        CKEDITOR.instances['examination'].setData(obj.examination);
        CKEDITOR.instances['diagnosis'].setData(obj.diagnosis);
        CKEDITOR.instances['suggestion'].setData(obj.suggestion);
        // CKEDITOR.instances['remark'].setData(obj.remark);
       $('#appointment_date').val(obj.appointment_date);
       

       
    };

    function load_test_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>opd/get_template_test_data/"+template_id, 
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
      arr += '<tbody><tr><td>Test Name</td><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_test_bytemp()">Add</a></td></tr>';
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) { 
     
         arr += '<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'" placeholder="Click to add Test"><input type="hidden" name="test_id[]" id="test_id'+i+'" value="'+obj[index].test_id+'"></td><td><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>';
        
        $(function () {
          

          var getData = function (request, response) { 
              row = i ;
              $.ajax({
              url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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
        $.ajax({url: "<?php echo base_url(); ?>opd/get_template_prescription_data/"+template_id, 
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
                            ?><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_medicine_bytemp()">Add</a></td></tr>';
      i = 1;
      $.each(obj, function (index, value) {       
         pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" value="'+obj[index].medicine_name+'" onkeyup="get_medicine_autocomplete('+i+');" id="medicine_name'+i+'" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]" class="" value="'+obj[index].medicine_id+'" id="medicine_id'+i+'"></td> <?php 
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
                      } ?><td><a href="javascript:void(0)" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';

    }); 
      pres += '</tbody>'; 
      $("#prescription_name_table tbody").replaceWith(pres);
    

    }    
    
       


     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
      // var chief_complaints_val = $("#chief_complaints").val();
      var chief_complaints_val = CKEDITOR.instances.chief_complaints.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/complaints_name/"+complaints_id, 
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
      // var examination_val = $("#examination").val();
      var examination_val = CKEDITOR.instances.examination.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/examination_name/"+examination_id, 
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
           CKEDITOR.instances['examination'].setData(examination_value);
        } 
      }); 
  }); 

    $('#diagnosis_data').change(function(){  
      var diagnosis_id = $(this).val();
      // var diagnosis_val = $("#diagnosis").val();
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



     $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
      // var suggestion_val = $("#suggestion").val();
      var suggestion_val = CKEDITOR.instances.suggestion.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/suggetion_name/"+suggetion_id, 
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
           CKEDITOR.instances['suggestion'].setData(suggestion_value);
        } 
      }); 
  }); 

     $('#personal_history_data').change(function(){  
      var personal_history_id = $(this).val();
      var personal_history_val = CKEDITOR.instances.personal_history.getData();
      // var personal_history_val = $("#personal_history").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/personal_history_name/"+personal_history_id, 
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
      // var prv_history_val = $("#prv_history").val();
      var prv_history_val = CKEDITOR.instances.prv_history.getData();
      $.ajax({url: "<?php echo base_url(); ?>opd/prv_history_name/"+prv_history_id, 
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
  
   //   Added By Nitin Sharma 06/02/2024
    function get_doctor_specilization(specilization_id,branch_id){   
        console.log("specilization_id " , specilization_id);
        if(typeof branch_id === "undefined" || branch_id === null)
        {
            $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
          success: function(result)
          {
            $('#refer_attended_doctor').html(result); 
          } 
        });
        }
        else
        {
    
          $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
          success: function(result)
          {
            $('#refer_attended_doctor').html(result); 
          } 
        });
       }
    }
    
    function add_spacialization(){
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

//   Added By Nitin Sharma 06/02/2024
 /*$(".datepicker").datetimepicker({
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
    });*/
    
    $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });*/

$(document).ready(function(){
    // Added By Nitin Sharma 06/02/2024 for add consultent
    var $modal = $('#load_add_modal_popup');
    
    $('#doctor_add_modal_2').on('click', function(){
        $modal.load('<?php echo base_url().'doctors/add/' ?>',
        {
          //'id1': '1',
          //'id2': '2'
        },
        function(){
            $modal.modal('show');
        });
    
    });
    // Added By Nitin Sharma 06/02/2024 for add consultent
    
    
    $(".addrow").click(function(){ 

      var i=$('#test_name_table tr').length;

        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'" placeholder="Click to add Test"><input type="hidden" name="test_id[]" id="test_id'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');

        $(function () {
         /* var getData = function (request, response) { 
              $.getJSON(
                  "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
                  function (data) {
                      response(data);
                  });
          };*/

          var getData = function (request, response) { 
              row = i ;
              $.ajax({
              url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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


$(".addprescriptionrow").click(function(){

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr id="prescription_tr_'+i+'"><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" onkeyup="get_medicine_autocomplete('+i+');" id="medicine_name'+i+'" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'" ><p style="color:green" id="medicine_total'+i+'"></p></td>                        <?php 
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
        url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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
        url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
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
          
          var total_qty = 'Available quantity in stock '+names[5];
          $('#medicine_total'+row_id).text(total_qty);
          
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
            "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
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
            "<?php echo base_url('opd/get_type_vals/'); ?>" + request.term,
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
            "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
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
            "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
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
            "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
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
 
         function get_medicine_bytemp()
         {
           //alert();
         
           var m=1;
           var i=$('#prescription_name_table tr').length;
                 $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            ?><td><input type="text" id="medicine_name'+i+'" name="medicine_name[]" onkeyup="get_medicine_autocomplete('+i+');" class="medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
            ?>   <td><input type="text" name="medicine_brand[]" id="brand'+i+'"  class="" ></td>                        <?php 
            } 
            
            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
            ?>  <td><input type="text" id="salt'+i+'"  name="medicine_salt[]" class=""  ></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            { ?>  <td><input type="text" id="type'+i+'"  name="medicine_type[]" class="input-small medicine_type_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            { ?> <td><input type="text" name="medicine_dose[]" class="input-small dosage_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {  ?>  <td><input type="text" name="medicine_duration[]" class="medicine-name duration_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            ?> <td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            { 
            ?>  <td><input type="text" name="medicine_advice[]" class="medicine-name advice_val'+i+'"></td>                        <?php 
            } 
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>",
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('opd/get_type_vals/'); ?>",
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
            /* var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
             $.ajax({
             url : "<?php echo base_url('opd/get_dosage_vals/'); ?>",
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('opd/get_duration_vals/'); ?>",
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
                 url : "<?php echo base_url('opd/get_frequency_vals/'); ?>",
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
                 url : "<?php echo base_url('opd/get_advice_vals/'); ?>",
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
                      url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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
<script>
$(".select-box-search-event").on('keyup', function() {
   var level = $(this).val();
   var appendClass = $(this).attr("data-appendId");
   $.ajax({
      url:"<?php echo base_url(); ?>prescription/search_box_data",
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
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $("#template_list").select2();
      </script>
      <script>
   $('#next_reason_data').change(function(){  
      var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.next_reason.getData();
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
           CKEDITOR.instances['next_reason'].setData(diagnosiss_value);
    //     } 
    //   }); 
  });
</script>