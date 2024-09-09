<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dental-style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?=ROOT_JS_PATH?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>   
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>select2.full.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>select2.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-select.min.css">
<style>
 .dental-chart{padding:0.5em;}
  ul.dental-tab{list-style: none;padding: 0px;margin: 0 auto;}
  ul.dental-tab li{float:left;padding:7px 17px!important;background: #0e854f;text-align:center;margin-left:4px;border-radius:4px;box-shadow: 2px 2px #ccc;}
  ul.dental-tab li a{text-decoration: none;color:#fff;}
  
  ul.dental-tab .active{background: #0e854f;}
  .box-left{padding:1em;width:100%;}
  .box-left select{width:100%;*padding: 4px;margin-bottom: 3px;}
  .box-left input{width:100%;margin-bottom: 3px;}
  .box-left button{width:100%;border: none;text-align: center;padding: 3px;margin-bottom: 5px;border-radius:4px;box-shadow: 0px 1px 2px #4e7c36;}
  .theme-color{background: #0e854f;color:#fff;}
  .duration-box select{width:49%;}
  .btn-s-e{width:100%;text-align: center;padding:4px;margin-bottom: 2px;border: none;border-radius: 4px;}

  .table-box{padding:1em;width:100%;}
  .btn-box{padding: 1em 5px;}
  .dent-type{border:1px solid #666;padding: 1em;}
  .btn-box1{width:150px;padding:4px;background: #0e854f;color:#fff;border-radius: 4px;text-align:center;margin:8px 0;}
  .btn-flex{display: flex;}
.btn-flex div{flex:1;}
.btn-text{text-align:center;margin-bottom:3px;}
.btn-heading{width:100%;margin: 0 auto;}
.btn-box2{width:150px;padding:4px;background: #0e854f;color:#fff;border-radius: 4px;text-align:center;margin:8px auto;}
.btn-text{text-align:center;margin-bottom:3px;}
</style>
</head>
<body>
<div class="container-fluid">
<?php $this->load->view('include/header');
 $this->load->view('include/inner_header');
 ?>
         <!-- ============================= Main content start here ===================================== -->
         <section class="userlist">
            <form id="dental_prescription_form" name="dental_prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
               <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
               <!--  // prescription button modal -->
               <div class="row">
                
                  <div class="col-xs-5">
                     <div class="row m-b-5">
                        <div class="col-xs-4"><strong>OPD No.</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="booking_code" value="<?php echo $form_data['booking_code']; ?>">
                        </div>
                     </div>
                     <div class="row m-b-5 m-b-5">
                        <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                        <div class="col-xs-8">
                           <input type="text"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>">
                        </div>
                     </div>
                     <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Patient Name</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="patient_name" value="<?=get_simulation_name($form_data['simulation_id'])?> <?php echo $form_data['patient_name']; ?>">
                           <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">
                           <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
                        </div>
                     </div>
                     <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="aadhaar_no" value="<?php echo $form_data['aadhaar_no']; ?>">
                           <?php if(!empty($form_error)){ echo form_error('aadhaar_no'); } ?>
                        </div>
                     </div>
                  </div>
                  <!-- 5 -->
                  <div class="col-xs-5">
                     <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Mobile No.</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>">
                        </div>
                     </div>
                     <div class="row m-b-5 m-b-5">
                        <div class="col-xs-4"><strong>Gender</strong></div>
                        <div class="col-xs-8">
                           <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                           <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                           <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                        </div>
                     </div>
                     <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Age</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>"> Y &nbsp;
                           <input type="text" name="age_m" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_m']; ?>"> M &nbsp;
                           <input type="text" name="age_d" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="2" value="<?php echo $form_data['age_d']; ?>"> D
                           <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                        </div>
                     </div>
                  </div>
                  <!-- 5 -->
               </div>
               <!-- row -->
               <div class="row m-t-10">
                  <div class="col-xs-9">
                     <label>
                        <b>Template</b> 
                        <select name="template_list"  id="template_list" >
                           <option value="">Select Template</option>
                           <?php
              if(!empty($template_list))
              {
                foreach($template_list as $templatelist)
                {
                  ?>
                    <option <?php if($form_data['template_list']==$templatelist->id){ echo 'selected="selected"'; } ?> value="<?php echo $templatelist->id; ?>"><?php echo $templatelist->template_name; ?></option>
                  <?php
                }
              }
              ?>
                        </select>
                     </label>
                     &nbsp;
                     <a href="<?=base_url('dental/prescription_template/add')?>" target="_blank" class="btn btn-success"><i class="fa fa-plus"></i> Add new</a>
                  </div>
                  <div class="col-xs-3">
                        <label for="">
                            <b>Date Time</b>
                            <input type="datetime-local" name="date_time_new" value="<?=$form_data['date_time_new']?>">
                        </label>
                        <script>
                            $('#date_time_new').datetimepicker({
                          format: 'DD-MM-YYYY HH:mm A',
                          useCurrent: false,  // Important! See issue #1075
                          showClose: true,
                          showClear: true,
                          showTodayButton: true,
                          stepping: 15, // Time step in minutes
                          minDate: moment().startOf('day'), // Minimum date is today
                          maxDate: moment().add(1, 'year'), // Maximum date is one year from today
                          icons: {
                              time: 'fa fa-clock-o',
                              date: 'fa fa-calendar',
                              up: 'fa fa-chevron-up',
                              down: 'fa fa-chevron-down',
                              previous: 'fa fa-chevron-left',
                              next: 'fa fa-chevron-right',
                              today: 'fa fa-crosshairs',
                              clear: 'fa fa-trash',
                              close: 'fa fa-times'
                          }
                                });
                        </script>
                    </div>
               </div>
               <!-- ///////////////// dental chart //////////////////////// -->
               <div class="row m-t-10">
                    <div class="col-xs-11">
                        <div class="row">
                           <div class="col-md-12 dental-chart ">
                              <div class="text-center">
                                 <ul class="nav nav-tabs">
                                    <?php 
                                    //   print '<pre>'; print_r($prescription_tab_setting);
                                         $i=1; 
                                         foreach ($prescription_tab_setting as $value) 
                                         { 
                                            ?>
                                    <li style="margin-top:2px;" <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
                                    <?php 
                                       $i++;
                                       
                                       
                                       }
                                       ?>
                                 </ul>
                              </div>
                           </div>
                        </div>
                        <!-- tab div end -->
                        <!-- ***************************** -->
                        <div class="row">
                           <div class="col-md-12 tab-content dental-chart">
                              <?php 
                                 $j=1; 
                                 foreach ($prescription_tab_setting as $value) 
                                 { 
                                    //print_r($chief_complaint_list);
                                    //die;
                                 ?>
                              <?php  //echo $value->setting_name;
                                 if(strcmp(strtolower($value->setting_name),'chief_complaint')=='0'){ ?>
                              <div id="tab_chief_complaint" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                                 <div class="col-md-5" >
                                    <div class="box-left" style="padding:1em 0px;">
                                       <div class="row">
                                          <div class="col-md-4">
                                             <label>Complaint Name</label>
                                          </div>
                                          <div class="col-md-8">
                                             <select name="complaint_name_id" class="m_select_btn" id="chief_complaint_id" style="width:200px;">
                                                <option value="">Select Chief Complaint</option>
                                                <?php
                                                   if(!empty($chief_complaint_list))
                                                   {
                                                     foreach($chief_complaint_list as $chief_complaint_values)
                                                     {
                                                       ?>
                                                <option <?php if($form_data['complaint_name_id']==$chief_complaint_values->id){ echo 'selected="selected"'; } ?> value="<?php echo $chief_complaint_values->id; ?>"><?php echo $chief_complaint_values->chief_complaints; ?></option>
                                                <?php
                                                   }
                                                   }
                                                   ?>
                                             </select>
                                          &nbsp; <a title="Add Chief Complaint" class="btn-new" href="javascript:void(0)" onclick="add_chief_complaint()"><i class="fa fa-plus"></i> New</a>
                                            
                                             
                                          </div>
                                       </div>
                                       <div class="row">
                                          <div class="col-md-4">
                                             <label>Teeth Type</label>
                                          </div>
                                          <div class="col-md-6">
                                             <select name="teeth_name" class="m_select_btn" id="teeth_name" onchange="get_open_chart(); return false;" >
                                                <option value="">Select Teeth Type </option>
                                                <option value="Permanent Teeth">Permanent Teeth</option>
                                                <option value="Deciduous Teeth">Deciduous Teeth</option>
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
                                             <button type="button" class="theme-color" id='modal_add' onclick="get_popup_click(); return false;">
                                             Mapping
                                             </button>
                                             <input type="hidden" class="w-40px" id='get_teeth_popupval' value='' name="get_teeth_popupval">
                                             <input type="text" class="w-40px" id='get_teeth_number_val' name="get_teeth_number_val" placeholder='Tooth Number'>
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
                                          <div class="col-md-4">
                                             <label>Duration</label>
                                          </div>
                                          <div class="col-md-6 duration-box">
                                             <select name="number" id='number'>
                                                <option value="">Number</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                 <option value="11">11</option>

                                                 <option value="12">12</option>

                                             </select>
                                             <select name="time" id="time" >
                                                <option value="">Unit</option>
                                                <option value="1">Days</option>
                                                <option value="2">Week</option>
                                                <option value="3">Month</option>
                                                <option value="4">Year</option>
                                             </select>
                                          </div>
                                          
                                       </div>
                                       
                                       <div class="row">
                                          <div class="col-md-4">
                                            
                                             &nbsp;
                                          </div>
                                          <div class="col-md-2">
                                              <button type="button" onclick="add_chief_complaint_listdata();" class="theme-color">Add</button>
                                              </div>
                                              <div class="col-md-6">
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
                                                   <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_chief(this);">
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
                                                <th>Teeth Type</th>
                                                <th>Tooth No</th>
                                                <th>Reason</th>
                                                <th>Duration</th>
                                              
                                             </tr>
                                          </thead>
                                          <tbody id="dental_data_records">
                                             <?php 
                                                $chief_complain_data_list = $this->session->userdata('chief_complain_data_list'); 
                                                //echo "<pre>"; print_r($chief_complain_data_list);
                                                $i = 0;
                                                if(!empty($chief_complain_data_list))
                                                {
                                                   $i = 1;
                                                   foreach($chief_complain_data_list as $chief_complaint_val)
                                                   {
                                                    //print_r($chief_complaint_val);
                                                
                                                    ?>
                                             <tr>
                                                <td>
                                                   <input type="checkbox" class="part_checkbox_chief booked_checkbox" name="chief_complaint[]" value="<?php echo $chief_complaint_val['chief_id']; //$chief_complaint_val['chief_complaint_id'] ?>" >
                                                </td>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $chief_complaint_val['chief_complaint_value']; ?></td>
                                                <td><?php echo $chief_complaint_val['teeth_name']; ?></td>
                                                <td><?php echo $chief_complaint_val['get_teeth_number_val']; ?></td>
                                                <td><?php echo $chief_complaint_val['reason']; ?></td>
                                                
                                      <td><?php echo $chief_complaint_val['number'].$chief_complaint_val['time']; ?></td>
                                             </tr>
                                             <?php
                                                $i++;
                                                }
                                                }
                                                ?> 
                                             <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_chief_vals();">
                                             <i class="fa fa-trash"></i> Delete
                                             </a>
                                             <?php  if(!empty($form_error)){ ?>    
                                             <tr>
                                                <td colspan="5"><?php echo form_error('chief_complaint');  ?></td>
                                             </tr>
                                             <?php } ?>
                                          </tbody>
                                       </table>
                                    </div>
                                 </div>
                              </div>


                              <!-- row -->
                              <?php } ?>

                              <?php 
                    if(strcmp(strtolower($value->setting_name),'previous_history')=='0'){ ?>
               <div id="tab_previous_history" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
     <div class="row">
  <div class="col-md-12 ta-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Disease Name</label>
            </div>

            <div class="col-md-8">
             <select name="disease_name" class="m_select_btn" id="disease_id" style="width:200px;">
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
            &nbsp; <a title="Add Disease" class="btn-new" href="javascript:void(0)" onclick="add_disease()"><i class="fa fa-plus"></i> New</a>
              
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
             <input type="text" name="operation_date" class="datepicker" value="" readonly />
            </div>
            <div class="col-md-2">
              
            </div>

       
           <div class="col-md-2">
              
            </div>
          </div>
          
        <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
            <button type="button" onclick="add_disease_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_history_disease_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_disease(this);">
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
                       $date_dis='';
  if((!empty($previous_history_disease_val['operation_date'])) && ($previous_history_disease_val['operation_date']!='0000-00-00') &&($previous_history_disease_val['operation_date']!='1970-01-01'))

  {
    $date_dis=date('d-m-Y', strtotime($previous_history_disease_val['operation_date']));

  }
  else
  {
    $date_dis='';
  }

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox_disease booked_checkbox" name="disease_name[]" value="<?php echo $previous_history_disease_val['prev_id']; //$previous_history_disease_val['disease_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previous_history_disease_val['disease_value']; ?></td>
                  <td><?php echo $previous_history_disease_val['disease_details']; ?></td>
                  <td><?php echo $previous_history_disease_val['operation']; ?></td>
                  <td><?php echo $date_dis; ?></td>
              
                  
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
  <div class="col-md-12 ta-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Allergy</label>
            </div>

            <div class="col-md-8">
             <select name="allergy_id" class="m_select_btn" id="allergy_id" style="width:200px;">
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
            &nbsp; <a title="Add Allergy" class="btn-new" href="javascript:void(0)" onclick="add_allergy()"><i class="fa fa-plus"></i> New</a>
              
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
            <div class="col-md-4">
              <label>Duration</label>
            </div>

            <div class="col-md-6 duration-box">
             <select name="number" id='number_allergy'>
              <option value="">Number</option>
             
              <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                 <option value="11">11</option>

                                                 <option value="12">12</option>


            </select> 
             <select name="time" id="time_allergy" >
              <option value="">Unit</option>
             
                <option value="1">Day</option>
                <option value="2">Week</option>
                <option value="3">Month</option>
                <option value="4">Year</option>

            </select> 
            </div>
           <div class="col-md-2">
              
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
            <button type="button" onclick="add_allergy_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_allergy_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_allergy(this);">
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
             foreach($previous_allergy_data as $previous_allergy_val)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox_allergy booked_checkbox" name="allergy[]" value="<?php echo $previous_allergy_val['aller_id'];//$previous_allergy_val['allergy_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $previous_allergy_val['allergy_value']; ?></td>
                  <td><?php echo $previous_allergy_val['reason']; ?></td>
                  <td><?php echo $previous_allergy_val['number'].$previous_allergy_val['time']; ?></td>
                  
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
  <div class="col-md-12 tb-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Habit Name</label>
            </div>

            <div class="col-md-8">
             <select name="habit_id" class="m_select_btn" id="habit_id" style="width:200px;">
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
             &nbsp; <a title="Add Habit" class="btn-new" href="javascript:void(0)" onclick="add_oral_habit()"><i class="fa fa-plus"></i> New</a>
               
            </div>
          </div>


          <div class="row">
            <div class="col-md-4">
              <label>Duration</label>
            </div>

            <div class="col-md-6 duration-box">
             <select name="number" id='number_oral_habit'>
              <option value="">Number</option>
             
               <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                 <option value="11">11</option>

                                                 <option value="12">12</option>


            </select> 
             <select name="time" id="time_oral_habit" >
              <option value="">Unit</option>
             
                <option value="1">Day</option>
                <option value="2">Week</option>
                <option value="3">Month</option>
                <option value="4">Year</option>

            </select> 
            </div>
           <div class="col-md-2">
             
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
             <button type="button" onclick="add_oral_habit_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_oral_habit_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_oral_habit(this);">
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
                  <input type="checkbox" class="part_checkbox_oral_habit booked_checkbox" name="habit[]" value="<?php echo $previousoralhabitdata['hab_id']; ?>" >
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

         <?php if(strcmp(strtolower($value->setting_name),'investigation')=='0'){  ?>
        <div id="tab_investigation" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row">
  <div class="col-md-12 ta-content dental-chart" id="chief">
  <div class="row">
    <div class="col-md-12">
      <div class="dent-type">
         <?php foreach ($investigation_category_list as $investigation)
            {?>
        <div class="btn-box1"><?php echo $investigation->investigation_sub ?></div>                  
        <div class="btn-flex">
        <?php $investigation_sub_category_list = get_dental_investigation_sub_category($investigation->id); ?>   <?php foreach ($investigation_sub_category_list as $key=>$investigation_sub_category){?>   
          <div><input type="checkbox" name="check_sub_category[]" id="sub_category-<?php echo $investigation_sub_category->id; ?>"  value="<?php echo $investigation_sub_category->id; ?>" onClick="add_sub_category('<?php echo $investigation_sub_category->id; ?>','<?php echo $investigation_sub_category->investigation_sub; ?>');"  <?php if(isset($investigation_cat[$investigation_sub_category->id])){ echo 'checked'; } ?> ><?php echo $investigation_sub_category->investigation_sub; ?>
</div>
<?php } ?>

        </div>
        <?php } ?>

  
      </div> 
     
      <!-- dent-type -->
      <div class="row">
        <div class="col-md-3">
          <div class="btn-box2">Investigations</div>
        </div>
        <div class="col-md-3">
          <div class="btn-box2">Tooth Number</div>
        </div>
        <div class="col-md-3">
          <div class="btn-box2">Remarks</div>
        </div>
        <div class="col-md-3"></div>
      </div>
      <?php if(!empty($investigation_template_data)) 
      { foreach($investigation_template_data as $key=>$value1) {
      ?>
      <div id="row-<?php echo $value1->sub_category_id ;?>">
        <div class="row">
          <div class="col-md-3"><div class="btn-text"><?php echo $value1->investigation_sub;?></div></div>
          <div class="col-md-3"><div class="btn-text"><select name="sub_category[<?php echo $value1->sub_category_id; ?>][name][]" class="w-150px m_select_btn" id="teeth_name_sub_category<?php echo $value1->sub_category_id; ?>" onchange="get_open_chart_sub_category(<?php echo $value1->sub_category_id; ?>); return false;"><option value="">Select Teeth Type </option><option value="Permanent Teeth"<?php if($value1->teeth_name=="Permanent Teeth"){ echo 'selected="selected"'; } ?>>Permanent Teeth</option><option value="Deciduous Teeth" <?php if($value1->teeth_name=="Deciduous Teeth"){ echo 'selected="selected"'; } ?>>Deciduous Teeth</option></select><button type="button" class="theme-color" onclick="get_popup_click_sub_category('<?php echo $value1->sub_category_id; ?>'); return false;" id="modal_add_sub_category<?php echo $value1->sub_category_id; ?>" class="theme-color"> Mapping</button></div></div>

     

      <div class="col-md-3"><div class="btn-text"><input type="text" name="sub_category[<?php echo $value1->sub_category_id; ?>][remarks][]" value="<?php echo $value1->remarks; ?>" id="remarks-<?php  echo $value1->sub_category_id; ?>"></div></div>

      <div class="col-md-3"> <input type="text" class="w-40px" readonly placeholder='Tooth Number' id="get_teeth_number_val_sub_category<?php echo $value1->sub_category_id; ?>" value="<?php echo $value1->teeth_no ?>" name="sub_category[<?php echo $value1->sub_category_id; ?>][teeth_no][]" value="" ><input type="hidden" class="w-40px" id="get_teeth_popupval_sub_category<?php echo $value1->sub_category_id; ?>" value="" name="get_teeth_popupval_sub_category[<?php echo $value1->sub_category_id; ?>]"></div>

    </div>     </div> <?php }} ?>
       <div id="sub_category_list">
</div>
  

     
    </div>
  </div>
  
   
  
   
  </div>
</div> 

        </div>
        <?php } ?>

        <?php if(strcmp(strtolower($value->setting_name),'diagnosis')=='0'){  ?>
        <div id="tab_diagnosis" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
<div class="row">
  <div class="col-md-12 tb-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Diagnosis Name</label>
            </div>

            <div class="col-md-8">
             <select name="diagnosis_id" class="m_select_btn" id="diagnosis_id" style="width:200px;">
              <option value="">Diagnosis Name</option>
               <?php
              if(!empty($diagnosis_list))
              {
                foreach($diagnosis_list as $diagnosis)
                {
                  ?>
                    <option <?php if($form_data['diagnosis_id']==$diagnosis->id){ echo 'selected="selected"'; } ?> value="<?php echo $diagnosis->id; ?>"><?php echo $diagnosis->diagnosis; ?></option>
                  <?php
                }
              }
              ?>
            </select>
            &nbsp; <a title="Add Diagnosis" class="btn-new" href="javascript:void(0)" onclick="add_diagnosis()"><i class="fa fa-plus"></i> New</a>
            </div>

           
          </div>
<div class="row">
                                          <div class="col-md-4">
                                             <label>Teeth Type</label>
                                          </div>
                                          <div class="col-md-6">
                                             <select name="teeth_name" class="m_select_btn" id="teeth_name_d" onchange="get_open_chart_diagnosis(); return false;" >
                                                <option value="">Select Teeth Type </option>
                                                <option value="Permanent Teeth">Permanent Teeth</option>
                                                <option value="Deciduous Teeth">Deciduous Teeth</option>
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
                                             <button type="button" class="theme-color" id='modal_add_diagnosis' onclick="get_popup_click_diagnosis(); return false;">
                                             Mapping
                                             </button>
                                             <input type="hidden" class="w-40px" id='get_teeth_popupval_diagnosis' value='' name="get_teeth_popupval_diagnosis">
                                             
                                          </div>
                                          <div class="col-md-2">
                                            
                                          </div>
                                       </div>

            <div class="row">
                <div class="col-md-4">
                   
                </div>
                <div class="col-md-6">
                   
                   <input type="text" class="w-40px" placeholder='Tooth Number' id='get_teeth_number_val_diagnosis' name="get_teeth_number_val_diagnosis" readonly>
                </div>
                <div class="col-md-2">
                  
                </div>
             </div>
                               
            <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
             <button type="button" onclick="add_diagnosis_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='diagnosis_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_diagnosis(this);">
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
              <th>Diagnosis Name</th>
                    <th>Teeth Type</th>
                      <th>Tooth No.</th>
                   

          </tr>
           </thead>
          <?php 
          $diagnosis_list_data = $this->session->userdata('diagnosis_list_data'); 
          $i = 0;
          if(!empty($diagnosis_list_data))
          {
             $i = 1;
             foreach($diagnosis_list_data as $diagnosis_list_vals)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox_diagnosis booked_checkbox" name="diagnosis[]" value="<?php echo $diagnosis_list_vals['diag_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $diagnosis_list_vals['diagnosis_value']; ?></td>
                  <td><?php echo $diagnosis_list_vals['teeth_name_d']; ?></td>
                  <td><?php echo $diagnosis_list_vals['get_teeth_number_val_diagnosis']; ?></td>
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_diagnosis_vals();">
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

                      //echo "<pre>"; print_r($medicine_template_data); exit;
                      if(!empty($medicine_template_data))
                      { 
                           $l=1;
                          foreach ($medicine_template_data as $prescription_presc) 
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

                        <input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="w-100px medicine_valss<?php echo $l; ?> first_txt_cap" id="medicine_valss<?php echo $l; ?>" onkeyup="get_medicine_dose_autocomplete(<?php echo $l; ?>);" value="<?php echo $prescription_presc->medicine_name; ?>">
                         <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->id; ?>"> 
                        </td>

                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_brand]" class="w-100px" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" id="salt<?php echo $l; ?>" class="w-100px" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" id="type<?php echo $l; ?>" class="input-small medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class="input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>" onkeyup="get_dose_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class="w-100px medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>" onkeyup="get_duration_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="w-100px medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>" onkeyup="get_frequency_autocomplete(<?php echo $l; ?>);"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="w-100px medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>" onkeyup="get_advice_autocomplete(<?php echo $l; ?>);"></td>
                        <?php } }
                        ?>                        
                        <td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td>
                    </tr>
                    <?php $l++; } }else{ ?>

                    <tr>
                        <?php
                        foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_name]" class="medicine_val w-100px first_txt_cap">
                        <input type="hidden" name="medicine_id[]" id="medicine_id">
                        </td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_brand]" class="w-100px"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_salt]" class="w-100px" ></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" name="prescription[1][medicine_type]" class="input-small w-100px"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" name="prescription[1][medicine_dose]" class="input-small"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-name w-100px"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-name w-100px"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-name w-100px"></td>
                        <?php } 
                      }
                   ?>
                    <td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td>
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
        <div id="tab_treatment" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
<div class="row">
  <div class="col-md-12 tb-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Treatment Name</label>
            </div>

            <div class="col-md-8">
             <select name="treatment_id" class="m_select_btn" id="treatment_id" style="width:200px;">
              <option value="">Treatment Name</option>
               <?php
              if(!empty($treatment_list))
              {
                foreach($treatment_list as $treatment_val)
                {
                  ?>
                    <option <?php if($form_data['treatment_id']==$treatment_val->id){ echo 'selected="selected"'; } ?> value="<?php echo $treatment_val->id; ?>"><?php echo $treatment_val->treatment; ?></option>
                  <?php
                }
              }
              ?>
            </select>
             &nbsp; <a title="Add Treatment" class="btn-new" href="javascript:void(0)" onclick="add_treatment()"><i class="fa fa-plus"></i> New</a>
           
            </div>
          </div>
          
          
          
        <div class="row">
              <div class="col-md-4">
                 <label>Teeth Type</label>
              </div>
              <div class="col-md-6">
                 <select name="teeth_name" class=" m_select_btn" id="teeth_name_treatment" onchange="get_open_chart_treatment(); return false;" >
                    <option value="">Select Teeth Type </option>
                    <option value="Permanent Teeth">Permanent Teeth</option>
                    <option value="Deciduous Teeth">Deciduous Teeth</option>
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
                 <button type="button" class="theme-color" id='modal_add_treatment' onclick="get_popup_click_treatment(); return false;">
                 Mapping
                 </button>
                 <input type="hidden" class="w-40px" id='get_teeth_popupval_treatment' value='' name="get_teeth_popupval_treatment">
                 
              </div>
              <div class="col-md-2">
                
              </div>
           </div>

           <div class="row">
                <div class="col-md-4">
                   
                </div>
                <div class="col-md-6">
                   
                   <input type="text" class="w-40px" id='get_teeth_number_val_treatment' name="get_teeth_number_val_treatment" placeholder='Tooth Number' readonly>
                </div>
                <div class="col-md-2">
                 
                </div>
             </div>
             
             <div class="row">
            <div class="col-md-4">
              <label>Material</label>
            </div>

            <div class="col-md-8">
             <select name="treatment_type_id" class="m_select_btn selectpicker" id="treatment_type_id" style="width:200px;" data-live-search="true" multiple="multiple">
              
               <?php
              if(!empty($treatment_type_list))
              {
                foreach($treatment_type_list as $treatment_type)
                {
                  ?>
                    <option <?php if($form_data['treatment_id']==$treatment_type->id){ echo 'selected="selected"'; } ?> value="<?php echo $treatment_type->id; ?>"><?php echo $treatment_type->treatment; ?></option>
                  <?php
                }
              }
              ?>
            </select>
             &nbsp; <a title="Add Material" class="btn-new" href="javascript:void(0)" onclick="add_treatment_type()"><i class="fa fa-plus"></i> New</a>
           
            </div>
          </div>
             
             <div class="row">
            <div class="col-md-4">
              <label>Remarks</label>
            </div>

            <div class="col-md-8">
             <textarea name="treatment_remarks" class="m_input_default" id="treatment_remarks" maxlength="250"></textarea>
           
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
              <button type="button" onclick="add_treatment_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='treatment_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_treatment(this);">
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
              <th>Treatment Name</th>
             
                    <th>Teeth Type</th>
                      <th>Tooth No.</th>
                       <th>Material</th>
                      <th>Remarks</th>
                   

          </tr>
           </thead>
          <?php 
          $treatment_list_data_values = $this->session->userdata('treatment_list_data_values'); 
          $i = 0;
          if(!empty($treatment_list_data_values))
          {
             $i = 1;
             foreach($treatment_list_data_values as $treatment_list_data_vals)
             {
              //print_r($chief_complaint_val);

              ?>
                <tr>
                  <td>
                  <input type="checkbox" class="part_checkbox_treatment booked_checkbox" name="treatment[]" value="<?php echo $treatment_list_data_vals['treat_id']; ?>" >
                  </td>
                  <td><?php echo $i; ?></td>
                  <td><?php echo $treatment_list_data_vals['treatment_value']; ?></td>
                 
                  <td><?php echo $treatment_list_data_vals['teeth_name_treatment']; ?></td>
                  <td><?php echo $treatment_list_data_vals['get_teeth_number_val_treatment']; ?></td>
                   <td><?php echo $treatment_list_data_vals['treatment_type_id']; ?></td>
                   <td><?php echo $treatment_list_data_vals['treatment_remarks']; ?></td>
                </tr>
              <?php
              $i++;
             }
          }
          ?> 
          <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_treatment_vals();">
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
         <?php  if(strcmp(strtolower($value->setting_name),'advice')=='0'){  ?>
        <div id="tab_advice" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
        <div class="row">
    <div class="col-md-12 tb-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Advice</label>
            </div>

            <div class="col-md-8">
             <select name="advice_id" class="m_select_btn" id="advice_id" style="width:200px;">
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
             &nbsp; <a title="Add Habit" class="btn-new" href="javascript:void(0)" onclick="add_oral_habit()"><i class="fa fa-plus"></i> New</a>
            
            </div>
          </div>
            
            <div class="row">
            <div class="col-md-4">&nbsp;</div>
            <div class="col-md-2">
              <button type="button" onclick="add_advice_listdata();" class="theme-color">Add</button>
            </div>
            <div class="col-md-6"></div>
        </div>

       
        </div>

      </div>
      <div class="col-md-7" >
        
        <div class="table-box">
          <table class="table table-bordered" id='previous_advice_list'>
              <thead>
              <tr>
              <th align="center" width="">
                <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_advice(this);">
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
                  <input type="checkbox" class="part_checkbox_advice booked_checkbox" name="advice[]" value="<?php echo $previousadvicedata['advi_id']; ?>" >
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
    <div class="col-md-12 tb-content dental-chart">
    <div class="row tab-pane fade in active" id="chief">
      <div class="col-md-5" >
        <div class="box-left">
          <div class="row">
            <div class="col-md-4">
              <label>Next Appointment</label>
            </div>
            <div class="col-md-6">
          <!--    <input type="hidden" name="check_appointment" <?php //if($form_data['check_appointment']==1){//echo 'checked="checked"';} ?> value="<?php //echo $form_data['check_appointment']; ?>"> -->
           <input type="checkbox" name="check_appointment" value="1"<?php if($form_data['check_appointment']==1){ echo 'checked="checked"'; } ?> id='check_appointment'>
            </div>

           
          </div>
          <?php $display=''; 
          if($form_data['check_appointment']!=NULL) {
            if($form_data['check_appointment']==1)
            {
              $display='display: block';
            }
            else
            {
              $display='display: none';
            }
          }
          else
          {
            $display='display: none';

           }
           if(!empty($form_data['next_app_date']) && $form_data['next_app_date']!='1970-01-01')
           {
             $nextappointment_date = date('d-m-Y',strtotime($form_data['next_app_date']));   
           }
           else
           {
               $nextappointment_date = $form_data['next_appointment_date'];
           }
            ?>
          
          <div class="row" id="date_time_next" style="<?php echo $display; ?>">
            <div class="col-md-4">
              <label>Appointment Date Time <?php echo $nextappointment_date; ?></label>
            </div>

            <div class="col-md-6">
             <input type="text" name="next_appointment_date" id="next_appointment_date"  class="datepickertime date "  data-date-format="dd-mm-yyyy HH:ii"  value="<?php echo $nextappointment_date; ?>" readonly /> 
            </div>

           
          </div>


          <div class="row">
          
          </div>
          <div class=" m-t-10">
                                             <div class="col-xs-8">
                                                <div class="well"><h4>Reason</h4>
                                                      <textarea class="form-control ckeditor" rows="8" id="next_reason" name="next_reason"><?php echo $form_data['next_reason']; ?></textarea>
                                                </div>
                                             </div>
                                             <div class="col-xs-4">
                                                <div class="well tab-right-scroll">
                                                      <!-- <input type="text" name="chief_complaints_search" data-appendId="next_reason_data" class="dropdown-box select-box-search-event" style="width: 300px;" placeholder="Type here for next appointment data"> -->
                                                      <a href="<?=base_url('next_appointment')?>" class="btn btn-success btn-xs" target="_blank"><i class="fa fa-plus"></i> Add new</a>
                                                      <select size="9" class="dropdown-box" name="next_reason_data"  id="next_reason_data" multiple="multiple" >
                                                         <?php
                                                            if(isset($next_appointment_list) && !empty($next_appointment_list))
                                                            {
                                                            foreach($next_appointment_list as $ps)
                                                            {
                                                                  echo '<option class="grp" value="'.$ps->id.'">'.$ps->name.'</option>';
                                                            }
                                                            }
                                                         ?>
                                                      </select>

                                                </div>
                                             </div>
                                          </div>

        </div>

      </div>
      <div class="col-md-7">
      </div>
      
    </div> <!-- row -->
  
   
  
   
  </div>
</div>
        </div>
         <?php } ?>
              <?php 
                                 $j++;
                                 }
                                 ?>
                           </div>
                        </div>
                        <!-- ******************** -->
</div> <!-- 11 -->

<div class="col-xs-1">
      <div class="prescription_btns">
      <button type="button" class="btn-save" id="form_submit"> <i class="fa fa-sign-out"></i> Save</button>
      
      
      <a target="_blank" href="<?php echo base_url('dental/opdprescriptionhistory/lists/'.$form_data['patient_id']); ?>"  class="btn-anchor" >
          <i class="fa fa-history"></i> History
        </a>
        
      <a href="<?php echo base_url('prescription'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>


      </div>
        

                     <!--  col-11 -->
                    <!-- <div class="col-md-1 btn-box">
                        <button type="button" class="btn-save" id="form_submit"> <i class="fa fa-sign-out"></i> Save</button>
                        <a href="< ?php echo base_url('prescription'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
                       
                     </div>-->
                     <!-- col-1 -->
                 
                  <!-- row -->
               </div>
            </form>
            <!-- container fluid -->
         </section>
         <?php
            $this->load->view('include/footer');
            ?>
      </div>
<script>   
function add_sub_category(ids,vals)
{ // alert(ids);
   $('#'+ids+'sub_category').remove(); 
   if ($('#sub_category-'+ids).is(":checked"))
   {  
      $("#sub_category_list").append('<div id ="'+ids+'sub_category"><div class="row"><div class="col-md-3"><div class="btn-text">'+vals+'</div></div><div class="col-md-3"><div class="btn-text"><select name="sub_category['+ids+'][name][]" class="w-150px m_select_btn" id="teeth_name_sub_category'+ids+'" onchange="get_open_chart_sub_category('+ids+'); return false;"><option value="">Select Teeth Type </option><option value="Permanent Teeth">Permanent Teeth</option><option value="Deciduous Teeth">Deciduous Teeth</option></select><button type="button" class="theme-color" onclick="get_popup_click_sub_category('+ids+'); return false;" id="modal_add_sub_category'+ids+'" class="theme-color"> Mapping</button></div></div><div class="col-md-3"><div class="btn-text"><input type="text" name="sub_category['+ids+'][remarks][]" id="remarks-'+ids+'"></div></div><div class="col-md-3"><input type="text" class="w-40px" id="get_teeth_number_val_sub_category'+ids+'" name="sub_category['+ids+'][teeth_no][]" value="" readonly placeholder="Tooth Number" readonly><input type="hidden" class="w-40px" id="get_teeth_popupval_sub_category'+ids+'" value="" name="get_teeth_popupval_sub_category['+ids+']"></div><div>');
      
   }
   else
   {
    $('#'+ids+'sub_category').remove(); 
   }
}
</script>
<script>
function get_open_chart_sub_category(ids)
         {
          //alert(ids);
          var teeth_name = $('#teeth_name_sub_category'+ids+' option:selected').text();
         // alert(teeth_name);
          $("#get_teeth_popupval_sub_category"+ids).val(teeth_name);
          //alert(teeth_name);        
         }


///get_popup_click
         function get_popup_click_sub_category(ids)
         {
          // alert(ids);
           var num_val=$("#get_teeth_popupval_sub_category"+ids).val();
           //alert(num_val);        
           if(num_val=='Permanent Teeth')
         {
          //alert(num_val);
          var url='<?php echo base_url().'dental/dental_prescription/add_perma_sub_category/' ?>';
           // alert(url);
         var $modal = $('#load_add_chart_perma_category_modal_popup');
         $('#modal_add_sub_category'+ids+'').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_perma_sub_category/' ?>/'+ids+'',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         });
         
          }
         
              else if(num_val=='Deciduous Teeth')
         {
         // alert(num_val);
          var url='<?php echo base_url().'dental/dental_prescription/add_decidous_sub_category/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
          var $modal = $('#load_add_chart_perma_category_modal_popup');
         $('#modal_add_sub_category'+ids).on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous_sub_category/' ?>/'+ids+'',
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
<script type="text/javascript">
$(function () {
        $("#check_appointment").click(function () {
            if ($(this).is(":checked")) {
                $("#date_time_next").show();
                
            } else {
                $("#date_time_next").hide();
                
            }
        });
    });


    $(".datepickertime").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });


      $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true, 
  });

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
function add_advice_listdata()
  {
    //alert();
    var advice_id = $('#advice_id').val();
    var advice_value = $('#advice_id option:selected').text();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/dental_prescription/advice_list/", 
            dataType: "json",
            data: 'advice_id='+advice_id+'&advice_value='+advice_value,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_advice_list').html(result.html_data);  
             
               
            } 
          });
    
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
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_advice');?>",
              data: 'advice='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_advice_list').html(result.html_data);    
              }
          });
      
   }
  }

 function add_treatment()
{
  var $modal = $('#load_add_dental_treatment_modal_popup');
  $modal.load('<?php echo base_url().'dental/treatment/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_treatment_type()
{
  var $modal = $('#load_add_dental_treatment_modal_popup');
  $modal.load('<?php echo base_url().'dental/treatment_type/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}



  function add_treatment_listdata()
  {
    //alert();
    var treatment_id = $('#treatment_id').val();
    var treatment_value = $('#treatment_id option:selected').text();
    var teeth_name_treatment = $('#teeth_name_treatment option:selected').text();
   var get_teeth_number_val_treatment=$('#get_teeth_number_val_treatment').val();
   var treatment_type_id=$('#treatment_type_id').val();
   var treatment_remarks=$('#treatment_remarks').val();
   
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/dental_prescription/treatment_list/", 
            dataType: "json",
            data: 'treatment_id='+treatment_id+'&treatment_value='+treatment_value+'&teeth_name_treatment='+teeth_name_treatment+'&get_teeth_number_val_treatment='+get_teeth_number_val_treatment+'&treatment_type_id='+treatment_type_id+'&treatment_remarks='+treatment_remarks,
            
            success: function(result)
            {

              $('#treatment_list').html(result.html_data);  
             
               
            } 
          });
    
  }

function delete_treatment_vals() 
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
       remove_treatment_vals(allVals);

  }


  function remove_treatment_vals(allVals)
  {    
   if(allVals!="")
   {
  
   var treatment_id = $('#treatment_id').val();
    var treatment_value = $('#treatment_value option:selected').text();
    var teeth_name_treatment = $('#teeth_name_treatment option:selected').text();
   var get_teeth_number_val_treatment=$('#get_teeth_number_val_treatment').val();
     //alert(chief_complain);
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_treatment_list');?>",
              data: 'treatment='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#treatment_list').html(result.html_data);    
              }
          });
      
   }
  }

 function add_diagnosis()
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


       function add_diagnosis_listdata()
  {
    //alert();
    var diagnosis_id = $('#diagnosis_id').val();
    var diagnosis_value = $('#diagnosis_id option:selected').text();
    var teeth_name_d = $('#teeth_name_d option:selected').text();
   var get_teeth_number_val_diagnosis=$('#get_teeth_number_val_diagnosis').val();
    
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>dental/dental_prescription/diagnosis_list/", 
            dataType: "json",
            data: 'diagnosis_id='+diagnosis_id+'&diagnosis_value='+diagnosis_value+'&teeth_name_d='+teeth_name_d+'&get_teeth_number_val_diagnosis='+get_teeth_number_val_diagnosis,
            
            success: function(result)
            {

              $('#diagnosis_list').html(result.html_data);  
             
               
            } 
          });
    
  }

