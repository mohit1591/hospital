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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>   
<body onload="set_tpa(<?php echo $form_data['insurance_type']; ?>)"> 
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
                              <!-- Patient Details page -->
<section class="content">
<form id="patient_form" name="ptaient_form" action="<?php echo current_url(); ?>/" method="post" enctype="multipart/form-data">
<input type="hidden" name="data_id" id="patient_id" value="<?php echo $form_data['data_id']; ?>">
<div class="content-inner">
  
    <div class="pat-col">
      <div class="grp">
        <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
        <div class="box-right"><?php echo $form_data['patient_code']; ?></div>
      </div>

      <div class="grp-full">
        <div class="grp">
            <label>Patient Name <span class="star">*</span></label>
            <div class="box-right">
                <select name="simulation_id" id="simulation_id" class="pat-select1">
                  <option value="">Select</option>
                  <?php
                  if(!empty($simulation_list))
                  {
                    foreach($simulation_list as $simulation)
                    {
                      $selected_simulation = '';
                      if($simulation->id==$form_data['simulation_id'])
                      {
                         $selected_simulation = 'selected="selected"';
                      }
                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                    }
                  }
                  ?> 
                </select> 
                <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" />
                <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
            </div>
          </div>
          <?php if(in_array('65',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>

      </div>


      <div class="grp">
        <label>Mobile No. <span class="star">*</span></label>
        <div class="box-right">
           <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
           <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
        </div>
      </div>


      <div class="grp">
        <label>Gender <span class="star">*</span></label>
        <div class="box-right">
            <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
        </div>
      </div>


      <div class="grp">
        <label>Age <span class="star">*</span></label>
        <div class="box-right">
            <input type="text" name="age_y" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
            <input type="text" name="age_m" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
            <input type="text" name="age_d" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
            <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
        </div>
      </div>


      <div class="grp">
        <label>Address</label>
        <div class="box-right">
            <textarea name="address" id="address" maxlength="255"><?php echo $form_data['address']; ?></textarea>
        </div>
      </div>
 
      
      <div class="grp">
        <label>Country</label>
        <div class="box-right">
            <select name="country_id" id="country_id" class="pat-select1" onchange="return get_state(this.value);">
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
      </div>

      <div class="grp">
        <label>State</label>
        <div class="box-right">
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
      </div>

      <div class="grp">
        <label>City</label>
        <div class="box-right">
            <select name="city_id" id="city_id">
              <option>Select City</option>
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


      <div class="grp">
        <label>Pincode</label>
        <div class="box-right">
            <input type="text" name="pincode" id="pincode" value="<?php echo $form_data['pincode']; ?>" maxlength="6" onkeypress="return isNumberKey(event);">
            <?php if(!empty($form_error)){ echo form_error('pincode'); } ?>
        </div>
      </div>


      <div class="grp">
        <label>Marital Status</label>
        <div class="box-right">
            <input type="radio" name="marital_status" value="1" <?php if($form_data['marital_status']==1){ echo 'checked="checked"'; } ?>> Married
            <input type="radio" name="marital_status" value="0" <?php if($form_data['marital_status']==0){ echo 'checked="checked"'; } ?>> Unmarried
        </div>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Religion</label>
            <div class="box-right">
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
          </div>
          <?php if(in_array('51',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a class="btn-new" href="javascript:void(0)" onclick="religion_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>


      <div class="grp">
        <label>Father/ Husband</label>
        <div class="box-right">
            <input type="text" name="father_husband" id="father_husband" value="<?php echo $form_data['father_husband']; ?>" />
        </div>
      </div>


      <div class="grp">
        <label>Mother Name</label>
        <div class="box-right">
            <input type="text" name="mother" id="mother" value="<?php echo $form_data['mother']; ?>" />
        </div>
      </div>



    </div> <!-- // -->

    <div class="pat-col">
      
      <div class="grp">
        <label>Guardian Name</label>
        <div class="box-right">
            <input type="text" name="guardian_name" id="guardian_name" value="<?php echo $form_data['guardian_name']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Guardian Email</label>
        <div class="box-right">
            <input type="text" name="guardian_email" id="guardian_email" value="<?php echo $form_data['guardian_email']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('guardian_email'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Guardian Mobile</label>
        <div class="box-right">
            <input type="text" name="guardian_phone" onkeypress="return isNumberKey(event);" id="guardian_phone" value="<?php echo $form_data['guardian_phone']; ?>" maxlength="10">
            <?php if(!empty($form_error)){ echo form_error('guardian_phone'); } ?>
        </div>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Relation</label>
            <div class="box-right">
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
          </div>
          <?php if(in_array('58',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a class="btn-new" href="javascript:void(0)" onclick="relation_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>
      
      <div class="grp">
        <label>Patient Email</label>
        <div class="box-right">
            <input type="text" name="patient_email" id="patient_email" value="<?php echo $form_data['patient_email']; ?>" />
            <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Monthly Income</label>
        <div class="box-right">
            <input type="text" name="monthly_income" onkeypress="return isNumberKey(event);" maxlength="10" id="monthly_income" value="<?php echo $form_data['monthly_income']; ?>" >
            <?php if(!empty($form_error)){ echo form_error('monthly_income'); } ?>
        </div>
      </div>
      
      <div class="grp">
        <label>Occupation</label>
        <div class="box-right">
            <input type="text" name="occupation" id="occupation" value="<?php echo $form_data['occupation']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Insurance Type</label>
        <div class="box-right">
            <input type="radio" name="insurance_type" value="0" <?php if($form_data['insurance_type']==0){ echo 'checked="checked"'; } ?> onclick="return set_tpa(0)"> Normal &nbsp;
            <input type="radio" name="insurance_type" value="1" <?php if($form_data['insurance_type']==1){ echo 'checked="checked"'; } ?> onclick="return set_tpa(1)"> TPA
        </div>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Type</label>
            <div class="box-right">
                <select name="insurance_type_id" id="insurance_type_id">
                  <option value="">Select Insurance Type</option>
                  <?php
                  if(!empty($insurance_type_list))
                  {
                    foreach($insurance_type_list as $insurance_type)
                    {
                      $selected_ins_type = "";
                      if($insurance_type->id==$form_data['insurance_type_id'])
                      {
                        $selected_ins_type = 'selected="selected"';
                      }
                      echo '<option value="'.$insurance_type->id.'" '.$selected_ins_type.'>'.$insurance_type->insurance_type.'</option>';
                    }
                  }
                  ?> 
                </select>
            </div>
          </div>
          <?php if(in_array('72',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a class="btn-new" href="javascript:void(0)" onclick="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>


      <div class="grp-full">
          <div class="grp">
            <label>Name</label>
            <div class="box-right">
                <select name="ins_company_id" id="ins_company_id">
                  <option value="">Select Insurance Company</option>
                  <?php
                  if(!empty($insurance_company_list))
                  {
                    foreach($insurance_company_list as $insurance_company)
                    {
                      $selected_company = '';
                      if($insurance_company->id == $form_data['ins_company_id'])
                      {
                        $selected_company = 'selected="selected"';
                      }
                      echo '<option value="'.$insurance_company->id.'" '.$selected_company.'>'.$insurance_company->insurance_company.'</option>';
                    }
                  }
                  ?> 
                </select>
            </div>
          </div>
          <?php if(in_array('79',$users_data['permission']['action'])) {
          ?>
               <div class="grp-right">
                    <a class="btn-new" href="javascript:void(0)" onclick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          <?php } ?>
      </div>
      
      <div class="grp">
        <label>Policy No.</label>
        <div class="box-right">
            <input type="text" name="polocy_no" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
        </div>
      </div>
      
      <div class="grp">
        <label>TPA ID</label>
        <div class="box-right">
            <input type="text" name="tpa_id" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Insurance Amount</label>
        <div class="box-right">
            <input type="text" name="ins_amount" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onkeypress="return isNumberKey(event);" />
        </div>
      </div>
      
      <div class="grp">
        <label>Authorization No.</label>
        <div class="box-right">
            <input type="text" name="ins_authorization_no" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
        </div>
      </div>
      
      <div class="grp">
        <label>Status</label>
        <div class="box-right">
            <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>> Active &nbsp;
            <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
        </div>
      </div>

      <div class="grp">
        <label></label>
        <div class="box-right">
            <button class="btn-update" id="form_submit"><i class="fa fa-refresh"></i> Update</button>
            <a href="<?php echo base_url('patient'); ?>" class="btn-update" style="text-decoration:none!important;color:#FFF;padding:8px 2em;"><i class="fa fa-sign-out"></i> Exit</a>
        </div>
      </div>

    </div> <!-- // --> 

    <div class="pat-col">
        <div class="pat-col-right-box">
          <strong><center>Patient Photo</center></strong>
          <div class="photo">
              <?php
               $img_path  = $file_img = base_url('assets/images/photo.png');
               if(!empty($form_data['data_id']) && !empty($form_data['old_img']))
                  {
                    $img_path = ROOT_UPLOADS_PATH.'patients/'.$form_data['old_img'];
                  }  
              ?>
              <img id="pimg" src="<?php echo $img_path; ?>" class="img-responsive">
          </div>
        </div>
        <div class="pat-col-right-box2">
          <strong>Select Image</strong>
          <input type="hidden" name="old_img"  value="<?php echo $form_data['old_img']; ?>" />
          <input type="file" id="img-input" accept="image/*" name="photo">
          <?php
           if(isset($photo_error) && !empty($photo_error))
           {
             echo '<div class="text-danger">'.$photo_error.'</div>';
           }
          ?>
        </div>
        <div class="pat-col-right-box2">
          <strong>Remark</strong>
          <textarea id="remark" name="remark" maxlength="255"><?php echo $form_data['remark']; ?></textarea>
        </div>
    </div> <!-- // -->

</div> <!-- content-inner -->
</form>
</section> <!-- content -->





<!-- =================== footer ============================== -->
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });

 

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

  function relation_modal()
  {
      var $modal = $('#load_add_relation_modal_popup');
      $modal.load('<?php echo base_url().'relation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function religion_modal()
  {
      var $modal = $('#load_add_religion_modal_popup');
      $modal.load('<?php echo base_url().'religion/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function insurance_type_modal()
  {
      var $modal = $('#load_add_insurance_type_modal_popup');
      $modal.load('<?php echo base_url().'insurance_type/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function insurance_company_modal()
  {
      var $modal = $('#load_add_insurance_company_modal_popup');
      $modal.load('<?php echo base_url().'insurance_company/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

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
  
  $('#form_submit').on("click",function(){
       $('#patient_form').submit();
  })
 
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
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
    }
 }

$(document).ready(function() {
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script> 

<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_religion_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_relation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div><!-- container-fluid -->
</body>
</html>