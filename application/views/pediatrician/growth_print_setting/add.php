<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
<meta name="viewport" content="width=1024">
<?php //print_r($form_data);?>

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
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->

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
<section class="userlist">
    
<div class="sale_opd">
    
<div class="row">
  <div class="col-md-12 add_opd_print_setting">
    <form action="<?php echo base_url('pediatrician/growth_print_setting/add'); ?>" method="post">
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label>Print Type<span class="star">*</span></label>
      </div>
      <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
      <div class="col-xs-9">
      <?php $printer_type= get_printer_type();?>
      <select class="" name="printer_type" id="printer_type" onchange="get_template_according(this.value);">
            <option value="">Select Type</option>
           <?php foreach($printer_type as $type) {?>
            <option value="<?php echo $type->id; ?>" <?php if($form_data['printer_type']==$type->id) {echo 'selected';}?>><?php echo $type->printer_name; ?></option>
            <?php }?>
           
        </select>
        <?php if(!empty($form_error)){ echo form_error('printer_type'); } ?>

      </div>
    </div> <!-- rowClose -->



    <div class="row m-b-5" <?php if($form_data['printer_type']==1 ) { }else{ ?> style="display: none;" <?php } ?> id="paper_dropdown">
      <div class="col-xs-2">
        <label>Print paper Type<span class="star">*</span></label>
      </div>
      
      <div class="col-xs-9">
      <?php $printer_paper_type= get_printer_paper_type(1);?>
      <select class="" name="printer_paper_type" id="printer_paper_type" onchange="get_template_according_to_papaer(this.value);">
            <option value="">Select Type</option>
           <?php foreach($printer_paper_type as $paper_type) {?>
            <option value="<?php echo $paper_type->id; ?>" <?php if($form_data['printer_paper_type']==$paper_type->id) {echo 'selected';}?>><?php echo $paper_type->printer_name; ?></option>
            <?php }?>
           
        </select>

      </div>
    </div> 

    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-9">
        <textarea type="text" name="message" class="message" id="message" cols="45"><?php echo $form_data['message'];?></textarea>
      </div>
    </div> <!-- rowClose -->


    <div class="row m-b-5">
      <div class="col-xs-2">
        <label>Short Code</label>
      </div>
      <div class="col-xs-9" >
      <textarea class="print_textarea"  type="text" name="short_code" cols="45" value="" readonly id="comment_data"/>{patient_reg_no}  ,     {patient_name}  ,  {hospital_receipt_no} ,  {mobile_no}  ,  {gender_age}  ,   {booking_date}  ,   {booking_code}  ,{patient_weight}, {patient_height_level}, {patient_height}, {bmi}, {measured}, {head_circum}, {muac},   {triceps}, {subscapular}
    </textarea>
      </div>
    </div> <!-- rowClose -->
    
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-10">
       <button class="btn-save" type="submit" name="remove_levels" onClick=" validateForm();" ><i class="fa fa-floppy-o"></i> Save</button>
             <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('pediatrician/growth_print_setting'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
    </div> <!-- rowClose -->

  </form>
</div> <!-- mainRowClose -->
    
<script>
  function get_template_according(value){
    
    if(value=='1')
    {
        $("#paper_dropdown").css("display","block");
        document.getElementById("comment_data").value == '';
    }
    else
    {

      $("#paper_dropdown").css("display","none"); 
      
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>pediatrician/growth_print_setting/growth_printtemplate_dropdown/", 
      data: {value: value},
      datatype:'JSON',
        success: function(result)
        {

           var newdata = $.parseJSON(result); 
           CKEDITOR.instances['message'].setData(newdata.template); 
           $('#comment_data').val(newdata.short_code); 
        } 
      });

    }
  }

  function get_template_according_to_papaer(value)
  {
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>pediatrician/growth_print_setting/growth_printtemplate_dropdown/", 
      data: {value: value},
      datatype:'JSON',
        success: function(result)
        {

           var newdata = $.parseJSON(result); 
           CKEDITOR.instances['message'].setData(newdata.template); 
           $('#comment_data').val(newdata.short_code); 
        } 
      });

  
  }


</script>







</div> 

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>
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