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
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ============================= -->


<section class="userlist">
  <form method="post" id="operation_summary_data" class="form-inline">  
    
     <div class="row">
   
   <div class="col-md-6">  <!--column 2-->
            
            <div class="row m-b-5">
              <div class="col-xs-4">
                <strong><?php echo $val= get_setting_value('PATIENT_REG_NO');?></strong>
              </div>
              <div class="col-xs-8">
                
                <input type="text" readonly="" name="patient_code" value="<?php if(!empty($data) && $data!="") { echo $data['patient_code']; } ?>" /> 
              </div>
            </div>
            
            <div class="row m-b-5">
              <div class="col-md-4"><b>OT Booking No.</b></div>
              <div class="col-md-8">
                
                <input type="text" readonly="" name="booking_code" value="<?php if(!empty($data) && $data!="") { echo $data['booking_code']; } ?>" /> 
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
                      if($simulation->id==$data['simulation_id'])
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
                      if($simulation->id==$data['simulation_id'])
                      {
                           $selected_simulation = 'selected="selected"';
                      }
                              
                      echo '<input type="hidden" name="simulation_id" value="'.$simulation->id.'">';
                    }
                  }
              ?>
              <input type="text" name="patient_name" readonly id="patient_name" value="<?php echo $data['patient_name']; ?>" class="mr-name txt_firstCap" autofocus/>
              
               
            </div>
        </div>
       
          
           
      
      <div class="row m-b-5">
        <div class="col-xs-4">
          <strong>Age</strong>
        </div>
        <div class="col-xs-8">
          <input type="text" name="age_y" readonly  class="input-tiny numeric" maxlength="3" value="<?php echo $data['age_y']; ?>"> Y &nbsp;
                <input type="text" name="age_m"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $data['age_m']; ?>"> M &nbsp;
                <input type="text" name="age_d"  readonly class="input-tiny numeric" maxlength="2" value="<?php echo $data['age_d']; ?>"> D
               
        </div>
      </div>
        

      <div class="row m-b-5">
        <div class="col-xs-4">
         <br>
        </div>
        <div class="col-xs-8"> <br>
            </div>
            </div>
            
             <div class="row m-b-5">
              <div class="col-xs-4"><strong>Select Summary Template</strong></div>
              <div class="col-xs-8">
                  <select name="template_list" id="template_list" >
                    <option value="">Select Summary Template</option>
                    <?php 
                      if(!empty($ot_summary_template) && $ot_summary_template!="")
                      {
                          foreach($ot_summary_template as $ot_template)
                          {
                            echo "<option value=".$ot_template->id." >".$ot_template->name."</option>";
                             
                          }
                      }
    
                    ?>
                  </select>
                 
              </div>
            </div>
            <?php if(getProcedureNoteTabSetting('diagnosis')['status']){ ?>
            <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('diagnosis')['var_value']?></strong></div>
              <div class="col-xs-8"> 
              <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Diagnosis" id="diagnosis" name="diagnosis"><?php if($summary_data!="empty"){ echo $summary_data['diagnosis']; } ?></textarea> 
              </div>
            </div>
            <?php }?>
            <?php if(getProcedureNoteTabSetting('op_findings')['status']){ ?>
             <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('op_findings')['var_value']?></strong></div>
              <div class="col-xs-8"> 
              <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="OP. Findings" id="op_findings" name="op_findings"><?php if($summary_data!="empty"){ echo $summary_data['op_findings']; } ?></textarea> 
              </div>
            </div>
            <?php }?>
    
    
            
            <?php if(getProcedureNoteTabSetting('procedures')['status']){ ?>
            <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('procedures')['var_value']?></strong></div>
              <div class="col-xs-8"> 
              <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Procedures" id="procedures" name="procedures"><?php if($summary_data!="empty"){ echo $summary_data['procedures']; } ?></textarea> 
              </div>
            </div>
            <?php }?>
            <?php if(getProcedureNoteTabSetting('post_op_order')['status']){ ?>
    
             <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('post_op_order')['var_value']?></strong></div>
              <div class="col-xs-8"> 
              <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Post OP. Order" id="pos_op_orders" name="pos_op_orders"><?php if($summary_data!="empty"){ echo $summary_data['pos_op_orders']; } ?></textarea> 
              </div>
            </div>
            <?php }?>
            <?php if(getProcedureNoteTabSetting('blood_transfusion')['status']){ ?>
            <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('blood_transfusion')['var_value']?></strong></div>
              <div class="col-xs-8"> 
                <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Blood Transfusion" id="blood_transfusion" name="blood_transfusion" ><?php if($summary_data!="empty"){ echo $summary_data['blood_transfusion']; } ?></textarea>
              </div>
            </div>
            <?php }?>
            <?php if(getProcedureNoteTabSetting('blood_loss')['status']){ ?>
            
            <div class="row m-b-5" id="content">
              <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('blood_loss')['var_value']?></strong></div>
              <div class="col-xs-8"> 
                 <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Blood Loss" id="blood_loss" name="blood_loss" ><?php if($summary_data!="empty"){ echo $summary_data['blood_loss']; } ?></textarea>
                  </div>
          </div>
          <?php }?>
          <?php if(getProcedureNoteTabSetting('drain')['status']){ ?>
          <div class="row m-b-5" id="content">
                  <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('drain')['var_value']?></strong></div>
                  <div class="col-xs-8"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor"  placeholder="Drain" id="drain" name="drain"><?php if($summary_data!="empty"){ echo $summary_data['drain']; } ?></textarea>
                  </div>
          </div>
        
          <?php }?>
          <?php if(getProcedureNoteTabSetting('materials_submitted_for_histopathological_exam')['status']){ ?>
           <div class="row m-b-5" id="content">
                  <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('materials_submitted_for_histopathological_exam')['var_value']?></strong></div>
                  <div class="col-xs-8"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Materials Submitted for Histopathological Exam" id="histopathological" name="histopathological"><?php if($summary_data!="empty"){ echo $summary_data['histopathological']; } ?></textarea>
                  </div>
          </div>
        
          <?php }?>
          <?php if(getProcedureNoteTabSetting('materials_submitted_for_microbiological_exam')['status']){ ?>
            <div class="row m-b-5" id="content">
                  <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('materials_submitted_for_microbiological_exam')['var_value']?></strong></div>
                  <div class="col-xs-8"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Materials Submitted for Microbiological Exam" id="microbiological" name="microbiological"><?php if($summary_data!="empty"){ echo $summary_data['microbiological']; } ?></textarea>
                  </div>
          </div>
          <?php }?>
          <?php if(getProcedureNoteTabSetting('remarks')['status']){ ?>
        <div class="row m-b-5" id="content">
                  <div class="col-xs-4"><strong><?=getProcedureNoteTabSetting('remarks')['var_value']?></strong></div>
                  <div class="col-xs-8"> 
                    <textarea style="height: 72px;width:280px !important;" class="ckeditor" placeholder="Remarks" id="remark" name="remark"><?php if($summary_data!="empty"){ echo $summary_data['remark']; } ?></textarea> 
                    </div>
          </div>
          <?php }?>
       
            
           
   </div> <!--column 1-->
   <div class="col-md-6"> <!--column 2-->
   
    
    <div class="row m-b-5">
          <div class="col-xs-4"><strong>Operation Booking Date</strong> </div>
          <div class="col-xs-8 ">
            <?php echo date('d-m-Y',strtotime($data['operation_booking_date'])); ?>
          </div>
      </div>
			
      <div class="row m-b-5">
           <div class="col-xs-4"><strong>Operation Date & Time</strong></div>
          <div class="col-xs-8">
           
           <?php 
           $time='';
           if(date('h:i:s',strtotime($data['operation_time']))!='12:00:00')
            {
              $time = date('h:i A',strtotime($data['operation_date']. $data['operation_time']));
            }
           echo date('d-m-Y',strtotime($data['operation_date'])).$time; ?>
          </div>
      </div>

      <div class="row m-b-5">
              <div class="col-xs-4"><strong>Mobile No.</strong></div>
              <div class="col-xs-8">
                   <input type="text" readonly name="mobile_no" value="<?php if(!empty($data) && $data!='') { echo $data['mobile_no']; } ?>" /> 
                
              </div>
            </div>
            
     
        <div class="row m-b-5">
        <div class="col-xs-4">
          <strong>Gender </strong>
        </div>
        <div class="col-xs-8" id="gender">
          <input type="radio" name="gender"  disabled="true" value="1" <?php if($data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
              <input type="radio" name="gender"  disabled="true" value="0" <?php if($data['gender']==0){ echo 'checked="checked"'; } ?>> Female
               <input type="radio" name="gender"  disabled="true" value="2" <?php if($data['gender']==2){ echo 'checked="checked"'; } ?>> Others
              <?php if(!empty($form_error)){ echo form_error('gender'); } ?>
        </div>
    
      </div> <!-- row -->

      <div class="row m-b-5">
         <div class="col-xs-4">
            <strong>Address</strong>
          </div>
          <div class="col-xs-8 ">
           <?php  echo $data['address']; ?>
          </div>
      </div>

      <div class="row m-b-5">
          <div class="col-xs-3">
            <strong>Procedure Data</strong>
          </div>
          <div class="col-xs-4 ">
            <select name="procedure_data" id="procedure_data">
                    <option value="">Select Procedure Data</option>
                    <?php
                    if(!empty($procedure_data))
                    {
                      foreach($procedure_data as $pd)
                      {
                        $selected_pd = "";
                        if($pd->id==$summary_data['procedure_data'])
                        {
                          $selected_pd = 'selected="selected"';
                        }
                        echo '<option value="'.$pd->id.'" '.$selected_pd.'>'.$pd->name.'</option>';
                      }
                    }
                    ?> 
              </select>
          
          </div>
          <div class="col-xs-3 ">
              <div class="grp-right">
                    <a title="Add Religion" class="btn-new" href="javascript:void(0)" onClick="procedure_data_modal()"><i class="fa fa-plus"></i> New</a>
               </div>
          </div>
      </div>
      
      <div class="row m-b-5">
        <div class="col-xs-4">
         <br>
        </div>
        <div class="col-xs-8"> <br>
            </div>
            </div>
            
            <div class="row m-b-5">
        <div class="col-xs-4">
         <br>
        </div>
        <div class="col-xs-8"> <br>
            </div>
            </div>
            
            
   
       
              <div class="row m-b-5">
              <div class="col-xs-4"><strong>Operation/Package</strong></div>
              <div class="col-xs-8"> 
               <?php if(!empty($data) && $data!="") { if($data['op_type']==1){ echo "OT (".$data['ot_pack_name'].")"; } else if($data['op_type']==2) { echo "OT (".$data['ot_pack_name'].")"; }   } ?>
              </div>
            </div>
            
            
      <?php 

        if(!empty($summary_data['operation_start_time']) && $summary_data['operation_start_time']!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($summary_data['operation_start_time']))!='01-01-1970')
        {
            $operation_start_time = date('d-m-Y H:i A',strtotime($summary_data['operation_start_time']));
        }
        else
        {
            $operation_start_time = ''; 
        }
        ?>
    <div class="row m-b-5">
          <div class="col-xs-4"><strong>Operation Start Date & Time</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Operation Start date & time" class="datepicker" id="operation_start_time" name="operation_start_time" value="<?php if($operation_start_time!=""){ echo $operation_start_time; } ?>">
          </div>
    </div>
    
     <?php 

        if(!empty($summary_data['operation_finish_time']) && $summary_data['operation_finish_time']!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($summary_data['operation_finish_time']))!='01-01-1970')
        {
            $operation_finish_time = date('d-m-Y H:i A',strtotime($summary_data['operation_finish_time']));
        }
        else
        {
            $operation_finish_time = ''; 
        }
        ?>
    <div class="row m-b-5">
          <div class="col-xs-4"><strong>Operation Finished Date & Time</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Operation Finished Date & Time" class="datepicker" id="operation_finish_time" name="operation_finish_time" value="<?php if($operation_finish_time!=""){ echo $operation_finish_time; } ?>">
          </div>
    </div>
           
            
            <div class="row m-b-5">
              <div class="col-xs-4"><strong>Surgeon Name</strong></div>
              <div class="col-xs-8"> 
              <input type="text"  placeholder="Surgeon Name" class="doctor_name_surgen" id="surgeon_name" name="surgeon_name" value="<?php if($summary_data!="empty"){ echo $summary_data['surgeon_name']; } ?>">
              </div>
            </div>
            
            <div class="row m-b-5">
          <div class="col-xs-4"><strong>OT Procedure</strong></div>
          <div class="col-xs-8">
              <select name="ot_procedure" id="ot_procedure" >
                <option value="">Select OT procedure</option>
                <?php 
                  if(!empty($ot_procedures) && $ot_procedures!="")
                  {
                      foreach($ot_procedures as $ot_procedure)
                      {
                        if($summary_data!='empty')
                        { 
                          if($summary_data['ot_procedure_id']==$ot_procedure->id )
                          { 
                            echo "<option selected=selected value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                          }
                          else
                          {
                            echo "<option value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                          }
                        }
                        else
                        {
                          echo "<option value=".$ot_procedure->id." >".$ot_procedure->ot_procedure."</option>";
                        }  
                      }
                  }

                ?>
              </select>
              <span id="ot_procedure_validate"></span>
          </div>
        </div>

         <div class="row m-b-5">
          <div class="col-xs-4"><strong>OT Post Observations</strong></div>
          <div class="col-xs-8">
              <select name="post_observations" id="post_observations" >
                <option value="">Select Post Observations</option>
                <?php 
                  if(!empty($post_observations) && $post_observations!="empty")
                  {
                      foreach($post_observations as $post_observations)
                      {
                            if($summary_data!='empty')
                            { 
                              if($summary_data['post_observation_id']==$post_observations->id )
                              { 
                                  echo "<option  selected=selected value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                              }
                              else
                              {
                                echo "<option value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                              }
                            }
                            else
                            {
                              echo "<option value=".$post_observations->id." >".$post_observations->post_observations."</option>";
                            }
                      } 
                  }
                ?>
              </select>
              <span id="post_observation_validate"></span>
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-4"><strong>Post Operative Period</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Post Operative Period" class="" id="post_operative_period" name="post_operative_period" value="<?php if($post_operative_period!=""){ echo $post_operative_period; } ?>">
          </div>
    </div>
        <div class="row m-b-5">
          <div class="col-xs-4"><strong> Indication of Surgery</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Indication of Surgery" class="" id="indication_of_surgery" name="indication_of_surgery" value="<?php if($indication_of_surgery!=""){ echo $indication_of_surgery; } ?>">
          </div>
    </div>
    
    <div class="row m-b-5">
          <div class="col-xs-4"><strong>Type of Anaesthesia</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Type of Anaesthesia" class="" id="type_of_anaesthesia" name="type_of_anaesthesia" value="<?php if($type_of_anaesthesia!=""){ echo $type_of_anaesthesia; } ?>">
          </div>
    </div>
    
        <div class="row m-b-5">
          <div class="col-xs-4"><strong> Name of Anaesthetist</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Name of Anaesthetist" class="anaesthetist_name_ot" id="anaesthetist_name_ot" name="name_of_anaesthetist" value="<?php if($name_of_anaesthetist!=""){ echo $name_of_anaesthetist; } ?>">
          </div>
    </div>
   
    
        
        <?php 

        if(!empty($summary_data['recovery_time']) && $summary_data['recovery_time']!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($summary_data['recovery_time']))!='01-01-1970')
        {
            $recovery_time = date('d-m-Y H:i A',strtotime($summary_data['recovery_time']));
        }
        else
        {
            $recovery_time = ''; 
        }
        ?>
    <div class="row m-b-5">
          <div class="col-xs-4"><strong>Recovery Time</strong></div>
          <div class="col-xs-8"> 
            <input type="text" placeholder="Recovery Time" class="datepicker" id="recovery_time" name="recovery_time" value="<?php if($recovery_time!=""){ echo $recovery_time; } ?>">
          </div>
    </div>
    
    <div class="row m-b-5 m-b-5">
          <div class="col-xs-4">
               <label>Assistant Surgeon Name</label>
           </div>
          <div class="col-xs-8"> 
               <input type="text" placeholder="Type and Add" class=" m-b-5 doctor_name_ot inputFocus" name="doctor_name" id="doctor_name_ot" >
               <input type="hidden" class=" m-b-5 doctor_id_ot inputFocus" name="doctor_id" id="doctor_id_ot" > &nbsp; <a class="btn-new" onclick="add_doctor_list();">Add</a>
              
            </div>
          </div>

        
          <div class="row m-t-5 m-b-5">
            <div class="col-xs-12">
               <div class="row">
                  <div class="col-sm-5"></div>
                 <!--  <div class="col-sm-7 ot_booking_delete">
                     <a class="btn-new">Delete</a>
                  </div> -->
               </div>
               <div class="">
               <from id="deleteform">
                  <table class="table table-bordered table-striped ot_table" id="doctor_list">
                     <thead class="bg-theme">
                        <tr>
                           <th><input type="checkbox" name="" onClick="toggle(this);add_check();"></th>
                           <th>S.No.</th>
                           <th>Doctor Name</th>
                        </tr>
                     </thead>
                     <tbody id="append_doctor_list">
                       <?php $i=1;if(!empty($doctor_list)){
                        
                        foreach($doctor_list as $key=>$value){
                        ?>

                       <tr><td><input type="checkbox" name="doctor_names[<?php echo $key?>][]" checked value="<?php echo $value[0]; ?>" class="child_checkbox"/><td><?php echo $i; ?></td><td><?php echo $value[0];?></td></tr>

                       <?php $i++;} }?>
                       <a class="btn-custom m-b-5 m1" id="deleteAll" onclick="remove_row();">
             <i class="fa fa-trash"></i> Delete
          </a>
                     </tbody>
                  </table>
                 
               </div>         
            </div>
          </div>
         <!-- prescribed Medicine -->
         <div class="row m-b-5">
             <div class="col-xs-12">
                
                  <table class="table table-bordered table-striped" id="prescription_name_table">
                    <thead>
                        <tr><th colspan="9">Medication Prescribed  </th></tr>
                    </thead>
                        
                     <tbody> 
                     <tr>
                        <?php if($data['booking_type']==1){ ?>
                        <td>Eye Drop</td>
                        <?php } ?>
                        <td>Medicine</td>
                        <td>Dose</td>
                        <td>Duration (Days)</td>
                        <td>Frequency</td>
                        <td>Advice</td>
                        
                        <?php if($data['booking_type']==1){ ?>
                        <td>R</td>
                        <td>L</td>
                        <?php } ?>
                        <td width="80">
                          <a class="btn-w-60" href="javascript:void(0)" onclick="add_rows();">Add</a>
                        </td>
                      </tr>
            <?php if($rec_id==0) {   ?>      
                      <tr id="rec_id_0" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                        <input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_0" name="medicine[0][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      <?php } ?>
                        <td><input style="width:100px;" type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input style="width:100px;" id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        
                        <?php if($data['booking_type']==1){ ?>
                        <td >
                        <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >   
                        <input type="checkbox" class="right_eye_appned_0" style="display:none;"  id="right_eye_val_0" value="1" name="medicine[0][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" >  
                        <input type="checkbox" class="left_eye_appned_0" style="display:none;"  id="left_eye_val_0" value="1" name="medicine[0][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >  
                           <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" > 
                        <?php } ?>
                        <td width="80">
                          
                        </td>
                      </tr>
            <?php } else if($rec_id > 0)  {  if($medicine_data!="empty") { 
              $x=0;
              foreach($medicine_data as $medicines)
              {
                $checked=0;
              ?>                

                    <tr id="rec_id_<?php echo $x; ?>" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[<?php echo $x; ?>][is_eyedrop]" value="0" id="is_eye_drop_<?php echo $x; ?>">  
                        <input type="checkbox" <?php if($medicines->is_eye_drop==1){ $checked=1; echo "checked=checked"; } ?> onclick="check_eye_drop(this);" value="1" id="is_eye_drop_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[<?php echo $x; ?>][is_eyedrop]" value="0" id="is_eye_drop_<?php echo $x; ?>">  
                      <?php } ?>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_name ;?>" id="medicine_name_<?php echo $x; ?>" class="w-100px medicine_val" name="medicine[<?php echo $x; ?>][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input style="width:100px;" id="medicine_dosage_<?php echo $x; ?>" type="text" value="<?php echo $medicines->medicine_dose ;?>" class="input-small w-100px" name="medicine[<?php echo $x; ?>][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_duration ;?>" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_frequency ;?>" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_advice ;?>" class="medicine-name advice_val1 w-100px" name="medicine[<?php echo $x; ?>][medicine_advice]" id="medicine_advice_<?php echo $x; ?>" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        
                        <?php if($data['booking_type']==1){ ?>
                        <td>
                        <input type="hidden" name="medicine[<?php echo $x; ?>][right_eye]" id="right_eye_val_<?php echo $x; ?>" value="0" >  
                        <?php if($checked==1){ $style=""; } else { $style="style='display:none'"; } ?>
                        <input type="checkbox" class="right_eye_appned_<?php echo $x; ?>" <?php echo $style; ?>  id="right_eye_val_<?php echo $x; ?>" <?php if($medicines->right_eye==1){ echo "checked=checked"; } ?>  value="1" name="medicine[<?php echo $x; ?>][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[<?php echo $x; ?>][left_eye]" id="left_eye_val_<?php echo $x; ?>" value="0" >  
                        <input type="checkbox" class="left_eye_appned_<?php echo $x; ?>" <?php echo $style; ?>  id="left_eye_val_<?php echo $x; ?>" <?php if($medicines->left_eye==1){ echo "checked=checked"; } ?> value="1" name="medicine[<?php echo $x; ?>][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[<?php echo $x; ?>][right_eye]" id="right_eye_val_<?php echo $x; ?>" value="0" >  
                           <input type="hidden" name="medicine[<?php echo $x; ?>][left_eye]" id="left_eye_val_<?php echo $x; ?>" value="0" > 
                        <?php } ?>
                        <?php if($x==0){ ?>
                        <td width="80">
                          
                        </td>
                        <?php } else if($x>0) { ?>
                        <td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine(<?php echo $x; ?>);" href="javascript:void(0)">Delete</a></td>
                        <?php } ?>
                      </tr>

            <?php $x++; } } else {  ?>
                   <tr id="rec_id_0" >
                      <?php if($data['booking_type']==1){ ?>
                        <td> <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                        <input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_0" name="medicine[0][is_eyedrop]">
                        </td>
                      <?php } else { ?>
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      <?php } ?>
                        <td><input type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        
                        <?php if($data['booking_type']==1){ ?>
                        <td >
                        <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >   
                        <input type="checkbox" class="right_eye_appned_0" style="display:none;"  id="right_eye_val_0" value="1" name="medicine[0][right_eye]">
                        </td>
                        <td >
                         <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" >  
                        <input type="checkbox" class="left_eye_appned_0" style="display:none;"  id="left_eye_val_0" value="1" name="medicine[0][left_eye]">
                        </td>
                        <?php } else {  ?>
                           <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >  
                           <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" > 
                        <?php } ?>
                        <td width="80">
                          
                        </td>
                      </tr>

            <?php } } ?>
                    </tbody>
                  </table>
            
             </div>
         </div>
         
         <!-- end of prescribed -->
         
                
                
            
            
            
    </div> <!--column 2-->
    </div>
     <div class="modal-footer r-btn-cntr"> 
            
            <input type="hidden" name="patient_id" value="<?php echo $data['patient_id']; ?>">
      <input type="hidden" name="ot_booking_id" value="<?php echo $data['id']; ?>">
      <input type="hidden" name="ot_summary_id" value="<?php echo $rec_id; ?>">
      
          <input type="button" class="btn-update" name="form_sub" value="Submit" onclick="submit_ot_summary();">
        
         <button type="button" onclick="window.location.href='<?php echo base_url('ot_booking'); ?>'" class="btn-cancel" data-number="1">Exit</button>
      </div>
   
      </form>
   </section> 
   <div id="load_add_religion_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<script type="text/javascript">
  function add_doctor_list(){
    var rowCount = $('#doctor_list tr').length;
    var doc= $('#doctor_name_ot').val();
    var doctor_id= $('#doctor_id_ot').val();

     $.ajax({
            url : "<?php echo base_url('operation_summary/append_doctor_list/'); ?>",
            method: 'post',
            data: {name : doc ,rowCount:rowCount,doctor_id:doctor_id},
            success: function( data ) {
            
             $('#append_doctor_list').append(data);
          }
          });

    }

    function remove_row()
    {
      jQuery('input:checkbox:checked').parents("tr").remove();
    }

    $(function () {

    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
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

          $('.doctor_name_ot').val(names[0]);
          $('.doctor_id_ot').val(names[1]);
          

        return false;
    }


    $(".doctor_name_ot").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});

    $(function () {

    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
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

          $('.doctor_name_surgen').val(names[0]);
         
          

        return false;
    }


    $(".doctor_name_surgen").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});


 $(function () {

    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
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

          $('.anaesthetist_name_ot').val(names[0]);
          $('.anaesthetist_id_ot').val(names[1]);
          

        return false;
    }


    $(".anaesthetist_name_ot").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});
