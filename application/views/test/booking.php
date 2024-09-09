<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(8);  /* old section id: 3*/
$child_branch_list = $this->session->userdata('sub_branches_data');
$profile_session_list = $this->session->userdata('set_profile');
//echo "<pre>"; print_r($profile_session_list);die;
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- boot210strap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<style>
.row_hide{display:none;}
</style>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
var save_method; 
var table; 
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('test/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
 
 
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

<body onLoad="payment_calc(1);">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="path-booking">
<form id="booking_form" action="<?php echo current_url(); //base_url('test/booking'); ?>" method="post">
<input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />

<input type="hidden" name="invoice_id" value="<?php echo $_GET['invoice_id']; ?>" />
<!--<script>get_payment_mode(this.value)</script> -->
<input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" />
<input type="hidden" name="ipd_id" id="ipd_id" value="<?php echo $form_data['ipd_id']; ?>" />

<input type="hidden" name="opd_id" id="opd_id" value="" />

  <!-- /////////////////////// top section /////////////////////////// -->
<div class="row">
  <div class="col-md-4">
    <div class="row m-b-5">
      <div class="col-sm-4">
    
        <label><input type="radio" name="" <?php if(empty($form_data['patient_id'])){ echo 'checked=""'; } ?>> New Patient</label>
      </div>  
      <div class="col-sm-6">
        <label><input type="radio" name="patient" onclick="window.location.href='<?php echo base_url("patient"); ?>'" <?php if(!empty($form_data['patient_id'])){ echo 'checked=""'; } ?> > Registered Patient</label>
      </div>
      <?php if(in_array('734',$users_data['permission']['action']))
        { ?>
      <div class="col-sm-4">
        <label><input type="radio" name="patient" onclick="window.location.href='<?php echo base_url("ipd_booking"); ?>'" <?php if(!empty($form_data['ipd_id'])){ echo 'checked=""'; } ?> > IPD Patient</label>
      </div>  
      <?php 
      } 
      if(empty($form_data['data_id']) && in_array('85',$users_data['permission']['section']))
      {


      ?>
      <div class="col-sm-8">
      <input type="text" name="opd_patient" id="opd_patient" placeholder="Search OPD Patient" class="opd_patient"> <label></label>
      </div>
      <?php } ?>
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Branch</label>
      </div>  
      <div class="col-sm-8">
        <select name="branch_id" class="m_input_default" id="branch_id" onchange="set_branch(this.value);">
                  <option <?php if($form_data['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?>  value="<?php echo $users_data['parent_id']; ?>">Self</option>  
                  <?php
                  /*foreach($child_branch_list as $child_branch)
                  {
                  ?>
                  <option <?php if($form_data['branch_id']==$child_branch['id']){ echo 'selected="selected"'; } ?> value="<?php echo $child_branch['id']; ?>"><?php echo $child_branch['branch_name']; ?></option>
                  <?php   
                  }*/
                  ?>
               </select>
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
      </div>  
      <div class="col-sm-8">
        <input type="text" id="patient_code" class="m_input_default" readonly="" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" /> 
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Lab Ref. No.</label>
      </div>  
      <div class="col-sm-8">
        <input type="text" readonly="" class="m_input_default" name="lab_reg_no" value="<?php echo $form_data['lab_reg_no']; ?>"/> 
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4 pr-0">
        <label>Patient Name <span class="star">*</span></label>
      </div>  
      <div class="col-sm-8">
        <select class="mr m_mr" name="simulation_id" id="simulation_id" onChange="find_gender(this.value)"> 
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
              <input type="text" name="patient_name"  id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="m_name txt_firstCap" style="width: 134px;margin-left: 5px;" autofocus=""/>
               
                 <?php if(in_array('65',$users_data['permission']['action'])) { ?>
                    <a title="Add Simulation" href="javascript:void(0)" onClick="simulation_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
               <?php } ?>
               <?php 
                       if(!empty($form_error)){ echo form_error('patient_name'); }
                       if(!empty($form_error)){ echo form_error('simulation_id'); } 

                       
                ?>
      </div>  
    </div>

    <!-- new code by mamta -->
    <div class="row m-b-5">
             <div class="col-sm-4 pr-0">
                 <label>
                  <select name="relation_type" id="relation_type_id" class="w-90px" onchange="father_husband_son(this.value);">
                  <?php foreach($gardian_relation_list as $gardian_list) 
                  {?>
                  <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
                  <?php }?>
                  </select>

                 </label>
          </div>
          <div class="col-sm-8">
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
                  else{
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
      <div class="col-sm-4">
        <label>Mobile No. 
          <?php if(!empty($field_list)){
                if($field_list[1]['mandatory_field_id']==38 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                              <span class="star">*</span>
                         <?php 
                         }
                    } 
               ?></label>
      </div>  
      <div class="col-sm-8">
        <input type="text" name="country_code" value="+91" readonly="" class="country_code m_c_code" placeholder="+91"> 
              <input type="text" name="mobile_no"  id="mobile_no" data-toggle="tooltip"  title="Allow only numeric." class="number m_number tooltip-text numeric"  value="<?php echo $form_data['mobile_no']; ?>" maxlength="10"   onkeyup="get_patient_detail_by_mobile();" autocomplete="off">
               
              <?php  if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='38' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile_no'); } 
                         }
                    }
               ?>
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Gender  <span class="star">*</span></label>
      </div>  
      <div class="col-sm-8">
        <div id="gender">
          <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
             <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php 
                              if(!empty($form_error)){ echo form_error('gender'); } 
                    ?>
        </div>
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Age
            <?php if(!empty($field_list)){
                  if($field_list[0]['mandatory_field_id']==37 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                              <span class="star">*</span>
                         <?php 
                         }
                    } 
               ?></label>
      </div>  
      <div class="col-sm-8">
        <input type="text" name="age_y"  id="age_y" class="input-tiny m_tiny2 numeric" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
              <input type="text" name="age_m"  id="age_m" class="input-tiny m_tiny2 numeric"  maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d"  class="input-tiny m_tiny2 numeric"  maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
              <input type="text" name="age_h" id="age_h"  class="input-tiny m_tiny2 numeric"  maxlength="2" value="<?php echo $form_data['age_h']; ?>"> H
              <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='37' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); } 
                         }
                    }
               ?>
      </div>  
    </div>
    
    <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Diseases</b></div>
        <div class="col-md-8" id="diseaseid">
           <select name="diseases" id="disease_id" class="w-150px m_select_btn" onChange="return get_diseases(this.value);">
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
            <a  class="btn-new" id="diseases_add_modal"><i class="fa fa-plus"></i> New</a>
           
        </div>
       </div>
    </div>
  </div>
  
  <div class="row m-b-5">
     <div class="col-md-12">
       <div class="row">
         <div class="col-md-4"><b>Reminder Days</b></div>
        <div class="col-md-8" id="">
            <input type="text" class="m_input_default" placeholder="Enter Numeric Only" id="reminder_days" name="reminder_days" value="<?php echo $form_data['reminder_days']; ?>"/> 
           
        </div>
       </div>
    </div>
  </div>
  

  
  </div> <!-- 4 -->


 


  <div class="col-md-4">
    
