<?php
$users_data = $this->session->userdata('auth_users');

?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="emp_type" class="form-inline">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">

              <!-- // ====== From Here  -->
              <div class="row">
                <div class="col-xs-6">
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                         <strong>Hospital Code</strong>
                      </div>
                      <div class="col-xs-8">
                        <div class="dcode"><b><?php echo $form_data['hospital_code']; ?></b></div>
                        <input type="hidden" name="hospital_code" id="hospital_code" value="<?php echo $form_data['hospital_code']; ?>"/>
                      </div>
                    </div> <!-- row -->

                    

                   <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Hospital Name <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" name="hospital_name" id="hospital_name" value="<?php echo $form_data['hospital_name'] ?>" class="txt_firstCapital alpha_space_dot_space inputFocus" autofocus> 
                        <?php if(!empty($form_error)){ echo form_error('hospital_name'); } ?>
                      </div>
                    </div> <!-- row -->

                    


                  

                     <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Mobile No.<span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-8">
                       <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                        <input type="text" maxlength="10" data-toggle="tooltip"  title="Allow only numeric." class="numeric number" name="mobile_no" id="mobile_no"  value="<?php echo $form_data['mobile_no'] ?>">
                      
                      <?php if(!empty($form_error)){ echo form_error('mobile_no'); } ?>
                      </div>
                    </div> <!-- row -->
                     <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Email</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="email"   title="Email should be like abc@example.com." class="" id="" value="<?php echo $form_data['email']; ?>">
                      
                        <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Alternate Mobile</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="alt_mobile_no" maxlength="10"  data-toggle="tooltip"  title="Allow only numeric." class="numeric" id="alt_mobile_no" value="<?php echo $form_data['alt_mobile_no']; ?>">
                      
                          <?php if(!empty($form_error)){ echo form_error('alt_mobile_no'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>LandLine(R)</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="landline_no" data-toggle="tooltip"  title="Landline should be 13 digits." class="landline" maxlength="13"  id="landline_no" value="<?php echo $form_data['landline_no']; ?>">
                        
                        <?php if(!empty($form_error)){ echo form_error('landline_no'); } ?>
                      </div>
                    </div> <!-- row -->

                 <div class="row m-b-5">
                     <div class="col-xs-4" id="share_lable">
                        <strong>Share Details</strong>
                     </div>
                     <div class="col-xs-8" id="share_input">
                        <a href="javascript:void(0)" class="btn-commission" onclick="comission();"><i class="fa fa-cog"></i> Commission</a> 
                     </div>
                   
                  </div> <!-- row -->
                    

                 
                   
                   
                    <div class="row m-b-5">
                         <div class="col-xs-4">
                              <strong>Status</strong>
                         </div>
                         <div class="col-xs-8">
                              <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active 
                              <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive 
                         </div>
                    </div>


                </div> <!-- 6 // Left -->





                <!-- Right portion from here -->
                <div class="col-xs-6">

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Address</strong>
                      </div>
                      <div class="col-xs-8">
                        <textarea name="address"><?php echo $form_data['address']; ?></textarea>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Country</strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="country_id" id="country_id" onchange="return get_state(this.value);">
                          <option value="">Select Country</option>
                          <?php
                          if(!empty($country_list))
                          {
                            foreach($country_list as $country)
                            {
                               $selected = "";
                               if($form_data['country_id']==$country->id)
                               {
                                 $seleted = 'selected="selected"';
                               }
                               echo '<option value="'.$country->id.'" '.$seleted.'>'.$country->country.'</option>';
                            }
                          }
                          ?>
                        </select>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>State</strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="state_id" id="state_id" onchange="return get_city(this.value);">
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
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>City</strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="city_id" id="city_id">
                          <option value="">Select City</option> 
                            <?php
                             if(!empty($form_data['state_id']))
                             {
                                $city_list = city_list($form_data['state_id']);
                                if(!empty($city_list))
                                {
                                   foreach($city_list as $city)
                                   {
                                    ?>   
                                      <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>><?php echo $city->city; ?></option>
                                    <?php
                                   }
                                }
                             }
                            ?>
                        </select>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>PIN Code</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="pincode" data-toggle="tooltip"  title="Pin code should be max digit." class="numeric"  maxlength="6" id="pincode"  value="<?php echo $form_data['pincode']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('pincode'); } ?>
                       
                      </div>
                    </div> <!-- row -->

                   

                    
                  
                   

                  
                </div> <!-- 6 // Right -->
              </div> <!-- ROW -->



              <!-- // Till Here -->




            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Save" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
});
$(".txt_firstCapital").on('keyup', function(){

   var str = $('.txt_firstCapital').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCapital').val(part_val.join(" "));
  
  }); 
</script>
<script>   

function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
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
function rate_modal()
  {
      var $modal = $('#load_add_rate_modal_popup');
      $modal.load('<?php echo base_url().'rate/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }
function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#emp_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Hospital successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Hospital successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('hospital/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        get_hospital();
        reload_table();
        flash_session_msg(msg); 
        reload_table();

      } 
      else
      {
        $("#load_add_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 

function get_hospital()
{
   $.ajax({url: "<?php echo base_url(); ?>hospital/hospital_dropdown/", 
    success: function(result)
    {
      $('#referral_hospital').html(result); 
    } 
  });
}

function get_comission(id)
{
  $.ajax({
    dataType: "json",
    url: "<?php echo base_url(); ?>hospital/get_hospital_comission/"+id, 
    success: function(result)
    {

      $('#share_lable').html('<strong>'+result.lable+'</strong>'); 
      $('#share_input').html(result.inputs); 
    } 
  });
}

function comission(ids)
{ 
  var $modal = $('#load_add_hospital_comission_modal_popup');
  $modal.load('<?php echo base_url().'hospital/add_hospital_comission/' ?>',
  {
    //'id1': '1',
    'id': '<?php echo $form_data['data_id']; ?>'
    },
  function(){
  $modal.modal('show');
  });
} 


   

$(document).ready(function() {
  $('#load_add_hospital_comission_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});


$(document).ready(function() {
  $('#load_add_rate_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    endDate : new Date(),
    autoclose: true,
    startView: 2
  })
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->   
</div><!-- /.modal-dialog -->  

<div id="load_add_hospital_comission_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- <div id="load_add_rate_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div> -->