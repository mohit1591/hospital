<!DOCTYPE html>
<html>
<head>
   <title><?php echo $page_title.PAGE_TITLE; ?></title>
   <?php  $users_data = $this->session->userdata('auth_users'); ?>
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

   <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
   <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

</head>

<body style="padding-bottom: 70px;">
   <div class="container-fluid">
      <?php
      $this->load->view('include/header');
      $this->load->view('include/inner_header');
      ?>
   </div>
   <!-- ============================= Main content start here ===================================== -->
   <section class="top_article">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-11">
               <article class="">
                  <div class="row">

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">From Date</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">To Date</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">Booking No.</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">Patient Name</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">Mobile No.</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>

                     <div class="col-md-6 mb-2">
                        <div class="form-group">
                           <label for="" class="col-md-4">Branch</label>
                           <div class="col-md-8">
                              <input type="text" class="form-control">
                           </div>
                        </div>
                     </div>                
                  </div>
               </article>

               <article class="mb-3">
                  <div class="table-responsive">
                     <table class="table table-bordered dataTable">
                        <thead>
                           <tr>
                              <th width="40" align="center" valign="middle">
                                 <input type="checkbox">
                              </th>
                              <th>Booking No.</th>
                              <th>Patient Name</th>
                              <th>Mobile No.</th>
                              <th>Email ID</th>
                              <th>City</th>
                              <th>Booking Date</th>
                              <th>Booking Time</th>
                              <th>Source</th>
                              <th>Destination</th>
                              <th>Action</th>                              
                           </tr>
                        </thead>
                        <tbody>
                           <tr>
                              <td align="center" valign="middle">
                                 <input type="checkbox">
                              </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td> -- </td>
                              <td width="150">
                                 <a href="#">
                                    <button class="btn-custom"><i class="fa fa-pencil"></i> Edit</button>
                                 </a>
                                 <a href="#">
                                    <button class="btn-custom"><i class="fa fa-info-circle"></i> View</button>
                                 </a>
                                 <div class="slidedown">
                                    <button disabled="" class="btn-custom">More <span class="caret"></span></button>
                                    <ul class="slidedown-content">
                                       <li>
                                          <a href="javascript:void(0)" title="Delete"><i class="fa fa-trash"></i> Delete</a>
                                       </li>
                                    </ul>
                                 </div>
                              </td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </article>
            </div>
            <div class="col-md-1">
               <div class="fixed">
                  <div class="btns">
                     <button class="btn-update" type="button"> <i class="fa fa-plus"></i> New </button>
                     <button class="btn-update" type="button"> <i class="fa fa-file-excel-o"></i> Excel </button>
                     <button class="btn-update" type="button"> <i class="fa fa-file-word-o"></i> CSV </button>
                     <button class="btn-update" type="button"> <i class="fa fa-file-pdf-o"></i> PDF </button>
                     <button class="btn-update" type="button"> <i class="fa fa-print"></i> Print </button>
                     <button class="btn-update" type="button"> <i class="fa fa-trash"></i> Delete </button>
                     <a href="#">
                        <button class="btn-update" type="button"> <i class="fa fa-file-excel-o"></i> Sample(.xls) </button>
                     </a>
                     <a href="#">
                        <button class="btn-update" type="button"> <i class="fa fa-file-excel-o"></i> Import(.xls) </button>
                     </a>
                     <button class="btn-update" type="button"><i class="fa fa-refresh"></i> Reload </button>
                     <button class="btn-update" type="button"><i class="fa fa-archive"></i> Archive </button>
                     <button class="btn-update" type="button"><i class="fa fa-sign-out"></i> Exit </button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>





















   <!-- container-fluid -->
   <div class="container-fluid  navbar-fixed-bottom">
      <?php $this->load->view('include/footer'); ?>
   </div>
</body>
</html>