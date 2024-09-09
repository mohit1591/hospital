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
              

                <div class="row">
               
                    <div class="form-group col-md-3">
                        <label for="">Template Name</label>
                        <input type="text" name="name" value="<?=$all_details['name']?>" class="form-control">
                    </div>
                    <!-- <div class="form-group col-md-3">
                    <label for="shift">Shift :</label><br>
                    <select name="shift" id="shift" >
                      <option value="Morning" <?php echo $all_details['shift']=='Morning'? "selected":"" ?>>Morning</option>
                      <option value="Night" <?php echo $all_details['shift']=='Night'? "selected":"" ?>>Night</option>
                    </select>
                    </div> -->
                </div>
                   
              
                <div class="row m-t-10">
                <div class="row">
                
        <div class="col-sm-6">
        
          <div class="form-group">
            <label for="morning_shift_medical_history">Medical History:</label>
            <textarea class="form-control ckeditor" id="morning_shift_medical_history" name="morning_shift_medical_history" rows="2"><?=$all_details['morning_shift_medical_history']?></textarea>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_allergies">Allergies:</label>
            <textarea class="form-control ckeditor" id="morning_shift_allergies" name="morning_shift_allergies" rows="2"><?=$all_details['morning_shift_allergies']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_barthel_index">Barthel Index:</label>
            <textarea class="form-control ckeditor" id="morning_shift_barthel_index" name="morning_shift_barthel_index" rows="2"><?=$all_details['morning_shift_barthel_index']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_current_treatment">Current Treatment:</label>
            <textarea class="form-control ckeditor" id="morning_shift_current_treatment" name="morning_shift_current_treatment" rows="2"><?=$all_details['morning_shift_current_treatment']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_vital_signs">Vital Signs:</label>
            <input type="text" class="form-control mb-2" id="morning_shift_vital_temp" name="morning_shift_vital_temp" placeholder="Temp." value="<?=$all_details['morning_shift_vital_temp']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_hr" name="morning_shift_vital_hr" placeholder="HR" value="<?=$all_details['morning_shift_vital_hr']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_rr" name="morning_shift_vital_rr" placeholder="RR" value="<?=$all_details['morning_shift_vital_rr']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_bp" name="morning_shift_vital_bp" placeholder="BP" value="<?=$all_details['morning_shift_vital_bp']?>">
            <input type="text" class="form-control mb-2" id="morning_shift_vital_pain_scale" name="morning_shift_vital_pain_scale" value="<?=$all_details['morning_shift_vital_pain_scale']?>" placeholder="Pain Scale">
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_abnormal_lab">Abnormal Lab:</label>
            <textarea class="form-control ckeditor" id="morning_shift_abnormal_lab" name="morning_shift_abnormal_lab" rows="2"><?=$all_details['morning_shift_abnormal_lab']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_lines_fluids">Lines/Fluids:</label>
            <textarea class="form-control ckeditor" id="morning_shift_lines_fluids" name="morning_shift_lines_fluids" rows="2"><?=$all_details['morning_shift_lines_fluids']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_goals">Goals:</label>
            <textarea class="form-control ckeditor" id="morning_shift_goals" name="morning_shift_goals" rows="2"><?=$all_details['morning_shift_goals']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_pending_consultations">Pending Consultations:</label>
            <textarea class="form-control ckeditor" id="morning_shift_pending_consultations" name="morning_shift_pending_consultations" rows="2"><?=$all_details['morning_shift_pending_consultations']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_test_treatment_pending">Test/Treatment Pending:</label>
            <textarea class="form-control ckeditor" id="morning_shift_test_treatment_pending" name="morning_shift_test_treatment_pending" rows="2"><?=$all_details['morning_shift_test_treatment_pending']?></textarea>
          </div>
          </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="morning_shift_discharge_needs">Discharge Needs:</label>
            <textarea class="form-control ckeditor" id="morning_shift_discharge_needs" name="morning_shift_discharge_needs" rows="2"><?=$all_details['morning_shift_discharge_needs']?></textarea>
          </div>
          </div>
       
          <!-- <div class="form-group">
            <label for="morning_shift_signature">Name/Signature:</label>
            <input type="text" class="form-control" id="morning_shift_signature" name="morning_shift_signature" value="<?=$all_details['morning_shift_signature']?>">
          </div>
          <div class="form-group">
            <label for="morning_shift_date_time">Date/Time:</label>
            <input type="text" class="form-control datepicker" id="morning_shift_date_time" name="morning_shift_date_time" value="<?=$all_details['morning_shift_date_time']?>" placeholder="__/__/20__ Time: __:__ AM/PM">
          </div> -->
       

       
      </div>
                </div>


                <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">

                <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
                <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">
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
</script>