<?php
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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

</head>
<body>

<!-- Header -->
<div class="header_top">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
</div>
<!-- Content -->
<main class="main_page">
  <div class="main_wrapper">

    <div class="main_content">
      <section>
        <div class="row">
          <div class="col-lg-4">
            
            <div class="row m-b-5">
              <div class="col-md-5">
                <label for="">
                  <input type="radio"> New Vendor
                </label>
              </div>
              <div class="col-md-7">
                <label for="">
                  <input type="radio"> Registered Vendor
                </label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">UHID No.</label>
              </div>
              <div class="col-sm-7">
                <input type="text" class="form-control" value="RCr456">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">Referred By</label>
              </div>
              <div class="col-sm-7">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">Referred By Vendor</label>
              </div>
              <div class="col-sm-7">
                <div class="input-group">
                  <input type="text" class="form-control" value="RCr456">
                  <span class="input-group-addon">
                    <a href="#" class="">New</a>
                  </span>
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-4">
            
            <div class="row">
              <div class="col-sm-5">
                <label for="">Sale No.</label>
              </div>
              <div class="col-sm-7">
                <input type="text" class="form-control" value="Sara456">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">Sale Date</label>
              </div>
              <div class="col-sm-7">
                <input type="date" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">Vendor Name</label>
              </div>
              <div class="col-sm-7">
                <input type="text" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-5">
                <label for="">Mobile No.</label>
              </div>
              <div class="col-sm-7">
                <div class="input-group">
                  <span class="inpur-group-addon">
                    <input type="text" class="form-control" value="+91" style="width:20%;border-right:0;">
                  </span>
                  <input type="text" class="form-control" value="" style="width:80%">
                </div>
              </div>
            </div>

          </div>

          <div class="col-lg-4">
            
            <div class="row">
              <div class="col-sm-4">
                <label for="">Gender</label>
              </div>
              <div class="col-sm-8">
                <div class="from-control m-b-5">
                  <input type="radio" class=""> Male
                  <input type="radio" class=""> Female
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-4">
                <label for="">Remark</label>
              </div>
              <div class="col-sm-8">
                <textarea name="" id="" rows="4" class="form-control"></textarea>
              </div>
            </div>

          </div>

        </div>
      </section>
      
      <section style="max-width:100%;height:200px;border:1px solid #aaa;overflow:auto;margin-bottom:20px;">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-theme">
              <tr>
                <th class="40" align="center"><input type="checkbox" name="" class="" value=""></th>
                <th>Item Name</th>
                <th>Packing</th>
                <th>Item Code</th>
                <th>HSN No.</th>
                <th>Item Company</th>
                <th>Batch No.</th>
                <th>Barcode</th>
                <th>Min Alert</th>
                <th>Qty</th>
                <th>MRP</th>
                <th>Discount(%)</th>
                <th>CGST(%)</th>
                <th>SGST(%)</th>
                <th>IGST(%)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
              </tr>
              <tr>
                <td colspan="15" class="text-danger text-center">Data not found!!</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
      

      <div class="row m-b-5">
        <div class="col-md-12 text-right">
          <a href="#" class="btn-new">Delete</a>
        </div>
      </div>
      <section style="max-width:100%;height:200px;border:1px solid #aaa;overflow:auto;">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="bg-theme">
              <tr>
                <th class="40" align="center"><input type="checkbox" name="" class="" value=""></th>
                <th>Item Name</th>
                <th>Packing</th>
                <th>Item Code</th>
                <th>HSN No.</th>
                <th>Item Company</th>
                <th>Batch No.</th>
                <th>Barcode</th>
                <th>Min Alert</th>
                <th>Qty</th>
                <th>MRP</th>
                <th>Discount(%)</th>
                <th>CGST(%)</th>
                <th>SGST(%)</th>
                <th>IGST(%)</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td width="40" class="text-center"><input type="checkbox" class=""></td>
                <td><input type="text" class="form-control" disabled readonly value="Samosa"></td>
                <td><input type="text" class="form-control" disabled readonly value="PCK505"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
                <td><input type="text" class="form-control"></td>
              </tr>
              <tr>
                <td colspan="15" class="text-danger text-center">Data not found!!</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      
      <section style="display:flex;justify-content:space-between;">
        <div class="black_div" style="flex:1;"></div>
        <div class="new_mode_of_payment">
            <div class="row">
              <div class="col-md-5">
                <label for="">Mode of Payment</label>
              </div>
              <div class="col-md-7">
                <select name="payment_mode" class="form-control" onchange="payment_function(this.value,'');">
                 <option value="671">sara</option>
                 <option value="257">cash</option>
                 <option value="259">paytm</option>
                 <option value="263">card</option>
                 <option value="252">Debit</option>                 
               </select>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">Total Amount</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">Discount</label>
              </div>
              <div class="col-md-7">
                <div class="row">
                  <div class="col-sm-4 p-r-0">
                    <select name="" id="" class="form-control p-l-0 p-r-0">
                      <option value="">%</option>
                      <option value="">₹</option>
                    </select>
                  </div>
                  <div class="col-sm-3 p-r-0 p-l-0"><input type="text" class="form-control" value="0.00"></div>
                  <div class="col-sm-5 p-l-0"><input type="text" class="form-control" value="0.00"></div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">CGST(₹)</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">SGST(₹)</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">IGST(₹)</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">Net Amount</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">Pay Amount <span class="text-danger">*</span></label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="">Balance</label>
              </div>
              <div class="col-md-7">
                <input type="text" class="form-control" value="0.00">
              </div>
            </div>
        </div>
      </section>


    </div>
    <div class="main_btns">
      <div class="fixed-top">
        <!-- <a href="<?php echo base_url('canteen/sale/add');?>"> -->
          <button class="btn-hmas" type="button"><i class="fa fa-floppy-o"></i> Save</button>
        <!-- </a> -->
        <a href="<?php echo base_url('canteen/sale');?>">
          <button class="btn-hmas" type="button"><i class="fa fa-sign-out"></i>Exit</button>
        </a>
      </div>
    </div>

  </div>
  <!-- Footer -->
  <footer>
     <?php $this->load->view('include/footer'); ?> 
  </footer>
