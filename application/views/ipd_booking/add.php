<?php 
$users_data = $this->session->userdata('auth_users'); 

if (array_key_exists("permission",$users_data)){
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];
}
else{
     $permission_section = array();
     $permission_action = array();
}
$field_list = mandatory_section_field_list(6);
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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script>
$('document').ready(function(){
 <?php
 $ipd_booking_id = $this->session->userdata('ipd_booking_id');
 if(isset($_GET['status']) && isset($ipd_booking_id) && $_GET['status']=='print'){ ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('opd/booking');?>'; 
    }); 
       
  <?php } ?>




   <?php if(isset($_GET['status']) && isset($ipd_booking_id) && isset($_GET['mlc_status']) && $_GET['mlc_status']==1){?>
  $('#confirm_mlc').modal({
      backdrop: 'static',
      keyboard: false
        })
  
    .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>

<?php if(isset($_GET['status']) && isset($ipd_booking_id) && isset($_GET['admission_form']) && $_GET['admission_form']=='print_admission'){?>
  $('#confirm_admission_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>
  
  <?php if($users_data['parent_id']=='113' && isset($_GET['status']) && isset($ipd_booking_id) && isset($_GET['admission_consent_form']) && $_GET['admission_consent_form']=='print_consent_form'){?>
  $('#confirm_admission_consent').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        //window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>
  
  });



</script>

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
 <form action="<?php echo current_url(); ?>" method="post" id="form_submit_data"> 
<input type="hidden" name="discharge_date" id="discharge_date" value="<?php echo $form_data['discharge_date']; ?>" />    
<input type="hidden" class="m_input_default" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
    <input type="hidden" class="m_input_default"  name="patient_id" value="<?php if(isset($form_data['patient_id'])){echo $form_data['patient_id'];}else{ echo '';}?>"/>

<div class="row">
    <div class="col-sm-4">
         <?php 
                /*$checked_reg=''; 
                $checked_ipd='';
                $checked_nor='checked';
               ?>
                <?php if(isset($_GET['ipd']) && $_GET['ipd']!='') {

                $checked_ipd="checked";
                $checked_nor='';
                } */ ?>
                <?php 
                
                if(isset($_GET['ipd']) && $_GET['ipd']!='') 
                {
                $checked_reg="";
                $checked_nor='';
                $checked_ipd='checked';
                }
                elseif(isset($form_data['data_id']) && $form_data['data_id']!='') 
                {
                    $checked_reg="";
                    $checked_nor='';
                    $checked_ipd ="checked";
                }
                else
                {
                    $checked_reg=''; 
                    $checked_ipd='';
                    $checked_nor='checked';  
                }

                ?>
               
        <div class="row m-b-5">
            <div class="col-sm-5"><label><input type="radio" name="" <?php echo $checked_nor; ?> onClick="window.location='<?php echo base_url('ipd_booking/');?>add/';"> New Patient</label></div>
            <div class="col-sm-7">
                <label><input type="radio" name="" <?php echo $checked_ipd; ?> onClick="window.location='<?php echo base_url('patient');?>';"> <span>Registered</span></label>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label><?php echo $data= get_setting_value('PATIENT_REG_NO');?><span class="star">*</span></label></div>
             <input type="hidden" class="m_input_default" value="<?php echo $form_data['patient_reg_code'];?>" name="patient_reg_code" />
            <div class="col-sm-7">
                <div class="ipdbox"><?php echo $form_data['patient_reg_code'];?></div>
            </div>
        </div>
        
        <div class="row m-b-5">
        <input type="hidden" value="<?php echo $form_data['ipd_no'];?>" name="ipd_no" />
            <div class="col-sm-5"><label>IPD No.<span class="star">*</span></label></div>
            <div class="col-sm-7">
                <div class="ipdbox"> <?php echo $form_data['ipd_no'];?> </div>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Patient Name <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select class="mr m_mr" name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                  <option value="">Select</option>
                    <?php 
                    //$simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
                    $simulations_arr=[];
          if(!empty($simulation_array))
          {
              foreach($simulation_array as $simulation_array)
              {
                $simulations_arr[]=$simulation_array->id;
              }    
          }
                    foreach($simulation_list as $simulation){
                        $selected_simulation = '';
                        if(in_array($simulation->simulation,$simulations_arr))
                        {
                            $selected_simulation = 'selected="selected"';

                        }
                        else
                        {
                          if($simulation->id==$form_data['simulation_id'])
                          {
                               $selected_simulation = 'selected="selected"';
                          }
                         }

                         if($simulation->simulation == "Mr.")
                         {
                          $selected_simulation = 'selected="selected"';
                         }
                        ?>
                      <option value="<?php echo $simulation->id; ?>" <?php  echo $selected_simulation; ?>><?php echo $simulation->simulation;?></option>
                    <?php }
                    ?>
                </select>
                <input type="text" name="name" class="mr-name m_name txt_firstCap" value="<?php echo $form_data['name'];?>">

                <div class=""><?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                    </div>
                <div class=""><?php if(!empty($form_error)){ echo form_error('name'); } ?>
                    </div>
            </div>
        </div>

                    <!-- new code by mamta -->
    <div class="row m-b-5">
      <div class="col-sm-5">
        <strong> 
          <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
          <?php foreach($gardian_relation_list as $gardian_list) 
          {?>
          <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
          <?php }?>
          </select>

             </strong>
      </div>
      <div class="col-sm-7">
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
            <div class="col-sm-5"><label>Mobile No. <?php if(!empty($field_list)){
                    if($field_list[0]['mandatory_field_id']==30 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-7">
                 <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code m_c_code" placeholder="+91" style="width:59px;"> 
                <input type="text" name="mobile" class="number m_number" id="mobile_no" maxlength="10" value="<?php echo $form_data['mobile'];?>" onKeyPress="return isNumberKey(event);">
                    <div class="">
                    <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='30' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('mobile'); }
                         }
                    }
          ?>
                    </div>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Gender <span class="star">*</span></label></div>
            <!-- <div class="col-sm-7">
                <span class="text-normal"><input type="radio" name="gender" value="0" < ?php if($form_data['gender']==0){ echo 'checked';} ?>> <span>Male</span></span>
                <span class="text-normal"><input type="radio" name="gender"  value="1" < ?php if($form_data['gender']==1){ echo 'checked';} ?>> <span>Female</span></span>
            </div> -->
            <div class="col-sm-7" id="gender">
           <input type="radio" name="gender" checked value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
            <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0 && !empty($form_data['gender'])){ echo 'checked="checked"'; } ?>> Female
              <input type="radio" name="gender" value="2" <?php if($form_data['gender']==2){ echo 'checked="checked"'; } ?>> Others
            <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
         </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Age <?php if(!empty($field_list)){
                    if($field_list[1]['mandatory_field_id']==31 && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-7">
                <input type="text" name="age_y" class="input-tiny m_tiny"  value="<?php echo $form_data['age_y'];?>"> Y
                <input type="text" name="age_m" class="input-tiny m_tiny" value="<?php echo $form_data['age_m'];?>"> M
                <input type="text" name="age_d" class="input-tiny m_tiny" value="<?php echo $form_data['age_d'];?>"> D
                <?php if(!empty($field_list)){
                         if($field_list[1]['mandatory_field_id']=='31' && $field_list[1]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('age_y'); }
                         }
                    }
                ?>
            </div>

        </div>
        
        <div class="row m-b-5">
            <div class="col-md-5">
               <div class="row">
                 
                 <div class="col-md-7" style="text-align: center;">
                    <a href="javascript:void(0);" class="show_hide_more" data-content="toggle-text" onclick="more_patient_info()">More Info</a>
                 </div>
               </div>
            </div>
          </div> <!-- row -->

        <div class="more_content" id="patient_info" style="display: none;">
        
         <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 1</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="address" id="" class="address" value="<?php echo $form_data['address'];?>"/>
               <?php //if(!empty($form_error)){ echo form_error('address'); } ?>
            </div>
          </div>

           <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 2</label>
            </div>
            <div class="col-xs-7">
               <input type="text"  name="address_second" class="address" value="<?php echo $form_data['address_second'];?>"/>
              
            </div>
          </div>
           <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Address 3</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="address_third" class="address" value="<?php echo $form_data['address_third'];?>"/>
               
            </div>
          </div>
         <div class="row m-b-3">
            <div class="col-xs-5">
               <label>Aadhaar No.</label>
            </div>
            <div class="col-xs-7">
               <input type="text" name="adhar_no" value="<?php echo $form_data['adhar_no'];?>"/>
               <?php if(!empty($form_error)){ echo form_error('adhar_no'); } ?>
            </div>
          </div>


        <div class="row m-b-2">
            <div class="col-sm-5"><label>MLC</label>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Medicolegal cases (MLC) are an integral part of medical practice that is frequently encountered by Medical Officers (MO). The occurrence of MLCs is on the increase, both in the Civil as well as in the Armed Forces.</span></a></sup>
</div>
            <div class="col-sm-7">
           
                <label><input type="radio" name="mlc_status" value="1" <?php if($form_data['mlc_status']=="1"){echo 'checked';} ?> onchange="check_status(1);">Yes</label> &nbsp;
                <label><input type="radio" name="mlc_status" value="0" <?php if($form_data['mlc_status']=="0"){echo 'checked';} ?> onchange="check_status(0);">No</label>

                 <input type="text" name="mlc" value="<?php echo $form_data['mlc'];?>" id="mlc_status" <?php if($form_data['mlc_status']=='1'){echo "style='display:block;'";} else{echo "style='display:none;'";}?>/>

            </div>
        </div>
</div> <!--more div close -->
         <div class="row m-b-5">
            <div class="col-sm-5"><label>Remarks</label></div>
            <div class="col-sm-7">
                <textarea type="text" class="m_input_default" name="remarks"><?php echo $form_data['remarks'];?></textarea>
            </div>
        </div>
        <div class="row m-b-5">
            <div class="col-sm-3"><label>Diagnosis</label></div>
            <div class="col-sm-9">
                <select name="diagnosis[]" class="diagnosis_list form-control" id="" multiple></select>
            </div>
        </div>
        
        

    </div> <!-- 4 -->


    


    <div class="col-sm-4">
       <?php if(in_array('117',$permission_section)){ ?>  
      <div class="row m-b-2">
            <div class="col-sm-5"><label>Package</label><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>According to the central government health scheme (CGHS), package is defined as a lump sum cost of inpatient treatment for which a patient has been referred by a competent authority or CGHS to the hospital or diagnostic center..</span></a></sup></div>
            <div class="col-sm-7">
                <label><input type="radio" name="package" value="1" <?php if($form_data['package']=="1"){echo 'checked';} ?>  onclick="package_val(this.value);"> No</label> &nbsp;
                <label><input type="radio" name="package" value="2" <?php if($form_data['package']=="2"){echo 'checked';} ?> onclick="package_val(this.value);"> Yes</label>


            </div>
        </div>

         <div class="row m-b-5">
            <div class="col-sm-5"><label>Package Name <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select name="package_id" class="m_input_default" id="package_id">
                    <option value="">-Select-</option>
                    <?php foreach($package_list as $ipd_pacakge){?>
                    <option value="<?php echo $ipd_pacakge->id; ?>" <?php if($form_data['package_id']==$ipd_pacakge->id){ echo 'selected';}?>><?php echo $ipd_pacakge->name; ?></option>

                    <?php } ?>
                </select>

                <?php if(!empty($form_error)){ echo form_error('package_id'); } ?>
            </div>
        </div>
        <?php } else{  ?>
        <input type="hidden" name="package_id" value="0">
         <input type="hidden" name="package" value="0">
        <?php } ?>
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Room Type <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select name="room_id" class="m_input_default" value="room_id" onchange="room_no_select(this.value);" id="room_id">
                    <option value="">-Select-</option>
                    <?php foreach($room_type_list as $room_type){?>
                    <option value="<?php echo $room_type->id; ?>" <?php if($form_data['room_id']==$room_type->id){ echo 'selected';}?>><?php echo $room_type->room_category; ?></option>
                    <?php }?>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('room_id'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5" >
            <div class="col-sm-5"><label>Room No. <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select name="room_no_id" class="m_input_default" id="room_no_id" onchange="select_no_bed(this.value);">
                    <option value="">-Select-</option>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('room_no_id'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Bed No. <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select name="bed_no_id" class="m_input_default" id="bed_no_id">
                    <option value="">-Select-</option>
                </select>
                 <?php if(!empty($form_error)){ echo form_error('bed_no_id'); } ?>
            </div>
        </div>
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
        <?php //echo "<pre>";print_r($field_list); ?>
        <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="col-sm-5"><label>Referred By Doctor <?php  if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==32 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){ ?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-7">
                <select name="referral_doctor" class="m_input_default" id="refered_id">
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
                    </select> 

                <?php //if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>

                <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('referral_doctor'); }
                         }
                    }
                ?>
            </div>
        </div>

        <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
    
         <div class="col-md-5"><b>Referred By Hospital <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==32 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></b></div>
         <div class="col-sm-7">
           <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
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
            <?php //if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
            <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('referral_hospital'); }
                         }
                    }
                ?>
         </div>
      
  </div> <!-- row -->
