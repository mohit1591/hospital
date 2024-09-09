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

<form id="ipd_admission_print_setting_form">


  <div class="row">
  <div class="col-xs-12 br-h-small m12">
  <div class="row">
    <div class="col-xs-3"><strong>&nbsp;</strong></div>
    <!-- <div class="col-xs-3 p-l-0"><strong>Variable Name </strong></div> -->
    <div class="col-xs-3"><strong class="m13">&nbsp;</strong></div>
    <div class="col-xs-3"></div>
  </div>
  </div> <!-- 12 -->
  </div> <!-- row -->


    <?php
      if(in_array('761',$users_data['permission']['action']))
      {
        if(!empty($ipd_admission_setting_list))
        {
          foreach($ipd_admission_setting_list as $setting_list)
          {
            
    ?>
      <div class="row">
      <div class="col-xs-12 m-b-5 m12">
        <div class="row">
          <div class="col-xs-3">
            <label><?php echo $setting_list->var_title; ?></label>
          </div>
          <div class="col-xs-3" style="padding-left:7px;">
          <input class="form-control text-uppercase"  name="data[<?php echo $setting_list->id; ?>][setting_name]" value="<?php echo $setting_list->setting_name; ?>"  onkeypress="return onlyAlphabets(event,this);" type="hidden" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($setting_list->setting_name)){ echo 'readonly'; } ?>>
          </div>
          <div class="col-xs-4">
          <?php if($setting_list->setting_name=='IPD_PRINT_SETTING') { ?>
          </div>
        </div>

      <?php } else{ ?>
      <div class="row">
        <div class="col-xs-12">
        <div>
        <input class="tooltip-text" onkeypress="return isNumberKey(event);" name="data[<?php echo $setting_list->id; ?>][setting_value]" value="<?php echo $setting_list->setting_value; ?>" type="text" placeholder="Example 10" data-toggle="tooltip"  title="Allow only numeric."> <span>px</span>
        </div>
        </div>

      <?php } ?>
      </div> <!-- row -->

      <div class="row m-b-5">
      <div class="col-xs-3"></div>
      <div class="col-xs-9">
      <?php if($setting_list->setting_name=='IPD_PRINT_SETTING') {  ?>

      <textarea type="text" name="data[<?php echo $setting_list->id; ?>][setting_value]" class="message" id="message" cols="45"><?php echo $setting_list->setting_value; ?></textarea>


      <?php } ?>
      </div> <!-- row -->


      </div> <!-- 12 -->
      </div> <!-- row -->

      <?php
      }
      }

      ?>

      <div class="row">
      <div class="col-xs-12 m-b-5 m12">
        <div class="row">
          <div class="col-xs-3">
            <label>Short Code</label>
          </div>
         <!-- <div class="col-xs-3" style="padding-left:7px;">-->
              <div class="col-xs-9 p-l-0 p-r-0">
           <!--  <textarea cols="45" readonly="">{patient_reg_no},{patient_name},{patient_age},{patient_address},{ipd_no},{mobile_no},{booking_date},{specialization},{doctor_name},{room_type},{bed_no},{mlc},{remarks}, {signature},{parent_relation_type},{parent_relation_name},{signature} </textarea>-->
           
           <textarea  type="text"  cols="45" class="print_textarea" readonly="">{patient_reg_no}  ,  {patient_name}  ,  {patient_age} , {patient_address}  , {ipd_no}  , {mobile_no}  , {booking_date}  , {doctor_name}  , {room_type}  , {bed_no}  , {mlc}  , {remarks}  , {parent_relation_type}  , {parent_relation_name} ,  {marital_status} ,  {anniversary}  , {religion}  , {dob}  ,  {relation_name} , {mother} , {guardian_name}  , {guardian_email} , {guardian_phone} ,  {guardian_relation}  ,  {patient_email} , {occupation} ,  {insurance_type} ,  {monthly_income} , {insurance_type_name}  , {insurance_company_name} , {insurance_policy_no}  ,  {insurance_tpa_id}  , {insurance_amount}  , {auth_no} , {referred_by}  , {referrer_name} , {attented_doctors} , {assigned_doctor} , {admission_date_time} , {ipd_no}  ,  {booking_date}  ,  {doctor_name}  , {specialization}  ,  {room_no} , {bed_no} , {room_type}  {patient_age}  , {signature},  {diagnosis}  </textarea>
        </div>
     </div>
     </div>
     </div>


      
      <div class="row">
      <div class="col-xs-3"></div>
      <div class="col-xs-9 p-l-0">
        <div style="">
            <button class="btn-update" name="submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
            
            
            <a class="btn-anchor" onclick="return preview_template('<?php echo $user_data['parent_id']; ?>');" title="Preview Template"> Preview </a>
            <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
        </div>
      </div>
      </div><!-- row -->
    <?php } ?>
</form>


</div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})

</script>
<script>  

  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

 function onlyAlphabets(e, t) {

            try {

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;

                }

                else { return true; }

                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))

                    return true;

                else

                    return false;

            }

            catch (err) {

                alert(err.Description);

            }

        } 
 
$("#ipd_admission_print_setting_form").on("submit", function(event) 
{

for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    } 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>ipd_admission_print_setting/",
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
  $modal.load('<?php echo base_url().'ipd_admission_print_setting/preview/' ?>'+id,
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