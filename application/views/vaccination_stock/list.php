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
if(in_array('1103',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
  form_submit();
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('vaccination_stock/ajax_list')?>",
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
var $modal = $('#load_add_modal_popup');
$('#medicine_stock_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'vaccination_stock/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_medicine_stock(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_stock/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_medicine_stock(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_stock/view/' ?>'+id,
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
                      url: "<?php echo base_url('vaccination_stock/deleteall');?>",
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
    
    $('#batch_no').val('');
    $.ajax({url: "<?php echo base_url(); ?>vaccination_stock?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
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
  <?php
  if(in_array('1103',$users_data['permission']['action'])) 
  {
  ?>
    <form id="new_search_form">
    <table class="ptl_tbl">
      <tr>
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date" class="datepicker datepicker_from" value="<?php echo $form_data['start_date'];?>" onkeyup="return form_submit();"></td>
          <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date" value="<?php echo $form_data['start_date'];?>" class="datepicker datepicker_to"  onkeyup="return form_submit();"></td>
            
        <td>
            <input value="Reset" class="btn-custom" onclick="reset_search(this.form)" type="button">
            <a href="javascript:void(0)" class="btn-custom" id="adv_search_stock"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> 

          

        </td>
      </tr>
      <tr><td><label>Batch Number</label>
            <input type="text" name="batch_no" id="batch_no" value="<?php echo $form_data['batch_no'];?>"  onkeyup="return form_submit();" autofocus=""></td>
          <td>
           
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
        <label>Select Branch</label>
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
          <?php endif;?>
          </td>

            </tr>

            <tr><td><label>According To</label>
            <select  onchange="return form_submit();" name="type"><option value="">Select Type</option><option value="1" selected> Batch </option><option value="2"> Vaccination </option></select></td></tr>
     
    </table>


    </form>
    <?php } ?>
       
    <form>
       <?php if(in_array('1103',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_stock_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Vaccination Name </th>
                    <th> Vaccination Company </th>  
                    <th> Packing </th>
                    <th> Batch No.</th> 
                    <th> Barcode</th> 
                    <th> Quantity</th>
                    <th> Expiry Date</th>  
                    <th> Rack No. </th> 
                    <th> Min Alert</th>  
                    <!-- <th> MRP</th>   -->
                 <!--    <th> Purchase Rate</th> -->
                    <th> Created Date</th>
                    <th> Action</th>
                  </tr>
            </thead>  
        </table>
        <?php } ?>
        <div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;">
        <table width="200px" align="right" cellpadding="0" cellspacing="0" >
           <tr>
               <td><div class="m_alert_red_mark"></div></td>
               <td>Vaccination minimum alert</td>
           </tr>
           <tr>
               <td><div class="m_alert_orange_mark"></div></td>
               <td>Vaccination near to expire</td>
           </tr>
        </table>
       </div> 
    </form>


   </div> <!-- close -->





    <div class="userlist-right">
      <div class="btns">
               <?php if(in_array('1108',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update h-auto" onclick="load_allot_model()">
                         <i class="fa fa-refresh"></i> Allotment To Branch
                    </button>
               <?php } ?>
               <?php if(in_array('1104',$users_data['permission']['action'])) {
               ?>
                <a href="<?php echo base_url('vaccination_stock/vaccination_stock_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>
                <?php } ?>
               <?php if(in_array('1105',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('vaccination_stock/vaccination_stock_csv'); ?>" class="btn-anchor m-b-2">
                         <i class="fa fa-file-word-o"></i> CSV
                    </a>
               <?php } ?>
               <?php if(in_array('1106',$users_data['permission']['action'])) {
               ?>
                    <a href="<?php echo base_url('vaccination_stock/pdf_vaccination_stock'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
                    </a>
               <?php } ?>
               <?php if(in_array('1107',$users_data['permission']['action'])) {
               ?>       
                    <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('< ?php echo base_url("vaccination_stock/print_medicine_stock"); ?>', 'windowTitle', 'width=820,height=600');">
                    <i class="fa fa-print"></i> Print
                    </a> -->

                    <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("vaccination_stock/print_vaccination_stock"); ?>');"> <i class="fa fa-print"></i> Print</a>
               <?php } ?>
               <?php if(in_array('1103',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
             
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
  reload_table();
  $('#selectAll').on('click', function () {
 // alert(); 
    if ($("#selectAll").hasClass('allChecked')) {

    $('.checklist').prop('checked', false);
    } else {
    $('.checklist').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
  });
});

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>vaccination_stock/reset_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        reload_table();
      } 
  }); 

}
function load_allot_model()
{
     $('#table').dataTable();

     var allVals = [];
     var batchNo= [];
     $(':checkbox').each(function() 
     {
          if($(this).prop('checked')==true)
          {
               allVals.push($(this).val());
               batchNo.push($(this).attr('name'));
          } 
     });
     var $modal = $('#load_allot_to_branch_modal_popup');
     $modal.load('<?php echo base_url('vaccination_stock/allotement_to_branch/'); ?>',{'vaccine_ids':allVals,'batch_no':batchNo},function(){
          $modal.modal('show');
     });

   
}
 function delete_medicine_stock(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('vaccination_stock/delete/'); ?>"+id, 
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
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_stock').on('click', function(){
  //  alert();
$modal.load('<?php echo base_url().'vaccination_stock/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});


 /*function openPrintWindow(url, name, specs) {
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
*/

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>vaccination_stock/advance_search/",
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