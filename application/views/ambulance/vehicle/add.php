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
          <form  id="ambulance_form" class="form-inline"> 
              <input type="hidden" name="data_id" id="vehicle_id" value="<?php echo $form_data['data_id']; ?>">
              <input type="hidden" name="vehi_type" id="vehi_type" value="<?php echo $form_data['vehicle_type']; ?>">
			  <input type="hidden" name="vendor_id" value="<?php echo $form_data['vendor_id'];?>"/>
			  <input type="hidden" name="vendor_code" value="<?php echo $form_data['vendor_code'];?>"/>
			  <input type="hidden" name="add_vendor_type" value="<?php echo $form_data['add_vendor_type'];?>"/>
			  
         <div class="modal-body">
             
 
                        <div class="row mb-2">
                           <label class="col-md-4">Vehicle No. <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="vehicle_no" value="<?php echo $form_data['vehicle_no']; ?>">
                               <?php if(!empty($form_error)){ echo form_error('vehicle_no'); } ?>                              
                           </div>
                        </div>
                        <div class="row mb-2">
                           <label class="col-md-4">Chassis No.<span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="chassis_no" value="<?php echo $form_data['chassis_no']; ?>">    
                              <?php if(!empty($form_error)){ echo form_error('chassis_no'); } ?>
                           </div>
                        </div>
                        <div class="row mb-2">
                           <label class="col-md-4">Engine No.<span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="engine_no" value="<?php echo $form_data['engine_no']; ?>">  
                              <?php if(!empty($form_error)){ echo form_error('engine_no'); } ?>
                           </div>
                        </div>
                       
                        <div class="row mb-2">
                           <label class="col-md-4">Reg. Date</label>
                           <div class="col-md-8">
                              <input type="text" class="datepicker"  name="registration_date" value="<?php if(!empty($form_data['registration_date'])){ echo $form_data['registration_date'];} else { echo date('d-m-Y');} ?>">
                           </div>
                        </div> 
                        <div class="row mb-2">
                           <label class="col-md-4">Reg. Exp.</label>
                           <div class="col-md-8">
                              <input type="text" class="datepicker1" name="registration_exp_date" value="<?php if(!empty($form_data['registration_exp_date'])){ echo $form_data['registration_exp_date'];} else { echo date('d-m-Y');} ?>">
                           </div>
                        </div>
                         <div class="row mb-2">
                           <label class="col-md-4">Location  <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <select name="location" id="location">
                                <option value="">Select</option>
                                <?php
                              if(!empty($location_list))
                              {
                                foreach($location_list as $location)
                                {
                                  $selected_location = "";
                                  if($location->id==$form_data['location'])
                                  {
                                    $selected_location = "selected='selected'";
                                  }
                                  echo '<option value="'.$location->id.'" '.$selected_location.'>'.$location->location_name.'</option>';
                                }
                              }
                              ?> 
                               
                              </select>
                           <!--   <a class="btn-new" href="javascript:void(0)" onClick="location_modal()"><i class="fa fa-plus"></i> New</a> -->
                               <?php if(!empty($form_error)){ echo form_error('location'); } ?>
                           </div>
                        </div>


                        
                        
                        
                        <div class="row mb-2">
                           <div class="col-md-8 col-md-push-4">
                              <label class="btn btn-sm">
                                 <input type="radio" name="vehicle_type" onchange="owner_tp(this.value);" id="owned" <?php if($form_data['vehicle_type']==2){ echo "";}else { echo "checked";}?> value="1">
                                 <span>Self Owned</span>
                              </label>
                             
                              <label class="btn btn-sm">
                                 <input type="radio" name="vehicle_type" onchange="owner_tp(this.value);" id="lease" <?php if($form_data['vehicle_type']==2){?>checked="checked" <?php } ?>value="2">
                                 <span>Leased</span>
                              </label>
                           </div>
                        </div>	 

						
						<!--- Brajesh---> 
							
							  
							<div class="row mb-2 owend" style="display:<?php if($form_data['vehicle_type']==2){ echo 'block'; } else{ echo 'none';} ?>">
							 <div class="col-md-8 col-md-push-4">
								  <label class="btn btn-sm">
								   <input type="radio"  class="" name="charge_type" <?php if($form_data['charge_type']==1){ echo 'checked="checked"'; } else { echo 'checked="checked"'; } ?> id="charge_type" value="1" />
									 <span>Fix Charge</span>
								  </label>
								  <label class="btn btn-sm">
								   <input type="radio"  class="" name="charge_type" <?php if($form_data['charge_type']==2){ echo 'checked="checked"'; } ?> id="charge_type" value="2" />
									 <span>Per KM Charge</span>
								  </label>
                              </div>
							 </div>
                             <!--- Brajesh---> 
							 
							 <div class="row mb-2 owend" style="display:<?php if($form_data['vehicle_type']==2){ echo 'block'; } else{ echo 'none';} ?>">
                                 <label class="col-md-4">Vendor Charge Amount<span class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                    <input type="text" name="charge" value="<?php echo $form_data['charge']; ?>">
									 <?php if(!empty($form_error)){ echo form_error('charge'); } ?>
                                 </div>
                              </div>
					
						
						
                         <div class="panel panel-default" id="lease_form">
                           <div class="panel-body"> 
						   
						   	<div class="row mb-2">
							 <div class="col-md-8 col-md-push-4">
							 
							<?php if($form_data['add_vendor_type']==2){  ?>
							
								 <label class="btn btn-sm">
								   <input type="radio" name="add_vendor_type" <?php if($form_data['add_vendor_type']==1){ echo ''; }?> id="newvendor" onclick="clearFields();" value="1"/>
									 <span>New Vendor</span>
								  </label>				     
                                  <label class="btn btn-sm">
								   <input type="radio" name="add_vendor_type" <?php if($form_data['add_vendor_type']==2){ echo 'checked="checked"'; } ?> id="regvendor" value="2" />
									 <span>Registered Vendor</span>
								  </label>
								  
							<?php } else { ?>	
							
								  <label class="btn btn-sm">
								   <input type="radio" name="add_vendor_type" <?php if($form_data['add_vendor_type']==2){ echo 'checked="checked"'; } else {  echo 'checked="checked"'; }?> id="newvendor" value="2" onclick="clearFields();"/>
									 <span>New Vendor</span>
								  </label>
								   <label class="btn btn-sm">
								   <input type="radio" name="add_vendor_type" <?php if($form_data['add_vendor_type']==1){ echo ''; } ?> id="regvendor" value="2" />
									 <span>Registered Vendor</span>
								  </label>
                              <?php } ?>	 
							  
                              </div>
							  
	
								<div class="col-md-4" id="regvendor_select">
								  <select class="w-150px m_select_btn" name="vendor_id" id="vendor_id" onchange="select_vendor_data(this.value)">
									  <option value="">Select Vendor</option>
									  <?php foreach($vendor_list as $vendorl){?>
									   <option value="<?php echo $vendorl->id;?>" <?php if(isset($form_data['vendor_id']) && $form_data['vendor_id']==$vendorl->id){ echo 'selected';}?>><?php echo $vendorl->name;?></option>
									  <?php }?>
								  </select>
								 <div class="">
								<?php if(!empty($form_error)){ echo form_error('vendor_id'); } ?>
								</div>
								</div>
     
							 </div>
						
                              <div class="row mb-2">
                                 <label class="col-md-4">Vendor Name<span class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                    <input type="text" name="name" id="name" value="<?php echo $form_data['name']; ?>" onkeydown="return alphaOnly(event);">
                                       <?php if(!empty($form_error)){ echo form_error('name'); } ?>

                                 </div>
                              </div>
							 
                              <div class="row mb-2">
                                 <label class="col-md-4">Vendor Mobile<span class="text-danger">*</span></label>
                                 <div class="col-md-8">
                                    <input type="text" name="mobile" id="mobile" value="<?php echo $form_data['mobile']; ?>" onkeypress="return numericvalue(event);">
                                       <?php if(!empty($form_error)){ echo form_error('mobile'); } ?>
                                 </div>
                              </div>
							  
                              <div class="row mb-2">
                                 <label class="col-md-4">Vendor Email</label>
                                 <div class="col-md-8">
                                    <input type="text" name="email" id="email" value="<?php echo $form_data['email']; ?>" >
                                 </div>
                              </div>
                           
							   <div class="row mb-2">
                                 <label class="col-md-4">Vehicle Address1</label>
                                 <div class="col-md-8">
                                    <input type="text" name="address" id="address" value="<?php echo $form_data['address']; ?>">
                                 </div>
                              </div>
							 
							   <div class="row mb-2">
                                 <label class="col-md-4">Vehicle Address2</label>
                                 <div class="col-md-8">
                                    <input type="text" name="address2" id="address2" value="<?php echo $form_data['address2']; ?>">
                                 </div>
                              </div>
							 
							   <div class="row mb-2">
                                 <label class="col-md-4">Vehicle Address3</label>
                                 <div class="col-md-8">
                                    <input type="text" name="address3" id="address3" value="<?php echo $form_data['address3']; ?>">
                                 </div>
                              </div>
						
                              <div class="row mb-2">
                                 <label class="col-md-4">GST No.</label>
                                 <div class="col-md-8">
                                    <input type="text" name="vendor_gst" id="vendor_gst" value="<?php echo $form_data['vendor_gst']; ?>">
                                 </div>
                              </div>
						 
                           </div>
                        </div>
                      

              
         </div>
         <div class="modal-footer">
            <input type="submit" class="btn-save" value="Save">
            <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
         </div>
           </form> 
		   
