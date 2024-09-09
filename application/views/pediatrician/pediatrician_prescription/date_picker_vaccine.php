<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment.min.js"></script>   
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
 $(".datepicker").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });
</script>
<?php
if(!empty($form_data['vaccination_date_time']))
{
  $vaccination_date_time=$form_data['vaccination_date_time'];
}
else
{
  $vaccination_date_time='';
}
?>

<div class="modal-dialog emp-add-add">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="date_time_vaccine" class="form-inline">
    <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id'];?>" /> 
    <input type="hidden" name="age_id" id="age_id" value="<?php echo $form_data['age_id'];?>" /> 
    <input type="hidden" name="vaccine_id" id="vaccine_id" value="<?php echo $form_data['vaccine_id'];?>"/>

    <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $form_data['booking_id'];?>" /> 
    <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $form_data['patient_id'];?>"/>
    <div class="modal-header">
    
    <h4><?php echo $page_title;?></h4> 
    </div>

    <div class="modal-body">   
      <div class="row">
      <div class="col-md-12 m-b1">
      <div class="row">
      <div class="col-md-4">
      <label>Vaccination Date Time<span class="star">*</span></label>
      </div>
    
      <div class="col-xs-8">
              <input type="text" name="vaccination_date_time" class="datepicker date" data-date-format="dd-mm-yyyy HH:ii" value="<?php if($vaccination_date_time!='empty' && $vaccination_date_time!='0000-00-00:00:00:00' && $vaccination_date_time!='1970-01-01:05:30:00'){ echo $vaccination_date_time; } ?>" /> 
         
                 <?php if(!empty($form_error)){ echo form_error('vaccination_date_time'); } ?>
             </div>
      </div> <!-- innerrow -->
      </div> <!-- 12 -->
      </div> <!-- row -->  

      <div class="row">
      <div class="col-md-12 m-b1">
        <div class="row">
          <div class="col-md-4">
          <label>Attended Doctor</label>
          </div>
        <div class="col-md-8">
        <select name="attended_doctor">
        <?php foreach($attended_doctor as $attended)
        {?>
         <option value="<?php echo $attended->id; ?>" <?php if(isset($form_data['attended_doctor']) && $form_data['attended_doctor']==$attended->id){echo 'selected';} ?>><?php echo $attended->doctor_name; ?></option>
        <?php } ?></select>

        </div>
        </div> <!-- innerrow -->
      </div> <!-- 12 -->
      </div> <!-- row -->

      <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Mode of Payment</label>
            </div>
            <div class="col-md-8">
            <select  name="payment_mode" onChange="payment_function(this.value,'');">
            <?php foreach($payment_mode as $payment_mode) 
            {?>
            <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
            <?php }?>

            </select>

            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div>

      <div id="updated_payment_detail">
      <?php if(!empty($form_data['field_name']))
      { foreach ($form_data['field_name'] as $field_names) {

      $tot_values= explode('_',$field_names);

      ?>

        <div class="row"><div class="col-md-12 m-b1"><div class="row">
        <div class="col-md-4">
        <label><?php echo $tot_values[1];?><span class="star">*</span></label>
        </div>
        <div class="col-md-8">
        <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" onkeypress="payment_calc_all();">
        <input type="hidden" name="field_id[]" value="<?php echo $tot_values[2];?>" onkeypress="payment_calc_all();">
        </div>
        <div class="f_right">
        <?php 
        if(empty($tot_values[0]))
        {
        if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
        }
        ?>
        </div>
        </div>
        </div>
        </div>

      <?php } }?>

      </div>

      <div id="payment_detail">


      </div>


        <div class="row">
        <div class="col-md-12 m-b1">

          <div class="row">
            <div class="col-md-4">
            <label>Quantity</label>
            </div>
            <div class="col-md-8">
             <span class="label-success">Your Current Stock Is: <?php if(isset($form_data['qty'])){echo $form_data['qty'];}?></span>
            <input type="text" name="qty" id="quantity" value="1" readonly/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->   


      <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Total Amount</label>
            </div>
            <div class="col-md-8">
            <input type="text" name="total_amount" id="total_amount" value="<?php if(isset($form_data['total_amount'])){echo $form_data['total_amount'];}?>" readonly onKeyUp="payment_calc_all();"/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->  



      <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Discount</label>
            </div>
            <div class="col-md-8">
            <input type="text" name="discount" id="discount" value="<?php if(isset($form_data['discount'])){echo $form_data['discount'];}?>" onKeyUp="payment_calc_all();"/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->  


      <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Net Amount</label>
            </div>
            <div class="col-md-8">
            <input type="text" name="net_amount" id="net_amount" readonly onKeyUp="payment_calc_all();" value="<?php if(isset($form_data['net_amount'])){echo $form_data['net_amount'];}?>"/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->  


      <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Paid Amount</label>
            </div>
            <div class="col-md-8">
            <input type="text" name="paid_amount" id="paid_amount" onblur="payemt_vals(1);" value="<?php if(isset($form_data['paid_amount'])){echo $form_data['paid_amount'];}?>"/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->


       <div class="row">
        <div class="col-md-12 m-b1">
          <div class="row">
            <div class="col-md-4">
            <label>Balance</label>
            </div>
            <div class="col-md-8">
            <input type="text" name="balance" id="balance" readonly value="<?php if(isset($form_data['balance'])){echo $form_data['balance'];}?>"/>
            </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->

    </div> <!-- modal-body --> 

   


    <div class="modal-footer"> 
        <?php 

        if(isset($form_data['qty']))
        {
          if($form_data['qty']>0)
          {
          ?>
            <input type="submit"  class="btn-update" name="submit" value="Save"/>

            <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
            <?php
          }
        else
        {?>
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
        <?php } }


        ?>
    </div>



   
  </form>     
 
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->



