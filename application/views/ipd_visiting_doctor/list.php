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
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
$('#doctor_id').val('');
      $.ajax({
         url: "<?php echo base_url('ipd_visiting_doctor/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
  }

$(document).ready(function(){
var $modal = $('#load_advance_search_modal_popup');
$('#advance_search').on('click', function(){
$modal.load('<?php echo base_url().'ipd_visiting_doctor/advance_search/' ?>',
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
    <form> 

    <div class="grp_box m-b-5">
     <?php  $users_data = $this->session->userdata('auth_users'); ?>
             
<input type="hidden" class="m_input_default" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">

<?php if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
  { ?>
<div class="grp media_float_left" id="search_box_user">
              <b>Doctor</b> 
              <select name="doctor_id" class="m_input_default" id="doctor_id" onchange="return form_submit();" style="width: 140px;">
                  <option value="">Select Doctor</option>
                  <?php 
                    if(!empty($doctor_list))
                    {
                      foreach($doctor_list as $doctor)
                      {
                        echo '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
                      }
                    }
                  ?> 
              </select>
      </div>
      
      <?php } ?>
      
      <div class="grp">

            <label><b>From Date:</b></label>
            <input type="text" id="start_date" name="from_date" class="datepicker start_datepicker m_input_default w-80px" value="<?php echo $form_data['from_date']; ?>">
      </div>

      <div class="grp">
            <label><b>To Date:</b></label>
            <input type="text" name="to_date" id="end_date" class="datepicker datepicker_to end_datepicker m_input_default w-80px" value="<?php echo $form_data['end_date']; ?>">
      </div>
      <div class="grp">
            <a class="btn-custom m_text_left" id="reset_date" onClick="reset_date_search();">Reset
            </a>
      </div>

     

      
    </div> <!-- //row -->






       <?php if(in_array('772',$users_data['permission']['action'])): ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered ipd_collection_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> IPD No. </th> 
                    <th> Admission Date </th>  
                    <th> Patient Name </th>
                    <th> Doctor Name </th>
                    <th> Amount </th>
                    <th> Date </th>  
                    
                </tr>
            </thead>  
        </table> 
        <?php endif;?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative" id="example_wrapper">
      <div class="fixed">
  		<div class="btns"> 
          <?php if(in_array('768',$users_data['permission']['action'])): ?>
               <a href="<?php echo base_url('ipd_visiting_doctor/ipd_report_excel'); ?>" class="btn-anchor m-b-2">
               <i class="fa fa-file-excel-o"></i> Excel
               </a>
          <?php endif;?>
          
          <?php if(in_array('770',$users_data['permission']['action'])): ?>
               <a href="<?php echo base_url('ipd_visiting_doctor/pdf_ipd_report'); ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
               </a>
          <?php endif;?>
          <?php if(in_array('771',$users_data['permission']['action'])): ?>
              

            <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ipd_visiting_doctor/print_ipd_report"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 

          <?php endif;?>
        
               <button class="btn-update" onClick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
         
         
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
         $('#doctor_id').val('');
        document.getElementById("search_box_user").style.display="none";
      }
      
}

  /*$(document).ready(function(){
    form_submit();
  });*/
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

  function form_submit(){
    var start_date = $('#start_date').val();
    var branch_id = $('#branch_id').val();
    var end_date = $('#end_date').val();
    var doctor_id = $('#doctor_id').val();
    var section_id = $('#section_id').val();//alert(branch_id);
    $.ajax({ 
           url: "<?php echo base_url('ipd_visiting_doctor/search_data/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date,section_id:section_id,branch_id:branch_id,doctor_id:doctor_id} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    

    
  }

function form_submit(vals)
{ 
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var doctor_id = $('#doctor_id').val();
  $.ajax({
         url: "<?php echo base_url('ipd_visiting_doctor/advance_search/'); ?>", 
         type: 'POST',
         data: { from_date: start_date, end_date : end_date,doctor_id:doctor_id} ,
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
<?php if(in_array('772',$users_data['permission']['action'])): ?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,  
        "pageLength": '20', 
        "aaSorting": [],
        "ajax": {
            "url": "<?php echo base_url('ipd_visiting_doctor/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php endif;?>
</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>