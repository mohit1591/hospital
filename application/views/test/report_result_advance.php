<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog"> 
  <div class="overlay-loader" id="#loader-ajax">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
    <form  id="advance_result_from" class="form-inline">
    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id']; ?>" />
    <input type="hidden" name="test_id" id="test_id" value="<?php echo $form_data['test_id']; ?>" />
  
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
            <div class="modal-body">  
         
          <div class="row">
            <div class="col-md-12"> 

              <div class="row">
                <div class="col-md-12 m-b-5">
                    <div class="row">
                       
                      <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4">
                              <strong>Test Name </strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" readonly="" name="test_name" id="test_name" value="<?php echo $form_data['test_name']; ?>" />

                                    <?php if(!empty($form_error)){ echo form_error('test_name'); } ?>

                            </div>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Result </strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" <?php if($test_type_id==1){ echo 'readonly=""';} ?>  name="result" class="result-<?php echo $test_id; ?>" alt="<?php echo $test_id; ?>" onkeyup="check_result_range(this.value,<?php echo $test_id; ?>);" id="adv_result" value="<?php echo $form_data['result']; ?>" class="report_result" />

                                    <?php if(!empty($form_error)){ echo form_error('result'); } ?>
                            </div>
                          </div>
                      </div>

                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->
              
             


              <div class="row">
                <div class="col-md-12 m-b-5">
                    <div class="row">

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Range From </strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" readonly="" name="range_from" id="range_from" value="<?php echo $form_data['range_from']; ?>" />
                                  <?php if(!empty($form_error)){ echo form_error('range_from'); } ?>
                            </div>
                          </div>
                      </div> 

                      <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-4">
                              <strong>Range To </strong>
                            </div>
                            <div class="col-md-8">
                                  <input type="text" readonly="" name="range_to" id="range_to" value="<?php echo $form_data['range_to']; ?>" />
                                  <?php if(!empty($form_error)){ echo form_error('range_to'); } ?>
                            </div>
                          </div>
                      </div>  
                      
                      
                  </div> <!-- row -->
                </div>
              </div> <!-- // main row -->

               



            </div> <!-- 12 -->
          </div> <!-- row -->

          <div class="row">
            <div class="col-xs-2"></div>
            <div class="col-xs-10" id="share_input">
             <a href="javascript:void(0)" onclick="add_interpretation()" class="btn-commission"><i class="fa fa-plus"></i>  Add Interpretation</a>
            </div>
          </div>

          


      </div>    <!--  modal-body --> 
             
             
        <div class="modal-footer"> 
           <input type="submit"  class="btn-update" name="submit" value="Save" />
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div>
</form>     

<script> 

$("#advance_result_from").on("submit", function(event) { 
  event.preventDefault(); 
  $('#loader-ajax').show(); 
  
  $.ajax({
    url: '<?php echo base_url("test/advance_report_result/").$booking_id."/".$test_id; ?>',
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        var adv_result = $('#adv_result').val();
        $('#load_add_test_modal_popup').modal('hide');  
        $('#result-<?php echo $test_id ?>').val(adv_result);
        flash_session_msg('Advance result of test successfully added.');
      } 
      else
      {
        $("#load_add_test_modal_popup").html(result);
      }   
      $('#loader-ajax').hide();     
    }
  });
}); 

function add_interpretation()
{
  var $modal = $('#load_add_interpretation_modal_popup');
  $modal.load('<?php echo base_url().'test/add_interpretation/'.$form_data['booking_id'].'/'.$form_data['test_id']; ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}    
</script> 
        </div>
  
   
</div>

<div id="load_add_interpretation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>    