 <?php  $users_data = $this->session->userdata('auth_users'); ?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="advance_search_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                          <input type="text" class="datepicker start_datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>" id="" >
                        </div>
                        
                        <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" class="inputFocus" name="patient_code"value="<?php echo $form_data['patient_code']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Patient Name </label>
                          <div class="rslt">
                              <select name="simulation_id" id="simulation_id" class="mr" onchange="find_gender(this.value)">
                                  <option value="">Select</option>
                                  <?php
                                   if(!empty($simulation_list))
                                  {
                                    foreach($simulation_list as $simulation)
                                    {
                                      $selected_simulation = '';
                                       if(in_array($simulation->simulation,$simulations_array)){

                                              $selected_simulation = 'selected="selected"';
                              
                                        }
                                       else{
                                            if($simulation->id==$form_data['simulation_id'])
                                            {
                                                 $selected_simulation = 'selected="selected"';
                                        }
                                   }
                                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                                    }
                                  }
                                  ?> 
                                </select> 
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name inputFocus" autofocus="">
                          </div>
                        </div>



                         <div class="grp">
                            <label>  
                              <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
                              <?php foreach($gardian_relation_list as $gardian_list) 
                              {?>
                              <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
                              <?php }?>
                              </select>
                            </label>
                          <div class="rslt">
                              <select name="relation_simulation_id" id="relation_simulation_id" class="mr">
                                  <option value="">Select</option>
                                      <?php
                                          if(!empty($simulation_list))
                                          {
                                            $s = 1;
                                            $sim_id = '';
                                            foreach($simulation_list as $simulation)
                                            {
                                              $selected_simulation = '';

                                              if($simulation->id==$form_data['relation_simulation_id'])
                                              {
                                                $selected_simulation = 'selected="selected"';
                                              }
                                              if($s==1)
                                              {
                                                $sim_id = $simulation->id;
                                              }
                                              echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                                              $s++;
                                            }

                                          }
                                      ?> 
                                </select> 
                             <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="p-name inputFocus"/>
                          </div>
                        </div>


                       <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                        <div class="grp">
                           <label>Gender</label>
                          <div class="rslt" id="gender">
                            <input type="radio" name="gender" value="1" <?php if(isset($form_data['gender']) && $form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                            <input type="radio" name="gender" value="0" <?php if(isset($form_data['gender']) && $form_data['gender']!="" && $form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                             <input type="radio" name="gender" value="2" <?php if(isset($form_data['gender']) && $form_data['gender']!="" && $form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
                          </div>
                        </div>

                        <div class="grp">
                           <label>Age </label>
                          <div class="rslt">
                           <input type="text" name="start_age_y" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['start_age_y']; ?>"> To
                            <input type="text" name="end_age_y" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['end_age_y']; ?>"> 
                          </div>
                        </div>
                        
                        <div class="grp">
                          <label>Address</label>
                          <textarea name="address" id="address" maxlength="255"><?php echo $form_data['address']; ?></textarea>
                        </div>
                        
                        <div class="grp">
                          <label>Country</label>
                          <select name="country_id" id="country_id" onchange="return get_state(this.value);">
                            <option value="">Select Country</option>
                            <?php
                            if(!empty($country_list))
                            {
                              foreach($country_list as $country)
                              {
                                $selected_country = "";
                                if($country->id==$form_data['country_id'])
                                {
                                  $selected_country = 'selected="selected"';
                                }
                                echo '<option value="'.$country->id.'" '.$selected_country.'>'.$country->country.'</option>';
                              }
                            }
                            ?> 
                          </select>
                        </div>
                        
                        <div class="grp">
                          <label>State</label>
                          <select name="state_id" id="state_id" onchange="return get_city(this.value)">
                            <option value="">Select State</option>
                            <?php
                           if(!empty($form_data['country_id']))
                           {
                              $state_list = state_list($form_data['country_id']); 
                              if(!empty($state_list))
                              {
                                 foreach($state_list as $state)
                                 {  
                                  ?>   
                                    <option value="<?php echo $state->id; ?>" <?php if(!empty($form_data['state_id']) && $form_data['state_id'] == $state->id){ echo 'selected="selected"'; } ?>><?php echo $state->state; ?></option>
                                  <?php
                                 }
                              }
                           }
                          ?>
                          </select>
                        </div>
                        
                        <div class="grp">
                          <label>City</label>
                          <select name="city_id" id="city_id">
                              <option value="">Select City</option>
                              <?php
                               if(!empty($form_data['state_id']))
                               {
                                  $city_list = city_list($form_data['state_id']);
                                  if(!empty($city_list))
                                  {
                                     foreach($city_list as $city)
                                     {
                                      ?>   
                                        <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>>
                                        <?php echo $city->city; ?> 
                                        </option>
                                      <?php
                                     }
                                  }
                               }
                              ?>
                            </select>
                        </div>
                        
                        <div class="grp">
                          <label>Pin Code</label>
                          <input type="text" name="pincode" id="pincode" value="<?php echo $form_data['pincode']; ?>" maxlength="6" onkeypress="return isNumberKey(event);">
                        </div>

                        <div class="grp">
                           <label>Marital Status</label>
                          <div class="rslt">
                            <div class="gen">
                            <input type="radio" name="marital_status" value="1" <?php if(isset($form_data['marital_status']) &&  $form_data['marital_status']==1){ echo 'checked="checked"'; } ?>> Married
                            </div>
                            <div class="gen">
                            <input type="radio" name="marital_status" value="0" <?php if(isset($form_data['marital_status']) &&  $form_data['marital_status']!="" && $form_data['marital_status']==0){ echo 'checked="checked"'; } ?>> Unmarried
                            </div>
                          </div>
                        </div>
                        
                        <div class="grp">
                          <label>Religion</label>
                          <select name="religion_id" id="religion_id">
                              <option value="">Select Religion</option>
                              <?php
                              if(!empty($religion_list))
                              {
                                foreach($religion_list as $religion)
                                {
                                  $selected_religion = "";
                                  if($religion->id==$form_data['religion_id'])
                                  {
                                    $selected_religion = 'selected="selected"';
                                  }
                                  echo '<option value="'.$religion->id.'" '.$selected_religion.'>'.$religion->religion.'</option>';
                                }
                              }
                              ?> 
                            </select>
                        </div>
                        
                       
                      
                        
                        <div class="grp">
                          <label>Mother Name</label>
                          <input type="text" name="mother" id="mother" value="<?php echo $form_data['mother']; ?>" />
                        </div>

                         

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>" id="">
                        </div>
                          
                        <div class="grp">
                          <label>Guardian Name</label>
                          <input type="text" name="guardian_name" id="guardian_name" value="<?php echo $form_data['guardian_name']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Guardian Email</label>
                          <input type="text" name="guardian_email" id="guardian_email" value="<?php echo $form_data['guardian_email']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Guardian Mobile</label>
                          <input type="text" name="guardian_phone" onkeypress="return isNumberKey(event);" id="guardian_phone" value="<?php echo $form_data['guardian_phone']; ?>" maxlength="10">
                        </div>
                          
                        <div class="grp">
                          <label>Relation</label>
                          <select name="relation_id" id="relation_id">
                              <option value="">Select Relation</option>
                              <?php
                              if(!empty($relation_list))
                              {
                                foreach($relation_list as $relation)
                                {
                                  $selected_relation = "";
                                  if($relation->id==$form_data['relation_id'])
                                  {
                                    $selected_relation = "selected='selected'";
                                  }
                                  echo '<option value="'.$relation->id.'" '.$selected_relation.'>'.$relation->relation.'</option>';
                                }
                              }
                              ?> 
                            </select>
                        </div>
                          
                        <div class="grp">
                          <label>Patient Email</label>
                          <input type="text" name="patient_email" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Monthly Income</label>
                          <input type="text" name="monthly_income" onkeypress="return isNumberKey(event);" maxlength="10" id="monthly_income" value="<?php echo $form_data['monthly_income']; ?>" >
                        </div>
                          
                        <div class="grp">
                          <label>Occupation</label>
                          <input type="text" name="occupation" id="occupation" value="<?php echo $form_data['occupation']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Insurance Type</label>
                          <div class="rslt">
                            <div class="gen">
                            <input type="radio" name="insurance_type" value="0" <?php if(isset($form_data['insurance_type']) && $form_data['insurance_type']!="" && $form_data['insurance_type']==0){ echo 'checked="checked"'; } ?> onclick="return set_tpa(0)"> Normal 
                            </div>
                            <div class="gen">
                            <input type="radio" name="insurance_type" value="1" <?php if(isset($form_data['insurance_type']) && $form_data['insurance_type']==1){ echo 'checked="checked"'; } ?> onclick="return set_tpa(1)"> TPA
                            </div>
                          </div>
                        </div>
                          
                        <div class="grp">
                          <label>Type</label>
                          <select name="insurance_type_id" id="insurance_type_id">
                              <option value="">Select Insurance Type</option>
                              <?php
                              if(!empty($insurance_type_list))
                              {
                                foreach($insurance_type_list as $insurance_type)
                                {
                                  $selected_ins_type = "";
                                  if(isset($form_data['insurance_type_id']) && $insurance_type->id==$form_data['insurance_type_id'])
                                  {
                                    $selected_ins_type = 'selected="selected"';
                                  }
                                  echo '<option value="'.$insurance_type->id.'" '.$selected_ins_type.'>'.$insurance_type->insurance_type.'</option>';
                                }
                              }
                              ?> 
                            </select>
                        </div>
                          
                        <div class="grp">
                          <label>Name</label>
                          <select name="ins_company_id" id="ins_company_id">
                            <option value="">Select Insurance Company</option>
                            <?php
                            if(!empty($insurance_company_list))
                            {
                              foreach($insurance_company_list as $insurance_company)
                              {
                                $selected_company = '';
                                if(isset($form_data['ins_type_id']) && $insurance_company->id == $form_data['ins_company_id'])
                                {
                                  $selected_company = 'selected="selected"';
                                }
                                echo '<option value="'.$insurance_company->id.'" '.$selected_company.'>'.$insurance_company->insurance_company.'</option>';
                              }
                            }
                            ?> 
                          </select>
                        </div>
                          
                        <div class="grp">
                          <label>Policy No.</label>
                          <input type="text" name="polocy_no" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
                        </div>
                          
                        <div class="grp">
                          <label>TPA ID</label>
                          <input type="text" name="tpa_id" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Insurance Amount</label>
                          <input type="text" name="ins_amount" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onkeypress="return isNumberKey(event);" />
                        </div>
                          
                        <div class="grp">
                          <label>Authorization No.</label>
                          <input type="text" name="ins_authorization_no" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Status</label>
                          <div class="rslt">
                            <div class="gen">
                              <input type="radio" name="status" value="1" <?php if(isset($form_data['status']) && $form_data['status']==1){ echo 'checked="checked"'; } ?>> Active
                            </div>
                            <div class="gen"> 
                              <input type="radio" name="status" value="0" <?php if(isset($form_data['status']) && $form_data['status']!="" && $form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
                            </div>
                          </div>
                        </div>


              <?php /*if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
                    { ?>
                       <div class="grp">
                                <label>User</label>
                                
                                <select name="employee"  id="employee">
                                      <option value="">Select User</option>
                                      <option value="<?php echo $users_data['id']; ?>">Self</option>
                                    <?php 
                                     
                                      if(!empty($employee_user_list))
                                      {
                                        foreach($employee_user_list as $employee)
                                        {
                                          echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                                        }
                                      }
                                    ?> 
                                </select>
                        </div>
                        <?php } 
                        else 
                        {?>
                        <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

                  <?php }*/ ?>

                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> -->
               <input value="Reset" class="btn-reset"  onclick="reset_search(this.form)" type="button">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  

function father_husband_son()
{
  // alert();
  //  $("#relation_name").css("display","block");
}
<?php
if(isset($form_data['insurance_type']) && $form_data['insurance_type']!="" && isset($form_data['insurance_type_id']) && $form_data['insurance_type_id']!="")
{
  echo 'set_tpa('.$form_data['insurance_type_id'].');';
}
?>
 $(document).ready(function(){
   var simulation_id = $("#simulation_id :selected").val();
    find_gender(simulation_id);
});
 //function to find gender according to selected simulation
 function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }
 //ends
function set_tpa(val)
 {
    if(val==0)
    {
      $('#insurance_type_id').attr("disabled", true);
      $('#insurance_type_id').val('');
      $('#ins_company_id').attr("disabled", true);
      $('#ins_company_id').val('');
      $('#polocy_no').attr("readonly", "readonly");
      $('#polocy_no').val('');
      $('#tpa_id').attr("readonly", "readonly");
      $('#tpa_id').val('');
      $('#ins_amount').attr("readonly", "readonly");
      $('#ins_amount').val('');
      $('#ins_authorization_no').attr("readonly", "readonly");
      $('#ins_authorization_no').val('');
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
      $('#ins_authorization_no').removeAttr("readonly", "readonly");
    }
 }

function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
}

function get_city(state_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
    success: function(result)
    {
      $('#city_id').html(result); 
    } 
  }); 
}

function reset_search(ele)
{

   $.ajax({url: "<?php echo base_url(); ?>patient/reset_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        reload_table();
      } 
  }); 


}

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#advance_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('patient/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 

$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });

$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->