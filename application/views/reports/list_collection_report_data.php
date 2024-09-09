<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
    
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
       
      </tr>
    </table>
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
 //print '<pre>'; print_r($branch_collection_list) ; 
if(!empty($branch_collection_list['opd_collection_list']) || !empty($branch_vaccination_collection_list['vaccination_collection']) || !empty($branch_medicine_collection_list['medicine_collection_list']) || !empty($branch_billing_collection_list['billing_array']) || !empty($pathology_branch_collection_list['pathalogy_collection']) || !empty($branch_ipd_collection_list['ipd_collection_list']) || !empty($branch_ot_collection_list['ot_collection']) || !empty($blood_bank_branch_collection_list['blood_bank_collection']) || !empty($branch_ambulance_collection_list['ambulance_collection_list']) )
  { ?>
<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

  <?php
  $pay_mode = array('0'=>'','1'=>'Cash', '2'=>'Cheque', '3'=>'Card', '4'=>'NEFT');
  if(!empty($branch_collection_list['opd_collection_list']) || !empty($branch_vaccination_collection_list) || !empty($branch_medicine_collection_list['medicine_collection_list']) || !empty($branch_billing_collection_list['billing_array']) || !empty($pathology_branch_collection_list['pathalogy_collection']) || !empty($branch_ipd_collection_list['ipd_collection_list']) || !empty($branch_ot_collection_list['ot_collection']) || !empty($blood_bank_branch_collection_list['blood_bank_collection']) || !empty($branch_ambulance_collection_list['ambulance_collection_list']))
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
          

        <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">
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
              //echo $collection_setting->setting_name;
                
              }
           }

            
            //echo $mode_check;
           ?>
           </tr>
           </thead>
         </table>
           
            
        

              <?php
              if(!empty($branch_names))
              {
                foreach($branch_names as $names)
                {
                  ?>

                  <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">            
                    <tbody>
                  
                  <tr>
                   <td style="padding:5px 0"><u>Branch : <?php echo $names; ?></u></td>
                  </tr>
                </tbody>
              </table>
              <!--branch opd collection-->
<?php 
        if(!empty($branch_collection_list['opd_collection_list']))
        { 
          $data_empty = 1;
        ?>
          <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">            
              <tbody>
         
            <tr>
              <td style="padding:5px 0"><u>OPD</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }
              else
              {

              }



             
             //20201014
             if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix)){ echo $branchs->reciept_prefix.$branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
       $branch_total = $branch_total+$branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             //echo "<pre>"; print_r($branch_collection_list['payment_mode_list']); exit;
                  $p_amount=0;  
                  foreach($branch_collection_list['payment_mode_list'] as $payment_mode) 
                  { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode->to_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            $grand_collection_total = $grand_collection_total+$branch_total;
            ?>
              
           </footer>

         <?php } ?>

                  <!-- end of branch opd collection -->  
                  <!--billing start -->
                  <?php 

                         
        if(!empty($branch_billing_collection_list['billing_array'])){
        
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Billing</u></td>
            </tr>

            
            <?php 
            $x = 1;   
            $branch_bill_total = 0; 
            $branch_bill_total1=0;
            $count_bill_branch = count($branch_billing_collection_list['billing_array']);
            $n_bnc = '';
            foreach($branch_billing_collection_list['billing_array'] as $bill_branchs)
            { 
            
            if($names == $branchs->branch_name) 
            {
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $x; ?></td>
              <?php }else{

              }



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($bill_branchs->reciept_prefix) || !empty($bill_branchs->reciept_suffix)){ echo $bill_branchs->reciept_prefix.$bill_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($bill_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($bill_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $bill_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($bill_branchs->balance=='1.00' || $bill_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($bill_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $bill_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $x++;
       $branch_bill_total = $branch_bill_total+$bill_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 
                    $p_amount=0;  
                    foreach($branch_billing_collection_list['billing_payment_mode_array'] as $payment_mode_bill) 
                  { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_bill->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_bill->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_bill_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
         $grand_collection_total = $grand_collection_total+$branch_bill_total;
       } 

                  ?>

                  <!-- billing end -->
                  <?php 
    //ipd start

        if(!empty($branch_ipd_collection_list['ipd_collection_list'])){
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>IPD</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $x; ?></td>
              <?php }else{

              }



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($ipd_branchs->reciept_prefix) || !empty($ipd_branchs->reciept_suffix)){ echo $ipd_branchs->reciept_prefix.$ipd_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($ipd_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($ipd_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $ipd_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($ipd_branchs->balance=='1.00' || $ipd_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ipd_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $ipd_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $x++;
        $branch_ipd_total = $branch_ipd_total+$ipd_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 
                   $p_amount=0;  
                      foreach($branch_ipd_collection_list['ipd_payment_mode_list'] as $payment_mode_ipd) 
                      { ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_ipd->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_ipd->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_ipd_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
         $grand_collection_total = $grand_collection_total+$branch_ipd_total;
       } 

//ipd end
//medicine start
        
        if(!empty($branch_medicine_collection_list['medicine_collection_list'])){
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Medicine</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }else{

              }



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix)){ echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($medi_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $medi_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
                          $branch_medi_total = $branch_medi_total+$medi_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 
                  $p_amount=0;  
                      foreach($branch_medicine_collection_list['medicine_payment_mode_array'] as $payment_mode_med) 
                      { 
                      ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_med->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_med->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_medi_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
          $grand_collection_total = $grand_collection_total+$branch_medi_total;
       } 


         ///medicine end
                      
                        ?>


                        <?php 
                        //vaccination

        if(!empty($branch_vaccination_collection_list['vaccination_collection'])){
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Vaccination</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }else
              {

              }



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($medi_branchs->reciept_prefix) || !empty($medi_branchs->reciept_suffix)){ echo $medi_branchs->reciept_prefix.$medi_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($medi_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($medi_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $medi_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $medi_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
                            $branch_vaccination_total = $branch_vaccination_total+$medi_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 $p_amount=0;  
                  foreach($branch_vaccination_collection_list['vaccination_payment_mode_collection'] as $payment_mode_vaccine) 
                        { 
                      ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_vaccine->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_vaccine->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_vaccination_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
         $grand_collection_total = $grand_collection_total+$branch_vaccination_total;
       }

      //vaccination end

         //ot start


if(!empty($branch_ot_collection_list['ot_collection'])){
          $data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>OT</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }else{

              }



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($ot_branchs->reciept_prefix) || !empty($ot_branchs->reciept_suffix)){ echo $ot_branchs->reciept_prefix.$ot_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($ot_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($ot_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $ot_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($ot_branchs->balance=='1.00' || $ot_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ot_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $ot_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
                            $branch_ot_total = $branch_ot_total+$ot_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 $p_amount=0;  
      foreach($branch_ot_collection_list['ot_collection_payment_mode'] as $payment_mode_ot) 
      {     ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_ot->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_ot->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_ot_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
         $grand_collection_total = $grand_collection_total+$branch_ot_total;
       }


         //ot  report 
      //blood bank  report
                 
if(!empty($blood_bank_branch_collection_list['blood_bank_collection'])){          

$data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Blood Bank</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }else{}



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($blood_bank_branchs->reciept_prefix) || !empty($blood_bank_branchs->reciept_suffix)){ echo $blood_bank_branchs->reciept_prefix.$blood_bank_branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($blood_bank_branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($blood_bank_branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $blood_bank_branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($blood_bank_branchs->balance=='1.00' || $blood_bank_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($blood_bank_branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $blood_bank_branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
                           $branch_blood_bank_total = $branch_blood_bank_total+$blood_bank_branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                 $p_amount=0;  
    
        foreach($blood_bank_branch_collection_list['blood_bank_coll_payment_mode'] as $payment_mode_blood_bank) 
        {      ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_blood_bank->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_blood_bank->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_blood_bank_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
         $grand_collection_total = $grand_collection_total+$branch_blood_bank_total;
       }

         //end blood bank


          //Pathology collection report 
         if(!empty($pathology_branch_collection_list['pathalogy_collection']))
          {
$data_empty = 1;
        ?>

         <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"> 
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Pathology</u></td>
            </tr>

            
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
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $i; ?></td>
              <?php }else{}



             //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($branchs->reciept_prefix) || !empty($branchs->reciept_suffix)){ echo $branchs->reciept_prefix.$branchs->reciept_suffix; } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($branchs->patient_name),10,'<br>'); ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $branchs->patient_code; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($branchs->created_date)); ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $branchs->doctor_name; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $branchs->doctor_hospital_name; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $branchs->mobile_no; ?></td>
                <?php
              }else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $branchs->panel_type; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $branchs->booking_code; ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $branchs->total_amount; ?></td>

                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $branchs->discount_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $branchs->net_amount; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $branchs->debit; ?></td>
                <?php
              }
              else
              {

              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance-1,2); } ?></td>
                <?php
              }
              else
              {

              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $branchs->mode; ?></td>
                <?php
              }
              else
              {

              }
              


            
              } ?>
              
            </tr>
           
        <?php $i++;
                           $branch_total = $branch_total+$branchs->debit;
     } ?>
</tbody>
          </table>
          <footer style="float:left;width:100%">
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              <div style="float:right;width:135px;text-align: right;">
              <table style="font-size:13px;text-align: right;">
              <?php 
             
                $p_amount_path=0; 
            foreach($pathology_branch_collection_list['pathalogy_collection_payment_mode'] as $payment_mode_path) 
            {     ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $payment_mode_path->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($payment_mode_path->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php } 

               
              }

              ?>
              <tr>
                <td>
                  <strong style="float:left;">Total:</strong>
                  <span style="float:right;"><?php echo number_format($branch_total,2); ?></span>
                </td>
                </tr>
                </table>
                </div>
              
              <!-- </div> -->
              <?php 
            }
            ?>
              
           </footer>

         <?php 
          $grand_collection_total = $grand_collection_total+$branch_total;
       }

           //Pathology collection end
           
           
           /// Ambulance Collection start //

        if(!empty($branch_ambulance_collection_list['ambulance_collection_list'])) { ?>
                  <div style="float:left;width:100%;font-weight:600;padding:4px;">
                    <span style="border-bottom:1px solid #111;">Ambulance</span>
                  </div>  
                  <?php 

                  $i = 1;   
                  $branch_total = 0;  
                  $branch_total1=0;
                  $count_branch = count($branch_ambulance_collection_list['ambulance_collection_list']);
                  $n_bnc = '';
                  foreach($branch_ambulance_collection_list['ambulance_collection_list'] as $branchs)
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
            //20201014
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
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php echo $branchs->booking_no; ?></div>
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
                <div style="float:left;width:10%;padding:1px 4px;text-align:left;"><?php if($branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance,2); } ?></div>
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

            $grand_collection_total = $grand_collection_total+$branch_total;
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
       // Ambulance collection end //



           
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
  if(!empty($self_opd_collection_list['self_opd_coll']) || !empty($self_billing_collection_list['self_bill_coll']) || !empty($self_medicine_collection_list['med_coll']) || !empty($self_ipd_collection_list['ipd_coll']) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list['path_coll']) || !empty($self_ot_collection_list['self_ot_coll']) || !empty($self_vaccination_collection_list['vaccine_coll']) || !empty($self_blood_bank_collection_list['self_blood_bank_collection']) || !empty($self_ambulance_collection_list['self_ambulance_coll']) || !empty($self_dialysis_collection_list['self_dialysis_coll']) )
  {

    ?>
   <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">
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
              //echo $collection_setting->setting_name;
                
              }
           }
        ?>
          </tr>
           </thead>
           
           <?php 
           
          if(!empty($self_opd_collection_list['self_opd_coll']))
          {
           ?>
          
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Branch: Self</u></td>
            </tr>

            <tr>
              <td style="padding:5px 0"><u>OPD</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_opd_collection_list['self_opd_coll']);
            foreach($self_opd_collection_list['self_opd_coll'] as $collection)
            { 
            
            ?>
            <tr>
           <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0'){?><td width="10" align="center"><?php echo $k; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0'){ ?><td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0'){ ?><td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0'){ ?><td><?php echo $collection->patient_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0'){ ?><td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0'){ ?><td><?php echo $collection->doctor_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0'){ ?><td><?php echo $collection->doctor_hospital_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0'){ ?><td><?php echo $collection->mobile_no; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0'){ ?><td><?php echo $collection->panel_type; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0'){ ?><td><?php echo $collection->booking_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0'){?><td><?php echo $collection->total_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0'){ ?><td><?php echo $collection->discount_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0'){ ?><td><?php echo $collection->net_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0'){ ?><td><?php echo $collection->debit; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0'){ ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0'){ ?><td><?php echo $collection->mode; ?></td><?php }              
              

              } ?>
              
            </tr>
           
        <?php $k++;
        $self_total = $self_total+$collection->debit;
     } ?>


           </tbody>
         <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              
              <?php 
              foreach($self_opd_collection_list['self_opd_coll_payment_mode'] as $payment_mode_opd){ ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $payment_mode_opd->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td colspan="16" align="right">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
     $grand_collection_total = $grand_collection_total+$self_total;
  } ?>
       <!-- opd collection -->
        <!--blood bank colllection -->
          

       <?php 
           
         if(!empty($self_blood_bank_collection_list['self_blood_bank_collection']))
         {
           ?>
          
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Branch: Self</u></td>
            </tr>

            <tr>
              <td style="padding:5px 0"><u>Blood Bank</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_blood_bank_collection_list['self_blood_bank_collection']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_blood_bank_collection_list['self_blood_bank_collection'] as $collection)
            {
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $k; ?></td>
              <?php }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $collection->patient_code; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_name; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_hospital_name; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $collection->mobile_no; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $collection->panel_type; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $collection->booking_code; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $collection->discount_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $collection->net_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $collection->debit; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $collection->mode; ?></td>
                <?php
              }
              } ?>
              
            </tr>
           
        <?php $k++; 
              $self_total = $self_total+$collection->debit;
     } ?>
            </tbody>
          
              <?php
              if($mode_check==1)
              {
              
              ?>
             
              <?php 
              $p_m_k=1;
                  foreach($self_blood_bank_collection_list['self_blood_bank_coll_payment_mode'] as $self_payment_mode) { ?>
              
                <tr>
              
                <td colspan="16" align ="right"<?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                     <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $self_payment_mode->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_payment_mode->tot_debit,2); echo $p_m_k; ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td colspan="16" align="right">
                     <div style="float:right;width:223px;">
                  <strong style="float:left">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
$grand_collection_total = $grand_collection_total+$self_total;
} ?>
          <!--BLOOD bank collection -->
          <!-- IPD -->
              


       <?php 
           
         if(!empty($self_ipd_collection_list['ipd_coll']))
          {
           ?>
         
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>IPD</u></td>
            </tr>

            
            <?php 
           $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_ipd_collection_list['ipd_coll']);

            foreach($self_ipd_collection_list['ipd_coll'] as $collection)
            {
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?><td width="10" align="center"><?php echo $k; ?></td><?php }              
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?><td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?><td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?><td><?php echo $collection->patient_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?><td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?><td><?php echo $collection->doctor_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?><td><?php echo $collection->doctor_hospital_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?><td><?php echo $collection->mobile_no; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?><td><?php echo $collection->panel_type; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?><td><?php echo $collection->booking_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?><td><?php echo $collection->discount_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?><td><?php echo $collection->net_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?><td><?php echo $collection->debit; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              { //-1
                ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance,2); } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?><td><?php echo $collection->mode; ?></td><?php
              }
              
              } ?>
              
            </tr>
           
        <?php $k++; 
              $self_total = $self_total+$collection->debit;
     } ?>



           </tbody>
         
            
              <?php
              if($mode_check==1)
              {
              
              ?>
             
              <?php 
               $p_m_k=1;
               foreach($self_ipd_collection_list['ipd_coll_payment_mode'] as $ipd_pay_mode)
               { ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                     <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $ipd_pay_mode->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($ipd_pay_mode->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                     <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
        $grand_collection_total = $grand_collection_total+$self_total;
      } ?>

              <!-- ipd end -->
           
           <!-- self opd billing collection -->
           
        <?php 
           //echo "<pre>"; print_r($self_billing_collection_list['self_bill_coll']); exit;
         if(!empty($self_billing_collection_list['self_bill_coll']))
         {
           ?>
      
      
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Billing</u></td>
            </tr>

            
            <?php 
            $l = 1 ;
            $self_billing_total = 0;
            $self_billing_counter = count($self_billing_collection_list['self_bill_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_billing_collection_list['self_bill_coll'] as $billing_collection)
            {
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $l; ?></td>
              <?php }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?><td><?php if(!empty($billing_collection->reciept_prefix) || !empty($billing_collection->reciept_suffix)){ echo $billing_collection->reciept_prefix.$billing_collection->reciept_suffix; } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?><td><?php echo wordwrap(trim($billing_collection->patient_name),10,'<br>'); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?><td><?php echo $billing_collection->patient_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?><td><?php echo date('d-m-Y', strtotime($billing_collection->created_date)); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?><td><?php echo $billing_collection->doctor_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?><td><?php echo $billing_collection->doctor_hospital_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?><td><?php echo $billing_collection->mobile_no; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?><td><?php echo $billing_collection->panel_type; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?><td><?php echo $billing_collection->booking_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $billing_collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?><td><?php echo $billing_collection->discount_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?><td><?php echo $billing_collection->net_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?><td><?php echo $billing_collection->debit; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?><td><?php if($billing_collection->balance=='1.00' || $billing_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($billing_collection->balance-1,2); } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?><td><?php echo $billing_collection->mode; ?></td><?php
              }
              
              } ?>
              
            </tr>
           
        <?php $l++; 
          $self_billing_total = $self_billing_total+$billing_collection->debit;
     } ?>



        
</tbody>
        
              <?php
              if($mode_check==1)
              {
              
              ?>
              
              <?php 
               
                $p_m_k=1;
                foreach($self_billing_collection_list['self_bill_coll_payment_mode'] as $billing_payment_mode){ ?>
              
                <tr>
              
                <td <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                  <strong style="float:left;"><?php echo $billing_payment_mode->mode; ?> :</strong>
                  <span style="float:right;"><?php echo number_format($billing_payment_mode->tot_debit,2); ?></span>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_billing_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
           
           $grand_collection_total = $grand_collection_total+$self_billing_total;
         } ?>    
           <!-- end self opd billing collection -->
        <!-- self medicine collection -->
          <?php
  if(!empty($self_medicine_collection_list['med_coll']))
  { ?>
       
          <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Medicine Sale</u></td>
            </tr>

            
            <?php 
            $m = 1 ;
            $self_med_total = 0;
            $self_med_counter = count($self_medicine_collection_list['med_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_medicine_collection_list['med_coll'] as $med_collection)
            {
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $m; ?></td>
              <?php }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?><td><?php if(!empty($med_collection->reciept_prefix) || !empty($med_collection->reciept_suffix)){ echo $med_collection->reciept_prefix.$med_collection->reciept_suffix; } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?><td><?php echo wordwrap(trim($med_collection->patient_name),10,'<br>'); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?><td><?php echo $med_collection->patient_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?><td><?php echo date('d-m-Y', strtotime($med_collection->created_date)); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?><td><?php echo $med_collection->doctor_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?><td><?php echo $med_collection->doctor_hospital_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?><td><?php echo $med_collection->mobile_no; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?><td><?php echo $med_collection->panel_type; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?><td><?php echo $med_collection->booking_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $med_collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?><td><?php echo $med_collection->discount_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?><td><?php echo $med_collection->net_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?><td><?php echo $med_collection->debit; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?><td><?php if($med_collection->balance=='1.00' || $med_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($med_collection->balance-1,2); } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?><td><?php echo $med_collection->mode; ?></td><?php
              }
              } ?>
              
            </tr>
           
        <?php $m++; 
            $self_med_total = $self_med_total+$med_collection->debit;
          } ?>

           </tbody>
          
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              
              <?php 
               
                $p_m_k=1;
                      foreach($self_medicine_collection_list['med_coll_pay_mode'] as $self_medicine_pay){ ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $self_medicine_pay->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_medicine_pay->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_med_total,2); ?></span>
                  </div>
                </td>
                </tr>
              <?php 
            }
            
           $grand_collection_total = $grand_collection_total+$self_med_total;
         } ?>
        <!-- end self medicine collection -->
        
        
        
         
       <!--pathology self collection -->
             
        <?php 
           
          if(!empty($pathology_self_collection_list['path_coll']))
          {
           ?>
           
          <tbody>
            <tr>
              <td style="padding:5px 0"><u>Pathology</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($pathology_self_collection_list['path_coll']);
            foreach($pathology_self_collection_list['path_coll'] as $collection)
            { 
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $k; ?></td>
              <?php }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?><td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?><td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?><td><?php echo $collection->patient_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?><td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?><td><?php echo $collection->doctor_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?><td><?php echo $collection->doctor_hospital_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?><td><?php echo $collection->mobile_no; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?><td><?php echo $collection->panel_type; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?><td><?php echo $collection->booking_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?><td><?php echo $collection->discount_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?><td><?php echo $collection->net_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?><td><?php echo $collection->debit; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance,2); } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?><td><?php echo $collection->mode; ?></td><?php
              }
            
              } ?>
              
            </tr>
           
        <?php $k++;
        $self_total = $self_total+$collection->debit;
     } ?>
        </tbody>
 
         
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              
              <?php 
              foreach($pathology_self_collection_list['path_coll_pay_mode'] as $payment_mode_path){ ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $payment_mode_path->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($payment_mode_path->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
           
         $grand_collection_total = $grand_collection_total+$self_total;
       } ?>

    <!---pathology end -->
    
    <!--- Self Ambulance -->

        <?php
          if(!empty($self_ambulance_collection_list['self_ambulance_coll']))
          {
            
            ?>
            
          <tbody>
            <tr>
              <td style="padding:5px 0"><u>Ambulance</u></td>
            </tr>

            <?php 
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
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
                <td width="10" align="center"><?php echo $k; ?></td>
                <?php
              }

              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo $collection->patient_name; ?></td>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $collection->patient_code; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_name; ?></td>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_hospital_name; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $collection->mobile_no; ?></td>
                <?php
              }
            
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $collection->booking_code; ?></td>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?>
                
                <td><?php echo $collection->total_amount; ?></td>

                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $collection->discount_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $collection->net_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $collection->debit; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td>
                <?php
              }

              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $collection->mode ?></td>
                <?php
              }
              


            } 
              ?>
              
                </tr> 
                <?php
                $k++; 
                $self_total = $self_total+$collection->debit;
              } 
              ?>

              </tbody>
 
          

              <?php 
              if($mode_check==1)
              {
              $p_m_k=1;

              ?>
              
              <?php 
              
              foreach($self_ambulance_collection_list['self_ambulance_coll_payment_mode'] as $payment_mode_opd){ ?>
              <tr>
                <td  colspan="16" align="right" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                   <strong style="float:left;"><?php echo $payment_mode_opd->mode; ?></strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
              <?php $p_m_k++;} ?>
              <tr>
                <td colspan="16" align="right">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</span>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                  </td>
                </tr>
              <?php 
            }
            
            $grand_collection_total = $grand_collection_total+$self_total;
            }
            ?>
       <!--- Self Ambulance -->
    

    <!-- vaccination -->



    <!--end vaccination -->
            <?php 
        if(!empty($self_vaccination_collection_list['vaccine_coll']))
        { 
          $data_empty = 1;
        ?>

        
         <tbody>
            <tr>
              <td style="padding:5px 0"><u>Vaccination</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_vaccine_total = 0;
            $self_counter = count($self_vaccination_collection_list['vaccine_coll']);
            foreach($self_vaccination_collection_list['vaccine_coll'] as $vaccination_collection)
            { 
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?><td width="10" align="center"><?php echo $k; ?></td><?php 
              }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?><td><?php if(!empty($vaccination_collection->reciept_prefix) || !empty($vaccination_collection->reciept_suffix)){ echo $vaccination_collection->reciept_prefix.$vaccination_collection->reciept_suffix; } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?><td><?php echo wordwrap(trim($vaccination_collection->patient_name),10,'<br>'); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?><td><?php echo $vaccination_collection->patient_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?><td><?php echo date('d-m-Y', strtotime($vaccination_collection->created_date)); ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?><td><?php echo $vaccination_collection->doctor_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?><td><?php echo $vaccination_collection->doctor_hospital_name; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?><td><?php echo $vaccination_collection->mobile_no; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?><td><?php echo $vaccination_collection->panel_type; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?><td><?php echo $vaccination_collection->booking_code; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $vaccination_collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?><td><?php echo $vaccination_collection->discount_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?><td><?php echo $vaccination_collection->net_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?><td><?php echo $vaccination_collection->debit; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?><td><?php if($vaccination_collection->balance=='1.00' || $vaccination_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($vaccination_collection->balance-1,2); } ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?><td><?php echo $vaccination_collection->mode; ?></td><?php
              }
              } ?>
              
            </tr>
           
        <?php $k++;
       $self_vaccine_total = $self_vaccine_total+$vaccination_collection->debit;
     } ?>
    </tbody>
    <
              <?php
             
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              
              <?php 
              foreach($self_vaccination_collection_list['vaccine_coll_payment_mode'] as $vaccine_payment_mode){ ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $vaccine_payment_mode->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($vaccine_payment_mode->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_vaccine_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
         $grand_collection_total = $grand_collection_total+$self_vaccine_total;
       } ?>
         
                      
                      <!-- end vaccination -->
                      
                      
                      

                       <!--ot colllection -->
          <?php 
           
         if(!empty($self_ot_collection_list['self_ot_coll']))
          {
           ?>
         
            <tbody>
            <tr>
              <td style="padding:5px 0"><u>OT</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_total = 0;
            $self_counter = count($self_ot_collection_list['self_ot_coll']);
                    //echo "<pre>";print_r($self_collection_list);
            foreach($self_ot_collection_list['self_ot_coll'] as $collection)
            {
            
            ?>
            <tr>
              <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0')
              {
                ?>
              <td width="10" align="center"><?php echo $k; ?></td>
              <?php }
              //20201014
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0')
              {
                ?>
                <td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0')
              {
                ?>
                <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0')
              {
                ?>
                <td><?php echo $collection->patient_code; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0')
              {
                ?>
                <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_name; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0')
              {
                ?>
                <td><?php echo $collection->doctor_hospital_name; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0')
              {
                ?>
                <td><?php echo $collection->mobile_no; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0')
              {
                ?>
                <td><?php echo $collection->panel_type; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0')
              {
                ?>
                <td><?php echo $collection->booking_code; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0')
              {
                ?><td><?php echo $collection->total_amount; ?></td><?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0')
              {
                ?>
                <td><?php echo $collection->discount_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0')
              {
                ?>
                <td><?php echo $collection->net_amount; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0')
              {
                ?>
                <td><?php echo $collection->debit; ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0')
              {
                ?>
                <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td>
                <?php
              }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0')
              {
                ?>
                <td><?php echo $collection->mode; ?></td>
                <?php
              }
              
              } ?>
              
            </tr>
          
        <?php $k++; 
              $self_total = $self_total+$collection->debit;
          }?>

            </tbody>
          
            
              <?php
              if($mode_check==1)
              {
              
              ?>
              
              <?php 
              $p_m_k=1;
                foreach($self_ot_collection_list['self_ot_coll_payment_mode'] as $self_payment_mode) { ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $self_payment_mode->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_payment_mode->tot_debit,2);?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td align="right" colspan="16">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_total,2); ?></span>
                  </div>
                </td>
                </tr>
              <?php 
            }
            
$grand_collection_total = $grand_collection_total+$self_total;
} 
// <!--ot collection -->
//dialysis collection           
          if(!empty($self_dialysis_collection_list['self_dialysis_coll']))
          {
           ?>
          
           <tbody>
            <tr>
              <td style="padding:5px 0"><u>Dialysis</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_dialysis_total = 0;
            $self_counter = count($self_dialysis_collection_list['self_dialysis_coll']);
            foreach($self_dialysis_collection_list['self_dialysis_coll'] as $collection)
            { 
            
            ?>
            <tr>
           <?php   
            foreach ($collection_tab_setting as $tab_value) 
            { 
              if(strcmp(strtolower($tab_value->setting_name),'s_no')=='0'){?><td width="10" align="center"><?php echo $k; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'reciept_no')=='0'){ ?><td><?php if(!empty($collection->reciept_prefix) || !empty($collection->reciept_suffix)){ echo $collection->reciept_prefix.$collection->reciept_suffix; } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_name')=='0'){ ?><td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'patient_code')=='0'){ ?><td><?php echo $collection->patient_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_date')=='0'){ ?><td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'doctor_name')=='0'){ ?><td><?php echo $collection->doctor_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'referred_by')=='0'){ ?><td><?php echo $collection->doctor_hospital_name; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'mobile_no')=='0'){ ?><td><?php echo $collection->mobile_no; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'panel_type')=='0'){ ?><td><?php echo $collection->panel_type; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'booking_code')=='0'){ ?><td><?php echo $collection->booking_code; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'total_amount')=='0'){?><td><?php echo $collection->total_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'discount')=='0'){ ?><td><?php echo $collection->discount_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'net_amount')=='0'){ ?><td><?php echo $collection->net_amount; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'paid_amount')=='0'){ ?><td><?php echo $collection->debit; ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'balance')=='0'){ ?><td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance-1,2); } ?></td><?php }
              if(strcmp(strtolower($tab_value->setting_name),'payment_mode')=='0'){ ?><td><?php echo $collection->mode; ?></td><?php }              
              

              } ?>
              
            </tr>
           
        <?php $k++;
        $self_dialysis_total = $self_dialysis_total+$collection->debit;
     } ?>


           </tbody>
         <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              
              <?php 
              foreach($self_dialysis_collection_list['dialysis_collection_payment_mode'] as $payment_mode_opd){ ?>
              
                <tr>
              
                <td align="right" colspan="16" <?php if($p_m_k==1){ ?> style="border-top:1px solid black;" <?php } ?>>
                    <div style="float:right;width:223px;">
                  <strong style="float:left;"><?php echo $payment_mode_opd->mode; ?> :</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($payment_mode_opd->tot_debit,2); ?></span>
                  </div>
                </td>
             </tr>
                
               
              <?php $p_m_k++;} ?>
              <tr>
                <td colspan="16" align="right">
                    <div style="float:right;width:223px;">
                  <strong style="float:left;">Total:</strong>
                  <span style="width:86px;text-align:left;"><?php echo number_format($self_dialysis_total,2); ?></span>
                  </div>
                </td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
     $grand_collection_total = $grand_collection_total+$self_dialysis_total;
  } ?>
       <!-- dialysis collection -->

       

       <!-- over all collection -->

       <tr>
              <td align="right" colspan="16"  style="border-top:1px solid black;" >
                  <div style="float:right;width:223px;">
                <strong style="float:left;">Grand Total:</strong>
                <span style="width:86px;text-align:left;"><?php echo number_format($grand_collection_total,2);?></span>
                </div>
              </td>
            </tr>
             </table>
                <?php 
              }
              ?>
     
      </html>
      
      <script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
    <script>
    
    <?php if($get['send']!='preview'){ ?>
      $(document).ready(function(){
          my_function();
      });
      <?php } ?>
                  
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