<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form id="widal_form"  class="form-inline">
      <div class="modal-header">
          <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div> 

      <input type="hidden" name="data_id" value="<?php echo $widal->id;?>">
      <input type="hidden" name="booking_id" value="<?php echo $booking_id;?>">
      <input type="hidden" name="test_id" value="<?php echo $test_id;?>">

      <div class="modal-body canteen_box">   
        <table class="table table-bordered">
          <thead>
            <th width="17%"></th>
            <th width="17%">1:20</th>
            <th width="17%">1:40</th>
            <th width="17%">1:80</th>
            <th width="17%">1:160</th>
            <th width="17%">1:320</th>
          </thead>
          <tbody>
            <tr>
              <th style="text-align: left;">TYPHY"O"</th>
                <td> <input type="text" name="typho_1" value="<?php echo $widal->typho_1;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typho_2" value="<?php echo $widal->typho_2;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typho_3" value="<?php echo $widal->typho_3;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typho_4" value="<?php echo $widal->typho_4;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typho_5" value="<?php echo $widal->typho_5;?>" class="form-control plus_minus"> </td>
            </tr>
          
            <tr>
              <th style="text-align: left;">TYPHI"H"</th>
                 <td> <input type="text" name="typhh_1" value="<?php echo $widal->typhh_1;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhh_2" value="<?php echo $widal->typhh_2;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhh_3" value="<?php echo $widal->typhh_3;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhh_4" value="<?php echo $widal->typhh_4;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhh_5" value="<?php echo $widal->typhh_5;?>" class="form-control plus_minus"> </td>
            </tr>
            <tr>
              <th style="text-align: left;">TYPHI"AH"</th>
                <td> <input type="text" name="typhah_1" value="<?php echo $widal->typhah_1;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhah_2" value="<?php echo $widal->typhah_2;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhah_3" value="<?php echo $widal->typhah_3;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhah_4" value="<?php echo $widal->typhah_4;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhah_5" value="<?php echo $widal->typhah_5;?>" class="form-control plus_minus"> </td>
            </tr>
            <tr>
              <th style="text-align: left;">TYPHI"BH"</th>
                <td> <input type="text" name="typhbh_1" value="<?php echo $widal->typhbh_1;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhbh_2" value="<?php echo $widal->typhbh_2;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhbh_3" value="<?php echo $widal->typhbh_3;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhbh_4" value="<?php echo $widal->typhbh_4;?>" class="form-control plus_minus"> </td>
                <td> <input type="text" name="typhbh_5" value="<?php echo $widal->typhbh_5;?>" class="form-control plus_minus"> </td>
            </tr>

          </tbody>
        </table>
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
</form>     

<script>  
 
$("#widal_form").on("submit", function(event) { 
  event.preventDefault(); 

  $('#overlay-loader').show();
  var path = 'update_widal_value';
  var msg = 'Test value successfully updated.';

  $.ajax({
    url: "<?php echo base_url('test/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_inventory_modal_popup').modal('hide');
        flash_session_msg(msg);  
      } 
      else
      {
        $("#load_add_inventory_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$('.plus_minus').click(function(){
   var value = $(this).val();
   if(value=='+')
   {
    $(this).val('-');
   }
   else if(value=='-')
   {
    $(this).val('+');
   }
   else{
     $(this).val('+');
   }
})
  


</script>  

</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->