<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(1);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="advance_search_form" class="form-inline" enctype="multipart/form-data">
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
                         <strong>From Date</strong>
                      </div>
                      <div class="col-xs-8">
                             <input type="text" class="start_datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>" id="" >
                      </div>
                    </div> <!-- row -->


                   <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Doctor Name <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" name="doctor_name" id="doctor_name" value="<?php echo $form_data['doctor_name'] ?>" class="txt_firstCapital alpha_space_dot_space inputFocus" autofocus> 
                       
                      </div>
                    </div> <!-- row -->

                    

                      <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Specialization <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="specialization_id" id="specialization_id" class="m10 specilization_id">
                          <option value=""> Select Specialization </option>
                          <?php
                          if(!empty($specialization_list))
                          {
                            foreach($specialization_list as $specialization)
                            {
                             ?>
                               <option value="<?php echo $specialization->id; ?>" <?php if($specialization->id==$form_data['specilization_id']){ echo 'selected="selected"'; } ?>><?php echo $specialization->specialization; ?></option>
                             <?php  
                            }
                          }
                          ?>
                        </select>
                       
                      </div>
                    </div> <!-- row -->

                  

                     <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Mobile No.
                      
                        </strong>
                      </div>
                      <div class="col-xs-8">
                       <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                        <input type="text" maxlength="10" data-toggle="tooltip"  title="Allow only numeric." class="numeric number" name="mobile_no" id="mobile_no"  value="<?php echo $form_data['mobile_no'] ?>">
                      
                      
                      </div>
                    </div> <!-- row -->

                 


                   <!--  <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Marketing Person </strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="marketing_person_id" id="marketing_person_id">
                          <option value=""> Select Person </option>
                          <?php
                            if(!empty($person_list))
                            {
                              foreach($person_list as $person)
                              {
                                ?>
                                <option value="<?php echo $person->id; ?>" <?php if($person->id==$form_data['marketing_person_id']){ echo 'selected="selected"'; } ?>><?php echo $person->name; ?></option>
                                <?php
                              }
                            }
                           ?> 
                        </select>
                      </div>
                    </div>  --><!-- row -->
                    <?php 
                     if(in_array('224',$users_data['permission']['action'])) 
                     {
                    ?>
                         <div class="row m-b-5">
                              <div class="col-xs-4">
                                   <strong>Sharing Pattern</strong>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Sharing pattern are of two type <br> Commision: Commission is a sum of money that is paid to an doctor upon completion of a task<br> Transaction:Basically this is used in Pathalogy it is type of doctor which paid before completion of any task. </span></a></sup>
                              </div>
                              <div class="col-xs-8">
                      
                                   <select name="doctor_pay_type" id="doctor_pay_type" onchange="get_comission(this.value);">
                                        <option value="1" <?php if(1==$form_data['doctor_pay_type']){ echo 'selected="selected"'; } ?>>Commission</option>
                                        <option value="2" <?php if(2==$form_data['doctor_pay_type']){ echo 'selected="selected"'; } ?>>Transaction</option>
                                   </select>
                              </div>
                         </div> <!-- row -->
                         
                         


                          <div class="row m-b-5">
                     <?php
                        if($form_data['doctor_pay_type']==2){ ?>
                     <div class="col-xs-4">
                        <strong id="share_lable">Rate list</strong>
                     </div>
                     <div class="col-xs-8 " id="share_input">
                        <select name="rate_plan_id" id="rate_id" class="m1">
                           <option value="">Select Rate Plan</option>
                              <?php if(!empty($rate_list)){
                                 foreach($rate_list as $rate){
                                    $rate_select = "";
                                       if($rate->id == $form_data['rate_plan_id'])
                                 {
                                       $rate_select = 'selected="selected"';
                                    }
                                       echo '<option value="'.$rate->id.'" '.$rate_select.'>'.$rate->title.'</option>';
                                    }
                                 }
                              ?>
                        </select>
                     </div>
                     <?php }else{ ?>
                  </div> <!-- row -->



                  <div class="row m-b-5">
                     <div class="col-xs-4" id="share_lable">
                        <strong>Share Details</strong>
                     </div>
                     <div class="col-xs-8" id="share_input">
                        <a href="javascript:void(0)" class="btn-commission" onclick="comission();"><i class="fa fa-cog"></i> Commission</a> 
                     </div>
                     <?php } ?>  
                  </div> <!-- row -->
                    

                    <?php }
                    else
                    {
                    ?>
                      <input name="doctor_pay_type" type="hidden" id="doctor_pay_type" value="0"/>
                    <?php  
                    } 
                    ?> 
                   
                      <div class="row m-b-5 m-t-5">
                      <div class="col-xs-4">
                         <strong>Doctor Type </strong>
                      </div>
                      <div class="col-xs-8">
                        <select name="doctor_type" id="doctor_type">
                            <option value="0" <?php if($form_data['doctor_type']==0){ echo 'selected="selected"'; } ?>>Referral</option>
                            <option value="1" <?php if($form_data['doctor_type']==1){ echo 'selected="selected"'; } ?>>Attended</option>
                            <option value="2" <?php if($form_data['doctor_type']==2){ echo 'selected="selected"'; } ?>>Both</option>
                        </select>
                      
                      </div>
                    </div> <!-- row -->

                    
                    <div class="row m-b-5">
                     <div class="col-xs-4">
                        <strong>Marketing Person </strong>
                     </div>
                     <div class="col-xs-8">
                        <select name="marketing_person_id" id="marketing_person_id">
                           <option value=""> Select Person </option>
                           <?php
                              if(!empty($person_list))
                              {
                              foreach($person_list as $person)
                              {
                                ?>
                                <option value="<?php echo $person->id; ?>" <?php if($person->id==$form_data['marketing_person_id']){ echo 'selected="selected"'; } ?>><?php echo $person->name; ?></option>
                                <?php
                              }
                            }
                           ?> 
                        </select>
                     </div>
                  </div> <!-- row -->

              

                    
