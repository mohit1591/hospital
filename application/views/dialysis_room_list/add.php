<div class="modal-dialog ">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="dialysis_room_list" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="2" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
     <div class="modal-body">   
          <div class="row">
               <div class="col-md-12 m-b1">
                    <div class="row">
                         <div class="col-md-5">
                              <label>Room Type  <span class="star">*</span></label>
                         </div>
                         <div class="col-md-7">
                              <select name="room_category_id" class="w-100px" id="room_category_id" class="pat-select1" >
                                   <option value="">Select Room Type</option>
                                   <?php
                                        if(!empty($room_type_list))
                                        {
                                             foreach($room_type_list as $room_type)
                                             {
                                                  $selected_room_type = "";
                                                  if($room_type->id==$form_data['room_category_id'])
                                                  {
                                                       $selected_room_type = 'selected="selected"';
                                                  }
                                                  echo '<option value="'.$room_type->id.'" '.$selected_room_type.'>'.$room_type->room_category.'</option>';
                                             }
                                        }
                                   ?> 
                              </select>
                              <a href="javascript:void(0)" onclick="room_type_model()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                              <?php if(!empty($form_error)){ echo form_error('room_category_id'); } ?>
                         </div>
                    </div> <!-- innerrow -->
               </div> <!-- 12 -->
          </div> <!-- row -->  
         
        
        <div class="row m-b-3">
            <div class="col-md-5">
                <label>Room  No.  <span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <input type="text" class=" inputFocus" name="room_no" id="room_no" value="<?php echo $form_data['room_no']; ?>" />
                    <?php if(!empty($form_error)){ echo form_error('room_no'); } ?>

            
       <!--  <label>Panel Charges <span class="star">*</span></label> -->
      </div>
        </div>
    
          <?php if(!empty($room_charge_type_list)){
          // echo "<pre>";print_r($room_charge_type_list);die;
                  $room_charge_type_list_count = count($room_charge_type_list);
                  for($i=0;$i<$room_charge_type_list_count;$i++){

                       ?>
                         <div class="row m-b-5">
                              <div class="col-md-5">
                                   <label><?php echo ucfirst($room_charge_type_list[$i]['charge_type']); ?> <span class="star">*</span></label>
                              </div>

                              <div class="col-md-7">
                             <?php  $charges='';
                             $code='';
                            // print_r($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]);
                             if(isset($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]))
                             {
                              if(isset($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'])){
                                 $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                              }
                             
                              if(isset($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['code'])){
                                $code =$form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['code'];
                              }
                              
                              //$charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']['charge']))];

                              }?>
                              <b> 10</b> <input class="w-60px" type="text" name="charges[<?php echo $room_charge_type_list[$i]['id'];?>][]" placeholder="Code" value="<?php echo $code; ?>" maxlength="4">
                                <input type="text" placeholder="Price" class="price_float w-133px" name="charges[<?php echo $room_charge_type_list[$i]['id'];?>][]" id="<?php echo strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type'])); ?>" value="<?php echo $charges; ?>" />
                                <?php if(!empty($form_error)){ echo form_error('charges['.$room_charge_type_list[$i]['id'].']'); }

                                 ?>

            
                             </div>
                         </div> <!-- innerrow -->
                    <?php } 
               } 
          ?>
          
       
          <div class="row m-b-5">
               <div class="col-md-5">
                    <b>No. of Beds <span class="star">*</span></b>
               </div>
               <div class="col-md-7">
                    <input type="text" class="numeric" name="total_bad" id="total_bad" value="<?php echo $form_data['total_bad']; ?>" />
                    <?php if(!empty($form_error)){ echo form_error('total_bad'); } ?>
               </div>
          </div> <!-- innerrow -->
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="2">Close</button>
      </div>
</form>     

<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#dialysis_room_list").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Dialysis Room List successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Dialysis Room List successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dialysis_room_list/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_dialysis_room_list_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_simulation();
        reload_table();
      } 
      else
      {
        $("#load_add_dialysis_room_list_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
function get_charges(room_category_id)
{
     if(room_category_id!='')
     {
          $.post('<?php echo base_url().'dialysis_room_type/get_room_type_charges/' ?>',{'room_category_id':room_category_id},function(result){
               if(result!='')
               {
                    var data = JSON.parse(result);
                    $("#bed_charges").val(data.bed_charges);
                    $("#nursing_charges").val(data.nursing_charges);
                    $("#rmo_charges").val(data.rmo_charges);
                    $("#panel_bed_charges").val(data.panel_bed_charges);
                    $("#panel_nursing_charges").val(data.panel_nursing_charges);
                    $("#panel_rmo_charges").val(data.panel_rmo_charges);
               }
               else
               {
                    $("#bed_charges").val('');
                    $("#nursing_charges").val('');
                    $("#rmo_charges").val('');
                    $("#panel_bed_charges").val('');
                    $("#panel_nursing_charges").val('');
                    $("#panel_rmo_charges").val('');
               }


          });
     }
}
$("button[data-number=1]").click(function(){
  $('#load_add_dialysis_room_type_modal_popup').modal('hide');
});

$("button[data-number=2]").click(function(){
  //alert();
  $('#load_add_dialysis_room_list_modal_popup').modal('hide');
});
 function room_type_model()
  {
      var $modal = $('#load_add_dialysis_room_type_modal_popup');
      $modal.load('<?php echo base_url().'dialysis_room_type/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }

function get_room_type()
{
 $.ajax({url: "<?php echo base_url(); ?>dialysis_room_list/get_room_type_list/", 
  success: function(result)
  {
    $('#room_category_id').html(result); 
  } 
});
}
   function panel_type_model()
  {
      var $modal = $('#load_add_ipd_panel_type_modal_popup');
      $modal.load('<?php echo base_url().'dialysis_panel_type/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }
   function panel_company_model()
  {
      var $modal = $('#load_add_ipd_panel_company_modal_popup');
      $modal.load('<?php echo base_url().'dialysis_panel_company/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }
function get_simulation()
{
   $.ajax({url: "<?php echo base_url(); ?>simulation/simulation_dropdown/", 
    success: function(result)
    {
      $('#simulation_id').html(result); 
    } 
  });
}

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 

</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_dialysis_room_type_modal_popup" class="modal fade modal-top45" role="dialog" data-backdrop="static" data-keyboard="false"></div>