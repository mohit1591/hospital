<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(3);
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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
var save_method; 
var table; 
 
 
function view_test(id)
{
  var $modal = $('#load_add_test_modal_popup');
  $modal.load('<?php echo base_url().'test/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
}
</script>

</head>

<body onLoad="set_tpa(<?php echo $form_data['pannel_type']; ?>)">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="path-booking">
<form action="<?php echo current_url(); ?>" method="post" id="appointment_form">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" />
<input type="hidden" name="type" value="1" />


<div class="row">
<div class="col-xs-4">

  <!-- <div class="row m-b-5">
    <div class="col-md-12">
       <a class="btn-new" href="<?php echo base_url('patient'); ?>"><i class="fa fa-user"></i> <b>Registered Patient</b></a>
    </div>
  </div> --> <!-- row -->
  

  <div class="row m-b-5">
      <div class="col-md-12">
         <div class="row">
           <div class="col-md-4"><b>Patient</b></div>
           <div class="col-md-8" id="opd_type">
             <input type="radio" name="new_patient" <?php if(empty($form_data['patient_id'])) { ?> checked <?php } ?> > New 
             <input type="radio" name="new_patient" onClick="window.location='<?php echo base_url('patient');?>';" <?php if(!empty($form_data['patient_id'])) { ?> checked <?php } ?>> Registered

           </div>
         </div>
      </div>
    </div> <!-- row -->  

  <div class="row m-b-5">
      <div class="col-md-12">
         <div class="row">
           <div class="col-md-4"><b>OPD Type</b></div>
           <div class="col-md-8" id="opd_type">
             <input type="radio" name="opd_type" value="0" <?php if($form_data['opd_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
              <input type="radio"  name="opd_type"  value="1" <?php if($form_data['opd_type']==1){ echo 'checked="checked"'; } ?>> <?php echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');  ?>
              <?php if(!empty($form_error)){ echo form_error('opd_type'); } ?>
           </div>
         </div>
      </div>
    </div> <!-- row -->


  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Branches</b></div>
         <div class="col-md-8">
           <?php    $users_data = $this->session->userdata('auth_users');
                    $sub_branch_details = $this->session->userdata('sub_branches_data');
                    $parent_branch_details = $this->session->userdata('parent_branches_data');
                    //$branch_name = get_branch_name($parent_branch_details[0]);
                ?>
        <select name="branch_id" id="branch_id">
           <option value="">Select Branch</option>
           
           <option  <?php if($users_data['parent_id']==$form_data['branch_id']){ ?> selected="selected" <?php } ?>  value="<?php echo $users_data['parent_id'];?>">Self</option>';
             <?php 
             if(!empty($sub_branch_details)){
                 $i=0;
                foreach($sub_branch_details as $key=>$value){
                     ?>
                     <option <?php if($sub_branch_details[$i]['id']==$form_data['branch_id']){ ?> selected="selected" <?php } ?> value="<?php echo $sub_branch_details[$i]['id'];?>"><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                     <?php 
                     $i = $i+1;
                 }
               
             }
            ?> 
            </select>
         </div>
       </div>
    </div>
  </div> <!-- row -->


  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b><?php echo $data= get_setting_value('PATIENT_REG_NO');?></b></div>
         <div class="col-md-8">
            <input type="text"  class="m_input_default" readonly="" id="patient_code" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
         </div>
       </div>
    </div>
  </div> <!-- row -->
 
  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Appointment No.</b></div>
         <div class="col-md-8">
           <input type="text" readonly="" id="appointment_code" class="numeric m_input_default" name="appointment_code" value="<?php echo $form_data['appointment_code']; ?>"/> 
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4 pr-0"><b>Patient Name <span class="star">*</span></b></div>
         <div class="col-md-8 p-r-0">
           <select class="mr  alpha_space m_mr"  name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                <option value="">Select</option>
                <?php
                $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
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
              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="mr-name m_name txt_firstCap"  autofocus/>
              <a href="javascript:void(0)" onclick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                <div class="clear">
                <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                <?php if(in_array('65',$users_data['permission']['action'])) {
                 ?>
               </div>
                    
               
          <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

     <!-- new code by mamta -->
        <div class="row m-b-5">
            <div class="col-md-4">
                <strong> 
                <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
                <?php foreach($gardian_relation_list as $gardian_list) 
                {?>
                <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
                <?php }?>
                </select>

                </strong>
            </div>
        <div class="col-md-8">
            <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id">
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
                    else
                    {
                        if($simulation->id==$form_data['relation_simulation_id'])
                        {
                        $selected_simulation = 'selected="selected"';
                        }
                    }
                    echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                    }
                }
                ?> 
            </select> 
            <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
        </div>
        </div> <!-- row -->

        <!-- new code by mamta -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Mobile No.
           <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==25 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
         </b></div>
         <div class="col-md-8">
         <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91"> 
           <input type="text" id="mobile_no" name="mobile_no" class="numeric number m_number" value="<?php echo $form_data['mobile_no']; ?>" maxlength="10" >
              <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='25' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile_no'); } 
                        }
                  }
            ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Gender <span class="star">*</span></b></div>
         <div class="col-md-8" id="gender">
           <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Age 
            <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==26 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?>
         </b></div>
         <div class="col-md-8">
              <input type="text" id="age_y" name="age_y" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m" id="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d" class="input-tiny m_tiny numeric"  maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
              <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='26' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); }
                        }
                  } 
               ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"></div>
         <div class="col-md-8" style="text-align: center;">
            <a href="javascript:void(0);" onclick="more_patient_info()">More Info</a>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div id="patient_info" style="display: none;">  
  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Email Id </b></div>
         <div class="col-md-8">
           <input type="text" name="patient_email" id="patient_email" class="email_address m_input_default" value="<?php echo $form_data['patient_email']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('patient_email'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

   <div class="row m-b-3">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address1</b></div>
         <div class="col-md-8">
          <input type="text" name="address" id="address" class="address" maxlength="255" value="<?php echo $form_data['address']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->
    <div class="row m-b-3">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address2</b></div>
         <div class="col-md-8">
           <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php echo $form_data['address_second']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->
    <div class="row m-b-3">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Address3</b></div>
         <div class="col-md-8">
           <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php echo $form_data['address_third']; ?>"/>
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div class="row m-b-3">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Aadhaar No.</b></div>
         <div class="col-md-8">
           <input type="text" name="adhar_no" class="m_input_default numeric" id="adhar_no" value="<?php echo $form_data['adhar_no']; ?>"/>
            <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Country</b></div>
         <div class="col-md-8">
           <select name="country_id" class="m_input_default" id="countrys_id" onchange="return get_state(this.value);">
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
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>State</b></div>
         <div class="col-md-8">
           <select name="state_id" class="m_input_default" id="states_id" onchange="return get_city(this.value)">
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
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>City</b></div>
         <div class="col-md-8">
           <select name="city_id" class="m_input_default" id="citys_id">
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
    </div>
  </div> <!-- row -->
