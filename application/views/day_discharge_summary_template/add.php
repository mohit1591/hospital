<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="day_discharge_summary_template" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body" style="padding-left:3em;">   
          
          <div class="row m-b-5">
             <div class="col-xs-3"><label>Name <span class="star">*</span></label></div>
             <div class="col-xs-9">
                <input type="text" name="name" id="name" class="inputFocus" value="<?php echo $form_data['name']; ?>">
                <?php if(!empty($form_error)){ echo form_error('name'); } ?>
             </div>
          </div> <!-- row -->  
          
          <div class="row m-b-5">
             <div class="col-xs-3"></div>
             <div class="col-xs-9 font-default">
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==0){ echo 'checked="checked"'; } ?> value="0"> LAMA</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==1){ echo 'checked="checked"'; } ?> value="1"> REFERRAL</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==2){ echo 'checked="checked"'; } ?> value="2"> DISCHARGE</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==3){ echo 'checked="checked"'; } ?> value="3"> D.O.P.R</label> &nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==4){ echo 'checked="checked"'; } ?> value="4"> NORMAL</label>&nbsp;
                <label class="font-default"><input type="radio" name="summery_type" id="summery_type" <?php if($form_data['summery_type']==5){ echo 'checked="checked"'; } ?> value="5"> Expired</label>
             </div>
          </div> <!-- row -->  
          <?php 
          if(!empty($discharge_labels_setting_list))
          {
            
            $i=1;
            foreach ($discharge_labels_setting_list as $value) 
            {
              //echo "<pre>"; print_r($value); 
              if(strcmp(strtolower($value['setting_name']),'cheif_complaints')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                        <textarea class="textarea-100" id="chief_complaints" name="chief_complaints"><?php echo $form_data['chief_complaints']; ?></textarea>
                        <?php if(!empty($form_error)){ echo form_error('chief_complaints'); } ?>
                     </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'ho_presenting_illness')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea id="h_o_presenting" name="h_o_presenting" class="textarea-100"><?php echo $form_data['h_o_presenting']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('h_o_presenting'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'on_examination')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="on_examination" id="on_examination" class="textarea-100"><?php echo $form_data['on_examination']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('on_examination'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'vitals')=='0')
              {
                ?>
                  


        <div class="row m-b-5">
             <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
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
                        
                        
                   <div class="row m-b-2 ">
                   
                        <?php 
                        if(strcmp(strtolower($vital_value['setting_name']),'pulse')=='0')
                        {
                        ?>
                          <div class="col-sm-6 ">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input name="vitals_pulse" id="vitals_pulse" class=" w-90px" type="text"  value="<?php echo $form_data['vitals_pulse']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('vitals_pulse'); } ?>
                        </div>
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value['setting_name']),'chest')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input id="vitals_chest" name="vitals_chest" class=" w-90px" type="text" value="<?php echo $form_data['vitals_chest']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('vitals_chest'); } ?>
                        </div> 
                        <?php 
                        }

                        ?>
                       
                   </div><!-- innerrow -->  
                   


                    
                   <div class="row m-b-2">
                   
                      <?php 
                      if(strcmp(strtolower($vital_value['setting_name']),'bp')=='0')
                      {
                      ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input id="vitals_bp" type="text" name="vitals_bp" class=" w-90px" value="<?php echo $form_data['vitals_bp']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_bp'); } ?>
                    </div> 
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value['setting_name']),'cvs')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input type="text" id="vitals_cvs" class=" w-90px" name="vitals_cvs" value="<?php echo $form_data['vitals_cvs']; ?>">
                         <?php if(!empty($form_error)){ echo form_error('vitals_cvs'); } ?>
                      </div> 
                        <?php 
                        }

                        ?>
                   
                   </div><!-- innerrow -->  
                
                   
                   <div class="row m-b-2">
                   
                        <?php 
                        if(strcmp(strtolower($vital_value['setting_name']),'temp')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input type="text" id="vitals_temp" class=" w-90px" name="vitals_temp" value="<?php echo $form_data['vitals_temp']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_temp'); } ?>
                    </div> 
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value['setting_name']),'cns')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                          <input type="text" id="vitals_cns" class="w-90px" name="vitals_cns" value="<?php echo $form_data['vitals_cns']; ?>">
                         <?php if(!empty($form_error)){ echo form_error('vitals_cns'); } ?>
                      </div> 
                        <?php 
                        }

                        ?>
                  
                   </div><!-- innerrow -->  
                  

                   
                   <div class="row m-b-2">
                  
                        <?php 
                        if(strcmp(strtolower($vital_value['setting_name']),'p_a')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input type="text" id="vitals_p_a" class="w-90px" name="vitals_p_a" value="<?php echo $form_data['vitals_p_a']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_p_a'); } ?>
                    </div> 
                        <?php 
                        }
                        ?>
                  
                   </div><!-- innerrow -->  
                   

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

              if(strcmp(strtolower($value['setting_name']),'provisional_diagnosis')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="provisional_diagnosis" id="provisional_diagnosis" class="textarea-100"><?php echo $form_data['provisional_diagnosis']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('provisional_diagnosis'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'final_diagnosis')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                     <textarea name="final_diagnosis" id="final_diagnosis" class="textarea-100"><?php echo $form_data['final_diagnosis']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('final_diagnosis'); } ?>
                 </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'course_in_hospital')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="course_in_hospital" id="course_in_hospital" class="textarea-100"><?php echo $form_data['course_in_hospital']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('course_in_hospital'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'investigation')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="investigations" id="investigations" class="textarea-100"><?php echo $form_data['investigations']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('investigations'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'condition_at_discharge_time')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                       <textarea name="discharge_time_condition" id="discharge_time_condition" class="textarea-100"><?php echo $form_data['discharge_time_condition']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('discharge_time_condition'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'advise_on_discharge')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <textarea name="discharge_advice" id="discharge_advice" class="textarea-100"><?php echo $form_data['discharge_advice']; ?></textarea>
                      <?php if(!empty($form_error)){ echo form_error('discharge_advice'); } ?>
                   </div>
                  </div> <!-- row -->

                <?php 

              }

              if(strcmp(strtolower($value['setting_name']),'review_time_and_date')=='0')
              {
                ?>
                  <div class="row m-b-5">
                     <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                     <div class="col-xs-9">
                      <input type="text" name="review_time_date" class="w-130px datepicker" placeholder="Date" value="<?php echo  $form_data['review_time_date']; ?>" >
                      <input type="text" name="review_time" class="w-65px datepicker3" placeholder="Time" value="<?php  echo $form_data['review_time']; ?>">

                      <?php if(!empty($form_error)){ echo form_error('review_time_date'); } ?>
                   </div>
                  </div> <!-- row -->

                  <?php 
                  }

                  if(strcmp(strtolower($value['setting_name']),'pulse')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         
                         <textarea name="vitals_pulse" id="vitals_pulse" class="textarea-100"><?php echo $form_data['vitals_pulse']; ?></textarea>
                          <?php if(!empty($form_error)){ echo form_error('vitals_pulse'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'chest')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                        
                        <textarea name="vitals_chest" id="vitals_chest" class="textarea-100"><?php echo $form_data['vitals_chest']; ?></textarea>
                           <?php if(!empty($form_error)){ echo form_error('vitals_chest'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'bp')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         
                        <textarea name="vitals_bp" id="vitals_bp" class="textarea-100"><?php echo $form_data['vitals_bp']; ?></textarea>

                         
                       <?php if(!empty($form_error)){ echo form_error('vitals_bp'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'cvs')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         <textarea name="vitals_cvs" id="vitals_cvs" class="textarea-100"><?php echo $form_data['vitals_cvs']; ?></textarea>
                         
                         <?php if(!empty($form_error)){ echo form_error('vitals_cvs'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'temp')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         <textarea name="vitals_temp" id="vitals_temp" class="textarea-100"><?php echo $form_data['vitals_temp']; ?></textarea>
                         
                       <?php if(!empty($form_error)){ echo form_error('vitals_temp'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'cns')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         <textarea name="vitals_cns" id="vitals_cns" class="textarea-100"><?php echo $form_data['vitals_cns']; ?></textarea>
                          
                         <?php if(!empty($form_error)){ echo form_error('vitals_cns'); } ?>
                       </div>
                      </div> <!-- row -->

                    <?php 

                  }

                  if(strcmp(strtolower($value['setting_name']),'p_a')=='0')
                  {
                    ?>
                      <div class="row m-b-5">
                         <div class="col-xs-3"><label><?php if(!empty($value['setting_value'])) { echo $value['setting_value']; } else { echo $value['var_title']; } ?></label></div>
                         <div class="col-xs-9">
                         <textarea name="vitals_p_a" id="vitals_p_a" class="textarea-100"><?php echo $form_data['vitals_p_a']; ?></textarea>
                          
                       <?php if(!empty($form_error)){ echo form_error('vitals_p_a'); } ?>
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

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#day_discharge_summary_template").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Day care Discharge Summary template successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Day Care Discharge Summary template successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('day_discharge_summary_template/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_daycare_discharge_summary_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_daycare_discharge_summary();
        reload_table();
      } 
      else
      {
        $("#load_add_daycare_discharge_summary_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_daycare_discharge_summary_modal_popup').modal('hide');
});

function get_daycare_discharge_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>day_discharge_summary_template/daycare_discharge_summary_dropdown/", 
    success: function(result)
    {
      $('#daycare_discharge_summary_id').html(result); 
    } 
  });
}

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
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->