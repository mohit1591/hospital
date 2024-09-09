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
          <h4><?php echo $page_title; ?> &nbsp; <a href="javascript:void(0)" id="collection_setting" style="background:#fff;padding:3px 1em;color:#000;border-radius:10px;font-size:12px;border-bottom:3px solid #555;">  Report Setting</a></h4> 
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
                    //if(in_array('1',$permission_section)){
                    if($users_data['emp_id']==0){
                    ?>  
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label>User</label>
                          </div>
                         <div class="col-md-7">
                            <select name="employee" class="m_input_default" id="employee_c">
                                <option value="">Select User</option>
                                <?php 
                                  if(!empty($employee_list))
                                  {
                                    foreach($employee_list as $employee)
                                    {
                                      echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                                    }
                                  }
                                ?> 
                            </select>
                        </div>  
                    </div>
                    <?php }else { ?>

                      <input type="hidden" name="employee" id="employee_c" value="<?php echo $users_data['id'];?>">
                      <?php } ?>
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="from_c_date" style="width:95px" name="from_c_date" class="collection_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                               <input type="text"  style="width:95px" id="from_c_time" name="from_c_time" class="datepicker3" value="<?php echo date('h:i A'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text"  style="width:95px" name="to_c_date" id="to_c_date" class="collection_datepicker_to" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                               <input type="text"  style="width:95px" name="to_c_date" id="to_c_time" class="datepicker3" value="<?php echo date('h:i A'); ?>" onblur="reset_search_value()">
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

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Export Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="print_opt" id="print_opt" onchange="print_options_report(this.value)">
                             <!--  <option value="">Select Print Options</option> -->
                              <option value="1" selected="selected">Plain</option>
                              <!-- <option value="2">CSV</option>
                              <option value="3">Excel</option> -->
                              </select>
                         </div>
                    </div>
                    <?php 
                      if(in_array('218',$users_data['permission']['section']))
                      {
                        ?>
                        <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Collection Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="module_type" id="module_type">
                             <option value="0" selected="selected">Normal</option>
                             <!--<option value="1">Hospital</option>-->
                             </select>
                         </div>
                    </div>
                        <?php 
                      }
                      else
                      {
                        ?>
                        <input type="hidden" name="module_type" value="0" id="module_type">
                        <?php 
                      } 
                       ?>
                       
                       <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Order By:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="order_by" id="order_by">
                             <option value="DESC" selected="selected">DESC</option>
                             <option value="ASC">ASC</option>
                             </select>
                         </div>
                    </div>
                      
                    
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:100%;display:inline-flex;text-align:center">
              
                <button type="button" class="btn-update" name="submit" id="print">Print</button>
                <button type="button" class="btn-update" name="submit" id="report_preview">Preview</button>
                <?php if(in_array('245',$users_data['permission']['action'])){ ?>
                <a href="" class="btn-anchor" style="display:none;" class="btn-update"  name="print_pdf" id="print_pdf">Print</a>
                <?php } ?>
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
$('select[multiple]').multiselect();
$(document).ready(function(){
  var $modal = $('#load_collection_setting_popup');
  $('#collection_setting').on('click', function(){
    $modal.load('<?php echo base_url("collection_tab_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

});

$(document).ready(function(){
  var printOpt = $( "#print_opt option:selected" ).val();
  print_options_report(printOpt);
});

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


function print_options_report(print_option)
{
      $("#print").removeAttr("onClick");
      document.getElementById("print_pdf").style.display = "none";
      document.getElementById("print").style.display = "block";
      var start_date = $('#from_c_date').val();
      var end_date = $('#to_c_date').val(); 
      var start_time = $('#from_c_time').val();
      var end_time = $('#to_c_time').val(); 
      var employee_c = $('#employee_c').val();
      var module_type = $('#module_type').val();
      var order_by  = $('#order_by').val();
      
      if(print_option!='')
      {
          if(print_option==1)
          {
               $("#print").attr("onClick","return collection_report();");
                $("#report_preview").attr("onClick","return preview_collection_report();");
          }
          else if(print_option==2)
          {
              $("#print").removeAttr("onClick");
              $("#print").attr("onClick","return openPrintWindows('<?php echo base_url('reports/opd_report_csv?') ?>start_date="+start_date+"&end_date="+end_date+"&employee="+employee_c+"&order_by="+order_by+"start_time="+start_time+"&end_time="+end_time+"', 'windowTitle', 'width=820,height=600');");
          }
          else if(print_option==3)
          { 
              $("#print").removeAttr("onClick");
              $("#print").removeAttr("onClick");
              $("#print").attr("onClick","return openPrintWindows('<?php echo base_url('reports/opd_report_excel?') ?>start_date="+start_date+"&end_date="+end_date+"&order_by="+order_by+"start_time="+start_time+"&end_time="+end_time+"', 'windowTitle', 'width=820,height=600');");
          }
          else
          {
                document.getElementById("print_pdf").style.display = "none";
                document.getElementById("print").style.display = "block";
               $("#print").removeAttr("onClick");
          }
     }
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
 function reset_search_value(){
     //$("#print_opt").val('');
     
 }

function collection_report()
{    
     var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }
    
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var start_time = $('#from_c_time').val();
    var end_time = $('#to_c_time').val(); 
    var module_type = $('#module_type').val(); 
    //var export_type = $('#export_c_type').val();
    var printOpt = $( "#print_opt option:selected" ).val();
    var employee_c = $('#employee_c').val(); 
    var order_by = $('#order_by').val();
    
    if(module_type=='1')
    {
      window.open('<?php echo base_url('reports/print_hospital_collection_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c+'&order_by='+order_by+'&start_time='+start_time+'&end_time='+end_time+'&dept='+xs,'mywin','width=800,height=600');
    }
    else
    {
      window.open('<?php echo base_url('reports/print_opd_collection_with_time_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c+'&order_by='+order_by+'&start_time='+start_time+'&end_time='+end_time+'&dept='+xs,'mywin','width=800,height=600');    
    }
 
  //window.open('<?php echo base_url('reports/print_opd_collection_with_time_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c,'mywin','width=800,height=600');
}

function preview_collection_report()
{    
    var department = $('#departments').val();
    var xs = '';
    if(department !=null)
    {
       xs = department.toString();
    }
    
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val();
     var start_time = $('#from_c_time').val();

    var end_time = $('#to_c_time').val(); 
    var module_type = $('#module_type').val(); 
    //var export_type = $('#export_c_type').val();
    var printOpt = $( "#print_opt option:selected" ).val();
    var employee_c = $('#employee_c').val(); 
    var order_by = $('#order_by').val();
    if(module_type=='1')
    {
      window.open('<?php echo base_url('reports/print_hospital_collection_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c+'&order_by='+order_by+'&start_time='+start_time+'&end_time='+end_time+'&send=preview'+'&dept='+xs,'mywin','width=800,height=600');
    }
    else
    {
      window.open('<?php echo base_url('reports/print_opd_collection_with_time_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c+'&order_by='+order_by+'&start_time='+start_time+'&end_time='+end_time+'&send=preview'+'&dept='+xs,'mywin','width=800,height=600');    
    }
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
$("#employee_c").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_collection_modal_popup').modal('hide');
});

$('#advance_report').click(function(){
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
     var employee_c = $('#employee_c').val(); 
    window.open('<?php echo base_url('reports/print_advance_collection_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&employee='+employee_c+'&order_by='+order_by+'mywin','width=800,height=600');
});

 $('.datepicker3').datetimepicker({
      format: 'LT'
  });

</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>