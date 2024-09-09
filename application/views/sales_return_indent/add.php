<?php
$this->load->model('medicine_entry/medicine_entry_model','medicine_entry');
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
 

</head>

<body onLoad="list_added_medicine();">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  $query_string = "";
  if(!empty($_SERVER['QUERY_STRING']))
  {
    $query_string = '?'.$_SERVER['QUERY_STRING'];
  }
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
 
   <form id="sales_return_form" action="<?php echo current_url().$query_string; ?>" method="post"> 
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
	<input type="hidden"   name="patient_id" id="patient_id" value="<?php if(isset($form_data['patient_id'])){echo $form_data['patient_id'];}?>"/>
        <div class="row m-b-5">
               <div class="col-md-4">
        <!-- ///////////// -->
        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Issue No.</label>
            </div>
            <div class="col-md-7">
                <input type="text" name="sales_no" class="m_input_default" value="<?php echo $form_data['sales_no'];?>" readonly >
            </div>
        </div> <!-- innerRow -->

        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Issue Date</label>
            </div>
            <div class="col-md-7">
                <input type="text" name="sales_date" class="datepicker m_input_default" value="<?php if($form_data['sales_date']=='00-00-0000' || empty($form_data['sales_date'])){echo '';}else{echo $form_data['sales_date'];}?>">
            </div>
        </div> <!-- innerRow -->

        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Select Indent <span class="star">*</span></label>
            </div>
            <div class="col-md-7">
                <select class="" name="indent_id" id="indent_id" >
                    <?php foreach($indent_list as $indent){?>
                      <option value="<?php echo $indent->id; ?>" <?php if($form_data['indent_id']==$indent->id){ echo 'selected';}?>><?php echo $indent->indent;?></option>
                    <?php }
                    ?>

                </select>
            </div>
        </div> <!-- innerRow -->
    </div> <!-- Middle Col-md-4 Close -->
    <div class="col-md-4">
        <div class="row m-b-5">
            <div class="col-md-4">
                <label>Remarks</label>
            </div>
            <div class="col-md-8">
                <textarea type="text" id="remarks" name="remarks" class=""><?php echo $form_data['remarks'];?></textarea>
            </div>
        </div> <!-- innerRow -->
    </div> <!-- Right Side Col-md-4 Close -->
        </div> <!-- row -->
            
    
    
    
    
    <div class="userlist-right relative">
        <div class="fixed">
            <div class="btns"></div>
        </div>
    </div>







 <div class="sale_medicine_tbl_box" id="medicine_table">
        <div class="left">
            <table class="table table-bordered table-striped">
                <thead class="bg-theme">
                    <tr>
                        <th class="40" align="center"><input type="checkbox" name="" class=""  onClick="toggle(this);add_check();" value=""></th>
                        <th>Medicine Name</th>
                        <th>Packing</th>
                        <th>Medicine Code</th>
                        <th>HSN No.</th>
                        <th>Medicine Company</th>
                        <th>Batch No.</th>
                        <th>Barcode</th>
                        <!--<th>Mfd. Date</th>
                        <th>Exp. Date</th>-->
                        <th>Min Alert</th>
                        <th>Quantity</th>
                        <th>MRP</th>
                        <th>Discount(%)</th>
                        <th>CGST(%)</th>
                        <th>SGST(%)</th>
                        <th>IGST(%)</th>
                         <!--<th>Total</th>-->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                     <tr id="previours_row">
                       <td colspan=""></td>
                        <td colspan=""><input type="text" name="medicine_name" id="medicine_name" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="packing" id="packing"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="medicine_code" id="medicine_code" onKeyUp="search_func(this.value);"/></td>
                          <td colspan=""><input type="text" name="hsn_no" id="hsn_no" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="medicine_company" id="medicine_company" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="batch_number" id="batch_number" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="bar_code" id="bar_code" onKeyUp="search_func(this.value);"/></td>
                       
                        <td colspan=""><input type="text" name="stock" id="stock" onKeyUp="search_func(this.value);"/></td>
                         <td colspan=""><input type="text" name="qty" id="qty"  onkeyup="search_func(this.value);"/></td>
                       <td colspan=""><input type="text" name="rate" id="rate"  onkeyup="search_func(this.value);"/></td>
                   
                        <td colspan=""><input type="text" name="discount" id="discount_search"  onkeyup="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="cgst" id="cgst_search" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="sgst" id="sgst_search" onKeyUp="search_func(this.value);"/></td>
                        <td colspan=""><input type="text" name="igst" id="igst_search" onKeyUp="search_func(this.value);"/></td>
                        
                     </tr>
                         <td class="append_row text-danger" colspan="15"><div class="text-center">No record found</div></td>
                    </tr>
                </tbody>
            </table>
        </div> <!-- left -->
        <!--<div class="right">
             <a class="btn-new" onClick="child_medicine_vals();">Add</a>
        </div> -->
        <div class="right relative">
            <div class="fixed">
                <button class="btn-save" type="button" id="sales_return_submit"><i class="fa fa-floppy-o"></i> <?php echo $button_value; ?></button>
                <!--<button class="btn-save"><i class="fa fa-refresh"></i> Update</button>-->
                <a href="<?php echo base_url('sales_return_indent');?>" class="btn-anchor"><i class="fa fa-sign-out"></i> Exit</a>
            </div>
        </div> <!-- dont delete this div -->

        <!-- right -->
    </div> <!-- sale_medicine_tbl_box -->



    <div class="sale_medicine_tbl_box" id="medicine_select">
      
    </div> <!-- sale_medicine_tbl_box -->