<?php 
 $users_data = $this->session->userdata('auth_users');
 if(in_array('588',$users_data['permission']['action'])) 
 { ?>
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Consulting Charge</strong>
<sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a price money of a doctor or consultant when doctor came with any reference like consultant this charge gave to a doctor or consultant by which doctor come. </span></a></sup>
                      </div>
                      <div class="col-xs-8">
                        <input type="text"  name="consultant_charge" class="price_float" id="consultant_charge" value="<?php echo $form_data['consultant_charge']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('consultant_charge'); } ?>
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong><?php echo $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');  ?></strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text"  name="emergency_charge" class="price_float" id="emergency_charge" value="<?php echo $form_data['emergency_charge']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('emergency_charge'); } ?>
                      </div>
                    </div> <!-- row -->

      <?php } else { ?>
      <input type="hidden"  name="consultant_charge" value="0.00">
      <input type="hidden"  name="emergency_charge" value="0.00">

      <?php } ?>
                    
                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>DOB</strong>
                      </div>
                      <div class="col-xs-5">
                              <input type="text" class="datepicker" readonly="" name="dob" id="dob" value="<?php echo $form_data['dob']; ?>" /> 
                            </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Marriage Anniversary</strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" class="datepicker" readonly="" name="anniversary" id="anniversary" value="<?php echo $form_data['anniversary']; ?>" /> 
                      </div>
                    </div> <!-- row -->
                
 

                </div> <!-- 6 // Left -->





                <!-- Right portion from here -->
                <div class="col-xs-6">
                    <div class="row m-b-5">
                    <div class="col-xs-4">
                      <strong>To Date</strong>
                    </div>
                    <div class="col-xs-8">
                      <input type="text" name="end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>" id="">

                    </div>
                  </div> <!-- row -->

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
                        <strong>PIN Code</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="pincode" data-toggle="tooltip"  title="Pin code should be max digit." class="numeric"  maxlength="6" id="pincode"  value="<?php echo $form_data['pincode']; ?>">
                       </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Email</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="email"  data-toggle="tooltip"  title="Email should be like abc@example.com." class="email_address" id="email" value="<?php echo $form_data['email']; ?>">
                       </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Alternate Mobile</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="alt_mobile_no" maxlength="10"  data-toggle="tooltip"  title="Allow only numeric." class="numeric" id="alt_mobile_no" value="<?php echo $form_data['alt_mobile_no']; ?>">
                      </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Landline No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="landline_no" data-toggle="tooltip"  title="Landline should be 13 digits." class="landline" maxlength="13"  id="landline_no" value="<?php echo $form_data['landline_no']; ?>">
                        </div>
                    </div> <!-- row -->

                    <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>PAN No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" maxlength="10" class="alpha_numeric" name="pan_no" id="pan_no" value="<?php echo $form_data['pan_no']; ?>">
                      </div>
                    </div> <!-- row -->

                      <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Registration No.</strong>
                      </div>
                      <div class="col-xs-8">
                        <input type="text" name="doc_reg_no" class="numeric" id="doc_reg_no" value="<?php echo $form_data['doc_reg_no']; ?>">
                       </div>
                    </div> <!-- row -->

                    <?php 
                    /*
                    if(isset($users_data['emp_id']) && $users_data['emp_id']=='0') 
                    { ?>
                        <div class="row m-b-5">
                      <div class="col-xs-4">
                                <label>User</label>
                                </div>
                      <div class="col-xs-8">
                                <select name="employee"  id="employee">
                                      <option value="">Select User</option>
                                      <option value="<?php echo $users_data['id']; ?>">Self</option>
                                    <?php 
                                     
                                      if(!empty($employee_user_list))
                                      {
                                        foreach($employee_user_list as $employee)
                                        {
                                          echo '<option value="'.$employee->id.'">'.$employee->name.'</option>';
                                        }
                                      }
                                    ?> 
                                </select>
                       </div>
                    </div> <!-- row -->

                        <?php } 
                        else 
                        {?>
                        <input type="hidden" name="employee" value="<?php echo $users_data['parent_id'];?>" />

                  <?php }*/ ?>

          
          


                  
                </div> <!-- 6 // Right -->
              </div> <!-- ROW -->



              <!-- // Till Here -->




            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <input value="Reset" onclick="clear_form_elements(this.form)" type="button" class="btn-reset">
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>

    </form>     