</div>  

</div> <!-- main4 -->



<!-- // ================ Second column -->



<div class="col-xs-4 media_margin_left">
<?php if(in_array('916',$users_data['permission']['action'])) { ?>
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Source From</b></div>
        <div class="col-md-7" id="patientsourceid">
           <select name="source_from" id="patient_source_id" class="w-150px m_select_btn">
              <option value="">Select Source</option>
              <?php
              if(!empty($source_list))
              {
                foreach($source_list as $source)
                {
                  ?>
                    <option <?php if($form_data['source_from']==$source->id){ echo 'selected="selected"'; } ?> value="<?php echo $source->id; ?>"><?php echo $source->source; ?></option>
                    
                  <?php
                }
              }
              ?>
    </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) { ?>
            <a  class="btn-new" id="patient_source_add_modal"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
<?php }else{ ?> <input type="hidden" name="source_from" value="0"> <?php } ?> 
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Diseases</b></div>
        <div class="col-md-7" id="diseaseid">
           <select name="diseases" id="disease_id" class="w-150px m_select_btn">
              <option value="">Select Diseases</option>
              <?php
              if(!empty($diseases_list))
              {
                foreach($diseases_list as $diseases)
                {
                  ?>
                    <option <?php if($form_data['diseases']==$diseases->id){ echo 'selected="selected"'; } ?> value="<?php echo $diseases->id; ?>"><?php echo $diseases->disease; ?></option>
                    
                  <?php
                }
              }
              ?>
    </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    
                    <a  class="btn-new" id="diseases_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
      </div>
    </div>
  </div> <!-- row -->