<?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section'])){ 

    ?>
    <div class="row m-b-5">
            <div class="col-sm-5"><label>Referred By Doctor <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==32 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></label></div>
            <div class="col-sm-7">
                <select name="referral_doctor" class="m_input_default" id="refered_id">
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
                    </select> 

                <?php //if(!empty($form_error)){ echo form_error('referral_doctor'); } ?>

                <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('referral_doctor'); }
                         }
                    }
                ?>
            </div>
        </div>
        <input type="hidden" name="referred_by" value="0">
  <input type="hidden" name="referral_hospital" value="0">
    <?php
    }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

        ?>
        <div class="row m-b-5">
    
         <div class="col-md-5"><b>Referred by Hospital <?php if(!empty($field_list)){
                    if($field_list[2]['mandatory_field_id']==32 && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } 
          ?></b></div>
         <div class="col-sm-7">
           <select name="referral_hospital" class="m_input_default" id="referral_hospital" >
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
            <?php //if(!empty($form_error)){ echo form_error('referral_hospital'); } ?>
            <?php if(!empty($field_list)){
                         if($field_list[2]['mandatory_field_id']=='32' && $field_list[2]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('referral_hospital'); }
                         }
                    }
                ?>
         </div>
      
  </div> <!-- row -->
<input type="hidden" name="referred_by" value="1">
  <input type="hidden" name="referral_doctor" value="0">
        <?php 

    }

    ?>
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Attended Doctor <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <select name="attended_doctor" class="m_input_default">
                    <option value="">-Select-</option>
                    <?php foreach($attended_doctor as $attened_docotr_list){ ?>
                    <option value="<?php echo $attened_docotr_list->id;?>" <?php if($form_data['attended_doctor']==$attened_docotr_list->id){echo 'selected';}?>><?php echo ucfirst($attened_docotr_list->doctor_name); ?></option>
                    <?php }?>
                </select>

                <?php if(!empty($form_error)){ echo form_error('attended_doctor'); } ?>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Assigned Doctor <span class="star">*</span></label></div>
            <div class="col-sm-7">
                <div class="ipd_right_scroll_box m_input_default">
                <?php $assigned_doctor_list=array();
                if(isset($assigned_d_by_id) && !empty($assigned_d_by_id)){


                     foreach($assigned_d_by_id as $assigned_list){
                        $assigned_doctor_list[]=$assigned_list->doctor_id;

                     }
                  }
                    // print_r($assigned_doctor);
                foreach($assigned_doctor as $assigned_doctor) {
                      if(in_array($assigned_doctor->id, $assigned_doctor_list)){
                         $var="checked='checked'";
                      }else{
                        $var='';
                      }

                    ?>
                    <div class="list">
                        <input type="checkbox" name="assigned_doctor_list[]" <?php echo $var;?> value="<?php echo $assigned_doctor->id;?>">  <span><?php echo ucfirst($assigned_doctor->doctor_name);?></span>
                    </div>

                    <?php } ?>
                
                </div>
                <?php if(!empty($form_error)){ echo form_error('assigned_doctor_list[]'); } ?>
            </div>
        </div>
         <?php if(in_array('774',$permission_action)){ ?> 
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Advance Deposit</label></div>
            <div class="col-sm-7">
                <input type="text" <?php if(!empty($form_data['advance_deposite'])){ echo "readonly"; } ?> name="advance_deposite" class="price_float m_input_default" value="<?php echo $form_data['advance_deposite'];?>">
            </div>
             <?php if(!empty($form_error)){ echo form_error('advance_deposite'); } ?>
        </div>
        <?php }else{   
            ?>  
            <input type="hidden" name="advance_deposite" class="m_input_default" value="0.00">
            <?php 
            } ?>
          <?php $data= get_setting_value('REG_CHARGE_IPD_BOOK'); 
          
              
              if(!empty($form_data['data_id']))
              {
                    
              
                  ?>
                    <div class="row m-b-5">
                        <div class="col-sm-5"><label>Registration Charge</label></div>
                        <div class="col-sm-7">
                            <input type="text" name="reg_charge" class="m_input_default" value="<?php echo $form_data['reg_charge'];?>" readonly>
                        </div>
                    </div>
                  <?php 
              }
              else if(!empty($data) && isset($data))
              {
                ?>
                    <div class="row m-b-5">
                        <div class="col-sm-5"><label>Registration Charge</label></div>
                        <div class="col-sm-7">
                            <input type="text" name="reg_charge" class="m_input_default" value="<?php echo $data;?>" readonly>
                        </div>
                    </div>
                
                <?php 
              
            
             }else{?>

           <div class="row m-b-5">
                    <div class="col-sm-5"><label>Registration Charge</label></div>
                    <div class="col-sm-7">
                        <input type="text" name="reg_charge" class="m_input_default" value="0.00" readonly>
                    </div>
                </div>
           <?php  }?>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label></label></div>
            <div class="col-sm-7">
                
            </div>
        </div>
        
    </div> <!-- 4 -->

    <div class="col-sm-4">
        
        <div class="row m-b-2">
            <div class="col-sm-5"><label>Patient Type</label>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>A doctor within a given area available for consultation by patients insured under the National Health Insurance Scheme It has two type <br> Normal: Having no policy. <br>Panel:Having policy.</span></a></sup>
