<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="inventory_data" class="form-inline">
       <!--  <input type="hidden" name="data_id" id="type_id" value="<?php //echo $form_data['data_id']; ?>" /> -->
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body" style="height: calc(25vh - 50px);
    overflow-y: auto;">

              <div class="row">
                <div class="col-md-12">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>"/>
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>"/>
               
                    <table class="table table-bordered table-striped medicine_allotment" id="medicine_list">
                         <thead class="bg-theme">
                              <tr>
                    <th align="center" width="40">S.NO.</th>
                                   <th>Name</th>
                                   <th>Comment</th>
                                   <th>Date</th>
                                  
                  
                              </tr>
                         </thead>
                         <tbody id="medicine">
                              <?php  echo $all_diagnose_list; ?>

                                    
                         </tbody>
                    </table>
                </div> <!-- 8 -->
              </div> <!-- Row -->
               
           
              </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
              <button type="button" class="btn-cancel" data-number="2" >Close</button>
            </div>
    </form>     

<script>   
$("button[data-number=2]").click(function(){
  <?php //$this->session->unset_userdata('alloted_medicine_ids'); ?>
    $('#load_add_inventory_modal_popup').modal('hide'); 
}); 

</script>  


</div><!-- /.modal-dialog -->