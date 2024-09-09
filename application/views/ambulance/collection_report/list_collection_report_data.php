<?php  $users_data = $this->session->userdata('auth_users'); 
//print_r($_GET);die;
//print_r($self_purchase_return_collection_list['self_purchase_coll']); die;
?>
<!DOCTYPE html>
<html>
<?php 

  $mode_check=0;
            if(!empty($collection_tab_setting))
            {
              foreach ($collection_tab_setting as $collection_setting) 
              {

                if(strcmp(strtolower($collection_setting->setting_name),'paid_amount')=='0')
                {
                  $mode_check=1;
                }
              }

            }
            $grand_collection_total = 0;
?>
<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

  <?php
  $pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');?>
  </div>
  <?php
  //print_r($self_ambulance_collection_list['self_ambulance_coll']); exit;
  if(!empty($self_ambulance_collection_list['self_ambulance_coll']))
  {
//echo "<pre>";print_r($self_ambulance_collection_list['self_ambulance_coll']); die;
    ?>
    <div style="float:left;width:100%;border:1px solid #111;">
      <div style="float:left; width:100%;font-size:10px;">        
        <table cellpadding="4"pich style="width:100%;font-size:10px;font-family: arial;">
          <thead>
        <tr>
            <?php 
            
           if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                ?>
              <td style="border-bottom: 1px solid black;"><?php echo $collection_setting->setting_value; ?></td>
              <?php   
              //echo $collection_setting->setting_name;  ye heading hai 
                
              }
           }
        ?>
           </tr>
           </thead>
         

       

       <!--ot collection -->
       <!--- Self Ambulance -->

        <?php
          if(!empty($self_ambulance_collection_list['self_ambulance_coll']))
          {
           ?>
           
            <tr>
              <td style="padding:5px 0"><u>Ambulance</u></td>
            </tr>

            
            <?php 
           // print_r($self_ambulance_collection_list['self_ambulance_coll']);die;
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_ambulance_collection_list['self_ambulance_coll']);
            foreach($self_ambulance_collection_list['self_ambulance_coll'] as $collection)
            { 
            
            ?>
            <tr>
           <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
                if(!empty($collection->refund_amount) && $collection->refund_amount > 0)
                {
                    $refund_amount=$collection->refund_amount;
                }
                else{
                   $refund_amount=0.00;
                }
                $net_amount=number_format($collection->net_amount,2); //$refund_amount
                 $debit_amount=number_format($collection->debit,2); //$refund_amount
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0'){?><td width="10" align="center"><?php echo $k; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0'){ ?><td style="widows: 20px;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0'){ ?><td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0'){ ?><td><?php echo $collection->patient_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0'){ ?><td><?php echo date('d-m-Y h:i A', strtotime($collection->created_date)); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0'){ ?><td><?php echo $collection->doctor_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0'){ ?><td><?php echo $collection->doctor_hospital_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0'){ ?><td><?php echo $collection->mobile_no; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0'){ ?><td><?php echo $collection->panel_type; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0'){ ?><td><?php echo $collection->booking_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0'){?><td><?php echo $net_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0'){ ?><td><?php echo $collection->discount_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0'){ ?><td><?php echo $net_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0'){ ?><td><?php echo $debit_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0'){ ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0'){ ?><td><?php echo $collection->mode; ?></td><?php }              
              

              } ?>
              
            </tr>
           
        <?php $k++;
            $self_total = $self_total+$collection->debit; //-$collection->refund_amount
         } ?>


           </tbody>
          </table>

        

          <footer style="float:left;width:100%">
            
              <?php 
              if($mode_check==1)
              {
              $p_m_k=1;
              
              foreach($self_ambulance_collection_list['self_ambulance_coll_payment_mode'] as $payment_mode_opd){ if(!empty($_GET['payment_mode']) && ($_GET['payment_mode']== $payment_mode_opd->pay_mode)){?>
              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                  <span style="float:left;"><?php echo $payment_mode_opd->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </div>
              </div> 
              <?php $p_m_k++;} elseif(empty($_GET['payment_mode'])) {?>
               <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                  <span style="float:left;"><?php echo $payment_mode_opd->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </div>
              </div> 
              <?php $p_m_k++;
              } } ?>
              
            
              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                  <span style="float:left;">Total:</span>
                  <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                </div>
              </div> 
              <!-- </div> -->
              <?php 
            }
            $grand_collection_total = $grand_collection_total+$self_total;
            }?>
              
           </footer>
    <?php 
     //$grand_collection_total = $grand_collection_total+$self_total;

            ?>
       <!--- Self Ambulance -->
        
              </div>
          </div>
                <?php 
              }
              ?>
      
      
     <div style="float:left;width:100%;border:1px solid #111;">


      <div style="float:left; width:100%;font-size:13px;">        
        
         
          <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
          <thead>
        <tr>
          
              <td style="border-bottom: 1px solid black;width:10%;">S.No.</td>
              <td style="border-bottom: 1px solid black;width:15%;">Date</td>
              <td style="border-bottom: 1px solid black;width:15%;">Voucher No.</td>
              <td style="border-bottom: 1px solid black;width:15%;">Exp. Type</td>
              <td style="border-bottom: 1px solid black;width:15%;">Exp. Category</td>
              <td style="border-bottom: 1px solid black;width:15%;">Payment Mode</td>
              <td style="border-bottom: 1px solid black;width:15%;">Amount</td>
             
           </tr>
           </thead>
         </table>
         

     <?php //echo "<pre>"; print_r($all_expense_list); exit;
     if(!empty($all_expense_list['expense_list']))
     {
         ?>
         
         <table cellpadding="4" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td colspan="7" style="padding:5px 0"><u>Ambulance Expenses</u></td>
            </tr>

         
         <?php 
        $i=1;
        $total = 0;
        $cash_total = 0;
        $cheque_total = 0;
        $neft_total = 0;
        $card_total = 0;
        
        foreach($all_expense_list['expense_list'] as $expenses)
        {
          ?> 
          <tr>
              <td style="width:10%;"><?php echo $i; ?></td>
          <td style="width:15%;"><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td style="width:15%;"><?php echo $expenses->vouchar_no; ?>  </td>
              <td style="width:15%;"><?php 
              $expenses_type = $expenses->exp_category;
            if($expenses->type>0)
            {
              $expenses_type = $expenses->expenses_type;
            }
              echo $expenses_type; 
              //echo $expenses->type; ?></td> 
              <td style="width:15%;"><?php echo $expenses->excategory; 
                  if(!empty(trim($expenses->remarks)))
                   {
                      echo ' ('.$expenses->remarks.')';
                   }
              ?></td>
              <td style="width:15%;">
              <?php 
                if(is_numeric($expenses->payment_mode))
                {
                  echo $mode[$expenses->payment_mode]; 
                }
                else
                {
                  echo ucwords(strtolower($expenses->payment_mode)); 
                }
              ?>      
              </td>
              <td style="width:15%;"><?php $total = $total+$expenses->paid_amount; echo number_format($expenses->paid_amount,2); ?>
            </td> 
            </tr>
  <?php
      $i++;
      }
      ?>
      
      </tbody>
          </table>
      
      <?php 


      $p_amount=0;  
      foreach($all_expense_list['expense_payment_mode'] as $payment_mode) 
      {
        ?>
          <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
              <span style="float:left;"><?php echo $payment_mode->payment_mode; ?>:</span>
              <span style="float:right;"><?php echo number_format($payment_mode->paid_total_amount,2); ?></span>
            </div>
          </div> 

      <?php 
      $p_amount = $p_amount+$payment_mode->paid_total_amount;
      } 
      ?>    

          

          
          <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                  <span style="float:left;">Total:</span>
                  <span style="float:right;"><?php echo number_format($total,2); ?></span>
                </div>
              </div> 

        <?php 
      
     }
     else
     {
      ?>
       <div style="float:left;width:100%;">
            <div style="float:left;width:100%;padding:1px 4px;text-indent:15px;"><div style="text-align:center;color:#990000;">Record not found</div></div>
       </div> 
      <?php
     }
     ?>    
     
     
     <!-- over all collection -->

       <!--<footer style="float:right;width:100%">
        <div style="float: right;text-align: right;padding-right: 10px;">
          <table style="font-size:10px;text-align: right;">
            <tr>
              <td  style="float:right;border-top:1px solid black;" >
                <strong style="float:right;padding-right:1rem;">Grand Total:</strong>
                <span style="float:right;"><?php echo number_format($grand_collection_total-$total,2);?></span>
              </td>
            </tr>
          </table>
        </div>

      </footer>-->
      
                          
                  <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                  <span style="float:left;">Grand Total:</span>
                  <span style="float:right;"><?php echo number_format($grand_collection_total-$total,2); ?></span>
                </div>
              </div> 
                          
       <!-- end of overall collection -->
    </div> 
    </div>
      </html>