</script>
<script type="text/javascript">
 
 $('#template_list').change(function(){  
      var template_id = $(this).val();
      $.ajax({url: "<?php echo base_url(); ?>operation_summary/get_procedure_template/"+template_id, 
        success: function(result)
        {
           load_values(result);
           load_prescription_values(template_id);
        } 
      }); 
  });

  function load_values(jdata)
  {
       var obj = JSON.parse(jdata);
       CKEDITOR.instances['diagnosis'].setData(obj.diagnosis);
       CKEDITOR.instances['op_findings'].setData(obj.op_findings);
       CKEDITOR.instances['procedures'].setData(obj.procedures);
       CKEDITOR.instances['pos_op_orders'].setData(obj.pos_op_orders);
       CKEDITOR.instances['blood_transfusion'].setData(obj.blood_transfusion);
       CKEDITOR.instances['blood_loss'].setData(obj.blood_loss);
       CKEDITOR.instances['drain'].setData(obj.drain);
       CKEDITOR.instances['histopathological'].setData(obj.histopathological);
       CKEDITOR.instances['microbiological'].setData(obj.microbiological);
       CKEDITOR.instances['remark'].setData(obj.remark);
       
      
       
  };

function load_prescription_values(template_id)
{
    $.ajax({url: "<?php echo base_url(); ?>operation_summary/get_template_medicine/"+template_id, 
    success: function(result)
    {
       get_prescription_values(result);
    } 
  });
}

    function get_prescription_values(result)
    {
        
        <?php if($medicine_data=="empty") { ?>
        var row_count=1;
        <?php } else {  ?>
        var row_count="<?php echo count($medicine_data); ?>";
        <?php } ?> 

      var obj = JSON.parse(result);
      var pres = '';
      i = 1;
      $.each(obj, function (index, value) { 
          
          
        
         pres += '<tr id="rec_id_'+row_count+'"><td></td><td><input class="w-100px medicine_val" type="text" name="medicine['+row_count+'][medicine_name]" class="" value="'+obj[index].medicine_name+'" placeholder="Click to add Medicine"></td><td><input class="w-100px" type="text" name="medicine['+row_count+'][medicine_dose]" onkeyup="get_auto_complete_medicine_dosage(this);" class="input-small" value="'+obj[index].medicine_dose+'"></td><td><input  class="w-100px" type="text" name="medicine['+row_count+'][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" class="medicine-name" value="'+obj[index].medicine_duration+'"></td><td><input type="text" class="w-100px" name="medicine['+row_count+'][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" class="medicine-name" value="'+obj[index].medicine_frequency+'"></td><td><input type="text" class="w-100px" name="medicine['+row_count+'][medicine_advice]" id="medicine_advice_'+row_count+'" onkeyup="get_auto_complete_medicine_advice(this);" class="medicine-name" value="'+obj[index].medicine_advice+'"></td><td></td><td></td><td><a href="javascript:void(0)" class="btn-w-60" onclick="delete_prescription_medicine('+row_count+');">Delete</a></td></tr>';

            i++;
          row_count++;
      }); 
      
      $("#prescription_name_table tbody").append(pres);

    }  

