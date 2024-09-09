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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>  
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  $users_data = $this->session->userdata('auth_users');
 // $users_data['permission']['section'];
   if(!empty($section_list))
          {
            $permited_section1=array();
             $permited_section=array();
             foreach($section_list as $section)
             {
              if(in_array($section->id, $users_data['permission']['section']) && $section->status=='1')
              {
               $permited_section[]=$section->action_class;
              }
             }
           }
           $permited_section= array_unique($permited_section);


if (array_key_exists("permission",$users_data))
{
     $permission_section_ids = $users_data['permission']['section'];
     $permission_action_ids = $users_data['permission']['action'];
}
else
{
     $permission_section_ids = array();
     $permission_action_ids = array();
}
 ?>
<!-- ============================= Main content start here ===================================== -->
<form id="permission_form">
<input type="hidden" value="<?php echo $users_id; ?>" name="uid" />
<section class="userlist">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
    <div class="userlist-box">  
      <div id="permission_section">
           <div class="permission">
           <div>
            <ul class="permission_menu">
            <?php  if(in_array('common', $permited_section)){?>
              <li class="per_menu_tab active _common">
                <strong>Common  </strong>
                <div>
                  <label><input type="radio"  class="tab_active"  name="common_radio" onClick="common_section_check(1);" id="common_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="common_radio" onClick="common_section_check(0);" id="common_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } if(in_array('opd', $permited_section)){?>
              <li class="per_menu_tab _opd">
                <strong>OPD </strong>
                <div>
                  <label><input type="radio"  class="tab_active" onClick="opd_section_check(1);" name="opd_radio" id="opd_active" value="1" > Active</label>
                  <label><input type="radio"  class="tab_inactive"  name="opd_radio" onClick="opd_section_check('0');" id="opd_inactive" value="0"> Inactive</label>
                </div>
              </li>
               <?php } 
               
                if(in_array('387', $permission_section_ids)){?>
              <li class="per_menu_tab _day_care">
                <strong>Day Care </strong>
                <div>
                  <label><input type="radio"  class="tab_active" onClick="day_care_section_check(1);" name="day_care_radio" id="day_care_active" value="1" > Active</label>
                  <label><input type="radio"  class="tab_inactive"  name="day_care_radio" onClick="day_care_section_check('0');" id="day_care_inactive" value="0"> Inactive</label>
                </div>
              </li>
               <?php }
               if(in_array('235', $permission_section_ids)){?>
              <li class="per_menu_tab _eye">
                <strong>Eye  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="eye_radio" onClick="eye_section_check(1);" id="eye_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="eye_radio" onClick="eye_section_check(0);" id="eye_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } /* if(in_array('opd eye', $permited_section)){?>
              <li class="per_menu_tab _opd_eye">
                <strong>Eye Prescriptions</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="opd_eye_radio" onClick="opd_eye_section_check(1);" id="opd_eye_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="opd_eye_radio" onClick="opd_eye_section_check(0);" id="opd_eye_inactive" value="0"> Inactive</label>
                </div>
              </li>
           <?php } if(in_array('advance_eye', $permited_section)){?>
              <li class="per_menu_tab _advance_eye">
                <strong>Advance Eye  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="advance_eye_radio" onClick="advance_eye_section_check(1);" id="advance_eye_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="advance_eye_radio" onClick="advance_eye_section_check(0);" id="advance_eye_inactive" value="0"> Inactive</label>
                </div>
              </li>
        <?php } */ if(in_array('pediatrician', $permited_section)){?>
              <li class="per_menu_tab _pediatrician">
                <strong>Pediatrician</strong>
                <div>
                  <label><input type="radio"  class="tab_active"  name="pediatrician_radio" onClick="pediatrician_section_check(1);" id="pediatrician_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive"  name="pediatrician_radio" onClick="pediatrician_section_check(0);" id="pediatrician_inactive" value="0"> Inactive</label>
                </div>
              </li>
          <?php } if(in_array('282', $permission_section_ids)){?>
              <li class="per_menu_tab _dental">
                <strong>Dental</strong>
                <div>
                  <label><input type="radio"  class="tab_active"  name="dental_radio" onClick="dental_section_check(1);" id="dental_active" value="1" > Active</label>
                  <label><input type="radio"  class="tab_inactive"  name="dental_radio" onClick="dental_section_check(0);" id="dental_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } /* if(in_array('opd dental', $permited_section)){?>
              <li class="per_menu_tab _opd_dental">
                <strong>Dental Prescription</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="opd_dental_radio" onClick="opd_dental_section_check(1);" id="opd_dental_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="opd_dental_radio" onClick="opd_dental_section_check(0);" id="opd_dental_inactive" value="0"> Inactive</label>
                </div>
              </li>
              <?php } */ if(in_array('299', $permission_section_ids)){?>
              <li class="per_menu_tab _gynecology">
                <strong>Gynecology</strong>
                <div>
                  <label><input type="radio"  class="tab_active"  name="gynecology_radio" onClick="gynecology_section_check(1);" id="gynecology_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive"  name="gynecology_radio" onClick="gynecology_section_check(0);" id="gynecology_inactive" value="0"> Inactive</label>
                </div>
              </li>
             
              <?php } /*if(in_array('opd eye pediatrician dental', $permited_section)){?>
              <li class="per_menu_tab _opd_eye_pediatrician_dental">
                <strong>OPD Particular</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="opd_eye_pediatrician_dental_radio" onClick="opd_eye_pediatrician_dental_section_check(1);" id="opd_eye_pediatrician_dental_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="opd_eye_pediatrician_dental_radio" onClick="opd_eye_pediatrician_dental_section_check(0);" id="opd_eye_pediatrician_dental_inactive" value="0"> Inactive</label>
                </div>
              </li>

           
              <?php }*/ if(in_array('ipd', $permited_section)){?>
              <li class="per_menu_tab _ipd">
                <strong>IPD </strong>
                <div>
                  <label><input type="radio" name="ipd_radio" onClick="ipd_section_check(1);" id="ipd_active" value="1"  class="tab_active"> Active</label>
                  <label><input type="radio" name="ipd_radio" onClick="ipd_section_check(0);" id="ipd_inactive" value="0"   class="tab_inactive"> Inactive</label>
                </div>
              </li>
             <?php } if(in_array('ambulance', $permited_section)){?>
              <li class="per_menu_tab _ambulance">
                <strong>Ambulance</strong>
                <div>
                  <label><input type="radio" class="tab_active"  name="ambulance_radio" onClick="ambulance_section_check(1);" id="ambulance_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive"  name="ambulance_radio" onClick="ambulance_section_check(0);" id="ambulance_inactive" value="0"> Inactive</label>
                </div>
              </li>
            
            <?php } if(in_array('ot eye', $permited_section)){?>
              <li class="per_menu_tab _ot_eye">
                <strong>OT  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="ot_eye_radio" onClick="ot_eye_section_check(1);" id="ot_eye_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="ot_eye_radio" onClick="ot_eye_section_check(0);" id="ot_eye_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } /*if(in_array('eye ot', $permited_section)){ ?>
              <li class="per_menu_tab _eye_ot">
                <strong>OT Procedure</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="eye_ot_radio" onClick="eye_ot_section_check(1);" id="eye_ot_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="eye_ot_radio" onClick="eye_ot_section_check(0);" id="eye_ot_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php }*/ if(in_array('dialysis', $permited_section)){?>
              <li class="per_menu_tab _dialysis">
                <strong>Dialysis</strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="dialysis_radio" onClick="dialysis_section_check(1);" id="dialysis_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="dialysis_radio" onClick="dialysis_section_check(0);" id="dialysis_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } if(in_array('blood', $permited_section)){?>
              <li class="per_menu_tab _blood">
                <strong>Blood Bank</strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="blood_radio" onClick="blood_section_check(1);" id="blood_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="blood_radio" onClick="blood_section_check(0);" id="blood_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } if(in_array('pathology', $permited_section)){?>
              <li class="per_menu_tab _pathology">
                <strong>Pathology  </strong>
                <div>
                  <label><input type="radio"  class="tab_active "  name="pathology_radio" onClick="pathology_section_check(1);" id="pathology_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive "  name="pathology_radio" onClick="pathology_section_check(0);" id="pathology_inactive" value="0" > Inactive</label>
                </div>
              </li>
            <?php } if(in_array('vaccination pediatrician', $permited_section)){?>
              <li class="per_menu_tab _vaccination_pediatrician">
                <strong>Vaccination  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="vaccination_pediatrician_radio" onClick="vaccination_pediatrician_section_check(1);" id="vaccination_pediatrician_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="vaccination_pediatrician_radio" onClick="vaccination_pediatrician_section_check(0);" id="vaccination_pediatrician_inactive" value="0"> Inactive</label>
                </div>
              </li>
            <?php } if(in_array('inventory', $permited_section)){?>
              <li class="per_menu_tab _inventory">
                <strong>Inventory  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="inventory_radio" onClick="inventory_section_check(1);" id="inventory_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="inventory_radio" onClick="inventory_section_check(0);" id="inventory_inactive" value="0"> Inactive</label>
                </div>
              </li> 
            <?php } if(in_array('medicine', $permited_section)){?>
              <li class="per_menu_tab _medicine">
                <strong>Medicine  </strong>
                <div>
                  <label><input type="radio"  class="tab_active" name="medicine_radio" onClick="medicine_section_check(1);" id="medicine_active" value="1"> Active</label>
                  <label><input type="radio"  class="tab_inactive" name="medicine_radio" onClick="medicine_section_check(0);" id="medicine_inactive" value="0"> Inactive</label>
                </div>
              </li>             
           
             <?php } if(in_array('payroll', $permited_section)){?>
              <li class="per_menu_tab _payroll">
                <strong>Payroll</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="payroll_radio" onClick="payroll_section_check(1);" id="payroll_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="payroll_radio" onClick="payroll_section_check(0);" id="payroll_inactive" value="0"> Inactive</label>
                </div>
              </li>

            <?php }
                if(in_array('crm', $permited_section)){?>
              <li class="per_menu_tab _crm">
                <strong>CRM</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="crm_radio" onClick="crm_section_check(1);" id="crm_active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="crm_radio" onClick="crm_section_check(0);" id="crm_inactive" value="0"> Inactive</label>
                </div>
              </li>

            <?php }
            if(in_array('hmas_app', $permited_section)){?>
              <li class="per_menu_tab _hmas_app">
                <strong>Hmas App</strong>
                <div>
                  <label><input type="radio" class="tab_active" name="hmas_app_radio" onClick="hmas_app_section_check(1);" id="hmas_app__active" value="1"> Active</label>
                  <label><input type="radio" class="tab_inactive" name="hmas_app_radio" onClick="hmas_app_section_check(0);" id="hmas_app_inactive" value="0"> Inactive</label>
                </div>
              </li>

            <?php } ?>
           </ul>


               
           </div> <!-- pheader -->
     
           <div class="pheader">
                   <div class="p-1"><strong>Section</strong></div>
                   <div class="p-2"><strong>Permission Action</strong></div>
                   <div class="p-3"><strong>Permission Status</strong>
                     <input type="radio" name="all_radio" id="all_active" value="1" /> All Active
                      <input type="radio" name="all_radio" id="all_inactive" value="0" /> All Inactive
                   </div>
                   <div class="p-4"><strong>Attribute</strong></div>
           </div> <!-- pheader -->
          <?php
          if(!empty($section_list))
          {
             foreach($section_list as $section)
             {
               
               if($users_data['users_role']==2 && ($section->id==1 || $section->id==8))
               {

               }
               else
               {
               $action_list = branch_permission_action_list($section->id,$users_id);
               $class= str_replace(' ', '_', $section->action_class);
              ?>
                <div class="pbody prms_table _<?php echo $class;?>">
                   <div class="p-1"><strong><?php echo $section->section; ?>
                         <?php if(!empty($action_list)){?>
                              <br/>
                              <input type="radio" name="section_radio" value="<?php echo $section->id; ?>"id="all_section_action_active" onClick="section_check(<?php echo $section->id; ?>,1);" /> Active
                              <input type="radio"  value="<?php echo $section->id; ?>" name="section_radio" id="all_section_action_inactive" onClick="section_check(<?php echo $section->id; ?>,0);" /> Inactive
                         <?php } ?></strong></div> <!-- p-1 -->
                   <div class="p-2">
                   <?php
                   
                   if(!empty($action_list))
                   {
                     foreach($action_list as $action)
                     {
                      ?>
                        <div class="action"><?php echo $action->action; ?></div> 
                      <?php
                     }
                   }
                   else
                   {
                     echo '<div class="action text-danger">N/A</div>';
                   }
                   ?> 
                   </div> <!-- p-2 --> 
                   <div class="p-3">  
                   <?php
                   $class_2='';
                   $class_1='';
                   $class_3='';
                    if(!empty($action_list))
                   { 
                     foreach($action_list as $action)
                     {   
                      $class_module=str_replace(' ', '_', $action->module_class);
                      ?> 
                             <div class="per">
                                <span><input type="radio" class="active active_section_<?php echo $section->id; ?> <?php echo  $class_module; ?>" name="active[<?php echo $action->id; ?>]" value="<?php echo $section->id.'-'.$action->id; ?>-1" <?php if($action->user_permission_status==1){ echo 'checked=""'; } ?> /> Active</span> 
                                <span><input type="radio" class="inactive inactive_section_<?php echo $section->id; ?> un_<?php echo $class_module; ?>" name="active[<?php echo $action->id; ?>]"  value="<?php echo $section->id.'-'.$action->id; ?>-2" <?php if($action->user_permission_status!=1){ echo 'checked=""'; } ?>> Inactive</span> 
                            </div> 
                          
                      <?php
                     }
                   }
                   else
                   {
                    echo '<div class="action  text-danger">N/A</div>';
                   }
                   ?>  
                   </div> <!-- p-3 -->

                   <div class="p-4">  
                   <?php
                    if(!empty($action_list))
                   {
                     foreach($action_list as $action)
                     {   
                        
                        if($action->attribute==1)
                        {  
                           ?> 
                            <div class="per">
                              <?php 
                              if($users_data['users_role']==1)
                              {
                                ?>
                                <input type="text"  name="attribute_val-<?php echo $action->id; ?>" id="attribute_val-<?php echo $action->id; ?>" value="<?php echo $action->attribute_val; ?>"   />
                                <?php 
                              }
                              else
                              {
                                if(!empty($action->attribute_val) && $action->attribute_val>0)
                                {
                                  if($section->id==1 && $action->id==2)
                                  {
                                    $total_attribute = get_permission_attr(1,2);
                                  }
                                  elseif($section->id==5 && $action->id==23)
                                  {
                                    $total_attribute = $action->attribute_val;
                                  }
                                  else
                                  {
                                    $total_attribute = $action->attribute_val;
                                  }
                                ?>
                                <select name="attribute_val-<?php echo $action->id; ?>" id="attribute_val-<?php echo $action->id; ?>">
                                   <option value="">Select total</option>
                                   <?php
                                   for($i=1;$i<=$total_attribute;$i++)
                                   {
                                       $selected_drop = '';
                                       if($action->attribute_val==$i)
                                       {
                                           $selected_drop = 'selected="selected"';
                                       }
                                     echo '<option '.$selected_drop.' value="'.$i.'">'.$i.'</option>';
                                   }
                                   ?>
                                </select>
                             
                                <?php
                                }
                                else
                                {
                                  echo $action->attribute_val;
                                }
                              }
                              ?> 
                               
                            </div>  
                           <?php
                        } 
                        else
                        {
                          ?>
                           <div class="per text-danger">
                                N/A
                            </div>  
                          <?php
                        }
                     }
                   }
                   else
                   {
                    echo '<div class="action text-danger">N/A</div>';
                   }
                   ?>  
                   </div> <!-- p-3 -->       

                 </div> <!-- pbody --> 
              <?php
              }
             } // Foreach end
          }
          ?>
        
      </div> <!-- permission -->
      </div>



     <div class="permission-btns">
     <!-- <div class=""> -->
      <div class="btns">
        <button type="submit"  class="btn-update" name="submit" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
        <a class="btn-anchor" onClick="window.location.href='<?php echo base_url('patient'); ?>'"><i class="fa fa-sign-out"></i> Exit</a> 
      </div>
      <!-- </div> -->
     </div> 

   </div> <!-- userlist-box -->
 
