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
                        
                  <form id="gynec_prescription_form" name="gynec_prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
                 <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>" />
              
               <div class="row">
                  <div class="col-xs-2">
                     <a class="btn-custom m-l-0 m-b-5" href="<?php echo base_url('opd'); ?>"><i class="fa fa-user"></i> <b>Registered Patient</b></a>
                  </div>
                  <!-- 5 -->
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
                           <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>">
                           <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">
                           <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
                        </div>
                     </div>
                    <!-- <div class="row m-b-5">
                        <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
                        <div class="col-xs-8">
                           <input type="text" name="aadhaar_no" value="< ?php echo $form_data['aadhaar_no']; ?>">
                           < ?php if(!empty($form_error)){ echo form_error('aadhaar_no'); } ?>
                        </div>
                     </div>-->
                     <input type="hidden" name="aadhaar_no" value="<?php echo $form_data['aadhaar_no']; ?>">

                      

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
              
               
               
               
              <div class="row">
    <div class="col-md-2">
      <label class="col-md-12 col-sm-12" for="printsummary-labels"><strong>Print only:</strong></label>
    </div>
    <div class="col-md-10 col-sm-10">
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_patient_history_flag" <?php if($form_data['print_patient_history_flag']==1){ echo 'checked';}?> id="checkboxhistory" value="1">
        <label for="checkboxhistory">Patient History</label>
      </div>

      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_disease_flag" id="checkboxcontactlens" <?php if($form_data['print_disease_flag']==1){ echo 'checked';}?> value="1">
         <label for="checkboxcontactlens">Disease</label>
      </div>
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_complaints_flag" id="checkboxglasses" <?php if($form_data['print_complaints_flag']==1){ echo 'checked';}?> value="1"> 
        <label for="checkboxglasses">Complaints</label>
      </div>

      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_allergy_flag" <?php if($form_data['print_allergy_flag']==1){ echo 'checked';}?> id="checkboxinterglasses" value="1"> 
        <label for="checkboxinterglasses">Allergy</label>
      </div>

      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_general_examination_flag" <?php if($form_data['print_general_examination_flag']==1){ echo 'checked';}?> id="checkboxexamination" value="1">             
         <label for="checkboxexamination">General Examination</label>
      </div>
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_clinical_examination_flag" <?php if($form_data['print_clinical_examination_flag']==1){ echo 'checked';}?> id="checkboxexamination" value="1">             
         <label for="checkboxexamination">Clinical Examination</label>
      </div>

      

      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_investigations_flag" <?php if($form_data['print_investigations_flag']==1){ echo 'checked';}?> id="checkboxinvestigations" value="1">       
         <label for="checkboxinvestigations">Investigations</label>
      </div>
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_medicine_flag" <?php if($form_data['print_medicine_flag']==1){ echo 'checked';}?> id="checkboxdiagnosis" value="1">                  
        <label for="checkboxdiagnosis">Medicine</label>
      </div>

      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_advice_flag" id="checkboxadvice" <?php if($form_data['print_advice_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">Advice</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_next_app_flag" id="checkboxadvice" <?php if($form_data['print_next_app_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">Next Appointment</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_gpla_flag" id="checkboxadvice" <?php if($form_data['print_gpla_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">GPLA</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_follicular_flag" id="checkboxadvice" <?php if($form_data['print_follicular_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">Follicular Scanning</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_icsilab_flag" id="checkboxadvice" <?php if($form_data['print_icsilab_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">ICSI lab</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_fertility_flag" id="checkboxadvice" <?php if($form_data['print_fertility_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">Fertility</label>
      </div>   
      
      <div class="ckbox ckbox-default col-md-3 col-sm-3">
        <input type="checkbox" name="print_antenatal_flag" id="checkboxadvice" <?php if($form_data['print_antenatal_flag']==1){ echo 'checked';}?> value="1">
        <label for="checkboxadvice">Antenatal Care</label>
      </div>   
      
      
      
      <div>      
      </div>
    </div>
  </div>
  
  
   <div class="row m-t-10">
                  <div class="col-xs-12">
                     <label>
                        <b>Template</b> 
                        <select name="template_list"  id="template_list">
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
                  </div>
               </div>
               <br>
              
               <?php 
               if(!empty($vitals_list))
                    {
                      $i=0;
                      foreach ($vitals_list as $vitals) 
                      {
                        $vital_val = get_vitals_value($vitals->id,$form_data['data_id'],3);
                        ?>
                          <label>
                            <b><?php echo $vitals->vitals_name; ?></b> 
                            <input type="text" name="data[<?php echo $vitals->id; ?>][name]"  class="w-50px input-tiny" value="<?php echo $vital_val; ?>"> 
                            <span><?php echo $vitals->vitals_unit; ?></span>
                        </label> &nbsp;

                        <?php

                        $i++;
                        if($i==6)
                        {
                           $i=0;
                           ?>
                           
                           <?php 
                        }
                      }
                    }
                    ?>
               <br>
  
  
               <!-- ************* -->
               <div class="row">
                  <div class="col-md-12 dental-chart">
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
        <div class="">
        <?php 
           $j=1; 
           foreach ($prescription_tab_setting as $value) 
           { 
           ?>
        <div class="tab-content" style="border:none">
           <?php  //echo $value->setting_name;
              if(strcmp(strtolower($value->setting_name),'patient_history')=='0'){
                $patient_history_data = $this->session->userdata('patient_history_data');
                $row_count = count($patient_history_data) + 1;
                 ?>


           <div id="tab_patient_history" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
              <input type="hidden" value="<?php echo $row_count; ?>" name="row_id" id="row_id">
              <div class="row">
                 <div class="col-md-12 tab-content dental-chart" style="border:1px solid #ccc;">
                    <div class="tab-pane fade in active" id="chief" style="padding:0 10px;">
                                               <!-- inner tabing -->
                          <div class="col-md-11">
                            <div class="innertab innertab1">
                               <!-- patient history-history starts -->
                               <div class="col-md-4 ">
                                  <div class="box-left">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Married</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="marriage_status" class="w-150px m_select_btn" id="marriage_status" onchange="check_marriage_status(this.value);">
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div id="marriage_columns">
                                        <div class="row m-b-5">
                                           <div class="col-md-5">
                                              <label>Married Life</label>
                                           </div>
                                           <div class="col-md-7">
                                              <input type="text" class="numeric" name="married_life_unit" id='married_life_unit' style="width:50px;">
                                              <select name="married_life_type" class="m_select_btn" id="married_life_type" style="width:108px;">
                                                 <option value="">Select Unit </option>
                                                 <option value="Days">Days</option>
                                                 <option value="Week">Week</option>
                                                 <option value="Month">Month</option>
                                                 <option value="Year">Year</option>
                                              </select>
                                           </div>
                                        </div>
                                        <div class="row m-b-5">
                                           <div class="col-md-5">
                                            <!--   <label>Marriage No.</label> -->
                                           </div>
                                           <div class="col-md-7">
                                              <input type="text" class="w-40px numeric d-none" id='marriage_no' name="marriage_no" placeholder='Marriage No.'>
                                           </div>
                                        </div>
                                        <div class="row m-b-5">
                                           <div class="col-md-5">
                                              <label>Marriage Details</label>
                                           </div>
                                           <div class="col-md-7">
                                              <textarea id="marriage_details" name="marriage_details" style="height:100px;"></textarea>
                                           </div>
                                        </div>
                                        <div class="row m-b-5">
                                           <div class="col-md-5">
                                              <label>Previous Delivery</label>
                                           </div>
                                           <div class="col-md-7">
                                              <select name="previous_delivery" id='previous_delivery' class="m_select_btn" onchange="check_previous_delivery(this.value);">
                                                 <option value="">Select Previous</option>
                                                 <option value="Yes">Yes</option>
                                                 <option value="No">No</option>
                                              </select>
                                           </div>
                                        </div>
                                        <div id="check_previous_delivery">
                                          <div class="row m-b-5">
                                             <div class="col-md-5">
                                                <label>Delivery Type</label>
                                             </div>
                                             <div class="col-md-7">
                                                <select name="delivery_type" id='delivery_type' class="m_select_btn">
                                                   <option value="">Select Delivery Type</option>
                                                   <option value="Normal">Normal</option>
                                                   <option value="Operative">Operative</option>
                                                </select>
                                             </div>
                                          </div>
                                          <div class="row m-b-5">
                                             <div class="col-md-5">
                                                <label>Delivery Details</label>
                                             </div>
                                             <div class="col-md-7">
                                                <textarea id="delivery_details" name="delivery_details" style="height:100px;"></textarea>
                                             </div>
                                          </div>
                                        </div>
                                     </div>
                                     <!-- hide column -->
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                          
                                        </div>
                                        <div class="col-md-7"> <button type="button" onclick="add_patient_history_listdata();" class="add-btn theme-color" style="float:right;">Add</button>
                                        </div>
                                     </div>
                                  </div>
                               </div>
                               <div class="col-md-8 p-r-0">
                                  <div class="table-box">
                                     <table class="table table-bordered input-center" id='patient_history_list'>
                                        <thead>
                                           <tr>
                                              <th align="center" width="">
                                                 <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_pat(this);">
                                                 <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                              <th>Married</th>
                                              <th>Married Life</th>
                                              <th>Marriage No.</th>
                                              <th>Married Details</th>
                                              <th>Previous Delivery</th>
                                              <th>Delivery Type</th>
                                              <th>Delivery Details </th>
                                           </tr>
                                        </thead>
                                        <?php 
                                           $i = 0;
                                           if(!empty($patient_history_data))
                                           {
                                              $i = 1;
                                              foreach($patient_history_data as $patient_history_val)
                                              {
                                           
                                               if (strpos($patient_history_val['married_life_type'], 'Select') !== false) 
                                               {
                                                   $patient_history_val['married_life_type'] = "";
                                               }
                                               else
                                               {
                                                 $patient_history_val['married_life_type'] = $patient_history_val['married_life_type'];
                                               }
                                               if (strpos($patient_history_val['previous_delivery'], 'Select') !== false) 
                                               {
                                                   $patient_history_val['previous_delivery'] = "";
                                               }
                                               else
                                               {
                                                 $patient_history_val['previous_delivery'] = $patient_history_val['previous_delivery'];
                                               }
                                               if (strpos($patient_history_val['delivery_type'], 'Select') !== false) 
                                               {
                                                   $patient_history_val['delivery_type'] = "";
                                               }
                                               else
                                               {
                                                 $patient_history_val['delivery_type'] = $patient_history_val['delivery_type'];
                                               }
                                           
                                               ?>
                                        <tr name="patient_history_row" id="<?php echo $i; ?>">
                                           <td>
                                              <input type="checkbox" class="part_checkbox_pat booked_checkbox" name="patient_history[]" value="<?php echo $i; ?>" >
                                           </td>
                                           <td><?php echo $i; ?></td>
                                           <input type="hidden" id="unique_id" name="unique_id" value="<?php echo $i; ?>">
                                           <td><?php echo $patient_history_val['marriage_status']; ?></td>
                                           <td><?php echo $patient_history_val['married_life_unit'].' '.$patient_history_val['married_life_type']; ?></td>
                                           <td><?php echo $patient_history_val['marriage_no']; ?></td>
                                           <td><?php echo $patient_history_val['marriage_details']; ?></td>
                                           <td><?php echo $patient_history_val['previous_delivery']; ?></td>
                                           <td><?php echo $patient_history_val['delivery_type']; ?></td>
                                           <td><?php echo $patient_history_val['delivery_details']; ?></td>
                                        </tr>
                                        <?php
                                           $i++;
                                           }?>
                                        <?php }
                                           else
                                           { ?>
                                        <tr>
                                           <td colspan="9" align="center" class=" text-danger ">
                                              <div class="text-center">Patient History not available.</div>
                                           </td>
                                        </tr>
                                        <?php }
                                           ?> 
                                        <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_history_vals();">
                                        <i class="fa fa-trash"></i> Delete
                                        </a>
                                     </table>
                                  </div>
                               </div>
                               <!-- patient history-history ends -->
                            </div>
                                                            <!-- innertab -->
                            <div class="innertab innertab2" style="display:none;">
                               <!-- patient history - family history starts -->
                               <?php
                                  $patient_family_history_data = $this->session->userdata('patient_family_history_data');
                                  $row_count = count($patient_family_history_data) + 1;
                                  ?>
                               <div class="row">
                                  <input type="hidden" value="<?php echo $row_count; ?>" name="row_id_family_history" id="row_id_family_history">
                                  <div class="col-md-4">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Relation</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="relation" class="w-139px m_select_btn" id="relation_id" >
                                              <option value="">Select Relation</option>
                                              <?php
                                                 if(!empty($relation_list))
                                                 {
                                                   foreach($relation_list as $relation)
                                                   {
                                                   ?>
                                              <option <?php /*if($form_data['relation_id']==$relation->id){ echo 'selected="selected"'; }*/ ?> value="<?php echo $relation->id; ?>"><?php echo $relation->relation; ?></option>
                                              <?php
                                                 }
                                                 }
                                                 ?>
                                           </select>
                                           <a href="javascript:void(0)" onclick="return add_relation();"  class="btn-new"><i class="">New</i></a>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Disease</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="disease" class="w-139px m_select_btn" id="disease_id">
                                              <option value="">Select Disease</option>
                                              <?php
                                                 if(!empty($disease_list))
                                                 {
                                                   foreach($disease_list as $disease)
                                                   {
                                                   ?>
                                              <option <?php /*if($form_data['relation_id']==$relation->id){ echo 'selected="selected"'; }*/ ?> value="<?php echo $disease->id; ?>"><?php echo $disease->disease_name; ?></option>
                                              <?php
                                                 }
                                                 }
                                                 ?>
                                           </select>
                                           <a href="javascript:void(0)" onclick="return add_disease();"  class="btn-new"><i class="">New</i></a>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Description</label>
                                        </div>
                                        <div class="col-md-7">
                                           <textarea id="family_description" name="family_description" style="height:100px;"></textarea>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Duration</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input type="text" class="numeric" name="family_duration_unit" id='family_duration_unit' style="width:50px;">
                                           <select name="family_duration_type" class="m_select_btn" id="family_duration_type" style="width:131px;">
                                              <option value="">Select Unit </option>
                                              <option value="Days">Days</option>
                                              <option value="Week">Week</option>
                                              <option value="Month">Month</option>
                                              <option value="Year">Year</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                          
                                        </div>
                                        <div class="col-md-7"> 
                                           <button type="button" onclick="add_patient_family_history_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                        </div>
                                     </div>
                                    
                                  </div>
                                  <div class="col-md-8 p-r-0">
                                     <div class="table-box">
                                        <table class="table table-bordered" id='patient_family_history_list'>
                                           <thead>
                                              <tr>
                                                 <th align="center" width="">
                                                    <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_family_history(this);">
                                                    <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                                 <th>Relation</th>
                                                 <th>Disease</th>
                                                 <th>Description</th>
                                                 <th>Duration</th>
                                              </tr>
                                           </thead>
                                           <?php 
                                              $patient_family_history_data = $this->session->userdata('patient_family_history_data'); 
                                              $i = 0;
                                              if(!empty($patient_family_history_data))
                                              {
                                                $i = 1;
                                                foreach($patient_family_history_data as $patient_family_history_val)
                                                {
                                                
                                                  if (strpos($patient_family_history_val['relation'], 'Select') !== false) 
                                                  {
                                                  $patient_family_history_val['relation'] = "";
                                                  }
                                                  else
                                                  {
                                                  $patient_family_history_val['relation'] = $patient_family_history_val['relation'];
                                                  }
                                                  if (strpos($patient_family_history_val['disease'], 'Select') !== false) 
                                                  {
                                                  $patient_family_history_val['disease'] = "";
                                                  }
                                                  else
                                                  {
                                                  $patient_family_history_val['disease'] = $patient_family_history_val['disease'];
                                                  }
                                                  if (strpos($patient_family_history_val['family_duration_type'], 'Select') !== false) 
                                                  {
                                                  $patient_family_history_val['family_duration_type'] = "";
                                                  }
                                                  else
                                                  {
                                                  $patient_family_history_val['family_duration_type'] = $patient_family_history_val['family_duration_type'];
                                                  }
                                                
                                                ?>
                                           <tr name="patient_family_history_row" id="<?php echo $i; ?>">
                                              <td>
                                                 <input type="checkbox" class="part_checkbox_family_history booked_checkbox" name="patient_family_history[]" value="<?php echo $i; ?>" >
                                              </td>
                                              <td><?php echo $i; ?></td>
                                              <input type="hidden" id="unique_id_family_history" name="unique_id_family_history" value="<?php echo $i; ?>">
                                              <td><?php echo $patient_family_history_val['relation']; ?></td>
                                              <td><?php echo $patient_family_history_val['disease']; ?></td>
                                              <td><?php echo $patient_family_history_val['family_description']; ?></td>
                                              <td><?php echo $patient_family_history_val['family_duration_unit'].' '.$patient_family_history_val['family_duration_type']; ?></td>
                                           </tr>
                                           <?php
                                              $i++;
                                              }
                                              }
                                              else
                                              { ?>
                                           <tr>
                                              <td colspan="6" align="center" class=" text-danger ">
                                                 <div class="text-center">Patient Family History not available.</div>
                                              </td>
                                           </tr>
                                           <?php
                                              }
                                              ?> 
                                           <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_family_history_vals();">
                                           <i class="fa fa-trash"></i> Delete
                                           </a>
                                        </table>
                                     </div>
                                  </div>
                               </div>
                               <!-- patient history - family history ends -->
                            </div>
                                                            <!-- innertab -->
                            <div class="innertab innertab3" style="display:none;">
                               <!-- patient history - personal history starts -->
                               <?php  
                                  $patient_personal_history_data = $this->session->userdata('patient_personal_history_data');
                                  $row_count_personal_history = count($patient_personal_history_data) + 1;
                                  ?>
                               <div class="row">
                                  <input type="hidden" value="<?php echo $row_count_personal_history; ?>" name="row_id_personal_history" id="row_id_personal_history">
                                  <div class="col-md-4">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Breast Discharge</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="br_discharge" class="m_select_btn full-width" id="br_discharge" onchange="check_br_discharge(this.value);">
                                              <option value="">Select Breast Discharge </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div id="check_br_discharge">
                                       <div class="row m-b-5">
                                          <div class="col-md-5">
                                             <label>Side</label>
                                          </div>
                                          <div class="col-md-7">
                                             <select name="side" class="m_select_btn full-width" id="side" >
                                                <option value="">Select Side </option>
                                                <option value="Left">Left</option>
                                                <option value="Right">Right</option>
                                             </select>
                                          </div>
                                       </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Hirsutism</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="hirsutism" class="m_select_btn full-width" id="hirsutism" >
                                              <option value="">Select Hirsutism </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>White Discharge</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="white_discharge" class="m_select_btn full-width" id="white_discharge" onchange="check_white_discharge(this.value);">
                                              <option value="">Select White Discharge </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div id="check_white_discharge">
                                       <div class="row m-b-5">
                                          <div class="col-md-5">
                                             <label>Type</label>
                                          </div>
                                          <div class="col-md-7">
                                             <select name="type" class="m_select_btn full-width" id="type" >
                                                <option value="">Select Type </option>
                                                <option value="Thick">Thick</option>
                                                <option value="Milky">Milky</option>
                                                <option value="Clumpy">Clumpy</option>
                                             </select>
                                          </div>
                                       </div>
                                     </div>

                                      <div class="row m-b-5">
                                        <div class="col-md-5">
                                         <label>Frequency of I/C No. </label>
                                        </div>
                                        <div class="col-md-7">
                                         <input class="numeric" style="width: 144px;" id="frequency_personal" name="frequency_personal" placeholder="Frequency" type="text"> /Week
                                        </div>
                                      </div>

                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Dyspareunia</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="dyspareunia" class="m_select_btn full-width" id="dyspareunia" >
                                              <option value="">Select Dyspareunia </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Details</label>
                                        </div>
                                        <div class="col-md-7">
                                           <textarea id="personal_details" name="personal_details" style="height:100px;"></textarea>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                          
                                        </div>
                                        <div class="col-md-7"> 
                                          <button type="button" onclick="add_patient_personal_history_listdata();" class="add-btn theme-color" style="float: right;">Add</button>
                                        </div>
                                     </div>
                                     
                                  </div>
                                  <div class="col-md-8 p-r-0">
                                     <div class="table-box">
                                        <table class="table table-bordered" id='patient_personal_history_list'>
                                           <thead>
                                              <tr>
                                                 <th align="center" width="">
                                                    <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_personal_history(this);">
                                                    <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                                 <th>Breast Discharge</th>
                                                 <th>Side</th>
                                                 <th>Hirsutism</th>
                                                 <th>White Discharge</th>
                                                 <th>Type</th>
                                                 <th>Frequency</th>
                                                 <th>Dyspareunia</th>
                                                 <th>Details</th>
                                              </tr>
                                           </thead>
                                           <?php 
                                              $i = 0;
                                              if(!empty($patient_personal_history_data))
                                              {
                                                $i = 1;
                                                foreach($patient_personal_history_data as $patient_personal_history_val)
                                                {
                                                
                                                if (strpos($patient_personal_history_val['br_discharge'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['br_discharge'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['br_discharge'] = $patient_personal_history_val['br_discharge'];
                                                }
                                                if (strpos($patient_personal_history_val['side'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['side'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['side'] = $patient_personal_history_val['side'];
                                                }
                                                if (strpos($patient_personal_history_val['hirsutism'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['hirsutism'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['hirsutism'] = $patient_personal_history_val['hirsutism'];
                                                }
                                                if (strpos($patient_personal_history_val['white_discharge'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['white_discharge'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['white_discharge'] = $patient_personal_history_val['white_discharge'];
                                                }
                                                if (strpos($patient_personal_history_val['type'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['type'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['type'] = $patient_personal_history_val['type'];
                                                }
                                                if (strpos($patient_personal_history_val['dyspareunia'], 'Select') !== false) 
                                                {
                                                $patient_personal_history_val['dyspareunia'] = "";
                                                }
                                                else
                                                {
                                                $patient_personal_history_val['dyspareunia'] = $patient_personal_history_val['dyspareunia'];
                                                }
                                                
                                                ?>
                                           <tr name="patient_personal_history_row" id="<?php echo $i; ?>">
                                              <td>
                                                 <input type="checkbox" class="part_checkbox_personal_history booked_checkbox" name="patient_personal_history[]" value="<?php echo $i; ?>" >
                                              </td>
                                              <td><?php echo $i; ?></td>
                                              <input type="hidden" id="unique_id_personal_history" name="unique_id_personal_history" value="<?php echo $i; ?>">
                                              <td><?php echo $patient_personal_history_val['br_discharge']; ?></td>
                                              <td><?php echo $patient_personal_history_val['side']; ?></td>
                                              <td><?php echo $patient_personal_history_val['hirsutism']; ?></td>
                                              <td><?php echo $patient_personal_history_val['white_discharge']; ?></td>
                                              <td><?php echo $patient_personal_history_val['type']; ?></td>
                                              <td><?php echo $patient_personal_history_val['frequency_personal']; ?></td>
                                              <td><?php echo $patient_personal_history_val['dyspareunia']; ?></td>
                                              <td><?php echo $patient_personal_history_val['personal_details']; ?></td>
                                           </tr>
                                           <?php
                                              $i++;
                                              }
                                              }
                                              else
                                              { ?>
                                           <tr>
                                              <td colspan="9" align="center" class=" text-danger ">
                                                 <div class="text-center">Patient Personal History not available.</div>
                                              </td>
                                           </tr>
                                           <?php
                                              }
                                              ?> 
                                           <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_personal_history_vals();">
                                           <i class="fa fa-trash"></i> Delete
                                           </a>
                                        </table>
                                     </div>
                                  </div>
                               </div>
                               <!-- patient history - personal history ends -->
                            </div>
                                                            <!-- innertab -->
                            <div class="innertab innertab4" style="display:none;">
                             <!-- patient menstrual history starts -->
                             <?php  
                                $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
                                $row_count_menstrual_history = count($patient_menstrual_history_data) + 1;
                                ?>
                             <input type="hidden" value="<?php echo $row_count_menstrual_history; ?>" name="row_id_menstrual_history" id="row_id_menstrual_history">
                             <div class="row">
                                <div class="col-md-4">
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Previous Cycle</label>
                                      </div>
                                      <div class="col-md-7">
                                         <select name="previous_cycle" class="m_select_btn full-width" id="previous_cycle" >
                                            <option value="">Select Previous Cycle </option>
                                            <option value="Regular">Regular</option>
                                            <option value="Irregular">Irregular</option>
                                            <option value="Flaw">Flaw</option>
                                         </select>
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Cycle Type</label>
                                      </div>
                                      <div class="col-md-7">
                                         <select name="prev_cycle_type" class="m_select_btn full-width" id="prev_cycle_type" >
                                            <option value="">Select Cycle Type </option>
                                            <option value="Regular">Regular</option>
                                            <option value="Irregular">Irregular</option>
                                         </select>
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Present Cycle</label>
                                      </div>
                                      <div class="col-md-7">
                                         <select name="present_cycle" class="m_select_btn full-width" id="present_cycle" >
                                            <option value="">Select Present Cycle </option>
                                            <option value="Regular">Regular</option>
                                            <option value="Irregular">Irregular</option>
                                            <option value="Flaw">Flaw</option>
                                         </select>
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Cycle Type</label>
                                      </div>
                                      <div class="col-md-7">
                                         <select name="present_cycle_type" class="m_select_btn full-width" id="present_cycle_type" >
                                            <option value="">Select Cycle Type </option>
                                            <option value="Regular">Regular</option>
                                            <option value="Irregular">Irregular</option>
                                         </select>
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Details</label>
                                      </div>
                                      <div class="col-md-7">
                                         <textarea id="cycle_details" name="cycle_details" style="height:100px;"></textarea>
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>LMP Date</label>
                                      </div>
                                      <div class="col-md-7">
                                         <input type="text" name="lmp_date" id="lmp_date" style="width:185px;" class="datepicker"  data-date-format="dd-mm-yyyy"  value="<?php //echo $form_data['next_appointment_date']; ?>" readonly/> 
                                      </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                         <label>Dysmenorrhea</label>
                                      </div>
                                      <div class="col-md-7">
                                         <select name="dysmenorrhea" class="m_select_btn full-width" id="dysmenorrhea" onchange="check_dysmenorrhea(this.value);">
                                            <option value="">Select Dysmenorrhea </option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                         </select>
                                      </div>
                                   </div>
                                   <div id="check_dysmenorrhea">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Dysmenorrhea Type</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="dysmenorrhea_type" class="m_select_btn full-width" id="dysmenorrhea_type" >
                                              <option value="">Select Dysmenorrhea Type </option>
                                              <option value="Primary">Primary</option>
                                              <option value="Secondary">Secondary</option>
                                           </select>
                                        </div>
                                     </div>
                                   </div>
                                   <div class="row m-b-5">
                                      <div class="col-md-5">
                                        
                                      </div>
                                      <div class="col-md-7">
                                      <button type="button" onclick="add_patient_menstrual_history_listdata();" class="add-btn theme-color" style="float:right;">Add</button>
                                      </div>
                                   </div>
                                   
                                </div>
                                <div class="col-md-8 p-r-0">
                                   <div class="table-box">
                                      <table class="table table-bordered" id='patient_menstrual_history_list'>
                                         <thead>
                                            <tr>
                                               <th align="center" width="">
                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_menstrual_history(this);">
                                                  <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                               <th>Previous Cycle</th>
                                               <th>Cycle Type</th>
                                               <th>Present Cycle</th>
                                               <th>Cycle Type</th>
                                               <th>Details</th>
                                               <th style="120px;">LMP Date</th>
                                               <th>Dysmenorrhea</th>
                                               <th>Dysmenorrhea Type</th>
                                            </tr>
                                         </thead>
                                         <?php 
                                            $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
                                            $i = 0;
                                            if(!empty($patient_menstrual_history_data))
                                            {
                                              $i = 1;
                                              foreach($patient_menstrual_history_data as $patient_menstrual_history_val)
                                              {
                                              
                                              if (strpos($patient_menstrual_history_val['previous_cycle'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['previous_cycle'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['previous_cycle'] = $patient_menstrual_history_val['previous_cycle'];
                                              }
                                              if (strpos($patient_menstrual_history_val['prev_cycle_type'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['prev_cycle_type'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['prev_cycle_type'] = $patient_menstrual_history_val['prev_cycle_type'];
                                              }
                                              if (strpos($patient_menstrual_history_val['present_cycle'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['present_cycle'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['present_cycle'] = $patient_menstrual_history_val['present_cycle'];
                                              }
                                              if (strpos($patient_menstrual_history_val['present_cycle_type'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['present_cycle_type'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['present_cycle_type'] = $patient_menstrual_history_val['present_cycle_type'];
                                              }
                                              if (strpos($patient_menstrual_history_val['dysmenorrhea'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['dysmenorrhea'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['dysmenorrhea'] = $patient_menstrual_history_val['dysmenorrhea'];
                                              }
                                              if (strpos($patient_menstrual_history_val['dysmenorrhea_type'], 'Select') !== false) 
                                              {
                                              $patient_menstrual_history_val['dysmenorrhea_type'] = "";
                                              }
                                              else
                                              {
                                              $patient_menstrual_history_val['dysmenorrhea_type'] = $patient_menstrual_history_val['dysmenorrhea_type'];
                                              }
                                              $lmp_date='';
                                              if(($patient_menstrual_history_val['lmp_date']=="01-01-1970")||($patient_menstrual_history_val['lmp_date']==''))
                                              $lmp_date = "";
                                              else
                                              $lmp_date = date("d-m-Y",strtotime($patient_menstrual_history_val['lmp_date']));
                                              ?>
                                         <tr name="patient_menstrual_history_row" id="<?php echo $i; ?>">
                                            <td>
                                               <input type="checkbox" class="part_checkbox_menstrual_history booked_checkbox" name="patient_menstrual_history[]" value="<?php echo $i; ?>" >
                                            </td>
                                            <td><?php echo $i; ?></td>
                                            <input type="hidden" id="unique_id_menstrual_history" name="unique_id_menstrual_history" value="<?php echo $i; ?>">
                                            <td><?php echo $patient_menstrual_history_val['previous_cycle']; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['prev_cycle_type']; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['present_cycle']; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['present_cycle_type']; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['cycle_details']; ?></td>
                                            <td><?php echo $lmp_date; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['dysmenorrhea']; ?></td>
                                            <td><?php echo $patient_menstrual_history_val['dysmenorrhea_type']; ?></td>
                                         </tr>
                                         <?php
                                            $i++;
                                            }
                                            }
                                            else
                                            { ?>
                                         <tr>
                                            <td colspan="10" align="center" class=" text-danger ">
                                               <div class="text-center">Patient Menstrual History not available.</div>
                                            </td>
                                         </tr>
                                         <?php
                                            }
                                            ?> 
                                         <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_menstrual_history_vals();">
                                         <i class="fa fa-trash"></i> Delete
                                         </a>
                                      </table>
                                   </div>
                                </div>
                             </div>
                             <!-- patient menstrual history ends -->
                          </div>
                                                            <!-- innertab -->
                            <div class="innertab innertab5" style="display:none;">
                               <!-- patient medical history starts -->
                               <?php   
                                  $patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
                                  $row_count_medical_history = count($patient_medical_history_data) + 1;
                                  ?>
                               <div class="row">
                                  <input type="hidden" value="<?php echo $row_count_medical_history; ?>" name="row_id_medical_history" id="row_id_medical_history">
                                  <div class="col-md-4">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>T.B</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="tb" class="m_select_btn full-width" id="tb" >
                                              <option value="">Select T.B </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Rx</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input class="w-185px numeric" id="tb_rx" name="tb_rx" placeholder="Rx" type="text">
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>D.M</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="dm" class="m_select_btn full-width" id="dm" >
                                              <option value="">Select D.M </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Years</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input type="text" class="w-185px numeric" id='dm_years' name="dm_years" placeholder='Years'>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Rx</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input type="text" class="w-185px" id='dm_rx' name="dm_rx" placeholder='Rx'>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>H.T</label>
                                        </div>
                                        <div class="col-md-7">
                                           <select name="ht" class="m_select_btn full-width" id="ht" >
                                              <option value="">Select H.T </option>
                                              <option value="Yes">Yes</option>
                                              <option value="No">No</option>
                                           </select>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Details</label>
                                        </div>
                                        <div class="col-md-7">
                                           <textarea id="medical_details" name="medical_details" style="height:100px;"></textarea>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>Others</label>
                                        </div>
                                        <div class="col-md-7">
                                           <textarea id="medical_others" name="medical_others" style="height:100px;"></textarea>
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                          
                                        </div>
                                        <div class="col-md-7"> 
                                          <button type="button" onclick="add_patient_medical_history_listdata();" class="add-btn theme-color" style="float:right;">Add</button>
                                        </div>
                                     </div>
                                     
                                  </div>
                                  <div class="col-md-8 p-r-0">
                                     <div class="table-box">
                                        <table class="table table-bordered" id='patient_medical_history_list'>
                                           <thead>
                                              <tr>
                                                 <th align="center" width="">
                                                    <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_medical_history(this);">
                                                    <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                                 <th>T.B</th>
                                                 <th>Rx</th>
                                                 <th>D.M</th>
                                                 <th>Years</th>
                                                 <th>Rx</th>
                                                 <th>H.T</th>
                                                 <th>Details</th>
                                                 <th>Others</th>
                                              </tr>
                                           </thead>
                                           <?php 
                                              $patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
                                              $i = 0;
                                                if(!empty($patient_medical_history_data))
                                                {
                                                $i = 1;
                                                foreach($patient_medical_history_data as $patient_medical_history_val)
                                                {
                                                
                                                if (strpos($patient_medical_history_val['tb'], 'Select') !== false) 
                                                {
                                                $patient_medical_history_val['tb'] = "";
                                                }
                                                else
                                                {
                                                $patient_medical_history_val['tb'] = $patient_medical_history_val['tb'];
                                                }
                                                if (strpos($patient_medical_history_val['dm'], 'Select') !== false) 
                                                {
                                                $patient_medical_history_val['dm'] = "";
                                                }
                                                else
                                                {
                                                $patient_medical_history_val['dm'] = $patient_medical_history_val['dm'];
                                                }
                                                if (strpos($patient_medical_history_val['ht'], 'Select') !== false) 
                                                {
                                                $patient_medical_history_val['ht'] = "";
                                                }
                                                else
                                                {
                                                $patient_medical_history_val['ht'] = $patient_medical_history_val['ht'];
                                                }
                                                
                                                ?>
                                           <tr name="patient_medical_history_row" id="<?php echo $i; ?>">
                                              <td>
                                                 <input type="checkbox" class="part_checkbox_medical_history booked_checkbox" name="patient_medical_history[]" value="<?php echo $i; ?>" >
                                              </td>
                                              <td><?php echo $i; ?></td>
                                              <input type="hidden" id="unique_id_medical_history" name="unique_id_medical_history" value="<?php echo $i; ?>">
                                              <td><?php echo $patient_medical_history_val['tb']; ?></td>
                                              <td><?php echo $patient_medical_history_val['tb_rx']; ?></td>
                                              <td><?php echo $patient_medical_history_val['dm']; ?></td>
                                              <td><?php echo $patient_medical_history_val['dm_years']; ?></td>
                                              <td><?php echo $patient_medical_history_val['dm_rx']; ?></td>
                                              <td><?php echo $patient_medical_history_val['ht']; ?></td>
                                              <td><?php echo $patient_medical_history_val['medical_details']; ?></td>
                                              <td><?php echo $patient_medical_history_val['medical_others']; ?></td>
                                           </tr>
                                           <?php
                                              $i++;
                                              }
                                              }
                                              else
                                              { ?>
                                           <tr>
                                              <td colspan="10" align="center" class=" text-danger ">
                                                 <div class="text-center">Patient Medical History not available.</div>
                                              </td>
                                           </tr>
                                           <?php
                                              }
                                              ?> 
                                           <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_medical_history_vals();">
                                           <i class="fa fa-trash"></i> Delete
                                           </a>
                                        </table>
                                     </div>
                                  </div>
                               </div>
                               <!-- patient medical history ends -->
                            </div>
                                                            <!-- innertab -->
                            <div class="innertab innertab6" style="display:none;">
                               <!-- patient obestetric history starts -->
                               <?php  
                                  $patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
                                  $row_count_obestetric_history = count($patient_obestetric_history_data) + 1;
                                  ?>
                               <input type="hidden" value="<?php echo $row_count_obestetric_history; ?>" name="row_id_obestetric_history" id="row_id_obestetric_history"> 
                               <div class="row">
                                  <div class="col-md-4">
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>G</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input class="full-width" id="obestetric_g" name="obestetric_g"  type="text">
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>P</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input class="full-width" id="obestetric_p" name="obestetric_p"  type="text">
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>L</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input class="full-width" id="obestetric_l" name="obestetric_l"  type="text">
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                           <label>MTP</label>
                                        </div>
                                        <div class="col-md-7">
                                           <input class="full-width" id="obestetric_mtp" name="obestetric_mtp" type="text">
                                        </div>
                                     </div>
                                     <div class="row m-b-5">
                                        <div class="col-md-5">
                                          
                                        </div>
                                        <div class="col-md-7" style="padding:0px;"> 
                                          <button type="button" onclick="add_patient_obestetric_history_listdata();" class="add-btn theme-color" style="float:right;">Add</button>
                                        </div>
                                     </div>
                                     
                                  </div>
                                  <div class="col-md-8 p-r-0">
                                     <div class="table-box">
                                        <table class="table table-bordered" id='patient_obestetric_history_list'>
                                           <thead>
                                              <tr>
                                                 <th align="center" width="">
                                                    <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_obestetric_history(this);">
                                                    <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
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
                                                 <th>G</th>
                                                 <th>P</th>
                                                 <th>L</th>
                                                 <th>MTP</th>
                                              </tr>
                                           </thead>
                                           <?php 
                                              $i = 0;
                                              if(!empty($patient_obestetric_history_data))
                                              {
                                                $i = 1;
                                                foreach($patient_obestetric_history_data as $patient_obestetric_history_val)
                                                {
                                                ?>
                                           <tr name="patient_obestetric_history_row" id="<?php echo $i; ?>">
                                              <td>
                                                 <input type="checkbox" class="part_checkbox_obestetric_history booked_checkbox" name="patient_obestetric_history[]" value="<?php echo $i; ?>" >
                                              </td>
                                              <td><?php echo $i; ?></td>
                                              <input type="hidden" id="unique_id_obestetric_history" name="unique_id_obestetric_history" value="<?php echo $i; ?>">
                                              <td><?php echo $patient_obestetric_history_val['obestetric_g']; ?></td>
                                              <td><?php echo $patient_obestetric_history_val['obestetric_p']; ?></td>
                                              <td><?php echo $patient_obestetric_history_val['obestetric_l']; ?></td>
                                              <td><?php echo $patient_obestetric_history_val['obestetric_mtp']; ?></td>
                                           </tr>
                                           <?php
                                              $i++;
                                                }
                                              }
                                              else
                                              { ?>
                                           <tr>
                                              <td colspan="6" align="center" class=" text-danger ">
                                                 <div class="text-center">Patient Obestetric History not available.</div>
                                              </td>
                                           </tr>
                                           <?php
                                              }
                                              ?> 
                                           <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_obestetric_history_vals();">
                                           <i class="fa fa-trash"></i> Delete
                                           </a>
                                        </table>
                                     </div>
                                  </div>
                               </div>
                               <!-- patient obestetric history ends -->
                            </div>
                                                            <!-- innertab -->
<div class="innertab innertab7" style="display:none;">
 <div class="row m-t-10">
    <div class="col-xs-12">
       <div class="well tab-right-scroll">
          <table class="table table-bordered table-striped" id="prescription_name_table_patient">
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
                      <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow_patient">Add</a>
                   </td>
                </tr>
                <?php 
                   //echo "<pre>"; print_r($prescription_presc_list); exit;
                   if(!empty($medicine_template_data_patient))
                   { 
                        $l=1;
                       foreach ($medicine_template_data_patient as $prescription_presc) 
                       {
                         
                       ?>
                <tr>
                   <?php
                      foreach ($prescription_patient_medicine_tab_setting as $tab_value) 
                      { 
                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                      {
                      ?>
                   <td>
                      <input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_name]" class=" medicine_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>">
                      <input type="hidden" name="medicine_id[]" id="medicine_id_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->id; ?>"> 
                   </td>
                   <?php 
                      }
                      
                      if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_brand]" class="" id="brand_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                   <?php 
                      } 
                      
                      if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_salt]" id="salt_patient<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                   <?php 
                      } 
                      
                      if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                      { ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_type]" id="type_patient<?php echo $l; ?>" class="medicine_type_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                   <?php 
                      }
                      if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                      { ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_dose]" class=" dosage_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                   <?php 
                      } 
                      if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                      {  ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_duration]" class=" medicine-name duration_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                   <?php 
                      }
                      if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_frequency]" class=" medicine-name frequency_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                   <?php 
                      } 
                      if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                      { 
                      ?>
                   <td><input type="text" name="prescription_patient[<?php echo $l; ?>][medicine_advice]" class=" medicine-name advice_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
                   <?php } }
                      ?>
                   <script type="text/javascript">
                      /* script start */
                        $(function () 
                        {
                              var getData = function (request, response) { 
                                row = <?php echo $l; ?> ;
                                $.ajax({
                                url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                      
                                  $('.medicine_val_patient'+<?php echo $l; ?>).val(names[0]);
                                  $('#type_patient'+<?php echo $l; ?>).val(names[1]);
                                  $('#brand_patient'+<?php echo $l; ?>).val(names[2]);
                                  $('#salt_patient'+<?php echo $l; ?>).val(names[3]);
                                  $('#medicine_id_patient'+<?php echo $l; ?>).val(names[4]);
                                //$(".medicine_val").val(ui.item.value);
                                return false;
                            }
                      
                            $(".medicine_val_patient"+<?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                    
                                }
                            });
                            });
                      
                        $(function () {
                            /*var getData = function (request, response) { 
                                $.getJSON(
                                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                                    function (data) {
                                        response(data);
                                    });
                            };*/
                            
                            var getData = function (request, response) { 
                             row = i ;
                             $.ajax({
                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                                $(".medicine_type_val_patient"+<?php echo $l; ?>).val(ui.item.value);
                                return false;
                            }
                      
                            $(".medicine_type_val_patient"+<?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                    
                                }
                            });
                          });
                      
                        $(function () {
                            /*var getData = function (request, response) { 
                                $.getJSON(
                                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                                    function (data) {
                                        response(data);
                                    });
                            };*/
                            
                            var getData = function (request, response) { 
                            
                             $.ajax({
                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
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
                                $(".dosage_val_patient"+<?php echo $l; ?>).val(ui.item.value);
                                return false;
                            }
                      
                            $(".dosage_val_patient"+<?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                    
                                }
                            });
                            });
                      
                        $(function () {
                            /*var getData = function (request, response) { 
                                $.getJSON(
                                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                                    function (data) {
                                        response(data);
                                    });
                            };*/
                            
                            var getData = function (request, response) { 
                                             
                             $.ajax({
                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                             dataType: "json",
                             method: 'post',
                           data: {
                              name_startsWith: request.term,
                              type: 'country_table',
                             
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
                                $(".duration_val_patient"+<?php echo $l; ?>).val(ui.item.value);
                                return false;
                            }
                      
                            $(".duration_val_patient"+<?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                    
                                }
                            });
                            });
                        $(function () {
                            /*var getData = function (request, response) { 
                                $.getJSON(
                                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                                    function (data) {
                                        response(data);
                                    });
                            };*/
                            
                            var getData = function (request, response) { 
                                             
                             $.ajax({
                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                             dataType: "json",
                             method: 'post',
                           data: {
                              name_startsWith: request.term,
                              type: 'country_table',
                             
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
                                $(".frequency_val_patient"+<?php echo $l; ?>).val(ui.item.value);
                                return false;
                            }
                      
                            $(".frequency_val_patient"+<?php echo $l; ?>).autocomplete({
                                source: getData,
                                select: selectItem,
                                minLength: 1,
                                change: function() {
                                    
                                }
                            });
                            });
                      
                        $(function () {
                            /*var getData = function (request, response) { 
                                $.getJSON(
                                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                                    function (data) {
                                        response(data);
                                    });
                            };*/
                            
                             var getData = function (request, response) { 
                                             
                             $.ajax({
                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                             dataType: "json",
                             method: 'post',
                           data: {
                              name_startsWith: request.term,
                              type: 'country_table',
                             
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
                                $(".advice_val_patient"+<?php echo $l; ?>).val(ui.item.value);
                                return false;
                            }
                      
                            $(".advice_val_patient"+<?php echo $l; ?>).autocomplete({
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
                              document.getElementById("prescription_name_table_patient").deleteRow(i);
                          }
                          
                   </script>
                   <td width="80">
                      <a onclick="delete_prescr_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                   </td>
                </tr>
                <?php $l++; } }else{ ?>
                <tr>
                   <?php
                      foreach ($prescription_patient_medicine_tab_setting as $tab_value) 
                      { 
                      if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[1][medicine_name]" class="input-small medicine_val_patient">
                      <input type="hidden" name="medicine_id[]" id="medicine_id_patient" class="input-small">
                   </td>
                   <?php 
                      }
                      if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[1][medicine_brand]" class="input-small" id="brand_patient0"></td>
                   <?php 
                      } 
                      
                      if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[1][medicine_salt]" class="input-small" id="salt_patient0"></td>
                   <?php 
                      }
                      
                      if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                      { ?>
                   <td><input type="text" name="prescription_patient[1][medicine_type]" class="input-small" id="type_patient0" onkeyup="get_medicine_type_autocomplete(0,1);"></td>
                   <?php 
                      }
                      if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                      { ?>
                   <td><input type="text" name="prescription_patient[1][medicine_dose]" class="input-small" id="dose_patient0" onkeyup="get_medicine_dose_autocomplete(0,1);"></td>
                   <?php 
                      } 
                      if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                      {  ?>
                   <td><input type="text" name="prescription_patient[1][medicine_duration]" class="input-small" id="duration_patient0" onkeyup="get_medicine_duration_autocomplete(0,1);"></td>
                   <?php 
                      }
                      if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                      {
                      ?>
                   <td><input type="text" name="prescription_patient[1][medicine_frequency]" class="input-small" id="frequency_patient0" onkeyup="get_medicine_frequency_autocomplete(0,1);"></td>
                   <?php 
                      } 
                      if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                      { 
                      ?>
                   <td><input type="text" name="prescription_patient[1][medicine_advice]" class="input-small" id="advice_patient0" onkeyup="get_medicine_advice_autocomplete(0,1);"></td>
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
                                    <!-- innertab -->
                                    <!-- <div class="row"> -->
                                 </div>
                                 <div class="col-md-1 p-l-0">
                                    <button class="btn-save activeBtn" id="history_btn" type="button" style="font-weight:bold;">History</button>
                                    <button class="btn-save" id="family_history_btn" type="button" style="font-weight:bold;">Family History</button>
                                    <button class="btn-save" id="personal_history_btn" type="button" style="line-height:unset;font-weight:bold;">Personal History</button>
                                    <button class="btn-save" id="menstrual_history_btn" type="button" style="line-height:unset;font-weight:bold;">Menstrual History</button>
                                    <button class="btn-save" id="medical_history_btn" type="button" style="line-height:unset;font-weight:bold;">Medical History</button>
                                    <button class="btn-save" id="obestetric_history_btn" type="button" style="line-height:unset;font-weight:bold;">Obestetric History</button>
                                    <button class="btn-save" id="current_medication_btn" type="button" style="line-height:unset;font-weight:bold;">Current Medication</button>
                                 </div>
                              </div>
                              <!-- rowClose -->
                           </div>
                           <!-- innerTabing Close -->
                        </div>
                     </div>
                  </div>
               </div> 
               <?php } ?>





               <?php 
                  if(strcmp(strtolower($value->setting_name),'disease')=='0'){?>
               <div id="tab_disease" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_disease_data = $this->session->userdata('patient_disease_data');
                     $row_count_patient_disease = count($patient_disease_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_disease; ?>" name="row_id_patient_disease" id="row_id_patient_disease">
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
                                       <select name="patient_disease_name" class="w-150px m_select_btn" id="patient_disease_id">
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
                                       <a href="javascript:void(0)" onclick="return add_disease();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Duration</label>
                                    </div>
                                    <div class="col-md-8">
                                       <input type="text" class="numeric" name="patient_disease_unit" id='patient_disease_unit' style="width:50px;">
                                       <select name="patient_disease_type" class="m_select_btn" id="patient_disease_type" style="width:163px;">
                                          <option value="">Select Unit </option>
                                          <option value="Days">Days</option>
                                          <option value="Week">Week</option>
                                          <option value="Month">Month</option>
                                          <option value="Year">Year</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Descritption</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="disease_description" name="disease_description" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_disease_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered " id='patient_disease_list'>
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
                                          <th>Duration</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <?php  
                                       $i = 0;
                                       if(!empty($patient_disease_data))
                                       {//print_r($patient_disease_data);die;
                                          $i = 1;
                                          foreach($patient_disease_data as $patient_disease_val)
                                          {
                                             if (strpos($patient_disease_val['patient_disease_type'], 'Select') !== false) 
                                             {
                                             $patient_disease_val['patient_disease_type'] = "";
                                             }
                                             else
                                             {
                                             $patient_disease_val['patient_disease_type'] = $patient_disease_val['patient_disease_type'];
                                             }
                                           ?>
                                    <tr name="patient_disease_row" id="<?php echo $i; ?>">
                                       <input type="hidden" id="unique_id_patient_disease" name="unique_id_patient_disease" value="<?php echo $i; ?>"> 
                                       <td>
                                          <input type="checkbox" class="part_checkbox_disease booked_checkbox" name="patient_disease_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_disease_val['disease_value']; ?></td>
                                       <td><?php echo $patient_disease_val['patient_disease_unit'].' '.$patient_disease_val['patient_disease_type']; ?></td>
                                       <td><?php echo $patient_disease_val['disease_description']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">Disease History not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_disease_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div> 
               <?php } ?>



               <?php 
                  if(strcmp(strtolower($value->setting_name),'complaints')=='0'){?>
               <div id="tab_complaints" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_complaint_data = $this->session->userdata('patient_complaint_data');
                     $row_count_patient_complaint = count($patient_complaint_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_complaint; ?>" name="row_id_patient_complaint" id="row_id_patient_complaint">
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
                                       <select name="patient_complaint_name" class="w-150px m_select_btn" id="patient_complaint_id">
                                          <option value="">Select Complaint</option>
                                          <?php
                                             if(!empty($complaint_list))
                                             {
                                               foreach($complaint_list as $complaint)
                                               {
                                                 ?>
                                          <option <?php if($form_data['disease_id']==$complaint->id){ echo 'selected="selected"'; } ?> value="<?php echo $complaint->id; ?>"><?php echo $complaint->complaints; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_complaint();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Duration</label>
                                    </div>
                                    <div class="col-md-8">
                                       <input type="text" class="numeric" name="patient_complaint_unit" id='patient_complaint_unit' style="width:50px;">
                                       <select name="patient_complaint_type" class="m_select_btn" id="patient_complaint_type" style="width:164px;">
                                          <option value="">Select Unit </option>
                                          <option value="Days">Days</option>
                                          <option value="Week">Week</option>
                                          <option value="Month">Month</option>
                                          <option value="Year">Year</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Descritption</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="complaint_description" name="complaint_description" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_complaint_listdata();" class="theme-color add-btn" style="float:right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_complaint_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_complaints(this);">
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
                                          <th>Duration</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                       $i = 0;
                                       if(!empty($patient_complaint_data))
                                       {
                                          $i = 1;
                                          foreach($patient_complaint_data as $patient_complaint_val)
                                          {
                                             if (strpos($patient_complaint_val['patient_complaint_type'], 'Select') !== false) 
                                             {
                                             $patient_complaint_val['patient_complaint_type'] = "";
                                             }
                                             else
                                             {
                                             $patient_complaint_val['patient_complaint_type'] = $patient_complaint_val['patient_complaint_type'];
                                             }
                                           ?>
                                    <tr name="patient_complaint_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_complaint_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_complaints booked_checkbox" name="patient_complaint_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_complaint" name="unique_id_patient_complaint" value="<?php echo $i; ?>"> 
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_complaint_val['complaint_value']; ?></td>
                                       <td><?php echo $patient_complaint_val['patient_complaint_unit'].' '.$patient_complaint_val['patient_complaint_type']; ?></td>
                                       <td><?php echo $patient_complaint_val['complaint_description']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">Complaints not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_complaint_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>



               <?php 
                  if(strcmp(strtolower($value->setting_name),'allergy')=='0'){?>
               <div id="tab_allergy" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_allergy_data = $this->session->userdata('patient_allergy_data');
                     $row_count_patient_allergy = count($patient_allergy_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_allergy; ?>" name="row_id_patient_allergy" id="row_id_patient_allergy">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Allergy Name</label>
                                    </div>
                                    <div class="col-md-6">
                                       <select name="patient_allergy_name" class="w-150px m_select_btn" id="patient_allergy_id">
                                          <option value="">Select Allergy</option>
                                          <?php
                                             if(!empty($allergy_list))
                                             {
                                               foreach($allergy_list as $allergy)
                                               {
                                                 ?>
                                          <option <?php if($form_data['disease_id']==$allergy->id){ echo 'selected="selected"'; } ?> value="<?php echo $allergy->id; ?>"><?php echo $allergy->allergy_name; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_allergy();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Duration</label>
                                    </div>
                                    <div class="col-md-8">
                                       <input type="text" class="numeric" name="patient_allergy_unit" id='patient_allergy_unit' style="width:50px;">
                                       <select name="patient_allergy_type" class="m_select_btn" id="patient_allergy_type" style="width:164px;">
                                          <option value="">Select Unit </option>
                                          <option value="Days">Days</option>
                                          <option value="Week">Week</option>
                                          <option value="Month">Month</option>
                                          <option value="Year">Year</option>
                                       </select>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Descritption</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="allergy_description" name="allergy_description" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                      <button type="button" onclick="add_patient_allergy_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_allergy_list'>
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
                                          <th>Allergy Name</th>
                                          <th>Duration</th>
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                       $i = 0;
                                       if(!empty($patient_allergy_data))
                                       {
                                          $i = 1;
                                          foreach($patient_allergy_data as $patient_allergy_val)
                                          {
                                             if (strpos($patient_allergy_val['patient_allergy_type'], 'Select') !== false) 
                                             {
                                             $patient_allergy_val['patient_allergy_type'] = "";
                                             }
                                             else
                                             {
                                             $patient_allergy_val['patient_allergy_type'] = $patient_allergy_val['patient_allergy_type'];
                                             }
                                           ?>
                                    <tr name="patient_allergy_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_allergy_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_allergy booked_checkbox" name="patient_allergy_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_allergy" name="unique_id_patient_allergy" value="<?php echo $i; ?>"> 
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_allergy_val['allergy_value']; ?></td>
                                       <td><?php echo $patient_allergy_val['patient_allergy_unit'].' '.$patient_allergy_val['patient_allergy_type']; ?></td>
                                       <td><?php echo $patient_allergy_val['allergy_description']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">Allergy not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_allergy_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div> 
               <?php  }  ?>


               <?php 
                  if(strcmp(strtolower($value->setting_name),'general_examination')=='0'){?>
               <div id="tab_general_examination" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
                     $row_count_general_examination = count($patient_general_examination_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_general_examination; ?>" name="row_id_general_examination" id="row_id_general_examination">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Exam Name</label>
                                    </div>
                                    <div class="col-md-6">
                                       <select name="patient_general_examination_name" class="w-150px m_select_btn" id="patient_general_examination_id">
                                          <option value="">Select Exam</option>
                                          <?php
                                             if(!empty($general_examination_list))
                                             {
                                               foreach($general_examination_list as $general_examination)
                                               {
                                                 ?>
                                          <option <?php if($form_data['disease_id']==$general_examination->id){ echo 'selected="selected"'; } ?> value="<?php echo $general_examination->id; ?>"><?php echo $general_examination->general_examination; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_general_examination();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Descritption</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="general_examination_description" name="general_examination_description" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_general_examination_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_general_examination_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_general_examination(this);">
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
                                          <th>Exam Name</th>
                                          
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                       $i = 0;
                                       if(!empty($patient_general_examination_data))
                                       {
                                          $i = 1;
                                          foreach($patient_general_examination_data as $patient_general_examination_val)
                                          {
                                             
                                           ?>
                                    <tr name="patient_general_examination_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_general_examination_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_general_examination booked_checkbox" name="patient_general_examination_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_general_examination" name="unique_id_patient_general_examination" value="<?php echo $i; ?>">
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_general_examination_val['general_examination_value']; ?></td>
                                       
                                       <td><?php echo $patient_general_examination_val['general_examination_description']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">General Exam not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_general_examination_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div> 
               </div> 
               <?php  }  ?>



               <?php 
                  if(strcmp(strtolower($value->setting_name),'clinical_examination')=='0'){?>
               <div id="tab_clinical_examination" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
                     $row_count_clinical_examination = count($patient_clinical_examination_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_clinical_examination; ?>" name="row_id_clinical_examination" id="row_id_clinical_examination">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Exam Name</label>
                                    </div>
                                    <div class="col-md-6">
                                       <select name="patient_clinical_examination_name" class="w-150px m_select_btn" id="patient_clinical_examination_id">
                                          <option value="">Select Exam</option>
                                          <?php
                                             if(!empty($clinical_examination_list))
                                             {
                                               foreach($clinical_examination_list as $clinical_examination)
                                               {
                                                 ?>
                                          <option <?php if($form_data['disease_id']==$clinical_examination->id){ echo 'selected="selected"'; } ?> value="<?php echo $clinical_examination->id; ?>"><?php echo $clinical_examination->clinical_examination; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_clinical_examination();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Descritption</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="clinical_examination_description" name="clinical_examination_description" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_clinical_examination_listdata();" class="theme-color add-btn" style="float:right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_clinical_examination_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_clinical_examination(this);">
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
                                          <th>Exam Name</th>
                                          
                                          <th>Description</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                       $i = 0;
                                       if(!empty($patient_clinical_examination_data))
                                       {
                                          $i = 1;
                                          foreach($patient_clinical_examination_data as $patient_clinical_examination_val)
                                          {
                                             
                                           ?>
                                    <tr name="patient_clinical_examination_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_clinical_examination_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_clinical_examination booked_checkbox" name="patient_clinical_examination_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_clinical_examination" name="unique_id_patient_clinical_examination" value="<?php echo $i; ?>">
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_clinical_examination_val['clinical_examination_value']; ?></td>
                                       
                                       <td><?php echo $patient_clinical_examination_val['clinical_examination_description']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">Clinical Exam not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_clinical_examination_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>


               <?php 
                  if(strcmp(strtolower($value->setting_name),'investigation')=='0'){?>
               <div id="tab_investigation" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_investigation_data = $this->session->userdata('patient_investigation_data');
                     $row_count_patient_investigation = count($patient_investigation_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_investigation; ?>" name="row_id_patient_investigation" id="row_id_patient_investigation">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Investigation</label>
                                    </div>
                                    <div class="col-md-6">
                                       <select name="patient_investigation_name" class="w-150px m_select_btn" id="patient_investigation_id" onchange="get_std_value(this.value);">
                                          <option value="">Select Investigation</option>
                                          <?php
                                             //print_r($investigation_list);die;
                                             if(!empty($investigation_list))
                                             {
                                              foreach($investigation_list as $investigation)
                                              {
                                                ?>
                                          <option <?php if($form_data['disease_id']==$investigation->id){ echo 'selected="selected"'; } ?> value="<?php echo $investigation->id; ?>"><?php echo $investigation->investigation; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_investigation();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Std Value</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input class="w-140px" id="std_value" name="std_value" type="text">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Observed Value</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input class="w-140px" id="observed_value" name="observed_value" type="text">
                                    </div>
                                    
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Date</label>
                                    </div>
                                    <div class="col-md-6">
                                      <input type="text" name="investigation_date" id="investigation_date" style="width:215px;" class="datepicker date"  data-date-format="dd-mm-yyyy"  value="" readonly/> 
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_investigation_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_investigation_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_investigation(this);">
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
                                          <th>Investigation Name</th>
                                          <th>Std. Value</th>
                                          <th>Observed Value</th>
                                          <th>Date</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                      
                                       $i = 0;
                                       if(!empty($patient_investigation_data))
                                       {
                                          $i = 1;
                                          foreach($patient_investigation_data as $patient_investigation_val)
                                          {
                                             if (strpos($patient_investigation_val['investigation_value'], 'Select') !== false) 
                                             {
                                             $patient_investigation_val['investigation_value'] = "";
                                             }
                                             else
                                             {
                                             $patient_investigation_val['investigation_value'] = $patient_investigation_val['investigation_value'];
                                             }
                                             $investigation_date='';
                                            if(($patient_investigation_val['investigation_date']=="01-01-1970")||($patient_investigation_val['investigation_date']=='') || ($patient_investigation_val['investigation_date']=="00-00-0000"))
                                            $investigation_date = "";
                                            else
                                            $investigation_date = date("d-m-Y",strtotime($patient_investigation_val['investigation_date']));
                                           ?>
                                    <tr name="patient_investigation_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_investigation_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_investigation booked_checkbox" name="patient_investigation_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_investigation" name="unique_id_patient_investigation" value="<?php echo $i; ?>">
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_investigation_val['investigation_value']; ?></td>
                                       <td><?php echo $patient_investigation_val['std_value']; ?></td>
                                       <td><?php echo $patient_investigation_val['observed_value']; ?></td>
                                       <td><?php echo $patient_investigation_val['investigation_date']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="6" align="center" class=" text-danger ">
                                          <div class="text-center">Investigation not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_investigation_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div> 
               <?php  }  ?>



               <?php 
                  if(strcmp(strtolower($value->setting_name),'medicine')=='0'){?>
               <div id="tab_medicine" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <div class="row m-t-10">
                     <div class="col-xs-12">
                        <div class="well tab-right-scroll" style="border:none;min-height:auto;">
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
                                       <input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class="input-small medicine_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>">
                                       <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->id; ?>"> 
                                    </td>
                                    <?php 
                                       }
                                       
                                       if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                                       {
                                       ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_brand]" class="input-small" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                                    <?php 
                                       } 
                                       
                                       if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                                       {
                                       ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" id="salt<?php echo $l; ?>" class="input-small" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                                    <?php 
                                       } 
                                       
                                       if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                                       { ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" id="type<?php echo $l; ?>" class=" medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                                    <?php 
                                       }
                                       if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                                       { ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class=" dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                                    <?php 
                                       } 
                                       if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                                       {  ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class="input-small medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                                    <?php 
                                       }
                                       if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                                       {
                                       ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class="input-small medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                                    <?php 
                                       } 
                                       if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                                       { 
                                       ?>
                                    <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class="input-small medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
                                    <?php } }
                                       ?>
                                    <script type="text/javascript">
                                       /* script start */
                                         $(function () 
                                         {
                                               var getData = function (request, response) { 
                                                 row = <?php echo $l; ?> ;
                                                 $.ajax({
                                                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                                            /* var getData = function (request, response) { 
                                                 $.getJSON(
                                                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                                                     function (data) {
                                                         response(data);
                                                     });
                                             };*/
                                             
                                             var getData = function (request, response) { 
                                             row = i ;
                                             $.ajax({
                                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                                             /*var getData = function (request, response) { 
                                                 $.getJSON(
                                                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                                                     function (data) {
                                                         response(data);
                                                     });
                                             };*/
                                             
                                             var getData = function (request, response) { 
                                             
                                             $.ajax({
                                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                                             dataType: "json",
                                             method: 'post',
                                           data: {
                                              name_startsWith: request.term,
                                              type: 'country_table',
                                             
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
                                            /* var getData = function (request, response) { 
                                                 $.getJSON(
                                                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                                                     function (data) {
                                                         response(data);
                                                     });
                                             };*/
                                             
                                             var getData = function (request, response) { 
                                             
                                             $.ajax({
                                             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                                             dataType: "json",
                                             method: 'post',
                                           data: {
                                              name_startsWith: request.term,
                                              type: 'country_table',
                                             
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
                                             /*var getData = function (request, response) { 
                                                 $.getJSON(
                                                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                                                     function (data) {
                                                         response(data);
                                                     });
                                             };*/
                                             
                                             var getData = function (request, response) { 
                                             
                                         $.ajax({
                                         url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                                         dataType: "json",
                                         method: 'post',
                                       data: {
                                          name_startsWith: request.term,
                                          type: 'country_table',
                                         
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
                                             /*var getData = function (request, response) { 
                                                 $.getJSON(
                                                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                                                     function (data) {
                                                         response(data);
                                                     });
                                             };*/
                                             
                                              var getData = function (request, response) { 
                                             
                                                 $.ajax({
                                                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                                                 dataType: "json",
                                                 method: 'post',
                                               data: {
                                                  name_startsWith: request.term,
                                                  type: 'country_table',
                                                 
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
                                           function delete_prescr_row_patient(r)
                                           { 
                                               var i = r.parentNode.parentNode.rowIndex;
                                               document.getElementById("prescription_name_table").deleteRow(i);
                                           }
                                           
                                    </script>
                                    <td width="80">
                                       <a onclick="delete_prescr_row_patient(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
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
                                    <td><input type="text" name="prescription[1][medicine_name]" class="input-small medicine_val" onkeyup="get_medicine_autocomplete(0);" id="medicine_name0">
                                       <input type="hidden" name="medicine_id[]" id="medicine_id">
                                    </td>
                                    <?php 
                                       }
                                       if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                                       {
                                       ?>
                                    <td><input type="text" id="brand0" name="prescription[1][medicine_brand]" class="input-small"></td>
                                    <?php 
                                       } 
                                       
                                       if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                                       {
                                       ?>
                                    <td><input type="text" name="prescription[1][medicine_salt]" class="input-small" id="salt0" ></td>
                                    <?php 
                                       }
                                       
                                       if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                                       { ?>
                                    <td><input type="text" name="prescription[1][medicine_type]" class="input-small" id="type0" onkeyup="get_medicine_type_autocomplete(0,2);"></td>
                                    <?php 
                                       }
                                       if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                                       { ?>
                                    <td><input type="text" name="prescription[1][medicine_dose]" class="input-small" id="dose0" onkeyup="get_medicine_dose_autocomplete(0,2);"></td>
                                    <?php 
                                       } 
                                       if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                                       {  ?>
                                    <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-name input-small" id="duration0" onkeyup="get_medicine_duration_autocomplete(0,2);"></td>
                                    <?php 
                                       }
                                       if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                                       {
                                       ?>
                                    <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-name input-small" id="frequency0" onkeyup="get_medicine_frequency_autocomplete(0,2);"></td>
                                    <?php 
                                       } 
                                       if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                                       { 
                                       ?>
                                    <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-name input-small" id="advice0" onkeyup="get_medicine_advice_autocomplete(0,2);"></td>
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
               </div>
               <?php  }  ?>


               <?php 
                  if(strcmp(strtolower($value->setting_name),'advice')=='0'){?>
               <div id="tab_advice" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_advice_data = $this->session->userdata('patient_advice_data');
                     $row_count_patient_advice = count($patient_advice_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_advice; ?>" name="row_id_patient_advice" id="row_id_patient_advice">
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
                                        
                                        
                                         
                                      <select name="patient_advice_master" class="w-150px m_select_btn" id="patient_advice_master" onchange="get_advice_master_value(this.value);">
                                          <option value="">Select Advice</option>
                                          <?php
                                             //print_r($advice_list);die;
                                             if(!empty($advice_list))
                                             {
                                              foreach($advice_list as $advice)
                                              {
                                                ?>
                                          <option value="<?php echo $advice->id; ?>"><?php echo $advice->advice; ?></option>
                                          <?php
                                             }
                                             }
                                             ?>
                                       </select>
                                       
                                        <textarea id="patient_advice_id" name="patient_advice_name" style="height:100px;"></textarea>
                                    </div>
                                    <div class="col-md-2">
                                       <a href="javascript:void(0)" onclick="return add_advice();"  class="btn-new"><i class="">New</i></a>
                                    </div>
                                 </div>

                                  <div class="row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_advice_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                      
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_advice_list'>
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
                                          <th>Advice Name</th>
                                       </tr>
                                    </thead>
                                    <?php  
                                       $i = 0;
                                       if(!empty($patient_advice_data))
                                       {
                                          $i = 1;
                                          foreach($patient_advice_data as $patient_advice_val)
                                          {
                                             if (strpos($patient_advice_val['advice_value'], 'Select') !== false) 
                                             {
                                             $patient_advice_val['advice_value'] = "";
                                             }
                                             else
                                             {
                                             $patient_advice_val['advice_value'] = $patient_advice_val['advice_value'];
                                             }
                                           ?>
                                    <tr name="patient_advice_row" id="<?php echo $i; ?>">
                                       <td id="<?php //echo $patient_advice_val['disease_id']; ?>">
                                          <input type="checkbox" class="part_checkbox_advice booked_checkbox" name="patient_advice_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_advice" name="unique_id_patient_advice" value="<?php echo $i; ?>"> 
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo nl2br($patient_advice_val['advice_value']); ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="3" align="center" class=" text-danger ">
                                          <div class="text-center">Advice not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_advice_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>


               <?php if(strcmp(strtolower($value->setting_name),'next_appointment')=='0'){   ?>
               <div id="tab_next_appointment" style="border:1px solid #ccc;" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
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
                                      ?>
                                 <div class="row" id="date_time_next" style="<?php echo $display; ?>">
                                    <div class="col-md-4">
                                       <label>Appointment Date Time</label>
                                    </div>
                                    <div class="col-md-6">
                                      <input type="text" name="nxt_days" id="nxt_days" style="width:60px;" /> &emsp;

                                       <input type="text" name="next_appointment_date" id="next_appointment_date"  class="datepickertime date"  data-date-format="dd-mm-yyyy HH:ii"  value="<?php echo $form_data['next_appointment_date']; ?>" readonly style="width:135px;"/> 

                                      <input id="bok_date_time" type="hidden" class="datepicker date"  data-date-format="dd-mm-yyyy" value="<?php echo date('d-m-Y',strtotime($form_data['booking_date']));?>">
                                    </div>
                                 </div>
                                 <div class="row">
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-7">
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div> 
               <?php } ?>



            
               <?php 
                  if(strcmp(strtolower($value->setting_name),'gpla')=='0'){?>
               <div id="tab_gpla" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_gpla_data = $this->session->userdata('patient_gpla_data');
                     $row_count_patient_gpla = count($patient_gpla_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_patient_gpla; ?>" name="row_id_patient_gpla" id="row_id_patient_gpla">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="gplad">
                           <div class="col-md-5" >
                              <div class="box-left">
                                
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>DOG</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input class="w-140px" id="dog_value" name="dog_value" type="text">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Mode</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input class="w-140px" id="mode_value" name="mode_value" type="text">
                                    </div>
                                    
                                 </div>
                               
                               <div class="row">
                                    <div class="col-md-4">
                                       <label>Month Year</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input class="w-140px" id="monyear_value" name="monyear_value" type="text">
                                    </div>
                                    
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_gpla_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>
                                 
                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_gpla_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_gpla(this);">
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
                                          <th>DOG</th>
                                          <th>Mode</th>
                                          <th>Month Year</th>
                                         
                                       </tr>
                                    </thead>
                                    <?php 
                                      
                                       $i = 0;
                                       if(!empty($patient_gpla_data))
                                       {
                                          $i = 1;
                                          foreach($patient_gpla_data as $patient_gpla_val)
                                          {?>
                                    <tr name="patient_investigation_row" id="<?php echo $i; ?>">
                                       <td>
                                          <input type="checkbox" class="part_checkbox_gpla booked_checkbox" name="patient_gpla_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_gpla" name="unique_id_patient_gpla" value="<?php echo $i; ?>">
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_gpla_val['dog_value']; ?></td>
                                       <td><?php echo $patient_gpla_val['mode_value']; ?></td>
                                       <td><?php echo $patient_gpla_val['monthyear_value']; ?></td>
                                      
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                    <tr>
                                       <td colspan="6" align="center" class=" text-danger ">
                                          <div class="text-center">GPLA not available.</div>
                                       </td>
                                    </tr>
                                    <?php
                                       }
                                       ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_gpla_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    <?php //} ?>
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div> 
               <?php  }  ?>




               <?php  //echo $value->setting_name;
              if(strcmp(strtolower($value->setting_name),'follicularscaning')=='0')
              {
                
                $patient_right_ovary_data = $this->session->userdata('patient_right_ovary_data');
                $row_count = count($patient_right_ovary_data) + 1;
                 ?>


           <div id="tab_follicularscaning" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
              <input type="hidden" value="<?php echo $row_count; ?>" name="row_id" id="row_right_ovary_id">
              <div class="row">
                 <div class="col-md-12 tab-content dental-chart" style="border:1px solid #ccc;">
                    <div class="tab-pane fade in active" id="chief" style="padding:0 10px;">
                                               <!-- inner tabing -->
                          <div class="col-md-11">
                            <div class="innertab innertab1" style="font-size:9pt;">
                              <div class="" style="display:flex;flex-wrap:wrap;">
                                  
                                  
                                  
                                 
                                 <div id="marriage_columns">
                                     <div class="row m-b-4 col-md-3" style="height: 25px;">
                                    <div class="col-md-4">
                                       <label>Date</label>
                                    </div>
                                    <div class="col-md-8">
                                       
                                       <input type="text" name="right_folli_date" id="right_folli_date"  class="datepicker"  data-date-format="dd-mm-yyyy" style="width:140px;"  value="<?php if(!empty($right_ovary_data[0]['right_folli_date']) && trim($right_ovary_data[0]['right_folli_date'])!='0000-00-00'){ echo date('d-m-Y', strtotime($right_ovary_data[0]['right_folli_date'])); } ?>"/> 
                                    </div>
                                 </div>
                                 
                                    <div class="row m-b-4 col-md-3" style="height: 25px;">
                                       <div class="col-md-4">
                                          <label>Day</label>
                                       </div>
                                       <div class="col-md-8">
                                          <input type="text" class="" name="right_folli_day"  style="width:140px;" id='right_folli_day' value="<?php echo $right_ovary_data[0]['right_folli_day']; ?>">
                                          
                                       </div>
                                    </div>
                                    
                                    <div class="row m-b-4 col-md-3" style="height: 25px;">
                                       <div class="col-md-4">
                                          <label>protocol</label>
                                       </div>
                                       <div class="col-md-8">
                                          
                                          <input type="text" class="" name="right_folli_protocol"  style="width:140px;" id='right_folli_protocol' value="<?php echo $right_ovary_data[0]['right_folli_protocol']; ?>">
                                       </div>
                                    </div>
                                    <div class="row m-b-4 col-md-3" style="height: 25px;">
                                       <div class="col-md-4">
                                          <label>PFSH</label>
                                       </div>
                                       <div class="col-md-8">
                                          <input type="text" style="width:140px;" class="" name="right_folli_pfsh" id='right_folli_pfsh' value="<?php echo $right_ovary_data[0]['right_folli_pfsh']; ?>">
                                       </div>
                                    </div>
                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>REC FSH</label>
                                         </div>
                                         <div class="col-md-8">
                                            <input type="text" class="" name="right_folli_recfsh" style="width:140px;" id='right_folli_recfsh' value="<?php echo $right_ovary_data[0]['right_folli_recfsh']; ?>">
                                         </div>
                                      </div>
                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>HMG</label>
                                         </div>
                                         <div class="col-md-8">
                                            
                                            <input type="text" style="width:140px;" id="right_folli_hmg" name="right_folli_hmg" value="<?php echo $right_ovary_data[0]['right_folli_hmg']; ?>">
                                         </div>
                                      </div>

                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>HP HMG</label>
                                         </div>
                                         <div class="col-md-8">
                                        
                                            <input type="text" id="right_folli_hp_hmg" style="width:140px;" name="right_folli_hp_hmg" value="<?php echo $right_ovary_data[0]['right_folli_hp_hmg']; ?>">
                                         </div>
                                      </div>

                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>Agonist</label>
                                         </div>
                                         <div class="col-md-8">
                                           
                                             <input type="text" id="right_folli_agonist" style="width:140px;" name="right_folli_agonist" value="<?php echo $right_ovary_data[0]['right_folli_agonist']; ?>">
                                         </div>
                                      </div>

                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>Antagonist</label>
                                         </div>
                                         <div class="col-md-8">
                                           
                                            <input type="text" style="width:140px;" id="right_folli_antiagonist" name="right_folli_antiagonist" value="<?php echo $right_ovary_data[0]['right_folli_antiagonist']; ?>">
                                         </div>
                                      </div>

                                      <div class="row m-b-4 col-md-3" style="height: 25px;">
                                         <div class="col-md-4">
                                            <label>Trigger</label>
                                         </div>
                                         <div class="col-md-8">
                                            <input type="text" style="width:140px;" id="right_folli_trigger" name="right_folli_trigger" value="<?php echo $right_ovary_data[0]['right_folli_trigger']; ?>">
                                         </div>
                                      </div>

                                      

                                 </div>
                                        <div class="row m-b-4 col-md-3" style="margin-top:15px;">
                                         <div class="col-md-4">
                                            <label>Size</label>
                                         </div>
                                         <div class="col-md-8">
                                          <select name="right_follic_size" id="right_follic_size" style="width:140px">
                                            <option value=" ">Select Size</option>
                                            <option value="9MM">9MM</option>
                                            <option value="11MM">11MM</option>
                                            <option value="13MM">13MM</option>
                                            <option value="14MM">14MM</option>
                                            <option value="15MM">15MM</option>
                                            <option value="16MM">16MM</option>
                                            <option value="17MM">17MM</option>
                                            <option value="18MM">18MM</option>
                                            <option value="19MM">19MM</option>
                                            <option value="20MM">20MM</option>
                                            <option value="21MM">21MM</option>
                                            <option value="22MM">22MM or More</option>
                                          </select>
                                         </div>
                                      </div>
                                      
                                      <div class="row m-b-4 col-md-3" style="margin-top:15px;">
                                          <div class="col-md-4">
                                              <button type="button" onclick="add_right_ovary();" class="add-btn theme-color">Add</button> 
                                          </div>
                                          <div class="col-md-8"></div>
                                      </div>
                                       
                                       
                                 
                                 
                              </div>    
                               <!-- patient history-history starts -->
                               
                               
                               <!-- patient history-history ends -->
                            </div>
                                 





                                                  <div class="innertab innertab2" style="display:none;">
                             <!-- patient menstrual history starts -->
                             <?php  
                                $left_ovary_data = $this->session->userdata('left_ovary_data');
                                $row_left_ovary_id = count($left_ovary_data) + 1;
                                ?>
                             <input type="hidden" value="<?php echo $row_left_ovary_id; ?>" name="row_left_ovary_id" id="row_left_ovary_id">
                             <div class="row">
                                <div class="col-md-12" style="display:flex;flex-wrap:wrap;">
                                   
                                   <div class="row m-b-3 col-md-3">
                                       <div class="col-md-5">
                                          <label>Date</label>
                                       </div>
                                       <div class="col-md-7">
                                           
                                           <input type="text" name="left_folli_date" id="left_folli_date"  class="datepicker"  data-date-format="dd-mm-yyyy" style="width:140px;"  value="<?php if(!empty($left_ovary_data[0]['left_folli_date']) && trim($left_ovary_data[0]['left_folli_date'])!='0000-00-00'){ echo date('d-m-Y', strtotime($left_ovary_data[0]['left_folli_date'])); } ?>"/> 
                                        </div>
                                     </div>

                                   <div class="row m-b-3 col-md-3">
                                       <div class="col-md-5">
                                          <label>Day</label>
                                       </div>
                                       <div class="col-md-7">
                                          <input type="text" class="" name="left_folli_day" id='left_folli_day' style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_day']; ?>">
                                          
                                       </div>
                                    </div>

                                        <div class="row m-b-3 col-md-3">
                                           <div class="col-md-5">
                                              <label>protocol</label>
                                           </div>
                                           <div class="col-md-7">
                                              
                                              <input type="text" class="" name="left_folli_protocol" id='left_folli_protocol' style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_protocol']; ?>">
                                           </div>
                                        </div>

                                        <div class="row m-b-3 col-md-3">
                                           <div class="col-md-5">
                                              <label>PFSH</label>
                                           </div>
                                           <div class="col-md-7">
                                              <input type="text" class="" name="left_folli_pfsh" id='left_folli_pfsh' style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_pfsh']; ?>">
                                           </div>
                                        </div>
                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>REC FSH</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" class="" name="left_folli_recfsh" id='left_folli_recfsh' style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_recfsh']; ?>">
                                             </div>
                                          </div>
                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>HMG</label>
                                             </div>
                                             <div class="col-md-7">
                                                
                                                <input type="text" id="left_folli_hmg" name="left_folli_hmg" style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_hmg']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>HP HMG</label>
                                             </div>
                                             <div class="col-md-7">
                                            
                                                <input type="text" id="left_folli_hp_hmg" name="left_folli_hp_hmg" style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_hp_hmg']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Agonist</label>
                                             </div>
                                             <div class="col-md-7">
                                               
                                                 <input type="text" id="left_folli_agonist" name="left_folli_agonist" style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_agonist']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Antagonist</label>
                                             </div>
                                             <div class="col-md-7">
                                               
                                                <input type="text" id="left_folli_antiagonist" name="left_folli_antiagonist" style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_antiagonist']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Trigger</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="left_folli_trigger" name="left_folli_trigger" style="width:140px;" value="<?php echo $left_ovary_data[0]['left_folli_trigger']; ?>">
                                             </div>
                                          </div>

                                          
                                      
                                       <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Endometriumothers</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="endometriumothers" name="endometriumothers" style="width:140px;" value="<?php echo $left_ovary_data[0]['endometriumothers']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>E2</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="e2" name="e2" style="width:140px;" value="<?php echo $left_ovary_data[0]['e2']; ?>">
                                             </div>
                                          </div>

                                          <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>P4</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="p4" name="p4" style="width:140px;" value="<?php echo $left_ovary_data[0]['p4']; ?>">
                                             </div>
                                          </div>

                                           <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Risk</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="risk" name="risk" style="width:140px;" value="<?php echo $left_ovary_data[0]['risk']; ?>">
                                             </div>
                                          </div>

                                           <div class="row m-b-3 col-md-3">
                                             <div class="col-md-5">
                                                <label>Others</label>
                                             </div>
                                             <div class="col-md-7">
                                                <input type="text" id="others" name="others"  style="width:140px;" value="<?php echo $left_ovary_data[0]['others']; ?>">
                                             </div>
                                          </div>
                                          
                                        
                                </div>                           

                                           <div class="row m-b-3 col-md-3" style="margin-top:15px;margin-left:0px">
                                             <div class="col-md-5">
                                                <label>Size</label>
                                             </div>
                                             <div class="col-md-7" style="padding-left:12px;">
                                                <select name="left_follic_size" id="left_follic_size" style="width:140px;">
                                                <option value=" ">Select Size</option>
                                                <option value="9MM">9MM</option>
                                                <option value="11MM">11MM</option>
                                                <option value="13MM">13MM</option>
                                                <option value="14MM">14MM</option>
                                                <option value="15MM">15MM</option>
                                                <option value="16MM">16MM</option>
                                                <option value="17MM">17MM</option>
                                                <option value="18MM">18MM</option>
                                                <option value="19MM">19MM</option>
                                                <option value="20MM">20MM</option>
                                                <option value="21MM">21MM</option>
                                                <option value="22MM">22MM or More</option>
                                              </select>
                                             </div>
                                          </div>
                                          
                                          <div class="row m-b-3 col-md-3" style="margin-top:15px;">
                                              <div class="col-md-5">
                                                  <button type="button" onclick="add_left_ovary();" class="add-btn theme-color">Add</button>
                                              </div>
                                              <div class="col-md-7"></div>
                                          </div>
                                          
                                      <!--<div class="row m-b-12"> 
                                        <div class="row m-b-3">
                                             <div class="col-md-5">
                                                <label>Size</label>
                                             </div>
                                             <div class="col-md-7">
                                              <select name="left_follic_size" id="left_follic_size" style="width:140px;">
                                                <option value=" ">Select Size</option>
                                                <option value="9MM">9MM</option>
                                                <option value="11MM">11MM</option>
                                                <option value="13MM">13MM</option>
                                                <option value="14MM">14MM</option>
                                                <option value="15MM">15MM</option>
                                                <option value="16MM">16MM</option>
                                                <option value="17MM">17MM</option>
                                                <option value="18MM">18MM</option>
                                                <option value="19MM">19MM</option>
                                                <option value="20MM">20MM</option>
                                                <option value="21MM">21MM</option>
                                                <option value="22MM">22MM or More</option>
                                              </select>
                                             </div>
                                          </div>
                                          <div class="col-md-7">
                                           <button type="button" onclick="add_left_ovary();" class="add-btn theme-color" style="float:right;">Add</button>
                                          </div>
                                       </div>-->
                                   
                
                             </div>
                             <!-- patient menstrual history ends -->
                          </div>


                                 <!-- <div class="row"> -->
                                 </div>
                                 <div class="col-md-1 p-l-0">
                                    <button class="btn-save activeBtn" id="right_ovary_btn" type="button" style="font-weight:bold;">Right Ovary</button>
                                    <button class="btn-save" id="left_overy_btn" type="button" style="font-weight:bold;">Left Ovary</button>
                                    
                                 </div>
                                 <div class="col-md-1 p-1-0" style="padding-left:5px; paddding-right:0px;">
                                   <a class="btn-custom m-b-5 m1" id="patient_right_ovary_delete" onclick="delete_right_ovary_vals();">
                                        <i class="fa fa-trash"></i> Delete
                                   </a>     
                                   <table class="table table-bordered input-center table-responsive" id='patient_right_ovary_list'>
                                        <thead>
                                           <tr>
                                              <th align="center" width="50">
                                                 <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_pat(this);">
                                                 <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
                                                    if ($(this).hasClass('allChecked')) {
                                                        $('.checklist').prop('checked', false);
                                                    } else {
                                                        $('.checklist').prop('checked', true);
                                                    }
                                                    $(this).toggleClass('allChecked');
                                                    })</script>
                                                    
                                                    ";  ?>
                                              </th> 
                                              <th width="50">Size </th> 
                                           </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                           $i = 0;
                                           if(!empty($patient_right_ovary_data))
                                           {
                                              $i = 1;
                                              foreach($patient_right_ovary_data as $patient_right_ovary_val)
                                              {
                                                 ?>
                                        <tr name="patient_right_ovary_row" id="<?php echo $i; ?>">
                                           <td width="50">
                                              <input type="checkbox" class="part_checkbox_pat booked_checkbox" name="right_ovary[]" value="<?php echo $i; ?>" >
                                           </td> 
                                           <input type="hidden" id="unique_id" name="unique_id" value="<?php echo $i; ?>"> 
                                           <td width="50"><?php echo $patient_right_ovary_val['right_follic_size']; ?></td>


                                        </tr>
                                        <?php
                                           $i++;
                                           }?>
                                        <?php }
                                           else
                                           { ?>
                                        <tr>
                                           <td colspan="2" align="center" class=" text-danger ">
                                              <div class="text-center">No data available.</div>
                                           </td>
                                        </tr>
                                        <?php }
                                           ?>
                                        </tbody>
                                    </table>  
                                    
                                    <a class="btn-custom m-b-5 m1" id="patient_left_ovary_delete" onclick="delete_left_ovary_vals();" style="display:none;">
                                        <i class="fa fa-trash"></i> Delete
                                   </a>     
                                   <table class="table table-bordered input-center table-responsive" id='patient_left_ovary_list' style="display:none;">
                                        <thead>
                                           <tr>
                                              <th align="center" width="">
                                                 <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_pat(this);">
                                                 <?php echo $check_script = "<script>$('#selectall').on('click', function () { 
                                                    if ($(this).hasClass('allChecked')) {
                                                        $('.checklist').prop('checked', false);
                                                    } else {
                                                        $('.checklist').prop('checked', true);
                                                    }
                                                    $(this).toggleClass('allChecked');
                                                    })</script>
                                                    
                                                    ";  ?>
                                              </th> 
                                              <th>Size </th> 
                                           </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $patient_left_ovary_data = $this->session->userdata('patient_left_ovary_data'); 
                                            $i = 0;
                                            if(!empty($patient_left_ovary_data))
                                            {
                                              $i = 1;
                                              foreach($patient_left_ovary_data as $patient_left_ovary_val)
                                              {
                                              ?>
                                         <tr name="patient_left_overy_row" id="<?php echo $i; ?>">
                                            <td>
                                               <input type="checkbox" class="part_checkbox_left_ovary booked_checkbox" name="patient_left_ovary[]" value="<?php echo $i; ?>" >
                                            </td> 
                                            <input type="hidden" id="unique_id_left_ovary" name="unique_id_left_ovary" value="<?php echo $i; ?>"> 
                                            <td><?php echo $patient_left_ovary_val['left_follic_size']; ?></td> 
                                         </tr>
                                         <?php
                                            $i++;
                                            }
                                            }
                                            else
                                            { ?>
                                         <tr>
                                            <td colspan="2" align="center" class=" text-danger ">
                                               <div class="text-center">No data available.</div>
                                            </td>
                                         </tr>
                                         <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>  
                                 </div>
                              </div>
                              <!-- rowClose -->
                           </div>
                           <!-- innerTabing Close -->
                        </div>
                     </div>
                  </div>
               </div> 
               <?php 
              } ?>


              <?php 
                  if(strcmp(strtolower($value->setting_name),'icsilab')=='0'){ ?>
               <div id="tab_icsilab" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_icsilab_data = $this->session->userdata('patient_icsilab_data');
                     $row_count_icsilab = count($patient_icsilab_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_icsilab; ?>" name="row_icsilab_id" id="row_icsilab_id">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Date</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="icsilab_date"  class="datepicker"  data-date-format="dd-mm-yyyy" id="icsilab_date">
                                    </div>
                               </div>
                                
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Oocytes</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="oocytes" name="oocytes">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>M2</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="m2" name="m2">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Injected</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="injected" name="injected">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Cleavge</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="cleavge" name="cleavge">
                                    </div>
                                   
                                 </div>


                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Embryos day3</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="embryos_day3" name="embryos_day3">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Day 5</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="day5" name="day5">
                                    </div>
                                   
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Day of ET</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="day_of_et" name="day_of_et">
                                    </div>
                                   
                                 </div>
                                 
                                 

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>ET</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="et" name="et">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>VIT</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="vit" name="vit">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>LAH</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="lah" name="lah">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Sperm</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="semen" name="semen">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Count</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="count" name="count">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Motility</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="motility" name="motility">
                                    </div>
                                   
                                 </div>


                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>G3</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="g3" name="g3">
                                    </div>
                                   
                                 </div>



                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Abn. Form</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="abn_form" name="abn_form">
                                    </div>
                                   
                                 </div>


                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>IMSI</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="imsi" name="imsi">
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Pregnancy</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="pregnancy" name="pregnancy" style="height:100px;"></textarea>
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Remarks</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="remarks" name="remarks" style="height:100px;"></textarea>
                                    </div>
                                   
                                 </div>


                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_icsilab_listdata();" class="theme-color add-btn" style="float:right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_icsilab_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_icsilab(this);">
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
                                          <th>Date</th>
                                          <th>Oocytes</th>
                                          <th>M2</th>
                                          <th>Injected</th>
                                          <th>Cleavge</th>
                                          <th>Embryos Day3</th>
                                          <th>Day 5</th>
                                          <th>Day of ET</th>
                                          <th>ET</th>
                                          <th>VIT</th>
                                          <th>LAH</th>
                                          <th>Sperm</th>

                                          <th>Count</th>
                                          <th>Motility</th>
                                          <th>G3</th>
                                          <th>Abn. Form</th>
                                          <th>IMSI</th>
                                          <th>Pregnancy</th>
                                          <th>Remarks</th>
                                       </tr>
                                    </thead>
                                    <?php 
                                       $i = 0;
                                       if(!empty($patient_icsilab_data))
                                       {
                                          $i = 1;
                                          foreach($patient_icsilab_data as $patient_icsilab_val)
                                          {
                                             
                                           ?>
                                        <tr name="patient_icsilab_row" id="<?php echo $i; ?>">
                                       <td>
                                          <input type="checkbox" class="part_checkbox_icsilab booked_checkbox" name="patient_icsilab_name[]" value="<?php echo $i; ?>" >
                                       </td>
                                       <input type="hidden" id="unique_id_patient_icsilab" name="unique_id_patient_icsilab" value="<?php echo $i; ?>">
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_icsilab_val['icsilab_date']; ?></td>
                                       <td><?php echo $patient_icsilab_val['oocytes']; ?></td>
                                       <td><?php echo $patient_icsilab_val['m2']; ?></td>
                                       <td><?php echo $patient_icsilab_val['injected']; ?></td>
                                       <td><?php echo $patient_icsilab_val['cleavge']; ?></td>
                                       <td><?php echo $patient_icsilab_val['embryos_day3']; ?></td>
                                       <td><?php echo $patient_icsilab_val['day5']; ?></td>
                                       
                                       <td><?php echo $patient_icsilab_val['day_of_et']; ?></td>
                                       
                                       <td><?php echo $patient_icsilab_val['et']; ?></td>
                                       
                                       <td><?php echo $patient_icsilab_val['vit']; ?></td>
                                       <td><?php echo $patient_icsilab_val['lah']; ?></td>
                                       <td><?php echo $patient_icsilab_val['semen']; ?></td>
                                       <td><?php echo $patient_icsilab_val['count']; ?></td>
                                       <td><?php echo $patient_icsilab_val['motility']; ?></td>
                                       <td><?php echo $patient_icsilab_val['g3']; ?></td>
                                       <td><?php echo $patient_icsilab_val['abn_form']; ?></td>
                                       <td><?php echo $patient_icsilab_val['imsi']; ?></td>
                                       <td><?php echo $patient_icsilab_val['pregnancy']; ?></td>
                                       <td><?php echo $patient_icsilab_val['remarks']; ?></td>
                                    </tr>
                                    <?php
                                       $i++;
                                       }
                                       }
                                       else
                                       { ?>
                                            <tr>
                                             <td colspan="20" align="center" class=" text-danger ">
                                                <div class="text-center">ICSI lab data not available.</div>
                                             </td>
                                          </tr>
                                    <?php
                                       }
                                    ?> 
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_icsilab_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>
               
               <?php 
                  if(strcmp(strtolower($value->setting_name),'antenatal_care')=='0'){ ?>
               <div id="tab_antenatal_care" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
                     $row_count_antenatal_care = count($patient_antenatal_care_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_antenatal_care; ?>" name="row_antenatal_care_id" id="row_antenatal_care_id">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-5" >
                              <div class="box-left">
                                
                                
                                <div class="row">
                                    <div class="col-md-4">
                                       <label>Weight (Kg)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="weight"  class="m_tiny"   id="weight" numeric onKeyUp="myFunction()">
                                    </div>
                               </div> 
                               
                               <div class="row">
                                    <div class="col-md-4">
                                       <label>Height (cm)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="height"  class="m_tiny"  id="height" onKeyUp="myFunction()" numeric>
                                    </div>
                               </div> 
                                
                                
                                <div class="row">
                                    <div class="col-md-4">
                                       <label>BMI (Kg/m2)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="bmi_calculate"  class="m_tiny" id="bmi_calculate">
                                    </div>
                               </div> 
                                
                                
                                
                                 
                                <!--- test --> 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Confirm Delivery Date</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="confirm_delivery_date"  class="datepicker2"  data-date-format="dd-mm-yyyy" id="confirm_delivery_date">
                                    </div>
                               </div> 
                                
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Last Menstrual Period(LMP)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="antenatal_care_period"  class="datepicker2"  data-date-format="dd-mm-yyyy" id="antenatal_care_period">
                                    </div>
                               </div>
                                
                                 

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Expected Date of Delivery(EDD)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="antenatal_expected_date" name="antenatal_expected_date" class="" readonly  >
                                    </div>
                                   
                                 </div>
                                 
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Gestational age(POG)</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="antenatal_first_date" class="" name="antenatal_first_date">
                                    </div>
                                   
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Ultrasound Images/Video</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="file" id="antenatal_ultrasound"  name="antenatal_ultrasound"> 
                                    </div>
                                   
                                 </div>

                                


                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Remarks</label>
                                    </div>
                                    <div class="col-md-6">
                                       <textarea id="antenatal_remarks" name="antenatal_remarks" style="height:100px;"></textarea>
                                    </div>
                                   
                                 </div>


                                 <div class="row">
                                    <div class="col-md-4">
                                      
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_patient_antenatal_care_listdata();" class="theme-color add-btn" style="float:right;">Add</button>
                                    </div>
                                    <div class="col-md-2">
                                       
                                    </div>
                                 </div>

                              </div>
                           </div>
                           <div class="col-md-7" >
                              <div class="table-box">
                                 <table class="table table-bordered input-center" id='patient_antenatal_care_list'>
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_antenatal_care(this);">
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
                                          
                                          <th>Weight</th>
                                          <th>Height</th>
                                          <th>BMI</th>
                                          <th>Confirm Delivery Date</th>
                                          
                                          <th>Last Menstrual Period(LMP)</th>
                                          <th>Expected Date of Delivery(EDD)</th>
                                          <th>Gestational Age(POG)</th>
                                          <th>Ultrasound Image/Video</th>
                                          <th>Remarks</th>
                                          
                                       </tr>
                                    </thead> 
                                    <tbody></tbody>
                                    <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_antenatal_care_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                    
                                 </table>
                              </div>
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>



               <!-- fertility -->

               <?php 
                  if(strcmp(strtolower($value->setting_name),'fertility')=='0'){?>
               <div id="tab_fertility" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_fertility_data = $this->session->userdata('patient_fertility_data');
                     $row_count_fertility = count($patient_fertility_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_fertility; ?>" name="row_fertility_id" id="row_fertility_id">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        <div class="row tab-pane fade in active" id="chief">
                           <div class="col-md-4" >
                              <div class="box-left">
                                 
                                 
                                  <div class="row">
                                    <div class="col-md-4">
                                       <label>Risk</label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea id="fertility_risk" name="fertility_risk" style="height:100px;"><?php echo $patient_fertillity_risk; ?></textarea>
                                        
                                      
                                    </div>
                                   
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>CO</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" name="fertility_co" value="<?php echo $fertility_co; ?>"  id="fertility_co">
                                    </div>
                               </div>
                               
                              
                                 
                               <div class="row">
                                    <div class="col-md-4">
                                       <label>Uterine Factor</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_uterine_factor" value="<?php echo $fertility_uterine_factor; ?>" name="fertility_uterine_factor">
                                    </div>
                                   
                                 </div>

                                 

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Tubal Factor</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_tubal_factor" value="<?php echo $fertility_tubal_factor; ?>" name="fertility_tubal_factor">
                                    </div>
                                   
                                 </div>
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Decision</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_decision" value="<?php echo $fertility_decision; ?>" name="fertility_decision">
                                    </div>
                                   
                                 </div>
                                 
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Upload HSG</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="file" id="fertility_uploadhsg" name="fertility_uploadhsg">
                                       <input type="hidden" name="old_fertility_uploadhsg" value="<?php echo $fertility_uploadhsg; ?>">
                                       <br>
                                       <?php if(!empty($fertility_uploadhsg)){ ?> 
                                         <a href="<?php echo DIR_FS_PATH.'prescription/hsg/'.$fertility_uploadhsg; ?>" target="_blank"><img src="<?php echo DIR_FS_PATH.'prescription/hsg/'.$fertility_uploadhsg; ?>" width="100px;"></a>
                                         <?php } ?><br>
                                    </div>
                                   
                                 </div>

                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Upload Laparoscopy Video</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="file" id="fertility_laparoscopy" name="fertility_laparoscopy">

                                       <input type="hidden" name="old_fertility_laparoscopy" value="<?php echo $fertility_laparoscopy; ?>">
                                       <br>
                                       <?php if(!empty($fertility_laparoscopy)){ ?> 
                                         <a href="<?php echo DIR_FS_PATH.'prescription/laparoscopy/'.$fertility_laparoscopy; ?>" target="_blank">Download Video</a>
                                         <?php } ?>
                                    </div>
                                   
                                 </div>
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Ultrasound Images</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="file" id="fertility_ultrasound_images"  name="fertility_ultrasound_images">

                                       <input type="hidden" name="old_fertility_ultrasound_images" value="<?php echo $fertility_ultrasound_images; ?>">
                                       <br>
                                       
                                       <?php if(!empty($fertility_ultrasound_images)){ ?> 
                                         <a href="<?php echo DIR_FS_PATH.'prescription/ultrasound/'.$fertility_ultrasound_images; ?>" target="_blank"><img src="<?php echo DIR_FS_PATH.'prescription/ultrasound/'.$fertility_ultrasound_images; ?>" width="100px;"></a>
                                         <?php } ?>

                                    </div>
                                   
                                 </div>
                                 &nbsp;
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Ovarian Factor</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_ovarian_factor" value="<?php echo $fertility_ovarian_factor; ?>" name="fertility_ovarian_factor">
                                    </div>
                                   
                                 </div>
                                 &nbsp;

                                  
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Sperm</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_male_factor" value="<?php echo $fertility_male_factor; ?>" name="fertility_male_factor">
                                    </div>
                                                                       
                                 </div>
                                 
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Date</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_sperm_date" class="datepicker"  value="<?php echo $fertility_sperm_date; ?>" name="fertility_sperm_date">
                                    </div>
                                                                       
                                 </div>
                                 &nbsp;
                                  <div class="row">
                                    <div class="col-md-4">
                                       <label>Count</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_sperm_count" value="<?php echo $fertility_sperm_count; ?>" name="fertility_sperm_count">
                                    </div>
                                                                       
                                 </div>
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Motality</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_sperm_motality" value="<?php echo $fertility_sperm_motality; ?>" name="fertility_sperm_motality">
                                    </div>
                                                                       
                                 </div>
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>G3</label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_sperm_g3" value="<?php echo $fertility_sperm_g3; ?>" name="fertility_sperm_g3">
                                    </div>
                                                                       
                                 </div>
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Abnormal form </label>
                                    </div>
                                    <div class="col-md-6">
                                       <input type="text" id="fertility_sperm_abnform" value="<?php echo $fertility_sperm_abnform; ?>" name="fertility_sperm_abnform">
                                    </div>
                                                                       
                                 </div>
                                 &nbsp;
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label>Remarks </label>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea id="fertility_sperm_remarks" name="fertility_sperm_remarks" style="height:100px;"><?php echo $fertility_sperm_remarks; ?></textarea>
                                       
                                    </div>
                                                                       
                                 </div>
                                 
                                 
                                 <div class="row">
                                    <div class="col-md-4">
                                       <label> </label>
                                    </div>
                                    <div class="col-md-6">
                                       <button type="button" onclick="add_fertility_data();" class="theme-color add-btn" style="float:right;">Add</button>
                                    </div>
                                                                       
                                 </div>

                               

                                 
 
                              </div>
                           </div>
                           <div class="col-md-8"  style="overflow-x:scroll;">
                                <a class="btn-custom m-b-5 m1" id="deleteAll_fertility" onclick="delete_fertility_record();">
                                    <i class="fa fa-trash"></i> Delete
                                    </a>  
                                <table class="table table-bordered input-center" id='table_patient_fertility' style="width:90%;">
                                    <thead>
                                       <tr>
                                          <th align="center" width="">
                                             <input name="selectall" class="" id="selectall" value="" type="checkbox"  onclick="toggle_fertility(this);"> 
                                          </th>
                                          <th scope="col">S.No.</th>
                                          <th>CO</th>
                                          <th>Risk</th>
                                          <th> Uterine Factor </th>
                                          <th>Tubal Factor</th>
                                          <th>Decision</th>
                                          <th> Ovarian Factor </th>
                                          <th>  HSG </th>
                                          <th>Laparoscopy Video</th>
                                          <th> Ultrasound Images </th> 
                                          <th>Sperm</th>
                                          <th>Date</th>
                                          <th>Count</th> 
                                          <th>Motality</th>
                                          <th>G3</th>
                                          <th> Abnormal form </th> 
                                          <th>Remarks</th>
                                       </tr>
                                    </thead>
                                    <tbody><tr><td colspan="18"></td></tr></tbody>
                                </table>    
                                 
                              
                                 
                                 
                           </div>
                        </div>
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>
               <!--- fertility -->
               
               
               
               
               
                <!-- antenatal_care -->

               <?php 
                  if(strcmp(strtolower($value->setting_name),'antenatal_care')=='0'){?>
               <div id="tab_antenatal_care" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php     
                     $patient_antenatal_care_data = $this->session->userdata('patient_antenatal_care_data');
                     $row_count_antenatal_care = count($patient_antenatal_care_data) + 1;
                     ?>
                  <input type="hidden" value="<?php echo $row_count_antenatal_care; ?>" name="row_antenatal_care_id" id="row_fertility_id">
                  <div class="row">
                     <div class="col-md-12 tab-content dental-chart">
                        
                        <!-- row -->
                     </div>
                  </div>
               </div>
               </div>
               <?php  }  ?>
               <!--- fertility -->





               <?php 
                  $j++;
                  }
                  ?>
            </div>
            <div class="col-md-1 btn-box text-right">
              <div class="prescription_btns">
                <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
                <a class="btn-save"  href="<?php echo base_url('gynecology/opdprescriptionhistory/lists/'.$form_data['patient_id']); ?>" style="padding:9px;" target="_blank"><i class="fa fa-history"></i> History</a>
                <button class="btn-save" type="button" name="" data-dismiss="modal" onclick="window.location.href='<?php echo base_url('opd'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
              </div>
            </div>
      <!-- row -->
      </form>
      </section> <!-- section close -->
      <?php
         $this->load->view('include/footer');
         ?>
      </div><!-- container-fluid -->
      <script type="text/javascript">
         $(".addprescriptionrow").click(function(){
         
           var i=$('#prescription_name_table tr').length;
                 $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
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
            { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="w-10px  medicine_type_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class=" dosage_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {  ?>  <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-10px medicine-name duration_val'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-10px medicine-name frequency_val'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            { 
            ?>  <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-10px medicine-name advice_val'+i+'"></td>                        <?php 
            } 
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               m=0
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                   $('#brand'+i).val(names[3]);
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                 $(".medicine_type_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".medicine_type_val"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
         //for gynec
           function check_marriage_status(value) 
           {
             if(value=="No")
             {
               $("#marriage_columns").css("display","none");
               $('#married_life_unit').val('');
               $('#married_life_type option:selected').removeAttr("selected");
               $('#marriage_no').val('');
               $('#marriage_details').val('');
               $('#previous_delivery option:selected').removeAttr("selected");
               $('#delivery_type option:selected').removeAttr("selected");
               $('#delivery_details').val('');
             }
             else 
             {
               $("#marriage_columns").css("display","block");
             }
           }

          function check_previous_delivery(value) 
          {
            if(value=="No")
            {
               $("#check_previous_delivery").css("display","none");
               $('#delivery_type option:selected').removeAttr("selected");
               $('#delivery_details').val('');
            }
            else 
            {
               $("#check_previous_delivery").css("display","block");
            }
          }

          function check_br_discharge(value) 
          {
            if(value=="No")
            {
               $("#check_br_discharge").css("display","none");
               $('#side option:selected').removeAttr("selected");
            }
            else 
            {
               $("#check_br_discharge").css("display","block");
            }
          }

          function check_white_discharge(value) 
          {
            if(value=="No")
            {
               $("#check_white_discharge").css("display","none");
               $('#type option:selected').removeAttr("selected");
            }
            else 
            {
               $("#check_white_discharge").css("display","block");
            }
          }

          function check_dysmenorrhea(value) 
          {
            if(value=="No")
            {
               $("#check_dysmenorrhea").css("display","none");
               $('#dysmenorrhea_type option:selected').removeAttr("selected");
            }
            else 
            {
               $("#check_dysmenorrhea").css("display","block");
            }
          }

          function myFunction() 
          {
            var weight= $('#weight').val();
            var height= $('#height').val();
            var newheight= height/100;
            if(weight!='' && newheight!='')
            {

              var bmi=parseFloat(weight/(newheight*newheight)).toFixed(2);
            }
            else
            {
              var bmi='';
            }
            

            $('#bmi_calculate').val(bmi);

          }
         
         //for gynec get template data
         $('#template_list').change(function(){  
         
               var template_id = $(this).val();
               if(template_id=="" || template_id==undefined)
               {
                  var test = "";
                  load_patient_history_data(test);
                  load_patient_family_history_data(test);
                  load_patient_personal_history_data(test);
                  load_patient_menstrual_history_data(test);
                  load_patient_medical_history_data(test);
                  load_patient_obestetric_history_data(test);
                  load_disease_values(test);
                  load_complaints_values(test);
                  load_allergy_values(test);
                  load_general_examination_values(test);
                  load_clinical_examination_values(test);
                  load_investigation_values(test);
                  load_advice_values(test);
                  return false;
               }
               else
               {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/get_template_data/"+template_id, 
                    success: function(result)
                    {
                      
                      load_values(result);
                      load_patient_history_data(template_id);
                      load_patient_family_history_data(template_id);
                      load_patient_personal_history_data(template_id);
                      load_patient_menstrual_history_data(template_id);
                      load_patient_medical_history_data(template_id);
                      load_patient_obestetric_history_data(template_id);
                      load_medicine_values(template_id);
                      load_disease_values(template_id);
                      load_complaints_values(template_id);
                      load_allergy_values(template_id);
                      load_general_examination_values(template_id);
                      load_clinical_examination_values(template_id);
                      load_investigation_values(template_id);
                      load_advice_values(template_id);
                      load_patient_medicine_values(template_id);
                      
                    } 
                  }); 
               }
               
           });
         
          function load_disease_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_disease_values/"+0, 
                       success: function(result)
                       {
                          get_disease_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_disease_values/"+template_id, 
                       success: function(result)
                       {
                          //alert(result);
                          get_disease_data(result);
                       } 
                     });
                }
                 
             }
         
         
              function get_disease_data(result)
              {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;

               var m = 1;
               var rec_count = 1;
               $.each(obj, function (index, value) {
                rec_count++;
                
                arr +='<tr><input type="hidden" name="unique_id_patient_disease" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_disease booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].disease_value+'</td><td>'+obj[index].patient_disease_unit+' '+obj[index].patient_disease_type+'</td><td>'+obj[index].disease_description+'</td></tr>'
  
                 }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="5" align="center" class=" text-danger "><div class="text-center">Disease History not available.</div></td></tr>';
                }
               $("#row_id_patient_disease").val(m);
               $("#patient_disease_list tbody").html(arr);
             }
         
         
         function load_complaints_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_complaints_values/"+0, 
                       success: function(result)
                       {
                          get_complaints_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_complaints_values/"+template_id, 
                       success: function(result)
                       {
                          get_complaints_data(result);
                       } 
                     });
                }
                 
             }
         
         
              function get_complaints_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_complaint" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_complaints booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].complaint_value+'</td><td>'+obj[index].patient_complaint_unit+' '+obj[index].patient_complaint_type+'</td><td>'+obj[index].complaint_description+'</td></tr>'
         
                }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="5" align="center" class=" text-danger "><div class="text-center">Compaints not available.</div></td></tr>';
                }
               $("#row_id_patient_complaint").val(m);
               $("#patient_complaint_list tbody").html(arr);
             }
         
         
         function load_allergy_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_allergy_values/"+0, 
                       success: function(result)
                       {
                          get_allergy_data('');
                       } 
                     });
                }
                else
                {
                   $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_allergy_values/"+template_id, 
                     success: function(result)
                     {
                        //alert(result);
                        get_allergy_data(result);
                     } 
                   });
                }
                 
             }
         
         
             function get_allergy_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_allergy" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_allergy booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].patient_allergy_name+'</td><td>'+obj[index].patient_allergy_unit+' '+obj[index].patient_allergy_type+'</td><td>'+obj[index].allergy_description+'</td></tr>'
         
                }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="5" align="center" class=" text-danger "><div class="text-center">Allergy not available.</div></td></tr>';
                }
               $("#row_id_patient_allergy").val(m);
               $("#patient_allergy_list tbody").html(arr);
             }
         
         
             function load_general_examination_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_general_examination_values/"+0, 
                       success: function(result)
                       {
                          get_general_examination_data('');
                       } 
                     });
                }
                else
                {
                     $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_general_examination_values/"+template_id, 
                       success: function(result)
                       {
                          //alert(result);
                          get_general_examination_data(result);
                       } 
                     });
                }

                
             }
         
         
             function get_general_examination_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_general_examination" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_general_examination booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].patient_general_examination_name+'</td><td>'+obj[index].general_examination_description+'</td></tr>'
         
                }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="5" align="center" class=" text-danger "><div class="text-center">General Exam not available.</div></td></tr>';
                }
               $("#row_id_general_examination").val(m);
               $("#patient_general_examination_list tbody").html(arr);
             }
         
         
         
         
             function load_clinical_examination_values(template_id="")
             {
                  if(template_id=="")
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_clinical_examination_values/"+0, 
                         success: function(result)
                         {
                            get_clinical_examination_data('');
                         } 
                       });
                  }
                  else
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_clinical_examination_values/"+template_id, 
                         success: function(result)
                         {
                            //alert(result);
                            get_clinical_examination_data(result);
                         } 
                       });
                  }
                 
             }
         
         
             function get_clinical_examination_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_clinical_examination" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_clinical_examination booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].patient_clinical_examination_name+'</td><td>'+obj[index].clinical_examination_description+'</td></tr>'
         
                }); 
                if(arr=="")
                {
                  arr = '<tr><td colspan="5" align="center" class=" text-danger "><div class="text-center">Clinical Exam not available.</div></td></tr>';
                }
               $("#row_id_clinical_examination").val(m);
               $("#patient_clinical_examination_list tbody").html(arr);
             }
         
         
             function load_investigation_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_investigation_values/"+0, 
                       success: function(result)
                       {
                          get_investigation_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_investigation_values/"+template_id, 
                       success: function(result)
                       {
                          //alert(result);
                          get_investigation_data(result);
                       } 
                     });
                }

                 
             }
         
         
             function get_investigation_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_investigation" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_investigation booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].investigation_value+'</td><td>'+obj[index].std_value+'</td><td>'+obj[index].observed_value+'</td></tr>'
         
               }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="6" align="center" class=" text-danger "><div class="text-center">Investigation not available.</div></td></tr>';
                }
               $("#row_id_patient_investigation").val(m);
               $("#patient_investigation_list tbody").html(arr);
             }
         
             
             function load_advice_values(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_advice_values/"+0, 
                       success: function(result)
                       {
                          get_advice_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_advice_values/"+template_id, 
                     success: function(result)
                     {
                        //alert(result);
                        get_advice_data(result);
                     } 
                   });
                }
                 
             }
         
         
             function get_advice_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_patient_advice" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_advice booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].advice_value+'</td></tr>'
         
                });
                if(arr=="")
                {
                  arr = '<tr><td colspan="3" align="center" class=" text-danger "><div class="text-center">Advice not available.</div></td></tr>';
                } 
               $("#row_id_patient_advice").val(m);
               $("#patient_advice_list tbody").html(arr);
             }
         
         
         
             function load_patient_medicine_values(template_id)
             {
                $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/get_tabing_medicine_data/"+template_id, 
                 success: function(result)
                 {
                    //alert(result);
                    get_medicine_data(result);
                    //get_patient_medicine_data(result);
                 } 
               });
             }
         
         
             function get_patient_medicine_data(result)
             {
                var obj = JSON.parse(result);
                var pres = '';
                pres += '<tbody><tr><?php 
                $l=0;
                foreach ($prescription_medicine_tab_setting as $med_value) { ?>                            <td <?php  if($l=0){ ?> class="text-left" <?php } ?> ><?php if(!empty($med_value->setting_value)) { echo $med_value->setting_value; } else { echo $med_value->var_title; } ?></td>                                <?php 
                $l++; 
                 }
                 ?><td width="80"><a href="javascript:void(0)" class="btn-w-60" onclick="get_patient_medicine_bytemp()">Add</a></td></tr>';
                   i = 1;
                   $.each(obj, function (index, value) {       
                      pres += '<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                { 
                if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                {
                ?><td><input type="text" name="prescription_patient['+i+'][medicine_name]" class="w-100px medicine_val_patient'+i+'" value="'+obj[index].medicine_name+'"><input type="hidden" name="medicine_id[]" id="medicine_id_patient'+i+'" value="'+obj[index].medicine_id+'"></td> <?php 
                }
                if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                {
                ?><td><input type="text" name="prescription_patient['+i+'][medicine_brand]" id="brand_patient'+i+'"  class="w-100px" value="'+obj[index].medicine_brand+'" ></td>                       <?php 
                } 
                
                if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                {
                ?><td><input type="text" id="salt_patient'+i+'"  name="prescription_patient['+i+'][medicine_salt]" class="w-100px" value="'+obj[index].medicine_salt+'" ></td>                        <?php 
                } 
                if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                { ?> <td><input type="text" id="type_patient'+i+'"  name="prescription_patient['+i+'][medicine_type]" class="w-100px input-small medicine_type_val_patient" value="'+obj[index].medicine_type+'"></td>                        <?php 
                }
                if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                { ?> <td><input type="text" name="prescription_patient['+i+'][medicine_dose]" class="w-100px input-small dosage_val_patient'+i+'" value="'+obj[index].medicine_dose+'"></td>                        <?php 
                } 
                if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                {  ?> <td><input type="text" name="prescription_patient['+i+'][medicine_duration]" class="w-100px medicine-name duration_val_patient'+i+'" value="'+obj[index].medicine_duration+'"></td>                    <?php 
                }
                if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                {
                ?> <td><input type="text" name="prescription_patient['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val_patient'+i+'" value="'+obj[index].medicine_frequency+'"></td>          <?php 
                } 
                if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                { 
                ?> <td><input type="text" name="prescription_patient['+i+'][medicine_advice]" class="w-100px medicine-name advice_val_patient'+i+'" value="'+obj[index].medicine_advice+'"></td>                          <?php } 
                } ?> <td><a href="javascript:void(0);" class="btn-w-60 remove_patient_prescription_row_patient">Delete</a></td></tr>';
                i++;
                 }); 
               pres += '</tbody>'; 
               $("#prescription_name_table_patient tbody").replaceWith(pres);
             }
         
         function get_patient_medicine_bytemp()
         {
           //alert();
         
           var m=1;
           var i=$('#prescription_name_table_patient tr').length;
                 $("#prescription_name_table_patient").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            ?><td><input type="text" name="prescription_patient['+i+'][medicine_name]" class="w-100px medicine_val_patient'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id_patient'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
            ?>   <td><input type="text" name="prescription_patient['+i+'][medicine_brand]" id="brand_patient'+i+'"  class="w-100px" ></td>                        <?php 
            } 
            
            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
            ?>  <td><input type="text" id="salt_patient'+i+'"  name="prescription_patient['+i+'][medicine_salt]" class="w-100px"  ></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            { ?>  <td><input type="text" id="type_patient'+i+'"  name="prescription_patient['+i+'][medicine_type]" class="w-100px input-small medicine_type_val_patient"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            { ?> <td><input type="text" name="prescription_patient['+i+'][medicine_dose]" class="w-100px input-small dosage_val_patient'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {  ?>  <td><input type="text" name="prescription_patient['+i+'][medicine_duration]" class="w-100px medicine-name duration_val_patient'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            ?> <td><input type="text" name="prescription_patient['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val_patient'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            { 
            ?>  <td><input type="text" name="prescription_patient['+i+'][medicine_advice]" class="w-100px medicine-name advice_val_patient'+i+'"></td>                        <?php 
            } 
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_patient_prescription_row_patient">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
                 data: {name_startsWith:request.term},
              /* data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                  row_num : row
               },*/
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
         
                   $('.medicine_val_patient'+i).val(names[0]);
                   $('#type_patient'+i).val(names[1]);
                   $('#brand_patient'+i).val(names[3]);
                   $('#salt_patient'+i).val(names[2]);
                 //$(".medicine_val").val(ui.item.value);
                 return false;
             }
         
             $(".medicine_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".dosage_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".dosage_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".duration_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".duration_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 1,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".frequency_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".frequency_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         
         
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".advice_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".advice_val_patient"+i).autocomplete({
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
         
          $("#prescription_name_table_patient").on('click','.remove_patient_prescription_row_patient',function(){
                 $(this).parent().parent().remove();
             });
           
         }
         
         
             function load_medicine_values(template_id)
             {
                $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/get_medicine_data/"+template_id, 
                 success: function(result)
                 {
                    //alert(result);
                    //get_medicine_data(result);
                    get_patient_medicine_data(result);
                 } 
               });
             }
         
         
         
            function get_medicine_data(result)
            {
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
              ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px medicine_val'+i+'" value="'+obj[index].medicine_name+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'" value="'+obj[index].medicine_id+'"></td> <?php 
              }
              if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
              {
              ?> <td><input type="text" name="prescription['+i+'][medicine_brand]" id="brand'+i+'"  class="w-100px" value="'+obj[index].medicine_brand+'" ></td>                      <?php 
              } 
              
              if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
              {
              ?><td><input type="text" id="salt'+i+'"  name="prescription['+i+'][medicine_salt]" class="w-100px" value="'+obj[index].medicine_salt+'" ></td>                       <?php 
              } 
              if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
              { ?> <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="w-100px input-small medicine_type_val'+i+'" value="'+obj[index].medicine_type+'"></td>                       <?php 
              }
              if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
              { ?> <td><input type="text" name="prescription['+i+'][medicine_dose]" class="w-100px input-small dosage_val'+i+'" value="'+obj[index].medicine_dose+'"></td>                                         <?php 
              } 
              if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
              {  ?> <td><input type="text" name="prescription['+i+'][medicine_duration]" class="w-100px medicine-name duration_val'+i+'" value="'+obj[index].medicine_duration+'"></td>                         <?php 
              }
              if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
              {
              ?> <td><input type="text" name="prescription['+i+'][medicine_frequency]" class="w-100px medicine-name frequency_val'+i+'" value="'+obj[index].medicine_frequency+'"></td>                                              <?php 
              } 
              if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
              { 
              ?> <td><input type="text" name="prescription['+i+'][medicine_advice]" class="w-100px medicine-name advice_val'+i+'" value="'+obj[index].medicine_advice+'"></td>                          <?php } 
              } ?> <td><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';
                i++;
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
            ?><td><input type="text" name="prescription['+i+'][medicine_name]" class="w-100px medicine_val'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id'+i+'"></td>                        <?php 
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
            { ?>  <td><input type="text" id="type'+i+'"  name="prescription['+i+'][medicine_type]" class="w-100px input-small medicine_type_val'+i+'"></td>                        <?php 
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
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                   $('#brand'+i).val(names[3]);
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                 $(".medicine_type_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".medicine_type_val"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });


         $(function () {
            /* var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
             $.ajax({
             url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
             dataType: "json",
             method: 'post',
           data: {
              name_startsWith: request.term,
              type: 'country_table',
             
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".frequency_val"+i).val(ui.item.value);
                 return false;
             }
         
             $(".frequency_val"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                var appointment_date = obj.appointment_date;
                $('#next_appointment_date').val(obj.appointment_date);
            }
         
         
             function load_patient_history_data(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_history_data/"+0, 
                       success: function(result)
                       {
                          get_patient_history_data('');
                       } 
                    });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_history_data/"+template_id, 
                     success: function(result)
                     {
                        get_patient_history_data(result);
                     } 
                    });
                }
                 
             }
         
         
             function get_patient_history_data(result)
             {
                if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               
               var arr = '';
               var i=obj.length;
         
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr name="patient_history_row" id="'+index+'"><input type="hidden" name="unique_id" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_pat booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].marriage_status+'</td><td>'+obj[index].married_life_unit+' '+obj[index].married_life_type+'</td><td>'+obj[index].marriage_no+'</td><td>'+obj[index].marriage_details+'</td><td>'+obj[index].previous_delivery+'</td><td> '+obj[index].delivery_type+'</td><td> '+obj[index].delivery_details+'</tr>'
         
                }); 
               
                if(arr=="")
                {
                  arr = '<tr><td colspan="9" align="center" class=" text-danger "><div class="text-center">Patient History not available.</div></td></tr>';
                }
                
               $("#row_id").val(m);
               $("#patient_history_list tbody").html(arr);
             }
         
           //  load_patient_family_history_data
             function load_patient_family_history_data(template_id="")
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_family_history_data/"+0, 
                       success: function(result)
                       {
                          get_patient_family_history_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_family_history_data/"+template_id, 
                       success: function(result)
                       {
                          get_patient_family_history_data(result);
                       } 
                     });
                }

                 
             }
         
         
             function get_patient_family_history_data(result)
             {
                if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
         
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_family_history" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_family_history booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].relation+'</td><td>'+obj[index].disease+'</td><td>'+obj[index].family_description+'</td><td>'+obj[index].family_duration_unit+' '+obj[index].family_duration_type+'</td></tr>'
                }); 
               if(arr=="")
                {
                  arr = '<tr><td colspan="6" align="center" class=" text-danger "><div class="text-center">Patient Family History not available.</div></td></tr>';
                }
               $("#row_id_family_history").val(m);
               $("#patient_family_history_list tbody").html(arr);  
             }
         
         
              //  load_patient_personal_history_data
             function load_patient_personal_history_data(template_id='')
             {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_personal_history_data/"+0, 
                       success: function(result)
                       {
                          get_patient_personal_history_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_personal_history_data/"+template_id, 
                       success: function(result)
                       {
                          get_patient_personal_history_data(result);
                       } 
                     });
                }
                 
             }
         
         
             function get_patient_personal_history_data(result)
             {
               if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
               var arr = '';
               var i=obj.length;
               var m = 1;
               $.each(obj, function (index, value) {
                arr +='<tr><input type="hidden" name="unique_id_personal_history" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_personal_history booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].br_discharge+'</td><td>'+obj[index].side+'</td><td>'+obj[index].hirsutism+'</td><td>'+obj[index].white_discharge+'</td><td>'+obj[index].type+'</td><td>'+obj[index].frequency_personal+'</td><td>'+obj[index].dyspareunia+'</td><td>'+obj[index].personal_details+'</td></tr>'
         
                }); 

               if(arr=="")
                {
                  arr = '<tr><td colspan="9" align="center" class=" text-danger "><div class="text-center">Patient Personal History not available.</div></td></tr>';
                }
               $("#row_id_personal_history").val(m);
               $("#patient_personal_history_list tbody").html(arr);
             }
         
         
               //  load_patient_personal_history_data
              function load_patient_menstrual_history_data(template_id='')
              {
                  if(template_id=="")
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_menstrual_history_data/"+0, 
                         success: function(result)
                         {
                            get_patient_menstrual_history_data('');
                         } 
                       });
                  }
                  else
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_menstrual_history_data/"+template_id, 
                         success: function(result)
                         {
                            //alert(result);
                            get_patient_menstrual_history_data(result);
                         } 
                      });
                  }
                   
              }


         
         
              function get_patient_menstrual_history_data(result)
              {
                 if(result=="")
                  {
                    var obj = "";
                  }
                  else
                  {
                    var obj = JSON.parse(result);
                  }
                 var arr = '';
                 var i=obj.length;
                 var m = 1;
                 $.each(obj, function (index, value) {
                  arr +='<tr><input type="hidden" name="unique_id_menstrual_history" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_menstrual_history booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].previous_cycle+'</td><td>'+obj[index].prev_cycle_type+'</td><td>'+obj[index].present_cycle+'</td><td>'+obj[index].present_cycle_type+'</td><td>'+obj[index].cycle_details+'</td><td>'+obj[index].lmp_date+'</td><td>'+obj[index].dysmenorrhea+'</td><td>'+obj[index].dysmenorrhea_type+'</td></tr>'
           
                  }); 

                  if(arr=="")
                  {
                    arr = '<tr><td colspan="9" align="center" class=" text-danger "><div class="text-center">Patient Menstrual History not available.</div></td></tr>';
                  }

                 $("#row_id_menstrual_history").val(m);
                 $("#patient_menstrual_history_list tbody").html(arr);
              }
         
              //  load_patient_personal_history_data
              function load_patient_medical_history_data(template_id="")
              {
                  if(template_id=="")
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_medical_history_data/"+0, 
                         success: function(result)
                         {
                            get_patient_medical_history_data('');
                         } 
                       });
                  }
                  else
                  {
                      $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_medical_history_data/"+template_id, 
                         success: function(result)
                         {
                            get_patient_medical_history_data(result);
                         } 
                      });
                  }
                 
              }
         
         
              function get_patient_medical_history_data(result)
              {
                 if(result=="")
                  {
                    var obj = "";
                  }
                  else
                  {
                    var obj = JSON.parse(result);
                  }
                 var arr = '';
                 var i=obj.length;
                 var m = 1;
                 $.each(obj, function (index, value) {
                  arr +='<tr><input type="hidden" name="unique_id_medical_history" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_medical_history booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].tb+'</td><td>'+obj[index].tb_rx+'</td><td>'+obj[index].dm+'</td><td>'+obj[index].dm_years+'</td><td>'+obj[index].dm_rx+'</td><td>'+obj[index].ht+'</td><td>'+obj[index].medical_details+'</td><td>'+obj[index].medical_others+'</td></tr>'
           
                  }); 

                 if(arr=="")
                  {
                    arr = '<tr><td colspan="10" align="center" class=" text-danger "><div class="text-center">Patient Medical History not available.</div></td></tr>';
                  }
                 $("#row_id_medical_history").val(m);
                 $("#patient_medical_history_list tbody").html(arr);
               
              
              }
         
              //  load_patient_Obestetric_history_data
              function load_patient_obestetric_history_data(template_id="")
              {
                if(template_id=="")
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_obestetric_history_data/"+0, 
                       success: function(result)
                       {
                          get_patient_obestetric_history_data('');
                       } 
                     });
                }
                else
                {
                    $.ajax({url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_patient_obestetric_history_data/"+template_id, 
                       success: function(result)
                       {
                          get_patient_obestetric_history_data(result);
                       } 
                     });
                }
                 
               }
         
         
            function get_patient_obestetric_history_data(result)
            {
              if(result=="")
                {
                  var obj = "";
                }
                else
                {
                  var obj = JSON.parse(result);
                }
              var arr = '';
              var i=obj.length;
              var m = 1;
              $.each(obj, function (index, value) {
              arr +='<tr><input type="hidden" name="unique_id_obestetric_history" value="'+index+'"><td><input type="checkbox"name="id[]" class="part_checkbox_obestetric_history booked_checkbox" value="'+index+'"></td><td>'+ m++  +'</td><td>'+obj[index].obestetric_g+'</td><td>'+obj[index].obestetric_p+'</td><td>'+obj[index].obestetric_l+'</td><td>'+obj[index].obestetric_mtp+'</td></tr>'

              }); 
              if(arr=="")
                {
                  arr = '<tr><td colspan="9" align="center" class=" text-danger "><div class="text-center">Patient Obestetric History not available.</div></td></tr>';
                }

              $("#row_id_obestetric_history").val(m);
              $("#patient_obestetric_history_list tbody").html(arr);


            }
         
         //for date time next appointment
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
             autoclose: true, 
           });
           
           $('.datepicker4').datepicker({
             format: 'dd-mm-yyyy',
             autoclose: true, 
           });
           
           
           /*$('.datepicker2').datepicker({
             format: 'dd-mm-yyyy',
             autoclose: true, 
           }).on('changeDate', function(e) {
              alert(e.format());
            });*/
            
            
            
            $('.datepicker4').datepicker({
             format: 'dd-mm-yyyy',
             autoclose: true, 
            }).on('changeDate', function(e) {
                var antenatal_care_period = $('#lmpss').val();
                var days=$('#days').val();
                
                $.ajax({
                        url: "<?php echo base_url('gynecology/gynecology_prescription/date_add_days/'); ?>", 
                        type: 'POST',
                        data: 'date='+antenatal_care_period+'&days=280',
                        success: function(result)
                        {
                          $("#edd").val(result);
                          get_pog_date();
                        } 
                      });
                //alert($('#antenatal_care_period').val());
            });
            
            
function get_pog_date()
{
   var lmpss = $('#confirm_delivery_date').val();
   var edd = $('#edd').val();
   
   //var days=+($('#days').val());
   
    
   $.ajax({
            url: "<?php echo base_url('gynecology/gynecology_prescription/get_gestational_days_date/'); ?>", 
            type: 'POST',
            data: 'confirm_delivery='+lmpss+'&antenatal_expected_date='+edd,
            success: function(result)
            {
              $("#pog").val(result);
            } 
          });
}


            $('.datepicker2').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
            }).on('changeDate', function(e) {
                var antenatal_care_period = $('#antenatal_care_period').val();
                $.ajax({
                        url: "<?php echo base_url('gynecology/gynecology_prescription/date_add_days/'); ?>", 
                        type: 'POST',
                        data: 'date='+antenatal_care_period+'&days=280',
                        success: function(result)
                        {
                          $("#antenatal_expected_date").val(result);
                          get_gestational_date();
                        } 
                      });
                //alert($('#antenatal_care_period').val());
            });
            
            
            
            
            
            function get_gestational_date()
            {
               var confirm_delivery = $('#confirm_delivery_date').val();
               var antenatal_expected_date = $('#antenatal_expected_date').val();
               $.ajax({
                        url: "<?php echo base_url('gynecology/gynecology_prescription/get_gestational_date/'); ?>", 
                        type: 'POST',
                        data: 'confirm_delivery='+confirm_delivery+'&antenatal_expected_date='+antenatal_expected_date,
                        success: function(result)
                        {
                          $("#antenatal_first_date").val(result);
                        } 
                      });
            }
            
            
           
           
         
             
         
         
          // tab link
            function tab_links(vals)
            {
              $('.inner_tab_box').removeClass('in');
              $('.inner_tab_box').removeClass('active');  
              $('#'+vals).addClass('in');
              $('#'+vals).addClass('active');
            }
         
             
         
            $(document).ready(function(){
               $('#days').val(280);
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
         
         $(".addprescriptionrow_patient").click(function(){
         
           var i=$('#prescription_name_table_patient tr').length;
                 $("#prescription_name_table_patient").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
            { 
            if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
            {
            ?><td><input type="text" name="prescription_patient['+i+'][medicine_name]" class=" medicine_val_patient'+i+'"><input type="hidden" name="medicine_id[]" id="medicine_id_patient'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
            {
            ?>   <td><input type="text" name="prescription_patient['+i+'][medicine_brand]" id="brand_patient'+i+'"  class="" ></td>                        <?php 
            } 
            
            if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
            {
            ?>  <td><input type="text" id="salt_patient'+i+'"  name="prescription_patient['+i+'][medicine_salt]" class=""  ></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
            { ?>  <td><input type="text" id="type_patient'+i+'"  name="prescription_patient['+i+'][medicine_type]" class=" medicine_type_val_patient'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
            { ?> <td><input type="text" name="prescription_patient['+i+'][medicine_dose]" class=" dosage_val_patient'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
            {  ?>  <td><input type="text" name="prescription_patient['+i+'][medicine_duration]" class=" medicine-name duration_val_patient'+i+'"></td>                        <?php 
            }
            if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
            {
            ?> <td><input type="text" name="prescription_patient['+i+'][medicine_frequency]" class=" medicine-name frequency_val_patient'+i+'"></td>                        <?php 
            } 
            if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
            { 
            ?>  <td><input type="text" name="prescription_patient['+i+'][medicine_advice]" class=" medicine-name advice_val_patient'+i+'"></td>                        <?php 
            } 
            } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
                 /* script start */
         $(function () 
         {
               m=0
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
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
         
                   $('.medicine_val_patient'+i).val(names[0]);
                   $('#type_patient'+i).val(names[1]);
                   $('#brand_patient'+i).val(names[3]);
                   $('#salt_patient'+i).val(names[2]);
                 //$(".medicine_val").val(ui.item.value);
                 return false;
             }
         
             $(".medicine_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });

         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                 $(".medicine_type_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".medicine_type_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".dosage_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".dosage_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".duration_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".duration_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 1,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         $(function () {
            /* var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".frequency_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".frequency_val_patient"+i).autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".advice_val_patient"+i).val(ui.item.value);
                 return false;
             }
         
             $(".advice_val_patient"+i).autocomplete({
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
             $(':input[id=form_submit]').prop('disabled', true);
                $('#gynec_prescription_form').submit();
           })
         
         
         
         $(function () 
         {
             var i=$('#prescription_name_table tr').length;
               var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                   $('.medicine_val_patient').val(names[0]);
                   $('#type_patient0').val(names[1]);
                   $('#brand_patient0').val(names[3]);
                   $('#salt_patient0').val(names[2]);
                 //$(".medicine_val").val(ui.item.value);
                 return false;
             }
         
             $(".medicine_val_patient").autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".dosage_val_patient").val(ui.item.value);
                 return false;
             }
         
             $(".dosage_val_patient").autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".duration_val_patient").val(ui.item.value);
                 return false;
             }
         
             $(".duration_val_patient").autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 1,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".frequency_val_patient").val(ui.item.value);
                 return false;
             }
         
             $(".frequency_val_patient").autocomplete({
                 source: getData,
                 select: selectItem,
                 minLength: 2,
                 change: function() {
                     //$("#test_val").val("").css("display", 2);
                 }
             });
             });
         
         $(function () {
             /*var getData = function (request, response) { 
                 $.getJSON(
                     "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                     function (data) {
                         response(data);
                     });
             };*/
             
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                 $(".advice_val_patient").val(ui.item.value);
                 return false;
             }
         
             $(".advice_val_patient").autocomplete({
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

        ////Arvind september 

        function add_right_ovary ()
        {
             var rec_count = $("#row_right_ovary_id").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             } 
             var right_follic_size= $('#right_follic_size option:selected').text(); 
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_right_ovary_list/", 
                     dataType: "json",
                     data:'right_follic_size='+right_follic_size+'&rec_count='+rec_count+'&unique_id='+rec_count,
                     success: function(result)
                     {
                       $('#patient_right_ovary_list').html(result.html_data);
                       //$('#right_folli_date').val('');
                       //$('#right_folli_day').val('');
                       //$('#right_folli_protocol').val('');
                       //$('#right_folli_pfsh').val('');
                       //$('#right_folli_recfsh').val('');
                       //$('#right_folli_hmg').val('');
                       //$('#right_folli_hp_hmg').val('');
                       //$('#right_folli_agonist').val('');
                       //$('#right_folli_antiagonist').val('');
                       //$('#right_folli_trigger').val('');
                       $('#right_follic_size option:selected').removeAttr("selected");
                       $("#right_ovary_count").val(parseInt(rec_count)+parseInt(1));
                     } 
                   }); 
             $('#row_right_ovary_id').val(parseInt(rec_count)+parseInt(1));
             
           }

        function add_left_ovary ()
        {
             var rec_count = $("#row_left_ovary_id").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var left_folli_date = $('#left_folli_date').val();
             var left_folli_day = $('#left_folli_day').val();
             var left_folli_protocol = $('#left_folli_protocol').val();
             var left_folli_pfsh = $('#left_folli_pfsh').val();
             var left_folli_recfsh = $('#left_folli_recfsh').val();
             var left_folli_hmg = $('#left_folli_hmg').val();
             var left_folli_hp_hmg = $('#left_folli_hp_hmg').val();
             var left_folli_agonist=$('#left_folli_agonist').val();
             var left_folli_antiagonist=$('#left_folli_antiagonist').val();
             var left_folli_trigger=$('#left_folli_trigger').val();
             var left_follic_size= $('#left_follic_size option:selected').text(); 

             var endometriumothers=$('#endometriumothers').val();
             var e2=$('#e2').val();
             var p4=$('#p4').val();
             var risk=$('#risk').val();
             var others=$('#others').val();

             
            $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_left_ovary_list/", 
                     dataType: "json",
                     data:'left_folli_date='+left_folli_date+'&left_folli_day='+left_folli_day+'&left_folli_protocol='+left_folli_protocol+'&left_folli_pfsh='+left_folli_pfsh+'&left_folli_recfsh='+left_folli_recfsh+'&left_folli_hmg='+left_folli_hmg+'&left_folli_hp_hmg='+left_folli_hp_hmg+'&left_folli_agonist='+left_folli_agonist+'&left_folli_antiagonist='+left_folli_antiagonist+'&left_folli_trigger='+left_folli_trigger+'&left_follic_size='+left_follic_size+'&endometriumothers='+endometriumothers+'&e2='+e2+'&p4='+p4+'&risk='+others+'&others='+risk+'&rec_count='+rec_count+'&unique_id='+rec_count,
                     success: function(result)
                     {
                       $('#patient_left_ovary_list').html(result.html_data);
                       //$('#left_folli_date').val('');
                       //$('#left_folli_day').val('');
                       //$('#left_folli_protocol').val('');
                       //$('#left_folli_pfsh').val('');
                       //$('#left_folli_recfsh').val('');
                       //$('#left_folli_hmg').val('');
                       //$('#left_folli_hp_hmg').val('');
                       //$('#left_folli_agonist').val('');
                       //$('#left_folli_antiagonist').val('');
                       //$('#left_folli_trigger').val('');
                       //$('#endometriumothers').val('');
                       //$('#e2').val('');
                       //$('#p4').val('');
                       //$('#risk').val('');
                       //$('#others').val('');
                       $('#left_follic_size option:selected').removeAttr("selected");
                       $("#left_ovary_count").val(parseInt(rec_count)+parseInt(1));
                     } 
                   }); 
             $('#row_left_ovary_id').val(parseInt(rec_count)+parseInt(1));
             
           }
         //shalini for new gynic
         //add for patient history
           function add_patient_history_listdata()
           {
             var rec_count = $("#row_id").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var marriage_status = $('#marriage_status option:selected').text();
             var married_life_unit = $('#married_life_unit').val();
             var married_life_type = $('#married_life_type option:selected').text();
             var marriage_no = $('#marriage_no').val();
             var marriage_details = $('#marriage_details').val();
             var previous_delivery = $('#previous_delivery option:selected').text();
             var delivery_type = $('#delivery_type option:selected').text();
             var delivery_details=$('#delivery_details').val();
              
             //alert(unique_id);return false; 
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_history_list/", 
                     dataType: "json",
                     data:'marriage_status='+marriage_status+'&married_life_unit='+married_life_unit+'&married_life_type='+married_life_type+'&marriage_no='+marriage_no+'&marriage_details='+marriage_details+'&previous_delivery='+previous_delivery+'&delivery_type='+delivery_type+'&delivery_details='+delivery_details+'&rec_count='+rec_count+'&unique_id='+rec_count,
                     success: function(result)
                     {
                       $('#patient_history_list').html(result.html_data);
                       $('#married_life_unit').val('');
                       $('#married_life_type option:selected').removeAttr("selected");
                       $('#marriage_no').val('');
                       $('#marriage_details').val('');
                       $('#previous_delivery option:selected').removeAttr("selected");
                       $('#delivery_type option:selected').removeAttr("selected");
                       $('#delivery_details').val('');
                       $("#patient_history_count").val(parseInt(rec_count)+parseInt(1));
                     } 
                   }); 
             $('#row_id').val(parseInt(rec_count)+parseInt(1));
             
           }
         
         
           function add_patient_family_history_listdata()
           {
             var rec_count = $("#row_id_family_history").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var relation = $('#relation_id option:selected').text();
             var relation_id = $('#relation_id').val();
             var disease = $('#disease_id option:selected').text();
             var disease_id = $('#disease_id').val();
             var family_description = $('#family_description').val();
             var family_duration_unit = $('#family_duration_unit').val();
             var family_duration_type = $('#family_duration_type option:selected').text();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_family_history_list/", 
                     dataType: "json",
                     data:'relation='+relation+'&disease='+disease+'&family_description='+family_description+'&family_duration_unit='+family_duration_unit+'&family_duration_type='+family_duration_type+'&relation_id='+relation_id+'&disease_id='+disease_id+'&unique_id_family_history='+rec_count,
                     success: function(result)
                     {
                       $('#patient_family_history_list').html(result.html_data);
                       $('#disease_id option:selected').removeAttr("selected");
                       $('#relation_id option:selected').removeAttr("selected");
                       $('#family_description').val('');
                       $('#family_duration_unit').val('');
                       $('#family_duration_type option:selected').removeAttr("selected");
                     } 
                   }); 
             $('#row_id_family_history').val(parseInt(rec_count)+parseInt(1));
             
           }
         
           function add_patient_personal_history_listdata()
           {
             var rec_count = $("#row_id_personal_history").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var br_discharge = $('#br_discharge option:selected').text();
             var side = $('#side option:selected').text();
             var hirsutism = $('#hirsutism option:selected').text();
             var white_discharge = $('#white_discharge option:selected').text();
             var type = $('#type option:selected').text();
             var dyspareunia = $('#dyspareunia option:selected').text();
             var personal_details = $('#personal_details').val();
             var frequency_personal = $('#frequency_personal').val();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_personal_history_list/", 
                     dataType: "json",
                     data:'br_discharge='+br_discharge+'&side='+side+'&hirsutism='+hirsutism+'&white_discharge='+white_discharge+'&type='+type+'&frequency_personal='+frequency_personal+'&dyspareunia='+dyspareunia+'&personal_details='+personal_details+'&unique_id_personal_history='+rec_count,
                     success: function(result)
                     {
                       $('#patient_personal_history_list').html(result.html_data);
                       $('#br_discharge option:selected').removeAttr("selected");
                       $('#side option:selected').removeAttr("selected");
                       $('#hirsutism option:selected').removeAttr("selected");
                       $('#white_discharge option:selected').removeAttr("selected");
                       $('#type option:selected').removeAttr("selected");
                       $('#dyspareunia option:selected').removeAttr("selected");
                       $('#personal_details').val('');
                       $('#frequency_personal').val('');
                     } 
                   }); 
             $('#row_id_personal_history').val(parseInt(rec_count)+parseInt(1));
           }
         
           function add_patient_menstrual_history_listdata()
           {
             var rec_count = $("#row_id_menstrual_history").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var previous_cycle = $('#previous_cycle option:selected').text();
             var prev_cycle_type = $('#prev_cycle_type option:selected').text();
             var present_cycle = $('#present_cycle option:selected').text();
             var present_cycle_type = $('#present_cycle_type option:selected').text();
             var lmp_date = $("#lmp_date").val();
             var dysmenorrhea = $('#dysmenorrhea option:selected').text();
             var dysmenorrhea_type = $('#dysmenorrhea_type option:selected').text();
             var cycle_details = $('#cycle_details').val();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_menstrual_history_list/", 
                     dataType: "json",
                     data:'previous_cycle='+previous_cycle+'&prev_cycle_type='+prev_cycle_type+'&present_cycle='+present_cycle+'&present_cycle_type='+present_cycle_type+'&lmp_date='+lmp_date+'&dysmenorrhea='+dysmenorrhea+'&dysmenorrhea_type='+dysmenorrhea_type+'&cycle_details='+cycle_details+'&unique_id_menstrual_history='+rec_count,
                     success: function(result)
                     {
                       $('#patient_menstrual_history_list').html(result.html_data);
                       $('#previous_cycle option:selected').removeAttr("selected");
                       $('#prev_cycle_type option:selected').removeAttr("selected");
                       $('#present_cycle option:selected').removeAttr("selected");
                       $('#present_cycle_type option:selected').removeAttr("selected");
                       $('#dysmenorrhea option:selected').removeAttr("selected");
                       $('#dysmenorrhea_type option:selected').removeAttr("selected");
                       $('#lmp_date').val('');
                       $('#cycle_details').val('');
                     } 
                   }); 
             $('#row_id_menstrual_history').val(parseInt(rec_count)+parseInt(1));
           }
         
           function add_patient_medical_history_listdata()
           {
             var rec_count = $("#row_id_medical_history").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var tb = $('#tb option:selected').text();
             var tb_rx = $("#tb_rx").val();
             var dm = $('#dm option:selected').text();
             var dm_years = $("#dm_years").val();
             var dm_rx = $("#dm_rx").val();
             var ht = $('#ht option:selected').text();
             var medical_details = $("#medical_details").val();
             var medical_others = $("#medical_others").val();
         
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_medical_history_list/", 
                     dataType: "json",
                     data:'tb='+tb+'&tb_rx='+tb_rx+'&dm='+dm+'&dm_years='+dm_years+'&dm_rx='+dm_rx+'&ht='+ht+'&medical_details='+medical_details+'&medical_others='+medical_others+'&unique_id_medical_history='+rec_count,
                     success: function(result)
                     {
                       $('#patient_medical_history_list').html(result.html_data);
                       $('#tb option:selected').removeAttr("selected");
                       $('#tb_rx').val('');
                       $('#dm option:selected').removeAttr("selected");
                       $('#dm_years').val('');
                       $('#dm_rx').val('');
                       $('#ht option:selected').removeAttr("selected");
                       $('#medical_details').val('');
                       $('#medical_others').val('');
                     } 
                   }); 
             $('#row_id_medical_history').val(parseInt(rec_count)+parseInt(1));
           }
         
           function add_patient_obestetric_history_listdata()
           {
             var rec_count = $("#row_id_obestetric_history").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var obestetric_g = $("#obestetric_g").val();
             var obestetric_p = $("#obestetric_p").val();
             var obestetric_l = $("#obestetric_l").val();
             var obestetric_mtp = $("#obestetric_mtp").val();
         
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_obestetric_history_list/", 
                     dataType: "json",
                     data:'obestetric_g='+obestetric_g+'&obestetric_p='+obestetric_p+'&obestetric_l='+obestetric_l+'&obestetric_mtp='+obestetric_mtp+'&unique_id_obestetric_history='+rec_count,
                     success: function(result)
                     {
                       $('#patient_obestetric_history_list').html(result.html_data);
                       $('#obestetric_g').val('');
                       $('#obestetric_p').val('');
                       $('#obestetric_l').val('');
                       $('#obestetric_mtp').val('');
                     } 
                   });
             $('#row_id_obestetric_history').val(parseInt(rec_count)+parseInt(1)); 
           }
         
         
           function add_patient_disease_listdata()
           {
             var rec_count = $("#row_id_patient_disease").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_disease_id = $('#patient_disease_id').val();
             var disease_value = $('#patient_disease_id option:selected').text();
             var patient_disease_unit = $('#patient_disease_unit').val();
             var patient_disease_type = $('#patient_disease_type option:selected').text();
             var disease_description = $('#disease_description').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_disease_list/", 
                     dataType: "json",
                     data: 'patient_disease_id='+patient_disease_id+'&disease_value='+disease_value+'&patient_disease_unit='+patient_disease_unit+'&patient_disease_type='+patient_disease_type+'&disease_description='+disease_description+'&unique_id_patient_disease='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_disease_list').html(result.html_data);  
                       $('#patient_disease_id option:selected').removeAttr("selected");
                       $('#patient_disease_unit').val('');
                       $('#patient_disease_type option:selected').removeAttr("selected");
                       $('#disease_description').val('');
                     } 
                   });
             $('#row_id_patient_disease').val(parseInt(rec_count)+parseInt(1)); 
           }
         
           function add_patient_complaint_listdata()
           {
             var rec_count = $("#row_id_patient_complaint").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_complaint_id = $('#patient_complaint_id').val();
             var complaint_value = $('#patient_complaint_id option:selected').text();
             var patient_complaint_unit = $('#patient_complaint_unit').val();
             var patient_complaint_type = $('#patient_complaint_type option:selected').text();
             //alert(patient_complaint_type);return false;
             var complaint_description = $('#complaint_description').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_complaint_list/", 
                     dataType: "json",
                     data: 'patient_complaint_id='+patient_complaint_id+'&complaint_value='+complaint_value+'&patient_complaint_unit='+patient_complaint_unit+'&patient_complaint_type='+patient_complaint_type+'&complaint_description='+complaint_description+'&unique_id_patient_complaint='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_complaint_list').html(result.html_data);  
                       $('#patient_complaint_id option:selected').removeAttr("selected");
                       $('#patient_complaint_unit').val('');
                       $('#patient_complaint_type option:selected').removeAttr("selected");
                       $('#complaint_description').val('');
                     } 
                   });
             $('#row_id_patient_complaint').val(parseInt(rec_count)+parseInt(1)); 
           }
         
           function add_patient_allergy_listdata()
           {
             var rec_count = $("#row_id_patient_allergy").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_allergy_id = $('#patient_allergy_id').val();
             var allergy_value = $('#patient_allergy_id option:selected').text();
             var patient_allergy_unit = $('#patient_allergy_unit').val();
             var patient_allergy_type = $('#patient_allergy_type option:selected').text();
             //alert(patient_allergy_type);return false;
             var allergy_description = $('#allergy_description').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_allergy_list/", 
                     dataType: "json",
                     data: 'patient_allergy_id='+patient_allergy_id+'&allergy_value='+allergy_value+'&patient_allergy_unit='+patient_allergy_unit+'&patient_allergy_type='+patient_allergy_type+'&allergy_description='+allergy_description+'&unique_id_patient_allergy='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_allergy_list').html(result.html_data);  
                       $('#patient_allergy_id option:selected').removeAttr("selected");
                       $('#patient_allergy_unit').val('');
                       $('#patient_allergy_type option:selected').removeAttr("selected");
                       $('#allergy_description').val('');
                     } 
                   });
             $('#row_id_patient_allergy').val(parseInt(rec_count)+parseInt(1));
             
           }
         
           function add_patient_general_examination_listdata()
           {
             var rec_count = $("#row_id_general_examination").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_general_examination_id = $('#patient_general_examination_id').val();
             var general_examination_value = $('#patient_general_examination_id option:selected').text();
             //var patient_general_examination_unit = $('#patient_general_examination_unit').val();
             //var patient_general_examination_type = $('#patient_general_examination_type option:selected').text();
             //alert(patient_general_examination_type);return false;
             var general_examination_description = $('#general_examination_description').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_general_examination_list/", 
                     dataType: "json",
                     data: 'patient_general_examination_id='+patient_general_examination_id+'&general_examination_value='+general_examination_value+'&general_examination_description='+general_examination_description+'&unique_id_patient_general_examination='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_general_examination_list').html(result.html_data);  
                       $('#patient_general_examination_id option:selected').removeAttr("selected");
                       //$('#patient_general_examination_unit').val('');
                       //$('#patient_general_examination_type option:selected').removeAttr("selected");
                       $('#general_examination_description').val('');
                     } 
                   });
             $('#row_id_general_examination').val(parseInt(rec_count)+parseInt(1));
           }
         
           function add_patient_clinical_examination_listdata()
           {
             var rec_count = $("#row_id_clinical_examination").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_clinical_examination_id = $('#patient_clinical_examination_id').val();
             var clinical_examination_value = $('#patient_clinical_examination_id option:selected').text();
             //var patient_clinical_examination_unit = $('#patient_clinical_examination_unit').val();
             //var patient_clinical_examination_type = $('#patient_clinical_examination_type option:selected').text();
             //alert(patient_clinical_examination_type);return false;
             var clinical_examination_description = $('#clinical_examination_description').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_clinical_examination_list/", 
                     dataType: "json",
                     data: 'patient_clinical_examination_id='+patient_clinical_examination_id+'&clinical_examination_value='+clinical_examination_value+'&clinical_examination_description='+clinical_examination_description+'&unique_id_patient_clinical_examination='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_clinical_examination_list').html(result.html_data);  
                       $('#patient_clinical_examination_id option:selected').removeAttr("selected");
                       //$('#patient_clinical_examination_unit').val('');
                       //$('#patient_clinical_examination_type option:selected').removeAttr("selected");
                       $('#clinical_examination_description').val('');
                     } 
                   });
             $('#row_id_clinical_examination').val(parseInt(rec_count)+parseInt(1));
           }


           function add_patient_icsilab_listdata()
           {
             var rec_count = $("#row_icsilab_id").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var icsilab_date = $('#icsilab_date').val();
             var oocytes = $('#oocytes').val();
             var m2 = $('#m2').val();
             var injected = $('#injected').val();
             var cleavge = $('#cleavge').val();
             var embryos_day3 = $('#embryos_day3').val();
             var day5 = $('#day5').val();
             var day_of_et = $('#day_of_et').val();
             var et = $('#et').val();
             var vit = $('#vit').val();
             var lah = $('#lah').val();
             var semen = $('#semen').val();
             var count = $('#count').val();
             var motility = $('#motility').val();
             var g3 = $('#g3').val();
             var abn_form = $('#abn_form').val();
             var imsi = $('#imsi').val();
             var pregnancy = $('#pregnancy').val();
             var remarks = $('#remarks').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_icsilab_list/", 
                     dataType: "json",
                     data: 'icsilab_date='+icsilab_date+'&oocytes='+oocytes+'&m2='+m2+'&injected='+injected+'&cleavge='+cleavge+'&embryos_day3='+embryos_day3+'&day5='+day5+'&day_of_et='+day_of_et+'&et='+et+'&vit='+vit+'&lah='+lah+'&semen='+semen+'&count='+count+'&motility='+motility+'&g3='+g3+'&abn_form='+abn_form+'&imsi='+imsi+'&pregnancy='+pregnancy+'&remarks='+remarks+'&unique_id_patient_icsilab='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_icsilab_list').html(result.html_data);  
                       $('#icsilab_date').val('');
                       $('#oocytes').val('');
                       $('#m2').val('');
                       $('#injected').val('');
                       $('#cleavge').val('');
                       $('#embryos_day3').val('');
                       $('#day5').val('');
                       $('#day_of_et').val('');
                       $('#et').val('');
                       $('#vit').val('');
                       $('#lah').val('');
                       $('#semen').val('');
                       $('#count').val('');
                       $('#motility').val('');
                       $('#g3').val('');
                       $('#abn_form').val('');
                       $('#imsi').val('');
                       $('#pregnancy').val('');
                       $('#remarks').val('');
                     } 
                   });
             $('#row_icsilab_id').val(parseInt(rec_count)+parseInt(1));
           }
           
           
           function add_fertility_data()
           { 
             var fertility_co = $('#fertility_co').val();
             var fertility_risk = $('#fertility_risk').val();
             var fertility_uterine_factor = $('#fertility_uterine_factor').val();
             var fertility_tubal_factor = $('#fertility_tubal_factor').val();
             var fertility_decision = $('#fertility_decision').val();
             var fertility_ovarian_factor = $('#fertility_ovarian_factor').val();
             var fertility_male_factor = $('#fertility_male_factor').val();
             var fertility_sperm_date = $('#fertility_sperm_date').val();
             var fertility_sperm_count = $('#fertility_sperm_count').val();
             var fertility_sperm_motality = $('#fertility_sperm_motality').val();
             var fertility_sperm_g3 = $('#fertility_sperm_g3').val();
             var fertility_sperm_abnform = $('#fertility_sperm_abnform').val();
             var fertility_sperm_remarks = $('#fertility_sperm_remarks').val(); 
             
             var fertility_uploadhsg = $("#fertility_uploadhsg").prop("files")[0];
             var fertility_laparoscopy = $("#fertility_laparoscopy").prop("files")[0];
             var fertility_ultrasound_images = $("#fertility_ultrasound_images").prop("files")[0];
             var form_data = new FormData(); 
             form_data.append("fertility_uploadhsg", fertility_uploadhsg);
             form_data.append("fertility_laparoscopy", fertility_laparoscopy);
             form_data.append("fertility_ultrasound_images", fertility_ultrasound_images);
             form_data.append("fertility_co", fertility_co);
             form_data.append("fertility_risk", fertility_risk);
             form_data.append("fertility_uterine_factor", fertility_uterine_factor);
             form_data.append("fertility_tubal_factor", fertility_tubal_factor);
             form_data.append("fertility_decision", fertility_decision);
             form_data.append("fertility_ovarian_factor", fertility_ovarian_factor);
             form_data.append("fertility_male_factor", fertility_male_factor);
             form_data.append("fertility_sperm_date", fertility_sperm_date);
             form_data.append("fertility_sperm_count", fertility_sperm_count);
             form_data.append("fertility_sperm_motality", fertility_sperm_motality);
             form_data.append("fertility_sperm_g3", fertility_sperm_g3);
             form_data.append("fertility_sperm_abnform", fertility_sperm_abnform);
             form_data.append("fertility_sperm_remarks", fertility_sperm_remarks); 
              
             $.ajax({
                    type: "POST",
                    url: "https://www.hospitalms.in/gynaecology/gynecology/gynecology_prescription/add_fertility_data/", 
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data, 
                     success: function(result)
                     {
                       $('#fertility_co').val('');
                       $('#fertility_uploadhsg').val('');
                       $('#fertility_laparoscopy').val('');
                       $('#fertility_ultrasound_images').val('');
                       $('#fertility_risk').val('');
                       $('#fertility_uterine_factor').val('');
                       $('#fertility_tubal_factor').val('');
                       $('#fertility_decision').val('');
                       $('#fertility_ovarian_factor').val('');
                       $('#fertility_male_factor').val('');
                       $('#fertility_sperm_date').val('');
                       $('#fertility_sperm_count').val('');
                       $('#fertility_sperm_motality').val('');
                       $('#fertility_sperm_g3').val('');
                       $('#fertility_sperm_abnform').val('');
                       $('#fertility_sperm_remarks').val(''); 
                       load_fertility_data();
                     } 
                   }); 
           }
           
           
           function load_fertility_data()
           {
               $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_fertility_data/",  
                     success: function(result)
                     { 
                       $('#table_patient_fertility tbody').html(result);
                     } 
                   });
           }
           
           load_fertility_data();
           
           
           
           function add_patient_antenatal_care_listdata()
           {
             var rec_count = $("#row_antenatal_care_id").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             
             var weight = $('#weight').val();
             var height = $('#height').val();
             var bmi_calculate = $('#bmi_calculate').val();
             var confirm_delivery_date = $('#confirm_delivery_date').val();
             
             var antenatal_care_period = $('#antenatal_care_period').val();
             var antenatal_first_date = $('#antenatal_first_date').val();
             var antenatal_expected_date = $('#antenatal_expected_date').val();
            var antenatal_remarks = $('#antenatal_remarks').val();
            
            var antenatal_ultrasound = $("#antenatal_ultrasound").prop("files")[0];
             var form_data = new FormData(); 
             form_data.append("weight", weight);
             form_data.append("height", height);
             form_data.append("bmi_calculate", bmi_calculate);
             form_data.append("confirm_delivery_date", confirm_delivery_date);
             
             form_data.append("antenatal_ultrasound", antenatal_ultrasound);
             form_data.append("antenatal_care_period", antenatal_care_period);
             form_data.append("antenatal_first_date", antenatal_first_date);
             form_data.append("antenatal_expected_date", antenatal_expected_date);
             form_data.append("antenatal_remarks", antenatal_remarks);
             
             $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_antenatal_care_list", 
                    dataType: 'script',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data, 
                     success: function(result)
                     {
                        $('#weight').val('');
                        $('#height').val('');
                        $('#bmi_calculate').val('');
                        $('#confirm_delivery_date').val('');
                        
                        
                       $('#antenatal_ultrasound').val('');
                       $('#antenatal_care_period').val('');
                       $('#antenatal_first_date').val('');
                       $('#antenatal_expected_date').val('');
                       $('#antenatal_remarks').val(''); 
                       load_antenatal_data();
                     } 
                   }); 
            
             /*$.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_antenatal_care_list/", 
                     dataType: "json",
                     data: 'antenatal_care_period='+antenatal_care_period+'&antenatal_first_date='+antenatal_first_date+'&antenatal_expected_date='+antenatal_expected_date+'&antenatal_remarks='+antenatal_remarks+'&unique_id_patient_antenatal_care='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_antenatal_care_list').html(result.html_data);  
                       $('#antenatal_care_period').val('');
                       $('#antenatal_first_date').val('');
                       $('#antenatal_expected_date').val('');
                       
                       $('#antenatal_remarks').val('');
                     } 
                   });*/
             $('#row_antenatal_care_id').val(parseInt(rec_count)+parseInt(1));
           }
           
           
           function load_antenatal_data()
           {
               $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/load_antenatal_data/",  
                     success: function(result)
                     { 
                       $('#patient_antenatal_care_list tbody').html(result);
                     } 
                   });
           }
           
           load_antenatal_data();
         


            function add_patient_gpla_listdata()
           {
             var rec_count = $("#row_id_patient_gpla").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var dog_value = $('#dog_value').val();
             var mode_value = $('#mode_value').val();
             var monyear_value = $('#monyear_value').val();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_gpla_list/", 
                     dataType: "json",
                     data: 'dog_value='+dog_value+'&mode_value='+mode_value+'&monthyear_value='+monyear_value+'&unique_id_patient_gpla='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_gpla_list').html(result.html_data);  
                       $('#mode_value').val('');
                       $('#dog_value').val('');
                       $('#monyear_value').val('');
                     } 
                   });
             $('#row_id_patient_gpla').val(parseInt(rec_count)+parseInt(1));
           }
           
           
           
         

         
         
           function add_patient_investigation_listdata()
           {
             var rec_count = $("#row_id_patient_investigation").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_investigation_id = $('#patient_investigation_id').val();
             var investigation_value = $('#patient_investigation_id option:selected').text();
             var std_value = $('#std_value').val();
             var investigation_date = $('#investigation_date').val();
             var observed_value = $('#observed_value').val();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_investigation_list/", 
                     dataType: "json",
                     data: 'patient_investigation_id='+patient_investigation_id+'&investigation_value='+investigation_value+'&observed_value='+observed_value+'&std_value='+std_value+'&unique_id_patient_investigation='+rec_count+'&investigation_date='+investigation_date,
                     
                     success: function(result)
                     {
                       $('#patient_investigation_list').html(result.html_data);  
                       $('#patient_investigation_id option:selected').removeAttr("selected");
                       $('#observed_value').val('');
                       $('#std_value').val('');
                       $('#investigation_date').val('');
                     } 
                   });
             $('#row_id_patient_investigation').val(parseInt(rec_count)+parseInt(1));
           }
         
           function add_patient_advice_listdata()
           {
             var rec_count = $("#row_id_patient_advice").val();
             if(rec_count==undefined | rec_count=="")
             {
               rec_count=1;
             }
             var patient_advice_id = $('#patient_advice_id').val();
             //var advice_value = $('#patient_advice_id option:selected').text();
            var advice_value = $('#patient_advice_id').val();
             
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/patient_advice_list/", 
                     dataType: "json",
                     data: 'patient_advice_id='+patient_advice_id+'&advice_value='+advice_value+'&unique_id_patient_advice='+rec_count,
                     
                     success: function(result)
                     {
                       $('#patient_advice_list').html(result.html_data);  
                        $("#patient_advice_id").val("");
                       //$('#patient_advice_id option:selected').removeAttr("selected");
                     } 
                   });
             $('#row_id_patient_advice').val(parseInt(rec_count)+parseInt(1));
             
           }
         
         
         
         // start open master frm popup for gynec
         function add_relation()
         {
           //alert();
           var $modal = $('#load_add_relation_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/relation/add/' ?>',
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
         
           var $modal = $('#load_add_gynecology_disease_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/disease/add/' ?>',
           {
             //'id1': '1',
             //'id2': '2'
             },
           function(){
           $modal.modal('show');
           });
         }
         
         
         function add_complaint()
         {
         
           var $modal = $('#load_add_gynecology_complaints_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/complaints/add/' ?>',
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
           var $modal = $('#load_add_gynecology_allergy_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/allergy/add/' ?>',
           {
             //'id1': '1',
             //'id2': '2'
             },
           function(){
           $modal.modal('show');
           });
         }
         
         function add_general_examination()
         {
           var $modal = $('#load_add_general_examination_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/general_examination/add/' ?>',
           {
             //'id1': '1',
             //'id2': '2'
             },
           function(){
           $modal.modal('show');
           });
         }
         
         function add_clinical_examination()
         {
           var $modal = $('#load_add_clinical_examination_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/clinical_examination/add/' ?>',
           {
             //'id1': '1',
             //'id2': '2'
             },
           function(){
           $modal.modal('show');
           });
         }
         
         function add_investigation()
         {
           var $modal = $('#load_add_gynecology_inves_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/investigation/add/' ?>',
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
           var $modal = $('#load_add_gynecology_advice_modal_popup');
           $modal.load('<?php echo base_url().'gynecology/advice/add/' ?>',
           {
             //'id1': '1',
             //'id2': '2'
             },
           function(){
           $modal.modal('show');
           });
         }
         
         /// finish
         
         function delete_rows_sub_category(id)
         { 
           $('#'+id+'sub_category').remove();
         }
      </script>
      <script type="text/javascript">
         function delete_patient_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_history_vals(allVals);
         }
         
         
         function remove_patient_history_vals(allVals)
         {    
           if(allVals!="")
           {
             var row_count = $('#patient_history_count').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_history');?>",
                     data: 'row_count='+row_count+'&patient_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_history_list').html(result.html_data);    
                     }
                 });
           }
         }



         function delete_right_ovary_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_right_ovary_vals(allVals);
         }
         //right ovary vals
         function remove_right_ovary_vals(allVals)
         {    
           if(allVals!="")
           {
             var row_count = $('#right_ovary_count').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_right_ovary');?>",
                     data: 'row_count='+row_count+'&right_ovary_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_right_ovary_list').html(result.html_data);    
                     }
                 });
           }
         }


         function delete_left_ovary_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_left_ovary_vals(allVals);
         }
         //right ovary vals
         function remove_left_ovary_vals(allVals)
         {    
           if(allVals!="")
           {
             var row_count = $('#left_ovary_count').val();
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_left_ovary');?>",
                     data: 'row_count='+row_count+'&left_ovary_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_left_ovary_list').html(result.html_data);    
                     }
                 });
           }
         }

         
         
         function delete_patient_family_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_family_history_vals(allVals);
         }
         
         
         function remove_patient_family_history_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_family_history');?>",
                     data: 'patient_family_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_family_history_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         
         function delete_patient_personal_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_personal_history_vals(allVals);
         }
         
         
         function remove_patient_personal_history_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_personal_history');?>",
                     data: 'patient_personal_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_personal_history_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_menstrual_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_menstrual_history_vals(allVals);
         }
         
         
         function remove_patient_menstrual_history_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_menstrual_history');?>",
                     data: 'patient_menstrual_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_menstrual_history_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_medical_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_medical_history_vals(allVals);
         }
         
         
         function remove_patient_medical_history_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_medical_history');?>",
                     data: 'patient_medical_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_medical_history_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_obestetric_history_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_obestetric_history_vals(allVals);
         }
         
         
         function remove_patient_obestetric_history_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_obestetric_history');?>",
                     data: 'patient_obestetric_history_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_obestetric_history_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_disease_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_disease_vals(allVals);
         }
         
         
         function remove_patient_disease_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_disease');?>",
                     data: 'patient_disease_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_disease_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_complaint_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_complaint_vals(allVals);
         }
         
         
         function remove_patient_complaint_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_complaint');?>",
                     data: 'patient_complaint_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_complaint_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_allergy_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_allergy_vals(allVals);
         }
         
         
         function remove_patient_allergy_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_allergy');?>",
                     data: 'patient_allergy_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_allergy_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_general_examination_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_general_examination_vals(allVals);
         }
         
         
         function delete_fertility_record() 
         {          
           var allVals = [];
           $('.fertility_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_fertility_vals(allVals);
         }
         
         
          
         
         
         function remove_patient_general_examination_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_general_examination');?>",
                     data: 'patient_general_examination_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_general_examination_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         
         function remove_fertility_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_fertility_vals');?>",
                     data: 'keys='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         if(result==1)
                         {
                             load_fertility_data(); 
                         } 
                     }
                 });
           }
         }
         
         function delete_patient_clinical_examination_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_clinical_examination_vals(allVals);
         }
         
         
         function remove_patient_clinical_examination_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_clinical_examination');?>",
                     data: 'patient_clinical_examination_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_clinical_examination_list').html(result.html_data);    
                     }
                 });
           }
         }

         ///icsilab

         function delete_patient_icsilab_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_icsilab_vals(allVals);
         }
         
         
         function remove_patient_icsilab_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_icsilab');?>",
                     data: 'patient_icsilab_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_icsilab_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         
          function delete_patient_antenatal_care_vals() 
         {          
           var allVals = [];
           $('.antenatal_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           }); 
           remove_patient_antenatal_care_vals(allVals);
         }
         
         
         function remove_patient_antenatal_care_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_antenatal_care');?>",
                     data: 'keys='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         //$('#patient_antenatal_care_list').html(result.html_data);    
                         load_antenatal_data();
                     }
                 });
           }
         }
         


         
         function delete_patient_investigation_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_investigation_vals(allVals);
         }
         
          function delete_patient_gpla_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_gpla_vals(allVals);
         }

         function remove_patient_gpla_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_gpla');?>",
                     data: 'patient_gpla_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_gpla_list').html(result.html_data);    
                     }
                 });
           }
         }


         function remove_patient_investigation_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_investigation');?>",
                     data: 'patient_investigation_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_investigation_list').html(result.html_data);    
                     }
                 });
           }
         }
         
         function delete_patient_advice_vals() 
         {          
           var allVals = [];
           $('.booked_checkbox').each(function() 
           {
               if($(this).prop('checked')==true && !isNaN($(this).val()))
               {
                   allVals.push($(this).val());
               } 
           });
           remove_patient_advice_vals(allVals);
         }
         
         
         function remove_patient_advice_vals(allVals)
         {    
           if(allVals!="")
           {
             $.ajax({
                     type: "POST",
                     url: "<?php echo base_url('gynecology/gynecology_prescription/remove_gynecology_patient_advice');?>",
                     data: 'patient_advice_vals='+allVals,
                     dataType: "json",
                     success: function(result) 
                     { 
                         $('#patient_advice_list').html(result.html_data);    
                     }
                 });
           }
         }


          function get_medicine_type_autocomplete(row_id, type)
          {
            if(type==1)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
              var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                  $("#type_patient"+row_id).val(ui.item.value);
                  return false;
              }

              $("#type_patient"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            else if(type==2)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
              var getData = function (request, response) { 
                 row = i ;
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>",
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
                  $("#type"+row_id).val(ui.item.value);
                  return false;
              }

              $("#type"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            
          }

          function get_medicine_dose_autocomplete(row_id, type)
          {
            if(type==1)
            {
             /* var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
               var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#dose_patient"+row_id).val(ui.item.value);
                  return false;
              }

              $("#dose_patient"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            else if(type==2)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
               var getData = function (request, response) { 
                 
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#dose"+row_id).val(ui.item.value);
                  return false;
              }

              $("#dose"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            
            
            
          }

          function get_medicine_duration_autocomplete(row_id, type)
          {
            if(type==1)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#duration_patient"+row_id).val(ui.item.value);
                  return false;
              }

              $("#duration_patient"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            else if(type==2)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#duration"+row_id).val(ui.item.value);
                  return false;
              }

              $("#duration"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            
            
            
          }

          function get_medicine_frequency_autocomplete(row_id, type)
          {
            if(type==1)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#frequency_patient"+row_id).val(ui.item.value);
                  return false;
              }

              $("#frequency_patient"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            else if(type==2)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#frequency"+row_id).val(ui.item.value);
                  return false;
              }

              $("#frequency"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            
            
            
          }

          function get_medicine_advice_autocomplete(row_id, type)
          {
            if(type==1)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
               var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#advice_patient"+row_id).val(ui.item.value);
                  return false;
              }

              $("#advice_patient"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            else if(type==2)
            {
              /*var getData = function (request, response) { 
                $.getJSON(
                    "< ?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                    function (data) {
                        response(data);
                    });
              };*/
              
               var getData = function (request, response) { 
                                             
                 $.ajax({
                 url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>",
                 dataType: "json",
                 method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                 
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
                  $("#advice"+row_id).val(ui.item.value);
                  return false;
              }

              $("#advice"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
            
            
          }

          function get_medicine_autocomplete(row_id)
          {
                var getData = function (request, response) { 
                $.ajax({
                  url : "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>",
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
                $('#medicine_name'+row_id).val(names[0]);
                $('#type'+row_id).val(names[1]);
                $('#brand'+row_id).val(names[3]);
                $('#salt'+row_id).val(names[2]);
                $('#medicine_id'+row_id).val(names[4]);
                //$(".medicine_val").val(ui.item.value);
                
                return false;
              }

              $("#medicine_name"+row_id).autocomplete({
                  source: getData,
                  select: selectItem,
                  minLength: 1,
                  change: function() {
                      //$("#test_val").val("").css("display", 2);
                  }
              });
            }
         
         
      </script>
      <script>
         $(document).ready(function() {
           $('#load_add_relation_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
           })
         });
          $(document).ready(function() {
           $('#load_add_gynecology_disease_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
           })
         });
         
          $(document).ready(function() {
           $('#load_add_gynecology_complaints_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
            })
          });
         
          $(document).ready(function() {
           $('#load_add_gynecology_allergy_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
            })
          });
         
          $(document).ready(function() {
           $('#load_add_general_examination_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
            })
          });
         
          $(document).ready(function() {
           $('#load_add_clinical_examination_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
            })
          });
         
         $(document).ready(function() {
           $('#load_add_gynecology_inves_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
           })
         });
         
         
           $(document).ready(function() {
           $('#load_add_gynecology_advice_modal_popup').on('shown.bs.modal', function(e) {
              $('.inputFocus').focus();
            })
          });
         
         
         //old
         
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
         
          function toggle_family_history(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_family_history');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
          function toggle_pat(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_pat');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
          function toggle_personal_history(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_personal_history');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
          function toggle_menstrual_history(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_menstrual_history');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }

          function toggle_left_ovary(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_left_ovary');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
          function toggle_medical_history(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_medical_history');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
          function toggle_obestetric_history(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_obestetric_history');
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
         
          function toggle_complaints(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_complaints');
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
          
          function toggle_general_examination(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_general_examination');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
         
         
          function toggle_clinical_examination(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_clinical_examination');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }

          function toggle_icsilab(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_icsilab');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
          

          function toggle_antenatal_care(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_antenatal_care');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }
          
           function toggle_fertility(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_fertility');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }

          
         
          function toggle_investigation(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_investigation');
              for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
              }
          }

          function toggle_gpla(source) 
          {  
              checkboxes = document.getElementsByClassName('part_checkbox_gpla');
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
          
          $(document).ready(function(){
         
            $("#patient_history_list").on('click','.remCF',function(){
                $(this).parent().parent().remove();
            });
         
            });
         
          function get_std_value(value)
          {
            if(value=="" || value==undefined)
            {
              $("#std_value").val('');
              return false;
            }
            else
            {
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/get_std_value/", 
                    data: 'value='+value,
                    success: function(result)
                    {
                      if(result==0)
                      {
                        $("#std_value").val("");
                      }
                      else
                      {
                        $("#std_value").val(result);
                      }
                    } 
                  });
            }
          }
          
           function get_advice_master_value(value)
          {
            if(value=="" || value==undefined)
            {
              $("#patient_advice_id").val('');
              return false;
            }
            else
            {
              $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>gynecology/gynecology_prescription/get_advice_value/", 
                    data: 'value='+value,
                    success: function(result)
                    {
                      if(result==0)
                      {
                        $("#patient_advice_id").val("");
                      }
                      else
                      {
                        $("#patient_advice_id").val(result);
                      }
                    } 
                  });
            }
          }
      </script>
      <div id="load_add_relation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_gynecology_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_gynecology_complaints_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_gynecology_allergy_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_general_examination_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_clinical_examination_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_gynecology_inves_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div id="load_add_gynecology_advice_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
         
         })
      </script>
      <script>
         $(document).ready(function(){

            $('#right_ovary_btn').click(function(){ 
             $('.innertab1').show(); 
             $('#patient_right_ovary_list').show();
             $('#patient_right_ovary_delete').show();
             $('.innertab2').hide();
             $('#patient_left_ovary_list').hide();
             $('#patient_left_ovary_delete').hide();
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#left_overy_btn').removeClass('activeBtn').addClass('btn-save');
           });

           $('#left_overy_btn').click(function(){
                $('.innertab1').hide(); 
                $('#patient_right_ovary_list').hide();
                $('#patient_right_ovary_delete').hide();
                $('.innertab2').show();
                $('#patient_left_ovary_list').show();
                $('#patient_left_ovary_delete').show();
                $(this).addClass('activeBtn').removeClass('btn-save');
                $('#right_ovary_btn').removeClass('activeBtn').addClass('btn-save');
           });


           $('#history_btn').click(function(){
             $('.innertab1').show();
             $('.innertab2').hide();
             $('.innertab3').hide();
             $('.innertab4').hide();
             $('.innertab5').hide();
             $('.innertab6').hide();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
         
         
           });
           $('#family_history_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').show();
             $('.innertab3').hide();
             $('.innertab4').hide();
             $('.innertab5').hide();
             $('.innertab6').hide();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
           });
           $('#personal_history_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').hide();
             $('.innertab3').show();
             $('.innertab4').hide();
             $('.innertab5').hide();
             $('.innertab6').hide();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
           });
           $('#menstrual_history_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').hide();
             $('.innertab3').hide();
             $('.innertab4').show();
             $('.innertab5').hide();
             $('.innertab6').hide();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
           });
           $('#medical_history_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').hide();
             $('.innertab3').hide();
             $('.innertab4').hide();
             $('.innertab5').show();
             $('.innertab6').hide();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
           });
           $('#obestetric_history_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').hide();
             $('.innertab3').hide();
             $('.innertab4').hide();
             $('.innertab5').hide();
             $('.innertab6').show();
             $('.innertab7').hide();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#current_medication_btn').removeClass('activeBtn').addClass('btn-save');
           });
           $('#current_medication_btn').click(function(){
             $('.innertab1').hide();
             $('.innertab2').hide();
             $('.innertab3').hide();
             $('.innertab4').hide();
             $('.innertab5').hide();
             $('.innertab6').hide();
             $('.innertab7').show();
         
             $(this).addClass('activeBtn').removeClass('btn-save');
             $('#history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#family_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#personal_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#menstrual_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#medical_history_btn').removeClass('activeBtn').addClass('btn-save');
             $('#obestetric_history_btn').removeClass('activeBtn').addClass('btn-save');
           });
         });

