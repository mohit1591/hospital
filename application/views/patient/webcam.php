<div class="modal-dialog emp-add-add">

<script language="JavaScript">
    Webcam.set({
      width: 400,
      height: 400,
      image_format: 'jpeg',
      jpeg_quality: 90
    });
    Webcam.attach( '#my_camera' );
  </script> 
  <div class="modal-content"  style="width: 450px;"> 
  <form  id="webcam" class="form-inline"> 
      <div class="modal-header">
          <button type="button" class="close"  data-dismiss="model" aria-label="Close" data-number="1"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body"> 
          <div id="results">Your captured image will appear here...</div> 
          <div id="my_camera"></div>   
       </div> <!-- row -->   
      <div class="modal-footer"> 
         <input type="button"  class="btn-update" name="capture" onclick="return take_snapshot();" value="Capture" />
         <button type="button" class="btn-cancel" data-dismiss="model" data-number="1">Close</button>
      </div> 
</form>     
 <script language="JavaScript">
    function take_snapshot() 
    {  
      Webcam.snap( function(data_uri) 
      {  
        document.getElementById('results').innerHTML = 
          '<h2>Processing:</h2>';
        Webcam.upload( data_uri, '<?php echo base_url("patient/webcam_save"); ?>', function(code, text) 
          {
          $('#pimg').attr('src','<?php echo ROOT_UPLOADS_PATH."patients/"; ?>'+text);
          $('#capture_img').val(text);
          $('#load_start_cam_modal_popup').modal('hide');
          });  
      });
    }

     $("button[data-number=1]").click(function(){
  $('#load_start_cam_modal_popup').modal('hide');
});
  </script> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->