<?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
<div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By</b></div>
         <div class="col-md-7" id="referred_by">
           <input type="radio" name="referred_by" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor &nbsp;
            <input type="radio" name="referred_by" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital
            <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->




  <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By Doctor</b></div>
         <div class="col-md-7">
           <select name="referral_doctor" id="refered_id" class="w-150px m_select_btn"  onChange="return get_other(this.value)">
              <option value="">Select Doctor</option>
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

              <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  


    <div class="row m-b-5" id="ref_by_other" <?php if($form_data['referral_doctor']=='0' && !empty($form_data['ref_by_other'])){ }else{ ?> style="display: none;" <?php } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b> Other </b></div>
         <div class="col-md-7">
           <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->


<div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By Hospital</b></div>
         <div class="col-md-7">
           <select name="referral_hospital" id="referral_hospital" class="w-150px m_select_btn">
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
            <?php if(in_array('999',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="hospital_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
<?php }else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){

?>
  
  <div class="row m-b-5" id="" >
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred By Doctor</b></div>
         <div class="col-md-7">
           <select name="referral_doctor" id="refered_id" class="w-150px m_select_btn"  onChange="return get_other(this.value)">
              <option value="">Select Doctor</option>
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

              <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
            </select> 
            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
  


    <div class="row m-b-5" id="ref_by_other" <?php if($form_data['referral_doctor']=='0' && !empty($form_data['ref_by_other'])){ }else{ ?> style="display: none;" <?php } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b> Other </b></div>
         <div class="col-md-7">
           <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
    </div>
  </div> 
 
  <input type="hidden" name="referral_hospital" value="0">
  <input type="hidden" name="referred_by" value="0">
  
<?php 


  }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

    ?>

    <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Referred by Hospital</b></div>
         <div class="col-md-7">
           <select name="referral_hospital" id="referral_hospital" class="w-150px m_select_btn">
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
            <?php if(in_array('999',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="hospital_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
         </div>
       </div>
    </div>
  </div>
  <input type="hidden" name="referred_by" value="1">
  <input type="hidden" name="ref_by_other" value="0"> 
  <input type="hidden" name="referral_doctor" value="0">
    <?php 
    } ?>




   

  






  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Specialization
         <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==41 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
            ?>
         </b></div>
         <div class="col-md-7" id="specilizationid">
        
           <select name="specialization" class="w-150px m_select_btn" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
              <option value="">Select Specialization</option>
              <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specializationlist)
                {
                  ?>
                    <option <?php if($form_data['specialization']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization;  ?></option>
                  <?php
                }
              }
              ?>
            </select> 
             <?php if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                    <a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new"><i class="fa fa-plus"></i> New</a>
               <?php } ?>
            <?php //if(!empty($form_error)){ echo form_error('specialization'); } ?>  
            <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='41' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('specialization'); } 
                        }
                  }
            ?> 
         </div>

       </div>
    </div>
  </div> <!-- row -->
  

  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Consultant  <?php if(!empty($field_list)){
                    if($field_list[3]['mandatory_field_id']==42 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
            ?></b></div>
         <div class="col-md-7"> <!-- attended_doctor change for new doctor add -->
           <select name="attended_doctor" class="w-150px m_select_btn" id="attended_doctor" onchange="return consultant_charge(this.value);">
              <option value="">Select Attended By</option>
              <?php
             if(!empty($form_data['specialization']))
             {
               
                $doctor_list = doctor_specilization_list($form_data['specialization'],$form_data['branch_id']); 
                
                if(!empty($doctor_list))
                {
                   foreach($doctor_list as $doctor)
                   {  
                        
                    ?>   
                      <option value="<?php echo $doctor->id; ?>"  <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                     
                   }
                }
             }
            ?>
            </select>

            <?php if(in_array('122',$users_data['permission']['action'])) {
                ?>
                    <a  class="btn-new" id="doctor_add_modal_2"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
                <?php // if(!empty($form_error)){ echo form_error('attended_doctor'); } ?>
                 <?php if(!empty($field_list)){
                         if($field_list[3]['mandatory_field_id']=='42' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('attended_doctor'); } 
                        }
                  }
            ?> 
         </div>
       </div>
    </div>
  </div> <!-- row -->
  

 <div class="row m-b-5" id="available_time" <?php if(empty($form_data['available_time'])){ ?> style="display: none;" <?php } ?> >
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Time</b></div>
         <div class="col-md-7">
            <select name="available_time" class="m_select_btn" id="doctor_time" onchange="return select_a_slot(this.value);">
              <option value="">Select time</option>
              <?php
                if(!empty($doctor_available_time))
                {
                    foreach($doctor_available_time as $doctor_av_time)
                    {
                        ?> 
                        <option <?php if($form_data['time_value']==date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time))){ echo 'selected="selected"';} ?> value="<?php echo $doctor_av_time->id; ?>"> <?php echo date("g:i A", strtotime($doctor_av_time->from_time)).' - '.date("g:i A", strtotime($doctor_av_time->to_time)); ?> </option>
                        
                   <?php  }
                } 

              ?>
            </select>

  </div>
  </div>
    </div>
  </div> <!-- row -->

  <div class="row m-b-5" id="available_slot"  <?php if(empty($form_data['doctor_slot'])){ ?> style="display: none;" <?php } ?> >
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Slot</b></div>
         <div class="col-md-7">
            <select name="doctor_slot" class="m_select_btn" id="doctor_slot">
              <!-- <option value="">Select time</option> -->
              <?php echo $doctor_available_slot; ?>
            </select>

  </div>
  </div>
    </div>
  </div> <!-- row -->

  
  <div class="row m-b-5" id="doctor_avalaible" style="display: none;">
      <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Available Time</b></div>
         <div class="col-md-7">
         <span id="doctor_not_avalaible"></span>

  </div>
  </div>
    </div>
  </div>

    <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Appointment Date</b></div>
         <div class="col-md-7">
           <input type="text" id="appointment_date" name="appointment_date" class="datepicker_app" value="<?php echo $form_data['appointment_date']; ?>" onchange="return get_available_days('',this.value);" />
         </div>
       </div>
    </div>
  </div>


    <div class="row m-b-5" id="appointment_time" <?php  if(empty($form_data['appointment_time']) || $form_data['appointment_time']=='00:00:00'){ ?> style="display: none;" <?php  } ?>>
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Appointment Time </b></div>
         <div class="col-md-7">
           <input type="text" name="appointment_time" id="appointmenttime" class="datepicker3 m_input_default" value="<?php echo $form_data['appointment_time']; ?>" />
         </div>
       </div>
    </div>
  </div> <!-- row -->



    <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Remarks</b></div>
         <div class="col-md-7">
           <textarea name="remarks" id="remarks" class="m_input_default" maxlength="250"><?php echo $form_data['remarks']; ?></textarea>
         </div>
       </div>
    </div>
  </div> <!-- row -->




  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Panel Type</b></div> 
         <div class="col-md-7" id="pannel_type">
           <input type="radio" name="pannel_type" value="0" onclick="set_tpa(0);" <?php if($form_data['pannel_type']==0){ echo 'checked="checked"'; } ?>> Normal &nbsp;
            <input type="radio" name="pannel_type" value="1" onclick="set_tpa(1);" <?php if($form_data['pannel_type']==1){ echo 'checked="checked"'; } ?>> Panel
            <?php if(!empty($form_error)){ echo form_error('pannel_type'); } ?>
         </div>
       </div>
    </div>
  </div> <!-- row -->