<script type="text/javascript">
function clearFields() {
    document.getElementById("name").value=""
    document.getElementById("email").value=""
	document.getElementById("mobile").value=""
    document.getElementById("address").value=""    
	document.getElementById("address2").value=""
    document.getElementById("address3").value=""
	document.getElementById("vendor_gst").value=""
}
</script>		   
 <script>
 function select_vendor_data(vendor_id)
{
   	
    var vendor_id = $('#vendor_id').val();	
    var name = $('#name').val();	
    var mobile = $('#mobile').val();	
    var email = $('#email').val();	
    var address = $('#address').val();	
    var address2 = $('#address2').val();	
    var address3 = $('#address3').val();
    var vendor_gst = $('#vendor_gst').val();	
    $.ajax({	
            type: "POST",	
            url: "<?php echo base_url('ambulance/vehicle/get_vandor_data/'); ?>"+vendor_id, 	
            dataType: "json",	
            data: 'vendor_id='+vendor_id+'&name='+name+'&mobile='+mobile+'&email='+email+'&address='+address+'&address2='+address2+'&address3='+address3+'&vendor_gst='+vendor_gst,	
            success: function(result)	
            {	
              $('#vendor_id').html(result.vendor_id);	
              $('#name').val(result.name); 	
              $('#mobile').val(result.mobile); 	
              $('#email').val(result.email);	
              $('#address').val(result.address);  	
              $('#address2').val(result.address2); 	
              $('#address3').val(result.address3); 
              $('#vendor_gst').val(result.vendor_gst); 			  
            } 	
          });	
}



 
 
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
   // startDate : new Date(),
    autoclose: true,
  });
