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
       <?php //if(in_array('2134',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered prescription_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr> 
                    <th> S.No. </th> 
                    <th> Name </th> 
                    <th> Mobile </th> 
                    <th> Created Date </th> 
                    <th> Action </th>
                </tr>
                </thead>  
                <?php if(!empty($list)){ $i=0;
                foreach ($list as $key => $data) { $i++; ?>    
                  <tr> 

                     <td> <?php echo $i; ?> </td> 
                    <td> <?php echo $data->patient_name; ?> </td> 
                    <td> <?php echo $data->mobile_no; ?> </td> 
                    <td> <?php echo date('d-M-Y',strtotime($data->created_date));?> </td> 
                    <td> <?php  $print_url = "'".base_url('eye/add_eye_prescription/view_prescription/'.$data->id.'/'.$data->booking_id)."'"; ?> 
                    <a class="btn-custom"  href="<?php echo base_url('eye/add_eye_prescription/view_prescription/'.$data->id.'/'.$data->booking_id);?>" title="View Eye Prescription" target="_blank" data-url="512"><i class="fa fa-info-circle"></i> View Eye Prescription</a>
                    <a class="btn-custom" onClick="return print_window_page(<?php echo $print_url;?>)" href="javascript:void(0)" title="Print Eye Prescription"  data-url="512"><i class="fa fa-print"></i> Print Eye Prescription</a> </td>
                </tr>
              <?php } } ?>

            
        </table>
        <?php //} ?>


   </div> <!-- close -->

  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

</body>
</html>