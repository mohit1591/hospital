<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<style>
  .receipentBox {
  float: left;
  width: 375px;
  height: auto;
  border: 1px solid #DDD;
  margin-top: 33px;
  }


</style>
<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

<?php 
if(!empty($source))
{
  $source=$source;
}
else
{
  $source=1;
}

if(!empty($reference_id))
{
  $reference_id=$reference_id;
}
else
{
  $reference_id=0;
}

if(!empty($get_type_data))
{

  if(($get_type_data=='4')&&($patient_id > 0))
  {
     $reg_pat="checked=checked";
     $new="";
     $reg_ot="";
     $reg_ipd="";
  }
  else if(($get_type_data=='2')&&($patient_id > 0))
  {
     $reg_ipd="checked=checked";
     $reg_pat="";
     $reg_ot="";
     $new="";

  }
  else if(($get_type_data=='3')&&($patient_id > 0))
  {
     $reg_ot="checked=checked";
     $reg_ipd="";
     $reg_pat="";
     $new="";

  }
 else if(($get_type_data=='1')&&($patient_id > 0))
  {
     $new="";
     $reg_ipd="";
     $reg_pat="checked=checked";
     $reg_ot="";
  }


}
else
  {
     $new="checked=checked";
     $reg_ipd="";
     $reg_pat="";
     $reg_ot="";
  }

?>
<input type="radio" value="New" <?php echo $new; ?>  name="donor_type" 
onclick="window.location.href='<?php echo base_url()."blood_bank/recipient/add"; ?>'"  > &nbsp;New Recipient
<input type="radio" value="Registered" name="donor_type" <?php echo $reg_pat; ?> onclick="window.location.href='<?php echo base_url()."patient" ?>'"; >&nbsp; Registered Patient Recipient
<?php
if(in_array('121',$users_data['permission']['section'])) 
{
?>
<input type="radio" value="Registered" name="donor_type" <?php echo $reg_ipd; ?> onclick="window.location.href='<?php echo base_url()."ipd_booking" ?>'"; >&nbsp; Registered IPD Recipient
<?php
}
?>
<?php
if(in_array('51',$users_data['permission']['section'])) 
{
  ?>
<input type="radio" value="Registered" name="donor_type" <?php echo $reg_ot; ?> onclick="window.location.href='<?php echo base_url()."ot_booking" ?>'"; >Registered OT Recipient
<?php
}
?>
<?php // print_r($donor_id); ?>

