<script>
   $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    <?php if(!empty($sample_date)){ ?>
    startDate : new Date('<?php echo $sample_date; ?>'), 
    <?php } ?>
    autoclose: true, 
  });
 

 $('.datepicker3').datetimepicker({
     format: 'LT'
  });
  

</script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<div class="modal-dialog" role="document" style="width:30%">
  <div class="modal-content">
    <!--onclick="$('#load_sample_modal_popup').hide();"-->
    <div class="modal-header">
      <h4>Sample Received Date
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
     </h4>
    </div>
    <?php if(!empty($sample_date)){ ?>
     <form id="sample_received_form" method="post" action="">
    <div class="modal-body">
        <lable>Sample received date:</lable>
          <div class="col-sm-10">
            <input type="hidden" name="testid" value="<?php echo $testid;?>">
            <input type="text" placeholder="Select Date" name="sample_received_date" class="datepicker" id="sample_received_date" value="<?php if(!empty($sample_receive_date) && $sample_receive_date!='2'){echo date('d-m-Y', strtotime($sample_receive_date));}else{}?>" style="width:130px;">
             <input type="text" name="sample_received_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php if(!empty($sample_receive_date)){echo date('h:i:A', strtotime($sample_receive_date));}elseif(!empty($sample_date)){ echo date('h:i:A', strtotime($sample_date)); }else{echo date("h:i:A");}?>">
           </div>
         <br>
         <br>
     </div> <!-- modal-body -->
      <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="sample_received">Save</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>
   
     </form>
     <?php }else{ ?>
     <div class="modal-body">
         <p style="color:red;">Please fill the sample collected date first.</p>
         </div>
     
     <?php } ?>
  </div>
</div>
<script>
 /* $('.datepicker').datepicker({
      format: 'dd-mm-yyyy h:i:A', 
      autoclose: true, 
      startDate : new Date('2021-06-14'), 
    });*/

 $('#sample_received').on('click',function(e){  
  
       // e.preventDefault();
        $.ajax({
          'type':'POST',
          'url':'<?php echo base_url('test/update_sample_received');?>',
          'data':$( "#sample_received_form" ).serialize(),
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