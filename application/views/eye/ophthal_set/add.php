<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<style>

  .ui-autocomplete { z-index:2147483647; }
</style>
<div class="modal-dialog " style="max-width:80%;">
  <div class="overlay-loader" id="overlay-loader">
    <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 



   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4><?php echo $page_title; ?></h4>
  </div>
  <form  id="ophthal_set_form" class="form-inline"> 
   <input type="hidden" name="data_id" id="ophthal_set_id" value="<?php echo $form_data['data_id']; ?>">
   <!--  <input type="hidden" name="vehi_type" id="vehi_type" value="<?php echo $form_data['vehicle_type']; ?>"> -->
   <div class="modal-body">


    <div class="row mb-2">
      <label class="col-md-3">Ophthal Laboratory Set Name : <span class="text-danger">*</span></label>
      <div class="col-md-8">
        <input type="text" name="ophthal_set_name" value="<?php echo $form_data['ophthal_set_name']; ?>" required>
        <?php if(!empty($form_error)){ echo form_error('ophthal_set_name'); } ?>                              
      </div>
    </div>


        <div class="row mb-2">
          <div class="col-md-3">
            <div><b>Eye region</b></div>
            <div class="boxleft">
              <select name="eye_region_test_head" id="eye_region" class="dropdown-box" multiple="" style="height:200px;">
                <option value="">ALL</option>
                <?php
                if(!empty($eye_region))
                {
                  foreach($eye_region as $region)
                  {
                    $region_select = "";
                    if($region->id==$form_data['eye_region_test_head'])
                    {
                      $region_select = "selected='selected'";
                    }
                    echo '<option value="'.$region->id.'" '.$region_select.'>'.$region->test_heads.'</option>';
                  }
                }
                ?>
              </select>
            </div>
          </div>

          <div class="col-md-3" >
            <div><b>Investigation</b></div>

            <div class="boxleft">
              <select size="9" class="dropdown-box" name="ophthal_investigation"  id="investig" tabindex="14" style="height:200px;">
                <?php
                if(!empty($ophthal_investigations))
                {
                  foreach($ophthal_investigations as $ophthal_investigation)
                  {
                    $investigation_select = "";
                    if($ophthal_investigation->id==$form_data['ophthal_investigation'])
                    {
                      $investigation_select = "selected='selected'";
                    }
                    echo '<h5  '.$region_select.'>'.$ophthal_investigation->test_heads.'</h5>';
                  }
                }
                ?>
              </select>
            </div> 

          </div>

        

          <div class="col-md-6" id="append_div">

            <?php         
            if(!empty($investigations))
            {
              $btn='';
            $i=80;
            foreach($investigations as $investigation) {
              ?>

          <div class="row test_add_<?php echo $i ;?>" >
            <div class="col-md-5"> 
              <input type="text" name="investig_name[]" id="investig_name" class="form-control" value="<?php echo $investigation['investig_name'] ;?>">
              <input type="hidden" name="investig_id[]"  class="form-control" value=" <?php echo $investigation['investigation_id']; ?>">
               </div>
               <div class="col-md-5">
                <select name="eye_side[]" id="eye_side" class="w-150px">
                  <option <?php if($investigation['eye_side']=='B/E'){ echo "selected";} ?> value="B/E">B/E</option>
                  <option   <?php if($investigation['eye_side']=='R'){ echo "selected";} ?> value="R">R</option>
                  <option  <?php if($investigation['eye_side']=='L'){ echo "selected";} ?> value="L">L</option>

                   <!--  <option value="" selected="selected">'.$region->test_heads.'</option> -->
                </select>
              </div>
              <div class="col-md-2"><a href="javascript:void(0);">
                <button type="button" class="btn-custom aa_<?php echo $i ;?>" onclick="return remove_test('<?php echo $i ;?>');"><i class="fa fa-times"></i></button>
              </a>
            </div>

          </div>
          <input type="hidden" name="test_id[]" value="<?php echo $investigation['test_id']; ?>">
        <?php $i++;} } else{ $btn='disabled'; } ?>
          </div>
        </div> 



      </div>
      <div class="modal-footer">
        <input type="submit" class="btn-save" <?php echo $btn; ?>  value="Save">
        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </form>    
    <script>

     $('#eye_region').change(function(){
      var test_head_id = $(this).val(); 
       $.ajax({url: "<?php echo base_url(); ?>eye/ophthal_set/eye_region_test/"+test_head_id, 
          success: function(result)
          {
             $('#investig').html(result);   
           } 
         }); 
     })  
     $(document).ready(function(){
      var count = 0;
      $('#investig').change(function(){
        var invest=$('#investig').val();
        var investig_name= $('#investig option:selected').html();
        $('#append_div').append('<div class="row test_add_'+count+'" ><div class="col-md-5"> <input type="text" name="investig_name[]" id="investig_name" class="form-control" value="'+investig_name +' "><input type="hidden" name="investig_id[]"  class="form-control" value="'+invest +' "> </div><div class="col-md-5"><select name="eye_side[]" id="eye_side'+invest+'" class="w-150px"><option value="B/E">B/E</option><option value="R">R</option><option value="L">L</option></select></div><div class="col-md-2"><a href="javascript:void(0);"><button type="button" class="btn-custom aa_'+count+'" onclick="return remove_test('+count+');"><i class="fa fa-times"></i></button></a></div></div> <input type="hidden" name="test_id[]" value="">');
         count++;

          if(count>=1)
          {
           $(':input[type="submit"]').prop('disabled', false);
          }
          else{
            $(':input[type="submit"]').prop('disabled', true);
          }


         })
 
});


function remove_test(count)
{
  $('.test_add_'+count).remove();
  count--;
   if(count>=1)
    {
     $(':input[type="submit"]').prop('disabled', false);
    }
    else{
      $(':input[type="submit"]').prop('disabled', true);
    }
}
</script>
     
<script>  

  
 $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});



 $("#ophthal_set_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();  
  var id=$('#ophthal_set_id').val();
  
  if(id=="")
  {
    var msg='Ophthal Set saved successfully';
    var path="<?php echo base_url('eye/ophthal_set/add'); ?>";
  }
  else{
    var msg='Ophthal Set updated successfully';
    var path="<?php echo base_url('eye/ophthal_set/edit/'); ?>"+id;
  }

  $.ajax({
    url: path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide'); 
        flash_session_msg(msg);
        reload_table(); 
      }
      else{

        $("#load_add_modal_popup").html(result);
      }
      $('#overlay-loader').hide();  
      
    }
  });
}); 

</script>

</div>

<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- /.modal-content -->     
</div><!-- /.modal-dialog -->