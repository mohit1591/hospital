<div class="cp-modal">
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div> 
  <div class="modal-content modal-top"> 
    <form  id="document_file_form" class="form-inline" enctype="multipart/form-data"> 
    <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
    <input type="hidden" name="old_document_file" value="<?php echo $form_data['old_document_file']; ?>">
    <input type="hidden" name="vehicle_id" id="vehicle_id" value="<?php echo $form_data['vehicle_id']; ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4><?php echo $page_title; ?></h4> 
            </div>
        <div class="modal-body">  
            <div class="row">
               <div class="col-md-12">
                    <div class="row m-b-5">
                         <div class="col-md-5">
                              <label><b>Renewal Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" id="exp_start_date" name="renewal_date" class="datepicker start_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>
                    <div class="row">
                         <div class="col-md-5">      
                              <label><b>Expiry Date:</b></label>
                         </div>
                         <div class="col-md-7">
                              <input type="text" name="expiry_date" id="exp_end_date" class="datepicker datepicker_to end_datepicker" value="<?php echo date('d-m-Y'); ?>">
                         </div>
                    </div>

                     <div class="row m-t-5">
                         <div class="col-md-5">      
                              <label><b>Document Name: <span class="text-danger">*</span></b></label>
                         </div>
                         <div class="col-md-7">
                           <select name="document_id" id="document_id">
                                <option value="">Select</option>
                                   <?php
                                        if(!empty($document_list)){
                                             foreach($document_list as $list){?>
                                                <option value="<?php echo $list->id; ?>"><?php echo $list->document; ?></option>
                                           <?php  }
                                        }
                                   ?> 
                              </select> 
                              <?php if(!empty($form_error)){ echo form_error('document_id'); } ?>
                         </div>
                    </div>    

                    <div class="row m-t-5">
                         <div class="col-md-5">      
                              <label><b>Remark:</b></label>
                         </div>
                         <div class="col-md-7">
                            <textarea class="form-control" name="remark"></textarea>
                         </div>
                    </div>                             
               </div> <!-- 12 -->
          </div> <!-- row -->    

           <div class="row m-b1">                
                <div class="col-xs-7">
                    <input type="file" name="document_file"> <span class="text-danger">*</span>                   
                    <?php if(!empty($form_error)){ echo form_error('document_file'); } ?>
                    
                    <?php
                    if(!empty($document_file_error))
                    {
                      echo '<div class="text-danger">'.$document_file_error.'</div>';
                    }
                    if(!empty($form_data['old_document_file']) && file_exists(DIR_UPLOAD_PATH.'vehicle_docs/'.$form_data['old_document_file']))
                    {
                        $document_file = ROOT_UPLOADS_PATH.'vehicle_docs/'.$form_data['old_document_file'];
                        echo '<img src="'.$document_file.'" width="100px" />';
                    }
                    ?>

                </div> 
              </div> <!-- row -->
            </div> <!-- modal-body -->
        <div class="modal-footer"> 
           <button type="submit"  class="btn-update" name="submit" value="Save">Save</button>
           <button type="button" class="btn-cancel" data-dismiss="modal">Close</button> &nbsp;
        </div>
</form>     
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script>  
 

$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      var start_date=  $("#exp_start_date").val();
    var end_date=  $("#exp_end_date").val();
    $("#exp_end_date").datepicker('setStartDate',start_date); 
    $("#exp_end_date").datepicker({ minDate: selectedDate });
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     var start_date=  $("#exp_start_date").val();
    var end_date=  $("#exp_end_date").val();
    $("#exp_end_date").datepicker('setStartDate',start_date); 
    $("#exp_end_date").datepicker({ minDate: selectedDate });
  }); 


$("#document_file_form").on("submit", function(event) { 
  event.preventDefault();  
  $('#overlay-loader').show();
  var vehicle_id  = $('#vehicle_id').val();
     var path = 'upload_document'+'/'+vehicle_id;
     var msg = "Document file successfully added.";
  $.ajax({
    url: "<?php echo base_url('ambulance/vehicle/'); ?>"+path,
    type: "post", 
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false, 
    success: function(result) {
        $('#overlay-loader').hide();
        var res= JSON.parse(result);
      if(res.result==1)
      {
        $('#load_add_modal_popup').hide();
         reload_table();
        flash_session_msg(msg); 
      } 
      else{
        $("#load_add_modal_popup").html(result);
      }       
      
    }
  });
}); 
</script>   
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
</div> <!-- modal -->