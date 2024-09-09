<?php
$users_data = $this->session->userdata('auth_users');
if (array_key_exists("permission",$users_data))
{
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];
}
else
{
     $permission_section = array();
     $permission_action = array();
}
?>
<div class="modal-dialog"> 
  <div class="overlay-loader" id="#loader-ajax">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
    <form  id="users_form" class="form-inline">
    <input type="hidden" name="data_id" id="user_id" value="<?php echo $form_data['data_id']; ?>" />
  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
            <div class="modal-body">  
         <?php
              if($total_users>0){
                    ?>
          <div class="row">
            <div class="col-md-12"> 

              <div class="row">
                <div class="col-md-12 m-b-5">
                    <div class="row">
                       
                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Employee Type <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <select id="emp_type_id" name="emp_type_id" class="inputFocus m_select_btn">
                                      <option value="">Select employee type</option>
                                      <?php
                                       if(!empty($type_list))
                                       { 
                                         foreach($type_list as $types)
                                         {
                                           if($form_data['emp_type_id'] == $types->id)
                                           {
                                             $selected = "selected = 'selected'";
                                           }
                                           else
                                           {
                                             $selected = "";
                                           }
                                           echo '<option value="'.$types->id.'" '.$selected.'>'.$types->emp_type.'</option>';
                                         } 
                                       }
                                      ?>
                                    </select>
                                    <?php if(in_array('31',$users_data['permission']['action'])) {
                                    ?>
                                        <a href="javascript:void(0)" onclick=" return add_emp_type();"  class="btn-new">
                                             <i class="fa fa-plus"></i> Add
                                        </a>
                                   <?php } ?>

                                    <?php if(!empty($form_error)){ echo form_error('emp_type_id'); } ?>

                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Employee <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <select id="emp_id" name="emp_id" class="m_select_btn">
                                      <option value="">Select employee</option> 
                                      <?php
                                      if(isset($emp_list) && !empty($emp_list))
                                      {
                                        foreach($emp_list as $emp)
                                        {
                                          $selected = "";
                                          if($emp->id==$form_data['emp_id'])
                                          {
                                            $selected = "selected = 'selected'";
                                          }
                                           ?>
                                            <option value="<?php echo $emp->id; ?>" <?php echo $selected; ?>><?php echo $emp->name; ?></option>
                                           <?php
                                        }
                                      }
                                      ?>
                                   </select> 
                                   <?php if(in_array('9',$users_data['permission']['action'])) {
                                   ?>
                                        <a href="javascript:void(0)" onclick=" return add_employee();"  class="btn-new">
                                             <i class="fa fa-plus"></i> Add
                                        </a>
                                   <?php } ?>
                                    <?php if(!empty($form_error)){ echo form_error('emp_id'); } ?>
                            </div>
                          </div>
                      </div>
                      
                      
                     <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>User Role </strong>
                            </div>
                            <div class="col-md-8">
                                  <select id="users_role" name="users_role" class="inputFocus m_select_btn">
                                      <option value="">Select user role</option>
                                      <?php
                                       if(!empty($role_list))
                                       { 
                                         foreach($role_list as $roles)
                                         {
                                             
                                             if($form_data['users_role'] == $roles->id)
                                           {
                                             $selected = "selected = 'selected'";
                                           }
                                           else
                                           {
                                             $selected = "";
                                           }
                                             
                                             if(!empty($roles->section_id) && in_array($roles->section_id,$permission_section))  //$roles->section_id=='378' &&
                                             {
                                                 echo '<option value="'.$roles->id.'" '.$selected.'>'.$roles->role.'</option>'; 
                                             }
                                             else if(empty($roles->section_id))
                                             {
                                                 echo '<option value="'.$roles->id.'" '.$selected.'>'.$roles->role.'</option>'; 
                                             }
                                           
                                          
                                         } 
                                       }
                                      ?>
                                    </select>
                                    

                                    <?php if(!empty($form_error)){ echo form_error('users_role'); } ?>

                            </div>
                          </div>
                      </div>

                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->
              
             


              <div class="row">
                <div class="col-md-12 m-b-5">
                    <div class="row">

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Email <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="email" data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text email_address" id="email" value="<?php echo $form_data['email']; ?>" />
                                
                                  
                                          <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                            </div>
                          </div>
                      </div> 
                      <?php if(in_array('145',$users_data['permission']['section'])) { ?>
                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Assign Doctors <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                       <select name="doctor_name[]" class="multi_type" multiple="multiple">
                            <?php
                                
                               if(!empty($doctor_list))
                               {
                                 foreach($doctor_list as $doctor_list_id)
                                  {
                                    
                                    if(in_array($doctor_list_id->id, $assigned_doctor))
                                      echo '<option  selected  value='.$doctor_list_id->id.'>'.$doctor_list_id->doctor_name.'</option> ';
                                    else
                                      echo '<option   value='.$doctor_list_id->id.'>'.$doctor_list_id->doctor_name.'</option> ';
                                  }
                               }
                            ?> 
                            </select>
                            </div>
                          </div>
                      </div> 
                      <?php } ?>

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Username <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" name="username" class="username inputFocus"  id="username" value="<?php echo $form_data['username']; ?>" />
                                  <?php if(!empty($form_error)){ echo form_error('username'); } ?>
                            </div>
                          </div>
                      </div>  
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->

              <div class="row">
                <div class="col-md-12 m-b-5">
                    <div class="row">
                    
                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Password<span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="password" name="password" data-toggle="tooltip"  autocomplete="new-password" title="Password should be minmum 6 digits contains atleast one lowercase,uppercase,special symbols." class="tooltip-text" id="password" value="<?php echo $form_data['password']; ?>" />
                                 
                                  <?php if(!empty($form_error)){ echo form_error('password'); } ?>
                            </div>
                          </div>
                      </div> 

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Confirm password <span class="star">*</span></strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="password" autocomplete="new-password" data-toggle="tooltip"  title="Password should be minmum 6 digits contains atleast one lowercase,uppercase,special symbols." class="tooltip-text" name="cpassword" id="cpassword" value="<?php echo $form_data['cpassword']; ?>" />
                               
                                  <?php if(!empty($form_error)){ echo form_error('cpassword'); } ?>
                            </div>
                          </div>
                      </div>  
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->


              <div class="row">
                <div class="col-md-12 br-h">
                    <div class="row">
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
                      
                      
                      <?php if($users_data['emp_id']==0){?>
                       <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Data Access</strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="radio" name="record_access" <?php if($form_data['record_access']==0){ echo 'checked="checked"'; } ?> id="record_access" value="0" /> All 
                                  <input type="radio" name="record_access" <?php if($form_data['record_access']==1){ echo 'checked="checked"'; } ?> id="record_access" value="1" /> Self  
                            </div>
                          </div>
                      </div> 
                      
                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Collection Type</strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="radio" name="collection_type" <?php if($form_data['collection_type']==0){ echo 'checked="checked"'; } ?> id="collection_type" value="0" /> All 
                                  <input type="radio" name="collection_type" <?php if($form_data['collection_type']==1){ echo 'checked="checked"'; } ?> id="collection_type" value="1" /> Self  
                            </div>
                          </div>
                      </div> 
                     <?php } ?>
                       
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row --> 


            </div> <!-- 12 -->
          </div> <!-- row -->


          <?php  
         }
         else
         {
         ?>
              <div class="row">
                   <div class="col-md-12">
                        <div class="text-danger">You exceed your users creation limits.</div>
                   </div>
              </div>   <!-- row --> 
         <?php  
         }  
         ?>


      </div>    <!--  modal-body --> 
             
             
        <div class="modal-footer"> 
           <input type="submit"  class="btn-update" name="submit" value="Save" />
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div>
</form>     
<script>

  $(document).ready(function() {
        $('.multi_type').multiselect({
          'includeSelectAllOption':true,
          'maxHeight':200,
          'includeSelectAllOption':false,
          'enableFiltering':false,
          
        });
    })
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right',
        trigger:'focus' 
    
    });   
}); 
</script>
<script> 
$("#emp_type_id").change(function() 
{
  var type_id = $(this).val();
  var data_id = $('#user_id').val();
  if(data_id>0)
  {
    data_id = '/'+data_id;
  }
  $.ajax({ 
          url: "<?php echo base_url('users/type_to_employee/'); ?>"+type_id+data_id, 
          success: function(result)
             {
                $('#emp_id').html(result);
             }
        });
});





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
 

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


 
$("#users_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#loader-ajax').show(); 
  var ids = $('#user_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'User successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'User successfully created.';
  }  

  $.ajax({
    url: "<?php echo base_url(); ?>users/"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg);
      } 
      else
            {
        $("#load_add_modal_popup").html(result);
      }   
      $('#loader-ajax').hide();     
    }
  });
}); 
 

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

function add_employee()
{
  var $modal = $('#load_add_emp_modal_popup');
  $modal.load('<?php echo base_url().'employee/add/' ?>',
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
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
  
   
</div><!-- /.modal-dialog --> 
<div id="load_add_emp_type_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div> 


<div id="load_add_emp_modal_popup" class="modal fade z-index-none m-r-1" role="dialog" data-backdrop="static" data-keyboard="false"></div>   