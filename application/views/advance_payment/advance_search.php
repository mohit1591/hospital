<style>

.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form_ot" class="form-inline"> 
      
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
                          <input type="text" class="datepicker" name="start_date" id="start_date_ot" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        
                        
                        <div class="grp">
                          <label>Patient Name</label>
                        
                              <!-- <select name="simulation_id" id="simulation_id" class="mr">
                                  <option value="">Select</option>
                                  <?php
                                  if(!empty($simulation_list))
                                  {
                                    foreach($simulation_list as $simulation)
                                    {
                                      $selected_simulation = '';
                                      if($simulation->id==$form_data['simulation_id'])
                                      {
                                         $selected_simulation = 'selected="selected"';
                                      }
                                      echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                                    }
                                  }
                                  ?> 
                                </select> -->
                              <input type="text" name="patient_name" id="patient_name" value="<?php echo $form_data['patient_name']; ?>" class="p-name searchFocus">
                         
                        </div>
                        
                     

                        <div class="grp">
                          <label>IPD No. </label>
                          <input type="text" name="ipd_no" value="<?php echo $form_data['ipd_no']; ?>" onkeypress="return isNumberKey(event);">
                        </div>

                       <div class="grp">
                          <label><?php echo $data= get_setting_value('PATIENT_REG_NO');?> </label>
                          <input type="text" name="patient_code" value="<?php echo $form_data['patient_code']; ?>" onkeypress="return isNumberKey(event);">
                        </div>
                        
                      
                        
                        
                        <!--<div class="grp">
                          <label>Operation Time</label>
                          <input type="text" name="operation_time" id="operation_time" value="<?php echo $form_data['operation_time']; ?>" >
                        </div>

                      <div class="grp">
                          <label>Operation Date</label>
                          <input type="text" name="operation_date" id="operation_date" value="<?php echo $form_data['operation_date']; ?>" >
                        </div>-->
                       
                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date_to">
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
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>ot_booking/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}
 
$("#search_form_ot").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('ot_booking/advance_search/'); ?>",
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

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 
 $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true
  })

  var today =new Date();
  $('#start_date_ot').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date_ot").datepicker("option", "minDate", selected);
        }
  });
 $('#end_date_ot').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date_ot").datepicker("option", "maxDate", selected);
        }
  });


</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->