<!DOCTYPE html>
<html>

<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
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
 // echo "<pre>";print_r($report_print_setting);die;
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
    <div class="userlist-box">
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>

<form id="prescription_setting_form">


    <div class="row">
        <div class="col-sm-12 br-h-small m12">
            <div class="row">
                <div class="col-sm-2"><strong>Title</strong></div>
                <div class="col-sm-7"></div>
                <div class="col-sm-1">&nbsp;</div>
                <div class="col-sm-1"><strong>Print</strong></div>
                <div class="col-sm-1"><strong>PDF</strong></div> 
            </div>
        </div> <!-- 12 -->
    </div> <!-- row -->


    <?php 
        if(!empty($report_print_setting))
        {
          foreach($report_print_setting as $setting_list)
          {

    ?>
      <div class="row">
      <div class="col-xs-12 m-b-5 m12">
        <div class="row">
          <div class="col-xs-2">
            <label><?php echo $setting_list->var_title; ?></label>
          </div>
          <div class="col-xs-4" style="padding-left:1.3%;">
          <input class="form-control text-uppercase"  name="data[<?php echo $setting_list->id; ?>][setting_name]" value="<?php echo $setting_list->setting_name; ?>"  onkeypress="return onlyAlphabets(event,this);" type="text" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($setting_list->setting_name)){ echo 'readonly'; } ?>>
          </div>
          <div class="col-xs-3">
          <?php if($setting_list->setting_name=='TEST_REPORT_PRINT_SETTING') { ?>
          </div>
        </div>

      <?php } else{ ?>
      <div class="row">
        <div class="col-xs-3">
        <input class="form-control media5" onkeypress="return isNumberKey(event);" name="data[<?php echo $setting_list->id; ?>][setting_value]" value="<?php echo $setting_list->setting_value; ?>" type="text" placeholder="Example 2.3" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text">
        </div>

      <?php } ?>
      </div> <!-- row -->

      <div class="col-xs-12 m-b-5 m12">
      <div  class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-10">
        <?php if($setting_list->setting_name=='TEST_REPORT_PRINT_SETTING') { ?>
        </div>
      </div>
   
    <!--neha 14-2-2019 start-->
     <div  class="row">
        <div class="col-xs-2"><b>Doctor Signature position</b></div>
        <div class="col-xs-5">
          <input type="radio" name="doctor_signature_position" <?php if($setting_list->doctor_signature_position==1){ echo 'checked="checked"'; } ?> value="1" /> Right
           <input type="radio" name="doctor_signature_position" <?php if($setting_list->doctor_signature_position==0){ echo 'checked="checked"'; } ?> value="0" /> Left
        </div>
        <div class="col-xs-5">
        </div>
      </div>
      
      <div  class="row">
        <div class="col-xs-2"><b>Doctor Signature Heading</b></div>
        <div class="col-xs-5">
          <input type="radio" name="doctor_signature_text" <?php if($setting_list->doctor_signature_text==0){ echo 'checked="checked"'; } ?> value="0" /> Yes
           <input type="radio" name="doctor_signature_text" <?php if($setting_list->doctor_signature_text==1){ echo 'checked="checked"'; } ?> value="1" /> No
        </div>
        <div class="col-xs-5">
        </div>
      </div>
    <!--neha 14-2-2019 end-->

    <!--Divya 23-4-2019 start-->
    <div  class="row">
        <div class="col-xs-2" style="padding-top:0.7%; padding-bottom:0.4%;"><b>Method</b></div>
        <div class="col-xs-5" style="padding-top:0.9%; padding-bottom:0.4%;">
          <input type="checkbox" name="method" <?php if($setting_list->method==1){ echo 'checked="checked"'; } ?> value="1" /> 
          
        </div>
        <div class="col-xs-5">
        </div>
      </div> 
    <!--Divya 23-4-2019 end-->

        <!--Divya 23-4-2019 start-->
      <div  class="row">
        <div class="col-xs-2" style="padding-top:0.7%; padding-bottom:0.5%;"><b>Sample type</b></div>
        <div class="col-xs-5" style="padding-top:0.9%; padding-bottom:0.5%;">
          <input type="checkbox" name="sample_type" <?php if($setting_list->sample_type==1){ echo 'checked="checked"'; } ?> value="1" /> 
          
        </div>
        <div class="col-xs-5">
        </div>
      </div> 
    <!--Divya 23-4-2019 end-->

      <div  class="row">
        <div class="col-xs-2"><b>Header Part</b></div>
        <div class="col-xs-8">
      <textarea type="text" name="messageh" class="messageh" id="messageh" cols="30"><?php echo $setting_list->page_header; ?></textarea><br/>
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="header_print" id="header_print" <?php if($setting_list->header_print==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="header_pdf" <?php if($setting_list->header_pdf==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
        
         <div class="col-xs-1" id="header_pixel" <?php if($setting_list->header_print==1){ ?> style="display:none;" <?php } ?>>
          <input type="text" style="width: 50px;" id="header_pixel_value" name="header_pixel_value" value="<?php echo $setting_list->header_pixel_value; ?>" /><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Header Margin for report print on letter head.</span></a></sup> <br> in pixel
        </div>
        
      </div>
      <div  class="row">
        <div class="col-xs-2"><b>Details Part</b></div>
        <div class="col-xs-8">
      <textarea type="text" name="messaged" class="messaged" id="messaged" cols="45"><?php echo $setting_list->page_details; ?></textarea><br/>
       </div>
       <div class="col-xs-1">
          <input type="checkbox" name="details_print" <?php if($setting_list->details_print==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="details_pdf" <?php if($setting_list->details_pdf==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
      </div>
      <div  class="row">
        <div class="col-xs-2"><b>Middle Part</b></div>
        <div class="col-xs-8">
      <textarea type="text" name="messagem" class="messagem" id="messagem" cols="45"><?php echo $setting_list->page_middle; ?></textarea><br/>
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="middle_print" <?php if($setting_list->middle_print==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="middle_pdf" <?php if($setting_list->middle_pdf==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
      </div>
      <div  class="row">
        <div class="col-xs-2"><b>Footer Part</b></div>
        <div class="col-xs-8">
      <textarea type="text" name="messagef" class="messagef" id="messagef" cols="45"><?php echo $setting_list->page_footer; ?></textarea>
        </div>
        <div class="col-xs-1">
          <input type="checkbox" id="footer_print" name="footer_print" <?php if($setting_list->footer_print==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
        <div class="col-xs-1">
          <input type="checkbox" name="footer_pdf" <?php if($setting_list->footer_pdf==1){ echo 'checked="checked"'; } ?> value="1" />
        </div>
         <div class="col-xs-1" id="pixel">
          <input type="text" style="width: 50px;" id="pixel_value" name="pixel_value" value="<?php echo $setting_list->pixel_value; ?>" /><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Footer Margin for report print on letter head.</span></a></sup> <br> in pixel
        </div>
        
      </div>

      <?php } ?>
      </div>
      </div> <!-- 12 -->


      </div> <!-- row -->

      <?php
      }
      

      ?>

      <div  class="row m-b-5">
        <div class="col-xs-2"><b>Report Variables</b></div>
        <div class="col-xs-8">
          <div style="margin-left:-1.2%;padding-right: 4.8em;">
            <textarea readonly="" class="form-control" cols="" rows="6">{patient_reg_no},{patient_name},{patient_age},{mobile_no},{address},{booking_date},{lab_reg_no},{doctor_name},{ref_doctor_name},{test_report_data},{signature_reprt_data},{qualification},{booking_time},{report_date},{report_time},{referred_by},{sample_collected_date},{sample_received_date},{verify_report_date},{delivered_report_date},{completed_report_date}. </textarea>
            
            </div>
        </div>
        
      </div>


      <div class="row">
        <div class="col-xs-2"></div>
        <div class="col-xs-10">
          <div style="margin-left:-1.2%;">
              <button class="btn-update" name="submit" type="submit"><i class="fa fa-floppy-o"></i>  Save</button>
              <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
          </div>
        </div>
      </div><!-- row -->
    <?php } ?>
</form>


 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'messageh', {
    FullPage : false, 
    ProtectedTags : 'html|head|body|title',
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

CKEDITOR.replace( 'messaged', {
    FullPage : false, 
    ProtectedTags : 'html|head|body|title',
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

CKEDITOR.replace( 'messagem', { 
    allowedContent: true,
    autoGrow_onStartup: true,
    FullPage : false, 
    ProtectedTags : 'html|head|body|title',
    enterMode: CKEDITOR.ENTER_BR
} );

CKEDITOR.replace( 'messagef', { 
    allowedContent: true,
    autoGrow_onStartup: true,
    FullPage : false, 
    ProtectedTags : 'html|head|body|title',
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});


<?php if($setting_list->footer_print==1){ ?>$("#pixel").css("display", "none"); <?php } ?>

 $('#footer_print').change(function(){ 
        if(this.checked)
          $("#pixel").css("display", "none");
          
          else
            $("#pixel").css("display", "block");
        //     $('#pixel').display('none');
        // else
        //     $('#pixel').display('block');

    });
    
     $('#header_print').change(function(){ 
        if(this.checked)
          $("#header_pixel").css("display", "none");
          
          else
            $("#header_pixel").css("display", "block");
        
    });

});







    


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
 
$("#prescription_setting_form").on("submit", function(event) 
{

for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    } 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>test_report_print_setting/",
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
</script>   
</div><!----container-fluid--->
</body>
</html>