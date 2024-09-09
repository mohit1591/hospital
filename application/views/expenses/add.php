<?php
$users_data = $this->session->userdata('auth_users');
?>
<div class="modal-dialog">
     <div class="overlay-loader">
          <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    <div class="modal-content"> 
          <form  id="expenses_form" class="form-inline">
               <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>" />
               <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4><?php echo $page_title; ?></h4> 
               </div>
               <div class="modal-body">    
            
                              
                    <div class="row m-b-5">
                         <div class="col-md-6">
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Voucher No.<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <span> <?php echo $form_data['voucher_no']; ?> </span>
                                        <input type="hidden" name="voucher_no" value="<?php echo $form_data['voucher_no']; ?>"/>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Expense Date<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" readonly="readonly" contenteditable="false" class="datepicker m_input_default" name="expenses_date" id="expensesdate" value="<?php echo $form_data['expenses_date']; ?>" />
                                   
                                        <?php if(!empty($form_error)){ echo form_error('expenses_date'); } ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              
                              <div class="row m-b-5">	
                                   <div class="col-md-4">	
                                   <strong>Department<span class="star">*</span></strong>	
                                   </div> <!-- 4 -->	
                                   <div class="col-md-8">	
                                        <select name="department_type" id="department_type" class="pat-select1 m_select_btn" >	
                                             <option value="">Select Department</option>	
                                            <?php  foreach($dept_list as $key=>$dept){?>	
                                             <option value="<?php echo $key;  ?>" <?php if(isset($form_data['department_type']) && $form_data['department_type']==$key){ echo 'selected';}?>><?php echo $dept;?></option>	
                                             <?php }?>	
                                        </select>	
                                   </div> <!-- 8 -->	
                              </div>

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                   <strong>Paid To<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <select name="vendor_id" id="vendor_id" class="pat-select1 m_select_btn" >
                                             <option value="">Select Vendor</option>
                                            <?php  foreach($vendor_list as $vendors){?>
                                             <option value="<?php echo $vendors->id;  ?>" <?php if(isset($form_data['vendor_id']) && $form_data['vendor_id']==$vendors->id){ echo 'selected';}?>><?php echo $vendors->name;?></option>
                                             <?php }?>
                                        </select> 
                                        <?php if(in_array('310',$users_data['permission']['action'])): ?>
                                             <a href="javascript:void(0)" onclick="expense_vendor_modal(2)" class="btn-new"><i class="fa fa-plus"></i> New</a>
                                        <?php endif;?>
                                       <?php if(!empty($form_error)){ echo form_error('expense_category_id'); } ?>
                                      
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                   <strong>Expense Type<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <select name="expense_category_id" id="expense_category_id" class="pat-select1 m_select_btn">
                                             <option value="">Select</option>
                                             <?php
                                                  if(!empty($expense_category_list)){
                                                       foreach($expense_category_list as $expense_category){
                                                            $selected_category = '';
                                                            if($expense_category->id==$form_data['expense_category_id']){
                                                                 $selected_category = 'selected="selected"';
                                                            }
                                                            echo '<option expcate="'.$expense_category->exp_category.'" value="'.$expense_category->id.'" '.$selected_category.'>'.$expense_category->exp_category.'</option>';
                                                       }
                                                  }
                                             ?> 
                                        </select> 
                                        <?php if(in_array('310',$users_data['permission']['action'])): ?>
                                             <a href="javascript:void(0)" onclick="expense_category_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>
                                        <?php endif;?>
                                       <?php if(!empty($form_error)){ echo form_error('expense_category_id'); } ?>
                                      
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong> Paid Amount<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" name="paid_amount" id="paid_amount" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text price_float m_input_default" value="<?php echo $form_data['paid_amount']; ?>" />
                                       
                                        <?php if(!empty($form_error)){ echo form_error('paid_amount'); } ?>
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
                                        <?php if(!empty($form_error)){ echo form_error('payment_mode'); }  ?>
                                      
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                         </div> <!-- main6 -->


                         <div class="col-md-6">
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
                                  <input type="text" name="field_name[]" value="<?php echo $tot_values[0];?>" /><input type="hidden" value="<?php echo $tot_values[2];?>" name="field_id[]" />
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

                           

                              <div class="row m-b-5" id="employeetype" style="display:none;">
                                   <div class="col-md-4 m-b-5">
                                        <strong>Employee Type <span class="star">*</span></strong>
                                   </div>
                                   <div class="col-md-8">
                                        <select id="emp_type_id" name="emp_type_id">
                                             <option value="">Select employee type</option>
                                             <?php if(!empty($type_list)){ 
                                                       foreach($type_list as $types){
                                                            if($form_data['emp_type_id'] == $types->id){
                                                                 $selected = "selected = 'selected'";
                                                            }else{
                                                                 $selected = "";
                                                            }
                                                            echo '<option value="'.$types->id.'" '.$selected.'>'.$types->emp_type.'</option>'; 
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

                                 <div class="row m-b-5" id="employeesalary" style="display:none;">
                                   <div class="col-md-4">
                                        <strong>Employee<span class="star">*</span></strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                         <select name="employee_id" id="emp_id" class="pat-select1" onchange="find_employee_salary(this.value)">
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
                                        <strong>Remarks</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <textarea name="remark" id="remark"><?php echo $form_data['remark']; ?></textarea> 
                                        <?php if(!empty($form_error)){ echo form_error('remark'); } ?>
                                   </div> <!-- 8 -->
                                  

                              </div> <!-- row -->
                              
                              <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong>Payment From</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <input type="text" name="payment_from" id="payment_from" value="<?php echo $form_data['payment_from']; ?>"> 
                                        <?php if(!empty($form_error)){ echo form_error('payment_from'); } ?>
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
                    container: 'body' ,
                    trigger: 'focus',
               });
          </script>
          <script> 

          function payment_function(value,error_field){
               $('#updated_payment_detail').html('');
                    $.ajax({
                         type: "POST",
                         url: "<?php echo base_url('expenses/get_payment_mode_data')?>",
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

          function get_vendor()
          {
               $.ajax({url: "<?php echo base_url(); ?>vendor/vendor_dropdown/"+'2', 
               success: function(result)
               {
               $('#vendor_id').html(result); 
               } 
          });
          }
          $(document).ready(function(){
               var id = $("#payment_mode").val();
               var expcatename =  $('#expense_category_id option:selected').text();
               if(id){
                   get_payment_mode(id); 
               }
               if(expcatename){
                    get_choosen_emp_cat(expcatename);

               }
               var expensesCat = $("#expense_category_id :selected").text();
               if(expensesCat!=''){
                    if(expensesCat=='advance' || expensesCat=='Advance'){
                        $("#paid_amount").attr("readonly","readonly");
                    }else{
                        $("#paid_amount").removeAttr("readonly");
                    }
               }
               
          })
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
          function expense_category_modal(){
               var $modal = $('#load_add_expense_category_modal_popup');
               $modal.load('<?php echo base_url().'expense_category/add/' ?>',
               {
                    //'id1': '1',
                    //'id2': '2'
               },
               function(){
                    $modal.modal('show');
               });
          }
          function isNumberKey(evt) {
               var charCode = (evt.which) ? evt.which : event.keyCode;
               if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
               } else {
                    return true;
               }      
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

          function get_choosen_emp_cat(text){
              var expcatename =  $('#expense_category_id option:selected').text();
              if(expcatename!=''){
                     if(expcatename=='Advance' || expcatename=='advance'){
                          document.getElementById("employeetype").style.display = "block";
                          document.getElementById("employeesalary").style.display = "block";
                          $("#paid_amount").attr("readonly","readonly");

                     
                          // $("#employee_id").val('');
                    
                     
                     }else{
                       
                         document.getElementById("employeesalary").style.display = "none";
                         document.getElementById("employeetype").style.display = "none";
                         $("#emp_id").val('');
                         $("#emp_type_id").val('');
                          $("#paid_amount").removeAttr("readonly");
                     }
               }
          }
          function find_employee_salary(id){
               $("#paid_amount").val('');
               if(id!=''){
                    $.post('<?php echo base_url().'employee_salary/find_employee_salary' ?>',{'emp_id':id},function(result){
                         if(result!=''){
                              $("#paid_amount").val(result);
                         }
                    })
               }


          }
          function get_payment_mode(id){
              
              if(id=='cheque'){
                    document.getElementById("cheque").style.display="block";
                    document.getElementById("chequeda").style.display="block";
                    document.getElementById("card").style.display="none";
                    document.getElementById("branchnames").style.display="block";
                    
                 
              }else if(id=='card' || id=='neft'){
                 
                  document.getElementById("cheque").style.display="none";
                  document.getElementById("chequeda").style.display="none";
                  document.getElementById("card").style.display="block";
                  document.getElementById("branchnames").style.display="none";
                  $("#branchname").val('');
                  $("#chequedate").val('');
                 
              
              }else{
                  document.getElementById("cheque").style.display="none";
                  document.getElementById("chequeda").style.display="none";
                  document.getElementById("card").style.display="none";
                  document.getElementById("branchnames").style.display="none";
                  $("#branchname").val('');
                  $("#chequedate").val('');
                  $("#transactionno").val('');
                 
              }
          }
       
 $(function(){
    $('.datepicker').datepicker({
        format: 'dd-mm-yyyy',
       
         autoclose: true
    });

     $('#cheque_date').datepicker({
        format: 'dd-mm-yyyy',
       
         autoclose: true
    });
});

          
          $("#expenses_form").on("submit", function(event) { 
               event.preventDefault(); 
               $('.overlay-loader').show();
               var ids = $('#data_id').val();
               if(ids!="" && !isNaN(ids)){ 
                    var path = 'edit/'+ids;
                    var msg = 'expense successfully Updated.';
               }else{
                    var path = 'add/';
                    var msg = 'expense successfully Paid.';
               } 
               //alert('ddd');return false;
               $.ajax({
                    url: "<?php echo base_url(); ?>expenses/"+path,
                    type: "post",
                    data: $(this).serialize(),
                     //dataType: "json",
                    success: function(result) {
                         
                         if(result==1){
                              flash_session_msg(msg);
                              $('#load_add_modal_popup').modal('hide');
                              window.location.href='<?php echo base_url('expenses/?status=print');?>';
                         } else{
                              $("#load_add_modal_popup").html(result);
                         }
                         $('.overlay-loader').hide();       
                    }
               });
          }); 

           function expense_vendor_modal(type_expense){
             var $modal = $('#load_add_ven_modal_popup');
               $modal.load('<?php echo base_url().'vendor/add/' ?>'+type_expense,
               {
                    //'id1': '1',
                    //'id2': '2'
               },
               function(){
                    $modal.modal('show');
               });
          }

$(document).ready(function(){
     $('#load_add_ven_modal_popup').on('shown.bs.modal', function(e){
          $(this).find('.inputFocus').focus();
     });
});
$(document).ready(function(){
     $('#load_add_expense_category_modal_popup').on('shown.bs.modal', function(e){
          $(this).find('.inputFocus').focus();
     });
});
</script>

<script type="text/javascript">
$(document).ready(function(){
<?php
if(empty($_POST))
{
if((empty($vendor_list)) || (empty($payment_mode)) || (empty($expense_category_list)))
{
  
?>  

 
  $('#expenses_count').modal({
     backdrop: 'static',
      keyboard: false
        })
<?php 

}
}
?>

});
</script>
<script>
$("button[data-number=4]").click(function(){
    $('#expenses_count').modal('hide');
   /* $(this).hide();*/
});
</script>
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<div id="load_add_emp_type_modal_popup" class="modal fade " role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_emp_modal_popup" class="modal fade z-index-none" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>  
<div id="load_add_ven_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="expenses_count" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content bg-r-border">
            <div class="modal-header bg-theme bg-red"><b><span>Notice</span></b><button type="button" class="close close1" data-number="4" aria-label="Close"><span aria-hidden="true">×</span></button></div>
          <div class="modal-footer  text-l">
            <?php if(empty($vendor_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Vendor is required.</span></p><?php } ?>
           <?php if(empty($payment_mode)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Payment Mode is required.</span></p><?php } ?>
         <?php if(empty($expense_category_list)) {
          ?> <p><i class="fa fa-star" aria-hidden="true"></i><span class="text1">Expense Category is required.</span></p><?php } ?>
          </div>
        </div>
      </div>  
    </div>

