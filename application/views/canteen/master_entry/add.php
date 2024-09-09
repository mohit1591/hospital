<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
 

</head>

<body>


<div class="header_top">
<?php
$this->load->view('include/header');
$this->load->view('include/inner_header');
?>
</div>
      <!-- ============================= Main content start here ===================================== -->
<main class="main_page">
  <form action="<?php echo current_url(); ?>" method="post" id="master_form">
  <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
   <div class="main_wrapper">
      <div class="main_content">
        
        <div class="row">
           <div class="col-lg-4">
              <div class="well">
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="product_code">Product Code <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Product code" name="product_code" value="<?php echo $form_data['product_code']; ?>" readonly>
                         <?php if(!empty($form_error)){ echo form_error('product_code'); } ?>
                    </div>
                  
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Product Category <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-7">
                       <select name="product_category" id="" class="form-control">
                          <option value="">Select Category </option>
                           <?php
                                          if(!empty($category_lists))
                                          {
                                            foreach($category_lists as $category_list)
                                            {
                                              $selected = "";
                                              if($category_list->id==$form_data['category_id'])
                                              {
                                                $selected = 'selected="selected"';
                                              }
                                              echo '<option value="'.$category_list->id.'" '.$selected.'>'.$category_list->category.'</option>';
                                            }
                                          }
                                          ?> 
                       </select>
                        <?php if(!empty($form_error)){ echo form_error('product_category'); } ?>
                    </div>
                    
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Product Name <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Product name" name="product_name" value="<?php echo $form_data['product_name']; ?>">
                       <?php if(!empty($form_error)){ echo form_error('product_name'); } ?>
                    </div>
                     
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Unit1</label>
                    </div>
                    <div class="col-sm-7">
                       <div class="input-group" id="unit1_id">
                            <select name="unit1_id"  class="form-control">
                          <option value="">Select Unit </option>
                           <?php
                                          if(!empty($unit_lists))
                                          {
                                            foreach($unit_lists as $unit_list)
                                            {
                                              $selected = "";
                                              if($unit_list->id==$form_data['unit1_id'])
                                              {
                                                $selected = 'selected="selected"';
                                              }
                                              echo '<option value="'.$unit_list->id.'" '.$selected.'>'.$unit_list->unit.'</option>';
                                            }
                                          }
                                          ?> 
                       
                       </select>
                          <span class="input-group-addon">
                             <a href="#unit_modal" data-toggle="modal"><i class="fa fa-plus"></i> Add</a>
                          </span>
                       </div>
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Conversion <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Conversion" name="conversion" value="<?php echo $form_data['conversion']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('conversion'); } ?>
                    </div>
                    
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Expiry Alert</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Min. Expiry Days" name="expiry_days" value="<?php echo $form_data['expiry_days']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">MRP</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="MRP" name="mrp" value="<?php echo $form_data['mrp']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Purchase Rate</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Purchase Rate" name="purchase_rate" value="<?php echo $form_data['purchase_rate']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Status</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="radio" name="status" value="1" <?php if($form_data['status']==1){ echo "checked";} ?>> Active
                       <input type="radio" name="status" value="0" <?php if($form_data['status']==0){ echo "checked";} ?>> Inactive
                    </div>
                 </div>
              </div>
           </div>
           <div class="col-lg-4">
              <div class="well">
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Product Type <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-7">
                       <select id="type" name="type" class="form-control">
                          <option value="">Select Product Type</option>
                          <option value="1" <?php if($form_data['type']==1){ echo "selected";} ?>>Readymade</option>
                          <option value="2" <?php if($form_data['type']==2){ echo "selected";} ?>>Manufactured</option>
                       </select>
                        <?php if(!empty($form_error)){ echo form_error('type'); } ?>
                    </div>
                    
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Min. Qty. Alert</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Min. Qty. Alert" name="min_qty_alert" value="<?php echo $form_data['min_qty_alert']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Unit2</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="Unit2" name="unit2" value="<?php echo $form_data['unit2']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">CGST(%)</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="CGST(%)" name="cgst" value="<?php echo $form_data['cgst']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">SGST(%)</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="SGST(%)" name="sgst" value="<?php echo $form_data['sgst']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">IGST(%)</label>
                    </div>
                    <div class="col-sm-7">
                       <input type="text" class="form-control" placeholder="IGST(%)" name="igst" value="<?php echo $form_data['igst']; ?>">
                    </div>
                 </div>
                 <div class="row">
                    <div class="col-sm-5">
                       <label for="">Description</label>
                    </div>
                    <div class="col-sm-7">
                       <textarea name="description" id="" rows="5" class="form-control"><?php echo $form_data['description']; ?></textarea>
                    </div>
                 </div>
              </div>
           </div>
        </div>

     </div>
     <div class="main_btns">
        <div class="fixed-top">
          <button class="btn-hmas" id="btnsubmit" type="button"> Save </button>
         <!--  <button class="btn-hmas" type="button"> <i class="fa fa-trash"></i> Delete </button>
          <button class="btn-hmas" type="button"> <i class="fa fa-refresh"></i> Reload </button>-->
          <a href="<?php echo base_url('canteen/master_entry');?>">
            <button class="btn-hmas" type="button"> <i class="fa fa-sign-out"></i> Exit </button>
          </a> 
        </div>
     </div>
 </div>
</form>

   <footer>
     <?php $this->load->view('include/footer'); ?>
   </footer>
</main>


<!-- Unit Modal -->
<div id="unit_modal" class="modal fade dlt-modal canteen_box" data-backdrop="dynamic"  data-keyboard="true">
   <div class="modal-dialog">
    <div class="modal-content">
         <form id="unit_modal_form">
     <div class="modal-header bg-theme"><h4>Add Unit</h4></div>
    
     <div class="modal-body canteen_box">
      <div class="form-group">
        <div class="row">
          <div class="col-md-4"><label for="">Add Unit</label><span class="text-danger">*</span></div>
          <div class="col-md-8">
              <input type="text" name="stock_item_unit" class="form-control" placeholder="Unit name">  
               <?php if(!empty($form_error)){ echo form_error('stock_item_unit'); } ?>
             
          </div>
        </div>
    </div>
 </div>
 <div class="modal-footer">
   <button type="submit" name="submit" class="btn-update">Save</button>
   <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
</div>
</form>
</div>
</div>  
</div> <!-- modal -->


<!-- Confirmation Box end -->
<div id="load_add_Cat_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
<script>
$("#unit_modal_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  
  var ids = $('#type_id').val();
    var path = 'add/';
    var msg = 'Unit successfully created.';
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('canteen/stock_item_unit/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
    
     if(result==1)
     {
        $('#unit_modal').modal('hide');
        flash_session_msg(msg);
        $('#unit1_id').load(window.location.href  + ' #unit1_id');
     }
    else{
        //flash_session_msg(msg);
       $("#unit_modal").html(result);
    }
      $('#overlay-loader').hide();
  
    }
  });
}); 
</script>
<script>
    $(document).ready(function(){
      $('#btnsubmit').on("click",function(){
     $(':input[id=btnsubmit]').prop('disabled', true);
       $('#master_form').submit();
  })
    });
    </script>
</html>