<div id="panel_box">
   <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Type</b></div>
        <div class="col-md-7" id="paneltypeid">
                <select name="insurance_type_id" id="insurance_type_id" class="w-150px m_select_btn">
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

                <?php if(in_array('72',$users_data['permission']['action'])) { ?>
            <a  class="btn-new" onclick="insurance_type_modal()"  id="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>

             

            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
              


     <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Name</b></div>
        <div class="col-md-7" id="panelcompanyid">
                <select name="ins_company_id" id="ins_company_id" class="w-150px m_select_btn">
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

                <?php if(in_array('79',$users_data['permission']['action'])) { ?>
            <a  class="btn-new" id="insurance_company_modal" onclick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
           </div>
       </div>
    </div>
  </div> <!-- row -->
          
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Policy No.</b></div>
        <div class="col-md-7">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>TPA ID</b></div>
        <div class="col-md-7">
            <input type="text" name="tpa_id" class="alpha_numeric" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Insurance Amount</b></div>
        <div class="col-md-7">
            <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onKeyPress="return isNumberKey(event);" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
      
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b>Authorization No.</b></div>
        <div class="col-md-7">
            <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" />
       </div>
       </div>
    </div>
  </div> <!-- row -->
</div>  

  
  




  <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-5"><b></b></div>
         <div class="col-md-7">
              <button class="btn-save" id="btnsubmit"><i class="fa fa-floppy-o"></i> Submit</button>
              <a href="<?php echo base_url('appointment');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
         </div>
       </div>
    </div>
  </div> <!-- row -->

    
  
  
  </div> 
  
</div> <!-- mainRow -->
<!-- =============================================== // new row  // ============================ -->



</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>
<!--new css-->
<script type="text/javascript">
  $('.datepicker_app').datepicker({ 
    format: 'dd-mm-yyyy',
   // startDate : new Date(),
    autoclose: true, 
  });
</script>
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script type="text/javascript">
 function more_patient_info()
 {
   $("#patient_info").slideToggle();
 }
 
function father_husband_son()
{
   $("#relation_name").css("display","block");
}

function set_tpa(val)
 { 
    
    if(val==0)
    {
      $('#panel_box').slideUp();
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
      $('#panel_box').slideDown();
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
      $('#ins_authorization_no').removeAttr("readonly", "readonly");
    }
 }

$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();
      if(test==0)
      { 
        $("#hospital_div").hide();
        $("#doctor_div").show();
        //$('#referral_hospital').val();
        $('#referral_hospital').val("");
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
          $("#ref_by_other").css("display","none"); 
          $("#hospital_div").show();
          $('#refered_id').val("");
          $('#ref_other').val("");
          
      }
        
    });
});