<?php //if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
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

  <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?>>
      <div class="col-sm-4">
        <label>Referred By Doctor <span class="star">*</span></label>
      </div>  
      <div class="col-sm-8">
        <select name="referral_doctor" class="m_select_btn" id="refered_id" onChange="check_doctor_plan_type(this.value);check_doctor_type(this.value); get_profile_test(); get_others(this.value);" >
                <option value="">Select Referred By</option>
                <?php
                if(!empty($referal_doctor_list))
                {
                  foreach($referal_doctor_list as $referal_doctor)
                  {
                    
                      if(empty($assigned_doctor))
                      {
                          ?>
                          <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                          <?php
                      }
                      else
                      {
                          if(in_array($referal_doctor->id, $assigned_doctor))
                          {
                        ?>
                          <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                            <?php
                          } 
                      }
                    /*?>
                      <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                    <?php*/
                  }

                  ?>

                        <option value="0" <?php if(!empty($form_data['ref_by_other'])){ if($form_data['referral_doctor']=='0'){ echo "selected"; }} ?>> Others </option>
                        <?php
                }
                ?>
              </select>  
               <?php if(in_array('122',$users_data['permission']['action'])) {
               ?>
              <a title="Add Referral Doctor" href="javascript:void(0)" onClick="doctor_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
              <?php
              }
              ?>
              <?php if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>
      </div>  
    </div>

    <div class="row m-b-5" id="ref_by_other" <?php if(!empty($form_data['ref_by_other']) && $form_data['referral_doctor']=='0'){ }else{ ?> style="display: none;" <?php } ?>>
    
           <div class="col-md-4"><b> Other </b></div>
           <div class="col-xs-8">
            <input type="text" name="ref_by_other" id="ref_other" value="<?php echo $form_data['ref_by_other']; ?>" >
              <?php if(!empty($form_error)){ echo form_error('ref_by_other'); } ?>
         </div>
       </div>
       <!-- row -->

    <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
      <div class="col-sm-4">
        <label>Referred By Hospital <span class="star">*</span></label>
      </div>  
      <div class="col-sm-8">
        <select name="referral_hospital" class="m_select_btn" id="referral_hospital" style="width: 146px;">
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
              <a title="Add Hospital" href="javascript:void(0)" onClick="hospital_add_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a> 
              <!-- <a  class="btn-new" id="hospital_add_modal"><i class="fa fa-plus"></i> New</a> -->
              
              <?php if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
      </div>  
    </div>
    <?php //}
      ?>
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Doctor Name</label>
      </div>  
      <div class="col-sm-8">
        <select name="attended_doctor" class="m_input_default" id="attended_doctor">
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
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4 p-r-0">
        <label>Sample Collected By</label>
      </div>  
      <div class="col-sm-8">
        <select name="sample_collected_by" class="m_input_default SampleCollectedBy">
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
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Staff Reference</label>
      </div>  
      <div class="col-sm-8">
        <select name="staff_refrenace_id" class="m_input_default staff_refrenace_id">
                <option value=""> Select Reference </option>
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
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Collection Center</label>
      </div>  
      <div class="col-sm-8">
        <select name="collection_center" id="collection_center" class="m_input_default" style="width: 146px;">
                <option value=""> Select Collection Center </option>
                <?php
                if(!empty($collection_center_list))
                {
                  foreach($collection_center_list as $collection_center)
                  {
                    ?>
                     <option <?php if($form_data['collection_center']==$collection_center->id){ echo 'selected="selected"'; } ?> value="<?php echo $collection_center->id; ?>"><?php echo $collection_center->source; ?></option>
                    <?php
                  }
                }
                ?> 
              </select>
              
              <a title="Add Collection Center" href="javascript:void(0)" onClick="collection_center_add_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a> 
      </div>  
    </div>
    
    <?php if(in_array('411',$users_data['permission']['section']))
        { ?>
    <div class="row m-b-5">
      <div class="col-sm-4"><b>Patient Category</b></div>  
      <div class="col-sm-8">
                              <select name="patient_category" id="patient_category" style="width: 146px;" m_select_btn">
                                  <option value="">Select Category</option>
                                  <?php
                                  if(!empty($patient_category))
                                  {
                                    foreach($patient_category as $patientcategory)
                                    {
                                      ?>
                                        <option <?php if($form_data['patient_category']==$patientcategory->id){ echo 'selected="selected"'; } ?> value="<?php echo $patientcategory->id; ?>"><?php echo $patientcategory->patient_category; ?></option>
                                        
                                      <?php
                                    }
                                  }
                                  ?>
                            </select> 
                            <?php if(in_array('2486',$users_data['permission']['action'])) {
                ?><a title="Add Patient Category" class="btn-new" id="patient_category_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
               </div>  
    </div>
          <?php } else { ?> <input type="hidden" id="patient_category" name="patient_category" value="0"> <?php } ?>
          <?php if(in_array('412',$users_data['permission']['section']))
        { ?>
         <div class="row m-b-5">
      <div class="col-sm-4"><b>Authorize Person</b></div>  
      <div class="col-sm-8">
                              <select name="authorize_person" id="authorize_person" style="width: 146px;" class="m_select_btn">
                                  <option value="">Select Authorize Person</option>
                                  <?php
                                  if(!empty($authrize_person_list))
                                  {
                                    foreach($authrize_person_list as $authrizelist)
                                    {
                                      ?>
                                        <option <?php if($form_data['authorize_person']==$authrizelist->id){ echo 'selected="selected"'; } ?> value="<?php echo $authrizelist->id; ?>"><?php echo $authrizelist->authorize_person; ?></option>
                                        
                                      <?php
                                    }
                                  }
                                  ?>
                            </select> 
                            <?php if(in_array('2493',$users_data['permission']['action'])) {
                ?><a title="Add authorize person" class="btn-new" id="authorize_person_add_modal"><i class="fa fa-plus"></i> New</a>
                <?php } ?>
              </div>  
    </div>
    <?php } else { ?> <input type="hidden" id="authorize_person" name="authorize_person" value="0"> <?php } ?>
    
    <!-- <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Booking Date</label>
      </div>  
      <div class="col-sm-8">
        <input type="text" name="booking_date" class="datepicker m_input_default" value="< ?php echo $form_data['booking_date']; ?>" /> 
      </div>  
    </div> -->
   
    <div class="row m-b-5">
            <div class="col-sm-4"><b>Booking Date & Time</b></div>
            <div class="col-sm-8">
                <input type="text" name="booking_date" class="w-130px datepicker m_input_default" value="<?php echo  $form_data['booking_date']; ?>" >
                <input type="text" name="booking_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['booking_time']; ?>">
              
            </div>
        </div>
     <div class="row m-b-5">
            <div class="col-sm-4"><b>Next Appointment</b></div>
            <div class="col-sm-8">
                <input type="text" name="next_app_date" class="datepicker m_input_default" value="<?php echo  $form_data['next_app_date']; ?>" > 
            </div>
        </div>
        <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Tube No. <?php if(!empty($field_list)){
                if($field_list[2]['mandatory_field_id']==39 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                              <span class="star">*</span>
                         <?php 
                         }
                    } 
               ?></label>
      </div>  
      <div class="col-sm-8">
        <div id="tube_no">
        <input type="text" class="m_input_default" name="tube_no" value="<?php echo $form_data['tube_no']; ?>"/> 
        
            <?php  if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='39' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('tube_no'); } 
                         }
                    }
               ?>  
        </div>
      </div>  
    </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Remarks <?php if(!empty($field_list)){
                if($field_list[3]['mandatory_field_id']==40 && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){?>         
                              <span class="star">*</span>
                         <?php 
                         }
                    } 
               ?></label>
               <br>
               <select  class="w-100px m_select_btn" onChange="return get_remarks(this.value);">
              <option value="">Select Remarks</option>
              <?php
              if(!empty($remarks_list))
              {
                foreach($remarks_list as $remarkslist)
                {
                  ?>
                    <option value="<?php echo $remarkslist->id; ?>"><?php echo $remarkslist->remarks_title; ?></option>
                    
                  <?php
                }
              }
              ?>
            </select>
      </div>  
      <div class="col-sm-8">
        <textarea name="remarks" id="remarks" class="remarks m_input_default" maxlength="250"><?php echo $form_data['remarks']; ?></textarea>
         <?php  if(!empty($field_list)){
                         if($field_list[3]['mandatory_field_id']=='40' && $field_list[3]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('remarks'); } 
                         }
                    }
               ?>
      </div>  
    </div>

    <div class="row m-b-5">
      <div class="col-sm-4"></div>  
      <div class="col-sm-8"></div>  
    </div>
    
  </div> <!-- 4 -->

   <div class="col-md-4">
    
    <div class="row m-b-5">
    <div class="col-md-12">
       <div class="row">
         <div class="col-md-4" style="text-align: left;"> <a href="javascript:void(0);" class="show_hide_more" data-content="toggle-text" onclick="more_patient_info()">More Info</a></div>
         <div class="col-md-8" >
           
         </div>
       </div>
    </div>
  </div> <!-- row -->