</div>
            <div class="col-sm-7">
                <label><input type="radio" name="patient_type" value="1" <?php if($form_data['patient_type']==1){ echo 'checked';}?>  onclick="patient_change(this.value);"> Normal</label> &nbsp;
                <label><input type="radio" name="patient_type" value="2" <?php if($form_data['patient_type']==2){ echo 'checked';}?> onclick="patient_change(this.value);"> Panel</label>
            </div>
        </div>
         <div id="panel_box">
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Type</label></div>
            <div class="col-sm-7">
                <select id="panel_type" class="m_input_default" name="panel_type">
                    <option value="">-Select-</option>
                    <?php foreach($panel_type_list as $type_list){ ?>
                    <option value="<?php echo $type_list->id; ?>" <?php if($form_data['panel_type']==$type_list->id){echo 'selected';}?>><?php echo $type_list->insurance_type; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Name</label></div>
            <div class="col-sm-7">
                <select id="company_name" class="m_input_default" name="company_name">
                    <option value="">-Select-</option>
                     <?php foreach($panel_company_list as $company_name){ ?>
                    <option value="<?php echo $company_name->id; ?>" <?php if($form_data['company_name']==$company_name->id){echo 'selected';}?>><?php echo $company_name->insurance_company; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Policy No.</label></div>
            <div class="col-sm-7">
                <input type="text" class="m_input_default" name="policy_number" id="policy_no" value="<?php echo $form_data['policy_number']; ?>">
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>ID No.</label></div>
            <div class="col-sm-7">
                <input type="text" name="id_number" class="m_input_default" id="id_number" value="<?php echo $form_data['id_number']; ?>">
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><label>Authorization Amt.</label></div>
            <div class="col-sm-7">
                <input type="text" class="m_input_default" name="authorization_amount" id="authorization_amount" value="<?php echo $form_data['authorization_amount']; ?>">
            </div>
        </div>
     </div>   
        <div class="row m-b-5">
            <div class="col-sm-5 p-r-0"><b>Admission Date & Time (dd-mm-yyyy)<span class="star">*</span></b></div>
            <div class="col-sm-7">
                <input type="text" name="admission_date" class="w-130px datepicker m_input_default" placeholder="Date" value="<?php echo  $form_data['admission_date']; ?>" >
                <input type="text" name="admission_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php echo $form_data['admission_time']; ?>">
              
            </div>
        </div>
        
        <div class="row m-b-5">
            <div class="col-sm-5"><b>Patient Category</b></div>
            <div class="col-sm-7">
              <select name="patient_category" id="patient_category" class="w-150px m_select_btn">
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
          
          <div class="row m-b-5">
            <div class="col-sm-5"><b>Authorize Person</b></div>
            <div class="col-sm-7">
              <select name="authorize_person" id="authorize_person" class="w-150px m_select_btn">
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

        <div class="row m-b-5">
            <div class="col-sm-5"><b>Mode of Payment <span class="star">*</span> </b></div>
            <div class="col-sm-7">
               <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                       <?php foreach($payment_mode as $payment_mode) 
                       {?>
                        <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                        <?php }?>
                         
                    </select>
            </div>
        </div>
        
        

      

        <div id="updated_payment_detail">
                 <?php if(!empty($form_data['field_name']))
                 { foreach ($form_data['field_name'] as $field_names) {
                     $tot_values= explode('_',$field_names);

                    ?>

                  <div class="row m-b-5" id="branch"> 
                  <div class="col-md-5">
                  <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                  </div>
                  <div class="col-md-7"> 
                  <input type="text" class="m_input_default" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" class="m_input_default" value="<?php echo $tot_values[2];?>" name="field_id[]" />
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
                  
                <div id="payment_detail">
                    

                </div>

     <div class="row m-b-5">
          <div class="col-sm-5"><label></label></div>
          <div class="col-sm-7">
            
            <button class="btn-update" type="button" name="" id="ipd_booking_save" onclick="button_disabled();"><i class="fa fa-floppy-o"></i> <?php echo $btn_name; ?></button>
            
            <a href="<?php echo base_url('ipd_booking'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
          </div>
        </div>
        
        
    </div> <!-- 4 -->
</div> <!-- main row -->
</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>

<script>
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
    echo 'flash_session_msg("'.$flash_success.'");';
   ?>
  <?php
}

?>

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });
/*function check_status(check_status)
{
  if(check_status==1)
  {
    $('#mlc_status').show();
  }
  else
  {
    $('#mlc_status').val('');
    $('#mlc_status').hide();
  }
  
}*/

function check_status(check_status)
{
  if(check_status==1)
  {
    <?php $arr=check_hospital_mlc_no(); $mlc_no=$arr['prefix'].$arr['suffix'];?>
    $('#mlc_status').show();
    if($('#mlc_status').val()=='')
    $('#mlc_status').val('<?php echo $mlc_no;?>');//.attr('readonly', true);
  }
  else
  {
    $('#mlc_status').val('');
    $('#mlc_status').hide();
  }
  
}

function button_disabled()
{
    $('#ipd_booking_save').attr('disabled','disabled');
    $("#form_submit_data" ).submit();
}

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
          //$("#refered_id :selected").val('');
      }
        
    });
});

