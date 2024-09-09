<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php //print_r($refund_data); ?>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>pwdwidget.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>pwdwidget.js"></script>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  
<script type="text/javascript">
var save_method; 
var table;
 $(document).ready(function() { 
          table = $('#table').DataTable({
               "searching": false,
               "bLengthChange": false, 
               "processing": true, 
               "serverSide": true, 
               "order": [], 
               "pageLength": '20',
               "ajax": {
                    "url": "<?php echo base_url('canteen/refund_payment/azax_refund_list')?>",
                    "type": "POST",
                 
               }, 
               "columnDefs": [{ 
                    "targets": [ 0 , -1 ], //last column
                    "orderable": false, //set not orderable
               },],
          });
     }); 



$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'doctors/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_doctors(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'doctors/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_doctors(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'doctors/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
} 

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        { 
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('doctors/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 }
</script>




</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show');
    </script>
    <?php
 }
?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

  <!-- // -->
<div class="row m-b-5">
     <div class="col-md-12">
          <div class="row">
               <div class="col-md-6">
               
               </div> <!-- 6 -->
               <div class="col-md-6 text-right">
                    <!-- <a href="javascript:void(0)" class="btn-custom" id="adv_search">
                     <i class="fa fa-cubes" aria-hidden="true"></i> 
                     Advance Search
                   </a> -->
               </div> <!-- 6 -->
          </div> <!-- innerRow -->
     </div> <!-- 12 -->
</div> <!-- row -->




  
  <form name="search_form"  id="search_form">   
  <table class="ptl_tbl">
   
   
   
  
  </table>
  <!-- // -->
  </form>
   <span style="color:#006400;"><font size="5" face='Arial'><center><?php
   //echo $this->session->flashdata('success');
 ?></center></font></span>

    <form>
        
            <!-- bootstrap data table -->




              <table id="table" class="table table-striped table-bordered refund_payment_list" cellspacing="0" width="100%">
               <thead class="bg-theme">
                    <tr>
                      <th>Sr.No.</th>
                      <th>Patient Name </th>
                       <th>Department </th>
                      <th>Refund Amount</th>
                      <th>Refund Date</th>
                     
                      
                      <!-- <th>Discount</th>  -->
            
                  
                       
                    </tr>
               </thead>  
               <tbody id="bal_list1">
               </tbody>
          </table>
              <!--  <table id="dataTable" class="table table-striped table-bordered refund_list dataTable no-footer" role="grid" aria-describedby="table_info" cellspacing="0">
          <thead class="bg-theme">
            <tr>
              <th>S.No.</th>
             <th>Patient Name</th>
             <th>Department</th>
              <th>Refund Amount</th>
              <th>Refund Date </th>
            </tr>
           
          </thead>
          <tbody>
          <?php $i=1;?>
        <?php if(!empty($refund_data)){ ?>
          <?php foreach($refund_data as $key=>$refund) {?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $refund['patient_name'];?></td>
            <td><?php if($refund['section_id']==3) {  ?>
                    Medicine
                  <?php } elseif($refund['section_id']==1) { ?>
                     Pathology</font>                  
                  <?php } else { ?> OPD<?php } ?> </td>
        
            <td><?php echo $refund['refund_amount'];?></td>
            <td><?php echo date('d-m-Y', strtotime($refund['refund_date']));?></td>
           </tr>

                        <?php } ?>

                        <?php } else  { ?>
        
          <tr>
          <td colspan="6">No Data Available in Table</td>
          </tr>
        
          <?php } ?>
          </tbody>
        </table> -->
    
     </form>


   </div> <!-- close -->





 
  <!-- cbranch-rslt close -->

  
  <div class="userlist-right relative">
    <div class="fixed">
        
                  
                    <button class="btn-exit" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url('canteen/refund_payment'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
          
    </div>
  </div> <!-- userlist-right -->

  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>


<!-- Confirmation Box -->

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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>