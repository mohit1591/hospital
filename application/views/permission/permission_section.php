<div class="permission">
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
                $action_list = permission_action_list($section->id, $user_role);
              ?>
                <div class="pbody">
                   <div class="p-1"><strong><?php echo $section->section; ?>
                         <?php if(!empty($action_list)){?>
                              <br/>
                              <input type="radio" name="section_radio" value="<?php echo $section->id; ?>"id="all_section_action_active" onclick="section_check(<?php echo $section->id; ?>,1);" /> Active
                              <input type="radio"  value="<?php echo $section->id; ?>" name="section_radio" id="all_section_action_inactive" onclick="section_check(<?php echo $section->id; ?>,0);" /> Inactive
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
                     echo '<div class="action  text-danger">N/A</div>';
                   }
                   ?> 
                   </div> <!-- p-2 --> 
                   <div class="p-3">  
                   <?php
                    if(!empty($action_list))
                   { 
                     foreach($action_list as $action)
                     {   
                      ?>    
                            <div class="per">
                                <span><input type="radio" class="active active_section_<?php echo $section->id; ?>" name="active[<?php echo $action->id; ?>]" value="<?php echo $section->id.'-'.$action->id; ?>-1" <?php if($action->permission_status==1){ echo 'checked=""'; } ?> /> Active</span> 
                                <span><input type="radio" class="inactive inactive_section_<?php echo $section->id; ?>" name="active[<?php echo $action->id; ?>]"  value="<?php echo $section->id.'-'.$action->id; ?>-2" <?php if(empty($action->permission_status)){ echo 'checked=""'; } ?>> Inactive</span> 
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
                                <input type="text" name="attribute_val-<?php echo $action->id; ?>" value="<?php echo $action->attribute_val; ?>">
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
                    echo '<div class="action  text-danger">N/A</div>';
                   }
                   ?>  
                   </div> <!-- p-3 -->       

                 </div> <!-- pbody --> 
              <?php
             }
          }
          ?>
        
      </div> <!-- permission -->

      <div class="permission-btns">
      <div class="btns">
        <button type="submit"  class="btn-update" name="submit" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'"><i class="fa fa-sign-out"></i> Exit</button> 
      </div>
     </div> 
     <script type="text/javascript">
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
     </script>