function form_submit()
{
  $('#search_form_list').delay(200).submit();
}

$("#search_form_list").on("submit", function(event) { 
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



$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });

function select_payment_mode(value_s){
     if(value_s==2){
          
            $('#card_no').css("display", "block");
            $('#bank_name').css("display", "block");
            $('#cheque_no').css("display", "none");
            $('#transaction_no').css("display", "none");
            $('#cheque_date').css("display", "none");
     }

     else if(value_s==3){

            $('#cheque_no').css("display", "block");
            $('#cheque_date').css("display", "block");
            $('#bank_name').css("display", "block"); 
            $('#transaction_no').css("display", "none"); 
            $('#card_no').css("display", "none"); 
     }
     else if(value_s==4){
            $('#transaction_no').css("display", "block");
            $('#bank_name').css("display", "block");  
            $('#cheque_no').css("display", "none");
            $('#card_no').css("display", "none"); 
            $('#cheque_date').css("display", "none");
     }
     else{
       
            $('#cheque_no').html('');
            $('#card_no').html('');
            $('#bank_name').html('');
            $('#transaction_no').html('');
            $('#cheque_date').html('');
     }


    
}


 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('ipd_booking/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
     
   
 
   
  }

 function package_val(value_p)
 {
    if(value_p==1)
    { 
        $('#package_id').attr("disabled", true); 
    }
    else
    {
        $('#package_id').attr("disabled", false); 
    }

}

 function patient_change(value_p)
 {
         if(value_p==1)
         {
             $('#panel_box').slideUp();
            $('#panel_type').attr("disabled", true); 
            $('#company_name').attr("disabled", true); 
            $('#policy_no').attr("disabled", true); 
            $('#id_number').attr("disabled", true); 
            $('#authorization_amount').attr("disabled", true); 
        }
        else
        {
            $('#panel_box').slideDown();
            $('#panel_type').attr("disabled", false); 
            $('#company_name').attr("disabled", false); 
            $('#policy_no').attr("disabled", false); 
            $('#id_number').attr("disabled", false); 
            $('#authorization_amount').attr("disabled", false); 
        }

}
  function room_no_select(value_room,room_no_id){
            $.ajax({
                url: "<?php echo base_url('ipd_booking/select_room_number/'); ?>",
                type: "post",
                data: {room_id:value_room,room_no_id:room_no_id},
                success: function(result) 
                {
                  $('#room_no_id').html(result);
                }
            });
     }

     function select_no_bed(value_bed,bed_id){

        var room_id= $("#room_id option:selected").val();
        var ipd_id = $("#type_id").val();
        
        $.ajax({
                url: "<?php echo base_url('ipd_booking/select_bed_no_number/'); ?>",
                type: "post",
                data: {room_id:room_id,room_no_id:value_bed,bed_id:bed_id,ipd_id:ipd_id},
                success: function(result) 
                {
                  $('#bed_no_id').html(result);
                }
            });

     }

