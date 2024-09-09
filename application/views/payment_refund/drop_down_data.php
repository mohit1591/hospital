  <?php if($type=="1") {?>

          <div class="grp" id="selection_criteria">Searching Criteria 
                        <select id="selection_criteria_list" name="selection_criteria_list" onchange="get_selected_value(this.value);">
                        <option value=''>Select Option</option>
                        <option value="patient_name">Patient Name</option>
                        <option value="p_mobile_no">Mobile No.</option>
                        </select>
             </div>
                     <div class="grp" id="search_box_patient_name" style="display:none;"><input type="text" name="patient_name"  id="patient_name"/></div>
                      <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                     <div class="grp" id="search_box_mobile_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="mobile_no" id="mobile_no"/></div> 
                      <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                    <div class="grp" id="branch_list"> <div id="child_branch" class="grp"></div>  </div>
                   <button type="button" class="btn-custom" onclick="get_balance_clearance_list(1);">Search</button>
                     <input type="hidden" value="<?php echo $type; ?>" name="type" id="type"/>
                   <?php }?>

                   <?php if($type=="2") {?>

                      <div class="grp" id="selection_criteria">Searching Criteria 
                      <select id="selection_criteria_list" name="selection_criteria_list" onchange="get_selected_value(this.value);">
                      <option value=''>Select Option</option>
                      <option value="vendor_name">Vendor Name</option>
                      <option value="v_mobile_no">Mobile No.</option>
                      </select>
                      </div>
                     <div class="grp" id="search_box_vendor_name" style="display:none;"><input type="text" name="vendor_name"  id="vendor_name"/></div>
                      <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                     <div class="grp" id="search_box_vendor_mobile_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="vendor_mobile_no" id="vendor_mobile_no"/></div> 
                      <?php if(!empty($form_error)){ echo form_error('vendor_mobile_no'); } ?>
                    <div class="grp" id="branch_list"> <div id="child_branch" class="grp"></div>  </div>
                    <div class="grp"> 
                   <button type="button" class="btn-custom" onclick="get_balance_clearance_list(2);">Search</button>
                    <input type="hidden" value="<?php echo $type; ?>" name="type" id="type"/>
                   </div>

                   <?php }?>