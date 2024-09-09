<?php
$users_data = $this->session->userdata('auth_users');
//print_r($discharge_vital_setting_list);
//echo "<pre>"; print_r($patient_details); exit;
?>
<!DOCTYPE html>
<html>

<head>
  <title><?php echo $page_title . PAGE_TITLE; ?></title>
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
  <!-- <script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script> -->
</head>

<body>


  <div class="container-fluid">
    <?php
    $this->load->view('include/header');
    $this->load->view('include/inner_header');
    ?>
    <section class="userlist">

      <form id="ipd_patient_discharge_summary" class="form-inline" action="<?php echo current_url(); ?>" method="post">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
        <input type="hidden" name="ipd_id" id="ipd_id" value="<?php echo $form_data['ipd_id']; ?>" />
        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" />
        <!-- ////////////////////////////// -->
        <div class="row">

          <div class="col-md-6">
            <!-- ============================ -->
            <div class="row m-b-5">
              <div class="col-md-4"><b><?php echo $data = get_setting_value('PATIENT_REG_NO'); ?></b></div>
              <div class="col-md-8">
                <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['patient_code']; ?>" />
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>IPD No.</strong>
              </div>
              <div class="col-xs-8">
                <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['ipd_no']; ?>" />
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Patient Name <span class="star">*</span></strong>
              </div>
              <div class="col-xs-8">
                <select class="mr" name="simulation_id" id="simulation_id" disabled="true">
                  <option value="">Select</option>
                  <?php

                  if (!empty($simulation_list)) {
                    foreach ($simulation_list as $simulation) {
                      $selected_simulation = '';
                      if ($simulation->id == $patient_details['simulation_id']) {
                        $selected_simulation = 'selected="selected"';
                      }

                      echo '<option value="' . $simulation->id . '" ' . $selected_simulation . '>' . $simulation->simulation . '</option>';
                    }
                  }
                  ?>
                </select>
                <?php
                if (!empty($simulation_list)) {
                  foreach ($simulation_list as $simulation) {
                    $selected_simulation = '';
                    if ($simulation->id == $patient_details['simulation_id']) {
                      $selected_simulation = 'selected="selected"';
                    }

                    echo '<input type="hidden" name="simulation_id" value="' . $simulation->id . '">';
                  }
                }
                ?>
                <input type="text" name="patient_name" readonly id="patient_name" value="<?php echo $patient_details['patient_name']; ?>" class="mr-name txt_firstCap" autofocus />

                <?php if (!empty($form_error)) {
                  echo form_error('simulation_id');
                } ?>
                <?php if (!empty($form_error)) {
                  echo form_error('patient_name');
                } ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Mobile No.</strong>
              </div>
              <div class="col-xs-8">
                <input type="text" name="mobile_no" readonly data-toggle="tooltip" title="Allow only numeric." class="tooltip-text numeric" value="<?php echo $patient_details['mobile_no']; ?>" maxlength="10">

              </div>
            </div>
            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Gender </strong>
              </div>
              <div class="col-xs-8" id="gender">
                <input type="radio" name="gender" disabled="true" value="1" <?php if ($patient_details['gender'] == 1) {
                                                                              echo 'checked="checked"';
                                                                            } ?>> Male &nbsp;
                <input type="radio" name="gender" disabled="true" value="0" <?php if ($patient_details['gender'] == 0) {
                                                                              echo 'checked="checked"';
                                                                            } ?>> Female
                <input type="radio" name="gender" disabled="true" value="2" <?php if ($patient_details['gender'] == 2) {
                                                                              echo 'checked="checked"';
                                                                            } ?>> Others
                <?php if (!empty($form_error)) {
                  echo form_error('gender');
                } ?>
              </div>

            </div> <!-- row -->

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Age</strong>
              </div>
              <div class="col-xs-8">
                <input type="text" name="age_y" readonly class="input-tiny numeric" maxlength="3" value="<?php echo $patient_details['age_y']; ?>"> Y &nbsp;
                <input type="text" name="age_m" readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_m']; ?>"> M &nbsp;
                <input type="text" name="age_d" readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_d']; ?>"> D

              </div>
            </div> <!-- row -->
            <div class="row m-b-5">`
              <div class="col-xs-4"><label>Select Discharge Summary </label></div>
              <div class="col-xs-8">

                <select name="template_list" id="template_list">
                  <option value="">Select Template</option>
                  <?php
                  if (isset($template_list) && !empty($template_list)) {
                    foreach ($template_list as $templatelist) {
                      echo '<option class="grp" value="' . $templatelist->id . '">' . $templatelist->name . '</option>';
                    }
                  }
                  ?>
                </select>

              </div>
            </div> <!-- row -->

            <div class="row m-b-5">
              <div class="col-xs-4"></div>
              <div class="col-xs-8 font-default">
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 0) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(0);" value="0"> LAMA</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 1) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(0);" value="1"> REFERRAL</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 2) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(0);" value="2"> DISCHARGE</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 3) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(0);" value="3"> D.O.P.R</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 4) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(0);" value="4"> NORMAL</label>&nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if ($form_data['summery_type'] == 5) {
                                                                                                        echo 'checked="checked"';
                                                                                                      } ?> onchange="check_status(1);" value="5"> Expired</label>
              </div>
            </div> <!-- row -->

            <div class="row m-b-5" <?php if ($form_data['summery_type'] == 5) {
                                    } else { ?> style="display: none;" <?php } ?> id="death_date_div">
              <div class="col-xs-4"><label>Death Date</label></div>
              <div class="col-xs-8 font-default">
                <input type="text" name="death_date" class="w-130px death_datepicker" placeholder="Date" value="<?php echo  $form_data['death_date']; ?>">



              </div>
            </div>

            <div id="death_time_div" <?php if ($form_data['summery_type'] == 5) {
                                    } else { ?> style="display: none;" <?php } ?>>
                <div class="row m-b-5" >
                  <div class="col-xs-4"><label>Death Time</label></div>
                  <div class="col-xs-8 font-default">
                    <input type="text" name="death_time" class="w-65px death_time_datepicker3" placeholder="Time" value="<?php echo $form_data['death_time']; ?>">
                  </div>
                </div>

                <div class="row m-b-5">
                  <div class="col-xs-4"><label>Cause of Death</label></div>
                  <div class="col-xs-8 font-default">
                    <textarea type="text" id="cause_of_death" name="cause_of_death" class="textarea-100 ckeditor" placeholder="Cause of Death">
                    <?php echo $form_data['cause_of_death']; ?>
                    </textarea>
                  </div>
                </div>
            </div>

            <div id="content">
              <?php
              //echo "<pre>"; print_r($discharge_labels_setting_list);  exit; 
              if (!empty($discharge_labels_setting_list)) {
                $i = 1;
                foreach ($discharge_labels_setting_list as $value) {
                  //echo "<pre>"; print_r($value); 
                  if (strcmp(strtolower($value['setting_name']), 'cheif_complaints') == '0') {
              ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <select name="diagnosis_list" class="diagnosis_list col-md-12" id="diagnosis_list">
                          <option value="">Select diagnosis</option>
                          
                        </select>
                        <textarea class="textarea-100 ckeditor" id="chief_complaints" name="chief_complaints" style="height: 78.5px; width: 400px;"><?php echo $form_data['chief_complaints']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('chief_complaints');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'ho_presenting_illness') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea id="h_o_presenting" name="h_o_presenting" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['h_o_presenting']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('h_o_presenting');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'on_examination') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="on_examination" id="on_examination" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['on_examination']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('on_examination');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'past_history') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea id="past_history" name="past_history" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['past_history']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('past_history');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'menstrual_history') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea id="menstrual_history" name="menstrual_history" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['menstrual_history']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('menstrual_history');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'obstetric_history') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea id="obstetric_history" name="obstetric_history" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['obstetric_history']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('obstetric_history');
                        } ?>
                      </div>
                    </div> <!-- row -->

                    <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'vitals') == '0') {
                    if (!empty($discharge_vital_setting_list)) { ?>



                      <div class="row m-b-5">
                        <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                        echo $value['setting_value'];
                                                      } else {
                                                        echo $value['var_title'];
                                                      } ?></label></div>
                        <div class="col-xs-8">

                          <?php

                          $j = $i;
                          $r_i = 1;
                          $c_i = 1;
                          foreach ($discharge_vital_setting_list as $vital_value) {
                          ?>


                            <div class="row m-b-2 ">

                              <?php
                              if (strcmp(strtolower($vital_value['setting_name']), 'pulse') == '0') {
                              ?>
                                <div class="col-sm-6 ">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input name="vitals_pulse" id="vitals_pulse" class=" w-90px" type="text" value="<?php echo $form_data['vitals_pulse']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_pulse');
                                  } ?>
                                </div>
                              <?php
                              }

                              if (strcmp(strtolower($vital_value['setting_name']), 'chest') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input id="vitals_chest" name="vitals_chest" class=" w-90px" type="text" value="<?php echo $form_data['vitals_chest']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_chest');
                                  } ?>
                                </div>
                              <?php
                              }

                              ?>

                            </div><!-- innerrow -->




                            <div class="row m-b-2">

                              <?php
                              if (strcmp(strtolower($vital_value['setting_name']), 'bp') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input id="vitals_bp" type="text" name="vitals_bp" class=" w-90px" value="<?php echo $form_data['vitals_bp']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_bp');
                                  } ?>
                                </div>
                              <?php
                              }

                              if (strcmp(strtolower($vital_value['setting_name']), 'cvs') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input type="text" id="vitals_cvs" class=" w-90px ckeditor" name="vitals_cvs" value="<?php echo $form_data['vitals_cvs']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_cvs');
                                  } ?>
                                </div>
                              <?php
                              }

                              ?>

                            </div><!-- innerrow -->


                            <div class="row m-b-2">

                              <?php
                              if (strcmp(strtolower($vital_value['setting_name']), 'temp') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input type="text" id="vitals_temp" class=" w-90px" name="vitals_temp" value="<?php echo $form_data['vitals_temp']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_temp');
                                  } ?>
                                </div>
                              <?php
                              }

                              if (strcmp(strtolower($vital_value['setting_name']), 'cns') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input type="text" id="vitals_cns" class="w-90px" name="vitals_cns" value="<?php echo $form_data['vitals_cns']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_cns');
                                  } ?>
                                </div>
                              <?php
                              }

                              ?>

                            </div><!-- innerrow -->



                            <div class="row m-b-2">

                              <?php
                              if (strcmp(strtolower($vital_value['setting_name']), 'p_a') == '0') {
                              ?>
                                <div class="col-sm-6">
                                  <label class="w-80px"><?php if (!empty($vital_value['setting_value'])) {
                                                          echo $vital_value['setting_value'];
                                                        } else {
                                                          echo $vital_value['var_title'];
                                                        } ?></label>
                                  <input type="text" id="vitals_p_a" class="w-90px" name="vitals_p_a" value="<?php echo $form_data['vitals_p_a']; ?>">
                                  <?php if (!empty($form_error)) {
                                    echo form_error('vitals_p_a');
                                  } ?>
                                </div>
                              <?php
                              }
                              ?>

                            </div><!-- innerrow -->


                          <?php

                            $r_i++;
                            $c_i++;
                          }


                          ?>

                        </div>
                      </div>

                    <?php  }
                  }

                  if (strcmp(strtolower($value['setting_name']), 'provisional_diagnosis') == '0') {
                    ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="provisional_diagnosis" id="provisional_diagnosis" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['provisional_diagnosis']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('provisional_diagnosis');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'final_diagnosis') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="final_diagnosis" id="final_diagnosis" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['final_diagnosis']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('final_diagnosis');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'course_in_hospital') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="course_in_hospital" id="course_in_hospital" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['course_in_hospital']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('course_in_hospital');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }


                  if (strcmp(strtolower($value['setting_name']), 'surgery_preferred') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="surgery_preferred" id="surgery_preferred" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['surgery_preferred']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('surgery_preferred');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'operation_notes') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="operation_notes" id="operation_notes" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['operation_notes']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('operation_notes');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'treatment_given') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="treatment_given" id="treatment_given" class="textarea-100" style="height: 78.5px; width: 400px;"><?php echo $form_data['treatment_given']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('treatment_given');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }



                  if (strcmp(strtolower($value['setting_name']), 'investigation') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="investigations" id="investigations" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['investigations']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('investigations');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'condition_at_discharge_time') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="discharge_time_condition" id="discharge_time_condition" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['discharge_time_condition']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('discharge_time_condition');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'advise_on_discharge') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="discharge_advice" id="discharge_advice" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['discharge_advice']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('discharge_advice');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'review_time_and_date') == '0') {
                  ?>
                  
                    <div class="row m-b-5 review_time_date_div" style="display: <?php echo ($form_data['summery_type'] != 5) ? "block":"none"; ?>">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      
                      <div class="col-xs-8">
                        <input type="text" name="review_time_date" class="w-130px datepicker" placeholder="Date" value="<?php echo  $form_data['review_time_date']; ?>">
                        <input type="text" name="review_time" class="w-65px datepicker3" placeholder="Time" value="<?php echo $form_data['review_time']; ?>">

                        <?php if (!empty($form_error)) {
                          echo form_error('review_time_date');
                        } ?>
                      </div>
                      
                      
                    </div> <!-- row -->
                    

                  <?php
                  }

                  if (strcmp(strtolower($value['setting_name']), 'pulse') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">

                        <textarea name="vitals_pulse" id="vitals_pulse" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_pulse']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_pulse');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'chest') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">

                        <textarea name="vitals_chest" id="vitals_chest" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_chest']; ?></textarea>
                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_chest');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'bp') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">

                        <textarea name="vitals_bp" id="vitals_bp" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_bp']; ?></textarea>


                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_bp');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'cvs') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="vitals_cvs" id="vitals_cvs" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_cvs']; ?></textarea>

                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_cvs');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'temp') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="vitals_temp" id="vitals_temp" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_temp']; ?></textarea>

                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_temp');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'cns') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="vitals_cns" id="vitals_cns" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_cns']; ?></textarea>

                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_cns');
                        } ?>
                      </div>
                    </div> <!-- row -->

                  <?php

                  }

                  if (strcmp(strtolower($value['setting_name']), 'p_a') == '0') {
                  ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4"><label><?php if (!empty($value['setting_value'])) {
                                                      echo $value['setting_value'];
                                                    } else {
                                                      echo $value['var_title'];
                                                    } ?></label></div>
                      <div class="col-xs-8">
                        <textarea name="vitals_p_a" id="vitals_p_a" class="textarea-100 ckeditor" style="height: 78.5px; width: 400px;"><?php echo $form_data['vitals_p_a']; ?></textarea>

                        <?php if (!empty($form_error)) {
                          echo form_error('vitals_p_a');
                        } ?>
                      </div>
                    </div> <!-- row -->

              <?php

                  }
                  $i++;
                }
              } ?>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4"><label>Status</label></div>
              <div class="col-xs-8">
                <input type="radio" name="status" value="1" <?php if ($form_data['status'] == 1) {
                                                              echo 'checked="checked"';
                                                            } ?>> Active &nbsp;
                <input type="radio" name="status" value="0" <?php if ($form_data['status'] == 0) {
                                                              echo 'checked="checked"';
                                                            } ?>> Inactive
              </div>
            </div> <!-- row -->
            <!-- ============================ -->
          </div> <!-- left portion -->


          <div class="col-md-6">
            <!-- ======================== -->
            <div class="row m-b-5">
              <div class="col-xs-4"><strong>D.O.A/Time</strong> </div>
              <div class="col-xs-8 ">
                <?php
                $time = "";
                if (date('h:i:s', strtotime($patient_details['admission_time'])) != '12:00:00') {
                  $time = date('h:i A', strtotime($patient_details['admission_date'] . $patient_details['admission_time']));
                }
                echo date('d-m-Y', strtotime($patient_details['admission_date'])) . ' ' . $time; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4"><strong>D.O.D/Time</strong></div>
              <div class="col-xs-8">
                <?php if (!empty($patient_details['discharge_date']) && $patient_details['discharge_date'] != '0000-00-00 00:00:00') {
                  echo date('d-m-Y h:i A', strtotime($patient_details['discharge_date']));
                } ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Doctor Incharge</strong>
              </div>
              <div class="col-xs-8">
                <?php echo $patient_details['doctor_name']; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Spec. :</strong>
              </div>
              <div class="col-xs-8">
                <?php if (!empty($patient_details['specialization'])) {
                  echo $patient_details['specialization'];
                }; ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>MLC</strong>
              </div>
              <div class="col-xs-8">
                <?php if (!empty($patient_details['mlc'])) {
                  echo $patient_details['mlc'] . ' ';
                } ?>
              </div>
            </div>

            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong>Address</strong>
              </div>
              <div class="col-xs-8 ">
                <?php echo $patient_details['address']; ?>
              </div>
            </div>

            <div id="content1">
              <?php
              if (in_array('222', $users_data['permission']['section'])) {
                if (empty($form_data['data_id'])) {
                  if (!empty($discharge_field_master_list)) {
                    foreach ($discharge_field_master_list as $discharge_field) {
              ?>
                      <div class="row m-b-5">
                        <div class="col-xs-4">
                          <strong><?php echo ucfirst($discharge_field->discharge_lable); ?></strong>
                        </div>


                        <?php if ($discharge_field->type == 1) {

                        ?>
                          <div class="col-xs-8">
                            <input type="text" name="field_name[]" value="" />
                            <input type="hidden" value="<?php echo $discharge_field->id; ?>" name="field_id[]" />
                          </div>
                        <?php
                        } else {
                        ?>
                          <div class="col-xs-8">
                            <textarea name="field_name[]" id="field_name[]" class="ckeditor" style="height: 78.5px; width: 400px;"></textarea>
                            <input type="hidden" value="<?php echo $discharge_field->id; ?>" name="field_id[]" />

                          </div>
                        <?php
                        } ?>
                      </div>
                    <?php

                    }
                  }
                } else {

                  if (!empty($field_name)) {
                    foreach ($field_name as $field_names) {
                      $tot_values = explode('__', $field_names);
                      //print_r($tot_values);
                    ?>

                      <div class="row m-b-5">
                        <div class="col-xs-4">
                          <b><?php echo ucfirst($tot_values[1]); ?></b>
                        </div>

                        <?php if ($tot_values[3] == 1) { ?>
                          <div class="col-xs-8">
                            <input type="text" name="field_name[]" value="<?php echo $tot_values[0]; ?>" />
                            <input type="hidden" value="<?php echo $tot_values[2]; ?>" name="field_id[]" />
                            <?php
                            if (empty($tot_values[0])) {
                              if (!empty($form_error)) {
                                echo '<div class="text-danger">The ' . strtolower($tot_values[1]) . ' field is required.</div>';
                              }
                            }
                            ?>
                          </div>
                        <?php
                        } else {
                        ?>
                          <div class="col-xs-8">
                            <textarea name="field_name[]" id="field_name[]" class="ckeditor" style="height: 78.5px; width: 400px;"><?php echo $tot_values[0]; ?></textarea>
                            <input type="hidden" value="<?php echo $tot_values[2]; ?>" name="field_id[]" />
                          </div>
                        <?php
                        }
                        if (empty($tot_values[0])) {
                          if (!empty($form_error)) {
                            echo '<div class="text-danger">The ' . strtolower($tot_values[1]) . ' field is required.</div>';
                          }
                        }
                        ?>
                      </div>
              <?php
                    }
                  }
                }
              } //permission
              ?>

            </div>


            <?php //if($medicine_setting==1){ 
            ?>
            <div class="row m-b-5">
              <div class="col-xs-12">
                <table class="table table-bordered table-striped" id="prescription_name_table">
                  <thead>
                    <tr>
                      <th colspan="9">Medication Prescribed </th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>

                    <?php
                    $m = 0;

                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                      <td <?php echo $med_value->setting_value=="Advice"?'width="30%"':'' ?>
                       <?php if ($m = 0) { ?> class="text-left" <?php } ?>><?php if (!empty($med_value->setting_value)) {
                                                                              echo $med_value->setting_value;
                                                                            } else {
                                                                              echo $med_value->var_title;
                                                                            } ?></td>
                    <?php
                      $m++;
                    }
                    ?>

                    <td width="40">
                      <a href="javascript:void(0)" style="width:40px;" class="btn-w-60 addprescriptionrow">Add</a>
                    </td>
                    </tr>
                  </tbody>
                  <tbody id="Medication_Prescribed">
                    

                    <?php

                    if (!empty($prescription_test_list)) {
                      $l = 1;
                      //  print_r($prescription_presc_list);die;
                      foreach ($prescription_presc_list as $prescription_presc) {

                    ?> 
                        <tr>
                          <?php

                          foreach ($prescription_medicine_tab_setting as $tab_value) {
                            if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                          ?>
                              <td><input type="text" name="medicine_name[]" style="width:100px;" class="medicine_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>"></td>
                            <?php
                            }

                            if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                            ?>
                              <td><input type="text" name="medicine_brand[]" style="width:80px;" class="" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                            <?php
                            }

                            if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                            ?>
                              <td><input type="text" name="medicine_salt[]" style="width:80px;" id="salt<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                            <?php
                            }

                            if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                              <td><input type="text" style="width:80px;" name="medicine_type[]" id="type<?php echo $l; ?>" class="input-small medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                            <?php
                            }
                            if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                              <td><input type="text" style="width:80px;" name="medicine_dose[]" class="input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                            <?php
                            }
                            if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                              <td><input type="text" style="width:80px;" name="medicine_duration[]" class="medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                            <?php
                            }
                            if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                            ?>
                              <td><input type="text" style="width:80px;" name="medicine_frequency[]" class="medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                            <?php
                            }
                            if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                            ?>
                              <td width="40%"><input type="text"  name="medicine_advice[]" class="form-control medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
                          <?php }
                          }
                          ?>
                          <script type="text/javascript">
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.medicine_val' + <?php echo $l; ?>).val(names[0]);
                                $('#type' + <?php echo $l; ?>).val(names[1]);
                                $('#brand' + <?php echo $l; ?>).val(names[2]);
                                $('#salt' + <?php echo $l; ?>).val(names[3]);
                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".medicine_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });


                            $(function() {
                              var getData = function(request, response) {
                                $.getJSON(
                                  "<?php echo base_url('opd/get_type_vals/'); ?>" + request.term,
                                  function(data) {
                                    response(data);
                                  });
                              };

                              var selectItem = function(event, ui) {
                                $(".medicine_type_val" + <?php echo $l; ?>).val(ui.item.value);
                                return false;
                              }

                              $(".medicine_type_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });

                            $(function() {
                              var getData = function(request, response) {
                                $.getJSON(
                                  "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
                                  function(data) {
                                    response(data);
                                  });
                              };

                              var selectItem = function(event, ui) {
                                $(".dosage_val" + <?php echo $l; ?>).val(ui.item.value);
                                return false;
                              }

                              $(".dosage_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });

                            $(function() {
                              var getData = function(request, response) {
                                $.getJSON(
                                  "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
                                  function(data) {
                                    response(data);
                                  });
                              };

                              var selectItem = function(event, ui) {
                                $(".duration_val" + <?php echo $l; ?>).val(ui.item.value);
                                return false;
                              }

                              $(".duration_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });
                            $(function() {
                              var getData = function(request, response) {
                                $.getJSON(
                                  "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
                                  function(data) {
                                    response(data);
                                  });
                              };

                              var selectItem = function(event, ui) {
                                $(".frequency_val" + <?php echo $l; ?>).val(ui.item.value);
                                return false;
                              }

                              $(".frequency_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });

                            $(function() {
                              var getData = function(request, response) {
                                $.getJSON(
                                  "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
                                  function(data) {
                                    response(data);
                                  });
                              };

                              var selectItem = function(event, ui) {
                                $(".advice_val" + <?php echo $l; ?>).val(ui.item.value);
                                return false;
                              }

                              $(".advice_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });
                            });
                            /* script end*/
                            function delete_prescr_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("prescription_name_table").deleteRow(i);
                            }
                          </script>



                          <td width="40">
                            <a onclick="delete_prescr_row(this)" href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
                          </td>
                        </tr>
                      <?php $l++;
                      }
                    } else { ?>

                      <tr>
                        <?php
                        foreach ($prescription_medicine_tab_setting as $tab_value) {
                          if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                        ?>
                            <td><input type="text" style="width:100px;" name="medicine_name[]" class="medicine_val"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                          ?>
                            <td><input type="text" style="width:100px;" name="medicine_brand[]" class="" id="brand"></td>
                          <?php
                          }

                          if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                          ?>
                            <td><input type="text" style="width:100px;" name="medicine_salt[]" class="" id="salt"></td>
                          <?php
                          }

                          if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                            <td><input type="text" style="width:100px;" name="medicine_type[]" class="input-small" id="type"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                            <td><input type="text" style="width:100px;" name="medicine_dose[]" class="input-small dosage_val"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                            <td><input type="text" style="width:100px;" name="medicine_duration[]" class="medicine-name duration_val"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                          ?>
                            <td><input type="text" style="width:100px;" name="medicine_frequency[]" class="medicine-name frequency_val"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                          ?>
                            <td width="40%"><input type="text" name="medicine_advice[]" class="form-control medicine-name advice_val"></td>
                        <?php }
                        }
                        ?>
                        <td width="40">
                          <a href="javascript:void(0)" onclick="delete_test_row(this)" class="btn-w-60">Delete</a>
                        </td>
                      </tr>
                      <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--- Medicine Section ---->
            <?php //} 
            ?>
            <!--- Investigation Section ---->
            <div class="row m-b-5">
              <div class="col-xs-12">
                <table class="table table-bordered table-striped" id="test_name_table">
                  <thead>
                    <tr>
                      <th colspan="4">Investigation</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>

                    <td>Test Name</td>
                    <td>Date</td>
                    <td>Result</td>


                    <td width="80">
                      <a href="javascript:void(0)" class="btn-w-60 addtestrow">Add</a>
                    </td>
                    </tr>
                  </tbody>
                  <tbody id="Investigation">
                    
                        <?php
                        if(empty($discharge_test_list)){
                            if (!empty($booked_profile_list)) {
                              $l = 1;
                              foreach ($booked_profile_list as $profile)
                              {
                                ?>
                                    <tr>

                                    <td colspan="3"><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $profile->profile_name; ?>">
                                      <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $profile->id; ?>">

                                      <input type="hidden" name="interpretation[]" class="interpretation<?php echo $l; ?>" value="<?php echo $profile->interpretation; ?>">
                                          <input type="hidden" name="interpratation_data[]" class="interpratation_data<?php echo $l; ?>" value="<?php echo $profile->interpratation_data; ?>">

                                      <input name="test_date[]" type="hidden" class="datepicker1<?php echo $l; ?>" value="">
                                      <input name="test_result[]" type="hidden" class="medicine-name result_val<?php echo $l; ?>" value="">
                                    </td>

                      
                                    <td width="80">
                                    <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                  </td>
                                </tr>
                                <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                                <?php
                                $profile_test_list = $this->test->report_test_list($path_test_booking['id'],'','1,2',$profile->profile_id);
                             
                                if(!empty($profile_test_list))
                                {
                                    // echo "<pre>";print_r($profile_test_list);die;
                                    foreach($profile_test_list as $test)
                                    {
                                        $testDate = "";
                                        if(!empty($test->result_date))
                                        {
                                            $td = explode('-',$test->result_date);
                                            if($td[0] > 2000){
                                                $testDate = date('d-m-Y',strtotime($test->result_date));
                                            }
                                        }
                                      ?>
                                            <tr>

                                                <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $test->test_name; ?>">
                                                  <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $test->id; ?>">
                                                </td>

                                                <td><input name="test_date[]" type="text" class="datepicker1<?php echo $test->id; ?>" value="<?php echo $testDate ?>"></td>

                                                <td><input name="test_result[]" type="text" class="medicine-name result_val<?php echo $test->result; ?>" value="<?php echo $test->result; ?>"></td>

                                                <td width="80">
                                                <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                </td>
                                            </tr>
                                            <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                                      <?php
                                    }
                                }

                                $child_profile_list = $child_profile_data = $this->test->get_booking_profile($path_test_booking['id'],'',$profile->profile_id);
                                if(!empty($child_profile_list))
                                {
                                  foreach($child_profile_list as $child_profile)
                                  {
                                    ?>
                                          <tr>

                                            <td colspan="3"><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $child_profile->profile_name; ?>">
                                              <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $child_profile->id; ?>">
                                              <input name="test_date[]" type="hidden" class="datepicker1<?php echo $l; ?>" value="">
                                              <input name="test_result[]" type="hidden" class="medicine-name result_val<?php echo $l; ?>" value="">
                                            </td>


                                            <td width="80">
                                            <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                            </td>
                                            </tr>
                                            <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                                    <?php
                                    $cprofile_test_list = $this->test->report_test_list($path_test_booking['id'],'','1,2',$child_profile->profile_id,'1,2');
                                    if(!empty($cprofile_test_list))
                                    {
                                      foreach($cprofile_test_list as $cp_test)
                                      {
                                            $testDate = "";
                                            if(!empty($cp_test->result_date))
                                            {
                                                $td = explode('-',$cp_test->result_date);
                                                if($td[0] > 2000){
                                                    $testDate = date('d-m-Y',strtotime($cp_test->result_date));
                                                }
                                            }
                                        ?>
                                                <tr>

                                                  <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $cp_test->test_name; ?>">
                                                    <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $cp_test->id; ?>">
                                                  </td>

                                                  <td><input name="test_date[]" type="text" class="datepicker1<?php echo $cp_test->id; ?>" value="<?php echo $testDate ?>"></td>

                                                  <td><input name="test_result[]" type="text" class="medicine-name result_val<?php echo $cp_test->result; ?>" value="<?php echo $cp_test->result; ?>"></td>

                                                  <td width="80">
                                                  <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                  </td>
                                                </tr>
                                                <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                                        <?php
                                      }
                                    }
                                  }
                                }
                              }
                            }
                            


                            if(!empty($test_list))
                            {
                              
                              foreach($test_list as $test)
                              {
                                    $testDate = "";
                                    if(!empty($test->result_date))
                                    {
                                        $td = explode('-',$test->result_date);
                                        if($td[0] > 2000){
                                            $testDate = date('d-m-Y',strtotime($test->result_date));
                                        }
                                    }
                                ?>
                                      <tr>

                                        <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $test->test_name; ?>">
                                          <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $test->id; ?>">

                                          <input type="hidden" name="interpretation[]" class="interpretation<?php echo $l; ?>" value="<?php echo $test->interpretation; ?>">
                                          <input type="hidden" name="interpratation_data[]" class="interpratation_data<?php echo $l; ?>" value="<?php echo $test->interpratation_data; ?>">
                                        </td>

                                        <td><input name="test_date[]" type="text" class="datepicker1<?php echo $test->id; ?>" value="<?php echo $testDate ?>"></td>

                                        <td><input name="test_result[]" type="text" class="medicine-name result_val<?php echo $test->result; ?>" value="<?php echo $test->result; ?>"></td>

                                        <td width="80">
                                        <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                        </td>
                                        </tr>
                                        <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                                <?php
                              }
                            }
                        }
                        ?>



                    <?php
                    if (!empty($discharge_test_list)) {
                      $l = 1;
                      //  print_r($prescription_presc_list);die;
                      foreach ($discharge_test_list as $discharge_test) {
                        $testDate = "";
                        if(!empty($discharge_test->test_date))
                        {
                            $td = explode('-',$discharge_test->test_date);
                            if($td[0] > 2000){
                                $testDate = date('d-m-Y',strtotime($discharge_test->test_date));
                            }
                        }
                    ?>
                        <tr>

                          <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $discharge_test->test_name; ?>">
                            <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $discharge_test->id; ?>">
                            <input type="hidden" name="interpretation[]" class="interpretation<?php echo $l; ?>" value="<?php echo $discharge_test->interpretation; ?>">
                                          <input type="hidden" name="interpratation_data[]" class="interpratation_data<?php echo $l; ?>" value="<?php echo $discharge_test->interpratation_data; ?>">
                          </td>

                          <td><input type="text" name="test_date[]" class="datepicker1<?php echo $l; ?>" value="<?php echo $testDate; ?>"></td>

                          <td><input type="text" name="test_result[]" class="medicine-name result_val<?php echo $l; ?>" value="<?php echo $discharge_test->result; ?>"></td>



                          <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>



                          <td width="80">
                            <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                          </td>
                        </tr>
                      <?php $l++;
                      }
                    } else { ?>

                      <tr>

                        <td><input type="text" name="test_name[]" class="test_val"></td>

                        <input type="hidden" name="test_id[]" class="test_id" value="<?php echo @$discharge_test->id; ?>">

                        <td><input type="text" name="test_date[]" class="datepicker1"></td>


                        <td><input type="text" name="test_result[]" class="medicine-name result_val"></td>

                        <td width="80">
                          <a href="javascript:void(0)" onclick="delete_test_row(this)" style="width:50px;" class="btn-w-60">Delete</a>
                        </td>
                      </tr>
                      <script>
                            /* script start */
                            $(function() {
                              var getData = function(request, response) {
                                row = <?php echo $l; ?>;
                                $.ajax({
                                  url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                                  dataType: "json",
                                  method: 'post',
                                  data: {
                                    name_startsWith: request.term,
                                    type: 'country_table',
                                    row_num: row
                                  },
                                  success: function(data) {
                                    response($.map(data, function(item) {
                                      var code = item.split("|");
                                      return {
                                        label: code[0],
                                        value: code[0],
                                        data: item
                                      }
                                    }));
                                  }
                                });


                              };

                              var selectItem = function(event, ui) {

                                var names = ui.item.data.split("|");

                                $('.test_val' + <?php echo $l; ?>).val(names[0]);
                                $('.test_id' + <?php echo $l; ?>).val(names[1]);

                                //$(".medicine_val").val(ui.item.value);
                                return false;
                              }

                              $(".test_val" + <?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                  //$("#test_val").val("").css("display", 2);
                                }
                              });


                              $('.datepicker1' + <?php echo $l; ?>).datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
                            });



                            /* script end*/
                            function delete_test_row(r) {
                              var i = r.parentNode.parentNode.rowIndex;
                              document.getElementById("test_name_table").deleteRow(i);
                            }
                          </script>
                    <?php } ?>
                  </tbody>
                  <tbody id="printTemplateTest">
                      
                  </tbody>
                </table>
              </div>
            </div>
            <!--- Investigation Section ---->




            <!-- ======================== -->
          </div> <!-- right portion -->

        </div>
        <!-- ///////////////////////// -->

        <div class="row" style="padding-left:3em;">













        </div> <!-- modal-body -->

        <div class="modal-footer r-btn-cntr">
          <input type="submit" class="btn-update " name="submit" value="Save" />

          <button type="button" onclick="window.location.href='<?php echo base_url('ipd_booking'); ?>'" class="btn-cancel" data-number="1">Close</button>
        </div>
      </form>
     
      <script>
        
        function isNumberKey(evt) {
          var charCode = (evt.which) ? evt.which : event.keyCode;
          if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
          } else {
            return true;
          }
        }

        function check_status(check_status) {
          if (check_status == 5) {
            $(".review_time_date_div").hide();
          } else {
            $(".review_time_date_div").show();
          }

          if (check_status == 1) {

            $('#death_date_div').show();
            $('#death_time_div').show();
            // $("#cause_of_death").show();

          } else {
            $('#death_date_div').hide();
            $('#death_time_div').hide();
            // $("#cause_of_death").show();
          }

        }

        /*$("#ipd_patient_discharge_summary").on("submit", function(event) { 
          event.preventDefault(); 
          $('#overlay-loader').show();
          var ids = $('#type_id').val();
          if(ids!="" && !isNaN(ids))
          { 
            var path = 'edit/'+ids;
            var msg = 'IPD Discharge Summary successfully updated.';
          }
          else
          {
            var path = 'add/';
            var msg = 'IPD Discharge Summary successfully created.';
          } 
          //alert('ddd');return false;
          $.ajax({
            url: "<?php echo base_url('ipd_patient_discharge_summary/'); ?>"+path,
            type: "post",
            data: $(this).serialize(),
            success: function(result) {
              if(result==1)
              {
                $('#load_add_ipd_patient_discharge_summary_modal_popup').modal('hide');
                flash_session_msg(msg);
                get_ipd_patient_discharge_summary();
                reload_table();
              } 
              else
              {
                $("#load_add_ipd_patient_discharge_summary_modal_popup").html(result);
              }       
              $('#overlay-loader').hide();
            }
          });
        }); */

        $("button[data-number=1]").click(function() {
          $('#load_add_ipd_patient_discharge_summary_modal_popup').modal('hide');
        });

        function get_ipd_patient_discharge_summary() {
          $.ajax({
            url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/ipd_patient_discharge_summary_dropdown/",
            success: function(result) {
              $('#ipd_patient_discharge_summary_id').html(result);
            }
          });
        }

        $('#template_list').change(function() {
          var template_id = $(this).val();
          $.ajax({
            url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/get_template_data/" + template_id,
            success: function(result) {
              load_values(result);
            }
          });
        });
        

        function load_values(jdata) {
          var obj = JSON.parse(jdata);
          // CKEDITOR.replaceAll('ckeditor');
          var h_o_presenting = CKEDITOR.instances['h_o_presenting'];
          h_o_presenting.setData(obj.result.h_o_presenting);

          var chief_complaints = CKEDITOR.instances['chief_complaints'];
          chief_complaints.setData(obj.result.chief_complaints);

          var on_examination = CKEDITOR.instances['on_examination'];
          on_examination.setData(obj.result.on_examination);

          var vitals_pulse = CKEDITOR.instances['vitals_pulse'];
          vitals_pulse.setData(obj.result.vitals_pulse);

          var vitals_chest = CKEDITOR.instances['vitals_chest'];
          vitals_chest.setData(obj.result.vitals_chest);

          var vitals_bp = CKEDITOR.instances['vitals_bp'];
          vitals_bp.setData(obj.result.vitals_bp);

          var vitals_cvs = CKEDITOR.instances['vitals_cvs'];
          vitals_cvs.setData(obj.result.vitals_cvs);

          var vitals_temp = CKEDITOR.instances['vitals_temp'];
          vitals_temp.setData(obj.result.vitals_temp);

          var vitals_cns = CKEDITOR.instances['vitals_cns'];
          vitals_cns.setData(obj.result.vitals_cns);

          var vitals_p_a = CKEDITOR.instances['vitals_p_a'];
          vitals_p_a.setData(obj.result.vitals_p_a);

          var provisional_diagnosis = CKEDITOR.instances['provisional_diagnosis'];
          provisional_diagnosis.setData(obj.result.provisional_diagnosis);

          var final_diagnosis = CKEDITOR.instances['final_diagnosis'];
          final_diagnosis.setData(obj.result.final_diagnosis);

          var course_in_hospital = CKEDITOR.instances['course_in_hospital'];
          course_in_hospital.setData(obj.result.course_in_hospital);

          var investigations = CKEDITOR.instances['investigations'];
          investigations.setData(obj.result.investigations);

          var discharge_time_condition = CKEDITOR.instances['discharge_time_condition'];
          discharge_time_condition.setData(obj.result.discharge_time_condition);

          var discharge_advice = CKEDITOR.instances['discharge_advice'];
          discharge_advice.setData(obj.result.discharge_advice);

          // var review_time_date = CKEDITOR.instances['review_time_date'];
          // review_time_date.setData(obj.result.review_time_date);

          // $('#h_o_presenting').val(obj.result.h_o_presenting);
          // $('#chief_complaints').val(obj.result.chief_complaints);
          // $('#on_examination').val(obj.result.on_examination);
          // $('#vitals_pulse').val(obj.result.vitals_pulse);
          // $('#vitals_chest').val(obj.result.vitals_chest);
          // $('#vitals_bp').val(obj.result.vitals_bp);
          // $('#vitals_cvs').val(obj.result.vitals_cvs);
          // $('#vitals_temp').val(obj.result.vitals_temp);
          // $('#vitals_cns').val(obj.result.vitals_cns);
          // $('#vitals_p_a').val(obj.result.vitals_p_a);
          // $('#provisional_diagnosis').val(obj.result.provisional_diagnosis);
          // $('#final_diagnosis').val(obj.result.final_diagnosis);
          // $('#course_in_hospital').val(obj.result.course_in_hospital);
          // $('#investigations').val(obj.result.investigations);
          // $('#discharge_time_condition').val(obj.result.discharge_time_condition);
          // $('#discharge_advice').val(obj.result.discharge_advice);
          $('#review_time_date').val(obj.result.review_time_date);

          $html = ``;

          obj.discharge_summery_medicine.forEach(function(item,index){
            $html += `<tr>
                          <td>
                              <input value="${item.medicine_name}" type="text" style="width:100px;" name="medicine_name[]" class="medicine_val2 ui-autocomplete-input" autocomplete="off">
                          </td>                          
                          <td>
                              <input value="${item.medicine_type}" style="width:80px;" type="text" id="type${(index+1)}" name="medicine_type[]" class="input-small medicine_type_val">
                          </td>                         
                          <td>
                              <input value="${item.medicine_dose}" style="width:80px;" type="text" name="medicine_dose[]" class="input-small dosage_val${(index+1)}">
                          </td>                          
                          <td>
                              <input value="${item.medicine_duration}" type="text" style="width:80px;" name="medicine_duration[]" class="medicine-name duration_val${(index+1)}">
                          </td>                         
                          <td>
                              <input value="${item.medicine_frequency}" style="width:80px;" type="text" name="medicine_frequency[]" class="medicine-name frequency_val${(index+1)}">
                          </td>                          
                          <td width="40%">
                              <input value="${item.medicine_advice}"  type="text" name="medicine_advice[]" class="form-control medicine-name advice_val${(index+1)}">
                          </td>                        
                          <td width="80">
                              <a href="javascript:void(0);" class="btn-w-60 remove_prescription_row" style="width:50px;">Delete</a>
                          </td>
                      </tr>`;
          });
          $("#Medication_Prescribed").empty().append($html);

          $html2 = ``;
          obj.discharge_summary_test.forEach(function(item,index) {
            $html2 += `<tr>
                          <td>
                              <input value="${item.test_name}" type="text" name="test_name[]" class="test_val${(index+1)} ui-autocomplete-input" autocomplete="off">
                          </td>
                              
                          <td>
                              <input value="${item.id}" type="hidden" name="test_id[]" class="test_id${(index+1)}">
                              <input value="${item.test_date}" type="text" name="test_date[]" class="datepicker12">
                          </td>                       
                          <td>
                              <input value="${item.result}" type="text" name="test_result[]" class="medicine-name result_val${(index+1)}">
                          </td>                        
                          <td width="80">
                              <a href="javascript:void(0);" class="btn-w-60 remove_test_row">Delete</a>
                          </td>
                    </tr>`;
          });
          console.log(obj.discharge_summary_test,'--manoj');
          $("#printTemplateTest").empty().append($html2);
        };

        $(document).ready(function() {
          $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
          });
          $('.datepicker3').datetimepicker({
            format: 'LT'
          });


          $('.death_datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true
          });
          $('.death_time_datepicker3').datetimepicker({
            format: 'LT'
          });



        });


        /******* Add Medicine *************/


        $(".addprescriptionrow").click(function() {

          var i = $('#prescription_name_table tr').length;
          $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) {
                                                      if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                                    ?><td><input type="text" style="width:100px;" name="medicine_name[]" class="medicine_val' + i + '"></td>                        <?php
                                                                                                                                                      }
                                                                                                                                                      if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                                                                                                                                        ?>   <td><input style="width:80px;" type="text" name="medicine_brand[]" id="brand' + i + '"  class="" ></td>                        <?php
                                                                                                                                                          }

                                                                                                                                                          if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                                                                                                                                            ?>  <td><input style="width:80px;" type="text" id="salt' + i + '"  name="medicine_salt[]" class=""  ></td>                        <?php
                                                                                                                                                          }
                                                                                                                                                          if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>  <td><input style="width:80px;" type="text" id="type' + i + '"  name="medicine_type[]" class="input-small medicine_type_val"></td>                        <?php
                                                                                                                                                                                      }
                                                                                                                                                                                      if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?> <td><input  style="width:80px;" type="text" name="medicine_dose[]" class="input-small dosage_val' + i + '"></td>                        <?php
                                                                                                                                                                                      }
                                                                                                                                                                                      if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>  <td><input type="text" style="width:80px;" name="medicine_duration[]" class="medicine-name duration_val' + i + '"></td>                        <?php
                                                                                                                                                                                      }
                                                                                                                                                                                      if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                                                                                                                              ?> <td><input style="width:80px;" type="text" name="medicine_frequency[]" class="medicine-name frequency_val' + i + '"></td>                        <?php
                                                                                                                                                                                      }
                                                                                                                                                                                      if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                                                                                                                            ?>  <td width="40%"><input type="text" name="medicine_advice[]" class="form-control medicine-name advice_val' + i + '"></td>                        <?php
                                                                                                                                                                                      }
                                                                                                                                                                                    } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row" style="width:50px;">Delete</a></td></tr>');


          /* script start */
          $(function() {

            var getData = function(request, response) {
              row = i;
              $.ajax({
                url: "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
                dataType: "json",
                method: 'post',
                data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                  row_num: row
                },
                success: function(data) {
                  response($.map(data, function(item) {
                    var code = item.split("|");
                    return {
                      label: code[0],
                      value: code[0],
                      data: item
                    }
                  }));
                }
              });


            };

            var selectItem = function(event, ui) {

              var names = ui.item.data.split("|");

              $('.medicine_val' + i).val(names[0]);

              //$(".medicine_val").val(ui.item.value);
              return false;
            }

            $(".medicine_val" + i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 1,
              change: function() {
                //$("#test_val").val("").css("display", 2);
              }
            });
          });

        });
        $("#prescription_name_table").on('click', '.remove_prescription_row', function() {
          $(this).parent().parent().remove();
        });

        /******* Medicine *************/


        /************** Add Test ***********/
        $(".addtestrow").click(function() {

          var i = $('#test_name_table tr').length;
          $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="test_val' + i + '"></td><input type="hidden" name="test_id[]" class="test_id' + i + '" ><td><input type="text" name="test_date[]" class="datepicker1' + i + '"></td>                       <td><input type="text" name="test_result[]" class="medicine-name result_val' + i + '"></td>                        <td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_test_row">Delete</a></td></tr>');


          /* script start */
          $(function() {

            var getData = function(request, response) {
              row = i;
              $.ajax({
                url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
                dataType: "json",
                method: 'post',
                data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                  row_num: row
                },
                success: function(data) {

                  response($.map(data, function(item) {
                    var code = item.split("|");

                    return {
                      label: code[0],
                      value: code[0],
                      data: item
                    }
                  }));
                }
              });


            };

            var selectItem = function(event, ui) {
              var names = ui.item.data.split("|");
              $('.test_val' + i).val(names[0]);
              $('.test_id' + i).val(names[1]);
              //$(".medicine_val").val(ui.item.value);
              return false;
            }

            $(".test_val" + i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 1,
              change: function() {
                //$("#test_val").val("").css("display", 2);
              }
            });

            $('.datepicker1' + i).datepicker({
              format: 'dd-mm-yyyy',
              autoclose: true
            });
          });

        });
        $("#test_name_table").on('click', '.remove_test_row', function() {
          $(this).parent().parent().remove();
        });

        /************** Add Test ***********/


        /**********Auto complete ***********/
        $(function() {
          var i = $('#prescription_name_table tr').length;
          var getData = function(request, response) {
            row = i;
            $.ajax({
              url: "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
              dataType: "json",
              method: 'post',
              data: {
                name_startsWith: request.term,
                type: 'country_table',
                row_num: row
              },
              success: function(data) {
                response($.map(data, function(item) {
                  var code = item.split("|");
                  return {
                    label: code[0],
                    value: code[0],
                    data: item
                  }
                }));
              }
            });


          };

          var selectItem = function(event, ui) {

            var names = ui.item.data.split("|");
            $('.medicine_val').val(names[0]);
            $('#type').val(names[1]);
            $('#brand').val(names[2]);
            $('#salt').val(names[3]);
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

        /**********Auto complete ***********/


        /**********test Auto complete ***********/
        $(function() {
          var i = $('#test_name_table tr').length;
          var getData = function(request, response) {
            row = i;
            $.ajax({
              url: "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
              dataType: "json",
              method: 'post',
              data: {
                name_startsWith: request.term,
                type: 'country_table',
                row_num: row
              },
              success: function(data) {
                response($.map(data, function(item) {
                  var code = item.split("|");
                  return {
                    label: code[0],
                    value: code[0],
                    data: item
                  }
                }));
              }
            });


          };

          var selectItem = function(event, ui) {

            var names = ui.item.data.split("|");
            $('.test_val').val(names[0]);
            $('.test_id').val(names[1]);

            //$(".medicine_val").val(ui.item.value);
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

        /**********Test Auto complete ***********/
      </script>
      <script type="text/javascript">
        $('#content, #content1').on('change keyup keydown paste cut', 'textarea', function() {
          $(this).height(70).height(this.scrollHeight);
        }).find('textarea').change();
      </script>
    </section> <!-- section close -->
    <?php
    $this->load->view('include/footer');
    ?>

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

        var element = document.querySelector('cause_of_death');
        if (element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        }

        var element = document.querySelector('field_name[]');
        if (element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        }
        
      </script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.diagnosis_list').select2({
            ajax: {
                url: '<?= base_url('medication_chart/diagnosis_list') ?>',
                dataType: 'json',
                data: function(params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.diagnosis,
                                id: item.id
                            }
                        })
                    };
                }
                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });
    </script>
    <script>
      $("#diagnosis_list").change(function(){
        var diagnosis_id = $(this).val();
        $.ajax({
          url: "<?php echo base_url('medication_chart/diagnosis_detail')?>",
          type: "get",
          dataType: "json",
          data: {diagnosis_id: diagnosis_id},
          success: function(data){
            var content = CKEDITOR.instances['chief_complaints'].getData();
            var chief_complaints = CKEDITOR.instances['chief_complaints'];
          chief_complaints.setData(content+data.diagnosis);
            // CKEDITOR.instances['chief_complaints'].setData(data.diagnosis);
          }
        });
      })
    </script>