/*$(document).ready(function (){
     package_val('<?php echo $form_data['package'];?>');
    patient_change('<?php echo $form_data['patient_type'];?>');

    room_no_select('<?php echo $form_data['room_id'];?>','<?php echo $form_data['room_no_id'];?>');
    select_no_bed('<?php echo $form_data['room_no_id'];?>','<?php echo $form_data['bed_no_id'];?>')

});*/

$(document).ready(function ()
{
  package_val('<?php echo $form_data['package'];?>');
  <?php  if(!empty($form_data['patient_type'])){ ?>
  patient_change('<?php echo $form_data['patient_type'];?>');
  <?php }
  if(!empty($form_data['room_id']))
  {
   ?>
    room_no_select('<?php echo $form_data['room_id'];?>','<?php echo $form_data['room_no_id'];?>');
  <?php } ?>
  <?php  if(!empty($form_data['room_no_id'])) { ?>
  select_no_bed('<?php echo $form_data['room_no_id'];?>','<?php echo $form_data['bed_no_id'];?>')

  <?php } ?>
});

 function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
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
 $(document).ready(function()
{
       var simulation_id = $("#simulation_id :selected").val();
        find_gender(simulation_id);
 })

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
<script type="text/javascript">


function more_patient_info()
 {
     var txt = $(".more_content").is(':visible') ? 'More Info' : 'Less Info';
        $(".show_hide_more").text(txt);
        
   $("#patient_info").slideToggle();
 }

