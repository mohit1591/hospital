
<div class="modal-dialog modal-lg">
     <div class="modal-content">  
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
          </div>
          <div class="modal-body form cmnClassF cf">  
               <table class="brlist-tbl-view">
                    <tr>
                        
                        <td><label> Expense Date </label></td>
                         <td><?php echo date('d-m-Y',strtotime($form_data['expenses_date'])); ?></td>
                         <td><label>  Remarks </label></td>
                         <td><?php echo $form_data['remarks']; ?></td>
                    </tr>
                    <tr>
                        
                        <td><label> Paid Amount </label></td>
                         <td><?php echo $form_data['paid_amount']; ?></td>
                    </tr>
                    <tr>
                        
                        <td><label> Patient Balance </label></td>
                         <td><?php echo $form_data['employee_patient_balance']; ?></td>
                    </tr>
                    <tr>
                        
                        <td><label> Advance </label></td>
                         <td><?php echo $form_data['employee_advance']; ?></td>
                    </tr>
                    <tr>
                        
                         <td><label>Pay Now </label></td>
                         <td><?php echo $form_data['employee_pay_now']; ?> </td>
                    </tr>
                    <tr>
                        
                        <td><label> Balance </label></td>
                         <td><?php echo $form_data['employee_balance']; ?></td>
                    </tr>
                    <tr>
                             
                              <td><label>Payment Mode </label></td>
                              <td> <?php echo $form_data['payment_mode']; ?></td>
                         </tr>
                    <?php if( $form_data['payment_mode']=='cheque'){
                         if(!empty($form_data['cheque_no'])) {?>
                              <tr>
                                   <td><label>Cheque No </label></td>
                                   <td><?php echo $form_data['cheque_no']; ?></td>
                              </tr> 
                         <?php } ?>
                        
                         <?php 
                         if(!empty($form_data['branch_name'])){ ?>
                              <tr>
                                   <td><label>Bank Name </label></td>
                                   <td><?php echo $form_data['branch_name']; ?></td>
                              </tr>
               
                         <?php } ?>
                    <?php }
                    else if($form_data['payment_mode']=='card' || $form_data['payment_mode']=='neft'){
                              if(!empty($form_data['transaction_no'])){
                         ?>
                              <tr>
                                   <td valign="top"><label>Transaction No </label></td>
                                   <td colspan="3"><?php echo $form_data['transaction_no']; ?></td>
                              </tr>
                         <?php }
                         } ?>

                    <tr>
                        
                        <td><label> Employee Type </label></td>
                         <td><?php echo $form_data['emp_type']; ?></td>
                    </tr>
                    <tr>
                         <td><label>Employee Name </label></td>
                         <?php if(!empty($form_data['employee_id'])){ ?>
                              <td><?php echo $form_data['name']; ?></td>
                         <?php }?>
                    </tr>
                           
                    
                 
                        
               
                       
               </table>
          </div>     
          <div class="modal-footer">  
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
     </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->