function delete_diagnosis_vals() 
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
       remove_diagnosis_vals(allVals);

  }


  function remove_diagnosis_vals(allVals)
  {    
   if(allVals!="")
   {
  
    var diagnosis_id = $('#diagnosis_id').val();
    var diagnosis_value = $('#diagnosis_id option:selected').text();
    var teeth_name_d = $('#teeth_name_d option:selected').text();
     //alert(chief_complain);
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_diagnosis_list');?>",
              data: 'diagnosis='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#diagnosis_list').html(result.html_data);    
              }
          });
      
   }
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
            url: "<?php echo base_url(); ?>dental/dental_prescription/oral_habit_list/", 
            dataType: "json",
            data: 'habit_id='+habit_id+'&oral_habit_value='+oral_habit_value+'&number='+number+'&time='+time,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_oral_habit_list').html(result.html_data);  
             
               
            } 
          });
    
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
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_oral_habit');?>",
              data: 'habit='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_oral_habit_list').html(result.html_data);    
              }
          });
      
   }
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
            url: "<?php echo base_url(); ?>dental/dental_prescription/allergy_list/", 
            dataType: "json",
            data: 'allergy_id='+allergy_id+'&allergy_value='+allergy_value+'&reason='+reason+'&number='+number+'&time='+time,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_allergy_list').html(result.html_data);  
             
               
            } 
          });
    
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
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_allergy');?>",
              data: 'allergy='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_allergy_list').html(result.html_data);    
              }
          });
      
   }
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

  
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('dental/dental_prescription/remove_previous_history_disease');?>",
              data: 'disease_name='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#previous_history_disease_list').html(result.html_data);    
              }
          });
      
   }
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
            url: "<?php echo base_url(); ?>dental/dental_prescription/previous_history_disease_data_list/", 
            dataType: "json",
            data: 'disease_id='+disease+'&disease_value='+disease_value+'&disease_details='+disease_details+'&operation='+operation+'&operation_date='+operation_date,
            
            success: function(result)
            {
              //alert(result);

              $('#previous_history_disease_list').html(result.html_data);  
             
               
            } 
          });
    
  }

   function get_open_chart_treatment()
         {

          var teeth_name = $('#teeth_name_treatment option:selected').text();
          $("#get_teeth_popupval_treatment").val(teeth_name);
          //alert(teeth_name);
        
         }

