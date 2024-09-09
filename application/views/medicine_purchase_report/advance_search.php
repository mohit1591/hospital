 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="medicine_collection_search_form" class="form-inline"> 
      
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
                         
                         <?php /* ?>

                        <div class="row m-b-5">
                          <label class="col-md-4">Branches</label>
                          <div class="col-md-8">
                             
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
                                 </div>

                           </div>

                           <?php */ ?>

                       <div class="row m-b-5">
                          <label class="col-md-4">Vendor Code</label>
                          <div class="col-md-8">
                            <input type="text" name="vendor_id" id="vendor_id" value="<?php echo $form_data['vendor_id']; ?>" />
                          </div>  
                        </div>

                         <div class="row m-b-5">
                          <label class="col-md-4">Vendor Name</label>
                          <div class="col-md-8">
                            <input type="text" name="name" id="name" value="<?php echo $form_data['name']; ?>" />
                          </div>  
                        </div>


                         <div class="row m-b-5">
                          <label class="col-md-4">Invoice No</label>
                          <div class="col-md-8">
                            <input type="text" name="invoice_id" id="invoice_id" value="<?php echo $form_data['invoice_id']; ?>" />
                          </div>  
                        </div>

                       <?php /* ?> 
                        <div class="row m-b-5">
                          <label class="col-md-4">From Date</label>
                          <div class="col-md-8">
                            <input type="text" class="datepicker start_datepicker" name="from_date" value="<?php echo $form_data['from_date']; ?>">
                          </div>
                        </div>
                   
                        <div class="row m-b-5">
                          <label class="col-md-4">To Date</label>
                          <div class="col-md-8">
                            <input type="text" name="end_date" class="datepicker end_datepicker" value="<?php echo $form_data['end_date']; ?>">
                          </div>
                        </div> 
                          <?php */ ?>

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="row m-b-5">
                          <label class="col-md-4">Purchase No.</label>
                          <div class="col-md-8">
                          <input type="text" name="purchase_id" id="purchase_id" value="<?php echo $form_data['sale_no']; ?>" />
                          </div>
                        </div>

                        <div class="row m-b-5">
                          <label class="col-md-4">Mobile No. </label>
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="mobile" value="<?php echo $form_data['mobile']; ?>" onkeypress="return isNumberKey(event);">
                          </div> 
                        </div> 

                      <?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
                    <?php if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
                    { ?>    
                    <div class="row m-b-5">
                          <label class="col-md-4">User</label>
                          <div class="col-md-8">
                            <select name="employee" class="m_input_default" id="employee">
                                <option value="">Select User</option>
                                <?php 
                                  if(!empty($employee_list))
                                  {
                                    foreach($employee_list as $employee)
                                    {
                                      echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                                    }
                                  }
                                ?> 
                            </select>
                        </div>  
                        </div> 
                        <?php 
                        } 
                        else 
                        { ?>
                        <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

                    <?php } ?>
                        
                         <?php /* ?>
                        <div class="row m-b-5">
                          <label class="col-md-4">Mfg. Company</label>
                            <div class="col-md-8">
                               <input type="text" name="medicine_company" id="medicine_company" value="<?php echo $form_data['medicine_company']; ?>" />
                            </div>  
                        </div>  
                        <?php */ ?>
                           

                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> -->
               <input value="Reset" class="btn-reset" onclick="reset_search(this.form)" type="button">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 

function reset_search(ele)
{
  
  $.ajax({url: "<?php echo base_url(); ?>Medicine_purchase_report/reset_date_search/", 
    success: function(result)
    {
      $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        reload_table();
    } 
  }); 
} 

  $("#medicine_collection_search_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('#overlay-loader').show();
     
    $.ajax({
      url: "<?php echo base_url('medicine_purchase_report/advance_search/'); ?>",
      type: "post",
      data: $(this).serialize(),
      success: function(result) 
      {
        $('#load_advance_search_modal_popup').modal('hide'); 
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

   
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->