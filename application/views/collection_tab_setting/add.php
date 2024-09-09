<?php  $users_data = $this->session->userdata('auth_users'); ?>
<div class="modal-dialog">
  <div class="overlay-loader" id="overlay-loader">
    <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 
    <div class="modal-header" style="padding:1em;">
    

        <div class="row">
          <div class="col-xs-2"><strong>Title</strong></div>
          <div class="col-xs-3"><strong>Variable Name </strong></div>
          <div class="col-xs-3"><strong>Value</strong></div>
          <div class="col-xs-2"><strong>Order By</strong></div>
          <div class="col-xs-2"><strong>Print Status</strong></div>
        </div> <!-- innerRow -->
      
     
  </div>
  <div class="modal-body">
    <form id="collection_tab_setting_form">








      <?php
      //if(in_array('245',$users_data['permission']['action']))
      //{
        if(!empty($collection_tab_setting_list))
        {
          foreach($collection_tab_setting_list as $tab_setting_list)
          {
            ?> 
                <div class="row mb-5">
                  <div class="col-xs-2">
                    <strong><?php echo $tab_setting_list->var_title; ?></strong> <!-- text-uppercase -->
                    <input type="hidden" name="data[<?php echo $tab_setting_list->id; ?>][var_title]" value="<?php echo $tab_setting_list->var_title; ?>">
                  </div>
                  <div class="col-xs-3">
                    <input class="form-control text-13px"  name="data[<?php echo $tab_setting_list->id; ?>][setting_name]" value="<?php echo $tab_setting_list->setting_name; ?>"  onkeypress="return onlyAlphabets(event,this);" type="text" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" <?php if(!empty($tab_setting_list->setting_name)){ echo 'readonly'; } ?>>
                  </div>
                 
                  <div class="col-xs-3">
                     
                      <input class="" name="data[<?php echo $tab_setting_list->id; ?>][setting_value]" value="<?php echo $tab_setting_list->setting_value; ?>" type="text" placeholder="Custom Name" data-toggle="tooltip"  title="Custom Name" class="tooltip-text">
                  
                  </div>

                  <div class="col-xs-2">
                    <input class="w-100px" onkeypress="return isNumberKey(event);" name="data[<?php echo $tab_setting_list->id; ?>][order_by]" value="<?php echo $tab_setting_list->order_by; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                  </div>

                  <div class="col-xs-2">
                    <input type="checkbox" class="w-100px" name="data[<?php echo $tab_setting_list->id; ?>][print_status]"  value="1" <?php if($tab_setting_list->print_status==1){ ?> checked="checked" <?php } ?>>
                  </div> <!-- 2 -->
               


            </div> <!-- row -->

            <?php
          }
        }
        ?> 
       <div class="modal-footer">
                <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i>  Save</button>
               <button type="button" class="btn-cancel" data-number="2">Close</button>
        </div>
               
        <?php // } ?>
      </form>
    </div>
  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->
<?php
//$this->load->view('include/footer');
?>
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

  $("#collection_tab_setting_form").on("submit", function(event) { 
    event.preventDefault(); 
    $('.overlay-loader').show();
    $.ajax({
      url: "<?php echo base_url(); ?>collection_tab_setting/",
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
  $("button[data-number=2]").click(function(){
    $('#load_collection_setting_popup').modal('hide');
});
</script>