function check_eye_drop(ref)
{
  chk_val=$(ref).is(":checked"); 
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[3];
  
  if(chk_val==0)
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','none');
    $(".right_eye_appned_"+cnt).css('display','none');

  }  
  else
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','');
    $(".right_eye_appned_"+cnt).css('display','');
    
  }
}

// function to add more rows
<?php if($medicine_data=="empty") { ?>
var row_count=1;
<?php } else {  ?>
var row_count="<?php echo count($medicine_data); ?>";
<?php } ?>  

function add_rows()
{
  var string='<tr id="rec_id_'+row_count+'">';

  if(<?php echo $data['booking_type']; ?>==1)
  {
    string+='<td><input type="hidden" value="0" name="medicine['+row_count+'][is_eyedrop]" id="is_eye_drop_'+row_count+'" ><input type="checkbox" onclick="check_eye_drop(this);" value="1" id="is_eye_drop_'+row_count+'" name="medicine['+row_count+'][is_eyedrop]"></td>';
  }
  else
  {
    string+='<input type="hidden" name="medicine['+row_count+'][is_eyedrop]" id="is_eye_drop_'+row_count+'" value=0 >';
  }

  string+='<td><input type="text" value="" id="medicine_name_'+row_count+'" class="w-100px medicine_val" name="medicine['+row_count+'][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>'+
              
              '<td><input type="text" value="" class="input-small w-100px dosage_val" name="medicine['+row_count+'][medicine_dosage]" id="medicine_dosage_'+row_count+'" onkeyup="get_auto_complete_medicine_dosage(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_'+row_count+'"  name="medicine['+row_count+'][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_'+row_count+'" name="medicine['+row_count+'][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine['+row_count+'][medicine_advice]" id="medicine_advice_'+row_count+'" onkeyup="get_auto_complete_medicine_advice(this);" ></td>';
              
if(<?php echo $data['booking_type']; ?>==1)
{
  string+='<td id="right_eye_appned_'+row_count+'"><input type="hidden" name="medicine['+row_count+'][right_eye]" id="right_eye_val_'+row_count+'" value="0" ><input type="checkbox" style="display:none;" class="right_eye_appned_'+row_count+'" id="right_eye_val_'+row_count+'" value="1" name="medicine['+row_count+'][right_eye]"></td>'+
    '<td ><input type="hidden" name="medicine['+row_count+'][left_eye]" id="left_eye_val_'+row_count+'" value="0"><input type="checkbox" style="display:none;" class="left_eye_appned_'+row_count+'"   id="left_eye_val_'+row_count+'" value="1" name="medicine['+row_count+'][left_eye]"></td>';
}
else
{
  string+='<input type="hidden" value="0" id="right_eye_val_'+row_count+'" name="medicine['+row_count+'][right_eye]">'+
          '<input type="hidden" id="left_eye_val_'+row_count+'" value="0" name="medicine['+row_count+'][left_eye]">';

}

string+='<td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine('+row_count+');" href="javascript:void(0)">Delete</a></td>';

string+='</tr>';

if(row_count==1)
{
  $(string).insertAfter("#rec_id_0");
}
else
{
  v=row_count-1;
  $(string).insertAfter("#rec_id_"+v);
}

row_count++;

}
// Function to add new rows



