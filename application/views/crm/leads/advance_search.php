 <?php  $users_data = $this->session->userdata('auth_users'); ?>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="advance_search_form" class="form-inline"> 
      
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
                          <label>From Follow-up</label>
                          <input type="text" class="datepicker start_datepicker" name="from_followup" value="<?php echo $form_data['from_followup']; ?>" id="" >
                        </div>

                        <div class="grp">
                          <label>Lead ID</label> 
                          <input type="text" class="" name="lead_id" value="<?php echo $form_data['lead_id']; ?>"> 
                        </div> 

                        <div class="grp">
                          <label>Source</label> 
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
                          <label>Name</label> 
                          <input type="text" class="" name="name" value="<?php echo $form_data['name']; ?>" > 
                        </div> 


                        <div class="grp">
                          <label>Phone</label> 
                          <input type="text" maxlength="10" class="" name="phone" value="<?php echo $form_data['phone']; ?>" > 
                        </div> 

                        <div class="grp">
                          <label>Age</label> 
                          <input type="text" maxlength="10" class="" name="age" value="<?php echo $form_data['age']; ?>" > 
                        </div>        

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>" id="">
                        </div> 
                        <div class="grp">
                          <label>To Follow-up</label>
                          <input type="text" name="to_followup" class="end_datepicker" value="<?php echo $form_data['to_followup']; ?>" id="">
                        </div>   
                        <div class="grp">
                          <label>Lead Type</label> 
                          <select name="lead_type_id" id="lead_type_id" class="" onchange="return set_lead_type(this.value)">
                            <option value="">Select Lead Source</option>
                            <?php
                             if(!empty($lead_type_list))
                             {
                                foreach($lead_type_list as $lead_type_id)
                                {
                                    $lead_type_select = '';
                                    if($form_data['lead_type_id']==$lead_type_id->id)
                                    {
                                        $lead_type_select = 'selected="selected"';
                                    }    
                                        echo '<option '.$lead_type_select.' value="'.$lead_type_id->id.'">'.$lead_type_id->lead_type.'</option>';
                                    
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
                             if(!empty($department_list))
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
                             }
                            ?>
                        </select>
                        </div> 

                        <div class="grp">
                          <label>Call Status</label> 
                          <select class="" name="call_status">
                        <option value="">Select Status</option>
                        <?php
                        if(!empty($call_status))
                        {
                            foreach($call_status as $status)
                            {
                                $status_select = '';
                                if($form_data['call_status']==$status['id'])
                                {
                                    $status_select = 'selected="selected"';
                                }
                                echo '<option '.$status_select.' value="'.$status['id'].'">'.$status['call_status'].'</option>';
                            }
                        }
                        ?>
                    </select>
                        </div> 

                        <div class="grp">
                          <label>Email</label> 
                          <input type="text" class="" name="email" value="<?php echo $form_data['email']; ?>" >
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
 
function reset_search(ele)
{

   $.ajax({url: "<?php echo base_url(); ?>patient/reset_search/", 
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

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#advance_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('crm/leads/set_advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_disease_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
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

$(document).ready(function() {
  $('#load_add_disease_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->