<?php
//$last_id= $this->session->userdata('book_vaccine_id');
//print_r($last_id);
//die;
 ?>
<script>

 function payment_function(value,error_field){
    $('#updated_payment_detail').html('');
     $.ajax({
        type: "POST",
        url: "<?php echo base_url('pediatrician/pediatrician_prescription/get_payment_mode_data')?>",
        data: {'payment_mode_id' : value,'error_field':error_field},
       success: function(msg){
         $('#payment_detail').html(msg);
        }
    });
   }
$(document).ready(function (){
  <?php if(isset($from_data['data_id']) && $from_data['data_id']!='')
   {?>
    
 <?php } 
 else
  {?>
    payment_calc_all();
 <?php }?>

});
 function payment_calc_all(pay)
    {
      var data_id= '';
      var discount = $('#discount').val();
      var net_amount = $('#net_amount').val();
      var pay_amount= $('#paid_amount').val();
      var total_amount= $('#total_amount').val();
      $.ajax({
      type: "POST",
      url: "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/payment_calc_all/", 
      dataType: "json",
       data: 'total_amount='+total_amount+'&discount='+discount+'&net_amount='+net_amount+'&pay_amount='+pay_amount+'&pay='+pay+'&data_id='+data_id,
      success: function(result)
      {
            $('#discount_amount').val(result.discount_amount);
            $('#total_amount').val(result.total_amount);
            $('#net_amount').val(result.net_amount);
            $('#paid_amount').val(result.pay_amount);
           
            //$('#vat_percent').val(result.vat);
            $('#balance').val(result.balance_due);
      } 
    });
    }

  function payemt_vals(pay)
  {
    var timerA = setInterval(function(){  
    payment_calc_all(pay);
    clearInterval(timerA); 
    }, 80);
  }

// $('.datepicker').datepicker({
//     format: 'dd-mm-yyyy',
//     endDate : new Date(),
//     autoclose: true,
//     startView: 2
//   }) 
///


$("button[data-number=1]").click(function(){
    $('#load_add_pediatrician_modal_popup').modal('hide');
});

$("#date_time_vaccine").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  //alert(ids);return false;
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit_vaccine_pres/'+ids;
    var msg = 'Vaccination prescription successfully updated.';
  }
  else
  {
    var path = 'add_vaccine_pres/';
    var msg = 'Vaccination prescription successfully created.';
  } 
  var span_id = '<?php echo $form_data['get_span']; ?>';

  //alert(span_id);return false;
  $.ajax({
    url: "<?php echo base_url('pediatrician/pediatrician_prescription/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {

      if(result==1)
      {
       
        $('#load_add_pediatrician_modal_popup').modal('hide');
        flash_session_msg(msg);
         print_data();
         $("#icon_id_"+span_id).html('<span style="color:green;"><i class="fa fa-check"></i></span>');

       //window.location.reload(true);
      } 
      else
      {
        $("#load_add_pediatrician_modal_popup").html(result);
      }        
      $('#overlay-loader').hide();
    }
  });
}); 


function print_data()
{
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .on('click', '#cancel_pop', function(e)
    { 
        $('#confirm_print').modal('hide');
    }) ;
 }


</script>

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
          

             <?php 

             $url=  base_url("pediatrician/pediatrician_prescription/print_pediatrician_prescription_recipt"); 
             //echo $url;
             //die;
             ?>
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo $url; ?>');">Print</a>

            <button type="button"  class="btn-cancel" id="cancel_pop" data-number="1">Close</button>
          </div>
        </div>
      </div>  
    </div>
    <script>
  $("button[data-number=1]").click(function(){
        //$('#confirm_print').modal('hide');
  });
    </script>