///get_popup_click
         function get_popup_click_treatment()
         {
            //alert();
         var num_val=$("#get_teeth_popupval_treatment").val();
         
             if(num_val=='Permanent Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_perma_treatment/' ?>';
            
          var $modal = $('#load_add_chart_perma_treatment_modal_popup');
         //$('#modal_add_treatment').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_perma_treatment/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         //});
         
          }
         
              else if(num_val=='Deciduous Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_decidous_treatment/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
                var $modal = $('#load_add_chart_perma_treatment_modal_popup');
        // $('#modal_add_treatment').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous_treatment/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
        // });
         
          }
         
         
          }

  //get_open_chart
      function get_open_chart_diagnosis()
         {

          var teeth_name = $('#teeth_name_d option:selected').text();
          $("#get_teeth_popupval_diagnosis").val(teeth_name);
          //alert(teeth_name);
        
         }

///get_popup_click
         function get_popup_click_diagnosis()
         {
         var num_val=$("#get_teeth_popupval_diagnosis").val();
         
             if(num_val=='Permanent Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_perma_diagnosis/' ?>';
            
                var $modal = $('#load_add_chart_perma_diagnosis_modal_popup');
         //$('#modal_add_diagnosis').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_perma_diagnosis/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         //});
         
          }
         
              else if(num_val=='Deciduous Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_decidous/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
                var $modal = $('#load_add_chart_perma_diagnosis_modal_popup');
         //$('#modal_add_diagnosis').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous_diagnosis/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
         //});
         
          }
         
         
          }

