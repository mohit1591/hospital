<?php
$users_data = $this->session->userdata('auth_users');
$user_role= $users_data['users_role'];
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

  <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
  <div class="hr-scroll">      
<table id="table" class="table table-striped table-bordered ot_summary_list" cellspacing="0" width="100%">
            <thead>
    <tr>
     <th>Patient Name</th>
     <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th>
    <th>Booking No.</th>
    
    
    <th>Date</th>
    <th>Action</th>
   
 </tr>
 </thead>  
 <tbody>  
 <?php
   if(!empty($history_list))
   {
   	 //echo "<pre>";print_r($history_list); die;
   	 $i=1;
   	 foreach($history_list as $data)
   	 {
   	   ?>
   	    <tr>
   	    <td><?php echo $data->patient_name; ?></td>    
        <td><?php echo $data->patient_code; ?></td>
        <td><?php echo $data->booking_code; ?></td>
        
        <td><?php echo date('d-m-Y',strtotime($data->booking_date)); ?></td>
        
      	<td> 
      	<?php 
      	$print_url = "'".base_url('dialysis_booking/print_inventory/'.$data->id)."'";
      	$btn_print_pre = ' <a class="btn-custom" onClick="return print_window_page('.$print_url.')" href="javascript:void(0)" title="Print Inventory"  data-url="512"><i class="fa fa-print"></i> Print Inventory</a>'; echo $btn_print_pre;?></td>
		 </tr>
   	   <?php
   	   $i++;	
   	 }	
   }
 ?> 
 </tbody>
</table>
</div>

</div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
  		   
             <button class="btn-exit" onclick="window.location.href='<?php echo base_url('dialysis_booking'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 
</section>
<?php
$this->load->view('include/footer');
?>
</div><!-- container-fluid -->
</body>
</html>
