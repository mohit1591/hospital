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
 

$('#appointment_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'appointment/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});



function view_appointment(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'appointment/view/' ?>'+id,
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
                      url: "<?php echo base_url('appointment/deleteall');?>",
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
  <div class="userlist-box">
    
    <?php if($users_data['users_role']==4 || $users_data['users_role']==3)
    { 
    }
    else
    {
      ?>
    
    <form name="search_form"  id="search_form" class="search_form"> 

    <div class="row">
      <!-- ///////////////// -->
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-2"><label>Date</label></div>
          <div class="col-xs-4">
            <input id="start_date_patient" name="appointment_date" class=" start_datepicker m_input_default" type="text" value="<?php echo $form_data['appointment_date']?>">
          </div>
        </div>
        
      
      </div> <!-- 4 -->
<!-- <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button"> -->

      <!-- ///////////////// -->
     
        
       

        
          
        <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">

 
    </div> <!-- row -->
  
         
    </form>

    <?php }?>
    <form>
       <?php
       $search = $this->session->userdata('appointment_search');
       
          
          ?>
       <!-- bootstrap data table -->
       <div class="hr-scroll" style="min-height: 1px;max-height: 400px;overflow-y: scroll;">
        <table id="table" class="table table-striped table-bordered appointment_booking_list" cellspacing="0" width="100%">

            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input  type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    
                    <th> <?php
                    $search = $this->session->userdata('appointment_search');
                     echo $search['appointment_date']; ?> </th> 
                    
                    
                     <?php 
                    $users_data= $this->session->userdata('auth_users');

                    $get_schedule= get_schedule($users_data['parent_id']);
                    if(!empty($get_schedule))
                    {

                      foreach($get_schedule as $doctors)
                      { ?>
                     <th> <?php echo ucfirst($doctors->schedule_name);?> </th>
                    <?php }
                    }
                  ?>          
                     
                </tr>
            </thead> 
            <tbody>
              
                <?php
                $range = hoursRange(28800, 75600, 60 * 10, 'h:i a' ); //for 30 min change 10 to 30

                foreach ($range as $time_data) 
                {

                  ?>
                  <tr>
                    <td>
                  <td><?php echo $time_data; ?></td>




                  <?php
                  foreach($get_schedule as $schedule_list)
                  { 
                    ?>

                    <td><?php $appointd_details = get_schedule_details($search['appointment_date'],$time_data,$schedule_list->id,1); 
                    //echo "<pre>"; print_r($appointd_details);
                    echo $appointd_details['patient_name'].'<br>'.$appointd_details['appointment_for'].'<br>'.$appointd_details['remarks'];
                    
                    
                    if(!empty($appointd_details['id']) && $appointd_details['type']==0)
                    {
                  ?>
                    <br><!--<a class="" href="javascript:void(0);" onclick="return confirm_booking('< ?php echo $appointd_details['id'];?>');" title="Confirm Appointment"><i class="fa fa-pencil"></i> Confirm </a>-->
                    
                    <a href="<?php echo base_url('dialysis_booking/add?appointment_id='.$appointd_details['id']); ?>" title="Edit Appointment"><i class="fa fa-check"></i> Confirm </a>
                    
                    <a  onclick="return cancel_appointment(<?php echo $appointd_details['id']; ?>)" href="javascript:void(0)" title="Cancel" data-url="512"><i class="fa fa-times"></i> Cancel</a>
                    <a class="" href="javascript:void(0);" onclick="return reschedule_appointment('<?php echo $appointd_details['id'];?>');" title="Reschedule Appointment"><i class="fa fa-calendar"></i> Reschedule </a>
                    
                    <a href="<?php echo base_url('dialysis_appointment/edit/'.$appointd_details['id']); ?>" title="Edit Appointment"><i class="fa fa-pencil"></i> Edit </a>
                    
                    <a  onclick="return delete_appointment(<?php echo $appointd_details['id']; ?>)" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>

                  <?php }else if(!empty($appointd_details['id']) && $appointd_details['type']==1){ ?>
                    <br><a class="" href="javascript:void(0);" title="Confirmed Appointment"> Confirmed </a>
                    
                  <?php }
                  else if($appointd_details['type']==2){ ?>
                    <br><a class="" href="javascript:void(0);" title="Confirmed Appointment"> Cancelled </a>
                  
                  <?php }
                  ?>
                  </td>


                    <?php
                  }

                  ?>

                  
                  
                </tr>
               <?php } ?>
                
            </tbody> 
        </table>
        </div>
        


    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns appointment_booking_list_right_btns">
               <?php if(in_array('523',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('dialysis_appointment/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
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
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script>
function form_submit(vals)
{
  $.ajax({
    url: "<?php echo base_url('appointment/advance_search/'); ?>",
    type: "post",
    data: $(".search_form").serialize(),
    success: function(result) 
    { 
        location.reload();
         //reload_table();  
            
    }
  });
}



function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>appointment/reset_search/", 
      success: function(result)
      { 
        
        location.reload();
      } 
  }); 

}
  

function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#appointment_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>appointment/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();

    } 
  }); 
}

$('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print') { ?>
  $('#confirm_print_booking').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
       // window.location.href='<?php echo base_url('appointment');?>'; 
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
    $modal.load('<?php echo base_url().'dialysis_appointment/confirm_booking/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
  

  
  

 function delete_appointment(appointment_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('dialysis_appointment/delete_appointment/'); ?>"+appointment_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    
                     //reload_table();
                     location.reload();
                 }
              });
    });     
 }
 
 function cancel_appointment(appointment_id)
 {    
    $('#confirm_cancel').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#canceles', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('dialysis_appointment/cancel_appointment/'); ?>"+appointment_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    location.reload();
                 }
              });
    });     
 }
  function reschedule_appointment(id)
  {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'dialysis_appointment/reschedule_appointment/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
 


 
 $('.start_datepicker').datepicker({
    dateFormat: 'dd-mm-yy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      //var start_data = $('.start_datepicker').val();
      //$('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    dateFormat: 'dd-mm-yy',
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });

 

$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script>

<div id="confirm_print_booking" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("opd/print_booking_report"); ?>');">Print</a>

           
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 



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
    
     <div id="confirm_cancel" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="cancel">Confirm</button>
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
</div><!-- container-fluid -->
<!-- function to reload columns -->
</body>
</html>