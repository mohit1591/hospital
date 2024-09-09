<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="emp_form" class="form-inline">
           <input type="hidden" name="data_id" id="emp_id" value="<?php echo $form_data['data_id']; ?>" />
                <div class="modal-header">
                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   <h4><?php echo $page_title; ?></h4> 
                </div>
                <div class="modal-body">  
                <!-- ============================================================================================ -->
                     <div class="row m-b-5">
                          <div class="col-md-12">
                               <div class="row m-b-5">
                                    <div class="col-md-12">
                                         <div class="row">
                                              <div class="col-md-6">
                                                   <div class="row">
                                                        <div class="col-md-4">
                                                             <strong>Reg. No. <span class="star">*</span></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                             <span><?php echo $form_data['reg_no']; ?></span>
                                                             <input type="hidden" name="reg_no" value="<?php echo $form_data['reg_no']; ?>">
                                                             <?php if(!empty($form_error)){ echo form_error('reg_no'); } ?>
                                                        </div>
                                                   </div>
                                              </div>
                                              <div class="col-md-6">
                                                   <div class="row">
                                                        <div class="col-md-4">
                                                             <strong>Employee Type <span class="star">*</span></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                             <select id="emp_type_id" name="emp_type_id" class="m_select_btn">
                                                                  <option value="">Select employee type</option>
                                                                  <?php
                                                                       if(!empty($type_list)){
                                                                            $selected = "";
                                                                            foreach($type_list as $types){
                                                                                 if($form_data['emp_type_id'] == $types->id){
                                                                                      $selected = "selected = 'selected'";
                                                                                 }
                                                                                 else{
                                                                                      $selected = "";
                                                                                 }
                                                                                 echo '<option value="'.$types->id.'" '.$selected.'>'.$types->emp_type.'</option>';
                                                                            } 
                                                                       }
                                                                  ?>
                                                             </select>
                                                             <?php if(in_array('31',$users_data['permission']['action'])) {
                                                             ?>
                                                             <a href="javascript:void(0)" onclick="return add_emp_type();"  class="btn-new">
                                                                  <i class="fa fa-plus"></i> Add
                                                             </a>
                                                             <?php } ?>
                                                             <?php if(!empty($form_error)){ echo form_error('emp_type_id'); } ?>

                                                        </div>
                                                   </div>
                                              </div>
                                         </div> <!-- row -->
                                    </div>
                               </div> <!-- // main row -->
                               <div class="row m-b-5">
                                    <div class="col-md-12">
                                         <div class="row">
                                              <div class="col-md-6">
                                                   <div class="row">
                                                        <div class="col-md-4">
                                                             <strong>Name <span class="star">*</span></strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                            <select name="simulation_id" id="simulation_id" class="mr m_mr" onchange="find_gender(this.value)">
                                                                 <option value="">Select</option>
                                                                 <?php
                                                                         $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
                                                                      if(!empty($simulation_list)){
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
                                                             <input type="text" name="name" id="name" class="w-133px inputFocus m_name" value="<?php echo $form_data['name']; ?>" />
                                                                    <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                                                             <?php   if(!empty($form_error)){ echo form_error('simulation_id'); } 
                                                             if(!empty($form_error)){ echo form_error('name'); } 
                                                                 ?>
                                                               <?php if(in_array('65',$users_data['permission']['action'])) {
                                                                ?>
                                                                
                                                            <?php } ?>
                                                        </div>
                                                   </div>
                                              </div> 
                                              <div class="col-md-6">
                                                   <div class="row">
                                                        <div class="col-md-4">
                                                             <strong>Email</strong>
                                                        </div>
                                                        <div class="col-md-8">
                                                             <input type="text" data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text email_address" name="email" id="email" value="<?php echo $form_data['email']; ?>" />
                                                       <?php if(!empty($form_error)){ echo form_error('email'); } ?>    
                                                        </div>
                                                   </div>
                                              </div> 
                                         </div> <!-- row -->
                                   </div>
                               </div> <!-- // main row -->
                               <div class="row m-b-5">
                                    <div class="col-md-12">
                                         <div class="row"> 
                                              <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Contact No.</strong>
                            </div>
                            <div class="col-md-8">
                             <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                              <input type="text" class="numeric number" name="contact_no" id="contact_no" maxlength="10" value="<?php echo $form_data['contact_no']; ?>" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text" /> 
                            </div>
                          </div>
                      </div>

                     
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->

              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row"> 

                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>DOB</strong>
                            </div>
                            <div class="col-md-8">
                              <input type="text" class="datepicker" readonly="" name="dob" id="dob" value="<?php if(isset($form_data['dob']) && $form_data['dob']!='01-01-1970'){echo $form_data['dob'];} else {echo '';}?>" /> 
                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Age</strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="age" id="age" class="numeric"  value="<?php echo $form_data['age']; ?>" />  
                            </div>
                          </div>
                      </div>
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->

              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row"> 

                      <div class="col-md-6">
                      <div class="row">
                            <div class="col-md-4">
                              <strong>Anniversary</strong>
                            </div>
                            <div class="col-md-8">
                              <input type="text" class="datepicker" readonly="" name="anniversary" id="anniversary" value="<?php if(isset($form_data['anniversary']) && $form_data['anniversary']!='01-01-1970'){echo $form_data['anniversary'];} else {echo '';}?>" /> 
                            </div>
                          </div>
                  </div>
                </div>
              </div>      
            </div>
              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row"> 
                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Sex <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8" id="gender">
                              <input type="radio" name="gender" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> id="male" value="1" /> Male 
                                  <input type="radio" name="gender" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?> id="female" value="0" /> Female 
                                   <input type="radio" name="gender" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?> id="others" value="2" /> Others 
                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Salary <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="salary" class="numeric" id="salary"  value="<?php echo $form_data['salary']; ?>"  /> 
                                  <?php if(!empty($form_error)){ echo form_error('salary'); } ?>
                            </div>
                          </div>
                      </div>
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->

              <div class="row m-b-4">
                <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Address</strong>
                            </div>
                            <div class="col-md-8">
                              <textarea rows="4" name="address" id="address" class="cbranch-txtar"><?php echo $form_data['address']; ?></textarea> 
                            </div>
                          </div>
                      </div>
                      
                     <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Qualification</strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="qualification" id="qualification"  value="<?php echo $form_data['qualification']; ?>" />
                            </div>
                          </div>
                      </div>
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->




              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row">

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Country</strong>
                            </div>
                            <div class="col-md-8">
                                  <select id="country_id" name="country_id" onchange="return get_state(this.value);">
                                      <option value="">Select Country</option>
                                      <?php
                                        if(!empty($country_list))
                                        { 
                                          foreach($country_list as $country)
                                          { 
                                            ?>
                                               <option value="<?php echo $country->id; ?>" <?php if($form_data['country_id'] == $country->id){ echo 'selected="selected"'; } ?>><?php echo $country->country; ?></option>
                                            <?php
                                          }
                                          $selected = "";
                                        }
                                      ?> 
                                  </select>  
                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Marital status  <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="radio" name="merital_status" <?php if($form_data['merital_status']==1){ echo 'checked="checked"'; } ?> id="merital_status" value="1" onclick="set_married(1);" > Single 
                                  <input type="radio" name="merital_status" <?php if($form_data['merital_status']==0){ echo 'checked="checked"'; } ?> id="merital_status" value="2" onclick="set_married(0);"/> Married  
                            </div>
                          </div>
                      </div>
                       
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->
 
              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row">
                      
                      

                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>State</strong>
                            </div>
                            <div class="col-md-8">
                              <select id="state_id" name="state_id" onchange="return get_city(this.value);">
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
                          </div>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Status  <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active 
                                  <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive  
                            </div>
                          </div>
                      </div> 
                       
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row --> 


              <div class="row m-b-5">
                <div class="col-md-12">
                    <div class="row">
                     


                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>City</strong>
                            </div>
                            <div class="col-md-8">
                                  <select id="city_id" name="city_id">
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
                          </div>
                      </div> 
                       
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row --> 

              <div class="row m-b-4">
                <div class="col-md-12">
                    <div class="row">
                      
                      
                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>PIN Code</strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="postal_code" maxlength="6" id="postal_code" value="<?php echo $form_data['postal_code']; ?>" class="numeric" data-toggle="tooltip"  title="Postal Code should be six digit  only numeric." class="tooltip-text" />  
                            </div>
                          </div>
                      </div>
                      
                  </div> <!-- row -->
                </div>
              </div>

            </div> <!-- 12 -->
          </div> <!-- row -->


          


      </div>    <!--  modal-body --> 
             
             
        <div class="modal-footer"> 
           <input type="submit"  class="btn-update" name="submit" value="Save" />
           <button type="button" class="btn-cancel" data-number="2">Close</button>
        </div>
</form>     
<script>

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
     var simulation_id = $("#simulation_id :selected").val();
    find_gender(simulation_id);
    set_married(<?php echo $form_data['merital_status']; ?>);

}); 

 function find_gender(id){
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }
 function set_married(val)
 { 
    if(val==1)
    {
      $('#anniversary').attr("disabled", true);
      $('#anniversary').val('');
    }
    else
    {
      $('#anniversary').attr("disabled", false);
      
    }
 }
