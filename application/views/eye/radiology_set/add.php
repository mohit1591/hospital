<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<div class="modal-dialog " style="max-width:80%;">
  <div class="overlay-loader" id="overlay-loader">
    <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 



   <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h4><?php echo $page_title; ?></h4>
  </div>
  <form  id="radiology_set_form" class="form-inline"> 
   <input type="hidden" name="data_id" id="lab_set_id" value="<?php echo $form_data['data_id']; ?>">
   <div class="modal-body">


    <div class="row mb-2">
      <div class="col-md-4">
      <label>Set Name : <span class="text-danger">*</span></label>
        <input type="text" name="set_name" value="<?php echo $form_data['set_name']; ?>" required>
        <?php if(!empty($form_error)){ echo form_error('set_name'); } ?>                              
      </div>

      <label class="col-md-2">Speciality : </label>
      <div class="col-md-4">
        <select name="speciality"  id="speciality" >
          <option value="">Select Any Speciality</option>
                 <?php
                if(!empty($speciality))
                {
                  foreach($speciality as $speci)
                  {
                    $lab_select = "";
                    if($speci->id==$form_data['speciality'])
                    {
                      $lab_select = "selected='selected'";
                    }
                    echo '<option value="'.$speci->id.'" '.$lab_select.'>'.$speci->specialization.'</option>';
                  }
                }
                ?>
              </select> 
               <?php if(!empty($form_error)){ echo form_error('speciality'); } ?>                           
      </div>
    </div>




        <div class="row mb-2">
        

          <div class="col-md-3" >
            <div><b>X-Rays</b></div>

            <div class="boxleft">
              <select size="15" class="dropdown-box" name="ophthal_investigation"  id="investig" tabindex="14" >
                 <?php
                if(!empty($x_ray_list))
                {
                  foreach($x_ray_list as $lab_test)
                  {
                    $lab_select = "";
                    if($lab_test->id==$form_data['lab_test'])
                    {
                      $lab_select = "selected='selected'";
                    }
                    echo '<option value="'.$lab_test->id.'" '.$lab_select.'>'.$lab_test->test_name.'</option>';
                  }
                }
                ?>
              </select>
            </div> 

          </div>

          <div class="col-md-3" >
            <div><b>MRI,etc.</b></div>

            <div class="boxleft">
              <select size="15" class="dropdown-box" name="ophthal_investigation"  id="mri_test" tabindex="14" >
                 <?php
                if(!empty($mri_list))
                {
                  foreach($mri_list as $mri_test)
                  {
                    $lab_select = "";
                    if($mri_test->id==$form_data['mri_test'])
                    {
                      $lab_select = "selected='selected'";
                    }
                    echo '<option value="'.$mri_test->id.'" '.$lab_select.'>'.$mri_test->test_name.'</option>';
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

            $i=80;  foreach($investigations as $investigation) {

              ?>

          <div class="row test_add_<?php echo $i ;?>" >
            <div class="col-md-5"> 
              <input type="text" name="investig_name[]" id="investig_name" class="w-200px" value="<?php echo $investigation->investig_name ;?>">
              <input type="hidden" name="investig_id[]"  class="w-150px" value=" <?php echo $investigation->investigation_id; ?>">
            </div>
               <div class="col-md-1"> </div>
              <div class="col-md-4"><a href="javascript:void(0);">
                <button type="button" class="btn-custom aa_<?php echo $i ;?>" onclick="return remove_test('<?php echo $i ;?>');"><i class="fa fa-times"></i></button>
              </a>
            </div>

          </div>
          <input type="hidden" name="test_id[]" value="<?php echo $investigation->test_id; ?>">
        <?php $i++;}} else{ $btn='disabled'; } ?>
          </div>
        </div> 



      </div>
      <div class="modal-footer">
        <input type="submit" class="btn-save"  <?php echo $btn; ?> value="Save">
        <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
    </form>    
    <script>

     $(document).ready(function(){
   
      var count = 0;
      $('#investig').change(function(){
        var invest=$('#investig').val();
        var investig_name= $('#investig option:selected').html();
        $('#append_div').append('<div class="row test_add_'+count+'" ><div class="col-md-5"> <input type="text" name="investig_name[]" id="investig_name" class="w-200px" value="'+investig_name +' "><input type="hidden" name="investig_id[]"  class="w-150px" value="'+invest +' "> </div> <div class="col-md-1"> </div><div class="col-md-2"><a href="javascript:void(0);"><button type="button" class="btn-custom aa_'+count+'" onclick="return remove_test('+count+');"><i class="fa fa-times"></i></button></a></div></div> <input type="hidden" name="test_id[]" value="">');
         count++;
         if(count>=1)
          {
           $(':input[type="submit"]').prop('disabled', false);
          }
          else{
            $(':input[type="submit"]').prop('disabled', true);
          }


         })


       $('#mri_test').change(function(){
        var invest=$('#mri_test').val();
        var investig_name= $('#mri_test option:selected').html();   

          $('#append_div').append('<div class="row test_add_'+count+'" ><div class="col-md-5"> <input type="text" name="investig_name[]" id="investig_name" class="w-200px" value="'+investig_name +' "><input type="hidden" name="investig_id[]"  class="w-150px" value="'+invest +' "> </div> <div class="col-md-1"> </div><div class="col-md-2"><a href="javascript:void(0);"><button type="button" class="btn-custom aa_'+count+'" onclick="return remove_test('+count+');"><i class="fa fa-times"></i></button></a></div></div> <input type="hidden" name="test_id[]" value="">');


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



 $("#radiology_set_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var id=$('#lab_set_id').val();
  
  if(id=="")
  {
    var msg='Radiology Set saved successfully';
    var path="<?php echo base_url('eye/radiology_set/add'); ?>";
  }
  else{
    var msg='Radiology Set updated successfully';
    var path="<?php echo base_url('eye/radiology_set/edit/'); ?>"+id;
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