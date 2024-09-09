<?php  $users_data = $this->session->userdata('auth_users'); ?>
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
//print '<pre>'; print_r($branch_collection_list) ; 
if(!empty($branch_collection_list['opd_collection_list']) || !empty($branch_vaccination_collection_list['vaccination_collection']) || !empty($branch_medicine_collection_list['medicine_collection_list']) || !empty($branch_billing_collection_list['billing_array']) || !empty($pathology_branch_collection_list['pathalogy_collection']) || !empty($branch_ipd_collection_list['ipd_collection_list']) || !empty($branch_ot_collection_list['ot_collection']) || !empty($blood_bank_branch_collection_list['blood_bank_collection']))
  { ?>
<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

  <?php
  $pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');
  if(!empty($branch_collection_list['opd_collection_list']) || !empty($branch_vaccination_collection_list) || !empty($branch_medicine_collection_list['medicine_collection_list']) || !empty($branch_billing_collection_list['billing_array']) || !empty($pathology_branch_collection_list['pathalogy_collection']) || !empty($branch_ipd_collection_list['ipd_collection_list']) || !empty($branch_ot_collection_list['ot_collection']) || !empty($blood_bank_branch_collection_list['blood_bank_collection']))
  { 
    $branch_names = []; 
          foreach($branch_collection_list['opd_collection_list'] as $names)  //opd
          {
            $branch_names[] = $names->branch_name;
          }

           foreach($branch_vaccination_collection_list['vaccination_collection'] as $names) //vaccine
           {
            $branch_names[] = $names->branch_name;
           }
           foreach($branch_medicine_collection_list['medicine_collection_list'] as $names) //medicine
           {
            $branch_names[] = $names->branch_name;
           }

           foreach($branch_billing_collection_list['billing_array'] as $names) //billing
           {
            $branch_names[] = $names->branch_name;
           }

           foreach($branch_ot_collection_list['ot_collection'] as $names) //ot
           {
            $branch_names[] = $names->branch_name;
           }

           foreach($blood_bank_branch_collection_list['blood_bank_collection'] as $names) //billing
           {
            $branch_names[] = $names->branch_name;
           }

           foreach($pathology_branch_collection_list['pathalogy_collection'] as $names) //pathology
           {
            $branch_names[] = $names->branch_name;
           }

           foreach($branch_ipd_collection_list['ipd_collection_list'] as $names) //IPD
           {
            $branch_names[] = $names->branch_name;
           }
           
           $branch_names = array_unique($branch_names);  

           ?>
           <!-- <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;"> -->
           <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">

           <?php 
           if(!empty($collection_tab_setting))
           {
              foreach ($collection_tab_setting as $collection_setting) 
              {
                  ?>
                  <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u><?php echo $collection_setting->setting_value; ?></u></div>
                  <?php 
              }
           }
           ?>
           
            </div> 
            <div style="float:left;width:100%;font-size:13px;"> 

              <?php
              if(!empty($branch_names))
              {
                foreach($branch_names as $names)
                {
                  ?>
                  <div style="float:left;width:100%;font-weight:600;padding:4px;">
                    <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
                  </div>  
                  <?php if(!empty($branch_collection_list['opd_collection_list'])) { ?>
                  <div style="float:left;width:100%;font-weight:600;padding:4px;">
                    <span style="border-bottom:1px solid #111;">OPD</span>
                  </div>  
                  <?php 

                  $i = 1;   
                  $branch_total = 0;  
                  $branch_total1=0;
                  $count_branch = count($branch_collection_list['opd_collection_list']);
                  $n_bnc = '';
                  foreach($branch_collection_list['opd_collection_list'] as $branchs)
                  { 



        if($names == $branchs->branch_name) 
        {
          ?>    
          <div style="float:left; width: 100%;display:flex;justify-content:space-around;">

                      <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix)){ echo $branchs->reciept_prefix.$branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
            </div>    
              <?php
              $i++;
              $branch_total = $branch_total+$branchs->debit;
              }
            }
            ?>
            <?php 
                  if($mode_check==1)
                  {
                  $p_m_k=1;
                  $p_amount=0;  
                  foreach($branch_collection_list['payment_mode_list'] as $payment_mode) 
                  {  

                    ?>

                    <div style="float:left;width:100%;padding:4px;text-align:right;">
                      <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        <span style="float:left;"><?php echo $payment_mode->mode; ?></span>
                        <span style="float:right;">
                          <?php echo number_format($payment_mode->to_debit,2); ?>

                        </span>
                      </div>

                    </div>
                    <?php $p_m_k++; } ?>

                    <div style="float:left;width:100%;padding:4px;text-align:right;">
                      <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                        <span style="float:left;">Total:</span>
                        <span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
                      </div>

                    </div>

                    <?php }  }


                    if(!empty($branch_billing_collection_list['billing_array'])){ ?>
                    <div style="float:left;width:100%;font-weight:600;padding:4px;">
                      <span style="border-bottom:1px solid #111;">Billing</span>
                    </div>
                    <?php 
                    $x = 1;   
                    $branch_bill_total = 0; 
                    $branch_bill_total1=0;
                    $count_bill_branch = count($branch_billing_collection_list['billing_array']);
                    $n_bnc = '';
                    foreach($branch_billing_collection_list['billing_array'] as $bill_branchs)
                    {   

                      if($names == $bill_branchs->branch_name)  
                      {

                        ?>    
                        <div style="float:left; width: 100%;display:flex;justify-content:space-around;">
                          
                          <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $x; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($bill_branchs->reciept_prefix) || !empty($bill_branchs->reciept_suffix)){ echo $bill_branchs->reciept_prefix.$bill_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($bill_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($bill_branchs->balance=='1.00' || $bill_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($bill_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $bill_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
             </div>   
                    <?php
                        $i++;
                        $branch_bill_total = $branch_bill_total+$bill_branchs->debit;

                        /* new data for payment mode */

    


                        /* new data for payment mode */
                      }
                    }

                    ?>

                    <?php 
                    if($mode_check==1)
                    {
                    $p_m_k=1;
                    $p_amount=0;  
                    foreach($branch_billing_collection_list['billing_payment_mode_array'] as $payment_mode_bill) 
                    {  

                      ?>

                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                          <span style="float:left;"><?php echo $payment_mode_bill->mode; ?></span>
                          <span style="float:right;">
                            <?php echo number_format($payment_mode_bill->tot_debit,2); ?>

                          </span>

                        </div>

                      </div>
                      <?php $p_m_k++; } ?>




                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                          <span style="float:left;">Total:</span>
                          <span style="float:right;"><?php echo number_format($branch_bill_total,2); ?></span>
                        </div>
                      </div>
                      <?php 

                    }
                  }
//ipd start
                    if(!empty($branch_ipd_collection_list['ipd_collection_list'])){ ?>
                    <div style="float:left;width:100%;font-weight:600;padding:4px;">
                      <span style="border-bottom:1px solid #111;">IPD</span>
                    </div>
                    <?php 
                    $x = 1;   
                    $branch_ipd_total = 0;  
                    $branch_ipd_total1=0;
                    $count_ipd_branch = count($branch_ipd_collection_list['ipd_collection_list']);
                    $n_bnc = '';
                    foreach($branch_ipd_collection_list['ipd_collection_list'] as $ipd_branchs)
                    {   
                      if($names == $ipd_branchs->branch_name) 
                      {

                        ?>    
                        <div style="float:left; width: 100%;display:flex;justify-content:space-around;">
                          
                          <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $x; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($ipd_branchs->reciept_prefix) || !empty($ipd_branchs->reciept_suffix)){ echo $ipd_branchs->reciept_prefix.$ipd_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ipd_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($ipd_branchs->balance=='1.00' || $ipd_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ipd_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ipd_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
                        
                          </div>    
                          <?php
                          $i++;
                          $branch_ipd_total = $branch_ipd_total+$ipd_branchs->debit;

                        }
                      }
                      ?>
                      <?php 
                      if($mode_check==1)
                      {
                      $p_m_k=1;
                      $p_amount=0;  
                      foreach($branch_ipd_collection_list['ipd_payment_mode_list'] as $payment_mode_ipd) 
                      {  
                        ?>

                        <div style="float:left;width:100%;padding:4px;text-align:right;">
                          <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                            

                            <span style="float:left;"><?php echo $payment_mode_ipd->mode; ?></span>
                            <span style="float:right;"><?php echo number_format($payment_mode_ipd->tot_debit,2); ?></span>

                          </div>

                        </div>
                        <?php }?>

                        <div style="float:left;width:100%;padding:4px;text-align:right;">
                          <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                            <span style="float:left;">Total:</span>
                            <span style="float:right;"><?php echo number_format($branch_ipd_total,2); ?></span>
                          </div>
                        </div>

                        <?php 

                      }
                    }
//ipd end

                      if(!empty($branch_medicine_collection_list['medicine_collection_list'])){ ?>
                      <div style="float:left;width:100%;font-weight:600;padding:4px;">
                        <span style="border-bottom:1px solid #111;">Medicine Sale</span>
                      </div>
                      <?php 
                      $c = 1;   
                      $branch_medi_total = 0; 
                      $branch_medi_total1=0;
                      $count_medi_branch = count($branch_medicine_collection_list);
                      $n_bnc = '';
                      $i=1;
                      foreach($branch_medicine_collection_list['medicine_collection_list'] as $medi_branchs)
                      {   
                        if($names == $medi_branchs->branch_name)  
                        {
                          ?>    
                          <div style="float:left; width: 100%;display:flex;justify-content:space-around;">

                          <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix)){ echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
                            
                          </div>    
                          <?php
                          $i++;
                          $branch_medi_total = $branch_medi_total+$medi_branchs->debit;

                          

                        }
                      }
                      ?>
                      <?php 
                      if($mode_check==1)
                      {
                      $p_m_k=1;
                      $p_amount=0;  
                      foreach($branch_medicine_collection_list['medicine_payment_mode_array'] as $payment_mode_med) 
                      {  
    
                        ?>

                        <div style="float:left;width:100%;padding:4px;text-align:right;">
                          <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        

                            <span style="float:left;"><?php echo $payment_mode_med->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_med->tot_debit,2); ?></span>
                          </div>

                        </div>
                        <?php $p_m_k++; }?>

                        <div style="float:left;width:100%;padding:4px;text-align:right;">
                          <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                            <span style="float:left;">Total:</span>
                            <span style="float:right;"><?php echo number_format($branch_medi_total,2); ?></span>
                          </div>
                        </div>
                        <?php } 


                      }
                        ?>


                        <?php 
//print_r($branch_vaccination_collection_list); exit;
                        if(!empty($branch_vaccination_collection_list['vaccination_collection'])){ ?>
                        <div style="float:left;width:100%;font-weight:600;padding:4px;">
                          <span style="border-bottom:1px solid #111;">Vaccination</span>
                        </div>
                        <?php 
                        $i = 1; 
                        $c=1; 
                        $branch_vaccination_total = 0;  
                        $branch_vaccination_total1 = 0; 
                        $count_vaccination_branch = count($branch_vaccination_collection_list['vaccination_collection']);
                        $n_bnc = '';
                        foreach($branch_vaccination_collection_list['vaccination_collection'] as $medi_branchs)
                        {   
                          if($names == $medi_branchs->branch_name)  
                          {
                            ?>    
                            <div style="float:left; width: 100%;display:flex;justify-content:space-around;">

                            <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix)){ echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $medi_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
                              
                            </div>    
                            <?php
                            $i++;
                            $branch_vaccination_total = $branch_vaccination_total+$medi_branchs->debit;

                            


                          }
                        }
                        ?>
                        <?php 
                        if($mode_check==1)
                        {
                        $p_m_k=1;
                        $p_amount=0;  
                        foreach($branch_vaccination_collection_list['vaccination_payment_mode_collection'] as $payment_mode_vaccine) 
                        {  

                          ?>

                          <div style="float:left;width:100%;padding:4px;text-align:right;">
                            <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">

                            <span style="float:left;"><?php echo $payment_mode_vaccine->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_vaccine->tot_debit,2); ?></span>
                        
                            </div>

                          </div>
                          <?php $p_m_k++; }?> 
                          <div style="float:left;width:100%;padding:4px;text-align:right;">
                            <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                              <span style="float:left;">Total:</span>
                              <span style="float:right;"><?php echo number_format($branch_vaccination_total,2); ?></span>


                            </div>
                          </div>
                          <?php } } ?>




                          


      <!--ot  report -->

      <?php
      if(!empty($branch_ot_collection_list['ot_collection'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">OT </span>
      </div>
      <?php 
      $i = 1; 
      $c = 1;   
      $branch_ot_total = 0; 
      $branch_ot_total1=0;
      $count_ot_branch = count($branch_ot_collection_list['ot_collection']);
      $n_bnc = '';
      foreach($branch_ot_collection_list['ot_collection'] as $ot_branchs)
      {   
        if($names == $ot_branchs->branch_name)  
        {
          ?>    
          <div style="float:left; width: 100%;display:flex;justify-content:space-around;">
              
               <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($ot_branchs->reciept_prefix) || !empty($ot_branchs->reciept_suffix)){ echo $ot_branchs->reciept_prefix.$ot_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ot_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($ot_branchs->balance=='1.00' || $ot_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ot_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $ot_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>

           
          </div>    
          <?php
          $i++;
          $branch_ot_total = $branch_ot_total+$ot_branchs->debit;

          
        }
      }
      ?>
      <?php 
      if($mode_check==1)
      {
      $p_m_k=1;
      $p_amount=0;  
      foreach($branch_ot_collection_list['ot_collection_payment_mode'] as $payment_mode_ot) 
      {  

        ?>

        <div style="float:left;width:100%;padding:4px;text-align:right;">
          <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        
             <span style="float:left;"><?php echo $payment_mode_ot->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_ot->tot_debit,2); ?></span>
          </div>

        </div>
        <?php $p_m_k++; } ?> 

        <div style="float:left;width:100%;padding:4px;text-align:right;">
          <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
            <span style="float:left;">Total:</span>
            <span style="float:right;"><?php echo number_format($branch_ot_total,2); ?></span>
          </div>
        </div>
        <?php } } ?>

        <!--ot  report -->


        <!--blood bank  report -->

        <?php 
        if(!empty($blood_bank_branch_collection_list['blood_bank_collection'])){ ?>
        <div style="float:left;width:100%;font-weight:600;padding:4px;">
          <span style="border-bottom:1px solid #111;">Blood Bank </span>
        </div>
        <?php 
        $i = 1; 
        $c = 1;   
        $branch_blood_bank_total = 0; 
        $branch_blood_bank_total1=0;
        $count_blood_bank_branch = count($blood_bank_branch_collection_list['blood_bank_collection']);
        $n_bnc = '';
        foreach($blood_bank_branch_collection_list['blood_bank_collection'] as $blood_bank_branchs)
        {   
          if($names == $blood_bank_branchs->branch_name)  
          {
            ?>    
            <div style="float:left; width: 100%;display:flex;justify-content:space-around;">

            <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($blood_bank_branchs->reciept_prefix) || !empty($blood_bank_branchs->reciept_suffix)){ echo $blood_bank_branchs->reciept_prefix.$blood_bank_branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($blood_bank_branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($blood_bank_branchs->balance=='1.00' || $blood_bank_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($blood_bank_branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $blood_bank_branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
              
            </div>    
            <?php
            $i++;
            $branch_blood_bank_total = $branch_blood_bank_total+$blood_bank_branchs->debit;

          }
        }
        ?>
        <?php 
        if($mode_check==1)
        {
        $p_m_k=1;
        $p_amount=0;  
    
        foreach($blood_bank_branch_collection_list['blood_bank_coll_payment_mode'] as $payment_mode_blood_bank) 
        {  

          ?>

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        <span style="float:left;">
              
              <span style="float:left;"><?php echo $payment_mode_blood_bank->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_blood_bank->tot_debit,2); ?></span>
            </div>

          </div>
          <?php $p_m_k++; } ?> 

          <div style="float:left;width:100%;padding:4px;text-align:right;">
            <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
              <span style="float:left;">Total:</span>
              <span style="float:right;"><?php echo number_format($branch_blood_bank_total,2); ?></span>
            </div>
          </div>
          <?php } } ?>

          <!--blood bank  report -->



          <!-- Pathology collection report -->
          <?php

          if(!empty($pathology_branch_collection_list['pathalogy_collection']))
          {

            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;">
              <span style="border-bottom:1px solid #111;">Pathology </span>
            </div>  
            <?php 

            $i = 1;   
            $branch_total = 0;  
            $branch_total1=0;
            $count_branch = count($pathology_branch_collection_list['pathalogy_collection']);
            $n_bnc = '';
            foreach($pathology_branch_collection_list['pathalogy_collection'] as $branchs)
            {   
              if($names == $branchs->branch_name) 
              {
                ?>
                <div style="float:left;width:100%;">
                  <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $i; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix)){ echo $branchs->reciept_prefix.$branchs->reciept_suffix; } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->patient_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->patient_code; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->doctor_name; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->doctor_hospital_name; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->mobile_no; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->panel_type; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->booking_code; ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->total_amount; ?></div>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->discount_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance-1,2); } ?></div>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->mode ?></div>
                <?php
              }
              


            } 
              ?>
                  
                </div>        
                <?php
                $i++;
                $branch_total = $branch_total+$branchs->debit;

              


              }
            }
            ?>
            <?php 
            if($mode_check==1)
            {
            $p_m_k=1;
            $p_amount_path=0; 
            foreach($pathology_branch_collection_list['pathalogy_collection_payment_mode'] as $payment_mode_path) 
            {  
      
              ?>

              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        
                  <span style="float:left;"><?php echo $payment_mode_path->mode; ?></span>
                  <span style="float:right;"><?php echo number_format($payment_mode_path->tot_debit,2); ?></span>

                       
                </div>

              </div>
              <?php $p_m_k++; } ?> 

              <div style="float:left;width:100%;padding:4px;text-align:right;">
                <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                  <span style="float:left;">Total:</span>
                  <span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
                </div>
              </div><?php

            }
          }
            ?>
            <!-- Pathology collection end -->



            <?php
          }
        }

        ?>     
      </div>
      <?php  
    }
    ?>
    
    <!-- Branch list end -->
    <!-- Doctor list start -->
    <?php

    ?>

    <!-- Doctor list end -->

  </div>
  <?php }
  if(!empty($self_opd_collection_list['self_opd_coll']) || !empty($self_billing_collection_list['self_bill_coll']) || !empty($self_medicine_collection_list['med_coll']) || !empty($self_ipd_collection_list['ipd_coll']) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list['path_coll']) || !empty($self_ot_collection_list['self_ot_coll']) || !empty($self_vaccination_collection_list['vaccine_coll']) || !empty($self_blood_bank_collection_list['self_blood_bank_collection']))
  {

    ?>
    <div style="float:left;width:100%;border:1px solid #111;">


      <div style="float:left; width:100%;font-size:13px;">        
        
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
      <!-- <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>S.No.</u></div>

      <?php //if(in_array('218',$users_data['permission']['section'])){ ?><div style="float:left;width:15%;font-weight:600;padding:4px;">Reciept No.</div> <?php // } ?>

        <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Date</u></div>
        <div style="float:left;width:20%;font-weight:600;padding:4px;"><u>Patient Name</u></div> 
        
        <div style="float:left;width:15%;font-weight:600;padding:4px;"><u>Doctor Name</u></div>
        <div style="float:left;width:10%;font-weight:600;padding:4px;"><u>Payment Mode</u></div>
        <div style="float:right;width:15%;font-weight:600;padding:4px;text-align:right;"><u>Amount</u></div> -->

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

          <div style="float:left;width:100%;font-weight:600;padding:4px;">
            <span style="border-bottom:1px solid #111;">Branch : Self</span>
          </div> 


          <?php
          if(!empty($self_opd_collection_list['self_opd_coll']))
          {
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">OPD</span>
            </div>
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_opd_collection_list['self_opd_coll']);
            foreach($self_opd_collection_list['self_opd_coll'] as $collection)
            { 
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
                
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

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
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
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
                $self_total = $self_total+$collection->debit;
              } 
              ?>

              <?php 
              if($mode_check==1)
              {
              $p_m_k=1;
              
              foreach($self_opd_collection_list['self_opd_coll_payment_mode'] as $payment_mode_opd){ ?>
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
            }
            //end self opd collection start //
            ?> 

            <!-- opd collection -->


            <!--ot colllection -->

            <?php

            if(!empty($self_ot_collection_list['self_ot_coll']))
            {
              ?>
              <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
                <span style="border-bottom:1px solid #111;">OT</span>
              </div>
              <?php 
              $k = 1 ;
              $self_total = 0;
              $self_counter = count($self_ot_collection_list['self_ot_coll']);
                      //echo "<pre>";print_r($self_collection_list);
              foreach($self_ot_collection_list['self_ot_coll'] as $collection)
              { 
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
                <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

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
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
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
                  $self_total = $self_total+$collection->debit;
                } 
                ?>
                <?php 
                if($mode_check==1)
                {
                  $p_m_k=1;
                foreach($self_ot_collection_list['self_ot_coll_payment_mode'] as $self_payment_mode) { ?>
                <div style="float:left;width:100%;padding:4px;text-align:right;">
                  <div style="float:right;width:150px; font-weight: bold;<?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                    <span style="float:left;"><?php echo $self_payment_mode->mode; ?></span>
                    <span style="float:right;"><?php echo number_format($self_payment_mode->tot_debit,2); ?></span>
                  </div>
                </div> 
                <?php $p_m_k++;}?>


                <div style="float:left;width:100%;padding:4px;text-align:right;">
                  <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                    <span style="float:left;">Total:</span>
                    <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </div> 
                <!-- </div> -->
                <?php 
              } }
              ?> 

              <!--ot collection -->

              <!--blood bank colllection -->

              <?php

              if(!empty($self_blood_bank_collection_list['self_blood_bank_collection']))
              {
                ?>
                <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
                  <span style="border-bottom:1px solid #111;">Blood Bank</span>
                </div>
                <?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_blood_bank_collection_list['self_blood_bank_collection']);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_blood_bank_collection_list['self_blood_bank_collection'] as $collection)
                { 
                  ?>
                  <div style="float:left;width:100%;">

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
                          <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

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
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->net_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->debit; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
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
                    $self_total = $self_total+$collection->debit;
                  } 
                  ?>
                  <?php 
                  if($mode_check==1)
                    {
                      $p_m_k=1;
                  foreach($self_blood_bank_collection_list['self_blood_bank_coll_payment_mode'] as $self_payment_mode) {?>
                  <div style="float:left;width:100%;padding:4px;text-align:right;">
                    <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                      <span style="float:left;"><?php echo $self_payment_mode->mode; ?></span>
                      <span style="float:right;"><?php echo number_format($self_payment_mode->tot_debit,2); ?></span>
                    </div>
                  </div> 
                  <?php $p_m_k++; }?>


                  <div style="float:left;width:100%;padding:4px;text-align:right;">
                    <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                      <span style="float:left;">Total:</span>
                      <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                    </div>
                  </div> 
                  <!-- </div> -->
                  <?php 
                }
              }
                ?> 

                <!--BLOOD bank collection -->

          <?php
  //echo "<pre>";print_r($self_ipd_collection_list);
          if(!empty($self_ipd_collection_list['ipd_coll']))
          {
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">IPD</span>
            </div>
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_ipd_collection_list['ipd_coll']);

            foreach($self_ipd_collection_list['ipd_coll'] as $collection)
            { 
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
                <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

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
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->net_amount; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->debit; ?></div>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
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
                      $self_total = $self_total+$collection->debit;
                    } 
                    ?>
                    <?php 
                    if($mode_check==1)
                    {
                      $p_m_k=1;
                    foreach($self_ipd_collection_list['ipd_coll_payment_mode'] as $ipd_pay_mode)
                    {?>

                    <div style="float:left;width:100%;padding:4px;text-align:right;">
                      <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                        <span style="float:left;"><?php echo $ipd_pay_mode->mode; ?></span>
                        <span style="float:right;"><?php echo number_format($ipd_pay_mode->tot_debit,2); ?></span>
                      </div>
                    </div> 
                    <?php $p_m_k++; } ?>
                    <div style="float:left;width:100%;padding:4px;text-align:right;">
                      <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                        <span style="float:left;">Total:</span>
                        <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                      </div>
                    </div> 
                    <!-- </div> -->
                    <?php 
                  }
                }
                  ?> 

                  <!-- ipd end -->
                  <!-- self opd billing collection -->
                <?php
                  if(!empty($self_billing_collection_list['self_bill_coll']))
                  {

                    ?>
                    <div style="float:left;width:100%;font-weight:600;padding:4px;">
                      <span style="border-bottom:1px solid #111;">Billing</span>
                    </div>
                    <?php

                    $l = 1 ;
                    $self_billing_total = 0;
                    $self_billing_counter = count($self_billing_collection_list['self_bill_coll']);
                //echo "<pre>";print_r($self_collection_list);
                    foreach($self_billing_collection_list['self_bill_coll'] as $billing_collection)
                    { 
                      ?>
                      <div style="float:left;width:100%;display:flex;justify-content:space-around;">
                      <?php   
                      foreach ($collection_tab_setting as $tab_value) 
                      { 
                        if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $l; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($billing_collection->reciept_prefix) || !empty($billing_collection->reciept_suffix)){ echo $billing_collection->reciept_prefix.$billing_collection->reciept_suffix; } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->patient_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->patient_code; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($billing_collection->created_date)); ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->doctor_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->doctor_hospital_name; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->mobile_no; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->panel_type; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->booking_code; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                          ?>
                          <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->total_amount; ?></div>

                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->discount_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->net_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->debit; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($billing_collection->balance=='1.00' || $billing_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($billing_collection->balance-1,2); } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $billing_collection->mode ?></div>
                          <?php
                        }
                        


                      } 
                        ?>
                        </div> 
                        <?php
                        $l++; 
                        $self_billing_total = $self_billing_total+$billing_collection->debit;
                      } 
                      ?>
                      <?php 
                      if($mode_check==1)
                      {
                      $p_m_k=1;
                      foreach($self_billing_collection_list['self_bill_coll_payment_mode'] as $billing_payment_mode){ ?>
                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                          <span style="float:left;"><?php echo $billing_payment_mode->mode; ?></span>
                          <span style="float:right;"><?php echo number_format($billing_payment_mode->tot_debit,2); ?></span>
                        </div>
                      </div> 
                      <?php $p_m_k++; } ?>
                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                          <span style="float:left;">Total:</span>
                          <span style="float:right;"><?php echo number_format($self_billing_total,2); ?></span>
                        </div>
                      </div> 
                      <!-- </div> -->
                      <?php

                    } 
                  }
                    ?> 
                     <!-- end self opd billing collection -->
                      <!-- self medicine collection -->
                    <?php
                    if(!empty($self_medicine_collection_list['med_coll']))
                      { ?>
                    <div style="float:left;width:100%;font-weight:600;padding:4px;">
                      <span style="border-bottom:1px solid #111;">Medicine Sale</span>
                    </div>
                    <?php
                    $m = 1 ;
                    $self_med_total = 0;
                    $self_med_counter = count($self_medicine_collection_list['med_coll']);
                //echo "<pre>";print_r($self_collection_list);
                    foreach($self_medicine_collection_list['med_coll'] as $med_collection)
                    { 

                      ?>
                      <div style="float:left;width:100%;display:flex;justify-content:space-around;">
                        <?php   
                      foreach ($collection_tab_setting as $tab_value) 
                      { 
                        if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $m; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($med_collection->reciept_prefix) || !empty($med_collection->reciept_suffix)){ echo $med_collection->reciept_prefix.$med_collection->reciept_suffix; } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->patient_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->patient_code; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($med_collection->created_date)); ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->doctor_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->doctor_hospital_name; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->mobile_no; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->panel_type; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->booking_code; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                          ?>
                          <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->total_amount; ?></div>

                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->discount_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->net_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->debit; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($med_collection->balance=='1.00' || $med_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($med_collection->balance-1,2); } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $med_collection->mode ?></div>
                          <?php
                        }
                        


                      } 
                        ?>
                        </div> 
                        <?php
                        $m++; 
                        $self_med_total = $self_med_total+$med_collection->debit;
                      } 
                      ?>
                      <?php 
                      if($mode_check==1)
                      {
                      $p_m_k=1;
                      foreach($self_medicine_collection_list['med_coll_pay_mode'] as $self_medicine_pay){?>
                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                          <span style="float:left;"><?php echo $self_medicine_pay->mode; ?></span>
                          <span style="float:right;"><?php echo number_format($self_medicine_pay->tot_debit,2); ?></span>
                        </div>
                      </div> 
                      <?php $p_m_k++; }?>

                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                          <span style="float:left;">Total:</span>
                          <span style="float:right;"><?php echo number_format($self_med_total,2); ?></span>
                        </div>
                      </div> 
                      <!-- </div> -->
                      <?php 
                    }
                    }
                    ?>
                    <!-- end self medicine collection -->


                    <?php
                    if(!empty($pathology_self_collection_list['path_coll']))
                    { 
                      $data_empty = 1;
                      ?>

                      <div style="float:left;width:100%;font-weight:600;padding:4px;">
                        <span style="border-bottom:1px solid #111;">Pathology</span>
                      </div>
                      <?php
                      $k = 1 ;
                      $self_total = 0;
                      $self_counter = count($pathology_self_collection_list['path_coll']);
                      foreach($pathology_self_collection_list['path_coll'] as $collection)
                      {
                        ?>
                        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
                          
                          <?php   
                      foreach ($collection_tab_setting as $tab_value) 
                      { 
                        if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:6%;padding:1px 4px;text-align:left;"><?php echo $k; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:12%;padding:1px 4px;text-align:left;"><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></div>
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
                          <div style="float:left;width:12%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></div>
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
                          <div style="float:left;width:12%;padding:1px 4px;text-align:left;"><?php echo $collection->booking_code; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                          ?>
                          <!-- <div style="float:left;width:10%;padding:1px 4px;text-align:left;"></div> -->

                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

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
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->net_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $collection->debit; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                          ?>
                          <div style="float:left;width:8%;padding:1px 4px;text-align:left;"><?php echo $collection->mode ?></div>
                          <?php
                        }
                        


                      } 
                        ?>

                          </div> 
                          <?php
                          $k++; 
                          $self_total = $self_total+$collection->debit;
                        } 
                        ?>
                        <?php 
                        if($mode_check==1)
                        {
                        $p_m_k=1;
                        foreach($pathology_self_collection_list['path_coll_pay_mode'] as $payment_mode_path)
                        { ?>

                        <div style="float:left;width:100%;padding:4px;text-align:right;">
                          <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                            <span style="float:left;"><?php echo $payment_mode_path->mode ?></span>
                            <span style="float:right;"><?php echo number_format($payment_mode_path->tot_debit,2); ?></span>
                          </div>
                        </div> 

                        <?php $p_m_k++; 
                      }?>
                      <div style="float:left;width:100%;padding:4px;text-align:right;">
                        <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                          <span style="float:left;">Total:</span>
                          <span style="float:right;"><?php echo number_format($self_total,2); ?></span>
                        </div>
                      </div> 

                      <?php } } ?>


                      <?php 
                      
                      if(!empty($self_vaccination_collection_list['vaccine_coll']))
                      { 
                        $data_empty = 1;
                        ?>

                        <div style="float:left;width:100%;font-weight:600;padding:4px;">
                          <span style="border-bottom:1px solid #111;">Vaccination</span>
                        </div>
                        <?php
                        $k = 1 ;
                        $self_vaccine_total = 0;
                        $self_counter = count($self_vaccination_collection_list['vaccine_coll']);
                        foreach($self_vaccination_collection_list['vaccine_coll'] as $vaccination_collection)
                        {
                          //echo "<pre>"; print_r($self_vaccination_collection_list['vaccine_coll']);
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
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if(!empty($vaccination_collection->reciept_prefix) || !empty($vaccination_collection->reciept_suffix)){ echo $vaccination_collection->reciept_prefix.$vaccination_collection->reciept_suffix; } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->patient_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->patient_code; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo date('d-m-Y', strtotime($vaccination_collection->created_date)); ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->doctor_name; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->doctor_hospital_name; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->mobile_no; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->panel_type; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->booking_code; ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
                        {
                          ?>
                         

                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->total_amount; ?></div>

                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->discount_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->net_amount; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->debit; ?></div>
                          <?php
                        }
                        if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($vaccination_collection->balance=='1.00' || $vaccination_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($vaccination_collection->balance-1,2); } ?></div>
                          <?php
                        }

                        if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
                        {
                          ?>
                          <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $vaccination_collection->mode ?></div>
                          <?php
                        }
                        


                      } 
                        ?>

                            
                            </div> 
                            <?php
                            $k++; 
                            $self_vaccine_total = $self_vaccine_total+$vaccination_collection->debit;
                          } 
                          ?>
                          <?php 
                          if($mode_check==1)
                          {
                          $p_m_k=1;
                          foreach($self_vaccination_collection_list['vaccine_coll_payment_mode'] as $vaccine_payment_mode){ ?>
                          <div style="float:left;width:100%;padding:4px;text-align:right;">
                            <div style="float:right;width:150px; font-weight: bold; <?php if($p_m_k==1){ ?> border-top:1px solid #000 <?php  } ?>;margin-right:15px;">
                              <span style="float:left;"><?php echo $vaccine_payment_mode->mode; ?></span>
                              <span style="float:right;"><?php echo number_format($vaccine_payment_mode->tot_debit,2); ?></span>
                            </div>
                          </div> 
                          <?php $p_m_k++; } ?>
                          <div style="float:left;width:100%;padding:4px;text-align:right;">
                            <div style="float:right;width:150px; font-weight: bold;margin-right:15px;">
                              <span style="float:left;">Total:</span>
                              <span style="float:right;"><?php echo number_format($self_vaccine_total,2); ?></span>
                            </div>
                          </div> 

                          <?php } } ?>



                        </div>
                        <?php 
                      }
                      ?>
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