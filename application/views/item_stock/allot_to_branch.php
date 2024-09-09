<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="allot_branch" class="form-inline">
       <!--  <input type="hidden" name="data_id" id="type_id" value="<?php //echo $form_data['data_id']; ?>" /> -->
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">


              <!-- // first row -->
              <div class="row m-b-5">
                <div class="col-md-12">
                    <div id="child_branch" class="patient_sub_branch"></div>
                </div> <!-- 12 -->
              </div> <!-- row -->

              <!-- // first row -->
              <div class="row m-b-5">
                <div class="col-md-12">
                  <div class="m_allotment_branches"><?php if(!empty($form_error)){ echo form_error('sub_branch_id'); } ?></div>
                </div> <!-- 12 -->
              </div> <!-- row -->
                

              <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped medicine_allotment" id="inventory_list">
                         <thead class="bg-theme">
                              <tr>
                    <th align="center" width="40"><input type="checkbox"  onclick= "check();"name="getitemselectAll" class="" id="getitemselectAll" value=""></th>
                                   <th>Item Name</th>
                                   <th>Item Code</th>
                                   <th>Qty</th>
                                   <th>Serial No.</th>
                  
                              </tr>
                         </thead>
                         <tbody id="inven">
                              <?php  echo $inventory_list; ?>
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
$(document).ready(function(){
  //get_unit();
  var countRow = $('#inven tr#nodata').length;
  
  if(countRow>0){
     $("#allot_to_branch").attr("disabled","disabled");
      $("#inven").html('<tr id="validate"><td class="text-danger text-center" colspan="5">Please Select atleast one</td></tr>');
  }else{
     $("#allot_to_branch").removeAttr("disabled");
      
    
  }

})
 $(document).ready(function(){
          $("#msg").html('');
          $.post('<?php echo base_url('item_stock/get_allsub_branch_list/'); ?>',{},function(result){
               
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
  
    $('#load_allot_to_branch_modal_popup').modal('hide'); 
}); 

$("#allot_branch").on("submit", function(event) { 
     event.preventDefault(); 
   
     $('.overlay-loader').show();
  
     var path = 'allot_item_to_branch/';
     var msg = 'Item successfully alloted.';
     var allVals = [];
     $.ajax({
          url: "<?php echo base_url(); ?>item_stock/"+path,
          type: "post",
          data: $(this).serialize(),
          
          success: function(result) {
               if(result==1)
               {
                    flash_session_msg(msg);
                    $('#load_allot_to_branch_modal_popup').modal('hide');
                    reload_table();
               } 
               else
               {
                    $("#load_allot_to_branch_modal_popup").html(result);
               }
               $('.overlay-loader').hide();       
          }
     });
}); 

 function check()
 {
     
     if($('#getitemselectAll').is(':checked')) 
     {    
        
          $('.itemchecklist').prop('checked', false);
     }
     else
     {

          $('.itemchecklist').prop('checked', false);
     }

 }
</script>

<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<script>

          function add_serial(value)
          {
            var ser_ar='';
            var ser_ids_ar='';
            var str_serial= $('#serial_no_array_'+value).val();
            if(str_serial!='')
            {
                var str_serial=JSON.parse(str_serial); 
                var ser_ar=str_serial.split(',');
                //var ser_length=ser_ar.length;
            }
            
            //get issued serial ids
            var str_issue_id_serial= $('#issued_ser_id_no_array_'+value).val();
             if(str_issue_id_serial!='')
            {
                var str_issue_id_serial=JSON.parse(str_issue_id_serial); 
                var ser_ids_ar=str_issue_id_serial.split(',');
                
            }
            
            var quantity=$('#qty_'+value).val();
          
            
                  if(value != '1_'+quantity)
                  {

                     $('#add_serial_no').val('1_'+quantity);

                     $('#serial_no_data').html('');

                    
                     if(quantity > 0)
                     {
                      pr="<tr><td><input type='hidden' id='serial_row_no' name='serial_row_no' value='"+value+"'></td></tr>";
                       $('#serial_no_data').append(pr);
                      //tr+="<tr><td>S.no</td><td>Serial No</td></tr>";
                       for(i=1;i<=quantity;i++)
                       {
                           var valss = ser_ar[i-1];
                           //if(typeof valss==="undefined")
                           if(typeof valss === 'undefined')
                           {
                             var valssw =''; 
                             
                           }
                           else
                           {
                              var valssw = ser_ar[i-1];
                           }
                           
                           var id_valss = ser_ids_ar[i-1];
                           if(typeof id_valss === 'undefined')
                           {
                             var id_valssw =''; 
                             
                           }
                           else
                           {
                              var id_valssw = ser_ids_ar[i-1];
                           }
                           
                           
                        tr="<tr>";
                        tr+="<td>"+i+"</td><td><input type='text' onkeyup='get_serial_autocomplete("+i+","+value+");' value='"+valssw+"' class='serial_"+i+"' id='serial_"+i+"'><input type='hidden' value='"+id_valssw+"'  id='issued_id_"+i+"' class='issued_id_"+i+"'></td>";
                        tr+="</tr>";
                        $('#serial_no_data').append(tr);
                        $('#save_serial_no_records').val(i);
                       
                       } 
                     }
                     else{
                        $('#serial_no_data').html("<div class='text-danger'>Insert Quantity First</div>");
                     } 
                  }
      
                 $('#serial_no').modal({
                     backdrop: 'static',
                     keyboard: false
                   })
          }
          
function get_serial_autocomplete(row_id,item_id)
{
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('item_stock/search_serial_no/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         
          item_id:item_id,
         type: 'country_table',
         row_num : row_id
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

    var selectItem = function (event, ui) {

          var names = ui.item.data.split("|");
          $('.serial_'+row_id).val(names[0]);
          $('#issued_id_'+row_id).val(names[1]);
          
          return false;
    }

    $(".serial_"+row_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }

          function show_serial(serials, k, status)
          {
                
              var str_serial=JSON.parse(serials);
              
              var ser_ar=str_serial.split(',');
              var ser_length=ser_ar.length;

              var serial_status=$("#serial_show_status_"+k).val();
          
                  $("#show_serial_no_data").html('');
                  $("#serial_show_status_"+k).val('1');
                  
                  tr="<tr><td>S.no</td><td>Serial No.</td></tr>";
                  for(i=0; i < ser_length; i++ )
                  {
                     if(i==0)
                     { 
                      tr+="<tr>";
                     } else { tr ="<tr>"; };

                     tr+="<td>"+(i+1)+"</td><td>"+ser_ar[i]+"</td>";
                     tr+="</tr>";
                     $("#show_serial_no_data").append(tr);
                     //console.log(tr);
                  }

     
                    $('#show_serial_no').modal({
                             backdrop: 'static',
                             keyboard: false
                           })
          }

          function save_serial_no_records(value)
          {
            var serial_row_no = $('#serial_row_no').val();
           rows=[];
           rows_ids=[];
           for(i=1;i <= value; i++)
           {
             val=$('#serial_'+i).val();
             rows.push(val);
             valids=$('#issued_id_'+i).val();
                 if(valids!='')
                 {
                   rows_ids.push(valids);  
                 }
           }
           
            
           $("#serial_no_array_"+serial_row_no).val('"'+rows+'"');
            $("#issued_ser_id_no_array_"+serial_row_no).val('"'+rows_ids+'"');
            
             $('#serial_no').modal('hide');
          }
          

 $("button[data-number=1]").click(function(){
                $('#serial_no').modal('hide');
            });
</script>  


</div><!-- /.modal-dialog -->

<div id="serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-number="1" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
              <table id="serial_no_data"></table>
              </div>
             </div> 

            <div class="modal-footer">
               <!--<button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>-->
               
        
              
              <button type="button"  class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>

                <button type="button" class="btn-cancel" data-number="1">Close</button>
               
            </div>
         </div>
      </div>
   </div>

  <div id="show_serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-dismiss="modal" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
                <table class="table table-striped table-bordered doctor_list dataTable no-footer" role="grid" aria-describedby="table_info" style="width: 100%;" width="100%" cellspacing="0" id="show_serial_no_data"></table>
               </div>
            </div>

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
         </div>
      </div>
   </div>