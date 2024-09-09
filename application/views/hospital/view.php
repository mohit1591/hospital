
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
                    <div class="col-sm-5"><label>Hospital code</label></div>
                    <div class="col-sm-7"><?php echo $form_data['hospital_code']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Hospital Name </label></div>
                    <div class="col-sm-7"><?php echo $form_data['hospital_name']; ?></div>
                  </div> 
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label> Mobile No. </label></div>
                    <div class="col-sm-7"><?php echo $form_data['mobile_no']; ?></div>
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
                    <div class="col-sm-5"><label>Share Details </label></div>
                    <div class="col-sm-7"><a href="javascript:void(0)" class="btn-commission" onclick="comission(<?php echo $form_data['id']; ?>);"><i class="fa fa-cog"></i> Commission</a></div>
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

                 
             </div> <!-- Left main6 -->

              <div class="col-sm-6">
                
                 
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
                  
                    <div class="row">
                    <div class="col-sm-5"><label> Status </label></div>
                    <div class="col-sm-7"><?php if($form_data['status']==1){ echo 'Active'; }else{ echo 'Inactive'; } ?></div>
                  </div>  
                  
                 
                 
                   
                  
                  <div class="row m-b-5">
                    <div class="col-sm-5"><label>Create date</label></div>
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
  var $modal = $('#load_add_hospital_comission_modal_popup');
  $modal.load('<?php echo base_url().'hospital/view_hospital_comission/' ?>',
  {
    //'id1': '1',
    'id': ids
    },
  function(){
  $modal.modal('show');
  });
} 
</script>        
    
</div><!-- /.modal-dialog -->
<div id="load_add_hospital_comission_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div> 