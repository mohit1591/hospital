<div class="modal-dialog">
<div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="rate_form" class="form-inline"> 
  
            <div class="modal-header">
                <button type="button" class="close p-t-0" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
      <div class="modal-body">  
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Rate Plan</label>
                </div>
                <div class="col-md-8">
                    <?php echo $result['title']; ?> 
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <?php
          $type = array('0'=>"Rs", '1'=>"%");
          $status = array('0'=>"<font color='red'>Inactive</font>", '1'=>"<font color='green'>Active</font>");
          ?>
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                <label> Patient Rate </label>
                </div>
                <div class="col-md-8">
                    <?php 
                    if($result['master_type']==1)
                    {
                         echo $result['master_rate'].' %'; 
                    }
                    else
                    {
                         echo 'Rs. '.$result['master_rate'];
                    }
                    ?>  
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label> Branch Rate  </label>
                </div>
                <div class="col-md-8"> 
                   <?php 
                    if($result['base_type']==1)
                    {
                         echo $result['base_rate'].' %'; 
                    }
                    else
                    {
                         echo 'Rs. '.$result['base_rate'];
                    }
                    ?>  
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4">
                    <label>Status </label>
                </div>
                <div class="col-md-8">
                     <?php echo $status[$result['status']]; ?>  
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
              

              

              
            
              

            
           
      </div> <!-- modal-body -->  
             
             
        <div class="modal-footer">  
           <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
        </div>
</form>     
  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->