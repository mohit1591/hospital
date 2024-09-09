<?php
$users_data = $this->session->userdata('auth_users');
//echo "<pre>"; print_r($patient_details); exit;
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
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
 <section class="userlist">
 
  <form  id="ipd_patient_discharge_summary" class="form-inline" action="<?php echo current_url(); ?>" method="post" >
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
  <input type="hidden" name="ipd_id" id="ipd_id" value="<?php echo $form_data['ipd_id']; ?>" /> 
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 
 
  
<div class="row" style="padding-left:3em;">
      
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Patient Reg. No.</strong>
      </div>
      <div class="col-xs-9 ">
        <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['patient_code']; ?>" /> 
      </div>
    </div> <!-- row -->
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>IPD No.</strong>
      </div>
      <div class="col-xs-9">
        <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['ipd_no']; ?>" /> 
      </div>
    </div> <!-- row -->

    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Patient Name <span class="star">*</span></strong>
      </div>
      <div class="col-xs-9">
        <select class="mr" name="simulation_id" id="simulation_id"  disabled="true">
          <option value="">Select</option>
          <?php
            
            if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
                if($simulation->id==$patient_details['simulation_id'])
                {
                     $selected_simulation = 'selected="selected"';
                }
                        
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              }
            }
            ?> 
        </select> 
        <?php 
        if(!empty($simulation_list))
            {
              foreach($simulation_list as $simulation)
              {
                $selected_simulation = '';
                if($simulation->id==$patient_details['simulation_id'])
                {
                     $selected_simulation = 'selected="selected"';
                }
                        
                echo '<input type="hidden" name="simulation_id" value="'.$simulation->id.'">';
              }
            }
        ?>
        <input type="text" name="patient_name" readonly id="patient_name" value="<?php echo $patient_details['patient_name']; ?>" class="mr-name txt_firstCap" autofocus/>
        
          <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
          <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
      </div>
    
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Mobile No.</strong>
      </div>
      <div class="col-xs-9">
        <input type="text" name="mobile_no" readonly data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text numeric" value="<?php echo $patient_details['mobile_no']; ?>" maxlength="10" >
        
      </div>
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Gender </strong>
      </div>
      <div class="col-xs-9" id="gender">
        <input type="radio" name="gender"  disabled="true" value="1" <?php if($patient_details['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender"  disabled="true" value="0" <?php if($patient_details['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender"  disabled="true" value="2" <?php if($patient_details['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
      </div>
	
    </div> <!-- row -->
    
    <div class="row m-b-5">
      <div class="col-xs-3">
        <strong>Age</strong>
      </div>
      <div class="col-xs-9">
        <input type="text" name="age_y" readonly  class="input-tiny numeric" maxlength="3" value="<?php echo $patient_details['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_d']; ?>"> D
             
      </div>
    </div> <!-- row -->
   
   </div> <!-- Main 4 -->
  
      
      <div class=" row modal-body" style="padding-left:3em;">   
          <div class="row m-b-5">
             <div class="col-xs-3"><label>Select Discharge Summary </label></div>
             <div class="col-xs-9">

             <select name="template_list"  id="template_list" >
                        <option value="">Select Template</option>
                     <?php
                        if(isset($template_list) && !empty($template_list))
                        {
                          foreach($template_list as $templatelist)
                          {
                             echo '<option class="grp" value="'.$templatelist->id.'">'.$templatelist->name.'</option>';
                          }
                        }
                     ?>
                  </select> 

             </div>
          </div> <!-- row -->  
          
          <div class="row m-b-5">
             <div class="col-xs-3"></div>
             <div class="col-xs-9 font-default">
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==0){ echo 'checked="checked"'; } ?> value="0"> LAMA</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==1){ echo 'checked="checked"'; } ?> value="1"> RERERRAL</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==2){ echo 'checked="checked"'; } ?> value="2"> DISCHARGE</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==3){ echo 'checked="checked"'; } ?> value="3"> D.O.P.R</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==4){ echo 'checked="checked"'; } ?> value="4"> NORMAL</label>
             </div>
          </div> <!-- row -->  
          <?php 
          if(!empty($discharge_labels_setting_list))
          {
            $i=1;
            foreach ($discharge_labels_setting_list as $value) 
            {
              //echo "<pre>"; print_r($value); 
              if(strcmp(strtolower($value->setting_name),'cheif_complaints')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                        <textarea class="textarea-100" id="chief_complaints" name="chief_complaints"><?php echo $form_data['chief_complaints']; ?></textarea>
                        <?php if(!empty($form_error)){ echo form_error('chief_complaints'); } ?>
                     </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'ho_presenting_illness')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea id="h_o_presenting" name="h_o_presenting" class="textarea-100"><?php echo $form_data['h_o_presenting']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('h_o_presenting'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'on_examination')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="on_examination" id="on_examination" class="textarea-100"><?php echo $form_data['on_examination']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('on_examination'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'vitals')=='0')
              {
                ?>
                  


        <div class="row m-b-5">
             <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
             <div class="col-xs-9 p-l-0">
                  
                  <?php 
                  if(!empty($discharge_vital_setting_list))
                  {
                    $j=$i;
                    $r_i = 1;
                    $c_i = 1;
                    foreach ($discharge_vital_setting_list as $vital_value) 
                    { 
                        ?>
                        
                        <?php if($r_i==1){ ?>
                   <div class="row m-b-2 ">
                   <?php } ?>
                        <?php 
                        if(strcmp(strtolower($vital_value->setting_name),'pulse')=='0')
                        {
                        ?>
                          <div class="col-sm-6 ">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input name="vitals_pulse" id="vitals_pulse" class=" w-90px" type="text"  value="<?php echo $form_data['vitals_pulse']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('vitals_pulse'); } ?>
                        </div>
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value->setting_name),'chest')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input id="vitals_chest" name="vitals_chest" class=" w-90px" type="text" value="<?php echo $form_data['vitals_chest']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('vitals_chest'); } ?>
                        </div> 
                        <?php 
                        }

                        ?>
                        <?php if($r_i==3){ ?>
                   </div><!-- innerrow -->  
                   <?php } ?>


                    <?php if($r_i==3){ ?>
                   <div class="row m-b-2">
                   <?php } ?>
                      <?php 
                      if(strcmp(strtolower($vital_value->setting_name),'bp')=='0')
                      {
                      ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input id="vitals_bp" type="text" name="vitals_bp" class=" w-90px" value="<?php echo $form_data['vitals_bp']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_bp'); } ?>
                    </div> 
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value->setting_name),'cvs')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input type="text" id="vitals_cvs" class=" w-90px" name="vitals_cvs" value="<?php echo $form_data['vitals_cvs']; ?>">
                         <?php if(!empty($form_error)){ echo form_error('vitals_cvs'); } ?>
                      </div> 
                        <?php 
                        }

                        ?>
                    <?php if($r_i==5){ ?>
                   </div><!-- innerrow -->  
                   <?php } ?>
                   <?php if($r_i==5){ ?>
                   <div class="row m-b-2">
                   <?php } ?>
                        <?php 
                        if(strcmp(strtolower($vital_value->setting_name),'temp')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input type="text" id="vitals_temp" class=" w-90px" name="vitals_temp" value="<?php echo $form_data['vitals_temp']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_temp'); } ?>
                    </div> 
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value->setting_name),'cns')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                          <input type="text" id="vitals_cns" class="w-90px" name="vitals_cns" value="<?php echo $form_data['vitals_cns']; ?>">
                         <?php if(!empty($form_error)){ echo form_error('vitals_cns'); } ?>
                      </div> 
                        <?php 
                        }

                        ?>
                   <?php if($r_i==7){ ?>
                   </div><!-- innerrow -->  
                   <?php } ?>

                   <?php if($r_i==7){ ?>
                   <div class="row m-b-2">
                   <?php } ?>
                        <?php 
                        if(strcmp(strtolower($vital_value->setting_name),'p_a')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value->setting_value)) { echo $vital_value->setting_value; } else { echo $vital_value->var_title; } ?></label>
                           <input type="text" id="vitals_p_a" class="w-90px" name="vitals_p_a" value="<?php echo $form_data['vitals_p_a']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_p_a'); } ?>
                    </div> 
                        <?php 
                        }
                        ?>
                   <?php if($r_i==7){ ?>
                   </div><!-- innerrow -->  
                   <?php } ?>

                        <?php 
                        
                    $r_i++;
                    $c_i++;
                    
                    }
                  } 

                    ?>

             </div> 
          </div>

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'provisional_diagnosis')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="provisional_diagnosis" id="provisional_diagnosis" class="textarea-100"><?php echo $form_data['provisional_diagnosis']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('provisional_diagnosis'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'final_diagnosis')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                     <textarea name="final_diagnosis" id="final_diagnosis" class="textarea-100"><?php echo $form_data['final_diagnosis']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('final_diagnosis'); } ?>
                 </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'course_in_hospital')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="course_in_hospital" id="course_in_hospital" class="textarea-100"><?php echo $form_data['course_in_hospital']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('course_in_hospital'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'investigation')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="investigations" id="investigations" class="textarea-100"><?php echo $form_data['investigations']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('investigations'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'condition_at_discharge_time')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                       <textarea name="discharge_time_condition" id="discharge_time_condition" class="textarea-100"><?php echo $form_data['discharge_time_condition']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('discharge_time_condition'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'advise_on_discharge')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="discharge_advice" id="discharge_advice" class="textarea-100"><?php echo $form_data['discharge_advice']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('discharge_advice'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value->setting_name),'review_time_and_date')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></label></div>
                     <div class="col-xs-9">
                      <!-- <input name="review_time_date" id="review_time_date" type="text" value="< ?php echo $form_data['review_time_date']; ?>" class="w-100per"> -->

                      <input type="text" name="review_time_date" class="w-130px datepicker" placeholder="Date" value="<?php echo  $form_data['review_time_date']; ?>" >
                      <input type="text" name="review_time" class="w-65px datepicker3" placeholder="Time" value="<?php  echo $form_data['review_time']; ?>">

                      <?php if(!empty($form_error)){ echo form_error('review_time_date'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

             
    $i++;
            }
          } ?>
            
          
           <div class="row m-b-5">
             <div class="col-xs-3"><label>Status</label></div>
             <div class="col-xs-9">
                 <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>> Active &nbsp;
                 <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
             </div>
          </div> <!-- row --> 
          

      </div> <!-- modal-body --> 

      <div class="modal-footer r-btn-cntr"> 
         <input type="submit"  class="btn-update " name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
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

