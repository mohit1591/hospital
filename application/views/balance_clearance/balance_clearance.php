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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>script.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 





</head>

<body id="bal_list">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

         <form>
            
               <!-- bootstrap data table -->
               <div class="grp_box m-b-5">
                     <div class="grp" id="selection_criteria">Searching Criteria <select id="selection_criteria_list" name="selection_criteria_list" onchange="get_selected_value(this.value);"><option value=''>Select Option</option><option value="patient_name">Patient Name</option><option value="mobile_no">Mobile No.</option></select></div>
                     <div class="grp" id="search_box_patient_name" style="display:none;"><input type="text" name="patient_name"  id="patient_name"/></div>
                      <?php if(!empty($form_error)){ echo form_error('patient_name'); } ?>
                     <div class="grp" id="search_box_mobile_no" style="display:none;"><input type="text" name="mobile_no" class="numeric" id="mobile_no"/></div> 
                      <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                    <div class="grp" id="branch_list"> <div id="child_branch" class="grp"></div>  </div>
                   
                   
                   
                   
                    <button type="button" class="btn-custom" onclick="get_balance_clearance_list();">Search</button>
               </div>
                <!-- bootstrap data table -->
             
                <!-- bootstrap data table -->
          <table id="table" class="table table-striped table-bordered balance_clearance" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                      
                         <th>  Patient Name </th>
                         <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
                         <th>  Balance </th> 
                         <th>Action</th>
                       
                    </tr>
               </thead>  
               <tbody id="bal_list1">
               <?php //print_r($patient_to_balclearlist); ?>
                    <?php if(!empty($patient_to_balclearlist))
                         {
                             
                             
                              foreach ($patient_to_balclearlist as $patients) {

                                   if($patients['balance']>0 or $patients['balance']<0)
                                   {
                    ?>
                                       <tr><td><?php echo $patients['patient_name']; ?></td><td><?php echo $patients['patient_code']; ?></td><td><?php echo $patients['balance'];?></td><td><button type="button" class="btn-custom" name="pay_now" id="pay_now" onclick="
                                        pay_now_to_branch(<?php echo $patients['id']; ?>,<?php echo $patients['balance'];?>);">Pay Now</button></td>
                                        </tr>
                                <?php
                                   }
                              }
                        }
                        else
                         {
                       
                         ?> 
                              <tr><td colspan="4" class="text-center">no record found</td></tr>
                         <?php 
                         }
                         ?>
               </tbody>
          </table>
          </form>
     </div> <!-- close -->
     <div class="userlist-right">
         
       
  		<div class="btns">
     
             
               <button class="btn-exit m-t-30px" onclick="window.location.href='<?php echo base_url(); ?>'">
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
function get_balance_clearance_list(value)
{
     $("#bal_list1 tr").remove(); 
     patientName = $("#patient_name").val();
     mobileNo = $("#mobile_no").val();


      $.post('<?php echo base_url('billing/balance_clearance/'); ?>',{'sub_branch_id':value,'patient_name':patientName,'mobile_no':mobileNo},function(result){
            if(result!=''){
                  $("#bal_list").html(result);
            }

      })
}
function pay_now_to_branch(section_id,patient_id,balance)
{
  
  var $modal = $('#load_add_pay_now_modal_popup');
  $modal.load('<?php echo base_url().'balance_clearance/pay_now/' ?>'+patient_id,{/*'id1': '1',//'id2': '2'*/},
     function(){
          $modal.modal('show');
     });
}

/*function get_selected_value(value)
{
     $("#bal_list1 tr").remove(); 
      if(value=='patient_name')
      {
            document.getElementById("search_box_patient_name").style.display="block";
             document.getElementById("search_box_mobile_no").style.display="none";
      }
      else if(value=='mobile_no')
      {
             document.getElementById("search_box_mobile_no").style.display="block";
              document.getElementById("search_box_patient_name").style.display="none";
      }else
      {
             document.getElementById("search_box_patient_name").style.display="none";
              document.getElementById("search_box_mobile_no").style.display="none";
      }
}*/
$(document).ready(function(){
     $.post('<?php echo base_url('billing/get_allsub_branch_list/'); ?>',{},function(result){

          $("#child_branch").html(result);
     });
   
 });
   function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }
  


</script>

<!-- Confirmation Box -->

    


</div><!-- container-fluid -->
<div id="load_add_pay_now_modal_popup" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>