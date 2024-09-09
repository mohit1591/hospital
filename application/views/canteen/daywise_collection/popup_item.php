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
          <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
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
                              <label><b>Export Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="print_opt" id="print_opt" onchange="print_options_report(this.value)">
                             <!--  <option value="">Select Print Options</option> -->
                              <option value="1" selected="selected">Plain</option>
                              <option value="3">Excel</option>
                              </select>
                         </div>
                    </div>

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Via</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="module_type" id="module_type">
                             <option value="0" selected="selected">Medicine</option>
                             <option value="1">Company</option>
                             <option value="2">Vender</option>
                             </select>
                         </div>
                    </div>
                

                   <div class="row m-b-5" id="venders">
                         <div class="col-md-5">      
                              <label><b>Select Vender</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="vender_lists" id="vender_lists">
                              <?php if(!empty($vendor_list)){
                                 foreach ($vendor_list as $vender) {?>
                                    <option value="<?php echo $vender->id; ?>"><?php echo $vender->name; ?></option>
                              <?php } } ?>
                             </select>
                         </div>
                    </div>

                    
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer" style="display:flex;justify-content:flex-end;align-items:center;">
                <button type="button" class="btn-update" name="submit" id="print">Print</button>
                <?php if(in_array('245',$users_data['permission']['action'])){ ?>
                <a href="" class="btn-anchor" style="display:none;" class="btn-update"  name="print_pdf" id="print_pdf">Print</a>
                <?php } ?>
                
                <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div>
</form>     

<script type="text/javascript">
$(document).ready(function(){
  var printOpt = $( "#print_opt option:selected" ).val();
  print_options_report(printOpt);
  $('#venders').hide();
});


$('#module_type').on('change', function(){
  var val=$('#module_type').val();
  if(val==2){
    $('#venders').show();
  }
  else{
    $('#venders').hide();
  }
})

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
      var employee_c = $('#employee_c').val();
      var module_type = $('#module_type').val();  
      var vender=$('#vender_lists').val();    
      
      if(print_option!='')
      {
          if(print_option==1)
          {
               $("#print").attr("onClick","return collection_report();");
          }
          else if(print_option==2)
          {
              $("#print").removeAttr("onClick");
              $("#print").attr("onClick","return openPrintWindows('<?php echo base_url('reports/opd_report_csv?') ?>start_date="+start_date+"&end_date="+end_date+"&employee="+employee_c+", 'windowTitle', 'width=820,height=600');");
          }
          else if(print_option==3)
          { 
              $("#print").removeAttr("onClick");
              $("#print").removeAttr("onClick");
              $("#print").attr("onClick","return openPrintWindows('<?php echo base_url('medicinequantityreport/report_excel?') ?>start_date="+start_date+"&end_date="+end_date+"&module_type="+module_type+"&vender="+vender+"', 'windowTitle', '');");

              
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
    var start_date = $('#from_c_date').val();
    var end_date = $('#to_c_date').val(); 
    var module_type = $('#module_type').val(); 
    var vender = $('#vender_lists').val();
    var printOpt = $( "#print_opt option:selected" ).val();
    var employee_c = $('#employee_c').val(); 
    
    if(module_type=='1')
    {
      window.open('<?php echo base_url('canteen/itemwise_report_data/print_quantity_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&module_type='+module_type+'&vender='+vender,'mywin','width=800,height=600');
    }
    else
    {
      window.open('<?php echo base_url('canteen/itemwise_report_data/print_quantity_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&module_type='+module_type+'&vender='+vender,'mywin','width=800,height=600');    
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
    $('#load_add_medicine_quantity_report_modal_popup').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>