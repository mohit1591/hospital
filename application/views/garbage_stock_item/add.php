<?php
  // print_r($this->session->userdata('net_values_all'));
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

    <!--new css-->
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
    <!--new css-->
    



<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('385',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('purchase/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],
        "drawCallback": function() 
        {
            $.ajax({
                      dataType: "json",
                      url: "<?php echo base_url('purchase/total_calc_return');?>",
                      success: function(result) 
                      {
                        $('#total_net_amount').val(result.net_amount);
                        $('#total_discount').val(result.discount);
                        $('#total_balance').val(result.balance);
                        $('#total_vat').val(result.vat);
                        $('#total_paid_amount').val(result.paid_amount);
                      }
                  });
        },

    });


}); 
<?php } ?>



function edit_purchase(id)
{
  
  window.location.href='<?php echo base_url().'purchase/edit/';?>'+id
  
  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'purchase/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
} 

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        { 
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('purchase/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 }
 
 
 
 
</script>
</head>

<body>

<div class="container-fluid">
 <?php
  $query_string = "";
  if(!empty($_SERVER['QUERY_STRING']))
  {
    $query_string = '?'.$_SERVER['QUERY_STRING'];
  }
  
 ?>
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<form action="<?php echo current_url().$query_string; ?>" method="post">
 <input type="hidden" value="<?php echo $form_data['data_id'];?>" name="data_id"/>
<section class="userlist">
  <!-- Left side Contents  -->
  <div class="userlist-box media_tbl_full">
    <div class="row">
      <div class="col-md-11 pr-0">
        <!-- ////////// -->
        <table class="table table-bordered stock_item_issue_upper" style="margin-bottom:0;background: transparent;">
          <tbody>
            <tr>
              <td>Garbage No.</td>
             <td colspan="8"><?php echo $form_data['garbage_code'];?>
                <input type="hidden" value="<?php echo $form_data['garbage_code'] ?>" name="garbage_code"/>
                </td>
            </tr>
            <tr>
              <td>Item Name</td>
              <td><input type="text" name="item_name" class="item_name txt_firstCap" value="" id="">
              <input type="hidden" value="" id="item_id"/>
              <input type="hidden" value="" id="category_id"/>
               <input type="hidden" value="" id="remaining_quantity"/>
              
              <td>Garbage Date  </td>
              <td colspan="8"><input type="text" name="garbage_date" class="garbage_date garbagedatepicker" value="<?php echo $form_data['garbage_date'] ?>"/></td>
            </tr>
            <tr>
              <td>Item Code</td>
              <td><input type="text" name="item_code" id="item_code" value="" readonly></td>
              <td>Qty.</td>
              <td><input type="text" class="w-100px" value="0" name="quantity" onblur="payemt_vals();" id="quantity"></td>
              <td><b>Unit</b></td>
              <td><input type="text" class="w-100px" value="0" id="unit" name="unit"></td>
              <td><b>Price</b></td>
              <td><input type="text" class="w-100px" value="0.00" id="price" name="price" onblur="payemt_vals();"></td>
              <td><b>Amount</b></td>
              <td><input type="text" class="w-100px" value="0.00" name="amount" id="amount"></td>
            </tr>
          </tbody>
        </table>
      <span class="text-danger" id="error_message"></span>
        <!-- /////////// table 2nd /////////// -->
        <table class="table table-bordered table-striped purchase_stock_item_list" id="item_list">
          <thead class="bg-theme">
            <tr>
              <th><input type="checkbox" name=""></th>
                <th>S.No.</th>
                <th>Item No.</th>
                <th>Item Name</th>
                <th>Qty</th>
                <th>Serial No.</th>
                <th>Unit </th>
                <th>Price</th>
                 <th>Total Price</th>
            </tr>
          </thead>
           <tbody>
                  <?php //print '<pre>';print_r($garbage_stock_item_list) ;
                  if(isset($garbage_stock_item_list) && !empty($garbage_stock_item_list))
                  {
                    $i=1;
                  foreach($garbage_stock_item_list as $stock_p_list)
                  {
                      
                       
                    ?>
                    <tr>
                      <td><input type="checkbox" name="item_id[]" value="<?php echo $stock_p_list['item_id']; ?>"  class="booked_checkbox"></td>
                      <td><?php echo $i;?><input type="hidden" value="<?php echo $stock_p_list['item_id'];?>" id="item_id_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><?php echo $stock_p_list['item_code'];?><input type="hidden" value="<?php echo $stock_p_list['item_code'];?>" id="item_code_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><?php echo $stock_p_list['item_name'];?><input type="hidden" value="<?php echo $stock_p_list['item_name'];?>" id="item_name_<?php echo $stock_p_list['item_id'];?>"/></td>
                     <td><input type="text" value="<?php echo $stock_p_list['quantity'];?>" name="quantity" id="quantity_<?php echo $stock_p_list['item_id'];?>" onkeyup="payment_cal_perrow('<?php echo $stock_p_list['item_id'];?>');"/></td>
                     
                       <?php 
                        if(empty($stock_p_list['serial_no_array']))
                        {
                            $nre ='';  
                        }
                        else
                        {
                            
                            $nre = json_encode($stock_p_list['serial_no_array']);  
                        }
                        
                        if(empty($stock_p_list['serial_ser_id']))
                        {
                            $nre_ids ='';  
                        }
                        else
                        {
                            
                            $nre_ids = json_encode($stock_p_list['serial_ser_id']);  
                        }
                        
                        
                        ?>
                        
                       <td> <button id="add_serial_no" value="" class='btn-custom' type="button" onclick="return add_serial(<?php echo $stock_p_list['item_id'];?>,this.value,1)"> Add </button> </td><input type='hidden' name='serial_no_array[]' id="serial_no_array_<?php echo $stock_p_list['item_id'];?>" value='<?php echo $nre;?>'><input type='hidden' name='issued_ser_id_no_array[]' id="issued_ser_id_no_array_<?php echo $stock_p_list['item_id'];?>" value='<?php echo $nre_ids;?>'>
                       
                     <td><?php echo $stock_p_list['unit'];?><input type="hidden" value="<?php echo $stock_p_list['unit'];?>" id="unit_<?php echo $stock_p_list['item_id'];?>"/></td>
                      <td><?php echo $stock_p_list['item_price'];?><input type="hidden" value="<?php echo $stock_p_list['item_price'];?>" id="item_price_<?php echo $stock_p_list['item_id'];?>"/><input type="hidden" value="<?php echo $stock_p_list['category_id'];?>" id="category_id_<?php echo $stock_p_list['item_id'];?>"/></td>
                      <td><input type="text" value="<?php echo $stock_p_list['total_price'];?>" id="total_price_<?php echo $stock_p_list['item_id'];?>"/></td>
                    </tr>
                    <?php $i++ ;
                  } 
                } 
                else
                {?>
                <tr>
                <td colspan="8">
                No Records Found
                </td>
                </tr>
                <?php }
                    ?>

              <?php  if(!empty($form_error)){ ?>    
              <tr>
              <td colspan="8"><?php  echo form_error('item_id');  ?></td>
              </tr>
              <?php } ?>
                  </tbody>
        </table>

      <!-- /////////// table 3rd /////////// -->
        <table class="table stock_item_issue_lower">
          <tbody class="no-bg">
              <tr class="no-bg">
              <td><label>Remarks:</label></td>
                <td><textarea type="text" class="w-100px" class="price_float" name="remarks" id="remarks" ><?php echo $form_data['remarks'];?></textarea></td>
                 <td><label>Total Amount:</label></td>
                <td><input type="text" class="w-100px"   class="price_float" name="total_amount" id="total_amount" value="<?php if(!empty($stock_item_payment_payment_array['total_amount'])) { echo number_format($stock_item_payment_payment_array['total_amount'],2,'.',''); } else{ echo $form_data['total_amount']; } ?>"></td>

               
                
             
              </tr>
            </tbody>
        </table>


      </div> <!-- 11 -->
      <div class="col-md-1 pl-0">
        <!-- /////////////// -->
          <div class="addDelete mt-1em">
             <a class="btn-custom" onclick="item_payment_calculation();">Add</a>
              <a class="btn-custom" onclick="child_medicine_vals();">Delete</a>
          </div>
      </div> <!-- 1 -->
    </div> <!-- Row -->
      
  
    
   </div> <!-- close -->
   <!-- Ends Left all content section -->





    <!-- Right side buttons  -->
    <div class="userlist-right relative">
      <div class="fixed">
        <div class="btns">
         <button class="btn-save" id="purchase_submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
         <a href="<?php echo base_url('garbage_stock_item'); ?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
        </div>
      </div>
    </div> 
    <!-- Ends Right all Button section -->


  
</section> <!-- cbranch -->
</form>
<?php
$this->load->view('include/footer');
?>

<script> 
function reset_search()
  { 
    $('#start_date_p').val('');
    $('#end_date_p').val('');
    $('#paid_amount_from').val('');
    $('#paid_amount_to').val('');
    $('#balance_to').val('');
    $('#balance_from').val('');
    $('#purchase_no').val('');
    $('#invoice_id').val('');
    $('#branch_id').val('');
    $.ajax({url: "<?php echo base_url(); ?>purchase?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }

 function delete_purchase(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('purchase/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
</script> 

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('purchase');?>'; 
    }) ;
   
       
  <?php }?>
 });

 
$(document).ready(function(){
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $(this).find('.inputFocus').focus();
  });
});

