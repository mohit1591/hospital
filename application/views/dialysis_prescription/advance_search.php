 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline"> 
      
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
                          <label>Booking From Date</label>
                          <input type="text" class="datepicker start_date_booking" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>

                                               


                          
                        <div class="grp">
                          <label> Branch </label>
                          <?php     $users_data = $this->session->userdata('auth_users');
                                    $sub_branch_details = $this->session->userdata('sub_branches_data');
                                    $parent_branch_details = $this->session->userdata('parent_branches_data');
                                    
                                ?>
                        <select name="branch_id" id="branch_id">
                           <option value="">Select Branch</option>
                           
                           <option  selected="selected" value="<?php echo $users_data['parent_id'];?>">Self</option>';
                             <?php 
                             if(!empty($sub_branch_details)){
                                 $i=0;
                                foreach($sub_branch_details as $key=>$value){
                                     ?>
                                     <option value="<?php echo $sub_branch_details[$i]['id'];?>"><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                                     <?php 
                                     $i = $i+1;
                                 }
                               
                             }
                            ?> 
                            </select>
                        </div>



                        <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
                          <input type="text" name="patient_code"  value="<?php echo $form_data['patient_code']; ?>">
                        </div>

                       
                        <div class="grp">
                          <label>Patient Name</label>
                          <div class="rslt">
                              
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name">
                          </div>
                        </div>
                        
                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                    
                       
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label> To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_booking" value="<?php echo $form_data['end_date']; ?>">
                        </div>
                       
                       

                    
                      

                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>  


 //ends



function reset_search()
{
  
  $.ajax({url: "<?php echo base_url(); ?>dialysis_prescription/reset_search/", 
    success: function(result)
    {
      $("#search_form").reset();
      reload_table();
    } 
  }); 
}


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('dialysis_prescription/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 


$('.start_date_booking').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_date_booking').val();
      $('.end_date_booking').datepicker('setStartDate', start_data); 
  });

  $('.end_date_booking').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });


  $('.start_date_appointment').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_date_appointment').val();
      $('.end_date_appointment').datepicker('setStartDate', start_data); 
  });

  $('.end_date_appointment').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });



 $('.datepicker3').datetimepicker({
      format: 'LT'
  });
</script> 

<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->