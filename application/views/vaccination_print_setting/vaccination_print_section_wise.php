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

<?php if($sub_section==1){
  ?>
   <input type="hidden" name="sub_section_data" id="sub_section_data" value ="<?php echo $sub_section;?>"/>
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
    
    <?php  $table='<table>{start_loop}<tr><td></td></tr>{end_loop}</table>'?>
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
      <textarea type="text" name="short_code"  class="print_textarea"   class="print_textarea"  cols="45" value="" readonly id="comment_data">
      <?php echo $form_data['short_code'];?></textarea>
      </div>
    </div> <!-- rowClose -->
    
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-9">
       <button class="btn-save" type="submit" name="remove_levels" onClick=" validateForm();" ><i class="fa fa-floppy-o"></i> Save</button>
             <a class="btn-anchor" href="<?php echo base_url();?>">
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>
    </div> <!-- rowClose -->

    <?php }?>

    <?php if($sub_section==2){?>
     <input type="hidden" name="sub_section_data" id="sub_section_data" value ="<?php echo $sub_section;?>"/>
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
    
    <?php  $table='<table>{start_loop}<tr><td></td></tr>{end_loop}</table>'?>
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
      <textarea type="text" name="short_code"  class="print_textarea"  cols="45" value="" readonly id="comment_data">
      <?php echo $form_data['short_code'];?></textarea>
      </div>
    </div> <!-- rowClose -->
    
    <div class="row m-b-5">
      <div class="col-xs-2">
        <label></label>
      </div>
      <div class="col-xs-9">
       <button class="btn-save" type="submit" name="remove_levels" onClick=" validateForm();" ><i class="fa fa-floppy-o"></i> Save</button>
             <a class="btn-anchor" href="<?php echo base_url();?>">
          <i class="fa fa-sign-out"></i> Exit
        </a>
      </div>
    </div> <!-- rowClose -->

    <?php }?>

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
      url: "<?php echo base_url(); ?>vaccination_print_setting/get_according_to_section/", 
      data: {value: value},
     
        success: function(result)
        {
            $('#all_report_section').html(result);
        } 
      });
 $('#all_report_section').removeClass('hide');
}
</script>