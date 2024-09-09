<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
     </div>
     <div class="modal-content"> 
          <form  id="email_form" class="form-inline">
               <input type="hidden" name="comp_id" id="comp_id" value="<?php echo $form_data['comp_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close p-t-0" data-dismiss="modal" aria-label="Close">&times;</button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">  
               <!-- ============================================================================================ -->
                    <div class="row m-b-5">
                         <div class="col-xs-12">
                            <div class="col-xs-6">
                              <div class="row m-b-5">
                                  <div class="col-xs-5">
                                      <strong>Company Logo</strong>
                                   </div>
                                        <div class="col-xs-7">
                                             
                                             <div class="photo_update_company_setting">
                                                  <?php
                                                       $img_path  = $file_img = base_url('assets/images/photo.png');
                                                       if(!empty($form_data['comp_id']) && !empty($form_data['old_img'])){
                                                            $img_path = ROOT_UPLOADS_PATH.'logo/'.$form_data['old_img'];
                                                       }  
                                                  ?>
                                                  <img id="pimg" src="<?php echo $img_path; ?>" class="img-responsive">
                                             </div>
                                        </div>
                                  </div> <!-- row -->

                                  <div class="row m-b-5">
                                        <div class="col-xs-5">
                                             <strong>Select Image</strong>
                                        </div>
                                        <div class="col-xs-7">
                                             <input type="hidden" name="old_img"  value="<?php echo $form_data['old_img']; ?>" />
                                             <input type="file" id="img-input" accept="image/*" name="photo">
                                             <?php
                                                  if(isset($photo_error) && !empty($photo_error)){
                                                       echo '<div class="text-danger">'.$photo_error.'</div>';
                                                  }
                                             ?>
                                        </div>
                                  </div>
                                     
                                   
                              
                          
                         

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong> Company Name <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="text"  name="company_name" value="<?php echo $form_data['company_name']; ?>">
                                        <?php if(!empty($form_error)){ echo form_error('company_name'); } ?>
                                   </div>
                              </div>
                                <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong> Email <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="text"  name="email" value="<?php echo $form_data['email']; ?>">
                                        <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                                   </div>
                              </div>
                            
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong> Address<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <textarea name="address" id="address"><?php echo $form_data['address']; ?></textarea>
                                       <?php if(!empty($form_error)){ echo form_error('address'); } ?>
                                   </div>
                              </div>

                              <div class="row m-b-5 ">
                                   <div class="col-md-5">
                                        <strong>Choose Country<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                      
                                        
                                             <select id="country_id" name="country_id" onchange="return get_state(this.value);" >
                                             <option value="">Select Country</option>
                                                  <?php
                                                       if(!empty($country_list)){
                                                            $selected = "";
                                                            foreach($country_list as $country){
                                                                 if($form_data['country_id'] == $country->id){
                                                                      $selected = "selected = 'selected'";
                                                                 }
                                                                 else{
                                                                           $selected = "";
                                                                      }
                                                                 echo '<option value="'.$country->id.'" '.$selected.'>'.$country->country.'</option>';
                                                            } 
                                                       }
                                                  ?>
                                             </select>
                                            
                                             <?php if(!empty($form_error)){ echo form_error('country_id'); } ?>

                                        
                                   </div>
                              </div>
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                     <strong>  State  <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        
                                       
                                        <select id="state_id" name="state_id" onchange="return get_city(this.value)">
                                                  <option value="">Select State</option>
                                                  <?php
                                                       if(!empty($state_list)){
                                                            $selected = "";
                                                            foreach($state_list as $state){
                                                                 if($form_data['state_id'] == $state->id){
                                                                      $selected = "selected = 'selected'";
                                                                 }
                                                                 else{
                                                                      $selected = "";
                                                                 }
                                                                 echo '<option value="'.$state->id.'" '.$selected.'>'.$state->state.'</option>';
                                                            } 
                                                       }
                                                  ?>
                                             </select>
                                            
                                             <?php if(!empty($form_error)){ echo form_error('state_id'); } ?>
                                       
                                   </div>
                              </div>

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                       <strong>City<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                       
                                        
                                             <select id="city_id" name="city_id" >
                                                  <option value="">Select City</option>
                                                  <?php
                                                       if(!empty($city_list)){
                                                            $selected = "";
                                                            foreach($city_list as $city){
                                                                 if($form_data['city_id'] == $city->id){
                                                                      $selected = "selected = 'selected'";
                                                                 }
                                                                 else{
                                                                      $selected = "";
                                                                 }
                                                                 echo '<option value="'.$city->id.'" '.$selected.'>'.$city->city.'</option>';
                                                            } 
                                                       }
                                                  ?>
                                             </select>
                                           
                                             <?php if(!empty($form_error)){ echo form_error('city_id'); } ?>
                                          </div> 
                                   </div>
                              </div> <!-- 12 -->

                              <div class="col-xs-6">
                                    <div class="row m-b-5">
                                    <strong> Patient Login Theme</strong>
                                   <div class="col-md-5">
                                        
                                   </div>
                                   <div class="col-md-7">
                                       
                                   </div>
                              </div>
                             
                             <div class="row m-b-5">
                                  <div class="col-md-5">
                                     <strong> Select Theme</strong>
                               
                                   </div>
                                   <div class="col-md-7">
                                       <input type="color" disabled value="<?php echo $form_data['theme']; ?>"/>
                                       <input type="text" id="full" name="theme" value="<?php echo $form_data['theme']; ?>"/>

                                   </div>
                              </div>
                              <div class="row m-b-5">
                                  <div class="col-xs-5">
                                      <strong>Banner Image</strong>
                                   </div>
                                        <div class="col-xs-7">
                                             
                                             <div class="photo_update_company_setting">
                                                  <?php
                                                       $img_path  = $file_img = base_url('assets/images/photo.png');
                                                       if(!empty($form_data['comp_id']) && !empty($form_data['old_img_banner'])){
                                                            $img_path = ROOT_UPLOADS_PATH.'patient_login/banner/'.$form_data['old_img_banner'];
                                                       }  
                                                  ?>
                                                  <img id="pbimg" src="<?php echo $img_path; ?>" class="img-responsive">
                                             </div>
                                        </div>
                                  </div> <!-- row -->

                               <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Select Banner Image</strong>
                                   </div>
                                   <div class="col-md-7">
                                      <div class="col-xs-7">
                                             <input type="hidden" name="old_img_banner"  value="<?php echo $form_data['old_img_banner']; ?>" />
                                             <input type="file" id="bimg-input1" accept="image/*" name="photo_banner">
                                              <?php
                                                  if(isset($photo_banner_error) && !empty($photo_banner_error)){
                                                       echo '<div class="text-danger">'.$photo_banner_error.'</div>';
                                                  }
                                             ?>
                                            
                                        </div>
                                   </div>
                              </div>
                               <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Logo URL</strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="text"  name="logo_url" value="<?php echo $form_data['logo_url']; ?>">
                                       
                                   </div>
                              </div>
                               <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Punch Line</strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="text"  name="punch_line" value="<?php echo $form_data['punch_line']; ?>">
                                      
                                   </div>
                              </div>
                              <?php
                              if(!empty($form_data['auth_code']))
                              {
                              ?> 
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Patient Login URL</strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="text" readonly=""  name="patient_login_url" value="<?php echo base_url('hmas_patient_login/?auth=').$form_data['auth_code']; ?>">
                                      
                                   </div>
                              </div>
                              <?php
                               }
                              ?>
                              </div>
                             </div>    
                    </div> <!-- row -->
               </div>    <!--  modal-body --> 
               <div class="modal-footer"> 
                    <input type="submit"  class="btn-update" name="submit" value="Save" />
                    <button type="button" class="btn-cancel" data-number="2">Cancel</button>
               </div>
          </form>  
 

