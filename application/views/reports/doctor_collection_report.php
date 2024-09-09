<?php
$users_data = $this->session->userdata('auth_users');

?>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link href="<?php echo ROOT_CSS_PATH; ?>bootstrap-multiselect.css" rel="stylesheet" />
<script src="<?php echo ROOT_JS_PATH; ?>bootstrap-multiselect.min.js"></script>

<style>
  .multiselect-native-select .btn-group {
    width: 100%!important;
  }
</style>

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
                              <input type="text" id="exp_start_date" name="from_date" class="datepicker start_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_date" id="exp_end_date" class="datepicker datepicker_to end_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    
                      <div class="row m-t-5">
                         <div class="col-md-5">      
                              <label><b>Department:</b></label>
                         </div>
                         <div class="col-md-7">
                               <select style="width: 100%;" name="department" id="departments" multiple>
                                  <option value="">All</option>
                                    <?php  foreach($department as $key=>$dept){?>
                                  <option value="<?php echo $key;  ?>"><?php echo $dept;?></option>
                                     <?php }?>
                                </select>
                         </div>
                    </div>

                    <div class="row m-t-5">
                      <div class="col-md-5">
                         <label>Doctor</label>
                      </div>
                      <div class="col-md-7">
                        <input type="text" id="doc_name_0"  name="doc_name_0" onkeyup="return search_referal_doc('0');"  placeholder="Doctor" autocomplete="off">
                          <div class="append_box_doc_0 advs_append_box">
                          </div>
                      </div>
                    </div>
                    <input type="hidden" id="doct_id_0">
                         
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
       <!-- <input type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();"> -->
         <?php if(in_array('252',$users_data['permission']['action'])) {
         ?>
              <input type="button"  class="btn-update" onClick="return expenses_report()" name="submit" value="Print" />
          <?php } ?>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script type="text/javascript">
$('select[multiple]').multiselect();
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
<?php $branch_id_n =  $this->session->userdata('sub_branches_data'); $users_data = $this->session->userdata('auth_users');  ?>
function expenses_report()
{ 
    var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }
     var branch_id = <?php echo $users_data['parent_id']; ?>;//$('#sub_branch_id').val();
     var start_date = $('#exp_start_date').val();
     var end_date = $('#exp_end_date').val();
     var doct_id = $('#doct_id_0').val();
     window.open('<?php echo base_url('reports/print_doctor_collection_reports?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date+'&doc_id='+doct_id+'&dept='+xs,'mywin','width=800,height=600');
}  


$(document).ready(function(){
     $.post('<?php echo base_url('reports/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
 });
 var count = 0;
 
 function reset_search_value(){
     $("#print_opt").val('');
     
 }
 function reset_date_search(){
     $("#exp_start_date").val('');
     $("#exp_end_date").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_collection_modal_popup').modal('hide');
});


  function search_referal_doc()
  {       
  var doc_name = $('#doc_name_0').val();
    $.ajax({
         type: "POST",
         url: "<?php echo base_url('eye/add_eye_prescription/ajax_list_attended_doc')?>",
         data: {'doc_name' : doc_name},
         dataType: "json",
         success: function(msg){
          $(".append_row_refer").remove();
          $(".append_box_doc_0").show().html(msg.data);
          $('.append_row_refer').click(function(){
        $('#doc_name_0').val($(this).text());
        $('#doct_id_0').val($(this).attr('data-type'));
        $(".append_box_doc_0").hide();
      });
         }
      });
  }



</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>