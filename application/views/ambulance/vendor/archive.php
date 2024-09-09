<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
<?php // echo $this->uri->segment(2);?>

<!---bootstrap---->
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

var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "ajax": {
            "url": "<?php echo base_url()?>"+uri_seg+'/archive_ajax_list',
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



$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
var uri_seg='<?php echo $this->uri->segment(2);?>';
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url() ?>'+uri_seg+'/add/',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

 

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  var uri_seg='<?php echo $this->uri->segment(2);?>';
  $modal.load('<?php echo base_url()?>'+uri_seg+'/view/'+id,
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
       if($(this).prop('checked')==true && !isNaN($(this).val()))
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
                      url: "<?php echo base_url();?>"+uri_seg+'/restoreall',
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   } 

  else
	{
	 $('#nochecked').modal({ backdrop: 'static', keyboard: false })
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
        <table id="table" class="table table-striped table-bordered  table-responsive vendor_archive" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Vendor Name </th> 
                    <th> Vendor Code </th> 
                    <th>Vendor Type</th>
                    <th> Mobile No</th>  
                    <th> Email Address</th>
                    <th> Status </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        

    </form>


   </div> <!-- close -->





  	<div class="userlist-right">
  		<div class="btns"> 
         
  			<button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-window-restore" aria-hidden="true"></i> Restore
  			</button>
         
          
               <button class="btn-update" id="deleteAll" onclick="return checkboxValuestrash();">
                    <i class="fa fa-trash"></i> Delete
               </button>
         
          
               <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
          
               <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>ambulance/<?php echo $this->uri->segment(2)?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  
 function restore_vendor(rate_id)
 {    
  var uri_seg='<?php echo $this->uri->segment(2);?>';  
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url(); ?>"+uri_seg+'/restore/'+rate_id,  
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
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

  function trash(id)
 {    
  var uri_seg='<?php echo $this->uri->segment(2);?>';  
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false, 
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url(); ?>"+uri_seg+'/trash/'+id,  
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
 var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';   
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
                      url: "<?php echo base_url(); ?>"+uri_seg+'/trashall',  
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }  
  else
	{
	 $('#nochecked').modal({ backdrop: 'static', keyboard: false })
    }    
 }
</script>  

<!-- Confirmation no select -->
<div id="nochecked" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header bg-theme"><h4>Please select at least one record! </h4></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation no select -->

<!-- Confirmation Box -->
<div id="confirm" class="modal fade dlt-modal">
  <div class="modal-dialog delete-modal">
    <div class="modal-content">
      <div class="modal-header bg-theme">
      <h4>Are you sure?</h4>
      </div>
      <!-- <div class="modal-body"></div> -->
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
        <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>
    </div>
  </div>  
</div>
<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!----container-fluid--->
</body>
</html>