//get_open_chart
      function get_open_chart()
         {


          var teeth_name = $('#teeth_name option:selected').text();
          $("#get_teeth_popupval").val(teeth_name);

        
         }

///get_popup_click
         function get_popup_click()
         {
         var num_val=$("#get_teeth_popupval").val();
         
             if(num_val=='Permanent Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_perma/' ?>';
            
                var $modal = $('#load_add_chart_perma_modal_popup');
        // $('#modal_add').on('click', function(){
         $modal.load('<?php echo base_url().'dental/Dental_prescription/add_perma/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
        // });
         
          }
         
              else if(num_val=='Deciduous Teeth')
         {
          var url='<?php echo base_url().'dental/dental_prescription/add_decidous/' ?>';
               //$('#load_add_chart_de_modal_popup').modal('hide');
                var $modal = $('#load_add_chart_perma_modal_popup');
        // $('#modal_add').on('click', function(){
         $modal.load('<?php echo base_url().'dental/dental_prescription/add_decidous/' ?>',
         {
          //'id1': '1',
          //'id2': '2'
          },
         function(){
         $modal.modal('show');
         });
         
        // });
         
          }
         
         
          }

        /*$('#form_submit').on("click",function(){
       $('#dental_prescription_form').submit();
  })*/
         $('#form_submit').on("click",function()
         {
             
             $('#confirm').modal({
              backdrop: 'static',
              keyboard: false
            })
            .one('click', '#confirm_submit', function(e)
            { 
                $(':input[id=form_submit]').prop('disabled', true);
                $('#dental_prescription_form').submit(); 
            });  
           
           
             
           })
         
         function tab_links(vals)
           {
         
             $('.inner_tab_box').removeClass('in');
             $('.inner_tab_box').removeClass('active');  
             $('#'+vals).addClass('in');
             $('#'+vals).addClass('active');
           }
         
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
              url: "<?php echo base_url('dental/dental_prescription/remove_dental_chief');?>",
              data: 'chief_complaint='+allVals,
              dataType: "json",
              success: function(result) 
              { 
                  $('#chief_complain_list').html(result.html_data);    
              }
          });
      
   }
  }
     
     
     function get_medicine_dose_autocomplete(row_id)
{
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_medicine_auto_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
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
          //alert(names);
          $('#medicine_valss'+row_id).val(names[0]);
          $('#type'+row_id).val(names[1]);
          $('#brand'+row_id).val(names[3]);
          $('#salt'+row_id).val(names[2]);
          $('#medicine_id'+row_id).val(names[4]);
          //$(".medicine_val").val(ui.item.value);
          
          return false;
    }   
    
    $("#medicine_valss"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }
 
 //neha 21-2-2019