</main>
       




<script> 
$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger: 'focus' 
});



function add_second_unit(unit_id)
{
   $('#unit_second_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('canteen/item_manage/get_second_unit')?>",
        data: {'unit_id' : unit_id},
       success: function(msg){
         $('#unit_second_detail').html(msg);
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


 
$("#item_form").on("submit", function(event) { 
 // alert();
  event.preventDefault();  
  $('.overlay-loader').show(); 
  var ids = $('#data_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Item manage successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Item manage successfully created.';
  }   

  $.ajax({
    url: "<?php echo base_url(); ?>canteen/item_manage/"+path,
    type: "post",
    data: $(this).serialize(),
    //dataType:'json',
    success: function(result) {
    
      if(result==1)
      {
        $('#load_item_inventory_import_modal_popup').modal('hide');
        reload_table();
        flash_session_msg(msg);
        reload_table();
      } 

      else{
        $("#load_item_inventory_import_modal_popup").html(result);
      }   
      $('.overlay-loader').hide();     
    }
  });
}); 



function add_item_manage_vendor()
{ 
  var $modal = $('#load_add_item_manage_modal_popup');
  $modal.load('<?php echo base_url().'canteen/item_manage/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
    function(){
  $modal.modal('show');
  }); 
}



function add_category()
{ 
  var $modal = $('#load_add_Cat_modal_popup');
  $modal.load('<?php echo base_url().'item_category/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function get_stock_unit()
{
   $.ajax({url: "<?php echo base_url(); ?>canteen/stock_item_unit/unit_dropdown/", 
    success: function(result)
    {
      $('#stock_item_unit').html(result); 
    } 
  });
}

function add_stock_item_unit()
{ 
  var $modal = $('#load_add_stock_unit_modal_popup');
  $modal.load('<?php echo base_url().'canteen/stock_item_unit/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


$("button[data-number=1]").click(function(){
    $('#load_add_item_manage_modal_popup').modal('hide'); 
});
$("button[data-number=2]").click(function(){
    $('#load_item_inventory_import_modal_popup').modal('hide'); 
});



$(".Cap_item_name").on('keyup', function(){

   var str = $('.Cap_item_name').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.Cap_item_name').val(part_val.join(" "));
  
  });
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
      

</div><!-- /.modal-dialog -->
<div id="load_add_stock_unit_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_item_manage_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
