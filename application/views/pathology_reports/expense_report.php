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
 <!--  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />  -->
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
                              <input type="text" id="start_date_from" readonly="" name="from_date" class="exp_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_date" readonly="" id="end_date_to" class="exp_datepicker_to" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                         
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
       <a  class="btn-anchor" href="javascript:void(0);" id="reset_date" value="Reset" onClick="reset_date_search();">Reset</a>
         <?php if(in_array('252',$users_data['permission']['action'])){ ?>
         <a  class="btn-anchor"  href="javascript:void(0);" onClick="return expenses_report()" name="submit" value="Print">Print</a>
         <?php } ?>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script type="text/javascript">
  
  $('.exp_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.exp_datepicker').val();
      $('.exp_datepicker_to').datepicker('setStartDate', start_data);
       
  });

  $('.exp_datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });

function expenses_report()
{ 
     var branch_id = $('#sub_branch_id').val();
     var start_date = $('#start_date_from').val();
     var end_date = $('#end_date_to').val(); 
        
   window.open('<?php echo base_url('reports/print_path_expenses_reports?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date,'mywin','width=800,height=600');
}  


$(document).ready(function(){
     $.post('<?php echo base_url('reports/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
 });
 var count = 0;
 
 function reset_date_search(){
     $("#start_date").val('');
     $("#end_date").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_expense_collection_modal_popup').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>