<div class="row">
  <form method="post" name="recipient_reg_form" id="recipient_reg_form" enctype="multipart/form-data">
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id; ?>">
   <input type="hidden" name="reference_id" id="reference_id" value="<?php  echo $reference_id; ?>">
    <input type="hidden" name="patient_id_res" id="patient_id_res" value="<?php echo $recipient_id; ?>">
   <input type="hidden" name="recipient_source" id="recipient_source" value="<?php echo $source; ?>">
  <div class="col-lg-4">
    <div class="">
      <!-- /////////////////// -->

    <div class="row mb-5">
        <div class="col-md-4"><b>Recipient Id</b></div>
        <div class="col-md-7 pr-0">
        <?php if(isset($patient_code)){ echo $patient_code;}?>
        </div>
    </div>
      <div class="row mb-5">
        <div class="col-md-4"><b>Recipient Name<span class="star">*</span></b></div>
            <div class="col-md-7 pr-0">
              <select class="mr m_mr alpha_space"  name="simulation_id" id="simulation_id" onchange="find_gender(this.value)" style="width:61px;">
                 <option value="">Select</option>
                  <?php
                   // $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
                    if(!empty($simulation_list))
                    {
                      $x=1;
                      foreach($simulation_list as $simulation)
                      {
                        if($pat_data!="empty")
                        {
                          if($pat_data["simulation_id"]==$simulation->id)
                            $select="selected=selected";
                           else
                            $select="";
                          
                          echo '<option  '.$select.' value="'.$simulation->id.'" >'.$simulation->simulation.'</option>';
                        }
                        else
                        {
                          if($x==2)
                              $selected="selected=selected";
                            else
                              $selected="";
                          
                          echo '<option '.$selected.' value="'.$simulation->id.'" >'.$simulation->simulation.'</option>'; 
                        }
                        $x++;
                      }
                    }
                  ?> 
              </select> 
              <input type="text" name="patient_name" id="patient_name"  value="<?php if($pat_data!="empty") { echo $pat_data['patient_name']; } ?>" class="mr-name m_name txt_firstCap"  autofocus/>
              <span id="patient_name_error"></span>
            </div>
           
      </div>
    <div class="row m-b-5">
    <div class="col-xs-4">
      <strong> 
      <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);">
      <?php foreach($gardian_relation_list as $gardian_list) 
      {?>
      <option value="<?php echo $gardian_list->id;?>" <?php if(isset($pat_data['relation_type']) && $pat_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
      <?php }?>
      </select>

      </strong>
    </div>
      <div class="col-xs-8">
        <select class="mr m_mr" name="relation_simulation_id" id="relation_simulation_id">
          <option value="">Select</option>
          <?php
             $selected_simulation = '';
              foreach($simulation_list as $simulation)
              {
                
                    $selected_simulation='';
                  if(isset($pat_data['relation_simulation_id']) && $simulation->id==$pat_data['relation_simulation_id'])
                  {
                       $selected_simulation = 'selected="selected"';
                  }
                       
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              
            }
            ?> 
        </select> 
        <input type="text" value="<?php if(isset($pat_data['relation_name'])){ echo $pat_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
      </div>
    </div> <!-- row -->
      
      <div class="row mb-5">
        <div class="col-md-4"><b>Mobile No.</b></div>
         <div class="col-md-8">
            <input type="text" name="" readonly="readonly" value="<?php ?>" class="country_code m_c_code" placeholder="+91"> 
           <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number m_number" placeholder="eg.9897221234" value="<?php if($pat_data!='empty'){ echo $pat_data['mobile_no']; } ?>">
           <span id="mobile_error"></span>
          </div>
      </div>
      <!-- row -->
      <div class="row mb-5">
        <div class="col-md-4"><b>Age</b></div>
          <div class="col-md-8">
              <input type="text" name="age_y" id="age_y" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php if($pat_data!='empty'){ echo $pat_data['age_y']; } ?>"> Y &nbsp;
              <input type="text" name="age_m" id="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php if($pat_data!='empty'){ echo $pat_data['age_m']; } ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d"  class="input-tiny m_tiny numeric"  maxlength="2" value="<?php if($pat_data!='empty'){ echo $pat_data['age_d']; } ?>"> D
              <br/><span id="age_error" ></span>
          </div>
          
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Gender <span class="star">*</span></b></div>
          <div class="col-md-8" id="gender">
              <?php
                $male=""; $female=""; $other="";
                if($pat_data!='empty')
                { 
                    if($pat_data['gender']==1)
                      $male="selected=selected"; 
                    else if($pat_data['gender']==0)
                      $female="selected=selected"; 
                    else if($pat_data['gender']==2)
                      $other="selected=selected"; 
                }
              ?>
              <input type="radio" name="gender" value="1" <?php echo $male; ?> > Male &nbsp;
              <input type="radio" name="gender" value="0" <?php echo $female; ?> > Female
              <input type="radio" name="gender" value="2" <?php echo $other; ?> > Others
          </div>
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Email Id</b></div>
         <div class="col-md-8">
           <input type="text" name="patient_email" class="email_address m_input_default" value="<?php if($pat_data!='empty'){ echo $pat_data['patient_email']; } ?>" >
           <span id="patient_email_error"></span>
          </div>
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Address 1</b></div>
         <div class="col-md-8">
           <input type="text" name="address" id="address" class="m_input_default address" maxlength="250" value="<?php if($pat_data!='empty'){ echo $pat_data['address']; } ?>">
         </div>
      </div>
      <div class="row mb-5">
        <div class="col-md-4"><b>Address 2</b></div>
         <div class="col-md-8">
           <input type="text" name="address_second" id="address_second" class="m_input_default address" maxlength="250" value="<?php if($pat_data!='empty'){ echo $pat_data['address2']; } ?>">
         </div>
      </div>
      <div class="row mb-5">
        <div class="col-md-4"><b>Address 3</b></div>
         <div class="col-md-8">
           <input type="text" name="address_third" id="address_third" class="m_input_default address" maxlength="250" value="<?php if($pat_data!='empty'){ echo $pat_data['address3']; } ?>">
         </div>
      </div>
       

   
      <!-- row -->
    
      <!-- row -->
    
    
      
     <div class="row mb-5">
        <div class="col-md-4"><b>Country</b></div>
         <div class="col-md-8">
           <select name="country_id" id="countrys_id" class="m_input_default" onchange="return get_state(this.value);">
              <option value="">Select Country</option>
              <?php
              if(!empty($country_list))
              {
                
                foreach($country_list as $country)
                {
                    if($pat_data!='empty')
                    { 
                        if($pat_data['country_id']==$country->id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$country->id.'" '.$select.'>'.$country->country.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$country->id.'" >'.$country->country.'</option>';
                    }
                }
              }
              ?> 
            </select> 
         </div>
      </div>

      <!-- row -->
      <div class="row mb-5">
         <div class="col-md-4"><b>State</b></div>
         <div class="col-md-8">
           <select name="state_id" id="states_id" class="m_input_default" onchange="return get_city(this.value)">
            <option value="">Select State</option>
            <?php
              $state_list = state_list(99); 
              if(!empty($state_list))
              {   
                  foreach($state_list as $state)
                  {  
                    if($pat_data!="empty")
                    {
                      if($pat_data['state_id']==$state->id)
                          $selected="selected=selected";
                        else
                          $selected="";
                      echo '<option value='.$state->id.' '.$selected.' >'.$state->state.'</option>';
                    }
                    else
                    {  
                     echo '<option value='.$state->id.'>'.$state->state.'</option>';
                    }
                  }
              }
            ?>
          </select>
         </div>
       </div>
       <!-- row -->
        <div class="row mb-5">
         <div class="col-md-4"><b>City</b></div>
         <div class="col-md-8">
           <select name="citys_id" class="m_input_default" id="citys_id">
              <option value="">Select City</option>
              <?php
                  $city_list = city_list(20);
                  
                  foreach($city_list as $city)
                  {
                    if($pat_data!="empty")
                    {
                      if($pat_data['city_id']==$city->id)
                          $select="selected=selected";
                        else
                          $select="";
                      echo '<option value='.$city->id.' '.$select.' >'.$city->city.'</option>';  
                    }
                    else
                    {
                      echo '<option value='.$city->id.'>'.$city->city.'</option>';
                    }
                    
                  }
              ?>
            </select> 
         </div>
      </div>

      <div class="row mb-5">
         <div class="col-md-4"><b>PIN Code</b></div>
         <div class="col-md-8">
            <input type="text" name="pincode" id="pincode" maxlength="6" data-toggle="tooltip"  title="Pincode should be six numeric digit." class="numeric tooltip-text tool_tip" value="<?php if($pat_data!='empty'){ echo $pat_data['pincode']; } ?>">
         </div>
      </div>
       
      <!-- row -->
      <!-- /////////////////// -->
    </div>
  </div>
  
     <div class="col-lg-8">
    <div class="">
        
        <div class="row mb-5">
        <div class="col-md-4"><b>Blood Group of Recipient <span class="star">*</span></b></div>
         <div class="col-md-8">
             <select name="blood_group_id" id="blood_group_id">
                  <option value="">Select Blood Group</option>
                <?php
                  if($blood_groups!="empty")
                  {
                    foreach($blood_groups as $bg)
                    {
                      if($pat_data!="empty")
                      {  
                        if($pat_data['blood_group_id']==$bg->id)
                            $bgselect="selected=selected";
                          else
                            $bgselect="";

                        echo '<option value='.$bg->id.' '.$bgselect.' >'.$bg->blood_group.'</option>';
                      }
                      else
                      {
                        echo '<option value='.$bg->id.'>'.$bg->blood_group.'</option>'; 
                      }
                    }
                  }
                ?>
               </select>
               <span id="blood_group_id_error"></span>
         </div>
      </div>
        <div class="row mb-5">
        <div class="col-md-4"><b>Quantity</b></div>
         <div class="col-md-8">
          <input type="text" name="volume" id="volume" class="numeric number m_number" maxlength="255" value="<?php if($pat_data!='empty'){ echo $pat_data['volume']; } ?>"/> Unit
         </div>
      </div>
      
     <!--  <div class="row mb-5">
        <div class="col-md-4"><b>Clinical Diagnosis</b></div>
         <div class="col-md-8">
          <input type="text" name="clinical_diagnosis" id="clinical_diagnosis" class="address" maxlength="255" value="<?php //if($pat_data!='empty'){ echo $pat_data['clinical_diagnosis']; } ?>"/>
         </div>
      </div> -->

      <input type="hidden" name="clinical_diagnosis" id="clinical_diagnosis" class="address" maxlength="255" value="<?php if($pat_data!='empty'){ echo $pat_data['clinical_diagnosis']; } ?>"/>
      <!-- row -->
      <input type="hidden" name="referred_by" value="0" >
      <input type="hidden" name="referred_by" value="1">
        <div class="row mb-5" id="hospital_div" >
        <div class="col-md-4"><b>Hospital Name</b></div>
         <div class="col-md-8">
                 <select name="hospital_id" id="hospital_id" onChange="return get_other(this.value)">
                  <option value="">Select Hospital</option>
                <?php
                  if($referal_hospital_list!="empty")
                  {
                    //print_r($referal_hospital_list);

                    foreach($referal_hospital_list as $hospital_list)
                    {
                      if($pat_data!="empty")
                      { 
                      if($pat_data['referred_by']==1)
                      { 
                        if($pat_data['hospital_id']==$hospital_list['id'])
                            $hosselect="selected=selected";
                          else
                            $hosselect="";
                        }
                        else
                        {
                           $hosselect="";
                        }
                        echo '<option value='.$hospital_list['id'].' '.$hosselect.' >'.$hospital_list['hospital_name'].'</option>';
                      }
                      else
                      {
                        echo '<option value='.$hospital_list['id'].'>'.$hospital_list['hospital_name'].'</option>'; 
                      }
                    }
                  }
                ?>
               </select>
         </div>
      </div>
      <div class="row mb-5" id="doctors_div" <?php //echo $dc_dis; ?>>
        <div class="col-md-4"><b>Doctor Name</b></div>
         <div class="col-md-8">
             <select name="doctor_id" id="doctor_id">
                  <option value="">Select Doctor</option>
                <?php
                  if($referal_doctor_list!="empty")
                  {
                    foreach($referal_doctor_list as $dr)
                    {
                      if($pat_data!="empty")
                      {  
                        if($pat_data['doctor_id']==$dr->id)
                            $drselect="selected=selected";
                          else
                            $drselect="";

                        echo '<option value='.$dr->id.' '.$drselect.' >'.$dr->doctor_name.'</option>';
                      }
                      else
                      {
                        echo '<option value='.$dr->id.'>'.$dr->doctor_name.'</option>'; 
                      }
                    }
                  }
                ?>
               </select>
         </div>
      </div>

      <div class="row mb-5">
        <div class="col-md-4"><b>Ward/Bed</b></div>
         <div class="col-md-8">
          <input type="text" name="ward_bed_no" id="ward_bed_no" class="address" maxlength="255" value="<?php if($pat_data!='empty' && $pat_data['ward_bed_no']!=0){ echo $pat_data['ward_bed_no']; } ?>"/>
         </div>
      </div>
        <?php $i=0;
        //print_r($component_detail);die; 
        if(isset($component_detail) && !empty($component_detail) && $component_detail!="empty")

        { 
          foreach ($component_detail  as $component_details)
          {

          ?>
          <div id="filed_name_new_<?php echo $i; ?>">
          <div class="row mb-5">
            <div class="col-md-4"><b>Component</b><span class="star">*</span></div>
              <div id="component_div_<?php echo $i; ?>">
                <div class="col-md-7">

                <select name="comp_wise[<?php echo $i; ?>][comp_name][]" class="w-100px" id="comp_name_<?php echo $i; ?>"> 
                <option value=""> Select Component </option>
                <?php if(!empty($component_list))
                { 
                  foreach($component_list as $comp_name){?>
                  <option value="<?php echo $comp_name->id; ?>" <?php if($component_detail!="empty" && $comp_name->id==$component_details->component_id){ echo 'selected';}?>><?php echo $comp_name->component; ?></option>

                  <?php 
                  } 
                } ?>
                </select>CP
                <input autocomplete="off" name="comp_wise[<?php echo $i; ?>][comp_qty][]" value="<?php if($component_detail!="empty"){echo $component_details->qty;}?>" id="comp_qty_<?php echo $i; ?>" class="w-40px comp_qty ui-autocomplete-input " placeholder="Qty" type="text">Qty

                <input autocomplete="off" name="comp_wise[<?php echo $i; ?>][comp_unite][]" value="<?php if($component_detail!="empty"){echo $component_details->comp_unite;}?>" id="comp_unite_<?php echo $i; ?>" class="w-40px comp_qty ui-autocomplete-input" placeholder="Unite" type="text">Unite


                <input autocomplete="off" name="comp_wise[<?php echo $i; ?>][check_type][]"  id="check_type_<?php echo $i;?>" <?php if($component_details->lc_check_status==1 && $component_detail!="empty"){ echo 'checked';} else{echo '';}?> value="1" type="checkbox">LC
                <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(<?php echo $i; ?>);">
                <i class="fa fa-plus"></i>
                </a>

                 <a href="javascript:void(0)" onclick=" return remove_field(<?php echo $i; ?>);"  class="btn-new"> <i class="fa fa-minus"></i></a>
                <div class="text-danger field_s" id="field'">
                </div>
              </div>
            </div>
        </div>
        </div>
       <?php $i++;} }
        else
        {?>
        <div class="row mb-5">
          <div class="col-md-4"><b>Component</b><span class="star">*</span></div>
            <div id="component_div_0">
              <div class="col-md-7">

                <select name="comp_wise[0][comp_name][]" class="w-100px" id="comp_name_0"> 
                <option value=""> Select Component </option>
                <?php if(!empty($component_list))
                { 
                  foreach($component_list as $comp_name){?>
                  <option value="<?php echo $comp_name->id; ?>"><?php echo $comp_name->component; ?></option>

                  <?php 
                  } 
                } ?>
                </select>CP
                <input autocomplete="off" name="comp_wise[0][comp_qty][]" value="" id="comp_qty_0" class="w-40px comp_qty ui-autocomplete-input numeric" placeholder="Qty" type="text">Qty
                
                <input autocomplete="off" name="comp_wise[0][comp_unit][]" value="" id="comp_unit_0" class="w-40px comp_unit ui-autocomplete-input" placeholder="Unite" type="text">Unite

                <input autocomplete="off" name="comp_wise[0][check_type][]" value="1" id="check_type_0"  type="checkbox">LC
                <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(0);">
                <i class="fa fa-plus"></i>
                </a>
                <div class="text-danger field_s" id="field'">
                </div>
              </div>
            </div>
        </div>
        <?php }?>
     
        
     <div class="add_more_filed">
     </div>

     <div class="row mb-5">
          <div class="col-md-4"><b>Requirement Date  <span class="star">*</span></b></div>
           <div class="col-md-8">
              <input type="text" readonly value="<?php if(($pat_data!='empty') && (strtotime($pat_data['requirement_date'])  > 0 ) ) { echo date('d-m-Y',strtotime($pat_data['requirement_date'])); }?>" class="datepicker" name="requirement_date">
              <span id="requirement_date_error"></span>
           </div>
        </div>
          <div class="row mb-5">
          <div class="col-md-4"><b>Requirement Time</b></div>
          <?php 
           $require_time='';
          if(($pat_data!='empty') && (strtotime($pat_data['require_time'])  > 0 ) ) 
          { 
            if((!empty($pat_data['require_time'])) && ($pat_data['require_time']!='00:00:00'))
            {
            $require_time= date('h:i A',strtotime($pat_data['require_time'])); 
            }
                    
          if($pat_data['require_time']=='00:00:00')
          {
           $require_time='';

          }
        }
            ?>
           <div class="col-md-8">
             <input type="text" name="require_time" id="require_time" class="w-95px require_time" placeholder="" value="<?php echo $require_time ?>">
           </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4"><b>Blood Requisition Form</b></div>
         
          <div class="col-md-8">
            <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
            <input type="hidden" name="old_requisitation_form"  value="<?php //echo $form_data['old_requisitation_form']; ?>" />
            <input type="file" id="img-input2" accept="image/*" name="requisitation_form" id="requisitation_form">
              <?php
              //if(isset($photo_error_right) && !empty($photo_error_right)){
              //echo '<div class="text-danger">'.$photo_error_right.'</div>';
              //}
              ?>
          </div>

          
        </div>

        <div class="row mb-5">
         <div class="col-md-4">
         <?php 
        if($recipient_id>0)
        {

        ?>
          <input type="submit" id="data_handler" name="submit" value="Update" class="btn-update">
        <?php
        }
        else
        {
        ?>

        <!-- <input type="submit" id="data_handler" name="submit" value="Submit" class="btn-update" > -->
          <input type="submit" id="data_handler" name="submit" value="Save" class="btn-update">
        <?php
        }
        ?>
          <a href="<?php echo base_url();?>blood_bank/recipient" class="btn-update" style="text-decoration:none!important;color:#FFF;padding:8px 2em;"><i class="fa fa-sign-out"></i> Exit</a>
         </div>
         <div class="col-md-8">
        
          </div>
        </div>


        
        <div class="row mb-5" id="printdiv">

         <div class="col-md-4">
         </div>
         <div class="col-md-8">
           <div class="col-md-9 frm_s">
           <div class="rec-box">
          <?php
          //print '<pre>'; print_r($pat_data);
          $img_path = base_url('assets/images/photo.png');
           if($pat_data!='empty' && isset($pat_data['document_name'])&& $pat_data['document_name']!=''){
           $img_path = ROOT_UPLOADS_PATH.'blood_bank/recipent_document/'.$pat_data['document_name'];
          } 
         // $print_url = "'".base_url('sales_medicine/print_sales_report/'.$sales_medicine->id)."'"; 
          ?>
          <img id="pimg2" src="<?php echo $img_path; ?>" class="img-responsive" style="margin: 0px auto;" >


          <a class="btn-custom" id="print_id" style="float:right;" href="javascript:void(0)" onClick="hide_div(); printDiv('printdiv');show_div();" title="Print" ><i class="fa fa-print"></i> Print</a>
          </div>

          </div>
          <div class="col-md-3"></div>
        </div>
        

        </div>
    </div>

  </div>
 
  </form>
  <div class="col-lg-4"></div> <!-- blank -->
</div>


  
 


</section> <!-- cbranch -->
  </div>
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">

function hide_div()
{
 $('#print_id').css('display','none');
}
  function printDiv(divId) {
      var divContents = $("#printdiv").html();
      var printWindow = window.open('', '', 'height=400,width=800');
      // var style_s ="<style>#printdiv { background: white;display: block; margin: 1em auto 0;margin-bottom: 0.5cm;box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);}#printdiv{ width: 21cm;height: 25.7cm;  padding: 3em;font-size:13px; }    size: auto;   /* auto is the initial value */margin: 0;}</style>";
   $('#print_id').css('display','none');
      printWindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title>Blood Requisition Form</title>');
        //var scr= $('#print_id').css('display','none');
      printWindow.document.write('</head><body onLoad="style_css();" >');
      printWindow.document.write(divContents);
    
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
  }
  function show_div()
  {
   $('#print_id').css('display','block');
  }
  function readURL2(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
    $('#pimg2').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
  }
  }
    $("#img-input2").change(function(){
  readURL2(this);
  });
<?php
if(!empty($component_detail))
{
  $total_num = count($component_detail);
  echo 'var divSize = '.$total_num.';';
}
else
{
  echo 'var divSize = $(".add_more_filed > div").size()+1;';
}
?>
function add_more_field()
{

      var my_div = '';  
      var option_test='<select name="comp_wise['+divSize+'][comp_name][]" class="w-100px" id="comp_name_'+divSize+'"><option value=""> Select Component </option>';
      <?php if(!empty($component_list))
      { 
     foreach($component_list as $comp_name){?>
      option_test+='<option value="<?php echo $comp_name->id; ?>"><?php echo $comp_name->component; ?></option>';


      <?php } } ?>
    option_test+='</select>';
   
   my_div+= '<div class="row mb-5" id="filed_name_new_'+divSize+'"><div class="col-md-4"> </div> <div class="col-md-7">';
    my_div+=option_test;
     my_div+='CP <input autocomplete="off" name="comp_wise['+divSize+'][comp_qty][]" value="" id="comp_qty_'+divSize+'""" class="w-40px comp_qty ui-autocomplete-input numeric" placeholder="Qty" type="text">Qty     <input autocomplete="off" name="comp_wise['+divSize+'][comp_unite][]" value="" id="comp_unite_'+divSize+'""" class="w-40px comp_qty ui-autocomplete-input" placeholder="Unite" type="text">Unite <input autocomplete="off" name="comp_wise['+divSize+'][check_type][]" value="1" id="check_type_'+divSize+'" type="checkbox" >LC';
     my_div+='<a href="javascript:void(0)" onclick=" return remove_field('+divSize+');"  class="btn-new"> <i class="fa fa-minus"></i></a>';

     my_div+='<div class="text-danger" id="field_name_error_'+divSize+'"></div> </div></div>';
      var fields =[];
      
      if(divSize=='0'){
      $('.add_more_filed').html(my_div);
      }
      else{
      $('.add_more_filed').append(my_div);
      }
      divSize++;
     
}
function remove_field(n)
{
   $('#filed_name_new_'+n).html('');
   $('#filed_name_new_'+n).remove('');
   $('#filed_name_new_'+n+' input').remove();
}


// Function to open datepicker
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
  });
