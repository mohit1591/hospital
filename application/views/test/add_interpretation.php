<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  
  <form id="interpretation_form"  class="form-inline">
  <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id']; ?>" /> 
  <input type="hidden" name="test_id" id="test_id" value="<?php echo $form_data['test_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div> 
      <div class="modal-body">   
          <div class="row">
              <div class="col-md-12 m-b1">
              <div class="row"> 
                <div class="col-md-12">
                    <label>First Page</label>
                    </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
            <div class="col-md-12 m-b1">
                
              <div class="row"> 
                <div class="col-md-12">
                    <textarea name="interpretation_data" class="interpretation_data" id="interpretation_data">
                      <?php echo $form_data['interpretation_data']; ?>
                    </textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('interpretation_data'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          <div class="row"> 
            <div class="col-md-6">
              <ul class="list-inline tb_btns">
          <?php
          if(!empty($multi_interpretation))
          {   
            foreach($multi_interpretation as $m_interp)
            {
              ?>
               <li><a href="javascript:void(0);"  onclick="change_interpretation_data(<?php echo $m_interp['id']; ?>)"><?php echo $m_interp['title']; ?></a></li>
              <?php
            } 
          }
          ?> 
            </ul>
          </div>
          
          
          
          <div class="col-md-3"><input type="button" id="btnpagesec" class="btn btn-default" value="Page2"></div>
          
          <div class="col-md-3"><input type="button" id="btnpagethree" class="btn btn-default" value="Page3"></div>
           
            
           
            <script type="text/javascript">
            $(document).ready(function () {
                $("#btnpagesec").click(function () {
                    $("#page_second").toggle();
                });
                
                 $("#btnpagethree").click(function () {
                    $("#page_third").toggle();
                });
                
            });
            </script>
            

        </div>
        
        
        <div class="row" id="page_second" style="display:none">
             <div class="col-md-12 m-b1">
              <div class="row"> 
                <div class="col-md-12">
                    <label>Second Page</label>
                    </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
            
            <div class="col-md-12 m-b1">
              <div class="row"> 
                <div class="col-md-12">
                    <textarea name="interp_sec_page" class="interp_sec_page" id="interp_sec_page">
                      <?php echo $form_data['interp_sec_page']; ?>
                    </textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('interp_sec_page'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          
          <div class="row" id="page_third" style="display:none">
              <div class="col-md-12 m-b1">
              <div class="row"> 
                <div class="col-md-12">
                    <label>Third Page</label>
                    </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
            <div class="col-md-12 m-b1">
              <div class="row"> 
                <div class="col-md-12">
                    <textarea name="interp_thir_page" class="interp_thir_page" id="interp_thir_page">
                      <?php echo $form_data['interp_thir_page']; ?>
                    </textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('interp_thir_page'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
        


          
             
          
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
</form>     

<script>  
  CKEDITOR.replace( 'interpretation_data' );
  CKEDITOR.replace( 'interp_sec_page' );
  CKEDITOR.replace( 'interp_thir_page' );
 
 
$("#interpretation_form").on("submit", function(event) { 
  event.preventDefault(); 

for (instance in CKEDITOR.instances)
CKEDITOR.instances[instance].updateElement();

  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'add_interpretation/'+ids;
    var msg = 'Interpretation successfully updated.';
  }
  else
  {
    var path = 'add_interpretation/';
    var msg = 'Interpretation successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('test/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_interpretation_modal_popup').modal('hide');
        flash_session_msg(msg);  
      } 
      else
      {
        $("#load_add_interpretation_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 


function change_interpretation_data(multi_inter_id)
{
  $.ajax({
    url: '<?php echo base_url("test/multi_interpretation_change_data/"); ?>'+multi_inter_id, 
    success: function(result) 
    {
       //$('#interpretation_data').val(result);
       CKEDITOR.instances['interpretation_data'].setData(result)
    }   
  });
}
  


</script>  

<script>
    $(document).ready(function(){
      $('.tb_btns > li:first-child > a').click(function(){
        $('.tb_btns > li:first-child > a').addClass('tb_btns_green');
        $('.tb_btns > li:nth-child(2) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(3) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:last-child > a').removeClass('tb_btns_green');
      });
      $('.tb_btns > li:nth-child(2) > a').click(function(){
        $('.tb_btns > li:first-child > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(2) > a').addClass('tb_btns_green');
        $('.tb_btns > li:nth-child(3) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:last-child > a').removeClass('tb_btns_green');
      });
      $('.tb_btns > li:nth-child(3) > a').click(function(){
        $('.tb_btns > li:first-child > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(2) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(3) > a').addClass('tb_btns_green');
        $('.tb_btns > li:last-child > a').removeClass('tb_btns_green');
      });
      $('.tb_btns > li:last-child > a').click(function(){
        $('.tb_btns > li:first-child > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(2) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:nth-child(3) > a').removeClass('tb_btns_green');
        $('.tb_btns > li:last-child > a').addClass('tb_btns_green');
      });
    });
  </script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->