<script>
$("#advance_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('doctors/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

function reset_search()
{
  $.ajax({url: "<?php echo base_url(); ?>doctors/reset_search/", 
    success: function(result)
    {
      //$("#advance_search_form").reset();
      document.getElementById('advance_search_form').reset();
      reload_table();
    } 
  }); 
}
</script>
<script>   
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


function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result); 
    } 
  });
}
function get_doctors()
{
   $.ajax({url: "<?php echo base_url(); ?>doctors/doctors_dropdown/", 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
  });
}

function get_comission(id)
{
  $.ajax({
    dataType: "json",
    url: "<?php echo base_url(); ?>doctors/get_comission/"+id, 
    success: function(result)
    {

      $('#share_lable').html('<strong>'+result.lable+'</strong>'); 
      $('#share_input').html(result.inputs); 
    } 
  });
}

function comission(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'doctors/add_comission/' ?>',
  {
    //'id1': '1',
    'id': '<?php echo $form_data['data_id']; ?>'
    },
  function(){
  $modal.modal('show');
  });
} 
function get_doctors_to_specialization()
    {
       var specilization_id = '<?php echo $this->session->userdata('specilization_id');?>';
      
       if(specilization_id!="" && specilization_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>doctors/type_to_doctors/"+specilization_id, 
              success: function(result)
              {
                $('#refered_id').html(result); 
              } 
            });
       } 
    }

    function get_attended_doctor_to_specialization()
    {
       var specilization_id = '<?php echo $this->session->userdata('specilization_id');?>';
      
       if(specilization_id!="" && specilization_id>0)
       {
          $.ajax({url: "<?php echo base_url(); ?>doctors/type_attended_doctors/"+specilization_id, 
              success: function(result)
              {
                $('#attended_doctor').html(result); 
              } 
            });
       } 
    }

$(document).ready(function() {
  $('#load_add_comission_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

$(document).ready(function() {
  $('#load_add_specialization_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
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

$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
  });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->   
</div><!-- /.modal-dialog -->  
<div id="load_add_specialization_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_comission_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_rate_modal_popup" class="modal fade modal-60" role="dialog" data-backdrop="static" data-keyboard="false"></div>

 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
  $('.datepicker_day').datetimepicker({
      format: 'LT'
  });
</script>