</script>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{

if((empty($package_list)) || (empty($room_type_list)) || (empty($simulation_list)) || (empty($room_no)) || (empty($bed_no)) || (empty($referal_doctor_list)) || (empty($assigned_doctor)) || (empty($attended_doctor)))
{
  
?>  

 
  $('#ipd_row_count1').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 

}
}
?>

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
$(document).ready(function() {
   $('#load_add_patient_category_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
  $('#load_add_authorize_person_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script>

<div id="ipd_row_count1" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          <?php if(empty($package_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Package Name is required.</span></p><?php } ?>
          <?php if(empty($room_type_list)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Room Type is required.</span></p>
          <?php } ?>
             <?php if(empty($room_no)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Room No. is required.</span></p>
          <?php } ?>
             <?php if(empty($bed_no)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Bed  No. is required.</span></p>
          <?php } ?>
              <?php if(empty($referal_doctor_list)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Referral Doctor is required.</span></p>
          <?php } ?>
              <?php if(empty($assigned_doctor)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Assigned Doctor is required.</span></p>
          <?php } ?>
              <?php if(empty($attended_doctor)) { ?>
           <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Attended Doctor is required.</span></p>
          <?php } ?>
          
          </div>
        </div>
      </div>  
    </div>
    
    
    
    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking_recipt"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_mlc" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/mlc_print"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
    <div id="confirm_admission_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_card"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_discharge" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure For Discharge?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  id="yes" onClick="" >Yes</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">No</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_readmit" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure For Re-admit?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  id="yes" onClick="" >Yes</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">No</button>
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
<div id="load_add_authorize_person_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_patient_category_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.diagnosis_list').select2({
  ajax: {
    url: '<?=base_url('medication_chart/diagnosis_list')?>',
    dataType: 'json',
    data: function (params) {

        var queryParameters = {
            term: params.term
        }
        return queryParameters;
    },
        processResults: function (data) {
            return {
                results: $.map(data, function (item) {
                    return {
                        text: item.diagnosis,
                        id: item.id
                    }
                })
            };
        }
    // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
  }
});
var selectedTexts1 = "<?php echo $form_data['diagnosis']; ?>";
if(selectedTexts1 != "")
  selectedTexts = selectedTexts1.split(';');
else
  selectedTexts = [];

    // Fetch the corresponding IDs and set them as selected
    if (selectedTexts.length > 0) {
        $.ajax({
            url: '<?=base_url('ipd_booking/diagnosis_listText')?>',
            dataType: 'json',
            type : "post",
            data : {term:selectedTexts},
            success: function(data) {
                console.log(data);
                var selectedIds = [];
                $.each(data, function(index, item) {
                    // selectedIds.push(item.id);

                    // Create a new option if it doesn't exist
                    // if (!$('.diagnosis_list option[value="' + item.id + '"]').length) {
                        var newOption = new Option(item.diagnosis, item.id, true, true);
                        $('.diagnosis_list').append(newOption).trigger('change');
                    // }
                });
            }
        });
    }
</script>