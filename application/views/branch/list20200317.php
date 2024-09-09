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
<style>
.formcontainer
{
text-align:left;
width:330px;
border-top: 1px solid;
border-bottom: 1px solid;
padding:10px;
margin: auto;
}
.para
{
margin-bottom: 10px;
}  
</style>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>pwdwidget.js"></script>   

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript">
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('branch/advance_search/'); ?>",
      type: "post",
      data: $('#search_form').serialize(),
      success: function(result) 
      {
        if(vals!='1')
        {
          $('#load_add_modal_popup').modal('hide'); 
          reload_table();       
          $('#overlay-loader').hide();
        } 
      }
    }); 
} 
  form_submit('1');
var save_method; 
var table;
<?php
if(in_array('1',$users_data['permission']['action']))
{
?>
$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '100',
        "ajax": {
            "url": "<?php echo base_url('branch/ajax_list')?>",
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
<?php  
}
?>


$(document).ready(function(){
var $modal = $('#load_add_branch_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'branch/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_branch(id)
{
  var $modal = $('#load_add_branch_modal_popup');
  $modal.load('<?php echo base_url().'branch/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function renew_mail(id)
{
  var $modal = $('#load_add_branch_modal_popup_mail');
  $modal.load('<?php echo base_url().'branch/renew_mail/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}



function view_branch(id)
{
  var $modal = $('#load_add_branch_modal_popup');
  $modal.load('<?php echo base_url().'branch/view/' ?>'+id,
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
                      url: "<?php echo base_url('branch/deleteall');?>",
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
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">

    <?php if($users_data['users_role']==1){ ?>
    
    <form name="search_form"  id="search_form"> 

    <div class="row">
      
      <!--<div class="col-sm-4">
        
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Branch Type</label></div>
          <div class="col-xs-7">
            <input name="branch_type" value="1" id="branch_type" <?php if($form_data['branch_type']==1){ echo 'checked="checked"'; } ?>  type="radio" onclick="return form_submit();" > Live
            <input name="branch_type" value="0" <?php if($form_data['branch_type']==0){ echo 'checked="checked"'; } ?> id="branch_type"  type="radio" onclick="return form_submit();" > Demo
          </div>
        </div>
      </div>--> <!-- 4 -->
     <div class="col-sm-4">
        
        <div class="row m-b-5">
          <div class="col-xs-3"><label>Status</label></div>
          <div class="col-xs-9">
            <input name="status" value="1" id="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>  type="radio" onclick="return form_submit();" > Live
            <input name="status" value="0" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status"  type="radio" onclick="return form_submit();" > Expired
            
            <input name="status" value="2" <?php if($form_data['status']==2){ echo 'checked="checked"'; } ?> id="status"  type="radio" onclick="return form_submit();" > Offline
            
          </div>
        </div>
      </div> <!-- 4 -->
    </div> <!-- row -->
  </form>
  <?php } ?>  	 
        <form>
           <?php
            if(in_array('1',$users_data['permission']['action']))
            {
            ?>
           <!-- bootstrap data table -->
            <table id="table" class="table table-striped table-bordered branch_list" cellspacing="0" width="100%">
                <thead class="bg-theme">
                    <tr>
                        <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th>
                        <th> ID </th>
                        <th> Name </th>
                        <th> City </th>
                        <th> Contact No. </th>
                        <?php
    if($users_data['users_role']==1)
    {
                        ?> 
                        <th> Module Alloted </th>
                        <?php
                        }
                        ?> 
                        <th> Status </th>
                        <th> Last Login Time </th> 
                        <?php if($users_data['users_role']==1){ ?>
                        <th> Valid From </th>
                        <th> Valid To </th>
                        <?php  } ?>
                        <th> Action </th>
                    </tr>
                </thead>  
            </table>
            <?php
            }
            ?>
        </form>

   </div> <!-- close -->


  	<div class="userlist-right">
  		<div class="btns">
      <?php
       if(in_array('2',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="modal_add">
            <i class="fa fa-plus"></i> New
          </button>
        <?php
       }

       if(in_array('4',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
            <i class="fa fa-trash"></i> Delete
          </button>
        <?php
       }

       if(in_array('1',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" onclick="reload_table()">
            <i class="fa fa-refresh"></i> Reload
          </button>
        <?php
       } 

       if(in_array('5',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-exit" onclick="window.location.href='<?php echo base_url('branch/archive'); ?>'">
          <i class="fa fa-archive"></i> Archive
        </button>
        <?php
       }
      ?> 
  			
        
  			
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
/*function form_submit()
{
  $('#search_form').delay(200).submit();
}

$("#search_form").on("submit", function(event) { 
  event.preventDefault();  
   
  $.ajax({
    url: "<?php //echo base_url('branch/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });
});*/

 function delete_branch(branch_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('branch/delete/'); ?>"+branch_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
   $('#load_add_branch_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 

$(document).ready(function() {
   $('#load_add_branch_modal_popup_mail').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
</script> 
<!-- Confirmation Box -->
  <div id="confirm" class="modal fade dlt-modal">
    <div class="modal-dialog br-dlt-model">
      <div class="modal-content">
        <div class="modal-header">
            <h4>Are you sure?</h4>
        </div>
        <!-- <div class="modal-body"></div> -->
        <div class="modal-footer">
          <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
          <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
        </div>
      </div>
    </div>  
  </div>
<!-- Confirmation Box end -->
<div id="load_add_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_branch_modal_popup_mail" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</div><!-- container-fluid -->
</body>
</html>