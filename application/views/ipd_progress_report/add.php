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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
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

 
<!-- <script src="< ?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>  due to conflicts in date picker-->
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
                    <div class="col-xs-2">
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>IPD No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="ipd_no" value="<?php echo $form_data['ipd_no']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                                <input type="text"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Patient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>">
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Room Type</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="room_category" value="<?php echo $form_data['room_category']; ?>">
                                <input type="hidden" name="room_type_id" value="<?php echo $form_data['room_type_id']; ?>">
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Bed No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="bed_no" value="<?php echo $form_data['bed_no']; ?>">
                                <input type="hidden" name="bad_id" value="<?php echo $form_data['bad_id']; ?>">
                              
                            </div>
                        </div>
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                               <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
                              <input type="text" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
                              <input type="text" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Room No.</strong></div>
                            <div class="col-xs-8">
                              <input type="text" name="room_no" value="<?php echo $form_data['room_no']; ?>">
                                <input type="hidden" name="room_id" value="<?php echo $form_data['room_id']; ?>">
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4">
                        <label><b>Date</b></label></div>
                            <div class="col-xs-8">
                            <input type="text" name="report_date" id="report_date" class=" validity_date " value="<?php echo $form_data['report_date']; ?>">
                            <input type="text" name="report_time" id="report_time" class="datepicker3 input-tiny" value="<?php echo $form_data['report_time']; ?>">
                     </div>
                        </div>


                    </div> <!-- 5 -->



                </div> <!-- row -->

                <!-- <div class="row m-t-10">
                    <div class="col-xs-12">
                        <label>
                            <b>Template</b> 
                   <select name="template_list"  id="template_list" >
                        <option value="">Select Template</option>
                     < ?php
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
                </div> -->

                <?php 

                //$enable_setting = get_setting_value('ENABLE_IPD_VITALS'); 
                //if($enable_setting==1)
                //{
                ?>

                

                <div class="row m-t-10">
                    <div class="col-xs-12">
                        <label>
                            <b>BP</b> 
                            <input type="text" name="patient_bp" id="patient_bp" class="input-tiny" value="<?php echo $form_data['patient_bp']; ?>"> 
                            <span>mm/Hg</span>
                        </label> &nbsp;
                        <label>
                            <b>PR</b> 
                            <input type="text" name="patient_height" id="patient_height" class=" input-tiny" value="<?php echo $form_data['patient_height'];?>"> 
                            <span>/Min</span>
                        </label> &nbsp;
                        <label>
                            <b>Temp</b> 
                            <input type="text" name="patient_temp" id="patient_temp" class=" input-tiny" value="<?php echo $form_data['patient_temp']; ?>"> 
                            <span>&#x2109;</span>
                        </label> &nbsp;
                        

                        <label>
                            <b>Weight</b> 
                            <input type="text" name="patient_weight" id="patient_weight" class=" input-tiny" value="<?php echo $form_data['patient_weight']; ?>"> 
                            <span>kg</span>
                        </label> &nbsp;
                        
                        <label>
                            <b>Spo2</b> 
                            <input type="text" name="patient_spo2" id="patient_spo2" class=" input-tiny" value="<?php echo $form_data['patient_spo2']; ?>"> 
                            <span>%</span>
                        </label> &nbsp;
                         <label>
                            <b>RBS/FBS</b> 
                            <input type="text" name="patient_rbs" id="patient_rbs" class="  input-tiny" value="<?php echo $form_data['patient_rbs']; ?>"> 
                            <span>mg/dl</span>
                        </label> &nbsp;
                    </div>
                </div> <!-- row -->
                 <?php //} else{ 
                 /* ?>  
          
          <input type="hidden" name="patient_bp" value="" class="">
          <input type="hidden" name="patient_temp" value="" class="">
          <input type="hidden" name="patient_weight" value="" class="">
          <input type="hidden" name="patient_height" value="" class="">
          <input type="hidden" name="patient_spo2" value="" class="">
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
                            foreach ($prescription_tab_setting as $value) 
                            { 
                              
                            ?>
                          <div class="tab-content">

                            <?php 
                            if(strcmp(strtolower($value->setting_name),'prescription')=='0'){ ?>

                            <div id="tab_prescription" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                             <textarea name="prescription" id="prescription" class="media_100"><?php echo $form_data['prescription']; ?></textarea> 
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                           
                                            <select size="9" class="dropdown-box media_dropdown_box" name="prescription_data"  id="prescription_data" multiple="multiple" >
                                             <?php
                                                if(isset($prescription_list) && !empty($prescription_list))
                                                {
                                                  foreach($prescription_list as $prescription)
                                                  {
                                                     echo '<option class="grp" value="'.$prescription->id.'">'.$prescription->prescription.'</option>';
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

                             if(strcmp(strtolower($value->setting_name),'dressing')=='0'){ ?>
                            <div id="tab_dressing" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-8">
                                        <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                                            <textarea name="dressing" id="dressing" class="media_100"><?php echo $form_data['dressing']; ?></textarea>  
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="well tab-right-scroll">
                                          
                                            <select size="9" class="dropdown-box" name="dressing_data"  id="dressing_data" multiple="multiple" >
                                             <?php
                                                if(isset($dressing_list) && !empty($dressing_list))
                                                {
                                                  foreach($dressing_list as $dressing)
                                                  {
                                                     echo '<option class="grp" value="'.$dressing->id.'">'.$dressing->dressing.'</option>';
                                                  }
                                                }
                                             ?>
                                          </select>

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
                   
                    <select size="9" class="dropdown-box" name="suggestion_data"  id="suggestion_data" multiple="multiple" >
                     <?php
                        if(isset($suggetion_list) && !empty($suggetion_list))
                        {
                          foreach($suggetion_list as $suggestionlist)
                          {
                             echo '<option class="grp" value="'.$suggestionlist->id.'">'.$suggestionlist->suggestion.'</option>';
                          }
                        }
                     ?>
                  </select>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>


     <?php  if(strcmp(strtolower($value->setting_name),'remarks')=='0'){  ?>
    <div id="tab_remarks" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row m-t-10">
            <div class="col-xs-8">
                <div class="well"><h4><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                       <textarea name="remarks" id="remarks" class="media_100"><?php echo $form_data['remarks']; ?></textarea>  
                </div>
            </div>
            <div class="col-xs-4">
                <div class="well tab-right-scroll">
                   
                    <select size="9" class="dropdown-box" name="remarks_data"  id="remarks_data" multiple="multiple" >
                     <?php
                        if(isset($remarks_list) && !empty($remarks_list))
                        {
                          foreach($remarks_list as $remarkslist)
                          {
                             echo '<option class="grp" value="'.$remarkslist->id.'">'.$remarkslist->remarks.'</option>';
                          }
                        }
                     ?>
                  </select>
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
     
     <input type="hidden" name="attend_doctor_id" value="<?php echo $form_data['attend_doctor_id']; ?>">
     <input type="hidden" name="ipd_id" value="<?php echo $form_data['ipd_id']; ?>">
      <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
      <div class="col-xs-1">
      <div class="prescription_btns">
      <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
      </form>
     <!--  <button class="btn-save" type="button"  onclick="window.location.href='< ?php echo base_url('ipd_progress_report_history/lists/'.$form_data['patient_id']); ?>'" name=""><i class="fa fa-history"></i> History</button>
      <button class="btn-save" type="button" name=""><i class="fa fa-info-circle"></i> View</button> -->
      
      <a class="btn-anchor" onclick="window.location.href='<?php echo base_url('ipd_progress_report'); ?>'">
      <i class="fa fa-sign-out"></i> Exit
      </a>
      </div>


      </div> <!-- row -->
 

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
      $.ajax({url: "<?php echo base_url(); ?>ipd_progress_report/get_template_data/"+template_id, 
        success: function(result)
        {
           load_values(result);
           
        } 
      }); 
  });


  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       $('#prescription').val(obj.prescription);
       $('#dressing').val(obj.dressing);
       $('#remarks').val(obj.remarks);
       $('#suggestion').val(obj.suggestion);
  }
    $('#remarks_data').change(function(){  
      var remarks_id = $(this).val();
      var remarks_val = $("#remarks").val();
      $.ajax({url: "<?php echo base_url(); ?>ipd_progress_report/remarks_name/"+remarks_id, 
        success: function(result)
        {
           if(remarks_val!='')
           {
            var remarks_value = remarks_val+' '+result; 
           }
           else
           {
            var remarks_value = result;
           }
           $('#remarks').val(remarks_value);
        } 
      }); 
  });



     $('#suggestion_data').change(function(){  
      var suggetion_id = $(this).val();
      var suggestion_val = $("#suggestion").val();
      $.ajax({url: "<?php echo base_url(); ?>ipd_progress_report/suggetion_name/"+suggetion_id, 
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
           //$('#suggestion').html(result); 
           $('#suggestion').val(suggestion_value);
        } 
      }); 
  }); 

     $('#dressing_data').change(function(){  
      var dressing_id = $(this).val();
      var dressing_val = $("#dressing").val();
      $.ajax({url: "<?php echo base_url(); ?>ipd_progress_report/dressing_name/"+dressing_id, 
        success: function(result)
        {
           
           if(dressing_val!='')
           {
            var dressing_value = dressing_val+' '+result; 
           }
           else
           {
            var dressing_value = result;
           }
           
           $('#dressing').val(dressing_value);
        } 
      }); 
  }); 

     $('#prescription_data').change(function(){  
      var prescription_id = $(this).val();
      var prescription_val = $("#prescription").val();
      $.ajax({url: "<?php echo base_url(); ?>ipd_progress_report/prescription_name/"+prescription_id, 
        success: function(result)
        {
           if(prescription_val!='')
           {
            var prescription_value = prescription_val+' '+result; 
           }
           else
           {
            var prescription_value = result;
           }
           
           $('#prescription').val(prescription_value);
        } 
      }); 
  }); 

 $('.validity_date').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    startDate : new Date(), 
  });
  $('.datepicker3').datetimepicker({
      format: 'LT'
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
        $.getJSON(
            "<?php echo base_url('ipd_progress_report/get_test_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $("#test_val").val(ui.item.value);
        return false;
    }

    $("#test_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
</script>

</body>
</html>