</script>


<script> 


function get_state(country_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(); 
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
 
//function to find gender according to selected simulation

 //ends
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
function simulation_modal()
  {
      var $modal = $('#load_add_simulation_modal_popup');
      $modal.load('<?php echo base_url().'simulation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }


 
$("#emp_form").on("submit", function(event) { 
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#emp_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Employee successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Employee successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>employee/"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_emp_modal_popup').modal('hide');
        get_employees();
        reload_table();
        flash_session_msg(msg);
      } 
      else
            {
        $("#load_add_emp_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
}); 

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    startView: 2
  })

function add_emp_type()
{ 
  var $modal = $('#load_add_emp_type_modal_popup');
  $modal.load('<?php echo base_url().'employee_type/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

$("button[data-number=1]").click(function(){
    $('#load_add_emp_type_modal_popup').modal('hide'); 
});

$("button[data-number=2]").click(function(){
    $('#load_add_emp_modal_popup').modal('hide'); 
});
function get_employees()
    {
       var emp_type_id = '<?php echo $this->session->userdata('emp_type_id');?>';
      
       if(emp_type_id!="" && emp_type_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>users/type_to_employee/"+emp_type_id, 
              success: function(result)
              {
                $('#emp_id').html(result); 
              } 
            });
       } 
    }

    
$(document).ready(function() {
   $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e) {
      $(this).find('.inputFocus').focus();
   })
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
     
</div><!-- /.modal-dialog -->    
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_emp_type_modal_popup" class="modal fade  top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>

