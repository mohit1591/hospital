<?php $users_data = $this->session->userdata('auth_users'); ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
 <section class="userlist">
 
  <form  id="ipd_patient_discharge_summary" class="form-inline" action="<?php echo current_url(); ?>" method="post" >
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
  <input type="hidden" name="dialysis_id" id="dialysis_id" value="<?php echo $form_data['dialysis_id']; ?>" /> 
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id']; ?>" /> 
  <!-- ////////////////////////////// -->
 <div class="row">
   
   <div class="col-md-6">
     <!-- ============================ -->
        <div class="row m-b-5">
          <div class="col-md-4"><b><?php echo $data= get_setting_value('PATIENT_REG_NO');?></b></div>
          <div class="col-md-8">
            <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['patient_code']; ?>" /> 
          </div>
        </div>

        <div class="row m-b-5">
          <div class="col-xs-4">
            <strong>Dialysis No.</strong>
          </div>
          <div class="col-xs-8">
            <input type="text" readonly="" name="patient_code" value="<?php echo $patient_details['booking_code']; ?>" /> 
          </div>
        </div>

        <div class="row m-b-5">
          <div class="col-xs-4">
              <strong>Patient Name <span class="star">*</span></strong>
            </div>
            <div class="col-xs-8">
              <select class="mr" name="simulation_id" id="simulation_id"  disabled="true">
                <option value="">Select</option>
                <?php
                  
                  if(!empty($simulation_list))
                  {
                    foreach($simulation_list as $simulation)
                    {
                      $selected_simulation = '';
                      if($simulation->id==$patient_details['simulation_id'])
                      {
                           $selected_simulation = 'selected="selected"';
                      }
                              
                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                    }
                  }
                  ?> 
              </select> 
              <?php 
              if(!empty($simulation_list))
                  {
                    foreach($simulation_list as $simulation)
                    {
                      $selected_simulation = '';
                      if($simulation->id==$patient_details['simulation_id'])
                      {
                           $selected_simulation = 'selected="selected"';
                      }
                              
                      echo '<input type="hidden" name="simulation_id" value="'.$simulation->id.'">';
                    }
                  }
              ?>
              <input type="text" name="patient_name" readonly id="patient_name" value="<?php echo $patient_details['patient_name']; ?>" class="mr-name txt_firstCap" autofocus/>
              
                <?php if(!empty($form_error)){ echo form_error('simulation_id'); } ?>
                <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
            </div>
        </div>

      <div class="row m-b-5">
         <div class="col-xs-4">
            <strong>Mobile No.</strong>
          </div>
         <div class="col-xs-8">
            <input type="text" name="mobile_no" readonly data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text numeric" value="<?php echo $patient_details['mobile_no']; ?>" maxlength="10" >
            
          </div>
      </div>
      <div class="row m-b-5">
        <div class="col-xs-4">
          <strong>Gender </strong>
        </div>
        <div class="col-xs-8" id="gender">
          <input type="radio" name="gender"  disabled="true" value="1" <?php if($patient_details['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
              <input type="radio" name="gender"  disabled="true" value="0" <?php if($patient_details['gender']==0){ echo 'checked="checked"'; } ?>> Female
               <input type="radio" name="gender"  disabled="true" value="2" <?php if($patient_details['gender']==2){ echo 'checked="checked"'; } ?>> Others
              <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
        </div>
    
      </div> <!-- row -->
      
      <div class="row m-b-5">
        <div class="col-xs-4">
          <strong>Age</strong>
        </div>
        <div class="col-xs-8">
          <input type="text" name="age_y" readonly  class="input-tiny numeric" maxlength="3" value="<?php echo $patient_details['age_y']; ?>"> Y &nbsp;
                <input type="text" name="age_m"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_m']; ?>"> M &nbsp;
                <input type="text" name="age_d"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $patient_details['age_d']; ?>"> D
               
        </div>
      </div> <!-- row -->
      <div class="row m-b-5">`
         <div class="col-xs-4"><label>Dialysis Template </label></div>
         <div class="col-xs-8">

         <select name="template_list"  id="template_list" >
                    <option value="">Select Template</option>
                 <?php
                    if(isset($template_list) && !empty($template_list))
                    {
                      foreach($template_list as $templatelist)
                      {
                         echo '<option class="grp" value="'.$templatelist->id.'">'.$templatelist->name.'</option>';
                      }
                    }
                 ?>
              </select> 

         </div>
      </div> <!-- row -->  

         


    <div id="content">
        <?php
       
        if(empty($form_data['data_id']))
        {
          if(!empty($discharge_field_master_list))
          {
            foreach ($discharge_field_master_list as $discharge_field) 
            {
                ?>
                      <div class="row m-b-5">
                      <div class="col-xs-4">
                      <strong><?php echo ucfirst($discharge_field->discharge_lable); ?></strong>
                      </div>
                      

                      <?php if($discharge_field->type==1)
                      {
                       
                        ?>
                        <div class="col-xs-8">
                        <input type="text" id="field_id_<?php echo strtoupper($discharge_field->discharge_lable);?>" name="field_name[]" value="" />
                        <input type="hidden" id="fieldsid_<?php echo strtoupper($discharge_field->discharge_lable);?>" value="<?php echo $discharge_field->id;?>" name="field_id[]" />
                        </div>
                        <?php 
                      }
                      else
                      {
                        ?>
                        <div class="col-xs-8">
                        <textarea id="field_id_<?php echo strtoupper($discharge_field->discharge_lable);?>" name="field_name[]"></textarea>
                        <input type="hidden" id="fieldsid_<?php echo strtoupper($discharge_field->discharge_lable);?>" value="<?php echo $discharge_field->id;?>" name="field_id[]" />

                         </div>
                        <?php
                      } ?>
                      </div>
                <?php 
                 
            }
          }
        }
        else
        {
           
                  if(!empty($field_name))
                   { 
                    foreach ($field_name as $field_names) 
                    {
                        $tot_values= explode('__',$field_names);
                        
                  ?>

              <div class="row m-b-5">
                      <div class="col-xs-4">
                      <b><?php echo ucfirst($tot_values[1]);?></b>
                      </div> 
                  
                  <?php if($tot_values[3]==1){ ?>
                  <div class="col-xs-8">
                  <input type="text" id="field_id_<?php echo $tot_values[1];?>" name="field_name[]" value="<?php echo $tot_values[0];?>" />
                  <input type="hidden" id="fieldsid_<?php echo $tot_values[1];?>" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                  <?php 
                      if(empty($tot_values[0]))
                      {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                      }
                      ?>
                  </div>
                    <?php 
                  }
                  else
                  {
                      ?>  
                        <div class="col-xs-8">
                        <textarea id="field_id_<?php echo $tot_values[1];?>" name="field_name[]"><?php echo $tot_values[0];?></textarea>
                        <input type="hidden" id="fieldsid_<?php echo $tot_values[1];?>" value="<?php echo $tot_values[2];?>" name="field_id[]" /> 
                        </div>
                      <?php 
                  }
                  if(empty($tot_values[0]))
                  {
                      if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                  }
                  ?>
               </div>
               <?php 
               } 
               } 
             }
            //permission
               ?>
         </div>   
          
           <div class="row m-b-5">
             <div class="col-xs-4"><label>Status</label></div>
             <div class="col-xs-8">
                 <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>> Active &nbsp;
                 <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>> Inactive
             </div>
          </div> <!-- row --> 
     <!-- ============================ -->
   </div> <!-- left portion -->


   <div class="col-md-6">
     <!-- ======================== -->
      <div class="row m-b-5">
          <div class="col-xs-4"><strong>Dialysis Date/Time</strong> </div>
          <div class="col-xs-8 ">
            <?php 
            $time = "";
            if(date('h:i:s',strtotime($patient_details['dialysis_time']))!='12:00:00')
            {
              $time = date('h:i A',strtotime($patient_details['dialysis_date']. $patient_details['dialysis_time']));
            }
            echo date('d-m-Y',strtotime($patient_details['dialysis_date'])).' ' . $time ; ?>
          </div>
      </div>

     

      <div class="row m-b-5">
          <div class="col-xs-4">
              <strong>Doctor Incharge</strong>
            </div>
            <div class="col-xs-8">
              <?php echo $patient_details['doctor_name']; ?>
            </div>
      </div>

     

      <div class="row m-b-5">
         <div class="col-xs-4">
            <strong>Address</strong>
          </div>
          <div class="col-xs-8 ">
           <?php  echo $patient_details['address']; ?>
          </div>
      </div>
      <div class="row m-b-5">
        <div class="col-xs-4"><strong>Date</strong></div>
        <div class="col-xs-8">
            <input type="text" name="summary_date"  id="summary_date" class="datepicker m_input_default" value="<?php echo $form_data['summary_date']; ?>"/>
            
        </div>
    </div>

       <div id="content1">
        
        
     </div>
     
     
     <?php //if($medicine_setting==1){ ?>
               <div class="row m-b-5">
                <div class="col-xs-12">
               <table class="table table-bordered table-striped" id="prescription_name_table" >
                   <thead><tr><th colspan="9">Medication Prescribed  </th></tr></thead>
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
                                                    
                        <td width="40">
                            <a href="javascript:void(0)" style="width:40px;" class="btn-w-60 addprescriptionrow">Add</a>
                        </td>
                    </tr>

                      <?php 

                      if(!empty($prescription_test_list))
                      { 
                           $l=1;
                         //  print_r($prescription_presc_list);die;
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
                        <td><input type="text" name="medicine_name[]" style="width:100px;" class="medicine_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_name; ?>"></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_brand[]" style="width:80px;" class="" id="brand<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_brand; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" name="medicine_salt[]" style="width:80px;" id="salt<?php echo $l; ?>" class="" value="<?php echo $prescription_presc->medicine_salt; ?>"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" style="width:80px;" name="medicine_type[]" id="type<?php echo $l; ?>" class="input-small medicine_type_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_type; ?>"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" style="width:80px;" name="medicine_dose[]" class="input-small dosage_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_dose; ?>"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" style="width:80px;" name="medicine_duration[]" class="medicine-name duration_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_duration; ?>"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" style="width:80px;" name="medicine_frequency[]" class="medicine-name frequency_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_frequency; ?>"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" style="width:80px;" name="medicine_advice[]" class="medicine-name advice_val<?php echo $l; ?>" value="<?php echo $prescription_presc->medicine_advice; ?>"></td>
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
                              //$(".medicine_val").val(ui.item.value);
                              return false;
                          }

                          $(".medicine_val"+<?php echo $l; ?>).autocomplete({
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
                                  //$("#test_val").val("").css("display", 2);
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
                                  //$("#test_val").val("").css("display", 2);
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
                                  //$("#test_val").val("").css("display", 2);
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
                                  //$("#test_val").val("").css("display", 2);
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
                                  //$("#test_val").val("").css("display", 2);
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
                        

                                                        
                        <td width="40">
                            <a onclick="delete_prescr_row(this)" href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
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
                        <td><input type="text" style="width:100px;" name="medicine_name[]" class="medicine_val"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>
                        <td><input type="text" style="width:100px;" name="medicine_brand[]" class="" id="brand"></td>
                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>
                        <td><input type="text" style="width:100px;" name="medicine_salt[]" class=""  id="salt"></td>
                        <?php 
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>
                        <td><input type="text" style="width:100px;" name="medicine_type[]" class="input-small" id="type"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?>
                        <td><input type="text" style="width:100px;" name="medicine_dose[]" class="input-small dosage_val"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>
                        <td><input type="text" style="width:100px;" name="medicine_duration[]" class="medicine-name duration_val"></td>
                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?>
                        <td><input type="text" style="width:100px;" name="medicine_frequency[]" class="medicine-name frequency_val"></td>
                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>
                        <td><input type="text" style="width:100px;" name="medicine_advice[]" class="medicine-name advice_val"></td>
                        <?php } 
                      }
                   ?>
                    <td width="40">
                        <a href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>



                </div>
               </div>
               <!--- Medicine Section ---->
              <?php //} ?>
             <!--- Investigation Section ---->
               <div class="row m-b-5">
                <div class="col-xs-12">
               <table class="table table-bordered table-striped" id="test_name_table" >
                    <thead><tr><th colspan="4">Investigation</th></tr></thead>
              <tbody>
                     <tr>
                      
                      <td>Test Name</td>
                    <td>Date</td>
                    <td>Result</td>
                    
                                                    
                        <td width="80">
                            <a href="javascript:void(0)" class="btn-w-60 addtestrow">Add</a>
                        </td>
                    </tr>

                      <?php 

                      if(!empty($test_list))
                      { 
                           $l=1;
                         //  print_r($prescription_presc_list);die;
                          foreach ($discharge_test_list as $discharge_test) 
                          {
                            
                          ?>
                     <tr>
                       
                        <td><input type="text" name="test_name[]" class="test_val<?php echo $l; ?>" value="<?php echo $discharge_test->test_name; ?>">
                          <input type="hidden" name="test_id[]" class="test_id<?php echo $l; ?>" value="<?php echo $discharge_test->id; ?>">
                        </td>
                        
                        <td><input type="text" name="test_date[]" class="datepicker1<?php echo $l; ?>" value="<?php echo date('d-m-Y',strtotime($discharge_test->test_date)); ?>"></td>
                                              
                        <td><input type="text" name="test_result[]" class="medicine-name result_val<?php echo $l; ?>" value="<?php echo $discharge_test->result; ?>"></td>
                       
                        
                       
                          <script>
                    /* script start */
                      $(function () 
                      {
                            var getData = function (request, response) { 
                              row = <?php echo $l; ?> ;
                              $.ajax({
                              url : "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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

                                $('.test_val'+<?php echo $l; ?>).val(names[0]);
                                $('.test_id'+<?php echo $l; ?>).val(names[1]);
                                
                              //$(".medicine_val").val(ui.item.value);
                              return false;
                          }

                          $(".test_val"+<?php echo $l; ?>).autocomplete({
                              source: getData,
                              select: selectItem,
                              minLength: 1,
                              change: function() {
                                  //$("#test_val").val("").css("display", 2);
                              }
                          });


                            $('.datepicker1'+<?php echo $l; ?>).datepicker({
                  format: 'dd-mm-yyyy', 
                  autoclose: true 
                });
                          });


                      
                              /* script end*/
                        function delete_test_row(r)
                        { 
                            var i = r.parentNode.parentNode.rowIndex;
                            document.getElementById("test_name_table").deleteRow(i);
                        }
                        </script>
                        

                                                        
                        <td width="80">
                            <a onclick="delete_test_row(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                        </td>
                    </tr>
                    <?php $l++; } }else{ ?>

                    <tr>
                       
                        <td><input type="text" name="test_name[]" class="test_val"></td>
                        
                       <input type="hidden" name="test_id[]" class="test_id" value="<?php echo $discharge_test->id; ?>">
                       
                        <td><input type="text" name="test_date[]" class="datepicker1"></td>
                        
                        
                        <td><input type="text" name="test_result[]" class="medicine-name result_val"></td>
                
                    <td width="80">
                        <a href="javascript:void(0)" style="width:50px;" class="btn-w-60">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>



                </div>
               </div>
               <!--- Investigation Section ---->
     
     
     

     <!-- ======================== -->
   </div> <!-- right portion -->

</div>
 <!-- ///////////////////////// -->
  
<div class="row" style="padding-left:3em;">
      
 
      </div> <!-- modal-body --> 

      <div class="modal-footer r-btn-cntr"> 
         <input type="submit"  class="btn-update " name="submit" value="Save" />
        
         <button type="button" onclick="window.location.href='<?php echo base_url('dialysis_booking'); ?>'" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

function check_status(check_status)
{
  if(check_status==1)
  {
    
    $('#death_date_div').show();
    $('#death_time_div').show();
    
  }
  else
  {
    $('#death_date_div').hide();
    $('#death_time_div').hide();
  }
  
}
 


$("button[data-number=1]").click(function(){
    $('#load_add_ipd_patient_discharge_summary_modal_popup').modal('hide');
});

function get_ipd_patient_discharge_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>ipd_patient_discharge_summary/ipd_patient_discharge_summary_dropdown/", 
    success: function(result)
    {
      $('#ipd_patient_discharge_summary_id').html(result); 
    } 
  });
}

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>dialysis_patient_summary/get_template_data/"+template_id, 
        success: function(result)
        {
            var obj = JSON.parse(result);
            load_field_values(obj.id);
           
        } 
      }); 
  });

    function load_field_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>dialysis_patient_summary/get_template_test_data/"+template_id, 
        success: function(result)
        {
           get_test_values(result);
        } 
      });
    }
    function get_test_values(result)
    {
      var obj = JSON.parse(result);
      
      $.each(obj, function (index, value) { 
          //alert('#field_id_'+obj[index].field_name);
          let text = obj[index].field_name;
          let ids = text.toUpperCase();
        $('#field_id_'+ids).val(obj[index].field_value);
        $('#fieldsid_'+ids).val(obj[index].field_id);
       }); 
    }

$(document).ready(function ()
{
  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });
 $('.datepicker3').datetimepicker({
     format: 'LT'
  });


$('.death_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });
 $('.death_time_datepicker3').datetimepicker({
     format: 'LT'
  });



});


/******* Add Medicine *************/


$(".addprescriptionrow").click(function(){

  var i=$('#prescription_name_table tr').length;
        $("#prescription_name_table").append('<tr><?php foreach ($prescription_medicine_tab_setting as $tab_value) 
                        { 
                        if(strcmp(strtolower($tab_value->setting_name),'medicine')=='0')
                        {
                        ?><td><input type="text" style="width:100px;" name="medicine_name[]" class="medicine_val'+i+'"></td>                        <?php 
                        }
                       if(strcmp(strtolower($tab_value->setting_name),'brand')=='0')
                        {
                        ?>   <td><input style="width:80px;" type="text" name="medicine_brand[]" id="brand'+i+'"  class="" ></td>                        <?php 
                        } 

                        if(strcmp(strtolower($tab_value->setting_name),'salt')=='0')
                        {
                        ?>  <td><input style="width:80px;" type="text" id="salt'+i+'"  name="medicine_salt[]" class=""  ></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'type')=='0')
                        { ?>  <td><input style="width:80px;" type="text" id="type'+i+'"  name="medicine_type[]" class="input-small medicine_type_val"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'dose')=='0')
                        { ?> <td><input  style="width:80px;" type="text" name="medicine_dose[]" class="input-small dosage_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'duration')=='0')
                        {  ?>  <td><input type="text" style="width:80px;" name="medicine_duration[]" class="medicine-name duration_val'+i+'"></td>                        <?php 
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'frequency')=='0')
                        {
                        ?> <td><input style="width:80px;" type="text" name="medicine_frequency[]" class="medicine-name frequency_val'+i+'"></td>                        <?php 
                        } 
                        if(strcmp(strtolower($tab_value->setting_name),'advice')=='0')
                        { 
                        ?>  <td><input style="width:80px;" type="text" name="medicine_advice[]" class="medicine-name advice_val'+i+'"></td>                        <?php 
                        } 
                      } ?><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_prescription_row" style="width:50px;">Delete</a></td></tr>');
      

      /* script start */
$(function () 
{

      var getData = function (request, response) {  
        row = i;
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

          $('.medicine_val'+i).val(names[0]);
          
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

    });
    $("#prescription_name_table").on('click','.remove_prescription_row',function(){
        $(this).parent().parent().remove();
    });

/******* Medicine *************/


/************** Add Test ***********/
$(".addtestrow").click(function(){

  var i=$('#test_name_table tr').length;
        $("#test_name_table").append('<tr><td><input type="text" name="test_name[]" class="test_val'+i+'"></td><input type="hidden" name="test_id[]" class="test_id'+i+'" ><td><input type="text" name="test_date[]" class="datepicker1'+i+'"></td>                       <td><input type="text" name="test_result[]" class="medicine-name result_val'+i+'"></td>                        <td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_test_row">Delete</a></td></tr>');
      

      /* script start */
$(function () 
{

      var getData = function (request, response) {  
        row = i;
        $.ajax({
        url : "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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
          $('.test_val'+i).val(names[0]); 
          $('.test_id'+i).val(names[1]);
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".test_val"+i).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });

    $('.datepicker1'+i).datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });
    });

 });
    $("#test_name_table").on('click','.remove_test_row',function(){
        $(this).parent().parent().remove();
    });

    /************** Add Test ***********/


/**********Auto complete ***********/
$(function () 
{
    var i=$('#prescription_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
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
          $('.medicine_val').val(names[0]);
          $('#type').val(names[1]);
          $('#brand').val(names[2]);
          $('#salt').val(names[3]);
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

/**********Auto complete ***********/


/**********test Auto complete ***********/
$(function () 
{
    var i=$('#test_name_table tr').length;
      var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('opd/get_test_vals/'); ?>" + request.term,
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
          $('.test_val').val(names[0]);
           $('.test_id').val(names[1]);
          
        //$(".medicine_val").val(ui.item.value);
        return false;
    }

    $(".test_val").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    });

/**********Test Auto complete ***********/
</script>  
<script type="text/javascript">
  $('#content, #content1').on('change keyup keydown paste cut', 'textarea', function () {
        $(this).height(70).height(this.scrollHeight);
    }).find('textarea').change();
</script>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>