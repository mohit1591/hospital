<?php
$users_data = $this->session->userdata('auth_users');
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
                <input type="hidden" name="ipd_id" id="ipd_id" value="<?php echo $form_data['ipd_id']; ?>">

                <div class="row">

                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>IPD No.</strong></div>
                            <div class="col-xs-8">
                                <input id="ipd_no" type="text" class="ipd_no" name="ipd_no" value="<?php echo $form_data['ipd_no']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data = get_setting_value('PATIENT_REG_NO'); ?></strong></div>
                            <div class="col-xs-8">
                                <input type="text" id="patient_code" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Patient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" id="patient_name" name="patient_name" value="<?=get_simulation_name($form_data['simulation_id'])?> <?php echo $form_data['patient_name']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-2"><strong>Diagnosis</strong></div>
                            <div class="col-xs-10">
                                <select style="width:100%" id="diagnosis_list" name="diagnosis_id" class="diagnosis_list" value="<?php echo $all_details['diagnosis_id']; ?>"> </select>
                            </div>
                        </div>



                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input id="mobile_no" type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                                <input type="radio" class="gender" name="gender" value="1" <?php if ($form_data['gender'] == 1) {
                                                                                                echo 'checked="checked"';
                                                                                            } ?> readonly=""> Male &nbsp;
                                <input type="radio" name="gender" class="gender" value="0" <?php if ($form_data['gender'] == 0) {
                                                                                                echo 'checked="checked"';
                                                                                            } ?> readonly=""> Female
                                <?php if (!empty($form_error)) {
                                    echo form_error('gender');
                                } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" id="age_y" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>" readonly=""> Y &nbsp;
                                <input type="text" id="age_m" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>" readonly=""> M &nbsp;
                                <input type="text" id="age_d" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>" readonly=""> D
                                <?php if (!empty($form_error)) {
                                    echo form_error('age_y');
                                } ?>
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Date</strong></div>
                            <div class="col-xs-8">
                            <input type="datetime-local" class=""  value="<?=$all_details['handover_date']?>" name="handover_date">
                            </div>
                        </div>

                        <!-- new code by mamta -->


                        <!-- new code by mamta -->
                    </div> <!-- 5 -->
                </div> <!-- row -->
                <div class="row m-t-10">
                <div class="row">
                <div class="col-sm-12">
                   
                </div>
                <div class="col-sm-12">
                    <div class="form-group col-md-12">
                        <label for="">Situation</label>
                        <input type="text" class="form-control " style="width: 100%;" value="<?=$all_details['situation']?>" name="situation">
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <div class="form-group col-md-6">
                        <label for="">Select Template</label>
                        <select name="" id="template_list">
                            <option value="">Select Template</option>
                            <?php foreach ($templates_morning as $template) {?>
                                <option value="<?php echo $template['id'];?>"><?php echo $template['name'];?></option>
                            <?php }?>
                        </select>
                        <a href="<?=base_url('nurse_handover_template/add')?>" target="_blank" class="btn btn-success"><i class="fa fa-plus"></i> Add new</a>
                    </div>
                
                    <div class="form-group col-md-6">
                        <label for="">Select Shift</label>
                        <select name="shift" id="shift">
                            <option value="Morning" <?php echo $all_details['shift'] == 'Morning'?'selected':'' ?>>Morning Shift</option>
                            <option value="Evening" <?php echo $all_details['shift'] == 'Evening'?'selected':'' ?>>Evening Shift</option>
                            <option value="Night" <?php echo $all_details['shift'] == 'Night'?'selected':'' ?>>Night Shift</option>
                        </select>
                    </div>
                </div>
        
          <!-- <h4>Morning Shift</h4> -->
          <div class="form-group col-md-4">
            <label for="morning_shift_medical_history">Medical History:</label>
            <textarea class="form-control ckeditor" id="morning_shift_medical_history" name="morning_shift_medical_history" rows="2"><?=$all_details['morning_shift_medical_history']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_allergies">Allergies:</label>
            <textarea class="form-control ckeditor" id="morning_shift_allergies" name="morning_shift_allergies" rows="2"><?=$all_details['morning_shift_allergies']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_barthel_index">Barthel Index:</label>
            <textarea class="form-control ckeditor" id="morning_shift_barthel_index" name="morning_shift_barthel_index" rows="2"><?=$all_details['morning_shift_barthel_index']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_current_treatment">Current Treatment:</label>
            <textarea class="form-control ckeditor" id="morning_shift_current_treatment" name="morning_shift_current_treatment" rows="2"><?=$all_details['morning_shift_current_treatment']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_vital_signs">Vital Signs:</label>
            <input type="text" class="form-control mb-2" id="morning_shift_vital_temp" name="morning_shift_vital_temp" placeholder="Temp." value="<?=$all_details['morning_shift_vital_temp']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_hr" name="morning_shift_vital_hr" placeholder="HR" value="<?=$all_details['morning_shift_vital_hr']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_rr" name="morning_shift_vital_rr" placeholder="RR" value="<?=$all_details['morning_shift_vital_rr']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_bp" name="morning_shift_vital_bp" placeholder="BP" value="<?=$all_details['morning_shift_vital_bp']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_pain_scale" name="morning_shift_vital_pain_scale" value="<?=$all_details['morning_shift_vital_pain_scale']?>" placeholder="Pain Scale">
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_abnormal_lab">Abnormal Lab:</label>
            <textarea class="form-control ckeditor" id="morning_shift_abnormal_lab" name="morning_shift_abnormal_lab" rows="2"><?=$all_details['morning_shift_abnormal_lab']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_lines_fluids">Lines/Fluids:</label>
            <textarea class="form-control ckeditor" id="morning_shift_lines_fluids" name="morning_shift_lines_fluids" rows="2"><?=$all_details['morning_shift_lines_fluids']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_goals">Goals:</label>
            <textarea class="form-control ckeditor" id="morning_shift_goals" name="morning_shift_goals" rows="2"><?=$all_details['morning_shift_goals']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_pending_consultations">Pending Consultations:</label>
            <textarea class="form-control ckeditor" id="morning_shift_pending_consultations" name="morning_shift_pending_consultations" rows="2"><?=$all_details['morning_shift_pending_consultations']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_test_treatment_pending">Test/Treatment Pending:</label>
            <textarea class="form-control ckeditor" id="morning_shift_test_treatment_pending" name="morning_shift_test_treatment_pending" rows="2"><?=$all_details['morning_shift_test_treatment_pending']?></textarea>
          </div>
          <div class="form-group col-md-4">
            <label for="morning_shift_discharge_needs">Discharge Needs:</label>
            <textarea class="form-control ckeditor" id="morning_shift_discharge_needs" name="morning_shift_discharge_needs" rows="2"><?=$all_details['morning_shift_discharge_needs']?></textarea>
          </div>
        <div class="form-group">
            <div class="col-md-6">
                <label for="morning_shift_signature">Name/Signature (Handover from)</label>
                <input type="text" class="form-control" id="morning_shift_signature_from" name="morning_shift_signature_from" value="<?=$all_details['morning_shift_signature_from']?>">
            </div>
            <div class="col-md-6">
                <label for="morning_shift_signature">Name/Signature (Handover To)</label>
                <input type="text" class="form-control" id="morning_shift_signature_to" name="morning_shift_signature_to" value="<?=$all_details['morning_shift_signature_to']?>">
            </div>
        </div>
          <div class="form-group">
            <div class="col-md-6">
                <label for="morning_shift_date_time" style="display:block; margin-top: 20px">Date/Time:</label>
                <input type="datetime-local" style="display:block" id="morning_shift_date_time" name="morning_shift_date_time" value="<?=$all_details['morning_shift_date_time']?>">
            </div>
          </div>
      

        <!-- <div class="col-sm-6">
          <h4>Night Shift</h4>
          <div class="form-group">
            <label for="night_shift_medical_history">Medical History:</label>
            <textarea class="form-control ckeditor" id="night_shift_medical_history" name="night_shift_medical_history" rows="2"><?=$all_details['night_shift_medical_history']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_allergies">Allergies:</label>
            <textarea class="form-control ckeditor" id="night_shift_allergies" name="night_shift_allergies" rows="2"><?=$all_details['night_shift_allergies']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_barthel_index">Barthel Index:</label>
            <textarea class="form-control ckeditor" id="night_shift_barthel_index" name="night_shift_barthel_index" rows="2"><?=$all_details['night_shift_barthel_index']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_current_treatment">Current Treatment:</label>
            <textarea class="form-control ckeditor" id="night_shift_current_treatment" name="night_shift_current_treatment" rows="2"><?=$all_details['night_shift_current_treatment']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_vital_signs">Vital Signs:</label>
            <input type="text" class="form-control mb-2" id="night_shift_vital_temp" name="night_shift_vital_temp" placeholder="Temp." value="<?=$all_details['night_shift_vital_temp']?>">
            <input type="text" class="form-control mb-2" id="night_shift_vital_hr" name="night_shift_vital_hr" placeholder="HR" value="<?=$all_details['night_shift_vital_hr']?>">
            <input type="text" class="form-control mb-2" id="night_shift_vital_rr" name="night_shift_vital_rr" placeholder="RR" value="<?=$all_details['night_shift_vital_rr']?>">
            <input type="text" class="form-control mb-2" id="night_shift_vital_bp" name="night_shift_vital_bp" placeholder="BP" value="<?=$all_details['night_shift_vital_bp']?>">
            <input type="text" class="form-control mb-2" id="night_shift_vital_pain_scale" name="night_shift_vital_pain_scale" placeholder="Pain Scale" value="<?=$all_details['night_shift_vital_pain_scale']?>">
          </div>
          <div class="form-group">
            <label for="night_shift_abnormal_lab">Abnormal Lab:</label>
            <textarea class="form-control ckeditor" id="night_shift_abnormal_lab" name="night_shift_abnormal_lab" rows="2"><?=$all_details['night_shift_abnormal_lab']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_lines_fluids">Lines/Fluids:</label>
            <textarea class="form-control ckeditor" id="night_shift_lines_fluids" name="night_shift_lines_fluids" rows="2"><?=$all_details['night_shift_lines_fluids']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_goals">Goals:</label>
            <textarea class="form-control ckeditor" id="night_shift_goals" name="night_shift_goals" rows="2"><?=$all_details['night_shift_goals']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_pending_consultations">Pending Consultations:</label>
            <textarea class="form-control ckeditor" id="night_shift_pending_consultations" name="night_shift_pending_consultations" rows="2"><?=$all_details['night_shift_pending_consultations']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_test_treatment_pending">Test/Treatment Pending:</label>
            <textarea class="form-control ckeditor" id="night_shift_test_treatment_pending" name="night_shift_test_treatment_pending" rows="2"><?=$all_details['night_shift_test_treatment_pending']?></textarea>
          </div>
          <div class="form-group">
            <label for="night_shift_discharge_needs">Discharge Needs:</label>
            <textarea class="form-control ckeditor" id="night_shift_discharge_needs" name="night_shift_discharge_needs" rows="2"><?=$all_details['night_shift_discharge_needs']?></textarea>
          </div>
          <div class="form-group">
                <div class="col-md-6">
                        <label for="morning_shift_signature">Name/Signature (Handover from)</label>
                        <input type="text" class="form-control" id="night_shift_signature_from" name="night_shift_signature_from" value="<?=$all_details['night_shift_signature_from']?>">
                </div>
                <div class="col-md-6">
                    <label for="morning_shift_signature">Name/Signature (Handover To)</label>
                    <input type="text" class="form-control" id="night_shift_signature_to" name="night_shift_signature_to" value="<?=$all_details['night_shift_signature_to']?>">
                </div>
            </div>
          <div class="form-group">
            <div class="col-md-12">
                <label for="night_shift_date_time">Date/Time:</label>
                <input type="text" class="form-control datepicker" id="night_shift_date_time" name="night_shift_date_time" placeholder="__/__/20__ Time: __:__ AM/PM" value="<?=$all_details['night_shift_date_time']?>">
            </div>
          </div>
        </div> -->
      </div>
                </div>


                <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">

                <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
                <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">
                <br>
                <div class="col-xs-1">
                    <div class="prescription_btns">
                        <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>


                        <a href="<?php echo base_url('ipd_prescription'); ?>" class="btn-anchor">
                            <i class="fa fa-sign-out"></i> Exit
                        </a>






            </form>
        </section> <!-- section close -->
        <?php
        $this->load->view('include/footer');
        ?>

    </div><!-- container-fluid -->
    
    <script>
        $('#form_submit').on("click", function() {
            $('#prescription_form').submit();
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#myTab li:eq(1) a").tab('show');
        });
    </script>
    <script>
        function deleteMyRow(my_row) {
            $(".my_row_" + my_row).remove();
        }
    </script>
    <script>
        $(function() {
            var getData = function(request, response) {

                $.ajax({
                    url: "<?php echo base_url('medication_chart/get_ipd_details/'); ?>" + request.term,
                    dataType: "json",
                    method: 'post',
                    data: {
                        name_startsWith: request.term,
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
                // $(".ipd_no").val(ui.item.value);
                const data = ui.item.data.split("|");
                return get_ipd_full_details(data[2]);
            }

            $(".ipd_no").autocomplete({
                source: getData,
                select: selectItem,
                minLength: 1,
                change: function() {

                }
            });

            function get_ipd_full_details(booking_id) {
                $.ajax({
                    url: "<?php echo base_url('medication_chart/get_full_ipd_details/'); ?>/" + booking_id,
                    type: 'POST',
                    data: {
                        booking_id: booking_id
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        $("#patient_name").val(data.patient_name);
                        $("#age_y").val(data.age_y);
                        $("#age_m").val(data.age_m);
                        $("#age_d").val(data.age_d);
                        $("#ipd_no").val(data.ipd_no);
                        $("#patient_code").val(data.patient_code);
                        $("#mobile_no").val(data.mobile_no);
                        $("#ipd_id").val(data.id);
                        if (data.gender == 1) {
                            $("input.gender[value='1']").prop("checked", true);
                        } else if (data.gender == 2) {
                            $("input.gender[value='0']").prop("checked", true);
                        }
                    }
                });

            }
        });

        
    </script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
         $('.diagnosis_list').select2({
  ajax: {
    url: '<?=base_url('medication_chart/diagnosis_list')?>',
    dataType: 'json',
    data: function (params) {

        var queryParameters = {
            term: params.term
        }
        return queryParameters;
    },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
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
        var selectedTexts1 = "<?php echo $diagnosis_name; ?>";
if(selectedTexts1 != "")
  selectedTexts = selectedTexts1.split(';');
else
  selectedTexts = [];

    // Fetch the corresponding IDs and set them as selected
    if (selectedTexts.length > 0) {
        $.ajax({
            url: '<?=base_url('ipd_booking/diagnosis_listText')?>',
            dataType: 'json',
            type : "post",
            data : {term:selectedTexts},
            success: function(data) {
                console.log(data);
                var selectedIds = [];
                $.each(data, function(index, item) {
                    // selectedIds.push(item.id);

                    // Create a new option if it doesn't exist
                    // if (!$('.diagnosis_list option[value="' + item.id + '"]').length) {
                        var newOption = new Option(item.diagnosis, item.id, true, true);
                        $('.diagnosis_list').append(newOption).trigger('change');
                    // }
                });
            }
        });
    }
    </script>
    <script src="<?= base_url('assets/ckeditor/ckeditor.js') ?>"></script>
<script>
    function InitializeCkeditor() {
        var basicToolbar = [{
                name: 'basicstyles',
                items: ['Bold', 'Italic']
            },
            {
                name: 'editing',
                items: ['Scayt']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList']
            },
            {
                name: 'styles',
                items: ['Styles', 'Format']
            },
            {
                name: 'insert',
                items: ['Table']
            },
            // { name: 'tools', items: ['Maximize'] }
        ];
        var elements = document.querySelectorAll('.ckeditor');
        elements.forEach(function(element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        });
    }
    InitializeCkeditor();

    function InitializeDatePicker() {
        $(".datepicker").datetimepicker({
            format: "dd-mm-yyyy HH:ii P",
            showMeridian: true,
            autoclose: true,
            todayBtn: true
        });

        $(".datepicker2").datepicker({
            format: "dd-mm-yyyy",
            showMeridian: true,
            autoclose: true,
            todayBtn: true
        });
    }
    InitializeDatePicker();
    $("#template_list").select2();
    $("#template_list2").select2();
    $("#template_list").change(function() {
        var template_id = $(this).val();
        $.ajax({
            url: '<?= base_url('nurse_handover_template/get_details_by_id')?>/' + template_id,
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);
                console.log(data);
                CKEDITOR.instances['morning_shift_medical_history'].setData(data.morning_shift_medical_history);
                CKEDITOR.instances['morning_shift_allergies'].setData(data.morning_shift_allergies);
                CKEDITOR.instances['morning_shift_barthel_index'].setData(data.morning_shift_barthel_index);
                CKEDITOR.instances['morning_shift_current_treatment'].setData(data.morning_shift_current_treatment);
                $("#morning_shift_vital_temp").val(data.morning_shift_vital_temp);
                $("#morning_shift_vital_hr").val(data.morning_shift_vital_hr);
                $("#morning_shift_vital_rr").val(data.morning_shift_vital_rr);
                $("#morning_shift_vital_bp").val(data.morning_shift_vital_bp);
                $("#morning_shift_vital_pain_scale").val(data.morning_shift_vital_pain_scale);

                CKEDITOR.instances['morning_shift_abnormal_lab'].setData(data.morning_shift_abnormal_lab);
                CKEDITOR.instances['morning_shift_lines_fluids'].setData(data.morning_shift_lines_fluids);
                CKEDITOR.instances['morning_shift_goals'].setData(data.morning_shift_goals);
                CKEDITOR.instances['morning_shift_pending_consultations'].setData(data.morning_shift_pending_consultations);
                CKEDITOR.instances['morning_shift_test_treatment_pending'].setData(data.morning_shift_test_treatment_pending);
                CKEDITOR.instances['morning_shift_discharge_needs'].setData(data.morning_shift_discharge_needs);
                // $("#morning_shift_signature").val(data.morning_shift_signature);
                // $("#morning_shift_date_time").val(data.morning_shift_date_time);
            }
        });
    });
    $("#template_list2").change(function() {
        var template_id = $(this).val();
        $.ajax({
            url: '<?= base_url('nurse_handover_template/get_details_by_id')?>/' + template_id,
            type: 'GET',
            success: function(response) {
                var data = JSON.parse(response);
                CKEDITOR.instances['night_shift_medical_history'].setData(data.morning_shift_medical_history);
                CKEDITOR.instances['night_shift_allergies'].setData(data.morning_shift_allergies);
                CKEDITOR.instances['night_shift_barthel_index'].setData(data.morning_shift_barthel_index);
                CKEDITOR.instances['night_shift_current_treatment'].setData(data.morning_shift_current_treatment);
                $("#night_shift_vital_temp").val(data.morning_shift_vital_temp);
                $("#night_shift_vital_hr").val(data.morning_shift_vital_hr);
                $("#night_shift_vital_rr").val(data.morning_shift_vital_rr);
                $("#night_shift_vital_bp").val(data.morning_shift_vital_bp);
                $("#night_shift_vital_pain_scale").val(data.morning_shift_vital_pain_scale);

                CKEDITOR.instances['night_shift_abnormal_lab'].setData(data.morning_shift_abnormal_lab);
                CKEDITOR.instances['night_shift_lines_fluids'].setData(data.morning_shift_lines_fluids);
                CKEDITOR.instances['night_shift_goals'].setData(data.morning_shift_goals);
                CKEDITOR.instances['night_shift_pending_consultations'].setData(data.morning_shift_pending_consultations);
                CKEDITOR.instances['night_shift_test_treatment_pending'].setData(data.morning_shift_test_treatment_pending);
                CKEDITOR.instances['night_shift_discharge_needs'].setData(data.morning_shift_discharge_needs);
                // $("#night_shift_signature").val(data.night_shift_signature);
                // $("#night_shift_date_time").val(data.night_shift_date_time);
                
            }
        });
    })
</script>