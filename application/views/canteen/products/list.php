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
//if(in_array('946',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/products/ajax_list')?>",
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
<?php } ?>


$(document).ready(function(){
var $modal = $('#load_add_Cat_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/products/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_Category(id)
{

  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/products/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}

function view_Category(id)
{
  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'canteen/products/view/' ?>'+id,
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
             allbranch_delete(allVals);
       } 
       else{
           alert('Select atleast one checkbox');
       }
     });
    
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
                      url: "<?php echo base_url('canteen/products/deleteall');?>",
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


<div class="header_top">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
</div>
<!-- ============================= Main content start here ===================================== -->
<main class="main_page">
    <div class="main_wrapper">
      <div class="main_content">
          <form>
             <?php //if(in_array('946',$users_data['permission']['action'])) 
             {
             ?>
              <table id="table" class="table table-striped table-bordered category_list" cellspacing="0" width="100%">
                  <thead class="bg-theme">
                      <tr>
                          <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                          <th> Product Code </th> 
                           <th> Product Name </th> 
                          <th> Product Type </th> 
                          <th> Created Date </th> 
                          <th> Status </th> 
                          <th width="200"> Action </th>
                      </tr>
                  </thead> 
              </table>
              <?php } ?>
          </form>
     </div> 
    	<div class="main_btns">
    		<div class="fixed-top">
            <?php //if(in_array('947',$users_data['permission']['action'])) {
            ?>
            <a href="<?php echo base_url('canteen/products/add');?>" title="Add New">
              <button class="btn-hmas"> <i class="fa fa-plus"></i> New </button>
            </a>
            <?php //} ?>
            <?php //if(in_array('949',$users_data['permission']['action'])) {
            ?>
    			<button class="btn-hmas" id="deleteAll" onclick="return checkboxValues();" title="Delete">
    				<i class="fa fa-trash"></i> Delete
    			</button>
            <?php //} ?>
            <?php //if(in_array('947',$users_data['permission']['action'])) {
            ?>
                 <button class="btn-hmas" onclick="reload_table()"  title="Reload">
                    <i class="fa fa-refresh"></i> Reload
                 </button>
            <?php //} ?>
            <?php //if(in_array('950',$users_data['permission']['action'])) {
            ?>
    			<!--<button class="btn-hmas" onclick="window.location.href='<?php echo base_url('canteen/item_category/archive'); ?>'">
    				<i class="fa fa-archive"></i> Archive
    			</button>-->
            <?php //} ?>
          <button class="btn-hmas" onclick="window.location.href='<?php echo base_url(); ?>'" title="Exit">
            <i class="fa fa-sign-out"></i> Exit
          </button>
    		</div>
    	</div> 
    </div>
    <footer>
      <?php $this->load->view('include/footer'); ?>
    </footer>
</main>

<script>  

 function delete_products(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/products/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

</script> 
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
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>