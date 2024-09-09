<?php
$users_data = $this->session->userdata('auth_users'); 
//print_r($form_data);die;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width initial-scale=1.0">
    <title>CRM | Add Lead</title>
    <!-- GLOBAL MAINLY STYLES-->
    
    <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/themify-icons/css/themify-icons.css" rel="stylesheet" />
    <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
    <!-- PLUGINS STYLES-->
    <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/DataTables/datatables.min.css" rel="stylesheet" />
    <!-- THEME STYLES-->
    <link href="<?php echo ROOT_ASSETS_PATH; ?>css/main.min.css" rel="stylesheet" />
    <link href="<?php echo ROOT_ASSETS_PATH; ?>css/themes/green-light.css" rel="stylesheet" /> 
    <!-- <link href="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/jquery/dist/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>


    <!-- PAGE LEVEL STYLES-->
    <style type="text/css">

        .fleft
        {
            float: left;
        }
        .text-danger {
            color: #cc0000;
            font-size: 9pt;
            text-align: left;
        }
        .bold
        {
            font-weight: bold;
        }
        .tab-heading
        {
        font-weight: bold;
        font-size: 15px;
        background-color: #eee;
        margin: 10px 0px 10px;
        height: 35px;
        padding:5px 0px 0px 0px;
        }
        a.btn-new, .btn-new
        {
            width: 70px;
            height: 18pt;
            line-height: 1.3;
            background: #F7f7f7;
            color: #333;
            border: 1px solid #999;
            border-radius: 3px;
            text-decoration: none !important;
            padding: 2px 0.5em;
        }

        .boxleft 
        {
            float: left; 
            height: 200px;
            border: 1px solid #ccc;
            *overflow-y: scroll;
            margin-right: 2%;
        }

        .boxright {
            float: left; 
            height: 200px;
            border: 1px solid #aaa;
                border-top-color: rgb(170, 170, 170);
                border-top-style: solid;
                border-top-width: 1px;
            border-top: 1px solid #aaa;
            overflow-y: scroll;
        }
        .pb-tbl4 {
            width: 100%;
        }
        .bg-theme {
            background: #2ecc71;
            color: #FFF;
        }
        .pb-tbl5 {
                    float: left;
                    width: 100%;
                    clear: both;
                    border: none;
                    font-size: 14px;
                }   
        .box .bk-tst-dtl {
            float: left;
            width: 91%;
            height: 200px;
            overflow-y: scroll;
            border: 1px solid #ccc;
            clear: both;
        }            
    </style>
</head>