<div class="more_content" id="patient_info" <?php if($form_data['pannel_type']==1){  }else{ ?> style="display: none;" <?php } ?>> 
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Email</label>
      </div>  
      <div class="col-sm-8">
        <input type="text" class="m_input_default" id="patient_email"  name="patient_email" value="<?php echo $form_data['patient_email']; ?>"/> 
             <?php
              if(!empty($form_error)){ echo form_error('patient_email'); } 
             ?>
      </div>  
    </div>

    <div class="row m-b-5">
      <div class="col-xs-4">
         <label>Address 1</label>
      </div>
      <div class="col-xs-8">
         <input type="text" name="address" id="address1" class="address" value="<?php echo $form_data['address'];?>"/>
         <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
      </div>
    </div>

   <div class="row m-b-5">
    <div class="col-xs-4">
       <label>Address 2</label>
    </div>
    <div class="col-xs-8">
       <input type="text"  name="address_second" id="address2" class="address" value="<?php echo $form_data['address_second'];?>"/>
      
    </div>
  </div>
   <div class="row m-b-5">
    <div class="col-xs-4">
       <label>Address 3</label>
    </div>
    <div class="col-xs-8">
       <input type="text" name="address_third" id="address3" class="address" value="<?php echo $form_data['address_third'];?>"/>
       
    </div>
  </div>
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Country</label>
      </div>  
      <div class="col-sm-8">
        <select name="country_id" class="m_input_default" id="country_id" onChange="return get_state(this.value);">
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
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>State</label>
      </div>  
      <div class="col-sm-8">
        <select name="state_id" class="m_input_default" id="states_id" onChange="return get_city(this.value)">
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
    
    <div class="row m-b-5">
      <div class="col-sm-4">
        <label>City</label>
      </div>  
      <div class="col-sm-8">
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


      <div class="row m-b-5">
      <div class="col-sm-4">
        <label>Form F</label>
 <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>DEFINITION of 'SEC Form F-1' SEC Form F-1 is a filing with the Securities and Exchange Commission (SEC) required for the registration of certain securities by foreign issuers. SEC Form F-1 is required to register securities issued by foreign issuers for which no other specialized form exists or is authorized .</span>
               </a>
               </sup>
      </div>  
      <div class="col-sm-8">
        <div id="form_f">
          <input type="radio" name="form_f" value="1" <?php if($form_data['form_f']==1){ echo 'checked="checked"'; } ?>> Yes &nbsp;
            <input type="radio" name="form_f" value="2" <?php if($form_data['form_f']==2){ echo 'checked="checked"'; } ?>> No
             
        </div>
      </div>  
    </div>

    

    <div class="row m-b-5">
    <div class="col-sm-4">
    <label>Panel Type  
     <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A doctor within a given area available for consultation by patients insured under the National Health Insurance Scheme It has two type <br> Normal: Having no policy. <br>Panel:Having policy.</span>
     </a>
    </sup>
    </label>
    </div>  
    <div class="col-sm-8">
    <div id="">
    <input type="radio" name="pannel_type" class="pannel_type_t" id="pannel" value="0" onclick="set_tpa(0);check_panel_type(0)" <?php if($form_data['pannel_type']==0){ echo 'checked="checked"'; } ?> > Normal &nbsp;
    <input type="radio" name="pannel_type" value="1" id="pannel_n" onclick="set_tpa(1);check_panel_type(1)" <?php if($form_data['pannel_type']==1){ echo 'checked="checked"'; } ?> class="pannel_type_t"> Panel
    <?php //if(!empty($form_error)){ //echo form_error('pannel_type'); } ?>
    </div>
    </div>  
    </div>
    <div id="pannel_type" <?php if($form_data['pannel_type']==0){echo 'style="display:none"';} ?> class="">
        <div class="row m-b-5">
        <div class="col-sm-4">
        <label>Type </label>
        </div> 
        <div class="col-md-8">
                <select name="insurance_type_id" id="insurance_type_id" class="w-150px m_select_btn" onchange="check_panel_type(this.value); ">
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
            <a title="Add Insurance Type" class="btn-new" onclick="insurance_type_modal()"  id="insurance_type_modal()"><i class="fa fa-plus"></i> New</a>
            <?php if(!empty($form_error)){ echo form_error('insurance_type_id'); } ?>

             

            <?php } ?>
           </div>
       </div>
   
  <div class="row m-b-5">
    <div class="col-sm-4">
      <label>Name </label>
      </div> 
        <div class="col-md-8">
                <select name="ins_company_id" id="ins_company_id" class="w-150px m_select_btn" onchange="check_panel_type(this.value);get_profile_test(this.value);">
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
            <a title="Add Insurance Company" class="btn-new" id="insurance_company_modal" onclick="insurance_company_modal()"><i class="fa fa-plus"></i> New</a>
            <?php } ?>
             <?php if(!empty($form_error)){ echo form_error('ins_company_id'); } ?>
           </div>
       </div>
  
          
      
  <div class="row m-b-5">
      <div class="col-sm-4">
      <label>Policy No.</label>
      </div>
        <div class="col-md-8">
            <input type="text" name="polocy_no" class="alpha_numeric" id="polocy_no" value="<?php echo $form_data['polocy_no']; ?>" maxlength="20" onkeypress="check_panel_type(this.value);"/>
                <?php if(!empty($form_error)){ echo form_error('polocy_no'); } ?>
       </div>
       </div>

      
  <div class="row m-b-5">
      <div class="col-sm-4">
      <label>TPA ID </label>
      </div>
        <div class="col-md-8">
            <input type="text" name="tpa_id" class="alpha_numeric" id="tpa_id" value="<?php echo $form_data['tpa_id']; ?>" onkeypress="check_panel_type(this.value);"/>
            <?php if(!empty($form_error)){ echo form_error('tpa_id'); } ?>
       </div>
       </div>
     
      <div class="row m-b-5">
        <div class="col-sm-4">
        <label>Insurance Amount.</label>
        </div>
        <div class="col-md-8">
        <input type="text" name="ins_amount" class="price_float" id="ins_amount" value="<?php echo $form_data['ins_amount']; ?>" onkeypress="check_panel_type(this.value);" />
        <?php if(!empty($form_error)){ echo form_error('ins_amount'); } ?>
        </div>
      </div>
    


      <div class="row m-b-5">
        <div class="col-sm-4">
        <label>Authorization No.</label>
        </div>
        <div class="col-md-8">
        <input type="text" name="ins_authorization_no" class="alpha_numeric" id="ins_authorization_no" value="<?php echo $form_data['ins_authorization_no']; ?>" onkeypress="check_panel_type(this.value);"/>
        <?php if(!empty($form_error)){ echo form_error('ins_authorization_no'); } ?>
        </div>
      </div>
      </div>
    
 
    </div>
    
  </div> <!-- 4 -->
