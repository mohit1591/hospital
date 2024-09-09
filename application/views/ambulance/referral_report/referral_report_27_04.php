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
                  
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>From Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="from_g_date" name="from_g_date" class="ref_collection_datepicker" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
                         </div>
                    </div>
                    <div class="row m-b-5">
                         <div class="col-md-5">      
                              <label><b>To Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="to_g_date" id="to_g_date" class="ref_collection_datepicker_to" value="<?php echo date('d-m-Y'); ?>" onblur="reset_search_value()">
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
                                <option value="all">All</option>
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

                        <?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
                        <div class="row m-b-5">
                          <div class="col-md-12">
                             <div class="row">
                               <div class="col-md-5"><b>Referred By</b></div>
                               <div class="col-md-7" id="referred_by">
                                 <input type="radio" checked name="referred_by" value="0"> Doctor &nbsp;
                                  <input type="radio" name="referred_by" value="1" > Hospital
                               </div>
                             </div>
                          </div>
                        </div> <!-- row -->


                        <div class="row m-b-5" id="doctor_div">
                          <div class="col-md-12">
                             <div class="row">
                               <div class="col-md-5"><b>Referred By Doctor</b></div>
                               <div class="col-md-7">
                                 <select name="referral_doctor" id="refered_id" class="m_select_btn"  onChange="return get_other(this.value)">
                                    <option value="">Select Doctor</option>
                                    <?php
                                    if(!empty($referal_doctor_list))
                                    {
                                      foreach($referal_doctor_list as $referal_doctor)
                                      {
                                        ?>
                                          <option value="<?php echo $referal_doctor->id; ?>"><?php echo $referal_doctor->doctor_name; ?></option>
                                          
                                        <?php
                                      }
                                    }
                                    ?>
                                  </select> 
                               </div>
                             </div>
                          </div>
                        </div> <!-- row -->

                      <div class="row m-b-5" id="hospital_div" style="display: none;">
                          <div class="col-md-12">
                             <div class="row">
                               <div class="col-md-5"><b>Referred By Hospital</b></div>
                               <div class="col-md-7">
                                 <select name="referral_hospital" id="referral_hospital" class="m_select_btn">
                                    <option value="">Select Hospital</option>
                                    <?php
                                    if(!empty($referal_hospital_list))
                                    {
                                      foreach($referal_hospital_list as $referal_hospital)
                                      {
                                        ?>
                                          <option value="<?php echo $referal_hospital['id']; ?>"><?php echo $referal_hospital['hospital_name']; ?></option>
                                          
                                        <?php
                                      }
                                    }
                                    ?>
                                  </select> 
                               </div>
                             </div>
                          </div>
                        </div> <!-- row -->
                      <?php }?>


                        <!--   <div class="row m-b-5">
                                <div class="col-md-5">      
                                <label><b>Export Type:</b></label>
                                </div>
                                <div class="col-md-7">
                                <select required="" name="print_opt" id="print_opt">
                                  onchange="print_options_ref_report(this.value)"
                                <option value="2">CSV</option>
                                <option value="3" selected="selected">Excel</option> 
                                </select>
                                </div>
                              </div>
 -->

                  
                    
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
      </div> <!-- modal-body --> 

        <div class="modal-footer">
            <div style="float:right;width:80%;display:inline-flex;">
                <!-- <button type="submit"  class="btn-update" id="reset_date" value="Reset" onClick="reset_date_search();">Reset</button> -->
                <button type="button" class="btn-update" onclick="referral_report_new();" name="submit" id="print_refe">Export</button>
                <?php if(in_array('245',$users_data['permission']['action'])){ ?>
                <a href="" class="btn-anchor" style="display:none;" class="btn-update"  name="print_pdf" id="print_pdf">Export</a>
                <?php } ?>
                <button type="button" class="btn-cancel" data-number="1">Close</button>
            </div>
        </div>
</form>     

<script type="text/javascript">
/*$(document).ready(function(){
  var printOpt = $( "#print_opt option:selected" ).val();
  print_options_ref_report(printOpt);
});*/

$('.ref_collection_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.ref_collection_datepicker').val();
      $('.ref_collection_datepicker_to').datepicker('setStartDate', start_data);
       
  });

  $('.ref_collection_datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });    


/*function print_options_ref_report(print_option)
{
   $("#print_refe").removeAttr("onClick");
     document.getElementById("print_pdf").style.display = "none";
     document.getElementById("print_refe").style.display = "block";
     var start_date = $('#from_g_date').val();
     var end_date = $('#to_g_date').val(); 
      var section_type = $('#section_type').val();
      var branch_id= $('#branch_id').val();

     if(print_option!=''){ //alert(print_option);
          if(print_option==1){ 
               $("#print_refe").attr("onClick","return referral_report_new();");
             }
             else if(print_option==2){
               $("#print_refe").removeAttr("onClick");
               $("#print_refe").attr("onClick","return openPrintWindows('<?php echo base_url('ambulance/ambulance_referral_reports/referral_report_csv?') ?>start_date="+start_date+"&end_date="+end_date+"&branch_id="+branch_id+"', 'windowTitle', 'width=820,height=600');");
          }else if(print_option==3){
            $("#print_refe").removeAttr("onClick");
               $("#print_refe").attr("onClick","return openPrintWindows('<?php echo base_url('ambulance/ambulance_referral_reports/referral_report_excel?') ?>start_date="+start_date+"&end_date="+end_date+"&branch_id="+branch_id+"', 'windowTitle', 'width=820,height=600');");
          }          
          else{
                document.getElementById("print_pdf").style.display = "none";
                document.getElementById("print_refe").style.display = "block";
               $("#print_refe").removeAttr("onClick");
          }
     }
} */
//get_referral_reports

function referral_report_new()
{   
  var reffer_by='';
  var start_date = $('#from_g_date').val();
  var end_date = $('#to_g_date').val(); 
  var ref_type=$("input[name='referred_by']:checked").val();
  if(ref_type==0)
  {
    reffer_by = $('#refered_id').val();
  }else
  {    
    reffer_by = $('#referral_hospital').val();
  }
  var section_type = $('#section_type option:selected').val(); 
  var branch_id = $('#branch_id option:selected').val();
   window.open('<?php echo base_url('ambulance/ambulance_referral_reports/get_appointment_data?') ?>start_date='+start_date+'&end_date='+end_date+'&branch_id='+branch_id+'&ref_type='+ref_type+'&reffer_by='+reffer_by,'mywin','width=800,height=600');
}  


$(document).ready(function(){
     $.post('<?php echo base_url('referral_reports/get_allsub_branch_list/'); ?>',{},function(result){
          $("#child_branch").html(result);
     });
 });
 var count = 0;
 
 function reset_date_search(){
     $("#start_date").val('');
     $("#end_date").val('');
 }
 $("button[data-number=1]").click(function(){
    $('#load_add_referral_report_modal_popup').modal('hide');
});

  function openPrintWindows(url, name, specs) { //alert(url);
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
     $("#print_opt").val('');
     
 }

    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();
      if(test==0)
      {
        $("#hospital_div").hide();
        $("#doctor_div").show();
        $('#referral_hospital').val('');
        
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
          $("#hospital_div").show();
          $('#refered_id').val('');
      }
        
    });

</script>

</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_referral_report_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>