</section> <!-- userlist -->
</form>
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">

$("#permission_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();   
  $.ajax({
    url: "<?php echo base_url(); ?>users/permission/<?php echo $users_id; ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      $('.overlay-loader').hide(); 
      flash_session_msg(result);      
    }
  });
});  
 
$("#all_active").click(function() {
    if ($(this).is(':checked'))
    {
        $("input:radio.active").prop("checked", "checked");
    } 
});

$("#all_inactive").click(function() {
    if ($(this).is(':checked'))
    {
        $("input:radio.inactive").prop("checked", "checked");
    } 
});

 function section_check(id,vals)
        {
           if(vals==1)
           {
               $("input:radio.inactive_section_"+id).prop('checked',false);
               $("input:radio.active_section_"+id).prop('checked', true);
                     
           }
           else
           { 
               $("input:radio.active_section_"+id).prop('checked',false);
               $("input:radio.inactive_section_"+id).prop('checked', true);
           
               
           }
        }

        function opd_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.opd").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_opd").prop('checked', true);
           }
        }

        function ipd_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.ipd").prop('checked', true);
           }
           else
           { //alert('1');
            $("input:radio.un_ipd").prop('checked', true);
           }
        }

        function medicine_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.medicine").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_medicine").prop('checked', true);
           }
        }
        function pathology_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.pathology").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_pathology").prop('checked', true);
           }
        }

        function ot_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.ot").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_ot").prop('checked', true);
           }
        }

        function common_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.common").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_common").prop('checked', true);
           }
        }
         function inventory_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.inventory").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_inventory").prop('checked', true);
           }
        }

      function eye_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.eye").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_eye").prop('checked', true);
       }
      }


      function dialysis_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.dialysis").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_dialysis").prop('checked', true);
       }
      }

      function vaccine_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.vaccination").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_vaccination").prop('checked', true);
       }
      }


      function blood_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.blood").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_blood").prop('checked', true);
       }
      }

      function pediatrician_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.pediatrician").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_pediatrician").prop('checked', true);
       }
      }
      function dental_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.dental").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_dental").prop('checked', true);
       }
      }

      function ambulance_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.ambulance").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_ambulance").prop('checked', true);
       }
      }

      function eye_ot_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.eye_ot").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_eye_ot").prop('checked', true);
       }
      }

      function advance_eye_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.advance_eye").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_advance_eye").prop('checked', true);
       }
      }

      function gynecology_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.gynecology").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_gynecology").prop('checked', true);
       }
      }

      function opd_dental_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.opd_dental").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_opd_dental").prop('checked', true);
       }
      }


      function opd_eye_pediatrician_dental_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.opd_eye_pediatrician_dental").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_opd_eye_pediatrician_dental").prop('checked', true);
       }
      }

       function opd_eye_section_check(vals)
      {
       if(vals==1)
       {
        
           $("input:radio.opd_eye").prop('checked', true);
       }
       else
       { 
        $("input:radio.un_opd_eye").prop('checked', true);
       }
      }

       function ot_eye_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.ot_eye").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_ot_eye").prop('checked', true);
           }
        }

        function vaccination_pediatrician_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.vaccination_pediatrician").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_vaccination_pediatrician").prop('checked', true);
           }
        }
        
        function day_care_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.day_care").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_day_care").prop('checked', true);
           }
        }


        function payroll_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.payroll").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_payroll").prop('checked', true);
           }
        }
        
        function crm_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.crm").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_crm").prop('checked', true);
           }
        }
        
        
         function hmas_app_section_check(vals)
        {
           if(vals==1)
           {
               $("input:radio.hmas_app").prop('checked', true);
           }
           else
           { 
            $("input:radio.un_hmas_app").prop('checked', true);
           }
        }