</script>
<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintnewWindow('< ?php echo base_url("purchase/print_purchase_recipt"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("purchase/print_purchase_recipt"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_purchase').on('click', function(){
$modal.load('<?php echo base_url().'purchase/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});



</script>

<script>
$(document).ready(function(){


});

  function toggle(source) 
  {  
   
     checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function payemt_vals()
  {
      var timerA = setInterval(function(){  
      get_total_amount();
      clearInterval(timerA); 
      }, 80);
  }
  function get_total_amount(){

  var amount= $('#price').val();
   var quantity= $('#quantity').val();
  var total_amount2= amount *quantity;
  var total_amount = total_amount2.toFixed(2);
  $('#amount').val(total_amount);

}
  <?php
  $flash_success = $this->session->flashdata('success');
  if(isset($flash_success) && !empty($flash_success))
  {
   echo 'flash_session_msg("'.$flash_success.'");';
  }
  ?>

     var i=1;
     var getData = function (request, response) { 

        row = i ;
        $.ajax({
        url : "<?php echo base_url('garbage_stock_item/get_item_values/'); ?>" + request.term,
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

    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

          $('.item_name').val(names[0]);
          $('#item_code').val(names[1]);
          $('#unit').val(names[2]);
          $('#price').val(names[3]);
          $('#item_id').val(names[4]);
          $('#category_id').val(names[5]);
           $('#remaining_quantity').val(names[6]);
         
          //$('#medicine_id').val(names[4]);

        return false;
    }

    $(".item_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
  
    


  $(document).ready(function(){
   payment_new_calc_all()
  });
  function item_payment_calculation()
  {
    var item_name = $('.item_name').val();
    var amount = $('#amount').val();
    var quantity = $('#quantity').val();
    var item_code = $('#item_code').val();
    var item_id = $('#item_id').val();
    var category_id = $('#category_id').val();
    var remaining_quantity = $('#remaining_quantity').val();
    var unit = $('#unit').val();
    var item_price= $('#price').val();
    var total_amount = $('#total_amount').val();
    var item_code = $('#item_code').val();
    var total_price = $('#total_price').val();

    if(item_name!='')
    {
     $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>garbage_stock_item/item_payment_calculation/", 
            dataType: "json",
            data: 'amount='+amount+'&quantity='+quantity+'&item_id='+item_id+'&item_name='+item_name+'&total_amount='+total_amount+'&item_price='+item_price+'&item_code='+item_code+'&unit='+unit+'&category_id='+category_id+'&remaining_quantity='+remaining_quantity+'&total_price='+amount,
            success: function(result)
            {
               if(result.error==1)
               {
                   $('#error_message').html(result.message);
               }
               else
               {
                $('#item_list').html(result.html_data);
                 $('#total_amount').val(result.total_amount);
                  $('#pay_amount').val(result.paid_amount);
                  $('#net_amount').val(result.net_amount);
                   $('#total_amount').val(result.total_amount);   
                $('#price').val('');
                $('#amount').val('');
                $('#quantity').val('1');  
               }
             
            } 
          });
      }
        else
	  {
	  $('#noadded').modal({ backdrop: 'static', keyboard: false })
      } 
   
  }
  function payemt_get_balance(pay)
  {
    var timerA = setInterval(function(){  
    payment_new_calc_all(pay);
    clearInterval(timerA); 
    }, 80);
  }

  function payment_new_calc_all(update_row)
  {
        var discount = $('#discount_all').val();
        var discount_a= $('#discount_amount').val();
        var data_id= '<?php echo $form_data['data_id'];?>';
        var net_amount = $('#net_amount').val();
        var total_amount = $('#total_amount').val();
        var pay_amount = $('#pay_amount').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url(); ?>garbage_stock_item/payment_calc_all/", 
        dataType: "json",
        data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+update_row+'&discount_amount='+discount_a+'&data_id='+data_id+'&total_amount='+total_amount,
            success: function(result)
            {
                $('#discount_all').val(result.discount);
                $('#discount_amount').val(result.discount_amount);
                $('#total_amount').val(result.total_amount);
                $('#net_amount').val(result.net_amount);
                $('#pay_amount').val(result.pay_amount);
                $('#balance_due').val(result.balance_due);
            } 
        });
  }

  function payment_cal_perrow(ids){
   
    var item_code = $('#item_code_'+ids).val();
    var item_id = $('#item_id_'+ids).val();
    var category_id = $('#category_id_'+ids).val();
    var item_name = $('#item_name_'+ids).val();
    var quantity= $('#quantity_'+ids).val();
    var unit= $('#unit_'+ids).val();
    var item_price= $('#item_price_'+ids).val();
    var total_price= $('#total_price_'+ids).val();
    
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>garbage_stock_item/payment_cal_perrow/", 
            dataType: "json",
            data: 'item_code='+item_code+'&item_name='+item_name+'&quantity='+quantity+'&unit='+unit+'&item_price='+item_price+'&item_id='+item_id+'&category_id='+category_id+'&total_price='+total_price,
            success: function(result)
            {
               //$('#total_amount_'+ids).val(result.total_amount); 
              $('#total_price_'+ids).val(result.total_new_price);
               payment_new_calc_all();
            } 
          });
 }

