<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>


</head>

<body id="expenses">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
   <form> 
    <div class="userlist-box">
    <div id="child_branch" class="patient_sub_branch"></div> 
   
   
       <div class="row">
            <div class="col-md-12">
                 <label><b>From Date:</b></label>
                      <input type="text" id="start_date" name="from_date" class="datepicker">
                      
                 <label><b>To Date:</b></label>
                      <input type="text" name="to_date" id="end_date" class="datepicker">
                      
                 <a class="btn-custom" id="reset_date" onClick="reset_date_search();">
                      <i class="fa fa-refresh"></i> Reset
                 </a>
                 <span class="userlist-right" id="example_wrapper">
                  
                   <a href="javascript:void(0)" class="btn-anchor" id="deleteAll" onClick="return expenses_report()">
                        <i class="fa fa-print"></i> Print
                   </a>
                      
                </span> 
            </div> 
       </div> <!-- //row -->
       
     </div> <!-- close -->
  	
     </form>
  	<!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?> 
<script type="text/javascript">
  
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
    
    $("#end_date").datepicker({ minDate: selectedDate });
}); 

function expenses_report()
{ 
     var branch_id = $('#sub_branch_id').val();
     var start_date = $('#start_date').val();
     var end_date = $('#end_date').val(); 
        
   window.open('<?php echo base_url('reports/print_opd_expenses_reports?') ?>branch_id='+branch_id+'&start_date='+start_date+'&end_date='+end_date,'mywin','width=800,height=600');
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
</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>