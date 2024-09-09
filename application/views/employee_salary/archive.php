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
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('207',$users_data['permission']['action']))
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "<?php echo base_url('employee_salary/archive_ajax_list')?>",
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
<?php  
}
?>




$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'employee_salary/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

 

function view_employee_salary(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'employee_salary/view/' ?>'+id,
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

function flash_session_msg(val)
{
    $('#flash_msg_text').html(val);
    $('#flash_session').slideDown('slow').delay(1500).slideUp('slow');
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
     allemployee_salary_delete(allVals);
} 

function checkboxValuestrash() 
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
     trashall(allVals);
} 

function allemployee_salary_delete(allVals)
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
                      url: "<?php echo base_url('employee_salary/restoreall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }
   else{
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
        });
   }
 }
</script>




</head>

<body>
<div id="flash_msg"  class="booked_session_flash" style="display: none;">
    <i class="fa fa-check-circle"></i>
    <span id="flash_msg_text"></span>
</div>

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
       <?php
        if(in_array('207',$users_data['permission']['action']))
        {
        ?>
        <table id="table" class="table table-striped table-bordered employee_salary_archive" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll"> </th>
                    <!--  <th> Voucher No </th> -->
                        <th> Expense Date</th>
                  
                        <th> Paid Amount </th> 
                        <th> Payment Mode </th> 
                        <th> Created_date </th> 
                        <th> Action </th>
                </tr>
            </thead>  
        </table>
       <?php
         } 
       ?>

    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
      <div class="fixed">
    <?php
    if(in_array('298',$users_data['permission']['action']))
    {
    ?>
  		<div class="btns"> 
  			<button class="btn-save" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-window-restore" aria-hidden="true"></i> Restore
  			</button>
    <?php
    }

    if(in_array('208',$users_data['permission']['action']))
    {
    ?>    
        <button class="btn-save" id="deleteAll" onclick="return checkboxValuestrash();">
          <i class="fa fa-trash" aria-hidden="true"></i> Delete
        </button>
    <?php
    }

    if(in_array('207',$users_data['permission']['action']))
    {
      ?>
        <button class="btn-save" onclick="reload_table()">
          <i class="fa fa-refresh"></i> Reload
        </button> 
      <?php
    } 
    ?>    
        
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('employee_salary'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
      </div>
  	</div> 
  	<!-- right -->
 
  <!-- cemployee_salary-rslt close -->

  


  
</section> <!-- cemployee_salary -->
<?php
$this->load->view('include/footer');
?>
<script>  

 function restore_bill(employee_salary_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false, 
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('employee_salary/restore/'); ?>"+employee_salary_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

  function trash_bill(employee_salary_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false, 
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('employee_salary/trash/'); ?>"+employee_salary_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 function trashall(allVals)
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
                      url: "<?php echo base_url('employee_salary/trashall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   } 
   else{
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
        });
   }
 }
 
</script>  

<div id="confirm-select" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Please select at-least one record.</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal --> 

<!-- Confirmation Box -->
<div id="confirm" class="modal fade dlt-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-theme">
        <h4>Are you sure?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn-save" id="delete">Confirm</button>
        <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>
    </div>
  </div>  
</div>
<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>