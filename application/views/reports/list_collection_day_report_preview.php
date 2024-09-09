<?php  $users_data = $this->session->userdata('auth_users');
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
  $full_grand_collection_total=0;
  $full_grand_total_amount=0;
  $full_grand_total_discount=0;
  $full_grand_total_balance=0;
  $full_grand_total_netamount=0;

if(1)
  { ?>
<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

  <?php
  if(1)
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
            foreach($branch_ipd_collection_list['ambulance_collection_list'] as $names) //IPD
           {
            $branch_names[] = $names->branch_name;
           }
           
           $branch_names = array_unique($branch_names);  

           ?>
           <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
           	<?php if(!empty($branch_collection_list['opd_collection_list']) || !empty($branch_ambulance_collection_list['ambulance_collection_list']) || !empty($branch_billing_collection_list['billing_array']) || !empty($branch_medicine_collection_list['medicine_collection_list']) || !empty($branch_vaccination_collection_list['vaccination_collection']) || !empty($branch_ipd_collection_list['ipd_collection_list']) || !empty($pathology_branch_collection_list['pathalogy_collection']) || !empty($branch_ot_collection_list['ot_collection']) || !empty($blood_bank_branch_collection_list['blood_bank_collection'])){ ?>          
           	 <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">

              <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u>S.No.</u></div>
              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u>Booking Date</u></div>
              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u>Total Amount</u></div>
              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Discount</u></div>

              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Balance</u></div>
              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Net Amount</u></div>

              <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Paid Amount</u></div>
            </div>
			<?php } ?>

          </div> 
            <div style="float:left;width:100%;font-size:13px;"> 

              <?php
              if(!empty($branch_names))
              {
                foreach($branch_names as $names)
                {

                  $branch_grand_collection_total=0;
                  $branch_grand_total_amount=0;
                  $branch_grand_total_discount=0;
                  $branch_grand_total_balance=0;
                  $branch_grand_total_netamount=0;
                  ?>
                  <div style="float:left;width:100%;font-weight:600;padding:4px;">
                    <span style="border-bottom:1px solid #111;">Branch : <?php echo $names; ?></span>
                  </div>  
          <?php if(!empty($branch_collection_list['opd_collection_list'])) {            
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">OPD</span>
            </div>
            <?php 
            $k1 = 1 ;
            $branch_total1 = 0;
            $branch_amount1 = 0;
            $branch_discount1 = 0;
            $branch_balance1 = 0;
            $branch_netamount1 = 0;
          $count_branch = count($branch_collection_list['opd_collection_list']);
          foreach($branch_collection_list['opd_collection_list'] as $branchs_opd)
            { 

            if($names == $branchs_opd->branch_name) 
            {
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k1; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs_opd->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs_opd->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs_opd->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($branchs_opd->balance=='1.00' || $branchs_opd->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs_opd->balance,2); $branch_balance1 += $branchs_opd->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs_opd->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs_opd->debit; ?></div>
              
                </div> 
                <?php
                $k1++; 
                 $branch_total1 += $branchs_opd->debit;
                 $branch_amount1 += $branchs_opd->total_amount;
                 $branch_discount1 += $branchs_opd->discount_amount;
                 $branch_netamount1 += $branchs_opd->net_amount;
               } }
                $branch_grand_collection_total += $branch_total1;
                $branch_grand_total_amount += $branch_amount1;
                $branch_grand_total_discount += $branch_discount1;
                $branch_grand_total_balance += $branch_balance1;
                $branch_grand_total_netamount += $branch_netamount1;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount1,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount1,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance1,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount1,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total1,2);?></div>
              
                </div> 
            <?php } ?>



<!-- billing collection start -->
     <?php 
      if(!empty($branch_billing_collection_list['billing_array'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Billing</span>
      </div>
      <?php 
        $k2 = 1 ;
        $branch_total2 = 0;
        $branch_amount2 = 0;
        $branch_discount2 = 0;
        $branch_balance2 = 0;
        $branch_netamount2 = 0;
       $count_bill_branch = count($branch_billing_collection_list['billing_array']);
      foreach($branch_billing_collection_list['billing_array'] as $bill_branchs)
      { 
       if($names == $bill_branchs->branch_name) 
        { ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k2; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($bill_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->discount_amount; ?></div>
             
                <?php if($bill_branchs->balance=='1.00' || $bill_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($bill_branchs->balance,2); $branch_balance2 += $bill_branchs->balance; } ?>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k2++; 
                 $branch_total2 += $bill_branchs->debit;
                 $branch_amount2 += $bill_branchs->total_amount;
                 $branch_discount2 += $bill_branchs->discount_amount;              
                 $branch_netamount2 += $bill_branchs->net_amount;
               } }
                $branch_grand_collection_total += $branch_total2;
                $branch_grand_total_amount += $branch_amount2;
                $branch_grand_total_discount += $branch_discount2;
                $branch_grand_total_balance += $branch_balance2;
                $branch_grand_total_netamount += $branch_netamount2;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount2,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount2,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance2,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount2,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total2,2);?></div>
              
                </div> 

  <?php }?>

 <!--  ipd collection start -->

   <?php 
       if(!empty($branch_ipd_collection_list['ipd_collection_list'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">IPD</span>
      </div>
      <?php 
        $k3 = 1 ;
        $branch_total3 = 0;
        $branch_amount3 = 0;
        $branch_discount3 = 0;
        $branch_balance3 = 0;
        $branch_netamount3 = 0;
       $count_ipd_branch = count($branch_ipd_collection_list['ipd_collection_list']);
      foreach($branch_ipd_collection_list['ipd_collection_list'] as $ipd_branchs)
      { 
       if($names == $ipd_branchs->branch_name) 
        {  ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k3; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ipd_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($ipd_branchs->balance=='1.00' || $ipd_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ipd_branchs->balance,2); $branch_balance3 += $ipd_branchs->balance; } ?></div>
                 
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k3++; 
                 $branch_total3 += $ipd_branchs->debit;
                 $branch_amount3 += $ipd_branchs->total_amount;
                 $branch_discount3 += $ipd_branchs->discount_amount;
                 $branch_netamount3 += $ipd_branchs->net_amount;
               }}
                $branch_grand_collection_total += $branch_total3;
                $branch_grand_total_amount += $branch_amount3;
                $branch_grand_total_discount += $branch_discount3;
                $branch_grand_total_balance += $branch_balance3;
                $branch_grand_total_netamount += $branch_netamount3;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount3,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount3,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance3,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount3,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total3,2);?></div>
              
                </div> 

  <?php }?>

<!--  ipd collection stop -->

<!--   Medicine Sale collection start -->

   <?php 
       if(!empty($branch_medicine_collection_list['medicine_collection_list'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Medicine Sale</span>
      </div>
      <?php 
        $k4 = 1 ;
        $branch_total4 = 0;
        $branch_amount4 = 0;
        $branch_discount4 = 0;
        $branch_balance4 = 0;
        $branch_netamount4 = 0;
        $count_medi_branch = count($branch_medicine_collection_list);
       foreach($branch_medicine_collection_list['medicine_collection_list'] as $medi_branchs) {
        if($names == $medi_branchs->branch_name) 
        {  ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k4; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($medi_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance,2); $branch_balance4 += $medi_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k4++; 
                 $branch_total4 += $medi_branchs->debit;
                 $branch_amount4 += $medi_branchs->total_amount;
                 $branch_discount4 += $medi_branchs->discount_amount;
                 $branch_netamount4 += $medi_branchs->net_amount;
               }}
                $branch_grand_collection_total += $branch_total4;
                $branch_grand_total_amount += $branch_amount4;
                $branch_grand_total_discount += $branch_discount4;
                $branch_grand_total_balance += $branch_balance4;
                $branch_grand_total_netamount += $branch_netamount4;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount4,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount4,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance4,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount4,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total4,2);?></div>
              
                </div> 

  <?php }?>


<!--   Medicine Sale collection end -->


<!--   Vaccination collection start -->

   <?php 
       if(!empty($branch_vaccination_collection_list['vaccination_collection'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Vaccination</span>
      </div>
      <?php 
        $k5 = 1 ;
        $branch_total5 = 0;
        $branch_amount5 = 0;
        $branch_discount5 = 0;
        $branch_balance5 = 0;
        $branch_netamount5 = 0;
        $count_vaccination_branch = count($branch_vaccination_collection_list['vaccination_collection']);
       foreach($branch_vaccination_collection_list['vaccination_collection'] as $vacc_branchs){
        if($names == $vacc_branchs->branch_name) 
        { 
          ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k5; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($vacc_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->discount_amount; ?></div>
             
              
                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($vacc_branchs->balance=='1.00' || $vacc_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($vacc_branchs->balance,2); $branch_balance5 += $vacc_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k5++; 
                 $branch_total5 += $vacc_branchs->debit;
                 $branch_amount5 += $vacc_branchs->total_amount;
                 $branch_discount5 += $vacc_branchs->discount_amount;
                 $branch_netamount5 += $vacc_branchs->net_amount;
               } }
                $branch_grand_collection_total += $branch_total5;
                $branch_grand_total_amount += $branch_amount5;
                $branch_grand_total_discount += $branch_discount5;
                $branch_grand_total_balance += $branch_balance5;
                $branch_grand_total_netamount += $branch_netamount5;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount5,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount5,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance5,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount5,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total5,2);?></div>
              
                </div> 

  <?php }?>
<!--   Vaccination collection end -->

<!--   OT collection start -->
    <?php
    if(!empty($branch_ot_collection_list['ot_collection'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">OT</span>
      </div>
      <?php 
        $k6 = 1 ;
        $branch_total6 = 0;
        $branch_amount6 = 0;
        $branch_discount6 = 0;
        $branch_balance6 = 0;
        $branch_netamount6 = 0;
        $count_ot_branch = count($branch_ot_collection_list['ot_collection']);
       foreach($branch_ot_collection_list['ot_collection'] as $ot_branchs){
        if($names == $ot_branchs->branch_name) 
         { 
          ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k6; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ot_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($ot_branchs->balance=='1.00' || $ot_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ot_branchs->balance,2); $branch_balance6 += $ot_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k6++; 
                 $branch_total6 += $ot_branchs->debit;
                 $branch_amount6 += $ot_branchs->total_amount;
                 $branch_discount6 += $ot_branchs->discount_amount;
                 $branch_netamount6 += $ot_branchs->net_amount;
               } }
                $branch_grand_collection_total += $branch_total6;
                $branch_grand_total_amount += $branch_amount6;
                $branch_grand_total_discount += $branch_discount6;
                $branch_grand_total_balance += $branch_balance6;
                $branch_grand_total_netamount += $branch_netamount6;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount6,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount6,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance6,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount6,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total6,2);?></div>
              
                </div> 

  <?php }?>


<!--   OT collection end -->

<!--   blood bank collection start -->
    <?php
    if(!empty($blood_bank_branch_collection_list['blood_bank_collection'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Blood Bank </span>
      </div>
      <?php 
        $k7 = 1 ;
        $branch_total7 = 0;
        $branch_amount7 = 0;
        $branch_discount7 = 0;
        $branch_balance7 = 0;
        $branch_netamount7 = 0;
        $count_blood_bank_branch = count($blood_bank_branch_collection_list['blood_bank_collection']);
       foreach($blood_bank_branch_collection_list['blood_bank_collection'] as $blood_bank_branchs){
        if($names == $blood_bank_branchs->branch_name) 
         {
          ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k7; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($blood_bank_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->discount_amount; ?></div>
             
              
               <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($blood_bank_branchs->balance=='1.00' || $blood_bank_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($blood_bank_branchs->balance,2); $branch_balance7 += $blood_bank_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k7++; 
                 $branch_total7 += $blood_bank_branchs->debit;
                 $branch_amount7 += $blood_bank_branchs->total_amount;
                 $branch_discount7 += $blood_bank_branchs->discount_amount;
                 $branch_netamount7 += $blood_bank_branchs->net_amount;
               }}
                $branch_grand_collection_total += $branch_total7;
                $branch_grand_total_amount += $branch_amount7;
                $branch_grand_total_discount += $branch_discount7;
                $branch_grand_total_balance += $branch_balance7;
                $branch_grand_total_netamount += $branch_netamount7;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount7,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount7,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance7,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount7,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total7,2);?></div>
              
                </div> 

  <?php }?>
<!--   Blood Bank collection end -->

<!--   Pathology collection start -->
    <?php
    if(!empty($pathology_branch_collection_list['pathalogy_collection'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Pathology</span>
      </div>
      <?php 
        $k8 = 1 ;
        $branch_total8 = 0;
        $branch_amount8 = 0;
        $branch_discount8 = 0;
        $branch_balance8 = 0;
        $branch_netamount8 = 0;
        $count_branch = count($pathology_branch_collection_list['pathalogy_collection']);
       foreach($pathology_branch_collection_list['pathalogy_collection'] as $path_branchs){
        if($names == $path_branchs->branch_name) 
         {
          ?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k8; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($path_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $path_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $path_branchs->discount_amount; ?></div>
             
              
                  <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($path_branchs->balance=='1.00' || $path_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($path_branchs->balance,2); $branch_balance8 += $path_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $path_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $path_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k8++; 
                 $branch_total8 += $path_branchs->debit;
                 $branch_amount8 += $path_branchs->total_amount;
                 $branch_discount8 += $path_branchs->discount_amount;
                 $branch_netamount8 += $path_branchs->net_amount;
               }}
                $branch_grand_collection_total += $branch_total8;
                $branch_grand_total_amount += $branch_amount8;
                $branch_grand_total_discount += $branch_discount8;
                $branch_grand_total_balance += $branch_balance8;
                $branch_grand_total_netamount += $branch_netamount8;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount8,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount8,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance8,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount8,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total8,2);?></div>
              
                </div> 

  <?php }?>
<!--   Pathology collection end -->


<!--   Ambulance collection start -->
    <?php
    if(!empty($branch_ambulance_collection_list['ambulance_collection_list'])) { ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">Ambulance</span>
      </div>
      <?php 
        $k9 = 1 ;
        $branch_total9 = 0;
        $branch_amount9 = 0;
        $branch_discount9 = 0;
        $branch_balance9 = 0;
        $branch_netamount9 = 0;
        $count_branch = count($branch_ambulance_collection_list['ambulance_collection_list']);
        foreach($branch_ambulance_collection_list['ambulance_collection_list'] as $amb_branchs){
         if($names == $amb_branchs->branch_name) 
          {?>
        <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k9; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($amb_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $amb_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $amb_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($amb_branchs->balance=='1.00' || $amb_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($amb_branchs->balance,2); $branch_balance9 += $amb_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $amb_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $amb_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k9++; 
                 $branch_total9 += $amb_branchs->debit;
                 $branch_amount9 += $amb_branchs->total_amount;
                 $branch_discount9 += $amb_branchs->discount_amount;
                 $branch_netamount9 += $amb_branchs->net_amount;
               }}
                $branch_grand_collection_total += $branch_total9;
                $branch_grand_total_amount += $branch_amount9;
                $branch_grand_total_discount += $branch_discount9;
                $branch_grand_total_balance += $branch_balance9;
                $branch_grand_total_netamount += $branch_netamount9;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_amount9,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_discount9,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_balance9,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_netamount9,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total9,2);?></div>
              
                </div> 

  <?php }?>
<!--   Ambulance collection end -->

            <div style="float:left;width:100%;padding:4px;text-align:right;font-weight: bold;border-top:1px solid #000;">
             <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Grand Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_grand_total_amount,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_grand_total_discount,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_grand_total_balance,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_grand_total_netamount,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_grand_collection_total,2);?></div>
              
                </div> 
            </div>  
            <?php
              $full_grand_collection_total +=$branch_grand_collection_total;
              $full_grand_total_amount +=$branch_grand_total_amount;
              $full_grand_total_discount +=$branch_grand_total_discount;
              $full_grand_total_balance +=$branch_grand_total_balance;
              $full_grand_total_netamount +=$branch_grand_total_netamount;
          }
        }

        ?>     
      </div>
      <?php  
    }
    ?>
  </div>
  <?php } ?>

     <!--  Branch List End -->



<?php 
$grand_collection_total = 0;    
$grand_total_amount = 0; 
$grand_total_discount = 0; 
$grand_total_balance = 0;  
$grand_total_netamount = 0; 
?>
    <div style="float:left;width:100%;border:1px solid #111;">
      <div style="float:left; width:100%;font-size:13px;">        
        
        <div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;display:flex;justify-content:space-around;">
      
          <div style="float:left;width:10%;font-weight:600;padding:4px;text-align:left;"><u>S.No.</u></div>
          <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u>Booking Date</u></div>
          <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u>Total Amount</u></div>
           <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Discount</u></div>

             <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Balance</u></div>
          <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Net Amount</u></div>
         
          <div style="float:left;width:15%;font-weight:600;padding:4px;text-align:left;"><u> Paid Amount</u></div>
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
            $self_amount1 = 0;
            $self_discount1 = 0;
            $self_balance1 = 0;
            $self_netamount1 = 0;
            $self_counter = count($self_opd_collection_list['self_opd_coll']);
            foreach($self_opd_collection_list['self_opd_coll'] as $collection)
            { 
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance,2); $self_balance1 += $collection->balance; }  ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->debit; ?></div>
              
                </div> 
                <?php
                $k++; 
                $self_total = $self_total+$collection->debit;
                 $self_amount1 += $collection->total_amount;
                 $self_discount1 += $collection->discount_amount;                 
                 $self_netamount1 += $collection->net_amount;
               }
                $grand_collection_total = $grand_collection_total+$self_total;
                $grand_total_amount= $grand_total_amount+$self_amount1;
                $grand_total_discount= $grand_total_discount+$self_discount1;
                $grand_total_balance= $grand_total_balance+$self_balance1;
                $grand_total_netamount= $grand_total_netamount+$self_netamount1;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount1,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount1,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance1,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount1,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_total,2);?></div>
              
                </div> 
            <?php } 

          if(!empty($branch_billing_collection_list['billing_array'])){ ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;">
              <span style="border-bottom:1px solid #111;">Billing</span>
            </div>
            <?php 
            $x = 1;   
            $branch_bill_total = 0; 
            $self_amount2=0;
            $self_discount2=0;
            $self_balance2=0;
            $self_netamount2=0;
            $branch_bill_total1=0;
            $count_bill_branch = count($branch_billing_collection_list['billing_array']);
            $n_bnc = '';
            foreach($branch_billing_collection_list['billing_array'] as $bill_branchs)
            {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $x; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($bill_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($bill_branchs->balance=='1.00' || $bill_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($bill_branchs->balance,2); $self_balance2 += $bill_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $bill_branchs->debit; ?></div>
              
                </div> 
                <?php
                $k++; 
                 $branch_bill_total = $branch_bill_total+$bill_branchs->debit;
                  $self_amount2 += $bill_branchs->total_amount;
                  $self_discount2 += $bill_branchs->discount_amount;
                 
                  $self_netamount2 += $bill_branchs->net_amount;
               }
                $grand_total_amount= $grand_total_amount+$self_amount2;
                $grand_total_discount= $grand_total_discount+$self_discount2;
                $grand_total_balance= $grand_total_balance+$self_balance2;
                $grand_total_netamount= $grand_total_netamount+$self_netamount2;
                $grand_collection_total = $grand_collection_total+$branch_bill_total;
              ?>
              <hr>
               <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount2,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount2,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance2,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount2,2);?></div>

                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_bill_total,2);?></div>
              
                </div> 
            <?php }
//ipd Start 

          if(!empty($self_ipd_collection_list['ipd_coll'])){ ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;">
               <span style="border-bottom:1px solid #111;">IPD</span>
            </div>
            <?php 
             $x = 1;   
              $branch_ipd_total = 0;
               $self_amount3 = 0;
               $self_discount3 = 0;
               $self_balance3 = 0;
               $self_netamount3 = 0;  
              $branch_ipd_total1=0;
              $count_ipd_branch = count($self_ipd_collection_list['ipd_coll']);
              $n_bnc = '';
              foreach($self_ipd_collection_list['ipd_coll'] as $ipd_branchs)
              {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $x; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ipd_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($ipd_branchs->balance=='1.00' || $ipd_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ipd_branchs->balance,2);$self_balance3 += $ipd_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ipd_branchs->debit; ?></div>
              
                </div> 
                <?php
                $x++; 
                 $branch_ipd_total = $branch_ipd_total+$ipd_branchs->debit;

                  $self_amount3 += $ipd_branchs->total_amount;
                 $self_discount3 += $ipd_branchs->discount_amount;                 
                 $self_netamount3 += $ipd_branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount3;
                $grand_total_discount= $grand_total_discount+$self_discount3;
                $grand_total_balance= $grand_total_balance+$self_balance3;
                $grand_total_netamount= $grand_total_netamount+$self_netamount3;
                $grand_collection_total = $grand_collection_total+$branch_ipd_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
               
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount3,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount3,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance3,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount3,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_ipd_total,2);?></div>
              
                </div> 
            <?php }
//ipd end

//Medicine Sale Start 

           if(!empty($self_medicine_collection_list['med_coll'])){ ?>
                <div style="float:left;width:100%;font-weight:600;padding:4px;">
                  <span style="border-bottom:1px solid #111;">Medicine Sale</span>
            </div>
            <?php 
            $c1 = 1;   
            $branch_medi_total = 0; 
            $self_amount4 = 0;
            $self_discount4 = 0;
            $self_balance4 = 0;
            $self_netamount4 = 0;  
            $branch_medi_total1=0;
            $count_medi_branch = count($self_medicine_collection_list);
            $n_bnc = '';
            $i=1;
            foreach($self_medicine_collection_list['med_coll'] as $medi_branchs)
            {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $c1; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($medi_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($medi_branchs->balance=='1.00' || $medi_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($medi_branchs->balance,2);  $self_balance4 += $medi_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $medi_branchs->debit; ?></div>
              
                </div> 
                <?php
                $c1++; 
                 $branch_medi_total = $branch_medi_total+$medi_branchs->debit;
                 $self_amount4 += $medi_branchs->total_amount;
                 $self_discount4 += $medi_branchs->discount_amount;
                 $self_netamount4 += $medi_branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount4;
                $grand_total_discount= $grand_total_discount+$self_discount4;
                $grand_total_balance= $grand_total_balance+$self_balance4;
                $grand_total_netamount= $grand_total_netamount+$self_netamount4;
                $grand_collection_total = $grand_collection_total+$branch_medi_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount4,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount4,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance4,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount4,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_medi_total,2);?></div>
              
                </div> 
            <?php }
//Medicine Sale end
//Vaccination Start 

           if(!empty($self_vaccination_collection_list['vaccine_coll'])){ ?>
                <div style="float:left;width:100%;font-weight:600;padding:4px;">
                  <span style="border-bottom:1px solid #111;">Vaccination</span>
            </div>
            <?php 
           $i = 1; 
           $c=1; 
           $branch_vaccination_total = 0;  
            $self_amount5 = 0;
            $self_discount5 = 0;
            $self_balance5 = 0;
            $self_netamount5 = 0;  
           $branch_vaccination_total1 = 0; 
            foreach($self_vaccination_collection_list['vaccine_coll'] as $vacc_branchs)
            {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $c; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($vacc_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($vacc_branchs->balance=='1.00' || $vacc_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($vacc_branchs->balance,2); $self_balance5 += $vacc_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $vacc_branchs->debit; ?></div>
              
                </div> 
                <?php
                $c++; 
                 $branch_vaccination_total = $branch_vaccination_total+$vacc_branchs->debit;
                  $self_amount5 += $vacc_branchs->total_amount;
                 $self_discount5 += $vacc_branchs->discount_amount;                 
                 $self_netamount5 += $vacc_branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount5;
                $grand_total_discount= $grand_total_discount+$self_discount5;
                $grand_total_balance= $grand_total_balance+$self_balance5;
                $grand_total_netamount= $grand_total_netamount+$self_netamount5;
                $grand_collection_total = $grand_collection_total+$branch_vaccination_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount5,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount5,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance5,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount5,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_vaccination_total,2);?></div>
              
                </div> 
            <?php }
//Vaccination end

//ot Collection Start 

      if(!empty($self_ot_collection_list['self_ot_coll'])){ ?>
      <div style="float:left;width:100%;font-weight:600;padding:4px;">
        <span style="border-bottom:1px solid #111;">OT </span>
      </div>
      <?php 
      $c2 = 1;   
      $branch_ot_total = 0; 
       $self_amount6 = 0;
       $self_discount6 = 0;
       $self_balance6 = 0;
       $self_netamount6 = 0;  
      $branch_ot_total1=0;
      $count_ot_branch = count($self_ot_collection_list['self_ot_coll']);
      $n_bnc = '';
      foreach($self_ot_collection_list['self_ot_coll'] as $ot_branchs)
      {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $c2; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($ot_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($ot_branchs->balance=='1.00' || $ot_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($ot_branchs->balance,2);$self_balance6 += $ot_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $ot_branchs->debit; ?></div>
              
                </div> 
                <?php
                $c2++; 
                 $branch_ot_total = $branch_ot_total+$ot_branchs->debit;
                  $self_amount6 += $ot_branchs->total_amount;
                 $self_discount6 += $ot_branchs->discount_amount;                 
                 $self_netamount6 += $ot_branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount6;
                $grand_total_discount= $grand_total_discount+$self_discount6;
                $grand_total_balance= $grand_total_balance+$self_balance6;
                $grand_total_netamount= $grand_total_netamount+$self_netamount6;
                $grand_collection_total = $grand_collection_total+$branch_ot_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount6,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount6,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance6,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount6,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_ot_total,2);?></div>
              
                </div> 
            <?php }
//ot Collection end


// blood bank  report

        if(!empty($self_blood_bank_collection_list['self_blood_bank_collection'])){ ?>
        <div style="float:left;width:100%;font-weight:600;padding:4px;">
          <span style="border-bottom:1px solid #111;">Blood Bank </span>
        </div>
        <?php 
        $c4 = 1;   
        $branch_blood_bank_total = 0; 
         $self_amount7 = 0;
         $self_discount7 = 0;
         $self_balance7 = 0;
         $self_netamount7 = 0;  
        $branch_blood_bank_total1=0;
        $count_blood_bank_branch = count($self_blood_bank_collection_list['self_blood_bank_collection']);
        $n_bnc = '';
        foreach($self_blood_bank_collection_list['self_blood_bank_collection'] as $blood_bank_branchs)
        {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $c2; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($blood_bank_branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($blood_bank_branchs->balance=='1.00' || $blood_bank_branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($blood_bank_branchs->balance,2); $self_balance7 += $blood_bank_branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $blood_bank_branchs->debit; ?></div>
              
                </div> 
                <?php
                $c4++; 
                 $branch_blood_bank_total = $branch_blood_bank_total+$blood_bank_branchs->debit;
                  $self_amount7 += $blood_bank_branchs->total_amount;
                 $self_discount7 += $blood_bank_branchs->discount_amount;                 
                 $self_netamount7 += $blood_bank_branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount7;
                $grand_total_discount= $grand_total_discount+$self_discount7;
                $grand_total_balance= $grand_total_balance+$self_balance7;
                $grand_total_netamount= $grand_total_netamount+$self_netamount7;
                $grand_collection_total = $grand_collection_total+$branch_blood_bank_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount7,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount7,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance7,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount7,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_blood_bank_total,2);?></div>
              
                </div> 
            <?php }
//blood bank end

// Pathology collection report
          if(!empty($pathology_self_collection_list['path_coll']))
          {

            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;">
              <span style="border-bottom:1px solid #111;">Pathology </span>
            </div>  
            <?php 
            $i = 1;   
            $branch_total = 0;  
             $self_amount8 = 0;
             $self_discount8 = 0;
             $self_balance8 = 0;
             $self_netamount8 = 0;  
            $branch_total1=0;
            $count_branch = count($pathology_self_collection_list['path_coll']);
            $n_bnc = '';
            foreach($pathology_self_collection_list['path_coll'] as $branchs)
            {   
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $i; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance,2); $self_balance8 += $branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->debit; ?></div>
              
                </div> 
                <?php
                $i++; 
                 $branch_total = $branch_total+$branchs->debit;
                  $self_amount8 += $branchs->total_amount;
                 $self_discount8 += $branchs->discount_amount;                 
                 $self_netamount8 += $branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount8;
                $grand_total_discount= $grand_total_discount+$self_discount8;
                $grand_total_balance= $grand_total_balance+$self_balance8;
                $grand_total_netamount= $grand_total_netamount+$self_netamount8;
                $grand_collection_total = $grand_collection_total+$branch_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount8,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount8,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance8,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount8,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total,2);?></div>
              
                </div> 
            <?php }
//Pathology collection end


// Ambulance Collection report
          if(!empty($self_ambulance_collection_list['self_ambulance_coll'])) { ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;">
              <span style="border-bottom:1px solid #111;">Ambulance</span>
            </div>  
            <?php 
            $i = 1;   
            $branch_total = 0;  
             $self_amount9 = 0;
             $self_discount9 = 0;
             $self_balance9 = 0;
             $self_netamount9 = 0;  
            $branch_total1=0;
            $count_branch = count($self_ambulance_collection_list['self_ambulance_coll']);
            $n_bnc = '';
            foreach($self_ambulance_collection_list['self_ambulance_coll'] as $branchs)
            { 
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $i; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($branchs->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($branchs->balance=='1.00' || $branchs->balance=='0.00'){ echo '0.00'; }else{ echo number_format($branchs->balance,2); $self_balance9 += $branchs->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $branchs->debit; ?></div>
              
                </div> 
                <?php
                $i++; 
                 $branch_total = $branch_total+$branchs->debit;
                  $self_amount9 += $branchs->total_amount;
                 $self_discount9 += $branchs->discount_amount;               
                 $self_netamount9 += $branchs->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount9;
                $grand_total_discount= $grand_total_discount+$self_discount9;
                $grand_total_balance= $grand_total_balance+$self_balance9;
                $grand_total_netamount= $grand_total_netamount+$self_netamount9;
                $grand_collection_total = $grand_collection_total+$branch_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount9,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount9,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance9,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount9,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($branch_total,2);?></div>
              
                </div> 
            <?php }
//Ambulance Collection end

// Purchase Collection report
         if(!empty($self_purchase_return_collection_list['self_purchase_coll']))
          {
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">Purchase</span>
            </div>
            <?php 
            $k = 1 ;
            $self_total = 0;
             $self_amount10 = 0;
             $self_discount10 = 0;
             $self_balance10 = 0;
             $self_netamount10 = 0;  
            $self_counter = count($self_purchase_return_collection_list['self_purchase_coll']);
            foreach($self_purchase_return_collection_list['self_purchase_coll'] as $collection)
            { 
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($collection->balance,2);  $self_balance10 += $collection->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $collection->debit; ?></div>
              
                </div> 
                <?php
                $k++; 
                 $self_total = $self_total+$collection->debit;
                  $self_amount10 += $collection->total_amount;
                 $self_discount10 += $collection->discount_amount;
                 $self_netamount10 += $collection->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount10;
                $grand_total_discount= $grand_total_discount+$self_discount10;
                $grand_total_balance= $grand_total_balance+$self_balance10;
                $grand_total_netamount= $grand_total_netamount+$self_netamount10;
                $grand_collection_total = $grand_collection_total+$self_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount10,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount10,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance10,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount10,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_total,2);?></div>
              
                </div> 
            <?php }
//Purchase Collection end
  

  // Billing Collection report
        if(!empty($self_billing_collection_list['self_bill_coll']))
        {

          ?>
          <div style="float:left;width:100%;font-weight:600;padding:4px;">
            <span style="border-bottom:1px solid #111;">Billing</span>
          </div>
          <?php

          $l = 1 ;
          $self_billing_total = 0;
           $self_amount11 = 0;
           $self_discount11 = 0;
           $self_balance11 = 0;
           $self_netamount11 = 0;  
          $self_billing_counter = count($self_billing_collection_list['self_bill_coll']);
          foreach($self_billing_collection_list['self_bill_coll'] as $billing_collection)
          { 
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $l; ?></div>
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($billing_collection->booking_date)); ?></div>
             
               
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $billing_collection->total_amount; ?></div>

                 <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $billing_collection->discount_amount; ?></div>
             
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php if($billing_collection->balance=='1.00' || $billing_collection->balance=='0.00'){ echo '0.00'; }else{ echo number_format($billing_collection->balance,2);  $self_balance11 += $billing_collection->balance; } ?></div>
              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $billing_collection->net_amount; ?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo $billing_collection->debit; ?></div>
              
                </div> 
                <?php
                $l++; 
                 $self_billing_total = $self_billing_total+$billing_collection->debit;
                  $self_amount11 += $billing_collection->total_amount;
                 $self_discount11 += $billing_collection->discount_amount;                
                 $self_netamount11 += $billing_collection->net_amount;
               }

                $grand_total_amount= $grand_total_amount+$self_amount11;
                $grand_total_discount= $grand_total_discount+$self_discount11;
                $grand_total_balance= $grand_total_balance+$self_balance11;
                $grand_total_netamount= $grand_total_netamount+$self_netamount11;
                $grand_collection_total = $grand_collection_total+$self_billing_total;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_amount11,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_discount11,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_balance11,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_netamount11,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($self_billing_total,2);?></div>
              
                </div> 
            <?php }
//Billing Collection end
            //dialysis
        if(!empty($self_dialysis_collection_list['self_dialysis_coll']))
          {
            ?>
            <div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
              <span style="border-bottom:1px solid #111;">Dialysis</span>
            </div>
            <?php 
            $k = 1 ;
            $self_dialysis_total = 0;
            $self_dialysis_amount1 = 0;
            $self_dialysis_discount1 = 0;
            $self_dialysis_balance1 = 0;
            $self_dialysis_netamount1 = 0;
            $self_counter = count($self_dialysis_collection_list['self_dialysis_coll']);
            foreach($self_dialysis_collection_list['self_dialysis_coll'] as $collection)
            { 
            ?>
            <div style="float:left;width:100%;display:flex;justify-content:space-around;">
     
                <div style="float:left;width:10%;padding:4px;text-align:left;"><?php echo $k; ?></div>
               
                <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo date('d-m-Y', strtotime($collection->booking_date)); ?></div>
             
               
                <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo $collection->total_amount; ?></div>

                 <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo $collection->discount_amount; ?></div>
             
              
                <div style="float:left;width:13%;padding:4px;text-align:left;"><?php if($collection->balance>0){ echo number_format($collection->balance,2);  $self_balance1 += $collection->balance; }else{  echo '0.00'; } ?></div>
              
                <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo $collection->net_amount; ?></div>

              
                <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo $collection->debit; ?></div>
              
                </div> 
                <?php
                $k++; 
                $self_dialysis_total = $self_dialysis_total+$collection->debit;
                 $self_dialysis_amount1 += $collection->total_amount;
                 $self_dialysis_discount1 += $collection->discount_amount;                
                 $self_dialysis_netamount1 += $collection->net_amount;
               }
                $grand_collection_total = $grand_collection_total+$self_dialysis_total;
                $grand_total_amount= $grand_total_amount+$self_dialysis_amount1;
                $grand_total_discount= $grand_total_discount+$self_dialysis_discount1;
                $grand_total_balance= $grand_total_balance+$self_dialysis_balance1;
                $grand_total_netamount= $grand_total_netamount+$self_dialysis_netamount1;
              ?>
              <hr>
                <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                    <div style="float:left;width:10%;padding:4px;text-align:left;">Total</div>
                   
                    <div style="float:left;width:13%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo number_format($self_dialysis_amount1,2);?></div>

                     <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo number_format($self_dialysis_discount1,2);?></div>
                 
                  
                    <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo number_format($self_dialysis_balance1,2);?></div>
                  
                    <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo number_format($self_netamount1,2);?></div>

                  
                    <div style="float:left;width:13%;padding:4px;text-align:left;"><?php echo number_format($self_dialysis_total,2);?></div>
              
                </div> 
            <?php }
             ?>    
          </div>
        


        






         <div style="float:left;width:100%;padding:4px;text-align:right;font-weight: bold;border-top:1px solid #000;">
             <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
     
                 <div style="float:left;width:10%;padding:4px;text-align:left;">Grand Total</div>
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
                 
                   
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($grand_total_amount,2);?></div>

                     <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($grand_total_discount,2);?></div>
                 
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($grand_total_balance,2);?></div>
                  
                    <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($grand_total_netamount,2);?></div>

              
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($grand_collection_total,2);?></div>
              
                </div> 
            </div>  
<?php 

$full_grand_total_amount+=$grand_total_amount;
$full_grand_total_discount+=$grand_total_discount;
$full_grand_total_balance+=$grand_total_balance;
$full_grand_total_netamount+=$grand_total_netamount;
$full_grand_collection_total+=$grand_collection_total;
?>





     <div style="float:left;width:100%;padding:4px;text-align:right;font-weight: bold;border-top:1px solid #000;">
        <div style="float:left;width:100%;display:flex;justify-content:space-around;font-weight:600;">
          
            <div style="float:left;width:10%;padding:4px;text-align:left;">All Grand Total</div>
              
               <div style="float:left;width:15%;padding:4px;text-align:left;"></div>
            
              
               <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($full_grand_total_amount,2);?></div>
     
                <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($full_grand_total_discount,2);?></div>
            
             
               <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($full_grand_total_balance,2);?></div>
             
               <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($full_grand_total_netamount,2);?></div>
     
         
           <div style="float:left;width:15%;padding:4px;text-align:left;"><?php echo number_format($full_grand_collection_total,2);?></div>
         
           </div> 
       </div>
       
       
       
        <!--expense list -->
       
        <!-- Expense list -->


          <div style="float:left; margin-top:10px; width:100%;border:1px solid #111;border-top:none;">
            <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;"><tbody><tr>
             <td style="padding:5px 0"><u>Expense List</u></td>
           </tr>
         </tbody>
       </table>
        <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">
          <thead>
           <tr>
            <th style=""><u>Date</u></th>
            <th><u>&nbsp;</u></th>
            <th><u>&nbsp;</u></th> 
            <th><u>&nbsp;</u></th> 
            <th><u>&nbsp;</u></th>
            <th><u>Amount</u></th>
          </tr>
        </thead>
        <tbody>
          <?php 

          if(!empty($normal_expense_list['expense_list']) || !empty($salary_list['expense_list']) || !empty($med_pur_list['expense_list']) || !empty($sale_ret_list['sale_ret_list']) || !empty($stock_list['expense_list']) || !empty($vacc_pur_list['expense_list']) || !empty($vacc_sale_ret_list['expense_list']) || !empty($opd_refund_list['expense_list']) || !empty($path_refund_list['expense_list']) || !empty($med_refund_list['expense_list']) || !empty($ipd_refund_list['expense_list']) || !empty($ot_refund_list['expense_list']) || !empty($bb_refund_list['expense_list']) || !empty($ambulance_list['expense_list']) || !empty($daycare_list['expense_list']) || !empty($doc_com_list['doctor_commission']) || !empty($dialysis_expense_list['expense_list'])){
            $i=1;
            $grand_tot = 0;
            if(!empty($normal_expense_list['expense_list']))
             { ?>
              <tr>
                <td style="padding:5px 0"><u>Expenses</u></td>
              </tr>
              <?php $i=1;
              $exp_totals = 0;
              foreach($normal_expense_list['expense_list'] as $expenses)
              {
                ?> 
                <tr>
                  <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                  <td> &nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                  <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                  <td>&nbsp;<?php //echo $expenses->exp_category; ?></td>
                  <td>&nbsp;<?php //payment mode  ?></td>
                  <td><?php echo number_format($expenses->paid_amount1,2); ?></td>
                </tr> 
                <?php
                $exp_totals = $exp_totals+$expenses->paid_amount1;
                $i++;
              }
              ?>

              <tr>
                <td colspan="5"></td>
                <td>
                  <strong>Total:</strong>
                  <span><?php echo number_format($exp_totals,2); ?></span>
                </td>
              </tr>
              <?php $grand_tot = $grand_tot+$exp_totals; }

              if(!empty($salary_list['expense_list']))
               { ?>
                 <tr>
                  <td style="padding:5px 0"><u>Employee Salary</u></td>
                </tr>
                <?php  $i=1;
                $exp_total = 0;
                foreach($salary_list['expense_list'] as $expenses)
                {
                  ?> 
                  <tr>
                    <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                    <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                    <td>&nbsp;<?php //echo $expenses->type; ?></td> 
                    <td>&nbsp;<?php //echo $expenses->exp_category; ?></td>
                    <td>&nbsp; </td>
                    <td><?php echo number_format($expenses->paid_amount1,2); ?></td>
                  </tr> 
                  <?php
                  $exp_total = $exp_total+$expenses->paid_amount1;
                  $i++;
                }
                ?>
                <tr>
                  <td colspan="5"></td>
                  <td>
                    <strong>Total:</strong>
                    <span><?php echo number_format($exp_total,2); ?></span>
                  </td>
                </tr>
                <?php $grand_tot = $grand_tot+$exp_total; }

                if(!empty($med_pur_list['expense_list']))
                 { ?>
                   <tr>
                    <td style="padding:5px 0"><u>Medicine Purchase</u></td>
                  </tr>
                  <?php
                  $i=1;
                  $exp_totals = 0;
                  foreach($med_pur_list['expense_list'] as $expenses)
                  {
                    ?> 
                    <tr>
                      <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                      <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                      <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                      <td>&nbsp;<?php //echo $expenses->exp_category; ?></td>
                      <td>&nbsp; </td>
                      <td><?php echo number_format($expenses->paid_amount1,2); ?></td>
                    </tr> 
                    <?php
                    $exp_totals = $exp_totals+$expenses->paid_amount1;
                    $i++;
                  }
                  ?>
                  <tr>
                    <td colspan="5"></td>
                    <td>
                      <strong>Total:</strong>
                      <span><?php echo number_format($exp_totals,2); ?></span>
                    </td>
                  </tr>
                  <?php $grand_tot = $grand_tot+$exp_totals; }

                  if(!empty($sale_ret_list['sale_ret_list']))
                   { ?>
                     <tr>
                      <td style="padding:5px 0"><u>Medicine Sale Return</u></td>
                    </tr>
                    <?php
                    $i=1;
                    $exp_total = 0;
                    foreach($sale_ret_list['sale_ret_list'] as $expenses)
                    {
                      ?> 
                      <tr>
                        <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                        <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                        <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                        <td>&nbsp;<?php //echo $expenses->exp_category; ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo number_format($expenses->paid_amount1,2); ?></td>
                      </tr> 
                      <?php
                      $exp_total = $exp_total+$expenses->paid_amount1;
                      $i++;
                    }
                    ?>
                    <tr>
                      <td colspan="5"></td>
                      <td>
                        <strong>Total:</strong>
                        <span><?php echo number_format($exp_total,2); ?></span>
                      </td>
                    </tr>
                    <?php  $grand_tot = $grand_tot+$exp_total; }


                    if(!empty($stock_list['expense_list']))
                    {
                      ?>
                      <tr>
                        <td style="padding:5px 0"><u>Purchase Stock Inventory</u></td>
                      </tr>
                      <?php
                      $i=1;
                      $exp_total = 0;
                      foreach($stock_list['expense_list'] as $expenses)
                      {
                        ?> 
                        <tr>
                          <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                          <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                          <td>&nbsp;<?php //echo $expenses->type; ?></td> 
                          <td>&nbsp;<?php //echo $expenses->exp_category; ?></td>
                          <td>&nbsp;</td>
                          <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                        </tr> 
                        <?php
                        $exp_total = $exp_total+$expenses->paid_amount1;
                        $i++;
                      }
                      ?>
                      <tr>
                        <td colspan="5"></td>
                        <td>
                          <strong>Total:</strong>
                          <span><?php echo number_format($exp_total,2); ?></span>
                        </td>
                      </tr>
                      <?php $grand_tot = $grand_tot+$exp_total; }


                      if(!empty($vacc_pur_list['expense_list']))
                       { ?>
                         <tr>
                          <td style="padding:5px 0"><u>Vaccine Purchase</u></td>
                        </tr>
                        <?php

                        $i=1;
                        $exp_total = 0;
                        foreach($vacc_pur_list['expense_list'] as $expenses)
                        {
                          ?> 
                          <tr>
                            <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                            <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                            <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                            <td>&nbsp;<?php //echo $expenses->exp_category; 
                            ?></td>
                            <td>&nbsp;</td>
                            <td><?php echo number_format($expenses->paid_amount1,2); ?></td>
                          </tr> 
                          <?php
                          $exp_total = $exp_total+$expenses->paid_amount1;
                          $i++;
                        }
                        ?>

                        <tr>
                          <td colspan="5"></td>
                          <td>
                            <strong>Total:</strong>
                            <span><?php echo number_format($exp_total,2); ?></span>
                          </td>
                        </tr>
                        <?php $grand_tot = $grand_tot+$exp_total; }


                        if(!empty($vacc_sale_ret_list['expense_list']))
                         {?>
                           <tr>
                            <td style="padding:5px 0"><u>Vaccine Sale Return</u></td>
                          </tr>
                          <?php
                          $i=1;
                          $exp_total = 0;
                          foreach($vacc_sale_ret_list['expense_list'] as $expenses)
                          {
                            ?> 
                            <tr>
                              <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                              <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                              <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                              <td>&nbsp;<?php //echo $expenses->exp_category; 
                              ?></td>
                              <td>&nbsp;
                              </td>
                              <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                            </tr> 
                            <?php
                            $exp_total = $exp_total+$expenses->paid_amount1;
                            $i++;
                          }
                          ?>
                          <tr>
                            <td colspan="5"></td>
                            <td>
                              <strong>Total:</strong>
                              <span><?php echo number_format($exp_total,2); ?></span>
                            </td>
                          </tr>
                          <?php $grand_tot = $grand_tot+$exp_total; }


                          if(!empty($opd_refund_list['expense_list']))
                           { ?>
                             <tr>
                              <td style="padding:5px 0"><u>OPD Return</u></td>
                            </tr>
                            <?php
                            $i=1;
                            $exp_total = 0;
                            foreach($opd_refund_list['expense_list'] as $expenses)
                            {
                              ?> 
                              <tr>
                                <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                <td>&nbsp;<?php //echo $expenses->exp_category; 
                                ?></td>
                                <td>&nbsp;</td>
                                <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                              </tr> 
                              <?php
                              $exp_total = $exp_total+$expenses->paid_amount1;
                              $i++;
                            }
                            ?>
                            <tr>
                              <td colspan="5"></td>
                              <td>
                                <strong>Total:</strong>
                                <span><?php echo number_format($exp_total,2); ?></span>
                              </td>
                            </tr>
                            <?php $grand_tot = $grand_tot+$exp_total; }


                            if(!empty($path_refund_list['expense_list']))
                             {?>
                               <tr>
                                <td style="padding:5px 0"><u>Pathology Return</u></td>
                              </tr>
                              <?php
                              $i=1;
                              $exp_total = 0;
                              foreach($path_refund_list['expense_list'] as $expenses)
                              {
                                ?> 
                                <tr>
                                  <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                  <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                  <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                  <td>&nbsp;<?php //echo $expenses->exp_category; 
                                  ?></td>
                                  <td>&nbsp;</td>
                                  <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                </tr> 
                                <?php
                                $exp_total = $exp_total+$expenses->paid_amount1;
                                $i++;
                              }
                              ?>
                              <tr>
                                <td colspan="5"></td>
                                <td>
                                  <strong>Total:</strong>
                                  <span><?php echo number_format($exp_total,2); ?></span>
                                </td>
                              </tr>
                              <?php $grand_tot = $grand_tot+$exp_total; }

                              if(!empty($med_refund_list['expense_list']))
                               {?>
                                 <tr>
                                  <td style="padding:5px 0"><u>Medicine Refund</u></td>
                                </tr>
                                <?php
                                $i=1;
                                $exp_total = 0;
                                foreach($med_refund_list['expense_list'] as $expenses)
                                {
                                  ?> 
                                  <tr>
                                    <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                    <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                    <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                    <td>&nbsp;<?php //echo $expenses->exp_category; 
                                    ?></td>
                                    <td>&nbsp;
                                    </td>
                                    <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                  </tr> 
                                  <?php
                                  $exp_total = $exp_total+$expenses->paid_amount1;
                                  $i++;
                                }
                                ?>
                                <tr>
                                  <td colspan="5"></td>
                                  <td>
                                    <strong>Total:</strong>
                                    <span><?php echo number_format($exp_total,2); ?></span>
                                  </td>
                                </tr>
                                <?php $grand_tot = $grand_tot+$exp_total; }


                                if(!empty($ipd_refund_list['expense_list']))
                                 { //echo "<pre>"; print_r($ipd_refund_list); exit; ?>
                               <tr>
                                <td style="padding:5px 0"><u>IPD Refund</u></td>
                              </tr>
                              <?php
                              $i=1;
                              $exp_total = 0;
                              foreach($ipd_refund_list['expense_list'] as $expenses)
                              {
                                ?> 
                                <tr>
                                  <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                  <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                  <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                  <td>&nbsp;<?php //echo $expenses->exp_category; 
                                  ?></td>
                                  <td>&nbsp;
                                  </td>
                                  <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                </tr> 
                                <?php
                                $exp_total = $exp_total+$expenses->paid_amount1;
                                $i++;
                              }
                              ?>
                              <tr>
                                <td colspan="5"></td>
                                <td>
                                  <strong>Total:</strong>
                                  <span><?php echo number_format($exp_total,2); ?></span>
                                </td>
                              </tr>
                              <?php $grand_tot = $grand_tot+$exp_total; }


                              if(!empty($ot_refund_list['expense_list']))
                               {?>
                                 <tr>
                                  <td style="padding:5px 0"><u>OT Refund</u></td>
                                </tr>
                                <?php
                                $i=1;
                                $exp_total = 0;
                                foreach($ot_refund_list['expense_list'] as $expenses)
                                {
                                  ?> 
                                  <tr>
                                    <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                    <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                    <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                    <td>&nbsp;<?php //echo $expenses->exp_category; 
                                    ?></td>
                                    <td>&nbsp;
                                    </td>
                                    <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                  </tr> 
                                  <?php
                                  $exp_total = $exp_total+$expenses->paid_amount1;
                                  $i++;
                                }
                                ?>
                                <tr>
                                  <td colspan="5"></td>
                                  <td>
                                    <strong>Total:</strong>
                                    <span><?php echo number_format($exp_total,2); ?></span>
                                  </td>
                                </tr>
                                <?php $grand_tot = $grand_tot+$exp_total; }


                                if(!empty($bb_refund_list['expense_list']))
                                 {?>
                                   <tr>
                                    <td style="padding:5px 0"><u>Blood Bank Refund</u></td>
                                  </tr>
                                  <?php
                                  $i=1;
                                  $exp_total = 0;
                                  foreach($bb_refund_list['expense_list'] as $expenses)
                                  {
                                    ?> 
                                    <tr>
                                      <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                      <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                      <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                                      <td>&nbsp;<?php //echo $expenses->exp_category; 
                                      ?></td>
                                      <td>&nbsp;
                                      </td>
                                      <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                    </tr> 
                                    <?php
                                    $exp_total = $exp_total+$expenses->paid_amount1;
                                    $i++;
                                  }
                                  ?>
                                  <tr>
                                    <td colspan="5"></td>
                                    <td>
                                      <strong>Total:</strong>
                                      <span><?php echo number_format($exp_total,2); ?></span>
                                    </td>
                                  </tr>
                                  <?php $grand_tot = $grand_tot+$exp_total; }


                                  if(!empty($ambulance_list['expense_list']))
                                   {?>
                                     <tr>
                                      <td style="padding:5px 0"><u>Ambulance Refund</u></td>
                                    </tr>
                                    <?php
                                    $i=1;
                                    $exp_total = 0;
                                    foreach($ambulance_list['expense_list'] as $expenses)
                                    {
                                      ?> 
                                      <tr>
                                        <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                        <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                        <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                        <td>&nbsp;<?php //echo $expenses->exp_category; 
                                        ?></td>
                                        <td>&nbsp;
                                        </td>
                                        <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                      </tr> 
                                      <?php
                                      $exp_total = $exp_total+$expenses->paid_amount1;
                                      $i++;
                                    }
                                    ?>
                                    <tr>
                                      <td colspan="5"></td>
                                      <td>
                                        <strong>Total:</strong>
                                        <span><?php echo number_format($exp_total,2); ?></span>
                                      </td>
                                    </tr>
                                    <?php $grand_tot = $grand_tot+$exp_total; }

                                    if($opd_bill_list['expense_list'])
                                    {
                                      ?>
                                      <tr>
                                        <td style="padding:5px 0"><u>OPD Billing Refund</u></td>
                                      </tr>
                                      <?php
                                      $i=1;
                                      $exp_total = 0;
                                      foreach($opd_bill_list['expense_list'] as $expenses)
                                      {
                                        ?> 
                                        <tr>
                                          <td>&nbsp;<?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                          <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                          <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name.' '.$expenses->book_code; ?></td> 
                                          <td>&nbsp;<?php //echo $expenses->exp_category; 
                                          ?></td>
                                          <td>&nbsp;
                                          </td>
                                          <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                        </tr> 
                                        <?php
                                        $exp_total = $exp_total+$expenses->paid_amount1;
                                        $i++;
                                      }
                                      ?>

                                      <tr>
                                        <td colspan="5"></td>
                                        <td>
                                          <strong>Total:</strong>
                                          <span><?php echo number_format($exp_total,2); ?></span>
                                        </td>
                                      </tr>
                                      <?php $grand_tot = $grand_tot+$exp_total; 
                                    }

                                    if(!empty($daycare_list['expense_list']))
                                     {?>
                                       <tr>
                                        <td style="padding:5px 0"><u>Day Care Refund</u></td>
                                      </tr>
                                      <?php
                                      $i=1;
                                      $exp_total = 0;
                                      foreach($daycare_list['expense_list'] as $expenses)
                                      {
                                        ?> 
                                        <tr>
                                          <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                          <td>&nbsp;<?php //echo $expenses->vouchar_no; ?>  </td>
                                          <td>&nbsp;<?php //echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                                          <td>&nbsp;<?php //echo $expenses->exp_category; 
                                          ?></td>
                                          <td>&nbsp;
                                          </td>
                                          <td style="float:right;"><?php echo number_format($expenses->paid_amount1,2); ?></td>
                                        </tr> 
                                        <?php
                                        $exp_total = $exp_total+$expenses->paid_amount1;
                                        $i++;
                                      }
                                      ?>

                                      <tr>
                                        <td colspan="5"></td>
                                        <td>
                                          <strong>Total:</strong>
                                          <span><?php echo number_format($exp_total,2); ?></span>
                                        </td>
                                      </tr>
                                      <?php $grand_tot = $grand_tot+$exp_total; }
                                      if(!empty($dialysis_expense_list['expense_list']))
                                     {?>
                                       <tr>
                                        <td style="padding:5px 0"><u>Dialysis Refund</u></td>
                                      </tr>
                                      <?php
                                      $i=1;
                                      $exp_total = 0;
                                      foreach($dialysis_expense_list['expense_list'] as $expenses)
                                      {
                                        ?> 
                                        <tr>
                                          <td><?php echo date('d-m-Y',strtotime($expenses->expenses_date)); ?></td>
                                          <td><?php echo $expenses->vouchar_no; ?>  </td>
                                          <td><?php echo $expenses->type.' '. $expenses->patient_name; ?></td> 
                                          <td><?php echo $expenses->exp_category; 
                                          if(!empty(trim($expenses->remarks)))
                                          {
                                            echo ' ('.$expenses->remarks.')';
                                          }
                                          ?></td>
                                          <td>
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
                                          <td style="float:right;"><?php echo number_format($expenses->paid_amount,2); ?></td>
                                        </tr> 
                                        <?php
                                        $exp_total = $exp_total+$expenses->paid_amount;
                                        $i++;
                                      }
                                      ?>

                                      <?php 
                                      foreach($dialysis_exp_list['expense_payment_mode'] as $payment_mode) 
                                      {
                                        ?>
                                        <tr>
                                          <td colspan="5"></td>
                                          <td>
                                            <strong><?php echo $payment_mode->payment_mode; ?>:</strong>
                                            <span><?php echo number_format($payment_mode->paid_total_amount,2); ?></span>
                                          </td>
                                        </tr>

                                        <?php 
                                        $p_amount = $p_amount+$payment_mode->paid_total_amount;
                                      } ?>
                                      <tr>
                                        <td colspan="5"></td>
                                        <td>
                                          <strong>Total:</strong>
                                          <span><?php echo number_format($exp_total,2); ?></span>
                                        </td>
                                      </tr>
                                      <?php $grand_tot = $grand_tot+$exp_total; }
                                      if(!empty($doc_com_list['doctor_commission']))
                                       {?>
                                         <tr>
                                          <td style="padding:5px 0"><u>Doctor Commission</u></td>
                                        </tr>
                                        <?php
                                        $i=0;
                                        $tot_exp=0;
                                        foreach($doc_com_list['doctor_commission'] as $expenses_doc)
                                        {
                                          ?> 
                                          <tr>
                                            <td><?php echo date('d-m-Y',strtotime($expenses_doc->created_date)); ?></td>
                                            <td>&nbsp;<?php echo ''; ?>  </td>
                                            <td>&nbsp;<?php //echo 'Doctor Commission'; ?></td> 
                                            <td>&nbsp;<?php //echo 'N/A';?></td>
                                            <td>&nbsp;
                                              <?php //echo ucwords(strtolower($expenses_doc->mode));   ?>      
                                            </td>
                                            <td style="float:right;"><?php echo number_format($expenses_doc->debit1,2); ?></td>
                                          </tr> 
                                          <?php
                                          $tot_exp = $tot_exp+$expenses_doc->debit;
                                          $i++;
                                        } ?>

                                        <tr>
                                          <td colspan="5"></td>
                                          <td>
                                            <strong>Total:</strong>
                                            <span><?php echo number_format($tot_exp,2); ?></span>
                                          </td>
                                        </tr>
                                      

                                    <?php $grand_tot = $grand_tot+$tot_exp; } ?>

                                   
                                    <footer style="float:left;width:100%">
                                      <div style="float: right;width:130px;text-align: right;padding-right: 10px;">
                                        <table style="font-size:13px;text-align: right;">
                                          <tr>
                                            <td  style="float:left;border-top:1px solid black;" >
                                              <strong style="float:left;padding-right:1rem;">Total Expenses:</strong>
                                              <span style="float:right;"><?php echo number_format($grand_tot,2); ?></span>
                                            </td>
                                          </tr>
                                        </table>
                                      </div>

                                    </footer>
                                  <?php } ?>
                                   </tbody>
                                    </table>

                                  <footer style="float:left;width:100%">
                                    <div style="float: right;width:130px;text-align: right;padding-right: 10px;">
                                      <table style="font-size:13px;text-align: right;">
                                        <tr>
                                          <td  style="float:left;border-top:1px solid black;" >
                                            <strong style="float:left;padding-right:1rem;">Grand Total:</strong>
                                            <span style="float:right;"><?php 
                                            $final_grand_total = $grand_collection_total-$grand_tot;
                                            echo number_format($final_grand_total,2);?></span>
                                          </td>
                                        </tr>
                                      </table>
                                    </div>

                                  </footer>

                                </div> 
       
       <!--expense list -->


      </page>

    </body>
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