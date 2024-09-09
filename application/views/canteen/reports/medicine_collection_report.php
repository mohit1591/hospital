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
  <form  id="" class="form-inline">
 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                   <!-- <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>User</b></label>
                         </div>
                        <div class="col-md-7">
                         <select name="employee_name" id="employee_name" >
                            <option value="">Select User</option>
                            <?php
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
                    </div>-->
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="start_m_date" name="start_m_date" class="datepicker">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="end_m_date" id="end_m_date" class="datepicker">
                         </div>
                    </div>

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Export Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="export_type" id="export_m_type" value="export_type">
                               
                               <option value="1"> Excel</option>
                               <option value="2"> CSV</option>
                               <option value="3"> Plain</option>
                             </select>
                         </div>
                    </div>
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
        <input type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();">
       
               <input type="button"  class="btn-update" onClick="return collection_medicine_report()" name="submit" value="Export" />
          
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<script type="text/javascript">
  
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
    
    $("#end_date").datepicker({ minDate: selectedDate });
}); 


function collection_medicine_report()
{    
  var start_date = $('#start_m_date').val();
  var end_date = $('#end_m_date').val(); 
  var user_id = $('#employee_m_name').val(); 
  var export_type = $('#export_m_type').val(); 
  window.open('<?php echo base_url('reports/print_medicine_collection_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&type='+export_type+'&user_id='+user_id,'mywin','width=800,height=600');
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
    $('#load_add_collection_modal_popup').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>