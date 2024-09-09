<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
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
    
<div class="sale_inventory">
    
<div class="row">
  <div class="col-md-12 add_inventory_print_setting">
    <form action="<?php echo base_url('inventory_print_setting/add'); ?>" method="post">
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label>Select Section</label>
      </div>
      <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
      <div class="col-xs-9">
    
      <select class="" name="sub_section" id="sub_section" onchange="get_printer_section(this.value);">
            <option value="">Select Section</option>
            <option value="1">Purchase</option>
            <option value="2">Issue/Allot</option>
            <option value="3">Garbage item</option>
            
           
      </select>

      </div>
    </div> <!-- rowClose -->
      <div id="all_report_section" class="hide">

      </div>
    </form>
    </div>

  
</div> <!-- mainRowClose -->
    
<script>
  function get_template_according(value){
   var sub_section= $('#sub_section_data').val();
  
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
      url: "<?php echo base_url(); ?>inventory_print_setting/inventory_printtemplate_dropdown/", 
      data: {value: value,sub_section:sub_section},
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
    var sub_section= $('#sub_section_data').val();
  $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>inventory_print_setting/inventory_printtemplate_dropdown/", 
      data: {value: value,sub_section:sub_section},
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







</div> <!-- inventory -->

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>
<script>
CKEDITOR.config.autoParagraph = false;
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message', {
  fullPage: true, 
  allowedContent: true,
  attributes: true,
  styles: true,
  classes: true,
  noSnapshot: true
    //autoGrow_onStartup: true,
   // enterMode: CKEDITOR.ENTER_BR
} );



CKEDITOR.replace( 'editor1', {
      extraPlugins: 'tableresize'
    } );

    CKEDITOR.inline( 'inline', {
      extraPlugins: 'tableresize'
    });
CKEDITOR.config.protectedSource.push(/\{foreach[\s\S]*?}|\{\/foreach}/g);

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});

})

function get_printer_section(value){

  $.ajax({
      type:"POST",
      url: "<?php echo base_url(); ?>inventory_print_setting/get_according_to_section/", 
      data: {value: value},
     
        success: function(result)
        {
            $('#all_report_section').html(result);
        } 
      });
 $('#all_report_section').removeClass('hide');
}
</script>