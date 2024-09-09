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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 


<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<style type="text/css">
.update_data
{
  float: left;
  display: block;
  margin-left: 10px;
  color: green;
}  
</style>
<script type="text/javascript">
var save_method; 
var table;
<?php if(in_array('863',$users_data['permission']['action'])): ?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('test_profile/ajax_list')?>",
            "type": "POST",
            "data":function(d){
                              d.branch_id =  $("#sub_branch_id :selected").val();
                              return d;
                              }
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
});
<?php endif;?>
 



$(document).ready(function(){
var $modal = $('#load_add_elem_temp_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'test_profile/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_test_profile(id)
{

    window.location.href="<?php echo base_url().'test_profile/edit/' ?>"+id;
}

function view_test_profile(id)
{
  var $modal = $('#load_add_elem_temp_modal_popup');
  $modal.load('<?php echo base_url().'test_profile/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function sort_test_master(id,value){

    if(id!=''){
        $.post('<?php echo base_url('test_profile/save_sort_order_data/'); ?>',{'test_id':id,'sort_order_value':value},function(result){
            if(result!=''){
                reload_table();
            }

        })
    }
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
                      url: "<?php echo base_url('test_profile/deleteall');?>",
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


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    	   
      <div class="row m-b-5">
           <div class="col-md-12">
                <div class="row">
                     <div class="col-md-6">
                          <div id="child_branch" class="patient_sub_branch"></div>
                     </div> <!-- 6 -->
                     <div class="col-md-6 text-right">
                          
                     </div> <!-- 6 -->
                </div> <!-- innerRow -->
           </div> <!-- 12 -->
      </div> <!-- row -->
    <!-- // -->
      <div class="row m-b-5">
           <div class="col-md-12">
                <div class="row">
                     <div class="col-md-6">
                    
                     </div> <!-- 6 -->
                     <div class="col-md-6 text-right">
                          
                     </div> <!-- 6 -->
                </div> <!-- innerRow -->
           </div> <!-- 12 -->
      </div> <!-- row -->



    <form>
       <?php if(in_array('863',$users_data['permission']['action'])):?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered test_profile_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <?php
                     if($users_data['users_role']!=3)
                     {
                      ?>
                       <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                      <?php
                     } 
                    ?>  
                    <th> Profile Name </th>
                    <th> Profile Print Name </th> 
                    <th>Patient Rate</th> 
                    <th>Sort Order</th>
                    <th> Status </th> 
                    <?php
                     if($users_data['users_role']!=3)
                     {
                      ?>
                        <th> Action </th>
                      <?php
                     } 
                    ?>
                    
                </tr>
            </thead>  
        </table>
        <?php endif; ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
         <?php
          if(in_array('864',$users_data['permission']['action'])) {
          ?>              <a class="btn-anchor" id="modal_addd" onclick="window.location.href='<?php echo base_url('test_profile/add'); ?>'"> <i class="fa fa-plus"></i> New </a>
          <?php     }
                
          ?>
          
          <?php if(in_array('865',$users_data['permission']['action'])) {
          ?>
  			<a class="btn-anchor" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-trash"></i> Delete
  			</a>
          <?php } ?> 
           
          <div class="dropdown">
            <button class="btn-anchor dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">  
              Update Data
              <span class="caret"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item update_data" title="Export data for update" href="<?php echo base_url('test_profile/update_test_excel'); ?>"> <i class="fa fa-file-excel-o"></i> Export Data</a>
              <a class="dropdown-item update_data" id="update_open_modelx" onclick="return edit_profiels();" href="javascript:void(0);" title="Import data for update"> <i class="fa fa-file-excel-o"></i> Import Data</a> 
            </div>
          </div> 
          <?php
          if(in_array('870',$users_data['permission']['action'])) {
          ?> 
          <button class="btn-update" id="downloadAll" onclick="return downloadcheckboxValues();" style="display: none;">
             <i class="fa fa-download"></i> Download
          </button> 
          
          <?php } ?>
          <?php if(in_array('863',$users_data['permission']['action'])) {
          ?>
               <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
          <?php } ?>
          <?php if(in_array('867',$users_data['permission']['action'])) {
          ?>
  			<button class="btn-exit" id="archive" onclick="window.location.href='<?php echo base_url('test_profile/archive'); ?>'">
  				<i class="fa fa-archive"></i> Archive
  			</button>
          <?php } ?>
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
<?php
 $flash_success = $this->session->flashdata('success');

 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>  
 function delete_test_profile(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('test_profile/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 $(document).ready(function(){
          $.post('<?php echo base_url('test_profile/get_allsub_branch_list/'); ?>',{},function(result){
               $("#child_branch").html(result);
          });
     });
  var count = 0;
     function get_selected_branch_test_profile_list(val){
        reload_table();
        if(val=='inherit'){
          <?php if(in_array('141',$users_data['permission']['action'])) { ?>
             document.getElementById("modal_addd").style.display="none";
          <?php } ?>   
          <?php if(in_array('143',$users_data['permission']['action'])) { ?>
             document.getElementById("deleteAll").style.display="none";
          <?php } ?>   
          <?php if(in_array('144',$users_data['permission']['action'])) { ?>
             document.getElementById("archive").style.display="none";
          <?php } ?>   
             document.getElementById("downloadAll").style.display="block"; 
        }
        else{
          <?php if(in_array('141',$users_data['permission']['action'])) { ?>
            document.getElementById("modal_addd").style.display="block";
          <?php } ?>  
          <?php if(in_array('143',$users_data['permission']['action'])) { ?>
             document.getElementById("deleteAll").style.display="block";
          <?php } ?>   
          <?php if(in_array('144',$users_data['permission']['action'])) { ?>
             document.getElementById("archive").style.display="block";
          <?php } ?>   
             document.getElementById("downloadAll").style.display="none";  
        }
     }

     function downloadcheckboxValues() 
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
           allbranch_test_downloads(allVals);
      }

      function allbranch_test_downloads(allVals)
       {    
         if(allVals!="")
         {
             $('#confirmd').modal({
            backdrop: 'static',
            keyboard: false
              })
              .one('click', '#downloadprofile', function(e)
              {
                  $.ajax({
                            type: "POST",
                            url: "<?php echo base_url('test_profile/downloadall');?>",
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

 

function edit_profiels(id)
{

  var $modal = $('#load_test_master_import_modal_popup');
  $modal.load('<?php echo base_url().'test_profile/import_update_test_master_excel/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(result){
     
  $modal.modal('show');
  });
}
</script>
<div id="load_test_master_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->
    
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

<div id="confirmd" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="downloadprofile">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation Box end -->
<div id="load_add_elem_temp_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div><!--container-fluid-->
</body>
</html>