<body class="fixed-navbar has-animation fixed-layout">
    <div class="page-wrapper">
        <!-- START HEADER-->
        <?php $this->load->view('include/header'); ?>
        <!-- END HEADER-->
        <!-- START SIDEBAR-->
       <?php $this->load->view('include/sidebar'); ?>
        <!-- END SIDEBAR-->
        <div class="content-wrapper">
            <!-- START PAGE CONTENT-->
            <div class="page-heading">
                <h1 class="page-title"><?php echo $page_title; ?></h1>  
            </div>
            <div class="page-content fade-in-up">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title"><?php echo $page_title; ?></div> 
                    </div>
                    <div class="ibox-body">
                         <form action="<?php echo $save_url; ?>" method="post" id="booking_form" name="lead_form">
                         <input type="hidden" name="data_id" value="<?php echo $form_data['data_id']; ?>">   
                         <div class="row">
                               
                         </div>

                         <div class="row">

                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Lead ID</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="lead_id" value="<?php echo $form_data['lead_id']; ?>" readonly>
                                    <?php if(!empty($form_error)){ echo form_error('lead_id'); } ?>
                                </div>
                            </div>

                              <div class="form-group col-sm-4">
                                    <label class="col-sm-4 fleft bold">Lead Type</label>
                                    <div class="col-sm-8 fleft">
                                        <select name="lead_type_id" id="lead_type_id" class="form-control" onchange="return set_lead_type(this.value)">
                                            <option value="">Select Lead Source</option>
                                            <?php
                                             if(!empty($lead_type_list))
                                             {
                                                foreach($lead_type_list as $lead_type_id)
                                                {
                                                    $lead_type_select = '';
                                                    if($form_data['lead_type_id']==$lead_type_id->id)
                                                    {
                                                        $lead_type_select = 'selected="selected"';
                                                    }    
                                                        echo '<option '.$lead_type_select.' value="'.$lead_type_id->id.'">'.$lead_type_id->lead_type.'</option>';
                                                    
                                                }
                                             }
                                            ?>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('lead_type_id'); } ?>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="col-sm-4 fleft bold"> Source</label>
                                    <div class="col-sm-8 fleft">
                                        <select name="lead_source_id" id="lead_source_id" class="form-control">
                                            <option value="">Select Lead Type</option>
                                            <?php
                                             if(!empty($lead_source_list))
                                             {
                                                foreach($lead_source_list as $lead_source)
                                                {
                                                    $lead_source_select = '';
                                                    if($form_data['lead_source_id']==$lead_source->id)
                                                    {
                                                        $lead_source_select = 'selected="selected"';
                                                    }    
                                                        echo '<option '.$lead_source_select.' value="'.$lead_source->id.'">'.$lead_source->source.'</option>';
                                                    
                                                }
                                             }
                                            ?>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('lead_source_id'); } ?>
                                    </div>
                                </div>

                         </div>


                         <div class="row">
                            <div class="col-sm-12 tab-heading" style="padding-left: 15px;">
                                Patient Details 
                            </div>

                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Name</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="name" value="<?php echo $form_data['name']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Email</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="email" value="<?php echo $form_data['email']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Phone</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" maxlength="10" class="form-control" name="phone" value="<?php echo $form_data['phone']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('phone'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Age</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" maxlength="3" class="form-control" name="age" value="<?php echo $form_data['age']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('age'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Address</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="address" value="<?php echo $form_data['address']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('address'); } ?>
                                </div>
                            </div>

                         </div>

                         <div class="row">
                            <div class="col-sm-12 tab-heading">
                            <div class="col-sm-3 fleft" style="width: 180px;">Service Department</div>
                            
                            <div class="col-sm-3 fleft">
                                <select name="department_id" id="department_id" class="form-control" onchange="return department_data(this.value);">
                                            <option value="">Select Department</option>
                                            <?php
                                             if(!empty($department_list))
                                             {
                                                foreach($department_list as $department)
                                                {
                                                    $lead_source_select = '';
                                                    if($form_data['department_id']==$department->id)
                                                    {
                                                        $lead_source_select = 'selected="selected"';
                                                    }    
                                                        echo '<option '.$lead_source_select.' value="'.$department->id.'">'.$department->department.'</option>';
                                                    
                                                }
                                             }
                                            ?>
                                        </select>
                                        <?php  if(!empty($form_error)){ echo form_error('department_id'); } ?>
                            </div>
                         </div>
                         </div>
                        <!-- Billing -->
                        <div class="row billing_booking_box" <?php if($form_data['department_id']!=4){ ?> style="display: none;" <?php } ?>>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Services</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="billing_service" value="<?php echo $form_data['billing_service']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('billing_service'); } ?>
                                </div>
                            </div>
                        </div>
                        <!-- Billing -->
                        <!-- IPD -->
                        <div class="row ipd_booking_box" <?php if($form_data['department_id']!=5){ ?> style="display: none;" <?php } ?>>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Services</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="ipd_service" value="<?php echo $form_data['ipd_service']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('ipd_service'); } ?>
                                </div>
                            </div>
                        </div>
                        <!-- IPD -->
                        <!-- IPD -->
                        <div class="row ot_booking_box" <?php if($form_data['department_id']!=6){ ?> style="display: none;" <?php } ?>>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Surgery</label>
                                <div class="col-sm-8 fleft">
                                    <select name="ot_id" class="w-145px form-control" id="ot_id">
                                      <option value="">Select Surgery</option>
                                      <?php foreach($operation_list as $op_list)
                                      {?>
                                      <option value="<?php echo $op_list->id;?>" <?php if(isset($form_data['ot_id']) && $form_data['ot_id']== $op_list->id){echo 'selected';}?>  > <?php echo $op_list->name; ?>   </option>
                                      <?php }?>

                                      </select>
                                    <?php if(!empty($form_error)){ echo form_error('ot_id'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Services</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="ot_service" value="<?php echo $form_data['ot_service']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('ot_service'); } ?>
                                </div>
                            </div>
                        </div>
                        <!-- IPD -->
                        <!-- OPD -->
                         <div class="row opd_booking_box" <?php if($form_data['department_id']!=1){ ?> style="display: none;" <?php } ?>>

                             <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Specialization</label>
                                <div class="col-sm-8 fleft">
                                    <select name="specialization_id" class="w-150px m_select_btn form-control" id="specialization_id" onChange="return get_doctor_specilization(this.value);">
                                          <option value="">Select Specialization</option>
                                          <?php

                                          if(!empty($specialization_list))
                                          {
                                            $select='';
                                            foreach($specialization_list as $specializationlist)
                                            {
                                              if($form_data['specialization_id']==$specializationlist->id)
                                              {
                                               $select='selected';
                                              }
                                              else
                                              {
                                                $select='';

                                              }

                                              ?>
                                                <option <?php echo $select; ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                                              <?php
                                            }
                                          }
                                          ?>
                                        </select> 
                                        <?php if(!empty($form_error)){ echo form_error('specialization_id'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Consultant</label>
                                <div class="col-sm-8 fleft">
                                    <select name="attended_doctor" class="form-control w-150px m_select_btn" id="attended_doctor">
                                      <option value="">Select Consultant</option>
                                      <?php                                         
                                        if(!empty($doctor_list))
                                        {
                                           foreach($doctor_list as $doctor)
                                           {    
                                          ?>   
                                            <option value="<?php echo $doctor->id; ?>" <?php if(!empty($form_data['attended_doctor']) && $form_data['attended_doctor'] == $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                                          <?php
                                            //}
                                         }
                                      }
                                   
                                  ?>
                                  </select>
                                    <?php if(!empty($form_error)){ echo form_error('attended_doctor'); } ?>
                                </div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Service</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" class="form-control" name="opd_service" value="<?php echo $form_data['opd_service']; ?>" >
                                    <?php if(!empty($form_error)){ echo form_error('opd_service'); } ?>
                                </div>
                            </div>
                            
                         </div>
                        <!-- OPD -->
                        

                        <!-- Lab -->
                        <?php
                        $path_arr = array(1,4,5,6);
                        ?>
                         <div class="row lab_booking_box" <?php if(in_array($form_data['department_id'], $path_arr)){ ?> style="display: none;" <?php } ?>>
                            <div class="row path_lab_box">
                                 <div class="col-md-12">&nbsp;</div>
                               <div class="col-md-12">

                                    <div class="form-group col-sm-4">
                                        <label class="col-sm-4 fleft bold">Department</label>
                                     <div class="form-group col-sm-8">   
                                         <select name="dept_id" id="dept_id" class="m_input_default form-control">
                                            <option value="">Select Department</option>
                                                  <?php
                                                   if(!empty($dept_list))
                                                   {
                                                      foreach($dept_list as $dept)
                                                      {
                                                          $dept_select = "";
                                                          if($dept->id==$form_data['dept_id'])
                                                          {
                                                              $dept_select = "selected='selected'";
                                                          }
                                                          echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                                                      }
                                                   }
                                                  ?>
                                          </select>
                                        </div>
                                          <?php if(!empty($form_error)){ echo form_error('dept_id'); } ?>
                                     </div>


                                     <div class="form-group col-sm-3">
                                        <label class="col-sm-3 fleft bold">Search</label>
                                        <div class="col-sm-9 fleft">
                                            <input type="text" class="form-control m_input_default" name="test_search" id="test_search" value=""> 
                                        </div>
                                    </div>

                                    <div class="form-group col-sm-5">
                                        
                                        <div class="col-sm-12 fleft">
                                            <label class="fleft bold">Profile </label>
                                            <select name="profile_id" class="form-control fleft" id="profile_id" onChange="get_profile_test();" style="width: 160px; margin-left: 3px;">
                                            <option value="">Select Profile</option>
                                                  <?php 
                                                    if(!empty($profile_list))
                                                   {
                                                      foreach($profile_list as $profile)
                                                      { 
                                                          $profile_select = "";
                                                          if($profile->id==$form_data['profile_id'])
                                                          {
                                                              $profile_select = "selected='selected'";
                                                          }
                            $profile_status = $profile_print_status->profile_status;
                                                          $print_status = $profile_print_status->print_status;
                                                          if($profile_status==1 && $print_status==1)
                                                          {
                                                            if(!empty($profile->print_name))
                                                            {
                                                              $profile_name = $profile->profile_name.' ('.$profile->print_name.')';
                                                            }
                                                            else
                                                            {
                                                              $profile_name = $profile->profile_name;
                                                            }
                                                          }
                                                          elseif($profile_status==1 && $print_status==0)
                                                          {
                                                            $profile_name = $profile->profile_name;
                                                          }
                                                          elseif($profile_status==0 && $print_status==1)
                                                          {
                                                            if(!empty($profile->print_name))
                                                            {
                                                              $profile_name = $profile->print_name;
                                                            }
                                                            else
                                                            {
                                                              $profile_name = $profile->profile_name;
                                                            }
                                                            
                                                          }
                                                          elseif($profile_status==0 && $print_status==0)
                                                          {
                                                            $profile_name = $profile->profile_name;
                                                          }
                                                          //echo "<pre>"; print_r($profile_print_status); exit;
                                                          
                                                          echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile_name.'</option>';
                                                          //echo '<option '.$profile_select.' value="'.$profile->id.'" >'.$profile->profile_name.'</option>';
                                                      }
                                                   } 
                                                  ?>
                                          </select> 
                                          
                                          <input type="text" name="rate" value="<?php echo $form_data['profile_price']; ?>" class="input-tiny m_tiny2 numeric form-control fleft" id="profile_price" style="width: 68px !important; margin-left: 3px;"/>
                                          &nbsp;&nbsp;
                                            <a class="btn-new fleft" onClick="add_profile();" style="margin-left: 3px; margin-top: 5px;">
                                              <i class="fa fa-plus"></i> Add
                                            </a>  
                                    </div>

                                      
                              </div>
                              <div class="col-md-12">&nbsp;</div>

                              <div class="form-group col-sm-4 ">
                                  <select size="9" class="dropdown-box boxleft" style="width: 100%; height: 200px;" name="dept_parent_test"  id="dept_parent_test" tabindex="14" >
                                     <?php
                                        if(isset($head_list) && !empty($head_list))
                                        {
                                          foreach($head_list as $head)
                                          {
                                             echo '<option value="'.$head->id.'">'.$head->test_heads.'</option>';
                                          }
                                        }
                                     ?>
                                  </select>
                              </div>

                              <div class="form-group col-sm-7 boxright" style="padding: 0;px;">
                                 <table class="pb-tbl4 table table-striped" id="test_list">
                                    <thead class="bg-theme">
                                    <tr>
                                      <th width="60" align="center">Select</th>
                                      <th>Test ID</th>
                                      <th>Test Name</th> 
                                      <th>Patient Rate</th>
                                    </tr>
                                    </thead>
                                    <tr>  
                                      <td colspan="5">
                                        <div class="text-danger p-l-half" style="text-align: center;">Test not available</div>
                                      </td>
                                    </tr>            
                                </table> 
                              </div>
                              <div class="form-group col-sm-1">
                                  <a class="btn-new" onClick="send_test(event);">
                                     <i class="fa fa-plus"></i> Add
                                  </a>
                              </div>

                              <div class="form-group col-sm-12" style="padding-right: 0px;">
                                  <div class="box path_lab_box">
    
                                    <legend>Booking Test Detail</legend>
                                    <div class="bk-tst-dtl">
                                      <table class="pb-tbl5 table table-striped" id="test_select">
                                        <thead class="bg-theme">
                                        <tr>
                                          <th width="40" align="center" style="text-align: center;"> <input type="checkbox" class="" name="select_all" id="select_all" onClick="final_toggle(this);"/> </th>
                                          <th>Test ID</th>
                                          <th>Test / Profile Name</th> 
                                          <th width="95px">Patient Rate</th>
                                        </tr>
                                        </thead>
                                        <?php
                                        $profile_data = $this->session->userdata('set_profile');
                                        //echo "<pre>";print_r($profile_data);die;
                                        $booked_arr = $this->session->userdata('book_test'); 
                                        $total_test = count($booked_arr);
                                        $profile_row = "";
                                        $p_order = 1;
                                        $profile_order = 1;
                                        if(isset($profile_data) && !empty($profile_data))
                                        {
                                          $pi = 1;
                                          foreach($profile_data as $profile_dat)
                                          {
                                             $profile_row .= '<tr>
                                                              <td width="40" align="center">
                                                                 <input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$profile_dat['id'].'" >
                                                              </td>
                                                              <td>'.$profile_dat['id'].'</td>
                                                              <td>'.$profile_dat['name'].'</td> 
                                                              <td>'.$profile_dat['price'].'</td>
                                                          </tr>';
                                          
                                           if($total_test>1)
                                            {
                                              $profile_order = $total_test-$profile_dat['order'];
                                              if($profile_order==0)
                                              {
                                                $profile_order = '1';
                                              }
                                            }
                                            $pi++;
                                          }                 
                                        }
                                        if(isset($booked_test_list) && !empty($booked_test_list))
                                        {
                                          $i = 1;          
                                          foreach($booked_test_list as $booked_test)
                                          {  
                                            ?>
                                              <tr>
                                                  <td width="40" align="center">
                                                     <input type="checkbox" name="test_id[]" class="booked_checkbox" value="<?php echo $booked_test->id; ?>" >
                                                  </td>
                                                  <td><?php echo $booked_test->test_code; ?></td>
                                                  <td><?php echo $booked_test->test_name; ?></td> 
                                                  <td><?php echo $booked_test->price; ?></td>
                                              </tr>       
                                            <?php
                                            if(isset($profile_data) && !empty($profile_data))
                                            { 
                                               if($i==1)
                                               {
                                                 echo $profile_row;
                                               }
                                            }
                                          $i++;  
                                          }
                                        }
                                        else
                                        {
                                          if(isset($profile_data) && !empty($profile_data))
                                          {
                                            echo $profile_row;
                                          }
                                          else
                                          {
                                             ?> 
                                            <tr>
                                              <td colspan="4" style="text-align: center;">
                                                 <div class="text-danger p-l-half">Test not added.</div>
                                              </td> 
                                            </tr>
                                            <?php
                                          } 
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="4"><?php if(!empty($form_error)){ echo form_error('test'); } ?></td>
                                        </tr>
                                      </table>
                                    </div> <!-- bk-tst-dtl -->


                                    <div class="boxbtns">
                                       &nbsp; &nbsp;  <a class="btn-new" onClick="test_list_vals();">
                                          <i class="fa fa-trash-o"></i> Delete
                                        </a>
                                    </div> <!-- boxbtns -->
                                  
                                  </div> <!-- box -->
                              </div>


                           </div>
                        </div> 
                    </div>
                        <!-- Lab -->

                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Appointment Date/Time</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" style="width: 55%; float: left;" class="form-control datepicker" name="appointment_date" value="<?php echo $form_data['appointment_date']; ?>" readonly="" >
                                    

                                    <input type="text" style="margin-left:5px; width: 41%; float: left;" class="form-control timepicker" name="appointment_time" value="<?php echo $form_data['appointment_time']; ?>"  readonly=""> 

                                </div>
                                <?php if(!empty($form_error)){ echo form_error('appointment_date'); } ?>
                                    <?php if(!empty($form_error)){ echo form_error('appointment_time'); } ?>
                            </div> 
                            <div class="form-group col-sm-4">
                                <label class="col-sm-4 fleft bold">Follow-up Date/Time</label>
                                <div class="col-sm-8 fleft">
                                    <input type="text" style="width: 55%; float: left;" class="form-control datepicker" name="followup_date" value="<?php echo $form_data['followup_date']; ?>"  readonly="">
                                    
                                    <input type="text" style="margin-left:5px; width: 41%; float: left;" class="form-control timepicker" name="followup_time" value="<?php echo $form_data['followup_time']; ?>"  readonly="">

                                </div>
                                <?php if(!empty($form_error)){ echo form_error('followup_date'); } ?>
                                    <?php if(!empty($form_error)){ echo form_error('followup_time'); } ?>
                            </div>
                             
                        </div>

                        <div class="row" style="text-align: center;"> 
                            <input type="submit" name="save" value="Submit" class="btn btn-success" id="save" />
                            <a class="btn btn-danger" href="<?php echo base_url('leads'); ?>">  Cancel </a>
                        </div>


                         </form>
                    </div>
                </div> 
            </div>
            <!-- END PAGE CONTENT-->
            <?php $this->load->view('include/footer'); ?>
        </div>
    </div>
     
    <!-- BEGIN PAGA BACKDROPS-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div>
<script type="text/javascript">
function set_lead_type(vals)
{
  var branch_id = '<?php echo $users_data['parent_id']; ?>';
  
  if(branch_id!='')
  {
     $.ajax({
            url: "<?php echo base_url(); ?>leads/specialization_list/"+branch_id, 
            success: function(result)
            {
              $('#specialization_id').html(result); 
            } 
          });
     
  }
}

function get_doctor_specilization(specilization_id,branch_id)
{   

    if(typeof branch_id === "undefined" || branch_id === null)
    {
        $.ajax({url: "<?php echo base_url(); ?>leads/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
    }
    else
    {

      $.ajax({url: "<?php echo base_url(); ?>leads/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
      success: function(result)
      {
        $('#billing__attended_doctor').html(result); 
      } 
    });
   } 
}


    </script>
    <!-- END PAGA BACKDROPS-->
    <!-- CORE PLUGINS--> 
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/popper.js/dist/umd/popper.min.js" type="text/javascript"></script>
    
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/metisMenu/dist/metisMenu.min.js" type="text/javascript"></script>
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/bootstrap-timepicker/js/bootstrap-timepicker.min.js" type="text/javascript"></script>
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- PAGE LEVEL PLUGINS-->
    <script src="<?php echo ROOT_ASSETS_PATH; ?>vendors/DataTables/datatables.min.js" type="text/javascript"></script>
    <!-- CORE SCRIPTS-->
    <script src="<?php echo ROOT_ASSETS_PATH; ?>js/app.min.js" type="text/javascript"></script>  
<div id="load_leads_modal_popup" class="modal fade" role="dialog" data-backdrop="dynamic" data-keyboard="true"></div>    
<script type="text/javascript">
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});   

$('.timepicker').timepicker({

});

function department_data(vals)
{
   if(vals==1)
   {
    $('.opd_booking_box').slideDown();
   }
   else
   {
    $('.opd_booking_box').slideUp();
   }

   if(vals==2 || vals==3)
   {
    $('.lab_booking_box').slideDown();
   }
   else
   {
    $('.lab_booking_box').slideUp();
   }

   if(vals==4)
   {
    $('.billing_booking_box').slideDown();
   }
   else
   {
    $('.billing_booking_box').slideUp();
   }

   if(vals==5)
   {
    $('.ipd_booking_box').slideDown();
   }
   else
   {
    $('.ipd_booking_box').slideUp();
   }

   if(vals==6)
   {
    $('.ot_booking_box').slideDown();
   }
   else
   {
    $('.ot_booking_box').slideUp();
   }
}

$('#dept_id').change(function(){
      var dept_id = $(this).val(); 
      $.ajax({url: "<?php echo base_url(); ?>leads/dept_test_heads/"+dept_id, 
        success: function(result)
        { 
           $('#dept_parent_test').html(result);   
        } 
      }); 
});

$('#dept_parent_test').change(function(){  
      var head_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>leads/test_list/"+head_id, 
        success: function(result)
        {
           $('#test_list').html(result); 
        } 
      }); 
  });

function toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

  function final_toggle(source) 
  {  
      checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }

$('#test_search').keyup(delay(function (e) {
  console.log('Time elapsed!', this.value);
}, 500)); 

function delay(callback, ms) {
  var timer = 0;
  return function() {
    var context = this, args = arguments;
    clearTimeout(timer);
    timer = setTimeout(function () {
      //callback.apply(context, args);
      test_search_list();
    }, ms || 0);
  };
}


  
 function test_search_list()
 {
      var search_text = $("#test_search").val();  
      var dept_id = $("#dept_id").val();  
      var test_head_id = $("#dept_parent_test").val();
      if(test_head_id=='' || test_head_id==null)
      {
        test_head_id = 0;
      }  
      if(dept_id=='')
      {
        dept_id = 0;
      } 
      if(search_text=='')
      {
        search_text = 0;
      }   
          
      var url ="<?php echo base_url(); ?>leads/test_list";
      $.post(url,
        { test_head_id: test_head_id, profile_id: 0, search_text: search_text, dept_id: dept_id},
        function (msg) {
          $("#test_list").html(msg);

        });  
}   


function add_profile()
  {  
     var profile_id = $('#profile_id').val(); 
     
     var profile_price_updated= $('#profile_price').val();
     
     $.ajax({
         type:'POST',
      url: "<?php echo base_url(); ?>leads/set_profile/"+profile_id, 
       data:{profile_price_updated:profile_price_updated},
      success: function(result)
      {
         if(result!=1)
         {
           $('#profile_error').html(result);
         } 
         else
         {
          $.ajax({url: "<?php echo base_url(); ?>leads/list_booked_test/",
                success: function(result)
                { 
                   $('#test_select').html(result);
                   //payment_calc();
                } 
              });
         }
      } 
    }); 
  }  

  function get_profile_test()
  { 
     var profile_id = $("#profile_id").val();  
     var panel_id ='';
     ///// Profile Price
      $.ajax({url: "<?php echo base_url(); ?>leads/profile_price/"+profile_id+'/'+panel_id,  
        success: function(result)
        {
           //$('#profile_price').html(result);  
            $('#profile_price').val(result); 
        } 
      }); 
  }

  function test_list_vals() 
  {          
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
       remove_test(allVals);
  } 

  function remove_test(allVals)
  {     
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('leads/remove_booked_test');?>",
              data: {test_id: allVals},
              success: function(result) 
              {
                $('#test_select').html(result); 
                var head_id = $('#dept_parent_test').val();
                $.ajax({url: "<?php echo base_url(); ?>leads/test_list/"+head_id, 
                  success: function(result)
                  {
                     $('#test_list').html(result); 
                     //payment_calc();
                     return false;
                  } 
                });  
              }
          });
   }
  }


  function child_test_vals() 
  {          
       var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       send_test(allVals);
  } 
 
  function send_test(event)
  {     
    event.preventDefault(); 
    $.ajax({
      url: "<?php echo base_url('leads/set_booking_test'); ?>",
      type: "post",
      data: $('#booking_form').serialize(),
      success: function(result) 
      {  
        $('#test_select').html(result); 
        var head_id = $('#dept_parent_test').val();
        $.ajax({url: "<?php echo base_url(); ?>leads/test_list/"+head_id, 
          success: function(result)
          { 
             $('#test_list').html(result); 
             //payment_calc();
          } 
        }); 
      }
  });       
  }
</script>
</body>

</html>