</div> <!-- mainRow -->


  <!-- ////////////////////////// ends top section ///////////////////////////// -->
   <!-- box -->



  <div class="row">
     <div class="col-md-12"> 
       <!-- <div id="profile_price" style="/*float:right; margin-right:105px;*/ text-align:right; margin-right: 8%; font-weight:bold;">Rs. <?php echo $form_data['profile_price']; ?></div>-->
     </div>
	 <div class="col-md-12">
         <div class="col-md-4 p-l-0">
             <label>Department </label>
             <select name="dept_id" id="dept_id" class="m_input_default">
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

              <?php if(!empty($form_error)){ echo form_error('dept_id'); } ?>
         </div>
        <div class="col-md-8">
            
            <div style="display: flex;justify-content: space-between;position: relative;left: -32px;width: calc(100% + 18px);">
        <div class="bx-left">
           <a title="Add Test" class="btn-new" onClick="send_test(event);">
          <i class="fa fa-plus"></i> Add
        </a>
        </div>
        <div class="bx-center">
            <label>Search </label>
             <input type="text" class="m_input_default" name="test_search" id="test_search" value="" placeholder="Search Test"> 
        </div>
        <div class="bx-right">
            <?php
              $profile = $this->session->userdata('set_profile'); 
              $profile_print_status = get_profile_print_status('test_booking');
              ?>
              <label>Profile </label>
             <select name="profile_id" class="m_input_default" id="profile_id" onChange="get_profile_test();" style="width: 171px !important;">
                <option value="">Select Profile</option>
                      <?php 
                        if(!empty($profile_list))
                       {
                          foreach($profile_list as $profile)
                          { 
                              $profile_select = "";
                              if($profile->id==$form_data['profile_id'])
                              {
                                  $profile_select = "selected='selected'";
                              }
$profile_status = $profile_print_status->profile_status;
                              $print_status = $profile_print_status->print_status;
                              if($profile_status==1 && $print_status==1)
                              {
                                if(!empty($profile->print_name))
                                {
                                  $profile_name = $profile->profile_name.' ('.$profile->print_name.')';
                                }
                                else
                                {
                                  $profile_name = $profile->profile_name;
                                }
                              }
                              elseif($profile_status==1 && $print_status==0)
                              {
                                $profile_name = $profile->profile_name;
                              }
                              elseif($profile_status==0 && $print_status==1)
                              {
                                if(!empty($profile->print_name))
                                {
                                  $profile_name = $profile->print_name;
                                }
                                else
                                {
                                  $profile_name = $profile->profile_name;
                                }
                                
                              }
                              elseif($profile_status==0 && $print_status==0)
                              {
                                $profile_name = $profile->profile_name;
                              }
                              //echo "<pre>"; print_r($profile_print_status); exit;
                              
                              echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile_name.'</option>';
                              //echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile->profile_name.'</option>';
                          }
                       } 
                      ?>
              </select> 
              &nbsp;&nbsp;<input type="text" name="rate" value="<?php echo $form_data['profile_price']; ?>" class="input-tiny m_tiny2 numeric" id="profile_price" style="width: 58px !important;"/>
             &nbsp;&nbsp;<a title="Add Profile" class="btn-new" onClick="add_profile();">
              <i class="fa fa-plus"></i> Add
            </a> 
        </div>
    </div>
        
    </div>
        
         
        


     </div>
     
      
  </div>

  <div class="box">
    <div class="boxleft">
      <select size="9" class="dropdown-box" name="dept_parent_test"  id="dept_parent_test" tabindex="14" >
         <?php
            if(isset($head_list) && !empty($head_list))
            {
              foreach($head_list as $head)
              {
                 echo '<option value="'.$head->id.'">'.$head->test_heads.'</option>';
              }
            }
         ?>
      </select>
    </div>     <!-- boxleft --> 
    <div class="boxright"> 
    
        <table class="pb-tbl4" id="test_list">
            <thead class="bg-theme">
            <tr>
              <th width="60" align="center">Select</th>
              <th>Test ID</th>
              <th>Test Name</th>
              <th>Sample Type</th>
              <th>Patient Rate</th>
            </tr>
            </thead>
            <tr>  
              <td colspan="5">
                <div class="text-danger p-l-half">Test not available</div>
              </td>
            </tr>            
        </table>       
    </div>  <!-- boxright --> 





     <!-- boxbtns -->

  </div> <!-- box -->







  <div class="box">
    
    <legend class="bg-theme"><b>Booking Test Detail</b></legend>
    <div class="bk-tst-dtl">
      <table class="pb-tbl5" id="test_select">
        <thead class="bg-theme">
        <tr>
          <th width="40" align="center"> <input type="checkbox" class="" name="select_all" id="select_all" onClick="final_toggle(this);"/> </th>
          <th>Test ID</th>
          <th>Test / Profile Name</th>
          <th>Sample Type</th>
          <th>Patient Rate</th>
        </tr>
        </thead>
        <?php
        $profile_data = $this->session->userdata('set_profile');
        //echo "<pre>";print_r($profile_data);die;
        $booked_arr = $this->session->userdata('book_test');
        $total_test = count($booked_arr);
        $profile_row = "";
        $p_order = 1;
        $profile_order = 1;
        if(isset($profile_data) && !empty($profile_data))
        {
          $pi = 1;
          foreach($profile_data as $profile_dat)
          {
             $profile_row .= '<tr>
                              <td width="40" align="center">
                                 <input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$profile_dat['id'].'" >
                              </td>
                              <td>'.$profile_dat['id'].'</td>
                              <td>'.$profile_dat['name'].'</td>
                              <td></td>
                              <td>'.$profile_dat['price'].'</td>
                          </tr>';
          
           if($total_test>1)
            {
              $profile_order = $total_test-$profile_dat['order'];
              if($profile_order==0)
              {
                $profile_order = '1';
              }
            }
            $pi++;
          }                 
        }
        if(isset($booked_test_list) && !empty($booked_test_list))
        {
          $i = 1;          
          foreach($booked_test_list as $booked_test)
          {  
            ?>
              <tr>
                  <td width="40" align="center">
                     <input type="checkbox" name="test_id[]" class="booked_checkbox" value="<?php echo $booked_test->id; ?>" >
                  </td>
                  <td><?php echo $booked_test->test_code; ?></td>
                  <td><?php echo $booked_test->test_name; ?></td>
                  <td><?php if(!empty($booked_arr[$booked_test->id]['sample_type_id'])) { echo $booked_arr[$booked_test->id]['sample_type_id']; } ?></td>
                  <td><?php echo $booked_arr[$booked_test->id]['price']; ?></td>
              </tr>       
            <?php
            if(isset($profile_data) && !empty($profile_data))
            { 
               if($i==1)
               {
                 echo $profile_row;
               }
            }
          $i++;  
          }
        }
        else
        {
          if(isset($profile_data) && !empty($profile_data))
          {
            echo $profile_row;
          }
          else
          {
             ?> 
            <tr>
              <td colspan="5">
                 <div class="text-danger p-l-half">Test not added.</div>
              </td> 
            </tr>
            <?php
          } 
        }
        ?>
        <tr>
            <td colspan="5"><?php if(!empty($form_error)){ echo form_error('test'); } ?></td>
        </tr>
      </table>
    </div> <!-- bk-tst-dtl -->


    <div class="boxbtns">
        <a title="Remove Test/Profile" class="btn-new" onClick="test_list_vals();">
          <i class="fa fa-trash-o"></i> Delete
        </a>
    </div> <!-- boxbtns -->
  
  </div> <!-- box -->






  <!-- new code by mamta -->

    <div class="row">
          <div class="col-md-11">
            
            <div class="row">
              <div class="col-md-8"></div> <!-- blank -->
              <div class="col-md-4 p-r-0">
                
                

                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Payment Mode<span class="star">*</span></label>
                    </div>
                    <div class="col-md-7">
                        <select  name="payment_mode" onChange="payment_function(this.value,'');">
                        <?php foreach($payment_mode as $payment_mode) 
                        {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>

                        </select>

                    </div>
                </div>

                <div id="updated_payment_detail">
            <?php if(!empty($form_data['field_name']))
            { 
              
              foreach ($form_data['field_name'] as $field_names) {
            $tot_values= explode('_',$field_names);

            ?>

            <div class="row m-b-5" id="branch"> 
                <div class="col-md-5">
                <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                </div>
                <div class="col-md-7"> 
                <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                <?php 
                if(empty($tot_values[0]))
                {
                if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                }
                ?>
                </div>
            </div>
            <?php } }?>

            </div>

            <div id="payment_detail"></div>
            <script>
                $(document).ready(function() {
 
                            
                    $('#is_home_collection').click(function() 
                    {
                        var hm_charge = '<?php echo $home_collection[0]->charge; ?>';
                        if(this.checked) 
                        { 
                            
                           $('#home_collection_amount').val(hm_charge);
                           payment_calc(hm_charge); 
                        }
                        else
                        {
                          $('#home_collection_amount').val(0);
                            payment_calc(0);  
                        }
                         
                    });
                });
           </script>
          <?php 

              if($home_collection!="empty" && in_array('221',$users_data['permission']['section']) )
              {
                if($home_collection[0]->status==1)
                {
              ?>
                  <div class="row m-b-5">
                    <div class="col-md-5">
                    <label>Home Collection </label>  <input type="checkbox" name="is_home_collection" <?php if($form_data['is_home_collecion']=='1'){ echo 'checked'; }elseif(empty($form_data['is_home_collecion'])){ }elseif($home_collection[0]->status==1){ echo 'checked'; } ?> value="1" id="is_home_collection">
                    </div>
                    <div class="col-md-7">
                    <input type="text" name="home_collection_amount" id="home_collection_amount" value="<?php echo $form_data['home_collection_amount']; ?>"  onkeyup=payment_calc(0);>
                    </div>
                  
                 
                  
                  </div>
              <?php
                }
              }
              else
              {
                ?>
                 <input type="hidden" value="0" name="home_collection_amount" id="home_collection_amount" />
                <?php
              }
              ?>


                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Total Amount</label>
                    </div>
                    <div class="col-md-7">
                     <input type="text" readonly="" name="total_amount" id="total_amount" value="<?php echo $form_data['total_amount']; ?>"><?php if(!empty($form_error)){ echo form_error('total_amount'); } ?>
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5 p-r-0">
                      <label>Discount</label>
                      <input type="text" class="input-tiny price_float"  id="discount_percent" value="" placeholder="%" onKeyUp="return discount_prcnt(this.value);" style="float:right;"> 
                    </div>
                    <div class="col-md-7">
                      <input type="text" name="discount" onKeyUp="discount_vals();" id="discount" value="<?php echo $form_data['discount']; ?>">
                    </div>
                </div>
                
                
                
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>GST</label>
                    </div>
                    <div class="col-md-7">
                    <input type="text" readonly="" name="gst_amount" id="gst_amount" value="<?php echo $form_data['gst_amount']; ?>">
                    </div>
                </div>
                
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Net Amount</label>
                    </div>
                    <div class="col-md-7">
                    <input type="text" readonly="" name="net_amount" id="net_amount" value="<?php echo $form_data['net_amount']; ?>">
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Paid Amount</label>
                    </div>
                     <?php
            $booking_doctor_type = $this->session->userdata('booking_doctor_type');
            ?>
                    <div class="col-md-7">
                     <input type="text" name="paid_amount" <?php if(!empty($form_data['ipd_id']) && isset($form_data['ipd_id'])){ echo 'readonly';} ?> class="price_float" onKeyUp="discount_vals(1);" id="paid_amount" value="<?php echo $form_data['paid_amount']; ?>" <?php if(!empty($booking_doctor_type)){ echo 'readonly="readonly"';} ?> />
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                      <label>Balance</label>
                    </div>
                    <div class="col-md-7">
                      <input type="text" readonly="" name="balance" id="balance" value="<?php echo $form_data['balance']; ?>">
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
						<button class="btn-save" id="btnsubmit">
						  <i class="fa fa-floppy-o"></i> Submit
						</button>
						<a href="<?php echo base_url('test');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
                    </div>
                </div>
                
                

              </div>
            </div>


          </div>
        </div>
        <div class="pb-btm-btns">
        
    </div> <!-- boxbtns -->
  <!-- new code by mamta-->


</form>

</section> <!-- close -->
<?php
$this->load->view('include/footer');
?>

<script>
function check_doctor_plan_type(doctor_id)
{
   
    $.ajax({
          type:"POST",
          url: "<?php echo base_url(); ?>test/check_doctor_plan_type/",
          data: 'doctor_id='+doctor_id, 
            success: function(response)
            {  
            var test_head = $('#dept_parent_test').val(); 
            if(test_head=="")
            {
            test_head = 0;
            }
             profile_id = 0;
             $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+test_head+"/"+profile_id, 
              success: function(result)
              { 

                 $('#test_list').html(result);
                 $.ajax({url: "<?php echo base_url(); ?>test/reset_booked_test/",
                    success: function(result)
                    { 
                      
                       $('#test_select').html(result);
                       payment_calc();
                    } 
                  });
              } 
            });

            if(response==1)
             {
                $('#paid_amount').val('0.00');
                //$('#paid_amount').attr('readonly',true);
             }
            else
            {
              //$('#paid_amount').attr('readonly',false);
            }   
               
            } 
    });
  
}
function get_remarks(id)
  {
    $.ajax({url: "<?php echo base_url(); ?>test/get_remarks/"+id,
     dataType: "json",
      success: function(result)
      {
        $('#remarks').val(result.remarks); 
      } 
    });
    
  }
  function get_diseases(id)
  {
    $.ajax({url: "<?php echo base_url(); ?>test/disease_days/"+id,
     dataType: "json",
      success: function(result)
      {
        //$('#reminder_days').html(result); 
        $('#reminder_days').val(result.reminder_days); 
      } 
    });
    
  }
 function more_patient_info()
 {
    var txt = $(".more_content").is(':visible') ? 'More Info' : 'Less Info';
    $(".show_hide_more").text(txt);
    $("#patient_info").slideToggle();
 }