function get_frequency_autocomplete(row_id)
{  //alert(row_id);
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_frequency_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          //var code = item.split("|");
          return {
            label: item,
            value: item,
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {

          $(".frequency_val"+row_id).val(ui.item.value);
        return false;
    }   
    
    $(".frequency_val"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }

 function get_duration_autocomplete(row_id)
{  //alert(row_id);
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_duration_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          //var code = item.split("|");
          return {
            label: item,
            value: item,
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {

          $(".duration_val"+row_id).val(ui.item.value);
        return false;
    }   
    
    $(".duration_val"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }

 function get_dose_autocomplete(row_id)
{  //alert(row_id);
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_dosage_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          //var code = item.split("|");
          return {
            label: item,
            value: item,
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {

          $(".dosage_val"+row_id).val(ui.item.value);
        return false;
    }   
    
    $(".dosage_val"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }

 function get_advice_autocomplete(row_id)
{  //alert(row_id);
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_advice_vals/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         type: 'country_table',
         row_num : row_id
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          //var code = item.split("|");
          return {
            label: item,
            value: item,
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {

          $(".advice_val"+row_id).val(ui.item.value);
        return false;
    }   
    
    $(".advice_val"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
 }

 //neha end
      
      </script>
      <script>
         function add_chief_complaint_listdata()
          {
            //alert();
             var chief_complain = $('#chief_complaint_id').val();
            var chief_complaint_value = $('#chief_complaint_id option:selected').text();
            var teeth_name = $('#teeth_name option:selected').text();
             var reason = $('#reason').val();
             var get_teeth_number_val=$('#get_teeth_number_val').val();
             var time = $('#time option:selected').text();
             var number = $('#number option:selected').text();
             if(number=='Number')
              {
                number ='';
              }
              if(time=='Unit')
              {
                time ='';
              }
             //alert(chief_complain);
             //alert(chief_complaint_value);
            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>dental/dental_prescription/add_chief_prepcription_list/", 
                    dataType: "json",
                    data: 'chief_complaint_id='+chief_complain+'&teeth_name='+teeth_name+'&reason='+reason+'&number='+number+'&time='+time+'&get_teeth_number_val='+get_teeth_number_val+'&chief_complaint_value='+chief_complaint_value,
                    
                    success: function(result)
                    {
                      //alert(result);
         
                      $('#chief_complain_list').html(result.html_data);
                      $('#get_teeth_number_val').val('');
                     
                       
                    } 
                  });
          }
         
         function add_chief_complaint()
         {
         
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
         
      </script>
<script type="text/javascript">




$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_medicine_auto_vals/'); ?>" + request.term,
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
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_dosage_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_duration_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_frequency_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_advice_vals/'); ?>" + request.term,
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
         $(document).ready(function() {
           $('#load_add_dental_chief_complaints_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
           })
         });
         $(document).ready(function() {
           $('#load_add_dental_teeth_chart_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
           })
         });
         $("button[data-number=1]").click(function(){
            $('#load_add_dental_chief_complaints_modal_popup').modal('hide');
         });
         $("button[data-number=1]").click(function(){
            $('#load_add_dental_teeth_chart_modal_popup').modal('hide');
         });

   
   $(document).ready(function() 
   {
         $('#load_add_dental_disease_modal_popup').on('shown.bs.modal', function(e) {
            $('.inputFocus').focus();
         })
   });

 $("button[data-number=1]").click(function()
 {
    $('#load_add_dental_disease_modal_popup').modal('hide');
});

  $(document).ready(function() {
   $('#load_add_dental_allergy_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});

  
 $("button[data-number=1]").click(function(){
    $('#load_add_dental_allergy_modal_popup').modal('hide');
});

   $(document).ready(function() {
   $('#load_add_dental_oral_habit_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
  $("button[data-number=1]").click(function(){
    $('#load_add_oral_habit_modal_popup').modal('hide');
});
         
         $(document).ready(function() {
   $('#load_add_dental_advice_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});

   $("button[data-number=1]").click(function(){
    $('#load_add_dental_advice_modal_popup').modal('hide');
});
$(document).ready(function() {
   $('#load_add_dental_treatment_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
$("button[data-number=1]").click(function(){
    $('#load_add_dental_treatment_modal_popup').modal('hide');
});
$(document).ready(function() {
   $('#load_add_dental_diagnosis_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
$(document).ready(function() {
   $('#load_add_chart_perma_diagnosis_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
$(document).ready(function() {
   $('#load_add_chart_perma_treatment_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
});
$("button[data-number=1]").click(function(){
    $('#load_add_dental_diagnosis_modal_popup').modal('hide');
});
$("button[data-number=1]").click(function(){
    $('#load_add_chart_perma_category_modal_popup').modal('hide');
});
$("button[data-number=1]").click(function(){
    $('#load_add_chart_perma_diagnosis_modal_popup').modal('hide');
});
$("button[data-number=1]").click(function(){
    $('#load_add_chart_perma_treatment_modal_popup').modal('hide');
});
function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
function toggle_chief(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_chief');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

   function toggle_disease(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_disease');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function toggle_allergy(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_allergy');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function toggle_oral_habit(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_oral_habit');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
    function toggle_diagnosis(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_diagnosis');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
    function toggle_treatment(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_treatment');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function toggle_advice(source) 
  {  
      checkboxes = document.getElementsByClassName('part_checkbox_advice');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
</script>
<script>
function delete_rows(id)
{
   $('#row-id').remove();
}
function delete_rows_sub_category(id)
{
   $('#'+id+'sub_category').remove();
}
</script>
<script>
 $('#template_list').change(function(){  

      var template_id = $(this).val();
     // alert(template_id);
      $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_template_data/"+template_id, 
        success: function(result)
        {
           
           load_values(result);
           load_chief_compalint_data(template_id);
           load_previous_history_data(template_id);
           load_previous_allergy_data(template_id);
           load_previous_oral_habit_data(template_id);
           load_previous_diagnosis_data(template_id);
           load_previous_advice_data(template_id);
           load_previous_treatment_data(template_id);
           load_medicine_values(template_id);
           load_investigation_values(template_id);
        } 
      }); 
  });

   function load_values(jdata)
  { 
       var obj = JSON.parse(jdata);
       var check_appointment = obj.check_appointment;
       //alert(check_appointment);
       $('#check_appointment').val(obj.check_appointment);
      if((check_appointment!='') &&(check_appointment==1))
      {
         $("#check_appointment").prop("checked", true);
        
          $('#date_time_next').show();
      }
      else
      {
           $("#check_appointment").prop("checked", false);
           
           $('#date_time_next').hide();
      }
   
       var appointment_date = obj.next_appointment_date;
   
       $('#next_appointment_date').val(obj.next_appointment_date);
      
       
    };

   function load_previous_history_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_previous_history_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_previous_history_data(result);
        } 
      });
    }

    function load_previous_allergy_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_previous_allergy_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_previous_allergy_data(result);
        } 
      });
    }

    function load_chief_compalint_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_chief_complaint_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_chief_complaint_data(result);
        } 
      });
    }

     function load_previous_oral_habit_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_oral_habit_data/"+template_id, 
        success: function(result)
        {
         //  alert(result);
           get_oral_habit_data(result);
        } 
      });
    }

      function load_previous_diagnosis_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_diagnosis_data/"+template_id, 
        success: function(result)
        {
          // alert(result);
           get_diagnosis_data(result);
        } 
      });
    }

    function load_previous_advice_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_advice_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_advice_data(result);
        } 
      });
    }

    function load_previous_treatment_data(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_treatment_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_treatment_data(result);
        } 
      });
    }

    function load_medicine_values(template_id)
    {
       $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_medicine_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
           get_medicine_data(result);
        } 
      });
    }

     function load_investigation_values(template_id)
    {
       $.ajax({url: "<?php echo base_url(); ?>dental/dental_prescription/get_investigation_data/"+template_id, 
        success: function(result)
        {
           //alert(result);
          get_investigation_data(result);
        } 
      });
    }
   
     function get_chief_complaint_data(result)
    {
      //alert(result);

      var obj = JSON.parse(result);
      var arr = '';
      
      var i=obj.length;
      var m = 1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="chief_complaint[]" class="part_checkbox_chief booked_checkbox" value="'+obj[index].chief_complaint_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].chief_complaint_value+'</td><td>'+obj[index].teeth_name+'</td><td>'+obj[index].get_teeth_number_val+'</td><td>'+obj[index].reason+'</td><td>'+obj[index].number+' '+obj[index].time+'</td></tr>'

    }); 
      $("#chief_complain_list tbody").html(arr);
      //$("#chief_complain_list tr:first").after(arr);
     
    }

        function get_previous_history_data(result)
    { 
      //alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m = 1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="disease_id[]" class="part_checkbox_disease booked_checkbox" value="'+obj[index].disease_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].disease_value+'</td><td>'+obj[index].disease_details+'</td><td>'+obj[index].operation+'</td><td>'+obj[index].operation_date+'</td></tr>'

    }); 
      $("#previous_history_disease_list tbody").html(arr);
     
    }

     function get_previous_allergy_data(result)
    { 
      //alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m = 1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="allergy[]" class="part_checkbox_allergy booked_checkbox" value="'+obj[index].allergy_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].allergy_value+'</td><td>'+obj[index].reason+'</td><td>'+obj[index].number+' '+obj[index].time+'</td></tr>'

    }); 
      $("#previous_allergy_list tbody").html(arr);
     
    }
    
     function get_oral_habit_data(result)
    { //alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m = 1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="habit[]" class="part_checkbox_oral_habit booked_checkbox" value="'+obj[index].oral_habit_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].oral_habit_value+'</td><td>'+obj[index].number+'  '+obj[index].time+'</td></tr>'

    }); 
      $("#previous_oral_habit_list tbody").html(arr);
     
    }

    function get_diagnosis_data(result)
    {// alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m = 1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="diagnosis[]" class="part_checkbox_diagnosis booked_checkbox" value="'+obj[index].diagnosis_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].diagnosis_value+'</td><td>'+obj[index].teeth_name_d+'</td><td>'+obj[index].get_teeth_number_val_diagnosis+'</td></tr>'

    }); 
      $("#diagnosis_list tbody").html(arr);
     
    }


    function get_advice_data(result)
    { //alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m=1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="advice[]" class="part_checkbox_advice booked_checkbox" value="'+obj[index].advice_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].advice_value+'</td></tr>'

    }); 
      $("#previous_advice_list tbody").html(arr);
     
    }

    function get_treatment_data(result)
    {// alert(result);
    
      var obj = JSON.parse(result);
      var arr = '';
      var i=obj.length;
      var m=1;
      $.each(obj, function (index, value) {
       arr +='<tr><td><input type="checkbox"name="treatment[]" class="part_checkbox_treatment booked_checkbox" value="'+obj[index].treatment_id+'"></td><td>'+ m++  +'</td><td>'+obj[index].treatment_value+'</td><td>'+obj[index].teeth_name_treatment+'</td><td>'+obj[index].get_teeth_number_val_treatment+'</td></tr>'

    }); 
      $("#treatment_list tbody").html(arr);
     
    }

    function get_medicine_data(result)
    {
    //alert(result);
      var obj = JSON.parse(result);
      var pres = '';
      pres += '<tbody><tr><?php 
                    $l=0;
                    foreach ($prescription_medicine_tab_setting as $med_value) { ?>                            <td <?php  if($l=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>                                <?php 
                           $l++; 
                            }
                            ?><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_medicine_bytemp()">Add</a></td></tr>';
      i = 1;
      $.each(obj, function (index, value) {       
         pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="medicine_name[]" class="w-100px input-small" value="'+obj[index].medicine_name+'"><input type="hidden" name="medicine_id[]" class="" value="'+obj[index].medicine_id+'"></td> <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_brand[]" class="w-100px input-small" value="'+obj[index].medicine_brand+'"></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_salt[]" class="input-small" value="'+obj[index].medicine_salt+'" ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>                        <td><input type="text" name="medicine_type[]" class="w-100px input-small" value="'+obj[index].medicine_type+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>                        <td><input type="text" name="medicine_dose[]" class="w-100px input-small" value="'+obj[index].medicine_dose+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>                        <td><input type="text" name="medicine_duration[]" class="w-100px medicine-name" value="'+obj[index].medicine_duration+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>                        <td><input type="text" name="medicine_frequency[]" class="w-100px medicine-name" value="'+obj[index].medicine_frequency+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>                        <td><input type="text" name="medicine_advice[]" class="w-100px medicine-name" value="'+obj[index].medicine_advice+'"></td>                        <?php } 
                      } ?> <td><a title="Click to Remove Prescription" href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';

    }); 
      pres += '</tbody>'; 
      $("#prescription_name_table tbody").replaceWith(pres);
    }

