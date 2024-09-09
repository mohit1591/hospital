<?php
$users_data = $this->session->userdata('auth_users');
//print_r($payment_mode);die;
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="update_advance_apyment" class="form-inline">
        <input type="hidden" name="data_id" id="type_id" value="" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">


              <!-- // first row -->
             <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped medicine_allotment" id="medicine_list">
                         <thead class="bg-theme">
                              <tr>
                    <th align="center" width="40">Sr.No</th>
                                   <th>Date</th>
                                   <th>Amount</th>
                                   <th style="text-transform: none !important;">Mode of Payment</th>
                                   
                  
                              </tr>
                         </thead>
                         <tbody id="medicine">
                             <?php 
                             $i=1;
                             $field_list='';
                             foreach($advance_list as $advance_payment_list)
                             { 
                             ?>
                             <tr>
                             <td><?php echo $i; ?></td>
                             <td><input type="text" name="advance_payment_date[<?php echo $advance_payment_list->id; ?>][date]" class="datepicker" value="<?php echo date('d-m-Y',strtotime($advance_payment_list->start_date)) ;?>"/></td>
                             <td><input type="text" name="advance_payment_date[<?php echo $advance_payment_list->id; ?>][net_price]" value="<?php echo $advance_payment_list->net_price ;?>"/></td>

                             <td style="width:300px;" >
                            <select onchange="select_bank_detail(this.value,'<?php echo $advance_payment_list->id;?>');"  name="advance_payment_date[<?php echo $advance_payment_list->id; ?>][payment_mode]">
                              <?php 
                              if(!empty($payment_mode))
                              {
                                foreach($payment_mode as $payment)
                                {
                                  ?>
                                     <option value="<?php echo $payment->id; ?>" <?php if($advance_payment_list->payment_mode==$payment->id){echo 'selected';} ?>><?php echo $payment->payment_mode; ?></option>
                                  <?php
                                }
                              }  
                              ?> 
                        </select>
                        

                             <span id="detail_data_<?php echo $advance_payment_list->id;?>">
                               <?php
                                  $field_list='';
                                  $field_list = get_advance_payment_pay_mode_field($advance_payment_list->branch_id,$advance_payment_list->id,3,10); 
                                //print_r($field_list);
                                  if(!empty($field_list))
                                  {
                                    foreach($field_list as $field)
                                    {
                                      echo '<input type="text" name="advance_payment_date['.$advance_payment_list->id.'][field_name][]" value="'.$field->field_value.'" id="field_value_new'.$advance_payment_list->id.$field->field_id.'" required/><input type="hidden" name="advance_payment_date['.$advance_payment_list->id.'][field_id][]" value="'.$field->field_id.'" id="field_value_"'.$advance_payment_list->id.'""/>';

                                      echo '<span style="margin: 0px 0px 0px 40px;" class="text-danger" id="field_error_'.$advance_payment_list->id.$field->field_id.'"></span>';
                                    }
                                  }
                               ?>
                                
                             </span>
                             </td>
                              </tr>
                             
                             <?php $i++;} ?>
                             <input type="hidden" value="<?php echo $form_data['ipd_id']; ?>" name="ipd_id" id="ipd_id" />
                             <input type="hidden" value="<?php echo $form_data['patient_id']; ?>" name="patient_id" id="patient_id"/>
                         </tbody>
                    </table>
                </div> <!-- 8 -->
              </div> <!-- Row -->

                  
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   
$(document).ready(function(){
  <?php if(isset($form_data['payment_mode']) && !empty($form_data['payment_mode'])) 
  { ?>

    //alert('<?php echo $form_data['transaction_no'];?>');
     select_bank_detail('<?php echo $form_data['payment_mode'];?>');
 <?php }?>
 
 var today =new Date();
    $('.datepicker').datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : today,
    onSelect: function (selected) {
 
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
        }
    })
})