$('#require_time').datetimepicker({
     format: 'LT'
  });

function get_other(id)
{
  //var test = $(this).val();
  //alert(test);

}

$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();

      if(test==0)
      {
        $("#hospital_div").hide();
        $("#doctors_div").show();
        
      }
      else if(test==1)
      {
          $("#doctors_div").hide();
          $("#hospital_div").show();
          
          
      }
        
    });
});

var setting_validation=1;
var age_valid=1;
var weight_valid=1; 
var donation_valid=1;
  
// To open simulation popup
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
// To open simulation popup

// Function to change gender according to simulation
function find_gender(id)
{
  if(id!=='')
  {
    $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result)
    {
      if(result!==''){  $("#gender").html(result);   }
    })
  }
}
// Function to change gender according to simulation


// Function to get state and city
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
// Function to get state and city



// Functions to Focus on popups
$(document).ready(function() {
  $('#load_add_simulation_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
// Function to focus on popups



// Function to load at document ready
$(document).ready(function()
{
  var simulation_id = $("#simulation_id :selected").val();
  find_gender(simulation_id);
});
// Function to load at document ready



// function to open datepicker


//Function to show hide camp list
function camp_list_show(val)
{
  if(val==1)
  {
    $("#camp_id_div").css('display','block');
    $("#camp_id").val(0);
  }
  else
  {
    $("#camp_id_div").css('display','none');
    $("#camp_id").val(0);
  }
}
// function to show hide camp list


// Function to validate criteria 

function validate_age(y,m,d)
{
  var years=$("#"+y).val();
  var months=$("#"+m).val();
  var days=$("#"+d).val();
  $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>blood_bank/donor/common_setting_validation", 
              data: {data:years,flag:1},  
              success: function(result)
              { 
                if(result.st==0)   
                { 
                  $("#age_error").html(result.msg);
                  age_valid=0;
                  //$("#data_handler").attr('disabled','true');
                }
                else
                {
                  $("#age_error").html('');
                  age_valid=1;
                  $("#data_handler").removeAttr('disabled');
                }
              }
          });

}


function validate_weight(value)
{
  $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>blood_bank/donor/common_setting_validation", 
              data: {data:value,flag:2},  
              success: function(result)
              { 
                if(result.st==0)   
                { 
                  $("#weight_error").html(result.msg);
                  weight_valid=0;
                  //$("#data_handler").attr('disabled','true');
                }
                else
                {
                  $("#weight_error").html('');
                  weight_valid=1;
                  $("#data_handler").removeAttr('disabled');
                }
              }
          });
}