$(".txt_firstCap").on('keyup', function(){

   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCap').val(part_val.join(" "));
  
  });

  function get_other(val)
  {
    if(val=='0')
    {
      $("#ref_by_other").css("display","block");
    }
    else
    {
      $("#ref_by_other").css("display","none"); 
     /*$.post('<?php echo base_url().'opd/saved_reffered_doctor_id/' ?>',{"referal_doctor_id":val},function(result){

          
      });*/
    }
  }
function add_spacialization()
{
  var $modal = $('#load_add_specialization_modal_popup');
  $modal.load('<?php echo base_url().'specialization/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

$(document).ready(function(){
var $modal = $('#load_add_modal_popup');

$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'doctors/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
$('#doctor_add_modal_2').on('click', function(){
$modal.load('<?php echo base_url().'doctors/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');

$('#hospital_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'hospital/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});




$(document).ready(function(){
var $modal = $('#load_add_disease_modal_popup');

$('#diseases_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'disease/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});


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

function ShowHideDiv(pay_now) {
        var payment_box = document.getElementById("payment_box"); 
        payment_box.style.display = pay_now.checked ? "block" : "none";
    }

function get_doctor_specilization(specilization_id,branch_id)
{   

    if(typeof branch_id === "undefined" || branch_id === null)
    {
        $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
    }
    else
    {

      $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });

      
    }

    

  }


function get_state(country_id)
{
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#states_id').html(result); 
      } 
    });
    get_city(); 
  }

  function get_city(state_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#citys_id').html(result); 
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

  
 

  function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

  function final_toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

  

  $('.datepicker3').datetimepicker({
      format: 'LT'
  });

          
  
  

  


  
 
  
  
  function discount_vals()
  {
     var timerA = setInterval(function(){  
          payment_calc();
          clearInterval(timerA); 
        }, 1600);
  }

 

  function consultant_charge(vals,pack_val)
  {
         
         var doctor_id = vals;
         get_available_days(doctor_id);
         //return;
         if(doctor_id=='')
         {
            var doctor_id = $('#attended_doctor').val();
         }
         
         if(pack_val=== undefined)
         {
            var pack_val = $('#package_id').val();
         }

         
         $('#total_amount').val();
         $('#discount').val();
         $('#net_amount').val();
         $('#paid_amount').val();
        
        var discount = $('#discount').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>appointment/doctor_rate/", 
            dataType: "json",
            data: 'doctor_id='+doctor_id+'&discount='+discount,
            success: function(result)
            {
               $('#total_amount').val(result.total_amount);
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.net_amount);  
               if(pack_val>0)
               {
                 var package_id = pack_val; 
                 //alert(package_id);
                 var total_amount = $('#total_amount').val(); 
                 var discount = $('#discount').val();
                 var paid_amount = $('#paid_amount').val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>appointment/package_rate/", 
                    dataType: "json",
                    data: 'package_id='+package_id+'&total_amount='+total_amount+'&discount='+discount+'&paid_amount='+paid_amount,
                    success: function(result)
                    {
                       $('#total_amount').val(result.total_amount);
                       $('#net_amount').val(result.net_amount); 
                       $('#discount').val(result.discount); 
                       $('#paid_amount').val(result.paid_amount);
                       $('#balance').val(result.balance);  
                    } 
                  });
               }
            }



          });
  }
   

  function get_available_days(doctor_id,date)
  {
      if(date!='')
      {
        var doctor_id = $('#attended_doctor').val();
      }
      if(doctor_id!='')
      {
        var booking_date = $('#appointment_date').val();
      }
      
      $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>appointment/get_doctor_available_days/", 
            dataType: "json",
            data: 'doctor_id='+doctor_id+'&booking_date='+booking_date,
            success: function(result)
            {
               
               if(result==1)
               {
                    
                    $.ajax({
                      type: "POST",
                      url: "<?php echo base_url(); ?>general/get_doctor_available_time/",
                      data: 'doctor_id='+doctor_id+'&booking_date='+booking_date, 
                      success: function(result)
                      {
                        $("#appointmenttime").val('');
                        $("#available_time").css("display", "block");
                        $("#doctor_avalaible").css("display", "none");
                        $("#appointment_time").css("display","none");
                        $('#doctor_time').html(result); 
                        
                      } 
                    });
                   
                    
               }
               else if(result==0)
               {
                    $("#appointmenttime").val('');
                    $("#appointment_time").css("display","none");
                    $("#available_time").css("display", "none");
                    $("#available_slot").css("display", "none");
                    $("#doctor_avalaible").css("display", "block");
                    
                    


                    $('#doctor_not_avalaible').html('<p style="color:red;">Doctor not available.</p>'); 
                }
                else if(result==2)
                {
                  $("#doctor_avalaible").css("display", "none");
                  $("#available_time").css("display", "none");
                  $("#available_slot").css("display", "none");
                  $("#appointment_time").css("display","block");
                  //available_time
                  
                } 

            }

          });
  }


  function select_a_slot(vals)
  {      
        var time_id = vals;
        var doctor_id = $('#attended_doctor').val();
        var booking_date = $('#appointment_date').val();
        $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>general/doctor_slot/", 
            data: 'doctor_id='+doctor_id+'&time_id='+time_id+'&booking_date='+booking_date,
            success: function(result)
            {
                
                $("#available_slot").css("display", "block");
                $('#doctor_slot').html(result); 
                //$('#doctor_slot').html(result); 
            }



          });
  }


 

  function payment_calc()
  {
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>appointment/calculate_payment/", 
            dataType: "json",
            data: 'total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance,
            success: function(result)
            {
               $('#total_amount').val(result.total_amount); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#paid_amount').val(result.paid_amount); 
               $('#balance').val(result.balance); 
               
            } 
          });
  }

   function find_gender(id){ 
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }


