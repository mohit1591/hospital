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
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                                <input type="text" id="patient_code"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>" readonly="">
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
                                <select style="width:100%" id="diagnosis_list" name="diagnosis_id[]" multiple class="diagnosis_list" value="<?php echo $form_data['diagnosis_id']; ?>"> </select>
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
                               <input type="radio" class="gender" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> readonly=""> Male &nbsp;
                                <input type="radio" name="gender" class="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?> readonly=""> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" id="age_y" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>" readonly=""> Y &nbsp;
                              <input type="text" id="age_m" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>" readonly=""> M &nbsp;
                              <input type="text" id="age_d" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>" readonly=""> D
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                            </div>
                        </div>
                        
                        <!-- new code by mamta -->
  

<!-- new code by mamta -->
                    </div> <!-- 5 -->
                </div> <!-- row -->
<div class="row m-t-10">
<table class="table table-bordered table-striped" id="prescription_name_table">
                                                <tbody>
                                                    <tr>
                                                        <td>Date</td>
                    <?php 
                    $m=0;
                    
                    foreach ($prescription_medicine_tab_setting as $med_value) { 
                        if($med_value->setting_value == "Type" || $med_value->setting_value == "Salt" || $med_value->setting_value == "Advice"){
                            continue;
                        }
                        ?>
                            <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo ($med_value->setting_value=="Duration (Days)")? "Route":$med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                                <?php 
                           $m++; 
                            }
                            ?>
                            <td width="80">
                                <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                            </td></tr>
                      


                        <?php
                        if(!empty($medication_chart_list))
                        {
                            foreach($medication_chart_list as $key => $mcl)
                            {
                                ?>
                                    <tr class="my_row_<?=$mcl['id']?>">
                                     
                                        <td><input class="" width="100%" name="date[]" placeholder="Date" value="<?=$mcl['date']?>"></td>
                                        <td><input type="text" name="medicine_name[]"  class="medicine_val" placeholder="Click to add Medicine" value="<?=$mcl['medicine_name']?>"></td>
                                        <td><input type="text" name="medicine_dose[]"  class="dosage_val" value="<?=$mcl['medicine_dose']?>"></td>
                                        <td><input type="text" name="medicine_duration[]" class="medicine-name duration_val" value="<?=$mcl['medicine_duration']?>"></td>
                                        <td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val" value="<?=$mcl['medicine_frequency']?>"></td>
                                        <td width="80">
                                          <a onclick="deleteMyRow(<?=$mcl['id']?>)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                            }
                        }
                        
                        ?>


              </tbody>
              </table>
</div>
               

<input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">
            
              <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
           <div class="col-xs-1">
            <div class="prescription_btns">
                <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
         
               
                <a href="<?php echo base_url('ipd_prescription'); ?>"  class="btn-anchor" >
                <i class="fa fa-sign-out"></i> Exit
                </a>
        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">


$(document).ready(function(){
    // Initialize Select2 on the .diagnosis_list element
    $('.diagnosis_list').select2({
        ajax: {
            url: '<?=base_url('medication_chart/diagnosis_list')?>', // URL to fetch data
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    term: params.term || '', // Fetch results immediately
                };
            },
            processResults: function (data) {
                // Mapping data to Select2 format
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.diagnosis, // Display text
                            id: item.id, // Value for the option
                        };
                    }),
                };
            },
            cache: true,
        },
        minimumInputLength: 0, // Load data without typing
        placeholder: 'Select a diagnosis', // Placeholder text
    });

    // Set the initial value programmatically if required
    // Example: To set the selected value to an ID of '123'
    const selectedIds = "<?php echo $form_data['diagnosis_id']; ?>"; // Replace this with the actual selected ID

    // Fetch the selected data if needed (e.g., on page reload)
    $.ajax({
        url: '<?=base_url('medication_chart/diagnosis_list')?>', // Fetch data to get the selected item details
        dataType: 'json',
        success: function (data) {
            // Find the selected items from the loaded data
            const selectedItems = data.filter(item => selectedIds.includes(item.id));

            // Add each selected item as an option in the Select2
            selectedItems.forEach(item => {
                const newOption = new Option(item.diagnosis, item.id, true, true);
                $('.diagnosis_list').append(newOption).trigger('change');
            });
        }
    });