function validate_donation_date(value)
{
  $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>blood_bank/donor/common_setting_validation", 
              data: {data:value,flag:3},  
              success: function(result)
              { 
                if(result.st==0)   
                { 
                  $("#date_error").html(result.msg);
                  donation_valid=0;
                  //$("#data_handler").attr('disabled','true');
                }
                else
                {
                  $("#date_error").html('');
                  donation_valid=1;
                  $("#data_handler").removeAttr('disabled');
                }
              }
          });  
}
// Function to validate criteria


// Function to save recipient Details
$("#recipient_reg_form").on("submit", function(event) { 
  event.preventDefault();  
    var arr1 = [];
    var str_pass='0';
    if($('#comp_name_0').val()=='')
    {

      var comp_name ='component name';
      var comas=',';
    }
    else
    {
      var comas='';
      var comp_name ='';
    }
    if($('#comp_qty_0').val()=='')
    {

      var comp_error ='component qty';
      if(comp_name=='')
      {

        var comas='';
      }
      else
      {
       var comas=',';
      }
      
    }
    else
    {

      var comp_error ='';
      var comas='';
    }
   
    

    if(comp_name!=''||comp_error!='')
    {

      $('.field_s').html(''+comp_name+comas+comp_error+' field is required');
      //exit;
       var error=1;
    }

    // <?php
    //   if(!empty($component_detail))
    //   {
    //   $total_num = count($component_detail);
    //     echo 'var divSize_s = '.$total_num.';';
    //   }
    //   else
    //   {
    //     echo 'var divSize_s = $(".add_more_filed > div").size()+1;';
    //   }
    // ?>
    
    for(var i=1;i<=divSize;i++)
    {
      
      if($("#filed_name_new_"+i).html()!='')
      {
        if(str_pass=='')
        {
          str_pass=i;
        }
        else
        {
          if($('#comp_name_'+i).val()=='')
          {

            var comp_name ='component name';
            var comas=',';
          }
          else
          {
            var comas='';
            var comp_name ='';
          }
          if($('#comp_qty_'+i).val()=='')
          {

           var comp_error ='component qty';
          if(comp_name=='')
          {

            var comas='';
          }
          else
          {
            var comas=',';
          }

          }
          else
          {

            var comp_error ='';
            var comas='';
          }
        
         
          if(comp_name!=''||comp_error!='')
          {
          
            $('#field_name_error_'+i).html(''+comp_name+comas+comp_error+' field is required');
              var error=1;
          }
          str_pass=str_pass+","+i;
        }
      }
      
    }
    if(error==1)
    {
      return false;
    }
    
    
   //$(':input[id=data_handler]').prop('disabled', true); 
    
  if(age_valid==1)
  {
    $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>blood_bank/recipient/save_recipient", 
              data: new FormData(this),  
              processData: false,
              contentType: false,     
              cache: false,            
              success: function(result)
              {    
                if(result.st==0)
                {
                  $("#age_error").html(result.age);
                  $("#mobile_error").html(result.mobile_no);
                  $("#patient_email_error").html(result.patient_email);
                  $("#patient_name_error").html(result.patient_name);
                  $("#blood_group_id_error").html(result.blood_group_id);
                  $("#bag_id_error").html(result.bag_id);
                  $("#requirement_date_error").html(result.requirement_date);
                }
                else if(result.st==1)
                {
                  $("#age_error").html('');
                  $("#mobile_error").html('');
                  $("#patient_email_error").html('');
                  $("#patient_name_error").html('');
                  $("#blood_group_id_error").html('');
                  $("#blood_group_id_error").html('')
                  $("#requirement_date_error").html('')
                  flash_session_msg(result.msg);
                  setTimeout(function () {
                  window.location.href = "<?php echo base_url(); ?>blood_bank/recipient";
                  }, 900); 

                  
                }

              }
          });
  }
  else
  {
    flash_session_msg('Please Fill Form Details');
  }
});

// Function to save Donor Details

</script>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($blood_groups)) || (empty($simulation_list)) || (empty($bag)))
{
  
?>  

 
  $('#recipient_count').modal({
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
$("button[data-number=4]").click(function(){
    $('#recipient_count').modal('hide');
   /* $(this).hide();*/
});
</script>


<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="recipient_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($blood_groups)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Blood Group is required.</span></p><?php } ?>
           <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
         <?php if(empty($bag)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Bag Type is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    </div>
<!--Div to load popups -->

</body>
</html>
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
