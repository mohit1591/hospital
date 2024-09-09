<style>
  .btn-default {
    width: 198px !important;
  }
</style>
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="age_vaccination" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">  

        <div class="row">
          <div class="col-md-12 m-b1">
            <div class="row">
              <div class="col-md-3">
              <label>Vaccination</label>
              </div>
              <div class="col-md-5">
                <select name="vaccine" id="vaccine" class="form-control">
                <option value="">Select Vaccination</option>
                <?php foreach($vaccination_list as $vaccine_list){?>
                <option value="<?php echo $vaccine_list->id;?>" <?php if(isset($form_data['vaccine']) && $form_data['vaccine']==$vaccine_list->id){echo 'selected';}?>><?php echo $vaccine_list->vaccination_name;?></option>
                <?php } ?>

                </select>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
        </div> <!--row -->  
        <div class="row"> 
         <div class="col-md-12 m-b1">
          <h4 style="border-bottom: 1px solid #ccc;">Recommended Age</h4>
          <div class="row">
            <div class="col-md-12 m-b1">
         
              <div class="row">
                   
                <div class="col-md-3">
                  <label>Age</label>
                </div>
                <?php if(!empty($recommended_age)) 
                {

                  

                    $age_list_arr=array();
                  foreach($recommended_age as $ages)
                  {
                    $age_list_arr[]=$ages->age_id;
                  }  
                }?>
                <div class="col-md-5">
                  <select name="age_text_recommended[]" id="age_text_recommended" class="multi_type" multiple="multiple">
                      <?php foreach($age_list as $age_data)
                      {
                       $selected='';
                          if(in_array($age_data->id,$age_list_arr))
                          {
                           
                            $selected='selected="selected"';
                          }
                          else
                          {
                            $selected='';
                          }
                        
                      ?>
                      <option value="<?php echo $age_data->id;?>" <?php echo $selected;?>><?php echo $age_data->title;?></option>
                      <?php } ?>
                 </select>
                </div>
              </div> <!-- innerrow -->
              
            </div> <!-- 12 -->
          </div> <!-- row -->
       </div>
      </div>
     


        <div class="row"> 
         <div class="col-md-12 m-b1">
          <h4 style="border-bottom: 1px solid #ccc;">Age for Catchup</h4>
          <div class="row">
            <div class="col-md-12 m-b1">
         
              <div class="row">
                   
                <div class="col-md-3">
                    <label>Age</label>
                </div>
                <?php if(!empty($catchup_age)) 
                {

                  $age_list_arr_catchup=array();
                  foreach($catchup_age as $ages)
                  {
                    $age_list_arr_catchup[]=$ages->age_id;
                  }
                }?>
                <div class="col-md-5">
                  <select name="age_text_catchup[]" id="age_text_catchup" class="multi_type" multiple="multiple">
                      <?php foreach($age_list as $age_data){

                         $selected='';
                          if(in_array($age_data->id,$age_list_arr_catchup))
                          {
                            
                            $selected='selected="selected"';
                          }
                          else
                          {
                            $selected='';
                          }

                        ?>
                      <option value="<?php echo $age_data->id;?>" <?php echo $selected;?>><?php echo $age_data->title;?></option>
                      <?php } ?>
                 </select>
                </div>
              </div> <!-- innerrow -->
              
            </div> <!-- 12 -->
          </div> <!-- row -->
          
      </div>
      </div>

      <div class="row"> 
       <div class="col-md-12 m-b1">
        <h4 style="border-bottom: 1px solid #ccc;">Age for Certain High-Risk Groups </h4>
        <div class="row">
          <div class="col-md-12 m-b1">
       
            <div class="row">
                 
              <div class="col-md-3">
                  <label>Age</label>
              </div>
                <?php if(!empty($risk_age)) 
                {

                  $age_list_arr_risk=array();
                foreach($risk_age as $ages)
                {
                  $age_list_arr_risk[]=$ages->age_id;
                }

                }?>
              <div class="col-md-5">
                <select name="age_text_risk_group[]" id="age_text_risk_group" class="multi_type" multiple="multiple">
                    <?php foreach($age_list as $age_data)
                    {
                      $selected='';
                      if(in_array($age_data->id,$age_list_arr_risk))
                      {
                        
                        $selected='selected="selected"';
                      }
                      else
                      {
                        $selected='';
                      }

                    ?>

                    <option value="<?php echo $age_data->id;?>" <?php echo $selected;?>><?php echo $age_data->title;?></option>
                    <?php } ?>
               </select>
              </div>
            </div> <!-- innerrow -->
            
          </div> <!-- 12 -->
        </div> <!-- row -->
      </div>
    </div>


       <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status</label>
                </div>
                <div class="col-md-8">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  


      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     

<script>  

$('.Number').keypress(function (event) {
    var keycode = event.which;
    if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
        event.preventDefault();
    }
});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#age_vaccination").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Age Vaccination successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Age Vaccination successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('pediatrician/age_vaccination/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_age_vaccination_modal_popup').modal('hide');
        flash_session_msg(msg);
        reload_table();
      } 
      else
      {
        $("#load_add_age_vaccination_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_age_vaccination_modal_popup').modal('hide');
});

  $(document).ready(function() {
        $('.multi_type').multiselect({
          'includeSelectAllOption':true,
          'maxHeight':200,
          'enableFiltering':true
        });
    });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->