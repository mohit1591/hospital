<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="inventory_data" class="form-inline">
       <!--  <input type="hidden" name="data_id" id="type_id" value="<?php //echo $form_data['data_id']; ?>" /> -->
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">

              <div class="row">
                <div class="col-md-12">
                <input type="hidden" name="test_id" value="<?php echo $test_id; ?>"/>
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>"/>
               
                    <table class="table table-bordered table-striped medicine_allotment" id="medicine_list">
                         <thead class="bg-theme">
                              <tr>
                    <th align="center" width="40">S.NO.</th>
                                   <th>Item Name</th>
                                   <th>Quantity</th>
                                   <th>Unit</th>
                                  
                  
                              </tr>
                         </thead>
                         <tbody id="medicine">
                              <?php  echo $item_inventory_list; ?>

                                      <?php  if(!empty($form_error)){ ?>    
                                      <tr>
                                      <td colspan="8"><?php  echo form_error('item_id');  ?></td>
                                      </tr>
                                      <?php } ?>
                         </tbody>
                    </table>
                </div> <!-- 8 -->
              </div> <!-- Row -->
               
           
              </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" id="allot_to_branch" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-number="2" >Close</button>
            </div>
    </form>     

<script>   

 function check_stock_quantity(id){
   $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>test/check_stock_quantity/", 
      dataType: "json",
       data: 'item_id='+id,
      success: function(result)
      {
        //alert(result);
         if(result==1){
          $('#stock_error_'+id).html('Stock Not Available');
         }else{
           $('#stock_error_'+id).html('');
         }

      } 
    });
  }
$(document).ready(function(){
  //get_unit();
  var countRow = $('#medicine tr#nodata').length;
  
  if(countRow>0){
     $("#allot_to_branch").attr("disabled","disabled");
      $("#medicine").html('<tr id="validate"><td class="text-danger text-center" colspan="5">Please Select atleast one</td></tr>');
  }else{
     $("#allot_to_branch").removeAttr("disabled");
      // $("#medicine").html('<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>');
    
  }

})
 $(document).ready(function(){
          $("#msg").html('');
          $.post('<?php echo base_url('medicine_stock/get_allsub_branch_list/'); ?>',{},function(result){
               
               $("#child_branch").html(result);
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
function check_max_qty(obj)
{
  
   
   var qty = $(obj).data('qty');
   var id = $(obj).data('id');
   var updateQty = obj.value;
   var msgId ="#msg_"+id; 
    $(msgId.trim()).html('');
    
   if(updateQty>qty)
   {
  
        $(msgId.trim()).html("Quantity exceed to available limit");
        $("#"+obj.id).val(qty);
        $("#allot_to_branch").attr("disabled","disabled");
   }
   else if(updateQty=='')
   {
   
        $(msgId.trim()).html('qty is required');
        $("#allot_to_branch").attr("disabled","disabled");
   }
   else
   {
     
     $(msgId.trim()).html('');
     $("#allot_to_branch").removeAttr("disabled");
   }
}
$("button[data-number=2]").click(function(){
  <?php //$this->session->unset_userdata('alloted_medicine_ids'); ?>
    $('#load_add_inventory_modal_popup').modal('hide'); 
}); 

$("#inventory_data").on("submit", function(event) { 
     event.preventDefault(); 
   
     $('.overlay-loader').show();
  
     var path = 'insert_inventory_item_booking/';
     var msg = 'Item Added successfully.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>test/"+path,
          type: "post",
          data: $(this).serialize(),
          
          success: function(result) {
               if(result==1)
               {
                    flash_session_msg(msg);
                    $('#load_add_inventory_modal_popup').modal('hide');
                    reload_table();
               } 
               else
               {
                    $("#load_add_inventory_modal_popup").html(result);
               }
               $('.overlay-loader').hide();       
          }
     });
}); 

 function check()
 {
     
     if($('#getmedicineselectAll').is(':checked')) 
     {    
        
          $('.medicinechecklist').prop('checked', false);
     }
     else
     {

          $('.medicinechecklist').prop('checked', false);
     }

 }




</script>  


</div><!-- /.modal-dialog -->