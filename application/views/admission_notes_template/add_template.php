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
                            $i=1; 
                            foreach ($prescription_tab_setting as $value) { 

                                 
                            ?>
                                <li <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
                                <?php 
                            $i++;
                            }
                         ?>
                            
                        </ul>

                        <div class="tab-content">


            <?php 
            $j=1; 
            foreach ($prescription_tab_setting as $value) 
            { 
            ?>
       <div class="tab-content">

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
                        <input type="text" name="chief_complaints_search" data-appendId="chief_complaints_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Chief Complaints">
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
                      <textarea name="examination" id="examination" class="media_100 ckeditor"><?php echo $form_data['examination']; ?></textarea>   
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                    <input type="text" name="chief_complaints_search" data-appendId="examination_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Examination">
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
                    <input type="text" name="chief_complaints_search" data-appendId="diagnosis_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for diagnosis data">
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
                                 <?php if(!empty($test_template_data)) { 
                                 $y=1; 
                                    foreach ($test_template_data as $testtemplate) { 
                                    ?>
                                     <tr>
                                    <td><input type="text" name="test_name[]" class="w-100 test_val<?php echo $y; ?>" value="<?php echo $testtemplate->test_name; ?>">
                                    <input type="hidden" name="test_id[]" id="test_id<?php echo $y; ?>" value="<?php echo $testtemplate->test_id; ?>"></td>
                                    <td><a href="javascript:void(0)" class="btn-w-60">Delete</a>
                                    </td>
                                    </tr>

                                   <script type="text/javascript">
                              $(function () {
                                      var getData = function (request, response) { 
                                        row = <?php echo $y; ?>;
                                        $.ajax({
                                        url : "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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
                                         
                                      }
                                  });
                                  });

                            </script>

                            <?php $y++;   
                                    }
                                }else{
                                  ?>
                                    <tr>
                                    <td><input type="text" name="test_name[]" class="w-100 test_val">
                                    <input type="hidden" name="test_id[]" id="test_id" value=""></td>
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
                <td width="80">
                    <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                </td>
            </tr>
            <?php if(!empty($prescription_template_data)) { 
             $l=1; 
                foreach ($prescription_template_data as $prescriptiontemplate) { 
                ?>
                 <tr>
                <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
    <td><input type="text" name="medicine_name[]" class="medicine_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_name; ?>"><input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_id; ?>"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
    {
    ?>
    <td><input type="text" name="medicine_salt[]" class="" id="salt<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_salt; ?>"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
    {
    ?>
    <td><input type="text" name="medicine_brand[]" class="" id="brand<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_brand; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
    { ?>
    <td><input type="text" name="medicine_type[]" class="input-small" id="type<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_type; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    { ?>
    <td><input type="text" name="medicine_dose[]" class="input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_dose; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    {  ?>
    <td><input type="text" name="medicine_duration[]" class="medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_duration; ?>"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_frequency; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="medicine_advice[]" class="medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_advice; ?>"></td>
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
                              url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
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
                                $('#brand'+<?php echo $l; ?>).val(names[2]);
                                $('#salt'+<?php echo $l; ?>).val(names[3]);
                                $('#medicine_id'+<?php echo $l; ?>).val(names[4]);
                              return false;
                          }

                          $(".medicine_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });


                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
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
                              minLength: 1,
                              change: function() {
                                 
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
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
                                  
                              }
                          });
                          });
                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
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
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
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
                              minLength: 1,
                              change: function() {
                                  
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
               <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
    <td><input type="text" name="medicine_name[]" class="medicine_val"><input type="hidden" name="medicine_id[]" id="medicine_id">
    </td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
    {
    ?>
    <td><input type="text" name="medicine_salt[]" id="salt" class=""></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
    {
    ?>
    <td><input type="text" name="medicine_brand[]" id="brand" class="" ></td>
    <?php 
    }  
    if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
    { ?>
    <td><input type="text" name="medicine_type[]"  id="type" class="input-small"></td>
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
                        <input type="text" name="chief_complaints_search" data-appendId="suggestion_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Procedure">
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
        <!--Changes by Nitin Sharma 19/02/2024-->
         <?php if(strcmp(strtolower($value->setting_name),'remarks')=='0'){   ?>
         <div id="tab_remarks" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
            <div class="well tab-right-scroll" style="padding:5px 10px;">
                <div class="row m-t-10">
                    <div class="col-xs-12">
                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                            <textarea class="form-control ckeditor" rows="8" id="remark" name="remark"><?php echo $form_data['remark']; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <?php } ?>
         <!--Changes by Nitin Sharma 19/02/2024-->
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
        <!--Added by Nitin Sharma 19th Feb 2024-->
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
                <input type="text" name="chief_complaints_search" data-appendId="history_presenting_illness_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for History of Presenting Illness">
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
                <input type="text" name="chief_complaints_search" data-appendId="obstetrics_menstrual_history_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for Obstetrics Menstrual History">
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
                <input type="text" name="chief_complaints_search" data-appendId="family_history_disease_data" class="dropdown-box select-box-search-event" style="width: 352px;" placeholder="Type here for family history disease">
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
        <div class="well tab-right-scroll" style="padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-12">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark1" name="remark1"><?php echo $form_data['remark1']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
     
      <?php if(strcmp(strtolower($value->setting_name),'remark2')=='0'){   ?>

    <div id="tab_remark2" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-12">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark2" name="remark2"><?php echo $form_data['remark2']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
     
      <?php if(strcmp(strtolower($value->setting_name),'remark3')=='0'){   ?>

    <div id="tab_remark3" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="well tab-right-scroll" style="padding:5px 10px;">
            <div class="row m-t-10">
                <div class="col-xs-12">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark3" name="remark3"><?php echo $form_data['remark3']; ?></textarea>
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
                <div class="col-xs-12">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control ckeditor" rows="8" id="remark4" name="remark4"><?php echo $form_data['remark4']; ?></textarea>
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
                <div class="col-xs-12">
                    <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                        <textarea class="form-control" rows="8" id="remark5" name="remark5"><?php echo $form_data['remark5']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <?php } ?>
    <!--Ended by Nitin Sharma 19th Feb 2024-->
        
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

  <button class="btn-save" type="button" name="" data-dismiss="modal" onclick="window.location.href='<?php echo base_url('admission_notes_template'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
  </div>


  </div> <!-- row -->
        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">
function delete_prescription_medicine(val,temp_id)
{
  
  var prescription_medicine_id = val;
  var templ_id = temp_id
  $.ajax({url: "<?php echo base_url(); ?>admission_notes_template/delete_pres_medicine/"+prescription_medicine_id+'/'+templ_id, 
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
    $('#'+vals).addClass('class','in');
    $('#'+vals).addClass('class','active');
  }

     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_complaints_name/"+complaints_id, 
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
           CKEDITOR.instances['chief_complaints'].setData(chief_complaints_value);
        } 
      }); 
  });


    $('#examination_data').change(function(){  
      var examination_id = $(this).val();
      var examination_val = $("#examination").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/examination_name/"+examination_id, 
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
      var diagnosis_val = $("#diagnosis").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_diagnosis_name/"+diagnosis_id, 
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
           CKEDITOR.instances['diagnosis'].setData(diagnosiss_value);
        } 
      }); 
  });



   

  $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
      var suggestion_val = $("#suggestion").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_suggetion_name/"+suggetion_id, 
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
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_personal_history_name/"+personal_history_id, 
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
      $.ajax({url: "<?php echo base_url(); ?>opd/admission_prv_history_name/"+prv_history_id, 
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

// Added by Nitin Sharma 19th Feb 2024
   $('#history_presenting_illness_data').change(function(){
        var illness_id = $(this).val();
        var illness_val = $("#history_presenting_illness").val();
        $.ajax({url: "<?php echo base_url(); ?>opd/admission_illness_name/"+illness_id, 
            success: function(result)
            {
                if(illness_val != ""){
                    var illness_value = `${illness_val},${result}`;
                }else{
                    var illness_value = result;
                }   
              CKEDITOR.instances['history_presenting_illness'].setData(illness_value);
            } 
        });
    });
    
    $('#obstetrics_menstrual_history_data').change(function(){
        var obs_mens_his_id = $(this).val();
        var obs_mens_his_val = $("#obstetrics_menstrual_history").val();
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
        var family_history_disease_val = $("#family_history_disease").val();
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
    
    // Ended by Nitin Sharma 19th Feb 2024

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });

$(document).ready(function(){
    
    var examination_type = "<?php echo $form_data['examination_type'] ?>"; // Added by Nitin Sharma
    examinationShowHide(examination_type); // Added By Nitin Sharma
    
    $(".addrow").click(function(){

      var i=$('#test_name_table tr').length;
        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'"><input type="hidden" name="test_id[]" id="test_id'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');


        $(function () {

          
                var getData = function (request, response) { 
                  row = i;
                  $.ajax({
                  url : "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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
          /*
          var getData = function (request, response) { 
              $.getJSON(
                  "< ?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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
                 
              }
          });*/
          


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
                        ?><td><input type="text" name="medicine_name[]"class="medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>                        <td><input type="text" id="salt'+i+'" name="medicine_salt[]" class=""></td>                        <?php 
                        }

                         if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>                        <td><input type="text" id="brand'+i+'" name="medicine_brand[]" class="" ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>                        <td><input type="text" id="type'+i+'" name="medicine_type[]" class="input-small"></td>                        <?php 
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
        url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
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
          $('#medicine_id'+i).val(names[4]);
        return false;
    }

    $(".medicine_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
           
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
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
            
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
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
            
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
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
            
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
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


  

    /*var selectItem = function (event, ui) {
        $("#test_val").val(ui.item.value);
        return false;
    }*/

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
/*$(function () {
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
        minLength: 2,
        change: function() {
            
        }
    });
    });*/


$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
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
          $('#medicine_id').val(names[4]);
        return false;
    }

    $(".medicine_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
           
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
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
            
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
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
            
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
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
            
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
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