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
 ?>
<!-- ============================= Main content start here ===================================== -->
<form id="permission_form">
<input type="hidden" value="<?php echo $branch_id; ?>" name="bid" />
<section class="userlist">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
    <div class="userlist-box">  
      <div id="permission_section">
           <div class="permission">
           <?php 
           if($users_data['users_role']==1)
           {
           ?>
           <div>

           <table class="table table-bordered">
             <tbody>
               <tr>
                 <td valign="middle"><strong>Module</strong></td>
                 <td>
                   <div class="module_permission"><strong>IPD</strong>
                     <input type="radio" name="ipd_radio" onClick="ipd_section_check(1);" id="ipd_active" value="1" /> Active
                      <input type="radio" name="ipd_radio" onClick="ipd_section_check(0);" id="ipd_inactive" value="0" /> Inactive
                   </div>
                   <div class="module_permission"><strong>OPD</strong>
                     <input type="radio" class="opd_all" onClick="opd_section_check(1);" name="opd_radio" id="opd_active" value="1" /> Active
                      <input type="radio" name="opd_radio" onClick="opd_section_check('0');" id="opd_inactive" value="0" /> Inactive
                   </div>
                   <div class="module_permission"><strong>Medicine</strong>
                     <input type="radio" name="medicine_radio" onClick="medicine_section_check(1);" id="medicine_active" value="1" /> Active
                      <input type="radio" name="medicine_radio" onClick="medicine_section_check(0);" id="medicine_inactive" value="0" /> Inactive
                   </div>
                   <div class="module_permission"><strong>Pathology</strong>
                     <input type="radio" name="pathology_radio" onClick="pathology_section_check(1);" id="pathology_active" value="1" /> Active
                      <input type="radio" name="pathology_radio" onClick="pathology_section_check(0);" id="pathology_inactive" value="0" /> Inactive
                   </div>
                   <div class="module_permission"><strong>OT</strong>
                     <input type="radio" name="ot_radio" onClick="ot_section_check(1);" id="ot_active" value="1" /> Active
                      <input type="radio" name="ot_radio" onClick="ot_section_check(0);" id="ot_inactive" value="0" /> Inactive
                   </div>
                   <div class="module_permission"><strong>Common</strong>
                     <input type="radio" name="common_radio" onClick="common_section_check(1);" id="common_active" value="1" /> Active
                      <input type="radio" name="common_radio" onClick="common_section_check(0);" id="common_inactive" value="0" /> Inactive
                   </div>
                    <div class="module_permission"><strong>Inventory</strong>
                     <input type="radio" name="inventory_radio" onClick="inventory_section_check(1);" id="inventory_active" value="1" /> Active
                      <input type="radio" name="inventory_radio" onClick="inventory_section_check(0);" id="inventory_inactive" value="0" /> Inactive
                   </div>

                    <div class="module_permission"><strong>Eye</strong>
                     <input type="radio" name="eye_radio" onClick="eye_section_check(1);" id="eye_active" value="1" /> Active
                      <input type="radio" name="eye_radio" onClick="eye_section_check(0);" id="eye_inactive" value="0" /> Inactive
                   </div>

                   <div class="module_permission"><strong>Vaccination</strong>
                     <input type="radio" name="vaccine_radio" onClick="vaccine_section_check(1);" id="vaccine_active" value="1" /> Active
                      <input type="radio" name="vaccine_radio" onClick="vaccine_section_check(0);" id="vaccine_inactive" value="0" /> Inactive
                   </div>

                   <div class="module_permission"><strong>Dialysis</strong>
                     <input type="radio" name="dialysis_radio" onClick="dialysis_section_check(1);" id="dialysis_active" value="1" /> Active
                      <input type="radio" name="dialysis_radio" onClick="dialysis_section_check(0);" id="dialysis_inactive" value="0" /> Inactive
                   </div>

                   <div class="module_permission"><strong>Blood Bank</strong>
                     <input type="radio" name="blood_radio" onClick="blood_section_check(1);" id="blood_active" value="1" /> Active
                      <input type="radio" name="blood_radio" onClick="blood_section_check(0);" id="blood_inactive" value="0" /> Inactive
                   </div>

                    <div class="module_permission"><strong>Pediatrician</strong>
                     <input type="radio" name="pediatrician_radio" onClick="pediatrician_section_check(1);" id="pediatrician_active" value="1" /> Active
                      <input type="radio" name="pediatrician_radio" onClick="pediatrician_section_check(0);" id="pediatrician_inactive" value="0" /> Inactive
                   </div>
                   
                   <div class="module_permission"><strong>Dental</strong>
                     <input type="radio" name="dental_radio" onClick="dental_section_check(1);" id="dental_active" value="1" /> Active
                      <input type="radio" name="dental_radio" onClick="dental_section_check(0);" id="dental_inactive" value="0" /> Inactive
                   </div>
                    
                    <div class="module_permission"><strong>Gynecology</strong>
                     <input type="radio" name="gynecology_radio" onClick="gynecology_section_check(1);" id="gynecology_active" value="1" /> Active
                      <input type="radio" name="gynecology_radio" onClick="gynecology_section_check(0);" id="gynecology_inactive" value="0" /> Inactive
                   </div>
                   
                   <div class="module_permission"><strong>Ambulance</strong>
                     <input type="radio" name="ambulance_radio" onClick="ambulance_section_check(1);" id="ambulance_active" value="1" /> Active
                      <input type="radio" name="ambulance_radio" onClick="ambulance_section_check(0);" id="ambulance_inactive" value="0" /> Inactive
                   </div>

                 </td>
               </tr>
             </tbody>
           </table>

                   <!-- <div><strong>Module</strong></div>
                   
                   <div><strong>IPD</strong>
                     <input type="radio" name="ipd_radio" onClick="ipd_section_check(1);" id="ipd_active" value="1" /> Active
                      <input type="radio" name="ipd_radio" onClick="ipd_section_check(0);" id="ipd_inactive" value="0" /> Inactive
                   </div>
                   <div><strong>OPD</strong>
                     <input type="radio" class="opd_all" onClick="opd_section_check(1);" name="opd_radio" id="opd_active" value="1" /> Active
                      <input type="radio" name="opd_radio" onClick="opd_section_check('0');" id="opd_inactive" value="0" /> Inactive
                   </div>
                   <div><strong>Medicine</strong>
                     <input type="radio" name="medicine_radio" onClick="medicine_section_check(1);" id="medicine_active" value="1" /> Active
                      <input type="radio" name="medicine_radio" onClick="medicine_section_check(0);" id="medicine_inactive" value="0" /> Inactive
                   </div>
                   <div><strong>Pathology</strong>
                     <input type="radio" name="pathology_radio" onClick="pathology_section_check(1);" id="pathology_active" value="1" /> Active
                      <input type="radio" name="pathology_radio" onClick="pathology_section_check(0);" id="pathology_inactive" value="0" /> Inactive
                   </div>
                   <div><strong>Common</strong>
                     <input type="radio" name="common_radio" onClick="common_section_check(1);" id="common_active" value="1" /> Active
                      <input type="radio" name="common_radio" onClick="common_section_check(0);" id="common_inactive" value="0" /> Inactive
                   </div> -->
                   
           </div> <!-- pheader -->
           <?php } ?>
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
               $action_list = branch_permission_action_list($section->id,$branch_id);
              ?>
                <div class="pbody">
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
                      $class_module=explode(" ",$action->module_class);
                      if(!empty($class_module[0]))
                      {
                        $class_1=$class_module[0];
                      }
                      if(!empty($class_module[1]))
                      {
                        $class_2=$class_module[1];
                      }
                      if(!empty($class_module[2]))
                      {
                        $class_3=$class_module[2];
                      }

                      //print_r($class);
                      ?>    
                            <div class="per">
                                <span><input type="radio" class="active active_section_<?php echo $section->id; ?> <?php echo  $class_1; ?> <?php echo  $class_2; ?> <?php echo  $class_3; ?>" name="active[<?php echo $action->id; ?>]" value="<?php echo $section->id.'-'.$action->id; ?>-1" <?php if($action->user_permission_status==1){ echo 'checked=""'; } ?> /> Active</span> 
                                <span><input type="radio" class="inactive inactive_section_<?php echo $section->id; ?> un_<?php echo $class_1; ?> un_<?php echo $class_2; ?> un_<?php echo $class_3; ?>" name="active[<?php echo $action->id; ?>]"  value="<?php echo $section->id.'-'.$action->id; ?>-2" <?php if($action->user_permission_status!=1){ echo 'checked=""'; } ?>> Inactive</span> 
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
                                     //$total_attribute = get_total_user(5,23);
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
                               <?php //echo   $total_attribute = $action->attribute_val;?>
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
        <a class="btn-anchor" onClick="window.location.href='<?php echo base_url('branch'); ?>'"><i class="fa fa-sign-out"></i> Exit</a> 
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
    url: "<?php echo base_url(); ?>branch/permission/<?php echo $branch_id; ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      $('#overlay-loader').hide(); 
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

      

</script>


</div><!-- container-fluid -->
</body>
</html>