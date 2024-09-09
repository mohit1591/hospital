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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<style>
 .dental-chart{padding:0.5em;}
  ul.dental-tab{list-style: none;padding: 0px;margin: 0 auto;}
  ul.dental-tab li{float:left;padding:7px 17px!important;background: #629c44;text-align:center;margin-left:4px;border-radius:4px;box-shadow: 2px 2px #ccc;}
  ul.dental-tab li a{text-decoration: none;color:#fff;}
  ul.dental-tab li:hover{background: #4e7c36;color:#fff;}
  ul.dental-tab .active{background: #8dc171;}
  

  .box-left{padding:1em;width:100%;}
  .box-left select{width:100%;padding: 4px;margin-bottom: 3px;}
  .box-left input{width:100%;margin-bottom: 3px;}
  .box-left button{width:100%;border: none;text-align: center;padding: 3px;margin-bottom: 5px;border-radius:4px;box-shadow: 0px 1px 2px #4e7c36;}
  .theme-color{background: #629c44;color:#fff;}
  .duration-box select{width:49%;}
  .btn-s-e{width:100%;text-align: center;padding:4px;margin-bottom: 2px;border: none;border-radius: 4px;}

  .table-box{padding:1em;width:100%;}
  .btn-box{padding: 1em 5px;}
</style>
</head>
<body>
<div class="container-fluid">
 <?php
 $this->load->view('include/header');
  $this->load->view('include/inner_header');

 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <form id="dental_prescription_form" name="dental_prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <!--  // prescription button modal -->

<input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
                <div class="row">
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>Template Name <span class="star">*</span></label></div>
                            <div class="col-xs-8">
                                <input type="text" name="template_name" value="<?php echo $form_data['template_name']; ?>" autofocus>
                                <?php if(!empty($form_error)){ echo form_error('template_name'); } ?>
                            </div>
                        </div>
                        
                    </div> <!-- 5 -->
                    
                </div> <!-- row -->
                   <div class="row">
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><label>Blood Group</label></div>
                           <div class="col-md-7">
           <select name="blood_group" class="w-150px m_select_btn" id="blood_group">
              <option value="">Select Blood Group</option>
              <?php
              if(!empty($blood_group_list))
              {
                foreach($blood_group_list as $bloodgrouplist)
                {
                  ?>
                    <option <?php if($form_data['blood_group']==$bloodgrouplist->id){ echo 'selected="selected"'; } ?> value="<?php echo $bloodgrouplist->id; ?>"><?php echo $bloodgrouplist->blood_group; ?></option>
                  <?php
                }
              }
              ?>
            </select> 
    
                 <?php if(!empty($form_error)){ echo form_error('blood_group'); } ?>
         </div>
                        </div>
                        
                    </div> <!-- 5 -->
                    
                </div> 
<div class="row">
  <div class="col-md-12 dental-chart ">
    <div class="text-center">
    <ul class="dental-tab ">
   <?php 
                          //print '<pre>'; print_r($prescription_tab_setting);
                            $i=1; 
                            foreach ($prescription_tab_setting as $value) 
                            { 

                              if($value->setting_name!='biometric_detail' &&  $value->setting_name!='pictorial_test')
                              {

                              
                               ?>
        <li style="margin-top:2px;" <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
       
           <?php 
                                $i++;
                              }

                             }
                         ?>
      </ul>
    </div>
  </div>  
</div>
<div class="tab-content">


            <?php 
            $j=1; 
            foreach ($prescription_tab_setting as $value) 
            { 
            ?>
       <div class="tab-content"  style="overflow-x:auto;">

        <?php  //echo $value->setting_name;
        if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0'){ ?>

        <div id="tab_chief_complaint" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
     <div class="row">
  <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Complaint Name</label>
            </div>

            <div class="col-md-6">
             <select name="complaint_name_id" class="w-150px m_select_btn" id="chief_complaint_id">
              <option value="">Select Chief Complaint</option>
               <?php
              if(!empty($chief_complaint_list))
              {
                foreach($chief_complaint_list as $chiefcomplaintlist)
                {
                  ?>
                    <option <?php if($form_data['chief_complaint_id']==$chiefcomplaintlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $chiefcomplaintlist->id; ?>"><?php echo $chiefcomplaintlist->chief_complaints; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_chief_complaint();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Teeth Name</label>
            </div>

            <div class="col-md-6">
             <select name="teeth_name" class="w-150px m_select_btn" id="teeth_name" onchange="get_open_chart(); return false;">
              <option value="">Select Teeth Name </option>
             
                    <option value="Permanent Teeth">Permanent Teeth</option>
                    <option value="Decidous Teeth">Decidous Teeth</option>
               
            </select> 
            </div>
            <div class="col-md-2">
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Tooth Number</label>
            </div>

            <div class="col-md-6">

              <button type="button"  onclick="get_popup_click(); return false;" id="modal_add" class="theme-color"> Mapping </button>
              <input type="hidden" class="w-40px" id='get_teeth_popupval' value='' name="get_teeth_popupval">
                                             <input type="text" class="w-40px" id='get_teeth_number_val' name="get_teeth_number_val" readonly>
            </div>
            <div class="col-md-2">
              
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <label>Reason</label>
            </div>

            <div class="col-md-6">
              <input type="text" name="reason" id='reason'>
            </div>
            <div class="col-md-2">
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <label>Duration</label>
            </div>

            <div class="col-md-7 duration-box">
             <select name="number" id='number'>
              <option value="">Number</option>
             
                    <option value="1">1</option>
                    <option value="2">2</option>
                     <option value="3">3</option>
               
            </select> 
             <select name="time" id="time" >
              <option value="">Time</option>
             
                    <option value="1">Day</option>
                    <option value="2">Week</option>
                     <option value="3">Month</option>
                      <option value="4">Year</option>
               
            </select> 
            </div>
           <div class="col-md-2">
              <button type="button" onclick="add_chief_complaint_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='chief_complain_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Complaint Name</th>
              <th>Teeth Name</th>
              <th>Tooth No</th>
               <th>Reason</th>
               <th>Duration </th>
                   

          </tr>
           </thead>
          <?php 
          $chief_complain_data = $this->session->userdata('chief_complain_data'); 
          $i = 0;
          if(!empty($chief_complain_data))
          {
             $i = 1;
             foreach($chief_complain_data as $chief_complaint_val)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="chief_complaint[]" value="<?php echo $chief_complaint_val['chief_complaint_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $chief_complaint_val['chief_complaint_value']; ?></td>
                  <td><?php echo $chief_complaint_val['teeth_name']; ?></td>
                  <td><?php echo $chief_complaint_val['get_teeth_number_val']; ?></td>
                  <td><?php echo $chief_complaint_val['reason']; ?></td>
                  <td><?php echo $chief_complaint_val['number'].$chief_complaint_val['time'];; ?></td>
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_chief_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
        <?php } ?>
         <?php 
         if(strcmp(strtolower($value->setting_name),'previous_history')=='0'){ ?>
               <div id="tab_previous_history" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
     <div class="row">
  <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Disease Name</label>
            </div>

            <div class="col-md-6">
             <select name="disease_name" class="w-150px m_select_btn" id="disease_id">
              <option value="">Select Disease</option>
               <?php
              if(!empty($disease_list))
              {
                foreach($disease_list as $disease)
                {
                  ?>
                    <option <?php if($form_data['disease_id']==$disease->id){ echo 'selected="selected"'; } ?> value="<?php echo $disease->id; ?>"><?php echo $disease->disease_name; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_disease();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Details</label>
            </div>

            <div class="col-md-6">
              <input type="text" name="disease_details" id="disease_details" value="<?php echo $form_data['disease_details']; ?>">
            </div>
            <div class="col-md-2">
              
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Any Operation</label>
            </div>

            <div class="col-md-6">
              <input type="text" name="operation" id="operation" value="<?php echo $form_data['operation']; ?>">
            </div>
            <div class="col-md-2">
              
            </div>
            </div>

                 

          <div class="row">
            <div class="col-md-4">
              <label>Date</label>
            </div>

            <div class="col-md-6">
             <input type="text" name="operation_date" class="datepicker" value="<?php echo $form_data['operation_date']; ?>" />
            </div>
            <div class="col-md-2">
              
            </div>

       
           <div class="col-md-2">
              <button type="button" onclick="add_disease_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_history_disease_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Disease Name</th>
              <th>Details</th>
              <th>Any Operation</th>
               <th>Date</th>
             

          </tr>
           </thead>
          <?php 
          $previous_history_disease_data = $this->session->userdata('previous_history_disease_data'); 
          $i = 0;
          if(!empty($previous_history_disease_data))
          {
             $i = 1;
             foreach($previous_history_disease_data as $previous_history_disease_val)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="disease_name[]" value="<?php echo $previous_history_disease_val['disease_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previous_history_disease_val['disease_value']; ?></td>
                  <td><?php echo $previous_history_disease_val['disease_details']; ?></td>
                  <td><?php echo $previous_history_disease_val['operation']; ?></td>
                  <td><?php echo $previous_history_disease_val['operation_date']; ?></td>
              
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_previous_history_disease();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
        <?php } ?>
         <?php 
        if(strcmp(strtolower($value->setting_name),'allergy')=='0'){ ?>
         <div id="tab_allergy" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
       <div class="row">
  <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Allergy</label>
            </div>

            <div class="col-md-6">
             <select name="allergy_id" class="w-150px m_select_btn" id="allergy_id">
              <option value="">Select Allergy</option>
               <?php
              if(!empty($allergy_list))
              {
                foreach($allergy_list as $allergylist)
                {
                  ?>
                    <option <?php if($form_data['allergy_id']==$allergylist->id){ echo 'selected="selected"'; } ?> value="<?php echo $allergylist->id; ?>"><?php echo $allergylist->allergy_name; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_allergy();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>


        

          <div class="row">
            <div class="col-md-4">
              <label>Reason</label>
            </div>

            <div class="col-md-6">

              <input type="text" name="reason" id="reason_allergy" value="<?php echo $form_data['reason'];?>">
            </div>
            <div class="col-md-2">
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <label>Duration</label>
            </div>

            <div class="col-md-7 duration-box">
             <select name="number" id='number_allergy'>
              <option value="">Number</option>
             
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>

            </select> 
             <select name="time" id="time_allergy" >
              <option value="">Time</option>
             
                <option value="1">Day</option>
                <option value="2">Week</option>
                <option value="3">Month</option>
                <option value="4">Year</option>

            </select> 
            </div>
           <div class="col-md-2">
              <button type="button" onclick="add_allergy_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_allergy_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Allergy</th>
               <th>Reason</th>
               <th>Duration </th>
                   

          </tr>
           </thead>
          <?php 
          $previous_allergy_data = $this->session->userdata('previous_allergy_data'); 
          $i = 0;
          if(!empty($previous_allergy_data))
          {
             $i = 1;
             foreach($previous_allergy_data as $previousallergyval)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="allergy[]" value="<?php echo $previousallergyval['allergy_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previousallergyval['allergy_value']; ?></td>
                  <td><?php echo $previousallergyval['reason']; ?></td>
                  <td><?php echo $previousallergyval['number'].$previousallergyval['time'];; ?></td>
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_allergy_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
        <?php } ?>
         <?php if(strcmp(strtolower($value->setting_name),'oral_habits')=='0'){  ?>
        <div id="tab_oral_habits" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
<div class="row">
  <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Habit Name</label>
            </div>

            <div class="col-md-6">
             <select name="habit_id" class="w-150px m_select_btn" id="habit_id">
              <option value="">Select Habit</option>
               <?php
              if(!empty($habit_list))
              {
                foreach($habit_list as $habitlist)
                {
                  ?>
                    <option <?php if($form_data['habit_id']==$habitlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $habitlist->id; ?>"><?php echo $habitlist->oral_habit_name; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_oral_habit();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>


          <div class="row">
            <div class="col-md-3">
              <label>Duration</label>
            </div>

            <div class="col-md-7 duration-box">
             <select name="number" id='number_oral_habit'>
              <option value="">Number</option>
             
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>

            </select> 
             <select name="time" id="time_oral_habit" >
              <option value="">Time</option>
             
                <option value="1">Day</option>
                <option value="2">Week</option>
                <option value="3">Month</option>
                <option value="4">Year</option>

            </select> 
            </div>
           <div class="col-md-2">
              <button type="button" onclick="add_oral_habit_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_oral_habit_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Habit Name</th>
               <th>Duration </th>
                   

          </tr>
           </thead>
          <?php 
          $previous_oral_habit_data = $this->session->userdata('previous_oral_habit_data'); 
          $i = 0;
          if(!empty($previous_oral_habit_data))
          {
             $i = 1;
             foreach($previous_oral_habit_data as $previousoralhabitdata)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="habit[]" value="<?php echo $previousoralhabitdata['habit_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previousoralhabitdata['oral_habit_value']; ?></td>
                  <td><?php echo $previousoralhabitdata['number'].$previousoralhabitdata['time'];; ?></td>
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_oral_habits_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
        <?php } ?>
         <?php  if(strcmp(strtolower($value->setting_name),'investigation')=='0'){  ?>
        <div id="tab_diagnosis123" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
            <div class="row m-t-10">
                <div class="col-xs-8">
                    <div class="well">
                         <div class="grid-frame3" style="<?php if(!empty($diagnosis_template_data)){echo '';}else{echo 'display:none;';}?>" id="diagnosis_grid">
                        <table width="100%" border="" id="diagnosis" cellspacing="0" cellpadding="0" class="grid_tbl">
                        <tr>
                          <td align="left" height="30"><b>Diagnosis</b></td>
                          <td align="center" height="30"><b>L</b></td>
                          <td align="center" height="30"><b>R</b></td>
                          <td align="center" height="30" colspan="2"><b>Duration</b></td>
                        </tr>
                        <tr>
                          <td align="center" height="30"></td>
                          <td align="center" height="30"></td>
                          <td align="center" height="30"></td>
                          <td align="center" height="30"><b>Days</b></td>
                          <td align="center" height="30"><b>Time</b></td>
                        </tr>
                        <?php if(!empty($diagnosis_template_data)) { $i=1;
                        foreach($diagnosis_template_data as $diagnosis_data)
                         {


                          ?>
                          <tr><td align="left" style="text-align:left;" height="30"><input type="text" name="diagnosis[<?php echo $i; ?>][diagnosis_name]" value="<?php echo $diagnosis_data->diagnosis;?>"/></td>
                            <td align="center" height="30">
                             <input type="checkbox" class="w-40px" value="1" <?php if(isset($diagnosis_data->left_eye)&& $diagnosis_data->left_eye==1){echo 'checked';}?> name="diagnosis[<?php echo $i; ?>][diagnosis_left]"> 
                            </td>
                            <td align="center" height="30">
                           

                            <input type="checkbox" class="w-40px" value="2" <?php if(isset($diagnosis_data->right_eye)&& $diagnosis_data->right_eye==2){echo 'checked';}?> name="diagnosis[<?php echo $i; ?>][diagnosis_right]">

                            </td>
                            <td align="center" height="30">
                                <select class="w-60px" id="" name="diagnosis[<?php echo $i; ?>][diagnosis_days]">
                                    <option value="1" <?php if(isset($diagnosis_data->days) && $diagnosis_data->days==1){echo 'selected';}?>>1</option>
                                    <option value="2" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==2){echo 'selected';}?>>2</option>
                                    <option value="3" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==3){echo 'selected';}?>>3</option>
                                    <option value="4" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==4){echo 'selected';}?>>4</option>
                                    <option value="5" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==5){echo 'selected';}?>>5</option>
                                    <option value="6" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==6){echo 'selected';}?>>6</option>
                                    <option value="7" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==7){echo 'selected';}?>>7</option>
                                    <option value="8" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==8){echo 'selected';}?>>8</option>
                                    <option value="9" <?php if(isset($diagnosis_data->days)&& $diagnosis_data->days==9){echo 'selected';}?>>9</option>
                                </select>
                            </td>
                            <td align="center" height="30">
                                <select class="w-60px" name="diagnosis[<?php echo $i; ?>][diagnosis_time]">
                                    <option value="1" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==1){echo 'selected';}?>>Days</option>
                                    <option value="2" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==2){echo 'selected';}?>>Week</option>
                                    <option value="3" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==3){echo 'selected';}?>>Month</option>
                                    <option value="4" <?php if(isset($diagnosis_data->time)&& $diagnosis_data->time==4){echo 'selected';}?>>Year</option>
                                </select>
                                
                            </td>
                            <td align="center" height="30"><a class="btn-custom btnDelete" onclick="remove_row('');"><i class="fa fa-trash"></i> Delete</a></td>
                        </tr>
                        <?php $i++;} } ?>
                      </table>
                    </div>

                 <!--    <h4><?php //if(!empty($value->setting_value)) { //echo $value->setting_value; } else { echo $value->var_title; } ?></h4>
                    <textarea name="diagnosis" id="diagnosis" class="media_100"><?php //echo $form_data['diagnosis']; ?></textarea>  -->
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="well tab-right-scroll">
                        <!-- <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div>
                        <div class="grp">DSHPT1</div> -->
                        <select size="9" class="dropdown-box diagnosis_data" name="diagnosis_data"  id="diagnosis_data" multiple="multiple" >
                         <?php
                            if(isset($diagnosis_list) && !empty($diagnosis_list))
                            {
                              foreach($diagnosis_list as $diagnosislist)
                              {
                                 echo '<option class="grp" value="'.$diagnosislist->id.'">'.$diagnosislist->diagnosis.'</option>';
                              }
                            }
                         ?>
                      </select>

                    </div>
                </div>
            </div>
        </div>
        <?php } ?>


          <?php if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){ 
          ?>
        <div id="tab_diagnosis" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row">
          <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Diagnosis </label>
            </div>

            <div class="col-md-6">
             <select name="diagnosis_id" class="w-150px m_select_btn" id="diagnosis_id">
              <option value="">Select diagnosis</option>
               <?php
              if(!empty($diagnosis_list))
              {
                foreach($diagnosis_list as $diagnosislist)
                {
                  ?>
                    <option <?php if($form_data['diagnosis_id']==$diagnosislist->id){ echo 'selected="selected"'; } ?> value="<?php echo $diagnosislist->id; ?>"><?php echo $diagnosislist->diagnosis; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_diagnosis();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Teeth Name</label>
            </div>

            <div class="col-md-6">
             <select name="teeth_name" class="w-150px m_select_btn" id="teeth_name_diagnosis" onchange="get_open_chart_diagnosis(); return false;">
              <option value="">Select Teeth Name </option>
             
                    <option value="Permanent Teeth">Permanent Teeth</option>
                    <option value="Decidous Teeth">Decidous Teeth</option>
               
            </select> 
            </div>
            <div class="col-md-2">
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <label>Tooth Number</label>
            </div>

            <div class="col-md-6">

              <button type="button"  onclick="get_popup_click_diagnosis(); return false;" id="modal_add_diagnosis" class="theme-color"> Mapping </button>
              <input type="hidden" class="w-40px" id='get_teeth_popupval_diagnosis' value='' name="get_teeth_popupval_diagnosis">
                                             <input type="text" class="w-40px" id='get_teeth_number_val_diagnosis' name="get_teeth_number_val_diagnosis" readonly>
            </div>
            <div class="col-md-2">
              
            </div>
          </div>



          <div class="row">
            
           <div class="col-md-2">
              <button type="button" onclick="add_diagnosis_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='diagnosis_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Diagnosis</th>
              <th>Teeth Name</th>
              <th>Tooth No</th>
            

          </tr>
           </thead>
          <?php 
          $diagnosis_data = $this->session->userdata('diagnosis_data'); 
          $i = 0;
          if(!empty($diagnosis_data))
          {
             $i = 1;
             foreach($diagnosis_data as $diagnosis)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="diagnosis[]" value="<?php echo $diagnosis['diagnosis_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $chief_complaint_val['diagnosis_value']; ?></td>
                  <td><?php echo $chief_complaint_val['teeth_name']; ?></td>
                  <td><?php echo $chief_complaint_val['get_teeth_number_val_diagnosis']; ?></td>
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_chief_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>

</div>
           
        </div>
        <?php } ?>


         <?php if(strcmp(strtolower($value->setting_name),'medicine')=='0'){  ?>
  <div id="tab_medicine" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
<div class="row m-t-10">
  <div class="col-xs-12">
      <div class="well tab-right-scroll">
          <table class="table table-bordered table-striped" id="prescription_name_table">
              <tbody>
                  <tr>
                      
                      <?php 
                    $m=0;

                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                            <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                                <?php 
                           $m++; 
                            }
                            ?>    
                                                    
                        <td width="80">
                            <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                        </td>
                    </tr>

                      <?php 
                      //echo "<pre>"; print_r($prescription_presc_list); exit;
                      if(!empty($prescription_presc_list))
                      { 
                           $l=1;
                          foreach ($prescription_presc_list as $prescription_presc) 
                          {
                            
                          ?>
                     <tr>
                       <?php
                        
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td>

                        <input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="medicine_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>">
                        <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_id; ?>">
                        </td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_brand]" class="" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" id="salt<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" id="type<?php echo $l; ?>" class="input-small medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class="input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class="medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->advice; ?>"></td>
                        <?php } }
                        ?>
                        <script type="text/javascript">
                          
                    /* script start */
                      $(function () 
                      {
                            var getData = function (request, response) { 
                              row = <?php echo $l; ?> ;
                              $.ajax({
                              url : "<?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
                              dataType: "json",
                              method: 'post',
                            data: {
                               name_startsWith: request.term,
                               type: 'country_table',
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

                          var selectItem = function (event, ui) {

                                var names = ui.item.data.split("|");

                                $('.medicine_val'+<?php echo $l; ?>).val(names[0]);
                                $('#type'+<?php echo $l; ?>).val(names[1]);
                                $('#brand'+<?php echo $l; ?>).val(names[2]);
                                $('#salt'+<?php echo $l; ?>).val(names[3]);
                                $('#medicine_id'+<?php echo $l; ?>).val(names[4]);
                              //$(".medicine_val").val(ui.item.value);
                              return false;
                          }

                          $(".medicine_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });


                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_type_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".medicine_type_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".medicine_type_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_dosage_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".dosage_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".dosage_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_duration_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".duration_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".duration_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });
                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_frequency_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".frequency_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".frequency_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('opd/get_advice_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".advice_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".advice_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  
                              }
                          });
                          });
                              /* script end*/
                        function delete_prescr_row(r)
                        { 
                            var i = r.parentNode.parentNode.rowIndex;
                            document.getElementById("prescription_name_table").deleteRow(i);
                        }
                        </script>
                        

                                                        
                        <td width="80">
                            <a onclick="delete_prescr_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                        </td>
                    </tr>
                    <?php $l++; } }else{ ?>

                    <tr>
                        <?php
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_name]" class="medicine_val">
                        <input type="hidden" name="medicine_id[]" id="medicine_id">
                        </td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_brand]" class=""></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_salt]" class="" ></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="prescription[1][medicine_type]" class="input-small"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="prescription[1][medicine_dose]" class="input-small"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-name"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-name"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-name"></td>
                        <?php } 
                      }
                   ?>
                    <td width="80">
                        <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
        <?php } ?>
    <?php if(strcmp(strtolower($value->setting_name),'treatment')=='0'){  ?>
  <div id="tab_prescription" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
  <div class="row m-t-10" style="padding:10px;">
  <div class="col-xs-12">
  <div class="well tab-right-scroll" style="border:none;">
    <table class="table table-bordered table-striped" id="prescription_name_table">
        <tbody>
            <tr>
                <?php 
                    $m=0;
                    
                    foreach ($prescription_medicine_tab_setting as $med_value) 
                    { 
                       //print_r($med_value);
                       if($med_value->setting_name!='date')
                       { //echo $med_value->setting_value;?>
                        <td <?php  if($m=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>
                       <?php }
                      ?>
                            
                                <?php 
                           $m++; 
                    }            
                            ?>
                <td width="80">
                    <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                </td>
            </tr>
            <?php  if(!empty($prescription_template_data)) { 

                 $l=1; 
                foreach ($prescription_template_data as $prescriptiontemplate) {
                   print '<pre>'; print_r($prescriptiontemplate);
                ?>
                
                 <tr>

                <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
      <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="w-100px medicine_val<?php echo $l; ?>" id="medicine_name<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_name; ?>"></td>
    <?php }?>

    <?php if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" class="w-100px" id="salt<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_salt; ?>"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_company]" class="w-100px" id="medicine_company<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_brand; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    { ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" class="w-100px input-small" id="type<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_type; ?>"></td>
    <?php 
    }
  
   
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="w-100px medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->medicine_frequency; ?>"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="w-100px medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescriptiontemplate->advice; ?>"></td>
    <?php } 
  }
  ?>        

        <script type="text/javascript">
                          
                    /* script start */
                      $(function () 
                      {
                            var getData = function (request, response) { 
                              row = <?php echo $l; ?> ;
                              $.ajax({
                              url : "<?php echo base_url('dental/prescription_template/get_dental_medicine_auto_vals/'); ?>" + request.term,
                              dataType: "json",
                              method: 'post',
                            data: {
                               name_startsWith: request.term,
                               type: 'country_table',
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

                          var selectItem = function (event, ui) {

                                var names = ui.item.data.split("|");

                                $('.medicine_val'+<?php echo $l; ?>).val(names[0]);
                                $('#type'+<?php echo $l; ?>).val(names[1]);
                                $('#medicine_company'+<?php echo $l; ?>).val(names[2]);
                                $('#salt'+<?php echo $l; ?>).val(names[3]);
                              //$(".medicine_val").val(ui.item.value);
                              return false;
                          }

                          $(".medicine_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });


                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('dental/prescription_template/get_dental_dosage_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".dosage_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".dosage_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('dental/prescription_template/get_dental_duration_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".duration_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".duration_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });
                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('dental/prescription_template/get_dental_frequency_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".frequency_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".frequency_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });

                      $(function () {
                          var getData = function (request, response) { 
                              $.getJSON(
                                  "<?php echo base_url('dental/prescription_template/get_dental_advice_vals/'); ?>" + request.term,
                                  function (data) {
                                      response(data);
                                  });
                          };

                          var selectItem = function (event, ui) {
                              $(".advice_val"+<?php echo $l; ?>).val(ui.item.value);
                              return false;
                          }

                          $(".advice_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 2,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });
                          });
                              /* script end*/

                        </script>

                <td width="80">
                    <a href="javascript:void(0)" onclick="delete_prescription_medicine('<?php echo $prescriptiontemplate->id; ?>,<?php echo $form_data['data_id']; ?>');" class="btn-w-60">Delete</a>
                </td>
                </tr>

                <?php     
                $l++; }
            }
            else
            {
                ?>
                 <tr>
                  <td><input type ="checkbox" id="is_eye_drop1" value="1" onclick="check_eye_drop(1);"/></td>
               <?php
    foreach ($prescription_medicine_tab_setting as $tab_value) 
    { 
    if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_name]" class="medicine_val w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_salt]" id="salt" class="w-100px"></td>
    <?php 
    }

     if(strcmp(strtolower($tab_value->setting_name),'medicine_company')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_company]" id="medicine_company" class="w-100px" ></td>
    <?php 
    }  
    if(strcmp(strtolower($tab_value->setting_name),'medicine_unit')=='0')
    { ?>
    <td><input type="text" name="prescription[1][medicine_type]"  id="type" class="input-small w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
    { ?>
    <td><input type="text" name="prescription[1][medicine_dose]" class="input-small dosage_val"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
    {  ?>
    <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-name duration_val w-100px"></td>
    <?php 
    }
    if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
    {
    ?>
    <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-name frequency_val w-100px"></td>
    <?php 
    } 
    if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
    { 
    ?>
    <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-name advice_val w-100px"></td>
    <?php } 



  if(strcmp(strtolower($tab_value->setting_name),'right_eye')=='0')
  { 
  ?>
    <td class="right_eye_appned1"><input type="checkbox" name="prescription[1][medicine_right_eye]" value="2" class="medicine-name right_eye_val1 hide"></td>
    <?php }
       if(strcmp(strtolower($tab_value->setting_name),'left_eye')=='0')
    { 
    ?>
    <td class="left_eye_appned1"><input type="checkbox" value="1"  name="prescription[1][medicine_left_eye]" class="medicine-name left_eye_val1 hide"></td>
    <?php }  
  }
  ?>
              <!--  <td width="80">
                   <a href="javascript:void(0)" class="btn-w-60">Delete</a>
                </td> -->


            </tr>
                <?php 
             }
             ?>
           

           
        </tbody>
    </table>
  </div>
  </div>
  </div>
  </div>

        <?php } ?>
         <?php  if(strcmp(strtolower($value->setting_name),'advice')=='0'){  ?>
        <div id="tab_advice" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row">
    <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Advice</label>
            </div>

            <div class="col-md-6">
             <select name="advice_id" class="w-150px m_select_btn" id="advice_id">
              <option value="">Select Advice</option>
               <?php
              if(!empty($advice_list))
              {
                foreach($advice_list as $advicelist)
                {
                  ?>
                    <option <?php if($form_data['advice_id']==$habitlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $advicelist->id; ?>"><?php echo $advicelist->advice; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            </div>

            <div class="col-md-2">
               <a href="javascript:void(0)" onclick="return add_advice();"  class="btn-new"><i class="fa fa-plus">Add</i></a>
            </div>
          </div>


          <div class="row">
          
           <div class="col-md-2">
              <button type="button" onclick="add_advice_listdata();" class="theme-color">Add</button>
            </div>
          </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_advice_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle(this);">
                <?php echo $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>
                              
                              ";  ?>
              </th>
              <th scope="col">S.No.</th>
              <th>Advice</th>
                   

          </tr>
           </thead>
          <?php 
          $previous_advice_data = $this->session->userdata('previous_advice_data'); 
          $i = 0;
          if(!empty($previous_advice_data))
          {
             $i = 1;
             foreach($previous_advice_data as $previousadvicedata)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox booked_checkbox" name="advice[]" value="<?php echo $previousadvicedata['advice_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previousadvicedata['advice_value']; ?></td>
                 
                  
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_advice_vals();">
             <i class="fa fa-trash"></i> Delete
          </a>
      <?php  //if(!empty($form_error)){ ?>    
     <tr>
            <td colspan="5"><?php //echo form_error('chief_complaint');  ?></td>
        </tr>
        <?php //} ?>
            </table>
        </div>
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
          
        </div>
        <?php } ?>
         <?php if(strcmp(strtolower($value->setting_name),'next_appointment')=='0'){   ?>

        <div id="tab_next_appointment" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                <div class="row">
  <div class="col-md-12 tab-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Date</label>
              <input type="checkbox" name="">
            </div>
            <div class="col-md-6">  
            <input type="text" name="next_appointment_date" class="datepicker1" value="<?php echo $form_data['next_appointment_date']; ?>" /> 
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <label>Time</label>
            </div>
            <div class="col-md-6">  
            <input type="text" name="appointment_time" class="datepicker3 m_input_default" value="<?php echo $form_data['appointment_time']; ?>" /> 
            </div>
          </div>

         

        </div>

      </div>
   
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
         <?php } ?>
         <?php if(strcmp(strtolower($value->setting_name),'appointment')=='0'){    ?>
        <div id="tab_appointment" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
            <div class="well tab-right-scroll" style="overflow-x: hidden;padding:5px 10px;">
                <div class="row m-t-10">
                    <div class="col-xs-2">
                        <label><b><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></b></label>
                    </div>
                   <!--  <div class="col-xs-10">
                        <input type="checkbox" name="">
                        <input type="text" name="next_appointment_date" class="datepicker" value="<?php //echo $form_data['next_appointment_date']; ?>" /> 
                    </div> -->
                </div>
            </div>
        </div>
  <?php } ?>
    </div>



            <?php 
        $j++;
        }
     ?>
        

    </div>
  </div> <!-- 11 -->
  <div class="col-md-1 btn-box">
  <div class="prescription_btns">
  <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
  <button class="btn-save" type="button" name="" data-dismiss="modal" onclick="window.location.href='<?php echo base_url('eye/prescription_template'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
  </div>
  </div> <!-- row -->

        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">
function check_eye_drop(row_val)
{

 var eye_drop_val= $('#is_eye_drop').val();
 if ($('input#is_eye_drop'+row_val).is(':checked')) 
  {
    $('.right_eye_val'+row_val).removeClass('hide');
    $('.right_eye_append'+row_val).html('<input type="checkbox" name="prescription['+row_val+'][medicine_right_eye]" value="2" class="medicine-name right_eye_val'+row_val+'">');
     
    $('.left_eye_val'+row_val).removeClass('hide');
    $('.left_eye_append'+row_val).html('<input type="checkbox" name="prescription['+row_val+'][medicine_left_eye]" value="1" class="medicine-name left_eye_val'+row_val+'">');
    
  }
  else
  {
    
    $('.right_eye_val'+row_val).addClass('hide');
    $('.right_eye_append'+row_val).html('');
    $('.left_eye_val'+row_val).addClass('hide');
    $('.left_eye_append'+row_val).html('');
  }

}
function delete_prescription_medicine(val,temp_id)
{
  
  var prescription_medicine_id = val;
  var templ_id = temp_id
  $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/delete_pres_medicine/"+prescription_medicine_id+'/'+templ_id, 
    success: function(result)
    {
       //alert(result); return;
       $("#tab_prescription").append(result);
    } 
  }); 
  
}
function tab_links(vals)
  {

    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('in');
    $('#'+vals).addClass('active');
  }

     $('#chief_complaints_data').change(function(){  
      var rowCount = $('#chief_complaints tr').length;
     var newrowcount=rowCount-1;

     //alert(newrowcount);

      var complaints_id = $(this).val();
      var chief_complaints_val = $("#chief_complaints").val();
      $.ajax({url: "<?php echo base_url(); ?>eye/prescription_template/eye_complaints_name/"+complaints_id+'/'+newrowcount, 
        success: function(result)
        {
           $(".chief_complaints_data option[value='"+complaints_id+"']").hide();
           // $(".chief_complaints_data option[value="+complaints_id+"").remove();
          //alert(result);
           //$('#chief_complaints').html(result);
           // if(chief_complaints_val!='')
           // {
           //  var chief_complaints_value = chief_complaints_val+','+result; 
           // }
           // else
           // {
           //  var chief_complaints_value = result;
           // }
           $('#complain_grid').css('display','block');
           $('#chief_complaints').append(result);  
        } 
      }); 
  });


function remove_row(row_val)
{

    $("#chief_complaints").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".chief_complaints_data option[value='"+row_val+"']").show();
    
}

function remove_systemic_ill_row(row_val)
{
  
    $("#systemic_illness").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".systemic_illness_data option[value='"+row_val+"']").show();
    
}

function remove_diagnosis_row(row_val)
{
  
    $("#diagnosis").on('click', '.btnDelete', function () {
     $(this).closest('tr').remove();
     
    });
    $(".diagnosis_data option[value='"+row_val+"']").show();
    
}



$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true, 
  });
$('.datepicker1').datepicker({
    format: 'dd-mm-yyyy',
    startDate: new Date(),
    autoclose: true, 
  });


$(document).ready(function(){
    $(".addrow").click(function(){

      var i=$('#test_name_table tr').length;
        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val'+i+'"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');


        $(function () {
          var getData = function (request, response) { 
              $.getJSON(
                  "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
                  function (data) {
                      response(data);
                  });
          };

          var selectItem = function (event, ui) {
              $(".test_val"+i).val(ui.item.value);
              return false;
          }

          $(".test_val"+i).autocomplete({
              source: getData,
              select: selectItem,
              minLength: 2,
              change: function() {
                  //$("#test_val").val("").css("display", 2);
              }
          });
          });

    });
    $("#test_name_table").on('click','.remove_row',function(){
        $(this).parent().parent().remove();
    });

$(".addprescriptionrow").click(function(){

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>   <td><input type="text" name="prescription['+i+'][medicine_brand]" id="brand'+i+'"  class="" ></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>  <td><input type="text" id="salt'+i+'"  name="prescription['+i+'][medicine_salt]" class=""  ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="input-small medicine_type_val"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class="input-small dosage_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>  <td><input type="text" name="prescription['+i+'][medicine_duration]" class="medicine-name duration_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="medicine-name frequency_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>  <td><input type="text" name="prescription['+i+'][medicine_advice]" class="medicine-name advice_val'+i+'"></td>                        <?php 
                        } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
        /* script start */
$(function () 
{
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dental/prescription_template/get_dental_medicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
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

    var selectItem = function (event, ui) {

          var names = ui.item.data.split("|");

          $('.medicine_val'+i).val(names[0]);
          $('#type'+i).val(names[1]);
          $('#medicine_company'+i).val(names[3]);
          $('#salt'+i).val(names[2]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".medicine_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_dosage_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".dosage_val"+i).val(ui.item.value);
        return false;
    }

    $(".dosage_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_duration_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".duration_val"+i).val(ui.item.value);
        return false;
    }

    $(".duration_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".frequency_val"+i).val(ui.item.value);
        return false;
    }

    $(".frequency_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".advice_val"+i).val(ui.item.value);
        return false;
    }

    $(".advice_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
        /* script end*/
m++;

    });
    $("#prescription_name_table").on('click','.remove_prescription_row',function(){
        $(this).parent().parent().remove();
    });
});

$('#form_submit').on("click",function(){
       $('#dental_prescription_form').submit();
  })


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".test_val").val(ui.item.value);
        return false;
    }

    $(".test_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dental/prescription_template/get_dental_medicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
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

        /*$.getJSON(
            "< ?php echo base_url('opd/get_medicine_auto_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });*/
    };

    var selectItem = function (event, ui) {

          var names = ui.item.data.split("|");
          $('.medicine_val').val(names[0]);
          $('#type').val(names[1]);
          $('#medicine_company').val(names[3]);
          $('#salt').val(names[2]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".medicine_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_dosage_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".dosage_val").val(ui.item.value);
        return false;
    }

    $(".dosage_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_duration_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".duration_val").val(ui.item.value);
        return false;
    }

    $(".duration_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_frequency_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".frequency_val").val(ui.item.value);
        return false;
    }

    $(".frequency_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/prescription_template/get_dental_advice_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) {
        $(".advice_val").val(ui.item.value);
        return false;
    }

    $(".advice_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
</script>
<script>
 function add_chief_complaint_listdata()
  {
    //alert();
    var chief_complain = $('#chief_complaint_id').val();
    var chief_complaint_value = $('#chief_complaint_id option:selected').text();
    var teeth_name = $('#teeth_name option:selected').text();
    var get_teeth_number_val=$('#get_teeth_number_val').val();
    var reason = $('#reason').val();
    var time = $('#time option:selected').text();
    var number = $('#number option:selected').text();
     //alert(chief_complain);
     //alert(chief_complaint_value);
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/chief_complain_list/", 
            dataType: "json",
            data: 'chief_complaint_id='+chief_complain+'&teeth_name='+teeth_name+'&reason='+reason+'&number='+number+'&time='+time+'&chief_complaint_value='+chief_complaint_value+'&get_teeth_number_val='+get_teeth_number_val,
            
            success: function(result)
            {
              //alert(result);

              $('#chief_complain_list').html(result.html_data);  
             
               
            } 
          });
    
  }
  function add_disease_listdata()
  {
    //alert();
    var disease = $('#disease_id').val();
    var disease_value = $('#disease_id option:selected').text();
    var disease_details = $('#disease_details').val();
    var operation = $('#operation').val();
   var operation_date = $('.datepicker').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/disease_list/", 
            dataType: "json",
            data: 'disease_id='+disease+'&disease_value='+disease_value+'&disease_details='+disease_details+'&operation='+operation+'&operation_date='+operation_date,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_history_disease_list').html(result.html_data);  
             
               
            } 
          });
    
  }
  function add_allergy_listdata()
  {
    //alert();
    var allergy_id = $('#allergy_id').val();
    var allergy_value = $('#allergy_id option:selected').text();
     var reason = $('#reason_allergy').val();
    var time = $('#time_allergy option:selected').text();
    var number = $('#number_allergy option:selected').text();
     //alert(chief_complain);
     //alert(chief_complaint_value);
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/allergy_list/", 
            dataType: "json",
            data: 'allergy_id='+allergy_id+'&allergy_value='+allergy_value+'&reason='+reason+'&number='+number+'&time='+time,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_allergy_list').html(result.html_data);  
             
               
            } 
          });
    
  }
 function add_oral_habit_listdata()
  {
    //alert();
    var habit_id = $('#habit_id').val();
    var oral_habit_value = $('#habit_id option:selected').text();
    var time = $('#time_oral_habit option:selected').text();
    var number = $('#number_oral_habit option:selected').text();
     //alert(chief_complain);
     //alert(chief_complaint_value);
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/oral_habit_list/", 
            dataType: "json",
            data: 'habit_id='+habit_id+'&oral_habit_value='+oral_habit_value+'&number='+number+'&time='+time,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_oral_habit_list').html(result.html_data);  
             
               
            } 
          });
    
  }
  function add_advice_listdata()
  {
    //alert();
    var advice_id = $('#advice_id').val();
    var advice_value = $('#advice_id option:selected').text();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/advice_list/", 
            dataType: "json",
            data: 'advice_id='+advice_id+'&advice_value='+advice_value,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_advice_list').html(result.html_data);  
             
               
            } 
          });
    
  }
  function add_diagnosis_listdata()
  {
    //alert();
    var diagnosis = $('#diagnosis_id').val();
    var diagnosis_value = $('#diagnosis_id option:selected').text();
    var teeth_name = $('#teeth_name option:selected').text();
    var get_teeth_number_val_diagnosis=$('#get_teeth_number_val_diagnosis').val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/prescription_template/diagnosis_list/", 
            dataType: "json",
            data: 'diagnosis_id='+diagnosis+'&teeth_name='+teeth_name+'&diagnosis_value='+diagnosis_value+'&get_teeth_number_val_diagnosis='+get_teeth_number_val_diagnosis,
            
            success: function(result)
            {
              //alert(result);

              $('#diagnosis_list').html(result.html_data);  
             
               
            } 
          });
    
  }
