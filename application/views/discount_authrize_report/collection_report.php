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
  <form  id="new_relation" class="form-inline">
 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12">
                 
                    <?php 
                    $users_data = $this->session->userdata('auth_users'); 

                    if (array_key_exists("permission",$users_data))
                    {
                         $permission_section = $users_data['permission']['section'];
                         $permission_action = $users_data['permission']['action'];
                    }
                    else
                    {
                         $permission_section = array();
                         $permission_action = array();
                    }
                    ?>  
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label>Patient Category</label>
                          </div>
                         <div class="col-md-7">
                            <select name="patient_category" class="m_input_default" id="patientcategory">
                                <option value="">Select Patient Category</option>
                                <?php 
                                  if(!empty($patient_category_list))
                                  {
                                    foreach($patient_category_list as $employee)
                                    {
                                      echo '<option value="'.$employee->id.'">'.$employee->patient_category.'</option>';
                                    }
                                  }
                                ?> 
                            </select>
                        </div>  
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label>Authorize Person</label>
                          </div>
                         <div class="col-md-7">
                            <select name="authorize_person" class="m_input_default" id="authorizeperson">
                                <option value="">Select Authorize Person</option>
                                <?php 
                                  if(!empty($authorize_person_list))
                                  {
                                    foreach($authorize_person_list as $employee)
                                    {
                                      echo '<option value="'.$employee->id.'">'.$employee->authorize_person.'</option>';
                                    }
                                  }
                                ?> 
                            </select>
                        </div>  
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="from_c_date" name="from_c_date" class="collection_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_c_date" id="to_c_date" class="collection_datepicker_to" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Department:</b></label>
                         </div>
                         <div class="col-md-7">
                               <select name="department" id="departments" multiple>
                                  <option value="">All</option>
                                    <?php  foreach($department as $key=>$dept){?>
                                  <option value="<?php echo $key;  ?>"><?php echo $dept;?></option>
                                     <?php }?>
                                </select>
                         </div>
                    </div>

                    
                    <input type="hidden" name="print_opt" id="print_opt" value="1">
                        <input type="hidden" name="module_type" value="0" id="module_type">
                       
                    
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:80%;display:inline-flex;">
                <button type="button" class="btn-update" name="submit" onclick="return discount_report_print();">Print</button>
                <button type="button" class="btn-update" name="submit" onclick="return print_excel_report();">Excel</button>
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">
 function reset_search_value(){
     //$("#print_opt").val('');
     
 }
$('select[multiple]').multiselect();
$('.collection_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.collection_datepicker').val();
      $('.collection_datepicker_to').datepicker('setStartDate', start_data);
       
  });

  $('.collection_datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });  


function discount_report_print()
{
     
    var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }

    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var module_type = $('#module_type').val(); 
    var printOpt = $( "#print_opt option:selected" ).val();
    var patient_category = $('#patientcategory').val(); 
    var authorize_person = $('#authorizeperson').val();
    //alert(patient_category);
    
      window.open('<?php echo base_url('discount_authrize_report/print_discount_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&patient_category='+patient_category+'&authorize_person='+authorize_person+'&dept='+xs,'mywin','width=800,height=600');    
      
}

function print_excel_report()
{
    
      
    var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var module_type = $('#module_type').val(); 
    var printOpt = $( "#print_opt option:selected" ).val();
    var authorize_person = $('#authorizeperson').val(); 
    var patient_category = $('#patientcategory').val(); 
    var order_by = $('#order_by').val();
    //alert(patient_category);
    window.location='<?php echo base_url('discount_authrize_report/discount_report_excel?') ?>start_date='+start_date+'&end_date='+end_date+'&authorize_person='+authorize_person+'&patient_category='+patient_category+'&order_by='+order_by+'&dept='+xs+'&send=preview','mywin','width=800,height=600';

}

 function openPrintWindows(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
} 
 

function discount_report_excel() //print excel report 
{
    
    var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var module_type = $('#module_type').val(); 
    var printOpt = $( "#print_opt option:selected" ).val();
    var patient_category = $('#patientcategory').val(); 
    var authorize_person = $('#authorizeperson').val();
    
    window.location='<?php echo base_url('report_excel/discount_reports_excel?') ?>start_date='+start_date+'&end_date='+end_date+'&patient_category='+patient_category+'&authorize_person='+authorize_person+'&dept='+xs+'&send=preview','mywin','width=800,height=600';  
    //$('#overlay-loader').show();
    //$('#overlay-loader').delay(50000).hide();
}

$(document).ready(function(){
     $.post('<?php echo base_url('discount_authrize_report/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
 });
 var count = 0;
 
 function reset_date_search(){
     $("#start_date").val('');
     $("#end_date").val('');
    $("#patientcategory").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_collection_modal_popup').modal('hide');
});

</script>
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>