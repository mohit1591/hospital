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
                    <div class="col-sm-5"><label>Doctor Reg. No.</label></div>
                    <div class="col-sm-7"><?php echo $form_data['doctor_code']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Doctor Name </label></div>
                    <div class="col-sm-7"><?php echo $form_data['doctor_name']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Specialization </label></div>
                    <div class="col-sm-7"><?php echo $form_data['specialization']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Mobile No. </label></div>
                    <div class="col-sm-7"><?php echo $form_data['mobile_no']; ?></div>
                  </div>

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Sharing Pattern </label></div>
                    <div class="col-sm-7"><?php 
                    $doc_comission = array('1'=>'Commission', '2'=>'Transaction');
                    echo $doc_comission[$form_data['doctor_pay_type']]; 
                   ?></div>
                  </div>

                  
                    <?php
                      if($form_data['doctor_pay_type']==1)
                      {
                       ?> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Share Details </label></div>
                    <div class="col-sm-7"><a href="javascript:void(0)" class="btn-commission" onclick="comission(<?php echo $form_data['id']; ?>);"><i class="fa fa-cog"></i> Commission</a></div>
                  </div> 
                     <div class="row m-b-5">
                    <div class="col-sm-5"><label>Doctor Type </label></div>
                    <div class="col-sm-7">
                      <?php 
                      //$doc_comission = array('1'=>'Commission', '2'=>'Transaction');
                      $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                      echo $doctor_types = $doctor_type[$form_data['doctor_type']]; 
                     ?>
                   </div>
                  </div>
 <?php
                      }
                      else if($form_data['doctor_pay_type']==2)
                      {
                      ?>
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>Rate List </label></div>
                    <div class="col-sm-7"><?php echo $form_data['rate_plan']; ?></div>
                  </div>
                  <?php
                    }
                    ?> 
<div class="row m-b-5">
                   <!-- <div class="col-sm-5"><label>Type </label></div>
                    <div class="col-sm-7">
                       <?php 
                        $doc_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                        echo $doc_type[$form_data['doctor_type']]; 
                       ?>
                    </div>
                  -->
 </div>

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Marketing Person  </label></div>
                    <div class="col-sm-7">
                      <?php 
                      //$doc_comission = array('1'=>'Commission', '2'=>'Transaction');
                      ////$doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                      echo $form_data['marketing_person']; //$doctor_types = $doctor_type[$form_data['doctor_type']];; 
                     ?>
                   </div>
                  </div>

                 

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Consultation Charge </label></div>
                    <div class="col-sm-7"><?php echo $form_data['consultant_charge']; ?></div>
                  </div>

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label><?php echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');  ?> </label></div>
                    <div class="col-sm-7"><?php echo $form_data['emergency_charge']; ?></div>
                  </div>

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>DOB </label></div>
                    <div class="col-sm-7"><?php if(isset($form_data['dob']) && $form_data['dob']!='1970-01-01'){ echo date('d-m-Y',strtotime($form_data['dob']));}else{echo '';}?></div>
                  </div>

                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Anniversary </label></div>
                    <div class="col-sm-7"><?php if(isset($form_data['anniversary']) && $form_data['dob']!='1970-01-01'){ echo date('d-m-Y',strtotime($form_data['anniversary'])); } else {echo '';}?></div>
                  </div>
                    <div class="row">
                    <div class="col-sm-5"><label> Status </label></div>
                    <div class="col-sm-7"><?php if($form_data['status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?></div>
                  </div>

                   <div class="row">
                    <div class="col-sm-5"><label> Sign Image </label></div>
                     <div class="col-sm-7">
                 <?php 

                   if(!empty($form_data['signature']) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$form_data['signature']))
                    {
                        $sign_img = ROOT_UPLOADS_PATH.'doctor_signature/'.$form_data['signature'];
                        ?>
                       
                      <?php   echo '<img src="'.$sign_img.'" width="100px" />';
                    }
                    ?>
                    </div>
                    </div>  
                 
                  
               
                  
                   
                   

              
              </div> <!-- Left main6 -->

              <div class="col-sm-6">
                
                  <!--<div class="row m-b-5">
                    <div class="col-sm-5"><label>Type </label></div>
                    <div class="col-sm-7">
                       <?php 
                        $doc_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
                        echo $doc_type[$form_data['doctor_type']]; 
                       ?>
                    </div>
                  </div> -->
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>Address </label></div>
                    <div class="col-sm-7"><?php echo $form_data['address']; ?></div>
                  </div> 
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>Country </label></div>
                    <div class="col-sm-7"><?php echo ucfirst(strtolower($form_data['country'])); ?> </div>
                  </div> 
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>State </label></div>
                    <div class="col-sm-7"><?php echo ucfirst(strtolower($form_data['state'])); ?> </div>
                  </div> 
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>City </label></div>
                    <div class="col-sm-7"><?php echo ucfirst(strtolower($form_data['city'])); ?> </div>
                  </div> 
                    <div class="row m-b-5">
                    <div class="col-sm-5"><label>PIN Code </label></div>
                    <div class="col-sm-7"><?php echo $form_data['pincode']; ?></div>
                  </div>
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Email </label></div>
                    <div class="col-sm-7"><?php echo $form_data['email']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Alt. Mobile No. </label></div>
                    <div class="col-sm-7"><?php echo $form_data['alt_mobile_no']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Landline No.</label></div>
                    <div class="col-sm-7"><?php echo $form_data['landline_no']; ?></div>
                  </div> 
                   <div class="row m-b-5">
                    <div class="col-sm-5"><label>PAN No. </label></div>
                    <div class="col-sm-7"><?php echo $form_data['pan_no']; ?> </div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Reg. No.</label></div>
                    <div class="col-sm-7"><?php echo $form_data['doc_reg_no']; ?></div>
                  </div> 

                   

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
                         $doctor_availablity_time = get_doctor_schedule_time($key,$form_data['id']);
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
<script>
function comission(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'doctors/view_comission/' ?>',
  {
    //'id1': '1',
    'id': ids
    },
  function(){
  $modal.modal('show');
  });
} 
</script>        
<div id="load_add_comission_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>     
</div><!-- /.modal-dialog -->