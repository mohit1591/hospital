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


                <div class="row">
                    
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
                                <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-2"><strong>Diagnosis</strong></div>
                            <div class="col-xs-10">
                                <select style="width:100%" id="diagnosis_list" name="diagnosis_id" multiple class="diagnosis_list" value="<?php echo $form_data['diagnosis_id']; ?>"> </select>
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
                                        <td><input type="text" name="date[]" class="" placeholder="" value="<?=date('Y-m-d', strtotime($mcl['date']))?>"></td>
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
           load_values(result);
           load_test_values(template_id);
           load_prescription_values(template_id);
        } 
      }); 
  });


  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       
        $('#prescription_medicine').val(obj.prescription_medicine);
        CKEDITOR.instances['prv_history'].setData(obj.prv_history);
        CKEDITOR.instances['personal_history'].setData(obj.personal_history);
        CKEDITOR.instances['chief_complaints'].setData(obj.chief_complaints);
        CKEDITOR.instances['examination'].setData(obj.examination);
        CKEDITOR.instances['diagnosis'].setData(obj.diagnosis);
        CKEDITOR.instances['suggestion'].setData(obj.suggestion);
        CKEDITOR.instances['remark'].setData(obj.remark);
    
       

       
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
      arr += '<tbody><tr><td>Test Name</td><td width="80"></td></tr>';
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) {
     
         arr += '<tr><td><input type="text"   name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'" placeholder="Click to add Test"><input type="hidden" id="test_id'+i+'" name="test_id[]" class="w-100" value="'+obj[index].test_id+'"></td></tr>';

         $(function () {



    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ipd_prescription/get_test_vals/'); ?>" + request.term,
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
            //$("#test_val").val("").css("display", 2);
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
                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>                            <td <?php  if($l=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>                                <?php 
                           $l++; 
                            }
                            ?><td width="80"></td></tr>';
      i = 1;
      $.each(obj, function (index, value) {       
         pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="" value="'+obj[index].medicine_name+'" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]"  value="'+obj[index].medicine_id+'" ><p style="color:green" id="medicine_total<?php echo $i; ?>"></p></td>                        <?php 
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
                      } ?></tr>';

    }); 
      pres += '</tbody>'; 
      $("#prescription_name_table tbody").replaceWith(pres);
    

    }    
    
       


     $('#chief_complaints_data').change(function(){  
      var complaints_id = $(this).val();
      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/complaints_name/"+complaints_id, 
        success: function(result)
        {
           //$('#chief_complaints').html(result);
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
      $.ajax({url: "<?php echo base_url(); ?>opd/examination_name/"+examination_id, 
        success: function(result)
        {
           //$('#examination').html(result);
           if(examination_val!='')
           {
            var examination_value = examination_val+' '+result; 
           }
           else
           {
            var examination_value = result;
           }
           $('#examination').val(examination_value); 
           CKEDITOR.instances['examination'].setData(examination_value);
        } 
      }); 
  }); 

    $('#diagnosis_data').change(function(){  
      var diagnosis_id = $(this).val();
      var diagnosis_val = $("#diagnosis").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
        success: function(result)
        {
           //$('#diagnosis').html(result);

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
      var suggestion_val = $("#suggestion").val();
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
      var personal_history_val = $("#personal_history").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/personal_history_name/"+personal_history_id, 
        success: function(result)
        {
           //$('#personal_history').html(result);

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
      var prv_history_val = $("#prv_history").val();
      $.ajax({url: "<?php echo base_url(); ?>opd/prv_history_name/"+prv_history_id, 
        success: function(result)
        {
           //$('#prv_history').html(result); 

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

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true, 
  });*/

      $(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });

    $('.datepickerewe').datetimepicker({
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
        url : "<?php echo base_url('ipd_prescription/get_test_vals/'); ?>" + request.term,
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

    var i = $('#prescription_name_table tr').length;
    var newRow = '<tr>';
    newRow += '<td><input type="text" class="" name="date[]" class="date' + i + '" placeholder="Date"></td>';
    <?php foreach ($prescription_medicine_tab_setting as $tab_value): ?>
       
      <?php $setting_name = strtolower($tab_value->setting_name); ?>
      <?php if ($setting_name == 'medicine'): ?>
        newRow += '<td><input type="text" name="medicine_name[]" class="medicine_val' + i + '" placeholder="Click to add Medicine"><input type="hidden" name="medicine_id[]" id="medicine_id' + i + '"><p style="color:green" id="medicine_total' + i + '"></p></td>';
      <?php elseif ($setting_name == 'brand'): ?>
        newRow += '<td><input type="text" id="brand' + i + '" name="brand[]" class=""></td>';
      <?php elseif ($setting_name == 'dose'): ?>
        newRow += '<td><input type="text" name="medicine_dose[]" class="input-small dosage_val' + i + '"></td>';
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

$(function () {
    var getData = function (request, response) { 
        /*$.getJSON(
            "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            })*/;
    

            row = 1;
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


 
    /*var selectItem = function (event, ui) { 
        $(".test_val").val(ui.item.value);
        return false;
    }*/

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
          $('#brand').val(names[2]);
          $('#salt').val(names[3]);
          $('#medicine_id').val(names[4]);
          var total_qty = 'Available quantity in stock '+names[5];
          
          $('#medicine_total').text(total_qty);
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
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
</script>
<script>
$(".select-box-search-event").on('keyup', function() {
   var level = $(this).val();
   var appendClass = $(this).attr("data-appendId");
   $.ajax({
      url:"<?php echo base_url(); ?>ipd_prescription/search_box_data",
      method:"POST",
      data:{type:level,class:appendClass},
      dataType:"json",
      success:function(data)
      {
        var html = '';
        for(var count = 0; count < data.length; count++)
        {
          html += '<option value="'+data[count].id+'">'+data[count].name+'</option>';
        }
          $(`#${appendClass}`).html(html);
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
      <script>
        function deleteMyRow(my_row)
        {
            $(".my_row_"+my_row).remove();
        }
      </script>

      <script>
        function initializeDatepicker() {
            $(".datepicker").datepicker({
                format: "dd-mm-yyyy",
                showMeridian: true,
                autoclose: true,
                todayBtn: true
            });
        }
    
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