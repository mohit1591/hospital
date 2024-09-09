<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(12);

?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="item_form" class="form-inline">
              <!--  <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" /> -->
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <!-- ============================================================================================ -->
       <div class="modal-body"> 
             <?php //echo '<pre>';//print_r($form_data) ; ?>
 
               <div class="row">
                <div class="col-md-6">
        
                 <div class="row m-b-5">
                      <div class="col-md-4">
                           <strong>Item Code:</strong>
                      </div>
                      <div class="col-md-8">
                            <?php echo $form_data['item_code']; ?>
                      </div>
                 </div> <!-- row -->

               <div class="row m-b-5">
                  <div class="col-md-4">
                     <strong>Item Name: </strong>
                  </div>
                  <div class="col-md-8">
                     <?php echo $form_data['item']; ?>
                  </div>
               </div> <!-- row -->

               <div class="row m-b-5">
                  <div class="col-md-4">
                  <label>Mfg.Company:</label>
                 </div>
                  <div class="col-md-8">

                    <?php if(!empty($manuf_company_list)) {
                        foreach($manuf_company_list as $manuf_company)
                        {
                          if($manuf_company->id==$form_data['manuf_company']) { echo $manuf_company->company_name; } 
                        }
                      }
                     ?> 
  
                </div>
               </div> <!-- row -->

            <div class="row m-b-5">
              <div class="col-md-4">
                <strong>Item Category:</strong>
              </div>
              <div class="col-md-8">
                <?php if(!empty($category_list)) {
                        foreach($category_list as $category)
                        {
                          if($category->id==$form_data['category_id']) { echo $category->category; } 
                        }
                      }
                  ?> 
              </div>
            </div> <!-- row -->

              <div class="row m-b-5">
                <div class="col-md-4">
                <strong>Item Price: </strong>
                </div>
                <div class="col-md-8">
                <?php echo $form_data['price']; ?>
                </div>
              </div>

             <!-- add jan 20 -->
              <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Item MRP:</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                         <?php echo $form_data['mrp']; ?>
                      </div>

                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->

                <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Quantity:</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                         <?php echo ($form_data['stock_item_unit']*$form_data['conversion']+$form_data['second_unit']); ?>
                      </div>

                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->

              
<!-- added 20 -->

              </div><!-- left col-md-6 --> 

              <div class="col-md-6">
<!-- add jan 20 -->
                <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Unit 1:</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                          <?php echo $form_data['stock_item_unit'] ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                </div> <!-- row -->

                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Unit 2:</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <?php echo $form_data['second_unit'] ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                </div> <!-- row -->
<!-- add jan 20 -->
 
<!-- add jan 20 -->
                  
                 <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Conversion:</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                        <?php echo $form_data['conversion'] ?>
                      </div> <!-- 8 -->
                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->

                <div class="row m-b-5">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-4">
                        <label>Packing:</label>
                      </div> <!-- 4 -->
                      <div class="col-md-8">
                        <?php echo $form_data['packing']; ?>
                      </div> <!-- 8 -->
                    </div> <!-- innerRow -->
                  </div> <!-- 12 -->
                </div> <!-- row -->

                <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Rack No.:</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                              <?php if(!empty($unit_list)) {
                               foreach($rack_list as $rack)
                                  {
                                    if($rack->id==$form_data['rack_no']) { echo $rack->rack_no; } 
                                  }
                                }
                              ?> 
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row --> 
<!-- add jan 20 -->

                 <div class="row m-b-5">
                  <div class="col-md-4">
                     <label><strong>Status:</strong></label>
                  </div>
                  <div class="col-md-8">
                    <?php if($form_data['status']==1){ echo 'Active'; } else { echo 'Inactive'; }?>
                  </div>
               </div>

             </div><!-- right col-md-6 --> 
            </div><!--  row --> 
         </div> <!--  modal-body --> 

         <div class="modal-footer">   
              <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
         </div>
    </form>     

<script> 
$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
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

<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
