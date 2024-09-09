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

<?php if($form_data['type']==0){
  ?>
    
      
      <input type="hidden" name="type" id="sub_section_data" value ="<?php echo $form_data['type'];?>"/><input type="hidden" name="data_id" id="data_id" value ="<?php echo $form_data['data_id'];?>"/>
      <?php  $table='<table>{start_loop}<tr><td></td></tr>{end_loop}</table>'?>
      <div class="row m-b-5">
        <div class="col-xs-2">
          <label></label>
        </div>
        <div class="col-xs-9">
          <textarea type="text" name="message" class="message" id="message" cols="45"><?php echo $form_data['message'];?></textarea>
        </div>
      </div> <!-- rowClose -->
    <?php } ?>
    <?php if($form_data['type']==1){?>
    <input type="hidden" name="data_id" id="data_id" value ="<?php echo $form_data['data_id'];?>"/>
     <input type="hidden" name="type" id="sub_section_data" value ="<?php echo $form_data['type'];?>"/>
      <?php  $table='<table>{start_loop}<tr><td></td></tr>{end_loop}</table>'?>
        <div class="row m-b-5">
          <div class="col-xs-2">
            <label></label>
          </div>
          <div class="col-xs-9">
            <textarea type="text" name="message" class="message" id="message" cols="45"><?php echo $form_data['message'];?></textarea>
          </div>
        </div> <!-- rowClose -->
    <?php } ?>

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

</script>