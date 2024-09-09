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



            <!-- <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
          -->
<section class="userlist">
    
<div class="sale_opd">
    
<div class="row">
  <div class="col-md-12 add_ipd_print_setting">
    <form action="<?php echo base_url('patient/print_doctor_certificate/'.$form_data['id'].'/'.$form_data['branch_id']); ?>" method="post">

    <!-- /////////////////// -->
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label>Certificate Type<span class="star">*</span></label>
      </div>
    
      <div class="col-xs-10">
 
         <select class="" name="certificate_id" id="certificate_id" onchange="get_template_according(this.value);">
            <option value="">Select Certificate</option>
           <?php foreach($template_data as $key=>$type) {?>
            <option value="<?php echo $type['id']; ?>"><?php echo $type['title']; ?></option>
            <?php }?>
           
          </select>
         <?php if(!empty($form_error)){ echo form_error('title'); } ?>

      </div> <!-- 10 -->
    </div> <!-- row -->


  <div class="row m-b-5">
    <div class="col-xs-2"><label>Select Doctor</label></div>
       <div class="col-xs-10">
            <select class="" name="doctor_id" id="doctor_id">
            <option value="">Select Doctor</option>
            <?php foreach($doctor_name as $key=>$name) {?>
            <option value="<?php echo $name['id']; ?>"><?php echo $name['doctor_name']; ?></option>
            <?php }?>
           
        </select>
        <?php if(!empty($form_error)){ echo form_error('doctor_name'); } ?>
      </div>       

  </div> <!-- rowClose -->



    

    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-9">
        <textarea type="text" name="template" class="template" id="template" cols="45"></textarea>
      </div>
    </div> <!-- rowClose -->

  
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-10">
       <button class="btn-save" type="submit" name="remove_levels" ><i class="fa fa-floppy-o"></i> Save</button>
             <a  class="btn-anchor" onclick="window.location.href='<?php echo base_url('patient'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>
    </div> <!-- rowClose -->

  </form>
</div> <!-- mainRowClose -->
    
</div> 

</section> <!-- section close -->
          
    
<script>
  function get_template_according(value){
  //  alert("hi");
    
  

      $("#paper_dropdown").css("display","none"); 
      
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>patient/ipd_printtemplate_dropdown/", 
      data: {value: value},
      datatype:'JSON',
        success: function(result)
        {

           var newdata = $.parseJSON(result); 
           CKEDITOR.instances['template'].setData(newdata.template); 
           $('#comment_data').val(newdata.short_code); 
        } 
      });

    
  }

  function get_template_according_to_papaer(value)
  {
    $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>patient/ipd_printtemplate_dropdown/", 
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


<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'template', {
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