function add_chief_complaint()
{
  //alert();
  var $modal = $('#load_add_dental_chief_complaints_modal_popup');
  $modal.load('<?php echo base_url().'dental/chief_complaints/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_disease()
{

  var $modal = $('#load_add_dental_disease_modal_popup');
  $modal.load('<?php echo base_url().'dental/disease/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_allergy()
{

  var $modal = $('#load_add_dental_allergy_modal_popup');
  $modal.load('<?php echo base_url().'dental/allergy/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_oral_habit()
{

  var $modal = $('#load_add_dental_oral_habit_modal_popup');
  $modal.load('<?php echo base_url().'dental/oral_habit/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_advice()
{

  var $modal = $('#load_add_dental_advice_modal_popup');
  $modal.load('<?php echo base_url().'dental/advice/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_diagnosis)
{
  var $modal = $('#load_add_dental_diagnosis_modal_popup');
  $modal.load('<?php echo base_url().'dental/diagnosis/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}





function add_teeth_chart()
{  //alert();
  var $modal = $('#load_add_dental_teeth_chart_modal_popup');
  $modal.load('<?php echo base_url().'dental/teeth_chart/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


</script>
<script>
  
  $('.datepicker3').datetimepicker({
      format: 'LT'
  });
</script>
<script type="text/javascript">
  function delete_chief_vals() 
  {          
    
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
      // alert(allVals);return false;
       remove_chief_vals(allVals);

  }


  function remove_chief_vals(allVals)
  {    
   if(allVals!="")
   {
    

    //var chief_complain = $('#chief_complaint_id').val();
      var chief_complaint_value = $('#chief_complaint_id option:selected').text();
    var teeth_name = $('#teeth_name option:selected').text();
     var reason = $('#reason').val();
     var time = $('#time option:selected').text();
     var number = $('#number option:selected').text();
     
   
     
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/prescription_template/remove_dental_chief');?>",
              data: 'chief_complaint='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#chief_complain_list').html(result.html_data);    
              }
          });
      
   }
  }
   function delete_previous_history_disease() 
  {          
    
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
      // alert(allVals);return false;
       remove_previous_history_disease(allVals);

  }


  function remove_previous_history_disease(allVals)
  {    
   if(allVals!="")
   {
     var disease = $('#disease_id').val();
    var disease_value = $('#disease_id option:selected').text();
    var disease_details = $('#disease_details').val();
    var operation = $('#operation').val();
    var operation_date = $('#operation_date').val();

    //var chief_complain = $('#chief_complaint_id').val();
  
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/prescription_template/remove_previous_history_disease');?>",
              data: 'disease_name='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_history_disease_list').html(result.html_data);    
              }
          });
      
   }
  }
  function delete_allergy_vals() 
  {          
    
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
      // alert(allVals);return false;
       remove_previous_allergy_vals(allVals);

  }


  function remove_previous_allergy_vals(allVals)
  {    
   if(allVals!="")
   {
  
    var allergy_id = $('#allergy_id').val();
    var allergy_value = $('#allergy_id option:selected').text();
    var reason = $('#reason_allergy').val();
    var time = $('#time_allergy option:selected').text();
    var number = $('#number_allergy option:selected').text();
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/prescription_template/remove_dental_allergy');?>",
              data: 'allergy='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_allergy_list').html(result.html_data);    
              }
          });
      
   }
  }
  function delete_oral_habits_vals() 
  {          
    
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
      // alert(allVals);return false;
       remove_previous_oral_habit_vals(allVals);

  }


  function remove_previous_oral_habit_vals(allVals)
  {    
   if(allVals!="")
   {
  
    var habit_id = $('#habit_id').val();
    var oral_habit_value = $('#habit_id option:selected').text();
    var time = $('#time_oral_habit option:selected').text();
    var number = $('#number_oral_habit option:selected').text();
     //alert(chief_complain);
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/prescription_template/remove_dental_oral_habit');?>",
              data: 'habit='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_oral_habit_list').html(result.html_data);    
              }
          });
      
   }
  }
  function delete_advice_vals() 
  {          
    
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
      // alert(allVals);return false;
       remove_previous_advice_vals(allVals);

  }


  function remove_previous_advice_vals(allVals)
  {    
   if(allVals!="")
   {
  
    var advice_id = $('#advice_id').val();
    var advice_value = $('#advice_value option:selected').text();
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/prescription_template/remove_dental_advice');?>",
              data: 'advice='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_advice_list').html(result.html_data);    
              }
          });
      
   }
  }

