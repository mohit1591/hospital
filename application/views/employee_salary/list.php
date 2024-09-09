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
if(in_array('203',$users_data['permission']['action']))
{
?>
$(document).ready(function() {
    table = $('#table').DataTable({
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('employee_salary/ajax_list')?>",
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


 $(document).ready(function (){

 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
 
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('employee_salary');?>'; 
    }) ;
   
       
  <?php }?>
 });

function edit_employee_salary(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'employee_salary/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

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
     allemployee_salary_delete(allVals);
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
                      url: "<?php echo base_url('employee_salary/deleteall');?>",
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
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    	
    <!-- // -->
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
    <div class="row m-b-5">
         <div class="col-md-12">
         <form name="search_form_emplist"  id="search_form_emplist"> 
              <div class="row">
               
                   <div class="col-md-4">
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
                   </div> <!-- 6 -->
              </div> <!-- innerRow -->

              </form>
         </div> <!-- 12 -->
    </div> <!-- row -->
 <?php endif;?>


        <form>
           <?php
            if(in_array('203',$users_data['permission']['action']))
            {
            ?>
           <!-- bootstrap data table -->
            <table id="table" class="table table-striped table-bordered employee_salary_list" cellspacing="0" width="100%">
                <thead class="bg-theme">
                    <tr>
                        <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th>
                      <!--   <th> Voucher No </th> -->
                        <th> Expense Date</th>
                       
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

   </div> <!-- close -->


  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
      <?php
       if(in_array('204',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="modal_add">
            <i class="fa fa-plus"></i> New
          </button>
        <?php
       }

       if(in_array('206',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
            <i class="fa fa-trash"></i> Delete
          </button>
        <?php
       }

       if(in_array('203',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-update" onclick="reload_table()">
            <i class="fa fa-refresh"></i> Reload
          </button>
        <?php
       } 

       if(in_array('207',$users_data['permission']['action']))
       {
        ?>
          <button class="btn-exit" onclick="window.location.href='<?php echo base_url('employee_salary/archive'); ?>'">
          <i class="fa fa-archive"></i> Archive
        </button>
        <?php
       }
      ?> 
      
             <a href="<?php echo base_url('employee_salary/salary_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('employee_salary/salary_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('employee_salary/salary_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("employee_salary/salary_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
  			
        
  			
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
$(document).ready(function(){
   form_submit();
});
  function form_submit()
{
  $('#search_form_emplist').delay(200).submit();
}

$("#search_form_emplist").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('employee_salary/advance_search/'); ?>",
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

 function delete_employee_salary(employee_salary_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('employee_salary/delete/'); ?>"+employee_salary_id, 
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


<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_expense_category_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>



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

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("expenses/print_expense_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 