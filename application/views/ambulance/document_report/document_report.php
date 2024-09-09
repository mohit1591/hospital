<?php
$users_data = $this->session->userdata('auth_users');

?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="new_relation" class="form-inline">
 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                  
                    <!--<div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Created Date:</b></label>
                         </div>
                         <div class="col-md-4">
                              <input type="text" id="create_from"  name="create_from" class="w-100px" placeholder="From">
                         </div>
                         <div class="col-md-4">
                              <input type="text" name="create_to" id="create_to" class="w-100px"placeholder="To">
                         </div>
                    </div>-->

                     <div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Renewal Date:</b></label>
                         </div>
                         <div class="col-md-4">
                              <input type="text" id="renewal_from" name="renewal_from"  class="w-100px" placeholder="From">
                         </div>
                         <div class="col-md-4">
                              <input type="text" name="renewal_to" id="renewal_to" class="w-100px" placeholder="To">
                         </div>
                    </div>

                     <div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Expiry Date:</b></label>
                         </div>
                         <div class="col-md-4">
                              <input type="text" id="exp_from"  name="exp_from" class="w-100px" placeholder="From">
                         </div>
                         <div class="col-md-4">
                              <input type="text" name="exp_to" id="exp_to" class="w-100px" placeholder="To">
                         </div>
                    </div>

                     <div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Vehicle :</b></label>
                         </div>
                         <div class="col-md-8">
                              <select name="vehicle_id" id="vehicle_id" class="m_select_btn">
                                   <option value="">All</option>
                                    <?php
                                    if(!empty($vehicle_list))
                                    {
                                      foreach($vehicle_list as $list)
                                      {
                                        ?>
                                          <option value="<?php echo $list->id; ?>"><?php echo $list->vehicle_no; ?></option>
                                        <?php
                                      }
                                    }
                                    ?>
                              </select> 
                         </div>
                    </div>

                     <div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Document :</b></label>
                         </div>
                         <div class="col-md-8">
                              <select name="document_id" id="document_id" class="m_select_btn">
                                    <option value="">All</option>
                                    <?php
                                    if(!empty($document_list))
                                    {
                                      foreach($document_list as $list)
                                      {
                                        ?>
                                          <option value="<?php echo $list->id; ?>"><?php echo $list->document; ?></option>                                      
                                        <?php
                                      }
                                    }
                                    ?>
                              </select> 
                         </div>
                    </div>
                    
                     <div class="row m-b-5">
                         <div class="col-md-4">
                              <label><b>Remark :</b></label>
                         </div>
                         <div class="col-md-8">
                             <input type="text" name="remark" id="remark">
                         </div>
                    </div>


               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:80%;display:inline-flex;">
                <button type="button" class="btn-update" onclick="referral_report_new();" name="submit">Export</button>
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">
// create
$('#create_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#create_from').val();
      $('#create_to').datepicker('setStartDate', start_data);
       
  });
  $('#create_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });    

  // renewal
  $('#renewal_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#renewal_from').val();
      $('#renewal_to').datepicker('setStartDate', start_data);
       
  });
  $('#renewal_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });  
  // expiry
  $('#exp_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('#exp_from').val();
      $('#exp_to').datepicker('setStartDate', start_data);
       
  });
  $('#exp_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });  

function referral_report_new()
{   
  var vehicle_id = $('#vehicle_id').val();
  var document_id = $('#document_id').val(); 
  var create_from = $('#create_from').val();
  var create_to = $('#create_to').val();
  var renewal_from = $('#renewal_from').val();
  var renewal_to = $('#renewal_to').val();
  var exp_from = $('#exp_from').val();
  var exp_to = $('#exp_to').val();
  var remark = $('#remark').val();
   window.open('<?php echo base_url('ambulance/amb_document_report/get_document_data?') ?>vehicle_id='+vehicle_id+'&document_id='+document_id+'&create_from='+create_from+'&create_to='+create_to+'&renewal_from='+renewal_from+'&renewal_to='+renewal_to+'&exp_from='+exp_from+'&exp_to='+exp_to+'&remark='+remark,'mywin','width=800,height=600');
}  

 $("button[data-number=1]").click(function(){
    $('#load_add_referral_report_modal_popup').modal('hide');
});
</script>

</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