$('#branch_id').change(function(){  
      var branch_id = $(this).val();

      $.ajax({url: "<?php echo base_url(); ?>appointment/get_branch_data/"+branch_id, 
        success: function(result)
        {
           load_values(result);
           load_simulation_values(branch_id);
           load_referral_doctor_values(branch_id);
           load_specialization_values(branch_id);
           load_attended_doctor_values(branch_id);
           load_source_from_values(branch_id);
           load_diseases_values(branch_id);
           load_panel_type_values(branch_id);
           load_panel_company_values(branch_id);

        } 
      }); 
  });

  function load_values(jdata)
  {
     var obj = JSON.parse(jdata);
     $('#patient_code').val(obj.patient_code);
     $('#appointment_code').val(obj.appointment_code);
  };

  function load_simulation_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_simulation_data/"+branch_id, 
    success: function(result)
    {
      $('#simulation_id').html(result); 
    } 
  });
  }

  function load_referral_doctor_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_referral_doctor_data/"+branch_id, 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
  });
  }

  function load_specialization_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_specialization_data/"+branch_id, 
    success: function(result)
    {
      $('#specilizationid').html(result); 
    } 
  });
  }

  function load_source_from_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_source_from_data/"+branch_id, 
    success: function(result)
    {
      $('#patient_source_id').html(result); 
    } 
  });
  }

  function load_diseases_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_diseases_data/"+branch_id, 
    success: function(result)
    {
      $('#diseaseid').html(result); 
    } 
  });
  }

  function load_panel_type_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_panel_type_data/"+branch_id, 
    success: function(result)
    {
      $('#paneltypeid').html(result); 
    } 
  });
  }

  function load_panel_company_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_panel_company_data/"+branch_id, 
    success: function(result)
    {
      $('#panelcompanyid').html(result); 
    } 
  });
  }

  function load_attended_doctor_values(branch_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>appointment/get_attended_doctor_data/"+branch_id, 
    success: function(result)
    {
      $('#attended_doctor').html(result); 
    } 
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

$(document).ready(function(){
var $modal = $('#load_add_emp_type_modal_popup');

$('#patient_source_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient_source/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});


});


$(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

$(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e) {
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_specialization_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputfocus').focus();
  });
});
$("button[data-number=4]").click(function(){
    $('#specilization_appointment_row_count').modal('hide');
   /* $(this).hide();*/
});
</script> 
<script type="text/javascript">
$(document).ready(function(){
<?php 
if(empty($_POST))
{
if((empty($specialization_list)) || (empty($attended_doctor_list)) || (empty($simulation_list)))
{
  
?>  

 
  $('#specilization_appointment_row_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 
}

}
?>

});
</script>
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 
    <div id="specilization_appointment_row_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          <?php if(empty($specialization_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Specialization is required.</span></p><?php } ?>
          <?php if(empty($attended_doctor_list)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Consultant is required.</span></p>
          <?php } ?>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->

<div id="load_add_specialization_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>

<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div><!-- container-fluid -->
<div id="load_add_emp_type_modal_popup" class="modal fade top-5em" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_insurance_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>
<script>

  $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#appointment_form').submit();
  })
  