function get_medicine_bytemp()
{
  //alert();

  var m=1;
  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px medicine_val'+i+' first_txt_cap"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>   <td><input type="text" name="prescription['+i+'][medicine_brand]" id="brand'+i+'"  class="w-100px" ></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>  <td><input type="text" id="salt'+i+'"  name="prescription['+i+'][medicine_salt]" class="w-100px"  ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="w-100px input-small medicine_type_val"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class="w-100px input-small dosage_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>  <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-100px medicine-name duration_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>  <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name advice_val'+i+'"></td>                        <?php 
                        } 
                      } ?><td width="80"><a title="Click to Remove Medicine" href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
        /* script start */
$(function () 
{
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_medicine_auto_vals/'); ?>" + request.term,
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
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_dosage_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_duration_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_frequency_vals/'); ?>" + request.term,
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
            "<?php echo base_url('dental/dental_prescription/get_dental_advice_vals/'); ?>" + request.term,
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

  
}



    $(document).ready(function(){  
$(".addprescriptionrow").click(function(){
  
 // return false;
 var m=1;
  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px medicine_val'+i+'   first_txt_cap'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>   <td><input type="text" name="prescription['+i+'][medicine_brand]" id="brand'+i+'"  class="w-100px first_brand_cap'+i+'" ></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>  <td><input type="text" id="salt'+i+'"  name="prescription['+i+'][medicine_salt]" class="w-100px"  ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="w-100px input-small medicine_type_val first_type_cap'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class="w-100px input-small dosage_val'+i+' first_dose_cap'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>  <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-100px medicine-name duration_val'+i+'  first_dur_cap'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val'+i+' first_fre_cap'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>  <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name advice_val'+i+' first_adv_cap'+i+'"></td>                        <?php 
                        } 
                      } ?><td width="80"><a title="Click to Remove Medicine" href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
        /* script start */
$(function () 
{
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('dental/dental_prescription/get_dental_medicine_auto_vals/'); ?>" + request.term,
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
        minLength: 1,
        change: function() 
        {
            
          

   var str = $('.first_txt_cap'+i).val();
   var part_val = str.split(" ");
    for ( var s = 0; s < part_val.length; s++ )
    {
        if(s==0)
        {
            var j = part_val[s].charAt(0).toUpperCase();
            part_val[s] = j + part_val[s].substr(1);     
        }
    }
    $('.first_txt_cap'+i).val(part_val.join(" "));
  
 
            //$("#test_val").val("").css("display", 2);
        }
    });
    });


$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_dosage_vals/'); ?>" + request.term,
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
            
            var str = $('.first_dose_cap'+i).val();
           var part_val = str.split(" ");
            for ( var s = 0; s < part_val.length; s++ )
            {
                if(s==0)
                {
                    var j = part_val[s].charAt(0).toUpperCase();
                    part_val[s] = j + part_val[s].substr(1);     
                }
            }
            $('.first_dose_cap'+i).val(part_val.join(" "));
            
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_duration_vals/'); ?>" + request.term,
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
            
            var str = $('.first_dure_cap'+i).val();
           var part_val = str.split(" ");
            for ( var s = 0; s < part_val.length; s++ )
            {
                if(s==0)
                {
                    var j = part_val[s].charAt(0).toUpperCase();
                    part_val[s] = j + part_val[s].substr(1);     
                }
            }
            $('.first_dure_cap'+i).val(part_val.join(" "));
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_frequency_vals/'); ?>" + request.term,
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
            
            var str = $('.first_fre_cap'+i).val();
           var part_val = str.split(" ");
            for ( var s = 0; s < part_val.length; s++ )
            {
                if(s==0)
                {
                    var j = part_val[s].charAt(0).toUpperCase();
                    part_val[s] = j + part_val[s].substr(1);     
                }
            }
            $('.first_fre_cap'+i).val(part_val.join(" "));
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('dental/dental_prescription/get_dental_advice_vals/'); ?>" + request.term,
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
            
            var str = $('.first_adv_cap'+i).val();
           var part_val = str.split(" ");
            for ( var s = 0; s < part_val.length; s++ )
            {
                if(s==0)
                {
                    var j = part_val[s].charAt(0).toUpperCase();
                    part_val[s] = j + part_val[s].substr(1);     
                }
            }
            $('.first_adv_cap'+i).val(part_val.join(" "));
            //$("#test_val").val("").css("display", 2);
        }
    });
    });
    
    
        /* script end*/
