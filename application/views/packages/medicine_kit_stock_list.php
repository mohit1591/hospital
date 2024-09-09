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

 <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->

<script type="text/javascript">
var save_method; 
var table;
<?php
//if(in_array('586',$users_data['permission']['action'])) 
//{
?>
$(document).ready(function() { 
  form_submit();
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('packages/medicine_kit_ajax_list')?>",
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
<?php //} ?>


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#packages_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'packages/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_packages(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'packages/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_packages(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'packages/view/' ?>'+id,
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
                      url: "<?php echo base_url('packages/deleteall');?>",
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

 function reset_search()
  { 
    $('#start_date').val('');
    $('#end_date').val('');
    

    $.ajax({url: "<?php echo base_url('packages/reset_search');?>", 
      success: function(result)
      { 
        reload_table();
        $("#sub_branch_id").val("");
      } 
    }); 
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
    <form id="new_search_form">
      
    <div class="row m-b-5">
         <div class="col-sm-3">
              <!-- <div id="child_branch" class="patient_sub_branch"></div> -->
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
          <?php if(in_array('1',$permission_section)): ?> 
        <label>Branch</label>
            <select name="branch_id" class="m_input_default" id="branch_id" onchange="return form_submit();">
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
          <?php endif;?>
         </div>
         <div class="col-sm-3">
              <label>form Date</label>
              <input type="text" name="start_date" id="start_date" class="datepicker datepicker_from m_input_default" value="" onkeyup="return form_submit();">
         </div>
         <div class="col-sm-3">
              <label>To Date</label>
              <input type="text" name="end_date" id="end_date" value="" class="datepicker datepicker_to m_input_default"  onkeyup="return form_submit();">
         </div>
         <div class="col-sm-3">
              <a class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a>
         </div>
         </div>
         

  
    
    </form>
    	 
    <form>
       <?php //if(in_array('415',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive packages_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th>Medicine Kit Name  </th>
                    <th>Amount </th> 
                    <th>Quantity </th> 
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Action</th>
                  </tr>
            </thead>  
        </table>
        <?php //} ?>
        <div style=" float: right; padding:5px; font-weight: bold;">
        <table width="200px" align="right" cellpadding="0" cellspacing="0" >
          <!--  <tr> -->
             <!--   <td><div class="m_alert_red_mark"></div></td>
               <td>Medicine minumum alert</td> -->
          <!--  </tr> -->
           <!-- <tr> -->
              <!--  <td><div class="m_alert_orange_mark"></div></td>
               <td>Medicine near to expire</td> -->
           <!-- </tr> -->
        </table>
       </div> 
    </form>


   </div> <!-- close -->





  	<div class="userlist-right">
  		<div class="btns">
      <?php if(in_array('587',$users_data['permission']['action'])) { ?>
                 <button class="btn-update h-auto" onclick="load_allot_model()">
                         <i class="fa fa-refresh"></i> Allotment To Branch
                    </button>
               <?php 
             } 
             //if(in_array('582',$users_data['permission']['action'])) {
               ?>
                    
               <?php //} ?>
               <?php //if(in_array('578',$users_data['permission']['action'])) {
               ?>
                <a href="<?php echo base_url('packages/medicine_kit_stock_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>
                <?php //} ?>
               <?php //if(in_array('579',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('packages/medicine_kit_stock_csv'); ?>" class="btn-anchor m-b-2">
                         <i class="fa fa-file-word-o"></i> CSV
                    </a>
               <?php //} ?>
               <?php //if(in_array('580',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('packages/pdf_medicine_kit_stock'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
                    </a>
               <?php // } ?>
               <?php //if(in_array('581',$users_data['permission']['action'])) {
               ?>       
                    <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return print_window_page('<?php echo base_url("packages/print_medicine_kit_stock"); ?>');">
                    <i class="fa fa-print"></i> Print
                    </a>
               <?php //} ?>
               <?php //if(in_array('415',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php //} ?>
             
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url();?>'">
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
 $(document).ready(function(){
          $("#msg").html('');
        
         
     });
 function load_allot_model()
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
     var $modal = $('#load_allot_to_branch_modal_popup');
     $modal.load('<?php echo base_url('packages/kit_allot_to_branch/'); ?>',{'medicine_kit_ids':allVals},function(){
          $modal.modal('show');
     });

   
}
 function delete_packages(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('packages/delete/'); ?>"+id, 
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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_quantity_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
function package_quantity_add(id){
     var opt = "add";
     var $modal = $('#load_add_quantity_add_modal_popup');
     $modal.load('<?php echo base_url().'packages/add_medicine_kit_quantity/' ?>'+id+'/'+opt,
     {
          //'id1': '1',
          //'id2': '2'
    },
    function(){
         
          $modal.modal('show');
    });
}




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
    url: "<?php echo base_url(); ?>packages/search_medicine_kit_stock_data/",
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
function packages_manage(id){
     window.location.href="<?php echo base_url(); ?>packages/add_medicine_kit_qty_manage/"+id;
}
/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/

  var today =new Date();
    $('.datepicker_from').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
       //alert(selected);
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $(".datepicker_to").datepicker("option", "minDate", selected);
              form_submit();
      }
    })

    $(".datepicker_to").datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          //$('.datepicker').datepicker("option", "maxDate", selected);
      }
    })
</script>
<!-- container-fluid -->
</body>
</html>