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
  <form  id="relation" class="form-inline">

      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="exp_start_date" name="from_date" class="datepicker start_datepicker" value="<?php echo $form_data['from_date']; ?>">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_date" id="exp_end_date" class="datepicker datepicker_to end_datepicker" value="<?php echo $form_data['to_date']; ?>">
                         </div>
                    </div>

                     <div class="row m-t-5">
                         <div class="col-md-5">      
                              <label><b>Module:</b></label>
                         </div>
                         <div class="col-md-7">
                            <input type="radio" checked name="vendor_type" class="vendor_type" value="1"> Ambulance
                           <!-- <input type="radio" name="vendor_type" class="vendor_type" value="2"> Vaccination
                            <input type="radio" name="vendor_type" class="vendor_type" value="3"> Inventory-->
                         </div>
                    </div>
                         
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
              <input type="button"  class="btn-update" onClick="return ledger_report()" name="submit" value="Print" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script type="text/javascript">

$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      var start_date=  $("#exp_start_date").val();
    var end_date=  $("#exp_end_date").val();
    $("#exp_end_date").datepicker('setStartDate',start_date); 
    $("#exp_end_date").datepicker({ minDate: selectedDate });
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     var start_date=  $("#exp_start_date").val();
    var end_date=  $("#exp_end_date").val();
    $("#exp_end_date").datepicker('setStartDate',start_date); 
    $("#exp_end_date").datepicker({ minDate: selectedDate });
  }); 
function ledger_report()
{ 
     var cat_id = $('input[name="vendor_type"]:checked').val();
     var branch_id = <?php echo $users_data['parent_id']; ?>;//$('#sub_branch_id').val();
     var start_date = $('#exp_start_date').val();
     var end_date = $('#exp_end_date').val();
     var vendor_id = '<?php echo $vendor_id; ?>';
    
   window.open('<?php echo base_url('ambulance/vendor_payment/ledger_print?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date+'&type='+cat_id+'&v_id='+vendor_id,'mywin','width=800,height=600');
}  

 function reset_date_search(){
     $("#exp_start_date").val('');
     $("#exp_end_date").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_eye_app_type').modal('hide');
});
</script>
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_eye_app_type" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>