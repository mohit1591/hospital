
<div class="modal-dialog emp-add-add modal-80">
<!--<div class="overlay-loader" id="overlay-loader">-->
<div>
       <!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">-->
    </div>
  <div class="modal-content"> 
  <form  id="camp_type" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php //echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
           <?php
           if(!empty($stock_list['flag']))
           {
              if($stock_list['flag']==1)
              {
                $page_title="Donor Deatils List";
              } 
              else
              {
                $page_title="Recipient Deatils List";
              }
         }
              ?>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <?php
      //print_r($form_data);
      //die;
      ?>
      <div class="modal-body">  
        <div class="row">
            <div class="col-md-12 m-b1">
              <?php
              if($stock_list['flag']==1)
              {
                $name="Donor Name";
              } 
              else
              {
                $name="Recipient Name";
              }

              ?>
              <div class="row">
                <div class="col-md-6">
                  <b><?php echo $name;?></b>:  
                </div>
                <div class="col-md-4">
                
                     <b><?php echo $stock_list['name'];?></b>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b>Blood Group</b>:  
                </div>
                <div class="col-md-4">
                     <b><?php echo $stock_list['blood'];?></b>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b>Bag Type</b>:  
                </div>
                <div class="col-md-4">
                     <b><?php echo $stock_list['bag_type'];?></b>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b>Component Name</b>:  
                </div>
                <div class="col-md-4">
                     <b><?php echo $stock_list['component_name'];?></b>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b>Component Price</b>:  
                </div>
                <div class="col-md-4">
                     <b><?php echo $stock_list['component_price'];?></b>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <b>Bar Code</b>:  
                </div>
                <div class="col-md-4">
                     <b><?php echo $stock_list['bar_code'];?></b>
                </div>
              </div>
              
             </div>
             
            
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
      
            
         
          
           
           
      </div> <!-- modal-body --> 

      
</form>     


<script>

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
  });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 




</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->