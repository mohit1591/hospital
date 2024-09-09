<div class="modal-dialog modal-lg">
     <div class="modal-content">  
          <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4><?php echo $page_title; ?></h4> 
          </div>


          <div class="modal-body form cmnClassF cf" >    
            
                              
                    <div class="row m-b-5">
                         <div class="col-md-6">
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Voucher No.</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <span> <?php echo $form_data['vouchar_no']; ?> </span>
                                        
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Expense Date</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <?php echo date('d-m-Y',strtotime($form_data['expenses_date'])); ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                   <strong>Paid To</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                         
                                        <?php echo $form_data['exp_category']; ?>
                                      
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong> Paid Amount</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                        <?php echo $form_data['paid_amount']; ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->

                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Payment Mode</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                          <?php  echo $payment_mode[0]->payment_mode; ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                         </div> <!-- main6 -->


                         <div class="col-md-6">
                            <?php if(!empty($form_data['transaction_no'])){ ?>
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Transaction No.</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                       
                                        <?php echo $form_data['transaction_no']; ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              <?php } ?>
                              <?php if(!empty($form_data['cheque_no']) && $form_data['payment_mode']=='cheque'){ ?>
                              <div class="row m-b-5" id="cheque" >
                                  
                                    <div class="col-md-4">
                                        <strong>Cheque No.</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                                                             
                                        <?php echo $form_data['cheque_no']; ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              <?php } ?>
                               <?php  if(!empty($form_data['cheque_date']) && $form_data['cheque_date']!='0000-00-00 00:00:00' && $form_data['payment_mode']=='cheque'){ ?>
                              <div class="row m-b-5" id="chequeda">
                                  
                                    <div class="col-md-4">
                                        <strong>Cheque Date</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                          <?php echo date('d-m-Y',strtotime($form_data['cheque_date']));  ?>
                                   
                                       
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              <?php } if(!empty($form_data['branch_name'])) { ?>
                              <div class="row m-b-5">
                                   <div class="col-md-4">
                                        <strong>Branch Name</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                       <?php echo $form_data['branch_name']; ?>
                                   </div> <!-- 8 -->
                              </div> <!-- row -->
                              <?php } if($form_data['exp_category']=='advance' || $form_data['exp_category']=='Advance'){ ?>
                                        <?php  if(!empty($form_data['emp_type_id']))
                                        { ?> 
                                             <div class="row m-b-5" id="employeesalary">
                                                  <div class="col-md-4 m-b-5">
                                                       <strong>Employee Type </strong>
                                                  </div>
                                                  <div class="col-md-8">
                                                       <?php echo employee_type_name($form_data['emp_type_id']);?>  
                                                  </div>
                                             </div>
                                        <?php }
                                   } 
                              ?>
                            <?php  if($form_data['exp_category']=='advance' || $form_data['exp_category']=='Advance'){ ?>
                                   <div class="row m-b-5">
                                        <div class="col-md-4">
                                             <strong>Employee</strong>
                                        </div> <!-- 4 -->
                                        <div class="col-md-8">
                                        <?php 
                                             if($form_data['exp_category']=='advance' || $form_data['exp_category']=='Advance'){
                                                  if(!empty($form_data['employee_id'])){ ?>
                                                       <?php echo $form_data['name']; ?>
                                                  <?php }
                                             }
                                        ?>
                                        </div> <!-- 8 -->
                                   </div> <!-- row -->
                              <?php } ?>

                              <div class="row m-b-5">
                                    <div class="col-md-4">
                                        <strong>Remark</strong>
                                   </div> <!-- 4 -->
                                   <div class="col-md-8">
                                       <?php echo $form_data['remarks']; ?>
                                   </div> <!-- 8 -->
                                  

                              </div> <!-- row -->
                         </div> <!-- main6 -->
                         
                    </div> <!-- mainRow -->
                  
               </div>


               
          <div class="modal-footer">  
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
        </div> 
 
     </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->