// function to autocomplete medicine
function get_auto_complete_medicine(ref)
{
    value =$(ref).val();
    ref_id=$(ref).attr('id');
    extract_count=ref_id.split("_");
    var cnt=extract_count[2];
    $(function () 
    {
      var getData = function (request, response) 
      { 
        $.ajax({
                  url : "<?php echo base_url('eye/prescription_template/get_eye_medicine_auto_vals/'); ?>" + value,
                  dataType: "json",
                  method: 'post',
                  data: {
                          name_startsWith: value,
                          type: 'country_table',
                          //row_num : row
                        },
                  success: function( data ) 
                  {
                    response( $.map( data, function( item ) 
                    {
                      var code = item.split("|");
                      return {
                        label: code[0],
                        value: code[0],
                        data : item,
                          }
                    }));
                  }
              });
      };
      var selectItem = function (event, ui) 
      {
        var names = ui.item.data.split("|");
        $('#'+ref_id).val(names[0]);
        $('#medicine_unit_'+cnt).val(names[1]);
        $('#medicine_company_'+cnt).val(names[3]);
        $('#medicine_salt_'+cnt).val(names[2]);
        return false;
      }
      $("#"+ref_id).autocomplete(
      {
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() 
        {
          //$("#test_val").val("").css("display", 2);
        }
      });
  });
}
// function to autocomplete medicine

