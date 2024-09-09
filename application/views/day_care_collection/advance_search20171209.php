 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">

                        <?php  
                $users_data = $this->session->userdata('auth_users'); 

                if (array_key_exists("permission",$users_data)){
                     $permission_section = $users_data['permission']['section'];
                     $permission_action = $users_data['permission']['action'];
                }
                else{
                     $permission_section = array();
                     $permission_action = array();
                }
              //print_r($permission_action);

            $new_branch_data=array();
           $users_data = $this->session->userdata('auth_users');
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            $parent_branch_details = $this->session->userdata('parent_branches_data');
            
             
             if(!empty($users_data['parent_id'])){
            $new_branch_data['id']=$users_data['parent_id'];
            
            $users_new_data[]=$new_branch_data;
            $merg_branch= array_merge($users_new_data,$sub_branch_details);
          
            $ids = array_column($merg_branch, 'id'); 
            $branch_id = implode(',', $ids); 
            $option= '<option value="'.$branch_id.'">All</option>';
            }

             ?>
             <?php if(in_array('1',$permission_section)){ ?> 
         <div class="grp">
          <label>Branch</label>
              
       
       
            <select name="branch_id" id="branch_id">
            <?php echo $option ;?>
            <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
            <?php 
            if(!empty($sub_branch_details)){
            $i=0;
            foreach($sub_branch_details as $key=>$value){
            ?>
            <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
            <?php 
            $i = $i+1;
            }

            }
            ?> 
            </select>
          

          </div>

<?php } else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } ?>


                        <?php /* <div class="grp">
                          <label>Branches</label>
                             
                               <?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
                            <select name="branch_id" id="branch_id">
                               <option value="">Select Branch</option>
                               <option  selected="selected" <?php if($form_data['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
                                 <?php 
                                 if(!empty($sub_branch_details)){
                                     $i=0;
                                    foreach($sub_branch_details as $key=>$value){
                                         ?>
                                         <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if($form_data['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                                         <?php 
                                         $i = $i+1;
                                     }
                                   
                                 }
                                ?> 
                                </select>
                             </div> */ ?>



                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker start_date_bill" name="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        <div class="grp">
                          <label>Referred By</label>
                          <select name="referral_doctor" id="referral_doctor">
                          <option value="">Select Referred By</option>
                          <?php
                          if(!empty($referal_doctor_list))
                          {
                            foreach($referal_doctor_list as $referal_doctor)
                            {
                              ?>
                                <option <?php if($form_data['referral_doctor']==$referal_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                              <?php
                            }
                          }
                          ?>
                        </select>
                        </div>
                        
                        

                        <div class="grp">
                          <label>Patient Reg. No.</label>
                          <input type="text" name="patient_code" id="patient_code" value="<?php echo $form_data['patient_code']; ?>" />
                        </div>

                        

                        <div class="grp">
                          <label>Mobile No. </label>
                          <input type="text" maxlength="10" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div> 

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker end_date_bill" value="<?php echo $form_data['end_date']; ?>">
                        </div>
                          
                        <div class="grp">
                          <label>Attendent Doctor</label>
                          <select name="attended_doctor" id="attended_doctor">
                            <option value="">Select Attended By</option>
                            <?php
                            if(!empty($attended_doctor_list))
                            {
                              foreach($attended_doctor_list as $attended_doctor)
                              { 
                                ?>
                                  <option <?php if($form_data['attended_doctor']==$attended_doctor->id){ echo 'selected="selected"'; } ?> value="<?php echo $attended_doctor->id; ?>"><?php echo $attended_doctor->doctor_name; ?></option>
                                <?php
                              }
                            }
                            ?>
                          </select>
                        </div>
                        
                        <div class="grp">
                          <label>Patient Name</label>
                          <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" />
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

function reset_search()
    {
      
      $.ajax({url: "<?php echo base_url(); ?>opd_collection_report/reset_date_search/", 
        success: function(result)
        {
          $("#search_form").reset();
          reload_table();
        } 
      }); 
    }    
    
  $("#search_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('#overlay-loader').show();
     
    $.ajax({
      url: "<?php echo base_url('opd_collection_report/advance_search/'); ?>",
      type: "post",
      data: $(this).serialize(),
      success: function(result) 
      {
        $('#load_advance_search_modal_popup').modal('hide'); 
        reload_table();        
      }
    });
  }); 

  $('.start_date_bill').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_date_bill').val();
      $('.end_date_bill').datepicker('setStartDate', start_data); 
  });

  $('.end_date_bill').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  }); 

  /*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true
  });*/

   
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->