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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
var save_method; 
var table;
<?php
$users_data = $this->session->userdata('auth_users');
if (array_key_exists("permission",$users_data))
{
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];
}
else
{
     $permission_section = array();
     $permission_action = array();
}
if(in_array('453',$permission_action) || in_array('121',$permission_section)){
//if(in_array('453',$users_data['permission']['action'])) {
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '25',
        "ajax": {
            "url": "<?php echo base_url('test_name/ajax_list')?>",
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
var $modal = $('#load_add_test_name_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'test_name/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_test_name(id)
{
  var $modal = $('#load_add_test_name_modal_popup');
  $modal.load('<?php echo base_url().'test_name/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_test_name(id)
{
  var $modal = $('#load_add_test_name_modal_popup');
  $modal.load('<?php echo base_url().'test_name/view/' ?>'+id,
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
                      url: "<?php echo base_url('test_name/deleteall');?>",
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
    
    <div class="userlist-box" style="width:100% !important">  
      <div id="permission_section">
           <div class="prescription_permission" style="width:100%;">
           <div> <?php  $uri_url = $this->uri->segment(1); ?>
            <ul class="prescription_menu">
            
              <li class="per_menu_tab <?php if($uri_url=='previous_history'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('previous_history');?>';" name="new_patient"> Previous History </span></label> </strong>
                
              </li>
              <li class="per_menu_tab <?php if($uri_url=='chief_complaints'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('chief_complaints');?>';"> Chief Complaints </span></label> </strong>
                
              </li>
               <li class="per_menu_tab <?php if($uri_url=='examination'){ ?> active <?php } ?>">
                <strong><label><span  onClick="window.location='<?php echo base_url('examination');?>';"> Examination </span>
                    </label>
                 </strong>
                </li>
              
                
                
                <li class="per_menu_tab <?php if($uri_url=='diagnosis'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('diagnosis');?>';">Diagnosis </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='test_name'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('test_name');?>';"> Test Name </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='medicine'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('medicine');?>';"> Medicine </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='personal_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('personal_history');?>';" > Personal History </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='type'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('type');?>';"> Type </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='dosage'){ ?> active <?php } ?>">
                <strong><label>
                    <span  onClick="window.location='<?php echo base_url('dosage');?>';">Dosage </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='duration'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('duration');?>';"> Duration </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='frequency'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('frequency');?>';"> Frequency </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='advice'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('advice');?>';"> Advice </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='suggestion'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('suggestion');?>';"> Suggestion </span>
                    </label>
                 </strong>
                </li>
                
                
                
            </ul>
            </div>
        </div>
     </div>
</div>
    <div class="userlist-box">
    	 
    <form>
       <?php 

if(in_array('453',$permission_action) || in_array('121',$permission_section)){
       //if(in_array('453',$users_data['permission']['action'])) {

       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered test_name_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Test Name </th> 
                    <th> Status </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
          <?php 
          if(in_array('454',$permission_action) || in_array('121',$permission_section)){
          //if(in_array('454',$users_data['permission']['action'])) {
          ?>
  			<button class="btn-update" id="modal_add">
  				<i class="fa fa-plus"></i> New
  			</button>
          <?php } ?>
          <?php 
if(in_array('456',$permission_action) || in_array('121',$permission_section)){
          //if(in_array('456',$users_data['permission']['action'])) {
          ?>
  			<button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-trash"></i> Delete
  			</button>
          <?php } ?>
          <?php 
if(in_array('453',$permission_action) || in_array('121',$permission_section)){
          //if(in_array('453',$users_data['permission']['action'])) {
          ?>
               <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
          <?php } ?>
          <?php //if(in_array('457',$users_data['permission']['action'])) {
            if(in_array('457',$permission_action) || in_array('121',$permission_section)){
          ?>
  			<button class="btn-exit" onclick="window.location.href='<?php echo base_url('test_name/archive'); ?>'">
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
  $('#selectAll').on('click', function () 
  { 
  if ($(this).hasClass('allChecked')) 
  {
    $('.checklist').prop('checked', false);
  } 
  else 
  {
    $('.checklist').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
  })
 function delete_test_name(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('test_name/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
   $('#load_add_test_name_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
</script> 
<!-- Confirmation Box -->

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

<!-- Confirmation Box end -->
<div id="load_add_test_name_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>