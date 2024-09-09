<?php
$users_data = $this->session->userdata('auth_users');
$user_role= $users_data['users_role'];
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

  <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<script type="text/javascript">
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('ot_booking/advance_search/'); ?>",
      type: "post",
      data: $('#search_form_list').serialize(),
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
if(in_array('807',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ot_booking/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });

    $('.tog-col').on( 'click', function (e) 
      {
        var column = table.column( $(this).attr('data-column') );
        column.visible( ! column.visible() );
      });
//form_submit();

}); 
<?php } ?>






$(document).ready(function(){
var $modal = $('#load_add_ot_booking_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'ot_booking/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});


function edit_ot_booking(id)
{
  window.location.href='<?php echo base_url().'ot_booking/edit/';?>'+id;
}


function ot_pack_detail(id,pack_id,pack_amount)
{
  var $modal = $('#load_add_ot_pack_modal_popup');
  $modal.load('<?php echo base_url().'ot_pack_detail/add/' ?>'+id+'/'+pack_id+'/'+pack_amount,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function ot_detail(id,ot_id,ot_amount)
{
  var $modal = $('#load_add_ot_detail_modal_popup');
  $modal.load('<?php echo base_url().'ot_detail/add/' ?>'+id+'/'+ot_id+'/'+ot_amount,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_ot_booking(id)
{
  var $modal = $('#load_add_ot_booking_modal_popup');
  $modal.load('<?php echo base_url().'ot_booking/view/' ?>'+id,
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
                      url: "<?php echo base_url('ot_booking/deleteall');?>",
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


<script>
  // left side bar
  $(document).ready(function(){
    $('.lsb_btns').click(function(){
      $('.leftSideBar').fadeIn(); 
    });
    $('.lsb_btns').click(function(){ 
      $('.leftSideBar').css('left','0px'); 
    });
  
  $('.toggleBtn').click(function(){
    $('.toggleBox').animate({width:'toggle'});
  });
  $('.toggleBox a').click(function(){
    $('.toggleBox').animate({width:'toggle'});
  });
  });
</script>

<?php 
  $checkbox_list = get_checkbox_coloumns('8');
  $module_id = $checkbox_list[0]->module;
?>

<!-- //////////////////////[ Left side bar ]////////////////////// -->

<?php if($users_data['emp_id']==0){  ?>
<div class="toggleBtn"><i class="fa fa-angle-right"></i></div>
<div class="toggleBox">
  <a>Exit <i class="fa fa-sign-out"></i></a>
  <form id="checkbox_data_form">
      <table class="table table-bordered table-striped table-hover">
        <tbody>
            <?php  
                $unchecked_column = [];
                foreach ($checkbox_list as $checkbox_list_data ) 
                {
                ?>
                    <tr>
                        <td><input type="checkbox" class="tog-col" <?php  if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status))
                   {  ?> checked="checked" <?php }else { $unchecked_column[] = $checkbox_list_data->coloum_id; } ?> value="<?php echo $checkbox_list_data->coloum_id; ?>" data-column="<?php echo $checkbox_list_data->coloum_id; ?>"></td>

                        <td><?php echo $checkbox_list_data->coloum_name; ?></td>
                    </tr>
                <?php
                }

                ?>
          <tr>
            <td colspan="2">
              <button type="submit" class="btn-save m-t-5"><i class="fa fa-floppy-o"></i> Save</button>
              <button onclick="reset_coloumn_record();" type="button" class="btn-save m-t-5"><i class="fa fa-refresh"></i> Reload</button>
            </td>
          </tr>
        </tbody>
      </table>
       </form>
  
</div>
<?php } else { 

  $unchecked_column = [];
  foreach ($checkbox_list as $checkbox_list_data ) 
  {

    if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status)) 
      { 
      
      } 
      else
      {
        $unchecked_column[] = $checkbox_list_data->coloum_id;
      }
  }

 } ?>


<!-- ============================= Main content start here ===================================== -->



<section class="userlist">
    <div class="userlist-box">
   <?php if(isset($user_role) && $user_role==4 || $user_role==3)
    {
    }
    else
    {?>
<form id="ot_booking_search_form">
    
    <table class="ptl_tbl m-b-5">
      <tr>
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date_ot_l" class="datepicker m_input_default"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'];?>"></td>
        <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date_ot_l" value="<?php echo $form_data['end_date'];?>" class="datepicker m_input_default"  onkeyup="return form_submit();"></td>
        <td>
        
        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
        <a href="javascript:void(0)" class="btn-custom" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> 

   

        </td>
      </tr>
     
      <tr>
      
      
        <td><?php  
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
            <?php endif;?></td>
      </tr>
      <tr> <td> <label>Searching Criteria</label>
              <select name="criteria" class="m_input_default" onchange="select_room_type(this.value);">
                <option value="">Select</option>
                <option value="1">All</option>
                <option value="2">Patient Name</option>
                <option value="3">Mobile No.</option>
                <option value="4">IPD No.</option>
              </select>
            </td>
            <td  id="room_type">
            </td>
      </tr>

    </table>


    </form>
     <?php  } ?>
    
    	 
    <form>
       <?php if(in_array('807',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered ot_summary_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> IPD No. </th>
                    <th> Patient Name </th>
                    <th> Mobile No.</th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?></th><th> Gender</th>
                    <th> Address</th>
                    <th> Booking Code</th>
                    <th> Operation Room </th>
                    <th> Package Name/Operation name </th>
                    <th> Operation Date & Time </th> 
                    <th> Referred Doctor/Hospital</th>
                    <th> Specialization </th> 
                    <th> Doctor List </th> 
                    <th> Payment Mode </th>
                    <th> Room Type </th> 
                    <th> Room No. </th> 
                    <th> Bed No. </th>
                    <th> Total Amount</th>
                    <th> Discount</th> 
                    <th> Net Amount</th>
                    <th> Paid Amount</th>
                    <th> Balance </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
          <?php if(in_array('808',$users_data['permission']['action'])) {
          ?>
  			 <button class="btn-update" onClick="window.location.href='<?php echo base_url('/ot_booking/add'); ?>'">
  				<i class="fa fa-plus"></i> New
  			</button>
          <?php } ?>
          <?php if(in_array('810',$users_data['permission']['action'])) {
          ?>
  			<button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-trash"></i> Delete
  			</button>
          <?php } ?>
           <?php if(in_array('814',$users_data['permission']['action'])) {
          ?>
           <a href="<?php echo base_url('ot_booking/ot_list_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>
              <?php } ?>
               <?php if(in_array('815',$users_data['permission']['action'])) {
          ?>
              <a href="<?php echo base_url('ot_booking/ot_list_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>
              <?php } ?>
          <?php if(in_array('816',$users_data['permission']['action'])) { ?>
              <a href="<?php echo base_url('ot_booking/pdf_ot_list'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
          <?php } ?>
                      
           <?php if(in_array('817',$users_data['permission']['action'])) {
                    ?>
              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("ot_booking/print_ot_list"); ?>');"> <i class="fa fa-print"></i> Print</a>
              <?php } ?>
         
               <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
         
          <?php if(in_array('811',$users_data['permission']['action'])) {
          ?>
  			<button class="btn-exit" onclick="window.location.href='<?php echo base_url('ot_booking/archive'); ?>'">
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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<?php
$this->load->view('include/footer');
?>

<script> 
function select_room_type(room_type){
   
  if(room_type==2){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="patient_name" placeholder="Patient Name" onkeyup="return form_submit();"/></div>');
  }
   if(room_type==3){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="mobile_no"  placeholder="Mobile No." onkeyup="return form_submit();"/></div>');
  }
   if(room_type==4){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="ipd_no" placeholder="IPD No." onkeyup="return form_submit();"/></div>');
  }
   if(room_type==1){
      $('#room_type').html('<div class="row m-b-5"><div class="col-xs-7"><input type="text" name="all" placeholder="All" onkeyup="return form_submit();"/></div>');
  }

  }

  function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>ot_booking/reset_search/", 
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
function reset_search()
{
  //alert();
  $('#start_date_ot_l').val('');
  $('#end_date_ot_l').val('');
  $.ajax({url: "<?php echo base_url(); ?>ot_booking/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}

var $modal = $('#load_add_modal_popup');
  $('#adv_search_sale').on('click', function(){

$modal.load('<?php echo base_url().'ot_booking/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
 function delete_ot_booking(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ot_booking/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

$(document).ready(function(){
	$('#load_add_ot_booking_modal_popup').on('shown.bs.modal', function(e){
		$('.inputFocus').focus();
	});
});
 


</script> 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>

  <?php
 $flash_error = $this->session->flashdata('error');
 if(isset($flash_error) && !empty($flash_error))
 {
   echo '<script> error_flash_session_msg("'.$flash_error.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>
<script>
  
  $('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ot_booking');?>'; 
    }) ;
   
       
  <?php }?>
 });

</script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_medicine/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ot_booking/print_ot_booking_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

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

<!-- Confirmation Box end -->
<div id="load_add_ot_booking_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ot_pack_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ot_detail_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>

<script>
function form_submit()
  {

    $('#ot_booking_search_form').delay(200).submit();
  }


    $("#ot_booking_search_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('#overlay-loader').show();

  $.ajax({
      url: "<?php echo base_url(); ?>ot_booking/advance_search/",
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
    $('#start_date_ot_l').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_ot_l").datepicker("option", "minDate", selected);
      }
    });

    $('#end_date_ot_l').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_ot_l").datepicker("option", "maxDate", selected);
      }
    });

    function operation_summary(ot_booking_id)
    {
      window.location.href='<?php echo base_url().'operation_summary/add/';?>'+ot_booking_id;
    }

    function print_operation_summary(ot_booking_id)
    {
     window.location.href='<?php echo base_url().'operation_summary/print_summary/';?>'+ot_booking_id; 
    }

</script>


<script type="text/javascript">
    
     $("#checkbox_data_form").on("submit", function(event) { 
      event.preventDefault(); 
      var module_id = '<?php echo $module_id; ?>'
      var sList = [];
        $('.tog-col').each(function () 
        {
            if(this.checked)
                sList.push($(this).attr("value"));
        });
        if(sList=="")
        {
            $('#no_rec').modal();
            setTimeout(function(){
            $("#no_rec").modal('hide');
            }, 1500);
        }
       $.ajax({
        url: "<?php echo base_url(); ?>opd/checkbox_list_save",
        type: "POST",
        data: {rec_id:sList, module_id:module_id},
        success: function(result) 
        { 
            flash_session_msg(result); 
            setTimeout(function () {
            window.location = "<?php echo base_url(); ?>ot_booking";
        }, 1300); 
        }
      });
    }); 




</script>
     <?php
if(!empty($unchecked_column))
{
    $implode_checked_column = implode(',', $unchecked_column);

?>
<script type="text/javascript">
    $( document ).ready(function(e) {  
            table.columns([<?php echo $implode_checked_column; ?>]).visible(false);
         } );
</script>
<?php    
}
?>


<!-- function to reload columns -->
<script type="text/javascript">
 function reset_coloumn_record()
    {
        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false, 
            })
        .one('click', '#delete', function(e)
        {
          $.ajax({
                url: '<?php echo base_url(); ?>opd/reset_coloumn_record',
                data: { 'module_id':'<?php echo $module_id ?>' },
                type: 'POST',
                success: function (data)
                    {
                        if(data.status==200)
                        {
                            flash_session_msg("Record Updated Successfully"); 
                            setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>ot_booking";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>ot_booking";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }
</script>