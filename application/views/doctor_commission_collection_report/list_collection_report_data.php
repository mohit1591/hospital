<?php  $users_data = $this->session->userdata('auth_users'); ?>
<!DOCTYPE html>
<html>
    
     <table cellpadding="0" cellspacing="0" border="0" width="100%">
      <tr>
        <td style="text-align:center;font-size:18px;"><span style="border-bottom:2px solid #111;">Doctor Commission & Collection Report</span></td>
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
   
    $grand_total_amount_collection=0;
    $grand_discount_collection=0;
    $grand_net_collection=0;
    $grand_paid_collection=0;
    $grand_balance_collection=0;
    $grand_commission_collection=0;
    $grand_collection_collection=0;
  if(!empty($self_opd_collection_list['self_opd_coll']) || !empty($self_billing_collection_list['self_bill_coll']) || !empty($self_medicine_collection_list['med_coll']) || !empty($self_ipd_collection_list['ipd_coll']) || !empty($self_medicine_return_collection_list) || !empty($pathology_self_collection_list['path_coll']) || !empty($self_ot_collection_list['self_ot_coll']) || !empty($self_vaccination_collection_list['vaccine_coll']) || !empty($self_blood_bank_collection_list['self_blood_bank_collection']) || !empty($self_ambulance_collection_list['self_ambulance_coll']) || !empty($self_dialysis_collection_list['self_dialysis_coll']) )
  {

    ?>
   <table cellpadding="4" style="width:100%;font-size:13px;font-family: arial;">
          <thead>
        <tr>
            <td style="border-bottom: 1px solid black;">S. No.</td>
            <td style="border-bottom: 1px solid black;">Patient Name</td>
            <td style="border-bottom: 1px solid black;">Booking Code</td>
            <td style="border-bottom: 1px solid black;">Doctor/Hospital</td>
            <td style="border-bottom: 1px solid black;">Date</td>
            <td style="border-bottom: 1px solid black;">Total Amount</td>
            <td style="border-bottom: 1px solid black;">Discount</td>
            <td style="border-bottom: 1px solid black;">Net Amount</td>
            <td style="border-bottom: 1px solid black;">Paid Amount</td>
            <td style="border-bottom: 1px solid black;">Balance</td>
            <td style="border-bottom: 1px solid black;">Commission</td>
            <td style="border-bottom: 1px solid black;">Collection</td>
            <td style="border-bottom: 1px solid black;">Payment Mode</td>
            
            
          </tr>
           </thead>
           
           <?php 
           $mode_check=1;
          if(!empty($self_opd_collection_list['self_opd_coll']))
          {
           ?>
          
           <tbody>
          
            <tr>
              <td style="padding:5px 0"><u>OPD</u></td>
            </tr>

            
            <?php 
            $k = 1 ;
            $total_amount_opd_row=0;
            $discount_amount_opd_row=0;
            $net_amount_opd_row=0;
            $self_total = 0;
            $balance_opd_row = 0;
            $commission_opd_row = 0;
            $self_collections_row = 0;
            $self_counter = count($self_opd_collection_list['self_opd_coll']);
            foreach($self_opd_collection_list['self_opd_coll'] as $collection)
            { 
            
            ?>
            <tr>
           
              <td width="10" align="center"><?php echo $k; ?></td>
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              <!--<td>< ?php echo $collection->doctor_name; ?></td>-->
              <td><?php echo $collection->doctor_hospital_name; ?></td>
              <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
              <td><?php echo $collection->total_amount; ?></td>
              <td><?php echo $collection->discount_amount; ?></td>
              <td><?php echo $collection->net_amount; ?></td>
              <td><?php echo $collection->debit; ?></td>
              <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $bal ='0.00'; }else{ echo $bal = number_format($collection->balance,2); } ?></td>
              <td><?php echo $collection->total_comission; ?></td>
               <td><?php $collections_row = $collection->debit-$collection->total_comission; echo number_format($collections_row,2); ?></td>
              <td><?php echo $collection->mode; ?></td>
              
            </tr>
           
        <?php $k++;
        $self_total = $self_total+$collection->debit;
        $self_collections_row = $self_collections_row+$collections_row;
        $total_amount_opd_row=$total_amount_opd_row+$collection->total_amount;
        $discount_amount_opd_row=$discount_amount_opd_row+$collection->discount_amount;
        $net_amount_opd_row=$net_amount_opd_row+$collection->net_amount;
        $balance_opd_row = $balance_opd_row+$bal;
        $commission_opd_row = $commission_opd_row+$collection->total_comission;
            
        
     } ?>


           </tbody>
         <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?>
              
              
                <tr>
                    <td colspan="5" align="right">Total</td>
                    <td><?php echo number_format($total_amount_opd_row,2); ?></td>
                    <td><?php echo number_format($discount_amount_opd_row,2); ?></td>
                    <td><?php echo number_format($net_amount_opd_row,2); ?></td>
                    <td><?php echo number_format($self_total,2); ?></td>
                    <td><?php echo number_format($balance_opd_row,2); ?></td>
                    <td><?php echo number_format($commission_opd_row,2); ?></td>
                    <td><?php echo number_format($self_collections_row,2); ?></td>
                    <td>&nbsp;</td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
            
            
        $grand_total_amount_collection=$grand_total_amount_collection+$total_amount_opd_row;
        $grand_discount_collection=$grand_discount_collection+$discount_amount_opd_row;
        $grand_net_collection=$grand_net_collection+$net_amount_opd_row;
        $grand_paid_collection=$grand_paid_collection+$self_total;
        $grand_balance_collection=$grand_balance_collection+$balance_opd_row;
        $grand_commission_collection=$grand_commission_collection+$commission_opd_row;
        $grand_collection_collection=$grand_collection_collection+$self_collections_row;
    
           
  } ?>
       <!-- opd collection -->
       
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
            
            $self_ipd_total_collection=0;
            $self_ipd_discount_collection=0;
            $self_ipd_net_collection=0;
            $self_ipd_paid_collection=0;
            $self_ipd_balance_collection=0;
            $self_ipd_commission_collection=0;
            $self_ipd_collection_collection=0;
            $self_counter = count($self_ipd_collection_list['ipd_coll']);

            foreach($self_ipd_collection_list['ipd_coll'] as $collection)
            {
            
            ?>
            <tr>
              <td width="10" align="center"><?php echo $k; ?></td>
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              <!--<td>< ?php echo $collection->doctor_name; ?></td>-->
              <td><?php echo $collection->doctor_hospital_name; ?></td>
              <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
              
              <td><?php echo $collection->total_amount; ?></td>
              <td><?php echo $collection->discount_amount; ?></td>
              <td><?php echo $collection->net_amount; ?></td>
              <td><?php echo $collection->debit; ?></td>
              <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $bal= '0.00'; }else{ echo $bal =number_format($collection->balance,2); } ?></td>
              <td><?php echo $collection->total_comission; ?></td>
              <td><?php $self_ipd_col = $collection->debit-$collection->total_comission; echo number_format($self_ipd_col,2); ?></td>
              <td><?php echo $collection->mode; ?></td>
            </tr>
           
        <?php $k++; 
              
              $self_ipd_total_collection=$self_ipd_total_collection+$collection->debit;
            $self_ipd_discount_collection=$self_ipd_discount_collection+$collection->discount_amount;
            $self_ipd_net_collection=$self_ipd_net_collection+$collection->net_amount;
            $self_ipd_paid_collection=$self_ipd_paid_collection+$collection->debit;
            $self_ipd_balance_collection=$self_ipd_balance_collection+$bal;
            $self_ipd_commission_collection=$self_ipd_commission_collection+$collection->total_comission;
            $self_ipd_collection_collection=$self_ipd_collection_collection+$self_ipd_col;
            
            
             
     } ?>



           </tbody>
         
            
              <?php
              if($mode_check==1)
              {
              
              ?>
               <tr>
                    <td colspan="5" align="right">Total</td>
                    <td><?php echo number_format($self_ipd_total_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_discount_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_net_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_paid_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_balance_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_commission_collection,2); ?></td>
                    <td><?php echo number_format($self_ipd_collection_collection,2); ?></td>
                    <td>&nbsp;</td>
                </tr>
              <!-- </div> -->
              <?php 
            }
            
         $grand_total_amount_collection=$grand_total_amount_collection+$self_ipd_total_collection;
        $grand_discount_collection=$grand_discount_collection+$self_ipd_discount_collection;
        $grand_net_collection=$grand_net_collection+$self_ipd_net_collection;
        $grand_paid_collection=$grand_paid_collection+$self_ipd_paid_collection;
        $grand_balance_collection=$grand_balance_collection+$self_ipd_balance_collection;
        $grand_commission_collection=$grand_commission_collection+$self_ipd_commission_collection;
        $grand_collection_collection=$grand_collection_collection+$self_ipd_collection_collection;
      } ?>

              <!-- ipd end -->
           
           <!-- self opd billing collection -->
           
        <?php 
          
         if(!empty($self_billing_collection_list['self_bill_coll']))
         {
           ?>
      
      
           <tbody>
           
            <tr>
              <td style="padding:5px 0"><u>Billing</u></td>
            </tr>

            
            <?php 
            $l = 1 ;
            $self_billing_total_collection=0;
            $self_billing_discount_collection=0;
            $self_billing_net_collection=0;
            $self_billing_paid_collection=0;
            $self_billing_balance_collection=0;
            $self_billing_commission_collection=0;
            $self_billing_collection_collection=0;
            $self_billing_counter = count($self_billing_collection_list['self_bill_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_billing_collection_list['self_bill_coll'] as $billing_collection)
            {
            
            ?>
            <tr>
            
              <td width="10" align="center"><?php echo $l; ?></td>
              <td><?php echo wordwrap(trim($billing_collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $billing_collection->booking_code; ?></td>
              <!--<td>< ?php echo $billing_collection->doctor_name; ?></td>-->
              <td><?php echo $billing_collection->doctor_hospital_name; ?></td>
              <td><?php echo date('d-m-Y', strtotime($billing_collection->created_date)); ?></td>
              <td><?php echo $billing_collection->total_amount; ?></td>
              <td><?php echo $billing_collection->discount_amount; ?></td>
              <td><?php echo $billing_collection->net_amount; ?></td>
              <td><?php echo $billing_collection->debit; ?></td>
              <td><?php if($billing_collection->balance=='1.00' || $billing_collection->balance=='0.00'){ echo $bal= '0.00'; }else{ echo $bal =number_format($billing_collection->balance,2); } ?></td>
              <td><?php echo $billing_collection->total_comission; ?></td>
              <td><?php  $tot = $billing_collection->debit-$billing_collection->total_comission; echo number_format($tot,2);?></td>
              <td><?php echo $billing_collection->mode; ?></td>
            </tr>
           
        <?php $l++; 
            $self_billing_total_collection=$self_billing_total_collection+$billing_collection->total_amount;
            $self_billing_discount_collection=$self_billing_discount_collection+$billing_collection->discount_amount;
            $self_billing_net_collection=$self_billing_net_collection+$billing_collection->net_amount;
            $self_billing_paid_collection=$self_billing_paid_collection+$billing_collection->debit;
            $self_billing_balance_collection=$self_billing_balance_collection+$bal;
            $self_billing_commission_collection=$self_billing_commission_collection+$billing_collection->total_comission;
            $self_billing_collection_collection=$self_billing_collection_collection+$tot;
     } ?>



        
</tbody>
        
              <?php
              if($mode_check==1)
              {
              
              ?>
             <tr>
                    <td colspan="5" align="right">Total</td>
                    <td><?php echo number_format($self_billing_total_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_discount_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_net_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_paid_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_balance_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_commission_collection,2); ?></td>
                    <td><?php echo number_format($self_billing_collection_collection,2); ?></td>
                    <td>&nbsp;</td>
                </tr>
              
              <!-- </div> -->
              <?php 
            }
           
          $grand_total_amount_collection=$grand_total_amount_collection+$self_billing_total_collection;
        $grand_discount_collection=$grand_discount_collection+$self_billing_discount_collection;
        $grand_net_collection=$grand_net_collection+$self_billing_net_collection;
        $grand_paid_collection=$grand_paid_collection+$self_billing_paid_collection;
        $grand_balance_collection=$grand_balance_collection+$self_billing_balance_collection;
        $grand_commission_collection=$grand_commission_collection+$self_billing_commission_collection;
        $grand_collection_collection=$grand_collection_collection+$self_billing_collection_collection;
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
            $self_med_total_collection=0;
            $self_med_discount_collection=0;
            $self_med_net_collection=0;
            $self_med_paid_collection=0;
            $self_med_balance_collection=0;
            $self_med_commission_collection=0;
            $self_med_collection_collection=0;
            $self_med_counter = count($self_medicine_collection_list['med_coll']);
            //echo "<pre>";print_r($self_collection_list);
            foreach($self_medicine_collection_list['med_coll'] as $med_collection)
            {
            
            ?>
            <tr>
             
              <td width="10" align="center"><?php echo $m; ?></td>
              <td><?php echo wordwrap(trim($med_collection->patient_name),10,'<br>'); ?></td>
               <td><?php echo $med_collection->booking_code; ?></td>
               <!--<td>< ?php echo $med_collection->doctor_name; ?></td>-->
              <td><?php echo $med_collection->doctor_hospital_name; ?></td>
              <td><?php echo date('d-m-Y', strtotime($med_collection->created_date)); ?></td>
               <td><?php echo $med_collection->total_amount; ?></td>
              <td><?php echo $med_collection->discount_amount; ?></td>
              <td><?php echo $med_collection->net_amount; ?></td>
              <td><?php echo $med_collection->debit; ?></td>
              <td><?php if($med_collection->balance=='1.00' || $med_collection->balance=='0.00'){ echo $bal = '0.00'; }else{ echo $bal = number_format($med_collection->balance,2); } ?></td>
              <td><?php echo $med_collection->total_comission; ?></td>
              <td><?php  $totl = $med_collection->debit-$med_collection->total_comission; echo number_format($totl,2);?></td>
              <td><?php echo $med_collection->mode; ?></td>  
            </tr>
           
        <?php $m++; 
            $self_med_total_collection=$self_med_total_collection+$med_collection->total_amount;
            $self_med_discount_collection=$self_med_discount_collection+$med_collection->discount_amount;
            $self_med_net_collection=$self_med_net_collection+$med_collection->net_amount;
            $self_med_paid_collection=$self_med_paid_collection+$med_collection->debit;
            $self_med_balance_collection=$self_med_balance_collection+$bal;
            $self_med_commission_collection=$self_med_commission_collection+$med_collection->total_comission;
            $self_med_collection_collection=$self_med_collection_collection+$totl;
          } ?>

           </tbody>
          
            
              <?php
              if($mode_check==1)
              {
              
              ?>
               <tr>
                    <td colspan="5" align="right">Total</td>
                    <td><?php echo number_format($self_med_total_collection,2); ?></td>
                    <td><?php echo number_format($self_med_discount_collection,2); ?></td>
                    <td><?php echo number_format($self_med_net_collection,2); ?></td>
                    <td><?php echo number_format($self_med_paid_collection,2); ?></td>
                    <td><?php echo number_format($self_med_balance_collection,2); ?></td>
                    <td><?php echo number_format($self_med_commission_collection,2); ?></td>
                    <td><?php echo number_format($self_med_collection_collection,2); ?></td>
                    <td>&nbsp;</td>
                </tr>
              <?php 
            }
            
           $grand_total_amount_collection=$grand_total_amount_collection+$self_med_total_collection;
        $grand_discount_collection=$grand_discount_collection+$self_med_discount_collection;
        $grand_net_collection=$grand_net_collection+$self_med_net_collection;
        $grand_paid_collection=$grand_paid_collection+$self_med_paid_collection;
        $grand_balance_collection=$grand_balance_collection+$self_med_balance_collection;
        $grand_commission_collection=$grand_commission_collection+$self_med_commission_collection;
        $grand_collection_collection=$grand_collection_collection+$self_med_collection_collection;
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
            $self_path_total_collection=0;
            $self_path_discount_collection=0;
            $self_path_net_collection=0;
            $self_path_paid_collection=0;
            $self_path_balance_collection=0;
            $self_path_commission_collection=0;
            $self_path_collection_collection=0;
            $self_counter = count($pathology_self_collection_list['path_coll']);
            foreach($pathology_self_collection_list['path_coll'] as $collection)
            { 
            
            ?>
            <tr>
              
              <td width="10" align="center"><?php echo $k; ?></td>
              <td><?php echo wordwrap(trim($collection->patient_name),10,'<br>'); ?></td>
              <td><?php echo $collection->booking_code; ?></td>
              
              
             <!--<td>< ?php echo $collection->doctor_name; ?></td>-->
             <td><?php echo $collection->doctor_hospital_name; ?></td>
             <td><?php echo date('d-m-Y', strtotime($collection->created_date)); ?></td>
            <td><?php echo $collection->total_amount; ?></td>
             <td><?php echo $collection->discount_amount; ?></td>
             <td><?php echo $collection->net_amount; ?></td>
             <td><?php echo $collection->debit; ?></td>
             <td><?php if($collection->balance=='1.00' || $collection->balance=='0.00'){ echo $bal = '0.00'; }else{ echo $bal= number_format($collection->balance,2); } ?></td>
             <td><?php echo $collection->total_comission; ?></td>
             <td><?php  $tot_p = $collection->debit-$collection->total_comission; echo number_format($tot_p,2); ?></td>
             <td><?php echo $collection->mode; ?></td>
             
            </tr>
           
        <?php $k++;
            $self_path_total_collection=$self_path_total_collection+$collection->total_amount;
            $self_path_discount_collection=$self_path_discount_collection+$collection->discount_amount;
            $self_path_net_collection=$self_path_net_collection+$collection->net_amount;
            $self_path_paid_collection=$self_path_paid_collection+$collection->debit;
            $self_path_balance_collection=$self_path_balance_collection+$bal;
            $self_path_commission_collection=$self_path_commission_collection+$collection->total_comission;
            $self_path_collection_collection=$self_path_collection_collection+$tot_p;
     } ?>
        </tbody>
 
         
            
              <?php
              if($mode_check==1)
              {
              $p_m_k=1;
              ?><tr>
              <td colspan="5" align="right">Total</td>
                    <td><?php echo number_format($self_path_total_collection,2); ?></td>
                    <td><?php echo number_format($self_path_discount_collection,2); ?></td>
                    <td><?php echo number_format($self_path_net_collection,2); ?></td>
                    <td><?php echo number_format($self_path_paid_collection,2); ?></td>
                    <td><?php echo number_format($self_path_balance_collection,2); ?></td>
                    <td><?php echo number_format($self_path_commission_collection,2); ?></td>
                    <td><?php echo number_format($self_path_collection_collection,2); ?></td>
                    <td>&nbsp;</td>
                    </tr>
              <?php 
            }
           
        $grand_total_amount_collection=$grand_total_amount_collection+$self_path_total_collection;
        $grand_discount_collection=$grand_discount_collection+$self_path_discount_collection;
        $grand_net_collection=$grand_net_collection+$self_path_net_collection;
        $grand_paid_collection=$grand_paid_collection+$self_path_paid_collection;
        $grand_balance_collection=$grand_balance_collection+$self_path_balance_collection;
        $grand_commission_collection=$grand_commission_collection+$self_path_commission_collection;
        $grand_collection_collection=$grand_collection_collection+$self_path_collection_collection;
       } ?>

    <!---pathology end -->
    

     
            <tr>
              <td colspan="5" align="right" style="border-top:1px solid black;">Grand Total</td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_total_amount_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_discount_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_net_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_paid_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_balance_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_commission_collection,2); ?></td>
                    <td style="border-top:1px solid black;"><?php echo number_format($grand_collection_collection,2); ?></td>
                    <td style="border-top:1px solid black;">&nbsp;</td>
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