</form>
</section> <!-- section close -->

<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>
<script>
$(document).on('focus', 'input[type=text]', function(){
    var $input = $(this);
    if ($input.val() == "0")
    {
        $input.val("");
    }
    if ($input.val() == "0.00")
    {
        $input.val("");
    }
});

$('#sales_return_submit').click(function(){  
    $(':input[id=sales_return_submit]').prop('disabled', true);
   $('#sales_return_form').submit();
});





$(document).ready(function(){
     payment_calc_all();
  
  $('.datepicker').datepicker({
                    format: "dd-mm-yyyy",
                    autoclose: true
                }); 
});

function search_func()
{  
    var medicine_name = $('#medicine_name').val();
    var hsn_no = $('#hsn_no').val();
    var medicine_code = $('#medicine_code').val();
    var medicine_company = $('#medicine_company').val();
    var batch_number= $('#batch_number').val();
    var packing = $('#packing').val();
    var stock = $('#stock').val();
    var qty = $('#qty').val();
    var mrp = $('#mrp').val();
    var rate = $('#rate').val();
    var discount = $('#discount_search').val();
    var cgst = $('#cgst_search').val();
    var sgst = $('#sgst_search').val();
    var igst = $('#igst_search').val();
    var bar_code = $('#bar_code').val();
    $.ajax({
       type: "POST",
       url: "<?php echo base_url('sales_return_indent/ajax_list_medicine')?>",
       data: {'medicine_name' : medicine_name,'medicine_code':medicine_code,'medicine_company':medicine_company,'stock':stock,'qty':qty,'rate':rate,'discount':discount,'cgst':cgst,'sgst':sgst,'hsn_no':hsn_no,'igst':igst,'packing':packing,'batch_number':batch_number,'bar_code':bar_code},
       dataType: "json",
       success: function(msg){
          $(".append_row").remove();
           $("#previours_row").after(msg.data);
         payment_calc_all();
                   //Receiving the result of search here
       }
    }); 
}

function add_check()
{
    var timerA = setInterval(function(){  
      child_medicine_vals();
      clearInterval(timerA); 
    }, 1000);
}

