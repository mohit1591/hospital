<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(3);
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

<script type="text/javascript">
var save_method; 
var table;
$(document).ready(function() { 
          table = $('#table').DataTable({  
               "processing": true, 
               "serverSide": true,
               "searching": false,
               "bLengthChange": false , 
               "order": [], 
               "pageLength": '20',
               "ajax": {
                    "url": "<?php echo base_url('doctors/doctor_panel_ajax'); ?>",
                    "type": "POST",
                 
               }, 
               "columnDefs": [{ 
                    "targets": [ 0 , -1 ], //last column
                    "orderable": false, //set not orderable
               },],
          });
     }); 

 /*$(document).ready(function() { 
          var doctor_id = $('#doctor_id :selected').val();
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true, 
             "order": [], 
             "pageLength": '20',
             "ajax": {
                 "url": "<?php echo base_url('doctors/doctor_panel_ajax')?>",
                 "type": "POST",
                 'doctor_id='+doctor_id
                 
             }, 
             "columnDefs": [
             { 
                 "targets": [ 0 , -1 ], //last column
                 "orderable": false, //set not orderable

             },
             ],

         });
     }); */

 
</script>
</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>


 



<section class="userlist">

    <div class="userlist-box">
<!--   <form name="new_search_form"  id="new_search_form">    -->
 <form name="doctor_panel_charge" id="doctor_panel_charge" action="" method="post">
  
  <div class="row m-b-5">
    <div class="col-md-4">
       
      <div class="row">
        <div class="col-md-4">
          <?php $panel_adv_search = $this->session->userdata('panel_doctor_search'); ?>
          <label>Doctor</label>
        </div>
        <div class="col-md-8">
          <select name="doctor_id" id="doctor_id" class="m_input_default"  onchange="return panel_rate(this.value);">
            <option value=""> Select Doctor </option>
            <?php
              if(!empty($doctor_list))
              {
                foreach($doctor_list as $doctor)
                {
                ?>
                   <option <?php if(!empty($panel_adv_search['doctor_id']) && $panel_adv_search['doctor_id']==$doctor->id){ echo 'selected="selected"';} ?> value="<?php echo $doctor->id; ?>"><?php echo $doctor->doctor_name; ?></option>
                <?php   
                }
              } 
            ?>
        </select>
        </div>
      </div>

    </div>
    <div class="col-md-8"></div>  <!-- blank -->
  </div>
  <!-- // -->
    <div class="panel-particular-box">

    <table id="table" class="table table-striped table-bordered add_doctor_panel_charge" cellspacing="0" width="100%">

        <thead class="">
          <tr class="bg-theme" id="previours_row">
                <th width="200"> Panel </th> 
                <th> Charge </th> 
                <th> <?php  echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME'); ?> </th> 
         </tr>
        </thead>
    
    </table>
    
    </div>
  </div>
  <div class="userlist-right">
      <div class="btns">
      
         
             <input type="submit"  class="btn-update" name="submit" value="Save" />

              <a class="btn-anchor" id="modal_add" onClick="return print_panel_charge();"><i class="fa fa-print"></i> Print
             </a>
             
          <a class="btn-anchor" onclick="window.location.href='<?php echo base_url('doctors'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>
    </div> 
</section>
  </form> 
  
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
$("#doctor_panel_charge").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
 
   
    var path = 'doctor_panel_charge/';
    var msg = 'Doctor panel charge successfully updated.';
  
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('doctors/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        flash_session_msg(msg); 
        //reload_table();
      } 
            
      $('#overlay-loader').hide();
    }
  });
}); 

function print_panel_charge()
{
  var doctor_id = $('#doctor_id').val();
  print_window_page('<?php echo base_url('doctors/print_panel_charge/'); ?>'+doctor_id);
  //
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
/*function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault();  
   
  $.ajax({
    url: "<?php echo base_url('doctors/panel_advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });
});*/

function panel_rate(val)
{
  var doctor_id = val; //$('#doctor_id').val();
  $.ajax({
    url: "<?php echo base_url('doctors/panel_advance_search/'); ?>",
    type: "post",
    data: 'doctor_id='+doctor_id,
    success: function(result) 
    { 
      reload_table();        
    }
  });
}
</script>