$("button[data-number=1]").click(function(){
    $('#load_add_ipd_patient_discharge_summary_modal_popup').modal('hide');
});

function get_ipd_patient_discharge_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/ipd_patient_discharge_summary_dropdown/", 
    success: function(result)
    {
      $('#ipd_patient_discharge_summary_id').html(result); 
    } 
  });
}

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/get_template_data/"+template_id, 
        success: function(result)
        {
           load_values(result);
        } 
      }); 
  });

  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);

       $('#h_o_presenting').val(obj.h_o_presenting);
       $('#chief_complaints').val(obj.chief_complaints);
       $('#on_examination').val(obj.on_examination);
       $('#vitals_pulse').val(obj.vitals_pulse);
       $('#vitals_chest').val(obj.vitals_chest);
       $('#vitals_bp').val(obj.vitals_bp);
       $('#vitals_cvs').val(obj.vitals_cvs);
       $('#vitals_temp').val(obj.vitals_temp);
       $('#vitals_cns').val(obj.vitals_cns);
       $('#vitals_p_a').val(obj.vitals_p_a);
       $('#provisional_diagnosis').val(obj.provisional_diagnosis);
       $('#final_diagnosis').val(obj.final_diagnosis);
       $('#course_in_hospital').val(obj.course_in_hospital);
       $('#investigations').val(obj.investigations);
       $('#discharge_time_condition').val(obj.discharge_time_condition);
       $('#discharge_advice').val(obj.discharge_advice);
       $('#review_time_date').val(obj.review_time_date);
  };

$(document).ready(function ()
{
  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });
 $('.datepicker3').datetimepicker({
     format: 'LT'
  });

});
</script>  

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>