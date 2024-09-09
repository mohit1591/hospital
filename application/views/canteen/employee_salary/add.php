<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="employee_salary_form" class="form-inline">
               <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">    
            
                              
                    <div class="row m-b-5">
                         <div class="col-md-6">
                              <div class="row m-b-5" id="employeesalary">
                                   <div class="col-md-4">
                                        <strong>Employee Type <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-8">
                                        <select id="emp_type_id" class="m_select_btn" name="emp_type_id" onchange="find_employee_salary()">
                                             <option value="">Select employee type</option>
                                             <?php if(!empty($type_list)){ 
                                                       foreach($type_list as $types){
                                                            if($form_data['emp_type_id'] == $types->id){
                                                                 $selected = "selected = 'selected'";
                                                            }else{
                                                                 $selected = "";
                                                            }
                                                            echo '<option value="'.$types->id.'" '.$selected.'>'.$types->department.'</option>'; 
                                                       } 
                                                  }
                                             ?>
                                        </select>
                                        <?php if(in_array('31',$users_data['permission']['action'])) {?>
                                             <a href="javascript:void(0)" onclick=" return add_emp_type();"  class="btn-new">
                                                  <i class="fa fa-plus"></i> Add
                                             </a>
                                        <?php } ?>
                                        <?php if(!empty($form_error)){ echo form_error('emp_type_id'); } ?>
                                   </div>
                               </div>
                                 <div class="row m-b-5">     
                                   <div class="col-md-4">
                                        <strong>Employee Listing<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <select name="employee_id" class="m_select_btn" id="emp_id" class="pat-select1" onchange="find_employee_salary()">
                                             <option value="">Select</option>
                                             <?php
                                                  if(!empty($employee_list)){
                                                       foreach($employee_list as $employee){
                                                            $selected_employee = '';
                                                            if($employee->id==$form_data['employee_id']){
                                                                 $selected_employee = 'selected="selected"';
                                                            }
                                                            echo '<option value="'.$employee->id.'" '.$selected_employee.'>'.$employee->name.'</option>';
                                                       }
                                                  }
                                             ?> 
                                        </select> 
                                         <?php if(in_array('9',$users_data['permission']['action'])): ?>
                                             <a href="javascript:void(0)" onclick="add_employee()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                                        <?php endif;?>
                                        <?php if(!empty($form_error)){ echo form_error('employee_id'); } ?>
                                   </div> <!-- 8 -->
                               </div> <!-- row -->
                                <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Expenses Date<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" readonly="readonly" contenteditable="false" class="datepicker m_input_default" name="expenses_date" id="expenses_date" value="<?php echo $form_data['expenses_date']; ?>" onchange="find_employee_salary()"/>
                                     
                                        <input type="hidden" name="voucher_no" value="<?php echo $form_data['voucher_no']; ?>"/> 
                                        <?php if(!empty($form_error)){ echo form_error('expenses_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                    
                                       <div class="col-md-4">
                                        <strong> Patient Balance<!-- <span class="star">*</span> --></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default" name="patient_balance" id="patient_balance" value="<?php echo $form_data['patient_balance']; ?>" />
                                       
                                        <?php if(!empty($form_error)){ echo form_error('patient_balance'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong> Pay Now<!-- <span class="star">*</span> --></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default"  name="pay_now" id="pay_now" value="<?php echo $form_data['pay_now']; ?>" />
                                      
                                        <?php if(!empty($form_error)){ echo form_error('pay_now'); } ?>
                                   </div> <!-- 8 -->
                                    
                              </div> <!-- row -->

                                <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong>Payment Mode<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                          <select  name="payment_mode" class="m_input_default" onChange="payment_function(this.value,'');">
                                          <?php foreach($payment_mode as $payment_mode) 
                                          {?>
                                          <option value="<?php echo $payment_mode->id;?>" <?php if($form_data['payment_mode']== $payment_mode->id){ echo 'selected';}?>><?php echo $payment_mode->payment_mode;?></option>
                                          <?php }?>

                                          </select>
                                        <?php if(!empty($form_error)){ echo form_error('payment_mode'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->


                           </div> <!-- 6 -->

                           <div class="col-sm-6">
                              <div id="updated_payment_detail">
                                  <?php if(!empty($form_data['field_name']))
                                  { foreach ($form_data['field_name'] as $field_names) {
                                  $tot_values= explode('_',$field_names);

                                  ?>

                                  <div class="row m-b-5" id="branch"> 
                                  <div class="col-md-4">
                                  <strong><?php echo $tot_values[1];?><span class="star">*</span></strong>
                                  </div>
                                  <div class="col-md-8"> 
                                  <input type="text" class="m_input_default" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" class="m_input_default" value="<?php echo $tot_values[2];?>" name="field_id[]" />
                                  <?php 
                                  if(empty($tot_values[0]))
                                  {
                                  if(!empty($form_error)){ echo '<div class="text-danger">The '.strtolower($tot_values[1]).' field is required.</div>'; } 
                                  }
                                  ?>
                                  </div>
                                  </div>
                                  <?php } }?>

                              </div>

                              <div id="payment_detail">


                              </div>
                              

                               <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong> Salary Amount<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text"   data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default" readonly = "readonly"  name="paid_amount" id="paid_amount" value="<?php echo $form_data['paid_amount']; ?>" />
                                      
                                        <?php if(!empty($form_error)){ echo form_error('paid_amount'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                               <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong> Advance<!-- <span class="star">*</span> --></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text"   data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default"  name="advance_paid" id="advance_paid" value="<?php echo $form_data['advance_paid']; ?>" />
                                       
                                        <?php if(!empty($form_error)){ echo form_error('advance_paid'); } ?>
                                   </div> <!-- 8 -->
                               </div> <!-- row -->

                              <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong> Balance<!-- <span class="star">*</span> --></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text"   data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default"  name="balance" id="balance" value="<?php echo $form_data['balance']; ?>" />
                                        
                                        <?php if(!empty($form_error)){ echo form_error('balance'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              <div class="row m-b-5" >
                                  <div class="col-md-4">
                                        <strong>Remarks</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <textarea name="remark" class="m_input_default" id="remark"><?php echo $form_data['remark']; ?></textarea> 
                                        <?php if(!empty($form_error)){ echo form_error('remark'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                         </div> <!-- main6 -->
                    </div> <!-- mainRow -->
               </div>    <!--  modal-body --> 
               <div class="modal-footer">
                    <input type="submit"  class="btn-save" name="submit" value="Save" />
                    <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
               </div>
          </form>      
          <script>
               $('.tooltip-text').tooltip({
                    placement: 'right', 
                    container: 'body',
                    trigger:'focus' 
               });
          </script>
          <script> 

        function payment_function(value,error_field){
        $('#updated_payment_detail').html('');
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('employee_salary/get_payment_mode_data')?>",
                data: {'payment_mode_id' : value,'error_field':error_field},
                success: function(msg){
                $('#payment_detail').html(msg);
                }
        });


        $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
            });  

        }
        $(document).ready(function(){
            $('.datepicker').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
            }); 
        });
          $(document).ready(function(){
               var id = $("#payment_mode").val();
               var expcatename =  $('#employee_salary_id option:selected').text();
               if(id){
                   get_payment_mode(id); 
               }
               if(expcatename){
                    get_choosen_emp_cat(expcatename);

               }
               
               
          })
          function checkAlphaNumeric(e) {
               if ((e.keyCode >= 48 && e.keyCode <= 57) ||
                  (e.keyCode >= 65 && e.keyCode <= 90) ||
                  (e.keyCode >= 97 && e.keyCode <= 122)){
                    return true;
               }
               else{
                    return false;
               }
          }
            $("#emp_type_id").change(function() {
               var type_id = $(this).val();
               var data_id = $('#data_id').val();
               if(data_id>0){
                    data_id = '/'+data_id;
               }
               $.ajax({ 
                    url: "<?php echo base_url('users/type_to_employee/'); ?>"+type_id+data_id, 
                    success: function(result){
                         $('#emp_id').html(result);
                    }
               });
          });
               function add_emp_type(){
               var $modal = $('#load_add_emp_type_modal_popup');
               $modal.load('<?php echo base_url().'employee_type/add/' ?>',{
                    //'id1': '1',
                    //'id2': '2'
               },
               function(){
                    $modal.modal('show');
               });
          }
           function add_employee(){
               var $modal = $('#load_add_emp_modal_popup');
               $modal.load('<?php echo base_url().'employee/add/' ?>',
               {
                    //'id1': '1',
                    //'id2': '2'
               },
               function(){
                    $modal.modal('show');
               });
          }
          function get_city(state_id){
               $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
                    success: function(result){
                         $('#city_id').html(result); 
                    }  
               }); 
          }
          function delete_confirmation(){ 
               alert('dd');return false;
          }
          $(document).on('click', '.delete-event', function(e) {
               alert('ddd');
          });
          function employee_salary_modal(){
               var $modal = $('#load_add_employee_salary_modal_popup');
               $modal.load('<?php echo base_url().'canteen/employee_salary/add/' ?>',
               {
                    //'id1': '1',
                    //'id2': '2'
               },
               function(){
                    $modal.modal('show');
               });
          }
          function find_employee_salary(){

             var emp_type= $('#emp_type_id').val();
             var expense_date= $('#expenses_date').val();
            var emp_id= $('#emp_id').val();
               $("#paid_amount").val('');
               if(emp_id!=''){
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url().'canteen/employee_salary/find_employee_salary' ?>", 
                    data:{'emp_id':emp_id,'emp_type':emp_type,'expense_date':expense_date},
                    dataType:"json",
                    success: function(result){
                     if(result.success==1){
                        $("#advance_paid").val(result.advance_payment);
                        $("#balance").val(result.balance);
                        $("#paid_amount").val(result.sal_amount);
                      }
                    }
                 });
               }
           }
          function isNumberKey(evt) {
               var charCode = (evt.which) ? evt.which : event.keyCode;
               if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
               } else {
                    return true;
               }      
          }
          function get_choosen_emp_cat(text){
              var expcatename =  $('#employee_salary_id option:selected').text();
              if(expcatename!=''){
                     if(expcatename=='Advance' || expcatename=='advance'){
                          document.getElementById("employeesalary").style.display = "block";
                     
                          // $("#employee_id").val('');
                    
                     
                     }else{
                       
                         document.getElementById("employeesalary").style.display = "none";
                         $("#emp_id").val('');
                         $("#emp_type_id").val('');
                     }
               }
          }
          function get_payment_mode(id){
              
              if(id=='cheque'){
                    document.getElementById("cheque").style.display="block";
                    document.getElementById("chequedate").style.display="block";
                    document.getElementById("chequeda").style.display="block";
                    
                    document.getElementById("card").style.display="none";
                    
                 
              }else if(id=='card' || id=='neft'){
                 
                  document.getElementById("cheque").style.display="none";
                   document.getElementById("chequedate").style.display="none";
                   document.getElementById("chequeda").style.display="none";
                  document.getElementById("card").style.display="block";
                 
                  $("#branchname").val('');
                  $("#chequedate").val('');
                  $("#chequeda").val('');

                 
              
              }else{
                  document.getElementById("cheque").style.display="none";
                  document.getElementById("card").style.display="none";
                   document.getElementById("chequedate").style.display="none";
                   document.getElementById("chequeda").style.display="none";
                
                  $("#branchname").val('');
                  $("#chequedate").val('');
                  $("#chequeda").val('');
                  $("#transactionno").val('');
                 
              }
          }
       
          $(function(){
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
        // endDate: new Date(),
        // startDate: '-0d',
         autoclose: true
    });

    $('.cheque_date').datepicker({
        format: 'dd-mm-yyyy',
        // endDate: new Date(),
        // startDate: '-0d',
         autoclose: true
    });

    
});
          $("#employee_salary_form").on("submit", function(event) { 
               event.preventDefault(); 
               $('.overlay-loader').show();
               var ids = $('#data_id').val();
               if(ids!="" && !isNaN(ids)){ 
                    var path = 'edit/'+ids;
                    var msg = 'employee salary successfully Updated.';
               }else{
                    var path = 'add/';
                    var msg = 'employee salary successfully Paid.';
               } 
               //alert('ddd');return false;
               $.ajax({
                    url: "<?php echo base_url(); ?>canteen/employee_salary/"+path,
                    type: "post",
                    data: $(this).serialize(),
                     //dataType: "json",
                    success: function(result) {
                         if(result==1){
                              flash_session_msg(msg);
                              $('#load_add_modal_popup').modal('hide');
                               window.location.href='<?php echo base_url('canteen/employee_salary/?status=print');?>';
                         } else{
                              $("#load_add_modal_popup").html(result);
                         }
                         $('.overlay-loader').hide();       
                    }
               });
          }); 

$(document).ready(function(){
   $('#load_add_emp_type_modal_popup').on('shown.bs.modal', function(e){
      $(this).find('.inputFocus').focus();
   });
});
$(document).ready(function(){
   $('#load_add_modal_popup').on('shown.bs.modal',function(e){
      $(this).find('.inputFocus').focus();
   });
});

</script>  

<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($type_list)) || (empty($employee_name)) || (empty($payment_mode)))
{
  
?>  

 
  /*$('#employee_count').modal({
     backdrop: 'static',
      keyboard: false
        })*/
<?php 

}
}
?>

});
</script>
<script>
$("button[data-number=4]").click(function(){
    $('#employee_count').modal('hide');
   /* $(this).hide();*/
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal-dialog -->
<div id="load_add_emp_type_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_emp_modal_popup" class="modal fade z-index-none m-r-1" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="employee_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($type_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Employee Type is required.</span></p><?php } ?>
           <?php if(empty($employee_name)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Employee Name is required.</span></p><?php } ?>
         <?php if(empty($payment_mode)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Payment Mode is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    </div>

