<style>

.ui-autocomplete { z-index:2147483647; }
</style>
 <div class="modal-dialog advance-search-modal">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="search_form" class="form-inline" autocomplete="off" > 
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xs-12">
                    <!-- ===============start================ -->
                    <div class="advance-search">
                      <div class="inner">
                        
                        <div class="grp">
                          <label>From Date</label>
                          <input type="text" class="datepicker" name="start_date" id="start_date" value="<?php echo $form_data['start_date']; ?>">
                        </div>
                        
                        
                        
                        <div class="grp">
                          <label>Indent Name</label>
                          <input type="text" name="indent_name" id="indent_name" value="<?php echo $form_data['indent_name']; ?>">
                         
                        </div>
                        
                      

                        <div class="grp">
                          <label>Issue No. </label>
                          <input type="text" name="sale_no" value="<?php echo $form_data['sale_no']; ?>">
                        </div>

                        
                        <div class="grp">
                          <label>Medicine Name</label>
                              <input type="text" name="medicine_name" id="medicine_name"  value="<?php echo $form_data['medicine_name']; ?>" onkeypress="search_func(this.value);" />
                              <div class="append_box_medicine medicin"></div>
                            
                        </div>
                        
                        
                        <div class="grp">
                          <label>Medicine company</label>
                              <input type="text" name="medicine_company" id="medicine_company" value="<?php echo $form_data['medicine_company']; ?>" onkeypress="search_func_com(this.value);" >
                               <div class="append_box_medicine comp"></div>
                             
                        </div>

                      <div class="grp">
                          <label>Medicine code</label>
                          <input type="text" name="medicine_code" id="medicine_code" value="<?php echo $form_data['medicine_code']; ?>" >
                        </div>
                        <div class="grp">
                          <label>CGST (RS.)</label>
                          <input type="text" name="cgst" id="cgst" value="<?php echo $form_data['cgst']; ?>" />
                        </div>

                        <div class="grp">
                          <label>SGST (Rs.)</label>
                          <input type="text" name="sgst" id="sgst" value="<?php echo $form_data['sgst']; ?>" />
                        </div>

                         <div class="grp">
                          <label>IGST (Rs.)</label>
                          <input type="text" name="igst" id="igst" value="<?php echo $form_data['igst']; ?>" />
                        </div>
            
                        
                        <div class="grp">
                          <label>Discount(%)</label>
                          <input type="text" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" />
                        </div>

                      </div> <!-- inner -->

                      <div class="inner">
                        
                        <div class="grp">
                          <label>To Date</label>
                          <input type="text" name="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>" id="end_date">
                        </div>
                          
                       
                          
                        <div class="grp">
                          <label>Batch No.</label>
                          <input type="text" name="batch_no" id="batch_no" value="<?php echo $form_data['batch_no']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Quantity</label>
                          <input type="text" name="quantity" onkeypress="return isNumberKey(event);" id="quantity" value="<?php echo $form_data['quantity']; ?>" maxlength="10">
                        </div>
                          
                         
                          
                        <div class="grp">
                          <label>Packing</label>
                          <input type="text" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" />
                        </div>
                          
                        <div class="grp">
                          <label>Conversion</label>
                          <input type="text" name="conversion" onkeypress="return isNumberKey(event);" maxlength="10" id="conversion" value="<?php echo $form_data['conversion']; ?>" >
                        </div>

                   
                      </div> <!-- inner -->
                    </div> <!-- advance-search -->

                    <!-- ==================== ends ============= -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Search" />
               <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script> 


function reset_search()
{
  //alert();
  $.ajax({url: "<?php echo base_url(); ?>sales_indent/reset_search/", 
    success: function(result)
    {
      //$("#search_form").reset();
      reload_table();
    } 
  }); 
}

 
$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url('sales_indent/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_specialization_modal_popup').modal('hide');
});
 

  var today =new Date();
  $('#start_date').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
              var dt = new Date(selected);
             
              dt.setDate(dt.getDate() + 1);
             
              $("#end_date").datepicker("option", "minDate", selected);
        }
  })
 $('#end_date').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#start_date").datepicker("option", "maxDate", selected);
        }
  })
</script>  
<script> 


  function search_func(val)
  {  
    var len = val.length;
     if(len>=2)
     { 
     $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_entry/ajax_list_medicine')?>",
         data: {'medicine_name' : val},
         dataType: "json",
         success: function(msg){
          $(".append_row_opt").remove();
          $(".medicin").show().html(msg.data);
          $('.append_row_opt').click(function(){
        $('#medicine_name').val($(this).text());
        $(".append_box_medicine").hide()
      });
         }
      }); 
    }
  }

  function search_func_com(val)
  {  
     var len = val.length;
     if(len>=2)
     {
     $.ajax({
         type: "POST",
         url: "<?php echo base_url('medicine_entry/ajax_list_medicine_com')?>",
         data: {'company_name' : val},
         dataType: "json",
         success: function(msg){
          $(".append_row_opt_com").remove();
          $(".comp").show().html(msg.data);
          $('.append_row_opt_com').click(function(){
        $('#medicine_company').val($(this).text());
        $(".append_box_medicine").hide()
      });
         }
      }); 
    }
  }
      </script>
  </div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->