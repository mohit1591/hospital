<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<style>
  
  .ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
   <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4><?php echo $page_title; ?></h4>
         </div>
        <!--  <form method="post" id="driver_form" action="<?php echo base_url(); ?>ambulance/driver" > -->
           <form  id="driver_form" class="form-inline"> 
      
            <input type="hidden" name="data_id" id="driver_id" value="<?php echo $form_data['data_id']; ?>">
         <div class="modal-body">
               <div class="row mb-2">
                  <label class="col-md-4">Driver Name <span class="text-danger">*</span></label>
                  <div class="col-md-8">
                     <input type="text" name="driver_name" value="<?php echo $form_data['driver_name']; ?>" onkeydown="return alphaOnly(event);">
                      <?php if(!empty($form_error)){ echo form_error('driver_name'); } ?>
                  </div>
                   
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">DL No. <span class="text-danger">*</span></label>
                  <div class="col-md-8">
                     <input type="text" name="licence_no" value="<?php echo $form_data['licence_no']; ?>">
                      <?php if(!empty($form_error)){ echo form_error('licence_no'); } ?>
                  </div>
                 
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">DL Expiry Date. <span class="text-danger">*</span></label>
                  <div class="col-md-8">
                     <!--<input type="text" name="dl_expiry_date" class="datepicker dl_expiry_date" value="<?php echo $form_data['dl_expiry_date']; ?>">-->
                      <input name="dl_expiry_date" id="dl_expiry_date" class="datepicker datepicker_to dl_expiry_date m_input_default" value="<?php echo $form_data['dl_expiry_date']?>" type="text">
                      <?php if(!empty($form_error)){ echo form_error('dl_expiry_date'); } ?>
                  </div>
                 
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Mobile No. <span class="text-danger">*</span></label>
                  <div class="col-md-8">
                     <input type="text" name="mobile_no" value="<?php echo $form_data['mobile_no']; ?>" maxlength="10" onkeypress="return numericvalue(event);" >
                      <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                  </div>
                 
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Email Id</label>
                  <div class="col-md-8">
                     <input type="text" name="email" value="<?php echo $form_data['email']; ?>">
                  </div>

               </div>
               <div class="row mb-2">
                  <label class="col-md-4">DOB</label>
                  <div class="col-md-8">
                     <input type="text" name="dob" class="datepicker" value="<?php echo $form_data['dob']; ?>">
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Guardian Name</label>
                  <div class="col-md-8">
                     <input type="text" name="guardian_name" value="<?php echo $form_data['guardian_name']; ?>" onkeydown="return alphaOnly(event);">
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Guardian Mobile</label>
                  <div class="col-md-8">
                     <input type="text" name="gaurdian_mob" value="<?php echo $form_data['gaurdian_mob']; ?>" maxlength="10" onkeypress="return numericvalue(event);" >
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Guardian Relation</label>
                  <div class="col-md-8">
                     <select name="relation" id="" class="">
                        <option value="">Select</option>
                          <?php
                  if(!empty($relation_list))
                  {
                    foreach($relation_list as $relation)
                    {
                      $selected_relation = "";
                      if($relation->id==$form_data['relation'])
                      {
                        $selected_relation = "selected='selected'";
                      }
                      echo '<option value="'.$relation->id.'" '.$selected_relation.'>'.$relation->relation.'</option>';
                    }
                  }
                  ?> 
                     </select>
                    <a class="btn-new" href="javascript:void(0)" onClick="relation_modal()"><i class="fa fa-plus"></i> New</a>
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Address</label>
                  <div class="col-md-8">
                     <textarea name="address" id=""><?php echo $form_data['address']; ?></textarea>
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Country</label>
                  <div class="col-md-8">
                     <select name="country_id" id="country_id" onChange="return get_state(this.value);">
                        <option value="">Select Country</option>
                  <?php
                  if(!empty($country_list))
                  {
                    foreach($country_list as $country)
                    {
                      $selected_country = "";
                      if($country->id==$form_data['country_id'])
                      {
                        $selected_country = 'selected="selected"';
                      }
                      echo '<option value="'.$country->id.'" '.$selected_country.'>'.$country->country.'</option>';
                    }
                  }
                  ?> 
                     </select>
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">State</label>
                  <div class="col-md-8">
                     <select name="state_id" id="state_id" onChange="return get_city(this.value)">
                           <option value="">Select State</option>
              <?php
             if(!empty($form_data['country_id']))
             {
                $state_list = state_list($form_data['country_id']); 
                if(!empty($state_list))
                {
                   foreach($state_list as $state)
                   {  
                    ?>   
                      <option value="<?php echo $state->id; ?>" <?php if(!empty($form_data['state_id']) && $form_data['state_id'] == $state->id){ echo 'selected="selected"'; } ?>><?php echo $state->state; ?></option>
                    <?php
                   }
                }
             }
            ?>
                     </select>
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">City</label>
                  <div class="col-md-8">
                     <select name="city_id" id="city_id">
                           <option value="0">Select City</option>
              <?php
               if(!empty($form_data['state_id']))
               {
                  $city_list = city_list($form_data['state_id']);
                  if(!empty($city_list))
                  {
                     foreach($city_list as $city)
                     {
                      ?>   
                        <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>>
                        <?php echo $city->city; ?> 
                        </option>
                      <?php
                     }
                  }
               }
              ?>
                     </select>
                  </div>
               </div>
               <div class="row mb-2">
                  <label class="col-md-4">Pincode</label>
                  <div class="col-md-8">
                     <input type="text" name="pincode" value="<?php echo $form_data['pincode']; ?>">
                  </div>
               </div>
         </div>
    
         <div class="modal-footer">
            <!-- <button type="button" id="form_submit" class="btn-save driver_save">Save</button> -->
             <input type="submit"  class="btn-update" name="submit" value="Save" />
            <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
         </div>
           </form>   
           <script>
            /****** Only Alphabet **********/
         function alphaOnly(event) {
          var key = event.keyCode;
        //   alert(key);
          return ((key >= 65 && key <= 90 ) || key == 8 || key== 32);
        };
        
  /****** Only Alphabet **********/
  /****** Only Numeric **********/
           
    function numericvalue(event){
        var e = event.keyCode;
         if (e != 8 && e != 0 && (e < 48 || e > 57))
         {
             return false;
            }
        else{
         
        }
    }
         
   /****** Only Numeric **********/     
           </script> 
<script>  

   $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
   /* endDate : new Date(),*/
    autoclose: true,
  });
 
   $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
 });

 
 
   function get_state(country_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#state_id').html(result); 
      } 
    });
    get_city(); 
  }

  function get_city(state_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#city_id').html(result); 
      } 
    }); 
  }

  $("#driver_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();

  var msg='Driver updated successfully';
   
  $.ajax({
    url: "<?php echo base_url('ambulance/driver/add'); ?>",
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


  function relation_modal()
  {
      var $modal = $('#load_add_relation_modal_popup');
      $modal.load('<?php echo base_url().'relation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  $(document).ready(function(){
  $('#load_add_relation_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
}); 
   </script>
   <div id="load_add_relation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->