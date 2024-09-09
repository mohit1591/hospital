<?php
$users_data = $this->session->userdata('auth_users'); 
//print_r($form_data);die;
?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="advance_search" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker start_datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>" id="" >
                        </div>

                        <div class="grp">
                          <label>From Follow-Up</label> 
                          <input type="text" class="datepicker datepicker" name="from_followup" value="<?php echo $form_data['from_followup']; ?>" id="" >
                        </div> 

                        <div class="grp">
                          <label>From Call Date</label> 
                          <input type="text" class="datepicker datepicker" name="from_call" value="<?php echo $form_data['from_call']; ?>" id="" >
                        </div> 

                        <div class="grp">
                          <label>From Appointment Date</label> 
                          <input type="text" class="datepicker datepicker" name="from_appointment" value="<?php echo $form_data['from_appointment']; ?>" id="" >
                        </div> 

                        <div class="grp">
                          <label>Lead Type</label> 
                          <select name="lead_source_id" id="lead_source_id" class="">
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
                        </div> 

                        <div class="grp">
                          <label>Department</label> 
                          <select name="department_id" id="department_id" class="">
                              <option value="">Select Department</option>
                             
                               
                               <?php
                  
               $users_data = $this->session->userdata('auth_users'); 
               if (array_key_exists("permission",$users_data)){
                     $permission_section = $users_data['permission']['section'];
                     $permission_action = $users_data['permission']['action'];
                }
                else{
                     $permission_section = array();
                     $permission_action = array();
                     $permission_section_new=array();
                   }
                //echo "<pre>"; print_r($dept_list);    die;
                if(!empty($department_list))
                {
                  foreach($department_list as $dept)
                   {
                       $lead_source_select = '';
                          if($form_data['department_id']==$dept->id)
                          {
                              $lead_source_select = 'selected="selected"';
                          }  
                          if($dept->department =='OPD' && in_array('85',$permission_section))
                          {
                              
                                 echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                          }
                           elseif($dept->department =='OPD Billing' && in_array('151',$permission_section))
                          {
                              echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                          }
                           elseif($dept->department =='IPD' && in_array('121',$permission_section))
                            {
                               echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>'; 
                            }
                            elseif($dept->department =='OT' && in_array('134',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                            elseif($dept->department =='BLOOD BANK' && in_array('262',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                             else if($dept->department =='Ambulance' && in_array('349',$permission_section))
                            {
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';    
                            }
                             elseif($dept->department =='LAB' && in_array('145',$permission_section))
                            { 
                                echo '<option '.$lead_source_select.' value="'.$dept->id.'">'.$dept->department.'</option>';  
                            }
                            
                            
                       
                   }
                   
                        if(in_array('179',$permission_section))
                            { 
                                echo '<option '.$lead_source_select.' value="-1">Vaccination</option>';  
                            }
                            
                             echo '<option '.$lead_source_select.' value="-2">Other</option>';  
                   
                }
                  
                  
                  
                  
                 
                               /*if(!empty($department_list))
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
                               }*/
                              ?>
                          </select>
                        </div>

                        <div class="grp">
                          <label>Lead Maturity</label> 
                          <select name="lead_maturity" id="lead_maturity" class=""> 
                              <option value="">All</option>
                              <option value="1">Booked</option>
                              <option value="2">Closed</option> 
                              <option value="3">Booked/Closed</option>
                          </select>
                        </div>

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>" id="">
                        </div>   

                        <div class="grp">
                          <label>To Follow-Up</label> 
                          <input type="text" class="datepicker datepicker" name="to_followup" value="<?php echo $form_data['to_followup']; ?>" id="" >
                        </div> 

                        <div class="grp">
                          <label>To Call Date</label> 
                          <input type="text" class="datepicker datepicker" name="to_call" value="<?php echo $form_data['to_call']; ?>" id="" >
                        </div> 

                        <div class="grp">
                          <label>To Appointment Date</label> 
                          <input type="text" class="datepicker datepicker" name="to_appointment" value="<?php echo $form_data['to_appointment']; ?>" id="" >
                        </div>

                        <div class="grp">
                          <label>Source</label> 
                          <select name="lead_type_id" id="lead_type_id" class="" onchange="return set_lead_type(this.value)">
                            <option value="">Select Lead Source</option>
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
                        </div> 

                        <div class="grp">
                          <label>Call Status</label> 
                          <select name="call_status" id="call_status" class="">
                            <option value="">Select Call Status</option>
                            <?php
                             if(!empty($call_status_list))
                             {
                                foreach($call_status_list as $call_status)
                                {
                                    $lead_source_select = '';
                                    if($form_data['call_status']==$call_status['id'])
                                    {
                                        $lead_source_select = 'selected="selected"';
                                    }    
                                        echo '<option '.$lead_source_select.' value="'.$call_status['id'].'">'.$call_status['call_status'].'</option>';
                                    
                                }
                             }
                            ?>
                        </select>
                        </div> 

                         

                      </div> <!-- inner -->

                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> -->
               <input value="Reset" class="btn-reset"  onclick="reset_search(this.form)" type="button">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  
$("#advance_search").on("submit", function(event) { 
  event.preventDefault();   
  $.ajax({
    url: "<?php echo base_url('crm/leads/set_advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      $('#load_leads_modal_popup').modal('hide'); 
        reload_table();      
    }
  });
}); 


$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true
});   


$("#advance_search").on("submit", function(event) { 
  event.preventDefault();   
  $.ajax({
    url: "<?php echo base_url('crm/report/set_report/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      window.open('<?php echo base_url('crm/report/print_report') ?>','mywin','width=800,height=600');   
    }
  });
}); 
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->