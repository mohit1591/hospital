
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="ipd_discharge_summary" class="form-inline">
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
                        <textarea class="textarea-100 ckeditor" id="chief_complaints" name="chief_complaints" style="width: 100%;"><?php echo $form_data['chief_complaints']; ?></textarea>
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
                      <textarea id="h_o_presenting" name="h_o_presenting" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['h_o_presenting']; ?></textarea>
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
                      <textarea name="on_examination" id="on_examination" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['on_examination']; ?></textarea>
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
                           <input name="vitals_pulse" id="vitals_pulse" class="ckeditor w-90px" type="text"  value="<?php echo $form_data['vitals_pulse']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('vitals_pulse'); } ?>
                        </div>
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value['setting_name']),'chest')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                           <input id="vitals_chest" name="vitals_chest" class="ckeditor w-90px" type="text" value="<?php echo $form_data['vitals_chest']; ?>">
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
                           <input id="vitals_bp" type="text" name="vitals_bp" class="ckeditor w-90px" value="<?php echo $form_data['vitals_bp']; ?>">
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
                           <input type="text" id="vitals_temp" class="ckeditor w-90px" name="vitals_temp" value="<?php echo $form_data['vitals_temp']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('vitals_temp'); } ?>
                    </div> 
                        <?php 
                        }

                        if(strcmp(strtolower($vital_value['setting_name']),'cns')=='0')
                        {
                        ?>
                          <div class="col-sm-6">
                           <label class="w-80px"><?php if(!empty($vital_value['setting_value'])) { echo $vital_value['setting_value']; } else { echo $vital_value['var_title']; } ?></label>
                          <input type="text" id="vitals_cns" class="w-90px ckeditor" name="vitals_cns" value="<?php echo $form_data['vitals_cns']; ?>">
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
                           <input type="text" id="vitals_p_a" class="w-90px ckeditor" name="vitals_p_a" value="<?php echo $form_data['vitals_p_a']; ?>">
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
                      <textarea name="provisional_diagnosis" id="provisional_diagnosis" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['provisional_diagnosis']; ?></textarea>
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
                     <textarea name="final_diagnosis" id="final_diagnosis" class="textarea-100 ckeditor" style="width:100%;"><?php echo $form_data['final_diagnosis']; ?></textarea>
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
                      <textarea name="course_in_hospital" id="course_in_hospital" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['course_in_hospital']; ?></textarea>
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
                      <textarea name="investigations" id="investigations" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['investigations']; ?></textarea>
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
                       <textarea name="discharge_time_condition" id="discharge_time_condition" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['discharge_time_condition']; ?></textarea>
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
                      <textarea name="discharge_advice" id="discharge_advice" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['discharge_advice']; ?></textarea>
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
                         
                         <textarea name="vitals_pulse" id="vitals_pulse" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_pulse']; ?></textarea>
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
                        
                        <textarea name="vitals_chest" id="vitals_chest" class="textarea-100 ckeditor" style="width: 100%;height:130px"><?php echo $form_data['vitals_chest']; ?></textarea>
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
                         
                        <textarea name="vitals_bp" id="vitals_bp" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_bp']; ?></textarea>

                         
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
                         <textarea name="vitals_cvs" id="vitals_cvs" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_cvs']; ?></textarea>
                         
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
                         <textarea name="vitals_temp" id="vitals_temp" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_temp']; ?></textarea>
                         
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
                         <textarea name="vitals_cns" id="vitals_cns" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_cns']; ?></textarea>
                          
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
                         <textarea name="vitals_p_a" id="vitals_p_a" class="textarea-100 ckeditor" style="width: 100%;"><?php echo $form_data['vitals_p_a']; ?></textarea>
                          
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
          
          <!-- ===================================================================================== -->
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
                        <td <?php if ($m = 0) { ?> class="text-left" <?php } ?>><?php if (!empty($med_value->setting_value)) {
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

                    <?php

                    if (!empty($form_data['discharge_summery_medicine'])) {
                      $l = 1;
                      //  print_r($prescription_presc_list);die;
                      foreach ($form_data['discharge_summery_medicine'] as $prescription_presc) {

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
                              <td><input type="text" style="width:80px;" name="medicine_advice[]" class="medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
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
                        $l = 1;
                        foreach ($prescription_medicine_tab_setting as $tab_value) {
                          if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                        ?>
                            <td><input type="text" style="width:100px;" name="medicine_name[]" class="medicine_val<?=$l?>"></td>
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
                            <td><input type="text" style="width:100px;" name="medicine_type[]" class="input-small medicine_type_val<?=$l?>" id="type"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                            <td><input type="text" style="width:100px;" name="medicine_dose[]" class="input-small dosage_val<?=$l?>"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                            <td><input type="text" style="width:100px;" name="medicine_duration[]" class="medicine-name duration_val<?=$l?>"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                          ?>
                            <td><input type="text" style="width:100px;" name="medicine_frequency[]" class="medicine-name frequency_val<?=$l?>"></td>
                          <?php
                          }
                          if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                          ?>
                            <td><input type="text" style="width:100px;" name="medicine_advice[]" class="medicine-name advice_val<?=$l?>"></td>
                        <?php }
                        }
                        ?>
                        <td width="40">
                          <a href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
                        </td>
                      </tr>
                      <script>
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
                      </script>
                    <?php $l++; } ?>
                  </tbody>
                </table>
              </div>
            </div>
            
          <!-- ===================================================================================== -->

          <!-- *************************************************************************************** -->
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

                    <?php

                    if (!empty($form_data['discharge_summary_test'])) {
                      $l = 1;
                      //  print_r($prescription_presc_list);die;
                      foreach ($form_data['discharge_summary_test'] as $discharge_test) {

                    ?>
                        <tr>

                          <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $discharge_test->test_name; ?>">
                            <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $discharge_test->id; ?>">
                          </td>

                          <td><input type="text" name="test_date[]" class="datepicker1<?php echo $l; ?>" value="<?php echo date('d-m-Y', strtotime($discharge_test->test_date)); ?>"></td>

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

                        <input type="hidden" name="test_id[]" class="test_id" value="<?php echo $discharge_test->id; ?>">

                        <td><input type="text" name="test_date[]" class="datepicker1"></td>


                        <td><input type="text" name="test_result[]" class="medicine-name result_val"></td>

                        <td width="80">
                          <a href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
                        </td>
                      </tr>
                      <script>
                      $('.datepicker1').datepicker({
                                format: 'dd-mm-yyyy',
                                autoclose: true
                              });
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
                              </script>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!--- Investigation Section ---->
          <!-- *************************************************************************************** -->
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" id="hideModel" class="btn-cancel" data-number="1">Close</button>
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
 
$("#ipd_discharge_summary").on("submit", function(event) { 
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
  for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
  }
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_discharge_summary/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ipd_discharge_summary_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_ipd_discharge_summary();
        reload_table();
      } 
      else
      {
        $("#load_add_ipd_discharge_summary_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ipd_discharge_summary_modal_popup').modal('hide');
});

function get_ipd_discharge_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>ipd_discharge_summary/ipd_discharge_summary_dropdown/", 
    success: function(result)
    {
      $('#ipd_discharge_summary_id').html(result); 
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

<script>
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
                                                                                                                                                if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>  <td><input  type="text" id="type' + i + '"  name="medicine_type[]" class="ui-autocomplete-input input-small medicine_type_val' + i + '"></td>                        <?php
                                                                                                                                                                            }
                                                                                                                                                                            if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?> <td><input   type="text" name="medicine_dose[]" class="input-small dosage_val' + i + '"></td>                        <?php
                                                                                                                                                                            }
                                                                                                                                                                            if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>  <td><input type="text" style="width:100px;" name="medicine_duration[]" class="medicine-name duration_val' + i + '"></td>                        <?php
                                                                                                                                                                            }
                                                                                                                                                                            if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                                                                                                                    ?> <td><input style="width:100px;" type="text" name="medicine_frequency[]" class="medicine-name frequency_val' + i + '"></td>                        <?php
                                                                                                                                                                            }
                                                                                                                                                                            if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                                                                                                                  ?>  <td><input style="width:100px;" type="text" name="medicine_advice[]" class="medicine-name advice_val' + i + '"></td>                        <?php
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

  var getData = function(request, response) {
    $.getJSON(
      "<?php echo base_url('opd/get_type_vals/'); ?>" + request.term,
      function(data) {
        response(data);
      });
  };

  var selectItem = function(event, ui) {
    $(".medicine_type_val" + i).val(ui.item.value);
    return false;
  }

  $(".medicine_type_val" + i).autocomplete({
    source: getData,
    select: selectItem,
    minLength: 1,
    change: function() {
      //$("#test_val").val("").css("display", 2);
    }
  });

  
      var getData = function(request, response) {
        $.getJSON(
          "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
          function(data) {
            response(data);
          });
      };

      var selectItem = function(event, ui) {
        $(".dosage_val" + i).val(ui.item.value);
        return false;
      }

      $(".dosage_val" + i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
          //$("#test_val").val("").css("display", 2);
        }
      });
  

   
      var getData = function(request, response) {
        $.getJSON(
          "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
          function(data) {
            response(data);
          });
      };

      var selectItem = function(event, ui) {
        $(".duration_val" + i).val(ui.item.value);
        return false;
      }

      $(".duration_val" + i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
          //$("#test_val").val("").css("display", 2);
        }
      });
   
   
      var getData = function(request, response) {
        $.getJSON(
          "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
          function(data) {
            response(data);
          });
      };

      var selectItem = function(event, ui) {
        $(".frequency_val" + i).val(ui.item.value);
        return false;
      }

      $(".frequency_val" + i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
          //$("#test_val").val("").css("display", 2);
        }
      });
   

 
      var getData = function(request, response) {
        $.getJSON(
          "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
          function(data) {
            response(data);
          });
      };

      var selectItem = function(event, ui) {
        $(".advice_val" + i).val(ui.item.value);
        return false;
      }

      $(".advice_val" + i).autocomplete({
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
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->


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

       
      </script>