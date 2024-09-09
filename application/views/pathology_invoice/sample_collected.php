<script>
   $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  });
 

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });
  

</script>
<div class="modal-dialog" role="document" style="width:30%">
  <div class="modal-content">
    <!--onclick="$('#load_sample_modal_popup').hide();"-->
    <div class="modal-header">
      <h4>Sample collected Date
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
     </h4>
    </div>
     <form id="sample_collected_form" method="post" action="">
    <div class="modal-body">
        <lable>Sample collected date:</lable>
         <div class="col-sm-10">
        <input type="hidden" name="testid" value="<?php echo $testid;?>">
        <input type="text" placeholder="Select Date" name="sample_collected_date" class="datepicker" id="sample_collected_date" value="<?php if(!empty($sample_date)){echo date('d-m-Y', strtotime($sample_date));}else{ }?>"style="width:130px;">
         <input type="text" name="sample_collected_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php if(!empty($sample_date)){echo date('h:i:A', strtotime($sample_date));}else{echo Date("h:i:A");}?>" >
         </div>
         <br>
         <br>
        
     </div> <!-- modal-body -->
      <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="sample_collected">Save</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>
   
     </form>
  </div>
</div>
<script>

$('#sample_collected').on('click',function(e){  
  
       // e.preventDefault();
        $.ajax({
          'type':'POST',
          'url':'<?php echo base_url('test/update_sample_collected');?>',
          'data':$( "#sample_collected_form" ).serialize(),
          'dataType':'json',
          success:function(result)
          {
            //$(".loader_modal").hide();
            if(result.success==1)
            {  
              
              flash_session_msg(result.msg);
              $('#load_sample_modal_popup').modal('hide');
                
              
            }
            /*else
            {   
              $('.flash_msg_random').css('display','block');
              $('.flash_msg_text_random').html(error_msg);
              $('.flash_msg_random').show('slow').delay(3000).toggle('slow');
              
            }*/
          }
        });
    });



</script>