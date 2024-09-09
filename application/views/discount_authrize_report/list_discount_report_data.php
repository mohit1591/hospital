<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
    
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Authorize Discount Report</span></td>
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
 
            $grand_collection_total = 0;

  if(!empty($self_opd_collection_list['self_opd_coll']) || !empty($self_billing_collection_list['self_bill_coll']) || !empty($self_medicine_collection_list['med_coll']) || !empty($self_ipd_collection_list['ipd_coll']) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list['path_coll']) || !empty($self_ot_collection_list['self_ot_coll']) || !empty($self_vaccination_collection_list['vaccine_coll']) || !empty($self_blood_bank_collection_list['self_blood_bank_collection']) || !empty($self_ambulance_collection_list['self_ambulance_coll']) || !empty($self_dialysis_collection_list['self_dialysis_coll']) )
  {

    ?>
   <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">
          <thead>
        <tr>
            <td style="border-bottom: 1px solid black;"><strong>S.No.</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Patient Name</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Patient Category</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Authorize Person</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Patient Code</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Booking Code</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Date</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Total Amount</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Discount</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Net Amount</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Paid Amount</strong></td>
            <td style="border-bottom: 1px solid black;"><strong>Balance</strong></td>
            
        </tr>
           </thead>
           
           <?php 
           
          if(!empty($self_opd_collection_list['self_opd_coll']))
          {
           ?>
          
           <tbody>
           <tr>
              <td style="padding:5px 0"><u>OPD</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $self_total_amt = 0;
            $self_total_dis = 0;
            $self_total_net = 0;
            $self_total_pai = 0;
            $self_total_bal = 0;
            $self_counter = count($self_opd_collection_list['self_opd_coll']);
            foreach($self_opd_collection_list['self_opd_coll'] as $collection)
            { 
            
            ?>
            <tr>
           <td width="10" align="center"><?php echo $k; ?></td>
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->patient_category_name; ?></td>
              <td><?php echo $collection->authorize_person_name; ?></td>
              <td><?php echo $collection->patient_code; ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
              <td><?php echo $collection->total_amount; ?></td>
              <td><?php echo $collection->discount_amount; ?></td>
              <td><?php echo $collection->net_amount; ?></td>
              <td><?php echo $collection->debit; ?></td>
              <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $balc = '0.00'; }else{ $balc =$collection->balance; echo number_format($collection->balance,2); } ?></td>
              
            </tr>
           
        <?php $k++;
        $self_total_amt = $self_total_amt+$collection->total_amount;
        $self_total_dis = $self_total_dis+$collection->discount_amount;
        $self_total_net = $self_total_net+$collection->net_amount;
        $self_total_pai = $self_total_pai+$collection->debit;
        $self_total_bal = $self_total_bal+$balc;
     } ?>


           </tbody>
        
              <tr>
               <td colspan="6" align="right">&nbsp;</td>
        <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;">
                <strong style="float:left;">Total:</strong></div></td>
                <td><?php echo number_format($self_total_amt,2); ?></td>
                    
                 <td><?php echo number_format($self_total_dis,2); ?></td>
                    
                <td><?php echo number_format($self_total_net,2); ?></td>
                <td><?php echo number_format($self_total_pai,2); ?></td>
                <td><?php echo number_format($self_total_bal,2); ?></td>
                </tr>
              
              <!-- </div> -->
              <?php 
            
    
     $grand_total_amount_collection_total = $grand_total_amount_collection_total+$self_total_amt; 
      $grand_discount_collection_total = $grand_discount_collection_total+$self_total_dis; 
      $grand_net_amount_collection_total = $grand_net_amount_collection_total+$self_total_net; 
     $grand_collection_total = $grand_collection_total+$self_total_pai;
     
     $grand_collection_balance_total = $grand_collection_balance_total+$self_total_bal;
     
    
  } ?>
       <!-- opd collection -->
           
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
            $self_billing_total_amt = 0;
            $self_billing_total_dis = 0;
            $self_billing_total_net = 0;
            $self_billing_total_pai = 0;
            $self_billing_total_bal = 0;
            
            $self_billing_counter = count($self_billing_collection_list['self_bill_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_billing_collection_list['self_bill_coll'] as $billing_collection)
            {
            
            ?>
            <tr>
             
               
              <td width="10" align="center"><?php echo $l; ?></td>
             
              <td><?php echo wordwrap(trim($billing_collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $billing_collection->patient_category_name; ?></td>
              <td><?php echo $billing_collection->authorize_person_name; ?></td>
              <td><?php echo $billing_collection->patient_code; ?></td>
              <td><?php echo $billing_collection->booking_code; ?></td>
              <td><?php echo date('d-m-Y', strtotime($billing_collection->created_date)); ?></td>
              <td><?php echo $billing_collection->total_amount; ?></td>
              <td><?php echo $billing_collection->discount_amount; ?></td>
              <td><?php echo $billing_collection->net_amount; ?></td>
              <td><?php echo $billing_collection->debit; ?></td>
              <td><?php if($billing_collection->balance=='1.00' || $billing_collection->balance=='0.00'){ echo $balc = '0.00'; }else{ $balc = $billing_collection->balance; echo number_format($billing_collection->balance,2); } ?></td>
              
             
            </tr>
           
        <?php $l++; 
        
          
        $self_billing_total_amt = $self_billing_total_amt+$billing_collection->total_amount;
        $self_billing_total_dis = $self_billing_total_dis+$billing_collection->discount_amount;
        $self_billing_total_net = $self_billing_total_net+$billing_collection->net_amount;
        $self_billing_total_pai = $self_billing_total_pai+$billing_collection->debit;
        $self_billing_total_bal = $self_billing_total_bal+$balc;
          
     } ?>


    </tbody>
        <tr>
        <td colspan="6" align="right">&nbsp;</td>
        <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;">
                <strong style="float:left;">Total:</strong></div></td>
        <td><?php echo number_format($self_billing_total_amt,2); ?></td>
        
        <td><?php echo number_format($self_billing_total_dis,2); ?></td>
        
        <td><?php echo number_format($self_billing_total_net,2); ?></td>
        <td><?php echo number_format($self_billing_total_pai,2); ?></td>
        <td><?php echo number_format($self_billing_total_bal,2); ?></td>
        </tr>
              
      <!-- </div> -->
      <?php 
            
$grand_total_amount_collection_total = $grand_total_amount_collection_total+$self_billing_total_amt; 
$grand_discount_collection_total = $grand_discount_collection_total+$self_billing_total_dis; 
$grand_net_amount_collection_total = $grand_net_amount_collection_total+$self_billing_total_net; 
$grand_collection_total = $grand_collection_total+$self_billing_total_pai;

$grand_collection_balance_total = $grand_collection_balance_total+$self_billing_total_bal;
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
             $self_med_total_amt = 0;
            $self_med_total_dis = 0;
            $self_med_total_net = 0;
            $self_med_total_pai = 0;
            $self_med_total_bal = 0;
            $self_med_counter = count($self_medicine_collection_list['med_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_medicine_collection_list['med_coll'] as $med_collection)
            {
            
            ?>
            <tr>
            
              <td width="10" align="center"><?php echo $m; ?></td>
              
              <td><?php echo wordwrap(trim($med_collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $med_collection->patient_category_name; ?></td>
              <td><?php echo $med_collection->authorize_person_name; ?></td>
              <td><?php echo $med_collection->patient_code; ?></td>
              <td><?php echo $med_collection->booking_code; ?></td>
              <td><?php echo date('d-m-Y', strtotime($med_collection->created_date)); ?></td>
              
              <td><?php echo $med_collection->total_amount; ?></td>
              <td><?php echo $med_collection->discount_amount; ?></td>
              <td><?php echo $med_collection->net_amount; ?></td>
              <td><?php echo $med_collection->debit; ?></td>
              <td><?php if($med_collection->balance=='1.00' || $med_collection->balance=='0.00'){ echo $balc = '0.00'; }else{ $balc = $med_collection->balance; echo number_format($med_collection->balance,2); } ?></td>
              
             
            </tr>
           
        <?php $m++; 
            $self_med_total_amt = $self_med_total_amt+$med_collection->total_amount;
            $self_med_total_dis = $self_med_total_dis+$med_collection->discount_amount;
            $self_med_total_net = $self_med_total_net+$med_collection->net_amount;
            $self_med_total_pai = $self_med_total_pai+$med_collection->debit;
            $self_med_total_bal = $self_med_total_bal+$balc;
          } ?>

           </tbody>
          
     <tr>
        <td colspan="6" align="right">&nbsp;</td>
        <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;">
                <strong style="float:left;">Total:</strong></div></td>
        <td><?php echo number_format($self_med_total_amt,2); ?></td>
        <td><?php echo number_format($self_med_total_dis,2); ?></td>
        <td><?php echo number_format($self_med_total_net,2); ?></td>
        <td><?php echo number_format($self_med_total_pai,2); ?></td>
        <td><?php echo number_format($self_med_total_bal,2); ?></td>
        </tr>
        
              
    <?php 
    $grand_total_amount_collection_total = $grand_total_amount_collection_total+$self_med_total_amt; 
    $grand_discount_collection_total = $grand_discount_collection_total+$self_med_total_dis; 
    $grand_net_amount_collection_total = $grand_net_amount_collection_total+$self_med_total_net; 
    $grand_collection_total = $grand_collection_total+$self_med_total_pai;
    
    $grand_collection_balance_total = $grand_collection_balance_total+$self_med_total_bal;
         } ?>
        <!-- end self medicine collection -->
        
        <!-- self ipd collection -->
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
              <td width="10" align="center"><?php echo $k; ?></td>
             
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->patient_category_name; ?></td>
              <td><?php echo $collection->authorize_person_name; ?></td>
              <td><?php echo $collection->patient_code; ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
              
              <td><?php echo $collection->total_amount; ?></td>
              <td><?php echo $collection->discount_amount; ?></td>
              <td><?php echo $collection->net_amount; ?></td>
              <td><?php echo $collection->debit; ?></td>
              <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $balc ='0.00'; }else{ $balc = $collection->balance; echo number_format($collection->balance,2); } ?></td>
              
            </tr>
           
        <?php $k++; 
        $self_ipd_total_amt = $self_ipd_total_amt+$collection->total_amount;
        $self_ipd_total_dis = $self_ipd_total_dis+$collection->discount_amount;
        $self_ipd_total_net = $self_ipd_total_net+$collection->net_amount;
        $self_ipd_total_pai = $self_ipd_total_pai+$collection->debit;
        $self_ipd_total_bal = $self_ipd_total_bal+$balc;
     } ?>



           </tbody>
         
          <tr>
        <td colspan="6" align="right">&nbsp;</td>
        <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;">
                <strong style="float:left;">Total:</strong></div></td>
        <td><?php echo number_format($self_ipd_total_amt,2); ?></td>
        <td><?php echo number_format($self_ipd_total_dis,2); ?></td>
        <td><?php echo number_format($self_ipd_total_net,2); ?></td>
        <td><?php echo number_format($self_ipd_total_pai,2); ?></td>
        <td><?php echo number_format($self_ipd_total_bal,2); ?></td>
        </tr>
        
        <?php 
        $grand_total_amount_collection_total = $grand_total_amount_collection_total+$self_ipd_total_amt; 
    $grand_discount_collection_total = $grand_discount_collection_total+$self_ipd_total_dis; 
    $grand_net_amount_collection_total = $grand_net_amount_collection_total+$self_ipd_total_net; 
    $grand_collection_total = $grand_collection_total+$self_ipd_total_pai;
    
    $grand_collection_balance_total = $grand_collection_balance_total+$self_ipd_total_bal;
    }
        ?>
        <!-- end of self ipd-->
        
         
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
             $self_path_total_amt = 0;
            $self_path_total_dis = 0;
            $self_path_total_net = 0;
            $self_path_total_pai = 0;
            $self_path_total_bal = 0;
            $self_counter = count($pathology_self_collection_list['path_coll']);
            foreach($pathology_self_collection_list['path_coll'] as $collection)
            { 
            
            ?>
            <tr>
             
              <td width="10" align="center"><?php echo $k; ?></td>
             
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->patient_category_name; ?></td>
              <td><?php echo $collection->authorize_person_name; ?></td>
              <td><?php echo $collection->patient_code; ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
              
              <td><?php echo $collection->total_amount; ?></td>
              <td><?php echo $collection->discount_amount; ?></td>
              <td><?php echo $collection->net_amount; ?></td>
              <td><?php echo $collection->debit; ?></td>
              <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $balc ='0.00'; }else{ $balc = $collection->balance; echo number_format($collection->balance,2); } ?></td>
              
            </tr>
           
        <?php $k++;
        $self_path_total_amt = $self_path_total_amt+$collection->total_amount;
        $self_path_total_dis = $self_path_total_dis+$collection->discount_amount;
        $self_path_total_net = $self_path_total_net+$collection->net_amount;
        $self_path_total_pai = $self_path_total_pai+$collection->debit;
        $self_path_total_bal = $self_path_total_bal+$balc;
     } ?>
        </tbody>
        
         <tr>
        <td colspan="6" align="right">&nbsp;</td>
        <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;">
                <strong style="float:left;">Total:</strong></div></td>
        <td><?php echo number_format($self_path_total_amt,2); ?></td>
        <td><?php echo number_format($self_path_total_dis,2); ?></td>
        <td><?php echo number_format($self_path_total_net,2); ?></td>
        <td><?php echo number_format($self_path_total_pai,2); ?></td>
        <td><?php echo number_format($self_path_total_bal,2); ?></td>
        </tr>
 
         
            
    
      <?php 
$grand_total_amount_collection_total = $grand_total_amount_collection_total+$self_path_total_amt; 
$grand_discount_collection_total = $grand_discount_collection_total+$self_path_total_dis; 
$grand_net_amount_collection_total = $grand_net_amount_collection_total+$self_path_total_net; 
$grand_collection_total = $grand_collection_total+$self_path_total_pai;

$grand_collection_balance_total = $grand_collection_balance_total+$self_path_total_bal;

      } ?>

    <!---pathology end -->
    
       
       <!-- over all collection -->

       <tr>
              
              <td align="right" colspan="6"  style="border-top:1px solid black;" >&nbsp;</td>
              <td align="right"  style="border-top:1px solid black;" >
                  <div style="float:right;width:100px;">
                <strong style="float:left;">Grand Total:</strong>
                
                </div>
              </td>
              <td   style="border-top:1px solid black;" ><?php echo number_format($grand_total_amount_collection_total,2);?>
                  </td>
              <td  style="border-top:1px solid black;" ><?php echo number_format($grand_discount_collection_total,2);?>
                  </td>
            <td  style="border-top:1px solid black;" ><?php echo number_format($grand_net_amount_collection_total,2);?>
                  </td>
                  
            <td  style="border-top:1px solid black;" ><?php echo number_format($grand_collection_total,2);?>
                  </td>      
              <td style="border-top:1px solid black;"><?php echo number_format($grand_collection_balance_total,2);?></td>
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