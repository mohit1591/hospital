<?php  $users_data = $this->session->userdata('auth_users'); 

//print_r($self_purchase_return_collection_list['self_purchase_coll']); die;
?>
<!DOCTYPE html>
<html>
<head>
  <title></title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
    page {
      background: white;
      display: block;
      margin: 1em auto 0;
      margin-bottom: 0.5cm;
    }
    page[size="A4"] {  

      padding: 3em;
      font-size: 13px;
      float: left;
    }
    @page {
      size: auto;   
      margin: 0;  
    }
  </style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

  <page size="A4">
    
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Collection Report</span></td>
      </tr>
      <tr>
        <td style="text-align:center;font-size:13px;padding:1em;">
          <strong>From</strong>
          <span><?php echo $get['start_date']; ?></span>
          <strong>To</strong>
          <span><?php echo $get['end_date']; ?></span>
        </td>
        <td><input type="button" name="button_print" value="Print" id="print" onClick="return my_function();"/></td>
      </tr>
    </table>
    <!-- Branch list start -->


<?php 
$grand_collection_total = 0;
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

            } ?>
<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

  <?php
  $pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');?>

    <!-- Doctor list end -->

  </div>
  <?php 
//print_r($self_ambulance_collection_list); exit;
  if( !empty($self_ambulance_collection_list['self_ambulance_coll']))
  {

    ?>
    <div style="float:left;width:100%;border:1px solid #111;">


      <div style="float:left; width:100%;font-size:13px;">        
        
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
      
            <?php 
            
           if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                ?>
              <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u><?php echo $collection_setting->setting_value; ?></u></div>
              <?php   
              //echo $collection_setting->setting_name;
                
              }
           }

            
            //echo $mode_check;
           ?>
          </div>

         

        

                        <!--- Self Ambulance --->
                         <?php
          if(!empty($self_ambulance_collection_list['self_ambulance_coll']))
          {
            //print_r($self_ambulance_collection_list['self_ambulance_coll']);die;
            //echo "hh";die;
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">Ambulance</span>
            </div>
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_ambulance_collection_list['self_ambulance_coll']);
            foreach($self_ambulance_collection_list['self_ambulance_coll'] as $collection)
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
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
            <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $k; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $net_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $debit_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->mode ?></div>
                <?php
              }
              


            } 
              ?>
              
                </div> 
                <?php
                $k++; 
                $self_total = $self_total+$collection->debit-$refund_amount;
              } 
              ?>

              <?php 
              if($mode_check==1)
              {
              $p_m_k=1;
              
              foreach($self_ambulance_collection_list['self_ambulance_coll_payment_mode'] as $payment_mode_opd){ ?>
              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                  <span style="float:left;"><?php echo $payment_mode_opd->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                </div>
              </div> 
              <?php $p_m_k++;} ?>
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
            }
           
            ?>
                        <!--- Self Ambulance --->

                        </div>
                        <?php 
                      }
                      ?>
                      
            <!--- Self expense --->              
                          
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
         
         <table cellpadding="7" style="width:100%;font-size:10px;font-family: arial;">
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Ambulance Expenses</u></td>
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
              <td width="10%"><?php echo $i; ?></td>
          <td width="15%"><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
              <td width="15%"><?php echo $expenses->vouchar_no; ?>  </td>
              <td width="15%"><?php
              $expenses_type = $expenses->exp_category;
            if($expenses->type>0)
            {
              $expenses_type = $expenses->expenses_type;
            }
              echo $expenses_type; //$expenses->type; ?></td> 
              <td width="15%"><?php echo $expenses->excategory; 
                  if(!empty(trim($expenses->remarks)))
                   {
                      echo ' ('.$expenses->remarks.')';
                   }
              ?></td>
              <td width="15%">
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
              <td width="15%"><?php $total = $total+$expenses->paid_amount; echo number_format($expenses->paid_amount,2); ?>
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

          

         <!-- <div style="float:left;width:100%;padding:4px;text-align:right;">
            
            <div style="float:right;width:20%; font-weight: bold;">
              <span style="float:left;">Total:</span>
              <span style="float:right;"><?php echo number_format($total,2); ?></span>
            </div>
          </div> -->
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
     
       <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                  <span style="float:left;">Grand Total:</span>
                  <span style="float:right;"><?php echo number_format($grand_collection_total-$total,2); ?></span>
                </div>
              </div> 
    </div> 
    </div>
                    </page>

                  </body>
                  </html>
                  <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
                  <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
                  <script>
                    function my_function()
                    {
                      $("#print").hide();
                      window.print();
                    }
                  </script>
                  <style type="text/css" media="print">
                    @page 
                    {
                      size:  auto;   /* auto is the initial value */
                      margin: 0mm;  /* this affects the margin in the printer settings */
                    }

                    html
                    {
                      /*background-color: #FFFFFF;*/ 
                      margin: 0px;  /* this affects the margin on the html before sending to printer */
                    }

                    body
                    {
                      border: solid 0px black ;
                      /* margin: 10mm 15mm 10mm 15mm;  margin you want for the content */
                    }
                  </style>