function remove_item(allVals)
  { 
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('garbage_stock_item/remove_stock_purchase_item_list');?>",
               dataType: "json",
              data: {item_id: allVals},
              success: function(result) 
              {  
              $('#item_list').html(result.html_data); 
                payment_new_calc_all();
                $('#discount_amount').val('');
                $('#total_amount').val('');
                $('#net_amount').val('');
                $('#discount_all').val('');
               $('#balance_due').val('');
                $('#pay_amount').val('');

              }
          });
   }
     else
	  {
	  $('#nochecked').modal({ backdrop: 'static', keyboard: false })
      } 
  }
$('.garbagedatepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  });
  function child_medicine_vals() 
  {  
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
              
              //barcode.push($('#bar_code_'+this).val());
         } 
       });

       if(allVals!="")
        {
       remove_item(allVals);
       }
       else
	  {
	  $('#nochecked').modal({ backdrop: 'static', keyboard: false })
      } 
  }  
$(".txt_firstCap").on('keyup', function(){

   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCap').val(part_val.join(" "));
  
  });
  </script>

<!-- container-fluid -->
</body>
</html>

<script>
  $('input[id=purchase_submit]').click(function(){
    $(this).attr('disabled', 'disabled');
});

function add_serial(value)
    {   
            var ser_ar = '';
            var ser_ids_ar='';
            var str_serial= $('#serial_no_array_'+value).val();
            if(str_serial!='')
            {
               var str_serial=JSON.parse(str_serial); 
               var ser_ar=str_serial.split(',');
                
            }
            
            //get issued serial ids
            var str_issue_id_serial= $('#issued_ser_id_no_array_'+value).val();
             if(str_issue_id_serial!='')
            {
                var str_issue_id_serial=JSON.parse(str_issue_id_serial);
                if(str_issue_id_serial!='')
                {
                   var ser_ids_ar=str_issue_id_serial.split(','); 
                }
                
                
            }
          
            var quantity=$('#quantity_'+value).val();

                  if(value != '1_'+quantity)
                  {

                     $('#add_serial_no').val('1_'+quantity);

                     $('#serial_no_data').html('');

                   
                     if(quantity > 0)
                     {
                      pr="<tr><td><input type='hidden' id='serial_row_no' name='serial_row_no' value='"+value+"'></td></tr>";
                       $('#serial_no_data').append(pr);
                      
                       for(i=1;i <= quantity;i++)
                       {
                         
                         var valss = ser_ar[i-1];
        
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
                        tr+="<td>"+i+"</td><td><input type='text' value='"+valssw+"' onkeyup='get_serial_autocomplete("+i+","+value+");' id='serial_"+i+"' class='serial_"+i+"'><input type='hidden' value='"+id_valssw+"'  id='issued_id_"+i+"' class='issued_id_"+i+"'></td>";
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

          }
          
          
function get_serial_autocomplete(row_id,item_id)
{ 
    var issue_allot_id = '<?php echo $form_data['data_id'];?>';
    
      var getData = function (request, response) { 
      $.ajax({
        url : "<?php echo base_url('stock_issue_allotment/search_serial_no/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
          issue_allot_id: issue_allot_id,
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
</script>

<!-- Confirmation no select -->
<div id="noadded" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header bg-theme"><h4>Please Enter Item Name after add Item! </h4></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation no select -->

<!-- Confirmation no select -->
<div id="nochecked" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
         <div class="modal-header bg-theme"><h4>Please select at least one record! </h4></div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
<!-- Confirmation no select -->

<div id="serial_no" class="modal fade dlt-modal" >
      <div class="modal-dialog">
     
         <div class="modal-content serial_no_padd" style="padding:0">

            <div class="modal-header">
               <h4>Serial No. Details
               <button type="button" data-dismiss="modal" class="close">&times;</button></h4>
            </div>
            <div class="modal-body"  style="max-height: 400px;overflow-y: auto;">
               <div class="content-details">
              <table id="serial_no_data"></table>
              </div>
             </div> 

            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="save_serial_no_records" value="" onclick="save_serial_no_records(this.value)">Save</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
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
