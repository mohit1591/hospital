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
               <?php /* <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>User</b></label>
                         </div>
                        <div class="col-md-7">
                         <select name="employee_name" id="employee_name" >
                            <option value="">Select User</option>
                            < ?php
                            if(!empty($employee_list))
                            {
                              foreach($employee_list as $employee)
                              {
                                $selected_country = "";
                                if($employee->id==$form_data['employee_name'])
                                {
                                  $selected_country = 'selected="selected"';
                                }
                                echo '<option value="'.$employee->id.'" '.$selected_country.'>'.$employee->name.'</option>';
                              }
                            }
                            ?> 
                          </select> 
         
                         </div>
                    </div> */ ?>
                      <div class="row m-b-2">
                         <div class="col-md-12">
                             <div id="child_sub_branch" class="patient_sub_branch"></div>
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" onchange="vaccination_kit_branch_report();" id="exp_start_date" name="from_date" class="datepicker start_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" onchange="vaccination_kit_branch_report();" name="to_date" id="exp_end_date" class="datepicker datepicker_to end_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                         
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
       <input type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();">
         <?php if(in_array('252',$users_data['permission']['action'])) {
         ?>
              <a href="#" id="print_vaccination_kit" class="btn-anchor" name="print_vaccination_kit">Print</a>
          <?php } ?>
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script type="text/javascript">
  $(document).ready(function(){
       vaccination_kit_branch_report();
  });
// $('.datepicker').datepicker({
//     format: 'dd-mm-yyyy', 
//     autoclose: true,  
//   }).on("change", function(selectedDate) 
//   {
//     var start_date=  $("#exp_start_date").val();
//     var end_date=  $("#exp_end_date").val();
//     $("#exp_end_date").datepicker('setStartDate',start_date); 
//     $("#exp_end_date").datepicker({ minDate: selectedDate });
// });
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
function vaccination_kit_branch_report()
{ 
     var branch_id = $("#sub_branch_ids option:selected").val();
     if(typeof branch_id=='undefined'){
          branch_id="<?php echo $users_data['parent_id']; ?>";
     }
     else
     {
          branch_id = $("#sub_branch_ids option:selected").val();
     }
     

     var start_date = $('#exp_start_date').val();
     var end_date = $('#exp_end_date').val(); 
     $("#print_vaccination_kit").attr("href",'<?php echo base_url('vaccination_packages/medicine_kit_to_branch_reports_excel?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date);
  
}  


 var count = 0;
  $(document).ready(function(){
          $("#msg").html('');
          $.post('<?php echo base_url('vaccination_packages/get_allsub_branch_medicine_kit_list/'); ?>',{},function(result){
                
               $("#child_sub_branch").html(result);
          });
        
         
     });
 function reset_date_search(){
     $("#exp_start_date").val('');
     $("#exp_end_date").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_vaccination_kit_modal_popup').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>