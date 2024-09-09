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
                        
                        <div class="grp">
                          <label>Referred By</label>
                          <select name="referral_doctor" id="referral_doctor">
                          <option value="">Select Referred By</option>
                          <?php
                          if(!empty($referal_doctor_list))
                          {
                            foreach($referal_doctor_list as $referal_doctor)
                            {
                              ?>
                                <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                        </div>
                        
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
                          <label>Patient Reg. No.</label>
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
               <input type="reset" onclick="return reset_date_search();" class="btn-reset" name="reset" value="Reset" data-dismiss="modal" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  
    
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