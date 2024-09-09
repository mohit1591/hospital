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
               

                <div class="row">

                   
                    
                </div> <!-- row -->
                <div class="row m-t-10">
                    <div class="row">


                        <div class="col-md-12">
                    <div class="form-group col-md-12">
                        <label for="">Template Name</label>
                        <input type="text" name="name" class="form-control" value="<?=$all_details['name']?>">
                    </div>
                </div>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="shift">Shift:</label>
                                    <select type="text" class="form-control" id="shift" name="shift">
                                        <option value="Morning" <?php echo $all_details['shift']=="Morning"?"selected":"" ?>>Morning</option>
                                        <option value="Evening" <?php echo $all_details['shift']=="Evening"?"selected":"" ?>>Evening</option>
                                        <option value="Night" <?php echo $all_details['shift']=="Night"?"selected":"" ?>>Night</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Handover date</label>
                                    <input type="text" class="datepicker2 form-control" value="<?= $all_details['handover_date'] ?>" name="handover_date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="current_complaints">Current Complaints:</label>
                                    <textarea class="form-control ckeditor" id="current_complaints" name="current_complaints" rows="3"><?= $all_details['current_complaints'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="general_condition">General Condition:</label>
                                    <textarea class="form-control ckeditor" id="general_condition" name="general_condition" rows="3"><?= $all_details['general_condition'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="any_changes_in_medication">Any Changes in Medication:</label>
                                    <textarea class="form-control ckeditor" id="any_changes_in_medication" name="any_changes_in_medication" rows="3"><?= $all_details['any_changes_in_medication'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pending_investigations">Pending Investigations:</label>
                                    <textarea class="form-control ckeditor" id="pending_investigations" name="pending_investigations" rows="3"><?= $all_details['pending_investigations'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="care_plan">Care Plan:</label>
                                    <textarea class="form-control ckeditor" id="care_plan" name="care_plan" rows="3"><?= $all_details['care_plan'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="discharge_transfer_shifting">Discharge/Transfer/Shifting (As per SBAR):</label>
                                    <textarea class="form-control ckeditor" id="discharge_transfer_shifting" name="discharge_transfer_shifting" rows="3"><?= $all_details['discharge_transfer_shifting'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="current_medication">Current Medication:</label>
                                    <textarea class="form-control ckeditor" id="current_medication" name="current_medication" rows="3"><?= $all_details['current_medication'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="medication_during_stay">Medication During Stay:</label>
                                    <textarea class="form-control ckeditor" id="medication_during_stay" name="medication_during_stay" rows="3"><?= $all_details['medication_during_stay'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="medication_on_discharge">Medication on Discharge:</label>
                                    <textarea class="form-control ckeditor" id="medication_on_discharge" name="medication_on_discharge" rows="3"><?= $all_details['medication_on_discharge'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="given_by">Given By:</label>
                                    <input type="text" class="form-control" id="given_by" name="given_by" value="<?= $all_details['given_by'] ?>">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="taken_by">Taken By:</label>
                                    <input type="text" class="form-control" id="taken_by" name="taken_by"  value="<?= $all_details['taken_by'] ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="pain_grade">Pain Grade/Action Taken:</label>
                                    <textarea class="form-control ckeditor" id="pain_grade" name="pain_grade" rows="3"><?= $all_details['pain_grade'] ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="remark">Remarks/Sign:</label>
                                    <textarea class="form-control" id="remark" name="remark" rows="3"><?= $all_details['remark'] ?></textarea>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pain_assesment_scale">Pain Assessment Scale:</label>
                                    <select class="form-control" id="pain_assesment_scale" name="pain_assesment_scale">
                                        <option value="0 - No Pain" <?php echo $all_details['pain_assesment_scale']=="0 - No Pain"?"selected":"" ?>>0 - No Pain</option>
                                        <option value="1 - Noticeable" <?php echo $all_details['pain_assesment_scale']=="1 - Noticeable"?"selected":"" ?>>1 - Noticeable</option>
                                        <option value="2 - Mild Pain" <?php echo $all_details['pain_assesment_scale']=="2 - Mild Pain"?"selected":"" ?>>2 - Mild Pain</option>
                                        <option value="3 - Uncomfortable" <?php echo $all_details['pain_assesment_scale']=="3 - Uncomfortable"?"selected":"" ?>>3 - Uncomfortable</option>
                                        <option value="4 - Annoying" <?php echo $all_details['pain_assesment_scale']=="4 - Annoying"?"selected":"" ?>>4 - Annoying</option>
                                        <option value="5 - Moderate Pain" <?php echo $all_details['pain_assesment_scale']=="5 - Moderate Pain"?"selected":"" ?>>5 - Moderate Pain</option>
                                        <option value="6 - Describable" <?php echo $all_details['pain_assesment_scale']=="6 - Describable"?"selected":"" ?>>6 - Describable</option>
                                        <option value="7 - Distressing" <?php echo $all_details['pain_assesment_scale']=="7 - Distressing"?"selected":"" ?>>7 - Distressing</option>
                                        <option value="8 - Horrible" <?php echo $all_details['pain_assesment_scale']=="8 - Horrible"?"selected":"" ?>>8 - Horrible</option>
                                        <option value="9 - Horrible Pain" <?php echo $all_details['pain_assesment_scale']=="9 - Horrible Pain"?"selected":"" ?>>9 - Horrible Pain</option>
                                        <option value="10 - Worst Pain" <?php echo $all_details['pain_assesment_scale']=="10 - Worst Pain"?"selected":"" ?>>10 - Worst Pain</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">

                <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
                <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">
                <div class="col-xs-1">
                    <div class="prescription_btns">
                        <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>


                        <a href="<?php echo base_url('doctor_handover'); ?>" class="btn-anchor">
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
        $("#template_list").change(function() {
            var template_id = $(this).val();
            $.ajax({
                url: '<?= base_url('doctor_handover_template/get_details_by_id') ?>/' + template_id,
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
                    $("#morning_shift_signature").val(data.morning_shift_signature);
                    $("#morning_shift_date_time").val(data.morning_shift_date_time);

                    CKEDITOR.instances['night_shift_medical_history'].setData(data.night_shift_medical_history);
                    CKEDITOR.instances['night_shift_allergies'].setData(data.night_shift_allergies);
                    CKEDITOR.instances['night_shift_barthel_index'].setData(data.night_shift_barthel_index);
                    CKEDITOR.instances['night_shift_current_treatment'].setData(data.night_shift_current_treatment);
                    $("#night_shift_vital_temp").val(data.night_shift_vital_temp);
                    $("#night_shift_vital_hr").val(data.night_shift_vital_hr);
                    $("#night_shift_vital_rr").val(data.night_shift_vital_rr);
                    $("#night_shift_vital_bp").val(data.night_shift_vital_bp);
                    $("#night_shift_vital_pain_scale").val(data.night_shift_vital_pain_scale);

                    CKEDITOR.instances['night_shift_abnormal_lab'].setData(data.night_shift_abnormal_lab);
                    CKEDITOR.instances['night_shift_lines_fluids'].setData(data.night_shift_lines_fluids);
                    CKEDITOR.instances['night_shift_goals'].setData(data.night_shift_goals);
                    CKEDITOR.instances['night_shift_pending_consultations'].setData(data.night_shift_pending_consultations);
                    CKEDITOR.instances['night_shift_test_treatment_pending'].setData(data.night_shift_test_treatment_pending);
                    CKEDITOR.instances['night_shift_discharge_needs'].setData(data.night_shift_discharge_needs);
                    $("#night_shift_signature").val(data.night_shift_signature);
                    $("#night_shift_date_time").val(data.night_shift_date_time);

                }
            });
        })
    </script>