</script>

    
<script>
//neha 14-2-2019
 function get_patient_detail_by_mobile() {
  var val = $('#mobile_no').val();
   if(val.length==10)
   {
    
    $.ajax({
      url: "<?php echo site_url('appointment/get_patient_detail_no_mobile'); ?>/"+val,
      type: 'POST',
      async : false,
      success: function(datas) {
       var data = $.parseJSON(datas);
        if(data.st==1)
        { 
          // Add response in Modal body
         $('.modal-body').html(data.patient_list);

      // Display Modal
        $('#patient_proceed').modal('show'); 
        }
       }
    });
    return false;
  }
};
 //neha 14-2-2019
  //neha 25-2-2019
    $(document).ready(function(){
       
        $("#proceed").click(function(){

            if($('input[name="patient_id"]:checked').length == 0){

                 alert("Please select a patient");
                  return false;

            }
            else
            {
              var action_url = '<?php echo site_url('appointment/add/'); ?>'
              var radioValue = $("input[name='patient_id']:checked").val();
               $.ajax({
                    url: "<?php echo site_url('appointment/get_patient_detail_byid'); ?>/"+radioValue,
                    type: 'POST',
                    async : false,
                   success: function(datas) {
                   var data = $.parseJSON(datas);

                   if(data.st==1)
                  { 

                    $('#patient_id').val(data.patient_detail.id);
                    $("#registered").attr('checked', true);
                    $('#new').attr('checked', false);
                    $('#patient_code').val(data.patient_detail.patient_code);
                    //$("#simulation_id option:selected").val(data.patient_detail.simulation_id);
                    $('#patient_name').val(data.patient_detail.patient_name);
                    //$("#relation_simulation_id option:selected").val(data.patient_detail.relation_simulation_id);
                    $('#relation_name').val(data.patient_detail.relation_name);
                    $('#mobile_no').val(data.patient_detail.mobile_no);
                    //$('#gender_'+data.patient_detail.gender).attr('checked', true);
                    $('#gender_'+data.patient_detail.gender).attr('checked', 'checked');
                    $('#age_y').val(data.patient_detail.age_y);
                    $('#age_m').val(data.patient_detail.age_m);
                    $('#age_d').val(data.patient_detail.age_d);
                    $('#age_h').val(data.patient_detail.age_h);
                    $('#appointment_form').attr('action', action_url+data.patient_detail.id); 
                    
                    $("#simulation_id option[value="+data.patient_detail.simulation_id+"]").attr('selected', 'selected');
                    $("#relation_simulation_id option[value="+data.patient_detail.relation_simulation_id+"]").attr('selected', 'selected');
                    $("#relation_type_id option[value="+data.patient_detail.relation_type+"]").attr('selected', 'selected');
                    find_gender(data.patient_detail.simulation_id);
                    
                  //find_gender(data.patient_detail.simulation_id);
                  }
                }

             });

          }

        });

    });

     //neha 25-2-2019
</script>

 <div id="patient_proceed" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Patient Already Registered!. Do You Want to procced?</h4></div>
           <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="proceed">Continue</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 