function child_medicine_vals() 
  {      

    var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
        if(allVals!=""){
       send_medicine(allVals);
       search_func();
       }
  } 
 function toggle(source) 
  {  
     checkboxes = document.getElementsByClassName('child_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
  function toggle_new(source) 
  {  
     checkboxes = document.getElementsByClassName('booked_checkbox');
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  }
   function send_medicine(allVals)
  {   
     // alert(allVals);
   if(allVals!="")
   {  
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_indent/set_medicine');?>",
              data: {medicine_id: allVals},
              dataType: "json",
              success: function(result) 
              {

                        $('#medicine_select').html(result.data); 
                        search_func(); 
                        list_added_medicine();
                        payment_calc_all();  
              }
          });
   }      
  }

  function list_added_medicine()
  {
    $.ajax({
                    url: "<?php echo base_url(); ?>sales_return_indent/ajax_added_medicine", 
                    dataType: "json",
                   success: function(result)
                    {
                      $('#medicine_select').html(result.data); 
                      //payment_calc_all();
                   } 
                 });   
  }

  function medicine_list_vals() 
  {      
  
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
       if(allVals!=""){
       remove_medicine(allVals);
       search_func();
        }
  
  } 
  function remove_medicine(allVals)
  { 
   if(allVals!="")
   {
   	//alert(allVals);
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_indent/remove_medicine_list');?>",
               dataType: "json",
              data: {medicine_id: allVals},
              success: function(result) 
              {  
                 $('#medicine_select').html(result.data); 
                    search_func();
                    payment_calc_all(); 
                    list_added_medicine();
                    $('#discount_amount').val('');
                    $('#total_amount').val('');
                    $('#net_amount').val('');
                    $('#cgst_amount').val('');
                    $('#igst_amount').val('');
                    $('#sgst_amount').val('');
                    $('#discount_all').val('');
                    $('#balance_due').val('');
                    $('#pay_amount').val('');
                    //$('#vat_percent').val('');
              }
          });
   }
  }


 function payment_cal_perrow(ids){
   
    var purchase_rate = $('#purchase_rate_mrp'+ids).val();
    var mrp = $('#mrp_'+ids).val();
    var qty = $('#qty_'+ids).val();
    var hsn_no = $('#hsn_no_'+ids).val();
     var mbid= $('#mbid_'+ids).val();
    var medicine_id= $('#medicine_id_'+ids).val();
    var expiry_date= $('#expiry_date_'+ids).val();
    var bar_code= $('#bar_code_'+ids).val();
    var manuf_date= $('#manuf_date_'+ids).val();
    var batch_no= $('#batch_no_'+ids).val();
    var igst= $('#igst_'+ids).val();
    var cgst= $('#cgst_'+ids).val();
    var sgst= $('#sgst_'+ids).val();
    var discount= $('#discount_'+ids).val();
    var conversion= $('#conversion_'+ids).val();
    $.ajax({
            type: "POST",
            url: "<?php echo base_url(); ?>sales_return_indent/payment_cal_perrow/", 
            dataType: "json",
            data: 'mbid='+mbid+'&purchase_rate='+purchase_rate+'&qty='+qty+'&medicine_id='+medicine_id+'&expiry_date='+expiry_date+'&igst='+igst+'&cgst='+cgst+'&sgst='+sgst+'&discount='+discount+'&manuf_date='+manuf_date+'&batch_no='+batch_no+'&conversion='+conversion+'&mrp='+mrp+'&hsn_no='+hsn_no+'&bar_code='+bar_code,
            success: function(result)
            {
               $('#total_amount_'+ids).val(result.total_amount); 
              
               payment_calc_all();
            } 
          });
 }

   function payment_calc_all(pay)
    {
    var data_id= '<?php echo $form_data['data_id'];?>';
    var discount = $('#discount_percent').val();
    var net_amount = $('#net_amount').val();
    var pay_amount = $('#pay_amount').val();  
        
      //alert(net_amount);
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>sales_return_indent/payment_calc_all/", 
      dataType: "json",
       data: 'discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
          $('#discount_amount').val(result.discount_amount);
          $('#total_amount').val(result.total_amount);
          $('#net_amount').val(result.net_amount);
          $('#pay_amount').val(result.pay_amount);
          $('#cgst_amount').val(result.cgst_amount);
          $('#sgst_amount').val(result.sgst_amount);
          $('#igst_amount').val(result.igst_amount);
          $('#discount_all').val(result.discount);
          //$('#vat_percent').val(result.vat);
          $('#balance_due').val(result.balance_due);
      } 
    });
    }

     function payemt_vals(pay)
  {
     var timerA = setInterval(function(){  
          payment_calc_all(pay);
          clearInterval(timerA); 
        }, 80);
  }

  



  function get_sales_docotors()
{
    $.ajax({url: "<?php echo base_url(); ?>sales_medicine/sales_medicine_dropdown/", 
    success: function(result)
    {
      $('#refered_id').html(result); 
    } 
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
$('#discount_percent').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

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


<style>

.ui-autocomplete { z-index:2147483647; }
</style>

<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<script type="text/javascript">
    $(function () {
    $('#no_ret').hide();
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('sales_return_indent/search_sales/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
        data: {
         name_startsWith: request.term,
         row_num : row
      },
       success: function( data ) {
        if(data.status!=0)
        {          
                $('#no_ret').hide();
                  response( $.map( data, function( item ) {
                  var code = item.split("|");
                  return {
                    label: code[0],
                    value: code[0],
                    data : item
                  }
                }));
          }
        else{
            $('#no_ret').show();
        }
      }
      });

       
    };

    var selectItem = function (event, ui) 
    {
           
            var names = ui.item.data.split("|");
            $('#sales_no').val(names[0]);
            $('#sales_id').val(names[1]);
            $('#referred_by').val(names[2]);
            $('#referral_hospital').val(names[3]);
            $('#ref_by_other_doctor').val(names[4]);
            $('#ref_by_other_hospital').val(names[5]);
            $('#refered_id').val(names[6]);
            $('#ref_by_other').val(names[7]);
            $('#total_amount').val(names[8]);
            $('#net_amount').val(names[9]);
            $('#pay_amount').val(names[10]);
            $('#balance_due').val(names[11]);
            $('#remarks').val(names[12]);
            $('#discount_amount').val(names[13]);
            $('#sgst_amount').val(names[14]);
            $('#cgst_amount').val(names[15]);
            $('#igst_amount').val(names[16]);
            $('#discount_percent').val(names[17]);
            $('#vat_percent').val(names[18]);
            $('#payment_mode').val(names[19]);
            $('#simulation_id').val(names[20]);
            $('#patinet_name').val(names[21]);
            $('#patient_id').val(names[22]);
            $('#mobile').val(names[23]);
            $('#email').val(names[24]);
            $('#relation_type').val(names[25]);
            $('#relation_simulation_id').val(names[26]);
            $('#relation_name').val(names[27]);
            $('#gender').val(names[28]);
            if(names[28]=='1')
            {
                $("#male_gender").attr('checked', 'checked');
            }
            else if(names[28]=='0')
            {
                $("#female_gender").attr('checked', 'checked');
            }
            else if(names[28]=='2')
            {
                $("#other_gender").attr('checked', 'checked');
            }
            set_sales_medicine(names[1]);
            return false;


    }

    $("#sales_no").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            
        }
    });
    });


  function set_sales_medicine(allVals)
  { 
  //alert(allVals );  
     if(allVals!="")
     {
        
        $.ajax({
              type: "POST",
              url: "<?php echo base_url('sales_return_indent/get_sales_medicine');?>",
              data: {sales_id: allVals},
              dataType: "json",
              success: function(result) 
              {
                $('#medicine_select').html(result.data); 
                //list_purchase_medicine();
                //payment_calc_all();  
              }
        });
     }      
  }

  
<?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 $sales_id = $this->session->userdata('sales_id');
 ?>
</script> 

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && isset($sales_id) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('sales_return_indent/add/');?>'; 
    }) ;
   
       
  <?php }?>
 });
</script>

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
         
          <div class="modal-footer">
           <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("sales_return_indent/print_sales_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>