function father_husband_son()
{
   $("#relation_name").css("display","block");
}


function check_panel_type(panel_val)
{
    var insurance_type_id = $('#insurance_type_id').val();
    var panel_type =  $('input[name=pannel_type]:checked').val();
    var ins_company_id = $('#ins_company_id').val(); 
    var polocy_no = $('#polocy_no').val(); 
    var tpa_id = $('#tpa_id').val();
    var ins_amount = $('#ins_amount').val();
    var ins_authorization_no = $('#ins_authorization_no').val(); 
    $.ajax({
          type:"POST",
          url: "<?php echo base_url(); ?>test/check_test_panel_type/",
          data: 'insurance_type_id='+insurance_type_id+'&panel_type='+panel_type+'&ins_company_id='+ins_company_id+'&polocy_no='+polocy_no+'&tpa_id='+tpa_id+'&ins_amount='+ins_amount+'&ins_authorization_no='+ins_authorization_no, 
            success: function(response)
            {  
            var test_head = $('#dept_parent_test').val(); 
            if(test_head=="")
            {
            test_head = 0;
            }
             profile_id = 0;
             $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+test_head+"/"+profile_id, 
              success: function(result)
              { 

                 $('#test_list').html(result);
                 $.ajax({url: "<?php echo base_url(); ?>test/reset_booked_test/",
                    success: function(result)
                    { 
                      
                       $('#test_select').html(result);
                       payment_calc();
                    } 
                  });
              } 
            });

            if(response==1)
             {
                $('#paid_amount').val('0.00');
                $('#paid_amount').attr('readonly',true);
             }
            else
            {
              $('#paid_amount').attr('readonly',false);
            }   
               
            } 
    });
  
}
function set_tpa(val)
 { 
    if(val==0)
    {
      $('#pannel').attr('checked', true);
      $('#pannel_n').attr('checked', false);
      $('#pannel_type').slideUp();
      
    }
    else
    {
       $('#pannel').attr('checked', false);
       $('#pannel_n').attr('checked', true);
       $('#pannel_type').slideDown();
       
    }
 }

 function get_others(val)
{
  
  if(val=='0')
  {
    $("#ref_by_other").css("display","block");
  }
  else
  {
    $("#ref_by_other").css("display","none");
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
        $('#referral_hospital').val('');
        
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
          $("#ref_by_other").css("display","none"); 
          $("#hospital_div").show();
          $('#refered_id').val('');
          $('#ref_other').val('');
      }
        
    });
});



  function payment_function(value,error_field){
           $('#updated_payment_detail').html('');
                $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('test/get_payment_mode_data')?>",
                     data: {'payment_mode_id' : value,'error_field':error_field},
                     success: function(msg){
                     $('#payment_detail').html(msg);
                     }
                });


           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           });  

      }
     /* $(document).ready(function(){
           $('.datepicker').datepicker({
           format: "dd-mm-yyyy",
           autoclose: true
           }); 
      });*/ 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
}); 
$("button[data-number=4]").click(function(){
    $('#test_row_count').modal('hide');
   /* $(this).hide();*/
});
</script>
<script type="text/javascript">
$(document).ready(function(){
<?php 
if(empty($_POST))
{
if((empty($referal_doctor_list)) || (empty($simulation_list)))
{
  
?>  

 
  $('#test_row_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 
}
}
?>

});
</script>
<script>  
function check_doctor_type(did)
{
   $.ajax({
      url: "<?php echo base_url(); ?>test/check_doctor_type/2/"+did, 
      success: function(response)
      {  
             var test_head = $('#dept_parent_test').val(); 
             if(test_head=="")
             {
                test_head = 0;
             }
             profile_id = 0;
             $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+test_head+"/"+profile_id, 
              success: function(result)
              { 
                 $('#test_list').html(result);
                 $.ajax({url: "<?php echo base_url(); ?>test/reset_booked_test/",
                    success: function(result)
                    { 
                       $('#test_select').html(result);
                       payment_calc();
                    } 
                  });
              } 
            });

            if(response==1)
             {
                $('#paid_amount').val('0.00');
                $('#paid_amount').attr('readonly',true);
             }
            else
            {
              $('#paid_amount').attr('readonly',false);
            }  
         
      } 
    });
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

