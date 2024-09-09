 <style>

.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline"> 
      
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
                        
                     <!--    <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker" name="start_date" value="<?php  // echo $form_data['start_date']; ?>" id="start_date">
                        </div> -->
                        
                         <div class="grp">
                          <label>Patient <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </label>
                          <input type="text" id="patient_code" name="patient_code" value="<?php echo $form_data['patient_code']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Patient Name</label>
                        
                              <input type="text" id="patient_name" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                         
                        </div>
                        
                      
                        
                      
                        <div class="row m-b-5">
                            <div class="col-md-5">
                                <label>Referred By</label>
                            </div>
                            <div class="col-md-7" id="referred_by_1">
                                   <label><input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
                                    <label><input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
                                    
                            </div>
                        </div> <!-- innerrow -->

                        <div class="row m-b-5" id="doctor_div_1" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
                            <div class="col-md-5">
                                <label>Referred By</label>
                            </div>
                            <div class="col-md-7">
                                <select class="w-150px" name="refered_id" id="refered_id_1">
                                    <option value="">Select Doctor</option>
                                    <?php foreach($doctors_list as $doctors) {?>
                                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                                    <?php }?>
                                </select>
                                   

                            </div>
                        </div> <!-- innerrow -->

                        <div class="row m-b-5" id="hospital_div_1" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
                            <div class="col-md-5">
                                <label>Referred By</label>
                            </div>
                            <div class="col-md-7">
                                <select name="referral_hospital" id="referral_hospital_1" class="w-150px m_input_default" >
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

                      </div> <!-- inner -->

                      <div class="inner">

                        <div class="grp">
                          <label>Sale Return No. </label>
                          <input type="text" id="return_no" name="return_no" value="<?php echo $form_data['return_no']; ?>">
                        </div>

                        <div class="grp">
                          <label>Sale No. </label>
                          <input type="text" id="sale_no" name="sale_no" value="<?php echo $form_data['sale_no']; ?>">
                        </div>

                       <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" id="mobile_no" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                      <?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
                        <?php if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
                        { ?>    
                        <div class="grp">
                              <label>User</label>
                                <select name="employee" class="m_input_default" id="employee">
                                    <option value="">Select User</option>
                                    <?php 
                                      if(!empty($employee_list))
                                      {
                                        foreach($employee_list as $employee)
                                        {
                                          echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                                        }
                                      }
                                    ?> 
                                </select>
                            <?php 
                            } 
                            else 
                            { ?>
                            <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

                        <?php } ?>
                       </div>
                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 
$(document).ready(function() {
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
<?php
if(isset($form_data['insurance_type']) && $form_data['insurance_type']!="" && isset($form_data['insurance_type_id']) && $form_data['insurance_type_id']!="")
{
  echo 'set_tpa('.$form_data['insurance_type_id'].');';
}
?>
 
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

function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>sales_return_medicine/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      $('#patient_code').val();
      $('#patient_name').val();
      $('#referred_by_1').val();
      $('#refered_id_1').val();
      $('#referral_hospital_1').val();
      $('#return_no').val('');
      $('#sale_no').val('');
      $('#mobile_no').val('');
      $('#employee').val('');
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
 
$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('sales_return_medicine/advance_search/'); ?>",
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
 
 /* $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true
  })*/

    var today =new Date();
  $('#start_date').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date").datepicker("option", "minDate", selected);
        }
  })
 $('#end_date').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date").datepicker("option", "maxDate", selected);
        }
  })
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->