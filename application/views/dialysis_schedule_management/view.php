<div class="modal-dialog modal-lg">

  <div class="modal-content">  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
         
            <div class="row">
              <div class="col-sm-6">
                  
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Schedule Name </label></div>
                    <div class="col-sm-7"><?php echo $form_data['schedule_name']; ?></div>
                  </div> 
                  

                
                    <div class="row">
                    <div class="col-sm-5"><label> Status </label></div>
                    <div class="col-sm-7"><?php if($form_data['status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?></div>
                  </div>

                  
                  
               
              </div> <!-- Left main6 -->

              <div class="col-sm-6">
                
                  
            <div class="row m-b-5">
            <div class="col-xs-4">
            <strong> Available Days</strong>
            </div>
              <div class="col-xs-8">
              <div id="day_check">
              <?php //print_r($available_day);
              foreach ($days_list as $value) 
              { 

                  ?>
                  <?php if(isset($available_day[$value->id]))
                  { ?> 
                  <div><?php echo $value->day_name; ?></div>
                  <?php } ?>
                  
                    <?php

              }
              ?>
              </div>
               <div>
                </div>
                   
            </div>
            </div>
          <?php
          if(!empty($available_day))
          {
            foreach ($available_day as $key=>$value) 
            {
                  ?>
                        <div><div class="row m-b-5">
                          <div class="col-xs-4"><strong><?php  echo $value; ?> </strong></div>
                          <div class="col-xs-8">
                          <table class="schedule_timing" id="doctor_time_table_day_<?php echo $key; ?>">
                          <thead>
                                  <tr>
                                      <td>From </td>
                                      <td>To </td>
                                      
                                  </tr>
                          </thead>
                          <tbody>
                          <?php 
                         $doctor_availablity_time = get_dialysis_schedule_time($key,$form_data['id']);
                          if(!empty($doctor_availablity_time))
                          {
                             $k=0;
                             foreach ($doctor_availablity_time as $doctor_time) 
                             { 
                          ?>
                              <tr>
                                    <td><?php echo $doctor_time->from_time; ?></td>
                                    <td><?php echo $doctor_time->to_time; ?></td>
                                    
                              </tr>
                             <?php 
                              $k++;
                              } 
                            }
                          ?>
                          </tbody>
                          </table>
                          </div>
                          </div>
                          </div>
                      <?php 
            }
          }

          

          ?>

         <div class="row m-b-5">

            <div class="col-xs-4">
                <strong>Per Patient Time</strong>
           </div>
           <div class="col-xs-8"><?php echo $form_data['per_patient_timing']; ?> Min.</div>
        </div>  <!-- row -->
                     

                   
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Created date</label></div>
                    <div class="col-sm-7"><?php echo date('d M Y H:i A', strtotime($form_data['created_date'])); ?></div>
                  </div>  
                  
                  
                  
         

              </div> <!-- Right main6 -->
           </div> <!-- MainRow -->
           
          
      </div>   <!-- modal_body -->  
             
             
        <div class="modal-footer">  
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
        </div><!-- /.modal-content -->
      
<div id="load_add_comission_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>     
</div><!-- /.modal-dialog -->