$('.diagnosis_list').select2('open').select2('close');

$(".addprescriptionrow").click(function(){ 

    var i = $('#prescription_name_table tr').length;
    var newRow = '<tr>';
    // newRow += '<td><input type="text" width="100%" name="date[]" class="datepicker" placeholder="Date"></td>';
    newRow += '<td><input class="" width="100%" name="date[]" placeholder="Date"></td>';
    <?php foreach ($prescription_medicine_tab_setting as $tab_value): ?>
       
      <?php $setting_name = strtolower($tab_value->setting_name); ?>
      <?php if ($setting_name == 'medicine'): ?>
        newRow += '<td><input type="text" name="medicine_name[]" class="medicine_val' + i + '" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]" id="medicine_id' + i + '"><p style="color:green" id="medicine_total' + i + '"></p></td>';
      <?php elseif ($setting_name == 'brand'): ?>
        newRow += '<td><input type="text" id="brand' + i + '" name="brand[]" class=""></td>';
      <?php elseif ($setting_name == 'dose'): ?>
        newRow += '<td><input type="text" name="medicine_dose[]" class=" dosage_val' + i + '"></td>';
      <?php elseif ($setting_name == 'duration'): ?>
        newRow += '<td><input type="text" name="medicine_duration[]" class="medicine-name duration_val' + i + '"></td>';
      <?php elseif ($setting_name == 'frequency'): ?>
        newRow += '<td><input type="text" name="medicine_frequency[]" class="medicine-name frequency_val' + i + '"></td>';
      <?php endif; ?>
    <?php endforeach; ?>
    newRow += '<td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';
    $("#prescription_name_table").append(newRow);
    initializeDatepicker();

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
          var total_qty = 'Available quantity in stock '+names[5];
          
          $('#medicine_total'+i).text(total_qty);
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
            //$("#test_val").val("").css("display", 2);
        }
    });
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('opd/get_type_vals/'); ?>" + request.term,
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
            //$("#test_val").val("").css("display", 2);
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
            //$("#test_val").val("").css("display", 2);
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
</script>
  <script>
        function deleteMyRow(my_row)
        {
            $(".my_row_"+my_row).remove();
        }
</script>
<script>
  $(function () {
    var getData = function (request, response) { 
       
        $.ajax({
        url : "<?php echo base_url('medication_chart/get_ipd_details/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
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
    function get_ipd_full_details(booking_id)
    {
      $.ajax({
        url : "<?php echo base_url('medication_chart/get_full_ipd_details/');?>/"+booking_id,
        type: 'POST',
        data: {booking_id: booking_id},
        success: function(response){
          var data = JSON.parse(response);
          $("#patient_name").val(data.patient_name);
          $("#age_y").val(data.age_y);
          $("#age_m").val(data.age_m);
          $("#age_d").val(data.age_d);
          $("#ipd_no").val(data.ipd_no);
          $("#patient_code").val(data.patient_code);
          $("#mobile_no").val(data.mobile_no);
          $("#ipd_id").val(data.id);
          if(data.gender == 1){
            $("input.gender[value='1']").prop("checked", true);
          } else if(data.gender == 2){
            $("input.gender[value='0']").prop("checked", true);
          }
        }
      });
          
    }
});
function initializeDatepicker() {
        $(".datepicker").datepicker({
            format: "dd-mm-yyyy",
            showMeridian: true,
            autoclose: true,
            todayBtn: true
        });
    }
    initializeDatepicker();
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
   
var selectedTexts = "<?php echo $all_details['diagnosis_id']; ?>";
selectedTexts = selectedTexts.split('/');
    // Fetch the corresponding IDs and set them as selected
    if (selectedTexts.length > 0) {
        $.ajax({
            url: '<?=base_url('medication_chart/diagnosis_listText')?>',
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