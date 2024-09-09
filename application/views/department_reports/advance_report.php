 <?php $users_data = $this->session->userdata('auth_users');?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form target="_blank" id="search_form" action="<?php echo base_url('pathology_reports/advance_report_generate'); ?>" method="post" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
                </div> 
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
    <div class="advance-search">
        <div class="row mb-5">
                        
            <div class="col-md-6 adv_report_height">
              <div class="row">
                <div class="col-md-5">
                <label>From Date</label>
                </div>              
                <div class="col-md-5">
                <input type="text" class="datepicker" name="start_date" value="<?php //echo $form_data['start_date']; ?>">
                </div>
              </div>
            </div>

            <div class="col-md-6 adv_report_height">
            <div class="row mb-5">
              <div class="col-md-5">
                <label>To Date</label>
              </div>
              <div class="col-md-7">
                <input type="text" name="end_date" class="datepicker" value="<?php //echo $form_data['end_date']; ?>">
              </div>                          
            </div>
          </div>
                        
          <div class="col-md-6 adv_report_height">
            <div class="row mb-5">
              <div class="col-md-5">
                <label>Report Type</label>
              </div>
              <div class="col-md-7">
                <select name="report_type" onchange="return change_collection_group(this.value);">
                   <option value="1">Summarize Report</option>
                   <option value="2">Detailed Report</option>
                   <option value="3">Date Wise Report</option>
                </select>
              </div>
            </div> 
          </div>
                         
                              
 
          
 
          <div class="col-md-6 adv_report_height collection_group" style="display: none;">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Collection Group</label>
                </div>
                <div class="col-md-7">
                <select name="collection_group"  onchange="multishow(this.value);" >
                   <option value="">Select Collection Group</option>
                   <option value="1">Department Wise</option> 
                   <option value="2">Test Wise</option>
                   <option value="3">Users Wise</option>
                   <option value="4">Attendant Doctor Wise</option>
                   <option value="5">Referred Doctor Wise</option>
                   <option value="7">Payment Mode Wise</option>
                </select>
                </div>
              </div>
            </div> 

            <div class="col-md-6 adv_report_height" id="dept_list">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Department</label>
                </div>
                <div class="col-md-7">
                <select name="dept_id[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($department_list))
                   {
                     foreach($department_list as $dept)
                     {
                      ?>
                         <option selected="selected" value="<?php echo $dept->id; ?>"><?php echo $dept->department; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div> 
          <?php if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
          { ?>
            <div class="col-md-6 adv_report_height" id="user_list">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Users</label>
                </div>
                <div class="col-md-7">
                <select name="users_id[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($users_list))
                   {
                     foreach($users_list as $users)
                     {
                       if(!empty($users->emp_name))
                       {
                      ?>
                         <option selected="selected" value="<?php echo $users->id; ?>"><?php echo $users->emp_name; ?></option> 
                      <?php  
                       }
                    } 
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div>
            <?php 
              } 
              else 
              { ?>
              <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

            <?php }?>    


            <div class="col-md-6 adv_report_height" id="ref_doc_list">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Referral Doctors</label>
                </div>
                <div class="col-md-7">
                <select name="referral_doctor[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($referral_doctors_list))
                   {
                     foreach($referral_doctors_list as $referral_doctors)
                     {
                      ?>
                         <option selected="selected" value="<?php echo $referral_doctors->id; ?>"><?php echo $referral_doctors->doctor_name; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 adv_report_height" id="att_doc_list">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Attended Doctors</label>
                </div>
                <div class="col-md-7">
                <select name="attended_doctor[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($attended_doctors_list))
                   {
                     foreach($attended_doctors_list as $attended_doctors)
                     {
                      ?>
                         <option selected="selected" value="<?php echo $attended_doctors->id; ?>"><?php echo $attended_doctors->doctor_name; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div>

            <div class="col-md-6 adv_report_height" id="pay_mode_list">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Payment Mode</label>
                </div>
                <div class="col-md-7">
                <select name="payment_mode[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($payment_mode_list))
                   {
                     foreach($payment_mode_list as $payment_mode)
                     {
                      ?>
                         <option selected="selected" value="<?php echo $payment_mode->id; ?>"><?php echo $payment_mode->payment_mode; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div> 

            <?php $test_master_list  = test_master_list(); ?>

             <div class="col-md-6 adv_report_height" style="display: none;">
            <div class="row mb-5">
                <div class="col-md-5">
                <label>Test</label>
                </div>
                <div class="col-md-7">
                <select name="test_id[]" class="multi_type" multiple="multiple">
                  <?php
                   if(!empty($test_master_list))
                   {
                     foreach($test_master_list as $test_master)
                     {
                      ?>
                         <option selected="selected" value="<?php echo $test_master->id; ?>"><?php echo $test_master->test_name; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div>
            </div>
                            

        </div>  <!-- row -->
      </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Generate" />
                <!--<input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />-->
                 <!-- <input value="Reset" onclick="clear_form_elements(this.form)" type="button" class="btn-reset"> -->
                 <input value="Set Default" onclick="set_default(this.form)" type="button" class="btn-reset">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 
function change_collection_group(val) 
{   
  if(val==1)
  {
    $(".collection_group").hide();
    multishow('all');
  } 
  if(val==2)
  {
    $(".collection_group").show();
    multishow(0);
  }

  if(val==3)
  {
    $(".collection_group").hide();
     
  }
  
} 


function multishow(sel) 
{
  if(sel=='all')
  {
    $("#dept_list").show();
    $("#test_list").show();
    $("#user_list").show();
    $("#att_doc_list").show();
    $("#ref_doc_list").show();
    $("#pay_mode_list").show();
  }
  else if(sel=='1')
  {
    $("#dept_list").show();
    $("#test_list").hide();
    $("#user_list").hide();
    $("#att_doc_list").hide();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").hide();
  }
  else if(sel=='2')
  {
    $("#dept_list").hide();
    $("#test_list").show();
    $("#user_list").hide();
    $("#att_doc_list").hide();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").hide();
  }
  else if(sel=='3')
  {
    $("#dept_list").hide();
    $("#test_list").hide();
    $("#user_list").show();
    $("#att_doc_list").hide();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").hide();
  }
  else if(sel=='4')
  {
    $("#dept_list").hide();
    $("#test_list").hide();
    $("#user_list").hide();
    $("#att_doc_list").show();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").hide();
  }
  else if(sel=='5')
  {
    $("#dept_list").hide();
    $("#test_list").hide();
    $("#user_list").hide();
    $("#att_doc_list").hide();
    $("#ref_doc_list").show();
    $("#pay_mode_list").hide();
  }
  else if(sel=='7')
  {
    $("#dept_list").hide();
    $("#test_list").hide();
    $("#user_list").hide();
    $("#att_doc_list").hide();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").show();
  }
  else 
  {
    $("#dept_list").hide();
    $("#test_list").hide();
    $("#user_list").hide();
    $("#att_doc_list").hide();
    $("#ref_doc_list").hide();
    $("#pay_mode_list").hide();
  }
}

    
/*function multishow(sel) {
  var opts = [],opt;
  var len = sel.options.length;

  for (var i = 0; i < len; i++) 
  {
    opt = sel.options[i];
    if (opt.selected) 
    { 

      opts.push(opt);
      
      if(opt.value==1)
      {
        $("#dep_list").show();
      }
      if(opt.value==2)
      {
        $("#test_list").show();
      }
      
      if(opt.value==3)
      {
        $("#user_list").show();
      }
      if(opt.value==4)
      { 
        $("#attended_doc_list").show();
      }
      if(opt.value==5)
      {
        $("#referred_doc_list").show();
      }
      if(opt.value==7)
      {
        $("#payment_list").show();
      }
      
      
    }

    
   // alert(opt.value);
    
    //alert(opt.value);

  }


  if ($("#multi_select").find('option').not(':selected').length > 0) 
    { 
      var notSelected = $("#multi_select").find('option').not(':selected'); 
      var unselectedgltype = notSelected.map(function () { 
        //alert(this.value);
        if(this.value==1)
        {
          $("#dep_list").hide();
        }
        if(this.value==2)
        {
          $("#test_list").hide();
        }
        
        if(this.value==3)
        {
          $("#user_list").hide();
        }
        if(this.value==4)
        {
          $("#attended_doc_list").hide();
        }
        if(this.value==5)
        {
          $("#referred_doc_list").hide();
        } 
        if(this.value==7)
        {
          $("#payment_list").hide();
        } 
      }).get(); }

  

  
}*/
$(document).ready(function() 
{
  $("input[name$='referred_by']").click(function() 
  {
    var test2 = $(this).val();
    if(test2==0)
    {
      $("#hospital_div_1").hide();
      $("#doctor_div_1").show();
      $('#referral_hospital_1').val('');
      
    }
    else if(test2==1)
    {
        
        $("#doctor_div_1").hide();
        $("#hospital_div_1").show();
        $('#refered_id_1').val('');
        //$("#refered_id :selected").val('');
    }
      
  });
}); 
    function reset_search()
    {
      
      $.ajax({url: "<?php echo base_url(); ?>pathology_reports/reset_date_search/", 
        success: function(result)
        {
          $("#search_form").reset();
          reload_table();
        } 
      }); 
    }  
 

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true
  });

   $(document).ready(function() {
        $('.multi_type').multiselect({
          'includeSelectAllOption':true,
          'maxHeight':200,
          'enableFiltering':true
        });
    });
   /*$('#multi_type').multiselect({ 
          includeSelectAllOption: true,  
          dropUp: true 
      });*/
function set_default()
{      
  var msg = 'Report search default set successfully.';
  var path = 'default_search_data/';
  $.ajax({
    url: "<?php echo base_url('pathology_report_default/'); ?>"+path,
    type: "post",
    data: $("#search_form").serialize(),
    success: function(result) {
      if(result==1)
      {
        flash_session_msg(msg);
        reload_table();
      } 
      
    }
  });

}
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->