function set_branch(bid)
{
  if(bid>0)
  {
     $.ajax({url: "<?php echo base_url(); ?>test/booking_set_branch/"+bid, 
      success: function(result)
      {
         window.location.href="<?php echo current_url(); ?>";
      } 
    });
  }
}  

  function add_profile()
  {  
     var profile_id = $('#profile_id').val(); 
     
     var profile_price_updated= $('#profile_price').val();
     
     $.ajax({
         type:'POST',
      url: "<?php echo base_url(); ?>test/set_profile/"+profile_id, 
       data:{profile_price_updated:profile_price_updated},
      success: function(result)
      {
         if(result!=1)
         {
           $('#profile_error').html(result);
         } 
         else
         {
          $.ajax({url: "<?php echo base_url(); ?>test/list_booked_test/",
                success: function(result)
                { 
                   $('#test_select').html(result);
                   payment_calc();
                } 
              });
         }
      } 
    }); 
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

  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }
  
  function get_payment_mode(id)
    {
     
          if(id=='2')
          { 
            
              $('#tr_transaction_no').addClass('row_hide');
              $('#tr_bank_name').removeClass('row_hide');
              $('#tr_cheque_no').addClass('row_hide');
              $('#tr_cheque_date').addClass('row_hide');
              $('#tr_card_no').removeClass('row_hide');
              $("#transaction_no").val('');
              $("#cheque_date").val('');
              $("#bank_name").val('');
              $("#cheque_no").val('');   
          }
          else if(id=='3')
          {
            $('#tr_transaction_no').removeClass('row_hide');
            $('#tr_bank_name').removeClass('row_hide');
            $('#tr_cheque_no').addClass('row_hide');
            $('#tr_cheque_date').addClass('row_hide');
            $('#tr_card_no').addClass('row_hide');
            $("#bank_name").val('');
            $("#cheque_date").val('');
            $("#cheque_no").val(''); 
            $("#card_no").val('');          
          }
          else if(id=='4')
          {
            $('#tr_transaction_no').addClass('row_hide');
            $('#tr_bank_name').removeClass('row_hide');
            $('#tr_cheque_no').removeClass('row_hide');
            $('#tr_cheque_date').removeClass('row_hide');
            $('#tr_card_no').addClass('row_hide'); 
            $("#transaction_no").val(''); 
            $("#card_no").val('');  
          }
		  else
		  {
          $('#tr_transaction_no').addClass('row_hide');
          $('#tr_bank_name').addClass('row_hide');
          $('#tr_cheque_no').addClass('row_hide');
          $('#tr_cheque_date').addClass('row_hide');
          $('#tr_card_no').addClass('row_hide');
          $("#transaction_no").val('');
          $("#bank_name").val('');
          $("#cheque_date").val('');
          $("#cheque_no").val(''); 
          $("#card_no").val(''); 
		  }
    }
    
function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      //callback.apply(context, args);
      test_search_list();
    }, ms || 0);
  };
}


// Example usage:

