<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('990',$users_data['permission']['action'])) 
//{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('refund_payment/ajax_list')?>",
            "type": "POST",
            
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php //} ?>








 



</script>



</head>

<body id="bal_list" >


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>


<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

         <form id="top_search_form">
            
               <!-- bootstrap data table -->
               <div class="grp_box m-b-5">

                  <div class="grp">
                    <label></label>  
                       <?php
                       $refund_search_data = $this->session->userdata('refund_search_data');
                       $users_data = $this->session->userdata('auth_users'); 

                      if (array_key_exists("permission",$users_data)){
                           $permission_section = $users_data['permission']['section'];
                           $permission_action = $users_data['permission']['action'];
                      }
                      else{
                           $permission_section = array();
                           $permission_action = array();
                      }
                       

                       if(in_array('85',$permission_section)){ 
                       ?>
                       <input type="radio" <?php if(!empty($refund_search_data) && in_array('2',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> name="section_id[]" onclick="return form_submit();" value="2"> OPD
                        <?php }
                        
                         if(in_array('121',$permission_section))
                        {  ?>
                          <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('5',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="5"> IPD Advance
                        <?php } 
                        
                        if(in_array('387',$permission_section)){ 
                       ?>
                       <input type="radio" <?php if(!empty($refund_search_data) && in_array('14',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> name="section_id[]" onclick="return form_submit();" value="14"> Day Care
                        <?php }
                        
                        
                        if(in_array('151',$permission_section)){ 
                       ?>
                       <input type="radio" <?php if(!empty($refund_search_data) && in_array('4',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> name="section_id[]" onclick="return form_submit();" value="4"> Billing
                        <?php } 
                        
                         if(in_array('145',$permission_section)){ 
                       ?>  
                      <input type="radio" <?php if(!empty($refund_search_data) && in_array('1',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> name="section_id[]" onclick="return form_submit();" value="1"> Pathology

                      <?php
                      }
                      if(in_array('60',$permission_section))
                        { ?>
                       <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('3',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="3"> Medicine
                       <?php } 
                       
                       if(in_array('134',$permission_section))
                        { ?>
                       <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('8',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="8"> OT
                       <?php } 
                        if(in_array('262',$permission_section))
                        { ?>
                       <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('10',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="10"> Blood Bank
                       <?php } 
                       
                       if(in_array('181',$permission_section))
                        { ?>
                       <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('7',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="7"> Vaccination
                       <?php }
                       
                       if(in_array('349',$permission_section))
                        { ?>
                       <input type="radio" name="section_id[]" <?php if(!empty($refund_search_data) && in_array('13',$refund_search_data['section_id'])){ echo 'checked="checked"'; } ?> onclick="return form_submit()" value="13"> Ambulance
                       <?php } 
                        

                        ?>
                       
                  </div>
                    

                  
                    <div id="previous_data">
                      

                      <div class="grp" id="selection_criteria">Searching Criteria 
                        <select id="selection_criteria_list" name="selection_criteria_list" onchange="get_selected_value(this.value);form_submit();">
                        <option value=''>Select Option</option>
                        <option value="patient_name">Patient Name</option>
                        <option value="p_mobile_no">Mobile No.</option>
                        </select>
             </div>
                     <div class="grp" id="search_box_patient_name" style="display:none;"><input  onkeyup="return form_submit();" type="text" name="patient_name"  id="patient_name" /></div>
                      <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                     <div class="grp" id="search_box_mobile_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="mobile_no" id="mobile_no" onkeyup="return form_submit();"/></div> 
                      <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                    <div class="grp" id="branch_list"> <div id="child_branch" class="grp"></div>  </div>
                 
                     <input type="hidden" value="1" name="type" id="type"/>
                    </div>
                    
                    <div id="print_data"></div>

                  
                    <?php //$this->load->view('balance_clearance/drop_down_data');?>
                   
               </div>
                <!-- bootstrap data table -->
             </form>
                <!-- bootstrap data table -->
          <table id="table" class="table table-striped table-bordered refund_payment_list" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                      <th>Sr.No.</th>
                      <th>Patient Name </th>
                       <th>Mobile No. </th>
                      <th>Department</th>
                      <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
                      <th>Booking Date</th>
                      
                      <!-- <th>Discount</th>  -->
            
                      <th>Action</th>
                       
                    </tr>
               </thead>  
               <tbody id="bal_list1">
               </tbody>
          </table>
          
     </div> <!-- close -->
     <div class="userlist-right">
         
       
      <div class="btns">
              <button class="btn-exit" onclick="window.location.href='<?php echo base_url('refund_payment/refund_list'); ?>'">
          <i class="fa fa-archive"></i> Refund List
        </button>
             <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
                    <i class="fa fa-sign-out"></i> Exit
               </button>
         </div>
    </div> 
    <!-- right --> 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
  
 if ($("#section_id").is(":checked")) 
 { 
    form_submit();
  } 

   function form_submit()
   { 
    
    $.ajax({
           url: "<?php echo base_url('refund_payment/search_data/'); ?>", 
           type: 'POST',
           data: $('#top_search_form').serialize() ,
           success: function(result)
           { 
             reload_table(); 
             // get_balance_clearance_list(); 
           }
        });    

 
  }

     function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }

  function get_all_branch()
  { 
     $.post('<?php echo base_url('refund_payment/get_allsub_branch_list/'); ?>',{},function(result){

          $("#child_branch").html(result);
     });
   
 }
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function get_selected_value(value)
{ 
      
      if(value=='patient_name')
      {
            document.getElementById("search_box_patient_name").style.display="block";
             document.getElementById("search_box_mobile_no").style.display="none";
            $('#patient_name').val('');
      }
      else if(value=='p_mobile_no')
      {
             document.getElementById("search_box_mobile_no").style.display="block";
              document.getElementById("search_box_patient_name").style.display="none";
              $('#mobile_no').val('');
      }
      else if(value=='v_mobile_no')
      {
             document.getElementById("search_box_vendor_mobile_no").style.display="block";
              document.getElementById("search_box_vendor_name").style.display="none";
              $('#patient_name').val('');
      }
       else if(value=='vendor_name')
      {
             document.getElementById("search_box_vendor_name").style.display="block";
              document.getElementById("search_box_vendor_mobile_no").style.display="none";
      }
      else
      {
             document.getElementById("search_box_patient_name").style.display="none";
              document.getElementById("search_box_mobile_no").style.display="none";
             $('#patient_name').val('');
      }
}

/*function get_balance_clearance_list(vals)
{
      // alert(vals);
    $("#bal_list1 tr").remove(); 
    patientName = $("#patient_name").val();
    vendorName = $("#vendor_name").val(); 
    vendorMobileNo= $("#vendor_mobile_no").val(); 
    mobileNo = $("#mobile_no").val();
    sub_branch_id = $("#sub_branch_id").val(); 
    $.post('< ?php echo base_url('refund_payment/balance_list/'); ?>',{'sub_branch_id':sub_branch_id,'patient_name':patientName,'mobile_no':mobileNo,'type':vals,'vendor_name':vendorName,'vendor_mobile_no':vendorMobileNo},function(result)
    { 
          if(result!='')
          {
            $("#bal_list1").html(result);
          }

    });
}*/



</script>

<script>
  function get_patient_drop_down(type)
  {

    $.ajax({
                 type: "POST",
                 url: "<?php echo base_url('refund_payment/get_drop_down_value/'); ?>", 
                 data: {type: type},
                 success: function(result)
                 {
                  
                   $('#print_data').html(result);
                   $('#branch_autodrop').html('');
                   $('#previous_data').html('');
                   
                    get_all_branch();
                 }
              });

  }



function pay_now_to_branch(id,parent_id,section_id,balance,branch_id)
{ 
   
    var $modal = $('#load_add_pay_now_modal_popup');
    $modal.load('<?php echo base_url().'refund_payment/pay_now/' ?>'+id+'/'+parent_id+'/'+section_id+'/'+branch_id,
    {'bal': balance},
     function(){
          $modal.modal('show');
     });
}

</script>

<!-- Confirmation Box -->

    


</div><!-- container-fluid -->
<div id="load_add_pay_now_modal_popup" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</body>
</html>