</script>
<script>
 function get_open_chart()
         {
          var teeth_name = $('#teeth_name option:selected').text();
          $("#get_teeth_popupval").val(teeth_name);        
         }


///get_popup_click
         function get_popup_click()
         {
           var num_val=$("#get_teeth_popupval").val();
          // var teeth_name=$('#teeth_name option:selected').text();
           //alert(teeth_name);
         
             if(num_val=='Permanent Teeth')
         {
          var url='<?php echo base_url().'dental/prescription_template/add_perma/' ?>';
           // alert(url);
         var $modal = $('#load_add_chart_perma_modal_popup');
         $('#modal_add').on('click', function(){
         $modal.load('<?php echo base_url().'dental/prescription_template/add_perma/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         });
         
          }
         
              else if(num_val=='Decidous Teeth')
         {
          var url='<?php echo base_url().'dental/prescription_template/add_decidous/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
          var $modal = $('#load_add_chart_perma_modal_popup');
         $('#modal_add').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         });
         
          }
         
         
          }
</script>
<script>
 function get_open_chart_diagnosis()
         {
          var teeth_name_diagnosis = $('#teeth_name_diagnosis option:selected').text();
          $("#get_teeth_popupval_diagnosis").val(teeth_name_diagnosis);        
         }


///get_popup_click
         function get_popup_click_diagnosis()
         {
           var num_val=$("#get_teeth_popupval_diagnosis").val();
          // var teeth_name=$('#teeth_name option:selected').text();
          // alert(num_val);
         
             if(num_val=='Permanent Teeth')
         {
          var url='<?php echo base_url().'dental/prescription_template/add_perma/' ?>';
           // alert(url);
         var $modal = $('#load_add_chart_perma_modal_popup');
         $('#modal_add_diagnosis').on('click', function(){
         $modal.load('<?php echo base_url().'dental/prescription_template/add_perma/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         });
         
          }
         
              else if(num_val=='Decidous Teeth')
         {
          var url='<?php echo base_url().'dental/prescription_template/add_decidous/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
          var $modal = $('#load_add_chart_perma_modal_popup');
         $('#modal_add_diagnosis').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         });
         
          }
         
         
          }