<script> 
$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
  
  /*function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });
 */

 function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });


     

  function readURLs(input) 
  {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pbimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

  $("#img-input").change(function(){
        readURL(this);
    });

  $("#bimg-input1").change(function(){
        readURLs(this);
    });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
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

 
$("#email_form").on("submit", function(event) { 
    
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#comp_id').val();
  
  if(ids!="" && !isNaN(ids))
  { 
     
    var path = 'edit/'+ids;
    var msg = 'Company Setting successfully updated.';
  }
  else
  {
     
    var path = 'add/';
    var msg = 'Company Setting successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>company_settings/"+path,
    type: "post",
     cache: false,
     contentType: false,
     processData: false,
    data: new FormData(this),
    success: function(result) {

      if(result==1)
      {
        

        $('#load_add_email_sett_modal_popup').modal('hide');
        reload_table();
        add_country();
        add_state();
        add_city();
        flash_session_msg(msg);
      } 
      else
            {

        $("#load_add_email_sett_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
  function add_country()
{
   $.ajax({url: "<?php echo base_url(); ?>company_settings/Country_list_dropdown/", 
    success: function(result)
    {
      
      $('#country_id').html(result); 
    } 
  });
}


  
function add_city()
{
   $.ajax({url: "<?php echo base_url(); ?>company_settings/City_list_dropdown/", 
    success: function(result)
    {
      
      $('#city_id').html(result); 
    } 
  });
}
function add_state()
{
   $.ajax({url: "<?php echo base_url(); ?>company_settings/State_list_dropdown/", 
    success: function(result)
    {
      
      $('#state_id').html(result); 
    } 
  });
}
}); 

  // $('.datepicker').datepicker({
  //   format: 'dd-mm-yyyy',
  //   endDate : new Date(),
  //   autoclose: true,
  //   startView: 2
  // })



$("button[data-number=2]").click(function(){
  
    $('#load_add_email_sett_modal_popup').modal('hide');
 
   
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
      


   
</div><!-- /.modal-dialog -->

<script>
/*$(".basic").spectrum({
    color: "#f00",
    change: function(color) {     
        $("#basic-log").text("change called: " + color.toHexString());
    }
});*/
$("#full").spectrum({
    color: "#ECC",
    showInput: true,
    className: "full-spectrum",
    showInitial: true,
    showPalette: true,
    showSelectionPalette: true,
    maxSelectionSize: 10,
    preferredFormat: "hex",
    localStorageKey: "spectrum.demo",
    move: function (color) {
        
    },
    show: function () {
    
    },
    beforeShow: function () {
    
    },
    hide: function () {
    
    },
    change: function() {
        
    },
    palette: [
        ["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
        "rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
        ["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
        "rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
        ["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
        "rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
        "rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
        "rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
        "rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
        "rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
        "rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
        "rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
        "rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
        "rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
    ]
});
</script>
