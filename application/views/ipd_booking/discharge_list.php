<?php
  // print_r($this->session->userdata('net_values_all'));
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

    <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('733',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "ordering": false,
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('ipd_discharge_booking/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],
        "drawCallback": function() 
        {
            
        },

    });

}); 
<?php } ?>



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
    <div class="userlist-box">
    <?php if(isset($user_role) && $user_role==4 || $user_role==3)
    {
    }
    else
    {?>
  <form name="search_form"  id="search_form"> 
  <div class="row">
  <div class="col-md-12">

    <div class="row m-b-2">
      
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
               <label>  Patient Type </label>
            </div>
            <div class="col-md-7">
              <select name="patient_type" class="m_input_default" id="patient_type" onchange="return form_submit();">
              <option value="-1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']=='-1'){ echo 'selected="selected"'; } ?> >All</option>
              <option  value="1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==1){ echo 'selected="selected"'; } ?> >Normal</option>
              <option value="2" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==2){ echo 'selected="selected"'; } ?> >Panel</option></select>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->


    <div class="row m-b-5">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> From Date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="start_date" id="start_date_p"  value="<?php echo $form_data['start_date'];?>" class="datepicker m_input_default"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <label>To date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date']?>" class="datepicker m_input_default"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
       
              <a href="javascript:void(0)" class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a>
              <a href="javascript:void(0)" class="btn-a-search" id="adv_search_ipd_booking"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a>
            
          </div>
      
    </div> <!-- inner row -->

    <!-- <div class="row m-b-5">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
             
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div>  --><!-- inner row -->


  </div> 
</div> 
    


</form>

<?php } ?>

    <form>
       <?php if(in_array('733',$users_data['permission']['action'])) {
       ?>

       <!-- bootstrap data table -->
        <div class="table-responsive">
            <table id="table" class="table table-striped table-bordered purchase_list" cellspacing="0" width="100%">

            <thead class="bg-theme">

                <tr>
                    <th align="center" width="40"> <input onclick="selectall();" type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                        <th>Bill No.</th>
                        <th>IPD No.</th> 
                        <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th> 
                        <th>Patient Name </th> 
                        <th>Mobile No.</th>
                        <th>Age</th>
                        <th>Gender</th> 
                        <th>Admission Date</th>
                        <th>Discharge Date</th>
                        <th>Total</th>
                        <th>Discount</th> 
                        <th>Paid Amount</th>
                        <th>Balance</th>
                        <th>Type </th>
                        <th>Action </th>
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>

    </form>


   </div><!-- close -->


<div class="userlist-right relative">
  <div class="fixed">
  		<div class="btns">

               
               <?php 
               if(in_array('733',$users_data['permission']['action'])) 
               {
               ?>

                <a href="<?php echo base_url('ipd_discharge_booking/ipd_booking_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>

                <a href="<?php echo base_url('ipd_discharge_booking/ipd_booking_csv'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-word-o"></i> CSV
                </a>

                <a href="<?php echo base_url('ipd_discharge_booking/pdf_ipd_booking'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-pdf-o"></i> PDF
                </a>
                <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ipd_discharge_booking/print_ipd_booking"); ?>');"><i class="fa fa-print"></i> Print</a> 
  			     
               <?php } ?>
                 <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_discharge_booking'); ?>'"><i class="fa fa-refresh"></i> Reload </button>
               <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
      </div>
  	</div> 
  	<!-- right -->

</section> <!-- cbranch -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<?php
$this->load->view('include/footer');
?>

<script> 

function check_status()
{
    var running = document.getElementById('running').value;
    if(running.length == 0)
    {
       form_submit();
    }
    else
    {
        form_submit();
    }
}

$(document).ready(function(){
   form_submit();
 });




 $(document).ready(function(){
  
   $('#selectAll').on('click', function () { //alert('yttt');
                                 
         if ($("#selectAll").hasClass('allChecked')) 
         {
             $('.checklist').prop('checked', false);
         } 
         else 
         {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
</script> 

<script>
$('documnet').ready(function(){

 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_discharge_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_card"); ?>')
    }) ;
   
       
  <?php }?>

  <?php if(isset($_GET['mlc_status']) && $_GET['mlc_status']==1){?>
  $('#confirm_mlc').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_discharge_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>

  <?php if(isset($_GET['admission_form']) && $_GET['admission_form']=='print_admission'){?>
  $('#confirm_admission_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_discharge_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>
 });



function confirmation_readmit(ipd_id,patient_id){
   $('#confirm_readmit').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#yes', function(e)
    { 
       window.location.href='<?php echo base_url('ipd_discharge_booking/readmit') ?>/'+ipd_id+'/'+patient_id;
    }) ;

}



</script>

<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_ipd_booking').on('click', function(){
   
$modal.load('<?php echo base_url().'ipd_discharge_booking/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});



</script>

<script>
function form_submit()
{
  $('#search_form').delay(200).submit();
}


function reset_search()
{ 
  $('#start_date_p').val('');
  $('#end_date_p').val('');
  $('#ipd_no').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>ipd_discharge_booking/reset_search/", 
    success: function(result)
    { 
          
      reload_table();
     
    } 
  }); 
}



$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/ipd_discharge_booking/advance_search/",
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
    $('#start_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_p").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_p").datepicker("option", "maxDate", selected);
      }
    })


 $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
 });
  </script>
<!-- Confirmation Box -->



    <div id="confirm_readmit" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure For Re-admit?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  id="yes" onClick="" >Yes</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">No</button>
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

<!-- Confirmation Box end -->
</div>
</body>
</html>
