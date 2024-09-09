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
                   <!-- <div class="row m-b-5">
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
                    </div>-->
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="from_g_date" name="from_g_date" class="collection_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_g_date" id="to_g_date" class="collection_datepicker_to" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>

                   <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Branches:</b></label>
                          </div>
                             
                               <?php    $users_data = $this->session->userdata('auth_users');
                                        $sub_branch_details = $this->session->userdata('sub_branches_data');
                                        $parent_branch_details = $this->session->userdata('parent_branches_data');
                                        //$branch_name = get_branch_name($parent_branch_details[0]);
                                    ?>
                                     <div class="col-md-7">
                            <select name="branch_id" id="branch_id">
                               <option value="">Select Branch</option>
                               <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
                                 <?php 
                                 if(!empty($sub_branch_details)){
                                     $i=0;
                                    foreach($sub_branch_details as $key=>$value){
                                         ?>
                                         <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
                                         <?php 
                                         $i = $i+1;
                                     }
                                   
                                 }
                                ?> 
                                </select>
                             </div>
                             </div>

                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Section Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="section_type" id="section_type" onchange="export_type_select(this.value);"> 
                             <option value="all" selected>All</option>
                              <option value="1">Purchase</option>
                              <option value="2">Purchase Return</option>
                              <option value="3">Sale</option>
                              <option value="4">Sale Return</option>
                              </select>
                         </div>
                    </div>
                    <div id="export_type">
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>Export Type:</b></label>
                         </div>
                         <div class="col-md-7">
                             <select name="print_opt" id="print_opt" onchange="print_options_report(this.value)">
                              <option value="">Select Print Options</option>
                              <option value="1" selected="selected">Plain</option>
                              <option value="2">CSV</option>
                              <option value="3">Excel</option>
                              </select>
                         </div>
                    </div>
                    </div>
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:80%;display:inline-flex;">
                <!-- <button type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();">Reset</button> -->

                <input value="Reset" onclick="reset_date_search(this.form)" type="button" class="btn-reset">

                <button type="button" class="btn-update" name="submit" id="print">Print</button>
                <?php if(in_array('245',$users_data['permission']['action'])){ ?>
                <a href="" class="btn-anchor" style="display:none;" class="btn-update"  name="print_pdf" id="print_pdf">Print</a>
                <?php } ?>
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">
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
function export_type_select(value_all){
$('#export_type').html('');
  if(value_all=='all'){
  
    $('#export_type').html('<div class="row m-b-5"> <div class="col-md-5"><label><b>Export Type:</b></label></div> <div class="col-md-7"><select name="print_opt" id="print_opt" onchange="print_options_report(this.value)"><option value="">Select Print Options</option> <option value="1">Plain</option></div></div>');
  }else{
   $('#export_type').html('<div class="row m-b-5"> <div class="col-md-5"><label><b>Export Type:</b></label></div> <div class="col-md-7"><select name="print_opt" id="print_opt" onchange="print_options_report(this.value)"><option value="">Select Print Options</option> <option value="1" selected="selected">Plain</option><option value="2">CSV</option><option value="3">Excel</option></div></div>');
  }
 
}

function print_options_report(print_option){
  //alert(print_option);
     $("#print").removeAttr("onClick");
     document.getElementById("print_pdf").style.display = "none";
     document.getElementById("print").style.display = "block";
     var start_date = $('#from_g_date').val();
     var end_date = $('#to_g_date').val(); 
      var section_type = $('#section_type').val();
      var branch_id= $('#branch_id').val();
     if(print_option!=''){
          if(print_option==1){
               $("#print").attr("onClick","return gst_report();");
          }else if(print_option==2){
               $("#print").removeAttr("onClick");
               $("#print").attr("onClick","return print_window_page('<?php echo base_url('medicine_gst/gst_report_csv?') ?>start_date="+start_date+"&end_date="+end_date+"&section_type="+section_type+"&branch_id="+branch_id+"');");
          }
          else if(print_option==3)
          { 
              $("#print").removeAttr("onClick");
              $("#print").removeAttr("onClick");
              $("#print").attr("onClick","return print_window_page('<?php echo base_url('medicine_gst/gst_report_excel?') ?>start_date="+start_date+"&end_date="+end_date+"&section_type="+section_type+"&branch_id="+branch_id+"');");

              }
              else
              {
                document.getElementById("print_pdf").style.display = "none";
                document.getElementById("print").style.display = "block";
               $("#print").removeAttr("onClick");
          }
     }
} 


function gst_report()
{   
  var start_date = $('#from_g_date').val();
  var end_date = $('#to_g_date').val(); 
  var section_type = $('#section_type option:selected').val(); 
  var branch_id = $('#branch_id option:selected').val();
   window.open('<?php echo base_url('medicine_gst/print_medicine_gst_reports?') ?>start_date='+start_date+'&end_date='+end_date+'&section_type='+section_type+'&branch_id='+branch_id,'mywin','width=800,height=600');
}  


$(document).ready(function(){
  export_type_select('all');
     $.post('<?php echo base_url('reports/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
 });
 var count = 0;
 
 function reset_date_search(ele)
 {
     /*$("#start_date").val('');
     $("#end_date").val('');*/

     $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });

 }
 $("button[data-number=1]").click(function(){
    $('#load_gst_modal_popup').modal('hide');
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_gst_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>