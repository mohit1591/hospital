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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>


<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 



</head>

<body id="bal_list" onload="return get_balance_clearance_list()">


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
                    <div class="grp">
                    <input type="radio" name="type" value="2" onclick="get_patient_drop_down(this.value), get_balance_clearance_list(2);"> <label>Vendor</label>
                    </div>
                 <!--  <div class="grp">
                    From Date: <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $start_date;?>">
                    To Date: <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $end_date;?>" type="text">
                  </div>  -->


                  
                    <?php //$this->load->view('balance_clearance/drop_down_data');?>
                   
               </div>

                    <div id="previous_data"></div>
                    
                    <div id="print_data"></div>
                <!-- bootstrap data table -->
             
                <!-- bootstrap data table -->
          <table id="table" class="table table-striped table-bordered vendor_payment_list" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                      <th>Sr. No.</th>
                      <th>Name </th>
                      <th>Code</th> 
                      <th>Balance </th> 
                      <th>Action</th>
                       
                    </tr>
               </thead>  
               <tbody id="bal_list1">
               </tbody>
          </table>
          </form>
     </div> <!-- close -->
     <div class="userlist-right">
         
       
      <div class="btns">
           <a class="btn-exit m-t-30px" onclick="return show_ledger_report('')" href="javascript:void(0)" title="Ledger Report" data-url="512">Ledger Report</a>

               <button class="btn-exit m-t-10" onclick="window.location.href='<?php echo base_url(); ?>'">
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
     $.post('<?php echo base_url('vendor_payment/get_allsub_branch_list/'); ?>',{},function(result){

          $("#child_branch").html(result);
     });
   
 }

function get_selected_value(value)
{ 
      if(value=='patient_name')
      {
            document.getElementById("search_box_patient_name").style.display="block";
             document.getElementById("search_box_mobile_no").style.display="none";
      }
      else if(value=='p_mobile_no')
      {
             document.getElementById("search_box_mobile_no").style.display="block";
              document.getElementById("search_box_patient_name").style.display="none";
      }
      else if(value=='v_mobile_no')
      {
             document.getElementById("search_box_vendor_mobile_no").style.display="block";
              document.getElementById("search_box_vendor_name").style.display="none";
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
      }
}

function get_balance_clearance_list(vals)
{
       //alert(vals);
    $("#bal_list1 tr").remove(); 
    patientName = $("#patient_name").val();
    vendorName = $("#vendor_name").val(); 
    vendorMobileNo= $("#vendor_mobile_no").val(); 
    mobileNo = $("#mobile_no").val();
    sub_branch_id = $("#sub_branch_id").val(); 
    $.post('<?php echo base_url('vendor_payment/balance_list/'); ?>',{'sub_branch_id':sub_branch_id,'patient_name':patientName,'mobile_no':mobileNo,'type':vals,'vendor_name':vendorName,'vendor_mobile_no':vendorMobileNo},function(result)
    { 
          if(result!='')
          {
            $("#bal_list1").html(result);
          }

    });
}

</script>

<script>
  function get_patient_drop_down(type)
  {

    $.ajax({
                 type: "POST",
                 url: "<?php echo base_url('balance_clearance/get_drop_down_value/'); ?>", 
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
function pay_now_to_branch(id,balance,type,branch_id)
{ 
    var $modal = $('#load_add_pay_now_modal_popup');
    $modal.load('<?php echo base_url().'vendor_payment/pay_now/' ?>'+id+'/'+type+'/'+branch_id,
    {'bal': balance},
     function(){
          $modal.modal('show');
     });
}

function pay_now_to_delete(id,balance,type)
{ 
    
   
      $.ajax({
                 type: "POST",
                 url: "<?php echo base_url('vendor_payment/pay_delete/'); ?>", 
                 data: {id:id,balance:balance,type:type},
                 success: function(result)
                 {
                   if(result ==1){
                   location.reload();
                 }
                   
                 }
              });
}


$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });

  $(document).ready(function(){
    form_submit();
  });
function form_submit()
{ 
  var start_date = $('#start_date_patient').val();
  var end_date = $('#end_date_patient').val();
 $.ajax({
   url: "<?php echo base_url('vendor_payment/advance_search'); ?>", 
   type: 'POST',
   data: {start_date: start_date, end_date : end_date},
   success: function(result)
   {
    get_balance_clearance_list(2);
   }
 });      
 }

function show_ledger_report(v_id)
{
  var $modal = $('#load_add_eye_app_type');
  $modal.load('<?php echo base_url().'vendor_payment/ledger_show/' ?>'+v_id,
  {
  },
  function(){
  $modal.modal('show');
  });
}
</script>



<!-- Confirmation Box -->

    


</div><!-- container-fluid -->
<div id="load_add_pay_now_modal_popup" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_eye_app_type" class="modal fade modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</body>
</html>