m++;

    });
});

    $("#prescription_name_table").on('click','.remove_prescription_row',function(){
        $(this).parent().parent().remove();
    });


    function get_investigation_data(result)
    {
     // alert(result);
      var obj = JSON.parse(result);
      var selectedoption1='';
      var selectedoption2 ='';
      var arr = '';
      var i=obj.length;
      var m=1;
      $.each(obj, function (index, value) {
           if(obj[index].teeth_name=="Permanent Teeth")
        {
        var selectedoption1='selected="selected"';
       }
       else
       {
         var selectedoption1='';
       }
         if(obj[index].teeth_name=="Deciduous Teeth")
        {
        var selectedoption2='selected="selected"';
       }
       else
       {
         var selectedoption2='';
       }
    arr +='<div class="row"><div class="col-md-3"><div class="btn-text">'+obj[index].investigation_sub+'</div></div><div class="col-md-3"><div class="btn-text"><select name="sub_category['+obj[index].sub_category_id+'][name][]" class="w-150px m_select_btn"id="teeth_name_sub_category'+obj[index].sub_category_id+'" onchange="get_open_chart_sub_category('+obj[index].sub_category_id+'); return false;"><option value="">Select Teeth Type </option><option value="Permanent Teeth"'+selectedoption1+'>Permanent Teeth</option><option value="Deciduous Teeth"'+selectedoption2+'>Deciduous Teeth</option></select><button type="button" class="theme-color" onclick="get_popup_click_sub_category('+obj[index].sub_category_id+'); return false;" id="modal_add_sub_category'+obj[index].sub_category_id+'" class="theme-color"> Mapping</button></div></div><div class="col-md-3"><div class="btn-text"><input type="text" name="sub_category['+obj[index].sub_category_id+'][remarks][]" value="'+obj[index].remarks+'" id="remarks-'+obj[index].sub_category_id+'"></div></div><div class="col-md-3"><input type="text" class="w-40px" id="get_teeth_number_val_sub_category'+obj[index].sub_category_id+'" value="'+obj[index].teeth_no+'"" name="sub_category['+obj[index].sub_category_id+'][teeth_no][]" value="" ><input type="hidden" class="w-40px" id="get_teeth_popupval_sub_category'+obj[index].sub_category_id+'" value="" name="get_teeth_popupval_sub_category['+obj[index].sub_category_id+']"></div></div>'
    }); 
      $("#sub_category_list").html(arr);
    }
   
  $(".first_txt_cap").on('keyup', function(){

   var str = $('.first_txt_cap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
        if(i==0)
        {
            var j = part_val[i].charAt(0).toUpperCase();
            part_val[i] = j + part_val[i].substr(1);     
        }
       
        
      
    }
      
   $('.first_txt_cap').val(part_val.join(" "));
  
  });
</script>
<div id="load_add_dental_chief_complaints_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_chart_de_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_chart_perma_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_allergy_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_oral_habit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_advice_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_treatment_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_dental_diagnosis_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_chart_perma_category_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_chart_perma_diagnosis_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_chart_perma_treatment_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Do you want to save the changes?</h4></div>
          
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="confirm_submit">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->
    <script>
   $('#next_reason_data').change(function(){  
      var text = $(this).find("option:selected").map(function() {
            return $(this).text();
        }).get().join(' ');
    //   var diagnosis_val = $("#diagnosis").val();
      var diagnosis_val = CKEDITOR.instances.next_reason.getData();
    //   $.ajax({url: "<?php echo base_url(); ?>opd/diagnosis_name/"+diagnosis_id, 
    //     success: function(result)
    //     {
           if(text!='')
           {
            var diagnosiss_value = diagnosis_val+' '+text; 
           }
           else
           {
            var diagnosiss_value = text;
           }
           CKEDITOR.instances['next_reason'].setData(diagnosiss_value);
    //     } 
    //   }); 
  });
</script>