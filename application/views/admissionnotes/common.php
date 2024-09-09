<div class="userlist-box" style="width:100% !important">  
      <div id="permission_section">
           <div class="prescription_permission" style="width:100%;">
           <div> <?php  $uri_url = $this->uri->segment(2); ?>
            <ul class="prescription_menu">
            
              <li class="per_menu_tab <?php if($uri_url=='previous_history'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('admissionnotes/previous_history');?>';" name="new_patient"> Previous History </span></label> </strong>
                
              </li>
              <li class="per_menu_tab <?php if($uri_url=='chief_complaints'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('admissionnotes/chief_complaints');?>';"> Chief Complaints </span></label> </strong>
                
              </li>
               <li class="per_menu_tab <?php if($uri_url=='examination'){ ?> active <?php } ?>">
                <strong><label><span  onClick="window.location='<?php echo base_url('admissionnotes/examination');?>';"> Examination </span>
                    </label>
                 </strong>
                </li>
              
                
                
                <li class="per_menu_tab <?php if($uri_url=='diagnosis'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/diagnosis');?>';">Diagnosis </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='test_name'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/test_name');?>';"> Test Name </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='medicine'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/medicine');?>';"> Medicine </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='personal_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/personal_history');?>';" > Personal History </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='type'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/type');?>';"> Type </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='dosage'){ ?> active <?php } ?>">
                <strong><label>
                    <span  onClick="window.location='<?php echo base_url('admissionnotes/dosage');?>';">Dosage </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='duration'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/duration');?>';"> Duration </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='frequency'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/frequency');?>';"> Frequency </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='advice'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/advice');?>';"> Advice </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='suggestion'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/suggestion');?>';"> Suggestion </span>
                    </label>
                 </strong>
                </li>
                <!-- added by Nitin Sharma 29th Jan 2024 -->
                <li class="per_menu_tab <?php if($uri_url=='history_presenting_illness'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/history_presenting_illness');?>';">History of Presenting Illness (HOPI) </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='obstetrics_menstrual_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/obstetrics_menstrual_history');?>';">Obstetrics Menstrual History </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='family_history_disease'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('admissionnotes/family_history_disease');?>';"> Family history of any relevant disease </span>
                    </label>
                 </strong>
                </li>
                <!-- Ended by Nitin Sharma 29th Jan 2024 -->
                
            </ul>
            </div>
        </div>
     </div>
</div>