</script>
<script>
 $(document).ready(function() {
   $('#load_add_dental_chief_complaints_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
  $(document).ready(function() {
   $('#load_add_dental_disease_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
 $(document).ready(function() {
   $('#load_add_dental_teeth_chart_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
 $(document).ready(function() {
   $('#load_add_dental_allergy_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
  $(document).ready(function() {
   $('#load_add_dental_oral_habit_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
    $(document).ready(function() {
   $('#load_add_dental_advice_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
     $(document).ready(function() {
   $('#load_add_dental_advice_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
      $(document).ready(function() {
   $('#load_add_dental_diagnosis_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});

 $("button[data-number=1]").click(function(){
    $('#load_add_dental_chief_complaints_modal_popup').modal('hide');
});
 
 $("button[data-number=1]").click(function(){
    $('#load_add_dental_disease_modal_popup').modal('hide');
});
 $("button[data-number=1]").click(function(){
    $('#load_add_dental_allergy_modal_popup').modal('hide');
});
  $("button[data-number=1]").click(function(){
    $('#load_add_oral_habit_modal_popup').modal('hide');
});
   $("button[data-number=1]").click(function(){
    $('#load_add_dental_advice_modal_popup').modal('hide');
});
   $("button[data-number=1]").click(function(){
    $('#load_add_dental_diagnosis_modal_popup').modal('hide');
});
 $("button[data-number=1]").click(function(){
    $('#load_add_chart_perma_modal_popup').modal('hide');
});



 function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  
  $(document).ready(function(){

    $("#chief_complain_list").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    });
</script>
<div id="load_add_dental_chief_complaints_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_allergy_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_oral_habit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_advice_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_diagnosis_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
 <div id="load_add_chart_perma_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_teeth_chart_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

  })
</script>

</body>
</html>