$('#test_search').keyup(delay(function (e) {
  console.log('Time elapsed!', this.value);
}, 500)); 



  
 function test_search_list()
 {
      var search_text = $("#test_search").val();  
      var dept_id = $("#dept_id").val();  
      var test_head_id = $("#dept_parent_test").val();
      if(test_head_id=='' || test_head_id==null)
      {
        test_head_id = 0;
      }  
      if(dept_id=='')
      {
        dept_id = 0;
      } 
      if(search_text=='')
      {
        search_text = 0;
      }   
          
      var url ="<?php echo base_url(); ?>test/test_list";
      $.post(url,
        { test_head_id: test_head_id, profile_id: 0, search_text: search_text, dept_id: dept_id},
        function (msg) {
          $("#test_list").html(msg);

        });  
}        


  $('#dept_id').change(function(){
      var dept_id = $(this).val(); 
      $.ajax({url: "<?php echo base_url(); ?>test/dept_test_heads/"+dept_id, 
        success: function(result)
        { 
           $('#dept_parent_test').html(result);   
        } 
      }); 
  })

  $('#dept_parent_test').change(function(){  
      var head_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+head_id, 
        success: function(result)
        {
           $('#test_list').html(result); 
        } 
      }); 
  });
 function doctor_modal()
  {
      var $modal = $('#load_add_modal_popup');
      $modal.load('<?php echo base_url().'doctors/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }

  function hospital_add_modal()
  {
      var $modal = $('#load_add_modal_popup');
      $modal.load('<?php echo base_url().'hospital/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }
  
  function collection_center_add_modal()
  {
      var $modal = $('#load_add_modal_popup');
      $modal.load('<?php echo base_url().'collection_center/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }

  function get_sales_docotors()
  {  
    $.ajax({url: "<?php echo base_url(); ?>sales_medicine/sales_medicine_dropdown/", 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
    });
  }
  function get_profile_test()
  { 
     var profile_id = $("#profile_id").val();  
     var panel_id =$("#ins_company_id").val();
     ///// Profile Price
      $.ajax({url: "<?php echo base_url(); ?>test/profile_price/"+profile_id+'/'+panel_id,  
        success: function(result)
        {
           //$('#profile_price').html(result);  
            $('#profile_price').val(result); 
        } 
      }); 
  }

  function discount_prcnt(discount)
  { 
     if(discount>0)
     {  
        var total_amount = $('#total_amount').val();
        var total_discount = (parseFloat(total_amount)/100)*parseFloat(discount);
        $('#discount').val(total_discount.toPrecision(4));
        total_net_amount = parseFloat(total_amount)-parseFloat(total_discount);
        var net_amount = $('#net_amount').val();  
        $('#net_amount').val(total_net_amount.toPrecision(4));
        discount_vals();

       
     }  
     else
     {
        var total_amount = $('#total_amount').val();
        $('#net_amount').val(total_amount);  
        $('#discount').val('0.00'); 
        discount_vals(); 
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

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  });

   $('.datepicker3').datetimepicker({
     format: 'LT'
  });
  
  function test_list_vals() 
  {          
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
       remove_test(allVals);
  } 

  function remove_test(allVals)
  {     
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('test/remove_booked_test');?>",
              data: {test_id: allVals},
              success: function(result) 
              {
                $('#test_select').html(result); 
                var head_id = $('#dept_parent_test').val();
                $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+head_id, 
                  success: function(result)
                  {
                     $('#test_list').html(result); 
                     payment_calc();
                     return false;
                  } 
                });  
              }
          });
   }
  }


  function child_test_vals() 
  {          
       var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       send_test(allVals);
  } 
 
  function send_test(event)
  {     
    event.preventDefault(); 
    $.ajax({
      url: "<?php echo base_url('test/set_booking_test'); ?>",
      type: "post",
      data: $('#booking_form').serialize(),
      success: function(result) 
      {  
        $('#test_select').html(result); 
        var head_id = $('#dept_parent_test').val();
        $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+head_id, 
          success: function(result)
          {
             $('#test_list').html(result); 
             payment_calc();
          } 
        }); 
      }
  });       
  }
  
  
  function discount_vals(vals)
  {
     var timerA = setInterval(function(){ 
                                          $('#discount_percent').val('');
                                          payment_calc(vals);
                                          clearInterval(timerA); 
                                        }, 1600);
  }

  function payment_calc(vals)
  {
    if (vals === undefined)
    {
      var vals = 0;
    }
    var total_amount = $('#total_amount').val();
    var discount = $('#discount').val();
    var net_amount = $('#net_amount').val();
    var paid_amount = $('#paid_amount').val();
    var balance = $('#balance').val();
    var gst_amount = $('#gst_amount').val();
    
    
    var home_collection=$("#home_collection_amount").val();
    $('.overlay-loader').css('display','block');
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>test/payment_calc/"+vals, 
            dataType: "json",
            data: 'total_amount='+total_amount+'&net_amount='+net_amount+'&discount='+discount+'&paid_amount='+paid_amount+'&balance='+balance+'&home_collection='+home_collection,
            success: function(result)
            {  //alert(result.balance);
               $('#total_amount').val(result.total_amount.toFixed(2)); 
               $('#net_amount').val(result.net_amount); 
               $('#discount').val(result.discount); 
               $('#gst_amount').val(result.gst_amount);
               
               <?php 
                if(isset($form_data['ipd_id']) && !empty($form_data['ipd_id']))
                  { 
                    echo "$('#paid_amount').val('0.00'); ";
                    echo "$('#balance').val('0.00'); ";
                    
                  }
                  else
                  { 
                      
                      ?>
                       $('#balance').val(result.balance); 
                    <?php //echo "$('#paid_amount').val(result.paid_amount); ";
                  } 
               ?>
               //$('#paid_amount').val(result.paid_amount); 
               //$('#balance').val(result.balance); 
               $('.overlay-loader').css('display','none');
            } 
          });
  }
  
   function find_gender(id)
   {
     if(id!==''){
          $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
               if(result!==''){
                    $("#gender").html(result);
               }
          })
     }
 }

$(document).ready(function(){
  var simulation_id = $('#simulation_id').val();
  if(simulation_id>0)
  {
   find_gender(simulation_id); 
  }
}); 
$(document).ready(function(){
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_doctor_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
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
</script> 
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
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
    </div> <!-- modal -->
     <div id="test_row_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          <?php if(empty($referal_doctor_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Referral Doctor is required.</span></p><?php } ?>
          
          </div>
        </div>
      </div>  
    </div>

<!-- Confirmation Box end -->
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_doctor_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<script type="text/javascript">
    $(function () {
 
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('sales_medicine/opd_patient/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) 
    {
            //$(".medicine_val").val(ui.item.value);
            var names = ui.item.data.split("|");
            $('.opd_patient').val(names[0]);
            $('#patient_name').val(names[1]);
            $('#simulation_id').val(names[2]);
            $('#patient_code').val(names[3]);
            $('#mobile_no').val(names[4]);
            //alert(names[4]);
            if(names[5]==1)
            {
                $('#male_gender').prop("checked",true);
                $('#female_gender').prop("checked",false);
                $('#other_gender').prop("checked",false);
                
            }
            else if(names[5]==0)
            {
                $('#female_gender').prop("checked",true);
                $('#other_gender').prop("checked",false);
                $('#male_gender').prop("checked",false);
            }
            else
            {
               $('#other_gender').prop("checked",true);
               $('#female_gender').prop("checked",false);
               $('#male_gender').prop("checked",false);
            }
            //$('#gender').val(names[3]);
            $('#relation_type').val(names[6]);
            $('#relation_simulation_id').val(names[7]);
            $('#relation_name').val(names[8]);

            if(names[9]==1)
            {
                $('#referred_by_doctor').prop("checked",false);
                $('#referred_by_hospital').prop("checked",true);
                $('#referral_hospital').val(names[12]);

                $("#doctor_div").hide();
                $("#ref_by_other").css("display","none"); 
                $("#hospital_div").show();
                $('#refered_id').val('');
                $('#ref_other').val('');
            }
            else
            {
                //alert(names[9]);
                $('#referred_by_doctor').prop("checked",true);
                $('#referred_by_hospital').prop("checked",false);
                $('#referral_doctor').val(names[10]);


                $("#hospital_div").hide();
                $("#doctor_div").show();
                $('#referral_hospital').val('');
                if(names[10]==0)
                {
                    get_others(0);
                    $('#ref_by_other').val(names[11]);
                }
                else
                {
                    get_others(1);
                }
            }
            $('#remarks').val(names[13]);
            
            $('#patient_email').val(names[15]);
            $('#age_y').val(names[16]);
            $('#age_m').val(names[17]);
            $('#age_d').val(names[18]);
            $('#age_h').val(names[19]);
            $('#address1').val(names[20]);
            $('#address2').val(names[21]);
            $('#address3').val(names[22]);
            $('#city_id').val(names[23]);
            $('#state_id').val(names[24]);
            $('#patient_id').val(names[25]);
            $('#opd_id').val(names[26]);
            
            set_prescription_test(names[14]);
            return false;
    }

    $(".opd_patient").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });


  function set_prescription_test(allVals)
  {   
     //alert(allVals);
     if(allVals!="")
     {
        $.ajax({url: "<?php echo base_url(); ?>test/set_prescription_booking_test/"+allVals, 
        success: function(result)
        {
           $('#test_select').html(result); 
           payment_calc();
        }
        });

       /* $.ajax({
                type: "POST",
                url: "< ?php echo base_url('test/set_prescription_booking_test/');?>",
                data: {prescription_id: allVals},
                dataType: "json",
                success: function(result) 
                {
                  alert(result);
                  $('#test_list').html(result.data); 
                  payment_calc();  
                 }
            });*/
     }      
  }

  /*function send_test(event)
  {     
    event.preventDefault(); 
    $.ajax({
      url: "< ?php echo base_url('test/set_booking_test'); ?>",
      type: "post",
      data: $('#booking_form').serialize(),
      success: function(result) 
      {  
        $('#test_select').html(result); 
        var head_id = $('#dept_parent_test').val();
        $.ajax({url: "< ?php echo base_url(); ?>test/test_list/"+head_id, 
          success: function(result)
          {
             $('#test_list').html(result); 
             payment_calc();
          } 
        }); 
      }
  });       
  }*/
</script>

<script type="text/javascript">
 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
  $test_book_ids = $this->session->userdata('test_booking_id');
 ?>

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && isset($test_book_ids)){ ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('test/booking');?>'; 
    }); 
       
  <?php }?>

  <?php if(isset($_GET['form_f_status']) && $_GET['form_f_status']=='1' && isset($test_book_ids)){ ?>
  $('#confirm_print_form_f').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('test/booking');?>'; 
    }); 
       
  <?php }?>
 });
 
