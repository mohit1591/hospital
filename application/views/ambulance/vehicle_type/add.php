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
              <input type="hidden" name="vehi_type" id="vehi_type" value="<?php echo $form_data['vehicle_type']; ?>">
			         <input type="hidden" name="vendor_id" value="<?php echo $form_data['vendor_id'];?>"/>
			         <input type="hidden" name="vendor_code" value="<?php echo $form_data['vendor_code'];?>"/>
			         <input type="hidden" name="add_vendor_type" value="<?php echo $form_data['add_vendor_type'];?>"/>

                <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id'];?>"/>
			  
         <div class="modal-body">
                        <div class="row mb-2">
                           <label class="col-md-4">Vehicle Type <span class="text-danger">*</span></label>
                           <div class="col-md-8">
                              <input type="text" name="type" placeholder="Vehicle Type" value="<?php echo $form_data['type']; ?>">
                               <?php if(!empty($form_error)){ echo form_error('type'); } ?>                              
                           </div>
                        </div>
                         <div class="row mb-2">
                           <label class="col-md-4">Local Min distance(in Km)</label>
                           <div class="col-md-8">
                              <input type="text" name="local_min_distance"  value="<?php echo $form_data['local_min_distance']; ?>" placeholder="Local Min distance" onkeypress="return isNumberKey(event);">   
                           </div>
                        </div>
                        <div class="row mb-2">
                           <label class="col-md-4">Local Amount(min dist)</label>
                           <div class="col-md-8">
                              <input type="text" name="local_min_amount" value="<?php echo $form_data['local_min_amount']; ?>" placeholder="Local Min amount" onkeypress="return isNumberKey(event);">
                           </div>
                        </div>
                         <div class="row mb-2">
                           <label class="col-md-4">Local Per Km charge</label>
                           <div class="col-md-8">
                              <input type="text" name="local_per_km_charge" value="<?php echo $form_data['local_per_km_charge']; ?>" placeholder="Local Per Km charge" onkeypress="return isNumberKey(event);">
                           </div>
                        </div>
                         <div class="row mb-2">
                           <label class="col-md-4">Outstation Min distance(in Km)</label>
                           <div class="col-md-8">
                              <input type="text" name="outstation_min_distance"  value="<?php echo $form_data['outstation_min_distance']; ?>" placeholder="Outstation Min distance" onkeypress="return isNumberKey(event);">   
                           </div>
                        </div>
                        <div class="row mb-2">
                           <label class="col-md-4">Outstation Amount(min dist)</label>
                           <div class="col-md-8">
                              <input type="text" name="outstation_min_amount" value="<?php echo $form_data['outstation_min_amount']; ?>" placeholder="Outstation Min amount" onkeypress="return isNumberKey(event);">
                           </div>
                        </div>
                         <div class="row mb-2">
                           <label class="col-md-4">Outstation Per Km charge</label>
                           <div class="col-md-8">
                              <input type="text" name="outstation_per_km_charge" value="<?php echo $form_data['outstation_per_km_charge']; ?>" placeholder="Outstation Per Km charge" onkeypress="return isNumberKey(event);">
                           </div>
                        </div>
                        
                     
							 
					
						
						
                    

              
         </div>
         <div class="modal-footer">
            <input type="submit" class="btn-save" value="Save">
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
   // startDate : new Date(),
    autoclose: true,
  });
$('.datepicker1').datepicker({
    format: 'dd-mm-yyyy',
    startDate : new Date(),
    autoclose: true,
  });
 
   $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
 });

  $("#ambulance_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var id=$('#data_id').val();
  var url='';
  if(id !=''){    
    var msg='Vehicle type updated successfully';
    url='edit/'+id;
  }
  else{
     var msg='Vehicle type added successfully';
     url='add';
  }
   
  $.ajax({
    url: "<?php echo base_url('ambulance/vehicle_type/'); ?>"+url,
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


  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}




   </script>

  </div>

<div id="load_add_type_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
  <!-- /.modal-content -->     
</div><!-- /.modal-dialog -->