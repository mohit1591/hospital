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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('210',$users_data['permission']['action']))
{
?>
$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('canteen/expenses/ajax_list')?>",
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


/*$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('< ?php echo base_url().'canteen/expenses/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});



});
*/

$(document).ready(function(){
 form_submit();
var $modal = $('#load_add_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'canteen/expenses/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'canteen/expenses/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});
function edit_expenses(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'canteen/expenses/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_expenses(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'canteen/expenses/view/' ?>'+id,
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
     if(allVals=="")
     {
         alert('Select atleast one checkbox');
     }
     allexpenses_delete(allVals);
} 

function allexpenses_delete(allVals)
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
                      url: "<?php echo base_url('canteen/expenses/deleteall');?>",
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

 $(document).ready(function (){

 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
 
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('canteen/expenses');?>'; 
    }) ;
   
       
  <?php }?>
 });

</script>
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    <form name="search_form_list"  id="search_form_list"> 

    	 <!-- // -->
      <div class="row m-b-5">
           <div class="col-md-12">
                <div class="row">
                     <div class="col-md-4">
                          <div class="row">
                            <div class="col-xs-4"><label>From Date:</label></div>
                            <div class="col-xs-8">
                              <input id="start_date_exp" name="start_date" class="datepicker start_datepicker" type="text" value="<?php echo $form_data['start_date']?>">
                            </div>
                          </div>
                     </div> <!-- 4 -->
                     <div class="col-md-4">
                          <div class="row">
                            <div class="col-xs-4"><label> To Date:</label></div>
                            <div class="col-xs-8">
                              <input name="end_date" id="end_date_exp" class="datepicker datepicker_to end_datepicker" value="<?php echo $form_data['end_date']?>" type="text">
                            </div>
                          </div>
                     </div> <!-- 4 -->
                     <div class="col-md-4">
                          
                     </div> <!-- 4 -->

        <div class="col-sm-4">
            <!-- <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a> -->
            <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a>
        </div> <!-- 4 -->
<?php 
 $users_data = $this->session->userdata('auth_users'); 

              if (array_key_exists("permission",$users_data)){
              $permission_section = $users_data['permission']['section'];
              $permission_action = $users_data['permission']['action'];
              }
              else{
              $permission_section = array();
              $permission_action = array();
              }

if(in_array('1',$permission_section)): ?> 
        <div class="col-sm-4">
          <div class="row m-t-3">
          <div class="col-xs-4"> <label>Select Branch :</label></div>
            <div class="col-xs-8">
            <?php  
             
              //print_r($permission_action);

              $new_branch_data=array();
              $users_data = $this->session->userdata('auth_users');
              $sub_branch_details = $this->session->userdata('sub_branches_data');
              $parent_branch_details = $this->session->userdata('parent_branches_data');


              if(!empty($users_data['parent_id'])){
              $new_branch_data['id']=$users_data['parent_id'];

              $users_new_data[]=$new_branch_data;
              $merg_branch= array_merge($users_new_data,$sub_branch_details);

              $ids = array_column($merg_branch, 'id'); 
              $branch_id = implode(',', $ids); 
              $option= '<option value="'.$branch_id.'">All</option>';
              }

              ?>
                  
                    <select name="branch_id" id="branch_id" onchange="return form_submit();">
              <?php echo $option ;?>
              <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
              <?php 
              if(!empty($sub_branch_details)){
              $i=0;
              foreach($sub_branch_details as $key=>$value){
              ?>
              <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
              <?php 
              $i = $i+1;
              }

              }

              ?> 
            </select>
            
            </div>
          </div>
        </div> <!-- 4 -->
        <?php endif;?>


        <div class="col-sm-4">
          <div class="row m-t-3">
          <div class="col-xs-4"> <label>Expense Type:</label></div>
            <div class="col-xs-8">
                 <input  onkeyup="return form_submit();" name="expense_type" id="expense_type" value="<?php echo $form_data['expense_type']?>" type="text">
            </div>
          </div>
        </div>
      </div>
 <!-- <div class="col-sm-4">


<a class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a>
</div> -->
                </div> <!-- innerRow -->
           </div> <!-- 12 -->
        </form>
      </div> <!-- row -->

      <div class="col-md-11">
        <form>
           <?php
            if(in_array('210',$users_data['permission']['action']))
            {
            ?>
           <!-- bootstrap data table -->
            <table id="table" class="table table-striped table-bordered expenses_list_branch" cellspacing="0" width="100%">
                <thead class="bg-theme">
                    <tr>
                        <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th>
                        <th> Voucher No. </th>
                        <th> Expense Date</th>
                        <th> Expense Type </th>
                        <th> Paid Amount </th> 
                        <th> Payment Mode </th> 
                        <th> Created Date </th> 
                        <th> Action </th>
                    </tr>
                </thead>  
            </table>
            <?php
            }
            ?>
        </form>
     </div>
  <div class="col-md-1">
  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
      <?php
       if(in_array('211',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="modal_add">
            <i class="fa fa-plus"></i> New
          </button>
        <?php
       }?>

         <a href="<?php echo base_url('canteen/expenses/expenses_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('canteen/expenses/expenses_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('canteen/expenses/pdf_expenses'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("canteen/expenses/print_expenses"); ?>');"> <i class="fa fa-print"></i> Print</a>

      <?php if(in_array('213',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
            <i class="fa fa-trash"></i> Delete
          </button>
        <?php
       }

       if(in_array('210',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" onclick="reload_table()">
            <i class="fa fa-refresh"></i> Reload
          </button>
        <?php
       } 

       if(in_array('214',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-exit" onclick="window.location.href='<?php echo base_url('canteen/expenses/archive'); ?>'">
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
  	</div> 
  </div>
  	<!-- right -->
 
  <!-- ccanteen/expenses-rslt close -->

  


  
</section> <!-- ccanteen/expenses -->
<?php
$this->load->view('include/footer');
?>

<script>  
$(document).ready(function(){
   form_submit();
});
  function form_submit()
{
  $('#search_form_list').delay(200).submit();
}

$("#search_form_list").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('canteen/expenses/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
});

$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });


 function delete_expenses(expenses_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('canteen/expenses/delete/'); ?>"+expenses_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 function reset_search()
  { 
    $('#start_date_exp').val('');
    $('#end_date_exp').val('');
    $('#branch_id').val('');
    $.ajax({url: "<?php echo base_url(); ?>canteen/expenses/reset_search/", 
      success: function(result)
      { 
            
        //document.getElementById("search_form").reset(); 
        reload_table();
      } 
    }); 
  } 
 
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
          <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
        </div>
      </div>
    </div>  
  </div>
<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_expense_category_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_expense_category_modal_popup" class="modal fade z-index" role="dialog" data-backdrop="static" data-keyboard="false"></div>



</div><!-- container-fluid -->
</body>
</html>


<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_medicine/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("canteen/expenses/print_expense_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 
<div id="load_add_ven_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>

