<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>script.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>custom.js"></script>

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 



</head>

<body>
 

<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
    <div class="userlist-box">
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
     <form id="sms_setting_form">


     
      <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3"><strong>Form Name</strong></div>
                <?php if(in_array('640',$users_data['permission']['action'])){ ?>
                <div class="col-xs-4"><strong>SMS</strong></div>
                <?php }  ?>
              </div>
        </div>
      </div> <!-- row -->

      


      <?php
        if(in_array('639',$users_data['permission']['action']))
        {
        if(!empty($sms_setting_list))
        {  
          foreach($sms_setting_list as $tab_setting_list)
          {
        ?>
     <!--  <div class="row">
        <div class="col-xs-10 br-h-small">
              <div class="row">
                <div class="col-xs-3">
                  <strong><?php echo $tab_setting_list->var_title; ?></strong> 
               </div>

               <?php if(in_array('640',$users_data['permission']['action']))
                     { ?>
                <div class="col-xs-4">
                  <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                </div>
                <?php 
                    } 
                    ?>
              <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
              </div>
        </div>
      </div> --> <!-- row -->








      <?php 

            if($tab_setting_list->setting_name=='ipd_assigned_doctor' && in_array('121',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='opd_appointment' && in_array('93',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if(($tab_setting_list->setting_name=='opd_booking' || $tab_setting_list->setting_name=='opd_next_appointment') && in_array('85',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if(($tab_setting_list->setting_name=='opd_billing' || $tab_setting_list->setting_name=='opd_billing_next_appointment') && in_array('151',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                  <div class="col-xs-5">
                  <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                  </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if(($tab_setting_list->setting_name=='sale_medicine' || $tab_setting_list->setting_name=='sale_medicine_next_appointment') && in_array('60',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='sale_medicine_return' && in_array('61',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='purchase_medicine' && in_array('58',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='purchase_medicine_return' && in_array('59',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='employee_salary' && in_array('36',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='vender_payment' && in_array('99',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='balance_clearance' && in_array('39',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='doctor_payment' && in_array('146',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

           
            if($tab_setting_list->setting_name=='reset_password')
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='forgot_password')
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='ipd_booking' && in_array('121',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='advance_payment' && in_array('127',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if(($tab_setting_list->setting_name=='discharge_bill' || $tab_setting_list->setting_name=='ipd_next_appointment') && in_array('130',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if(($tab_setting_list->setting_name=='patient_registration' || $tab_setting_list->setting_name=='update_patient_mobile') && in_array('19',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            if($tab_setting_list->setting_name=='doctor_registration' && in_array('20',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='branch_registration' && in_array('1',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='user_registration' && in_array('5',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

             if(($tab_setting_list->setting_name=='test_booking' || $tab_setting_list->setting_name=='pathology_next_appointment') && in_array('145',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='test_report_complete' && in_array('149',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='doctor_payment_received' && in_array('146',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }

            if($tab_setting_list->setting_name=='expenses' && in_array('35',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            //echo $tab_setting_list->setting_name;
         
        
        if($tab_setting_list->setting_name=='ot_next_appointment' && in_array('134',$users_data['permission']['section']))
            {
              ?>
                 <div class="row">
                <div class="col-xs-10 br-h-small">
                      <div class="row">
                        <div class="col-xs-3">
                          <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                       </div>
         
                      
                        <div class="col-xs-5">
                        <input type="checkbox" class="m-l-1em m18" name="data[<?php echo $tab_setting_list->id; ?>][sms_status]"  value="1" <?php if($tab_setting_list->sms_status==1){ ?> checked="checked" <?php } ?>>
                        </div>
                      


                      <input name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>" type="hidden">
                      </div>
                </div>
              </div> 
              <?php 
            }
            //echo $tab_setting_list->setting_name;
        ?>
    
        <?php
            







      
        
            }
          }
         ?> 
      <div class="row">
        <div class="col-xs-7">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-9">
                    <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i>  Save</button>
                    <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
                               </div>
              </div>
        </div>
      </div> <!-- row -->
      <?php } ?>
        </form>


   </div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

 function onlyAlphabets(e, t) {

            try {

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;

                }

                else { return true; }

                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))

                    return true;

                else

                    return false;

            }

            catch (err) {

                alert(err.Description);

            }

        } 
 
$("#sms_setting_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>sms_setting/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       flash_session_msg(result);    
       $('.overlay-loader').hide();    
    }
  });
});

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
</script>   
</div><!----container-fluid--->
</body>
</html>