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
    <div class="col-xs-2"><label>Form Name</label></div>
    <div class="col-xs-3">
    <select id="template_name" name="template_name" onchange="get_template(this.value);">
         <option value="1">ADMINSSION & DISCHARGE RECORD</option>
                      <option value="2">PATIENTS RIGHTS</option>
                      <option value="3">DOCTOR'S VISIT</option>
                      <option value="4">EMERGENCY ASSESSMENT SHEET</option>
                      <option value="5">INITIAL CLINICIAN ASSESSMENT </option>
                      <option value="6">NURSING CARE PLAN</option>
                        <option value="7">NURSING ADMISSION ASSESSMENT </option>
                      <option value="8">CARE PLAN </option>
                      <option value="9">PROGRESS NOTES</option>
                      <option value="10">NURSING NOTES</option>
                      <option value="11">MEDICATION CHART</option>
                      <option value="12">INTAKE OUTPUT</option>
                        <option value="13">VITALS</option>
                      <option value="14">NUTRITIONAL ASSESSMENT</option>
                      <option value="15">UNIVERSAL PAIN </option>
                      <option value="16">BILLING SHEET  </option>
                      <option value="17">SURGICAL PATIENT FILE </option>
                      <option value="18">PRE OPERATIVE CHECKLIST</option>
                        <option value="19">INFORMED CONSENT SURGERY</option>
                      <option value="20">INFORMED CONSENT ANASTHESIA</option>
                      <option value="21">PRE ANAESTHEIC ASSESSMENT</option>
                      <option value="22">PRE INTRA OPERATIVE EVENTS</option>
                      <option value="23">OPERATIONAL NOTES</option>
                      <option value="24">GULFASA(DISCHARGE SUMMERY)</option>
                        <option value="25">POST OPERATIVE RECOVERY</option>
                      <option value="26">MAST AAHAN(DISCHARGE SUMMERY)</option>
                      <option value="27">SURGICAL SAFETY</option>
                      <option value="28">DOCUMENT CHECKLIST</option>
                      <option value="29">MINOR PROCEDURE</option>
                      <option value="30">MODERATE SEDATION </option>
                        <option value="31">HIV CONSENT</option>
                      <option value="32">BLOOD TRANSFUSION</option>
                      <option value="33">MISS AANCHAL(DISCHARGE SUMMERY)</option>
                      <option value="34">LAMA</option>
                      <option value="35">FEEDBACK</option>
                      <option value="36">RECORD OF DAILY  PATIENT’S</option>
                      <option value="37">INVESTIGATION CHART</option>
                      <option value="38">Diet Template</option>
                      
                      <?php if(in_array('248',$users_data['permission']['section'])){  ?>
                      <option value="39">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="40">OPTHAL Consent form</option>
                      <option value="41">OPTHAL OPERATION NOTES</option>
                      <option value="42">OPTHAL Medication chart</option>
                      <option value="43">OPTHAL Nurse Notes</option>
                      <!--<option value="44">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="45">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="46">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="39">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="39">OPTHAL CONSENT FOR SURGERY</option>
                      <option value="39">OPTHAL CONSENT FOR SURGERY</option>-->
                      
                      
                      
                      <?php } ?>
          
    </select>
   </div>
  </div>

  </div> <!-- row -->




  </div>
      <div id="all_report_section" class="hide">

      </div>


     
         <div class="row m-b-5">
          <div class="col-xs-2">
            <label>Short Code</label>
          </div>
          <div class="col-xs-9">
             <textarea cols="45" readonly="" class="print_textarea" >{patient_reg_no},{patient_name},{patient_age},{patient_address},{ipd_no},{mobile_no},{admission_date},{discharge_date},{doctor_name},{specialization},{chief_complaints}, {h_o_presenting_illness} ,{on_examination} ,{vitals}, {provisional_diagnosis}, {final_diagnosis}, {course_in_hospital}, {investigation}, {condition_at_discharge_time}, {advise_on_aischarge}, {review_time_and_date},{vitals_pulse},{vitals_chest},{vitals_bp},{vitals_cvs},{vitals_temp},{vitals_cns},{vitals_p_a},{mlc},{specialization},{signature} </textarea>
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
$(document).ready(function(){
  get_template();
})
function get_template(){
 var temp_name=$('#template_name').val();
  $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>nabh_form_print_setting/get_according_to_section/", 
      data: {temp_name: temp_name },
     
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
    url: "<?php echo base_url(); ?>nabh_form_print_setting/",
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
  $modal.load('<?php echo base_url().'nabh_form_print_setting/preview/' ?>'+id,
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