<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content">

  <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
          <h4>Preview</h4> 
      </div>
      
      <div class="modal-body" style="padding:0px;"> 
<?php 

    $template_data = str_replace("{margin_left}",$format_left,$template_data);
    $template_data = str_replace("{margin_right}",$format_right,$template_data);
    $template_data = str_replace("{margin_top}",$format_top,$template_data);
    $template_data = str_replace("{margin_bottom}",$format_bottom,$template_data);
    
   ?>
    <div style="margin:0 0 0 8%;">
    <?php echo $template_data; 


/* end leaser printing*/
?>
</div>
</div>
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