// add date
  /*      $('#nxt_days').change(function() {
                var date2 = $('#bok_date_time').val(); 
                date2=new Date(date2);
                alert(date2);
                date2.setDate(date2.getDate()+1); 
                
                $('#next_appointment_date').datepicker('setDate', date2);
              });*/

$(document).ready(function(){
    
    function DateFromString(str){ 
      var dayss=+($('#nxt_days').val()); 
        str = str.split('-');
        str = new Date(str[2],str[1]-1,(parseInt(str[0])+dayss));
        return MMDDYYYY(str);
    }
    
    function MMDDYYYY(str) {
        return convert(str);
    }

    function Add7Days() {
        var date = $('#bok_date_time').val();
        var ndate = DateFromString(date);
        return ndate;
    }

    $('#nxt_days').change(function(){
        $('#next_appointment_date').val(Add7Days());
    });

  function convert(str) {
    var date = new Date(str),
    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
    day = ("0" + date.getDate()).slice(-2);
    var date =[day, mnth, date.getFullYear()].join("-");
    return (date+' '+'<?php echo date('h:i A',strtotime($form_data['booking_time'])); ?>');
}

           /* $("#lmpsss").datetimepicker({
                 minView: 2,
                  format: 'dd-mm-yyyy',
                 autoclose: true,
             });*/

$(document).on("focusout","#days",function(){
   test();
});
         
/*$(document).on("focusout","#lmps",function(){
   test();
});*/


  function test(){
    var days=+($('#days').val());
    var date2 = $('#lmps').datetimepicker('getDate', '+'+days+'d'); 
    date2.setDate(date2.getDate()+days); 
    $('#edd').val(formatDate(date2));

    var week = parseInt(days/7);
    var day = days%7;
    $('#pog').val(week+' week + '+day+' days');
  }; 


  $('#bp').change(function() {
    var bp=$('#bp').val();
    var arr=bp.split('/');
    var sbp=parseInt(arr[0]);
    var dbp=parseInt(arr[1]);
    var map=(sbp+(2*dbp))/3;
    $('#map').val(Math.round(map));
  }); 

 function formatDate(date) {
                
                var day = date.getDate();
                var year = date.getFullYear();
                var month = (date.getMonth()+1);
                if(day<10)
                  day='0'+day;
                if(month<10)
                  month='0'+month;
               
                return day + "-" + month + "-" + year;
            }

});

  
 function check_male_factor(value) 
 {
   if(value=="2")
   {
     $("#male_factor_div").css("display","none");
     $('#male_count').val('');
     $('#male_motility').val('');
     $('#male_rapid_motility').val('');
     $('#male_abnormal').val('');
   }
   else 
   {
     $("#male_factor_div").css("display","block");
   }
 }

      </script>
      <script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>

