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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

<script type="text/javascript">
var save_method; 
var table; 
//unauthorise_permission('159','931');
<?php //if(in_array('931',$users_data['permission']['action'])): ?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,  
        "pageLength": '20', 
        "aaSorting": [],
        "ajax": {
            "url": "<?php echo base_url('canteen/canteen_collection_report/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
    form_submit();
}); 
<?php //endif;?>
function reload_table()
{
    table.ajax.reload(null,false);
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
      $.ajax({
         url: "<?php echo base_url('canteen/canteen_collection_report/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
  }

$(document).ready(function(){
var $modal = $('#load_advance_search_modal_popup');
$('#advance_search').on('click', function(){
$modal.load('<?php echo base_url().'canteen/canteen_collection_report/advance_search/' ?>',
{
//'id1': '1',
//'id2': '2'
},
function(){
$modal.modal('show');
});

});

});
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
    <form id="new_search_form"> 

    <div class="grp_box m-b-5">

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
      
      <div class="grp">

            <label><b>Branch:</b></label>
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
         <select name="branch_id" class="m_input_default w-130px" id="branch_id" onchange="return  get_selected_value(this.value); form_submit();">
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
      <?php endif;?>

     <?php if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
      { ?>
      <div class="grp media_float_left" id="search_box_user">
              <b>User</b> 
              <select name="employee" class="m_input_default w-130px" id="employee" onchange="return form_submit();">
                    <option value="">Select User</option>
                    <option value="<?php echo $users_data['id']; ?>">Self</option>
                  <?php 
                   
                    if(!empty($employee_list))
                    {
                      foreach($employee_list as $employee)
                      {
                        echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                      }
                    }
                  ?> 
              </select>
      </div>
      <?php } 
      else 
      {?>
      <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

<?php }?>


      <div class="grp">

            <label><b>From Date:</b></label>
            <input type="text" id="start_date" name="from_date" class="datepicker datepicker_from m_input_default w-80px" value="<?php echo $form_data['from_date']; ?>" onkeyup="return form_submit();">
      </div>

      <div class="grp">
            <label><b>To Date:</b></label>
            <input type="text" name="end_date" id="end_date" class="datepicker datepicker_to m_input_default w-80px" value="<?php echo $form_data['end_date']; ?>" onkeyup="return form_submit();">
      </div>
      <div class="grp">
            <a class="btn-custom m_text_left" id="reset_date" onClick="reset_date_search();">
              <i class="fa fa-refresh"></i> Reset
            </a>
      </div>

      <div class="grp">
            <a class="btn-custom m_text_left" id="advance_search">
              <i class="fa fa-cubes"></i> Advance Search 
            </a>

      </div> 
    </div> <!-- //row -->






       <?php //if(in_array('931',$users_data['permission']['action'])): ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered opd_collection_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> Sale No. </th> 
                    <th> Sale Date </th>  
                    <th> Patient Name </th>
                    <!-- <th> Department </th> -->
                    <th> Net Amount </th>
                    <th> Discount </th>
                   
                    <th> Paid Amount </th>
                    <th> Balance </th>
                    <th> Action </th> 
                </tr>
            </thead>  
        </table> 
        <?php //endif;?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right relative" id="example_wrapper">
      <div class="fixed">
      <div class="btns media_btns"> 
          <?php //if(in_array('560',$users_data['permission']['action'])): ?>
               <a href="<?php echo base_url('canteen/canteen_collection_report/canteen_report_excel');?>" class="btn-anchor m-b-2">
               <i class="fa fa-file-excel-o"></i> Excel
               </a>
          <?php //endif;?>
          <?php //if(in_array('561',$users_data['permission']['action'])): ?>
               <a href="<?php echo base_url('canteen/canteen_collection_report/canteen_report_csv');?>" class="btn-anchor m-b-2">
               <i class="fa fa-file-word-o"></i> CSV
               </a>
          <?php //endif;?>
         <?php //if(in_array('562',$users_data['permission']['action'])): ?>
               <a href="<?php echo base_url('canteen/canteen_collection_report/pdf_canteen_report'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
               </a>
          <?php //endif;?>
          <?php //if(in_array('563',$users_data['permission']['action'])): ?>
              <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('< ?php echo base_url("canteen/canteen_collection_report/print_medicine_report"); ?>', 'windowTitle', 'width=820,height=600');">
               <i class="fa fa-print"></i> Print
               </a> -->

              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("canteen/canteen_collection_report/print_canteen_report"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 

          <?php //endif;?>
          <?php //if(in_array('564',$users_data['permission']['action'])): ?>
               <button class="btn-update" onClick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
          <?php //endif;?> 
         
        <button class="btn-update" onClick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
      </div>
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?> 
<script type="text/javascript">
  function get_selected_value(value)
{ 
      var branch_id ='<?php echo $users_data['parent_id']; ?>';
      if(value==branch_id)
      {
        document.getElementById("search_box_user").style.display="block";
      }
      else
      {
         $('#employee').val('');
        document.getElementById("search_box_user").style.display="none";
      }
      
}
/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    $.ajax({
           url: "<?php //echo base_url('canteen/canteen_collection_report/search_data/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
    $("#end_date").datepicker({ minDate: selectedDate });
}); */

function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>canteen/canteen_collection_report/advance_search/",
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

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/


  $('.datepicker_from').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate: "+0d",
  }).on("change", function(selectedDate) 
  { 

      var start_data = $('.datepicker_from').val();
      $('.datepicker_to').datepicker('setStartDate', start_data);
      form_submit();
  });

  $('.datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {  
      form_submit();
  });



</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>