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
if(in_array('64',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('balance_clearance_history/ajax_list')?>",
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
var $modal = $('#load_add_disease_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'balance_clearance_history/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function update_payment(id)
{
  var $modal = $('#load_add_disease_modal_popup');
  $modal.load('<?php echo base_url().'balance_clearance_history/edit/' ?>'+id,
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
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
         
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>

  <form name="search_form"  id="search_form"> 

    <div class="row">
      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-4"><label> From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>  
      </div> <!-- 4 -->

      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-4"><label> To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div> 
      </div>

      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-3"><label>Department</label></div>
          <div class="col-xs-7">
             <select name="department" id="department" onchange="return form_submit();">
               <option value="">Select Department</option>
               <?php 
               $users_data = $this->session->userdata('auth_users');  
               $this->session->unset_userdata('balance_search');  
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
              ?>

              <?php
              if(in_array('85',$permission_section))
              {
              ?>
              <option id="2" value="2">OPD</option>
              <?php  
              } 
              ?>

              <?php
              if(in_array('387',$permission_section))
              {
              ?>
              <option id="14" value="14">Day Care</option>
              <?php  
              } 
              ?>

              <?php
              if(in_array('145',$permission_section))
              {
              ?>
              <option id="1" value="1">Pathology</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('60',$permission_section))
              {
              ?>
              <option id="3" value="3">Medicine</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('179',$permission_section))
              {
              ?>
              <option id="7" value="7">Vaccination</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('151',$permission_section))
              {
              ?>
              <option id="4" value="4">OPD Billing</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('121',$permission_section))
              {
              ?>
              <option id="5" value="5">IPD</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('134',$permission_section))
              {
              ?>
              <option id="8" value="8">OT</option>
              <?php  
              } 
              ?>


              <?php
              if(in_array('262',$permission_section))
              {
              ?>
              <option id="10" value="10">Blood Bank</option>
              <?php  
              } 
              ?>

              <?php
              if(in_array('350',$permission_section))
              {
              ?>
              <option id="13" value="13">Ambulance</option>
              <?php  
              } 
              ?> 
               
             </select>
          </div>
        </div> 
      </div>  

      <div class="col-sm-3">  
        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
      </div> <!-- 4 --> 
 
    </div>  
         
    </form>

    <form>
       <?php if(in_array('231',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered disease_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> S. No. </th> 
                    <th>  UH ID </th> 
                    <th>  Patient Name </th> 
                    <th> Mobile No. </th> 
                    <th> Payment Date </th>
                    <th> Payment Mode </th>
                    <th> Amount </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
     <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">
                 
               <?php if(in_array('231',$users_data['permission']['action'])) {
               ?> 

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?> 
        <button class="btn-update" onclick="window.location.href='<?php echo base_url('balance_clearance'); ?>'">
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
  var department = $('#department').val();
 var emergency_booking = $("input[name='emergency_booking']:checked").val();
 //var emergency_booking = document.getElementsByName('emergency_booking'); 
 //alert(emergency_booking);
  $.ajax({
         url: "<?php echo base_url('balance_clearance_history/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date, department:department} ,
         success: function(result)
         { 
            reload_table(); 
         }
      });      
 }

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>balance_clearance_history/reset_search/", 
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


form_submit();
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
    
    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_disease_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
<script type="text/javascript">
  var $modal = $('#load_import_modal_popup');
        $('#open_model').on('click', function(){
        //  alert();
      $modal.load('<?php echo base_url().'disease/import_disease_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      });
</script>
</body>
</html>