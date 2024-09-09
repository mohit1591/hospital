 <div class="modal-dialog" style="">
    <div class="overlay-loader" id="overlay-loader-comission">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="comission_form_test" class="form-inline"> 
      
                <div class="modal-header">
                    <button type="button" class="close" data-number="2" aria-label="Close" onclick="transaction_modal();"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body"> 

       <span>Transactional: This is as a transfer Price for any Doctor who'll recieve the direct payment from Patients & pay the Lab later. </span>
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
                <button type="button" class="btn-cancel" data-number="2" onclick="transaction_modal();">Close</button>
            </div>
    </form>     

</div>  
</div><!-- /.modal-dialog -->