<?php
$users_data = $this->session->userdata('auth_users');
?>
<?php $this->load->helper('gynecology'); ?>
<!DOCTYPE html>
<html>

<head>
   <title><?php echo $page_title . PAGE_TITLE; ?></title>
   <meta name="viewport" content="width=1024">
   <!-- bootstrap -->
   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dental-style.css">
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
   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
   <!-- datatable js -->
   <script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
   <script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
   <script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
   <style>
      .dental-chart {
         padding: 0.5em;
      }

      ul.dental-tab {
         list-style: none;
         padding: 0px;
         margin: 0 auto;
      }

      ul.dental-tab li {
         float: left;
         padding: 7px 17px !important;
         background: #0e854f;
         text-align: center;
         margin-left: 4px;
         border-radius: 4px;
         box-shadow: 2px 2px #ccc;
      }

      ul.dental-tab li a {
         text-decoration: none;
         color: #fff;
      }

      ul.dental-tab .active {
         background: #0e854f;
      }

      .box-left {
         padding: 1em;
         width: 100%;
      }

      .box-left select {
         width: 100%;
         *padding: 4px;
         margin-bottom: 3px;
      }

      .box-left input {
         width: 100%;
         margin-bottom: 3px;
      }

      .box-left button {
         width: 100%;
         border: none;
         text-align: center;
         padding: 3px;
         margin-bottom: 5px;
         border-radius: 4px;
         box-shadow: 0px 1px 2px #4e7c36;
      }

      .theme-color {
         background: #0e854f;
         color: #fff;
      }

      .duration-box select {
         width: 49%;
      }

      .btn-s-e {
         width: 100%;
         text-align: center;
         padding: 4px;
         margin-bottom: 2px;
         border: none;
         border-radius: 4px;
      }

      .table-box {
         padding: 1em;
         width: 100%;
      }

      .btn-box {
         padding: 1em 5px;
      }

      .dent-type {
         border: 1px solid #666;
         padding: 1em;
      }

      .btn-box1 {
         width: 150px;
         padding: 4px;
         background: #0e854f;
         color: #fff;
         border-radius: 4px;
         text-align: center;
         margin: 8px 0;
      }

      .btn-flex {
         display: flex;
      }

      .btn-flex div {
         flex: 1;
      }

      .btn-text {
         text-align: center;
         margin-bottom: 3px;
      }

      .btn-heading {
         width: 100%;
         margin: 0 auto;
      }

      .btn-box2 {
         width: 150px;
         padding: 4px;
         background: #0e854f;
         color: #fff;
         border-radius: 4px;
         text-align: center;
         margin: 8px auto;
      }

      .btn-text {
         text-align: center;
         margin-bottom: 3px;
      }
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
         <form id="dental_prescription_form" name="dental_prescription_form" method="post" enctype="multipart/form-data">
            <!-- <form id="dental_prescription_form" name="dental_prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data"> -->
            <!--  // prescription button modal -->
            <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
            <div class="row">
               <div class="col-xs-5">
                  <div class="row m-b-5">
                     <div class="col-xs-4"><label>Template Name <span class="star">*</span></label></div>
                     <div class="col-xs-8">
                        <input type="text" name="template_name" value="<?php echo $form_data['template_name']; ?>" autofocus>
                        <?php if (!empty($form_error)) {
                           echo form_error('template_name');
                        } ?>
                     </div>
                  </div>
               </div>
               <!-- 5 -->
            </div>
            <!-- row -->
            <div class="row">
               <div class="col-md-12 dental-chart">
                  <div class="text-center">
                     <ul class="nav nav-tabs">
                        <?php
                        $i = 1;
                        //echo "<pre>"; print_r($prescription_tab_setting);die;


                        foreach ($prescription_tab_setting as $value) {

                           if ($value->setting_name != 'biometric_detail' &&  $value->setting_name != 'pictorial_test'  &&  $value->setting_name != 'antenatal_care'  &&  $value->setting_name != 'fertility'  &&  $value->setting_name != 'icsilab' &&  $value->setting_name != 'follicularscaning') {


                        ?>
                              <li style="margin-top:2px;" <?php if ($i == 1) {  ?> class="active" <?php }  ?>><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if (!empty($value->setting_value)) {
                                                                                                                                                                                                                                                                                 echo $value->setting_value;
                                                                                                                                                                                                                                                                              } else {
                                                                                                                                                                                                                                                                                 echo $value->var_title;
                                                                                                                                                                                                                                                                              } ?></a></li>
                        <?php
                              $i++;
                           }
                        }
                        ?>
                     </ul>
                  </div>
               </div>
            </div> <!-- row -->


            <div class="row">
               <div class="col-md-12">

                  <?php
                  $j = 1;
                  foreach ($prescription_tab_setting as $value) {
                  ?>
                     <div class="tab-content" style="border:none;">
                        <?php  //echo $value->setting_name;
                        if (strcmp(strtolower($value->setting_name), 'patient_history') == '0') {
                           $patient_history_data = $this->session->userdata('patient_history_data');
                           $row_count = count($patient_history_data) + 1;


                        ?>
                           <div id="tab_patient_history" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
                              <input type="hidden" value="<?php echo $row_count; ?>" name="row_id" id="row_id">
                              <div class="row">
                                 <div class="col-md-12 tab-content dental-chart">
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
                                                            <select name="married_life_type" class="m_select_btn" id="married_life_type" style="width:110px;">
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
                                                            <label>Marriage No.</label>
                                                         </div>
                                                         <div class="col-md-7">
                                                            <input type="text" class="w-40px numeric" id='marriage_no' name="marriage_no" placeholder='Marriage No.'>
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
                                                               <option value="">Select Previous Delivery</option>
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
                                                      <div class="row m-b-5">
                                                         <div class="col-md-5">
                                                         </div>
                                                         <div class="col-md-7">
                                                            <button type="button" onclick="add_patient_history_listdata();" class="theme-color add-btn" style="float:right;">Add</button>
                                                         </div>
                                                      </div>
                                                   </div>
                                                   <!-- hide column -->
                                                </div>
                                             </div>
                                             <div class="col-md-8 p-r-0">
                                                <div class="table-box">
                                                   <table class="table table-bordered" id='patient_history_list'>
                                                      <thead>
                                                         <tr>
                                                            <th align="center" width="" style="margin-left:8px;">
                                                               <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_pat(this);">
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
                                                      if (!empty($patient_history_data)) {
                                                         $i = 1;
                                                         foreach ($patient_history_data as $patient_history_val) {

                                                            if (strpos($patient_history_val['married_life_type'], 'Select') !== false) {
                                                               $patient_history_val['married_life_type'] = "";
                                                            } else {
                                                               $patient_history_val['married_life_type'] = $patient_history_val['married_life_type'];
                                                            }
                                                            if (strpos($patient_history_val['previous_delivery'], 'Select') !== false) {
                                                               $patient_history_val['previous_delivery'] = "";
                                                            } else {
                                                               $patient_history_val['previous_delivery'] = $patient_history_val['previous_delivery'];
                                                            }
                                                            if (strpos($patient_history_val['delivery_type'], 'Select') !== false) {
                                                               $patient_history_val['delivery_type'] = "";
                                                            } else {
                                                               $patient_history_val['delivery_type'] = $patient_history_val['delivery_type'];
                                                            }

                                                            if (($patient_history_val['married_life_unit'] == '') || ($patient_history_val['married_life_unit'] == 0)) {
                                                               $patient_history_val['married_life_unit'] = '';
                                                            } else {
                                                               $patient_history_val['married_life_unit'] = $patient_history_val['married_life_unit'];
                                                            }
                                                            if (($patient_history_val['marriage_no'] == '') || ($patient_history_val['marriage_no'] == 0)) {
                                                               $patient_history_val['marriage_no'] = '';
                                                            } else {
                                                               $patient_history_val['marriage_no'] = $patient_history_val['marriage_no'];
                                                            }


                                                      ?>
                                                            <tr name="patient_history_row" id="<?php echo $i; ?>">
                                                               <td>
                                                                  <input type="checkbox" class="part_checkbox_pat booked_checkbox" name="patient_history[]" value="<?php echo $i; ?>">
                                                               </td>
                                                               <td><?php echo $i; ?></td>
                                                               <input type="hidden" id="unique_id" name="unique_id" value="<?php echo $i; ?>">
                                                               <td><?php echo $patient_history_val['marriage_status']; ?></td>
                                                               <td><?php echo $patient_history_val['married_life_unit'] . ' ' . $patient_history_val['married_life_type']; ?></td>
                                                               <td><?php echo $patient_history_val['marriage_no']; ?></td>
                                                               <td><?php echo $patient_history_val['marriage_details']; ?></td>
                                                               <td><?php echo $patient_history_val['previous_delivery']; ?></td>
                                                               <td><?php echo $patient_history_val['delivery_type']; ?></td>
                                                               <td><?php echo $patient_history_val['delivery_details']; ?></td>
                                                            </tr>
                                                         <?php
                                                            $i++;
                                                         } ?>
                                                      <?php } else { ?>
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
                                                <div class="col-md-4">
                                                   <input type="hidden" value="<?php echo $row_count; ?>" name="row_id_family_history" id="row_id_family_history">

                                                   <div class="row m-b-5">
                                                      <div class="col-md-5">
                                                         <label>Relation</label>
                                                      </div>
                                                      <div class="col-md-7">
                                                         <select name="relation" class="w-139px m_select_btn" id="relation_id">
                                                            <option value="">Select Relation</option>
                                                            <?php
                                                            if (!empty($relation_list)) {
                                                               foreach ($relation_list as $relation) {
                                                            ?>
                                                                  <option <?php /*if($form_data['relation_id']==$relation->id){ echo 'selected="selected"'; }*/ ?> value="<?php echo $relation->id; ?>"><?php echo $relation->relation; ?></option>
                                                            <?php
                                                               }
                                                            }
                                                            ?>
                                                         </select>
                                                         <a href="javascript:void(0)" onclick="return add_relation();" class="btn-new"><i class="">New</i></a>
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
                                                            if (!empty($disease_list)) {
                                                               foreach ($disease_list as $disease) {
                                                            ?>
                                                                  <option <?php /*if($form_data['relation_id']==$relation->id){ echo 'selected="selected"'; }*/ ?> value="<?php echo $disease->id; ?>"><?php echo $disease->disease_name; ?></option>
                                                            <?php
                                                               }
                                                            }
                                                            ?>
                                                         </select>
                                                         <a href="javascript:void(0)" onclick="return add_disease();" class="btn-new"><i class="">New</i></a>
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
                                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_family_history(this);">
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
                                                         if (!empty($patient_family_history_data)) {
                                                            $i = 1;
                                                            foreach ($patient_family_history_data as $patient_family_history_val) {

                                                               if (strpos($patient_family_history_val['relation'], 'Select') !== false) {
                                                                  $patient_family_history_val['relation'] = "";
                                                               } else {
                                                                  $patient_family_history_val['relation'] = $patient_family_history_val['relation'];
                                                               }
                                                               if (strpos($patient_family_history_val['disease'], 'Select') !== false) {
                                                                  $patient_family_history_val['disease'] = "";
                                                               } else {
                                                                  $patient_family_history_val['disease'] = $patient_family_history_val['disease'];
                                                               }
                                                               if (strpos($patient_family_history_val['family_duration_type'], 'Select') !== false) {
                                                                  $patient_family_history_val['family_duration_type'] = "";
                                                               } else {
                                                                  $patient_family_history_val['family_duration_type'] = $patient_family_history_val['family_duration_type'];
                                                               }

                                                         ?>
                                                               <tr name="patient_family_history_row" id="<?php echo $i; ?>">
                                                                  <td>
                                                                     <input type="checkbox" class="part_checkbox_family_history booked_checkbox" name="patient_family_history[]" value="<?php echo $i; ?>">
                                                                  </td>
                                                                  <td><?php echo $i; ?></td>
                                                                  <input type="hidden" id="unique_id_family_history" name="unique_id_family_history" value="<?php echo $i; ?>">
                                                                  <td><?php echo $patient_family_history_val['relation']; ?></td>
                                                                  <td><?php echo $patient_family_history_val['disease']; ?></td>
                                                                  <td><?php echo $patient_family_history_val['family_description']; ?></td>
                                                                  <td><?php echo $patient_family_history_val['family_duration_unit'] . ' ' . $patient_family_history_val['family_duration_type']; ?></td>
                                                               </tr>
                                                            <?php
                                                               $i++;
                                                            }
                                                         } else { ?>
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
                                                            <select name="side" class="m_select_btn full-width" id="side">
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
                                                         <select name="hirsutism" class="m_select_btn full-width" id="hirsutism">
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
                                                            <select name="type" class="m_select_btn full-width" id="type">
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
                                                         <select name="dyspareunia" class="m_select_btn full-width" id="dyspareunia">
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
                                                         <button type="button" onclick="add_patient_personal_history_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-8 p-r-0">
                                                   <div class="table-box">
                                                      <table class="table table-bordered" id='patient_personal_history_list'>
                                                         <thead>
                                                            <tr>
                                                               <th align="center" width="">
                                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_personal_history(this);">
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
                                                         if (!empty($patient_personal_history_data)) {
                                                            $i = 1;
                                                            foreach ($patient_personal_history_data as $patient_personal_history_val) {

                                                               if (strpos($patient_personal_history_val['br_discharge'], 'Select') !== false) {
                                                                  $patient_personal_history_val['br_discharge'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['br_discharge'] = $patient_personal_history_val['br_discharge'];
                                                               }
                                                               if (strpos($patient_personal_history_val['side'], 'Select') !== false) {
                                                                  $patient_personal_history_val['side'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['side'] = $patient_personal_history_val['side'];
                                                               }
                                                               if (strpos($patient_personal_history_val['hirsutism'], 'Select') !== false) {
                                                                  $patient_personal_history_val['hirsutism'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['hirsutism'] = $patient_personal_history_val['hirsutism'];
                                                               }
                                                               if (strpos($patient_personal_history_val['white_discharge'], 'Select') !== false) {
                                                                  $patient_personal_history_val['white_discharge'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['white_discharge'] = $patient_personal_history_val['white_discharge'];
                                                               }
                                                               if (strpos($patient_personal_history_val['type'], 'Select') !== false) {
                                                                  $patient_personal_history_val['type'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['type'] = $patient_personal_history_val['type'];
                                                               }
                                                               if (strpos($patient_personal_history_val['dyspareunia'], 'Select') !== false) {
                                                                  $patient_personal_history_val['dyspareunia'] = "";
                                                               } else {
                                                                  $patient_personal_history_val['dyspareunia'] = $patient_personal_history_val['dyspareunia'];
                                                               }

                                                         ?>
                                                               <tr name="patient_personal_history_row" id="<?php echo $i; ?>">
                                                                  <td>
                                                                     <input type="checkbox" class="part_checkbox_personal_history booked_checkbox" name="patient_personal_history[]" value="<?php echo $i; ?>">
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
                                                         } else { ?>
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
                                                         <select name="previous_cycle" class="m_select_btn full-width" id="previous_cycle">
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
                                                         <select name="prev_cycle_type" class="m_select_btn full-width" id="prev_cycle_type">
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
                                                         <select name="present_cycle" class="m_select_btn full-width" id="present_cycle">
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
                                                         <select name="present_cycle_type" class="m_select_btn full-width" id="present_cycle_type">
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
                                                         <textarea id="cycle_details" name="cycle_details" style="height:100px;padding:0px;"></textarea>
                                                      </div>
                                                   </div>
                                                   <div class="row m-b-5">
                                                      <div class="col-md-5">
                                                         <label>LMP Date</label>
                                                      </div>
                                                      <div class="col-md-7">
                                                         <input type="text" name="lmp_date" id="lmp_date" class="datepicker date" data-date-format="dd-mm-yyyy" style="width:186px;" value="<?php //echo $form_data['next_appointment_date']; 
                                                                                                                                                                                             ?>" readonly />
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
                                                            <select name="dysmenorrhea_type" class="m_select_btn full-width" id="dysmenorrhea_type">
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
                                                         <button type="button" onclick="add_patient_menstrual_history_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-8 p-r-0">
                                                   <div class="table-box">
                                                      <table class="table table-bordered" id='patient_menstrual_history_list'>
                                                         <thead>
                                                            <tr>
                                                               <th align="center" width="">
                                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_menstrual_history(this);">
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
                                                               <th>LMP Date</th>
                                                               <th>Dysmenorrhea</th>
                                                               <th>Dysmenorrhea Type</th>
                                                            </tr>
                                                         </thead>
                                                         <?php
                                                         $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
                                                         $i = 0;
                                                         if (!empty($patient_menstrual_history_data)) {
                                                            $i = 1;
                                                            foreach ($patient_menstrual_history_data as $patient_menstrual_history_val) {

                                                               if (strpos($patient_menstrual_history_val['previous_cycle'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['previous_cycle'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['previous_cycle'] = $patient_menstrual_history_val['previous_cycle'];
                                                               }
                                                               if (strpos($patient_menstrual_history_val['prev_cycle_type'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['prev_cycle_type'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['prev_cycle_type'] = $patient_menstrual_history_val['prev_cycle_type'];
                                                               }
                                                               if (strpos($patient_menstrual_history_val['present_cycle'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['present_cycle'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['present_cycle'] = $patient_menstrual_history_val['present_cycle'];
                                                               }
                                                               if (strpos($patient_menstrual_history_val['present_cycle_type'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['present_cycle_type'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['present_cycle_type'] = $patient_menstrual_history_val['present_cycle_type'];
                                                               }
                                                               if (strpos($patient_menstrual_history_val['dysmenorrhea'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['dysmenorrhea'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['dysmenorrhea'] = $patient_menstrual_history_val['dysmenorrhea'];
                                                               }
                                                               if (strpos($patient_menstrual_history_val['dysmenorrhea_type'], 'Select') !== false) {
                                                                  $patient_menstrual_history_val['dysmenorrhea_type'] = "";
                                                               } else {
                                                                  $patient_menstrual_history_val['dysmenorrhea_type'] = $patient_menstrual_history_val['dysmenorrhea_type'];
                                                               }
                                                               $lmp_date = '';
                                                               if (($patient_menstrual_history_val['lmp_date'] == "01-01-1970") || ($patient_menstrual_history_val['lmp_date'] == ''))
                                                                  $lmp_date = "";
                                                               else
                                                                  $lmp_date = date("d-m-Y", strtotime($patient_menstrual_history_val['lmp_date']));

                                                         ?>
                                                               <tr name="patient_menstrual_history_row" id="<?php echo $i; ?>">
                                                                  <td>
                                                                     <input type="checkbox" class="part_checkbox_menstrual_history booked_checkbox" name="patient_menstrual_history[]" value="<?php echo $i; ?>">
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
                                                         } else { ?>
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
                                                         <select name="tb" class="m_select_btn full-width" id="tb">
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
                                                         <select name="dm" class="m_select_btn full-width" id="dm">
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
                                                         <select name="ht" class="m_select_btn full-width" id="ht">
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
                                                         <button type="button" onclick="add_patient_medical_history_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-8 p-r-0">
                                                   <div class="table-box">
                                                      <table class="table table-bordered" id='patient_medical_history_list'>
                                                         <thead>
                                                            <tr>
                                                               <th align="center" width="">
                                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_medical_history(this);">
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
                                                         if (!empty($patient_medical_history_data)) {
                                                            $i = 1;
                                                            foreach ($patient_medical_history_data as $patient_medical_history_val) {

                                                               if (strpos($patient_medical_history_val['tb'], 'Select') !== false) {
                                                                  $patient_medical_history_val['tb'] = "";
                                                               } else {
                                                                  $patient_medical_history_val['tb'] = $patient_medical_history_val['tb'];
                                                               }
                                                               if (strpos($patient_medical_history_val['dm'], 'Select') !== false) {
                                                                  $patient_medical_history_val['dm'] = "";
                                                               } else {
                                                                  $patient_medical_history_val['dm'] = $patient_medical_history_val['dm'];
                                                               }
                                                               if (strpos($patient_medical_history_val['ht'], 'Select') !== false) {
                                                                  $patient_medical_history_val['ht'] = "";
                                                               } else {
                                                                  $patient_medical_history_val['ht'] = $patient_medical_history_val['ht'];
                                                               }

                                                         ?>
                                                               <tr name="patient_medical_history_row" id="<?php echo $i; ?>">
                                                                  <td>
                                                                     <input type="checkbox" class="part_checkbox_medical_history booked_checkbox" name="patient_medical_history[]" value="<?php echo $i; ?>">
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
                                                         } else { ?>
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
                                                         <input class="full-width" id="obestetric_g" name="obestetric_g" type="text">
                                                      </div>
                                                   </div>
                                                   <div class="row m-b-5">
                                                      <div class="col-md-5">
                                                         <label>P</label>
                                                      </div>
                                                      <div class="col-md-7">
                                                         <input class="full-width" id="obestetric_p" name="obestetric_p" type="text">
                                                      </div>
                                                   </div>
                                                   <div class="row m-b-5">
                                                      <div class="col-md-5">
                                                         <label>L</label>
                                                      </div>
                                                      <div class="col-md-7">
                                                         <input class="full-width" id="obestetric_l" name="obestetric_l" type="text">
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
                                                         <button type="button" onclick="add_patient_obestetric_history_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="col-md-8 p-r-0">
                                                   <div class="table-box">
                                                      <table class="table table-bordered" id='patient_obestetric_history_list'>
                                                         <thead>
                                                            <tr>
                                                               <th align="center" width="">
                                                                  <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_obestetric_history(this);">
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
                                                         if (!empty($patient_obestetric_history_data)) {
                                                            $i = 1;
                                                            foreach ($patient_obestetric_history_data as $patient_obestetric_history_val) {
                                                         ?>
                                                               <tr name="patient_obestetric_history_row" id="<?php echo $i; ?>">
                                                                  <td>
                                                                     <input type="checkbox" class="part_checkbox_obestetric_history booked_checkbox" name="patient_obestetric_history[]" value="<?php echo $i; ?>">
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
                                                         } else { ?>
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
                                                      <div class="table-responsive">
                                                         <table class="table table-bordered table-striped" id="prescription_name_table">
                                                            <tbody>
                                                               <tr>
                                                                  <?php
                                                                  $m = 0;

                                                                  foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                                                                     <td <?php if ($m = 0) { ?> class="text-left" <?php } ?>><?php if (!empty($med_value->setting_value)) {
                                                                                                                              echo $med_value->setting_value;
                                                                                                                           } else {
                                                                                                                              echo $med_value->var_title;
                                                                                                                           } ?></td>
                                                                  <?php
                                                                     $m++;
                                                                  }
                                                                  ?>
                                                                  <td width="80">
                                                                     <a href="javascript:void(0)" class="btn-w-60 addprescriptionrow">Add</a>
                                                                  </td>
                                                               </tr>
                                                               <?php

                                                               if (!empty($medicine_patient_template_data)) {
                                                                  $l = 1;
                                                                  foreach ($medicine_patient_template_data as $prescription_presc) {

                                                               ?>
                                                                     <tr>
                                                                        <?php
                                                                        foreach ($prescription_medicine_tab_setting as $tab_value) {
                                                                           if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                                                        ?>
                                                                              <td>
                                                                                 <input type="text" name="prescription_history[<?php echo $l; ?>][medicine_name]" class=" medicine_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>">
                                                                                 <input type="hidden" name="medicine_id[]" id="medicine_id<?php echo $l; ?>" value="<?php echo $prescription_presc->id; ?>">
                                                                              </td>
                                                                           <?php
                                                                           }

                                                                           if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                                                           ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_brand]" class="" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                                                                           <?php
                                                                           }

                                                                           if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                                                           ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_salt]" id="salt<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                                                                           <?php
                                                                           }

                                                                           if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_type]" id="type<?php echo $l; ?>" class=" medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                                                                           <?php
                                                                           }
                                                                           if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_dose]" class=" dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                                                                           <?php
                                                                           }
                                                                           if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_duration]" class=" medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                                                                           <?php
                                                                           }
                                                                           if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                           ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_frequency]" class=" medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                                                                           <?php
                                                                           }
                                                                           if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                           ?>
                                                                              <td><input type="text" name="prescription_history[<?php echo $l; ?>][medicine_advice]" class=" medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
                                                                        <?php }
                                                                        }
                                                                        ?>
                                                                        <script type="text/javascript">
                                                                           /* script start */
                                                                           $(function() {
                                                                              var getData = function(request, response) {
                                                                                 row = <?php echo $l; ?>;
                                                                                 $.ajax({
                                                                                    url: "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
                                                                                    dataType: "json",
                                                                                    method: 'post',
                                                                                    data: {
                                                                                       name_startsWith: request.term,
                                                                                       type: 'country_table',
                                                                                       row_num: row
                                                                                    },
                                                                                    success: function(data) {
                                                                                       response($.map(data, function(item) {
                                                                                          var code = item.split("|");
                                                                                          return {
                                                                                             label: code[0],
                                                                                             value: code[0],
                                                                                             data: item
                                                                                          }
                                                                                       }));
                                                                                    }
                                                                                 });


                                                                              };

                                                                              var selectItem = function(event, ui) {

                                                                                 var names = ui.item.data.split("|");

                                                                                 $('.medicine_val' + <?php echo $l; ?>).val(names[0]);
                                                                                 $('#type' + <?php echo $l; ?>).val(names[1]);
                                                                                 $('#brand' + <?php echo $l; ?>).val(names[2]);
                                                                                 $('#salt' + <?php echo $l; ?>).val(names[3]);
                                                                                 $('#medicine_id' + <?php echo $l; ?>).val(names[4]);
                                                                                 //$(".medicine_val").val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".medicine_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });

                                                                           $(function() {
                                                                              var getData = function(request, response) {

                                                                                 $.getJSON(
                                                                                    "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                                                                                    function(data) {
                                                                                       response(data);
                                                                                    });
                                                                              };

                                                                              var selectItem = function(event, ui) {
                                                                                 $(".medicine_type_val" + <?php echo $l; ?>).val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".medicine_type_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });

                                                                           $(function() {
                                                                              var getData = function(request, response) {
                                                                                 $.getJSON(
                                                                                    "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                                                                                    function(data) {
                                                                                       response(data);
                                                                                    });
                                                                              };

                                                                              var selectItem = function(event, ui) {
                                                                                 $(".dosage_val" + <?php echo $l; ?>).val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".dosage_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });

                                                                           $(function() {
                                                                              var getData = function(request, response) {
                                                                                 $.getJSON(
                                                                                    "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                                                                                    function(data) {
                                                                                       response(data);
                                                                                    });
                                                                              };

                                                                              var selectItem = function(event, ui) {
                                                                                 $(".duration_val" + <?php echo $l; ?>).val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".duration_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });
                                                                           $(function() {
                                                                              var getData = function(request, response) {
                                                                                 $.getJSON(
                                                                                    "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                                                                                    function(data) {
                                                                                       response(data);
                                                                                    });
                                                                              };

                                                                              var selectItem = function(event, ui) {
                                                                                 $(".frequency_val" + <?php echo $l; ?>).val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".frequency_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });

                                                                           $(function() {
                                                                              var getData = function(request, response) {
                                                                                 $.getJSON(
                                                                                    "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                                                                                    function(data) {
                                                                                       response(data);
                                                                                    });
                                                                              };

                                                                              var selectItem = function(event, ui) {
                                                                                 $(".advice_val" + <?php echo $l; ?>).val(ui.item.value);
                                                                                 return false;
                                                                              }

                                                                              $(".advice_val" + <?php echo $l; ?>).autocomplete({
                                                                                 source: getData,
                                                                                 select: selectItem,
                                                                                 minLength: 1,
                                                                                 change: function() {

                                                                                 }
                                                                              });
                                                                           });
                                                                           /* script end*/
                                                                           function delete_prescr_row(r) {
                                                                              var i = r.parentNode.parentNode.rowIndex;
                                                                              document.getElementById("prescription_name_table").deleteRow(i);
                                                                           }
                                                                        </script>
                                                                        <td width="80">
                                                                           <a onclick="delete_prescr_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                                        </td>
                                                                     </tr>
                                                                  <?php $l++;
                                                                  }
                                                               } else { ?>
                                                                  <tr>
                                                                     <?php
                                                                     foreach ($prescription_medicine_tab_setting as $tab_value) {
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                                                     ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_name]" class="medicine_val">
                                                                              <input type="hidden" name="medicine_id[]" id="medicine_id">
                                                                           </td>
                                                                        <?php
                                                                        }
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                                                        ?>
                                                                           <td><input type="text" id="brand0" name="prescription_history[1][medicine_brand]" class=""></td>
                                                                        <?php
                                                                        }

                                                                        if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                                                        ?>
                                                                           <td><input type="text" id="salt0" name="prescription_history[1][medicine_salt]" class=""></td>
                                                                        <?php
                                                                        }

                                                                        if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_type]" class="" id="type0" onkeyup="get_medicine_type_autocomplete(0,1);"></td>
                                                                        <?php
                                                                        }
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_dose]" class="input-smal" id="dose0" onkeyup="get_medicine_dose_autocomplete(0,1);"></td>
                                                                        <?php
                                                                        }
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_duration]" class="medicine-ame" id="duration0" onkeyup="get_medicine_duration_autocomplete(0,1);"></td>
                                                                        <?php
                                                                        }
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                        ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_frequency]" class="medicine-ame" id="frequency0" onkeyup="get_medicine_frequency_autocomplete(0,1);"></td>
                                                                        <?php
                                                                        }
                                                                        if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                        ?>
                                                                           <td><input type="text" name="prescription_history[1][medicine_advice]" class="medicine-ame" id="advice0" onkeyup="get_medicine_advice_autocomplete(0,1);"></td>
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
                     if (strcmp(strtolower($value->setting_name), 'disease') == '0') { ?>
               <div id="tab_disease" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>" style="border:1px solid #ccc;">
                  <?php
                        $patient_disease_data = $this->session->userdata('patient_disease_data');
                        $row_count_patient_disease = count($patient_disease_data) + 1;
                  ?>
                  <input type="hidden" value="<?php echo $row_count_patient_disease; ?>" name="row_id_patient_disease" id="row_id_patient_disease">
                  <!-- <div class="row">
                     <div class="col-md-12 tab-content dental-chart"> -->
                  <div class="row tab-pane fade in active" id="chief">
                     <div class="col-md-5">
                        <div class="box-left">
                           <div class="row">
                              <div class="col-md-4">
                                 <label>Disease Name</label>
                              </div>
                              <div class="col-md-6">
                                 <select name="patient_disease_name" class="w-150px m_select_btn" id="patient_disease_id">
                                    <option value="">Select Disease</option>
                                    <?php
                                    if (!empty($disease_list)) {
                                       foreach ($disease_list as $disease) {
                                    ?>
                                          <option <?php if ($form_data['disease_id'] == $disease->id) {
                                                      echo 'selected="selected"';
                                                   } ?> value="<?php echo $disease->id; ?>"><?php echo $disease->disease_name; ?></option>
                                    <?php
                                       }
                                    }
                                    ?>
                                 </select>
                              </div>
                              <div class="col-md-2">
                                 <a href="javascript:void(0)" onclick="return add_disease();" class="btn-new"><i class="">New</i></a>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-4">
                                 <label>Duration</label>
                              </div>
                              <div class="col-md-8">
                                 <input type="text" class="numeric" name="patient_disease_unit" id='patient_disease_unit' style="width:50px;">
                                 <select name="patient_disease_type" class="m_select_btn" id="patient_disease_type" style="width:164px;">
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
                     <div class="col-md-7">
                        <div class="table-box">
                           <table class="table table-bordered" id='patient_disease_list'>
                              <thead>
                                 <tr>
                                    <th align="center" width="">
                                       <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_disease(this);">
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
                              if (!empty($patient_disease_data)) { //print_r($patient_disease_data);die;
                                 $i = 1;
                                 foreach ($patient_disease_data as $patient_disease_val) {
                                    if (strpos($patient_disease_val['patient_disease_type'], 'Select') !== false) {
                                       $patient_disease_val['patient_disease_type'] = "";
                                    } else {
                                       $patient_disease_val['patient_disease_type'] = $patient_disease_val['patient_disease_type'];
                                    }
                              ?>
                                    <tr name="patient_disease_row" id="<?php echo $i; ?>">
                                       <input type="hidden" id="unique_id_patient_disease" name="unique_id_patient_disease" value="<?php echo $i; ?>">
                                       <td>
                                          <input type="checkbox" class="part_checkbox_disease booked_checkbox" name="patient_disease_name[]" value="<?php echo $i; ?>">
                                       </td>
                                       <td><?php echo $i; ?></td>
                                       <td><?php echo $patient_disease_val['disease_value']; ?></td>
                                       <td><?php echo $patient_disease_val['patient_disease_unit'] . ' ' . $patient_disease_val['patient_disease_type']; ?></td>
                                       <td><?php echo $patient_disease_val['disease_description']; ?></td>
                                    </tr>
                                 <?php
                                    $i++;
                                 }
                              } else { ?>
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
                              <?php //} 
                              ?>
                           </table>
                        </div>
                     </div>
                  </div>
                  <!-- row -->
               </div>
            </div>

         <?php } ?>

         


         <?php
                     if (strcmp(strtolower($value->setting_name), 'complaints') == '0') { ?>
            <div id="tab_complaints" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
               <?php
                        $patient_complaint_data = $this->session->userdata('patient_complaint_data');
                        $row_count_patient_complaint = count($patient_complaint_data) + 1;
               ?>
               <input type="hidden" value="<?php echo $row_count_patient_complaint; ?>" name="row_id_patient_complaint" id="row_id_patient_complaint">
               <div class="row">
                  <div class="col-md-12 tab-content dental-chart">
                     <div class="row tab-pane fade in active" id="chief">
                        <div class="col-md-5">
                           <div class="box-left">
                              <div class="row">
                                 <div class="col-md-4">
                                    <label>Complaint Name</label>
                                 </div>
                                 <div class="col-md-6">
                                    <select name="patient_complaint_name" class="w-150px m_select_btn" id="patient_complaint_id">
                                       <option value="">Select Complaint</option>
                                       <?php
                                       if (!empty($complaint_list)) {
                                          foreach ($complaint_list as $complaint) {
                                       ?>
                                             <option <?php if ($form_data['disease_id'] == $complaint->id) {
                                                         echo 'selected="selected"';
                                                      } ?> value="<?php echo $complaint->id; ?>"><?php echo $complaint->complaints; ?></option>
                                       <?php
                                          }
                                       }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="col-md-2">
                                    <a href="javascript:void(0)" onclick="return add_complaint();" class="btn-new"><i class="">New</i></a>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-4">
                                    <label>Duration</label>
                                 </div>
                                 <div class="col-md-8">
                                    <input type="text" class="numeric" name="patient_complaint_unit" id='patient_complaint_unit' style="width:50px;">
                                    <select name="patient_complaint_type" class="m_select_btn" id="patient_complaint_type" style="width:165px;">
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
                        <div class="col-md-7">
                           <div class="table-box">
                              <table class="table table-bordered" id='patient_complaint_list'>
                                 <thead>
                                    <tr>
                                       <th align="center" width="">
                                          <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_complaints(this);">
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
                                 if (!empty($patient_complaint_data)) {
                                    $i = 1;
                                    foreach ($patient_complaint_data as $patient_complaint_val) {
                                       if (strpos($patient_complaint_val['patient_complaint_type'], 'Select') !== false) {
                                          $patient_complaint_val['patient_complaint_type'] = "";
                                       } else {
                                          $patient_complaint_val['patient_complaint_type'] = $patient_complaint_val['patient_complaint_type'];
                                       }
                                 ?>
                                       <tr name="patient_complaint_row" id="<?php echo $i; ?>">
                                          <td id="<?php //echo $patient_complaint_val['disease_id']; 
                                                   ?>">
                                             <input type="checkbox" class="part_checkbox_complaints booked_checkbox" name="patient_complaint_name[]" value="<?php echo $i; ?>">
                                          </td>
                                          <input type="hidden" id="unique_id_patient_complaint" name="unique_id_patient_complaint" value="<?php echo $i; ?>">
                                          <td><?php echo $i; ?></td>
                                          <td><?php echo $patient_complaint_val['complaint_value']; ?></td>
                                          <td><?php echo $patient_complaint_val['patient_complaint_unit'] . ' ' . $patient_complaint_val['patient_complaint_type']; ?></td>
                                          <td><?php echo $patient_complaint_val['complaint_description']; ?></td>
                                       </tr>
                                    <?php
                                       $i++;
                                    }
                                 } else { ?>
                                    <tr>
                                       <td colspan="5" align="center" class=" text-danger ">
                                          <div class="text-center">Compaints not available.</div>
                                       </td>
                                    </tr>
                                 <?php
                                 }
                                 ?>
                                 <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_complaint_vals();">
                                    <i class="fa fa-trash"></i> Delete
                                 </a>
                                 <?php //} 
                                 ?>
                              </table>
                           </div>
                        </div>
                     </div> <!-- row -->
                  </div>
               </div>
            </div>
   </div>

<?php  }  ?>





<?php
                     if (strcmp(strtolower($value->setting_name), 'allergy') == '0') { ?>
   <div id="tab_allergy" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <?php
                        $patient_allergy_data = $this->session->userdata('patient_allergy_data');
                        $row_count_patient_allergy = count($patient_allergy_data) + 1;
      ?>
      <input type="hidden" value="<?php echo $row_count_patient_allergy; ?>" name="row_id_patient_allergy" id="row_id_patient_allergy">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
                  <div class="box-left">
                     <div class="row">
                        <div class="col-md-4">
                           <label>Allergy Name</label>
                        </div>
                        <div class="col-md-6">
                           <select name="patient_allergy_name" class="w-150px m_select_btn" id="patient_allergy_id">
                              <option value="">Select Allergy</option>
                              <?php
                              if (!empty($allergy_list)) {
                                 foreach ($allergy_list as $allergy) {
                              ?>
                                    <option <?php if ($form_data['disease_id'] == $allergy->id) {
                                                echo 'selected="selected"';
                                             } ?> value="<?php echo $allergy->id; ?>"><?php echo $allergy->allergy_name; ?></option>
                              <?php
                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-md-2">
                           <a href="javascript:void(0)" onclick="return add_allergy();" class="btn-new"><i class="">New</i></a>
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
               <div class="col-md-7">
                  <div class="table-box">
                     <table class="table table-bordered" id='patient_allergy_list'>
                        <thead>
                           <tr>
                              <th align="center" width="">
                                 <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_allergy(this);">
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
                        if (!empty($patient_allergy_data)) {
                           $i = 1;
                           foreach ($patient_allergy_data as $patient_allergy_val) {
                              if (strpos($patient_allergy_val['patient_allergy_type'], 'Select') !== false) {
                                 $patient_allergy_val['patient_allergy_type'] = "";
                              } else {
                                 $patient_allergy_val['patient_allergy_type'] = $patient_allergy_val['patient_allergy_type'];
                              }
                        ?>
                              <tr name="patient_allergy_row" id="<?php echo $i; ?>">
                                 <td id="<?php //echo $patient_allergy_val['disease_id']; 
                                          ?>">
                                    <input type="checkbox" class="part_checkbox_allergy booked_checkbox" name="patient_allergy_name[]" value="<?php echo $i; ?>">
                                 </td>
                                 <input type="hidden" id="unique_id_patient_allergy" name="unique_id_patient_allergy" value="<?php echo $i; ?>">
                                 <td><?php echo $i; ?></td>
                                 <td><?php echo $patient_allergy_val['allergy_value']; ?></td>
                                 <td><?php echo $patient_allergy_val['patient_allergy_unit'] . ' ' . $patient_allergy_val['patient_allergy_type']; ?></td>
                                 <td><?php echo $patient_allergy_val['allergy_description']; ?></td>
                              </tr>
                           <?php
                              $i++;
                           }
                        } else { ?>
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
                        <?php //} 
                        ?>
                     </table>
                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>

<?php  }  ?>





<?php
                     if (strcmp(strtolower($value->setting_name), 'general_examination') == '0') { ?>
   <div id="tab_general_examination" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <?php
                        $patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
                        $row_count_general_examination = count($patient_general_examination_data) + 1;
      ?>
      <input type="hidden" value="<?php echo $row_count_general_examination; ?>" name="row_id_general_examination" id="row_id_general_examination">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
                  <div class="box-left">
                     <div class="row">
                        <div class="col-md-4">
                           <label>Exam Name</label>
                        </div>
                        <div class="col-md-6">
                           <select name="patient_general_examination_name" class="w-150px m_select_btn" id="patient_general_examination_id">
                              <option value="">Select Exam</option>
                              <?php
                              if (!empty($general_examination_list)) {
                                 foreach ($general_examination_list as $general_examination) {
                              ?>
                                    <option <?php if ($form_data['disease_id'] == $general_examination->id) {
                                                echo 'selected="selected"';
                                             } ?> value="<?php echo $general_examination->id; ?>"><?php echo $general_examination->general_examination; ?></option>
                              <?php
                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-md-2">
                           <a href="javascript:void(0)" onclick="return add_general_examination();" class="btn-new"><i class="">New</i></a>
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
               <div class="col-md-7">
                  <div class="table-box">
                     <table class="table table-bordered" id='patient_general_examination_list'>
                        <thead>
                           <tr>
                              <th align="center" width="">
                                 <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_general_examination(this);">
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
                        if (!empty($patient_general_examination_data)) {
                           $i = 1;
                           foreach ($patient_general_examination_data as $patient_general_examination_val) {

                        ?>
                              <tr name="patient_general_examination_row" id="<?php echo $i; ?>">
                                 <td id="<?php //echo $patient_general_examination_val['disease_id']; 
                                          ?>">
                                    <input type="checkbox" class="part_checkbox_general_examination booked_checkbox" name="patient_general_examination_name[]" value="<?php echo $i; ?>">
                                 </td>
                                 <input type="hidden" id="unique_id_patient_general_examination" name="unique_id_patient_general_examination" value="<?php echo $i; ?>">
                                 <td><?php echo $i; ?></td>
                                 <td><?php echo $patient_general_examination_val['general_examination_value']; ?></td>
                                 <td><?php echo $patient_general_examination_val['general_examination_description']; ?></td>
                              </tr>
                           <?php
                              $i++;
                           }
                        } else { ?>
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
                        <?php //} 
                        ?>
                     </table>
                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>
<?php  }  ?>




<?php
                     if (strcmp(strtolower($value->setting_name), 'clinical_examination') == '0') { ?>
   <div id="tab_clinical_examination" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <?php
                        $patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
                        $row_count_clinical_examination = count($patient_clinical_examination_data) + 1;
      ?>
      <input type="hidden" value="<?php echo $row_count_clinical_examination; ?>" name="row_id_clinical_examination" id="row_id_clinical_examination">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
                  <div class="box-left">
                     <div class="row">
                        <div class="col-md-4">
                           <label>Exam Name</label>
                        </div>
                        <div class="col-md-6">
                           <select name="patient_clinical_examination_name" class="w-150px m_select_btn" id="patient_clinical_examination_id">
                              <option value="">Select Exam</option>
                              <?php
                              if (!empty($clinical_examination_list)) {
                                 foreach ($clinical_examination_list as $clinical_examination) {
                              ?>
                                    <option <?php if ($form_data['disease_id'] == $clinical_examination->id) {
                                                echo 'selected="selected"';
                                             } ?> value="<?php echo $clinical_examination->id; ?>"><?php echo $clinical_examination->clinical_examination; ?></option>
                              <?php
                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-md-2">
                           <a href="javascript:void(0)" onclick="return add_clinical_examination();" class="btn-new"><i class="">New</i></a>
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
                           <button type="button" onclick="add_patient_clinical_examination_listdata();" class="theme-color add-btn" style="float: right;">Add</button>
                        </div>
                        <div class="col-md-2">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class="table-box">
                     <table class="table table-bordered" id='patient_clinical_examination_list'>
                        <thead>
                           <tr>
                              <th align="center" width="">
                                 <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_clinical_examination(this);">
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
                        if (!empty($patient_clinical_examination_data)) {
                           $i = 1;
                           foreach ($patient_clinical_examination_data as $patient_clinical_examination_val) {

                        ?>
                              <tr name="patient_clinical_examination_row" id="<?php echo $i; ?>">
                                 <td id="<?php //echo $patient_clinical_examination_val['disease_id']; 
                                          ?>">
                                    <input type="checkbox" class="part_checkbox_clinical_examination booked_checkbox" name="patient_clinical_examination_name[]" value="<?php echo $i; ?>">
                                 </td>
                                 <input type="hidden" id="unique_id_patient_clinical_examination" name="unique_id_patient_clinical_examination" value="<?php echo $i; ?>">
                                 <td><?php echo $i; ?></td>
                                 <td><?php echo $patient_clinical_examination_val['clinical_examination_value']; ?></td>
                                 <td><?php echo $patient_clinical_examination_val['clinical_examination_description']; ?></td>
                              </tr>
                           <?php
                              $i++;
                           }
                        } else { ?>
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
                        <?php //} 
                        ?>
                     </table>
                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>
<?php  }  ?>




<?php
                     if (strcmp(strtolower($value->setting_name), 'investigation') == '0') { ?>
   <div id="tab_investigation" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <?php
                        $patient_investigation_data = $this->session->userdata('patient_investigation_data');
                        $row_count_patient_investigation = count($patient_investigation_data) + 1;
      ?>
      <input type="hidden" value="<?php echo $row_count_patient_investigation; ?>" name="row_id_patient_investigation" id="row_id_patient_investigation">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
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
                              if (!empty($investigation_list)) {
                                 foreach ($investigation_list as $investigation) {
                              ?>
                                    <option <?php if ($form_data['disease_id'] == $investigation->id) {
                                                echo 'selected="selected"';
                                             } ?> value="<?php echo $investigation->id; ?>"><?php echo $investigation->investigation; ?></option>
                              <?php
                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-md-2">
                           <a href="javascript:void(0)" onclick="return add_investigation();" class="btn-new"><i class="">New</i></a>
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
               <div class="col-md-7">
                  <div class="table-box">
                     <table class="table table-bordered" id='patient_investigation_list'>
                        <thead>
                           <tr>
                              <th align="center" width="">
                                 <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_investigation(this);">
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
                           </tr>
                        </thead>
                        <?php
                        $i = 0;
                        if (!empty($patient_investigation_data)) {
                           $i = 1;
                           foreach ($patient_investigation_data as $patient_investigation_val) {
                              if (strpos($patient_investigation_val['investigation_value'], 'Select') !== false) {
                                 $patient_investigation_val['investigation_value'] = "";
                              } else {
                                 $patient_investigation_val['investigation_value'] = $patient_investigation_val['investigation_value'];
                              }
                        ?>
                              <tr name="patient_investigation_row" id="<?php echo $i; ?>">
                                 <td id="<?php //echo $patient_investigation_val['disease_id']; 
                                          ?>">
                                    <input type="checkbox" class="part_checkbox_investigation booked_checkbox" name="patient_investigation_name[]" value="<?php echo $i; ?>">
                                 </td>
                                 <input type="hidden" id="unique_id_patient_investigation" name="unique_id_patient_investigation" value="<?php echo $i; ?>">
                                 <td><?php echo $i; ?></td>
                                 <td><?php echo $patient_investigation_val['investigation_value']; ?></td>
                                 <td><?php echo $patient_investigation_val['std_value']; ?></td>
                                 <td><?php echo $patient_investigation_val['observed_value']; ?></td>
                              </tr>
                           <?php
                              $i++;
                           }
                        } else { ?>
                           <tr>
                              <td colspan="3" align="center" class=" text-danger ">
                                 <div class="text-center">Investigation not available.</div>
                              </td>
                           </tr>
                        <?php
                        }
                        ?>
                        <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="delete_patient_investigation_vals();">
                           <i class="fa fa-trash"></i> Delete
                        </a>
                        <?php //} 
                        ?>
                     </table>
                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>
<?php  }  ?>






<?php
                     if (strcmp(strtolower($value->setting_name), 'medicine') == '0') { ?>
   <div id="tab_medicine" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <div class="row m-t-10">
         <div class="col-xs-12">
            <div class="well tab-right-scroll">
               <div class="table-responsive">
                  <table class="table table-bordered table-striped" id="prescription_name_table_patient">
                     <tbody>
                        <tr>
                           <?php
                           $m = 0;

                           foreach ($prescription_medicine_tab_setting as $med_value) { ?>
                              <td <?php if ($m = 0) { ?> class="text-left" <?php } ?>><?php if (!empty($med_value->setting_value)) {
                                                                                       echo $med_value->setting_value;
                                                                                    } else {
                                                                                       echo $med_value->var_title;
                                                                                    } ?></td>
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
                        if (!empty($medicine_template_data)) {
                           $l = 1;
                           foreach ($medicine_template_data as $prescription_presc) {

                        ?>
                              <tr>
                                 <?php
                                 foreach ($prescription_medicine_tab_setting as $tab_value) {
                                    if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                 ?>
                                       <td>
                                          <input type="text" name="prescription[<?php echo $l; ?>][medicine_name]" class=" medicine_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>">
                                          <input type="hidden" name="medicine_id[]" id="medicine_id_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->id; ?>">
                                       </td>
                                    <?php
                                    }

                                    if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                    ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_brand]" class="" id="brand_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                                    <?php
                                    }

                                    if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                    ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_salt]" id="salt_patient<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                                    <?php
                                    }

                                    if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_type]" id="type_patient<?php echo $l; ?>" class=" medicine_type_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                                    <?php
                                    }
                                    if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_dose]" class=" dosage_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                                    <?php
                                    }
                                    if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_duration]" class=" medicine-name duration_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                                    <?php
                                    }
                                    if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                    ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_frequency]" class=" medicine-name frequency_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                                    <?php
                                    }
                                    if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                    ?>
                                       <td><input type="text" name="prescription[<?php echo $l; ?>][medicine_advice]" class=" medicine-name advice_val_patient<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
                                 <?php }
                                 }
                                 ?>
                                 <script type="text/javascript">
                                    /* script start */
                                    $(function() {
                                       var getData = function(request, response) {
                                          row = <?php echo $l; ?>;
                                          $.ajax({
                                             url: "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
                                             dataType: "json",
                                             method: 'post',
                                             data: {
                                                name_startsWith: request.term,
                                                type: 'country_table',
                                                row_num: row
                                             },
                                             success: function(data) {
                                                response($.map(data, function(item) {
                                                   var code = item.split("|");
                                                   return {
                                                      label: code[0],
                                                      value: code[0],
                                                      data: item
                                                   }
                                                }));
                                             }
                                          });


                                       };

                                       var selectItem = function(event, ui) {

                                          var names = ui.item.data.split("|");

                                          $('.medicine_val_patient' + <?php echo $l; ?>).val(names[0]);

                                          $('#type_patient' + <?php echo $l; ?>).val(names[1]);
                                          $('#brand_patient' + <?php echo $l; ?>).val(names[2]);
                                          $('#salt_patient' + <?php echo $l; ?>).val(names[3]);
                                          $('#medicine_id_patient' + <?php echo $l; ?>).val(names[4]);
                                          //$(".medicine_val").val(ui.item.value);
                                          return false;
                                       }

                                       $(".medicine_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });

                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".medicine_type_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".medicine_type_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });

                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".dosage_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".dosage_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });

                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".dosage_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".dosage_val" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });

                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".duration_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".duration_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });
                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".frequency_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".frequency_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });

                                    $(function() {
                                       var getData = function(request, response) {
                                          $.getJSON(
                                             "<?php echo base_url('gynecology/gynecology_prescription/get_gynecologyl_advice_vals/'); ?>" + request.term,
                                             function(data) {
                                                response(data);
                                             });
                                       };

                                       var selectItem = function(event, ui) {
                                          $(".advice_val_patient" + <?php echo $l; ?>).val(ui.item.value);
                                          return false;
                                       }

                                       $(".advice_val_patient" + <?php echo $l; ?>).autocomplete({
                                          source: getData,
                                          select: selectItem,
                                          minLength: 1,
                                          change: function() {

                                          }
                                       });
                                    });
                                    /* script end*/
                                    function delete_prescr_row_patient(r) {
                                       var i = r.parentNode.parentNode.rowIndex;
                                       document.getElementById("prescription_name_table_patient").deleteRow(i);
                                    }
                                 </script>
                                 <td width="80">
                                    <a onclick="delete_prescr_row_patient(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                 </td>
                              </tr>
                           <?php $l++;
                           }
                        } else { ?>
                           <tr>
                              <?php
                              foreach ($prescription_medicine_tab_setting as $tab_value) {
                                 if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                              ?>
                                    <td><input type="text" name="prescription[1][medicine_name]" class="medicine_val_patient">
                                       <input type="hidden" name="medicine_id[]" id="medicine_id_patient">
                                    </td>
                                 <?php
                                 }
                                 if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                 ?>
                                    <td><input type="text" id="brand_patient0" name="prescription[1][medicine_brand]" class=""></td>
                                 <?php
                                 }

                                 if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                 ?>
                                    <td><input type="text" id="salt_patient0" name="prescription[1][medicine_salt]" class=""></td>
                                 <?php
                                 }

                                 if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>
                                    <td><input type="text" name="prescription[1][medicine_type]" class="input-smal" id="type_patient0" onkeyup="get_medicine_type_autocomplete(0,2);"></td>
                                 <?php
                                 }
                                 if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?>
                                    <td><input type="text" name="prescription[1][medicine_dose]" class="input-smal" id="dose_patient0" onkeyup="get_medicine_dose_autocomplete(0,2);"></td>
                                 <?php
                                 }
                                 if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>
                                    <td><input type="text" name="prescription[1][medicine_duration]" class="medicine-nae" id="duration_patient0" onkeyup="get_medicine_duration_autocomplete(0,2);"></td>
                                 <?php
                                 }
                                 if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                 ?>
                                    <td><input type="text" name="prescription[1][medicine_frequency]" class="medicine-nme" id="frequency_patient0" onkeyup="get_medicine_frequency_autocomplete(0,2);"></td>
                                 <?php
                                 }
                                 if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                 ?>
                                    <td><input type="text" name="prescription[1][medicine_advice]" class="medicine-nme" id="advice_patient0" onkeyup="get_medicine_advice_autocomplete(0,2);"></td>
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
                     if (strcmp(strtolower($value->setting_name), 'advice') == '0') { ?>
   <div id="tab_advice" class="inner_tab_box tab-pane fade  <?php if ($j == 1) { ?> in active <?php  } ?>">
      <?php
                        $patient_advice_data = $this->session->userdata('patient_advice_data');
                        $row_count_patient_advice = count($patient_advice_data) + 1;
      ?>
      <input type="hidden" value="<?php echo $row_count_patient_advice; ?>" name="row_id_patient_advice" id="row_id_patient_advice">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
                  <div class="box-left">
                     <div class="row">
                        <div class="col-md-4">
                           <label>Advice</label>
                        </div>
                        <div class="col-md-6">
                           <select name="patient_advice_name" class="w-150px m_select_btn" id="patient_advice_id">
                              <option value="">Select Advice</option>
                              <?php

                              if (!empty($advice_list)) {
                                 foreach ($advice_list as $advice) {
                              ?>
                                    <option value="<?php echo $advice->id; ?>"><?php echo $advice->advice; ?></option>
                              <?php
                                 }
                              }
                              ?>
                           </select>
                        </div>
                        <div class="col-md-2">
                           <a href="javascript:void(0)" onclick="return add_advice();" class="btn-new"><i class="">New</i></a>
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
               <div class="col-md-7">
                  <div class="table-box">
                     <table class="table table-bordered" id='patient_advice_list'>
                        <thead>
                           <tr>
                              <th align="center" width="">
                                 <input name="selectall" class="" id="selectall" value="" type="checkbox" onclick="toggle_advice(this);">
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
                        if (!empty($patient_advice_data)) {
                           $i = 1;
                           foreach ($patient_advice_data as $patient_advice_val) {
                              if (strpos($patient_advice_val['advice_value'], 'Select') !== false) {
                                 $patient_advice_val['advice_value'] = "";
                              } else {
                                 $patient_advice_val['advice_value'] = $patient_advice_val['advice_value'];
                              }
                        ?>
                              <tr name="patient_advice_row" id="<?php echo $i; ?>">
                                 <td id="<?php //echo $patient_advice_val['disease_id']; 
                                          ?>">
                                    <input type="checkbox" class="part_checkbox_advice booked_checkbox" name="patient_advice_name[]" value="<?php echo $i; ?>">
                                 </td>
                                 <input type="hidden" id="unique_id_patient_advice" name="unique_id_patient_advice" value="<?php echo $i; ?>">
                                 <td><?php echo $i; ?></td>
                                 <td><?php echo $patient_advice_val['advice_value']; ?></td>
                              </tr>
                           <?php
                              $i++;
                           }
                        } else { ?>
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
                        <?php //} 
                        ?>
                     </table>
                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>
<?php  }  ?>


<?php 
                  if(strcmp(strtolower($value->setting_name),'gpla')=='0'){?>
               <div id="tab_gpla" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                  <?php 
                     if(!isset($patient_gpla_data) || empty($patient_gpla_data)){   
                      $patient_gpla_data = $this->session->userdata('patient_gpla_data');
                     }
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



<?php if (strcmp(strtolower($value->setting_name), 'next_appointment') == '0') {   ?>
   <div id="tab_next_appointment" class="inner_tab_box tab-pane fade <?php if ($j == 1) { ?> in active <?php  } ?>">
      <div class="row">
         <div class="col-md-12 tab-content dental-chart">
            <div class="row tab-pane fade in active" id="chief">
               <div class="col-md-5">
                  <div class="box-left">
                     <div class="row">
                        <div class="col-md-4">
                           <label>Next Appointment</label>
                        </div>
                        <div class="col-md-6">
                           <input type="hidden" name="check_appointment" <?php if ($form_data['check_appointment'] == 1) {
                                                                              echo 'checked="checked"';
                                                                           } ?> value="<?php echo $form_data['check_appointment']; ?>">
                           <input type="checkbox" name="check_appointment" value="1" <?php if ($form_data['check_appointment'] == 1) {
                                                                                          echo 'checked="checked"';
                                                                                       } ?> id='check_appointment'>
                        </div>
                     </div>
                     <?php $display = '';
                        if ($form_data['check_appointment'] != NULL) {
                           if ($form_data['check_appointment'] == 1) {
                              $display = 'display: block';
                           } else {
                              $display = 'display: none';
                           }
                        } else {
                           $display = 'display: none';
                        }
                     ?>
                     <div class="row" id="date_time_next" style="<?php echo $display; ?>">
                        <div class="col-md-4">
                           <label>Appointment Date Time</label>
                        </div>
                        <div class="col-md-6">
                           <input type="text" name="next_appointment_date" id="next_appointment_date" class="datepickertime date " data-date-format="dd-mm-yyyy HH:ii" value="<?php echo $form_data['next_appointment_date']; ?>" readonly />
                        </div>
                     </div>

                  </div>
               </div>
            </div> <!-- row -->
         </div>
      </div>
   </div>
   </div>
<?php } ?>




<?php
                     $j++;
                  } ?>
</div>


<div class="col-md-1 btn-box text-right">
   <div class="prescription_btns">
      <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
      <button class="btn-save" type="button" name="" data-dismiss="modal" onclick="window.location.href='<?php echo base_url('gynecology/patient_template'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
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
   function check_marriage_status(value) {
      if (value == "No") {
         $("#marriage_columns").css("display", "none");
         $('#married_life_unit').val('');
         $('#married_life_type option:selected').removeAttr("selected");
         $('#marriage_no').val('');
         $('#marriage_details').val('');
         $('#previous_delivery option:selected').removeAttr("selected");
         $('#delivery_type option:selected').removeAttr("selected");
         $('#delivery_details').val('');
      } else {
         $("#marriage_columns").css("display", "block");
      }
   }

   function check_previous_delivery(value) {
      if (value == "No") {
         $("#check_previous_delivery").css("display", "none");
         $('#delivery_type option:selected').removeAttr("selected");
         $('#delivery_details').val('');
      } else {
         $("#check_previous_delivery").css("display", "block");
      }
   }

   function check_br_discharge(value) {
      if (value == "No") {
         $("#check_br_discharge").css("display", "none");
         $('#side option:selected').removeAttr("selected");
      } else {
         $("#check_br_discharge").css("display", "block");
      }
   }

   function check_white_discharge(value) {
      if (value == "No") {
         $("#check_white_discharge").css("display", "none");
         $('#type option:selected').removeAttr("selected");
      } else {
         $("#check_white_discharge").css("display", "block");
      }
   }

   function check_dysmenorrhea(value) {
      if (value == "No") {
         $("#check_dysmenorrhea").css("display", "none");
         $('#dysmenorrhea_type option:selected').removeAttr("selected");
      } else {
         $("#check_dysmenorrhea").css("display", "block");
      }
   }

   function load_values(jdata) {
      var obj = JSON.parse(jdata);
      var check_appointment = obj.check_appointment;
      $('#check_appointment').val(obj.check_appointment);
      if ((check_appointment != '') && (check_appointment == 1)) {
         $("#check_appointment").prop("checked", true);

         $('#date_time_next').show();
      } else {
         $("#check_appointment").prop("checked", false);

         $('#date_time_next').hide();
      }
      $('#next_appointment_date').val(obj.appointment_date);
   };


   function delete_prescription_medicine(val, temp_id) {

      var prescription_medicine_id = val;
      var templ_id = temp_id
      $.ajax({
         url: "<?php echo base_url(); ?>eye/prescription_template/delete_pres_medicine/" + prescription_medicine_id + '/' + templ_id,
         success: function(result) {
            //alert(result); return;
            $("#tab_prescription").append(result);
         }
      });

   }

   function tab_links(vals) {

      $('.inner_tab_box').removeClass('in');
      $('.inner_tab_box').removeClass('active');
      $('#' + vals).addClass('in');
      $('#' + vals).addClass('active');
   }




   function remove_row(row_val) {

      $("#chief_complaints").on('click', '.btnDelete', function() {
         $(this).closest('tr').remove();

      });
      $(".chief_complaints_data option[value='" + row_val + "']").show();

   }



   $('.datepicker').datepicker({
      format: 'dd-mm-yyyy',
      autoclose: true,
   });
   $('.datepicker1').datepicker({
      format: 'dd-mm-yyyy',
      startDate: new Date(),
      autoclose: true,
   });


   $(document).ready(function() {
      $(".addrow").click(function() {

         var i = $('#test_name_table tr').length;
         $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="w-100 test_val' + i + '"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');


         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".test_val" + i).val(ui.item.value);
               return false;
            }

            $(".test_val" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 2,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });

      });
      $("#test_name_table").on('click', '.remove_row', function() {
         $(this).parent().parent().remove();
      });

      $(".addprescriptionrow").click(function() {

         var i = $('#prescription_name_table tr').length;
         $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) {
                                                      if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                                   ?><td><input type="text" name="prescription_history[' + i + '][medicine_name]" class=" medicine_val' + i + '"><input type="hidden" name="medicine_id[]" id="medicine_id' + i + '"></td>                        <?php
                                                                                                                                                                                                                              }
                                                                                                                                                                                                                              if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                                                                                                                                                                                                                 ?>   <td><input type="text" name="prescription_history[' + i + '][medicine_brand]" id="brand' + i + '"  class="" ></td>                        <?php
                                                                                                                                                                                                                              }

                                                                                                                                                                                                                              if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                                                                                                                                               ?>  <td><input type="text" id="salt' + i + '"  name="prescription_history[' + i + '][medicine_salt]" class=""  ></td>                        <?php
                                                                                                                                                                                                                              }
                                                                                                                                                                                                                              if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>  <td><input type="text" id="type' + i + '"  name="prescription_history[' + i + '][medicine_type]" class="" id="type' + i + '" onkeyup="get_medicine_type_autocomplete(' + i + ',1);"></td>                        <?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?> <td><input type="text" name="prescription_history[' + i + '][medicine_dose]" class=" dosage_val' + i + '"></td>                        <?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>  <td><input type="text" name="prescription_history[' + i + '][medicine_duration]" class=" medicine-name duration_val' + i + '"></td>                        <?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                                                                                                                                 ?> <td><input type="text" name="prescription_history[' + i + '][medicine_frequency]" class=" medicine-name frequency_val' + i + '"></td>                        <?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                    if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                                                                                                                                 ?>  <td><input type="text" name="prescription_history[' + i + '][medicine_advice]" class=" medicine-name advice_val' + i + '"></td>                        <?php
                                                                                                                                                                                                                                    }
                                                                                                                                                                                                                                 } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>');
         /* script start */
         $(function() {
            m = 0
            var getData = function(request, response) {
               row = i;
               $.ajax({
                  url: "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
                  dataType: "json",
                  method: 'post',
                  data: {
                     name_startsWith: request.term,
                     type: 'country_table',
                     row_num: row
                  },
                  success: function(data) {
                     response($.map(data, function(item) {
                        var code = item.split("|");
                        return {
                           label: code[0],
                           value: code[0],
                           data: item
                        }
                     }));
                  }
               });


            };

            var selectItem = function(event, ui) {

               var names = ui.item.data.split("|");

               $('.medicine_val' + i).val(names[0]);
               $('#type' + i).val(names[1]);
               $('#brand' + i).val(names[3]);
               $('#salt' + i).val(names[2]);
               //$(".medicine_val").val(ui.item.value);
               return false;
            }

            $(".medicine_val" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 2,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });

         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".medicine_type" + i).val(ui.item.value);
               return false;
            }

            $(".medicine_type" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 2,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });

         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".dosage_val" + i).val(ui.item.value);
               return false;
            }

            $(".dosage_val" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 2,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });

         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".duration_val" + i).val(ui.item.value);
               return false;
            }

            $(".duration_val" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 1,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });
         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".frequency_val" + i).val(ui.item.value);
               return false;
            }

            $(".frequency_val" + i).autocomplete({
               source: getData,
               select: selectItem,
               minLength: 2,
               change: function() {
                  //$("#test_val").val("").css("display", 2);
               }
            });
         });

         $(function() {
            var getData = function(request, response) {
               $.getJSON(
                  "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
                  function(data) {
                     response(data);
                  });
            };

            var selectItem = function(event, ui) {
               $(".advice_val" + i).val(ui.item.value);
               return false;
            }

            $(".advice_val" + i).autocomplete({
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
      $("#prescription_name_table").on('click', '.remove_prescription_row', function() {
         $(this).parent().parent().remove();
      });
   });

   $('#form_submit').on("click", function() {
      $('#dental_prescription_form').submit();
   })


   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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


   $(function() {
      var i = $('#prescription_name_table tr').length;
      var getData = function(request, response) {
         row = i;
         $.ajax({
            url: "<?php echo base_url('gynecology/patient_template/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
            dataType: "json",
            method: 'post',
            data: {
               name_startsWith: request.term,
               type: 'country_table',
               row_num: row
            },
            success: function(data) {
               response($.map(data, function(item) {
                  var code = item.split("|");
                  return {
                     label: code[0],
                     value: code[0],
                     data: item
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

      var selectItem = function(event, ui) {

         var names = ui.item.data.split("|");
         $('.medicine_val').val(names[0]);
         $('#type0').val(names[1]);
         $('#brand0').val(names[3]);
         $('#salt0').val(names[2]);
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


   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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

   $(function() {
      var getData = function(request, response) {

         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
         $(".medicine_type_val").val(ui.item.value);
         return false;
      }

      $(".medicine_type_val").autocomplete({
         source: getData,
         select: selectItem,
         minLength: 2,
         change: function() {
            //$("#test_val").val("").css("display", 2);
         }
      });
   });

   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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
   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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

   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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
   // medicine for patient main tabs
   $(document).ready(function() {
      $(".addrow").click(function() {

         var i = $('#test_name_table_patient tr').length;
         $("#test_name_table_patient").append('<tr><td><input type="text" name="test_name_patient[]" class="w-100 test_val' + i + '"></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');

         var selectItem = function(event, ui) {
            $(".test_val" + i).val(ui.item.value);
            return false;
         }

         $(".test_val" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });

   });
   $("#test_name_table_patient").on('click', '.remove_row', function() {
      $(this).parent().parent().remove();
   });

   $(".addprescriptionrow_patient").click(function() {

      var i = $('#prescription_name_table_patient tr').length;
      $("#prescription_name_table_patient").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) {
                                                            if (strcmp(strtolower($tab_value->setting_name), 'medicine') == '0') {
                                                         ?><td><input type="text" name="prescription[' + i + '][medicine_name]" class=" medicine_val_patient' + i + '"><input type="hidden" name="medicine_id[]" id="medicine_id_patient' + i + '"></td>                        <?php
                                                                                                                                                                                                                                       }
                                                                                                                                                                                                                                       if (strcmp(strtolower($tab_value->setting_name), 'brand') == '0') {
                                                                                                                                                                                                                                          ?>   <td><input type="text" name="prescription[' + i + '][medicine_brand]" id="brand_patient' + i + '"  class="" ></td>                        <?php
                                                                                                                                                                                                                                       }

                                                                                                                                                                                                                                       if (strcmp(strtolower($tab_value->setting_name), 'salt') == '0') {
                                                                                                                                                               ?>  <td><input type="text" id="salt_patient' + i + '"  name="prescription[' + i + '][medicine_salt]" class=""  ></td>                        <?php
                                                                                                                                                                                                                                       }
                                                                                                                                                                                                                                       if (strcmp(strtolower($tab_value->setting_name), 'type') == '0') { ?>  <td><input type="text" id="type_patient' + i + '"  name="prescription[' + i + '][medicine_type]" class="" id="type_patient' + i + '" onkeyup="get_medicine_type_autocomplete(' + i + ',2);"></td>                        <?php
                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                             if (strcmp(strtolower($tab_value->setting_name), 'dose') == '0') { ?> <td><input type="text" name="prescription[' + i + '][medicine_dose]" class="  dosage_val_patient' + i + '"></td>                        <?php
                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                             if (strcmp(strtolower($tab_value->setting_name), 'duration') == '0') {  ?>  <td><input type="text" name="prescription[' + i + '][medicine_duration]" class=" medicine-name duration_val_patient' + i + '"></td>                        <?php
                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                             if (strcmp(strtolower($tab_value->setting_name), 'frequency') == '0') {
                                                                                                                                                                                 ?> <td><input type="text" name="prescription[' + i + '][medicine_frequency]" class=" medicine-name frequency_val_patient' + i + '"></td>                        <?php
                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                             if (strcmp(strtolower($tab_value->setting_name), 'advice') == '0') {
                                                                                                                                                                                 ?>  <td><input type="text" name="prescription[' + i + '][medicine_advice]" class=" medicine-name advice_val_patient' + i + '"></td>                        <?php
                                                                                                                                                                                                                                             }
                                                                                                                                                                                                                                          } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row_patient">Delete</a></td></tr>');
      /* script start */
      $(function() {
         m = 0
         var getData = function(request, response) {
            row = i;
            $.ajax({
               url: "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
               dataType: "json",
               method: 'post',
               data: {
                  name_startsWith: request.term,
                  type: 'country_table',
                  row_num: row
               },
               success: function(data) {
                  response($.map(data, function(item) {
                     var code = item.split("|");
                     return {
                        label: code[0],
                        value: code[0],
                        data: item
                     }
                  }));
               }
            });


         };

         var selectItem = function(event, ui) {

            var names = ui.item.data.split("|");
            //alert("Dfdf");console.log(names);
            $('.medicine_val_patient' + i).val(names[0]);
            $('#type_patient' + i).val(names[1]);
            $('#brand_patient' + i).val(names[3]);
            $('#salt_patient' + i).val(names[2]);
            //$(".medicine_val").val(ui.item.value);
            return false;
         }

         $(".medicine_val_patient" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });


      $(function() {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $(".dosage_val_patient" + i).val(ui.item.value);
            return false;
         }

         $(".dosage_val_patient" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });

      $(function() {
         var getData = function(request, response) {
            alert("asujhsa");
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $(".medicine_type_val_patient" + i).val(ui.item.value);
            return false;
         }

         $(".medicine_type_val_patient" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });

      $(function() {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $(".duration_val_patient" + i).val(ui.item.value);
            return false;
         }

         $(".duration_val_patient" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });
      $(function() {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $(".frequency_val_patient" + i).val(ui.item.value);
            return false;
         }

         $(".frequency_val_patient" + i).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 2,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      });

      $(function() {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $(".advice_val_patient" + i).val(ui.item.value);
            return false;
         }

         $(".advice_val_patient" + i).autocomplete({
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
   $("#prescription_name_table_patient").on('click', '.remove_prescription_row_patient', function() {
      $(this).parent().parent().remove();
   });


   /*$('#form_submit').on("click",function(){
   $('#dental_prescription_form').submit();
   })*/


   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('eye/prescription_template/get_eye_test_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
         $(".test_val_patient").val(ui.item.value);
         return false;
      }

      $(".test_val_patient").autocomplete({
         source: getData,
         select: selectItem,
         minLength: 2,
         change: function() {
            //$("#test_val").val("").css("display", 2);
         }
      });
   });


   $(function() {
      var i = $('#prescription_name_table tr').length;
      var getData = function(request, response) {
         row = i;
         $.ajax({
            url: "<?php echo base_url('gynecology/patient_template/get_gynecology_medicine_auto_vals/'); ?>" + request.term,
            dataType: "json",
            method: 'post',
            data: {
               name_startsWith: request.term,
               type: 'country_table',
               row_num: row
            },
            success: function(data) {
               response($.map(data, function(item) {
                  var code = item.split("|");
                  return {
                     label: code[0],
                     value: code[0],
                     data: item
                  }
               }));
            }
         });
      };

      var selectItem = function(event, ui) {

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


   $(function() {
      var getData = function(request, response) {

         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
         $(".medicine_type_val_patient").val(ui.item.value);
         return false;
      }

      $(".medicine_type_val_patient").autocomplete({
         source: getData,
         select: selectItem,
         minLength: 2,
         change: function() {
            //$("#test_val").val("").css("display", 2);
         }
      });
   });

   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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

   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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
   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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

   $(function() {
      var getData = function(request, response) {
         $.getJSON(
            "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
            function(data) {
               response(data);
            });
      };

      var selectItem = function(event, ui) {
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
   function add_patient_history_listdata() {
      var rec_count = $("#row_id").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var marriage_status = $('#marriage_status option:selected').text();
      var married_life_unit = $('#married_life_unit').val();
      var married_life_type = $('#married_life_type option:selected').text();
      var marriage_no = $('#marriage_no').val();
      var marriage_details = $('#marriage_details').val();
      var previous_delivery = $('#previous_delivery option:selected').text();
      var delivery_type = $('#delivery_type option:selected').text();
      var delivery_details = $('#delivery_details').val();

      //alert(unique_id);return false; 
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_history_list/",
         dataType: "json",
         data: 'marriage_status=' + marriage_status + '&married_life_unit=' + married_life_unit + '&married_life_type=' + married_life_type + '&marriage_no=' + marriage_no + '&marriage_details=' + marriage_details + '&previous_delivery=' + previous_delivery + '&delivery_type=' + delivery_type + '&delivery_details=' + delivery_details + '&rec_count=' + rec_count + '&unique_id=' + rec_count,
         success: function(result) {
            $('#patient_history_list').html(result.html_data);
            $('#married_life_unit').val('');
            $('#married_life_type option:selected').removeAttr("selected");
            $('#marriage_no').val('');
            $('#marriage_details').val('');
            $('#previous_delivery option:selected').removeAttr("selected");
            $('#delivery_type option:selected').removeAttr("selected");
            $('#delivery_details').val('');
            $("#patient_history_count").val(parseInt(rec_count) + parseInt(1));
         }
      });
      $('#row_id').val(parseInt(rec_count) + parseInt(1));

   }

   function add_patient_family_history_listdata() {
      var rec_count = $("#row_id_family_history").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
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
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_family_history_list/",
         dataType: "json",
         data: 'relation=' + relation + '&disease=' + disease + '&family_description=' + family_description + '&family_duration_unit=' + family_duration_unit + '&family_duration_type=' + family_duration_type + '&relation_id=' + relation_id + '&disease_id=' + disease_id + '&unique_id_family_history=' + rec_count,
         success: function(result) {
            $('#patient_family_history_list').html(result.html_data);
            $('#disease_id option:selected').removeAttr("selected");
            $('#relation_id option:selected').removeAttr("selected");
            $('#family_description').val('');
            $('#family_duration_unit').val('');
            $('#family_duration_type option:selected').removeAttr("selected");
         }
      });
      $('#row_id_family_history').val(parseInt(rec_count) + parseInt(1));

   }

   function add_patient_personal_history_listdata() {
      var rec_count = $("#row_id_personal_history").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var br_discharge = $('#br_discharge option:selected').text();
      var side = $('#side option:selected').text();
      var hirsutism = $('#hirsutism option:selected').text();
      var white_discharge = $('#white_discharge option:selected').text();
      var type = $('#type option:selected').text();
      var frequency_personal = $('#frequency_personal').val();
      var dyspareunia = $('#dyspareunia option:selected').text();
      var personal_details = $('#personal_details').val();

      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_personal_history_list/",
         dataType: "json",
         data: 'br_discharge=' + br_discharge + '&side=' + side + '&hirsutism=' + hirsutism + '&white_discharge=' + white_discharge + '&type=' + type + '&frequency_personal=' + frequency_personal + '&dyspareunia=' + dyspareunia + '&personal_details=' + personal_details + '&unique_id_personal_history=' + rec_count,
         success: function(result) {
            $('#patient_personal_history_list').html(result.html_data);
            $('#br_discharge option:selected').removeAttr("selected");
            $('#side option:selected').removeAttr("selected");
            $('#hirsutism option:selected').removeAttr("selected");
            $('#white_discharge option:selected').removeAttr("selected");
            $('#type option:selected').removeAttr("selected");
            $('#dyspareunia option:selected').removeAttr("selected");
            $('#frequency_personal').val('');
            $('#personal_details').val('');
         }
      });
      $('#row_id_personal_history').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_menstrual_history_listdata() {
      var rec_count = $("#row_id_menstrual_history").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
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
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_menstrual_history_list/",
         dataType: "json",
         data: 'previous_cycle=' + previous_cycle + '&prev_cycle_type=' + prev_cycle_type + '&present_cycle=' + present_cycle + '&present_cycle_type=' + present_cycle_type + '&lmp_date=' + lmp_date + '&dysmenorrhea=' + dysmenorrhea + '&dysmenorrhea_type=' + dysmenorrhea_type + '&cycle_details=' + cycle_details + '&unique_id_menstrual_history=' + rec_count,
         success: function(result) {
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
      $('#row_id_menstrual_history').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_medical_history_listdata() {
      var rec_count = $("#row_id_medical_history").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
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
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_medical_history_list/",
         dataType: "json",
         data: 'tb=' + tb + '&tb_rx=' + tb_rx + '&dm=' + dm + '&dm_years=' + dm_years + '&dm_rx=' + dm_rx + '&ht=' + ht + '&medical_details=' + medical_details + '&medical_others=' + medical_others + '&unique_id_medical_history=' + rec_count,
         success: function(result) {
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
      $('#row_id_medical_history').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_obestetric_history_listdata() {
      var rec_count = $("#row_id_obestetric_history").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var obestetric_g = $("#obestetric_g").val();
      var obestetric_p = $("#obestetric_p").val();
      var obestetric_l = $("#obestetric_l").val();
      var obestetric_mtp = $("#obestetric_mtp").val();

      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_obestetric_history_list/",
         dataType: "json",
         data: 'obestetric_g=' + obestetric_g + '&obestetric_p=' + obestetric_p + '&obestetric_l=' + obestetric_l + '&obestetric_mtp=' + obestetric_mtp + '&unique_id_obestetric_history=' + rec_count,
         success: function(result) {
            $('#patient_obestetric_history_list').html(result.html_data);
            $('#obestetric_g').val('');
            $('#obestetric_p').val('');
            $('#obestetric_l').val('');
            $('#obestetric_mtp').val('');
         }
      });
      $('#row_id_obestetric_history').val(parseInt(rec_count) + parseInt(1));
   }


   function add_patient_disease_listdata() {
      var rec_count = $("#row_id_patient_disease").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_disease_id = $('#patient_disease_id').val();
      var disease_value = $('#patient_disease_id option:selected').text();
      var patient_disease_unit = $('#patient_disease_unit').val();
      var patient_disease_type = $('#patient_disease_type option:selected').text();
      var disease_description = $('#disease_description').val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_disease_list/",
         dataType: "json",
         data: 'patient_disease_id=' + patient_disease_id + '&disease_value=' + disease_value + '&patient_disease_unit=' + patient_disease_unit + '&patient_disease_type=' + patient_disease_type + '&disease_description=' + disease_description + '&unique_id_patient_disease=' + rec_count,

         success: function(result) {
            $('#patient_disease_list').html(result.html_data);
            $('#patient_disease_id option:selected').removeAttr("selected");
            $('#patient_disease_unit').val('');
            $('#patient_disease_type option:selected').removeAttr("selected");
            $('#disease_description').val('');
         }
      });
      $('#row_id_patient_disease').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_complaint_listdata() {
      var rec_count = $("#row_id_patient_complaint").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_complaint_id = $('#patient_complaint_id').val();
      var complaint_value = $('#patient_complaint_id option:selected').text();
      var patient_complaint_unit = $('#patient_complaint_unit').val();
      var patient_complaint_type = $('#patient_complaint_type option:selected').text();
      //alert(patient_complaint_type);return false;
      var complaint_description = $('#complaint_description').val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_complaint_list/",
         dataType: "json",
         data: 'patient_complaint_id=' + patient_complaint_id + '&complaint_value=' + complaint_value + '&patient_complaint_unit=' + patient_complaint_unit + '&patient_complaint_type=' + patient_complaint_type + '&complaint_description=' + complaint_description + '&unique_id_patient_complaint=' + rec_count,

         success: function(result) {
            $('#patient_complaint_list').html(result.html_data);
            $('#patient_complaint_id option:selected').removeAttr("selected");
            $('#patient_complaint_unit').val('');
            $('#patient_complaint_type option:selected').removeAttr("selected");
            $('#complaint_description').val('');
         }
      });
      $('#row_id_patient_complaint').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_allergy_listdata() {
      var rec_count = $("#row_id_patient_allergy").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_allergy_id = $('#patient_allergy_id').val();
      var allergy_value = $('#patient_allergy_id option:selected').text();
      var patient_allergy_unit = $('#patient_allergy_unit').val();
      var patient_allergy_type = $('#patient_allergy_type option:selected').text();
      //alert(patient_allergy_type);return false;
      var allergy_description = $('#allergy_description').val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_allergy_list/",
         dataType: "json",
         data: 'patient_allergy_id=' + patient_allergy_id + '&allergy_value=' + allergy_value + '&patient_allergy_unit=' + patient_allergy_unit + '&patient_allergy_type=' + patient_allergy_type + '&allergy_description=' + allergy_description + '&unique_id_patient_allergy=' + rec_count,

         success: function(result) {
            $('#patient_allergy_list').html(result.html_data);
            $('#patient_allergy_id option:selected').removeAttr("selected");
            $('#patient_allergy_unit').val('');
            $('#patient_allergy_type option:selected').removeAttr("selected");
            $('#allergy_description').val('');
         }
      });
      $('#row_id_patient_allergy').val(parseInt(rec_count) + parseInt(1));

   }

   function add_patient_general_examination_listdata() {
      var rec_count = $("#row_id_general_examination").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_general_examination_id = $('#patient_general_examination_id').val();
      var general_examination_value = $('#patient_general_examination_id option:selected').text();
      //var patient_general_examination_unit = $('#patient_general_examination_unit').val();
      //var patient_general_examination_type = $('#patient_general_examination_type option:selected').text();
      //alert(patient_general_examination_type);return false;
      var general_examination_description = $('#general_examination_description').val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_general_examination_list/",
         dataType: "json",
         data: 'patient_general_examination_id=' + patient_general_examination_id + '&general_examination_value=' + general_examination_value + '&general_examination_description=' + general_examination_description + '&unique_id_patient_general_examination=' + rec_count,

         success: function(result) {
            $('#patient_general_examination_list').html(result.html_data);
            $('#patient_general_examination_id option:selected').removeAttr("selected");
            //$('#patient_general_examination_unit').val('');
            //$('#patient_general_examination_type option:selected').removeAttr("selected");
            $('#general_examination_description').val('');
         }
      });
      $('#row_id_general_examination').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_clinical_examination_listdata() {
      var rec_count = $("#row_id_clinical_examination").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_clinical_examination_id = $('#patient_clinical_examination_id').val();
      var clinical_examination_value = $('#patient_clinical_examination_id option:selected').text();

      var clinical_examination_description = $('#clinical_examination_description').val();
      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_clinical_examination_list/",
         dataType: "json",
         data: 'patient_clinical_examination_id=' + patient_clinical_examination_id + '&clinical_examination_value=' + clinical_examination_value + '&clinical_examination_description=' + clinical_examination_description + '&unique_id_patient_clinical_examination=' + rec_count,

         success: function(result) {
            $('#patient_clinical_examination_list').html(result.html_data);
            $('#patient_clinical_examination_id option:selected').removeAttr("selected");
            // $('#patient_clinical_examination_unit').val('');
            //$('#patient_clinical_examination_type option:selected').removeAttr("selected");
            $('#clinical_examination_description').val('');
         }
      });
      $('#row_id_clinical_examination').val(parseInt(rec_count) + parseInt(1));
   }

   function get_std_value(value) {
      if (value == "" || value == undefined) {
         $("#std_value").val('');
         return false;
      } else {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>gynecology/patient_template/get_std_value/",
            data: 'value=' + value,
            success: function(result) {
               if (result == 0) {
                  $("#std_value").val("");
               } else {
                  $("#std_value").val(result);
               }
            }
         });
      }
   }

   function add_patient_investigation_listdata() {
      var rec_count = $("#row_id_patient_investigation").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_investigation_id = $('#patient_investigation_id').val();
      var investigation_value = $('#patient_investigation_id option:selected').text();
      var std_value = $('#std_value').val();
      var observed_value = $('#observed_value').val();

      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_investigation_list/",
         dataType: "json",
         data: 'patient_investigation_id=' + patient_investigation_id + '&investigation_value=' + investigation_value + '&observed_value=' + observed_value + '&std_value=' + std_value + '&unique_id_patient_investigation=' + rec_count,

         success: function(result) {
            $('#patient_investigation_list').html(result.html_data);
            $('#patient_investigation_id option:selected').removeAttr("selected");
            $('#observed_value').val('');
            $('#std_value').val('');
         }
      });
      $('#row_id_patient_investigation').val(parseInt(rec_count) + parseInt(1));
   }

   function add_patient_advice_listdata() {
      var rec_count = $("#row_id_patient_advice").val();
      if (rec_count == undefined | rec_count == "") {
         rec_count = 1;
      }
      var patient_advice_id = $('#patient_advice_id').val();
      var advice_value = $('#patient_advice_id option:selected').text();

      $.ajax({
         type: "POST",
         url: "<?php echo base_url(); ?>gynecology/patient_template/patient_advice_list/",
         dataType: "json",
         data: 'patient_advice_id=' + patient_advice_id + '&advice_value=' + advice_value + '&unique_id_patient_advice=' + rec_count,

         success: function(result) {
            $('#patient_advice_list').html(result.html_data);
            $('#patient_advice_id option:selected').removeAttr("selected");
         }
      });
      $('#row_id_patient_advice').val(parseInt(rec_count) + parseInt(1));

   }


   function add_relation() {
      //alert();
      var $modal = $('#load_add_relation_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/relation/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_disease() {

      var $modal = $('#load_add_gynecology_disease_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/disease/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }


   function add_complaint() {

      var $modal = $('#load_add_gynecology_complaints_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/complaints/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_allergy() {
      var $modal = $('#load_add_gynecology_allergy_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/allergy/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_general_examination() {
      var $modal = $('#load_add_general_examination_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/general_examination/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_clinical_examination() {
      var $modal = $('#load_add_clinical_examination_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/clinical_examination/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_investigation() {
      var $modal = $('#load_add_gynecology_inves_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/investigation/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }

   function add_advice() {
      var $modal = $('#load_add_gynecology_advice_modal_popup');
      $modal.load('<?php echo base_url() . 'gynecology/advice/add/' ?>', {
            //'id1': '1',
            //'id2': '2'
         },
         function() {
            $modal.modal('show');
         });
   }


   function delete_rows_sub_category(id) { //alert();
      $('#' + id + 'sub_category').remove();
   }

   $(function() {
      $("#check_appointment").click(function() {
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


   function delete_patient_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_history_vals(allVals);
   }


   function remove_patient_history_vals(allVals) {
      if (allVals != "") {
         var row_count = $('#patient_history_count').val();
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_history'); ?>",
            data: 'row_count=' + row_count + '&patient_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_history_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_family_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_family_history_vals(allVals);
   }


   function remove_patient_family_history_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_family_history'); ?>",
            data: 'patient_family_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_family_history_list').html(result.html_data);
            }
         });
      }
   }


   function delete_patient_personal_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_personal_history_vals(allVals);
   }


   function remove_patient_personal_history_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_personal_history'); ?>",
            data: 'patient_personal_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_personal_history_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_menstrual_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_menstrual_history_vals(allVals);
   }


   function remove_patient_menstrual_history_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_menstrual_history'); ?>",
            data: 'patient_menstrual_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_menstrual_history_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_medical_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_medical_history_vals(allVals);
   }


   function remove_patient_medical_history_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_medical_history'); ?>",
            data: 'patient_medical_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_medical_history_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_obestetric_history_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_obestetric_history_vals(allVals);
   }


   function remove_patient_obestetric_history_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_obestetric_history'); ?>",
            data: 'patient_obestetric_history_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_obestetric_history_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_disease_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_disease_vals(allVals);
   }


   function remove_patient_disease_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_disease'); ?>",
            data: 'patient_disease_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_disease_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_complaint_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_complaint_vals(allVals);
   }


   function remove_patient_complaint_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_complaint'); ?>",
            data: 'patient_complaint_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_complaint_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_allergy_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_allergy_vals(allVals);
   }


   function remove_patient_allergy_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_allergy'); ?>",
            data: 'patient_allergy_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_allergy_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_general_examination_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_general_examination_vals(allVals);
   }


   function remove_patient_general_examination_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_general_examination'); ?>",
            data: 'patient_general_examination_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_general_examination_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_clinical_examination_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_clinical_examination_vals(allVals);
   }


   function remove_patient_clinical_examination_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_clinical_examination'); ?>",
            data: 'patient_clinical_examination_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_clinical_examination_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_investigation_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_investigation_vals(allVals);
   }


   function remove_patient_investigation_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_investigation'); ?>",
            data: 'patient_investigation_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_investigation_list').html(result.html_data);
            }
         });
      }
   }

   function delete_patient_advice_vals() {
      var allVals = [];
      $('.booked_checkbox').each(function() {
         if ($(this).prop('checked') == true && !isNaN($(this).val())) {
            allVals.push($(this).val());
         }
      });
      remove_patient_advice_vals(allVals);
   }


   function remove_patient_advice_vals(allVals) {
      if (allVals != "") {
         $.ajax({
            type: "POST",
            url: "<?php echo base_url('gynecology/patient_template/remove_gynecology_patient_advice'); ?>",
            data: 'patient_advice_vals=' + allVals,
            dataType: "json",
            success: function(result) {
               $('#patient_advice_list').html(result.html_data);
            }
         });
      }
   }

   function get_medicine_type_autocomplete(row_id, type) {
      if (type == 2) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#type_patient" + row_id).val(ui.item.value);
            return false;
         }

         $("#type_patient" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      } else if (type == 1) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_type_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#type" + row_id).val(ui.item.value);
            return false;
         }

         $("#type" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      }

   }

   function get_medicine_dose_autocomplete(row_id, type) {
      if (type == 2) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#dose_patient" + row_id).val(ui.item.value);
            return false;
         }

         $("#dose_patient" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      } else if (type == 1) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_dosage_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#dose" + row_id).val(ui.item.value);
            return false;
         }

         $("#dose" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      }



   }

   function get_medicine_duration_autocomplete(row_id, type) {
      if (type == 2) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#duration_patient" + row_id).val(ui.item.value);
            return false;
         }

         $("#duration_patient" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      } else if (type == 1) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_duration_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#duration" + row_id).val(ui.item.value);
            return false;
         }

         $("#duration" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      }



   }

   function get_medicine_frequency_autocomplete(row_id, type) {
      if (type == 2) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#frequency_patient" + row_id).val(ui.item.value);
            return false;
         }

         $("#frequency_patient" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      } else if (type == 1) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_frequency_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#frequency" + row_id).val(ui.item.value);
            return false;
         }

         $("#frequency" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      }



   }

   function get_medicine_advice_autocomplete(row_id, type) {
      if (type == 2) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#advice_patient" + row_id).val(ui.item.value);
            return false;
         }

         $("#advice_patient" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      } else if (type == 1) {
         var getData = function(request, response) {
            $.getJSON(
               "<?php echo base_url('gynecology/gynecology_prescription/get_gynecology_advice_vals/'); ?>" + request.term,
               function(data) {
                  response(data);
               });
         };

         var selectItem = function(event, ui) {
            $("#advice" + row_id).val(ui.item.value);
            return false;
         }

         $("#advice" + row_id).autocomplete({
            source: getData,
            select: selectItem,
            minLength: 1,
            change: function() {
               //$("#test_val").val("").css("display", 2);
            }
         });
      }


   }


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


   $("button[data-number=1]").click(function() {
      $('#load_add_gyn ecology_disease_modal_popup').modal('hide');
   });


   function toggle(source) {
      checkboxes = document.getElementsByClassName('part_checkbox');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_chief(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_chief');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_family_history(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_family_history');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_pat(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_pat');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_personal_history(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_personal_history');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_menstrual_history(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_menstrual_history');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_medical_history(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_medical_history');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_obestetric_history(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_obestetric_history');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_disease(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_disease');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_complaints(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_complaints');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }


   function toggle_allergy(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_allergy');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_general_examination(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_general_examination');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }


   function toggle_clinical_examination(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_clinical_examination');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_investigation(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_investigation');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   function toggle_advice(source) {
      checkboxes = document.getElementsByClassName('part_checkbox_advice');
      for (var i = 0, n = checkboxes.length; i < n; i++) {
         checkboxes[i].checked = source.checked;
      }
   }

   $(document).ready(function() {

      $("#patient_history_list").on('click', '.remCF', function() {
         $(this).parent().parent().remove();
      });

   });
</script>
<div id="load_add_relation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_gynecology_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_gynecology_complaints_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_gynecology_allergy_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_general_examination_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_clinical_examination_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_gynecology_inves_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_gynecology_advice_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- old -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script>
   $(document).ready(function() {
      $('#history_btn').click(function() {
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
      $('#family_history_btn').click(function() {
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
      $('#personal_history_btn').click(function() {
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
      $('#menstrual_history_btn').click(function() {
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
      $('#medical_history_btn').click(function() {
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
      $('#obestetric_history_btn').click(function() {
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
      $('#current_medication_btn').click(function() {
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
</script>
<script>
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
                     url: "<?php echo base_url(); ?>gynecology/patient_template/patient_gpla_list/", 
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
</script>
</body>

</html>