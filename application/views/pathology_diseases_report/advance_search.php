 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
<?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                         

                         <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
                           <div class="grp">
                           <label>Referred By</label>
                           <div class="rslt">
                                <div id="referred_by_1">
                                       <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
                                        <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
                                        
                                </div>
                            </div>
                        </div> <!-- innerrow -->

                        <div class="grp" id="doctor_div_1" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
                            
                                <label>Referred By</label>

                              <div class="rslt">
                            
                                <select  name="refered_id" id="refered_id_1">
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors_list as $doctors) {?>
                                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                                    <?php }?>
                                </select>
                                   

                           </div>
                        </div> 

                        <div class="grp" id="hospital_div_1" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
                            
                            <label>Referred By</label>
                            <div class="rslt">
                                <select name="referral_hospital" id="referral_hospital_1" class="" >
                                  <option value="">Select Hospital</option>
                                  <?php
                                  if(!empty($referal_hospital_list))
                                  {
                                    foreach($referal_hospital_list as $referal_hospital)
                                    {
                                      ?>
                                        <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                                        
                                      <?php
                                    }
                                  }
                                  ?>

                              
                            </select> 
                            </div>
                            
                           
                        </div> 
                        <?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
                        {  ?>
                          <div class="grp">
                            
                                <label>Referred By</label>
                               <div class="rslt">
                                <select  name="refered_id" id="refered_id_1">
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors_list as $doctors) {?>
                                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                                    <?php }?>
                                </select>
                            </div>
                                   

                           
                        </div> <!-- row -->
                        <input type="hidden" name="referred_by" value="0">
                        <input type="hidden" name="referral_hospital" value="0">
                        <?php 
                        } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
                        {  
                          ?>

                          <div class="grp">
                            
                                <label>Referred By</label>

                                 <div class="rslt">
                            
                                <select name="referral_hospital" id="referral_hospital_1"  >
                              <option value="">Select Hospital</option>
                              <?php
                              if(!empty($referal_hospital_list))
                              {
                                foreach($referal_hospital_list as $referal_hospital)
                                {
                                  ?>
                                    <option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
                                    
                                  <?php
                                }
                              }
                              ?>

                              
                            </select> 
                            </div>
                          
                        </div>
                        <input type="hidden" name="referred_by" value="1">
                        <input type="hidden" name="refered_id" value="0">
                          <?php 

                          } ?>
                        
                        <div class="grp">
                          <label>Department</label>
                          <div class="rslt">
                              <select name="dept_id" id="dept_id">
                                <option value="">Select Department</option>
                                      <?php
                                       if(!empty($dept_list))
                                       {
                                          foreach($dept_list as $dept)
                                          {
                                              $dept_select = "";
                                              if($dept->id==$form_data['dept_id'])
                                              {
                                                  $dept_select = "selected='selected'";
                                              }
                                              echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                          }
                                       }
                                      ?>
                              </select>
                          </div>
                        </div>


                        <div class="grp">
                          <label>User </label>
                          <div class="rslt">
                              
                              <select name="employee" class="m_input_default" id="employee" >
                                  <option value="">Select User</option>
                                  <?php 
                                    if(!empty($employee_user_list))
                                    {
                                      foreach($employee_user_list as $user_employee)
                                      {
                                        echo '<option value="'.$user_employee->id.'">'.$user_employee->name.'</option>';
                                      }
                                    }
                                  ?> 
                              </select>
                          </div>
                        </div>

                        <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code" id="patient_code" value="<?php echo $form_data['patient_code']; ?>" />
                        </div>

                        <div class="grp">
                          <label>Patient Name</label>
                          <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" />
                        </div>

                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div> 

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>">
                        </div>
                          
                        <div class="grp">
                          <label>Attendent Doctor</label>
                          <select name="attended_doctor" id="attended_doctor">
                            <option value="">Select Attended By</option>
                            <?php
                            if(!empty($attended_doctor_list))
                            {
                              foreach($attended_doctor_list as $attended_doctor)
                              { 
                                ?>
                                  <option <?php if($form_data['attended_doctor']==$attended_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $attended_doctor->id; ?>"><?php echo $attended_doctor->doctor_name; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                          
                        <div class="grp">
                          <label>Profile</label>
                          <select name="profile_id" id="profile_id" onchange="get_profile_test();">
                            <option value="">Select Profile</option>
                                  <?php
                                   if(!empty($profile_list))
                                   {
                                      foreach($profile_list as $profile)
                                      { 
									      $selected = "";
									      if($form_data['profile_id']==$profile->id)
										  {
										     $selected =  'selected="selected"'; 
										  }
                                          echo '<option value="'.$profile->id.'" '.$selected.' >'.$profile->profile_name.'</option>';
                                      }
                                   }
                                  ?>
                          </select>
                        </div>
                          
                        <div class="grp">
                          <label>Sample collected</label>
                          <select name="sample_collected_by">
                            <option value=""> Select </option>
                            <?php
                            if(!empty($employee_list))
                            {
                              foreach($employee_list as $employee)
                              {
                                ?>
                                 <option <?php if($form_data['sample_collected_by']==$employee->id){ echo 'selected="selected"'; } ?> value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
                                <?php
                              }
                            }
                            ?> 
                          </select>
                        </div>
                          
                        <div class="grp">
                          <label>Staff Reference</label>
                          <select name="staff_refrenace_id">
                            <option value=""> Select Refrence </option>
                            <?php
                            if(!empty($employee_list))
                            {
                              foreach($employee_list as $employee)
                              {
                                ?>
                                 <option <?php if($form_data['staff_refrenace_id']==$employee->id){ echo 'selected="selected"'; } ?> value="<?php echo $employee->id; ?>"><?php echo $employee->name; ?></option>
                                <?php
                              }
                            }
                            ?> 
                          </select>
                        </div> 
                           

                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
                <!--<input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />-->
                 <input value="Reset" onclick="clear_form_elements(this.form)" type="button" class="btn-reset">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 

$(document).ready(function() 
{
  $("input[name$='referred_by']").click(function() 
  {
    var test2 = $(this).val();
    if(test2==0)
    {
      $("#hospital_div_1").hide();
      $("#doctor_div_1").show();
      $('#referral_hospital_1').val('');
      
    }
    else if(test2==1)
    {
        
        $("#doctor_div_1").hide();
        $("#hospital_div_1").show();
        $('#refered_id_1').val('');
        //$("#refered_id :selected").val('');
    }
      
  });
}); 
    function reset_search()
    {
      
      $.ajax({url: "<?php echo base_url(); ?>pathology_reports/reset_date_search/", 
        success: function(result)
        {
          $("#search_form").reset();
          reload_table();
        } 
      }); 
    }  
  $("#search_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('#overlay-loader').show();
     
    $.ajax({
      url: "<?php echo base_url('reports/advance_search/'); ?>",
      type: "post",
      data: $(this).serialize(),
      success: function(result) 
      {
        $('#load_advance_search_modal_popup').modal('hide'); 
        reload_table();        
      }
    });
  });  

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true
  });

   
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->