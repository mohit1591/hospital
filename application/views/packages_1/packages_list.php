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
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
     $(document).ready(function() { 
          table = $('#package_list').DataTable({  
               "processing": true, 
               "serverSide": true, 
               "order": [], 
               "pageLength": '20',
               "ajax": {
                    "url": "<?php echo base_url('packages/packages_ajax_list')?>",
                    "type": "POST",
               }, 
               "columnDefs": [
               { 
                    "targets": [ 0 , -1 ], //last column
                    "orderable": false, //set not orderable
               },],
          });
     }); 
</script>

</head>

<body id="hello">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="userlist">

    <div class="profile-master">
    
        <div class="pro-left">
            
               <div class="row m-b-5">
                   <!--  <div class="col-md-5">
                        
                    </div>
                    <div class="col-md-7"> -->
                         <table class="table table-bordered table-striped" id="package_list">
                              <thead class="bg-theme">
                                   <tr>
                                        <th align="center" width="40"><input type="checkbox" name="gettestselectAll" class="" id="gettestselectAll" value=""></th>
                                        <th>Package Title</th>
                                        <th>Package Amount</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Action</th>
                                   </tr>
                              </thead>
                         </table>
                 <!--    </div> -->
               </div> <!-- row -->
             
          </div> <!-- pro-left -->

        <div class="pro-right">
            
            <!-- <div class="pframe2"> -->
                
         <!--    </div>  --><!-- pframe2 -->

            <div class="pframe-right">
                <div class="btns">
                    <button class="btn-update" id="modal_add">
                        <i class="fa fa-plus"></i> New
                  </button>
                  <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                        <i class="fa fa-trash"></i> Delete
                  </button>
                  <button class="btn-update" onclick="reload_table()">
                        <i class="fa fa-refresh"></i> Reload
                  </button>
                  <button class="btn-exit" onclick="window.location.href='<?php echo base_url('medicine/archive'); ?>'">
                        <i class="fa fa-archive"></i> Archive
                  </button>
                 
                </div>
            </div>

        </div> <!-- pro-right -->


    </div> <!-- profile-master -->




<div class="profile-master2">
    <div class="proleft">


            <table class="table table-bordered table-striped" id="test_child_add_list">
                    <thead class="bg-theme">
                        <tr>
                            <th align="center" width="40"><input type="checkbox" name="addtestchildselectAll" class="" id="addtestchildselectAll" value=""></th>
                            <th>Medicine Name</th>
                            
                        </tr>
                    </thead>
                    <tbody id="test_child_add">
                    
                    </tbody>
                </table>
                <?php if(!empty($form_error)){ echo form_error('test'); } ?>
    </div> <!-- proleft -->

    <div class="proright">
        <div class="btns">
            <button class="btn-update" id="deleteAll" onclick="return deleteChildCheckboxValues();">
                    <i class="fa fa-trash"></i> Add
               </button>
            <button type="submit" class="btn-save" name="" id="" onclick="return addtestcheckboxValues();"><i class="fa fa-floppy-o"></i> Save</button>
            <button type="button" class="btn-save" name="" id="" onclick="window.location.href='<?php echo base_url('test_profile'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>

        </div>
    </div> <!-- proright -->
</div> <!-- profile-master2 -->












<div id="load_add_interpretation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>