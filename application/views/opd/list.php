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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#opd_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'opd/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});

function edit_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/view/' ?>'+id,
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
                      url: "<?php echo base_url('opd/deleteall');?>",
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
  $checkbox_list = get_checkbox_coloumns('1');
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
                        <td><input type="checkbox" class="tog-col" <?php if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status)) 
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
<!-- //////////////////////[ End Left side bar ]////////////////////// -->


<section class="userlist">
  <div class="userlist-box">

<?php if(isset($user_role) && $user_role!=4 && $user_role!=3)
 { ?>
    <form name="search_form"  id="search_form"> 

    <div class="row">
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>OPD No.</label></div>
          <div class="col-xs-7">
            <input name="booking_code" class="m_input_default" id="booking_code" onkeyup="return form_submit();"  value="<?php echo $form_data['booking_code']?>" type="text" autofocus>
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Mobile No.</label></div>
          <div class="col-xs-7">
            <input name="mobile_no" value="<?php echo $form_data['mobile_no']?>" id="mobile_no" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text">
          </div>
        </div>
        
         <div class="row m-b-5">
          <div class="col-xs-5"><label>Specialization</label></div>
          <div class="col-xs-7">
            <select name="specialization_id" id="specialization_id" onChange="return form_submit();">
              <option value="">Select Specialization</option>
              <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specializationlist)
                {
                  ?>
                    <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
        </div>
        
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Patient Name</label></div>
          <div class="col-xs-7">
            <input name="patient_name" value="<?php echo $form_data['patient_name']?>" id="patient_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
          </div>
        </div>
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
             <?php if(in_array('1',$permission_section)){ ?> 
          <div class="row m-b-5">
          <div class="col-xs-5"><label>Branch</label> </div>
          <div class="col-xs-7">
              
       
       
            <select name="branch_id" id="branch_id" class="m_input_default" onchange="return form_submit();">
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

<?php } else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } ?>

     
      <div class="row m-b-5">
          <div class="col-xs-5"><label> Booking Type</label></div>
          <div class="col-xs-7">
              <input name="emergency_booking"  id="emergency_booking" onclick="return form_submit();" value="3" type="radio" <?php if($form_data['emergency_booking']=='3'){ echo 'checked'; } ?> > Normal
            <input name="emergency_booking"  id="emergency_booking" onclick="return form_submit();" value="4" type="radio" <?php if($form_data['emergency_booking']=='4'){ echo 'checked'; } ?>> Emergency
          </div>
        </div>
        
      
      </div> <!-- 4 -->

      <div class="col-sm-4">
          <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
           <!--<a class="btn-custom" id="reset_date" onclick="clear_form_elements(this.form);"><i class="fa fa-refresh"></i> Reset</a>-->
        <a href="javascript:void(0)" class="btn-a-search" id="opd_adv_search">
              <i class="fa fa-cubes" aria-hidden="true"></i> 
              Advance Search
            </a>
         
      </div> <!-- 4 -->
    </div> <!-- row -->
  
         
    </form>

    <?php } ?>
    
    <form>
       <?php
          if(in_array('529',$users_data['permission']['action'])) 
          {
          ?>
       <!-- bootstrap data table -->
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <?php $data= get_setting_value('PATIENT_REG_NO'); 
                    if(!empty($data) && isset($data)) 
                    { ?>
                    <th><?php echo $data; ?></th>
                     <?php } else { ?>
                     <th>Patient Reg No.</th>
                     <?php  } ?>
                    <th> OPD No. </th> 
                    <th> Patient Name </th> 
                    
                    <th> Appointment Date </th>
                    <th> Doctor Name </th> 
                    <th> Booking Date </th>  
                    <th> Booking Status </th>
                    <!-- added on 11-Feb-2018 -->
                    
                     <th>Mobile No.</th>
                     <th>Gender</th>
                     <th>Address</th>
                     <th>Father Name</th>
                     <th>Patient Email</th>
                     <th>Insurance Type</th>
                     <th>Insurance Company Name</th>

                     <th>Source From</th>
                     <th>Disease</th>
                     <th>Referred Doctor/Hospital</th>
                     <th>Specialization</th>
                     <th>Consultant</th>
                     <th>Booking Time</th>
                     <th>Validity Date</th>
                     <th>Patient Status</th>
                     <th>Next Appointment</th>
                     <th>Total Amount</th>
                     <th>Net Amount</th>
                     <th>Paid Amount</th>
                     <th>Discount</th>
                      <th> Policy No. </th>                <!-- added on 11-Feb-2018 -->
                     <th> Action </th>
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>


    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
        <div class="fixed">
  		<div class="btns opd_booking_list_right_btns">
               <?php if(in_array('523',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd/booking'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
              

               <a href="<?php echo base_url('opd/opd_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('opd/opd_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('opd/opd_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
              
              <?php if(in_array('2058',$users_data['permission']['action'])) 
               {
               ?>
                <a href="<?php echo base_url('opd/sample_import_opd_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample(.xls)
                </a>
              <?php }?>
              
              <?php if(in_array('2059',$users_data['permission']['action'])) 
               {
               ?>
                <a id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import(.xls)
                </a>
              <?php }?>

              <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return openPrintWindow('< ?php echo base_url("opd/opd_print"); ?>', 'windowTitle', 'width=820,height=600');">
              <i class="fa fa-print"></i> Print
              </a> -->
              <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("opd/opd_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
  			
               <?php if(in_array('525',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                         <i class="fa fa-trash"></i> Delete
                    </button>
               <?php } ?> 
               <?php if(in_array('529',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('526',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd/archive'); ?>'">
                         <i class="fa fa-archive"></i> Archive
                    </button>
               <?php } ?>
        
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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

<script>
function update_patient_arrival(id)
{
  var $modal = $('#load_add_eye_app_type');
  $modal.load('<?php echo base_url().'opd/update_patient_status/' ?>'+id,
  {
  },
  function(){
  $modal.modal('show');
  });
}



  $(document).ready(function()
  {
      var $modal = $('#load_opd_import_modal_popup');
        $('#open_model').on('click', function(){
        $modal.load('<?php echo base_url().'opd/import_opd_excel' ?>',
        { 

        },
      function(){
        $modal.modal('show');
        });

      });
  })
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>opd/reset_search/", 
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
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#booking_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>opd/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
    } 
  }); 
}


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



function form_submit(vals)
{ 
    var start_date = $('#start_date_patient').val();
    var end_date = $('#end_date_patient').val();
    var branch_id = $('#branch_id').val();
    var specialization_id = $('#specialization_id').val();
    var mobile_no = $('#mobile_no').val();
    var booking_code = $('#booking_code').val();
    var patient_name = $('#patient_name').val();
    var emergency_booking = $("input[name='emergency_booking']:checked").val();
 
  $.ajax({
         url: "<?php echo base_url('opd/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date,branch_id:branch_id,emergency_booking:emergency_booking,specialization_id:specialization_id,mobile_no:mobile_no,booking_code:booking_code,patient_name:patient_name} ,
         success: function(result)
         { 
            if(vals!="1")
            {
               reload_table(); 
            }
         }
      });      
 }

form_submit(1);
var save_method; 
var table;
<?php
if(in_array('529',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('opd/ajax_list')?>",
            "type": "POST",
            "deferRender": true,
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

}); 
<?php } ?>
$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('opd');?>'; 
    }); 
       
  <?php }?>
 });

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && isset($_GET['type']) && $_GET['type']==1){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('opd');?>'; 
    }); 
       
  <?php }?>
 });

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

  function confirm_booking(id)
  {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'opd/confirm_booking/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

 function delete_opd_booking(booking_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('opd/delete_booking/'); ?>"+booking_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }


$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script> 
<!-- Confirmation Box -->
    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
              <?php 
              if($users_data['parent_id']=='60')
                {
                    $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>
                    <?php 
                }
              
              ?>
            

                <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm_billing_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
             <!--<a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>-->
             <?php 
              if($users_data['parent_id']=='60')
                {
                    $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php
                }
                elseif($users_data['parent_id']=='64')
                {
                  $opd_booking_id = $this->session->userdata('opd_booking_id');
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_blank_prescriptions/".$opd_booking_id."/".$users_data['parent_id']); ?>');">Print</a>
                    <?php  
                }
                else
                {
                    ?>
                    <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>
                    <?php 
                }
              
              ?>
            <!-- <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("opd/print_booking_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->
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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_eye_app_type" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_opd_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->



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
            window.location = "<?php echo base_url(); ?>opd";
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

$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })
                              
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
                            window.location = "<?php echo base_url(); ?>opd";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>opd";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }
    
    function upload_prescription(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'prescription/upload_prescription/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
function upload_eye_prescription(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'eye/add_prescription/upload_eye_prescription/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }

  function upload_pediatrician_prescription(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'pediatrician/add_prescription/upload_pediatrician_prescription/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }

 
  
  function upload_dental_prescription(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'dental/dental_prescription/upload_dental_prescription/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
  
  function dilated(id)
{
     $.ajax({
        url: "<?php echo base_url(); ?>opd/dilated_start",
        type: "POST",
        data: { booked_id:id },
        success: function(result) 
        { 
            flash_session_msg(result); 
            setTimeout(function () {
              window.location = "<?php echo base_url(); ?>opd";
          }, 1300); 
        }
      });
}
function upload_opd_prescription(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'opd/upload_opd_prescription/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
// Added By Nitin Sharma 02/02/2024   
  function view_test_report(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'opd/view_test_report/' ?>'+id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }

  function print_label(id)
  {
    var $modal = $('#load_add_opd_label_summary_print_modal_popup');
    $modal.load('<?php echo base_url().'opd/print_template/'; ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
// Ended By Nitin Sharma 02/02/2024
</script>
<!-- function to reload columns -->


</body>
</html>
<div id="load_add_opd_label_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>