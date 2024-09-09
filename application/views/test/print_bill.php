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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>



</head>

<body>

<div class="container-fluid">

<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    <form name="search_form" action="<?php echo base_url('test'); ?>" method="post" id="search_form">
    
         
    <!-- // -->
   
    

   
    <table width="100%" class="m-b-10 hidden">
       <tr>
           <td>
                From date : <input type="text" id="start_date" name="from_date" class="datepicker" value="<?php echo $form_data['start_date']; ?>">
          </td>
           <td>
                To date : <input type="text" name="to_date" id="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>">
          </td>
            <td>
            <a class="btn-update" id="reset_date" onclick="reset_date_search();">
                <i class="fa fa-refresh"></i> Reset
            </a>
         </td>

       </tr>
    </table>	 
    </form>
    <form> 
       <!-- bootstrap data table -->

        <table id="table" class="table table-striped table-bordered test_booking_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Lab Ref. No. </th> 
                    <th> Patient Name </th> 
                    <th> Booking Date </th> 
                    <th> Total Amount </th> 
                    <th> Paid Amount </th>  
                    <th> Discount </th>  
                    <th> Created Date </th> 
                    
                </tr>
            </thead>  
        </table> 
        <tbody>
            <tr>
               <td><?php  echo $data[0]->lab_reg_no; ?></td>
               <td><?php echo $data[0]->patient_name;?></td>
               <td><?php echo $data[0]->booking_date;?></td>
               <td><?php echo $data[0]->total_master_amount;?></td>
               <td><?php echo $data[0]->paid_amount;?></td>
               <td><?php echo $data[0]->discount;?></td>
               <td><?php echo $data[0]->created_date;?></td>
            </tr>
        </tbody>

    </form>
   </div> <!-- close -->
 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->



<!-- Confirmation Box -->

    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('<?php echo base_url("test/print_test_booking_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>