</script>

<script>
  // tabs for permission_menu
  $(document).ready(function(){
    // for active tab
    $('.prms_table').hide();
    $('._common').show();

    $('.per_menu_tab').click(function(){
      $('.per_menu_tab').removeClass('active');
      $(this).addClass('active');
    });
    // for inactive tab
    $('.per_menu_tab').click(function(){
      $('.per_menu_tab').removeClass('active');
      $(this).addClass('active');
    });
    // for tab content
    $('._ipd').click(function () {
      $('.prms_table').hide();
      $('.prms_table._ipd').show();
    });
    $('._opd').click(function () {
      $('.prms_table').hide();
      $('.prms_table._opd').show();
    });
     $('._day_care').click(function () {
      $('.prms_table').hide();
      $('.prms_table._day_care').show();
    });
    $('._medicine').click(function () {
      $('.prms_table').hide();
      $('.prms_table._medicine').show();
    });
    $('._pathology').click(function () {
      $('.prms_table').hide();
      $('.prms_table._pathology').show();
    });
    $('._ot').click(function () {
      $('.prms_table').hide();
      $('.prms_table._ot').show();
    }); 
    $('._common').click(function () {
      $('.prms_table').hide();
      $('.prms_table._common').show();
    });
    $('._inventory').click(function () {
      $('.prms_table').hide();
      $('.prms_table._inventory').show();
    });
    $('._opd_eye').click(function () {
      $('.prms_table').hide();
      $('.prms_table._opd_eye').show();
    });
    
    $('._dialysis').click(function () {
      $('.prms_table').hide();
      $('.prms_table._dialysis').show();
    });
    $('._blood').click(function () {
      $('.prms_table').hide();
      $('.prms_table._blood').show();
    });
    $('._pediatrician').click(function () {
      $('.prms_table').hide();
      $('.prms_table._pediatrician').show();
    });
    $('._dental').click(function () {
      $('.prms_table').hide();
      $('.prms_table._dental').show();
    });
    $('._gynecology').click(function () {
      $('.prms_table').hide();
      $('.prms_table._gynecology').show();
    });
    $('._ambulance').click(function () {
      $('.prms_table').hide();
      $('.prms_table._ambulance').show();
    });

    $('._vaccination_pediatrician').click(function () {
      $('.prms_table').hide();
      $('.prms_table._vaccination_pediatrician').show();
    });
    $('._eye_ot').click(function () {
      $('.prms_table').hide();
      $('.prms_table._eye_ot').show();
    });
    $('._ot_eye').click(function () {
      $('.prms_table').hide();
      $('.prms_table._ot_eye').show();
    });
    $('._opd_dental').click(function () {
      $('.prms_table').hide();
      $('.prms_table._opd_dental').show();
    });
    $('._opd_eye_pediatrician_dental').click(function () {
      $('.prms_table').hide();
      $('.prms_table._opd_eye_pediatrician_dental').show();
    });
    $('._eye').click(function () {
      $('.prms_table').hide();
      $('.prms_table._eye').show();
    });

    $('._advance_eye').click(function () {
      $('.prms_table').hide();
      $('.prms_table._advance_eye').show();
    });
     $('._payroll').click(function () {
      $('.prms_table').hide();
      $('.prms_table._payroll').show();
    });
    
    $('._crm').click(function () {
      $('.prms_table').hide();
      $('.prms_table._crm').show();
    });

    $('._hmas_app').click(function () {
      $('.prms_table').hide();
      $('.prms_table._hmas_app').show();
    });
    
  });
</script>


</div><!-- container-fluid -->
</body>
</html>