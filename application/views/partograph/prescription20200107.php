<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


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
<style type="text/css">
 .w-100{
  width: 100%!important;
  } 
  .nobp{
    opacity: 0;
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
    <form id="prescription_form" name="prescription_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <!--  // prescription button modal -->


                <div class="row">
                    <div class="col-xs-2">
                        <!-- <button class="btn-commission2" type="button"  data-toggle="modal" data-target="#prescription_select_patient"> Select Patient</button> -->
                        <a class="btn-custom m-l-0 m-b-5" href="<?php echo base_url('opd'); ?>"><i class="fa fa-user"></i> <b>Registered Patient</b></a>
                        
                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>OPD No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_code" value="<?php echo $form_data['booking_code']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                                <input type="text"  name="patient_code" value="<?php echo $form_data['patient_code']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Patient Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?php echo $form_data['patient_name']; ?>" readonly="">
                            </div>
                        </div>


                        <!--  <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="aadhaar_no" value="<?php echo $form_data['aadhaar_no']; ?>" readonly="">
                                 <?php if(!empty($form_error)){ echo form_error('aadhaar_no'); } ?>
                            </div>
                        </div> -->

                         <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Gravida</strong></div>
                            <div class="col-xs-8">
                                <input type="text" class="numeric number" name="gravida" value="<?php echo $form_data['gravida']; ?>" >
                                 <?php if(!empty($form_error)){ echo form_error('gravida'); } ?>
                            </div>
                        </div>

                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Booking Date</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_date" value="<?php echo date('d-m-Y',strtotime($form_data['booking_date'])); ?>" readonly>
                                 <?php if(!empty($form_error)){ echo form_error('booking_date'); } ?>
                            </div>
                        </div>

                    </div> <!-- 5 -->
                    <div class="col-xs-5">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" readonly="">
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                               <input type="radio" name="gender" value="1" <?php if($form_data['gender']==1){ echo 'checked="checked"'; } ?> readonly=""> Male &nbsp;
                                <input type="radio" name="gender" value="0" <?php if($form_data['gender']==0){ echo 'checked="checked"'; } ?> readonly=""> Female
                                <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
                            </div>
                        </div>
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>DOB</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="age_y" onkeypress="return isNumberKey(event);" class="input-tiny" onkeypress="return isNumberKey(event);" maxlength="3" value="<?php echo $form_data['age_y']; ?>" readonly=""> Y &nbsp;
                              <?php if(!empty($form_error)){ echo form_error('age_y'); } ?>
                            </div>
                        </div>
                        
                        <!-- new code by mamta -->
  <div class="row m-b-5">
    <div class="col-xs-4">
      <strong> 
      <select name="relation_type"  class="w-90px" onchange="father_husband_son(this.value);" >
      <?php foreach($gardian_relation_list as $gardian_list) 
      {?>
      <option value="<?php echo $gardian_list->id;?>" <?php if(isset($form_data['relation_type']) && $form_data['relation_type']==$gardian_list->id){echo 'selected';}?>><?php echo $gardian_list->relation;?></option>
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
                  if(isset($form_data['relation_simulation_id']) && $simulation->id==$form_data['relation_simulation_id'])
                  {
                       $selected_simulation = 'selected="selected"';
                  }
                       
                echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
              
            }
            ?> 
        </select> 
        <input type="text" value="<?php if(isset($form_data['relation_name'])){ echo $form_data['relation_name'];}?>" name="relation_name" id="relation_name" class="mr-name m_name" readonly />
      </div>
    </div> <!-- row -->

<!-- new code by mamta -->
                
                  <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Para</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="para" class="numeric number" value="<?php echo $form_data['para']; ?>" >
                            </div>
                        </div>
                   <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Booking Time</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_time" value="<?php echo date('h:i a',strtotime($form_data['booking_time'])); ?>"  readonly="">
                            </div>
                        </div>


                    </div> <!-- 5 -->
                </div> <!-- row -->

     

                <div class="row m-t-10">
                    <div class="col-xs-11">
                        <ul class="nav nav-tabs" >
                        <?php 
                            $i=1; 
                            foreach ($partograph_tab_setting as $value) { 

                                 
                            ?>
                                <li <?php if($i==1){  ?> class="active" <?php }  ?> ><a onclick="return tab_links('tab_<?php echo strtolower($value->setting_name); ?>')" data-toggle="tab" href="#tab_<?php echo strtolower($value->setting_name); ?>"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></a></li>
                                <?php 
                            $i++;
                            }
                         ?>
                          
                        </ul>

                       <?php 
                            $j=1; 
                         // echo "<pre>";  print_r($prescription_tab_setting);die();
                            foreach ($partograph_tab_setting as $value) { 
                            ?>
                                <div class="tab-content">

                            <?php 
                            if(strcmp(strtolower($value->setting_name),'foetal_heart_rate')=='0'){ ?>

                            <div id="tab_foetal_heart_rate" class="inner_tab_box tab-pane fade  <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_fhr">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_fhr">Add</a>
                                                        </td>
                                                    </tr>
                                          <?php 
                                            if(!empty($foetal_heart_list))
                                            { 
                                                $y=1;
                                                foreach ($foetal_heart_list as $foetal_heart) 
                                                { $y++;
                                                    ?>
                                                    <tr>
                                                        <td><input type="text" name="fhr_test_value[]" class="w-100 numeric number" value="<?php echo $foetal_heart->value; ?>" placeholder="Value" >
                                                
                                                        </td>
                                                         <td><input type="text" name="fhr_test_time[]"
                                                         placeholder="Time" value="<?php echo date('h:i a',strtotime($foetal_heart->time)); ?>" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                        
                                                        <td width="100">
                                                            <a onclick="delete_row1(this)" href="javascript:void(0)" class="fhr_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                   <script type="text/javascript">
                                                      function delete_row1(r)
                                                      { 
                                                          var i = r.parentNode.parentNode.rowIndex;
                                                          document.getElementById("test_name_table_fhr").deleteRow(i);
                                                      }
                                                    </script>
                                                  <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                             <?php  if(strcmp(strtolower($value->setting_name),'temp')=='0'){  ?>
                            <div id="tab_temp" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_tm">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_tm">Add</a>
                                                        </td>
                                                    </tr>
                                        <?php 
                                            if(!empty($temp_list))
                                            { 
                                                $a=1;
                                                foreach ($temp_list as $temp) 
                                                { $a++;
                                                    ?>
                                                    <tr>
                                                        <td><input type="text" name="tm_test_value[]" class="w-100 numeric number" value="<?php echo $temp->value; ?>" placeholder="Value" >
                                                
                                                        </td>
                                                         <td><input type="text" name="tm_test_time[]"
                                                         placeholder="Time" value="<?php echo date('h:i a',strtotime($temp->time)); ?>" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                        
                                                        <td width="100">
                                                            <a onclick="delete_row2(this)" href="javascript:void(0)" class="tm_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                      <script type="text/javascript">
                                                      function delete_row2(r2)
                                                      { 
                                                          var i2 = r2.parentNode.parentNode.rowIndex;
                                                          document.getElementById("test_name_table_tm").deleteRow(i2);
                                                      }
                                                    </script>
                                                  <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                             <?php 

                             if(strcmp(strtolower($value->setting_name),'contraction_per_10_min')=='0'){ ?>
                            <div id="tab_contraction_per_10_min" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_cpm">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                        
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_cpm">Add</a>
                                                        </td>
                                                    </tr>
                                          <?php 
                                            if(!empty($contraction_list))
                                            { 
                                                $b=1;
                                                foreach ($contraction_list as $contraction) 
                                                { $b++;
                                                    ?>  
                                                    <tr>
                                                        <td>
                                                          <select name="cpm_test_value[]" class="w-100">
                                                            <option <?php if($contraction->value==2){echo "selected";}?> value="2">Regular contractions</option>
                                                            <option <?php if($contraction->value==3){echo "selected";}?> value="3">Irregular contractions</option>
                                                            <option <?php if($contraction->value==4){echo "selected";}?> value="4">Progressing contractions</option>
                                                            <option <?php if($contraction->value==5){echo "selected";}?> value="5">Non-progressing contractions</option>
                                                          </select>
                                                          <!-- <input type="text" name="cpm_test_value[]" class="w-100 numeric number" value="<?php echo $contraction->value; ?>" placeholder="Value" > -->
                                                
                                                        </td>
                                                         <td><input type="text" name="cpm_test_time[]"
                                                         placeholder="Time" value="<?php echo date('h:i a',strtotime($contraction->time)); ?>" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a onclick="delete_row3(this)" href="javascript:void(0)" class="cpm_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                  <script type="text/javascript">
                                                  function delete_row3(r3)
                                                  { 
                                                      var i3 = r3.parentNode.parentNode.rowIndex;
                                                      document.getElementById("test_name_table_cpm").deleteRow(i3);
                                                  }
                                                </script>
                                              <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php 
                            if(strcmp(strtolower($value->setting_name),'drugs_and_lv_fluid_given')=='0'){ ?>
                             <div id="tab_drugs_and_lv_fluid_given" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_dlf">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_dlf">Add</a>
                                                        </td>
                                                    </tr>
                                           <?php 
                                            if(!empty($drugs_fluid_list))
                                            { 
                                                $c=1;
                                                foreach ($drugs_fluid_list as $drugs_fluid) 
                                                { $c++;
                                                  ?>   
                                                    <tr>
                                                        <td><input type="text" name="dlf_test_value[]" class="w-100" value="<?php echo $drugs_fluid->value; ?>" placeholder="Value" >
                                                
                                                        </td>
                                                         <td><input type="text" value="<?php echo date('h:i a',strtotime($drugs_fluid->time)); ?>" name="dlf_test_time[]"
                                                         placeholder="Time" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a onclick="delete_row4(this)" href="javascript:void(0)" class="dlf_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>
                                                <script type="text/javascript">
                                                  function delete_row4(r4)
                                                  { 
                                                      var i4 = r4.parentNode.parentNode.rowIndex;
                                                      document.getElementById("test_name_table_dlf").deleteRow(i4);
                                                  }
                                                </script>
                                              <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php if(strcmp(strtolower($value->setting_name),'pulse_bp')=='0'){  ?>
                            <div id="tab_pulse_bp" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_pb">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                        
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_pb">Add</a>
                                                        </td>
                                                    </tr>
                                          <?php 
                                            if(!empty($pulse_bp_list))
                                            { 
                                             //echo "<pre>"; print_r($pulse_bp_list);die();
                                                $d=0;
                                                foreach ($pulse_bp_list as $pulse_bp) 
                                                { $d++;
                                                    ?>
                                                    <tr>
                                                        <td><input type="text" name="pb_test_value[]" class="w-100 numeric number" value="<?php echo $pulse_bp->value; ?>" placeholder="Value" >
                                                
                                                        </td>

                                                        <td><input type="text" name="pb_test_value_x[]" class="w-100 numeric number" value="<?php echo $pulse_bp->value_x; ?>" placeholder="Value of X" >
                                                
                                                        </td>
                                                        <?php if(!empty($pulse_bp->value_low_bp)) {?>
                                                         <td>
                                                          <input type="text" name="pb_test_value_low[]" class="w-40 numeric number" value="<?php echo $pulse_bp->value_low_bp; ?>" placeholder="BP Lower" >
                                                          <input type="text" name="pb_test_value_high[]" class="w-40 numeric number" value="<?php echo $pulse_bp->value_high_bp; ?>" placeholder="BP Uper" >
                                                        </td>
                                                      <?php } else{?>
                                                        <td><input type="text" name="pb_test_value_low[]" class="w-40 numeric number nobp" readonly><input type="text" name="pb_test_value_high[]" class="w-40 numeric number nobp" readonly></td>
                                                         <?php } ?>
                                                        <td width="100">
                                                            <a onclick="delete_row5(this)" href="javascript:void(0)" class="pb_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>

                                                  <script type="text/javascript">
                                                  function delete_row5(r5)
                                                  { 
                                                      var i5 = r5.parentNode.parentNode.rowIndex;
                                                      document.getElementById("test_name_table_pb").deleteRow(i5);
                                                  }
                                                </script>
                                              <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php  if(strcmp(strtolower($value->setting_name),'amniotic_fluid')=='0'){  ?>
                            <div id="tab_amniotic_fluid" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table_af">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                        
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow_af">Add</a>
                                                        </td>
                                                    </tr>
                                          <?php 
                                            if(!empty($amniotic_fluid_list))
                                            { 
                                                $e=1;
                                                foreach ($amniotic_fluid_list as $amniotic_fluid) 
                                                { $e++;
                                                    ?>
                                                    <tr>
                                                        <td><input type="text" name="af_test_value[]" value="<?php echo $amniotic_fluid->value; ?>" class="w-100" placeholder="Value" >
                                                
                                                        </td>
                                                         <td><input type="text" value="<?php echo date('h:i a',strtotime($amniotic_fluid->time)); ?>" name="af_test_time[]"
                                                         placeholder="Time" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a onclick="delete_row6(this)" href="javascript:void(0)" class="af_delete btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>

                                                    <script type="text/javascript">
                                                    function delete_row6(r6)
                                                    { 
                                                        var i6 = r6.parentNode.parentNode.rowIndex;
                                                        document.getElementById("test_name_table_af").deleteRow(i6);
                                                    }
                                                  </script>
                                                <?php }}?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                             <?php if(strcmp(strtolower($value->setting_name),'cervic')=='0'){  ?>
                            <div id="tab_cervic" class="inner_tab_box tab-pane fade <?php if($j==1){ ?> in active <?php  } ?>">
                                <div class="row m-t-10">
                                    <div class="col-xs-12">
                                        <div class="well tab-right-scroll">
                                            <table class="table table-bordered table-striped" id="test_name_table">
                                                <tbody>
                                                    <tr>
                                                        <td width="100"><?php if(!empty($value->setting_value)) { echo $value->setting_value; } else { echo $value->var_title; } ?></td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                         <td width="100">
                                                        
                                                        </td>
                                                        <td width="100">
                                                            <a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>
                                                        </td>
                                                    </tr>
                                          <?php 
                                            if(!empty($cervic_list))
                                            {
                                                $f=1;
                                                foreach ($cervic_list as $cervic) 
                                                { $f++;
                                                    ?>
                                                    <tr>
                                                        <td> 
                                                          <input type="text" name="cervic_test_value[]" value="<?php echo $cervic->value; ?>" class="w-100 numeric number" placeholder="Value" >
                                                
                                                        </td>
                                                        <td> 
                                                          <input type="text" name="cervic_test_value_x[]" value="<?php echo $cervic->value_x; ?>" class="w-100 numeric number" placeholder="Value of X" >
                                                
                                                        </td>
                                                         <td><input type="text" name="cervic_test_time[]" value="<?php echo date('h:i a',strtotime($cervic->time)); ?>"
                                                         placeholder="Time" class="w-100 datepicker3" >
                                                        
                                                        </td>
                                                         
                                                        <td width="100">
                                                            <a onclick="delete_row7(this)" href="javascript:void(0)" class="btn-w-60">Delete</a>
                                                        </td>
                                                    </tr>

                                                    <script type="text/javascript">
                                                    function delete_row7(r7)
                                                    { 
                                                        var i7 = r7.parentNode.parentNode.rowIndex;
                                                        document.getElementById("test_name_table").deleteRow(i7);
                                                    }
                                                  </script>
                                                <?php }}?> 

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                         
                        </div>



                                <?php 
                            $j++;
                            }
                         ?>


                        
                    </div> <!-- 11 -->
                



            <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>">                    
            <input type="hidden" name="attended_doctor" value="<?php echo $form_data['attended_doctor']; ?>">
             <input type="hidden" name="appointment_date" value="<?php echo $form_data['appointment_date']; ?>">
              <input type="hidden" name="booking_id" value="<?php echo $form_data['booking_id']; ?>">
            <input type="hidden" name="patient_id" value="<?php echo $form_data['patient_id']; ?>">    
           <div class="col-xs-1">
            <div class="prescription_btns">
                <button class="btn-update" type="button" id="form_submit"><i class="fa fa-floppy-o"></i> Save</button>
              
                <a href="<?php echo base_url('partograph'); ?>"  class="btn-anchor" >
          <i class="fa fa-sign-out"></i> Exit
        </a>
            </div>


            </div> <!-- row -->
        





</form>
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
<script type="text/javascript">
  function tab_links(vals)
  {
    $('.inner_tab_box').removeClass('in');
    $('.inner_tab_box').removeClass('active');  
    $('#'+vals).addClass('class','in');
    $('#'+vals).addClass('class','active');
  }

  $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>opd/get_template_data/"+template_id, 
        success: function(result)
        {
           load_values(result);
           load_test_values(template_id);
           load_prescription_values(template_id);
        } 
      }); 
  });


  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       $('#prescription_medicine').val(obj.prescription_medicine);
       $('#prv_history').val(obj.prv_history);
       $('#personal_history').val(obj.personal_history);
       $('#chief_complaints').val(obj.chief_complaints);
       $('#examination').val(obj.examination);
       $('#diagnosis').val(obj.diagnosis);
       $('#suggestion').val(obj.suggestion);
       $('#remark').val(obj.remark);
       $('#appointment_date').val(obj.appointment_date);
       

       
    };

    function load_test_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>opd/get_template_test_data/"+template_id, 
        success: function(result)
        {
           get_test_values(result);
        } 
      });
    }
/*<a href="javascript:void(0)" class="btn-w-60 addrow">Add</a>*/
    function get_test_values(result)
    {
      
      var obj = JSON.parse(result);
      var arr = '';
      arr += '<tbody><tr><td>Test Name</td><td width="80"></td></tr>';
      var i=$('#test_name_table tr').length;
      $.each(obj, function (index, value) {
     
         arr += '<tr><td><input type="text"   name="test_name[]" class="w-100 test_val'+i+'" value="'+obj[index].test_name+'"><input type="hidden" id="test_id'+i+'" name="test_id[]" class="w-100" value="'+obj[index].test_id+'"></td></tr>';

         $(function () {



    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('prescription/get_test_vals/'); ?>" + request.term,
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

          var test_names = ui.item.data.split("|");

          $('.test_val'+i).val(test_names[0]);
          $('#test_id'+i).val(test_names[1]);
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
    
    });
        

    }); 

    arr += '</tbody>'; 
      
      $("#test_name_table tbody").replaceWith(arr);
     
    }

    function load_prescription_values(template_id)
    {
        $.ajax({url: "<?php echo base_url(); ?>opd/get_template_prescription_data/"+template_id, 
        success: function(result)
        {
           get_prescription_values(result);
        } 
      });
    }
    
      $(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });

    $('.datepickerewe').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
    });
    $('.datepicker1').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 2,
        forceParse: 0
    });
    $('.datepicker2').datetimepicker({
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 1,
        minView: 0,
        maxView: 1,
        forceParse: 0
    });


$(document).ready(function(){

    $(".addrow").click(function(){ 
      var i=$('#test_name_table tr').length;
      $("#test_name_table").append('<tr><td><input type="text" name="cervic_test_value[]" class="w-100 test_val numeric number'+i+'" placeholder="Value" ></td><td><input type="text" name="cervic_test_value_x[]" class="w-100 test_val numeric number'+i+'" placeholder="Value of X" ></td><td><input type="text" name="cervic_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row">Delete</a></td></tr>');
      times();
    });
    $("#test_name_table").on('click','.remove_row',function(){
        $(this).parent().parent().remove();
    });

    $(".addrow_fhr").click(function(){ 
      var i1=$('#test_name_table_fhr tr').length;
        $("#test_name_table_fhr").append('<tr><td><input type="text" name="fhr_test_value[]" class="w-100 test_val numeric number'+i1+'" placeholder="Value" ></td><td><input type="text" name="fhr_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row1">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_fhr").on('click','.remove_row1',function(){
        $(this).parent().parent().remove();
    });

 $(".addrow_pb").click(function(){ 
      var i3=$('#test_name_table_pb tr').length;
      console.log(i3);
      if(i3===1 || i3%8===0){
        var puls=' <td><input type="text" name="pb_test_value_low[]" class="w-40 numeric number" value="" placeholder="BP Lower" ><input type="text" name="pb_test_value_high[]" class="w-40 numeric number" value="" placeholder="BP Uper" ></td>';
      }
      else{
        var puls='<td><input type="text" name="pb_test_value_low[]" class="w-40 numeric number nobp" readonly><input type="text" name="pb_test_value_high[]" class="w-40 numeric number nobp" readonly></td>';
      }
        $("#test_name_table_pb").append('<tr><td><input type="text" name="pb_test_value[]" class="w-100 test_val numeric number'+i3+'" placeholder="Value" ></td><td><input type="text" name="pb_test_value_x[]" value="'+i3/2+'" class="w-100 test_val numeric number'+i3+'" placeholder="Value of X" ></td>'+puls+'<td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row3">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_pb").on('click','.remove_row3',function(){
        $(this).parent().parent().remove();
    });

$(".addrow_dlf").click(function(){ 
      var i4=$('#test_name_table_dlf tr').length;
        $("#test_name_table_dlf").append('<tr><td><input type="text" name="dlf_test_value[]" class="w-100 number'+i4+'" placeholder="Value" ></td><td><input type="text" name="dlf_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row4">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_dlf").on('click','.remove_row4',function(){
        $(this).parent().parent().remove();
    });

    $(".addrow_cpm").click(function(){ 
      var i5=$('#test_name_table_cpm tr').length;
        $("#test_name_table_cpm").append('<tr><td> <select name="cpm_test_value[]" class="w-100"> <option value="2">Regular contractions</option><option value="3">Irregular contractions</option><option value="4">Progressing contractions</option><option value="5">Non-progressing contractions</option></select></td><td><input type="text" name="cpm_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row5">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_cpm").on('click','.remove_row5',function(){
        $(this).parent().parent().remove();
    });

    $(".addrow_tm").click(function(){ 
      var i6=$('#test_name_table_tm tr').length;
        $("#test_name_table_tm").append('<tr><td><input type="text" name="tm_test_value[]" class="w-100 test_val numeric number'+i6+'" placeholder="Value" ></td><td><input type="text" name="tm_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row6">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_tm").on('click','.remove_row6',function(){
        $(this).parent().parent().remove();
    });


  $(".addrow_af").click(function(){ 
      var i2=$('#test_name_table_af tr').length;
        $("#test_name_table_af").append('<tr><td><input type="text" name="af_test_value[]" class="w-100 number'+i2+'" placeholder="Value" ></td><td><input type="text" name="af_test_time[]" placeholder="Time" class="w-100 datepicker3" ></td><td width="80"><a href="javascript:void(0);" class="btn-w-60 remove_row2">Delete</a></td></tr>');
        times();
    });
    $("#test_name_table_af").on('click','.remove_row2',function(){
        $(this).parent().parent().remove();
    });
   times();
});

 function times()
    {
      $('.datepicker3').datetimepicker({
          format: 'LT'
         });
    }

$('#form_submit').on("click",function(){
    $(':input[id=form_submit]').prop('disabled', true);
       $('#prescription_form').submit();
  })
</script>

<script type="text/javascript">
$(document).ready(function(){ 
    $("#myTab li:eq(1) a").tab('show');
});


</script>

</body>
</html>