function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 function check_validation()
 {
    <?php $i=1;
    foreach($advance_list as $advance_payment_list)
    { 
      $field_list = get_advance_payment_pay_mode_field($advance_payment_list->branch_id,$advance_payment_list->id,3,10);
      if(!empty($field_list))
     {
     foreach($field_list as $field)
      {
      ?>
     var status = 1;
     //alert('#field_value_new'+'<?php echo $advance_payment_list->id;?>'+'<?php echo $field->field_id ?>');
     //var value_field= $('#field_value_new'+'<?php echo $advance_payment_list->id;?>'+'<?php echo $field->field_id ?>').val();
      //alert(value_field);
      //return 2;
     //alert('#field_value_new'+'<?php echo $advance_payment_list->id;?>'+'<?php echo $field->field_id ?>');
    
     //alert(value_field);
     
     // if($('#field_value_new'+'<?php echo $advance_payment_list->id;?>'+'<?php echo $field->field_id ?>').val()=='')
     // {
     //  alert();
     // //  $('#field_error_'+'<?php echo $advance_payment_list->id;?>'+'<?php echo $field->field_id ?>').html('<?php echo $field->field_value;?>' 'is required'); 
     //  return 2;

     // }
     
  
   

<?php } } }?>
 }


$("#update_advance_apyment").on("submit", function(event) { 
  var status = check_validation();
  if(status==2)
  {
    return false;
  }
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  var ipd_id = $('#ipd_id').val();
  var patient_id = $('#patient_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'advance_detail/'+ids;
    var msg = 'Advance Payment successfully updated.';
  }
  else
  {
    var path = 'advance_detail/'+ipd_id+'/'+patient_id;
    var msg = 'Advance Payment successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ipd_discharge_bill/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        window.location.href="<?php echo base_url('ipd_discharge_bill/discharge_bill_info');?>/"+'<?php echo $ipd_id ?>'+'/'+'<?php echo $patient_id ?>';
      } 
      else
      {
        $("#load_advance_payment_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function select_bank_detail(value_advance,ids){
         $('#updated_payment_detail').html('');
              $.ajax({
                   type: "POST",
                   url: "<?php echo base_url('ipd_discharge_bill/get_payment_mode_data_advance')?>",
                   data: {'payment_mode_id' : value_advance,'advance_id':ids},
                   success: function(msg){
                   $('#detail_data_'+ids).html(msg);
                   }
              });


         $('.datepicker').datepicker({
         format: "dd-mm-yyyy",
         autoclose: true
         });  

    }


/*function select_bank_detail(value_advance,ids){
  if(value_advance==2)
  {
   
    $('#detail_data_'+ids).html('<input type="text" name="advance_payment_date['+ids+'][transaction_no]" value="" placeholder="Card No" id="transaction_no_'+ids+'"/><span style="margin: 0px 0px 0px 70px;" class="text-danger" id="transaction_error_'+ids+'"></span>');
  }
  if(value_advance==3)
  {
    $('#detail_data_'+ids).html('<input type="text" name="advance_payment_date['+ids+'][bank_name]" value="" placeholder="Bank Name" id="bank_name_'+ids+'"><span style="margin: 0px 0px 0px 70px;" class="text-danger" id="bank_name_error_'+ids+'"></span>  <input type="text" name="advance_payment_date['+ids+'][cheque_no]" value="" placeholder="Cheque No" id="cheque_no_'+ids+'"/><span style="margin: 0px 0px 0px 70px;" class="text-danger" id="cheque_no_error_'+ids+'"></span>  <input type="text" name="advance_payment_date['+ids+'][cheque_date]" value="" placeholder="Cheque Date" class="datepicker" id="cheque_date_'+ids+'"/><span style="margin: 0px 0px 0px 70px;" class="text-danger" id="cheque_date_error_'+ids+'"></span>');
  }
  if(value_advance==4)
  {
     $('#detail_data_'+ids).html('<input type="text" name="advance_payment_date['+ids+'][transaction_no]" value="" placeholder="Transaction No" id="transaction_neft_no_'+ids+'"/><span style="margin: 0px 0px 0px 40px;" class="text-danger" id="transaction_neft_error_'+ids+'"></span>');
  }

  if(value_advance==1 || value_advance=="")
  {
     $('#detail_data_'+ids).html(' ');
  }

    var today =new Date();
    $('.datepicker').datepicker({
    dateFormat: 'dd-mm-yy',
    minDate : today,
    onSelect: function (selected) {

    var dt = new Date(selected);
    dt.setDate(dt.getDate() + 1);
    }
    });

}*/



</script>  


</div><!-- /.modal-dialog -->