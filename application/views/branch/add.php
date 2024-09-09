<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="branch_form" class="form-inline">
               <input type="hidden" name="data_id" id="branch_id" value="<?php echo $form_data['data_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">    
                    <?php
                   
                         if($total_branch>0){
                    ?> 
                         <div class="row">
                              <div class="col-sm-6">

                                   <div class="row m-b-5">
                                        <div class="col-md-5">
                                             <strong>Branch ID<span class="star">*</span></strong>
                                        </div> <!-- 4 -->
                                        <div class="col-md-7">
                                             <span> <?php echo $form_data['branch_code']; ?> </span>
                                        </div> <!-- 8 -->
                                   </div> <!-- row -->

                                 <div class="row">
                                   <div class="col-md-5">
                                        <strong>Branch Name<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="branch_name" id="branch_name" value="<?php echo $form_data['branch_name']; ?>" class="inputFocus"/>
                                        <?php if(!empty($form_error)){ echo form_error('branch_name'); } ?>
                                   </div> <!-- 8 -->
                                 </div> <!-- row -->


                              <div class="row m-b-5">
                                   <?php if($users_data['users_role']==1){ ?>
                                        <div class="col-md-5">
                                             <strong>Branch Type<span class="star">*</span></strong>
                                        </div> <!-- 4 -->
                                        <div class="col-md-7">
                                             <input type="radio" onclick = "get_branch_type(this.value);" name="branch_type" <?php if($form_data['branch_type']==1){ echo 'checked="checked"'; } ?> id="branch_type" value="1" /> Live 
                                             <input type="radio" onclick = "get_branch_type(this.value);" name="branch_type" <?php if($form_data['branch_type']==0){ echo 'checked="checked"'; } ?> id="branch_type" value="0" /> Demo 
                                             
                                             <!--<input type="radio" name="branch_type" < ?php if($form_data['branch_type']==2){ echo 'checked="checked"'; } ?> id="branch_type" value="2" /> Offline -->
                                        </div> <!-- 8 -->
                                   <?php }else{?>
                                        <input type="hidden" value="1" name="branch_type"/>
                                   <?php } ?>

                              </div> <!-- row -->

                              <div  id="trainingtype" style="display: block">
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Valid From<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" readonly = "readonly" ccontenteditable="false" lass="datepicker" name="start_date"  data-toggle="tooltip"  title="Enter Training Start Date" class="tooltip-text" id="startdate" value="<?php echo $form_data['start_date']; ?>" />
                                      
                                        <?php if(!empty($form_error)){ echo form_error('start_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Valid To<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text"  readonly = "readonly" class="datepicker" contenteditable="false"   data-toggle="tooltip"  title="Enter Training End Date." class="tooltip-text" name="end_date" id="enddate" value="<?php echo $form_data['end_date']; ?>" />
                                     
                                        <?php if(!empty($form_error)){ echo form_error('end_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              </div>

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Contact No.<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" data-toggle="tooltip"  title="Allow only numeric, '-' and max length 12 numbers." class="tooltip-text landline"  maxlength="12" name="contact_no" id="contact_no" value="<?php echo $form_data['contact_no']; ?>" />
                                     
                                        <?php if(!empty($form_error)){ echo form_error('contact_no'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Contact Person</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="contact_person" id="contact_person" value="<?php echo $form_data['contact_person']; ?>" />
                                     
                                        <?php if(!empty($form_error)){ echo form_error('contact_person'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Email<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="email"   data-toggle="tooltip"  title="Email should be like abc@example.com." class="tooltip-text email_address" id="email" value="<?php echo $form_data['email']; ?>" />
                                       
                                       
                                         <?php if(!empty($form_error)){ echo form_error('email'); } ?>
                                        </span>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->


                    
                        <!-- <div class="col-md-6">
                              <div class="row">
                                   <div class="col-md-4">
                                        <strong>Rate List <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-8">
                                         <select id="rate_id" name="rate_id" class="">
                                        <option value="">Select Rate Plan</option>
                                        <?php
                                        if(!empty($rate_list)){
                                        $selected = "";
                                        foreach($rate_list as $rate){
                                        if($form_data['rate_id'] == $rate->id){
                                        $selected = "selected = 'selected'";
                                        }
                                        echo '<option value="'.$rate->id.'" '.$selected.'>'.$rate->title.'</option>';
                                        }
                                        $selected = "";
                                        }
                                        ?>
                                        </select>
                                        <?php if(in_array('16',$users_data['permission']['action'])) {?>
                                             
                                                  <a href="javascript:void(0)" onclick="rate_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                                             
                                        <?php } ?>
                                        <?php if(!empty($form_error)){ echo form_error('rate_id'); } ?>
                                   </div>
                              </div>
                         </div> -->



                              <!-- <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Address<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                       <textarea rows="4" name="address" id="address" class="cbranch-txtar address"><?php //echo $form_data['address']; ?></textarea>
                                        
                                   </div>
                              </div> -->



                               <div class="row m-b-5">
                                   <div class="col-md-5">
                                 <strong>Address 1 <span class="star">*</span></strong>
                                 </div>
                                   <div class="col-md-7">
                                     <input type="text" name="address" id="address" class="address" maxlength="255" value="<?php echo $form_data['address']; ?>"/>
                                     <?php if(!empty($form_error)){ echo form_error('address'); } ?>
                                 </div>
                              </div>
                                <div class="row m-b-5">
                                   <div class="col-md-5">
                                 <strong>Address 2</strong>
                                 </div>
                                   <div class="col-md-7">
                                     <input type="text" name="address_second" id="address_second" class="address" maxlength="255" value="<?php echo $form_data['address_second']; ?>"/>
                                 </div>
                              </div>
                                <div class="row m-b-5">
                                   <div class="col-md-5">
                                 <strong>Address 3</strong>
                                 </div>
                                   <div class="col-md-7">
                                     <input type="text" name="address_third" id="address_third" class="address" maxlength="255" value="<?php echo $form_data['address_third']; ?>"/>
                                </div>
                              </div>




                        </div> <!-- 6 -->
                        <div class="col-sm-6">

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Country<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <select id="country_id" name="country_id" onchange="return get_state(this.value);">
                                        <option value="">Select Country</option>
                                        <?php
                                        if(!empty($country_list)){ 
                                        foreach($country_list as $country){ 
                                        ?>
                                        <option value="<?php echo $country->id; ?>" <?php if($form_data['country_id'] == $country->id){ echo 'selected="selected"'; } ?>><?php echo $country->country; ?></option>
                                        <?php
                                        }
                                        $selected = "";
                                        }
                                        ?> 
                                        </select> 
                                        <?php if(!empty($form_error)){ echo form_error('country_id'); } ?>
                                   </div>
                              </div> <!-- row -->


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>State<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <select id="state_id" name="state_id" onchange="return get_city(this.value);">
                                        <option value="">Select State</option> 
                                        <?php
                                        if(!empty($form_data['country_id'])){
                                        $state_list = state_list($form_data['country_id']); 
                                        if(!empty($state_list)){
                                        foreach($state_list as $state){  
                                        ?>   
                                        <option value="<?php echo $state->id; ?>" <?php if(!empty($form_data['state_id']) && $form_data['state_id'] == $state->id){ echo 'selected="selected"'; } ?>><?php echo $state->state; ?></option>
                                        <?php
                                        }
                                        }
                                        }
                                        ?>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('state_id'); } ?>
                                   </div>
                              </div> <!-- row -->


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                         <strong>City<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <select id="city_id" name="city_id">
                                        <option value="">Select City</option> 
                                        <?php
                                        if(!empty($form_data['state_id'])){
                                        $city_list = city_list($form_data['state_id']);
                                        if(!empty($city_list)){
                                        foreach($city_list as $city){
                                        ?>   
                                        <option value="<?php echo $city->id; ?>" <?php if(!empty($form_data['city_id']) && $form_data['city_id'] == $city->id){ echo 'selected="selected"'; } ?>><?php echo $city->city; ?></option>;
                                        <?php
                                        }
                                        }
                                        }
                                        ?>
                                        </select>
                                        <?php if(!empty($form_error)){ echo form_error('city_id'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                         
                         <?php
                         if(empty($form_data['data_id']) || $form_data['data_id']==0)
                         {
                         ?>


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Username<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" name="username" data-toggle="tooltip"  title="Allow only alphanumeric." class="tooltip-text username" id="username" value="<?php echo $form_data['username']; ?>" <?php if(!empty($form_data['data_id']) && $form_data['data_id']>0){ echo 'readonly'; } ?> />
                                       
                                        <?php if(!empty($form_error)){ echo form_error('username'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- Row -->
                         <?php
                         }
                         ?>
                         <?php
                         if(!empty($form_data['data_id']) && $form_data['data_id']>0){
                         ?>

                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Username<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-7">
                                        <input type="text" readonly="readonly" name="username" data-toggle="tooltip"  title="Allow only alphanumeric." class="tooltip-text" id="username" readonly="" value="<?php echo $form_data['username']; ?>"  />
                                      
                                        <?php if(!empty($form_error)){ echo form_error('username'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                         <?php
                         }
                         ?>
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Password<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <!-- <input type="password" name="password" id="password" value="<?php //echo $form_data['password']; ?>"> -->
                                       
                                        <div class='pwdwidgetdiv' id='thepwddiv'>
                                        </div>
                                        


                                         <div class="brn_cover"> 
                                             <div class="brn_1" id="brn_1">
                                                  <div class="brn_arrow"></div>
                                                  <div class="brn_txt">Password strength:</div>
                                                  <div id="mark_bar" class="brn_mark"></div>
                                                  <div class="brn_validation">
                                                       Password length should be 6-20 character only.
                                                  </div>
                                             </div>
                                        </div>  


                                        <script  type="text/javascript" >
                                        var pwdwidget = new PasswordWidget('thepwddiv','password');
                                        pwdwidget.MakePWDWidget();
                                        </script>
                                        <?php if(!empty($form_error)){ echo form_error('password'); } ?>
                                   </div>
                              </div> <!-- Row -->


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Confirm Password <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="password"  data-toggle="tooltip"  title="Password length should be 6-20 character only." class="tooltip-text" name="cpassword" id="cpassword" value="<?php //echo $form_data['cpassword']; ?>" />
                                       
                                        <?php if(!empty($form_error)){ echo form_error('cpassword'); } ?>
                                   </div>
                              </div> <!-- Row -->

                         <?php
                         if(empty($form_data['data_id']) || $form_data['data_id']==0)
                         {
                         ?>
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Branch Status<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="radio" name="branch_status" <?php if($form_data['branch_status']==1){ echo 'checked="checked"'; } ?> id="branch_status" value="1" /> Active 
                                        <input type="radio" name="branch_status" <?php if($form_data['branch_status']==0){ echo 'checked="checked"'; } ?> id="branch_status" value="0" /> Inactive 
                                   </div>
                              </div> <!-- Row -->


                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Login Status <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="radio" name="login_status" <?php if($form_data['login_status']==1){ echo 'checked="checked"'; } ?> id="login_status" value="1" /> Active 
                                        <input type="radio" name="login_status" <?php if($form_data['login_status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive  
                                   </div>
                              </div> <!-- Row -->

                         <?php
                         }
                         ?>
                          

                         
             
                    





                               <?php
                              if(!empty($form_data['data_id']) && $form_data['data_id']>0)
                              {
                              ?>
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Login Status <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="radio" name="login_status" <?php if($form_data['login_status']==1){ echo 'checked="checked"'; } ?> id="login_status" value="1" /> Active 
                                        <input type="radio" name="login_status" <?php if($form_data['login_status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive  
                                   </div>
                              </div> <!-- Row -->
                               <?php
                         }
                         ?>

                              <?php
                              if(!empty($form_data['data_id']) && $form_data['data_id']>0)
                              {
                              ?>
                              <div class="row m-b-5">
                                   <div class="col-md-5">
                                        <strong>Branch Status<span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-7">
                                        <input type="radio" name="branch_status" <?php if($form_data['branch_status']==1){ echo 'checked="checked"'; } ?> id="branch_status" value="1" /> Active 
                                        <input type="radio" name="branch_status" <?php if($form_data['branch_status']==0){ echo 'checked="checked"'; } ?> id="branch_status" value="0" /> Inactive 
                                   </div>
                              </div> <!-- Row -->

                         <?php
                         }
                         ?>
                        </div> <!-- 6 -->  
                    </div> <!-- MainRow -->
               <?php  
               }
               else
               {
               ?>
                    <div class="row">
                         <div class="col-md-12">
                              <div class="text-danger">You exceed your branch creation limits.</div>
                         </div>
                    </div>   <!-- row --> 
               <?php  
               }  
               ?> 

      </div>    <!--  modal-body --> 
             
             
        <div class="modal-footer">
          <?php
          if($total_branch>0)
          {
          ?>  
           <input type="submit"  class="btn-save" name="submit" value="Save" />
          <?php
          }
          ?> 
           <button type="button" class="btn-cancel"  data-number="2">Close</button>
        </div>
</form>      
<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
}); 
</script>
<script> 

$("button[data-number=1]").click(function(){
    $('#load_add_rate_modal_popup').modal('hide'); 
});

$("button[data-number=2]").click(function(){
    $('#load_add_branch_modal_popup').modal('hide'); 
});

$(document).on("click", function(e){
    if( !$(".password_id").is(e.target) ){ 
    //if your box isn't the target of click, hide it
        $(".brn_1").hide();
    }
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
$(document).ready(function(){
     var gendervalue = $('input[name=branch_type]:checked').val()
   
     if(gendervalue){
          get_branch_type(gendervalue);
     }
})
function checkAlphaNumeric(e) {
            
            if ((e.keyCode >= 48 && e.keyCode <= 57) ||
               (e.keyCode >= 65 && e.keyCode <= 90) ||
               (e.keyCode >= 97 && e.keyCode <= 122))
                return true;

            return false;
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
function get_city(state_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
    success: function(result)
    {
      $('#city_id').html(result); 
    } 
  }); 
}
function get_branch_type(id){
     if(id==0){
          document.getElementById("trainingtype").style.display="block";
     }else{
          document.getElementById("trainingtype").style.display="block";
          // $("#startdate").val("");
          // $("#enddate").val("");

     }
}
function delete_confirmation()
{ alert('dd');return false;
    
}
$(document).on('click', '.delete-event', function(e) {
alert('ddd');
});


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

function show_bar()
{  
     $(".brn_1").css("display", "block");
}

$("#startdate").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    endDate: "+0d",
}).on('changeDate', function (selected) {
    var startDate = new Date(selected.date.valueOf());
    $('#enddate').datepicker('setStartDate', startDate);
}).on('clearDate', function (selected) {
    $('#enddate').datepicker('setStartDate', null);
});

$("#enddate").datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
}).on('changeDate', function (selected) {
    var endDate = new Date(selected.date.valueOf());
    $('#startdate').datepicker('setEndDate', endDate);
}).on('clearDate', function (selected) {
    $('#startdate').datepicker('setEndDate', null);
});
       



 // $('.datepicker').datepicker({
 //    format: 'dd-mm-yyyy', 
 //    autoclose: true, 
 //    startDate: '-2m',
 //    endDate: '+2d'

 //  })
$("#branch_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  var ids = $('#branch_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Branch successfully Updated.';
   
  }
  else
  {
    var path = 'add/';
    var msg = 'Branch successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url(); ?>branch/"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        flash_session_msg(msg);
        $('#load_add_branch_modal_popup').modal('hide');
        reload_table();
      } 
      else
      {
        $("#load_add_branch_modal_popup").html(result);
      }
      $('.overlay-loader').hide();       
    }
  });
}); 
</script>  


</div><!-- /.modal-dialog -->
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
     <div id="load_add_rate_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

<script type="text/javascript">
     $(function() {
    //alert();
          
              var get_address  =  
             [
              <?php
              $address_list= get_branch_address();
              if(!empty($address_list))
              { 
                 foreach($address_list as $addres_li)
                  { 
                    echo '"'.$addres_li.'"'.',';  
                  }
              }   
              ?> 
            ];
            $( ".address" ).autocomplete({
               source: get_address
            });
         });
</script>