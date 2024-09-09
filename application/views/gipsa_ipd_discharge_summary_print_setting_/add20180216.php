<!DOCTYPE html>
<html>

<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users');
$user_data = $this->session->userdata('auth_users');
 ?>
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
 
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>


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
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>

<form id="prescription_setting_form">


  <div class="row">
  <div class="col-xs-12 br-h-small m12">
  <div class="row">
    <div class="col-xs-3"><label>Template</label></div>
    <div class="col-xs-3">
    <select name="template" onchange="get_template(this.value);">
      <option value="0">Normal</option>
      <option value="1">Tabular</option>
    </select>
    
  </div>
  </div> <!-- 12 -->
  </div> <!-- row -->
  </div>
      <div id="all_report_section" class="hide">

      </div>


      <div class="row">
      <div class="col-xs-12 m-b-5 m12">
        <div class="row">
          <div class="col-xs-3">
            <label>Short Code</label>
          </div>
          <div class="col-xs-3">
             <textarea cols="45" readonly="">{patient_reg_no},{patient_name},{patient_age},{patient_address},{ipd_no},{mobile_no},{admission_date},{discharge_date},{doctor_name},{specialization},{chief_complaints}, {h_o_presenting_illness} ,{on_examination} ,{vitals}, {provisional_diagnosis}, {final_diagnosis}, {course_in_hospital}, {investigation}, {condition_at_discharge_time}, {advise_on_aischarge}, {review_time_and_date},{vitals_pulse},{vitals_chest},{vitals_bp},{vitals_cvs},{vitals_temp},{vitals_cns},{vitals_p_a},{mlc},{signature} </textarea>
        </div>
     </div>
     </div>
     </div>


      <div class="row">
      <div class="col-xs-3"></div>
      <div class="col-xs-9 p-l-0">
        <div style="">
            <button class="btn-update" name="submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
            
            
            <!-- <a class="btn-anchor" onclick="return preview_template('<?php echo $user_data['parent_id']; ?>');" title="Preview Template"> Preview </a> -->
            <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
        </div>
      </div>
      </div><!-- row -->
</form>


</div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>

function get_template(value){

  $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>ipd_discharge_print_setting/get_according_to_section/", 
      data: {value: value},
     
        success: function(result)
        {
            $('#all_report_section').html(result);
        } 
      });
      
      $('#all_report_section').removeClass('hide');
}
 

$("#prescription_setting_form").on("submit", function(event) 
{

for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    } 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>ipd_discharge_print_setting/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       flash_session_msg(result);    
       $('.overlay-loader').hide();    
    }
  });
});

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});


function preview_template(id)
{
  
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_discharge_print_setting/preview/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
</script>



</div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- container-fluid -->
</body>
</html>