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
                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>"/>
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>"/>
               
                   <table id="inventory_box" class="table table-bordered table-striped add_master" cellpadding="0" cellspacing="0">
                <thead class="bg-theme">
                   <tr>
                       <th>S.No.</th>
                       <th>Item</th>
                       <th>Quantity</th>
                       <th>Unit</th>
                      <th>Action</th>
                   </tr> 
                </thead>
                <tbody>   
                   <?php


                    if(!empty($form_data['item_list']))
                    {
                        $r = 0;
                        $i=1;
                        foreach($form_data['item_list'] as $item)
                        {

                           $get_unit_according_item= get_units_by_item($item['item_id']);
                           $get_unit_by_unit_id= get_units_by_id($item['unit_id']);

                        ?>
                         <tr class="inventory_row" id="row_<?php echo $r; ?>">
                           <td><?php echo $i; ?></td>
                            
                             <td>
                                <input type="text" name="item_name[<?php echo $r; ?>]" value="<?php echo $item['item']; ?>" class="" id="item_name_<?php echo $r;?>" /> 
                                <input type="hidden" name="item_id[<?php echo $r; ?>]" value="<?php echo $item['item_id']; ?>" class="" id="item_id_<?php echo $r;?>" /> 
                                
                             </td> 
                             <td>
                                 <input type="text" name="quantity[<?php echo $r; ?>]" value="<?php echo $item['inventory_qty']; ?>" id="quantity_<?php echo $r;?>" />
                             </td> 
                            <td>
                                 <select name="unit_value[<?php echo $r; ?>]" id="unit_dropdown_<?php echo $r; ?>" >
                                 
                                  <option value="">Select Unit</option>
                                  <?php  if(!empty($get_unit_according_item[0])) { foreach($get_unit_according_item[0] as $get_unit)
                                  {

                                      if(!empty($get_unit))
                                      {
                                  ?>
                                 <option value="<?php echo $get_unit['id'];?>" <?php if($get_unit['id']==$get_unit_by_unit_id[0]->id){ echo 'selected';} ?>><?php echo $get_unit['first_name'];?></option>
                                 <?php } } }?>

                                 </select>
                             </td> 
                             <td>
                                 <?php
                                  if($r==0)
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory()"><i class="fa fa-plus"></i> Add</a>';
                                    if($form_data['data_id']>0)
                                    {
                                       echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('.$r.')"> Remove </a>'; 
                                    }
                                  }
                                  else
                                  {
                                    echo '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('.$r.')"> Remove </a>';
                                  }
                                 ?>
                             </td>
                         </tr> 
                        <?php    
                        $r++;$i++;
                        } 
                    }
                   ?>
                   </tbody>
               </table>
                </div> <!-- 8 -->
              </div> <!-- Row -->
               
           
              </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" id="allot_to_item" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-number="2" >Close</button>
            </div>
    </form>     

<script>   

 function check_stock_quantity(id){
   $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>dialysis_booking/check_stock_quantity/", 
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
        $("#allot_to_item").attr("disabled","disabled");
   }
   else if(updateQty=='')
   {
   
        $(msgId.trim()).html('qty is required');
        $("#allot_to_item").attr("disabled","disabled");
   }
   else
   {
     
     $(msgId.trim()).html('');
     $("#allot_to_item").removeAttr("disabled");
   }
}
$("button[data-number=2]").click(function(){
  <?php //$this->session->unset_userdata('alloted_medicine_ids'); ?>
    $('#load_add_inventory_modal_popup').modal('hide'); 
}); 

