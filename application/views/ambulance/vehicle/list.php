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
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2081',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ambulance/vehicle/ajax_list')?>",
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
     form_submit();
var $modal = $('#load_add_modal_popup');
  $('#vehicle_reg').on('click', function(){
    $modal.load('<?php echo base_url().'ambulance/vehicle/add' ?>',
{
  },
function(){
$modal.modal('show');
});

});

});

function edit_vehicle_list(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ambulance/vehicle/edit/' ?>'+id,
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
    // alert(allVals);
     if(allVals!="")
    {
      allbranch_delete(allVals);
    }
    else{
      alert('Select atleast one checkbox');
    }
    
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
                      url: "<?php echo base_url('ambulance/vehicle/deleteall');?>",
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
       <form name="search_form"  id="search_form">
                  <div class="row">
                     <div class="col-md-4">
                        <div class="row mb-2">
                           <label class="col-md-3 p-r-0">From Date</label>
                           <div class="col-md-4">
                              <input id="start_date" name="reg_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
                           </div>
                        </div>
                        
                         <div class="row mb-2">
                           <label class="col-md-3 p-r-0">Location</label>
                           <div class="col-md-4">
                             <select name="location" id="location" onchange="form_submit();">
                                <option value="">Select</option>
                                <?php
                              if(!empty($location_list))
                              {
                                foreach($location_list as $location)
                                {
                                  $selected_location = "";
                                  if($location->id==$form_data['location'])
                                  {
                                    $selected_location = "selected='selected'";
                                  }
                                  echo '<option value="'.$location->id.'" '.$selected_location.'>'.$location->location_name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                           </div>
                        </div>
                  
                     </div>
                    
                     <div class="col-md-4">
                        <div class="row mb-2">
                           <label class="col-md-4">To Date</label>
                           <div class="col-md-8">
                              <input name="end_date" id="reg_exp" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
                           </div>
                        </div>
                        
                        <div class="row mb-2">
                           <label class="col-md-4">Vehicle No.</label>
                           <div class="col-md-8">
                              <input name="vehicle_no" id="vehicle_no" value="<?php echo $form_data['vehicle_no']?>" id="vehicle_no" onkeyup="return form_submit();" class="m_input_default" value="" type="text">
                           </div>
                        </div>
                       <!--  <div class="row mb-2">
                           <label class="col-md-4">Driver Name</label>
                           <div class="col-md-8">
                              <input name="driver_name" value="<?php echo $form_data['driver_name']?>" id="driver_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
                           </div>
                        </div> -->
                      
                     </div>
                     <div class="col-sm-4">
                         <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a>
                         <a href="javascript:void(0)" class="btn-a-search" id="vehicle_adv_search">
                           <i class="fa fa-cubes" aria-hidden="true"></i> 
                           Advance Search
                           </a> 
                       <!-- <input value="Reset" class="btn-custom" onclick="reset_search()" type="button">-->
                     </div>
                       
                  </div>
               </form>
  <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-6">
         
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>

    <form>
       <?php if(in_array('2081',$users_data['permission']['action'])) {
        ?> 
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ipd_perticular_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                     <th>Vehicle No.</th>
                     <th>Chassis No.</th>
                     <th>Engine No.</th>
                     <th>Vendor Type</th>
                     <th>Reg. Date</th>
                     <th>Reg. Exp.</th>
                     <th>Created Date</th>
                     <th>Charge</th> 
                     <th>Action</th>
                </tr>
            </thead>  
        </table>
   <?php } ?>
    </form>
   </div> <!-- close -->
    <div class="userlist-right">

      <div class="btns">
       <?php if(in_array('2082',$users_data['permission']['action'])) {
                     ?>
                     <a  href="javascript:void(0)" data-toggle="modal" id="vehicle_reg"><button class="btn-update" type="button"> <i class="fa fa-plus"></i> New </button> </a> 
                   <?php }?>
                     <!-- <button class="btn-update" type="button"> <i class="fa fa-file-excel-o"></i> Excel </button>
                     <button class="btn-update" type="button"> <i class="fa fa-file-pdf-o"></i> PDF </button> -->
                     <a href="<?php echo base_url('ambulance/vehicle/vehicle_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              

              <a href="<?php echo base_url('ambulance/vehicle/pdf_vehicle'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>
               <?php if(in_array('2084',$users_data['permission']['action'])) {
               ?>
                      <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                      <i class="fa fa-trash"></i> Delete
                     </button>
                   <?php } ?>
                    
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
                     <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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


 function delete_driver_list(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ambulance/vehicle/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 function upload_document(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'ambulance/vehicle/upload_document/' ?>'+id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
 $(document).ready(function() {
   var $modal = $('#load_add_modal_popup');
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });



  /* 25-04-2020 */


 $('#vehicle_adv_search').on('click', function(){
$modal.load('<?php echo base_url().'ambulance/vehicle/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
});





 $('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
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

  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  /*var patient_name=$('#patient_name').val();*/
  var vehicle_no=$('#vehicle_no').val();
  /*var driver_name=$('#driver_name').val();*/
  var location=$('#location').val();
 
  $.ajax({
         url: "<?php echo base_url('ambulance/vehicle/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date:start_date,end_date:end_date,vehicle_no:vehicle_no,location:location} ,
         success: function(result)
         { 
            if(vals!="1")
            {
               reload_table(); 
            }
         }
      });      
 }


  function reset_search()
{ 
  $('#start_date').val('');
  $('#end_date').val('');
  $('#vehicle_no').val('');
 /* $('#driver_id').val('');
  $('#patient_name').val('');*/
  $('#location').val('');
  $('#reg_exp').val('');

  $.ajax({url: "<?php echo base_url(); ?>ambulance/vehicle/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
    } 
  }); 
}
/* 25-04-2020*/


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


</div><!-- container-fluid -->

</body>
</html>