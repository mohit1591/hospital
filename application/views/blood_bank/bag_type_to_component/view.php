
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
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <?php
      //print_r($form_data);
      //die;
      ?>
      <div class="modal-body">  
        <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-3">
                  <b>Bag Type</b>:  
                </div>
                <div class="col-md-3">
                     <b><?php echo $bag_type_list[0]->bag_type;?></b>
                </div>
              </div>
             </div>
             
            <div class="col-md-12">  
              <b>Components</b><br/>
               <?php 
                    $x=1;
               foreach($bag_type_list as $bag_type) { ?>
                   <?php echo $x; ?>:<?php echo $bag_type->component;?><br/>

            <?php  $x++; }  ?>
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
 
$("#camp_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'bag type to component  successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'bag type to component  successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('blood_bank/bag_type_to_component/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_camp_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_deferral_reason();
        reload_table();
      } 
      else
      {
        $("#load_add_camp_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_camp_modal_popup').modal('hide');
});



</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->