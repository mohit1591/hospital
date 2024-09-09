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
<body onLoad="set_married(<?php echo $marital_status;?>);">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

<?php 

if($donor_id > 0)
{
  $reg="checked=checked";
  $new="";
}
else
{
  $new="checked=checked";
  $reg="";
}
?>
<input type="radio" value="New" <?php echo $new; ?>  name="donor_type" 
onclick="window.location.href='<?php echo base_url()."blood_bank/donor/add"; ?>'"  >New Donor
<input type="radio" value="Registered" name="donor_type" <?php echo $reg; ?> onclick="window.location.href='<?php echo base_url()."blood_bank/donor" ?>'"; >Registered Donor



<?php // print_r($donor_id); ?>

<div class="row">
  <form method="post" name="donor_reg_form" id="donor_reg_form" enctype="multipart/form-data" >
  <input type="hidden" name="donor_id" id="donor_id" value="<?php echo $donor_id; ?>">
  <div class="col-lg-4">
    <div class="">
 
      <div class="row mb-5">
        <div class="col-md-4"><b>Donor ID</b></div>
         <div class="col-md-8">
            <?php echo $reg_no; ?>
           <input type="hidden" name="donor_code" id="donor_code" value="<?php echo $reg_no; ?>" />
          </div>
      </div>
      
      <!-- /////////////////// -->
      <div class="row mb-5">
        <div class="col-md-4"><b>Donor Name <span class="star">*</span></b></div>
            <div class="col-md-6 pr-0">
              <select class="mr_blood m_mr alpha_space"  name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
                 <option value="">Select</option>
                  <?php
                   // $simulations_array = array('Mr','mR','mr','MR','Mr.','mR.','mr.','MR.');
                    if(!empty($simulation_list))
                    {
                      $x=1;
                      foreach($simulation_list as $simulation)
                      {
                        if($donor_data!="empty")
                        {
                          if($donor_data["simulation_id"]==$simulation->id)
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
              <input type="text" name="donor_name" id="donor_name" value="<?php if($donor_data!="empty") { echo $donor_data['donor_name']; } ?>" class="alpha_space mr-name m_name txt_firstCap"  autofocus/>
              <span id="donor_name_error"></span>
            </div>
            <div class="col-md-2">
                <a title="Add Simulation" style="margin-left:-15px;"  href="javascript:void(0)" onclick="simulation_modal()" class="btn-new move1"> <i class="fa fa-plus"></i> New</a>
            </div>
      </div>
      <!-- row -->
      <div class="row mb-5">
         <div class="col-md-4">
            <select name="relation_type"  class="w-90px">
            <?php 
              foreach($gardian_relation_list as $gardian_list) 
              { 
                if($donor_data!="empty")
                {
                  if($donor_data["relation_type"]==$gardian_list->id)
                            $select="selected=selected";
                           else
                            $select="";
                  echo '<option '.$select.' value="'.$gardian_list->id.'">'.$gardian_list->relation.'</option>';
                }        
                else
                {
                  echo '<option  value="'.$gardian_list->id.'">'.$gardian_list->relation.'</option>';
                }
              }
            ?>
          </select>
          </div>
          <div class="col-md-8">
            <select class="mr_blood m_mr" name="relation_simulation_id" id="relation_simulation_id">
              <option value="">Select</option>
                <?php
                 $selected_simulation = '';
                  foreach($simulation_list as $simulation)
                  {
                      if($donor_data!="empty")
                      {
                        if($donor_data["relation_simulation_id"]==$simulation->id)
                            $select="selected=selected";
                           else
                            $select="";
                        echo '<option value="'.$simulation->id.'" '.$select.'>'.$simulation->simulation.'</option>';
                      }
                      else
                      {
                        echo '<option value="'.$simulation->id.'" >'.$simulation->simulation.'</option>';
                      }
                  }
                ?> 
            </select> 
            <input type="text" value="<?php if($donor_data!='empty'){ echo $donor_data['relation_name']; } ?>" name="relation_name" id="relation_name" class="mr-name m_name"/>
          </div>
      </div>
      <!-- row -->
      <div class="row mb-5">
        <div class="col-md-4"><b>Mobile No.</b></div>
         <div class="col-md-8">
            <input type="text" name="" readonly="readonly" value="<?php ?>" class="country_code m_c_code" placeholder="+91"> 
           <input type="text" maxlength="10"  name="mobile_no"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number m_number" placeholder="eg.9897221234" value="<?php if($donor_data!='empty'){ echo $donor_data['mobile_no']; } ?>">
           <span id="mobile_error"></span>
          </div>
      </div>
      <!-- row -->

      <div class="row mb-5">
        <div class="col-md-4"><b>DOB</b></div>
         <div class="col-md-8">
              <input type="text" class="datepicker1" readonly="" name="dob" id="dob" value="<?php if($donor_data!='empty' && $donor_data['dob']!='0000-00-00' && $donor_data['dob']!='1970-01-01'){ echo date('d-m-Y',strtotime($donor_data['dob'])); } ?>"  onchange="showAge(this.value);"/> 
        </div>
      </div>
      <div class="row mb-5">
        <div class="col-md-4"><b>Age</b></div>
          <div class="col-md-8">
              <input type="text" name="age_y" id="age_y" onkeyup="validate_age('age_y','age_m','age_d');" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php if($donor_data!='empty'){ echo $donor_data['age_y']; } ?>"> Y &nbsp;
              <input type="text" name="age_m" id="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php if($donor_data!='empty'){ echo $donor_data['age_m']; } ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d"  class="input-tiny m_tiny numeric"  maxlength="2" value="<?php if($donor_data!='empty'){ echo $donor_data['age_d']; } ?>"> D
              <br/><span id="age_error" ></span>
          </div>
          
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Gender <span class="star">*</span></b></div>
          <div class="col-md-8" id="gender">
              <?php
                $male=""; $female=""; $other="";
                if($donor_data!='empty')
                { 
                    if($donor_data['gender']==1)
                      $male="selected=selected"; 
                    else if($donor_data['gender']==0)
                      $female="selected=selected"; 
                    else if($donor_data['gender']==2)
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
        <div class="col-md-4"><b>Email Id </b></div>
         <div class="col-md-8">
           <input type="text" name="donor_email" class="email_address m_input_default" value="<?php if($donor_data!='empty'){ echo $donor_data['donor_email']; } ?>" >
           <span id="email_error"></span>
          </div>
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Address 1</b></div>
         <div class="col-md-8">
           <input type="text" name="address" id="address" class="m_input_default address" maxlength="250" value="<?php if($donor_data!='empty'){ echo $donor_data['address']; } ?>">
         </div>
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Address 2</b></div>
         <div class="col-md-8">
             <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php if($donor_data!='empty'){ echo $donor_data['address2']; } ?>"/>
         </div>
      </div>
      <!-- row -->
     <div class="row mb-5">
        <div class="col-md-4"><b>Address 3</b></div>
         <div class="col-md-8">
          <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php if($donor_data!='empty'){ echo $donor_data['address3']; } ?>"/>
         </div>
      </div>
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
                    if($donor_data!='empty')
                    { 
                        if($donor_data['country_id']==$country->id)
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
                    if($donor_data!="empty")
                    {
                      if($donor_data['state_id']==$state->id)
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
                 // $city_list = city_list(20);
                   $city_list = city_list($form_data['state_id']);
                  
                  foreach($city_list as $city)
                  {
                    if($donor_data!="empty")
                    {
                      if($donor_data['city_id']==$city->id)
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
      <!-- row -->

      <div class="row mb-5">
       <div class="col-md-4"><b>Pin Code</b></div>
       <div class="col-md-8">
            <input type="text" name="pincode" id="pincode" maxlength="6" data-toggle="tooltip"  title="Pincode should be six numeric digit." class="numeric tooltip-text tool_tip" value="<?php if($donor_data!='empty'){ echo $donor_data['pincode']; } ?>">
           
        </div>
      </div>

        <div class="row mb-5">
         <div class="col-md-4"><b>Marital Status </b></div>
       
        <div class="col-md-8">
            <input type="radio" name="marital_status" value="1" <?php if($marital_status==1){ echo 'checked="checked"'; } ?> onclick="set_married(1);" > Married
            <input type="radio" name="marital_status" value="0" <?php if($marital_status==0){ echo 'checked="checked"'; }?> onclick="set_married(0);"> Unmarried
        </div>
      </div>

       <div class="row mb-5">
         <div class="col-md-4"><b>Marriage Anniversary</b></div>
          <div class="col-md-8">
              <input type="text" class="datepickerani" readonly="" name="anniversary" id="anniversary" value="<?php if($donor_data!='empty' && $donor_data['anniversary']!='0000-00-00' && $donor_data['anniversary']!='1970-01-01'){ echo date('d-m-Y',strtotime($donor_data['anniversary'])); } ?>" /> 
            </div>
      </div>

      <!-- /////////////////// -->
    </div>
  </div>
  <div class="col-lg-4">
    <div class="">
        
        <div class="row mb-5">
          <div class="col-md-4"><b>Blood Group</b></div>
           <div class="col-md-8">
               <select name="blood_group_id" id="blood_group_id">
                  <option value="">Select Blood Group</option>
                <?php
                  if($blood_groups!="empty")
                  {
                    foreach($blood_groups as $bg)
                    {
                      if($donor_data!="empty")
                      {  
                        if($donor_data['blood_group_id']==$bg->id)
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
           </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4"><b>Mode Of Donation</b></div>
           <div class="col-md-8">
            <select name='mode_of_donation' id='mode_of_donation' onchange="camp_list_show(this.value);" >
              <option value="">Select Donation Mode</option>
              <?php
                if($modes_of_donation!="empty")
                {
                  foreach($modes_of_donation as $modes)
                  {
                    if($donor_data!="empty")
                    {
                      if($donor_data['mode_of_donation']==$modes->id)
                        $mod="selected=seleceted";
                      else
                        $mod="";

                      echo '<option value='.$modes->id.' '.$mod.' >'.$modes->mode_of_donation.'</option>';  
                    }
                    else
                    {
                      echo '<option value='.$modes->id.' >'.$modes->mode_of_donation.'</option>';  
                    }
                    
                  }
                }
              ?>
              </select>
              <!-- <input type="radio" value="0" name="mode_of_donation">Camp
              <input type="radio" value="1" name="mode_of_donation" checked="checked" >Center -->
           </div>
        </div>

        <?php
          if($donor_data!="empty")
          {
            if($donor_data['mode_of_donation']==1)
              $style="display:block";
            else
              $style="display:none";
          }
          else
          {
            $style="display:none";
          }
        ?>

        <div class="row mb-5" style="<?php echo $style; ?>" id="camp_id_div">
          <div class="col-md-4"><b>Select Camp</b></div>
           <div class="col-md-7">
              <select name="camp_id" id="camp_id" >
                <option value="0" >Select Camp</option>
                <?php
                    if($camp_list!="empty")
                   {
                    foreach($camp_list as $camp)
                    {
                      if($donor_data!="empty")
                      {
                        if($donor_data['camp_id']==$camp->id)
                          $cmp="selected=selected";
                        else
                          $cmp="";

                        echo '<option value='.$camp->id.' '.$cmp.'  >'.$camp->camp_name.'</option>';  
                      }
                      else
                      {
                        echo '<option value='.$camp->id.' >'.$camp->camp_name.'</option>';
                      }
                    }
                   } 
                ?>
              </select>
           </div>
           
        </div>


        <div class="row mb-5">
          <div class="col-md-4"><b>Previous Donation Date</b></div>
           <div class="col-md-8">
              <input type="text" readonly value="<?php if(($donor_data!='empty') && (strtotime($donor_data['previous_donation_date'])  > 0 ) ) { echo date('d-m-Y',strtotime($donor_data['previous_donation_date'])); } ?>" class="datepicker" name="previous_donation_date" onchange="validate_donation_date(this.value);" >
              <span id="date_error"></span>
           </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4"><b>Reminder Service</b></div>
           <div class="col-md-7">
              <select name="reminder_service" >
                <option value="">Select Reminder Service</option>
                <?php
                  if($reminder_service!="empty")
                  {
                    foreach($reminder_service as $services)
                    {
                      if($donor_data!="empty")
                      {
                        if($donor_data['reminder_service_id']==$services->id)
                          $serv="selected=seleceted";
                        else
                          $serv="";
                        echo '<option value='.$services->id.' '.$serv.' >'.$services->preferred_reminder_service.'</option>';
                      }
                      else
                      {
                        echo '<option value='.$services->id.'>'.$services->preferred_reminder_service.'</option>';  
                      }
                      
                    }
                  }
                ?>
              </select>
           </div>
          
        </div>
        
  <?php
  $display='';
if(!empty($donor_data['other_id']))
{
  $display='display:block';
}
else
{
  $display='display:none';
}
   ?>
        <div class="row mb-5" id='other_div' style='<?php echo $display ;?>'>
          <div class="col-md-4"><b>Others</b></div>
           <div class="col-md-8">
              <input type="text" class='' value="<?php if($donor_data!='empty'){ echo $donor_data['other_post']; } ?>" name="other_post">

              
           </div>
        </div>
            
        <div class="row mb-5">
          <div class="col-md-4"><b>Height(cm)</b></div>
           <div class="col-md-8">
              <input type="text" class='price_float' value="<?php if($donor_data!='empty'){ echo $donor_data['height']; } ?>" name="height" numeric autocomplete="off">
           </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4"><b>Weight(kg)</b></div>
           <div class="col-md-8">
              <input type="text" class='price_float' value="<?php if($donor_data!='empty' && $donor_data['weight'] > 0 ){ echo $donor_data['weight']; } ?>" name="weight" autocomplete="off" numeric onkeyup="validate_weight(this.value);">
            <span id="weight_error"></span>  
           </div>
        </div>

        <div class="row mb-5">
          <div class="col-md-4"><b>Remarks</b></div>
           <div class="col-md-8">
              <textarea name="remark" id="remark" ><?php if($donor_data!="empty") 
              {  echo $donor_data['remark'];  } ?></textarea>
           </div>
        </div>
         

      <div class="row mb-5">
      <div class="col-md-4"> <strong>Donor Photo</strong></div>
        <div class="col-md-8">
        <div class="pat-col">
          <div class="pat-col-right-box">
          <?php
          $img_path = base_url('assets/images/photo.png');

         // if($donor_data!='empty' && $donor_data['profile_image'] > 0 ){ echo  $donor_data['profile_image']; }
          if($donor_data!='empty' && !empty($donor_id) && !empty($donor_data['profile_image']))
          {
             $img_path = ROOT_UPLOADS_PATH.'blood_bank/donor_profile/'.$donor_data['profile_image'];
          }  
          ?>
          <!-- <img id="pimg" src="<?php //echo $img_path; ?>" class="img-responsive"> -->
            <div class="photo">
              <img id="pimg" src="<?php echo $img_path; ?>" class="img-responsive">
          </div>
          </div>
          <div class="pat-col-right-box2">
         

          <?php
          if(isset($photo_error) && !empty($photo_error)){
          echo '<div class="text-danger">'.$photo_error.'</div>';
          }
          ?>
        <input name="old_img" value="<?php if($donor_data!='empty') {echo $donor_data['profile_image']; }?>" type="hidden">
          <input id="capture_img" name="capture_img" value="" type="hidden">
        </div>
            <input id="img-input" accept="image/*" name="photo" type="file">
        </div>
        </div>
        </div>

 
    </div>

     
  </div>
 
  <div class="col-lg-4">        
    <div class="row mb-5">
    <div class="col-md-4"><b>Donor General Information</b></div>

    <div class="col-md-8">
    <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
    <input type="hidden" name="old_general_form"  value="<?php if($donor_data!='empty') {echo $donor_data['document_name']; }?>"/>
    <input type="file" id="img-input2" accept="image/*" name="general_form" id="general_form" onclick="set_current_time('start_time');">
    <?php

    ?>
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
          $img_path_name = base_url('assets/images/photo.png');
          if($donor_data!='empty' && isset($donor_data['document_name'])&& $donor_data['document_name']!=''){
          $img_path_name = ROOT_UPLOADS_PATH.'blood_bank/donor_profile/'.$donor_data['document_name'];
          } 
          // $print_url = "'".base_url('sales_medicine/print_sales_report/'.$sales_medicine->id)."'"; 
          ?>

          <img id="pimg2" src="<?php echo $img_path_name; ?>" class="img-responsive" >
         <a class="btn-custom" id="print_id" style="float:right;" href="javascript:void(0)" onClick="hide_div(); printDiv('printdiv')" title="Print" ><i class="fa fa-print"></i> Print</a>
          </div>

        </div>
      <div class="col-md-3"></div>
      </div>
    </div>
  <div class="row mb-5">
  <div class="col-md-4"><b>Start Time</b></div>
  <div class="col-md-4">
  <input class="numeric datepicker3" type="text"  name="start_time" id="start_time" placeholder="Start Time" value="<?php if(($start_time!="") &&($start_time!='00:00:00')) { echo $start_time; } ?>">

  </div> 
  </div>


  <div class="row mb-5">
    <div class="col-md-8"><input type="submit" id="data_handler" name="submit" value="Submit" class="btn-update" >

    <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('blood_bank/donor'); ?>'">
      <i class="fa fa-sign-out"></i> Exit
      </button></div>
    
  </div>


  </div> <!-- blank -->
  
         </form>
</div>
</section> <!-- cbranch -->
  </div>
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
$(document).ready(function(){

  $('.datepicker3').datetimepicker({
      format: 'LT'
  });
   

});
function set_current_time(ref_id)
{
 
   
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor/calc_times');?>",
            data: {'flag':1},
            success: function(result) 
            {
              $("#"+ref_id).val(result);
            }
        });

  setTimeout(function()
  { 
    var start_time=$("#start_time").val();    
  }, 1500);
}

function hide_div()
{
 $('#print_id').css('display','none');
}
  function printDiv(divId) {
      var divContents = $("#printdiv").html();
      var printWindow = window.open('', '', 'height=400,width=800');
     
   $('#print_id').css('display','none');
      printWindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title>Donor General Information</title>');
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


function set_married(val)
 { 
    if(val==0)
    {
      $('#anniversary').attr("disabled", true);
      $('#anniversary').val('');
    }
    else
    {
      $('#anniversary').attr("disabled", false);
      
    }
 }
function showAge(dob_birth) 
{
  
  var now = new Date(); //Todays Date   
  var birthday = dob_birth
  birthday=birthday.split("-");   

  var dobMonth= birthday[1]; 
  var dobDay= birthday[0];
  var dobYear= birthday[2];

  var nowDay= now.getDate();
  var nowMonth = now.getMonth() + 1;  //jan=0 so month+1
  var nowYear= now.getFullYear();

  var ageyear = nowYear- dobYear;
  var agemonth = nowMonth - dobMonth;
  var ageday = nowDay- dobDay;
  if (agemonth < 0) {
       ageyear--;
       agemonth = (12 + agemonth);
        }
  if (nowDay< dobDay) {
      agemonth--;
      ageday = 30 + ageday;
      }
  var val = ageyear + "-" + agemonth + "-" + ageday;
    $('#age_y').val(ageyear);
    $('#age_m').val(agemonth);
    $('#age_d').val(ageday);
    //if(ageyear!='' && agemonth!='' && ageday!='')
    //{
      var y='age_y';
      var m='age_m';
      var d='age_d';
       validate_age(y,m,d);
   // }
   
}

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
    })



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





// Function to open datepicker
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  
  });
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
                  $("#data_handler").attr('disabled','true');
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
                  $("#data_handler").attr('disabled','true');
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
                  $("#data_handler").attr('disabled','true');
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


// Function to save Donor Details
$("#donor_reg_form").on("submit", function(event) { 
  event.preventDefault();  
  if(age_valid==1 && weight_valid==1 && donation_valid==1)
  {
    $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>blood_bank/donor/save_donor",
              data: new FormData(this),  
              contentType: false,      
              cache: false,            
              processData:false, 
              success: function(result)
              {    
                if(result.st==0)
                {
                  $("#age_error").html(result.age);
                  $("#mobile_error").html(result.mobile_no);
                  $("#email_error").html(result.email_error);
                  $("#donor_name_error").html(result.name);
                }
                else if(result.st==1)
                {
                  $("#age_error").html('');
                  $("#mobile_error").html('');
                  $("#email_error").html('');
                  $("#donor_name_error").html('');
                  flash_session_msg(result.msg);
                //window.location.href="<?php echo base_url(); ?>blood_bank/donor";
                   setTimeout(function () {
                  window.location.href = "<?php echo base_url(); ?>blood_bank/donor";
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



$('.datepicker1').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    startView: 2
  })

$('.datepickerani').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    startView: 2
})

$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

</script>
<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($simulation_list)))
{
  
?>  

 
  $('#blood_bank_count').modal({
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
    $('#blood_bank_count').modal('hide');
   /* $(this).hide();*/
});
</script>



<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="blood_bank_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-dismiss="modal" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
           <?php if(empty($simulation_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Simulation is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    </div>
<!--Div to load popups -->

</body>
</html>