$("#inventory_data").on("submit", function(event) { 
     event.preventDefault(); 
   
     $('.overlay-loader').show();
  
     var path = 'add_inventory_item_to_booking/';
     var msg = 'Item Added successfully.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>dialysis_booking/"+path,
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





function add_inventory()
{ 
    
    if(typeof int_i === 'undefined')
    {
        int_i=0;
    }
    
   if(int_i==0)
    {
      var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory('+int_i+')"><i class="fa fa-plus"></i> Add</a>';
    }
    else
    {
      var rowCount = $('#inventory_box >tbody >tr').length; 
      if(rowCount==0)
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="add_inventory('+int_i+')"><i class="fa fa-plus"></i> Add</a>';
      } 
      else
      {
         var action_btn = '<a href="javascript:void(0);" class="btn-custom" onclick="remove_inventory('+int_i+',0)"> Remove </a>';
      } 
    }
    
    j= int_i+1;
   
    var drop_down='<select id="unit_dropdown_'+int_i+'" name="unit_value['+int_i+']"><option value="">Select Unit</option></select>'
    $('#inventory_box').append('<tr class="range_row" id="row_'+int_i+'"><td>'+j+'</td><td><input type="text" name="item_name['+int_i+']" value="" class="" id="item_name_'+int_i+'"/><input type="hidden" name="item_id['+int_i+']" value="" class="" id="item_id_'+int_i+'"/></td> <td><input type="text" name="quantity['+int_i+']" value="" class="qty" id="quantity_'+int_i+'" /></td> <td>'+drop_down+'</td> <td> '+action_btn+' </td></tr>');
   
      var getData1 = function (request, response) { 
        //var id = this.element.attr('id'); 
        row = int_i ;
        //alert(JSON.stringify(request))  
        $.ajax({
        url : "<?php echo base_url('stock_purchase/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });
    };

      var selectItem1 = function (event, ui) {
          var rowCount = $('#inventory_box >tbody >tr').length-1; 
         var id = $(this).attr('id'); 
         var explode = id.split("_");
         var names = ui.item.data.split("|");
        
         //alert($('#quantity_'+i).val(names[6]));
          $("#item_id_"+explode[2]).val(names[4]);
          $("#item_name_"+explode[2]).val(names[0]);
          $('#quantity_'+explode[2]).val(names[6]);
          $('#unit_dropdown_'+explode[2]).html(names[7]);
          
         return false;
      }
      
      $("#item_name_"+int_i).autocomplete({
          source: getData1,
          select: selectItem1,
          minLength: 0,
          change: function() 
          {   

          }
      });


    int_i++;
    j++;


    
}


 /* autocomplete code in item */
    <?php
    if(!empty($item_list))
    {
    $r = 0;
    $i=1;
    foreach($item_list as $item)
    {
    ?>

    var getData1 = function (request, response) { 
       //var id = this.element.attr('id'); 
        row = i ;
        //alert(JSON.stringify(request))  
        $.ajax({
        url : "<?php echo base_url('stock_purchase/get_item_values/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
        data: {
        name_startsWith: request.term,

        row_num : row
        },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });
    };

      var selectItem1 = function (event, ui) {
          var rowCount = $('#inventory_box >tbody >tr').length-1; 
         var id = $(this).attr('id'); 
         var explode = id.split("_");
         var names = ui.item.data.split("|");
        
          $("#item_id_"+explode[2]).val(names[4]);
          $("#item_name_"+explode[2]).val(names[0]);
          $('#quantity_'+explode[2]).val(names[6]);
          $('#unit_dropdown_'+explode[2]).append(names[7]);
          
         return false;
      }
       $("#item_name_<?php echo $r; ?>").autocomplete({
          source: getData1,
          select: selectItem1,
          minLength: 2,
          change: function() 
          {   

          }
      });
  
      <?php $i++; $r++;} }?>

    /* autocomplete code in item */

$(document).ready(function(){
  
  add_inventory(0);
   /* var r = 0;

   r++; 
   if(r==1)
   {
     
     add_inventory(r);
   }
   else
   {
    
     $('.inventory_row').remove();
     r = 0;
     i = 0;
   }    
   
    
    add_inventory(1);
  //get_unit();
  var countRow = $('#medicine tr#nodata').length;*/
  

})

//var i= 0;
//var int_i =0;  
    

//j= int_i+1;
function remove_inventory(tr_id)
{
   $("#row_"+tr_id).remove();
}
</script>  


</div><!-- /.modal-dialog -->