$('.datepicker1').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true,
  });

   $(document).ready(function() {
   $('input[type="radio"]').click(function() {
       if($(this).attr('id') == 'lease') {
            $('#lease_form').show();           
       }
	   
	        else if ($(this).attr('id') == 'owned') {
            $('#lease_form').hide();   
       }
	  
	   
   });
   var aa=$('#vehi_type').val();
  
 if(aa == 2) {
            $('#lease_form').show();           
       }
       else{
          $('#lease_form').hide(); 
       }
          
      
});

 $(document).ready(function() {
   $('input[type="radio"]').click(function() {
       if($(this).attr('id') == 'regvendor') {
            $('#regvendor_select').show();           
       }
	   
	        else if ($(this).attr('id') == 'newvendor') {
            $('#regvendor_select').hide();   
       }
	  
	   
   });
   var vnd=$('#vehi_type').val();
  
 if(vnd == 2) {
            $('#regvendor_select').show();           
       }
       else{
          $('#regvendor_select').hide(); 
       }
   
});

 
   $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
 });

   $(document).ready(function(){
  $('#load_add_type_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});


 
 
  

  $("#ambulance_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();

   var id=$('#vehicle_id').val();
  var url='';
   if(id !=''){    
    var msg='Ambulance Vehicle updated successfully';
    url='edit/'+id;
  }
   else{
     var msg='Ambulance Vehicle added successfully';
     url='add';
  }
   
  $.ajax({
    url: "<?php echo base_url('ambulance/vehicle/'); ?>"+url,
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

  function type_modal()
  {
      var $modal = $('#load_add_type_modal_popup');
      $modal.load('<?php echo base_url().'ambulance/vehicle/add_type/' ?>',
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

function owner_tp(val)
{
    if(val==2)
    {
        $('.owend').show();
    }
    else{
        $('.owend').hide();
    }
    
}


   </script>

  </div>

<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
  <!-- /.modal-content -->     
</div><!-- /.modal-dialog -->