</script> 
<!-- Confirmation Box -->

    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("test/print_test_booking_report"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

    
    <div id="confirm_print_form_f" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("test/form_f_print"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

    
     <div id="confirm_print_bill" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("test/print_test_bill"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

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
    </div> <!-- modal -->

      <div id="confirm_delivered" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delivered">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->
    
     <script>
/*  $('input[id=btnsubmit]').click(function(){
    $(this).attr('disabled', 'disabled');
});*/

$('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#booking_form').submit();
  })

//neha 14-2-2019
 function get_patient_detail_by_mobile() {
  var val = $('#mobile_no').val();
   if(val.length==10)
   {
    
    $.ajax({
      url: "<?php echo site_url('test/get_patient_detail_no_mobile'); ?>/"+val,
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
              var action_url = '<?php echo site_url('test/booking/'); ?>'
              var radioValue = $("input[name='patient_id']:checked").val();
               $.ajax({
                    url: "<?php echo site_url('test/get_patient_detail_byid'); ?>/"+radioValue,
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
                    $('#booking_form').attr('action', action_url+data.patient_detail.id); 
                    
                    
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
     
<?php     
if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0 && !empty($lead_profile_list))
{
  foreach($lead_profile_list as $profile)
  {
  ?>
     var profile_id = '<?php echo $profile["profile_id"];?>';  
     var panel_id = '';
     ///// Profile Price
      $.ajax({url: "<?php echo base_url(); ?>test/profile_price/"+profile_id+'/'+panel_id,  
        success: function(profile_price)
        { 
            $.ajax({
                    type:'POST',
                    url: "<?php echo base_url(); ?>test/set_profile/"+<?php echo $profile["profile_id"];?>, 
                     data:{profile_price_updated:profile_price},
                    success: function(result)
                    {
                       if(result!=1)
                       {
                         $('#profile_error').html(result);
                       } 
                       else
                       {
                        $.ajax({url: "<?php echo base_url(); ?>test/list_booked_test/",
                              success: function(result)
                              { 
                                 $('#test_select').html(result);
                                 payment_calc();
                              } 
                            });
                       }
                    } 
                  }); 
        } 
      }); 
  <?php 
  }
}


if(isset($_GET['lid']) && !empty($_GET['lid']) && $_GET['lid']>0 && !empty($lead_test_list))
{
  foreach($lead_test_list as $test)
  {
  ?>
      $.ajax({
                url: "<?php echo base_url('test/set_booking_test2/').$test->id.'/'.$test->rate; ?>",
                type: "post",
                data: $('#booking_form').serialize(),
                success: function(result) 
                {  
                  $('#test_select').html(result);  
                  payment_calc(); 
                }
            });   
  <?php 
  }
} ?>


<?php     
//For porforma invoice test and profile 
if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id']) && $_GET['invoice_id']>0 && !empty($invoice_profile_list))
{
  foreach($invoice_profile_list as $profile)
  {
  ?>
     var profile_id = '<?php echo $profile["profile_id"];?>';  
     var panel_id = '';
     ///// Profile Price
      $.ajax({url: "<?php echo base_url(); ?>test/profile_price/"+profile_id+'/'+panel_id,  
        success: function(profile_price)
        { 
            $.ajax({
                    type:'POST',
                    url: "<?php echo base_url(); ?>test/set_profile/"+<?php echo $profile["profile_id"];?>, 
                     data:{profile_price_updated:profile_price},
                    success: function(result)
                    {
                       if(result!=1)
                       {
                         $('#profile_error').html(result);
                       } 
                       else
                       {
                        $.ajax({url: "<?php echo base_url(); ?>test/list_booked_test/",
                              success: function(result)
                              { 
                                 $('#test_select').html(result);
                                 payment_calc();
                              } 
                            });
                       }
                    } 
                  }); 
        } 
      }); 
  <?php 
  }
}


if(isset($_GET['invoice_id']) && !empty($_GET['invoice_id']) && $_GET['invoice_id']>0 && !empty($lead_test_list))
{
  foreach($lead_test_list as $test)
  {
  ?>
      $.ajax({
                url: "<?php echo base_url('test/set_booking_test2/').$test->id.'/'.$test->rate; ?>",
                type: "post",
                data: $('#booking_form').serialize(),
                success: function(result) 
                {  
                  $('#test_select').html(result);  
                  payment_calc(); 
                }
            });   
  <?php 
  }
} ?>

$(document).ready(function(){
var $modal = $('#load_add_authorize_person_modal_popup');

$('#authorize_person_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'authorize_person/add/' ?>',
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
   
   var $modal = $('#load_add_patient_category_modal_popup');

$('#patient_category_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient_category/add/' ?>',
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

  $('#load_add_authorize_person_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$(document).ready(function(){
  $('#load_add_doctor_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

</script>

<script>
  $(document).ready(function(){
   
   var $modal = $('#load_add_patient_category_modal_popup');

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
    
    <div id="load_add_patient_category_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_authorize_person_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
    <div class="overlay-loader"><img src="<?php echo base_url().'assets/images/loader.gif';?>"></div>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $("#refered_id").select2();
  $("#attended_doctor").select2();
  $(".SampleCollectedBy").select2(); 
  $(".staff_refrenace_id").select2();
</script>