// function to autocomplete dosage
function get_auto_complete_medicine_dosage(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_dosage_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_dosage_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete dosage


// function to autocomplete duration
function get_auto_complete_medicine_duration(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_duration_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_duration_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete duration

// function to autocomplete frequency

function get_auto_complete_medicine_frequency(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_frequency_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_frequency_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete frequency


function get_auto_complete_medicine_advice(ref)
{
    value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_advice_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_advice_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });
}

function show_date_time_picker(ref)
{
  var id=$(ref).attr('id');
  $('#'+id).datepicker({
    dateFormat: 'dd-mm-yy',
    startDate : new Date(),
    autoclose: true, 
  });
}


function submit_ot_summary()
{
  $.ajax({
            type: "POST",
            dataType:'json',
            url: "<?php echo base_url('operation_summary/save_procedure_data');?>",
            data: function() {
                // Update CKEditor instances before serializing the form
                for (var instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                return $("#operation_summary_data").serialize();
            }(),
            success: function(result) 
            {
              if(result.st==0)
              {
                $("#ot_procedure_validate").html(result.ot_procedure);
                $("#post_observation_validate").html(result.post_observations);
              }
              else if(result.st==1)
              {

                 flash_session_msg(result.message);
                 window.location.href= "<?php echo base_url('ot_booking'); ?>";
               // location.reload(true);
              }
            }
        });
}


function delete_prescription_medicine(row_id)
{
  $("#rec_id_"+row_id).remove();
}


$(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });
</script>
<script type="text/javascript">
  $('#content, #content1').on('change keyup keydown paste cut', 'textarea', function () {
        $(this).height(70).height(this.scrollHeight);
    }).find('textarea').change();
</script>
<?php
$this->load->view('include/footer');
?>
</body>
</html>
<script src="<?=base_url('assets/ckeditor/ckeditor.js')?>"></script>
      <script>
        var basicToolbar = [
            { name: 'basicstyles', items: ['Bold', 'Italic'] },
            { name: 'editing', items: ['Scayt'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'insert', items: ['Table'] },
            // { name: 'tools', items: ['Maximize'] }
        ];
        var elements = document.querySelectorAll('.ckeditor');
        elements.forEach(function(element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        });

        // var element = document.querySelector('cause_of_death');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }

        // var element = document.querySelector('field_name[]');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }
        
      </script>

      <script>
        function procedure_data_modal()
        {
            var $modal = $('#load_add_religion_modal_popup');
            $modal.load('<?php echo base_url().'procedure_data/add/' ?>',
            {
              //'id1': '1',
              //'id2': '2'
              },
            function(){
            $modal.modal('show');
            });
        } 
        $(document).ready(function(){
  $('#load_add_religion_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});
      </script>
      <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
      <script>
        $("#template_list").select2();
      </script>