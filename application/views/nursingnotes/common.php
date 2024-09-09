<div class="userlist-box" style="width:100% !important">  
      <div id="permission_section">
           <div class="prescription_permission" style="width:100%;">
           <div> <?php  $uri_url = $this->uri->segment(2); ?>
            <ul class="prescription_menu">
            
              <li class="per_menu_tab <?php if($uri_url=='previous_history'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('nursingnotes/previous_history');?>';" name="new_patient"> Previous History </span></label> </strong>
                
              </li>
              <li class="per_menu_tab <?php if($uri_url=='chief_complaints'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('nursingnotes/chief_complaints');?>';"> Chief Complaints </span></label> </strong>
                
              </li>
               <li class="per_menu_tab <?php if($uri_url=='examination'){ ?> active <?php } ?>">
                <strong><label><span  onClick="window.location='<?php echo base_url('nursingnotes/examination');?>';"> Examination </span>
                    </label>
                 </strong>
                </li>
              
                
                
                <li class="per_menu_tab <?php if($uri_url=='diagnosis'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/diagnosis');?>';">Diagnosis </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='test_name'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/test_name');?>';"> Test Name </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='medicine'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/medicine');?>';"> Medicine </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='personal_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/personal_history');?>';" > Personal History </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='type'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/type');?>';"> Type </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='dosage'){ ?> active <?php } ?>">
                <strong><label>
                    <span  onClick="window.location='<?php echo base_url('nursingnotes/dosage');?>';">Dosage </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='duration'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/duration');?>';"> Duration </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='frequency'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/frequency');?>';"> Frequency </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='advice'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/advice');?>';"> Advice </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='suggestion'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('nursingnotes/suggestion');?>';"> Suggestion </span>
                    </label>
                 </strong>
                </li>
                
                
                
            </ul>
            </div>
        </div>
     </div>
</div>